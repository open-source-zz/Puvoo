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
        global $mysession, $user_profile; 
		
 		$db = Zend_Db_Table::getDefaultAdapter();
		Zend_Registry::set('Db_Adapter', $db);
 		// Define Site Url for fb folder
		
		if($_SERVER['HTTP_HOST'] == 'rishi')
		{
			
			define('SITE_FB_URL', 'http://'. $_SERVER['HTTP_HOST']. INSTALL_DIR."fb/");
			define('SITE_AJX_URL', 'http://'. $_SERVER['HTTP_HOST']. INSTALL_DIR."fb/");
		}else{
 			define('SITE_FB_URL', 'http://apps.facebook.com/pvalpha/');
			define('SITE_AJX_URL', 'http://fbstore.kintudesigns.com/fb/');
		}
		//define('SITE_FB_URL', 'http://apps.facebook.com/pvalpha/');
			
		// Define Path for images folder FB
		define('IMAGES_FB_PATH', INSTALL_DIR."public/images/fb");

		// Define Path for css folder for FB
		define('CSS_FB_PATH', INSTALL_DIR."public/css/fb" );

		// Define Path for css folder for FB
		define('JS_FB_PATH', INSTALL_DIR."public/js/fb" );
		
		// Facebook User Id
		define('FACEBOOK_USERID', $mysession->FbuserId);
		
		define('FBUSER_ID', $mysession->Facebook_UserId);
		
		define('DEFAULT_CURRENCY', 2);
		
		$tempArray = explode("/",$_SERVER["REQUEST_URI"]);
		$siteArray = array();
		foreach( $tempArray as $key => $val )
		{
			if( $key != 0 && $key != 1 && $key != 2 ) {
				$siteArray[] = $val;
			}
		}
		
		$temp_url = SITE_FB_URL.implode("/",$siteArray);
		
		define('FB_REDIRECT_URL', $temp_url);
		
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
		define('Facebook_Authentication_URL',"https://www.facebook.com/dialog/oauth?client_id=124974344272864&redirect_uri=https://apps.facebook.com/pvalpha/");
		
		
		$mysession->FbuserId = "";
 		$Category = new Models_Category();
		$Product = new Models_Product();
		$Common = new Models_Common();
		$admin_master = new Models_AdminMaster();
		$catid = $this->_request->getParam('id');
		
		$this->view->Facebook_userid = FBUSER_ID;
		$this->view->facebook_user_numeric_id = FBUSER_ID;
		
		if( $user_profile != NULL ) { 
		
			$where = '1 = 1';
			
			if($admin_master->ValidateTableField("facebook_id",$user_profile['id'],"facebook_user_master",$where)) {
			
				if($user_profile['email'] != '' ) {
					$fbArray = array( 
									'facebook_id'	 => $user_profile['id'],
									'facebook_email' =>	$user_profile['email'],
									'facebook_name'  =>	$user_profile['name']
								 );
					$admin_master->insertFacebookUser($fbArray);
				}
			}
			
		}
		
		
		$currency_value = $Common->GetCurrencyValue(DEFAULT_CURRENCY);
		
		$mysession->currency_value = $currency_value['currency_value'];
		$mysession->currency_id = $currency_value['currency_id'];
		
		define('DEFAULT_CURRENCY_SYMBOL', $currency_value['currency_symbol']);
		
		
		define('DEFAULT_CURRENCY_CODE', $currency_value['currency_code']);
		
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
		
		// Left Maenu
		//to get all main category
		$Allcategory = $Category->GetMainCategory();
		
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
			
			
			for($i=0; $i< $final_cal; $i++) 
			{
				$cat_array[$i] += 1; 	
			}
			
			$new_no = $cat_array[0];
			
			// loop the array by $new_no
			 
			$menu_array = array();
			$increment_no = 0;
			foreach( $cat_array as $key => $val )
			{
				
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
		
		
		$this->view->Mycategory = $menu_array;
		$this->view->Allcategory = $Allcategory;
		
		//to get selected category
		$selectCat = $Category->GetTopMainCategory();
		
		 
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
					if($SubCat[$i]['icon_image'] != '')
					{
						$Icon = "<img src='".SITE_ICONS_IMAGES_PATH."/".$SubCat[$i]['icon_image']."' alt='' />";
					}else{
						$Icon = "<img src='".IMAGES_FB_PATH."cat_default.png' alt='' />";
					}
				
				
					$SubCatList .= "<li>";
					$SubCatList .= "<a href='".SITE_FB_URL."category/subcat/id/".$SubCat[$i]['category_id']."' target='_top'>".$Icon.$SubCat[$i]['category_name']."</a>";
					$SubCatList .= "<div><ul class='submenu'>";
					$Sub_Cat = $Category->GetSubCategory($SubCat[$i]['category_id']);
					
					for($j=0; $j<count($Sub_Cat); $j++)
					{
						if($Sub_Cat[$i]['icon_image'] != '')
						{
							$Icon1 = "<img src='".SITE_ICONS_IMAGES_PATH."/".$Sub_Cat[$i]['icon_image']."' alt='' />";
						}else{
							$Icon1 = "<img src='".IMAGES_FB_PATH."cat_default.png' alt='' />";
						}
					
						$SubCatList .= "<li>";
						$SubCatList .= "<a href='".SITE_FB_URL."category/subcat/id/".$Sub_Cat[$i]['category_id']."' target='_top'>".$Icon1.$Sub_Cat[$i]['category_name']."</a>";
						$SubCatList .= "</li>";
					}
					$SubCatList .= "</ul></div>";
					$SubCatList .="</li>";
 				}
									
   			$SubCatList .="</ul></div></li>";
 			$this->view->Category .= $SubCatList;
			
 		}
		
 		//To know how many product in Cart
		$Cart = new Models_Cart();
		$CartDetails = $Cart->GetProductInCart(FBUSER_ID);
		
		if($CartDetails){
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
				
				$currency_symbol = $Common->GetCurrencyValue($value['currId']);
				
			}
			$this->view->currency_symbol = $currency_symbol['currency_symbol'];
			
 			$this->view->CartCnt = count($CartDetails);
 			$this->view->TotalPrice = $Price;
			$this->view->cartId = $cartId;
			$mysession->cartId = $cartId;
			$this->view->currencyId = $currency_symbol['currency_id'];
			// Get Shipping Information
			if($cartId) {
				
				$ShippingInfo = $Cart->GetShippingInfo($cartId);

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
 
 				$this->view->bill_firstName = $BillingInfo['billing_user_fname'];
				$this->view->bill_lastName = $BillingInfo['billing_user_lname'];
				$this->view->bill_email = $BillingInfo['billing_user_email'];
				$this->view->bill_phone = $BillingInfo['billing_user_telephone'];
				$this->view->bill_addType = $BillingInfo['billing_user_address_type'];
				$this->view->bill_stateID = $BillingInfo['billing_user_state_id'];
				$this->view->bill_stateName = $BillingInfo['state_name'];
				$this->view->bill_countryId = $BillingInfo['billing_user_country_id'];
				$Billaddress = explode('@',$BillingInfo['billing_user_address']);
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
			/// Currency
			$this->view->CurrencyCombo = $Common->GetCurrency();
			
		}
		$Id = $this->_request->getParam('id');
		
		if($cur_controller == 'product' || $cur_controller == 'retailer')
		{
			$userId = '';
			
			if($cur_controller == 'product' && $Id!='')
			{
				$prodExist = $Product->ProductExist($Id);
				if($prodExist) {		
					$productDetails = $Product->GetProductDetails($Id);
					$userId = $productDetails['user_id'];
				} else  {
					$this->_redirect("/fb");
				 }
			}
			if($cur_controller == 'retailer' && $Id!='')
			{
				$userId = $Id;
			}
			
			if ( $userId != '' ) {  

				$sellerInfo = $Product->GetSellerInformation($userId);
				$this->view->terms = $sellerInfo['store_terms_policy'];
				$this->view->returnPolicy = $sellerInfo['return_policy'];
				$this->view->storeDescription = $sellerInfo['store_description'];
				
			}
			
		}
		
 		// top friends like
		$TopFrdsLikeProd = $Product->GetTopFrndsLikeProduct();
		$this->view->frndstoplike = $TopFrdsLikeProd;
		
		
		//to get paypal details
		//to check paypal live 
		$paypalive = $Common->PaypalExist('paypal_live');
 		$PaypalLiveDetails = $Common->GetPaypalLiveDetails();
		
		if($paypalive['configuration_value'] == 1)
		{
			foreach($PaypalLiveDetails as $plive)
			{
						
				if($plive['configuration_key'] == 'paypallive_api_username')
				{
					$mysession->Api_Username = $plive['configuration_value'];
				}
				if($plive['configuration_key'] == 'paypallive_api_password')
				{
					$mysession->Api_Password = $plive['configuration_value'];
				}
				if($plive['configuration_key'] == 'paypallive_api_signature')
				{
					$mysession->Api_Signature = $plive['configuration_value'];
				}
			
			}
		}
		
		//to check paypal sendbox
		
		$sendbox = $Common->PaypalExist('paypal_sandbox');
		$PaypalSendboxDetails = $Common->GetSendboxDetails();
		
		if($sendbox['configuration_value'] == 1)
		{
			foreach($PaypalSendboxDetails as $sendbox)
			{
					
				if($sendbox['configuration_key'] == 'sandbox_api_username')
				{
					$mysession->Api_Username = $sendbox['configuration_value'];
				}
				if($sendbox['configuration_key'] == 'sandbox_api_password')
				{
					//print "sfd";
					$mysession->Api_Password = $sendbox['configuration_value'];
				}
				if($sendbox['configuration_key'] == 'sandbox_api_signature')
				{
					$mysession->Api_Signature = $sendbox['configuration_value'];
				}
			
			}//die;
		}
		//print $mysession->Api_Username;die;
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
