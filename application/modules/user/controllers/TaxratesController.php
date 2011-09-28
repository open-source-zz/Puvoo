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
 * User_TaxratesController
 *
 * User_TaxratesController extends UserCommonController. It is used to manage the tax rates.
 *
 * @category	Puvoo
 * @package 	User_Controllers
 * @author	    Yogesh 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
class User_TaxratesController extends UserCommonController
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
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function init()
	{
		parent::init();		
		$this->view->JS_Files = array('user/taxes.js','user/usercommon.js');
		define("USER_TABLE","tax_rate");
		define("USER_PRIMARY_KEY","tax_rate_id");
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
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function indexAction()
    {
		global $mysession,$arr_pagesize;
		$translate = Zend_Registry::get('Zend_Translate');
		$this->view->site_url = SITE_URL."user/taxrates";
		$this->view->add_action = SITE_URL."user/taxrates/add";
		$this->view->edit_action = SITE_URL."user/taxrates/edit";
		$this->view->delete_action = SITE_URL."user/taxrates/delete";
		$this->view->delete_all_action = SITE_URL."user/taxrates/deleteall";
		
		$taxes = new Models_UserTaxes();
		
		//$this->view->taxclass = $taxes->getAllRecors("tax_class");
		//$this->view->taxzone = $taxes->getAllRecors("tax_zone");
		
		
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
			$data["tax_name"]=$filter->filter(trim($this->_request->getPost('tax_name'))); 			
			$result = $taxes->SearchTaxes($data);
			
		} elseif($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $taxes->getAllRateRecors();
						
		} else 	{
			//Get all Categories
			$result = $taxes->getAllRateRecors();
			
		}	
		
		// Success Message
		$this->view->User_Message = $mysession->User_Message;
		$mysession->User_Message = "";
		
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
		
		$this->view->site_url = SITE_URL."user/taxrates";
		$this->view->add_action = SITE_URL."user/taxrates/add";		
		
		$translate = Zend_Registry::get('Zend_Translate');
		$filter = new Zend_Filter_StripTags();	
		$request = $this->getRequest();
		$taxes = new Models_UserTaxes();
		$home = new Models_UserMaster();
		
		// Initial values
		$this->view->country = $taxes->selectAllRecord("country_master");
		
		// On Form Submit
		if($request->isPost())	{
			
			// Fetch all record here
			$data['user_id']=$mysession->User_Id;
			$data['tax_name']=$filter->filter(trim($this->_request->getPost('tax_name')));
			$data2['zone']=$filter->filter(trim($this->_request->getPost('tax_zone'))); 	
			$data2['rate']=$filter->filter(trim($this->_request->getPost('tax_price'))); 
			
			$error_message = "";
			// Validate records and insert
			if($data['tax_name'] == "" ) {
				$error_message = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Tax_Name')."</h5>";
			} else if($data2['zone'] == "" ) {
				$error_message = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Tax_Zone')."</h5>";
			}
			else if($data2['rate'] == "" ) {
				$error_message = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Tax_Price')."</h5>";
			} else {
				
				$where = "user_id = ".$mysession->User_Id;
				if($home->ValidateTableField("tax_name",$data['tax_name'],"tax_rate",$where)) {
									
					// Insert Records
					$tax_id = $taxes->insertRecord($data);
					
					if($tax_id > 0) {
					
						$data2["tax_rate_id"] = $tax_id;
						$taxes->insertRecord($data2,"tax_rate_detail");
						$mysession->User_Message = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Success_Add_Taxes_Rates')."</h5>";
						$this->_redirect('/user/taxrates'); 	
					} else {
						$error_message = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Add_Taxes_Rates')."</h5>";	
					}
				} else {
					$error_message = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Taxe_Exist')."</h5>";
				}
			}
			
			$this->view->error_message = $error_message;			
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
		$this->view->site_url = SITE_URL."user/taxrates";
		$this->view->edit_action = SITE_URL."user/taxrates/edit";
		$translate = Zend_Registry::get('Zend_Translate');
		$filter = new Zend_Filter_StripTags();
		
		$taxes = new Models_UserTaxes();
		$home = new Models_UserMaster();
		$request = $this->getRequest();
		
		$tax_rate_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 
		
		// Initial values
		$this->view->country = $taxes->selectAllRecord("country_master");
		
		// Fetch records 
		if($tax_rate_id > 0 && $tax_rate_id != "") {
			
			$this->view->records = $taxes->getRecordsById($tax_rate_id);
			$this->view->tax_rate_id =  $tax_rate_id;	
			
		} else {
			
			// On Form Submit
			if($request->isPost()){
				
				// Primary key
				$primary_id = $filter->filter(trim($this->_request->getPost('tax_rate_id')));
				
				// Fetch all record here				
				$data['user_id']=$mysession->User_Id;
				$data['tax_name']=$filter->filter(trim($this->_request->getPost('tax_name')));
				$data2['zone']=$filter->filter(trim($this->_request->getPost('tax_zone'))); 	
				$data2['rate']=$filter->filter(trim($this->_request->getPost('tax_price'))); 
			
				$error_message = "";
				// Validate records 
				if($data['tax_name'] == "" ) {
					$error_message = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Tax_Name')."</h5>";
				} else if($data2['zone'] == "" ) {
					$error_message .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Tax_Zone')."</h5>";
				}
				else if($data2['rate'] == "" ) {
					$error_message .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Tax_Price')."</h5>";
				} else {		
				
					// Update Records
					$cond = "tax_rate_id != '".$primary_id."' AND user_id = ".$mysession->User_Id;
					if($home->ValidateTableField("tax_name",$data['tax_name'],"tax_rate",$cond)) {
					
						if($error_message == "") {
						$where = "tax_rate_id = ".$primary_id;
							$update1 = $taxes->updateRecord($data,$where);
							$update2 = $taxes->updateRecord($data2,$where,"tax_rate_detail");
							if($update1 == TRUE || $update2 == TRUE) {
								
								$mysession->User_Message = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Success_Edit_Taxes_Rates')."</h5>";
								$this->_redirect('/user/taxrates'); 	
							} else {
								$error_message = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Tax_Rate_Update')."</h5>";	
							}	
						} 
						
					} else {
						$error_message = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Taxe_Exist')."</h5>";
					}
				}
				
				$this->view->error_message = $error_message;	
				if($primary_id != "") {
					$this->view->records = $taxes->getRecordsById($primary_id);	
					$this->view->tax_rate_id =  $primary_id;						
				}
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
				$taxes->deleteRecord($tax_rates_id,"tax_rate_id","tax_rate_detail");
				$mysession->User_Message = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Taxes_Rates_Success_Delete')."</h5>";
			} else {
				$mysession->User_Message = "<h5 style='color:#FF0000;margin-bottom:0px;'>There is some problem in deleting tax rates</h5>";	
			}		
		} 
		$this->_redirect('/user/taxrates'); 	
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
				$mysession->User_Message = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Taxes_Rates_Success_M_Delete')."</h5>";	
			} else {
				$mysession->User_Message = "<h5 style='color:#FF0000;margin-bottom:0px;'>There is some problem in deleting tax rates</h5>";				
			}	
			
		}	else {
		
			$mysession->User_Message = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Taxes_Rates_Err_M_Delete')."</h5>";				
		}
		$this->_redirect('/user/taxrates'); 	
	
	}
	
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