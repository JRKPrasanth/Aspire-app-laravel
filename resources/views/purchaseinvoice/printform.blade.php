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
$decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred','thousand','lakh', 'crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    //return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise ;
    return ($Rupees ? $Rupees . 'Rupees ' : '');
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
//        $image_file = public_path().'/images/jrks.png';
         $image_file = public_path().'/images/'.$this->logo;
       $this->Image($image_file, 13, 8, 23, 13, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
       
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
          $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');     
        }    
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setLogo($company_logo_name);
  

// add a page
$pdf->AddPage('P', $resolution);

$pdf->SetHeaderData('', '', 'PO INVOICE PRINT', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
 
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);



/************************************Header *********************************/
$pdf->SetXY(70,10);
$pdf->SetFont('','B','20');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PURCHASE INVOICE" ,0,1,'L');

$pdf->Line(12, 22, 208, 22, $style);

$pdf->SetXY(13,22);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "INVOICE TO" ,0,1,'L');

$pdf->SetXY(13,25);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "$company_name" ,0,1,'L');

$pdf->SetXY(13,29);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "($company_name)" ,0,1,'L');


if($po_for_verdura == 'YES'){
$pdf->SetXY(12,33);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, " Mfg Unit: 18, Perumal Koil Street, Regd. Off: 13, Perumal Koil Street,Kunrathur,Chennai,Tamil Nadu,INDIA" ,0,'L');
}else{
$pdf->SetXY(13,33);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "$company_address" ,0,'L');   
}

$oldad = $pdf->getY();

$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Pin Code:" .$pincode,0,1,'L');

//$pdf->SetXY(13,39);
//$pdf->SetFont('','','9');
//$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, "" ,0,1,'L');

$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN: ".$cin_no ,0,1,'L');

$pdf->SetXY(13,$oldad+8);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN: ".$gstno ,0,1,'L');
/*
$pdf->SetXY(13,49);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State Name :" .$state_name. " ,State Code:"  .$state_code_no ,0,1,'L');
*/
//$pdf->SetXY(13,51);
//$pdf->SetFont('','','9');
//$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, "CIN: ".$cin_no ,0,1,'L');

$pdf->SetXY(13,$oldad+12);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "E-Mail : ".$email_id ,0,1,'L');

$pdf->Line(12, 58, 208, 58, $style);
$pdf->Line(113, 22, 113, 59, $style);

$pdf->SetXY(13,58);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "DESPATCH TO:" ,0,1,'L');

$pdf->SetXY(13,62);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "$company_name" ,0,1,'L');

//$pdf->SetXY(13,65);
//$pdf->SetFont('','','8');
//$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, "" ,0,1,'L');

if($po_for_verdura == 'YES'){
$pdf->SetXY(12,65);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, " Mfg Unit: 18, Perumal Koil Street, Regd. Off: 13, Perumal Koil Street,Kunrathur,Chennai,Tamil Nadu,INDIA" ,0,'L');
}else{
$pdf->SetXY(13,65);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "$company_address" ,0,'L');  
}

$oldad1 = $pdf->getY();

$pdf->SetXY(13,$oldad1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Pincode:" .$pincode,0,1,'L');

$pdf->SetXY(13,$oldad1+3);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN:".$cin_no,0,1,'L');

$pdf->SetXY(13,$oldad1+6);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "E-Mail : ".$email_id ,0,1,'L');

$pdf->SetXY(62,$oldad1+3);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0, "GSTIN/UIN :" .$gstno,0,'L');

// $pdf->SetXY(13,79);
// $pdf->SetFont('','','8');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(100,2, "State Name :" .$state_name. " ,State Code:"  .$state_code_no ,0,1,'L');



$pdf->SetXY(13,83);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Supplier" ,0,1,'L');

$pdf->SetXY(13,87);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $bcustomer ,0,1,'L');

$pdf->SetXY(13,92);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $supplier_address ,0,'L');
$oldad = $pdf->getY();

// $pdf->SetXY(13,$oldad+2);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "PH : ".$bcontact_number ,0,'L');

$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "GSTIN/UIN : ".$bgst_no ,0,'L');

// $pdf->SetXY(13,$oldad+7);
// $pdf->SetFont('','','8');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "State Name : ".$bstate_name ,0,'L');

$pdf->SetXY(113,22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Voucher No ",0,'L');

$pdf->SetXY(115,26);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $invoice_number,0,'L');

$pdf->SetXY(160,22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Dated ",0,'L');

$pdf->SetXY(160,26);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $invoice_date,0,'L');

$pdf->Line(113, 31, 208, 31, $style);

$pdf->SetXY(113,32);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Freight Terms ",0,'L');

$pdf->SetXY(113,36);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $fob_point_name,0,'L');

$pdf->SetXY(160,32);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Mode/Terms of Payment ",0,'L');

$pdf->SetXY(160,36);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ap_payment_term_name,0,'L');

$pdf->Line(113, 40, 208, 40, $style);

$pdf->SetXY(113,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Supplier’s Ref./Order No.",0,'L');

$pdf->SetXY(113,45);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $supplier_ref_no,0,'L');

$pdf->SetXY(160,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Other Reference(s)",0,'L');

$pdf->SetXY(160,45);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $remarks,0,'L');

$pdf->Line(113, 50, 208, 50, $style);


$pdf->SetXY(113,51);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Despatch through.",0,'L');

$pdf->SetXY(113,54);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $despatch_through,0,'L');

$pdf->SetXY(160,51);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Destination",0,'L');

$pdf->SetXY(160,54);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "$destination",0,'L');

$pdf->Line(113, 50, 208, 50, $style);
$pdf->Line(160, 22, 160, 58, $style);
/************************************Header bottom line******************************/


$pdf->SetXY(113,59);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Terms of Delivery",0,'L');

$pdf->SetXY(113,63);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "$deliveryterm",0,'L');

if($transport_charges!=0)
{
$pdf->SetXY(113,105);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Transportation Charge :",0,'L');

$pdf->SetXY(152,105);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "$transport_charges",0,'L');
}
/************************************Header End******************************/

/************************************Body Lines Content******************************/

$pdf->Line(12, 82, 113, 82, $style);
$pdf->Line(113, 59, 113, 112, $style);
$pdf->Line(12, 112, 208, 112, $style);
$pdf->Line(12, 122, 208, 122, $style);

$pdf->Line(25, 112, 25, 290, $style);
$pdf->Line(75, 112, 75, 290, $style);
$pdf->Line(95, 112, 95, 290, $style);
$pdf->Line(113, 112, 113, 290, $style);
$pdf->Line(127, 112, 127, 290, $style);
$pdf->Line(142, 112, 142, 290, $style);
$pdf->Line(159, 112, 159, 290, $style);
$pdf->Line(169, 112, 169, 290, $style);
$pdf->Line(181, 112, 181, 290, $style);

$pdf->SetXY(13,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "S.No",0,'L');

$pdf->SetXY(23,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2, "Description of Goods",0,'C');

$pdf->SetXY(65,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(40,2, "HSN/SAC",0,'C');

$pdf->SetXY(97,114);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(10,2, "GST Rate",0,'C');

$pdf->SetXY(110,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Due on",0,'C');

$pdf->SetXY(125,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Quantity",0,'C');

$pdf->SetXY(140,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Rate",0,'C');

$pdf->SetXY(155,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Uom",0,'C');

$pdf->SetXY(165,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Disc. %",0,'C');

$pdf->SetXY(185,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Amount",0,'C');


$i=0;
$oldY=0;
$dis_amt=0;
$disamt=0;
$gst_tot=0;
$discount=0;
$tax_amt=0;
$count=1;
//$details_var=$value;
//$value=$linedata[0];
//for($j=0;$j<24;$j++) {
foreach($linedata as $key=>$value) { 
  
if($oldY==0) $y =125;
else $y = $oldY+2;	
$i++;
 
$pdf->SetXY(15,$y+1);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $i ,0,1,'L');
$y=$y+8;


$pdf->SetXY(27,$y-7);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(47,2, $value['product'] ,0,'L');
$oldY=$pdf->getY();
	
$pdf->SetXY(77,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $value['hsn_code'] ,0,1,'L');
	
$pdf->SetXY(97,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $value['gst']." %" ,0,1,'L');
///	dd($value['promised_date']);
$pdf->SetXY(113,$y-7);
$pdf->SetFont('','','7');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(15,0, $value['promised_date'] ,0,1,'L');

$pdf->SetXY(127,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, $value['qty'] ,0,'R');
	
$pdf->SetXY(143,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, number_format($value['unit_price'],2) ,0,'R');
	
$pdf->SetXY(159,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(10,2, $value['uom'] ,0,'C');
	
$pdf->SetXY(170,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
if($value['discount_amount'] !=0)
{
$pdf->MultiCell(10,2, $value['discount_amount'] ,0,'R');
}
else
{
$pdf->MultiCell(10,2, '' ,0,'R');
}
	
$pdf->SetXY(183,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, number_format($value['amount'],2) ,0,'R');
	
	
if($oldY > 280)
{
//$pdf->Line(12, 183, 208, 183, $style);
//$pdf->Line(12, 193, 208, 193, $style);
$oldY = 0;
if($pdf->pageno()==1)
{
	
}
	$pdf->addPage();
	$pdf->Line(25, 193, 25, 290, $style);
	$pdf->Line(75, 193, 75, 290, $style);
	$pdf->Line(95, 193, 95, 290, $style);
	$pdf->Line(112, 193, 112, 290, $style);
	$pdf->Line(127, 193, 127, 290, $style);
	$pdf->Line(142, 193, 142, 290, $style);
	$pdf->Line(159, 193, 159, 290, $style);
	$pdf->Line(169, 193, 169, 290, $style);
	$pdf->Line(181, 193, 181, 290, $style);
	$count = $count + 1;
	
	$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
$pdf->SetXY(70,10);
$pdf->SetFont('','B','20');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PURCHASE INVOICE" ,0,1,'L');

$pdf->Line(12, 22, 208, 22, $style);

$pdf->SetXY(13,22);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "INVOICE TO" ,0,1,'L');

$pdf->SetXY(13,25);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "$company_name" ,0,1,'L');

$pdf->SetXY(13,29);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "($company_name)" ,0,1,'L');

if($po_for_verdura == 'YES'){
$pdf->SetXY(12,33);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, " Mfg Unit: 18, Perumal Koil Street, Regd. Off: 13, Perumal Koil Street,Kunrathur,Chennai,Tamil Nadu,INDIA" ,0,'L');
}else{
$pdf->SetXY(13,33);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "$company_address" ,0,'L'); 
}

$oldad = $pdf->getY();

$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Pin Code:" .$pincode,0,1,'L');

//$pdf->SetXY(13,39);
//$pdf->SetFont('','','9');
//$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, "" ,0,1,'L');

$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN: ".$cin_no ,0,1,'L');

$pdf->SetXY(13,$oldad+8);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN: ".$gstno ,0,1,'L');
/*
$pdf->SetXY(13,49);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State Name :" .$state_name. " ,State Code:"  .$state_code_no ,0,1,'L');
*/
//$pdf->SetXY(13,51);
//$pdf->SetFont('','','9');
//$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, "CIN: ".$cin_no ,0,1,'L');

$pdf->SetXY(13,$oldad+12);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "E-Mail : ".$email_id ,0,1,'L');


$pdf->Line(12, 58, 208, 58, $style);
$pdf->Line(113, 22, 113, 59, $style);

$pdf->SetXY(13,58);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "DESPATCH TO:" ,0,1,'L');

$pdf->SetXY(13,62);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "$company_name" ,0,1,'L');

//$pdf->SetXY(13,65);
//$pdf->SetFont('','','8');
//$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, "" ,0,1,'L');


if($po_for_verdura == 'YES'){
$pdf->SetXY(12,65);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, " Mfg Unit: 18, Perumal Koil Street, Regd. Off: 13, Perumal Koil Street,Kunrathur,Chennai,Tamil Nadu,INDIA" ,0,'L');
}else{
$pdf->SetXY(13,65);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "$company_address" ,0,'L'); 
}

$oldad1 = $pdf->getY();

$pdf->SetXY(13,$oldad1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Pincode:" .$pincode,0,1,'L');

$pdf->SetXY(13,$oldad1+3);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN:".$cin_no,0,1,'L');

$pdf->SetXY(13,$oldad1+6);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "E-Mail : ".$email_id ,0,1,'L');

$pdf->SetXY(62,$oldad1+3);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0, "GSTIN/UIN :" .$gstno,0,'L');

// $pdf->SetXY(13,79);
// $pdf->SetFont('','','8');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(100,2, "State Name :" .$state_name. " ,State Code:"  .$state_code_no ,0,1,'L');



$pdf->SetXY(13,83);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Supplier" ,0,1,'L');

$pdf->SetXY(13,87);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $bcustomer ,0,1,'L');

$pdf->SetXY(13,92);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $supplier_address ,0,'L');
$oldad = $pdf->getY();

// $pdf->SetXY(13,$oldad+2);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "PH : ".$bcontact_number ,0,'L');

$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "GSTIN/UIN : ".$bgst_no ,0,'L');

// $pdf->SetXY(13,$oldad+7);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "State Name : ".$bstate_name ,0,'L');

$pdf->SetXY(113,22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Voucher No ",0,'L');

$pdf->SetXY(115,26);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $invoice_number,0,'L');

$pdf->SetXY(160,22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Dated ",0,'L');

$pdf->SetXY(160,26);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $invoice_date,0,'L');

$pdf->Line(113, 31, 208, 31, $style);

$pdf->SetXY(113,32);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Freight Terms ",0,'L');

$pdf->SetXY(113,36);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $fob_point_name,0,'L');

$pdf->SetXY(160,32);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Mode/Terms of Payment ",0,'L');

$pdf->SetXY(160,36);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ap_payment_term_name,0,'L');

$pdf->Line(113, 40, 208, 40, $style);

$pdf->SetXY(113,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Supplier’s Ref./Order No.",0,'L');

$pdf->SetXY(113,45);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $supplier_ref_no,0,'L');

$pdf->SetXY(160,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Other Reference(s)",0,'L');

$pdf->SetXY(160,45);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $remarks,0,'L');

$pdf->Line(113, 50, 208, 50, $style);


$pdf->SetXY(113,51);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Despatch through.",0,'L');

$pdf->SetXY(113,54);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $despatch_through,0,'L');

$pdf->SetXY(160,51);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Destination",0,'L');

$pdf->SetXY(160,54);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "$destination",0,'L');

$pdf->Line(113, 50, 208, 50, $style);
$pdf->Line(160, 22, 160, 59, $style);
/************************************Header bottom line******************************/


$pdf->SetXY(113,59);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Terms of Delivery",0,'L');

$pdf->SetXY(113,63);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "$deliveryterm",0,'L');
/************************************Header End******************************/

/************************************Body Lines Content******************************/


$pdf->Line(12, 82, 113, 82, $style);
$pdf->Line(113, 59, 113, 112, $style);
$pdf->Line(12, 112, 208, 112, $style);
$pdf->Line(12, 122, 208, 122, $style);

$pdf->Line(25, 112, 25, 290, $style);
$pdf->Line(75, 112, 75, 290, $style);
$pdf->Line(95, 112, 95, 290, $style);
$pdf->Line(113, 112, 113, 290, $style);
$pdf->Line(127, 112, 127, 290, $style);
$pdf->Line(142, 112, 142, 290, $style);
$pdf->Line(159, 112, 159, 290, $style);
$pdf->Line(169, 112, 169, 290, $style);
$pdf->Line(181, 112, 181, 290, $style);




$pdf->SetXY(13,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "S.No",0,'L');

$pdf->SetXY(23,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2, "Description of Goods",0,'C');

$pdf->SetXY(65,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(40,2, "HSN/SAC",0,'C');

$pdf->SetXY(97,114);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(10,2, "GST Rate",0,'C');

$pdf->SetXY(110,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Due on",0,'C');

$pdf->SetXY(125,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Quantity",0,'C');

$pdf->SetXY(140,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Rate",0,'C');

$pdf->SetXY(155,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Uom",0,'C');

$pdf->SetXY(165,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Disc. %",0,'C');

$pdf->SetXY(185,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Amount",0,'C');

}
}
$total = $pdf->getNumPages();
if($pdf->pageNo()==$total)
{	
if($oldY < 180)
{
$pdf->Rect(12, 193, 196, 100,'DF', "",  array(255, 255, 255));
}
	else
	{
		$pdf->addPage();
		$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);
		$pdf->SetXY(70,10);
$pdf->SetFont('','B','20');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PURCHASE INVOICE" ,0,1,'L');

$pdf->Line(12, 22, 208, 22, $style);

$pdf->SetXY(13,22);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "INVOICE TO" ,0,1,'L');

$pdf->SetXY(13,25);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "$company_name" ,0,1,'L');

$pdf->SetXY(13,29);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "($company_name)" ,0,1,'L');

if($po_for_verdura == 'YES'){
$pdf->SetXY(12,33);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, " Mfg Unit: 18, Perumal Koil Street, Regd. Off: 13, Perumal Koil Street,Kunrathur,Chennai,Tamil Nadu,INDIA" ,0,'L');
}else{
$pdf->SetXY(13,33);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "$company_address" ,0,'L'); 
}

$oldad = $pdf->getY();

$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Pin Code:" .$pincode,0,1,'L');

//$pdf->SetXY(13,39);
//$pdf->SetFont('','','9');
//$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, "" ,0,1,'L');

$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN: ".$cin_no ,0,1,'L');

$pdf->SetXY(13,$oldad+8);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN: ".$gstno ,0,1,'L');
/*
$pdf->SetXY(13,49);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State Name :" .$state_name. " ,State Code:"  .$state_code_no ,0,1,'L');
*/
//$pdf->SetXY(13,51);
//$pdf->SetFont('','','9');
//$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, "CIN: ".$cin_no ,0,1,'L');

$pdf->SetXY(13,$oldad+12);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "E-Mail : ".$email_id ,0,1,'L');


$pdf->Line(12, 58, 208, 58, $style);
$pdf->Line(113, 22, 113, 59, $style);

$pdf->SetXY(13,58);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "DESPATCH TO:" ,0,1,'L');

$pdf->SetXY(13,62);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "$company_name" ,0,1,'L');

//$pdf->SetXY(13,65);
//$pdf->SetFont('','','8');
//$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, "" ,0,1,'L');

if($po_for_verdura == 'YES'){
$pdf->SetXY(12,65);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, " Mfg Unit: 18, Perumal Koil Street, Regd. Off: 13, Perumal Koil Street,Kunrathur,Chennai,Tamil Nadu,INDIA" ,0,'L');
}else{
$pdf->SetXY(13,65);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "$company_address" ,0,'L');
}

$oldad1 = $pdf->getY();

$pdf->SetXY(13,$oldad1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Pincode:" .$pincode,0,1,'L');

$pdf->SetXY(13,$oldad1+3);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN:".$cin_no,0,1,'L');

$pdf->SetXY(13,$oldad1+6);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "E-Mail : ".$email_id ,0,1,'L');

$pdf->SetXY(62,$oldad1+3);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0, "GSTIN/UIN :" .$gstno,0,'L');

// $pdf->SetXY(13,79);
// $pdf->SetFont('','','8');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(100,2, "State Name :" .$state_name. " ,State Code:"  .$state_code_no ,0,1,'L');

$pdf->SetXY(13,83);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Supplier" ,0,1,'L');

$pdf->SetXY(13,87);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $bcustomer ,0,1,'L');

$pdf->SetXY(13,92);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $supplier_address ,0,'L');
$oldad = $pdf->getY();

// $pdf->SetXY(13,$oldad+2);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "PH : ".$bcontact_number ,0,'L');

$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "GSTIN/UIN : ".$bgst_no ,0,'L');

// $pdf->SetXY(13,$oldad+7);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "State Name : ".$bstate_name ,0,'L');

$pdf->SetXY(113,22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Voucher No ",0,'L');

$pdf->SetXY(115,26);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $invoice_number,0,'L');

$pdf->SetXY(160,22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Dated ",0,'L');

$pdf->SetXY(160,26);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $invoice_date,0,'L');

$pdf->Line(113, 31, 208, 31, $style);

$pdf->SetXY(113,32);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Freight Terms ",0,'L');

$pdf->SetXY(113,36);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $fob_point_name,0,'L');

$pdf->SetXY(160,32);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Mode/Terms of Payment ",0,'L');

$pdf->SetXY(160,36);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ap_payment_term_name,0,'L');

$pdf->Line(113, 40, 208, 40, $style);

$pdf->SetXY(113,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Supplier’s Ref./Order No.",0,'L');

$pdf->SetXY(113,45);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $supplier_ref_no,0,'L');

$pdf->SetXY(160,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Other Reference(s)",0,'L');

$pdf->SetXY(160,45);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $remarks,0,'L');

$pdf->Line(113, 50, 208, 50, $style);


$pdf->SetXY(113,51);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Despatch through.",0,'L');

$pdf->SetXY(113,54);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $despatch_through,0,'L');

$pdf->SetXY(160,51);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Destination",0,'L');

$pdf->SetXY(160,54);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "$destination",0,'L');

$pdf->Line(113, 50, 208, 50, $style);
$pdf->Line(160, 22, 160, 59, $style);
/************************************Header bottom line******************************/


$pdf->SetXY(113,59);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Terms of Delivery",0,'L');

$pdf->SetXY(113,63);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "$deliveryterm",0,'L');
/************************************Header End******************************/

/************************************Body Lines Content******************************/


$pdf->Line(12, 82, 113, 82, $style);
$pdf->Line(113, 59, 113, 112, $style);
$pdf->Line(12, 112, 208, 112, $style);
$pdf->Line(12, 122, 208, 122, $style);

$pdf->Line(25, 112, 25, 290, $style);
$pdf->Line(75, 112, 75, 290, $style);
$pdf->Line(95, 112, 95, 290, $style);
$pdf->Line(113, 112, 113, 290, $style);
$pdf->Line(127, 112, 127, 290, $style);
$pdf->Line(142, 112, 142, 290, $style);
$pdf->Line(159, 112, 159, 290, $style);
$pdf->Line(169, 112, 169, 290, $style);
$pdf->Line(181, 112, 181, 290, $style);




$pdf->SetXY(13,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "S.No",0,'L');

$pdf->SetXY(23,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2, "Description of Goods",0,'C');

$pdf->SetXY(65,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(40,2, "HSN/SAC",0,'C');

$pdf->SetXY(97,114);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(10,2, "GST Rate",0,'C');

$pdf->SetXY(110,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Due on",0,'C');

$pdf->SetXY(125,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Quantity",0,'C');

$pdf->SetXY(140,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Rate",0,'C');

$pdf->SetXY(155,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Uom",0,'C');

$pdf->SetXY(165,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Disc. %",0,'C');

$pdf->SetXY(185,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Amount",0,'C');
	}
	
}
/************************************Body Lines Content End******************************/
$pdf->Rect(12, 193, 196, 100,'DF', "",  array(255, 255, 255));
/************************************ Footer ******************************/
$pdf->Line(12, 183, 208, 183, $style);
$pdf->Line(12, 193, 208, 193, $style);
$pdf->SetXY(60,186);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Total",0,'L');
	
$pdf->SetXY(183,186);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, number_format($sub_total,2),0,'R');

$pdf->SetXY(13,195);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Amount Chargeable (in words)",0,'L');

$pdf->SetXY(13,200);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, convert_number_to_words(round($sub_total)),0,'L');

if($payment_status=='1')
{
$pdf->SetXY(13,250);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$image_file1 = public_path().'/images/paid-stamp.png';
$pdf->Image($image_file1, 76.5, 245, 40, 30, 'PNG', '', 'T', false, 250, '', false, false, 0, false, false, false);
}

$pdf->SetXY(13,270);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Company’s Service Tax No.",0,'L');

$pdf->SetXY(56,270);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, ": ".$tax_reg_no,0,'L');

$pdf->SetXY(13,275);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Company’s PAN",0,'L');

$pdf->SetXY(56,275);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, ": ".$pan_no,0,'L');

$pdf->SetXY(110,270);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "$company_name",0,'C');

$pdf->SetXY(120,283);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Authorised Signatory",0,'R');

$pdf->Line(110, 268, 110, 293, $style);
$pdf->Line(110, 268, 208, 268, $style);
/************************************ Footer End ******************************/

$hsn_y=0;	
$sgat=0;
$cgst=0;
$gsttot=0;
$gsttot=208;
//dd($gst);
foreach($gst as $k1=>$gstvalue)
{
	//dd($gstvalue['gst'][0]);
	if($hsn_y==0)
	{
		$hsny=198;
	}
	else
	{
	$hsny=$hsn_y-3;
	}
if($gstvalue['gsttype'] !="IGST") 
{ 
	$pdf->SetXY(128,$hsny+1);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(19,10, $gstvalue['gst'][0]." %" ,0,'R');

	$pdf->SetXY(156,$hsny);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(19,10, "input SGST" ,0,'R');

	$pdf->SetXY(156,$hsny+5);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(19,10, "input CGST" ,0,'R');

	$pdf->SetXY(188,$hsny);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(19,10, number_format($gstvalue['sgst_val'],2) ,0,'R');

	$pdf->SetXY(188,$hsny+5);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(19,10, number_format($gstvalue['cgst_val'],2) ,0,'R');
	$hsn_y=$pdf->getY();
}
else
{
$pdf->SetXY(128,$hsny+1);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, $gstvalue['gst'][0]." %" ,0,'R');
	
$pdf->SetXY(156,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, "input IGST" ,0,'R');
	
$pdf->SetXY(188,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, number_format($gstvalue['gst_val'],2) ,0,'R');
}
}

$pdf->Line(180, $hsny+10, 208, $hsny+10, $style);	
$pdf->Line(180, $hsny+18, 208, $hsny+18, $style);	

$pdf->SetXY(154,$hsny+12);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, "GST Total" ,0,'R');
	
$pdf->SetXY(188,$hsny+12);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, number_format($gsttotal,2) ,0,'R');

if($transport_charges != 0){
$pdf->SetXY(154,$hsny+20);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, "Transport Charges" ,0,'R');
	
$pdf->SetXY(188,$hsny+20);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, number_format($transport_charges,2) ,0,'R');    
}
	
$pdf->SetXY(154,$hsny+29);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, "Grand Total" ,0,'R');
	
$pdf->SetXY(188,$hsny+29);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, number_format($grand_total,2) ,0,'R');



if(count($terms_condition)>0){
 $pdf->addPage();
$pdf->SetHeaderData('', '', 'PO PRINT', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
 
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);



/************************************Header *********************************/
$pdf->SetXY(70,10);
$pdf->SetFont('','B','20');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PURCHASE INVOICE" ,0,1,'L');

$pdf->Line(12, 22, 208, 22, $style);
	
$pdf->SetXY(15,24);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Terms and Condition :" ,0,1,'L');
$oldY1=0;
foreach($terms_condition as $k11=>$v11){
//	for($j=0;$j<38;$j++){
if($oldY1==0) $y =30;
else $y = $oldY1;
	
$pdf->SetXY(17,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(185,10, ($k11+1)." ." ,0,'L');
	
$pdf->SetXY(24,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(185,10, $v11->element_content ,0,'L');
//$pdf->Cell(0,0, $j ,0,1,'L');
$oldY1=$pdf->getY();
	
	if($oldY1 > 280)
	{
		$oldY1 = 0;
		$pdf->addPage();
$pdf->SetHeaderData('', '', 'PO PRINT', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
 
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);



/************************************Header *********************************/
$pdf->SetXY(70,10);
$pdf->SetFont('','B','20');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PURCHASE INVOICE" ,0,1,'L');

$pdf->Line(12, 22, 208, 22, $style);

	}
	
}

}


ob_end_clean();
  
  
   
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