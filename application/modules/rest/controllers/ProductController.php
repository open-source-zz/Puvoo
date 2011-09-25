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
 * Rest_ProductController
 *
 * Rest_ProductController extends RestCommonController.
 * It is used to handle product related api calls.
 *
 * Date created: 2011-09-06
 *
 * @category	Puvoo
 * @package 	Rest_Controllers
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
class Rest_ProductController extends RestCommonController
{
	protected $api_key = "";
	protected $user_id = 0;
	
	/**
	 * Function init
	 *
	 * This is function is used to initialize rest api
	 *
	 * Date created: 2011-09-06
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function init(){
		parent::init();
		
		//Get api key from header
		$this->api_key = $this->getRequest()->getHeader('apikey');
		
		if($this->api_key == "" || $this->api_key == null)
		{
			$this->view->message = 'Access Denied';
        	$this->getResponse()->setHttpResponseCode(401);
			
		}
		else{
		
			$userlogin = new Models_UserLogin();
			$this->user_id = $userlogin->checkApiToken($this->api_key);
		}
		
		if($this->user_id <= 0){
			$this->view->message = 'Access Denied';
        	$this->getResponse()->setHttpResponseCode(401);
		}	
		
	}
	
    /**
	 * Function optionsAction
	 *
	 * The options action handles OPTIONS requests; it should respond with
     * the HTTP methods that the server supports for specified URL.
	 *
	 * Date created: 2011-09-06
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function optionsAction()
    {
        $this->view->message = 'Resource Options';
        $this->getResponse()->setHttpResponseCode(200);
    }


	/**
	 * Function indexAction
	 *
	 * The index action handles index/list requests; it should respond with a
     * list of the requested resources.
	 *
	 * Date created: 2011-09-06
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function indexAction()
    {
        
		if($this->user_id > 0)
		{
			$this->view->resources = array('mykey' => $this->api_key);
			$this->view->message = 'Resource Index';
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
	 * Date created: 2011-09-06
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
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
	 * Date created: 2011-09-06
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
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
	 * Function getAction
	 *
	 * The post action handles POST requests; it should accept and digest a
     * POSTed resource representation and persist the resource state.
	 *
	 * Date created: 2011-09-06
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
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
			
			$myparams = $this->getRequest()->getParams();
			
			/*print "<pre>";
			print_r($myparams);
			print "</pre>";
			die;*/
			$products = array();
			$arr_error = array();
			$categories = array();
			$images = array();
			$attributes = array();
					
			
			//create objects
			$Product = new Models_Product();
			$Weight = new Models_Weight();
			$Length = new Models_Length();
			$Category = new Models_Category();
			$ProdImg = new Models_ProductImages();
			
			//variables
			$img_content = "";
			$img_width = 0;
			$img_height = 0;
			$img_type = 0;
			
			if(count($myparams["Products"]) <= 10)
			{
				$x = 0;
				for($i = 0; $i < count($myparams["Products"]["Product"]); $i++)
				{
					$prod = $myparams["Products"]["Product"][$i];
					
					
					//filter values
					$name = $filter->filter(trim($prod['name']));
					$description = 	$filter->filter(trim($prod['description']));
					$price = 	(float) $filter->filter(trim($prod['price']));
					$code = 	$filterChain2->filter(trim($prod['code']));
					$weight = 	(float) $filter->filter(trim($prod['weight']));
					$length = 	(float) $filter->filter(trim($prod['length']));
					$width = 	(float) $filter->filter(trim($prod['width']));
					$depth = 	(float) $filter->filter(trim($prod['depth']));
					$weight_unit = 	$filterChain1->filter(trim($prod['weight_unit']));
					$length_unit = 	$filterChain1->filter(trim($prod['length_unit']));
					$start_sales = 	(int) $filter->filter(trim($prod['start_sales']));
					$available_qty = 	(int) $filter->filter(trim($prod['available_qty']));
					$main_image = $filter->filter(trim($prod['main_image']));
					
					if(isset($prod["categories"]))
					{
						$categories = $prod["categories"]["id"];
					}
					
					if(isset($prod["images"]))
					{
						$images = $prod["images"]["image"];
					}
					
					if(isset($prod["attributes"]))
					{
						$attributes = $prod["attributes"];
					}
					
					//check values
					if($name == ""){
						$arr_error[] = "Invalid or empty name for product "  . ($i+1);
					}
					
					if($description == ""){
						$arr_error[] = "Invalid or empty description for product "  . ($i+1);
					}
					
					if($price == "" || $price <= 0)
					{
						$arr_error[] = "Invalid price for product " . ($i+1);
					}
					
					if($weight == "" || $weight <= 0)
					{
						$arr_error[] = "Invalid weight for product " . ($i+1);
					}
					
					if($length == "" || $length <= 0)
					{
						$arr_error[] = "Invalid length for product " . ($i+1);
					}
					
					if($width == "" || $width <= 0)
					{
						$arr_error[] = "Invalid width for product " . ($i+1);
					}
					
					if($depth == "" || $depth <= 0)
					{
						$arr_error[] = "Invalid depth for product " . ($i+1);
					}
					
					if($available_qty == "" || $available_qty <= 0)
					{
						$arr_error[] = "Invalid quantity for product " . ($i+1);
					}
					
					if($main_image == "" || !$url_validator->isValid($main_image))
					{
						$arr_error[] = "Invalid main image url for product " . ($i+1);
					}
					else{
						//$img_content = file_get_contents($main_image);
						list($img_width, $img_height, $img_type) = getimagesize($main_image);
						
						if($img_width < 300)
						{
							$arr_error[] = "Invalid main image width for product " . ($i+1);	
						}
						
						if($img_height < 300)
						{
							$arr_error[] = "Invalid main image height for product " . ($i+1);	
						}
						
						if($img_type <= 0  ||  $img_type > 3) 
						{
							$arr_error[] = "Invalid main image type for product " . ($i+1);	
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
							
							if($images[$j] == "" || !$url_validator->isValid($images[$j]))
							{
								$arr_error[] = "Invalid url for image " . ($j+1) . " for product " . ($i+1);	
							}
							else{
									//$img_content = file_get_contents($main_image);
									list($img_width, $img_height, $img_type) = getimagesize($images[$j]);
										 
									if($img_width < 300)
									{
										$arr_error[] = "Invalid width for image " . ($j+1) . " for product " . ($i+1);	
									}
									
									if($img_height < 300)
									{
										$arr_error[] = "Invalid height for image " . ($j+1) . " for product " . ($i+1);	
									}
									
									if($img_type <= 0  ||  $img_type > 3) 
									{
										$arr_error[] = "Invalid type for image " . ($j+1) . " for product " . ($i+1);	
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
								$arr_error[] = "Invalid id provided for category " . ($j+1) . " for product " . ($i+1);	
							}
							
							if($categories[$j] > 0)
							{
								$cat_res = $Category->GetCategoryById($categories[$j]);
								
								if($cat_res == "" || $cat_res == null )
								{
									$arr_error[] = "Invalid id provided for category " . ($j+1) . " for product " . ($i+1);	
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
								$arr_error[] = "Invalid name provided for attribute " . ($j+1) . " for product " . ($i+1);	
							}
							
							if(count($attributes[$j]["options"]["value"]) > 0)
							{
								
								
								for($k = 0; $k < count($attributes[$j]["options"]["value"]); $k++)
								{
									$attributes[$j]["options"]["value"][$k] = $filter->filter(trim($attributes[$j]["options"]["value"][$k]));
									
									if($attributes[$j]["options"]["value"][$k] == "")
									{
										$arr_error[] = "Invalid option value provided for attribute " . ($k+1) . " for product " . ($i+1);
									}
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
						$products[$i]['start_sales'] = $start_sales;
						$products[$i]['available_qty'] = $available_qty;
						$products[$i]['main_image'] = $main_image;
						$products[$i]['images'] = $images;
						$products[$i]['categories'] = $categories;
						$products[$i]['attributes'] = $attributes;
 						
					}
					
				}
				
			}			
			else
			{
				$arr_error[] = "You can add maximum 10 products at a time";
			}
			
			if(count($arr_error) == 0)
			{
				$arr_msg = array();
				//add products
				for($i = 0; $i < count($products); $i++)
				{
					$data = array();
					
					$arr_msg[$i]["name"] = $products[$i]["name"];
					
					$data["user_id"] = $this->user_id;
					$data["product_name"] = $products[$i]["name"];
					$data["product_description"] = $products[$i]["description"];
					$data["product_price"] = $products[$i]["price"];
					$data["product_code"] = $products[$i]["code"];
					$data["product_weight"] = $products[$i]["weight"];
					$data["length"] = $products[$i]["length"];
					$data["width"] = $products[$i]["width"];
					$data["depth"] = $products[$i]["depth"];
					
					//get weight unit id
					if($products[$i]["weight_unit"] != "")
					{
						$data["weight_unit_id"] = $Weight->getWeightIdFromKey($products[$i]["weight_unit"]);
					}
					
					//get length unit id
					if($products[$i]["length_unit"] != "")
					{
						$data["length_unit_id"] = $Length->getLengthIdFromKey($products[$i]["length_unit"]);
					}
					
					$data["start_sales"] = $products[$i]["start_sales"];
					$data["available_qty"] = $products[$i]["available_qty"];
					
					//insert product and get product id
					$arr_msg[$i]["id"] = $Product->insertProduct($data);
					
					//insert categories for given product
					if(count($products[$i]["categories"]) > 0)
					{
						$ProdToCat = new Models_ProductCategory();
						
						for($j = 0; $j < count($products[$i]["categories"]); $j++)
						{
							$ptoc_data = array('product_id'  => $arr_msg[$i]["id"],
											   'category_id' => $products[$i]["categories"][$j]
											);
											
							$ProdToCat->insertProductToCategories($ptoc_data);
						}
						
						
					}
					
					//Insert product attributes and its details
					if(count($products[$i]["attributes"]) > 0)
					{
						$ProdOpt = new Models_ProductOptions();
						
						for($j = 0; $j < count($products[$i]["attributes"]); $j++)
						{
							
							$opt_id = 0;
							
							$opt_data = array('product_id'  => $arr_msg[$i]["id"],
											   'option_title' => $products[$i]["attributes"][$j]["name"]
											);
											
							$opt_id = $ProdOpt->insertProductOptions($opt_data);
							
							if($opt_id > 0 && count($products[$i]["attributes"][$j]["options"]["value"]) > 0)
							{
								
								for($k = 0; $k < count($products[$i]["attributes"][$j]["options"]["value"]); $k++)
								{
									$det_id = 0;
								
									$det_data = array('product_options_id'  => $opt_id,
													   'option_value' => $products[$i]["attributes"][$j]["options"]["value"][$k]
													);
													
									$det_id = $ProdOpt->insertProductOptionsDetail($det_data);
								}
							}
						}
					}
					
					//create folder for product to insert images
					$folder_path =  SITE_PRODUCT_IMAGES_FOLDER . "/p".$arr_msg[$i]["id"];
					
					
					$folder_exists = createDirectory($folder_path);
					
					$thumb = new Thumbnail();
					
					$ext = "";
					$imgname = "p".$this->user_id;
					$filepath = SITE_PRODUCT_IMAGES_PATH . "/p".$arr_msg[$i]["id"];
					$arr_imgname = array();
					$img_data = array();
					$filename = "";	
					$encname = "";	
					//Insert main image and sub images of product
					if($products[$i]['main_image'] != "" && $folder_exists)
					{
						
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
						
						
						$ext = substr($products[$i]['main_image'], -3);
						
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
						$img_data = array( 'product_id'  => $arr_msg[$i]["id"],
										   'image_name' => $filename,
										   'image_path' => $filepath,
										   'is_primary_image' => 1
											);
						
						$ProdImg->insertProductImages($img_data);
						
						
					}
					
					
					//Insert sub images of product
					if(count($products[$i]['images']) > 0 && $folder_exists)
					{
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
							$img_data = array( 'product_id'  => $arr_msg[$i]["id"],
											   'image_name' => $filename,
											   'image_path' => $filepath,
											   'is_primary_image' => 0
												);
							
							$ProdImg->insertProductImages($img_data);	
						
						}
					}
				}
				
				$this->view->result = 'Success';
				$arr_products = array();
				if(count($arr_msg > 0))
				{
					for($i = 0; $i < count($arr_msg); $i++)
					{
						$arr_products["Product"][$i]["name"] = $arr_msg[$i]["name"];
						$arr_products["Product"][$i]["id"] = $arr_msg[$i]["id"];
					}
				}
				$this->view->Products = array($arr_products);
        		$this->getResponse()->setHttpResponseCode(201);
				
			}
			else
			{
				//send error message
				$this->view->result = 'Failure';
				$this->view->errormessage = array('error' => $arr_error);
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
	 * Date created: 2011-09-06
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function putAction()
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
			
			$myparams = $this->getRequest()->getParams();
			
			/*print "<pre>";
			print_r($myparams);
			print "</pre>";
			die;*/
			$products = array();
			$arr_error = array();
			$categories = array();
			$images = array();
			$attributes = array();
					
			
			//create objects
			$Product = new Models_Product();
			$Weight = new Models_Weight();
			$Length = new Models_Length();
			$Category = new Models_Category();
			$ProdImg = new Models_ProductImages();
			
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
					
			
			if(count($myparams["Products"]) <= 1)
			{
				$x = 0;
				for($i = 0; $i < count($myparams["Products"]["Product"]); $i++)
				{
					$prod = $myparams["Products"]["Product"][$i];
					
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
						$description = 	$filter->filter(trim($prod['description']));
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
					
					if(isset($prod['start_sales']))
					{
						$start_sales = 	(int) $filter->filter(trim($prod['start_sales']));
					}
					
					if(isset($prod['available_qty']))
					{
						$available_qty = 	(int) $filter->filter(trim($prod['available_qty']));
					}
					
					if(isset($prod['main_image']))
					{
						$main_image = $filter->filter(trim($prod['main_image']));
					}
					
					if(isset($prod["categories"]))
					{
						$categories = $prod["categories"]["id"];
					}
					
					if(isset($prod["images"]))
					{
						$images = $prod["images"]["image"];
					}
					
					if(isset($prod["attributes"]))
					{
						$attributes = $prod["attributes"];
					}
					
					//check values
					if($pid <= 0){
						$arr_error[] = "Invalid id for product";
					}
					
					if($pid > 0 && !$Product->checkProductForUser($pid, $this->user_id))
					{
						$arr_error[] = "Invalid id for product";
					}
					
					if($price != "" && $price <= 0)
					{
						$arr_error[] = "Invalid price for product";
					}
					
					if($weight != "" && $weight <= 0)
					{
						$arr_error[] = "Invalid weight for product";
					}
					
					if($length != "" && $length <= 0)
					{
						$arr_error[] = "Invalid length for product";
					}
					
					if($width != "" && $width <= 0)
					{
						$arr_error[] = "Invalid width for product";
					}
					
					if($depth != "" && $depth <= 0)
					{
						$arr_error[] = "Invalid depth for product";
					}
					
					if($available_qty != "" && $available_qty <= 0)
					{
						$arr_error[] = "Invalid quantity for product";
					}
					
					if($main_image != "" && !$url_validator->isValid($main_image))
					{
						$arr_error[] = "Invalid main image url for product";
					}
					elseif($main_image != "")
					{
						//$img_content = file_get_contents($main_image);
						list($img_width, $img_height, $img_type) = getimagesize($main_image);
						
						if($img_width < 300)
						{
							$arr_error[] = "Invalid main image width for product";	
						}
						
						if($img_height < 300)
						{
							$arr_error[] = "Invalid main image height for product";	
						}
						
						if($img_type <= 0  ||  $img_type > 3) 
						{
							$arr_error[] = "Invalid main image type for product";	
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
								$arr_error[] = "Invalid url for image " . ($j+1) . " for product " . ($i+1);	
							}
							elseif($images[$j] != "")
							{
								//$img_content = file_get_contents($main_image);
								list($img_width, $img_height, $img_type) = getimagesize($images[$j]);
									 
								if($img_width < 300)
								{
									$arr_error[] = "Invalid width for image " . ($j+1) . " for product";	
								}
								
								if($img_height < 300)
								{
									$arr_error[] = "Invalid height for image " . ($j+1) . " for product";	
								}
								
								if($img_type <= 0  ||  $img_type > 3) 
								{
									$arr_error[] = "Invalid type for image " . ($j+1) . " for product";	
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
								$arr_error[] = "Invalid id provided for category " . ($j+1) . " for product";	
							}
							
							if($categories[$j] > 0)
							{
								$cat_res = $Category->GetCategoryById($categories[$j]);
								
								if($cat_res == "" || $cat_res == null )
								{
									$arr_error[] = "Invalid id provided for category " . ($j+1) . " for product";	
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
								$arr_error[] = "Invalid name provided for attribute " . ($j+1) . " for product";	
							}
							
							if(count($attributes[$j]["options"]["value"]) > 0)
							{
								for($k = 0; $k < count($attributes[$j]["options"]["value"]); $k++)
								{
									$attributes[$j]["options"]["value"][$k] = $filter->filter(trim($attributes[$j]["options"]["value"][$k]));
									
									if($attributes[$j]["options"]["value"][$k] == "")
									{
										$arr_error[] = "Invalid option value provided for attribute " . ($k+1) . " for product";
									}
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
						$products[$i]['start_sales'] = $start_sales;
						$products[$i]['available_qty'] = $available_qty;
						$products[$i]['main_image'] = $main_image;
						$products[$i]['images'] = $images;
						$products[$i]['categories'] = $categories;
						$products[$i]['attributes'] = $attributes;
 						
					}
					
				}
				
			}			
			else
			{
				$arr_error[] = "You can edit maximum 1 product at a time";
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
					
					//update product 
					$Product->updateProduct($data);
					
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
								
									$det_data = array('product_options_id'  => $opt_id,
													   'option_value' => $products[$i]["attributes"][$j]["options"]["value"][$k]
													);
													
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
					$filepath = SITE_PRODUCT_IMAGES_PATH . "/p".$products[$i]['pid'];
					$arr_imgname = array();
					$img_data = array();
					$filename = "";	
					$encname = "";	
					//Insert main image and sub images of product
					if($products[$i]['main_image'] != "" && $folder_exists)
					{
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
						
						
						$ext = substr($products[$i]['main_image'], -3);
						
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
				
				$this->view->result = 'Success';
				$this->view->message = 'Product updated successfully';
				
        		$this->getResponse()->setHttpResponseCode(201);
				
			}
			else
			{
				//send error message
				$this->view->result = 'Failure';
				$this->view->errormessage = array('error' => $arr_error);
	        	$this->getResponse()->setHttpResponseCode(500);
			}
		}
    }
	
	/**
	 * Function deleteAction
	 *
	 * The delete action handles DELETE requests and receives an 'id'
     * parameter; it should update the server resource state of the resource
     * identified by the 'id' value.
	 *
	 * Date created: 2011-09-06
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return () - Return void
	 * @author Amar
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
    public function deleteAction()
    {
		if($this->user_id > 0)
		{
        	//single filter
			$filter = new Zend_Filter_StripTags();
			

			//Filter chain 1
			$filterChain1 = new Zend_Filter();

			$filterChain1->addFilter(new Zend_Filter_StripTags())
			            ->addFilter(new Zend_Filter_Alpha());

 			$myparams = $this->getRequest()->getParams();
			
			/*print "<pre>";
			print_r($myparams);
			print "</pre>";
			die;*/
			$products = array();
			$arr_error = array();
			
					
			
			//create objects
			$Product = new Models_Product();
			$Category = new Models_Category();
			$ProdImg = new Models_ProductImages();
			$ProdToCat = new Models_ProductCategory();
			$ProdOpt = new Models_ProductOptions();
			
			//variables
			$pid = 0;
			$reason = "";
			
					
			
			if(count($myparams["Products"]) <= 50)
			{
				$x = 0;
				for($i = 0; $i < count($myparams["Products"]["Product"]); $i++)
				{
					$prod = $myparams["Products"]["Product"][$i];
					
					//filter values
					if(isset($prod['pid']))
					{
						$pid = (int) $filter->filter(trim($prod['pid']));
					}
					
					if(isset($prod['ending_reason']))
					{
						$reason = $filter->filter(trim($prod['ending_reason']));
					}
					
					
					
					//check values
					if($pid <= 0){
						$arr_error[] = "Invalid id for product " . ($i + 1);
					}
					
					if($pid > 0 && !$Product->checkProductForUser($pid, $this->user_id))
					{
						$arr_error[] = "Invalid id for product " . ($i + 1);
					}
					
					if(count($arr_error) > 0)
					{
						break;
					}
					else
					{
						$products[$i]['pid'] = $pid;
						$products[$i]['ending_reason'] = $reason;
					}
					
				}
				
			}			
			else
			{
				$arr_error[] = "You can delete maximum 50 product at a time";
			}
			
			if(count($arr_error) == 0)
			{
				$arr_msg = array();
				//add products
				for($i = 0; $i < count($products); $i++)
				{
					$data = array();
					
					
					/*$data["product_id"] = $products[$i]["pid"];
					$data["user_id"] = $this->user_id;
					
					if($products[$i]["ending_reason"] != "")
					{
						$data["ending_reason"] = $products[$i]["ending_reason"];
					}*/
					
					
					
					//update product 
					$Product->DeleteProductDetail($products[$i]["pid"]);
					
					
						
					
					
				}
				
				$this->view->result = 'Success';
				$this->view->message = 'Product deleted successfully';
				
        		$this->getResponse()->setHttpResponseCode(201);
				
			}
			else
			{
				//send error message
				$this->view->result = 'Failure';
				$this->view->errormessage = array('error' => $arr_error);
	        	$this->getResponse()->setHttpResponseCode(500);
			}
		}
    }

}
?>
