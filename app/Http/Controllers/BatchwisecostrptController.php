<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;

class BatchwisecostrptController extends Controller
{
      public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
    }
	
  public function index()
    {
      return view('batchwisecostrpt.batchwisecost',$this->data);    
    }

	
	
public function getbatchwisecost(Request $request) {


            $wh='';
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


           $SQL = "select * from(select v2.*,(SELECT
              m_product_groups_t.group_name
          FROM
              m_product_groups_t
          WHERE
              m_product_groups_t.product_group_id = m_products_t.product_group_id
      ) AS group_name,
      (
      SELECT
          m_product_category_t.category_name
      FROM
          m_product_category_t
      WHERE
          m_product_category_t.product_category_id = m_products_t.product_category_id
  ) AS category_name,
  (
      SELECT
          m_product_subcategory_t.subcategory_name
      FROM
          m_product_subcategory_t
      WHERE
          m_product_subcategory_t.product_subcategory_id = m_products_t.product_subcategory_id
  ) AS subcat_name,
  (
    SELECT
        m_sublocators_t.locator_code
    FROM
        m_sublocators_t
    WHERE
        m_sublocators_t.sublocator_id = m_products_t.sublocator_id
) AS sublocat_name,m_products_t.product_code,m_products_t.concatenated_product as product_name,round(sum(v2.qty*v2.rate),2)as cost1 from (select *,(select i_qoh_detail_t.cost from i_qoh_detail_t where product_id=v1.product_id and batch_number=v1.batch_number and cost>0 order by qoh_detail_id desc limit 1)as rate from(SELECT round(sum(qoh_trx_qty),2)qty,batch_number,product_id FROM `i_qoh_detail_t` where date(created_at) BETWEEN ? and ?  group by batch_number,product_id)v1 where qty>0)v2 join m_products_t on m_products_t.product_id=v2.product_id GROUP by v2.product_id,batch_number)v1";
 
    $results = \DB::select($SQL, [$start_date, $end_date]);

    return response()->json(['data' => $results]);
		
    
    }
	
  public function wipcostindex()
    {
        $this->data['jobno']=$this->jcombologin("w_jobcard_hdr_t","w_jobs_hdr_id","job_no","");
        $this->data['pageMethod']=\Request::route()->getName();
       if($this->data['pageMethod']=="productionbeforecostrpt"){
        $this->data['product']=$this->jcustomselect("m_products_t","product_id","product_code|concatenated_product","","and product_group_id in('1','4')");
       }

      return view('batchwisecostrpt.wipcostrpt',$this->data);    
    }
    public function jobbasedwipcostdetails($jobno=null){
    $jobdetails=\DB::select('select * from r_job_cost_hdr_tbl where job_id='.$jobno);
    $prddetails="<div align='center'>There is No Data</div>";
    if(count($jobdetails)>0){
    $prddetails=$this->jobbomdetails($jobdetails[0]->r_job_cost_hdr_id);
    }
    return $prddetails;
}
  /*deepika purpose:to display bom details from material bom based on fg,sfg to create job card*/
  public function jobbomdetails($id=null,$product_id=null,$jbqty=null,$jid=null)
  {
    
  $jbhdr=\DB::select("select * from r_job_cost_hdr_tbl where r_job_cost_hdr_id=".$id);
    $sql=\DB::table('r_job_cost_lines_tbl')->where('r_job_cost_hdr_id',$id)->get();
    
$html='<table class="collaptable table table-striped" style="width:100%;">
  <tr style="background-color:#455986;">
  
    <th >Line No</th>
    <th>Product</th>
    <th>Uom</th>
     <th>Batch Number</th>
    <th>Qty</th>
    <th>Rate</th>
    <th>Total</th>';
 $excelHeader = "Line No"."\t"."Product"."\t"."Uom"."\t"."Batch Number"."\t"."Qty"."\t"."Rate"."\t"."Total";
  $processs=array();
  $rowData='';
            $setData='';
            $productdata='';
    $i=0;
    if(count($sql)>0){
    foreach($sql as $key=>$val)
    {
    
      $prds=\DB::select('select * from m_products_t where product_id='.$val->product_id);
      if(count($prds)>0){
      $uom=$this->uomname($prds[0]->primary_uom_id);
        }else{
          $uom='';  
        } $process="";
        $mprds=\DB::select('select * from m_products_t where product_id='.$jbhdr[0]->product_id);
        if(count($prds)>0){
      $uomname1=$this->uomname($mprds[0]->primary_uom_id);
        }else{
          $uomname1='';  
        }
          if(!array_key_exists($jbhdr[0]->product_id,$processs)){
    
      
        $html.='<tr class="table'.$key.'" data-id="'.$jbhdr[0]->product_id.$process.'" data-parent=""> <td></td>
        
        ';
     
        

        
          
          $html.='<td class="pdtdiv"><input type="text"  title="'.$jbhdr[0]->product_name.'" class="input-sm bulk_product_id" value="'.$jbhdr[0]->product_name.'" readonly="readonly" style="color:black;width:280px;">
          </td>
            <td>
          
          <input type="text" name="bulk_uom_code_id[]" class="input-sm uom" value="'.$uomname1.'" readonly="readonly" style="color:black;width:100px;">
          </td><td>
          <input type="text" name="bulk_batch_number[]" class="input-sm bulk_batch_number input_qty_width" value="'.$jbhdr[0]->batch_number.'"  readonly style="color:black;width:150px;">
          </td>
          <td>
          <input type="text" name="bulk_qty[]" class="input-sm bulk_qty input_qty_width" value="'.$jbhdr[0]->qty.'"  readonly style="color:black;width:150px;">
          </td>';
    
        $html.='<td>
        <input type="text"  class="input-sm  rate input_qty_width" value="'.$jbhdr[0]->rate.'"  readonly style="color:black;width:180px;">
          </td> 
          
           <td>
        <input type="text"  class="form-control input-sm total input_qty_width total'.$key.'" value="'.$jbhdr[0]->total.'"  readonly style="color:black;width:200px;">
    </td>
          </tr>';
      
        $processs[$jbhdr[0]->product_id]="0";
         $productdata= '"' .'P'.'"'."\t". $jbhdr[0]->product_name."\t".$uomname1."\t".$jbhdr[0]->batch_number."\t".$jbhdr[0]->qty."\t".$jbhdr[0]->rate."\t"
                            .$jbhdr[0]->total."\t";
                          
                       $rowData .= $productdata."\n";
    $i++; 
      }     
    
  $html.='<tr class="table'.$key.'"   data-id="'.$val->product_id.'" data-parent="'.$jbhdr[0]->product_id.'">';
  $qty='';
    if($val->product_group=="4"){
          $jbhdr1=\DB::select("select * from r_job_cost_hdr_tbl where product_id=".$val->product_id." and batch_number='".$val->batch_number."'");
if(count($jbhdr1)>0){
$qty=$jbhdr1[0]->qty;
}
    }
  
        $html.='<td><input type="text"  class="input-sm line_no" value="'.($key+1).'" readonly="readonly" style="color:black;width:90px;">
          </td>';
        
          
          $html.='<td class="pdtdiv"><input type="text"  title="'.$val->product_name.'" class="input-sm product" value="'.$val->product_name.'" readonly="readonly" style="color:black;width:280px;">
          </td>
          <td>
          <input type="text"  class="input-sm uom" value="'.$uom.'" readonly="readonly" style="color:black;width:100px;">
          </td><td>
          <input type="text" name="bulk_batch_number[]" class="input-sm bulk_batch_number input_qty_width" value="'.$val->batch_number.'"  readonly style="color:black;width:150px;">
          </td>
            <td>  <input type="text" name="bulk_qty[]" class="input-sm bulk_qty input_qty_width" value="'.$qty.'"  readonly style="color:black;width:150px;">
          
          </td>';
          $html.='<td>
        <input type="text"  class="input-sm actualrate input_qty_width" value="'.$val->rate.'"  readonly style="color:black;width:180px;">
        
          </td><td>
        <input type="text"  class="input-sm total input_qty_width" value="'.$val->total.'"  readonly style="color:black;width:200px;">
        
          </td>
          
          </tr>';
          $productdata= '"'.'Cp-' .($key+1).'"'."\t". $val->product_name."\t".$uom."\t".$val->batch_number."\t".$qty."\t".$val->rate."\t"
                            .$val->total."\t";
                          
                       $rowData .= $productdata."\n";
        if($val->product_group=="4"){
        
      $html1=$this->jobbomsubdetails($val->r_job_cost_hdr_id,$val->product_id,$val->batch_number);
      $html.=$html1['html'];  
                         $rowData .= $html1['row']."\n";
          }
          
    }
    }else{
$html.= '<tr class="sub  inter_sub0" data-id="0"  data-parent=""><td></td><td></td><td></td><td><input type="hidden" class="nobom"/>No Data for these Job</td></tr>';  
     }
  $html.='</table>';
if(strpos($html,'<pre>') !== false)
        {
            $replace = array(
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/<\?php/"                  => '<?php ',
                "/\r/"                      => '',
                "/>\n</"                    => '><',
                "/>\s+\n</"                 => '><',
                "/>\n\s+</"                 => '><',
            );
        }
        else
        {
            $replace = array(
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/\n([\S])/"                => '$1',
                "/\r/"                      => '',
                "/\n/"                      => '',
                "/\t/"                      => '',
                "/ +/"                      => ' ',
        
            );
        }
         $setData .= trim($rowData)."\n";
         $my_file = uniqid().'.xls';
            $file = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
           header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=".uniqid()." Production Cost Report.xls");
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=Production Cost Report.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    
        $buffer = preg_replace(array_keys($replace), array_values($replace), $html);
       $columnHeader =  $excelHeader."\n".$setData."\n";
    $file = fwrite($file,$columnHeader);
    
     $buffer.=    "<tr>"
                    . "<td colspan='12' align='center'><a href=".$my_file." class='delimeters ' download><button class='btn btn-success fa fa-download' type='button'>DOWNLOAD</button></a></td>"
                    . "</tr>";
   
        ini_set('zlib.output_compression', 'On'); // If you like to enable GZip, too!
      return $buffer;
}
  /*end*/ 
  /*deepika purpose:to display bom details from plan lines based on sfg*/
public function jobbomsubdetails($id=null,$pid=null,$bno=null){
    $html='';
    $subproductdata="";
    $subrowData="";
  $jbhdr1=\DB::select("select * from r_job_cost_hdr_tbl where product_id=".$pid." and batch_number='".$bno."'");
  if(count($jbhdr1)>0){
      $sql1=\DB::table('r_job_cost_lines_tbl')->where('r_job_cost_hdr_id',$jbhdr1[0]->r_job_cost_hdr_id)->get();
  $html='';
    $decimal=\Session::get("decimal");
              if(count($sql1)>0){          
    foreach($sql1 as $key1=>$val1)
    {
      
    $qty='';
    if($val1->product_group=="4"){
          $jbhdr11=\DB::select("select * from r_job_cost_hdr_tbl where product_id=".$val1->product_id." and batch_number='".$val1->batch_number."'");
if(count($jbhdr11)>0){
$qty=$jbhdr11[0]->qty;
}
    }
    
        $prds=\DB::select('select * from m_products_t where product_id='.$val1->product_id);
        if(count($prds)>0){
      $uom=$this->uomname($prds[0]->primary_uom_id);
        }else{
          $uom='';  
        }
        $html.='
      
      <tr class="sub'.$pid.'  inter_sub'.$val1->product_id.'" data-id="'.$val1->product_id.'" data-parent="'.$pid.'">';
          $html.='<td><input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="'.($key1+1).'" readonly="readonly" style="width: 90px !important;">
          </td>';
      
          
      
      $html.='<td class="pdtdiv">
          <input type="text"  title="'.$val1->product_name.'" class="input-sm product" value="'.$val1->product_name.'" readonly="readonly" style="color:black;width:280px;">
          </td>
          <td>
          <input type="text"  title="'.$uom.'" class="input-sm uom_code" value="'.$uom.'" readonly="readonly" style="color:black;width:80px;">
          </td><td>
          <input type="text" name="bulk_batch_number[]" class="input-sm bulk_batch_number input_qty_width" value="'.$val1->batch_number.'"  readonly style="color:black;width:150px;">
          </td><td><input type="text" name="bulk_qty[]" class="input-sm bulk_qty input_qty_width" value="'.$qty.'"  readonly style="color:black;width:150px;" >
          </td>
        <td>
          <input type="text"  class="input-sm bomrate input_qty_width" value="'.$val1->rate.'" style="color:black;width:180px;" readonly>
          </td>
          </td><td><input type="text"  class="input-sm total input_qty_width" value="'.$val1->total.'" style="color:black;width:200px;" readonly></td>';
        
      
        $html.='</tr>';
      $subproductdata= '"'.'c-'.($key1+1).'"'."\t". $val1->product_name."\t".$uom."\t".$val1->batch_number."\t".$qty."\t".$val1->rate."\t"
                            .$val1->total."\t";
                          
                       $subrowData .= $subproductdata."\n";
                          if($val1->product_group =="4"){
            
                    $html1=$this->jobbomsubdetails($id,$val1->product_id,$val1->batch_number);
                     $html.=$html1['html'];  
                         $subrowData .= $html1['row']."\n";
     
          }
    
    }
     }else{
$html.= '<tr class="sub'.$pid.'  inter_sub0" data-id="0"  data-parent="'.$pid.'"><td></td><td></td><td></td><td><input type="hidden" class="nobom"/>No Bom for these product</td></tr>';  
     }
  }else{
$html.= '<tr class="sub'.$pid.'  inter_sub0" data-id="0"  data-parent="'.$pid.'"><td></td><td></td><td></td><td><input type="hidden" class="nobom"/>No Bom for these product</td></tr>';  
     }
     $data['html']=$html;
     $data['row']=$subrowData;
  return $data;   
    
  }
/*deepika purpose:to display bom details from material bom based on fg,sfg*/
public function prdbasedwipcostdetails($productid=null)
  {
    $sql=\DB::table('m_material_bom_hdr_t as bomh')
    ->leftjoin('m_material_bom_lines_t as boml','bomh.material_bom_hdr_id','=','boml.material_bom_hdr_id')
    ->leftjoin('m_products_t as prod','prod.product_id','=','boml.component_product_id')
    ->leftjoin('m_uom_codes_t as uom','uom.uom_code_id','=','boml.component_uom_code_id')        
    ->select('bomh.material_bom_hdr_id','bomh.assembly_product_id','bomh.uom_code_id','boml.component_product_id','boml.component_uom_code_id','boml.component_qty','prod.product_group_id','boml.component_uom_code_id','boml.process_level','boml.process_name','.boml.component_qty','boml.machine_name','uom.uom_code','prod.concatenated_product as product_name')
    ->where('bomh.assembly_product_id',$productid)
    ->get();  
    //dd($sql);  
    $html11='<table class="collaptable table table-striped" style="width:100%;">
    <tr style="background-color:#455986;">
      <th >Line No</th>
      <th>Product</th>
      <th>Uom</th>
      <th>Qty</th>
      <th>Rate</th>
      <th>Total</th>';
  
    $excelHeader = "Line No"."\t"."Product"."\t"."Uom"."\t"."Qty"."\t"."Rate"."\t"."Total";
    $processs=array();
    $rowData='';
    $setData='';
    $productdata='';
    $hdrtbl=\DB::table('m_material_bom_hdr_t as bomh')
    ->leftjoin('m_products_t as prod','prod.product_id','=','bomh.assembly_product_id')
    ->leftjoin('m_uom_codes_t as uom','uom.uom_code_id','=','bomh.uom_code_id')        
    ->select('bomh.material_bom_hdr_id','bomh.assembly_product_id','bomh.uom_code_id','bomh.total_component_qty','prod.concatenated_product as product_name','uom.uom_code')
    ->where('bomh.assembly_product_id',$productid)
    ->get();    
      $i=0;
      $lk=0;
      $numItems=count($sql);
      $total=0;
      $html11.='<tr class="table" data-id="'.$productid.'" data-parent=""><td> <input type="text"  title="" class="input-sm bulk_line_no" value="1" readonly="readonly" style="color:black;width:280px;"></td><td class="pdtdiv"><input type="text"  title="'.$hdrtbl[0]->product_name.'" class="input-sm bulk_product_id" value="'.$hdrtbl[0]->product_name.'" readonly="readonly" style="color:black;width:280px;">
      </td>
        <td>    
      <input type="text" name="bulk_uom_code_id[]" class="input-sm uom" value="'.$hdrtbl[0]->uom_code.'" readonly="readonly" style="color:black;width:100px;">
      </td>
      <td>
      <input type="text" name="bulk_qty[]" class="input-sm bulk_qty input_qty_width" value="1"  readonly style="color:black;width:150px;">
      </td>';
    $setData11="1"."\t".$hdrtbl[0]->product_name."\t".$hdrtbl[0]->uom_code."\t"."1"."\t";  
   $setDatal="";
    $html='';
      foreach($sql as $key=>$value){
        
        $html.='<tr class="table'.$key.'" data-id="'.$value->component_product_id.'" data-parent="'.$productid.'"><td> <input type="text"  title="" class="input-sm bulk_line_no" value="'.($key+1).'" readonly="readonly" style="color:black;width:280px;"></td><td class="pdtdiv"><input type="text"  title="'.$value->product_name.'" class="input-sm bulk_product_id" value="'.$value->product_name.'" readonly="readonly" style="color:black;width:280px;">
        </td>
          <td>    
        <input type="text" name="bulk_uom_code_id[]" class="input-sm uom" value="'.$value->uom_code.'" readonly="readonly" style="color:black;width:100px;">
        </td>
        <td>
        <input type="text" name="bulk_qty[]" class="input-sm bulk_qty input_qty_width" value="'.$value->component_qty.'"  readonly style="color:black;width:150px;">
        </td>';
        $setDatal.=($key+1)."\t".$value->product_name."\t".$value->uom_code."\t";  
      if($value->product_group_id=='1' || $value->product_group_id=='4'){
        $html1=$this->jobbomsubdetails2($value->component_product_id,1);
        $html.='<td>
        <input type="text" name="bulk_rate_qty[]" class="input-sm bulk_rate_qty input_qty_width" value="0"  readonly style="color:black;width:150px;">
        </td><td>
        <input type="text" name="bulk_tot_qty[]" class="input-sm bulk_tot_qty input_qty_width" value="'.$html1['total'].'"  readonly style="color:black;width:150px;">
        </td></tr>';
        $setDatal.="0"."\t".$html1['total']."\t".""."\n";
        $setDatal.=$html1['setDatal'];
        $html.=$html1['html']; 
        $total+=$html1['total'];
     
      }

      }// foreach end
      $html11.='<td>
      <input type="text" name="bulk_rate_qty[]" class="input-sm bulk_rate_qty input_qty_width" value="0"  readonly style="color:black;width:150px;">
      </td><td>
      <input type="text" name="bulk_tot_qty[]" class="input-sm bulk_tot_qty input_qty_width" value="'.$total.'"  readonly style="color:black;width:150px;">
      </td></tr>';
      $setData11.="0"."\t".$total."\t".""."\n";
      $html=$html11.$html;
      $rowData=$setData11.$setDatal; 
      $rowData= trim($rowData)."\n";
      $my_file = uniqid().'.xls';
         $file = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
        header("Content-type: application/vnd-ms-excel");
 header("Content-Disposition: attachment; filename=".uniqid()." Production Cost Report.xls");
 header("Content-type: application/octet-stream");
 header("Content-Disposition: attachment; filename=Production Cost Report.xls");
 header("Pragma: no-cache");
 header("Expires: 0");

     $columnHeader =  $excelHeader."\n".$rowData."\n";
 $file = fwrite($file,$columnHeader);
 
  $html.=    "<tr>"
                 . "<td colspan='12' align='center'><a href=".$my_file." class='delimeters ' download><button class='btn btn-success fa fa-download' type='button'>DOWNLOAD</button></a></td>"
                 . "</tr>";

     ini_set('zlib.output_compression', 'On'); // If you like to enable GZip, too!
      return $html;
    }
  public function jobbomsubdetails2($productid=null,$qty=null)
  {
//dd("sdf");
  $sql=\DB::table('m_material_bom_hdr_t as bomh')
  ->leftjoin('m_material_bom_lines_t as boml','bomh.material_bom_hdr_id','=','boml.material_bom_hdr_id')
  ->leftjoin('m_products_t as prod','prod.product_id','=','boml.component_product_id')        
  ->leftjoin('m_uom_codes_t as uom','uom.uom_code_id','=','bomh.uom_code_id')        
  ->select('bomh.material_bom_hdr_id','bomh.assembly_product_id','bomh.uom_code_id','boml.component_product_id','boml.component_uom_code_id','boml.component_qty','prod.product_group_id','boml.component_uom_code_id','boml.process_level','boml.process_name','boml.component_qty','boml.machine_name','prod.concatenated_product as productname1','uom.uom_code')
  ->where('bomh.assembly_product_id',$productid)
  ->get(); 
  $html='';
  $html11='';
$total=0;
$setDatal='';
  foreach($sql as $key=>$value){

    $html.='<tr class="table'.$key.'" data-id="'.$value->component_product_id.'" data-parent="'.$productid.'"><td> <input type="text"  title="" class="input-sm bulk_line_no" value="'.($key+1).'" readonly="readonly" style="color:black;width:280px;"></td><td class="pdtdiv"><input type="text"  title="'.$value->productname1.'" class="input-sm bulk_product_id" value="'.$value->productname1.'" readonly="readonly" style="color:black;width:280px;">
    </td>
      <td>    
    <input type="text" name="bulk_uom_code_id[]" class="input-sm uom" value="'.$value->uom_code.'" readonly="readonly" style="color:black;width:100px;">
    </td>
    <td>
    <input type="text" name="bulk_qty[]" class="input-sm bulk_qty input_qty_width" value="'.$value->component_qty.'"  readonly style="color:black;width:150px;">
    </td>';
    $setDatal.=($key+1)."\t".$value->productname1."\t".$value->uom_code."\t".$value->component_qty."\t";
      
    if($value->product_group_id!='1' && $value->product_group_id!='4'){
    $polin=\DB::select("select unit_price from p_po_invoice_lines_t where product_id='$value->component_product_id' order by po_invoice_lines_id desc limit 1");
    if(count($polin)>0){

      $total+=round($polin[0]->unit_price * $value->component_qty * $qty,3);
      $html.='<td>
      <input type="text" name="bulk_rate_qty[]" class="input-sm bulk_rate_qty input_qty_width" value="'.$polin[0]->unit_price.'"  readonly style="color:black;width:150px;">
      </td><td>
      <input type="text" name="bulk_tot_qty[]" class="input-sm bulk_tot_qty input_qty_width" value="'.round($polin[0]->unit_price * $value->component_qty * $qty,3).'"  readonly style="color:black;width:150px;">
      </td>';
      $html.='</tr>';
      $setDatal.=$polin[0]->unit_price."\t".round($polin[0]->unit_price * $value->component_qty * $qty,3)."\t".""."\n";
    }else{
      $total+=0;
      $html.='<td>
      <input type="text" name="bulk_rate_qty[]" class="input-sm bulk_rate_qty input_qty_width" value="0"  readonly style="color:black;width:150px;">
      </td><td>
      <input type="text" name="bulk_tot_qty[]" class="input-sm bulk_tot_qty input_qty_width" value="0"  readonly style="color:black;width:150px;">
      </td>';
      $html.='</tr>';
      $setDatal.="0"."\t"."0"."\t".""."\n";
    }
   
  }else{
    $html1=$this->jobbomsubdetails2($value->component_product_id,$value->component_qty);
    $html.='<td>
    <input type="text" name="bulk_rate_qty[]" class="input-sm bulk_rate_qty input_qty_width" value="0"  readonly style="color:black;width:150px;">
    </td><td>
    <input type="text" name="bulk_tot_qty[]" class="input-sm bulk_tot_qty input_qty_width" value="'.$html1['total'].'"  readonly style="color:black;width:150px;">
    </td></tr>';
    $setDatal.="0"."\t".$html1['total']."\t".""."\n";
    $html.=$html1['html'];  
    $total+=$html1['total'];
    
  }
    }// foreach end
  $retval=array();
  $retval['html']=$html;
  $retval['setDatal']=$setDatal;
  $retval['total']=$total;
  
  return $retval;
  }
  public function costrptindex()
    {
      return view('batchwisecostrpt.costreport',$this->data);    
    }
    
  public function getcostrptData(Request $request)
	{

		$start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
		$end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';



    $SQL = "SELECT * from (SELECT
            r_job_cost_hdr_tbl.r_job_cost_hdr_id,
            w_productionplan_hdr_t.plan_no,
            r_job_cost_hdr_tbl.job_number,
            r_job_cost_hdr_tbl.job_date,
            concat_ws('-',left(MonthName(r_job_cost_hdr_tbl.job_date),3),right(year(r_job_cost_hdr_tbl.job_date),2)) AS Month,
            CASE WHEN m_product_category_t.category_name ='COSMETICS' THEN 'PERSONAL CARE PRODUCTS' ELSE 'SIDDHA MEDICINE' END AS cat_grp,
            m_product_category_t.category_name,
            m_product_subcategory_t.subcategory_name,
            m_product_groups_t.group_name,
            m_product_variants_t.product_variant_name,  
            m_product_type_t.product_type,
            m_uom_codes_t.uom_code, 
            r_job_cost_hdr_tbl.product_name,
            'P_Product' AS e_type,
            '' as employee_number,
            r_job_cost_hdr_tbl.product_name AS com_product,
            r_job_cost_hdr_tbl.batch_number,
            r_job_cost_hdr_tbl.batch_number as com_batch,
            ROUND(r_job_cost_hdr_tbl.qty, 2) AS qty,
            ROUND(r_job_cost_hdr_tbl.rate, 2) AS rate,
            ROUND(r_job_cost_hdr_tbl.total, 2) AS total,w_jobcard_hdr_t.job_process
        FROM
            `r_job_cost_hdr_tbl`
        LEFT JOIN m_products_t ON m_products_t.concatenated_product = r_job_cost_hdr_tbl.product_name
        LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id  = m_products_t.product_category_id 
        LEFT JOIN m_product_subcategory_t ON m_product_subcategory_t.product_subcategory_id  = m_products_t.product_subcategory_id 
        LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id  = m_products_t.product_group_id 
        LEFT JOIN m_product_variants_t ON m_product_variants_t.product_variant_id  = m_products_t.product_variant_id 
        LEFT JOIN m_product_type_t ON m_product_type_t.product_type_id  = m_products_t.product_type_id 
        LEFT JOIN m_uom_codes_t ON m_uom_codes_t.uom_code_id = m_products_t.trx_uom_id
        JOIN w_jobcard_hdr_t ON w_jobcard_hdr_t.w_jobs_hdr_id = r_job_cost_hdr_tbl.job_id
        JOIN w_productionplan_hdr_t ON w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id  where 1=1 and  r_job_cost_hdr_tbl.job_date and r_job_cost_hdr_tbl.job_date BETWEEN ? and ?
		union all 
        SELECT
        r_job_cost_hdr_tbl.r_job_cost_hdr_id,
        w_productionplan_hdr_t.plan_no,
        r_job_cost_hdr_tbl.job_number,
        r_job_cost_hdr_tbl.job_date,
        concat_ws('-',left(MonthName(r_job_cost_hdr_tbl.job_date),3),right(year(r_job_cost_hdr_tbl.job_date),2)) AS Month,
        CASE WHEN m_product_category_t.category_name ='COSMETICS' THEN 'PERSONAL CARE PRODUCTS' ELSE 'SIDDHA MEDICINE' END AS cat_grp,
        m_product_category_t.category_name,
        m_product_subcategory_t.subcategory_name,
        m_product_groups_t.group_name,
        m_product_variants_t.product_variant_name,  
        m_product_type_t.product_type,
        m_uom_codes_t.uom_code, 
        r_job_cost_hdr_tbl.product_name,
        r_job_cost_lines_tbl.type as e_type,
        case when r_job_cost_lines_tbl.type!='EMPLOYEE' then
        '' else SUBSTRING_INDEX(r_job_cost_lines_tbl.product_name,'-',1) end as employee_number,
        case when r_job_cost_lines_tbl.type!='EMPLOYEE' then
        r_job_cost_lines_tbl.product_name else SUBSTRING_INDEX(r_job_cost_lines_tbl.product_name,'-',-1) end as com_product,
        r_job_cost_hdr_tbl.batch_number,
        r_job_cost_lines_tbl.batch_number as com_batch,
        case when r_job_cost_lines_tbl.type!='EMPLOYEE' then
        ROUND(r_job_cost_lines_tbl.qty,2) else ROUND(r_job_cost_lines_tbl.qty/60,2) end,
        ROUND(r_job_cost_lines_tbl.rate, 2) AS rate,
        ROUND(r_job_cost_lines_tbl.total, 2) AS total,w_jobcard_hdr_t.job_process
    FROM
        `r_job_cost_hdr_tbl`
            LEFT JOIN m_products_t ON m_products_t.concatenated_product = r_job_cost_hdr_tbl.product_name
        LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id  = m_products_t.product_category_id 
        LEFT JOIN m_product_subcategory_t ON m_product_subcategory_t.product_subcategory_id  = m_products_t.product_subcategory_id 
        LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id  = m_products_t.product_group_id 
        LEFT JOIN m_product_variants_t ON m_product_variants_t.product_variant_id  = m_products_t.product_variant_id 
        LEFT JOIN m_product_type_t ON m_product_type_t.product_type_id  = m_products_t.product_type_id 
    LEFT JOIN m_uom_codes_t ON m_uom_codes_t.uom_code_id = m_products_t.trx_uom_id
    JOIN r_job_cost_lines_tbl ON r_job_cost_lines_tbl.r_job_cost_hdr_id = r_job_cost_hdr_tbl.r_job_cost_hdr_id
    JOIN w_jobcard_hdr_t ON w_jobcard_hdr_t.w_jobs_hdr_id = r_job_cost_hdr_tbl.job_id
    JOIN w_productionplan_hdr_t ON w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id  where 1=1 and  r_job_cost_hdr_tbl.job_date and r_job_cost_hdr_tbl.job_date BETWEEN ? and ?) AS v1";

     $results = \DB::select($SQL, [$start_date,$end_date,$start_date,$end_date]);

   return DataTables::of($results)->make(true);
}
    
	
    public function balancesheetrptindex()
    {
        $this->data['account_line_id']=$this->jcombocomp('f_account_codes_lines_t','account_codes_line_id','account_code|account_code_meaning','');
      return view('batchwisecostrpt.balancesheetdetailsreport',$this->data);    
    }
  
	
	public function getbalancesheetrptData(Request $request)
	{
		
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
    $account_code_line = $request->account_code_line ?? null;

    $SQL = "SELECT * from (
        SELECT
            f_journal_entry_t.journal_entry_id,
            f_journal_entry_t.journal_name,
            f_journal_entry_t.journal_date,
            CONCAT(LEFT(MONTHNAME(f_journal_entry_t.journal_date),3),'-',YEAR(f_journal_entry_t.journal_date)) as monyr,
            f_journal_entry_lines_t.reference_source,
            CASE 
                WHEN f_journal_entry_lines_t.reference_source = 'CUSTOMER' THEN m_customers_t.customer_name
                WHEN f_journal_entry_lines_t.reference_source = 'PRODUCT' THEN m_products_t.concatenated_product
                WHEN f_journal_entry_lines_t.reference_source = 'EMPLOYEE' THEN hr_employee_t.first_name
                WHEN f_journal_entry_lines_t.reference_source = 'SUPPLIER' THEN m_supplier_t.supplier_name
                WHEN f_journal_entry_lines_t.reference_source = 'MACHINE' THEN w_machine_hdr_t.machine_name
                WHEN f_journal_entry_lines_t.reference_source = 'OPENING BALANCE' THEN 'OPENING BALANCE'
            END AS reference_name,
            f_account_class_t.account_class_name,
            f_account_structure_t.concatenated_segments,
            f_account_class_t.main_account_code,
            f_account_structure_t.account_name,
            r2.account_code AS r2_account_code,
            r2.account_code_meaning AS r2_account_code_meaning,
            f_account_codes_lines_t.account_code,
            f_account_codes_lines_t.account_code_meaning,
            fr.account_code AS fr_account_code,
            fr.account_code_meaning AS fr_account_code_meaning,
            r4.account_code AS r4_account_code,
            r4.account_code_meaning AS r4_account_code_meaning,
            f_journal_entry_lines_t.debit_amount,
            f_journal_entry_lines_t.credit_amount,
            (f_journal_entry_lines_t.debit_amount - f_journal_entry_lines_t.credit_amount) AS amount,
            f_account_codes_lines_t.description
        FROM f_journal_entry_t
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        LEFT JOIN m_products_t ON m_products_t.product_id = f_journal_entry_lines_t.reference_id
        LEFT JOIN m_customers_t ON m_customers_t.customer_id = f_journal_entry_lines_t.reference_id
        LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = f_journal_entry_lines_t.reference_id
        LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = f_journal_entry_lines_t.reference_id
        LEFT JOIN w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id = f_journal_entry_lines_t.reference_id
        LEFT JOIN f_account_class_t ON f_account_class_t.account_class_id = f_account_structure_t.main_account_id
        LEFT JOIN f_account_codes_lines_t ON f_account_codes_lines_t.account_codes_line_id = f_account_structure_t.future_reference1
        LEFT JOIN f_account_codes_lines_t AS fr ON fr.account_codes_line_id = f_account_structure_t.future_reference2
        LEFT JOIN f_account_codes_lines_t AS r4 ON r4.account_codes_line_id = f_account_structure_t.sub_account4_id
        LEFT JOIN f_account_codes_lines_t AS r2 ON r2.account_codes_line_id = f_account_structure_t.sub_account_id
        WHERE f_journal_entry_lines_t.journal_date BETWEEN ? AND ?
        AND fr.account_codes_line_id = ?
    ) AS v1";

    $results = \DB::select($SQL, [$start_date, $end_date, $account_code_line]);

    return response()->json(['data' => $results]);
}


public function Export(Request $request)
{
    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date   = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

    $SQL = "SELECT * from (SELECT
            r_job_cost_hdr_tbl.r_job_cost_hdr_id,
            w_productionplan_hdr_t.plan_no,
            r_job_cost_hdr_tbl.job_number,
            r_job_cost_hdr_tbl.job_date,
            concat_ws('-',left(MonthName(r_job_cost_hdr_tbl.job_date),3),right(year(r_job_cost_hdr_tbl.job_date),2)) AS Month,
            CASE WHEN m_product_category_t.category_name ='COSMETICS' THEN 'PERSONAL CARE PRODUCTS' ELSE 'SIDDHA MEDICINE' END AS cat_grp,
            m_product_category_t.category_name,
            m_product_subcategory_t.subcategory_name,
            m_product_groups_t.group_name,
            m_product_variants_t.product_variant_name,  
            m_product_type_t.product_type,
            m_uom_codes_t.uom_code, 
            r_job_cost_hdr_tbl.product_name,
            'P_Product' AS e_type,
            '' as employee_number,
            r_job_cost_hdr_tbl.product_name AS com_product,
            r_job_cost_hdr_tbl.batch_number,
            r_job_cost_hdr_tbl.batch_number as com_batch,
            ROUND(r_job_cost_hdr_tbl.qty, 2) AS qty,
            ROUND(r_job_cost_hdr_tbl.rate, 2) AS rate,
            ROUND(r_job_cost_hdr_tbl.total, 2) AS total,w_jobcard_hdr_t.job_process
        FROM
            `r_job_cost_hdr_tbl`
        LEFT JOIN m_products_t ON m_products_t.concatenated_product = r_job_cost_hdr_tbl.product_name
        LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id  = m_products_t.product_category_id 
        LEFT JOIN m_product_subcategory_t ON m_product_subcategory_t.product_subcategory_id  = m_products_t.product_subcategory_id 
        LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id  = m_products_t.product_group_id 
        LEFT JOIN m_product_variants_t ON m_product_variants_t.product_variant_id  = m_products_t.product_variant_id 
        LEFT JOIN m_product_type_t ON m_product_type_t.product_type_id  = m_products_t.product_type_id 
        LEFT JOIN m_uom_codes_t ON m_uom_codes_t.uom_code_id = m_products_t.trx_uom_id
        JOIN w_jobcard_hdr_t ON w_jobcard_hdr_t.w_jobs_hdr_id = r_job_cost_hdr_tbl.job_id
        JOIN w_productionplan_hdr_t ON w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id  where 1=1 and  r_job_cost_hdr_tbl.job_date and r_job_cost_hdr_tbl.job_date BETWEEN ? and ?
		union all 
        SELECT
        r_job_cost_hdr_tbl.r_job_cost_hdr_id,
        w_productionplan_hdr_t.plan_no,
        r_job_cost_hdr_tbl.job_number,
        r_job_cost_hdr_tbl.job_date,
        concat_ws('-',left(MonthName(r_job_cost_hdr_tbl.job_date),3),right(year(r_job_cost_hdr_tbl.job_date),2)) AS Month,
        CASE WHEN m_product_category_t.category_name ='COSMETICS' THEN 'PERSONAL CARE PRODUCTS' ELSE 'SIDDHA MEDICINE' END AS cat_grp,
        m_product_category_t.category_name,
        m_product_subcategory_t.subcategory_name,
        m_product_groups_t.group_name,
        m_product_variants_t.product_variant_name,  
        m_product_type_t.product_type,
        m_uom_codes_t.uom_code, 
        r_job_cost_hdr_tbl.product_name,
        r_job_cost_lines_tbl.type as e_type,
        case when r_job_cost_lines_tbl.type!='EMPLOYEE' then
        '' else SUBSTRING_INDEX(r_job_cost_lines_tbl.product_name,'-',1) end as employee_number,
        case when r_job_cost_lines_tbl.type!='EMPLOYEE' then
        r_job_cost_lines_tbl.product_name else SUBSTRING_INDEX(r_job_cost_lines_tbl.product_name,'-',-1) end as com_product,
        r_job_cost_hdr_tbl.batch_number,
        r_job_cost_lines_tbl.batch_number as com_batch,
        case when r_job_cost_lines_tbl.type!='EMPLOYEE' then
        ROUND(r_job_cost_lines_tbl.qty,2) else ROUND(r_job_cost_lines_tbl.qty/60,2) end,
        ROUND(r_job_cost_lines_tbl.rate, 2) AS rate,
        ROUND(r_job_cost_lines_tbl.total, 2) AS total,w_jobcard_hdr_t.job_process
    FROM
        `r_job_cost_hdr_tbl`
            LEFT JOIN m_products_t ON m_products_t.concatenated_product = r_job_cost_hdr_tbl.product_name
        LEFT JOIN m_product_category_t ON m_product_category_t.product_category_id  = m_products_t.product_category_id 
        LEFT JOIN m_product_subcategory_t ON m_product_subcategory_t.product_subcategory_id  = m_products_t.product_subcategory_id 
        LEFT JOIN m_product_groups_t ON m_product_groups_t.product_group_id  = m_products_t.product_group_id 
        LEFT JOIN m_product_variants_t ON m_product_variants_t.product_variant_id  = m_products_t.product_variant_id 
        LEFT JOIN m_product_type_t ON m_product_type_t.product_type_id  = m_products_t.product_type_id 
    LEFT JOIN m_uom_codes_t ON m_uom_codes_t.uom_code_id = m_products_t.trx_uom_id
    JOIN r_job_cost_lines_tbl ON r_job_cost_lines_tbl.r_job_cost_hdr_id = r_job_cost_hdr_tbl.r_job_cost_hdr_id
    JOIN w_jobcard_hdr_t ON w_jobcard_hdr_t.w_jobs_hdr_id = r_job_cost_hdr_tbl.job_id
    JOIN w_productionplan_hdr_t ON w_productionplan_hdr_t.productionplan_hdr_id = w_jobcard_hdr_t.reference_source_id  where 1=1 and  r_job_cost_hdr_tbl.job_date and r_job_cost_hdr_tbl.job_date BETWEEN ? and ?) AS v1";

    $data = DB::select($SQL, [$start_date,$end_date,$start_date,$end_date]);

    $filename = "JournalReport.csv";

    $headers = [
        "Content-Type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $callback = function () use ($data) {

        $file = fopen('php://output', 'w');

        // Headings
        fputcsv($file, [
              'Plan NO',
              'Job No',
              'Job Date',
              'Month YY',
              'Group',
              'Product Category',
              'Product Sub Category',
              'Product Group',
              'Product Variant',
              'Product Type',
              'UOM Code',
              'Product Name',
              'Type',
              'Batch Number',
              'Employee ID',
              'Component Product Name',
              'Component Batch Number',
              'Qty',
              'Rate',
              'Value'
        ]);

        foreach ($data as $row) {
            fputcsv($file, [
          $row->plan_no,
          $row->job_number,
          $row->job_date,
          $row->Month,
          $row->cat_grp,
          $row->category_name,
          $row->subcategory_name,
          $row->group_name,
          $row->product_variant_name,
          $row->product_type,
          $row->uom_code,
          $row->product_name,
          $row->e_type,
          $row->batch_number,
          $row->employee_number,
          $row->com_product,
          $row->com_batch,
          $row->qty,
          $row->rate,
          $row->total
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

}
