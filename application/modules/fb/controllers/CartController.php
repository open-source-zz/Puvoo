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
		
		if($IsBilling = 'on')
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
		
			echo json_encode($ShippingInfo);die;
		
		
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
		$prodData = $Product->GetProductDetails($prodId);
		$updateData = array(
 				'product_qty' => $_POST['prodQty'],
				'product_total_cost' => $prodData['product_price']*$_POST['prodQty']
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
		//print_r($prodData['product_weight']);
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
 		echo json_encode($ship_Price);die;
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
	
	
}

?>