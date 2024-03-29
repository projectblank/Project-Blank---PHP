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
 * @author     Mattis Stordalen Flister <mattis@xait.no>
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright  2002-2010 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       http://www.phpunit.de/
 * @since      File available since Release 3.3.0
 */

require_once 'PHPUnit/Util/Filter.php';
require_once 'PHPUnit/Util/Template.php';
require_once 'PHPUnit/Extensions/Story/ResultPrinter.php';

PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');

/**
 * Prints stories in HTML format.
 *
 * @category   Testing
 * @package    PHPUnit
 * @author     Mattis Stordalen Flister <mattis@xait.no>
 * @author     Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright  2002-2010 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 3.4.9
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 3.3.0
 */
class PHPUnit_Extensions_Story_ResultPrinter_Text extends PHPUnit_Extensions_Story_ResultPrinter
{
    /**
     * Handler for 'start class' event.
     *
     * @param  string $name
     */
    protected function startClass($name)
    {
        $this->write($this->currentTestClassPrettified . "\n");
    }

    /**
     * Handler for 'on test' event.
     *
     * @param  string  $name
     * @param  boolean $success
     * @param  array   $steps
     */
    protected function onTest($name, $success = TRUE, array $steps = array())
    {
        if ($this->testStatus == PHPUnit_Runner_BaseTestRunner::STATUS_FAILURE) {
            $scenarioStatus = 'failed';
        }

        else if ($this->testStatus == PHPUnit_Runner_BaseTestRunner::STATUS_SKIPPED) {
            $scenarioStatus = 'skipped';
        }

        else if ($this->testStatus == PHPUnit_Runner_BaseTestRunner::STATUS_INCOMPLETE) {
            $scenarioStatus = 'incomplete';
        }

        else {
            $scenarioStatus = 'successful';
        }

        $this->write(
          sprintf(
            " [%s] %s\n\n",
            $scenarioStatus == 'successful' ? 'x' : ' ',
            $name
          )
        );

        $lastStepName = '';
        $stepsBuffer  = '';

        foreach ($steps as $step) {
            $currentStepName = $step->getName();

            if ($lastStepName == $currentStepName) {
                $stepText = 'and';
            } else {
                $stepText = $currentStepName;
            }

            $lastStepName = $currentStepName;

            $this->write(
              sprintf(
                "   %5s %s %s\n",
                $stepText,
                $step->getAction(),
                $step->getArguments(TRUE)
              )
            );
        }

        $this->write("\n");
    }

    /**
     * Handler for 'end run' event.
     *
     */
    protected function endRun()
    {
        $this->write(
          sprintf(
            "Scenarios: %d, Failed: %d, Skipped: %d, Incomplete: %d.\n",

            $this->successful + $this->failed +
            $this->skipped    + $this->incomplete,
            $this->failed,
            $this->skipped,
            $this->incomplete
          )
        );
    }
}
?>
