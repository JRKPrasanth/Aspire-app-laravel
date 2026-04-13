<?php
//dd($ddd);

setlocale(LC_MONETARY, 'en_IN');


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// set default header data

$pdf->SetAutoPageBreak(true, 0);
// set image scale factor
//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  
// add a page
$resolution= array(215, 327);
$pdf->AddPage('P', $resolution);


//Function to convert Amount in words
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







// set font
$pdf->SetFont('times', '', 12);

class MYPDF extends TCPDF { 
    public $style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

 public function setLogo($company_logo,$cus_name,$st_date,$en_date){
        $this->cus_name = $cus_name;
        $this->st_date = $st_date;
        $this->en_date = $en_date;
       
   
    }
//Page header
 
	public function topage($ccnopgp) {
    

}

    // Page footer
    public function fopage($totalpage) {
          $this->totalpage = $totalpage;   
    }    


 public function Header() {
       
       
   $ccnopgp= $this->pageNo() ;  
 if($ccnopgp!=1)
{

 $this->SetXY(85,5);
    $this->SetFont('times','B','10');
    $this->SetTextColor('0','0','0');
 //   $this->Cell(0,0, $this->cus_name ,0,1,'L');
    
       $this->SetXY(85,12);
    $this->SetFont('times','','11');
    $this->SetTextColor('0','0','0');
  //  $this->Cell(0,0, $this->st_date ." to ".$this->en_date ,0,1,'L'); 

    $y=1;
// $this->Line(12, $y+18, 204, $y+18, $style1);

  
//$this->Line(12, $y+23, 204, $y+23, $style1);

     
 $this->SetXY(14,$y+18);
    $this->SetFont('times','','11');
    $this->SetTextColor('0','0','0');
    $this->Cell(18,0, "Date" ,0,1,'C');
    
    $this->SetXY(35,$y+18);
    $this->SetFont('times','','11');
    $this->SetTextColor('0','0','0');
    $this->Cell(45,0, "Name" ,0,1,'L');
    
     $this->SetXY(85,$y+18);
    $this->SetFont('times','','11');
    $this->SetTextColor('0','0','0');
    $this->Cell(40,0, "Particulars" ,0,1,'L');
    
    $this->SetXY(130,$y+18);
    $this->SetFont('times','','11');
    $this->SetTextColor('0','0','0');
    $this->Cell(20,0, "Debit (Rs.)" ,0,1,'C');
    
    $this->SetXY(153,$y+18);
    $this->SetFont('times','','11');
    $this->SetTextColor('0','0','0');
    $this->Cell(20,0, "Credit (Rs.)" ,0,1,'C');
    
    
    $this->SetXY(177,$y+18);
    $this->SetFont('times','','11');
    $this->SetTextColor('0','0','0');
    $this->Cell(25,0, "Balance (Rs.)" ,0,1,'C');

}
}

     public function MultiRow($value,$i,$pdf,$total_amount) { 
            $y = $this->getY()+4;
         
//     $this->Setxy(10,$y);
// $this->SetFont('times','','9');
// $this->SetTextColor('0','0','0');
// $this->cell(0,0,$i,0,1,'L');

if(date('d-m-Y',strtotime($value->journal_date)) == '01-01-1970'){ 
    $this->Setxy(14,$y);
$this->SetFont('times','','9');
$this->SetTextColor('0','0','0');
//$this->cell(18,0,date('d-m-Y',strtotime($start_date)),0,1,'L');
}else{
 $this->Setxy(14,$y);
$this->SetFont('times','','9');
$this->SetTextColor('0','0','0');
$this->cell(18,0,date('d-m-Y',strtotime($value->journal_date)),0,1,'L');   
}

$y = $this->getY()-4;
 
if(date('d-m-Y',strtotime($value->journal_date)) == '01-01-1970'){  
$this->Setxy(130,$y+2);
$this->SetFont('times','','9');
$this->SetTextColor('0','0','0');
$this->cell(20,0,preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($value->debit_amount,2)),0,1,'R');

 $this->Setxy(153,$y+2);
$this->SetFont('times','','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(20,2,preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round( $value->credit_amount,2)),0,'R');

 $this->Setxy(177,$y+2);
$this->SetFont('times','','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(25,2,preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($value->balance,2)),0,'R');
     }else{ 
 $this->Setxy(130,$y);
$this->SetFont('times','','9');
$this->SetTextColor('0','0','0');
$this->cell(20,0,preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($value->debit_amount,2)),0,1,'R');

 $this->Setxy(153,$y);
$this->SetFont('times','','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(20,2,preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round( $value->credit_amount,2)),0,'R');

 $this->Setxy(177,$y);
$this->SetFont('times','','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(25,2,preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($value->balance,2)),0,'R');
}


if(date('d-m-Y',strtotime($value->journal_date)) == '01-01-1970'){ 
$this->Setxy(35,$y+2);
$this->SetFont('times','','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(45,2,'OPENING BALANCE',0,'L');
}else{
$this->Setxy(35,$y);
$this->SetFont('times','','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(45,2,$value->journal_name,0,'L');    
}

$xxy = $this->getY();

    $this->Setxy(85,$y);
$this->SetFont('times','','9');
$this->SetTextColor('0','0','0');
//$this->MultiCell(40,0,$value->name,0,'L');
$xyy = $this->getY();
if($xyy<$xxy)
{
        $y =$xxy;
}else{
        $y = $xyy;
}
        
            $this->SetY($y);
}
 public function Footer() {
         // Position at 15 mm from bottom
         
         $this->setXY(70,-16);
         $this->SetFont('times','','10');
$this->SetTextColor('0','0','0');
// $this->MultiCell(80,2, "computer generated document since no stamp required" ,0,'L');

        $this->SetY(-11);
        // Set font
        $this->SetFont('times', 'B', 8);
        // Page number
      $this->Cell(0, 10, 'Page No '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');  
      $this->SetY(-19);
      $this->SetX(-155);  


   
    }
    }
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$pdf-> setLogo($company_logo,$customer_dat[0]->customer_name,date('d-m-Y',strtotime($start_date)),date('d-m-Y',strtotime($end_date)));

$totalpage = $pdf->getNumPages();
//dd($totalpage);
$pdf->fopage($totalpage);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


if($pdf->pageNo()!=1)
{

    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+5, PDF_MARGIN_RIGHT);

}
else
{
   $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+90, PDF_MARGIN_RIGHT);
 
}

// set margins
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

 
 $pdf->SetFont('times', '', 12);

// add a page
$pdf->AddPage('P', $resolution);
 // Logo
        $image_file = public_path().'/images/logo.png';
        // $this->Image($image_file, 17, 8, 30, 20, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
        // Set font
        $pdf->SetFont('Times', 'B', 14);
  



//$this->Line(12, 290, 208, 290, $this->style);

$style1 = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$pdf->Line(80, 64, 130, 64, $style1);

    $pdf->SetXY(14,15);
    $pdf->SetFont('times','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "To  :" ,0,1,'L');

    $pdf->SetXY(22,15);
    $pdf->SetFont('times','B','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, $customer_dat[0]->customer_name ,0,1,'L');
    //dd($customer_name);
    $y=$pdf->getY();
  $pdf->SetXY(22,$y);
    $pdf->SetFont('times','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(60,0, $customer_dat[0]->customer_site_name.", ".$customer_dat[0]->address.", ".$customer_dat[0]->city_name.", ".$customer_dat[0]->state_name .", ".$customer_dat[0]->country_name."- ".$customer_dat[0]->pincode ,0,'L');
 
//   $y=$pdf->getY();
//  $pdf->SetXY(22,$y);
//     $pdf->SetFont('times','','10');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->Cell(0,0, "Kundrathur" ,0,1,'L');

//   $y=$pdf->getY();
//  $pdf->SetXY(22,$y);
//     $pdf->SetFont('times','','10');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->Cell(0,0, "Chennai-600 069" ,0,1,'L');

//   $y=$pdf->getY();
//  $pdf->SetXY(22,$y);
//     $pdf->SetFont('times','','10');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->Cell(0,0, "Phone-24780353,24780466" ,0,1,'L');
    
    $pdf->SetXY(110,15);
    $pdf->SetFont('times','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "From:" ,0,1,'L');
   $pdf->SetXY(120,15);
    $pdf->SetFont('times','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Dr.JRK’s Research and Pharmaceuticals Private Limited" ,0,1,'L');
    

   $y=$pdf->getY();
 $pdf->SetXY(120,$y);
    $pdf->SetFont('times','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "(Dr.JRK’s Siddha Research and Pharmaceuticals Pvt Ltd.)" ,0,1,'L');
    
    
  $y=$pdf->getY();
 $pdf->SetXY(120,$y);
    $pdf->SetFont('times','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Regd Off: 11, Perumal Koil Street" ,0,1,'L');

 $y=$pdf->getY();
 $pdf->SetXY(120,$y);
    $pdf->SetFont('times','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Mfg Unit: 18 & 19, Perumal Koil Street" ,0,1,'L');
    
    
   $y=$pdf->getY();
 $pdf->SetXY(120,$y);
    $pdf->SetFont('times','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Kunrathur, Chennai - 600069, India" ,0,1,'L');  
    
    
    $y=$pdf->getY();
 $pdf->SetXY(120,$y);
    $pdf->SetFont('times','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "CIN: U24231TN1990PTC018741" ,0,1,'L'); 
    
    $pdf->SetXY(14,$y+16);
    $pdf->SetFont('times','','11');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Dear Sir/Madam," ,0,1,'L'); 
    
    
     $pdf->SetXY(180,$y+16);
    $pdf->SetFont('times','','11');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Date : ".date("d-m-Y") ,0,1,'L');
    
     $pdf->SetXY(80,$y+20);
    $pdf->SetFont('times','','11');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Sub: Confirmation of Accounts" ,0,1,'L'); 
    
    
     $pdf->SetXY(82,$y+24);
    $pdf->SetFont('times','','11');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, date('d-m-Y',strtotime($start_date))." to ".date('d-m-Y',strtotime($end_date)) ,0,1,'L'); 
    
    $pdf->SetXY(14,$y+35);
    $pdf->SetFont('times','','11');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Given below is the details of your Accounts as standing in my/our Books of Accounts for the above mentioned
period.," ,0,1,'L'); 


 $pdf->SetXY(14,$y+46);
    $pdf->SetFont('times','','11');
    $pdf->SetTextColor('0','0','0');
  //  $pdf->Cell(0,0, "Kindly return 3 copies stating your I.T. Permanent A/c No., duly signed and sealed, in confirmation of the same." ,0,1,'L'); 
    
    $pdf->SetXY(14,$y+52);
    $pdf->SetFont('times','','11');
    $pdf->SetTextColor('0','0','0');
  //  $pdf->Cell(0,0, "Please note that if no reply is received from you within a fortnight, it will be assumed that you have accepted the" ,0,1,'L'); 

 $pdf->SetXY(14,$y+58);
    $pdf->SetFont('times','','11');
    $pdf->SetTextColor('0','0','0');
  //  $pdf->Cell(0,0, "balance shown below." ,0,1,'L');


   
$pdf->Line(12, 117, 204, 117, $style1);

  
$pdf->Line(12, 111, 204, 111, $style1);

//$this->Line(80, 64, 130, 64, $this->style);

$y=93;
      
 $pdf->SetXY(14,$y+18);
    $pdf->SetFont('times','','11');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(18,0, "Date" ,0,1,'C');
    
    $pdf->SetXY(35,$y+18);
    $pdf->SetFont('times','','11');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(45,0, "Name" ,0,1,'L');
    
     $pdf->SetXY(85,$y+18);
    $pdf->SetFont('times','','11');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(40,0, "Particulars" ,0,1,'L');
    
    $pdf->SetXY(130,$y+18);
    $pdf->SetFont('times','','11');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(20,0, "Debit (Rs.)" ,0,1,'C');
    
    $pdf->SetXY(153,$y+18);
    $pdf->SetFont('times','','11');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(20,0, "Credit (Rs.)" ,0,1,'C');
    
    
    $pdf->SetXY(177,$y+18);
    $pdf->SetFont('times','','11');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(25,0, "Balance (Rs.)" ,0,1,'C');

$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

    

$i=0;
$oldY=0;
$debit_amount=0;
$credit_amount=0;
$total_amount=0;
$convert=0;
$tax_amount=0;

foreach($results as $value) 
{   
   
  $i++;

      $debit_amount=$debit_amount+$value->debit_amount;
      $credit_amount=$credit_amount+$value->credit_amount;
      

     $pdf->MultiRow($value,$i,$pdf,$total_amount); 
 }

$y=$pdf->getY()+4;
 $pdf->Setxy(130,$y);
$pdf->SetFont('times','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->cell(20,0,preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($debit_amount,2)),0,1,'R');

 $pdf->Setxy(153,$y);
$pdf->SetFont('times','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2,preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($credit_amount,2)),0,'R');

 $pdf->Setxy(177,$y);
$pdf->SetFont('times','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2,preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($debit_amount-$credit_amount,2)),0,'R');



$xxy = $pdf->getY();

    $pdf->Setxy(85,$y);
$pdf->SetFont('times','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(40,0,'Total',0,'L');

 


   



$total = $pdf->getNumPages();

//dd($total);
if($pdf->pageNo()!=1){
$ccnopgp=$pdf->pageNo();
   $pdf->topage($ccnopgp);
}
if($pdf->pageNo()==$total)
{

    $y = $pdf->getY();
 //dd($y);
    if($y > 260)
    //$y = $pdf->getY()-150;
        $pdf->AddPage('P', $resolution);
    $totalpage = $pdf->getNumPages();
   
    $pdf->fopage($totalpage);
//$pdf->Rect(12, 250, 196, 80,'DF', "",  array(255, 255, 255));

//$pdf->Line(12, 38, 208, 38, $pdf->style);

   $pdf->SetDrawColor(255, 255,255);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0, 0, 0);
$pdf->Rect(12, 290, 196, 20, 'DF');
 
$pdf->Line(12, 295, 200, 295, $pdf->style);


$pdf->SetXY(13,296);
$pdf->SetFont('times','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "I/We hereby confirm the above" ,0,1,'L');

$pdf->SetXY(160,296);
$pdf->SetFont('times','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Yours faithfully," ,0,1,'L');

$pdf->lastPage();

}



// ---------------------------------------------------------

//Close and output PDF document

ob_end_clean();
  
			
//	  ob_end_clean();
	  if(isset($customer_dat[0]->customer_name)){
 $pdf->Output($customer_dat[0]->customer_name.'.pdf','I'); 
	  }else{
	   $pdf->Output('No_customer_name.pdf','I');    
	  }
	exit;
	

	  $pdf->close(); 
//============================================================+
// END OF FILE
//============================================================+

/******************************************************************************************/ 
?>
