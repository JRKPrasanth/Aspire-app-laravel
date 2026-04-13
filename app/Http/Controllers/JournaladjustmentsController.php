<?php

namespace App\Http\Controllers;

use App\Journaladjustments;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;

class JournaladjustmentsController extends Controller
{
    
 public $module="journaladjustments";
	public function __construct()
	{
		$this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
		$this->table="f_adjustments_t";
		$this->pageModule="journaladjustments";
        $this->model=new Journaladjustments();
		$this->model=new Journaladjustments;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
       $this->table="f_adjustments_t";

	}
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

                $this->data['accountcode']=$this->jqgridselect('f_account_structure_t','f_account_structure_id','concatenated_segments'); 
                $table = \DB::table('f_adjustments_t')->get();
                $this->data['datas'] = $table;
			
                 return view('journaladjustments.table',$this->data);
        }
	
     public function getJournalAdjustmentsData(){
		 
                $wh='';
                $search_table=array();
		 
		        $org=\Session::get('organization');
				$loc=\Session::get('location');
				$compy=\Session::get('companyid');
		        $wh.='and f_adjustments_t.company_id='.$compy;

			$wh.=$grid_data=$this->grid_check('f_adjustments_t','adjustment_date');
           
                $SQL = "SELECT f_adjustments_t.adjustment_id,f_adjustments_t.adjustment_date,f_adjustments_t.adjustment_status,f_adjustments_t.account_type,f_adjustments_t.adjustment_amount,f_account_structure_t.concatenated_segments,f_adjustments_t.reason_code FROM f_adjustments_t left join f_account_structure_t on(f_account_structure_t.f_account_structure_id=f_adjustments_t.account_code_id) 
where 1=1 $wh order by f_adjustments_t.adjustment_id DESC";


				$result = \DB::select( $SQL );
				return DataTables::of($result)->make(true);
		 
	}
	
          public function create($id=null){

        $this->data =array('pageModule'=>'journaladjustments','pageUrl'=>url('journaladjustments'));

        if($id == null )
		{
			$this->data['row']= (object) array();
			$this->data['row']->adjustment_id = "";
                        $this->data['row']->adjustment_date = date('Y-m-d');
                        $this->data['row']->account_type="";
                        $this->data['row']->adjustment_status="DRAFT";
                        $this->data['row']->adjustment_amount="";
                        $this->data['row']->description="";
                        $this->data['row']->reason_code="";
                        $this->data['account_code_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
                }
		else {

			$table = \DB::table('f_adjustments_t')->where('adjustment_id',$id)->get();
                        $this->data['account_code_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$table[0]->account_code_id);
			$this->data['row']= $table[0];
		}
		$this->data['pageMethod']=\Request::route()->getName();	  
		return view('journaladjustments.form',$this->data);
			  
	}
	
                  /* purpose for Save function*/
         public function save(Request $request){
			 
			$id='';
			$form = $request->all();
			$dataupload ="";
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
			\DB::beginTransaction();
                     try{
                        	$id=$this->model->insertRow($data);
                         	\DB::commit();
				return response()->json(array('status' => 'success', 'message' => 'Adjustments Saved','id' => $id));
			}
			catch (\Illuminate\Database\QueryException$e){
				$message = explode('(', $e->getMessage());
				$dbCode = rtrim($message[0], ']');
				$dbCode = trim($dbCode, '[');
				\DB::rollback();
				return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
			}

        }
	
                  /* purpose for Display View function*/
  public function show(request $request,$id=null){
        if(isset($id)){
          $vdata=\DB::table('f_adjustments_t')
                ->select('f_adjustments_t.*','f_account_structure_t.concatenated_segments')
		->leftjoin('f_account_structure_t','f_account_structure_t.f_account_structure_id','=','f_adjustments_t.account_code_id')
                ->where('adjustment_id',$id)->get();

          $this->data['adjustment_date']=$vdata[0]->adjustment_date;
          $this->data['adjustment_status']=$vdata[0]->adjustment_status;
          $this->data['account_type']=$vdata[0]->account_type;
          $this->data['adjustment_amount']=$vdata[0]->adjustment_amount;
          $this->data['description']=$vdata[0]->description;
          $this->data['reason_code']=$vdata[0]->reason_code;
          $this->data['concatenated_segments']=$vdata[0]->concatenated_segments;
          
          
          return view('journaladjustments.view',$this->data);
        }
    }
                      /*Karthigaa purpose for Display View function*/
  public function approvalview(request $request,$id=null){
        if(isset($id)){
          $vdata=\DB::table('f_adjustments_t')
                ->select('f_adjustments_t.*','f_account_structure_t.concatenated_segments')
		->leftjoin('f_account_structure_t','f_account_structure_t.f_account_structure_id','=','f_adjustments_t.account_code_id')
                ->where('adjustment_id',$id)->get();
            $this->data['adjustment_id']=$vdata[0]->adjustment_id;
          $this->data['adjustment_date']=$vdata[0]->adjustment_date;
          $this->data['account_type']=$vdata[0]->account_type;
          $this->data['adjustment_amount']=$vdata[0]->adjustment_amount;
          $this->data['description']=$vdata[0]->description;
          $this->data['reason_code']=$vdata[0]->reason_code;
          $this->data['account_code_id']=$vdata[0]->account_code_id;
          $this->data['concatenated_segments']=$vdata[0]->concatenated_segments;
          
          
          return view('journaladjustments.approvalview',$this->data);
        }
    }
     public function adjustmentsapproval($id=null,$status=null,$date=null)
    {
         
        if($status!="" ){
                        $journal=\DB::select("update f_adjustments_t set adjustment_status='APPROVED'  WHERE adjustment_id ='$id'");
                         //Journal Hdr Insert 
                        $reason="ADJUSTMENTS-".$_GET['reason'];  
                   $org=\Session::get('organization');
                   $loc=\Session::get('location');
                   $compy=\Session::get('companyid');
                    $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$reason','ADJUSTMENTS','$date','$id','POSTED','$compy','$loc','$org')");
                    $jid = DB::getPdo()->lastInsertId();
                      //Journal lINES Insert           
                       $accid=$_GET['account'];   
                      // dd($accid);
                       $amount=$_GET['amount']; 
                       $type=$_GET['account_type']; 
                    
                       if($type=='Debit'){
                       \DB::insert("insert into f_journal_entry_lines_t(journal_entry_id,account_id,debit_amount,credit_amount,company_id,location_id,organization_id)value('$jid','$accid','$amount','','$compy','$loc','$org')");         
                       }else{
                           \DB::insert("insert into f_journal_entry_lines_t(journal_entry_id,account_id,debit_amount,credit_amount,company_id,location_id,organization_id)value('$jid','$accid','','$amount','$compy','$loc','$org')");         
                       }
        		return response()->json(array('status' => 'success', 'message' => 'Adjustments Approved Successfully','id' => $id));
        	}
    }
}
