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
 * Class Thumbnail
 *
 * Class Thumbnail contains methods to generate image thumbnail on site.
 *
 * Date created: 2011-09-13
 *
 * @category	Puvoo
 * @package 	Models
 * @author	    Amar 
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */  
 

class Thumbnail
{
	var $img;
    var $gd_version;

	function thumbnail()
	{
    	// Check the gd version
    	$this->gd_version = $this->check_gd();
    }

   	function show()
    {
    	// Create thumb and show the images
    	$this->create(true);
    }

	function get($save="")
	{
    	// Create thumb and return the file name
    	return $this->create(false,$save);
	}

	function get1()
	{
    	// Create thumb and return the file name
    	return $this->create1(false);
	}

	function get2()
	{
    	// Create thumb and return the file name
    	return $this->create2(false);
	}


	function save($save="")
	{
		//Save thumb
		if (empty($save)) $save=strtolower("./". $this->img["dirname"]. "/thumb_". $this->img["basename"]);

        // Create the thumb and save into file
        return $this->create($dispay=false, $save);
	}

	function check_gd()
    {

	    $gd_version=1;

	    ob_start();

	    phpinfo(8);
	    $phpinfo=ob_get_contents();

	    ob_end_clean();

	    $phpinfo=strip_tags($phpinfo);
	    $phpinfo=stristr($phpinfo,"gd version");
	    $phpinfo=stristr($phpinfo,"version");

	    preg_match('/\d/',$phpinfo,$gd);

	    if ($gd[0]=='2'){$gd_version=2;}

	    return $gd_version;
	}

    function image($imgfile)
    {
        // Check for file existance
	    if (!file_exists($imgfile))
        {
	        $this->img["error"] = "The file $imgfile does not exist";
	        // echo $this->img["error"];
            return;
        }

        //detect image extension, name and dirname
		$this->img=pathinfo($imgfile);

        switch(strtoupper($this->img["extension"]))
        {
			// JPG, JPEG
        	case "JPG":
			case "JPEG":
	            $this->img["extension"]="JPEG";
	            $this->img["src"] = @ImageCreateFromJPEG ($imgfile);
				break;

			//PNG
			case "PNG":
	            $this->img["extension"]="PNG";
	            $this->img["src"] = @ImageCreateFromPNG($imgfile);
				break;

			//GIF
			case "GIF":
	            $this->img["extension"]="GIF";
	            $this->img["src"] = @ImageCreateFromGIF($imgfile);
				break;

			//WBMP
			case "WBMP":
	            $this->img["extension"]="WBMP";
	            @$this->img["src"] = ImageCreateFromWBMP($imgfile);
				break;

			//DEFAULT
            default:
                $this->img["error"] = "Not Supported File !!";
	            // echo $this->img["error"];
	            return;
        }

		// Height and width of image
		@$this->img["width"] = ImageSX($this->img["src"]);
		@$this->img["height"] = ImageSY($this->img["src"]);

		//default quality jpeg
		$this->img["quality"]=75;
	}

    function src_width()
    {
    	return $this->img['width'];
    }

    function src_height()
    {
    	return $this->img['height'];
    }

	function size_height($size=100)
	{
		//height
    	$this->img["height_thumb"]=$size;
    	@$this->img["width_thumb"] = ($this->img["height_thumb"]/$this->img["height"])*$this->img["width"];
	}

	function size_width($size=100)
	{
		//width
		$this->img["width_thumb"]=$size;
    	@$this->img["height_thumb"] = ($this->img["width_thumb"]/$this->img["width"])*$this->img["height"];
	}

	function size_auto($size=100)
	{
		//size
		if ($this->img["width"]>=$this->img["height"]) {
    		$this->img["width_thumb"]=$size;
    		@$this->img["height_thumb"] = ($this->img["width_thumb"]/$this->img["width"])*$this->img["height"];
		} else {
	    	$this->img["height_thumb"]=$size;
    		@$this->img["width_thumb"] = ($this->img["height_thumb"]/$this->img["height"])*$this->img["width"];
 		}
	}

	function size_fix($width, $height)
	{
		$this->img["width_thumb"]	=	$width;
    	$this->img["height_thumb"]	=	$height;
	}

	function jpeg_quality($quality=75)
	{
		//jpeg quality
		$this->img["quality"]=$quality;
	}

	function create($dispay=true, $save="")
	{
    	if(isset($this->img["error"]))	return;
		//Show thumb
        if($dispay)
			@header("Content-Type: image/".$this->img["extension"]);

   		//Save thumb
    	if($save == "")
	        $tmpimg = $this->img["dirname"]. "/thumb_". $this->img["basename"];
        else
			$tmpimg = $save;
			
        // Create destinatin image and 
		// Copies a rectangular portion of src image to des image and smoothly interpolating pixel values 
		if($this->gd_version == 2)
		{
			$this->img["des"] = ImageCreateTrueColor($this->img["width_thumb"],$this->img["height_thumb"]);
			@ImageCopyResampled ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["width_thumb"], $this->img["height_thumb"], $this->img["width"], $this->img["height"]);
		}
        else
		{
			$this->img["des"] = ImageCreate($this->img["width_thumb"],$this->img["height_thumb"]);
			@ImageCopyResized ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["width_thumb"], $this->img["height_thumb"], $this->img["width"], $this->img["height"]);
		}

        // Display the image directly in browser
        if($dispay)
        {
	        switch(strtoupper($this->img["extension"]))
	        {
	            // JPG, JPEG
	            case "JPG":
	            case "JPEG":
	                imageJPEG($this->img["des"],"",$this->img["quality"]);
	                break;

	            //PNG
	            case "PNG":
	                imagePNG($this->img["des"]);
	                break;

	            //GIF
	            case "GIF":
	                imageGIF($this->img["des"]);
	                break;

	            //WBMP
	            case "WBMP":
	                imageWBMP($this->img["des"]);
	                break;

	            //DEFAULT
	            default:
	                $this->img["error"] = "Not Supported File !!";
	                // echo $this->img["error"];
	                return;
	        }
        }
		// Save the image into file
        else
        {
	        switch(strtoupper($this->img["extension"]))
	        {
	            // JPG, JPEG
	            case "JPG":
	            case "JPEG":
	                imageJPEG($this->img["des"], $tmpimg, $this->img["quality"]);
	                break;

	            //PNG
	            case "PNG":
	                imagePNG($this->img["des"], $tmpimg);
	                break;

	            //GIF
	            case "GIF":
	                imageGIF($this->img["des"], $tmpimg);
	                break;

	            //WBMP
	            case "WBMP":
	                imageWBMP($this->img["des"], $tmpimg);
	                break;

	            //DEFAULT
	            default:
	                $this->img["error"] = "Not Supported File !!";
	                // echo $this->img["error"];
	                return;
	        }

            return $tmpimg;
        }
	}

//thumb 1	
	function create1($dispay=true, $save="")
	{
    	if(isset($this->img["error"]))	return;
		//Show thumb
        if($dispay)
			@header("Content-Type: image/".$this->img["extension"]);

   		//Save thumb
    	if($save == "")
	        $tmpimg = $this->img["dirname"]. "/big_thumb_". $this->img["basename"];
        else
			$tmpimg = $save. ".". strtolower($this->img["extension"]);
			
        // Create destinatin image and 
		// Copies a rectangular portion of src image to des image and smoothly interpolating pixel values 
		if($this->gd_version == 2)
		{
			$this->img["des"] = ImageCreateTrueColor($this->img["width_thumb"],$this->img["height_thumb"]);
			@ImageCopyResampled ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["width_thumb"], $this->img["height_thumb"], $this->img["width"], $this->img["height"]);
		}
        else
		{
			$this->img["des"] = ImageCreate($this->img["width_thumb"],$this->img["height_thumb"]);
			@ImageCopyResized ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["width_thumb"], $this->img["height_thumb"], $this->img["width"], $this->img["height"]);
		}

        // Display the image directly in browser
        if($dispay)
        {
	        switch(strtoupper($this->img["extension"]))
	        {
	            // JPG, JPEG
	            case "JPG":
	            case "JPEG":
	                imageJPEG($this->img["des"],"",$this->img["quality"]);
	                break;

	            //PNG
	            case "PNG":
	                imagePNG($this->img["des"]);
	                break;

	            //GIF
	            case "GIF":
	                imageGIF($this->img["des"]);
	                break;

	            //WBMP
	            case "WBMP":
	                imageWBMP($this->img["des"]);
	                break;

	            //DEFAULT
	            default:
	                $this->img["error"] = "Not Supported File !!";
	                // echo $this->img["error"];
	                return;
	        }
        }
		// Save the image into file
        else
        {
	        switch(strtoupper($this->img["extension"]))
	        {
	            // JPG, JPEG
	            case "JPG":
	            case "JPEG":
	                imageJPEG($this->img["des"], $tmpimg, $this->img["quality"]);
	                break;

	            //PNG
	            case "PNG":
	                imagePNG($this->img["des"], $tmpimg);
	                break;

	            //GIF
	            case "GIF":
	                imageGIF($this->img["des"], $tmpimg);
	                break;

	            //WBMP
	            case "WBMP":
	                imageWBMP($this->img["des"], $tmpimg);
	                break;

	            //DEFAULT
	            default:
	                $this->img["error"] = "Not Supported File !!";
	                // echo $this->img["error"];
	                return;
	        }

            return $tmpimg;
        }
	}
	
//thumb 2	
	function create2($dispay=true, $save="")
	{
    	if(isset($this->img["error"]))	return;
		//Show thumb
        if($dispay)
			@header("Content-Type: image/".$this->img["extension"]);

   		//Save thumb
    	if($save == "")
	        $tmpimg = $this->img["dirname"]. "/medium_thumb_". $this->img["basename"];
        else
			$tmpimg = $save. ".". strtolower($this->img["extension"]);
			
        // Create destinatin image and 
		// Copies a rectangular portion of src image to des image and smoothly interpolating pixel values 
		if($this->gd_version == 2)
		{
			$this->img["des"] = ImageCreateTrueColor($this->img["width_thumb"],$this->img["height_thumb"]);
			@ImageCopyResampled ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["width_thumb"], $this->img["height_thumb"], $this->img["width"], $this->img["height"]);
		}
        else
		{
			$this->img["des"] = ImageCreate($this->img["width_thumb"],$this->img["height_thumb"]);
			@ImageCopyResized ($this->img["des"], $this->img["src"], 0, 0, 0, 0, $this->img["width_thumb"], $this->img["height_thumb"], $this->img["width"], $this->img["height"]);
		}

        // Display the image directly in browser
        if($dispay)
        {
	        switch(strtoupper($this->img["extension"]))
	        {
	            // JPG, JPEG
	            case "JPG":
	            case "JPEG":
	                imageJPEG($this->img["des"],"",$this->img["quality"]);
	                break;

	            //PNG
	            case "PNG":
	                imagePNG($this->img["des"]);
	                break;

	            //GIF
	            case "GIF":
	                imageGIF($this->img["des"]);
	                break;

	            //WBMP
	            case "WBMP":
	                imageWBMP($this->img["des"]);
	                break;

	            //DEFAULT
	            default:
	                $this->img["error"] = "Not Supported File !!";
	                // echo $this->img["error"];
	                return;
	        }
        }
		// Save the image into file
        else
        {
	        switch(strtoupper($this->img["extension"]))
	        {
	            // JPG, JPEG
	            case "JPG":
	            case "JPEG":
	                imageJPEG($this->img["des"], $tmpimg, $this->img["quality"]);
	                break;

	            //PNG
	            case "PNG":
	                imagePNG($this->img["des"], $tmpimg);
	                break;

	            //GIF
	            case "GIF":
	                imageGIF($this->img["des"], $tmpimg);
	                break;

	            //WBMP
	            case "WBMP":
	                imageWBMP($this->img["des"], $tmpimg);
	                break;

	            //DEFAULT
	            default:
	                $this->img["error"] = "Not Supported File !!";
	                // echo $this->img["error"];
	                return;
	        }

            return $tmpimg;
        }
	}
}
?>
