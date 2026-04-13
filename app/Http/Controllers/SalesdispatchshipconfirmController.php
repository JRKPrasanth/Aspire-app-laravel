<?php

namespace App\Http\Controllers;
use App\Salesdispatchshipconfirm;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SalesdispatchshipconfirmController extends Controller
{
    
    public function create(Request $request)
    {
        // restrict illegal menu entry purpose - RATHI R

        $url = $request->path();
        
        $Controller = new Controller();
        $access = $Controller->Accessdined();
        
        $userAccess = json_decode($access, true);
        
        // Flatten the $userAccess array
        $flatUserAccess = [];
        foreach ($userAccess as $accessItem) {
            if (is_array($accessItem)) {
                $flatUserAccess = array_merge($flatUserAccess, $accessItem);
            } else {
                $flatUserAccess[] = $accessItem;
            }
        }
        
        if (!in_array($url, $flatUserAccess)) {
            return view('accessresticted.view');
               
        }

        // END

        $this->data['pageMethod']="salesdispatchshipconfirm";
        $this->data['urlmenu']=$this->indexs();
        return view('salesdispatchshipconfirm.table',$this->data);
    }


    public function getshipstatus($id=null){

         $dis_data1 = \DB::select("SELECT s_dispatched_qty_t.issue_qoh,i_qoh_detail_t.cost,s_dispatched_qty_t.batch_no,s_dispatch_lines_t.product_id,m_products_t.account_code_id FROM s_dispatch_lines_t JOIN s_dispatched_qty_t on (s_dispatched_qty_t.so_dispatch_hdr_id=s_dispatch_lines_t.so_dispatch_hdr_id and s_dispatched_qty_t.so_dispatch_line_id=s_dispatch_lines_t.so_dispatch_line_id) left JOIN (select * from i_qoh_detail_t where cost>0 order by qoh_detail_id desc)as i_qoh_detail_t  on (i_qoh_detail_t.product_id=s_dispatch_lines_t.product_id and i_qoh_detail_t.batch_number=s_dispatched_qty_t.batch_no and i_qoh_detail_t.cost>0) JOIN m_products_t on m_products_t.product_id=s_dispatch_lines_t.product_id where s_dispatch_lines_t.so_dispatch_hdr_id='$id' ORDER by s_dispatch_lines_t.product_id asc");
                    //Invoice Entry    

        $ship_no = \DB::table('s_dispatch_hdr_t')->leftjoin('s_invoice_hdr_t','s_invoice_hdr_t.reference_source_id','=','s_dispatch_hdr_t.so_dispatch_hdr_id')->where('so_dispatch_hdr_id','=',$id)->select('s_invoice_hdr_t.invoice_number','s_invoice_hdr_t.invoice_type','s_invoice_hdr_t.invoice_date')->get();  
       
        $ship_invno = "SHIP-".$ship_no[0]->invoice_number;
        $invdate=$ship_no[0]->invoice_date;
       $org=\Session::get('organization');
       $loc=\Session::get('location');
       $compy=\Session::get('companyid'); 
        
//         $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$ship_invno','SHIPMENT CONFIRM','$invdate','$id','APPROVED','$compy','$loc','$org')");

//         $jid = \DB::getPdo()->lastInsertId();
// $product='';
// $acc_id='';
// $rate=0;
// $total=0;
// $tkey=0;
//  foreach ($dis_data1 as $key => $value) {
//      $total+=$value->cost*$value->issue_qoh;

//      if($product==$value->product_id)
//      {
//         $rate+=$value->cost*$value->issue_qoh;
//      }
//      elseif($key!=0)
//      {

//         if($rate>0)
//         {
//         $tkey++;
//             $journal_lines_data[$tkey]['journal_entry_id']=$jid;
//             $journal_lines_data[$tkey]['journal_date']=$ship_no[0]->invoice_date;
//             $journal_lines_data[$tkey]['reference_source']="PRODUCT";
//             $journal_lines_data[$tkey]['reference_id']=$product;
//             $journal_lines_data[$tkey]['account_id']=$acc_id;
            
//             $journal_lines_data[$tkey]['debit_amount']='';
//             $journal_lines_data[$tkey]['credit_amount']=$rate;
//             $journal_lines_data[$tkey]['line_no']=$tkey+1;
//             $journal_lines_data[$tkey]['created_by']=\Session::get('id');
//             $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
//             $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
//             $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
//             $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
//             $journal_lines_data[$tkey]['company_id']=\Session::get('location');

//         }
//           $rate=$value->cost*$value->issue_qoh;
//           $product=$value->product_id; 
//           $acc_id=$value->account_code_id;
//      }
//      else
//      {
//           $rate=$value->cost*$value->issue_qoh;
//           $product=$value->product_id; 
//           $acc_id=$value->account_code_id;
//      }



//  }
//  if($rate>0)
//         {
//             $tkey++; 
//             $journal_lines_data[$tkey]['journal_entry_id']=$jid;
//             $journal_lines_data[$tkey]['journal_date']=$ship_no[0]->invoice_date;
//             $journal_lines_data[$tkey]['reference_source']="PRODUCT";
//             $journal_lines_data[$tkey]['reference_id']=$product;
//             $journal_lines_data[$tkey]['account_id']=$acc_id;
            
//             $journal_lines_data[$tkey]['debit_amount']='';
//             $journal_lines_data[$tkey]['credit_amount']=$rate;
//             $journal_lines_data[$tkey]['line_no']=$tkey+1;
//             $journal_lines_data[$tkey]['created_by']=\Session::get('id');
//             $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
//             $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
//             $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
//             $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
//             $journal_lines_data[$tkey]['company_id']=\Session::get('location');
// }
// $accntsettings = \DB::table('f_account_setting_t')->where('module_name','=','salesaccount')->select('sales_account_id','cogs_account_id')->get();
//       if($ship_no[0]->invoice_type=="SAMPLE")
//       {
//         $cogs_account_id = 85;
//     }
//     else
//     {
//  $cogs_account_id = $accntsettings[0]->cogs_account_id;
// }
// if($total>0)
// {
//              $tkey=0; 
//             $journal_lines_data[$tkey]['journal_entry_id']=$jid;
//             $journal_lines_data[$tkey]['journal_date']=$ship_no[0]->invoice_date;
//             $journal_lines_data[$tkey]['reference_source']="";
//             $journal_lines_data[$tkey]['reference_id']="";
//             $journal_lines_data[$tkey]['account_id']=$cogs_account_id;
            
//             $journal_lines_data[$tkey]['debit_amount']=$total;
//             $journal_lines_data[$tkey]['credit_amount']='';
//             $journal_lines_data[$tkey]['line_no']=$tkey+1;
//             $journal_lines_data[$tkey]['created_by']=\Session::get('id');
//             $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
//             $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
//             $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
//             $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
//             $journal_lines_data[$tkey]['company_id']=\Session::get('location');

//     \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);  
// }
        if($id!='') {
             $result= \DB::table('s_dispatch_hdr_t')->where('so_dispatch_hdr_id', $id)->update(['dispatch_status' => 'SHIPPED']);
             //$result = \DB::select("update s_dispatch_hdr_t set dispatch_status = 'SHIPPED' where so_dispatch_hdr_id = '$id'");
           // $result = \DB::select($SQL);
            $disdata = \DB::select("SELECT s_dispatched_qty_t.*,s_dispatch_lines_t.* FROM `s_dispatched_qty_t` left join s_dispatch_lines_t on s_dispatch_lines_t.so_dispatch_line_id=s_dispatched_qty_t.so_dispatch_line_id where s_dispatch_lines_t.so_dispatch_hdr_id=$id");
             $dis_data = \DB::select("SELECT * from s_dispatch_hdr_t where so_dispatch_hdr_id=$id ");
             $repl = '';
         if(count($dis_data) > 0 ){
             $source=$dis_data[0]->dispatch_source;
             if($source=="INVOICE"){
                $replace_type = \DB::table('s_invoice_hdr_t')->where('invoice_hdr_id', $dis_data[0]->reference_source_id)->select('invoice_type')->get();
                if(count($replace_type)>0){
                    $repl = $replace_type[0]->invoice_type;              
                }
                \DB::table('s_invoice_hdr_t')->where('invoice_hdr_id', $id)->update(['shiped_status' => 'SHIPPED']);
             //    \DB::select("update s_invoice_hdr_t set shiped_status = 'SHIPPED' where invoice_hdr_id = '$id'");
             }
             else{
                //$invoice_data=\DB::table('s_invoice_hdr_t')->where('reference_source_id', $id)->where('source','DISPATCH')->update(['shiped_status' => 'SHIPPED']);
               // $invoice_data=\DB::table('s_invoice_hdr_t')->where('reference_source_id', $id)->where('source','DISPATCH')->orWhere('source','REPLACEMENT')->update(['shiped_status' => 'SHIPPED']);
                $invoice_data= \DB::select("update s_invoice_hdr_t set shiped_status = 'SHIPPED' where (source='DISPATCH' or source='REPLACEMENT') and reference_source_id=$id");
             }
         }
        
             return response()->json(array('status' => 'success', 'message' => 'Shipped Successfully'));
         
        }else{
            return response()->json(array('status' => 'error', 'message' => 'Shipped UnSuccessful'));
        }

    }


    public function getshipgriddata()
    {

    $wh='';


        
        $groupname=\Session::get('groupname');
		$grid_date=\Session::get('griddate');
        $gridenddate=\Session::get('gridenddate');
        $compy=\Session::get('companyid');
		$loc=\Session::get('location');
        $tbl_name="v1";
        $date_col="dispatch_date";
        $status_col="dispatch_status";
		
      if($groupname=='1' || $groupname=='Admin'){
            $wh.=" and  $tbl_name.company_id=".$compy;
            
            
    }else{
        $wh .=" and ( ( $tbl_name.$date_col < '$grid_date'  and ($tbl_name.$status_col='DISPATCHED' or $tbl_name.$status_col!='SHIPPED')) or  ( $tbl_name.$date_col BETWEEN '$grid_date' and '$gridenddate' ) )  ";
        $wh.="and  $tbl_name.company_id=".$compy." and $tbl_name.location_id=".$loc;
        
    }

    $SQL = "select * from(SELECT
    s_dispatch_hdr_t.`so_dispatch_hdr_id`,
    s_invoice_hdr_t.invoice_number,
    s_dispatch_hdr_t.dispatch_number,
   s_dispatch_hdr_t.dispatch_source,
    s_dispatch_hdr_t.dispatch_date,
    s_dispatch_hdr_t.dispatch_status,
    s_dispatch_hdr_t.prepare_date,
    s_dispatch_hdr_t.deliver_to_location,
    s_dispatch_hdr_t.remarks,
    s_dispatch_hdr_t.company_id,
    s_dispatch_hdr_t.location_id,
    s_invoice_hdr_t.invoice_hdr_id,
    tb_users.username,
    m_frieghtcarriers_hdr_t.carrier_name,
	 concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as empname,
		 concat(m_customers_t.customer_number,'-',m_customers_t.customer_name) as customername
FROM
    s_dispatch_hdr_t 
    left join s_invoice_hdr_t on s_invoice_hdr_t.reference_number=s_dispatch_hdr_t.dispatch_number
    left join m_frieghtcarriers_hdr_t ON
    m_frieghtcarriers_hdr_t.ar_frieghtcarriers_hdr_id=s_dispatch_hdr_t.freight_carrier_id
    left join tb_users on
    tb_users.id=s_dispatch_hdr_t.preparer_id
	LEFT JOIN hr_employee_t ON(hr_employee_t.employee_id = s_dispatch_hdr_t.`employee_id`)
 LEFT JOIN m_customers_t ON m_customers_t.customer_id = s_dispatch_hdr_t.`ship_to_customer_id`
WHERE
    s_dispatch_hdr_t.dispatch_status = 'DISPATCHED' AND  s_dispatch_hdr_t.dispatch_status != 'SHIPPED' ) as v1 where 1=1 $wh";
       
   
        $result = \DB::select( $SQL );

		return DataTables::of($result)->make(true);

    }



}
