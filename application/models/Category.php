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
 * Class Models_Category
 *
 * Class Models_Category contains methods handle categories on site.
 *
 * Date created: 2011-08-22
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Jayesh 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_Category
{
	
	private $db;
	
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-08-24
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function __construct()
	{
		$this->db = Zend_Registry::get('Db_Adapter');
	}
 	
	 /*
	 * getMainCategory(): To get the main category of the store.
	 *
	 * It is used to get the main category from the database and display in left menu all ca.
	 *
	 * Date created: 2011-08-22
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
 	 
	public function GetMainCategory()
	{
		$db = $this->db;
		
		$sql = "select * from category_master where parent_id = '0' and is_active='1' order By category_name asc";
 		$result = $db->fetchAll($sql);
		return $result;
 	} 
	
	 /*
	 * GetCategory(): To get category that have be shown in left menu.
	 *
	 * It is used to get the category from the database and display in left menu.
	 *
	 * Date created: 2011-08-22
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	
	public function GetCategory()
	{
		$db = $this->db;
		
		$sql = "select * from category_master where parent_id = '0' and is_active='1' order By category_name asc";
 		$result = $db->fetchAll($sql);
		return $result;
	
	}
	
	/*
	 * GetCategoryById(): To get data of category by selected category id.
	 *
	 * It is used to get the all records of particular category by category id.
	 *
	 * Date created: 2011-08-26
	 *
	 * @author  Yogesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	
	public function GetCategoryById($id)
	{
		$db = $this->db;
		
		$sql = "select * from category_master where category_id = ".$id." order By category_name asc";
 		$result = $db->fetchRow($sql);
		return $result;
	
	}

	 /*
	 * GetSubCategory(): To get the sub category.
	 *
	 * It is used to get the main category from the database and display in left menu.
	 *
	 * Date created: 2011-08-22
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */


	public function GetSubCategory($parentid)
	{
		$db = $this->db;
		
		$sql = "select * from category_master where parent_id = '".$parentid."' order By category_name asc";
		//print $sql;die;
 		$result = $db->fetchAll($sql);
		return $result;
	
	}
	
	
	/**
	 * Function GetAllCategories
	 *
	 * This function is used to get all categories available.
     *
	 * Date created: 2011-08-24
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (Array) - Return Array of records
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetAllCategories()
	{
		$db = $this->db;
		
		$sql = "select * from category_master";
		
 		$result = $db->fetchAll($sql);
		return $result;
	}
	
	/**
	 * Function GetCategoryDetail
	 *
	 * This function is used to get all details of that particular categories.
     *
	 * Date created: 2011-08-25
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (Array) - Return Array of records
	 * @author Jayesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetCategoryDetail($catid)
	{
		$db = $this->db;
		
		$sql = "select * from category_master where category_id = ".$catid."";
		
		$result =  $db->fetchRow($sql);
		
		return $result;
	
	}
	
	/**
	 * Function SearchCategories
	 *
	 * This function is used to search the category from category_master on search array.
     *
	 * Date created: 2011-08-26
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of search options
	 * @return (Array) - Return Array of records
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function SearchCategories($data)
	{
		$db = $this->db;
		
		$sql  = "";
		$sql .= "SELECT * FROM category_master";
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
	 * Function insertCategory
	 *
	 * This function is used to insert category.
     *
	 * Date created: 2011-08-26
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to insert
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function insertCategory($data)
	{
		$db = $this->db;
		
		$db->insert("category_master", $data); 	 
		//return $db->lastInsertId(); 
		return true; 
	}
	
	/**
	 * Function updateCategory
	 *
	 * This function is used to Update category records on specified condition.
     *
	 * Date created: 2011-08-26
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to update
	 * @param () (String)  - $where : Condition on which update record
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function updateCategory($data,$where)
	{
		$db = $this->db;		
		$db->update("category_master", $data, $where); 	
		return true;
	}
	
	/**
	 * Function deleteCategory
	 *
	 * This function is used to delete category on specified condition.
     *
	 * Date created: 2011-08-26
	 *
	 * @access public
	 * @param () (String)  - $id : Category Id
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function deleteCategory($id)
	{
		$db = $this->db;	
		$db->delete("category_master", 'category_id = ' .$id);		
		return true;		
	}
	
	/**
	 * Function deletemultipleCategories
	 *
	 * This function is used to delete multiple category on specified condition.
     *
	 * Date created: 2011-08-26
	 *
	 * @access public
	 * @param () (String)  - $ids : Sting of all Category Id with comma seprated.
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function deletemultipleCategories($ids)
	{
		$db = $this->db;	
		$where = 'category_id in ('.$ids.')'; 			
		$db->delete("category_master",$where);	 
		
		return true;	
	}
	
}
?>
