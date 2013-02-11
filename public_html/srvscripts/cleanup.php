<?php

session_start();

$imgfolder = $_SESSION['id'];
$img_folder  = substr($imgfolder, 0, 10);

if (!isset($_SESSION['id'])) {
    $img_folder = "temp";
}

$mask = $img_folder . "/*";
 
 array_map( "unlink", glob($mask) );

 rmdir($img_folder);
 
 session_destroy();

?>