<?php
/*
 * Covi View Helper Minify
 * 
 * Copyright 2012 J. A. Cobo aka Covi <cobo.juanantonio@gmail.com>
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
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * @see Zend_Loader
 */
require_once 'Zend/Loader.php';

/**
 * Covi View Helper Minify
 * 
 * @author J.A.Cobo (aka Covi)
 * @package Zend_View
 * @subpackage Zend_View_Helper
 * @category Zend
 * @fixme Esto sólo es para HTML. Se puede incluir $type y minificar lo que se quiera.
 */
class Covi_View_Helper_Minify extends Zend_View_Helper_Abstract
{
	public function minify($html = '')
	{
		if ( empty($html) ) 
			return $html;
			//throw new Exception('No se ha pasado contenido a minificar.', E_USER_ERROR);

		// Set include path:
		set_include_path(implode(PATH_SEPARATOR, array(
			LIBRARY_PATH . DS . 'Minify', 
			get_include_path()
		)));

		// Incluir Minify:
		if ( !@class_exists('Minify_HTML') ) 
			Zend_Loader::loadClass('Minify_HTML');

		if ( !@class_exists('Minify_CSS') ) 
			Zend_Loader::loadClass('Minify_CSS');

		// Si usas esto puedes usarlo para todo CSS y JS:
		if ( !@class_exists('JSMinPlus') ) 
			Zend_Loader::loadClass('JSMinPlus');

		// Run...
		// -------------------------------------------------- /
		$result 		= Minify_HTML::minify($html, array(
							'cssMinifier'	=> array('Minify_CSS',	'minify'), 
							'jsMinifier'	=> array('JSMinPlus',	'minify') 
						));

		// Return:
		return $result;
	}
}
