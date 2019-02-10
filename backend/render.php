<?php
use setasign\Fpdi\Fpdi;

require_once(dirname(__FILE__) . '/libraries/fpdf/fpdf.php'); 
require_once(dirname(__FILE__) . '/libraries/fpdi/src/autoload.php');

// initiate FPDI
$pdf = new Fpdi();
// add a page
$pdf->AddPage('L', 'A4');
// set the source file
$pdf->setSourceFile(dirname(__FILE__) . '/template.pdf');
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at position 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx, 0, 0, 297);

// now write some text above the imported page
$pdf->AddFont('PermanentMarker','','PermanentMarker-Regular.php');
$pdf->SetFont('PermanentMarker', '', 80);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(100, 25);
$pdf->Write(20, urldecode($_SERVER['QUERY_STRING']));

$pdf->Output('D','LoveEU-poster.pdf');
?>