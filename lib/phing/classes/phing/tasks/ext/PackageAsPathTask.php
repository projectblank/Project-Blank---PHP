<?php

/*
 *  $Id: PackageAsPathTask.php,v 1.3 2010/09/03 14:32:53 alairjt@gmail.com Exp $
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
 * Convert dot-notation packages to relative paths.
 *
 * @author    Hans Lellelid <hans@xmpl.org>
 * @version   $Id: PackageAsPathTask.php,v 1.3 2010/09/03 14:32:53 alairjt@gmail.com Exp $
 * @package   phing.tasks.ext
 */
class PackageAsPathTask extends Task {

    /** The package to convert. */
    protected $pckg;

    /** The value to store the conversion in. */
    protected $name;
    
    /**
     * Executes the package to patch converstion and stores it
     * in the user property <code>value</code>.
     */
    public function main()
    {
        $this->project->setUserProperty($this->name, strtr($this->pckg, '.', '/'));        
    }

    /**
     * @param string $pckg the package to convert
     */
    public function setPackage($pckg)
    {
        $this->pckg = $pckg;
    }

    /**
     * @param string $name the Ant variable to store the path in
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
}
