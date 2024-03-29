<?php
/*
 * $Id: FileHashTask.php,v 1.3 2010/09/03 14:32:53 alairjt@gmail.com Exp $
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
require_once 'phing/Task.php';

/**
 * fileHash
 *
 * Calculate either MD5 or SHA hash value of a specified file and retun the
 * value in a property
 *
 * @author      Johan Persson <johan162@gmail.com>
 * @version     $Id: FileHashTask.php,v 1.3 2010/09/03 14:32:53 alairjt@gmail.com Exp $
 * @package     phing.tasks.ext
 */
class FileHashTask extends Task
{
    /**
     * Property for File
     * @var PhingFile file
     */
    private $file;

    /**
     * Property to be set
     * @var string $property
     */
    private $propertyName = "filehashvalue";
    
    /**
     * Specify which hash algorithm to use.
     *   0 = MD5
     *   1 = SHA1
     * 
     * @var integer $hashtype
     */
    private $hashtype=0;


    /**
     * Specify if MD5 or SHA1 hash should be used
     * @param integer $type 0=MD5, 1=SHA1
     */
    public function setHashtype($type)
    {
        $this->hashtype = $type;
    }

    /**
     * Which file for calculate the has value of
     * @param PhingFile $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Set the name of the property to use to return the has value
     * @param $property
     * @return
     */
    public function setPropertyName($property)
    {
        $this->propertyName = $property;
    }

    /**
     * Main-Method for the Task
     *
     * @return  void
     * @throws  BuildException
     */
    public function main()
    {
        $this->checkFile();
        $this->checkPropertyName();

        // read file
        if( (int)$this->hashtype === 0 ) {
            $this->log("Calculating MD5 hash from: ".$this->file);
            $hashValue = md5_file($this->file,false);
        }
        elseif( (int)$this->hashtype === 1 ) {
            $this->log("Calculating SHA1 hash from: ".$this->file);
            $hashValue = sha1_file($this->file,false);
        }
        else {
            throw new BuildException(
                sprintf('[FileHash] Unknown hashtype specified %d. Must be either 0 (=MD5) or 1 (=SHA1).',$this->hashtype));
            
        }

        // publish hash value
        $this->project->setProperty($this->propertyName, $hashValue);

    }

    /**
     * checks file attribute
     * @return void
     * @throws BuildException
     */
    private function checkFile()
    {
        // check File
        if ($this->file === null ||
            strlen($this->file) == 0) {
            throw new BuildException('[FileHash] You must specify an input file.', $this->file);
        }

        if( ! is_readable($this->file) ) { 
            throw new BuildException(sprintf('[FileHash] Input file does not exist or is not readable: %s',$this->file));
        }     

    }

    /**
     * checks property attribute
     * @return void
     * @throws BuildException
     */
    private function checkPropertyName()
    {
        if (is_null($this->propertyName) ||
            strlen($this->propertyName) === 0) {
            throw new BuildException('Property name for publishing hashvalue is not set');
        }
    }
}