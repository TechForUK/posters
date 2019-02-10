<?php

use setasign\Fpdi\Fpdi;

require_once(dirname(__FILE__) . '/libraries/fpdf/fpdf.php'); 
require_once(dirname(__FILE__) . '/libraries/fpdi/src/autoload.php');

$city = urldecode($_SERVER['REQUEST_URI']);

// Check city
if((strlen($city)<1)||(substr(strtolower($city), -4)!=".pdf")){
	header("HTTP/1.0 404 Not Found");
	echo "Not found.\n";
	die();
}
$city = substr($city, 1, -4);
$headers = "accept: */*\r\n" .
    "Refer: https://bestforbritain.org/\r\n";

$context = [
    'http' => [
        'method' => 'GET',
        'header' => $headers,
        'content' => http_build_query($data),
    ]
];
$output = file_get_contents("https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=".urlencode($city).",%20UK&inputtype=textquery&fields=name&key=AIzaSyBB3wZ4G6IoT7PM6ze4lXu50QriGJmSqvg");

if(strlen($output)<10){
	header("HTTP/1.0 404 Not Found");
	echo "Not found.\n";
	die();
}

$check = json_decode($output);
if((!isset($check)) || (!isset($check->status)) || ($check->status != 'OK')){
	header("HTTP/1.0 404 Not Found");
	echo "Not found.\n";
	die();
}
$city = str_replace('-',' - ', $city);
$isLongName = strlen($city) > 9;
$templateName = $isLongName ? '/templatebig.pdf' : '/template.pdf';

// initiate FPDI
$pdf = new Fpdi();
// add a page
$pdf->AddPage('L', 'A4');
// set the source file
$pdf->setSourceFile(dirname(__FILE__) . $templateName);
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at position 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx, 0, 0, 297);

// now write some text above the imported page
$pdf->AddFont('PermanentMarker','','PermanentMarker-Regular.php');
$pdf->SetFont('PermanentMarker', '', 80);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(100, 20);
$pdf->Write(30, strtoupper($city));

$pdf->Output('D','LoveEU-poster.pdf');
?>
