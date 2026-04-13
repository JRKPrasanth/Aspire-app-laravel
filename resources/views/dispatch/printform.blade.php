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
	//dd($decimal);
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
        //dd($number)
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            //dd($str [10]);
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    //return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise ;
    //dd($Rupees);
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
       $this->Image($image_file, 187,12,21,20, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
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
			//dd($total);
          $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');     
        }    
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setLogo($company_logo);
  

// add a page
$pdf->AddPage('P', $resolution);

$pdf->SetHeaderData('', '', 'DELIVERY NOTE', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
 
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$pdf->Line(12, 12, 12, 290, $style);
$pdf->Line(12, 12, 208,12, $style);
$pdf->Line(208, 12, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
$pdf->SetXY(90,7);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "DELIVERY NOTE" ,0,1,'L');



$pdf->SetXY(60,12);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $company_name ,0,1,'L');

$pdf->SetXY(61,17);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(125,0,$company_address  ,0,'L');

$pdf->SetXY(13,25);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN: ".$cin_no ,0,1,'L');

$pdf->SetXY(115,25);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Website : ".$website_address ,0,1,'L');

$pdf->SetXY(13,30);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Company’s GSTIN/UIN" ,0,1,'L');

$pdf->SetXY(50,30);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, " : ".$gst_no ,0,1,'L');


$pdf->SetXY(13,35);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ph No : +91-44-".$company_contact_no ,0,1,'L');

$pdf->SetXY(115,32);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PAN / Income Tax No." ,0,1,'L');

$pdf->SetXY(153,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, " : ".$pan_no ,0,1,'L');


$pdf->Line(12, 40, 208, 40, $style);

$pdf->SetXY(13,40);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Consignee" ,0,1,'L');

$pdf->SetXY(13,44);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
if($customer_name!=""){
$pdf->MultiCell(100,2, $customer_name ,0,'L');
}else{
$pdf->MultiCell(100,2, $employee_name ,0,'L');	
}
$pdf->SetXY(13,48);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ship_to_address_1 ,0,'L');
$y1=$pdf->getY();

$pdf->SetXY(13,$y1);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Phone No: ".$contact_number ,0,1,'L');
$y1=$pdf->getY();

if($alternative_contact_number!=''){
$pdf->SetXY(13,$y1);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Alternative Phone No: ".$alternative_contact_number ,0,1,'L');
}

if($order_type_id!="SAMPLE"){
$pdf->SetXY(13,70);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN" ,0,1,'L');

$pdf->SetXY(33,70);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ": ".$ship_gst_no ,0,1,'L');
}else{
$pdf->SetXY(13,70);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PAN NUMBER" ,0,1,'L');

$pdf->SetXY(33,70);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ": ".$pan_number ,0,1,'L');
}
$pdf->Line(12, 75, 115, 75, $style);

$pdf->SetXY(13,76);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ship to" ,0,1,'L');

$pdf->SetXY(13,80);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
if($customer_name!=""){
$pdf->MultiCell(100,2, $customer_name ,0,'L');
}else{
$pdf->MultiCell(100,2, $employee_name ,0,'L');	
}

$pdf->SetXY(13,84);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ship_to_address_1 ,0,'L');
$y2=$pdf->getY();

$pdf->SetXY(13,$y2);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Phone : ".$contact_number ,0,1,'L');

$y2=$pdf->getY();
if($alternative_contact_number!=''){
$pdf->SetXY(13,$y2);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Alternative Phone No: ".$alternative_contact_number ,0,1,'L');
}
if($order_type_id!="SAMPLE"){
$pdf->SetXY(13,108);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Buyer’s GSTIN" ,0,1,'L');

$pdf->SetXY(40,108);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ": ".$ship_gst_no ,0,1,'L');
}
$pdf->Line(115, 40, 115, 114, $style);


$pdf->Line(163, 40, 163, 79, $style);
$pdf->Line(115, 49, 208, 49, $style);
$pdf->Line(115, 59, 208, 59, $style);
$pdf->Line(115, 69, 208, 69, $style);
$pdf->Line(115, 79, 208, 79, $style);

$pdf->SetXY(117,40);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Delivery Note No." ,0,1,'L');

$pdf->SetXY(166,40);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Dated" ,0,1,'L');

$pdf->SetXY(117,44);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $dispatch_number ,0,1,'L');

$pdf->SetXY(166,44);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $dispatch_date ,0,1,'L');


$pdf->SetXY(117,50);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Buyer’s Order No." ,0,1,'L');

$pdf->SetXY(166,50);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Dated" ,0,1,'L');

$pdf->SetXY(117,54);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $source_number ,0,1,'L');

$pdf->SetXY(166,54);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $source_date ,0,1,'L');


$pdf->SetXY(117,60);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Supplier’s Ref./Order No." ,0,1,'L');

$pdf->SetXY(166,60);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Other Reference(s)" ,0,1,'L');

$pdf->SetXY(117,64);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $customer_po_number ,0,1,'L');

$pdf->SetXY(166,64);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $remarks ,0,1,'L');

$pdf->SetXY(117,70);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Despatched through" ,0,1,'L');

$pdf->SetXY(117,74);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $despatch_through ,0,1,'L');


$pdf->SetXY(166,70);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Place Of Supply" ,0,1,'L');


$pdf->SetXY(166,74);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $state_name_ship ,0,1,'L');

$pdf->SetXY(117,80);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Terms of Delivery" ,0,1,'L');

$pdf->Line(115, 92, 208, 92, $style);

$pdf->SetXY(117,94);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "No of Boxs / Weight" ,0,1,'L');

$pdf->SetXY(117,98);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $packaging_qty."  /  ".$pack_weight."Kgs" ,0,1,'L');
	
$pdf->SetXY(117,85);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $deliveryterm ,0,1,'L');

$pdf->Line(12, 114, 208, 114, $style);
$pdf->Line(12, 124, 208, 124, $style);
//dd($total = $pdf->getNumPages());
/************************************Body Lines Content End******************************/
$pdf->SetXY(12,118);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "S.No" ,0,1,'L');

$pdf->SetXY(30,118);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Description of Goods" ,0,1,'L');

$pdf->SetXY(130,118);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Batch No" ,0,1,'L');

$pdf->SetXY(165,118);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
if($sample=="Yes"){
$pdf->Cell(0,0, "Box No" ,0,1,'L');
}else{
$pdf->Cell(0,0, "Box No" ,0,1,'L');	
}

$pdf->SetXY(195,118);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Quantity" ,0,1,'L');

$pdf->Line(21, 114, 21, 290, $style);
$pdf->Line(128, 114, 128, 290, $style);
$pdf->Line(162, 114, 162, 290, $style);
$pdf->Line(191, 114, 191, 290, $style);

$i=0;
$oldY=0;
$dis_amt=0;
$disamt=0;
$gst_tot=0;
$discount=0;
$tax_amt=0;
//$details_var=$value;
$count=1; 
$row_count = 61;
//$value=$linesdata[0];
//for($j=0;$j<95;$j++) {
$total_qty=0;
foreach($linesdata as $key=>$value) { 
	if($oldY==0) $y =125;
	else $y = $oldY+2;	
	$i++;

	$pdf->SetXY(13,$y+3);
	$pdf->SetFont('','','8');
	$pdf->SetTextColor('0','0','0');
	$pdf->Cell(0,0, $i ,0,1,'L');
	$y=$y+11;


	$pdf->SetXY(23,$y-7);
	$pdf->SetFont('','','8');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(75,2, $value->product ,0,'L');
	//$pdf->MultiCell(124,2, "THIRIPALA-CHOORNA-200ML" ,0,'L');
	$oldY=$pdf->getY();
	
	$pdf->SetXY(130,$y-7);
	$pdf->SetFont('','','8');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(13,2, $value->batch_no ,0,'L');
	
	$pdf->SetXY(162,$y-7);
	$pdf->SetFont('','','8');
	$pdf->SetTextColor('0','0','0');
	if($sample=="Yes"){
	$pdf->MultiCell(29,2, $value->box_no ,0,'L');
	}else{
    $pdf->MultiCell(29,2, $value->box_no ,0,'L');		
	}
	$oldYbo=$pdf->getY();
	//$pdf->MultiCell(23,2, "650012" ,0,'L');
		
	$pdf->SetXY(182,$y-7);
	$pdf->SetFont('','','8');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(25,2, $value->qty ,0,'R');
	//$pdf->MultiCell(25,2, "1000" ,0,'R');
			
	$total_qty=$value->qty+$total_qty;
	
	if($oldYbo>$oldY)
	{
		$oldY=$oldYbo;
	}

	if($oldY > 275)
	{
		$oldY = 0;
		if($pdf->pageno()==1)
		{
		
		}
		$pdf->addPage();
		
$pdf->Line(12, 12, 12, 290, $style);
$pdf->Line(12, 12, 208,12, $style);
$pdf->Line(208, 12, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
$pdf->SetXY(90,7);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "DELIVERY NOTE" ,0,1,'L');

		
$pdf->SetXY(60,12);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $company_name ,0,1,'L');

$pdf->SetXY(61,17);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(125,0,$company_address  ,0,'L');

$pdf->SetXY(13,25);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN: ".$cin_no ,0,1,'L');

$pdf->SetXY(115,25);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Website : ".$website_address ,0,1,'L');

$pdf->SetXY(13,30);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Company’s GSTIN/UIN" ,0,1,'L');

$pdf->SetXY(50,30);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, " : ".$gst_no ,0,1,'L');


$pdf->SetXY(13,35);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ph No : +91-44-".$company_contact_no ,0,1,'L');

$pdf->SetXY(115,32);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PAN / Income Tax No." ,0,1,'L');

$pdf->SetXY(153,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, " : ".$pan_no ,0,1,'L');

$pdf->Line(12, 40, 208, 40, $style);

$pdf->SetXY(13,40);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Consignee" ,0,1,'L');

$pdf->SetXY(13,44);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
if($customer_name!=""){
$pdf->MultiCell(100,2, $customer_name ,0,'L');
}else{
$pdf->MultiCell(100,2, $employee_name ,0,'L');	
}

$pdf->SetXY(13,48);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ship_to_address_1 ,0,'L');
$y1=$pdf->getY();

$pdf->SetXY(13,$y1);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Phone : ".$contact_number ,0,1,'L');

$y1=$pdf->getY();
if($alternative_contact_number!=''){
$pdf->SetXY(13,$y1);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Alternative Phone No: ".$alternative_contact_number ,0,1,'L');
}
if($order_type_id!="SAMPLE"){
$pdf->SetXY(13,70);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN" ,0,1,'L');

$pdf->SetXY(33,70);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ": ".$ship_gst_no ,0,1,'L');
}else{
$pdf->SetXY(13,70);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PAN NUMBER" ,0,1,'L');

$pdf->SetXY(33,70);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ": ".$pan_number ,0,1,'L');
}
$pdf->Line(12, 75, 115, 75, $style);

$pdf->SetXY(13,76);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ship to" ,0,1,'L');

$pdf->SetXY(13,80);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
if($customer_name!=""){
$pdf->MultiCell(100,2, $customer_name ,0,'L');
}else{
$pdf->MultiCell(100,2, $employee_name ,0,'L');	
}

$pdf->SetXY(13,84);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ship_to_address_1 ,0,'L');
$y2=$pdf->getY();

$pdf->SetXY(13,$y2);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Phone : ".$contact_number ,0,1,'L');


$y2=$pdf->getY();
if($alternative_contact_number!=''){
$pdf->SetXY(13,$y2);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Alternative Phone No: ".$alternative_contact_number ,0,1,'L');
}
if($order_type_id!="SAMPLE"){
$pdf->SetXY(13,108);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Buyer’s GSTIN" ,0,1,'L');

$pdf->SetXY(40,108);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ": ".$ship_gst_no ,0,1,'L');
}
$pdf->Line(115, 40, 115, 114, $style);


$pdf->Line(163, 40, 163, 79, $style);
$pdf->Line(115, 49, 208, 49, $style);
$pdf->Line(115, 59, 208, 59, $style);
$pdf->Line(115, 69, 208, 69, $style);
$pdf->Line(115, 79, 208, 79, $style);

$pdf->SetXY(117,40);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Delivery Note No." ,0,1,'L');

$pdf->SetXY(166,40);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Dated" ,0,1,'L');

$pdf->SetXY(117,44);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $dispatch_number ,0,1,'L');

$pdf->SetXY(166,44);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $dispatch_date ,0,1,'L');


$pdf->SetXY(117,50);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Buyer’s Order No." ,0,1,'L');

$pdf->SetXY(166,50);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Dated" ,0,1,'L');

$pdf->SetXY(117,54);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $source_number ,0,1,'L');

$pdf->SetXY(166,54);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $source_date ,0,1,'L');


$pdf->SetXY(117,60);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Supplier’s Ref./Order No." ,0,1,'L');

$pdf->SetXY(166,60);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Other Reference(s)" ,0,1,'L');

$pdf->SetXY(117,64);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $customer_po_number ,0,1,'L');

$pdf->SetXY(166,64);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $remarks ,0,1,'L');

$pdf->SetXY(117,70);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Despatched through" ,0,1,'L');

$pdf->SetXY(166,70);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Place Of Supply" ,0,1,'L');

$pdf->SetXY(117,74);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $despatch_through ,0,1,'L');

$pdf->SetXY(166,74);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $state_name_ship ,0,1,'L');

$pdf->SetXY(117,80);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Terms of Delivery" ,0,1,'L');

	
$pdf->SetXY(117,85);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $deliveryterm ,0,1,'L');

$pdf->Line(12, 114, 208, 114, $style);
$pdf->Line(12, 124, 208, 124, $style);
//dd($total = $pdf->getNumPages());
/************************************Body Lines Content End******************************/
$pdf->SetXY(12,118);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "S.No" ,0,1,'L');

$pdf->SetXY(30,118);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Description of Goods" ,0,1,'L');

$pdf->SetXY(130,118);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Batch No" ,0,1,'L');

$pdf->SetXY(165,118);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
if($sample=="Yes"){
$pdf->Cell(0,0, "Box No" ,0,1,'L');
}else{
$pdf->Cell(0,0, "Box No" ,0,1,'L');	
}

$pdf->SetXY(195,118);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Quantity" ,0,1,'L');

$pdf->Line(21, 114, 21, 290, $style);
$pdf->Line(128, 114, 128, 290, $style);
$pdf->Line(162, 114, 162, 290, $style);
$pdf->Line(191, 114, 191, 290, $style);
	}	
	}

$total = $pdf->getNumPages();
//dd($total);
if($pdf->pageNo()==$total)
{	
	//dd($total);
if($oldY < 240)
{
if($oldY==0)
{
$pdf->SetXY(16,164);
$pdf->SetFont('','B','14');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "<---------------------------------------------------End of Page-------------------------------------->",0,'C');
}
$pdf->Line(12, 240, 208, 240, $style);
$pdf->Line(12, 250, 208, 250, $style);
$pdf->Rect(12, 250, 196, 40,'DF', "",  array(255, 255, 255));
	
$pdf->SetXY(110,242);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Total" ,0,1,'L');

$pdf->SetXY(181,242);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(26,2, $total_qty,0,'R');
/*
$pdf->SetXY(13,250);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Amount Chargeable (in words) :" ,0,1,'L');*/
if($order_type_id=="SAMPLE"){
$pdf->SetXY(151,230);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$image_file1 = public_path().'/images/dispatch_print_stamp.png';
$pdf->Image($image_file1, 60.5, 240, 50, 40, 'PNG', '', 'T', false, 250, '', false, false, 0, false, false, false);
}
$pdf->Line(12, 270, 208, 270, $style);

//udhaya
$pdf->SetXY(181,242);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(26,2, '' ,0,'L');
//end


$pdf->SetXY(13,270);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Recd. in Good Condition" ,0,1,'L');

$pdf->SetXY(123,270);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "for ".$company_name ,0,1,'L');

$pdf->SetXY(112,278);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2, $prepared_by ,0,'L');
	
$pdf->SetXY(112,283);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Prepared by" ,0,1,'L');


$pdf->SetXY(146,283);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Verified by" ,0,1,'L');

$pdf->SetXY(175,283);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Authorised Signatory" ,0,1,'L');

$pdf->Line(110, 270, 110, 290, $style);	
	
}
else

{
$pdf->addPage();
$pdf->Line(12, 12, 12, 290, $style);
$pdf->Line(12, 12, 208,12, $style);
$pdf->Line(208, 12, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
$pdf->SetXY(90,7);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "DELIVERY NOTE" ,0,1,'L');


$pdf->SetXY(60,12);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $company_name ,0,1,'L');

$pdf->SetXY(61,17);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(125,0,$company_address  ,0,'L');

$pdf->SetXY(13,25);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN: ".$cin_no ,0,1,'L');

$pdf->SetXY(115,25);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Website : ".$website_address ,0,1,'L');

$pdf->SetXY(13,30);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Company’s GSTIN/UIN" ,0,1,'L');

$pdf->SetXY(50,30);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, " : ".$gst_no ,0,1,'L');


$pdf->SetXY(13,35);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ph No : +91-44-".$company_contact_no ,0,1,'L');

$pdf->SetXY(115,32);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PAN / Income Tax No." ,0,1,'L');

$pdf->SetXY(153,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, " : ".$pan_no ,0,1,'L');



$pdf->Line(12, 40, 208, 40, $style);

$pdf->SetXY(13,40);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Consignee" ,0,1,'L');

$pdf->SetXY(13,44);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
if($customer_name!=""){
$pdf->MultiCell(100,2, $customer_name ,0,'L');
}else{
$pdf->MultiCell(100,2, $employee_name ,0,'L');	
}


$pdf->SetXY(13,48);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ship_to_address_1 ,0,'L');
$y1=$pdf->getY();

$pdf->SetXY(13,$y1);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Phone : ".$contact_number ,0,1,'L');


$y1=$pdf->getY();
if($alternative_contact_number!=''){
$pdf->SetXY(13,$y1);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Alternative Phone No: ".$alternative_contact_number ,0,1,'L');
}
if($order_type_id!="SAMPLE"){
$pdf->SetXY(13,70);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN" ,0,1,'L');

$pdf->SetXY(33,70);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ": ".$ship_gst_no ,0,1,'L');
}else{
$pdf->SetXY(13,70);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PAN NUMBER" ,0,1,'L');

$pdf->SetXY(33,70);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ": ".$pan_number ,0,1,'L');
}
$pdf->Line(12, 75, 115, 75, $style);

$pdf->SetXY(13,76);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ship to" ,0,1,'L');

$pdf->SetXY(13,80);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
if($customer_name!=""){
$pdf->MultiCell(100,2, $customer_name ,0,'L');
}else{
$pdf->MultiCell(100,2, $employee_name ,0,'L');	
}

$pdf->SetXY(13,84);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $ship_to_address_1 ,0,'L');
$y2=$pdf->getY();

$pdf->SetXY(13,$y2);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Phone : ".$contact_number ,0,1,'L');


$y2=$pdf->getY();
if($alternative_contact_number!=''){
$pdf->SetXY(13,$y2);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Alternative Phone No: ".$alternative_contact_number ,0,1,'L');
}
if($order_type_id!="SAMPLE"){
$pdf->SetXY(13,108);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Buyer’s GSTIN" ,0,1,'L');

$pdf->SetXY(40,108);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ": ".$ship_gst_no ,0,1,'L');
}
$pdf->Line(115, 40, 115, 114, $style);


$pdf->Line(163, 40, 163, 79, $style);
$pdf->Line(115, 49, 208, 49, $style);
$pdf->Line(115, 59, 208, 59, $style);
$pdf->Line(115, 69, 208, 69, $style);
$pdf->Line(115, 79, 208, 79, $style);

$pdf->SetXY(117,40);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Delivery Note No." ,0,1,'L');

$pdf->SetXY(166,40);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Dated" ,0,1,'L');

$pdf->SetXY(117,44);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $dispatch_number ,0,1,'L');

$pdf->SetXY(166,44);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $dispatch_date ,0,1,'L');


$pdf->SetXY(117,50);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Buyer’s Order No." ,0,1,'L');

$pdf->SetXY(166,50);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Dated" ,0,1,'L');

$pdf->SetXY(117,54);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $source_number ,0,1,'L');

$pdf->SetXY(166,54);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $source_date ,0,1,'L');


$pdf->SetXY(117,60);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Supplier’s Ref./Order No." ,0,1,'L');

$pdf->SetXY(166,60);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Other Reference(s)" ,0,1,'L');

$pdf->SetXY(117,64);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $customer_po_number ,0,1,'L');

$pdf->SetXY(166,64);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $remarks ,0,1,'L');

$pdf->SetXY(117,70);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Despatched through" ,0,1,'L');

$pdf->SetXY(166,70);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Place Of Supply" ,0,1,'L');

$pdf->SetXY(117,74);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $despatch_through ,0,1,'L');

$pdf->SetXY(166,74);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $state_name_ship ,0,1,'L');

$pdf->SetXY(117,80);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Terms of Delivery" ,0,1,'L');

	
$pdf->SetXY(117,85);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $deliveryterm ,0,1,'L');

$pdf->Line(12, 114, 208, 114, $style);
$pdf->Line(12, 124, 208, 124, $style);
//dd($total = $pdf->getNumPages());
/************************************Body Lines Content End******************************/
$pdf->SetXY(12,118);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "S.No" ,0,1,'L');

$pdf->SetXY(30,118);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Description of Goods" ,0,1,'L');

$pdf->SetXY(130,118);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Batch No" ,0,1,'L');

$pdf->SetXY(165,118);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
if($sample=="Yes"){
$pdf->Cell(0,0, "Box No" ,0,1,'L');
}else{
$pdf->Cell(0,0, "Box No" ,0,1,'L');	
}

$pdf->SetXY(195,118);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Quantity" ,0,1,'L');

$pdf->Line(21, 114, 21, 290, $style);
$pdf->Line(128, 114, 128, 290, $style);
$pdf->Line(162, 114, 162, 290, $style);
$pdf->Line(191, 114, 191, 290, $style);
	
	$pdf->SetXY(16,164);
$pdf->SetFont('','B','14');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "<---------------------------------------------------End of Page-------------------------------------->",0,'C');
	
	
	$pdf->Rect(12, 250, 196, 40,'DF', "",  array(255, 255, 255));
	
	
	
	
	$pdf->Line(12, 240, 208, 240, $style);
$pdf->Line(12, 250, 208, 250, $style);

	
$pdf->SetXY(168,242);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Total" ,0,1,'L');

$pdf->SetXY(181,242);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(26,2, $total_qty ,0,'R');
/*
$pdf->SetXY(13,250);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Amount Chargeable (in words) :" ,0,1,'L');*/
if($order_type_id=="SAMPLE"){
$pdf->SetXY(151,230);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$image_file1 = public_path().'/images/dispatch_print_stamp.png';
$pdf->Image($image_file1, 60.5, 240, 50, 40, 'PNG', '', 'T', false, 250, '', false, false, 0, false, false, false);
}
$pdf->Line(12, 270, 208, 270, $style);

$pdf->SetXY(13,270);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Recd. in Good Condition" ,0,1,'L');

$pdf->SetXY(123,270);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "for ".$company_name ,0,1,'L');

$pdf->SetXY(112,278);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2, $prepared_by ,0,'L');

$pdf->SetXY(112,283);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Prepared by" ,0,1,'L');

$pdf->SetXY(146,283);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Verified by" ,0,1,'L');

$pdf->SetXY(175,283);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Authorised Signatory" ,0,1,'L');

$pdf->Line(110, 270, 110, 290, $style);	
}
	
}
/*******************************************Body content Lines**************************************************/

/*******************************************Body content Lines End**********************************************/

/************************************ Footer ******************************/

/************************************ Footer End ******************************/
ob_end_clean();

if($print=='PRINT')
{				//}              
 $pdf->Output('name.pdf','FI'); 
$pdf->close(); 
exit;
}
else
{
$filename="uploads/dispatch/D_".$dispatch_number.".pdf";
  $pdf->Output('uploads/dispatch/D_'.$dispatch_number.'.pdf', 'F');
 
}

?>