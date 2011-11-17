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
 * ErrorController class is used for handling all errors.
 * 
 *
 * @category	Puvoo
 * @package 	Default
 * @author	    Amar
 * 
 */
class Fb_ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {

        $errors = $this->_getParam('error_handler');
      // echo "test";die;
			$this->_helper->viewRenderer->setNoRender(true);
			$this->_helper->layout()->disableLayout();
			//echo SITE_URL;die;
		//echo "<pre>";
		//print_r($errors);die;
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
				$this->view->message = '404: Not Found';
				$this->render('error');
				 break;
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        		// 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = '404: Not Found';
                //$this->view->message = $errors->exception;//'Application error';
				$this->render('error');
                break;
            default:
                // application error
				$this->getResponse()->setHttpResponseCode(500);
				$this->view->message = 'An unexpected error occurred.';
				$this->render('error');
                //$this->view->message = $errors->exception;//'Application error';
                break;
        }
        
        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->crit($this->view->message, $errors->exception);
        }
        
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }
        
        $this->view->request   = $errors->request;
        //assign view of error template for smarty
        $this->view->T_Body='../error/error.tpl';
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasPluginResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }


}
?>