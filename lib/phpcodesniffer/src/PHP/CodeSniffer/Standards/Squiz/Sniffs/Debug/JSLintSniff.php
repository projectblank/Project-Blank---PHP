<?php
/**
 * Squiz_Sniffs_Debug_JSLintSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   CVS: $Id: JSLintSniff.php,v 1.2 2010/09/03 14:33:26 alairjt@gmail.com Exp $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Squiz_Sniffs_Debug_JSLintSniff.
 *
 * Runs jslint.js on the file.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.3.0a1
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Squiz_Sniffs_Debug_JSLintSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array('JS');


    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_OPEN_TAG);

    }//end register()


    /**
     * Processes the tokens that this sniff is interested in.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
     * @param int                  $stackPtr  The position in the stack where
     *                                        the token was found.
     *
     * @return void
     * @throws PHP_CodeSniffer_Exception If jslint.js could not be run
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $fileName = $phpcsFile->getFilename();

        $rhinoPath  = PHP_CodeSniffer::getConfigData('rhino_path');
        $jslintPath = PHP_CodeSniffer::getConfigData('jslint_path');
        if ($rhinoPath === null || $jslintPath === null) {
            return;
        }

        $cmd = "$rhinoPath \"$jslintPath\" \"$fileName\"";
        $msg = exec($cmd, $output, $retval);

        if (is_array($output) === true) {
            $tokens = $phpcsFile->getTokens();

            foreach ($output as $finding) {
                $matches    = array();
                $numMatches = preg_match('/^Lint at line ([0-9]+).*:(.*)$/', $finding, $matches);
                if ($numMatches === 0) {
                    continue;
                }

                $line    = (int) $matches[1];
                $message = 'jslint says: '.trim($matches[2]);

                // Find the token at the start of the line.
                $lineToken = null;
                foreach ($tokens as $ptr => $info) {
                    if ($info['line'] === $line) {
                        $lineToken = $ptr;
                        break;
                    }
                }

                if ($lineToken !== null) {
                    $phpcsFile->addWarning($message, $lineToken);
                }
            }//end foreach
        }//end if

    }//end process()


}//end class

?>