<?php
/*
 * Date.php
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
 * Helper Fecha para la vista
 * 
 * @todo TODO Más argumentos en el caso por ejemplo del formato 'since' u otro helper para ese formato.
 */
class Covi_View_Helper_Date extends Zend_View_Helper_Abstract
{
	public function date($date, $format = 'TIMESTAMP')
	{
		// -------------------------------------------------- /
		// Comprobar si es o no un objeto Zend_Date

		if ( false == ($date instanceof Zend_Date) ) 
			$date	= new Zend_Date((string) $date);

		// -------------------------------------------------- /
		// Run...

		$format	= strtoupper($format);

		if ( 'COVI' == $format || 'HUMAN' == $format ) {
			return $date->get(Zend_Date::WEEKDAY) . ', ' . 
					$date->get(Zend_Date::DATE_LONG) . ' @ ' . 
					$date->get(Zend_Date::TIME_SHORT) . 'h';
		}

		if ( 'SINCE' == $format || 'AGO' == $format || 'HACE' == $format || 'FROM' == $format ) 
			return $this->view->timeSince($date);

		$Zend_Date		= new ReflectionClass('Zend_Date');
		$formatString	= $Zend_Date->getConstant($format);
		return $date->get($formatString);
	}
}
