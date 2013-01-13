<?php
/*
 * Covi View Helper Tidy
 * 
 * Copyright 2012 Juan Antonio Cobo <cobo.juanantonio@gmail.com>
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
 * Covi View Helper Tidy
 * 
 * @author Juan Antonio Cobo
 * @package Zend_Framework
 * @subpackage Covi
 * @category Helper
 * @uses Tidy
 */
class Covi_View_Helper_Tidy extends Zend_View_Helper_Abstract
{
	private $_config = array();

	public function tidy($html = '', $charset = 'utf8') 
	{
		$this->_config['show-body-only']	= true;
		$this->_config['wrap']				= 0;
		$this->_config['wrap-attributes']	= 0;
		$this->_config['preserve-entities']	= 1;
		$this->_config['output-xhtml']		= 1;
		$this->_config['new-inline-tags']	= 'go';
		$this->_config['fix-bad-comments']	= 'no';
		$this->_config['hide-comments']		= 'no';
		$this->_config['drop-empty-paras']	= 'yes';

		if ( function_exists('tidy_repair_string') ) 
			return tidy_repair_string($html, $this->_config, $charset);

		return $html;
	}
}
