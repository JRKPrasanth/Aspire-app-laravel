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
        $image_file = public_path().'/images/'.$this->logo;
//        $image_file = public_path().'/images/jrks.png';
       $this->Image($image_file, 15, 6, 28, 17, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
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
            //dd($Total);
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
$pdf->SetFont('','','9');
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

$pdf->SetXY(12,31);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(102,3, " $company_address" ,0,'L');

$pdf->SetXY(60,36);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN:".$cin_no ,0,1,'L');

$pdf->SetXY(13,36);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Pin Code : ".$pincode ,0,1,'L');

$pdf->SetXY(13,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN: ".$gstno ,0,1,'L');


$pdf->SetXY(70,41);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "PAN NO: ".$panno ,0,1,'L');

$pdf->SetXY(13,46);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State Name :" .$state_name. " ,State Code:"  .$state_code_no ,0,1,'L');

$pdf->SetXY(13,50);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "E-Mail: ".$email_id ,0,1,'L');

$pdf->SetXY(13,54);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Website: ".$website_address ,0,1,'L');

$pdf->Line(12, 59, 208, 59, $style);
$pdf->Line(113, 22, 113, 59, $style);

$pdf->SetXY(13,59);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Supplier :" ,0,1,'L');

$pdf->SetXY(13,64);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','B','0');
$pdf->Cell(0,0, $bcustomer ,0,1,'L');

$pdf->SetXY(113,22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Voucher No ",0,'L');

$pdf->SetXY(160,22);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Dated ",0,'L');


$pdf->Line(113, 31, 208, 31, $style);

$pdf->SetXY(160,32);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Mode/Terms of Payment ",0,'L');

$pdf->Line(113, 40, 208, 40, $style);

$pdf->SetXY(113,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Supplier’s Ref./Order No.",0,'L');

$pdf->SetXY(160,41);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Other Reference(s)",0,'L');

$pdf->Line(113, 50, 208, 50, $style);


$pdf->SetXY(113,51);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Despatch through.",0,'L');


$pdf->SetXY(160,51);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Destination",0,'L');


$pdf->Line(113, 50, 208, 50, $style);
$pdf->Line(160, 22, 160, 59, $style);
/************************************Header bottom line******************************/
$pdf->Line(113, 59, 113, 93, $style);
$pdf->Line(12, 93, 208, 93, $style);

$pdf->SetXY(113,59);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2, "Terms of Delivery",0,'L');

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


  
   
  if($print=='PRINT')
{				//}              
 $pdf->Output('name.pdf','FI'); 
$pdf->close(); 
exit;
}
else
{

  $pdf->Output('uploads/dispatch/D_'.$dispatch_number.'.pdf', 'F');
 
}




/* end */
//$pdf->Output('example_007.pdf', 'FI');
//exit();
//============================================================+
// END OF FILE
//============================================================+

/******************************************************************************************/ 
?>