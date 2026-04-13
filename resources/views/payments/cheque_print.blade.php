

<?php

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'Px', PDF_PAGE_FORMAT, true, 'UTF-8', false);


$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');



$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);


//Maruthu Function to convert Amount in words
function convert_number_to_words($number)
{
$no = round($number);
$point = round($number - $no, 2) * 100;
$hundred = null;
$digits_1 = strlen($no);
$i = 0;
$str = array();
$words = array('0' => '', '1' => 'One', '2' => 'Two',
'3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
'7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
'10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
'13' => 'Thirteen', '14' => 'Fourteen',
'15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
'18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
'30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
'60' => 'Sixty', '70' => 'Seventy',
'80' => 'Eighty', '90' => 'Ninety');
$digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
while ($i < $digits_1)
{
$divider = ($i == 2) ? 10 : 100;
$number = floor($no % $divider);
$no = floor($no / $divider);
$i += ($divider == 10) ? 1 : 2;
if ($number) {
$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
$str [] = ($number < 21) ? $words[$number] .
" " . $digits[$counter] . $plural . " " . $hundred
:
$words[floor($number / 10) * 10]
. " " . $words[$number % 10] . " "
. $digits[$counter] . $plural . " " . $hundred;
} else $str[] = null;
}
$str = array_reverse($str);
$result = implode('', $str);
$points = ($point) ?
"." . $words[$point / 10] . " " .
$words[$point = $point % 10] : '';
return $result . "Rupees Only";
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
        
		$this->image(public_path().'/images/cheque.jpg',20,1,500,250);
     
        $this->SetFont('helvetica', 'B', 15);
     
       
    }

     
        public function Footer() {
            
            $this->SetY(-8);
           
            $this->SetFont('helvetica', '', 8);
           
          $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');     
        }    
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, 'Px', PDF_PAGE_FORMAT, true, 'UTF-8', false);






$pdf->AddPage('P', 'A4');


$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$date=date('d');
$date1=str_split($date);
$month=date('m');
$month1=str_split($month);
$year=date('Y');
$year1=str_split($year);
//end


$pdf->SetXY(65,53);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(320,10, $name_in_account ,0,'L');

$text=wordwrap('                   One Crore Twenty Three Lakh Fourty Five Thousand Six Hundred And Seventy Eight Only', 400, "\n\n");

$pdf->SetXY(40,77);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(460,3,'                   One Crore Twenty Three Lakh Fourty Five Thousand Six Hundred And Seventy Eight Only' ,0,'T', false,'T');
$pdf->MultiCell(460,10,"                   ".convert_number_to_words(round($batch_total_amount)) ,0,'L');

$pdf->SetXY(410,95);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,number_format($batch_total_amount,2,'.',''),0,1,'L');

$pdf->SetXY(80,125);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,$account_number,0,1,'L');


$pdf->SetXY(400,140);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'FOR JRKs',0,1,'L');

$pdf->SetXY(400,180);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'PROPRIETOR',0,1,'L');

$pdf->SetFont('helvetica', '', 11);
$pdf->SetTitle('CHEQUE');
$count=1;
$oldY=0;

 $pdf->SetXY(400,15);
$pdf->SetFont('','','12');
$pdf->SetFontSpacing(6);
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,$payment_date,0,1,'L');



$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
ob_end_clean();
    
$pdf->Output('example_007.pdf', 'FI');
exit();

?>
