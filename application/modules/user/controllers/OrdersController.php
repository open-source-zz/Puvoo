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
 * User_OrdersController
 *
 * User_OrdersController extends UserCommonController. It is used to manage the orders.
 *
 * @category	Puvoo
 * @package 	User_Controllers
 * @author	    Yogesh 
 *  
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
class User_OrdersController extends UserCommonController
{
 
 	/**
	 * Function init
	 * 
	 * This function in used for initialization. Add javacript file and define constant.
	 *
	 * Date created: 2011-09-24
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
		$this->view->JS_Files = array('user/usercommon.js');	
	}
	
	
	/**
	 * Function indexAction
	 * 
	 * This function is used for listing all Orders and for searching Orders.
	 *
	 * Date created: 2011-09-24
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
		$this->view->site_url = SITE_URL."user/orders";
		$this->view->edit_action = SITE_URL."user/orders/edit";
		$this->view->view_action = SITE_URL."user/orders/view";
		$this->view->delete_action = SITE_URL."user/orders/delete";
		$this->view->delete_all_action = SITE_URL."user/orders/deleteall";
		
		
		//Create Object of Product model
		$orders = new Models_UserOrders();
		$master = new Models_UserMaster();
		$status = new Models_Status();
		$translate = Zend_Registry::get('Zend_Translate');
		
		//set current page number
		$page_no = 1;
		
		//set no. of records to display on page
		$pagesize = $mysession->pagesize;
		
		//set search param
		$is_search = "";
		
		//Initial records
		$this->view->order_status = $status->GetAllStatus();
		
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
			$where = "";
			
			$filter = new Zend_Filter_StripTags();
			$product_name 	= $filter->filter(trim($this->_request->getPost('product_name'))); 
			$user_name 		= $filter->filter(trim($this->_request->getPost('user_name'))); 
			$date_from 		= $filter->filter(trim($this->_request->getPost('order_date_from'))); 
			$date_to 		= $filter->filter(trim($this->_request->getPost('order_date_to'))); 
			$status 		= $filter->filter(trim($this->_request->getPost('order_status'))); 
			
			if( $product_name != '' ) {
				$order_array = $orders->getAllOrderId($product_name);
				$ids = '';
				
				for($i = 0; $i < count($order_array); $i++ ){
					if($i != 0 ){
						$ids .= ",".$order_array[$i]["order_id"];
					} else {
						$ids .= $order_array[$i]["order_id"];
					}
				}
				if( $ids != '' ) { 
					$where .= " AND om.order_id IN(".$ids.")";
				}
			}			
			if($user_name != '') {
				$where .= " AND om.shipping_user_fname LIKE '%".$user_name."%' OR om.billing_user_fname LIKE '%".$user_name."%'";
			}
			if($date_from != '' && $date_to != '' )
			{
				if($date_to < $date_from) {
					$mysession->Admin_Message = $translate->_('Err_Order_Search_Date');
				} else {
					$where .= " AND om.order_creation_date >= '".$date_from."' AND om.order_creation_date <= '".$date_to."'";
				}
			}
			if($status != '') {
				$where .= " AND om.order_status = ".$status;
			}
			
			$result = $orders->SearchOrders($where);
			
		} elseif($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $orders->GetAllOrders();
						
		} else 	{
			//Get all Categories
			$result = $orders->GetAllOrders();
			
		}		
		// Success Message
		$this->view->User_SMessage = $mysession->User_SMessage;
		$this->view->User_EMessage = $mysession->User_EMessage;
		
		$mysession->User_SMessage = "";
		$mysession->User_EMessage = "";
		
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
	 * This function is used to update order's product status.
	 *
     * Date Created: 2011-09-24
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
		$this->view->site_url = SITE_URL."user/orders";
		$this->view->edit_action = SITE_URL."user/orders/edit";
		$translate = Zend_Registry::get('Zend_Translate');
		$filter = new Zend_Filter_StripTags();
		
		$orders = new Models_UserOrders();
		$master = new Models_UserMaster();
		$status = new Models_Status();
		
		$request = $this->getRequest();
		
		$order_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 
		
		// Initial values
		$this->view->order_status = $status->GetAllStatus();
		
		// Fetch records 
		if($order_id > 0 && $order_id != "") {
			
			$this->view->order_id = $order_id;
			$this->view->order_detail = $orders->getOrderById($order_id);
			$this->view->order_product_detail = $orders->getOrderProductDetail($order_id);	
			
		} else {
			
			// On Form Submit
			if($request->isPost()){
				
				// Primary key
				$order_id = $filter->filter(trim($this->_request->getPost('order_id')));
					
				$data['order_id'] = $order_id;
				$data['order_status']= $this->_request->getPost('order_status');
									
				if($orders->updateOrder($data)) {
					$mysession->User_SMessage = $translate->_('Order_Update_Success');
				} else {
					$mysession->User_EMessage = $translate->_('Err_Update_Order');
				}
				
				$this->_redirect('/user/orders'); 
			}  
		}
	}
	
	
	/**
     * Function viewAction
	 *
	 * This function is used to view the all details of order.
	 *
     * Date Created: 2011-09-24
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
		
		$this->view->site_url = SITE_URL."user/orders";
		$translate = Zend_Registry::get('Zend_Translate');
		
		$orders = new Models_UserOrders();
		$master = new Models_UserMaster();
		$status = new Models_Status();
		
		$request = $this->getRequest();
		
		$this->view->order_status = $status->GetAllStatus();
		
		$filter = new Zend_Filter_StripTags();	
		$order_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 
		
		if($order_id > 0 && $order_id != "") { 
			
			$this->view->order_id = $order_id;
			$this->view->order_detail = $orders->getOrderById($order_id);
			$this->view->order_product_detail = $orders->getOrderProductDetail($order_id);
			
		} else {
			
			$mysession->User_EMessage = $translate->_('Err_View_Products');	
			$this->_redirect('/user/orders'); 	
		}
		
	}
	
///////////////////////////////////  ORDER DELETE OPTIONS   //////////////////////////////////

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
	
	/*public function deleteAction()
	{
		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$orders = new Models_UserOrders();
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$order_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($order_id > 0 && $order_id != "") {
			if($orders->DeleteOrder($order_id)) {
				$mysession->User_Message = $translate->_('Order_Success_Delete');
			} else {
				$mysession->User_Message = $translate->_('Err_Order_Delete');	
			}		
		} 
		$this->_redirect('/user/orders'); 	
	}*/
	
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
	
	
	/*public function deleteallAction()
	{
		global $mysession;
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$orders = new Models_UserOrders();
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		
   		if(isset($_POST["id"])) {
		
			$order_id = $this->_request->getPost('id');
			
			if($orders->DeleteAllOrderDetail($order_id)) {
				
				$mysession->User_Message = "<h5 style='color:#389834;margin-bottom:0px;'>".$translate->_('Order_Success_M_Delete')."</h5>";	
			} else {
				$mysession->User_Message = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Err_Order_Delete')."</h5>";				
			}	
			
		}	else {
		
			$mysession->User_Message = "<h5 style='color:#FF0000;margin-bottom:0px;'>".$translate->_('Order_Err_M_Delete')."</h5>";				
		}
		$this->_redirect('/user/orders'); 	
	
	}*/
}
?>