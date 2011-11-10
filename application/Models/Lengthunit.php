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
 * Class Models_Lengthunit
 *
 * Class Models_Weight contains methods to handle weight unit.
 *
 * Date created: 2011-09-09
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Amar 
 *  
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_Lengthunit
{
	
	private $db;
	
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-09-09
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function __construct()
	{
		$this->db = Zend_Registry::get('Db_Adapter');
	}
 	
	 /**
	 * Function GetAllLengthunit
	 *
	 * This function is used to get all currency available.
     *
	 * Date created: 2011-08-24
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (Array) - Return Array of records
	 * @author Vaibhavi
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetAllLengthunit()
	{
		$db = $this->db;
		
		$sql = "select a.from_id,(select b.length_unit_name from length_master as b where a.from_id = b.length_unit_id) as from_length,a.to_id,(select b.length_unit_name from length_master as b where a.to_id = b.length_unit_id) as to_length,value from length_unit_conversion as a";
		
 		$result = $db->fetchAll($sql);
		return $result;
	}

		/*
	 * GetLengthunitById(): To get data of currency by selected currency id.
	 *
	 * It is used to get the all records of particular currency by currency id.
	 *
	 * Date created: 2011-08-26
	 *
	 * @author  Vaibhavi 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	
	public function GetLengthunitById($from_id,$to_id)
	{
		$db = $this->db;
		
		$sql = "select * from length_unit_conversion where from_id = ".$from_id." and to_id = ".$to_id;
 		$result = $db->fetchRow($sql);
		return $result;
	
	}


	/**
	 * Function SearchLengthunit
	 *
	 * This function is used to search the currency from length_unit_conversion on search array.
     *
	 * Date created: 2011-08-26
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of search options
	 * @return (Array) - Return Array of records
	 * @author Vaibhavi
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function SearchLengthunit($value)
	{
		$db = $this->db;
		
		$sql  = "";
		
		$sql .= "SELECT * FROM length_unit_conversion";
		
		$sql.=" WHERE value = '".$value."'"; 
		
		// Check search array is null or not
		/*if(count($data) > 0 && $data != "") {
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
		}*/
		
		$result =  $db->fetchAll($sql);		
		return $result;		
	}

		/**
	 * Function insertLengthunit
	 *
	 * This function is used to insert currency.
     *
	 * Date created: 2011-08-26
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to insert
	 * @return (Boolean) - Return true on success
	 * @author Vaibhavi
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function insertLengthunit($data)
	{
		$db = $this->db;
		
		$db->insert("length_unit_conversion", $data); 	 
		//return $db->lastInsertId(); 
		return true; 
	}
	
	/**
	 * Function updateLengthunit
	 *
	 * This function is used to Update currency records on specified condition.
     *
	 * Date created: 2011-08-26
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to update
	 * @param () (String)  - $where : Condition on which update record
	 * @return (Boolean) - Return true on success
	 * @author Vaibhavi
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function updateLengthunit($data,$where)
	{
		$db = $this->db;		
		$db->update("length_unit_conversion", $data, $where); 	
		return true;
	}
	
	/**
	 * Function deleteLengthunit
	 *
	 * This function is used to delete currency on specified condition.
     *
	 * Date created: 2011-08-26
	 *
	 * @access public
	 * @param () (String)  - $id : Weight Id
	 * @return (Boolean) - Return true on success
	 * @author Vaibhavi
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function deleteLengthunit($id1,$id2)
	{
		$db = $this->db;	
		$db->delete("length_unit_conversion", 'from_id = ' .$id1.' and to_id = '.$id2);		
		return true;		
	}
	
	/**
	 * Function deletemultipleLengthunit
	 *
	 * This function is used to delete multiple currency on specified condition.
     *
	 * Date created: 2011-08-26
	 *
	 * @access public
	 * @param () (String)  - $ids : Sting of all Weight Id with comma seprated.
	 * @return (Boolean) - Return true on success
	 * @author Vaibhavi
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function deletemultipleLengthunit($ids,$toids)
	{
		$db = $this->db;	
		$where = 'from_id in ('.$ids.') and to_id in ('.$toids.')' ; 	
		$db->delete("length_unit_conversion",$where);	 
		
		return true;	
	}

    public function ValidateLengthunit($from_id,$to_id)
	{
		$db = $this->db;
		
		$sql = "select count(*) from length_unit_conversion where from_id = ".$from_id." and to_id = ".$to_id;
		//print $sql;die;
 		$data = $db->fetchOne($sql);
		
		return $data;
	}

	
}
?>