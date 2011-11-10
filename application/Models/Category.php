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
	 * This is a constructor function.
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
 	
	/**
	 * function getMainCategory()
	 *
	 * It is used to get the main category from the database and display in left menu.
	 *
	 * Date created: 2011-08-22
	 *
	 * @param () - No parameter
	 * @return (Array) - Return Array of records
	 * @author Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
 	public function GetMainCategory()
	{
		$db = $this->db;
		
		$sql = "select cm.*,cml.category_name as catName from category_master as cm
				LEFT JOIN category_master_lang as cml ON(cm.category_id = cml.category_id and cml.language_id= ".DEFAULT_LANGUAGE.")
		 		where cm.parent_id = '0' and cm.is_active='1' order By cm.category_name asc";
		
		//echo $sql;die;
 		$result = $db->fetchAll($sql);
		return $result;
 	} 
	
	/**
	 * function GetTopMainCategory()
	 *
	 * It is used to get the top main category from the database and display in left menu.
	 *
	 * Date created: 2011-11-06
	 *
	 * @param () - No parameter
	 * @return (Array) - Return Array of records
	 * @author Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
 	public function GetTopMainCategory()
	{
		$db = $this->db;
		
		$sql = "select cm.*,cml.category_name as catName from category_master as cm
				LEFT JOIN category_master_lang as cml ON(cm.category_id = cml.category_id and cml.language_id= ".DEFAULT_LANGUAGE.")
		 		where cm.parent_id = '0' and cm.is_active='1' order By cm.category_name asc limit 0,10";
				//echo $sql;die;
 		$result = $db->fetchAll($sql);
		return $result;
 	} 
	
	
	/**
	 * function GetCategory
	 *
	 * It is used to get the category from the database and display in left menu.
	 *
	 * Date created: 2011-08-22
	 *
	 * @param () - No parameter
	 * @return (Array) - Return Array of records
	 * @author Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetCategory()
	{
		$db = $this->db;
		
		$sql = "select cm.*,cml.category_name as catName from category_master as cm
				LEFT JOIN category_master_lang as cml ON(cm.category_id = cml.category_id and cml.language_id= ".DEFAULT_LANGUAGE.")
		 		where cm.parent_id = '0' and cm.is_active='1' order By cm.category_name asc";
 		$result = $db->fetchAll($sql);
		return $result;
	
	}

	/**
	 * function GetCategoryID
	 *
	 * It is used to get category id throught product id.
	 *
	 * Date created: 2011-08-22
	 *
	 * @param () (Int)  - $Poductid : Product Id
	 * @return (Array) - Return Array of records
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	
	public function GetCategoryID($Poductid)
	{
		$db = $this->db;
		
		$sql = "SELECT cm.*,cml.category_name as CatName from product_to_categories as ptc 
				LEFT JOIN category_master as cm ON(cm.category_id = ptc.category_id)
				LEFT JOIN category_master_lang as cml ON(cm.category_id = cml.category_id and cml.language_id= ".DEFAULT_LANGUAGE.")
				WHERE ptc.product_id='".$Poductid."' or cm.parent_id !='0'";
				
 		$result = $db->fetchRow($sql);
		return $result;
	
	}

	
	/*
	 * GetCategoryById(): To get data of category by selected category id.
	 *
	 * It is used to get the all records of particular category by category id.
	 *
	 * Date created: 2011-08-26
	 *
	 * @param () (Int)  - $id : Category Id
	 * @return (Array) - Return Array of records
	 * @author  Yogesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	
	public function GetCategoryById($id)
	{
		$db = $this->db;
		
		$sql1 = "SELECT * 
				 FROM category_master 
				 WHERE category_id = ".$id;
		
		$sql2 = "SELECT *
				 FROM  category_master_lang 
				 WHERE category_id = ".$id." ORDER BY language_id ASC";
		
 		$result["category"] = $db->fetchRow($sql1);
		$result["category_lang"] = $db->fetchAll($sql2);
		
		return $result;
	
	}

	 /*
	 * GetSubCategory(): To get the sub category.
	 *
	 * It is used to get the main category from the database and display in left menu.
	 *
	 * Date created: 2011-08-22
	 *
	 * @param (Int)- $parentid  - Parent id
	 * @return (Array) - Return Array of records
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */


	public function GetSubCategory($ids=0)
	{
		$db = $this->db;
		
		$sql = "select distinct ptc.category_id as CatId,cm.*,cml.category_name as catName from category_master as cm
				LEFT JOIN product_to_categories as ptc ON (ptc.category_id = cm.category_id)
				LEFT JOIN category_master_lang as cml ON(cm.category_id = cml.category_id  AND cml.language_id= ".DEFAULT_LANGUAGE." )
				where  cm.parent_id IN(".$ids.") order By cm.category_name asc ";
				
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
	 *  
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
	 * @param () (Int)  - $catid : Category Id
	 * @return (Array) - Return Array of records
	 * @author Jayesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetCategoryDetail($catid)
	{
		$db = $this->db;
		
		$sql = "select cm.*,cml.category_name as CatName from category_master as cm
				LEFT JOIN category_master_lang as cml ON(cm.category_id = cml.category_id and cml.language_id= ".DEFAULT_LANGUAGE.")
				where cm.category_id = ".$catid."";
		
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
	 *  
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
						$sql.= ' WHERE lower('.$key.') like "%'.$val.'%"';
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
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function insertCategory($data,$langArray)
	{
		$db = $this->db;
		
		$db->insert("category_master", $data);
		 	 
		$category_id = $db->lastInsertId(); 
		
		$sql = "INSERT into category_master_lang (category_id, language_id, category_name) values ";
		
		foreach( $langArray as $key => $val )
		{
			$sql .= '(' . $category_id . ','. $key. ', "'.$val["category_name"].'" ),';
		}
		
		$sql = rtrim($sql,",");
		
		$db->query($sql);
		
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
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function updateCategory($data,$where,$lang_array)
	{
		$db = $this->db;		
		$db->update("category_master", $data, $where); 	
		
		$validator = new Zend_Validate_Db_NoRecordExists(
				array(
						'table' => "category_master_lang",
						'field' => "language_id",
						'exclude' => $where
				)
		);
		
		foreach( $lang_array as $key => $val )
		{
			if ($validator->isValid($key)) {
				
				$data1["category_id"] = $data["category_id"];
				$data1["language_id"] = $key;
				$data1["category_name"] = $val["category_name"];
				
				$db->insert("category_master_lang", $data1);	
				
			} else {
				
				$data2["category_id"] = $data["category_id"];
				$data2["language_id"] = $key;
				$data2["category_name"] = $val["category_name"];
				
				$where2 = "category_id = ".$data["category_id"]." and language_id = ".$key;
				
				$db->update("category_master_lang", $data2, $where2); 	
				
			}	
		}
		
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
	 * @param () (Int)  - $id : Category Id
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function deleteCategory($id)
	{
		$db = $this->db;	
		$db->delete("category_master", 'category_id = ' .$id);		
		$db->delete("category_master_lang", 'category_id = ' .$id);		
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
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function deletemultipleCategories($ids)
	{
		$db = $this->db;	
		
		$where = 'category_id in ('.$ids.')'; 			
		$db->delete("category_master",$where);	 
		$db->delete("category_master_lang",$where);	
		
		return true;	
	}
	
	/**
	 * Function getParentCategories
	 *
	 * This function is used to get parent categories of given category
     *
	 * Date created: 2011-09-28
	 *
	 * @access public
	 * @param () (int)  - $cid : category id to get parents.
	 * @param () (array)  - $carray : array to put results in.
	 * @return (array) - Return array of category ids
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function getParentCategories($cid,$carray = array())
	{
		$db = $this->db;	
		$data = NULL;
		if($cid > 0)
		{
			$sql = "select category_id, parent_id, category_name from category_master where category_id = " . $cid;
			$data = $db->fetchRow($sql);
			$x = count($carray);
			$carray[$x]["category_id"] = $data["category_id"];
			$carray[$x]["category_name"] = $data["category_name"];
			
			return($this->getParentCategories($data["parent_id"],$carray));
		}
		else
		{
			return $carray;
		}
		
	}
	
	/**
	 * Function getAllCateTree
	 *
	 * This function is used to get category tree 
     *
	 * Date created: 2011-09-30
	 *
	 * @access public
	 * @param () (int)  - $cid : category id 
	 * @param () (array)  - $carray : array to put results in.
	 * @return (array) - Return array of category ids
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	function getCateTree( $cid = 0 )
	{	
		global $exclude;
		$db = $this->db;	
		$tree = "";                         // Clear the directory tree
		$depth = 1;                         // Child level depth.
		$top_level_on = 1;               // What top-level category are we on?
		$exclude = array();               // Define the exclusion array
		array_push($exclude, 0);     // Put a starting value in it
		
		
		$sql = "SELECT cm.*,cml.category_name as CatName FROM category_master as cm
				LEFT JOIN category_master_lang as cml ON(cm.category_id = cml.category_id and cml.language_id=".DEFAULT_LANGUAGE.")
				WHERE cm.is_active = 1";
		
		$data = $db->fetchAll($sql);
 	
		foreach ( $data as $key => $nav_row  )
		{
     		$goOn = 1;               // Resets variable to allow us to continue building out the tree.
			for($x = 0; $x < count($exclude); $x++ )          // Check to see if the new item has been used
			{
				  if ( $exclude[$x] == $nav_row['category_id'] )
				  {
					   $goOn = 0;
					   break;         // Stop looking b/c we already found that it's in the exclusion list and we can't continue to process this node
				  }
			 }
			 if ( $goOn == 1 )
			 {
				  if($cid == $nav_row['category_id'] ) {
				    $tree .= "<option selected='selected' value='".$nav_row['category_id']."' >";
				  } else {
					$tree .= "<option value='".$nav_row['category_id']."' >";				
				  }
				  
				  if($nav_row['CatName'] != '')
				  {
				  	$tree .= $nav_row['CatName'];
				  }else{
				  
				  	$tree .= $nav_row['category_name'];
				  }
				                      // Process the main tree node
				  $tree .= "</option>";
				  array_push($exclude, $nav_row['category_id']);          // Add to the exclusion list
				  if ( $nav_row['category_id'] < 6 )
				  { $top_level_on = $nav_row['category_id']; }
		 
				  $tree .= $this->build_child($nav_row['category_id'],$cid);          // Start the recursive function of building the child tree
			 }
		}
		
		return $tree; 
 	}
	
	function build_child($oldID,$cid)               // Recursive function to get all of the children...unlimited depth
	{	
		 global $exclude, $depth;               // Refer to the global array defined at the top of this script
		 $db = $this->db;	
		 $tempTree = "";
		 $child_query = "SELECT cm.*,cml.category_name as CatName FROM category_master as cm
						 LEFT JOIN category_master_lang as cml ON(cm.category_id = cml.category_id and cml.language_id=".DEFAULT_LANGUAGE.")
						 WHERE is_active = 1 AND parent_id=" . $oldID;
		 
		 $row = $db->fetchAll($child_query);
		 
		foreach ( $row as $key => $child )
		{
			if ( $child['category_id'] != $child['parent_id'] )
			{		
			
				if($cid == $child['category_id'] ) {
					$tempTree .= "<option selected='selected' value='".$child['category_id']."' >";
				} else {
					$tempTree .= "<option value='".$child['category_id']."' >";				
				}
				for ( $c=0; $c < $depth; $c++ )               // Indent over so that there is distinction between levels
				{ 
					$tempTree .= "&nbsp;&nbsp;-"; 
				}
				
				  if($child['CatName'] != '')
				  {
					$tempTree .=  "&nbsp;&nbsp;-&nbsp;&nbsp;".$child['CatName'] . "<br>";
				  }else{
				  
					$tempTree .=  "&nbsp;&nbsp;-&nbsp;&nbsp;".$child['category_name'] . "<br>";
				  }
				
				$tempTree .= "</option>";
				$depth++;          // Incriment depth b/c we're building this child's child tree  (complicated yet???)
				$tempTree .= $this->build_child($child['category_id'],$cid);          // Add to the temporary local tree
				$depth--;          // Decrement depth b/c we're done building the child's child tree.
				array_push($exclude, $child['category_id']);               // Add the item to the exclusion list
			}
		}
	 
		 return $tempTree;          // Return the entire child tree
	}
	
	/**
	 * function CategoryExist
	 *
	 * It is used to check that category is exist or not.
	 *
	 * Date created: 2011-09-10
	 *
	 * @param (int) $catId- Category Id.
	 * @return (Array) - Return true on success
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 */
	public function CategoryExist($catId)
	{
		$db= $this->db;
		
		$sql = "SELECT * FROM category_master WHERE category_id = ".$catId."";
		
		$result = $db->fetchRow($sql);
		if($result == NULL || $result == "")
		{
			return false;
		
		}else{ 
			return true;
		}
	
	}
	
	
	/**
	 * Function getCategoryTreeView
	 *
	 * This function is used to get category tree view
     *
	 * Date created: 2011-10-14
	 *
	 * @access public
	 * @param () (int)  - $cid : category id 
	 * @param () (array)  - $carray : array to put results in.
	 * @return (array) - Return array of category ids
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	//recursive function that prints categories as a nested html unorderd list

	function getCategoryTreeView($parent = 0, $lvl=0)
	{
		$db= $this->db;
		
		$tree = "";
	
		$sql = "SELECT * FROM category_master WHERE is_active = 1 and parent_id = ". $parent;
		$data = $db->fetchAll($sql);
		
		if ($lvl === 0)
		{
			$tree .= "<ul id='navigation' class='treeview'>";
		}
		else
		{
			$tree .= "<ul>";
		}
		
		$lvl = $lvl + 1;
		
		foreach($data as $key => $value)
		{
	
			$sql_child = "select count(*) as cnt from category_master where is_active = 1 and parent_id = " . $value["category_id"];
			$cnt = $db->fetchOne($sql_child);
			
			if($cnt > 0)
			{
					$tree .= "<li>"  . $value['category_name'] . " [Id = ". $value['category_id'] ."]" .  $this->getCategoryTreeView($value['category_id'],$lvl) . "</li>";
			}
			else
			{
				$tree .= '<li>' . $value['category_name'] . " [Id = ". $value['category_id'] ."]" . "</li>";
			}
			
		}
		return $tree . "</ul>";
	}


	/**
	 * Function getCategoryTreeForAPI
	 *
	 * This function is used to get category tree for API call
     *
	 * Date created: 2011-10-15
	 *
	 * @access public
	 * @param (int)  - $cid : category id 
	 * @param (int)  - $lvl: current level of category.
	 * @return (array) - Return array of category ids
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	//recursive function that prints categories as a nested html unorderd list

	function getCategoryTreeForAPI($langCode, $parent = 0, $lvl=0)
	{
		$db= $this->db;
		
		
	
		$sql = "SELECT cm.category_id,cml.category_name FROM category_master as cm
				LEFT JOIN category_master_lang as cml ON(cm.category_id = cml.category_id and cml.language_id = 
				(
					SELECT language_id FROM language_master WHERE code = '".$langCode."'
				))
				WHERE is_active = 1 and parent_id = ". $parent;
		
		$data = $db->fetchAll($sql);
		
		foreach($data as $key => $value)
		{
	
			$sql_child = "select count(*) as cnt from category_master where is_active = 1 and parent_id = " . $value["category_id"];
			$cnt = $db->fetchOne($sql_child);
			
			if($cnt > 0)
			{
					$tree[$value['category_id']]["Name"] = $value['category_name'];
					$tree[$value['category_id']]["Id"] = $value['category_id'];
					
					//array_push($tree, $this->getCategoryTreeForAPI($value['category_id'],$lvl,$tree)	); 
					$tree[$value['category_id']]["Category"] = $this->getCategoryTreeForAPI($langCode, $value['category_id'],$lvl+1);
			}
			else
			{
				$tree[$value['category_id']]["Name"] = $value['category_name'];
				$tree[$value['category_id']]["Id"] = $value['category_id'];
			}
			
		}
		return $tree;
	}
	
	/**
	 * Function getCategoryTreeString
	 *
	 * This function is used to get category tree for API call
     *
	 * Date created: 2011-10-17
	 *
	 * @access public
	 * @param (int)  - $cid : category id 
	 * @param (int)  - $lvl: current level of category.
	 * @return (array) - Return array of category ids
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	//recursive function that prints categories as a nested html unorderd list

	function getCategoryTreeString($parent = 0, $lvl=0)
	{
		$db= $this->db;
		
		
	
		$sql = "SELECT * FROM category_master WHERE is_active = 1 and parent_id = ". $parent;
		//print $sql . "<br>";die;
		$data = $db->fetchAll($sql);
		
		$tree = "";
		
		foreach($data as $key => $value)
		{
	
			$sql_child = "select count(*) as cnt from category_master where is_active = 1 and parent_id = " . $value["category_id"];
			$cnt = $db->fetchOne($sql_child);
			
			if($cnt > 0)
			{
					
					$tree .= $value['category_id'] . ",";
					
					//array_push($tree, $this->getCategoryTreeForAPI($value['category_id'],$lvl,$tree)	); 
					$tree .= $this->getCategoryTreeString($value['category_id'],$lvl+1);
			}
			else
			{
				$tree .= $value['category_id'] . ",";
			}
			
		}
		return $tree;
	}
	
	
	/**
	 * Function getCategoryTreeProductCount
	 *
	 * This function is used to get product count for given category 
     *
	 * Date created: 2011-10-18
	 *
	 * @access public
	 * @param (int)  - $cid : category id 
	 * @param (int)  - $lvl: current level of category.
	 * @return (array) - Return array of category ids
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	//recursive function that prints categories as a nested html unorderd list

	function getCategoryTreeProductCount($parent = 0, $lvl=0)
	{
		$db= $this->db;
		
		
	
		$sql = "SELECT * FROM category_master WHERE is_active = 1 and parent_id = ". $parent;
		//print $sql . "<br>";die;
		$data = $db->fetchAll($sql);
		
		$tree = 0;
		
		$sql = "SELECT count(*) as cnt FROM product_to_categories WHERE category_id = ". $parent;
		
		$tree = (int)$db->fetchOne($sql);
		
		if($tree > 0)
		{
			return $tree;
		}
		
		foreach($data as $key => $value)
		{
	
			$sql_child = "select count(*) as cnt from category_master where is_active = 1 and parent_id = " . $value["category_id"];
			$cnt = $db->fetchOne($sql_child);
			
 			
			if($cnt > 0)
			{
					
					
					//array_push($tree, $this->getCategoryTreeForAPI($value['category_id'],$lvl,$tree)	); 
					$tree += (int)$this->getCategoryTreeProductCount($value['category_id'],$lvl+1);
			}
		}
		return $tree;
	}
	
	
	/**
	 * Function getCateTree2
	 *
	 * This function is used to get category tree 
     *
	 * Date created: 2011-09-30
	 *
	 * @access public
	 * @param () (int)  - $cid : category id 
	 * @param () (array)  - $carray : array to put results in.
	 * @return (array) - Return array of category ids
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	function getCateTree2( $cid = 0 )
	{	
		global $exclude;
		$db = $this->db;	
		$tree = "";                         // Clear the directory tree
		$depth = 1;                         // Child level depth.
		$top_level_on = 1;               // What top-level category are we on?
		$exclude = array();               // Define the exclusion array
		array_push($exclude, 0);     // Put a starting value in it
		
		
		$sql = "SELECT * FROM category_master WHERE is_active = 1";
		$data = $db->fetchAll($sql);
 	
		foreach ( $data as $key => $nav_row  )
		{
     		$goOn = 1;               // Resets variable to allow us to continue building out the tree.
			for($x = 0; $x < count($exclude); $x++ )          // Check to see if the new item has been used
			{
				  if ( $exclude[$x] == $nav_row['category_id'] )
				  {
					   $goOn = 0;
					   break;         // Stop looking b/c we already found that it's in the exclusion list and we can't continue to process this node
				  }
			 }
			 if ( $goOn == 1 )
			 {
			 
			 	 $prodCount = 0;
				 $prodCount = $this->getCategoryTreeProductCount($nav_row['category_id']);
			 	if($prodCount > 0) {
					  if($cid == $nav_row['category_id'] ) {
						
							$tree .= "<option selected='selected' value='".$nav_row['category_id']."' >";
						
					  } else {
						
						$tree .= "<option value='".$nav_row['category_id']."' >";				
					  }
				 }
				  $tree .= $nav_row['category_name'];                    // Process the main tree node
				  $tree .= "</option>";
				  array_push($exclude, $nav_row['category_id']);          // Add to the exclusion list
				  if ( $nav_row['category_id'] < 6 )
				  { $top_level_on = $nav_row['category_id']; }
		 
				  $tree .= $this->build_child2($nav_row['category_id'],$cid);          // Start the recursive function of building the child tree
			 }
		}
		
		return $tree; 
 	}
	
	function build_child2($oldID,$cid)               // Recursive function to get all of the children...unlimited depth
	{	
		 global $exclude, $depth;               // Refer to the global array defined at the top of this script
		 $db = $this->db;	
		 $tempTree = "";
		 $child_query = "SELECT * FROM category_master WHERE is_active = 1 AND parent_id=" . $oldID;
		 
		 $row = $db->fetchAll($child_query);
		 
		foreach ( $row as $key => $child )
		{
			if ( $child['category_id'] != $child['parent_id'] )
			{		
			
				 $prodCount = 0;
				 $prodCount = $this->getCategoryTreeProductCount($child['category_id']);
				if($prodCount > 0) {
					if($cid == $child['category_id'] ) {
						$tempTree .= "<option selected='selected' value='".$child['category_id']."' >";
					} else {
						$tempTree .= "<option value='".$child['category_id']."' >";				
					}
				}
				for ( $c=0; $c < $depth; $c++ )               // Indent over so that there is distinction between levels
				{ 
					$tempTree .= "&nbsp;&nbsp;-"; 
				}
			   
				$tempTree .=  "&nbsp;&nbsp;-&nbsp;&nbsp;".$child['category_name'] . "<br>";
				$tempTree .= "</option>";
				$depth++;          // Incriment depth b/c we're building this child's child tree  (complicated yet???)
				$tempTree .= $this->build_child2($child['category_id'],$cid);          // Add to the temporary local tree
				$depth--;          // Decrement depth b/c we're done building the child's child tree.
				array_push($exclude, $child['category_id']);               // Add the item to the exclusion list
			}
		}
	 
		 return $tempTree;          // Return the entire child tree
	}
	
	
	
	function getCategoryTreeForMenu($langCode, $parent = 0)
	{
		$db= $this->db;
		
		$sql = "SELECT cm.category_id,cm.category_name,cml.category_name as catName,cm.icon_image FROM category_master as cm
				LEFT JOIN category_master_lang as cml ON(cm.category_id = cml.category_id and cml.language_id = 
				(
					SELECT language_id FROM language_master WHERE code = '".$langCode."'
				))
				WHERE is_active = 1 and parent_id = ". $parent;
		
		$data = $db->fetchAll($sql);
		
		$tree = '';
		foreach($data as $key => $value)
		{
			
			if($value['catName'] != '')
			{
				$subCatName = $value['catName'];
			}else{
				$subCatName = $value['category_name'];
			}
			
			if($parent == 0) {
				$tree .= '<ul id="Category_'.$value['category_id'].'" class="ddsubmenustyle blackwhite">';
			} 
			
			if($parent != 0) {
				
				if($value['icon_image'] != '')
				{
					$Icon = "<img src='".SITE_ICONS_IMAGES_PATH."/".$value['icon_image']."' style='vertical-align:middle;' alt='' />&nbsp;&nbsp;";
				}else{
					$Icon = "<img src='".IMAGES_FB_PATH."/cat_default.png' style='vertical-align:middle;' alt='' />&nbsp;&nbsp;";
				}
			
				$tree .= '<li><a href="'.SITE_FB_URL.'category/subcat/id/'.$value['category_id'].'" target="_top">'.$Icon.$subCatName.'</a>';
			}
			
			$sql_child = "select count(*) as cnt from category_master where is_active = 1 and parent_id = " . $value["category_id"];
			$cnt = $db->fetchOne($sql_child);
			
			if( $cnt > 0 ) {
				
				if($parent != 0) {
					
					$tree .= '<ul>';
				} 
				
				$tree .= $this->getCategoryTreeForMenu($langCode,$value["category_id"]);
				
				if($parent != 0) {
					$tree .= '</ul>';					
				} 
			}
			
			if($parent != 0) {
				$tree .= '</li>';
			} 
			
			if($parent == 0) {
				$tree .= '</ul>';
			}
		}
		
		return $tree;
	}
	
	/**
	 * Function getAllFriendLikes
	 *
	 * This function is used to friends like product. 
     *
	 * Date created: 2011-11-07
	 *
	 * @access public
	 * 
	 * @param () (array)  - $friends_list : array to put results in.
	 * @return (array) - Return array of category ids
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function getAllFriendLikes($friends_list)

	{

 
		global $mysession;

		

		$db = $this->db;

		

		$select = "SELECT upl.*,pm.product_id, pm.product_name, pm.product_price, cm.currency_symbol, cm.currency_code, round( ( pm.product_price * ".$mysession->currency_value.") / cm.currency_value, 2 ) as converted_price, pi.image_name, pi.image_path,pml.product_name as ProdName

				   FROM user_product_likes as upl

				   JOIN product_master as pm ON(pm.product_id = upl.product_id)

				   LEFT JOIN product_master_lang as pml ON (pm.product_id = pml.product_id and pml.language_id= ".DEFAULT_LANGUAGE.")

				   LEFT JOIN user_master as um ON ( um.user_id = pm.user_id )

				   LEFT JOIN currency_master as cm ON (um.currency_id = cm.currency_id)

				   LEFT JOIN product_images as pi ON ( pm.product_id = pi.product_id AND pi.is_primary_image = 1)

				   WHERE upl.facebook_user_id in ( ".$friends_list." ) limit 0,3 ";

		

		return $db->fetchAll($select);

	}


	
}
?>