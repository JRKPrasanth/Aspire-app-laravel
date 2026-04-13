<?php
//dd($ddd);
// dd($results);
error_reporting(0);
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

 public function setLogo($cus_name,$st_date,$en_date){
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
   $add=$this->getY();
//   dd($y);
 if($ccnopgp!=1)
{
// $this->addpage();
//  $this->SetXY(85,5);
//     $this->SetFont('times','B','10');
//     $this->SetTextColor('0','0','0');
//     $this->Cell(0,0, $this->cus_name ,0,1,'L');
    
//       $this->SetXY(85,12);
//     $this->SetFont('times','','11');
//     $this->SetTextColor('0','0','0');
//     $this->Cell(0,0, $this->st_date ." to ".$this->en_date ,0,1,'L'); 

    $y=10;
 $this->Line(12, $y+7, 204, $y+7, $style1);

  
$this->Line(12, $y+17, 204, $y+17, $style1);



$this->SetXY(14,$y+10);
    $this->SetFont('times','B','10');
    $this->SetTextColor('0','0','0');
    $this->Cell(18,0, "Date" ,0,1,'C');
    
    $this->SetXY(35,$y+10);
    $this->SetFont('times','B','10');
    $this->SetTextColor('0','0','0');
    $this->Cell(45,0, "Particulars" ,0,1,'L');
    
     $this->SetXY(85,$y+10);
    $this->SetFont('times','B','10');
    $this->SetTextColor('0','0','0');
    $this->Cell(40,0, "Vch Type" ,0,1,'L');
    
    $this->SetXY(110,$y+10);
    $this->SetFont('times','B','10');
    $this->SetTextColor('0','0','0');
    $this->Cell(20,0, "Vch No" ,0,1,'C');
    
    $this->SetXY(135,$y+10);
    $this->SetFont('times','B','10');
    $this->SetTextColor('0','0','0');
    $this->Cell(20,0, "Debit (Rs.)" ,0,1,'C');
    
    
    $this->SetXY(155,$y+10);
    $this->SetFont('times','B','10');
    $this->SetTextColor('0','0','0');
    $this->Cell(25,0, "Credit (Rs.)" ,0,1,'C');
    
    $this->SetXY(177,$y+10);
    $this->SetFont('times','B','10');
    $this->SetTextColor('0','0','0');
    $this->Cell(25,0, "Balance (Rs.)" ,0,1,'C');
    
    
                    $this->Line(12, $y+7, 12, 295, $style1);
                    $this->Line(33, $y+7, 33,295, $style1);
                    $this->Line(79, $y+7, 79, 295, $style1);
                    $this->Line(106, $y+7, 106, 295, $style1);
                    $this->Line(133, $y+7, 133, 295, $style1);
                    $this->Line(155 ,$y+7, 155, 295, $style1);
                    $this->Line(178, $y+7, 178, 295, $style1);
                    $this->Line(204, $y+7, 204,295, $style1);
    $this->Line(12, 295, 204,295, $style1);

}
}

    public function MultiRow($value,$i,$y,$pdf,$total_amount,$neww,$count,$key) { 
        
        
        
        // dd($i);
        
        // $y = $this->getY()+4;
        //     $this->Setxy(10,$y);
        // $this->SetFont('times','','9');
        // $this->SetTextColor('0','0','0');
        // $this->cell(0,0,$i,0,1,'L');
        $jdate=date('d-m-Y',strtotime($value->journal_date));
        $hei=$this->getY();
        
        
        $b = "B";
        if($jdate!='01-01-1970'){
            $b = '';
            $this->Setxy(14,$y);
            $this->SetFont('times',$b,'9');
            $this->SetTextColor('0','0','0');
            $this->cell(18,0,$jdate,0,1,'L');
            
            $y = $this->getY()-4;
        }
        
        
            
        // $y=$y+5;
        
            if($value->debit_amounts!=0){
            $this->Setxy(134,$y);
            $this->SetFont('times',$b,'9');
            $this->SetTextColor('0','0','0');
            $this->cell(20,0,money_format('%!i', $value->debit_amounts),0,1,'R');
            }
            if($value->credit_amounts!=0){
            $this->Setxy(155,$y);
            $this->SetFont('times',$b,'9');
            $this->SetTextColor('0','0','0');
            $this->MultiCell(20,2,money_format('%!i', $value->credit_amounts),0,'R');
            }
            
            
            if($value->balance<0){$balance = 'Cr.'.money_format('%!i', (-1*$value->balance));}else{$balance = money_format('%!i', $value->balance);}
            
            if($count==$key){
             $this->Setxy(177,$y);
            $this->SetFont('times',$b,'9');
            $this->SetTextColor('0','0','0');
            $this->MultiCell(25,2,$balance,0,'R');
}
            $this->Setxy(85,$y);
            $this->SetFont('times',$b,'9');
            $this->SetTextColor('0','0','0');
              $this->MultiCell(20,2,($value->journal_type),0,'L');
             
             
            $this->Setxy(108,$y);
            $this->SetFont('times',$b,'9');
            $this->SetTextColor('0','0','0');
             $this->MultiCell(25,2,($value->journal_name),0,'L'); 
            //  dd($value->journal_date);
            
            
             
             
            // if($value->narration!=''){
            //     // $this->MultiCell(45,2,($value->journal_name.'  ---  '.$value->narration),0,'L');
            // }else{
            //     // $this->MultiCell(45,2,($value->journal_name),0,'L');
            // }

            $xxy = $this->getY()+1;

            $this->Setxy(35,$y);
            $this->SetFont('times','B','9');
            $this->SetTextColor('0','0','0');
            // $pdf->writeHTML($value->ac_name.'<br>'.$value->name, true, false, false, false, '');
            
            if($value->narration!=''){
                $this->MultiCell(45,2,($value->concatenated_segments),0,'L');
                
                $y=$this->getY()+1;
                $this->Setxy(35,$y);
                $this->SetFont('times',$b,'9');
                $this->SetTextColor('0','0','0');
                $this->MultiCell(45,2,($value->narration),0,'L');
            }else{
                $this->MultiCell(45,2,($value->concatenated_segments),0,'L');
            }
            // dd($neww);
            
            
            // $this->Line(12, $y-15, 12, $y+10, $style1);
            // $this->Line(33, $y-15, 33, $y+10, $style1);
            // $this->Line(79, $y-15, 79, $y+10, $style1);
            // $this->Line(106, $y-15, 106, $y+10, $style1);
            // $this->Line(133, $y-15, 133, $y+10, $style1);
            // $this->Line(155 ,$y-15, 155, $y+10, $style1);
            // $this->Line(178, $y-15, 178, $y+10, $style1);
            // $this->Line(204, $y-15, 204, $y+10, $style1);
            
            // $this->MultiCell(40,0,($value->concatenated_segments),0,'L');
            // $this->MultiCell(60,0,($value->ac_name.'-'.$value->name),0,'L');
          
            $xyy = $this->getY()+4;
            // $xyyy = $this->getY()+3;
            //   $this->Line(12, $xyyy, 204, $xyyy, $style1);
            if($xyy<$xxy)
            {
                $y =$xxy;
            }else{
                $y = $xyy;
            }
        // }        
        $this->SetY($y);
        return $y;
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
    // dd($start_date);
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf-> setLogo('',date('d-m-Y',strtotime($start_date)),date('d-m-Y',strtotime($end_date)));

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

//$pdf->Line(80, 64, 130, 64, $style1);

    // $pdf->SetXY(14,15);
    // $pdf->SetFont('times','','10');
    // $pdf->SetTextColor('0','0','0');
    // $pdf->Cell(0,0, "To  :" ,0,1,'L');

//     $pdf->SetXY(22,15);
//     $pdf->SetFont('times','B','10');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->Cell(0,0, '' ,0,1,'L');
//     //dd($customer_name);
//     $y=$pdf->getY();
//   $pdf->SetXY(22,$y);
//     $pdf->SetFont('times','','10');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->MultiCell(60,0, '' ,0,'L');
 
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
    
    // $pdf->SetXY(110,15);
    // $pdf->SetFont('times','','9');
    // $pdf->SetTextColor('0','0','0');
    // $pdf->Cell(0,0, "From:" ,0,1,'L');
   $pdf->SetXY(14,15);
    $pdf->SetFont('times','B','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(180,0, "DR. JRK'S RESEARCH AND PHARMACEUTICALS PVT.LTD," ,0,1,'C');
    

   $y=$pdf->getY();
 $pdf->SetXY(14,$y+1);
    $pdf->SetFont('times','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(180,0, "Mfg Unit: 18 & 19, Perumal Koil Street, Regd. Off: 13, Perumal Koil Street,Kunrathur" ,0,1,'C');
    
    
  $y=$pdf->getY();
 $pdf->SetXY(14,$y+3);
    $pdf->SetFont('times','B','11');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(185,0, "Cash Book " ,0,1,'C');

 $y=$pdf->getY();
 
    
    // $pdf->SetXY(14,$y+16);
    // $pdf->SetFont('times','','11');
    // $pdf->SetTextColor('0','0','0');
    // $pdf->Cell(0,0, "Dear Sir/Madam," ,0,1,'L'); 
    
    
    // $pdf->SetXY(180,$y+16);
    // $pdf->SetFont('times','','11');
    // $pdf->SetTextColor('0','0','0');
    // $pdf->Cell(0,0, "Date : ".date("d-m-Y") ,0,1,'L');
    
    // $pdf->SetXY(174,$y+5);
    // $pdf->SetFont('times','','11');
    // $pdf->SetTextColor('0','0','0');
    // $pdf->Cell(18,0, "Print Date : ".date('d-m-Y') ,0,1,'C'); 
    
    $pdf->SetXY(82,$y+4);
    $pdf->SetFont('times','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0,"Date : ". date('d-m-Y',strtotime($start_date))." to ".date('d-m-Y',strtotime($end_date)) ,0,1,'L');
    
    // $pdf->SetXY(80,$y+20);
    // $pdf->SetFont('times','','11');
    // $pdf->SetTextColor('0','0','0');
    // $pdf->Cell(0,0, "Sub: Confirmation of Accounts" ,0,1,'L'); 
    
    
    // $pdf->SetXY(82,$y+24);
    // $pdf->SetFont('times','','11');
    // $pdf->SetTextColor('0','0','0');
    // $pdf->Cell(0,0, date('d-m-Y',strtotime($start_date))." to ".date('d-m-Y',strtotime($end_date)) ,0,1,'L'); 
    
//     $pdf->SetXY(14,$y+35);
//     $pdf->SetFont('times','','11');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->Cell(0,0, "Given below is the details of your Accounts as standing in my/our Books of Accounts for the above mentioned
// period.," ,0,1,'L'); 


//  $pdf->SetXY(14,$y+46);
//     $pdf->SetFont('times','','11');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->Cell(0,0, "Kindly return 3 copies stating your I.T. Permanent A/c No., duly signed and sealed, in confirmation of the same." ,0,1,'L'); 
    
//     $pdf->SetXY(14,$y+52);
//     $pdf->SetFont('times','','11');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->Cell(0,0, "Please note that if no reply is received from you within a fortnight, it will be assumed that you have accepted the" ,0,1,'L'); 

//  $pdf->SetXY(14,$y+58);
//     $pdf->SetFont('times','','11');
//     $pdf->SetTextColor('0','0','0');
//     $pdf->Cell(0,0, "balance shown below." ,0,1,'L');


   
$pdf->Line(12,43,204, 43, $style1);

  
$pdf->Line(12, 53, 204, 53, $style1);

//$this->Line(80, 64, 130, 64, $this->style);

$y=27.5;

      


 $pdf->SetXY(14,$y+18);
    $pdf->SetFont('times','B','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(18,0, "Date" ,0,1,'C');
    
    $pdf->SetXY(35,$y+18);
    $pdf->SetFont('times','B','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(45,0, "Particulars" ,0,1,'L');
    
     $pdf->SetXY(85,$y+18);
    $pdf->SetFont('times','B','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(40,0, "Vch Type" ,0,1,'L');
    
    $pdf->SetXY(110,$y+18);
    $pdf->SetFont('times','B','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(20,0, "Vch No" ,0,1,'C');
    
    $pdf->SetXY(135,$y+18);
    $pdf->SetFont('times','B','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(20,0, "Debit (Rs.)" ,0,1,'C');
    
    
    $pdf->SetXY(155,$y+18);
    $pdf->SetFont('times','B','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(25,0, "Credit (Rs.)" ,0,1,'C');
    
    $pdf->SetXY(177,$y+18);
    $pdf->SetFont('times','B','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(25,0, "Balance (Rs.)" ,0,1,'C');
    
    
    
                    $pdf->Line(12, $y+15.5, 12, 295, $style1);
                    $pdf->Line(33, $y+15.5, 33,295, $style1);
                    $pdf->Line(79, $y+15.5, 79, 295, $style1);
                    $pdf->Line(106, $y+15.5, 106, 295, $style1);
                    $pdf->Line(133, $y+15.5, 133, 295, $style1);
                    $pdf->Line(155 ,$y+15.5, 155, 295, $style1);
                    $pdf->Line(178, $y+15.5, 178, 295, $style1);
                    $pdf->Line(204, $y+15.5, 204,295, $style1);
                     $pdf->Line(12, 295, 204,295, $style1);    

$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

    

$i=0;
$oldY=0;
$debit_amount=0;
$credit_amount=0;
$debit_amount1=0;
$credit_amount1=0;
$total_amount=0;
$convert=0;
$tax_amount=0;
 $y = 58;
// dd($results);
$datee=0;
$dd=0;
$datee_save = array();
$debit_amount2=0;
$debitamt3=0;
$balance_amt=0;
$opening =0;
$count=count($results)-1;
// dd($results);
foreach($results as $key=>$value) 
{   
   
  $i++;
  
  if($key==0){
      $opening=-($value->balance);
    //   dd($opening);
  }
           if($y>285){
                $pdf->AddPage('P', $resolution);
                $y = 32;
            }
              $debit_amount=$debit_amount+$value->debit_amounts;
              $credit_amount=$credit_amount+$value->credit_amounts;

    if ($count==$key || $datee != $value->journal_date ) 
    {
        $neww = "success";
        $debit_amount1=$debitamt3;
        $credit_amount1=$creditamt3;
        // if($i!=1){
// dd($opening,$debit_amount1);}
        $opening=$opening-$debit_amount1+$credit_amount1;

        $debitamt3=0;
        $creditamt3=0;
         
    } else 
    {
        $neww = "fail";
    }
    $datee = $value->journal_date;
    
 if($datee==$value->journal_date)
 {
     
    $debitamt3=$debitamt3+$value->debit_amounts;
     if($key!=0)
     {
        $creditamt3=$creditamt3+$value->credit_amounts;
     }
    // $balanceamt=$creditamt3-$debitamt3;
 }
//  dd($balance_amt);
    if($i!=1)
    {
        if($y>285){
                $pdf->AddPage('P', $resolution);
                $y = 32;
            }
        
        if($neww=="success")
        {
            
            
            $pdf->Setxy(108,$y);
            $pdf->SetFont('times','B','9');
            $pdf->SetTextColor('0','0','0');
            $pdf->MultiCell(25,2,"Closing Balance",0,'L'); 
            
            $pdf->Setxy(129,$y);
            $pdf->SetFont('times','B','9');
            $pdf->SetTextColor('0','0','0');
            $pdf->MultiCell(25,2,money_format('%!i',$debit_amount1),0,'R'); 
            
             $pdf->Setxy(150,$y);
            $pdf->SetFont('times','B','9');
            $pdf->SetTextColor('0','0','0');
            $pdf->MultiCell(25,2,money_format('%!i',$credit_amount1),0,'R'); 
            
             $pdf->Setxy(177,$y);
            $pdf->SetFont('times','B','9');
            $pdf->SetTextColor('0','0','0');
            $pdf->MultiCell(25,2,money_format('%!i', $opening),0,'R'); 
            
            $y=$pdf->getY()+4;
              $pdf->Line(12, $y-2, 204, $y-2, $style1);  
            }
        }
        
             $y = $pdf->MultiRow($value,$i,$y,$pdf,$total_amount,$neww,$count,$key); 
        // }
 }
 
//  $debit_amounts = array_column($results, 'debit_amounts');
//  $debit_amount = array_sum($debit_amounts);

// dd($debit_amount);
//debit pixel -> 150, credit pixel ->173


/*$y=$pdf->getY()+4;
 $pdf->Setxy(130,$y);
$pdf->SetFont('times','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->cell(20,0,money_format('%!i', $debit_amount),0,1,'R');

 $pdf->Setxy(153,$y);
$pdf->SetFont('times','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2,money_format('%!i', $credit_amount),0,'R');

if(($debit_amount-$credit_amount)<0){$balance = 'Cr.'.money_format('%!i', -1*($debit_amount-$credit_amount));}else{$balance = money_format('%!i', $debit_amount-$credit_amount);}

 $pdf->Setxy(177,$y);
$pdf->SetFont('times','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2,$balance,0,'R');



$xxy = $pdf->getY();

    $pdf->Setxy(85,$y);
$pdf->SetFont('times','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(40,0,'Total',0,'L');*/

 


   



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
    if($y > 290)
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
 
$pdf->Line(12, 290, 204, 290, $pdf->style);


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
  
            
      ob_end_clean();
 $pdf->Output('name.pdf','I'); 
    exit;
    

      $pdf->close(); 
//============================================================+
// END OF FILE
//============================================================+

/******************************************************************************************/ 
?>
