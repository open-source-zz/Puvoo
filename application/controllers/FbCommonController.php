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
 * FbCommonController class for assigning constants.
 *
 * This class file extends Zend_Controller_Action.It is used to check whether admin is login or not and for assigning path constants,title,etc.
 *
 *
 * @author Jayesh
 *  
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class FbCommonController extends Zend_Controller_Action 
{
	/**
	 * Function init
	 *
	 * This function is used for assigning constants like path,title,etc 
	 *
	 * Date created: 2011-08-22
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Jayesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function init() 
	{
        global $mysession; 
		
 		$db = Zend_Db_Table::getDefaultAdapter();
		Zend_Registry::set('Db_Adapter', $db);
		//require_once ("../public/paypalfunctions.php");
		// Define Site Url for fb folder
		define('SITE_FB_URL', 'http://'. $_SERVER['HTTP_HOST']. INSTALL_DIR."fb/");

		// Define Path for images folder FB
		define('IMAGES_FB_PATH', INSTALL_DIR."public/images/fb");

		// Define Path for css folder for FB
		define('CSS_FB_PATH', INSTALL_DIR."public/css/fb" );

		// Define Path for css folder for FB
		define('JS_FB_PATH', INSTALL_DIR."public/js/fb" );
		
		//Get Language array
		$lang = array();
		
		$lang_def = new Models_LanguageDefinitions();
		
		$lang = $lang_def->getGroupLanguage('FB', 1);
		
		//Set Default language for site
		$tr = new Zend_Translate(
			array(
			'adapter' => 'array',
			  'content' => $lang,
			  'locale'  => $mysession->language 
			)
		);
		   
		   
		Zend_Registry::set('Zend_Translate', $tr);
		define('Facebook_Authentication_URL',"https://www.facebook.com/dialog/oauth?client_id=236169479754038&redirect_uri=https://apps.facebook.com/puvootest/");
		
		//$mysession->FbuserId = "xyz@yahoo.com";
		$mysession->FbuserId = "";
 		$Category = new Models_Category();
		$Product = new Models_Product();
		$catid = $this->_request->getParam('id');
		
		//Get Request
		$request = $this->getRequest();
		
		$cur_controller = $request->getControllerName();
 		
			if($cur_controller!='category' && $catid != '')
			{
				$this->view->SerchCategoryCombo = $Category->GetMainCategory();
			}
			else
			{
				$CatCombo = $Category->GetSubCategory($catid);
				if($CatCombo)
				{
					$this->view->SerchCategoryCombo = $CatCombo;
				}else{
					$this->view->SerchCategoryCombo = $Category->GetMainCategory();
				}
			}
		
		//set default pagesize for admin section
	
		if(!isset($mysession->pagesize1)){
			
			$mysession->pagesize1 = 6;
		}
		//print $mysession->pagesize1;die;
		// Left Maenu
		//to get all main category
		$Allcategory = $Category->GetMainCategory();
		//print "<pre>";
		//print_r($Allcategory);die;
		$No_category = count($Allcategory);
		
		if($No_category > 4)
		{
			$Modulo_category = $No_category % 4;
			
			$final_cal = ($No_category - $Modulo_category ) / 4;
			
			$cat_array = array();
			
			$cat_array[0] = $final_cal;
			$cat_array[1] = $final_cal;
			$cat_array[2] = $final_cal;
			$cat_array[3] = $final_cal;
			
			//print_r($cat_array);die;
			for($i=0; $i< $final_cal; $i++) 
			{
				$cat_array[$i] += 1; 	
			}
			
			$new_no = $cat_array[0];
			// i m here
			// loop the array by $new_no
			 
			$menu_array = array();
			$increment_no = 0;
			foreach( $cat_array as $key => $val )
			{
				//print_r($val);
				$row = array();
				$limit_no = $increment_no + $new_no; 
				$other_no = 0;
				for( $j = $increment_no; $j < (int)$limit_no; $j++ )
				{
					if(isset($Allcategory[$j])) {
						$row[] = $Allcategory[$j];
					}
				}
				$increment_no = $increment_no + $new_no; 
				$menu_array[] = $row;
			}
		}else{
		
			$menu_array[] = $Allcategory;
		}
		//print "<pre>";
		//print_r($menu_array);die;
		$this->view->Mycategory = $menu_array;
		$this->view->Allcategory = $Allcategory;
		
		//$cat = array();

		//print_r($cat);die;
		//to get selected category
		$selectCat = $Category->GetCategory();
		
		 
		//for display category list on left menu 
		foreach($selectCat as $val)
		{
		 	$SubCatList = "";
			
		  	$SubCat = $Category->GetSubCategory($val['category_id']);
 			
			$SubCatList .= "<li class=''>";
			$SubCatList .= "<a class='sf-with-ul' href='".SITE_FB_URL."category/subcat/id/".$val['category_id']."' target='_top'>".$val['category_name']."				                            <span class='sf-sub-indicator'> »</span></a>";
			$SubCatList .= "<div><ul class='submenu'>";
				for($i=0; $i<count($SubCat); $i++)
				{
					$SubCatList .= "<li>";
					$SubCatList .= "<a href='".SITE_FB_URL."category/subcat/id/".$SubCat[$i]['category_id']."' target='_top'><img src='".IMAGES_FB_PATH."/2d30f5a834021d7887e1712062d0ed055283edc5_th.jpg' alt='' />".$SubCat[$i]['category_name']."</a>";
					$SubCatList .="</li>";
 				}
									
   			$SubCatList .="</ul></div></li>";
 			$this->view->Category .= $SubCatList;
			
 		}
		
		///CART Controller
		
 		//To know how many product in Cart
		$Cart = new Models_Cart();
		$CartDetails = $Cart->GetProductInCart();
		//print "<pre>";
		//print_r($CartDetails);die;
		if($CartDetails){
			$this->view->cartItems = $CartDetails;
			
			$cartId = '';
			$Price = 0;
			$CartTotal = '';
			foreach($CartDetails as $value)
			{
 				$Price += $value['product_price']*$value['product_qty'];
				$cartId = $value['cart_id'];
				$CartTotal += $value['product_qty'];
			}
			//print $Price;die;
			$this->view->CartCnt = $CartTotal;
 			$this->view->TotalPrice = $Price;
			$this->view->cartId = $cartId;
			$mysession->cartId = $cartId;
			
			// Get Shipping Information
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
 				$this->view->address = $address[0];
				$this->view->address1 = $address[1];
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
 				$this->view->bill_address = $Billaddress[0];
				$this->view->bill_address1 = $Billaddress[1];
				$this->view->bill_city = $BillingInfo['billing_user_city'];
				$this->view->bill_state = $BillingInfo['billing_user_state_id'];
				$this->view->bill_zip = $BillingInfo['billing_user_zipcode'];
				
			}
			
			// Country combo
			$this->view->CountryCombo = $Cart->GetCountry();
			// State combo
			$this->view->StateCombo = $Cart->GetState($this->view->countryId);
			
			
			$_SESSION['Payment_Amount'] = '1000';
			
			
			
		}
		$Id = $this->_request->getParam('id');
		//print_r($Url);
		if($Id)
		{
			
				if($cur_controller == 'product' || $cur_controller == 'retailer')
				{
					$userId = '';
					
					if($cur_controller == 'product' && $Id!='')
					{
						$productDetails = $Product->GetProductDetails($Id);
						$userId = $productDetails['user_id'];
					}
					if($cur_controller == 'retailer' && $Id!='')
					{
						$userId = $Id;
					}
					//print $userId;die;
					$sellerInfo = $Product->GetSellerInformation($userId);
					
					//print_r($sellerInfo);die;
					$this->view->terms = $sellerInfo['store_terms_policy'];
					$this->view->returnPolicy = $sellerInfo['return_policy'];
					$this->view->storeDescription = $sellerInfo['store_description'];
				}
			
		}
  	} 

	/**
	 * Function indexAction
	 *
	 * This is empty function currently
	 *
	 * Date created: 2011-08-22
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Jayesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function indexAction()
	{
 	}
	
 }
?>