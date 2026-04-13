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

//Page header
    public function Header() {
        // Logo
        $image_file = public_path().'/images/premiumlogo.jpg';
        $this->Image($image_file, 15 , 5, 48, 25, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 12);
        // Title

        $this->MultiCell(0, 1,'', 0,'R');
        $this->MultiCell(0, 2,'PREMIUM COATINGS AND CHEMICALS PVT.LTD', 0,'R');
        //       $this->SetFont('helvetica', '', 10);
        $this->MultiCell(0,3, "19,(NP) Sidco Industrial Estate,Ambattur,Chennai 600 098" ,0,'R');
        $this->MultiCell(0,4, "Phone No:044-43111101,02,    Fax: 044-43111101" ,0,'R');
         
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
 
 
 
 
 
$pdf->SetXY(13,50);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Invoice No"  ,0,1,'L');
 

$pdf->SetXY(35,50);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ": ".$row[0]->invoice_number  ,0,1,'L');

$pdf->SetXY(13,55);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Invoice Date"  ,0,1,'L');
 
$pdf->SetXY(35,55);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, ": ".$row[0]->invoice_date ,0,1,'L');


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
 
$pdf->SetXY(30,50);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Vehicle No" ,0,1,'R');
 
 $pdf->SetXY(36.5,55);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Date Of Supply" ,0,1,'R');
 
 
 $pdf->SetXY(71,55);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0,": ".$row[0]->invoice_date,0,1,'R');
 
 
$pdf->SetXY(38,60);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Place Of Supply" ,0,1,'R'); 
 
 $pdf->SetXY(23,65);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Po No" ,0,1,'R'); 
 
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
//$pdf->Cell(0,0, "$bcustomer",0,1,'L');


$pdf->SetXY(13,84);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Address"  ,0,1,'L');


$pdf->SetXY(35,84);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->cell(0,0,$baddressall  ,0,0,'L');
//$pdf->MultiCell(58,10, "$baddressall",0,'L');

$pdf->SetXY(13,95);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN"  ,0,1,'L');



$pdf->SetXY(34,95);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0," :" .$bgst_no  ,0,1,'L');

$pdf->SetXY(13,100);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Lr No"  ,0,1,'L');


$pdf->SetXY(35,100);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0,": " .$lr_no  ,0,1,'L');

$pdf->SetXY(13,105);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Permit No"  ,0,1,'L');

$pdf->SetXY(13,109);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Packaging Qty "  ,0,1,'L');

$pdf->SetXY(35,110);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0,": " .$packaging_qty  ,0,1,'L');



$pdf->SetXY(13,114);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Pack Weight "  ,0,1,'L');

$pdf->SetXY(35,114);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0,": " .$pack_weight  ,0,1,'L');

 $pdf->SetXY(13,118);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State"  ,0,1,'L');

$pdf->SetXY(33,118);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0,": ". "$bstate"  ,0,1,'L');


 
 $pdf->Line(71, 113, 115, 113, $style);
 
 $pdf->Line(71, 113,71 , 123);
 
 $pdf->SetXY(73,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State Code"  ,0,1,'L');


$pdf->SetXY(102,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, $bstate_code  ,0,1,'L');
 
 
$pdf->Line(99, 113, 99 , 123);
 
 
$pdf->SetXY(116,79);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Name"  ,0,1,'L'); 


$pdf->SetXY(140,79);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, ": ".$scustomer  ,0,1,'L');

$pdf->SetXY(116,84);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Address"  ,0,1,'L');


$pdf->SetXY(116,84);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Address"  ,0,1,'L');



 $pdf->SetXY(140,84);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(64,10,$saddressall,0,'L');


 $pdf->SetXY(60,60);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(104,0,": ". $scity,0,1,'R'); 
 

 
$pdf->SetXY(116,95);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "GSTIN"  ,0,1,'L');



$pdf->SetXY(138,95);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, ": " .$sgst_no ,0,1,'L');





$pdf->SetXY(116,100);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "LR No"  ,0,1,'L');


$pdf->SetXY(116,105);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Permit No"  ,0,1,'L');




$pdf->SetXY(138,100);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0,": " .$lr_no  ,0,1,'L');


$pdf->SetXY(116,113);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Pack Weight "  ,0,1,'L');

$pdf->SetXY(138,114);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0,": " .$pack_weight  ,0,1,'L');



$pdf->SetXY(116,109);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "Packaging Qty "  ,0,1,'L');

$pdf->SetXY(138,109);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0,": " .$packaging_qty  ,0,1,'L');

 $pdf->SetXY(116,118);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State"  ,0,1,'L');


$pdf->SetXY(130,118);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0,": " .$sstate ,0,1,'L');

 
$pdf->Line(165, 113, 208, 113, $style);
 
 $pdf->Line(165, 113, 165 , 123);
 
 
 $pdf->SetXY(168,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "State Code"  ,0,1,'L');


$pdf->SetXY(195,115);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//$pdf->Cell(0,0, $sstate_code ,0,1,'L');



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

    
$pdf->SetXY(133,125);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "LTR"  ,0,1,'L');

$pdf->SetXY(103,125);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, "UOM"  ,0,1,'L');

 $pdf->SetXY(155,125);
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



 
$i=0;
$oldY=0;
$dis_amt=0;
$total_amount=0;
$dis_amt=0;
//dd($linetable);
//$linetable=$value;
foreach($linetable as $value) 
{ 
if($oldY==0) $y =135;
elseif($oldY==1){$y=35;}
else $y = $oldY+2;  
$i++;


for($i=0;$i<=10;$i++){
$pdf->SetXY(12,$y);
$pdf->SetFont('','B','9');
$pdf->SetTextColor('0','0','0');
//dd($value);
$pdf->cell(0,0,$value->line_no,0,1,'L');

    $y=$y+10;
 
 $pdf->SetXY(22,$y-10);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//dd($value);
$pdf->Multicell(50,10,$product,0,'L');  
$oldY=$pdf->getY();           


 //vertical s.no
$pdf->Line(22, 123, 22 , $y-2);
$pdf->Line(73, 123, 73 , $y-2);
$pdf->Line(87, 123, 87 , $y-2);
$pdf->Line(101, 123, 101 , $y-2);
$pdf->Line(115, 43, 115 , $y-2);     
$pdf->Line(130, 123, 130 , $y-2);
$pdf->Line(148, 123, 148 , $y-2);
$pdf->Line(170, 123, 170 , $y-2);
$pdf->Line(191, 123, 191, $y-2);
$pdf->Line(12, $y-2, 208, $y-2, $style);                             
    //end 


$pdf->SetXY(73,$y-10);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(15,10, $product_code  ,0,'L');


 $pdf->SetXY(105,$y-10);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Multicell(50,10,$uom_code,0,'L');  


$pdf->SetXY(22,$y-10);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//dd($value);
//$pdf->Multicell(50,10,$value['productid'],0,'L');  



$pdf->SetXY(135,$y-13);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
//$pdf->cell(50,10,$value['packvalue'],0,'L');   


    

$pdf->SetXY(156 ,$y-13);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->cell(50,10,$value->qty,0,'L');  

 

    //vertical s.no
     
    //end 

 
$pdf->SetXY(176,$y-10);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(0,0, $value->unit_price  ,0,1,'L');
 
 //vertical s.no
     
    //end 
    
$discount=$value->discount_amount;
$unit= $value->unit_price;
$qty=$value->qty;
$total=$unit*$qty;
$total_amount=$total-2/100;



$pdf->SetXY(195,$y-13);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->cell(50,10,(round($total)) ,0,'L'); 



$pdf->SetXY(118,$y-10);
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
if($oldY>=270){

$pdf->AddPage();

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

$y=35;   
}
}

    
   
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
