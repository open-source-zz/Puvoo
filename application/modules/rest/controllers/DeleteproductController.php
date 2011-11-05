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
 * Rest_ProductController
 *
 * Rest_ProductController extends RestCommonController.
 * It is used to handle product related api calls.
 *
 * Date created: 2011-09-06
 *
 * @category	Puvoo
 * @package 	Rest_Controllers
 * @author	    Amar
 *  
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
class Rest_DeleteproductController extends RestCommonController
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
			$this->view->message = $this->translate->_('Product_Access_Denied');
        	$this->getResponse()->setHttpResponseCode(401);
			
		}
		else{
		
			$userlogin = new Models_UserLogin();
			$this->user_id = $userlogin->checkApiToken($this->api_key);
		}
		
		if($this->user_id <= 0){
			$this->view->message = $this->translate->_('Product_Access_Denied');
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
        $this->view->message = $this->translate->_('Product_Resource_Option');
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
			$this->view->message = $this->translate->_('Product_Resource_Option');
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
	 * Function postAction
	 *
	 * The post action handles POST requests and receives an 'id'
     * parameter; it should update the server resource state of the resource
     * identified by the 'id' value.
	 *
	 * Date created: 2011-11-04
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
        	//single filter
			$filter = new Zend_Filter_StripTags();
			

			//Filter chain 1
			$filterChain1 = new Zend_Filter();

			$filterChain1->addFilter(new Zend_Filter_StripTags())
			            ->addFilter(new Zend_Filter_Alpha());

 			$myparams = $this->getRequest()->getParams();
			
			$products = array();
			$arr_error = array();
			
			//create objects
			$Product = new Models_Product();
			$Category = new Models_Category();
			$ProdImg = new Models_ProductImages();
			$ProdToCat = new Models_ProductCategory();
			$ProdOpt = new Models_ProductOptions();
			
			//variables
			$pid = 0;
			$reason = "";
			
					
			$cntProduct = 0;
			$isProdArray = false;
			
			if(isset($myparams["Products"]["Product"]))
			{
				
				$cntProduct = count($myparams["Products"]["Product"]);
				$isProdArray = true;	
			}
			else
			{
				$cntProduct = 1;
				$isProdArray = false;
			}
			
			if( $cntProduct == 1 ) {
				
				$myparams["Products"]["Product"][0]["pid"] = $myparams["Products"]["Product"]["pid"];
				unset($myparams["Products"]["Product"]["pid"]); 
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
					
					if(isset($prod['ending_reason']))
					{
						$reason = $filter->filter(trim($prod['ending_reason']));
					}
					
					//check values
					if($pid <= 0){
						$arr_error[] = sprintf($this->translate->_('Product_Invalid_Product_Id'), $pid);
					}
					
					if($pid > 0 && !$Product->checkProductForUser($pid, $this->user_id))
					{
						$arr_error[] = sprintf($this->translate->_('Product_Invalid_Product_Id'), $pid);
					}
					
					if(count($arr_error) > 0)
					{
						continue;
					}
					else
					{
						$products[$i]['pid'] = $pid;
						$products[$i]['ending_reason'] = $reason;
					}
					
				}
				
			}			
			else
			{
				$arr_error[] = $this->translate->_('Product_Delete_Limit');
			}
			
			
			if(count($arr_error) == 0)
			{
				$arr_msg = array();
				//add products
				for($i = 0; $i < count($products); $i++)
				{
					$data = array();
					
					//delete product 
					$Product->DeleteProductDetail($products[$i]["pid"]);
					
				}
				
				$this->view->result = $this->translate->_('Product_Success');
				$this->view->message = $this->translate->_('Product_Delete_Success');
				
        		$this->getResponse()->setHttpResponseCode(201);
				
			}
			else
			{
				
				//send error message
				$this->view->result = $this->translate->_('Product_Failure');
				$this->view->errormessage = array('error' => $arr_error);
	        	$this->getResponse()->setHttpResponseCode(500);
			}
		}
    }
	
	/**
	 * Function getAction
	 *
	 * The put action handles PUT requests; it should accept and digest a
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
    public function putAction()
    {
        if($this->user_id > 0)
		{
			$this->view->message = $this->translate->_('Store_Resource_Created');
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