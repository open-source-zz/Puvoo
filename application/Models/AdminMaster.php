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
 * Class Models_AdminMaster
 *
 * Class Models_AdminMaster contains methods that handles dashboard, admin account and other common functionality.
 *
 * Date created: 2011-09-04
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Yogesh 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
class Models_AdminMaster 
{
	private $db;
	
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-08-24
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function __construct()
	{
		$this->db = Zend_Registry::get('Db_Adapter');
	}
	
	/**
	 * Function validatePassword
	 *
	 * This function is useed to validate admin account and update the password
	 *
	 * Date created: 2011-08-25
	 *
	 * @access public
	 * @param (string)  - 	$oldP : This is the cuurent password
	 * @param (string)  - 	$newP : This is the new password of admin
	 * @return (Boolean) - True if password update successfully otherwise false
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	function validatePassword($oldP,$newP) 
	{
		global $mysession;
		
		$sql = "SELECT * FROM admin_master WHERE admin_id='".$mysession->Admin_Id."' AND password = '".md5($oldP)."'";		
		$record = $this->db->fetchAll($sql);
		// Check that old password is correct or not
		if($record != null && $record != '') {
		
			$update = "UPDATE admin_master SET password = '".md5($newP)."' WHERE admin_id='".$mysession->Admin_Id."'";
			
			if($this->db->query($update)) {
				return true;
			} else {
				return false;
			}
			
		} else {		
			return false;			
		}
		
	}
	
	
	/**
	 * Function ValidateTableField
	 *
	 * This function is used to check the particular field value in table if it is already exists in table or not
     *
	 * Date created: 2011-09-02
	 *
	 * @access public
	 * @param () (String)  - $field :  Field name.
	 * @param () (String)  - $value : Field value.
	 * @param () (String)  - $table : Table name.
	 * @param () (String)  - $where : Other condition.
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function ValidateTableField($field,$value,$table,$where)
	{
		$db = $this->db;		
		$validator = new Zend_Validate_Db_NoRecordExists(
				array(
						'table' => $table,
						'field' => $field,
						'exclude' => $where
				)
		);
		
		if ($validator->isValid($value)) {
			return true;
		} else {
			return false;
		}
	
	}
	
	
	/**
	 * Function getConstantArray
	 *
	 * This function is used to get constant array for order status
     *
	 * Date created: 2011-09-02
	 *
	 * @access public
	 * @param () - No parameter.
	 * @return (array) - Return array of status values
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getConstantArray()
	{
		global $mysession;
		
		if (Zend_Registry::isRegistered('Zend_Translate')) {
			$translate = Zend_Registry::get('Zend_Translate');
		
		$array = array();
		
		// For Order Status
		$order_status = array(
							   "0" => $translate->_('Order_Status_Pending'),
							   "1" => $translate->_('Order_Status_Complete'),
							   "2" => $translate->_('Order_Status_Cancle'),							   
						    );
		
		$array["order_status"] = $order_status;
		
		}
		return $array;
	}
	
	/**
	 * Function updateProductCounter
	 *
	 * This function is used to update the product like counter
     *
	 * Date created: 2011-10-13
	 *
	 * @access public
	 * @param (Array) 	-	$data: Array of product counter.
	 * @return (array) 	- 	Return true on success
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function updateProductCounter($data)
	{
		$db = $this->db;
		
		$select = "SELECT * FROM product_master WHERE product_id = ".$data["product_id"];
		
		$records = $db->fetchRow($select);
		
		$counter["like_count"] = $records["like_count"] + 1;
		
		$where = "product_id = ".$data["product_id"];
		
		$db->update("product_master", $counter, $where);
		
		$db->insert("user_product_likes", $data); 	 
		
		return true;
	}
	/**
	 * Function deleteProductLike
	 *
	 * This function is used to delete the product like
     *
	 * Date created: 2011-10-13
	 *
	 * @access public
	 * @param (Array) - $data: Array of product like data.
	 * @return (array) - Return true on success
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function deleteProductLike($data)
	{
		$db = $this->db;
		
		$select = "SELECT * FROM product_master WHERE product_id = ".$data["product_id"];
		
		$records = $db->fetchRow($select);
		
		if ( $records["like_count"] > 0 ) { 
			
			$counter["like_count"] = $records["like_count"] - 1;
		
			$where = "product_id = ".$data["product_id"];
		
			$db->update("product_master", $counter, $where);
		}
		
		$where2 =  "product_id = '".$data["product_id"]."' and facebook_user_id = '".$data["facebook_user_id"]."'";
		
		$db->delete("user_product_likes", $where2);		
		
		return true;
	}
	
	/**
	 * Function insertFacebookUser
	 *
	 * This function is used to insert facebook user records
     *
	 * Date created: 2011-10-13
	 *
	 * @access public
	 * @param (Array) - $data: Array of facebook user
	 * @return (array) - Return true on success
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	 
	public function insertFacebookUser($data)
	{
		$db = $this->db;
		
		$db->insert("facebook_user_master", $data); 	 	
		
		return true;
	}
	
	/**
	 * Function getDefaultLanguage
	 *
	 * This function is used to fetch the default language id.
     *
	 * Date created: 2011-10-20
	 *
	 * @access public
	 * @return (array) - Return true on success
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	 
	public function getDefaultLanguage()
	{
		$db = $this->db;
		
		$Sql = "SELECT language_id
				FROM language_master
				WHERE is_default = 1";
		
		return $db->fetchOne($Sql); 	 	
	}
	
}