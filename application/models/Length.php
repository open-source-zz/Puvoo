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
 * Class Models_Length
 *
 * Class Models_Length contains methods to handel length unit.
 *
 * Date created: 2011-09-09
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Yogesh 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_Length
{
	
	private $db;
	
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-09-24
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author 		Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function __construct()
	{
		$this->db = Zend_Registry::get('Db_Adapter');
	}
	
	/**
	* Function GetAllRecords
	*
	* This function is used to get all available records from table.
    *
	* Date created: 2011-09-24
	*
	* @access public
    * @param (String)  - $table: Table name
	* @return (Array) - Return Array of records
	*
	* @author Yogesh
	*  
	* @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	**/
	public function GetAllRecords($table = TABLE)
	{
		$db = $this->db;
		
		$select = $db->select()
					 ->from(TABLE);
					 
 		$result = $db->fetchAll($select);
		
		return $result;
	}
	
	/**
	 * Function SearchRecords
	 *
	 * This function is used to search the records from defined table on search array.
     *
	 * Date created: 2011-09-24
	 *
	 * @access public
	 * @param (Array)  - $data : Array of search options
	 * @param (String)  - $table : Table name
	 * @return (Array) - Return Array of records
	 *
	 * @author 	Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function SearchRecords($data, $table = TABLE)
	{
		$db = $this->db;
		
		$sql  = "";
		$sql .= "SELECT * FROM ".$table;
		// Check search array is null or not
		if(count($data) > 0 && $data != "") {
			$count = 0;		
			foreach($data as $key => $val) {
				if( $count == 0 ) {
				
					if($val != "") {
						$sql.=" WHERE lower(".$key.") like '%".$val."%'";
					} else {
						$sql.=" WHERE 1=1";
					}
					
				} else {
				
					if($val != "") {
						$sql.=" AND lower(".$key.") like '%".$val."%'";					
					} 						
				}
								
				$count++;
			}
			
		} else {
		
			$sql .= " WHERE 1=1";
		}
		
		$result =  $db->fetchAll($sql);		
		return $result;		
	}
	
	/**
	 * Function insertRecord
	 *
	 * This function is used to insert record on defined table.
     *
	 * Date created: 2011-09-24
	 *
	 * @access public
	 * @param (Array)  		- $data : Array of record to insert
	 * @param (String)  	- $table : Table name
	 * @return (Boolean) 	- Return true on success
	 *
	 * @author 		Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function insertRecord($data, $table = TABLE)
	{
		$db = $this->db;
		
		$db->insert($table, $data); 	 
		
		return true; 
	}

	
	/*
	 * Function GetRecordById
	 *
	 * It is used to get the all data of particular record by primary id.
	 *
	 * Date created: 2011-09-24
	 *
	 * @access public
	 * @param (Ini)  		- $id : Value of primary id
	 * @param (String)  	- $table : Table name
	 * @param (String)  	- $primary_key : Name of primary id 
	 * @return (Boolean) 	- Return true on success
	 *
	 * @author 		Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function GetRecordById($id,$table = TABLE, $primary_key = PRIMARY_KEY)
	{
		$db = $this->db;
		
		$select = $db->select()
					 ->from($table)
					 ->where($primary_key." = ".$id);
					 
 		$result = $db->fetchRow($select);
		
		return $result;
	
	}
	
	/**
	 * Function updateRecord
	 *
	 * This function is used to update records on specified condition.
     *
	 * Date created: 2011-09-24
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to update
	 * @param () (String)  - $where : Condition on which update record
	 * @param () (String)  - $table : Table name
	 *
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function updateRecord($data,$where,$table = TABLE)
	{
		$db = $this->db;		
		$db->update($table, $data, $where); 	
		return true;
	}
	
	
	/**
	 * Function deleteRcord
	 *
	 * This function is used to delete record on specified condition.
     *
	 * Date created: 2011-09-24
	 *
	 * @access public
	 * @param (Int)  	- $id : value of primary id
	 * @param (String)  - $table : Table name
	 * @param (String) 	- $primary_key : Name of primary id
	 * @return (Boolean) - Return true on success
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function deleteRcord($id, $table = TABLE, $primary_key = PRIMARY_KEY)
	{
		$db = $this->db;	
		$db->delete($table, $primary_key.' = ' .$id);		
		return true;		
	}
	
	/**
	 * Function deletemultipleRecord
	 *
	 * This function is used to delete multiple records on specified condition.
     *
	 * Date created: 2011-09-24
	 *
	 * @access public
	 * @param (String)  - $ids : Sting of all records primary id with comma seprated.
	 * @param (String)  - $table : Table name
	 * @param (String)  - $primary_key : Name of primary key.
	 * @return (Boolean) - Return true on success
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function deletemultipleRecord($ids, $table = TABLE, $primary_key = PRIMARY_KEY)
	{
		$db = $this->db;	
		$where = $primary_key.' in ('.$ids.')'; 			
		$db->delete($table,$where);	 
		return true;	
		
	}
	
	/**
	 * Function getLengthIdFromKey
	 *
	 * This function with get length unit id from unit key
     * 
	 * Date created: 2011-09-24
	 *
	 * @access public
	 * @param (string)  - $unit_key: unit key.
	 *
	 * @return (int) - Return length id if found else 0
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function getLengthIdFromKey($unit_key)
	{
		$db = $this->db;
		
		$sql = "select length_unit_id from length_master where length_unit_key = '" . $unit_key . "'";
		
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