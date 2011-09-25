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
 * Default Registration Controller.
 *
 * RegistrationController  extends DefaultCommonController. 
 * It controls the registration process on default section
 *
 * Date Created: 2011-08-29
 *
 * @category	Puvoo
 * @package 	Default_Controllers
 * @author	    Yogesh 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/ 


class RegistrationController extends DefaultCommonController
{
	
	/**
     * Function init
	 *
	 * This function is used for initialization. 
	 * You can also include necessary javascript files from here.
	 *
     * Date Created: 2011-08-29
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
 

    public function init()
    {
		/* Initialize action controller here */
        parent::init();
		$this->view->JS_Files = array('default/registration.js');	
		Zend_Loader::loadClass('Models_UserMaster');

    }

 	 /**
     * Function indexAction
	 *
	 * This function is used for registration process 
	 *
     * Date Created: 2011-08-29
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/

    public function indexAction()
    {
		
        // action body
		 global $mysession;
		 $translate = Zend_Registry::get('Zend_Translate');
		 
		 $home = new Models_UserMaster();
		
		 if($this->_request->isPost())
		 {
			$filter = new Zend_Filter_StripTags();	
			$data['user_fname']=$filter->filter(trim($this->_request->getPost('user_fname'))); 	
			$data['user_lname']=$filter->filter(trim($this->_request->getPost('user_lname'))); 	
			$data['user_email']=$filter->filter(trim($this->_request->getPost('user_email'))); 	
			$data['user_password']=$filter->filter(trim($this->_request->getPost('user_password'))); 	
			$user_conf_password = $filter->filter(trim($this->_request->getPost('user_conf_password'))); 	
			$data['user_facebook_id']=$filter->filter(trim($this->_request->getPost('user_facebook_id'))); 	
			$data['registration_date']=date("Y-m-d H:i:s"); 			
			$verfication_code = RandomPassword(6); 	
			$data['user_verification_code']= md5($verfication_code); 	
			$data['user_api_token']=md5($data['user_email'].date("Y-m-d H:i:s"));
			
			$registrationError = "";
			if($data['user_fname'] == "" ) {
				$registrationError .= "<h4 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Reg_FName')."</h4>";			
			}
			if($data['user_lname'] == "" ) {
				$registrationError .= "<h4 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Reg_LName')."</h4>";			
			}
			if($data['user_email'] == "" ) {
				$registrationError .= "<h4 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Reg_Email')."</h4>";			
			}
			$validator = new Zend_Validate_EmailAddress();
			if ($validator->isValid($data['user_email'])) {  } else {
				$registrationError .= "<h4 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Reg_Invalid_Email')."</h4>";
			}			
			if($data['user_password'] == "" ) {
				$registrationError .= "<h4 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Reg_Password')."</h4>";
			}		
			if($user_conf_password == "" ) {
				$registrationError .= "<h4 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Reg_Conf_Password')."</h4>";
			}		
			if($data['user_password'] != $user_conf_password ) {
				$registrationError .= "<h4 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Reg_Conf_Password_Match')."</h4>";
			}		
			
			$where = "1=1";
			if($home->ValidateTableField("user_email",$data['user_email'],"user_master",$where)) {
				if($registrationError == "") {
				
					$data['user_password'] = md5($data['user_password']);
					
					if($home->registerUser($data)) {
					
						$to 		= $data['user_email'];
						$to_name 	= $data['user_fname']."  ".$data['user_lname'];
						$from		= "noreply@puvoo.com";
						$from_name	= "Puvoo";
						$subject	= "Confirm registration process";					
						$body 		= SITE_URL.'registration/confirm?varification_code='.$data['user_verification_code'].'&email='.$data["user_email"];
						/*$body 		= '<table>
										<tr>
											<td><b>Welcome To Puvoo</b>,</td>
										</tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td>Thank you for signing up with Puvoo!</td>
										</tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td>To confirm your registration '.SITE_URL.'registration/confirm/?varification_code='.$data['user_verification_code'].'&email='.$data["user_email"].'   click or copy n paste in browser </td>
										</tr>
										<tr><td height="10"></td></tr>									
										<tr>	
											<td>Your Name:'.$objArray['first_name'].' '.$objArray['last_name'].' </td>
										</tr> 
										<tr>
											<td>Your Password :'.$user_conf_password.'</td>
										</tr>									
										<tr>
											<td>Your Varification code :'.$verfication_code.'</td>
										</tr>									
										<tr><td height="30"></td></tr>
										<tr>
											<td>Wishing you all the best</td>
										</tr>
										<tr><td height="10"></td></tr>
										<tr>
											<td>Yours sincerely,</td>
										</tr>
									</table>'; 	*/		
									
						if(sendMail($to,$to_name,$from,$from_name,$subject,$body)) {
						
							$this->view->RegMessage = "<h3 style='color:#339900;margin-bottom:0px;'>".$translate->_('Success_Registration')."</h3>"; 		
							
						} else {
							
							$this->view->RegMessage = "<h4 style='color:#FF0000;margin-bottom:0px;'>There is some problem in seding mail. </h4>";	
							
						}
						 
						 
					} else {
					
						$this->view->RegMessage = "<h4 style='color:#FF0000;margin-bottom:0px;'>There is some problem in registration process</h4>";	
					}				
				
				} else {
				
					$this->view->RegMessage = $registrationError;
				}
			} else {
			
				$this->view->RegMessage = "<h4 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Reg_Email_Exists')."</h4>";					
			}
			
		 }

    }
	
	
	 /**
     * Function confirmAction
	 *
	 * This function is used to varify user account
	 *
     * Date Created: 2011-08-29
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	
	public function confirmAction()
	{
	
		 global $mysession;
		 $filter = new Zend_Filter_StripTags();	
         $varification_code=$filter->filter(trim($this->_request->getParam('varification_code'))); 	
		
		 $email=$filter->filter(trim($this->_request->getParam('email'))); 	
		 $home = new Models_UserMaster();
		 $flag = "";	
		 if($this->_request->isPost()) {
		 	 
			 // Check varification code and email id by manually
				 $vari_code=$filter->filter(trim($this->_request->getPost('verify_code'))); 	
				 $vari_email=$filter->filter(trim($this->_request->getPost('verify_email'))); 	
				 if($home->VarifyCodeMail(md5($vari_code),$vari_email))	{
				 	$this->view->varifyMessage = "<h3 style='color:#339900;margin-bottom:0px; text-align:center'>Your account has been varified. <br /> Now you can login into our site.</h3>"; 	
					$flag = "1";
				 	
				 } else {				 	
				 	$flag = "0";
					$this->view->varifyMessage = "<h4 style='color:#FF0000;margin-bottom:0px; text-align:center'>Your account is not varified.<br /> Please enter correct varification code and email id</h4>";	
				 }
				 
		  } else {		 
			
			
			 // Check varification code and email id by confirmation link
			 if($varification_code != "" && $email != ""){		 	
			 
				if($home->VarifyCodeMail($varification_code,$email))	{
					$this->view->varifyMessage = "<h3 style='color:#339900;margin-bottom:0px; text-align:center'>Your account has been varified. <br /> Now you can login into our site.</h3>"; 	
					$flag = "1";			
				} else {
					$this->view->varifyMessage = "<h4 style='color:#FF0000;margin-bottom:0px; text-align:center'>You are not authorized person to view this site. <br /> Please check your varification code and email id.</h4>";	
					$flag = "2";
				}				
			 } else {
					$flag = "0";
					$this->view->varifyMessage = "<h4 style='color:#FF0000;margin-bottom:0px; text-align:center'>Your account is not varified. <br />  Please enter varification code and email id</h4>";	
			 }			 
			
		}
		
		 $this->view->flag = $flag;		 
	}
	

}

?>
