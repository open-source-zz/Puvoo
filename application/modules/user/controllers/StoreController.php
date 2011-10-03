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
 * User_StoreController
 *
 * User_StoreController extends UserCommonController. It is used to control Store Setting page.
 *
 * @category	Puvoo
 * @package 	User_Controllers
 * @author	    Yogesh 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
class User_StoreController extends UserCommonController
{
 
 	/**
	 * Function init
	 * 
	 * This function in used for initialization.
	 *
	 * Date created: 2011-08-30
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function init()
	{
		parent::init();		
		//$this->view->JS_Files = array('/user/store.js');
	}
	
	
	/**
	 * Function indexAction
	 * 
	 * This function is used for setting the user's store, and if store not available, create new store.
	 *
	 * Date created: 2011-08-30
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function indexAction()
    {
		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$store = new Models_UserMaster();
		$home = new Models_UserMaster();
		$User_id = $mysession->User_Id;		
		
		// Some Array variables
		$this->view->constant_array = $store->constant_array; 
		$this->view->currency = $store->getAllRecords("currency_master");
		$this->view->country  = $store->getAllRecords("country_master","country_name");		
		$this->view->country_states   = $store->getAllRecords("state_master");		
		
		if($this->_request->isPost()) {
		
			$filter = new Zend_Filter_StripTags();	
			
			$data["user_id"] = $mysession->User_Id;
			$data["store_name"] = $filter->filter(trim($this->_request->getPost('story_name'))); 	
			$data["store_description"] = $filter->filter(trim($this->_request->getPost('story_desc'))); 	
			$data["paypal_email"] = $filter->filter(trim($this->_request->getPost('story_email'))); 	
			$data["currency_id"] = $filter->filter(trim($this->_request->getPost('story_currency'))); 	
			$data["country_id"] = $filter->filter(trim($this->_request->getPost('store_country'))); 	
			$data["store_address"] = $filter->filter(trim($this->_request->getPost('story_address'))); 	
			$data["store_city"] = $filter->filter(trim($this->_request->getPost('story_city'))); 	
			$data["state_id"] = $filter->filter(trim($this->_request->getPost('store_state'))); 	
			$data["store_zipcode"] = $filter->filter(trim($this->_request->getPost('store_zipcode'))); 	
			$data["allow_free_shipping"] = $filter->filter(trim($this->_request->getPost('free_shipping'))); 	
			$data["allow_no_shipping"] = $filter->filter(trim($this->_request->getPost('no_shipping'))); 	
			$data["return_policy"] = $filter->filter(trim($this->_request->getPost('ship_return_policy'))); 	
			$data["item_returned_for"] = $filter->filter(trim($this->_request->getPost('ship__item_return_for'))); 	
			$data["shipping_handling_time"] = $filter->filter(trim($this->_request->getPost('ship_handling_time'))); 	
			$data["store_terms_policy"] = $filter->filter(trim($this->_request->getPost('store_terms'))); 	
			
			$addErrorMessage = array();	
			$addSuccessMessage = "";
			if($data['store_name'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Store_Name');			
			}
			if($data['store_description'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Store_Description');			
			}
			if($data['paypal_email'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Store_Paypal_Email');			
			}
			$validator = new Zend_Validate_EmailAddress();
			if ($validator->isValid($data['paypal_email'])) { } else {
				$addErrorMessage[] = $translate->_('Err_Store_Paypal_Email_Invalid');
			}
			if($data['currency_id'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Store_Currency');
			}		
			if($data['country_id'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Store_Country');
			}		
			if($data['store_address'] == "") {
				$addErrorMessage[] = $translate->_('Err_Store_Address');			
			}
			if($data['store_city'] == "") {
				$addErrorMessage[] = $translate->_('Err_Store_City');			
			}
			if($data['state_id'] == "") {
				$addErrorMessage[] = $translate->_('Err_Store_State');			
			}
			if($data['store_zipcode'] == "") {
				$addErrorMessage[] = $translate->_('Err_Store_zipcode');			
			}
			
			$where = "user_id != ".$User_id;
			if(count($addErrorMessage) == 0 || $addErrorMessage == "") {
			
				if($home->ValidateTableField("store_name",$data['store_name'],"user_master",$where)) {
			
					if($store->UpdateStore($data)) {
						
						$addSuccessMessage = $translate->_('Success_Store_Update');
					} else { 
						
						$addErrorMessage[] = $translate->_('Err_Store_Update');
					} 
					
				} else {
					
					$addErrorMessage[] = $translate->_('Err_Store_Exists');	
				} 
				
			}
			
			$this->view->records = $data;
			$this->view->User_EMessage = $addErrorMessage;
			$this->view->User_SMessage = $addSuccessMessage;
			
		} else {
		
			$addErrorMessage = array();
			if($store->Check_UserStore($User_id)) {
		
				$this->view->records = $store->getUserStore($User_id);
				
			} else {
		
				$addErrorMessage = $translate->_('Err_Store_Found');
			}	
			
			$this->view->User_EMessage = $addErrorMessage;
		
		}
		
	}
}
?>