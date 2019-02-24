<?php

$url = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if($url == '/favicon.ico'){
	echo $_SERVER['HTTP_HOST'];
}


header("HTTP/1.0 404 Not Found");
echo "Not found.\n";
die();
?>