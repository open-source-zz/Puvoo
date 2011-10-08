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
 * User Apitoken Controller.
 *
 * User_ApitokenController. It is used to get and generate api token for user.
 *
 * Date Created: 2011-10-08
 *
 * @category	Puvoo
 * @package 	User_Controllers
 * @author	    Amar
 *  
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/  
class User_ApitokenController extends UserCommonController
{
	
	
	/**
	 * Function init
	 * 
	 * This function in used for initialization.
	 *
	 * Date created: 2011-10-08
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function init()
	{
		parent::init();				
	}
	
	
	/**
     * Function indexAction
	 *
	 * This function is used to display api token and recreate new api token.
	 *
     * Date Created: 2011-10-08
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/  
   function indexAction() 
   {
		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		//Create Object
		$User = new Models_UserMaster();
		
		//Current logged in user id
		$user_id = $mysession->User_Id;
		
		//Get Request
		$request = $this->getRequest();
		
		if($request->isPost())
		{
			//recreate token
			if($User->createToken($user_id))
			{
				$this->view->updateSuccess = $translate->_('Succ_Token');
			}
			else
			{
				$this->view->updateError = $translate->_('Err_Token');
			}
		}
		
		$this->view->token = $User->getToken($user_id);
				
   }
   
   
}
?>