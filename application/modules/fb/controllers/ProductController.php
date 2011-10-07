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



class Fb_ProductController extends FbCommonController

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
		$this->_redirect("fb/");
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
 		$Product = new Models_Product();
		$Category = new Models_Category();
 		$Poductid = $this->_request->getParam('id');
		$prodExist = $Product->ProductExist($Poductid);
		
		if($prodExist)
		{
			$this->view->ProdId = $Poductid;
					
			 
			//get category id through product id
				
			$result = $Category->GetcategoryID($Poductid);
			$this->view->cat_id = $result['category_id'];
			$this->view->catname = $result['category_name'];
	
			//to get parentcategory details
			$parentcat_details = $Category->GetCategoryDetail($result['parent_id']);
			$this->view->parentcat_id = $result['parent_id'];
			$this->view->parentcat_name = $parentcat_details['category_name'];
			 
			//to get subcategory
			$subcat =$Category->GetSubCategory($result['category_id']); 
			$this->view->subcatlist = $subcat;
			
			//to get Product Details
			$productDetail = $Product->GetProductDetails($Poductid);
			//print "<pre>";
			//print_r($productDetail);die;
			$this->view->ProdName = ucfirst($productDetail['product_name']);
			$ProdPrice = $productDetail['product_price'];
			$this->view->ProdPrice = $ProdPrice;
			$this->view->ProdDesc = $productDetail['product_description'];
			$this->view->ProdWeight = $productDetail['product_weight']."&nbsp".$productDetail['weight_unit_key'];
			$this->view->ProdLength = $productDetail['length']."&nbsp".$productDetail['length_unit_key'];
			$this->view->ProdSeller = $productDetail['store_name'];
			$this->view->ProdSellerId = $productDetail['user_id'];
	
			//to get Product images
			$productImages = $Product->GetProductImages($Poductid);
			$tinyImageList = '';
			$tinyImage = '';
			
			foreach($productImages as $key=>$img)
			{
				$SmallImg = explode('_',$img['image_name']);
				$tinyImage = SITE_PRODUCT_IMAGES_PATH.$img['image_path']."/".$SmallImg[0]."_th2.jpg";
				if($key == 0)
				{
					$this->view->DefaultImagePath = SITE_PRODUCT_IMAGES_PATH.$img['image_path']."/".$img['image_name']."";
				}
	
				if(count($productImages) > 1){
					$tinyImageList .= "<li id='TinyImageBox_".$key."' onclick='showProductThumbImage(".$key.",&#39;".$img['image_path']."&#39;,&#39;".$img['image_name']."&#39;)'>";
					$tinyImageList .= "<a href='javascript:void(0)' id='link".$key."' class='activelink'>";
					if($key == 0)
					{
						$tinyImageList .= "<div id='Tiny".$key."' class='active'>";
					}else{
						$tinyImageList .= "<div id='Tiny".$key."' class='otherimg'>";
					}
					$tinyImageList .= "<img id='TinyImage_".$key."' src='".$tinyImage."' style='border:none; padding:0px;' alt='' />";
					$tinyImageList .= "</div></a></li>";
					
					$this->view->imageList = $tinyImageList;
				}
			}	
			
			//to get Product Option
			$productOptions = $Product->GetProductOption($Poductid);
			
			for($i=0; $i < count($productOptions); $i++)
			{
				$Opt = '';
				$this->view->totalCombo = count($productOptions);
				//$TotalPrice = '';
				foreach($productOptions as $key => $option)
				{
						$OptionsValue = $Product->GetProductOptionValue($option['product_options_id']);
						//print "<pre>";
						//print_r($option);die;
						
						$Opt .= "<tr>";
						$Opt .= "<td><b>".ucfirst($option['option_title'])." Choice:</b><br />";
						$Opt .= "<select class='optionText inputtext' name='OptionCombo".$key."' id='OptionCombo".$key."' >";
						$Opt .= "<option value='0' onclick='javascript:changePrice(0,".$Poductid.",".$option['product_options_id'].")'>Choose ".$option['option_title']."</option>";
						
						foreach($OptionsValue as $val){
							//print_r($val);die;
							//$TotalPrice += $val['option_price'];
							$Opt .= "<option value='".$val['product_options_detail_id']."' onclick='javascript:changePrice(".$val['product_options_detail_id'].",".$Poductid.",".$option['product_options_id'].")'>".$val['option_name']."</option>";
							
						}
						//print $TotalPrice."+".$ProdPrice;die;
						//$this->view->ProdPrice = $ProdPrice+$TotalPrice;
						//print $this->view->ProdPrice;die;
						$Opt .= "</select></td></tr>";
						$Opt .= "<tr><td height='8'></td></tr>";
						
						$this->view->OptionCombo = $Opt;
				}
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
			$sort = $this->_request->getPost('sortBy');
			$this->view->$sort = "selected='selected'";
			if($sort == ''){
				$sort = 'bestseller';
			}
		}else{
			$QueryString = $_GET['q'];
			$Search = $_GET['Search'];
 			$CatId = $_GET['cid'];
			$sort = $this->_request->getPost('sortBy');
			$this->view->$sort = "selected='selected'";
			if($sort == ''){
				$sort = 'bestseller';
			}
		}
		//print $sort;die;
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
		$parentid = '';
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
 			$catProd = $Product->GetProductByCategoryId($CatId,$sort);
			//$this->view->ProductList = $catProd;
		}
		else
		{
			$this->view->allcategories = $Category->GetMainCategory();
		}
		
		$SearchDetails = $Product->GetProductSearch($QueryString,$Search,$CatId,$sort);
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
 	 	
		//print_r($_POST);die;
		//if(Facebook_Authentication_URL)
		//{
			if($this->_request->getParam('prodid'))
			{
				$prodId = $this->_request->getParam('prodid');
			}else{
				$prodId = 0;
			}
			
			if($prodId > 0)
			{
				//print $prodId;die;
				$ProductInfo = $Product->GetProductDetails($prodId);
				$ProductInfo['price'] = $this->_request->getParam('prodPrice');
				//print_r($ProductInfo);die;
				$exist = $Cart->ProductExist($ProductInfo['product_id']);
				
				//print $exist;die;
				if($exist < 1)
				{	
					// Insert Record in cart
					$ins_crt = $Cart->Insert_Record($ProductInfo);
					//print $ins_crt;die;
					if ($ins_crt = true)
					{
						$CartDetails = $Cart->GetCartDetailId($prodId);
 						$cartDetailId = $CartDetails['cart_detail_id'];
						// Insert Record in cart option
						if($this->_request->getParam('option'))
						{
							$optionInfo = explode(',',$this->_request->getParam('option'));
							
							foreach($optionInfo as $opt)
							{
								$productOptions = $Product->GetProductOptionUsingValue($prodId,$opt);
								
								//print_r($productOptions);die;
								$ins_opt = $Cart->Insert_CartOption_Record($productOptions,$cartDetailId);
								//$ProductInfo['options'] = $optionInfo;
								
							}
						}
						echo json_encode("Yout product add successfully in your cart");die;
					}
 					//echo json_encode($CartCnt);die;
					//echo SITE_FB_URL.$_SERVER['REQUEST_URI'];die;
					
				}
				else
				{
					echo json_encode("Product is already in your cart");die;
 					
				}
	
			}
		//}else{
		//	echo "error";die;
		//}
		
	 }
	 
	 /**
	 * Function updatepriceAction
	 *
	 * This function is used for update product price as per product option selection. 
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

	 public function updatepriceAction()
	 {
	 	global $mysession;
		$db = Zend_Registry::get('Db_Adapter');
		$Category = new Models_Category();
		$Product = new Models_Product();
		$Cart = new Models_Cart();
 	 	
		//print_r($_POST);die;
		//if(Facebook_Authentication_URL)
		//{
		
		if($this->_request->getParam('prodid'))
		{
			$prodId = $this->_request->getParam('prodid');
		}else{
			$prodId = 0;
		}
		
		$Opt_detailId = $this->_request->getParam('opt_detail_id');
		
		$OptPrice = $Product->getProductOptPrice($Opt_detailId,$prodId);
		//print_r($OptPrice );die;
		echo json_encode($OptPrice);die;
	 }
	 
}

?>