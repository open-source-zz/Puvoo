<?php
/**
 * Puvoo
 * http://www.puvoo.com
 *
 * NOTICE OF LICENSE
 *
 * Copyright (c) 2011
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@puvoo.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Puvoo to newer
 * versions in the future. If you wish to customize Puvoo for your
 * needs please refer to http://www.puvoo.com for more information.
 */
 
/**
 * Zend_View_Helper_Translate class. 
 *
 * This class file extends Zend_View_Helper_Abstract.
 * It is used to get language variable value in view of our application
 *
 *
 * @author Amar
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Zend_View_Helper_Translate extends Zend_View_Helper_Abstract
{
	
	private $language_value = "";
 
	
    public function Translate($lkey)
    {
	   
        $translate = Zend_Registry::get('Zend_Translate');
        $this->$language_value = $translate->_($lkey);
		
        return $this->$language_value;
    }
 
}
?>