<?php
/* 						- CoviTheme Gravatar View Helper -
 @author J.A.Cobo (aka Covi)
 @url http://culturadigital.org
 @version 0.1
 @license GPL 3.0
						- CoviTheme Gravatar View Helper -

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
 * CoviTheme Gravatar View Helper
 *
 * @author J.A.Cobo (aka Covi)
 * @package Zend_Framework
 * @subpackage Covi_Helper
 * @category View Helper
 * @todo TODO Procesar el cache desde el Helper o no.
 *
 * View Helper propio para Gravatar, que nos permite 
 * obtener la Url de la imagen para poder cachearla.
 * Esto nos da la posibilidad de guardarla en binario y renderizarla en base64.
 */
class Covi_View_Helper_AuthorGravatar extends Zend_View_Helper_Gravatar
{
   /**
     * Returns an avatar from gravatar's service.
     *
     * $options may include the following:
     * - 'img_size' int height of img to return
     * - 'default_img' string img to return if email adress has not found
     * - 'rating' string rating parameter for avatar
     * - 'secure' bool load from the SSL or Non-SSL location
     *
     * @see    http://pl.gravatar.com/site/implement/url
     * @see    http://pl.gravatar.com/site/implement/url More information about gravatar's service.
     * @param  string|null $email Email adress.
     * @param  null|array $options Options
     * @param  array $attribs Attributes for image tag (title, alt etc.)
     * @return Zend_View_Helper_Gravatar
     */
    public function authorGravatar($email = "", $options = array(), $attribs = array())
    {
        $this->setEmail($email);
        $this->setOptions($options);
        $this->setAttribs($attribs);
        return $this;
    }

	public function getAvatarSrc()
	{
		return $this->_getAvatarUrl();
	}

	public function getImageSrc()
	{
		return $this->_getAvatarUrl();
	}
}
