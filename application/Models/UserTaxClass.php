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
 * Class Models_UserTaxes
 *
 * Class Models_UserTaxes contains methods for Taxe management
 *
 * Date created: 2011-11-09
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Hiren 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_UserTaxClass
{
	 
	private $db;
	private $user_id;
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-11-09
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Hiren
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	 
	function __construct()
	{
		global $mysession;
		$this->db = Zend_Registry::get('Db_Adapter');
		$this->user_id = $mysession->User_Id;
	}
	
	/**
	 * Function getRecordsById
	 *
	 * This function is used to fetch a row of table by primary key
	 *
	 * Date created: 2011-11-09
	 *
	 * @access public
	 * @param (Int)  	- $id : Value of primary key
	 * @param (string)  - $primary_key :	Name of primary key
	 * @param (string)  - $table : Table name
	 * @return (Array) 	- $data : One selected row by primary key of table
	 * @author Hiren
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function getRecordsById($id,$primary_key = USER_PRIMARY_KEY ,$table=USER_TABLE)
	{
		$db = $this->db;
		$sql = "SELECT * FROM tax_rate_class TRC
				WHERE TRC.user_id = ".$this->user_id." AND TRC.".$primary_key."=".$id;	
				
		$data = $db->fetchRow($sql);
		return $data;
	}
	
	
	/**
	 * Function getAllRecors
	 *
	 * This function is used to fetch all recors of table 
	 *
	 * Date created: 2011-11-09
	 *
	 * @access public
	 * @param (string)  - $table : Table name
	 * @return (Array) 	- All records of the table
	 * @author Hiren
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function getAllRecors($table = USER_TABLE)
	{
		$db = $this->db;
		$sql = "SELECT * FROM ".$table." WHERE user_id = ".$this->user_id;		
		$data = $db->fetchAll($sql);
		return $data;
	}
	
	/**
	 * Function SearchTaxes
	 *
	 * This function is used to search tax rates on condition
	 *
	 * Date created: 2011-11-09
	 *
	 * @access public
	 * @param (Array)   - $data : Condition array
	 * @param (string)  - $table : Table name
	 * @return (Array) 	- All matched records of the table
	 * @author Hiren
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function SearchTaxes($data,$table = USER_TABLE)
	{
				
		$db = $this->db;
		$sql = "Select TRC.* from tax_rate_class TRC WHERE TRC.user_id = ".$this->user_id;
		
		// Check search array is null or not
		if(count($data) > 0 && $data != "") {
			foreach($data as $key => $val) {				
				if($val != "") {
					$sql.=" AND lower(".$key.") like '%".$val."%'";					
				} 						
			}			
		}
		
		$sql .= " AND user_id = ".$this->user_id;
		$data = $db->fetchAll($sql);
		
		foreach($data as $key=>$val)
		{
			$zone = $val['tax_zone'];
			if(substr($val['tax_zone'], -1) == ",")
			{
				$zone = substr($val['tax_zone'], 0, -1);
			}
			
			$sq = "select group_concat(tax_name separator ',') as zone from tax_rate where tax_rate_id in(".$zone.") group by 'all';";
			$d = $db->fetchOne($sq);
			$data[$key]['zone'] = $d;
			
		}
		return $data;
	}
	
	/**
	 * Function selectAllRecord
	 *
	 * This function is used to get all records of the table
	 *
	 * Date created: 2011-11-09
	 *
	 * @access public
	 * @param (string)  - $table : Table name
	 * @author Hiren
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function selectAllRecord($table = USER_TABLE)
	{
		$db = $this->db;
		$select = "SELECT * FROM ".$table;
		$result =  $db->fetchAll($select);		
		return $result;	
	}
	
	
	/**
	 * Function selectAllZone
	 *
	 * This function is used to get all records of the table
	 *
	 * Date created: 2011-11-09
	 *
	 * @access public
	 * @author Hiren
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function selectAllZone()
	{
		$db = $this->db;
		$select = "SELECT tax_rate_id as tr, tax_name as tn FROM tax_rate where user_id = ".$this->user_id;
		$result =  $db->fetchAll($select);		
		return $result;	
	}
	
	
	/**
	 * Function selectAllZone
	 *
	 * This function is used to get all records of the table
	 *
	 * Date created: 2011-11-09
	 *
	 * @access public
	 * @param (string)  - $rec : comma saprated zone id
	 * @author Hiren
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function selectAllZoneEdit($rec)
	{
		$db = $this->db;
		$select = "SELECT tax_rate_id as tr, tax_name as tn FROM tax_rate where user_id = ".$this->user_id;
		$result =  $db->fetchAll($select);	
		
		$all_zone = explode(',',$rec['tax_zone']);
		foreach($result as $key=>$value)
		{
			$result[$key]['selected'] = "";
			foreach($all_zone as $zone)
			{
				if($zone == $value['tr'])
				{
					$result[$key]['selected'] = 'selected="selected"';
				}
			}
		}
		return $result;	
	}

	/**
	 * Function insertRecord
	 *
	 * This function is used to insert record
     *
	 * Date created: 2011-11-09
	 *
	 * @access public
	 * @param () (Array)  	- $data : Array of record to insert
	 * @param () (String)  	- $table : Name of table
	 * @return (Boolean) - Return true on success
	 * @author Hiren
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function insertRecord($data,$table = USER_TABLE)
	{
		$db = $this->db;
		if($data['is_default'] == "1")
		{
			$data2['is_default'] = "0";
			$where2 = " user_id = ".$this->user_id;
			$db->update($table, $data2, $where2);	
		}
		$db->insert($table, $data); 	 
		
		return $db->lastInsertId();
	}
	
	/**
	 * Function updateRecord
	 *
	 * This function is used to Update Record on specified condition.
     *
	 * Date created: 2011-11-09
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to update
	 * @param () (String)  - $where : Condition on which update record
	 * @param () (String)  - $table : Table name
	 * @return (Boolean) - Return true on success
	 * @author Hiren
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function updateRecord($data,$where,$table = USER_TABLE)
	{
		$db = $this->db;
		if($data['is_default'] == "1")
		{
			$data2['is_default'] = "0";
			$where2 = " user_id = ".$this->user_id;
			$db->update($table, $data2, $where2);	
		}
		$db->update($table, $data, $where);				
		return true;		
	}
	
	
	
	/**
	 * Function deleteRecord
	 *
	 * This function is used to delete record on specified condition.
     *
	 * Date created: 2011-11-09
	 *  
	 * @access public
	 * @param () (Ini)  	- $id : value of primary key
	 * @param () (String)  	- $primary_key : name of primary key
	 * @param () (String)  	- $table : Table name
	 * @return (Boolean) - Return true on success
	 * @author Hiren
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function deleteRecord($id,$primary_key = USER_PRIMARY_KEY, $table = USER_TABLE)
	{
		$db = $this->db;	
		
		if($db->delete($table, $primary_key.' = '.$id)){
			return true;	
		} else {
			return false;
		}		
				
	}
	
	/**
	 * Function deleteAllRecords
	 *
	 * This function is used to delete multiple records on specified condition.
     *
	 * Date created: 2011-11-09
	 *
	 * @access public
	 * @param () (String)  	- $ids : Sting of all primary keys with comma seprated.
	 * @param () (String)  	- $primary_key : name of primary key
	 * @param () (String)  	- $table : Table name
	 * @return (Boolean) - Return true on success
	 * @author Hiren
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function deleteAllRecords($ids,$primary_key = USER_PRIMARY_KEY, $table = USER_TABLE)
	{
		$db = $this->db;	
		$where = $primary_key.' in ('.$ids.')'; 			
		$db->delete($table,$where);	 		
		return true;	
	}
	
	/**
	 * Function getAllRateRecors
	 *
	 * This function is used to fetch all record of the tax rate class.
     *
	 * Date created: 2011-11-09
	 *
	 * @access public
	 * @param ()  -	 No Parameters
	 * @author Hiren
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function getAllRateRecors()
	{
		$db = $this->db;
		$sql = "Select TRC.* from tax_rate_class TRC WHERE TRC.user_id = ".$this->user_id;
		
		$data = $db->fetchAll($sql);
		
		foreach($data as $key=>$val)
		{
			$zone = $val['tax_zone'];
			if(substr($val['tax_zone'], -1) == ",")
			{
				$zone = substr($val['tax_zone'], 0, -1);
			}
			
			$sq = "select group_concat(tax_name separator ',') as zone from tax_rate where tax_rate_id in(".$zone.") group by 'all';";
			$d = $db->fetchOne($sq);
			$data[$key]['zone'] = $d;
			
		}
		return $data;
	}
}
?>