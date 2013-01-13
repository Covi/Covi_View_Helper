<?php
/*
 * HtmlTagDOM.php
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
 * HtmlTagDOM View Helper
 * 
 * @author Juan Antonio Cobo
 * @package Zend_Framework
 * @subpackage Covi_Helper
 * @category View Helper
 */
class Covi_View_Helper_HtmlTagDOM extends Zend_View_Helper_Abstract
{
	// Atributos:
	private 	$_doc			= null;
	protected 	$_root			= null;
	protected	$_htmlTag		= '';

	public function htmlTagDOM($document = 'xhtml5', $attribs = array())
	{
		// Check
		$attribs				= !is_array($attribs)	? array()	: (array) $attribs;
		$document				= !isset($document)		? 'xhtml5'	: (string) $document;

		// Build
		$this->_doc				= $this->view->Document($document, $attribs);
		$this->_root			= $this->_doc->documentElement;
		$this->_htmlTag			= $this->_doc->saveHTML($root);

		// Return
		return $this;
	}

	/**
	 * to String
	 */
	public function __toString() { return $this->getHtmlTag(); }
	public function getHtmlTag() { return str_replace('</html>', '', $this->_htmlTag); }


	/**
	 * Obtener el DOMDOcument
	 */
	public function getDoc()
	{
		return $this->_doc;
	}

	/**
	 * Obtener el elemento root del DOMDocument, normalmente el <html>
	 */
	public function getRoot()
	{
		return $this->_root;
	}

	/**
	 * Añadir otros atributos
	 */
	public function addAttribute($attrib = '', $value = '')
	{
		$this->_root->setAttribute("{$attrib}", "{$value}");
		$this->_htmlTag			= $this->_doc->saveHTML($this->_root);

		return $this;
	}
}
