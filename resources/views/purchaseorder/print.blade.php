<?php
// dd("fdhdf");
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
            $hundred = ($counter == 1 && $str[0]) ? 'and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    //return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise ;
    return ($Rupees ? $Rupees .'Rupees ' : '');
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
    public function MultiRow($left, $right,$data) {
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0)

        $page_start = $this->getPage();
        $y_start = $this->GetY();

        // write the left cell
        $this->MultiCell(40, 0, $data, 0, 'R', 0, 2, '', '', true, 0);

        $page_end_1 = $this->getPage();
        $y_end_1 = $this->GetY();

        $this->setPage($page_start);

        // write the right cell
        $this->MultiCell(0, 0, $right, 1, 'J', 0, 1, $this->GetX() ,$y_start, true, 0);

        $page_end_2 = $this->getPage();
        $y_end_2 = $this->GetY();

        // set the new row position by case
        if (max($page_end_1,$page_end_2) == $page_start) {
            $ynew = max($y_end_1, $y_end_2);
        } elseif ($page_end_1 == $page_end_2) {
            $ynew = max($y_end_1, $y_end_2);
        } elseif ($page_end_1 > $page_end_2) {
            $ynew = $y_end_1;
        } else {
            $ynew = $y_end_2;
        }

        $this->setPage(max($page_end_1,$page_end_2));
        $this->SetXY($this->GetX(),$ynew);
    }
//Page header
    public function Header() {
        // Logo
        $image_file = public_path().'/images/'.$this->logo;

       $this->Image($image_file, 14, 9, 13, 12, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
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
			$Total = $this->getNumPages();
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

/************************************Header *********************************/
$pdf->SetXY(70,10);
$pdf->SetFont('','B','20');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PURCHASE ORDER" ,0,1,'L');

$pdf->Line(12, 22, 208, 22, $style);

$pdf->SetXY(13,23);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "INVOICE TO" ,0,1,'L');

$pdf->SetXY(13,27);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "$company_name" ,0,1,'L');

//$pdf->SetXY(13,29);
//$pdf->SetFont('','','8');
//$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, "($company_name)" ,0,1,'L');

//$pdf->SetXY(13,33);
//$pdf->SetFont('','','9');
//$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, "" ,0,1,'L');
if($po_for_verdura == 'YES'){
$pdf->SetXY(12,31);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(102,3, " Mfg Unit: 18, Perumal Koil Street, Regd. Off: 13, Perumal Koil Street,Kunrathur,Chennai,Tamil Nadu,INDIA" ,0,'L');
}else{
$pdf->SetXY(12,31);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(102,3, " $company_address" ,0,'L');    
}

$pdf->SetXY(13,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN              : ".$cin_no ,0,1,'L');

$pdf->SetXY(13,38);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Pin Code : ".$pincode ,0,1,'L');

$pdf->SetXY(13,45);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN  : ".$gstno ,0,1,'L');


$pdf->SetXY(13,49);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PAN NO         : ".$panno ,0,1,'L');

// $pdf->SetXY(13,46);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "State Name :" .$state_name. " ,State Code:"  .$state_code_no ,0,1,'L');

$pdf->SetXY(13,54);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "E-Mail : ".$email_id ,0,1,'L');

$pdf->SetXY(70,54);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Website : ".$website_address ,0,1,'L');

$pdf->Line(12, 59, 208, 59, $style);
$pdf->Line(113, 22, 113, 59, $style);

$pdf->SetXY(13,59);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Supplier :" ,0,1,'L');

$pdf->SetXY(13,63);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','B','0');
$pdf->Cell(0,0, $bcustomer ,0,1,'L');

$pdf->SetXY(13,67);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $supplier_address ,0,'L');
$oldad = $pdf->getY();

$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Contact : ".$bcontact_number ,0,'L');

$pdf->SetXY(13,$oldad+5);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "GSTIN/UIN : ".$bgst_no ,0,'L');

$pdf->SetXY(13,$oldad+9);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(100,2, "State Name : ".$bstate_name ,0,'L');

$pdf->SetXY(113,22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "PO No ",0,'L');

$pdf->SetXY(115,26);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $po_number,0,'L');

$pdf->SetXY(160,22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Dated ",0,'L');

$pdf->SetXY(160,26);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $po_date,0,'L');

$pdf->Line(113, 31, 208, 31, $style);

$pdf->SetXY(113,32);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Frieght Term ",0,'L');

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
$pdf->MultiCell(100,2, $paymentterm,0,'L');

$pdf->Line(113, 40, 208, 40, $style);

$pdf->SetXY(113,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Supplier’s Ref./Order No.",0,'L');

$pdf->SetXY(113,45);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $supplier_reference_no,0,'L');

$pdf->SetXY(160,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Other Reference(s)",0,'L');

$pdf->SetXY(160,45);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $reference_number,0,'L');

$pdf->Line(113, 50, 208, 50, $style);


$pdf->SetXY(113,51);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Despatch through.",0,'L');

$pdf->SetXY(113,55);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $despatch_through,0,'L');

$pdf->SetXY(160,51);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Destination",0,'L');

$pdf->SetXY(160,55);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $destination,0,'L');

$pdf->Line(113, 50, 208, 50, $style);
$pdf->Line(160, 22, 160, 59, $style);
/************************************Header bottom line******************************/
$pdf->Line(113, 59, 113, 93, $style);
$pdf->Line(12, 93, 208, 93, $style);

$pdf->SetXY(113,59);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Terms of Delivery",0,'L');

$pdf->SetXY(113,63);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $deliveryterm,0,'L');
/************************************Header End******************************/

/************************************Body Lines Content******************************/

$pdf->Line(25, 93, 25, 193, $style);
$pdf->Line(75, 93, 75, 193, $style);
$pdf->Line(95, 93, 95, 193, $style);
$pdf->Line(112, 93, 112, 193, $style);
$pdf->Line(127, 93, 127, 193, $style);
$pdf->Line(142, 93, 142, 193, $style);
$pdf->Line(159, 93, 159, 193, $style);
$pdf->Line(169, 93, 169, 193, $style);
$pdf->Line(181, 93, 181, 193, $style);

$pdf->Line(12, 103, 208, 103, $style);
//$pdf->Line(12, 183, 208, 183, $style);
//$pdf->Line(12, 193, 208, 193, $style);

$pdf->SetXY(13,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "S.No",0,'L');

$pdf->SetXY(23,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2, "Description of Goods",0,'C');

$pdf->SetXY(65,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(40,2, "HSN/SAC",0,'C');

$pdf->SetXY(97,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(10,2, "GST Rate",0,'C');

$pdf->SetXY(110,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Due on",0,'C');

$pdf->SetXY(125,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Quantity",0,'C');

$pdf->SetXY(140,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Rate",0,'C');

$pdf->SetXY(155,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Uom",0,'C');

$pdf->SetXY(165,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Disc. %",0,'C');

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
$count=1; 
$row_count = 61;
//$value=$linedata[0];
//for($j=0;$j<31;$j++) {
	//dd($linedata);
	foreach($linedata as $key=>$value) { 
		//dd($value);
	if($oldY==0) $y =105;
	else $y = $oldY+2;	
	$i++;

	$pdf->SetXY(15,$y+1);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->Cell(0,0, $i ,0,1,'L');
	$y=$y+8;
	
	
	$pdf->SetXY(77,$y-7);
	$pdf->SetFont('','','8');
	$pdf->SetTextColor('0','0','0');
	$pdf->Cell(0,0, $value['hsn_code'] ,0,1,'L');
	//$pdf->Cell(0,0, "650012" ,0,1,'L');

	$pdf->SetXY(97,$y-7);
	$pdf->SetFont('','','8');
	$pdf->SetTextColor('0','0','0');
	$pdf->Cell(0,0, $value['gst']." %" ,0,1,'L');
	//$pdf->Cell(0,0, "18 %" ,0,1,'L');

	$pdf->SetXY(112,$y-7);
	$pdf->SetFont('','','7');
	$pdf->SetTextColor('0','0','0');
	$pdf->Cell(0,0, $value['promised_date'] ,0,1,'L');
	//$pdf->Cell(0,0, "" ,0,1,'L');

	$pdf->SetXY(127,$y-7);
	$pdf->SetFont('','','8');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(15,2, $value['qty'] ,0,'R');
	//$pdf->MultiCell(15,2,"1000" ,0,'R');

	$pdf->SetXY(143,$y-7);
	$pdf->SetFont('','','8');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(15,2, $value['unit_price'] ,0,'R');
	//$pdf->MultiCell(15,2, 250 ,0,'R');

	$pdf->SetXY(159,$y-7);
	$pdf->SetFont('','','8');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(10,2, $value['uom'] ,0,'C');
	//$pdf->MultiCell(10,2, "Nos" ,0,'C');

	$pdf->SetXY(170,$y-7);
	$pdf->SetFont('','','8');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(10,2, number_format($value['discount_amount'],2) ,0,'R');
	//$pdf->MultiCell(10,2, "10" ,0,'R');

	$pdf->SetXY(183,$y-7);
	$pdf->SetFont('','','8');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(25,2, number_format($value['amount'],2) ,0,'R');	
	//$pdf->MultiCell(25,2, "25000" ,0,'R');	
		

	$oldY2=$pdf->getY();

	$pdf->SetXY(27,$y-7);
	$pdf->SetFont('','','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(47,2, $value['product'] ,0,'L');

     if( $value['comments'] != '' ){
        $pdf->SetFont('','','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->writeHTMLCell(47,2, 28,$oldY2+4, $value['comments'], 0, 1, false, 'L', false);
	 }
	$oldY=$pdf->getY();
if($value['product_description'] != "" ){
		$pdf->SetFont('','','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->writeHTMLCell(47,2, 28,$oldY+4, $value['product_description'], 0, 1, false, 'L', false);
			$oldY=$pdf->getY();
			
}
	
if($oldY > 250)
{
$oldY = 0;
if($pdf->pageno()==1)
{

$pdf->Line(25, 193, 25, 290, $style);
$pdf->Line(75, 193, 75, 290, $style);
$pdf->Line(95, 193, 95, 290, $style);
$pdf->Line(112, 193, 112, 290, $style);
$pdf->Line(127, 193, 127, 290, $style);
$pdf->Line(142, 193, 142, 290, $style);
$pdf->Line(159, 193, 159, 290, $style);
$pdf->Line(169, 193, 169, 290, $style);
$pdf->Line(181, 193, 181, 290, $style);

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
$pdf->Cell(0,0, "PURCHASE ORDER" ,0,1,'L');

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

/*$pdf->SetXY(12,33);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(102,3, " $company_address" ,0,'L');*/

if($po_for_verdura == 'YES'){
$pdf->SetXY(12,33);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(102,3, " Mfg Unit: 18, Perumal Koil Street, Regd. Off: 13, Perumal Koil Street,Kunrathur,Chennai,Tamil Nadu,INDIA" ,0,'L');
}else{
$pdf->SetXY(12,33);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(102,3, " $company_address" ,0,'L');    
}

$pdf->SetXY(13,36);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Pin Code :".$pincode ,0,1,'L');

$pdf->SetXY(13,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN              : ".$cin_no ,0,1,'L');

$pdf->SetXY(13,45);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN  : ".$gstno ,0,1,'L');
	
$pdf->SetXY(13,49);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PAN NO         : ".$panno ,0,1,'L');

//$pdf->SetXY(13,47);
//$pdf->SetFont('','','9');
//$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, "State Name :" .$state_name. " ,State Code:"  .$state_code_no ,0,1,'L');

$pdf->SetXY(13,54);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "E-Mail : ".$email_id ,0,1,'L');

$pdf->SetXY(70,54);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Website : ".$website_address ,0,1,'L');


$pdf->Line(12, 59, 208, 59, $style);
$pdf->Line(113, 22, 113, 59, $style);

$pdf->SetXY(13,59);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Supplier" ,0,1,'L');

$pdf->SetXY(13,63);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $bcustomer ,0,1,'L');

$pdf->SetXY(13,66);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $supplier_address ,0,'L');
$oldad = $pdf->getY();

$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Contact : ".$bcontact_number ,0,'L');

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
$pdf->MultiCell(100,2, "PO No ",0,'L');

$pdf->SetXY(115,26);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $po_number,0,'L');

$pdf->SetXY(160,22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Dated ",0,'L');

$pdf->SetXY(160,26);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $po_date,0,'L');

$pdf->Line(113, 31, 208, 31, $style);

$pdf->SetXY(113,32);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Frieght Term ",0,'L');

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
$pdf->MultiCell(100,2, $paymentterm,0,'L');

$pdf->Line(113, 40, 208, 40, $style);

$pdf->SetXY(113,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Supplier’s Ref./Order No.",0,'L');

$pdf->SetXY(113,45);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $supplier_reference_no,0,'L');

$pdf->SetXY(160,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Other Reference(s)",0,'L');

$pdf->SetXY(160,45);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $reference_number,0,'L');

$pdf->Line(113, 50, 208, 50, $style);


$pdf->SetXY(113,51);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Despatch through.",0,'L');

$pdf->SetXY(113,55);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $despatch_through,0,'L');

$pdf->SetXY(160,51);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Destination",0,'L');

$pdf->SetXY(160,55);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $destination,0,'L');

$pdf->Line(113, 50, 208, 50, $style);
$pdf->Line(160, 22, 160, 59, $style);
/************************************Header bottom line******************************/
$pdf->Line(113, 59, 113, 93, $style);
$pdf->Line(12, 93, 208, 93, $style);

$pdf->SetXY(113,59);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Terms of Delivery",0,'L');

$pdf->SetXY(113,63);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $deliveryterm,0,'L');
/************************************Header End******************************/

/************************************Body Lines Content******************************/

$pdf->Line(25, 93, 25, 193, $style);
$pdf->Line(75, 93, 75, 193, $style);
$pdf->Line(95, 93, 95, 193, $style);
$pdf->Line(112, 93, 112, 193, $style);
$pdf->Line(127, 93, 127, 193, $style);
$pdf->Line(142, 93, 142, 193, $style);
$pdf->Line(159, 93, 159, 193, $style);
$pdf->Line(169, 93, 169, 193, $style);
$pdf->Line(181, 93, 181, 193, $style);

$pdf->Line(12, 103, 208, 103, $style);


$pdf->SetXY(13,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "S.No",0,'L');

$pdf->SetXY(23,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2, "Description of Goods",0,'C');

$pdf->SetXY(65,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(40,2, "HSN/SAC",0,'C');

$pdf->SetXY(97,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(10,2, "GST Rate",0,'C');

$pdf->SetXY(110,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Due on",0,'C');

$pdf->SetXY(125,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Quantity",0,'C');

$pdf->SetXY(140,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Rate",0,'C');

$pdf->SetXY(155,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Uom",0,'C');

$pdf->SetXY(165,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Disc. %",0,'C');

$pdf->SetXY(185,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Amount",0,'C');
	
	
	
	
}
 $count = $count +1;	

}
$Total = $pdf->getNumPages();
//exit;
if($pdf->pageNo()==$Total)
{	
if($oldY < 180)
{
	
$pdf->Rect(12, 193, 196, 100,'DF', "",  array(255, 255, 255));
	$pdf->Line(12, 183, 208, 183, $style);
$pdf->Line(12, 193, 208, 193, $style);
	
$pdf->SetXY(40,186);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(130,2, "Total",0,'L');

$pdf->SetXY(183,186);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, number_format($sub_total,2),0,'R');

$pdf->SetXY(13,195);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Amount Chargeable (in words)",0,'L');

$pdf->SetXY(13,200);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
if($reverse_charge ==1){
	$grandtotalc=$grand_total-$gsttotal;
}else{
    $grandtotalc=$grand_total;
}
$pdf->MultiCell(150,2, convert_number_to_words($grandtotalc)."only",0,'L');

$pdf->SetXY(13,270);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(100,2, "Company’s Service Tax No.",0,'L');

$pdf->SetXY(56,270);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(100,2, ": ".$tax_reg_no,0,'L');

$pdf->SetXY(13,275);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(100,2, "Company’s PAN",0,'L');

$pdf->SetXY(56,275);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(100,2, ": ".$panno,0,'L');

$pdf->SetXY(110,270);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "for $company_name",0,'C');

$pdf->SetXY(120,283);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Authorised Signatory",0,'R');

$pdf->Line(110, 268, 110, 293, $style);
$pdf->Line(110, 268, 208, 268, $style);
	
	
	
$hsn_y=0;	
$sgat=0;
$cgst=0;
$gsttot=0;
$gsttot=208;
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
$hsn_y=$pdf->getY();
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
if($reverse_charge ==1){
$pdf->SetXY(154,$hsny+20);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, "Rev. GST" ,0,'R');
	
$pdf->SetXY(188,$hsny+20);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, " - ".number_format($gsttotal,2) ,0,'R');    
}
if($other_charges!=0)
{	

$pdf->SetXY(154,$hsny+22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, "Other Charges" ,0,'R');

$pdf->SetXY(188,$hsny+22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, number_format($other_charges,2) ,0,'R');

}
//$whole = floor($grand_total);
//dd($whole);
//$roundoff = $grand_total - $whole;

if($round_off!=0.0){
$hsny=$pdf->getY();
$pdf->SetXY(154,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, "Round Off" ,0,'R');
	
$pdf->SetXY(188,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, number_format($round_off,2),0,'R');	

}
$hsny=$pdf->getY();
//dd($round_off);
$pdf->SetXY(154,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, "Grand Total" ,0,'R');
if($reverse_charge ==1){
	$grandtotal=$grand_total-$gsttotal;
}else{
    $grandtotal=$grand_total+$round_off;
}
$pdf->SetXY(188,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, number_format($grandtotal,2) ,0,'R');	
	
	
	
}
else
{
	$pdf->Line(25, 193, 25, 290, $style);
$pdf->Line(75, 193, 75, 290, $style);
$pdf->Line(95, 193, 95, 290, $style);
$pdf->Line(112, 193, 112, 290, $style);
$pdf->Line(127, 193, 127, 290, $style);
$pdf->Line(142, 193, 142, 290, $style);
$pdf->Line(159, 193, 159, 290, $style);
$pdf->Line(169, 193, 169, 290, $style);
$pdf->Line(181, 193, 181, 290, $style);
$pdf->addPage();
	$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
$pdf->SetXY(70,10);
$pdf->SetFont('','B','20');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PURCHASE ORDER" ,0,1,'L');

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

/*$pdf->SetXY(12,33);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(102,3, " $company_address" ,0,'L');*/

if($po_for_verdura == 'YES'){
$pdf->SetXY(12,33);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(102,3, " Mfg Unit: 18, Perumal Koil Street, Regd. Off: 13, Perumal Koil Street,Kunrathur,Chennai,Tamil Nadu,INDIA" ,0,'L');
}else{
$pdf->SetXY(12,33);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(102,3, " $company_address" ,0,'L');    
}

$pdf->SetXY(13,36);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Pin Code :".$pincode ,0,1,'L');

$pdf->SetXY(13,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN              : ".$cin_no ,0,1,'L');

$pdf->SetXY(13,45);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN  : ".$gstno ,0,1,'L');
	
$pdf->SetXY(13,49);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PAN NO         : ".$panno ,0,1,'L');

// $pdf->SetXY(13,47);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "State Name :" .$state_name. " ,State Code:"  .$state_code_no ,0,1,'L');

$pdf->SetXY(13,54);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "E-Mail : ".$email_id ,0,1,'L');

$pdf->SetXY(70,54);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Website : ".$website_address ,0,1,'L');

$pdf->Line(12, 59, 208, 59, $style);
$pdf->Line(113, 22, 113, 59, $style);

$pdf->SetXY(13,59);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Supplier" ,0,1,'L');

$pdf->SetXY(13,63);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $bcustomer ,0,1,'L');

$pdf->SetXY(13,66);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $supplier_address ,0,'L');
$oldad = $pdf->getY();

$pdf->SetXY(13,$oldad);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Contact : ".$bcontact_number ,0,'L');

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
$pdf->MultiCell(100,2, "PO No ",0,'L');

$pdf->SetXY(115,26);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $po_number,0,'L');

$pdf->SetXY(160,22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Dated ",0,'L');

$pdf->SetXY(160,26);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $po_date,0,'L');

$pdf->Line(113, 31, 208, 31, $style);

$pdf->SetXY(113,32);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Frieght Term ",0,'L');

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
$pdf->MultiCell(100,2, $paymentterm,0,'L');

$pdf->Line(113, 40, 208, 40, $style);

$pdf->SetXY(113,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Supplier’s Ref./Order No.",0,'L');

$pdf->SetXY(113,45);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $supplier_reference_no,0,'L');

$pdf->SetXY(160,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Other Reference(s)",0,'L');

$pdf->SetXY(160,45);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $reference_number,0,'L');

$pdf->Line(113, 50, 208, 50, $style);


$pdf->SetXY(113,51);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Despatch through.",0,'L');

$pdf->SetXY(113,55);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $despatch_through,0,'L');

$pdf->SetXY(160,51);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Destination",0,'L');

$pdf->SetXY(160,55);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $destination,0,'L');

$pdf->Line(113, 50, 208, 50, $style);
$pdf->Line(160, 22, 160, 59, $style);
/************************************Header bottom line******************************/
$pdf->Line(113, 59, 113, 93, $style);
$pdf->Line(12, 93, 208, 93, $style);

$pdf->SetXY(113,59);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Terms of Delivery",0,'L');

$pdf->SetXY(113,63);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, $deliveryterm,0,'L');
/************************************Header End******************************/

/************************************Body Lines Content******************************/

$pdf->Line(25, 93, 25, 193, $style);
$pdf->Line(75, 93, 75, 193, $style);
$pdf->Line(95, 93, 95, 193, $style);
$pdf->Line(112, 93, 112, 193, $style);
$pdf->Line(127, 93, 127, 193, $style);
$pdf->Line(142, 93, 142, 193, $style);
$pdf->Line(159, 93, 159, 193, $style);
$pdf->Line(169, 93, 169, 193, $style);
$pdf->Line(181, 93, 181, 193, $style);

$pdf->Line(12, 103, 208, 103, $style);
//$pdf->Line(12, 183, 208, 183, $style);
//$pdf->Line(12, 193, 208, 193, $style);

$pdf->SetXY(13,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "S.No",0,'L');

$pdf->SetXY(23,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2, "Description of Goods",0,'C');

$pdf->SetXY(65,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(40,2, "HSN/SAC",0,'C');

$pdf->SetXY(97,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(10,2, "GST Rate",0,'C');

$pdf->SetXY(110,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Due on",0,'C');

$pdf->SetXY(125,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Quantity",0,'C');

$pdf->SetXY(140,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Rate",0,'C');

$pdf->SetXY(155,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Uom",0,'C');

$pdf->SetXY(165,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Disc. %",0,'C');

$pdf->SetXY(185,94);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, "Amount",0,'C');
	
$pdf->Rect(12, 193, 196, 100,'DF', "",  array(255, 255, 255));
$pdf->Line(12, 183, 208, 183, $style);
$pdf->Line(12, 193, 208, 193, $style);
	
	$pdf->SetXY(20,150);
$pdf->SetFont('','','14');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "<---------------------------------------------End Of Page----------------------------------------------->" ,0,1,'L');
	
$pdf->SetXY(60,186);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Total ",0,'L');

$pdf->SetXY(183,186);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, number_format($sub_total,2),0,'R');

$pdf->SetXY(13,195);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,2, "Amount Chargeable (in words)",0,'L');

$pdf->SetXY(13,200);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
if($reverse_charge ==1){
	$grandtotalc=$grand_total-$gsttotal;
}else{
    $grandtotalc=$grand_total;
}
$pdf->MultiCell(150,2, convert_number_to_words($grandtotalc),0,'L');

$pdf->SetXY(13,270);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(100,2, "Company’s Service Tax No.",0,'L');

$pdf->SetXY(56,270);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(100,2, ": ".$tax_reg_no,0,'L');

$pdf->SetXY(13,275);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(100,2, "Company’s PAN",0,'L');

$pdf->SetXY(56,275);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(100,2, ": ".$panno,0,'L');

$pdf->SetXY(110,270);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "for $company_name",0,'C');

$pdf->SetXY(120,283);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2, "Authorised Signatory",0,'R');

$pdf->Line(110, 268, 110, 290, $style);
$pdf->Line(110, 268, 208, 268, $style);
	
	
$hsn_y=0;	
$sgat=0;
$cgst=0;
$gsttot=0;
$gsttot=208;
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
$hsn_y=$pdf->getY();
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
if($reverse_charge ==1){
$pdf->SetXY(154,$hsny+20);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, "Rev. GST" ,0,'R');
	
$pdf->SetXY(188,$hsny+20);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, " - ".number_format($gsttotal,2) ,0,'R');    
}	
/*$pdf->SetXY(154,$hsny+22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, "Grand Total" ,0,'R');*/


if($round_off!=0.0){
$hsny=$pdf->getY();
$pdf->SetXY(154,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, "Round Off" ,0,'R');
	
$pdf->SetXY(188,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, number_format($round_off,2),0,'R');	

}
$hsny=$pdf->getY();
//dd($round_off);
$pdf->SetXY(154,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, "Grand Total" ,0,'R');
	if($reverse_charge ==1){
	$grandtotal=$grand_total-$gsttotal;
}else{
    $grandtotal=$grand_total+$round_off;
}
	
$pdf->SetXY(188,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(19,10, number_format($grandtotal,2) ,0,'R');	

	
// $pdf->SetXY(188,$hsny+22);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(19,10, number_format($grand_total,2) ,0,'R');
	
	
}

}



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
$pdf->Cell(0,0, "PURCHASE ORDER" ,0,1,'L');

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
$pdf->Cell(0,0, "PURCHASE ORDER" ,0,1,'L');

$pdf->Line(12, 22, 208, 22, $style);

	}
	
}

}


//dd($Total = $pdf->getNumPages());
/************************************Body Lines Content End******************************/

/************************************ Footer ******************************/


/************************************ Footer End ******************************/
ob_end_clean();
  
  
   // dd($print);
  if($print=='PRINT')
{				//}              
 $pdf->Output('name.pdf','FI'); 
$pdf->close(); 
exit;
}
else
{

  $pdf->Output('uploads/purchaseorder/PO_'.$po_hdr_id.'.pdf', 'F');
 
}




/* end */
//$pdf->Output('example_007.pdf', 'FI');
//exit();
//============================================================+
// END OF FILE
//============================================================+

/******************************************************************************************/ 
?>