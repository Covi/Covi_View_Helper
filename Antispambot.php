<?php
/*
 * Antispambot.php
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
 * Ported of antispambot from Wordpress
 * 
 * @author Juan Antonio Cobo
 * @package Zend
 * @subpackage Zend_View_Helper
 * @category Wordpress
 */
class Covi_View_Helper_Antispambot extends Zend_View_Helper_Abstract
{
	/**
	 * Converts email addresses characters to HTML entities to block spam bots.
	 *
	 * @since 0.71
	 *
	 * @param string $emailaddy Email address.
	 * @param int $mailto Optional. Range from 0 to 1. Used for encoding.
	 * @return string Converted email address.
	 */
	function antispambot($emailaddy = '', $mailto = 0) {

		$emailNOSPAMaddy = '';

		srand ((float) microtime() * 1000000);

		for ( $i = 0; $i < strlen($emailaddy); $i = $i + 1 ) {

			$j = floor(rand(0, 1+$mailto));

			if ( $j == 0 ) {
				$emailNOSPAMaddy .= '&#'.ord(substr($emailaddy,$i,1)).';';
			} elseif ( $j == 1 ) {
				$emailNOSPAMaddy .= substr($emailaddy,$i,1);
			} elseif ( $j == 2 ) {
				$emailNOSPAMaddy .= '%'. zeroise(dechex(ord(substr($emailaddy, $i, 1))), 2);
			}
		}

		$emailNOSPAMaddy = str_replace('@', '&#64;', $emailNOSPAMaddy);

		return $emailNOSPAMaddy;
	}
}
