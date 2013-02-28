<?php
require_once 'includes/initialize.php';
session_start();

$_SESSION['id'] = session_id();
$imgfolder = $_SESSION['id'];
$img_folder  = IMG_FOLDER . substr($imgfolder, 0, 10);

if ($_GET['index']==1) {
copy($img_folder."/image1.jpg", $img_folder."/cropd_image1.jpg" );
}
else if ($_GET['index']==2) {
    copy($img_folder."/image2.jpg", $img_folder."/cropd_image2.jpg" );
}
else {
    exit;
}
?>