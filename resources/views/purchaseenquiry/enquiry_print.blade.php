<?php

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
ob_start();


// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// set default header data
$pdf->SetTitle('PO ENQUIRY ');
$pdf->SetAutoPageBreak(true, 0);
// set image scale factor

// set some language-dependent strings (optional)
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
public $company_logo_name;

    public function setLogo($company_logo){
        $this->logo = $company_logo;
    }
//Page header
    public function Header() {
        // Logo
//        $image_file = public_path().'/images/jrks.png';
         $image_file = public_path().'/images/'.$this->logo;
       $this->Image($image_file, 15, 10, 35, 23, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 14);
        // Title

        //$this->Cell(0, 5, 'PREMIUM COATINGS AND CHEMICALS PVT.LTD', 0, false, 'R', 0, '', 0, false, 'M', 'L');

				// $this->SetFont('helvetica', '', 10);
		// $this->Cell(0,10, "19,(NP) Sidco Industrial Estate,Ambattur,Chennai 600 098" ,0,1,'R');
		//  $this->Cell(0,5, "Phone No:044-43111101,02,    Fax: 044-43111101" ,0,1,'R');
		 
		
		

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
$pdf->setLogo($company_logo_name);

// add a page
$pdf->AddPage('P', $resolution);

$pdf->SetHeaderData('', '', 'PO ENQUIRY', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);





$pdf->SetXY(55,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(60,0, "Company UIN No: " .$cin_no   ,0,1,'M');

$pdf->SetXY(85,9);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');	
$pdf->Cell(0,0, $company_name ,0,1,'L'); 	


$pdf->SetXY(85,15);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(110,2,$company_address,'0','L');



$pdf->SetXY(145,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "GST No: ".$gst_no ,0,1,'L');

$pdf->Line(12, 38, 208, 38, $style);


$pdf->SetXY(95,39);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('255','255','255');
$pdf->SetFillColor('36','108','164');
$pdf->Rect(12, 38, 196, 8, 'F');
$pdf->Cell(104,0, " Purchase Enquiry  " ,0,1,'M');
$pdf->Line(12, 46, 208, 46, $style);


$pdf->Line(12, 55, 112, 55, $style);	

$pdf->SetXY(18,48);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "TO" ,0,1,'L');

$pdf->SetXY(112,48);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Enquiry No" ,0,1,'L');
	
$pdf->SetXY(140,48);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, ": ".$enquiry_number ,0,1,'L');


$pdf->SetXY(112,57);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Enquiry Date" ,0,1,'L');


$pdf->SetXY(140,57);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, ": ".$enquiry_date ,0,1,'L');


$pdf->SetXY(112,66);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Enquiry Type" ,0,1,'L');


$pdf->SetXY(140,66);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, ": ".$enquiry_type_id ,0,1,'L');

	
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


$pdf->SetXY(113,87);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('255','255','255');
$pdf->Cell(104,0, "Unit" ,0,1,'L');



$pdf->SetXY(133,87);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('255','255','255');
$pdf->Cell(104,0, "Qty" ,0,1,'L');

$pdf->SetXY(158,87);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('255','255','255');
$pdf->Cell(104,0, "Comments" ,0,1,'L');

	

$oldY2=0;

$pdf->SetXY(15,56);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(280,10,$supplier_name,'0','1');

$pdf->SetXY(15,76);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(280,10,"GST No:  ".$gst_number,'0','1');

$pdf->SetXY(15,62);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,2,$supplier_address,'0','1');


 


$i=0;
$oldY=0;
foreach($result as $key1=> $value) 
{


if ($oldY==0)  $y=95;

else $y = $oldY+1;	
$i++;


$pdf->SetXY(14,$y);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, $i,0,1,'L');


$pdf->SetXY(26,$y);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(84,2,$value['product'],0,'L');
$oldY = $pdf->getY();

$pdf->SetXY(114,$y);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,0,$value['uom'] ,0,'L');

$pdf->SetXY(133,$y);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, $value['qty'] ,0,1,'L');	


$pdf->SetXY(158,$y);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, $value['comments'] ,0,1,'L');	


//$oldY=$pdf->getY()-5;	


    $pdf->Line(112, 46, 112 , 290);	 	
    $pdf->Line(23, 93, 23 , 290);	 	 
    $pdf->Line(130, 93, 130 , 290);	 	 
    $pdf->Line(148, 93, 148 , 290);	 	 


if($oldY>=276)
{  
        $pdf->AddPage();

        $pdf->SetHeaderData('', '', 'PO ENQUIRY', '');
        $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
        $pdf->SetFont('helvetica', '', 11);
        $style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);

$pdf->SetXY(55,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(60,0, "Company UIN No: " .$cin_no   ,0,1,'M');

$pdf->SetXY(85,9);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');	
$pdf->Cell(0,0, $company_name ,0,1,'L'); 	


$pdf->SetXY(85,15);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(110,2,$company_address,'0','L');



$pdf->SetXY(145,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "GST No: ".$gst_no ,0,1,'L');

$pdf->Line(12, 38, 208, 38, $style);


$pdf->SetXY(95,39);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('255','255','255');
$pdf->SetFillColor('36','108','164');
$pdf->Rect(12, 38, 196, 8, 'F');
$pdf->Cell(104,0, "Purchase Enquiry  " ,0,1,'M');
$pdf->Line(12, 46, 208, 46, $style);


$pdf->Line(12, 55, 112, 55, $style);	

$pdf->SetXY(18,48);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "TO" ,0,1,'L');

$pdf->SetXY(112,48);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Enquiry No" ,0,1,'L');
	
$pdf->SetXY(140,48);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, ": ".$enquiry_number ,0,1,'L');


$pdf->SetXY(112,57);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Enquiry Date" ,0,1,'L');


$pdf->SetXY(140,57);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, ": ".$enquiry_date ,0,1,'L');


$pdf->SetXY(112,66);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Enquiry Type" ,0,1,'L');


$pdf->SetXY(140,66);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, ": ".$enquiry_type_id ,0,1,'L');

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

//vertical s.no
$pdf->Line(23, 93, 23 , 230);	 	 
//end 
$pdf->SetXY(38,87);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('255','255','255');
$pdf->Cell(104,0, "Description Of Goods" ,0,1,'L');


$pdf->SetXY(113,87);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('255','255','255');
$pdf->Cell(104,0, "Unit" ,0,1,'L');



	//vertical s.no
$pdf->Line(130, 93, 130 , 230);	 	 
	//end 


$pdf->SetXY(133,87);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('255','255','255');
$pdf->Cell(104,0, "Qty" ,0,1,'L');


//vertical s.no
$pdf->Line(148, 93, 148 , 230);	 	 
	//end 
 
 $pdf->SetXY(158,87);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('255','255','255');
$pdf->Cell(104,0, "Comments" ,0,1,'L');

$oldY2=0;

$pdf->SetXY(15,56);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(280,10,$supplier_name,'0','1');

$pdf->SetXY(15,76);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(280,10,"GST No:  ".$gst_number,'0','1');

$pdf->SetXY(15,62);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(90,2,$supplier_address,'0','1');
  $oldY=$pdf->getY()+27;//second page alignment start
}
}
   
 if(count($terms_condition)>0){
 $pdf->addPage();
$pdf->SetHeaderData('', '', 'PO ENQUIRY', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);





$pdf->SetXY(55,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(60,0, "Company UIN No: " .$cin_no   ,0,1,'M');

$pdf->SetXY(85,9);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');	
$pdf->Cell(0,0, $company_name ,0,1,'L'); 	


$pdf->SetXY(85,15);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(110,2,$company_address,'0','L');



$pdf->SetXY(145,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "GST No: ".$gst_no ,0,1,'L');

$pdf->Line(12, 38, 208, 38, $style);


$pdf->SetXY(95,39);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('255','255','255');
$pdf->SetFillColor('36','108','164');
$pdf->Rect(12, 38, 196, 8, 'F');
$pdf->Cell(104,0, " Purchase Enquiry  " ,0,1,'M');
$pdf->Line(12, 46, 208, 46, $style);

$pdf->SetXY(15,48);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Terms and Condition :" ,0,1,'L');
$oldY1=0;
foreach($terms_condition as $k11=>$v11){
//	for($j=0;$j<38;$j++){
if($oldY1==0) $y =56;
else $y = $oldY1;
	
$pdf->SetXY(17,$y+1);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(185,10, ($k11+1)." ." ,0,'L');
	
$pdf->SetXY(24,$y+1);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(185,10, $v11->element_content ,0,'L');
//$pdf->Cell(0,0, $j ,0,1,'L');
$oldY1=$pdf->getY();
	
	if($oldY1 > 280)
	{
		$oldY1 = 0;
		$pdf->addPage();

$pdf->SetHeaderData('', '', 'PO ENQUIRY', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);





$pdf->SetXY(55,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(60,0, "Company UIN No: " .$cin_no   ,0,1,'M');

$pdf->SetXY(85,9);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');	
$pdf->Cell(0,0, $company_name ,0,1,'L'); 	


$pdf->SetXY(85,15);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(110,2,$company_address,'0','L');



$pdf->SetXY(145,32);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "GST No: ".$gst_no ,0,1,'L');

$pdf->Line(12, 38, 208, 38, $style);


$pdf->SetXY(95,39);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('255','255','255');
$pdf->SetFillColor('36','108','164');
$pdf->Rect(12, 38, 196, 8, 'F');
$pdf->Cell(104,0, " Purchase Enquiry  " ,0,1,'M');
$pdf->Line(12, 46, 208, 46, $style);

	}
	
}

}
  




// ---------------------------------------------------------

//Close and output PDF document


    if($print=="PRINT"){	
//dd($print);           
$pdf->Output('name.pdf','I');
exit;
}else{
		//dd($print);   
$filename="uploads/P_".$enquiry_number.".pdf";
$pdf->Output("uploads/purchaseenquiry/P_".$enquiry_number.".pdf",'F');

}
$pdf->close();
   ob_end_clean();
/* end */
    
//$pdf->Output('example_007.pdf', 'FI');
//exit();





//============================================================+
// END OF FILE
//============================================================+

/******************************************************************************************/ 
?>
