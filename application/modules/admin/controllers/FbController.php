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
 * Admin Fb Controller.
 *
 * Admin_FbController. It is used to ser facebook store settings from admin section.
 *
 * Date Created: 2011-10-07
 *
 * @category	Puvoo
 * @package 	Admin_Controllers
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/  
class Admin_FbController extends AdminCommonController 
{
	/**
     * Function init
	 *
	 * This function is used for initialization. 
	 * You can also include necessary javascript files from here.
	 *
     * Date Created: 2011-08-20
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	function init()
    {
        parent::init();
        //$this->view->JS_Files = array('admin/AdminCommon.js');			
	}
	
	
	/**
     * Function indexAction
	 *
	 * This function is empty currntly.
	 *
     * Date Created: 2011-08-20
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/  
	function indexAction() 
	{
		
	}
   
   
	/**
     * Function contactusAction
	 *
	 * This function is used to set contact us related informations.
	 *
     * Date Created: 2011-10-07
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Amar
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/  
	function contactusAction() 
	{
		$Configuration = new Models_Configuration();
		$home = new Models_AdminMaster();
		
		$data = array();
		
		$request = $this->getRequest();
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		if($request->isPost())
		{
			
			
			$filter = new Zend_Filter_StripTags();	
			
			$data['contact_us_address'] = $filter->filter(trim($request->getPost('contact_us_address')));
			$data['contact_us_telephone'] = $filter->filter(trim($request->getPost('contact_us_telephone')));
			$data['contact_us_email'] = $filter->filter(trim($request->getPost('contact_us_email')));
			
			$id = $filter->filter(trim($request->getPost('configuration_group_id')));
			
			$updateError = array();
			
			if($data['contact_us_address'])
			{
				$updateError[] = $translate->_('Err_Contact_Address');
			}
			
			if($data['contact_us_telephone'])
			{
				$updateError[] = $translate->_('Err_Contact_Tel');
			}
			
			if($data['contact_us_email'])
			{
				$updateError[] = $translate->_('Err_Contact_Email');
			}
			
			if(count($updateError == 0))
			{
				//update record
				$Configuration->updateKeyValueForGroup($id,$data);
				
				$this->view->updateSuccess = $translate->_('Succ_Contact_Us');
			}
			else
			{
				$this->view->updateError = $updateError;
			}
			
		}
		
		//Get Configuration for Facebook Store
		
		//Check if Facebook Store group is present in configuration
		$where = "1=1";
		$id = 0;
		
		if($home->ValidateTableField("configuration_group_key","Facebook Store","configuration_group",$where)) 
		{
			
			$id = $Configuration->addFacebookGroup();
			
			//$Configuration->addContactKey($id);
		}
		else
		{
			
			$id = $Configuration->GetConfigurationGroupId("Facebook Store");
			
			$where = "configuration_group_id = " . $id;
			if($home->ValidateTableField("configuration_key","contact_us_address","configuration",$where)) 
			{	
				//Insert the key
				$Configuration->insertKeyForGroup($id,'contact_us_address');
				
			}
			
			if($home->ValidateTableField("configuration_key","contact_us_telephone","configuration",$where)) 
			{	
				//Insert the key
				$Configuration->insertKeyForGroup($id,'contact_us_telephone');
				
			}
			
			if($home->ValidateTableField("configuration_key","contact_us_email","configuration",$where)) 
			{	
				//Insert the key
				$Configuration->insertKeyForGroup($id,'contact_us_email');
				
			}
		}
		$record = array();
		
		echo $id; die;
		
		$res = $Configuration->getKeyValueForGroup($id,array("contact_us_address","contact_us_telephone","contact_us_email"));
		
		if($res != "" && is_array($res))
		{
			for($i = 0; $i < count($res); $i++)
			{
				$this->view->$res[$i]["configuration_key"]	= $res[$i]["configuration_value"];		
			}
		}
		
		$this->view->record = $record;
		$this->view->configuration_group_id = $id;
	}
	
	
	/**
     * Function paypalAction
	 *
	 * This function is used to set paypal seettings.
	 *
     * Date Created: 2011-10-15
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/  
	 
	function paypalAction() 
	{
		$Configuration = new Models_Configuration();
		$home = new Models_AdminMaster();
		
		$data = array();
		
		$request = $this->getRequest();
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		if($request->isPost())
		{
			$filter = new Zend_Filter_StripTags();	
			
			$data['paypal_url'] = $filter->filter(trim($request->getPost('paypal_url')));
			
			$id = $filter->filter(trim($request->getPost('configuration_group_id')));
			
			$updateError = array();
			
			if( $data['paypal_url'] == '' )
			{
				$updateError[] = $translate->_('Err_Paypal_Url');
			}
			
			if(count($updateError) == 0)
			{
				//update record
				$Configuration->updateKeyValueForGroup($id,$data);
				
				$this->view->updateSuccess = $translate->_('Succ_Paypal');
			}
			else
			{
				$this->view->updateError = $updateError;
			}
			
			foreach( $data as $key => $val )
			{
				$this->view->$key = $val;		
			}
			
			
		} else 	{
			
			$record = array();
			$id = $Configuration->GetConfigurationGroupId("Facebook Store");
			
			$key_array = array(
								"paypal_url",
							  ); 
			
			$res = $Configuration->getKeyValueForGroup($id,$key_array);
			
			if($res != "" && is_array($res))
			{
				foreach( $res as $key => $val )
				{
					$this->view->$val["configuration_key"] = $val["configuration_value"];		
				}
			}
		}
		
		//$this->view->record = $record;
		$this->view->configuration_group_id = $id;
	}
	
}
?>