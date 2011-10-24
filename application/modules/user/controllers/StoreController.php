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
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function indexAction()
    {
		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$store = new Models_UserMaster();
		$home = new Models_UserMaster();
		$lang = new Models_Language(); 
		$User_id = $mysession->User_Id;		
		
		// Some Array variables
		$this->view->constant_array = $store->constant_array; 
		$this->view->currency = $store->getAllRecords("currency_master");
		$this->view->country  = $store->getAllRecords("country_master","country_name");		
		$this->view->country_states   = $store->getAllRecords("state_master");		
		$this->view->language = $lang->getAllLanguages();
		
		if($this->_request->isPost()) {
		
			$filter = new Zend_Filter_StripTags();	
			
			$data["user_id"] = $mysession->User_Id;
			$data["store_name"] = $filter->filter(trim($this->_request->getPost('story_name'))); 	
			$data["store_description"] = $filter->filter(trim($this->_request->getPost('story_desc'))); 	
			$data["paypal_email"] = $filter->filter(trim($this->_request->getPost('story_email'))); 
			$data["paypal_password"] = $filter->filter(trim($this->_request->getPost('paypal_password'))); 
			$data["paypal_signature"] = $filter->filter(trim($this->_request->getPost('paypal_signature'))); 	
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
			if($data['paypal_password'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Store_Paypal_Password');			
			}
			if($data['paypal_signature'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Store_Paypal_Signature');			
			}
			/*$validator = new Zend_Validate_EmailAddress();
			if ($validator->isValid($data['paypal_email'])) { } else {
				$addErrorMessage[] = $translate->_('Err_Store_Paypal_Email_Invalid');
			}*/
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
			
			
			$langArray = array();
					
			$langArray[DEFAULT_LANGUAGE]["store_description"] = $data['store_description'];
			$langArray[DEFAULT_LANGUAGE]["return_policy"] = $data['return_policy'];
			$langArray[DEFAULT_LANGUAGE]["store_terms_policy"] = $data['store_terms_policy'];
		
			foreach( $this->view->language as $key => $val ) 
			{ 
				if($val['language_id'] != DEFAULT_LANGUAGE ) { 
					
					$desc_lang_index = 'store_description_'.$val['language_id'];
					$rtrn_lang_index = 'return_policy_'.$val['language_id'];
					$term_lang_index = 'store_terms_policy_'.$val['language_id'];
					
					$langArray[$val['language_id']]["store_description"]=$filter->filter(trim($this->_request->getPost($desc_lang_index)));
					$langArray[$val['language_id']]["return_policy"]=$filter->filter(trim($this->_request->getPost($rtrn_lang_index)));
					$langArray[$val['language_id']]["store_terms_policy"]=$filter->filter(trim($this->_request->getPost($term_lang_index)));
				}		
			}
			
			$where = "user_id != ".$User_id;
			if(count($addErrorMessage) == 0 || $addErrorMessage == "") {
			
				if($home->ValidateTableField("store_name",$data['store_name'],"user_master",$where)) {
			
					if($store->UpdateStore($data, $langArray)) {
						
						$addSuccessMessage = $translate->_('Success_Store_Update');
					} else { 
						
						$addErrorMessage[] = $translate->_('Err_Store_Update');
					} 
					
				} else {
					
					$addErrorMessage[] = $translate->_('Err_Store_Exists');	
				} 
				
			}
			
			$this->view->records = $data;
			$this->view->langdata = $langArray;
			
			$this->view->User_EMessage = $addErrorMessage;
			$this->view->User_SMessage = $addSuccessMessage;
			
		} else {
		
			$addErrorMessage = array();
			if($store->Check_UserStore($User_id)) {
		
				$records = $store->getUserStore($User_id);
				
				$langArray = array();
				if( $records["language"] != NULL ) {
					foreach($records["language"] as $key => $val )
					{
						$langArray[$val["language_id"]]["store_description"] = $val["store_description"];
						$langArray[$val["language_id"]]["store_terms_policy"] = $val["store_terms_policy"];
						$langArray[$val["language_id"]]["return_policy"] = $val["return_policy"];
					}
				}
				
				$this->view->records = $records["store"];
				$this->view->langdata = $langArray;	
				
			} else {
		
				$addErrorMessage = $translate->_('Err_Store_Found');
			}	
			
			$this->view->User_EMessage = $addErrorMessage;
		
		}
		
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
	public function fillstateAction()
    {
		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$store = new Models_UserMaster();
		
		$filter = new Zend_Filter_StripTags();	
			
		$country_id = $filter->filter(trim($this->_request->getPost('country_id'))); 	
		
		$states = $store->getAllStates($country_id);
		
		$stateStr = ''; 
		$stateStr .= '<option value="" selected="selected">'.$translate->_('Shipping_Select_State').'</option>';  
		if( count($states) > 0 ) {
		
			foreach( $states as $key => $val ) 
			{
				$stateStr .= '<option value="'.$val["state_id"].'" >'.$val["state_name"].'</option>'; 
			}
		}
		
		echo $stateStr; die;
		
	}
}
?>