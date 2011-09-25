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
 * Admin Category Controller.
 *
 * Admin_CategoryController  extends AdminCommonController. 
 * It controls categories on admin section
 *
 * Date Created: 2011-08-20
 *
 * @category	Puvoo
 * @package 	Admin_Controllers
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/  
 class Admin_CategoryController extends AdminCommonController
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
     * @author Amar
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	 
    function init()
    {
        parent::init();
        $this->view->JS_Files = array('admin/category.js','admin/AdminCommon.js');	
		Zend_Loader::loadClass('Models_Category');
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
     * @author Amar
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   function indexAction() 
   {
        global $mysession,$arr_pagesize;
		$this->view->site_url = SITE_URL."admin/category";
		$this->view->add_action = SITE_URL."admin/category/add";
		$this->view->edit_action = SITE_URL."admin/category/edit";
		$this->view->delete_action = SITE_URL."admin/category/delete";
		$this->view->delete_all_action = SITE_URL."admin/category/deleteall";
		
		
		//Create Object of Category model
		$category = new Models_Category();
		
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
			$data['category_name']=$filter->filter(trim($this->_request->getPost('category_name'))); 	
			$data['is_active']=$filter->filter(trim($this->_request->getPost('is_active'))); 			
			//Get search Categories
			$result = $category->SearchCategories($data);
			
		} elseif($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $category->GetAllCategories();
						
		} else 	{
			//Get all Categories
			$result = $category->GetAllCategories();
			
		}		
		// Success Message
		$this->view->Admin_Message = $mysession->Admin_Message;
		$mysession->Admin_Message = "";
		
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
	 * This function is used to add category
	 *
     * Date Created: 2011-08-26
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author yogesh
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function addAction()
   {
   		global $mysession;
		$this->view->site_url = SITE_URL."admin/category";
		$this->view->add_action = SITE_URL."admin/category/add";		
		
		$category = new Models_Category();
		$home = new Models_AdminMaster();
   		
		$this->view->categories = $category->GetMainCategory();
		
		$request = $this->getRequest();
		
		if($request->isPost()){
			
			$translate = Zend_Registry::get('Zend_Translate');
			
			$filter = new Zend_Filter_StripTags();	
			$data['category_name']=$filter->filter(trim($this->_request->getPost('category_name'))); 	
			$data['parent_id']=$filter->filter(trim($this->_request->getPost('parent_category'))); 	
			$data['is_active']=$filter->filter(trim($this->_request->getPost('is_active'))); 	
			$addErrorMessage = "";
			if($data['category_name'] == "") {
				$addErrorMessage .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Category_Name')."</h5><br />";			
			}
			if($data['is_active'] == "") {
				$addErrorMessage .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Is_Active')."</h5><br />";			
			}
			$where = "1 = 1";
			if($home->ValidateTableField("category_name",$data['category_name'],"category_master",$where)) {
				if( $addErrorMessage == ""){
					if($category->insertCategory($data)) {
						$mysession->Admin_Message = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Success_Add_Category')."</h5>";
						$this->_redirect('/admin/category'); 	
					} else {
						$addErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>There is some problem in adding category</h5>";	
					}
				} else {
					$this->view->addErrorMessage = $addErrorMessage;
				} 
			} else {
			
				$this->view->addErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Category_Name_Exists')."</h5>";	
			}
		}
   }
   
   
   /**
     * Function editAction
	 *
	 * This function is used to update category data.
	 *
     * Date Created: 2011-08-26
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author yogesh
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function editAction()
   {
   		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$category = new Models_Category();
   		$home = new Models_AdminMaster();
		
		$this->view->categories = $category->GetMainCategory();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$category_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($category_id > 0 && $category_id != "") {
			$this->view->records = $category->GetCategoryById($category_id);	
			$this->view->category_id =  $category_id;	
			
		} else {
			
			if($request->isPost()){
				
				$data["category_id"] = $filter->filter(trim($this->_request->getPost('category_id'))); 	
				$data['category_name']=$filter->filter(trim($this->_request->getPost('category_name'))); 	
				$data['parent_id']=$filter->filter(trim($this->_request->getPost('parent_category'))); 	
				$data['is_active']=$filter->filter(trim($this->_request->getPost('is_active'))); 
				
				$editErrorMessage = "";
				if($data['category_name'] == "") {
					$editErrorMessage .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Category_Name')."</h5>";			
				}
				if($data['is_active'] == "") {
					$editErrorMessage .= "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Is_Active')."</h5>";			
				}
				
				$where = "category_id != ".$data["category_id"];
				if($home->ValidateTableField("category_name",$data['category_name'],"category_master",$where)) {
					if( $editErrorMessage == ""){
						$where = "category_id = ".$data["category_id"];
						if($category->updateCategory($data,$where)) {
							$mysession->Admin_Message = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Success_Edit_Category')."</h5>";
							$this->_redirect('/admin/category'); 	
						} else {
							$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>There is some problem in editing category</h5>";	
						}
					} 
				} else {			
					
					$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Category_Name_Exists')."</h5>";	
				}
				
				$this->view->records = $category->GetCategoryById($data["category_id"]);	
				$this->view->category_id =  $data["category_id"];	
				$this->view->editErrorMessage = $editErrorMessage;
				
			} else {
			
				$this->_redirect("/admin/category");
			}
		
		}
		
   }
   
   /**
     * Function deleteAction
	 *
	 * This function is used to delete the category
	 *
     * Date Created: 2011-08-26
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function deleteAction()
   {
   		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$category = new Models_Category();
   		
		$this->view->categories = $category->GetMainCategory();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$category_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($category_id > 0 && $category_id != "") {
			if($category->deleteCategory($category_id)) {
				$mysession->Admin_Message = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Success_Delete_Category')."</h5>";
			} else {
				$mysession->Admin_Message = "<h5 style='color:#FF0000;margin-bottom:0px;'>There is some problem in deleting category</h5>";	
			}		
		} 
		$this->_redirect("/admin/category");		
   }  
   
   
   /**
     * Function deleteallAction
	 *
	 * This function is used to delete all selected categories.
	 *
     * Date Created: 2011-08-26
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function deleteallAction()
   {
   		//"deletemultipleCategories"
		
		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$category = new Models_Category();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		
   		if(isset($_POST["id"])) {
		
			$category_ids = $this->_request->getPost('id'); 
			$ids = implode($category_ids,",");
			
			if($category->deletemultipleCategories($ids)) {
				$mysession->Admin_Message = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Success_M_Delete_Category')."</h5>";	
			} else {
				$mysession->Admin_Message = "<h5 style='color:#FF0000;margin-bottom:0px;'>There is some problem in deleting categories</h5>";				
			}	
			
		}	else {
		
			$mysession->Admin_Message = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_M_Delete_Category')."</h5>";				
		}
		$this->_redirect("/admin/category");	
   }
   
}
?>
