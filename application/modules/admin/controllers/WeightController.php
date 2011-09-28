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
 * Admin Weight Controller.
 *
 * Admin_WeightController  extends AdminCommonController. 
 * It controls weight on admin section
 *
 * Date Created: 2011-08-20
 *
 * @weight	Puvoo
 * @package 	Admin_Controllers
 * @author      Vaibhavi
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/  
 class Admin_WeightController extends AdminCommonController
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
        $this->view->JS_Files = array('admin/weight.js','admin/AdminCommon.js');	
		Zend_Loader::loadClass('Models_Weight');
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
		$this->view->site_url = SITE_URL."admin/weight";
		$this->view->add_action = SITE_URL."admin/weight/add";
		$this->view->edit_action = SITE_URL."admin/weight/edit";
		$this->view->delete_action = SITE_URL."admin/weight/delete";
		$this->view->delete_all_action = SITE_URL."admin/weight/deleteall";
		
		
		//Create Object of Weight model
		$weight = new Models_Weight();
		
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
			$data['weight_unit_name']=$filter->filter(trim($this->_request->getPost('weight_unit_name'))); 
			$data['weight_unit_key']=$filter->filter(trim($this->_request->getPost('weight_unit_key'))); 
			//Get search Weight
			$result = $weight->SearchWeight($data);
			
		} elseif($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $weight->GetAllWeight();
						
		} else 	{
			//Get all Weight
			$result = $weight->GetAllWeight();
			
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
	 * This function is used to add weight
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
		$this->view->site_url = SITE_URL."admin/weight";
		$this->view->add_action = SITE_URL."admin/weight/add";		
		
		$weight = new Models_Weight();
		$home = new Models_AdminMaster();
		
		$request = $this->getRequest();
		
		if($request->isPost()){
			
			$translate = Zend_Registry::get('Zend_Translate');
			
			$filter = new Zend_Filter_StripTags();	
			$data['weight_unit_name']=$filter->filter(trim($this->_request->getPost('weight_unit_name'))); 	
			$data['weight_unit_key']=$filter->filter(trim($this->_request->getPost('weight_unit_key'))); 	
			 	
			$addErrorMessage = array();
			if($data['weight_unit_name'] == "") {
				$addErrorMessage[] = $translate->_('Err_Weight_Unit_Name');			
			}
			if($data['weight_unit_key'] == "") {
				$addErrorMessage[] = $translate->_('Err_Weight_Unit_Key');			
			}
			
			$this->view->data = $data;
			
			$where = "1 = 1";
			if($home->ValidateTableField("weight_unit_name",$data['weight_unit_name'],"weight_master",$where)) {
				if( count($addErrorMessage) == 0 || $addErrorMessage == '' ){
					if($weight->insertWeight($data)) {
						$mysession->Admin_SMessage = $translate->_('Success_Add_Weight');
						$this->_redirect('/admin/weight'); 	
					} else {
						$addErrorMessage[] = $translate->_('Err_Add_Weight');	
					}
				} else { 
					$this->view->addErrorMessage = $addErrorMessage;
				} 
			} else {
				$addErrorMessage[] = $translate->_('Err_Weight_Unit_Name_Exists');	
			}
			$this->view->addErrorMessage = $addErrorMessage;
		}
   }
   
   
   /**
     * Function editAction
	 *
	 * This function is used to update weight data.
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
		
		$weight = new Models_Weight();
   		$home = new Models_AdminMaster();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$weight_unit_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($weight_unit_id > 0 && $weight_unit_id != "") {
			$this->view->records = $weight->GetWeightById($weight_unit_id);	
			$this->view->weight_unit_id =  $weight_unit_id;	
			
		} else {
			
			if($request->isPost()){
				
				$data["weight_unit_id"] = $filter->filter(trim($this->_request->getPost('weight_unit_id'))); 	
				$data['weight_unit_name']=$filter->filter(trim($this->_request->getPost('weight_unit_name'))); 	
				$data['weight_unit_key']=$filter->filter(trim($this->_request->getPost('weight_unit_key'))); 	
				
				$editErrorMessage = array();
				if($data['weight_unit_name'] == "") {
					$editErrorMessage[] = $translate->_('Err_Weight_Unit_Name');			
				}
				if($data['weight_unit_key'] == "") {
					$editErrorMessage[] = $translate->_('Err_Weight_Unit_Key');			
				}
				
				$where = "weight_unit_id != ".$data["weight_unit_id"];
				if($home->ValidateTableField("weight_unit_name",$data['weight_unit_name'],"weight_master",$where)) {
					if( count($editErrorMessage) == 0 || $editErrorMessage == ''){
						$where = "weight_unit_id = ".$data["weight_unit_id"];
						if($weight->updateWeight($data,$where)) {
							$mysession->Admin_SMessage = $translate->_('Success_Edit_Weight');
							$this->_redirect('/admin/weight'); 	
						} else {
							$editErrorMessage[] = $translate->_('Err_Edit_Weight');
						}
					} 
				} else {			
					
					$editErrorMessage[] = $translate->_('Err_Weight_Name_Exists');
				}
				
				$this->view->records = $data;	
				$this->view->weight_unit_id =  $data["weight_unit_id"];	
				$this->view->editErrorMessage = $editErrorMessage;
				
			} else {
			
				$this->_redirect("/admin/weight");
			}
		
		}
		
   }
   
   /**
     * Function deleteAction
	 *
	 * This function is used to delete the weight
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
		
		$weight = new Models_Weight();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$weight_unit_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($weight_unit_id > 0 && $weight_unit_id != "") {
			if($weight->deleteWeight($weight_unit_id)) {
				$mysession->Admin_SMessage = $translate->_('Success_Delete_Weight');
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Weight'); 
			}		
		} 
		$this->_redirect("/admin/weight");		
   }  
   
   
   /**
     * Function deleteallAction
	 *
	 * This function is used to delete all selected weight.
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
		$weight = new Models_Weight();
		$request = $this->getRequest();
		$filter = new Zend_Filter_StripTags();	
		
   		if(isset($_POST["id"])) {
		
			$weight_unit_ids = $this->_request->getPost('id'); 
			$ids = implode($weight_unit_ids,",");
			
			if($weight->deletemultipleWeight($ids)) {
				$mysession->Admin_SMessage = $translate->_('Success_M_Delete_Weight');	
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Weight');
			}	
			
		}	else {
		
			$mysession->Admin_EMessage = $translate->_('Err_M_Delete_Weight');				
		}
		$this->_redirect("/admin/weight");	
   }
   
}
?>