<?php
/*
 * HtmlHead.php
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
 * HtmlHead View Helper
 * 
 * @author J.A.Cobo (aka Covi)
 * @package Zend_Framework
 * @category View Helper
 */
class Covi_View_Helper_HtmlHead 
	extends  Zend_View_Helper_Abstract
{
	// Atributos
	protected $_title			= null;
	protected $_description	= null;
	protected $_keywords		= null;
	protected $_author			= null;

	/**
	 * Generar htmlHead
	 */
	public function htmlHead($auto = 'true')
	{
		// -------------------------------------------------- /
		// Parámetros base

		$renderer			= $this->view;
		if ( !isset($renderer->model) ) 
			return;
		$model				= $renderer->model;


		// -------------------------------------------------- /
		// Atributos

		$this->_microdata	= $model->microdata; // TODO Forzar objeto??
		$this->_title		= $model->title;
		$this->_description	= $model->description;
		$this->_keywords	= $model->keywords;
		$this->_author		= $model->author->microdata->getProperties();


		// -------------------------------------------------- /
		// Run en modo automático. La otra opción es ir llamando a los métodos internos uno a uno.

		if ( true == $auto ) {

			// -------------------------------------------------- /
			// Html Tag

			$this->setHtmlTag($this->_microdata->itemtype());


			// -------------------------------------------------- /
			// Metas

			$renderer->headMeta()->setName('description',	$renderer->minify($this->_description));
			$renderer->headMeta()->setName('keywords',		$this->_keywords);
			$renderer->headMeta()->setName('author',		$this->_author->name);


			// -------------------------------------------------- /
			// HeadTitle

			$type	= '';
			if ( isset($model->type) ) 
				$type	= $model->type;

			// TODO Traducir
			$extra	= '';
			if ( !empty($renderer->params['page']) ) {
				$page	= $renderer->params['page'];
				if ( strpos($page, 'comment-page-') ) 
					$extra	.= $renderer->translate('Comment Page') . ' ' . substr($page, 13, 1);
				else 
					$extra	.= $renderer->translate(array('Page', 'Pages', 1)) . " {$page}";
			}

			$this->setHtmlTitle($this->_title, $type, $extra);


			// -------------------------------------------------- /
			// HeadLinks

			$links	= array(
				'author'	=> array($this->_author->url,	'text/html'), 
				'image_src'	=> array($model->image,			'image/jpg')
			);

			$this->setHeadLinks($links);


			// -------------------------------------------------- /
			// Estilo

			if ( !empty($model->embedStyle) ) 
				$renderer->headStyle()->appendStyle($model->embedStyle);
		}


		// Fluent interface por si se hace por métodos separados
		return $this;
	}



	// -------------------------------------------------- /
	// MÉTODOS INTERNOS

	/**
	 * Setup HtmlTag
	 */
	public function setHtmlTag($type = '')
	{
		$renderer			= $this->view;

		$htmlTag			= $renderer->htmlTag();
		$htmlTag->getElement()->setAttribute('itemscope',	'');
		$htmlTag->getElement()->setAttribute('itemtype',	$type);
		$renderer->htmlTag		= $htmlTag->getHtmlTag();

		// Fluent interface por si se hace por métodos separados
		return $this;
	}

	/**
	 * Setup Html Title
	 */
	public function setHtmlTitle($title = '', $type = '', $extra = '')
	{
		$renderer = $this->view;
		$renderer
				->headTitle($renderer->escape("{$type}"), 'PREPEND')
				->headTitle($renderer->escape("{$title}"), 'PREPEND')
				->headTitle($renderer->escape("({$extra})"), 'APPEND')
				;

		// Fluent interface por si se hace por métodos separados
		return $this;
	}

	/**
	 * Setup HeadLinks
	 */
	public function setHeadLinks($links = array())
	{
		$renderer			= $this->view;

		foreach ( (array) $links as $rel => $properties ) 
		{
			list($href, $type) = $properties;
			$renderer->headLink(array(
				'href'	=> (string) $href, 
				'type'	=> (string) $type, 
				'rel'	=> (string) $rel
			));
		}

		// Fluent interface por si se hace por métodos separados
		return $this;
	}
}
