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
		$this->view->retailerId = $retailer_id;
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
				if($request->getParam('page_no') != ''){
					$page_no = $request->getParam('page_no');
					$Sort = $request->getParam('sortBy');
					$mysession->pagesize = $pagesize;
					$this->view->$Sort = "selected='selected'";
					if($Sort == ''){
					$Sort = 'bestseller';
					}
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
	 
	 
}

?>