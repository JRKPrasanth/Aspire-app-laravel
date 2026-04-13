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

    public $company_logo;

public function setLogo($company_logo,$employee_number,$first_name,$date_of_joining,$date_of_leaving,$date_of_birth,$notice_period,$job_title_name,$address,$salary_advance,$imprest_cash,$damages,$unpaid_loans,$training_expenses,$others_staff_welfare,$notice_period_pay,$non_receipt_of_clearnce_form,$non_receipt_of_noc,$paid_amount_if_any,$leave_profile,$el,$others,$remarks,$gross_pay,$basic_pay,$grandtotal_b,$grandtotal_a,$grandtotal
)
{
        $this->logo = $company_logo;
        $this->employee_number = $employee_number;
        $this->first_name = $first_name;
        $this->date_of_joining = $date_of_joining;
        $this->date_of_leaving = $date_of_leaving;
        $this->date_of_birth = $date_of_birth;
        $this->notice_period = $notice_period;
        $this->job_title_name = $job_title_name;
        $this->address = $address;
    	$this->salary_advance = $salary_advance;
		$this->imprest_cash = $imprest_cash;
		$this->damages = $damages;
		$this->unpaid_loans = $unpaid_loans;
		$this->training_expenses = $training_expenses;
		$this->others_staff_welfare = $others_staff_welfare;
		$this->notice_period_pay = $notice_period_pay;
		$this->non_receipt_of_clearnce_form = $non_receipt_of_clearnce_form;
		$this->non_receipt_of_noc = $non_receipt_of_noc;
		$this->paid_amount_if_any = $paid_amount_if_any;
		$this->leave_profile= $leave_profile;
		$this->el= $el;
		$this->others = $others;
		$this->remarks = $remarks;
		$this->gross_pay = $gross_pay;
		$this->basic_pay = $basic_pay;
		$this->grandtotal_b = $grandtotal_b;
		$this->grandtotal_a = $grandtotal_a;
		$this->grandtotal = $grandtotal;
	
    }
    
//Page header
    public function Header() {
        // Logo
        $image_file = public_path().'/images/'.$this->logo;
       $this->Image($image_file,  10, 7, 35, 15	, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
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
          $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');     
        }    
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setLogo($company_logo,$employee_number,$first_name,$date_of_joining,$date_of_leaving,$date_of_birth,$notice_period,$job_title_name,$address,$salary_advance,$imprest_cash,$damages,$unpaid_loans,$training_expenses,$others_staff_welfare,$notice_period_pay,$non_receipt_of_clearnce_form,$non_receipt_of_noc,$paid_amount_if_any,$leave_profile,$el,$others,$remarks,$gross_pay,$basic_pay,$grandtotal_b,$grandtotal_a,$grandtotal);

// add a page
$pdf->AddPage('P', $resolution);

$pdf->SetHeaderData('', '', 'Full & Final Settlement Form', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
 
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
// if($order_type_id=='SAMPLE'){
// $pdf->SetXY(12,10);
// $pdf->SetFont('','B','20');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "SAMPLE ORDER" ,0,1,'C');
// }else{
$pdf->SetXY(12,10);
$pdf->SetFont('','B','18');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Full & Final Settlement form" ,0,1,'C');
// }
$pdf->Line(12, 22, 208, 22, $style);

$y= $pdf->getY()+4;
$pdf->SetXY(13,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, 'Name' ,0,1,'L');

$pdf->SetXY(69,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $pdf->first_name ,0,1,'L');

$pdf->SetXY(109,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "EMP Code: ",0,1,'L');

$pdf->SetXY(165,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $pdf->employee_number ,0,1,'L');

$y= $pdf->getY()+4;
$pdf->Line(12, $y, 208, $y, $style);




// $y= $pdf->getY()+4;
$pdf->SetXY(13,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Designation" ,0,1,'L');


$pdf->SetXY(69,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $pdf->job_title_name ,0,1,'L');

$pdf->SetXY(109,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Location :  ",0,1,'L');


$pdf->SetXY(162,$y+2);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(45,0, $pdf->address ,0,'L');

$y= $pdf->getY()+4;
$pdf->Line(12, $y, 208, $y, $style);

$pdf->SetXY(13,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "DOJ" ,0,1,'L');


$pdf->SetXY(69,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$dateOfLeaving = new DateTime($pdf->date_of_joining);
$formattedDate = $dateOfLeaving->format('d-m-Y');
$pdf->Cell(0,0, $formattedDate ,0,1,'L');

$pdf->SetXY(109,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Last Drawn Salary Rs.",0,1,'L');

$pdf->SetXY(165,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $pdf->gross_pay ,0,1,'L');


$y= $pdf->getY()+4;
$pdf->Line(12, $y, 208, $y, $style);



// $y= $pdf->getY()+4;
$pdf->SetXY(13,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Statutory Resignation Date" ,0,1,'L');


$pdf->SetXY(69,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$dateOfLeaving = new DateTime($pdf->date_of_leaving);
$formattedDate = $dateOfLeaving->format('d-m-Y');
$pdf->Cell(0,0, $formattedDate ,0,1,'L');

$pdf->SetXY(109,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Last Drawn Basic Rs." ,0,1,'L');

$pdf->SetXY(165,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $pdf->basic_pay ,0,1,'L');


$y= $pdf->getY()+4;
$pdf->Line(12, $y, 208, $y, $style);

$pdf->SetXY(13,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Notice Period" ,0,1,'L');


$pdf->SetXY(69,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $pdf->notice_period ,0,1,'L');

$pdf->SetXY(109,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');-
$pdf->Cell(0,0, "Hand- Over form/Clearance form" ,0,1,'L');

$y= $pdf->getY()+4;
$pdf->Line(12, $y, 208, $y, $style);

$pdf->SetXY(13,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Last Working Day",0,1,'L');

$pdf->SetXY(69,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$dateOfLeaving = new DateTime($pdf->date_of_leaving);
$formattedDate = $dateOfLeaving->format('d-m-Y');
$pdf->Cell(0,0, $formattedDate ,0,1,'L');

$pdf->SetXY(109,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Annual Allowance-I",0,1,'L');

$y= $pdf->getY()+4;
$pdf->Line(12, $y, 208, $y, $style);

$pdf->Line(65, 22, 65, $y, $style);

$pdf->SetXY(109,$y+2);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Annual Allowance-II",0,1,'L');

$y= $pdf->getY()+4;
$pdf->Line(109, $y, 208, $y, $style);

$pdf->Line(160, 22, 160, $y, $style);

// /************************************Header bottom line******************************/
$pdf->Line(109, 22, 109, $y, $style);
// $pdf->Line(12, 93, 208, 93, $style);


/************************************Header End******************************/

/************************************Body Lines Content******************************/





$pdf->SetFillColor('32','100','150');
 $pdf->Rect(12, 92, 196, 6, 'F');

// $pdf->SetXY(13,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('255','255','255');

// $pdf->MultiCell(100,2, "S.No",0,'L');


$y= $pdf->getY()+13;




$pdf->Line(12, $y+15, 208, $y+15, $style);

$pdf->Line(65, $y+7, 109, $y+7, $style);


$pdf->Line(165, $y+7, 208, $y+7, $style);

// $pdf->Line(109, $y, 109, 250, $style);

$pdf->Line(65, $y-5.5, 65, 270 ,$style);

$pdf->Line(80, $y-5.5, 80, 270, $style);

$pdf->Line(95, $y+7, 95, 270, $style);

$pdf->Line(109, $y-5.5, 109, 270, $style);

// $pdf->Line(205, $y+7, 205, 250, $style);

$y= $pdf->getY()+4;
$pdf->SetXY(7,92);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('255','255','255');
$pdf->MultiCell(125,2, "Earnings ",0,'C');


$pdf->SetXY(95,92);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('255','255','255');
$pdf->MultiCell(125,2, "Deductions",0,'C');



$pdf->SetXY(25,102);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, "Description",0,'C');


$pdf->SetXY(125,102);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, "Description",0,'C');

$pdf->SetXY(65,98.5);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, "Amount",0,'C');

$pdf->SetXY(165,98.5);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,2, "Amount",0,'C');


$pdf->SetXY(182.5,98.5);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, "Prepared By",0,'C');



$pdf->SetXY(82.5,98.5);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, "Prepared By",0,'C');


$pdf->SetXY(60,106.5);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, "in Rs",0,'C');

$pdf->SetXY(75,106.5);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, "Dept",0,'C');

$pdf->SetXY(90,106.5);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, "Sign",0,'C');



$pdf->SetXY(160,106.5);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, "in Rs",0,'C');

$pdf->SetXY(175,106.5);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, "Dept",0,'C');

$pdf->SetXY(190,106.5);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(25,2, "Sign",0,'C');

$y=$pdf->getY()+4;

$pdf->SetXY(109,$y);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "Salary Advance (if any)",0,'L');


$pdf->SetXY(169,$y);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0, $pdf->salary_advance ,0,'L');

$pdf->SetXY(12,$y);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "Payable",0,'L');

  

$y=$pdf->getY()+4;
$pdf->Line(12, $y, 208, $y, $style);



$pdf->SetXY(109,$y+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "Imprest Cash (if any)",0,'L');

$pdf->SetXY(140,$y+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0, $pdf->imprest_cash ,0,'C');

$y=$pdf->getY()+4;
$pdf->Line(12, $y, 208, $y, $style);

$pdf->SetXY(109,$y+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "Damages (if any)",0,'L');

$pdf->SetXY(140,$y+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0, $pdf->damages ,0,'C');


$pdf->SetXY(12,$y);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "",0,'L');

$y=$pdf->getY()+4;
$pdf->Line(12, $y, 208, $y, $style);

$pdf->SetXY(109,$y+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "Unpaid Loans",0,'L');

$pdf->SetXY(140,$y+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0, $pdf->unpaid_loans ,0,'C');


$y=$pdf->getY()+4;
$pdf->Line(12, $y, 208, $y, $style);

$pdf->SetXY(109,$y+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "Training Expenses",0,'L');

$pdf->SetXY(140,$y+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0, $pdf->training_expenses ,0,'C');


$y=$pdf->getY()+4;
$pdf->Line(12, $y, 208, $y, $style);

$pdf->SetXY(109,$y+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "Others (Staff welfare)",0,'L');

$pdf->SetXY(140,$y+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0, $pdf->others_staff_welfare ,0,'C');

$pdf->SetXY(12,$y+1);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "Expenses",0,'L');


$y=$pdf->getY()+4;
$pdf->Line(12, $y, 208, $y, $style);
$pdf->SetXY(109,$y+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "Notice Period Pay ",0,'L');

$pdf->SetXY(140,$y+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0, $pdf->notice_period_pay ,0,'C');


$y=$pdf->getY()+4;
$pdf->Line(12, $y, 208, $y, $style);

$pdf->SetXY(109,$y+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(45,2, "Non receipt of Clearance form",0,'L');

$pdf->SetXY(140,$y+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0, $pdf->non_receipt_of_clearnce_form ,0,'C');

$pdf->SetXY(12,$y);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "",0,'L');

$y=$pdf->getY()+4;
$pdf->Line(12, $y, 208, $y, $style);

$pdf->SetXY(109,$y+1);
$pdf->SetFont('','','8'); 
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "Non receipt of NOC",0,'L');

$pdf->SetXY(140,$y+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0, $pdf->non_receipt_of_noc ,0,'C');



$y=$pdf->getY()+4;
$pdf->Line(12, $y, 208, $y, $style);

$pdf->Line(165, $y-90.5, 165, $y, $style);
 
$pdf->Line(180, $y-90.5, 180, $y, $style);

$pdf->Line(195, $y-77.7, 195, $y, $style);

$pdf->SetXY(109,$y+1);
$pdf->SetFont('','B','8.8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "Paid amount  if any",0,'L');

$pdf->SetXY(140,$y+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0, $pdf->paid_amount_if_any ,0,'C');


// $y=$pdf->getY()+4;
// $pdf->Line(12, $y, 208, $y, $style);

$y=$pdf->getY()+4;
$pdf->Line(12, $y, 208, $y, $style);

$pdf->SetXY(12,$y);
$pdf->SetFont('','B','8.8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "Annual Allowance",0,'L');

$y=$pdf->getY()+4;
$pdf->Line(12, $y, 208, $y, $style);

$pdf->Line(12, $y+8, 208, $y+8, $style);
$pdf->Line(12, $y+16, 208, $y+16, $style);
// $y=$pdf->getY();
// $pdf->setY($y);
// $pdf->Line(12, $y+4, 208, $y+4, $style);

$y=$pdf->getY()+20;
$pdf->Line(12, $y+8, 208, $y+8, $style);

$pdf->SetXY(12,$y+1);
$pdf->SetFont('','B','8.8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "Leave Profile",0,'L');

$pdf->SetXY(65,$y+1);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,0, $pdf->leave_profile ,0,'C');

$pdf->SetXY(12,$y+8);
$pdf->SetFont('','B','8.8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "EL",0,'L');

$pdf->SetXY(65,$y+8);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,0, $pdf->el ,0,'C');


// $pdf->SetXY(12,$y+20);
// $pdf->SetFont('','B','8.8');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(35,2, "Other (if any)",0,'L');



$i=0;
$oldY=0;
$dis_amt=0;
$disamt=0;
$gst_tot=0;
$discount=0;
$tax_amt=0;
//$value=$linedata[0];
//for($j=0;$j<26;$j++) {
// foreach($linedata as $key=>$value) { 
  
// if($oldY==0) $y =100;
// else $y = $oldY+2;	
// $i++;
	
// $pdf->SetXY(15,$y+1);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, $i ,0,1,'L');
// $y=$y+8;

// $pdf->SetXY(26,$y-7);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(124,2, $value['product'] ,0,'L');
// $oldY=$pdf->getY();

// $pdf->SetXY(150,$y-7);
// $pdf->SetFont('','','8');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(15,2, $value['uom_code'] ,0,'C'); 
	
// $pdf->SetXY(165,$y-7);
// $pdf->SetFont('','','8');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(20,2, $value['qty'] ,0,'C');

// $pdf->SetXY(185,$y-7);
// $pdf->SetFont('','','8');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(23,2, $value['free_qty'] ,0,'C');
	
// 	if($oldY > 280)
// 	{
// 		$oldY = 0;
// 		if($pdf->pageno()==1)
// 		{

// 		}
// 		$pdf->addPage();
// 		$pdf->Line(12, 7, 12, 290, $style);
// $pdf->Line(12, 7, 208, 7, $style);
// $pdf->Line(208, 7, 208, 290, $style);
// $pdf->Line(12, 290, 208, 290, $style);

// /************************************Header *********************************/
// // if($order_type_id=='SAMPLE'){
// // $pdf->SetXY(12,10);
// // $pdf->SetFont('','B','20');
// // $pdf->SetTextColor('0','0','0');
// // $pdf->Cell(0,0, "SAMPLE ORDER" ,0,1,'C');
// // }else{
// $pdf->SetXY(12,10);
// $pdf->SetFont('','B','20');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "Full & Final Settlement Form" ,0,1,'C');
// // }

// $pdf->Line(12, 22, 208, 22, $style);

// $pdf->SetXY(13,25);
// $pdf->SetFont('','B','9.5');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, $company_name ,0,1,'L');

// $pdf->SetXY(13,29);
// $pdf->SetFont('','','8');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "(".$company_name.")" ,0,1,'L');

// $pdf->SetXY(13,33);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(95,10, $company_address ,0,1,'');


// $pdf->SetXY(13,42);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "CIN: ".$cin_no ,0,1,'L');

// $pdf->SetXY(13,47);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "GSTIN/UIN: ".$gst_no ,0,1,'L');

// $pdf->SetXY(13,51);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "State Name : ".$state_name.", Code : ".$state_code,0,1,'L');



// $pdf->SetXY(13,55);
// $pdf->SetFont('','','8');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "Website : ".$website_address,0,1,'L');


// //$pdf->Line(12, 59, 208, 59, $style);
// $pdf->Line(109, 22, 109, 59, $style);

// $pdf->SetXY(13,59);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "Invoice To" ,0,1,'L');

// $pdf->SetXY(13,63);
// $pdf->SetFont('','','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, $customer_name ,0,1,'L');

// $pdf->SetXY(13,66);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $ship_to_address ,0,'L');
// $oldad = $pdf->getY();

// $pdf->SetXY(13,$oldad);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "PH : ".$contact_number ,0,'L');
// if($order_type_id!='SAMPLE'){
// $pdf->SetXY(13,$oldad+4);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "GSTIN/UIN : ".$bcgst_no,0,'L');
// }
// $pdf->SetXY(13,$oldad+7);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "State Name : ".$state_s ,0,'L');

// $pdf->SetXY(109,22);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "Order No ",0,'L');

// $pdf->SetXY(115,26);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $sales_order_no,0,'L');

// $pdf->SetXY(160,22);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "Dated ",0,'L');

// $pdf->SetXY(160,26);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $sales_order_date,0,'L');

// $pdf->Line(109, 31, 208, 31, $style);

// $pdf->SetXY(160,32);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "Mode/Terms of Payment ",0,'L');

// $pdf->SetXY(160,36);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $payment_term,0,'L');

// $pdf->Line(109, 40, 208, 40, $style);

// $pdf->SetXY(109,41);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "Customer Po Number",0,'L');

// $pdf->SetXY(109,45);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $customer_po_number,0,'L');

// $pdf->SetXY(160,41);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "Other Reference(s)",0,'L');

// $pdf->SetXY(160,45);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $remarks,0,'L');

// $pdf->Line(109, 50, 208, 50, $style);


// $pdf->SetXY(109,51);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "Despatch through.",0,'L');

// $pdf->SetXY(109,55);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $despatch_through,0,'L');

// $pdf->SetXY(160,51);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "Destination",0,'L');

// $pdf->SetXY(160,55);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $destination,0,'L');

// $pdf->Line(13, 59, 208, 59, $style);
// $pdf->Line(160, 22, 160, 59, $style);
// /************************************Header bottom line******************************/
// $pdf->Line(109, 59, 109, 93, $style);
// $pdf->Line(12, 93, 208, 93, $style);

// $pdf->SetXY(109,59);
// $pdf->SetFont('','','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "Terms of Delivery",0,'L');

// $pdf->SetXY(109,63);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $deliveryterm,0,'L');
// /************************************Header End******************************/

// /************************************Body Lines Content******************************/

// $pdf->Line(25, 93, 25, 290, $style);
// $pdf->Line(150, 93, 150, 290, $style);
// $pdf->Line(165, 93, 165, 290, $style);
// $pdf->Line(185, 93, 185, 290, $style);

// $pdf->Line(12, 100, 208, 100, $style);
// //$pdf->Line(12, 230, 208, 230, $style);
// //$pdf->Line(12, 240, 208, 240, $style);
// $pdf->SetFillColor('32','100','150');
//  $pdf->Rect(12, 92, 196, 8, 'F');
// $pdf->SetXY(13,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('255','255','255');
// $pdf->MultiCell(100,2, "S.No",0,'L');

// $pdf->SetXY(25,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('255','255','255');
// $pdf->MultiCell(155,2, "Description of Goods",0,'C');

// $pdf->SetXY(150,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('255','255','255');
// $pdf->MultiCell(15,2, "UOM",0,'C');


// $pdf->SetXY(165,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('255','255','255');
// $pdf->MultiCell(20,2, "Quantity",0,'C');

// $pdf->SetXY(185,92);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('255','255','255');
// $pdf->MultiCell(20,2, "Free Quantity",0,'C');
// 	}
// }
$total = $pdf->getNumPages();
if($pdf->pageNo()==$total)
{	
if($oldY < 300)
{
// $pdf->Rect(12, 240, 196, 50,'DF', "",  array(255, 255, 255));


// $pdf->Line(12, 230, 208, 230, $style);
$pdf->Line(12, 238, 208, 238, $style);
	
	$y=$y1=$pdf->getY()+12;
	
//
// 	dd($y);
$pdf->SetXY(12,$y);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "Other (if any)",0,'L');
$pdf->Line(12, $y+8, 208, $y+8, $style);

$pdf->SetXY(65,$y);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,0, $pdf->others ,0,'C');
	
	
	$y=$pdf->getY()+6;
	$pdf->SetXY(12,$y);

$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "(A) Total Rs",0,'L');

$pdf->SetXY(65,$y);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,0, $pdf->grandtotal_a ,0,'C');

$pdf->SetXY(109,$y);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "(B) Total Rs",0,'L');
$pdf->Line(12, $y+7, 208, $y+7, $style);
	
$pdf->SetXY(165,$y);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,0, $pdf->grandtotal_b ,0,'C');	
	
	
		$y=$pdf->getY()+6;
$pdf->SetXY(12,$y);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "Grand Total (A – B) Rs. ",0,'L');
$pdf->Line(12, $y+7, 208, $y+7, $style);

	
$pdf->SetXY(65,$y);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,0, $pdf->grandtotal ,0,'C');

$y=$pdf->getY()+6;
$pdf->SetXY(12,$y);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, "Total: (in Words) :",0,'L');

// $grandtotal=-*$pdf->grandtotal;

$pdf->SetXY(59,$y);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(70,0, convert_number_to_words((-1*$pdf->grandtotal)),0,'C');


$pdf->Line(12, $y+7, 208, $y+7, $style);

$y=$pdf->getY()+6;

$pdf->SetXY(12,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(45,2,"Remarks:-(if any)" ,0,'L');


$pdf->SetXY(25,$y);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,0, $pdf->remarks ,0,'C');

$pdf->Line(180, $y1-47.6, 180, $y-2.5, $style);
 	$pdf->Line(165, $y1-47.6, 165, $y-2.5, $style);




$pdf->Line(195,$y1-47.6, 195, $y-2.5, $style);
/************************************ Footer ******************************/
 


// $pdf->SetXY(110,270);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// // $pdf->MultiCell(100,2, "for ".$company_name,0,'C');

// $pdf->SetXY(120,283);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(80,2, "Authorised Signatory",0,'R');

// $pdf->Line(110, 268, 110, 290, $style);
// $pdf->Line(110, 268, 208, 268, $style);
}
else
{
	$pdf->addPage();
	$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

/************************************Header *********************************/
// if($order_type_id=='SAMPLE'){
// $pdf->SetXY(12,10);
// $pdf->SetFont('','B','20');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "SAMPLE ORDER" ,0,1,'C');
// }else{
// $pdf->SetXY(12,10);
// $pdf->SetFont('','B','20');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "SALES ORDER" ,0,1,'C');
// }

// $pdf->Line(12, 22, 208, 22, $style);

// $pdf->SetXY(13,25);
// $pdf->SetFont('','B','9.5');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, $company_name ,0,1,'L');

// $pdf->SetXY(13,29);
// $pdf->SetFont('','','8');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "(".$company_name.")" ,0,1,'L');

// $pdf->SetXY(13,33);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(95,10, $company_address ,0,1,'');


// $pdf->SetXY(13,42);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "CIN: ".$cin_no ,0,1,'L');

// $pdf->SetXY(13,47);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "GSTIN/UIN: ".$gst_no ,0,1,'L');

// $pdf->SetXY(13,51);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "State Name : ".$state_name.", Code : ".$state_code,0,1,'L');



// $pdf->SetXY(13,55);
// $pdf->SetFont('','','8');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "Website : ".$website_address,0,1,'L');
// //$pdf->Line(12, 59, 208, 59, $style);
// $pdf->Line(109, 22, 109, 59, $style);

// $pdf->SetXY(13,59);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "Invoice To" ,0,1,'L');

// $pdf->SetXY(13,63);
// $pdf->SetFont('','','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, $customer_name ,0,1,'L');

// $pdf->SetXY(13,66);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $ship_to_address ,0,'L');
// $oldad = $pdf->getY();

// $pdf->SetXY(13,$oldad);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "PH : ".$contact_number ,0,'L');
// if($order_type_id!='SAMPLE'){
// $pdf->SetXY(13,$oldad+4);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "GSTIN/UIN : ".$bcgst_no,0,'L');
// }
// $pdf->SetXY(13,$oldad+7);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "State Name : ".$state_s ,0,'L');

// $pdf->SetXY(109,22);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "Order No ",0,'L');

// $pdf->SetXY(115,26);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $sales_order_no,0,'L');

// $pdf->SetXY(160,22);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "Dated ",0,'L');

// $pdf->SetXY(160,26);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $sales_order_date,0,'L');

// // $pdf->Line(109, 31, 208, 31, $style);

// $pdf->SetXY(160,32);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "Mode/Terms of Payment ",0,'L');

// $pdf->SetXY(160,36);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $payment_term,0,'L');

// // $pdf->Line(109, 40, 208, 40, $style);

// $pdf->SetXY(109,41);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "Customer Po Number",0,'L');

// $pdf->SetXY(109,45);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $customer_po_number,0,'L');

// $pdf->SetXY(160,41);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "Other Reference(s)",0,'L');

// $pdf->SetXY(160,45);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $remarks,0,'L');

// // $pdf->Line(109, 50, 208, 50, $style);


// $pdf->SetXY(109,51);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "Despatch through.",0,'L');

// $pdf->SetXY(109,55);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $despatch_through,0,'L');

// $pdf->SetXY(160,51);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "Destination",0,'L');

// $pdf->SetXY(160,55);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $destination,0,'L');

// $pdf->Line(13, 59, 208, 59, $style);
// $pdf->Line(160, 22, 160, 59, $style);
// /************************************Header bottom line******************************/
// $pdf->Line(109, 59, 109, 93, $style);
// $pdf->Line(12, 93, 208, 93, $style);

// $pdf->SetXY(109,59);
// $pdf->SetFont('','','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "Terms of Delivery",0,'L');

// $pdf->SetXY(109,63);
// $pdf->SetFont('','B','10');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, $deliveryterm,0,'L');
// /************************************Header End******************************/

// /************************************Body Lines Content******************************/

// $pdf->Line(25, 93, 25, 290, $style);
// $pdf->Line(150, 93, 150, 290, $style);
// $pdf->Line(165, 93, 165, 290, $style);
// $pdf->Line(185, 93, 185, 290, $style);

// $pdf->Line(12, 100, 208, 100, $style);
// //$pdf->Line(12, 230, 208, 230, $style);
// //$pdf->Line(12, 240, 208, 240, $style);

// $pdf->SetXY(13,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "S.No",0,'L');

// $pdf->SetXY(25,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(155,2, "Description of Goods",0,'C');

// $pdf->SetXY(16,144);
// $pdf->SetFont('','B','14');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "<---------------------------------------------------End of Page-------------------------------------->",0,'C');

// $pdf->SetXY(150,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('255','255','255');
// $pdf->MultiCell(15,2, "UOM",0,'C');


// $pdf->SetXY(165,94);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('255','255','255');
// $pdf->MultiCell(20,2, "Quantity",0,'C');

// $pdf->SetXY(185,92);
// $pdf->SetFont('','B','9');
// $pdf->SetTextColor('255','255','255');
// $pdf->MultiCell(20,2, "Free Quantity",0,'C');
	
// 	$pdf->Rect(12, 240, 196, 50,'DF', "",  array(255, 255, 255));
// $pdf->Line(12, 230, 208, 230, $style);
// $pdf->Line(12, 240, 208, 240, $style);
	
// 		$pdf->SetXY(150,232);
// $pdf->SetFont('','B','12');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(15,2, "Total",0,'C');

// $pdf->SetXY(165,232);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(20,2,$totqty ,0,'C');

// 	$pdf->SetXY(160,232);
// $pdf->SetFont('','B','12');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(150,2, "Total",0,'L');

// $pdf->SetXY(183,232);
// $pdf->SetFont('','','8');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(15,2,<!--$totqty--> ,0,'R');
/************************************ Footer ******************************/


// $pdf->SetXY(110,270);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(100,2, "for ".$company_name,0,'C');

// $pdf->SetXY(120,283);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->MultiCell(80,2, "Authorised Signatory",0,'R');

// $pdf->Line(110, 268, 110, 290, $style);
// $pdf->Line(110, 268, 208, 268, $style);
}
}
/************************************Body Lines Content End******************************/


// if(count($terms_condition)>0){
//  $pdf->addPage();
// $pdf->SetHeaderData('', '', 'SALES ORDER', '');
//  $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
 
// $style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

// $pdf->Line(12, 7, 12, 290, $style);
// $pdf->Line(12, 7, 208, 7, $style);
// $pdf->Line(208, 7, 208, 290, $style);
// $pdf->Line(12, 290, 208, 290, $style);

// /************************************Header *********************************/
// // if($order_type_id=='SAMPLE'){
// // $pdf->SetXY(12,10);
// // $pdf->SetFont('','B','20');
// // $pdf->SetTextColor('0','0','0');
// // $pdf->Cell(0,0, "SAMPLE ORDER" ,0,1,'C');
// // }else{
// // $pdf->SetXY(12,10);
// // $pdf->SetFont('','B','20');
// // $pdf->SetTextColor('0','0','0');
// // $pdf->Cell(0,0, "SALES ORDER" ,0,1,'C');
// // }

// $pdf->Line(12, 22, 208, 22, $style);
	
// $pdf->SetXY(15,24);
// $pdf->SetFont('','B','11');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, "Terms and Condition :" ,0,1,'L');
// $oldY1=0;
// foreach($terms_condition as $k11=>$v11){
// //	for($j=0;$j<38;$j++){
// if($oldY1==0) $y =30;
// else $y = $oldY1+2;
	
// $pdf->SetXY(20,$y+1);
// $pdf->SetFont('','','9');
// $pdf->SetTextColor('0','0','0');
// $pdf->Cell(0,0, $v11->element_content ,0,1,'L');
// //$pdf->Cell(0,0, $j ,0,1,'L');
// $oldY1=$pdf->getY();
	
// 	if($oldY1 > 280)
// 	{
// 		$oldY1 = 0;
// 		$pdf->addPage();
// $pdf->SetHeaderData('', '', 'SALES ORDER', '');
//  $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
 
// $style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

// $pdf->Line(12, 7, 12, 290, $style);
// $pdf->Line(12, 7, 208, 7, $style);
// $pdf->Line(208, 7, 208, 290, $style);
// $pdf->Line(12, 290, 208, 290, $style);

// /************************************Header *********************************/
// // if($order_type_id=='SAMPLE'){
// // $pdf->SetXY(12,10);
// // $pdf->SetFont('','B','20');
// // $pdf->SetTextColor('0','0','0');
// // $pdf->Cell(0,0, "SAMPLE ORDER" ,0,1,'C');
// // }else{
// // $pdf->SetXY(12,10);
// // $pdf->SetFont('','B','20');
// // $pdf->SetTextColor('0','0','0');
// // $pdf->Cell(0,0, "SALES ORDER" ,0,1,'C');
// // }

// $pdf->Line(12, 22, 208, 22, $style);

// 	}
	
// }

// }



//Close and output PDF document
ob_end_clean();
 if($print=='PRINT')
{                    
 $pdf->Output('example_007.pdf','FI');
 $pdf->close();

exit();
}
else
{
    
  $filename="Uploads/salesorderupload/SO_.pdf";
  $pdf->Output('Uploads/salesorderupload/SO_.pdf', 'F');
}   
//$pdf->Output('example_007.pdf', 'FI');
//exit();
//============================================================+
// END OF FILE
//============================================================+

/******************************************************************************************/ 
?>
