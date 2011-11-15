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
 * Class Models_Cart
 *
 * Class Models_Cart contains methods handle cart on site.
 *
 * Date created: 2011-08-31
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Jayesh 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */ 
class Models_Cart
{
	
	private $db;
	
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Jayesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function __construct()
	{
		$this->db = Zend_Registry::get('Db_Adapter');
	}

	/**
	 * function ProductExist 
	 *
	 * It is used to check that the product is already on cart or not.
	 *
	 * Date created: 2011-08-29
	 *
	 * @param (Int) - $prodId : Product Id
	 * @return (Array) - Return number of records
	 * @author Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function ProductExist($prodId,$fbuid)
	{
		global $mysession;
		$db= $this->db;
		
		$where = array();
		
		$sql = "SELECT count(*) as cnt1 FROM cart_detail as cd
				LEFT JOIN cart_master as cm ON (cm.cart_id = cd.cart_id)
				WHERE product_id=".$prodId." and facebook_user_id = ".$fbuid."";
				
  		$count = $db->fetchRow($sql);
		 
 		return($count['cnt1']);
	}

	/**
	 * function Insert_Record()
	 *
	 * It is used to insert the product information in cart table.
	 *
	 * Date created: 2011-08-29
	 *
	 * @param (Array)  - $ProductInfo : Array of records
	 * @return (Boolean) - Return true on success
	 * @author Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function InsertRecord($ProductInfo)
	{
		global $mysession;
		$db= $this->db;
		$Common = new Models_Common();
		
		//Insert information in cart master table
		$ProductMaster = array(
				'facebook_user_id' => $ProductInfo['fbid'],
				'currency_id' => DEFAULT_CURRENCY,
				'cart_status' => 1
		);
 
 		$facebookUser = $this->UserExist($ProductInfo['fbid']);
 
 		if(!$facebookUser){
					
			$db->insert('cart_master', $ProductMaster);
			
		}
		$facebookUser1 = $this->GetCartId($ProductInfo['fbid']);
		
		
		$id = $facebookUser1['cart_id'];
		$curr_id = $facebookUser1['currency_id'];
		$cart_curr_value = $Common->GetCurrencyValue($curr_id);
		//Insert information in cart Details table
		$UserDetail = $this->GetCartUser($id);
		
		$productUserDetail = $this->GetProductUser($ProductInfo['product_id']);
		
		$prod_curr_value = $Common->GetCurrencyValue(DEFAULT_CURRENCY);
 		
		if($UserDetail['user_id'] == $productUserDetail['user_id'] || $UserDetail == '' )
		{
			$productQty = '1';
			
			if($ProductInfo['ProdName'] != '')
			{
				$prodName = $ProductInfo['ProdName'];
			}else{
				$prodName = $ProductInfo['product_name'];
			}	
				
			
			$prodPrice = round( ($ProductInfo['price'] * $cart_curr_value['currency_value']) / $prod_curr_value['currency_value'], 2 );
				
			//print $test;die;
			//echo $prodPrice;die;
			$ProductDetails = array(
					'cart_id' => $id,
					'product_id' => $ProductInfo['product_id'],
					'product_name' => $prodName,
					'product_price' => $prodPrice,
					'product_qty' => $productQty,
					'product_total_cost' => $prodPrice*$productQty
			);
			
			$db->insert('cart_detail', $ProductDetails);
		}
		return true;
	}
	
	/**
	 * function Insert_CartOption_Record
	 *
	 * It is used to insert product option record in cart product option table.
	 *
	 * Date created: 2011-08-29
	 * @param (Array) - $productOptions : Array of records
	 * @param (Int) - $cartDetailId : cart detail id
	 * @return (Array) - Return true on success
	 * @author Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function InsertCartOptionRecord($productOptions,$cartDetailId)
	{
		$db= $this->db;
		
		//Insert information in cart product Option table
		$ProductOptionDetails = array(
				'cart_detail_id' => $cartDetailId,
				'product_options_id' => $productOptions['product_options_id'],
				'product_options_detail_id' => $productOptions['product_options_detail_id'],
				'option_title' => $productOptions['option_title'],
				'option_value' => $productOptions['option_name'],
				'option_code' => $productOptions['option_code'],
				'option_weight' => $productOptions['option_weight'],
				'option_weight_unit_id' => $productOptions['option_weight_unit_id'],
				'option_price' => $productOptions['Opt_convert_price']
				//'option_quantity' => $productOptions['option_quantity']
		);
		
		$db->insert('cart_product_options', $ProductOptionDetails);
		
		return true;
	}
	
	/**
	 * function GetProductInCart
	 *
	 * It is used get all the product that are in the cart.
	 *
	 * Date created: 2011-09-01
	 * @param () - No parameter
	 * @return (Array) - Return array of result
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetProductInCart($facebook_id,$curr_value = 1,$current_curr_data = 1,$Cart_Country_Vat = DEFAULT_VAT_RATE)
	{
	
		global $mysession;
		$db= $this->db;
		
		//,ROUND((cd.product_price  * ".$curr_value.") / ".$current_curr_data.", 2 ) as Prod_convert_price
		
		$sql = "SELECT cd.*,cd.tax_rate as taxRate,cd.product_price as price,cm.currency_id as currId,cm.*,cm.currency_id as CurrId,pi.image_path,pi.image_name,pm.*,pml.product_name as ProdName,um.*,um.user_id as uid,crm.*,trc.* FROM cart_detail as cd
				LEFT JOIN cart_master as cm ON (cd.cart_id = cm.cart_id)
				LEFT JOIN product_master as pm ON (cd.product_id = pm.product_id)
				LEFT JOIN product_images as pi ON (pm.product_id = pi.product_id and pi.is_primary_image = 1)
				
				LEFT JOIN product_master_lang as pml ON (pm.product_id = pml.product_id and pml.language_id= ".DEFAULT_LANGUAGE.")
				LEFT JOIN user_master as um ON (pm.user_id = um.user_id)
 				LEFT JOIN currency_master as crm ON (crm.currency_id = um.currency_id)
				LEFT JOIN tax_rate_class as trc ON (pm.tax_rate_class_id = trc.tax_rate_class_id)

				WHERE cm.facebook_user_id ='".$facebook_id."'";
		
		$result = $db->fetchAll($sql);
		
		
		return $result;
	}	
	
	public function GetCartDetails($facebook_id)
	{
		global $mysession;
		$db= $this->db;
	
		$sql = "SELECT * FROM cart_master WHERE facebook_user_id = '".$facebook_id."'";
		
		$result = $db->fetchRow($sql);
		
		return $result;
	}
	
	/**
	 * function GetOptionDetail
	 *
	 * It is used to get cart product option detail.
	 *
	 * Date created: 2011-11-09
	 *
	 * @param (Int) -$cart_detail_id- cart detail id.
	 * @return (Array) - Return true on success
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 */
	public function GetOptionDetail($cart_detail_id)
	{	
		global $mysession;
		$db= $this->db;
		
		$sql = "SELECT option_price FROM cart_product_options WHERE cart_detail_id = '".$cart_detail_id."'";
		
		$result = $db->fetchAll($sql);
		
		return $result;
	
	}
	
	/**
	 * function GetProductCartDetails
	 *
	 * It is used to get cart product option detail.
	 *
	 * Date created: 2011-11-10
	 *
	 * @param (Int) -$prodid- product id.
	 *
	 * @param (Int) -$cartid- cart id.
	 *
	 * @return (Array) - Return true on success
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 */
	public function GetProductCartDetails($prodid,$cartid)
	{	
		global $mysession;
		$db= $this->db;
		
		$sql = "SELECT * FROM cart_detail WHERE cart_id = ".$cartid." and product_id = ".$prodid."";
		
		$result = $db->fetchRow($sql);
		
		return $result;
	
	}

	/**
	 * function UserExist
	 *
	 * It is used find the user that have in the cart.
	 *
	 * Date created: 2011-09-02
	 *
	 * @param (Int) -$fbuid- facebook user id.
	 * @return (Array) - Return true on success
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 */
	public function UserExist($fbuid)
	{	
		global $mysession;
		$db= $this->db;
		
		$sql = "SELECT * FROM cart_master WHERE facebook_user_id = '".$fbuid."'";
		
		$result = $db->fetchRow($sql);
		
		return $result;
	
	}

	/**
	 * function GetCartProductDetail
	 *
	 * It is used get cart product details.
	 *
	 * Date created: 2011-09-02
	 *
	 * @param () - No parameter.
	 * @return (Array) - Return true on success
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 */
	public function GetCartProductDetail($prodId,$cartId)
	{
		$db= $this->db;
		
		$sql = "SELECT cd.*,cm.shipping_user_country_id,cum.*,trc.*,pm.product_price,um.user_id as uid FROM cart_detail as cd
				LEFT JOIN cart_master as cm ON (cd.cart_id = cm.cart_id)
				LEFT JOIN product_master as pm ON(pm.product_id = cd.product_id)
				LEFT JOIN user_master as um ON (pm.user_id = um.user_id)
				LEFT JOIN currency_master as cum ON(cum.currency_id = um.currency_id)
				LEFT JOIN tax_rate_class as trc ON (pm.tax_rate_class_id = trc.tax_rate_class_id)	
		 		WHERE cd.product_id = ".$prodId." and cd.cart_id=".$cartId."";
		
		$result = $db->fetchRow($sql);
		
		return $result;
	
	}
	
	/**
	 * function DeleteCartProduct
	 *
	 * It is used to delete product in to the cart.
	 *
	 * Date created: 2011-09-05
	 *
	 * @param (int) - $prodId : Product Id
	 * @param (int) - $cartId : Cart Id
	 * @param (int) - $cartDetailId : Cart Detail Id
	 * @return (Boolean) - Return true on success
	 * @author Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function DeleteCartProduct($prodId,$cartId,$cartDetailId)
	{
		$db= $this->db;
		$where = array(
			'product_id = ?' => $prodId,
			'cart_id = ?' => $cartId
		);
		
		$where1 = "cart_detail_id = ".$cartDetailId."";
		
		$db->delete('cart_detail',$where);
		$db->delete('cart_product_options',$where1);
		
		return true;
	}


	/**
	 * function UpdateShippingInfo
	 *
	 * It is used add or edit shipping information of the current facebook user.
	 *
	 * Date created: 2011-09-17
	 *
	 * @param (Array) - $data : array of records
	 * @param (int) - $cartId : Cart Id
	 * @return (Array) - Return array of records
	 * @author Jayesh 
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 */
	public function UpdateShippingInfo($data,$cartId)
	{
		$db= $this->db;
		
		$where = "cart_id=".$cartId."";
	
		$result = $db->update('cart_master', $data, $where);
		
		return $result;
	
	}

	/**
	 * function GetShippingInfo
	 *
	 * It is used to get shipping information of the current facebook user.
	 *
	 * Date created: 2011-09-17
	 *
	 * @param (int) - $cartId : Cart Id
	 * @return (Array) - Return array of records
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetShippingInfo($cartId)
	{
		$db= $this->db;
		
		$sql = "SELECT cm.*,sm.* FROM cart_master as cm
				LEFT JOIN state_master as sm ON (cm.shipping_user_state_id = sm.state_id) 
				WHERE cart_id=".$cartId."";
		
		$result = $db->fetchRow($sql);
		
		return $result;
	
	}

	/**
	 * function GetBillingInfo
	 *
	 * It is used to get billing information of the current facebook user.
	 *
	 * Date created: 2011-09-29
	 *
	 * @param (int) - $cartId : Cart Id
	 * @return (Array) - Return array of records
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetBillingInfo($cartId)
	{
		$db= $this->db;
		
		$sql = "SELECT cm.*,sm.* FROM cart_master as cm
				LEFT JOIN state_master as sm ON (cm.billing_user_state_id = sm.state_id) 
				WHERE cart_id=".$cartId."";
		
		$result = $db->fetchRow($sql);
		
		return $result;
	
	}

	/**
	 * function GetCountry
	 *
	 * It is used to get all countires names.
	 *
	 * Date created: 2011-09-17
	 *
	 * @param () - No parameter
	 * @return (Array) - Return array of records
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 */
	public function GetCountry()
	{
		$db= $this->db;
		
		$sql = "SELECT CM.* FROM language_master as LM 
				LEFT JOIN country_master as CM ON(LM.country_id = CM.country_id)";
		
		$result = $db->fetchAll($sql);
		
		return $result;
	
	}

	/**
	 * function GetCountryCombo
	 *
	 * It is used to get all countires names.
	 *
	 * Date created: 2011-09-17
	 *
	 * @param () - No parameter
	 * @return (Array) - Return array of records
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 */
	public function GetCountryCombo()
	{
		$db= $this->db;
		
		$sql = "SELECT * FROM country_master";
		
		$result = $db->fetchAll($sql);
		
		return $result;
	
	}


	/**
	 * function GetState
	 *
	 * It is used to get all states of perticular country.
	 *
	 * Date created: 2011-09-17
	 *
	 * @param (int) - $id : Country Id
	 * @return (Array) - Return array of records
	 * @author Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 */
	
	public function GetState($id)
	{
		$db= $this->db;
		
		$sql = "SELECT * FROM state_master where country_id = '".$id."'";
		
		$result = $db->fetchAll($sql);
		
		return $result;
	
	}

	/**
	 * function GetCountryCode
	 *
	 * It is used to get iso code for that particular country.
	 *
	 * Date created: 2011-09-17
	 *
	 * @param (int) - $countryId : Country Id
	 * @return (Array) - Return array of records
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetCountryCode($countryId)
	{
		$db= $this->db;
		
		$sql = "SELECT * FROM country_master WHERE country_id=".$countryId."";
		
		$result = $db->fetchRow($sql);
		
		return $result;
	
	}

	/**
	 * function GetShippingMethod
	 *
	 * It is used to get shipping method for particular user or merchant.
	 *
	 * Date created: 2011-09-21
	 *
	 * @param (int) - $userId : User Id
	 * @return (Array) - Return array of records
	 * @author Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetShippingMethod($userId)
	{
		$db= $this->db;
		
 		
		$sql = "SELECT um.*,usm.*,usd.*,usml.shipping_method_name as ShippingName FROM user_shipping_method usm
				LEFT JOIN user_shipping_method_lang usml ON (usm.shipping_method_id = usml.shipping_method_id and usml.language_id = ".DEFAULT_LANGUAGE.")
				LEFT JOIN user_master as um ON (um.user_id = usm.user_id)
				LEFT JOIN user_shipping_method_detail usd ON (usm.shipping_method_id = usd.shipping_method_id)
				WHERE usm.user_id =".$userId."";
		$result = $db->fetchAll($sql);
		
		return $result;
	
	}

	/**
	 * function GetTaxName
	 *
	 * It is used to get different tax name or type for particular user or merchant.
	 *
	 * Date created: 2011-09-26
	 *
	 * @param (int) - $userId : User Id
	 * @return (Array) - Return array of records
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetTaxName($userId)
	{
		$db= $this->db;
		
 		
		$sql = "SELECT tr.*,trd.* FROM tax_rate tr
				LEFT JOIN tax_rate_detail trd ON (tr.tax_rate_id = trd.tax_rate_id)
				WHERE tr.user_id =".$userId."";
		
		$result = $db->fetchAll($sql);
		
		return $result;
	
	}


	/**
	 * function GetProductShippingmethod
	 *
	 * It is used to get shipping method for cart product.
	 *
	 * Date created: 2011-09-21
	 *
	 * @param (int) - $prodId : Product Id
	 * @return (Array) - Return array of records
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetProductShippingmethod($prodId)
	{
		$db= $this->db;
		
		$sql = "SELECT cd.*,cm.*,pm.user_id,um.*,usm.shipping_method_name,usm.shipping_method_id as ship_method_id,usd.* FROM cart_detail as cd
				LEFT JOIN cart_master as cm ON (cd.cart_id = cm.cart_id)
				LEFT JOIN product_master as pm ON (cd.product_id = pm.product_id)
				LEFT JOIN user_master as um ON (pm.user_id = um.user_id)
				LEFT JOIN user_shipping_method usm ON (um.user_id = usm.user_id) 
				LEFT JOIN user_shipping_method_detail usd ON (usm.shipping_method_id = usd.shipping_method_id)
				WHERE cd.product_id=".$prodId."";
		
		$result = $db->fetchAll($sql);
		
		return $result;
	
	}
	
	/**
	 * function UpdateCart
	 *
	 * It is used edit cart information of the current facebook user.
	 *
	 * Date created: 2011-09-21
	 *
	 * @param (Array) - $data : array of records
	 * @param (int) - $cartId : Cart Id
	 * @param (int) - $prodId : Product Id
	 * @return (Array) - Return array of records
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function UpdateCart($data,$cartId,$prodId)
	{
		$db = $this->db;
		
		
		$where = "cart_id=".$cartId." and product_id=".$prodId."";
	
		$result = $db->update('cart_detail', $data, $where);
		
		return $result;
	
	}

	/**
	 * function UpdateCartMaster
	 *
	 * It is used edit cartmaster information of the current facebook user.
	 *
	 * Date created: 2011-10-12
	 *
	 * @param (Array) - $data : array of records
	 * @param (int) - $cartId : Cart Id
	 * @param (int) - $fbuid : facebook user Id
	 * @return (Array) - Return array of records
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function UpdateCartMaster($data,$cartId,$fbuid)
	{
		$db = $this->db;
		
		$where = "cart_id=".$cartId." and facebook_user_id='".$fbuid."'";
	
		$result = $db->update('cart_master', $data, $where);
		
		return $result;
	
	}


	/**
	 * function GetShippingCost
	 *
	 * It is used to get shipping cost.
	 *
	 * Date created: 2011-09-23
	 *
	 * @param (int) - $methodId : Shipping Method Id
	 * @return (Array) - Return array of records
	 * @author Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetShippingCost($methodId)
	{
		$db = $this->db;
		
		$sql = "SELECT * FROM user_shipping_method_detail as usmd
				LEFT JOIN user_shipping_method as usm ON (usm.shipping_method_id = usmd.shipping_method_id)
				LEFT JOIN user_master as um ON (usm.user_id = um.user_id) 
				LEFT JOIN currency_master as cm ON (cm.currency_id = um.currency_id)
				WHERE usmd.shipping_method_id=".$methodId."";
				
		$result = $db->fetchRow($sql);
		
		return $result;
	
	}

	/**
	 * function GetCartDetailId
	 *
	 * It is used to get cart detail id.
	 *
	 * Date created: 2011-09-28
	 *
	 * @param (int) - $prodId : Product Id
	 * @return (Array) - Return array of records
	 * @author Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetCartDetailId($prodId,$fbuid)
	{
		global $mysession;
		$db = $this->db;
		
		$sql = "SELECT cd.cart_detail_id FROM cart_master as cm
				LEFT JOIN cart_detail as cd ON (cm.cart_id = cd.cart_id)
				WHERE cd.product_id=".$prodId." and cm.	facebook_user_id = '".$fbuid."'";
		
		$result = $db->fetchRow($sql);
		
		return $result;
	
	}
	
	/**
	 * function GetCartId
	 *
	 * It is used to get cart id.
	 *
	 * Date created: 2011-10-12
	 *
	 * @param (int) - $userId : favebook user Id
	 * @return (int) - Return id of cart
	 * @author Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetCartId($userId)
	{
		global $mysession;
		$db = $this->db;
		
		$sql = "SELECT * FROM cart_master WHERE facebook_user_id = '".$userId."'";
		
		$result = $db->fetchRow($sql);
		
		return $result;
	
	}
	
	/**
	 * function GetCartUser
	 *
	 * It is used to get user info that product is already in cart.
	 *
	 * Date created: 2011-10-16
	 *
	 * @param (int) - $cartId : cart Id
	 * @return (Array) - Return array of records
	 * @author Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetCartUser($cartId)
	{
		global $mysession;
		$db = $this->db;
		
		$sql = "SELECT um.user_id,pm.* FROM cart_detail as cd
				LEFT JOIN product_master as pm ON(pm.product_id = cd.product_id)
				LEFT JOIN user_master as um ON(um.user_id = pm.user_id)
		 		WHERE cd.cart_id = '".$cartId."'";
		
		$result = $db->fetchRow($sql);
		
		return $result;
	
	
	}
	
	/**
	 * function GetProductUser
	 *
	 * It is used to get user info using product id.
	 *
	 * Date created: 2011-10-16
	 *
	 * @param (int) - $prodId : product Id
	 * @return (Array) - Return array of records
	 * @author Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetProductUser($prodId)
	{
		global $mysession;
		$db = $this->db;
		
		$sql = "SELECT um.*,pm.* FROM product_master as pm
				LEFT JOIN user_master as um ON(um.user_id = pm.user_id)
		 		WHERE pm.product_id = '".$prodId."'";
		
		$result = $db->fetchRow($sql);
		
		return $result;
	
	
	}
}
?>
