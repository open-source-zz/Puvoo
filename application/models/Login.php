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
 * Class Models_Login
 *
 * Class Models_Login contains methods for admin login section
 *
 * Date created: 2011-08-20
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_Login
{
	 /*
	 * Authenticate_Admin(): To check login_is and password of admin in all records from database.
	 *
	 * It is used to get check records from ADMIN_MASTER.It is used in Admin_IndexContorller in Admin Section to check all records from ADMIN_MASTER and find out that  login_id is correct or not .
	 *
	 * @author  Yogesh 
	 * @param (String) - $username: login id of user
	 * @param (String) - $password: User's Password
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * @return  It will return 1 if successfull login or 0 for error.
	 */

	public function Authenticate_Admin($username, $password)
	{
		global $db,$mysession;
		$sql = "SELECT * FROM admin_master WHERE login_id = '".$username."' AND password = md5('".$password."')";
		$result = $db->fetchRow($sql);
		if($result != null && $result != '' )
		{
			$mysession->Admin_Id = $result["admin_id"];
			return '1';
		}
		else 
		{
			$mysession->Admin_Id = '';
			return '0' ;
		}
	}
	
	
	/*
	 * Authenticate_Email(): To check email id of admin in all records from database.
	 *
	 * It is used to get check records from ADMIN_MASTER.It is used in Admin_LoginController in Admin Section to check all records from ADMIN_MASTER and find out that email is correct or not. Genrate new password and update old password with new password. 
	 *
	 * Date created: 2011-08-31
	 *
	 * @author  		-	Yogesh 
	 * @param (string)  - 	$email : To validate email in admin_master table
	 * @return  		-	It will return array of new passoword and records of admin
	 */
	
	public function Authenticate_Email($email)
	{
		$db = Zend_Registry::get('Db_Adapter');
		$sql = "SELECT * FROM admin_master WHERE email = '".$email."'";		
		$data = $db->fetchRow($sql);
		
		if($data != null && $data != '')
		{
			
			$new_password = RandomPassword(7); 
			
			$record = array(
								"password"	=> md5($new_password),
							);
			
			$where = array(
								"email  = ?"	=> $email,
							);
			
			$db->update('admin_master', $record, $where);
			
			$result[] = $data;
			$result[] = $new_password;
			
		}
		
		return $result;
	}
	
	/*
	 * function UpdateAdminlogintime
	 *
	 * This function is used to update the last login time of admin.
	 *
	 * Date created: 2011-08-31
	 *
	 * @author  	  -		Yogesh 
	 * @param (date)  - 	$date : Date , when admin login 
	 * @param (Int)   - 	$id : Admin Id 
	 * @return (Boolean) -	Return true on su
	 */
	
	function UpdateAdminlogintime($data,$id)
	{
		global $mysession;
		$db = Zend_Registry::get('Db_Adapter');	
		$db->update("admin_master", $data, "admin_id=".$id); 		
		return true;	
	}
	
}
?>
