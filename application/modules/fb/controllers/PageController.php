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



class Fb_PageController extends FbCommonController

{
	/**
	 * Function init
	 *
	 * This function is used for initialization. Also include necessary javascript files.
	 *
	 * Date Created: 2011-09-19
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Jayesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/  
	 public function init()
	 {
		parent::init();
		 /* Initialize action controller here */
		Zend_Loader::loadClass('Models_Product');
	 }
	
	/**
	 * Function indexAction
	 *
	 * This function is used for displays the product page. 
	 *
	 * Date Created: 2011-09-19
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Jayesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/  
	 
	 public function indexAction()
	 {
		global $mysession;
		// action body
		print "test";die;
	 }
	 
	/**
	 * Function aboutusAction
	 *
	 * This function is used for displays the about us page. 
	 *
	 * Date Created: 2011-09-20
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Jayesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/  
	 public function aboutusAction()
	 {
	 
	 }
	 
	/**
	 * Function contactusAction
	 *
	 * This function is used for displays the contact us page. 
	 *
	 * Date Created: 2011-09-20
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Jayesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/  
	 public function contactusAction()
	 {
	 
	 }
}
?>