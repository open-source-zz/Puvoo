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
	 	global $mysession;
		parent::init();
		 /* Initialize action controller here */
		Zend_Loader::loadClass('Models_Product');
		
		//$this->view->FB_userid = $mysession->FbuserId;
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
		$this->view->FB_userid = FBUSER_ID;
		$translate = Zend_Registry::get('Zend_Translate');
		
		if($Poductid)
		{
			$prodExist = $Product->ProductExist($Poductid);
			
			if($prodExist)
			{
				$this->view->ProdId = $Poductid;
						
				 
				//get category id through product id
					
				$result = $Category->GetcategoryID($Poductid);
				$this->view->cat_id = $result['category_id'];
				if($result['CatName'] != ''){
					$this->view->catname = $result['CatName'];
				}else{
					$this->view->catname = $result['category_name'];
				}
		
				//to get parentcategory details
				$parentcat_details = $Category->GetCategoryDetail($result['parent_id']);
				$this->view->parentcat_id = $result['parent_id'];
				
				if($parentcat_details['CatName'] != ''){
					$this->view->parentcat_name = $parentcat_details['CatName'];
				}else{
					$this->view->parentcat_name = $parentcat_details['category_name'];
				}
 				
				//to get Product Details
				$productDetail = $Product->GetProductDetails($Poductid);
 				
				if($productDetail['ProdName'] != ''){
					$product_name = $productDetail['ProdName'];
				}else{
					$product_name = $productDetail['product_name'];
				}
				
				if($productDetail['ProdDesc'] != ''){
					$product_desc = $productDetail['ProdDesc'];
				}else{
					$product_desc = $productDetail['product_description'];
				}
				$this->view->ProdUserId = $productDetail['user_id'];
				$this->view->ProdName = ucfirst($product_name);
				$ProdPrice = $productDetail['prod_convert_price'];
				//print $ProdPrice;die;
				$this->view->ProdPrice = $ProdPrice;
				$this->view->ProdDesc = $product_desc;
				$this->view->ProdWeight = $productDetail['product_weight']."&nbsp;".$productDetail['weight_unit_key'];
				$this->view->ProdLength = $productDetail['length']."&nbsp;".$productDetail['length_unit_key'];
				$this->view->ProdSeller = $productDetail['store_name'];
				
				$registration_date = date('Y-m-d',strtotime($productDetail['registration_date']));
				
				$ExpiteDate = date("Y-m-d",strtotime(date("Y-m-d", strtotime($registration_date)) . " +1 month"));
				$todaydate = date("Y-m-d");
				
				$this->view->todaydate = $todaydate;
				
				$this->view->Expiredate = $ExpiteDate;
				
				
				$this->view->ProdSellerId = $productDetail['user_id'];
		
				//to get Product images
				$productImages = $Product->GetProductImages($Poductid);
				$tinyImageList = '';
				$tinyImage = '';
				
				foreach($productImages as $key=>$img)
				{
					$SmallImg = explode('_',$img['image_name']);
					$tinyImage = SITE_PRODUCT_IMAGES_PATH.$img['image_path']."/".$SmallImg[0]."_th2.jpg";
					if($img["is_primary_image"] == 1)
					{
						$this->view->DefaultImagePath = SITE_PRODUCT_IMAGES_PATH.$img['image_path']."/".$img['image_name']."";
					}
		
					if(count($productImages) > 1){
						$tinyImageList .= "<li id='TinyImageBox_".$key."' onclick='showProductThumbImage(".$key.",&#39;".$img['image_path']."&#39;,&#39;".$img['image_name']."&#39;)'>";
						$tinyImageList .= "<a href='javascript:void(0)' id='link".$key."' class='activelink'>";
						if($img["is_primary_image"] == 1)
						{
							$tinyImageList .= "<div id='Tiny".$key."' class='active'>";
						}else{
							$tinyImageList .= "<div id='Tiny".$key."' class='otherimg'>";
						}
						$tinyImageList .= "<img id='TinyImage_".$key."' src='".$tinyImage."' style='border:none; padding:0px;' alt='".$product_name."' title='".$product_name."' />";
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
							$Opt .= "<td><b>".ucfirst($option['option_title'])." ".$translate->_('Choice').":</b><br />";
							$Opt .= "<select class='optionText inputtext' name='OptionCombo".$key."' id='OptionCombo".$key."' onchange='javascript:changePrice(this.value,".$Poductid.",".$option['product_options_id'].")' >";
							$Opt .= "<option value='0' >".$translate->_('Choose')."&nbsp;".$option['option_title']."</option>";
							
							foreach($OptionsValue as $val){
								//print_r($val);die;
								//$TotalPrice += $val['option_price'];
								$Opt .= "<option value='".$val['product_options_detail_id']."' >".$val['option_name']."</option>";
								
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
		
		$this->view->temp_url = FB_REDIRECT_URL;
		
		if(!$_GET)
		{
			$QueryString = $this->_request->getParam('q');
			$Search = 1;
 			$CatId = $this->_request->getParam('ccatid');
		}else{
			$QueryString = $this->_request->getParam('q');
			$Search = $this->_request->getParam('search');
 			$CatId = $this->_request->getParam('cid');
			
			
			$this->view->QueryString = $QueryString;
			$this->view->Search = $Search;
 			$this->view->CatId = $CatId;
		}
		
		if($this->_request->getParam('sortBy') !='')
		{
			$sort = $this->_request->getParam('sortBy');
			$this->view->$sort = "selected='selected'";
		}else{
			$sort = 'bestseller';
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
		
		if($request->getParam('page_no') != ''){
 			$page_no = $request->getParam('page_no');
 			$mysession->pagesize = $pagesize;
 		}
		$parentid = '';
		if($CatId != '')
		{
			
 			// to get category details
			$catdetails = $Category->GetCategoryDetail($CatId);
			$parentid = $catdetails['parent_id'];
			
			if($catdetails['CatName'] != ''){
				$this->view->catname = $catdetails['CatName'];
			}else{
				$this->view->catname = $catdetails['category_name'];
			}
			
			if($parentid == 0)
			{
				$catIdString = $Category->getCategoryTreeString($CatId);
				
				$catid_String = $catIdString.$CatId;
			}
			else
			{
				$catid_String = $CatId;
			}
 			//to get category product
 			$catProd = $Product->GetProductByCategoryId($CatId,$sort);
			//$this->view->ProductList = $catProd;
		}
		
		$QueryString =$this->_request->getParam('q');
		$Search = $this->_request->getParam('search');
		$CatId = $this->_request->getParam('cid');
		
		$SearchDetails = $Product->GetProductSearch($QueryString,$Search,$catid_String,$sort);
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
		$this->_helper->layout()->disableLayout();
		$db = Zend_Registry::get('Db_Adapter');
		$Category = new Models_Category();
		$Product = new Models_Product();
		$Cart = new Models_Cart();
 	 	$Common = new Models_Common();
		//if(Facebook_Authentication_URL)
		//{
			
			if($this->_request->getPost('prodid'))
			{
				$prodId = $this->_request->getPost('prodid');
			}else{
				$prodId = 0;
			}
			
			
			if($prodId > 0)
			{
				
				$ProductInfo = $Product->GetProductDetails($prodId);
				$ProductInfo['price'] = $this->_request->getPost('prodPrice');
				$ProductInfo['fbid'] = $this->_request->getPost('fbuserid');
				
				$exist = $Cart->ProductExist($ProductInfo['product_id'],$ProductInfo['fbid']);
 				
 				if($exist < 1)
				{	
					// Insert Record in cart
					$ins_crt = $Cart->InsertRecord($ProductInfo);
					
					if ($ins_crt = true)
					{
						$CartDetails = $Cart->GetCartDetailId($prodId,$ProductInfo['fbid']);
 						$cartDetailId = $CartDetails['cart_detail_id'];
						// Insert Record in cart option
						//print_r($this->_request->getParam('option'));die;
						if($this->_request->getPost('option') != '')
						{
							$optionInfo = explode(',',$this->_request->getPost('option'));
							
							foreach($optionInfo as $opt)
							{
								if($opt != 0)
								{
									$productOptions = $Product->GetProductOptionUsingValue($prodId,$opt);
									//print_r($productOptions);die;
									$ins_opt = $Cart->InsertCartOptionRecord($productOptions,$cartDetailId);
								}
								//$ProductInfo['options'] = $optionInfo;
								
							}
						}
						//echo json_encode("Yout product add successfully in your cart");die;
					}
 					//echo json_encode($CartCnt);die;
					//echo SITE_FB_URL.$_SERVER['REQUEST_URI'];die;
					
				}
	
			}
			
		$CartDetails = $Cart->GetProductInCart($this->_request->getPost('fbuserid'));
		
		if(count($CartDetails) > 0){
			$this->view->cartItems = $CartDetails;
			
			$cartId = '';
			$Price = 0;
			$CartTotal = '';
			foreach($CartDetails as $value)
			{
				//echo "<pre>";
				//print_r($value);die;
 				$Price += $value['price']*$value['product_qty'];
				$cartId = $value['cart_id'];
				$CartTotal += $value['product_qty'];
				$cartuserId = $value['user_id'];
				$currency_symbol = $Common->GetCurrencyValue($value['currId']);
				
			}
			$this->view->cartuserId = $cartuserId;
			$this->view->currency_symbol = $currency_symbol['currency_symbol'];
			
 			$this->view->CartCnt = count($CartDetails);
 			$this->view->TotalPrice = $Price;
			$this->view->cartId = $cartId;
			$mysession->cartId = $cartId;
			$this->view->currencyId = $currency_symbol['currency_id'];
			
			if($cartId) {
				
				$ShippingInfo = $Cart->GetShippingInfo($cartId);
				//print_r($ShippingInfo);die;
				$this->view->firstName = $ShippingInfo['shipping_user_fname'];
				$this->view->lastName = $ShippingInfo['shipping_user_lname'];
				$this->view->email = $ShippingInfo['shipping_user_email'];
				$this->view->phone = $ShippingInfo['shipping_user_telephone'];
				$this->view->addType = $ShippingInfo['shipping_user_address_type'];
				$this->view->stateID = $ShippingInfo['shipping_user_state_id'];
				$this->view->stateName = $ShippingInfo['state_name'];
				$this->view->countryId = $ShippingInfo['shipping_user_country_id'];
				$address = explode('@',$ShippingInfo['shipping_user_address']);
				if(isset($address[0]))
				{
					$this->view->address = $address[0];
 				}
				if(isset($address[1]))
				{
					$this->view->address1 = $address[1];
 				}
				
				$this->view->city = $ShippingInfo['shipping_user_city'];
				$this->view->state = $ShippingInfo['shipping_user_state_id'];
				$this->view->zip = $ShippingInfo['shipping_user_zipcode'];
				
				$BillingInfo = $Cart->GetBillingInfo($cartId);
				//print_r($ShippingInfo);die;
				$this->view->bill_firstName = $BillingInfo['billing_user_fname'];
				$this->view->bill_lastName = $BillingInfo['billing_user_lname'];
				$this->view->bill_email = $BillingInfo['billing_user_email'];
				$this->view->bill_phone = $BillingInfo['billing_user_telephone'];
				$this->view->bill_addType = $BillingInfo['billing_user_address_type'];
				$this->view->bill_stateID = $BillingInfo['billing_user_state_id'];
				$this->view->bill_stateName = $BillingInfo['state_name'];
				$this->view->bill_countryId = $BillingInfo['billing_user_country_id'];
				$Billaddress = explode('@',$BillingInfo['billing_user_address']);
 				//$this->view->bill_address = $Billaddress[0];
				//$this->view->bill_address1 = $Billaddress[1];
				if(isset($Billaddress[0]))
				{
					$this->view->bill_address = $Billaddress[0];
 				}
				if(isset($Billaddress[1]))
				{
					$this->view->bill_address1 = $Billaddress[1];
 				}
				
				$this->view->bill_city = $BillingInfo['billing_user_city'];
				$this->view->bill_state = $BillingInfo['billing_user_state_id'];
				$this->view->bill_zip = $BillingInfo['billing_user_zipcode'];
				
			}
			// Country combo
			$this->view->CountryCombo = $Cart->GetCountry();
			// State combo
			$this->view->StateCombo = $Cart->GetState($this->view->countryId);
			
			$this->view->ShipCountryCombo = $Cart->GetCountryCombo();
			
			$this->view->CurrencyCombo = $Common->GetCurrency();
			
			//to get paypal details
			$paypalUrl = $Common->GetConfigureValue('paypal_url');
			
			$PaypalDetails = $Common->GetPaypalDetails($this->view->cartuserId);
	
			$mysession->Paypal_Url = $paypalUrl['configuration_value'];
			$mysession->Api_Username = $PaypalDetails['paypal_email'];
			$mysession->Api_Password = $PaypalDetails['paypal_password'];
			$mysession->Api_Signature = $PaypalDetails['paypal_signature'];
		}		

		
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
		//echo "<pre>";
		//print_r($OptPrice );die;
		echo json_encode($OptPrice);die;
	 }
	 
}

?>