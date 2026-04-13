<?php

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'Px', PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
//$pdf->SetTitle('TCPDF Example 007');
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// set default header data

//$pdf=new TCPDF('P', 'pt');
// set header and footer fonts
/* $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
 */
// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//
// set auto page breaks
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
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

    public function setLogo($company_name){
        $this->logo = $company_name;
    }
//Page header
    public function Header() {

$find = ["[logo]","[company_name]", "[company_address]", "[current_date]","[position]","[employee_name]","[gross_salary]","[gross_text]","[at date]","[valid date]","[HR name]"];
//dd($find);
        $image_file = public_path().'/images/'.$this->logo;

       $this->Image($image_file, 490, 40, 75, 73, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);

               $this->SetFont('helvetica', 'B', 15);
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
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, 'Px', PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setLogo($company_logo_name);
// add a page
$pdf->AddPage('P', 'A4');


$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));
//margin
$pdf->Line(25, 27, 570, 27, $style);
$pdf->Line(25, 27, 25, 780, $style);
$pdf->Line(570, 27, 570, 780, $style);
$pdf->Line(25, 780, 570, 780, $style);
//Margin End

// Company Details
$pdf->SetXY(44,40);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('255','0','0');
$pdf->Cell(0,0,$company_name,0,1,'L');

$pdf->SetXY(44,56);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'(formerly  Dr. JRK`s Siddha Research and Pharmaceuticals Private Limited)',0,1,'L');

$pdf->SetXY(44,67);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,''.$address0.','.$location_name1.','.$city_name1.'-'.$pincode1.'.'.$country_name1,0,1,'L');

//$pdf->SetXY(44,73);
//$pdf->SetFont('','','8');
//$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0,'Mfg unit : '.$address2.','.$location_name2.','.$city_name2.'-'.$pincode2.'.'.$country_name2,0,1,'L');

$pdf->SetXY(44,80);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Mail : '.$email_id.'  Website : '.$website_address,0,1,'L');

$pdf->SetXY(44,91);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Helpline : 91-44-'.$contact_no1,0,1,'L');

//$pdf->Cell(0,0,'Helpline : 91-44-'.$contact_no1.'/ '.$contact_no2,0,1,'L');

$pdf->SetXY(44,103);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'CIN : '.$cin_no.'  GST No : '.$gst_no,0,1,'L');

/*END Company*/

$pdf->SetXY(240,125);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Payment Advice',0,1,'L');

$pdf->Line(240, 140, 340, 140, $style);
$pdf->Line(240, 140, 340, 140, $style);

$pdf->SetXY(380,140);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Date',0,1,'L');

$date=date('d-m-Y');
$pdf->SetXY(435,140);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,': '.$date,0,1,'L');


$pdf->SetXY(380,155);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Voucher No',0,1,'L');

$pdf->SetXY(435,155);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,': '.$payment_number,0,1,'L');

$pdf->SetXY(380,170);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Cash/Bank',0,1,'L');

$pdf->SetXY(435,170);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,': '.$bank_name,0,1,'L');

$pdf->SetXY(380,185);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Account No',0,1,'L');

$pdf->SetXY(435,185);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,': '.$account_number,0,1,'L');

$pdf->SetXY(35,140);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'TO',0,1,'L');

$pdf->SetXY(55,150);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(300,0,$supplier_name,0,'L');

$ss=$pdf->getY();

if($favouring_name!="")
{
$pdf->SetXY(55,$ss);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(300,0,$favouring_name,0,'L');
$ss=$pdf->getY();
$pdf->SetXY(55,$ss);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(280,0,$supplier_address,0,'L'); 
}else
{
  $pdf->SetXY(55,$ss);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(280,0,$supplier_address,0,'L');
}

$pdf->SetXY(40,215);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Dear Sir/Madam,',0,1,'L');


$pdf->SetXY(40,230);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Please Find the Below Payment Details :',0,1,'L');

$pdf->SetXY(40,265);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(170,0,'Bill Ref.',0,1,'C');

$pdf->SetXY(230,265);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Bill Date',0,1,'L');

$pdf->SetXY(313,265);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(120,0,'PO/Invoice Amount (Rs)',0,1,'L');

$pdf->SetXY(435,265);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(125,0,'Payment Amount (Rs)',0,1,'C');


$oldY=0;

//$details_var=$value;
$count=1; 
//$row_count = 61;
//$value=$linedata[0];
//for($j=0;$j<29;$j++) {
foreach($linedata as $key=>$value) { 
  if($oldY==0) $y =285;
  else $y = $oldY+2; 

$pdf->SetXY(40,$y+1);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(160,0,$value['bill_number'],0,1,'L');

$pdf->SetXY(200,$y+1);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(110,0,$value['invoice_date'],0,1,'C');

$pdf->SetXY(330,$y+1);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,10,number_format(($value['invoice_grand_total']),2),0,'R');

$pdf->SetXY(465,$y+1);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,10,number_format(($value['paid_amount']),2),0,'R');
$oldY=$pdf->getY();
  

  if($value['tds_applicable']=="YES" || $value['tds_applicable']=="Yes"){
  $pdf->SetXY(40,$oldY+10);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(160,0,"TDS-".$value['bill_number'],0,1,'L');

$pdf->SetXY(200,$oldY+10);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(110,0,"",0,1,'C');

$pdf->SetXY(465,$oldY+10);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,10,"-".number_format($value['tds_amount'],2),0,'R');
  }

$oldY=$pdf->getY();

}
if($payment_source!="ADVANCE")
{
    if($advance_amount!=0){
      
$pdf->SetXY(40,$oldY+1);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(160,0,"ADVANCE",0,1,'L');

$pdf->SetXY(200,$oldY+1);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(110,0,"",0,1,'C');


$pdf->SetXY(465,$oldY+1);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,10,'(-) '.number_format($advance_amount,2),0,'R');
  }
}
//$pdf->Rect(25, 420, 545, 380,'DF', "",  array(255, 255, 255));
//body lines
$pdf->Line(37, 260, 560, 260, $style);
$pdf->Line(37, 280, 560, 280, $style);
$oldY=$pdf->getY();

$pdf->Line(37, 260, 37, $oldY+32, $style);
 $pdf->Line(200, 260, 200, $oldY+32, $style);
 $pdf->Line(310, 260, 310, $oldY+32, $style);
 $pdf->Line(435, 260, 435, $oldY+32, $style);
$pdf->Line(560, 260, 560, $oldY+32, $style);
$pdf->Line(37, $oldY+10, 560, $oldY+10, $style);
$pdf->Line(37, $oldY+32, 560, $oldY+32, $style);

 $pdf->SetXY(230,$oldY+10);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Net Amount',0,1,'L');
$pdf->SetXY(465,$oldY+10);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,10,number_format(($grand_total-$advance_amount-$tdstotal),2),0,'R');
if($oldY < 455)
{
$pdf->SetXY(90,$oldY+42);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(400,0,'Payment Details',0,1,'C');

$pdf->SetXY(40,$oldY+60);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'S.No',0,1,'L');

$pdf->SetXY(50,$oldY+82);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'1',0,1,'L');

$pdf->SetXY(80,$oldY+60);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(80,0,'Payment Type',0,1,'L');

$pdf->SetXY(80,$oldY+82);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,10,$payment_name,0,'C');

$pdf->SetXY(150,$oldY+60);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(110,0,'UTR Number',0,1,'C');
//$pdf->MultiCell(130,10,'Payment Ref/Cheque No',0,'C');

$pdf->SetXY(150,$oldY+82);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(110,10,$payment_reference,0,'C');

$pdf->SetXY(250,$oldY+60);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(120,0,'Cheque No',0,1,'C');

$pdf->SetXY(250,$oldY+82);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,10,$cheque_no,0,'C');

$pdf->SetXY(380,$oldY+60);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Payment Date',0,1,'L');

$pdf->SetXY(370,$oldY+82);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(85,10,$payment_date,0,'C');

$pdf->SetXY(490,$oldY+60);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Amount (Rs)',0,1,'L');

$pdf->SetXY(465,$oldY+82);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,10,number_format($payment_amount,2),0,'R');

$pdf->SetXY(380,$oldY+130);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Total Amount',0,1,'L');


$pdf->SetXY(465,$oldY+130);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,10,number_format($payment_amount,2),0,'R');

//Payment body lines

$pdf->Line(37, $oldY+40, 560, $oldY+40, $style);
$pdf->Line(37, $oldY+60, 560, $oldY+60, $style);
$pdf->Line(37, $oldY+78, 560, $oldY+78, $style);
$pdf->Line(37, $oldY+125, 560, $oldY+125, $style);
$pdf->Line(37, $oldY+155, 560, $oldY+155, $style);

$pdf->Line(37, $oldY+40, 37, $oldY+155, $style);
$pdf->Line(70, $oldY+60, 70, $oldY+155, $style);
$pdf->Line(160, $oldY+60, 160, $oldY+155, $style);
$pdf->Line(260, $oldY+60, 260, $oldY+155, $style);
$pdf->Line(370, $oldY+60, 370, $oldY+155, $style);
$pdf->Line(460, $oldY+60, 460, $oldY+155, $style);
$pdf->Line(560, $oldY+40, 560, $oldY+155, $style);

$pdf->SetXY(37,$oldY+162);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(500,0,$remarks,0,'L');

$pdf->SetXY(37,$oldY+190);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Kindly acknowledge the receipt.',0,1,'L');


$pdf->SetXY(37,$oldY+210);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Thanking You',0,1,'L');


$pdf->SetXY(37,$oldY+260);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Authorised Signatory',0,1,'L');

$pdf->SetXY(37,$oldY+320);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Checked by',0,1,'L');


$pdf->SetXY(480,$oldY+260);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Receiver’s Signature',0,1,'R');

$pdf->SetXY(480,$oldY+320);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Verified by',0,1,'R');

}
if($oldY > 455)
{
$oldY = 0;

//margin
$pdf->Line(25, 27, 570, 27, $style);
$pdf->Line(25, 27, 25, 780, $style);
$pdf->Line(570, 27, 570, 780, $style);
$pdf->Line(25, 780, 570, 780, $style);
//Margin End
$pdf->addPage();
$pdf->Line(25, 27, 570, 27, $style);
$pdf->Line(25, 27, 25, 780, $style);
$pdf->Line(570, 27, 570, 780, $style);
$pdf->Line(25, 780, 570, 780, $style);
//  //body lines
//$pdf->Line(37, 260, 560, 260, $style);
//$pdf->Line(37, 280, 560, 280, $style);
//$pdf->Line(37, 380, 560, 380, $style);
//$pdf->Line(37, 400, 560, 400, $style);
//
//$pdf->Line(37, 260, 37, 400, $style);
//// $pdf->Line(70, 260, 70, 370, $style);
// $pdf->Line(200, 260, 200, 400, $style);
// $pdf->Line(310, 260, 310, 400, $style);
//// $pdf->Line(370, 260, 370, 370, $style);
////$pdf->Line(460, 260, 460, 370, $style);
//$pdf->Line(560, 260, 560, 400, $style);
  $count = $count + 1;

// Company Details
$pdf->SetXY(44,40);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('255','0','0');
$pdf->Cell(0,0,$company_name,0,1,'L');

$pdf->SetXY(44,53);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'(formerly '.$company_name.')',0,1,'L');

$pdf->SetXY(44,63);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Regd Office : '.$address0.','.$location_name1.','.$city_name1.'-'.$pincode1.'.'.$country_name1,0,1,'L');

$pdf->SetXY(44,73);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Mfg unit : '.$address2.','.$location_name2.','.$city_name2.'-'.$pincode2.'.'.$country_name2,0,1,'L');

$pdf->SetXY(44,83);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Mail : '.$email_id.'  Website : '.$website_address,0,1,'L');

$pdf->SetXY(44,93);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Helpline : 91-44-'.$contact_no1,0,1,'L');

//$pdf->Cell(0,0,'Helpline : 91-44-'.$contact_no1.'/ '.$contact_no2,0,1,'L');

$pdf->SetXY(44,103);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'CIN : '.$cin_no.'  GST No : '.$gst_no,0,1,'L');

$pdf->SetXY(240,125);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Payment Advice',0,1,'L');

$pdf->Line(240, 140, 340, 140, $style);
$pdf->Line(240, 140, 340, 140, $style);

$pdf->SetXY(380,140);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Date',0,1,'L');

$date=date('d-m-Y');
$pdf->SetXY(435,140);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,': '.$date,0,1,'L');


$pdf->SetXY(380,155);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Voucher No',0,1,'L');

$pdf->SetXY(435,155);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,': '.$payment_number,0,1,'L');

$pdf->SetXY(380,170);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Cash/Bank',0,1,'L');

$pdf->SetXY(435,170);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,': '.$bank_name,0,1,'L');

$pdf->SetXY(380,185);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Account No',0,1,'L');

$pdf->SetXY(435,185);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,': '.$account_number,0,1,'L');

$pdf->SetXY(35,140);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'TO',0,1,'L');

$pdf->SetXY(55,150);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,$supplier_name,0,1,'L');


$ss=$pdf->getY();

if($favouring_name!="")
{
$pdf->SetXY(55,$ss);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,$favouring_name,0,1,'L');

$pdf->SetXY(55,$ss+10);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(280,0,$supplier_address,0,'L'); 
}else
{
  $pdf->SetXY(55,$ss);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(280,0,$supplier_address,0,'L');
} 

$pdf->SetXY(40,205);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Dear Sir/Madam,',0,1,'L');

$pdf->SetXY(40,230);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Please Find the Below Payment Details :',0,1,'L');

$oldY=$pdf->getY();
$pdf->SetXY(90,$oldY+42);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(400,0,'Payment Details',0,1,'C');

$pdf->SetXY(40,$oldY+60);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'S.No',0,1,'L');

$pdf->SetXY(50,$oldY+82);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'1',0,1,'L');

$pdf->SetXY(80,$oldY+60);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(80,0,'Payment Type',0,1,'L');

$pdf->SetXY(80,$oldY+82);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,10,$payment_name,0,'C');

$pdf->SetXY(150,$oldY+60);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(110,0,'Payment Ref',0,1,'C');
//$pdf->MultiCell(130,10,'Payment Ref/Cheque No',0,'C');

$pdf->SetXY(150,$oldY+82);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(110,10,$payment_reference,0,'C');

$pdf->SetXY(250,$oldY+60);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(120,0,'Cheque No',0,1,'C');

$pdf->SetXY(250,$oldY+82);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,10,$cheque_no,0,'C');

$pdf->SetXY(380,$oldY+60);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Payment Date',0,1,'L');

$pdf->SetXY(370,$oldY+82);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(85,10,$payment_date,0,'C');

$pdf->SetXY(490,$oldY+60);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Amount (Rs)',0,1,'L');

$pdf->SetXY(465,$oldY+82);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,10,number_format($payment_amount,2),0,'R');

$pdf->SetXY(380,$oldY+130);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Total Amount',0,1,'L');


$pdf->SetXY(465,$oldY+130);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,10,number_format($payment_amount,2),0,'R');

//Payment body lines

$pdf->Line(37, $oldY+40, 560, $oldY+40, $style);
$pdf->Line(37, $oldY+60, 560, $oldY+60, $style);
$pdf->Line(37, $oldY+78, 560, $oldY+78, $style);
$pdf->Line(37, $oldY+125, 560, $oldY+125, $style);
$pdf->Line(37, $oldY+155, 560, $oldY+155, $style);

$pdf->Line(37, $oldY+40, 37, $oldY+155, $style);
$pdf->Line(70, $oldY+60, 70, $oldY+155, $style);
$pdf->Line(160, $oldY+60, 160, $oldY+155, $style);
$pdf->Line(260, $oldY+60, 260, $oldY+155, $style);
$pdf->Line(370, $oldY+60, 370, $oldY+155, $style);
$pdf->Line(460, $oldY+60, 460, $oldY+155, $style);
$pdf->Line(560, $oldY+40, 560, $oldY+155, $style);

$pdf->SetXY(37,$oldY+162);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(500,0,$remarks,0,'L');

$pdf->SetXY(37,$oldY+190);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Kindly acknowledge the receipt.',0,1,'L');


$pdf->SetXY(37,$oldY+210);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Thanking You',0,1,'L');


$pdf->SetXY(37,$oldY+260);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Authorised Signatory',0,1,'L');

$pdf->SetXY(37,$oldY+320);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Checked by',0,1,'L');


$pdf->SetXY(480,$oldY+260);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Receiver’s Signature',0,1,'R');

$pdf->SetXY(480,$oldY+320);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Verified by',0,1,'R');


}
 $count = $count +1;

$Total = $pdf->getNumPages();

//exit;

// reset pointer to the last page
$pdf->lastPage();


//Close and output PDF document
ob_end_clean();
    
$pdf->Output('example_007.pdf', 'FI');
exit();
//============================================================+
// END OF FILE
//============================================================+

/******************************************************************************************/ 
?>
