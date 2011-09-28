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
 * Class Models_ProductImages
 *
 * Class Models_ProductImages contains methods handle product images on site.
 *
 * Date created: 2011-09-14
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_ProductImages
{
	
	private $db;
	
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-09-14
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
	 * Function insertProductImages
	 *
	 * This function is used to insert product images
     *
	 * Date created: 2011-09-14
	 *  
	 * @access public
	 * @param (Array) - $data : Array of record to insert
	 * @return (void) - Return void
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function insertProductImages($data)
	{
		$db = $this->db;
		
		$db->insert("product_images", $data); 	 
				
		return $db->lastInsertId();
	}
	
	
	/**
	 * Function deleteImagesByProductId
	 *
	 * This function is used to delete product images 
     *
	 * Date created: 2011-09-15
	 *  
	 * @access public
	 * @param (int) - $id : id of product
	 * @param (int) - $is_primary : 1 for primary,  0 for non-primary
	 *
	 * @return (void) - Return void
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function deleteImagesByProductId($id,$is_primary=-1)
	{
		$db = $this->db;	
		$folder_path = SITE_PRODUCT_IMAGES_FOLDER . "/p" . $id;
		$data = "";
	
		if($is_primary === -1)
		{
			//delete all images from product folder
			deleteAllFiles($folder_path . "/*.*");
			
			//delete all images form database table
			$db->delete("product_images", 'product_id = ' . $id);
		}
		else
		{
			//get filename to delete it from product folder
			if($is_primary === 1)
			{
				$sql = "select image_name from product_images where is_primary_image = 1 and product_id = " . $id;
		
				$data = $db->fetchOne($sql);
				
				if($data != '' && $data != NULL)
				{
					$data = str_replace('_img','*',$data);
					deleteAllFiles($folder_path.'/'.$data);
				}
				
			}
			else
			{
				$sql = "select image_name from product_images where is_primary_image = 0 and product_id = " . $id;
		
				$data = $db->fetchAll($sql);
				
				if(count($data) > 0)
				{
					for($i = 0; $i < count($data); $i++)
					{
						$data[0]["image_name"] = str_replace('_img','*',$data[0]["image_name"]);
						deleteAllFiles($folder_path.'/'.$data[0]["image_name"]);	
					}
				}
				
				
			}
			
			//delete files image files from table
			$db->delete("product_images", array('product_id = ' . $id , 'is_primary_image = ' . $is_primary ));
		}
		return true;
	}
	
	
	/**
	 * Function deleteImagesByProductId
	 *
	 * This function is used to delete product images 
     *
	 * Date created: 2011-09-15
	 *  
	 * @access public
	 * @param (int) - $id : id of product
	 * @param (int) - $is_primary : 1 for primary,  0 for non-primary
	 *
	 * @return (void) - Return void
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function deleteSingleImagesById($id,$image_id)
	{
		$db = $this->db;	
		$folder_path = SITE_PRODUCT_IMAGES_FOLDER . "/p" . $id;
		$data = "";
	
		//get filename to delete it from product folder
		if($image_id > 0)
		{
			$sql = "select image_name from product_images where image_id = '".$image_id."' and product_id = " . $id;
		
			$data = $db->fetchOne($sql);
			
			$data = str_replace('_img','*',$data);
			deleteAllFiles($folder_path.'/'.$data);
		}
			
		//delete files image files from table
		$db->delete("product_images", array('product_id = ' . $id , 'image_id = ' . $image_id ));
		
		return true;
	}
	
	
	public function updateProductImage($data)
	{
	
		$db = $this->db;
				
		$where ="image_id = ".$data["image_id"];		
		
		return $db->update("product_images", $data, $where); 	
	}
	
}
?>