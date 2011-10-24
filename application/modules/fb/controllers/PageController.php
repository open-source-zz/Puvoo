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
		
		//Disable layout
		$this->_helper->layout()->disableLayout();
		
		$facebook = new Facebook(array(
			  'appId'  => FACEBOOK_APP_API_ID,
			  'secret' => FACEBOOK_APP_SECRET_KEY,
			  'cookie' => true,
		));
		
		// Retrieve array of friends who've already authorized the app.
		$fql = 'SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1='.FACEBOOK_USERID.') AND is_app_user = 1';
		
		$_friends = $facebook->api( 
						array( 
								'method' => 'fql.query', 
								'query' => $fql
							) 
						);
	
		// Extract the user ID's returned in the FQL request into a new array.
		$friends = array();
		if (is_array($_friends) && count($_friends)) {
			foreach ($_friends as $friend) {
				$friends[] = $friend['uid'];
			}
		}
	
		// Convert the array of friends into a comma-delimeted string.
		$friends = implode(',', $friends);
		
		echo $friends; die;
		
	 }
}
?>