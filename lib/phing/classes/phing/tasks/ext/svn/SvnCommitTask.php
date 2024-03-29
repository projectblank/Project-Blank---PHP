<?php
/**
 * $Id: SvnCommitTask.php,v 1.3 2010/09/03 14:33:22 alairjt@gmail.com Exp $
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
require_once 'phing/tasks/ext/svn/SvnBaseTask.php';

/**
 * Commits changes in a local working copy to the repository
 *
 * @author Johan Persson <johanp@aditus.nu>
 * @version $Id: SvnCommitTask.php,v 1.3 2010/09/03 14:33:22 alairjt@gmail.com Exp $
 * @package phing.tasks.ext.svn
 * @since 2.4.0
 */
class SvnCommitTask extends SvnBaseTask
{
       /**
        * Commit message
        */
        private $message = '';

       /**
        * Property name where we store the revision number of the just
        * commited version.
        */
        private $propertyName = "svn.committedrevision";

        /**
         * Sets the commit message
         */
        function setMessage($message)
        {
                $this->message = $message;
        }

        /**
         * Gets the commit message
         */
        function getMessage()
        {
                 return $this->message;
        }

        /**
         * Sets the name of the property to use for returned revision
         */
        function setPropertyName($propertyName)
        {
                $this->propertyName = $propertyName;
        }

        /**
         * Returns the name of the property to use for returned revision
         */
        function getPropertyName()
        {
                return $this->propertyName;
        }

        /**
         * The main entry point
         *
         * @throws BuildException
         */
        function main()
        {
                if( trim($this->message) === '' )
                {
                        throw new BuildException('SVN Commit message can not be empty.');
                }

                $this->setup('commit');

                $this->log("Commiting SVN working copy at '" . $this->getWorkingCopy() . "' with message '".$this->GetMessage()."'");

                $output = $this->run(array(), array('message' => $this->GetMessage() ) );

                if( preg_match('/[\s]*Committed revision[\s]+([\d]+)/', $output, $matches) )
                {
                        $this->project->setProperty($this->getPropertyName(), $matches[1]);
                }
                else
                {
                        /**
                         * If no new revision was committed set revision to "empty". Remember that
                         * this is not necessarily an error. It could be that the specified working
                         * copy is identical to to the copy in the repository and in that case
                         * there will be no update and no new revision number.
                         */
                        $this->project->setProperty($this->getPropertyName(), '' );
                }

        }
}

