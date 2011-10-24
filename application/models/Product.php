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
 * Class Models_Product
 *
 * Class Models_Product contains methods handle products on site.
 *
 * Date created: 2011-08-25
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Jayesh 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_Product
{
	
	private $db;
	private $admin_id;
	
	
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
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function __construct()
	{
		global $mysession;
		$this->db = Zend_Registry::get('Db_Adapter');
		$this->admin_id = $mysession->Admin_Id;
		
	}
 	
	 /*
	 * GetProductByCategoryId(): To get product from particular category.
	 *
	 * It is used to get all the product of that particular category.
	 *
	 * Date created: 2011-08-24
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
 	 
	public function GetProductByCategoryId($catids,$sort)
	{
		global $mysession;
		$db = $this->db;
		
		$where = '';
		$where .= "WHERE ptc.category_id IN (".$catids.")";
		//print $sort;die;
		if($sort == 'price_asc')
		{
 			$where .= " order By product_price asc";
			
		}elseif($sort == 'price_desc'){
			 
			$where .= " order By product_price desc";
			
		}elseif($sort == 'bestseller'){
			
			$where .= " order By sold_count desc";
			
		}elseif($sort == 'most_liked'){
			
			$where .= " order By like_count desc";
		}else{
		
			$where .= " order By sold_count desc";
		}
 		
		$sql = "SELECT DISTINCT pm.product_id,pm.product_name,pm.product_price,pi.*,um.*,cm.*,ROUND( (pm.product_price * ".$mysession->currency_value.") / cm.currency_value, 2 ) as prod_convert_price,pml.product_name as ProdName FROM product_to_categories as ptc 
				LEFT JOIN product_master as pm ON (pm.product_id = ptc.product_id)
				LEFT JOIN product_master_lang as pml ON (pm.product_id = pml.product_id and pml.language_id= ".DEFAULT_LANGUAGE.")
				LEFT JOIN product_images as pi ON (pm.product_id = pi.product_id and is_primary_image = 1)
				LEFT JOIN user_master as um ON (um.user_id = pm.user_id)
				LEFT JOIN currency_master as cm ON (cm.currency_id = um.currency_id)
				 ".$where."";
		//print $sql;die;
 		$result = $db->fetchAll($sql);
		return $result;
 	} 
	
	 /*
	 * GetProductDetails(): To get product Details.
	 *
	 * It is used to get all the details of particular product.
	 *
	 * Date created: 2011-08-29
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	public function GetProductDetails($prodId)
	{
		global $mysession;
		$db = $this->db;
		$sql = "SELECT pm.*,um.*,wm.*,lm.*,cm.*,ROUND( (pm.product_price * ".$mysession->currency_value.")/  cm.currency_value , 2 ) as prod_convert_price,pml.product_name as ProdName,pml.product_description as ProdDesc FROM product_master as pm
				LEFT JOIN product_master_lang as pml ON (pm.product_id = pml.product_id and pml.language_id= ".DEFAULT_LANGUAGE.")
				LEFT JOIN user_master as um ON (pm.user_id = um.user_id)
  				LEFT JOIN weight_master as wm ON (pm.weight_unit_id = wm.weight_unit_id)
				LEFT JOIN length_master as lm ON (pm.length_unit_id = lm.length_unit_id)
				LEFT JOIN currency_master as cm ON (cm.currency_id = um.currency_id)
				WHERE pm.product_id='".$prodId."'";
		//print $sql;die;
 		$result = $db->fetchRow($sql);
		return $result;
	}
	
	 /*
	 * GetProductByRetailerId(): To get all product for particular retailer.
	 *
	 * It is used to get all the product of particular retailer.
	 *
	 * Date created: 2011-09-26
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	public function GetProductByRetailerId($ret_id,$sort)
	{
		global $mysession;
		$db = $this->db;
		
		$where = '';
		$where .= "WHERE pm.user_id='".$ret_id."'";
		//print $sort;die;
		if($sort == 'price_asc')
		{
			 
			$where .= " order By product_price asc";
			
		}elseif($sort == 'price_desc'){
			 
			$where .= " order By product_price desc";
			
		}elseif($sort == 'bestseller'){
			
			$where .= " order By sold_count desc";
			
		}elseif($sort == 'most_liked'){
			
			$where .= " order By like_count desc";
		}else{
		
			$where .= " order By sold_count desc";
		}
 		
		$sql = "SELECT DISTINCT pm.*,pi.*,um.store_name,cm.*,ROUND( (pm.product_price * ".$mysession->currency_value.")/  cm.currency_value , 2 ) as prod_convert_price,pml.product_name as ProdName FROM product_master as pm 
				LEFT JOIN product_master_lang as pml ON (pm.product_id = pml.product_id and pml.language_id= ".DEFAULT_LANGUAGE.")
				LEFT JOIN product_images as pi ON (pm.product_id = pi.product_id and is_primary_image = 1)
				LEFT JOIN user_master as um ON (um.user_id = pm.user_id)
				LEFT JOIN currency_master as cm ON (cm.currency_id = um.currency_id)
				 ".$where."";
		//print $sql;die;
 		$result = $db->fetchAll($sql);
		return $result;
 	} 
	
	 /*
	 * GetProductImages(): To get product Details.
	 *
	 * It is used to get all the details of particular product images.
	 *
	 * Date created: 2011-08-29
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	public function GetProductImages($prodId)
	{
		global $mysession;
		$db = $this->db;
		$sql = "SELECT * FROM product_images WHERE product_id='".$prodId."' order By is_primary_image desc";
		//print $sql;die;
 		$result = $db->fetchAll($sql);
		return $result;
	}
	
	 /*
	 * GetProductOption(): To get product Options.
	 *
	 * It is used to get all the details of particular product Options.
	 *
	 * Date created: 2011-08-29
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	public function GetProductOption($prodId)
	{
		global $mysession;
		$db = $this->db;
		$sql = "SELECT * FROM product_options WHERE product_id='".$prodId."'";
		 
 		$result = $db->fetchAll($sql);
		return $result;
	}

	 /*
	 * GetProductOptionUsingValue(): To get product Options using value submit by user.
	 *
	 * It is used to get product Options using value submit by user.
	 *
	 * Date created: 2011-09-27
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	public function GetProductOptionUsingValue($prodId,$value)
	{
		global $mysession;
		$db = $this->db;
		$sql = "SELECT *,ROUND( ( pod.option_price * ".$mysession->currency_value.") / cm.currency_value, 2 ) as Opt_convert_price FROM product_options_detail as pod
				LEFT JOIN product_options as po ON (pod.product_options_id = po.product_options_id) 	
				LEFT JOIN product_master as pm ON (pm.product_id = po.product_id)
 				LEFT JOIN user_master as um ON (um.user_id = pm.user_id)
				LEFT JOIN currency_master as cm ON (cm.currency_id = um.currency_id)	
				WHERE po.product_id='".$prodId."' and pod.product_options_detail_id = '".$value."'";
		
 		$result = $db->fetchRow($sql);
		return $result;
	}

	 /*
	 * GetProductOptionValue(): To get product Options value.
	 *
	 * It is used to get all the details of particular product Options value.
	 *
	 * Date created: 2011-09-20
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	public function GetProductOptionValue($prodOptId)
	{
		global $mysession;
		$db = $this->db;
		$sql = "SELECT * FROM product_options_detail WHERE product_options_id='".$prodOptId."'";
		 
		//print $sql;die;
 		$result = $db->fetchAll($sql);
		return $result;
	}
 	
	 /*
	 * getProductOptPrice(): To get product Options price using its detail id.
	 *
	 * It is used to get price of the product option.
	 *
	 * Date created: 2011-09-20
	 *
	 * @author  Jayesh 
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	public function getProductOptPrice($Opt_DetailId)
	{
		global $mysession;
		$db = $this->db;
		$sql = "SELECT *,ROUND( ( pod.option_price * ".$mysession->currency_value.") / cm.currency_value, 2 ) as Opt_convert_price FROM product_options_detail as pod
				LEFT JOIN product_options as po ON (po.product_options_id = pod.product_options_id)
				LEFT JOIN product_master as pm ON (pm.product_id = po.product_id)
 				LEFT JOIN user_master as um ON (um.user_id = pm.user_id)
				LEFT JOIN currency_master as cm ON (cm.currency_id = um.currency_id)
				WHERE product_options_detail_id='".$Opt_DetailId."'";
		 
		//print $sql;die;
 		$result = $db->fetchRow($sql);
		return $result;
	}
	
	/**
	 * Function GetProductSearch
	 *
	 * This function is used to search the product as per various category.
     *
	 * Date created: 2011-08-26
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of search options
	 * @return (Array) - Return Array of records
	 * @author Jayesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function GetProductSearch($querystring,$search,$catid,$sort)
	{
		global $mysession;
		$db = $this->db;
	 	 
 		$sql  = "";
		$sql .= "SELECT DISTINCT pm.product_id, pm.*, pi.*,um.*,cm.*,ROUND( (pm.product_price * ".$mysession->currency_value.") / cm.currency_value, 2 ) as Prod_convert_price,pml.product_name as ProdName FROM product_master as pm 
				 LEFT JOIN product_master_lang as pml ON (pm.product_id = pml.product_id and pml.language_id= ".DEFAULT_LANGUAGE.")	
				 LEFT JOIN product_to_categories as ptc ON(pm.product_id = ptc.product_id)
				 LEFT JOIN product_images as pi ON (pi.product_id = pm.product_id and is_primary_image = 1)
				 LEFT JOIN user_master as um ON (um.user_id = pm.user_id)
				 LEFT JOIN currency_master as cm ON (cm.currency_id = um.currency_id)
				 ";
		// Check search array is null or not
		if($search = 1) {
			
			if($querystring != '') {
				$sql.=" WHERE pml.product_name like '%".$querystring."%'";
			} 
			if($catid != 0 && $catid != ''){
				$sql.=" AND ptc.category_id = ".$catid."";
			}
			
		} else {
		
			$sql .= " WHERE 1=1";
		}
		
		if($sort == 'price_asc')
		{
			 
			$sql .= " order By product_price asc";
			
		}elseif($sort == 'price_desc'){
			 
			$sql .= " order By product_price desc";
			
		}elseif($sort == 'bestseller'){
			
			$sql .= " order By sold_count desc";
			
		}elseif($sort == 'most_liked'){
			
			$sql .= " order By like_count desc";
		}else{
		
			$sql .= " order By sold_count desc";
		}
		//print $sql;die;
		$result =  $db->fetchAll($sql);		
		return $result;		
	}

	/**
	 * Function GetFeaturedSeller
	 *
	 * This function is used to get featured seller of the store.
     *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of search options
	 * @return (Array) - Return Array of records
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/

	
	public function GetFeaturedSeller()
	{
		global $mysession;
		$db = $this->db;
		$sql = "SELECT pm.*,pi.* FROM product_master as pm
				LEFT JOIN product_images as pi ON (pm.product_id = pi.product_id and pi.is_primary_image='1')
				WHERE pm.product_is_featured='1' limit 0,3";
		 
 		$result = $db->fetchAll($sql);
		return $result;
	
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
	
	public function insertProduct($data)
	{
		$db = $this->db;
		$id = 0;
		
		$db->insert("product_master", $data); 	 
		
		$id = 	$db->lastInsertId();	
		
		//insert values for language table
		$Language = new Models_Language();
		
		$languages = $Language->getAllLanguages();
		
		for($i = 0; $i < count($languages); $i++)
		{
			$ldata = array();
			$ldata["product_id"] = $id;
			$ldata["language_id"] = $languages[$i]["language_id"];	
			$db->insert("product_master_lang", $ldata); 	 
		}
		
		return $id;
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
				
		$where ="product_id = ".$data["product_id"];		
		
		$db->update("product_master", $data, $where); 	 
		
		$validator = new Zend_Validate_Db_NoRecordExists(
				array(
						'table' => "product_master_lang",
						'field' => "language_id",
						'exclude' => $where
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
	 * Function updateProductLang
	 *
	 * This function is used to update product language
     *
	 * Date created: 2011-10-20
	 *  
	 * @access public
	 * @param (Array) - $data : Array of record to update
	 * @param (Int) - $product_id : product id
	 * @param (Int) - $language_id : language id
	 *
	 * @return (int) - Return number of rows updated
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function updateProductLang($data,$product_id,$language_id)
	{
		global $mysession;
		$db = $this->db;
				
		$where ="product_id = ". $product_id . " and language_id = " . $language_id;		
		
		return $db->update("product_master_lang", $data, $where); 	 
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
		global $mysession;
		$db = $this->db;
		
		$select = "SELECT pm.product_id,
					(SELECT cm.category_name 
					FROM category_master as cm
					WHERE cm.category_id = ptc.category_id) as category_name
				   FROM product_master as pm 
				   LEFT JOIN product_to_categories as ptc
				   ON (pm.product_id = ptc.product_id)";
		
		return $db->fetchAll($select);
	}

	/**
	 * function ProductExist
	 *
	 * It is used to check that product is exist or not.
	 *
	 * Date created: 2011-09-02
	 *
	 * @param (int) $prodId- Product Id.
	 * @return (Array) - Return true on success
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 */
	public function ProductExist($prodId)
	{
		global $mysession;
		$db= $this->db;
		
		$sql = "SELECT * FROM product_master WHERE product_id = ".$prodId."";
		
		$result = $db->fetchRow($sql);
		if($result == NULL || $result == "")
		{
			return false;
		
		}else{ 
			return true;
		}
	
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
		global $mysession;
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
				   ON (pm.product_id = ptc.product_id) GROUP BY pm.product_id";
		
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
		global $mysession;
		$db = $this->db;
		$sql = "";
		if(count($data) > 0 && $data != "") {
			foreach($data as $key => $val) {				
				if($val != "") {
					$sql.=" AND lower(".$key.") like '%".$val."%'";					
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
				   WHERE 1=1 ".$sql." 
				   GROUP BY pm.product_id";
		
		return $db->fetchAll($select);	
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
		global $mysession;
		$db = $this->db;
		$select = $db->select()
				 	 ->from($table)
					 ->where($where);
		return $db->fetchAll($select);
	}
	
	
	/**
	 * Function DeleteProductDetail
	 *
	 * This function is used to delete product details, images and product properties.
     *
	 * Date created: 2011-09-14
	 *
	 * @access public
	 * @param (Int)  		- $id : Value of product id
	 * @return (Boolean) 	- Return true on success
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function DeleteProductDetail($id)
	{
		global $mysession;
		$db = $this->db;	
		$flag = $db->delete("product_master","product_id = ".$id);		
		$folder_path = SITE_PRODUCT_IMAGES_FOLDER . "/p" . $id;
		
		if($flag) {
			
			//delete all images from product folder
			deleteAllFiles($folder_path . "/*.*");
			
			//Delete product folder to store images
			rmdir(realpath($folder_path));
					
			$db->delete("product_images","product_id = ".$id);			
			$product_options = $this->fetchAllRecords("product_options","product_id = ".$id);			
			if( isset($product_options) ) {		
				foreach($product_options as $key => $val) {
					$db->delete("product_options_detail","product_options_id = ".$val["product_options_id"]);
				}
			}
			$db->delete("product_options","product_id = ".$id);
			$db->delete("product_to_categories","product_id = ".$id);
			$db->delete("product_master_lang","product_id = ".$id);
			$this->DeleteCartProduct($id);
			
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Function DeleteAllProductDetail
	 *
	 * This function is used to delete multiple products.
     *
	 * Date created: 2011-09-14
	 *
	 * @access public
	 * @param (Array) 		- $ids : Array of Product id.
	 * @return (Boolean) 	- Return true on success
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function DeleteAllProductDetail($ids)
	{
		global $mysession;
		$db = $this->db;	
		
		if( isset($ids) ) {
		
			foreach( $ids as $key => $val ) {
				
				$flag = $this->DeleteProductDetail($val);
				
				if($flag) { } else { break; return false; }
			}
			
			return true;	
		}
	}
	
	/**
	 * Function checkProductForUser
	 *
	 * This function is used to check product for given user
     *
	 * Date created: 2011-09-15
	 *  
	 * @access public
	 * @param (int) - $id : product id
	 * @param (int) - $user_id : user id
	 *
	 * @return (Boolean) - Return true if product for given user is present false otherwise
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function checkProductForUser($id,$user_id)
	{
		global $mysession;
		$db = $this->db;
		
		$sql = "select count(*) from product_master where product_id = " . $id . " and user_id = " . $user_id;
		
		$data = $db->fetchOne($sql);
			
		if($data === "1") {
			return true; 
		} else {
			return false;
		}	
		
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
		global $mysession;
		$db = $this->db;
		
		$product = array();
		
		
		$sql = " SELECT pm.*,CONCAT(um.user_fname,' ',um.user_lname) as user_name,wm.weight_unit_key,lm.length_unit_key
				 FROM product_master as pm 
				 LEFT JOIN user_master as um ON (pm.user_id = um.user_id)
				 LEFT JOIN weight_master as wm ON (pm.weight_unit_id = wm.weight_unit_id)
				 LEFT JOIN length_master as lm ON (pm.length_unit_id = lm.length_unit_id)
				 WHERE pm.product_id = ".$id;
		$sql2 =" SELECT * 
				 FROM product_images
				 WHERE product_id =".$id;		
		$sql3 =" SELECT cm.*
				 FROM product_to_categories as ptc
				 JOIN category_master as cm ON(cm.category_id = ptc.category_id)
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
	 * Function updateProductOption
	 *
	 * This function is used to update the product options and its values.
     *
	 * Date created: 2011-09-15
	 *  
	 * @access public
	 * @param (Int) 	- $id : product option id
	 * @param (String) 	- $title : product option title
	 * @param (String) 	- $detail : Value of product options with comma seprated.
	 *
	 * @return (Boolean) - Return true on success.
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	function updateProductOption($id,$title,$detail)
	{
		
		$db = $this->db;		
		
		$data["option_title"] =  $title;
		$where = "product_options_id=".$id;
		$db->update("product_options", $data, $where); 	
		
		$db->delete("product_options_detail", 'product_options_id = ' .$id);	
		
		$detail_array = explode(",",$detail);
		 
		foreach($detail_array as $key => $val )
		{
			if( $val != '' )
			{
				$data2["product_options_id"] = $id;
				$data2["option_name"] = $val;
				
				$db->insert("product_options_detail", $data2); 	 
				
			}
		}
		
		return true;
	}
	
	
	/**
	 * Function deleteProductOption
	 *
	 * This function is used to delete the product options and its values.
     *
	 * Date created: 2011-09-15
	 *  
	 * @access public
	 * @param (Int) 	- $id : product option id
	 *
	 * @return (Boolean) - Return true on success.
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	function deleteProductOption($id)
	{
		$db = $this->db;	
		
		$db->delete("product_options", 'product_options_id = ' .$id);	
		$db->delete("product_options_detail", 'product_options_id = ' .$id);
		
		return true;
	
	}
	
	/**
	 * Function insertProductOption
	 *
	 * This function is used to insert the product options and its values.
     *
	 * Date created: 2011-09-15
	 *  
	 * @access public
	 * @param (Int) 	- $pid : product id
	 * @param (String) 	- $title : product option title
	 * @param (String) 	- $detail : Value of product options with comma seprated.
	 *
	 * @return (Int) 	- $option_id : Product option id.
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	function insertProductOption($pid,$title) 
	{
		$db = $this->db;	
		
		$data["product_id"] = $pid;
		$data["option_title"] = $title;
		
		$db->insert("product_options", $data); 	 
		$option_id =  $db->lastInsertId(); 
	
		return $option_id;
	}

	/**
	 * Function GetSellerInformation
	 *
	 * This function is used to get product seller information.
     *
	 * Date created: 2011-08-31
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of search options
	 * @return (Array) - Return Array of records
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/

	
	public function GetSellerInformation($Id)
	{
		$db = $this->db;
		$sql = "SELECT um.*,uml.store_description as StoreDesc,uml.store_terms_policy as StoreTermsPolicy,uml.return_policy as Returnpolicy,uml.item_returned_for as ReturnFor FROM user_master as um
				LEFT JOIN user_master_lang as uml ON(um.user_id = uml.user_id and uml.language_id = ".DEFAULT_LANGUAGE.")
				WHERE um.user_id=".$Id."";
		
		//print $sql;die;
 		$result = $db->fetchRow($sql);
		return $result;
	
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
	
	
	function getCateTree( $cid = array() )
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
				  if( in_array($nav_row['category_id'], $cid) ) {
				    $tree .= "<option selected='selected' value='".$nav_row['category_id']."' >";
				  } else {
					$tree .= "<option value='".$nav_row['category_id']."' >";				
				  }
				  $tree .= $nav_row['category_name'];                    // Process the main tree node
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
		 $child_query = "SELECT * FROM category_master WHERE is_active = 1 AND parent_id=" . $oldID;
		 
		 $row = $db->fetchAll($child_query);
		 
		foreach ( $row as $key => $child )
		{
			if ( $child['category_id'] != $child['parent_id'] )
			{		
			
				if( in_array($child['category_id'], $cid ) ) {
					$tempTree .= "<option selected='selected' value='".$child['category_id']."' >";
				} else {
					$tempTree .= "<option value='".$child['category_id']."' >";				
				}
				for ( $c=0; $c < $depth; $c++ )               // Indent over so that there is distinction between levels
				{ 
					$tempTree .= "&nbsp;&nbsp;-"; 
				}
			   
				$tempTree .=  "&nbsp;&nbsp;-&nbsp;&nbsp;".$child['category_name'] . "<br>";
				$tempTree .= "</option>";
				$depth++;          // Incriment depth b/c we're building this child's child tree  (complicated yet???)
				$tempTree .= $this->build_child($child['category_id'],$cid);          // Add to the temporary local tree
				$depth--;          // Decrement depth b/c we're done building the child's child tree.
				array_push($exclude, $child['category_id']);               // Add the item to the exclusion list
			}
		}
	 
		 return $tempTree;          // Return the entire child tree
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


	/*
	 * GetBestSelllerProduct(): To get best seller product in store.
	 *
	 * It is used to get best seller product in store.
	 *
	 * Date created: 2011-11-06
	 *
	 * @author  	Jayesh
	 * @return (Array) : Array of best seller product
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	
	public function GetBestSelllerProduct()
	{
		global $mysession;
		$db = $this->db;
		
		$sql = "SELECT pm.*,pi.*,um.*,cm.*,ROUND( (pm.product_price * ".$mysession->currency_value.") / cm.currency_value, 2 ) as converted_price,pml.product_name as ProdName,pml.product_description FROM product_master as pm
				LEFT JOIN product_master_lang as pml ON (pm.product_id = pml.product_id and pml.language_id= ".DEFAULT_LANGUAGE.")
				LEFT JOIN product_images as pi ON (pm.product_id = pi.product_id and is_primary_image = 1)
				LEFT JOIN user_master as um ON (um.user_id = pm.user_id)
				LEFT JOIN currency_master as cm ON (cm.currency_id = um.currency_id)
				order By sold_count desc limit 0,3";
		//print $sql;die;
		$result = $db->fetchAll($sql);
		
		return $result;
	
	}
	
	/*
	 * GetFrndsLikeProduct(): To get friends like product in store.
	 *
	 * It is used to get friends like product in store.
	 *
	 * Date created: 2011-11-06
	 *
	 * @author  	Jayesh
	 * @return (Array) : Array of best seller product
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	
	public function GetFrndsLikeProduct()
	{
		global $mysession;
		$db = $this->db;
		
		$sql = "SELECT pm.*,pi.*,um.*,cm.*,ROUND( (pm.product_price * ".$mysession->currency_value.")/ cm.currency_value, 2 ) as converted_price,pml.product_name as ProdName,pml.product_description FROM product_master as pm 
				LEFT JOIN product_master_lang as pml ON (pm.product_id = pml.product_id and pml.language_id= ".DEFAULT_LANGUAGE.")
				LEFT JOIN product_images as pi ON (pm.product_id = pi.product_id and is_primary_image = 1)
				LEFT JOIN user_master as um ON (um.user_id = pm.user_id)
				LEFT JOIN currency_master as cm ON (cm.currency_id = um.currency_id)
				order By like_count desc limit 0,3";
		//print $sql;die;
		$result = $db->fetchAll($sql);
		return $result;
	
	}


	/*
	 * GetTopFrndsLikeProduct(): To get top friends like product in store.
	 *
	 * It is used to get top friends like product in store.
	 *
	 * Date created: 2011-11-06
	 *
	 * @author  	Jayesh
	 * @return (Array) : Array of best seller product
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	
	public function GetTopFrndsLikeProduct()
	{
		global $mysession;
		$db = $this->db;
		
		$sql = "SELECT pm.*,pi.*,cm.*,pml.product_name as ProdName,pml.product_description as ProdDesc FROM product_master as pm 
				LEFT JOIN product_master_lang as pml ON(pm.product_id = pml.product_id and pml.language_id= ".DEFAULT_LANGUAGE.")
				LEFT JOIN product_images as pi ON (pm.product_id = pi.product_id and is_primary_image = 1)
				LEFT JOIN user_master as um ON (um.user_id = pm.user_id)
				LEFT JOIN currency_master as cm ON (cm.currency_id = um.currency_id)
				order By like_count desc limit 0,5";
		//print $sql;die;
		$result = $db->fetchAll($sql);
		return $result;
	
	}
	
	/**
	 * function DeleteCartProduct
	 *
	 * It is used to delete product in to the cart.
	 *
	 * Date created: 2011-10-15
	 *
	 * @param (int) - $prodId : Product Id
	 * @return (Boolean) - Return true on success
	 * @author Yogesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	 
	public function DeleteCartProduct($prodId)
	{
		$db= $this->db;
		
		$sql = "SELECT * FROM cart_detail WHERE product_id = ".$prodId;
		
		$cart_deatail = $db->fetchAll($sql);
		if( count($cart_deatail) > 0 ) {
			$cart_detail_id = array();
			foreach( $cart_deatail as $key => $val )
			{
				$cart_detail_id[] = $val["cart_detail_id"]; 
			}
			
			$cartdetailId = implode(",",$cart_detail_id);
			
			$where1 = 'cart_detail_id in ('.$cartdetailId.')'; 			
			$db->delete("cart_product_options",$where1);	 
		}
		
		$where = array(
			'product_id = ?' => $prodId
		);
		
		$db->delete('cart_detail',$where);
		
		return true;
	}

}
?>