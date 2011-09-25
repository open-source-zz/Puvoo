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
 * Class Models_Merchants
 *
 * Class Models_Merchants contains methods that handle Merchants(Users) on site.
 *
 * Date created: 2011-08-31
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Yogesh 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_Merchants
{
	
	private $db;
	
	
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
		$this->db = Zend_Registry::get('Db_Adapter');
	}
 	
	/*
	 *  Function : GetMerchants()
	 *
	 * It is used to get all Merchants from the database whose status is active .
	 *
	 * Date created: 2011-08-31
	 *
	 * @param ()  No Parameters
     * @return (Array)  - $result : Array of merchants records
                
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
 	 
	public function GetMerchants()
	{
		$db = $this->db;
		
		$sql = "select * from user_master where user_status = '1'";
 		$result = $db->fetchAll($sql);
		return $result;
 	} 
	
	
	/*
	 *  Function : GetMerchantsById()
	 *
	 * It is used to get Merchant's data by their id .
	 *
	 * Date created: 2011-08-31
	 *
	 * @param (Int) 	- $id : Value of merchant id
     * @return (Array)  - $result : Array of merchant's data
                
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function GetMerchantsById($id)
	{
		$db = $this->db;
		
		$sql = "select * from user_master where user_id = ".$id;
 		$result = $db->fetchRow($sql);
		return $result;
	
	}

	/*
	 *  Function : GetAllMerchants()
	 *
	 * It is used to get all Merchants from the database .
	 *
	 * Date created: 2011-08-31
	 *
	 * @param ()  No Parameters
     * @return (Array)  - $result : Array of merchants records
                
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function GetAllMerchants()
	{
		$db = $this->db;
		
		$sql = "select * from user_master";
		
 		$result = $db->fetchAll($sql);
		return $result;
	}
	
	/**
	 * Function GetMerchantsDetail
	 *
	 * This function is used to get all details of that particular merchants.
     *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param (Int)  -	$merid : Value of user id
	 * @return (Array) - Return Array of records
	 *
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	 
	public function GetMerchantsDetail($merid)
	{
		$db = $this->db;
		
		$sql = "select * from user_master where user_id = ".$merid."";
		
		$result =  $db->fetchRow($sql);
		
		return $result;
	
	}
	
	/**
	 * Function SearchMerchants
	 *
	 * This function is used to search the users from user_master on search array.
     *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param (Array)  - $data : Array of search options
	 * @return (Array) - Return Array of records
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function SearchMerchants($data)
	{
		$db = $this->db;
		
		$sql  = "";
		$sql .= "SELECT * FROM user_master";
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
		
		//echo $sql; die;
		
		$result =  $db->fetchAll($sql);		
		return $result;		
	}
	
	
	/**
	 * Function insertMerchants
	 *
	 * This function is used to add Merchant(User).
     *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param (Array)  	- $data : Array of Merchant(User) data
	 * @return () 		 	- Return true on success
	 *
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function insertMerchants($data)
	{
		$db = $this->db;
		$db->insert("user_master", $data); 	 
		return true; 
	}
	
	/**
	 * Function updateMerchants
	 *
	 * This function is used to update Merchant's(User) records.
     *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param (Array)  		- $data : Array of Merchant(User) data
	 * @param (String)  	- $where : String of condition
	 * @return () 		 	- Return true on success
	 *
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function updateMerchants($data,$where)
	{
		$db = $this->db;		
		$db->update("user_master", $data, $where); 	
		return true;
	}
	
	/**
	 * Function deleteMerchants
	 *
	 * This function is used to delete Merchant(User).
     *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param (Int)  		- $id : Value of Merchants id
	 * @return () 		 	- Return true on success
	 *
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function deleteMerchants($id)
	{
		$db = $this->db;	
		$db->delete("user_master", 'user_id = ' .$id);		
		return true;		
	}
	
	/**
	 * Function deletemultipleMerchants
	 *
	 * This function is used to delete multiple Merchant(User).
     *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param (String) 		- $ids : String of Merchant's ids with comma seprated 
	 * @return () 		 	- Return true on success
	 *
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function deletemultipleMerchants($ids)
	{
		$db = $this->db;	
		$where = 'user_id in ('.$ids.')'; 			
		$db->delete("user_master",$where);	 		
		return true;	
	}
	
}
?>
