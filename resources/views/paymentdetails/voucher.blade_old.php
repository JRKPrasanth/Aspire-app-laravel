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

       $this->Image($image_file, 460, 40, 75, 73, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);

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
$pdf->Line(25, 27, 25, 600, $style);
$pdf->Line(570, 27, 570, 600, $style);
$pdf->Line(25, 600, 570, 600, $style);
//Margin End

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

/*
$pdf->SetXY(168,55);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(250,10, $company_name ,0,'C');

$pdf->SetXY(35,70);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'ADDRESS',0,1,'L');

$company_address=$location_name.",".$address1.",".$street.",".$location.",".$area.",".$city.",".$state.",".$country;

$pdf->SetXY(55,85);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(270,10,$company_address,'0','L');
*/
/*
$pdf->SetXY(35,70);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'ADDRESS',0,1,'L');

$pdf->SetXY(55,85);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'SYMTEC Tower,',0,1,'L');

$pdf->SetXY(55,100);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'F-5, IIIrd Phase,',0,1,'L');

$pdf->SetXY(55,115);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Ekkaduthangal, Chennai - 600 032.',0,1,'L');*/

$pdf->SetXY(35,170);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'TO',0,1,'L');

$pdf->SetXY(55,180);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,$supplier_name,0,1,'L');

$pdf->SetXY(55,195);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(280,0,$supplier_address,0,'L');


$pdf->SetXY(40,265);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'S.No',0,1,'L');

$pdf->SetXY(50,290);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'1',0,1,'L');

$pdf->SetXY(80,265);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(80,0,'Payment Type',0,1,'L');

$pdf->SetXY(80,290);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,10,$payment_name,0,'C');



$pdf->SetXY(150,265);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(110,0,'Payment Ref',0,1,'C');
//$pdf->MultiCell(130,10,'Payment Ref/Cheque No',0,'C');

$pdf->SetXY(150,290);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(110,10,$payment_reference,0,'C');

$pdf->SetXY(250,265);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(120,0,'Cheque No',0,1,'C');

$pdf->SetXY(250,290);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,10,$cheque_no,0,'C');

// $pdf->SetXY(215,290);
// $pdf->SetFont('','','10');
// $pdf->SetTextColor('0','0','0');
// if($payment_name!="CHEQUE")
// {
// $pdf->MultiCell(150,10,$payment_reference,0,'C');
// }
// else
// {
// $pdf->MultiCell(150,10,$cheque_no,0,'C');
// }

$pdf->SetXY(380,265);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Payment Date',0,1,'L');

$pdf->SetXY(370,290);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(85,10,$payment_date,0,'C');

$pdf->SetXY(490,265);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Amount (Rs)',0,1,'L');

$pdf->SetXY(465,290);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,10,number_format($payment_amount,2),0,'R');

$pdf->SetXY(380,350);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Total Amount',0,1,'L');


$pdf->SetXY(465,350);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,10,number_format($payment_amount,2),0,'R');

//body lines
$pdf->Line(37, 260, 560, 260, $style);
$pdf->Line(37, 280, 560, 280, $style);
$pdf->Line(37, 350, 560, 350, $style);
$pdf->Line(37, 370, 560, 370, $style);

$pdf->Line(37, 260, 37, 370, $style);
$pdf->Line(70, 260, 70, 370, $style);
$pdf->Line(160, 260, 160, 370, $style);
$pdf->Line(260, 260, 260, 370, $style);
$pdf->Line(370, 260, 370, 370, $style);
$pdf->Line(460, 260, 460, 370, $style);
$pdf->Line(560, 260, 560, 370, $style);

// //Payment Details lines
// $pdf->Line(37, 400, 560, 400, $style);
// $pdf->Line(37, 420, 560, 420, $style);
// $pdf->Line(37, 440, 560, 440, $style);
// $pdf->Line(37, 500, 560, 500, $style);
// $pdf->Line(37, 520, 560, 520, $style);

// $pdf->Line(37, 400, 37, 520, $style);
// $pdf->Line(560, 400, 560, 520, $style);
// $pdf->Line(140, 420, 140, 520, $style);
// $pdf->Line(300, 420, 300, 520, $style);
// $pdf->Line(430, 420, 430, 520, $style);



// $pdf->SetXY(100,402);
// $pdf->SetFont('','B','11');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(400,0,'Payment Details',0,1,'C');

// $pdf->SetXY(40,423);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0,'Payment Mode',0,1,'L');


// $pdf->SetXY(170,423);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0,'Instrument Details',0,1,'L');


// $pdf->SetXY(340,423);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0,'Issued From',0,1,'L');

// $pdf->SetXY(465,423);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0,'Amount (Rs)',0,1,'L');

// $pdf->SetXY(340,500);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0,'Total Amount',0,1,'L');

$pdf->SetXY(37,402);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Kindly acknowledge the receipt.',0,1,'L');


$pdf->SetXY(37,420);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Thanking You',0,1,'L');


$pdf->SetXY(37,500);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Authorised Signatory',0,1,'L');

$pdf->SetXY(37,550);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Checked by',0,1,'L');


$pdf->SetXY(480,500);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Receiver’s Signature',0,1,'R');

$pdf->SetXY(480,550);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Verified by',0,1,'R');




// $pdf->SetXY(100,600);
// $pdf->SetFont('','','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0,'Preparer',0,1,'L');

// $pdf->SetXY(280,600);
// $pdf->SetFont('','','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0,'Approver',0,1,'L');

// $pdf->SetXY(490,600);
// $pdf->SetFont('','','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0,'Receiver',0,1,'L');

//Body lines End

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
