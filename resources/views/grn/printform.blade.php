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
if (!isset($number)) {
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

$pdf->SetAutoPageBreak(true, 0);

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
       $image_file = public_path().'/images/'.$this->logo;
       $this->Image($image_file,13,8,23,23, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 14);
        // Title
         
    }
	
       // Page footer
        public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-8);
            // Set font
            $this->SetFont('helvetica', '', 8);
            // Page number
			$total = $this->getNumPages();
         // $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');     
          $this->Cell(0, 10, $this->getAliasNumPage().' / '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');     
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

/************************************Header *********************************/
$pdf->SetXY(41,10);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "$company_name" ,0,1,'L');

$pdf->SetXY(81,26);
$pdf->SetFont('','BU','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Material Inward Note" ,0,1,'L');

$pdf->SetXY(160,9);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "MIN No" ,0,1,'L');

$pdf->SetXY(183,9);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $grn_number ,0,1,'L');

$pdf->SetXY(160,16);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "MIN Date" ,0,1,'L');

$pdf->SetXY(183,16);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $dc_date ,0,1,'L');

$pdf->SetXY(160,22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Format No" ,0,1,'L');

$pdf->SetXY(183,23);
$pdf->SetFont('','','6.5');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, $other_reference ,0,1,'L');
$pdf->Cell(0,0, "JRK/DOC/PUR/MIN/03" ,0,1,'L');

$pdf->SetXY(160,28);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Department" ,0,1,'L');

$pdf->SetXY(183,28);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Purchase" ,0,1,'L');

$pdf->Line(36, 7, 36, 33, $style);
$pdf->Line(156, 7, 156, 33, $style);
$pdf->Line(156, 14, 208, 14, $style);
$pdf->Line(156, 21, 208, 21, $style);
$pdf->Line(156, 28, 208, 28, $style);
$pdf->Line(183, 7, 183, 33, $style);
$pdf->Line(12, 33, 208, 33, $style);

$pdf->Line(12, 40, 208, 40, $style);
$pdf->Line(12, 47, 208, 47, $style);
$pdf->Line(12, 57, 208, 57, $style);

$pdf->SetXY(13,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Supplier Name" ,0,1,'L');

$pdf->SetXY(60,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Po No" ,0,1,'L');

$pdf->SetXY(100,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Po Date" ,0,1,'L');

$pdf->SetXY(122,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Dc No" ,0,1,'L');

$pdf->SetXY(140,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Dc Date" ,0,1,'L');

$pdf->SetXY(158,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Invoice No" ,0,1,'L');

$pdf->SetXY(185,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Invoice Date" ,0,1,'L');



$pdf->SetXY(13,42);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $supplier_name ,0,1,'L');

$pdf->SetXY(60,42);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $po_number ,0,1,'L');

$pdf->SetXY(98,42);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $po_date ,0,1,'L');

$pdf->SetXY(116,42);
$pdf->SetFont('','','6.5');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $dc_number ,0,1,'L');

$pdf->SetXY(140,42);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $dc_date ,0,1,'L');

$pdf->SetXY(158,42);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $bill_number ,0,1,'L');

$pdf->SetXY(185,42);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $invoice_date ,0,1,'L');


$pdf->SetXY(13,50);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "S.No" ,0,1,'L');

$pdf->SetXY(33,50);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Item Code" ,0,1,'L');

$pdf->SetXY(57,50);
$pdf->SetFont('','','7');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Item Description" ,0,1,'L');

$pdf->SetXY(95,50);
$pdf->SetFont('','','7');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Unit" ,0,1,'L');

$pdf->SetXY(107,50);
$pdf->SetFont('','','7');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Qty" ,0,1,'L');

$pdf->SetXY(105,53);
$pdf->SetFont('','','7');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Received" ,0,1,'L');

$pdf->SetXY(119,50);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Qty" ,0,1,'L');

$pdf->SetXY(117,53);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Accepted" ,0,1,'L');



$pdf->SetXY(130,50);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Qty" ,0,1,'L');

$pdf->SetXY(128,53);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Rejected" ,0,1,'L');



$pdf->SetXY(141,50);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Reason" ,0,1,'L');

$pdf->SetXY(160,50);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Reference" ,0,1,'L');

$pdf->SetXY(185,50);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Remarks" ,0,1,'L');



$pdf->Line(25, 47, 25, 290, $style);
$pdf->Line(47, 33, 47, 290, $style);
$pdf->Line(95, 33, 95, 290, $style);
$pdf->Line(105, 47, 105, 290, $style);
$pdf->Line(117, 33, 117, 290, $style);
$pdf->Line(128, 47, 128, 290, $style);
$pdf->Line(138, 33, 138, 290, $style);
$pdf->Line(158, 33, 158, 290, $style);
$pdf->Line(183, 33, 183, 290, $style);
$i=0;
$oldY=0;
$dis_amt=0;
$disamt=0;
$gst_tot=0;
$discount=0;
$tax_amt=0;
//$details_var=$value;
$count=1; 
//$value=$grnlines[0];
//for($j=0;$j<248;$j++) {

 foreach($grnlines as $key1=>$value1) {
     
foreach($value1 as $key=>$value) { 
    
	if($oldY==0) $y =58;
	else $y = $oldY+1;	
	$i++;

	$pdf->SetXY(15,$y+1);
	$pdf->SetFont('','','6');
	$pdf->SetTextColor('0','0','0');
	$pdf->Cell(0,0, $i ,0,1,'L');
	$y=$y+8;

	$pdf->SetXY(27,$y-7);
	$pdf->SetFont('','','6');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(47,2, $value->product_code ,0,'L');

	$pdf->SetXY(47,$y-7);
	$pdf->SetFont('','','6');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(47,2, $value->product ,0,'L');
	$oldY=$pdf->getY();
		
	$pdf->SetXY(95,$y-7);
	$pdf->SetFont('','','6');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(47,2, $value->uom ,0,'L');
		
	$pdf->SetXY(105,$y-7);
	$pdf->SetFont('','','6');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(47,2, $value->received_qty ,0,'L');
		
	$pdf->SetXY(120,$y-7);
	$pdf->SetFont('','','6');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(47,2, $value->accepted_qty ,0,'L');
		
	$pdf->SetXY(132,$y-7);
	$pdf->SetFont('','','6');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(47,2, $value->rejected_qty ,0,'L');
		
	$pdf->SetXY(139,$y-7);
	$pdf->SetFont('','','6');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(20,2, $value->reason ,0,'L');
		
	$pdf->SetXY(158,$y-7);
	$pdf->SetFont('','','6');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(25,2, $value->reference ,0,'L');
		
	$pdf->SetXY(184,$y-7);
	$pdf->SetFont('','','6');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(23,2, $value->remarks ,0,'L');
		
		if($oldY > 280)
		{
		$oldY =0;
		$pdf->addPage();
			$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
$pdf->SetXY(41,10);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Dr. JRK’s Research & Pharmaceuticals Pvt Ltd" ,0,1,'L');

$pdf->SetXY(88,29);
$pdf->SetFont('','U','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Material Inward Note" ,0,1,'L');

$pdf->SetXY(160,9);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "MIN No" ,0,1,'L');

$pdf->SetXY(183,9);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $grn_number ,0,1,'L');

$pdf->SetXY(160,16);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "MIN Date" ,0,1,'L');

$pdf->SetXY(183,16);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $dc_date ,0,1,'L');

$pdf->SetXY(160,22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Format No" ,0,1,'L');

$pdf->SetXY(183,22);
$pdf->SetFont('','','6.5');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, $other_reference ,0,1,'L');
$pdf->Cell(0,0, "JRK/DOC/PUR/MIN/03" ,0,1,'L');

$pdf->SetXY(160,28);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Department" ,0,1,'L');

$pdf->SetXY(183,28);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Purchase" ,0,1,'L');

$pdf->Line(36, 7, 36, 33, $style);
$pdf->Line(156, 7, 156, 33, $style);
$pdf->Line(156, 14, 208, 14, $style);
$pdf->Line(156, 21, 208, 21, $style);
$pdf->Line(156, 28, 208, 28, $style);
$pdf->Line(183, 7, 183, 33, $style);
$pdf->Line(12, 33, 208, 33, $style);

$pdf->Line(12, 40, 208, 40, $style);
$pdf->Line(12, 47, 208, 47, $style);
$pdf->Line(12, 57, 208, 57, $style);

$pdf->SetXY(13,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Supplier Name" ,0,1,'L');

$pdf->SetXY(60,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Po No" ,0,1,'L');

$pdf->SetXY(100,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Po Date" ,0,1,'L');

$pdf->SetXY(122,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Dc No" ,0,1,'L');

$pdf->SetXY(140,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Dc Date" ,0,1,'L');

$pdf->SetXY(158,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Invoice No" ,0,1,'L');

$pdf->SetXY(185,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Invoice Date" ,0,1,'L');



$pdf->SetXY(13,42);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $supplier_name ,0,1,'L');

$pdf->SetXY(60,42);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $po_number ,0,1,'L');

$pdf->SetXY(98,42);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $po_date ,0,1,'L');

$pdf->SetXY(116,42);
$pdf->SetFont('','','6.5');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $dc_number ,0,1,'L');

$pdf->SetXY(140,42);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $dc_date ,0,1,'L');

$pdf->SetXY(158,42);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $bill_number ,0,1,'L');

$pdf->SetXY(185,42);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $invoice_date ,0,1,'L');


$pdf->SetXY(13,50);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "S.No" ,0,1,'L');

$pdf->SetXY(33,50);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Item Code" ,0,1,'L');

$pdf->SetXY(57,50);
$pdf->SetFont('','','7');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Item Description" ,0,1,'L');

$pdf->SetXY(95,50);
$pdf->SetFont('','','7');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Unit" ,0,1,'L');

$pdf->SetXY(107,50);
$pdf->SetFont('','','7');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Qty" ,0,1,'L');

$pdf->SetXY(105,53);
$pdf->SetFont('','','7');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Received" ,0,1,'L');

$pdf->SetXY(119,50);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Qty" ,0,1,'L');

$pdf->SetXY(117,53);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Accepted" ,0,1,'L');



$pdf->SetXY(130,50);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Qty" ,0,1,'L');

$pdf->SetXY(128,53);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Rejected" ,0,1,'L');



$pdf->SetXY(141,50);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Reason" ,0,1,'L');

$pdf->SetXY(160,50);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Reference" ,0,1,'L');

$pdf->SetXY(185,50);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Remarks" ,0,1,'L');



$pdf->Line(25, 47, 25, 290, $style);
$pdf->Line(47, 33, 47, 290, $style);
$pdf->Line(95, 33, 95, 290, $style);
$pdf->Line(105, 47, 105, 290, $style);
$pdf->Line(117, 33, 117, 290, $style);
$pdf->Line(128, 47, 128, 290, $style);
$pdf->Line(138, 33, 138, 290, $style);
$pdf->Line(158, 33, 158, 290, $style);
$pdf->Line(183, 33, 183, 290, $style);
		}
	}
 }

/**********************************************Footer Content *************************************************/
$total = $pdf->getNumPages();
if($pdf->pageNo()==$total)
{	
if($oldY < 245)
{
$pdf->Rect(12, 247, 196, 43,'DF', "",  array(255, 255, 255));
	$pdf->Line(12, 247, 208, 247, $style);
$pdf->Line(12, 254, 208, 254, $style);
$pdf->Line(12, 272, 208, 272, $style);
$pdf->Line(100, 272, 100, 290, $style);
$pdf->Line(150, 272, 150, 290, $style);

$pdf->SetXY(13,249);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Payment Details (By Accounts)" ,0,1,'L');

$pdf->SetXY(13,254);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Remarks" ,0,1,'L');
	
$pdf->SetXY(13,259);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $remarks ,0,1,'L');

$pdf->SetXY(13,273);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Checked by" ,0,1,'L');

$pdf->SetXY(13,280);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $checked_by ,0,1,'L');

$pdf->SetXY(100,273);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Approved by" ,0,1,'L');

$pdf->SetXY(100,280);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $approved_by ,0,1,'L');

$pdf->SetXY(193,273);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Accounts" ,0,1,'L');
}
else
{
$pdf->addPage();
	$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
$pdf->SetXY(41,10);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Dr. JRK’s Research & Pharmaceuticals Pvt Ltd" ,0,1,'L');

$pdf->SetXY(88,29);
$pdf->SetFont('','U','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Material Inward Note" ,0,1,'L');

$pdf->SetXY(160,9);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "MIN No" ,0,1,'L');

$pdf->SetXY(183,9);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $grn_number ,0,1,'L');

$pdf->SetXY(160,16);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "MIN Date" ,0,1,'L');

$pdf->SetXY(183,16);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $dc_date ,0,1,'L');

$pdf->SetXY(160,22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Format No" ,0,1,'L');

$pdf->SetXY(183,22);
$pdf->SetFont('','','6.5');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, $other_reference ,0,1,'L');
$pdf->Cell(0,0, "JRK/DOC/PUR/MIN/03" ,0,1,'L');

$pdf->SetXY(160,28);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Department" ,0,1,'L');

$pdf->SetXY(183,28);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Purchase" ,0,1,'L');

$pdf->Line(36, 7, 36, 33, $style);
$pdf->Line(156, 7, 156, 33, $style);
$pdf->Line(156, 14, 208, 14, $style);
$pdf->Line(156, 21, 208, 21, $style);
$pdf->Line(156, 28, 208, 28, $style);
$pdf->Line(183, 7, 183, 33, $style);
$pdf->Line(12, 33, 208, 33, $style);

$pdf->Line(12, 40, 208, 40, $style);
$pdf->Line(12, 47, 208, 47, $style);
$pdf->Line(12, 57, 208, 57, $style);

$pdf->SetXY(13,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Supplier Name" ,0,1,'L');

$pdf->SetXY(60,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Po No" ,0,1,'L');

$pdf->SetXY(100,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Po Date" ,0,1,'L');

$pdf->SetXY(122,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Dc No" ,0,1,'L');

$pdf->SetXY(140,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Dc Date" ,0,1,'L');

$pdf->SetXY(158,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Invoice No" ,0,1,'L');

$pdf->SetXY(185,35);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Invoice Date" ,0,1,'L');



$pdf->SetXY(13,42);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $supplier_name ,0,1,'L');

$pdf->SetXY(60,42);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $po_number ,0,1,'L');

$pdf->SetXY(98,42);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $po_date ,0,1,'L');

$pdf->SetXY(116,42);
$pdf->SetFont('','','6.5');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $dc_number ,0,1,'L');

$pdf->SetXY(140,42);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $dc_date ,0,1,'L');

$pdf->SetXY(158,42);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $bill_number ,0,1,'L');

$pdf->SetXY(185,42);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $invoice_date ,0,1,'L');


$pdf->SetXY(13,50);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "S.No" ,0,1,'L');

$pdf->SetXY(33,50);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Item Code" ,0,1,'L');

$pdf->SetXY(57,50);
$pdf->SetFont('','','7');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Item Description" ,0,1,'L');

$pdf->SetXY(95,50);
$pdf->SetFont('','','7');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Unit" ,0,1,'L');

$pdf->SetXY(107,50);
$pdf->SetFont('','','7');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Qty" ,0,1,'L');

$pdf->SetXY(105,53);
$pdf->SetFont('','','7');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Received" ,0,1,'L');

$pdf->SetXY(119,50);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Qty" ,0,1,'L');

$pdf->SetXY(117,53);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Accepted" ,0,1,'L');



$pdf->SetXY(130,50);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Qty" ,0,1,'L');

$pdf->SetXY(128,53);
$pdf->SetFont('','','6');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Rejected" ,0,1,'L');



$pdf->SetXY(141,50);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Reason" ,0,1,'L');

$pdf->SetXY(160,50);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Reference" ,0,1,'L');

$pdf->SetXY(185,50);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Remarks" ,0,1,'L');

	
	$pdf->SetXY(20,120);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "<--------------------------------------------------------End of Page----------------------------------------------------->" ,0,1,'L');


$pdf->Line(25, 47, 25, 290, $style);
$pdf->Line(47, 33, 47, 290, $style);
$pdf->Line(95, 33, 95, 290, $style);
$pdf->Line(105, 47, 105, 290, $style);
$pdf->Line(117, 33, 117, 290, $style);
$pdf->Line(128, 47, 128, 290, $style);
$pdf->Line(138, 33, 138, 290, $style);
$pdf->Line(158, 33, 158, 290, $style);
$pdf->Line(183, 33, 183, 290, $style);
	
$pdf->Rect(12, 247, 196, 43,'DF', "",  array(255, 255, 255));
$pdf->Line(12, 247, 208, 247, $style);
$pdf->Line(12, 254, 208, 254, $style);
$pdf->Line(12, 272, 208, 272, $style);
$pdf->Line(100, 272, 100, 290, $style);
$pdf->Line(150, 272, 150, 290, $style);

$pdf->SetXY(13,249);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Payment Details (By Accounts)" ,0,1,'L');

$pdf->SetXY(13,254);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Remarks" ,0,1,'L');
	
$pdf->SetXY(13,259);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $remarks ,0,1,'L');

$pdf->SetXY(13,273);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Checked by" ,0,1,'L');

$pdf->SetXY(13,280);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $checked_by ,0,1,'L');

$pdf->SetXY(100,273);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Approved by" ,0,1,'L');

$pdf->SetXY(100,280);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $approved_by ,0,1,'L');

$pdf->SetXY(193,273);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Accounts" ,0,1,'L');
}
}
/************************************Body Lines Content End******************************/

ob_end_clean();
  
  
$print='PRINT';
if($print=='PRINT')
{				//}              
$pdf->Output('name.pdf','FI'); 
$pdf->close(); 
exit;
}
else
{

  $pdf->Output('Uploads/purchaseorder/PO_'.$po_number.'.pdf', 'F');
 
}




/* end */
//$pdf->Output('example_007.pdf', 'FI');
//exit();
//============================================================+
// END OF FILE
//============================================================+

/******************************************************************************************/ 
?>