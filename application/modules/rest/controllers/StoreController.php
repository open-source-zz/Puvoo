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
 * Rest_StoreController
 *
 * Rest_StoreController extends RestCommonController.
 * It is used to handle store related api calls.
 *
 * Date created: 2011-09-06
 *
 * @category	Puvoo
 * @package 	Rest_Controllers
 * @author	    Amar
 *  
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
class Rest_StoreController extends RestCommonController
{
	protected $api_key = "";
	protected $user_id = 0;
	protected $translate = NULL;
	
	/**
	 * Function init
	 *
	 * This is function is used to initialize rest api
	 *
	 * Date created: 2011-09-06
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
	 * Date created: 2011-09-06
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
	 * Date created: 2011-09-06
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
	 * Date created: 2011-09-06
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
	 * Date created: 2011-09-06
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
	    	$this->view->id = $this->_getParam('id');
        	$this->view->resource = new stdClass;
        	$this->getResponse()->setHttpResponseCode(200);
		}
    }

	/**
	 * Function getAction
	 *
	 * The post action handles POST requests; it should accept and digest a
     * POSTed resource representation and persist the resource state.
	 *
	 * Date created: 2011-09-06
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
			$this->view->message = $this->translate->_('Store_Resource_Created');
        	$this->getResponse()->setHttpResponseCode(201);
		}
    }

	/**
	 * Function putAction
	 *
	 * The put action handles PUT requests and receives an 'id' parameter; it
     * should update the server resource state of the resource identified by
     * the 'id' value.
	 *
	 * Date created: 2011-09-06
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
			//single filter
			$filter = new Zend_Filter_StripTags();
			

			//Filter chain 1
			$filterChain1 = new Zend_Filter();

			$filterChain1->addFilter(new Zend_Filter_StripTags())
			            ->addFilter(new Zend_Filter_Alpha());

 			//Filter chain 2
			$filterChain2 = new Zend_Filter();

			$filterChain2->addFilter(new Zend_Filter_StripTags())
			            ->addFilter(new Zend_Filter_Alnum());

			
			$objparams = $this->getRequest()->getParams();
			
			$myparams = $objparams['store_info'];
			
			$store_name = $filter->filter(trim($myparams['store_name']));
			$store_description = $filter->filter(trim($myparams['store_description']));
			$store_address = $filter->filter(trim($myparams['store_address']));
			$store_country = $filterChain1->filter(trim($myparams['store_country']));
			$store_state = $filter->filter(trim($myparams['store_state']));
			$store_city = $filterChain1->filter(trim($myparams['store_city']));
			$store_zipcode = $filterChain2->filter(trim($myparams['store_zipcode']));
			$paypal_address = $filter->filter(trim($myparams['paypal_address']));
			$currency = $filterChain1->filter(trim($myparams['currency']));
			
			$shipping_table = array();
			
			$validator = new Zend_Validate_EmailAddress();
			
			$arr_error = array();
			
			//create objects
			$UserMaster = new Models_UserMaster();
			$Country = new Models_Country();
			$State = new Models_State();
			$Currency = new Models_Currency();
			$ShippingMethod = new Models_UserShippingMethod();
			
			if($store_name != "")
			{
				//check if store name exists for other user
				if(!$UserMaster->ValidateTableField("store_name",$store_name,"user_master","user_id != " . $this->user_id))
				{
					$arr_error[] = $this->translate->_('Store_Store_Name_Exists');
				}
			}
			
			if($store_country != "")
			{
				//check if country is present in table
				$store_country = $Country->GetCountryId($store_country);
				
				if($store_country == 0)
				{
					$arr_error[] = $this->translate->_('Store_Invalid_Country_Code');
				}
				else
				{
					if($store_state != "")
					{
						//check if state is present for given country
						$store_state = $State->GetStateId($store_state,$store_country);
						
						if($store_state == 0)
						{
							$arr_error[] = $this->translate->_('Store_Invalid_State_Code');
						}		
					}
				}
			}
			
			if($store_country == 0 && $store_state > 0)
			{
				$arr_error[] = $this->translate->_('Store_Country_Need_For_State');
			}
			
			if( $paypal_address != "" && !$validator->isValid($paypal_address))
			{
				$arr_error[] = $this->translate->_('Store_Invalid_Paypal_Email');
			}
			
			if($currency != "")
			{
				//check if currency exists in system
				
				$currency = $Currency->GetCurrencyId($currency);
				
				if($currency == 0)
				{
					$arr_error[] = $this->translate->_('Store_Country_Not_Support');
				}
				
			}
			
			
			//check shipping tables
			if(isset($myparams['shipping_table']))
			{
				if(!isset($myparams['shipping_table'][0]))
				{
					$myparams['shipping_table'] = array($myparams['shipping_table']);
				}
				if(count($myparams['shipping_table']) > 0)
				{
					for($i = 0; $i < count($myparams['shipping_table']); $i++)
					{
						
						$shipping_name = $filter->filter(trim($myparams['shipping_table'][$i]["shipping_name"]));
						$shipping_zone = $filter->filter(trim($myparams['shipping_table'][$i]["shipping_zone"]));
						$shipping_cost = $filter->filter(trim($myparams['shipping_table'][$i]["shipping_cost"]));
						
						if($shipping_name == "")
						{
							$arr_error[] = $this->translate->_('Store_Invalid_Name_For_Shipping_Method')." " . ($i+1);
						}
						else
						{
							$shipping_table[$i]['shipping_name'] = $shipping_name;
						}
						
						if($shipping_zone == "")
						{
							$arr_error[] = $this->translate->_('Store_Invalid_Zone_For_Shipping_Method')." " . ($i+1);
						}
						else
						{
							$shipping_table[$i]['shipping_zone'] = $shipping_zone;
						}
						
						if($shipping_cost == "")
						{
							$arr_error[] = $this->translate->_('Store_Invalid_Cost_For_Shipping_Method')." " . ($i+1);
						}
						else
						{
							$shipping_table[$i]['shipping_cost'] = $shipping_cost;
						}
					}
				}
			}
			
			
			if(count($arr_error) == 0)
			{
				//Update store information
					
			
				$data["user_id"] = $this->user_id;
				
				if($store_name != "")
				{
					$data["store_name"] =  	$store_name;
				}
				
				if($store_description != "")
				{
					$data["store_description"] = $store_description;	
				}
				
				if($store_address != "")
				{
					$data["store_address"] = $store_address;	
				}
				
				if($store_country != "")
				{
					$data["country_id"] = $store_country;	
				}
				
				if($store_state != "")
				{
					$data["state_id"] = $store_state;	
				}
				
				if($store_city != "")
				{
					$data["store_city"] = $store_city;	
				}
				
				if($store_zipcode != "")
				{
					$data["store_zipcode"] = $store_zipcode;	
				}
				
				if($paypal_address != "")
				{
					$data["paypal_email"] = $paypal_address;
				}
				
				if($currency > 0)
				{
					$data["currency_id"] = $currency;
				}
			
				if(count($data) > 1)
				{
					//Update store
					$UserMaster->UpdateStore($data);	
				}
				
				//Insert or update shipping method
				if(count($shipping_table > 0))
				{
					for($i = 0; $i < count($shipping_table); $i++)
					{
						$sdata['shipping_method_name'] = $shipping_table[$i]['shipping_name'];
						$sdata['user_id'] = $this->user_id;
						$sdata2['zone'] = $shipping_table[$i]['shipping_zone'];
						$sdata2['price'] = $shipping_table[$i]['shipping_cost'];
						
						//check shipping method if available or not
						if($ShippingMethod->ValidateTableField("shipping_method_name",$sdata['shipping_method_name'],"user_shipping_method","user_id = " . $this->user_id))
						{
							//add new shipping method and zone for user 
							$shipping_method_id = $ShippingMethod->insertShippingMethod($sdata);
							if($shipping_method_id > 0) {
								$sdata2["shipping_method_id"] = $shipping_method_id;
								$ShippingMethod->insertShippingMethod($sdata2,"user_shipping_method_detail");
							}
							
						}
						else
						{
						
							//add or update shipping zone for given shipping method
							$shipping_method_id = $ShippingMethod->getShippingMethodIdByName($sdata['shipping_method_name'],$this->user_id);
							if($shipping_method_id > 0) {
								if($ShippingMethod->ValidateTableField("zone",$sdata2['zone'],"user_shipping_method_detail","shipping_method_id = " . $shipping_method_id))
								{	
									//add new zone
									$sdata2["shipping_method_id"] = $shipping_method_id;
									$ShippingMethod->insertShippingMethod($sdata2,"user_shipping_method_detail");
									
								}
								else
								{
									$shipping_method_detail_id = $ShippingMethod->getShippingDetailIdByMethodId($shipping_method_id);
									
									$where = " user_shipping_method_detail_id = " . $shipping_method_detail_id . " and shipping_method_id = " . $shipping_method_id; 
									//update zone
									$ShippingMethod->updateShippingMethod($sdata2,$where,"user_shipping_method_detail");		
									
								}	
							}							
						}
					}
				}
				
				$this->view->result = $this->translate->_('Store_Success');
				$this->view->message = $this->translate->_('Store_Update_Success');
        		$this->getResponse()->setHttpResponseCode(201);
					
			}else{
				//send error message
				$this->view->result = $this->translate->_('Store_Failure');
				$this->view->errormessage = array('error' => $arr_error);
	        	$this->getResponse()->setHttpResponseCode(500);
			}
			
		}
    }
	
	/**
	 * Function deleteAction
	 *
	 * The delete action handles DELETE requests and receives an 'id'
     * parameter; it should update the server resource state of the resource
     * identified by the 'id' value.
	 *
	 * Date created: 2011-09-06
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