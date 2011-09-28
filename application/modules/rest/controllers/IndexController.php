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
 *  Sample Foo Resource
 */
class Rest_ProductController extends RestCommonController
{
	protected $api_key = "";
	protected $user_id = 0;
	
	public function init(){
		parent::init();
		
		//Get api key from header
		$this->api_key = $this->getRequest()->getHeader('apikey');
		
		if($this->api_key == "" || $this->api_key == null)
		{
			$this->view->message = 'Access Denied';
        	$this->getResponse()->setHttpResponseCode(401);
			
		}
		else{
		
			$userlogin = new Models_UserLogin();
			$this->user_id = $userlogin->checkApiToken($this->api_key);
		}
		
		if($this->user_id <= 0){
			$this->view->message = 'Access Denied';
        	$this->getResponse()->setHttpResponseCode(401);
		}	
		
	}
	
    /**
     * The options action handles OPTIONS requests; it should respond with
     * the HTTP methods that the server supports for specified URL.
     */
    public function optionsAction()
    {
        $this->view->message = 'Resource Options';
        $this->getResponse()->setHttpResponseCode(200);
    }

    /**
     * The index action handles index/list requests; it should respond with a
     * list of the requested resources.
     */
    public function indexAction()
    {
        
		if($this->user_id > 0)
		{
			$this->view->message = 'Resource Index';
        	$this->getResponse()->setHttpResponseCode(200);
		}
    }

    /**
     * The head action handles HEAD requests; it should respond with an
     * identical response to the one that would correspond to a GET request,
     * but without the response body.
     */
    public function headAction()
    {
        $this->getResponse()->setHttpResponseCode(200);
    }

    /**
     * The get action handles GET requests and receives an 'id' parameter; it
     * should respond with the server resource state of the resource identified
     * by the 'id' value.
     */
    public function getAction()
    {
        if($this->user_id > 0)
		{
			$this->view->message = sprintf('Resource #%s found', $this->_getParam('id'));
        	$this->getResponse()->setHttpResponseCode(200);
		}
    }

    /**
     * The post action handles POST requests; it should accept and digest a
     * POSTed resource representation and persist the resource state.
     */
    public function postAction()
    {
        if($this->user_id > 0)
		{
			$this->view->message = 'Resource Created';
        	$this->getResponse()->setHttpResponseCode(201);
			
			/*print "<pre>";
			print_r($this->getRequest()->getPost());
			print "</pre>";*/
		}
    }

    /**
     * The put action handles PUT requests and receives an 'id' parameter; it
     * should update the server resource state of the resource identified by
     * the 'id' value.
     */
    public function putAction()
    {
		if($this->user_id > 0)
		{
        	$this->view->message = sprintf('Resource #%s Updated', $this->_getParam('id'));
        	$this->getResponse()->setHttpResponseCode(201);
		}
    }

    /**
     * The delete action handles DELETE requests and receives an 'id'
     * parameter; it should update the server resource state of the resource
     * identified by the 'id' value.
     */
    public function deleteAction()
    {
        if($this->user_id > 0)
		{
			$this->view->message = sprintf('Resource #%s Deleted', $this->_getParam('id'));
        	$this->getResponse()->setHttpResponseCode(200);
		}
    }
		
}
?>