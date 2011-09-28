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
	 *  
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
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function GetCountryId($code)
	{
		$db = $this->db;
		
		$sql = "select country_id from country_master where country_iso2 = '" . $code . "'";
		
		$data = $db->fetchOne($sql);
		
		if($data == NULL || $data == '')
		{
			return 0;
		}
		else
		{
			return $data;
		}
		
		
	}
	
	/**
	 * Function GetAllCountries
	 *
	 * This function is used to get all countries.
     *
	 * Date created: 2011-09-26
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (Array) - Return Array of records
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetAllCountries()
	{
		$db = $this->db;
		
		$sql = "select * from country_master";
		
 		$result = $db->fetchAll($sql);
		return $result;
	}
 	
	
	/*
	 * Function getCountryById 
	 *
	 * It is used to get the record of a particular country by country id.
	 *
	 * Date created: 2011-09-26
	 *
	 * @access public
	 * @param (int)  - $id: country id
	 *
	 * @return (array) - Return array or record
     * 
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getCountryById($id)
	{
		$db = $this->db;
		
		$sql = "select * from country_master where country_id = ".$id;
 		$result = $db->fetchRow($sql);
		return $result;
	
	}
	
	
	/**
	 * Function SearchCountry
	 *
	 * This function is used to search countries from country_master on search array.
     *
	 * Date created: 2011-09-26
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of search options
	 * @return (Array) - Return Array of records
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function SearchCountry($data)
	{
		$db = $this->db;
		
		$sql = "SELECT * FROM country_master where 1=1";
		
		if($data["country_name"] != "")
		{
			$sql .= " and country_name like '%" . $data["country_name"] . "%'";
		}
		
		if($data["country_iso2"] != "")
		{
			$sql .= " and country_iso2 like '%" . $data["country_iso2"] . "%'";
		}
		
		if($data["country_iso3"] != "")
		{
			$sql .= " and country_iso3 like '%" . $data["country_iso3"] . "%'";
		}
		
		$result =  $db->fetchAll($sql);
		
		return $result;		
	}
	
	
	/**
	 * Function insertCountry
	 *
	 * This function is used to insert country.
     *
	 * Date created: 2011-09-26
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to insert
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function insertCountry($data)
	{
		$db = $this->db;
		
		$db->insert("country_master", $data); 	 
		
		return true; 
	}
	
	
	/**
	 * Function updateCountry
	 *
	 * This function is used to Update country records on specified condition.
     *
	 * Date created: 2011-09-26
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to update
	 * @param () (String)  - $where : Condition on which update record
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function updateCountry($data,$where)
	{
		$db = $this->db;		
		$db->update("country_master", $data, $where); 	
		return true;
	}
	
	
	/**
	 * Function deleteCountry
	 *
	 * This function is used to delete country on specified condition.
     *
	 * Date created: 2011-09-26
	 *
	 * @access public
	 * @param () (int)  - $id : Country Id
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function deleteCountry($id)
	{
		$db = $this->db;	
		
		//Delete state realted to country
		$db->delete("state_master", 'country_id = ' .$id);		
		
		//Delete country record
		$db->delete("country_master", 'country_id = ' .$id);		
		return true;		
	}
	
	
	/**
	 * Function deletemultipleCountry
	 *
	 * This function is used to delete multiple countries on specified condition.
     *
	 * Date created: 2011-09-26
	 *
	 * @access public
	 * @param  (String)  - $ids : Sting of all Country Id with comma seprated.
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function deletemultipleCountry($ids)
	{
		$db = $this->db;	
		$where = 'country_id in ('.$ids.')'; 
		
		//delete related state records
		$db->delete("state_master",$where);	 
					
		//delete country records
		$db->delete("country_master",$where);	 
		
		return true;
	}
	
	/**
	 * Function getAllStateByContry
	 *
	 * This function is used to get all states by country id.
     *
	 * Date created: 2011-09-28
	 *
	 * @access public
	 * @param  (Int)  - $id : Country Id
	 * @return (Array) - Array of records
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getAllStateByContry($id)
	{
		$db = $this->db;	
		$where = 'country_id='.$id; 
		
		$select = $db->select()
					 ->from("state_master")
					 ->where($where);
		
		return $db->fetchAll($select);	 
		
	}
	
	/**
	 * Function searchState
	 *
	 * This function is used to search state by specified condition.
     *
	 * Date created: 2011-09-28
	 *
	 * @access public
	 * @param  (Array)  - $data : Array of condition value.
	 * @param  (Int)  	- $id : Country Id
	 * @return (Array)  - Return array of records
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	 
	public function searchState($data,$id)
	{
		$db = $this->db;	
		
		$sql = "SELECT * 
		 		FROM state_master 
				WHERE country_id = '".$id."'
				AND state_name LIKE '%".$data["state_name"]."%'"; 
		
		return $db->fetchAll($sql);	 
	}
	
	
	/**
	 * Function insertState
	 *
	 * This function is used to insert state.
     *
	 * Date created: 2011-09-28
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to insert
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	 
	public function insertState($data)
	{
		$db = $this->db;
		
		$db->insert("state_master", $data); 	 
		
		return true; 
	}
	
	/*
	 * Function getStateById 
	 *
	 * It is used to get the record of a particular state by id.
	 *
	 * Date created: 2011-09-28
	 *
	 * @access public
	 * @param (int)  - $id: state id
	 *
	 * @return (array) - Return array or record
     * 
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	 
	public function GetStateById($id)
	{
		$db = $this->db;
		
		$sql = "select * from state_master where state_id = ".$id;
 		$result = $db->fetchRow($sql);
		return $result;
	
	}
	
	
	/**
	 * Function updateState
	 *
	 * This function is used to Update State records on specified condition.
     *
	 * Date created: 2011-09-28
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to update
	 * @param () (String)  - $where : Condition on which update record
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	 
	public function updateState($data,$where)
	{
		$db = $this->db;		
		$db->update("state_master", $data, $where); 	
		return true;
	}
	
	/**
	 * Function deleteState
	 *
	 * This function is used to delete state on specified condition.
     *
	 * Date created: 2011-09-28
	 *
	 * @access public
	 * @param () (int)  - $id : State Id
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function deleteState($id)
	{
		$db = $this->db;	
		
		//Delete state realted to country
		$db->delete("state_master", 'state_id = ' .$id);		
		
		return true;		
	} 
	
	/**
	 * Function deletemultipleState
	 *
	 * This function is used to delete multiple state on specified condition.
     *
	 * Date created: 2011-09-28
	 *
	 * @access public
	 * @param  (String)  - $ids : Sting of all State Id with comma seprated.
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function deletemultipleState($ids)
	{
		$db = $this->db;	
		
		$where = 'state_id in ('.$ids.')'; 
		
		//delete related state records
		$db->delete("state_master",$where);	 
		
		return true;
	}
	
	
}
?>