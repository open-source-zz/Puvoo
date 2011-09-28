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



class Fb_IndexController extends FbCommonController
{
	 /**
     * Function init
	 *
	 * This function is used for initialization. Also include necessary javascript files.
	 *
     * Date Created: 2011-08-22
     *
     * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Jayesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/  
 
     public function init()
     {
         parent::init();
		 /* Initialize action controller here */
		 Zend_Loader::loadClass('Models_Category');

     }
     /**
	 * Function indexAction
	 *
	 * This function is used for displays the Home page of the site. 
	 *
     * Date Created: 2011-08-22
     *
	 * @access public
	 * @param ()  - No parameter
	 * @return (void) - Return void
	 *
     * @author Jayesh
     *  
     * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
     **/  

    public function indexAction()
     {
         // action body
		 global $db,$mysession;
		 $Category = new Models_Category();
		 $Product = new Models_Product();
		 //to get all main category
		 $this->view->Allcategory = $Category->GetMainCategory();
		 //to get selected category
		 $selectCat = $Category->GetCategory();
		 
		//for display category list on left menu 
		 foreach($selectCat as $val)
		 {
		 	$SubCatList = "";
			
		  	$SubCat = $Category->GetSubCategory($val['category_id']);
 			
			$SubCatList .= "<li class=''>";
			$SubCatList .= "<a class='sf-with-ul' href='".SITE_FB_URL."category/subcat/id/".$val['category_id']."' target='_top'>".$val['category_name']."				                            <span class='sf-sub-indicator'> »</span></a>";
			$SubCatList .= "<div><ul class='submenu'>";
				for($i=0; $i<count($SubCat); $i++)
				{
					$SubCatList .= "<li>";
					$SubCatList .= "<a href='".SITE_FB_URL."category/subcat/id/".$SubCat[$i]['category_id']."' target='_top'><img src='".IMAGES_FB_PATH."/2d30f5a834021d7887e1712062d0ed055283edc5_th.jpg' alt='' />".$SubCat[$i]['category_name']."</a>";
					$SubCatList .="</li>";
 				}
									
   			$SubCatList .="</ul></div></li>";
 			//$this->view->Category .= $SubCatList;
			
 		 }
		 
		 //$this->view->FeaturedProduct = $Product->GetFeaturedSeller();
		 $this->view->FeaturedProduct = array();
		 //print "<pre>";
		 //print_r($this->view->FeaturedProduct);die;
      }

}

?>