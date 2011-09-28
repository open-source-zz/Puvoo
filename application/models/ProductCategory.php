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
 * Class Models_ProductCategory
 *
 * Class Models_Product contains methods handle product to categories on site.
 *
 * Date created: 2011-09-13
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 
class Models_ProductCategory
{
	
	private $db;
	
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-09-13
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
	 * Function insertProductCategory
	 *
	 * This function is used to insert product to categories 
     *
	 * Date created: 2011-09-13
	 *  
	 * @access public
	 * @param (Array) - $data : Array of record to insert
	 * @return (void) - Return void
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function insertProductToCategories($data)
	{
		$db = $this->db;
		
		$db->insert("product_to_categories", $data); 	 
				
		return;
	}
	
	/**
	 * Function deleteProductToCategories
	 *
	 * This function is used to delete product to categories 
     *
	 * Date created: 2011-09-15
	 *  
	 * @access public
	 * @param (int) - $id : id of product
	 * @return (void) - Return void
	 * @author Amar
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function deleteProductToCategories($id)
	{
		$db = $this->db;	
		$db->delete("product_to_categories", 'product_id = ' . $id);		
		return true;
	}
	
	
	
}
?>