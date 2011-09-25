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
 * Class Models_UserShippingMethod
 *
 * Class Models_UserShippingMethod contains methods for shipping method management
 *
 * Date created: 2011-08-31
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Yogesh 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_UserShippingMethod
{
	 
	private $db;
	private $user_id;
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	 
	function __construct()
	{
		global $mysession;
		$this->db = Zend_Registry::get('Db_Adapter');
		$this->user_id = $mysession->User_Id;
	}
	
	/**
	 * Function getShippingMethodById
	 *
	 * This function is used to fetch all records of particular shipping methods
	 *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param (string)  - 	No parameters
	 *
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function getShippingMethodById($id)
	{
		$db = $this->db;
		$sql = "SELECT SM.*,SMD.zone, SMD.price 
				FROM user_shipping_method as SM, user_shipping_method_detail as SMD 
				WHERE SM.shipping_method_id = SMD.shipping_method_id AND SM.shipping_method_id = ".$id;		
		$data = $db->fetchRow($sql);
		return $data;
	}
	
	
	/**
	 * Function getAllShippingMethod
	 *
	 * This function is used to fetch all shipping methods
	 *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param (string)  - 	No parameters
	 *
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function getAllShippingMethod()
	{
		$db = $this->db;
		$sql = "SELECT SM.*,SMD.zone, SMD.price 
				FROM user_shipping_method as SM, user_shipping_method_detail as SMD 
				WHERE SM.shipping_method_id = SMD.shipping_method_id and user_id = ".$this->user_id;		
		$data = $db->fetchAll($sql);
		return $data;
	}
	
	/**
	 * Function SearchShippingType
	 *
	 * This function is used to search the shipping type from shipping_type table on search array.
     *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of search options
	 * @return (Array) - Return Array of records
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function SearchShippingMethod($data)
	{
		$db = $this->db;
		
		$sql  = "";
		$sql .= "SELECT SM.*,SMD.zone, SMD.price 
				FROM user_shipping_method as SM, user_shipping_method_detail as SMD 
				WHERE SM.shipping_method_id = SMD.shipping_method_id";
		// Check search array is null or not
		if(count($data) > 0 && $data != "") {
			foreach($data as $key => $val) {				
				if($val != "") {
					$sql.=" AND lower(".$key.") like '%".$val."%'";					
				} 						
			}			
		}
		
		$sql .= " AND user_id = ".$this->user_id;
		$result =  $db->fetchAll($sql);		
		return $result;		
	}
	
	/**
	 * Function selectAllRecord
	 *
	 * This function is used to fetch all record from the specified table.
     *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param () (String)  - $table : Table name 
	 * @return (Array) - Return all records of the table
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function selectAllRecord($table)
	{
		$db = $this->db;
		$select = "SELECT * FROM ".$table;
		$result =  $db->fetchAll($select);		
		return $result;	
	}
	
	/**
	 * Function insertShippingMethod
	 *
	 * This function is used to insert shipping method 
     *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to insert
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function insertShippingMethod($data,$table = "user_shipping_method")
	{
		$db = $this->db;
		
		$db->insert($table, $data); 	 
		
		return $db->lastInsertId();
	}
	
	/**
	 * Function updateShippingMethod
	 *
	 * This function is used to Update shipping method on specified condition.
     *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to update
	 * @param () (String)  - $where : Condition on which update record
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function updateShippingMethod($data,$where,$table="user_shipping_method")
	{
		$db = $this->db;		
		$db->update($table, $data, $where); 	
		return true;
	}
	
	/**
	 * Function deleteShippingMethod
	 *
	 * This function is used to delete shipping method on specified condition.
     *
	 * Date created: 2011-08-31
	 *  
	 * @access public
	 * @param () (String)  - $id : Shipping Method Id
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function deleteShippingMethod($id)
	{
		$db = $this->db;	
		
		if($db->delete("user_shipping_method", 'shipping_method_id = ' .$id)){
			return true;	
		} else {
			return false;
		}		
				
	}
	
	/**
	 * Function deletemultipleShippingMethod
	 *
	 * This function is used to delete multiple shipping method on specified condition.
     *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param () (String)  - $ids : String of all Shipping Method Id with comma seprated.
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function deletemultipleShippingMethod($ids)
	{
		$db = $this->db;	
		$where = 'shipping_method_id in ('.$ids.')'; 			
		$db->delete("user_shipping_method",$where);	 		
		return true;	
	}
	
	
	
	/**
	 * Function ValidateTableField
	 *
	 * This function is used check particular condition on table field.
     *
	 * Date created: 2011-09-07
	 *
	 * @access public
	 * @param () (String)  - $field : field name in table
	 * @param () (String)  - $value : field value to search in table
	 * @param () (String)  - $where : where condition to apply
	 *
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function ValidateTableField($field,$value,$table,$where)
	{
		$db = $this->db;		
		//$clause = $db->quoteInto($primary_key.'=?', $value);		
		$validator = new Zend_Validate_Db_NoRecordExists(
				array(
						'table' => $table,
						'field' => $field,
						'exclude' => $where
				)
		);
		
		if ($validator->isValid($value)) {
			return true;
		} else {
			return false;
		}
	
	}
	
	/**
	 * Function getShippingMethodIdByName
	 *
	 * This function is used to fetch shipping method id from its name
	 *
	 * Date created: 2011-09-08
	 *
	 * @access public
	 * @param (string)  - $method_name: shipping method name
	 * @param (int)  - $user_id: user id
	 *
	 * @return (int) - it will rerun method id
	 *
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function getShippingMethodIdByName($method_name, $user_id)
	{
		$db = $this->db;
		$sql = "SELECT shipping_method_id 
				FROM user_shipping_method 
				WHERE shipping_method_name = '" . $method_name . "' and user_id = " . $user_id;		
		$data = $db->fetchOne($sql);
		
		if($data != null || $data != '')
		{
			return $data;
		}
		else
		{
			return 0;
		}
		
	}
	
	/**
	 * Function getShippingDetailIdByMethodId
	 *
	 * This function is used to fetch shipping method id from its name
	 *
	 * Date created: 2011-09-08
	 *
	 * @access public
	 * @param (int)  - $method_id: shipping method id
	 *
	 * @return (int) - it will rerun shipping method detail id
	 *
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function getShippingDetailIdByMethodId($method_id)
	{
		$db = $this->db;
		$sql = "SELECT user_shipping_method_detail_id 
				FROM user_shipping_method_detail 
				WHERE shipping_method_id = " . $method_id;		
		$data = $db->fetchOne($sql);
		
		if($data != null || $data != '')
		{
			return $data;
		}
		else
		{
			return 0;
		}
		
	}
}
?>
