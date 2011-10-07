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

	

class Fb_CategoryController extends FbCommonController

{
    /**
     * Function init()
	 *
	 * This function is used for initialization. Also include necessary javascript files.
	 *
     * Date Created: 2011-08-22
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Jayesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/  
     public function init()
     {
	 	parent::init();
        /* Initialize action controller here */
		Zend_Loader::loadClass('Models_Category');
     }
  
  	/**
	 * Function indexAction()
	 *
	 * This function is used for displays the category form. 
	 *
     * Date Created: 2011-08-22
     *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Jayesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/  
	 
	 public function indexAction()
     {
		global $mysession;
		 
		// action body
		$Category = new Models_Category();
		 
		//to get all main category
		$Allcategory = $Category->GetMainCategory();
		$this->view->Allcategory = $Category->GetMainCategory();
		 
		//for display category list on all categories page.
		foreach($Allcategory as $val)
		{
 			$pagecatlist = "";
 		 	$SubCat = $Category->GetSubCategory($val['category_id']);
			
			$pagecatlist .= "<div class='categoryItem'>";
			$pagecatlist .=	"<div class='bluewidgetHeader'>".$val['category_name']."</div>";
			$pagecatlist .=	"<ul class='categorySubItem'>";
				for($i=0; $i < count($SubCat); $i++)
					{
						$pagecatlist .= "<li>&raquo;&nbsp;&nbsp;<a href='".SITE_FB_URL."category/subcat/id/".$SubCat[$i]['category_id']."' target='_top'>".$SubCat[$i]['category_name']."</a></li>";
						
					}
			$pagecatlist .= "</ul></div>";
			//print_r($pagecatlist);
			$this->view->PageCatlist .= $pagecatlist;
		}// die;
		 
      }

  	/**
	 * Function subcatAction()
	 *
	 * This function is used for displays the category product page. 
	 *
     * Date Created: 2011-08-23
     *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Jayesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/  
	  
	 public function subcatAction()
	 {
		global $mysession;
		
	 	
 		$id = $this->_request->getParam('id');
		$this->view->cat_id = $id;
		$Category = new Models_Category();
		
		if(!$id)
		{
			$this->_redirect("fb/");
		}
		
		//set current page number
		$page_no = 1;
		
		//set no. of records to display on page
		$pagesize = $mysession->pagesize1;
 		
   		//Get Request
		$request = $this->getRequest();
		$Sort = '';
		if($request->isPost()){
 			$page_no = $request->getPost('page_no');
			$Sort = $request->getPost('sortBy');
 			$mysession->pagesize = $pagesize;
			$this->view->$Sort = "selected='selected'";
			if($Sort == ''){
				$Sort = 'bestseller';
			}
 		}
		
 		// to get category details
		$catdetails = $Category->GetCategoryDetail($id);
 		$parentid = $catdetails['parent_id'];
		
		// to get parentcategory details
 		$parentcat_details = $Category->GetCategoryDetail($parentid);
		$this->view->parentcat_id = $parentid;
		$this->view->parentcat_name = $parentcat_details['category_name'];
		
		//to get subcategory
		$subcat =$Category->GetSubCategory($id); 
 		$this->view->subcatlist = $subcat;
 		$this->view->catname = $catdetails['category_name'];
		 
		//to get category product
		$Product = new Models_Product();
		$catProd = $Product->GetProductByCategoryId($id,$Sort);
		$this->view->ProductList = $catProd;
		
//		print "<pre>";
//		print_r($catProd);die;
		
		//Set Pagination
		$paginator = Zend_Paginator::factory($catProd);
    	$paginator->setItemCountPerPage($pagesize);
    	$paginator->setCurrentPageNumber($page_no);
		
  		//Set View variables
		$this->view->sortBy = $Sort;
		$this->view->pagesize = $pagesize;
		$this->view->page_no = $page_no;
		$this->view->arr_pagesize = '1';
		$this->view->paginator = $paginator;
		$this->view->records = $paginator->getCurrentItems();
 	 }

}

?>