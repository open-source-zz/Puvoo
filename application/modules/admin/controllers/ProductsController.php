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
 * Admin_ProductController
 *
 * Admin_ProductController extends AdminCommonController. It is used to manage the products.
 *
 * @category	Puvoo
 * @package 	User_Controllers
 * @author	    Yogesh 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
class Admin_ProductsController extends AdminCommonController
{
 
 	/**
	 * Function init
	 * 
	 * This function in used for initialization. Add javacript file and define constant.
	 *
	 * Date created: 2011-09-13
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function init()
	{
		parent::init();				
		Zend_Loader::loadClass('Models_Product');
		Zend_Loader::loadClass('Models_ProductImages');
		$this->view->JS_Files = array('admin/AdminCommon.js','admin/product.js','admin/jquery.jscrollpane.min.js','admin/fileuploader.js','admin/jquery.multiselect.js');	
		define("TABLE","product_master");
		define("PRIMARY_KEY","product_id");
	}
	
	
	/**
	 * Function indexAction
	 * 
	 * This function is used for listing all Products and for searching Products.
	 *
	 * Date created: 2011-09-13
	 *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
	 * @author Yogesh
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function indexAction()
    {
		global $mysession,$arr_pagesize;
		$this->view->site_url = SITE_URL."admin/products";
		$this->view->add_action = SITE_URL."admin/products/add";
		$this->view->edit_action = SITE_URL."admin/products/edit";
		$this->view->view_action = SITE_URL."admin/products/view";
		$this->view->delete_action = SITE_URL."admin/products/delete";
		$this->view->delete_all_action = SITE_URL."admin/products/deleteall";
		
		
		//Create Object of Product model
		$product = new Models_Product();
		//Create Object of Validator
		$validator = new Zend_Validate_Float();
		
		//set current page number
		$page_no = 1;
		
		//set no. of records to display on page
		$pagesize = $mysession->pagesize;
		
		//set search param
		$is_search = "";
		
		//Initial records
		$this->view->category = $product->fetchAllRecords("category_master");
		$this->view->users = $product->fetchAllRecords("user_master");
		
		//Get Request
		$request = $this->getRequest();
		
		if($request->isPost()){
		
			$page_no = $request->getPost('page_no');
			$pagesize = $request->getPost('pagesize');
			$mysession->pagesize = $pagesize;
			$is_search = $request->getPost('is_search');
		}
		
		if($is_search == "1") {
			// Search product
			$translate = Zend_Registry::get('Zend_Translate');
			$filter = new Zend_Filter_StripTags();	
			$data['product_name']=$filter->filter(trim($this->_request->getPost('product_name'))); 	
			$data['category_id']=$filter->filter(trim($this->_request->getPost('category_id'))); 	
			$data['user_id'] = $filter->filter(trim($this->_request->getPost('user_id'))); 			
			$range = $_POST['range']; 				
			$search_error = ''; 
			$flag = 0; 
			$range_value = array();
			
			if($range[0] != '') {		
				if($validator->isValid($range[0]))	{		
					if($validator->isValid($range[1])) {
						if($range[1] > $range[0]) { 
							$flag = 1;
						} else {
							$search_error = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Search_Range_Invalid')."</h5>";
							
						}
					} else {
						$search_error = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Search_Price_Invalid')."</h5>"; 
					}
				} else {
					$search_error = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Search_Price_Invalid')."</h5>";			
				}
			}
			
			if($flag == 1) {
				$range_value = $range;
			} 
			
			$this->view->Search_Message = $search_error;
			$result = $product->SearchProducts($data,$range_value);
			
		} elseif($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $product->GetAllDistinctProducts();
						
		} else 	{
			//Get all Categories
			$result = $product->GetAllDistinctProducts();
			
		}		
		// Success Message
		$this->view->products = $product->GetAllProdCate();
		$this->view->Admin_Message = $mysession->Admin_Message;
		$mysession->Admin_Message = "";
		
		//Set Pagination
		$paginator = Zend_Paginator::factory($result);
    	$paginator->setItemCountPerPage($pagesize);
    	$paginator->setCurrentPageNumber($page_no);
		
		//Set View variables
		$this->view->pagesize = $pagesize;
		$this->view->page_no = $page_no;
		$this->view->arr_pagesize = $arr_pagesize;
		$this->view->paginator = $paginator;
		$this->view->records = $paginator->getCurrentItems();		
		
	}
	
	
	/**
     * Function editAction
	 *
	 * This function is used to update product details , images and product properties
	 *
     * Date Created: 2011-09-14
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	
	public function editAction()
	{
		
		global $mysession;
		$this->view->site_url = SITE_URL."admin/products";
		$this->view->edit_action = SITE_URL."admin/products/edit";
		$translate = Zend_Registry::get('Zend_Translate');
		$filter = new Zend_Filter_StripTags();
		
		$product = new Models_Product();
		$product_image = new Models_ProductImages();
		$request = $this->getRequest();
		
		$product_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 
		
		// Initial values
		$this->view->weight = $product->fetchAllRecords("weight_master");
		$this->view->length = $product->fetchAllRecords("length_master");
		$this->view->category = $product->fetchAllRecords("category_master");
		
		// Fetch records 
		if($product_id > 0 && $product_id != "") {
			
			$this->view->product_id = $product_id;
			$this->view->records = $product->getAllProductDetail($product_id);			
			
		} else {
			
			// On Form Submit
			if($request->isPost()){
				
				// Primary key
				$product_id = $filter->filter(trim($this->_request->getPost('product_id')));
				$edit_type = $filter->filter(trim($this->_request->getPost('product_edit_type')));
				
				// Validator 
				$float_validator = new Zend_Validate_Float();
				$number_validator = new Zend_Validate_Digits();
				
				if( $edit_type == "Detail" ) {
					
					$data['product_id'] = $product_id;
					$data['product_name']=$filter->filter(trim($this->_request->getPost('product_name')));
					$data['product_description']=$filter->filter(trim($this->_request->getPost('product_description')));
					$data['product_price']=$filter->filter(trim($this->_request->getPost('product_price')));
					$data['product_code']=$filter->filter(trim($this->_request->getPost('product_code')));
					$data['product_weight']=$filter->filter(trim($this->_request->getPost('product_weight')));
					$data['weight_unit_id']=$filter->filter(trim($this->_request->getPost('weight_unit_id')));
					$data['length']=$filter->filter(trim($this->_request->getPost('length')));
					$data['length_unit_id']=$filter->filter(trim($this->_request->getPost('length_unit_id')));
					$data['width']=$filter->filter(trim($this->_request->getPost('width')));
					$data['depth']=$filter->filter(trim($this->_request->getPost('depth')));
					$data['available_qty']=$filter->filter(trim($this->_request->getPost('available_qty')));
					$data['start_sales']=$filter->filter(trim($this->_request->getPost('start_sales')));
					
					$product_category = $filter->filter(trim($this->_request->getPost('multiselect_product_category_value')));
					
					$editErrorMessage = ''; 
					$product_primary_image = $filter->filter(trim($this->_request->getPost('product_primary_image')));
					
					if($data['product_name'] == '') {
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Name')."</h5>";		
					} else if($data['product_description'] == '') {
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Desc')."</h5>";		
					} else if($data['product_price'] == '') {
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Price')."</h5>";		
					}else if(!$float_validator->isValid($data['product_price'])) {
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Invalid_Price')."</h5>";		
					} else if($data['product_code'] == '') {
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Code')."</h5>";		
					} else if($data['product_weight'] == '') {
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Weight')."</h5>";		
					} else if(!$float_validator->isValid($data['product_weight'])) {
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Invalid_Weight')."</h5>";		
					} else if($data['weight_unit_id'] == '') {
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Weight_Unit')."</h5>";		
					} else if($data['length'] == '') {
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Length')."</h5>";		
					} else if(!$float_validator->isValid($data['length'])) {
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Invalid_Length')."</h5>";		
					} else if($data['length_unit_id'] == '') {
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Length_Unit')."</h5>";		
					} else if($data['width'] == '') {
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Width')."</h5>";		
					} else if(!$float_validator->isValid($data['width'])) {
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Invalid_Width')."</h5>";		
					} else if($data['depth'] == '') {
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Depth')."</h5>";		
					} else if(!$float_validator->isValid($data['depth'])) {
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Invalid_Depth')."</h5>";		
					} else if($data['available_qty'] == '') {
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Quantity')."</h5>";		
					} else if(!$number_validator->isValid($data['available_qty'])) {
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Invalid_Quantity')."</h5>";		
					} else if($product_primary_image == '' ) {					
						$editErrorMessage = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Product_Primary_Image')."</h5>";
					} 
					
					if($editErrorMessage == '') {
					
						if($product->updateProduct($data)) {
						
							$editErrorMessage = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Product_Update_Success')."</h5>";
						} 
						
						$product->updateProductCategory($product_id, $product_category);
						
						$data2["image_id"] = $product_primary_image;
						$data2["is_primary_image"] = 1;
						
						$product_image->updateProductImage($data2);
						
						$editErrorMessage = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Product_Update_Success')."</h5>";
					}
					
					$mysession->Admin_Message = $editErrorMessage;
					$this->_redirect('/admin/products'); 
					$this->view->product_id = $product_id;
					$this->view->records = $product->getAllProductDetail($product_id);	
					
				} 
			}  
		}
	}
	
	
	/**
     * Function viewAction
	 *
	 * This function is used to view the all details of product.
	 *
     * Date Created: 2011-09-13
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	
	public function viewAction()
	{
		global $mysession;
		
		$this->view->site_url = SITE_URL."admin/products";
		$translate = Zend_Registry::get('Zend_Translate');
		
		$product = new Models_Product();
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$product_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 
		
		if($product_id > 0 && $product_id != "") { 
			
			$this->view->product_id = $product_id;
			$this->view->product_detail = $product->getAllProductDetail($product_id);
			
		} else {
			
			$mysession->Admin_Message = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_View_Products')."</h5>";	
			$this->_redirect('/admin/products'); 	
		}
		
	}
	
	
	
	/**
     * Function deleteAction
	 *
	 * This function is used to delete product and its all information and images.
	 *
     * Date Created: 2011-09-15
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	
	public function deleteAction()
	{
		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$product = new Models_Product();
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$product_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($product_id > 0 && $product_id != "") {
			if($product->DeleteProductDetail($product_id)) {
				
				$mysession->Admin_Message = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Products_Success_Delete')."</h5>";
			} else {
				$mysession->Admin_Message = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Products_Delete')."</h5>";	
			}		
		} 
		$this->_redirect('/admin/products'); 	
	}
	
	/**
     * Function deleteallAction
	 *
	 * This function is used to delete multiple products.
	 *
     * Date Created: 2011-09-15
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	
	
	public function deleteallAction()
	{
		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$product = new Models_Product();
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		
   		if(isset($_POST["id"])) {
		
			$product_id = $this->_request->getPost('id');
			
			if($product->DeleteAllProductDetail($product_id)) {
				
				$mysession->Admin_Message = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Product_Success_M_Delete')."</h5>";	
			} else {
				$mysession->Admin_Message = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Products_Delete')."</h5>";				
			}	
			
		}	else {
		
			$mysession->Admin_Message = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Product_Err_M_Delete')."</h5>";				
		}
		$this->_redirect('/admin/products'); 	
	
	}
	
	/**
     * Function deleteimageAction
	 *
	 * This function is used to delete images of products.
	 *
     * Date Created: 2011-09-15
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	
	
	public function deleteimageAction()
	{
		global $mysession;
		$translate = Zend_Registry::get('Zend_Translate');
		
		$product_image = new Models_ProductImages();
		$filter = new Zend_Filter_StripTags();	
		
		$product_id = $filter->filter(trim($this->_request->getPost('product_id'))); 	
		$image_id = $filter->filter(trim($this->_request->getPost('image_id'))); 	
		
		if($product_id > 0 && $image_id > 0) {
		
			if($product_image->deleteSingleImagesById($product_id,$image_id)) {
			
				echo $translate->_('Product_Image_Delete_Success'); die;
			}
		
		} else {
			echo $translate->_('Err_Product_Image_Delete'); die;
		}
		
	} 
	
	/**
     * Function fillstateAction
	 *
	 * This function is used to fill the combo of the state on selection of country.
	 *
     * Date Created: 2011-09-13
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	
	
	public function fillstateAction()
	{
		$store = new Models_UserMaster();
		$translate = Zend_Registry::get('Zend_Translate');
		$state = $store->getAllStates($_POST["country_id"]);
		
		$str = '';
		$str .= '<option selected="selected" value="">';
		$str .= $translate->_('Shipping_Select_State');
		$str .='</option>';	
		if($state != NULL && $state != '')
		{		
			foreach($state as $key => $val)
			{
				$str .= '<option value="'.$val['state_name'].'">';
				$str .= $val["state_name"];
				$str .='</option>';	
			}
		} 
		echo $str; die;		
	}
	
	/**
     * Function updateoptionAction
	 *
	 * This function is used to updates the product options and thier values..
	 *
     * Date Created: 2011-09-15
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	
	
	public function updateoptionAction()
	{
		global $mysession;
		$translate = Zend_Registry::get('Zend_Translate');
		
		$product = new Models_Product();
		$filter = new Zend_Filter_StripTags();	
		
		$option_id = $filter->filter(trim($this->_request->getPost('option_id'))); 
		$option_title = $filter->filter(trim($this->_request->getPost('option_title'))); 
		$option_detail = $filter->filter(trim($this->_request->getPost('option_detail'))); 		
		
		if($product->updateProductOption($option_id,$option_title,$option_detail)) {
			
			echo $translate->_('Product_Option_Update_Success'); die;
 		}
		
	}
	
	/**
     * Function deleteoptionAction
	 *
	 * This function is used to delete the product options and thier values..
	 *
     * Date Created: 2011-09-15
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/	
	
	public function deleteoptionAction()
	{
		global $mysession;
		$translate = Zend_Registry::get('Zend_Translate');
		
		$product = new Models_Product();
		$filter = new Zend_Filter_StripTags();	
		
		$option_id = $filter->filter(trim($this->_request->getPost('option_id'))); 
		
		if($product->deleteProductOption($option_id)) {
			
			echo $translate->_('Product_Option_Delete_Success'); die;
 		}
	
	}
	
	/**
     * Function addoptionAction
	 *
	 * This function is used to add new product options and thier values..
	 *
     * Date Created: 2011-09-15
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/	
	
	public function addoptionAction()
	{
		
		global $mysession;
		$translate = Zend_Registry::get('Zend_Translate');
		
		$product = new Models_Product();
		$filter = new Zend_Filter_StripTags();	
		
		$project_id = $filter->filter(trim($this->_request->getPost('product_id'))); 
		$option_title = $filter->filter(trim($this->_request->getPost('option_title'))); 
		$option_detail = $filter->filter(trim($this->_request->getPost('option_detail'))); 	
		
		$option_id = $product->insertProductOption($project_id,$option_title,$option_detail);
		
		if($option_id > 0) {
			
			echo "///".$option_id."///".$translate->_('Product_Option_Insert_Success'); die;
 		}
	
	}
	
}
?>