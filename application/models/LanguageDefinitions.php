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
 * Class Models_LanguageDefinitions
 *
 * Class Models_LanguageDefinitions contains methods to handle language definitions
 *
 * Date created: 2011-09-06
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_LanguageDefinitions
{
	 
	private $db;
	private $user_id;
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-09-06
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
		global $mysession;
		$this->db = Zend_Registry::get('Db_Adapter');
		$this->user_id = $mysession->User_Id;
	}
	
	
	/**
	 * Function getGroupLanguage
	 *
	 * This function will get all languages for given group and will return its array
	 *
	 * Date created: 2011-09-06
	 *
	 * @access public
	 * @param (string)  - $groups: group name
	 * @param (int)  - $lang: language id
	 * @return (array) - Return array of language key and value
	 * @author Amar 
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function getGroupLanguage($group, $lang=1)
	{
		$db = $this->db;
		$arr_lang = array();
		$sql = "Select definition_key, definition_value from language_definitions where status = 1 and content_group = '" . $group . "' and language_id = ". $lang;
		$data = $db->fetchAll($sql);
		if($data != null || $data != "")
		{
			foreach($data as $key => $val)
			{
				
				$arr_lang[$val['definition_key']] = $val['definition_value'];
			}
		}
		return $arr_lang;
		
	}
	
	
	/**
	 * Function getAllLanguages
	 *
	 * This function is used to get all languages definitions available.
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
	public function getAllDefinitions()
	{
		$db = $this->db;
		
		$sql = "select * from language_definitions 
				left join language_master on language_master.language_id = language_definitions.language_id 
				order by language_definitions.language_id, content_group asc";
		
 		$result = $db->fetchAll($sql);
		return $result;
	}
	
	
	
	/**
	 * Function SearchDefinitions
	 *
	 * This function is used to search the language from language_master on search array.
     *
	 * Date created: 2011-09-19
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of search options
	 * @return (Array) - Return Array of records
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function SearchDefinitions($data)
	{
		$db = $this->db;
		
		$sql = "select * from language_definitions 
				left join language_master on language_master.language_id = language_definitions.language_id 
				where 1 = 1";
				
				
		
		if($data["language_id"] > 0)
		{
			$sql .= " and language_definitions.language_id = " . $data["language_id"];
		}
		
		if($data["content_group"] != "")
		{
			$sql .= " and language_definitions.content_group = '" . $data["content_group"] . "'";
		}
		
		if($data["status"] == "0" || $data["status"] == "1")
		{
			$sql .= " and language_definitions.status = '" . $data["status"] . "'";
		}
		
		if($data["definition_key"] != "")
		{
			$sql .= " and language_definitions.definition_key like '%" . $data["definition_key"] . "%'";
		}
		
		if($data["definition_value"] != "")
		{
			$sql .= " and language_definitions.definition_value like '%" . $data["definition_value"] . "%'";
		}
		
		$sql .= " order by language_definitions.language_id, content_group asc";
		
		$result =  $db->fetchAll($sql);		
		
		return $result;		
	}
	
	/**
	 * Function getContentGroups
	 *
	 * This function is used to get content groups for language definitions.
     *
	 * Date created: 2011-09-19
	 *
	 * @access public
	 * @param () - No parameter
	 * @return (Array) - Return Array of records
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getContentGroups()
	{
		$db = $this->db;
		
		
		$sql = "SELECT distinct(content_group) as content_group FROM language_definitions";
		
		$result =  $db->fetchAll($sql);		
		return $result;		
	}
	
	/*
	 * getDefinitionById(): 
	 *
	 * It is used to get the record of a particular definition by definition id.
	 *
	 * Date created: 2011-09-20
	 *
	 * @access public
	 * @param (int)  - $id: definition id
	 *
	 * @return (array) - Return array or record
     * 
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getDefinitionById($id)
	{
		$db = $this->db;
		
		$sql = "select * from language_definitions where id = ".$id;
 		$result = $db->fetchRow($sql);
		return $result;
	
	}
	
	/**
	 * Function insertDefinition
	 *
	 * This function is used to insert definition.
     *
	 * Date created: 2011-09-20
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to insert
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function insertDefinition($data)
	{
		$db = $this->db;
		
		$db->insert("language_definitions", $data); 	 
		
		return true; 
	}
	
	/**
	 * Function updateDefinition
	 *
	 * This function is used to Update definition record on specified condition.
     *
	 * Date created: 2011-09-20
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to update
	 * @param () (String)  - $where : Condition on which update record
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function updateDefinition($data,$where)
	{
		$db = $this->db;
		
		$db->update("language_definitions", $data, $where); 	
		return true;
	}
	
	/**
	 * Function deleteDefinition
	 *
	 * This function is used to delete definition.
     *
	 * Date created: 2011-09-20
	 *
	 * @access public
	 * @param () (String)  - $id : definition id
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function deleteDefinition($id)
	{
		$db = $this->db;	
		
		$db->delete("language_definitions", 'id = ' .$id);		
		
		return true;		
	}
	
	
	/**
	 * Function deleteMultipleDefinitions
	 *
	 * This function is used to delete multiple definitions.
     *
	 * Date created: 2011-09-20
	 *
	 * @access public
	 * @param () (String)  - $ids : String of comma separated definition ids.
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function deleteMultipleDefinitions($ids)
	{
		$db = $this->db;	
		$where = 'id in ('.$ids.')'; 			
		$db->delete("language_definitions",$where);	 
		
		return true;
	}
	
	
	/**
	 * Function executeQuery
	 *
	 * This function is used to execute query.
     *
	 * Date created: 2011-09-30
	 *
	 * @access public
	 * @param () (String)  - $sql : sql query to execute.
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function executeQuery($sql)
	{
		$db = $this->db;	
		
		$db->query($sql);
		
		return true;
	}
	
	
	/**
	 * Function getDefinitionsByLanguageId
	 *
	 * This function is used to get definitions from language id.
     *
	 * Date created: 2011-10-01
	 *
	 * @access public
	 * @param (int)  - $language_id: Language Id
	 * @return (Array) - Return Array of records
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getDefinitionsByLanguageId($language_id)
	{
		$db = $this->db;
		
		$sql = "select * from language_definitions 
				where language_id = " . $language_id .  "
				order by language_definitions.language_id, content_group asc";
		
 		$result = $db->fetchAll($sql);
		return $result;
	}
	
}
?>