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
 * Admin_ImageController
 *
 * Admin_ImageController extends AdminCommonController.
 * It is used to handle product related api calls.
 *
 * Date created: 2011-09-06
 *
 * @category	Puvoo
 * @package 	Admin_Controllers
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
class Admin_ImageController extends AdminCommonController
{
	
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
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	public function init(){
		parent::init();
		Zend_Loader::loadClass('Thumbnail');
		Zend_Loader::loadClass('Models_ProductImages');
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
	 *  
	 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
	 **/
	 
    public function indexAction()
    {
		
			$allowedExtensions = array();
			$sizeLimit = 2097152;		
			$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
			$folder_path =  SITE_PRODUCT_IMAGES_FOLDER . "/p".$_GET['id'];
			$folder_exists = is_dir($folder_path);
			if(!$folder_exists) {
				createDirectory($folder_path);
			}
			
			chmod($folder_path,0777);
			
			$thumb = new Thumbnail();
			$ProdImg = new Models_ProductImages();
			$result = $uploader->handleUpload($folder_path."/");
			
			
			$ext = "";
			//$imgname = "p".$this->user_id;
			$filepath = "/p".$_GET['id'];
			$arr_imgname = array();
			$img_data = array();
			$filename = "";	
								
			$arr_imgname = array();
			$img_content = "";
			$img_width = 0;
			$img_height = 0;
			$img_type = 0;
			$encname = $result["image_name"];			
					
			list($img_width, $img_height, $img_type) = getimagesize($result["filename"]);
				
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
			
			$filename = $encname . "_img." . $ext;
			
			//Image of size 350x350
			$arr_imgname[] = $result["filename"];
			
			//Image of size 128x128
			$arr_imgname[] = $folder_path . "/" . $encname . "_th1.".$ext ;
			
			//Image of size 64x64
			$arr_imgname[] = $folder_path . "/" . $encname . "_th2.".$ext ;
			
			//Image of size 28x28
			$arr_imgname[] = $folder_path . "/" . $encname . "_th3.".$ext ;
					
					
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
			
			$record = $ProdImg->selectProductImages($_GET["id"]);
			$is_primary_image = 0;
			if( count($record) > 0 ) {  $is_primary_image = 0; } else { $is_primary_image = 1; } 
			
			$img_data = array( 'product_id'  => $_GET["id"],
							   'image_name' => $filename,
							   'image_path' => $filepath,
							   'is_primary_image' => $is_primary_image
								);
			
			$id = $ProdImg->insertProductImages($img_data);	
			
			
			$success = array('success'=>true,'id'=> $id,'is_primary_image' => $is_primary_image, 'filename'=>SITE_PRODUCT_IMAGES_PATH.$filepath.'/'.$encname.'_th1.'.$ext);
			
			echo htmlspecialchars(json_encode($success), ENT_NOQUOTES);
			die;
			
		}
}


 
/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
	 
	 
    function save($path) { 
   
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        
        if ($realSize != $this->getSize()){            
            return false;
        }
        
        $target = fopen($path, "w");        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        
        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
		$translate = Zend_Registry::get('Zend_Translate');
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception($translate->_('Err_Image_Upload_Length'));
        }      
    }   
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {  
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit =   2097152;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 2097152){        
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        
        $this->checkServerSettings();       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false; 
        }
    }
    
    private function checkServerSettings(){        
		$translate = Zend_Registry::get('Zend_Translate');
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . ' MB';             
            die("{'".$translate->_('Err_Image_Upload_Error')."':'".$translate->_('Err_Image_Upload_Max_Size')." $size'}");    
        }        
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
		$translate = Zend_Registry::get('Zend_Translate');
        if (!is_writable($uploadDirectory)){
            return array($translate->_('Err_Image_Upload_Error') => $translate->_('Err_Image_Upload_Server') );
        }
        
        if (!$this->file){
            return array($translate->_('Err_Image_Upload_Error') =>$translate->_('Err_Image_Upload_File'));
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0) {
            return array($translate->_('Err_Image_Upload_Error') => $translate->_('Err_Image_Upload_File_Empty'));
        }
        
        if ($size > $this->sizeLimit) {
            return array($translate->_('Err_Image_Upload_Error') => $translate->_('Err_Image_Upload_Large_File'));
        }
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'] ;
		//$filename."_". //$filename."_".
		$filename = md5(microtime());
		
		 $ext = $pathinfo['extension'];
		 
		 
		global $mysession;
	

        //$filename = md5(uniqid());
       

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array($translate->_('Err_Image_Upload_Error') => $translate->_('Err_Image_Upload_Invalid_Ext_File'). $these . '.');
        }
        
        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }
        
		$mysession->FileName=($filename.'.'.$ext);
			
        if ($this->file->save($uploadDirectory . $filename . '_img.' . $ext)){
            return array('success'=>true,'image_name' => $filename,'image_path'=> $filename . '_img.' . $ext,'filename'=>$uploadDirectory.$filename.'_img.'. $ext);
        } else {
            return array($translate->_('Err_Image_Upload_Error')=> $translate->_('Err_Image_Upload_Cancel'));
        }
        
    }    
}


?>