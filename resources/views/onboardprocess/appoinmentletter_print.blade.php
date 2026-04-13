<?php

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

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
  public $employee_type = null;
  
    public function setLogo($logo){
        $this->logo = $logo;
           
    }
//Page header
     public function Header() {
        //Logo
     //dd($this->employee_type);
       //$image_file = public_path().'/images/'.$this->logo;
       if($this->employee_type =='231'){
       $image_file = public_path().'/images/jrks.png';
      $this->Image($image_file, 175, 10,20, 20, 'PNG', '', 'T', false, 250, '', false, false, 0, false, false, false);
       }
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
			//dd('d');
            // Position at 15 mm from bottom
            $this->SetY(-8);
            // Set font
            $this->SetFont('helvetica', '', 8);
            // Page number
			//dd($this->PageNo());
			
          $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M'); 
			if($this->PageNo() =="2" || $this->PageNo() =="3" || $this->PageNo() =="4" || $this->PageNo() =="5")
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

$pdf->employee_type = $employee_type;



// add a page
$pdf->AddPage();

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
$pdf->SetTitle('Appoinment Letter');
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

	

$salary = convert_number_to_words($gross_pay);
	if($letter_content!=''){
$letter= $letter_content[0]->body_content;
}else{
    $letter='';
}
$doj = date("m-d-Y", strtotime($Date_of_joining));
$current_date = date("F j, Y");
$offer_date = date("d-m-Y");
$content  = $letter;	

// remove Word-specific tags and attributes
$cleanHtml = tcpdfAlignFix($content);

$logo = "<img src='".public_path()."/images/".$logo."'".">";                     	
$find = ["[current_date]","[logo]","[company_name]","[location]","[employee_name]","[employee_address]","[Date_of_joining]","[gross_salary]","[basic]","[hra]","[da]","[gross_text]","[Director]","[prefix]","[randid]","[position]","[grade]","[loc]","[offer]","[state]","[department_name]"];
$replace   = [$current_date,$logo,$company_name,$location,$employee_name,$address,$doj,$gross_pay,$basic_pay,$hra,$da,$salary,$director,$prefix,$randid,$jobdesc_name,$grade_level,$loc_name,$offer_date,$state,$department_name];
if($employee_type=='231'){
$newPhrase = str_replace($find, $replace, $content);
$pdf->writeHTML($newPhrase);
}else{
$newPhrase = str_replace($find, $replace, $cleanHtml);
$pdf->writeHTML($newPhrase, true, false, true, false, '');
}

if($employee_type=='231'){
$absolute = public_path().'/images/venkatesh.png';
$pdf->Image($absolute, 0, 40, 50, 0, '', '', '', false, 300, '', false, false, 0, false, false, false);
}
}
  	
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
$pdf->SetTitle('Appoinment Letter');

function tcpdfAlignFix($html) {
    // Fix text-align:center
    $html = preg_replace(
        '/<p[^>]*style="[^"]*text-align:\s*center;?[^"]*"[^>]*>(.*?)<\/p>/i',
        '<table width="100%"><tr><td align="center">$1</td></tr></table>',
        $html
    );

    // Fix text-align:right
    $html = preg_replace(
        '/<p[^>]*style="[^"]*text-align:\s*right;?[^"]*"[^>]*>(.*?)<\/p>/i',
        '<table width="100%"><tr><td align="right">$1</td></tr></table>',
        $html
    );

    return $html;
}


// reset pointer to the last page
$pdf->lastPage();

$pager = $pdf->getAliasNumPage();
// ---------------------------------------------------------

//Close and output PDF document
ob_end_clean();

$pdf->Output('example_007.pdf', 'FI');
exit();
//============================================================+
// END OF FILE
//============================================================+

/******************************************************************************************/ 
?>
