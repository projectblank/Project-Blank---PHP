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
 * @version    SVN: $Id: Annotation.php,v 1.2 2010/09/03 14:33:12 alairjt@gmail.com Exp $
 * @link       http://phpmd.org
 */

/**
 * Simple code annotation class.
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
class PHP_PMD_Node_Annotation
{
    /**
     * Name of the suppress warnings annotation.
     */
    const SUPPRESS_ANNOTATION = 'SuppressWarnings';

    /**
     * The annotation name.
     *
     * @var string
     */
    private $_name = null;

    /**
     * The annotation value.
     *
     * @var string
     */
    private $_value = null;

    /**
     * Constructs a new annotation instance.
     *
     * @param string $name  The annotation name.
     * @param string $value The supplied annotation value.
     */
    public function __construct($name, $value)
    {
        $this->_name  = $name;
        $this->_value = trim($value, '" ');
    }

    /**
     * Checks if this annotation suppresses the given rule.
     *
     * @param PHP_PMD_AbstractRule $rule The rule to check.
     *
     * @return boolean
     */
    public function suppresses(PHP_PMD_AbstractRule $rule)
    {
        if ($this->_name === self::SUPPRESS_ANNOTATION) {
            return $this->_suppresses($rule);
        }
        return false;
    }

    /**
     * Checks if this annotation suppresses the given rule.
     *
     * @param PHP_PMD_AbstractRule $rule The rule to check.
     *
     * @return boolean
     */
    private function _suppresses(PHP_PMD_AbstractRule $rule)
    {
        if (in_array($this->_value, array('PHPMD', 'PMD'))) {
            return true;
        } else if (strpos($this->_value, 'PMD.' . $rule->getName()) !== false) {
            return true;
        }
        return (stripos($rule->getName(), $this->_value) !== false);
    }
}
