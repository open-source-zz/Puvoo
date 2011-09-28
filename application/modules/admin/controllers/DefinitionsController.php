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
 * Admin Definitions Controller.
 *
 * Admin_DefinitionsController  extends AdminCommonController. 
 * It controls language definitions on admin section
 *
 * Date Created: 2011-09-19
 *
 * @category	Puvoo
 * @package 	Admin_Controllers
 * @author	    Amar
 *  
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/  
 class Admin_DefinitionsController extends AdminCommonController
{

	/**
     * Function init
	 *
	 * This function is used for initialization. 
	 * You can also include necessary javascript files from here.
	 *
     * Date Created: 2011-09-19
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	 
    function init()
    {
        parent::init();
        $this->view->JS_Files = array('admin/definitions.js','admin/AdminCommon.js');	
		Zend_Loader::loadClass('Models_LanguageDefinitions');
	}
	
    /**
     * Function indexAction
	 *
	 * This function is used to list languages on site and to do necessary actions
	 *
     * Date Created: 2011-09-19
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
		$this->view->site_url = SITE_URL."admin/definitions";
		$this->view->add_action = SITE_URL."admin/definitions/add";
		$this->view->edit_action = SITE_URL."admin/definitions/edit";
		$this->view->delete_action = SITE_URL."admin/definitions/delete";
		$this->view->delete_all_action = SITE_URL."admin/definitions/deleteall";
		
		
		//Create Object of Langage model
		$Definitions = new Models_LanguageDefinitions();
		$Language = new Models_Language();
		
		
		//set current page number
		$page_no = 1;
		
		//set no. of records to display on page
		$pagesize = $mysession->pagesize;
		
		//set search param
		$is_search = "";
		
		//Get available languages
		$this->view->languages = $Language->getActiveLanguages();
		
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
			$data['definition_value']=$filter->filter(trim($this->_request->getPost('definition_value'))); 	
			$data['language_id']= (int)$filter->filter(trim($this->_request->getPost('language_id')));
			$data['content_group']= $filter->filter(trim($this->_request->getPost('content_group')));
			$data['status'] = $filter->filter(trim($this->_request->getPost('status')));
			
			
			//Get searched Languages
			$result = $Definitions->SearchDefinitions($data);
			
			$this->view->data = $data;
			
		} elseif($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $Definitions->GetAllDefinitions();
						
		} else 	{
			//Get all Categories
			$result = $Definitions->GetAllDefinitions();
			
		}		
		// Success Message
		$this->view->Admin_Message = $mysession->Admin_Message;
		$this->view->Admin_Message_Error = $mysession->Admin_Message_Error;
		$mysession->Admin_Message = "";
		$mysession->Admin_Message_Error = "";
		
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
	 * This function is used to add language
	 *
     * Date Created: 2011-09-20
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function addAction()
   {
   		global $mysession;
		$this->view->site_url = SITE_URL."admin/definitions";
		$this->view->add_action = SITE_URL."admin/definitions/add";		
		
		$Definitions = new Models_LanguageDefinitions();
		$home = new Models_AdminMaster();
		$Language = new Models_Language();
   		
		
		//Get available languages
		$this->view->languages = $Language->getActiveLanguages();
		
		$request = $this->getRequest();
		
		if($request->isPost()){
			
			$translate = Zend_Registry::get('Zend_Translate');
			
			$validator = new Zend_Validate_Regex(array('pattern' => '/^[a-zA-Z0-9_]+$/'));
			
			$filter = new Zend_Filter_StripTags();
			
			$data['language_id']=(int)$filter->filter(trim($this->_request->getPost('language_id')));
			$data['content_group']=$filter->filter(trim($this->_request->getPost('content_group')));
			$data['definition_key']=$filter->filter(trim($this->_request->getPost('definition_key')));
			$data['definition_value']=$filter->filter(trim($this->_request->getPost('definition_value')));
			$data['status']=$filter->filter(trim($this->_request->getPost('status')));
			
			
			
			$addErrorMessage = array();
			
			if($data['definition_key'] == "") {
				$addErrorMessage[] = $translate->_('Err_Definition_Key');			
			}
			
			if($data['definition_key'] != "" && $validator->isValid($data['definition_key']) === false) {
				$addErrorMessage[] = $translate->_('Err_Definition_Key_Invalid');			
			}
			
			if($data['definition_value'] == "") {
				$addErrorMessage[] = $translate->_('Err_Definition_Value');			
			}
			
			$this->view->data = $data;
			
			if(count($addErrorMessage) === 0)
			{
				$where = " language_id = " . $data["language_id"] . " and content_group = '" . $data['content_group']. "' ";
				if($home->ValidateTableField("definition_key",$data['definition_key'],"language_definitions",$where)) {
					if($Definitions->insertDefinition($data)) {
						$mysession->Admin_Message = $translate->_('Success_Add_Definition');
						$this->_redirect('/admin/definitions'); 	
					} else {
						$addErrorMessage[] = "There is some problem in adding definition";	
					}
					
				} else {
				
					$this->view->addErrorMessage = array($translate->_('Err_Definition_Key_Exists'));	
				}
			} else {
				
				$this->view->addErrorMessage = $addErrorMessage;
			} 
		}
   }
   
   
   /**
     * Function editAction
	 *
	 * This function is used to update definition data.
	 *
     * Date Created: 2011-09-20
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function editAction()
   {
   		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$Definitions = new Models_LanguageDefinitions();
   		$home = new Models_AdminMaster();
		$Language = new Models_Language();
   		
		//Get available languages
		$this->view->languages = $Language->getActiveLanguages();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		
		$validator = new Zend_Validate_Regex(array('pattern' => '/^[a-zA-Z0-9_]+$/'));
		
		$definition_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($definition_id > 0 && $definition_id != "") {
			$this->view->records = $Definitions->getDefinitionById($definition_id);	
			$this->view->definition_id = $definition_id;	
			
		} else {
			
			if($request->isPost()){
				
				$data['language_id']=(int)$filter->filter(trim($this->_request->getPost('language_id')));
				$data['content_group']=$filter->filter(trim($this->_request->getPost('content_group')));
				$data['definition_key']=$filter->filter(trim($this->_request->getPost('definition_key')));
				$data['definition_value']=$filter->filter(trim($this->_request->getPost('definition_value')));
				$data['status']=$filter->filter(trim($this->_request->getPost('status')));
			
				$definition_id = $filter->filter(trim($this->_request->getPost('definition_id'))); 	
				
				
				$editErrorMessage = array();
				
				if($data['definition_key'] == "") {
					$editErrorMessage[] = $translate->_('Err_Definition_Key');			
				}
				
				if($data['definition_key'] != "" && $validator->isValid($data['definition_key']) === false) {
					$editErrorMessage[] = $translate->_('Err_Definition_Key_Invalid');			
				}
			
				if($data['definition_value'] == "") {
					$editErrorMessage[] = $translate->_('Err_Definition_Value');			
				}
				
			
				if(count($editErrorMessage) === 0)
				{
					$where = " language_id = " . $data["language_id"] . " and content_group = '" . $data['content_group']. "' and id != ".$definition_id;
					if($home->ValidateTableField("definition_key",$data['definition_key'],"language_definitions",$where)) {
						$where = " id = " . $definition_id;
						if($Definitions->updateDefinition($data,$where)) {
							$mysession->Admin_Message = $translate->_('Success_Edit_Definition');
							$this->_redirect('/admin/definitions'); 	
						} else {
							$editErrorMessage[] = "There is some problem in editing definition";	
						}
						
					} else {
					
						$this->view->editErrorMessage = array($translate->_('Err_Definition_Key_Exists'));	
					}
				} else {
					$this->view->editErrorMessage = $editErrorMessage;
				} 
			
				$this->view->records = $data;
				$this->view->definition_id = $definition_id;
				$this->view->editErrorMessage = $editErrorMessage;
				
			} else {
			
				$this->_redirect("/admin/definitions");
			}
		
		}
		
   }
   
   /**
     * Function deleteAction
	 *
	 * This function is used to delete the definition
	 *
     * Date Created: 2011-09-20
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function deleteAction()
   {
   		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$Definitions = new Models_LanguageDefinitions();
   		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$definition_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($definition_id > 0 && $definition_id != "") {
			if($Definitions->deleteDefinition($definition_id)) {
				$mysession->Admin_Message = $translate->_('Success_Delete_Definition');
			} else {
				$mysession->Admin_Message = "There is some problem in deleting language";	
			}		
		} 
		$this->_redirect("/admin/definitions");		
   }  
   
   
   /**
     * Function deleteallAction
	 *
	 * This function is used to delete all selected definitions.
	 *
     * Date Created: 2011-09-20
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function deleteallAction()
   {
   		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$Definitions = new Models_LanguageDefinitions();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		
   		if(isset($_POST["id"])) {
		
			$definition_ids = $this->_request->getPost('id'); 
			$ids = implode($definition_ids,",");
			
			if($Definitions->deleteMultipleDefinitions($ids)) {
				$mysession->Admin_Message = $translate->_('Success_M_Delete_Definition');	
			} else {
				$mysession->Admin_Message_Error = "There is some problem in deleting definitions";				
			}	
			
		}	else {
		
			$mysession->Admin_Message_Error = $translate->_('Err_M_Delete_Definition');				
		}
		$this->_redirect("/admin/definitions");	
   }
   
}
?>