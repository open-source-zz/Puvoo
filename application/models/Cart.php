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

	 /*
	 * ProductExist(): To check that the product is already on cart or not.
	 *
	 * It is used to check that the product is already on cart or not.
	 *
	 * Date created: 2011-08-29
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	
	public function ProductExist($prodId)
	{
		$db= $this->db;
		
		$where = array();
		
		$sql = "SELECT count(*) as cnt1 FROM cart_detail
				WHERE product_id=".$prodId."";
//		$sql = $db->select()
//					 ->from('cart_detail', array('cnt1' => new Zend_Db_Expr('COUNT(*)')))
//					 ->where('product_id = ?', $prodId);
					
		//print $sql;die;		
 		$count = $db->fetchRow($sql);
		 
 		return($count['cnt1']);
	
	}

	 /*
	 * Insert_Record(): To insert product record in cart.
	 *
	 * It is used to insert the product information in cart table.
	 *
	 * Date created: 2011-08-29
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	
	public function Insert_Record($ProductInfo)
	{
		global $mysession;
		$db= $this->db;
		
		//Insert information in cart master table
		$ProductMaster = array(
				'facebook_user_id' => '123@yahoo.com',
				'cart_status' => '1'
		);
		
		$facebookUser = $this->UserExist();
		//print_r(count($facebookUser));die;
		if(count($facebookUser) < 2){
					
			$db->insert('cart_master', $ProductMaster);
			$facebookUser = $this->UserExist();
		}
		
		$id = $facebookUser['cart_id'];
		//Insert information in cart Details table
		$ProductDetails = array(
				'cart_id' => 1,
				'product_id' => $ProductInfo['product_id'],
				'product_name' => $ProductInfo['product_name'],
				'product_price' => $ProductInfo['product_price']
		);
		
		$db->insert('cart_detail', $ProductDetails);
	}
	
	 /*
	 * GetProductInCart(): To get cart product.
	 *
	 * It is used get all the product that are in the cart.
	 *
	 * Date created: 2011-09-01
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	public function GetProductInCart()
	{
		global $mysession;
		$db= $this->db;
		
		$sql = "SELECT cd.*,cm.*,pi.image_path,pm.user_id,um.* FROM cart_detail as cd
				LEFT JOIN cart_master as cm ON (cd.cart_id = cm.cart_id)
				LEFT JOIN product_images as pi ON (cd.product_id = pi.product_id and pi.is_primary_image = 1)
				LEFT JOIN product_master as pm ON (pi.product_id = pm.product_id)
				LEFT JOIN user_master as um ON (pm.user_id = um.user_id)
				WHERE cm.facebook_user_id ='123@yahoo.com' ";
		//print $sql;die;
		$result = $db->fetchAll($sql);
		
		
		return $result;
	}	
	
	 /*
	 * UserExist(): To find that user have a cart id or not.
	 *
	 * It is used find the user that have in the cart.
	 *
	 * Date created: 2011-09-02
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	public function UserExist()
	{
		global $mysession;
		$db= $this->db;
		
		$sql = "SELECT * FROM cart_master WHERE facebook_user_id = '123@yahoo.com'";
		//print $sql;die;
		$result = $db->fetchRow($sql);
		
		return $result;
	
	}
	
	 /*
	 * DeleteCartProduct(): To delete product in to the cart.
	 *
	 * It is used to delete product in to the cart.
	 *
	 * Date created: 2011-09-05
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	public function DeleteCartProduct($prodId,$cartId)
	{
		$db= $this->db;
		$where = array(
			'product_id = ?' => $prodId,
			'cart_id = ?' => $cartId
		);
		
		//$sql = "DELETE FROM cart_detail WHERE cart_id = ".$cartId." and product_id = ".$prodId."";
		//print $sql;die;
		$db->delete('cart_detail',$where);
		
		return true;
	}


	 /*
	 * UpdateShippingInfo(): To update shipping information of current facebook user.
	 *
	 * It is used add or edit shipping information of the current facebook user.
	 *
	 * Date created: 2011-09-17
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	
	public function UpdateShippingInfo($data,$cartId)
	{
		$db= $this->db;
		
		$where = "cart_id=".$cartId."";
	
		$result = $db->update('cart_master', $data, $where);
		
		return $result;
	
	}

	 /*
	 * GetShippingInfo(): To get shipping information of current facebook user.
	 *
	 * It is used to get shipping information of the current facebook user.
	 *
	 * Date created: 2011-09-17
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
	 * @global  $db Zend_db for database.
				$mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	
	public function GetShippingInfo($cartId)
	{
		$db= $this->db;
		
		$sql = "SELECT * FROM cart_master WHERE cart_id=".$cartId."";
		//print $sql;die;
		$result = $db->fetchRow($sql);
		
		return $result;
	
	}
	
}
?>
