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
 * Admin Currency Controller.
 *
 * Admin_CurrencyController  extends AdminCommonController. 
 * It controls currency on admin section
 *
 * Date Created: 2011-08-20
 *
 * @currency	Puvoo
 * @package 	Admin_Controllers
 * @author      Vaibhavi
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/  
 class Admin_CurrencyController extends AdminCommonController
{

	/**
     * Function init
	 *
	 * This function is used for initialization. 
	 * You can also include necessary javascript files from here.
	 *
     * Date Created: 2011-08-20
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Vaibhavi
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	 
    function init()
    {
        parent::init();
        $this->view->JS_Files = array('admin/currency.js','admin/AdminCommon.js');			
		Zend_Loader::loadClass('Models_Currency');
		Zend_Loader::loadClass('Models_AdminMaster');
        
    }
	
    /**
     * Function indexAction
	 *
	 * This function is used for initialization. Also include necessary javascript files.
	 *
     * Date Created: 2011-08-20
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Vaibhavi
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   function indexAction() 
   {
        global $mysession,$arr_pagesize;
		$this->view->site_url = SITE_URL."admin/currency";
		$this->view->add_action = SITE_URL."admin/currency/add";
		$this->view->edit_action = SITE_URL."admin/currency/edit";
		$this->view->delete_action = SITE_URL."admin/currency/delete";
		$this->view->delete_all_action = SITE_URL."admin/currency/deleteall";
		$this->view->updateall_action = SITE_URL."admin/currency/updateall";
		
		
		//Create Object of Currency model
		$currency = new Models_Currency();
		
		//set current page number
		$page_no = 1;
		
		//set no. of records to display on page
		$pagesize = $mysession->pagesize;
		
		//set search param
		$is_search = "";
		
		//Get Request
		$request = $this->getRequest();
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		if($request->isPost()){
		
			$page_no = $request->getPost('page_no');
			$pagesize = $request->getPost('pagesize');
			$mysession->pagesize = $pagesize;
			$is_search = $request->getPost('is_search');
		}
		
		if($is_search == "1") {
		
			$filter = new Zend_Filter_StripTags();	
			$data['currency_name']=$filter->filter(trim($this->_request->getPost('currency_name'))); 
			$data['currency_code']=$filter->filter(trim($this->_request->getPost('currency_code'))); 
			$data['currency_symbol']=$filter->filter(trim($this->_request->getPost('currency_symbol'))); 	
			$data['currency_value']=$filter->filter(trim($this->_request->getPost('currency_value'))); 
			
			//Get search Currency
			$counter = 0;
			
			foreach( $data as  $key => $val ) 
			{
			
				if( $val != '' ) {
					
					$counter++;
					
				}
			}
			
			if( $counter > 0 ) {
			
				$result = $currency->SearchCurrency($data);
				
			} else  {
			
				$mysession->Admin_EMessage = $translate->_('No_Search_Criteria');
			
				$result = $currency->GetAllCurrency();
			
			} 
			
		} elseif($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $currency->GetAllCurrency();
						
		} else 	{
			//Get all Currency
			$result = $currency->GetAllCurrency();
			
		}		
		// Success Message
		$this->view->Admin_SMessage = $mysession->Admin_SMessage;
		$this->view->Admin_EMessage = $mysession->Admin_EMessage;
		
		$mysession->Admin_SMessage = "";
		$mysession->Admin_EMessage = "";
		
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
	 * This function is used to add currency
	 *
     * Date Created: 2011-08-26
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author vaibhavi
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function addAction()
   {
   		global $mysession;
		$this->view->site_url = SITE_URL."admin/currency";
		$this->view->add_action = SITE_URL."admin/currency/add";		
		
		$currency = new Models_Currency();
		$home = new Models_AdminMaster();
		
		$request = $this->getRequest();
		
		if($request->isPost()){
			
			$translate = Zend_Registry::get('Zend_Translate');
			$float_validator = new Zend_Validate_Float();
			
			$filter = new Zend_Filter_StripTags();	
			$data['currency_name']=$filter->filter(trim($this->_request->getPost('currency_name'))); 	
			$data['currency_code']=$filter->filter(trim($this->_request->getPost('currency_code'))); 	
			$data['currency_symbol']=$filter->filter(trim($this->_request->getPost('currency_symbol')));
			$data['currency_value']=$filter->filter(trim($this->_request->getPost('currency_value')));
			 	
			$addErrorMessage = array();
			if($data['currency_name'] == "") {
				$addErrorMessage[] = $translate->_('Err_Currency_Name');			
			}
			if($data['currency_code'] == "") {
				$addErrorMessage[] = $translate->_('Err_Currency_Code');			
			}
			if($data['currency_symbol'] == "") {
				$addErrorMessage[] = $translate->_('Err_Currency_Symbol');			
			}
			if($data['currency_value'] == "") {
				$addErrorMessage[] = $translate->_('Err_Currency_Value');			
			} else if(!$float_validator->isValid($data['currency_value'])) {
				$addErrorMessage[] = $translate->_('Err_Currency_Invalid_Value');		
			}
			
			$this->view->data = $data;
			
			$where = "1 = 1";
			if( count($addErrorMessage) == 0 || $addErrorMessage == "" ){
			
				if($home->ValidateTableField("currency_name",$data['currency_name'],"currency_master",$where)) {
				
					if($currency->insertCurrency($data)) {
						$mysession->Admin_SMessage = $translate->_('Success_Add_Currency');
						$this->_redirect('/admin/currency'); 	
					} else {
						$addErrorMessage[] = $translate->_('Err_Add_Currency'); 
					}
				} else { 
					$addErrorMessage[] = $translate->_('Err_Currency_Name_Exists');	
				} 
			} 
			
			$this->view->addErrorMessage = $addErrorMessage;
		}
   }
   
   
   /**
     * Function editAction
	 *
	 * This function is used to update currency data.
	 *
     * Date Created: 2011-08-26
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author vaibhavi
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function editAction()
   {
   		global $mysession;
		
		$this->view->site_url = SITE_URL."admin/currency";
		$this->view->edit_action = SITE_URL."admin/currency/edit";
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$currency = new Models_Currency();
   		$home = new Models_AdminMaster();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$currency_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($currency_id > 0 && $currency_id != "") {
			$this->view->records = $currency->GetCurrencyById($currency_id);	
			$this->view->currency_id =  $currency_id;	
			
		} else {
			
			if($request->isPost()){
				
				$float_validator = new Zend_Validate_Float();
				
				$data["currency_id"] = $filter->filter(trim($this->_request->getPost('currency_id'))); 	
				$data['currency_name']=$filter->filter(trim($this->_request->getPost('currency_name'))); 	
				$data['currency_code']=$filter->filter(trim($this->_request->getPost('currency_code'))); 	
				$data['currency_symbol']=$filter->filter(trim($this->_request->getPost('currency_symbol'))); 
				$data['currency_value']=$filter->filter(trim($this->_request->getPost('currency_value')));
				
				$editErrorMessage = array();
				if($data['currency_name'] == "") {
					$editErrorMessage[] = $translate->_('Err_Currency_Name');			
				}
				if($data['currency_code'] == "") {
					$editErrorMessage[] = $translate->_('Err_Currency_Code');			
				}
				if($data['currency_symbol'] == "") {
					$editErrorMessage[]= $translate->_('Err_Currency_Symbol');			
				}
				if($data['currency_value'] == "") {
					$editErrorMessage[] = $translate->_('Err_Currency_Value');			
				}else if(!$float_validator->isValid($data['currency_value'])) {
					$editErrorMessage[] = $translate->_('Err_Currency_Invalid_Value');		
				}
				
				$where = "currency_id != ".$data["currency_id"];
				if( count($editErrorMessage) == 0 || $editErrorMessage == '' ){
				
					if($home->ValidateTableField("currency_name",$data['currency_name'],"currency_master",$where)) {
					
						$where = "currency_id = ".$data["currency_id"];
						if($currency->updateCurrency($data,$where)) {
							$mysession->Admin_SMessage = $translate->_('Success_Edit_Currency');
							$this->_redirect('/admin/currency'); 	
						} else {
							$editErrorMessage[] =  $translate->_('Err_Edit_Currency'); 
						}
					} else {			
						
						$editErrorMessage[] = $translate->_('Err_Currency_Name_Exists');	
					}
				}				
				$this->view->records = $data;	
				$this->view->currency_id =  $data["currency_id"];	
				$this->view->editErrorMessage = $editErrorMessage;
				
			} else {
			
				$this->_redirect("/admin/currency");
			}
		
		}
		
   }
   
   /**
     * Function deleteAction
	 *
	 * This function is used to delete the currency
	 *
     * Date Created: 2011-08-26
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
		$currency = new Models_Currency();
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$currency_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($currency_id > 0 && $currency_id != "") {
			if($currency->deleteCurrency($currency_id)) {
				$mysession->Admin_SMessage = $translate->_('Success_Delete_Currency');
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Currency');
			}		
		} 
		$this->_redirect("/admin/currency");		
   }  
   
   
   /**
     * Function deleteallAction
	 *
	 * This function is used to delete all selected currency.
	 *
     * Date Created: 2011-08-26
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
		$currency = new Models_Currency();
		$request = $this->getRequest();
		$filter = new Zend_Filter_StripTags();	
		
   		if(isset($_POST["id"])) {
		
			$currency_ids = $this->_request->getPost('id'); 
			$ids = implode($currency_ids,",");
			
			if($currency->deletemultipleCurrency($ids)) {
				$mysession->Admin_SMessage = $translate->_('Success_M_Delete_Currency');	
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Currency');	
			}	
			
		}	else {
		
			$mysession->Admin_EMessage = $translate->_('Err_M_Delete_Currency');				
		}
		$this->_redirect("/admin/currency");	
   }
   
   /**
     * Function updateallAction
	 *
	 * This function is used to update all currency.
	 *
     * Date Created: 2011-09-27
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function updateallAction()
   {
		global $mysession;
		
		$this->view->site_url = SITE_URL."admin/currency";
		$this->view->updateall_action = SITE_URL."admin/currency/updateall";
		
		$translate = Zend_Registry::get('Zend_Translate');
		$currency = new Models_Currency();
		$home = new Models_AdminMaster();
		
		$request = $this->getRequest();
		$filter = new Zend_Filter_StripTags();	
		$total_rec = 0;
		
   		if($request->isPost()) {
			
			$float_validator = new Zend_Validate_Float();
			
			$total_rec = (int)$filter->filter(trim($this->_request->getPost('total_records')));
			$data = array();
			if($total_rec > 0)
			{
				$editErrorMessage = array();
				
				for($i=0; $i < $total_rec; $i++)
				{
					$data[$i]["currency_id"] = $filter->filter(trim($this->_request->getPost('currency_id_'.$i))); 	
					$data[$i]['currency_name']=$filter->filter(trim($this->_request->getPost('currency_name_'.$i))); 	
					$data[$i]['currency_code']=$filter->filter(trim($this->_request->getPost('currency_code_'.$i))); 	
					$data[$i]['currency_symbol']=$filter->filter(trim($this->_request->getPost('currency_symbol_'.$i))); 
					$data[$i]['currency_value']=$filter->filter(trim($this->_request->getPost('currency_value_'.$i)));
				}	
				
				for($i=0; $i < $total_rec; $i++)
				{
					if($data[$i]['currency_name'] == "") {
						$editErrorMessage[] = $translate->_('Err_Currency_Name');			
					}
					if($data[$i]['currency_code'] == "") {
						$editErrorMessage[] = $translate->_('Err_Currency_Code');			
					}
					if($data[$i]['currency_symbol'] == "") {
						$editErrorMessage[]= $translate->_('Err_Currency_Symbol');			
					}
					if($data[$i]['currency_value'] == "") {
						$editErrorMessage[] = $translate->_('Err_Currency_Value');			
					}else if(!$float_validator->isValid($data[$i]['currency_value'])) {
						$editErrorMessage[] = $translate->_('Err_Currency_Invalid_Value');		
					}
					
					if(count($editErrorMessage) > 0)
					{
						break;
					}
				}
				
				if(count($editErrorMessage) > 0)
				{
					$this->view->records = $data;
					$this->view->editErrorMessage = $editErrorMessage;
				}
				else
				{
					for($i=0; $i < $total_rec; $i++)
					{
						$where = "currency_id != ".$data[$i]["currency_id"];
						if($home->ValidateTableField("currency_name",$data[$i]['currency_name'],"currency_master",$where)) {
							$where = "currency_id = ".$data[$i]["currency_id"];
							if($currency->updateCurrency($data[$i],$where)) {
								$mysession->Admin_SMessage = $translate->_('Success_Edit_Currency');
								 	
							} else {
								$editErrorMessage[] =  $translate->_('Err_Edit_Currency'); 
							}
							 
						} else {			
							
							$editErrorMessage[] = $translate->_('Err_Currency_Name_Exists');	
						}
					}
					
					if(count($editErrorMessage) == 0)
					{
						$this->_redirect('/admin/currency');
					}
					else
					{
						$this->view->records = $data;
						$this->view->editErrorMessage = $editErrorMessage;
					}
					
				}
			}			
		}
		else
		{
			$this->view->records = $currency->GetAllCurrency();
		}
   }
}
?>