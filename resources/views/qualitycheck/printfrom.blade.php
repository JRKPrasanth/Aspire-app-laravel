<?php

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetTitle('Production ');
$pdf->SetAutoPageBreak(true, 0);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}
 // create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  
// add a page
$resolution= array(215, 307);
$pdf->AddPage('P', $resolution);
 
//Function to convert Amount in words
function convert_number_to_words($number)
{
$no = round($number);
$point = round($number - $no, 2) * 100;
$hundred = null;
$digits_1 = strlen($no);
$i = 0;
$str = array();
$words = array('0' => '', '1' => 'One', '2' => 'Two',
'3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
'7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
'10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
'13' => 'Thirteen', '14' => 'Fourteen',
'15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
'18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
'30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
'60' => 'Sixty', '70' => 'Seventy',
'80' => 'Eighty', '90' => 'Ninety');
$digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
while ($i < $digits_1) 
{
$divider = ($i == 2) ? 10 : 100;
$number = floor($no % $divider);
$no = floor($no / $divider);
$i += ($divider == 10) ? 1 : 2;
if ($number) {
$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
$str [] = ($number < 21) ? $words[$number] .
" " . $digits[$counter] . $plural . " " . $hundred:
$words[floor($number / 10) * 10]
. " " . $words[$number % 10] . " "
. $digits[$counter] . $plural . " " . $hundred;
} else $str[] = null;
}
$str = array_reverse($str);
$result = implode('', $str);
$points = ($point) ?
"." . $words[$point / 10] . " " . 
$words[$point = $point % 10] : '';

return $result . "Rupees Only";
}

$pdf->SetAutoPageBreak(true, 0);
// set image scale factor
//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', '', 12);
class MYPDF extends TCPDF { 

//Page header
public function Header() {
// Logo
$image_file = public_path().'/images/jrks.png';
$this->Image($image_file, 15  , 8, 35, 20, 'PNG', '', 'T', false, 250, '', false, false, 0, false, false, false);
// Set font
$this->SetFont('helvetica', 'B', 14);
}

// Page footer
public function Footer() {
// Position at 15 mm from bottom
$this->SetY(-8);
// Set font
$this->SetFont('helvetica', '', 8);
// Page number
$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');     
}    
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// add a page
$pdf->AddPage('P', $resolution);

$pdf->SetHeaderData('', '', 'Batch Card', '');
$pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);
$pdf->Line(12, 31, 208, 31, $style);

$pdf->SetXY(65,10);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->SetFillColor('32','100','150');
//$pdf->Rect(12, 29.8, 196, 6, 'F');
$pdf->Cell(0,0, $company_name ,0,1,'L');

$pdf->SetXY(100,20);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->SetFillColor('32','100','150');
//$pdf->Rect(12, 29.8, 196, 6, 'F');
$pdf->Cell(0,0, $city. " - " . $pincode ,0,1,'L');


$pdf->SetXY(15,32);
$pdf->SetFont('','b','12');
$pdf->SetTextColor('0','0','0');  
$pdf->Cell(0,0, "Product Description :");
$pdf->SetXY(59,32);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');  
$pdf->MultiCell(85,10,$product_description ,0,'L');

$pdf->SetXY(15,40);
$pdf->SetFont('','b','12');
$pdf->SetTextColor('0','0','0');  
$pdf->Cell(0,0, "Product Code :");

$pdf->SetXY(48,40);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');  
$pdf->MultiCell(85,12,$product_code ,0,'L');

$pdf->SetXY(15,46);
$pdf->SetFont('','b','12');
$pdf->SetTextColor('0','0','0');  
$pdf->Cell(68,12, "Batch No :");

$pdf->SetXY(38,49);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');  
$pdf->MultiCell(90,10,$batch_no ,0,'');

$pdf->SetXY(15,55);
$pdf->SetFont('','b','12');
$pdf->SetTextColor('0','0','0');  
$pdf->Cell(68,12, "Date :");

$pdf->SetXY(30,58);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');  
$pdf->MultiCell(50,10,$date ,0,'L');



    $pdf->SetXY(13,65);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');  
    $pdf->Cell(60,12, "S.No" ,0,'L');
    
    $pdf->SetXY(48,65);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');  
    $pdf->Cell(60,12, "Tests" ,0,'L');

    $pdf->SetXY(120,65);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');  
    $pdf->Cell(60,12, "Specification" ,0,'L'); 

   

    $pdf->SetXY(175,65);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');  
    $pdf->Cell(60,12, "Actual" ,0,'L'); 

   

/* End */

$pdf->SetXY(140,42);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(65,12,$company_address,'0','L');

$pdf->Line(12,65,208,65);
$pdf->Line(12,75,208,75);
$pdf->Line(29,65,29,245);
$pdf->Line(110,65,110,270);
$pdf->Line(160,65,160,270);
//$pdf->Line(160,65,160,245);
//$pdf->Line(185,65,185,245);
$pdf->Line(12,245,208,245);
$pdf->Line(12,270,208,270);



/***************** Lines data *************/
$i=0;
$oldY=0;
$xx=235;
$yy=225;
foreach($linedata as $key=>$value) 
{ 
  // dd($value);
    $i++;
    $j=0;
    //$lines=array($value['product'],$value['hsn_code'],$value['disp_name'],$value['gst'],$value['qty'],$value['unit_price'],$value['amount']);
    //$lines_value=implode(' ',$lines);
   
    $pdf->SetXY($xx-218,$yy-148);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(35,2,$i,'0','L');

    $pdf->SetXY($xx-203,$yy-148);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(75,7,$value->parameter,'0','L');
 
    $pdf->SetXY($xx-125,$yy-148);
    $pdf->SetFont('','','8.5');
    $pdf->SetTextColor('0','0','0');
	if($value->lookup_code=="BETWEEN"){
		 $pdf->MultiCell(55,9,$value->spec_value_from.' to '.$value->spec_value_to,'0','L');
	}else if($value->lookup_code=="GREATER THAN"){
		 $pdf->MultiCell(55,9,' > '.$value->spec_value_to,'0','L');
	}else if($value->lookup_code=="LESS THAN"){
		 $pdf->MultiCell(55,9,' < '.$value->spec_value_to,'0','L');
	}else if($value->lookup_code=="EQUAL"){
		 $pdf->MultiCell(55,9,' = '.$value->spec_value_to,'0','L');
	}else if($value->lookup_code=="NOR"){
		 $pdf->MultiCell(55,9,$value->spec_value_to,'0','L');
	}else if($value->lookup_code=="NOT MORE THAN"){
		 $pdf->MultiCell(55,9,'NOT MORE THAN '.$value->spec_value_to,'0','L');
	}else if($value->lookup_code=="PASS/FAIL"){
		 $pdf->MultiCell(55,9,$value->spec_value_to,'0','L');
	}else if($value->lookup_code=="NOT LESS THAN"){
		 $pdf->MultiCell(55,9,'NOT LESS THAN '.$value->spec_value_to,'0','L');
	}else if($value->lookup_code=="PLUS OR MINUS"){
		 $pdf->MultiCell(55,9,'PLUS OR MINUS '.$value->spec_value_to,'0','L');
	}else if($value->lookup_code=="NOT APPLICABLE"){
		 $pdf->MultiCell(55,9,$value->spec_value_to,'0','L');
	}
	
   

  

    
    
    $pdf->SetXY($xx-73,$yy-148);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(55,9,$value->measurement,'0','L');

  
    
    //$xx=$xx;
    $yy=$yy+10;
 
   
}

$pdf->SetXY(15,245);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');  
$pdf->Cell(90,12, "Disposition" ,0,'L');

$pdf->SetXY(15,260);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');  
$pdf->Cell(0,0, $status ,0,'L');

$pdf->SetXY(110,249);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');  
$pdf->Cell(0,0, "Correction:" ,0,'L');

$pdf->SetXY(130,249);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');  
$pdf->MultiCell(30,10, $correction ,0,'L');

$pdf->SetXY(80,260);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');  
$pdf->Cell(0,0, "" ,0,'L');

$pdf->SetXY(104,260);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');  
$pdf->MultiCell(35,10, '' ,0,'L');

$pdf->SetXY(100,282);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');  
$pdf->Cell(0,0, "Q.C Chemist" ,0,'L');

$pdf->SetXY(165,282);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');  
$pdf->Cell(0,0, "Incharge" ,0,'L');





//ob_end_clean();
  
//dd($print);
   
  if($print=="PRINT")
{     
    ob_end_clean();
 $pdf->Output('name.pdf','I'); 
  exit;
  
}
else
{
$filename="Uploads/PO_".$po_number.".pdf";
  $pdf->Output('Uploads/poheader/PO_'.$po_number.'.pdf', 'F');

}
    $pdf->close(); 




/* end */
//$pdf->Output('example_007.pdf', 'FI');
//exit();
//============================================================+
// END OF FILE
//============================================================+

/******************************************************************************************/ 
?>
