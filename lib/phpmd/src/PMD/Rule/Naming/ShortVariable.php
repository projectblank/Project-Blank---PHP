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
 * @subpackage Rule_Naming
 * @author     Manuel Pichler <mapi@phpmd.org>
 * @copyright  2009-2010 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id: ShortVariable.php,v 1.1 2010/08/30 13:55:10 alairjt@gmail.com Exp $
 * @link       http://phpmd.org
 */

require_once 'PHP/PMD/AbstractRule.php';
require_once 'PHP/PMD/Rule/IClassAware.php';
require_once 'PHP/PMD/Rule/IMethodAware.php';
require_once 'PHP/PMD/Rule/IFunctionAware.php';

/**
 * This rule class will detect variables, parameters and properties with short
 * names.
 *
 * @category   PHP
 * @package    PHP_PMD
 * @subpackage Rule_Naming
 * @author     Manuel Pichler <mapi@phpmd.org>
 * @copyright  2009-2010 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 0.2.6
 * @link       http://phpmd.org
 */
class PHP_PMD_Rule_Naming_ShortVariable
       extends PHP_PMD_AbstractRule
    implements PHP_PMD_Rule_IClassAware,
               PHP_PMD_Rule_IMethodAware,
               PHP_PMD_Rule_IFunctionAware
{
    /**
     * Temporary map holding variables that were already processed in the
     * current context.
     *
     * @var array(string=>boolean)
     */
    private $_processedVariables = array();

    /**
     * Extracts all variable and variable declarator nodes from the given node
     * and checks the variable name length against the configured minimum
     * length.
     *
     * @param PHP_PMD_AbstractNode $node The context source code node.
     *
     * @return void
     */
    public function apply(PHP_PMD_AbstractNode $node)
    {
        $this->resetProcessed();

        if ($node->getType() === 'class') {
            $fields = $node->findChildrenOfType('FieldDeclaration');
            foreach ($fields as $field) {
                $declarators = $field->findChildrenOfType('VariableDeclarator');
                foreach ($declarators as $declarator) {
                    $this->checkNodeImage($declarator);
                }
            }
        } else {
            $declarators = $node->findChildrenOfType('VariableDeclarator');
            foreach ($declarators as $declarator) {
                $this->checkNodeImage($declarator);
            }

            $variables = $node->findChildrenOfType('Variable');
            foreach ($variables as $variable) {
                $this->checkNodeImage($variable);
            }
        }

        $this->resetProcessed();
    }

    /**
     * Checks if the variable name of the given node is greater/equal to the
     * configured threshold or if the given node is an allowed context.
     *
     * @param PHP_PMD_AbstractNode $node The context source code node.
     *
     * @return void
     */
    protected function checkNodeImage(PHP_PMD_AbstractNode $node)
    {
        if ($this->isNotProcessed($node)) {
            $this->addProcessed($node);
            $this->doCheckNodeImage($node);
        }
    }

    /**
     * Template method that performs the real node image check.
     *
     * @param PHP_PMD_AbstractNode $node The context source code node.
     *
     * @return void
     */
    protected function doCheckNodeImage(PHP_PMD_AbstractNode $node)
    {
        if ($this->getIntProperty('minimum') <= strlen($node->getImage()) - 1) {
            return;
        }
        if ($this->_isNameAllowedInContext($node)) {
            return;
        }
        $this->addViolation($node, array($node->getImage()));
    }

    /**
     * Checks if a short name is acceptable in the current context. For the
     * moment these contexts are the init section of a for-loop and short
     * variable names in catch-statements.
     *
     * @param PHP_PMD_AbstractNode $node The context source code node.
     * 
     * @return boolean
     */
    private function _isNameAllowedInContext(PHP_PMD_AbstractNode $node)
    {
        return $this->_isChildOf($node, 'CatchStatement')
                || $this->_isChildOf($node, 'ForInit')
                || $this->_isChildOf($node, 'ForeachStatement')
                || $this->_isChildOf($node, 'MemberPrimaryPrefix');
    }

    /**
     * Checks if the given node is a direct or indirect child of a node with
     * the given type.
     *
     * @param PHP_PMD_AbstractNode $node The context source code node.
     * @param string               $type Possible parent type.
     * 
     * @return boolean
     */
    private function _isChildOf(PHP_PMD_AbstractNode $node, $type)
    {
        $parent = $node->getParent();
        while (is_object($parent)) {
            if ($parent->isInstanceOf($type)) {
                return true;
            }
            $parent = $parent->getParent();
        }
        return false;
    }

    /**
     * Resets the already processed nodes.
     *
     * @return void
     */
    protected function resetProcessed()
    {
        $this->_processedVariables = array();
    }

    /**
     * Flags the given node as already processed.
     *
     * @param PHP_PMD_AbstractNode $node The node to add.
     *
     * @return void
     */
    protected function addProcessed(PHP_PMD_AbstractNode $node)
    {
        $this->_processedVariables[$node->getImage()] = true;
    }

    /**
     * Checks if the given node was already processed.
     *
     * @param PHP_PMD_AbstractNode $node The node to check.
     *
     * @return boolean
     */
    protected function isNotProcessed(PHP_PMD_AbstractNode $node)
    {
        return !isset($this->_processedVariables[$node->getImage()]);
    }
}
