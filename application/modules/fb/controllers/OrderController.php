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



class Fb_OrderController extends FbCommonController

{
	/**
	* Function init
	*
	* This function is used for initialization. Also include necessary javascript files.
	*
	* Date Created: 2011-10-09
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
	* This function is used for displays the order details. 
	*
	* Date Created: 2011-10-09
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
	* Function orderconfirmAction
	*
	* This function is used to know the order confirmation. 
	*
	* Date Created: 2011-10-09
	*
	* @access public
	* @param ()  - No parameter
	* @return (void) - Return void
	*
	* @author Jayesh
	*  
	* @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	**/  
	
    public function confirmorderAction()
	{
		global $mysession;
		
		
		$filter = new Zend_Filter_StripTags();	
		$Token = $filter->filter(trim($this->_request->getParam('token'))); 	
		$PayerID = $filter->filter(trim($this->_request->getParam('PayerID'))); 	
		
		if($Token != '' &&  $PayerID != '' ) {
			$Cart = new Models_Cart();
			$Order = new Models_Orders();
			$Product = new Models_Product();
			
			$OrderCart = $Order->getOrderDetail(FBUSER_ID);
			
			
			if($OrderCart) {
				
				$cartOrderDetail = array(
							'facebook_user_id'			 => FBUSER_ID,
							'shipping_user_fname' 		 => $OrderCart['shipping_user_fname'],
							'shipping_user_lname'  		 => $OrderCart["shipping_user_lname"], 
							'shipping_user_email' 		 => $OrderCart["shipping_user_email"],
							'shipping_user_telephone'	 => $OrderCart["shipping_user_telephone"],
							'shipping_user_address_type' => $OrderCart["shipping_user_address_type"],
							'shipping_user_country_id'   => $OrderCart["shipping_user_country_id"],
							'shipping_user_address'		 => $OrderCart["shipping_user_address"],
							'shipping_user_city' 		 => $OrderCart["shipping_user_city"],	
							'shipping_user_state_id'  	 => $OrderCart["shipping_user_state_id"],
							'shipping_user_zipcode'		 => $OrderCart["shipping_user_zipcode"],	
							'billing_user_fname' 		 => $OrderCart["billing_user_fname"],
							'billing_user_lname'  		 => $OrderCart["billing_user_lname"], 
							'billing_user_email' 		 => $OrderCart["billing_user_email"],
							'billing_user_telephone'	 => $OrderCart["billing_user_telephone"],
							'billing_user_address_type'  => $OrderCart["billing_user_address_type"],
							'billing_user_country_id'    => $OrderCart["billing_user_country_id"],
							'billing_user_address'		 => $OrderCart["billing_user_address"],
							'billing_user_city' 		 => $OrderCart["billing_user_city"],	
							'billing_user_state_id'  	 => $OrderCart["billing_user_state_id"],
							'billing_user_zipcode'		 => $OrderCart["billing_user_zipcode"],
							'total_product_amount'		 => $OrderCart["total_product_amount"],		
							'shipping_cost'		 		 => $OrderCart["shipping_cost"],		
							'total_tax_cost'		 	 => $OrderCart["total_tax_cost"],		
							'total_order_amount'		 => $OrderCart["total_order_amount"],		
							'currency_id'		 		 => $OrderCart["currency_id"],
							'order_status'				 => 1,	
							'order_creation_date'		 => date("Y-m-d H:i:s"),
							'language_id'				 => 1,
							'payment_method'			 => 'Express Checkout',
							'payment_token'				 => $Token,
							'payment_payerid'			 => $PayerID
						);
					
						
				$Order_id = $Order->InsertOrder($cartOrderDetail);
				
				$ordercartdetail = $Order->getCartDetail($OrderCart["cart_id"]);
				
				
				foreach($ordercartdetail as $key => $val )
				{
					$orderDetailArray = array(
											'order_id'				=>	$Order_id,
											'product_id'			=>	$val["product_id"],
											'product_name'			=>	$val["product_name"],
											'product_price'			=>	$val["product_price"],
											'tax_rate'				=>	$val["tax_rate"],
											'shipping_method_id'	=>	$val["shipping_method_id"],
											'shipping_cost'			=>	$val["shipping_cost"],
											'product_qty'			=>	$val["product_qty"],
											'product_total_cost'	=>	$val["product_total_cost"]
										);
										
										
					$OrderDetailId = $Order->InsertOrderDetail($orderDetailArray);
					
					$ordercartoptiondetail = $Order->getCartOptionDetail($val["cart_detail_id"]);
					
					if($ordercartoptiondetail)
					{
						foreach($ordercartoptiondetail as $key2 => $val2 )
						{
						
							$orderOptionArray = array(
												'order_detail_id'				=> $OrderDetailId,
												'product_options_id'		 	=> $val2['product_options_id'],
												'product_options_detail_id' 	=> $val2['product_options_detail_id'],
												'option_title' 					=> $val2['option_title'],
												'option_value'					=> $val2['option_value'],
												'option_code' 					=> $val2['option_code'],
												'option_weight' 				=> $val2['option_weight'],
												'option_weight_unit_id' 		=> $val2['option_weight_unit_id'],
												'option_price' 					=> $val2['option_price']
											);
								
							$Order->InsertOrderOptionDetail($orderOptionArray);
						
						}
					}
					
					$productDetail = $Product->GetProductDetails($val["product_id"]);
					
					$updateProductArray = array(
							'sold_count' => $productDetail['sold_count']+$val["product_qty"]
											
					);
					$Order->UpdateProductDetails($updateProductArray,$val["product_id"]);
					
				}
				
				$Order->DeleteCart($OrderCart["cart_id"]);
				
				$CartDetails = $Order->GetProductInCart($Order_id);
				
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
					}
					//print $Price;die;
					$this->view->CartCnt = count($CartDetails);
					$this->view->TotalPrice = $Price;
					$this->view->cartId = $cartId;
					$mysession->cartId = $cartId;
				}
				
			}
			
			
		} else { 
			$this->_request("/");
		}
		
	}
	
	/**
	* Function cancelorderAction
	*
	* This function is used for cancelled the order. 
	*
	* Date Created: 2011-10-12
	*
	* @access public
	* @param ()  - No parameter
	* @return (void) - Return void
	*
	* @author Yogesh
	*  
	* @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	**/  
	
    public function cancelorderAction()
	{
		global $mysession;
		
	}
}

?>