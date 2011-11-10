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
 * Class Models_Common
 *
 * Class Models_Common contains methods handle common on site.
 *
 * Date created: 2011-10-07
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Jayesh 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */ 
class Models_Common
{
	
	private $db;
	
	
	/**
	 * Function __construct
	 *
	 * This is a constructor functions.
     * It will set db adapter for the model 
	 *
	 * Date created: 2011-11-07
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Jayesh
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	function __construct()
	{
		$this->db = Zend_Registry::get('Db_Adapter');
	}

	/**
	 * function GetfacebookBanner 
	 *
	 * It is used to get banner that can be display on home page.
	 *
	 * Date created: 2011-10-07
	 *
 	 * @return (Array) - Return number of records
	 *
	 * @author Jayesh 
	 *
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetfacebookBanner()
	{
		$db= $this->db;
		
 		$sql = "SELECT * FROM facebook_banner WHERE is_active = '1' limit 0,5";
		//print $sql;die;		
 		$result = $db->fetchAll($sql);
		 
 		return $result;
	}
	
	/**
	 * function GetContactUsDetails 
	 *
	 * It is used to get contact us details.
	 *
	 * Date created: 2011-10-07
	 *
 	 * @return (Array) - Return number of records
	 *
	 * @author Jayesh 
	 *
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetContactUsDetails()
	{
		$db= $this->db;
		
 		$sql = "SELECT * FROM configuration
				LEFT JOIN configuration_group ON configuration_group.configuration_group_id = configuration.configuration_group_id
				WHERE configuration_group.configuration_group_key = 'Facebook Store' AND configuration_key IN ('contact_us_address', 'contact_us_telephone', 'contact_us_email')";
		//print $sql;die;		
 		$result = $db->fetchAll($sql);
		 
 		return $result;
	}

	/**
	 * function RetailerExist
	 *
	 * It is used to check that retailer is exist or not.
	 *
	 * Date created: 2011-09-10
	 *
	 * @param (int) $retId- Retailer Id.
	 * @return (Array) - Return true on success
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 */
	public function RetailerExist($retId)
	{
		$db= $this->db;
		
		$sql = "SELECT * FROM user_master WHERE user_id = ".$retId."";
		
		$result = $db->fetchRow($sql);
		if($result == NULL || $result == "")
		{
			return false;
		
		}else{ 
			return true;
		}
	
	}

	/**
	 * function GetCurrency
	 *
	 * It is used to get all currency names.
	 *
	 * Date created: 2011-10-12
	 *
	 * @param () - No parameter
	 * @return (Array) - Return array of records
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 */
	public function GetCurrency()
	{
		$db= $this->db;
		
		$sql = "SELECT * FROM currency_master";
		//print $sql;die;
		$result = $db->fetchAll($sql);
		
		return $result;
	
	}

	/**
	 * function GetCurrencyValue
	 *
	 * It is used to get currency value.
	 *
	 * Date created: 2011-10-12
	 *
	 * @param (Int) - $curr_id - currency id
	 * @return (Array) - Return array of records
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 */
	public function GetCurrencyValue($curr_id)
	{
		$db= $this->db;
		
		$sql = "SELECT * FROM currency_master WHERE currency_id = ".$curr_id."";
		//print $sql;die;
		$result = $db->fetchRow($sql);
		
		return $result;
	
	}

 	/**
	 * function GetPaypalDetails 
	 *
	 * It is used to get paypal details of cart user.
	 *
	 * Date created: 2011-10-15
	 *
 	 * @param (int) $userId- User Id.
	 *
	 * @return (Array) - Return number of records
	 *
	 *
	 * @author Jayesh 
	 *
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetPaypalDetails($userId)
	{
		$db= $this->db;
		
 		$sql = "SELECT * FROM user_master WHERE user_id=".$userId."";
		//print $sql;die;		
 		$result = $db->fetchRow($sql);
		 
 		return $result;
	}
	
	/**
	 * function GetConfigureValue 
	 *
	 * It is used to get configuration value that merchant set at admin.
	 *
	 * Date created: 2011-10-15
	 *
	 * @param (int) $pname- Keyname.
	 *
 	 * @return (Array) - Return number of records
	 *
	 * @author Jayesh 
	 *
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetConfigureValue($pname)
	{
		$db= $this->db;
		
 		$sql = "SELECT configuration_value FROM configuration
				LEFT JOIN configuration_group ON configuration_group.configuration_group_id = configuration.configuration_group_id
				WHERE configuration_group.configuration_group_key = 'Facebook Store' AND configuration_key ='".$pname."'";
		//print $sql;die;		
 		$result = $db->fetchRow($sql);
		 
 		return $result;
	}
	
	/**
	 * function GetConfigValue 
	 *
	 * It is used to get configuration value as per select country.
	 *
	 * Date created: 2011-10-19
	 *
	 * @param (int) $CountryId- Country Id.
	 *
 	 * @return (Array) - Return number of records
	 *
	 * @author Jayesh 
	 *
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetConfigValue($CountryId)
	{
		$db= $this->db;
		
 		$sql = "SELECT * FROM language_master WHERE country_id = ".$CountryId."";
		//print $sql;die;		
 		$result = $db->fetchRow($sql);
		 
 		return $result;
	}
	
	/**
	 * function GetDefaultConfigureValue 
	 *
	 * It is used to get default configuration value.
	 *
	 * Date created: 2011-10-19
	 *
  	 * @return (Array) - Return number of records
	 *
	 * @author Jayesh 
	 *
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetDefaultConfigValue()
	{
		$db= $this->db;
		
 		$sql = "SELECT * FROM language_master WHERE is_default = 1";
		//print $sql;die;		
 		$result = $db->fetchRow($sql);
		 
 		return $result;
	}

 	/**
	 * function GetVatForCountry 
	 *
	 * It is used to get default vat rate from language_master table.
	 *
	 * Date created: 2011-11-04
	 *
	 * @param (int) $CurrencyId- Currency Id.
	 *
  	 * @return (Array) - Return number of records
	 *
	 * @author Jayesh 
	 *
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetVatForCountry($CurrencyId)
	{
		$db= $this->db;
		
 		$sql = "SELECT vat FROM language_master WHERE currency_id = ".$CurrencyId."";
		//print $sql;die;		
 		$result = $db->fetchOne($sql);
		 
 		return $result;
	}
	
 	/**
	 * function GetWeigthUnit 
	 *
	 * It is used to get default weight unit for store.
	 *
	 * Date created: 2011-11-05
	 *
   	 * @return (int) - Return weight unit id
	 *
	 * @author Jayesh 
	 *
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetWeigthUnit()
	{
		$db= $this->db;
		
 		$sql = "SELECT weight_unit_id FROM weight_master WHERE is_default = 1";
		//print $sql;die;		
 		$result = $db->fetchOne($sql);
		 
 		return $result;
	}
	
 	/**
	 * function GetWeigthUnitPrice 
	 *
	 * It is used to get convert weight price.
	 *
	 * Date created: 2011-11-05
	 *
	 * @param (int) $from_weightunitid- From Weigth Unit Id.
	 *
	 * @param (int) $to_weightunitid- From Weigth Unit Id.
	 *
   	 * @return (int) - Return weight unit id
	 *
	 * @author Jayesh 
	 *
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetWeigthUnitPrice($from_weightunitid,$to_weightunitid)
	{
		$db= $this->db;
		
 		$sql = "SELECT value FROM weight_unit_conversion WHERE from_id = ".$from_weightunitid." and to_id=".$to_weightunitid."";
		//print $sql;die;		
 		$result = $db->fetchOne($sql);
		 
 		return $result;
	}

	/**
	 * function UpdateProductLikeCount
	 *
	 * It is used to updates product like count.
	 *
	 * Date created: 2011-11-07
	 *
	 * @param (Array) $prodIds - Array of product id
	 * @return (Array) - Return true on success
	 * @author  Jayesh 
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 */
	public function UpdateProductLikeCount($prodIds)
	{
		$db= $this->db;
		
		if(count($prodIds) > 0 ){ 
		
			foreach($prodIds as $key => $val)
			{
				$sql = "SELECT count(*) as cnt FROM user_product_likes WHERE product_id= ".$val."";
				
				$result = $db->fetchOne($sql);
			
				$sql1 = "UPDATE product_master set like_count = ".$result." WHERE product_id = ".$val."";
				
				$db->query($sql1);
			}
			
		}
		return true;
	
	}
	
 	/**
	 * function GetZoneDetails 
	 *
	 * It is used to get details of zone.
	 *
	 * Date created: 2011-11-09
	 *
	 * @param (int) $zoneid- tax_rate_id .
  	 *
   	 * @return (Array) - Return array of records
	 *
	 * @author Jayesh 
	 *
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetZoneDetails($zoneid)
	{
		$db= $this->db;
		
 		$sql = "SELECT * FROM tax_rate_detail WHERE tax_rate_id = ".$zoneid;
		//print $sql;die;		
 		$result = $db->fetchRow($sql);
		 
 		return $result;
	}
	
 	/**
	 * function GetDefaultTaxRate 
	 *
	 * It is used to get default tax rate for store.
	 *
	 * Date created: 2011-11-09
	 * @param (int) - $userid - user id
     * @return (Array) - Return array of records
	 *
	 * @author Jayesh 
	 *
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function GetDefaultTaxRate($userid)
	{
		$db= $this->db;
		
 		$sql = "SELECT * FROM tax_rate_class WHERE user_id = ".$userid." and is_default  = '1'";
		//print $sql;die;		
 		$result = $db->fetchRow($sql);
		 
 		return $result;
	}
	
 	/**
	 * function TaxCalculation 
	 *
	 * It is used to get tax rate.
	 *
	 * Date created: 2011-11-09
	 * @param (Array) $taxzone- array of data .
	 *
     * @return (Array) - Return array of records
	 *
	 * @author Jayesh 
	 *
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function TaxCalculation($taxzone,$taxrate, $Ship_country='', $state = '', $defaultRate )
	{
		global $mysession;
		
		$flag = false;
		 foreach($taxzone as $zn)
		 {
		 	if($zn !='')
			{
				$ZoneDetail = $this->GetZoneDetails($zn);
				
				$zone = explode(';',$ZoneDetail['zone']);
				
				//IT:st1,st2; US:st1,st3; AO
		
				for($k=0; $k < count($zone); $k++)
				{
					
					$taxArray = explode(':',$zone[$k]);
					
					
					if(isset($taxArray[0])) {
						
						$country = $taxArray[0];
					
					} else {
					
						$country = $zone[$k];
					}
					
					
//					if($country == $mysession->Default_Countrycode) {
//			
//						$tax_rate = $taxrate;
//						$flag = true;
//						break;
//
//					}else{
//					
//						$tax_rate = $mysession->default_taxrate;
//					}
					if($Ship_country != '')
					{
						//print $country." == ".$Ship_country."<br />";
						
						if($country == $Ship_country) {
				
							$tax_rate = $taxrate;
							$flag = true;
							break;
	
						}else{
						
							$tax_rate = $defaultRate;
						}
					}else{
						if($country == $mysession->Default_Countrycode) {
				
							$tax_rate = $taxrate;
							$flag = true;
							break;
	
						}else{
						
							$tax_rate = $defaultRate;
						}
						}

					
					if(isset($taxArray[1]))
					{
					
						$stateArray = explode(",", $taxArray[1] );
						
						foreach($stateArray as $key => $val1 )
						{
							
							if($state != '' ){
								
									foreach( $stateArray as $key2 => $val2 )
									{
									
										//print $state."/";
										//print $val2."=";
										if( $state == $val2  )
										{
											$tax_rate = $taxrate;
																						
										}else{
										
											
											$tax_rate = $defaultRate;
											
										}
									}//die;
								
								}
							
						}
						
					}
					
				}	
				
			}
			if($flag == true)	
				{
					break;
				}	
		
		 }
	
 		return $tax_rate;
		
				//	foreach($taxArray as $key => $val1 )
				//	{
		
						//if($key == 0 ) {
						
		
//							if($val1 == $mysession->Default_Countrycode) {
//			
//								$tax_rate = $taxrate;
//		
//							}else{
//							
//								$tax_rate = $mysession->default_taxrate;
//							}
		
						//} 
						//else {
						
							//if($val1 != '' ) {
//							
//								$stateArray = explode(",", $val1);
//								
//								if($state != '' ){
//								
//									foreach( $stateArray as $key2 => $val2 )
//									{
//									
//										if($val1 == $mysession->Default_Countrycode && $state = $val2  )
//										{
//											$tax_rate = $taxrate;
//																						
//										}else{
//											$tax_rate = $mysession->default_taxrate;
//											
//										}
//									}
//								
//								}
//							
//							}
						
						//}		
				//	}
		
				
			
	}
	
}
?>