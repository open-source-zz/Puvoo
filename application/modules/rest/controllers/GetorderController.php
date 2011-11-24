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
 * Rest_GetorderController
 *
 * Rest_CategoryController extends RestCommonController.
 * It is used to handle category related api calls.
 *
 * Date created: 2011-10-19
 *
 * @category	Puvoo
 * @package 	Rest_Controllers
 * @author	    Amar
 *  
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
class Rest_GetorderController extends RestCommonController
{
	protected $api_key = "";
	protected $user_id = 0;
	protected $translate = NULL;
	
	/**
	 * Function init
	 *
	 * This is function is used to initialize rest api
	 *
	 * Date created: 2011-10-15
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function init(){
		parent::init();
		
		$this->translate = Zend_Registry::get('Zend_Translate');
		//Get api key from header
		$this->api_key = $this->getRequest()->getHeader('apikey');
		
		if($this->api_key == "" || $this->api_key == null)
		{
			$this->view->message = $this->translate->_('Store_Access_Denied');
        	$this->getResponse()->setHttpResponseCode(401);
			
		}
		else{
		
			$userlogin = new Models_UserLogin();
			$this->user_id = $userlogin->checkApiToken($this->api_key);
		}
		
		if($this->user_id <= 0){
			$this->view->message = $this->translate->_('Store_Access_Denied');
        	$this->getResponse()->setHttpResponseCode(401);
		}	
		
	}
	
    /**
	 * Function optionsAction
	 *
	 * The options action handles OPTIONS requests; it should respond with
     * the HTTP methods that the server supports for specified URL.
	 *
	 * Date created: 2011-10-15
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function optionsAction()
    {
        $this->view->message = $this->translate->_('Store_Resource_Options');
        $this->getResponse()->setHttpResponseCode(200);
    }


	/**
	 * Function indexAction
	 *
	 * The index action handles index/list requests; it should respond with a
     * list of the requested resources.
	 *
	 * Date created: 2011-10-15
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function indexAction()
    {
        
		if($this->user_id > 0)
		{
			$this->view->resources = array('mykey' => $this->api_key);
			$this->view->message = $this->translate->_('Store_Resource_Index');
        	$this->getResponse()->setHttpResponseCode(200);
		}
    }

    /**
	 * Function headAction
	 *
	 * The head action handles HEAD requests; it should respond with an
     * identical response to the one that would correspond to a GET request,
     * but without the response body.
	 *
	 * Date created: 2011-10-15
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function headAction()
    {
        $this->getResponse()->setHttpResponseCode(200);
    }

	/**
	 * Function getAction
	 *
	 * The get action handles GET requests and receives an 'id' parameter; it
     * should respond with the server resource state of the resource identified
     * by the 'id' value.
	 *
	 * Date created: 2011-10-15
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function getAction()
    {
		if($this->user_id > 0)
		{       
	    	
			
		}
    }

	/**
	 * Function getAction
	 *
	 * The post action handles POST requests; it should accept and digest a
     * POSTed resource representation and persist the resource state.
	 *
	 * Date created: 2011-10-19
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function postAction()
    {
        if($this->user_id > 0)
		{
			$myparams = $this->getRequest()->getParams();
			
			$orders = array();
			$arr_error = array();
			
			$oid = 0;
			
			//create objects
			$UserOrders = new Models_UserOrders();
			
			$UserOrders->setUserId($this->user_id);
			
			$myorder = array();
			$myorder_detail = array();
			
			if(isset($myparams["Order"]["Id"]))
			{
				$oid = (int) trim($myparams["Order"]["Id"]);
				
				if($oid > 0)
				{
					if(!$UserOrders->orderExistForUser($oid, $this->user_id))
					{
						$arr_error[] = $this->translate->_('Order_Does_Not_Exist');
					}
					
					
					
					if(count($arr_error) == 0)
					{
					
				
						$myorder = $UserOrders->getOrderById($oid);
						
						$orders["Order"][0]["id"] = $myorder["order_id"];
						$orders["Order"][0]["order_creation_date"] = $myorder["order_creation_date"];
						
						$orders["Order"][0]["shipping_user_first_name"] = $myorder["shipping_user_fname"];
						$orders["Order"][0]["shipping_user_last_name"] = $myorder["shipping_user_lname"];
						$orders["Order"][0]["shipping_user_email"] = $myorder["shipping_user_email"];
						$orders["Order"][0]["shipping_user_telephone"] = $myorder["shipping_user_telephone"];
						$orders["Order"][0]["shipping_user_address_type"] = $myorder["shipping_user_address_type"];
						$orders["Order"][0]["shipping_user_country"] = $myorder["shipping_country"];
						$orders["Order"][0]["shipping_user_address"] = $myorder["shipping_user_address"];
						$orders["Order"][0]["shipping_user_state"] = $myorder["shipping_state_name"];
						$orders["Order"][0]["shipping_user_city"] = $myorder["shipping_user_city"];
						$orders["Order"][0]["shipping_user_zipcode"] = $myorder["shipping_user_zipcode"];
						
						$orders["Order"][0]["billing_user_first_name"] = $myorder["billing_user_fname"];
						$orders["Order"][0]["billing_user_last_name"] = $myorder["billing_user_lname"];
						$orders["Order"][0]["billing_user_email"] = $myorder["billing_user_email"];
						$orders["Order"][0]["billing_user_telephone"] = $myorder["billing_user_telephone"];
						$orders["Order"][0]["billing_user_address_type"] = $myorder["billing_user_address_type"];
						$orders["Order"][0]["billing_user_country"] = $myorder["billing_country"];
						$orders["Order"][0]["billing_user_address"] = $myorder["billing_user_address"];
						$orders["Order"][0]["billing_user_state"] = $myorder["billing_state_name"];
						$orders["Order"][0]["billing_user_city"] = $myorder["billing_user_city"];
						$orders["Order"][0]["billing_user_zipcode"] = $myorder["billing_user_zipcode"];
						
						$orders["Order"][0]["payment_method"] = $myorder["payment_method"];
						$orders["Order"][0]["shipping_cost"] = $myorder["shipping_cost"];
						$orders["Order"][0]["total_tax_cost"] = $myorder["total_tax_cost"];
						$orders["Order"][0]["total_order_amount"] = $myorder["total_order_amount"];
						$orders["Order"][0]["currency"] = $myorder["currency_code"];
						$orders["Order"][0]["products"] = array();
						
						//Get Product details
						
						$myorder_detail = $UserOrders->getOrderProductDetail($oid);
						
						$pid = 0;
						$pcount = -1;
						$optcount = 0;
						for($i = 0; $i < count($myorder_detail); $i++)
						{
							if($pid != $myorder_detail[$i]["product_id"])
							{
								$pcount = $pcount + 1;
								
								$orders["Order"][0]["products"][0]["product"][$pcount]["product_id"] = $myorder_detail[$i]["product_id"];
								$orders["Order"][0]["products"][0]["product"][$pcount]["product_name"] = $myorder_detail[$i]["product_name"];
								$orders["Order"][0]["products"][0]["product"][$pcount]["product_price"] = $myorder_detail[$i]["product_price"];
								$orders["Order"][0]["products"][0]["product"][$pcount]["product_qty"] = $myorder_detail[$i]["product_qty"];
								$orders["Order"][0]["products"][0]["product"][$pcount]["product_price"] = $myorder_detail[$i]["product_price"];
								$optcount = 0;
							}
							
							$orders["Order"][0]["products"][0]["product"][$pcount]["options"][0]["option"][$optcount]["option_title"] = $myorder_detail[$i]["option_title"];
							$orders["Order"][0]["products"][0]["product"][$pcount]["options"][0]["option"][$optcount]["option_value"] = $myorder_detail[$i]["option_value"];
							$orders["Order"][0]["products"][0]["product"][$pcount]["options"][0]["option"][$optcount]["option_code"] = $myorder_detail[$i]["option_code"];
							$orders["Order"][0]["products"][0]["product"][$pcount]["options"][0]["option"][$optcount]["option_weight"] = $myorder_detail[$i]["option_weight"];
							$orders["Order"][0]["products"][0]["product"][$pcount]["options"][0]["option"][$optcount]["option_weight_unit"] = $myorder_detail[$i]["weight_unit_key"];
							$orders["Order"][0]["products"][0]["product"][$pcount]["options"][0]["option"][$optcount]["option_price"] = $myorder_detail[$i]["option_price"];
									
							$optcount = $optcount + 1;			
						}
					} 
					
				}
				
				//process all orders for given user
				if($oid == 0)
				{
					$myorder = $UserOrders->GetAllOrders();
					
					for($m = 0; $m < count($myorder); $m++)
					{
						$orders["Order"][$m]["id"] = $myorder[$m]["order_id"];
						$orders["Order"][$m]["order_creation_date"] = $myorder[$m]["order_creation_date"];
						
						$orders["Order"][$m]["shipping_user_first_name"] = $myorder[$m]["shipping_user_fname"];
						$orders["Order"][$m]["shipping_user_last_name"] = $myorder[$m]["shipping_user_lname"];
						$orders["Order"][$m]["shipping_user_email"] = $myorder[$m]["shipping_user_email"];
						$orders["Order"][$m]["shipping_user_telephone"] = $myorder[$m]["shipping_user_telephone"];
						$orders["Order"][$m]["shipping_user_address_type"] = $myorder[$m]["shipping_user_address_type"];
						$orders["Order"][$m]["shipping_user_country"] = $myorder[$m]["shipping_country"];
						$orders["Order"][$m]["shipping_user_address"] = $myorder[$m]["shipping_user_address"];
						$orders["Order"][$m]["shipping_user_state"] = $myorder[$m]["shipping_state_name"];
						$orders["Order"][$m]["shipping_user_city"] = $myorder[$m]["shipping_user_city"];
						$orders["Order"][$m]["shipping_user_zipcode"] = $myorder[$m]["shipping_user_zipcode"];
						
						$orders["Order"][$m]["billing_user_first_name"] = $myorder[$m]["billing_user_fname"];
						$orders["Order"][$m]["billing_user_last_name"] = $myorder[$m]["billing_user_lname"];
						$orders["Order"][$m]["billing_user_email"] = $myorder[$m]["billing_user_email"];
						$orders["Order"][$m]["billing_user_telephone"] = $myorder[$m]["billing_user_telephone"];
						$orders["Order"][$m]["billing_user_address_type"] = $myorder[$m]["billing_user_address_type"];
						$orders["Order"][$m]["billing_user_country"] = $myorder[$m]["billing_country"];
						$orders["Order"][$m]["billing_user_address"] = $myorder[$m]["billing_user_address"];
						$orders["Order"][$m]["billing_user_state"] = $myorder[$m]["billing_state_name"];
						$orders["Order"][$m]["billing_user_city"] = $myorder[$m]["billing_user_city"];
						$orders["Order"][$m]["billing_user_zipcode"] = $myorder[$m]["billing_user_zipcode"];
						
						$orders["Order"][$m]["payment_method"] = $myorder[$m]["payment_method"];
						$orders["Order"][$m]["shipping_cost"] = $myorder[$m]["shipping_cost"];
						$orders["Order"][$m]["total_tax_cost"] = $myorder[$m]["total_tax_cost"];
						$orders["Order"][$m]["total_order_amount"] = $myorder[$m]["total_order_amount"];
						$orders["Order"][$m]["currency"] = $myorder[$m]["currency_code"];
						$orders["Order"][$m]["products"] = array();
						
						//Get Product details
						
						$myorder_detail = $UserOrders->getOrderProductDetail($myorder[$m]["order_id"]);
						
						$pid = 0;
						$pcount = -1;
						$optcount = 0;
						for($i = 0; $i < count($myorder_detail); $i++)
						{
							if($pid != $myorder_detail[$i]["product_id"])
							{
								$pcount = $pcount + 1;
								
								$orders["Order"][$m]["products"][0]["product"][$pcount]["product_id"] = $myorder_detail[$i]["product_id"];
								$orders["Order"][$m]["products"][0]["product"][$pcount]["product_name"] = $myorder_detail[$i]["product_name"];
								$orders["Order"][$m]["products"][0]["product"][$pcount]["product_price"] = $myorder_detail[$i]["product_price"];
								$orders["Order"][$m]["products"][0]["product"][$pcount]["product_qty"] = $myorder_detail[$i]["product_qty"];
								$orders["Order"][$m]["products"][0]["product"][$pcount]["product_price"] = $myorder_detail[$i]["product_price"];
								$optcount = 0;
							}
							
							$orders["Order"][$m]["products"][0]["product"][$pcount]["options"][0]["option"][$optcount]["option_title"] = $myorder_detail[$i]["option_title"];
							$orders["Order"][$m]["products"][0]["product"][$pcount]["options"][0]["option"][$optcount]["option_value"] = $myorder_detail[$i]["option_value"];
							$orders["Order"][$m]["products"][0]["product"][$pcount]["options"][0]["option"][$optcount]["option_code"] = $myorder_detail[$i]["option_code"];
							$orders["Order"][$m]["products"][0]["product"][$pcount]["options"][0]["option"][$optcount]["option_weight"] = $myorder_detail[$i]["option_weight"];
							$orders["Order"][$m]["products"][0]["product"][$pcount]["options"][0]["option"][$optcount]["option_weight_unit"] = $myorder_detail[$i]["weight_unit_key"];
							$orders["Order"][$m]["products"][0]["product"][$pcount]["options"][0]["option"][$optcount]["option_price"] = $myorder_detail[$i]["option_price"];
									
							$optcount = $optcount + 1;			
						}
					}
				}
				
				
			}
			else
			{
				$arr_error[] = $this->translate->_('Invalid_Order_Request');
			}
			
			if(count($arr_error) == 0)
			{
				$this->view->result = 'Success';
				$this->view->Orders = array("Orders" => array($orders));
        		$this->getResponse()->setHttpResponseCode(201);
				
			}
			else
			{
				//send error message
				$this->view->result = 'Failure';
				$this->view->errormessage = array('error' => $arr_error);
	        	$this->getResponse()->setHttpResponseCode(500);
			}
		}
    }

	/**
	 * Function putAction
	 *
	 * The put action handles PUT requests and receives an 'id' parameter; it
     * should update the server resource state of the resource identified by
     * the 'id' value.
	 *
	 * Date created: 2011-10-15
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function putAction()
    {
		if($this->user_id > 0)
		{
			$this->view->message = 'Resource Created';
        	$this->getResponse()->setHttpResponseCode(201);
			
		}
    }
	
	/**
	 * Function deleteAction
	 *
	 * The delete action handles DELETE requests and receives an 'id'
     * parameter; it should update the server resource state of the resource
     * identified by the 'id' value.
	 *
	 * Date created: 2011-10-15
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function deleteAction()
    {
		if($this->user_id > 0)
		{
        	$this->view->message = sprintf($this->translate->_('Store_Resource_Delete'), $this->_getParam('id'));
        	$this->getResponse()->setHttpResponseCode(200);
		}
    }

}
?>