<?php
$resolution= array(215, 307);

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
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal) ? "" . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise ;
}
    class MYPDF extends TCPDF { 
public $style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));
public function setLogo($company_name,$company_address,$comp_address,$website_address){
            $this->company_name = $company_name;
            $this->company_address = $company_address;
            $this->comp_address = $comp_address;
            $this->website_address = $website_address;
           

          }


 public function fopage($totalpage){
 $this->totalpage = $totalpage;

    }

      public function Header() {

 $image_file = URL::to('/images/backend-logo.jpg');
 //dd($image_file);
  $this->Image($image_file,180	,10,20,20, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 14);
        // Title

		$this->Line(12, 50, 208, 50);
		$this->Line(12, 7, 12, 290);
		$this->Line(12, 7, 208, 7);
		$this->Line(208, 7, 208, 290);
		$this->Line(12, 290, 208, 290);




$this->SetXY(12,10);
		$this->SetFont('','','12');
		$this->SetTextColor('0','0','0');
		$this->SetFillColor('36','108','164');
		$this->Cell(195,0, $this->company_name,0,1,'C');
		
			$this->SetXY(12,17);
			$this->SetFont('','','10');
			$this->SetTextColor('0','0','0');
			$this->MultiCell(195,0,$this->company_address  ,0,'C');

			$this->SetXY(12,22);
			$this->SetFont('','','10');
			$this->SetTextColor('0','0','0');
			$this->MultiCell(195,0,$this->comp_address  ,0,'C');

			$this->SetXY(12,28);
			$this->SetFont('','','10');
			$this->SetTextColor('0','0','0');
			$this->Cell(195,0, "Website"." : ".$this->website_address ,0,1,'C');

		$this->SetXY(12,39);
		$this->SetFont('','B','14');
		$this->SetTextColor('0','0','0');
		$this->MultiCell(195,10,"Payment Advice",'0','C');
                
                $this->SetXY(90,40);
		$this->SetFont('','B','11');
		$this->SetTextColor('0','0','0');
		$this->MultiCell(195,10,date('d-m-Y'),'0','C');
                
$this->SetXY(6,55);
$this->SetFont('','B','11');
$this->SetTextColor('0','0','0');
$this->MultiCell(25,2, "S.No",0,'C');

$this->SetXY(30,55);
$this->SetFont('','B','11');
$this->SetTextColor('0','0','0');
$this->MultiCell(25,2, "Payment Number",0,'C');

$this->SetXY(55,55);
$this->SetFont('','B','11');
$this->SetTextColor('0','0','0');
$this->MultiCell(25,2, "Payment Date",0,'C');

$this->SetXY(78,55);
$this->SetFont('','B','11');
$this->SetTextColor('0','0','0');
$this->MultiCell(25,2, "Name",0,'C');

$this->SetXY(135,55);
$this->SetFont('','B','11');
$this->SetTextColor('0','0','0');
$this->MultiCell(25,2, "Narration",0,'C');



$this->SetXY(180,55);
$this->SetFont('','B','11');
$this->SetTextColor('0','0','0');
$this->MultiCell(25,2, "Payment Amount",0,'C');




// // x axis line sno
 $this->Line(12, 65, 208, 65, $this->style);
// //next s.no line
 $this->Line(25, 50, 25, 290, $this->style);
// //next Payment Number line
 $this->Line(55, 50, 55, 290, $this->style);
// //next Payment DATE line
 $this->Line(77, 50, 77, 290, $this->style);
// //next NAME  line
 $this->Line(125, 50, 125, 290, $this->style);
// //next Narration no  line
 $this->Line(180, 50, 180, 290, $this->style);

    }

           public function MultiRow($value,$i,$pdf,$alltotal) { 
         
           	     $y = $pdf->getY()+3;

        


$pdf->SetXY(15,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $i ,0,1,'L');
           	     $y = $pdf->getY()-4;

 
$pdf->SetXY(32,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, $value->payment_number ,0,'L');
$py = $pdf->getY();
$y = $pdf->getY()-4;
$pdf->SetXY(57,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, $value->payment_date ,0,'L');

$name='';
if($value->first_name!=''){
    $name=$value->first_name;
}
else if($value->customer_name!=''){
    $name=$value->customer_name;
}
if($value->supplier_name!=''){
    $name=$value->supplier_name;
}

$pdf->SetXY(78,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(45,2, $name ,0,'L');
$ny = $pdf->getY();

$pdf->SetXY(180,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, number_format($value->payment_amount,2),0,'R');

$pdf->SetXY(125,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(55,2,  $value->remarks ,0,'L');

     $yy = $pdf->getY();
 
     if($ny>$yy){
     	    $pdf->setY($ny);
     	     $this->Line(12, $ny, 208, $ny);  
}else{
		    $pdf->setY($yy);
		     $this->Line(12, $yy, 208, $yy);  
}
           }

               public function Footer() {


}

}


$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setLogo($company_name,$company_address,$comp_address,$website_address);

$totalpage = $pdf->getNumPages();
//dd($totalpage);
$pdf->fopage($totalpage);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+40, PDF_MARGIN_RIGHT);
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
 
 $pdf->SetFont('times', '', 12);

// add a page
$pdf->AddPage('P', $resolution);



$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));


$count=1;
$i=0;
$oldY=0;
$tot=0;
$totamt=0;
$amount1=0;
$alltotal=0;
$line_total=0;
$gndtot=0;
$hsn_y=0;
//$value=$linedata[0];
//for($j=0;$j<50;$j++) {
foreach($details as $key=>$value) {  
    $i++;

$pdf->MultiRow($value,$i,$pdf,$alltotal); 
}


$total = $pdf->getNumPages();
// dd($total);     
if($pdf->pageNo()==$total)
{
    $y = $pdf->getY();
    // dd($y);
    if($y > 180)
        $pdf->AddPage('P', $resolution);

    $totalpage = $pdf->getNumPages();
    $pdf->fopage($totalpage);
 

 
$pdf->Rect(12, 240, 196, 50,'DF', "",  array(255, 255, 255));

      $pdf->Line(125, 250, 208, 250, $style);
      $pdf->Line(125, 240, 125, 250, $style);
      $pdf->Line(180, 240, 180, 250, $style);
    //  $pdf->Line(12, 238, 208, 238, $style); 
        $pdf->SetXY(130,243);
        $pdf->SetFont('','B','11');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(25,2, "Total",0,'C');
        
         $pdf->SetXY(180,243);
        $pdf->SetFont('','B','11');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(25,2,number_format($alltot,2),0,'R');

        $pdf->SetXY(15,245);
        $pdf->SetFont('','B','10');
        $pdf->SetTextColor('0','0','0');
        $pdf->Cell(0,2, "Amount In Words : ",0,1,'L');

        $pdf->SetXY(20,251);
        $pdf->SetFont('','','10');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(0,0,  convert_number_to_words($alltot),0,'L');
       
        $pdf->SetXY(15,275);
        $pdf->SetFont('','','11');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(25,2, "Created By",0,'C');
        
          $pdf->SetXY(60,275);
        $pdf->SetFont('','','11');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(100,2, "Authorized By",0,'C');
        
          $pdf->SetXY(130,275);
        $pdf->SetFont('','','11');
        $pdf->SetTextColor('0','0','0');
        $pdf->MultiCell(100,2, "Verified By",0,'C');
}
 $total = $pdf->getNumPages();

ob_end_clean();
    
$pdf->Output('example_007.pdf', 'FI');
exit();


   ?>