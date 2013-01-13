<?php
/*
 * TwitterEntities Parser Zend View Helper
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
 * @see Zend_View_Helper_Abstract
 */
require_once 'Zend/View/Helper/Abstract.php';

/**
 * Covi View Helper Twitter Entities
 * 
 * @author Juan Antonio Cobo
 * @package Zend_Framework
 * @subpackage Covi
 * @category Helper
 */
class Covi_View_Helper_TwitterEntities extends Zend_View_Helper_HtmlElement {

	public function twitterEntities($tweet = null)
	{
		$convertedEntities = array();

		// Check TODO Exception
		if ( null == $tweet || empty($tweet) ) 
			return;

		// Convert to Object main level
		if ( !is_object($tweet) ) 
			$tweet	= (object) $tweet;

		// No entities
		if ( !isset($tweet->entities) ) 
			return $tweet->text;


		// -------------------------------------------------- /
		// Parámetros base

		$twitter_url	= 'https://twitter.com';

		$urlAttrs		= array(
							'target'	=> '_blank', 
							'rel'		=> 'nofollow', 
							'class'		=> 'twitter-link'
						);

		$hashtagAttrs	= array(
							'target'	=> '_blank', 
							'rel'		=> 'nofollow', 
							'class'		=> 'hashtag'
						);

		$mentionAttrs	= array(
							'rel'		=> 'nofollow', 
							'class'		=> 'twitter-atreply'
						);

		$html			= '';


		// -------------------------------------------------- /
		// Parsear

		$founds				= array();
		$replaces			= array();
		$objects			= array();

		foreach ( $tweet->entities as $type => $type_entity ) {

			$type_entity	= (object) $type_entity;

			foreach ( $type_entity as $entity ) {

				$entity		= (object) $entity;

				$pos		= $entity->indices[0]; // Var corta
				$text		= mb_substr($tweet->text, $pos, $entity->indices[1] - $pos, 'utf-8');
				$founds[]	= $text;


				// -------------------------------------------------- /
				// Urls

				if ( $type === 'urls' ) {

					// -------------------------------------------------- /
					// Youtube. TODO Mejorar, menos html

					if ( stristr($entity->display_url, 'youtube') ) {
						$object				= str_replace('watch?v=', 'v/', $entity->expanded_url) 
											. '?version=3&amp;hl=es_ES&amp;fs=1&amp;hd=1';

						$name				= '@Youtube';

						$mentionAttrs['data-screen-name'] = $name;

						$objects[]			= '<br /><a href="#video-' . $tweet->id . '" ' 
											. 'class="toggle toggleClosed" ' 
											. 'title="Mostrar / Ocultar vídeo">Mostrar / Ocultar vídeo</a>' 
											. '<br />' 

											. '<div id="video-' . $tweet->id . '">' 
												. $this->view->htmlObject($object, 
													'application/x-shockwave-flash', 
													array('width'	=> '480', 'height' => '270'), 
													array('movie'	=> $object)) . '</br />'

												. 'Vía ' . $this->view->htmlLink(
													"{$twitter_url}/intent/user?screen_name={$name}", 
													$name, $name, $mentionAttrs) 
											. '</div>';
					}

					$url		= isset($entity->expanded_url) 
									? $entity->expanded_url : $entity->display_url;
					$title		= $this->view->escape($url);
					$attrs		= $urlAttrs;


				// -------------------------------------------------- /
				// Hashtags

				} elseif ( $type == 'hashtags' ) {

					$url		= $twitter_url . '/search?q=' . urlencode($text);
					$attrs		= $hashtagAttrs;


				// -------------------------------------------------- /
				// Menciones

				} elseif ( $type == 'user_mentions' ) {

					$url		= $twitter_url . '/intent/user?screen_name=' 
								. $this->view->escape($entity->screen_name);
					$attrs		= $mentionAttrs;
					$attrs['data-screen-name'] = $entity->screen_name;

				// -------------------------------------------------- /
				// Media.
				// Ojo, sólo son los archivos incrustrados vía Twitter, no enlaces a Youtube p.ej.
				// @link https://dev.twitter.com/docs/platform-objects/entities#obj-media

				} elseif ( 'media' == $type ) {

					$url		= isset($entity->expanded_url) 
									? $entity->expanded_url : $entity->display_url;
					$url		= str_replace($twitter_url, $twitter_url . '/intent', $url);
					$title		= $this->view->escape($url);
					$attrs		= $urlAttrs;
				}

				// -------------------------------------------------- /
				// Composición final

				$title		= $this->view->escape($text);
				$link		= $this->view->htmlLink($url, $text, $title, $attrs);

				$replaces[]	= $link;
			}
		}



		// -------------------------------------------------- /
		// Remplazos

		$html	= str_replace($founds, $replaces, $tweet->text);

		// Añadir objetos desplegables
		if ( !empty($objects) ) 
			$html	.= implode('', $objects);


		// -------------------------------------------------- /
		// Return

		return $html;
	}
}
