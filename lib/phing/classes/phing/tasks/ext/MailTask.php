<?php
/*
 *  $Id: MailTask.php,v 1.3 2010/09/03 14:32:53 alairjt@gmail.com Exp $
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
 
include_once 'phing/Task.php';

/**
 *  Send a message by mail() 
 *
 *  <mail to="user@example.org" subject="build complete">The build process is a success...</mail> 
 * 
 *  @author   Francois Harvey at SecuriWeb (http://www.securiweb.net)
 *  @version  $Id: MailTask.php,v 1.3 2010/09/03 14:32:53 alairjt@gmail.com Exp $
 *  @package  phing.tasks.ext
 */
class MailTask extends Task {

    protected $recipient;
      
    protected $subject;
    
    protected $msg;

    function main() {
        $this->log('Sending mail to ' . $this->recipient );    
        mail($this->recipient, $this->subject, $this->msg);
    }

    /** setter for message */
    function setMsg($msg) {
        $this->setMessage($msg);
    }

    /** alias setter */
    function setMessage($msg) {
        $this->msg = (string) $msg;
    }
    
    /** setter for subject **/
    function setSubject($subject) {
        $this->subject = (string) $subject;    
    }

    /** setter for recipient **/
    function setRecipient($recipient) {
        $this->recipient = (string) $recipient;
    }

    /** alias for recipient **/
    function setTo($recipient) {
        $this->recipient = (string) $recipient;
    }
        
    /** Supporting the <mail>Message</mail> syntax. */
    function addText($msg)
    {
        $this->msg = (string) $msg;
    }
}

