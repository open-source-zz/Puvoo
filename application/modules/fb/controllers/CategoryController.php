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
		 
		$Category = new Models_Category();
		 
		//to get all main category
		$Allcategory = $Category->GetMainCategory();
		$this->view->Allcategory = $Category->GetMainCategory();
		
		foreach($Allcategory as $val)
		{
 			$pagecatlist = "";
 		 	
 			$catIdString = $Category->getCategoryTreeString($val['category_id']);
					
			$catid_String = $catIdString.$val['category_id'];
			
			$SubCat = $Category->GetSubCategory($catid_String);
			
			$pagecatlist .= "<div class='categoryItem'>";
			$pagecatlist .=	"<div class='bluewidgetHeader'>".$val['category_name']."</div>";
			$pagecatlist .=	"<ul class='categorySubItem'>";
				for($i=0; $i < count($SubCat); $i++)
					{
						if($SubCat[$i]['catName'] != '')
						{
							$subCatName = $SubCat[$i]['catName'];
						}else{
							$subCatName = $SubCat[$i]['category_name'];
						}
						
						$pagecatlist .= "<li>&raquo;&nbsp;&nbsp;<a href='".SITE_FB_URL."category/subcat/id/".$SubCat[$i]['category_id']."' target='_top'>".$subCatName."</a></li>";
						
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
		
	 	
 		$catid = $this->_request->getParam('id');
		$this->view->cat_id = $catid;
		$Category = new Models_Category();
		$Common = new Models_Common();
		
		
		if($catid)
		{
		
			$catExist = $Category->CategoryExist($catid);
			
			if($catExist)
			{
				//set current page number
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
				
				// to get category details
				$catdetails = $Category->GetCategoryDetail($catid);
				
				$parentid = $catdetails['parent_id'];
				
				// to get parentcategory details
				$parentcat_details = $Category->GetCategoryDetail($parentid);
				$this->view->parentcat_id = $parentid;
				if($parentcat_details['CatName'] != ''){
					$this->view->parentcat_name = $parentcat_details['CatName'];
				}else{
					$this->view->parentcat_name = $parentcat_details['category_name'];
				}
				
				//to get subcategory
				$subcat =$Category->GetSubCategory($catid); 
				
 				if($catdetails['CatName'] != ''){
					$this->view->catname = $catdetails['CatName'];
				}else{
					$this->view->catname = $catdetails['category_name'];
				}
				
				 
				if($parentid == 0)
				{
					$catIdString = $Category->getCategoryTreeString($catid);
					
					$catid_String = $catIdString.$catid;
				}
				else
				{
					$catid_String = $catid;
				}
				//to get category product
				$Product = new Models_Product();
				$catProd = $Product->GetProductByCategoryId($catid_String,$Sort);
				$this->view->ProductList = $catProd;
				
				foreach($catProd as $prokey => $val)
				{
				
					$defaultZone = $Common->GetDefaultTaxRate($val['uid']);
					//$mysession->default_taxrate = $defaultZone['tax_rate'];
					//$mysession->default_taxZone = $defaultZone['tax_zone'];
					
					 if($val['tax_zone'] != '')
					 {		 
					 	$taxzone = explode(',',$val['tax_zone']);
					 }else{
					 	$taxzone = explode(',',$defaultZone['tax_zone']);
					 }
					 
 					
					 $tax_rate = $Common->TaxCalculation($taxzone,$val['tax_rate'],$mysession->Default_Countrycode,'',$defaultZone['tax_rate']);
					 
					 
					 $catProd[$prokey]['prod_convert_price'] = number_format(round((($val['product_price'] +(($val['product_price'] * $tax_rate)/100))* $mysession->currency_value)/$val['currency_value'],2), 2, DEFAULT_DECIMAL_SEPARATOR, DEFAULT_THOUSANDS_SEPARATOR);
					 
				 }
				
				
//				foreach($catProd as $val)
//				{
//					$url = SITE_FB_URL . "product/view/id/" . $val['product_id'];
//					print $url;die;
//					$facebook = new Facebook(array(
//			
//							  'appId'  => FACEBOOK_APP_API_ID,
//			
//							  'secret' => FACEBOOK_APP_SECRET_KEY,
//			
//							  'cookie' => true,
//			
//						));
//					
//					$data = $facebook->api( 
//			
//									array( 
//			
//											'method' => 'fql.query', 
//			
//											'query' => 'SELECT like_count FROM link_stat WHERE url='.$url.'' 
//			
//										) 
//			
//									);
//									
//					print $data;
//				
//				}die;
				
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
			}else{
				$this->_redirect("fb/");
			}
		}else{
			$this->_redirect("fb/");
		}
 	 }

}

?>