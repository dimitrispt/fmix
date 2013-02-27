<?php
require_once 'includes/initialize.php';

session_start();


$_SESSION['id'] = session_id();
$imgfolder = $_SESSION['id'];
$img_folder  = IMG_FOLDER . substr($imgfolder, 0, 10);
$mask = $img_folder."/bkg*_*.jpeg";
$files = glob($mask); 
$mixfile = $files[0];
$mymix = $mixfile;

$caption = $_GET['capt'];
//$caption = strip_tags($caption);

if ($caption == "") {$caption = "mymix";}


header('Content-type: image/jpeg');
$header2 = 'Content-disposition: attachment; filename="' .$caption. '.jpg"';
header($header2);

readfile($mymix); 
?>