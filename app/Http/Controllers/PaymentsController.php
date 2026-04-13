<?php

namespace App\Http\Controllers;
use DB;
use App\Payments;
use App\Paymentlines;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
     public function __construct() {
        $this->data = array();
        $this->table = "p_payments_hdr_t";
        $this->subtable = "p_payment_lines_t";
        $this->pageModule = "payments";
        $this->model = new Payments;
        $this->submodel = new Paymentlines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'payments',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu']=$this->indexs(); 
        $this->modelname = new Payments();
        $this->data['pageFormtype'] = 'ajax';
    }

    public function index()
    {
        $this->data['batchopt'] = $this->jqgridselect('p_payment_batches_hdr_t', 'payment_batches_hdr_id', 'payment_batch_name');
       $table = \DB::table('p_payments_hdr_t')->get();
       $this->data['datas'] = $table;
      $this->data['pageMethod']="payments";
       return view('payments.table',$this->data);
    }
    public function getPaymentsData() {
        $wh = '';
        if ($_GET['_search'] == 'true') {
            $wh = $this->jqgridsearch('p_payments_hdr_t', $_GET['filters']);
        }
		$loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $org=\Session::get('organization');
		$groupname=\Session::get('groupname');
		if($groupname=='Superadmin' || $groupname=='Admin'){
		$wh.='and p_payments_hdr_t.company_id='.$compy;	
		}else{
			$wh.='and p_payments_hdr_t.company_id='.$compy.' and p_payments_hdr_t.location_id='.$loc;		
		}
		
		
		
		
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if (!$sidx)
            $sidx = 1;
        $result = \DB::select("SELECT COUNT(p_payments_hdr_t.payment_hdr_id) AS count FROM p_payments_hdr_t where 1=1 $wh");
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
                p_payments_hdr_t.payment_hdr_id,
                p_payments_hdr_t.payment_number,
                p_payments_hdr_t.payment_date,
                p_payments_hdr_t.payment_status,
                p_payments_hdr_t.batch_total_amount,
                p_payment_batches_hdr_t.payment_batch_name as payment_batches_hdr_id
                FROM p_payments_hdr_t
                left join p_payment_batches_hdr_t on(p_payment_batches_hdr_t.payment_batches_hdr_id=p_payments_hdr_t.payment_batches_hdr_id)where 1=1  $wh ORDER BY $sidx $sord LIMIT $start , $limit";
		
		

        $result = \DB::select($SQL);
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        echo json_encode($responce);
    }
    public function create($id=null)
	{
   		if($id == null)
		{

		$this->data['row']= (object) array();
                $this->data['row']->payment_hdr_id = "";
                $this->data['row']->payment_date = date('Y-m-d');
                $this->data['row']->payment_number = "";
                $this->data['row']->payment_status = "";
                $this->data['row']->batch_total_amount = "";
                $this->data['row']->payment_reference = "";
                $this->data['row']->payment_type_id  = "";
                $this->data['row']->payment_source  = "";
                $this->data['row']->advance_amount  = "";
                 $this->data['row']->remarks  = "";
	         	$this->data['id'] = '';

                $this->data['payment_batches_hdr_id'] = $this->jcustomselect('p_payment_batches_hdr_t','payment_batches_hdr_id','payment_batch_name','','and payment_batch_status="INITIATED"');
                $this->data['bank_id'] = $this->jCombo('f_bank_account_hdr_t','bank_account_hdr_id','bank_name','');
                $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t','bank_account_line_id','account_number','');
                $this->data['cheque_no'] = $this->jCombo('f_bank_cheque_lines_t','bank_cheque_hdr_id','cheque_no','');
                $this->data['supplier_id'] = $this->jCombo('m_supplier_t','supplier_id','supplier_name','');
                $this->data['supplier_site_id'] = $this->jCombo('m_supplier_sites_t','supplier_site_id','supplier_site_name','');
                $this->data['organization_id'] = $this->jCombologin('m_organizations_t','organization_id','organization_name',\Session::get('organization'));
                $this->data['created_by'] = $this->jCombo('tb_users','id','username','');
                //$this->data['account_structure_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
                $this->data['account_structure_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','account_name','');
		$this->data['po_invoice_id'] = $this->jCombo('p_po_invoice_hdr_t','po_invoice_id','bill_number','');
                $this->data['po_hdr_id'] = $this->jCombo('p_po_hdr_t','po_hdr_id','po_number','');
		
                $this->data['linedata'] = array();

		}
		  else
		  {
			$this->data['id'] = $id;
		$table = \DB::table('p_payments_hdr_t')->where('payment_hdr_id',$id)->get();
//                dd($table);
		$this->data['row'] = $table[0];
		$tablelines = \DB::table('p_payment_lines_t')->where('payment_hdr_id',$id)->get();
		$this->data['linedata'] = $tablelines;


		$this->data['organization_id'] = $this->jCombologin('m_organizations_t','organization_id','organization_name',$table[0]->organization_id);
                $this->data['created_by'] = $this->jCombo('tb_users','id','username',$table[0]->created_by);
                $this->data['payment_batches_hdr_id'] = $this->jCombo('p_payment_batches_hdr_t','payment_batches_hdr_id','payment_batch_name',$table[0]->payment_batches_hdr_id);
                $this->data['bank_id'] = $this->jCombo('f_bank_account_hdr_t','bank_account_hdr_id','bank_name',$table[0]->bank_id);
                $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t','bank_account_line_id','account_number',$table[0]->account_no);
                $this->data['cheque_no'] = $this->jCombo('f_bank_cheque_lines_t','bank_cheque_hdr_id','cheque_no',$table[0]->cheque_no);
                $this->data['supplier_id'] = $this->jCombo('m_supplier_t','supplier_id','supplier_name',$table[0]->supplier_id);
                $this->data['supplier_site_id'] = $this->jCombo('m_supplier_sites_t','supplier_site_id','supplier_site_name',$table[0]->supplier_site_id);
               // $this->data['account_structure_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$table[0]->account_structure_id);
                $this->data['account_structure_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','account_name',$table[0]->account_structure_id);
                $this->data['po_invoice_id'] = $this->jCombo('p_po_invoice_hdr_t','po_invoice_id','bill_number','');
                $this->data['po_hdr_id'] = $this->jCombo('p_po_hdr_t','po_hdr_id','po_number',$table[0]->po_hdr_id);

                $this->data['payment_status'] = $table[0]->payment_status;
		  }
		if(count($this->data['linedata']) >= 1)
		{
			foreach ($this->data['linedata'] as $key => $value) {
			$this->data['linedata'][$key]->po_invoice_id = $this->data['po_invoice_id'] = $this->jCombo('p_po_invoice_hdr_t','po_invoice_id','bill_number',$value->po_invoice_id);
//			$this->data['linedata'][$key]->account_structure_id =  $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$value->account_structure_id);
			}

		}
          	return view('payments.form',$this->data);

        }


  /*Karthigaa purpose for Save function*/
         public function save(Request $request){
		        $id='';
			$data = $this->validatePost($request->all(),$this->table,'header');
			$lines_data = $this->validatePost($request->all(),$this->subtable,'lines');
                            /*karthigaa Purpose for Auto Number*/
                        if ($_POST['payment_number'] =="")
				{
					$seqno=$this->Seqnoe('PMT-','p_payments_hdr_t','','payment_count');
					$data['payment_number'] = $seqno[0];
                                        $data['payment_count'] = $seqno[1];
				}
				else
				{
					$seqno[0] = $_POST['payment_number'];
				}
			 /*End*/
			\DB::beginTransaction();
			try
			{
				$id=$this->model->insertRow($data);
				$lid=$this->submodel->subgridSave($lines_data,$id);
                  // $journallines=\DB::insert("insert into f_journal_entry_lines_t(journal_entry_id,debit_account_id,credit_account_id) select $jid,debit_acc, credit_acc from m_account_setting_t where module_name='payments'");    

/*Karthigaa Purpose For Journal Entry Insert*/
if($_POST['payment_status']=="INITIATED"){
                   $rcptdate=$_POST['receipt_date'];
                   $org=\Session::get('organization');
                   $loc=\Session::get('location');
                   $compy=\Session::get('companyid');
                   //Journal Header Insert
                   $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('PAYABLES','$rcptdate','$id','OPEN','$compy','$loc','$org')");
                   $jid = DB::getPdo()->lastInsertId();
                   //Journal Lines Insert
                   $invoiceid=DB::table('p_payment_lines_t')->where('payment_hdr_id',$id)->get();
               foreach($invoiceid as $key => $value) {
                    $inv_acc['journal_entry_id']   = $jid; 
                     $accid=$_POST['account_structure_id'];
                    $inv_acc['account_id']   =$accid;
                    $inv_acc['debit_amount']   =0;
                    $inv_acc['credit_amount']   = $value->payment_amount;
                    \DB::table('f_journal_entry_lines_t')->insert($inv_acc);
                    }
                    foreach($invoiceid as $key => $value) {
                    $inv_acc1['journal_entry_id']   = $jid; 
                        $sid=$_POST['supplier_id'];
                        $supacc=\DB::select("select account_structure_id from m_supplier_t where supplier_id=$sid");
                    $inv_acc1['account_id']   =$supacc[0]->account_structure_id;
                    $inv_acc1['debit_amount']   =$value->payment_amount;
                    $inv_acc1['credit_amount']   = 0;
                    \DB::table('f_journal_entry_lines_t')->insert($inv_acc1);
               }
        }
                   
          /*End*/
				\DB::commit();
				return response()->json(array('status' => 'success', 'message' => 'Payment Saved','id' => $id,'lid' => $lid,'auto_no'=>$seqno[0]));
			}
			catch (\Illuminate\Database\QueryException$e)
			{
				$message = explode('(', $e->getMessage());
				$dbCode = rtrim($message[0], ']');
				$dbCode = trim($dbCode, '[');
                          
				\DB::rollback();
				return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
			}

        }




        function getBatchdetails($batchid=null)
        {
        $sql = array();
        $sql = \DB::SELECT('select * from p_payment_batches_hdr_t where payment_batches_hdr_id=' . $batchid);
        return $sql;
        }
    public function invoicenumber($id=null){
		$sql=\DB::select("select po_invoice_id,bill_number from p_po_invoice_hdr_t where po_invoice_id=".$id);
		return $sql[0]->bill_number;
	}
  public function getPaid($id = null, $inv_amount = null) {
        $result = \Db::select("select SUM(payment_amount) as payment_amount from p_payment_lines_t where po_invoice_id='" . $id . "'");
        $paid = $result[0]->payment_amount;
        if ($paid != null) {
            return $paid;
        } else {
            return 0;
        }
    }
        public function getBalance($id = null, $inv_amount = null) {
        $result = \Db::select("select SUM(payment_amount) as payment_amount from p_payment_lines_t where po_invoice_id='" . $id . "'");
        $paid = $result[0]->payment_amount;
	//dd($inv_amount);
        if (!empty($result)) {
            return ($inv_amount - $paid);
        }if (empty($result)) {
            return $inv_amount;
        }
    }
   /*Karthigaa purpose:to get Invoice details*/
	public function batchinvoicedetails($id=null)
	{
		$sql=\DB::table('p_payment_batches_hdr_t as batch')->leftjoin('p_payment_batches_lines_t as batchln','batch.payment_batches_hdr_id','=','batchln.payment_batches_hdr_id')
			->select('batch.payment_batches_hdr_id','batchln.payment_batches_line_id','batchln.po_invoice_id','batchln.invoice_amount')->where('batch.payment_batches_hdr_id',$id)->get();
//                dd($sql);
		$html = '';
		foreach($sql as $key=>$val)
		{

			$inv=$this->jCombo('p_po_invoice_hdr_t','po_invoice_id','bill_number',$val->po_invoice_id);
//                        $acc_code=$this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
                       $paidamt=$this->getPaid($val->po_invoice_id);
                       $bal=$this->getBalance($val->po_invoice_id,$val->invoice_amount);
//                       $balamt=$val->invoice_amount;
			$html.='<tr class="table'.$key.'"><body>
					<td ><input type="hidden" name="bulk_payment_line_id[]" class="form-control input-sm bulk_payment_line_id" value=""></td>
					<td ><input type="text" name="bulk_line_no[]" class="input-sm bulk_line_no" value="'.($key+1).'" readonly="readonly" style="width: 68px !important;text-align: center;"></td>
					<td >

					<select name="po_invoice_id[]" class="input-sm po_invoice_id"  style="pointer-events:none;color:black;border-color:#0000007a;background-color:#00000014;">'.$inv.'</select>
					</td>
					<td >
					<input type="text" name="bulk_batch_invoice_amount[]" class="input-sm bulk_batch_invoice_amount" readonly="readonly" value="'.$val->invoice_amount.'" style="color:black;background-color:#00000014;">
					</td>
					<td >
                                        <input type="text" name="bulk_payment_amount[]"  class="input-sm bulk_payment_amount" value="" required style="color:black;">
                                        </td>
                                        <td>
                                        <input type="text" name="bulk_paid_amount[]"  class="input-sm bulk_paid_amount" readonly="readonly" value="'.$paidamt.'" style="background-color:#00000014;">
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
		//	print_r($html);
		}

		return $html;
	}
	/*end*/
    /*Karthigaa purpose for Display hdr & Lines View function*/
  public function show(request $request,$id=null)
    {
        if(isset($id))
        {
      $vdata=\DB::table('p_payments_hdr_t')->leftjoin('m_supplier_t','m_supplier_t.supplier_id','=','p_payments_hdr_t.supplier_id')
                                            ->leftjoin('m_organizations_t','m_organizations_t.organization_id','=','p_payments_hdr_t.organization_id')
                                            ->leftjoin('m_supplier_sites_t','m_supplier_sites_t.supplier_site_id','=','p_payments_hdr_t.supplier_site_id')
                                            ->leftjoin('p_payment_batches_hdr_t','p_payment_batches_hdr_t.payment_batches_hdr_id','=','p_payments_hdr_t.payment_batches_hdr_id')
                                            ->leftjoin('f_bank_account_hdr_t','f_bank_account_hdr_t.bank_account_hdr_id','=','p_payments_hdr_t.bank_id')
                                            ->leftjoin('f_bank_cheque_lines_t','f_bank_cheque_lines_t.bank_cheque_line_id','=','p_payments_hdr_t.cheque_no')
                                            ->leftjoin('f_bank_account_lines_t','f_bank_account_lines_t.bank_account_line_id','=','p_payments_hdr_t.account_no')
                                            ->where('payment_hdr_id',$id)->get();

          $this->data['payment_number']=$vdata[0]->payment_number;
          $this->data['payment_date']=$vdata[0]->payment_date;
          $this->data['payment_status']=$vdata[0]->payment_status;
          $this->data['payment_source']=$vdata[0]->payment_source;
          $this->data['advance_amount']=$vdata[0]->advance_amount;
          $this->data['batch_total_amount']=$vdata[0]->batch_total_amount;
          $this->data['remarks']=$vdata[0]->remarks;
          $this->data['payment_batch_name']=$vdata[0]->payment_batch_name;
          $this->data['account_number']=$vdata[0]->account_number;
          $this->data['payment_reference']=$vdata[0]->payment_reference;
          $this->data['payment_type_id']=$vdata[0]->payment_type_id;
          $this->data['cheque_no']=$vdata[0]->cheque_no;
          $this->data['bank_name']=$vdata[0]->bank_name;
          $this->data['supplier_name']=$vdata[0]->supplier_name;
          $this->data['supplier_site_name']=$vdata[0]->supplier_site_name;
          $this->data['organization_name']=$vdata[0]->organization_name;

         $vlinesdata = \DB::table('p_payment_lines_t')->leftjoin('p_po_invoice_hdr_t','p_po_invoice_hdr_t.po_invoice_id','=','p_payment_lines_t.po_invoice_id')
                                                      ->leftjoin('f_account_structure_t','f_account_structure_t.f_account_structure_id','=','p_payment_lines_t.account_structure_id')
                                                      ->where('p_payment_lines_t.payment_hdr_id',$id)->get();
            $this->data['vlinesdata']=$vlinesdata;
            $this->data['bill_number']=$vlinesdata[0]->bill_number;
            $this->data['concatenated_segments']=$vlinesdata[0]->concatenated_segments;

            return view('payments.view',$this->data);

        }
    }

//   public function getPaymentdetails($id=null,$inv_id=null,$invoice_amount=null,$payment_amount=null) {
//        $pay_check = \DB::select("select count(*) as count from p_payments_hdr_t where payment_hdr_id='" . $id . "'");
//        if (($pay_check[0]->count) <= 0) {
//            $result = \Db::select("select SUM(payment_amount) as payment_amount from p_payment_lines_t where po_invoice_id='" . $inv_id . "'");
//            $bal_amt = $invoice_amount - $result[0]->payment_amount;
//            if ($payment_amount > $bal_amt)
//                return 0;
//            else
//                return 1;
//        }if (($pay_check[0]->count) > 0) {
//            $pay = \Db::select("select ap_payment_hdr_id from p_payments_hdr_t where payment_hdr_id='" . $id . "'");
//            $payline = \Db::select("select payment_amount from p_payment_lines_t where payment_hdr_id='" . $pay[0]->payment_hdr_id . "' and po_invoice_id='" . $inv_id . "'");
//            $pbl1 = 0;
//            $pbl2 = 0;
//            if (!empty($payline)) {
//                $pbl = $payline[0]->payment_amount;
//            }$result = \Db::select("select SUM(payment_amount) as payment_amount from p_payment_lines_t where po_invoice_id='" . $inv_id . "'");
//            if (!empty($result)) {
//                $pbl2 = $result[0]->payment_amount;
//            }$bal_amt = $invoice_amount - ($pbl1 - $pbl2);
//            if ($payment_amount > $bal_amt)
//                return 0;
//            else
//                return 1;
//        }
//
//    }

}
