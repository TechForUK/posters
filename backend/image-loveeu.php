<?php
use setasign\Fpdi\Fpdi;

require_once(dirname(__FILE__) . '/libraries/fpdf/fpdf.php'); 
require_once(dirname(__FILE__) . '/libraries/fpdi/src/autoload.php');

$city = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Basic syntax checking
if((strlen($city)<14) || (substr(strtolower($city), 0, 8) != "/images/") || 
	((substr(strtolower($city), -4)!=".pdf") && (substr(strtolower($city), -4)!=".png"))){
	header("HTTP/1.0 404 Not Found");
	echo "Not found.\n";
	die();
}
$filetype = substr($city, -3); // For example, input will be "/images/Cityname.pdf"
$city = substr($city, 8, -4);  // For example, input will be "/images/Cityname.pdf"

// Check this is a valid city or town
include(dirname(__FILE__) . '/uktowns.inc.php'); // A list of all UK towns and cities
if(!in_array(strtolower($city), $uktowns)){
	header("HTTP/1.0 404 Not Found");
	echo "Not found.\n";
	die();
}

// Do formatting
$city = str_replace('-',' - ', $city);
if(strlen($city) > 18) {
	$templateName = '/template-loveeu-3line.pdf';
} else if(strlen($city) > 9) {
	$templateName = '/template-loveeu-2line.pdf';
} else {
	$templateName = '/template-loveeu-1line.pdf';
}

// Initiate FPDI to add name to PDF
$pdf = new Fpdi();
// add a page
$pdf->AddPage('L', 'A4');
// set the source file
$pdf->setSourceFile(dirname(__FILE__) . $templateName);
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at position 0,0 with a width of 297 mm
$pdf->useTemplate($tplIdx, 0, 0, 297);
header('Cache-Control: public, max-age='.(60*60*24*14));
header('Expires: '.gmdate("D, d M Y H:i:s", strtotime("+14 days")) . " GMT");
// Now write some text above the imported page
$pdf->AddFont('PermanentMarker','','PermanentMarker-Regular.php');
$pdf->SetFont('PermanentMarker', '', 80);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(100, 31);
$pdf->Write(30, strtoupper($city));
if($filetype == 'png'){
	$url = 'http://xeio.com/poster/convert.php';
	$data = $pdf->Output('S');
	$context = array (
        'http' => array (
            'method' => 'POST',
            'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
                . "Content-Length: " . strlen($data) . "\r\n",
            'content' => $data
            )
        );
	$context = stream_context_create($context);
	header('Content-type: image/png');
	echo file_get_contents($url, false, $context);
} else {
	header('Content-type: application/pdf');
	header('content-disposition: attachment; filename="LoveEU-poster-'.preg_replace('/[^a-z]/i', '-', strtolower($city)).'.pdf"');
	echo $pdf->Output('S');
}
?>