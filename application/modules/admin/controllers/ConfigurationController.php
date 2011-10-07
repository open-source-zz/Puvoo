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
 * Configuration Controller.
 *
 * Admin_ConfigurationController  extends AdminCommonController. 
 * It controls Configuration for different groups on site for admin section
 *
 * Date Created: 2011-10-06
 *
 * @category	Puvoo
 * @package 	Admin_Controllers
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/ 
class Admin_ConfigurationController extends AdminCommonController
{

	/**
     * Function init
	 *
	 * This function is used for initialization. 
	 * You can also include necessary javascript files from here.
	 *
     * Date Created: 2011-10-06
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
        $this->view->JS_Files = array('admin/configuration.js','admin/AdminCommon.js');	
		Zend_Loader::loadClass('Models_Language');
	}
	
    /**
     * Function indexAction
	 *
	 * This function is used to list configuration on site and to do necessary actions
	 *
     * Date Created: 2011-10-06
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
		$this->view->site_url = SITE_URL."admin/configuration";
		$this->view->add_action = SITE_URL."admin/configuration/add";
		$this->view->edit_action = SITE_URL."admin/configuration/edit";
		$this->view->delete_action = SITE_URL."admin/configuration/delete";
		$this->view->delete_all_action = SITE_URL."admin/configuration/deleteall";
		$this->view->definition_action = SITE_URL."admin/configuration/definitionlist";
		
		//Create Object of Configuration model
		$Configuration = new Models_Configuration();
		
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
			$data['configuration_group_key']=$filter->filter(trim($this->_request->getPost('configuration_group_key')));
			$data['visible']=$filter->filter(trim($this->_request->getPost('visible')));
			//Get searched Languages
			$result = $Configuration->SearchCongfigurationGroup($data);
			
			$this->view->data = $data;
			
		} elseif($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $Configuration->getAllConfigurationGroup();
		} else 	{
			//Get all Configuration group
			$result = $Configuration->getAllConfigurationGroup();
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
		$this->view->search = $is_search;
   }
   
   /**
     * Function addAction
	 *
	 * This function is used to add configuration group
	 *
     * Date Created: 2011-10-06
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
		$this->view->site_url = SITE_URL."admin/configuration";
		$this->view->add_action = SITE_URL."admin/configuration/add";		
		
		$Configuration = new Models_Configuration();
		$home = new Models_AdminMaster();
   		
		//$this->view->languages = $language->GetMainCategory();
		
		$request = $this->getRequest();
		
		if($request->isPost()){
			
			$translate = Zend_Registry::get('Zend_Translate');
			
			$filter = new Zend_Filter_StripTags();
			
			$data['configuration_group_key']=$filter->filter(trim($this->_request->getPost('configuration_group_key')));
			$data['visible']=$filter->filter(trim($this->_request->getPost('visible')));
			
			$addErrorMessage = array();
			
			if($data['configuration_group_key'] == "") {
				$addErrorMessage[] = $translate->_('Err_Configuration_Group_Key');			
			}
			
			
			$this->view->data = $data;
			
			if(count($addErrorMessage) === 0)
			{
				$where = "1 = 1";
				if($home->ValidateTableField("configuration_group_key",$data['configuration_group_key'],"configuration_group",$where)) {
					
					$data['sort_order'] = $Configuration->getMaxOrder() + 1;
					
					if($Configuration->insertConfigurationGroup($data)) {
						$mysession->Admin_SMessage = $translate->_('Success_Add_Configuration_Group');
						$this->_redirect('/admin/configuration'); 	
					} else {
						$addErrorMessage[] =  $translate->_('Err_Add_Configuration_Group');	
					}
					
				} else {
					$this->view->addErrorMessage = array($translate->_('Err_Configuration_Group_Key_Exists'));	
				}
			} else {
				$this->view->addErrorMessage = $addErrorMessage;
			} 
		}
   }
   
   
   /**
     * Function editAction
	 *
	 * This function is used to update language data.
	 *
     * Date Created: 2011-10-06
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
		
		$Configuration = new Models_Configuration();
   		$home = new Models_AdminMaster();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$configuration_group_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($configuration_group_id > 0 && $configuration_group_id != "") {
			$this->view->records = $Configuration->getConfigurationGroupById($configuration_group_id);	
			$this->view->configuration_group_id =  $configuration_group_id;	
			
		} else {
			
			if($request->isPost()){
				
				$data['configuration_group_key']=$filter->filter(trim($this->_request->getPost('configuration_group_key')));
				$data['visible']=$filter->filter(trim($this->_request->getPost('visible')));
				$configuration_group_id = $filter->filter(trim($this->_request->getPost('configuration_group_id'))); 	
				
				
				$editErrorMessage = array();
				
				if($data['configuration_group_key'] == "") {
					$editErrorMessage[] = $translate->_('Err_Configuration_Group_Key');			
				}
				
				
				if(count($editErrorMessage) === 0)
				{
					$where = "configuration_group_id != ".$configuration_group_id;
					if($home->ValidateTableField("configuration_group_key",$data['configuration_group_key'],"configuration_group",$where)) {
						$where = "configuration_group_id = " . $configuration_group_id;
						if($Configuration->updateConfigurationGroup($data,$where)) {
							$mysession->Admin_SMessage = $translate->_('Success_Edit_Configuration_Group');
							$this->_redirect('/admin/configuration'); 	
						} else {
							$editErrorMessage[] = $translate->_('Err_Edit_Configuration_Group');
						}
						
					} else {
					
						$this->view->editErrorMessage = array($translate->_('Err_Configuration_Group_Key_Exists'));	
					}
				} else {
					$this->view->editErrorMessage = $editErrorMessage;
				} 
			
				$this->view->records = $data; 
				$this->view->configuration_group_id =  $configuration_group_id;	
				$this->view->editErrorMessage = $editErrorMessage;
				
			} else {
			
				$this->_redirect("/admin/configuration");
			}
		
		}
		
   }
   
   /**
     * Function deleteAction
	 *
	 * This function is used to delete the configuration group
	 *
     * Date Created: 2011-10-06
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
		
		$Configuration = new Models_Configuration();
   		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$configuration_group_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($configuration_group_id > 0 && $configuration_group_id != "") {
			if($Configuration->deleteConfigurationGroup($configuration_group_id)) {
				$mysession->Admin_SMessage = $translate->_('Success_Delete_Configuration_Group');
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Configuration_Group');
			}		
		} 
		$this->_redirect("/admin/configuration");		
   }  
   
   
   /**
     * Function deleteallAction
	 *
	 * This function is used to delete all selected configuration groups.
	 *
     * Date Created: 2011-10-06
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
		
		$Configuration = new Models_Configuration();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		
   		if(isset($_POST["id"])) {
		
			$configuration_group_ids = $this->_request->getPost('id'); 
			$ids = implode($configuration_group_ids,",");
			
			if($Configuration->deleteMultipleConfigurationGroup($ids)) {
				$mysession->Admin_SMessage = $translate->_('Success_M_Delete_Configuration_Group');	
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Configuration_Group');				
			}	
			
		}else{
		
			$mysession->Admin_EMessage = $translate->_('Err_M_Delete_Configuration_Group');				
		}
		$this->_redirect("/admin/configuration");	
   }
   
   
   /**
     * Function definitionlistAction
	 *
	 * This function is used to list configuration definitins on site and to do necessary actions
	 *
     * Date Created: 2011-10-06
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   function definitionlistAction() 
   {
        global $mysession,$arr_pagesize;
		$this->view->configuration_group_url = SITE_URL."admin/configuration";
		$this->view->site_url = SITE_URL."admin/configuration/dedinitionlist";
		$this->view->add_action = SITE_URL."admin/configuration/definitionadd";
		$this->view->edit_action = SITE_URL."admin/configuration/definitionedit";
		$this->view->delete_action = SITE_URL."admin/configuration/definitiondelete";
		$this->view->delete_all_action = SITE_URL."admin/configuration/definitiondeleteall";
		
		
		//Create Object of Configuration model
		$Configuration = new Models_Configuration();
		
		//set current page number
		$page_no = 1;
		
		//set no. of records to display on page
		$pagesize = $mysession->pagesize;
		
		//set search param
		$is_search = "";
		
		//set configuration group id
		$configuration_group_id = 0;
		
		//Get Request
		$request = $this->getRequest();
		
		
		if($request->getParam('id') != "")
		{
		
			$configuration_group_id = 	(int)$request->getParam('id');
		}
		
		if($request->isPost()){
			$page_no = $request->getPost('page_no');
			$pagesize = $request->getPost('pagesize');
			$mysession->pagesize = $pagesize;
			$is_search = $request->getPost('is_search');
			$configuration_group_id = (int)$filter->filter(trim($request->getPost('configuration_group_id')));
		}
		
		if($is_search == "1") {
			$filter = new Zend_Filter_StripTags();
			$data['configuration_key']=$filter->filter(trim($this->_request->getPost('configuration_key')));
			$data['configuration_value']=$filter->filter(trim($this->_request->getPost('configuration_value')));
			$data['configuration_group_id']= $configuration_group_id;
			//Get searched Languages
			$result = $Configuration->SearchConfiguration($data);
			
			$this->view->data = $data;
			
		} elseif($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $Configuration->getAllConfigurationForGroup($configuration_group_id);
		} else 	{
			//Get all Configuration group
			$result = $Configuration->getAllConfigurationForGroup($configuration_group_id);
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
		$this->view->search = $is_search;
		$this->view->configuration_group_id = $configuration_group_id;
   }
   
   
   /**
     * Function definitionaddAction
	 *
	 * This function is used to add configuration for group
	 *
     * Date Created: 2011-10-06
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   public function definitionaddAction()
   {
   		global $mysession;
		$this->view->site_url = SITE_URL."admin/configuration/definitionlist";
		$this->view->add_action = SITE_URL."admin/configuration/definitionadd";		
		
		$Configuration = new Models_Configuration();
		$home = new Models_AdminMaster();
   		
		//$this->view->languages = $language->GetMainCategory();
		$configuration_group_id = 0;
		
		
		$request = $this->getRequest();
		
		if($request->getParam('id') != "")
		{
			$configuration_group_id = 	(int)$request->getParam('id');
		}
		
		if($request->isPost()){
			
			$translate = Zend_Registry::get('Zend_Translate');
			
			$filter = new Zend_Filter_StripTags();
			
			$data['configuration_key']=$filter->filter(trim($this->_request->getPost('configuration_key')));
			$data['configuration_value']=$filter->filter(trim($this->_request->getPost('configuration_value')));
			$data['configuration_group_id']=(int)$filter->filter(trim($this->_request->getPost('configuration_group_id')));
			$data['last_modified'] = new Zend_Db_Expr('NOW()');
			$data['date_added'] = new Zend_Db_Expr('NOW()');
			
			$configuration_group_id = $data['configuration_group_id'];
			
			$addErrorMessage = array();
			
			if($data['configuration_key'] == "") {
				$addErrorMessage[] = $translate->_('Err_Configuration_Key');			
			}
			
			if($data['configuration_value'] == "") {
				$addErrorMessage[] = $translate->_('Err_Configuration_Value');			
			}
			
			$this->view->data = $data;
			
			if(count($addErrorMessage) === 0)
			{
				$where = "configuration_group_id = " . $configuration_group_id;
				if($home->ValidateTableField("configuration_key",$data['configuration_key'],"configuration",$where)) {
					
					$data['sort_order'] = $Configuration->getMaxConfigurationOrder() + 1;
					
					if($Configuration->insertConfiguration($data)) {
						$mysession->Admin_SMessage = $translate->_('Success_Add_Configuration_Definition');
						$this->_redirect('/admin/configuration/definitionlist/id/' . $configuration_group_id); 	
					} else {
						$addErrorMessage[] =  $translate->_('Err_Add_Configuration_Definition');	
					}
					
				} else {
					$this->view->addErrorMessage = array($translate->_('Err_Configuration_Key_Exists'));	
				}
			} else {
				$this->view->addErrorMessage = $addErrorMessage;
			} 
		}
		
		$this->view->configuration_group_id = $configuration_group_id;
   }
   
   
   
   /**
     * Function definitioneditAction
	 *
	 * This function is used to update configuration data.
	 *
     * Date Created: 2011-10-06
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function definitioneditAction()
   {
   		global $mysession;
		
		$this->view->site_url = SITE_URL."admin/configuration/definitionlist";
		$this->view->edit_action = SITE_URL."admin/configuration/definitionedit";	
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$Configuration = new Models_Configuration();
   		$home = new Models_AdminMaster();
		
		$configuration_group_id = 0;
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$configuration_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		
		if($configuration_id > 0 && $configuration_id != "") {
			$this->view->records = $Configuration->getConfigurationById($configuration_id);	
			$this->view->configuration_id = $configuration_id;	
			$configuration_group_id = $this->view->records['configuration_group_id'];
			$this->view->configuration_group_id = $configuration_group_id;
			
		} else {
			
			if($request->isPost()){
				
				$data['configuration_key']=$filter->filter(trim($this->_request->getPost('configuration_key')));
				$data['configuration_value']=$filter->filter(trim($this->_request->getPost('configuration_value')));
				$data['last_modified'] = new Zend_Db_Expr('NOW()');
				
				$configuration_id = (int)$filter->filter(trim($this->_request->getPost('configuration_id'))); 	
				$configuration_group_id = (int)$filter->filter(trim($this->_request->getPost('configuration_group_id'))); 	
				
				$editErrorMessage = array();
				
				if($data['configuration_key'] == "") {
					$editErrorMessage[] = $translate->_('Err_Configuration_Key');			
				}
				
				if($data['configuration_value'] == "") {
					$editErrorMessage[] = $translate->_('Err_Configuration_Value');			
				}
				
				if(count($editErrorMessage) === 0)
				{
				
					$where = "configuration_id != ".$configuration_id;
					if($home->ValidateTableField("configuration_key",$data['configuration_key'],"configuration",$where)) {
						$where = "configuration_id = " . $configuration_id;
						if($Configuration->updateConfiguration($data,$where)) {
							$mysession->Admin_SMessage = $translate->_('Success_Edit_Configuration_Group');
							$this->_redirect('/admin/configuration/definitionlist/id/' . $configuration_group_id); 	
						} else {
							$editErrorMessage[] = $translate->_('Err_Edit_Configuration_Group');
						}
						
					} else {
						
						$editErrorMessage[] = $translate->_('Err_Configuration_Group_Key_Exists');	
					}
				} else {
				
					$this->view->editErrorMessage = $editErrorMessage;
				} 
			
				$this->view->records = $data; 
				$this->view->configuration_id =  $configuration_id;	
				$this->view->configuration_group_id =  $configuration_group_id;	
				$this->view->editErrorMessage = $editErrorMessage;
				
			} else {
			
				$this->_redirect("/admin/configuration/");
			}
		
		}
		
   }
  
  
	/**
     * Function deleteAction
	 *
	 * This function is used to delete the configuration group
	 *
     * Date Created: 2011-10-06
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function definitiondeleteAction()
   {
   		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$Configuration = new Models_Configuration();
   		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$configuration_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		$configuration_group_id = $filter->filter(trim($this->_request->getPost('configuration_group_id'))); 	
		
		if($configuration_id > 0 && $configuration_id != "") {
			if($Configuration->deleteConfiguration($configuration_id)) {
				$mysession->Admin_SMessage = $translate->_('Success_Delete_Configuration');
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Configuration');
			}		
		} 
		$this->_redirect("/admin/configuration/definitionlist/id/" . $configuration_group_id);		
   }  
   
   
   /**
     * Function definitiondeleteallAction
	 *
	 * This function is used to delete all selected configuration groups.
	 *
     * Date Created: 2011-10-06
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function definitiondeleteallAction()
   {
   		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$Configuration = new Models_Configuration();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	

		$configuration_group_id = $filter->filter(trim($this->_request->getPost('configuration_group_id'))); 	
		
   		if(isset($_POST["id"])) {
		
			$configuration_ids = $this->_request->getPost('id'); 
			$ids = implode($configuration_ids,",");
			
			if($Configuration->deleteMultipleConfiguration($ids)) {
				$mysession->Admin_SMessage = $translate->_('Success_M_Delete_Configuration');	
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Configuration');				
			}	
			
		}else{
		
			$mysession->Admin_EMessage = $translate->_('Err_M_Delete_Configuration');				
		}
		$this->_redirect("/admin/configuration/definitionlist/id/" . $configuration_group_id);		
   }
   
}
?>