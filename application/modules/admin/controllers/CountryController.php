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
 * Admin_CountryController  extends AdminCommonController. 
 * It controls country management on admin section
 *
 * Date Created: 2011-09-26
 *
 * @currency	Puvoo
 * @package 	Admin_Controllers
 * @author      Amar 
 *  
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/  
 class Admin_CountryController extends AdminCommonController
{

	/**
     * Function init
	 *
	 * This function is used for initialization. 
	 * You can also include necessary javascript files from here.
	 *
     * Date Created: 2011-09-26
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
        $this->view->JS_Files = array('admin/country.js','admin/AdminCommon.js');			
		Zend_Loader::loadClass('Models_Country');
		Zend_Loader::loadClass('Models_AdminMaster');
        
    }
	
    /**
     * Function indexAction
	 *
	 * This function is used to display country list.
	 *
     * Date Created: 2011-09-26
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
		$this->view->site_url = SITE_URL."admin/country";
		$this->view->add_action = SITE_URL."admin/country/add";
		$this->view->edit_action = SITE_URL."admin/country/edit";
		$this->view->delete_action = SITE_URL."admin/country/delete";
		$this->view->delete_all_action = SITE_URL."admin/country/deleteall";
		$this->view->state_action = SITE_URL."admin/country/statelist";
		
		//Create Object of Country model
		$Country = new Models_Country();
		
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
			$data['country_name']=$filter->filter(trim($this->_request->getPost('country_name'))); 
			$data['country_iso2']=$filter->filter(trim($this->_request->getPost('country_iso2'))); 
			$data['country_iso3']=$filter->filter(trim($this->_request->getPost('country_iso3'))); 	
			
			$this->view->data = $data;
			//Get search Country
			
			$counter = 0;
			
			foreach( $data as  $key => $val ) 
			{
			
				if( $val != '' ) {
					
					$counter++;
					
				}
			}
			
			if( $counter > 0 ) {
			
				$result = $Country->SearchCountry($data);
				
			} else  {
			
				$mysession->Admin_EMessage = $translate->_('No_Search_Criteria');
			
				$result = $Country->GetAllCountries();
			
			} 
			
		} elseif($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $Country->GetAllCountries();
						
		} else 	{
			//Get all Countries
			$result = $Country->GetAllCountries();
			
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
		$this->view->is_search	= $is_search;
		
   }
   
   /**
     * Function addAction
	 *
	 * This function is used to add currency
	 *
     * Date Created: 2011-09-26
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
		$this->view->site_url = SITE_URL."admin/country";
		$this->view->add_action = SITE_URL."admin/country/add";		
		
		$Country = new Models_Country();
		$home = new Models_AdminMaster();
		
		$request = $this->getRequest();
		
		if($request->isPost()){
			
			$translate = Zend_Registry::get('Zend_Translate');
			
			$filter = new Zend_Filter_StripTags();	
			$data['country_name']=$filter->filter(trim($this->_request->getPost('country_name'))); 
			$data['country_iso2']=strtoupper($filter->filter(trim($this->_request->getPost('country_iso2')))); 
			$data['country_iso3']=strtoupper($filter->filter(trim($this->_request->getPost('country_iso3')))); 
			 	
			$addErrorMessage = array();
			if($data['country_name'] == "") {
				$addErrorMessage[] = $translate->_('Err_Country_Name');			
			}
			if($data['country_iso2'] == "") {
				$addErrorMessage[] = $translate->_('Err_ISO2_Code');			
			}
			if($data['country_iso3'] == "") {
				$addErrorMessage[] = $translate->_('Err_ISO3_Code');			
			}
			
			$this->view->data = $data;
			
			if(count($addErrorMessage) === 0)
			{
				$where = "1 = 1";
				if($home->ValidateTableField("country_name",$data['country_name'],"country_master",$where) 
					&& $home->ValidateTableField("country_iso2",$data['country_iso2'],"country_master",$where) 
					&& $home->ValidateTableField("country_iso3",$data['country_iso3'],"country_master",$where)) 
				{
					if($Country->insertCountry($data)) {
						$mysession->Admin_SMessage = $translate->_('Success_Add_Country');
						$this->_redirect('/admin/country'); 	
					} else {
						$addErrorMessage[] = $translate->_('Err_Add_Country'); 
					}
					
				} else {
				
					$this->view->addErrorMessage = array($translate->_('Err_Country_Exists'));	
				}
			} 
			
			$this->view->addErrorMessage = $addErrorMessage;
		}
   }
   
   
   /**
     * Function editAction
	 *
	 * This function is used to update country data.
	 *
     * Date Created: 2011-09-26
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
		
		$Country = new Models_Country();
   		$home = new Models_AdminMaster();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$country_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($country_id > 0 && $country_id != "") {
			$this->view->records = $Country->GetCountryById($country_id);	
			$this->view->country_id =  $country_id;	
			
		} else {
			
			if($request->isPost()){
				
				$data['country_name']=$filter->filter(trim($this->_request->getPost('country_name'))); 
				$data['country_iso2']=strtoupper($filter->filter(trim($this->_request->getPost('country_iso2')))); 
				$data['country_iso3']=strtoupper($filter->filter(trim($this->_request->getPost('country_iso3')))); 
				$country_id = (int)$filter->filter(trim($this->_request->getPost('country_id'))); 
				
				$editErrorMessage = array();
				if($data['country_name'] == "") {
					$editErrorMessage[] = $translate->_('Err_Country_Name');			
				}
				if($data['country_iso2'] == "") {
					$editErrorMessage[] = $translate->_('Err_ISO2_Code');			
				}
				if($data['country_iso3'] == "") {
					$editErrorMessage[] = $translate->_('Err_ISO3_Code');			
				}
				
				$where = "country_id != ".$country_id;
				
				if( count($editErrorMessage) == 0 || $editErrorMessage == '' ){
					if($home->ValidateTableField("country_name",$data['country_name'],"country_master",$where) 
						&& $home->ValidateTableField("country_iso2",$data['country_iso2'],"country_master",$where) 
						&& $home->ValidateTableField("country_iso3",$data['country_iso3'],"country_master",$where))
					{
						
						$where = "country_id = ".$country_id;
						if($Country->updateCountry($data,$where)) {
							$mysession->Admin_SMessage = $translate->_('Success_Edit_Country');
							$this->_redirect('/admin/country'); 	
						} else {
							$editErrorMessage[] =  $translate->_('Err_Edit_Country'); 
						}
						 
					} else {			
						
						$editErrorMessage[] = $translate->_('Err_Country_Name_Exists');	
					}
				}				
				$this->view->records = $data;	
				$this->view->country_id =  $country_id;	
				$this->view->editErrorMessage = $editErrorMessage;
				
			} else {
			
				$this->_redirect("/admin/country");
			}
		
		}
		
   }
   
   /**
     * Function deleteAction
	 *
	 * This function is used to delete the country
	 *
     * Date Created: 2011-09-26
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
		$Country = new Models_Country();
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$country_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($country_id > 0 && $country_id != "") {
			if($Country->deleteCountry($country_id)) {
				$mysession->Admin_SMessage = $translate->_('Success_Delete_Country');
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Country');
			}		
		} 
		$this->_redirect("/admin/country");		
   }  
   
   
   /**
     * Function deleteallAction
	 *
	 * This function is used to delete all selected countries.
	 *
     * Date Created: 2011-09-26
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
		$Country = new Models_Country();
		$request = $this->getRequest();
		$filter = new Zend_Filter_StripTags();	
		
   		if(isset($_POST["id"])) {
		
			$country_ids = $this->_request->getPost('id'); 
			$ids = implode($country_ids,",");
			
			if($Country->deletemultipleCountry($ids)) {
				$mysession->Admin_SMessage = $translate->_('Success_M_Delete_Country');	
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Country');	
			}	
			
		}	else {
		
			$mysession->Admin_EMessage = $translate->_('Err_M_Delete_Country');				
		}
		$this->_redirect("/admin/country");	
   }
   
   
	/**
	 * Function statelistAction
	 *
	 * This function is used to list all state by country wise.
	 *
	 * Date Created: 2011-09-28
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	 
	public function statelistAction()
	{
		global $mysession,$arr_pagesize;
		$this->view->country_url = SITE_URL."admin/country";
		$this->view->site_url = SITE_URL."admin/country/statelist";
		$this->view->add_action = SITE_URL."admin/country/stateadd";
		$this->view->edit_action = SITE_URL."admin/country/statedit";
		$this->view->delete_action = SITE_URL."admin/country/statedelete";
		$this->view->delete_all_action = SITE_URL."admin/country/statedeleteall";
		
		$translate = Zend_Registry::get('Zend_Translate');
		$Country = new Models_Country();
		$request = $this->getRequest();
		$filter = new Zend_Filter_StripTags();	
		
		if($request->isPost()){ 
			$temp_id = $this->_request->getPost('hidden_primary_country_id'); 
		}
		
		$country_id = $filter->filter(trim($this->_request->getParam('cid'))); 
		
		$this->view->country_data = $Country->getCountryById($country_id);
		
		if( $country_id > 0 ) {
			
			$country_data = $Country->getCountryById($country_id);
			
			if($country_data != NULL || $country_data != "" ) {
					
					$page_no = 1;
					$pagesize = $mysession->pagesize2;
					$is_search = "";
					$request = $this->getRequest();
					
					if($request->isPost()){
						$page_no = $request->getPost('page_no');
						$pagesize = $request->getPost('pagesize');
						$mysession->pagesize2 = $pagesize;
						$is_search = $request->getPost('is_search');
					}
					
					if($is_search == "1") {
						$filter = new Zend_Filter_StripTags();	
						
						$data["state_name"] = $filter->filter(trim($this->_request->getPost('state_name'))); 
						
						if( $data["state_name"] != '' ) {
						
							$result = $Country->searchState($data, $country_id);
						
						} else  {
			
							$mysession->Admin_EMessage = $translate->_('No_Search_Criteria');
						
							$result = $Country->searchState($data, $country_id);
						
						} 
						
					} elseif($is_search == "0") {
					
						$page_no = 1;
						$result = $Country->getAllStateByContry($country_id);
						
					} else 	{
					
						$result = $Country->getAllStateByContry($country_id);
					}		
					
					$this->view->country_id = $country_id;
					
					// Success Message
					$this->view->Admin_SMessage = $mysession->Admin_SMessage;
					$this->view->Admin_EMessage = $mysession->Admin_EMessage;
					
					$mysession->Admin_SMessage = "";
					$mysession->Admin_EMessage = "";
					
					//Set Pagination
					$paginator2 = Zend_Paginator::factory($result);
					$paginator2->setItemCountPerPage($pagesize);
					$paginator2->setCurrentPageNumber($page_no);
					
					//Set View variables
					$this->view->pagesize = $pagesize;
					$this->view->page_no = $page_no;
					$this->view->arr_pagesize = $arr_pagesize;
					$this->view->paginator = $paginator2;
					$this->view->records = $paginator2->getCurrentItems();
					$this->view->is_search	= $is_search;
					
				} else {
				
					$mysession->Admin_EMessage = $translate->_('Err_Country_Notfound');
					$this->_redirect('/admin/country'); 
					
				}
		
		} else {
		
			$mysession->Admin_EMessage = $translate->_('Err_Country');
			$this->_redirect('/admin/country'); 
		}
			
	}	
	
	/**
	 * Function stateaddAction
	 *
	 * This function is used to add state.
	 *
	 * Date Created: 2011-09-28
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	function stateaddAction()
	{
		global $mysession;
		$this->view->site_url = SITE_URL."admin/country/statelist";
		$this->view->add_action = SITE_URL."admin/country/stateadd";		
		
		$translate = Zend_Registry::get('Zend_Translate');
		$filter = new Zend_Filter_StripTags();	
		$Country = new Models_Country();
		$home = new Models_AdminMaster();
		
		$request = $this->getRequest();
		
		$country_id = $filter->filter(trim($this->_request->getParam('cid'))); 
		$this->view->country_data = $Country->getCountryById($country_id);
		if( $country_id > 0 ) {
		
			if( $request->isPost() ){
				
				$data['state_name']=$filter->filter(trim($this->_request->getPost('state_name'))); 
				$data['country_id']= $filter->filter(trim($this->_request->getPost('country_id'))); 
						
				$addErrorMessage = array();
					
				if($data['state_name'] == "") {
					$addErrorMessage[] = $translate->_('Err_State_Name');			
				}
				
				$this->view->data = $data;
				
				if(count($addErrorMessage) === 0)
				{
					$where = "country_id = ".$data['country_id'];
					
					if($home->ValidateTableField("state_name",$data['state_name'],"state_master",$where)) 
					{
						if($Country->insertState($data)) {
							
							$mysession->Admin_SMessage = $translate->_('Success_Add_State');
							$this->_redirect('/admin/country/statelist/cid/'.$data['country_id']);
								
						} else {
						
							$addErrorMessage[] = $translate->_('Err_Add_Country'); 
						}
						
					} else {
					
						$this->view->addErrorMessage = array($translate->_('Err_State_Exists'));	
					}
				} else {
					$this->view->addErrorMessage = $addErrorMessage;
				}
			}
			$this->view->country_id	= $country_id;
			
		} else {
		
			$this->_redirect('/admin/country');
		}
	}
	
	/**
	 * Function stateditAction
	 *
	 * This function is used to edit state.
	 *
	 * Date Created: 2011-09-28
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	function stateditAction()
	{
	
		global $mysession;
		$this->view->site_url = SITE_URL."admin/country/statelist";
		$this->view->edit_action = SITE_URL."admin/country/statedit";	
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$Country = new Models_Country();
   		$home = new Models_AdminMaster();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$state_id = $filter->filter(trim($this->_request->getPost('hidden_primary_state_id'))); 
		$country_id = $filter->filter(trim($this->_request->getParam('cid'))); 
		
		$this->view->country_data = $Country->getCountryById($country_id);
		
		if($state_id > 0 && $state_id != "") {
			
			$this->view->records = $Country->GetStateById($state_id);	
			$this->view->state_id =  $state_id;	
			$this->view->country_id = $country_id;
			
		} else {
			
			if($request->isPost()){
				
				$data['state_id']=$filter->filter(trim($this->_request->getPost('state_id')));
				$data['state_name']=$filter->filter(trim($this->_request->getPost('state_name'))); 
				$country_id = $filter->filter(trim($this->_request->getPost('country_id'))); 
				
				
				$editErrorMessage = array();
				if($data['state_name'] == "") {
					$editErrorMessage[] = $translate->_('Err_State_Name');			
				}
				
				if( count($editErrorMessage) == 0 || $editErrorMessage == '' ){
					
					$where = "country_id = '".$country_id."' and state_id != ".$data['state_id'];
					
					if($home->ValidateTableField("state_name",$data['state_name'],"state_master",$where) )
					{
						$where = "state_id = ".$data['state_id'];
						if($Country->updateState($data,$where)) {
							$mysession->Admin_SMessage = $translate->_('Success_Edit_State');
							$this->_redirect('/admin/country/statelist/cid/'.$country_id); 	
						} else {
							$editErrorMessage[] =  $translate->_('Err_Edit_State'); 
						}
						 
					} else {			
						
						$editErrorMessage[] = $translate->_('Err_State_Exists');	
					}
				}				
				$this->view->records = $data;	
				$this->view->state_id = $data['state_id'];	
				$this->view->country_id = $country_id;
				$this->view->editErrorMessage = $editErrorMessage;
				
			}
		}
	}
	
	/**
	 * Function statedeleteAction
	 *
	 * This function is used to delete state.
	 *
	 * Date Created: 2011-09-28
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	function statedeleteAction()
	{
		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');		
		$Country = new Models_Country();
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$state_id = $filter->filter(trim($this->_request->getPost('hidden_primary_state_id'))); 
		$country_id = $filter->filter(trim($this->_request->getPost('hidden_primary_country_id'))); 	
		
		if($state_id > 0 && $state_id != "") {
			if($Country->deleteState($state_id)) {
				$mysession->Admin_SMessage = $translate->_('Success_Delete_State');
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_State');
			}		
		} 
		$this->_redirect("/admin/country/statelist/cid/".$country_id);		
		
	}
    
	/**
	 * Function statedeleteAction
	 *
	 * This function is used to delete nultiple state.
	 *
	 * Date Created: 2011-09-28
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	function statedeleteallAction()
	{
		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		$Country = new Models_Country();
		$request = $this->getRequest();
		$filter = new Zend_Filter_StripTags();	
		
		$country_id = $filter->filter(trim($this->_request->getPost('hidden_primary_country_id'))); 
   		if(isset($_POST["sid"])) {
		
			$state_ids = $this->_request->getPost('sid'); 
			$ids = implode($state_ids,",");
			
			if($Country->deletemultipleState($ids)) {
				$mysession->Admin_SMessage = $translate->_('Success_M_Delete_State');	
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_State');	
			}	
			
		}	else {
		
			$mysession->Admin_EMessage = $translate->_('Err_M_Delete_State');				
		}
		$this->_redirect("/admin/country/statelist/cid/".$country_id);		
	}
	
}
?>