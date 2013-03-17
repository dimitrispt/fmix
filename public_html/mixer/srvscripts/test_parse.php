<?php
require_once 'includes/initialize.php';

@mkdir("temp");

$start = microtime(true);

print_r(get_images_from_url("http://www.rollingstone.com/music/artists", "temp", 200, 200));

echo "\n<br/>";
echo microtime(true)- $start;

?>