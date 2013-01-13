<?php
/*
 * HtmlLink.php View Helper
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
 * HtmlLink View Helper.
 *
 * @author J.A.Cobo (aka Covi)
 * @package Zend_Framework
 * @subpackage Covi_View_Helper
 * @category View Helper
 */
class Covi_View_Helper_HtmlLink extends Zend_View_Helper_HtmlElement
{
	// Atributos
	protected $_format		= '<a href="%1$s" title="%2$s"%4$s>%3$s</a>';
	protected $_htmlLink	= '';

	/**
	 * Output an anchor element
	 *
	 * @param string $href URI of link
	 * @param string $text
	 * @param string $title short description
	 * @param array  $attribs Attribs for the object tag
	 * @return string
	 */
	public function HtmlLink($href = '', $text = 'Sin texto', $title = 'Sin título', 
								array $attribs = array(), $absoluteUrl = false)
	{
		// -------------------------------------------------- /
		// Href

		$href		= isset($href) ? $href : $this->view->baseUrl();

		// En esta versión Zend_Uri no tiene un método registerScheme, por lo tanto lo dejamos sólo para http
		if ( stristr($href, 'http') || stristr($href, 'https') ) 
			$href	= Zend_Uri::factory($href);

		// Relativas
		if ( 0 == stripos($href, '/') ) {
			// Esto es por si queremos rutas absolutas:
			if ( isset($absoluteUrl) && false !== $absoluteUrl ) 
				$href	= $this->view->serverUrl($href);
			$href	= $href;
		}

		// Resto igual
		$attribs['href']	= $href;


		// -------------------------------------------------- /
		// Texto

		$text				= empty($text) ? 'Sin texto' : $text;


		// -------------------------------------------------- /
		// Título

		$attribs['title']	= empty($title) ? $this->view->escape($text) : $this->view->escape($title);

		return '<a' . $this->_htmlAttribs($attribs) . ">{$text}</a>";
	}

	/**
	 * Get format
	 */
	public function getFormat()
	{
		return $this->_format;
	} 
}
