<?php
require_once 'includes/initialize.php';

//Check for domain-origin of request!!!!!!!!

$time = time();
session_start();


$_SESSION['id'] = session_id();
$imgfolder = $_SESSION['id'];
$img_folder  = IMG_FOLDER . substr($imgfolder, 0, 10);


if (!is_dir($img_folder)) {
    mkdir($img_folder);
 }

$url = $_GET['url'];

if  (   !isset($_GET['index'])   )  {exit;}
if ( $_GET['index'] !=1  &&  $_GET['index'] !=2 && 
       $_GET['index'] !="1" && $_GET['index'] !="2" ) {exit;}
$index = $_GET['index'];


$mask = $img_folder .  '/image' . $index . '*.jpg';
@array_map( "unlink", glob($mask) );

switch (pathinfo($url, PATHINFO_EXTENSION)) {

    case 'gif':
        $filename = $img_folder . '/image' . $index . '.' . pathinfo($url, PATHINFO_EXTENSION);
        img2file($url, $filename);
        $new_filename =  $img_folder .  '/image' . $index ."_".$time. '.jpg';
        gif2jpeg($filename, $new_filename);
        
        
        $cropd_filename =  $img_folder .  '/cropd_image' . $index. '.jpg'; 
        copy($new_filename, $cropd_filename);
        
        header('Content-Type: application/json');
        echo jresponse($new_filename);
        exit;
        break;
    
    case 'jpg':
    case 'jpeg':
        $filename = $img_folder . '/image' . $index ."_".$time. '.' . pathinfo($url, PATHINFO_EXTENSION);
        img2file($url, $filename);
        
        $cropd_filename =  $img_folder .  '/cropd_image'.$index.'.jpg'; 
        copy($filename, $cropd_filename);
        
        header('Content-Type: application/json');
        echo jresponse($filename);
        exit;
        break;
    
    case 'png':
        header('Content-Type: application/json');
        echo jresponse(null, "Oops.. PNG images are not supported yet! Sorry..", 1);
        
        
//        $response = array("filename"=>$filename);
//        header('Content-Type: application/json');
//        echo json_encode($response);
        exit;
        break;


    default:
        $a = @getimagesize($url);
        $image_type = $a[2];

        switch ($image_type) {  
               case IMAGETYPE_GIF:
                    
                    $filename = $img_folder . '/image' . $index ."_".$time.  '.gif';
                    img2file($url, $filename);
                    $new_filename =  $img_folder .  '/image' . $index ."_".$time. '.jpg';
                    gif2jpeg($filename, $new_filename);

                    $cropd_filename =  $img_folder .  '/cropd_image' . $index . ".jpg" ; 
                    copy($new_filename, $cropd_filename);

                    header('Content-Type: application/json');
                    echo jresponse($new_filename);
                    exit;
                    break;
               
               case IMAGETYPE_JPEG:
                    $filename = $img_folder . '/image' . $index ."_".$time. '.jpg';
                    img2file($url, $filename);
        
                    $cropd_filename =  $img_folder .  '/cropd_image'.$index . '.jpg';
                    copy($filename, $cropd_filename);
        
                    header('Content-Type: application/json');
                    echo jresponse($filename);
                    exit;
                    break;
               
                case IMAGETYPE_PNG:
                    header('Content-Type: application/json');
                    echo jresponse(null, "Oops.. PNG images are not supported yet! Sorry..", 1);
                    exit;
                    break;
                   
               default:
                   if (!startsWith($url, "http://") && !startsWith($url, "https://")) {
                       $url = "http://".$url;
                   }
                   
                   $images_r = get_images_from_url($url, $img_folder."/temp", 200, 200);
                   header('Content-Type: application/json');
                        
                   if ( !empty($images_r) ) {
                      // $i = 0;
                       $images = array();
                       
                       foreach ($images_r as $image) {
                           $images[] = $image;
                          // unset($images_r[$i]);
                       //   $i++;
                       }
                       $_SESSION['images'] = $images;
                        echo jresponse(json_encode($images), NULL, 0, 1);
                   }
                   else {
                        echo jresponse(null, 'Sorry.. We couldn\'t find any valid images. ', 1,0);
                   }
                   
                   break;
            }
  }


?>