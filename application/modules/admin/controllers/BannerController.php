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
 * Admin banner Controller.
 *
 * Admin_bannerController  extends AdminCommonController. 
 * It controls banner on admin section
 *
 * Date Created: 2011-10-07
 *
 * @banner	Puvoo
 * @package 	Admin_Controllers
 * @author      Yogesh
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 **/  
 class Admin_BannerController extends AdminCommonController
{

	/**
     * Function init
	 *
	 * This function is used for initialization. 
	 * You can also include necessary javascript files from here.
	 *
     * Date Created: 2011-10-07
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
	 
    function init()
    {
        parent::init();
        $this->view->JS_Files = array('admin/AdminCommon.js');	
		
    }
	
    /**
     * Function indexAction
	 *
	 * This function is used for initialization. Also include necessary javascript files.
	 *
     * Date Created: 2011-08-20
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   function indexAction() 
   {
        global $mysession,$arr_pagesize;
		$this->view->site_url = SITE_URL."admin/banner";
		$this->view->add_action = SITE_URL."admin/banner/add";
		$this->view->edit_action = SITE_URL."admin/banner/edit";
		$this->view->delete_action = SITE_URL."admin/banner/delete";
		$this->view->delete_all_action = SITE_URL."admin/banner/deleteall";
		
		
		//Create Object of banner model
		$banner = new Models_Banner();
		
		//set current page number
		$page_no = 1;
		
		//set no. of records to display on page
		$pagesize = $mysession->pagesize;
		
		//set search param
		$is_search = "";
		
		//Get Request
		$request = $this->getRequest();
		
		if($request->isPost()){
		
			$page_no = $request->getPost('page_no');
			$pagesize = $request->getPost('pagesize');
			$mysession->pagesize = $pagesize;
			$is_search = $request->getPost('is_search');
		}
		
		if($is_search == "0") {
			// Clear serch option
			$page_no = 1;
			$result = $banner->GetAllBanner();
						
		} else 	{
			//Get all banner
			$result = $banner->GetAllBanner();
			
		}	
			
		// Success Message
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
     * Function addAction
	 *
	 * This function is used to add banner
	 *
     * Date Created: 2011-10-07
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
   public function addAction()
   {
   		global $mysession;
		$this->view->site_url = SITE_URL."admin/banner";
		$this->view->add_action = SITE_URL."admin/banner/add";		
		
		$banner = new Models_Banner();
		$home = new Models_AdminMaster();
		
		$request = $this->getRequest();
		
		if($request->isPost()){
			
			$translate = Zend_Registry::get('Zend_Translate');
			
			$filter = new Zend_Filter_StripTags();	
			$url_validator = new UrlValidator();
			
			$data['banner_image']=$_FILES["banner_image"]["name"]; 	
			$data['banner_link']=$filter->filter(trim($this->_request->getPost('banner_link'))); 	
			$data['is_active']=$filter->filter(trim($this->_request->getPost('is_active'))); 	
			
			$addErrorMessage = array();
			if($_FILES["banner_image"]["name"] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Banner_Image');
			} else {
				$UploadImageErr = $this->uploadImage($_FILES["banner_image"]);
				if($UploadImageErr === TRUE) { } else {
					$addErrorMessage[] = $UploadImageErr;
				}
			}			
			if($data['banner_link'] == "") {
				$addErrorMessage[] = $translate->_('Err_Banner_Link');			
			} else if(!$url_validator->isValid($data['banner_link'])) {
				$addErrorMessage[] = $translate->_('Err_Banner_Invalid_Link');			
			}
			if( $data['is_active'] == "" ) {
				$addErrorMessage[] = $translate->_('Err_Banner_Status');			
			}
			
			$this->view->data = $data;
			
			if( count($addErrorMessage) == 0 || $addErrorMessage == '' ){
				if($banner->insertBanner($data)) {
					$mysession->Admin_SMessage = $translate->_('Success_Add_Banner');
					$this->_redirect('/admin/banner'); 	
				} else {
					$addErrorMessage[] = $translate->_('Err_Add_Banner');	
				}
			} 
			
			
			$this->view->addErrorMessage = $addErrorMessage;
		}
   }
   
   
   /**
     * Function editAction
	 *
	 * This function is used to update banner data.
	 *
     * Date Created: 2011-10-07
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
		
		$translate = Zend_Registry::get('Zend_Translate');
		
		$banner = new Models_Banner();
   		$home = new Models_AdminMaster();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$facebook_banner_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($facebook_banner_id > 0 && $facebook_banner_id != "") {
			$this->view->records = $banner->GetBannerById($facebook_banner_id);	
			$this->view->facebook_banner_id =  $facebook_banner_id;	
			
		} else {
			
			if($request->isPost()){
				
				$url_validator = new UrlValidator();
				
				$data["facebook_banner_id"] = $filter->filter(trim($this->_request->getPost('facebook_banner_id'))); 	
				$data['banner_link']=$filter->filter(trim($this->_request->getPost('banner_link'))); 	
				$data['is_active']=$filter->filter(trim($this->_request->getPost('is_active'))); 	
				
				$editErrorMessage = array();
				if($_FILES["banner_image"]["name"] != "" ) 
				{
					$data['banner_image']=$_FILES["banner_image"]["name"]; 	
					$UploadImageErr = $this->uploadImage($_FILES["banner_image"]);
					if($UploadImageErr === TRUE) { } else {
						$editErrorMessage[] = $UploadImageErr;
					}
				}			
				if($data['banner_link'] == "") {
					$editErrorMessage[] = $translate->_('Err_Banner_Link');			
				} else if(!$url_validator->isValid($data['banner_link'])) {
					$editErrorMessage[] = $translate->_('Err_Banner_Invalid_Link');			
				}
				if( $data['is_active'] == "" ) {
					$editErrorMessage[] = $translate->_('Err_Banner_Status');			
				}
				
				if( count($editErrorMessage) == 0 || $editErrorMessage == ''){	
									
					$where = "facebook_banner_id = ".$data["facebook_banner_id"];
					if($banner->updateBanner($data,$where)) {
						$mysession->Admin_SMessage = $translate->_('Success_Edit_Banner');
						$this->_redirect('/admin/banner'); 	
					} else {
						$editErrorMessage[] = $translate->_('Err_Edit_Banner');
					}
				} 
				$data['banner_image'] = $filter->filter(trim($this->_request->getPost('upload_image_name'))); 	
				$this->view->records = $data;	
				$this->view->facebook_banner_id =  $data["facebook_banner_id"];	
				$this->view->editErrorMessage = $editErrorMessage;
				
			} else {
			
				$this->_redirect("/admin/banner");
			}
		
		}
		
   }
   
   /**
     * Function deleteAction
	 *
	 * This function is used to delete the banner
	 *
     * Date Created: 2011-10-07
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
		
		$banner = new Models_Banner();
		
		$request = $this->getRequest();
		
		$filter = new Zend_Filter_StripTags();	
		$banner_unit_id = $filter->filter(trim($this->_request->getPost('hidden_primary_id'))); 	
		
		if($banner_unit_id > 0 && $banner_unit_id != "") {
			if($banner->deleteBanner($banner_unit_id)) {
				$mysession->Admin_SMessage = $translate->_('Success_Delete_Banner');
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Banner'); 
			}		
		} 
		$this->_redirect("/admin/banner");		
   }  
   
   
   /**
     * Function deleteallAction
	 *
	 * This function is used to delete all selected banner.
	 *
     * Date Created: 2011-10-07
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
		$banner = new Models_Banner();
		$request = $this->getRequest();
		$filter = new Zend_Filter_StripTags();	
		
   		if(isset($_POST["id"])) {
		
			$banner_unit_ids = $this->_request->getPost('id'); 
			$ids = implode($banner_unit_ids,",");
			if($banner->deletemultipleBanner($ids)) {
				$mysession->Admin_SMessage = $translate->_('Success_M_Delete_Banner');	
			} else {
				$mysession->Admin_EMessage = $translate->_('Err_Delete_Banner');
			}	
			
		}	else {
		
			$mysession->Admin_EMessage = $translate->_('Err_M_Delete_Banner');				
		}
		$this->_redirect("/admin/banner");	
   }
   
   /**
     * Function uploadImage
	 *
	 * This function is used to upload the image banner.
	 *
     * Date Created: 2011-10-07
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Yogesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/
   
	public function uploadImage($file)
	{
		$translate = Zend_Registry::get('Zend_Translate');
		define ("MAX_SIZE","10000");
		$errors=0;
		
		$image =$file["name"];
		$uploadedfile = $file['tmp_name'];
	
		if ($image) 
		{
			$filename = stripslashes($file['name']);
			$extension =  explode("/",$file['type']);
			$extension = $extension[1] ;
			$extension = strtolower($extension);
			
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
				return $translate->_('Err_Upload_Image_Ext');	
				$errors=1;
			} else 	{
				
				$size=filesize($file['tmp_name']);
				
				if ($size > MAX_SIZE*1024)
				{
				 	return $translate->_('Err_Upload_Image_Size_Limit');	
				 	$errors=1;
				}
	 
				if($extension=="jpg" || $extension=="jpeg" )
				{
					$uploadedfile = $file['tmp_name'];
					$src = imagecreatefromjpeg($uploadedfile);
				}
				else if($extension=="png")
				{
					$uploadedfile = $file['tmp_name'];
					$src = imagecreatefrompng($uploadedfile);
				}
				else 
				{
					$src = imagecreatefromgif($uploadedfile);
				}
		 
				list($width,$height)=getimagesize($uploadedfile);
				
				$newwidth=547;
				$newheight= 264;
				$tmp=imagecreatetruecolor($newwidth,$newheight);
								
				imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
				
				$filename = SITE_BANNER_IMAGES_FOLDER."/". $file['name'];
				
				imagejpeg($tmp,$filename,100);
	
				imagedestroy($src);
				imagedestroy($tmp);
			}
		}
	
		//If no errors registred, print the success message
		 if(!$errors) 	 {
			return true;
		 }
	}
   
}
?>