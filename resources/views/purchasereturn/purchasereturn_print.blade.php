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
public $company_logo_name;

    public function setLogo($company_logo){
        $this->logo = $company_logo;
    }
//Page header
public function Header() {
// Logo
//$image_file = public_path().'/images/jrks.png';
$image_file = public_path().'/images/'.$this->logo;

$this->Image($image_file, 15	, 10, 25, 15, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
// Set font

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
$pdf->setLogo($company_logo_name);
// add a page
$pdf->AddPage('P', $resolution);

$pdf->SetHeaderData('', '', 'PO PRINT', '');
$pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);
$pdf->Line(12, 26, 208, 26, $style);


$pdf->SetXY(12,26);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->SetFillColor('32','100','150');
//$pdf->Rect(12, 29.8, 196, 6, 'F');
//$pdf->Cell(195,0, "RETURNABLE CHALLAN" ,0,1,'C');
$pdf->Cell(195,0, "PURCHASE RETURN INVOICE" ,0,1,'C');

//"Revision: ".$return_date,"Date: ".$return_date
/* headrer  company name with supplier address*/
$header=array("RTV No : ".$return_invoice_number,"Po No : ".$po_number ,"Grn No:".$grn_number,"Qc No : ".$qc_number,"PO Invoice No : ".$bill_number);
$x=227;$y=235;
foreach($header as $key=>$value)
{	
	$pdf->Ln();
	$pdf->SetXY(105,$y-202);
	$pdf->SetFont('','','11');
	$pdf->MultiCell(55, 2, $value, 0, 'L');
//	$y=$pdf->getY();
	$y=$y+5;
	
}
/* End */

/* header rno and date */

$header=array("Revision: ".$return_date,"Po Date : ".$po_date,"Grn Date: ".$dc_date,"Qc Date : ".$qc_date,"Bill Date : ".$invoice_date);
$x=235;$y=235;
foreach($header as $key=>$value)
{	
	$pdf->Ln();
	$pdf->SetXY(160,$y-202);
	$pdf->SetFont('','','12');
	$pdf->MultiCell(45, 2, $value, 0, 'L');
	//$y=$pdf->getY();
	$y=$y+5;
	
}

/* End */

/* supplier address with supplier name */

// $pdf->SetXY(176,10);
// $pdf->SetFont('','','12');
// $pdf->SetTextColor('0','0','0');	
// $pdf->MultiCell(0,0, $return_invoice_number ,0,'L');

// $pdf->SetXY(176,15);
// $pdf->SetFont('','','12');
// $pdf->SetTextColor('0','0','0');	
// $pdf->MultiCell(0,0, $return_date ,0,'L');

$pdf->SetXY(50,8);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(0,0, $company_name ,0,'L');

$pdf->SetXY(50,12);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(90,2, "".$company_address ,0,'L');

//$y=$pdf->getY();
$pdf->SetXY(140,20);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(0,0, "GSTIN No. :".$gst_no ,0,'L');

$pdf->SetXY(15,28);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');	
$pdf->Cell(0,0, "TO" ,0,1,'L');

$pdf->SetXY(15,32);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(80,12, "M/s. ".$supplier_name ,0,'L');

$pdf->SetXY(15,36);
$pdf->SetFont('','','8.5');
$pdf->SetTextColor('0','0','0');    
$pdf->MultiCell(70,12, "".$supplier_address ,0,'L');
//$y=$pdf->getY();
$pdf->SetXY(15,60);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(70,0, "GST NO: ".$sup_gst_no ,0,'L');

$pdf->SetXY(15,67);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(60,12, "Please receive the following" ,0,'L');

$pdf->SetXY(140,67);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(60,12, "J.C. No" ,0,'L');

//$header=array('No.','DESCRIPTION','','','','QTY','UNIT PRICE','','','Amount(Rs.)','','P.');
$x=235;$y=235;
/*foreach($header as $key=>$value)
{
    $pdf->SetXY($x-220,78);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');	
    $pdf->MultiCell(60,12, " ".$value ,0,'L');
    $x=$x+18;
    
}*/

$pdf->SetXY($x-220,78);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');	
    $pdf->MultiCell(60,12, " No." ,0,'L');
    $x=$x+18;

$pdf->SetXY($x-205,78);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');	
    $pdf->MultiCell(60,12, "DESCRIPTION" ,0,'L');
    $x=$x+18;

$pdf->SetXY($x-165,78);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');	
    $pdf->MultiCell(60,12, "QTY" ,0,'L');
    $x=$x+18;
    
    $pdf->SetXY($x-171,78);
    $pdf->SetFont('','B','8');
    $pdf->SetTextColor('0','0','0');	
    $pdf->MultiCell(60,12, "UNIT PRICE" ,0,'L');
    $x=$x+18;

$pdf->SetXY($x-145,78);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');	
    $pdf->MultiCell(60,12, "Amount(Rs.)" ,0,'L');
    $x=$x+18;
    
    $pdf->SetXY($x-130,78);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');	
    $pdf->MultiCell(60,12, "P." ,0,'L');
    $x=$x+18;

  $pdf->SetXY(140,78);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');	
    $pdf->MultiCell(60,12,$linedata[0]->tax_group_id ,0,'L');
    $x=$x+18;



/* End */
/*Lines*/
$pdf->Line(12,75,208,75);
$pdf->Line(12,85,208,85);
$pdf->Line(26,75,26,290);
$pdf->Line(103,75,103,290);
$pdf->Line(118,75,118,290);
$pdf->Line(136,75,136,290);
$pdf->Line(158,75,158,290);
$pdf->Line(190,75,190,290);
$pdf->Line(12,65,208,65);

/***************** Lines data *************/
$i=0;
$oldY=0;
$xx=235;
$yy=235;
//dd($linedata);
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

	$pdf->SetXY(105,$y);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(47,2, $value->reject_qty ,0,'L');

    $pdf->SetXY(123,$y);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(60,7, $value->unit_price ,0,'L');

	$pdf->SetXY(27,$y);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(75,10, $value->product_id ,0,'L');
	$oldY=$pdf->getY();
		
	$pdf->SetXY(165,$y);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(47,2, $value->amount ,0,'L');
        
		
	$pdf->SetXY(137,$y);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(47,2, $value->tax_group_id ,0,'L');
        
        $pdf->SetXY(195,$y);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(47,2, '00' ,0,'L');
        
    $yy=$yy+10;
    
 if($oldY > 280)
		{
		$oldY =0;
$pdf->addPage();
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

$pdf->SetXY(66,15);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->SetFillColor('32','100','150');
//$pdf->Rect(12, 29.8, 196, 6, 'F');
//$pdf->Cell(0,0, "RETURNABLE CHALLAN" ,0,1,'L');
$pdf->Cell(0,0, "PURCHASE RETURN INVOICE" ,0,1,'L');


/* headrer  company name with supplier address*/
$header=array("PO Invoice No:","Revision: ","Date: ".$return_date);
$x=235;$y=235;
foreach($header as $key=>$value)
{	
	$pdf->Ln();
	$pdf->SetXY(135,$y-225);
	$pdf->SetFont('','','12');
	$pdf->MultiCell(45, 2, $value, 0, 'L', 0, 0);
	$y=$y+5;
	
}
/* End */

/* header rno and date */

$header=array("R.no:","Date:");
$x=235;$y=235;
foreach($header as $key=>$value)
{	
	$pdf->Ln();
	$pdf->SetXY(175,$y-225);
	$pdf->SetFont('','','12');
	$pdf->MultiCell(30, 2, $value, 0, 'L', 0, 0);
	$y=$y+5;
	
}

/* End */
/************************************Header *********************************/

$pdf->SetXY(15,30);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(0,0, $company_name ,0,'L');

$pdf->SetXY(15,38);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(85,12, "".$company_address ,0,'L');

$pdf->SetXY(15,50);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(68,12, "GSTIN No. :".$gst_no ,0,'L');

$pdf->SetXY(133,28);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->Cell(0,0, "TO" ,0,1,'L');

$pdf->SetXY(135,38);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(80,12, "M/s. ".$supplier_name ,0,'L');

$pdf->SetXY(135,43);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');    
$pdf->MultiCell(60,12, "".$supplier_address ,0,'L');

$pdf->SetXY(135,55);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(60,12, "GST NO: ".$sup_gst_no ,0,'L');

$pdf->SetXY(15,67);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(60,12, "Please receive the following" ,0,'L');

$pdf->SetXY(140,67);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(60,12, "J.C. No" ,0,'L');

$header=array('No.','DESCRIPTION','','','QUANTITY','','','','Amount(Rs.)','','P.');
$x=235;$y=235;
foreach($header as $key=>$value)
{
    $pdf->SetXY($x-220,78);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');	
    $pdf->MultiCell(60,12, " ".$value ,0,'L');
    $x=$x+18;
    
}



/* End */

$pdf->SetXY(140,42);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(65,12,$company_address,'0','L');


$pdf->Line(12,75,208,75);
$pdf->Line(12,85,208,85);

$pdf->Line(26,75,26,290);
$pdf->Line(58,75,58,290);
//$pdf->Line(112,108,112,225);
//$pdf->Line(130,108,130,225);
$pdf->Line(150,75,150,290);
//$pdf->Line(170,108,170,225);
$pdf->Line(190,75,190,290);
//$pdf->Line(12,245,208,245);

$pdf->Line(12,65,208,65);   
}
}
/**********************************************Footer Content *************************************************/
$total = $pdf->getNumPages();
if($pdf->pageNo()==$total)
{	
if($oldY < 220)
{
$pdf->Rect(12, 220, 196, 70,'DF', "",  array(255, 255, 255));

$arr = explode(' ',trim($linedata[0]->tax_group_id));
//dd($arr[1]);
if($arr[0]=='GST')
{
    $a=rtrim($arr[1],'%');
$taxes=$tot+$tax;
$amount=round($taxes,2);
$amounts=round($taxes,0);

$roundoff=$amount-$amounts;
//dd($amount);
    $per=$a/2;
   // dd($per);
    $tax=$tax/2;
    
$pdf->SetXY(60,228);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(100,2, "CGST AMOUNT ".$per."%"  ,0,'R');
    
$pdf->SetXY(85,228);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(100,2,$tax,0,'R');
  
$pdf->SetXY(60,233);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(100,2, "SGST AMOUNT ".$per."%" ,0,'R');

$pdf->SetXY(60,239);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(100,2, "Round off" ,0,'R');

$pdf->SetXY(85,239);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(100,2, $roundoff ,0,'R');

$pdf->SetXY(13,240);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Amount Chargeable (in words)",0,'L');

$pdf->SetXY(13,244);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, convert_number_to_words(round($amounts)),0,'L');

$pdf->SetXY(60,245);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(100,2, "Total Amount" ,0,'R');
$amounts=number_format($amounts,2);


$pdf->SetXY(85,245);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(105,2, $amounts ,0,'R');

$pdf->SetXY(85,233);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(100,2,$tax ,0,'R');
$pdf->Line(168,250,190,250);
$pdf->Line(168,244,190,244);
$pdf->Line(168,238,190,238);


}
if($arr[0]=='IGST')
{
    $a=rtrim($arr[1],'%');
$taxes=$tot+$tax;
$amount=round($taxes,2);
$amounts=round($taxes,0);

$roundoff=$amount-$amounts;
//dd($amount);
    $per=$a/2;
   // dd($per);
    $tax=$tax;
    
$pdf->SetXY(60,232);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(100,2, "IGST AMOUNT ".$a."%"  ,0,'R');
    
$pdf->SetXY(85,232);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(100,2,$tax,0,'R');

$pdf->SetXY(60,239);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(100,2, "Round off" ,0,'R');

$pdf->SetXY(85,239);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(100,2, $roundoff ,0,'R');

$pdf->SetXY(13,240);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Amount Chargeable (in words)",0,'L');

$pdf->SetXY(13,244);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, convert_number_to_words(round($amounts)),0,'L');

$pdf->SetXY(60,245);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(100,2, "Total Amount" ,0,'R');
$amounts=number_format($amounts,2);
$pdf->SetXY(85,245);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(100,2, $amounts ,0,'R');

$pdf->Line(168,250,190,250);
$pdf->Line(168,244,190,244);

}

$pdf->SetXY(15,255);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(90,12, "Received the above material in good condition" ,0,'L');

$tot=number_format($tot,2);

$pdf->SetXY(165,221);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(24,2, $tot ,0,'R');

$pdf->SetXY(135,221);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(20,2, "TOTAL" ,0,'R');

$pdf->Line(12,226,208,226);   


$pdf->SetXY(15,263);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(90,12, "Signature  ................................" ,0,'L');

$pdf->SetXY(15,273);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(90,12, "Date          ................................" ,0,'L');

$pdf->SetXY(40,270);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(90,12, "".$return_date ,0,'L');

$pdf->SetXY(105,255);
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
$pdf->Line(12, 26, 208, 26, $style);

$pdf->SetXY(68,15);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->SetFillColor('32','100','150');
//$pdf->Rect(12, 29.8, 196, 6, 'F');
//$pdf->Cell(0,0, "RETURNABLE CHALLAN" ,0,1,'L');
$pdf->Cell(0,0, "PURCHASE RETURN INVOICE" ,0,1,'L');


/* headrer  company name with supplier address*/
$header=array("PO Invoice No:","Revision: ","Date: ".$return_date);
$x=235;$y=235;
foreach($header as $key=>$value)
{	
	$pdf->Ln();
	$pdf->SetXY(135,$y-225);
	$pdf->SetFont('','','12');
	$pdf->MultiCell(45, 2, $value, 0, 'L', 0, 0);
	$y=$y+5;
	
}
/* End */

/* header rno and date */

$header=array("R.no:","Date:");
$x=235;$y=235;
foreach($header as $key=>$value)
{	
	$pdf->Ln();
	$pdf->SetXY(175,$y-225);
	$pdf->SetFont('','','12');
	$pdf->MultiCell(30, 2, $value, 0, 'L', 0, 0);
	$y=$y+5;
	
}

/* End */

/* supplier address with supplier name */

$pdf->SetXY(15,30);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(0,0, $company_name ,0,'L');

$pdf->SetXY(15,38);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(85,12, "".$company_address ,0,'L');

$pdf->SetXY(15,50);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(68,12, "GSTIN No. :".$gst_no ,0,'L');

$pdf->SetXY(133,28);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->Cell(0,0, "TO" ,0,1,'L');

$pdf->SetXY(135,38);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(80,12, "M/s. ".$supplier_name ,0,'L');

$pdf->SetXY(135,43);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');    
$pdf->MultiCell(60,12, "".$supplier_address ,0,'L');

$pdf->SetXY(135,55);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(60,12, "GST NO: ".$sup_gst_no ,0,'L');

$pdf->SetXY(15,67);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(60,12, "Please receive the following" ,0,'L');

$pdf->SetXY(140,67);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(60,12, "J.C. No" ,0,'L');

	
	$pdf->SetXY(20,120);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "<--------------------------------------------------------End of Page----------------------------------------------------->" ,0,1,'L');


$pdf->Line(12,75,208,75);
$pdf->Line(12,85,208,85);
$pdf->Line(26,75,26,290);
$pdf->Line(58,75,58,290);
$pdf->Line(150,75,150,290);
$pdf->Line(190,75,190,290);
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





//ob_end_clean();
  
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
