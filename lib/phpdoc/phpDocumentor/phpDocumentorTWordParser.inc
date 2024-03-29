<?php
/**
 * tokenizer extension-based lexer for PHP code
 * 
 * phpDocumentor :: automatic documentation generator
 * 
 * PHP versions 4 and 5
 *
 * Copyright (c) 2002-2006 Gregory Beaver
 * 
 * LICENSE:
 * 
 * This library is free software; you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General
 * Public License as published by the Free Software Foundation;
 * either version 2.1 of the License, or (at your option) any
 * later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @category   ToolsAndUtilities
 * @package    phpDocumentor
 * @subpackage Parsers
 * @author     Gregory Beaver <cellog@php.net>
 * @copyright  2002-2007 Gregory Beaver
 * @license    http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version    CVS: $Id: phpDocumentorTWordParser.inc,v 1.1.6.2 2012/02/01 13:45:14 alair.tavares Exp $
 * @link       http://www.phpdoc.org
 * @link       http://pear.php.net/PhpDocumentor
 * @since      1.2
 * @todo       CS cleanup - change package to PhpDocumentor
 * @todo       CS cleanup - PHPCS needs to ignore CVS Id length
 */

/**
 * Like WordParser, but expects an array of tokens from the tokenizer 
 * instead of a string.
 *
 * @category   ToolsAndUtilities
 * @package    phpDocumentor
 * @subpackage WordParsers
 * @author     Gregory Beaver <cellog@php.net>
 * @copyright  2002-2007 Gregory Beaver
 * @license    http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version    Release: 1.4.3
 * @link       http://www.phpdoc.org
 * @link       http://pear.php.net/PhpDocumentor
 * @since      1.2
 * @todo       CS cleanup - change package to PhpDocumentor
 * @todo       CS cleanup - change classname to PhpDocumentor_*
 */
class phpDocumentorTWordParser extends WordParser
{
    /**#@+
     * @access private
     */
    /**
     * tokenized array from {@link token_get_all()}
     * @var array
     */
    var $_all;
    /**
     * List of tokens that can contain a newline
     * @var array
     */
    var $_nl_check = array(
        T_WHITESPACE,
        T_ENCAPSED_AND_WHITESPACE,
        T_CONSTANT_ENCAPSED_STRING,
        T_COMMENT,
        T_DOC_COMMENT,
        T_OPEN_TAG,
        T_CLOSE_TAG,
        T_INLINE_HTML,
        T_START_HEREDOC,
    );
    /**
     * @var array
     */
    var $_global_search;
    /**
     * current source line number (relative)
     * @var integer
     */
    var $_sourceline;
    /**
     * Source of the entire file, parsed into arrays of tokens on each line
     * @var array
     */
    var $_file_source = array();
    /**
     * Line number the last comment was on
     * @var integer
     */
    var $_docblock_linenum;
    /**#@-*/
    
    /**
     * Uses {@link token_get_all()} to tokenize the source code.
     * {@internal
     * Also, it divides the source tokens into separate lines for use by
     * the @filesource tag.
     *
     * {@source}}}
     *
     * @param string &$input source code
     *
     * @return void
     */
    function setup(&$input)
    {
        $input      = rtrim(ltrim($input, "\r\n"));
        $this->data = &$input;
        // fix php warnings on invalid source code
        $this->_all         = @token_get_all($input);
        $this->_file_source = array();
        $this->addFileSource($this->_all);
        $this->_sourceline = 0;
        $this->pos         = 0;
        $this->linenum     = 0;
    }
    
    /**
     * loads up next set of source code
     *
     * @return array source code array
     */
    function getSource()
    {
        $source          = $this->source;
        $this->source    = array();
        $this->getsource = false;
        return $source;
    }
    
    /**
     * gets the source code tokens
     *
     * @return array source code tokens split up by line number
     */
    function getFileSource()
    {
        return $this->_file_source;
    }
    
    /**
     * Begin retrieving source code
     *
     * @param string $word word to add the beginning of source code
     *
     * @return void
     * @access private
     * @todo   CS cleanup - rename to retrieveSource for camelCase rule
     */
    function retrievesource($word = '')
    {
        $this->source      = array(array($word));
        $this->_sourceline = 0;
        $this->getsource   = true;
    }

    /**
     * Utility function to determine whether two tokens from the tokenizer are equal
     *
     * @param mixed $a first token
     * @param mixed $b second token
     *
     * @return bool whether or not the tokens are equal
     * @static
     */
    function tokenEquals($a, $b)
    {
        if (is_array($a)) $a = $a[1];
        if (is_array($b)) $b = $b[1];
        return $a == $b;
    }
    
    /**
     * Utility function to convert a series of tokens into a string
     *
     * @param array $a array of tokens
     *
     * @return string the resulting string
     * @static
     */
    function concatTokens($a)
    {
        $b = '';
        foreach ($a as $c) {
            if (is_array($c)) {
                $c = $c[1];
            }
            $b .= $c;
        }
        return $b;
    }
    
    /**
     * Retrieve a token for the phpDocumentorTParser
     * {@internal
     * This method adds source code to the array for a function to be returned
     * to a {@}source} tag, and will return the token unless it is T_WHITESPACE
     * and {@link $returnWhiteSpace} is false.
     *
     * The global variable search is more complicated than it is in the
     * WordParser, as we have to compare an array of tokens to each other, and
     * that is what this code does}}
     *
     * @return string|array token from tokenizer
     */
    function getWord()
    {
        if (!isset($this->_all[$this->pos])) {
            return false;
        }

        $oldlinenum = $this->linenum;
        $word       = $this->_all[$this->pos++];

        // if we're looking for a global variable declaration, then this section
        // will search the upcoming tokens to see if they match the tokens
        // that define the global variable
        if (isset($this->_global_search)) {
            $pos   = $this->pos;
            $gpos  = 0;
            $found = false;
            if ($this->tokenEquals($word, $this->_global_search[$gpos++])) {
                $found = true;
                for (;$gpos<count($this->_global_search);$gpos++, $pos++) {
                    if (isset($this->_all[$pos]) &&
                        !$this->tokenEquals($this->_global_search[$gpos], 
                            $this->_all[$pos])
                    ) {
                        $found = false;
                    }
                }
            }
            if ($found) {
                $a          = $this->concatTokens($this->_global_search);
                $this->pos += count($this->_global_search) - 1;
                unset($this->_global_search);
                return $a;
            }
        }
        if ($this->getsource) {
            $this->addSource($word);
        }
        if (is_array($word)) {
            if (in_array($word[0], $this->_nl_check)) {
                $this->linenum += substr_count($word[1], "\n");
            }
            if ($word[0] == T_WHITESPACE && !$this->returnWhiteSpace) {
                return $this->getWord();
            }
            // seeing if we can get line numbers out of the beast
        }
        if (is_array($word) && $word[0] == T_COMMENT) {
            $this->_docblock_linenum = $oldlinenum;
        }
        return $word;
    }
    
    /**
     * Wrapper for {@link addSource()} used to retrieve the entire source code
     * organized by line number in setup()
     *
     * @param array $word full file source code
     *
     * @return void
     */
    function addFileSource($word)
    {
        $this->_sourceline = 0;
        foreach ($word as $token) {
            $this->addSource($token, true);
        }
        // var_dump($this->_file_source);
    }
    
    /**
     * Generate source token arrays organized by line number
     *
     * This code will split up tokens that contain "\n" and add them to the
     * source code as separate tokens on different lines.
     *
     * @param array|string $word token to add
     * @param bool         $file true if this should be added 
     *                           to {@link $_file_source}
     *
     * @return void
     * @uses _set_sars()
     */
    function addSource($word, $file = false)
    {
        if (is_array($word)) {
            $lines = str_replace("\r", '', explode("\n", $word[1]));
            foreach ($lines as $i => $line) {
                $this->_set_sars($file, array($word[0], $line));
                if ($i < count($lines) - 1) {
                    // increment sourceline
                    $this->_sourceline++;
                }
            }
        } else {
            $this->_set_sars($file, $word);
        }
    }
    
    /**
     * Add tokens to source code
     *
     * {@source}
     *
     * @param bool         $type true if this is file source,
     *                           otherwise it is function source
     * @param string|array $word token to add
     *
     * @return void
     * @access private
     * @todo CS cleanup - rename to _setSars for camelCasing rule
     */
    function _set_sars($type, $word)
    {
        if ($type) {
            $this->_file_source[$this->_sourceline][] = $word;
        } else {
            $this->source[$this->_sourceline][] = $word;
        }
    }
    
    /**
     * Tell the phpDocumentorTWordParser to return the entire global variable
     * if it is found.
     *
     * @param array $tokens tokens that represent the global variable definition
     *
     * @return void
     * @uses $_global_search
     */
    function findGlobal($tokens)
    {
        if (!$tokens) {
            unset($this->_global_search);
        } else {
            $this->_global_search = $tokens;
        }
    }
    
    /**
     * backs the parser up to the previous position
     *
     * @return int|void can return a word
     */
    function backupPos()
    {
        $this->pos--;
        $word = $this->_all[$this->pos];
        if ($this->getsource) {
            unset($this->source[$this->_sourceline]
                [count($this->source[$this->_sourceline]) - 1]);
            if (empty($this->source[$this->_sourceline])) {
                unset($this->source[$this->_sourceline]);
            } else {
                $this->source[$this->_sourceline] 
                    = array_values($this->source[$this->_sourceline]);
            }
        }
        if (is_array($word)) {
            if ($word[0] == T_WHITESPACE && !$this->returnWhiteSpace) {
                return $this->getWord();
            }
            // seeing if we can get line numbers out of the beast
            if (in_array($word[0], $this->_nl_check)) {
                $this->linenum -= substr_count($word[1], "\n");
            }
        }
    }
}
?>
