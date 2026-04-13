<?php

namespace App\Http\Controllers;
use DB;
use App\Advancepayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;

class AdvancepaymentController extends Controller
{
    public $module="advancepayment";
	public function __construct()
	{
		$this->data=array();
                 $this->data['urlmenu']=$this->indexs(); 
		$this->table="p_payments_t";
		$this->pageModule="advancepayment";
                $this->model=new Advancepayment();
		$this->model=new Advancepayment;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
                $this->table="p_payments_t";

	}
        
      public function index(){
      	 $this->data['supplieropt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
         $table = \DB::table('p_payments_t')->get();
   	 $this->data['datas'] = $table;
         return view('advancepayment.table',$this->data);
    }

   
        
        public function getPodetailsData($id=null){
                $wh='';
                if($_GET['_search']=='true')
                {
                    $search_tables=array('m_supplier_t');
                    $wh=$this->jqgridsearch('p_po_hdr_t',$_GET['filters'],$search_tables);
		}
                $loc=\Session::get('location');
		$compy=\Session::get('companyid');
		$groupname=\Session::get('groupname');
		if($groupname=='Superadmin' || $groupname=='Admin'){
		$wh.='and p_po_hdr_t.company_id='.$compy;	
		}else{
			$wh.='and p_po_hdr_t.company_id='.$compy.' and p_po_hdr_t.location_id='.$loc;		
		}	
		
		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
$compy=\Session::get('companyid');
$result = \DB::select("SELECT COUNT(p_po_hdr_t.po_hdr_id) AS count FROM p_po_hdr_t left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.`supplier_id`) where 1=1  $wh");
    if($limit==0)
      $limit=$count;
		$count = $result[0]->count;
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
    
                $SQL = "SELECT
                        p_po_hdr_t.po_hdr_id as po_hdr_id,
                        p_po_hdr_t.po_number as po_number,
                        p_po_hdr_t.po_date as po_date,
                        p_po_hdr_t.po_type as po_type,
                        p_po_hdr_t.po_grand_total as po_grand_total,
                        p_po_hdr_t.po_status as po_status,
                        p_po_hdr_t.`supplier_id` as sup_id,
                        p_po_hdr_t.remarks as remarks,
                        p_po_hdr_t.advance_status,
                        m_supplier_t.supplier_name
                        FROM `p_po_hdr_t`
                        left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.`supplier_id`) where p_po_hdr_t.advance_status=0 and p_po_hdr_t.po_status!='DRAFT' and 1=1  $wh ORDER BY $sidx $sord LIMIT $start , $limit";

                $download_SQL = "SELECT
                        p_po_hdr_t.po_hdr_id as po_hdr_id,
                        p_po_hdr_t.po_number as po_number,
                        p_po_hdr_t.po_date as po_date,
                        p_po_hdr_t.po_type as po_type,
                        p_po_hdr_t.po_grand_total as po_grand_total,
                        p_po_hdr_t.po_status as po_status,
                        p_po_hdr_t.`supplier_id` as sup_id,
                        p_po_hdr_t.remarks as remarks,
						 p_po_hdr_t.advance_status,
                        m_supplier_t.supplier_name
                        FROM `p_po_hdr_t`
                        left join m_supplier_t on(m_supplier_t.supplier_id=p_po_hdr_t.`supplier_id`) where p_po_hdr_t.advance_status=0 and p_po_hdr_t.po_status!='DRAFT' and 1=1  $wh ORDER BY $sidx $sord";
                          $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
    
    
		$result = \DB::select($SQL);
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
               
		echo json_encode($responce);
	}
/*Karthigaa Purpose for Advance payment creation*/
    public function create($id=null){
        $this->data['pageModule']= "advancepayment";
        $this->date['pageUrl']= "advancepayment";
        $table = \DB::table('p_po_hdr_t')->whereIn('po_hdr_id',explode(",",$id))->get();
        $supplierbankdetails = \DB::table('f_bank_account_hdr_t')->select('f_bank_account_hdr_t.*','f_bank_account_lines_t.*')
                ->leftjoin('f_bank_account_lines_t','f_bank_account_lines_t.bank_account_hdr_id','=','f_bank_account_hdr_t.bank_account_hdr_id')
                ->where('f_bank_account_hdr_t.supplierid',$table[0]->supplier_id)->where('f_bank_account_lines_t.active','Yes')->get();
			$this->data['row']= (object) array();
			$this->data['row']->payment_id = "";
                        $this->data['row']->payment_number = "";
                        $this->data['row']->payment_date=date('Y-m-d');
                        $this->data['row']->payment_amount="";
                        $this->data['row']->payment_status="";
			$this->data['row']->payment_source="ADVANCE";
                        $this->data['row']->payment_type_id="";
                         $po_number=\DB::select("select po_number from p_po_hdr_t where po_hdr_id in ($id)");
                         $po_no="";
                         foreach($po_number as $k=>$v){
                             
                         $po_no.=$v->po_number.",";
                         }
                         $purchase_no=rtrim($po_no,",");
                         $this->data['row']->payment_reference=$purchase_no;
                         $this->data['row']->po_number=$purchase_no;

                         $grand_total=\DB::select("select round(sum(po_grand_total),2) as grand_total from p_po_hdr_t where po_hdr_id in ($id)");
                         $this->data['row']->po_amount=$grand_total[0]->grand_total;
			 $this->data['row']->cheque_no="";
                         $this->data['row']->remarks="";
                          if(count($supplierbankdetails)>0){
                         $this->data['row']->supplier_bank_id=$supplierbankdetails[0]->bank_name;
                        $this->data['row']->supplier_account_no=$supplierbankdetails[0]->account_number;
                        $this->data['row']->supplier_ifsc_code=$supplierbankdetails[0]->ifsc_code;
                        $this->data['row']->supplier_account_name=$supplierbankdetails[0]->name_in_account;
						$this->data['row']->favouring_name=$supplierbankdetails[0]->favouring_name;
                          }
                        $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t','bank_account_line_id','account_number','');
                        $this->data['po_hdr_id']= $this->jcustommultiselect('p_po_hdr_t','po_hdr_id','po_number',$id,'and po_hdr_id in '."(".$id.")");
			$this->data['supplier_id'] = $this->jCombo('m_supplier_t','supplier_id','supplier_name',$table[0]->supplier_id);
			$this->data['bank_id'] = $this->jCombo('f_bank_account_hdr_t','bank_account_hdr_id','bank_name','',"and bank_source='Company Account'");
                        $this->data['account_code_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
                        $this->data['tds_account_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
                        $this->data['tds_prcnt']= $this->jCombo('f_tds_slab_t','tds_slab_id','tds_percentage','');
                        
                        $this->data['po_balamt']=$sql1=\DB::select("select po_number,balance_amount,advance_amount,po_hdr_id from p_po_hdr_t where supplier_id=".$table[0]->supplier_id." and balance_amount!=0 and po_hdr_id in ($id)");

		return view('advancepayment.form',$this->data);
	}
        
        
    /*Karthigaa Purpose for Po based Supplier and amount load*/    
        function getpodetails($id=null){
            $podata=\DB::select("select po_hdr_id,supplier_id,po_grand_total from p_po_hdr_t where po_hdr_id='$id'");
             if(!empty($podata)){
            return $podata;
        }else{
            return 0;
        }
        }
       /*End*/ 
/*Karthigaa Purpose for Default Cheque Number*/
    function getpaymentchequeno($accno=null){
        
        $sql = \DB::SELECT("select cheque_from_no,cheque_to_no from f_bank_cheque_lines_t where bank_cheque_hdr_id = '$accno' and cheque_status='ACTIVE'");
       if(!empty($sql)){
        $cheqno=\DB::select("SELECT MAX(cheq.cheque_count) AS cheque_no FROM f_bank_cheque_lines_t cheq LEFT JOIN p_payments_t payhdr on (payhdr.account_no=cheq.bank_cheque_hdr_id)where payhdr.account_no='$accno'");
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
       }
       else{
           $data['nocheque']="nocheque";
       }
         return $data;  
    }
/*End*/
    
/*Karthigaa purpose for Save function*/
         public function save(Request $request){
                
                        $id='';
			$data = $this->validatePost($request->all(),$this->table,'header');
                           /*karthigaa Purpose for Auto Number*/
                if($_POST['payment_number'] =="")
				{
					$seqno=$this->Seqnoe('PMT-','p_payments_t','','payment_count');
					$data['payment_number'] = $seqno[0];
                                        $data['payment_count'] = $seqno[1];
				}
				else
				{
					$seqno[0] = $_POST['payment_number'];
				}
			 /*End*/
                          $pohdrid=implode(",",$_POST['po_hdr_id']);
                          $data['po_hdr_id']= $pohdrid;     
			\DB::beginTransaction();
                     try{
                        	$id=$this->model->insertRow($data);
                /*purpose:update Amount in po*/
                            $advanceamt=$data['payment_amount'];
                            $balamt=$data['payment_amount'];
                            $posql=\DB::select("select advance_amount,balance_amount,po_hdr_id,advance_status,po_grand_total from p_po_hdr_t where po_hdr_id in (".$data['po_hdr_id'].") and advance_status=0");
                             if(count($posql)>0){
                    foreach ($posql as $tkey => $tvalue) {
                                 $balance_amount=$tvalue->balance_amount;
                                 $po_grand_total=$tvalue->po_grand_total;
                    $advance_amount=$tvalue->advance_amount;
                    $poid=$tvalue->po_hdr_id;
                   
                    $current_balance_amount=$balance_amount-$_POST['paymentamt'][$tkey];
                    $advance_amount=$advance_amount+$_POST['paymentamt'][$tkey];
                   
                    if($current_balance_amount==0){
                            \DB::update("update p_po_hdr_t set advance_amount='$advance_amount',balance_amount='$current_balance_amount',advance_status='1' where po_hdr_id='$poid'");
                    }else{
                            \DB::update("update p_po_hdr_t set advance_amount='$advance_amount',balance_amount='$current_balance_amount' where po_hdr_id='$poid'");
                    }
                        }
                    }
            /*end*/       
         //Cheque no count update
                    $chequeno=$_POST['cheque_no'];
                    $accno=$_POST['account_no'];
				    $chequeupdate=\DB::update("UPDATE f_bank_cheque_lines_t set cheque_count='$chequeno' where bank_cheque_hdr_id='$accno'");
             if($_POST['payment_status'] == "INITIATED"){     
                 $advancepaymt="ADVANCE PAYMENT-".$data['payment_number'];
                   $advdate=$_POST['payment_date'];
				  // dd($advdate);
                   $org=\Session::get('organization');
                   $loc=\Session::get('location');
                   $compy=\Session::get('companyid');
                   //Journal Header Insert
                   $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$advancepaymt','ADVANCE PAYMENT','$advdate','$id','APPROVED','$compy','$loc','$org')");
                   $jid = DB::getPdo()->lastInsertId();
                        //Journal lINES Insert         
                   
                    $tds_applicable= $_POST['tds_applicable'];
                    $tds_account=$_POST['tds_account_id'];
                    $tds_amt=$_POST['tds_amount'];
                    $supplier_acc= \DB::table('m_supplier_t')->where('supplier_id',$_POST['supplier_id'])->get();
                    $supplier_acc=$supplier_acc[0]->account_structure_id;
                        //Journal lINES Insert       


            $tkey=0;

            $journal_lines_data[$tkey]['journal_entry_id']=$jid;
            $journal_lines_data[$tkey]['journal_date']=$_POST['payment_date'];
            $journal_lines_data[$tkey]['reference_source']="SUPPLIER";
            $journal_lines_data[$tkey]['reference_id']=$_POST['supplier_id'];
            $journal_lines_data[$tkey]['account_id']=$supplier_acc;
            
            $journal_lines_data[$tkey]['debit_amount']=$_POST['advance_amount'];
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
            $journal_lines_data[$tkey]['journal_date']=$_POST['payment_date'];
            $journal_lines_data[$tkey]['reference_source']="";
            $journal_lines_data[$tkey]['reference_id']='';
            $journal_lines_data[$tkey]['account_id']=$_POST['account_code_id'];
            
            $journal_lines_data[$tkey]['debit_amount']='';
            $journal_lines_data[$tkey]['credit_amount']=$_POST['payment_amount'];
            $journal_lines_data[$tkey]['line_no']=$tkey+1;
            $journal_lines_data[$tkey]['created_by']=\Session::get('id');
            $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
            $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
            $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
            $journal_lines_data[$tkey]['company_id']=\Session::get('location');                           
             $tkey++;
                 //TDS  Insert   
         if($tds_applicable == "YES" ){
            $journal_lines_data[$tkey]['journal_entry_id']=$jid;
            $journal_lines_data[$tkey]['journal_date']=$_POST['payment_date'];
            $journal_lines_data[$tkey]['reference_source']="";
            $journal_lines_data[$tkey]['reference_id']="";
            $journal_lines_data[$tkey]['account_id']=$tds_account;
            $journal_lines_data[$tkey]['debit_amount']='';
            $journal_lines_data[$tkey]['credit_amount']=$tds_amt;
            $journal_lines_data[$tkey]['line_no']=$tkey+1;
            $journal_lines_data[$tkey]['created_by']=\Session::get('id');
            $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
            $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
            $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
            $journal_lines_data[$tkey]['company_id']=\Session::get('location');  
                    }
                       
           \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);      
                       
                      
                     
                    
             }
				\DB::commit();
				return response()->json(array('status' => 'success', 'message' => 'Advance Payment Saved','id' => $id));
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
