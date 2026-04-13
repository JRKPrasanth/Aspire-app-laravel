<?php
// dd('l');
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
   // return ($Rupees ? $Rupees . 'Rupees Only' : '');
   return ($Rupees ? $Rupees : 'Zero ');
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
            $this->SetY(-18);
            // Set font
            $this->SetFont('helvetica', '', 10);
            // Page number
          $this->Cell(0, 10, 'This is a computer generated proforma invoice',0, false, 'C', 0, '', 0, false, 'T', 'M');
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

$pdf->SetHeaderData('', '', 'PROFORMA INVOICE', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
 
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
$pdf->SetXY(75,10);
$pdf->SetFont('','B','20');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PROFORMA INVOICE" ,0,1,'L');
//dd($sub_total);
$pdf->SetXY(170,2);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
if($order_status_id !='APPROVED')
{
$pdf->Cell(0,0,"(Duplicate Copy)" ,0,1,'L');
}
$pdf->Line(12, 22, 208, 22, $style);

$pdf->SetXY(13,23);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Exporter:" ,0,1,'L');

$pdf->SetXY(13,28);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $company_name ,0,1,'L');

$pdf->SetXY(13,32);
$pdf->SetFont('','','7.5');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "(Formerly known as Dr. JRK'S Siddha Research and Pharmaceuticals Pvt Ltd.)" ,0,1,'L');


$pdf->SetXY(13,36);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(95,10, $company_address ,0,1,'');

$pdf->SetXY(13,45);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Phone: +91 44 3805 6262/ 6625 5000/ 6625 5777",0,1,'L');

$pdf->SetXY(13,49);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN: ".$cmp_gst_no ,0,1,'L');

$pdf->SetXY(13,53);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN: ".$cin_no ,0,1,'L');

//$pdf->Line(12, 59, 208, 59, $style);
$pdf->Line(109, 22, 109, 59, $style);

$pdf->SetXY(13,59);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Buyer:" ,0,1,'L');

$pdf->SetXY(13,63);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $customer_name_con ,0,1,'L');

$pdf->SetXY(13,67);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ship_to_address_1_con ,0,'L');
$oldad = $pdf->getY();

$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "TEL : ".$contact_number_con ,0,'L');

$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Email : ".$mail_con ,0,'L');

$pdf->SetXY(13,90);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Consignee:" ,0,1,'L');

$pdf->SetXY(13,94);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $customer_name_s ,0,1,'L');

$pdf->SetXY(13,98);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ship_to_address_1 ,0,'L');
$oldad = $pdf->getY();

$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "TEL : ".$contact_number ,0,'L');

$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Email : ".$mail_s ,0,'L');

$pdf->SetXY(109,23);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Invoice No. & Date",0,'L');

$pdf->SetXY(109,27);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $sales_order_no." & ".$sales_order_date,0,'L');

$pdf->SetXY(160,23);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Exporters Ref.",0,'L');

$pdf->SetXY(160,27);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "MJ 000 959 RBI",0,'L');

$pdf->Line(109, 32, 208, 32, $style);

$pdf->SetXY(109,33);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Buyers Order No. & Date",0,'L');

if($customer_po_number != ''){
$pdf->SetXY(109,37);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2,$customer_po_number."&".$sales_order_date,0,'L');    
}else{
$pdf->SetXY(109,37);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2,$sales_order_date,0,'L');
}
$pdf->Line(109, 42, 208, 42, $style);

$pdf->SetXY(109,43);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Other Reference(s)",0,'L');

$pdf->SetXY(109,46);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $remarks,0,'L');

$pdf->SetXY(109,53);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Exporter's IE Code ",0,'L');
//dd("dfds");

$pdf->SetXY(135,53);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, '0 496021150',0,'L');

$pdf->Line(109, 64, 208, 64, $style);
$pdf->Line(109, 69, 208, 69, $style);
$pdf->Line(160, 64, 160, 133, $style);

$pdf->SetXY(109,60);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "BANK DETAILS:",0,'C');

$pdf->SetXY(13,125);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Pre-Carriage",0,'L');

$pdf->SetXY(13,129);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $transport_mode,0,'L');

$pdf->SetXY(13,134);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Vessel/Flight No.",0,'L');

$pdf->SetXY(13,143);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Port of Discharge",0,'L');

$pdf->SetXY(13,147);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $port_of_discharge,0,'L');

$pdf->SetXY(13,152);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Marks & Nos.",0,'L');

$pdf->SetXY(13,156);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Container No./C.Seal No",0,'L');

$pdf->SetXY(60,125);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Place of Receipt Pre-Carriage:",0,'L');

$pdf->SetXY(60,129);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $receipt_pre_carriage,0,'L');

$pdf->SetXY(60,134);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Port of Loading",0,'L');

$pdf->SetXY(60,138);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $port_of_loading,0,'L');

$pdf->SetXY(60,143);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Final Destination",0,'L');

$pdf->SetXY(60,147);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $country_b,0,'L');

$pdf->SetXY(60,152);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "No. of Pkgs",0,'L');

$pdf->SetXY(109,125);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Country of Origin of Goods:",0,'L');

$pdf->SetXY(109,129);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $country_of_origin,0,'L');

$pdf->SetXY(160,125);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Country of Final Destination:",0,'L');

$pdf->SetXY(160,129);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $country_b,0,'L');

$pdf->SetXY(109,134);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Terms of Delivery and Payment",0,'L');

$pdf->SetXY(109,138);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $payment_term,0,'L');

$pdf->SetXY(109,142);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $deliveryterm,0,'L');

$pdf->SetXY(109,146);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $del_remarks,0,'L');

$pdf->Line(12, 59, 208, 59, $style);
$pdf->Line(12, 124, 208, 124, $style);
$pdf->Line(12, 133, 208, 133, $style);
$pdf->Line(12, 142, 109, 142, $style);
$pdf->Line(12, 151, 109, 151, $style);
$pdf->Line(60, 124, 60, 160, $style);
$pdf->Line(160, 22, 160, 32, $style); //header vertical line
/************************************Header bottom line******************************/
$pdf->Line(109, 58, 109, 160, $style);
$pdf->Line(12, 160, 208, 160, $style);

$pdf->SetXY(109,64);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Seller's - Account details",0,'L');

$pdf->SetXY(161,64);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Buyer's - Bank details",0,'L');

$pdf->SetXY(109,70);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $name_in_acc,0,'L');

$pdf->SetXY(109,75);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "A/C No. ".$acc_no,0,'L');

$pdf->SetXY(109,80);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "IFS CODE: ".$ifsc_code,0,'L');

$pdf->SetXY(109,85);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,3, $bank_name,0,'L');

$pdf->SetXY(109,90);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,3, $bank_branch,0,'L');

$pdf->SetXY(109,95);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,3, $bank_state,0,'L');

$pdf->SetXY(109,115);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,3, "SWIFT Code: IOBAINBBE52 (Large Advances- Irungattukottai)",0,'L');
/************************************Header End******************************/

/************************************Body Lines Content******************************/

$pdf->Line(21, 160, 21, 290, $style);
//$pdf->Line(75, 93, 75, 193, $style);
//$pdf->Line(95, 93, 95, 193, $style);
$pdf->Line(109, 160, 109, 290, $style);
$pdf->Line(124, 160, 124, 290, $style);
//$pdf->Line(138, 160, 138, 290, $style);
$pdf->Line(151, 160, 151, 290, $style);
 //$pdf->Line(166, 160, 166, 290, $style);
//$pdf->Line(178, 160, 178, 290, $style);
 $pdf->Line(182, 160, 182, 290, $style);

$pdf->Line(12, 170, 208, 170, $style);
//$pdf->Line(12, 193, 208, 193, $style);
//$pdf->Line(12, 183, 208, 183, $style);


$pdf->SetXY(12,161);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "S.No",0,'L');

$pdf->SetXY(23,161);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Description of Goods",0,'C');

$pdf->SetXY(26,165);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Siddha System/Cosmetic Products",0,'C');

$pdf->SetXY(109,161);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, "Batch No.",0,'C');


$pdf->SetXY(124,163);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, "No.of Unit",0,'C');

$currency = ucfirst(strtolower($invoice_currency));

$pdf->SetXY(154,162);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, "PRICE PER UNIT in ".$currency,0,'C');

$pdf->SetXY(189,161);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,2, "Amount",0,'C');

$pdf->SetXY(189,165);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,2, $currency,0,'C');

$i=0;
$oldY=0;
$dis_amt=0;
$disamt=0;
$gst_tot=0;
$discount=0;
$tax_amt=0;
//$details_var=$value;
//$value=$linedata[0];
//for($j=0;$j<28;$j++){
//dd($linedata);
 foreach($linedata as $key=>$value) { 
  
if($oldY==0) $y =170;
else $y = $oldY+4;  
$i++;
    
$pdf->SetXY(15,$y+1);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $i ,0,1,'L');
$y=$y+8;

$pdf->SetXY(21,$y-7);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(82,2, $value['product'] ,0,'L');
$y2=$pdf->getY();
$pdf->SetXY(21,$y2);
$pdf->SetFont('','','7');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(82,2, $value['batch_mfg'] ,0,'L');
$y1=$pdf->getY();
$y1=$y1+3;
$pdf->SetXY(21,$y1);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('220','20','60');
//$pdf->Cell(82,2, $value['comments'] ,0,'L');
$y10=$pdf->getY();
$y10=$y10+3;
if($value['uom_code']=="SET"){
foreach($value['bomprd1'] as $bk=>$bv){
	
	
	
	
	$pdf->SetXY(21,$y1+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');

$pdf->MultiCell(82,2, $bv ,0,'L');
$y1=$pdf->getY();

}
}
$oldY=$pdf->getY();
        
$pdf->SetXY(109,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
//if($value['mrp_price'] != '')
//$pdf->MultiCell(15,2, number_format($value['mrp_price'],'2','.','') ,0,'R');

$pdf->SetXY(129,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, $value['qty'] ,0,'C');
$y1=$pdf->getY();
$y1=$y1+5;
 if($value['uom_code']=="SET"){

foreach($value['bomprdqty1'] as $bqk=>$bqv){
	$pdf->SetXY(134,$y1+1);
$pdf->SetFont('','','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(82,2, $bqv ,0,'L');
$y1=$pdf->getY();

}
}   
    

$pdf->SetXY(166,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, $value['unit_price'] ,0,'R');
    
$pdf->SetXY(189,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,2,number_format( $value['amount'],'2','.','') ,0,'R');
    
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
$pdf->SetXY(75,10);
$pdf->SetFont('','B','20');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PROFORMA INVOICE" ,0,1,'L');

$pdf->SetXY(170,2);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
if($order_status_id !='APPROVED')
{
$pdf->Cell(0,0,"(Duplicate Copy)" ,0,1,'L');
}
$pdf->Line(12, 22, 208, 22, $style);

$pdf->SetXY(13,23);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Exporter:" ,0,1,'L');

$pdf->SetXY(13,28);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $company_name ,0,1,'L');

$pdf->SetXY(13,32);
$pdf->SetFont('','','7.5');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "(Formerly known as Dr. JRK'S Siddha Research and Pharmaceuticals Pvt Ltd.)" ,0,1,'L');


$pdf->SetXY(13,36);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(95,10, $company_address ,0,1,'');

$pdf->SetXY(13,45);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Phone: +91 44 3805 6262/ 6625 5000/ 6625 5777",0,1,'L');

$pdf->SetXY(13,49);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN: ".$cmp_gst_no ,0,1,'L');

$pdf->SetXY(13,53);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN: ".$cin_no ,0,1,'L');

//$pdf->Line(12, 59, 208, 59, $style);
$pdf->Line(109, 22, 109, 59, $style);

$pdf->SetXY(13,59);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Buyer:" ,0,1,'L');

$pdf->SetXY(13,63);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $customer_name_con ,0,1,'L');

$pdf->SetXY(13,67);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ship_to_address_1_con ,0,'L');
$oldad = $pdf->getY();

$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "TEL : ".$contact_number_con ,0,'L');

$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Email : ".$mail_con ,0,'L');

$pdf->SetXY(13,90);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Consignee:" ,0,1,'L');

$pdf->SetXY(13,94);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $customer_name_s ,0,1,'L');

$pdf->SetXY(13,98);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ship_to_address_1 ,0,'L');
$oldad = $pdf->getY();

$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "TEL : ".$contact_number ,0,'L');

$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Email : ".$mail_s ,0,'L');

$pdf->SetXY(109,23);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Invoice No. & Date",0,'L');

$pdf->SetXY(109,27);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $sales_order_no." & ".$sales_order_date,0,'L');

$pdf->SetXY(160,23);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Exporters Ref.",0,'L');

$pdf->SetXY(160,27);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "MJ 000 959 RBI",0,'L');

$pdf->Line(109, 32, 208, 32, $style);

$pdf->SetXY(109,33);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Buyers Order No. & Date",0,'L');

if($customer_po_number != ''){
$pdf->SetXY(109,37);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2,$customer_po_number."&".$sales_order_date,0,'L');    
}else{
$pdf->SetXY(109,37);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2,$sales_order_date,0,'L');
}
$pdf->Line(109, 42, 208, 42, $style);

$pdf->SetXY(109,43);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Other Reference(s)",0,'L');

$pdf->SetXY(109,46);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $remarks,0,'L');

$pdf->SetXY(109,53);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Exporter's IE Code ",0,'L');
//dd("dfds");

$pdf->SetXY(135,53);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, '0 496021150',0,'L');

$pdf->Line(109, 64, 208, 64, $style);
$pdf->Line(109, 69, 208, 69, $style);
$pdf->Line(160, 64, 160, 133, $style);

$pdf->SetXY(109,60);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "BANK DETAILS:",0,'C');

$pdf->SetXY(13,125);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Pre-Carriage",0,'L');

$pdf->SetXY(13,129);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $transport_mode,0,'L');

$pdf->SetXY(13,134);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Vessel/Flight No.",0,'L');

$pdf->SetXY(13,143);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Port of Discharge",0,'L');

$pdf->SetXY(13,147);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $port_of_discharge,0,'L');

$pdf->SetXY(13,152);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Marks & Nos.",0,'L');

$pdf->SetXY(13,156);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Container No./C.Seal No",0,'L');

$pdf->SetXY(60,125);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Place of Receipt Pre-Carriage:",0,'L');

$pdf->SetXY(60,129);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $receipt_pre_carriage,0,'L');

$pdf->SetXY(60,134);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Port of Loading",0,'L');

$pdf->SetXY(60,138);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $port_of_loading,0,'L');

$pdf->SetXY(60,143);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Final Destination",0,'L');

$pdf->SetXY(60,147);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $country_b,0,'L');

$pdf->SetXY(60,152);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "No. of Pkgs",0,'L');

$pdf->SetXY(109,125);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Country of Origin of Goods:",0,'L');

$pdf->SetXY(109,129);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $country_of_origin,0,'L');

$pdf->SetXY(160,125);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Country of Final Destination:",0,'L');

$pdf->SetXY(160,129);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $country_b,0,'L');

$pdf->SetXY(109,134);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Terms of Delivery and Payment",0,'L');

$pdf->SetXY(109,138);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $payment_term,0,'L');

$pdf->SetXY(109,142);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $deliveryterm,0,'L');

$pdf->SetXY(109,146);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $del_remarks,0,'L');

$pdf->Line(12, 59, 208, 59, $style);
$pdf->Line(12, 124, 208, 124, $style);
$pdf->Line(12, 133, 208, 133, $style);
$pdf->Line(12, 142, 109, 142, $style);
$pdf->Line(12, 151, 109, 151, $style);
$pdf->Line(60, 124, 60, 160, $style);
$pdf->Line(160, 22, 160, 32, $style); //header vertical line
/************************************Header bottom line******************************/
$pdf->Line(109, 58, 109, 160, $style);
$pdf->Line(12, 160, 208, 160, $style);

$pdf->SetXY(109,64);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Seller's - Account details",0,'L');

$pdf->SetXY(161,64);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Buyer's - Bank details",0,'L');

$pdf->SetXY(109,70);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $name_in_acc,0,'L');

$pdf->SetXY(109,75);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "A/C No. ".$acc_no,0,'L');

$pdf->SetXY(109,80);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "IFS CODE: ".$ifsc_code,0,'L');

$pdf->SetXY(109,85);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,3, $bank_name,0,'L');

$pdf->SetXY(109,90);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,3, $bank_branch,0,'L');

$pdf->SetXY(109,95);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,3, $bank_state,0,'L');

$pdf->SetXY(109,115);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,3, "SWIFT Code: IOBAINBBE52 (Large Advances- Irungattukottai)",0,'L');
/************************************Header End******************************/

/************************************Body Lines Content******************************/

$pdf->Line(21, 160, 21, 290, $style);
//$pdf->Line(75, 93, 75, 193, $style);
//$pdf->Line(95, 93, 95, 193, $style);
$pdf->Line(109, 160, 109, 290, $style);
$pdf->Line(124, 160, 124, 290, $style);
//$pdf->Line(138, 160, 138, 290, $style);
$pdf->Line(151, 160, 151, 290, $style);
 //$pdf->Line(166, 160, 166, 290, $style);
//$pdf->Line(178, 160, 178, 290, $style);
 $pdf->Line(182, 160, 182, 290, $style);

$pdf->Line(12, 170, 208, 170, $style);
//$pdf->Line(12, 193, 208, 193, $style);
//$pdf->Line(12, 183, 208, 183, $style);


$pdf->SetXY(12,161);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "S.No",0,'L');

$pdf->SetXY(23,161);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Description of Goods",0,'C');

$pdf->SetXY(26,165);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Siddha System/Cosmetic Products",0,'C');

$pdf->SetXY(109,161);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, "Batch No.",0,'C');


$pdf->SetXY(124,163);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, "No.of Unit",0,'C');

$currency = ucfirst(strtolower($invoice_currency));

$pdf->SetXY(154,162);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, "PRICE PER UNIT in ".$currency,0,'C');

$pdf->SetXY(189,161);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,2, "Amount",0,'C');

$pdf->SetXY(189,165);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,2, $currency,0,'C');
    }
    
}

/************************************Body Lines Content End******************************/

/************************************ Footer ******************************/



//Gst Calculation End
$total = $pdf->getNumPages();
    if($pdf->pageNo()==$total)
    {   
    if($oldY < 225)
    {
    $pdf->Rect(12, 222, 196, 68,'DF', "",  array(255, 255, 255));
//$pdf->Line(12, 278, 208, 278, $style);
$pdf->Line(12, 213, 208, 213, $style);
$pdf->SetXY(60,216);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Assessable Value",0,'L');

    
$pdf->SetXY(124,216);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2,number_format( $qtytotal,'2','.',''),0,'R');

$pdf->SetXY(189,216);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,2,number_format( $sub_total,'2','.',''),0,'R');

/*$pdf->SetXY(13,195);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Amount Chargeable (in words)",0,'L');

$pdf->SetXY(13,200);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, convert_number_to_words(round($sub_total)),0,'L'); */

$pdf->SetXY(80,227);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Trade Discount",0,'L');

$cashamount=($sub_total*$schemes_type_value)/100;
$pdf->SetXY(107,223);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(152,2,'( '.$schemes_type_value.' % ) ',0,'L'); 

$pdf->SetXY(56,223);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(152,2, number_format( $cashamount,\Session::get('decimal'),'.',''),0,'R'); 

$pdf->SetXY(80,223);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Cash Discount",0,'L');
/*deepika purpose:trade calculation*/
$trddis=$trade_discount_pre-$schemes_type_value;
$trddisamt=$trade_discount-$cashamount;	
/*end*/

/*if($trade_discount_pre>$schemes_type_value)
{
$trddis=$trade_discount_pre-$schemes_type_value;
$trddisamt=$trade_discount-$cashamount;	
}else{
$trddis=$schemes_type_value-$trade_discount_pre;
$trddisamt=$cashamount-$trade_discount;			
}*/


if($trade_discount_pre!='')
{
$pdf->SetXY(107,227);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "( ".Round($trddis,2)." % )",0,'L'); 
}



$pdf->SetXY(56,227);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(152,2, number_format( $trddisamt,\Session::get('decimal'),'.',''),0,'R'); 

if($trade_discount > 0)
{$ttt =  $sub_total - $trade_discount;
}else
{$ttt =  $sub_total - $cashamount;
}

$ta=$ttt+$gsttotal ;
//dd($ta);
$round_off = $ta-(round($ta));
//dd($gsttotal);
$ka=$gsttotal/2;
$words = '';

    $pdf->SetXY(80,231);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(150,2, "Freight and Forwarding Cost ",0,'L');

    $pdf->SetXY(50,231);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    //$pdf->Cell(0,0,$ka ,0,1,'L');
    $pdf->MultiCell(158,2,"",0,'R'); 
    
    $pdf->SetXY(80,235);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
   // $pdf->MultiCell(150,2, "TCS ",0,'L');

    $pdf->SetXY(80,235);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    //$pdf->Cell(0,0,$ka ,0,1,'L');
    $pdf->MultiCell(158,2,"",0,'R');

$pdf->SetXY(80,238);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Round Off",0,'L');

$pdf->SetXY(48,238);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(160,2, round($round_off,2),0,'R'); 
//$pdf->MultiCell(150,2,(round($ttt)),'2','.',''),0,'L'); 
//$pdf->Cell(0,0,round($round_off,2) ,0,1,'R');

$pdf->SetXY(80,242);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Grand Total",0,'L');

$pdf->SetXY(50,242);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(150,2,(round($ttt)),'2','.',''),0,'L');
 $pdf->MultiCell(158,2, (round($ta)).'.00',0,'R'); 
//$pdf->Cell(0,0,(round($ta)).'.00' ,0,1,'L');

if($order_type_id == 'EXPORT' || $order_type_id == 'EXPORT SAMPLE'){
     $curr = ucfirst(strtolower($invoice_currency)).'s Only';
    }else{
    $curr = 'Rupees Only';
    }

$pdf->SetXY(13,227);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "ALL PRICES IN ".strtoupper($curr),0,'L');

$pdf->SetXY(13,231);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Mode of Transport: ".$transport_mode,0,'L');

$pdf->SetXY(13,235);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, '"CIF Incoterms 2010 - '.$port_of_discharge.' '.$country_b.'"',0,'L');

$pdf->SetXY(13,248);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Amount :",0,'L');

 

$pdf->SetXY(28,248);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, convert_number_to_words(round($ta)).$curr,0,'L');


  /* $pdf->Line(180, 197, 208, 197, $style);
    $pdf->Line(180, 205, 208, 205, $style);
    $pdf->Line(180, 209, 208, 209, $style);
    $pdf->Line(12, 220, 208, 220, $style);
    $pdf->Line(12, 230, 208, 230, $style);
    $pdf->Line(75, 220, 75, 255, $style);
    $pdf->Line(102, 220, 102, 255, $style);
    
    $pdf->Line(184, 220, 184, 255, $style);*/
    /*AMOUNT LINE*/


$totaaaa = $gsttotal ;
$val=($gsttotal/2);

/*$pdf->SetXY(193,256);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2, round($totaaaa).'.00',0,'L');*/

$pdf->Line(12, 247, 208, 247, $style);
//$pdf->Line(12, 261, 208, 261, $style);

/*$pdf->SetXY(13,262);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Tax Amount (in words) :",0,'L');

$pdf->SetXY(50,262);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, convert_number_to_words(round($totaaaa)).$curr,0,'L');*/


//Gst calculation


$hsn_y=0;
$hsny=193;
$gst_total=0;
$net_total=0;
 $gross_total=0;




$sgat=0;
$cgst=0;
$gsttot=0;
$gsttot=208;
// dd($gst);
foreach($gst as $gstvalue)
{
 
    if($hsn_y==0)
    {
        $hsny=214;        
    }
    else
    {
        $hsny=$hsny+4;
    }
        
    $gsttot=$gsttot+12;
  

/*$pdf->SetXY(13,$hsny+16);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(105,10, $gstvalue['hsn'] ,0,'L');*/
$hsn_y=$pdf->getY();
 

    /*$pdf->SetXY(77,$hsny+16);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(19,10, number_format($gstvalue['amount'],'2','.','') ,0,'R');*/
    
    $oldslab=$pdf->getY();
        
   /* $pdf->SetXY(180,$hsny+16);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(23,10, number_format($gstvalue['gst_val'],'2','.','') ,0,'R');*/
    
    
$pdf->SetXY(13,255);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Gross Weight :",0,'L');

$pdf->SetXY(55,255);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "(Approximated)",0,'L');

$pdf->SetXY(56,255);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "",0,'L');

$pdf->SetXY(13,260);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Net Weight :",0,'L');

$pdf->SetXY(55,260);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "(Approximated)",0,'L');

$pdf->SetXY(56,260);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "",0,'L');

$pdf->SetXY(13,268);
$pdf->SetFont('','U','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Declaration",0,'L');

$pdf->SetXY(13,272);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.",0,'L');

$pdf->SetXY(13,283);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Date:",0,'L');

$pdf->SetXY(22,283);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2,$sales_order_date ,0,'L');
    
}

$pdf->SetXY(110,270);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "for Dr. JRK'S Research & Pharmaceutical Pvt Ltd.",0,'C');

$pdf->SetXY(120,283);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Authorised Signatory",0,'R');

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
$pdf->SetXY(75,10);
$pdf->SetFont('','B','20');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PROFORMA INVOICE" ,0,1,'L');

$pdf->SetXY(170,2);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
if($order_status_id !='APPROVED')
{
$pdf->Cell(0,0,"(Duplicate Copy)" ,0,1,'L');
}
$pdf->Line(12, 22, 208, 22, $style);

$pdf->SetXY(13,23);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Exporter:" ,0,1,'L');

$pdf->SetXY(13,28);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $company_name ,0,1,'L');

$pdf->SetXY(13,32);
$pdf->SetFont('','','7.5');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "(Formerly known as Dr. JRK'S Siddha Research and Pharmaceuticals Pvt Ltd.)" ,0,1,'L');


$pdf->SetXY(13,36);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(95,10, $company_address ,0,1,'');

$pdf->SetXY(13,45);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Phone: +91 44 3805 6262/ 6625 5000/ 6625 5777",0,1,'L');

$pdf->SetXY(13,49);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN: ".$cmp_gst_no ,0,1,'L');

$pdf->SetXY(13,53);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN: ".$cin_no ,0,1,'L');

//$pdf->Line(12, 59, 208, 59, $style);
$pdf->Line(109, 22, 109, 59, $style);

$pdf->SetXY(13,59);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Buyer:" ,0,1,'L');

$pdf->SetXY(13,63);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $customer_name_con ,0,1,'L');

$pdf->SetXY(13,67);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ship_to_address_1_con ,0,'L');
$oldad = $pdf->getY();

$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "TEL : ".$contact_number_con ,0,'L');

$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Email : ".$mail_con ,0,'L');

$pdf->SetXY(13,90);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Consignee:" ,0,1,'L');

$pdf->SetXY(13,94);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $customer_name_s ,0,1,'L');

$pdf->SetXY(13,98);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ship_to_address_1 ,0,'L');
$oldad = $pdf->getY();

$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "TEL : ".$contact_number ,0,'L');

$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Email : ".$mail_s ,0,'L');

$pdf->SetXY(109,23);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Invoice No. & Date",0,'L');

$pdf->SetXY(109,27);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $sales_order_no." & ".$sales_order_date,0,'L');

$pdf->SetXY(160,23);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Exporters Ref.",0,'L');

$pdf->SetXY(160,27);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "MJ 000 959 RBI",0,'L');

$pdf->Line(109, 32, 208, 32, $style);

$pdf->SetXY(109,33);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Buyers Order No. & Date",0,'L');

if($customer_po_number != ''){
$pdf->SetXY(109,37);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2,$customer_po_number."&".$sales_order_date,0,'L');    
}else{
$pdf->SetXY(109,37);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2,$sales_order_date,0,'L');
}
$pdf->Line(109, 42, 208, 42, $style);

$pdf->SetXY(109,43);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Other Reference(s)",0,'L');

$pdf->SetXY(109,46);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $remarks,0,'L');

$pdf->SetXY(109,53);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Exporter's IE Code ",0,'L');
//dd("dfds");

$pdf->SetXY(135,53);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, '0 496021150',0,'L');

$pdf->Line(109, 64, 208, 64, $style);
$pdf->Line(109, 69, 208, 69, $style);
$pdf->Line(160, 64, 160, 133, $style);

$pdf->SetXY(109,60);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "BANK DETAILS:",0,'C');

$pdf->SetXY(13,125);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Pre-Carriage",0,'L');

$pdf->SetXY(13,129);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $transport_mode,0,'L');

$pdf->SetXY(13,134);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Vessel/Flight No.",0,'L');

$pdf->SetXY(13,143);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Port of Discharge",0,'L');

$pdf->SetXY(13,147);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $port_of_discharge,0,'L');

$pdf->SetXY(13,152);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Marks & Nos.",0,'L');

$pdf->SetXY(13,156);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Container No./C.Seal No",0,'L');

$pdf->SetXY(60,125);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Place of Receipt Pre-Carriage:",0,'L');

$pdf->SetXY(60,129);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $receipt_pre_carriage,0,'L');

$pdf->SetXY(60,134);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Port of Loading",0,'L');

$pdf->SetXY(60,138);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $port_of_loading,0,'L');

$pdf->SetXY(60,143);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Final Destination",0,'L');

$pdf->SetXY(60,147);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $country_b,0,'L');

$pdf->SetXY(60,152);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "No. of Pkgs",0,'L');

$pdf->SetXY(109,125);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Country of Origin of Goods:",0,'L');

$pdf->SetXY(109,129);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $country_of_origin,0,'L');

$pdf->SetXY(160,125);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Country of Final Destination:",0,'L');

$pdf->SetXY(160,129);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $country_b,0,'L');

$pdf->SetXY(109,134);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Terms of Delivery and Payment",0,'L');

$pdf->SetXY(109,138);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $payment_term,0,'L');

$pdf->SetXY(109,142);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $deliveryterm,0,'L');

$pdf->SetXY(109,146);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $del_remarks,0,'L');

$pdf->Line(12, 59, 208, 59, $style);
$pdf->Line(12, 124, 208, 124, $style);
$pdf->Line(12, 133, 208, 133, $style);
$pdf->Line(12, 142, 109, 142, $style);
$pdf->Line(12, 151, 109, 151, $style);
$pdf->Line(60, 124, 60, 160, $style);
$pdf->Line(160, 22, 160, 32, $style); //header vertical line
/************************************Header bottom line******************************/
$pdf->Line(109, 58, 109, 160, $style);
$pdf->Line(12, 160, 208, 160, $style);

$pdf->SetXY(109,64);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Seller's - Account details",0,'L');

$pdf->SetXY(161,64);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Buyer's - Bank details",0,'L');

$pdf->SetXY(109,70);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $name_in_acc,0,'L');

$pdf->SetXY(109,75);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "A/C No. ".$acc_no,0,'L');

$pdf->SetXY(109,80);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "IFS CODE: ".$ifsc_code,0,'L');

$pdf->SetXY(109,85);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,3, $bank_name,0,'L');

$pdf->SetXY(109,90);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,3, $bank_branch,0,'L');

$pdf->SetXY(109,95);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,3, $bank_state,0,'L');

$pdf->SetXY(109,115);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,3, "SWIFT Code: IOBAINBBE52 (Large Advances- Irungattukottai)",0,'L');
/************************************Header End******************************/

/************************************Body Lines Content******************************/

/************************************Body Lines Content******************************/

$pdf->Line(21, 160, 21, 290, $style);
//$pdf->Line(75, 93, 75, 193, $style);
//$pdf->Line(95, 93, 95, 193, $style);
$pdf->Line(109, 160, 109, 290, $style);
$pdf->Line(124, 160, 124, 290, $style);
//$pdf->Line(138, 160, 138, 290, $style);
$pdf->Line(151, 160, 151, 290, $style);
 //$pdf->Line(166, 160, 166, 290, $style);
//$pdf->Line(178, 160, 178, 290, $style);
 $pdf->Line(182, 160, 182, 290, $style);

$pdf->Line(12, 170, 208, 170, $style);
//$pdf->Line(12, 193, 208, 193, $style);
//$pdf->Line(12, 183, 208, 183, $style);


$pdf->SetXY(12,161);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "S.No",0,'L');

$pdf->SetXY(23,161);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Description of Goods",0,'C');

$pdf->SetXY(26,165);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Siddha System/Cosmetic Products",0,'C');

$pdf->SetXY(109,161);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, "Batch No.",0,'C');


$pdf->SetXY(124,163);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, "No.of Unit",0,'C');

$currency = ucfirst(strtolower($invoice_currency));

$pdf->SetXY(154,162);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, "PRICE PER UNIT in ".$currency,0,'C');

$pdf->SetXY(189,161);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,2, "Amount",0,'C');

$pdf->SetXY(189,165);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,2, $currency,0,'C');
        
$pdf->SetXY(16,185);
$pdf->SetFont('','B','14');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "<---------------------------------------------------End of Page-------------------------------------->",0,'C');
        
$pdf->Rect(12, 222, 196, 68,'DF', "",  array(255, 255, 255));
//$pdf->Line(12, 278, 208, 278, $style);
$pdf->Line(12, 213, 208, 213, $style);

$pdf->SetXY(60,216);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Assessable Value",0,'L');
    
$pdf->SetXY(124,216);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2,number_format( $qtytotal,'2','.',''),0,'R');

$pdf->SetXY(189,216);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,2, number_format($sub_total,'2','.',''),0,'R');

/*$pdf->SetXY(13,195);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Amount Chargeable (in words)",0,'L');

$pdf->SetXY(13,200);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, convert_number_to_words(round($sub_total)),0,'L');*/

/*if($trade_discount_pre=0)
{
		$trade_discount_pre=1.5;
		$trddisamt = 0;
		$cashamount = 0;
}*/

$pdf->SetXY(80,227);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Trade Discount",0,'L');
$pdf->SetXY(107,223);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2,'( '.$schemes_type_value.' % )',0,'L'); 

$cashamount=number_format(($sub_total*$schemes_type_value)/100,'2','.','');
$pdf->SetXY(56,223);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(152,2, number_format( $cashamount,\Session::get('decimal'),'.',''),0,'R'); 

$pdf->SetXY(80,223);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Cash Discount",0,'L');

/*deepika purpose:trade calculation*/
$trddis=$trade_discount_pre-$schemes_type_value;
$trddisamt=$trade_discount-$cashamount;	

/*end*/
if($trade_discount_pre!='')
{
$pdf->SetXY(107,227);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(144,2, "( ".Round($trddis,2)." % )",0,'L'); 
}

$pdf->SetXY(200,227);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(152,2, number_format( $trddisamt,\Session::get('decimal'),'.',''),0,'L'); 

if($trade_discount > 0)
{

    //dd($sub_total);
    $ttt =  round($sub_total,2) - round($trade_discount,2);
}else
{$ttt =  round($sub_total,2) - round($cashamount,2);
}
//dd($gsttotal);
$ta=$ttt+$gsttotal ;
//dd(round($ta,2));
$round_off = $ta-(round($ta));
//dd($round_off);
$ka=$gsttotal/2;
$words = '';

$pdf->SetXY(80,231);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(150,2, "Freight and Forwarding Cost ",0,'L');

    $pdf->SetXY(50,231);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    //$pdf->Cell(0,0,$ka ,0,1,'L');
    $pdf->MultiCell(158,2,"",0,'R'); 
    
    $pdf->SetXY(80,235);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
  //  $pdf->MultiCell(150,2, "TCS ",0,'L');

    $pdf->SetXY(80,235);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    //$pdf->Cell(0,0,$ka ,0,1,'L');
    $pdf->MultiCell(158,2,"",0,'R');

$pdf->SetXY(80,238); 
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Round Off",0,'L');

//dd($round_off);

$pdf->SetXY(199,238); 
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(150,2,(round($ttt)),'2','.',''),0,'L'); 
$pdf->Cell(0,0,round($round_off,2) ,0,1,'L');

$pdf->SetXY(80,242);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(144,2, "Grand Total",0,'L');

$pdf->SetXY(193,242);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(150,2,(round($ttt)),'2','.',''),0,'L'); 
$pdf->Cell(0,0,(round($ta)).'.00' ,0,1,'L');

if($order_type_id == 'EXPORT' || $order_type_id == 'EXPORT SAMPLE'){
     $curr = ucfirst(strtolower($invoice_currency)).'s Only';
    }else{
    $curr = 'Rupees Only';
    }

$pdf->SetXY(13,227);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "ALL PRICES IN ".strtoupper($curr),0,'L');

$pdf->SetXY(13,231);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Mode of Transport: ".$transport_mode,0,'L');

$pdf->SetXY(13,235);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, '"CIF Incoterms 2010 - '.$port_of_discharge.' '.$country_b.'"',0,'L');

$pdf->SetXY(13,248);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Amount :",0,'L');

 

$pdf->SetXY(28,248);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, convert_number_to_words(round($ta)).$curr,0,'L');


/* $pdf->Line(180, 197, 208, 197, $style);
    $pdf->Line(180, 205, 208, 205, $style);
    $pdf->Line(180, 209, 208, 209, $style);

    $pdf->Line(12, 220, 208, 220, $style);
    $pdf->Line(12, 230, 208, 230, $style);
    $pdf->Line(75, 220, 75, 255, $style);
    $pdf->Line(102, 220, 102, 255, $style);
    
    $pdf->Line(184, 220, 184, 255, $style);*/

$pdf->Line(12, 247, 208, 247, $style);

//Gst calculation




$hsn_y=0;
$hsny=193;
$gst_total=0;
$net_total=0;
 $gross_total=0;




$sgat=0;
$cgst=0;
$gsttot=0;
$gsttot=208;
foreach($gst as $gstvalue)
{
    if($hsn_y==0)
    {
        $hsny=214;        
    }
    else
    {
        $hsny=$hsny+4;
    }


/*$pdf->SetXY(13,$hsny+16);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(105,10, $gstvalue['hsn'] ,0,'L');*/
$hsn_y=$pdf->getY();
      
    $gsttot=$gsttot+12;

        
    /*$pdf->SetXY(77,$hsny+16);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(19,10, number_format($gstvalue['amount'],'2','.','') ,0,'R');*/
    
    $oldslab=$pdf->getY();
        
    /*$pdf->SetXY(180,$hsny+16);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(23,10, number_format($gstvalue['gst_val'],'2','.','') ,0,'R');*/

    
$pdf->SetXY(13,255);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Gross Weight :",0,'L');

$pdf->SetXY(55,255);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "(Approximated)",0,'L');

$pdf->SetXY(56,255);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "",0,'L');

$pdf->SetXY(13,260);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Net Weight :",0,'L');

$pdf->SetXY(55,260);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "(Approximated)",0,'L');

$pdf->SetXY(56,260);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "",0,'L');

$pdf->SetXY(13,268);
$pdf->SetFont('','U','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Declaration",0,'L');

$pdf->SetXY(13,272);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.",0,'L');

$pdf->SetXY(13,283);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Date:",0,'L');

$pdf->SetXY(22,283);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2,$sales_order_date ,0,'L');
    
}
$pdf->SetXY(110,270);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "for ".$company_name,0,'C');

$pdf->SetXY(120,283);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Authorised Signatory",0,'R');

$pdf->Line(110, 268, 110, 290, $style);
$pdf->Line(110, 268, 208, 268, $style);
        
    }
}

if(count($terms_condition)>0){
 $pdf->addPage();
 $pdf->SetHeaderData('', '', 'PROFORMA INVOICE', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
 
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
$pdf->SetXY(75,10);
$pdf->SetFont('','B','20');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PROFORMA INVOICE" ,0,1,'L');
$pdf->Line(12, 22, 208, 22, $style);
    
$pdf->SetXY(15,25);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Terms and Condition :" ,0,1,'L');
$oldY1=0;
foreach($terms_condition as $k11=>$v11){
  //for($j=0;$j<38;$j++){
if($oldY1==0) $y =30;
else $y = $oldY1+2;
    
$pdf->SetXY(20,$y+1);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $v11->element_content ,0,1,'L');
$pdf->Cell(0,0, $j ,0,1,'L');
$oldY1=$pdf->getY();
    
    if($oldY1 > 280)
    {
        $oldY1 = 0;
        $pdf->addPage();
        $pdf->SetHeaderData('', '', 'PROFORMA INVOICE', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
 
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
$pdf->SetXY(75,10);
$pdf->SetFont('','B','20');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PROFORMA INVOICE" ,0,1,'L');
        $pdf->Line(12, 22, 208, 22, $style);
    }
    
}

}

//Close and output PDF document
ob_end_clean();
       
if($print=='PRINT')
{                    
 $pdf->Output($sales_order_no.'.pdf','FI');
 //$pdf->Output('uploads/soinvoiceupload/SOINV_'.$sales_order_no.'.pdf', 'FI');
 $pdf->close();

exit();
}
else
{
    
  $filename="uploads/soinvoiceupload/SOINV_".$sales_order_no.".pdf";
  // dd($filename);
  $pdf->Output('uploads/soinvoiceupload/SOINV_'.$sales_order_no.'.pdf', 'F');
}

//============================================================+
// END OF FILE
//============================================================+

/******************************************************************************************/ 
?>
