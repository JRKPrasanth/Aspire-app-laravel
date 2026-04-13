
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
$points = (!isset($point)) ?
"." . $words[$point / 10] . " " . 
$words[$point = $point % 10] : '';
return $result . "Rupees Only";
}

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

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
        $this->Image($image_file, 16 , 6    , 25, 50, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 12);
        // Title

        //$this->MultiCell(0, 1,'', 0,'R');
        //$this->MultiCell(0, 2,'PREMIUM COATINGS AND CHEMICALS PVT.LTD', 0,'R');
        //       $this->SetFont('helvetica', '', 10);
        //$this->MultiCell(0,3, "19,(NP) Sidco Industrial Estate,Ambattur,Chennai 600 098" ,0,'R');
       // $this->MultiCell(0,4, "Phone No:044-43111101,02,    Fax: 044-43111101" ,0,'R');
         
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

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);


$pdf->Line(12, 65, 208, 65, $style);

/*------------------- header line   row -1 ----------------- */
$pdf->Line(55, 15, 208, 15, $style);
$pdf->Line(55, 7, 55, 65, $style);
$pdf->Line(97, 15, 97, 65, $style);
$pdf->Line(136, 15, 136, 65, $style);
$pdf->Line(170, 15, 170, 65, $style);

$pdf->SetXY(55,8);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "TAX INVOICE" ,0,1,'L');

$pdf->SetXY(85,8);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "PREVIEW" ,0,1,'L');

/*-------------- registered with sub row -------------------*/

$pdf->SetXY(57,16);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Registered Office Address" ,0,1,'L');

$pdf->SetXY(56,22);
$pdf->SetFont('','B','7.5');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(45,2, "".$company_address ,0,'L');

$pdf->SetXY(56,40);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Tel/Fax:" ,0,1,'L');

$pdf->SetXY(56,50);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Pan:".$pan_no ,0,1,'L');

/*--------------- end -------------------------------------*/


/*---------------- supplier location with row -------------*/
$pdf->SetXY(102,16);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->multiCell(30,2, "Supplying Location Address" ,0,'L');

$pdf->SetXY(98,40);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "State :".$state_name ,0,1,'L');

$pdf->SetXY(98,48);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "State Code :".$state_code ,0,1,'L');

$pdf->SetXY(98,55);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "GSTIN :".$gst_no ,0,1,'L');

/*---------------- END ----------------------------------*/

/*----------------- invoice details with sub row --------*/

$pdf->SetXY(142,16);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Invoice Details" ,0,1,'L');

$pdf->SetXY(137,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Invoice No" ,0,'L');

$pdf->SetXY(153,25);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, ": ".$invoice_no ,0,'L');

$pdf->SetXY(137,32);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Invoice Date" ,0,'L');


$pdf->SetXY(154,32.2);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, ": ".$invoice_date ,0,'L');

/*---------------- END ---------------------------------*/

/*---------------order details with sub row-------------*/

$pdf->SetXY(178,16);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Order Details" ,0,1,'L');

$pdf->SetXY(171,20);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Order No:" ,0,'L');

$pdf->SetXY(190,20);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $so_order ,0,'L');

$pdf->SetXY(171,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Order Date:" ,0,'L');

$pdf->SetXY(190,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $sales_order_date ,0,'L');

$pdf->SetXY(171,30);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Delivery No:" ,0,'L');

$pdf->SetXY(171,35);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Delivery Date:" ,0,'L');

$pdf->SetXY(171,40);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Int Ref No:".$so_ref_no ,0,'L');

$pdf->SetXY(171,45);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Reference:".$ref_no ,0,'L');

/*--------------- END ----------------------------------*/ 


/*-------------- End ---------------------------------- */


/*--------------header line   row -2------------------ */

$pdf->Line(12, 110, 208, 110, $style);
$pdf->Line(60, 110, 60, 65, $style);
$pdf->Line(108, 110, 108, 65, $style);
$pdf->Line(158, 110, 158, 65, $style);
$pdf->Line(12, 72, 158, 72, $style);

$pdf->SetXY(25,66);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Bill To Partys" ,0,'L');

/*------------- Bill party with sub row ---------------*/

$pdf->SetXY(13,75);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Customer Code:" ,0,'L');

/** $pdf->SetXY(38,75);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $customer_number ,0,'L'); **/

$pdf->SetXY(13,79);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(45,2, $customer_name ,0,'L');

$pdf->SetXY(13,82);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $bill_to_address_1 ,0,'L');


$pdf->SetXY(13,90);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Tell No:" ,0,'L');

$pdf->SetXY(38,90);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $contact_number ,0,'L');

$pdf->SetXY(13,95);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Pan:" ,0,'L');

$pdf->SetXY(38,95);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $pan_no ,0,'L');

$pdf->SetXY(13,100);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "State:" ,0,'L');

$pdf->SetXY(38,100);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, $state_name ,0,'L');

$pdf->SetXY(13,105);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Place of Supply:" ,0,'L');

/*------------- End -----------------------------------*/

$pdf->SetXY(70,66);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Ship To Partys" ,0,'L');

/*------------- Bill party with sub row ---------------*/

$pdf->SetXY(61,90);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Tell No:" ,0,'L');

$pdf->SetXY(88,90);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $contact_number ,0,'L');

$pdf->SetXY(61,95);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "State Code:" ,0,'L');

$pdf->SetXY(88,95);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $state_code ,0,'L');

$pdf->SetXY(61,100);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Contact Person:" ,0,'L');

$pdf->SetXY(88,100);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $contact_person ,0,'L');

$pdf->SetXY(61,105);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "contact Person No:" ,0,'L');

$pdf->SetXY(88,105);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $contact_number ,0,'L');

/*------------- END -----------------------------------*/

$pdf->SetXY(125,66);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Details" ,0,'L');

/*------------- Details with sub row -------------------------------*/

$pdf->SetXY(109,75);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Terms Of Payment:" ,0,'L');

$pdf->SetXY(109,80);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Due Date:" ,0,'L');

$pdf->SetXY(109,85);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Gross Weight:" ,0,'L');

$pdf->SetXY(109,90);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Currency:" ,0,'L');

$pdf->SetXY(109,95  );
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Mode Of Transport:" ,0,'L');

/*------------- End -----------------------------------*/

/*----------------------------------------------------------- end ----------------------------------------------------------------------------- */


/*----------------------------------------------------------- header line   row -3------------------------------------------------------------- */


$pdf->SetXY(13,112);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Materials" ,0,'L');

$pdf->SetXY(42,112);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Product Name" ,0,'L');

$pdf->SetXY(68,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(10,2, "HSN/SAC" ,0,'L');

$pdf->SetXY(80,112);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Qty" ,0,'L');

$pdf->SetXY(94,112);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "GST" ,0,'L');

$pdf->SetXY(105,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Volume (Kg/Ltr)" ,0,'L');

$pdf->SetXY(119,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Rate (INR/%)" ,0,'L');

$pdf->SetXY(132,112);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Value" ,0,'L');

$pdf->SetXY(145,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "In Bill Desc" ,0,'L');

$pdf->SetXY(158,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Cash Desc" ,0,'L');

$pdf->SetXY(169,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Taxable Amount" ,0,'L');

$pdf->SetXY(181,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Tax Amount" ,0,'L');

$pdf->SetXY(195,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Total Amount" ,0,'L');


$x=150;
$y=150;
//dd($linetable);
$i=0;
$oldY=0;
$tax_tot=0;
$discont=0;
$unit_price=0;
$tot_amt=0;
$tot=0;
$tax_amt=0;
$tax_tot=0;
foreach($linedata as $key=>$value)
{


$i++;

$pdf->SetXY(195,$y-28);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $value['amount'] ,0,'L');

$pdf->SetXY(15,$y-28);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $value['line_no'] ,0,'L');

$pdf->SetXY(28,$y-30);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(35,2, $value['product'] ,0,'L');

$pdf->SetXY(182,$y-28);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $value['tax_amount_1'] ,0,'L');

$pdf->SetXY(182,$y-24);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $value['tax_amount_2'] ,0,'L');

$pdf->SetXY(169,$y-28);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $value['taxable_amount'] ,0,'L');

$pdf->SetXY(68,$y-28);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $value['hsn_code'] ,0,'L');

$pdf->SetXY(80,$y-28);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $value['qty'],0,'L');

$pdf->SetXY(90,$y-28);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $value['gst_per'],0,'L');

$pdf->SetXY(108,$y-28);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $value['uom_code'],0,'L');

$pdf->SetXY(120,$y-28);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $value['unit_price'] ,0,'L');

$pdf->SetXY(132,$y-28);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $value['amount'] ,0,'L');



$pdf->SetXY(158,$y-28);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $value['discount_amount'] ,0,'L');

$y=$y+5;


$oldY=$pdf->getY(); 


if($oldY>=155)
{
$pdf->Line(28, 110, 28, 290, $style);
$pdf->Line(66, 110, 66, 290, $style);
$pdf->Line(78, 110, 78, 290, $style);
$pdf->Line(90, 110, 90, 290, $style);

$pdf->Line(105, 110, 105, 290, $style);
$pdf->Line(118, 110, 118, 290, $style);
$pdf->Line(131, 110, 131, 290, $style);
$pdf->Line(144, 110, 144, 290, $style);
$pdf->Line(156, 110, 156, 290, $style);
$pdf->Line(169, 110, 169, 290, $style);
$pdf->Line(181, 110, 181, 290, $style);
$pdf->Line(195, 110, 195, 290, $style);
$pdf->Line(12, 120, 208, 120, $style);
}
else
{
$pdf->Line(28, 110, 28, 147, $style);
$pdf->Line(66, 110, 66, 147, $style);
$pdf->Line(78, 110, 78, 147, $style);
$pdf->Line(90, 110, 90, 147, $style);

$pdf->Line(105, 110, 105, 147, $style);
$pdf->Line(118, 110, 118, 147, $style);
$pdf->Line(131, 110, 131, 147, $style);
$pdf->Line(144, 110, 144, 147, $style);
$pdf->Line(156, 110, 156, 147, $style);
$pdf->Line(169, 110, 169, 147, $style);
$pdf->Line(181, 110, 181, 147, $style);
$pdf->Line(195, 110, 195, 147, $style);
$pdf->Line(12, 120, 208, 120, $style);
}








$discont+=$value['discount_amount'];

$uom="100";



$unit_price+=$value['unit_price'];

$dis=$value['discount_amount'];
$value=$value['qty']*$value['unit_price'];

$tax_tot+=$value;
$taxable_amount=$value-$dis;



$tax_amt+=$taxable_amount;
//dd($tax_amt);

$tax_amount=$taxable_amount*$tax_group_name/100;
$tax_value=$tax_amount/2;
//dd($tax_value);

//$tax_value


$totalamount=$taxable_amount+$tax_value+$tax_value;

$tot_amt+=$tax_value+$tax_value;
//dd($totalamount);



$tot+=$totalamount;


$y=$y+9;

if($oldY>=270)
{

// add a page
$pdf->AddPage('P', $resolution);

//$pdf->SetHeaderData('', '', 'CORRESPONDENCE LETTER', '');
$pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);


$pdf->Line(12, 65, 208, 65, $style);

/*------------------- header line   row -1 ----------------- */
$pdf->Line(55, 15, 208, 15, $style);
$pdf->Line(55, 7, 55, 65, $style);
$pdf->Line(97, 15, 97, 65, $style);
$pdf->Line(136, 15, 136, 65, $style);
$pdf->Line(170, 15, 170, 65, $style);

$pdf->SetXY(55,8);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "TAX INVOICE" ,0,1,'L');

$pdf->SetXY(85,8);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "PREVIEW" ,0,1,'L');

/*-------------- registered with sub row -------------------*/

$pdf->SetXY(57,16);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Registered Office Address" ,0,1,'L');

$pdf->SetXY(56,22);
$pdf->SetFont('','B','7.5');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(45,2, "".$company_address ,0,'L');

$pdf->SetXY(56,40);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Tel/Fax:" ,0,1,'L');

$pdf->SetXY(56,50);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Pan:".$pan_no ,0,1,'L');

/*--------------- end -------------------------------------*/


/*---------------- supplier location with row -------------*/
$pdf->SetXY(102,16);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->multiCell(30,2, "Supplying Location Address" ,0,'L');

$pdf->SetXY(98,40);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "State :".$state_name ,0,1,'L');

$pdf->SetXY(98,48);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "State Code :".$state_code ,0,1,'L');

$pdf->SetXY(98,55);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "GSTIN :".$gst_no ,0,1,'L');

/*---------------- END ----------------------------------*/

/*----------------- invoice details with sub row --------*/

$pdf->SetXY(142,16);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Invoice Details" ,0,1,'L');

$pdf->SetXY(137,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Invoice No" ,0,'L');

$pdf->SetXY(153,25);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, ": ".$invoice_no ,0,'L');

$pdf->SetXY(137,32);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Invoice Date" ,0,'L');


$pdf->SetXY(154,32.2);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, ": ".$invoice_date ,0,'L');

/*---------------- END ---------------------------------*/

/*---------------order details with sub row-------------*/

$pdf->SetXY(178,16);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Order Details" ,0,1,'L');

$pdf->SetXY(171,20);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Order No:" ,0,'L');

$pdf->SetXY(190,20);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $so_order ,0,'L');

$pdf->SetXY(171,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Order Date:" ,0,'L');

$pdf->SetXY(190,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $sales_order_date ,0,'L');

$pdf->SetXY(171,30);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Delivery No:" ,0,'L');

$pdf->SetXY(171,35);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Delivery Date:" ,0,'L');

$pdf->SetXY(171,40);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Int Ref No:".$so_ref_no ,0,'L');

$pdf->SetXY(171,45);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Reference:".$ref_no ,0,'L');

/*--------------- END ----------------------------------*/ 


/*-------------- End ---------------------------------- */


/*--------------header line   row -2------------------ */

$pdf->Line(12, 110, 208, 110, $style);
$pdf->Line(60, 110, 60, 65, $style);
$pdf->Line(108, 110, 108, 65, $style);
$pdf->Line(158, 110, 158, 65, $style);
$pdf->Line(12, 72, 158, 72, $style);

$pdf->SetXY(25,66);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Bill To Partys" ,0,'L');

/*------------- Bill party with sub row ---------------*/

$pdf->SetXY(13,75);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Customer Code:" ,0,'L');

/** $pdf->SetXY(38,75);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $customer_number ,0,'L'); **/

$pdf->SetXY(13,79);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(45,2, $customer_name ,0,'L');

$pdf->SetXY(13,82);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $bill_to_address_1 ,0,'L');


$pdf->SetXY(13,90);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Tell No:" ,0,'L');

$pdf->SetXY(38,90);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $contact_number ,0,'L');

$pdf->SetXY(13,95);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Pan:" ,0,'L');

$pdf->SetXY(38,95);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $pan_no ,0,'L');

$pdf->SetXY(13,100);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "State:" ,0,'L');

$pdf->SetXY(38,100);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, $state_name ,0,'L');

$pdf->SetXY(13,105);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Place of Supply:" ,0,'L');

/*------------- End -----------------------------------*/

$pdf->SetXY(70,66);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Ship To Partys" ,0,'L');

/*------------- Bill party with sub row ---------------*/

/**$pdf->SetXY(61,75);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Customer Code:" ,0,'L');

$pdf->SetXY(88,75);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $customer_site_number ,0,'L'); **/

$pdf->SetXY(61,90);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Tell No:" ,0,'L');

$pdf->SetXY(88,90);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $contact_number ,0,'L');

$pdf->SetXY(61,95);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "State Code:" ,0,'L');

$pdf->SetXY(88,95);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $state_code ,0,'L');

$pdf->SetXY(61,100);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Contact Person:" ,0,'L');

$pdf->SetXY(88,100);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $contact_person ,0,'L');

$pdf->SetXY(61,105);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "contact Person No:" ,0,'L');

$pdf->SetXY(88,105);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $contact_number ,0,'L');

/*------------- END -----------------------------------*/

$pdf->SetXY(125,66);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Details" ,0,'L');

/*------------- Details with sub row -------------------------------*/

$pdf->SetXY(109,75);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Terms Of Payment:" ,0,'L');

$pdf->SetXY(109,80);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Due Date:" ,0,'L');

$pdf->SetXY(109,85);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Gross Weight:" ,0,'L');

$pdf->SetXY(109,90);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Currency:" ,0,'L');

$pdf->SetXY(109,95  );
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Mode Of Transport:" ,0,'L');

/*------------- End -----------------------------------*/

/*----------------------------------------------------------- end ----------------------------------------------------------------------------- */


/*----------------------------------------------------------- header line   row -3------------------------------------------------------------- */


$pdf->SetXY(13,112);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Materials" ,0,'L');

$pdf->SetXY(42,112);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Product Name" ,0,'L');

$pdf->SetXY(68,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(10,2, "HSN/SAC" ,0,'L');

$pdf->SetXY(80,112);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Qty" ,0,'L');

$pdf->SetXY(94,112);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "GST" ,0,'L');

$pdf->SetXY(105,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Volume (Kg/Ltr)" ,0,'L');

$pdf->SetXY(119,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Rate (INR/%)" ,0,'L');

$pdf->SetXY(132,112);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Value" ,0,'L');

$pdf->SetXY(145,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "In Bill Desc" ,0,'L');

$pdf->SetXY(158,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Cash Desc" ,0,'L');

$pdf->SetXY(169,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Taxable Amount" ,0,'L');

$pdf->SetXY(181,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Tax Amount" ,0,'L');

$pdf->SetXY(195,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Total Amount" ,0,'L');
$y=154;

$oldY=$pdf->getY()-20;


}
}
if($oldY>=155)
{

// add a page
$pdf->AddPage('P', $resolution);

//$pdf->SetHeaderData('', '', 'CORRESPONDENCE LETTER', '');
$pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$pdf->SetFont('helvetica', '', 11);
$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);


$pdf->Line(12, 65, 208, 65, $style);

/*------------------- header line   row -1 ----------------- */
$pdf->Line(55, 15, 208, 15, $style);
$pdf->Line(55, 7, 55, 65, $style);
$pdf->Line(97, 15, 97, 65, $style);
$pdf->Line(136, 15, 136, 65, $style);
$pdf->Line(170, 15, 170, 65, $style);

$pdf->SetXY(55,8);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "TAX INVOICE" ,0,1,'L');

$pdf->SetXY(85,8);
$pdf->SetFont('','B','10');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "PREVIEW" ,0,1,'L');

/*-------------- registered with sub row -------------------*/

$pdf->SetXY(57,16);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Registered Office Address" ,0,1,'L');

$pdf->SetXY(56,22);
$pdf->SetFont('','B','7.5');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(45,2, "".$company_address ,0,'L');

$pdf->SetXY(56,40);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Tel/Fax:" ,0,1,'L');

$pdf->SetXY(56,50);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Pan:".$pan_no ,0,1,'L');

/*--------------- end -------------------------------------*/


/*---------------- supplier location with row -------------*/
$pdf->SetXY(102,16);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->multiCell(30,2, "Supplying Location Address" ,0,'L');

$pdf->SetXY(98,40);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "State :".$state_name ,0,1,'L');

$pdf->SetXY(98,48);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "State Code :".$state_code ,0,1,'L');

$pdf->SetXY(98,55);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "GSTIN :".$gst_no ,0,1,'L');

/*---------------- END ----------------------------------*/

/*----------------- invoice details with sub row --------*/

$pdf->SetXY(142,16);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Invoice Details" ,0,1,'L');

$pdf->SetXY(137,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Invoice No" ,0,'L');

$pdf->SetXY(153,25);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, ": ".$invoice_no ,0,'L');

$pdf->SetXY(137,32);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Invoice Date" ,0,'L');


$pdf->SetXY(154,32.2);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, ": ".$invoice_date ,0,'L');

/*---------------- END ---------------------------------*/

/*---------------order details with sub row-------------*/

$pdf->SetXY(178,16);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->Cell(104,0, "Order Details" ,0,1,'L');

$pdf->SetXY(171,20);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Order No:" ,0,'L');

$pdf->SetXY(190,20);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $so_order ,0,'L');

$pdf->SetXY(171,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Order Date:" ,0,'L');

$pdf->SetXY(190,25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $sales_order_date ,0,'L');

$pdf->SetXY(171,30);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Delivery No:" ,0,'L');

$pdf->SetXY(171,35);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Delivery Date:" ,0,'L');

$pdf->SetXY(171,40);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Int Ref No:".$so_ref_no ,0,'L');

$pdf->SetXY(171,45);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Reference:".$ref_no ,0,'L');

/*--------------- END ----------------------------------*/ 


/*-------------- End ---------------------------------- */


/*--------------header line   row -2------------------ */

$pdf->Line(12, 110, 208, 110, $style);
$pdf->Line(60, 110, 60, 65, $style);
$pdf->Line(108, 110, 108, 65, $style);
$pdf->Line(158, 110, 158, 65, $style);
$pdf->Line(12, 72, 158, 72, $style);

$pdf->SetXY(25,66);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Bill To Partys" ,0,'L');

/*------------- Bill party with sub row ---------------*/

$pdf->SetXY(13,75);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Customer Code:" ,0,'L');

/** $pdf->SetXY(38,75);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $customer_number ,0,'L'); **/

$pdf->SetXY(13,79);
$pdf->SetFont('','B','7');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(45,2, $customer_name ,0,'L');

$pdf->SetXY(13,82);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $bill_to_address_1 ,0,'L');


$pdf->SetXY(13,90);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Tell No:" ,0,'L');

$pdf->SetXY(38,90);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $contact_number ,0,'L');

$pdf->SetXY(13,95);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Pan:" ,0,'L');

$pdf->SetXY(38,95);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $pan_no ,0,'L');

$pdf->SetXY(13,100);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "State:" ,0,'L');

$pdf->SetXY(38,100);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(20,2, $state_name ,0,'L');

$pdf->SetXY(13,105);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Place of Supply:" ,0,'L');

/*------------- End -----------------------------------*/

$pdf->SetXY(70,66);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Ship To Partys" ,0,'L');

/*------------- Bill party with sub row ---------------*/

/**$pdf->SetXY(61,75);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Customer Code:" ,0,'L');

$pdf->SetXY(88,75);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $customer_site_number ,0,'L'); **/

$pdf->SetXY(61,90);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Tell No:" ,0,'L');

$pdf->SetXY(88,90);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $contact_number ,0,'L');

$pdf->SetXY(61,95);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "State Code:" ,0,'L');

$pdf->SetXY(88,95);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $state_code ,0,'L');

$pdf->SetXY(61,100);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Contact Person:" ,0,'L');

$pdf->SetXY(88,100);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $contact_person ,0,'L');

$pdf->SetXY(61,105);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "contact Person No:" ,0,'L');

$pdf->SetXY(88,105);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $contact_number ,0,'L');

/*------------- END -----------------------------------*/

$pdf->SetXY(125,66);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Details" ,0,'L');

/*------------- Details with sub row -------------------------------*/

$pdf->SetXY(109,75);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Terms Of Payment:" ,0,'L');

$pdf->SetXY(109,80);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Due Date:" ,0,'L');

$pdf->SetXY(109,85);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Gross Weight:" ,0,'L');

$pdf->SetXY(109,90);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Currency:" ,0,'L');

$pdf->SetXY(109,95  );
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Mode Of Transport:" ,0,'L');



$pdf->SetXY(13,112);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Materials" ,0,'L');

$pdf->SetXY(42,112);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Product Name" ,0,'L');

$pdf->SetXY(68,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(10,2, "HSN/SAC" ,0,'L');

$pdf->SetXY(80,112);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Qty" ,0,'L');

$pdf->SetXY(94,112);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "GST" ,0,'L');

$pdf->SetXY(105,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Volume (Kg/Ltr)" ,0,'L');

$pdf->SetXY(119,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Rate (INR/%)" ,0,'L');

$pdf->SetXY(132,112);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Value" ,0,'L');

$pdf->SetXY(145,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "In Bill Desc" ,0,'L');

$pdf->SetXY(158,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Cash Desc" ,0,'L');

$pdf->SetXY(169,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Taxable Amount" ,0,'L');

$pdf->SetXY(181,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Tax Amount" ,0,'L');

$pdf->SetXY(195,111);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(14,2, "Total Amount" ,0,'L');




$pdf->SetXY(15,148);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Total" ,0,'L');

$pdf->SetXY(15,213);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Total Invoice Value ( In Words ):" ,0,'L');

$pdf->SetXY(55,213);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,0, convert_number_to_words($tot) ,0,'R');




$i=1;
$y=150;
for($i=1;$i<=2;$i++){
$pdf->SetXY(15,$y-28);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(104,2, $i ,0,'L');
$pdf->Line(12, $y-18, 208, $y-18);
$y=$y+15;
}

$pdf->Line(12, 153, 208, 153, $style);
$pdf->Line(100, 153, 100, 212,$style);
$pdf->Line(150, 153, 150, 212, $style);
$pdf->Line(180, 153, 180, 212, $style);
/*----------------------- end -------------------------- */

/*--------------- header line   row -4----------------- */
$pdf->Line(12, 225, 208, 225, $style);
$pdf->Line(12, 258, 208, 258, $style);
$pdf->Line(12, 270, 208, 270, $style);
$pdf->Line(90, 225, 90, 258, $style);
$pdf->Line(130, 225, 130, 258, $style);
$pdf->Line(165, 225, 165, 258, $style);


$i=0;
$y=178;
for($i=0;$i<=7;$i++)
{

$pdf->SetXY(15,$y-20);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(104,2, $i ,0,'L');
$pdf->Line(100, $y-18, 208, $y-18);

$y=$y+6.5;

}



if($linedata[0]['gst_igst']=="GST")
{
$summery=array("valve Sale","Site Special Discount 65-20-INR","Credit Discount","Taxable Amount","IN:Central ".$linedata[0]['gst_igst']."-OP 9%","IN:State ".$linedata[0]['gst_igst']."-OP 9%","Commercial Rounding","Total Discount Amount");

$pdf->Line(12, 186, 100, 186);
$pdf->Line(12, 192.5, 100, 192.5);
$pdf->Line(12, 199, 100, 199);
$pdf->Line(70, 186, 70, 199,$style);



$pdf->SetXY(15,194);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,0, "IN:Central GST-OP 14%" ,0,'L');

$pdf->SetXY(15,187);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,0, "IN:Central GST-OP 14%" ,0,'L');



}
else
{
$summery=array("valve Sale","Site Special Discount 65-20-INR","Credit Discount","Taxable Amount","IN:Central SGST-OP 14%","IN:State CGST-OP 14%","Commercial Rounding","Total Discount Amount");
}




	
foreach($summery as $key=>$value)
{
$pdf->SetXY(102,$y-69);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,0,$value,0,'L');
$y=$y+6.5;
}


$x=150;
$header=array("Summery","Taxable Amount","Total Amount");
foreach($header as $key=>$value)
{

$pdf->SetXY($x-25,155);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,0,$value,0,'L');
$x=$x+30;

}
/******** gst % divide with tax amount **********/

$y=235;
$x=15;
$pdf->SetXY($x+62,$y-47);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,0, "". $tot_tax_amt_x,0,'L');

$pdf->SetXY($x+62,$y-41);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,0, "". $tot_tax_amt_x,0,'L');

$pdf->SetXY($x+145,$y-47);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,0, "".$tot_tax_amt_y  ,0,'L');

$pdf->SetXY($x+145,$y-41);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,0, "".$tot_tax_amt_y  ,0,'L');

     /************ End **********/



$pdf->SetXY(132,148);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $tax_tot,0,'L');

$pdf->SetXY(158,148);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $discont,0,'L');


$pdf->SetXY(118,148);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $unit_price,0,'L');

$pdf->SetXY(181,148);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $tot_amt,0,'L');


/*$pdf->SetXY(195,148);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $tot,0,'L');*/

$pdf->SetXY(195,148);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $tax_tot,0,'L');

$pdf->SetXY(185,187);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(104,2, $tax_value,0,'L');

$pdf->SetXY(170,148);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $tax_amt,0,'L');



/*********** total Amount***********8****/

$pdf->SetXY(185,174);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $discont,0,'L');

$pdf->SetXY(185,181);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $tot_amt,0,'L');

$pdf->SetXY(185,207);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $tot,0,'L');

$pdf->SetXY(185,194);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(104,2, $tax_value,0,'L');


$pdf->SetXY(185,161);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $tax_tot,0,'L');


/***********taxable amount **************/

$pdf->SetXY(185,187);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $tot_tax_amt,0,'L');

$pdf->SetXY(185,194);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $tot_tax_amt,0,'L');




$y=150;
$sub_total=array($discont,$value,$tax_value,$tax_value,'',$tot);
foreach($sub_total as $key=>$value)
{
$pdf->SetXY(185,$y+25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(104,2, $value,0,'L');
$y=$y+6.5;

}

$pdf->SetXY(15,226);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Corporate Identification Number (CIN): " ,0,'L');

/*----------------------- Corporate with consumer ------------*/

$pdf->SetXY(13,230);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,4, "For Consumer Quiries/Complaints/Dealership Enquiries,email to customercare@premium.com,For HR Related Quiries email to careers@premium.com,For Media Related Quiries, email to prooffice@premium.com,For share Related Quiries email to investor.relations@premium.com" ,0,'L');

/*----------------------- END --------------------------------*/


$pdf->SetXY(90,226);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('','','');
$pdf->MultiCell(104,2,"Customer Acknoledgement",0,'L');

/*----------------------- customer with sub row ---------------*/

$pdf->SetXY(90,232);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('','','');
$pdf->MultiCell(104,2,"Receipt Date:",0,'L');

$pdf->SetXY(90,238);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('','','');
$pdf->MultiCell(104,2,"Receipt Time:",0,'L');

$pdf->SetXY(90,244);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('','','');
$pdf->MultiCell(104,2,"Customer Sign & Stamp",0,'L');

/*--------------------- END ----------------------------------*/


$pdf->SetXY(90,226);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('','','');
$pdf->MultiCell(104,2,"Customer Acknoledgement",0,'L');

$pdf->SetXY(135,226);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('','','');
$pdf->MultiCell(104,2,"Package Summery",0,'L');

$pdf->SetXY(170,226);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('','','');
$pdf->MultiCell(104,2,"Authorised Signature",0,'L');

$pdf->SetXY(92,258);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('','','');
$pdf->MultiCell(104,2,"Declaration",0,'L');

//$pdf->Line(156, 220, 156, 165, $style);
//$pdf->Line(82, 120, 82, 110, $style);
//$pdf->Line(94, 120, 94, 110, $style);

//$pdf->Line(12, 120, 208, 120, $style);
//$pdf->Line(12, 158, 208, 158, $style);
/*----------------- end ------------------- */
//border line//
$pdf->Line(12, 7, 12, 290, $style);
$pdf->Line(12, 7, 208, 7, $style);
$pdf->Line(208, 7, 208, 290, $style);
$pdf->Line(12, 290, 208, 290, $style);


$pdf->Line(12, 65, 208, 65, $style);

$pdf->Line(12, 212, 208, 212, $style);
$pdf->Line(12, 218, 208, 218, $style);

$pdf->Line(28, 110, 28, 147, $style);
$pdf->Line(66, 110, 66, 147, $style);
$pdf->Line(78, 110, 78, 147, $style);
$pdf->Line(90, 110, 90, 147, $style);

$pdf->Line(105, 110, 105, 147, $style);
$pdf->Line(118, 110, 118, 147, $style);
$pdf->Line(131, 110, 131, 147, $style);
$pdf->Line(144, 110, 144, 147, $style);
$pdf->Line(156, 110, 156, 147, $style);
$pdf->Line(169, 110, 169, 147, $style);
$pdf->Line(181, 110, 181, 147, $style);
$pdf->Line(195, 110, 195, 147, $style);
$pdf->Line(12, 120, 208, 120, $style);



}
else
{



$pdf->SetXY(15,148);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Total" ,0,'L');

$pdf->SetXY(15,213);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Total Invoice Value ( In Words ):" ,0,'L');

$pdf->SetXY(55,213);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,0, convert_number_to_words($tot) ,0,'R');




$i=1;
$y=150;
for($i=1;$i<=2;$i++){
$pdf->SetXY(15,$y-28);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(104,2, $i ,0,'L');
$pdf->Line(12, $y-18, 208, $y-18);
$y=$y+15;
}

$pdf->Line(12, 153, 208, 153, $style);
$pdf->Line(100, 153, 100, 212,$style);
$pdf->Line(150, 153, 150, 212, $style);
$pdf->Line(180, 153, 180, 218, $style);
/*----------------------- end -------------------------- */

/*--------------- header line   row -4----------------- */
$pdf->Line(12, 225, 208, 225, $style);
$pdf->Line(12, 258, 208, 258, $style);
$pdf->Line(12, 270, 208, 270, $style);
$pdf->Line(90, 225, 90, 258, $style);
$pdf->Line(130, 225, 130, 258, $style);
$pdf->Line(165, 225, 165, 258, $style);


$i=0;
$y=178;
for($i=0;$i<=7;$i++)
{

$pdf->SetXY(15,$y-20);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(104,2, $i ,0,'L');
$pdf->Line(100, $y-18, 208, $y-18);

$y=$y+6.5;

}



if($linedata[0]['gst_igst']=="GST")
{
$summery=array("valve Sale","Site Special Discount 65-20-INR","Credit Discount","Taxable Amount","IN:Central ".$linedata[0]['gst_igst']."-OP 9%","IN:State ".$linedata[0]['gst_igst']."-OP 9%","Commercial Rounding","Total Discount Amount");

$pdf->Line(12, 186, 100, 186);
$pdf->Line(12, 192.5, 100, 192.5);
$pdf->Line(12, 199, 100, 199);
$pdf->Line(70, 186, 70, 199,$style);



$pdf->SetXY(15,194);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,0, "IN:Central GST-OP 14%" ,0,'L');

$pdf->SetXY(15,187);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,0, "IN:Central GST-OP 14%" ,0,'L');



}
else
{
$summery=array("valve Sale","Site Special Discount 65-20-INR","Credit Discount","Taxable Amount","IN:Central SGST-OP 14%","IN:State CGST-OP 14%","Commercial Rounding","Total Discount Amount");
}




	
foreach($summery as $key=>$value)
{
$pdf->SetXY(102,$y-69);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,0,$value,0,'L');
$y=$y+6.5;
}


$x=150;
$header=array("Summery","Taxable Amount","Total Amount");
foreach($header as $key=>$value)
{

$pdf->SetXY($x-25,155);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,0,$value,0,'L');
$x=$x+30;

}

$y=235;
$x=15;
$pdf->SetXY($x+62,$y-47);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,0, "". $tot_tax_amt_x,0,'L');

$pdf->SetXY($x+62,$y-41);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,0, "". $tot_tax_amt_x,0,'L');

$pdf->SetXY($x+145,$y-47);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,0, "".$tot_tax_amt_y  ,0,'L');

$pdf->SetXY($x+145,$y-41);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(120,0, "".$tot_tax_amt_y  ,0,'L');




$pdf->SetXY(132,148);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $tax_tot,0,'L');

$pdf->SetXY(158,148);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $discont,0,'L');


$pdf->SetXY(118,148);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $unit_price,0,'L');

$pdf->SetXY(181,148);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $tot_amt,0,'L');


/*$pdf->SetXY(195,148);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $tot,0,'L');*/

$pdf->SetXY(195,148);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $tax_tot,0,'L');

$pdf->SetXY(185,187);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(104,2, $tax_value,0,'L');

$pdf->SetXY(170,148);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $tax_amt,0,'L');



/*********** total Amount***********8****/

$pdf->SetXY(185,174);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $discont,0,'L');

$pdf->SetXY(185,181);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $tot_amt,0,'L');



$pdf->SetXY(185,194);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(104,2, $tax_value,0,'L');


$pdf->SetXY(185,161);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $tax_tot,0,'L');


/***********taxable amount **************/

$pdf->SetXY(187,213);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, $line_total,0,'L');

$pdf->SetXY(185,194);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "",0,'L');




$y=150;
$sub_total=array($discont,$value,$tax_value,$tax_value,'',$tot);
foreach($sub_total as $key=>$value)
{
$pdf->SetXY(185,$y+25);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
//$pdf->MultiCell(104,2, $value,0,'L');
$y=$y+6.5;

}








$pdf->SetXY(15,226);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(104,2, "Corporate Identification Number (CIN): " ,0,'L');

/*----------------------- Corporate with consumer ------------*/

$pdf->SetXY(13,230);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(80,4, "For Consumer Quiries/Complaints/Dealership Enquiries,email to customercare@premium.com,For HR Related Quiries email to careers@premium.com,For Media Related Quiries, email to prooffice@premium.com,For share Related Quiries email to investor.relations@premium.com" ,0,'L');

/*----------------------- END --------------------------------*/


$pdf->SetXY(90,226);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('','','');
$pdf->MultiCell(104,2,"Customer Acknowledgement",0,'L');

/*----------------------- customer with sub row ---------------*/

$pdf->SetXY(90,232);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('','','');
$pdf->MultiCell(104,2,"Receipt Date:",0,'L');

$pdf->SetXY(90,238);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('','','');
$pdf->MultiCell(104,2,"Receipt Time:",0,'L');

$pdf->SetXY(90,244);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('','','');
$pdf->MultiCell(104,2,"Customer Sign & Stamp",0,'L');

/*--------------------- END ----------------------------------*/


$pdf->SetXY(90,226);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('','','');
$pdf->MultiCell(104,2,"Customer Acknoledgement",0,'L');

$pdf->SetXY(135,226);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('','','');
$pdf->MultiCell(104,2,"Package Summery",0,'L');

$pdf->SetXY(170,226);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('','','');
$pdf->MultiCell(104,2,"Authorised Signature",0,'L');

$pdf->SetXY(92,258);
$pdf->SetFont('','B','8');
$pdf->SetTextColor('','','');
$pdf->MultiCell(104,2,"Declaration",0,'L');

$pdf->Line(12, 212, 208, 212, $style);
$pdf->Line(12, 218, 208, 218, $style);

$pdf->Line(28, 110, 28, 147, $style);
$pdf->Line(66, 110, 66, 147, $style);
$pdf->Line(78, 110, 78, 147, $style);
$pdf->Line(90, 110, 90, 147, $style);

$pdf->Line(105, 110, 105, 147, $style);
$pdf->Line(118, 110, 118, 147, $style);
$pdf->Line(131, 110, 131, 147, $style);
$pdf->Line(144, 110, 144, 147, $style);
$pdf->Line(156, 110, 156, 147, $style);
$pdf->Line(169, 110, 169, 147, $style);
$pdf->Line(181, 110, 181, 147, $style);
$pdf->Line(195, 110, 195, 147, $style);
$pdf->Line(12, 120, 208, 120, $style);



//$pdf->Line(156, 220, 156, 165, $style);
//$pdf->Line(82, 120, 82, 110, $style);
//$pdf->Line(94, 120, 94, 110, $style);

//$pdf->Line(12, 120, 208, 120, $style);
//$pdf->Line(12, 158, 208, 158, $style);
/*----------------------------------------------------------- end ----------------------------------------------------------------------------- */
}

$pdf->LastPage();















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
