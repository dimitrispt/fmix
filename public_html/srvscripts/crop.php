<?php
require_once 'functions.inc.php';

session_start();

if  (   !( isset($_GET['index']) )   )  {exit;}
if ( $_GET['index'] !=1  &&  $_GET['index'] !=2 ) {exit;}
$index = $_GET['index'];

$imgfolder = $_SESSION['id'];
$img_folder  = substr($imgfolder, 0, 10);

//Original image
$filename = "{$img_folder}/image{$index}.jpg";
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
$cropd = $img_folder .  '/image' . $index . '_crop.jpg'; 


crop_image($filename, $cropd, $dimensions);

echo $cropd;

?>