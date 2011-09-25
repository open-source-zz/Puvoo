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
 * Class Models_Country
 *
 * Class Models_Country contains methods to handel country.
 *
 * Date created: 2011-09-08
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_Country
{
	
	private $db;
	
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-09-08
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function __construct()
	{
		$this->db = Zend_Registry::get('Db_Adapter');
	}
	
	/**
	 * Function GetCountryId($code)
	 *
	 * This function with get country id from code
     * 
	 *
	 * Date created: 2011-06-09
	 *
	 * @access public
	 * @param (string)  - $code: code of country like US,IT etc.
	 * @return (int) - Return country id if found else 0
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function GetCountryId($code)
	{
		$db = $this->db;
		
		$sql = "select country_id from country_master where country_iso2 = '" . $code . "'";
		
		$data = $db->fetchOne($sql);
		
		if($data == null || $data == '')
		{
			return 0;
		}
		else
		{
			return $data;
		}
		
		
	}
 	
	
	
}
?>
