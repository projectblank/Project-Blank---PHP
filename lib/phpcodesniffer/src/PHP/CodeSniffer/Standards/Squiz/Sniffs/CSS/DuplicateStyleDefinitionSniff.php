<?php
/**
 * Squiz_Sniffs_CSS_DuplicateStyleDefinitionSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   CVS: $Id: DuplicateStyleDefinitionSniff.php,v 1.2 2010/09/03 14:33:10 alairjt@gmail.com Exp $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Squiz_Sniffs_CSS_DuplicateStyleDefinitionSniff.
 *
 * Check for duplicate style definitions in the same class.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.3.0a1
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Squiz_Sniffs_CSS_DuplicateStyleDefinitionSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array('CSS');


    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_OPEN_CURLY_BRACKET);

    }//end register()


    /**
     * Processes the tokens that this sniff is interested in.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
     * @param int                  $stackPtr  The position in the stack where
     *                                        the token was found.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Find the content of each style definition name.
        $end  = $tokens[$stackPtr]['bracket_closer'];
        $next = $phpcsFile->findNext(T_STYLE, ($stackPtr + 1), $end);
        if ($next === false) {
            // Class definition is empty.
            return;
        }

        $styleNames = array();

        while ($next !== false) {
            $name = $tokens[$next]['content'];
            if (isset($styleNames[$name]) === true) {
                $first = $styleNames[$name];
                $line  = $tokens[$first]['line'];
                $error = "Duplicate style definition found; first defined on line $line";
                $phpcsFile->addError($error, $next);
            } else {
                $styleNames[$name] = $next;
            }

            $next = $phpcsFile->findNext(T_STYLE, ($next + 1), $end);
        }//end while

    }//end process()


}//end class

?>