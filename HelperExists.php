<?php
/*
 * HelperExists.php View Helper
 * 
 * Copyright 2012 J. A. Cobo (aka Covi) <cobo.juanantonio@gmail.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */
/**
 * HelperExists View Helper.
 *
 * @author J.A.Cobo (aka Covi)
 * @package Zend_Framework
 * @subpackage Covi_View_Helper
 * @category View Helper
 */
class Covi_View_Helper_HelperExists extends Zend_View_Helper_Abstract
{
	public function helperExists($name)
	{
		return (bool) $this->view->getPluginLoader('helper')->load($name, false);
	}
}
