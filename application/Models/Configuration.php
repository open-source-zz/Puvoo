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
 * Class Models_Configuration
 *
 * Class Models_Configuration contains methods handle configuration on site.
 *
 * Date created: 2011-10-06
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_Configuration
{
	
	private $db;
	
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-10-06
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
	 * getConfigurationGroupById(): 
	 *
	 * It is used to get the record of a particular configuration gorup by configuration group id.
	 *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param (int)  - $id: configuration group id
	 *
	 * @return (array) - Return array or record
     * 
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getConfigurationGroupById($id)
	{
		$db = $this->db;
		
		$sql = "select * from configuration_group where configuration_group_id = ".$id;
 		$result = $db->fetchRow($sql);
		return $result;
	
	}

	 
	/**
	 * Function getAllConfigurationGroup
	 *
	 * This function is used to get all languages available.
     *
	 * Date created: 2011-10-06
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (Array) - Return Array of records
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getAllConfigurationGroup()
	{
		$db = $this->db;
		
		$sql = "select * from configuration_group order by sort_order";
		
 		$result = $db->fetchAll($sql);
		return $result;
	}
	
	
	
	/**
	 * Function SearchConfigurationGroup
	 *
	 * This function is used to search group from configuration_group on search array.
     *
	 * Date created: 2011-10-06
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of search options
	 * @return (Array) - Return Array of records
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function SearchConfigurationGroup($data)
	{
		$db = $this->db;
		
		$sql = "SELECT * FROM configuration_group where 1=1";
		
		if($data["configuration_group_key"] != "")
		{
			$sql .= " and configuration_group_key like '%" . $data["configuration_group_key"] . "%'";
		}
		
		if($data["visible"] == "0" || $data["visible"] == "1")
		{
			$sql .= " and visible = '" . $data["visible"] . "'";
		}
		
		$result =  $db->fetchAll($sql);
		
		return $result;		
	}
	
	
	/**
	 * Function insertConfigurationGroup
	 *
	 * This function is used to insert Configuration Group.
     *
	 * Date created: 2011-10-06
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to insert
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function insertConfigurationGroup($data)
	{
		$db = $this->db;
		
		$id = 0;
		
		$db->insert("configuration_group", $data); 	 
		
		$id = $db->lastInsertId();
		
		return true; 
	}
	
	
	/**
	 * Function updateConfigurationGroup
	 *
	 * This function is used to Update Configuration Group record on specified condition.
     *
	 * Date created: 2011-10-06
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to update
	 * @param () (String)  - $where : Condition on which update record
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function updateConfigurationGroup($data,$where)
	{
		$db = $this->db;
		
		$db->update("configuration_group", $data, $where); 	
		return true;
	}
	
	
	/**
	 * Function deleteConfigurationGroup
	 *
	 * This function is used to delete configuration group.
     *
	 * Date created: 2011-10-06
	 *
	 * @access public
	 * @param () (String)  - $id : configuration group id
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function deleteConfigurationGroup($id)
	{
		$db = $this->db;	
		
		//delete all data from configurations table for given configuration group
		//delete data from configuration table
		$db->delete("configuration", 'configuration_group_id = ' .$id);
		
		$db->delete("configuration_group", 'configuration_group_id = ' .$id);		
		
		return true;		
	}
	
	
	/**
	 * Function deleteMultipleConfigurationGroup
	 *
	 * This function is used to delete multiple configuration group.
     *
	 * Date created: 2011-10-06
	 *
	 * @access public
	 * @param () (String)  - $ids : String of comma separated configuration group ids.
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function deleteMultipleConfigurationGroup($ids)
	{
		$db = $this->db;
		
		//Delete from from configuration table
		$where = 'configuration_group_id in ('.$ids.')'; 			
		$db->delete("configuration",$where);
		
		$where = 'configuration_group_id in ('.$ids.')'; 			
		$db->delete("configuration_group",$where);	 
		
		return true;
	}
	
	
	/**
	 * Function getMaxOrder
	 *
	 * This function is used to get max sort order for configuration group.
     *
	 * Date created: 2011-10-06
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
		
		$sql = "SELECT max(sort_order) FROM configuration_group";
		
		$data = $db->fetchOne($sql);
		
		if($data == '' || $data == NULL)
		{
			return 0;
		}else{
			return $data;
		}
		
	}
	
	
	/**
	 * Function getActiveConfigurationGroups
	 *
	 * This function is used to get all active configuration groups available.
     *
	 * Date created: 2011-10-06
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (Array) - Return Array of records
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getActiveConfigurationGroups()
	{
		$db = $this->db;
		
		$sql = "select * from configuration_group where status=1 order by sort_order";
		
 		$result = $db->fetchAll($sql);
		return $result;
	}
	
	
	/**
	 * Function SearchConfiguration
	 *
	 * This function is used to search definition from configuration on search array.
     *
	 * Date created: 2011-10-06
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of search options
	 * @return (Array) - Return Array of records
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function SearchConfiguration($data)
	{
		$db = $this->db;
		
		$sql = "SELECT * FROM configuration where 1=1";
		
		if($data["configuration_key"] != "")
		{
			$sql .= " and configuration_key like '%" . $data["configuration_key"] . "%'";
		}
		
		if($data["configuration_value"] != "")
		{
			$sql .= " and configuration_value like '%" . $data["configuration_value"] . "%'";
		}
		
		if($data["configuration_group_id"] != "")
		{
			$sql .= " and configuration_group_id = " . $data["configuration_group_id"];
		}
		
		
		$result =  $db->fetchAll($sql);
		
		return $result;		
	}
	
	/**
	 * Function getAllConfigurationForGroup
	 *
	 * This function is used to get all definition for given group.
     *
	 * Date created: 2011-10-06
	 *
	 * @access public
	 * @param (int) - $configuration_group_id: Configurtion group id
	 * @return (Array) - Return Array of records
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getAllConfigurationForGroup($configuration_group_id)
	{
		$db = $this->db;
		
		$sql = "select * from configuration where configuration_group_id = " . $configuration_group_id . " order by sort_order";
		
 		$result = $db->fetchAll($sql);
		return $result;
	}
	
	/**
	 * Function getMaxConfigurationOrder
	 *
	 * This function is used to get max sort order for configuration.
     *
	 * Date created: 2011-10-06
	 *
	 * @access public
	 * @param ()  - No parameters.
	 * @return (int) - Return max sort order
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getMaxConfigurationOrder()
	{
		$db = $this->db;	
		
		$sql = "SELECT max(sort_order) FROM configuration";
		
		$data = $db->fetchOne($sql);
		
		if($data == '' || $data == NULL)
		{
			return 0;
		}else{
			return $data;
		}
		
	}
	
	
	/**
	 * Function insertConfiguration
	 *
	 * This function is used to insert Configuration.
     *
	 * Date created: 2011-10-06
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to insert
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function insertConfiguration($data)
	{
		$db = $this->db;
		
		$id = 0;
		
		$db->insert("configuration", $data); 	 
		
		$id = $db->lastInsertId();
		
		return true; 
	}
	
	/*
	 * getConfigurationById(): 
	 *
	 * It is used to get the record of a particular configuration by configuration id.
	 *
	 * Date created: 2011-10-06
	 *
	 * @access public
	 * @param (int)  - $id: configuration id
	 *
	 * @return (array) - Return array or record
     * 
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getConfigurationById($id)
	{
		$db = $this->db;
		
		$sql = "select * from configuration where configuration_id = ".$id;
 		$result = $db->fetchRow($sql);
		return $result;
	
	}
	
	
	/**
	 * Function updateConfiguration
	 *
	 * This function is used to Update Configuration record on specified condition.
     *
	 * Date created: 2011-10-06
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to update
	 * @param () (String)  - $where : Condition on which update record
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function updateConfiguration($data,$where)
	{
		$db = $this->db;
		
		$db->update("configuration", $data, $where); 	
		return true;
	}
	
	
	/**
	 * Function deleteConfiguration
	 *
	 * This function is used to delete configuration.
     *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param () (String)  - $id : configuration id
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function deleteConfiguration($id)
	{
		$db = $this->db;	
		
		//delete data from configuration table
		$db->delete("configuration", 'configuration_id = ' .$id);
		
		return true;		
	}
	
	
	/**
	 * Function deleteMultipleConfiguration
	 *
	 * This function is used to delete multiple configuration.
     *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param () (String)  - $ids : String of comma separated configuration ids.
	 * @return (Boolean) - Return true on success
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function deleteMultipleConfiguration($ids)
	{
		$db = $this->db;
		
		//Delete from from configuration table
		$where = 'configuration_id in ('.$ids.')'; 			
		$db->delete("configuration",$where);
		
		return true;
	}
	
	
	/**
	 * Function addFacebookGroup
	 *
	 * This function is used to add facebook configuration group.
     *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (int) - last inserted id
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function addFacebookGroup()
	{
		$db = $this->db;
		
		$maxOrder = $this->getMaxOrder() + 1;
		
		$data = array();
		
		$data['configuration_group_key'] = "Facebook Store";
		$data['sort_order'] = $maxOrder;
		$data['visible'] = 1;
		
		$id = 0;
		
		$db->insert("configuration_group", $data); 	 
		
		$id = $db->lastInsertId();
		
		return $id;
	}
	
	
	/**
	 * Function addContactKey
	 *
	 * This function is used to add contact key for facebook configuration group.
     *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param (int) - $id: Configuration group id of Facebook Store
	 * @return (Boolean) - True of False; 
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function addContactKey($id)
	{
		$db = $this->db;
		
		$maxOrder = $this->getMaxConfigurationOrder() + 1;
		
		$sql = "insert ignore into configuration (configuration_key,configuration_value,configuration_group_id,sort_order,last_modified,date_added) Values ";
		$sql .="('contact_us_address','', " . $id . ", ". $maxOrder .", CURDATE(), CURDATE() ),"; 
		$sql .="('contact_us_telephone','', " . $id . ", ". ($maxOrder+1) .", CURDATE(), CURDATE() ),"; 
		$sql .="('contact_us_email','', " . $id . ", ". ($maxOrder+2) .", CURDATE(), CURDATE() )"; 
		
		
		$result = $db->query($sql); 	 
		
		
		
		return true;
	}
	
	/**
	 * Function GetConfigurationGroupId
	 *
	 * This function is used to get Configuration Group Id for given Configuration Group Key.
     *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param (String) - $key: Configuration group key
	 * @return (int) - Configuration Group Id
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetConfigurationGroupId($key)
	{
		$db = $this->db;
		
		$sql = "Select configuration_group_id from configuration_group where configuration_group_key = '" . $key . "'";
		 
		$data = $db->fetchOne($sql); 	 
		
		if($data == "" || $data == NULL)
		{
			return 0;
		}
		else
		{
			return $data;
		}
		
	}
	
	
	/**
	 * Function insertKeyForGroup
	 *
	 * This function is used to insert a key for given group.
     *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param (int) - $id: Configuration group id of Configuration Group
	 * @param (String) - $key: Configuration key to insert for Configuration Group
	 * @return (Boolean) - True of False; 
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function insertKeyForGroup($id,$key)
	{
		$db = $this->db;
		
		$maxOrder = $this->getMaxConfigurationOrder() + 1;
		
		$sql = "insert ignore into configuration (configuration_key,configuration_value,configuration_group_id,sort_order,last_modified,date_added) Values ";
		$sql .="('". $key ."','', " . $id . ", ". $maxOrder .", CURDATE(), CURDATE() )"; 
		
		$result = $db->query($sql); 	 
		
		return true;
	}
	
	
	/**
	 * Function updateKeyValueForGroup
	 *
	 * This function is used to update a key values for given group.
     *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param (int) - $id: Configuration group id of Configuration Group
	 * @param (Array) - $data: array of key and data to update
	 * @return (Boolean) - True of False; 
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function updateKeyValueForGroup($id,$data)
	{
		$db = $this->db;
		
		if(count($data) > 0)
		{
			$sql = "";
			foreach($data as $key => $val)
			{
				$sql = "update configuration set configuration_value = '" . $val . "', last_modified = CURDATE() where configuration_group_id = " . $id . " and configuration_key = '" . $key . "'";
				
				$db->query($sql);
			}
		
		}
		
		return true;
	}
	
	/**
	 * Function getKeyValueForGroup
	 *
	 * This function is used to get key values for given group.
     *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param (int) - $id: Configuration group id of Configuration Group
	 * @param (Array) - $data: array of keys to get
	 * @return (Boolean) - True of False; 
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function getKeyValueForGroup($id,$data)
	{
		$db = $this->db;
		
		$res = "";
		$str = "";
		$sql = "";
		if(count($data) > 0)
		{
			foreach($data as $key => $val)
			{
				$str .= "'".$val."',";
			}
			
			if($str != "")
			{
				$str = rtrim($str,",");
				
				$sql = "SELECT * FROM configuration	WHERE configuration_group_id = ".$id." AND configuration_key IN (". $str .")";
				
				$res = $db->fetchAll($sql);
			}		
		}
		
		return $res;
	}
	
	
}
?>