<?php
require_once 'includes/initialize.php';

session_start();
$_SESSION['id'] = session_id();


//retrieve POST parameters
$width1   = intval($_POST['wi1']);
$height1  = intval($_POST['hi1']);
$top1      = intval($_POST['t1']);
$left1      = intval($_POST['l1']);
$opacity1= intval($_POST['opac1']);

$width2   = intval($_POST['wi2']);
$height2  = intval($_POST['hi2']);
$top2      = intval($_POST['t2']);
$left2      = intval($_POST['l2']);
$opacity2= intval($_POST['opac2']);

$zindex   = intval($_POST['zx']);
$bg_index = intval($_POST['bg_index']);

if (!(  is_numeric($width1) && is_numeric($height1) 
       && is_numeric($width2) && is_numeric($height2) )){exit;}

if (!(  is_numeric($top1) && is_numeric($left1) 
       && is_numeric($top2) && is_numeric($left2) )){exit;}
       
if (!(  is_numeric($opacity1) && is_numeric($opacity2) 
       && is_numeric($zindex) && is_numeric($bg_index) )){exit;}
       
//set sessions' folder and chosen background
$imgfolder = $_SESSION['id'];
$img_folder  = IMG_FOLDER . substr($imgfolder, 0, 10);
$backfile = "bkg".$bg_index.".jpeg";
$bkgimage = IMG_FOLDER . $backfile;

$img1       = $img_folder . "/cropd_image1.jpg";
$img2       = $img_folder . "/cropd_image2.jpg";
$newimg1 = $img_folder . "/resd_image1.jpg";
$newimg2 = $img_folder . "/resd_image2.jpg";

if (!(is_file($img1) && is_file($img2))) {echo "no files";exit;}

//Resize images to much the resizable-images dimensions at the front-end
resizeImage($img1, $width1, $height1, $newimg1);
resizeImage($img2, $width2, $height2, $newimg2);

//Merge with an order as such to much the z-index at the front-end
if ($zindex == 1) {
    mergeImages($bkgimage, $newimg2, $newimg1, $top2,$left2, $width2, $height2,
                $top1, $left1, $width1, $height1, $opacity2, $opacity1,$bg_index);
}
else {
    mergeImages($bkgimage, $newimg1, $newimg2, $top1,$left1, $width1, $height1,
                $top2, $left2, $width2, $height2, $opacity1, $opacity2,$bg_index);
}

//delete previous mixes
$mask = $img_folder .  '/bkg*_*.jpeg';
@array_map( "unlink", glob($mask) );
$mix = $img_folder."/bkg".$bg_index."_".time().".jpeg";

copy($img_folder."/".$backfile, $mix );

//delete the (empty) backgrounds
$mask = $img_folder .  '/bkg?.jpeg';
@array_map( "unlink", glob($mask) );

//output the location of the mix
echo $mix;

?>