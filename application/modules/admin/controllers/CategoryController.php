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
     *  
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
     *  
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
		
		//set category parent id
		$parent_id = 0;
		
		//Get Request
		$request = $this->getRequest();
		
		if($request->isGet()){
			$parent_id = (int)$request->getParam('parent_id');
		}
		if($request->isPost()){
		
			$page_no = $request->getPost('page_no');
			$pagesize = $request->getPost('pagesize');
			$mysession->pagesize = $pagesize;
			$is_search = $request->getPost('is_search');
			$parent_id = (int)$request->getPost('parent_id');
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
			
			//$result = $category->GetAllCategories();
			$result = $category->GetSubCategory($parent_id);
						
		} else 	{
			//Get all Categories
			//$result = $category->GetAllCategories();
			$result = $category->GetSubCategory($parent_id);
			
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
		$this->view->parent_id = $parent_id;
		$this->view->pagesize = $pagesize;
		$this->view->page_no = $page_no;
		$this->view->arr_pagesize = $arr_pagesize;
		$this->view->paginator = $paginator;
		$this->view->records = $paginator->getCurrentItems();
		
		if($parent_id > 0)
		{
			$this->view->path_array = $category->getParentCategories($parent_id);
		}else{
			$this->view->path_array = array();
		}		
		
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
     *  
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
		$parent_id = 0;
		if($request->isGet()){
			$parent_id = (int)$request->getParam('parent_id');
		}
		
		if($parent_id > 0)
		{
			$this->view->path_array = $category->getParentCategories($parent_id);
		}else{
			$this->view->path_array = array();
		}
		
		$this->view->cateTree = $category->getCateTree($parent_id);
		
		if($request->isPost()){
			
			$translate = Zend_Registry::get('Zend_Translate');
			
			$filter = new Zend_Filter_StripTags();	
			$parent_id = (int)$request->getPost('parent_id');
			$data['category_name']=$filter->filter(trim($this->_request->getPost('category_name'))); 	
			$data['parent_id']=$filter->filter(trim($this->_request->getPost('parent_category'))); 	
			$data['is_active']=$filter->filter(trim($this->_request->getPost('is_active'))); 	
			$addErrorMessage = array();
			if($data['category_name'] == "") {
				$addErrorMessage[] = $translate->_('Err_Category_Name');			
			}
			if($data['is_active'] == "") {
				$addErrorMessage[] = $translate->_('Err_Is_Active');			
			}
			$where = "1 = 1";
			if( count($addErrorMessage) == 0 || $addErrorMessage == ""){
				if($home->ValidateTableField("category_name",$data['category_name'],"category_master",$where)) {
					if($category->insertCategory($data)) {
						$mysession->Admin_SMessage = $translate->_('Success_Add_Category');
						$this->_redirect('/admin/category/index/parent_id/'.$parent_id); 	
					} else {
						$addErrorMessage[] = $translate->_('Err_Add_Category');
					}
				} else {
			
					$addErrorMessage[] = $translate->_('Err_Category_Name_Exists');	
				}
			} 
			$this->view->addErrorMessage = $addErrorMessage;
		}
		$this->view->parent_id = $parent_id;
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
     *  
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
		
		$parent_id = $this->_request->getParam('parent_id');
		
		$this->view->cateTree = $category->getCateTree($parent_id);
		
		if($category_id > 0 && $category_id != "") {
			$this->view->records = $category->GetCategoryById($category_id);	
			$this->view->category_id =  $category_id;	
			$this->view->parent_id = (int)$request->getPost('parent_id');
		} else {
			
			if($request->isPost()){
				
				$parent_id = (int)$request->getPost('parent_id');
				$data["category_id"] = $filter->filter(trim($this->_request->getPost('category_id'))); 	
				$data['category_name']=$filter->filter(trim($this->_request->getPost('category_name'))); 	
				$data['parent_id']=$filter->filter(trim($this->_request->getPost('parent_category'))); 	
				$data['is_active']=$filter->filter(trim($this->_request->getPost('is_active'))); 
				
				$editErrorMessage = array();
				if($data['category_name'] == "") {
					$editErrorMessage[] = $translate->_('Err_Category_Name');			
				}
				if($data['is_active'] == "") {
					$editErrorMessage[] = $translate->_('Err_Is_Active');			
				}
				
				$where = "category_id != ".$data["category_id"];
				if( count($editErrorMessage) == 0 || $editErrorMessage == ""){
					if($home->ValidateTableField("category_name",$data['category_name'],"category_master",$where)) {
					
						$where = "category_id = ".$data["category_id"];
						if($category->updateCategory($data,$where)) {
							$mysession->Admin_SMessage = $translate->_('Success_Edit_Category');
							$this->_redirect('/admin/category/index/parent_id/' . $parent_id); 	
						} else {
							$editErrorMessage[] = $translate->_('Err_Edit_Category'); 
						}
					
					} else {			
						
						$editErrorMessage[] = $translate->_('Err_Category_Name_Exists');	
					}
				}
				
				$this->view->parent_id = $parent_id;
				$this->view->records = $data;	
				$this->view->category_id =  $data["category_id"];	
				$this->view->editErrorMessage = $editErrorMessage;
				
			} else {
			
				$this->_redirect("/admin/category/index/parent_id/" . $parent_id);
			}
		
		}
		
		if($parent_id > 0)
		{
			$this->view->path_array = $category->getParentCategories($parent_id);
		}else{
			$this->view->path_array = array();
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
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function deleteAction()
   {
   		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$category = new Models_Category();
   		
		$this->view->categories = $category->GetMainCategory();
		
		$request = $this->getRequest();
		
		$parent_id = (int)$request->getPost('parent_id');
		
		$filter = new Zend_Filter_StripTags();	
		$category_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($category_id > 0 && $category_id != "") {
			if($category->deleteCategory($category_id)) {
				$mysession->Admin_SMessage = $translate->_('Success_Delete_Category');
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Category'); 
			}		
		} 
		$this->_redirect("/admin/category/index/parent_id/" . $parent_id);		
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
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function deleteallAction()
   {
   		//"deletemultipleCategories"
		
		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$category = new Models_Category();
		
		$request = $this->getRequest();
		
		$parent_id = (int)$request->getPost('parent_id');
		
		$filter = new Zend_Filter_StripTags();	
		
   		if(isset($_POST["id"])) {
		
			$category_ids = $this->_request->getPost('id'); 
			$ids = implode($category_ids,",");
			
			if($category->deletemultipleCategories($ids)) {
				$mysession->Admin_SMessage = $translate->_('Success_M_Delete_Category');	
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Category'); 				
			}	
			
		}	else {
		
			$mysession->Admin_EMessage = $translate->_('Err_M_Delete_Category');				
		}
		$this->_redirect("/admin/category/index/parent_id/" . $parent_id);	
   }
   
}
?>