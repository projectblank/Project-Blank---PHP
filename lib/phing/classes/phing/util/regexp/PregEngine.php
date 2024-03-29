<?php
/* 
 *  $Id: PregEngine.php,v 1.3 2010/09/03 14:33:22 alairjt@gmail.com Exp $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information please see
 * <http://phing.info>. 
 */

require_once 'phing/util/regexp/RegexpEngine.php';

/**
 * PREG Regexp Engine.
 * Implements a regexp engine using PHP's preg_match(), preg_match_all(), and preg_replace() functions.
 * 
 * @author hans lellelid, hans@velum.net
 * @package phing.util.regex
 */
class PregEngine implements RegexpEngine {

    /**
     * Set to null by default to distinguish between false and not set
     * @var boolean
     */
    private $ignoreCase = null;

    /**
     * Set to null by default to distinguish between false and not set
     * @var boolean
     */
    private $multiline = null;

    /**
     * Pattern modifiers 
     * @link http://php.net/manual/en/reference.pcre.pattern.modifiers.php
     * @var string
     */
    private $modifiers = null;

    /**
     * Sets pattern modifiers for regex engine
     *
     * @param string $mods Modifiers to be applied to a given regex
     * @return void
     */
    public function setModifiers($mods) {
        $this->modifiers =  (string)$mods;
    }

    /**
     * Gets pattern modifiers.
     * @return string
     */
    public function getModifiers() {
        $mods = $this->modifiers;
        if($this->getIgnoreCase()) {
            $mods .= 'i';
        } elseif($this->getIgnoreCase() === false) {
            $mods = str_replace('i', '', $mods);
        }
        if($this->getMultiline()) {
            $mods .= 's';
        } elseif($this->getMultiline() === false) {
            $mods = str_replace('s', '', $mods);
        }
        // filter out duplicates
        $mods = preg_split('//', $mods, -1, PREG_SPLIT_NO_EMPTY);
        $mods = implode('', array_unique($mods));
        return $mods;
    }
        
    /**
     * Sets whether or not regex operation is case sensitive.
     * @param boolean $bit
     * @return void
     */
    function setIgnoreCase($bit) {
        $this->ignoreCase = (boolean) $bit;
    }

    /**
     * Gets whether or not regex operation is case sensitive.
     * @return boolean
     */
    function getIgnoreCase() {
        return $this->ignoreCase;
    }

    /**
     * Sets whether regexp should be applied in multiline mode.
     * @param boolean $bit
     */
    function setMultiline($bit) {
        $this->multiline = $bit;
    }

    /**
     * Gets whether regexp is to be applied in multiline mode.
     * @return boolean
     */
    function getMultiline() {
        return $this->multiline;
    }
        
    /**
     * The pattern needs to be converted into PREG style -- which includes adding expression delims & any flags, etc.
     * @param string $pattern
     * @return string prepared pattern.
     */
    private function preparePattern($pattern)
    {
        // Use backquotes since hardly ever found in a regexp pattern, avoids using preg_quote
        return '`'.$pattern.'`' . $this->getModifiers();
    }
    
    /**
     * Matches pattern against source string and sets the matches array.
     * @param string $pattern The regex pattern to match.
     * @param string $source The source string.
     * @param array $matches The array in which to store matches.
     * @return boolean Success of matching operation.
     */
    function match($pattern, $source, &$matches) { 
        return preg_match($this->preparePattern($pattern), $source, $matches);
    }

    /**
     * Matches all patterns in source string and sets the matches array.
     * @param string $pattern The regex pattern to match.
     * @param string $source The source string.
     * @param array $matches The array in which to store matches.
     * @return boolean Success of matching operation.
     */        
    function matchAll($pattern, $source, &$matches) {
        return preg_match_all($this->preparePattern($pattern), $source, $matches);
    }

    /**
     * Replaces $pattern with $replace in $source string.
     * References to \1 group matches will be replaced with more preg-friendly
     * $1.
     * @param string $pattern The regex pattern to match.
     * @param string $replace The string with which to replace matches.
     * @param string $source The source string.
     * @return string The replaced source string.
     */        
    function replace($pattern, $replace, $source) {
        // convert \1 -> $1, because we want to use the more generic \1 in the XML
        // but PREG prefers $1 syntax.
        $replace = preg_replace('/\\\(\d+)/', '\$$1', $replace);
        return preg_replace($this->preparePattern($pattern), $replace, $source);
    }

}

