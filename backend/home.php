<?php
// There's no landing page for loveeu.uk, so if the request is for that then redirect to B4B site
$url = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if($_SERVER['HTTP_HOST'] == 'loveeu.uk'){
	header("HTTP/1.1 301 Moved Permanently"); 
	header("Location: https://www.bestforbritain.org/love-eu"); 
	exit();
}
echo file_get_contents(dirname(dirname(__FILE__)).'/frontend/heymp/index.html');
?>