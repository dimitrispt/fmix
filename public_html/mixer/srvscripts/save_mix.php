<?php
require_once 'includes/initialize.php';

session_start();
$_SESSION['id'] = session_id();

$imgfolder = $_SESSION['id'];
$img_folder  = IMG_FOLDER . substr($imgfolder, 0, 10);
$backfile = "bkg.jpeg";
$bkgimage = IMG_FOLDER . $backfile;

function resizeImage($img, $width, $height, $newimg) {
     
    $image = new SimpleImage();
    $image->load($img);
    $image->resize($width,$height);
    $image->save($newimg);
}

function mergeImages($bkgimage, $newimg1, $newimg2, $top1, $left1, $w1, $h1, 
                                                        $top2, $left2,$w2, $h2, $opacity1, $opacity2) {
    
    $imgfolder = $_SESSION['id'];
    $img_folder  = IMG_FOLDER . substr($imgfolder, 0, 10);
    copy($bkgimage, $img_folder."/bkg.jpeg" );
    $bkgimage = $img_folder."/bkg.jpeg" ;
    
    $background = imagecreatefromjpeg($bkgimage);
    $image1 = imagecreatefromjpeg($newimg1);
    $image2 = imagecreatefromjpeg($newimg2);

    imagecopymerge($background, $image1, $left1, $top1, 0, 0, $w1, $h1, $opacity1);
    imagejpeg($background, $bkgimage );
    
    $background = imagecreatefromjpeg($bkgimage);
    imagecopymerge($background, $image2, $left2, $top2, 0, 0, $w2, $h2, $opacity2);
    imagejpeg($background, $bkgimage );
    
    imagedestroy($background);
    imagedestroy($image1);
    imagedestroy($image2);
    
}


        
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


if (!(  is_numeric($width1) && is_numeric($height1) 
       && is_numeric($width2) && is_numeric($height2) )){exit;}

if (!(  is_numeric($top1) && is_numeric($left1) 
       && is_numeric($top2) && is_numeric($left2) )){exit;}
       
if (!(  is_numeric($opacity1) && is_numeric($opacity2) 
       && is_numeric($zindex) )){exit;}

$img1       = $img_folder . "/res_image1.jpg";
$img2       = $img_folder . "/res_image2.jpg";
$newimg1 = $img_folder . "/newimage1.jpg";
$newimg2 = $img_folder . "/newimage2.jpg";

resizeImage($img1, $width1, $height1, $newimg1);
resizeImage($img2, $width2, $height2, $newimg2);

if ($zindex == 1) {
    mergeImages($bkgimage, $newimg2, $newimg1, $top2,$left2, $width2, $height2, $top1, $left1, $width1, $height1, $opacity2, $opacity1);
}
else {
    mergeImages($bkgimage, $newimg1, $newimg2, $top1,$left1, $width1, $height1, $top2, $left2, $width2, $height2, $opacity1, $opacity2);
}

echo  $img_folder."/".$backfile;
  /*  copy($bkgimage, $img_folder."/bkg.jpeg" );
    $bkgimage = $img_folder."/bkg.jpeg" ;
    
    $background = imagecreatefromjpeg($bkgimage);
    $image1 = imagecreatefromjpeg($newimg1);
    $image2 = imagecreatefromjpeg($newimg2);

    echo imagecopymerge($background, $image1, 100, 100, 0, 0, $width1, $height1, 75);
    echo imagejpeg($background, $bkgimage );
*/

?>