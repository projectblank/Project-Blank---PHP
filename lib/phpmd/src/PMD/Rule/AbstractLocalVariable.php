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
 * @subpackage Rule
 * @author     Manuel Pichler <mapi@phpmd.org>
 * @copyright  2009-2010 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id: AbstractLocalVariable.php,v 1.1 2010/08/30 13:54:30 alairjt@gmail.com Exp $
 * @link       http://phpmd.org
 * @since      0.2.6
 */

require_once 'PHP/PMD/Rule/AbstractLocalVariable.php';

/**
 * Base class for rules that rely on local variables.
 *
 * @category   PHP
 * @package    PHP_PMD
 * @subpackage Rule
 * @author     Manuel Pichler <mapi@phpmd.org>
 * @copyright  2009-2010 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 0.2.6
 * @link       http://phpmd.org
 * @since      0.2.6
 */
abstract class PHP_PMD_Rule_AbstractLocalVariable extends PHP_PMD_AbstractRule
{
    /**
     * PHP super globals that are available in all php scopes, so that they
     * can never be unused local variables.
     *
     * @var array(string=>boolean)
     */
    private static $_superGlobals = array(
        '$argc'                => true,
        '$argv'                => true,
        '$_COOKIE'             => true,
        '$_ENV'                => true,
        '$_FILES'              => true,
        '$_GET'                => true,
        '$_POST'               => true,
        '$_REQUEST'            => true,
        '$_SERVER'             => true,
        '$_SESSION'            => true,
        '$GLOBALS'             => true,
        '$HTTP_RAW_POST_DATA'  => true,
    );

    /**
     * Tests if the given variable node represents a local variable or if it is
     * a static object property or something similar.
     *
     * @param PHP_PMD_Node_ASTNode $variable The variable to check.
     *
     * @return boolean
     */
    protected function isLocal(PHP_PMD_Node_ASTNode $variable)
    {
        return ($this->isNotSuperGlobal($variable)
            && $this->isNotThis($variable)
            && $this->isRegularVariable($variable)
        );
    }

    /**
     * Tests if the given variable node is a regular variable an not property
     * or method postfix.
     *
     * @param PHP_PMD_Node_ASTNode $variable The variable node to check.
     *
     * @return boolean
     */
    protected function isRegularVariable(PHP_PMD_Node_ASTNode $variable)
    {
        if ($this->isNotMemberPrefixOrIsArgument($variable)) {
            return true;
        }

        $parent = $variable->getParent();
        if ($this->isIndexExpression($variable)) {
            return $parent->getChild(0)->getImage() !== $variable->getImage();
        } else if ($parent->isInstanceOf('MethodPostfix')) {
            return $parent->getChild(0)->getImage() === $variable->getImage();
        } else if ($parent->isInstanceOf('PropertyPostfix')) {
            $parentParent = $parent->getParent()->getParent();
            if ($parentParent->isInstanceOf('MemberPrimaryPrefix')
                && $parentParent->isStatic()
            ) {
                return $parent->getChild(0)->getImage() !== $variable->getImage();
            }
            if ($parent->getParent()->isStatic()) {
                return $parent->getChild(0)->getImage() !== $variable->getImage();
            }
            return $parent->getChild(0)->getImage() === $variable->getImage();
        }
        return true;
    }

    /**
     * Tests if the given variable represents one of the PHP super globals
     * that are available in scopes.
     *
     * @param PHP_PMD_AbstractNode $variable The currently analyzed variable node.
     *
     * @return boolean
     */
    protected function isNotSuperGlobal(PHP_PMD_AbstractNode $variable)
    {
        return !isset(self::$_superGlobals[$variable->getImage()]);
    }

    /**
     * This method will return <b>true</b> when the given variable node
     * is not a reference to the objects <b>$this</b> context.
     *
     * @param PHP_PMD_AbstractNode $variable The currently analyzed variable node.
     *
     * @return boolean
     */
    protected function isNotThis(PHP_PMD_AbstractNode $variable)
    {
        return ($variable->getImage() !== '$this');
    }

    /**
     * Tests if the given variable is not part of a member primary prefix or if
     * it is part of method/function arguments.
     *
     * @param PHP_PMD_Node_ASTNode $variable The variable to test.
     *
     * @return boolean
     */
    protected function isNotMemberPrefixOrIsArgument(PHP_PMD_Node_ASTNode $variable)
    {
        $parent = $variable;
        while ($parent = $parent->getParent()) {
            if ($parent->isInstanceOf('MemberPrimaryPrefix')) {
                return false;
            } else if ($parent->isInstanceOf('Arguments')) {
                return true;
            }
        }
        return true;
    }

    /**
     * Tests if the given variable node os part of an index expression.
     *
     * @param PHP_PMD_Node_ASTNode $variable The variable to test.
     *
     * @return boolean
     */
    protected function isIndexExpression(PHP_PMD_Node_ASTNode $variable)
    {
        return ($variable->getParent()->isInstanceOf('ArrayIndexExpression')
            || $variable->getParent()->isInstanceOf('StringIndexExpression')
        );
    }
}