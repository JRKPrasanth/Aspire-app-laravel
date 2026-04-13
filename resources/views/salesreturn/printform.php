<?php

// dd('l');
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
    $words = array(0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $digits = array('', 'Hundred','Thousand','Lakh', 'Crore','Million');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        //if ($number) {
        if ($number>0) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' And ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal) ? "" . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise.' Only' ;
   

  //  return ($Rupees ? $Rupees : '');
}

class MYPDF extends TCPDF { 
    
public $style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//Page header

    public function setLogo($company_name,$company_address,$company_city,$cin_no,$cmp_gst_no,$state_name,$state_code,$website_address,$customer_name,$ship_to_address_1,$contact_number,$ship_gst_no,$state_name_ship,$rma_ref_no,$return_date,$remarks,$invoice_no,$invoice_date,$despatch_through,$payment_term_name,$gsttotal,$gst,$so_order,$so_date,$dispatch_no,$dispatch_date,$customer_po,$line_total,$tot_tax_amt,$sub_total,$linedata){
        $this->company_name = $company_name;
        $this->company_address = $company_address;
        $this->company_city = $company_city;
        $this->cin_no = $cin_no;
        $this->cmp_gst_no = $cmp_gst_no;
        $this->state_name = $state_name;
        $this->state_code = $state_code;
        $this->website_address = $website_address;
        $this->customer_name = $customer_name;
        $this->ship_to_address_1 = $ship_to_address_1;
        $this->contact_number = $contact_number;
        $this->ship_gst_no = $ship_gst_no;
        $this->state_name_ship = $state_name_ship;
        $this->rma_ref_no = $rma_ref_no;
        $this->return_date = $return_date;
        $this->remarks = $remarks;
        $this->invoice_no = $invoice_no;
        $this->invoice_date = $invoice_date;
        $this->despatch_through = $despatch_through;
        $this->payment_term_name = $payment_term_name;
        $this->gsttotal = $gsttotal;
        $this->gst = $gst;
        $this->so_order = $so_order;
        $this->so_date = $so_date;
        $this->dispatch_no = $dispatch_no;
        $this->dispatch_date = $dispatch_date;
        $this->customer_po = $customer_po;
        $this->line_total = $line_total;
        $this->tot_tax_amt = $tot_tax_amt;
        $this->sub_total = $sub_total;
        $this->linedata = $linedata;
    
    }
    public function fopage($totalpage){
        $this->totalpage = $totalpage;
    }
    
    public function Header() {
        // Logo    

$this->Line(12, 7, 12, 210, $this->style);
$this->Line(12, 7, 296, 7, $this->style);
$this->Line(296, 7, 296, 210, $this->style);
$this->Line(12, 210, 296, 210, $this->style);
        $image_file = public_path().'/images/backend-logo.jpg';
        $this->Image($image_file, 13 , 8, 18, 13, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 14);
        // Title


        $this->SetXY(145,16);
        $this->SetFont('times','B','12');
        $this->SetTextColor('0','B','0');
        $this->Cell(120,0, "CREDIT NOTE"    ,0,1,'M');

$this->Line(12, 22, 296, 22, $this->style);
$this->SetXY(160,23);
$this->SetFont('','B','9');
$this->SetTextColor('0','0','0');
$this->Cell(0,0, $this->company_name ,0,1,'L');



$this->SetXY(160,27);
$this->SetFont('','','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(133,5, $this->company_address ,0,1,'');
$this->SetXY(160,31);
$this->SetFont('','','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(133,5, $this->company_city ,0,1,'');

$this->SetXY(160,35);
$this->SetFont('','','9');
$this->SetTextColor('0','0','0');
$this->Cell(0,0, "CIN: ".$this->cin_no ,0,1,'L');

$this->SetXY(235,35);
$this->SetFont('','','9');
$this->SetTextColor('0','0','0');
$this->Cell(0,0, "GSTIN/UIN: ".$this->cmp_gst_no ,0,1,'L');

$this->SetXY(160,39);
$this->SetFont('','','9');
$this->SetTextColor('0','0','0');
$this->Cell(0,0, "State Name : ".$this->state_name.", Code : ".$this->state_code,0,1,'L');



$this->SetXY(160,43);
$this->SetFont('','','8');
$this->SetTextColor('0','0','0');
$this->Cell(0,0, "Website : ".$this->website_address,0,1,'L');

$this->Line(12, 47, 296, 47, $this->style);

$this->Line(158, 22, 158, 47, $this->style);
$this->SetXY(13,23);
$this->SetFont('','B','10');
$this->SetTextColor('0','0','0');
$this->Cell(0,0, "Buyer" ,0,1,'L');

$this->SetXY(13,27);
$this->SetFont('','','10');
$this->SetTextColor('0','0','0');
$this->Cell(0,0, $this->customer_name ,0,1,'L');

$this->SetXY(13,31);
$this->SetFont('','','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(143,5, $this->ship_to_address_1 ,0,'L');
$oldad = $this->getY();

$this->SetXY(13,$oldad-1);
$this->SetFont('','','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, "PH : ".$this->contact_number ,0,'L');

$this->SetXY(13,$oldad+3);
$this->SetFont('','','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, "GSTIN/UIN : ".$this->ship_gst_no ,0,'L');

// $this->SetXY(13,$oldad+7);
// $this->SetFont('','','9');
// $this->SetTextColor('0','0','0');
// $this->MultiCell(100,2, "State Name : ".$this->state_name_ship ,0,'L');

$this->SetXY(13,48);
$this->SetFont('','','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, "Sales Return No :",0,'L');

$this->SetXY(35,48);
$this->SetFont('','B','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, $this->rma_ref_no,0,'L');

$this->SetXY(65,48);
$this->SetFont('','','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, "Dated :",0,'L');

$this->SetXY(75,48);
$this->SetFont('','B','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, $this->return_date,0,'L');

//$this->Line(109, 29, 296, 29, $this->style);

$this->SetXY(115,48);
$this->SetFont('','','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, "Delivery Note :",0,'L');

$this->SetXY(135,48);
$this->SetFont('','B','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, $this->dispatch_no,0,'L');

$this->SetXY(165,48);
$this->SetFont('','','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, "Delivery Note Date :",0,'L');

$this->SetXY(191,48);
$this->SetFont('','B','8');
$this->SetTextColor('0','0','0');
//$this->MultiCell(100,2, $dispatch_date,0,'L');
$this->MultiCell(100,2, $this->dispatch_date,0,'L');

//$this->Line(109, 37, 296, 37, $this->style);

$this->SetXY(230,48);
$this->SetFont('','','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, "Customer Po Number :",0,'L');

$this->SetXY(260	,48);
$this->SetFont('','B','8');
$this->SetTextColor('0','0','0');
//$this->MultiCell(100,2, $customer_po_number,0,'L');
$this->MultiCell(100,2, $this->customer_po,0,'L');

$this->SetXY(12,56);
$this->SetFont('','','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, "Other Reference(s):",0,'L');

$this->SetXY(36,56);
$this->SetFont('','B','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, $this->remarks,0,'L');

//$this->Line(13, 44, 296, 44, $this->style);


/*$this->SetXY(13,52);
$this->SetFont('','','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, "Sales Order No :",0,'L');

$this->SetXY(35,52);
$this->SetFont('','','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, $this->so_order,0,'L');*/

$this->SetXY(75,52);
$this->SetFont('','B','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, $this->so_date,0,'L');

$this->SetXY(65,52);
$this->SetFont('','','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, "Dated :",0,'L');

$this->SetXY(115,52);
$this->SetFont('','','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, "Mode/Terms of Payment :",0,'L');

$this->SetXY(148,52);
$this->SetFont('','B','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, $this->payment_term_name,0,'L');

$this->SetXY(115,56);
$this->SetFont('','','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, "Invoice No :",0,'L');

$this->SetXY(148,56);
$this->SetFont('','B','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, $this->invoice_no,0,'L');

$this->SetXY(165,56);
$this->SetFont('','','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, "Invoice Date  :",0,'L');

$this->SetXY(192,56);
$this->SetFont('','B','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, $this->invoice_date,0,'L');

$this->SetXY(230,52);
$this->SetFont('','','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, "Despatched through    :",0,'L');

$this->SetXY(80,80);
$this->SetFont('','B','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, $this->despatch_through,0,'L');

$this->SetXY(165,52);
$this->SetFont('','','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, "Place Of Supply      :",0,'L');

$this->SetXY(192,52);
$this->SetFont('','B','8');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, $this->state_name_ship,0,'L');

/************************************Header bottom line******************************/
$this->Line(12, 70, 296, 70, $this->style);





$this->SetXY(13,62);
$this->SetFont('','B','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(100,2, "S.No",0,'L');

$this->SetXY(30,62);
$this->SetFont('','B','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(80,2, "Description of Goods",0,'C');


$this->SetXY(130,62);
$this->SetFont('','B','9');
$this->SetTextColor('0','0','0');
$this->Cell(15,2, "Batch No",0,'C');

$this->SetXY(150,62);
$this->SetFont('','B','9');
$this->SetTextColor('0','0','0');
$this->Cell(15,2, "Mfg Date",0,'C');

$this->SetXY(172,62);
$this->SetFont('','B','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(15,2, "Expire Date",0,'C');


$this->SetXY(190,62);
$this->SetFont('','B','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(20,2, "Quantity",0,'C');

$this->SetXY(212,62);
$this->SetFont('','B','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(20,2, "Rate",0,'C');

$this->SetXY(230,62);
$this->SetFont('','B','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(20,2, "Per",0,'C');

$this->SetXY(248,62);
$this->SetFont('','B','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(20,2, "Disc. %",0,'C');

$this->SetXY(270,62);
$this->SetFont('','B','9');
$this->SetTextColor('0','0','0');
$this->MultiCell(20,2, "Amount",0,'C');

/* End */

$this->Line(12,61,296,61);
//$this->Line(12,75,208,75);
$this->Line(26,61,26,210);
//$this->Line(78,65,78,270);
//$this->Line(140,65,140,270);
//$this->Line(140,70,140,275);
$this->Line(129,61,129,210);
$this->Line(149,61,149,210);
$this->Line(168,61,168,210);
$this->Line(188,61,188,210);
$this->Line(212,61,212,210);
$this->Line(233,61,233,210);
$this->Line(247,61,247,210);
$this->Line(270,61,270,210);


$this->Line(12,270,208,270);

$this->SetXY(140,42);
$this->SetFont('','','10');
$this->SetTextColor('0','0','0');
//$this->MultiCell(65,12,$company_address,'0','L');
 }
    public function MultiRow($value,$i,$discount,$tax_amt,$pdf) { 
      
             $y = $this->getY();

    // dd($value);
	    $this->SetXY(13,$y);
	    $this->SetFont('','','8.5');
	    $this->SetTextColor('0','0','0');
	    $this->Cell(0,0, $i ,0,1,'L');
        $y = $this->getY()-5;

	    
		
	    $this->SetXY(135,$y);
	    $this->SetFont('','','10');
	    $this->SetTextColor('0','0','0');
	    $this->MultiCell(22,2,$value['batch_number'],0,'L');
	      
	    $this->SetXY(150,$y);
	    $this->SetFont('','','10');
	    $this->SetTextColor('0','0','0');
	    $this->Cell(13,0, $value['mfg_date'],0,1,'L');
	     
	     $this->SetXY(174,$y);
	    $this->SetFont('','','10');
	    $this->SetTextColor('0','0','0');
	    $this->Cell(13,0, $value['exp_date'],0,1,'R');
	      
	            $this->SetXY(195,$y);
	    $this->SetFont('','','10');
	    $this->SetTextColor('0','0','0');
	    $this->Cell(3,0, $value['qty'] ,0,1,'R');

	    $this->SetXY(210,$y);
	    $this->SetFont('','','10');  
	    $this->SetTextColor('0','0','0');
	    $this->Cell(18,0, number_format($value['unit_price'],'2','.','') ,0,1,'R');
	    $this->SetXY(235,$y);
	    $this->SetFont('','','9');
	    $this->SetTextColor('0','0','0');
	    $this->multiCell(70,2,$value['uom_code'] ,0,'L');
	     
    $this->SetXY(250,$y);
    $this->SetFont('','','10');
    $this->SetTextColor('0','0','0');
    $this->MultiCell(55,9,$value['discount_per'],'0','L');
    
     $this->SetXY(275,$y);
    $this->SetFont('','','10');
    $this->SetTextColor('0','0','0');
    $this->MultiCell(55,9,number_format($value['amount'],2),'0','L');
 
  $this->SetXY(28,$y);
	    $this->SetFont('','','10');
	    $this->SetTextColor('0','0','0');
	    $this->MultiCell(99,7,$value['product'] ,0,'L');
	      $yy = $this->getY();
   $this->setY($yy);
    }
   
    // Page footer
    public function Footer() {
    
        // Position at 15 mm from bottom
        $this->SetY(-7);
        // Set font
        $this->SetFont('times', 'B', 8);
        // Page number
      $this->Cell(0, 10, 'Page No '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');  
       
              
		if($this->pageNo()==$this->totalpage)
        {   
             $totals=0;
    $discountamt=0;
    $gross_total=0;
    $hsn_y=0;
    $hsny=145;
    $gst_total=0;
    $sgstval=0;
    $cgstval=0;
    $gstamt12=0;
            $gstamt=0;
             $this->Rect(12, 140, 284, 70,'DF', "",  array(255, 255, 255));   
            // dd($this->gst);
 foreach($this->gst as $key=>$gstvalue)
    {
     
        if($gstvalue['gsttype'] !="IGST") 
        { 
           $cgst=$gstvalue['gst'][0]/2;
          $gstamt=$gstvalue['gst_val'];
             $gstval=implode('',$gstvalue['gst']);
            $this->SetXY(35,$hsny-3);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            $this->Cell(19,10,$cgst." %" ,0,'R');  
            $x=$this->getX();
            $this->SetXY(13,$hsny);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            $this->Cell(0,0, 'Output SGST ' ,0,1,'L');  
        
         $this->SetXY($x+18,$hsny);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            $this->Cell(0,0, 'Output CGST ' ,0,1,'L');  


            $this->SetXY($x+38,$hsny-3);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            
            $this->Cell(19,10,$cgst." %" ,0,'R');
            
            $gstvalue1=$gstvalue['gst_val']/2;

            $sgstval+=$this->tot_tax_amt/2;
            $cgstval+=$this->tot_tax_amt/2;

            $this->SetXY(42,$hsny);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            //$pdf->Cell(0,0, "Sgst" ,0,1,'L');
            $this->Cell(0,0,': ' .number_format($gstvalue['sgst_val'],2),0,1,'L');

            $this->SetXY(84,$hsny);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            //$pdf->Cell(0,0, "Sgst" ,0,1,'L');
            $this->Cell(0,0,': ' .number_format($gstvalue['sgst_val'],2) ,0,1,'L');
            $gstamt12 +=$gstamt;
        }
        else
        {
           $gst=$gstvalue['gst'][0];
          // dd($gst);
          $gstamt1=$gstvalue['gst_val'];
          //dd($gstamt1);
            $gstval=implode('',$gstvalue['gst']);
             $this->SetXY(30,$hsny-2);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
           $this->MultiCell(19,10, $gstvalue['gst'][0]." %" ,0,'L');
                
    
            $this->SetXY(13,$hsny-2);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            $this->MultiCell(19,10, "Output IGST " ,0,'L');
                
            $this->SetXY(40,$hsny-2);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            $this->MultiCell(19,10,': '. number_format($gstvalue['gst_val'],2) ,0,'L');
           $gstamt12 +=$gstamt1;
        }

            
        $hsny=$hsny+5;
		 
		// $totals=$tax_amt+$ab+$other_charges;
		 $totals=100;
		//dd($totals);
      

       /* $pdf->SetXY(157,235);
        $pdf->SetFont('','B','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->Cell(125,0, "Net Total" ,0,1,'L'); 
         //dd($masterto); 
        $pdf->SetXY(184,235);
        $pdf->SetFont('','B','9');
        $pdf->SetTextColor('0','0','0');
        $pdf->multiCell(23,0, $totals ,0,'R');*/
         
    }

     $this->SetXY(13,$hsny);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            $this->Cell(20,2, "Discount",0,'C');

             $sum = array_column($this->linedata, 'discount_amount');
                $sum1 = array_sum($sum);

            $this->SetXY(40,$hsny);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            $this->Cell(20,2, " : ".number_format($sum1,2),0,'C');
            
         $this->SetXY(195,148);
        $this->SetFont('','B','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(125,0, "Tax Amount" ,0,1,'L'); 

   
        $this->SetXY(278,148);
        $this->SetFont('','','9');
        $this->SetTextColor('0','0','0');
        $this->multiCell(0,0,number_format(($gstamt12),2) ,0,'L');     
        // $this->Rect(12, 224, 284, 70,'DF', "",  array(255, 255, 255));

             $this->SetXY(195,142);
        $this->SetFont('','B','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(125,0, "Total" ,0,1,'L'); 

   
        $this->SetXY(278,142);
        $this->SetFont('','','9');
        $this->SetTextColor('0','0','0');
        $this->MultiCell(0,0,number_format($this->sub_total,2) ,0,'L'); 
           

$ta=1+$this->gsttotal;
$round_off = $ta-(round($ta));
$ka=$this->gsttotal/2;
$words = '';
/*if(count($this->gst) > 0 ){
$words = preg_replace('/[^a-zA-Z]/', '', $this->gst[0]['tax_group_name']);
if($words == "GST"){
    $this->SetXY(195,156);
    $this->SetFont('','B','8');
    $this->SetTextColor('0','0','0');
    $this->MultiCell(150,2, "SGST",0,'L');

    $this->SetXY(280,156);
    $this->SetFont('','','9');
    $this->SetTextColor('0','0','0');
    //$pdf->Cell(0,0,$ka ,0,1,'L');
    $this->MultiCell(150,2, number_format( $ka,'2','.',''),0,'L'); 


    $this->SetXY(195,159);
    $this->SetFont('','B','8');
    $this->SetTextColor('0','0','0');
    $this->MultiCell(150,2, "CGST",0,'L');

    $this->SetXY(280,159);
    $this->SetFont('','','9');
    $this->SetTextColor('0','0','0');
    $this->MultiCell(150,2, number_format( $ka,'2','.',''),0,'L'); 
}else{
    $this->SetXY(195,158);
    $this->SetFont('','B','8');
    $this->SetTextColor('0','0','0');
    $this->MultiCell(150,2, "IGST",0,'L');

    $this->SetXY(280,158);
    $this->SetFont('','','9');
    $this->SetTextColor('0','0','0');
    //$pdf->Cell(0,0,$ka ,0,1,'L');
    $this->MultiCell(150,2, number_format( $this->gsttotal,'2','.',''),0,'L'); 
}
}*/

            $this->SetXY(195,162);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
          //  $this->Cell(20,2, "Round off ",0,'C');

            $this->SetXY(195,154);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            $this->Cell(20,2, "Net Total ",0,'C');

           $net=$gstamt12+$this->sub_total;
            $this->SetXY(278,154);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            $this->Cell(20,2, number_format($net,2),0,'C');

             $this->SetXY(13,168);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            $this->Cell(20,2, "Tax Amount (in words) :",0,'C');
            
            $this->SetXY(53,168);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            $this->MultiCell(100,2, convert_number_to_words($gstamt12),0,1,'C');
            
            $this->SetXY(13,174);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            $this->Cell(20,2, "Amount (in words) :",0,'C');
            
            $this->SetXY(53,174);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            $this->MultiCell(100,2, convert_number_to_words($net),0,1,'C');
            
            $this->SetXY(13,188);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            $this->Cell(20,2, "Company’s Service Tax No. :",0,'C');
    
            $this->SetXY(53,188);
            $this->SetFont('','','9');
            $this->SetTextColor('0','0','0');
            $this->MultiCell(100,2, "AAACD1708PSD001",0,'L');

            $this->SetXY(13,192);
            $this->SetFont('','B','9');
            $this->SetTextColor('0','0','0');
            $this->Cell(20,2, "Company’s PAN  :",0,'C');
         
            $this->SetXY(53,192);
            $this->SetFont('','','9');
            $this->SetTextColor('0','0','0');
            $this->MultiCell(100,2, "AAACD1708P",0,'L');

            $this->SetXY(13,197);
            $this->SetFont('','U','8');
            $this->SetTextColor('0','0','0');
            $this->MultiCell(100,2, "Declaration",0,'L');

            $this->SetXY(13,200);
            $this->SetFont('','','9');
            $this->SetTextColor('0','0','0');
            $this->MultiCell(150,2, "We declare that this invoice shows the actual price of the goods described and that all",0,'L');

            $this->Line(210,190,296,190);
            $this->Line(210,210,210,190);
            $this->SetXY(135,191);
            $this->SetFont('','','10');
            $this->SetTextColor('0','0','0');
            $this->Cell(160,0,"for Dr. JRK'S Research & Pharmaceutical Pvt Ltd." ,0,1,'R');
            
            $this->SetXY(256,205);
            $this->SetFont('','','10');
            $this->SetTextColor('0','0','0');
            $this->Cell(0,0, "Authorised Signatory" ,0,1,'L');
            
           
        }    
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setLogo($company_name,$company_address,$company_city,$cin_no,$cmp_gst_no,$state_name,$state_code,$website_address,$customer_name,$ship_to_address_1,$contact_number,$ship_gst_no,$state_name_ship,$rma_ref_no,$return_date,$remarks,$invoice_no,$invoice_date,$despatch_through,$payment_term_name,$gsttotal,$gst,$so_order,$so_date,$dispatch_no,$dispatch_date,$customer_po,$line_total,$tot_tax_amt,$sub_total,$linedata);

$totalpage = $pdf->getNumPages();
$pdf->fopage($totalpage);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+45, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', '', 12);

// add a page
$pdf->AddPage('L', $resolution);


$i=0;
$oldY=0;
$dis_amt=0;
$disamt=0;
$gst_tot=0;
$discount=0;
$tax_amt=0;
$ab=0;

//$details_var=$value;
$count=1; 
// $row_count = 61;
//$value=$linedata[0];
//for($j=0;$j<10;$j++)
	

 foreach($linedata as $key=>$value) 
{ 
    $i++;
     $discount+=$value['discount_amount'];
    $tax_amt+=$value['tax_amount'];
	    $ab=$value['amount']+$ab;



  $pdf->MultiRow($value,$i,$discount,$tax_amt,$pdf); 


}

  



$total = $pdf->getNumPages();     
if($pdf->pageNo()==$total)
{
    $y = $pdf->getY();
 
    if($y > 150)
        $pdf->AddPage('L', $resolution);

    $totalpage = $pdf->getNumPages();
    $pdf->fopage($totalpage);

 //GST Calculation
   
     // dd($totals);  
 

}

//$pdf->setAutoPageBreak(true, 300);
    
// reset pointer to the last page
$pdf->lastPage();
ob_end_clean();
    
if($print=='PRINT')
{          
    $pdf->Output('Credit Note.pdf','FI'); 
}
$pdf->close(); 
exit;


/******************************************************************************************/ 
?>
