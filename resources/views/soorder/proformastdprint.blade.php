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

$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "PH : ".$contact_number ,0,'L');
if($order_type_id!="SAMPLE"){
$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "GSTIN/UIN : ".$ship_gst_no ,0,'L');
}
$pdf->SetXY(13,$oldad+8);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "State Name : ".$state_name_ship ,0,'L');

$pdf->SetXY(109,22);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Proforma Invoice No ",0,'L');

$pdf->SetXY(109,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $sales_order_no,0,'L');

$pdf->SetXY(160,22);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Dated ",0,'L');

$pdf->SetXY(160,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $sales_order_date,0,'L');

$pdf->Line(109, 29, 208, 29, $style);

$pdf->SetXY(109,29);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Delivery Note ",0,'L');

$pdf->SetXY(109,33);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2,'',0,'L');

$pdf->SetXY(160,29);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Delivery Note Date ",0,'L');

$pdf->SetXY(160,33);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2,'',0,'L');

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
$pdf->MultiCell(100,2, "LR No ",0,'L');

$pdf->SetXY(121,51);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, '',0,'L');

$pdf->SetXY(109,55);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "LR Date ",0,'L');
//dd("dfds");

$pdf->SetXY(121,55);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, '',0,'L');


$pdf->SetXY(160,51);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Mode/Terms of Payment",0,'L');

$pdf->SetXY(160,54);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $payment_term,0,'L');

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
$pdf->MultiCell(100,2, '',0,'L');

$pdf->SetXY(109,63);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(100,2, "Door Delivery",0,'L');
/************************************Header End******************************/

/************************************Body Lines Content******************************/

$pdf->Line(21, 93, 21, 290, $style);
//$pdf->Line(75, 93, 75, 193, $style);
//$pdf->Line(95, 93, 95, 193, $style);
$pdf->Line(109, 93, 109, 290, $style);
$pdf->Line(124, 93, 124, 290, $style);
$pdf->Line(138, 93, 138, 290, $style);
$pdf->Line(151, 93, 151, 290, $style);
 $pdf->Line(166, 93, 166, 290, $style);
$pdf->Line(178, 93, 178, 290, $style);
 $pdf->Line(189, 93, 189, 290, $style);

$pdf->Line(12, 100, 208, 100, $style);
//$pdf->Line(12, 193, 208, 193, $style);
//$pdf->Line(12, 183, 208, 183, $style);


$pdf->SetXY(12,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "S.No",0,'L');

$pdf->SetXY(23,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Description of Goods",0,'C');


$pdf->SetXY(109,94);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, "MRP",0,'C');


$pdf->SetXY(124,94);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Quantity",0,'C');

$pdf->SetXY(138,93);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Free",0,'C');

$pdf->SetXY(138,96);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(13,2, "Quantity",0,'C');

$pdf->SetXY(151,94);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, "Rate",0,'C');

$pdf->SetXY(166,94);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(12,2, "Per",0,'C');

$pdf->SetXY(179,93);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(10,2, "Disc.",0,'C');

$pdf->SetXY(179,96);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(10,2, " %",0,'C');

$pdf->SetXY(189,94);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,2, "Amount",0,'C');

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
  
if($oldY==0) $y =100;
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
if($value['mrp_price'] != '')
$pdf->MultiCell(15,2, number_format($value['mrp_price'],'2','.','') ,0,'R');

$pdf->SetXY(124,$y-7);
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

$pdf->SetXY(138,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(13,2, $value['free_qty'] ,0,'C');
    

$pdf->SetXY(151,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, $value['unit_price'] ,0,'R');
    
$pdf->SetXY(166,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(12,2, $value['uom_code'] ,0,'C');
    
$pdf->SetXY(178,$y-7);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(11,2, $value['discount_per'] ,0,'C');
    
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

$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "PH : ".$contact_number ,0,'L');
if($order_type_id!="SAMPLE"){
$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "GSTIN/UIN : ".$ship_gst_no ,0,'L');
}
$pdf->SetXY(13,$oldad+8);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "State Name : ".$state_name_ship ,0,'L');

$pdf->SetXY(109,22);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Proforma Invoice No ",0,'L');

$pdf->SetXY(109,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $sales_order_no,0,'L');

$pdf->SetXY(160,22);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Dated ",0,'L');

$pdf->SetXY(160,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $sales_order_date,0,'L');

$pdf->Line(109, 29, 208, 29, $style);

$pdf->SetXY(109,29);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Delivery Note ",0,'L');

$pdf->SetXY(109,33);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, '',0,'L');

$pdf->SetXY(160,29);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Delivery Note Date ",0,'L');

$pdf->SetXY(160,33);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, '',0,'L');

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
$pdf->MultiCell(100,2, 'Dated',0,'L');

$pdf->SetXY(160,47);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $sales_order_date,0,'L');

$pdf->Line(109, 51, 208, 51, $style);

$pdf->SetXY(109,51);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "LR No ",0,'L');

$pdf->SetXY(121,51);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, '',0,'L');

$pdf->SetXY(109,55);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "LR Date ",0,'L');


$pdf->SetXY(121,55);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, '',0,'L');


$pdf->SetXY(160,51);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Mode/Terms of Payment",0,'L');

$pdf->SetXY(160,54);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $payment_term,0,'L');

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
$pdf->MultiCell(100,2, '',0,'L');

$pdf->SetXY(109,63);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(100,2, "Door Delivery",0,'L');
/************************************Header End******************************/

/************************************Body Lines Content******************************/

$pdf->Line(21, 93, 21, 290, $style);
//$pdf->Line(75, 93, 75, 193, $style);
//$pdf->Line(95, 93, 95, 193, $style);
$pdf->Line(109, 93, 109, 290, $style);
$pdf->Line(124, 93, 124, 290, $style);
$pdf->Line(138, 93, 138, 290, $style);
$pdf->Line(151, 93, 151, 290, $style);
 $pdf->Line(166, 93, 166, 290, $style);
$pdf->Line(178, 93, 178, 290, $style);
 $pdf->Line(189, 93, 189, 290, $style);

$pdf->Line(12, 100, 208, 100, $style);
//$pdf->Line(12, 193, 208, 193, $style);
//$pdf->Line(12, 183, 208, 183, $style);


$pdf->SetXY(12,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "S.No",0,'L');

$pdf->SetXY(23,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Description of Goods",0,'C');


$pdf->SetXY(109,94);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, "MRP",0,'C');


$pdf->SetXY(124,94);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Quantity",0,'C');

$pdf->SetXY(138,93);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Free",0,'C');

$pdf->SetXY(138,96);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(13,2, "Quantity",0,'C');

$pdf->SetXY(151,94);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, "Rate",0,'C');

$pdf->SetXY(166,94);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(12,2, "Per",0,'C');

$pdf->SetXY(179,93);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(10,2, "Disc.",0,'C');

$pdf->SetXY(179,96);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(10,2, " %",0,'C');

$pdf->SetXY(189,94);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
    }
    
}

/************************************Body Lines Content End******************************/

/************************************ Footer ******************************/



//Gst Calculation End
$total = $pdf->getNumPages();
    if($pdf->pageNo()==$total)
    {   
    if($oldY < 175)
    {
    $pdf->Rect(12, 186, 196, 104,'DF', "",  array(255, 255, 255));
$pdf->Line(12, 186, 208, 186, $style);
$pdf->Line(12, 176, 208, 176, $style);
$pdf->SetXY(60,180);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Total",0,'L');

    
$pdf->SetXY(124,180);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2,number_format( $qtytotal,'2','.',''),0,'R');

$pdf->SetXY(189,180);
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

$pdf->SetXY(70,193);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Trade Discount",0,'L');

$cashamount=($sub_total*$schemes_type_value)/100;
$pdf->SetXY(97,188);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(152,2,'( '.$schemes_type_value.' % ) ',0,'L'); 

$pdf->SetXY(56,188);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(152,2, number_format( $cashamount,\Session::get('decimal'),'.',''),0,'R'); 

$pdf->SetXY(70,188);
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
$pdf->SetXY(97,193);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "( ".Round($trddis,2)." % )",0,'L'); 
}



$pdf->SetXY(51,193);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(152,2, number_format( $trddisamt,\Session::get('decimal'),'.',''),0,'R'); 

if($trade_discount > 0)
{$ttt =  $sub_total - $trade_discount;
}else
{$ttt =  $sub_total - $cashamount;
}

$ta=$ttt+$gsttotal+$tcs_amount ;
//dd($ta);
$round_off = $ta-(round($ta));
//dd($gsttotal);
$ka=$gsttotal/2;
$words = '';
if(count($gst) > 0 ){
$words = preg_replace('/[^a-zA-Z]/', '', $gst[0]['tax_group_name']);
if($words == "GST"){

    $pdf->SetXY(70,198);
    $pdf->SetFont('','B','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(150,2, "SGST",0,'L');

    $pdf->SetXY(50,198);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    //$pdf->Cell(0,0,$ka ,0,1,'L');
    $pdf->MultiCell(158,2, number_format( $ka,'2','.',''),0,'R'); 


    $pdf->SetXY(70,201);
    $pdf->SetFont('','B','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(150,2, "CGST",0,'L');

    $pdf->SetXY(50,201);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(158,2, number_format( $ka,'2','.',''),0,'R'); 
}else{
    $pdf->SetXY(70,198);
    $pdf->SetFont('','B','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(150,2, "IGST",0,'L');

    $pdf->SetXY(50,198);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    //$pdf->Cell(0,0,$ka ,0,1,'L');
    $pdf->MultiCell(158,2, number_format( $gsttotal,'2','.',''),0,'R'); 
}
}

$pdf->SetXY(70,205);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Round Off",0,'L');

$pdf->SetXY(48,205);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(160,2, round($round_off,2),0,'R'); 
//$pdf->MultiCell(150,2,(round($ttt)),'2','.',''),0,'L'); 
//$pdf->Cell(0,0,round($round_off,2) ,0,1,'R');

if($tcs_applicable == "YES"){
$pdf->SetXY(70,209);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "TCS Amount (0.1%)",0,'L');

//dd($round_off);
$pdf->SetXY(195,209);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(150,2,(round($ttt)),'2','.',''),0,'L'); 
$pdf->Cell(0,0,round($tcs_amount,2) ,0,1,'L');
}
if($tcs_applicable == "YES"){
$pdf->SetXY(70,207);
}else{
$pdf->SetXY(70,205);    
}



$pdf->SetXY(70,211);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Total",0,'L');

$pdf->SetXY(50,212);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(150,2,(round($ttt)),'2','.',''),0,'L');
 $pdf->MultiCell(158,2, (round($ta)).'.00',0,'R'); 
//$pdf->Cell(0,0,(round($ta)).'.00' ,0,1,'L');

$pdf->SetXY(13,209);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Invoice Amount (in words) :",0,'L');

 if($order_type_id == 'EXPORT' || $order_type_id == 'EXPORT SAMPLE'){
     $curr = ucfirst(strtolower($invoice_currency)).'s Only';
    }else{
    $curr = 'Rupees Only';
    }

$pdf->SetXY(13,214);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, convert_number_to_words(round($ta)).$curr,0,'L');

$pdf->SetXY(13,214);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, convert_number_to_words(round($ta)),0,'L');

   $pdf->Line(180, 197, 208, 197, $style);
    $pdf->Line(180, 205, 208, 205, $style);
    $pdf->Line(180, 209, 208, 209, $style);
    $pdf->Line(12, 220, 208, 220, $style);
    $pdf->Line(12, 230, 208, 230, $style);
    $pdf->Line(75, 220, 75, 255, $style);
    $pdf->Line(102, 220, 102, 255, $style);
    
    $pdf->Line(184, 220, 184, 255, $style);
    /*AMOUNT LINE*/


if($words == "GST"){
    
    $pdf->Line(102, 225, 184, 225, $style);
    $pdf->Line(122, 225, 122, 255, $style);
    $pdf->Line(162, 225, 162, 255, $style);
    $pdf->Line(144, 220, 144, 255, $style);

    $pdf->SetXY(113,220);   
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(50,2, "Central Tax",0,'L');
    
    $pdf->SetXY(105,225);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(50,2, "Rate",0,'L');

    $pdf->SetXY(125,225);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(50,2, "Amount",0,'L');

    $pdf->SetXY(153,220);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(50,2, "State Tax",0,'L');

    $pdf->SetXY(145,225);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(50,2, "Rate",0,'L');

    $pdf->SetXY(165,225);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(50,2, "Amount",0,'L');

}else{
    $pdf->SetXY(140,220);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(50,2, "IGST",0,'L');

    $pdf->SetXY(125,225);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(50,2, "Rate",0,'L');

    $pdf->SetXY(155,225);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(50,2, "Amount",0,'L');
}


$pdf->SetXY(13,223);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "HSN/SAC",0,'L');

$pdf->SetXY(83,221);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, "Taxable Value",0,'L');

$pdf->SetXY(193,223);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2, "Total",0,'L');

$pdf->SetXY(70,256);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2, "Tax Total",0,'L');

$totaaaa = $gsttotal ;
$val=($gsttotal/2);

$pdf->SetXY(193,256);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2, round($totaaaa).'.00',0,'L');

$pdf->Line(12, 255, 208, 255, $style);
$pdf->Line(12, 261, 208, 261, $style);

$pdf->SetXY(13,262);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Tax Amount (in words) :",0,'L');

$pdf->SetXY(50,262);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, convert_number_to_words(round($totaaaa)).$curr,0,'L');


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
  

$pdf->SetXY(13,$hsny+16);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(105,10, $gstvalue['hsn'] ,0,'L');
$hsn_y=$pdf->getY();
 
if($words == "GST"){
    $pdf->SetXY(102,$hsny+16);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(19,10, $gstvalue['sgcgst']." %" ,0,'R');

    $pdf->SetXY(123,$hsny+16);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(19,10,number_format( $gstvalue['sgst_val'],'2','.','') ,0,'R');

    $pdf->SetXY(142,$hsny+16);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(19,10, $gstvalue['sgcgst']." %" ,0,'R');

    $pdf->SetXY(164,$hsny+16);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(19,10, number_format($gstvalue['sgst_val'],'2','.','') ,0,'R');

}else{
    // dd($gstvalue);
    $pdf->SetXY(115,$hsny+16);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(19,10, $gstvalue['gst']." %" ,0,'R');

    $pdf->SetXY(150,$hsny+16);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(19,10, number_format($gstvalue['gst_val'],'2','.','') ,0,'R');
}
    $pdf->SetXY(77,$hsny+16);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(19,10, number_format($gstvalue['amount'],'2','.','') ,0,'R');
    
    $oldslab=$pdf->getY();
        
    $pdf->SetXY(180,$hsny+16);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(23,10, number_format($gstvalue['gst_val'],'2','.','') ,0,'R');
    
    
    $pdf->SetXY(13,267);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Company’s Service Tax No.",0,'L');

$pdf->SetXY(56,267);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, ": "."AAACD1708PSD001",0,'L');

$pdf->SetXY(13,271);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Company’s PAN",0,'L');

$pdf->SetXY(56,271);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, ": "."AAACD1708P",0,'L');

$pdf->SetXY(13,275);
$pdf->SetFont('','U','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Declaration",0,'L');

$pdf->SetXY(13,279);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.",0,'L');


    
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

$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "PH : ".$contact_number ,0,'L');
if($order_type_id!="SAMPLE"){
$pdf->SetXY(13,$oldad+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "GSTIN/UIN : ".$ship_gst_no ,0,'L');
}
$pdf->SetXY(13,$oldad+8);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "State Name : ".$state_name_ship ,0,'L');

$pdf->SetXY(109,22);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Proforma Invoice No ",0,'L');

$pdf->SetXY(109,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $sales_order_no,0,'L');

$pdf->SetXY(160,22);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Dated ",0,'L');

$pdf->SetXY(160,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $sales_order_date,0,'L');

$pdf->Line(109, 29, 208, 29, $style);

$pdf->SetXY(109,29);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Delivery Note ",0,'L');

$pdf->SetXY(109,33);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, '',0,'L');

$pdf->SetXY(160,29);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Delivery Note Date ",0,'L');

$pdf->SetXY(160,33);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, '',0,'L');

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
$pdf->MultiCell(100,2, "LR No ",0,'L');

$pdf->SetXY(121,51);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, '',0,'L');

$pdf->SetXY(109,55);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "LR Date ",0,'L');


$pdf->SetXY(121,55);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, '',0,'L');

$pdf->SetXY(160,51);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Mode/Terms of Payment",0,'L');

$pdf->SetXY(160,54);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $payment_term,0,'L');

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
$pdf->MultiCell(100,2, '',0,'L');

$pdf->SetXY(109,63);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(100,2, "Door Delivery",0,'L');
/************************************Header End******************************/

/************************************Body Lines Content******************************/

$pdf->Line(21, 93, 21, 290, $style);
//$pdf->Line(75, 93, 75, 193, $style);
//$pdf->Line(95, 93, 95, 193, $style);
$pdf->Line(109, 93, 109, 290, $style);
$pdf->Line(124, 93, 124, 290, $style);
$pdf->Line(138, 93, 138, 290, $style);
$pdf->Line(151, 93, 151, 290, $style);
 $pdf->Line(166, 93, 166, 290, $style);
$pdf->Line(178, 93, 178, 290, $style);
 $pdf->Line(189, 93, 189, 290, $style);

$pdf->Line(12, 100, 208, 100, $style);
//$pdf->Line(12, 193, 208, 193, $style);
//$pdf->Line(12, 183, 208, 183, $style);


$pdf->SetXY(12,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "S.No",0,'L');

$pdf->SetXY(23,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Description of Goods",0,'C');


$pdf->SetXY(109,94);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, "MRP",0,'C');


$pdf->SetXY(124,94);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Quantity",0,'C');

$pdf->SetXY(138,93);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Free",0,'C');

$pdf->SetXY(138,96);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(13,2, "Quantity",0,'C');

$pdf->SetXY(151,94);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, "Rate",0,'C');

$pdf->SetXY(166,94);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(12,2, "Per",0,'C');

$pdf->SetXY(179,93);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(10,2, "Disc.",0,'C');

$pdf->SetXY(179,96);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(10,2, " %",0,'C');

$pdf->SetXY(189,94);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
        
        $pdf->SetXY(16,144);
$pdf->SetFont('','B','14');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "<---------------------------------------------------End of Page-------------------------------------->",0,'C');
        
$pdf->Rect(12, 186, 196, 104,'DF', "",  array(255, 255, 255));
$pdf->Line(12, 186, 208, 186, $style);
$pdf->Line(12, 178, 208, 178, $style);

$pdf->SetXY(60,180);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Total",0,'L');
    
$pdf->SetXY(124,180);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2,number_format( $qtytotal,'2','.',''),0,'R');

$pdf->SetXY(189,180);
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

$pdf->SetXY(70,193);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Trade Discount",0,'L');
$pdf->SetXY(95,188);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2,'( '.$schemes_type_value.' % )',0,'L'); 

$cashamount=number_format(($sub_total*$schemes_type_value)/100,'2','.','');
$pdf->SetXY(56,188);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(152,2, number_format( $cashamount,\Session::get('decimal'),'.',''),0,'R'); 

$pdf->SetXY(70,188);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Cash Discount",0,'L');

/*deepika purpose:trade calculation*/
$trddis=$trade_discount_pre-$schemes_type_value;
$trddisamt=$trade_discount-$cashamount;	

/*end*/
if($trade_discount_pre!='')
{
$pdf->SetXY(95,193);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(144,2, "( ".Round($trddis,2)." % )",0,'L'); 
}

$pdf->SetXY(195,193);
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
$ta=$ttt+$gsttotal+$tcs_amount ;
//dd(round($ta,2));
$round_off = $ta-(round($ta));
//dd($round_off);
$ka=$gsttotal/2;
$words = '';
if(count($gst) > 0 ){
$words = preg_replace('/[^a-zA-Z]/', '', $gst[0]['tax_group_name']);
if($words == "GST"){
    $pdf->SetXY(70,198);
    $pdf->SetFont('','B','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(150,2, "SGST",0,'L');

    $pdf->SetXY(193,198);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    //$pdf->Cell(0,0,$ka ,0,1,'L');
    $pdf->MultiCell(152,2, number_format( $ka,'2','.',''),0,'L'); 


    $pdf->SetXY(70,201);
    $pdf->SetFont('','B','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(150,2, "CGST",0,'L');

    $pdf->SetXY(193,201);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(152,2, number_format( $ka,'2','.',''),0,'L'); 
}else{
    $pdf->SetXY(70,198);
    $pdf->SetFont('','B','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(150,2, "IGST",0,'L');

    $pdf->SetXY(193,198);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
   // $pdf->Cell(0,0,$ka ,0,1,'L');
    $pdf->MultiCell(152,2, number_format( $gsttotal,'2','.',''),0,'L'); 
}
}

$pdf->SetXY(70,205);  
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Round Off",0,'L');

//dd($round_off);

$pdf->SetXY(200,205); 
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(150,2,(round($ttt)),'2','.',''),0,'L'); 
$pdf->Cell(0,0,round($round_off,2) ,0,1,'L');


if($tcs_applicable == "YES"){
$pdf->SetXY(70,208);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "TCS Amount (0.1%)",0,'L');

//dd($round_off);
$pdf->SetXY(195,209);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(150,2,(round($ttt)),'2','.',''),0,'L'); 
$pdf->Cell(0,0,round($tcs_amount,2) ,0,1,'L');
}
if($tcs_applicable == "YES"){
$pdf->SetXY(70,207);
}else{
$pdf->SetXY(70,205);    
}


$pdf->SetXY(70,211);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(144,2, "Total",0,'L');

$pdf->SetXY(191,212);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(150,2,(round($ttt)),'2','.',''),0,'L'); 
$pdf->Cell(0,0,(round($ta)).'.00' ,0,1,'L');

$pdf->SetXY(13,209);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Invoice Amount (in words) :",0,'L');

if($order_type_id == 'EXPORT' || $order_type_id == 'EXPORT SAMPLE'){
     $curr = ucfirst(strtolower($invoice_currency)).'s Only';
    }else{
    $curr = 'Rupees Only';
    }

$pdf->SetXY(13,214);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, convert_number_to_words(round($ta)).$curr,0,'L');

/*$pdf->SetXY(13,214);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, convert_number_to_words(round($ta)),0,'L');*/


 $pdf->Line(180, 197, 208, 197, $style);
    $pdf->Line(180, 205, 208, 205, $style);
    $pdf->Line(180, 209, 208, 209, $style);

    $pdf->Line(12, 220, 208, 220, $style);
    $pdf->Line(12, 230, 208, 230, $style);
    $pdf->Line(75, 220, 75, 255, $style);
    $pdf->Line(102, 220, 102, 255, $style);
    
    $pdf->Line(184, 220, 184, 255, $style);


    if($words == "GST"){
        $pdf->Line(102, 225, 184, 225, $style);
        $pdf->Line(122, 225, 122, 255, $style);
        $pdf->Line(162, 225, 162, 255, $style);
        $pdf->Line(144, 220, 144, 255, $style);

        $pdf->SetXY(113,220);   
        $pdf->SetFont('','B','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(50,2, "Central Tax",0,'L');
        
        $pdf->SetXY(105,225);
        $pdf->SetFont('','','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(50,2, "Rate",0,'L');

        $pdf->SetXY(125,225);
        $pdf->SetFont('','','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(50,2, "Amount",0,'L');

        $pdf->SetXY(153,220);
        $pdf->SetFont('','B','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(50,2, "State Tax",0,'L');

        $pdf->SetXY(145,225);
        $pdf->SetFont('','','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(50,2, "Rate",0,'L');

        $pdf->SetXY(165,225);
        $pdf->SetFont('','','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(50,2, "Amount",0,'L');
    }else{
        $pdf->SetXY(140,220);
        $pdf->SetFont('','B','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(50,2, "IGST",0,'L');

        $pdf->SetXY(125,225);
        $pdf->SetFont('','','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(50,2, "Rate",0,'L');

        $pdf->SetXY(155,225);
        $pdf->SetFont('','','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(50,2, "Amount",0,'L');
    }

    $pdf->SetXY(13,223);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "HSN/SAC",0,'L');

$pdf->SetXY(83,221);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, "Taxable Value",0,'L');

$pdf->SetXY(193,223);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2, "Total",0,'L');

$pdf->SetXY(70,256);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2, "Tax Total",0,'L');

$totaaaa = $gsttotal ;
$val=($gsttotal/2);

$pdf->SetXY(188,256);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(45,2, round($totaaaa).'.00',0,'L');

$pdf->Line(12, 255, 208, 255, $style);
$pdf->Line(12, 261, 208, 261, $style);

$pdf->SetXY(13,262);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Tax Amount (in words) :",0,'L');

$pdf->SetXY(50,262);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, convert_number_to_words(round($totaaaa)).$curr,0,'L');



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


$pdf->SetXY(13,$hsny+16);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(105,10, $gstvalue['hsn'] ,0,'L');
$hsn_y=$pdf->getY();
      
    $gsttot=$gsttot+12;

    if($words == "GST"){
        $pdf->SetXY(102,$hsny+16);
        $pdf->SetFont('','','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(19,10, $gstvalue['sgcgst']." %" ,0,'R');

        $pdf->SetXY(123,$hsny+16);
        $pdf->SetFont('','','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(19,10,number_format( $gstvalue['sgst_val'],'2','.','') ,0,'R');

        $pdf->SetXY(142,$hsny+16);
        $pdf->SetFont('','','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(19,10, $gstvalue['sgcgst']." %" ,0,'R');

        $pdf->SetXY(164,$hsny+16);
        $pdf->SetFont('','','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(19,10, number_format($gstvalue['sgst_val'],'2','.','') ,0,'R');
    }else{
        $pdf->SetXY(115,$hsny+16);
        $pdf->SetFont('','','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(19,10, $gstvalue['gst']." %" ,0,'R');

        $pdf->SetXY(150,$hsny+16);
        $pdf->SetFont('','','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(19,10, number_format($gstvalue['gst_val'],'2','.','') ,0,'R');
    }
        
    $pdf->SetXY(77,$hsny+16);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(19,10, number_format($gstvalue['amount'],'2','.','') ,0,'R');
    
    $oldslab=$pdf->getY();
        
    $pdf->SetXY(180,$hsny+16);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(23,10, number_format($gstvalue['gst_val'],'2','.','') ,0,'R');

    
    $pdf->SetXY(13,267);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Company’s Service Tax No.",0,'L');

$pdf->SetXY(56,267);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, ": "."AAACD1708PSD001",0,'L');

$pdf->SetXY(13,271);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Company’s PAN",0,'L');

$pdf->SetXY(56,271);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, ": "."AAACD1708P",0,'L');

$pdf->SetXY(13,275);
$pdf->SetFont('','U','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Declaration",0,'L');

$pdf->SetXY(13,279);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.",0,'L');
    
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
 $pdf->Output('example_007.pdf','FI');
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
