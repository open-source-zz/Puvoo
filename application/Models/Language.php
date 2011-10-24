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
 * Class Models_Language
 *
 * Class Models_Language contains methods handle categories on site.
 *
 * Date created: 2011-09-17
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_Language
{
	
	private $db;
	
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-09-17
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
 	
	/*
	 * getLanguageById(): 
	 *
	 * It is used to get the record of a particular language by language id.
	 *
	 * Date created: 2011-09-17
	 *
	 * @access public
	 * @param (int)  - $id: language id
	 *
	 * @return (array) - Return array or record
     * 
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getLanguageById($id)
	{
		$db = $this->db;
		
		$sql = "select * from language_master where language_id = ".$id;
 		$result = $db->fetchRow($sql);
		return $result;
	
	}

	 
	/**
	 * Function getAllLanguages
	 *
	 * This function is used to get all languages available.
     *
	 * Date created: 2011-09-17
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (Array) - Return Array of records
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getAllLanguages()
	{
		$db = $this->db;
		
		$sql = "select * from language_master order by sort_order";
		
 		$result = $db->fetchAll($sql);
		return $result;
	}
	
	
	
	/**
	 * Function SearchLanguages
	 *
	 * This function is used to search the language from language_master on search array.
     *
	 * Date created: 2011-09-17
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of search options
	 * @return (Array) - Return Array of records
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function SearchLanguages($data)
	{
		$db = $this->db;
		
		$sql = "SELECT * FROM language_master where 1=1";
		
		if($data["name"] != "")
		{
			$sql .= " and name like '%" . $data["name"] . "%'";
		}
		
		if($data["status"] == "0" || $data["status"] == "1")
		{
			$sql .= " and status = '" . $data["status"] . "'";
		}
		
		$result =  $db->fetchAll($sql);
		
		return $result;		
	}
	
	
	/**
	 * Function insertLanguage
	 *
	 * This function is used to insert language.
     *
	 * Date created: 2011-09-17
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to insert
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function insertLanguage($data)
	{
		$db = $this->db;
		
		$id = 0;
		
		if( $data["is_default"] == 1 ) {
			
			$data1["is_default"] = 0;
			$where1 = "1=1";
			$db->update("language_master", $data1, $where1); 	
		}
		
		$db->insert("language_master", $data); 	 
		
		$id = $db->lastInsertId();
		
		//Insert definitions for latest added language
		$sql = "INSERT into language_definitions (language_id, content_group,definition_key,definition_value, status) 
				(SELECT '".$id."', content_group, definition_key, definition_value,status from language_definitions  
				where language_definitions.language_id = 1)";
		
		$db->query($sql);	
		
		
		return true; 

	}
	
	/**
	 * Function updateLanguage
	 *
	 * This function is used to Update language record on specified condition.
     *
	 * Date created: 2011-09-17
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to update
	 * @param () (String)  - $where : Condition on which update record
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function updateLanguage($data,$where)
	{
		$db = $this->db;
		
		if( $data["is_default"] == 1 ) {
			
			$data1["is_default"] = 0;
			$where1 = "1=1";
			$db->update("language_master", $data1, $where1); 	
		}
		
		$db->update("language_master", $data, $where); 	
		return true;
	}
	
	/**
	 * Function deleteLanguage
	 *
	 * This function is used to delete language.
     *
	 * Date created: 2011-09-17
	 *
	 * @access public
	 * @param () (String)  - $id : language id
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function deleteLanguage($id)
	{
		$db = $this->db;	
		
		$where = "is_default = 1"; 
		
		$validator = new Zend_Validate_Db_NoRecordExists(
				array(
						'table' => "language_master",
						'field' => "language_id",
						'exclude' => $where
				)
		);
		
		if ($validator->isValid($id)) {
				
			//delete all data from languages table for given language
			//delete data from language definitions table
			$db->delete("language_definitions", 'language_id = ' .$id);
			
			//delete data from category_master_lang table
			$db->delete("category_master_lang", 'language_id = ' .$id);	
			
			//delete data from product_master_lang table
			$db->delete("product_master_lang", 'language_id = ' .$id);		
			
			//delete data from user_master_lang table
			$db->delete("user_master_lang", 'language_id = ' .$id);
			
			//delete data from user_shipping_method_lang table
			$db->delete("user_shipping_method_lang", 'language_id = ' .$id);
			
			$db->delete("language_master", 'language_id = ' .$id);		
			
			return true;		
				
		} else {
		
			return false;
		}
				
	}
	
	/**
	 * Function deleteMultipleLanguages
	 *
	 * This function is used to delete multiple languages.
     *
	 * Date created: 2011-09-17
	 *
	 * @access public
	 * @param () (String)  - $ids : String of comma separated language ids.
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function deleteMultipleLanguages($ids)
	{
		$db = $this->db;	
		
		$where = 'language_id in ('.$ids.')'; 			
		
		$validator = new Zend_Validate_Db_NoRecordExists(
				array(
						'table' => "language_master",
						'field' => "is_default",
						'exclude' => $where
				)
		);
		
		if ($validator->isValid(1)) {
		
			$db->delete("language_master",$where);	 
			return true;
			
		} else {
		
			return false;	
		}
	}
	
	/**
	 * Function getMaxOrder
	 *
	 * This function is used to get max sort order for language.
     *
	 * Date created: 2011-09-17
	 *
	 * @access public
	 * @param ()  - No parameters.
	 * @return (int) - Return max sort order
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function getMaxOrder()
	{
		$db = $this->db;	
		
		$sql = "SELECT max(sort_order) FROM language_master";
		
		$data = $db->fetchOne($sql);
		
		if($data == '' || $data == NULL)
		{
			return 0;
		}else{
			return $data;
		}
		
	}
	
	
	/**
	 * Function getActiveLanguages
	 *
	 * This function is used to get all active languages available.
     *
	 * Date created: 2011-09-19
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (Array) - Return Array of records
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getActiveLanguages()
	{
		$db = $this->db;
		
		$sql = "select * from language_master where status=1 order by sort_order";
		
 		$result = $db->fetchAll($sql);
		return $result;
	}
	
	/**
	 * Function checkLanguageByCode
	 *
	 * This function is used to check language by code.
     *
	 * Date created: 2011-10-20
	 *
	 * @access public
	 * @param (String) - $code: code of language
	 * @return (Boolean) - Return True or False
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function checkLanguageByCode($code)
	{
		$db = $this->db;
		
		$sql = "select count(*) as cnt from language_master where code = '" . $code . "'";
		
 		$result = $db->fetchOne($sql);
		
		if($result > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	/**
	 * Function getLanguageIdByCode
	 *
	 * This function is used to get language id by code.
     *
	 * Date created: 2011-10-20
	 *
	 * @access public
	 * @param (String) - $code: code of language
	 * @return (Boolean) - Return language id
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getLanguageIdByCode($code)
	{
		$db = $this->db;
		
		$sql = "select language_id from language_master where code = '" . $code . "'";
		
 		return $db->fetchOne($sql);
		
	}
	
	/**
	 * Function getDefaultLanguageId
	 *
	 * This function is used to get language id of default language.
     *
	 * Date created: 2011-10-20
	 *
	 * @access public
	 * @param () - No parameter
	 * @return (Boolean) - Return language id
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getDefaultLanguageId()
	{
		$db = $this->db;
		
		$sql = "select language_id from language_master where is_default = 1";
		
 		return $db->fetchOne($sql);
		
	}
}
?>