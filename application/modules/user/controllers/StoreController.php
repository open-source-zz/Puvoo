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
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function init()
	{
		parent::init();		
		 $this->view->JS_Files = array('/user/store.js');
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
			
			$addStoreError = "";	
			if($data['store_name'] == "" ) {
				$addStoreError .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Store_Name')."</h5>";			
			}
			if($data['store_description'] == "" ) {
				$addStoreError .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Store_Description')."</h5>";			
			}
			if($data['paypal_email'] == "" ) {
				$addStoreError .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Store_Paypal_Email')."</h5>";			
			}
			$validator = new Zend_Validate_EmailAddress();
			if ($validator->isValid($data['paypal_email'])) { } else {
				$addStoreError .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Store_Paypal_Email_Invalid')."</h5>";
			}
			if($data['currency_id'] == "" ) {
				$addStoreError .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Store_Currency')."</h5>";
			}		
			if($data['country_id'] == "" ) {
				$addStoreError .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Store_Country')."</h5>";
			}		
			if($data['store_address'] == "") {
				$addStoreError .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Store_Address')."</h5>";			
			}
			if($data['store_city'] == "") {
				$addStoreError .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Store_City')."</h5>";			
			}
			if($data['state_id'] == "") {
				$addStoreError .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Store_State')."</h5>";			
			}
			if($data['store_zipcode'] == "") {
				$addStoreError .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Store_zipcode')."</h5>";			
			}
			
			$where = "user_id != ".$User_id;
			
			if($home->ValidateTableField("store_name",$data['store_name'],"user_master",$where)) {
			
				if($addStoreError == "") {
					
					if($store->UpdateStore($data)) {
						
						$this->view->StoreMessage = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Success_Store_Update')."</h5>";
					} else { 
						
						$this->view->StoreMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Store_Update')."</h5>";
					} 
					$this->view->records = $store->getUserStore($User_id);
					
				} else {
					
					$this->view->records = $store->getUserStore($User_id);
					$this->view->StoreMessage ="<h5 style='color:#FF0000;margin-bottom:0px;'>".$addStoreError."</h5>";
				} 
				
			} else {
				
				$this->view->records = $store->getUserStore($User_id);
				$this->view->StoreMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Store_Exists')."</h5>";	;
			}
			
		} else {
			if($store->Check_UserStore($User_id)) {
				$this->view->records = $store->getUserStore($User_id);
				
			} else {
				$this->view->StoreMessage ="<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Store_Found')."</h5>";
			}	
		
		}
		
	}
}
?>
