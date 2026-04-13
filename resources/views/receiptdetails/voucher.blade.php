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

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', '', 12);
class MYPDF extends TCPDF {  

//Page header
    public function Header() {
        $this->SetFont('helvetica', 'B', 15);
        // Title
    }

  /*     // Page footer
        public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-8);
            // Set font
            $this->SetFont('helvetica', '', 8);
            // Page number
          $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');     
        }  */  
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, 'Px', PDF_PAGE_FORMAT, true, 'UTF-8', false);

// add a page
$pdf->AddPage('P', 'A4');


$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));
//margin
$pdf->Line(25, 27, 570, 27, $style);
$pdf->Line(25, 27, 25, 400, $style);
$pdf->Line(570, 27, 570, 400, $style);
$pdf->Line(25, 400, 570, 400, $style);
//Margin End
$pdf->SetXY(250,30);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'(Banker’s Copy)',0,1,'L');

$pdf->SetXY(200,45);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Cheque Deposit Slip/Pay-in-Slip',0,1,'L');

$pdf->Line(200, 60, 390, 60, $style);

$pdf->SetXY(300,70);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Date',0,1,'L');

$date=date('d-m-y');
$pdf->SetXY(420,70);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,': '.$date,0,1,'L');


$pdf->SetXY(300,90);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Company’s telephone no',0,1,'L');

$pdf->SetXY(420,90);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,': '.'044 - 7125 5000 / 6625 5777',0,1,'L');

$pdf->SetXY(35,70);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Bank name',0,1,'L');

$pdf->SetXY(135,70);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(270,10,': '.$com_bank_name,'0','L');

$pdf->SetXY(35,85);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Branch name',0,1,'L');

$pdf->SetXY(135,85);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(280,15,': '.$com_branch_name,'0','L');

$pdf->SetXY(35,100);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Account no.',0,1,'L');

$pdf->SetXY(135,100);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,': '.$com_account_number,0,1,'L');

$pdf->SetXY(35,115);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Account holder name.',0,1,'L');

$pdf->SetXY(135,115);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,': '.$com_name_in_account,0,1,'L');

$pdf->SetXY(40,165);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'S.No',0,1,'L');

$pdf->SetXY(50,190);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'1',0,1,'L');

$pdf->SetXY(70,165);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Received From',0,1,'L');

$pdf->SetXY(75,190);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(70,2,$customer_name,0,'L');

$pdf->SetXY(150,165);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2,'Cheque/ DD No.',0,'L');

$pdf->SetXY(150,190);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
/*if($payment_name!="CHEQUE")
{
$pdf->MultiCell(50,2,$payment_reference,0,'L');}
else
{
$pdf->MultiCell(50,2,$cheque_no,0,'L');    

}*/
$pdf->MultiCell(50,2,$cheque_no,0,'L');

$pdf->SetXY(210,165);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2,'Cheque/ DD Date.',0,'L');

$pdf->SetXY(210,190);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2,$cheque_date,0,'L');

$pdf->SetXY(270,165);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(100,0,'Bank Name',0,1,'C');


$pdf->SetXY(270,190);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2,$bank_name,0,'L');

$pdf->SetXY(380,165);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(80,0,'Branch',0,1,'C');

$pdf->SetXY(375,190);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2,$branch_name,0,'L');

$pdf->SetXY(380,235);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(80,0,'Total',0,1,'R');

$pdf->SetXY(490,165);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(65,0,'Amount (Rs)',0,1,'L');

$pdf->SetXY(465,190);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,10,number_format($payment_amount,2),0,'R');

$pdf->SetXY(465,235);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,10,number_format($payment_amount,2),0,'R');

//body lines
$pdf->Line(37, 160, 560, 160, $style);
$pdf->Line(37, 190, 560, 190, $style);
$pdf->Line(37, 250, 560, 250, $style);
$pdf->Line(37, 230, 560, 230, $style);

$pdf->Line(37, 160, 37, 250, $style);
$pdf->Line(70, 160, 70, 250, $style);
$pdf->Line(150, 160, 150, 250, $style);
$pdf->Line(205, 160, 205, 250, $style);
$pdf->Line(265, 160, 265, 250, $style);
$pdf->Line(370, 160, 370, 250, $style);
$pdf->Line(460, 160, 460, 250, $style);
$pdf->Line(560, 160, 560, 250, $style);

$pdf->SetXY(35,260);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Amount (in words) : ',0,1,'L');

$pdf->SetXY(130,260);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0,'INR '.convert_number_to_words($payment_amount,2),0,'L');

$pdf->SetXY(40,300);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Deposited by',0,1,'L');

$pdf->SetXY(40,350);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Signature',0,1,'L');

$pdf->SetXY(490,300);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Received by',0,1,'L');

$pdf->SetXY(490,350);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Signature',0,1,'L');

//Body lines End
//CENTER LINE
$pdf->Line(15, 415, 590, 415, $style);
//Customer’s Copy
//margin
$pdf->Line(25, 427, 570, 427, $style);
$pdf->Line(25, 427, 25, 800, $style);
$pdf->Line(570, 427, 570, 800, $style);
$pdf->Line(25, 800, 570, 800, $style);
//Margin End

$pdf->SetXY(250,430);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'(Customer’s Copy)',0,1,'L');

$pdf->SetXY(200,445);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Cheque Deposit Slip/Pay-in-Slip',0,1,'L');

$pdf->Line(200, 460, 390, 460, $style);

$pdf->SetXY(300,470);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Date',0,1,'L');

$date=date('d-m-y');
$pdf->SetXY(420,470);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,': '.$date,0,1,'L');


$pdf->SetXY(300,490);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Company’s telephone no',0,1,'L');

$pdf->SetXY(420,490);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,': '.'044 - 7125 5000 / 6625 5777',0,1,'L');

$pdf->SetXY(35,470);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Bank name',0,1,'L');

$pdf->SetXY(135,470);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(270,10,': '.$com_bank_name,'0','L');

$pdf->SetXY(35,485);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Branch name',0,1,'L');

$pdf->SetXY(135,485);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(280,15,': '.$com_branch_name,'0','L');

$pdf->SetXY(35,500);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Account no.',0,1,'L');

$pdf->SetXY(135,500);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,': '.$com_account_number,0,1,'L');

$pdf->SetXY(35,515);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Account holder name.',0,1,'L');

$pdf->SetXY(135,515);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,': '.$com_name_in_account,0,1,'L');

$pdf->SetXY(40,565);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'S.No',0,1,'L');

$pdf->SetXY(50,590);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'1',0,1,'L');

$pdf->SetXY(70,565);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Received From',0,1,'L');

$pdf->SetXY(75,590);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(70,2,$customer_name,0,'L');

$pdf->SetXY(150,565);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2,'Cheque/ DD No.',0,'L');

$pdf->SetXY(150,590);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
/*if($payment_name!="CHEQUE")
{
$pdf->MultiCell(50,2,$payment_reference,0,'L');}
else
{
$pdf->MultiCell(50,2,$cheque_no,0,'L');    

}*/
$pdf->MultiCell(50,2,$cheque_no,0,'L');

$pdf->SetXY(210,565);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2,'Cheque/ DD Date.',0,'L');

$pdf->SetXY(210,590);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(50,2,$cheque_date,0,'L');

$pdf->SetXY(270,565);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(100,0,'Bank Name',0,1,'C');


$pdf->SetXY(270,590);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2,$bank_name,0,'L');

$pdf->SetXY(380,565);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(80,0,'Branch',0,1,'C');

$pdf->SetXY(375,590);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,2,$branch_name,0,'L');

$pdf->SetXY(380,635);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(80,0,'Total',0,1,'R');

$pdf->SetXY(490,565);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(65,0,'Amount (Rs)',0,1,'L');

$pdf->SetXY(465,590);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,10,number_format($payment_amount,2),0,'R');

$pdf->SetXY(465,635);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,10,number_format($payment_amount,2),0,'R');

//body lines
$pdf->Line(37, 560, 560, 560, $style);
$pdf->Line(37, 590, 560, 590, $style);
$pdf->Line(37, 650, 560, 650, $style);
$pdf->Line(37, 630, 560, 630, $style);

$pdf->Line(37, 560, 37, 650, $style);
$pdf->Line(70, 560, 70, 650, $style);
$pdf->Line(150, 560, 150, 650, $style);
$pdf->Line(205, 560, 205, 650, $style);
$pdf->Line(265, 560, 265, 650, $style);
$pdf->Line(370, 560, 370, 650, $style);
$pdf->Line(460, 560, 460, 650, $style);
$pdf->Line(560, 560, 560, 650, $style);

$pdf->SetXY(35,660);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Amount (in words) : ',0,1,'L');

$pdf->SetXY(130,660);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0,'INR '.convert_number_to_words($payment_amount,2),0,'L');

$pdf->SetXY(40,700);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Deposited by',0,1,'L');

$pdf->SetXY(40,750);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Signature',0,1,'L');

$pdf->SetXY(490,700);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Received by',0,1,'L');

$pdf->SetXY(490,750);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,'Signature',0,1,'L');

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
