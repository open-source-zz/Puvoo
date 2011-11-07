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
        global $mysession, $user_profile, $facebook; 
		
 		$db = Zend_Db_Table::getDefaultAdapter();
		Zend_Registry::set('Db_Adapter', $db);
 		error_reporting(0);
		// Model Objects
 		$Category = new Models_Category();
		$Product = new Models_Product();
		$Common = new Models_Common();
		$Configuration = new Models_Configuration();

		$key_array = array(
							"application_hosting_url",
							"facebook_application_url",
							"application_api_id",
							"application_secret_key"
						  ); 

		$ConfigValue = $Configuration->getKeyValueForGroup(1,$key_array);
		
		foreach( $ConfigValue as $key => $val )
		{
			$$val["configuration_key"] = $val["configuration_value"];		
		}
		
		///////////////////////////   Facebook Login Authentication  //////////////////////////////
		
		$user = null; //facebook user uid
		
		try{
				include_once SITE_ROOT_PATH.'/../src/facebook.php';
		
			}catch(Exception $o){ }
		
			// Create our Application instance.
		$facebook = new Facebook(array(
			  'appId'  => $application_api_id,
			  'secret' => $application_secret_key,
			  'cookie' => true,
		));
		 
		//Facebook Authentication part
		$user       = $facebook->getUser();
		$loginUrl   = $facebook->getLoginUrl(
				array(
					'scope'         => 'email,publish_stream,user_birthday,user_location,user_work_history,user_about_me,user_hometown'
				)
		);
		
		
		if ($user) {
		
			try {
				// Proceed knowing you have a logged in user who's authenticated.
				$user_profile = $facebook->api('/me');
			
			} catch (FacebookApiException $e) {
				
				//you should use error_log($e); instead of printing the info on browser
				d($e);  // d is a debug function defined at the end of this file
				$user = null;
			}
		}
		
		if (isset($_GET['code'])){
		
				header("Location: ".$facebook_application_url);
				exit;
		}
		
		
		$Facebook_UserId = ''; 
		
		if(isset($user_profile)) {
			
			$Facebook_UserId = $user_profile['id']; 
			
		} 
		
		// Facebook User Id
		define('FACEBOOK_USERID', $Facebook_UserId);		
		define('FBUSER_ID', $Facebook_UserId);
		
		#-----------------------------------------------------------------------------------
		
		// Define facebook application url
		define('SITE_FB_URL', $facebook_application_url);
		
		// Define facebook application hosting url
  		define('SITE_AJX_URL', $application_hosting_url);
		
		// Defien facebook application api id
		define('FACEBOOK_APP_API_ID', $application_api_id);
		
		// Define facebook application secret key
		define('FACEBOOK_APP_SECRET_KEY', $application_secret_key); 
		
		// Define Path for images folder FB
		define('IMAGES_FB_PATH', INSTALL_DIR."public/images/fb");

		// Define Path for css folder for FB
		define('CSS_FB_PATH', INSTALL_DIR."public/css/fb" );

		// Define Path for css folder for FB
		define('JS_FB_PATH', INSTALL_DIR."public/js/fb" );
		
		
		$tempArray = str_replace(INSTALL_DIR."fb/","",$_SERVER["REQUEST_URI"]);
		
		$temp_url = SITE_FB_URL.$tempArray;
		
		define('FB_REDIRECT_URL', $temp_url);
  		
 		//Get Language array
		$lang = array();
		
		$lang_def = new Models_LanguageDefinitions();
		   
		define('Facebook_Authentication_URL',"https://www.facebook.com/dialog/oauth?client_id=124974344272864&redirect_uri=".SITE_FB_URL);
		
		$this->view->random = rand();
		
		
		$mysession->FbuserId = "";
		$admin_master = new Models_AdminMaster();
		$catid = $this->_request->getParam('id');
		
		$this->view->Facebook_userid = FBUSER_ID;
		$this->view->facebook_user_numeric_id = FBUSER_ID;
		
		if( $user_profile != NULL ) { 
		
			$where = '1 = 1';
			
			if($admin_master->ValidateTableField("facebook_id",$user_profile['id'],"facebook_user_master",$where)) {
			
				if($user_profile['email'] != '' ) {
					$name = '';
					if( isset($user_profile['name']) ){
						$name = $user_profile['name'];
					} 
				
					$fbArray = array( 
									'facebook_id'	 => $user_profile['id'],
									'facebook_email' =>	$user_profile['email'],
									'facebook_name'  =>	$name
								 );
					$admin_master->insertFacebookUser($fbArray);
				}
			}
			
		}
		
		if(isset($_GET['country']))
		{
			$mysession->CountryID =  $_GET['country'];
		}
		
		if(isset($mysession->CountryID))
		{
			$Country_id = $mysession->CountryID;
			$Site_config_value = $Common->GetConfigValue($Country_id);
			$mysession->default_Currency = $Site_config_value['currency_id'];
			$mysession->default_Language = $Site_config_value['language_id'];
			$mysession->default_vatrate = $Site_config_value['vat'];
			$mysession->default_Language_locale = $Site_config_value['code'];
			$this->view->sel_country = $Site_config_value['country_id'];
			
		}
		else
		{
 			$DefaultValues = $Common->GetDefaultConfigValue();
			$mysession->default_Currency = $DefaultValues['currency_id'];
			$mysession->default_Language = $DefaultValues['language_id'];
			$mysession->default_vatrate = $DefaultValues['vat'];
			$mysession->default_Language_locale = $DefaultValues['code'];
			$this->view->sel_country = $DefaultValues['country_id'];
		}
		
		$lang = $lang_def->getGroupLanguage('FB',$mysession->default_Language);
		
		//Set Default language for site
		$tr = new Zend_Translate(
			array(
			'adapter' => 'array',
			  'content' => $lang,
			  'locale'  => $mysession->default_Language_locale 
			)
		);
		
		Zend_Registry::set('Zend_Translate', $tr);

		// Define Currency Id
		define('DEFAULT_CURRENCY', $mysession->default_Currency);
		
		// Define Language Id
		define('DEFAULT_LANGUAGE', $mysession->default_Language);

		// Define vat price for current country
		define('DEFAULT_VAT_RATE', $mysession->default_vatrate);
		
		$currency_value = $Common->GetCurrencyValue(DEFAULT_CURRENCY);
		
		$mysession->currency_value = $currency_value['currency_value'];
		
		$mysession->currency_id = $currency_value['currency_id'];
		
		define('DEFAULT_CURRENCY_SYMBOL', $currency_value['currency_symbol']);
		
		define('DEFAULT_CURRENCY_CODE', $currency_value['currency_code']);
		
		$Default_Weight_Unit = $Common->GetWeigthUnit();
		
		define('DEFAULT_WEIGHT_UNIT', $Default_Weight_Unit);
		
		
		
		//Get Request
		$request = $this->getRequest();
		
		$cur_controller = $request->getControllerName();
 		
		$this->view->SerchCategoryCombo = $Category->getCateTree();
		
		//set default pagesize for admin section
	
		if(!isset($mysession->pagesize1)){
			
			$mysession->pagesize1 = 6;
		}
		
		//////////////////////////////////// Left Maenu //////////////////////////////////////
		
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
		
		$catMenuHtml = '';
		$catMenuHtml .= '<div id="ddsidemenubar" class="markermenu">';
		$catMenuHtml .= '<ul>';
		
		foreach($selectCat as $key => $val )
		{
			$Sub_Cat = $Category->GetSubCategory($val['category_id']);
			if( count($Sub_Cat) > 0 ) {
				$catMenuHtml .=  "<li><a class='sf-with-ul' href='".SITE_FB_URL."category/subcat/id/".$val['category_id']."' target='_top' rel='Category_".$val['category_id']."'>".$val["category_name"]."</a></li>";
				
			} else {
				
				$catMenuHtml .=  "<li><a class='sf-with-ul' href='".SITE_FB_URL."category/subcat/id/".$val['category_id']."' target='_top' >".$val["category_name"]."</a></li>";
			}
		}
		
		$catMenuHtml .= '</ul>';
		$catMenuHtml .= '</div>';
		
		// LEFT MENU
		$this->view->MainCategoryMenu = $catMenuHtml;
		
		$this->view->test = $Category->getCategoryTreeForMenu($mysession->default_Language_locale);
		
		
 		//To know how many product in Cart
		$Cart = new Models_Cart();
		$CartDetails = $Cart->GetProductInCart(FBUSER_ID);
		
		// Country combo
		$this->view->CountryCombo = $Cart->GetCountry();
		$this->view->ShipCountryCombo = $Cart->GetCountryCombo();
		
		if($CartDetails){
			$this->view->cartItems = $CartDetails;
			
			
			$cartId = '';
			$Price = 0;
			$CartTotal = '';
			foreach($CartDetails as $value)
			{
 
  				$Price += $value['price']*$value['product_qty'];
				$cartId = $value['cart_id'];
				$CartTotal += $value['product_qty'];
				
				$this->view->cartuserId = $value['user_id'];
				
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
			
			// State combo
			$this->view->StateCombo = $Cart->GetState($this->view->countryId);
			/// Currency
			$this->view->CurrencyCombo = $Common->GetCurrency();
			
			
			if($this->view->cartuserId != '' ){
				
				$paypalUrl = $Common->GetConfigureValue('paypal_url');
				$PaypalDetails = $Common->GetPaypalDetails($this->view->cartuserId);
				
				$this->view->Paypal_Url = $paypalUrl['configuration_value'];
				$this->view->Api_Username = $PaypalDetails['paypal_email'];
				$this->view->Api_Password = $PaypalDetails['paypal_password'];
				$this->view->Api_Signature = $PaypalDetails['paypal_signature'];
			}
			
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

				if($sellerInfo['StoreTermsPolicy'] != '')
				{
					$this->view->terms = $sellerInfo['StoreTermsPolicy'];
				}else{
					$this->view->terms = $sellerInfo['store_terms_policy'];
				}
				if($sellerInfo['Returnpolicy'] != '')
				{
					$this->view->returnPolicy = $sellerInfo['Returnpolicy'];
				}else{
					$this->view->returnPolicy = $sellerInfo['return_policy'];
				}
				if($sellerInfo['StoreDesc'] != '')
				{
					$this->view->storeDescription = $sellerInfo['StoreDesc'];
				}else{
					$this->view->storeDescription = $sellerInfo['store_description'];
				}
 				
			}
			
		}
		
 		// top friends like
		$TopFrdsLikeProd = $Product->GetTopFrndsLikeProduct();
		$this->view->frndstoplike = $TopFrdsLikeProd;
		
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
