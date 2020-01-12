<?php
include_once('pixelcode/pixelconstants.php');
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($path) {


 
    default :
        header("HTTP/1.0 404 Not Found");

        exit;
        break;

}

?>

