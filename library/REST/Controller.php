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
 * Class REST_Controller
 *
 * Class REST_Controller extends Zend_Rest_Controller and adds 2 more methods head and action.
 *
 * Date created: 2011-08-30
 *
 * @category	Puvoo
 * @package 	REST
 * @author	    Amar
 *  
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  

abstract class REST_Controller extends Zend_Rest_Controller
{
    
	/**
	 * Function headAction
	 *
	 * The head action handles HEAD requests; it should respond with an
     * identical response to the one that would correspond to a GET request,
     * but without the response body.
	 *
	 * Date created: 2011-08-30
	 *
	 * @access public
	 * @param ()  - No Parameter
	 * @return (void) - Return void
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function headAction()
    {
        // TODO: reset the view object but not the headers
        // $request->_forward('get');
    }

    
	/**
	 * Function headAction
	 *
	 * The options action handles OPTIONS requests; it should respond with
     * the HTTP methods that the server supports for specified URL.
	 *
	 * Date created: 2011-08-30
	 *
	 * @access public
	 * @param ()  - No Parameter
	 * @return (void) - Return void
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function optionsAction()
    {
        $this->getResponse()->setBody(null);
        $this->getResponse()->setHeader('Allow', 'OPTIONS, HEAD, INDEX, GET, POST, PUT, DELETE');
    }
}
?>