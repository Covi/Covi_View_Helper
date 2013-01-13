<?php
/*
 * Subdomain.php
 * 
 * Copyright 2013 Juan Antonio Cobo <cobo.juanantonio@gmail.com>
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


class Covi_View_Helper_Subdomain 
	extends Zend_View_Helper_Abstract
{
	public function subdomain($subdomain = '', $uri = '')
	{
		return $this->direct($subdomain, $uri);
	}

	public function direct($subdomain = '', $uri = '')
	{
		// -------------------------------------------------- /
		// Parámetros

		$filter			= new Zend_Filter_Alnum();
		$sign			= array('-', '_', '~', '.');
		$flag			= array('RRR', 'XXX', 'YYY', 'ZZZ');

		// Parámetros finales
		$uri			= empty($uri) ? $this->view->serverUrl() : (string) $uri;
		if ( empty($subdomain) ) 
			return (string) $uri;

		$subdomain		= str_replace($sign, $flag, $subdomain);
		$subdomain		= strtolower(str_replace($flag, $sign, $filter->filter($subdomain)));


		// -------------------------------------------------- /
		// Run...

		try {
			$Uri			= Zend_Uri::factory($uri);
		} catch ( Zend_Uri_exception $e ) {
			// TODO Manejar la excepción Covi_Exception, etc o retornar algún tipo de url válida
			throw new Exception('URI no válida: ' . $e->getMessage(), 1);
		}

		$host			= explode('.', $Uri->getHost());
		$subdomains		= (array) array_slice($host, 0, count($host) - 2);


		// -------------------------------------------------- /
		// Return

		if ( empty($subdomains) ) {
			$Uri->setHost($subdomain . '.' . $Uri->getHost());
			return (string) $Uri;
		} else {
			return (string) str_replace(current($subdomains), $subdomain, $Uri);
		}
		return (string) $Uri;
	}
}
