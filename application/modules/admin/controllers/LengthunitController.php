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
 * Admin Lengthunit Controller.
 *
 * Admin_LengthunitController  extends AdminCommonController. 
 * It controls length on admin section
 *
 * Date Created: 2011-08-20
 *
 * @length	Puvoo
 * @package 	Admin_Controllers
 * @author      Vaibhavi 
 *  
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/  
 class Admin_LengthunitController extends AdminCommonController
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
        $this->view->JS_Files = array('admin/lengthunit.js','admin/AdminCommon.js');	
		//$this->view->JS_Files = array('admin/AdminCommon.js');	
		Zend_Loader::loadClass('Models_Lengthunit');
		Zend_Loader::loadClass('Models_Length');
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
		$this->view->site_url = SITE_URL."admin/lengthunit";
		$this->view->add_action = SITE_URL."admin/lengthunit/add";
		$this->view->edit_action = SITE_URL."admin/lengthunit/edit";
		$this->view->delete_action = SITE_URL."admin/lengthunit/delete";
		$this->view->delete_all_action = SITE_URL."admin/lengthunit/deleteall";
		
		
		//Create Object of Weight model
		$lengthunit = new Models_Lengthunit();
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
			$data['from_id']= $filter->filter(trim($this->_request->getPost('from_id'))); 
			$data['to_id']  = $filter->filter(trim($this->_request->getPost('to_id'))); 
			$data['value']  = $filter->filter(trim($this->_request->getPost('value'))); 
			
			//Get search Weight
			$result = $lengthunit->SearchLengthunit($data);
			
		} elseif($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $lengthunit->GetAllLengthunit();
						
		} else 	{
			//Get all Weight
			$result = $lengthunit->GetAllLengthunit();
			
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
		$this->view->site_url = SITE_URL."admin/lengthunit";
		$this->view->add_action = SITE_URL."admin/lengthunit/add";		
		
		$lengthunit = new Models_Lengthunit();
		$length = new Models_Length();
		$home = new Models_AdminMaster();
		
		$request = $this->getRequest();
		
		define("TABLE", "length_master");
		$this->view->length = $length->GetAllRecords();
		
		if($request->isPost()){
			
			$translate = Zend_Registry::get('Zend_Translate');
			$float_validator = new Zend_Validate_Float();
			
			$filter = new Zend_Filter_StripTags();	
			$data['from_id']=$filter->filter(trim($this->_request->getPost('from_id'))); 	
			$data['to_id']=$filter->filter(trim($this->_request->getPost('to_id'))); 	
			$data['value']=$filter->filter(trim($this->_request->getPost('value'))); 	
			 	
			$addErrorMessage = array();
			if($data['from_id'] == "0") {
				$addErrorMessage[] = $translate->_('Err_Lengthunit_Id');			
			}
			if($data['to_id'] == "0") {
				$addErrorMessage[] = $translate->_('Err_Lengthunit_Id');			
			}
			if($data['value'] == "") {
				$addErrorMessage[] = $translate->_('Err_Lengthunit_Value');			
			} else if(!$float_validator->isValid($data['value'])) {
				$addErrorMessage[] = $translate->_('Err_Lengthunit_Invalid_Value');		
			}
			if($data['from_id'] == $data['to_id']) {
				$addErrorMessage[] = $translate->_('Err_Lengthunit_Unit_Same');			
			}
			
			$this->view->data = $data;
			
			$where = "1 = 1";
			if($lengthunit->ValidateLengthunit($data['from_id'],$data['to_id']) == 0) {
				if( count($addErrorMessage) == 0 || $addErrorMessage  == "" ){
					if($lengthunit->insertLengthunit($data)) {
						$mysession->Admin_SMessage = $translate->_('Success_Add_Lengthunit');
						$this->_redirect('/admin/lengthunit'); 	
					} else {
						$addErrorMessage[] = $translate->_('Err_Add_Lengthunit'); 
					}
				} else { 
					$this->view->addErrorMessage = $addErrorMessage;
				} 
			} else {
			
				$addErrorMessage[] = $translate->_('Err_Lengthunit_From_Id_Exists');	
			}
			$this->view->addErrorMessage = $addErrorMessage;
		}
   }
   
   
   /**
     * Function editAction
	 *
	 * This function is used to update length data.
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
		
		$translate = Zend_Registry::get('Zend_Translate');

		$lengthunit = new Models_Lengthunit();
		$length = new Models_Length();
   		$home = new Models_AdminMaster();
		
		$request = $this->getRequest();
		
		define("TABLE", "length_master");
		$this->view->length = $length->GetAllRecords();
		
		$filter = new Zend_Filter_StripTags();	
		$from_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id1'))); 	
		$to_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id2'))); 
		
		
		if($from_id > 0 && $from_id != "" && $to_id > 0 && $to_id != "") {
			$this->view->records = $lengthunit->GetLengthunitById($from_id,$to_id);	
			$this->view->from_id_p =  $from_id;	
			$this->view->to_id_p =  $to_id;	
			
		} else {
			
			if($request->isPost()){
				
				$float_validator = new Zend_Validate_Float();
				
				$data['from_id']=$filter->filter(trim($this->_request->getPost('from_id'))); 	
				$data['to_id']=$filter->filter(trim($this->_request->getPost('to_id'))); 	
				$data['value']=$filter->filter(trim($this->_request->getPost('value'))); 
				
				$editErrorMessage = array();
				if($data['from_id'] == "0") {
					$editErrorMessage = $translate->_('Err_Lengthunit_Id');			
				}
				if($data['to_id'] == "0") {
					$editErrorMessage = $translate->_('Err_Lengthunit_Id');			
				}
				if($data['value'] == "") {
					$editErrorMessage = $translate->_('Err_Lengthunit_Value');			
				} else if(!$float_validator->isValid($data['value'])) {
					$editErrorMessage[] = $translate->_('Err_Lengthunit_Invalid_Value');		
				}
				if($data['from_id'] == $data['to_id']) {
					$editErrorMessage .= $translate->_('Err_Lengthunit_Unit_Same');			
				}
				
				$where = "from_id = ".$this->_request->getPost('from_id_p')." and to_id = ".$this->_request->getPost('to_id_p');
				if($lengthunit->ValidateLengthunit($data['from_id'],$data['to_id']) == 0) {
					if( count($editErrorMessage) == 0 || $editErrorMessage == ''){
						$where = "from_id = ".$this->_request->getPost('from_id_p')." and to_id = ".$this->_request->getPost('to_id_p') ;
						
						if($lengthunit->updateLengthunit($data,$where)) {
							$mysession->Admin_SMessage = $translate->_('Success_Edit_Lengthunit');
							$this->_redirect('/admin/lengthunit'); 	
						} else {
							$editErrorMessage[] = $translate->_('Err_Edit_Lengthunit'); 
						}
					} 
				} else {			
					
					$editErrorMessage[] = $translate->_('Err_Lengthunit_From_Id_Exists');	
				}	

				$this->view->records = $data;
				$this->view->from_id_p =  $this->_request->getPost('from_id_p');
				$this->view->to_id_p =  $this->_request->getPost('to_id_p');	
				$this->view->editErrorMessage = $editErrorMessage;
				
			} else {
			
				$this->_redirect("/admin/lengthunit");
			}
		
		}
		
   }
   
   /**
     * Function deleteAction
	 *
	 * This function is used to delete the length
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
		
		$lengthunit = new Models_Lengthunit();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$from_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id1'))); 
		$to_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id2'))); 	
		
		if($from_id > 0 && $from_id != "" && $to_id > 0 && $to_id != "") {
			if($lengthunit->deleteLengthunit($from_id,$to_id)) {
				$mysession->Admin_SMessage = $translate->_('Success_Delete_Lengthunit');
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Length_Unit');
			}		
		} 
		$this->_redirect("/admin/lengthunit");		
   }  
   
   
   /**
     * Function deleteallAction
	 *
	 * This function is used to delete all selected length.
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
   		//"deletemultipleWeight"
		
		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$lengthunit = new Models_Lengthunit();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		
   		if(isset($_POST["id"]) && isset($_POST["to_id"])) {
		
			$from_ids = $this->_request->getPost('id'); 
			$to_ids = $this->_request->getPost('to_id'); 
			
			$ids = implode($from_ids,",");
			$toids = implode($to_ids,",");
			
			if($lengthunit->deletemultipleLengthunit($ids,$toids)) {
				$mysession->Admin_SMessage = $translate->_('Success_M_Delete_Lengthunit');	
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Length_Unit');	
			}	
			
		}	else {
		
			$mysession->Admin_SMessage = $translate->_('Err_M_Delete_Lengthunit');				
		}
		$this->_redirect("/admin/lengthunit");	
   }
   
}
?>