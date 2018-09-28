<?php

function create_thumb_relative($fullpath, $destinationpath, $setwidth, $setheight)
{
    // ------ Create Thumbnail -------
    $CI =& get_instance();
    $CI->load->library('image_lib');
    $CI->image_lib->clear();

    // create image thumbnail
    list($width, $height, $type, $attr) = getimagesize($fullpath);
    
    // landscape
    $config['image_library']  = 'gd2';
    $config['source_image']   = $fullpath;
    $config['create_thumb']   = false;
    $config['maintain_ratio'] = true;
    $config['new_image']      = $destinationpath;

    // we set fix image width & height
    $config['width']  = $setwidth;
    $config['height'] = $setheight;
    $CI->image_lib->initialize($config);
    
    if (!$CI->image_lib->resize()) {
        echo $CI->image_lib->display_errors();
    }
}

function create_thumbnail($fullpath, $destinationpath, $setwidth, $setheight)
{
    // ------ Create Thumbnail -------
    $CI =& get_instance();
    $CI->load->library('image_lib');
    $CI->image_lib->clear();

    // create image thumbnail
    list($width, $height, $type, $attr) = getimagesize($fullpath);
    
    // landscape
    $config['image_library']  = 'gd2';
    $config['source_image']   = $fullpath;
    $config['create_thumb']   = true;
    $config['maintain_ratio'] = true;
    $config['new_image']      = $destinationpath;

    // we set fix image width & height
    $config['width']  = $setwidth;
    $config['height'] = $setheight;
    $CI->image_lib->initialize($config);
    
    if (!$CI->image_lib->resize()) {
        echo $CI->image_lib->display_errors();
    }
}

/*function create_img_thumbnail($fullpath)
{
	// ------ Create Thumbnail -------
	$CI =& get_instance();
    $CI->load->library('image_lib');
    $CI->image_lib->clear();

    // create image thumbnail
    list($width, $height, $type, $attr) = getimagesize($fullpath);
    
    // landscape
    $config['image_library']  = 'gd2';
    $config['source_image']   = $fullpath;
    $config['create_thumb']   = true;
    $config['maintain_ratio'] = true;        

    // if ($width >= $height) {
    //     $res_width  = 160;
    //     $res_height = 160;
    // } else {
    //     $res_width  = 0.25*$width;
    //     $res_height = 0.25*$height;
    // }

    // we set fix image width & height
    $config['width']  = 92;
    $config['height'] = 138;
	
    $CI->image_lib->initialize($config);
    
    if (!$CI->image_lib->resize()) {
        echo $CI->image_lib->display_errors();
    }
}*/

function WaterMarkImages($SourceFile, $watermark){
    // Load the stamp and the photo to apply the watermark to
    $stamp = imagecreatefrompng($watermark);
    $im = imagecreatefromjpeg($SourceFile);

    // Set the margins for the stamp and get the height/width of the stamp image
    //$marge_right = 15;
    //$marge_right = (imagesx($im) - imagesx($im))+50;
    //$marge_bottom = (imagesy($im) - (imagesy($im)/2))/2+20;
    $marge_right = (imagesx($im) - imagesx($stamp))/2;
    $marge_bottom = (imagesy($im) - imagesy($stamp))/2;

    $sx = imagesx($stamp);
    $sy = imagesy($stamp);

    // Copy the stamp image onto our photo using the margin offsets and the photo 
    // width to calculate positioning of the stamp. 
    //imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
    imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
    
    // Output and free memory
    header('Content-type: image/png');
    imagepng($im);
    imagedestroy($im);
}

function croppingImagetoThumb($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale)
{
    list($imagewidth, $imageheight, $imageType) = getimagesize($image);
    $imageType = image_type_to_mime_type($imageType);

    $newImageWidth = ceil($width * $scale);
    $newImageHeight = ceil($height * $scale);
    $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
    switch ($imageType) {
        case "image/gif":
            $source = imagecreatefromgif($image);
            break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
            $source = imagecreatefromjpeg($image);
            break;
        case "image/png":
        case "image/x-png":
            $source = imagecreatefrompng($image);
            break;
    }
    imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width, $height);
    switch ($imageType) {
        case "image/gif":
            imagegif($newImage, $thumb_image_name);
            break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
            imagejpeg($newImage, $thumb_image_name, 90);
            break;
        case "image/png":
        case "image/x-png":
            imagepng($newImage, $thumb_image_name);
            break;
    }
    chmod($thumb_image_name, 0777);
    return $thumb_image_name;
}

?>