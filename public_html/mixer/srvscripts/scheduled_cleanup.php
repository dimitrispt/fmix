<?php
require_once 'includes/initialize.php';

if ( !($dh = opendir(IMG_FOLDER)) ) {exit;}

$name = "temp";
echo basename(__DIR__);

while ($name = readdir($dh)) {
    //echo $name . "<br/>";
    
    if (is_dir($name)) {
    
        $age = time() - filectime($name);
    
        //79200 = 23 hours
        if (   ($age>79200)  &&  ($name !=".") && ($name !="..")     ){

                $mask = $name . "/*";
                array_map( "unlink", glob($mask) );
                rmdir($name);
                echo "<br/> {$name} has deleted!<br/>";
        }
   }
}


?>
