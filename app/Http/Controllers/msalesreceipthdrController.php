<?php
namespace App\Http\Controllers;
use App\msalesreceipthdr;
use App\msalesreceiptlines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Validator,DB;
use File;
use Config;
use App\Http\Controllers\Controller;

class msalesreceipthdrController extends Controller
{
    public $module='msalesreceipthdr';
    public function __construct()
    {
            $this->data=array();
             $this->table='s_m_receipts_t';
        $this->subtable='s_m_receipts_lines_t';$this->model=new  msalesreceipthdr ;$this->submodel=new msalesreceiptlines;$this->data=array(
                    'pageModule'=> 'msalesreceipthdr',
                    'pageUrl'   =>  url('msalesreceipthdr'),
                     'pageMethod'=>\Request::route()->getName()
                  );$this->data["urlmenu"]=$this->indexs();
               
    }
    
     /*Deepika Purpose For :Index Function to Call Table Blade*/
    public function index(Request $request){
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

               
        $this->data["pageMethod"]=\Request::route()->getName();
   return view('msalesreceipthdr.table',$this->data);
    }
    public function index1(Request $request){
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

        $this->data["pageMethod"]=\Request::route()->getName();
   return view('msalesreceipthdr.rectable',$this->data);
    }
 /* Deepika purpose for Display Data in JQgrid function */
    

 /* Deepika purpose for Display Data in JQgrid function */
        public function getmsalesreceipthdrData($id=null)
        {
               $app_id=\Session::get("id");

                $wh=" where 1=1";
              
        if($_GET["_search"]=="true")
                {
                   
                    $wh .=$this->jqgridsearchnotab("t1",$_GET["filters"]);
                }

        $page = $_GET["page"];
        $limit = $_GET["rows"];
        $sidx = $_GET["sidx"];
        $sord = $_GET["sord"];


$loc=\Session::get("location");
        $compy=\Session::get("companyid");
    $groupname=\Session::get("groupname");
    //   if($groupname=="Superadmin" || $groupname=="Admin"){
    // $wh.= " and  t1.company_id=".$compy;
    // }else{
    //   $wh.=" and  t1.company_id=".$compy. "and t1.location_id=".$loc;
    // }

    $wh.=$grid_data=$this->grid_check('t1','receipt_date');
        if(!$sidx) $sidx =1;
        $result = \DB::select("select * from (select s_m_receipts_t.company_id,s_m_receipts_t.location_id, s_m_receipts_t.receipt_id , s_m_receipts_t.receipt_number , s_m_receipts_t.receipt_date , s_m_receipts_t.invoice_amount , s_m_receipts_t.receipt_amount , ( select concatenated_segments  from  f_account_structure_t where f_account_structure_t.f_account_structure_id=s_m_receipts_t.account_code_id) as account_code_id, s_m_receipts_t.receipt_type_id , s_m_receipts_t.receipt_reference , s_m_receipts_t.remarks , s_m_receipts_t.cheque_no  from s_m_receipts_t)t1 $wh ");
        //dd($result);
        $count = count($result);
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
                $SQL = " select * from (select s_m_receipts_t.company_id,s_m_receipts_t.location_id, s_m_receipts_t.receipt_id , s_m_receipts_t.receipt_number , s_m_receipts_t.receipt_date , s_m_receipts_t.invoice_amount , s_m_receipts_t.receipt_amount , ( select concatenated_segments  from  f_account_structure_t where f_account_structure_t.f_account_structure_id=s_m_receipts_t.account_code_id) as account_code_id, s_m_receipts_t.receipt_type_id , s_m_receipts_t.receipt_reference , s_m_receipts_t.remarks , s_m_receipts_t.cheque_no  from s_m_receipts_t)t1 $wh ORDER BY  $sidx $sord LIMIT $start , $limit";
      if(isset($_GET["download"]))
    {
       $download_SQL = " select * from (select s_m_receipts_t.company_id,s_m_receipts_t.location_id, s_m_receipts_t.receipt_id , s_m_receipts_t.receipt_number , s_m_receipts_t.receipt_date , s_m_receipts_t.invoice_amount , s_m_receipts_t.receipt_amount , ( select concatenated_segments  from  f_account_structure_t where f_account_structure_t.f_account_structure_id=s_m_receipts_t.account_code_id) as account_code_id, s_m_receipts_t.receipt_type_id , s_m_receipts_t.receipt_reference , s_m_receipts_t.remarks , s_m_receipts_t.cheque_no  from s_m_receipts_t)t1 $wh ORDER BY  $sidx $sord ";
                                    $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
   
        return $result1;
    }

    $result = \DB::select( $SQL );
        $responce->rows[]="";
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        echo json_encode($responce);
    }
 /* Purpose for create and update mode*/
        public function create($id=null)
    {
          
                if($id!=" "){

       $this->modelname = new msalesreceipthdr();
			$this->data["row"]= (object)array();
			$table = $this->modelname->getTableColumns();
			foreach($table as $key=>$val)
			{
				$this->data["row"]->$val="";
			}
			$this->data['row']->receipt_date=date('Y-m-d');
			$this->data["row"]->account_code_id = $this->jCombo("f_account_structure_t","f_account_structure_id","account_name","");
			$this->data["row"]->account_no = $this->jCombo("f_bank_account_lines_t","bank_account_line_id","account_number","");
            $this->data["row"]->expense_account_code_id = $this->jCombo("f_account_structure_t","f_account_structure_id","account_name","");

			$this->data["row"]->bank_id = $this->jcustomselect('f_bank_account_hdr_t','bank_account_hdr_id','bank_name','',"and bank_source='Company Account'");
			$this->data["invoice_hdr_id"] = $this->jCombologin("s_invoice_hdr_t","invoice_hdr_id","invoice_number","");
			$idd=$id;
			$linedata=\DB::select("select * from s_invoice_hdr_t where invoice_hdr_id IN (".$idd.")");
		$this->data["linedata"]=$linedata;
			foreach($this->data["linedata"] as $key=>$value){
			      
	$this->data["linedata"][$key]->receipt_id ='';
	$this->data["linedata"][$key]->expense_amount='0';
	$this->data["linedata"][$key]->invoice_amount=$value->invoice_grand_total;
	$this->data["linedata"][$key]->balance_amount=$value->balance_amount;
	$this->data["linedata"][$key]->receipt_amount ='0';
	$this->data["linedata"][$key]->receipt_line_id='';
	$this->data["linedata"][$key]->invoice_hdr_id = $this->jCombologin("s_invoice_hdr_t","invoice_hdr_id","invoice_number",$value->invoice_hdr_id);    
	$this->data["linedata"][$key]->customer_id = $this->jCombologin("m_customers_t","customer_id","customer_number|customer_name",$value->ship_to_customer_id);    
			
			}
                    
                } else {

                $this->data["id"] = $id;
        $table = \DB::table('s_m_receipts_t')->where('receipt_id',$id)->get();
        $this->data["pageMethod"]=\Request::route()->getName();
        $this->data["row"] = $table[0];$this->data["row"]->account_code_id = $this->jCombo("f_account_structure_t","f_account_structure_id","account_name",$table[0]->account_code_id);
        $this->data["row"]->expense_account_code_id = $this->jCombo("f_account_structure_t","f_account_structure_id","account_name",$table[0]->expense_account_code_id);
        $this->data["row"]->account_no = $this->jCombo("f_bank_account_lines_t","bank_account_line_id","account_number",$table[0]->account_no);
        $this->data["row"]->bank_id = $this->jCombo("f_bank_account_hdr_t","bank_account_hdr_id","bank_name",$table[0]->bank_id);
        $tablelines = \DB::table('s_m_receipts_lines_t')->where('receipt_id',$id)->get();
        $this->data["linedata"] = $tablelines;
             
              if(count($this->data["linedata"]) >= 1){
            foreach ($this->data["linedata"] as $key => $value){
                $this->data["linedata"][$key]->invoice_hdr_id = $this->jCombo("s_invoice_hdr_t","invoice_hdr_id","invoice_number",$value->invoice_hdr_id);

            }  
              } 
        }
			
      return view('msalesreceipthdr.form',$this->data);
       
		}   
	
	
         public function save(Request $request)
        {
            
   
            
                 $id="";
            $data = $this->validatePost($request->all(),$this->table,"header");
            if ($_POST['receipt_number'] =="")
				{
					$seqno=$this->Seqnoe('MRCPT-','s_m_receipts_t','','receipt_count');
					$data['receipt_number'] = $seqno[0];
                    $data['receipt_count'] = $seqno[1];
				}
				else
				{
					$seqno[0] = $_POST['receipt_number'];
				}
            $lines_data = $this->validatePost($request->all(),$this->subtable,"lines");
            \DB::beginTransaction();
            unset($data["removed_line_id"]);
                    try
                    {
                        
                    $id=$this->model->insertRow($data);
                    $lid="";  
                    $lid=$this->submodel->subgridSave($lines_data,$id);
                    $total=0;
                    foreach($_POST['bulk_invoice_hdr_id'] as $tkey => $tvalue) {  
        $balance_amount=$_POST['bulk_balance_amount'][$tkey];
        $paid_amount=$_POST['bulk_receipt_amount'][$tkey]+$_POST['bulk_expense_amount'][$tkey];
        $total=$total+$_POST['bulk_receipt_amount'][$tkey];
        
        $invoice_grand_total=$_POST['bulk_invoice_amount'][$tkey];
        $expense_amount=$_POST['bulk_expense_amount'][$tkey];
        $inid=$tvalue;
       // dd($paid_amount);
          \DB::update("Update s_invoice_hdr_t set paid_amount='$paid_amount',balance_amount='$balance_amount',receipt_status='1' where invoice_hdr_id='$inid'");
                        
                    }
                    
                     $receiptno="RECEIPTS-".$data['receipt_number'];
                   $paydate=$_POST['receipt_date'];
                   $org=\Session::get('organization');
                   $loc=\Session::get('location');
                   $compy=\Session::get('companyid');
                   //Journal Header Insert
                   $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$receiptno','RECEIPT','$paydate','$id','APPROVED','$compy','$loc','$org')");
                   $jid = DB::getPdo()->lastInsertId();
                    
          $tkey=0;
                  
            $journal_lines_data[$tkey]['journal_entry_id']=$jid;
            $journal_lines_data[$tkey]['journal_date']=$_POST['receipt_date'];
            $journal_lines_data[$tkey]['reference_source']="";
            $journal_lines_data[$tkey]['reference_id']='';
            $journal_lines_data[$tkey]['account_id']=$_POST['account_code_id'];
            $journal_lines_data[$tkey]['debit_amount']=$total;
            $journal_lines_data[$tkey]['credit_amount']='';
            $journal_lines_data[$tkey]['line_no']=$tkey+1;
            $journal_lines_data[$tkey]['created_by']=\Session::get('id');
            $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
            $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
            $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
            $journal_lines_data[$tkey]['company_id']=\Session::get('location');   
                foreach($_POST['bulk_invoice_hdr_id'] as $key => $tvalue) {
                
                
                $cus_id=$_POST['bulk_customer_id'][$key];
                
                 $customer_acc= \DB::table('m_customers_t')->where('customer_id',$cus_id)->get();
                  $customer_acc=$customer_acc[0]->account_structure_id;
                    $tkey++;
              $journal_lines_data[$tkey]['journal_entry_id']=$jid;
            $journal_lines_data[$tkey]['journal_date']=$_POST['receipt_date'];
            $journal_lines_data[$tkey]['reference_source']="CUSTOMER";
            $journal_lines_data[$tkey]['reference_id']=$cus_id;
            $journal_lines_data[$tkey]['account_id']=$customer_acc;
            $journal_lines_data[$tkey]['debit_amount']='';
            $journal_lines_data[$tkey]['credit_amount']=$_POST['bulk_receipt_amount'][$key]+$_POST['bulk_expense_amount'][$key];
            $journal_lines_data[$tkey]['line_no']=$tkey+1;
            $journal_lines_data[$tkey]['created_by']=\Session::get('id');
            $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
            $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
            $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
            $journal_lines_data[$tkey]['company_id']=\Session::get('location');

if($_POST['bulk_expense_amount'][$key]!=0 && $_POST['bulk_expense_amount'][$key]!=''){
            $tkey++;  
            $journal_lines_data[$tkey]['journal_entry_id']=$jid;
            $journal_lines_data[$tkey]['journal_date']=$_POST['receipt_date'];
            $journal_lines_data[$tkey]['reference_source']="";
            $journal_lines_data[$tkey]['reference_id']='';
            $journal_lines_data[$tkey]['account_id']=$_POST['expense_account_code_id'];
            $journal_lines_data[$tkey]['debit_amount']=$_POST['bulk_expense_amount'][$key];
            $journal_lines_data[$tkey]['credit_amount']='';
            $journal_lines_data[$tkey]['line_no']=$tkey+1;
            $journal_lines_data[$tkey]['created_by']=\Session::get('id');
            $journal_lines_data[$tkey]['created_at']=date('Y-m-d H:i:s');
            $journal_lines_data[$tkey]['last_updated_by']=\Session::get('id');
            $journal_lines_data[$tkey]['updated_at']=date('Y-m-d H:i:s');;
            $journal_lines_data[$tkey]['location_id']=\Session::get('companyid');
            $journal_lines_data[$tkey]['company_id']=\Session::get('location'); 

            }




          }
                    
                     \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data); 
           
                    \DB::commit();
                    
                return response()->json(array("status" => "success", "message" => "msalesreceipt Saved","id" => $id,"lid" => $lid));
            }
            catch (\Illuminate\Database\QueryException$e)
            {
                $message = explode("(", $e->getMessage());
                $dbCode = rtrim($message[0], "]");
                $dbCode = trim($dbCode, "[");
               dd($dbCode);
                \DB::rollback();
                return response()->json(array("status" => "error", "message" => "DatabaseError:=>" . $dbCode . "\n"));
            }
        }    /*Deepika purpose for Display hdr & Lines View function*/
        public function show($id=null){
      
          $vdata=\DB::select("select * from (select  s_m_receipts_t.receipt_number , s_m_receipts_t.receipt_date , s_m_receipts_t.invoice_amount , s_m_receipts_t.receipt_amount , ( select concatenated_segments  from  f_account_structure_t where f_account_structure_t.f_account_structure_id=s_m_receipts_t.account_code_id) as account_code_id, s_m_receipts_t.receipt_type_id , s_m_receipts_t.receipt_reference , s_m_receipts_t.cheque_no , ( select account_number  from  f_bank_account_lines_t where f_bank_account_lines_t.bank_account_line_id=s_m_receipts_t.account_no) as account_no, ( select bank_name  from  f_bank_account_hdr_t where f_bank_account_hdr_t.bank_account_hdr_id=s_m_receipts_t.bank_id) as bank_id, s_m_receipts_t.reference_no , s_m_receipts_t.bank_date  from s_m_receipts_t where s_m_receipts_t.receipt_id= $id)t1");
$this->data["data"]=$vdata;$vlinesdata = \DB::select("select * from (select  s_m_receipts_lines_t.line_no , ( select invoice_number  from  s_invoice_hdr_t where s_invoice_hdr_t.invoice_hdr_id=s_m_receipts_lines_t.invoice_hdr_id) as invoice_hdr_id, s_m_receipts_lines_t.receipt_amount , s_m_receipts_lines_t.balance_amount , s_m_receipts_lines_t.invoice_amount , s_m_receipts_lines_t.expense_amount  from s_m_receipts_lines_t where s_m_receipts_lines_t.receipt_id= $id)t1");    $this->data["vlinesdata"]=$vlinesdata;return view("msalesreceipthdr.view",$this->data);
    }      /*Deepika purpose for delete function*/
        public function delete(Request $request,$id=null){

            msalesreceipthdr::destroy($id);
            $query = \DB::table("s_m_receipts_lines_t")->where("receipt_id",$id)->delete();
                         /**Auditlog**/
                    $action = "Delete";
                    $this->auditlog($id,"msalesreceipthdr",$action,$id,"s_m_receipts_t");
            if($query)
            {
                return 0;
            }
            else
            {
                return 1;
            }
 
    }
        /*End*/ 
        }