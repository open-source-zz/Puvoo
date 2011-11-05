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
 * Admin Languages Controller.
 *
 * Admin_LanguagesController  extends AdminCommonController. 
 * It controls languages on admin section
 *
 * Date Created: 2011-09-16
 *
 * @category	Puvoo
 * @package 	Admin_Controllers
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/ 
 class Admin_LanguagesController extends AdminCommonController
{

	/**
     * Function init
	 *
	 * This function is used for initialization. 
	 * You can also include necessary javascript files from here.
	 *
     * Date Created: 2011-09-16
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
        $this->view->JS_Files = array('admin/languages.js','admin/AdminCommon.js');	
		Zend_Loader::loadClass('Models_Language');
	}
	
    /**
     * Function indexAction
	 *
	 * This function is used to list languages on site and to do necessary actions
	 *
     * Date Created: 2011-09-16
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
		$this->view->site_url = SITE_URL."admin/languages";
		$this->view->add_action = SITE_URL."admin/languages/add";
		$this->view->edit_action = SITE_URL."admin/languages/edit";
		$this->view->delete_action = SITE_URL."admin/languages/delete";
		$this->view->delete_all_action = SITE_URL."admin/languages/deleteall";
		
		//Create Object of Langage model
		$language = new Models_Language();
		$Country = new Models_Country();
		
		//set current page number
		$page_no = 1;
		
		//set no. of records to display on page
		$pagesize = $mysession->pagesize;
		
		//set search param
		$is_search = "";
		
		$this->view->country = $Country->GetAllCountries();
		
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
			$data['name']=$filter->filter(trim($this->_request->getPost('name'))); 	
			$data['status']=$filter->filter(trim($this->_request->getPost('status'))); 			
			//Get searched Languages
			$result = $language->SearchLanguages($data);
			
			$this->view->data = $data;
			
		} elseif($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $language->GetAllLanguages();
						
		} else 	{
			//Get all Categories
			$result = $language->GetAllLanguages();
			
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
	 * This function is used to add language
	 *
     * Date Created: 2011-09-17
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
		$this->view->site_url = SITE_URL."admin/languages";
		$this->view->add_action = SITE_URL."admin/languages/add";		
		
		$Language = new Models_Language();
		$home = new Models_AdminMaster();
   		$Country = new Models_Country();
		$Courrency = new Models_Currency();
		
		$this->view->country = $Country->GetAllCountries();
		$this->view->currency = $Courrency->GetAllCurrency();
		
		$request = $this->getRequest();
		
		if($request->isPost()){
			
			$translate = Zend_Registry::get('Zend_Translate');
			
			$filter = new Zend_Filter_StripTags();
			
			$data['name']=$filter->filter(trim($this->_request->getPost('name')));
			$data['code']=$filter->filter(trim($this->_request->getPost('code')));
			$data['charset']=$filter->filter(trim($this->_request->getPost('charset')));
			$data['date_format_short']=$filter->filter(trim($this->_request->getPost('date_format_short')));
			$data['date_format_long']=$filter->filter(trim($this->_request->getPost('date_format_long')));
			$data['time_format']=$filter->filter(trim($this->_request->getPost('time_format')));
			$data['text_direction']=$filter->filter(trim($this->_request->getPost('text_direction')));
			$data['numeric_separator_decimal']=$filter->filter(trim($this->_request->getPost('numeric_separator_decimal')));
			$data['numeric_separator_thousands']=$filter->filter(trim($this->_request->getPost('numeric_separator_thousands')));
			$data['country_id']=$filter->filter(trim($this->_request->getPost('country_id')));
			$data['currency_id']=$filter->filter(trim($this->_request->getPost('currency_id')));
			$data['vat']=$filter->filter(trim($this->_request->getPost('vat')));
			$data['is_default']=$filter->filter(trim($this->_request->getPost('is_default')));
			$data['status']=$filter->filter(trim($this->_request->getPost('status')));
			
			
			$addErrorMessage = array();
			
			if($data['name'] == "") {
				$addErrorMessage[] = $translate->_('Err_Language_Name');			
			}
			
			if($data['code'] == "") {
				$addErrorMessage[] = $translate->_('Err_Language_Code');			
			}
			
			if($data['charset'] == "") {
				$addErrorMessage[] = $translate->_('Err_Language_Charset');			
			}
			
			if($data['numeric_separator_decimal'] == "") {
				$addErrorMessage[] = $translate->_('Err_Language_Decimal');			
			}
			
			if($data['numeric_separator_thousands'] == "") {
				$addErrorMessage[] = $translate->_('Err_Language_Thousands');			
			}
			
			if($data['country_id'] == "") {
				$addErrorMessage[] = $translate->_('Err_Language_Country');			
			}
			
			if($data['currency_id'] == "") {
				$addErrorMessage[] = $translate->_('Err_Language_Currency');			
			}
			if($data['vat'] == "") {
				$addErrorMessage[] = $translate->_('Err_Language_Vat');			
			}
			
			$this->view->data = $data;
			
			if(count($addErrorMessage) === 0)
			{
				$where = "1 = 1";
				if($home->ValidateTableField("code",$data['code'],"language_master",$where)) {
					$data['locale'] = $data['code'] . "," . $data['charset'] . "," . $data["code"] . "," . strtolower($data["name"]);
					$data['sort_order'] = $Language->getMaxOrder() + 1;
					
					if($Language->insertLanguage($data)) {
						$mysession->Admin_SMessage = $translate->_('Success_Add_Language');
						$this->_redirect('/admin/languages'); 	
					} else {
						$addErrorMessage[] =  $translate->_('Err_Add_Language');	
					}
					
				} else {
				
					$this->view->addErrorMessage = array($translate->_('Err_Language_Code_Exists'));	
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
     * Date Created: 2011-09-17
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
		
		$Language = new Models_Language();
   		$home = new Models_AdminMaster();
		$Country = new Models_Country();
		$Courrency = new Models_Currency();
		
		$this->view->country = $Country->GetAllCountries();
		$this->view->currency = $Courrency->GetAllCurrency();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$language_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($language_id > 0 && $language_id != "") {
			$this->view->records = $Language->getLanguageById($language_id);	
			$this->view->language_id =  $language_id;	
			
		} else {
			
			if($request->isPost()){
				
				$data['name']=$filter->filter(trim($this->_request->getPost('name')));
				$data['code']=$filter->filter(trim($this->_request->getPost('code')));
				$data['charset']=$filter->filter(trim($this->_request->getPost('charset')));
				$data['date_format_short']=$filter->filter(trim($this->_request->getPost('date_format_short')));
				$data['date_format_long']=$filter->filter(trim($this->_request->getPost('date_format_long')));
				$data['time_format']=$filter->filter(trim($this->_request->getPost('time_format')));
				$data['text_direction']=$filter->filter(trim($this->_request->getPost('text_direction')));
				$data['numeric_separator_decimal']=$filter->filter(trim($this->_request->getPost('numeric_separator_decimal')));
				$data['numeric_separator_thousands']=$filter->filter(trim($this->_request->getPost('numeric_separator_thousands')));
				$data['country_id']=$filter->filter(trim($this->_request->getPost('country_id')));
				$data['currency_id']=$filter->filter(trim($this->_request->getPost('currency_id')));
				$data['vat']=$filter->filter(trim($this->_request->getPost('vat')));
				$data['is_default']=$filter->filter(trim($this->_request->getPost('is_default')));
				$data['status']=$filter->filter(trim($this->_request->getPost('status')));
				$language_id = $filter->filter(trim($this->_request->getPost('language_id'))); 	
				
				
				$editErrorMessage = array();
				
				if($data['name'] == "") {
					$editErrorMessage[] = $translate->_('Err_Language_Name');			
				}
				
				if($data['code'] == "") {
					$editErrorMessage[] = $translate->_('Err_Language_Code');			
				}
				
				if($data['charset'] == "") {
					$editErrorMessage[] = $translate->_('Err_Language_Charset');			
				}
				
				if($data['numeric_separator_decimal'] == "") {
					$editErrorMessage[] = $translate->_('Err_Language_Decimal');			
				}
				
				if($data['numeric_separator_thousands'] == "") {
					$editErrorMessage[] = $translate->_('Err_Language_Thousands');			
				}
				
				if($data['country_id'] == "") {
					$editErrorMessage[] = $translate->_('Err_Language_Country');			
				}
				
				if($data['currency_id'] == "") {
					$editErrorMessage[] = $translate->_('Err_Language_Currency');			
				}
				if($data['vat'] == "") {
					$editErrorMessage[] = $translate->_('Err_Language_Vat');			
				}
				
				if(count($editErrorMessage) === 0)
				{
					$where = "language_id != ".$language_id;
					if($home->ValidateTableField("code",$data['code'],"language_master",$where)) {
						$data['locale'] = $data['code'] . "," . $data['charset'] . "," . $data["code"] . "," . strtolower($data["name"]);
						$where = "language_id = " . $language_id;
						if($Language->updateLanguage($data,$where)) {
							$mysession->Admin_SMessage = $translate->_('Success_Edit_Language');
							$this->_redirect('/admin/languages'); 	
						} else {
							$editErrorMessage[] = $translate->_('Err_Edit_Language');
						}
						
					} else {
					
						$this->view->editErrorMessage = array($translate->_('Err_Language_Code_Exists'));	
					}
				} else {
					$this->view->editErrorMessage = $editErrorMessage;
				} 
			
				$this->view->records = $data; 
				$this->view->language_id =  $language_id;	
				$this->view->editErrorMessage = $editErrorMessage;
				
			} else {
			
				$this->_redirect("/admin/languages");
			}
		
		}
		
   }
   
   /**
     * Function deleteAction
	 *
	 * This function is used to delete the language
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
   
   public function deleteAction()
   {
   		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$Language = new Models_Language();
   		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$language_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($language_id > 0 && $language_id != "") {
			if($Language->deleteLanguage($language_id)) {
				$mysession->Admin_SMessage = $translate->_('Success_Delete_Language');
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Language');
			}		
		} 
		$this->_redirect("/admin/languages");		
   }  
   
   
   /**
     * Function deleteallAction
	 *
	 * This function is used to delete all selected languages.
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
   
   public function deleteallAction()
   {
   		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$Language = new Models_Language();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		
   		if(isset($_POST["id"])) {
		
			$language_ids = $this->_request->getPost('id'); 
			$ids = implode($language_ids,",");
			
			if($Language->deleteMultipleLanguages($ids)) {
				$mysession->Admin_SMessage = $translate->_('Success_M_Delete_Language');	
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Language');				
			}	
			
		}else{
		
			$mysession->Admin_EMessage = $translate->_('Err_M_Delete_Language');				
		}
		$this->_redirect("/admin/languages");	
   }
   
}
?>