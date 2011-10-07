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
 * Class Models_Common
 *
 * Class Models_Common contains methods handle common on site.
 *
 * Date created: 2011-10-07
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Jayesh 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */ 
class Models_Common
{
	
	private $db;
	
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-11-07
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Jayesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function __construct()
	{
		$this->db = Zend_Registry::get('Db_Adapter');
	}

	/**
	 * function GetfacebookBanner 
	 *
	 * It is used to get banner that can be display on home page.
	 *
	 * Date created: 2011-10-07
	 *
 	 * @return (Array) - Return number of records
	 *
	 * @author Jayesh 
	 *
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetfacebookBanner()
	{
		$db= $this->db;
		
 		$sql = "SELECT * FROM facebook_banner WHERE is_active = '1' limit 0,5";
		//print $sql;die;		
 		$result = $db->fetchAll($sql);
		 
 		return $result;
	}
	
	/**
	 * function GetContactUsDetails 
	 *
	 * It is used to get contact us details.
	 *
	 * Date created: 2011-10-07
	 *
 	 * @return (Array) - Return number of records
	 *
	 * @author Jayesh 
	 *
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetContactUsDetails()
	{
		$db= $this->db;
		
 		$sql = "SELECT * FROM configuration
				LEFT JOIN configuration_group ON configuration_group.configuration_group_id = configuration.configuration_group_id
				WHERE configuration_group.configuration_group_key = 'Facebook Store' AND configuration_key IN ('contact_us_address', 'contact_us_telephone', 'contact_us_email')";
		//print $sql;die;		
 		$result = $db->fetchAll($sql);
		 
 		return $result;
	}
	
}
?>