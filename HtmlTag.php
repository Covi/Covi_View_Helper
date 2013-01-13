<?php
/* 						- HtmlTag View Helper -
 @author J.A.Cobo (aka Covi)
 @url http://culturadigital.org
 @version 0.1
 @license GPL 3.0
						- HtmlTag View Helper -

Copyright (c) 2011 J.A.Cobo (aka Covi)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.*/

/**
 * HtmlTag View Helper
 * 
 * @author J.A.Cobo (aka Covi)
 * @package Zend_Framework
 * @subpackage Covi_Helper
 * @category View Helper
 */
class Covi_View_Helper_HtmlTag extends Zend_View_Helper_HtmlElement
{
	// Atributos:
	protected $_xmlDeclaration	= '';
	protected $_htmlTag		= '';
	protected $_attribs		= array();
	protected $_document		= null;
	protected $_element		= null;
	protected $_saveFormat		= 'saveHTML';

	/**
	 * Montar un tag html al que se le puden pasar parámetros
	 * 
	 * @param array $attribs Array de atributos para el elemento
	 * @param string $extra Atributos extra.
	 * 						Actualmente no se usa ya que usamos el DOM para generar el elemento.
	 */
	public function htmlTag($attribs = array(), $extra = '')
	{
		// -------------------------------------------------- /
		// Parámetros base

		$renderer					= $this->view;

		// Atributos
		$this->_attribs				= (array) $attribs;
		// TODO Comprobar helper Document
		$this->_document			= $renderer->Document($renderer->doctype()->getDoctype(), $this->_attribs);
		$this->_element				= $doc->documentElement;
		$this->_xmlDeclaration		= $this->getXmlDeclaration();
		$this->_htmlTag				= $this->getHtmlTag();

		// Save format
		if ( $this->view->doctype()->isXhtml() ) 
			$this->_saveFormat	= 'saveXML';

		// Setup vista
		$renderer->xmlDeclaration	= $this->_xmlDeclaration;
		$renderer->htmlTag			= $this->_htmlTag;

		// Return
		return $this;
	}

	/**
	 * Get document
	 */
	public function getDocument()
	{
		return $this->_document;
	}

	/**
	 * Get xmlDeclaration
	 */
	public function getXmlDeclaration()
	{
		$doc		= $this->getDocument();
		if ( $this->view->doctype()->isXhtml() ) {
			$xml	= explode("?>", $doc->saveXML());
			return $xml[0] . "?>";
		}
		return '';
	}

	/**
	 * Get element
	 */
	public function getElement()
	{
		return $this->getDocument()->documentElement;
	}

	/**
	 * Obtener el tag html, sólo el tag, no el elemento.
	 * Tenemos que separar la declaración del elemento root para meter el doctype en medio en el layout
	 */
	public function getHtmlTag()
	{
		$renderer	= $this->view;
		$doc		= $this->getDocument();
		$root		= $this->getElement();

		if ( $renderer->doctype()->isXhtml() )
			$this->_htmlTag			= str_replace('"/>', '">', $doc->saveXML($root));
		else
			$this->_htmlTag			= str_replace('</html>', '', $doc->saveHTML($root));

		return $this->_htmlTag;
	}


	public function direct()
	{
		return $this->__toString();
	}
	public function __toString()
	{
		return (string) $this->getHtmlTag();
	}

	// -------------------------------------------------- /
	// SETTERS

	/**
	 * Añadir otros atributos
	 */
	public function addAttributes($attribs = array())
	{
		$attribs		= empty($attribs) ? array() : (array) $attribs;
		foreach ( $attribs as $name => $value ) 
			$this->getElement()->setAttribute($name, $value);
		return $this;
	}
}
