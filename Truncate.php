<?php
/*
 * Truncate.php
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

class Covi_View_Helper_Truncate
{
	public function truncate($content = '', $length = 100, $postfix = '&hellip;', $prefix = '', $start = 0)
	{
		// -------------------------------------------------- /
		// Check

		if ( empty($content) ) 
			return '';

		$truncated		= (string)	trim($content);
		$length			= (int)		$length;
		$start			= (int)		$start;

		// Retornar si la longitud no es adecuada
		if ( $length < 1 ) 
			return $truncated;


		// -------------------------------------------------- /
		// Run... Usar validadores y filtros Zend

		// Longitud total
		$full_length	= mb_strlen($truncated, 'UTF-8');

		// Recortar
		if ( $full_length > $length ) {
			// Right-clipped
			if ( $length + $start > $full_length ) {
				$start		= $full_length - $length;
				$postfix	= '';
			}

			// Left-clipped
			if ( $start == 0 ) 
				$prefix = '';

			// Do truncate!
			$truncated	= (string) $prefix 
						. (string) trim(mb_substr($truncated, $start, $length, 'UTF-8')) 
						. (string) $postfix;
		}
		
		return (string) $truncated;
	}
}
