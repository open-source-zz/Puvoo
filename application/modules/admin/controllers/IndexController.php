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
 * Admin_IndexController
 *
 * Admin_IndexController extends AdmCommonController.It is used to control Index page.
 *
 * Date created: 2011-08-19
 *
 * @category	Puvoo
 * @package 	Admin_Controllers
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
class Admin_IndexController extends AdminCommonController
{
 
 	/**
	 * Function init
	 * 
	 * This function in used for initialization.It would call parent's init() function to check whether admin is login or not.Also initialize necessary session variables.
	 *
	 * Date created: 2011-08-19
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
		$this->view->JS_Files = array('admin/AdminCommon.js');	
		Zend_Loader::loadClass('Models_AdminMaster');
		
	}
	
	
	/**
	 * Function indexAction
	 * 
	 * This function is used to control indexAction.
	 *
	 * Date created: 2011-08-19
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
		
		$orders = new Models_Orders();
		$master = new Models_AdminMaster();
		$status = new Models_Status();
		
		$this->view->records = $orders->getDashboardOrders();
		$this->view->order_status = $status->GetAllStatus();
		
		$this->view->active_class = 'All';
		if($this->_request->isPost()) {
			
			$filter = new Zend_Filter_StripTags();
			$order_status = $filter->filter(trim($this->_request->getPost('order_status')));
			
			$this->view->active_class = $order_status;
			
			if($order_status == 'All' ) {
				$where = " WHERE 1=1 ";
			} else {
				$where = " WHERE order_status = '".$order_status."' ";
			}
			
			$this->view->records = $orders->getDashboardOrders($where);
		}
		
	}
	
	/**
	 * Function changepasswordAction
	 * 
	 * This function is used to chagne the passowrd.
	 *
	 * Date created: 2011-08-25
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function changepasswordAction()
    {
		global $mysession;
		$updateError = "";
		$updateSuccess = "";
		$this->view->JS_Files = array('admin/home.js');	
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
				
					if($confirm_password == "" || $confirm_password != $new_password ) {
						$updateError = $translate->_('Err_Conf_Invalid_Password');
					 } else {
					 	// Update Passwords
					 	$auth = new Models_AdminMaster();			
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