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



class Fb_PageController extends FbCommonController

{
	/**
	 * Function init
	 *
	 * This function is used for initialization. Also include necessary javascript files.
	 *
	 * Date Created: 2011-09-19
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
		Zend_Loader::loadClass('Models_Product');
	 }
	
	/**
	 * Function indexAction
	 *
	 * This function is used for displays the product page. 
	 *
	 * Date Created: 2011-09-19
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
		// action body
		print "test";die;
	 }
	 
	/**
	 * Function aboutusAction
	 *
	 * This function is used for displays the about us page. 
	 *
	 * Date Created: 2011-09-20
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Jayesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/  
	 public function aboutusAction()
	 {
	 
	 }
	 
	/**
	 * Function contactusAction
	 *
	 * This function is used for displays the contact us page. 
	 *
	 * Date Created: 2011-09-20
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Jayesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/  
	 public function contactusAction()
	 {
	 	global $mysession;
		
		$Common = new Models_Common();
	 	
		$ContactDetails = $Common->GetContactUsDetails();
 
  		foreach($ContactDetails as $key=>$detail)
		{
			if($detail['configuration_key'] == 'contact_us_address')
			{
				$this->view->ContactAddress = $detail['configuration_value'];
			}
			
			if($detail['configuration_key'] == 'contact_us_telephone')
			{
				if(stristr($detail['configuration_value'], ',') === FALSE)
				{
					$this->view->ContactPhone = $detail['configuration_value'];
				}
				else
				{
					$phone = explode(',',$detail['configuration_value']);
					$list = '';
					foreach($phone as $val)
					{
						$list .= $val."<br>";
					}
					$this->view->ContactPhone = $list;
				}
				
			}
			
			if($detail['configuration_key'] == 'contact_us_email')
			{
				if(stristr($detail['configuration_value'], ',') === FALSE)
				{
					$this->view->ContactEmail = $detail['configuration_value'];
				}
				else
				{
					$email = explode(',',$detail['configuration_value']);
					$list1 = '';
					foreach($email as $val2)
					{
						$list1 .= "<a href='mailto:".$val2."'>".$val2."</a><br>";
					}
					$this->view->ContactEmail = $list1;
				}
			}
		}
	 }
	 
	/**
	 * Function contactusAction
	 *
	 * This function is used for displays the contact us page. 
	 *
	 * Date Created: 2011-09-20
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Jayesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/  
	 public function invitefriendAction()
	 {
	 	global $user,$facebook;
		$flag = 0;
		if ($user) {
		  $flag = 1;
		  try {
			// Proceed knowing you have a logged in user who's authenticated.
			$uprofile = $facebook->api('/me/likes');
			
		  } catch (FacebookApiException $e) {
			error_log($e);
			$user = null;
		  }
		} else {
			$flag = 0;
		}
		
		$this->view->userlogin = $flag;
		
		$request = $this->getRequest();
		
		if($request->isPost() && $request->isPost("yourlike_user_id") != '' ){
			
			$filter = new Zend_Filter_StripTags();	
			
			$data['friends_email']=$filter->filter(trim($this->_request->getPost('friends_email'))); 	
			$data['friends_mailtext']=$filter->filter(trim($this->_request->getPost('friends_mailtext'))); 	
			
			$addError = array();
			if( $data['friends_email'] == '' || $data['friends_email'] == "Start typing a friend's email id" ) {
				$addError[] = "Please enter friend's email id with comma seprated";
			}
			if( $data['friends_mailtext'] == '' ) {
				$addError[] = "Please enter mail text";
			}
			$validator = new Zend_Validate_EmailAddress();
			$success = '';
			$this->view->userlogin = 1;
			if( count($addError) == 0 || $addError == '' ) {
			
				$emailArray = explode(",",$data['friends_email']);
				
				if( count($emailArray) > 0 ) {
					
					foreach($emailArray as $key => $val )
					{
						if ($validator->isValid($val)) {  
						
							$to 		= $val;
							$to_name 	= '';
							$from		= "noreply@puvoo.com";
							$from_name	= "Puvoo";
							$subject	= "Invite friend";					
							$body 		= 'Prefer this link:<br />';
							$body 		.= "<a href ='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."' >http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."</a><br />";
							$body 		.= $data['friends_mailtext'];
							
							sendMail($to,$to_name,$from,$from_name,$subject,$body);
						} 
					} 
					$success = "Invitation successfully send to your friends";
					
				}
			} else {
			
				$this->view->addError = $addError;
			}
			
			$this->view->success = $success;
		}
		
	 }
}
?>