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
		//Delete Product
		$CartDetails = $Cart->GetCartDetailId($prodId);
		$cartDetailId = $CartDetails['cart_detail_id'];
		
		$result = $Cart->DeleteCartProduct($prodId,$CartId,$cartDetailId);
		$CartCnt = array();
		$CartCnt = count($Cart->GetProductInCart());
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
		
		$CartDetails = $Cart->GetProductInCart();
		
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
			//print $Price;die;
			$this->view->CartCnt = $CartTotal;
 			$this->view->TotalPrice = $Price;
			$this->view->cartId = $cartId;
			$mysession->cartId = $cartId;

			// For Shipping Method for each product
			$ShippingMetodInfo = $Cart->GetShippingMethod($CartDetails[$j]['user_id']);
			
			$start = 0;
			$Ship_Method_Combo_Str = array();
			
			foreach($ShippingMetodInfo as $ship)
			{
 				
				$shippingZone = explode(',',$ship['zone']);
 				for($k=0; $k < count($shippingZone); $k++)
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
				$Ship_handling_day = $ship['shipping_handling_time'];
			}
			
			$Ship_Handel_Day[$CartDetails[$j]['product_id']] = $Ship_handling_day;
			
			$Ship_Method_Combo[$CartDetails[$j]['product_id']] = $Ship_Method_Combo_Str;
			
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
  		
		if($result == true)
		{
			echo json_encode("Success");die;
		}else{
			echo json_encode("Fail");die;
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
		$prodPrice = $_POST['prodPrice'];
		$prodData = $Product->GetProductDetails($prodId);
		$updateData = array(
 				'product_qty' => $_POST['prodQty'],
				'product_total_cost' => $prodPrice*$_POST['prodQty']
 			);
		//print_r($updateData);die;
		$UpdateCartDetails = $Cart->UpdateCart($updateData,$cartId,$prodId);
		//print_r($StateCombo);die;
		$CartData = $Cart->GetProductInCart();
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
 		
		$methodId = $_POST['shipmethodid'];
		$prodId = $_POST['prodid'];
		
		$prodData = $Product->GetProductDetails($prodId);
		
 		$ShippingCostDetails = $Cart->GetShippingCost($methodId);
 		
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
			$ShippingValue = $ship_Price;
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
		
		$prodData = $Product->GetProductDetails($prodId);
		$updateData = array(
 				'shipping_method_id' => $methodId,
				'shipping_cost' => $prodData['product_price']*$_POST['prodQty']
 			);
		//print_r($updateData);die;
		$UpdateCartDetails = $Cart->UpdateShippingCost($updateData,$cartId,$prodId);
		//print_r($StateCombo);die;
		$CartData = $Cart->GetProductInCart();
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
 		
		$cartId = $_POST['cartid'];
		$prodIds = explode(',',$_POST['prodid']);
		$methodIds = explode(',',$_POST['methodid']);
		
		$taxrates = explode(',',$_POST['taxrate']);
		
 		for($i=0; $i<count($prodIds); $i++)
		{
				
			$taxRate = $filter->filter(trim($taxrates[$i]));
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
			else{
				$ship_Price = 0;
			}
			
			$prodData = $Cart->GetCartProductDetail($prodIds[$i],$cartId);
			$total_cost = round((($prodData['product_price']+$ship_Price)*$prodData['product_qty'])+$taxRate);
			$updateData = array(
					'tax_rate' => round($taxRate),
					'shipping_method_id' => $methodIds[$i],
					'shipping_cost' => round($ship_Price),
					'product_total_cost' => $total_cost
				);
			//print "<pre>";
			//print $taxRate;	
			$UpdateCartDetails = $Cart->UpdateCart($updateData,$cartId,$prodIds[$i]);
			
			
		}
		$CartData = $Cart->GetProductInCart();
		$Final_Amount = 0;
		foreach($CartData as $value)
		{
			$Final_Amount += round($value['product_total_cost']);
		
		}
		//print $Final_Amount;die;
		echo json_encode($Final_Amount);die;
  	} 

	
}

?>