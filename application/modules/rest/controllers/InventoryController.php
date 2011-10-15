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
 * Rest_InventoryController
 *
 * Rest_InventoryController extends RestCommonController.
 * It is used to handle product inventory related api calls.
 *
 * Date created: 2011-09-16
 *
 * @category	Puvoo
 * @package 	Rest_Controllers
 * @author	    Amar
 *  
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
class Rest_InventoryController extends RestCommonController
{
	protected $api_key = "";
	protected $user_id = 0;
	protected $translate = NULL;
	/**
	 * Function init
	 *
	 * This is function is used to initialize rest api
	 *
	 * Date created: 2011-09-16
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
			$this->view->message = $this->translate->_('Invt_Access_Denied');
        	$this->getResponse()->setHttpResponseCode(401);
			
		}
		else{
		
			$userlogin = new Models_UserLogin();
			$this->user_id = $userlogin->checkApiToken($this->api_key);
		}
		
		if($this->user_id <= 0){
			$this->view->message = $this->translate->_('Invt_Access_Denied');
        	$this->getResponse()->setHttpResponseCode(401);
		}	
		
	}
	
    /**
	 * Function optionsAction
	 *
	 * The options action handles OPTIONS requests; it should respond with
     * the HTTP methods that the server supports for specified URL.
	 *
	 * Date created: 2011-09-16
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
		
        $this->view->message = $this->translate->_('Invt_Resource_Options');
        $this->getResponse()->setHttpResponseCode(200);
    }


	/**
	 * Function indexAction
	 *
	 * The index action handles index/list requests; it should respond with a
     * list of the requested resources.
	 *
	 * Date created: 2011-09-16
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
			$this->view->message = $this->translate->_('Invt_Resource_Index');
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
	 * Date created: 2011-09-16
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
	 * Date created: 2011-09-16
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
	 * Date created: 2011-09-16
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
		
		}
    }

	/**
	 * Function putAction
	 *
	 * The put action handles PUT requests and receives an 'id' parameter; it
     * should update the server resource state of the resource identified by
     * the 'id' value.
	 *
	 * Date created: 2011-09-16
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

			
			$myparams = $this->getRequest()->getParams();
			
			/*print "<pre>";
			print_r($myparams);
			print "</pre>";
			die;*/
			$products = array();
			$arr_error = array();
			
					
			
			//create objects
			$Product = new Models_Product();
			
			//variables
			$pid = 0;
			$price = 0;
			$available_qty = 0;
			
			$cntProduct = 0;
			$isProdArray = false;
			
			if(isset($myparams["Products"]["Product"][0]))
			{
				
				$cntProduct = count($myparams["Products"]["Product"][0]);
				$isProdArray = true;	
			}
			else
			{
				$cntProduct = 1;
				$isProdArray = false;
			}
			
			if(isset($myparams["Products"]) && $cntProduct <= 50)
			{
				$x = 0;
				for($i = 0; $i < $cntProduct; $i++)
				{
					if($isProdArray)
					{
						$prod = $myparams["Products"]["Product"][$i];
					}
					else
					{
						$prod = $myparams["Products"]["Product"];
					}
					
					//filter values
					if(isset($prod['pid']))
					{
						$pid = (int) $filter->filter(trim($prod['pid']));
					}
					
					if(isset($prod['price']))
					{
						$price = (float) $filter->filter(trim($prod['price']));
					}
					
					if(isset($prod['available_qty']))
					{
						$available_qty = (int) $filter->filter(trim($prod['available_qty']));
					}
					
					//check values
					if($pid <= 0){
						$arr_error[] = $this->translate->_('Invt_Invalid_Product_Id');
					}
					
					if($pid > 0 && !$Product->checkProductForUser($pid, $this->user_id))
					{
						$arr_error[] = $this->translate->_('Invt_Invalid_Product_Id');
					}
					
					if($price != "" && $price <= 0)
					{
						$arr_error[] = $this->translate->_('Invt_Invalid_Product_Price');
					}
					
					
					if($available_qty != "" && $available_qty <= 0)
					{
						$arr_error[] = $this->translate->_('Invt_Invalid_Product_Quantity');
					}
					
					
					if(count($arr_error) > 0)
					{
						break;
					}
					else
					{
						$products[$i]['pid'] = $pid;
						$products[$i]['price'] = $price;
						$products[$i]['available_qty'] = $available_qty;
					}
					
				}
				
			}			
			else
			{
				$arr_error[] = $this->translate->_('Invt_Product_Edit_Limit');
			}
			
			if(count($arr_error) == 0)
			{
				$arr_msg = array();
				//add products
				for($i = 0; $i < count($products); $i++)
				{
					$data = array();
					
					$data["product_id"] = $products[$i]["pid"];
					$data["user_id"] = $this->user_id;
					
					if($products[$i]["price"] != "" && $products[$i]["price"] > 0)
					{
						$data["product_price"] = $products[$i]["price"];
					}
					
					if($products[$i]["available_qty"] != "" && $products[$i]["available_qty"] > 0)
					{
						$data["available_qty"] = $products[$i]["available_qty"];
					}
					
					//update product 
					$Product->updateProduct($data);
					
				}
				
				$this->view->result = $this->translate->_('Invt_Success');
				$this->view->message = $this->translate->_('Invt_Product_Edit_Success');
				
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
	 * Function deleteAction
	 *
	 * The delete action handles DELETE requests and receives an 'id'
     * parameter; it should update the server resource state of the resource
     * identified by the 'id' value.
	 *
	 * Date created: 2011-09-16
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
		
		}
    }

}
?>