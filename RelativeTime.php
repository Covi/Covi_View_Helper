<?php
/*
 * RelativeTime.php Covi View Helper
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
 * View Helper TimeSince.
 * 
 * @todo TODO Extender de la clase principal Covi para establecer, por ejemplo, directorios, traducciones...
 */
class Covi_View_Helper_RelativeTime extends Zend_View_Helper_Abstract
{
	public static function relativeTime($unixtime = null, $to = null, $accuracy = 2, $splitter = ', ')
	{
		// Comprobación inicial para no seguir:
		if ( empty($unixtime) ) 
			return '';

		// -------------------------------------------------- /
		// Fecha Zend: Asegurar un timestamp válido. TODO gestionar excepciones¿?

		$date			= new Zend_Date($unixtime);
		$unixtime		= $date->get(Zend_Date::TIMESTAMP);


		// Parámetros base:
		$return			= '';

		$filter			= new Zend_Filter();
		$filter->addFilter(new Zend_Filter_Alpha())
			->addFilter(new Zend_Filter_StringToLower())
			->addFilter(new Zend_Filter_StripTags());

		// TODO Añadir po de traducciones en /languages
		$translations	= array('year'	=> array('year',	'years'), 
								'month'	=> array('month',	'months'),
								'week'	=> array('week',	'weeks'), 
								'day'	=> array('day',		'days'), 
								'h'		=> array('hour',	'hours'), 
								'min'	=> array('minute',	'minutes'), 
								's'		=> array('second',	'seconds'));


		// Limpieza:
		$unixtime	= null !== $unixtime	? intval($unixtime)	: time();
		$to			= null !== $to			? intval($to)		: time();
		$accuracy	= intval($accuracy);
		$splitter	= empty($splitter) ? (string) $filter->filter($splitter) : ', ';


		// Zend_Measure: 
		// -------------------------------------------------- /
		$measure		= array();
		$chunks			= array(
							Zend_Measure_Time::YEAR, 
							Zend_Measure_Time::MONTH, 
							Zend_Measure_Time::WEEK, 
							Zend_Measure_Time::DAY, 
							Zend_Measure_Time::HOUR, 
							Zend_Measure_Time::MINUTE, 
							Zend_Measure_Time::SECOND
						);
		$mt				= new Zend_Measure_Time($unixtime);
		$units			= $mt->getConversionList();


		// Locale y Translate: 
		// -------------------------------------------------- /

		// Locale:
		if ( !Zend_Registry::isRegistered('Zend_Locale') ) 
			$locale 	= new Zend_Locale();
		else 
			$locale		= Zend_Registry::get('Zend_Locale');

		// Translate:
		// - Caché para translate
		$frontendOptions	= array(
			'lifetime'					=> 31536000,		// 1 año, es difícil que cambie
			'automatic_serialization'	=> true
		);
		$backendOptions		= array(
			'cache_file_perm'			=> 0666
		);

		$Cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
		Zend_Translate::setCache($Cache);

		// - Translate
		$translate		= null;
		$fileTranslate	= realpath(dirname(__FILE__) . "/../../data/locale/dates_{$locale}.mo");

		if ( @file_exists($fileTranslate) ) {
			$translate	= new Zend_Translate(array(
										'adapter'	=> 'gettext', 
										'content'	=> $fileTranslate, 
										'locale'	=> "{$locale}"));

			$translate->addTranslation($fileTranslate, "{$locale}");
		}

		// Run...
		// -------------------------------------------------- /
		if ( $to > $unixtime ) 
			$unixtime	= $to - $unixtime;
		else 
			$unixtime	= $unixtime - $to;


		for ( $i = 0; $i < count($chunks); $i++ ) {

			$chunk_seconds = $units[$chunks[$i]][0];

			if ( $unixtime >= $chunk_seconds ) {
				$measure[$units[$chunks[$i]][1]] = floor($unixtime / $chunk_seconds);
				$unixtime %= $chunk_seconds;
			}
		}

		$measure	= array_slice($measure, 0, $accuracy, true);

		foreach ( $measure as $key => $val ) {
			$unit	= $translations[$key];
			$unit	= ( $val == 1 ) ? $unit[0] : $unit[1];

			// Traducción:
			if ( $translate instanceof Zend_Translate ) 
				$unit	= $translate->_($unit);

			// Texto final:
			$return	.= $val . ' ' . $unit . $splitter;
		}

		// Return:
		return substr($return, 0, 0 - strlen($splitter));
	}
}
