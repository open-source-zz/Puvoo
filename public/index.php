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
 * index page of site 
 *
 * Date created: 2011-08-18
 *
 * @author Amar
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/
 

defined('PUVOO_VERSION')
    || define('PUVOO_VERSION', "Puvoo&trade; v. 1.0.0 ALPHA");
	
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));
	

// Define Path for Site Root
define('SITE_ROOT_PATH',realpath(dirname(__FILE__))); 

// Define Path for library folder
define('LIBRARY_PATH',realpath(SITE_ROOT_PATH."/../library"));

// Define Path for models folder
define('MODELS_PATH',realpath(APPLICATION_PATH."/models"));

// Define Path for controllers folder
define('CONTROLLERS_PATH',realpath(APPLICATION_PATH."/controllers"));

// Define installation directory
define('INSTALL_DIR', '/');

// Define Site Url
define('SITE_URL', 'http://'. $_SERVER['HTTP_HOST']. INSTALL_DIR);
 
// Define Path for css folder
define('CSS_PATH', INSTALL_DIR."public/css" );

// Define Path for js folder
define('JS_PATH', INSTALL_DIR."public/js");

// Define Path for images folder
define('IMAGES_PATH', INSTALL_DIR."public/images");

 // Define Path for languages folder
define('LANGUAGE_PATH', realpath(SITE_ROOT_PATH."/../languages"));

// Define Path for Global View Helpers folder
define('GLOBAL_VIEW_HELPERS', realpath(APPLICATION_PATH."/globalviewhelpers"));

//Site Upload folder path
define('SITE_UPLOAD_FOLDER', realpath(SITE_ROOT_PATH . "/upload"));

//Site Product Images folder path
define('SITE_PRODUCT_IMAGES_FOLDER', realpath(SITE_UPLOAD_FOLDER . "/products"));

//Site upload folder relative path
define('SITE_UPLOAD_FOLDER_PATH', INSTALL_DIR . "public/upload");

//Site Product Images relative path
define('SITE_PRODUCT_IMAGES_PATH', INSTALL_DIR . "public/upload/products");

//Site Banner Images folder path
define('SITE_BANNER_IMAGES_FOLDER', realpath(SITE_UPLOAD_FOLDER . "/banner"));

//Site Banner Images relative path
define('SITE_BANNER_IMAGES_PATH', INSTALL_DIR . "public/upload/banner");

//Category Icons Images folder path
define('SITE_ICONS_IMAGES_FOLDER', realpath(SITE_UPLOAD_FOLDER . "/icons"));

//Category Icons Images relative path
define('SITE_ICONS_IMAGES_PATH', INSTALL_DIR . "public/upload/icons");

// set include_path
set_include_path(implode(PATH_SEPARATOR, array(
	APPLICATION_PATH,LIBRARY_PATH,CONTROLLERS_PATH,MODELS_PATH,
    get_include_path()
)));


// Common functions
require_once LIBRARY_PATH.'/functions.php';

// Global arrays 
require_once LIBRARY_PATH.'/global_arrays.php';

/** Zend_Application */
require_once LIBRARY_PATH.'/Zend/Application.php';


// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

//Golgal variables declaration
/**
 * $mysession will be used to store session information
**/

 global $mysession;

//register name spaces to autoload classes
require_once LIBRARY_PATH."/Zend/Loader/Autoloader.php";
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Models_'); 
$autoloader->registerNamespace('REST_'); 
$autoloader->setFallbackAutoloader(true);

// Create registry object and setting it as the static instance in the Zend_Registry class
$registry = new Zend_Registry();
Zend_Registry::setInstance($registry);

//Initializing Zend Session
$mysession = new Zend_Session_Namespace('Puvoo');

//set default language
if(!isset($mysession->language)){
	$mysession->language = 'en';
}

//Load Translate class to handle multilanguage part
Zend_Loader::loadClass('Zend_Translate');


//setting rest api route
$front = Zend_Controller_Front::getInstance();
$router = $front->getRouter();

	   
//Error Handling
//$front->addModuleDirectory(dirname(__FILE__) . '\../application/modules');

$front->registerPlugin(new Zend_Controller_Plugin_ErrorHandler(array(
    'module'     => 'fb',
    'controller' => 'Error',
    'action'     => 'error'
)));


// Specifying the "rest" module only as RESTful:
$restRoute = new Zend_Rest_Route($front, array(), array('rest'));
$router->addRoute('rest', $restRoute);

//Initialize and/or retrieve a ViewRenderer object on demand via the helper broker
$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
$viewRenderer->initView();
 
//add the global helper directory path
$viewRenderer->view->addHelperPath(GLOBAL_VIEW_HELPERS);

//Run it 
$application->bootstrap()->run();

?>