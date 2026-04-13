<?php

namespace App\Http\Controllers;
use App\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use DB;

class PaymentforexpenseController extends Controller
{
     public $module="paymentforexpense";
	public function __construct()
	{
		$this->data=array();
                 $this->data['urlmenu']=$this->indexs(); 
		$this->table="f_expenses_t";
		$this->pageModule="paymentforexpense";
                $this->model=new Expenses();
		$this->model=new Expenses;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
                $this->table="f_expenses_t";
             if($this->data['pageMethod']=="expenseapproval"){
                    $this->data['status']="INITIATED";
                }
          else{
               $this->data['status']="";
          }

	}
    public function index()
    {
		$this->data['suppliername']=$this->jqgridselect('m_supplier_t','supplier_id','supplier_name'); 
        $table = \DB::table('f_expenses_t')->get();
		$this->data['datas'] = $table;
         return view('paymentforexpense.table',$this->data);
    }

     public function getExpenseData(){
                $wh='';
    if($_GET['status']!=''){
		  $wh=" and  f_expenses_t.expense_status='".$_GET['status']."'";
	  }
                $search_table=array();

                if($_GET['_search']=='true')
                {
                    $search_tables=array('tb_users');
                $wh.=$this->jqgridsearch("f_expenses_t",$_GET['filters'],$search_tables);
                }
		 
		        $org=\Session::get('organization');
				$loc=\Session::get('location');
				$compy=\Session::get('companyid');
		        $wh.='and f_expenses_t.company_id='.$compy;
		 
                $page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
              $result = \DB::select("SELECT COUNT(expense_id) AS count FROM f_expenses_t left join tb_users on (tb_users.id =f_expenses_t.created_by) where 1=1 $wh");
//                dd($result);
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

                $SQL = "SELECT f_expenses_t.expense_id,
                    f_expenses_t.expense_date,
                    f_expenses_t.expense_type,
                    f_expenses_t.expense_status,
                    f_expenses_t.remarks,
                    m_supplier_t.supplier_name as supplier_id,
                    f_expenses_t.expense_amount,
                    tb_users.username
                    FROM f_expenses_t
                    left join f_account_structure_t on(f_account_structure_t.f_account_structure_id=f_expenses_t.expense_account_id) 
                    left join m_supplier_t on(m_supplier_t.supplier_id=f_expenses_t.supplier_id)
                    left join tb_users on (tb_users.id =f_expenses_t.created_by)
                    where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
		 
		 $download_SQL =  "SELECT f_expenses_t.expense_id,
                    f_expenses_t.expense_date,
                    f_expenses_t.expense_type,
                    f_expenses_t.expense_status,
                    f_expenses_t.remarks,
                    m_supplier_t.supplier_name as supplier_id,
                    f_expenses_t.expense_amount,
                    tb_users.username
                    FROM f_expenses_t
                    left join f_account_structure_t on(f_account_structure_t.f_account_structure_id=f_expenses_t.expense_account_id) 
                    left join m_supplier_t on(m_supplier_t.supplier_id=f_expenses_t.supplier_id)
                    left join tb_users on (tb_users.id =f_expenses_t.created_by)
                    where 1=1 $wh ORDER BY $sidx $sord";
		 
		 $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
		 
		$result = \DB::select( $SQL );
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);
	}
     public function create($id=null,$aprv=null){

        //$this->data =array('pageModule'=>'expenses','pageUrl'=>url('expenses'));
        $this->data['pageModule'] ='expenses';
$this->data['pageUrl'] =url('expenses');

        if($id == null )
		{
		    
		    
			$this->data['row']= (object) array();
			$this->data['row']->expense_id = "";
                        $this->data['row']->expense_date = date('Y-m-d');
                        $this->data['row']->expense_type="";
                        $this->data['row']->expense_amount="";
                        $this->data['row']->expense_status="INITIATED";
                        $this->data['row']->gst_code_id="";
                        $this->data['row']->concatenated_segments="";
                        $this->data['supplier_id'] = $this->jCombo('m_supplier_t','supplier_id','supplier_name','');
                        //$this->data['expense_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
                        $this->data['expense_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','account_name','');
                        $this->data['row']->reverse_charge="";
                        $this->data['gst_treatment']=$this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','','AND lookup_type="GST_TREATMENT"');
                        //$this->data['gst_code_id']=$this->jCombo('f_gst_code_hdr_t','gst_code_hdr_id','classification_code','','and classification_name="HSN"');
                        $this->data['gst_code_id']="";
                        $this->data['tax_group_id']=$this->jCombo('m_tax_group_t','tax_group_id','tax_group_name','');
                        $this->data['row']->gst_no="";
                        $this->data['row']->invoice="";
                        $this->data['row']->remarks="";
                        $this->data['customer_id']=$this->jCombo('m_customers_t','customer_id','customer_name','');
                        
                        
                }
		else {

			$table = \DB::table('f_expenses_t')->where('expense_id',$id)->get();
//                        dd($table);
                        $this->data['supplier_id'] = $this->jCombo('m_supplier_t','supplier_id','supplier_name',$table[0]->supplier_id);
                    //    $this->data['expense_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$table[0]->expense_account_id);
                    //     $this->data['paid_through_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$table[0]->paid_through_id);
                    $this->data['expense_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','account_name',$table[0]->expense_account_id);
                         $this->data['paid_through_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','account_name',$table[0]->paid_through_id);
                         $this->data['gst_treatment']=$this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code',$table[0]->gst_treatment,'AND lookup_type="GST_TREATMENT"');
                         //dd($this->data['gst_treatment']);
                       // $this->data['gst_code_id']=$this->jCombo('f_gst_code_hdr_t','gst_code_hdr_id','classification_code',$table[0]->hsn_code_id,'and classification_name="HSN"');
                        $this->data['tax_group_id']=$this->jCombo('m_tax_group_t','tax_group_id','tax_group_name',$table[0]->tax_group_id);
                        $this->data['customer_id']=$this->jCombo('m_customers_t','customer_id','customer_name',$table[0]->customer_id);
			$this->data['row']= $table[0];
                        
		}
                 if($aprv!="")
                        { 
                           $this->data['aprvidenty']=$aprv;
                        }else
                        {
                         $this->data['aprvidenty']="";
                        }
		return view('expenses.form',$this->data);
	}
 public function expensespaycreate($id=null){

        $this->data =array('pageModule'=>'expenses','pageUrl'=>url('expenses'));
 

        $table = \DB::table('f_expenses_t')->where('expense_id',$id)->get();
//        dd($table);
			$this->data['row']= (object) array();
			$this->data['row']->expense_id = "";
                        $this->data['row']->expense_date = date('Y-m-d');
                        $this->data['row']->expense_type=$table[0]->expense_type;
                        $this->data['row']->expense_amount=$table[0]->expense_amount;;
                        $this->data['row']->expense_status="INITIATED";
                        $this->data['row']->gst_code_id=$table[0]->gst_code_id;
                        $this->data['supplier_id'] = $this->jCombo('m_supplier_t','supplier_id','supplier_name',$table[0]->supplier_id);
                        //$this->data['expense_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$table[0]->paid_through_id);
                        //$this->data['paid_through_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
                        $this->data['expense_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','account_name',$table[0]->paid_through_id);
                        $this->data['paid_through_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','account_name','');
                        $this->data['row']->reverse_charge="";
                        $this->data['gst_treatment']=$this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code',$table[0]->gst_treatment,'AND lookup_type="GST_TREATMENT"');
                        $this->data['gst_code_id']="";
                        $this->data['tax_group_id']=$this->jCombo('m_tax_group_t','tax_group_id','tax_group_name',$table[0]->tax_group_id);
                        $this->data['row']->gst_no=$table[0]->gst_no;
                        $this->data['row']->invoice=$table[0]->invoice;
                        $this->data['row']->pay_amount="";
                        $this->data['row']->remarks=$table[0]->remarks;
                        $this->data['customer_id']=$this->jCombo('m_customers_t','customer_id','customer_name',$table[0]->customer_id);
                      
		return view('expenses.expensepayform',$this->data);
	}
    
   /*Karthigaa purpose for Save function*/
         public function save(Request $request){
//dd($_POST);
                        $id='';
			$data = $this->validatePost($request->all(),$this->table,'header');
                       
			\DB::beginTransaction();
                       
                     try{
                        	$id=$this->model->insertRow($data);
//                                 dd($id);
                            if(isset($_POST['reverse_charge']))
                            {
                                $reverse_charge=$_POST['reverse_charge'];
                                $charge=implode(",",$reverse_charge);
                                \DB::update("update f_expenses_t set reverse_charge='".$charge."' where expense_id='".$id."'");
                               
                            } 
                         
                            /*Karthigaa Purpose For Journal Entry Insert*/
if($_POST['expense_status']=="APPROVED"){
                   $expensedate=$_POST['expense_date'];
                   $org=\Session::get('organization');
                   $loc=\Session::get('location');
                   $compy=\Session::get('companyid');
                   //Journal Header Insert
                   $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('EXPENSES','EXPENSES','$expensedate','$id','APPROVED','$compy','$loc','$org')");
                   $jid = DB::getPdo()->lastInsertId();
                   //Journal Lines Insert
                    $expenseacc=$_POST['expense_account_id'];
                     $paidthrough=$_POST['paid_through_id'];
                      $expenseamt=$_POST['expense_amount'];
                      $journal_date=date('Y-m-d');
                    $journallines= \DB::insert("insert into f_journal_entry_lines_t(journal_entry_id,journal_date,account_id,debit_amount,credit_amount,company_id,location_id,organization_id)values('$jid','$expensedate','$expenseacc','$expenseamt','','$compy','$loc','$org')");
                     $journallines= \DB::insert("insert into f_journal_entry_lines_t(journal_entry_id,journal_date,account_id,debit_amount,credit_amount,company_id,location_id,organization_id)values('$jid','$expensedate','$paidthrough','','$expenseamt','$compy','$loc','$org')");

}
                   
          /*End*/
				\DB::commit();
				return response()->json(array('status' => 'success', 'message' => 'Expenses Saved Successfully','id' => $id));
			}
			catch (\Illuminate\Database\QueryException$e){
				$message = explode('(', $e->getMessage());
				$dbCode = rtrim($message[0], ']');
                                 //dd($dbCode);
				$dbCode = trim($dbCode, '[');
                               
				\DB::rollback();
				return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
			}

        }
   
  /*Karthigaa purpose for Display View function*/
  public function show(request $request,$id=null){
        if(isset($id)){
          $vdata=\DB::table('f_expenses_t')->select('f_expenses_t.*','m_supplier_t.supplier_id','tb_users.username',
                'm_supplier_t.supplier_name','expense.concatenated_segments as expense_account','paidthrough.concatenated_segments as paidthrough','m_tax_group_t.tax_group_id','m_tax_group_t.tax_group_name','m_customers_t.customer_name')
		->leftjoin('m_supplier_t','m_supplier_t.supplier_id','=','f_expenses_t.supplier_id')
                ->leftjoin('m_customers_t','m_customers_t.customer_id','=','f_expenses_t.customer_id')
		->leftjoin('f_account_structure_t as expense','expense.f_account_structure_id','=','f_expenses_t.expense_account_id')
		->leftjoin('f_account_structure_t as paidthrough','paidthrough.f_account_structure_id','=','f_expenses_t.paid_through_id')
		->leftjoin('m_tax_group_t','m_tax_group_t.tax_group_id','=','f_expenses_t.tax_group_id')
                  ->leftjoin('tb_users','tb_users.id','=','f_expenses_t.created_by')
                ->where('expense_id',$id)->get();
//dd($vdata);
          $this->data['expense_date']=$vdata[0]->expense_date;
          $this->data['username']=$vdata[0]->username;
          $this->data['expense_type']=$vdata[0]->expense_type;
          $this->data['gst_code_id']=$vdata[0]->gst_code_id;
          $this->data['expense_amount']=$vdata[0]->expense_amount;
          $this->data['supplier_name']=$vdata[0]->supplier_name;
		  $this->data['tax_group_name']=$vdata[0]->tax_group_name;
		  $this->data['expense_account']=$vdata[0]->expense_account;
		  $this->data['paidthrough']=$vdata[0]->paidthrough;
		  $this->data['gst_no']=$vdata[0]->gst_no;
          $this->data['gst_treatment']=$vdata[0]->gst_treatment;
          $this->data['invoice']=$vdata[0]->invoice;
          $this->data['remarks']=$vdata[0]->remarks;
          $this->data['customer_name']=$vdata[0]->customer_name;

          return view('expenses.view',$this->data);
        }
    }
  public function expenseapproval($id=null,$aprv=null)
    {
		
	       $this->data['linedata'] = array();
                     $this->data['id'] = $id;
                     $table = \DB::table('f_expenses_t')->where('expense_id', $id)->get();
                     $this->data['row'] = $table[0];
            $this->data['supplier_id'] = $this->jCombo('m_supplier_t','supplier_id','supplier_name',$table[0]->supplier_id);
                        //$this->data['expense_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$table[0]->expense_account_id);
                        // $this->data['paid_through_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$table[0]->paid_through_id);
                         $this->data['expense_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','account_name',$table[0]->expense_account_id);
                         $this->data['paid_through_id']= $this->jCombo('f_account_structure_t','f_account_structure_id','account_name',$table[0]->paid_through_id);
                         $this->data['gst_treatment']=$this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code',$table[0]->gst_treatment,'AND lookup_type="GST_TREATMENT"');
                        $this->data['tax_group_id']=$this->jCombo('m_tax_group_t','tax_group_id','tax_group_name',$table[0]->tax_group_id);
        if($aprv!="")
        { 
           $this->data['aprvidenty']=$aprv; 
        }else
        {
         $this->data['aprvidenty']="";
        }
        
//        	dd($this->data);	
      return view('expenses.form',$this->data);  
    }

   
}
