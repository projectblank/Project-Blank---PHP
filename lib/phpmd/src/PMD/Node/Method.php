<?php
/**
 * This file is part of PHP_PMD.
 *
 * PHP Version 5
 *
 * Copyright (c) 2009-2010, Manuel Pichler <mapi@phpmd.org>.
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
 * @category   PHP
 * @package    PHP_PMD
 * @subpackage Node
 * @author     Manuel Pichler <mapi@phpmd.org>
 * @copyright  2009-2010 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id: Method.php,v 1.1 2010/08/30 13:55:09 alairjt@gmail.com Exp $
 * @link       http://phpmd.org
 */

require_once 'PHP/PMD/Node/AbstractCallable.php';

/**
 * Wrapper around a PHP_Depend method node.
 *
 * @category   PHP
 * @package    PHP_PMD
 * @subpackage Node
 * @author     Manuel Pichler <mapi@phpmd.org>
 * @copyright  2009-2010 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 0.2.6
 * @link       http://phpmd.org
 */
class PHP_PMD_Node_Method extends PHP_PMD_Node_AbstractCallable
{
    /**
     * Constructs a new method wrapper.
     *
     * @param PHP_Depend_Code_CodeMethod $node The wrapped method object.
     */
    public function __construct(PHP_Depend_Code_Method $node)
    {
        parent::__construct($node);
    }

    /**
     * Returns the name of the parent package.
     *
     * @return string
     */
    public function getPackageName()
    {
        return $this->getNode()->getParent()->getPackage()->getName();
    }

    /**
     * Returns the name of the parent type or <b>null</b> when this node has no
     * parent type.
     *
     * @return string
     */
    public function getParentName()
    {
        return $this->getNode()->getParent()->getName();
    }

    /**
     * Returns <b>true</b> when the underlying method is declared as abstract or
     * is declared as child of an interface.
     *
     * @return boolean
     */
    public function isAbstract()
    {
        return $this->getNode()->isAbstract();
    }

    /**
     * Checks if this node has a suppressed annotation for the given rule
     * instance.
     *
     * @param PHP_PMD_AbstractRule $rule The context rule instance.
     *
     * @return boolean
     */
    public function hasSuppressWarningsAnnotationFor(PHP_PMD_AbstractRule $rule)
    {
        if (parent::hasSuppressWarningsAnnotationFor($rule)) {
            return true;
        }
        return $this->getParentType()->hasSuppressWarningsAnnotationFor($rule);
    }

    /**
     * Returns the parent class or interface instance.
     *
     * @return PHP_PMD_Node_AbstractType
     */
    public function getParentType()
    {
        $parentNode = $this->getNode()->getParent();
        if ($parentNode instanceof PHP_Depend_Code_Class) {
            return new PHP_PMD_Node_Class($parentNode);
        }
        return new PHP_PMD_Node_Interface($parentNode);
    }
}
