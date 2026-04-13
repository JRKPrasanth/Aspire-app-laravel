<?php
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// set auto page breaks
$pdf->SetAutoPageBreak(true, 0);
// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
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
" " . $digits[$counter] . $plural . " " . $hundred
:
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
// set font
$pdf->SetFont('times', '', 12);

class MYPDF extends TCPDF { 

//Page header
    public function Header() {
        // Logo
        $image_file = public_path().'/images/premiumlogo.jpg';
       $this->Image($image_file, 10 , 6, 48, 25, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
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
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);


$pdf->Line(12, 31, 208, 31, $style);
$pdf->Line(12, 38, 208, 38, $style);

$pdf->SetXY(85,9);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');	
$pdf->Cell(0,0, $company_name ,0,1,'L'); 	

$pdf->SetXY(90,15);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(110,0,$company_address,'0','L');

$pdf->SetXY(85,32);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('255','255','255');
$pdf->SetFillColor('32','100','150');
$pdf->Rect(12, 29.8, 196, 8, 'F');
//$pdf->SetFillColor('36','108','164');
$pdf->Cell(104,0, "SALES ORDER" ,0,1,'M');

$pdf->SetXY(15,40);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Order No" ,0,1,'L');   


$pdf->SetXY(45,40);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,": ".$sales_order_no ,0,1,'L');   


$pdf->SetXY(45,47);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,": ".$sales_order_date ,0,1,'L'); 

 

$pdf->SetXY(15,47);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Date" ,0,1,'L');

$pdf->SetXY(128,47);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, ": ".$customer_po_date ,0,1,'L');

$pdf->SetXY(15,54);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Customer Name" ,0,1,'L');


$pdf->SetXY(45,54);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0,": ".$bcustomer ,0,1,'L');
$pdf->MultiCell(55,0,": ".$customer_name,'0','L');

$pdf->SetXY(108,67);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(85,2,"".$ship_to_address ,0,'L');

$pdf->SetXY(15,67);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(85,2,"".$bill_to_address ,0,'L');



$pdf->SetXY(108,40);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ref No" ,0,1,'L');



$pdf->SetXY(128,40);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ": ".$so_ref_no ,0,1,'L');

$pdf->SetXY(108,47);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ref Date" ,0,1,'L');

$pdf->SetXY(128,47);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ":" ,0,1,'L');
     
$pdf->SetXY(110,50);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "" ,0,1,'L');     
     
$pdf->SetXY(108,54);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Valid Upto" ,0,1,'L');
     
$pdf->SetXY(128,54);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ":" ,0,1,'L');     
     
   
     
   
 $pdf->Line(12, 61, 208, 61, $style);
 
 
 
 $pdf->Line(12, 95, 208, 95, $style);
 
 
 
 
 
 $pdf->Line(12, 105, 208, 105, $style);

//$pdf->Line(12, 210, 208, 210, $style);

//$pdf->Line(12, 218, 208, 218, $style);
 

$pdf->SetXY(15,62);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Bill To Address" ,0,1,'L');

$pdf->SetXY(108,62);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ship To Address" ,0,1,'L');



$pdf->SetXY(14,98);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "S.No" ,0,1,'L');


 

 
  $pdf->SetXY(36,98);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Product Name" ,0,1,'L');
 
 $pdf->SetXY(108,96);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Packing" ,0,'L');

$pdf->SetXY(110,100);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Size" ,0,'L');


$pdf->SetXY(128,96);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Sales " ,0,'L');

 
 $pdf->SetXY(130,100);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Rate " ,0,'L');


 $pdf->SetXY(148,97);
$pdf->SetFont('','B','10');	
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "MRP " ,0,'L');
 

$pdf->SetXY(168,96);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "No Of " ,0,'L');



$pdf->SetXY(168,100);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Packs" ,0,'L');



$pdf->SetXY(188,97);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Amount" ,0,'L');



$i=0;
$oldY=0;
$sub_total=0;
$net_amount=0;
$pack=0;
foreach($line as $key=>$value)
{	

$i++;
if($oldY==0) $y =103;
else $y = $oldY-5;
$i++;

//$sub_total=$value['unitprice']*$value['qty'];

$pdf->SetXY(15,$y+5);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, $value['line_no'],0,1,'L');

$y=$y+15;

$oldY=$pdf->getY()+5;

$pdf->SetXY(30,$y-10);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(77,10, $value['product'],0,'L');

$pdf->SetXY(127,$y-10);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, $value['unit_price'],0,'L');
	
$pdf->SetXY(170,$y-10);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, $value['qty'],0,'L');

$pdf->SetXY(187,$y-10);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, $value['amount'],0,'L');

//$net_amount=$sub_total+$net_amount;
$net_amount+=$value['amount'];
$pack+=$value['qty'];


//$pdf->Line(12, $y-5, 208, $y-5, $style);
if($oldY>=200){
$pdf->Line(165, 95, 165, 290);
$pdf->Line(145, 95, 145, 290);
$pdf->Line(185, 95, 185, 290);
$pdf->Line(25, 95, 25, 290); 
$pdf->Line(125, 95, 125, 290);
$pdf->Line(105, 38, 105, 290);
}
else
{
$pdf->Line(165, 95, 165, 210);
$pdf->Line(145, 95, 145, 210);
$pdf->Line(185, 95, 185, 210);
$pdf->Line(25, 95, 25, 210); 
$pdf->Line(125, 95, 125, 210);
$pdf->Line(105, 38, 105, 210);
}  



if($oldY>=275)
{

$pdf->AddPage();
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

 



$pdf->SetXY(85,9);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(0,0, $company_name ,0,'L'); 	

$pdf->SetXY(90,15);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(110,0,$company_address,'0','L');

$pdf->SetXY(85,32);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('255','255','255');
$pdf->SetFillColor('32','100','150');
$pdf->Rect(12, 29.8, 196, 8, 'F');
//$pdf->SetFillColor('36','108','164');
$pdf->Cell(104,0, "SALES ORDER" ,0,1,'M');


$pdf->SetXY(15,40);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Order No" ,0,1,'L');   


$pdf->SetXY(45,40);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,": ".$sales_order_no ,0,1,'L');   


$pdf->SetXY(45,47);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,": ".$sales_order_date ,0,1,'L'); 

 

$pdf->SetXY(15,47);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Date" ,0,1,'L');

$pdf->SetXY(128,47);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, ": ".$customer_po_date ,0,1,'L');



$pdf->SetXY(15,54);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Customer Name" ,0,1,'L');


$pdf->SetXY(45,54);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0,": ".$bcustomer ,0,1,'L');
$pdf->MultiCell(55,0,": ".$customer_name,'0','L');

$pdf->SetXY(108,67);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(85,2,"".$ship_to_address ,0,'L');



$pdf->SetXY(15,67);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(85,2,"".$bill_to_address ,0,'L');



$pdf->SetXY(108,40);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ref No" ,0,1,'L');



$pdf->SetXY(128,40);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ": ".$so_ref_no ,0,1,'L');

$pdf->SetXY(108,47);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ref Date" ,0,1,'L');

$pdf->SetXY(128,47);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ":" ,0,1,'L');
     
$pdf->SetXY(110,50);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "" ,0,1,'L');     
     
$pdf->SetXY(108,54);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Valid Upto" ,0,1,'L');
     
$pdf->SetXY(128,54);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ":" ,0,1,'L');     
     
   
     
   
 $pdf->Line(12, 61, 208, 61, $style);
 $pdf->Line(12, 95, 208, 95, $style);
 $pdf->Line(12, 105, 208, 105, $style);

//$pdf->Line(12, 210, 208, 210, $style);

//$pdf->Line(12, 218, 208, 218, $style);
 

$pdf->SetXY(15,62);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Bill To Address" ,0,1,'L');

$pdf->SetXY(108,62);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ship To Address" ,0,1,'L');



$pdf->SetXY(14,98);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "S.No" ,0,1,'L');


 

 
  $pdf->SetXY(36,98);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Product Name" ,0,1,'L');
 
 $pdf->SetXY(108,96);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Packing" ,0,'L');

$pdf->SetXY(110,100);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Size" ,0,'L');


$pdf->SetXY(128,96);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Sales " ,0,'L');

 
 $pdf->SetXY(130,100);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Rate " ,0,'L');


 $pdf->SetXY(148,97);
$pdf->SetFont('','B','10');	
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "MRP " ,0,'L');
 

$pdf->SetXY(168,96);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "No Of " ,0,'L');



$pdf->SetXY(168,100);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Packs" ,0,'L');



$pdf->SetXY(188,97);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Amount" ,0,'L');




$pdf->SetXY(189,212);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Multicell(104,0, $net_amount,0,'L');

$pdf->SetXY(170,212);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Multicell(104,0, $pack,0,'L');

$oldY=$pdf->getY()-105;

}
}



if($oldY>=200)
{
$pdf->AddPage();
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

$pdf->Line(12, 210, 208, 210, $style);
$pdf->Line(12, 218, 208, 218, $style);

$pdf->SetXY(85,9);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');	
$pdf->MultiCell(0,0, $company_name ,0,'L'); 	

$pdf->SetXY(90,15);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(110,0,$company_address,'0','L');

$pdf->SetXY(85,32);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('255','255','255');
$pdf->SetFillColor('32','100','150');
$pdf->Rect(12, 29.8, 196, 8, 'F');
//$pdf->SetFillColor('36','108','164');
$pdf->Cell(104,0, "SALES ORDER" ,0,1,'M');


$pdf->SetXY(15,40);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Order No" ,0,1,'L');   


$pdf->SetXY(45,40);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,": ".$sales_order_no ,0,1,'L');   


$pdf->SetXY(45,47);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,": ".$sales_order_date ,0,1,'L'); 

 

$pdf->SetXY(15,47);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Date" ,0,1,'L');

$pdf->SetXY(128,47);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, ": ".$customer_po_date ,0,1,'L');



$pdf->SetXY(15,54);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Customer Name" ,0,1,'L');


$pdf->SetXY(45,54);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0,": ".$bcustomer ,0,1,'L');
$pdf->MultiCell(55,0,": ".$customer_name,'0','L');

$pdf->SetXY(108,67);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(85,2,"".$ship_to_address ,0,'L');



$pdf->SetXY(15,67);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(85,2,"".$bill_to_address ,0,'L');



$pdf->SetXY(108,40);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ref No" ,0,1,'L');



$pdf->SetXY(128,40);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ": ".$so_ref_no ,0,1,'L');

$pdf->SetXY(108,47);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ref Date" ,0,1,'L');

$pdf->SetXY(128,47);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ":" ,0,1,'L');
     
$pdf->SetXY(110,50);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "" ,0,1,'L');     
     
$pdf->SetXY(108,54);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Valid Upto" ,0,1,'L');
     
$pdf->SetXY(128,54);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ":" ,0,1,'L');     
     
   
 $pdf->Line(12, 61, 208, 61, $style);
 $pdf->Line(12, 95, 208, 95, $style);
 $pdf->Line(12, 105, 208, 105, $style);

//$pdf->Line(12, 210, 208, 210, $style);

//$pdf->Line(12, 218, 208, 218, $style);
 

$pdf->SetXY(15,62);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Bill To Address" ,0,1,'L');

$pdf->SetXY(108,62);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ship To Address" ,0,1,'L');



$pdf->SetXY(14,98);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "S.No" ,0,1,'L');


 

 
  $pdf->SetXY(36,98);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Product Name" ,0,1,'L');
 
 $pdf->SetXY(108,96);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Packing" ,0,'L');

$pdf->SetXY(110,100);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Size" ,0,'L');


$pdf->SetXY(128,96);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Sales " ,0,'L');

 
 $pdf->SetXY(130,100);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Rate " ,0,'L');


 $pdf->SetXY(148,97);
$pdf->SetFont('','B','10');	
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "MRP " ,0,'L');
 

$pdf->SetXY(168,96);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "No Of " ,0,'L');



$pdf->SetXY(168,100);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Packs" ,0,'L');



$pdf->SetXY(188,97);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Amount" ,0,'L');



$pdf->SetXY(189,212);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Multicell(104,0, $net_amount,0,'L');

$pdf->SetXY(170,212);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, $pack,0,'L');

$pdf->SetXY(151,212);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Total" ,0,'L');

$pdf->SetXY(20,222);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Amount In Words" ,0,'L');

$pdf->SetXY(20,227);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0,convert_number_to_words(round($net_amount)),0,'L');

$pdf->SetXY(113,265);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "For" ,0,'L');

$pdf->SetXY(120,265);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, $company_name ,0,'L');

$pdf->SetXY(189,212);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, $net_amount,0,'L');

$pdf->SetXY(170,212);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, $pack,0,'L');

$pdf->SetXY(165,282);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Authorised Signatory" ,0,'L');

$pdf->Line(165, 95, 165, 210);
$pdf->Line(145, 95, 145, 210);
$pdf->Line(185, 95, 185, 210);
$pdf->Line(25, 95, 25, 210); 
$pdf->Line(125, 95, 125, 210);
$pdf->Line(105, 38, 105, 210);

}
else
{



//$pdf->Line(12, 31, 208, 31, $style);
//$pdf->Line(12, 38, 208, 38, $style);
$pdf->Line(12, 210, 208, 210, $style);
$pdf->Line(12, 218, 208, 218, $style);


//$pdf->Line(12, 61, 208, 61, $style);
//$pdf->Line(12, 95, 208, 95, $style);
 



$pdf->SetXY(151,212);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Total" ,0,'L');

$pdf->SetXY(20,222);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Amount In Words" ,0,'L');

$pdf->SetXY(20,227);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0,convert_number_to_words(round($net_amount)),0,'L');

$pdf->SetXY(113,265);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "For" ,0,'L');

$pdf->SetXY(120,265);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, $company_name ,0,'L');


$pdf->SetXY(189,212);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, $net_amount,0,'L');

$pdf->SetXY(170,212);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, $pack,0,'L');


$pdf->SetXY(165,282);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, "Authorised Signatory" ,0,'L');
}

$pdf->lastPage();



if(count($terms_condition)>0){
 $pdf->addPage();
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);


$pdf->Line(12, 31, 208, 31, $style);
$pdf->Line(12, 38, 208, 38, $style);

$pdf->SetXY(85,9);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');	
$pdf->Cell(0,0, $company_name ,0,1,'L'); 	

$pdf->SetXY(90,15);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(110,0,$company_address,'0','L');

$pdf->SetXY(85,32);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('255','255','255');
$pdf->SetFillColor('32','100','150');
$pdf->Rect(12, 29.8, 196, 8, 'F');
$pdf->Cell(104,0, "SALES ORDER" ,0,1,'M');
	
$pdf->SetXY(15,40);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Terms and Condition :" ,0,1,'L');
$oldY1=0;
foreach($terms_condition as $k11=>$v11){
//	for($j=0;$j<38;$j++){
if($oldY1==0) $y =45;
else $y = $oldY1+2;
	
$pdf->SetXY(20,$y+1);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $v11->element_content ,0,1,'L');
//$pdf->Cell(0,0, $j ,0,1,'L');
$oldY1=$pdf->getY();
	
	if($oldY1 > 280)
	{
		$oldY1 = 0;
		$pdf->addPage();
		$pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);


$pdf->Line(12, 31, 208, 31, $style);
$pdf->Line(12, 38, 208, 38, $style);

$pdf->SetXY(85,9);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');	
$pdf->Cell(0,0, $company_name ,0,1,'L'); 	

$pdf->SetXY(90,15);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(110,0,$company_address,'0','L');

$pdf->SetXY(85,32);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('255','255','255');
$pdf->SetFillColor('32','100','150');
$pdf->Rect(12, 29.8, 196, 8, 'F');
//$pdf->SetFillColor('36','108','164');
$pdf->Cell(104,0, "SALES ORDER" ,0,1,'M');

	}
	
}

}



// ---------------------------------------------------------

//Close and output PDF document
ob_end_clean();
  if($print=='PRINT')
{ 
$pdf->Output('name.pdf','FI'); 
$pdf->close();
exit;
}
else
{
  $filename="Uploads/salesorderupload/SO_".$sales_order_no.".pdf";
  $pdf->Output('Uploads/salesorderupload/SO_'.$sales_order_no.'.pdf', 'F');
}
?>
