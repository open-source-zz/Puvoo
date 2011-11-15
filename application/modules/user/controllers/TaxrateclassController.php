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
 * User_TaxrateclassController
 *
 * User_TaxrateclassController extends UserCommonController. It is used to manage the tax rates.
 *
 * @category	Puvoo
 * @package 	User_Controllers
 * @author	    Hiren 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
class User_TaxrateclassController extends UserCommonController
{
 
 	/**
	 * Function init
	 * 
	 * This function in used for initialization.
	 *
	 * Date created: 2011-09-01
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Hiren
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function init()
	{
		parent::init();		
		$this->view->JS_Files = array('user/taxes.js','user/usercommon.js','user/jquery.multiselect.js');
		define("USER_TABLE","tax_rate_class");
		define("USER_PRIMARY_KEY","tax_rate_class_id");
	}
	
	
	/**
	 * Function indexAction
	 * 
	 * This function is used for listing all Tax Rates and for searching Tax Rates.
	 *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Hiren
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function indexAction()
    {
		global $mysession,$arr_pagesize;
		$translate = Zend_Registry::get('Zend_Translate');
		$this->view->site_url = SITE_URL."user/taxrateclass";
		$this->view->add_action = SITE_URL."user/taxrateclass/add";
		$this->view->edit_action = SITE_URL."user/taxrateclass/edit";
		$this->view->delete_action = SITE_URL."user/taxrateclass/delete";
		$this->view->delete_all_action = SITE_URL."user/taxrateclass/deleteall";
		
		$taxes = new Models_UserTaxClass();
		
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
			$data["tax_rate_name"]=$filter->filter(trim($this->_request->getPost('tax_name'))); 	
			
			
			if( $data["tax_rate_name"]  != '' ) {
			
				$result = $taxes->SearchTaxes($data);
			
			} else {
			
				$mysession->User_EMessage = $translate->_('No_Search_Criteria');
				
				$result = $taxes->getAllRateRecors();
			}
					
			
			
		} elseif($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $taxes->getAllRateRecors();
				
		} else 	{
			//Get all Categories
			$result = $taxes->getAllRateRecors();
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
	 * This function is used to add Tax Rates
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
		
		$this->view->site_url = SITE_URL."user/taxrateclass";
		$this->view->add_action = SITE_URL."user/taxrateclass/add";		
		
		$translate = Zend_Registry::get('Zend_Translate');
		$filter = new Zend_Filter_StripTags();	
		$request = $this->getRequest();
		$taxes = new Models_UserTaxClass();
		$home = new Models_UserMaster();
		
		// Initial values
		$this->view->country = $taxes->selectAllRecord("country_master");
		$this->view->zone = $taxes->selectAllZone();
		// On Form Submit
		if($request->isPost())	{
			/*echo "<pre>";
			print_r($_POST);die;*/
			$float_validator = new Zend_Validate_Float();
			
			// Fetch all record here
			$data['user_id']=$mysession->User_Id;
			$data['tax_rate_name']=$filter->filter(trim($this->_request->getPost('tax_rate_name')));
			$data['tax_zone']=$filter->filter(trim($this->_request->getPost('multiselect_tax_zone_value'))); 	
			$data['tax_rate']=$filter->filter(trim($this->_request->getPost('tax_rate'))); 
			$data['is_default']=$filter->filter(trim($this->_request->getPost('is_default')));
			
			
			$this->view->data = $data;
		
			$addErrorMessage = array();
			// Validate records and insert
			if($data['tax_rate_name'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Tax_Name');
			}
			if($data['tax_zone'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Tax_class_Zone');
			}
			if($data['tax_rate'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Tax_Price');
			} else if(!$float_validator->isValid($data['tax_rate'])) {
				$addErrorMessage[] = $translate->_('Err_Taxrate_Invalid_Value');		
			}
			
			
			if( count($addErrorMessage) == 0 || $addErrorMessage == "" ) {
				
				$where = "user_id = ".$mysession->User_Id;
				if($home->ValidateTableField("tax_rate",$data['tax_rate'],"tax_rate_class",$where)) {
						
					$tax_id = $taxes->insertRecord($data);
					$this->_redirect('/user/taxrateclass'); 
					
					
				} else {
					
					$addErrorMessage[] = $translate->_('Err_Tax_Rate_Exist');
				}
			}
			
			$this->view->addErrorMessage = $addErrorMessage;			
		} 
	
	}
	
	
	/**
     * Function editAction
	 *
	 * This function is used to update Tax Rates
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
		$this->view->site_url = SITE_URL."user/taxrateclass";
		$this->view->edit_action = SITE_URL."user/taxrateclass/edit";
		$translate = Zend_Registry::get('Zend_Translate');
		$filter = new Zend_Filter_StripTags();
		
		$taxes = new Models_UserTaxClass();
		$home = new Models_UserMaster();
		$request = $this->getRequest();
		
		$tax_rate_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 
		
		// Initial values
		$this->view->country = $taxes->selectAllRecord("country_master");
		
		// Fetch records 
		if($tax_rate_id > 0 && $tax_rate_id != "") {
			
			$this->view->records = $taxes->getRecordsById($tax_rate_id);
			$this->view->zone = $taxes->selectAllZoneEdit($this->view->records);
			$this->view->tax_rate_id =  $tax_rate_id;
			$zone = "";	
			//echo "<pre>";
			//print_r($this->view->zone);die;
			
			foreach($this->view->zone as $z){ 
			
				$zone .= "<option value='".$z["tr"]."' ".$z['selected'].">".$z['tn']."</option>";
				
				//$zone .= '<option value="'.$zone["tr"].'"  '.$zone['selected'].'>'.$zone['tn'].'</option>';	
			}
			$this->view->zone = $zone;
		} else {
			
			// On Form Submit
			if($request->isPost()){
				
				$float_validator = new Zend_Validate_Float();
				// Primary key
				$primary_id = $filter->filter(trim($this->_request->getPost('tax_rate_id')));
				
				// Fetch all record here				
				$data['user_id']=$mysession->User_Id;
				$data['tax_rate_name']=$filter->filter(trim($this->_request->getPost('tax_rate_name')));
				$data['tax_zone']=$filter->filter(trim($this->_request->getPost('multiselect_tax_zone_value'))); 	
				$data['tax_rate']=$filter->filter(trim($this->_request->getPost('tax_rate'))); 
				$data['is_default']=$filter->filter(trim($this->_request->getPost('is_default'))); 
			
				$editErrorMessage = array();
				// Validate records 
				if($data['tax_rate_name'] == "" ) {
					$editErrorMessage[] = $translate->_('Err_Tax_Name');
				} 
				if($data['tax_zone'] == "" ) {
					$editErrorMessage[] = $translate->_('Err_Tax_Zone');
				}
				if($data['tax_rate'] == "" ) {
					$editErrorMessage[] = $translate->_('Err_Tax_Price');
				} else if(!$float_validator->isValid($data['tax_rate'])) {
					$editErrorMessage[] = $translate->_('Err_Taxrate_Invalid_Value');		
				}
				
				$this->view->zone = $taxes->selectAllZoneEdit($this->view->records);
				$zone = "";	
				foreach($this->view->zone as $z){ 
				
					$zone .= "<option value='".$z["tr"]."' ".$z['selected'].">".$z['tn']."</option>";
					
					//$zone .= '<option value="'.$zone["tr"].'"  '.$zone['selected'].'>'.$zone['tn'].'</option>';	
				}
				$this->view->zone = $zone;
				$row = $data;
				
				 if( count($editErrorMessage) == 0 || $editErrorMessage == "" ) {		
				
					// Update Records
					$cond = "tax_rate_class_id != '".$primary_id."' AND user_id = ".$mysession->User_Id;
					if($home->ValidateTableField("tax_rate",$data['tax_rate'],"tax_rate_class",$cond)) {
					
						$where = "tax_rate_class_id = ".$primary_id;
						$update1 = $taxes->updateRecord($data,$where);
						
						
							
						if($update1 == TRUE) {
							
							$mysession->User_SMessage = $translate->_('Success_Edit_Taxes_Rates');
							$this->_redirect('/user/taxrateclass'); 	
							
						} else {
						
							$editErrorMessage[] = $translate->_('Err_Tax_Rate_Update');	
						}	
						
						
					} else {
						$editErrorMessage[] = $translate->_('Err_Taxe_Exist');
					}
				}
				
				$this->view->editErrorMessage = $editErrorMessage;	
				$this->view->records = $row;	
				$this->view->tax_rate_id =  $primary_id;	
			}  
		}
		
	}
	
	/**
     * Function deleteAction
	 *
	 * This function is used to delete Tax Rates
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
		
		$taxes = new Models_UserTaxes();
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$tax_rates_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($tax_rates_id > 0 && $tax_rates_id != "") {
			if($taxes->deleteRecord($tax_rates_id)) {
				$mysession->User_SMessage = $translate->_('Taxes_Rates_Success_Delete');
			} else {
				$mysession->User_EMessage = $translate->_('Err_Delete_Taxrate'); 
			}		
		} 
		$this->_redirect('/user/taxrateclass'); 	
	}
	
	/**
     * Function deleteallAction
	 *
	 * This function is used to delete multiple tax rates
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
		
		$taxes = new Models_UserTaxes();
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		
   		if(isset($_POST["id"])) {
		
			$tax_rates_id = $this->_request->getPost('id'); 
			$ids = implode($tax_rates_id,",");
			
			if($taxes->deleteAllRecords($ids)) {
				$taxes->deleteAllRecords($ids,"tax_rate_id","tax_rate_detail");
				$mysession->User_Message = $translate->_('Taxes_Rates_Success_M_Delete');	
			} else {
				$mysession->User_EMessage = $translate->_('Err_Delete_Taxrate'); 	
			}	
			
		}	else {
		
			$mysession->User_EMessage = $translate->_('Taxes_Rates_Err_M_Delete');				
		}
		$this->_redirect('/user/taxrateclass'); 	
	
	}
	
	
	/**
     * Function fillstateAction
	 *
	 * This function is used to fill the state combo on country selection.
	 *
     * Date Created: 2011-09-01
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (String) - Html string
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