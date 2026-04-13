<?php 

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
error_reporting(0);
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

//Page header
    public function Header() {
        // Logo
        $image_file = public_path().'/images/backend-logo.png';
        $this->Image($image_file, 15 , 6, 48, 25, 'PNG', '', 'T', false, 250, '', false, false, 0, false, false, false);
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


// add a page
$pdf->AddPage('P', $resolution);

//$pdf->SetHeaderData('', '', 'CORRESPONDENCE LETTER', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
/* $pdf->Line(0,0,$pdf->getPageWidth(),0); 
$pdf->Line($pdf->getPageWidth(),0,$pdf->getPageWidth(),$pdf->getPageHeight());
$pdf->Line(0,$pdf->getPageHeight(),$pdf->getPageWidth(),$pdf->getPageHeight());
$pdf->Line(0,0,0,$pdf->getPageHeight());
$pdf->SetLineStyle( array( 'width' => 14, 'color' => array(255,255,255)));
$pdf->Line(0,0,$pdf->getPageWidth(),0); 
$pdf->Line($pdf->getPageWidth(),0,$pdf->getPageWidth(),$pdf->getPageHeight());
$pdf->Line(0,$pdf->getPageHeight(),$pdf->getPageWidth(),$pdf->getPageHeight());
$pdf->Line(0,0,0,$pdf->getPageHeight());// set margins */

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);


$pdf->Line(12, 31, 208, 31, $style);

$pdf->SetXY(85,8);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0,$company_name,'0','L');


$pdf->SetXY(85,12.5);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(95,2,$company_address,'0','L');


$pdf->SetXY(70,25);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(104,2,"Ph/Fax :                      Mobile :                               Email",'0','C');

$pdf->SetXY(55,34);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "INVOICE" ,0,1,'M');

$x=235;$y=235;$j=0;

$header_company=array('CIN:','GSTIN/UIN : '.$company_gstno);
foreach($header_company as $key=>$value)
	{
	
			$pdf->SetXY(85,$y-214);
			$pdf->SetFont('','B','8');
			$pdf->SetTextColor('0','0','0');    
			$pdf->MultiCell(50,2, $value ,0,'L');
			$y=$y+4;
	}

$x=235;$y=235;
$header_com_mail=array('STATE CODE : '.$state_no,'E-MAIL : '.$e_mail);
foreach($header_com_mail as $key=>$value)
	{
			$pdf->SetXY(150,$y-215);
			$pdf->SetFont('','B','8');
			$pdf->SetTextColor('0','0','0');    
			$pdf->MultiCell(50,2, $value ,0,'L');
			$y=$y+3.5;
	}
			
			
		

 
 $pdf->SetXY(90,$y-213);
$pdf->SetFont('','','14');
$pdf->SetTextColor('0','0','0');    
//$pdf->Cell(0,0, $com_cin ,0,1,'L');
$y=$y+15;



//$company_address=$address1.",".$street.",".$area.",".$city.",".$state.",".$country;
//dd($company_address);
$pdf->SetXY(90,15);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(110,0,$company_address,'0','L');
 $pdf->SetXY(68,32);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Original For Receipient" ,0,1,'R');

 $pdf->Line(135, 34.5, 140, 34.5, $style);

 $pdf->Line(135, 39, 140, 39, $style);
 
 
$pdf->SetXY(81.5,35.5);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Duplicate For Supplier/Transpoter" ,0,1,'R'); 
 
 $pdf->SetXY(66.9,39);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Triplicate For Supplier" ,0,1,'R');
 

 $pdf->Line(135, 31, 135 , 43); 
 $pdf->Line(140, 31, 140 , 43);
 
 
 $pdf->Line(12, 43, 208, 43, $style);
 $pdf->Line(12, 31, 208, 31, $style);


$y=235;
$company_details=array('GSTIN : '.$company_gstno,'Invoice No : '.$invoice_no,'Invoice Date : '.$invoice_date,'Supplier’s Ref : '.$reference_number,'Buyer’s Order No : '.$sales_order_no);
$j=0;
foreach($company_details as $key=>$value)
{
    $pdf->SetXY(13,$y-190);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(0,0,$value,0,'L');
    $y=$y+5;
}


$y=235;
$company_details_2=array('Dispatch Document No : ','Dispatched through : ','Dated : '.$date,'Mode/Terms of Payment : '.$payment_term_name,'Destination : '.$state_name_ship);
$j=0;
foreach($company_details_2 as $key=>$value)
{
    $pdf->SetXY(115,$y-190);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(0,0,$value,0,'L');
    $y=$y+5;
}


$y=235;
$company_details_3=array('Transport Mode : ','Vehicle No : ','Date Of Supply : '.$date,'Po No : ','Place Of Supply : '.$state_name);
$j=0;
foreach($company_details_3 as $key=>$value)
{
    $pdf->SetXY(65,$y-190);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(0,0,$value,0,'L');
    $y=$y+5;
}


$y=235;
$company_details_4=array('State : '.$state_name,'State Code : '.$state_no);
$j=0;
foreach($company_details_4 as $key=>$value)
{
    $pdf->SetXY(170,$y-190);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(0,0,$value,0,'L');
    $y=$y+5;
}


$pdf->SetXY(35,60);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0,": Tamil Nadu"  ,0,1,'L');

 
  //vertical line
$pdf->Line(115, 43, 115 , 200);      
    //end 

 
 $pdf->Line(12, 70, 208, 70, $style);
 
 $pdf->SetXY(37,72);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Detail Of Receiver| Billed To"  ,0,1,'L');
 
 
 $pdf->SetXY(125,72);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Detail Of Consignee| Shipped To"  ,0,1,'L');
 
$pdf->Line(12, 77, 208, 77, $style);

 $pdf->SetXY(13,79);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Name"  ,0,1,'L');

$pdf->SetXY(35,79);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0," : ".$customer_name,0,1,'L');


$pdf->SetXY(13,84);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Address"  ,0,1,'L');


$pdf->SetXY(35,84);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->cell(0,0,$baddressall  ,0,0,'L');
//$pdf->MultiCell(58,10, $billing_address,0,'L');

$pdf->SetXY(13,95);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN"  ,0,1,'L');



$pdf->SetXY(34,95);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0," :".$bill_gst_no ,0,1,'L');

$pdf->SetXY(13,100);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Lr No"  ,0,1,'L');


$pdf->SetXY(35,100);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,": " .$lr_no  ,0,1,'L');

$pdf->SetXY(13,105);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Permit No"  ,0,1,'L');

$pdf->SetXY(13,109);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Packaging Qty " ,0,1,'L');

$pdf->SetXY(35,110);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,": " .$packaging_qty  ,0,1,'L');


$pdf->SetXY(13,114);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Pack Weight "  ,0,1,'L');

$pdf->SetXY(35,114);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,": " .$pack_weight  ,0,1,'L');

$pdf->SetXY(13,118);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State"  ,0,1,'L');

$pdf->SetXY(33,118);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,": ".$state_name_ship ,0,1,'L');

$pdf->Line(71, 113, 115, 113, $style);
$pdf->Line(71, 113,71 , 123);
 
$pdf->SetXY(73,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State Code"  ,0,1,'L');


$pdf->SetXY(102,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $state_id_bill  ,0,1,'L');
 
 
$pdf->Line(99, 113, 99 , 123);

$pdf->SetXY(116,79);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Name"  ,0,1,'L'); 


$pdf->SetXY(138,79);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, " : ".$customer_name  ,0,1,'L');

$pdf->SetXY(116,84);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Address"  ,0,1,'L');

$pdf->SetXY(138,85);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(70,2, " :  ".$ship_to_address_1 ,0,'L');


$pdf->SetXY(116,84);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Address"  ,0,1,'L');

$pdf->SetXY(35,83);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,4, " : ".$bill_to_address_1  ,0,'L');
 
$pdf->SetXY(116,95);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN"  ,0,1,'L');

$pdf->SetXY(138,95);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, " : ".$ship_gst_no ,0,1,'L');

$pdf->SetXY(116,100);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "LR No"  ,0,1,'L');

$pdf->SetXY(116,105);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Permit No"  ,0,1,'L');

$pdf->SetXY(138,105);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, " : "  ,0,1,'L');

$pdf->SetXY(138,100);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0," : " .$lr_no  ,0,1,'L');

$pdf->SetXY(116,113);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Pack Weight "  ,0,1,'L');

$pdf->SetXY(138,114);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0," : " .$pack_weight  ,0,1,'L');

$pdf->SetXY(116,109);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Packaging Qty "  ,0,1,'L');

$pdf->SetXY(138,109);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0," : " .$packaging_qty  ,0,1,'L');

 $pdf->SetXY(116,118);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State :"  ,0,1,'L');


$pdf->SetXY(128,118);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2,"" .$state_name_ship ,0,'L');

 
$pdf->Line(165, 113, 208, 113, $style);
 
 $pdf->Line(165, 113, 165 , 123);
 
 
 $pdf->SetXY(168,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State Code"  ,0,1,'L');


$pdf->SetXY(195,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $state_id_bill ,0,1,'L');


 //vertical
 $pdf->Line(192, 113, 192 , 123);
 //
$pdf->Line(12, 123, 208, 123, $style);
 
 $pdf->Line(12, 133, 208, 133, $style);

$header_line=array('S.No','Product/Services','','','Product Code','HSN','UOM','TAX/HSN','Batch No','TAX
AMOUNT','     Qty','Rate Per Unit','Amount');

$x=235;$y=235;
foreach($header_line as $key=>$value)
{
    $pdf->SetXY($x-223,125);
    $pdf->SetFont('','B','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(16.8,2, $value ,0,'L');
    
    $x=$x+14.7;
}

 
$i=0;
$oldY=0;
$dis_amt=0;
$total_amount=0;
$dis_amt=0;
$sub_tot=0;
$tax_sgst=0;
$tax_cgst=0;
foreach($linedata as $key=>$value) 
{ 
//dd($value);
if($oldY==0) $y =135;
elseif($oldY==1){$y=30;}
else $y = $oldY+3;  
$i++;


$pdf->SetXY(12,$y);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->cell(0,0,$value['line_no'],0,1,'L');

$y=$y+10;

$pdf->SetXY(22,$y-10);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(50,10,$value['product'],0,'L'); 
 
$oldY=$pdf->getY();           

$pdf->SetXY(72,$y-10);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,10, $value['product_code']  ,0,'L');

$pdf->SetXY(85,$y-10);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(15,10,$value['hsn_code'],0,'L'); 

$pdf->SetXY(103,$y-10);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(50,10,$value['uom_code'],0,'L');
    
$pdf->SetXY(114.5,$y-10);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(50,10,$value['gst_per'],0,'L'); 

$pdf->SetXY(148,$y-13);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$taxamt=number_format($value['tax_amount'],\Session::get("decimal"));	
$taxamt1= str_replace(',', '',$taxamt);
$pdf->cell(50,10,$taxamt1,0,'L');   

$pdf->SetXY(162 ,$y-13);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->cell(50,10,$value['qty'],0,'L');  

//$tax_sgst+=$value['sgst'];
//$tax_cgst+=$value['cgst'];

$pdf->SetXY(176,$y-10);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$price=number_format($value['unit_price'],\Session::get("decimal"));	
$price1= str_replace(',', '',$price);
$pdf->Cell(0,0, $price1  ,0,1,'L');
  
$discount=$value['discount_amount'];
$dis_amt+=$discount;

$unit= $value['unit_price'];
$qty=$value['qty'];
$total=$unit*$qty;
$total_amount=$total-2/100;



$pdf->SetXY(193,$y-13);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->cell(50,10,(round($total)) ,0,'L'); 

$sub_tot+=(round($total));
//dd($sub_tot);



$pdf->SetXY(118,$y);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0'); 
//dd($value);
//$pdf->Multicell(50,10,$value['display_name']."%",0,'L'); 
  
  
//$dis_amt=$value['discount_amount']+$dis_amt;
//dd($dis_amt);

$pdf->SetXY(35,106);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0,": " .$value['permit_number']  ,0,1,'L');

$pdf->SetXY(138,106);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0,": " .$value['permit_number']  ,0,1,'L');

//dd($oldY);



 //vertical s.no
$pdf->Line(22, 123, 22 , $y);
$pdf->Line(71, 123, 71 , $y);
$pdf->Line(85, 123, 85 , $y);
$pdf->Line(99, 123, 99 , $y);
$pdf->Line(115, 43, 115 , $y);     
$pdf->Line(130, 123, 130 , $y);
$pdf->Line(145, 123, 145 , $y);
$pdf->Line(160, 123, 160 , $y);
$pdf->Line(173, 123, 173, $y);
$pdf->Line(189, 123, 189, $y);
$pdf->Line(12, $y, 208, $y, $style);                             
    //end 






if($oldY>=255){

$pdf->AddPage();

//$pdf->SetHeaderData('', '', 'CORRESPONDENCE LETTER', '');
 $pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));
/* $pdf->Line(0,0,$pdf->getPageWidth(),0); 
$pdf->Line($pdf->getPageWidth(),0,$pdf->getPageWidth(),$pdf->getPageHeight());
$pdf->Line(0,$pdf->getPageHeight(),$pdf->getPageWidth(),$pdf->getPageHeight());
$pdf->Line(0,0,0,$pdf->getPageHeight());
$pdf->SetLineStyle( array( 'width' => 14, 'color' => array(255,255,255)));
$pdf->Line(0,0,$pdf->getPageWidth(),0); 
$pdf->Line($pdf->getPageWidth(),0,$pdf->getPageWidth(),$pdf->getPageHeight());
$pdf->Line(0,$pdf->getPageHeight(),$pdf->getPageWidth(),$pdf->getPageHeight());
$pdf->Line(0,0,0,$pdf->getPageHeight());// set margins */

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);


$pdf->Line(12, 31, 208, 31, $style);

$pdf->SetXY(85,8);
$pdf->SetFont('','B','12');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0,$company_name,'0','L');


$pdf->SetXY(85,14);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(95,2,$company_address,'0','L');


$pdf->SetXY(70,25);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2,"Ph/Fax :                      Mobile :                               Email",'0','C');

$pdf->SetXY(55,34);

$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "INVOICE" ,0,1,'M');
 
 $pdf->SetXY(90,9);
$pdf->SetFont('','','14');
$pdf->SetTextColor('0','0','0');    
//$pdf->Cell(0,0, $name ,0,1,'L');    



//$company_address=$address1.",".$street.",".$area.",".$city.",".$state.",".$country;
//dd($company_address);
$pdf->SetXY(90,15);
$pdf->SetFont('','','10');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(110,0,$company_address,'0','L');
 $pdf->SetXY(68,32);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Original For Receipient" ,0,1,'R');

$pdf->Line(135, 34.5, 140, 34.5, $style);
$pdf->Line(135, 39, 140, 39, $style);
 
$pdf->SetXY(81.5,35.5);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Duplicate For Supplier/Transpoter" ,0,1,'R'); 
 
 $pdf->SetXY(66.9,39);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Triplicate For Supplier" ,0,1,'R');
 

 $pdf->Line(135, 31, 135 , 43); 
 $pdf->Line(140, 31, 140 , 43);
 
 
 $pdf->Line(12, 43, 208, 43, $style);
 $pdf->Line(12, 31, 208, 31, $style);
 
$pdf->SetXY(13,45);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN"  ,0,1,'L');
 
$pdf->SetXY(35,45);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ": ".$cus_gst_no  ,0,1,'L');
  
$pdf->SetXY(13,50);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Invoice No"  ,0,1,'L');
 

$pdf->SetXY(35,50);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ": ".$invoice_number,0,1,'L');

$pdf->SetXY(13,55);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Invoice Date"  ,0,1,'L');
 
$pdf->SetXY(35,55);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ": ".$invoice_date,0,1,'L');


$pdf->SetXY(13,60);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State"  ,0,1,'L');

$pdf->SetXY(35,60);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,": Tamil Nadu"  ,0,1,'L');

$pdf->SetXY(35,65);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,": TN"  ,0,1,'L');
 
$pdf->SetXY(13,65);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State Code"  ,0,1,'L');
 
  //vertical line
$pdf->Line(115, 43, 115 , 200);      
    //end 
 $pdf->SetXY(37,45);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Transport Mode" ,0,1,'R');

$pdf->SetXY(54,45);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, ":" ,0,1,'R');
 
$pdf->SetXY(30,50);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Vehicle No" ,0,1,'R');

$pdf->SetXY(54,50);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, ":" ,0,1,'R');
 
 $pdf->SetXY(36.5,55);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, " Date Of Supply" ,0,1,'R');
 
 
$pdf->SetXY(71,55);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0,": " .$invoice_date,0,1,'R');
 
 
$pdf->SetXY(38,60);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Place Of Supply" ,0,1,'R'); 

$pdf->SetXY(54,60);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, ":" ,0,1,'R');
 
 $pdf->SetXY(23,65);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Po No" ,0,1,'R');

$pdf->SetXY(54,65);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, ":" ,0,1,'R');
 
$pdf->SetXY(38,60);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Place Of Supply" ,0,1,'R'); 
 
 $pdf->SetXY(35,45);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, ": ".$company_gst_no ,0,1,'L');
 
 $pdf->Line(12, 70, 208, 70, $style);
 
 $pdf->SetXY(37,72);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Detail Of Receiver| Billed To"  ,0,1,'L');
 
 
 $pdf->SetXY(125,72);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Detail Of Consignee| Shipped To"  ,0,1,'L');
 
$pdf->Line(12, 77, 208, 77, $style);

 $pdf->SetXY(13,79);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Name"  ,0,1,'L');

$pdf->SetXY(35,79);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0," : ".$customer_name,0,1,'L');


$pdf->SetXY(13,84);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Address"  ,0,1,'L');


$pdf->SetXY(35,84);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->cell(0,0,$baddressall  ,0,0,'L');
//$pdf->MultiCell(58,10, $billing_address,0,'L');

$pdf->SetXY(13,95);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN"  ,0,1,'L');



$pdf->SetXY(34,95);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0," :".$gst_no_ship   ,0,1,'L');

$pdf->SetXY(13,100);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Lr No"  ,0,1,'L');


$pdf->SetXY(35,100);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,": " .$lr_no  ,0,1,'L');

$pdf->SetXY(13,105);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Permit No"  ,0,1,'L');

$pdf->SetXY(13,109);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Packaging Qty " ,0,1,'L');

$pdf->SetXY(35,110);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,": " .$packaging_qty  ,0,1,'L');


$pdf->SetXY(13,114);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Pack Weight "  ,0,1,'L');

$pdf->SetXY(35,114);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,": " .$pack_weight  ,0,1,'L');



 $pdf->SetXY(13,118);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State"  ,0,1,'L');

$pdf->SetXY(33,118);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0,": ".$state_name_ship ,0,1,'L');


 
 $pdf->Line(71, 113, 115, 113, $style);
 
 $pdf->Line(71, 113,71 , 123);
 
 $pdf->SetXY(73,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State Code"  ,0,1,'L');


$pdf->SetXY(102,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $state_code_ship  ,0,1,'L');
 
 
$pdf->Line(99, 113, 99 , 123);

$pdf->SetXY(116,79);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Name"  ,0,1,'L'); 


$pdf->SetXY(138,79);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, " : ".$customer_name  ,0,1,'L');

$pdf->SetXY(116,84);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Address"  ,0,1,'L');

$pdf->SetXY(138,85);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,0, " : ".$ship_to_address ,0,'L');


$pdf->SetXY(116,84);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Address"  ,0,1,'L');

$pdf->SetXY(35,83);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,4, " : ".$bill_to_address  ,0,'L');

 
$pdf->SetXY(116,95);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN"  ,0,1,'L');



$pdf->SetXY(138,95);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, " : ".$gst_no_ship ,0,1,'L');





$pdf->SetXY(116,100);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "LR No"  ,0,1,'L');


$pdf->SetXY(116,105);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Permit No"  ,0,1,'L');

$pdf->SetXY(138,105);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, " : "  ,0,1,'L');


$pdf->SetXY(138,100);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0," : " .$lr_no  ,0,1,'L');


$pdf->SetXY(116,113);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Pack Weight "  ,0,1,'L');

$pdf->SetXY(138,114);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0," : " .$pack_weight  ,0,1,'L');



$pdf->SetXY(116,109);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Packaging Qty "  ,0,1,'L');

$pdf->SetXY(138,109);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0," : " .$packaging_qty  ,0,1,'L');

 $pdf->SetXY(116,118);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State :"  ,0,1,'L');


$pdf->SetXY(128,118);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2,"" .$state_name_bill ,0,'L');

 
$pdf->Line(165, 113, 208, 113, $style);
 
 $pdf->Line(165, 113, 165 , 123);
 
 
 $pdf->SetXY(168,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State Code"  ,0,1,'L');


$pdf->SetXY(195,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $state_code_bill ,0,1,'L');


 //vertical
 $pdf->Line(192, 113, 192 , 123);
 //
$pdf->Line(12, 123, 208, 123, $style);
 
 $pdf->Line(12, 133, 208, 133, $style);


$pdf->SetXY(12,125);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "S.NO"  ,0,1,'L');


  $pdf->SetXY(27,125);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Name Of Product/Services"  ,0,1,'L');

 
 $pdf->SetXY(73,125);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,10, "Product Code"  ,0,'L');


$pdf->SetXY(118,124);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "TAX"  ,0,1,'L');

$pdf->SetXY(118,128);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "HSN"  ,0,1,'L');

    
$pdf->SetXY(132,124);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,8, "TAX AMOUNT"  ,0,'L');

$pdf->SetXY(103,125);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "UOM"  ,0,1,'L');

 $pdf->SetXY(165,125);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Qty"  ,0,1,'L');

    
$pdf->SetXY(173,125);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Rate Per"  ,0,1,'L');
    
$pdf->SetXY(176,128);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Unit"  ,0,1,'L');

 
$pdf->SetXY(191,125);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Amount"  ,0,1,'L');   

$pdf->SetXY(89,125);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "HSN/"  ,0,1,'L');

$pdf->SetXY(89,128);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "ACN"  ,0,1,'L');


$oldY=$pdf->getY();
}
}
if($oldY>=200)
{


 $pdf->SetXY(68,32);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Original For Receipient" ,0,1,'R');

 $pdf->Line(135, 34.5, 140, 34.5, $style);

 $pdf->Line(135, 39, 140, 39, $style);
 
 
$pdf->SetXY(81.5,35.5);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Duplicate For Supplier/Transpoter" ,0,1,'R'); 
 
 $pdf->SetXY(66.9,39);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Triplicate For Supplier" ,0,1,'R');


 $pdf->Line(135, 31, 135 , 43); 
 $pdf->Line(140, 31, 140 , 43);
 
 
 $pdf->Line(12, 43, 208, 43, $style);
 $pdf->Line(12, 31, 208, 31, $style);
 

//if(($value['discount_amount'])!=0)

$pdf->SetXY(135,245);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Discount Amount"  ,0,1,'L');   

$pdf->SetXY(195,245);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, (round($dis_amt)) ,0,1,'L');

  

  
 
 $pdf->Line(12, 200, 208, 200, $style);

 $pdf->SetXY(26,202);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Total "  ,0,1,'L');

$pdf->SetXY(194,200);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$totamt=number_format($total_amount,\Session::get("decimal"));	
$totamt1= str_replace(',', '',$totamt);
$pdf->cell(50,10,$totamt1,0,'L');  

 $pdf->Line(12, 208, 208, 208, $style);
 
 
 $pdf->SetXY(26,215);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Total Invoice In Amounts In Words"  ,0,1,'L');

$pdf->Line(115, 290, 115 , 208);

$pdf->SetXY(126,208);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Total Amount Before Tax"  ,0,1,'L');

$pdf->SetXY(194,210);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, (round($sub_tot))  ,0,1,'L');





$hsn_y=0;
$hsny=193;
$gst_total=0;
$net_total=0;
 $gross_total=0;




$sgat=0;
$cgst=0;
$gsttot=0;
$gsttot=208;foreach($gst as $gstvalue)
{
    
    if($hsn_y==0)
    {
        $hsny=214;
        
    }
    else
    {
    //$maxY=max($hsn_y,$oldslab);
    $hsny=$oldslab+1;
    }
        //else $hsny=$hsn_y+5;

    $gsttot=$gsttot+12;
    
    $pdf->SetXY(126,$hsny+3);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(105,10, $gstvalue['sgst_tax'] ,0,'L');
    $hsny=$hsny+5;
    
    $pdf->SetXY(126,$hsny+3);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
    $pdf->MultiCell(105,10, $gstvalue['cgst_tax'] ,0,'L');
    $hsny=$hsny+5;


 $pdf->SetXY(120,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(105,10, $gstvalue['hsn'] ,0,'L');
$hsn_y=$pdf->getY();
 

$pdf->SetXY(133,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(50,10, number_format($gstvalue['amount'],'2','.','') ,0,'R');

$pdf->SetXY(200,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(50,10, $gstvalue['SGST'] ,0,'R');


$pdf->SetXY(190,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(15,10, number_format($gst_1) ,0,'R');

$pdf->SetXY(143,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(60,10, $gstvalue['SGST_S'] ,0,'L');

$pdf->SetXY(143,$hsny+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(60,10, $gstvalue['CGST_S'] ,0,'L');
$oldslab=$pdf->getY();

$pdf->SetXY(190,$hsny+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(15,10, number_format($gst_2),0,'R');
    
$pdf->SetXY(140,$hsny+5);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(25,0,"SGST " .$gstvalue['sgcgst']."%",0,'R');

    
$pdf->SetXY(140,$hsny+15);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(25,0,"CGST " .$gstvalue['sgcgst']."%",0,'R');

    
    
$pdf->SetXY(180,$hsny+5);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(25,0,number_format($gstvalue['sgst_val'],'2','.',''),0,'R');
   
$pdf->SetXY(180,$hsny+15);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(25,0,number_format($gstvalue['sgst_val'],'2','.',''),0,'R');

    



//$gsttot_2+=$gsttotal;
//dd($gsttot);
 
//$sgst+=$gstvalue['sgst_val']+$gstvalue['sgst_val'];
    

//$sgat+=$gstvalue['sgst_val'];
//$cgst+=$gstvalue['cgst_val'];
 
    
//$total_tax=$gstvalue['gst_val']+$total_tax;
    
    
}

  
$pdf->SetXY(190,225);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(15,10, number_format($sgat,'2','.','') ,0,'R'); 
    
$pdf->SetXY(190,230);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(15,10, number_format($cgst,'2','.','') ,0,'R');

$pdf->SetXY(195,255);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, $total_tax ,0,1,'L');
$pdf->Cell(0,0, number_format($tot_tax_amt) ,0,1,'L');    


$pdf->SetXY(135,255);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Total Tax Amount"  ,0,1,'L');

$pdf->SetXY(135,260);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Total Amount After Tax"  ,0,1,'L');

$pdf->SetXY(195,260);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, (round($tot_amt_aftr_tax))  ,0,1,'L');

$pdf->SetXY(23,220);
$pdf->SetFont('','','10');
$pdf->MultiCell(80,8,convert_number_to_words(round($value['amount'])),0,'L');





//dd($gstvalue);

//vertical
 $pdf->Line(75, 290, 75 , 243);
 //

$pdf->SetXY(30,245);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Bank Details"  ,0,1,'L');
 

$pdf->SetXY(15,250);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Bank Name"  ,0,1,'L');
 
$pdf->SetXY(15,258);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Account Number"  ,0,1,'L');

$pdf->SetXY(15,266);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Bank Branch Ifsc"  ,0,1,'L');



$bank='Bank Details : Account Name : '.\Session::get('company_rpt_title').' Account No : 01242560001442/ Bank Name : HDFC Bank Ltd.Branch : Kilpauk, IFSC Code : HDFC0000124';



 //vertical
 $pdf->Line(169, 243, 169 , 208);
 
 $pdf->SetXY(78,282);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(140,0, "Common Seal" ,0,1,'L');  
 
 $pdf->SetXY(27,269.5);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(95,0, "For" ,0,1,'R');    
     
    $pdf->SetXY(47,270);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(160,0, $company_name,0,1,'R');   

$pdf->SetXY(66,282);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(140,0, "Authorised Signatory" ,0,1,'R');  
 

 $pdf->Line(12, 243, 208, 243, $style);
}
else
{

 $pdf->SetXY(68,32);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Original For Receipient" ,0,1,'R');

 $pdf->Line(135, 34.5, 140, 34.5, $style);

 $pdf->Line(135, 39, 140, 39, $style);
 
 
$pdf->SetXY(81.5,35.5);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Duplicate For Supplier/Transpoter" ,0,1,'R'); 
 
 $pdf->SetXY(66.9,39);
$pdf->SetFont('','','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Triplicate For Supplier" ,0,1,'R');


 $pdf->Line(135, 31, 135 , 43); 
 $pdf->Line(140, 31, 140 , 43);
 
 
 $pdf->Line(12, 43, 208, 43, $style);
 $pdf->Line(12, 31, 208, 31, $style);
 

//if(($value['discount_amount'])!=0)

$pdf->SetXY(135,245);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Discount Amount"  ,0,1,'L');   

$pdf->SetXY(195,245);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, (round($tot_desc)) ,0,1,'L');

  

  
 
 $pdf->Line(12, 200, 208, 200, $style);

 $pdf->SetXY(26,202);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Total"  ,0,1,'L');

$pdf->SetXY(194,200);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->cell(50,10,(round($value['amount'])),0,'L');  

 $pdf->Line(12, 208, 208, 208, $style);
 
 
 $pdf->SetXY(26,215);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Total Invoice In Amounts In Words"  ,0,1,'L');

 
 
 //vertical
 $pdf->Line(115, 290, 115 , 208);
 //
$pdf->SetXY(126,210);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Total Amount Before Tax"  ,0,1,'L');

$pdf->SetXY(194,210);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, (round($value['amount']))  ,0,1,'L');
 $a=215;
 $a1=215;
foreach($taxcode1 as $k=>$v){
   

$pdf->SetXY(126,$a);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $v ,0,1,'L');
$a=$a+5;

$pdf->SetXY(194,$a1);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, $total_tax ,0,1,'L');
$pdf->Cell(0,0, number_format($tot_tax_amt/2) ,0,1,'L');  
$a1=$a1+5;
}
$hsn_y=0;
$hsny=193;
$gst_total=0;
$net_total=0;
 $gross_total=0;



$total_tax=0;

$hsn_y=0;
//$tax=$tax_id;



$pdf->SetXY(126,215);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, $tax ,0,1,'L');

$tax_amount=$total;
$gst_amount=$tax_amount*28/100;
//dd($gst_amount);

$hsn_y=0;
$hsny=193;
$gst_total=0;
$net_total=0;
 $gross_total=0;




$sgat=0;
$cgst=0;
$gsttot=0;
$gsttot=208;foreach($gst as $gstvalue)
{
    
    if($hsn_y==0)
    {
        $hsny=214;
        
    }
    else
    {
    //$maxY=max($hsn_y,$oldslab);
    $hsny=$oldslab+1;
    }
        //else $hsny=$hsn_y+5;

    $gsttot=$gsttot+12;
    
    $pdf->SetXY(126,$hsny+3);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
//    $pdf->MultiCell(105,10, $gstvalue['sgst_tax'] ,0,'L');
    $hsny=$hsny+5;
    
    $pdf->SetXY(126,$hsny+3);
    $pdf->SetFont('','','9');
    $pdf->SetTextColor('0','0','0');
//    $pdf->MultiCell(105,10, $gstvalue['cgst_tax'] ,0,'L');
    $hsny=$hsny+5;


 $pdf->SetXY(120,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(105,10, $gstvalue['hsn'] ,0,'L');
$hsn_y=$pdf->getY();
 

$pdf->SetXY(133,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(50,10, number_format($gstvalue['amount'],'2','.','') ,0,'R');

$pdf->SetXY(200,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(50,10, $gstvalue['SGST'] ,0,'R');


$pdf->SetXY(190,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(15,10, number_format($gst_1) ,0,'R');

$pdf->SetXY(143,$hsny);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(60,10, $gstvalue['SGST_S'] ,0,'L');

$pdf->SetXY(143,$hsny+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(60,10, $gstvalue['CGST_S'] ,0,'L');
$oldslab=$pdf->getY();

$pdf->SetXY(190,$hsny+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(15,10, number_format($gst_2),0,'R');
    
$pdf->SetXY(140,$hsny+5);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(25,0,"SGST " .$gstvalue['sgcgst']."%",0,'R');

    
$pdf->SetXY(140,$hsny+15);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(25,0,"CGST " .$gstvalue['sgcgst']."%",0,'R');

    
    
$pdf->SetXY(180,$hsny+5);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(25,0,number_format($gstvalue['sgst_val'],'2','.',''),0,'R');
   
$pdf->SetXY(180,$hsny+15);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(25,0,number_format($gstvalue['sgst_val'],'2','.',''),0,'R');

    



//$gsttot_2+=$gsttotal;
//dd($gsttot);
 
//$sgst+=$gstvalue['sgst_val']+$gstvalue['sgst_val'];
    

//$sgat+=$gstvalue['sgst_val'];
//$cgst+=$gstvalue['cgst_val'];
 
    
//$total_tax=$gstvalue['gst_val']+$total_tax;
    
    
}
    
$pdf->SetXY(190,225);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(15,10, number_format($sgat,'2','.','') ,0,'R'); 
    
$pdf->SetXY(190,230);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(15,10, number_format($cgst,'2','.','') ,0,'R');

$pdf->SetXY(195,255);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, $total_tax ,0,1,'L');
$pdf->Cell(0,0, number_format($tot_tax_amt) ,0,1,'L');    


$pdf->SetXY(135,255);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Total Tax Amount"  ,0,1,'L');

$pdf->SetXY(135,260);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Total Amount After Tax"  ,0,1,'L');

$pdf->SetXY(195,260);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, (round($tot_amt_aftr_tax))  ,0,1,'L');



$pdf->SetXY(23,220);
$pdf->SetFont('','','10');
$pdf->MultiCell(80,8,convert_number_to_words(round($value['amount'])),0,'L');





//dd($gstvalue);

//vertical
 $pdf->Line(75, 290, 75 , 243);
 //

$pdf->SetXY(30,245);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Bank Details"  ,0,1,'L');
 

$pdf->SetXY(15,250);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Bank Name"  ,0,1,'L');
 
$pdf->SetXY(15,258);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Account Number"  ,0,1,'L');

$pdf->SetXY(15,266);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Bank Branch Ifsc"  ,0,1,'L');



$bank='Bank Details : Account Name : '.\Session::get('company_rpt_title').' Account No : 01242560001442/ Bank Name : HDFC Bank Ltd.Branch : Kilpauk, IFSC Code : HDFC0000124';



 //vertical
 $pdf->Line(169, 243, 169 , 208);
 
 $pdf->SetXY(78,282);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(140,0, "Common Seal" ,0,1,'L');  
 
 $pdf->SetXY(27,269.5);
$pdf->SetFont('','','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(95,0, "For" ,0,1,'R');    
     
    $pdf->SetXY(47,270);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(160,0, $company_name,0,1,'R');   

$pdf->SetXY(66,282);
$pdf->SetFont('','B','11');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(140,0, "Authorised Signatory" ,0,1,'R');  
 

 $pdf->Line(12, 243, 208, 243, $style);
}



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
