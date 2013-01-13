<?php
/* 						- CoviTheme SiteUrl View Helper -
 @author J.A.Cobo (aka Covi)
 @url http://culturadigital.org
 @version 0.1
 @license GPL 3.0
						- CoviTheme SiteUrl View Helper -

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
 * CoviTheme SiteUrl View Helper
 *
 * @author J.A.Cobo (aka Covi)
 * @package Wordpress
 * @subpackage CoviTheme
 */
class Zend_View_Helper_SiteUrl 
	extends Zend_View_Helper_Abstract
{
	// Atributos:
	protected $_urls 				= array();

	// The class must have a public method that matches the helper name
	public function siteUrl($type = 'server')
	{
		if ( empty($this->_urls) ) 
			$this->setUrls();

		$filter		= new Zend_Filter();
		$filter 	->addFilter(new Zend_Filter_Alpha())
					->addFilter(new Zend_Filter_StringToLower());

		$type 		= $filter->filter($type);

		$validator	= new Zend_Validate();
		$validator 	->addValidator(new Zend_Validate_StringLength(array('min' => 4, 'max' => 12)))
					->addValidator(new Zend_Validate_Alnum());

		if ( !$validator->isValid($type) || !array_key_exists( (string) $type, (array) $this->_urls) ) 
			$type		= 'server';

		try {
			$uri 		= Zend_Uri::factory($this->_urls["{$type}"]);
		} catch ( Zend_Uri_exception $e ) {
			// TODO Manejar la excepción Covi_Exception, etc o retornar algún tipo de url válida
			throw new Exception('URI no válida: ' . $e->getMessage(), 1);
		}

		try {
			$hostnameValidator = new Zend_Validate_Hostname(Zend_Validate_Hostname::ALLOW_ALL);
			if ( !$hostnameValidator->isValid($uri->getHost()) )
				throw new Exception('Host no válido en la url: ' . $uri->getHost(), 1);
		} catch ( Zend_Validate_Hostname_Exception $e ) {
			throw new Exception('Host no válido: ' . $e->getMessage(), 1);
		}

		// Return
		return $uri->getUri();
	}

	/**
	 * Set Urls
	 * 
	 * @todo Pasar sólo un hostname y generar subdominios o rutas en base a él.
	 */
	public function setUrls($urls = array())
	{
		// Si no se manda nada sólo usamos una pública con el host actual
		if ( empty($urls) ) 
			$urls 	= array('server' => "http://{$_SERVER['HTTP_HOST']}");

		if ( empty($urls['server']) ) 
			$urls['server'] = "http://{$_SERVER['HTTP_HOST']}";

		foreach ( $urls as $key => $value ) {
			$this->addUrl($key, $value);
		}
	}

	/**
	 * Get Urls
	 */
	public function getUrls($type = '')
	{
		if ( empty($this->_urls) ) 
			$this->setUrls();

		$filter = new Zend_Filter();
		$filter	->addFilter(new Zend_Filter_Alpha())
				->addFilter(new Zend_Filter_StringToLower());
		$type	= $this->view->escape($filter->filter($type));


		if ( array_key_exists((string) $type, $this->_urls) ) 
			return $this->_urls["{$type}"];

		return $this->_urls;
	}

	/**
	 * Add Url
	 */
	public function addUrl($key = '', $url = '')
	{
		// Si no se manda nada sólo usamos una pública con el host actual
		if ( empty($url) || empty($key) ) 
			throw new Exception('Clave para la url no válida o URL vacía');

		try {
			$uri 		= Zend_Uri::factory($url);
		} catch ( Zend_Uri_exception $e ) {
			// TODO Manejar la excepción Covi_Exception, etc o retornar algún tipo de url válida
			throw new Exception('URI no válida: ' . $e->getMessage(), 1);
		}

		$this->_urls["{$key}"] = $uri;

		return $this;
	}


	// -------------------------------------------------------------- /
	// HELPERS

	/**
	 * Get registry value
	 */
	private function _getRegistryValue($value = '')
	{
		if ( Zend_Registry::isRegistered($value) ) 
			return Zend_Registry::get($value);
		return '';
	}
}
