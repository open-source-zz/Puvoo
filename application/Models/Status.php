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
 * Class Models_Status
 *
 * Class Models_Status contains methods to handle system status for the order.
 *
 * Date created: 2011-11-04
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Yogesh
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */   
 
class Models_Status
{
	
	private $db;
	
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function __construct()
	{
		$this->db = Zend_Registry::get('Db_Adapter');
	}
	
	
	/**
	* Function GetAllBanner
	*
	* This function is used to get all available order status.
    *
	* Date created: 2011-10-07
	*
	* @access public
    * @param ()  - No parameter
	* @return (Array) - Return Array of records
	* @author Yogesh
	*  
	* @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	**/
	public function GetAllStatus()
	{
		$db = $this->db;
		
		$sql = "select * from order_status";
		
 		$result = $db->fetchAll($sql);
		return $result;
	}
	
	/*
	 * GetBannerById(): To get data of banner by selected facebook banner id.
	 *
	 * It is used to get the all records of particular Banner by facebook banner id.
	 *
	 * Date created: 2011-10-07
	 *
	 * @author  Yogesh
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	
	public function GetStatusById($id)
	{
		$db = $this->db;
		
		$sql = "select * from order_status where order_status_id = ".$id;
 		$result = $db->fetchRow($sql);
		return $result;
	
	}
	
	/**
	 * Function deleteBanner
	 *
	 * This function is used to delete facebook banner on specified condition.
     *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param () (String)  - $id : facebook banner Id
	 * @return (Boolean) - Return true on success
	 * @author Vaibhavi
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function deleteStatus($id)
	{
		$db = $this->db;	
		$db->delete("order_status", 'order_status_id = ' .$id);		
		return true;		
	}
	
	/**
	 * Function deletemultipleBanner
	 *
	 * This function is used to delete multiple facebook banner on specified condition.
     *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param () (String)  - $ids : Sting of all Facebook banner id with comma seprated.
	 * @return (Boolean) - Return true on success
	 * @author Vaibhavi
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function deletemultipleStatus($ids)
	{
		$db = $this->db;	
		$where = 'order_status_id in ('.$ids.')'; 			
		$db->delete("order_status",$where);	 
		
		return true;	
	}
	
	
	
	/**
	 * Function insertBanner
	 *
	 * This function is used to insert facebook banner.
     *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to insert
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function insertStatus($data)
	{
		$db = $this->db;
		
		$db->insert("order_status", $data); 	 
		
		return true; 
	}
	
	
	/**
	 * Function updateStatus
	 *
	 * This function is used to Update facebook banner records on specified condition.
     *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to update
	 * @param () (String)  - $where : Condition on which update record
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function updateStatus($data,$where)
	{
		$db = $this->db;		
		$db->update("order_status", $data, $where); 	
		return true;
	}
	
}
?>