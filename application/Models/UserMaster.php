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
 * Class Models_UserStore
 *
 * Class Models_UserStore contains methods for user store management
 *
 * Date created: 2011-08-30
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Yogesh 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_UserMaster
{
	 
	private $db;
	public $constant_array = array();
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-08-30
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	 
	function __construct()
	{
		$this->db = Zend_Registry::get('Db_Adapter');
		$this->constant_array = $this->getConstantArray();
	}
	
	
	
	
	/**
	 * Function registerUser
	 *
	 * This function is used for the registration of user. 
	 * This function used in RegistrationController in default module
	 *
	 * Date created: 2011-08-29
	 *
	 * @access public
	 * @param (array)  - 	$data : This is the reocrds of user
	 * @return () - True if user register successfully
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function registerUser($data)
	{
		$db = $this->db;
		$db->insert("user_master", $data); 	 
		return true; 
	}
	
	
	
	/**
	 * Function validatePassword
	 *
	 * This function is used to validate user account and update the password
	 *
	 * Date created: 2011-08-26
	 *
	 * @access public
	 * @param (string)  - 	$oldP : This is the cuurent password
	 * @param (string)  - 	$newP : This is the new password of user
	 * @return () - True if password update successfully otherwise false
	 *
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	function validatePassword($oldP,$newP) 
	{
		global $mysession;
		
		$sql = "SELECT * FROM user_master WHERE user_id='".$mysession->User_Id."' AND user_password = '".md5($oldP)."'";		

		$record = $this->db->fetchAll($sql);
		// Check that old password is correct or not
		if($record != null && $record != '') {
		
			$update = "UPDATE user_master SET user_password = '".md5($newP)."' WHERE user_id='".$mysession->User_Id."'";
			
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
	 * Function VarifyCodeMail
	 *
	 * This function is used to varify the user account after registration and update the status of the user account.
	 * This function useed in RegistrationController in default module.
	 *
	 * Date created: 2011-08-29
	 *
	 * @access public
	 * @param (string)  - 	$code : This is the varification code that sent to user
	 * @param (string)  - 	$email : This is the email id of user
	 * @return () - True if user account varified successfully
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function VarifyCodeMail($code,$email)
	{
		$db = $this->db;		
		$sql = "SELECT * FROM user_master WHERE user_verification_code = '".$code."' AND user_email = '".$email."'";		
		
		$record = $db->fetchRow($sql);		
		if($record != null && $record != '') {					
			$update = "UPDATE user_master SET user_status = 1 WHERE user_id= ".$record["user_id"];			
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
	 * Function getAllRecords
	 *
	 * This function is used to fetch all record form the table 
	 *
	 * Date created: 2011-08-30
	 *
	 * @access public
	 * @param (string)  - 	$table : Table name
	 * @param (string)  - 	$orderby : Field name on which we have to orderd the fetched records
	 * @return (array)  -	$data : All Records of the table
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function getAllRecords($table,$orderby="")
	{
		
		$db = $this->db;
		$sql = "SELECT * FROM ".$table;
		if($orderby != "" ) {
			$sql .= " ORDER BY ".$orderby;
		}		
		$data = $db->fetchAll($sql);
		return $data;
	}
	
	
	/**
	 * Function Check_UserStore
	 *
	 * This function is used to check that user's store is already exist or not. 
	 *
	 * Date created: 2011-08-30
	 *
	 * @access public
	 * @param (string)  - 	$id : User Id
	 * @return (Boolean)  -	Return ture if store exist otherwise false   
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function Check_UserStore($id)
	{
		$db = $this->db;
		$sql = "SELECT store_name FROM user_master WHERE user_id = '".$id."'";		
		$data = $db->fetchOne($sql);		
		if($data != NULL && $data != '') {
			return true; 
		} else {
			return false;
		}		
	}
	
	
	/**
	 * Function getUserStore
	 *
	 * This function is used to fetch the store information of the user
	 *
	 * Date created: 2011-08-30
	 *
	 * @access public
	 * @param (string)  - 	$id : User Id
	 * @return (array)  -   $data : Return all record of user's store
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function getUserStore($id)
	{
		$db = $this->db;
		
		$select1 = $db->select()
					  ->from("user_master")
					  ->where("user_id = ".$id);
					 
		$select2 = $db->select()
					  ->from("user_master_lang")
					  ->where("user_id = ".$id);
		
		
		$data["store"] 		= $db->fetchRow($select1);
		$data["language"] 	= $db->fetchAll($select2);
		
		return $data;
	}
	
	
	/**
	 * Function UpdateStore
	 *
	 * This function is used to update the store information of the user. This function first check , there is already store exist or not. If store not exist , create new store otherwise update the store information
	 *
	 * Date created: 2011-08-30
	 *
	 * @access public
	 * @param (array)  - 	$data : Records of the store 
	 * @return (Boolean)  -	Return true if store update successfully otherwise false
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function UpdateStore($data, $lang_array)
	{
		$db = $this->db;
		
		$select = $db->select()
					 ->from("user_master")
					 ->where("user_id =".$data["user_id"]);
		
		$result = $db->fetchAll($select);
		if($result != NULL && $result != '') {
			
			$where =" user_id = ".$data["user_id"];			
			$db->update("user_master", $data, $where);
			
		} else {
		
			$db->insert($data);					
		}
		
		if( $lang_array != NULL ) {
		
			$where1 = "user_id = ".$data["user_id"];			
		
			$validator = new Zend_Validate_Db_NoRecordExists(
					array(
							'table' => "user_master_lang",
							'field' => "language_id",
							'exclude' => $where1
					)
			);
			
			foreach( $lang_array as $key => $val )
			{
				if ($validator->isValid($key)) {
					
					$data1["user_id"] = $data["user_id"];
					$data1["language_id"] = $key;
					$data1["store_description"] = $val["store_description"];
					$data1["return_policy"] = $val["return_policy"];
					$data1["store_terms_policy"] = $val["store_terms_policy"];
					
					$db->insert("user_master_lang", $data1);	
					
				} else {
					
					$data2["user_id"] = $data["user_id"];
					$data2["language_id"] = $key;
					$data2["store_description"] = $val["store_description"];
					$data2["return_policy"] = $val["return_policy"];
					$data2["store_terms_policy"] = $val["store_terms_policy"];
					
					$where2 = "user_id = ".$data["user_id"]." and language_id = ".$key;
					
					$db->update("user_master_lang", $data2, $where2); 	
					
				}	
			}
		}
		
		return true;
	}
	
	/**
	 * Function getConstantArray
	 *
	 * This function is used to set all the required array used in the store update form. This function also used to set the property of this class.
	 *
	 * Date created: 2011-08-30
	 *
	 * @access public
	 * @param   - 	No parameters
	 * @return (array)  -	$array : Return all required array
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function getConstantArray()
	{
		global $mysession;
		
		$array = array();
		
		if (Zend_Registry::isRegistered('Zend_Translate')) {
			$translate = Zend_Registry::get('Zend_Translate');
		
		
		
		// For return type
		$return_type = array(
							   "Money" => $translate->_('RETURN_TYPE_MONEY'),
							   "Exachange" => $translate->_('RETURN_TYPE_EXCHANGE'),
						    );
		
		// For handling time
		$handle_time = array(
							   "1" => "1 ".$translate->_('STORE_BUSINESS_DAY'),
							   "2" => "2 ".$translate->_('STORE_BUSINESS_DAY'),
							   "3" => "3 ".$translate->_('STORE_BUSINESS_DAY'),
							   "4" => "4 ".$translate->_('STORE_BUSINESS_DAY'),
							   "5" => "5 ".$translate->_('STORE_BUSINESS_DAY'),
							   "10" => "10 ".$translate->_('STORE_BUSINESS_DAY'),
							   "15" => "15 ".$translate->_('STORE_BUSINESS_DAY'),
							   "20" => "20 ".$translate->_('STORE_BUSINESS_DAY'),
							   "30" => "30 ".$translate->_('STORE_BUSINESS_DAY'),	
						    );
							
		$order_status = array(
							   "0" => $translate->_('Order_Status_Pending'),
							   "1" => $translate->_('Order_Status_Complete'),
							   "2" => $translate->_('Order_Status_Cancle'),							   
						    );
		
		$array["order_status"] = $order_status;
		$array["return_type"] = $return_type;
		$array["handle_time"] = $handle_time;
		}
		return $array;
	
	}
	
	
	/**
	 * Function getAllStates
	 *
	 * This function is used to fill the combo of the states of country on selection of country. This function is used in User_StoreController.   
	 *
	 * Date created: 2011-09-06
	 *
	 * @access public
	 * @param (Int)  - 	$country_id : Country Id
	 * @return (Array) - $data : Return array of all states of country.
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function getAllStates($country_id)
	{
		
		$db = $this->db;		
		$select = $db->select()
					 ->from("state_master")
					 ->where("country_id = ".$country_id);
		
		$data = $db->fetchAll($select);
		return $data;		
	}
	
	
	
	
	/**
	 * Function ValidateTableField
	 *
	 * This function is used to check the particular field value in table that it is already exists in table or not
     *
	 * Date created: 2011-09-02
	 *
	 * @access public
	 * @param () (String)  - $field :  Field name.
	 * @param () (String)  - $field_value : Field value.
	 * @param () (String)  - $table : Table name.
	 * @param () (String)  - $where : Other condtion.
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function ValidateTableField($field,$value,$table,$where)
	{
		$db = $this->db;		
		//$clause = $db->quoteInto($primary_key.'=?', $value);		
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
	 * Function getToken
	 *
	 * This function is used to get api token of given user. 
	 *
	 * Date created: 2011-10-08
	 *
	 * @access public
	 * @param (string)  - 	$id : User Id
	 * @return (String)  -	Return API token of user   
	 *
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getToken($id)
	{
		$db = $this->db;
		$sql = "SELECT user_api_token FROM user_master WHERE user_id = '".$id."'";	
			
		$data = $db->fetchOne($sql);		
		if($data != NULL && $data != '') {
			return $data; 
		} else {
			return "";
		}		
	}
	
	
	/**
	 * Function createToken
	 *
	 * This function is used to create new api token of given user. 
	 *
	 * Date created: 2011-10-08
	 *
	 * @access public
	 * @param (string)  - 	$id : User Id
	 * @return (Boolean)  -	Return true   
	 *
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function createToken($id)
	{
		$db = $this->db;
		$sql = "UPDATE user_master SET user_api_token = md5( concat( user_email, NOW( ) ) ) , user_api_token_expiry = DATE_ADD( NOW( ) , INTERVAL 18 MONTH ) WHERE user_id = " . $id;	
			
		$data = $db->query($sql);		
		if($data != NULL && $data != '') {
			return true; 
		} else {
			return false;
		}		
	}
}
?>