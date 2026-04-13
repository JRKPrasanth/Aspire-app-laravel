<?php

namespace App\Http\Controllers;
use DB;
use App\Receipts;
use App\Receiptlines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect,Input;
use session;


class ReceiptsController extends Controller
{
      public function __construct() {
        $this->data = array();
        $this->table = "s_receipts_hdr_t";
        $this->subtable = "s_receipts_lines_t";
        $this->pageModule = "receipts";
        $this->model = new Receipts;
        $this->submodel = new Receiptlines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'receipts',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu']=$this->indexs(); 
        $this->modelname = new Receipts();
        $this->data['pageFormtype'] = 'ajax';
    }  
public function index()
    {
        $this->data['batchopt'] = $this->jqgridselect('s_receipt_batches_hdr_t', 's_receipt_batch_hdr_id', 'receipt_batch_name');
       $table = \DB::table('s_receipts_hdr_t')->get();
       $this->data['datas'] = $table;
      $this->data['pageMethod']="receipts";
       return view('receipts.table',$this->data);
    }
    public function getReceiptsData() {
        $wh = '';
        if ($_GET['_search'] == 'true') {
            $wh = $this->jqgridsearch('s_receipts_hdr_t', $_GET['filters']);
        }
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if (!$sidx)
            $sidx = 1;
        $result = \DB::select("SELECT COUNT(receipt_hdr_id) AS count FROM s_receipts_hdr_t where 1=1 $wh");
        $count = $result[0]->count;
        if ($count > 0 && $limit > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages)
            $page = $total_pages;
        $start = $limit * $page - $limit;
        if ($start < 0)
            $start = 0;
        $SQL = "SELECT 
                s_receipts_hdr_t.receipt_hdr_id,
                s_receipts_hdr_t.receipt_number,
                s_receipts_hdr_t.receipt_date,
                s_receipts_hdr_t.receipt_status,
                s_receipts_hdr_t.batch_total_amount,
                s_receipt_batches_hdr_t.receipt_batch_name as receipt_batch_hdr_id
                FROM s_receipts_hdr_t 
                left join s_receipt_batches_hdr_t on(s_receipt_batches_hdr_t.s_receipt_batch_hdr_id=s_receipts_hdr_t.receipt_batch_hdr_id)
               $wh ORDER BY $sidx $sord LIMIT $start , $limit";
        
        $result = \DB::select($SQL);
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        echo json_encode($responce);
    }
  
    /*Karthigaa Purpose for Default Cheque Number*/
    function getchequeno($accno=null){
        $sql = \DB::SELECT("select cheque_from_no,cheque_to_no from f_bank_cheque_lines_t where bank_cheque_hdr_id = '$accno' and cheque_status='ACTIVE'");
//        $cheqno=\DB::select("select MAX(cheque_no) as cheque_no from p_payments_hdr_t where account_no='$accno'");
//        $cheqno=\DB::select("select MAX(cheque_count) as cheque_no from f_bank_cheque_lines_t where bank_cheque_hdr_id='$accno'");
        $cheqno=\DB::select("SELECT MAX(cheq.cheque_count) AS cheque_no FROM f_bank_cheque_lines_t cheq LEFT JOIN p_payments_hdr_t payhdr on (payhdr.account_no=cheq.bank_cheque_hdr_id)where payhdr.account_no='$accno'");
//        dd($cheqno);
        $max=$cheqno[0]->cheque_no;
        $end=$sql[0]->cheque_to_no;
        if($max == null){
            $data1 = $sql[0]->cheque_from_no;
        }
        else{
            $data1=$max+1;
        }
        $data['chequeno']=$data1;
        $data['endcheque']=$end;

         return $data;  
    }
/*End*/
    
   public function create($id=null)
	{ 		
   		if($id == null)
		{ 
			
		$this->data['row']= (object) array();
                $this->data['row']->receipt_hdr_id = "";
                $this->data['row']->receipt_date = date('Y-m-d');
                $this->data['row']->receipt_number = "";
                $this->data['row']->receipt_status = "";
                $this->data['row']->batch_total_amount = "";
                $this->data['row']->receipt_reference = "";
                $this->data['row']->receipt_type_id  = "";
                $this->data['row']->receipt_source = "";
                $this->data['row']->advance_amount  = "";
                $this->data['cheque_no'] = $this->jCombocomp('f_bank_cheque_lines_t','bank_cheque_line_id','cheque_no','');
                 $this->data['row']->remarks  = "";
		$this->data['id'] = '';
               
                $this->data['receipt_batch_hdr_id'] = $this->jcustomselectcomp('s_receipt_batches_hdr_t','s_receipt_batch_hdr_id','receipt_batch_name','','and receipt_batch_status="INITIATED"');
                $this->data['bank_id'] = $this->jCombo('f_bank_account_hdr_t','bank_account_hdr_id','bank_name','');
                $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t','bank_account_line_id','account_number','');
                $this->data['customer_id'] = $this->jCombo('m_customers_t','customer_id','customer_name','');
		$this->data['customer_site_id'] = $this->jCombo('m_customer_sites_t','customer_site_id','customer_site_name','');
                $this->data['account_structure_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
                $this->data['organization_id'] = $this->jCombo('m_organizations_t','organization_id','organization_name',\Session::get('organization'));
                $this->data['created_by'] = $this->jCombo('tb_users','id','username',''); 
                
		
		$this->data['invoice_hdr_id'] = $this->jCombocomp('s_invoice_hdr_t','invoice_hdr_id','invoice_number','');
                
//		$this->data['account_structure_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
                $this->data['linedata'] = array(); 
			
		} 
		  else
		  {
			$this->data['id'] = $id;
		$table = \DB::table('s_receipts_hdr_t')->where('receipt_hdr_id',$id)->get();
             
		$this->data['row'] = $table[0];
		$tablelines = \DB::table('s_receipts_lines_t')->where('receipt_hdr_id',$id)->get();
		$this->data['linedata'] = $tablelines;
              
		
		$this->data['organization_id'] = $this->jCombo('m_organizations_t','organization_id','organization_name',$table[0]->organization_id);
                $this->data['created_by'] = $this->jCombo('tb_users','id','username',$table[0]->created_by);
                $this->data['receipt_batch_hdr_id'] = $this->jCombo('s_receipt_batches_hdr_t','s_receipt_batch_hdr_id','receipt_batch_name',$table[0]->receipt_batch_hdr_id);
                $this->data['bank_id'] = $this->jCombo('f_bank_account_hdr_t','bank_account_hdr_id','bank_name',$table[0]->bank_id);
                $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t','bank_account_line_id','account_number',$table[0]->account_no);
                $this->data['customer_id'] = $this->jCombo('m_customers_t','customer_id','customer_name',$table[0]->customer_id);
		$this->data['customer_site_id'] = $this->jCombo('m_customer_sites_t','customer_site_id','customer_site_name',$table[0]->customer_site_id);
                $this->data['account_structure_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$table[0]->account_structure_id);
                $this->data['invoice_hdr_id'] = $this->jCombo('s_invoice_hdr_t','invoice_hdr_id','invoice_number','');
		
                $this->data['receipt_status'] = $table[0]->receipt_status;  
		  }
		if(count($this->data['linedata']) >= 1)
		{
			foreach ($this->data['linedata'] as $key => $value) {
			$this->data['linedata'][$key]->invoice_hdr_id = $this->data['invoice_hdr_id'] = $this->jCombo('s_invoice_hdr_t','invoice_hdr_id','invoice_number',$value->invoice_hdr_id);	
//			$this->data['linedata'][$key]->account_structure_id =  $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$value->account_structure_id);	
			}
                       
		}
//               dd($this->data);
          	return view('receipts.form',$this->data);

        }
    

   
    /*Karthigaa purpose for Save function*/   
         public function save(Request $request){
		//	dd($_POST);
                        $id='';
                          //   dd($request->all());
			$data = $this->validatePost($request->all(),$this->table,'header');	
//                   dd($data);
			$lines_data = $this->validatePost($request->all(),$this->subtable,'lines');	
                        
                     
                   
                         /*karthigaa Purpose for Auto Number*/
                        if ($_POST['receipt_number'] =="") 
				{
					$seqno=$this->Seqnoe('RCPT-','s_receipts_hdr_t','','receipt_count');
					$data['receipt_number'] = $seqno[0];
                                        $data['receipt_count'] = $seqno[1];
				}
				else
				{
					$seqno[0] = $_POST['receipt_number'];
				}
			 /*End*/  
			\DB::beginTransaction();
			try
			{
				$id=$this->model->insertRow($data);
				$lid=$this->submodel->subgridSave($lines_data,$id);
  
                /*End*/ 
                 //Cheque no count update
                    $chequeno=$_POST['cheque_no'];
                    $accno=$_POST['account_no'];
                    $chequeupdate=\DB::update("UPDATE f_bank_cheque_lines_t set cheque_count='$chequeno' where bank_cheque_hdr_id='$accno'");
        /*Karthigaa Purpose For Journal Entry Insert*/
           if($_POST['receipt_status']=="INITIATED"){
                           $rcptdate=$_POST['receipt_date'];
                           $org=\Session::get('organization');
                           $loc=\Session::get('location');
                           $compy=\Session::get('companyid');
                           //Journal Header Insert
                           $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('RECEIVABLES','$rcptdate','$id','OPEN','$compy','$loc','$org')");
                           $jid = DB::getPdo()->lastInsertId();

                           //Journal Lines Insert
                      $receiptslines=DB::table('s_receipts_lines_t')->where('receipt_hdr_id',$id)->get();
                         foreach($receiptslines as $key => $value) {
                             $inv_acc['journal_entry_id']   = $jid; 
                                $cid=$_POST['customer_id'];
                                $cusacc=\DB::select("select account_structure_id from m_customers_t where customer_id=$cid");
                            $inv_acc['account_id']   =$cusacc[0]->account_structure_id;
                            $inv_acc['debit_amount']   =0;
                            $inv_acc['credit_amount']   = $value->receipt_amount;
                            \DB::table('f_journal_entry_lines_t')->insert($inv_acc);
                           }

                            foreach($receiptslines as $key => $value) {
                            $inv_acc1['journal_entry_id']   = $jid; 
                             $accid=$_POST['account_structure_id'];
                            $inv_acc1['account_id']   =$accid;
                            $inv_acc1['debit_amount']   = $value->receipt_amount;
                            $inv_acc1['credit_amount']   = 0;
                            \DB::table('f_journal_entry_lines_t')->insert($inv_acc1);
                           }
                } 
                  /*End*/
                   
				\DB::commit();
				return response()->json(array('status' => 'success', 'message' => 'Receipt Saved','id' => $id,'lid' => $lid,'auto_no'=>$seqno[0]));
			}
			catch (\Illuminate\Database\QueryException$e)
			{
				$message = explode('(', $e->getMessage());
				$dbCode = rtrim($message[0], ']');
				$dbCode = trim($dbCode, '[');
                                dd($dbCode);
				\DB::rollback();
				return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
			}	

        }

    function getreceiptbatchdetails($batchid=null) 
        {
        $sql = array();
        $sql = \DB::SELECT('select * from s_receipt_batches_hdr_t where s_receipt_batch_hdr_id=' . $batchid);
        return $sql;
        }
    public function getPaid($id = null, $inv_amount = null) {
        $result = \Db::select("select SUM(receipt_amount) as receipt_amount from s_receipts_lines_t where invoice_hdr_id='" . $id . "'");
        $paid = $result[0]->receipt_amount;
        if ($paid != null) {
            return $paid;
        } else {
            return 0;
        }
    }
        public function getBalance($id = null, $inv_amount = null) {
        $result = \Db::select("select SUM(receipt_amount) as receipt_amount from s_receipts_lines_t where invoice_hdr_id='" . $id . "'");
        $paid = $result[0]->receipt_amount;
	//dd($inv_amount);	
        if (!empty($result)) {
            return ($inv_amount - $paid);
        }if (empty($result)) {
            return $inv_amount;
        }
    }
    
     /*Karthigaa purpose:to get Invoice details*/	
	public function rcptinvoicedetails($id=null)
	{
		$sql=\DB::table('s_receipt_batches_hdr_t as batch')->leftjoin('s_receipt_batches_lines_t as batchln','batch.s_receipt_batch_hdr_id','=','batchln.s_receipt_batch_hdr_id')
			->select('batch.s_receipt_batch_hdr_id','batchln.s_receipt_batch_lines_id','batchln.invoice_hdr_id','batchln.invoice_amount')->where('batch.s_receipt_batch_hdr_id',$id)->get();
//                dd($sql);
		$html = '';
		foreach($sql as $key=>$val)
		{
			
			$inv=$this->jCombo('s_invoice_hdr_t','invoice_hdr_id','invoice_number',$val->invoice_hdr_id);
//                        $acc_code=$this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
                       $paidamt=$this->getPaid($val->invoice_hdr_id);
                       $bal=$this->getBalance($val->invoice_hdr_id,$val->invoice_amount);

			$html.='<tr class="table'.$key.'"><body>
					<td><input type="hidden" name="bulk_receipt_line_id[]" class="form-control input-sm bulk_receipt_line_id" value=""></td>
					<td ><input type="text" name="bulk_line_no[]" class="input-sm bulk_line_no" value="'.($key+1).'" readonly="readonly" style="width: 68px !important;text-align: center;"></td>
					<td>
                                        
					<select name="invoice_hdr_id[]" class="input-sm invoice_hdr_id"  style="pointer-events:none;color:black;border-color:#0000007a;background-color:#00000014;">'.$inv.'</select>
					</td>
					<td>
					<input type="text" name="bulk_batch_invoice_amount[]" class="input-sm bulk_batch_invoice_amount" readonly="readonly" value="'.$val->invoice_amount.'" style="color:black;background-color:#00000014;">
					</td>
					<td>
                                        <input type="text" name="bulk_receipt_amount[]"  class="input-sm bulk_receipt_amount" value="" required style="color:black;">
                                        </td>
                                        <td>
                                        <input type="text" name="bulk_paid_amount[]"  class="input-sm bulk_paid_amount" readonly="readonly" value="'.$paidamt.'" style="color:black;background-color:#00000014;">
                                        </td>
                                        <td>
                                        <input type="text"  name="bulk_balance_amount[]"  class="input-sm bulk_balance_amount" readonly="readonly" value="'.$bal.'" style="color:black;background-color:#00000014;">
                                            <input type="hidden"  name="balance_amount"  class="input-sm balance_amount'.$key.'" readonly="readonly" value="'.$bal.'" style="color:black;background-color:#00000014;">
                                        </td>
                                       
                                        <td>
                                        <input type="text" name="bulk_comments[]"   class="input-sm bulk_comments" value="" style="color:black;">
                                        </td>
					<input type="hidden" name="counter[]">
					</td></body>
					</tr>';
		
		}
	
		return $html;
	}
	/*end*/
    /*Karthigaa purpose for Display hdr & Lines View function*/
  public function show(request $request,$id=null)
    {
        if(isset($id))
        {
      $vdata=\DB::table('s_receipts_hdr_t')->leftjoin('m_customers_t','m_customers_t.customer_id','=','s_receipts_hdr_t.customer_id')
                                            ->leftjoin('m_organizations_t','m_organizations_t.organization_id','=','s_receipts_hdr_t.organization_id')
                                            ->leftjoin('m_customer_sites_t','m_customer_sites_t.customer_site_id','=','s_receipts_hdr_t.customer_site_id')
                                            ->leftjoin('s_receipt_batches_hdr_t','s_receipt_batches_hdr_t.s_receipt_batch_hdr_id','=','s_receipts_hdr_t.receipt_batch_hdr_id')
                                            ->leftjoin('f_bank_account_hdr_t','f_bank_account_hdr_t.bank_account_hdr_id','=','s_receipts_hdr_t.bank_id')
                                            ->leftjoin('f_bank_cheque_lines_t','f_bank_cheque_lines_t.bank_cheque_line_id','=','s_receipts_hdr_t.cheque_no')
                                            ->leftjoin('f_bank_account_lines_t','f_bank_account_lines_t.bank_account_line_id','=','s_receipts_hdr_t.account_no')
                                            ->where('receipt_hdr_id',$id)->get();

          $this->data['receipt_number']=$vdata[0]->receipt_number;
          $this->data['receipt_date']=$vdata[0]->receipt_date;
          $this->data['receipt_status']=$vdata[0]->receipt_status;
          $this->data['receipt_source']=$vdata[0]->receipt_source;
          $this->data['advance_amount']=$vdata[0]->advance_amount;
          $this->data['batch_total_amount']=$vdata[0]->batch_total_amount;
          $this->data['remarks']=$vdata[0]->remarks;
          $this->data['receipt_batch_name']=$vdata[0]->receipt_batch_name;
          $this->data['account_number']=$vdata[0]->account_number;
          $this->data['receipt_reference']=$vdata[0]->receipt_reference;
          $this->data['receipt_type_id']=$vdata[0]->receipt_type_id;
          $this->data['cheque_no']=$vdata[0]->cheque_no;
          $this->data['bank_name']=$vdata[0]->bank_name;
          $this->data['customer_name']=$vdata[0]->customer_name;
          $this->data['customer_site_name']=$vdata[0]->customer_site_name;
          $this->data['organization_name']=$vdata[0]->organization_name;

         $vlinesdata = \DB::table('s_receipts_lines_t')->leftjoin('s_invoice_hdr_t','s_invoice_hdr_t.invoice_hdr_id','=','s_receipts_lines_t.invoice_hdr_id')
                                                      ->leftjoin('f_account_structure_t','f_account_structure_t.f_account_structure_id','=','s_receipts_lines_t.account_structure_id')
                                                      ->where('s_receipts_lines_t.receipt_hdr_id',$id)->get();
            $this->data['vlinesdata']=$vlinesdata;
            $this->data['invoice_number']=$vlinesdata[0]->invoice_number;
            $this->data['concatenated_segments']=$vlinesdata[0]->concatenated_segments;
            
            return view('receipts.view',$this->data);

        }
    }
    public function destroy(Receipts $receipts)
    {
        //
    }
}
