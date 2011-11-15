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

 * Admin_OrdersController

 *

 * Admin_OrdersController extends Admin_OrdersController. It is used to manage the orders.

 *

 * @category	Puvoo

 * @package 	Admin_Controllers

 * @author	    Yogesh 

 *  

 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)

 */  

class Admin_OrdersController extends AdminCommonController

{

 

 	/**

	 * Function init

	 * 

	 * This function in used for initialization. Add javacript file and define constant.

	 *

	 * Date created: 2011-09-21

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

		$this->view->JS_Files = array('admin/AdminCommon.js');	

	}

	

	

	/**

	 * Function indexAction

	 * 

	 * This function is used for listing all Orders and for searching Orders.

	 *

	 * Date created: 2011-09-21

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

		$this->view->site_url = SITE_URL."admin/orders";

		$this->view->edit_action = SITE_URL."admin/orders/edit";

		$this->view->view_action = SITE_URL."admin/orders/view";

		$this->view->delete_action = SITE_URL."admin/orders/delete";

		$this->view->delete_all_action = SITE_URL."admin/orders/deleteall";

		

		

		//Create Object of Product model

		$orders = new Models_Orders();

		$master = new Models_AdminMaster();

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

		
		$translate = Zend_Registry::get('Zend_Translate');
		

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

			$data["product_name"] 	= $filter->filter(trim($this->_request->getPost('product_name'))); 

			$data["user_name"] 		= $filter->filter(trim($this->_request->getPost('user_name'))); 

			$data["date_from"] 		= $filter->filter(trim($this->_request->getPost('order_date_from'))); 

			$data["date_to"] 		= $filter->filter(trim($this->_request->getPost('order_date_to'))); 

			$data["status"] 		= $filter->filter(trim($this->_request->getPost('order_status'))); 

			
			if( $data["product_name"] != '' ) {

				$order_array = $orders->getAllOrderId($data["product_name"]);

				$ids = '';

				for($i = 0; $i < count($order_array); $i++ ){

					if($i != 0 ){

						$ids .= ",".$order_array[$i]["order_id"];

					} else {

						$ids .= $order_array[$i]["order_id"];

					}

				}

				if( $ids != '' ) { 

					$where .= " AND order_id IN(".$ids.")";

				}

			}			

			if($data["user_name"] != '') {

				$where .= " AND om.shipping_user_fname LIKE '%".$data["user_name"]."%' OR om.billing_user_fname LIKE '%".$data["user_name"]."%'";

			}

			if($data["date_from"] != '' && $data["date_to"] != '' )

			{

				if($data["date_to"] < $data["date_from"]) {

					$mysession->Admin_Message = $translate->_('Err_Order_Search_Date');

				} else {

					$where .= " AND om.order_creation_date >= '".$data["date_from"]."' AND om.order_creation_date <= '".$data["date_to"]."'";
					
					
				}

			}

			if($data["status"] != '') {

				$where .= " AND om.order_status = ".$data["status"];
				
			}
			
			
			$counter = 0;
			
			foreach( $data as  $key => $val ) 
			{
			
				if( $val != '' ) {
					
					$counter++;
					
				}
			}
			
			if( $counter > 0 ) {
			
				$result = $orders->SearchOrders($where);
			
			} else {
			
				$mysession->Admin_EMessage = $translate->_('No_Search_Criteria');
				
				$result = $orders->GetAllOrders();
				
			}
			


		} elseif($is_search == "0") {

			// Clear serch option

			$page_no = 1;

			$result = $orders->GetAllOrders();

						

		} else 	{

			//Get all Categories

			$result = $orders->GetAllOrders();

			

		}		

		// Success and Error Message

		$this->view->Admin_SMessage = $mysession->Admin_SMessage;

		$this->view->Admin_EMessage = $mysession->Admin_EMessage;

		

		$mysession->Admin_SMessage = "";

		$mysession->Admin_EMessage = "";

		

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

	 * This function is used to update order status 

	 *

     * Date Created: 2011-09-21

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

		$this->view->site_url = SITE_URL."admin/orders";

		$this->view->edit_action = SITE_URL."admin/orders/edit";

		$translate = Zend_Registry::get('Zend_Translate');

		$filter = new Zend_Filter_StripTags();

		

		$orders = new Models_Orders();

		$master = new Models_AdminMaster();

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

				$data['order_status']=$filter->filter(trim($this->_request->getPost('order_status')));

				

				if($orders->updateOrder($data)) {

					$mysession->Admin_SMessage = $translate->_('Order_Update_Success');

				} else {

					$mysession->Admin_EMessage = $translate->_('Err_Update_Order');

				}

				

				$this->_redirect('/admin/orders'); 

			}  

		}

	}

	

	

	/**

     * Function viewAction

	 *

	 * This function is used to view the all details of order.

	 *

     * Date Created: 2011-09-21

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

		

		$this->view->site_url = SITE_URL."admin/orders";

		$translate = Zend_Registry::get('Zend_Translate');

		

		$orders = new Models_Orders();

		$master = new Models_AdminMaster();

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

			

			$mysession->Admin_EMessage = $translate->_('Err_View_Products');	

			$this->_redirect('/admin/orders'); 	

		}

		

	}

	

	/**

     * Function deleteAction

	 *

	 * This function is used to delete order and all its product detail.

	 *

     * Date Created: 2011-09-21

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

		

		$orders = new Models_Orders();

		$request = $this->getRequest();

		

		$filter = new Zend_Filter_StripTags();	

		$order_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	

		

		if($order_id > 0 && $order_id != "") {

			if($orders->DeleteOrder($order_id)) {

				$mysession->Admin_SMessage = $translate->_('Order_Success_Delete');

			} else {

				$mysession->Admin_EMessage = $translate->_('Err_Order_Delete');	

			}		

		} 

		$this->_redirect('/admin/orders'); 	

	}

	

	/**

     * Function deleteallAction

	 *

	 * This function is used to delete multiple orders.

	 *

     * Date Created: 2011-09-21

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

		

		$orders = new Models_Orders();

		$request = $this->getRequest();

		

		$filter = new Zend_Filter_StripTags();	

		

   		if(isset($_POST["id"])) {

		

			$order_id = $this->_request->getPost('id');

			

			if($orders->DeleteAllOrderDetail($order_id)) {

				

				$mysession->Admin_SMessage = $translate->_('Order_Success_M_Delete');	

			} else {

				$mysession->Admin_EMessage = $translate->_('Err_Order_Delete');				

			}	

			

		}	else {

		

			$mysession->Admin_EMessage = $translate->_('Order_Err_M_Delete');				

		}

		$this->_redirect('/admin/orders'); 	

	

	}

}

?>