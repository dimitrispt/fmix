<?php
require_once 'functions.inc.php';
//
//From proxy.php - PHPmaster >api> ajax with php
//
//$requested_host = parse_url("http:/" . $_SERVER['PATH_INFO'], PHP_URL_HOST);
//
//if (!isset($allowed_hosts[$requested_host])) {
//    header("Status: 403 Forbidden");
//    exit;
//}


//$_GET['img_url']
$img_url = 'http://www.womensdish.com/wp-content/plugins/php-image-cache/image.php?path=/wp-content/uploads/2012/03/Working-Women-Fashion.jpeg';

// if  (   !( isset($_GET['index']) )   )  {exit;}
// if ( $_GET['index'] !=1  &&  $_GET['index'] !=2 ) {exit;}

// $index = $_GET['index']
$index = 1;

$filename = 'image' . $index . '.jpg';

img2file($img_url, $filename);
?>