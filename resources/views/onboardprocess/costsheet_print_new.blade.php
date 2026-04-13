<?php
error_reporting(0);

ob_start();
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$folder = storage_path('framework/views');

//Get a list of all of the file names in the folder.
$files = glob($folder . '/*');

//Loop through the file list.
foreach($files as $file){
    //Make sure that this is a file and not a directory.
    if(is_file($file)){
        //Use the unlink function to delete the file.
        unlink($file);
    }
}



$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(true, 0);
// set image scale factor

if (@file_exists(dirname(__FILE__).'/lang/eng.php')) 
{
    require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}



// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetProtection(array('print','copy'),'123456',null,0,null);  
// add a page
$resolution= array(215, 307);
$pdf->AddPage('P', $resolution);
// set font
$pdf->SetFont('times', '', 12);

class MYPDF extends TCPDF { 
public $company_logo;

    public function setLogo($company_logo){
        $this->logo = $company_logo;
    }
    
//Page header
    public function Header() {
     
        // Logo
          $image_file = public_path().'/images/'.$this->logo;
       
       $this->Image($image_file,180	,10,20,20, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
        // Logo	 
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

    $pdf->setlogo($company_logo);
    $pdf->SetXY(210,32);
    $pdf->SetFont('','','16');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0,$company_name,0,1,'L');
		// add a page
		$pdf->AddPage('P', $resolution);

		$pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

		$pdf->SetFont('helvetica', '', 11);
		$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

		//header line

		$pdf->Line(12, 50, 208, 50, $style);
		$pdf->Line(12, 7, 12, 290, $style);
		$pdf->Line(12, 7, 208, 7, $style);
		$pdf->Line(208, 7, 208, 290, $style);
		$pdf->Line(12, 290, 208, 290, $style);

		$pdf->SetXY(60,8);
		$pdf->SetFont('','B','12');
		$pdf->SetTextColor('0','0','0');
		$pdf->MultiCell(100,2,'','0','C');

		$pdf->SetXY(80,15);
		$pdf->SetFont('','','10');
		$pdf->SetTextColor('0','0','0');
		$pdf->MultiCell(80,2,'','0','L');

		$pdf->SetXY(12,10);
		$pdf->SetFont('','','12');
		$pdf->SetTextColor('0','0','0');
		$pdf->SetFillColor('36','108','164');
		$pdf->Cell(195,0, $company_name,0,1,'C');
		
			$pdf->SetXY(12,17);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(195,0,$company_address  ,0,'C');

			$pdf->SetXY(12,22);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(195,0,$comp_address  ,0,'C');

			$pdf->SetXY(12,28);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->Cell(195,0, "Website"." : ".$website_address ,0,1,'C');

			// $pdf->SetXY(95,28);
			// $pdf->SetFont('','','10');
			// $pdf->SetTextColor('0','0','0');
			// $pdf->Cell(0,0,":".$website_address ,0,1,'L');
		
		$pdf->SetXY(12,35);
		$pdf->SetFont('','B','11');
		$pdf->SetTextColor('0','0','0');
		$pdf->MultiCell(195,10,"Pay Slip For",'0','C');

		$pdf->SetXY(12,40);
		$pdf->SetFont('','B','10');
		$pdf->SetTextColor('0','0','0');
		$pdf->MultiCell(195,20,$month.' - '.$year,'0','C');
		
                
		$pdf->SetXY(12,45);
		$pdf->SetFont('','B','10');
		$pdf->SetTextColor('0','0','0');
		$pdf->MultiCell(195,20,$emp_details[0]->first_name,'0','C');

			
    		$oldX_leave=510;

			$pdf->SetXY(20,50);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Employee Number",'0','L');


			$pdf->SetXY(55,50);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,":",'0','L');


			$pdf->SetXY(58,50);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,$emp_details[0]->employee_number,'0','L');
			$oldX_leave=$oldX_leave+30;

			$pdf->SetXY(20,55);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Department",'0','L');

			$pdf->SetXY(55,55);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,":",'0','L');

			$pdf->SetXY(58,55);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,$department,'0','L');
			$oldX_leave=$oldX_leave+30;

			$pdf->SetXY(20,60);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Position",'0','L');

			$pdf->SetXY(55,60);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,":",'0','L');

			$pdf->SetXY(58,60);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,$emp_details[0]->position_name."/".$emp_details[0]->job_title_name,'0','L');
			$oldX_leave=$oldX_leave+30;

			$pdf->SetXY(20,65);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Location",'0','L');

			$pdf->SetXY(55,65);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,":",'0','L');

			$pdf->SetXY(58,65);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,$location_name_emp,'0','L');
			$oldX_leave=$oldX_leave+30;

			$pdf->SetXY(20,70);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Bank Details",'0','L');

			$pdf->SetXY(55,70);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,":",'0','L');

			$pdf->SetXY(58,70);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,$bank_name,'0','L');
                        $pdf->SetXY(20,75);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Attendance Days",'0','L');

			$pdf->SetXY(55,75);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,":",'0','L');

			$pdf->SetXY(58,75);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,$total_days,'0','L');
                        
                        $pdf->SetXY(20,80);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Date of Joining",'0','L');

			$pdf->SetXY(55,80);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,":",'0','L');

			$pdf->SetXY(58,80);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,date('d-m-Y',strtotime($emp_details[0]->date_of_joining)),'0','L');
                        
                        
			$oldX_leave=$oldX_leave+30;

		 	$pdf->SetXY(110,50);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,"Income Tax Number (PAN)",'0','L');

			$pdf->SetXY(170,50);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,":",'0','L');

			$pdf->SetXY(175,50);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,$pan_number,'0','L');

			$pdf->SetXY(110,55);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,"Universal Account Number (UAN)",'0','L');


			$pdf->SetXY(170,55);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,":",'0','L');

		

			$pdf->SetXY(175,55);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,$uan_no,'0','L');

			$pdf->SetXY(110,60);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,"PF account number",'0','L');

			

			$pdf->SetXY(170,60);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,":",'0','L');

			$pdf->SetXY(175,60);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,$pf_no,'0','L');

			$pdf->SetXY(110,65);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,"ESI Number",'0','L');
			
			$pdf->SetXY(170,65);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,":",'0','L');

			$pdf->SetXY(175,65);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,$esi_no,'0','L');


			$pdf->SetXY(110,70);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,"PR Account Number (PRAN)",'0','L');

			$pdf->SetXY(170,70);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,":",'0','L');

			$pdf->SetXY(175,70);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,$pf_no,'0','L');

            $pdf->SetXY(110,75);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,"Present Days",'0','L');

			$pdf->SetXY(170,75);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,":",'0','L');

			$pdf->SetXY(175,75);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,$no_of_present_days,'0','L');
                        
			$leave=$total_days-$no_of_present_days;
			$pdf->SetXY(110,80);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,"LOP",'0','L');

			$pdf->SetXY(170,80);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,":",'0','L');

			$pdf->SetXY(175,80);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(400,10,$leave,'0','L');
                        
			$pdf->Line(12,85,208,85,$style);
			$pdf->Line(12,93,208,93,$style);
			$pdf->Line(12,100,208,100,$style);

			$pdf->Line(110,93,110,220,$style);
			$pdf->Line(85,100,85,220,$style);
			$pdf->Line(185,100,185,220,$style);

			$pdf->Line(55,100,55,220,$style);
			$pdf->Line(150,100,150,220,$style);

			
			$pdf->Line(12,200,208,200,$style);
			$pdf->Line(12,210,208,210,$style);
			$pdf->Line(12,220,208,220,$style);
			
			if($emp_details[0]->group_type != '14'){
			
			$pdf->SetXY(25,85);
			$pdf->SetFont('','B','9');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(500,10,'Taken Leave','0','L');
			
				$pdf->SetXY(125,85);
			$pdf->SetFont('','B','9');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(500,10,'Remaining Leave','0','L');
			
             $pdf->SetXY(12,88);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			
			
			$pdf->MultiCell(500,10,"Casual Leave-".$cl."     Earn Leave-".$el."     Comp Off-".$co."             Casual Leave-".$leave_balance[0]->causal_leave."     Earn Leave-".$leave_balance[0]->earn_leave."     Comp Off-".$leave_balance[0]->comp_off_leave,'0','L');  
			}
			
			$pdf->SetXY(20,95);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Earnings",'0','L');
			
			$pdf->SetXY(65,95);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Gross",'0','L');

			$pdf->SetXY(90,95);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Actual",'0','L');

			$pdf->SetXY(120,95);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Deductions",'0','L');
 
             $pdf->SetXY(160,95);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Gross",'0','L');
			
			$pdf->SetXY(190,95);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Actual",'0','L');

			/**** Earning ****/
if($emp_basic[0]->basic_pay!=0)
{
			$pdf->SetXY(15,105);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Basic",'0','L');

			$pdf->SetXY(65,105);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($payslip_details[0]->basic_salary+$basic_arrear),'0','R');
			
			$pdf->SetXY(40,105);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($emp_basic[0]->basic_pay),'0','R');
		}
		if($emp_basic[0]->hra!=0)
{

			$pdf->SetXY(15,110);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"HRA",'0','L');

			$pdf->SetXY(65,110);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($payslip_details[0]->hra+$hra_arrear),'0','R');
			
			$pdf->SetXY(40,110);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($emp_basic[0]->hra),'0','R');

}
if($emp_basic[0]->da!=0)
{
			$pdf->SetXY(15,115);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"DA",'0','L');

			$pdf->SetXY(65,115);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($payslip_details[0]->da+
                                $da_arrear),'0','R');
			
			$pdf->SetXY(40,115);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($emp_basic[0]->da),'0','R');
		}
$y=110;
  
$total_earning=$payslip_details[0]->basic_salary+$payslip_details[0]->hra+$payslip_details[0]->da+$basic_arrear+$hra_arrear+$da_arrear;
$actual_earning=$emp_basic[0]->basic_pay+$emp_basic[0]->hra+$emp_basic[0]->da;
$oldY=$pdf->getY()-5;
			//dd($allowance_data);
                  if(count($allowance_data)>0){
                       foreach($allowance_data as $k2=>$v2){
                           if(isset($v2['actual']))
                           {
                           	if($v2['actual']!=0){
                        $oldY=$oldY;
                        $pdf->SetXY(15,$oldY);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,$k2,'0','L');
			$pdf->SetXY(40,$oldY);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			if(isset($v2['actual']))
			{
			$actual=$v2['actual'];
			}else{
				$actual=0;
			}
			$pdf->MultiCell(40,10,number_format($actual),'0','R');
            $pdf->SetXY(65,$oldY);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
                        if(isset($v2['arrear']))
			{
			$arrear=$v2['arrear'];
			}else{
				$arrear=0;
			}
			$pdf->MultiCell(40,10,number_format($v2['amount']+$arrear),'0','R');
		        $total_earning=$v2['amount']+$total_earning+$arrear;
		        $actual_earning=$actual+$actual_earning;
                           $oldY=$pdf->getY()-5;
                 $pdf->SetXY(15,$oldY);
                       }
                   }
                       }
                  }    else{
                       $pdf->SetXY(15,120);
                  } 

		if($payslip_details[0]->annual_allowance !=0){
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Annual Allowance",'0','L');

			$pdf->SetXY(65,$oldY);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($payslip_details[0]->annual_allowance),'0','R');
			
				$pdf->SetXY(40,$oldY);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($emp_basic[0]->annual_allowance),'0','R');
			   $actual_earning=$emp_basic[0]->annual_allowance+$actual_earning;
                 $total_earning=$payslip_details[0]->annual_allowance+$total_earning;      
                        $oldY=$pdf->getY()-5;
                            $pdf->SetXY(15,$oldY);
                        }
          if($payslip_details[0]->gratuity !=0)
          {
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Gratuity Provision",'0','L');

			$pdf->SetXY(65,$oldY);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($payslip_details[0]->gratuity),'0','R');
			
			$pdf->SetXY(40,$oldY);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($emp_basic[0]->gratuity),'0','R');
			$actual_earning=$emp_basic[0]->gratuity+$actual_earning;
                 $total_earning=$payslip_details[0]->gratuity+$total_earning; 
			/********/
}
  /*** total earnings ***/
			$pdf->SetXY(15,202);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Total Earnings",'0','L');

			$pdf->SetXY(65,202);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($total_earning),'0','R');
			
			$pdf->SetXY(40,202);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($actual_earning),'0','R');

		$total_deduction_basic=0;
			/**** Deductions ****/
			$k=105;
if($emp_basic[0]->pf_amount!=0){
			$pdf->SetXY(113,$k);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"PF",'0','L');

			$pdf->SetXY(163,$k);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($amount_employee),'0','R');
			
			$pdf->SetXY(133,$k);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($emp_basic[0]->pf_amount),'0','R');
			$total_deduction_basic=$total_deduction_basic+$emp_basic[0]->pf_amount;
}
if($emp_basic[0]->pf_amount!=0){
$k=$pdf->getY();
}else{
		$k=110;
}
if($payslip_details[0]->esi!=0){
			$pdf->SetXY(113,$k-5);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			if($esi_cutoff==1){
			    $pdf->MultiCell(100,10,"ESI-CUTOFF",'0','L');
			}else{
			    $pdf->MultiCell(100,10,"ESI",'0','L');
			}
			

			$pdf->SetXY(163,$k-5);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($payslip_details[0]->esi+$esi_arrear),'0','R');
			
			$pdf->SetXY(133,$k-5);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($emp_basic[0]->esi_amount),'0','R');
			$total_deduction_basic=$total_deduction_basic+$emp_basic[0]->esi_amount;
}
if($emp_basic[0]->esi_amount!=0 || $emp_basic[0]->pf_amount!=0){
$k=$pdf->getY();
}else{
		$k=110;
}
if($payslip_details[0]->tax_amount !=0){
			$pdf->SetXY(113,$k-5);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"T.D.S",'0','L');

			$pdf->SetXY(163,$k-5);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($payslip_details[0]->tax_amount),'0','R');
			
			$pdf->SetXY(133,$k-5);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,0,'0','R');
			
			 $oldY=$pdf->getY()-5;
                            $pdf->SetXY(15,$oldY);
                            $total_deduction_basic=$total_deduction_basic+0;

	}	
if($emp_basic[0]->esi_amount!=0 || $emp_basic[0]->pf_amount!=0 || $payslip_details[0]->tax_amount !=0 ){
$k=$pdf->getY();
}else{
		$k=110;
}	
if($payslip_details[0]->loan_deduction !=0){
			$pdf->SetXY(113,$k-5);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Advance",'0','L');

			$pdf->SetXY(163,$k-5);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($payslip_details[0]->loan_deduction),'0','R');
			
			$pdf->SetXY(133,$k-5);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,0,'0','R');
			$total_deduction_basic=$total_deduction_basic+0;

	}	
if($emp_basic[0]->esi_amount!=0 || $emp_basic[0]->pf_amount!=0 || $payslip_details[0]->tax_amount !=0 || $payslip_details[0]->loan_deduction !=0){
$k=$pdf->getY();
}else{
		$k=110;
}

	if($professional_tax!=0){
	                  $pdf->SetXY(113,$k-5);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Professional Tax",'0','L');

			$pdf->SetXY(163,$k-5);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($payslip_details[0]->pt+$pt_arrear),'0','R');
			
			$pdf->SetXY(133,$k-5);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($professional_tax),'0','R');
			$total_deduction_basic=$total_deduction_basic+$professional_tax;
            }
		if($emp_basic[0]->esi_amount!=0 || $emp_basic[0]->pf_amount!=0 || $payslip_details[0]->tax_amount !=0 || $payslip_details[0]->loan_deduction !=0 || $payslip_details[0]->pt!=0){
$k=$pdf->getY();
}else{
		$k=110;
}
            if($volunter_pf!=0){
            $pdf->SetXY(113,$k-5);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Voluntary PF",'0','L');

			$pdf->SetXY(163,$k-5);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($volunter_pf),'0','R');
			
			
			$pdf->SetXY(133,$k-5);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($emp_basic[0]->volunter_pf),'0','R');
			$total_deduction_basic=$total_deduction_basic+$emp_basic[0]->volunter_pf;
		    }
		  
		$total_deduction=0;
                $oldY=$pdf->getY();
			/********/
if(count($deduction_data)>0){
    
    //dd($deduction_data);
                       foreach($deduction_data as $k2=>$v2){
                           if(isset($v2['actual']))
                           {
                           	if($v2['actual']!=0){
                           $oldY=$oldY-5;
            $pdf->SetXY(113,$oldY);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,$k2,'0','L');

             $pdf->SetXY(133,$oldY);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			if(isset($v2['actual']))
			{
			$actual=$v2['actual'];
			}else{
				$actual=0;
			}
			$pdf->MultiCell(40,10,number_format($actual),'0','R');
			
			 $pdf->SetXY(163,$oldY);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($v2['amount']),'0','R');
			
		        $total_deduction=$v2['amount']+$total_deduction;
				 $total_deduction_basic=$actual+$total_deduction_basic;
                                 $oldY=$pdf->getY();
                       }
                   }
                       else if($v2['amount']!=0)
                       {
                       	 
            $oldY=$oldY-5;
            $pdf->SetXY(113,$oldY);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,$k2,'0','L');

             $pdf->SetXY(133,$oldY);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');


			 $pdf->SetXY(163,$oldY);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($v2['amount']),'0','R');
			
		        $total_deduction=$v2['amount']+$total_deduction;
				// $total_deduction_basic=$v2['amount']+$total_deduction_basic;
                                 $oldY=$pdf->getY();
                   
                       }
                       }
                  }    else{
                       $pdf->SetXY(113,135);
                  } 


			/*** total deduction ***/
		    $total_deduction=$esi_arrear+$pt_arrear+$total_deduction+$payslip_details[0]->pt+$volunter_pf+$payslip_details[0]->loan_deduction+$payslip_details[0]->tax_amount+$payslip_details[0]->esi+$amount_employee;
			
			$pdf->SetXY(113,202);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Total Deductions",'0','L');

			$pdf->SetXY(163,202);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($total_deduction),'0','R');
			
				$pdf->SetXY(133,202);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format($total_deduction_basic),'0','R');
		
			/********/



			/*** Net Amount ***/
           
			$pdf->SetXY(113,212);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Net Amount",'0','L');

			$pdf->SetXY(163,212);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format(round($payslip_details[0]->net_salary+$net_salary_arrear)),'0','R');
		
			$pdf->SetXY(133,212);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(40,10,number_format(round($emp_basic[0]->net_pay)),'0','R');
		
			/********/
			//$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                        if($payslip_details[0]->net_salary>0){
                            $number=$payslip_details[0]->net_salary+$net_salary_arrear;
                             $no = round($payslip_details[0]->net_salary+$net_salary_arrear);
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
        if ($number) 
        {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
        } 
        else 
            $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $points = ($point) ?
    "." . $words[$point / 10] . " " .
    $words[$point = $point % 10] : '';
    $amount_word= $result . "Rupees Only";
                            
                        }else{
                            $amount_word ='';
                        }
			/*** Amount in words ***/

			$pdf->SetXY(13,225);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"Amount (in words):",'0','L');

			$pdf->SetXY(13,230);
			$pdf->SetFont('','','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,$amount_word,'0','L');

			$pdf->SetXY(117,225);
			$pdf->SetFont('','B','10');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(93,10,"for ".$company_name,'0','L');

			$pdf->SetXY(80,275);
			$pdf->SetFont('','B','11');
			$pdf->SetTextColor('0','0','0');
			$pdf->MultiCell(100,10,"System Generated Payslip",'0','L');

			$i=0;
			$oldY=0;

//*********************** DECIDINGE THE PAGE ERROR OR CONTENT****////


ob_end_clean();

if(isset($print)){

    if($print=='PRINT')
    {    

        $pdf->Output('Payslip.pdf','I');
		exit;
    }
    else
    {
    	// dd("655");                     
        $m=date('m');
        $pdf->Output('uploads/payslip/payslip_'.$m.'_'.$emp_details[0]->employee_number.'.pdf', 'F'); 
  
      
    }
}
else
{
	  $pdf->Output('Payslip.pdf','I');
		exit;
}


?>
