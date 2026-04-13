<?php
namespace App\Http\Controllers;
use DB;
use App\Receiptforinvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;

class ReceiptforinvoiceController extends Controller
{
       public $module="receiptforinvoice";
	public function __construct()
	{
		$this->data=array();
        $this->data=array();
		$this->table="s_receipts_t";
		$this->pageModule="receiptforinvoice";
        $this->model=new Receiptforinvoice();
		$this->model=new Receiptforinvoice;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
        $this->table="s_receipts_t";
        $this->data['urlmenu']=$this->indexs(); 
	}
      public function index(){
		$this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
      	$this->data['customeropt'] = $this->jqgridselect('m_customers_t', 'customer_id', 'customer_name');
         $table = \DB::table('s_receipts_t')->get();
   	 	$this->data['datas'] = $table; 
         return view('receiptforinvoice.table',$this->data);
    }
	public function getSalesinvoicedetailsData()
		{
          //  dd("dssd");
		$wh='';
		$wh1='';
		if($_GET['_search']=='true')
		{
                    $search_tables=array('m_customers_t');
		$wh=$this->jqgridsearchnotab('v1',$_GET['filters']);
		}
  		    $loc=\Session::get('location');
			$compy=\Session::get('companyid');
			$org=\Session::get('organization');
			$groupname=\Session::get('groupname');
				if($groupname=='Superadmin' || $groupname=='Admin'){
				$wh1.=' and s_invoice_hdr_t.company_id='.$compy;	
				}else{
					$wh1.=' and s_invoice_hdr_t.company_id = '.$compy.' and s_invoice_hdr_t.location_id='.$loc;		
				}
		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
		$result = \DB::select("select * from (SELECT
		s_invoice_hdr_t.`invoice_hdr_id`,
		s_invoice_hdr_t.`invoice_type`,
		s_invoice_hdr_t.`invoice_number`,
		s_invoice_hdr_t.`invoice_date`,
		s_invoice_hdr_t.remarks,
		s_invoice_hdr_t.invoice_status,
		s_invoice_hdr_t.savestatus,
		s_invoice_hdr_t.invoice_grand_total,
		s_invoice_hdr_t.balance_amount,
        s_invoice_hdr_t.ship_to_customer_id,
		m_customers_t.customer_name
		FROM `s_invoice_hdr_t`
               LEFT JOIN m_customers_t  on(
		m_customers_t.customer_id=s_invoice_hdr_t.`ship_to_customer_id`
		)
		where 1=1  and s_invoice_hdr_t.balance_amount!=0 and s_invoice_hdr_t.invoice_status='APPROVED' $wh1) as v1 where 1=1 $wh  ORDER BY  v1.invoice_hdr_id DESC");
		$count = COUNT($result);
		if( $count > 0 && $limit > 0)
		{
		$total_pages = ceil($count/$limit);
		} else {
		$total_pages = 0;
		}
		if ($page > $total_pages)
		$page=$total_pages;
		$start = $limit*$page - $limit;
		if($start <0) $start = 0;
		// $SQL = "SELECT
		// s_invoice_hdr_t.`invoice_hdr_id`,
		// s_invoice_hdr_t.`invoice_type`,
		// s_invoice_hdr_t.`invoice_number`,
		// s_invoice_hdr_t.`invoice_date`,
		// s_invoice_hdr_t.remarks,
		// s_invoice_hdr_t.invoice_status,
		// s_invoice_hdr_t.savestatus,
		// s_invoice_hdr_t.invoice_grand_total,
		// s_invoice_hdr_t.balance_amount,
  //       s_invoice_hdr_t.ship_to_customer_id,
		// m_customers_t.customer_name
		// FROM `s_invoice_hdr_t`
  //              LEFT JOIN m_customers_t  on(
		// m_customers_t.customer_id=s_invoice_hdr_t.`ship_to_customer_id`
		// )
		// where 1=1  and s_invoice_hdr_t.balance_amount!=0 and s_invoice_hdr_t.invoice_status='APPROVED' and s_invoice_hdr_t.company_id=$compy and s_invoice_hdr_t.location_id=$loc $wh ORDER BY  $sidx $sord LIMIT $start , $limit";
		
		// $download_SQL =  "SELECT
		// s_invoice_hdr_t.`invoice_hdr_id`,
		// s_invoice_hdr_t.`invoice_type`,
		// s_invoice_hdr_t.`invoice_number`,
		// s_invoice_hdr_t.`invoice_date`,
		// s_invoice_hdr_t.remarks,
		// s_invoice_hdr_t.invoice_status,
		// s_invoice_hdr_t.savestatus,
		// s_invoice_hdr_t.invoice_grand_total,
		// s_invoice_hdr_t.balance_amount,
  //              s_invoice_hdr_t.ship_to_customer_id,
		// m_customers_t.customer_name
		// FROM `s_invoice_hdr_t`
  //              LEFT JOIN m_customers_t  on(
		// m_customers_t.customer_id=s_invoice_hdr_t.`ship_to_customer_id`
		// )
		// where 1=1  and s_invoice_hdr_t.balance_amount!=0 and s_invoice_hdr_t.invoice_status='APPROVED' and s_invoice_hdr_t.company_id=$compy and s_invoice_hdr_t.location_id=$loc $wh ORDER BY  $sidx $sord";
		 
    if(isset($_GET['download']))
    {
    	$result1 = $result;
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
        return $result1;
    }

			
		$result= array_slice($result, $start , $limit);
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);
		}
     /*Karthigaa Purpose for Default Cheque Number*/
//    function getchequeno($accno=null){
//        $sql = \DB::SELECT("select cheque_from_no,cheque_to_no from f_bank_cheque_lines_t where bank_cheque_hdr_id = '$accno' and cheque_status='ACTIVE'");
//        $cheqno=\DB::select("SELECT MAX(cheq.cheque_count) AS cheque_no FROM f_bank_cheque_lines_t cheq LEFT JOIN s_receipts_t payhdr on (payhdr.account_no=cheq.bank_cheque_hdr_id)where payhdr.account_no='$accno'");
//        $max=$cheqno[0]->cheque_no;
//        $end=$sql[0]->cheque_to_no;
//        if($max == null){
//            $data1 = $sql[0]->cheque_from_no;
//        }
//        else{
//            $data1=$max+1;
//        }
//        $data['chequeno']=$data1;
//        $data['endcheque']=$end;
//
//         return $data;  
//    }
/*End*/
  public function create($invid=null,$soid=null,$id=null){
 
        $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
        $this->data =array('pageModule'=>'receiptforinvoice','pageUrl'=>url('receiptforinvoice'));
        $table = \DB::table('s_invoice_hdr_t')->where('invoice_hdr_id',$invid)->get();
       // dd($table);
        $customerbankdetails = \DB::table('f_bank_account_hdr_t')->select('f_bank_account_hdr_t.*','f_bank_account_lines_t.*')
                ->leftjoin('f_bank_account_lines_t','f_bank_account_lines_t.bank_account_hdr_id','=','f_bank_account_hdr_t.bank_account_hdr_id')
                ->where('f_bank_account_hdr_t.customerid',$table[0]->ship_to_customer_id)->where('f_bank_account_lines_t.active','Yes')->get();
		$this->data['row']= (object) array();
			$this->data['row']->receipt_id = "";
			$this->data['row']->receipt_number = "";
			$this->data['row']->receipt_date=date('Y-m-d');
			//dd($table[0]->invoice_currency);
			if($table[0]->invoice_currency!='' && $table[0]->invoice_currency!=0){
				$curr=\DB::table('f_account_currency_t')->select('currency_code')->where('account_currency_id',$table[0]->invoice_currency)->get();
				
               $this->data['row']->invoice_currency=$curr[0]->currency_code;
               			if($curr[0]->currency_code!="INR"){
               $data11 = \DB::table('f_account_exchangerates_t')->whereDate('from_date','<=',$table[0]->invoice_date)->whereDate('to_date','>=',$table[0]->invoice_date)->where('from_currency_id',$table[0]->invoice_currency)->where('to_currency_id',37)->where('active','Yes')->select('*')->get();
               // dd($data11);
               if(count($data11)>0){
            		$this->data['row']->conversion_rate=$data11[0]->conversion_rate; 
            			}else{
            		$data112 = \DB::table('f_account_exchangerates_t')->where('from_currency_id',$table[0]->invoice_currency)->where('to_currency_id',37)->where('active','Yes')->select('*')->OrderBY('account_exchangerate_id')->get();
            		$this->data['row']->conversion_rate=$data112[0]->conversion_rate; 
            }
            
            					$adrate = \DB::table('s_receipts_t')->where('sales_hdr_id',$table[0]->ar_sales_hdr_id)->select('exchangerate')->get();
            					//dd($adrate);
            					if(count($adrate)>0){
            						
            						$this->data['row']->exchangerate=$adrate[0]->exchangerate; 
            					}else{

            						$this->data['row']->exchangerate=""; 
            					}
            					
            					
            					}
            					else{
            						$this->data['row']->exchangerate=''; 
            						$this->data['row']->conversion_rate=1; 						
            					}
			}else{
			$this->data['row']->invoice_currency='INR';	
			$this->data['row']->conversion_rate=1;
			}
			
			$payamt=$this->data['row']->receipt_status="";
			$this->data['row']->receipt_amount="";
                        if(count($customerbankdetails)>0){
                        $this->data['row']->bank_name=$customerbankdetails[0]->bank_name;
                        $this->data['row']->account_number=$customerbankdetails[0]->account_number;
                        $this->data['row']->ifsc_code=$customerbankdetails[0]->ifsc_code;
                        $this->data['row']->customer_account_name=$customerbankdetails[0]->name_in_account;
                        }else{
                            $this->data['row']->supplier_bank_id="";
                        $this->data['row']->supplier_account_no="";
                        $this->data['row']->supplier_ifsc_code="";
                        $this->data['row']->supplier_account_name="";
                        }
                          //Advance
           $result = \DB::select("select advance_amount from s_advance_receipts_t where sales_hdr_id in ($soid)");
          // dd($result);
                       if($result==null){
			$this->data['row']->advance_amount=0;
			}
			else{
                            $advanceamount = $result[0]->advance_amount;
				$this->data['row']->advance_amount=round($advanceamount,2);	
			}
			$this->data['row']->debit_amount=0;
			$this->data['row']->credit_amount=0;
			//Invoice Amount
			$this->data['row']->invoice_hdr_id = $invid;
			$invamount= \DB::select("select sum(invoice_grand_total) as inv_amt,sum(balance_amount) as balance_amt,sum(debit_note) as debit_note,sum(credit_note) as credit_note,SUM(paid_amount) as paid_amount from s_invoice_hdr_t where invoice_hdr_id in($invid)");

						//dd($this->data['row']->conversion_rate);
			$inv_amount=$this->data['row']->invoice_amount=round((($invamount[0]->inv_amt + $invamount[0]->debit_note - $invamount[0]->credit_note))-$invamount[0]->paid_amount,2);	
			if($this->data['row']->invoice_currency!='INR'){
			$this->data['row']->conversion_amt=round($inv_amount*$this->data['row']->conversion_rate,2);
			}else{
				$this->data['row']->conversion_amt='';
			}
			//dd($this->data['row']->conversion_amt);
			//paid
			$result = \DB::select("select SUM(paid_amount) as paid_amount from s_invoice_hdr_t where invoice_hdr_id in ($invid)");
			$paidamount = $result[0]->paid_amount;
			if($paidamount==null){
			$this->data['row']->paid_amount=0;
			}
			else{
				$this->data['row']->paid_amount=round($paidamount,2);	
			}
			//balance
			//$balamt= \DB::select("select sum(balance_amount) as balance_amt from s_invoice_hdr_t where invoice_hdr_id in($invid)");
			$balance=$this->data['row']->balance_amount=round($invamount[0]->balance_amt,2);	
			$this->data['row']->receipt_type_id="";
			$this->data['row']->receipt_reference="";
			$this->data['row']->cheque_no="";
			$this->data['row']->remarks="";
			
//			$this->data['account_no'] = $this->jCombo('f_bank_account_lines_t','bank_account_line_id','account_number','');
			$this->data['customer_id'] = $this->jCombo('m_customers_t','customer_id','customer_name',$table[0]->ship_to_customer_id);
			$this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t','bank_account_hdr_id','bank_name','',"and bank_source='Company Account'");
			$this->data['bank_id'] = $this->jcustomselect('f_bank_account_hdr_t','bank_account_hdr_id','bank_name','',"and bank_source='Company Account'");
			$this->data['account_code_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
		
		$this->data['invoice_balamt']=$sql=\DB::select("select invoice_number,balance_amount,invoice_hdr_id,debit_note,credit_note from s_invoice_hdr_t where invoice_hdr_id in ($invid)");
			$invno=""; 
				foreach($sql as $key=>$value)
				{
				  $invno.=$value->invoice_number.",";
                                }   
	  
				$invoiceno=rtrim($invno,',');
				$this->data['invoice_number']=$invoiceno;
                                $this->data['row']->receipt_reference=$invoiceno;
                                $this->data['statement']=0;
	 $this->data['invoice_credit']= $invoicecredit=\DB::select("select invoice_number,balance_amount,invoice_hdr_id,credit_note,credit_note_balance from s_invoice_hdr_t where ship_to_customer_id=".$table[0]->ship_to_customer_id." and credit_note_balance!=0");
 $this->data['invoice_debit']= $invoicedebit=\DB::select("select invoice_number,balance_amount,invoice_hdr_id,debit_note,debit_note_balance from s_invoice_hdr_t where ship_to_customer_id=".$table[0]->ship_to_customer_id." and debit_note_balance!=0");
 //dd($table[0]->ship_to_customer_id);
	  /*deepika purpose:get advance amount from so*/		      
		$this->data['so_balamt']=$sql1=\DB::select("select sales_order_no,balance_amount,advance_amount,sales_hdr_id from s_salesorder_hdr_t where ship_to_customer_id=".$table[0]->ship_to_customer_id." and balance_amount!=0");
		//dd($sql1);
    $soid="";
    $soid1=0;
		if(count($sql1)<0){
	    $this->data['soamt']=1;
       }else{
	   foreach($sql1 as $k=>$v){
		$soid.=$v->sales_hdr_id.",";  
	   }
	   $soid1=rtrim($soid,",");
	   $this->data['soamt']=0;  
       }
	 $this->data['row']->sales_hdr_id=$soid1; 
	  /*end*/
	  //	dd($this->data['row']);
				return view('receiptforinvoice.form',$this->data);
			}
                  public function createsostatement($invid=null,$soid=null,$row_id=null){
                     
                        $this->data =array('pageModule'=>'receiptforinvoice','pageUrl'=>url('receiptforinvoice'));
                        $table = \DB::table('s_receipts_t')->where('receipt_id',$invid)->select('s_receipts_t.*','s_invoice_hdr_t.*')->leftjoin('s_invoice_hdr_t','s_invoice_hdr_t.invoice_hdr_id','=','s_receipts_t.invoice_hdr_id')
                        ->get();
                     //  dd($table);
                        $this->data['row']= (object) array();
			            $this->data['row']->receipt_id = "";
			            $this->data['row']->receipt_number = $table[0]->receipt_number;
						$this->data['row']->bank_name = $table[0]->bank_name;
						$this->data['row']->account_number = $table[0]->account_number;
						$this->data['row']->ifsc_code = $table[0]->ifsc_code;
						$this->data['row']->invoice_amount = $table[0]->invoice_amount;
						$this->data['row']->receipt_type_id = $table[0]->receipt_type_id;
						$this->data['row']->reference_no = $table[0]->reference_no;
						$this->data['row']->paid_amount = $table[0]->paid_amount;
						$this->data['row']->cheque_date = $table[0]->cheque_date;
						$this->data['row']->receipt_date=date('Y-m-d');
						$payamt=$this->data['row']->receipt_status="";
			            
                        $statement= \DB::select("select * from f_bankstmtupload_t where bankstmt_id='$row_id'");
                       
                      $this->data['row']->receipt_amount=$statement[0]->credit;
                       //Advance
                        $result = \DB::select("select advance_amount from s_advance_receipts_t where sales_hdr_id in ($soid)");
                       if($result==null){
			$this->data['row']->advance_amount=0;
			}
			else{
                            $advanceamount = $result[0]->advance_amount;
				$this->data['row']->advance_amount=round($advanceamount,2);	
			}
                        
			//Invoice Amount
			$this->data['row']->invoice_hdr_id = $invid;
			$invamount= \DB::select("select sum(invoice_grand_total) as inv_amt from s_invoice_hdr_t where invoice_hdr_id in($invid)");
			$inv_amount=$this->data['row']->invoice_amount=$invamount[0]->inv_amt;	
			
			//paid
			$result = \DB::select("select SUM(paid_amount) as paid_amount from s_invoice_hdr_t where invoice_hdr_id in ($invid)");
			$paidamount = $result[0]->paid_amount;
			if($paidamount==null){
			$this->data['row']->paid_amount=0;
			}
			else{
				$this->data['row']->paid_amount=$paidamount;	
			}
			//balance
			$balamt= \DB::select("select sum(balance_amount) as balance_amt from s_invoice_hdr_t where invoice_hdr_id in($invid)");
			$balance=$this->data['row']->balance_amount=$balamt[0]->balance_amt;
			$this->data['row']->cod=$statement[0]->cod;
			$this->data['row']->narration=$statement[0]->narration;
			$this->data['row']->cheque_no=$statement[0]->chq_no;
			$this->data['row']->remarks="";
			
//			$this->data['account_no'] = $this->jCombo('f_bank_account_lines_t','bank_account_line_id','account_number','');
			$this->data['customer_id'] = $this->jCombo('m_customers_t','customer_id','customer_name',$table[0]->customer_id);

		//	$this->data['bank_name'] = $this->jCombo('f_bank_account_hdr_t','bank_account_hdr_id','bank_name','');
			$this->data['account_code_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$table[0]->account_code_id);
			
			$this->data['customer_account_name']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$table[0]->concatenated_segments);
			
			//$this->data['customer_account_name']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$table[0]->customer_account_name);
			
		$this->data['invoice_balamt']=$sql=\DB::select("select invoice_number,balance_amount,debit_note,credit_note,invoice_hdr_id from s_invoice_hdr_t where invoice_hdr_id in ($invid)");
			$invno=""; 
				foreach($sql as $key=>$value)
				{
				  $invno.=$value->invoice_number.",";
                                }   
	  
				$invoiceno=rtrim($invno,',');
				$this->data['invoice_number']=$invoiceno;
                                 $this->data['statement']=1;
				return view('receiptforinvoice.form',$this->data);
			}
         public function getadvance($invid=null){
            $sql=\DB::select("select s_invoice_hdr_t.bill_number,s_invoice_hdr_t.balance_amount,"
                                . " s_invoice_hdr_t.invoice_hdr_id,"
                                . " s_invoice_hdr_t.invoice_grand_total,"
                                . " p_advance_payments_t.advance_amount "
                                . "from s_invoice_hdr_t"
                                . " join s_salesorder_hdr_t "
                                  . " left join s_advance_receipts_t on (s_advance_receipts_t.sales_hdr_id=s_salesorder_hdr_t.sales_hdr_id) "
                                . " where invoice_hdr_id in ($invid)");
//            dd($sql);
            if (count($sql) > 0) {
            $advance['invoice_grand_total']=$sql[0]->invoice_grand_total;
            $advance['balance_amount']=$sql[0]->balance_amount;
            $advance['advance_amount']=$sql[0]->advance_amount;
            $advance['advance_deduction']=round($sql[0]->invoice_grand_total-$sql[0]->advance_amount,2);
        }
        return $advance;
        }
			/*Karthigaa purpose for Save function*/
         public function save(Request $request){
    //   dd($_POST);
            $id='';
			$data = $this->validatePost($request->all(),$this->table,'header');
			 /*karthigaa Purpose for Auto Number*/
               if ($_POST['receipt_number'] =="")
				{
					$seqno=$this->Seqnoe('RCPT-','s_receipts_t','','receipt_count');
					$data['receipt_number'] = $seqno[0];
                    $data['receipt_count'] = $seqno[1];
				}
				else
				{
					$seqno[0] = $_POST['receipt_number'];
				}
			 /*End*/
			 
			 
		//	 dd($_POST);
                
			\DB::beginTransaction();
                     try{
			
			$id=$this->model->insertRow($data);
						   $advance_amount =$_POST['advance_amount'];
						  if($_POST['sales_hdr_id']!=""){
			$soid=explode(",",$_POST['sales_hdr_id']);
							
                foreach($soid as $k => $v) { 
				$sohdrid=\DB::select("select  sales_hdr_id,balance_amount from s_salesorder_hdr_t where sales_hdr_id =$v");
				if(count($sohdrid)>0){
                    $balance_amount=$sohdrid[0]->balance_amount;
				}else{
					$balance_amount=0;
				}
			 if($_POST['sopaymentamt'][$v]==""){
                $socurrentbalance_amount=$balance_amount;
              }else{
                $socurrentbalance_amount=$balance_amount-$_POST['sopaymentamt'][$v];
             if($socurrentbalance_amount==0){
                    \DB::update("Update s_salesorder_hdr_t set balance_amount='$socurrentbalance_amount',advance_status='1' where sales_hdr_id='$v'");
                    }else{
                    \DB::update("Update s_salesorder_hdr_t set balance_amount='$socurrentbalance_amount' where sales_hdr_id='$v'");
                    }
			  }
				}	  }

						 
						 /*deepika purpose:update credit balance in invoice*/
						  if($_POST['credit_amount']!="" && $_POST['credit_amount']!=0){
						 foreach($_POST['creditamount'] as $ck=>$cv){
						     
						     
						       $credit_balance=\DB::select("select  debitcredit_id,balance_amount,paid_amount from f_debitcredit_t where source_type='CREDIT' and  invoice_no =$ck and balance_amount!='0'"); 
             
             if(count($credit_balance)>0)
             {
             $credit_balance_amount=$credit_balance[0]->balance_amount-$cv;
             $credit_paid_amount=$credit_balance[0]->paid_amount+$cv;
             $credit_debit_id=$credit_balance[0]->debitcredit_id;
              \DB::update("Update f_debitcredit_t set balance_amount='$credit_balance_amount',paid_amount='$credit_paid_amount' where debitcredit_id='$credit_debit_id'");
             
             }
						     
								$creditinv=\DB::select("select  invoice_hdr_id,credit_note_balance from s_invoice_hdr_t where invoice_hdr_id =$ck"); 
				     $creditbalance_amount=$creditinv[0]->credit_note_balance-$cv;
                           \DB::update("Update s_invoice_hdr_t set credit_note_balance='$creditbalance_amount' where invoice_hdr_id='$ck'");
              }
          }
						 /*end*/
						 /*deepika purpose:update credit balance in invoice*/
						 if($_POST['debit_amount']!="" && $_POST['debit_amount']!=0){
						 foreach($_POST['debitamount'] as $ck=>$cv){
						     
						     
						     
						     
						       $credit_balance=\DB::select("select  debitcredit_id,balance_amount,paid_amount from f_debitcredit_t where source_type='DEBIT' and  invoice_no =$ck and balance_amount!='0'"); 
             
             if(count($credit_balance)>0)
             {
             $credit_balance_amount=$credit_balance[0]->balance_amount-$cv;
             $credit_paid_amount=$credit_balance[0]->paid_amount+$cv;
             $credit_debit_id=$credit_balance[0]->debitcredit_id;
              \DB::update("Update f_debitcredit_t set balance_amount='$credit_balance_amount',paid_amount='$credit_paid_amount' where debitcredit_id='$credit_debit_id'");
             
             }
						     
						     
						     
								$creditinv=\DB::select("select  invoice_hdr_id,debit_note_balance from s_invoice_hdr_t where invoice_hdr_id =$ck"); 
				     $debitbalance_amount=$creditinv[0]->debit_note_balance-$cv;
                           \DB::update("Update s_invoice_hdr_t set debit_note_balance='$debitbalance_amount' where invoice_hdr_id='$ck'");
              }
          }
						 /*end*/
			            $invoiceid=$_POST['invoice_hdr_id'];
                        $invhdrid=\DB::select("select  invoice_hdr_id,balance_amount,invoice_grand_total,paid_amount from s_invoice_hdr_t where invoice_hdr_id in ($invoiceid)");
                     //dd($_POST['debit_amount']);
        $total_amount=$_POST['advance_amount']+$_POST['credit_amount'];

        foreach($invhdrid as $tkey => $tvalue) { 
                $balance_amount=$tvalue->balance_amount;
                $invoice_grand_total=$tvalue->invoice_grand_total;
                $paid_amount=$tvalue->paid_amount;
                $invid=$tvalue->invoice_hdr_id;
                $current_balance_amount=$balance_amount-$total_amount-$_POST['rcptamt'][$tkey];
                   if($current_balance_amount<=0)
                            {
                            $bal=0;
                            $balance_temp=$balance_amount-$_POST['rcptamt'][$tkey];
                            //$receipt_amount=$receipt_amount-$balance_amount;
                            $total_amount=$total_amount-$balance_temp;
                	        $paid_amount=$invoice_grand_total;
                          \DB::update("Update s_invoice_hdr_t set paid_amount='$paid_amount',balance_amount='$bal',receipt_status='1' where invoice_hdr_id='$invid'");
                            }
                            else{
                             $bal=$balance_amount-$_POST['rcptamt'][$tkey]-$total_amount;
                             $paid_amount=$paid_amount+$total_amount+$_POST['rcptamt'][$tkey];
                             \DB::update("Update s_invoice_hdr_t set paid_amount='$paid_amount',balance_amount='$bal' where invoice_hdr_id='$invid'");
                                        break;
                                }
                        }
			
                        //show current balance
                        $old_balance=$_POST['balance_amount'];
                        if($_POST['receipt_amount']!=''){
                       	$receipt =$_POST['receipt_amount'];
                       }else{
                       	$receipt=0;
                       }
                       // $receipt=$_POST['receipt_amount'];
                        $current_balance=$old_balance-$receipt;	
                        \DB::update("UPDATE s_receipts_t set balance_amount='$current_balance' where receipt_id='$id'");
                        
                        /*ajith purpose:update credit balance in invoice*/
						 	$invoice_currency=$_POST['invoice_currency'];
						 							 	
						 	if($invoice_currency!='INR'){
						 		$conversion_rate=$_POST['conversion_rate'];
						 		$exchangerate=$_POST['exchangerate'];
						 		$gainloss=$_POST['gainloss'];
						 	}else{
						 		$conversion_rate=1;
						 		$exchangerate=1;
						 		$gainloss=0;
						 	}


                        
                        /**Karthigaa Purpose for Journal  Insert**/
				   $receiptno="RECEIPTS-".$data['receipt_number'];
                   $paydate=$_POST['receipt_date'];
                   $org=\Session::get('organization');
                   $loc=\Session::get('location');
                   $compy=\Session::get('companyid');
                   //Journal Header Insert
                   $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$receiptno','RECEIPT','$paydate','$id','APPROVED','$compy','$loc','$org')");
                   $jid = DB::getPdo()->lastInsertId();

                 $customer_acc= \DB::table('m_customers_t')->where('customer_id',$_POST['customer_id'])->get();
                  $customer_acc=$customer_acc[0]->account_structure_id;
                   
                     $tkey=0;
                     if($_POST['receipt_amount']!=''){
                       	$receipt1 =$_POST['receipt_amount'];
                       }else{
                       	$receipt1=0;
                       }
                    
            $journal_lines_data[$tkey]['journal_entry_id']=$jid;
            $journal_lines_data[$tkey]['journal_date']=$_POST['receipt_date'];
            $journal_lines_data[$tkey]['reference_source']="";
            $journal_lines_data[$tkey]['reference_id']='';
            $journal_lines_data[$tkey]['account_id']=$_POST['account_code_id'];
            $journal_lines_data[$tkey]['debit_amount']=$receipt1*$exchangerate;
            $journal_lines_data[$tkey]['credit_amount']='';
            $journal_lines_data[$tkey]['line_no']=$tkey+1;
            $journal_lines_data[$tkey]['created_by']=\Session::get('id');
            $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
            $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
            $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
            $journal_lines_data[$tkey]['company_id']=\Session::get('location');   
             $tkey++;
              $journal_lines_data[$tkey]['journal_entry_id']=$jid;
            $journal_lines_data[$tkey]['journal_date']=$_POST['receipt_date'];
            $journal_lines_data[$tkey]['reference_source']="CUSTOMER";
            $journal_lines_data[$tkey]['reference_id']=$_POST['customer_id'];
            $journal_lines_data[$tkey]['account_id']=$customer_acc;
            $journal_lines_data[$tkey]['debit_amount']='';
            $journal_lines_data[$tkey]['credit_amount']=$receipt1*$conversion_rate;
            $journal_lines_data[$tkey]['line_no']=$tkey+1;
            $journal_lines_data[$tkey]['created_by']=\Session::get('id');
            $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
            $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
            $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
            $journal_lines_data[$tkey]['company_id']=\Session::get('location');                           
            
            if($gainloss!=0 && $invoice_currency!='INR'){
            	$tkey++;
            if($gainloss>0){
            $journal_lines_data[$tkey]['journal_entry_id']=$jid;
            $journal_lines_data[$tkey]['journal_date']=$_POST['receipt_date'];
            $journal_lines_data[$tkey]['reference_source']='';
            $journal_lines_data[$tkey]['reference_id']='';
            $journal_lines_data[$tkey]['account_id']="608";
            $journal_lines_data[$tkey]['debit_amount']='';
            $journal_lines_data[$tkey]['credit_amount']=$gainloss;
            $journal_lines_data[$tkey]['line_no']=$tkey+1;
            $journal_lines_data[$tkey]['created_by']=\Session::get('id');
            $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
            $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
            $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
            $journal_lines_data[$tkey]['company_id']=\Session::get('location');

            if($_POST['advance_amount']!=0){
            	$tkey++;
             $journal_lines_data[$tkey]['journal_entry_id']=$jid;
            $journal_lines_data[$tkey]['journal_date']=$_POST['receipt_date'];
            $journal_lines_data[$tkey]['reference_source']="CUSTOMER";
            $journal_lines_data[$tkey]['reference_id']=$_POST['customer_id'];
            $journal_lines_data[$tkey]['account_id']=$customer_acc;
            $journal_lines_data[$tkey]['debit_amount']=$gainloss;
            $journal_lines_data[$tkey]['credit_amount']="";
            $journal_lines_data[$tkey]['line_no']=$tkey+1;
            $journal_lines_data[$tkey]['created_by']=\Session::get('id');
            $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
            $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
            $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
            $journal_lines_data[$tkey]['company_id']=\Session::get('location');   
        }
                 }
              else{
            $journal_lines_data[$tkey]['journal_entry_id']=$jid;
            $journal_lines_data[$tkey]['journal_date']=$_POST['receipt_date'];
            $journal_lines_data[$tkey]['reference_source']='';
            $journal_lines_data[$tkey]['reference_id']='';
            $journal_lines_data[$tkey]['account_id']="108";
            $journal_lines_data[$tkey]['debit_amount']=$gainloss*-1;
            $journal_lines_data[$tkey]['credit_amount']='';
            $journal_lines_data[$tkey]['line_no']=$tkey+1;
            $journal_lines_data[$tkey]['created_by']=\Session::get('id');
            $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
            $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
            $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
            $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 

               if($_POST['advance_amount']!=0){
            	$tkey++;
             $journal_lines_data[$tkey]['journal_entry_id']=$jid;
            $journal_lines_data[$tkey]['journal_date']=$_POST['receipt_date'];
            $journal_lines_data[$tkey]['reference_source']="CUSTOMER";
            $journal_lines_data[$tkey]['reference_id']=$_POST['customer_id'];
            $journal_lines_data[$tkey]['account_id']=$customer_acc;
            $journal_lines_data[$tkey]['debit_amount']='';
            $journal_lines_data[$tkey]['credit_amount']=$gainloss*-1;
            $journal_lines_data[$tkey]['line_no']=$tkey+1;
            $journal_lines_data[$tkey]['created_by']=\Session::get('id');
            $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
            $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
            $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
            $journal_lines_data[$tkey]['company_id']=\Session::get('location');   
        }
                 }
             }
           \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data); 
           
           
				\DB::commit();
				return response()->json(array('status' => 'success', 'message' => 'Receipt Saved','id' => $id));
			}
			catch (\Illuminate\Database\QueryException$e){
				$message = explode('(', $e->getMessage());
				$dbCode = rtrim($message[0], ']');
				$dbCode = trim($dbCode, '[');
				//dd($dbCode);
				\DB::rollback();
				return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
			}

        }
  
}
