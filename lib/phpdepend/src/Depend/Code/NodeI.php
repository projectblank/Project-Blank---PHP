<?php
/**
 * This file is part of PHP_Depend.
 * 
 * PHP Version 5
 *
 * Copyright (c) 2008-2010, Manuel Pichler <mapi@pdepend.org>.
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
 *   * Neither the name of Manuel Pichler nor the names of his
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
 * @category   QualityAssurance
 * @package    PHP_Depend
 * @subpackage Code
 * @author     Manuel Pichler <mapi@pdepend.org>
 * @copyright  2008-2010 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id: NodeI.php,v 1.1 2010/08/30 13:54:35 alairjt@gmail.com Exp $
 * @link       http://pdepend.org/
 */

/**
 * Represents any possible code node.
 *
 * @category   QualityAssurance
 * @package    PHP_Depend
 * @subpackage Code
 * @author     Manuel Pichler <mapi@pdepend.org>
 * @copyright  2008-2010 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 0.9.17
 * @link       http://pdepend.org/
 */
interface PHP_Depend_Code_NodeI
{
    /**
     * Returns the name for this code node.
     *
     * @return string
     */
    function getName();
    
    /**
     * Returns a uuid for this code node.
     *
     * @return string
     */
    function getUUID();
    
    /**
     * Visitor method for node tree traversal.
     *
     * @param PHP_Depend_VisitorI $visitor The context visitor 
     *                                              implementation.
     * 
     * @return void
     */
    function accept(PHP_Depend_VisitorI $visitor);

    /**
     * This method can be called by the PHP_Depend runtime environment or a
     * utilizing component to free up memory. This methods are required for
     * PHP version < 5.3 where cyclic references can not be resolved
     * automatically by PHP's garbage collector.
     *
     * @return void
     * @since 0.9.12
     */
    function free();
}