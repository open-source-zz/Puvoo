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
 * Class Models_UserProduct
 *
 * Class Models_UserProduct contains methods handle products on site.
 *
 * Date created: 2011-09-20
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Yogesh
 *  
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_UserProduct
{
	private $db;
	private $user_id;
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-09-20
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Yogesh
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
	 * Function SearchProducts
	 *
	 * This function is used to fetch all records from diffrent table.
     *
	 * Date created: 2011-09-13
	 *  
	 * @access public
	 * @param (String) -  $table: Table name.
	 * @param (String) -  $where: Condition on which records are fetch.
	 * @return (Array) -  Return All Products
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function fetchAllRecords($table,$where = '1=1')
	{
		$db = $this->db;
		$select = $db->select()
				 	 ->from($table)
					 ->where($where);
		return $db->fetchAll($select);
	}
	
	/**
	 * Function GetAllDistinctProducts
	 *
	 * This function is used to fetch all Distinct Products with user name and category
     *
	 * Date created: 2011-09-13
	 *  
	 * @access public
	 * @param () -  No Parameters
	 * @return (Arrat) - Return All Products
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function GetAllDistinctProducts()
	{
		$db = $this->db;
		
		$select = "SELECT pm.product_id,pm.product_name, pm.product_description, pm.product_price, pm.product_weight, ptc.category_id,
					(SELECT CONCAT(um.user_fname,' ',um.user_lname) 
					 FROM user_master as um
					 WHERE um.user_id = pm.user_id) as user_name,
					(SELECT cm.category_name 
					 FROM category_master as cm
					 WHERE cm.category_id = ptc.category_id) as category_name
				   FROM product_master as pm 
				   LEFT JOIN product_to_categories as ptc
				   ON (pm.product_id = ptc.product_id) 
				   WHERE pm.user_id = ".$this->user_id." 
				   GROUP BY pm.product_id";
		
		return $db->fetchAll($select);
	}
	
	/**
	 * Function GetAllProdCate
	 *
	 * This function is used to fetch all Products id and category name
     *
	 * Date created: 2011-09-13
	 *  
	 * @access public
	 * @param () -  No Parameters
	 * @return (Arrat) - Return All Products
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function GetAllProdCate()
	{
		$db = $this->db;
		
		$select = "SELECT pm.product_id,
					(SELECT cm.category_name 
					 FROM category_master as cm
					 WHERE cm.category_id = ptc.category_id) as category_name
				   FROM product_master as pm 

				   LEFT JOIN product_to_categories as ptc
				   ON (pm.product_id = ptc.product_id)
				   WHERE user_id = ".$this->user_id;
		
		return $db->fetchAll($select);
	}
	
	/**
	 * Function SearchProducts
	 *
	 * This function is used to search Products on search condition.
     *
	 * Date created: 2011-09-13
	 *  
	 * @access public
	 * @param (Array) -  $data: Array of search value.
	 * @param (Array) -  $range: Range of product price.
	 * @return (Array) - Return All Products
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function SearchProducts($data,$range = NULL)
	{
		$db = $this->db;
		$sql = "";
		if(count($data) > 0 && $data != "") {
			foreach($data as $key => $val) {			
				if( $key == "category_id" && $val != ""  ) {
					
					$sql.=" AND ptc.".$key." = ".$val;		
									
				} else {
					
					if($val != "") {
						$sql.=" AND lower(".$key.") like '%".$val."%'";					
					}
				
				}	
				 						
			}			
		}
		
		
		if($range!=NULL)
		{
			$sql.=" AND pm.product_price >= '".$range[0]."' AND pm.product_price <= '".$range[1]."'";			
		}
		
		$select = "SELECT pm.product_id,pm.product_name, pm.product_description, pm.product_price, pm.product_weight, ptc.category_id, pm.user_id,
					(SELECT CONCAT(um.user_fname,' ',um.user_lname) 
					 FROM user_master as um
					 WHERE um.user_id = pm.user_id) as user_name,
					(SELECT cm.category_name 
					 FROM category_master as cm
					 WHERE cm.category_id = ptc.category_id) as category_name
				   FROM product_master as pm 
				   LEFT JOIN product_to_categories as ptc
				   ON (pm.product_id = ptc.product_id)
				   WHERE pm.user_id = ".$this->user_id." ".$sql." 
				   GROUP BY pm.product_id";
		
		return $db->fetchAll($select);	
	}
	
	/**
	 * Function getAllProductDetail
	 *
	 * This function is used to fetch all product details , images , categories and product properties.
     *
	 * Date created: 2011-09-15
	 *  
	 * @access public
	 * @param (int) - $id : product id
	 *
	 * @return (Array) - Return array of all product details.
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function getAllProductDetail($id)
	{
		$db = $this->db;
		
		$product = array();
		
		$sql = " SELECT pm.*,CONCAT(um.user_fname,' ',um.user_lname) as user_name,wm.weight_unit_key,lm.length_unit_key
				 FROM product_master as pm 
				 LEFT JOIN user_master as um ON (pm.user_id = um.user_id)
				 LEFT JOIN weight_master as wm ON (pm.weight_unit_id = wm.weight_unit_id)
				 LEFT JOIN length_master as lm ON (pm.length_unit_id = lm.length_unit_id)
				 WHERE pm.user_id = ".$this->user_id." AND pm.product_id = ".$id;
		$sql2 =" SELECT * 
				 FROM product_images
				 WHERE product_id =".$id;
		$sql3 =" SELECT cm.*
				 FROM product_to_categories as ptc
				 LEFT JOIN category_master as cm ON(cm.category_id = ptc.category_id)
				 WHERE cm.is_active = 1 
				 AND ptc.product_id =".$id;
	 	$sql4 =" SELECT po.*,pod.option_name,pod.product_options_id as LNK_options_id, pod.option_code ,pod.product_options_detail_id, pod.option_weight, pod.option_price, pod.option_quantity,
				 (SELECT wm.weight_unit_key FROM weight_master as wm WHERE wm.weight_unit_id = pod.option_weight_unit_id ) as weight_unit_key
				 FROM product_options as po
				 LEFT JOIN product_options_detail as pod ON(po.product_options_id = pod.product_options_id)
				 WHERE po.product_id = ".$id;
		
		$sql5 =" SELECT *
				 FROM product_master_lang
				 WHERE product_id = ".$id;
		
		$product["detail"] = $db->fetchRow($sql);
		$product["images"] = $db->fetchAll($sql2);
		$product["category"] = $db->fetchAll($sql3);
		$product["options"] = $db->fetchAll($sql4);
		$product["language"] = $db->fetchAll($sql5);
		
		return $product;
	}
	
	/**
	 * Function updateProduct
	 *
	 * This function is used to update product 
     *
	 * Date created: 2011-09-15
	 *  
	 * @access public
	 * @param (Array) - $data : Array of record to update
	 *
	 * @return (int) - Return number of rows updated
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function updateProduct($data, $lang_array)
	{
		global $mysession;
		$db = $this->db;
				
		$where ="user_id = '".$this->user_id."' AND product_id = ".$data["product_id"];	
		$where1 ="product_id = ".$data["product_id"];	
		
		$db->update("product_master", $data, $where); 	 
		
		$validator = new Zend_Validate_Db_NoRecordExists(
				array(
						'table' => "product_master_lang",
						'field' => "language_id",
						'exclude' => $where1
				)
		);
		
		foreach( $lang_array as $key => $val )
		{
			if ($validator->isValid($key)) {
				
				$data1["product_id"] = $data["product_id"];
				$data1["language_id"] = $key;
				$data1["product_name"] = $val["product_name"];
				$data1["product_description"] = $val["product_description"];
				
				$db->insert("product_master_lang", $data1);	
				
			} else {
				
				$data2["product_id"] = $data["product_id"];
				$data2["language_id"] = $key;
				$data2["product_name"] = $val["product_name"];
				$data2["product_description"] = $val["product_description"];
				
				$where2 = "product_id = ".$data["product_id"]." and language_id = ".$key;
				
				$db->update("product_master_lang", $data2, $where2); 	
				
			}	
		}
		
		return true;
	}
	
	/**
	 * Function updateProductCategory
	 *
	 * This function is used to update the product categories.
     *
	 * Date created: 2011-09-15
	 *  
	 * @access public
	 * @param (Int) - $product_id : product id
	 * @param (String) - $cate_string : String of category is with comma seprated.
	 *
	 * @return (Boolean) - Return true on success.
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	function updateProductCategory($product_id, $cate_string)
	{
		$db = $this->db;		
		
		$db->delete("product_to_categories", 'product_id = ' .$product_id);	
		
		$category_array = explode(",",$cate_string);
		 
		foreach($category_array as $key => $val )
		{
			if( $val != '' )
			{
				$data["product_id"] = $product_id;
				$data["category_id"] = $val;
				
				$db->insert("product_to_categories", $data); 	 
			}
		}
		
		return true;
	}
	
	
	/**
	 * Function insertProduct
	 *
	 * This function is used to insert product 
     *
	 * Date created: 2011-09-09
	 *  
	 * @access public
	 * @param (Array) - $data : Array of record to insert
	 * @return (int) - Return product id
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function insertProduct($data, $langArray)
	{
		$db = $this->db;
		
		$db->insert("product_master", $data); 	 
		
		$product_id = $db->lastInsertId();
		
		$sql = "INSERT into product_master_lang (product_id, language_id, product_name, product_description) values ";
		
		foreach( $langArray as $key => $val )
		{
			$sql .= "(" . $product_id . ",". $key. ", '".$val["product_name"]."', '".$val["product_description"]."' ),";
		}
		
		$sql = rtrim($sql,",");
		
		$db->query($sql);
				
		return $product_id;
	}
	
	/*
	 * GetProductOptionValueById(): To get product Options value.
	 *
	 * It is used to get all the details of particular product Options value.
	 *
	 * Date created: 2011-10-05
	 *
	 * @author  	Yogesh
	 * @param  (Int)   : $prodOptValId - Product option deatail id.
	 * @return (Array) : Array of product option value
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	public function GetProductOptionValueById($prodOptValId)
	{
		//print $prodOptId;die;
		$db = $this->db;
		
		$sql = "SELECT pod.*, po.option_title, po.product_id  
				FROM product_options_detail as pod
				LEFT JOIN product_options as po ON ( po.product_options_id = pod.product_options_id ) 
				WHERE pod.product_options_detail_id='".$prodOptValId."'";
		
		$result = $db->fetchRow($sql);
		return $result;
	}
	
	/*
	 * updateProductOptionValue(): To update product Options value.
	 *
	 * It is used to update the details of particular product Options value and product option.
	 *
	 * Date created: 2011-10-06
	 *
	 * @author  	Yogesh
	 * @return (Array) 	: $data1 - Array of product option value
	 * @param  (Array)  : $data2 - Array of Product option data.
	 * @return (Boolean): True on success.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	
	public function updateProductOptionValue($data1, $data2)
	{
		$db = $this->db;
				
		$where1 ="product_options_detail_id = ".$data1["product_options_detail_id"];		
		
		$db->update("product_options_detail", $data1, $where1); 	
		
		$where2 ="product_options_id = ".$data2["product_options_id"];		
		
		$db->update("product_options", $data2, $where2); 
		
		return true;	
	
	}
	
	/*
	 * insertProductOptionValue(): To insert product Options value.
	 *
	 * It is used to add the product Options value.
	 *
	 * Date created: 2011-10-06
	 *
	 * @author  	Yogesh
	 * @return (Array) 	: $data - Array of product option value
	 * @return (Boolean): True on success.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	
	public function insertProductOptionValue($data)
	{
		$db = $this->db;	
				
		$db->insert("product_options_detail", $data); 	 
		
		$product_options_detail_id = $db->lastInsertId();
		
		if($product_options_detail_id > 0 ) {
		
			$select = $db->select()
						 ->from("product_options_detail")
						 ->where("product_options_detail_id = ".$product_options_detail_id);
			$record = $db->fetchRow($select);
			return $record;
		
		} else {
			return false;
		}
	}
	
	/*
	 * DeleteProductOptionValue(): To delete product Options value.
	 *
	 * It is used to delete the product Options value.
	 *
	 * Date created: 2011-10-06
	 *
	 * @author  	Yogesh
	 * @return (Int) 	: $id - product option value id
	 * @return (Boolean): True on success.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	
	public function DeleteProductOptionValue($id)
	{
	
		$db = $this->db;	
		
		$db->delete("product_options_detail","product_options_detail_id = ".$id);	
		
		return true;
	
	} 

	
}
?>