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
 * Class Models_UserLogin
 *
 * Class Models_UserLogin contains methods for user login section
 *
 * Date created: 2011-08-26
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_UserLogin
{
	 
	private $db;
	
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-08-26
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
	
	/*
	 * Authenticate_Email(): To check email id of user in all records from database.
	 *
	 * It is used to get check records from ADMIN_MASTER.It is used in Admin_LoginController in Admin Section to check all records from ADMIN_MASTER and find out that email is correct or not. Genrate new password and update old password with new password. 
	 *
	 * Date Created: 2011-08-26
     *
	 * @access public
	 * @param (string)- $email: email to check in table user_master
	 * @return (array) - It will return array of new passoword and record of user
	 *
     * @author Amar
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/  
	
	public function Authenticate_Email($email)
	{
		$db = $this->db;
		$sql = "SELECT * FROM user_master WHERE user_email = '".$email."'";		
		$data = $db->fetchRow($sql);
		
		if($data != null && $data != '')
		{
			
			$new_password = RandomPassword(7); 
			
			$record = array("user_password"	=> md5($new_password));
			
			$where = array("user_email  = ?"	=> $email);
			
			$db->update('user_master', $record, $where);
			
			$result[] = $data;
			$result[] = $new_password;
			
		}
		
		return $result;
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
	 * Function checkApiToken
	 *
	 * This function is used to validate api token
	 *
	 * Date created: 2011-09-01
	 *
	 * @access public
	 * @param (string)  - 	$api_token : api token string
	 * 
	 * @return (integer) - 0 if token not found in table else user_id of the owner of the token if token found
	 *
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	 
	function checkApiToken($api_token)
	{
		$sql = "Select user_id from user_master where user_api_token = '" . $api_token . "'";
		
		$record = $this->db->fetchRow($sql);
		
		if($record != null && $record != '') {
			return $record['user_id'];
		}
		else{
			return 0;
		}
		
	}
	
	/**
	 * Function UpdateUserlogintime
	 *
	 * This function is used to Update ths last login time of user when they login to the site
	 *
	 * Date created: 2011-09-01
	 *
	 * @access public
	 * @param (Array)  		- 	$data : Array of record
	 * @param (Intiger)  	- 	$id : Value of user id
	 * 
	 * @return (Boolean) 	- Return true on success
	 *
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	function UpdateUserlogintime($data,$id)
	{
		global $mysession;
		$db = $this->db;		
		$db->update("user_master", $data, "user_id=".$id); 		
		return true;	
	}
	
}
?>
