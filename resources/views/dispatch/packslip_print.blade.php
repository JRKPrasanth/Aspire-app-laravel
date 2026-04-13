<?php
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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
//Function to convert Amount in words End

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

// ---------------------------------------------------------
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  
// add a page
$resolution= array(215, 307);
$pdf->AddPage('P', $resolution);
// set font
$pdf->SetFont('times', '', 12);

class MYPDF extends TCPDF { 
public $company_logo;

//Page header
	public function setLogo($company_logo){
        $this->logo = $company_logo;
    }
    public function Header() {
        // Logo
        $image_file = public_path().'/images/'.$this->logo;
        $this->Image($image_file, 15 , 6, 30, 25, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 12);
        // Title

        $this->MultiCell(0, 1,'', 0,'R');
        //$this->MultiCell(0, 2,'ROTOFAB WORKS', 0,'R');
        //       $this->SetFont('helvetica', '', 10);
        //$this->MultiCell(0,3, "FACTORY,571/1A,571/2A (CRPF ROAD,Kathirnaikenpalayam" ,0,'R');
       // $this->MultiCell(0,3, "Road,FACTORY,Thoppampatti (PO),Coimbatore,Tamil Nadu,INDIA",0,'R');

       

        //$this->MultiCell(0,4, "Phone No:044-43111101,02,    Fax: 044-43111101" ,0,'R');
         
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
$ii=0;

foreach($result as $key=>$value5)

{
	$mpq_qty=$value5->mpq_qty;
	$issue_qoh=$value5->issue_qoh;
	$box=explode(",", $value5->box_no);
	$bq=count($box);
	foreach ($box as $k => $v) {
		if($mpq_qty>=$issue_qoh)
		{
			$value5->issue_qoh=$issue_qoh;
		}
		else
		{
			$issue_qoh=$issue_qoh-$mpq_qty;
			$value5->issue_qoh=$mpq_qty;
		}
	if($ii==1)
		$pdf->addPage();
	$ii=1;	
//$pdf->SetHeaderData('', '', 'CORRESPONDENCE LETTER', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);


$pdf->Line(12, 31, 208, 31, $style);

$pdf->SetXY(50,8);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(160,0,$company_name,'0','C');


$pdf->SetXY(70,12.5);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,2,$company_address,'0','C');

$pdf->SetXY(85,31);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Packing List" ,0,1,'M');

/*************** Cin and Gstin *****************/

$x=235;$y=235;$j=0;
$header_company=array('CIN:','GSTIN/UIN : '.$gst_no);
foreach($header_company as $key=>$value)
	{
	
			$pdf->SetXY(60,$y-214);
			$pdf->SetFont('','B','8');
			$pdf->SetTextColor('0','0','0');    
			$pdf->MultiCell(50,2, $value ,0,'L');
			$y=$y+4;
	}

/******************* End **********************/

/*********** state code And Email **************/

$x=235;$y=235;
$header_com_mail=array('STATE CODE : '.$state_no,'E-MAIL : '.$e_mail);
foreach($header_com_mail as $key=>$value)
	{
			$pdf->SetXY(140,$y-214);
			$pdf->SetFont('','B','8');
			$pdf->SetTextColor('0','0','0');    
			$pdf->MultiCell(50,2, $value ,0,'L');
			$y=$y+4.5;
	}

/******************* End **********************/
	$pdf->Line(12,185,208,185,$style);
		
$pdf->Line(12, 37, 208, 37, $style);
$pdf->Line(12, 95, 208, 95, $style);
$pdf->Line(110, 48, 208, 48, $style);
$pdf->Line(110, 60, 208, 60, $style);
$pdf->Line(160,37,160,60, $style);
$pdf->Line(185,37,185,48, $style);
$pdf->Line(110,37,110,175, $style);
$pdf->Line(160,125,160,140,$style);

$pdf->Line(12, 140, 208, 140, $style);
$pdf->Line(12, 175, 208, 175, $style);
$pdf->Line(110,120,208,120,$style);
$pdf->Line(110,125,208,125,$style);

/* header data */
$pdf->Line(12,150,110,150,$style);
$pdf->Line(12, 160, 110, 160, $style);
$pdf->Line(60,255,60,140,$style);
$pdf->Line(85,255,85,175,$style);
$pdf->Line(160,255,160,175,$style);
$pdf->Line(185,255,185,175,$style);

/*  End */



/************* Packing List and order no **************/

$x=235;$y=235;
$packing=array('Packing List No. & Date:','','InvoiceNo.&Date:','Exporters Ref.');
foreach($packing as $key=>$value)
{
	$pdf->SetXY($x-124,$y-198);
	$pdf->SetFont('','B','8');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(25,2, $value ,0,'L');
	$x=$x+25;
}
	$pdf->SetXY(160,41);
	$pdf->SetFont('','B','8');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(25,2, "$dispatch_number / $dispatch_date" ,0,'L');

	$pdf->SetXY(112,49);
	$pdf->SetFont('','B','8');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(25,2, "Order No.&Date" ,0,'L');

	$pdf->SetXY(112,55);
	$pdf->SetFont('','B','8');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(45,2, "$sales_order_no / $sales_order_date" ,0,'L');

/************************ End ***********************/

/********* company address ***********/

	$x=235;$y=235;
	$exporter=array('Exporter :',''.$company_name,'(Formerly known as '.$company_name.'.)','Regd office : '.$company_address,'Works at : '.$company_address,'Phone : '.$phone,'Mail : '.$e_mail,'Web : '.$web,'CIN : '.$cin,'GSTIN /UIN : '.$company_gst_no);
foreach($exporter as $key=>$value)
{
	$pdf->SetXY(12,$y-198);
	$pdf->SetFont('','B','7.5');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(100,2, $value ,0,'L');
	$y=$y+6;
}

/************* End *****************/

/******** Consignee ****************/

	$x=235;$y=235;
if($customer_name!=""){
$customer_name=$customer_name;	
}else{
$customer_name=$empname;	
}
	$consignee=array('Consignee :',''.$customer_name,''.$ship_to_address_1.'','Phone : '.$contact_number,'Mail :');
foreach($consignee as $key=>$value)
{
	$pdf->SetXY(12,$y-138);
	$pdf->SetFont('','B','8');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(50,2, $value ,0,'L');
	$y=$y+6;
}

/************ End ******************/

/********** other Reference ********/

$x=235;$y=235;
$reference=array('Other Reference(s) :','LETTER OF ACCEPTANCE UNDERTAKING','By Assistant Commissioner Of Central Excise- Pallavaram Division','Reference:-','LUT Register page No.','Commissionerate :');
foreach($reference as $key=>$value)
{
	$pdf->SetXY(112,$y-174);
	$pdf->SetFont('','B','8');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(104,2, $value ,0,'L');
	$y=$y+5;
}

/************ End ******************/

/************ Buyer ****************/

$pdf->SetXY(112,96);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2,"Buyer (if other than consignee)",0,'L');

$pdf->SetXY(112,100);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(96,2,"".$bill_to_address_1,0,'L');

/************ End *****************/


/********** Country Of goods ******/

$x=235;$y=235;
$country=array('Country of Origin of Goods:-','Country of Final Destination:');
foreach($country as $key=>$value)
{
	$pdf->SetXY($x-124,$y-110);
	$pdf->SetFont('','B','8');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(50,2, $value ,0,'L');
	$x=$x+50;
}

	$pdf->SetXY(125,135);
	$pdf->SetFont('','B','8');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(50,2, $loc_country ,0,'L');

	$pdf->SetXY(175,135);
	$pdf->SetFont('','B','8');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(50,2, $ship_country ,0,'L');

/**********   End *****************/

/********** Terms of Delivery and payment *********/

$x=235;$y=235;
$terms=array('Terms of Delivery and Payment','','Received Full payment','Kindly hand over the shipment to consignee');
foreach($terms as $key=>$value)
{
	$pdf->SetXY($x-124,$y-94);
	$pdf->SetFont('','B','8');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(104,2, $value ,0,'L');
	$y=$y+5;
}

/********************* End ************************/

/*********** pre-Carriage ****************/

$x=235;$y=235;
$pre=array('Pre-Carriage By AIR','','Vessel \ Flight No:','','Port of Discharge','','','Marks & Nos.','Container No./C.Seal No');
foreach($pre as $key=>$value)
{
	$pdf->SetXY(12,$y-94);
	$pdf->SetFont('','B','8');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(104,2, $value ,0,'L');
	$y=$y+5;
}

/************** End **********************/

/************* place of receipt **********/

$x=235;$y=235;
$place_pre=array('Place of receipt by Pre- Carrier','','Port of Loading','','Final Destination','','','No. of Pkgs.-');

foreach($place_pre as $key=>$value)
{
	$pdf->SetXY(60,$y-94);
	$pdf->SetFont('','B','8');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(104,2, $value ,0,'L');
	$y=$y+5;
}	
	$pdf->SetXY(60,145);
	$pdf->SetFont('','B','8');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(104,2, "$loc_city / $loc_country",0,'L');

	$pdf->SetXY(60,155);
	$pdf->SetFont('','B','8');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(104,2, "$loc_city / $loc_country",0,'L');

	$pdf->SetXY(60,170);
	$pdf->SetFont('','B','8');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(104,2, "$ship_city / $ship_country",0,'L');

/******************* End ****************/

/************* Description of Goods **************/

$x=235;$y=235;
$des_good=array('Description of Goods','','No. of Unit','Remarks');
foreach($des_good as $key=>$value)
{
	$pdf->SetXY($x-125,176);
	$pdf->SetFont('','B','8');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(104,2, $value ,0,'L');
	$x=$x+27;
}

	$pdf->SetXY(90,180);
	$pdf->SetFont('','B','10');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(104,2, "Siddha System and Cosmetics Products" ,0,'L');

/****************** End **************************/


	//if (is_numeric($totalitem))
	//{
	$pdf->SetXY(68,180);
	$pdf->SetFont('','B','9');
	$pdf->SetTextColor('0','0','0');
	$pdf->MultiCell(125,2, $bq,0,'L');
	//}

// $pdf->Line(120,260,120,255,$style);
// $pdf->Line(120,260,208,260,$style);
// $pdf->Line(12,185,208,185,$style);

$pdf->Line(12,255,208,255,$style);
$pdf->Line(12,268,208,268,$style);

$pdf->Line(160,268,160,290,$style);
//$pdf->SetXY(170,255);
	// $pdf->SetFont('','B','10');
	// $pdf->SetTextColor('0','0','0');    
	// $pdf->MultiCell(104,2, $sum ,0,'L');
	
	$pdf->SetXY(140,255);
	$pdf->SetFont('','B','10');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(104,2, "Total" ,0,'L');

/**************** Weight ************************/

$x=235;$y=235;

$weight=array('Gross Weight :','Net Weight :');
foreach($weight as $key=>$value)
{
	$pdf->SetXY(12,$y+23);
	$pdf->SetFont('','B','10');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(104,2, $value ,0,'L');
	$y=$y+5;
}

/********************** End ********************/

/********** Declaration and Signature **********/

$x=235;$y=235;
$declaration=array('Declaration :','We declare that this PL shows the actual quantity of the goods','described and that all particulars are true and correct.','Date : '.$date);
foreach($declaration as $key=>$value)
{
	$pdf->SetXY(12,$y+33);
	$pdf->SetFont('','B','9');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(125,2, $value ,0,'L');
	$y=$y+5;
}

$x=235;$y=235;
$authorised=array('Signature','AUTHORISED TO SIGN.');
foreach($authorised as $key=>$value)
{
	$pdf->SetXY(165,$y+33);
	$pdf->SetFont('','B','9');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(125,2, $value ,0,'L');
	$y=$y+15;
}

 
$i=0;
$oldY=0;
$sum=0;
//$value = $result[0];
//dd($result);
	// foreach($box as $k=>$v){
	// $lines[$key]->box_nos[$k]=$v;	
	// }


	 $y =180;
		$pdf->SetXY(69,$y+6);
	$pdf->SetFont('','B','9');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(74,2, $v ,0,'L');
	
	$pdf->SetXY(85,$y+6);
	$pdf->SetFont('','B','9');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(74,2, $value5->product ,0,'L');
	//$pdf->MultiCell(74,2, 'dfgfdg' ,0,'L');
 	$oldY=$pdf->getY();
 	$pdf->SetXY(168,$y+6);
	$pdf->SetFont('','B','9');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(125,2, $value5->issue_qoh ,0,'L');
		
	$pdf->SetXY(170,255);
	$pdf->SetFont('','B','10');
	$pdf->SetTextColor('0','0','0');    
	$pdf->MultiCell(104,2, $value5->issue_qoh ,0,'L');

	}

//dd($result);


}
	
//}
	
/******* End ********/

// reset pointer to the last page

//$pdf->AddPage('P', $resolution);
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
