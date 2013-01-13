<?php
/*
 * Document.php
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
 * View Helper Document
 * 
 */
class Covi_View_Helper_Document extends Zend_View_Helper_Abstract
{
	# Atributos
	private $_doctypes 	= array();
	private $_doc			= null;

	/**
	 * Constante default document
	 */
	const DEF_DOC			= 'HTML5';
	const DEF_ID			= 'root-document';

	/**
	 * Namespaces
	 * @link http://dev.w3.org/html5/spec/namespaces.html#namespaces
	 * 
	 * 2.9 Namespaces
	 * The HTML namespace is: http://www.w3.org/1999/xhtml
	 * The MathML namespace is: http://www.w3.org/1998/Math/MathML
	 * The SVG namespace is: http://www.w3.org/2000/svg
	 * The XLink namespace is: http://www.w3.org/1999/xlink
	 * The XML namespace is: http://www.w3.org/XML/1998/namespace
	 * The XMLNS namespace is: http://www.w3.org/2000/xmlns/
	 */
	const HTML_NS		= 'http://www.w3.org/1999/xhtml';
	const MathML_NS		= 'http://www.w3.org/1998/Math/MathML';
	const SVG_NS		= 'http://www.w3.org/2000/svg';
	const XLink_NS		= 'http://www.w3.org/1999/xlink';
	const XML_NS		= 'http://www.w3.org/XML/1998/namespace';
	const XMLNS_NS		= 'http://www.w3.org/2000/xmlns/';
	const XMLEvents_NS	= 'http://www.w3.org/2001/xml-events';

	// Namespaces foráneos
	const DC_NS			= 'http://purl.org/dc/elements/1.1/';
	const DCTERMS_NS	= 'http://purl.org/dc/terms';
	const CC_NS			= 'http://creativecommons.org/ns#';

	// Esquemas
	const XMLSchema		= 'http://www.w3.org/2001/XMLSchema-instance';
	const SchemaRDFa	= 'http://www.w3.org/MarkUp/SCHEMA/xhtml-rdfa-2.xsd';

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$viewDoctye			= new Zend_View_Helper_Doctype();
		$parentDoctypes		= $viewDoctye->getDoctypes();
		$newDoctypes		= array();
		$myDoctypes			= array(
			// -------------------------------------------------- /
			// XHTML

			// XHTML1.0 Estricto
			'XHTML1_STRICT' => array(
				'doctype' 	=> array(
					'qualifiedName' 	=> 'html', 
					'publicId'			=> '-//W3C//DTD XHTML 1.0 Strict//EN', 
					'systemId'			=> 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd', 
				), 
				'document'	=> array(
					'nameSpace'			=> self::HTML_NS, 
					'element'			=> 'html'
				)
			), 

			// XHTML 1.0 Transicional
			'XHTML1_TRANSITIONAL' => array(
				'doctype' 	=> array(
					'qualifiedName' 	=> 'html', 
					'publicId'			=> '-//W3C//DTD XHTML TRANSITIONAL 1.0//EN', 
					'systemId'			=> 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd', 
				), 
				'document'	=> array(
					'nameSpace'			=> self::HTML_NS, 
					'element'			=> 'html'
				)
			), 

			// XHTML1.1 xml puro
			'XHTML11' => array(
				'doctype' 	=> array(
					'qualifiedName' 	=> 'html', 
					'publicId'			=> '-//W3C//DTD XHTML 1.1//EN', 
					'systemId'			=> 'http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd', 
				), 
				'document'	=> array(
					'nameSpace'			=> self::HTML_NS, 
					'element'			=> 'html'
				)
			), 

			'XHTML1_RDFA'=> array(
				'doctype' 	=> array(
					'qualifiedName' 	=> 'html', 
					'publicId'			=> '-//W3C//DTD XHTML+RDFa 1.0//EN', 
					'systemId'			=> 'http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd', 
				), 
				'document'	=> array(
					'nameSpace'			=> self::HTML_NS, 
					'element'			=> 'html'
				)
			), 

			'XHTML5' => array(
				'doctype' 	=> array(
					'qualifiedName' 	=> 'html', 
					'publicId'			=> '', 
					'systemId'			=> '', 
				), 
				'document'	=> array(
					'nameSpace'			=> self::HTML_NS, 
					'element'			=> 'html'
				)
			), 

			'XHTML_MATH_SVG' => array(
				'doctype' 	=> array(
					'qualifiedName' 	=> 'html', 
					'publicId'			=> '-//W3C//DTD XHTML 1.1 plus MathML 2.0 plus SVG 1.1//EN', 
					'systemId'			=> 'http://www.w3.org/2002/04/xhtml-math-svg/xhtml-math-svg.dtd', 
				), 
				'document'	=> array(
					'nameSpace'			=> self::HTML_NS, 
					'element'			=> 'html'
				)
			), 

			// -------------------------------------------------- /
			// HTML

			'HTML4_LOOSE' => array(
				'doctype' 	=> array(
					'qualifiedName' 	=> 'html', 
					'publicId'			=> '-//W3C//DTD HTML 4.01 Transitional//EN', 
					'systemId'			=> 'http://www.w3.org/TR/html4/loose.dtd', 
				), 
				'document'	=> array(
					'nameSpace'			=> self::HTML_NS, 
					'element'			=> 'html'
				)
			), 

			'HTML5' => array(
				'doctype' 	=> array(
					'qualifiedName' 	=> 'html', 
					'publicId'			=> '', 
					'systemId'			=> '', 
				), 
				'document'	=> array(
					'nameSpace'			=> self::HTML_NS, 
					'element'			=> 'html'
				)
			), 

			// -------------------------------------------------- /
			// SVG

			'SVG' 	=> array(
				'doctype' 	=> array(
					'qualifiedName' 	=> 'svg', 
					'publicId'			=> '-//W3C//DTD SVG 1.1//EN', 
					'systemId'			=> 'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd', 
				), 
				'document'	=> array(
					'nameSpace'			=> self::SVG_NS, 
					'element'			=> 'svg'
				)
			), 

			'SVG_XHTML_MATH' 	=> array(
				'doctype' 	=> array(
					'qualifiedName' 	=> 'svg:svg', 
					'publicId'			=> '-//W3C//DTD XHTML 1.1 plus MathML 2.0 plus SVG 1.1//EN', 
					'systemId'			=> 'http://www.w3.org/2002/04/xhtml-math-svg/xhtml-math-svg-flat.dtd', 
				), 
				'document'	=> array(
					'nameSpace'			=> self::SVG_NS, 
					'element'			=> 'svg'
				)
			)
		);

		foreach ( $parentDoctypes as $name => $doctype ) {

			$doctypeParts	= explode("\"", $doctype);

			if ( isset($doctypeParts[1]) ) {
				$newDoctypes["{$name}"]['doctype']['publicId']	= $doctypeParts[1];
			}

			if ( isset($doctypeParts[3]) ) {
				$newDoctypes["{$name}"]['doctype']['systemId']	= $doctypeParts[3];
			}

			// Para la definición de documento, el original no los tiene:
			if ( stripos($name, 'HTML') == 1 ||  stripos($name, 'XHTML') == 1 ) {
				$newDoctypes["{$name}"]['document']				= array('nameSpace'=> self::HTML_NS, 
																		'element'	=> 'html');
			}

			// (X)HTML5
			if ( 1 == count($doctypeParts) ) {
				$newDoctypes["{$name}"]							= $myDoctypes["{$name}"];
				continue;
			}
		}

		// Unirlos a todos ...y atarlos en las tinieblas XDD
		$intersectDoctypes	= array_intersect_key($myDoctypes, $newDoctypes);
		$diffDoctypes		= array_diff_key($newDoctypes, $myDoctypes);
		$allDoctypes		= array_merge($intersectDoctypes, $diffDoctypes, $myDoctypes);

		// Setup
		$this->_doctypes		= $allDoctypes;
	}

	/**
 	 * Inicializa un DOMDocument bien formado
	 * 
	 * @param  string $document Tipo de documento
	 * @param  array $attrs Root atributos iniciales
	 * @return object DOMDocument
	 */
	public function Document($userDocument = self::DEF_DOC, $attrs = array('id' => self::DEF_ID))
	{
		// -------------------------------------------------- /
		// Parámetros base

		$filter			= new Zend_Filter_Alnum(array('allowwhitespace' => true));


		// -------------------------------------------------- /
		// Limpieza

		$userDocument	= $filter->filter(str_replace('_', ' ', strtoupper($userDocument)));
		$userDocument	= str_replace(' ', '_', $userDocument);
		$attrs			= array_merge(array('id' => self::DEF_ID), (array) $attrs);


		// -------------------------------------------------- /
		// Montando el DOMDocument

		$reqDocument			= (array) $this->getDoctypes($userDocument);
		if ( 2 !== count($reqDocument) ) {
			throw new Exception ('Para crear un DOMDocument necesitamos un array con 2 elementos: ' 
									. 'doctype y documento. Se han pasado ' . count($reqDocument) . ' elementos.');
		}

		if ( !isset($reqDocument['doctype']) || !isset($reqDocument['document']) ) {
			throw new Exception ('El array para DOMDocument necesita un elemento con clave «doctype» y ' 
									. 'un elemento con clave «document». Alguna de las claves no es correcta.');
		}
		extract($reqDocument);

		// DOMImplementation. @see @link http://es.php.net/manual/es/class.domimplementation.php
		$imp 					= new DOMImplementation;

		// Lo hacemos así para no tener que trabajar con los arrays y pasarlos directamente como argumentos.
		$doctype				= call_user_func_array(array($imp, 'createDocumentType'),	$doctype);
		$doc					= call_user_func_array(array($imp, 'createDocument'),		$document);

		// Properties:
		$doc->encoding 			= 'UTF-8';
		$doc->standalone 		= false;	// Lo queremos que pueda usar otros documentos
		$doc->formatOutput 		= false; 	// Sin indentación ni nada (opt)
		$doc->preserveWhiteSpace= false;	// Sin espacios (opt)
		$doc->validateOnParse 	= true;		// Validar al crear

		// -------------------------------------------------- /
		// ROOT Element

		$root 					= $doc->documentElement;


		// -------------------------------------------------- /
		// Namespaces extras
		// Para ayuda, consulta aquí doctypes: 
		// @link http://www.w3.org/QA/2002/04/valid-dtd-list.html
		// OJO, Para poder usar namespaces el documento tiene que ser XHTML de verdad.

		// Namespaces que nos servirán
		$xmlns					= self::XMLNS_NS;
		$xmlschema				= self::XMLSchema;

		// Estos son para RDFa, tienes que hacer otro:
		// Los espacios de nombres para microdatos Schema no son necesarios pero los incluiremos siempre:
		// $root->setAttributeNS($xmlns,		'xmlns:xsi',	$xmlschema);
		// $root->setAttributeNS($xmlschema,	'schemaLocation', self::HTML_NS . ' ' . self::SchemaRDFa);

		// Namespaces que incluiremos siempre que sea XML (de verdad, incluido XHTML5), SVG o Math
		// Si es una dtd (systemId) XML, es decir XHTML de verdad|SVG|Math... incluir estos namespaces
		// OJO, no comprar contra el nombre (publicId) porque es más variable, la dtd es única
		// Además HTML5 no lleva dtd, de ahí, para XHTML5, comparar con la petición
		if (	'XHTML5' === $userDocument				||
				stripos($doctype->systemId, 'xhtml')	|| 
				stripos($doctype->systemId, 'svg')		|| 
				stripos($doctype->systemId, 'math') ) {

			$root->setAttributeNS($xmlns, 'xmlns:svg',		self::SVG_NS);
			$root->setAttributeNS($xmlns, 'xmlns:xlink',	self::XLink_NS);
			$root->setAttributeNS($xmlns, 'xmlns:evt',		self::XMLEvents_NS);
		}

		// SVG y Math
		if ( stripos($doctype->systemId, 'math') || stripos($doctype->systemId, 'svg') ) {
			$root->setAttributeNS($xmlns, 'xmlns:xhtml', self::HTML_NS);
		}

		// Math
		if ( stripos($doctype->systemId, 'math') ) {
			$root->setAttributeNS($xmlns,		'xmlns:mml',		self::MathML_NS);
			$root->setAttributeNS($xmlschema,	'schemaLocation',	self::MathML_NS);
		}


		// -------------------------------------------------- /
		// Atributos del usuario

		$this->fillElementAttrs($root, $attrs);


		// -------------------------------------------------- /
		// Setup y return DOMDocument

		$this->_doc	= $doc;

		return $this->_doc;
	}


	/**
	 * Inicializa un documento DOM bien formado para SVG.
	 */
	public function DocumentSVG($attrs = array('id' => self::DEF_ID))
	{
		// Return:
		return $this->direct('SVG', $attrs);
	}


	/**
	 * Strategy pattern: call helper as broker method
	 * 
	 * @param  string $document Tipo de documento
	 * @param  array $attrs Root atributos
	 * 
	 * @return object DOMDocument
	 */
	public function direct($document = self::DEF_DOC, $attrs = array('id' => self::DEF_ID))
	{
		return $this->Document($document, $attrs);
	}


	/**
	 * get Doctypes
	 */
	public function getDoctypes($doctype = self::DEF_DOC)
	{
		if ( array_key_exists($doctype, (array) $this->_doctypes) ) 
			return $this->_doctypes["{$doctype}"];

		if ( 'all' == $doctype ) 
			return $this->_doctypes;

		return array();
	}

	/**
	 * Obtener otros namespaces
	 */
	/*public function getNamespace($namespace = '')
	{
		Zend_Debug::dump(ReflectionClass::getConstant($namespace));
		exit(__FILE__ . ' :: ' . __LINE__);
		if ( isset(self::$namespace) ) 
			return self::$namespace;
		return null;
	}*/

	/**
	 * to String
	 */
	public function toString()	{ return $this->_doc->saveHTML();	}
	public function __toString()	{ return $this->toString();		}


	/**
	 * Rellena un elemento con sus atributos.
	 * @todo Hacer Helper.
	 * @todo TODO Comprobar si el typecasting de la función va en todas las versiones php "actuales".
	 */
	public function fillElementAttrs(DOMElement $element = null, $attrs = array())
	{
		// TODO Ver si se puede trabajar con el array directamente array_walk, etc
		foreach ( $attrs as $attr => $val ) 
			$element->setAttribute($attr, $val);

		return $element;
	}
	// Alias
	public function fillAttributes(DOMElement $element = null, $attrs = array())
	{
		Zend_Debug::dump($this->fillElementAttrs($element, $attrs));
		return $this->fillElementAttrs($element, $attrs);
	}
}
