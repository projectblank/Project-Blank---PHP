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
 * @category   PHP
 * @package    PHP_Depend
 * @subpackage Code
 * @author     Manuel Pichler <mapi@pdepend.org>
 * @copyright  2008-2010 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id: ASTClassOrInterfaceReference.php,v 1.1 2010/08/30 13:54:34 alairjt@gmail.com Exp $
 * @link       http://www.pdepend.org/
 * @since      0.9.5
 */

require_once 'PHP/Depend/Code/ASTTypeNode.php';

/**
 * This class is used as a placeholder for unknown classes or interfaces. It
 * will resolve the concrete type instance on demand.
 *
 * @category   PHP
 * @package    PHP_Depend
 * @subpackage Code
 * @author     Manuel Pichler <mapi@pdepend.org>
 * @copyright  2008-2010 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 0.9.17
 * @link       http://www.pdepend.org/
 * @since      0.9.5
 */
class PHP_Depend_Code_ASTClassOrInterfaceReference
    extends PHP_Depend_Code_ASTTypeNode
{
    /**
     * The image type of this node.
     */
    const CLAZZ = __CLASS__;

    /**
     * The associated AST builder.
     *
     * @var PHP_Depend_BuilderI $builder
     */
    protected $builder = null;

    /**
     * An already loaded type instance.
     *
     * @var PHP_Depend_Code_AbstractClassOrInterface $typeInstance
     */
    protected $typeInstance = null;

    /**
     * Constructs a new type holder instance.
     *
     * @param PHP_Depend_BuilderI $builder       The associated AST builder instance.
     * @param string              $qualifiedName The qualified type name.
     */
    public function __construct(PHP_Depend_BuilderI $builder, $qualifiedName)
    {
        parent::__construct($qualifiedName);

        $this->builder = $builder;
    }

    /**
     * Returns the concrete type instance associated with with this placeholder.
     *
     * @return PHP_Depend_Code_AbstractClassOrInterface
     */
    public function getType()
    {
        if ($this->typeInstance === null) {
            $this->typeInstance = $this->builder->getClassOrInterface(
                $this->getImage()
            );
        }
        return $this->typeInstance;
    }

    /**
     * Accept method of the visitor design pattern. This method will be called
     * by a visitor during tree traversal.
     *
     * @param PHP_Depend_Code_ASTVisitorI $visitor The calling visitor instance.
     * @param mixed                       $data    Optional previous calculated data.
     *
     * @return mixed
     * @since 0.9.12
     */
    public function accept(PHP_Depend_Code_ASTVisitorI $visitor, $data = null)
    {
        return $visitor->visitClassOrInterfaceReference($this, $data);
    }
}
