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
    // dd($number);
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
    $paise = ($decimal) ? " And " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    // dd($paise);
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise ;
  //  return ($Rupees ? $Rupees . 'Rupees ' : '');
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
            $Total = $this->getNumPages();
          $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');     
        }    
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setLogo($company_logo_name);
  

// add a page
$pdf->AddPage('P', $resolution);

$pdf->SetHeaderData('', '', 'Credit Note', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
 
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
//dd($source_type);
if($source_type!="DEBIT")
{
$pdf->SetXY(85,10);
$pdf->SetFont('','B','15');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CREDIT NOTE" ,0,1,'L');
}
else
{
$pdf->SetXY(85,10);
$pdf->SetFont('','B','15');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "DEBIT NOTE" ,0,1,'L');
}

$pdf->SetXY(12,23);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(100,2, $company_name,0,'L');

$pdf->SetXY(12,27);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->multiCell(100,2, $address,0,'L');

$pdf->SetXY(12,35);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "CIN                     :" ,0,1,'L');

$pdf->SetXY(12,47);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "E-MAIL               :" ,0,1,'L');

$pdf->SetXY(38,47);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->multiCell(100,2, $email_id,0,'L');

$pdf->SetXY(12,39);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN         : " ,0,1,'L');

$pdf->SetXY(12,43);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "STATE NAME    : " ,0,1,'L');

$pdf->SetXY(80,43);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "STATE CODE   : " ,0,1,'L');


$pdf->SetXY(38,43);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->multiCell(100,2, $stcode,0,'L');

$pdf->SetXY(103,43);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->multiCell(100,2, $statecode,0,'L');

$pdf->SetXY(38,39);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->multiCell(100,2, $gst_no,0,'L');

$pdf->SetXY(38,35);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->multiCell(100,2, $cin,0,'L');

if($source_type!="DEBIT")
{
$pdf->SetXY(113,23);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Credit Note No:" ,0,1,'L');

$pdf->SetXY(113,33);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Buyer/seller Debit Note No" ,0,1,'L');

}
else{
$pdf->SetXY(113,23);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Debit Note No:" ,0,1,'L');

$pdf->SetXY(113,33);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Buyer/seller Credit Note No:" ,0,1,'L');

}
if($buyer_refno!=""){
$pdf->SetXY(126,37);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $buyer_refno,0,1,'L');

$pdf->SetXY(165,37);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $buyer_refdate,0,1,'L');
}
$pdf->SetXY(160,33);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Date" ,0,1,'L');

$pdf->SetXY(126,27);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $debitcredit_no,0,1,'L');

$pdf->SetXY(160,23);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Date" ,0,1,'L');

$pdf->SetXY(165,27);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $debitcredit_date,0,1,'L');

$pdf->SetXY(113,44);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Buyer/seller Ref." ,0,1,'L');

$pdf->SetXY(120,48);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $reference_no,0,1,'L');


$pdf->SetXY(160,44);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Other Reference(s)" ,0,1,'L');

$pdf->SetXY(113,55);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Buyer/seller Invoice No." ,0,1,'L');

$pdf->SetXY(160,55);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Date" ,0,1,'L');

$pdf->SetXY(113,66);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Despatch Document No." ,0,1,'L');

$pdf->SetXY(113,77);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Despatched through" ,0,1,'L');

$pdf->SetXY(160,77);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Destination" ,0,1,'L');

$pdf->SetXY(113,88);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Terms of Delivery" ,0,1,'L');


$pdf->SetXY(13,55);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Party :" ,0,1,'L');

$pdf->SetXY(16,58);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $supplier_name,0,1,'L');

$pdf->SetXY(16,62);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,1, $addres."-".$pincode,0,'L');

$pdf->SetXY(13,73);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Ph No                 :" ,0,1,'L');

$pdf->SetXY(36,73);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(150,1, $contact_number,0,'L');

$pdf->SetXY(13,78);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN/UIN         : ". $gst_number ,0,1,'L');

$pdf->SetXY(13,84);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "STATE NAME    : ".$state1code ,0,1,'L');

$pdf->SetXY(80,84);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "STATE CODE   : ".$statenocode,0,1,'L');


$pdf->SetXY(13,110);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "S.No " ,0,1,'L');

$pdf->SetXY(46,110);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Particulars" ,0,1,'L');


$pdf->SetXY(107,110);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "HSN/SAC" ,0,1,'L');

$pdf->SetXY(126,110);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Quantity" ,0,1,'L');


$pdf->SetXY(145,110);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Rate" ,0,1,'L');

$pdf->SetXY(162,110);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Per" ,0,1,'L');

$pdf->SetXY(169,110);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Disc. %" ,0,1,'L');

$pdf->SetXY(185,110);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Amount" ,0,1,'L');


     $pdf->SetXY(80,195);
    $pdf->SetFont('','','8');
    $pdf->SetTextColor('0','0','0');
  // $pdf->MultiCell(19,10, convert_number_to_words(($roundoff,2)),0,'R');   

$pdf->SetXY(65,210);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "HSN/SAC" ,0,1,'L');

$pdf->SetXY(120,210);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Taxable value" ,0,1,'L');
//dd($linesdata);
$yyy=220;
$oldYY=0;
$dbamount=0;
$tottaxamount=0;
foreach ($linesdata as $ky => $vale) {
       if($oldYY==0) $yyy =220;
    else $yyy = $oldYY+2;  
   

    $pdf->SetXY(25,$yyy);
    $pdf->SetFont('','','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, $vale->classification_code,0,1,'L');

    $pdf->SetXY(115,$yyy);
    $pdf->SetFont('','','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, $vale->debitcredit_line_amount,0,1,'L');
$dbamount+=$vale->debitcredit_line_amount;
    $pdf->SetXY(150,$yyy);
    $pdf->SetFont('','','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(25,1, $vale->tax_group_name,0,'L');
    
    $pdf->SetXY(165,$yyy);
    $pdf->SetFont('','','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(15,0, $vale->tax_amount,0,1,'R');  

     $pdf->SetXY(185,$yyy);
    $pdf->SetFont('','','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, $vale->tax_amount,0,1,'L');

    $tottaxamount+=$vale->tax_amount;

    $pdf->Line(12, $yyy+5, 208, $yyy+5, $style);
$oldYY=$pdf->getY();
// $pdf->Line(12, 230, 208, 230, $style);

    // $pdf->SetXY(185,225);
    // $pdf->SetFont('','','8');
    // $pdf->SetTextColor('0','0','0');
    // $pdf->Cell(0,0, $tax_amount,0,1,'L');
    
    // $pdf->SetXY(165,225);
    // $pdf->SetFont('','','8');
    // $pdf->SetTextColor('0','0','0');
    // $pdf->Cell(15,0, $tax_amount,0,1,'R');

    //  $pdf->SetXY(115,225);
    // $pdf->SetFont('','','8');
    // $pdf->SetTextColor('0','0','0');
    // $pdf->Cell(0,0, $debitcredit_line_amount,0,1,'L');
}



$pdf->SetXY(100,$oldYY+3);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Total" ,0,1,'L');

    $pdf->SetXY(115,$yyy+6);
    $pdf->SetFont('','','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, $dbamount,0,1,'L');


    $pdf->SetXY(185,$yyy+6);
    $pdf->SetFont('','','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, $tottaxamount,0,1,'L');

    $pdf->Line(12, $yyy+10, 208, $yyy+10, $style);

    //hsn code line//
$pdf->Line(113, 208, 113, $yyy+10, $style);
$pdf->Line(150, 208, 150, $yyy+10, $style);
$pdf->Line(180, 208, 180, $yyy+10, $style);

$pdf->SetXY(155,210);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Integrated Tax" ,0,1,'L');

$pdf->Line(166, 215 , 166, 230, $style);
$pdf->Line(150, 215, 180, 215, $style);


$pdf->SetXY(150,215);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Rate" ,0,1,'L');
 

  //  dd($tax_group_name);

$pdf->SetXY(168,215);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Amount" ,0,1,'L');

    $pdf->SetXY(180,210);
    $pdf->SetFont('','B','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Total Tax Amount" ,0,1,'L');

$pdf->SetXY(12,265);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Company’s Service Tax No. :" .$tax_reg_no,0,1,'L');

$pdf->SetXY(12,270);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Company’s PAN                    :".$pan_no ,0,1,'L');



$pdf->SetXY(160,283);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Authorised Signatory  " ,0,1,'L');



$i=0;
$oldY=0;
$dis_amt=0;
$disamt=0;
$gst_tot=0;
$discount=0;
$tax_amt=0;
$gsttotal=0;
$grandtotal=0;

$count=1; 
$row_count = 61;
$debitcredit_line_amount1=0;
//$value=$linesdata[0];
// for($j=0;$j<31;$j++) {
   // dd($linesdata);
foreach($linesdata as $key=>$value) { 
    if($oldY==0) $y =118;
    else $y = $oldY+2;  
    $i++;

    $pdf->SetXY(13,$y+1);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, $i ,0,1,'L');
    $y=$y+8;
    
    $pdf->SetXY(28,$y-7);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(77,0, $value->description  .' @ '. $value->tax_group_name,0,'L');
    
    $oldY=$pdf->getY();
    
    $pdf->SetXY(110,$y-7);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, $value->classification_code,0,1,'L');
//dd($debitcredit_line_amount);
    $pdf->SetXY(183,$y-7);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, number_format( $value->debitcredit_line_amount,'2','.',''),0,1,'L');
$debitcredit_line_amount1+=$value->debitcredit_line_amount;
}
    //  $pdf->SetXY(185,$y-1);
    // $pdf->SetFont('','','8');
    // $pdf->SetTextColor('0','0','0');
    // $pdf->Cell(0,0, $value->tax_amount,0,1,'L');
//dd($value->tax_amount);
$grandtotal = $debitcredit_line_amount1+$taxamount;
//dd($grandtotal);
$rr=(number_format($grandtotal-round($grandtotal),'2','.',''));
//$taxamount
  $grandtotal1 = $debitcredit_line_amount1-$rr+$taxamount;
//dd($tax_amount);  
// if($tax_amount=="GST")
// {
//     $ka=$tax_amount/2;

// }
// else{
// 	$ka=$tax_amount;
// }

// $pdf->Line(181, 127, 208, 127, $style);
//  $pdf->Line(181, 140, 208, 140, $style);
//   $pdf->Line(181, 150, 208, 150, $style);

  $y=$pdf->getY();
$words = preg_replace('/[^a-zA-Z]/', '', $tax_group_name);
//dd($words);
if($words=="GST")
{
    $ka=$taxamount/2;

}
else{
	$ka=$taxamount;
}
if($words == "GST"){
// dd("sada");
    $pdf->Line(181, $y+1, 208, $y+1, $style);
    $pdf->Line(181, $y+7, 208, $y+7, $style);
    $pdf->Line(181, $y+14, 208, $y+14, $style);
    $pdf->SetXY(80,$y+3);
    $pdf->SetFont('','B','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(150,2, "SGST",0,'L');

  $pdf->SetXY(185,$y+3);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    //$pdf->Cell(0,0,$ka ,0,1,'L');
    $pdf->MultiCell(150,2, number_format( $ka,'2','.',''),0,'L'); 


    $pdf->SetXY(80,$y+9);
    $pdf->SetFont('','B','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(150,2, "CGST",0,'L');

   $pdf->SetXY(185,$y+9);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(150,2, number_format( $ka,'2','.',''),0,'L'); 
}
else{
    $pdf->SetXY(80,$y+7);
    $pdf->SetFont('','B','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(150,2, "IGST",0,'L');

    $pdf->SetXY(185,$y+6);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(150,2, number_format( $ka,'2','.',''),0,'L'); 
}

$pdf->SetXY(92,$y+55);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Total" ,0,1,'L');

     $pdf->SetXY(177,$y+55);
    $pdf->SetFont('','','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(19,10, number_format($grandtotal1,2),0,'R');
	

$pdf->SetXY(74,$y+17);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Round Off" ,0,1,'L');

$rount=number_format($grandtotal-round($grandtotal),'2','.','');
if($grandtotal>$grandtotal1){
$rount=$rount;
}else{
    //dd($rount);
   $rount=(-1)*($rount);
   $rount='+ '.$rount;
}
   $pdf->SetXY(187,$y+17);
    $pdf->SetFont('','','8');
    $pdf->SetTextColor('0','0','0');
	$pdf->Cell(0,0,$rount,0,1,'L');


//}



$pdf->SetXY(13,195);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Amount Chargeable (in words) :",0,1,'L');
// dd($roundoff);

$pdf->SetXY(30,200);
    $pdf->SetFont('','','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0,convert_number_to_words( $grandtotal),0,1,'L');


$pdf->SetXY(12,240);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Tax Amount (in words) :",0,1,'L');

$pdf->SetXY(30,245);
    $pdf->SetFont('','','8');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0,convert_number_to_words($tax_amount),0,1,'L');


$pdf->Line(12, 22, 208, 22, $style);
$pdf->Line(113, 33, 208, 33, $style);
$pdf->Line(113, 22, 113, 108, $style);
$pdf->Line(113, 44, 208, 44, $style);
$pdf->Line(113, 55, 208, 55, $style);
$pdf->Line(113, 66, 208, 66, $style);
$pdf->Line(113, 77, 208, 77, $style);
$pdf->Line(113, 88, 208, 88, $style);

//$pdf->Line(12, 125, 208, 125, $style);


//$pdf->Line(113, 44, 208, 44, $style);
$pdf->Line(160, 22, 160, 88, $style);

//address after line//
$pdf->Line(12, 55, 113, 55, $style);


//$pdf->Line(25, 93, 25, 193, $style);
//$pdf->Line(75, 108, 75, 193, $style);
$pdf->Line(26, 108, 26, 193, $style);
$pdf->Line(105, 108, 105, 193, $style);
$pdf->Line(125, 108, 125, 193, $style);
$pdf->Line(142, 108, 142, 193, $style);
$pdf->Line(159, 108, 159, 193, $style);
$pdf->Line(169, 108, 169, 193, $style);
$pdf->Line(181, 108, 181, 193, $style);

$pdf->Line(12, 108, 208, 108, $style);
$pdf->Line(12, 117, 208, 117, $style);

    
 $pdf->Line(12, 183, 208, 183, $style);
$pdf->Line(12, 193, 208, 193, $style);

//tax line//
$pdf->Line(12, 220, 208, 220, $style);

$pdf->Line(12, 208, 208, 208, $style);


$pdf->Line(110, 268, 110, 290, $style);
$pdf->Line(110, 268, 208, 268, $style);

//dd($Total = $pdf->getNumPages());
/************************************Body Lines Content End******************************/

/************************************ Footer ******************************/


/************************************ Footer End ******************************/
ob_end_clean();
  
  
    // dd($print);
  if($print=='PRINT')
{               //}              
 $pdf->Output('name.pdf','FI'); 
$pdf->close(); 
exit;
}
else
{

  $pdf->Output('name.pdf','FI'); 
 
}




/* end */
//$pdf->Output('example_007.pdf', 'FI');
//exit();
//============================================================+
// END OF FILE
//============================================================+

/******************************************************************************************/ 
?>