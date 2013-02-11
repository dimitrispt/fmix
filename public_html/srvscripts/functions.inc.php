<?php

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
    
    

    /********* Alternative Script   with 'Save As' **************************************/


    /*

    $w=400;

    $h=400;    // h est facultatif, =w par d&#233;faut
    $x=150;    // x est facultatif, 0 par d&#233;faut
    $y=150;    // y est facultatif, 0 par d&#233;faut

    $filename="someimage.jpg";

    header('Content-type: image/jpg');

     //next header forces server to Save As
    header('Content-Disposition: attachment; filename=cropped.jpg'); 

    $image = imagecreatefromjpeg($filename); 
    $crop = imagecreatetruecolor($w,$h);

    imagecopy( $crop, $image, 0, 0, $x, $y, $w, $h );

    imagejpeg($crop);

    */


}

?>