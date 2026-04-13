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
    $words = array(0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
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
    return ($Rupees ? $Rupees . 'Rupees Only' : '');
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
public $company_logo;

    public function setLogo($company_logo){
        $this->logo = $company_logo;
    }
//Page header
    public function Header() {
        // Logo
          $image_file = public_path().'/images/'.$this->logo;
       $this->Image($image_file, 12 , 7, 22, 15, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
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
$pdf->setLogo($company_logo);
  

// add a page
$pdf->AddPage('P', $resolution);

$pdf->SetHeaderData('', '', 'INVOICE', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
 
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
$pdf->SetXY(90,10);
$pdf->SetFont('','B','20');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "INVOICE" ,0,1,'L');

$pdf->SetXY(170,2);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
if($copy!='')
{
$pdf->Cell(0,0,"(".$copy.")" ,0,1,'L');
}
$pdf->Line(12, 22, 208, 22, $style);

$pdf->SetXY(13,25);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $company_name ,0,1,'L');

$pdf->SetXY(13,33);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(95,10, $company_address ,0,1,'');

$pdf->SetXY(13,42);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN: ".$cin_no ,0,1,'L');

$pdf->SetXY(13,47);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN: ".$cmp_gst_no ,0,1,'L');

$pdf->SetXY(13,51);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State Name : ".$state_name.", Code : ".$state_code,0,1,'L');

$pdf->SetXY(13,55);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Website : ".$website_address,0,1,'L');

$pdf->SetXY(60,55);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ph No : ".$comp_contact_no,0,1,'L');

//$pdf->Line(12, 59, 208, 59, $style);
$pdf->Line(109, 22, 109, 59, $style);

$pdf->SetXY(13,59);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Buyer" ,0,1,'L');

$pdf->SetXY(13,63);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $customer_name ,0,1,'L');

$pdf->SetXY(13,67);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ship_to_address_1 ,0,'L');
$oldad = $pdf->getY();
if($alternative_telephone_number!=''){
    $altertelt='/'.$alternative_telephone_number;
}else{
    $altertelt='';
}
$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "PH : ".$contact_number."".$altertelt ,0,'L');
$oldad = $pdf->getY();
// if($invoice_type=="SAMPLE"){
// $pdf->SetXY(13,$oldad);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "Alternative Ph No : ".$alternative_telephone_number ,0,'L');
// $oldad = $pdf->getY();
// }

if($invoice_type!="SAMPLE"){
$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "GSTIN/UIN : ".$ship_gst_no ,0,'L');
$oldad = $pdf->getY();
}else{
$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "PAN NUMBER : ".$pan_number ,0,'L');
$oldad = $pdf->getY();
}
$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "State Name : ".$state_name_ship ,0,'L');

$pdf->SetXY(109,22);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Invoice No ",0,'L');

$pdf->SetXY(109,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $invoice_no,0,'L');
if($invoice_type!="SAMPLE"){
$pdf->SetXY(135,22);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "e-Way Bill No",0,'L');

$pdf->SetXY(135,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $eway_billno,0,'L');
}
$pdf->SetXY(160,22);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Dated ",0,'L');

$pdf->SetXY(160,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $invoice_date,0,'L');

$pdf->Line(109, 29, 208, 29, $style);

$pdf->SetXY(109,29);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Delivery Note ",0,'L');

$pdf->SetXY(109,33);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $dispatch_no,0,'L');

$pdf->SetXY(160,29);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Delivery Note Date ",0,'L');

$pdf->SetXY(160,33);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $dispatch_date,0,'L');

$pdf->Line(109, 37, 208, 37, $style);

$pdf->SetXY(109,37);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Customer Po Number",0,'L');

$pdf->SetXY(109,40);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $customer_po_number,0,'L');

$pdf->SetXY(160,37);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Other Reference(s)",0,'L');

$pdf->SetXY(160,40);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $remarks,0,'L');

$pdf->Line(109, 44, 208, 44, $style);


$pdf->SetXY(109,44);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Sales Order No",0,'L');

$pdf->SetXY(109,47);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $so_ref,0,'L');

$pdf->SetXY(160,44);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Dated",0,'L');

$pdf->SetXY(160,47);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $sales_order_date,0,'L');

$pdf->Line(109, 51, 208, 51, $style);

$pdf->SetXY(109,51);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "LR No",0,'L');

$pdf->SetXY(121,51);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $lr_no,0,'L');

$pdf->SetXY(109,55);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "LR Date ",0,'L');

if($lr_date!='00-00-0000' and $lr_date!='01-01-1970'){
$pdf->SetXY(121,55);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $lr_date,0,'L');
}

$pdf->SetXY(160,51);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Mode/Terms of Payment",0,'L');

$pdf->SetXY(160,54);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $payment_term_name,0,'L');

$pdf->Line(109, 68, 208, 68, $style);

$pdf->SetXY(109,59);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Despatched through",0,'L');

$pdf->SetXY(109,63);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $despatch_through,0,'L');

$pdf->SetXY(160,59);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Place Of Supply",0,'L');

$pdf->SetXY(160,63);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $state_name_ship,0,'L');

$pdf->Line(13, 59, 208, 59, $style);
$pdf->Line(160, 22, 160, 68, $style);
/************************************Header bottom line******************************/
$pdf->Line(109, 59, 109, 93, $style);
$pdf->Line(12, 93, 208, 93, $style);

$pdf->SetXY(109,69);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Terms of Delivery",0,'L');

$pdf->SetXY(109,74);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $deliveryterm,0,'L');


$pdf->SetXY(109,79);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $del_remarks,0,'L');

$pdf->SetXY(109,88);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "No of Box/Weight :",0,'L');

$pdf->SetXY(140,88);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $packing_qty ."/" . $packing_weight."Kgs",0,'L');

$pdf->SetXY(109,63);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(100,2, "Door Delivery",0,'L');
/************************************Header End******************************/

/************************************Body Lines Content******************************/

$pdf->Line(25, 93, 25, 290, $style);
//$pdf->Line(75, 93, 75, 193, $style);
//$pdf->Line(95, 93, 95, 193, $style);
//$pdf->Line(109, 93, 109, 290, $style);
// $pdf->Line(127, 93, 127, 290, $style);
// $pdf->Line(142, 93, 142, 290, $style);
$pdf->Line(150, 93, 150, 290, $style);
$pdf->Line(165, 93, 165, 290, $style);
$pdf->Line(183, 93, 183, 290, $style);

$pdf->Line(12, 100, 208, 100, $style);
//$pdf->Line(12, 193, 208, 193, $style);
//$pdf->Line(12, 183, 208, 183, $style);


$pdf->SetXY(13,94);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "S.No",0,'L');

$pdf->SetXY(25,94);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(140,2, "Description of Goods",0,'C');


// $pdf->SetXY(110,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(15,2, "MRP",0,'C');




$pdf->SetXY(150,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(16,2, "Per",0,'C');

$pdf->SetXY(165,94);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Quantity",0,'C');

// $pdf->SetXY(140,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(20,2, "Rate",0,'C');

// $pdf->SetXY(165,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(20,2, "Disc. %",0,'C');

$pdf->SetXY(185,94);
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
//$details_var=$value;
//$value=$linedata[0];
//for($j=0;$j<39;$j++) {
//dd($linedata);
 foreach($linedata as $key=>$value) { 
  
if($oldY==0) $y =100;
else $y = $oldY+4;  
$i++;
    
$pdf->SetXY(15,$y+1);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $i ,0,1,'L');
$y=$y+8;

$pdf->SetXY(26,$y-7);
$pdf->SetFont('','','9.5');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(124,2, $value['product'] ,0,'L');

$pdf->SetXY(28,$y-3);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(130,2, $value['batch_mfg'] ,0,'L');

$oldY=$pdf->getY();
        
// $pdf->SetXY(110,$y-7);
// $pdf->SetFont('','','8');
// $pdf->SetTextColor('0','0','0');
// if($value['mrp_price'] != '')
// $pdf->MultiCell(15,2, number_format($value['mrp_price'],'2','.','') ,0,'R');

$pdf->SetXY(165,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(18,2, $value['qty'] ,0,'C');
    
// $pdf->SetXY(139,$y-7);
// $pdf->SetFont('','','8');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(15,2, $value['unit_price'] ,0,'R');
    
$pdf->SetXY(150,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, $value['uom_code'] ,0,'C');
    
// $pdf->SetXY(168,$y-7);
// $pdf->SetFont('','','8');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(10,2, $value['discount_per'] ,0,'R');
    
$pdf->SetXY(175,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2,number_format( $value['amount'],'2','.','') ,0,'R');
    
    if($oldY > 270)
    {
        $oldY = 0;
        if($pdf->pageno()==1)
        {

        }
        $pdf->addPage();
        $pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
$pdf->SetXY(90,10);
$pdf->SetFont('','B','20');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "INVOICE" ,0,1,'L');

$pdf->Line(12, 22, 208, 22, $style);
$pdf->SetXY(13,25);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $company_name ,0,1,'L');



$pdf->SetXY(13,33);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(95,10, $company_address ,0,1,'');


$pdf->SetXY(13,42);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN: ".$cin_no ,0,1,'L');

$pdf->SetXY(13,47);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN: ".$cmp_gst_no ,0,1,'L');

$pdf->SetXY(13,51);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State Name : ".$state_name.", Code : ".$state_code,0,1,'L');

$pdf->SetXY(13,55);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Website : ".$website_address,0,1,'L');

$pdf->SetXY(60,55);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ph No : ".$comp_contact_no,0,1,'L');

//$pdf->Line(12, 59, 208, 59, $style);
$pdf->Line(109, 22, 109, 59, $style);

$pdf->SetXY(13,59);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Buyer" ,0,1,'L');

$pdf->SetXY(13,63);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $customer_name ,0,1,'L');

$pdf->SetXY(13,67);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ship_to_address_1 ,0,'L');
$oldad = $pdf->getY();
if($alternative_telephone_number!=''){
    $altertelt='/'.$alternative_telephone_number;
}else{
    $altertelt='';
}
$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "PH : ".$contact_number.''.$altertelt ,0,'L');
if($invoice_type!="SAMPLE"){
$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "GSTIN/UIN : ".$ship_gst_no ,0,'L');
}else{
$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "PAN NUMBER : ".$pan_number ,0,'L');
$oldad = $pdf->getY();
}
$pdf->SetXY(13,$oldad+1);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "State Name : ".$state_name_ship ,0,'L');

$pdf->SetXY(109,22);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Invoice No ",0,'L');

$pdf->SetXY(109,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $invoice_no,0,'L');
if($invoice_type!="SAMPLE"){
$pdf->SetXY(135,22);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "e-Way Bill No",0,'L');

$pdf->SetXY(135,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $eway_billno,0,'L');
}
$pdf->SetXY(160,22);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Dated ",0,'L');

$pdf->SetXY(160,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $invoice_date,0,'L');

$pdf->Line(109, 29, 208, 29, $style);

$pdf->SetXY(109,29);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Delivery Note ",0,'L');

$pdf->SetXY(109,33);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $dispatch_no,0,'L');

$pdf->SetXY(160,29);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Delivery Note Date ",0,'L');

$pdf->SetXY(160,33);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $dispatch_date,0,'L');

$pdf->Line(109, 37, 208, 37, $style);

$pdf->SetXY(109,37);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Customer Po Number",0,'L');

$pdf->SetXY(109,40);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $customer_po_number,0,'L');

$pdf->SetXY(160,37);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Other Reference(s)",0,'L');

$pdf->SetXY(160,40);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $remarks,0,'L');

$pdf->Line(109, 44, 208, 44, $style);


$pdf->SetXY(109,44);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Sales Order No",0,'L');

$pdf->SetXY(109,47);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $so_ref,0,'L');

$pdf->SetXY(160,44);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, 'Dated',0,'L');

$pdf->SetXY(160,47);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $sales_order_date,0,'L');

$pdf->Line(109, 51, 208, 51, $style);


$pdf->SetXY(109,51);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "LR No",0,'L');

$pdf->SetXY(121,51);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $lr_no,0,'L');

$pdf->SetXY(109,55);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "LR Date ",0,'L');

if($lr_date!='00-00-0000' and $lr_date!='01-01-1970'){
$pdf->SetXY(121,55);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $lr_date,0,'L');
}

$pdf->SetXY(160,51);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Mode/Terms of Payment",0,'L');

$pdf->SetXY(160,54);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $payment_term_name,0,'L');

$pdf->Line(109, 68, 208, 68, $style);

$pdf->SetXY(109,59);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Despatched through",0,'L');

$pdf->SetXY(109,63);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $despatch_through,0,'L');

$pdf->SetXY(160,59);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Place Of Supply",0,'L');

$pdf->SetXY(160,63);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $state_name_ship,0,'L');

$pdf->Line(13, 59, 208, 59, $style);
$pdf->Line(160, 22, 160, 68, $style);
/************************************Header bottom line******************************/
$pdf->Line(109, 59, 109, 93, $style);
$pdf->Line(12, 93, 208, 93, $style);

$pdf->SetXY(109,69);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Terms of Delivery",0,'L');
        
$pdf->SetXY(109,74);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $deliveryterm,0,'L');

$pdf->SetXY(109,88);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "No of Box/Weight :",0,'L');

$pdf->SetXY(140,88);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $packing_qty ."/" . $packing_weight,0,'L');

// $pdf->SetXY(109,63);
// $pdf->SetFont('','B','10');
//$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(100,2, "Door Delivery",0,'L');
/************************************Header End******************************/

/************************************Body Lines Content******************************/


$pdf->Line(25, 93, 25, 290, $style);
//$pdf->Line(75, 93, 75, 193, $style);
//$pdf->Line(95, 93, 95, 193, $style);
//$pdf->Line(109, 93, 109, 290, $style);
// $pdf->Line(127, 93, 127, 290, $style);
// $pdf->Line(142, 93, 142, 290, $style);
$pdf->Line(150, 93, 150, 290, $style);
$pdf->Line(165, 93, 165, 290, $style);
$pdf->Line(183, 93, 183, 290, $style);


$pdf->Line(12, 100, 208, 100, $style);
//$pdf->Line(12, 193, 208, 193, $style);
//$pdf->Line(12, 183, 208, 183, $style);


$pdf->SetXY(13,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "S.No",0,'L');

$pdf->SetXY(23,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Description of Goods",0,'C');


// $pdf->SetXY(110,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(15,2, "MRP",0,'C');

$pdf->SetXY(150,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(16,2, "Per",0,'C');

$pdf->SetXY(165,94);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Quantity",0,'C');

// $pdf->SetXY(140,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(20,2, "Rate",0,'C');

// $pdf->SetXY(165,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(20,2, "Disc. %",0,'C');

$pdf->SetXY(185,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Amount",0,'C');
    }
    
}

/************************************Body Lines Content End******************************/

/************************************ Footer ******************************/



//Gst Calculation End
$total = $pdf->getNumPages();
    if($pdf->pageNo()==$total)
    {   
    if($oldY < 250)
    {
    $pdf->Rect(12, 250, 196, 40,'DF', "",  array(255, 255, 255));

//$pdf->Line(12, 193, 208, 193, $style);
$pdf->Line(150, 250, 150, 260, $style);
$pdf->Line(150, 260, 208, 260, $style);
$pdf->Line(165, 250, 165, 260, $style);
$pdf->Line(183, 250, 183, 260, $style);

$pdf->SetXY(152,252);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Total",0,'L');

    
$pdf->SetXY(155,252);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2,number_format( $qtytotal,'2','.',''),0,'R');

$pdf->SetXY(175,252);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2,number_format( $sub_total,'2','.',''),0,'R');

/*$pdf->SetXY(13,195);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Amount Chargeable (in words)",0,'L');

$pdf->SetXY(13,200);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, convert_number_to_words(round($sub_total)),0,'L'); */

// $pdf->SetXY(70,193);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, "Trade Discount",0,'L');

// if($trade_discount_pre!='')
// {
// $pdf->SetXY(97,193);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, "( ".$trade_discount_pre."% )",0,'L'); 
// }



// $pdf->SetXY(50,193);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, number_format( $trade_discount,'2','.',''),0,'R'); 

// $ttt =  $sub_total - $trade_discount;
// $ta=$ttt+$gsttotal ;
// $round_off = $ta-(round($ta));
// //dd($gsttotal);
// $ka=$gsttotal/2;
// $words = '';
// if(count($gst) > 0 ){
// $words = preg_replace('/[^a-zA-Z]/', '', $gst[0]['tax_group_name']);
// if($words == "GST"){

//     $pdf->SetXY(70,198);
//     $pdf->SetFont('','B','8');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(150,2, "SGST",0,'L');

//     $pdf->SetXY(193,198);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     //$pdf->Cell(0,0,$ka ,0,1,'L');
//     $pdf->MultiCell(150,2, number_format( $ka,'2','.',''),0,'L'); 


//     $pdf->SetXY(70,201);
//     $pdf->SetFont('','B','8');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(150,2, "CGST",0,'L');

//     $pdf->SetXY(193,201);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(150,2, number_format( $ka,'2','.',''),0,'L'); 
// }else{
//     $pdf->SetXY(70,198);
//     $pdf->SetFont('','B','8');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(150,2, "IGST",0,'L');

//     $pdf->SetXY(50,198);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     //$pdf->Cell(0,0,$ka ,0,1,'L');
//     $pdf->MultiCell(150,2, number_format( $gsttotal,'2','.',''),0,'R'); 
// }
// }

// $pdf->SetXY(70,205);
// $pdf->SetFont('','B','8');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, "Round Off",0,'L');


// $pdf->SetXY(48,205);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, round($round_off,2),0,'R'); 
// //$pdf->MultiCell(150,2,(round($ttt)),'2','.',''),0,'L'); 
// // $pdf->Cell(0,0,round($round_off,2) ,0,1,'R');

// $pdf->SetXY(70,209);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, "Total",0,'L');

// $pdf->SetXY(50,210);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// //$pdf->MultiCell(150,2,(round($ttt)),'2','.',''),0,'L');
//  $pdf->MultiCell(150,2, (round($ta)).'.00',0,'R'); 
// // $pdf->Cell(0,0,(round($ta)).'.00' ,0,1,'L');

$pdf->SetXY(13,250);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Invoice Amount (in words) :",0,'L');

$pdf->SetXY(57,250);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2,"Nil",0,'L');

//    $pdf->Line(180, 197, 208, 197, $style);
//     $pdf->Line(180, 205, 208, 205, $style);
//     $pdf->Line(180, 209, 208, 209, $style);

//     $pdf->Line(12, 220, 208, 220, $style);
//     $pdf->Line(12, 230, 208, 230, $style);
//     $pdf->Line(75, 220, 75, 250, $style);
//     $pdf->Line(102, 220, 102, 250, $style);
    
//     $pdf->Line(184, 220, 184, 250, $style);
//     /*AMOUNT LINE*/


// if($words == "GST"){
    
//     $pdf->Line(102, 225, 184, 225, $style);
//     $pdf->Line(122, 225, 122, 250, $style);
//     $pdf->Line(162, 225, 162, 250, $style);
//     $pdf->Line(144, 220, 144, 250, $style);

//     $pdf->SetXY(113,220);   
//     $pdf->SetFont('','B','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(50,2, "Central Tax",0,'L');
    
//     $pdf->SetXY(105,225);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(50,2, "Rate",0,'L');

//     $pdf->SetXY(125,225);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(50,2, "Amount",0,'L');

//     $pdf->SetXY(153,220);
//     $pdf->SetFont('','B','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(50,2, "State Tax",0,'L');

//     $pdf->SetXY(145,225);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(50,2, "Rate",0,'L');

//     $pdf->SetXY(165,225);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(50,2, "Amount",0,'L');

// }else{
//     $pdf->SetXY(140,220);
//     $pdf->SetFont('','B','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(50,2, "IGST",0,'L');

//     $pdf->SetXY(125,225);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(50,2, "Rate",0,'L');

//     $pdf->SetXY(155,225);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(50,2, "Amount",0,'L');
// }


// $pdf->SetXY(13,223);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, "HSN/SAC",0,'L');

// $pdf->SetXY(83,221);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(15,2, "Taxable Value",0,'L');

// $pdf->SetXY(193,220);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(50,2, "Total",0,'L');

// $pdf->SetXY(70,250);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(50,2, "Tax Total",0,'L');

// $totaaaa = $gsttotal ;
// $val=($gsttotal/2);

// $pdf->SetXY(193,250);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(50,2, round($totaaaa).'.00',0,'L');

// $pdf->Line(12, 250, 208, 250, $style);
// $pdf->Line(12, 255, 208, 255, $style);

// $pdf->SetXY(13,255);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, "Tax Amount (in words) :",0,'L');

// $pdf->SetXY(13,260);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, convert_number_to_words(round($totaaaa)),0,'L');


//Gst calculation


// $hsn_y=0;
// $hsny=193;
// $gst_total=0;
// $net_total=0;
//  $gross_total=0;




// $sgat=0;
// $cgst=0;
// $gsttot=0;
// $gsttot=208;
// foreach($gst as $gstvalue)
// {
 
//     if($hsn_y==0)
//     {
//         $hsny=218;        
//     }
//     else
//     {
//         $hsny=$hsny+4;
//     }
        
//     $gsttot=$gsttot+12;
  

// $pdf->SetXY(13,$hsny+16);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(105,10, $gstvalue['hsn'] ,0,'L');
// $hsn_y=$pdf->getY();
 
// if($words == "GST"){
//     $pdf->SetXY(102,$hsny+16);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(19,10, $gstvalue['sgcgst']." %" ,0,'R');

//     $pdf->SetXY(123,$hsny+16);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(19,10,number_format( $gstvalue['sgst_val'],'2','.','') ,0,'R');

//     $pdf->SetXY(142,$hsny+16);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(19,10, $gstvalue['sgcgst']." %" ,0,'R');

//     $pdf->SetXY(164,$hsny+16);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(19,10, number_format($gstvalue['sgst_val'],'2','.','') ,0,'R');

// }else{
//     // dd($gstvalue);
//     $pdf->SetXY(115,$hsny+16);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(19,10, $gstvalue['gst']." %" ,0,'R');

//     $pdf->SetXY(150,$hsny+16);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(19,10, number_format($gstvalue['gst_val'],'2','.','') ,0,'R');
// }
//     $pdf->SetXY(77,$hsny+16);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(19,10, number_format($gstvalue['amount'],'2','.','') ,0,'R');
    
//     $oldslab=$pdf->getY();
        
//     $pdf->SetXY(180,$hsny+16);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(23,10, number_format($gstvalue['gst_val'],'2','.','') ,0,'R');
    
    
    $pdf->SetXY(13,265);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Company’s Service Tax No.",0,'L');

$pdf->SetXY(56,265);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, ": "."AAACD1708PSD001",0,'L');

$pdf->SetXY(13,270);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Company’s PAN",0,'L');

$pdf->SetXY(56,270);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, ": "."AAACD1708P",0,'L');

$pdf->SetXY(13,274);
$pdf->SetFont('','U','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Declaration",0,'L');

$pdf->SetXY(13,278);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "We declare that this invoice shows the actual price of the goods described and that all",0,'L');


    
//}

$pdf->SetXY(110,270);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "for Dr. JRK'S Research & Pharmaceutical Pvt Ltd.",0,'C');

$pdf->SetXY(120,283);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Authorised Signatory",0,'C');

$pdf->Line(110, 268, 110, 290, $style);
$pdf->Line(110, 268, 208, 268, $style);
    }
    else
    {
    $pdf->addPage();
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
$pdf->SetXY(90,10);
$pdf->SetFont('','B','20');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "INVOICE" ,0,1,'L');

$pdf->Line(12, 22, 208, 22, $style);

$pdf->SetXY(13,25);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $company_name ,0,1,'L');

$pdf->SetXY(13,29);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "(".$company_name.")" ,0,1,'L');

$pdf->SetXY(13,33);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(95,10, $company_address ,0,1,'');


$pdf->SetXY(13,42);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN: ".$cin_no ,0,1,'L');

$pdf->SetXY(13,47);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN: ".$cmp_gst_no ,0,1,'L');

$pdf->SetXY(13,52);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State Name : ".$state_name.", Code : ".$state_code,0,1,'L');



$pdf->SetXY(13,55);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Website : ".$website_address,0,1,'L');

$pdf->SetXY(60,55);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ph No : ".$comp_contact_no,0,1,'L');

//$pdf->Line(12, 59, 208, 59, $style);
$pdf->Line(109, 22, 109, 59, $style);

$pdf->SetXY(13,59);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Buyer" ,0,1,'L');

$pdf->SetXY(13,63);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $customer_name ,0,1,'L');

$pdf->SetXY(13,67);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ship_to_address_1 ,0,'L');
$oldad = $pdf->getY();
if($alternative_telephone_number!=''){
    $altertelt='/'.$alternative_telephone_number;
}else{
    $altertelt='';
}
$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "PH : ".$contact_number.''. $altertelt ,0,'L');
if($invoice_type!="SAMPLE"){
$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "GSTIN/UIN : ".$ship_gst_no ,0,'L');
}else{
$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "PAN NUMBER : ".$pan_number ,0,'L');
$oldad = $pdf->getY();
}
$pdf->SetXY(13,$oldad+8);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "State Name : ".$state_name_ship ,0,'L');

$pdf->SetXY(109,22);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Invoice No ",0,'L');

$pdf->SetXY(109,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $invoice_no,0,'L');
if($invoice_type!="SAMPLE"){
$pdf->SetXY(135,22);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "e-Way Bill No",0,'L');

$pdf->SetXY(135,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $eway_billno,0,'L');
}
$pdf->SetXY(160,22);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Dated ",0,'L');

$pdf->SetXY(160,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $invoice_date,0,'L');

$pdf->Line(109, 29, 208, 29, $style);

$pdf->SetXY(109,29);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Delivery Note ",0,'L');

$pdf->SetXY(109,33);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $dispatch_no,0,'L');

$pdf->SetXY(160,29);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Delivery Note Date ",0,'L');

$pdf->SetXY(160,33);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $dispatch_date,0,'L');

$pdf->Line(109, 37, 208, 37, $style);

$pdf->SetXY(109,37);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Customer Po Number",0,'L');

$pdf->SetXY(109,40);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $customer_po_number,0,'L');

$pdf->SetXY(160,37);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Other Reference(s)",0,'L');

$pdf->SetXY(160,40);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $remarks,0,'L');

$pdf->Line(109, 44, 208, 44, $style);


$pdf->SetXY(109,44);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Sales Order No",0,'L');

$pdf->SetXY(109,47);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $sales_order_no,0,'L');

$pdf->SetXY(160,44);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Dated",0,'L');

$pdf->SetXY(160,47);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $sales_order_date,0,'L');

$pdf->Line(109, 51, 208, 51, $style);


$pdf->SetXY(109,51);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "LR No",0,'L');

$pdf->SetXY(121,51);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $lr_no,0,'L');

$pdf->SetXY(109,55);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "LR Date ",0,'L');

if($lr_date!='00-00-0000' and $lr_date!='01-01-1970'){
$pdf->SetXY(121,55);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $lr_date,0,'L');
}

$pdf->SetXY(160,51);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Mode/Terms of Payment",0,'L');

$pdf->SetXY(160,54);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $payment_term_name,0,'L');

$pdf->Line(109, 68, 208, 68, $style);

$pdf->SetXY(109,59);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Despatched through",0,'L');

$pdf->SetXY(109,63);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $despatch_through,0,'L');

$pdf->SetXY(160,59);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Place Of Supply",0,'L');

$pdf->SetXY(160,63);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $state_name_ship,0,'L');

$pdf->Line(13, 59, 208, 59, $style);
$pdf->Line(160, 22, 160, 68, $style);
/************************************Header bottom line******************************/
$pdf->Line(109, 59, 109, 93, $style);
$pdf->Line(12, 93, 208, 93, $style);

$pdf->SetXY(109,69);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Terms of Delivery",0,'L');
        
$pdf->SetXY(109,74);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $deliveryterm,0,'L');

$pdf->SetXY(109,88);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "No of Box/Weight :",0,'L');

$pdf->SetXY(140,88);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $packing_qty ."/" . $packing_weight,0,'L');

$pdf->SetXY(109,63);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(100,2, "Door Delivery",0,'L');
/************************************Header End******************************/

/************************************Body Lines Content******************************/

$pdf->Line(25, 93, 25, 290, $style);
//$pdf->Line(75, 93, 75, 193, $style);
//$pdf->Line(95, 93, 95, 193, $style);
//$pdf->Line(109, 93, 109, 290, $style);
// $pdf->Line(127, 93, 127, 290, $style);
// $pdf->Line(142, 93, 142, 290, $style);
// $pdf->Line(159, 93, 159, 290, $style);
$pdf->Line(150, 93, 150, 290, $style);
$pdf->Line(165, 93, 165, 290, $style);
$pdf->Line(183, 93, 183, 290, $style);


$pdf->Line(12, 100, 208, 100, $style);
//$pdf->Line(12, 193, 208, 193, $style);
//$pdf->Line(12, 183, 208, 183, $style);
$pdf->SetXY(13,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "S.No",0,'L');

$pdf->SetXY(23,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Description of Goods",0,'C');


// $pdf->SetXY(110,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(15,2, "MRP",0,'C');


$pdf->SetXY(150,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(16,2, "Per",0,'C');

$pdf->SetXY(165,94);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Quantity",0,'C');

// $pdf->SetXY(140,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(20,2, "Rate",0,'C');

// $pdf->SetXY(165,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(20,2, "Disc. %",0,'C');

$pdf->SetXY(185,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Amount",0,'C');
        
        $pdf->SetXY(16,144);
$pdf->SetFont('','B','14');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "<---------------------------------------------------End of Page-------------------------------------->",0,'C');
        
$pdf->Rect(12, 250, 196, 40,'DF', "",  array(255, 255, 255));

$pdf->Line(150, 250, 150, 260, $style);
$pdf->Line(150, 260, 208, 260, $style);
$pdf->Line(165, 250, 165, 260, $style);
$pdf->Line(183, 250, 183, 260, $style);


$pdf->SetXY(152,252);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Total",0,'L');

    
$pdf->SetXY(155,252);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2,number_format( $qtytotal,'2','.',''),0,'R');

$pdf->SetXY(175,252);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2,number_format( $sub_total,'2','.',''),0,'R');
/*
$pdf->SetXY(13,195);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Amount Chargeable (in words)",0,'L');

$pdf->SetXY(13,200);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, convert_number_to_words(round($sub_total)),0,'L');*/


// $pdf->SetXY(70,193);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, "Trade Discount",0,'L');

// $pdf->SetXY(193,193);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, number_format( $trade_discount,'2','.',''),0,'L'); 

// $ttt =  $sub_total - $trade_discount;
// $ta=$ttt+$gsttotal ;
// $round_off = $ta-(round($ta));
// $ka=$gsttotal/2;
// $words = '';
// if(count($gst) > 0 ){
// $words = preg_replace('/[^a-zA-Z]/', '', $gst[0]['tax_group_name']);
// if($words == "GST"){
//     $pdf->SetXY(70,198);
//     $pdf->SetFont('','B','8');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(150,2, "SGST",0,'L');

//     $pdf->SetXY(193,198);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     //$pdf->Cell(0,0,$ka ,0,1,'L');
//     $pdf->MultiCell(150,2, number_format( $ka,'2','.',''),0,'L'); 


//     $pdf->SetXY(70,201);
//     $pdf->SetFont('','B','8');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(150,2, "CGST",0,'L');

//     $pdf->SetXY(193,201);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(150,2, number_format( $ka,'2','.',''),0,'L'); 
// }else{
//     $pdf->SetXY(70,198);
//     $pdf->SetFont('','B','8');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(150,2, "IGST",0,'L');

//     $pdf->SetXY(193,198);
//     $pdf->SetFont('','','9');
//     $pdf->SetTextColor('0','0','0');
//     //$pdf->Cell(0,0,$ka ,0,1,'L');
//     $pdf->MultiCell(150,2, number_format( $gsttotal,'2','.',''),0,'L'); 
// }
// }

// $pdf->SetXY(70,205);
// $pdf->SetFont('','B','8');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, "Round Off",0,'L');


// $pdf->SetXY(193,205);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// //$pdf->MultiCell(150,2,(round($ttt)),'2','.',''),0,'L'); 
// $pdf->Cell(0,0,round($round_off,2) ,0,1,'L');

// $pdf->SetXY(70,209);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, "Total",0,'L');

// $pdf->SetXY(191,210);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// //$pdf->MultiCell(150,2,(round($ttt)),'2','.',''),0,'L'); 
// $pdf->Cell(0,0,(round($ta)).'.00' ,0,1,'L');

// $pdf->SetXY(13,209);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, "Invoice Amount (in words) :",0,'L');

// $pdf->SetXY(13,214);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, convert_number_to_words(round($ta)),0,'L');


//  $pdf->Line(180, 197, 208, 197, $style);
//     $pdf->Line(180, 205, 208, 205, $style);
//     $pdf->Line(180, 209, 208, 209, $style);

//     $pdf->Line(12, 220, 208, 220, $style);
//     $pdf->Line(12, 230, 208, 230, $style);
//     $pdf->Line(75, 220, 75, 250, $style);
//     $pdf->Line(102, 220, 102, 250, $style);
    
//     $pdf->Line(184, 220, 184, 250, $style);


//     if($words == "GST"){
//         $pdf->Line(102, 225, 184, 225, $style);
//         $pdf->Line(122, 225, 122, 250, $style);
//         $pdf->Line(162, 225, 162, 250, $style);
//         $pdf->Line(144, 220, 144, 250, $style);

//         $pdf->SetXY(113,220);   
//         $pdf->SetFont('','B','9');
//         $pdf->SetTextColor('0','0','0');
//         $pdf->MultiCell(50,2, "Central Tax",0,'L');
        
//         $pdf->SetXY(105,225);
//         $pdf->SetFont('','','9');
//         $pdf->SetTextColor('0','0','0');
//         $pdf->MultiCell(50,2, "Rate",0,'L');

//         $pdf->SetXY(125,225);
//         $pdf->SetFont('','','9');
//         $pdf->SetTextColor('0','0','0');
//         $pdf->MultiCell(50,2, "Amount",0,'L');

//         $pdf->SetXY(153,220);
//         $pdf->SetFont('','B','9');
//         $pdf->SetTextColor('0','0','0');
//         $pdf->MultiCell(50,2, "State Tax",0,'L');

//         $pdf->SetXY(145,225);
//         $pdf->SetFont('','','9');
//         $pdf->SetTextColor('0','0','0');
//         $pdf->MultiCell(50,2, "Rate",0,'L');

//         $pdf->SetXY(165,225);
//         $pdf->SetFont('','','9');
//         $pdf->SetTextColor('0','0','0');
//         $pdf->MultiCell(50,2, "Amount",0,'L');
//     }else{
//         $pdf->SetXY(140,220);
//         $pdf->SetFont('','B','9');
//         $pdf->SetTextColor('0','0','0');
//         $pdf->MultiCell(50,2, "IGST",0,'L');

//         $pdf->SetXY(125,225);
//         $pdf->SetFont('','','9');
//         $pdf->SetTextColor('0','0','0');
//         $pdf->MultiCell(50,2, "Rate",0,'L');

//         $pdf->SetXY(155,225);
//         $pdf->SetFont('','','9');
//         $pdf->SetTextColor('0','0','0');
//         $pdf->MultiCell(50,2, "Amount",0,'L');
//     }

//     $pdf->SetXY(13,223);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, "HSN/SAC",0,'L');

// $pdf->SetXY(83,221);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(15,2, "Taxable Value",0,'L');

// $pdf->SetXY(193,220);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(50,2, "Total",0,'L');

// $pdf->SetXY(70,250);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(50,2, "Tax Total",0,'L');

// $totaaaa = $gsttotal ;
// $val=($gsttotal/2);

// $pdf->SetXY(193,250);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(50,2, round($totaaaa).'.00',0,'L');

// $pdf->Line(12, 250, 208, 250, $style);
// $pdf->Line(12, 255, 208, 255, $style);

// $pdf->SetXY(13,255);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, "Tax Amount (in words) :",0,'L');

// $pdf->SetXY(13,260);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, convert_number_to_words(round($totaaaa)),0,'L');



//Gst calculation




$hsn_y=0;
$hsny=193;
$gst_total=0;
$net_total=0;
 $gross_total=0;




// $sgat=0;
// $cgst=0;
// $gsttot=0;
// $gsttot=208;
// foreach($gst as $gstvalue)
// {
//     if($hsn_y==0)
//     {
//         $hsny=218;        
//     }
//     else
//     {
//         $hsny=$hsny+4;
//     }


// $pdf->SetXY(13,$hsny+16);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(105,10, $gstvalue['hsn'] ,0,'L');
// $hsn_y=$pdf->getY();
      
//     $gsttot=$gsttot+12;

//     if($words == "GST"){
//         $pdf->SetXY(102,$hsny+16);
//         $pdf->SetFont('','','9');
//         $pdf->SetTextColor('0','0','0');
//         $pdf->MultiCell(19,10, $gstvalue['sgcgst']." %" ,0,'R');

//         $pdf->SetXY(123,$hsny+16);
//         $pdf->SetFont('','','9');
//         $pdf->SetTextColor('0','0','0');
//         $pdf->MultiCell(19,10,number_format( $gstvalue['sgst_val'],'2','.','') ,0,'R');

    //     $pdf->SetXY(142,$hsny+16);
    //     $pdf->SetFont('','','9');
    //     $pdf->SetTextColor('0','0','0');
    //     $pdf->MultiCell(19,10, $gstvalue['sgcgst']." %" ,0,'R');

    //     $pdf->SetXY(164,$hsny+16);
    //     $pdf->SetFont('','','9');
    //     $pdf->SetTextColor('0','0','0');
    //     $pdf->MultiCell(19,10, number_format($gstvalue['sgst_val'],'2','.','') ,0,'R');
    // }else{
    //     $pdf->SetXY(115,$hsny+16);
    //     $pdf->SetFont('','','9');
    //     $pdf->SetTextColor('0','0','0');
    //     $pdf->MultiCell(19,10, $gstvalue['gst']." %" ,0,'R');

    //     $pdf->SetXY(150,$hsny+16);
    //     $pdf->SetFont('','','9');
    //     $pdf->SetTextColor('0','0','0');
    //     $pdf->MultiCell(19,10, number_format($gstvalue['gst_val'],'2','.','') ,0,'R');
    // }
        
    // $pdf->SetXY(77,$hsny+16);
    // $pdf->SetFont('','','9');
    // $pdf->SetTextColor('0','0','0');
    // $pdf->MultiCell(19,10, number_format($gstvalue['amount'],'2','.','') ,0,'R');
    
    // $oldslab=$pdf->getY();
        
    // $pdf->SetXY(180,$hsny+16);
    // $pdf->SetFont('','','9');
    // $pdf->SetTextColor('0','0','0');
    // $pdf->MultiCell(23,10, number_format($gstvalue['gst_val'],'2','.','') ,0,'R');

    
    $pdf->SetXY(13,265);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Company’s Service Tax No.",0,'L');

$pdf->SetXY(56,265);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, ": "."AAACD1708PSD001",0,'L');

$pdf->SetXY(13,270);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Company’s PAN",0,'L');

$pdf->SetXY(56,270);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, ": "."AAACD1708P",0,'L');

$pdf->SetXY(13,274);
$pdf->SetFont('','U','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Declaration",0,'L');

$pdf->SetXY(13,278);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "We declare that this invoice shows the actual price of the goods described and that all",0,'L');



    
//}
$pdf->SetXY(110,270);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "for ".$company_name,0,'C');

$pdf->SetXY(120,283);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Authorised Signatory",0,'C');

$pdf->Line(110, 268, 110, 290, $style);
$pdf->Line(110, 268, 208, 268, $style);
        
    }
}

if(count($terms_condition)>0){
 $pdf->addPage();
 $pdf->SetHeaderData('', '', 'INVOICE', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
 
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
$pdf->SetXY(90,10);
$pdf->SetFont('','B','20');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "INVOICE" ,0,1,'L');
$pdf->Line(12, 22, 208, 22, $style);
    
$pdf->SetXY(15,25);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Terms and Condition :" ,0,1,'L');
$oldY1=0;
foreach($terms_condition as $k11=>$v11){
//  for($j=0;$j<38;$j++){
if($oldY1==0) $y =30;
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
        $pdf->SetHeaderData('', '', 'INVOICE', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
 
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
$pdf->SetXY(90,10);
$pdf->SetFont('','B','20');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "INVOICE" ,0,1,'L');
        $pdf->Line(12, 22, 208, 22, $style);
    }
    
}

}

//Close and output PDF document
ob_end_clean();
       
if($print=='PRINT')
{                    
 $pdf->Output('example_007.pdf','FI');
 $pdf->close();

exit();
}
else
{
    
  $filename="uploads/soinvoiceupload/SOINV_".$invoice_no.".pdf";
  // dd($filename);
  $pdf->Output('uploads/soinvoiceupload/SOINV_'.$invoice_no.'.pdf', 'F');
}
//============================================================+
// END OF FILE
//============================================================+

/******************************************************************************************/ 
?>
