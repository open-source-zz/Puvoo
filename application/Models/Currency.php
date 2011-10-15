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
 * Class Models_Currency
 *
 * Class Models_Currency contains methods to handel currency.
 *
 * Date created: 2011-09-06
 *
 * @currency	Puvoo
 * @package 	Models
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_Currency
{
	
	private $db;
	
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-06-09
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
	 * Function GetCurrencyId($code)
	 *
	 * This function with get currency id from code
     * 
	 *
	 * Date created: 2011-06-09
	 *
	 * @access public
	 * @param (string)  - $code: code of currnency like USD,GBP etc.
	 * @return (int) - Return currency id if found else 0
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function GetCurrencyId($code)
	{
		$db = $this->db;
		
		$sql = "select currency_id from currency_master where currency_code = '" . $code . "'";
		
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
 	
	/**
	 * Function GetAllCurrency
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
	public function GetAllCurrency()
	{
		$db = $this->db;
		
		$sql = "select * from currency_master";
		
 		$result = $db->fetchAll($sql);
		return $result;
	}

	/*
	 * GetCurrencyById(): To get data of currency by selected currency id.
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
	
	public function GetCurrencyById($id)
	{
		$db = $this->db;
		
		$sql = "select * from currency_master where currency_id = ".$id." order By currency_name asc";
 		$result = $db->fetchRow($sql);
		return $result;
	
	}


		/**
	 * Function SearchCurrency
	 *
	 * This function is used to search the currency from currency_master on search array.
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
	
	
	public function SearchCurrency($data)
	{
		$db = $this->db;
		
		$sql  = "";
		$sql .= "SELECT * FROM currency_master";
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
	 * Function insertCurrency
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
	
	public function insertCurrency($data)
	{
		$db = $this->db;
		
		$db->insert("currency_master", $data); 	 
		//return $db->lastInsertId(); 
		return true; 
	}
	
	/**
	 * Function updateCurrency
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
	
	public function updateCurrency($data,$where)
	{
		$db = $this->db;		
		$db->update("currency_master", $data, $where); 	
		return true;
	}
	
	/**
	 * Function deleteCurrency
	 *
	 * This function is used to delete currency on specified condition.
     *
	 * Date created: 2011-08-26
	 *
	 * @access public
	 * @param () (String)  - $id : Currency Id
	 * @return (Boolean) - Return true on success
	 * @author Vaibhavi
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function deleteCurrency($id)
	{
		$db = $this->db;	
		$db->delete("currency_master", 'currency_id = ' .$id);		
		return true;		
	}
	
	/**
	 * Function deletemultipleCurrency
	 *
	 * This function is used to delete multiple currency on specified condition.
     *
	 * Date created: 2011-08-26
	 *
	 * @access public
	 * @param () (String)  - $ids : Sting of all Currency Id with comma seprated.
	 * @return (Boolean) - Return true on success
	 * @author Vaibhavi
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function deletemultipleCurrency($ids)
	{
		$db = $this->db;	
		$where = 'currency_id in ('.$ids.')'; 			
		$db->delete("currency_master",$where);	 
		
		return true;	
	}

}
?>