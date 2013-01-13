<?php
/*
 * Schemaorg.php
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
 * SchemaOrg View Helper
 * 
 * @author J.A.Cobo (aka Covi)
 * @package Zend_Framework
 * @subpackage Covi_Helper
 * @category View Helper
 * 
 * @link http://schema.org/docs/gs.html
 */
class Covi_View_Helper_Schemaorg 
	extends Zend_View_Helper_HtmlElement
{
	// Atributos:
	public $itemtype	= 'Thing';
	public $itemprops	= array();

	/**
	 * Constructor
	 */
	public function schemaorg($type = 'Thing')
	{
		$this->itemtype		= new Covi_Model_Microdata_Factory($type);
		return $this; // Fluent interface
	}

	/**
	 * Type
	 */
	public function itemtype()
	{
		return $this->itemtype->url;
	}

	/**
	 * Scope
	 */
	public function itemscope($echo = true)
	{
		if ( true == $echo ) 
			echo 'itemscope' . $this->_htmlAttribs(array('itemtype' => $this->itemtype()));
		else 
			return 'itemscope' . $this->_htmlAttribs(array('itemtype' => $this->itemtype()));
	}

	/**
	 * Prop
	 */
	public function itemprop($prop = 'prop')
	{
		$return = '';
		if ( isset($this->itemtype->getProperties()->{$prop}) )
			$return	= $prop;
		elseif ( isset($this->itemtype->getProperties(true)->{$prop}) )
			$return	= $prop;
		return $this->_htmlAttribs(array('itemprop' => $return));
	}

	/**
	 * to String
	 */
	public function toString()	{ return $this->itemtype(); }
	public function __toString()	{ return $this->toString(); }

	/**
	 * to Array
	 */
	public function toArray()
	{
		return array('itemtype' => $this->itemtype());
	}

	/**
	 * to Element
	 */
	public function toElement()
	{
		return $this->_htmlAttribs($this->toArray());
	}

	/**
	 * Sobreescribir propiedades
	 */
	public function overrideProperties($properties = array(), $specific = false, $clean = true)
	{
		$this->itemtype->overrideProperties((array) $properties, $specific, $clean);
		return $this->itemtype;
	}
}
