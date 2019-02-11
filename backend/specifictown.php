<?php
$city = urldecode($_SERVER['REQUEST_URI']);

// Basic syntax checking
if((strlen($city)<1)){
	header("HTTP/1.0 404 Not Found");
	echo "Not found.\n";
	die();
}
$city = substr($city, 1);  // For example, input will be "/Cityname"

// Check this is a valid city or town
include(dirname(__FILE__) . '/uktowns.inc.php'); // A list of all UK towns and cities
if(!in_array(strtolower($city), $uktowns)){
	header("HTTP/1.0 404 Not Found");
	echo "Not found.\n";
	die();
}
?>
<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Article">
<head>
<title><?php echo $city;?> ❤️ EU - #LoveEU</title>
<meta charset="UTF-8">
<meta name="description" content="I love <?php echo $city;?>, I love the UK, and I love the EU" />
<meta http-equiv="refresh" content="0; url=https://www.bestforbritain.org/love-eu">
<!-- Schema.org markup for Google+ -->
<meta itemprop="name" content="<?php echo $city;?> ❤️ EU - #LoveEU">
<meta itemprop="description" content="I love <?php echo $city;?>, I love the UK, and I love the EU">
<meta itemprop="image" content="https://loveeu.uk/images/<?php echo urlencode($city);?>.png">

<!-- Twitter Card data -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@BestForBritain">
<meta name="twitter:title" content="<?php echo $city;?> ❤️ EU - #LoveEU">
<meta name="twitter:description" content="I love <?php echo $city;?>, I love the UK, and I love the EU">
<meta name="twitter:creator" content="@BestForBritain">
<!-- Twitter summary card with large image must be at least 280x150px -->
<meta name="twitter:image:src" content="https://loveeu.uk/images/<?php echo urlencode($city);?>.png">

<!-- Open Graph data -->
<meta property="og:title" content="<?php echo $city;?> ❤️ EU - #LoveEU" />
<meta property="og:type" content="article" />
<meta property="og:image" content="https://loveeu.uk/images/<?php echo urlencode($city);?>.png" />
<meta property="og:description" content="I love <?php echo $city;?>, I love the UK, and I love the EU" />
<meta property="og:site_name" content="Best For Britain" />
<link href="https://fonts.googleapis.com/css?family=Permanent+Marker|Roboto:300" rel="stylesheet" crossorigin="anonymous">
<style type="text/css">
body { font-family: 'Roboto', Arial, Sans; text-align: center; padding-top: 60px;}
img { width: 40vw;}
a { text-decoration: none; color: gray; padding-bottom: 30px;}
</style>
</head>
<body>
<p>I love <?php echo $city;?>, I love the UK, and I love the EU</p>
<p><a href="https://www.bestforbritain.org/love-eu">Make One For Your Town Now!</a></p>
<img src="https://loveeu.uk/images/<?php echo urlencode($city);?>.png">
<script type="text/javascript">
window.location.href = "https://www.bestforbritain.org/love-eu";
</script>
</body>
</html>