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

		 $Common = new Models_Common();
		 
		 $likes = new Models_Like();

 			
 		 //$this->view->FeaturedProduct = $Product->GetFeaturedSeller();

		 $this->view->FeaturedProduct = array();

		 

		 /// Best Seller Products
		
 		 $bestSellerProd = $Product->GetBestSelllerProduct();
		 
		 //
		 foreach($bestSellerProd as $prokey => $val)
		 {
					 
			 if($val['tax_zone'] != '')
			 {		 
				$taxzone = explode(',',$val['tax_zone']);
			 }else{
				$taxzone = explode(',',$mysession->default_taxZone);
			 }
			
			 $tax_rate = $Common->TaxCalculation($taxzone,$val['tax_rate']);
  			 
			 $bestSellerProd[$prokey]['converted_price'] = round((($val['product_price'] +(($val['product_price'] * $tax_rate)/100))* $mysession->currency_value)/$val['currency_value'],2);
			 
		 }
		
		$this->view->bestSeller = $bestSellerProd;

		 

		 // Friens Like
		 
		 if(FACEBOOK_USERID != '' ) {
		
			$facebook = new Facebook(array(
	
					  'appId'  => FACEBOOK_APP_API_ID,
	
					  'secret' => FACEBOOK_APP_SECRET_KEY,
	
					  'cookie' => true,
	
				));
		
		
			$uprofile = $facebook->api('/me/friends'); 
					
			$friends = array();
			
		
			foreach( $uprofile["data"] as $key => $val )
	
			{
	
				$friends[] = $val["id"];
	
			}

		

			$frined_list = implode(",",$friends);

		
			if( $frined_list != '' ){
			
				$FrdsLikeProd = $Category->getAllFriendLikes($frined_list); 
				
				 foreach($FrdsLikeProd as $prokey => $val)
				 {
							 
					 if($val['tax_zone'] != '')
					 {		 
					 	$taxzone = explode(',',$val['tax_zone']);
					 }else{
					 	$taxzone = explode(',',$mysession->default_taxZone);
					 }
					
					 $tax_rate = $Common->TaxCalculation($taxzone,$val['tax_rate']);
					 
					 $FrdsLikeProd[$prokey]['converted_price'] = round((($val['product_price'] +(($val['product_price'] * $tax_rate)/100))* $mysession->currency_value)/$val['currency_value'],2);
					 
				 }
 				
				$this->view->frndslike = $FrdsLikeProd;
			}
		
		} else {
		
			$this->view->frndslike = array();
		
		}
			/*$FrdsLikeProd = $Product->GetFrndsLikeProduct();
			
			$this->view->frndslike = $FrdsLikeProd;*/

		 /// Facebook banner

		 $banners = $Common->GetfacebookBanner();

		

		 $this->view->bannerCnt = count($banners);

		 $this->view->banner = $banners;

     }

	  

	  

	function adduserlikeAction()

	{

		global $user,$facebook;

		
		//Disable layout

		$this->_helper->layout()->disableLayout();

		

		$master = new Models_AdminMaster();
		
		$Common = new Models_Common();

		$Definitions = new Models_LanguageDefinitions();

		$filter = new Zend_Filter_StripTags();	

		

		if(FACEBOOK_USERID != '' ) {

		

			$facebook = new Facebook(array(

				  'appId'  => FACEBOOK_APP_API_ID,

				  'secret' => FACEBOOK_APP_SECRET_KEY,

				  'cookie' => true,

			));

			 

			

			$data = $facebook->api( 

						array( 

								'method' => 'fql.query', 

								'query' => 'SELECT url FROM url_like WHERE user_id = '.FACEBOOK_USERID 

							) 

						);

			

			if( count($data) > 0 ) {

				
				$sql = "INSERT IGNORE into user_product_likes (facebook_user_id, facebook_user_email, product_id, product_url) values ";

				$counter = 0;
				$prod_ids = array();
				foreach($data as $key => $val )

				{

					$pos = strpos($val["url"], SITE_FB_URL);

										

					if($pos !== false) {

					

						$urlArray = explode("/",$val["url"]);

						$product_id = $urlArray[count($urlArray)-1];

						

						if( $product_id != '' && $product_id > 0 ) {

							$prod_ids[] = $product_id;

							$sql .= "(" . FACEBOOK_USERID . ", '".FACEBOOK_USERID."', ".$product_id.", '".$val["url"]."'),";	

							$counter++;

						}

					}

					

				}

				

				$sql = rtrim($sql,",");

				

				if($counter > 0) {

				
					$Definitions->executeQuery($sql);
 					
					$Common->UpdateProductLikeCount($prod_ids);

				}

				

			}

			

			echo "Done !!"; die;

				

		} else { 

		

			echo "Done !!"; die;

		}

	}

	

	function removeuserlikeAction()

	{

		global $user,$facebook;

		

		//Disable layout

		$this->_helper->layout()->disableLayout();

		$Common = new Models_Common();
		
		$master = new Models_AdminMaster();

		$filter = new Zend_Filter_StripTags();	

		

		$data["product_url"] = $filter->filter(trim($this->_request->getParam('likeurl'))); 	

		$data["facebook_user_email"] = $filter->filter(trim($this->_request->getParam('facebook_user_email'))); 	

		$data["facebook_user_id"] = $filter->filter(trim($this->_request->getParam('facebook_user_id'))); 	

		

		$urlArray = explode("/",$data["product_url"]);

		$data["product_id"] = $urlArray[count($urlArray)-1];

		

		$master->deleteProductLike($data);
		
		$Common->UpdateProductLikeCount($data["product_id"]);
		

		print "Done !!";

		die;

		

	}



}



?>