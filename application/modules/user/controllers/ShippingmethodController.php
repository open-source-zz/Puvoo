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
 * User_ShippingmethodController
 *
 * User_ShippingmethodController extends UserCommonController. It is used to manage the shipping method.
 *
 * @category	Puvoo
 * @package 	User_Controllers
 * @author	    Yogesh 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
class User_ShippingmethodController extends UserCommonController
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
		$this->view->JS_Files = array('user/shippingmethod.js','user/usercommon.js');
	}
	
	
	/**
	 * Function indexAction
	 * 
	 * This function is used for listing all shipping methods and for searching shipping methods.
	 *
	 * Date created: 2011-08-31
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
		global $mysession,$arr_pagesize;
		$translate = Zend_Registry::get('Zend_Translate');
		$this->view->site_url = SITE_URL."user/shippingmethod";
		$this->view->add_action = SITE_URL."user/shippingmethod/add";
		$this->view->edit_action = SITE_URL."user/shippingmethod/edit";
		$this->view->delete_action = SITE_URL."user/shippingmethod/delete";
		$this->view->delete_all_action = SITE_URL."user/shippingmethod/deleteall";
		
		
		$shipping_method = new Models_UserShippingMethod();
		
		//$this->view->records =  $shipping_method->getAllShippingMethod();
		
		//set current page number
		$page_no = 1;
		
		//set no. of records to display on page
		$pagesize = $mysession->pagesize;
		
		//set search param
		$is_search = "";
		
		//Get Request
		$request = $this->getRequest();
		
		if($request->isPost()){
		
			$page_no = $request->getPost('page_no');
			$pagesize = $request->getPost('pagesize');
			$mysession->pagesize = $pagesize;
			$is_search = $request->getPost('is_search');
		}
		
		if($is_search == "1") {
		
			$filter = new Zend_Filter_StripTags();	
			$data['shipping_method_name']=$filter->filter(trim($this->_request->getPost('shipping_method_name'))); 
			
			//$data['shipping_rate']=$filter->filter(trim($this->_request->getPost('shipping_method_rate'))); 	
			
			
			if( $data["shipping_method_name"]  != '' ) {
			
				$result = $shipping_method->SearchShippingMethod($data);
			
			} else {
			
				$mysession->User_EMessage = $translate->_('No_Search_Criteria');
				
				$result = $shipping_method->getAllShippingMethod();
			}
			
			
			
			
		} elseif($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $shipping_method->getAllShippingMethod();
						
		} else 	{
			//Get all Categories
			$result = $shipping_method->getAllShippingMethod();
			
		}	
		
		// Success Message
		$this->view->User_SMessage = $mysession->User_SMessage;
		$this->view->User_EMessage = $mysession->User_EMessage;
		
		$mysession->User_SMessage = "";
		$mysession->User_EMessage = "";
		
		//Set Pagination
		$paginator = Zend_Paginator::factory($result);
    	$paginator->setItemCountPerPage($pagesize);
    	$paginator->setCurrentPageNumber($page_no);
			
		//Set View variables
		$this->view->pagesize = $pagesize;
		$this->view->page_no = $page_no;
		$this->view->arr_pagesize = $arr_pagesize;
		$this->view->paginator = $paginator;
		$this->view->records = $paginator->getCurrentItems();	
		
	}
	
	 /**
     * Function addAction
	 *
	 * This function is used to add Shipping method
	 *
     * Date Created: 2011-09-01
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	
	
	public function addAction()
	{
		global $mysession;
		
		$this->view->site_url = SITE_URL."user/shippingmethod";
		$this->view->add_action = SITE_URL."user/shippingmethod/add";		
		
		$translate = Zend_Registry::get('Zend_Translate');
		$filter = new Zend_Filter_StripTags();	
		$request = $this->getRequest();
		$shipping_method = new Models_UserShippingMethod();
		$home = new Models_UserMaster();
		$lang = new Models_Language(); 
		
		// Initial values
		$this->view->country = $shipping_method->selectAllRecord("country_master");
		$this->view->language = $lang->getAllLanguages();
		
		// On Form Submit
		if($request->isPost())	{
			
			// Fetch all record here
			$data['user_id']=$mysession->User_Id;
			$data['shipping_method_name']=$filter->filter(trim($this->_request->getPost('shipping_method_name'))); 	
			$data2['zone']=$filter->filter(trim($this->_request->getPost('shipping_zone'))); 	
			$data2['price']=$filter->filter(trim($this->_request->getPost('shipping_price'))); 
			
			$row = array_merge($data, $data2);
			$this->view->data = $row;
			
			$addErrorMessage = array();
			// Validate records and insert
			if($data['shipping_method_name'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Shipping_Method_Name');
			}
			if($data2['zone'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Shipping_Zone');
			}
			if($data2['price'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Shipping_Method_Rate');
			} 
			
			$langArray = array();
					
			$langArray[DEFAULT_LANGUAGE]["shipping_method_name"] = $data['shipping_method_name'];
		
			foreach( $this->view->language as $key => $val ) 
			{ 
				if($val['language_id'] != DEFAULT_LANGUAGE ) { 
					
					$name_lagn_index = 'shipping_method_name_'.$val['language_id'];
					$langArray[$val['language_id']]["shipping_method_name"]=$filter->filter(trim($this->_request->getPost($name_lagn_index)));
				}		
			}
			
			
			if( count($addErrorMessage) == 0 || $addErrorMessage == "" ) {
				
				$where = "user_id = ".$mysession->User_Id;
				
				if($home->ValidateTableField("shipping_method_name",$data['shipping_method_name'],"user_shipping_method",$where)) {
					// Insert Records
					$shipping_method_id = $shipping_method->insertShippingMethod($data, "user_shipping_method", $langArray);
					
					if($shipping_method_id > 0) {
					
						$data2["shipping_method_id"] = $shipping_method_id;
						$shipping_method->insertShippingMethod($data2,"user_shipping_method_detail");
						$mysession->User_SMessage = $translate->_('Success_Add_Shipping_Method');
						$this->_redirect('/user/shippingmethod'); 	
					
					} else {
					
						$addErrorMessage[] = $translate->_('Err_Add_Shipping_Method');	
					}
					
				} else {
					
					$addErrorMessage[] = $translate->_('Err_Shipping_Method_Exists');
					
				}
			
			}
			
			$this->view->addErrorMessage = $addErrorMessage;			
		} 
	
	}
	
	/**
     * Function editAction
	 *
	 * This function is used to update Shipping method
	 *
     * Date Created: 2011-09-01
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	
	public function editAction()
	{
		
		global $mysession;
		$this->view->site_url = SITE_URL."user/shippingmethod";
		$this->view->edit_action = SITE_URL."user/shippingmethod/edit";
		$translate = Zend_Registry::get('Zend_Translate');
		$filter = new Zend_Filter_StripTags();
		
		$shipping_method = new Models_UserShippingMethod();
		$home = new Models_UserMaster();
		$lang = new Models_Language(); 
		$request = $this->getRequest();
		
		$shipping_method_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 
		
		// Initial values
		$this->view->country = $shipping_method->selectAllRecord("country_master");
		$this->view->language = $lang->getAllLanguages();			
		
		// Fetch records 
		if($shipping_method_id > 0 && $shipping_method_id != "") {
			
			$records = $shipping_method->GetShippingMethodById($shipping_method_id);	
			$this->view->shipping_method_id =  $shipping_method_id;	
			
			$langArray = array();
			if( $records["language"] != NULL ) {
				foreach($records["language"] as $key => $val )
				{
					$langArray[$val["language_id"]]["shipping_method_name"] = $val["shipping_method_name"];
				}
			}
			
			$this->view->records = $records["shipping"];
			$this->view->langdata = $langArray;	
			
		} else {
			
			// On Form Submit
			if($request->isPost()){
				
				// Primary key
				$primary_id = $filter->filter(trim($this->_request->getPost('shipping_method_id')));
				// Fetch all record here
				$data['user_id']=$mysession->User_Id;
				$data['shipping_method_id'] = $primary_id; 
				$data['shipping_method_name']=$filter->filter(trim($this->_request->getPost('shipping_method_name'))); 	
				$data2['zone']=$filter->filter(trim($this->_request->getPost('shipping_zone'))); 	
				$data2['price']=$filter->filter(trim($this->_request->getPost('shipping_price'))); 
				
				$row = array_merge($data, $data2);
				$editErrorMessage = array();
				// Validate records and insert
				if($data['shipping_method_name'] == "" ) {
					$editErrorMessage[] = $translate->_('Err_Shipping_Method_Name');
				} 
				if($data2['zone'] == "" ) {
					$editErrorMessage[] = $translate->_('Err_Shipping_Zone');
				}
				if($data2['price'] == "" ) {
					$editErrorMessage[] = $translate->_('Err_Shipping_Method_Rate');
				} 
				
				$langArray = array();
					
				$langArray[DEFAULT_LANGUAGE]["shipping_method_name"] = $data['shipping_method_name'];
			
				foreach( $this->view->language as $key => $val ) 
				{ 
					if($val['language_id'] != DEFAULT_LANGUAGE ) { 
						
						$name_lagn_index = 'shipping_method_name_'.$val['language_id'];
						$langArray[$val['language_id']]["shipping_method_name"]=$filter->filter(trim($this->_request->getPost($name_lagn_index)));
						
					}		
				}
				
				
				if( count($editErrorMessage) == 0 || $editErrorMessage == "") {
					// Update Records
					$cond = "shipping_method_id != '".$primary_id."' AND user_id = ".$mysession->User_Id;
					if($home->ValidateTableField("shipping_method_name",$data['shipping_method_name'],"user_shipping_method",$cond)) 					{
						
						$where = "shipping_method_id = ".$primary_id;		
						$update1 = $shipping_method->updateShippingMethod($data,$where, "user_shipping_method", $langArray);
						$update2 = $shipping_method->updateShippingMethod($data2,$where,"user_shipping_method_detail");			

						if($update1 = TRUE || $update2 = TRUE) {

							$shipping_method->updateShippingMethod($data2,$where,"user_shipping_method_detail");
							$mysession->User_SMessage = $translate->_('Success_Edit_Shipping_Method');
							$this->_redirect('/user/shippingmethod'); 	

						} else {

							$editErrorMessage[] = $translate->_('Err_Edit_Shipping_Method');	
						}					
						
					} else {
					
						$editErrorMessage[] = $translate->_('Err_Shipping_Method_Exists');
					}
				
				}
				
				$this->view->editErrorMessage = $editErrorMessage;	
				$this->view->records = $row;	
				$this->view->langdata = $langArray;	
				$this->view->shipping_method_id =  $primary_id;		
			}  
		}
		
	}
	
	/**
     * Function deleteAction
	 *
	 * This function is used to delete Shipping method
	 *
     * Date Created: 2011-09-01
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	
	public function deleteAction()
	{
		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$shipping_method = new Models_UserShippingMethod();
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$shipping_method_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($shipping_method_id > 0 && $shipping_method_id != "") {
			if($shipping_method->deleteShippingMethod($shipping_method_id)) {			
				$shipping_method->deleteShippingMethod($shipping_method_id,"shipping_method_id","user_shipping_method_detail");
				$mysession->User_SMessage = $translate->_('Shipping_Method_Success_Delete');
			} else {
				$mysession->User_EMessage = $translate->_('Err_Delete_Shipping_Method');
			}		
		} 
		$this->_redirect('/user/shippingmethod'); 	
	}
	
	/**
     * Function deleteallAction
	 *
	 * This function is used to delete multiple Shipping method
	 *
     * Date Created: 2011-09-01
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	
	public function deleteallAction()
	{
		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$shipping_method = new Models_UserShippingMethod();
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		
   		if(isset($_POST["id"])) {
		
			$shipping_method_id = $this->_request->getPost('id'); 
			$ids = implode($shipping_method_id,",");
			
			if($shipping_method->deletemultipleShippingMethod($ids)) {
				$shipping_method->deletemultipleShippingMethod($ids,"shipping_method_id","user_shipping_method_detail");
				$mysession->User_SMessage = $translate->_('Shipping_Method_Success_M_Delete');	
			} else {
				$mysession->User_EMessage = $translate->_('Err_Delete_Shipping_Method');			
			}	
			
		}	else {
		
			$mysession->User_EMessage = $translate->_('Shipping_Method_Err_M_Delete');				
		}
		$this->_redirect('/user/shippingmethod'); 	
	
	}
	
	
	/**
     * Function fillstateAction
	 *
	 * This function is used to fill the combo of the state on selecting country
	 *
     * Date Created: 2011-09-10
     *
     * @access public
	 * @param ()  - No parameter
	 * @return () - No returns
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	
	public function fillstateAction()
	{
		$store = new Models_UserMaster();
		$translate = Zend_Registry::get('Zend_Translate');
		$state = $store->getAllStates($_POST["country_id"]);
		
		$str = '';
		$str .= '<option selected="selected" value="">';
		$str .= $translate->_('Shipping_Select_State');
		$str .='</option>';	
		if($state != NULL && $state != '')
		{		
			foreach($state as $key => $val)
			{
				$str .= '<option value="'.$val['state_name'].'">';
				$str .= $val["state_name"];
				$str .='</option>';	
			}
		} 
		echo $str; die;		
	}
	
}
?>