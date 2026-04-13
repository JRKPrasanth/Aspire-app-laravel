<?php
// dd("dfgfd");
$resolution= array(215, 307);

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
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' And ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    //return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise ;
   

    return ($Rupees ? $Rupees . 'Rupees '  : '');
}

class MYPDF extends TCPDF { 
    
public $style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));

//Page header

    public function setLogo($company_logo_name,$company_name,$company_address,$Phone,$gst_no,$cin_no,$pan_no,$Web,$e_mail,$bcustomer,$bgst_no,$supplier_address,$bcontact_number,$replacement_no,$replacement_date,$po_number,$po_date,$invoice_date,$bill_number,$grn_number,$dc_date)
    {
         $this->logo = $company_logo_name;
        $this->companyname = $company_name;
        $this->company_addr = $company_address;
        $this->gst_number = $gst_no;
        $this->cin_no = $cin_no;
        $this->pan_no = $pan_no;
        $this->email_id = $e_mail;
        $this->Web = $Web;
        $this->contact_no =$Phone;

        $this->bcustomer = $bcustomer;
        $this->bgst_no = $bgst_no;
        $this->supplier_address = $supplier_address;
        
        $this->bcontact_number =$bcontact_number;


        $this->po_date = $po_date;
        $this->po_number = $po_number;
       
  

        $this->replacement_no =  $replacement_no;
        $this->replacement_date =  $replacement_date;
        $this->bill_number =  $bill_number;
        $this->grn_number =  $grn_number;
        $this->invoice_date = $invoice_date;
        $this->dc_date =  $dc_date;
        
    }
    public function fopage($totalpage){
        $this->totalpage = $totalpage;
    }
    
    public function Header() {
        // Logo    

        $image_file = public_path().'/images/'.$this->logo;
       $this->Image($image_file, 13, 8, 23, 13, 'JPG', '', 'T', false, 250, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 14);
        // Title


        $this->Line(12, 7, 12, 290, $this->style);
        $this->Line(12, 7, 208, 7, $this->style);
        $this->Line(208, 7, 208, 290, $this->style);
        $this->Line(12, 290, 208, 290, $this->style);
       

        $this->SetXY(20,11);
        $this->SetFont('times','B','20');
        $this->SetTextColor('0','B','0');
        $this->Cell(0,0, "Purchase Return Invoice",0,1,'C');

         $this->Line(12, 25, 208, 25, $this->style);


        $this->SetXY(13,26);
        $this->SetFont('','B','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0, "INVOICE TO" ,0,1,'L');


        $this->SetXY(13,30);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
        $this->Multicell(100,2,$this->companyname,0,'L');

        $this->SetXY(13,35);
        $this->SetFont('','','9');
        $this->SetTextColor('0','0','0');
        $this->Multicell(100,2,$this->company_addr,0,'L');

        $oldcmp = $this->getY();

        $this->SetXY(13,$oldcmp);
        $this->SetFont('','','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0, "CIN               : ".$this->cin_no ,0,1,'L');

        $this->SetXY(13,$oldcmp+5);
        $this->SetFont('','','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0, "GSTIN/UIN  : ".$this->gst_number ,0,1,'L');


        $this->SetXY(13,$oldcmp+10);
        $this->SetFont('','','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0, "PAN NO       : " .$this->pan_no,0,1,'L');

        $this->SetXY(13,$oldcmp+14);
        $this->SetFont('','','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(50,0, "Website         : ".$this->Web,0,1,'L');

        $this->SetXY(65,$oldcmp+14);
        $this->SetFont('','','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(50,0, "Mail Id : ".$this->Web,0,1,'L');

        $this->Line(12, 65, 208, 65);

        $this->SetXY(13,65);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0, "Supplier :" ,0,1,'L');

        $this->SetXY(13,70);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
         $this->Multicell(100,2,$this->bcustomer,0,'L');

        $this->SetXY(13,75);
        $this->SetFont('','','9');
        $this->SetTextColor('0','0','0');
        $this->Multicell(100,2,$this->supplier_address,0,'L');

        $oldsup = $this->getY();

        $this->SetXY(75,$oldsup+1);
        $this->SetFont('','','9');
        $this->SetTextColor('0','0','0');
        $this->Multicell(100,2,"PH No  : ".$this->bcontact_number,0,'L');

        $this->SetXY(13,$oldsup+1);
        $this->SetFont('','B','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0, "GSTIN/UIN  : ".$this->bgst_no ,0,1,'L');

        $this->Line(12, 95, 208, 95);

        $this->Line(115, 25, 115, 95);
        $this->Line(115, 25, 115, 95);

        $this->Line(165, 25, 165, 65);
        $this->Line(165, 25, 165, 65);
        $this->SetXY(117,25);
        $this->SetFont('','B','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0, "Return Invoice No" ,0,1,'L');

        $this->SetXY(120,29);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0,$this->replacement_no ,0,1,'L');

        $this->SetXY(167,25);
        $this->SetFont('','B','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0, "Return Invoice Date" ,0,1,'L');

        $this->SetXY(169,29);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0, $this->replacement_date ,0,1,'L');

        $this->Line(115, 35,208, 35);
        $this->SetXY(117,35);
        $this->SetFont('','B','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0, "PO No" ,0,1,'L');

        $this->SetXY(119,39);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0, $this->po_number,0,1,'L');

        $this->SetXY(167,35);
        $this->SetFont('','B','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0, "PO Date" ,0,1,'L');

        $this->SetXY(169,39);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0,$this->po_date,0,1,'L');

        $this->Line(115, 45,208, 45);
        $this->SetXY(117,45);
        $this->SetFont('','B','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0, "Invoice Number" ,0,1,'L');

        $this->SetXY(119,49);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0,$this->bill_number,0,1,'L');

        $this->SetXY(167,45);
        $this->SetFont('','B','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0, "Invoice Date" ,0,1,'L');

        $this->SetXY(169,49);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0,$this->invoice_date,0,1,'L');

        $this->Line(115, 55,208, 55);
        $this->SetXY(117,55);
        $this->SetFont('','B','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0, "GRN NO" ,0,1,'L');

        $this->SetXY(119,59);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0,$this->grn_number,0,1,'L');

        $this->SetXY(167,55);
        $this->SetFont('','B','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0, "GRN Date" ,0,1,'L');
        
        $this->SetXY(169,59);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
        $this->Cell(0,0,$this->dc_date,0,1,'L');
       

        $this->Line(25, 95, 25, 290);
        $this->SetXY(12,98);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
        $this->Cell(13,0, "S.NO " ,0,1,'C');
         
        $this->Line(90, 95, 90 , 290);        
        $this->SetXY(28,98);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
        $this->Cell(60,0, "Description of Goods" ,0,1,'C');

        $this->Line(115, 95, 115 , 290);
        $this->SetXY(90,98);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
        $this->Cell(25,0, "HSN/SAC" ,0,1,'C');

        $this->Line(130, 95, 130, 290);
        $this->SetXY(118,95);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
        $this->MultiCell(10,0, "GST Rate" ,0,'C');
        

        $this->Line(145, 95, 145 , 290);
        $this->SetXY(130,98);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
        $this->Cell(15,0, "Quantity" ,0,1,'C');

         $this->Line(160,95, 160 , 290);
        $this->SetXY(145,98);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
        $this->Cell(15,0, "Rate" ,0,1,'C');                
         
        $this->Line(175,95, 175 , 290);        
        $this->SetXY(160,98);
        $this->SetFont('','B','11');
        $this->SetTextColor('0','0','0');
        $this->Cell(15,0,"UOM",0,1,'C');

        $this->Line(186,95, 186 , 290);        
        $this->SetXY(173.5,98);
        $this->SetFont('','B','10');
        $this->SetTextColor('0','0','0');
        $this->Cell(15,0,"Disc.%",0,1,'C');

        $this->SetXY(190,98);
        $this->SetFont('','B','11');
        $this->SetTextColor('0','0','0');
        $this->Cell(20,0,"Amount",0,1,'C');
        $this->Line(12,105, 208 , 105);  

        
    }
//Page Body
    public function MultiRow($value,$i,$discount,$tax_amt) { 
        $y = $this->getY();
        if($i==1){
                    $y = $y+18;
        }
        else{

            $y = $this->getY();
             $y = $y+8;
        }
        $this->SetXY(13,$y);
        $this->SetFont('','','9');
        $this->SetTextColor('0','0','0');
        $this->Cell(11,0, $i ,0,1,'C');

        $this->SetXY(28,$y);
        $this->SetFont('','','11');
        $this->SetTextColor('0','0','0');
        $this->MultiCell(65,2,$value->product_id ,0,'L');

         $this->SetXY(90,$y);
        $this->SetFont('','','11');
        $this->SetTextColor('0','0','0');
        $this->Cell(25,0,$value->classification_code ,0,1,'C');
        
        // if($value->display_name!=0)
        // {  
        $this->SetXY(115,$y);
        $this->SetFont('','','10');
        $this->SetTextColor('0','0','0');
        $this->MultiCell(15,2,$value->display_name ,0,'C');
       // }
        $this->SetXY(130,$y);
        $this->SetFont('','','11');
        $this->SetTextColor('0','0','0');
        $this->Cell(15,0, $value->qty,0,1,'C');
         
        $this->SetXY(145,$y);
        $this->SetFont('','','11');
        $this->SetTextColor('0','0','0');
        $this->Cell(15,0, number_format($value->unit_price,'3','.','') ,0,1,'R');

        $this->SetXY(160,$y);
        $this->SetFont('','','11');
        $this->SetTextColor('0','0','0');
        $this->Cell(15,0, $value->uom_code_id,0,1,'C');

        if($value->discount_percentage!=0)
        {
        $this->SetXY(170,$y);
        $this->SetFont('','','11');
        $this->SetTextColor('0','0','0');
        $this->Cell(15,0, $value->discount_percentage,1,1,'C');
    }
        $linetotal =$value->unit_price * $value->qty;

        $this->SetXY(187,$y);
        $this->SetFont('','B','9');
        $this->SetTextColor('0','0','0');
        $this->multiCell(20,0, number_format($linetotal,'2','.','') ,0,'R');
        
        $oldy=$this->getY();
 
    }
   
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom

              
        if($this->pageNo()==$this->totalpage)
        {            
            $this->SetXY(110,270);
            $this->SetFont('','','9');
            $this->SetTextColor('0','0','0');
            $this->MultiCell(100,2, "for $this->companyname",0,'C');

            $this->SetXY(100,285);
            $this->SetFont('','','9');
            $this->SetTextColor('0','0','0');
            $this->MultiCell(80,2, "Authorised Signatory",0,'R');

            $this->Line(110, 268, 110, 293, $this->style);
            $this->Line(110, 268, 208, 268, $this->style);
            
        }    
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setLogo($company_logo_name,$company_name,$company_address,$Phone,$gst_no,$cin_no,$pan_no,$Web,$e_mail,$bcustomer,$bgst_no,$supplier_address,$bcontact_number,$replacement_no,$replacement_date,$po_number,$po_date,$invoice_date,$bill_number,$grn_number,$dc_date);

$totalpage = $pdf->getNumPages();
$pdf->fopage($totalpage);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+63, PDF_MARGIN_RIGHT);
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
$pdf->AddPage('P', $resolution);



$style = array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0 , 'color' => array(2, 0, 0));


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
    //dd($linedata);
 foreach($linedata as $key=>$value) 
{ 
  // dd($value);
    $i++;
   $discount+=$value->discount_amount;
    $tax_amt+=$value->tax_amount;
     $ab=$value->amount+$ab;
        ;

 $pdf->MultiRow($value,$i,$discount,$tax_amt,$pdf); 

}
  //dd($tax_amt);

  $totals=$ab;

$total = $pdf->getNumPages();     
if($pdf->pageNo()==$total)
{
    $y = $pdf->getY();
   
    if($y > 280)
        $pdf->AddPage('P', $resolution);

    $totalpage = $pdf->getNumPages();
    $pdf->fopage($totalpage);
    
    $pdf->SetXY(70,215);
    $pdf->SetFont('','B','11');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(0,0, "Total " ,0,1,'L');

    $pdf->SetXY(103,215);
    $pdf->SetFont('','B','11');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(104,0,number_format($totals,2) ,0,1,'R');

    $pdf->Line(12, 214, 208, 214, $style);
    //$pdf->Line(12, 222, 208, 222, $style);      
                

    $pdf->Line(12, 250, 208, 250, $style);
    $pdf->Line(12, 250, 208, 250, $style);
    $pdf->Rect(12, 224, 196, 70,'DF', "",  array(255, 255, 255));

 //GST Calculation
    $discountamt=0;
    $gross_total=0;
    $hsn_y=0;
    $hsny=232;
    $gst_total=0;
    $sgstval=0;
    $cgstval=0;

  foreach($gst1 as $k1=>$gstvalue)
{
// dd($gstvalue);
  if ($gstvalue['gst']!=0){
   
        if($gstvalue['gsttype'] !="IGST") 
        { 
           // $gstval=implode('',$gstvalue['gst']);
            $pdf->SetXY(180,$hsny-3);
            $pdf->SetFont('','B','9');
            $pdf->SetTextColor('0','0','0');
            $pdf->Cell(0,0,$gstvalue['sgst_val']." %" ,0,1,'L');  
           
            $pdf->SetXY(160,$hsny-3);
            $pdf->SetFont('','','9');
            $pdf->SetTextColor('0','0','0');
            $pdf->Cell(0,0, 'Input SGST ' ,0,1,'L');  


            $pdf->SetXY(180,$hsny+2);
            $pdf->SetFont('','B','9');
            $pdf->SetTextColor('0','0','0');
            $pdf->Cell(0,0,$gstvalue['cgst_val']." %" ,0,1,'L');
                
                    
            $pdf->SetXY(160,$hsny+2);
            $pdf->SetFont('','','9');
            $pdf->SetTextColor('0','0','0');
            $pdf->Cell(0,0, 'Input CGST ' ,0,1,'L');  


  // $gstvalue1=$gstvalue['gst_val']/2;

             // $sgstval+=$tax_amt/2;
             // $cgstval+=$tax_amt/2;

            $pdf->SetXY(177,$hsny-3);
            $pdf->SetFont('','','9');
            $pdf->SetTextColor('0','0','0');
            $pdf->Cell(30,0,$gst_val1,0,1,'R');

            $pdf->SetXY(177,$hsny+2);
            $pdf->SetFont('','','9');
            $pdf->SetTextColor('0','0','0');
           $pdf->Cell(30,0,$gst_val1,0,1,'R');
        }
        else
         {

           // $gstval=implode('',$gstvalue['gst']);
            $pdf->SetXY(180,$hsny-3);
            $pdf->SetFont('','B','9');
            $pdf->SetTextColor('0','0','0');
            $pdf->Cell(0,0, $gstvalue['gst']." %" ,0,1,'L');
                
    
            $pdf->SetXY(160,$hsny-3);
            $pdf->SetFont('','','9');
            $pdf->SetTextColor('0','0','0');
            $pdf->Cell(0,0, "Input IGST" ,0,1,'L');
                
            $pdf->SetXY(177,$hsny-3);
            $pdf->SetFont('','','9');
            $pdf->SetTextColor('0','0','0');
            $pdf->MultiCell(30,0,number_format($gsttotal,2) ,0,'R');

        } 

        }  
    }
      $hsny=$hsny+5;
      $totals=$tax_amt+$ab;

      $whole = floor($grand_total);
//dd($grand_total);
$roundoff = $grand_total - $whole;

if($roundoff!=0.00){

$pdf->SetXY(160,$hsny+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0, "Round Off" ,0,1,'L');
    
$pdf->SetXY(177,$hsny+4);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(30,0, "-".number_format($roundoff,2),0,'R');

}

$grandtotal=$grand_total-$roundoff;  

$pdf->SetXY(160,$hsny+9);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(0,0, "Grand Total" ,0,1,'L');

$pdf->Line(190, $hsny+8, 208, $hsny+8, $style);
$pdf->Line(190, $hsny+14, 208, $hsny+14, $style);

$pdf->SetXY(177,$hsny+9);
$pdf->SetFont('','','9');
$pdf->SetTextColor('0','0','0');
$pdf->MultiCell(30,0, number_format($grandtotal,2),0,'R');

 //  }    
    $pdf->SetXY(18,225);
    $pdf->SetFont('','B','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(104,0,'Amount Chargeable (in words) :',0,1,'L');

    $pdf->SetXY(35,230);
    $pdf->SetFont('','','10');
    $pdf->SetTextColor('0','0','0');
    $pdf->Cell(140,0,convert_number_to_words($grandtotal).'Only',0,1,'L');
}

// }
// }

$pdf->setAutoPageBreak(true, 300);
    
//reset pointer to the last page
$pdf->lastPage();
ob_end_clean();
  
   
if($print=='PRINT')
{               //}              
    $pdf->Output('name.pdf','FI'); 
}
else
{
    $filename="Uploads/purchaseorder/PO_".$po_number.".pdf";
    $pdf->Output('Uploads/purchaseorder/PO_'.$po_number.'.pdf', 'F');

}
$pdf->close(); 
exit;


/******************************************************************************************/ 
?>
