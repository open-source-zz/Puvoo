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
 * Admin Order Status Controller.
 *
 * Admin_OrderstatusController  extends AdminCommonController. 
 * It controls System's status for the order on admin section
 *
 * Date Created: 2011-11-04
 *
 * @banner	Puvoo
 * @package 	Admin_Controllers
 * @author      Yogesh
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/  
 class Admin_OrderstatusController extends AdminCommonController
{

	/**
     * Function init
	 *
	 * This function is used for initialization. 
	 * You can also include necessary javascript files from here.
	 *
     * Date Created: 2011-11-04
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
        $this->view->JS_Files = array('admin/AdminCommon.js');	
		
    }
	
    /**
     * Function indexAction
	 *
	 * This function is used for initialization. Also include necessary javascript files.
	 *
     * Date Created: 2011-11-04
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   function indexAction() 
   {
        global $mysession,$arr_pagesize;
		$this->view->site_url = SITE_URL."admin/orderstatus";
		$this->view->add_action = SITE_URL."admin/orderstatus/add";
		$this->view->edit_action = SITE_URL."admin/orderstatus/edit";
		$this->view->delete_action = SITE_URL."admin/orderstatus/delete";
		$this->view->delete_all_action = SITE_URL."admin/orderstatus/deleteall";
		
		
		//Create Object of banner model
		$banner = new Models_Status();
		
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
		
		if($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $banner->GetAllStatus();
						
		} else 	{
			//Get all banner
			$result = $banner->GetAllStatus();
			
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
	 * This function is used to add banner
	 *
     * Date Created: 2011-10-07
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
		$this->view->site_url = SITE_URL."admin/orderstatus";
		$this->view->add_action = SITE_URL."admin/orderstatus/add";		
		
		$banner = new Models_Status();
		$home = new Models_AdminMaster();
		
		$request = $this->getRequest();
		
		if($request->isPost()){
			
			$translate = Zend_Registry::get('Zend_Translate');
			
			$filter = new Zend_Filter_StripTags();	
			$url_validator = new UrlValidator();
			
			
			$data['status_value'] = $filter->filter(trim($this->_request->getPost('status_value'))); 	
			$data['status'] = 1;
			
			$addErrorMessage = array();
				
			if($data['status_value'] == "") {
				
				$addErrorMessage[] = $translate->_('Err_Status_Value');
							
			} 
			
			$this->view->data = $data;
			$where = '1=1';
			if( count($addErrorMessage) == 0 || $addErrorMessage == '' ){
			
				if($home->ValidateTableField("status_value",$data['status_value'],"order_status",$where)) {
				
					if($banner->insertStatus($data)) {
					
						$mysession->Admin_SMessage = $translate->_('Success_Add_Status');
						$this->_redirect('/admin/orderstatus'); 	
						
					} else {
					
						$addErrorMessage[] = $translate->_('Err_Add_Status');	
					}
					
				}
			} 
			
			$this->view->addErrorMessage = $addErrorMessage;
		}
   }
   
   
   /**
     * Function editAction
	 *
	 * This function is used to update banner data.
	 *
     * Date Created: 2011-10-07
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
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$status = new Models_Status();
   		$home = new Models_AdminMaster();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$order_status_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($order_status_id > 0 && $order_status_id != "") {
		
			$this->view->records = $status->GetStatusById($order_status_id);	
			$this->view->order_status_id =  $order_status_id;	
			
		} else {
			
			if($request->isPost()){
				
				$data["order_status_id"] = $filter->filter(trim($this->_request->getPost('order_status_id'))); 	
				$data['status_value'] = $filter->filter(trim($this->_request->getPost('status_value'))); 	
				
				$editErrorMessage = array();
					
				if($data['status_value'] == "") {
					
					$editErrorMessage[] = $translate->_('Err_Status_Value');
								
				} 
				
				if( count($editErrorMessage) == 0 || $editErrorMessage == ''){	
									
					$where1 = "order_status_id != ".$data["order_status_id"];
					
					if($home->ValidateTableField("status_value",$data['status_value'],"order_status",$where1)) {
					
						$where2 = "order_status_id = ".$data["order_status_id"];
						if($status->updateStatus($data,$where2)) {
						
							$mysession->Admin_SMessage = $translate->_('Success_Edit_Status');
							$this->_redirect('/admin/orderstatus'); 	
							
						} else {
						
							$editErrorMessage[] = $translate->_('Err_Edit_Status');
						}
					
					}  else {			
						
						$editErrorMessage[] = $translate->_('Err_Status_Value_Exists');	
					}
					
					
				} 
				
				$this->view->records = $data;	
				$this->view->order_status_id =  $data["order_status_id"];	
				$this->view->editErrorMessage = $editErrorMessage;
				
			} else {
			
				$this->_redirect("/admin/orderstatus");
			}
		
		}
		
   }
   
   /**
     * Function deleteAction
	 *
	 * This function is used to delete the banner
	 *
     * Date Created: 2011-10-07
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
		
		$status = new Models_Status();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$order_status_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($order_status_id > 0 && $order_status_id != "") {
		
			if($status->deleteStatus($order_status_id)) {
			
				$mysession->Admin_SMessage = $translate->_('Success_Delete_Status');
			
			} else {
			
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Status'); 
			}	
				
		} 
		$this->_redirect("/admin/orderstatus");		
   }  
   
   
   /**
     * Function deleteallAction
	 *
	 * This function is used to delete all selected banner.
	 *
     * Date Created: 2011-10-07
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
		
		$status = new Models_Status();
		$request = $this->getRequest();
		$filter = new Zend_Filter_StripTags();	
		
   		if(isset($_POST["id"])) {
		
			$order_status_ids = $this->_request->getPost('id'); 
			$ids = implode($order_status_ids,",");
			
			if($status->deletemultipleStatus($ids)) {
			
				$mysession->Admin_SMessage = $translate->_('Success_M_Delete_Status');	
				
			} else {
			
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Status');
			}	
			
		}	else {
		
			$mysession->Admin_EMessage = $translate->_('Err_M_Delete_Status');				
		}
		
		$this->_redirect("/admin/orderstatus");	
   }
   
}
?>