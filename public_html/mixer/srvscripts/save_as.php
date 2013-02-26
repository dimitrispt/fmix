<?php
require_once 'includes/initialize.php';

session_start();


$_SESSION['id'] = session_id();
$imgfolder = $_SESSION['id'];
$img_folder  = IMG_FOLDER . substr($imgfolder, 0, 10);
$mixfile = "bkg.jpeg";
$mymix = $img_folder."/".$mixfile;

$caption = $_GET['capt'];
//$caption = strip_tags($caption);

if ($caption == "") {$caption = "mymix";}


header('Content-type: image/jpeg');
$header2 = 'Content-disposition: attachment; filename="' .$caption. '.jpg"';
header($header2);

readfile($mymix);
?>