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
 * Rest_Bootstrap class.
 * 
 * It is used for configuring and initializing zend environment
 *
 * Date created: 2011-08-30
 *
 * @category	Puvoo
 * @package 	Bootstrap
 * @author Amar
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0) * 
 **/
class Rest_Bootstrap extends Zend_Application_Module_Bootstrap
{

	/**
	 * Function _initPlugins
	 *
	 * This function will initialize plugins for rest module
	 *
	 * Date created: 2011-08-30
	 *
	 * @access protected
	 * @param ()  - No parameter
	 * @return (void) - return void
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    protected function _initPlugins()
    {
		
		
		if(strpos(strtolower($_SERVER['REQUEST_URI']), strtolower(INSTALL_DIR."rest/")) === 0){
			
				
			$bootstrap = $this->getApplication();
			if ($bootstrap instanceof Zend_Application) {
				$bootstrap = $this;
			}
			$bootstrap->bootstrap('FrontController');
			$front = $bootstrap->getResource('FrontController');
			
			$plugin = new REST_Controller_Plugin_RestHandler();
			//$plugin->setBootstrap($this);
			$front->registerPlugin($plugin);
		}
		
    }
	
	/**
	 * Function _initActionHelpers
	 *
	 * This function will register action helpers for rest module
	 *
	 * Date created: 2011-08-30
	 *
	 * @access protected
	 * @param ()  - No parameter
	 * @return (void) - return void
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
   	protected function _initActionHelpers()
    {
       
		if(strpos(strtolower($_SERVER['REQUEST_URI']), strtolower(INSTALL_DIR."rest/")) === 0){
			$contextSwitch = new REST_Controller_Action_Helper_ContextSwitch();
        	Zend_Controller_Action_HelperBroker::addHelper($contextSwitch);

        	$restContexts = new REST_Controller_Action_Helper_RestContexts();
        	Zend_Controller_Action_HelperBroker::addHelper($restContexts);
		}
    }

    /**
	 * Function _initRequest
	 *
	 * This function will initialize request for rest module
	 *
	 * Date created: 2011-08-30
	 *
	 * @access protected
	 * @param ()  - No parameter
	 * @return (object) - return request object
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    protected function _initRequest()
    {
        if(strpos(strtolower($_SERVER['REQUEST_URI']), strtolower(INSTALL_DIR."rest/")) === 0){
			// Ensure front controller instance is present, and fetch it
			$this->bootstrap('FrontController');
			$front = $this->getResource('FrontController');
	
			// Initialize the request object
			$request = new REST_Controller_Request_Http();
	
			// Add it to the front controller
			$front->setRequest($request);
	
			return $request;
		}
    }
}

?>
