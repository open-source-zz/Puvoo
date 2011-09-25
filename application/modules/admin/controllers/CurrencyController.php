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
 * @author      Vaibhavi Jariwala 
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
     * @author Vaibhavi Jariwala
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
     * @author Vaibhavi Jariwala
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
			$result = $currency->SearchCurrency($data);
			
		} elseif($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $currency->GetAllCurrency();
						
		} else 	{
			//Get all Currency
			$result = $currency->GetAllCurrency();
			
		}		
		// Success Message
		$this->view->Admin_Message = $mysession->Admin_Message;
		$mysession->Admin_Message = "";
		
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
			
			$filter = new Zend_Filter_StripTags();	
			$data['currency_name']=$filter->filter(trim($this->_request->getPost('currency_name'))); 	
			$data['currency_code']=$filter->filter(trim($this->_request->getPost('currency_code'))); 	
			$data['currency_symbol']=$filter->filter(trim($this->_request->getPost('currency_symbol')));
			$data['currency_value']=$filter->filter(trim($this->_request->getPost('currency_value')));
			 	
			$addErrorMessage = "";
			if($data['currency_name'] == "") {
				$addErrorMessage .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Currency_Name')."</h5><br />";			
			}
			if($data['currency_code'] == "") {
				$addErrorMessage .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Currency_Code')."</h5><br />";			
			}
			if($data['currency_symbol'] == "") {
				$addErrorMessage .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Currency_Symbol')."</h5><br />";			
			}
			if($data['currency_value'] == "") {
				$addErrorMessage .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Currency_Value')."</h5><br />";			
			}
			$where = "1 = 1";
			if($home->ValidateTableField("currency_name",$data['currency_name'],"currency_master",$where)) {
				if( $addErrorMessage == ""){
					if($currency->insertCurrency($data)) {
						$mysession->Admin_Message = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Success_Add_Currency')."</h5>";
						$this->_redirect('/admin/currency'); 	
					} else {
						$addErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>There is some problem in adding currency</h5>";	
					}
				} else { 
					$this->view->addErrorMessage = $addErrorMessage;
				} 
			} else {
			
				$this->view->addErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Currency_Name_Exists')."</h5>";	
			}
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
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function editAction()
   {
   		global $mysession;
		
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
				
				$data["currency_id"] = $filter->filter(trim($this->_request->getPost('currency_id'))); 	
				$data['currency_name']=$filter->filter(trim($this->_request->getPost('currency_name'))); 	
				$data['currency_code']=$filter->filter(trim($this->_request->getPost('currency_code'))); 	
				$data['currency_symbol']=$filter->filter(trim($this->_request->getPost('currency_symbol'))); 
				$data['currency_value']=$filter->filter(trim($this->_request->getPost('currency_value')));
				
				$editErrorMessage = "";
				if($data['currency_name'] == "") {
					$editErrorMessage .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Currency_Name')."</h5>";			
				}
				if($data['currency_code'] == "") {
					$editErrorMessage .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Currency_Code')."</h5>";			
				}
				if($data['currency_symbol'] == "") {
					$editErrorMessage .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Currency_Symbol')."</h5>";			
				}
				if($data['currency_value'] == "") {
					$editErrorMessage .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Currency_Value')."</h5>";			
				}
				
				$where = "currency_id != ".$data["currency_id"];
				if($home->ValidateTableField("currency_name",$data['currency_name'],"currency_master",$where)) {
					if( $editErrorMessage == ""){
						$where = "currency_id = ".$data["currency_id"];
						if($currency->updateCurrency($data,$where)) {
							$mysession->Admin_Message = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Success_Edit_Currency')."</h5>";
							$this->_redirect('/admin/currency'); 	
						} else {
							$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>There is some problem in editing currency</h5>";	
						}
					} 
				} else {			
					
					$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Currency_Name_Exists')."</h5>";	
				}
				
				$this->view->records = $currency->GetCurrencyById($data["currency_id"]);	
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
				$mysession->Admin_Message = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Success_Delete_Currency')."</h5>";
			} else {
				$mysession->Admin_Message = "<h5 style='color:#FF0000;margin-bottom:0px;'>There is some problem in deleting currency</h5>";	
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
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function deleteallAction()
   {
   		//"deletemultipleCurrency"
		
		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$currency = new Models_Currency();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		
   		if(isset($_POST["id"])) {
		
			$currency_ids = $this->_request->getPost('id'); 
			$ids = implode($currency_ids,",");
			
			if($currency->deletemultipleCurrency($ids)) {
				$mysession->Admin_Message = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Success_M_Delete_Currency')."</h5>";	
			} else {
				$mysession->Admin_Message = "<h5 style='color:#FF0000;margin-bottom:0px;'>There is some problem in deleting currency</h5>";				
			}	
			
		}	else {
		
			$mysession->Admin_Message = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_M_Delete_Currency')."</h5>";				
		}
		$this->_redirect("/admin/currency");	
   }
   
}
?>
