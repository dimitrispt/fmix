<?php
require_once 'includes/initialize.php';

//Check for domain-origin of request


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

switch (pathinfo($url, PATHINFO_EXTENSION)) {

    case 'gif':
        $filename = $img_folder . '/image' . $index . '.' . pathinfo($url, PATHINFO_EXTENSION);
        img2file($url, $filename);
        gif2jpeg($filename, $img_folder . '/image' . $index . '.jpg');
        $filename =  $img_folder .  '/image' . $index . '.jpg';
        
        $res_filename =  $img_folder .  '/res_image' . $index . '.jpg'; 
        copy($filename, $res_filename);
        
        header('Content-Type: application/json');
        echo jresponse($filename);
        exit;
        break;
    
    case 'jpg':
    case 'jpeg':
        $filename = $img_folder . '/image' . $index . '.' . pathinfo($url, PATHINFO_EXTENSION);
        img2file($url, $filename);
        
        $res_filename =  $img_folder .  '/res_image' . $index . '.jpg'; 
        copy($filename, $res_filename);
        
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
        header('Content-Type: application/json');
        echo jresponse(null, 'Sorry.. this is not a valid Image URL. ', 1);
        
        break;
}


?>