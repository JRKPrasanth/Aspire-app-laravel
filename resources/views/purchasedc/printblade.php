<?php
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
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
// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}
// set font
$pdf->SetFont('times', '', 12);
class MYPDF extends TCPDF { 
public $company_logo_name;

    public function setLogo($company_logo){
        $this->logo = $company_logo;
    }
//Page header
public function Header() {
// Logo
$image_file = public_path().'/images/'.$this->logo;
$this->Image($image_file, 15	, 8, 35, 15, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
}
// Page footer
public function Footer() {
// Position at 15 mm from bottom
$this->SetY(-8);
// Set font
$this->SetFont('helvetica', '', 8);
$total = $this->getNumPages();
// Page number
$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');     
}    
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setLogo($company_logo_name);//Logo
// add a page
$pdf->AddPage('P', $resolution);

$pdf->SetHeaderData('', '', 'DELIVERY CHALLAN', '');
$pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);
$pdf->Line(12, 25, 208, 25, $style);

$pdf->SetXY(75,10);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->SetFillColor('32','100','150');
$pdf->Cell(0,0, "DELIVERY CHALLAN" ,0,1,'L');


/* supplier address with supplier name */

$pdf->SetXY(15,25);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(123,0, $company_name ,0,'L');

$pdf->SetXY(15,35);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(85,13, "".$company_address ,0,'L');

$pdf->SetXY(15,47);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(68,10, "GSTIN No. :".$gst_no ,0,'L');

$pdf->SetXY(128,27);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');	
$pdf->Cell(0,0, "TO" ,0,1,'L');

$pdf->SetXY(128,32);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(70,12, "M/s. ".$supplier_name ,0,'L');

$pdf->SetXY(128,37);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');    
$pdf->MultiCell(80,20, "".$supplier_address ,0,'L');

$pdf->SetXY(128,58);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(80,0, "GST NO: ".$sup_gst_no ,0,'L');

$pdf->SetXY(15,67);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(60,12, "Please receive the following :-" ,0,'L');



$header=array('No.','DESCRIPTION','','QUANTITY','','BATCH.NO','','REASON','','RECEIVE DATE');
$x=235;$y=235;
foreach($header as $key=>$value)
{
    $pdf->SetXY($x-218,78);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');	
    $pdf->MultiCell(60,12, " ".$value ,0,'L');
    $x=$x+18;
    
}
/* End */

$pdf->SetXY(140,42);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');

$pdf->Line(12,75,208,75);
$pdf->Line(12,85,208,85);
$pdf->Line(26,75,26,290);
$pdf->Line(72,75,72,290);
$pdf->Line(95,75,95,290);
$pdf->Line(130,75,130,290);
$pdf->Line(180,75,180,290);
$pdf->Line(12,65,208,65);

/***************** Lines data *************/
$i=0;
$oldY=0;
$xx=235;
$yy=235;
foreach($linedata as $key=>$value) 
{  
    if($oldY==0) $y =86;
	else $y = $oldY+1;	
    $i++;
    $j=0;
   
    $pdf->SetXY(16,$y+1);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->Cell(0,0, $i ,0,1,'L');
	$y=$y;

	$pdf->SetXY(30,$y);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(47,2, $value->product_id ,0,'L');

	$pdf->SetXY(80,$y);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(65,10, $value->qty ,0,'L');
	$oldY=$pdf->getY();
		
        $pdf->SetXY(105,$y);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(47,2, $value->batch ,0,'L');
        
	$pdf->SetXY(145,$y);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(47,2, $value->comments ,0,'L');
        
        
        
        $pdf->SetXY(187,$y);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(47,2, $value->receive_date ,0,'L');
        
    $yy=$yy+10;
    
 if($oldY > 280)
		{
     /*Multipage Start*/
		$oldY =0;
$pdf->addPage();
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

$pdf->SetXY(75,15);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->SetFillColor('32','100','150');
$pdf->Cell(0,0, "DELIVERY CHALLAN" ,0,1,'L');





/************************************Header *********************************/

$pdf->SetXY(15,32);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(0,0, $company_name ,0,'L');

$pdf->SetXY(15,40);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(85,12, "".$company_address ,0,'L');

$pdf->SetXY(15,55);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(68,12, "GSTIN No. :".$gst_no ,0,'L');

$pdf->SetXY(138,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->Cell(0,0, "TO" ,0,1,'L');

$pdf->SetXY(140,38);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(80,12, "M/s. ".$supplier_name ,0,'L');

$pdf->SetXY(140,43);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');    
$pdf->MultiCell(60,12, "".$supplier_address ,0,'L');

$pdf->SetXY(140,55);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(60,12, "GST NO: ".$sup_gst_no ,0,'L');

$pdf->SetXY(15,67);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(60,12, "Please receive the following :-" ,0,'L');



$header=array('No.','DESCRIPTION','','QUANTITY','','BATCH.NO','','REASON','','RECEIVE DATE');
$x=235;$y=235;
foreach($header as $key=>$value)
{
    $pdf->SetXY($x-218,78);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');	
    $pdf->MultiCell(60,12, " ".$value ,0,'L');
    $x=$x+18;
    
}
/* End */

$pdf->Line(12,75,208,75);
$pdf->Line(12,85,208,85);
$pdf->Line(26,75,26,290);
$pdf->Line(72,75,72,290);
$pdf->Line(95,75,95,290);
$pdf->Line(130,75,130,290);
$pdf->Line(180,75,180,290);
$pdf->Line(12,65,208,65); 
}
}
/**********************************************Footer Content *************************************************/
$total = $pdf->getNumPages();
if($pdf->pageNo()==$total)
{	
if($oldY < 245)
{
$pdf->Rect(12, 247, 196, 43,'DF', "",  array(255, 255, 255));


$pdf->SetXY(15,250);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(90,12, "Received the above material in good condition" ,0,'L');

$pdf->SetXY(15,260);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(90,12, "Signature  ................................" ,0,'L');

$pdf->SetXY(15,270);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(90,12, "Date          ................................" ,0,'L');

$pdf->SetXY(40,268);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(90,12, "".$date ,0,'L');

$pdf->SetXY(105,250);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(104,12, "For  ".$company_name ,0,'L');

}
else
{
    
$pdf->addPage();
	$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);
$pdf->Line(12, 31, 208, 31, $style);

$pdf->SetXY(75,15);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->SetFillColor('32','100','150');
//$pdf->Rect(12, 29.8, 196, 6, 'F');
$pdf->Cell(0,0, "DELIVERY CHALLAN" ,0,1,'L');


/* supplier address with supplier name */

$pdf->SetXY(15,32);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(0,0, $company_name ,0,'L');

$pdf->SetXY(15,40);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(85,12, "".$company_address ,0,'L');

$pdf->SetXY(15,55);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(68,12, "GSTIN No. :".$gst_no ,0,'L');

$pdf->SetXY(138,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->Cell(0,0, "TO" ,0,1,'L');

$pdf->SetXY(140,38);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(80,12, "M/s. ".$supplier_name ,0,'L');

$pdf->SetXY(140,43);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');    
$pdf->MultiCell(60,12, "".$supplier_address ,0,'L');

$pdf->SetXY(140,55);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(60,12, "GST NO: ".$sup_gst_no ,0,'L');

$pdf->SetXY(15,67);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(60,12, "Please receive the following :-" ,0,'L');



	
	$pdf->SetXY(20,120);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "<--------------------------------------------------------End of Page----------------------------------------------------->" ,0,1,'L');


$pdf->Line(12,75,208,75);
$pdf->Line(12,85,208,85);
$pdf->Line(26,75,26,290);
$pdf->Line(72,75,72,290);
$pdf->Line(95,75,95,290);
$pdf->Line(130,75,130,290);
$pdf->Line(180,75,180,290);
$pdf->Line(12,65,208,65); 	



/*Multipage Footer*/
$pdf->Rect(12, 247, 196, 43,'DF', "",  array(255, 255, 255));
$pdf->SetXY(15,250);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(90,12, "Received the above material in good condition" ,0,'L');

$pdf->SetXY(15,260);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(90,12, "Signature  ................................" ,0,'L');

$pdf->SetXY(15,270);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(90,12, "Date          ................................" ,0,'L');

$pdf->SetXY(40,268);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(90,12, "".$date ,0,'L');

$pdf->SetXY(105,250);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(104,12, "For  ".$company_name ,0,'L');
}
}



//------------------------------------------------------------------





ob_end_clean();
  
// dd($print);
   
if($print=="PRINT")
{			
	ob_end_clean();
    $pdf->Output('name.pdf','I'); 
	exit;	
}
else
{
    $filename="Uploads/PO_".$return_invoice_number.".pdf";
    $pdf->Output('uploads/purchasereturn/P_'.$return_invoice_number.'.pdf', 'F');
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
