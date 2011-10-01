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
 * Admin Length Controller.
 *
 * Admin_LengthController  extends AdminCommonController. 
 * It controls length on admin section
 *
 * Date Created: 2011-09-24
 *
 * @weight	Puvoo
 * @package 	Admin_Controllers
 * @author      Yogesh
 *  
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/  
 class Admin_LengthController extends AdminCommonController
{

	/**
     * Function init
	 *
	 * This function is used for initialization. 
	 * You can also include necessary javascript files from here.
	 *
     * Date Created: 2011-09-24
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
        $this->view->JS_Files = array('admin/length.js','admin/AdminCommon.js');	
		define("TABLE", "length_master");
		define("PRIMARY_KEY", "length_unit_id");
		
    }
	
    /**
     * Function indexAction
	 *
	 * This function is used for initialization. Also include necessary javascript files.
	 * This function is also used for the listing and searchig records
	 *
     * Date Created: 2011-09-24
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
		$this->view->site_url = SITE_URL."admin/length";
		$this->view->add_action = SITE_URL."admin/length/add";
		$this->view->edit_action = SITE_URL."admin/length/edit";
		$this->view->delete_action = SITE_URL."admin/length/delete";
		$this->view->delete_all_action = SITE_URL."admin/length/deleteall";
		
		
		//Create Object of Weight model
		$length = new Models_Length();
		
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
			$data['length_unit_name']=$filter->filter(trim($this->_request->getPost('length_unit_name'))); 
			$data['length_unit_key']=$filter->filter(trim($this->_request->getPost('length_unit_key'))); 
			//Get search Weight
			$result = $length->SearchRecords($data);
			
		} elseif($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $length->GetAllRecords();
						
		} else 	{
			//Get all Weight
			$result = $length->GetAllRecords();
			
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
	 * This function is used to add length
	 *
     * Date Created: 2011-09-24
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
		$this->view->site_url = SITE_URL."admin/length";
		$this->view->add_action = SITE_URL."admin/length/add";		
		
		$length = new Models_Length();
		$home = new Models_AdminMaster();
		$request = $this->getRequest();
		
		if($request->isPost()){
			
			$translate = Zend_Registry::get('Zend_Translate');
			
			$filter = new Zend_Filter_StripTags();	
			$data['length_unit_name']=$filter->filter(trim($this->_request->getPost('length_unit_name'))); 
			$data['length_unit_key']=$filter->filter(trim($this->_request->getPost('length_unit_key'))); 	
			 	
			$addErrorMessage = array();
			if($data['length_unit_name'] == "") {
				$addErrorMessage[] = $translate->_('Err_Length_Unit_Name');			
			}
			if($data['length_unit_key'] == "") {
				$addErrorMessage[] = $translate->_('Err_Length_Unit_Key');			
			}
			$this->view->data = $data;
			
			$where = "1 = 1";
			if( count($addErrorMessage) == 0 || $addErrorMessage == '' ){
				if($home->ValidateTableField("length_unit_name",$data['length_unit_name'],TABLE,$where)) {
					if($length->insertRecord($data)) {
						$mysession->Admin_SMessage = $translate->_('Success_Add_Weight');
						$this->_redirect('/admin/length'); 	
					} else {
						$addErrorMessage[] = $translate->_('Err_Add_Length');	
					}
				} else { 
					$addErrorMessage[] = $translate->_('Err_Length_Unit_Name_Exists');	
				} 
			}
			
			$this->view->addErrorMessage = $addErrorMessage;
		}
   }
   
   
   /**
     * Function editAction
	 *
	 * This function is used to update length data.
	 *
     * Date Created: 2011-09-24
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
		
		$this->view->site_url = SITE_URL."admin/length";
		$this->view->edit_action = SITE_URL."admin/length/edit";		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$length = new Models_Length();
   		$home = new Models_AdminMaster();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$length_unit_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($length_unit_id > 0 && $length_unit_id != "") {
			$this->view->records = $length->GetRecordById($length_unit_id);	
			$this->view->length_unit_id =  $length_unit_id;	
		} else {
			
			if($request->isPost()){
				
				$data["length_unit_id"] = $filter->filter(trim($this->_request->getPost('length_unit_id'))); 	
				$data['length_unit_name']=$filter->filter(trim($this->_request->getPost('length_unit_name'))); 
				$data['length_unit_key']=$filter->filter(trim($this->_request->getPost('length_unit_key'))); 	
					
				$editErrorMessage = array();
				if($data['length_unit_name'] == "") {
					$editErrorMessage[] = $translate->_('Err_Length_Unit_Name');			
				}
				if($data['length_unit_key'] == "") {
					$editErrorMessage[] = $translate->_('Err_Length_Unit_Key');			
				}	
				
				$where = "length_unit_id != ".$data["length_unit_id"];
				if( count($editErrorMessage) == 0 || $editErrorMessage == '' ){
					if($home->ValidateTableField("length_unit_name",$data['length_unit_name'],TABLE,$where)) {
						$where = "length_unit_id = ".$data["length_unit_id"];
						if($length->updateRecord($data,$where)) {
							$mysession->Admin_SMessage = $translate->_('Success_Edit_Length');
							$this->_redirect('/admin/length'); 	
						} else {
							$editErrorMessage[] = $translate->_('Err_Edit_Length');	
						}
					} else {			
					
						$editErrorMessage[] = $translate->_('Err_Length_Unit_Name_Exists');	
					}
				} 
				
				$this->view->records = $data;	
				$this->view->length_unit_id =  $data["length_unit_id"];	
				$this->view->editErrorMessage = $editErrorMessage;
				
			} else {
			
				$this->_redirect("/admin/length");
			}
		
		}
		
   }
   
   /**
     * Function deleteAction
	 *
	 * This function is used to delete the length
	 *
     * Date Created: 2011-09-24
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
		
		$length = new Models_Length();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$length_unit_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($length_unit_id > 0 && $length_unit_id != "") {
			if($length->deleteRcord($length_unit_id)) {
				$mysession->Admin_SMessage = $translate->_('Success_Delete_Length');
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Length');	
			}		
		} 
		$this->_redirect("/admin/length");		
   }  
   
   
   /**
     * Function deleteallAction
	 *
	 * This function is used to delete all selected length.
	 *
     * Date Created: 2011-09-24
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
		
		$length = new Models_Length();
		$filter = new Zend_Filter_StripTags();	
		
		$request = $this->getRequest();
		
   		if(isset($_POST["id"])) {
		
			$length_unit_ids = $this->_request->getPost('id'); 
			$ids = implode($length_unit_ids,",");
			
			if($length->deletemultipleRecord($ids)) {
				$mysession->Admin_SMessage = $translate->_('Success_M_Delete_Length');	
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_M_Length');				
			}	
			
		}	else {
		
			$mysession->Admin_EMessage = $translate->_('Err_M_Delete_Length');				
		}
		$this->_redirect("/admin/length");	
   }
   
}
?>