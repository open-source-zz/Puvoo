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
 * RestCommonController class.To initialize rest server api.
 *
 * This class file extends REST_Controller.
 * It is used to initialize rest api.
 *
 * Date created: 2011-08-29
 *
 * @author Amar
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
abstract class RestCommonController extends REST_Controller
{
	/**
	 * Function init
	 *
	 * This is function is used to initialize rest api
     * It will disable layout as we do not need layout for rest.
	 *
	 * Date created: 2011-08-29
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function init() 
	{
        $this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout()->disableLayout();
		
		$db = Zend_Db_Table::getDefaultAdapter();
		
		//save database adapter in zend registry
		Zend_Registry::set('Db_Adapter', $db);
		
	} 
	
 }
?>