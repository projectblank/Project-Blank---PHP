<?php
/**
 * $Id: PhpDependLoggerElement.php,v 1.3 2010/09/03 14:33:22 alairjt@gmail.com Exp $
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

require_once 'phing/system/io/PhingFile.php';

/**
 * Logger element for the PhpDependTask.
 *
 * @package phing.tasks.ext.pdepend
 * @author  Benjamin Schultz <bschultz@proqrent.de>
 * @version $Id: PhpDependLoggerElement.php,v 1.3 2010/09/03 14:33:22 alairjt@gmail.com Exp $
 * @since   2.4.1
 */
class PhpDependLoggerElement
{
    /**
     * The type of the logger.
     *
     * @var string
     */
    protected $_type = '';

    /**
     * Output file for logger.
     *
     * @var PhingFile
     */
    protected $_outfile = null;

    /**
     * Sets the logger type.
     *
     * @param string $type Type of the logger
     *
     * @return void
     */
    public function setType($type)
    {
        $this->_type = $type;

        switch ($this->_type) {
            case 'jdepend-chart':
            case 'jdepend-xml':
            case 'overview-pyramid':
            case 'phpunit-xml':
            case 'summary-xml':
                break;

            default:
                throw new BuildException(
                    "Logger '" . $this->_type . "' not implemented"
                );
        }
    }

    /**
     * Get the logger type
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Sets the output file for the logger results.
     *
     * @param PhingFile $outfile The output file
     *
     * @return void
     */
    public function setOutfile(PhingFile $outfile)
    {
        $this->_outfile = $outfile;
    }

    /**
     * Get the output file.
     *
     * @return PhingFile
     */
    public function getOutfile()
    {
        return $this->_outfile;
    }
}