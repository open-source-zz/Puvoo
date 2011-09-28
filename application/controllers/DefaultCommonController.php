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
 * DefaultCommonController class.To assign variables and constants.
 *
 * This class file extends Zend_Controller_Action.
 * It is used for assigning path constants,title,etc.
 *
 * Date created: 2011-08-26
 *
 * @author Amar
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class DefaultCommonController extends Zend_Controller_Action 
{
	/**
	 * Function init
	 *
	 * It is used for assigning constants like path,title,etc 
	 *
	 * Date created: 2011-08-26
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
				   
		$db = Zend_Db_Table::getDefaultAdapter();
		
		//save database adapter in zend registry
		Zend_Registry::set('Db_Adapter', $db);
		
		
		//Get Language array
		$lang = array();
		
		$lang_def = new Models_LanguageDefinitions();
		
		$lang = $lang_def->getGroupLanguage('DEFAULT', 1);
		
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
		
		//set default pagesize for user section
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