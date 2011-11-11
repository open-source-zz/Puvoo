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
		include_once(SITE_ROOT_PATH.'/../public/paypalfunctions.php');
		
		$filter = new Zend_Filter_StripTags();	
		$Cart = new Models_Cart();
		$Order = new Models_Orders();
		$Common = new Models_Common();
		$Product = new Models_Product();
		$translate = Zend_Registry::get('Zend_Translate');
		
		$OrderCart = $Order->getOrderDetail(FBUSER_ID);
		
		$Token = $filter->filter(trim($this->_request->getParam('token'))); 	
		$PayerID = $filter->filter(trim($this->_request->getParam('PayerID'))); 
		
		//GetExpressCheckoutDetails
		$ShippingDetail = GetShippingDetails($Token);
		
		
		//DoExpressCheckoutDetails
		$ConfirmPayment = ConfirmPayment($OrderCart["total_order_amount"]);
	
		$ack = strtoupper($ConfirmPayment["ACK"]);
		
		//print $ack;die;
		if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING" || 1 == 1) {
			
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
					$OrderProduct = '';
					foreach($CartDetails as $value)
					{
						$currency_symbol = $Common->GetCurrencyValue($value['currency_id']);
						
			
 						$Price += $value['price']*$value['product_qty'];
						$totalAmount = $value['total_order_amount'];
						$totalshipping = $value['shipping_cost'];
						
						$cartId = $value['order_id'];
						$CartTotal += $value['product_qty'];
						
						$OrderProduct .= '<tr height="25" style="height:25px;">
                                            	<td style="padding-left:5px; color:#6E6E6E;"><b>'.$value['product_name'].'</b></td>
												
                                                <td style="padding-left:5px; color:#6E6E6E;">'.$value['product_qty'].'</td>
                                                <td style="padding-left:5px; color:#6E6E6E;">'.$currency_symbol['currency_symbol'].'&nbsp;'.$value['product_total_cost']/$value['product_qty'].'</td>
                                                <td style="padding-left:5px; color:#6E6E6E;">'.$currency_symbol['currency_symbol'].'&nbsp;'.$value['product_total_cost'].'</td>
                                            </tr>';
					}
					//print $Price;die;
					$this->view->currency_symbol = $currency_symbol['currency_symbol'];
					$this->view->TotalOrder = $totalAmount;
					$this->view->TotalShipping = $totalshipping;
					$this->view->CartCnt = 0;
					$this->view->TotalPrice = $Price;
					$this->view->cartId = $cartId;
					$mysession->cartId = $cartId;
				}
				
				
				$to 		= $OrderCart["shipping_user_email"];
				$to_name 	= $OrderCart['shipping_user_fname']."  ".$OrderCart["shipping_user_lname"];
				$from		= "noreply@puvoo.com";
				$from_name	= "Puvoo";
				$subject	= $translate->_('OrderSubject');					
				$body 		= '<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
</head>

<body style="font-size:12px; font-family:Arial, Helvetica, sans-serif; color:#333333; margin:0px; padding:0px;">
	<div style="background:#ffffff; height:100px; width:680px; margin:auto;" id="top">
    	<div style="padding-top:10px;" id="header">
            <div style="color:#6d6e72; font-weight:bold; font-size:14px; font-family: Verdana, Geneva, sans-serif; line-height:87px; float:left;">
                <a style=" margin-right:11px; padding-left:20px;" href="#"><img border="0" alt="" style="vertical-align:middle;" src="'.IMAGES_FB_PATH.'/logo.png"></a>
            </div>
        </div>
    </div>
    <div style="height:5px; width:680px; margin:auto;">
    	<img alt="" src="'.IMAGES_FB_PATH.'/sep.jpg">
    </div>
    <div style=" background: none repeat scroll 0 0 #E3E3E3; padding-top: 5px; padding-bottom:5px; width: 680px; margin:auto;" id="middle">
    	<div style="background:#FFFFFF; border: 1px solid #CCCCCC; width:666px; border: 1px solid #CCCCCC; margin: auto auto ; width: 670px;">
        	<div style="padding:10px;">
                <h1 style="padding-top:0px; margin-top:0px; color:#FF282E; font-weight:normal; border-bottom:1px dashed #999;">Thanks for Your Order!</h1>
                <div style="background:#fff4ea; padding:7px; margin-bottom:12px;">
                	A summary of your order is shown below.  
                </div>
                
                <table width="100%" cellspacing="0" cellpadding="0">
                	<tbody>
                	
                    
                    <tr><td height="5"></td></tr>
                    <tr>
                    	<td colspan="3">
                        	<table width="100%" cellspacing="0" cellpadding="0">
                            	<tbody><tr>
                                	<td><h2 style="font-size:16px; padding-bottom:5px; margin-bottom:0px;">Your Order Contains the Following Items...</h2></td>
                                </tr>
                                <tr>
                                	<td>
                                    	<table width="100%" cellspacing="0" cellpadding="0" bordercolor="#c7d7db" border="1" style="border-collapse:collapse;">
                                        	<tbody><tr height="25" style="background:#020b6f; height:25px; border-right:none;">
                                            	<td style="color:#ffffff; padding-left:5px;">Cart Items</td> 	
                                                
                                                <td style="color:#ffffff; padding-left:5px;">Qty</td>
                                                <td style="color:#ffffff; padding-left:5px;">Item Price</td>
                                                <td style="color:#ffffff; padding-left:5px;">Item Total</td>
                                            </tr>
                                            '.$OrderProduct.'
                                           
                                        </tbody></table>
                                    </td>
                                </tr>
								<tr><td height="10"></td></tr>
                                <tr>
                                	<td align="right" style="padding-right:25px;">
                                    	<table width="100%" cellspacing="0" cellpadding="0">
                                        	<tbody>
											<tr>
                                            	<td align="right"><b>Total Shipping:&nbsp;&nbsp;'.$this->view->currency_symbol.'&nbsp;'.$totalshipping.'</b></td>
                                            </tr>
                                        </tbody></table>
                                    </td>
                                </tr>
                                <tr><td height="10"></td></tr>
                                <tr>
                                	<td align="right" style="padding-right:25px;">
                                    	<table width="100%" cellspacing="0" cellpadding="0">
                                        	<tbody>
											<tr>
                                            	<td align="right"><b>Total Cost:&nbsp;&nbsp;'.$this->view->currency_symbol.'&nbsp;'.$totalAmount.'</b></td>
                                            </tr>
                                        </tbody></table>
                                    </td>
                                </tr>
                            </tbody></table>
                        </td>
                    </tr>
                </tbody></table>
           </div>
        </div>
    </div>
    <div style=" line-height:24px; width:680px; margin:auto;" id="bottom">
    	<img alt="" src="'.IMAGES_FB_PATH.'/sep.jpg">
    	<div align="right">
        	<a href="#"><img style="margin-top:8px; margin-bottom:8px;" alt="" src="'.IMAGES_FB_PATH.'/poweredby_puvoo.png"></a>
        </div>
    </div>


</body></html>';
				
				//sendMail($to,$to_name,$from,$from_name,$subject,$body);
				
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: '. $from . "\r\n";
						
				mail($to,$subject,$body,$headers);
				
			}
			
			
		} else { 
			$this->_redirect("/fb/");
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