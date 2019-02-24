<?php
use setasign\Fpdi\Fpdi;

require_once(dirname(__FILE__) . '/libraries/fpdf/fpdf.php'); 
require_once(dirname(__FILE__) . '/libraries/fpdi/src/autoload.php');

$firstname = 'JACK';
$fullname = 'Jack Brereton';
$city = 'Stoke on Trent';
$image = 'E14000720.png';
$templateName = 'template-heymp.pdf';

class FPDIr extends FPDI { // Add ability to rotate inserted text
	function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle=0)
	{
	    $font_angle+=90+$txt_angle;
	    $txt_angle*=M_PI/180;
	    $font_angle*=M_PI/180;
	    $txt_dx=cos($txt_angle);
	    $txt_dy=sin($txt_angle);
	    $font_dx=cos($font_angle);
	    $font_dy=sin($font_angle);
	    $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',$txt_dx,$txt_dy,$font_dx,$font_dy,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
	    if ($this->ColorFlag)
	        $s='q '.$this->TextColor.' '.$s.' Q';
	    $this->_out($s);
	}
}

// Initiate FPDI to add name to PDF
$pdf = new FPDIr();
// add a page
$pdf->AddPage('L', 'A4');
// set the source file
$pdf->setSourceFile(dirname(__FILE__) .'/'. $templateName);
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at position 0,0 with a width of 297 mm
$pdf->useTemplate($tplIdx, 0, 0, 297);
header('Cache-Control: public, max-age='.(60*60*24*14));
header('Expires: '.gmdate("D, d M Y H:i:s", strtotime("+14 days")) . " GMT");
// Now write first name
$pdf->AddFont('Staatliches','','Staatliches-Regular.php');
$pdf->SetFont('Staatliches', '', 80);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(101, 39);
$pdf->Write(30, strtoupper('HEY '.$firstname));
// Now write arrow label
$pdf->AddFont('NanumPenScript','','NanumPenScript-Regular.php');
$pdf->SetFont('NanumPenScript', '', 24);
$pdf->SetTextColor(128, 128, 128);
$pdf->TextWithRotation(53, 18, $fullname.",", 7);
$pdf->TextWithRotation(54, 25, "MP for ".$city, 7);

// Now add pic of MP
$pdf->Image(dirname(__FILE__).'/mp-images/'.$image, -24, 20, 0, 200); // X, Y, w, h

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
	header('content-disposition: attachment; filename="HeyMP-poster-'.preg_replace('/[^a-z]/i', '-', strtolower($city)).'.pdf"');
	echo $pdf->Output('S');
}
?>