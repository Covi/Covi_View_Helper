<?php
/**
 * Formats a date as the time since that date.
 *
 * This is useful for creating "Last updated 5 week and 4 days ago" strings
 *
 * @author	 Geoffrey Tran
 * @license	http://www.zym-project.com/license New BSD License
 * @package	Zym_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2008 Zym. (http://www.zym-project.com/)
 */
class Covi_View_Helper_TimeSince extends Zend_View_Helper_Abstract
{
	/**
	 * Formats a date as the time since that date
	 *
	 * @param integer $timestamp
	 * @param integer $time	  Timestamp to use instead of time()
	 */
	public function timeSince($timestamp, $time = null)
	{
		// Usamos RelativeTime que usa Zend_Translate del registro automático y Zend_Measure_Time.
		if ( class_exists('Covi_View_Helper_RelativeTime') ) 
			return Covi_View_Helper_RelativeTime::relativeTime($timestamp, $time);
		return false;
	}
}
