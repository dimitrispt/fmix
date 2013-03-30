<?php
require_once 'includes/initialize.php';

session_start();

if (!(  is_numeric($_GET['xi1']) && is_numeric($_GET['yi1']) 
        && is_numeric($_GET['wi1']) && is_numeric($_GET['hi1']) ) ){exit;}

if  (   !( isset($_GET['index']) )   )  {exit;}
if ( $_GET['index'] !=1  &&  $_GET['index'] !=2 ) {exit;}
$index = $_GET['index'];

$filename =explode('/',$_GET['image']);
$filename0 =array_pop($filename);

$imgfolder = $_SESSION['id'];
$img_folder  =  IMG_FOLDER . substr($imgfolder, 0, 10);

//Original image
$filename = "{$img_folder}/{$filename0}";

//Original dimensions
list($current_width, $current_height) = getimagesize($filename);
$w_coef = $current_width / 250;
$h_coef = $current_height / 250;

$x1  = $_GET['xi1'] * $w_coef;
$y1  = $_GET['yi1']  * $h_coef;
$w1 = $_GET['wi1'] * $w_coef;
$h1  = $_GET['hi1'] * $h_coef;



//$dimensions = $_GET['dimensions']
$dimensions = array("width"=>$w1, "height"=>$h1, "top"=>$y1, "left"=>$x1);


//new cropped image name
$cropd = $img_folder .  '/' . $filename0 . '_crop'. time() .'.jpg'; 

$mask = $img_folder .  '/image' . $index . '*_crop*.jpg';
@array_map( "unlink", glob($mask) );


crop_image($filename, $cropd, $dimensions);


$mask = $img_folder .  '/cropd_image' . $index . '*.jpg';
@array_map( "unlink", glob($mask) );

$cropd_filename =  $img_folder .  '/cropd_image' . $index . '.jpg'; 
copy($cropd, $cropd_filename);

echo $cropd;

?>