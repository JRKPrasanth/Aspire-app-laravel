<?php
//dd("Raji");\

ob_start();
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
//$pdf->SetTitle('TCPDF Example 007');
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetAutoPageBreak(true, 0);
// set image scale factor

if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  
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
        
        $image_file = public_path().'/images/'.$this->logo;
       $this->Image($image_file, 10	, 10, 48, 25, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
      
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
$pdf->setLogo($company_logo);

// add a page
$pdf->AddPage('P', $resolution);

 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

$pdf->SetXY(75,8);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0,$company_name,'0','C');


//$company_address=$address.",".$street_name.",".$city.",".$state.",".$country;

$pdf->SetXY(84,15);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2,$company_address,'0','L');


$pdf->SetXY(55,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(60,0, "Company UIN No:" .$cin_no ,0,1,'R');

$pdf->SetXY(125,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "GST No: " .$gst_no ,0,1,'M');

$pdf->Line(12, 38, 208, 38, $style);


$pdf->SetXY(95,39);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('255','255','255');
$pdf->SetFillColor('36','108','164');
$pdf->Rect(12, 38, 196, 8, 'F');
$pdf->Cell(104,0, "Sales Enquiry" ,0,1,'M');
$pdf->Line(12, 46, 208, 46, $style);



 $pdf->Line(12, 55, 112, 55, $style);	
	
$pdf->SetXY(18,48);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "TO" ,0,1,'L');

$oldY2=0;
	
$pdf->SetXY(15,56);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(280,10,$customer_name,'0','1');


$pdf->SetXY(15,61);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,2,$to_address,'0','1');
$oldY2 = $pdf->getY();	
	
$pdf->SetXY(15,65);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(280,10,$bill_to_address1,'0','1');
$oldY2 = $pdf->getY();	
	

$pdf->SetXY(112,48);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Inquiry No" ,0,1,'L');
	
$pdf->SetXY(142,48);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, ": ".$header[0]->inquiry_no ,0,1,'L');
	
$pdf->SetXY(112,55);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Inquiry Date" ,0,1,'L');

$pdf->SetXY(142,55);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, ": ".$header[0]->inquiry_date ,0,1,'L');


$pdf->SetXY(112,62);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Inquiry Type" ,0,1,'L');

$pdf->SetXY(142,62);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, ": ".$header[0]->inquiry_type ,0,1,'L');


$pdf->SetXY(18,61);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('255','255','255');
$pdf->SetFillColor('36','108','164');
$pdf->Rect(12, 85, 196, 8, 'F');
//$pdf->Cell(104,0, " So No " ,0,1,'M');
	
$pdf->SetXY(12,87);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('255','255','255');
$pdf->Cell(104,0, "S.NO" ,0,1,'L');
	
$pdf->SetXY(38,87);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('255','255','255');
$pdf->Cell(104,0, "Description Of Goods" ,0,1,'L');


$pdf->SetXY(145,87);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('255','255','255');
$pdf->Cell(104,0, "Unit" ,0,1,'L');



$pdf->SetXY(178,87);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('255','255','255');
$pdf->Cell(104,0, "Qty" ,0,1,'L');


//vertical s.no
$pdf->Line(112, 46, 112 , 85);	 
$pdf->Line(25, 93, 25 , 290);	 	 
$pdf->Line(138, 93, 138 , 290);	 	 
$pdf->Line(168, 93, 168 , 290);	 	 
	//end 
 


$i=0;
$oldY=0;

foreach($lines as $key1=> $value) 
{
if($oldY==0) $y =85;
else $y = $oldY-3;	
$i++;

$pdf->SetXY(14,$y+10);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, $i,0,1,'L');
$y=$y+5;

$pdf->SetXY(30,$y+5);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(82,2, $value->product_code."-".$value->concatenated_product ,0,'L');
$oldY=$pdf->getY();

$pdf->SetXY(143,$y+5);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, $value->uom_code ,0,1,'L');

$pdf->SetXY(178,$y+5);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, $value->required_qty ,0,1,'L');

//dd($oldY);


if($oldY>=270){
    
$pdf->AddPage();

 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

$pdf->SetXY(75,8);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0,$company_name,'0','C');


//$company_address=$address.",".$street_name.",".$city.",".$state.",".$country;

$pdf->SetXY(84,15);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2,$company_address,'0','L');


$pdf->SetXY(55,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(60,0, "Company UIN No:" .$cin_no ,0,1,'R');

$pdf->SetXY(125,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "GST No: " .$gst_no ,0,1,'M');

$pdf->Line(12, 38, 208, 38, $style);


$pdf->SetXY(95,39);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('255','255','255');
$pdf->SetFillColor('36','108','164');
$pdf->Rect(12, 38, 196, 8, 'F');
$pdf->Cell(104,0, "Sales Enquiry" ,0,1,'M');
$pdf->Line(12, 46, 208, 46, $style);



 $pdf->Line(12, 55, 112, 55, $style);	
	
$pdf->SetXY(18,48);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "TO" ,0,1,'L');

$oldY2=0;
	
$pdf->SetXY(15,56);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(280,10,$customer_name,'0','1');


$pdf->SetXY(15,61);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,2,$to_address,'0','1');
$oldY2 = $pdf->getY();	
	
$pdf->SetXY(15,65);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(280,10,$bill_to_address1,'0','1');
$oldY2 = $pdf->getY();	
	

$pdf->SetXY(112,48);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Inquiry No" ,0,1,'L');
	
$pdf->SetXY(142,48);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, ": ".$header[0]->inquiry_no ,0,1,'L');
	
$pdf->SetXY(112,55);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Inquiry Date" ,0,1,'L');

$pdf->SetXY(142,55);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, ": ".$header[0]->inquiry_date ,0,1,'L');


$pdf->SetXY(112,62);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Inquiry Type" ,0,1,'L');

$pdf->SetXY(142,62);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, ": ".$header[0]->inquiry_type ,0,1,'L');


$pdf->SetXY(18,61);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('255','255','255');
$pdf->SetFillColor('36','108','164');
$pdf->Rect(12, 85, 196, 8, 'F');
//$pdf->Cell(104,0, " So No " ,0,1,'M');
	
$pdf->SetXY(12,87);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('255','255','255');
$pdf->Cell(104,0, "S.NO" ,0,1,'L');
	
$pdf->SetXY(38,87);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('255','255','255');
$pdf->Cell(104,0, "Description Of Goods" ,0,1,'L');


$pdf->SetXY(145,87);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('255','255','255');
$pdf->Cell(104,0, "Unit" ,0,1,'L');



$pdf->SetXY(178,87);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('255','255','255');
$pdf->Cell(104,0, "Qty" ,0,1,'L');


//vertical s.no
$pdf->Line(112, 46, 112 , 85);	 
$pdf->Line(25, 93, 25 , 290);	 	 
$pdf->Line(138, 93, 138 , 290);	 	 
$pdf->Line(168, 93, 168 , 290);	 	 
	//end 
$oldY=$pdf->getY();
}
}  

if(count($terms_condition)>0){
 $pdf->addPage();

	
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

$pdf->SetXY(75,8);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0,$company_name,'0','C');


//$company_address=$address.",".$street_name.",".$city.",".$state.",".$country;

$pdf->SetXY(84,15);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2,$company_address,'0','L');


$pdf->SetXY(55,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(60,0, "Company UIN No:" .$cin_no ,0,1,'R');

$pdf->SetXY(125,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "GST No: " .$gst_no ,0,1,'M');

$pdf->Line(12, 38, 208, 38, $style);


$pdf->SetXY(95,39);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('255','255','255');
$pdf->SetFillColor('36','108','164');
$pdf->Rect(12, 38, 196, 8, 'F');
$pdf->Cell(104,0, "Sales Enquiry" ,0,1,'M');
$pdf->Line(12, 46, 208, 46, $style);

$pdf->SetXY(15,50);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Terms and Condition :" ,0,1,'L');
$oldY1=0;
foreach($terms_condition as $k11=>$v11){
//	for($j=0;$j<38;$j++){
if($oldY1==0) $y =58;
else $y = $oldY1+2;
	
$pdf->SetXY(20,$y+1);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $v11->element_content ,0,1,'L');
//$pdf->Cell(0,0, $j ,0,1,'L');
$oldY1=$pdf->getY();
	
	if($oldY1 > 280)
	{
		$oldY1 = 0;
		$pdf->addPage();

 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

$pdf->SetXY(75,8);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0,$company_name,'0','C');


//$company_address=$address.",".$street_name.",".$city.",".$state.",".$country;

$pdf->SetXY(84,15);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,2,$company_address,'0','L');


$pdf->SetXY(55,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(60,0, "Company UIN No:" .$cin_no ,0,1,'R');

$pdf->SetXY(125,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "GST No: " .$gst_no ,0,1,'M');

$pdf->Line(12, 38, 208, 38, $style);


$pdf->SetXY(95,39);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('255','255','255');
$pdf->SetFillColor('36','108','164');
$pdf->Rect(12, 38, 196, 8, 'F');
$pdf->Cell(104,0, "Sales Enquiry" ,0,1,'M');
$pdf->Line(12, 46, 208, 46, $style);

	
	}
	
}

}

ob_end_clean();
 	   
if($print=='PRINT')
{			         
 $pdf->Output('name.pdf','I');
 $pdf->close();

exit();
}
else
{
	
  $filename="uploads/salesenquiry/S_".$inquiry_no.".pdf";
  // dd($filename);
  $pdf->Output('uploads/salesenquiry/S_'.$inquiry_no.'.pdf', 'F');
}



?>
