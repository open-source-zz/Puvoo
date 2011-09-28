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
 * User Login Controller.
 *
 * User_LoginController  extends UserCommonController. It is used to login to admin section.
 *
 * Date Created: 2011-08-26
 *
 * @category	Puvoo
 * @package 	User_Controllers
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/  

class User_LoginController extends UserCommonController
{

    /**
     * Function init
	 *
	 * This function is used for initialization. Also include necessary javascript files.
	 *
     * Date Created: 2011-08-26
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/  
    function init()
    {
        parent::init();
    }
	 
	/**
	 * Function indexAction
	 *
	 * This function is used for Login. It displays the login form. 
	 *
     * Date Created: 2011-08-26
     *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/  
	function indexAction()
	{
		global $mysession,$db;
		$data = array();
		//Disable layout
		$this->_helper->layout()->disableLayout();
		
		//Get Request
		$request = $this->getRequest();
		
		// For remember username and  password
		if (isset ($_COOKIE["cookie"])) 
		{ 
		   while (list ($name, $value) = each ($_COOKIE["cookie"])) 
		   { 
		      $data[] = $value;
		   } 
		} 
		
		if($data != null)
		{
			$this->view->username = $data[0];
			$this->view->password = $data[1];
			$this->view->remember = $data[2];
		}
		
		
		//Get translator adapter
		$translate = Zend_Registry::get('Zend_Translate');
		
		if($request->isPost()){
			
			$auth  = Zend_Auth::getInstance();
			$authAdapter = new Zend_Auth_Adapter_DbTable($db); 
	   
	   		$authAdapter->setTableName('user_master') 
               			->setIdentityColumn('user_email') 
	               		->setCredentialColumn('user_password');
			
			// Set the input credential values 
			$uname = trim($request->getPost('user_name'));
			$paswd = trim($request->getPost('password'));
			$rembr = trim($request->getPost('remember'));
			
			$this->view->loginError = '';
			if($uname == '')
			{
				$this->view->loginError .= $translate->_('Tip_Enter_Username') . '<br>';
			}
			if($paswd == '')
			{
				$this->view->loginError .= $translate->_('Tip_Enter_Password');
			}
			if($this->view->loginError == '')
			{
				$authAdapter->setIdentity($uname); 
				$authAdapter->setCredential(md5($paswd)); 
		 
				//Perform the authentication query, saving the result 
				$result = $auth->authenticate($authAdapter); 
				
				if($result->isValid())
				{ 
				  $data = $authAdapter->getResultRowObject(null,'user_password'); 
				  
 				  if($data->user_status == 0) {
				  		$this->view->loginError = $translate->_('Unauthorized_User');
				  } else {
				  	
					$records["user_last_login"] = date("Y-m-d H:i:s");
					$auth = new Models_UserLogin();
					$result = $auth->UpdateUserlogintime($records,$data->user_id); 
					
				  	$mysession->User_Id = $data->user_id;
				  	$mysession->User_Login = $data->user_email;
					
					if($rembr == '1')
					{
						$cookie_username = $uname;
						$cookie_password = $paswd;
						$cookie_remember = $rembr;
						$expire_time = time()+60*60*24*30; //30 days expire time
						setcookie ("cookie[user_uname]", $cookie_username,$expire_time); 
						setcookie ("cookie[user_passwd]", $cookie_password,$expire_time); 	
						setcookie ("cookie[user_rembr]", $cookie_remember,$expire_time); 	
					}
					else
					{
						$cookie_username = $username;
						$cookie_password = $password;
						$cookie_remember = $rembr;
						$expire_time = time()-60*60*24*30; //30 days expire time
						setcookie ("cookie[user_uname]", $cookie_username,$expire_time); 
						setcookie ("cookie[user_passwd]", $cookie_password,$expire_time); 
						setcookie ("cookie[user_rembr]", $cookie_remember,$expire_time); 	
					}
					
				  	$this->_redirect('/user/index/'); 	
				  }
				  
				} else 	{
				  $this->view->loginError = $translate->_('err_login');  
				} 
			}
		}	
	  
      if ($mysession->Forgot_Error != "") {
          $this->view->Forgot_Error =  $mysession->Forgot_Error;
		  $mysession->Forgot_Error = "";
      }
	  
	}
	
	
	/**
	 * Function forgotpassAction
	 *
	 * This function is used for password recovery. This function update old password with new password and mail new password. 
	 *
     * Date Created: 2011-08-26
     *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/  
	
	
	function forgotpassAction()
	{
		
		$this->_helper->layout()->disableLayout();
		global $mysession;
		
		//Get translator adapter
		$translate = Zend_Registry::get('Zend_Translate');
		
		if($this->_request->isPost()) { 
		
			$email = trim($this->_request->getParam('forgotpass_email'));
			
			if($email == ""){ 
			
				$mysession->Forgot_Error = $translate->_('Forgot_Pass_Email_Error'); 
				$this->_redirect("/user/login"); 
				
			} else {		
							
			$validator = new Zend_Validate_EmailAddress(); 
			
			if ($validator->isValid($email)) {
			
				// email appears to be valid
				//Perform the authentication query, and send new password.
				$auth = new Models_UserLogin();
				$result = $auth->Authenticate_Email($email); 				
				if($result != null && $result != '' ) { 
					
					// Mail new password
					$to 		= $email; 
					$to_name 	= $result[0]["user_name"];
					$from		= "admin@Puvoo.com";
					$from_name	= "Puvoo Administrator";
					$subject	= "Forgot Password Recovery";
					$body		= "Hello, ".$result[0]["user_name"]."<br /><br />Your new password : ".$result[1];					
					
					if(sendMail($to,$to_name,$from,$from_name,$subject,$body)){
	  					
						$mysession->Forgot_Error = $translate->_('Forgot_Pass_Success');   
						$this->_redirect("/user/login");
						
	  				}					
					
				} else {
				
					$mysession->Forgot_Error = $translate->_('Forgot_Pass_Email_Notfound'); 
					$this->_redirect("/user/login");
					
				}
				
			} else {
			
				// email is invalid; print the reasons
				$mysession->Forgot_Error = $translate->_('Forgot_Pass_Email_Invalid');
				$this->_redirect("/user/login"); 
				
			}
		  }
		}
	}	
}
?>