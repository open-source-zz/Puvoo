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

	/**
	 * function RetailerExist
	 *
	 * It is used to check that retailer is exist or not.
	 *
	 * Date created: 2011-09-10
	 *
	 * @param (int) $retId- Retailer Id.
	 * @return (Array) - Return true on success
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 */
	public function RetailerExist($retId)
	{
		$db= $this->db;
		
		$sql = "SELECT * FROM user_master WHERE user_id = ".$retId."";
		
		$result = $db->fetchRow($sql);
		if($result == NULL || $result == "")
		{
			return false;
		
		}else{ 
			return true;
		}
	
	}

	/**
	 * function GetCurrency
	 *
	 * It is used to get all currency names.
	 *
	 * Date created: 2011-10-12
	 *
	 * @param () - No parameter
	 * @return (Array) - Return array of records
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 */
	public function GetCurrency()
	{
		$db= $this->db;
		
		$sql = "SELECT * FROM currency_master";
		//print $sql;die;
		$result = $db->fetchAll($sql);
		
		return $result;
	
	}

	/**
	 * function GetCurrencyValue
	 *
	 * It is used to get currency value.
	 *
	 * Date created: 2011-10-12
	 *
	 * @param (Int) - $curr_id - currency id
	 * @return (Array) - Return array of records
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 */
	public function GetCurrencyValue($curr_id)
	{
		$db= $this->db;
		
		$sql = "SELECT * FROM currency_master WHERE currency_id = ".$curr_id."";
		//print $sql;die;
		$result = $db->fetchRow($sql);
		
		return $result;
	
	}

 	/**
	 * function GetPaypalDetails 
	 *
	 * It is used to get paypal details of cart user.
	 *
	 * Date created: 2011-10-15
	 *
 	 * @param (int) $userId- User Id.
	 *
	 * @return (Array) - Return number of records
	 *
	 *
	 * @author Jayesh 
	 *
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetPaypalDetails($userId)
	{
		$db= $this->db;
		
 		$sql = "SELECT * FROM user_master WHERE user_id=".$userId."";
		//print $sql;die;		
 		$result = $db->fetchRow($sql);
		 
 		return $result;
	}
	
	/**
	 * function GetPaypalUrl 
	 *
	 * It is used to get paypal url that merchant set at admin.
	 *
	 * Date created: 2011-10-15
	 *
 	 * @return (Array) - Return number of records
	 *
	 * @author Jayesh 
	 *
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetPaypalUrl($pname)
	{
		$db= $this->db;
		
 		$sql = "SELECT configuration_value FROM configuration
				LEFT JOIN configuration_group ON configuration_group.configuration_group_id = configuration.configuration_group_id
				WHERE configuration_group.configuration_group_key = 'Facebook Store' AND configuration_key ='".$pname."'";
		//print $sql;die;		
 		$result = $db->fetchRow($sql);
		 
 		return $result;
	}

}
?>