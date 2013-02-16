<?php
require_once 'functions.inc.php';

//Check for domain-origin of request


session_start();


$_SESSION['id'] = session_id();
$imgfolder = $_SESSION['id'];
$img_folder  = substr($imgfolder, 0, 10);


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
        header('Content-Type: application/json');
        echo jresponse($filename);
        exit;
        break;
    
    case 'jpg':
    case 'jpeg':
        $filename = $img_folder . '/image' . $index . '.' . pathinfo($url, PATHINFO_EXTENSION);
        img2file($url, $filename);
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


/*

$c = curl_init($url);
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

$html = curl_exec($c);

if (curl_error($c))
    die(curl_error($c));

// Get the status code
$status = curl_getinfo($c, CURLINFO_HTTP_CODE);

curl_close($c);
    


$dom = new domDocument;
$dom->loadHTML($html);
$dom->preserveWhiteSpace = false;
$images = $dom->getElementsByTagName('img');
foreach ($images as $image) {
  $all_images[] =  $image->getAttribute('src');
}


foreach($all_images as $image) {
    list($current_width, $current_height) = getimagesize($image);
    if ($current_width>=100 && $current_height>=100) {
        $my_images[] = $image;
    }
}

echo(json_encode($my_images));
 */
?>