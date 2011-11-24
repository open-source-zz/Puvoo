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
 * Rest_EditproductController
 *
 * Rest_EditproductController extends RestCommonController.
 * It is used to handle product related api calls.
 *
 * Date created: 2011-11-23
 *
 * @category	Puvoo
 * @package 	Rest_Controllers
 * @author	    Yogesh
 *  
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
class Rest_EditproductController extends RestCommonController
{
	protected $api_key = "";
	protected $user_id = 0;
	protected $translate = NULL;
	
	/**
	 * Function init
	 *
	 * This is function is used to initialize rest api
	 *
	 * Date created: 2011-11-23
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function init(){
		parent::init();
		
		$this->translate = Zend_Registry::get('Zend_Translate');
		//Get api key from header
		$this->api_key = $this->getRequest()->getHeader('apikey');
		
		if($this->api_key == "" || $this->api_key == null)
		{
			$this->view->message = $this->translate->_('Product_Access_Denied');
        	$this->getResponse()->setHttpResponseCode(401);
			
		}
		else{
		
			$userlogin = new Models_UserLogin();
			$this->user_id = $userlogin->checkApiToken($this->api_key);
		}
		
		if($this->user_id <= 0){ 
			$this->view->message = $this->translate->_('Product_Access_Denied');
        	$this->getResponse()->setHttpResponseCode(401);
		}	
		
	}
	
    /**
	 * Function optionsAction
	 *
	 * The options action handles OPTIONS requests; it should respond with
     * the HTTP methods that the server supports for specified URL.
	 *
	 * Date created: 2011-11-23
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function optionsAction()
    {
        $this->view->message = $this->translate->_('Product_Resource_Option');
        $this->getResponse()->setHttpResponseCode(200);
    }


	/**
	 * Function indexAction
	 *
	 * The index action handles index/list requests; it should respond with a
     * list of the requested resources.
	 *
	 * Date created: 2011-11-23
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function indexAction()
    {
        if($this->user_id > 0)
		{
			$this->view->resources = array('mykey' => $this->api_key);
			$this->view->message = $this->translate->_('Product_Resource_Option');
        	$this->getResponse()->setHttpResponseCode(200);
		}
    }

    /**
	 * Function headAction
	 *
	 * The head action handles HEAD requests; it should respond with an
     * identical response to the one that would correspond to a GET request,
     * but without the response body.
	 *
	 * Date created: 2011-11-23
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function headAction()
    {
        $this->getResponse()->setHttpResponseCode(200);
    }

	/**
	 * Function getAction
	 *
	 * The get action handles GET requests and receives an 'id' parameter; it
     * should respond with the server resource state of the resource identified
     * by the 'id' value.
	 *
	 * Date created: 2011-11-23
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author YOgesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function getAction()
    {
		if($this->user_id > 0)
		{       
	    	$this->view->id = $this->_getParam('id');
        	$this->view->resource = new stdClass;
        	$this->getResponse()->setHttpResponseCode(200);
		}
    }

	/**
	 * Function postAction
	 *
	 * The post action handles POST requests; it should accept and digest a
     * POSTed resource representation and persist the resource state.
	 *
	 * Date created: 2011-11-23
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function postAction()
    {
		
		if($this->user_id > 0)
		{
			
			//single filter
			$filter = new Zend_Filter_StripTags();
			

			//Filter chain 1
			$filterChain1 = new Zend_Filter();

			$filterChain1->addFilter(new Zend_Filter_StripTags())
			            ->addFilter(new Zend_Filter_Alpha());

 			//Filter chain 2
			$filterChain2 = new Zend_Filter();

			$filterChain2->addFilter(new Zend_Filter_StripTags())
			            ->addFilter(new Zend_Filter_Alnum());

			//Url Validator
			$url_validator = new UrlValidator();
			
			//Date Validator
			$date_validator = new Zend_Validate_Date();
			
			$myparams = $this->getRequest()->getParams();
			
			
			$products = array();
			$arr_error = array();
			$categories = array();
			$images = array();
			$attributes = array();
			$languages = array();
					
			
			//create objects
			$Product = new Models_Product();
			$Weight = new Models_Weight();
			$Length = new Models_Length();
			$Category = new Models_Category();
			$ProdImg = new Models_ProductImages();
			$Language = new Models_Language();
			$UserMaster = new Models_UserMaster();
			
			//variables
			$img_content = "";
			$img_width = 0;
			$img_height = 0;
			$img_type = 0;
			
			$pid = 0;
			$name = "";
			$description = 	"";
			$price = 0;
			$code = "";
			$weight = 0;
			$length = 0;
			$width = 0;
			$depth = 0;
			$weight_unit = "";
			$length_unit = "";
			$start_sales = "";
			$available_qty = 0;
			$main_image = "";
			$discount = 0;
			$available_date = "";
			$expiration_date = "";
			$promotion_start_date = "";
			$promotion_end_date = "";
			$main_image = "";
			$prod_opt_qty = 0;
			
					
			$cntProduct = 0;
			$isProdArray = false;
			
			if(isset($myparams["Products"]["Product"][0]))
			{
				
				$cntProduct = count($myparams["Products"]["Product"]);
				$isProdArray = true;	
			}
			else
			{
				$cntProduct = 1;
				$isProdArray = false;
			}
			
			if(isset($myparams["Products"]) && $cntProduct <= 1)
			{
				$x = 0;
				for($i = 0; $i < $cntProduct; $i++)
				{
					if($isProdArray)
					{
						$prod = $myparams["Products"]["Product"][$i];
					}
					else
					{
						$prod = $myparams["Products"]["Product"];
					}
					
					//filter values
					if(isset($prod['pid']))
					{
						$pid = (int) $filter->filter(trim($prod['pid']));
					}
					
					if(isset($prod['name']))
					{
						$name = $filter->filter(trim($prod['name']));
					}
					
					if(isset($prod['description']))
					{
						$description = 	trim($prod['description']);
					}
					
					if(isset($prod['price']))
					{
						$price = 	(float) $filter->filter(trim($prod['price']));
					}
					
					if(isset($prod['code']))
					{
						$code = 	$filterChain2->filter(trim($prod['code']));
					}
					
					if(isset($prod['weight']))
					{
						$weight = 	(float) $filter->filter(trim($prod['weight']));
					}
					
					if(isset($prod['length']))
					{
						$length = 	(float) $filter->filter(trim($prod['length']));
					}
					
					if(isset($prod['width']))
					{
						$width = 	(float) $filter->filter(trim($prod['width']));
					}
				
					if(isset($prod['depth']))
					{
						$depth = 	(float) $filter->filter(trim($prod['depth']));
					}
					
					if(isset($prod['weight_unit']))
					{
						$weight_unit = 	$filterChain1->filter(trim($prod['weight_unit']));
					}
					
					if(isset($prod['length_unit']))
					{
						$length_unit = 	$filterChain1->filter(trim($prod['length_unit']));
					}
					
					$tex_rate_class = '';
					
					if(isset($prod['taxrate_class']))
					{
						$tex_rate_class = $prod['taxrate_class'];
						
					}
					
					if(isset($prod['start_sales']))
					{
						$start_sales = 	(int) $filter->filter(trim($prod['start_sales']));
					}
					
					if(isset($prod['available_qty']))
					{
						$available_qty = (int) $filter->filter(trim($prod['available_qty']));
					}
					
					if(isset($prod['main_image']))
					{
						$main_image = $filter->filter(trim($prod['main_image']));
					}
					
					if(isset($prod['discount']))
					{
						$discount = (int) $filter->filter(trim($prod['discount']));
					}
					else
					{
						$discount = "";
					}
					
					if(isset($prod['available_date']))
					{
						$available_date = $filter->filter(trim($prod['available_date']));
					}
					
					if(isset($prod['expiration_date']))
					{
						$expiration_date = $filter->filter(trim($prod['expiration_date']));
					}
					
					if(isset($prod['promotion_start_date']))
					{
						$promotion_start_date = $filter->filter(trim($prod['promotion_start_date']));
					}
					
					if(isset($prod['promotion_end_date']))
					{
						$promotion_end_date = $filter->filter(trim($prod['promotion_end_date']));
					}
					
					if(isset($prod["categories"]))
					{
						if(is_array($prod["categories"]["id"]))
						{
							$categories = $prod["categories"]["id"];
						}
						else
						{
							$categories = array($prod["categories"]["id"]);
						}
					}
					
					if(isset($prod["images"]))
					{
						if(is_array($prod["images"]["image"]))
						{
							$images = $prod["images"]["image"];
						}
						else
						{
							$images = array($prod["images"]["image"]);
						}
					}
					
					if(isset($prod["attributes"]['attribute']))
					{
						if(isset($prod["attributes"]['attribute'][0]))
						{
							$attributes = $prod["attributes"]['attribute'];
						}
						else
						{
							$attributes = array($prod["attributes"]['attribute']);
						}
					}
					
					if(isset($prod["languages"]))
					{
						if(isset($prod["languages"]["language"][0]))
						{
							$languages = $prod["languages"]["language"];
						}
						else
						{
							$languages = array($prod["languages"]["language"]);
						}
					}
					
					//check values
					if($pid <= 0){
						$arr_error[] = $this->translate->_('Product_Invalid_Product_Id');
					}
					
					if($pid > 0 && !$Product->checkProductForUser($pid, $this->user_id))
					{
						$arr_error[] = $this->translate->_('Product_Invalid_Product_Id');
					}
					
					if($price != "" && $price <= 0)
					{
						$arr_error[] = $this->translate->_('Product_Invalid_Product_Price');
					}
					
					if($weight != "" && $weight <= 0)
					{
						$arr_error[] = $this->translate->_('Product_Invalid_Product_Weight');
					}
					
					if($length != "" && $length <= 0)
					{
						$arr_error[] = $this->translate->_('Product_Invalid_Product_Length');
					}
					
					if($width != "" && $width <= 0)
					{
						$arr_error[] = $this->translate->_('Product_Invalid_Product_Width');
					}
					
					if($depth != "" && $depth <= 0)
					{
						$arr_error[] = $this->translate->_('Product_Invalid_Product_Depth');
					}
					
					if($tex_rate_class != "" || $tex_rate_class > 0 )
					{
						$where = "user_id = " .$this->user_id;
						
						if($UserMaster->ValidateTableField("tax_rate_class_id",$tex_rate_class,"tax_rate_class",$where))
						{
							$arr_error[] = $this->translate->_('Tax_Rate_Class_Not_Found_Product')." " . ($i+1);
						} 
					}

					
					if($available_qty != "" && $available_qty <= 0)
					{
						$arr_error[] = $this->translate->_('Product_Invalid_Product_Quantity');
					}
					
					if($available_date != "")
					{
						if(!$date_validator->isValid($available_date))
						{
							$arr_error[] = $this->translate->_('Product_Invalid_Product_Ava_Date');
						}
					}
					
					if($expiration_date != "")
					{
						if(!$date_validator->isValid($expiration_date))
						{
							$arr_error[] = $this->translate->_('Product_Invalid_Product_Exp_Date');
						}
					}
					
					if($promotion_start_date != "")
					{
						if(!$date_validator->isValid($promotion_start_date))
						{
							$arr_error[] = $this->translate->_('Product_Invalid_Product_Promo_Start_Date');
						}
					}
					
					if($promotion_end_date != "")
					{
						if(!$date_validator->isValid($promotion_end_date))
						{
							$arr_error[] = $this->translate->_('Product_Invalid_Product_Promo_End_Date');
						}
					}
					
					if($main_image != "" && !$url_validator->isValid($main_image))
					{
						$arr_error[] = $this->translate->_('Product_Invalid_Product_Image_Url');
					}
					elseif($main_image != "")
					{
						
						$image_ext = substr($main_image,strrpos($main_image, ".")); 
						
						$image_ext = strtolower($image_ext);						
						
						if( $image_ext != ".jpg" && $image_ext != ".jpeg"  && $image_ext != ".bmp" && $image_ext != ".gif" && $image_ext != ".png"  ) 
						{
							$arr_error[] = $this->translate->_('Product_Invalid_Product_Image_Type');
						}
						
						
						//$img_content = file_get_contents($main_image);
						list($img_width, $img_height, $img_type) = getimagesize($main_image);
						
						if($img_width < 350)
						{
							$arr_error[] = $this->translate->_('Product_Invalid_Product_Image_Width');
						}
						
						if($img_height < 350)
						{
							$arr_error[] = $this->translate->_('Product_Invalid_Product_Image_Height');
						}
						
						if($img_type <= 0  ||  $img_type > 3) 
						{
							$arr_error[] = $this->translate->_('Product_Invalid_Product_Image_Type');
						}
						
						
						$img_width = 0;
						$img_height = 0;
						$img_type = 0;
					}
					
					
					if(count($images > 0))
					{
						for($j = 0; $j < count($images); $j++)
						{
							$images[$j] = $filter->filter(trim($images[$j]));
							
							if($images[$j] != "" && !$url_validator->isValid($images[$j]))
							{
								$arr_error[] = $this->translate->_('Product_Invalid_Image_Url')." " . ($j+1) . " ".$this->translate->_('Product_For_Product')." " . ($i+1);	
							}
							elseif($images[$j] != "")
							{
							
								$image_ext = substr($images[$j],strrpos($images[$j], ".")); 
						
								$image_ext = strtolower($image_ext);						
								
								if( $image_ext != ".jpg" && $image_ext != ".jpeg"  && $image_ext != ".bmp" && $image_ext != ".gif" && $image_ext != ".png"  ) 
								{
									$arr_error[] = $this->translate->_('Product_Invalid_Image_Type')." " . ($j+1) . " ".$this->translate->_('Product_For_Product')."";	
									
								}
								
								//$img_content = file_get_contents($main_image);
								list($img_width, $img_height, $img_type) = getimagesize($images[$j]);
									 
								if($img_width < 350)
								{
									$arr_error[] = $this->translate->_('Product_Invalid_Image_Width')." " . ($j+1) . " ".$this->translate->_('Product_For_Product')."";	
								}
								
								if($img_height < 350)
								{
									$arr_error[] = $this->translate->_('Product_Invalid_Image_Height')." " . ($j+1) . " ".$this->translate->_('Product_For_Product')."";	
								}
								
								if($img_type <= 0  ||  $img_type > 3) 
								{
									$arr_error[] = $this->translate->_('Product_Invalid_Image_Type')." " . ($j+1) . " ".$this->translate->_('Product_For_Product')."";	
								}
								
								$img_width = 0;
								$img_height = 0;
								$img_type = 0;
							}
						}
					}
					
					if(count($categories > 0))
					{
						
						for($j = 0; $j < count($categories); $j++)
						{
							$categories[$j] = (int)$filter->filter(trim($categories[$j]));
							
							if($categories[$j] <= 0)
							{
								$arr_error[] = $this->translate->_('Product_Invalid_Product_Category_Id')." " . ($j+1) . " ".$this->translate->_('Product_For_Product')."";	
							}
							
							if($categories[$j] > 0)
							{
								$cat_res = $Category->GetCategoryById($categories[$j]);
								
								if($cat_res == "" || $cat_res == null )
								{
									$arr_error[] = $this->translate->_('Product_Invalid_Product_Category_Id')." " . ($j+1) . " ".$this->translate->_('Product_For_Product')."";	
								}
							}
						}
						
					}
					
					if(count($attributes > 0))
					{
						
						for ($j = 0; $j < count($attributes); $j++)
						{
							$attributes[$j]["name"] = $filter->filter(trim($attributes[$j]["name"]));
							
							if($attributes[$j]["name"] == "")
							{
								$arr_error[] = $this->translate->_('Product_Invalid_Name_For_Attr')." " . ($j+1) . " ".$this->translate->_('Product_For_Product')."";	
							}
							
							if(is_array($attributes[$j]["options"]["value"]) && isset($attributes[$j]["options"]["value"]["name"]))
							{
								$attributes[$j]["options"]["value"] = array($attributes[$j]["options"]["value"]);
							}
							
							if(count($attributes[$j]["options"]["value"]) > 0)
							{
								$prod_opt_qty = 0;
								
								for($k = 0; $k < count($attributes[$j]["options"]["value"]); $k++)
								{
									$attributes[$j]["options"]["value"][$k]["name"] = $filter->filter(trim($attributes[$j]["options"]["value"][$k]["name"]));
									
									if(isset($attributes[$j]["options"]["value"][$k]["sku"]))
									{
										$attributes[$j]["options"]["value"][$k]["sku"] = $filter->filter(trim($attributes[$j]["options"]["value"][$k]["sku"]));
									}
									else
									{
										$attributes[$j]["options"]["value"][$k]["sku"] = "";
									}
									
									
									if(isset($attributes[$j]["options"]["value"][$k]["weight"]))
									{
										$attributes[$j]["options"]["value"][$k]["weight"] = $filter->filter(trim($attributes[$j]["options"]["value"][$k]["weight"]));
									}
									else
									{
										$attributes[$j]["options"]["value"][$k]["weight"] = $weight;
									}
									
									if(isset($attributes[$j]["options"]["value"][$k]["weight_unit"]))
									{
										$attributes[$j]["options"]["value"][$k]["weight_unit"] = $filter->filter(trim($attributes[$j]["options"]["value"][$k]["weight_unit"]));
										
										if($attributes[$j]["options"]["value"][$k]["weight_unit"] == "")
										{
											$attributes[$j]["options"]["value"][$k]["weight_unit"] = $weight_unit;
										}
									}
									else
									{
										$attributes[$j]["options"]["value"][$k]["weight_unit"] = $weight_unit;
									}
									
									if(isset($attributes[$j]["options"]["value"][$k]["price"]))
									{
										$attributes[$j]["options"]["value"][$k]["price"] = $filter->filter(trim($attributes[$j]["options"]["value"][$k]["price"]));
										
									}
									else
									{
										$attributes[$j]["options"]["value"][$k]["price"] = 0;
									}
								
									if(isset($attributes[$j]["options"]["value"][$k]["available_qty"]))
									{
										$attributes[$j]["options"]["value"][$k]["available_qty"] = (int)$filter->filter(trim($attributes[$j]["options"]["value"][$k]["available_qty"]));
										$prod_opt_qty += $attributes[$j]["options"]["value"][$k]["available_qty"];
									}
									else
									{
										$attributes[$j]["options"]["value"][$k]["available_qty"] = 0;
									}
									
									if($attributes[$j]["options"]["value"][$k]["name"] == "")
									{
										$arr_error[] = $this->translate->_('Product_Invalid_Option_Value')." " . ($k+1) . " ".$this->translate->_('Product_For_Product')." " ;
									}
								}
								
								/*if($prod_opt_qty != $available_qty)
								{
									$arr_error[] = "Sum of quantity of product options is not equal to available quantity for product";
								}*/
							}
						}
					}
					
					$warning_error = array();
					
					if(count($languages) > 0)
					{
						for($l = 0; $l < count($languages); $l++)
						{
							//check if language is present or not
							if(trim($languages[$l]["code"]) == "")
							{
								$arr_error[] = $this->translate->_('Language_Code_Empty')." " . ($i+1);
							}
							
							//check if language is present in system or not
							if(trim($languages[$l]["code"]) != "")
							{
								if(!$Language->checkLanguageByCode(trim($languages[$l]["code"])))
								{
									$arr_error[] = $this->translate->_('Language_Not_Present')." " . ($i+1);
									$warning_error["warning"][] = $languages[$l]["code"] .": ".$this->translate->_('Language_Not_Present')." " . ($i+1);
								}
							}
						}
					}
					
					
					
					if(count($arr_error) > 0)
					{
						break;
					}
					else
					{
						$products[$i]['pid'] = $pid;
						$products[$i]['name'] = $name;
						$products[$i]['description'] = $description;
						$products[$i]['price'] = $price;
						$products[$i]['code'] = $code;
						$products[$i]['weight'] = $weight;
						$products[$i]['length'] = $length;
						$products[$i]['width'] = $width;
						$products[$i]['depth'] = $depth;
						$products[$i]['weight_unit'] = $weight_unit;
						$products[$i]['length_unit'] = $length_unit;
						$products[$i]['tax_rate_class_id'] = $tex_rate_class;
						$products[$i]['start_sales'] = $start_sales;
						$products[$i]['available_qty'] = $available_qty;
						$products[$i]['discount'] = $discount;
						$products[$i]['available_date'] = $available_date;
						$products[$i]['expiration_date'] = $expiration_date;
						$products[$i]['promotion_start_date'] = $promotion_start_date;
						$products[$i]['promotion_end_date'] = $promotion_end_date;
						$products[$i]['main_image'] = $main_image;
						$products[$i]['images'] = $images;
						$products[$i]['categories'] = $categories;
						$products[$i]['attributes'] = $attributes;
						$products[$i]['languages'] = $languages;
 						
					}
					
				}
				
			}			
			else
			{
				if(!isset($myparams["Products"]))
				{
					$arr_error[] = $this->translate->_('Product_Invalid_Request');
				}
				else
				{
					$arr_error[] = $this->translate->_('Product_Edit_Limit');
				}
				
			}
			
			if(count($arr_error) == 0)
			{
				$arr_msg = array();
				//add products
				for($i = 0; $i < count($products); $i++)
				{
					$data = array();
					
					
					$data["product_id"] = $products[$i]["pid"];
					$data["user_id"] = $this->user_id;
					
					if($products[$i]["name"] != "")
					{
						$data["product_name"] = $products[$i]["name"];
					}
					
					if($products[$i]["description"] != "")
					{
						$data["product_description"] = $products[$i]["description"];
					}
					
					if($products[$i]["price"] != "" && $products[$i]["price"] > 0)
					{
						$data["product_price"] = $products[$i]["price"];
					}
					
					if($products[$i]["code"] != "")
					{
						$data["product_code"] = $products[$i]["code"];
					}
					
					if($products[$i]["weight"] != "" && $products[$i]["weight"] > 0)
					{
						$data["product_weight"] = $products[$i]["weight"];
					}
					
					if($products[$i]["length"] != "" && $products[$i]["length"] > 0)
					{
						$data["length"] = $products[$i]["length"];
					}
					
					if($products[$i]["width"] != "" && $products[$i]["width"] > 0)
					{
						$data["width"] = $products[$i]["width"];
					}
					
					if($products[$i]["depth"] != "" && $products[$i]["depth"] > 0)
					{
						$data["depth"] = $products[$i]["depth"];
					}
					
					//get weight unit id
					if($products[$i]["weight_unit"] != "")
					{
						$data["weight_unit_id"] = $Weight->getWeightIdFromKey($products[$i]["weight_unit"]);
					}
					
					// get taxrate class id
					if($products[$i]["tax_rate_class_id"] != "")
					{
						$data["tax_rate_class_id"] = $products[$i]["tax_rate_class_id"];
					}
					
					
					//get length unit id
					if($products[$i]["length_unit"] != "")
					{
						$data["length_unit_id"] = $Length->getLengthIdFromKey($products[$i]["length_unit"]);
					}
					
					if($products[$i]["start_sales"] != "")
					{
						$data["start_sales"] = $products[$i]["start_sales"];
					}
					
					if($products[$i]["available_qty"] != "" && $products[$i]["available_qty"] > 0)
					{
						$data["available_qty"] = $products[$i]["available_qty"];
					}
					
					if($products[$i]["discount"] != "" && $products[$i]["discount"] >= 0)
					{
						$data["discount"] = $products[$i]["discount"];
					}

					
					if($products[$i]["available_date"] != "")
					{
						$data["available_date"] = $products[$i]["available_date"];
					}
					
					if($products[$i]["expiration_date"] != "")
					{
						$data["expiration_date"] = $products[$i]["expiration_date"];
					}
					
					if($products[$i]["promotion_start_date"] != "")
					{
						$data["promotion_start_date"] = $products[$i]["promotion_start_date"];
					}
					
					if($products[$i]["promotion_end_date"] != "")
					{
						$data["promotion_end_date"] = $products[$i]["promotion_end_date"];
					}
					
					
					
					$lang_array = array();
					//update product language for other languages
					if(count($products[$i]['languages']) > 0)
					{
						for($l = 0; $l < count($products[$i]['languages']); $l++)
						{
							$lid = $Language->getLanguageIdByCode($products[$i]['languages'][$l]["code"]);
							
							$lang_array[$lid]["product_name"] = $products[$i]['languages'][$l]["name"];
							$lang_array[$lid]["product_description"] = $products[$i]['languages'][$l]["description"];
							
						}
					}
					
					//update product 
					$Product->updateProduct($data,$lang_array);
					
					
					
					
					//insert categories for given product
					if(count($products[$i]["categories"]) > 0)
					{
						$ProdToCat = new Models_ProductCategory();
						
						//delete existing categories first
						$ProdToCat->deleteProductToCategories($products[$i]["pid"]);
						
						for($j = 0; $j < count($products[$i]["categories"]); $j++)
						{
							$ptoc_data = array('product_id'  => $products[$i]["pid"],
											   'category_id' => $products[$i]["categories"][$j]
											);
											
							$ProdToCat->insertProductToCategories($ptoc_data);
						}
						
						
					}
					
					//Insert product attributes and its details
					if(count($products[$i]["attributes"]) > 0)
					{
						$ProdOpt = new Models_ProductOptions();
						
						//delete previous options first
						$ProdOpt->deleteProductOptions($products[$i]["pid"]);
						
						for($j = 0; $j < count($products[$i]["attributes"]); $j++)
						{
							
							$opt_id = 0;
							
							$opt_data = array('product_id'  => $products[$i]["pid"],
											   'option_title' => $products[$i]["attributes"][$j]["name"]
											);
											
							$opt_id = $ProdOpt->insertProductOptions($opt_data);
							
							if($opt_id > 0 && count($products[$i]["attributes"][$j]["options"]["value"]) > 0)
							{
								
								for($k = 0; $k < count($products[$i]["attributes"][$j]["options"]["value"]); $k++)
								{
									$det_id = 0;
										
									$det_data['product_options_id'] = $opt_id;
									$det_data['option_name'] = $products[$i]["attributes"][$j]["options"]["value"][$k]["name"];
									$det_data['option_code'] = $products[$i]["attributes"][$j]["options"]["value"][$k]["sku"];
									$det_data['option_weight'] = $products[$i]["attributes"][$j]["options"]["value"][$k]["weight"];
									$det_data['option_weight_unit_id'] = $Weight->getWeightIdFromKey($products[$i]["attributes"][$j]["options"]["value"][$k]["weight_unit"]);
									$det_data['option_price'] = $products[$i]["attributes"][$j]["options"]["value"][$k]["price"];
									$det_data['option_quantity'] = $products[$i]["attributes"][$j]["options"]["value"][$k]["available_qty"];
									
									$det_id = $ProdOpt->insertProductOptionsDetail($det_data);
								}
							}
						}
					}
					
					//create folder for product to insert images
					$folder_path =  SITE_PRODUCT_IMAGES_FOLDER . "/p".$products[$i]['pid'];
					
					
					$folder_exists = is_dir($folder_path);
					
					
					
					$thumb = new Thumbnail();
					
					$ext = "";
					$imgname = "p".$this->user_id;
					$filepath = "/p".$products[$i]['pid'];
					$arr_imgname = array();
					$img_data = array();
					$filename = "";	
					$encname = "";	
					//Insert main image and sub images of product
					if($products[$i]['main_image'] != "" && $folder_exists)
					{
						chmod($folder_path,0777);
						
						//delete product main image and insert new
						$ProdImg->deleteImagesByProductId($products[$i]['pid'],1);
						
						list($img_width, $img_height, $img_type) = getimagesize($products[$i]['main_image']);
						
						if($img_type == 1)
						{
							$ext = "gif";	
						}
						
						if($img_type == 2)
						{
							$ext = "jpg";
						}
						
						if($img_type == 3)
						{
							$ext = "png";
						}
						
						$ext = substr($products[$i]['main_image'], -4);
						
						if( $ext != "jpeg" ) {
						
							$ext = substr($products[$i]['main_image'], -3);
						}
						
						$encname = md5($imgname . time());
						
						$filename = $encname . "_img." . $ext;
						
						//Image of size 350x350
						$arr_imgname[] = $folder_path . "/" . $encname . "_img.".$ext ;
						
						//Image of size 128x128
						$arr_imgname[] = $folder_path . "/" . $encname . "_th1.".$ext ;
						
						//Image of size 64x64
						$arr_imgname[] = $folder_path . "/" . $encname . "_th2.".$ext ;
						
						//Image of size 28x28
						$arr_imgname[] = $folder_path . "/" . $encname . "_th3.".$ext ;
						
						
						$img_content= file_get_contents($products[$i]['main_image']);
						
						
						
						file_put_contents($arr_imgname[0], $img_content );
						
						copy($arr_imgname[0],$arr_imgname[1]);
						copy($arr_imgname[0],$arr_imgname[2]);
						copy($arr_imgname[0],$arr_imgname[3]);
						
						$thumb->image($arr_imgname[0]);
				  		$thumb->size_fix(350,350);
						$thumb->get($arr_imgname[0]);	
						
						
						$thumb->image($arr_imgname[1]);
				  		$thumb->size_fix(128,128);
						$thumb->get($arr_imgname[1]);	
						
						$thumb->image($arr_imgname[2]);
				  		$thumb->size_fix(64,64);
						$thumb->get($arr_imgname[2]);	
						
						$thumb->image($arr_imgname[3]);
				  		$thumb->size_fix(28,28);
						$thumb->get($arr_imgname[3]);	
						
						//Insert record in database table
						$img_data = array( 'product_id'  => $products[$i]["pid"],
										   'image_name' => $filename,
										   'image_path' => $filepath,
										   'is_primary_image' => 1
											);
						
						$ProdImg->insertProductImages($img_data);
						
						
					}
					
					
					//Insert sub images of product
					if(count($products[$i]['images']) > 0 && $folder_exists)
					{
						//delete product sub images and insert new
						$ProdImg->deleteImagesByProductId($products[$i]['pid'],0);
						
						for($j = 0; $j < count($products[$i]['images']); $j++)
						{
							
							$arr_imgname = array();
							$img_content = "";
							$img_width = 0;
							$img_height = 0;
							$img_type = 0;
							$encname = "";
							
							list($img_width, $img_height, $img_type) = getimagesize($products[$i]['images'][$j]);
						
							if($img_type == 1)
							{
								$ext = "gif";	
							}
							
							if($img_type == 2)
							{
								$ext = "jpg";
							}
							
							if($img_type == 3)
							{
								$ext = "png";
							}
							
							$ext = substr($products[$i]['main_image'], -4);
						
							if( $ext != "jpeg" ) {
							
								$ext = substr($products[$i]['main_image'], -3);
							}
							
							$encname = md5($imgname . $j . time());
							
							$filename = $encname . "_img." . $ext;
							
							
							//Image of size 350x350
							$arr_imgname[] = $folder_path . "/" . $encname . "_img.".$ext ;
							
							//Image of size 128x128
							$arr_imgname[] = $folder_path . "/" . $encname . "_th1.".$ext ;
							
							//Image of size 64x64
							$arr_imgname[] = $folder_path . "/" . $encname . "_th2.".$ext ;
							
							//Image of size 28x28
							$arr_imgname[] = $folder_path . "/" . $encname . "_th3.".$ext ;
							
							
							$img_content= file_get_contents($products[$i]['images'][$j]);
							
							
							
							file_put_contents($arr_imgname[0], $img_content );
							
							copy($arr_imgname[0],$arr_imgname[1]);
							copy($arr_imgname[0],$arr_imgname[2]);
							copy($arr_imgname[0],$arr_imgname[3]);
							
							$thumb->image($arr_imgname[0]);
							$thumb->size_fix(350,350);
							$thumb->get($arr_imgname[0]);	
							
							
							$thumb->image($arr_imgname[1]);
							$thumb->size_fix(128,128);
							$thumb->get($arr_imgname[1]);	
							
							$thumb->image($arr_imgname[2]);
							$thumb->size_fix(64,64);
							$thumb->get($arr_imgname[2]);	
							
							$thumb->image($arr_imgname[3]);
							$thumb->size_fix(28,28);
							$thumb->get($arr_imgname[3]);	
							
							//Insert record in database table
							$img_data = array( 'product_id'  => $products[$i]["pid"],
											   'image_name' => $filename,
											   'image_path' => $filepath,
											   'is_primary_image' => 0
												);
							
							$ProdImg->insertProductImages($img_data);	
						
						}
					}
				}
				
				
				$this->view->result = $this->translate->_('Product_Success');	
				$this->view->message = $this->translate->_('Product_Update_Success');	
				$this->getResponse()->setHttpResponseCode(201);
				
			}
			else
			{
				
				//send error message
				$this->view->result = $this->translate->_('Product_Failure');
				if( count($warning_error) > 0 ) {
				
					$this->view->errormessage = array('error' => $warning_error);
				
				} else {
				
					$this->view->errormessage = array('error' => $arr_error);
				}
				
	        	$this->getResponse()->setHttpResponseCode(500);
			}
		}
    
	
	}

	/**
	 * Function putAction
	 *
	 * The put action handles PUT requests and receives an 'id' parameter; it
     * should update the server resource state of the resource identified by
     * the 'id' value.
	 *
	 * Date created: 2011-11-23
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function putAction()
    {
		if($this->user_id > 0)
		{       
	    	$this->view->id = $this->_getParam('id');
        	$this->view->resource = new stdClass;
        	$this->getResponse()->setHttpResponseCode(200);
		}
	}
	
	/**
	 * Function deleteAction
	 *
	 * The delete action handles DELETE requests and receives an 'id'
     * parameter; it should update the server resource state of the resource
     * identified by the 'id' value.
	 *
	 * Date created: 2011-11-23
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function deleteAction()
    {
		if($this->user_id > 0)
		{       
	    	$this->view->id = $this->_getParam('id');
        	$this->view->resource = new stdClass;
        	$this->getResponse()->setHttpResponseCode(200);
		}
	}

}
?>