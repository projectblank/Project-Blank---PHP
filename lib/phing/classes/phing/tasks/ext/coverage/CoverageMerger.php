<?php
/**
 * $Id: CoverageMerger.php,v 1.3 2010/09/03 14:33:24 alairjt@gmail.com Exp $
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

require_once 'phing/system/util/Properties.php';

/**
 * Saves coverage output of the test to a specified database
 *
 * @author Michiel Rook <michiel.rook@gmail.com>
 * @version $Id: CoverageMerger.php,v 1.3 2010/09/03 14:33:24 alairjt@gmail.com Exp $
 * @package phing.tasks.ext.coverage
 * @since 2.1.0
 */
class CoverageMerger
{
    private static function mergeCodeCoverage($left, $right)
    {
        $coverageMerged = array();

        reset($left);
        reset($right);

        while (current($left) && current($right)) {
            $linenr_left = key($left);
            $linenr_right = key($right);

            if ($linenr_left < $linenr_right) {
                $coverageMerged[$linenr_left] = current($left);
                next($left);
            } elseif ($linenr_right < $linenr_left) {
                $coverageMerged[$linenr_right] = current($right);
                next($right);
            } else {
                if ((current($left) < 0) || (current($right) < 0)) {
                    $coverageMerged[$linenr_right] = current($right);
                } else {
                    $coverageMerged[$linenr_right] = current($left) + current($right);
                }
                
                next($left);
                next($right);
            }
        }

        while (current($left)) {
            $coverageMerged[key($left)] = current($left);
            next($left);
        }

        while (current($right)) {
            $coverageMerged[key($right)] = current($right);
            next($right);
        }

        return $coverageMerged;
    }

    static function merge($project, $codeCoverageInformation)
    {
        $coverageDatabase = $project->getProperty('coverage.database');
        
        if (!$coverageDatabase) {
            throw new BuildException("Property coverage.database is not set - please include coverage-setup in your build file");
        }
        
        $database = new PhingFile($coverageDatabase);

        $props = new Properties();
        $props->load($database);
        
        $coverageTotal = $codeCoverageInformation;
        
        foreach ($coverageTotal as $filename => $data) {
            $lines = array();
            $filename = strtolower($filename);

            if ($props->getProperty($filename) != null) {
                foreach ($data as $_line => $_data) {
                    if (is_array($_data)) {
                        $count = count($_data);
                    } else if ($_data == -1) {
                        // not executed
                        $count = -1;
                    } else if ($_data == -2) {
                        // dead code
                        $count = -2;
                    }

                    $lines[$_line] = $count;
                }

                ksort($lines);

                $file = unserialize($props->getProperty($filename));
                $left = $file['coverage'];

                $coverageMerged = CoverageMerger::mergeCodeCoverage($left, $lines);

                $file['coverage'] = $coverageMerged;
                $props->setProperty($filename, serialize($file));
            }           
        }

        $props->store($database);
    }
}