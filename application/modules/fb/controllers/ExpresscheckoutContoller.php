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



class Fb_ExpresscheckoutController extends FbCommonController

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
	 * @author Jayesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/  
	 public function init()
	 {
		parent::init();
		 /* Initialize action controller here */
		Zend_Loader::loadClass('Models_Product');
		require_once ("../public/paypalfunctions.php");

	 }
	
	/**
	 * Function indexAction
	 *
	 * This function is used for displays the product page. 
	 *
	 * Date Created: 2011-08-26
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
		// ==================================
		// PayPal Express Checkout Module
		// ==================================
		
		//'------------------------------------
		//' The paymentAmount is the total value of 
		//' the shopping cart, that was set 
		//' earlier in a session variable 
		//' by the shopping cart page
		//'------------------------------------
		$paymentAmount = $_SESSION["Payment_Amount"];
		
		//'------------------------------------
		//' The currencyCodeType and paymentType 
		//' are set to the selections made on the Integration Assistant 
		//'------------------------------------
		$currencyCodeType = "USD";
		$paymentType = "Sale";
		
		//'------------------------------------
		//' The returnURL is the location where buyers return to when a
		//' payment has been succesfully authorized.
		//'
		//' This is set to the value entered on the Integration Assistant 
		//'------------------------------------
		$returnURL = "http://rishi/Puvoo/fb/";
		
		//'------------------------------------
		//' The cancelURL is the location buyers are sent to when they hit the
		//' cancel button during authorization of payment during the PayPal flow
		//'
		//' This is set to the value entered on the Integration Assistant 
		//'------------------------------------
		$cancelURL = "http://rishi/Puvoo/fb/";
		
		//'------------------------------------
		//' Calls the SetExpressCheckout API call
		//'
		//' The CallShortcutExpressCheckout function is defined in the file PayPalFunctions.php,
		//' it is included at the top of this file.
		//'-------------------------------------------------
		$resArray = CallShortcutExpressCheckout ($paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL);
		$ack = strtoupper($resArray["ACK"]);
		if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
		{
			RedirectToPayPal ( $resArray["TOKEN"] );
		} 
		else  
		{
			//Display a user friendly Error on the page using any of the following error information returned by PayPal
			$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
			$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
			$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
			$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
			
			echo "SetExpressCheckout API call failed. ";
			echo "Detailed Error Message: " . $ErrorLongMsg;
			echo "Short Error Message: " . $ErrorShortMsg;
			echo "Error Code: " . $ErrorCode;
			echo "Error Severity Code: " . $ErrorSeverityCode;
		}
	 }
}
?>