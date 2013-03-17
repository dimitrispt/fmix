<?php
require_once 'includes/initialize.php';

//Check for domain-origin of request!!!!!!!!


session_start();


$_SESSION['id'] = session_id();
$imgfolder = $_SESSION['id'];
$img_folder  = IMG_FOLDER . substr($imgfolder, 0, 10);


if (!is_dir($img_folder)) {
    mkdir($img_folder);
 }

$imgindex = $_GET['imgi'];

if  (   !isset($_GET['mixindex'])   )  {exit;}

if ( $_GET['mixindex'] !=1  &&  $_GET['mixindex'] !=2 && 
       $_GET['mixindex'] !="1" && $_GET['mixindex'] !="2" ) {exit;}
$index = $_GET['mixindex'];


$url = $_SESSION['images'][$imgindex];
/*$filename = $img_folder .  '/image' . $index . '.jpg';
copy($url, $filename);

$cropd_filename =  $img_folder .  '/cropd_image' . $index . '.jpg'; 
copy($filename, $cropd_filename);
        
header('Content-Type: application/json');
echo jresponse($filename, NULL, 0, 0);

/*/
switch (pathinfo($url, PATHINFO_EXTENSION)) {

    case 'gif':
        $filename = $img_folder . '/image' . $index . '.' . pathinfo($url, PATHINFO_EXTENSION);
        copy($url, $filename);
        gif2jpeg($filename, $img_folder . '/image' . $index . '.jpg');
        $filename =  $img_folder .  '/image' . $index . '.jpg';
        
        $cropd_filename =  $img_folder .  '/cropd_image' . $index . '.jpg'; 
        copy($filename, $cropd_filename);
        
        header('Content-Type: application/json');
        echo jresponse($filename);
        exit;
        break;
    
    case 'jpg':
    case 'jpeg':
        $filename = $img_folder . '/image' . $index . '.' . pathinfo($url, PATHINFO_EXTENSION);
        copy($url, $filename);
        
        $cropd_filename =  $img_folder .  '/cropd_image' . $index . '.jpg'; 
        copy($filename, $cropd_filename);
        
        header('Content-Type: application/json');
        echo jresponse($filename);
        exit;
        break;
    
    case 'png':
        header('Content-Type: application/json');
        echo jresponse(null, "Oops.. PNG images are not supported yet! Sorry..[error: GIU01]", 1);
        
        
//        $response = array("filename"=>$filename);
//        header('Content-Type: application/json');
//        echo json_encode($response);
        exit;
        break;


    default:
        header('Content-Type: application/json');
        echo jresponse(null, "Sorry, this is not a valid image [error: GIU02]", 1);
        exit;
        break;
            
  }


?>