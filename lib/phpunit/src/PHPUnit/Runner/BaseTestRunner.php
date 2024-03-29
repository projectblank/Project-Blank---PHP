<?php
/**
 * PHPUnit
 *
 * Copyright (c) 2002-2010, Sebastian Bergmann <sb@sebastian-bergmann.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Testing
 * @package    PHPUnit
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright  2002-2010 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.phpunit.de/
 * @since      File available since Release 2.0.0
 */

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Util/Filter.php';
require_once 'PHPUnit/Runner/StandardTestSuiteLoader.php';

PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');

/**
 * Base class for all test runners.
 *
 * @category   Testing
 * @package    PHPUnit
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright  2002-2010 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.4.9
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 2.0.0
 * @abstract
 */
abstract class PHPUnit_Runner_BaseTestRunner
{
    const STATUS_PASSED     = 0;
    const STATUS_SKIPPED    = 1;
    const STATUS_INCOMPLETE = 2;
    const STATUS_FAILURE    = 3;
    const STATUS_ERROR      = 4;
    const SUITE_METHODNAME  = 'suite';

    /**
     * Returns the loader to be used.
     *
     * @return PHPUnit_Runner_TestSuiteLoader
     */
    public function getLoader()
    {
        return new PHPUnit_Runner_StandardTestSuiteLoader;
    }

    /**
     * Returns the Test corresponding to the given suite.
     * This is a template method, subclasses override
     * the runFailed() and clearStatus() methods.
     *
     * @param  string  $suiteClassName
     * @param  string  $suiteClassFile
     * @param  boolean $syntaxCheck
     * @return PHPUnit_Framework_Test
     */
    public function getTest($suiteClassName, $suiteClassFile = '', $syntaxCheck = FALSE)
    {
        if (is_dir($suiteClassName) &&
            !is_file($suiteClassName . '.php') && empty($suiteClassFile)) {
            $testCollector = new PHPUnit_Runner_IncludePathTestCollector(
              array($suiteClassName)
            );

            $suite = new PHPUnit_Framework_TestSuite($suiteClassName);
            $suite->addTestFiles($testCollector->collectTests(), $syntaxCheck);

            return $suite;
        }

        try {
            $testClass = $this->loadSuiteClass(
              $suiteClassName, $suiteClassFile, $syntaxCheck
            );
        }

        catch (Exception $e) {
            $this->runFailed($e->getMessage());
            return NULL;
        }

        try {
            $suiteMethod = $testClass->getMethod(self::SUITE_METHODNAME);

            if (!$suiteMethod->isStatic()) {
                $this->runFailed(
                  'suite() method must be static.'
                );

                return NULL;
            }

            try {
                $test = $suiteMethod->invoke(NULL, $testClass->getName());
            }

            catch (ReflectionException $e) {
                $this->runFailed(
                  sprintf(
                    "Failed to invoke suite() method.\n%s",

                    $e->getMessage()
                  )
                );

                return NULL;
            }
        }

        catch (ReflectionException $e) {
            $test = new PHPUnit_Framework_TestSuite($testClass);
        }

        $this->clearStatus();

        return $test;
    }

    /**
     * Returns the loaded ReflectionClass for a suite name.
     *
     * @param  string  $suiteClassName
     * @param  string  $suiteClassFile
     * @param  boolean $syntaxCheck
     * @return ReflectionClass
     */
    protected function loadSuiteClass($suiteClassName, $suiteClassFile = '', $syntaxCheck = FALSE)
    {
        $loader = $this->getLoader();

        if ($loader instanceof PHPUnit_Runner_StandardTestSuiteLoader) {
            return $loader->load($suiteClassName, $suiteClassFile, $syntaxCheck);
        } else {
            return $loader->load($suiteClassName, $suiteClassFile);
        }
    }

    /**
     * Clears the status message.
     *
     */
    protected function clearStatus()
    {
    }

    /**
     * Override to define how to handle a failed loading of
     * a test suite.
     *
     * @param  string  $message
     */
    abstract protected function runFailed($message);
}
?>
