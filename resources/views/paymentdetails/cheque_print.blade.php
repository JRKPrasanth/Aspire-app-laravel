<?php

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'Px',PDF_PAGE_FORMAT,true, 'UTF-8', false);


$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');



$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);


//Function to convert Amount in words
function convert_number_to_words($number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $aa= str_split($decimal,2);
    $decimal=$aa[0];
  //  dd($decimal);
    //dd($aa);
    $hundred = null;
    $hundred1 = null;
    $digits_length = strlen($no);
    $digits_length1 = strlen($decimal);
    $i = 0;
    $i1 = 0;
    $str = array();
    $strdecimal = array();
    $words = array(0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $digits = array('', 'Hundred','Thousand','Lakh', 'Crore','Million');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' And ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    while( $i1 < $digits_length1 ) {
        $divider = ($i1 == 2) ? 10 : 100;
        $decimal = floor($decimal % $divider);
       // dd($decimal);
        $i1 += $divider == 10 ? 1 : 2;

        if ($decimal) {
            //dd($decimal);
            $plural = (($counter = count($strdecimal)) && $decimal > 9) ? 's' : null;
            $hundred1 = ($counter == 1 && $strdecimal[0]) ? ' And ' : null;
            $strdecimal [] = ($decimal < 21) ? $words[$decimal].' '. $digits[$counter]. $plural.' '.$hundred1:$words[floor($decimal / 10) * 10].' '.$words[$decimal % 10]. ' '.$digits[$counter].$plural.' '.$hundred1;
        } 
        else $strdecimal[] = null;
    }
    //dd($strdecimal);
    $Rupees = implode('', array_reverse($str));
    $paise = implode('', array_reverse($strdecimal));
    if($paise!=''){
        $paises=$paise."Paise";
    }else{
        $paises='';
    }
    //$paise = ($decimal) ? " " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise ' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paises." Only" ;
   

   // return ($Rupees ? $Rupees : '');
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

//Page header
    public function Header() {
        
		//$this->image(public_path().'/images/cheque.jpg',20,1,500,250);
     
        $this->SetFont('helvetica', 'B', 15);
     
       
    }

     
        public function Footer() {
            
            $this->SetY(-8);
           
            $this->SetFont('helvetica', '', 8);
           
          $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');     
        }    
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, 'Px', PDF_PAGE_FORMAT, true, 'UTF-8', false);





	
$pdf->AddPage('P', array('format' => 'A4'));



$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$date=date('d');
$date1=str_split($date);
$month=date('m');
$month1=str_split($month);
$year=date('Y');
$year1=str_split($year);
//end


$pdf->StartTransform();
// Rotate 20 degrees counter-clockwise centered by (70,110) which is the lower left corner of the rectangle
$pdf->Rotate(90, 346, 226);
$pdf->Text(20, 96, $name_in_account);
// Stop Transformation
$pdf->StopTransform();



$pdf->StartTransform();
// Rotate 20 degrees counter-clockwise centered by (70,110) which is the lower left corner of the rectangle
$pdf->Rotate(90, 370, 228);

//$pdf->MultiCell(70, 2,convert_number_to_words(round($payment_amount)).'dddfdgdfgdfgdhyugdughdfjkgkdfjgdfjkgjkgfggffggfdyugnfdfdfddfdfudynuygrncuygiugndifgidrsgydg', 0, 1,'L');
$pdf->Text(70, 96,convert_number_to_words($payment_amount));
// Stop Transformation
$pdf->StopTransform();


$pdf->StartTransform();
// Rotate 20 degrees counter-clockwise centered by (70,110) which is the lower left corner of the rectangle
$pdf->Rotate(90, 185, 18);
$pdf->Text(70, 96,number_format($payment_amount,2,'.',''));

$pdf->StopTransform();







$pdf->StartTransform();
$pdf->SetFont('','','14');
// Rotate 20 degrees counter-clockwise centered by (70,110) which is the lower left corner of the rectangle
$pdf->Rotate(90, 145, 67);
$pdf->Text(70, 96,implode('  ',str_split($payment_date)));
$pdf->StopTransform();

//dd($ac_payee_status);
if($ac_payee_status!="No")
{
    $pdf->Line(173, 160, 173, 220, $style);
    $pdf->Line(193, 160, 193, 220, $style);

$pdf->StartTransform();

$pdf->SetFont('','','10');
// Rotate 20 degrees counter-clockwise centered by (70,110) which is the lower left corner of the rectangle
$pdf->Rotate(90, 186, 105);
$pdf->Text(70, 96,'A/C PAYEE');
$pdf->StopTransform();

}

$pdf->SetFont('helvetica', '', 11);
$pdf->SetTitle('CHEQUE');
$count=1;
$oldY=0;

 $pdf->SetXY(398,13);
$pdf->SetFont('','','12');
$pdf->SetFontSpacing(6);
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0,$payment_date,0,1,'L');


// ---------------------------------------------------------

//Close and output PDF document
ob_end_clean();
    
$pdf->Output('example_007.pdf', 'FI');
exit();

?>
