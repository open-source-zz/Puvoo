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



class Fb_RetailerController extends FbCommonController

{
	/**
	 * Function init
	 *
	 * This function is used for initialization. Also include necessary javascript files.
	 *
	 * Date Created: 2011-08-26
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
		Zend_Loader::loadClass('Models_Product');
	 }
	
	/**
	 * Function indexAction
	 *
	 * This function is used for displays the product page. 
	 *
	 * Date Created: 2011-08-26
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
	 }

	/**
	 * Function viewAction
	 *
	 * This function is used for displays the product details page. 
	 *
	 * Date Created: 2011-08-26
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Jayesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/  
	 
	 public function viewAction()
	 {
	 	global $mysession;
		$db = Zend_Registry::get('Db_Adapter');
		$Common = new Models_Common();
		$Product = new Models_Product();
		$Category = new Models_Category(); 
		
 		$retailer_id = $this->_request->getParam('id');
		if($retailer_id)
		{
			$Ret_Exist = $Common->RetailerExist($retailer_id);
			if($Ret_Exist)
			{
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
				}
				
				$this->view->$Sort = "selected='selected'";
				if($Sort == ''){
					$Sort = 'bestseller';
				}
		
				$this->view->RetailerId = $retailer_id;
						
				
				 
				//to get Product Details
				$Ret_productDetail = $Product->GetProductByRetailerId($retailer_id,$Sort);
				
				//print_r($Ret_productDetail);die;
				$this->view->StoreName = $Ret_productDetail[0]['store_name'];
				$paginator = Zend_Paginator::factory($Ret_productDetail);
				$paginator->setItemCountPerPage($pagesize);
				$paginator->setCurrentPageNumber($page_no);
				
				//Set View variables
				$this->view->sortBy = $Sort;
				$this->view->pagesize = $pagesize;
				$this->view->page_no = $page_no;
				$this->view->arr_pagesize = '1';
				$this->view->paginator = $paginator;
				$this->view->records = $paginator->getCurrentItems();
			}else
			{
 			 	$this->_redirect("fb/");
			}
		}else{
		
			$this->_redirect("fb/");
		}
	 }
	 
	/**
	 * Function searchAction
	 *
	 * This function is used for Searching the product from various category. 
	 *
	 * Date Created: 2011-08-30
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Jayesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/  
	 
	 public function searchAction()
	 {
	 	global $mysession;
		$db = Zend_Registry::get('Db_Adapter');
		$Category = new Models_Category();
		$Product = new Models_Product();
		
		//print_r($_POST);die;
		if(!$_GET)
		{
			$QueryString = $this->_request->getParam('q');
			$Search = 1;
 			$CatId = $this->_request->getParam('ccatid');
		}else{
			$QueryString = $_GET['q'];
			$Search = $_GET['Search'];
 			$CatId = $_GET['cid'];
		}
		//print $sort;die;
		$sort = $this->_request->getPost('sortBy');
 		$this->view->$sort = "selected='selected'";
		if($sort == ''){
			$sort = 'bestseller';
		}
		$this->view->qstring = $QueryString;
   		$this->view->catid = $CatId;
 		//set current page number
		$page_no = 1;
 		//set no. of records to display on page
		$pagesize = $mysession->pagesize1;
   		//Get Request
		$request = $this->getRequest();
		
		if($request->isPost()){
 			$page_no = $request->getPost('page_no');
 			$mysession->pagesize = $pagesize;
 		}
		
		if($CatId)
		{
 			// to get category details
			$catdetails = $Category->GetCategoryDetail($CatId);
			$parentid = $catdetails['parent_id'];
			//to get subcategory
			$subcat =$Category->GetSubCategory($CatId); 
			$this->view->subcatlist = $subcat;
			$this->view->catname = $catdetails['category_name'];
 			//to get category product
			$Product = new Models_Product();
			$catProd = $Product->GetProductByCategoryId($CatId,$sort);
			$this->view->ProductList = $catProd;
		}
		else
		{
			$this->view->allcategories = $Category->GetMainCategory();
		}
		
		$SearchDetails = $Product->GetProductSearch($QueryString,$Search,$parentid);
//		print "<pre>";
//		print_r($SearchDetails);die;
		//Set Pagination
		$paginator = Zend_Paginator::factory($SearchDetails);
    	$paginator->setItemCountPerPage($pagesize);
    	$paginator->setCurrentPageNumber($page_no);
		
  		//Set View variables
		$this->view->pagesize = $pagesize;
		$this->view->page_no = $page_no;
		$this->view->arr_pagesize = '1';
		$this->view->paginator = $paginator;
		$this->view->records = $paginator->getCurrentItems();
		//print_r($this->view->records);die;		
	 }
	 
	 /**
	 * Function addtocartAction
	 *
	 * This function is used for add current product on the cart. 
	 *
	 * Date Created: 2011-08-30
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Jayesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/  

	 public function addtocartAction()
	 {
	 	global $mysession;
		$db = Zend_Registry::get('Db_Adapter');
		$Category = new Models_Category();
		$Product = new Models_Product();
		$Cart = new Models_Cart();
 	 	
		//if(Facebook_Authentication_URL)
		//{
			if($_POST['prodid'])
			{
				$prodId = $_POST['prodid'];
			}else{
				$prodId = 0;
			}
			
			if($prodId > 0)
			{
				$ProductInfo = $Product->GetProductDetails($prodId);
				//print_r($ProductInfo);die;
 				
				$exist = $Cart->ProductExist($ProductInfo['product_id']);
				
				if($exist < 1)
				{		
					$ins_crt = $Cart->Insert_Record($ProductInfo);
 					//echo json_encode($CartCnt);die;
					//echo SITE_FB_URL.$_SERVER['REQUEST_URI'];die;
					echo "Product add successfully";
				}
				else
				{
					echo "Product is already in cart";
					exit();
				}
	
			}
		//}else{
		//	echo "error";die;
		//}
		
	 }
}

?>