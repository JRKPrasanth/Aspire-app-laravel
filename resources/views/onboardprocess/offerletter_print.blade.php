<?php

function cleanForTcpdf($html)
{
    // Replace text-align with align attribute
    $html = preg_replace('/<p[^>]*text-align:\s*right[^>]*>/i', '<p align="right">', $html);
    $html = preg_replace('/<p[^>]*text-align:\s*center[^>]*>/i', '<p align="center">', $html);
    $html = preg_replace('/<p[^>]*text-align:\s*left[^>]*>/i', '<p align="left">', $html);

    $html = preg_replace('/<div[^>]*text-align:\s*right[^>]*>/i', '<div align="right">', $html);
    $html = preg_replace('/<div[^>]*text-align:\s*center[^>]*>/i', '<div align="center">', $html);
    $html = preg_replace('/<div[^>]*text-align:\s*left[^>]*>/i', '<div align="left">', $html);

    // Strip unused tags
    $html = strip_tags($html, '<p><b><i><u><br><ol><ul><li><div>');

    return $html;
}

ob_start();
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(20, 15, 15);
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
   public $logo;

    public function setLogo($company_logo){
        $this->logo = $logo;
    }
//Page header
     public function Header() {
        //Logo
        //dd($this->logo);
       $image_file = public_path().'/images/letterhead.PNG';
      // dd($image_file);
      $this->Image($image_file, 0, 0,200, 40, 'PNG', '', 'T', false, 250, '', false, false, 0, false, false, false);
    
        // Set font
        $this->SetFont('helvetica', 'B', 14);
        // Title
        $this->SetXY(0,5);
        $this->SetFont('','B','12');
        $this->SetTextColor('0','0','0');
        $this->Cell(0, 10, '', 0, 1, 'R');
        $this->Cell(0, 30, '', 0, false, 'R', 0, '', 0, false, 'M', 'L');

         
    }
    
       // Page footer
        public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-8);
            // Set font
            $this->SetFont('helvetica', '', 8);
            // Page number
          $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M'); 
          if($this->PageNo() =="2")
            {
                /*$this->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
                $this->Line(0,0,$this->getPageWidth(),0); 
                $this->Line($this->getPageWidth(),0,$this->getPageWidth(),$this->getPageHeight());
                $this->Line(0,$this->getPageHeight(),$this->getPageWidth(),$this->getPageHeight());
                $this->Line(0,0,0,$this->getPageHeight());
                $this->SetLineStyle( array( 'width' => 14, 'color' => array(255,255,255)));
                $this->Line(0,0,$this->getPageWidth(),0); 
                $this->Line($this->getPageWidth(),0,$this->getPageWidth(),$this->getPageHeight());
                $this->Line(0,$this->getPageHeight(),$this->getPageWidth(),$this->getPageHeight());
                $this->Line(0,0,0,$this->getPageHeight());*/// set margins

                
            }    
        }    
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$pdf->setLogo($company_logo_name);

// add a page
$pdf->AddPage();

//$pdf->SetHeaderData('', '', 'CORRESPONDENCE LETTER', '');
// $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
//$pdf->Line(0,0,$pdf->getPageWidth(),0); 
//$pdf->Line($pdf->getPageWidth(),0,$pdf->getPageWidth(),$pdf->getPageHeight());
//$pdf->Line(0,$pdf->getPageHeight(),$pdf->getPageWidth(),$pdf->getPageHeight());
//$pdf->Line(0,0,0,$pdf->getPageHeight());
//$pdf->SetLineStyle( array( 'width' => 14, 'color' => array(255,255,255)));
//$pdf->Line(0,0,$pdf->getPageWidth(),0); 
//$pdf->Line($pdf->getPageWidth(),0,$pdf->getPageWidth(),$pdf->getPageHeight());
//$pdf->Line(0,$pdf->getPageHeight(),$pdf->getPageWidth(),$pdf->getPageHeight());
//$pdf->Line(0,0,0,$pdf->getPageHeight());// set margins

$pdf->SetFont('helvetica', '', 11);
$pdf->SetTitle('Offer Letter');
$count=1;
$oldY=0;
    
if($count==1)  
{   
    
  function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $only        = 'Only';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        100000              => 'lakh',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break; 
        case $number < 100000:
            $thousands   = ((int) ($number / 1000));
            $remainder = $number % 1000;

            $thousands = convert_number_to_words($thousands);

            $string .= $thousands . ' ' . $dictionary[1000];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
         case $number < 10000000:
            $lakhs   = ((int) ($number / 100000));
            $remainder = $number % 100000;

            $lakhs = convert_number_to_words($lakhs);

            $string = $lakhs . ' ' . $dictionary[100000];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder) .  ' ' .$only;
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
} 


$salary = convert_number_to_words($ctc_pay);
$letter= $letter_content[0]->body_content;
$current_date = date("d-m-Y");
$content  = $letter;

$find = ["[logo]","[company_name]", "[address]", "[current_date]","[position]","[employee_name]","[ctc_pay]","[ctc_text]","[joining date]","[valid date]","[HR name]","[grade]","[location]","[id]","[prefix]","[da]","[offer_letter_no]","[department_name]","[office_location]"];
$logo = "<img src='".public_path()."/images/".$logo."'".">";

$replace   = [$logo,$company_name,$address, $current_date,$description_name,$name_of_the_candidate,$ctc_pay,$salary,$date_of_joining,$at_date,$hr_name,$grade_name,$location,$randid,$prefix,$da,$offer_letter_no,$department_name,$office_location];

$newphrase1 = cleanForTcpdf($content);

$newPhrase = str_replace($find, $replace, $newphrase1);

$pdf->SetX(15);

//$pdf->WriteHTML($newPhrase, true, false, true, false);

$pdf->writeHTML($newPhrase, true, false, true, false, '');
if($employee_type=='231'){
$absolute = public_path().'/images/venkatesh.png';
$pdf->Image($absolute, 0, 233, 50, 0, '', '', '', false, 300, '', false, false, 0, false, false, false);
}else{
$absolute = public_path().'/images/rajesh.png';
$pdf->Image($absolute, 10, 125, 40, 0, '', '', '', false, 300, '', false, false, 0, false, false, false);    
}
}
 
//$pdf->SetHeaderData('', '', 'CORRESPONDENCE LETTER', '');
 /*$pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
$pdf->Line(0,0,$pdf->getPageWidth(),0); 
$pdf->Line($pdf->getPageWidth(),0,$pdf->getPageWidth(),$pdf->getPageHeight());
$pdf->Line(0,$pdf->getPageHeight(),$pdf->getPageWidth(),$pdf->getPageHeight());
$pdf->Line(0,0,0,$pdf->getPageHeight());
$pdf->SetLineStyle( array( 'width' => 14, 'color' => array(255,255,255)));
$pdf->Line(0,0,$pdf->getPageWidth(),0); 
$pdf->Line($pdf->getPageWidth(),0,$pdf->getPageWidth(),$pdf->getPageHeight());
$pdf->Line(0,$pdf->getPageHeight(),$pdf->getPageWidth(),$pdf->getPageHeight());
$pdf->Line(0,0,0,$pdf->getPageHeight());*/// set margins

$pdf->SetFont('helvetica', '', 11);
$pdf->SetTitle('Offer Letter');

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
      ob_end_clean(); 
      if($print=="PRINT")
{           

 $pdf->Output('name.pdf','I'); 
 $pdf->close(); 
    exit;
    
}
else
{   // print_r($candidate_id);exit;

  $pdf->Output('Uploads/offetletter/C_'.$candidate_id.'.pdf', 'F');

}
  



  

//============================================================+
// END OF FILE
//============================================================+

/******************************************************************************************/ 
?>
