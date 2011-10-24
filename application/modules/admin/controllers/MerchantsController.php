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
 * Admin Merchant Controller.
 *
 * Admin_MerchantsController  extends AdminCommonController. 
 * It controls Merchants on admin section
 *
 * Date Created: 2011-08-29
 *
 * @category	Puvoo
 * @package 	Admin_Controllers
 * @author	    Yogesh 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/  
 class Admin_MerchantsController extends AdminCommonController
{

	/**
     * Function init
	 *
	 * This function is used for initialization. 
	 * You can also include necessary javascript files from here.
	 *
     * Date Created: 2011-08-29
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	 
    function init()
    {
        parent::init();
        $this->view->JS_Files = array('admin/merchants.js','admin/AdminCommon.js');	
		Zend_Loader::loadClass('Models_Merchants');
		Zend_Loader::loadClass('Models_AdminMaster');
        
    }
	
    /**
     * Function indexAction
	 *
	 * This function is used for listing all merchants and for searching merchants
	 *
     * Date Created: 2011-08-20
     *
     * @access public
	 * @param ()  - No parameter
	 
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   function indexAction() 
   {
        global $mysession,$arr_pagesize;
		$this->view->site_url = SITE_URL."admin/merchants";
		$this->view->add_action = SITE_URL."admin/merchants/add";
		$this->view->edit_action = SITE_URL."admin/merchants/edit";
		$this->view->delete_action = SITE_URL."admin/merchants/delete";
		$this->view->delete_all_action = SITE_URL."admin/merchants/deleteall";
		
		
		//Create Object of Category model
		$merchants = new Models_Merchants();
		
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
			$data['user_fname']=$filter->filter(trim($this->_request->getPost('user_fname'))); 	
			$data['user_status']=$filter->filter(trim($this->_request->getPost('user_status'))); 			
			
			$result = $merchants->SearchMerchants($data);
			
		} elseif($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $merchants->GetAllMerchants();
						
		} else 	{
			//Get all Categories
			$result = $merchants->GetAllMerchants();
			
		}		
		// Error and Success Message
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
	 * This function is used to add Merchants
	 *
     * Date Created: 2011-08-29
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
		$this->view->site_url = SITE_URL."admin/merchants";
		$this->view->add_action = SITE_URL."admin/merchants/add";		
		
		$merchants = new Models_Merchants();
		$home = new Models_AdminMaster();
   		
		$request = $this->getRequest();
		
		if($request->isPost()){
			
			$translate = Zend_Registry::get('Zend_Translate');
			
			$filter = new Zend_Filter_StripTags();	
			$data['user_fname']=$filter->filter(trim($this->_request->getPost('user_fname'))); 	
			$data['user_lname']=$filter->filter(trim($this->_request->getPost('user_lname'))); 	
			$data['user_email']=$filter->filter(trim($this->_request->getPost('user_email'))); 	
			$data['user_password']=$filter->filter(trim($this->_request->getPost('user_password'))); 	
			$data['user_facebook_id']=$filter->filter(trim($this->_request->getPost('user_facebook_id'))); 	
			$data['user_verification_code']= md5(RandomPassword(6)); 	
			$data['user_status']=$filter->filter(trim($this->_request->getPost('user_status'))); 
			$data['registration_date']=date("Y-m-d H:i:s"); 
			$data['user_api_token']=md5($data['user_email'].date("Y-m-d H:i:s"));
			$data['user_api_token_expiry'] = new Zend_Db_Expr('DATE_ADD( NOW( ) , INTERVAL 18 MONTH )');
			
			$addErrorMessage = array();
			if($data['user_fname'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Merchant_FName');			
			}
			if($data['user_lname'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Merchant_LName');			
			}
			if($data['user_email'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Merchant_Email');			
			}
			
			$validator = new Zend_Validate_EmailAddress();
			if ($validator->isValid($data['user_email'])) { } else {
				$addErrorMessage[] = $translate->_('Err_Merchant_Invalid_Email');
			}
			if($data['user_password'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Merchant_Password');
			}		
			if($data['user_facebook_id'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Merchant_FB');
			}		
			if($data['user_status'] == "") {
				$addErrorMessage[] = $translate->_('Err_Is_Active');			
			}
			
			$this->view->data = $data;
			
			$where = "1 = 1";
			if( count($addErrorMessage) == 0 || $addErrorMessage == ''){
				if($home->ValidateTableField("user_email",$data['user_email'],"user_master",$where)) {
					$data['user_password'] = md5($data['user_password']);
					if($merchants->insertMerchants($data)) {
						$mysession->Admin_SMessage = $translate->_('Success_Add_Merchant'); 
						$this->_redirect('/admin/merchants'); 	
					} else {
						$addErrorMessage[] = $translate->_('Err_Add_Merchants');	
					}
				} else {
					$addErrorMessage[] = $translate->_('Err_Merchants_Exists');	
				} 
			} else {
				$this->view->addErrorMessage = $addErrorMessage;
			}
			$this->view->addErrorMessage = $addErrorMessage;			
		}
   }
   
   
   /**
     * Function editAction
	 *
	 * This function is used to update the merchant's record
	 *
     * Date Created: 2011-08-29
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
		$this->view->site_url = SITE_URL."admin/merchants";
		$this->view->edit_action = SITE_URL."admin/merchants/edit";
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$merchants = new Models_Merchants();
		$home = new Models_AdminMaster();
   		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$merchant_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($merchant_id > 0 && $merchant_id != "") {
			$this->view->records = $merchants->GetMerchantsById($merchant_id);	
			$this->view->merchant_id =  $merchant_id;	
			
		} else {
			
			if($request->isPost()){
			
				$data['user_id']=$filter->filter(trim($this->_request->getPost('merchant_id'))); 	
				$data['user_fname']=$filter->filter(trim($this->_request->getPost('user_fname'))); 	
				$data['user_lname']=$filter->filter(trim($this->_request->getPost('user_lname'))); 	
				$data['user_email']=$filter->filter(trim($this->_request->getPost('user_email'))); 	
				$data['user_facebook_id']=$filter->filter(trim($this->_request->getPost('user_facebook_id'))); 	
				$data['user_status']=$filter->filter(trim($this->_request->getPost('user_status'))); 
				
				$editErrorMessage = array();
				if($data['user_fname'] == "" ) {
					$editErrorMessage[] = $translate->_('Err_Merchant_FName');			
				}
				if($data['user_lname'] == "" ) {
					$editErrorMessage[] = $translate->_('Err_Merchant_LName');			
				}
				if($data['user_email'] == "" ) {
					$editErrorMessage[] = $translate->_('Err_Merchant_Email');			
				}					
				if($data['user_facebook_id'] == "" ) {
					$editErrorMessage[] = $translate->_('Err_Merchant_FB');
				}		
				if($data['user_status'] == "") {
					$editErrorMessage[] = $translate->_('Err_Is_Active');			
				}
				
				$cond = "user_id != ".$data["user_id"];
				
				if( count($editErrorMessage) ==  0 || $editErrorMessage == ''){
				
					if($home->ValidateTableField("user_email",$data['user_email'],"user_master",$cond)) {
					
						$where = "user_id = ".$data["user_id"];
						if($merchants->updateMerchants($data,$where)) {
							
							$mysession->Admin_SMessage = $translate->_('Success_Edit_Merchant');
							$this->_redirect('/admin/merchants'); 	
							
						} else {
							
							$editErrorMessage[] = $translate->_('Err_Edit_Merchants');	 
						}
					
					} else {
					
						$editErrorMessage[] = $translate->_('Err_Merchants_Exists');	
					}
				} 
				
				$this->view->records = $data;	
				$this->view->merchant_id =  $data["user_id"];
				$this->view->editErrorMessage = $editErrorMessage;
				
			} else {
			
				$this->_redirect("/admin/merchants");
			}
		
		}
		
   }
   
   /**
     * Function deleteAction
	 *
	 * This function is used to delete the merchant
	 *
     * Date Created: 2011-08-29
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
		
		$merchants = new Models_Merchants();
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$merchant_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($merchant_id > 0 && $merchant_id != "") {
			if($merchants->deleteMerchants($merchant_id)) {
				$mysession->Admin_SMessage = $translate->_('Success_Delete_Merchant');
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Merchant');
			}		
		} 
		$this->_redirect("/admin/merchants");		
   }  
   
   
    /**
     * Function deleteallAction
	 *
	 * This function is used to delete multiple merchants
	 *
     * Date Created: 2011-08-29
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
		
		$merchants = new Models_Merchants();
		$request = $this->getRequest();
		$filter = new Zend_Filter_StripTags();	
		
   		if(isset($_POST["id"])) {
		
			$merchants_ids = $this->_request->getPost('id'); 
			
			$ids = implode($merchants_ids,",");
			
			if($merchants->deletemultipleMerchants($ids)) {
				$mysession->Admin_SMessage = $translate->_('Success_M_Delete_Merchant');	
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Merchant');
			}	
			
		}	else {
		
			$mysession->Admin_EMessage = $translate->_('Err_M_Delete_Merchant');				
		}
		$this->_redirect("/admin/merchants");	
   }
   
}
?>