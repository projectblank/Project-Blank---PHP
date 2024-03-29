<?php
/**
 * startup file
 * 
 * phpDocumentor :: automatic documentation generator
 * 
 * PHP versions 4 and 5
 *
 * Copyright (c) 2000-2007 Joshua Eichorn, Gregory Beaver
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
 * @author    Gregory Beaver <cellog@php.net>
 * @copyright 2000-2007 Joshua Eichorn, Gregory Beaver
 * @license   http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version   CVS: $Id: phpdoc.inc,v 1.1.6.2 2012/02/01 13:45:14 alair.tavares Exp $
 * @link      http://www.phpdoc.org
 * @link      http://pear.php.net/PhpDocumentor
 * @since     0.1
 * @filesource
 * @todo      CS cleanup - change package to PhpDocumentor
 */


// set up include path so we can find all files, no matter what
$a = explode('/', str_replace('\\', '/', dirname(realpath(__FILE__))));
array_pop($a);
$GLOBALS['_phpDocumentor_install_dir'] = join('/', $a);
// add my directory to the include path, and make it first, should fix any errors
if (substr(PHP_OS, 0, 3) == 'WIN')
ini_set('include_path', 
    $GLOBALS['_phpDocumentor_install_dir'].';'.ini_get('include_path'));
else
ini_set('include_path', 
    $GLOBALS['_phpDocumentor_install_dir'].':'.ini_get('include_path'));

/**
 * All command-line handling from previous version has moved to here
 *
 * Many settings also moved to phpDocumentor.ini
 */
require_once "phpDocumentor/Setup.inc.php";

$phpdoc = new phpDocumentor_setup;
$phpdoc->readCommandLineSettings();
$phpdoc->setupConverters();
$phpdoc->createDocs();
?>
