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
	 * @param (string)  - $groups group name
	 * @param (int)  - $lang language id
	 * @return (array) - Return array of language key and value
	 * @author Amar 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function getGroupLanguage($group, $lang=1)
	{
		$db = $this->db;
		$arr_lang = array();
		$sql = "Select definition_key, definition_value from language_definitions where content_group = '" . $group . "' and language_id = ". $lang;
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
		
}
?>
