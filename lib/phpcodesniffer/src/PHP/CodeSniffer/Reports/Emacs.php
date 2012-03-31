<?php
/**
 * Emacs report for PHP_CodeSniffer.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Gabriele Santini <gsantini@sqli.com>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2009 SQLI <www.sqli.com>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   CVS: $Id: Emacs.php,v 1.2 2010/09/03 14:32:50 alairjt@gmail.com Exp $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Emacs report for PHP_CodeSniffer.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Gabriele Santini <gsantini@sqli.com>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2009 SQLI <www.sqli.com>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.3.0a1
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class PHP_CodeSniffer_Reports_Emacs implements PHP_CodeSniffer_Report
{


    /**
     * Generates an emacs report.
     * 
     * @param array   $report       Prepared report.
     * @param boolean $showSources  Show sources?
     * @param int     $width        Maximum allowed lne width.
     * 
     * @return string 
     */
    public function generate(
        $report,
        $showSources=false,
        $width=80
    ) {
        $errorsShown = 0;

        foreach ($report['files'] as $filename => $file) {
            foreach ($file['messages'] as $line => $lineErrors) {
                foreach ($lineErrors as $column => $colErrors) {
                    foreach ($colErrors as $error) {
                        $message = $error['message'];
                        $type    = strtolower($error['type']);
                        echo $filename.':'.$line.':'.$column.': '.$type.' - '.$message.PHP_EOL;
                        $errorsShown++;
                    }
                }
            }//end foreach
        }//end foreach

        return $errorsShown;

    }//end generate()


}//end class

?>
