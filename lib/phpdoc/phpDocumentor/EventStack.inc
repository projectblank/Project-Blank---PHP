<?php
/**
 * An Event Stack for inter-program communication, particularly for parsing
 *
 * phpDocumentor :: automatic documentation generator
 * 
 * PHP versions 4 and 5
 *
 * Copyright (c) 2000-2007 Joshua Eichorn
 * 
 * LICENSE:
 * 
 * This library is free software; you can redistribute it
 * and/or modify it under the terms of the GNU Lesser General
 * Public License as published by the Free Software Foundation;
 * either version 2.1 of the License, or (at your option) any
 * later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @category  ToolsAndUtilities
 * @package   phpDocumentor
 * @author    Joshua Eichorn <jeichorn@phpdoc.org>
 * @copyright 2000-2007 Joshua Eichorn
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   CVS: $Id: EventStack.inc,v 1.1.6.2 2012/02/01 13:45:14 alair.tavares Exp $
 * @filesource
 * @link      http://www.phpdoc.org
 * @link      http://pear.php.net/PhpDocumentor
 * @since     0.1
 * @todo      CS cleanup - change package to PhpDocumentor
 */
/**
 * An event Stack
 * 
 * @category ToolsAndUtilities
 * @package  phpDocumentor
 * @author   Joshua Eichorn <jeichorn@phpdoc.org>
 * @license  http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version  Release: 1.4.3
 * @link     http://www.phpdoc.org
 * @link     http://pear.php.net/PhpDocumentor
 * @todo     CS cleanup - change package to PhpDocumentor
 */
class EventStack
{
    /**
     * The stack
     * @var array
     */
    var $stack = array(PARSER_EVENT_NOEVENTS);

    /**
     * The number of events in the stack
     * @var integer
     */
    var $num = 0;

    /**
     * Push an event onto the stack
     *
     * @param int $event All events must be constants
     *
     * @return void
     */
    function pushEvent($event)
    {
        $this->num = array_push($this->stack, $event) - 1;
    }

    /**
     * Pop an event from the stack
     *
     * @return int An event
     */
    function popEvent()
    {
        $this->num--;
        return array_pop($this->stack);
    }

    /**
     * Get the current event
     *
     * @return int An event
     */
    function getEvent()
    {
        return $this->stack[$this->num];
    }
}
