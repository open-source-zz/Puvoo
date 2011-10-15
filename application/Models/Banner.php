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
 * Class Models_Banner
 *
 * Class Models_Banner contains methods to handle Facebook banner.
 *
 * Date created: 2011-10-07
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Yogesh
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */   
 
class Models_Banner
{
	
	private $db;
	
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-10-07
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
		$this->db = Zend_Registry::get('Db_Adapter');
	}
	
	
	/**
	* Function GetAllBanner
	*
	* This function is used to get all available banner.
    *
	* Date created: 2011-10-07
	*
	* @access public
    * @param ()  - No parameter
	* @return (Array) - Return Array of records
	* @author Yogesh
	*  
	* @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	**/
	public function GetAllBanner()
	{
		$db = $this->db;
		
		$sql = "select * from facebook_banner";
		
 		$result = $db->fetchAll($sql);
		return $result;
	}
	
	/*
	 * GetBannerById(): To get data of banner by selected facebook banner id.
	 *
	 * It is used to get the all records of particular Banner by facebook banner id.
	 *
	 * Date created: 2011-10-07
	 *
	 * @author  Yogesh
	 * @param   two parameters / login_id and password.
     * @global  $db Zend_db for database.
                $mysession Zend_Session_Namespace for session variables.
	 * 
	 */
	
	public function GetBannerById($id)
	{
		$db = $this->db;
		
		$sql = "select * from facebook_banner where facebook_banner_id = ".$id;
 		$result = $db->fetchRow($sql);
		return $result;
	
	}
	
	/**
	 * Function deleteBanner
	 *
	 * This function is used to delete facebook banner on specified condition.
     *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param () (String)  - $id : facebook banner Id
	 * @return (Boolean) - Return true on success
	 * @author Vaibhavi
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	
	public function deleteBanner($id)
	{
		$db = $this->db;	
		$db->delete("facebook_banner", 'facebook_banner_id = ' .$id);		
		return true;		
	}
	
	/**
	 * Function deletemultipleBanner
	 *
	 * This function is used to delete multiple facebook banner on specified condition.
     *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param () (String)  - $ids : Sting of all Facebook banner id with comma seprated.
	 * @return (Boolean) - Return true on success
	 * @author Vaibhavi
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function deletemultipleBanner($ids)
	{
		$db = $this->db;	
		$where = 'facebook_banner_id in ('.$ids.')'; 			
		$db->delete("facebook_banner",$where);	 
		
		return true;	
	}
	
	
	
	/**
	 * Function insertBanner
	 *
	 * This function is used to insert facebook banner.
     *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to insert
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function insertBanner($data)
	{
		$db = $this->db;
		
		$db->insert("facebook_banner", $data); 	 
		
		return true; 
	}
	
	
	/**
	 * Function updateBanner
	 *
	 * This function is used to Update facebook banner records on specified condition.
     *
	 * Date created: 2011-10-07
	 *
	 * @access public
	 * @param () (Array)  - $data : Array of record to update
	 * @param () (String)  - $where : Condition on which update record
	 * @return (Boolean) - Return true on success
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	
	public function updateBanner($data,$where)
	{
		$db = $this->db;		
		$db->update("facebook_banner", $data, $where); 	
		return true;
	}
}
?>