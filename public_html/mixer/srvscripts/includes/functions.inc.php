<?php
require_once 'simpleimage.class.php';
require_once 'simple_html_dom.php';
require_once 'Fastimage.php';


function jresponse($filename, $msg="", $error=0, $array=0) {
    $response = array("filename"=>$filename, "msg"=>$msg, "error"=>$error, "array"=>$array);
     return json_encode($response);
}


function gif2jpeg($p_fl, $p_new_fl='', $bgcolor=false){
  list($wd, $ht, $tp, $at)=getimagesize($p_fl);
  $img_src=imagecreatefromgif($p_fl);
  $img_dst=imagecreatetruecolor($wd,$ht);
  $clr['red']=255;
  $clr['green']=255;
  $clr['blue']=255;
  if(is_array($bgcolor)) $clr=$bgcolor;
  $kek=imagecolorallocate($img_dst,
                  $clr['red'],$clr['green'],$clr['blue']);
  imagefill($img_dst,0,0,$kek);
  imagecopyresampled($img_dst, $img_src, 0, 0, 
                  0, 0, $wd, $ht, $wd, $ht);
  $draw=true;
  if(strlen($p_new_fl)>0){
    if($hnd=fopen($p_new_fl,'w')){
      $draw=false;
      fclose($hnd);
    }
  }
  if(true==$draw){
    header("Content-type: image/jpeg");
    imagejpeg($img_dst);
  }else imagejpeg($img_dst, $p_new_fl);
  imagedestroy($img_dst);
  imagedestroy($img_src);
}

function load_image($img_url, $index) {
    

    $filename = 'image' . $index . '.jpg';

    img2file($img_url, $filename);

}

/**
 * 
 * @param string $img_url
 * @param string $filename
 * @return void 
 */
function img2file($img_url, $filename) {

    $ch = curl_init($img_url);
    $fp = fopen($filename, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);

}


function crop_image($img_file, $cropd, $dimensions) {
    
    
    //TODO: check if $dimensions are bigger than the original ones
    
 
    // Get dimensions of the original image
    list($current_width, $current_height) = getimagesize($img_file);

    // Resample the image
    $canvas = imagecreatetruecolor($dimensions['width'], $dimensions['height']);
    
    //$current_image = imagecreatefromjpeg($img_file);
    switch (pathinfo($img_file, PATHINFO_EXTENSION)) {
        case 'jpg':
        case 'jpeg':
            $current_image = imagecreatefromjpeg($img_file);
             imagecopy($canvas, $current_image, 0, 0, $dimensions['left'], $dimensions['top'],
                        $current_width, $current_height);
             imagejpeg($canvas, $cropd, 100); 
            break;

        case 'gif':
            $current_image = imagecreatefromgif($img_file);
             imagecopy($canvas, $current_image, 0, 0, $dimensions['left'], $dimensions['top'],
                        $current_width, $current_height);
             imagegif($canvas, $cropd, 100);
            break;

        case 'png':
            $current_image = imagecreatefrompng($img_file);
             imagecopy($canvas, $current_image, 0, 0, $dimensions['left'], $dimensions['top'],
                        $current_width, $current_height);
             imagepng($canvas, $cropd, 100);
            break;

}
    
}

function resizeImage($img, $width, $height, $newimg) {
    $image = new SimpleImage();
    $image->load($img);
    $image->resize($width,$height);
    $image->save($newimg);
}


function mergeImages($bkgimage, $newimg1, $newimg2, $top1, $left1, $w1, $h1, 
                        $top2, $left2,$w2, $h2, $opacity1, $opacity2,$bg_index) {
    
    $imgfolder = $_SESSION['id'];
    $img_folder  = IMG_FOLDER . substr($imgfolder, 0, 10);
    copy($bkgimage, $img_folder."/bkg".$bg_index.".jpeg" );
    $bkgimage = $img_folder."/bkg".$bg_index.".jpeg" ;
    
    $background = imagecreatefromjpeg($bkgimage);
    $image1 = imagecreatefromjpeg($newimg1);
    $image2 = imagecreatefromjpeg($newimg2);

    imagecopymerge($background, $image1, $left1, $top1, 0, 0, $w1, $h1, $opacity1);
    imagejpeg($background, $bkgimage );
    
    $background = imagecreatefromjpeg($bkgimage);
    imagecopymerge($background, $image2, $left2, $top2, 0, 0, $w2, $h2, $opacity2);
    imagejpeg($background, $bkgimage );
    
    imagedestroy($background);
    imagedestroy($image1);
    imagedestroy($image2);
    
}

function startsWith($str, $prefix) {
    $temp = substr ( $str, 0, strlen ( $prefix ) );
    $temp = strtolower ( $temp );
    $prefix = strtolower ( $prefix );
    return ($temp == $prefix);
}

function getExtention($type) {
    $type = strtolower ( $type );
    switch ($type) {
        case "image/gif" :
            return ".gif";
            break;
        case "image/png" :
            return ".png";
            break;

        case "image/jpeg" :
            return ".jpg";
            break;

        default :
            return ".img";
            break;
    }
}


function get_images_from_url($url, $tempfolder, $minWidth, $minHeight) {
    $imgindex = 0;
    $mask = $tempfolder .  '/*';
    @array_map( "unlink", glob($mask) );
    @rmdir($tempfolder);
    mkdir($tempfolder);
    
    $nodes = array();
    //get all images src
    $html = file_get_html($url);
    
    foreach ($html->find('img') as $element) {
        if (startsWith( $element->src, "/" )) {
            $element->src = $url . $element->src;
        }
        else if (!startsWith( $element->src, "http" )) {
            $element->src = $url . "/" . $element->src;
        }
        
        $nodes[] =  $element->src;
        
    }
    
    //save on temp folder on disk
    $mh = curl_multi_init();
    $curl_array = array();
        
    foreach ($nodes as $i=>$src_url) {
        $curl_array[$i] = curl_init($src_url);
        curl_setopt ( $curl_array [$i], CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $curl_array [$i], CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 (.NET CLR 3.5.30729)' );
        curl_setopt ( $curl_array [$i], CURLOPT_CONNECTTIMEOUT, 5 );
        curl_setopt ( $curl_array [$i], CURLOPT_TIMEOUT, 15 );
        curl_multi_add_handle ( $mh, $curl_array [$i] );
    }
    
   $running = NULL;
   do {
        //usleep ( 10000 );
        curl_multi_exec ( $mh, $running );
   } while ( $running > 0 );
        
  $res = array ();
  foreach ( $nodes as $i => $src_url ) {
        $curlErrorCode = curl_errno ( $curl_array [$i] );

        if ($curlErrorCode === 0) {
            $info = curl_getinfo ( $curl_array [$i] );
            $ext = getExtention ( $info ['content_type'] );
            
            if (      ($info ['content_type'] !== null) && 
                    ( ($ext==".jpg") || ($ext=".gif") )   )  {   //filter of image type
                $temp = $tempfolder. "/img" . $imgindex . $ext;
                touch ( $temp );
                $imageContent = curl_multi_getcontent ( $curl_array [$i] );
                file_put_contents ( $temp, $imageContent ); //save image on disk
                
                if ($minHeight == 0 || $minWidth == 0) {
                    $res [] = $temp;
                } else {
                    $size = @getimagesize ( $temp );
                    if (($size [0] >= $minWidth) && ($size [1] >= $minHeight)) {  //filter dimensions
                        $res [] = $temp;
                    } else {
                        unlink ( $temp );  //delete smaller images
                    }
                }
            }
        }
        curl_multi_remove_handle ( $mh, $curl_array [$i] );
        curl_close ( $curl_array [$i] );
        $imgindex++;
    }

    curl_multi_close ( $mh );
    
    //output the useful images filenames
    return $res;
    
}


/**********************************************************************/
//                      -----TOO SLOW----
/* function get_images_from_url2($url, $tempfolder, $maxWidth, $maxHeight) {
 
    $nodes = array();
    //get all images src
    $html = file_get_html($url);
    
    foreach ($html->find('img') as $element) {
        if (startsWith( $element->src, "/" )) {
            $element->src = $url . $element->src;
        }
        else if (!startsWith( $element->src, "http" )) {
            $element->src = $url . "/" . $element->src;
        }
        
        $nodes[] =  $element->src;
        
    }
    
   foreach ($nodes as $i=>$src_url) {
        $image = new FastImage($src_url);
        list($width, $height) = $image->getSize();
        $imgtype = $image->getType();
        
        if ( ($width<$maxWidth) || ($height<$maxHeight) || (($imgtype != "jpeg") && ($imgtype!="gif")) ){
            unset($nodes[$i]);
        }
           
   } 
    //save on temp folder on disk
    $mh = curl_multi_init();
    $curl_array = array();
        
   foreach ($nodes as $i=>$src_url) {
           
        $curl_array[$i] = curl_init($src_url);
        curl_setopt ( $curl_array [$i], CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $curl_array [$i], CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 (.NET CLR 3.5.30729)' );
        curl_setopt ( $curl_array [$i], CURLOPT_CONNECTTIMEOUT, 5 );
        curl_setopt ( $curl_array [$i], CURLOPT_TIMEOUT, 15 );
        curl_multi_add_handle ( $mh, $curl_array [$i] );
    }
    
   $running = NULL;
   do {
        usleep ( 10000 );
        curl_multi_exec ( $mh, $running );
   } while ( $running > 0 );
        
  $res = array ();
  foreach ( $nodes as $i => $src_url ) {
        $curlErrorCode = curl_errno ( $curl_array [$i] );

        if ($curlErrorCode === 0) {
            $info = curl_getinfo ( $curl_array [$i] );
            $ext = getExtention ( $info ['content_type'] );
            
            if (      ($info ['content_type'] !== null) && 
                    ( ($ext==".jpg") || ($ext=".gif") )   )  {   //filter of image type
                $temp = $tempfolder. "/img" . md5( mt_rand() ) . $ext;
                touch ( $temp );
                $imageContent = curl_multi_getcontent ( $curl_array [$i] );
                file_put_contents ( $temp, $imageContent ); //save image on disk
                
               $res [] = $temp;
               
         }
     }
   
        
        curl_multi_remove_handle ( $mh, $curl_array [$i] );
        curl_close ( $curl_array [$i] );

    }

    curl_multi_close ( $mh );
    
    //output the useful images filenames
    return $res;
    
} END OF ALTERNATIVE IMPLEMENTATION FOR GETTING IMAGES FROM URL */
?>