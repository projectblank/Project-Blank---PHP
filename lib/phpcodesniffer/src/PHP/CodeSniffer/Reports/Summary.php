<?php
/**
 * Summary report for PHP_CodeSniffer.
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
 * @version   CVS: $Id: Summary.php,v 1.2 2010/09/03 14:32:50 alairjt@gmail.com Exp $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Summary report for PHP_CodeSniffer.
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
class PHP_CodeSniffer_Reports_Summary implements PHP_CodeSniffer_Report
{


    /**
     * Generates a summary of errors and warnings for each file processed.
     * 
     * If verbose output is enabled, results are shown for all files, even if
     * they have no errors or warnings. If verbose output is disabled, we only
     * show files that have at least one warning or error.
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
        $errorFiles = array();
        $width      = max($width, 70);

        foreach ($report['files'] as $filename => $file) {
            $numWarnings = $file['warnings'];
            $numErrors   = $file['errors'];

            // If verbose output is enabled, we show the results for all files,
            // but if not, we only show files that had errors or warnings.
            if (PHP_CODESNIFFER_VERBOSITY > 0
                || $numErrors > 0
                || ($numWarnings > 0
                && $showWarnings === true)
            ) {
                $errorFiles[$filename] = array(
                                          'warnings' => $numWarnings,
                                          'errors'   => $numErrors,
                                         );
            }//end if
        }//end foreach

        if (empty($errorFiles) === true) {
            // Nothing to print.
            return 0;
        }

        echo PHP_EOL.'PHP CODE SNIFFER REPORT SUMMARY'.PHP_EOL;
        echo str_repeat('-', $width).PHP_EOL;
        echo 'FILE'.str_repeat(' ', ($width - 20)).'ERRORS  WARNINGS'.PHP_EOL;
        echo str_repeat('-', $width).PHP_EOL;

        $totalErrors   = 0;
        $totalWarnings = 0;
        $totalFiles    = 0;

        foreach ($errorFiles as $file => $errors) {
            $padding = ($width - 18 - strlen($file));
            if ($padding < 0) {
                $file    = '...'.substr($file, (($padding * -1) + 3));
                $padding = 0;
            }

            echo $file.str_repeat(' ', $padding).'  ';
            echo $errors['errors'];
            echo str_repeat(' ', (8 - strlen((string) $errors['errors'])));
            echo $errors['warnings'];
            echo PHP_EOL;

            $totalFiles++;
        }//end foreach

        echo str_repeat('-', $width).PHP_EOL;
        echo 'A TOTAL OF '.$report['totals']['errors'].' ERROR(S) ';
        echo 'AND '.$report['totals']['warnings'].' WARNING(S) ';

        echo 'WERE FOUND IN '.$totalFiles.' FILE(S)'.PHP_EOL;
        echo str_repeat('-', $width).PHP_EOL.PHP_EOL;

        if ($showSources === true) {
            $source = new PHP_CodeSniffer_Reports_Source();
            $source->generate($report, $showSources, $width);
        }

        if (class_exists('PHP_Timer', false) === true) {
            echo PHP_Timer::resourceUsage().PHP_EOL.PHP_EOL;
        }

        return ($report['totals']['errors'] + $report['totals']['warnings']);

    }//end generate()


}//end class

?>
