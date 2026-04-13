<?php

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
$resolution= array(215, 307);
$pdf->AddPage('P', $resolution);


//Function to convert Amount in words
function convert_number_to_words($number)
{
$no = round($number);
$point = round($no - $number, 2) * 100;
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

    public $company_logo;

    public function setLogo($company_logo){
        $this->logo = $company_logo;
    }
//Page header
    public function Header() {
        // Logo
        $image_file = public_path().'/images/'.$this->logo;
        $this->Image($image_file, 15, 8, 45, 20, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 14);
        $this->data['print']="PRINT";
        $this->data['print_val'] = '1';
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


$pdf->Line(12, 31, 208, 31, $style);
$pdf->Line(12, 38, 208, 38, $style);

    $pdf->SetXY(80,10);
    $pdf->SetFont('','B','12');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(0,0,$company_name,'0','C');

  $pdf->SetXY(100,25);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(0,0,"GST No: ".$gst_no,'0','C');

$pdf->SetXY(55,25);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(60,0, "Company UIN No:" .$cin_no ,0,1,'R');


    $pdf->SetXY(90,31);
    $pdf->SetFont('','B','12');
    $pdf->SetTextColor('255','255','255');
    $pdf->SetFillColor('32','100','150');
    $pdf->Rect(12, 29.8, 196, 8, 'F');
    $pdf->Cell(0,0, "SO QUOTE " ,0,1,'L');
    //$pdf->SetFillColor('36','108','164');
   

    $pdf->SetXY(15,38);
    $pdf->SetFont('','B','12');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "To " ,0,1,'L');

    $pdf->Line(12, 61, 208, 61, $style);
    //dd($linesdata);
    //$location_address=array("$location_name","$address1","$street_name","$area","$city","$state","country");
    $company=$address_l.",".$street_name_l."".$area_l.",".$city_l.",".$state_l.",".$country_l;
    $company_address = str_replace("", "",$company);

    $pdf->SetXY(87,16);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(120,2,$company_address,'0','L');

    $pdf->SetXY(13,43);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Name & Address Of Consignee  " ,0,1,'L');

    $address=$address_c." , ".$city_c." , ".$state_c." , ".$country_c.",".$pincode_c;
    $customer_address = str_replace(", ", "",$address);
//dd($customer_address);

    $pdf->SetXY(13,47);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(65,2,$customer_name ,0,'L');

    $pdf->SetXY(13,51);
    $pdf->SetFont('','','8.5');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(105,2,$customer_address ,0,'L');

    $pdf->SetXY(12,62);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "phone/Fax   :" ,0,1,'L');

    $pdf->SetXY(38,62);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, $contact_no ,0,1,'L');

    $pdf->SetXY(12,68);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0,"Email           :     ".$email_id,0,1,'L');

    $pdf->SetXY(12,74);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(280,10,"GST No       :     ".$gst_number,'0','L');

    $pdf->Line(115, 38, 115 , 80);	 	 
   
    $pdf->SetXY(115,40);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Quotation No" ,0,1,'L');

    $pdf->SetXY(145,40);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0,": " .$header[0]->quote_no,0,1,'L');

    $pdf->SetXY(115,45);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Quotation Date" ,0,1,'L');

    $pdf->SetXY(145,45);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0,": " .$header[0]->quote_date,0,1,'L');

    $pdf->SetXY(115,50);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Enquiry No" ,0,1,'L');

    $pdf->SetXY(145,50);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0,": " .$inquiry_no,0,1,'L');

    $pdf->SetXY(115,55);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Enquiry Date" ,0,1,'L');

    $pdf->SetXY(145,55);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0,": ".$inquiry_date,0,1,'L');

    $pdf->SetXY(115,63);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Kind Attention        :" ,0,1,'L');

    $pdf->SetXY(115,69);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Phone                    :" ,0,1,'L');

    $pdf->SetXY(115,74);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Email                     :" ,0,1,'L');

    $pdf->Line(12, 80, 208, 80, $style);

    $pdf->Line(12, 88, 208, 88, $style);

    $pdf->Rect(12, 80, 196, 8, 'F');

    $pdf->SetXY(14,82);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('255','255','255');
    $pdf->Cell(0,0, "S.No" ,0,1,'L');

    $pdf->SetXY(30,82);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('255','255','255');		
    $pdf->Cell(0,0, "Description of Goods" ,0,1,'L');

    $pdf->SetXY(95,82);
    $pdf->SetFont('','B','8');
    $pdf->SetTextColor('255','255','255');	
    $pdf->Cell(0,0, "HSN / SAC" ,0,1,'L');

    $pdf->SetXY(114 ,82);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('255','255','255');		
    $pdf->Cell(0,0, "Qty" ,0,1,'L');

    $pdf->SetXY(132,82);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('255','255','255');
    $pdf->Cell(0,0, "Unit" ,0,1,'L');

    $pdf->SetXY(150,82);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('255','255','255');
    $pdf->Cell(0,0, "Rate" ,0,1,'L');

    $pdf->SetXY(169,82);
    $pdf->SetFont('','B','8');
    $pdf->SetTextColor('255','255','255');
    $pdf->Cell(0,0, "GST %" ,0,1,'L');

    $pdf->SetXY(189 ,82);
    $pdf->SetFont('','B','8');
    $pdf->SetTextColor('255','255','255');
    $pdf->Cell(0,0, "Amount" ,0,1,'L');

    

$i=0;
$oldY=0;
$dis_amt=0;
$net_amount=0;
$total_amount=0;
$convert=0;
$tax_amount=0;
//dd($linesdata);
foreach($linesdata as $value) 
{   
   
if($oldY==0) $y =91;
else $y = $oldY+3;	
$i++;

$pdf->Setxy(19,$y);
$pdf->Setfont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->cell(0,0,$value['lineno'],0,1,'L');

  //$y=$y+20;



$pdf->SetXY(28,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(65,2, $value['productid'] ,0,'L');
$oldY=$pdf->getY();

$pdf->SetXY(96,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->cell(0,0,$value['hsn_code'],0,0,'L');

$pdf->SetXY(115,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->cell(0,0,$value['qty'],0,0,'L');

$pdf->SetXY(150,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$price=number_format($value['unitprice'],\Session::get("decimal"));	
$price1= str_replace(',', '',$price);	
$pdf->cell('0','0',$price1,'0','0','L');
    
$pdf->SetXY(132,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->cell('0','0',$value['uom_code'],'0','0','L');

$pdf->SetXY(170,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->cell('0','0',$value['gst'].'','0','0','L');
 
$pdf->SetXY(190,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$tot=number_format($value['total'],\Session::get("decimal"));
$tot1=str_replace(',', '',$tot);
$pdf->cell('0','0',$tot1,'0','0','L');

$total_amount+=$value['total'];
$tax_amount+=$value['tax_amount'];

if($oldY>=200)
{
$pdf->Line(25, 80, 25, 290);
$pdf->Line(95, 80, 95, 290);
$pdf->Line(112, 80, 112, 290);
$pdf->Line(126, 80, 126, 290);
$pdf->Line(147, 80, 147, 290);
$pdf->Line(167, 80, 167, 290);
$pdf->Line(187, 80, 187, 290);
}   


   




if($oldY>=270)
{
	$pdf->AddPage();

// add a page

$pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);


$pdf->Line(12, 31, 208, 31, $style);
$pdf->Line(12, 38, 208, 38, $style);

    $pdf->SetXY(80,10);
    $pdf->SetFont('','B','12');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(0,0,$company_name,'0','C');

	    $company=$address_l.",".$street_name_l."".$area_l.",".$city_l.",".$state_l.",".$country_l;
    $company_address = str_replace("", "",$company);

    $pdf->SetXY(87,16);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(90,2,$company_address,'0','L');
	
  $pdf->SetXY(100,25);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(0,0,"GST No: ".$gst_no,'0','C');

$pdf->SetXY(55,25);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(60,0, "Company UIN No:" .$cin_no ,0,1,'R');


    $pdf->SetXY(90,31);
    $pdf->SetFont('','B','12');
    $pdf->SetTextColor('255','255','255');
    $pdf->SetFillColor('32','100','150');
    $pdf->Rect(12, 29.8, 196, 8, 'F');
    $pdf->Cell(0,0, "SO QUOTE " ,0,1,'L');
    //$pdf->SetFillColor('36','108','164');
   

    $pdf->SetXY(15,38);
    $pdf->SetFont('','B','12');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "To " ,0,1,'L');

    $pdf->Line(12, 61, 208, 61, $style);
    //dd($linesdata);
    //$location_address=array("$location_name","$address1","$street_name","$area","$city","$state","country");


    $pdf->SetXY(13,43);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Name & Address Of Consignee  " ,0,1,'L');

    $address=$address_c." , ".$city_c." , ".$state_c." , ".$country_c.",".$pincode_c;
    $customer_address = str_replace(", ", "",$address);
//dd($customer_address);

    $pdf->SetXY(13,47);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(65,2,$customer_name ,0,'L');

    $pdf->SetXY(13,51);
    $pdf->SetFont('','','8.5');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(105,2,$customer_address ,0,'L');

    $pdf->SetXY(12,62);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "phone/Fax   :" ,0,1,'L');

    $pdf->SetXY(38,62);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, $contact_no ,0,1,'L');

    $pdf->SetXY(12,68);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0,"Email           :     ".$email_id,0,1,'L');

    $pdf->SetXY(12,74);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(280,10,"GST No       :     ".$gst_number,'0','L');

    $pdf->Line(115, 38, 115 , 80);	 	 
   
    $pdf->SetXY(115,40);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Quotation No" ,0,1,'L');

    $pdf->SetXY(145,40);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0,": " .$header[0]->quote_no,0,1,'L');

    $pdf->SetXY(115,45);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Quotation Date" ,0,1,'L');

    $pdf->SetXY(145,45);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0,": " .$header[0]->quote_date,0,1,'L');

    $pdf->SetXY(115,50);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Enquiry No" ,0,1,'L');

    $pdf->SetXY(145,50);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0,": " .$inquiry_no,0,1,'L');

    $pdf->SetXY(115,55);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Enquiry Date" ,0,1,'L');

    $pdf->SetXY(145,55);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0,": ".$inquiry_date,0,1,'L');

    $pdf->SetXY(115,63);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Kind Attention        :" ,0,1,'L');

    $pdf->SetXY(115,69);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Phone                    :" ,0,1,'L');

    $pdf->SetXY(115,74);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Email                     :" ,0,1,'L');

    $pdf->Line(12, 80, 208, 80, $style);

    $pdf->Line(12, 88, 208, 88, $style);

    $pdf->Rect(12, 80, 196, 8, 'F');

    $pdf->SetXY(14,82);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('255','255','255');
    $pdf->Cell(0,0, "S.No" ,0,1,'L');

    $pdf->SetXY(30,82);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('255','255','255');		
    $pdf->Cell(0,0, "Description of Goods" ,0,1,'L');

    $pdf->SetXY(95,82);
    $pdf->SetFont('','B','8');
    $pdf->SetTextColor('255','255','255');	
    $pdf->Cell(0,0, "HSN / SAC" ,0,1,'L');

    $pdf->SetXY(114 ,82);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('255','255','255');		
    $pdf->Cell(0,0, "Qty" ,0,1,'L');

    $pdf->SetXY(132,82);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('255','255','255');
    $pdf->Cell(0,0, "Unit" ,0,1,'L');

    $pdf->SetXY(150,82);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('255','255','255');
    $pdf->Cell(0,0, "Rate" ,0,1,'L');

    $pdf->SetXY(169,82);
    $pdf->SetFont('','B','8');
    $pdf->SetTextColor('255','255','255');
    $pdf->Cell(0,0, "GST %" ,0,1,'L');

    $pdf->SetXY(189 ,82);
    $pdf->SetFont('','B','8');
    $pdf->SetTextColor('255','255','255');
    $pdf->Cell(0,0, "Amount" ,0,1,'L');
    
    $pdf->Line(12, 80, 208, 80, $style);
    
    $pdf->Line(25, 80, 25, 210);
    $pdf->Line(95, 80, 95, 210);
    $pdf->Line(112, 80, 112, 210);
    $pdf->Line(126, 80, 126, 210);
    $pdf->Line(147, 80, 147, 210);
    $pdf->Line(167, 80, 167, 210);
    $pdf->Line(187, 80, 187, 210);


    //$pdf->Line(12, 88, 208, 88, $style);
    
    $oldY=$pdf->getY()+3;

   
}
}

if($oldY>=200)
{

$pdf->SetXY(130,212);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Sub Total" ,0,1,'L');

$pdf->SetXY(190,212);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,$total_amount,0,1,'L');

$pdf->SetXY(130,220);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Total Discount" ,0,1,'L');

$pdf->SetXY(190,220);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,($value['total_discount']) ,0,1,'L');

$pdf->SetXY(130,226);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Tax Amount" ,0,1,'L');

$pdf->SetXY(190,226);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,$value['tax_amount'] ,0,1,'L');



$pdf->SetXY(15,210);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Amount in Words" ,0,1,'L');

$pdf->SetXY(14,217);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, convert_number_to_words($total_amount) ,0,'L');

$pdf->SetXY(130,231);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,5, "Other Tax&Charges" ,0,'L');

//$other_charges

$pdf->SetXY(190,231);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$otax=number_format($value['other_tax'],\Session::get("decimal"));	
$otax1= str_replace(',', '',$otax);	
$pdf->Cell(104,2,$otax1,0,1,'L');

$net_amount=$value['tax_amount']+$total_amount-$value['total_discount'];

$pdf->SetXY(130,236);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Net Amt" ,0,1,'L');

$pdf->SetXY(190,236);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,2, round($net_amount) ,0,1,'L');

$pdf->SetXY(115,246);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "For  $company_name" ,0,1,'L');

$pdf->SetXY(170,265);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Authorised Signatory  " ,0,1,'L');


$pdf->SetXY(12,272);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "$company_name ADMIN  " ,0,1,'L');


$pdf->SetXY(12,280);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Created By  " ,0,1,'L');

$pdf->SetXY(170,281);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Authorised By " ,0,1,'L');

//dd($gstvalue);

$pdf->Line(12, 243, 208, 243, $style);
$pdf->Line(12, 270, 208, 270, $style);
    
$pdf->Line(12, 210, 208, 210, $style);
$pdf->Line(115, 243, 115 , 210);	
$pdf->Line(175, 243, 175 , 210);
$pdf->Line(115, 270, 115 , 243);
    
$pdf->Line(25, 80, 25, 210);
$pdf->Line(95, 80, 95, 210);
$pdf->Line(112, 80, 112, 210);
$pdf->Line(126, 80, 126, 210);
//$pdf->Line(147, 80, 147, 210);
$pdf->Line(167, 80, 167, 210);
$pdf->Line(187, 80, 187, 210);
    

}
else
{

$pdf->SetXY(130,212);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Sub Total" ,0,1,'L');

$pdf->SetXY(190,212);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,$total_amount,0,1,'L');

$pdf->SetXY(130,220);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Total Discount" ,0,1,'L');

$pdf->SetXY(190,220);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,$value['total_discount'] ,0,1,'L');

$pdf->SetXY(130,226);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Tax Amount" ,0,1,'L');

$pdf->SetXY(190,226);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,$value['tax_amount'] ,0,1,'L');


$pdf->SetXY(15,210);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Amount in Words" ,0,1,'L');

$pdf->SetXY(14,217);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(104,0, convert_number_to_words($total_amount) ,0,'L');

$pdf->SetXY(130,231);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(100,5, "Other Tax&Charges" ,0,'L');

//$other_charges

$pdf->SetXY(190,231);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$otax=number_format($value['other_tax'],\Session::get("decimal"));	
$otax1= str_replace(',', '',$otax);	
$pdf->Cell(104,2,$otax1,0,1,'L');

$net_amount=$value['tax_amount']+$total_amount+$value['other_tax']-$value['total_discount'];

$pdf->SetXY(130,236);
$pdf->SetFont('','','12');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Net Amt" ,0,1,'L');

$pdf->SetXY(190,236);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,2, round($net_amount) ,0,1,'L');

$pdf->SetXY(115,246);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "For  $company_name" ,0,1,'L');

$pdf->SetXY(170,265);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Authorised Signatory  " ,0,1,'L');


$pdf->SetXY(12,272);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "$company_name ADMIN  " ,0,1,'L');


$pdf->SetXY(12,280);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Created By  " ,0,1,'L');

$pdf->SetXY(170,281);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Authorised By " ,0,1,'L');
//dd($gstvalue);

$pdf->Line(12, 243, 208, 243, $style);
$pdf->Line(12, 270, 208, 270, $style);
    
$pdf->Line(12, 210, 208, 210, $style);
$pdf->Line(115, 243, 115 , 210);	
$pdf->Line(175, 243, 175 , 210);
$pdf->Line(115, 270, 115 , 243);
    
$pdf->Line(25, 80, 25, 210);
$pdf->Line(95, 80, 95, 210);
$pdf->Line(112, 80, 112, 210);
$pdf->Line(126, 80, 126, 210);
$pdf->Line(147, 80, 147, 210);
$pdf->Line(167, 80, 167, 210);
$pdf->Line(187, 80, 187, 210);
}
// reset pointer to the last page
$pdf->lastPage();




if(count($terms_condition)>0){
 $pdf->addPage();
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);


$pdf->Line(12, 31, 208, 31, $style);
$pdf->Line(12, 38, 208, 38, $style);

    $pdf->SetXY(80,10);
    $pdf->SetFont('','B','12');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(0,0,$company_name,'0','C');

	    $company=$address_l.",".$street_name_l."".$area_l.",".$city_l.",".$state_l.",".$country_l;
    $company_address = str_replace("", "",$company);

    $pdf->SetXY(87,16);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(90,2,$company_address,'0','L');
	
  $pdf->SetXY(100,25);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(0,0,"GST No: ".$gst_no,'0','C');

$pdf->SetXY(55,25);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(60,0, "Company UIN No:" .$cin_no ,0,1,'R');


    $pdf->SetXY(90,31);
    $pdf->SetFont('','B','12');
    $pdf->SetTextColor('255','255','255');
    $pdf->SetFillColor('32','100','150');
    $pdf->Rect(12, 29.8, 196, 8, 'F');
  
	
	

$pdf->SetXY(15,40);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Terms and Condition :" ,0,1,'L');
$oldY1=0;
foreach($terms_condition as $k11=>$v11){
//	for($j=0;$j<38;$j++){
if($oldY1==0) $y =45;
else $y = $oldY1;
	
$pdf->SetXY(17,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(185,10, ($k11+1)." ." ,0,'L');
	
$pdf->SetXY(24,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(185,10, $v11->element_content ,0,'L');
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


$pdf->Line(12, 31, 208, 31, $style);
$pdf->Line(12, 38, 208, 38, $style);

    $pdf->SetXY(80,10);
    $pdf->SetFont('','B','12');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(0,0,$company_name,'0','C');

	    $company=$address_l.",".$street_name_l."".$area_l.",".$city_l.",".$state_l.",".$country_l;
    $company_address = str_replace("", "",$company);

    $pdf->SetXY(87,16);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(90,2,$company_address,'0','L');
	
  $pdf->SetXY(100,25);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(0,0,"GST No: ".$gst_no,'0','C');

$pdf->SetXY(55,25);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(60,0, "Company UIN No:" .$cin_no ,0,1,'R');


    $pdf->SetXY(90,31);
    $pdf->SetFont('','B','12');
    $pdf->SetTextColor('255','255','255');
    $pdf->SetFillColor('32','100','150');
    $pdf->Rect(12, 29.8, 196, 8, 'F');
    $pdf->Cell(0,0, "SO QUOTE " ,0,1,'L');


	}
	
}

}


// ---------------------------------------------------------

//Close and output PDF document

ob_end_clean();
  
  if($print=="PRINT")
{			
	 
 $pdf->Output('name.pdf','I'); 
	exit;
	
}
else
{
$filename="uploads/S_".$quote_no.".pdf";
  $pdf->Output('uploads/soquoteupload/S_'.$quote_no.'.pdf', 'F');

}
	  $pdf->close(); 
//============================================================+
// END OF FILE
//============================================================+

/******************************************************************************************/ 
?>
