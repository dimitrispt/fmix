<?php
require_once 'includes/initialize.php';

session_start();
$_SESSION['id'] = session_id();
$imgfolder = $_SESSION['id'];
$img_folder  = IMG_FOLDER . substr($imgfolder, 0, 10);

if (!is_dir($img_folder)) {
    mkdir($img_folder);
 }


copy(IMG_FOLDER."image1.jpg", $img_folder."/image1.jpg");
copy(IMG_FOLDER."image2.jpg", $img_folder."/image2.jpg");
copy(IMG_FOLDER."image1.jpg", $img_folder."/cropd_image1.jpg");
copy(IMG_FOLDER."image2.jpg", $img_folder."/cropd_image2.jpg");



if ( !($dh = opendir(IMG_FOLDER)) ) {exit;}

$name = "temp";
//echo basename(__DIR__);

while ($name = readdir($dh)) {
    //echo $name . "<br/>";
    
    if (is_dir($name)) {
    
        $age = time() - filectime($name);
    
        //79200 = 23 hours
        if (   ($age>79200)  &&  ($name !=".") && ($name !="..")     ){

                $mask = $name . "/*";
                array_map( "unlink", glob($mask) );
                rmdir($name);
                //echo "<br/> {$name} has deleted!<br/>";
        }
   }
}

echo $img_folder;

?>
