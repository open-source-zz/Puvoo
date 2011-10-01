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
 * User_IndexController
 *
 * User_IndexController extends AdmCommonController.It is used to control Index page.
 *
 * @category	Puvoo
 * @package 	User_Controllers
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
class User_IndexController extends UserCommonController
{
 
 	/**
	 * Function init
	 * 
	 * This function in used for initialization.
	 *
	 * Date created: 2011-08-26
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
		$this->view->JS_Files = array('user/home.js');	
		
	}
	
	
	/**
	 * Function indexAction
	 * 
	 * This function is used to control indexAction.
	 *
	 * Date created: 2011-08-26
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function indexAction()
    {
		global $mysession;
		
		$orders = new Models_UserOrders();
		$master = new Models_UserMaster();
		
		$this->view->records = $orders->getDashboardOrders();
		$this->view->order_status = $master->getConstantArray();
		$this->view->active_class = 'All';
		if($this->_request->isPost()) {
			
			$filter = new Zend_Filter_StripTags();
			$order_status = $filter->filter(trim($this->_request->getPost('order_status')));
			
			$this->view->active_class = $order_status;
			
			if($order_status == 'All' ) {
				$where = " AND 1=1 ";
			} else {
				$where = " AND om.order_status = '".$order_status."' ";
			}
			$this->view->records = $orders->getDashboardOrders($where);
		}
		
	}
	
	/**
	 * Function changepasswordAction
	 * 
	 * This function is used to change the passowrd.
	 *
	 * Date created: 2011-08-26
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function changepasswordAction()
    {
		global $mysession;
		$updateError = "";
		$updateSuccess = "";
		$translate = Zend_Registry::get('Zend_Translate');
		if($this->_request->isPost()) { 
			// Validate Passwords
			$old_password 		= trim($this->_request->getPost('old_password'));
			$new_password 		= trim($this->_request->getPost('new_password'));
			$confirm_password 	= trim($this->_request->getPost('confirm_password'));
			
			$this->view->old_password = $old_password;
			$this->view->new_password = $new_password;
			$this->view->confirm_password = $confirm_password;
			
			if($old_password == "") {
				$updateError = $translate->_('Err_Old_Password');
			} else {
				if($new_password == "") {
					$updateError = $translate->_('Err_New_Password');
				} else {
				
					if($confirm_password == "" && $confirm_password != $new_password ) {
						$updateError = $translate->_('Err_Confirm_Password');
					 } else {
					 	// Update Passwords
					 	$auth = new Models_UserLogin();			
						if($auth->validatePassword($old_password,$new_password)) {
							$updateSuccess = $translate->_('Suceess_Update_Password');
							$updateError = "";
						} else {
							$updateError = $translate->_('Err_Update_Password');
						}
					 }
				}
			} 
			// Display error or success message
			$this->view->updateSuccess = $updateSuccess;
			$this->view->updateError = $updateError;	
		}		
	}	
}
?>