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
		define('IMAGES_FB_PATH', INSTALL_DIR."images/fb");

		// Define Path for css folder for FB
		define('CSS_FB_PATH', INSTALL_DIR."css/fb" );

		// Define Path for css folder for FB
		define('JS_FB_PATH', INSTALL_DIR."js/fb" );
		
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
 		$Url = explode('/',$_SERVER['REQUEST_URI']);
		
 		if($Url[3]!='category' && $catid != '')
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
	
		if(isset($mysession->pagesize)){
			
			$mysession->pagesize1 = 6;
		}
		// Left Maenu
		//to get all main category
		$Allcategory = $Category->GetMainCategory();
		
		$tno = count($Allcategory);
		
		$eno = $tno % 4;
		
		$fno = ($tno - $eno ) / 4;
		
		$no_array = array();
		
		$no_array[0] = $fno;
		$no_array[1] = $fno;
		$no_array[2] = $fno;
		$no_array[3] = $fno;
		
		
		for($i=0; $i< $eno; $i++) 
		{
			$no_array[$i] += 1; 	
		}
		
		$new_no = $no_array[0];
		// i m here
		// loop the array by $new_no
		 
		$menu_array = array();
		$increment_no = 0;
		foreach( $no_array as $key => $val )
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
		
		
		$this->view->Mycategory = $menu_array;
		$this->view->Allcategory = $Allcategory;
		
		$cat = array();

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
			if($cartId){
				
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
			}
			
			// Country combo
			$this->view->CountryCombo = $Cart->GetCountry();
			// State combo
			$this->view->StateCombo = $Cart->GetState($this->view->countryId);
			
			// get country code
			$CountryCode = $Cart->GetCountryCode($this->view->countryId);
			
			$this->view->CountryCode = $CountryCode['country_iso2'];
			$Ship_Method_Combo = array();
			$Ship_Handel_Day = array();
			
			for($j=0; $j < count($CartDetails); $j++)
			{
				// For Shipping Method for each product
 				$ShippingMetodInfo = $Cart->GetShippingMethod($CartDetails[$j]['user_id']);
				
				$start = 0;
				$Ship_Method_Combo_Str = array();
				
				foreach($ShippingMetodInfo as $ship)
				{
					//print "<pre>";
					//print_r($ship);
					
					$shippingZone = explode(',',$ship['zone']);
					//print (count($shippingZone))."<br>";
					for($k=0; $k < count($shippingZone); $k++)
					{
						//print_r($shippingZone[$k])."<br>";
						$shippingState = explode(':',$shippingZone[$k]);
						
						foreach($shippingState as $key => $val )
						{
							if($key == 0 ) {
								
								if($val == $CountryCode['country_iso2']) {
								 	
									$Ship_Method_Combo_Str[$ship['shipping_method_id']] = $ship['shipping_method_name'];
									
								}
								
							} else {
							
								if( $val == $this->view->stateName ) {
							
									$Ship_Method_Combo_Str[$ship['shipping_method_id']] = $ship['shipping_method_name'];
								}
							
							}
							
						}//die;
					}
					$Ship_handling_day = $ship['shipping_handling_time'];
				}
				
				$Ship_Handel_Day[$CartDetails[$j]['product_id']] = $Ship_handling_day;
				
				$Ship_Method_Combo[$CartDetails[$j]['product_id']] = $Ship_Method_Combo_Str;
				
				// For Tax rate for each product
				$TaxInfo = $Cart->GetTaxName($CartDetails[$j]['user_id']);
				
				//print "<pre>";
				//print_r($TaxInfo);
				$tax_rate = array();
				foreach($TaxInfo as $tax)
				{
					//print "<pre>";
					//print_r($ship);
					//$taxName[] = $tax['tax_name'];
					$taxZone = explode(',',$tax['zone']);
					//print (count($shippingZone))."<br>";
					for($k=0; $k < count($taxZone); $k++)
					{
						//print_r($shippingZone[$k])."<br>";
						$taxState = explode(':',$taxZone[$k]);
						//print "<pre>";
						//print_r($taxState);
						foreach($taxState as $key => $val )
						{
							if($key == 0 ) {
								
								if($val == $CountryCode['country_iso2']) {
								 	
									$tax_rate[$tax['tax_rate_id']] = $tax['rate'];
									
								}
								
							} else {
							
								if( $val == $this->view->stateName ) {
							
									$tax_rate[$tax['tax_rate_id']] = $tax['rate'];
								}
							
							}
							
						}//die;
					}
				}
				//die;
				//print_r($tax_rate);
				$taxe_rate_value[$CartDetails[$j]['product_id']] = $tax_rate;

 			}
			
			$combo_array = array();
			foreach( $Ship_Method_Combo as $key => $val ) 
			{
				$str = '';
				foreach( $val as $key2 => $val2 ) 
				{
					$str .= "<option name='' value='".$key2."' onclick='GetShippingCost(".$key2.",".$key.")'>".$val2."</option>";
					
				}
				$combo_array[$key] = $str;
			}
			
			//print "<pre>";
			//print_r($combo_array);die;
			$this->view->taxRate = $taxe_rate_value;
 			$this->view->ship_handel_day = $Ship_Handel_Day;
			$this->view->Ship_Method_Combo = $combo_array;
			
			$_SESSION['Payment_Amount'] = '1000';
			
			
			
		}
		
		//print_r($Url);
//		if($Url[3] == 'product' || $Url[3] == 'retailer')
//		{
//			$userId = '';
//			$Id = $this->_request->getParam('id');
//			if($Url[3] == 'product')
//			{
//				$productDetails = $Product->GetProductDetails($Id);
//				$userId = $productDetails['user_id'];
//			}
//			if($Url[3] == 'retailer')
//			{
//				$userId = $Id;
//			}
//			//print $userId;die;
//			$sellerInfo = $Product->GetSellerInformation($userId);
//			
//			//print_r($sellerInfo);die;
//			$this->view->terms = $sellerInfo['store_terms_policy'];
//			$this->view->returnPolicy = $sellerInfo['return_policy'];
//			$this->view->storeDescription = $sellerInfo['store_description'];
//		}
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