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
		$this->view->import_action = SITE_URL."admin/definitions/import";
		$this->view->export_action = SITE_URL."admin/definitions/export";
		
		
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
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		if($request->isPost()){
		
			$page_no = $request->getPost('page_no');
			$pagesize = $request->getPost('pagesize');
			$mysession->pagesize = $pagesize;
			$is_search = $request->getPost('is_search');
		}
		
		if($is_search == "1") {
		
			$filter = new Zend_Filter_StripTags();	
			$data['definition_key']=$filter->filter(trim($this->_request->getPost('definition_key'))); 	
			$data['definition_value']=$filter->filter(trim($this->_request->getPost('definition_value'))); 	
			$data['language_id']= (int)$filter->filter(trim($this->_request->getPost('language_id')));
			$data['content_group']= $filter->filter(trim($this->_request->getPost('content_group')));
			$data['status'] = $filter->filter(trim($this->_request->getPost('status')));
			
			
			//Get searched Languages
			
			$counter = 0;
			
			foreach( $data as  $key => $val ) 
			{
			
				if( $val != '' ) {
					
					$counter++;
					
				}
			}
			
			if( $counter > 0 ) {
			
				$result = $Definitions->SearchDefinitions($data);
			
			} else  {
			
				$mysession->Admin_EMessage = $translate->_('No_Search_Criteria');
			
				$result = $Definitions->GetAllDefinitions();
			
			} 
			
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
						$mysession->Admin_SMessage = $translate->_('Success_Add_Definition');
						$this->_redirect('/admin/definitions'); 	
					} else {
						$addErrorMessage[] = $translate->_('Err_Add_Definition');			
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
							$mysession->Admin_SMessage = $translate->_('Success_Edit_Definition');
							$this->_redirect('/admin/definitions'); 	
						} else {
							$editErrorMessage[] =  $translate->_('Err_Edit_Definition');
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
				$mysession->Admin_SMessage = $translate->_('Success_Delete_Definition');
			} else {
				$mysession->Admin_EMessage =  $translate->_('Err_Delete_Definition');
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
				$mysession->Admin_SMessage = $translate->_('Success_M_Delete_Definition');	
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Definition');			
			}	
			
		}	else {
		
			$mysession->Admin_EMessage = $translate->_('Err_M_Delete_Definition');				
		}
		$this->_redirect("/admin/definitions");	
   }
   
   /**
     * Function importAction
	 *
	 * This function is used to import language definition
	 *
     * Date Created: 2011-09-30
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 * @author Amar
     * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
     **/
   public function importAction()
   {
   		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$this->view->site_url = SITE_URL."admin/definitions";
		$this->view->import_action = SITE_URL."admin/definitions/import";		
		
		$Definitions = new Models_LanguageDefinitions();
		
		$Language = new Models_Language();
   		
		$filter = new Zend_Filter_StripTags();	
		
		$validator = new Zend_Validate_Regex(array('pattern' => '/^[a-zA-Z0-9_]+$/'));
		$db = Zend_Registry::get('Db_Adapter');
		
		//Get available languages
		$this->view->languages = $Language->getActiveLanguages();
		
		$request = $this->getRequest();
		
		if($request->isPost()){
			
			$allowedExtensions = array("txt");
			$maxsize = 2097152;
			$data['language_id']=(int)$filter->filter(trim($request->getPost('language_id')));
			
			$ErrorMessage = array();
			$dfile = array();
			
			//Check if file is uploaded or not
			if(count($_FILES) == 0)
			{
				$ErrorMessage[] = $translate->_('Err_Definition_File');
			}
			else
			{
				$dfile = $_FILES['definition_file'];
			}	
			
			//Check if file extension for supported extensions
			if(!in_array(substr($dfile['name'],strpos($dfile['name'],".")+1), $allowedExtensions))
			{
				$ErrorMessage[] = $translate->_('Err_Invalid_File_Ext');
			}
			
			//Check if file type for allowed file type
			if($dfile['type'] != "text/plain")
			{
				$ErrorMessage[] = $translate->_('Err_Invalid_File_Type');
			}
			
			//Check if file size for maximum size allowed
			if($dfile['size'] > $maxsize)
			{
				$ErrorMessage[] = $translate->_('Err_Max_File_Size');
			}
			
			if(count($ErrorMessage) == 0)
			{
				$sql = "Insert ignore into language_definitions (language_id, content_group, definition_key, definition_value, status) values ";
				
				$fh = fopen($dfile['tmp_name'], 'r');
				
				$arr_group = array("ADMIN","USER","DEFAULT","FB STORE","FB","REST");
				
				$current_group = "";
				
				$x = 0;
				while($str = fgets($fh))
				{
					$str = trim($str);
					
					if($str == "")
					{
						continue;
					}
					
					if($str != "" && strpos($str,"=") === false && in_array(strtoupper($str),$arr_group))
					{
						$current_group = strtoupper($str);
						if($current_group === "FB STORE")
						{
							$current_group = "FB";
						}
					}
					
					if($str != "" && $current_group != "")
					{
						if(strpos($str,"=") !== false)
						{
							$arr_str = explode("=",$str);
							
							if($validator->isValid(trim($arr_str[0])) === false)
							{
								continue;
							}
							else
							{
							
								$sql .= "(" . $data['language_id'] . ", '".$current_group."', '".mysql_real_escape_string(trim($arr_str[0]))."', '".mysql_real_escape_string(trim($arr_str[1]))."', 1),";	
								$x++;
							}
						}
					}
					else
					{
						continue;
					}
					
					
					if($x == 100)
					{
					
						$sql = rtrim($sql,",");
						
						$Definitions->executeQuery($sql);
						$x = 0;
						$sql = "Insert ignore into language_definitions (language_id, content_group, definition_key, definition_value, status) values ";
					}			
					
						
				}
				fclose($fh);
				@unlink($dfile['tmp_name']);
				
				if($x > 0)
				{
					
					$sql = rtrim($sql,",");
					
					$Definitions->executeQuery($sql);
				}
				
				if($current_group == "")
				{
					$ErrorMessage[] = $translate->_('Err_No_Group_In_File');
				}
				else
				{
					$this->view->SuccessMessage = $translate->_('Succ_Import_Language_Definitions');
				}

			}
			
			if(count($ErrorMessage) > 0)
			{
				$this->view->data = $data;
				$this->view->ErrorMessage = $ErrorMessage;
			}
				
			
		}
		
   }
   
   /**
     * Function exportAction
	 *
	 * This function is used to export language definition
	 *
     * Date Created: 2011-10-01
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 * @author Amar
     * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
     **/
   public function exportAction()
   {
   		$translate = Zend_Registry::get('Zend_Translate');
		
		$this->view->site_url = SITE_URL."admin/definitions";
		$this->view->import_action = SITE_URL."admin/definitions/export";		
		
		$Definitions = new Models_LanguageDefinitions();
		
		$Language = new Models_Language();
   		
		//Get available languages
		$this->view->languages = $Language->getActiveLanguages();
		
		$request = $this->getRequest();
		
		if($request->isPost()){
			
			$language_id=(int)trim($request->getPost('language_id'));
			
			$arr_definition = $Definitions->getDefinitionsByLanguageId($language_id);
			
			$str = "";
			$content_group = "";
			
			if(count($arr_definition) > 0)
			{
				
				for($i = 0; $i < count($arr_definition); $i++)
				{
					if($content_group != $arr_definition[$i]['content_group'])
					{
						$content_group = $arr_definition[$i]['content_group'];
						$str .= PHP_EOL . $content_group . PHP_EOL;	
					}
					
					$str .= $arr_definition[$i]['definition_key'] . " = " . $arr_definition[$i]['definition_value'] . PHP_EOL;	
				}
				
				//Download code for created string
				if($str != "")
				{
					header("Expires: 0");
					header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
					header("Cache-Control: no-store, no-cache, must-revalidate");
					header("Cache-Control: post-check=0, pre-check=0", false);
					header("Pragma: no-cache");
					header("Content-type: text/plain");
					// tell file size
					//header('Content-length: '.strlen($file));
					// set file name
					header('Content-disposition: attachment; filename=language.txt');
					echo $str;
					// Exit script. So that no useless data is output-ed.
					exit;
				}
			}
		}
   }
}
?>