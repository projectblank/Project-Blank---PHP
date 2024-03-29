<?php
/**
 * Generic_Sniffs_PHP_DeprecatedFunctionsSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   CVS: $Id: DeprecatedFunctionsSniff.php,v 1.2 2010/09/03 14:33:04 alairjt@gmail.com Exp $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Generic_Sniffs_PHP_DeprecatedFunctionsSniff.
 *
 * Discourages the use of deprecated functions that are kept in PHP for
 * compatibility with older versions.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.3.0a1
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Generic_Sniffs_PHP_DeprecatedFunctionsSniff extends Generic_Sniffs_PHP_ForbiddenFunctionsSniff
{
    /**
     * A list of forbidden functions with their alternatives.
     *
     * The value is NULL if no alternative exists. IE, the
     * function should just not be used.
     *
     * @var array(string => string|null)
     */
    protected $forbiddenFunctions = array();

    /**
     * Constructor.
     *
     * Uses the Reflection API to get a list of deprecated functions.
     */
    public function __construct()
    {
        $functions = get_defined_functions();

        foreach ($functions['internal'] as $functionName) {
            $function = new ReflectionFunction($functionName);

            if ($function->isDeprecated()) {
                $this->forbiddenFunctions[$functionName] = NULL;
            }
        }
    }
}
?>
