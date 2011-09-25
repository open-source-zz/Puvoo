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
 * AdminCommonController class.To check admin login and for assigning constants.
 *
 * This class file extends Zend_Controller_Action.It is used to check whether admin is login or not and for assigning path constants,title,etc.
 *
 *
 * @author Amar
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AdminCommonController extends Zend_Controller_Action 
{
	/**
	 * Function init
	 *
	 * This is function is used to check whether admin is login or not.
     * It is also used for assigning constants like path,title,etc 
	 *
	 * Date created: 2011-08-19
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function init() 
	{
        global $mysession; 
				   
		if(!in_array(strtolower($this->_request->getControllerName()),array("login","logout")))
		{
			
	
			if(!Zend_Auth::getInstance()->hasIdentity())  
         	{
				header("location: " . SITE_URL . "admin/logout");
				exit();
			}
			
			
			
		}
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		//save database adapter in zend registry
		Zend_Registry::set('Db_Adapter', $db);
		
		//Get Language array
		$lang = array();
		
		$lang_def = new Models_LanguageDefinitions();
		
		$lang = $lang_def->getGroupLanguage('ADMIN', 1);
		
		//Set Default language for site
		$tr = new Zend_Translate(
			array(
			'adapter' => 'array',
			  'content' => $lang,
			  'locale'  => $mysession->language 
			)
		);
		   
		//Save translation adapter in zend registry
		Zend_Registry::set('Zend_Translate', $tr);
		
		//set login user name
		$this->view->username = Zend_Auth::getInstance()->getIdentity();
		
		//set default pagesize for admin section
		if(!isset($mysession->pagesize)){
			$mysession->pagesize = 10;
		}
	} 

	/**
	 * Function indexAction
	 *
	 * This is empty function currently
	 *
	 * Date created: 2011-08-19
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function indexAction()
	{
	}
 }
?>
