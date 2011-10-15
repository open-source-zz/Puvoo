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



class Fb_CartController extends FbCommonController

{
	/**
	* Function init
	*
	* This function is used for initialization. Also include necessary javascript files.
	*
	* Date Created: 2011-09-01
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
	* Function indexAction
	*
	* This function is used for displays the cart details. 
	*
	* Date Created: 2011-09-01
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
 		$this->_redirect("fb/");
	}
	 
	/**
	* Function deletecartproductAction
	*
	* This function is used for delete the product of cart. 
	*
	* Date Created: 2011-09-01
	*
	* @access public
	* @param ()  - No parameter
	* @return (void) - Return void
	*
	* @author Jayesh
	*  
	* @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	**/  
	public function deletecartproductAction()
	{
		global $mysession;
		$Cart = new Models_Cart();
		$prodId = $_POST['prodid'];
		$CartId = $_POST['cartid'];
		$FbuId = $_POST['fbuid'];
		//Delete Product
		$CartDetails = $Cart->GetCartDetailId($prodId,$FbuId);
		$cartDetailId = $CartDetails['cart_detail_id'];
		
		$result = $Cart->DeleteCartProduct($prodId,$CartId,$cartDetailId);
		$CartCnt = array();
		$CartCnt = count($Cart->GetProductInCart($FbuId));
		if($result == true){
			echo json_encode($CartCnt);die;
		}
		
	}	 

	/**
	* Function updateshippingAction
	*
	* This function is used for update shipping information in cart_maste table. 
	*
	* Date Created: 2011-09-05
	*
	* @access public
	* @param ()  - No parameter
	* @return (void) - Return void
	*
	* @author Jayesh
	*  
	* @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	**/  
	public function updateshippingAction()
	{
		
		global $mysession;
		
		//Disable layout
		$this->_helper->layout()->disableLayout();
		
		$Cart = new Models_Cart();
		$Common = new Models_Common();
		
		//$prodId = $_POST['prodid'];
		$CartId = $_POST['cartid'];
		$FirstName = $_POST['fname'];
		$LastName = $_POST['lname'];
		$Email = $_POST['email'];
		$Phone = $_POST['telephone'];
		//$CarrEmail = $_POST['carr_email'];
		$AddressType = $_POST['add_type'];
		$Country = $_POST['country'];
		$Address = $_POST['add'];
		$City = $_POST['city'];
		$State = $_POST['state'];
		$PostCode = $_POST['postcode'];
		$IsBilling = $_POST['isbilling'];
		$fbuid = $_POST['fbuid'];
		//print $_POST['isbilling'];die;
		if($IsBilling = 'true')
		{
			$ShippingDetail = array(
				'shipping_user_fname' 		 => $FirstName,
				'shipping_user_lname'  		 => $LastName, 
				'shipping_user_email' 		 => $Email,
				'shipping_user_telephone'	 => $Phone,
				'shipping_user_address_type' => $AddressType,
				'shipping_user_country_id'   => $Country,
				'shipping_user_address'		 => $Address,
				'shipping_user_city' 		 => $City,	
				'shipping_user_state_id'  	 => $State,
				'shipping_user_zipcode'		 => $PostCode,	
				'billing_user_fname' 		 => $FirstName,
				'billing_user_lname'  		 => $LastName, 
				'billing_user_email' 		 => $Email,
				'billing_user_telephone'	 => $Phone,
				'billing_user_address_type'  => $AddressType,
				'billing_user_country_id'    => $Country,
				'billing_user_address'		 => $Address,
				'billing_user_city' 		 => $City,	
				'billing_user_state_id'  	 => $State,
				'billing_user_zipcode'		 => $PostCode	
			);
		
		}else{
			$ShippingDetail = array(
				'shipping_user_fname' 		 => $FirstName,
				'shipping_user_lname'  		 => $LastName, 
				'shipping_user_email' 		 => $Email,
				'shipping_user_telephone'	 => $Phone,
				'shipping_user_address_type' => $AddressType,
				'shipping_user_country_id'   => $Country,
				'shipping_user_address'		 => $Address,
				'shipping_user_city' 		 => $City,	
				'shipping_user_state_id'  	 => $State,
				'shipping_user_zipcode'		 => $PostCode	
			);
		}
		//Delete Product
		//print $CartId;die;
		$result = $Cart->UpdateShippingInfo($ShippingDetail,$CartId);
		
		$ShippingInfo = $Cart->GetShippingInfo($CartId);
		
		$CartDetails = $Cart->GetProductInCart($fbuid);
		
		$CountryCode = $Cart->GetCountryCode($ShippingInfo['shipping_user_country_id']);
		
		$this->view->CountryCode = $CountryCode['country_iso2'];
		$Ship_Method_Combo = array();
		$Ship_Handel_Day = array();
		
		$this->view->cartItems = $CartDetails;
		
		$cartId = '';
		$Price = 0;
		$CartTotal = '';

		for($j=0; $j < count($CartDetails); $j++)
		{
		
			$Price += $CartDetails[$j]['product_price']*$CartDetails[$j]['product_qty'];
			$cartId = $CartDetails[$j]['cart_id'];
			$CartTotal += $CartDetails[$j]['product_qty'];
			
			$currency_symbol = $Common->GetCurrencyValue($CartDetails[$j]['currId']);
			//print $Price;die;
			$this->view->currency_symbol = $currency_symbol['currency_symbol'];
			
 			$this->view->CartCnt = count($CartDetails);
 			$this->view->TotalPrice = $Price;
			$this->view->cartId = $cartId;
			$mysession->cartId = $cartId;
			$cartuserId = $CartDetails[$j]['user_id'];
			$this->view->cartuserId = $cartuserId;
			$this->view->currencyId = $currency_symbol['currency_id'];
			
			$this->view->cartuserId = $CartDetails[$j]['user_id'];
			// For Shipping Method for each product
			$ShippingMetodInfo = $Cart->GetShippingMethod($CartDetails[$j]['user_id']);
			
			$start = 0;
			$Ship_Method_Combo_Str = array();
			
			foreach($ShippingMetodInfo as $ship)
			{
 				
				$shippingZone = explode(',',$ship['zone']);
				
 				for($k=0; $k < count($shippingZone); $k++)
				{
					//echo "<pre>";
					//print_r($shippingZone[$k]);
					
 					
					if($shippingZone[$k] == 'Worldwide')
					{
					
						$Ship_Method_Combo_Str[$ship['shipping_method_id']] = $ship['shipping_method_name'];
						
					}else
					{
						$shippingState = explode(':',$shippingZone[$k]);
						foreach($shippingState as $key => $val )
						{
							if($key == 0 ) {
								
								if($val == $CountryCode['country_iso2']) {
									
									$Ship_Method_Combo_Str[$ship['shipping_method_id']] = $ship['shipping_method_name'];
									
								}
								
							} else {
							
								if( $val == $ShippingInfo['state_name'] ) {
							
									$Ship_Method_Combo_Str[$ship['shipping_method_id']] = $ship['shipping_method_name'];
								}
							
							}
							
						}
					}
				}
				$Ship_handling_day = $ship['shipping_handling_time'];
			}//die;
			
			$Ship_Handel_Day[$CartDetails[$j]['product_id']] = $Ship_handling_day;
			
			$Ship_Method_Combo[$CartDetails[$j]['product_id']] = $Ship_Method_Combo_Str;
			
			//to get paypal details
			$paypalUrl = $Common->GetPaypalUrl('paypal_url');
			
			$PaypalDetails = $Common->GetPaypalDetails($this->view->cartuserId);
	
			$mysession->Paypal_Url = $paypalUrl['configuration_value'];
			$mysession->Api_Username = $PaypalDetails['paypal_email'];
			$mysession->Api_Password = $PaypalDetails['paypal_password'];
			$mysession->Api_Signature = $PaypalDetails['paypal_signature'];
			// For Tax rate for each product
			$TaxInfo = $Cart->GetTaxName($CartDetails[$j]['user_id']);
			
 			$tax_rate = array();
			foreach($TaxInfo as $tax)
			{
				$taxZone = explode(',',$tax['zone']);
 				for($k=0; $k < count($taxZone); $k++)
				{
 					$taxState = explode(':',$taxZone[$k]);
 					foreach($taxState as $key => $val )
					{
						if($key == 0 ) {
							
							if($val == $CountryCode['country_iso2']) {
								
								$tax_rate[$tax['tax_rate_id']] = $tax['rate'];
								
							}
							
						} else {
						
							if( $val == $ShippingInfo['state_name'] ) {
						
								$tax_rate[$tax['tax_rate_id']] = $tax['rate'];
							}
						
						}
						
					}
				}
			}
			
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
		
 		$this->view->taxRate = $taxe_rate_value;
		$this->view->ship_handel_day = $Ship_Handel_Day;
		$this->view->Ship_Method_Combo = $combo_array;

		//echo json_encode($IsBilling);die;
		
		
	}	

	/**
	* Function updatebillingAction
	*
	* This function is used for update billing information in cart_maste table. 
	*
	* Date Created: 2011-09-29
	*
	* @access public
	* @param ()  - No parameter
	* @return (void) - Return void
	*
	* @author Jayesh
	*  
	* @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	**/  
	public function updatebillingAction()
	{
	
		global $mysession;
		$Cart = new Models_Cart();
		$Common = new Models_Common();
		$this->_helper->layout()->disableLayout();
		//$prodId = $_POST['prodid'];
		$CartId = $_POST['Bill_cartid'];
		$FirstName = $_POST['Bill_fname'];
		$LastName = $_POST['Bill_lname'];
		$Email = $_POST['Bill_email'];
		$Phone = $_POST['Bill_telephone'];
		//$CarrEmail = $_POST['carr_email'];
		$AddressType = $_POST['Bill_add_type'];
		$Country = $_POST['Bill_country'];
		$Address = $_POST['Bill_add'];
		$City = $_POST['Bill_city'];
		$State = $_POST['Bill_state'];
		$PostCode = $_POST['Bill_postcode'];
		$fbuid = $_POST['fbuid'];
		
			$BillingDetail = array(
				'billing_user_fname' 		 => $FirstName,
				'billing_user_lname'  		 => $LastName, 
				'billing_user_email' 		 => $Email,
				'billing_user_telephone'	 => $Phone,
				'billing_user_address_type'  => $AddressType,
				'billing_user_country_id'    => $Country,
				'billing_user_address'		 => $Address,
				'billing_user_city' 		 => $City,	
				'billing_user_state_id'  	 => $State,
				'billing_user_zipcode'		 => $PostCode	
			);
		
		//Delete Product
		//print $CartId;die;
		$result = $Cart->UpdateShippingInfo($BillingDetail,$CartId);
  		
		$ShippingInfo = $Cart->GetShippingInfo($CartId);
		
		$CartDetails = $Cart->GetProductInCart($fbuid);
		
		$CountryCode = $Cart->GetCountryCode($ShippingInfo['shipping_user_country_id']);
		
		$this->view->CountryCode = $CountryCode['country_iso2'];
		$Ship_Method_Combo = array();
		$Ship_Handel_Day = array();
		
		$this->view->cartItems = $CartDetails;
		
		$cartId = '';
		$Price = 0;
		$CartTotal = '';

		for($j=0; $j < count($CartDetails); $j++)
		{
		
			$Price += $CartDetails[$j]['product_price']*$CartDetails[$j]['product_qty'];
			$cartId = $CartDetails[$j]['cart_id'];
			$CartTotal += $CartDetails[$j]['product_qty'];
			
			$currency_symbol = $Common->GetCurrencyValue($CartDetails[$j]['currId']);
			//print $Price;die;
			$this->view->currency_symbol = $currency_symbol['currency_symbol'];
			
 			$this->view->CartCnt = count($CartDetails);
 			$this->view->TotalPrice = $Price;
			$this->view->cartId = $cartId;
			$mysession->cartId = $cartId;
			$cartuserId = $CartDetails[$j]['user_id'];
			$this->view->cartuserId = $cartuserId;
			$this->view->currencyId = $currency_symbol['currency_id'];
			
			$this->view->cartuserId = $CartDetails[$j]['user_id'];
			// For Shipping Method for each product
			$ShippingMetodInfo = $Cart->GetShippingMethod($CartDetails[$j]['user_id']);
			
			
			$start = 0;
			$Ship_Method_Combo_Str = array();
			
			foreach($ShippingMetodInfo as $ship)
			{
 				
				$shippingZone = explode(',',$ship['zone']);
 				for($k=0; $k < count($shippingZone); $k++)
				{
					
 					
					if($shippingZone[$k] == 'Worldwide')
					{
					
						$Ship_Method_Combo_Str[$ship['shipping_method_id']] = $ship['shipping_method_name'];
						
					}else
					{
						$shippingState = explode(':',$shippingZone[$k]);
						foreach($shippingState as $key => $val )
						{
							if($key == 0 ) {
								
								if($val == $CountryCode['country_iso2']) {
									
									$Ship_Method_Combo_Str[$ship['shipping_method_id']] = $ship['shipping_method_name'];
									
								}
								
							} else {
							
								if( $val == $ShippingInfo['state_name'] ) {
							
									$Ship_Method_Combo_Str[$ship['shipping_method_id']] = $ship['shipping_method_name'];
								}
							
							}
							
						}
					}
				}
				$Ship_handling_day = $ship['shipping_handling_time'];
			}
			
			$Ship_Handel_Day[$CartDetails[$j]['product_id']] = $Ship_handling_day;
			
			$Ship_Method_Combo[$CartDetails[$j]['product_id']] = $Ship_Method_Combo_Str;
			
			//to get paypal details
			$paypalUrl = $Common->GetPaypalUrl('paypal_url');
			
			$PaypalDetails = $Common->GetPaypalDetails($this->view->cartuserId);
	
			$mysession->Paypal_Url = $paypalUrl['configuration_value'];
			$mysession->Api_Username = $PaypalDetails['paypal_email'];
			$mysession->Api_Password = $PaypalDetails['paypal_password'];
			$mysession->Api_Signature = $PaypalDetails['paypal_signature'];
			// For Tax rate for each product
			$TaxInfo = $Cart->GetTaxName($CartDetails[$j]['user_id']);
			
 			$tax_rate = array();
			foreach($TaxInfo as $tax)
			{
				$taxZone = explode(',',$tax['zone']);
 				for($k=0; $k < count($taxZone); $k++)
				{
 					$taxState = explode(':',$taxZone[$k]);
 					foreach($taxState as $key => $val )
					{
						if($key == 0 ) {
							
							if($val == $CountryCode['country_iso2']) {
								
								$tax_rate[$tax['tax_rate_id']] = $tax['rate'];
								
							}
							
						} else {
						
							if( $val == $ShippingInfo['state_name'] ) {
						
								$tax_rate[$tax['tax_rate_id']] = $tax['rate'];
							}
						
						}
						
					}
				}
			}
			
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
		
 		$this->view->taxRate = $taxe_rate_value;
		$this->view->ship_handel_day = $Ship_Handel_Day;
		$this->view->Ship_Method_Combo = $combo_array;
		
		
	}	
	
	/**
	* Function updateshippingAction
	*
	* This function is used for update shipping information in cart_maste table. 
	*
	* Date Created: 2011-09-05
	*
	* @access public
	* @param ()  - No parameter
	* @return (void) - Return void
	*
	* @author Jayesh
	*  
	* @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	**/  
	public function getcountrystateAction()
	{
		global $mysession;
		$Cart = new Models_Cart();
		$StateCombo = array();
		$countryId = $this->_request->getPost('countryid');
		$StateCombo = $Cart->GetState($countryId);
		
		//print_r($StateCombo);die;
		echo json_encode($StateCombo);die;
  	} 

	/**
	* Function updatecartAction
	*
	* This function is used for update cart information. 
	*
	* Date Created: 2011-09-21
	*
	* @access public
	* @param ()  - No parameter
	* @return (void) - Return void
	*
	* @author Jayesh
	*  
	* @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	**/  
	public function updatecartAction()
	{
		global $mysession;
		$Cart = new Models_Cart();
		$Product = new Models_Product();
 		
		$cartId = $_POST['cartid'];
		$prodId = $_POST['prodid'];
		$fbuid = $_POST['fbuid'];
		$prodPrice = $_POST['prodPrice'];
		$prodData = $Product->GetProductDetails($prodId);
		$updateData = array(
 				'product_qty' => $_POST['prodQty'],
				'product_total_cost' => $prodPrice*$_POST['prodQty']
 			);
		//print_r($updateData);die;
		$UpdateCartDetails = $Cart->UpdateCart($updateData,$cartId,$prodId);
		//print_r($StateCombo);die;
		$CartData = $Cart->GetProductInCart($fbuid);
		//print "<pre>";
		//print_r($CartData);die;
		echo json_encode($CartData);die;
  	} 

	/**
	* Function gethippingcostAction
	*
	* This function is used for get the shipping cost as per shipping method. 
	*
	* Date Created: 2011-09-22
	*
	* @access public
	* @param ()  - No parameter
	* @return (void) - Return void
	*
	* @author Jayesh
	*  
	* @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	**/  
	public function getshippingcostAction()
	{
		global $mysession;
		$Cart = new Models_Cart();
		$Product = new Models_Product();
 		$Common = new Models_Common();
		$methodId = $_POST['shipmethodid'];
		$prodId = $_POST['prodid'];
		$filter = new Zend_Filter_StripTags();	
		
 		$current_currencyId = $filter->filter(trim($this->_request->getPost('current_currencyId')));
		$current_curr_data = $Common->GetCurrencyValue($current_currencyId);
		
		$prodData = $Product->GetProductDetails($prodId);
		
 		$ShippingCostDetails = $Cart->GetShippingCost($methodId);
 		//echo "<pre>";
		//print_r($ShippingCostDetails);die;
		$shippingPriceArray = explode(',',$ShippingCostDetails['price']);
		
		$start = 0;
		
		foreach($shippingPriceArray as $val)
		{
			$shippingRangeArray = explode(':',$val);
			
			if($prodData['product_weight'] > $start && $prodData['product_weight'] <= $shippingRangeArray[0])
			{
				
				$ship_Price = $shippingRangeArray[1];
 			}
			
			$start = $shippingRangeArray[0];
			
 		}
		if($ShippingCostDetails)
		{
			
			$Shipping_Price = round(($ship_Price*$current_curr_data['currency_value'])/ $ShippingCostDetails['currency_value'],2);
			$ShippingValue = $Shipping_Price;
			
			
		}else{
			$ShippingValue = 0;
		}
 		echo json_encode($ShippingValue);die;
  	} 


	/**
	* Function updateshippingcostAction
	*
	* This function is used for update the shipping cost as per shipping method. 
	*
	* Date Created: 2011-09-22
	*
	* @access public
	* @param ()  - No parameter
	* @return (void) - Return void
	*
	* @author Jayesh
	*  
	* @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	**/  
	public function updateshippingcostAction()
	{
		global $mysession;
		$Cart = new Models_Cart();
		$Product = new Models_Product();
		
 		
		$methodId = $_POST['shipmethodid'];
		$prodId = $_POST['prodid'];
		$fbuid = $_POST['fbuid'];
		
		$prodData = $Product->GetProductDetails($prodId);
		$updateData = array(
 				'shipping_method_id' => $methodId,
				'shipping_cost' => $prodData['product_price']*$_POST['prodQty']
 			);
		//print_r($updateData);die;
		$UpdateCartDetails = $Cart->UpdateShippingCost($updateData,$cartId,$prodId);
		//print_r($StateCombo);die;
		$CartData = $Cart->GetProductInCart($fbuid);
		//print "<pre>";
		//print_r($CartData);die;
		echo json_encode($CartData);die;
  	} 

	/**
	* Function updateshippingmethodAction
	*
	* This function is used for update the shipping method and its detail. 
	*
	* Date Created: 2011-10-07
	*
	* @access public
	* @param ()  - No parameter
	* @return (void) - Return void
	*
	* @author Jayesh
	*  
	* @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	**/  
	public function updateshippingmethodAction()
	{
		global $mysession;
		$Cart = new Models_Cart();
		$Product = new Models_Product();
		$filter = new Zend_Filter_StripTags();
		$Common = new Models_Common();
 		
		$cartId = $_POST['cartid'];
		$fbuid = $_POST['fbuid'];
		$prodIds = explode(',',$_POST['prodid']);
		$methodIds = explode(',',$_POST['methodid']);
		
		$taxrates = explode(',',$_POST['taxrate']);
		
 		$current_currencyId = $filter->filter(trim($this->_request->getPost('current_currencyId')));
		$current_curr_data = $Common->GetCurrencyValue($current_currencyId);
		//print_r($taxrates);die;
		$product_total_price = 0;
		$product_shipping_price = 0;
		$product_tax_price = 0;
 		for($i=0; $i<count($prodIds); $i++)
		{
				
			//$taxRate = $filter->filter(trim($taxrates[$i]));
			$taxRate = preg_replace("/[^0-9,.]/", '', $taxrates[$i]);
			
			$prodData = $Product->GetProductDetails($prodIds[$i]);
			if($methodIds[$i])
			{
				$ShippingCostDetails = $Cart->GetShippingCost($methodIds[$i]);
				
				$shippingPriceArray = explode(',',$ShippingCostDetails['price']);
				
				$start = 0;
				
				foreach($shippingPriceArray as $val)
				{
					$shippingRangeArray = explode(':',$val);
					
					if($prodData['product_weight'] > $start && $prodData['product_weight'] <= $shippingRangeArray[0])
					{
						
						$ship_Price = $shippingRangeArray[1];
					}
					
					$start = $shippingRangeArray[0];
					
				}
			}
			
			if($ship_Price)
			{
				
				$Shipping_Price = round(($ship_Price*$current_curr_data['currency_value'])/ $ShippingCostDetails['currency_value'],2);
				$ShippingValue = $Shipping_Price;
				
				
			}else{
				$ShippingValue = 0;
			}
			
			$prodData = $Cart->GetCartProductDetail($prodIds[$i],$cartId);
			$total_cost = round((($prodData['product_price']+$ShippingValue)*$prodData['product_qty'])+$taxRate,2);
			
			$product_total_price = $product_total_price+($prodData['product_price']*$prodData['product_qty']);
			
			$product_shipping_price = $product_shipping_price+($ShippingValue*$prodData['product_qty']);
			$product_tax_price = $product_tax_price+$taxRate;
			
			$updateData = array(
					'tax_rate' => $taxRate,
					'shipping_method_id' => $methodIds[$i],
					'shipping_cost' => $ShippingValue,
					'product_total_cost' => $total_cost
				);
			
			$UpdateCartDetails = $Cart->UpdateCart($updateData,$cartId,$prodIds[$i]);
			
			
		}
		
		$order_total_amt = $product_total_price+$product_shipping_price+$product_tax_price;
		$updatePrice = array(
					'total_product_amount' => round($product_total_price,2),
					'shipping_cost' => $product_shipping_price,
					'total_tax_cost' => round($product_tax_price,2),
					'total_order_amount' => $order_total_amt
				);
		
		$Cart->UpdateCartMaster($updatePrice,$cartId,$fbuid);
		
		$CartData = $Cart->GetProductInCart($fbuid);
		$Final_Amount = 0;
		foreach($CartData as $value)
		{
			$Final_Amount += round($value['product_total_cost'],2);
		
		}
		//print $Final_Amount;die;
		echo json_encode($Final_Amount);die;
  	} 
	
	
	/**
	* Function updatecartcurrency
	*
	* This function is used for update cart currency. 
	*
	* Date Created: 2011-10-12
	*
	* @access public
	* @param ()  - No parameter
	* @return (void) - Return void
	*
	* @author Jayesh
	*  
	* @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	**/  
	public function updatecartcurrencyAction()
	{
		global $mysession;
		$this->_helper->layout()->disableLayout();
		$Cart = new Models_Cart();
		$Product = new Models_Product();
 		$Common = new Models_Common();
		$filter = new Zend_Filter_StripTags();	
		
		$cart_currencyId = $filter->filter(trim($this->_request->getPost('currencyId'))); 
		$current_currencyId = $filter->filter(trim($this->_request->getPost('current_currencyId')));
		$fbuid = $filter->filter(trim($this->_request->getPost('fbuid')));
		//print $current_currencyId;die;
		$curr_data = $Common->GetCurrencyValue($cart_currencyId);
		$current_curr_data = $Common->GetCurrencyValue($current_currencyId);
 		$cartData = $Cart->GetProductInCart($fbuid,$curr_data['currency_value'],$current_curr_data['currency_value']);
		//echo "<pre>";
		//print_r($cartData);die;
		foreach($cartData as $key=>$value)
		{
		
			$updateCuurency = array(
							'currency_id' => $cart_currencyId
			);
			
			$Cart->UpdateCartMaster($updateCuurency,$value['cart_id'],$fbuid);
			
			$updateData = array(
					'product_price' => $value['Prod_convert_price'],
					'product_qty' => $value['product_qty'],
					'product_total_cost' => $value['Prod_convert_price']*$value['product_qty']
				);
			//print_r($updateData);die;
			$UpdateCartDetails = $Cart->UpdateCart($updateData,$value['cart_id'],$value['product_id']);
		}
		
		$CartDetails = $Cart->GetProductInCart($fbuid);
		
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
			
			$this->view->CurrencyCombo = $Common->GetCurrency();
		}
  	} 
	
}

?>