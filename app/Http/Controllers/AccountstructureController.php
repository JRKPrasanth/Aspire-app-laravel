<?php

namespace App\Http\Controllers;

use App\Accountstructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use DB;

class AccountstructureController extends Controller
{
	
    public function __construct(){
		
        $this->data=array();
        $this->model = new Accountstructure;
        $this->table = "f_account_structure_t";
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
        $this->data['urlmenu']=$this->indexs(); 
        
		
	}
	
	
	
    public function index(Request $request)
    {
	
        return view('accountstructure.table', $this->data);
    }
	

	
public function getAccountstructData(Request $request)
{
    $compy = session('companyid');

    $query = DB::table('f_account_structure_t')
        ->leftJoin('m_company_t', 'm_company_t.company_id', '=', 'f_account_structure_t.company_id')
        ->leftJoin('m_location_t', 'm_location_t.location_id', '=', 'f_account_structure_t.location_id')
        ->leftJoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'f_account_structure_t.costcenter_id')
        ->leftJoin('f_account_class_t', 'f_account_class_t.account_class_id', '=', 'f_account_structure_t.main_account_id')
        ->leftJoin('f_account_codes_lines_t', 'f_account_codes_lines_t.account_codes_line_id', '=', 'f_account_structure_t.sub_account_id')
        ->leftJoin('tb_users', 'tb_users.id', '=', 'f_account_structure_t.created_by')
        ->where('f_account_structure_t.company_id', $compy)
        ->select([
            'f_account_structure_t.f_account_structure_id',
            'f_account_structure_t.concatenated_segments',
            'm_company_t.company_code as company_code',
            'm_location_t.location_code',
            'm_department_lines_t.sub_department_name',
            'f_account_class_t.main_account_code',
            'f_account_codes_lines_t.account_code_meaning',
            'f_account_structure_t.account_description',
            'f_account_structure_t.account_name',
            'f_account_structure_t.active',
            'tb_users.first_name'
        ]);

    return DataTables::of($query)->make(true);
}



    // API for Sub Accounts dropdown (child)
    public function getSubAccounts(Request $request)
    {
        $main_account_id = $request->main_account_id;

        $sub_accounts = DB::table('f_account_codes_lines_t')
            ->where('account_class_id', $main_account_id)
            ->where('parent_class_id', 0)
            ->select('account_codes_line_id', 'account_code', 'account_code_meaning')
            ->get();

        return response()->json($sub_accounts);
    }

    // API for Sub Child Accounts dropdown (child of sub account)
    public function getSubChildAccounts(Request $request)
    {
        $parent_id = $request->parent_id;

        $child_accounts = DB::table('f_account_codes_lines_t')
            ->where('parent_class_id', $parent_id)
            ->select('account_codes_line_id', 'account_code', 'account_code_meaning')
            ->get();

        return response()->json($child_accounts);
    }

    // Helper function for Combo options (simplified jCombo replacement)
    private function jComboOptions($table, $keyField, $valueField)
    {
        $columns = explode('|', $valueField);

        $results = DB::table($table)->where('active', 'Yes')->get();

        $html = '<option value="">-- Please Select --</option>';

        foreach ($results as $row) {
            $text = '';
            foreach ($columns as $col) {
                $text .= $row->$col . ' ';
            }
            $text = trim($text);
            $selected = '';

            $html .= '<option value="' . $row->$keyField . '" ' . $selected . '>' . $text . '</option>';
        }

        return $html;
    }
	
	
   public function create($id=null){

       
$this->data['pageModule'] ='accountstructure';
$this->data['pageUrl'] =url('accountstructure');
        if($id == null )
		{
			$this->data['row']= (object) array();
			$this->data['row']->f_account_structure_id = "";
                        
                        $loc=\Session::get('location');
                        $compy=\Session::get('companyid');
                        $dept_id=\Session::get('dept_id');
                        $this->data['company_id'] = $this->jCombo('m_company_t','company_id','company_code|company_name',$compy);
                        $this->data['location_id'] = $this->jCombo('m_location_t','location_id','location_code|location_name',$loc);
                        $this->data['costcenter_id'] = $this->jcustomselect('m_department_lines_t','department_line_id','sub_department_code|sub_department_name','',' and parent_class_id="0"');
                        $this->data['subcostcenter1_id']= "";
                        $this->data['subcostcenter2_id']= "";
                        $this->data['subcostcenter3_id']= "";
                        $this->data['main_account_id'] = $this->jCombo('f_account_class_t','account_class_id','main_account_code','');
                        $this->data['main_account_name'] = $this->jCombo('f_account_class_t','account_class_id','main_account_name','');
                        $this->data['sub_account_id']= "";
                        $this->data['future_reference1']= "";
                        $this->data['future_reference2']= "";
                        $this->data['sub_account4_id']= "";
                        $this->data['rpt_grp']=$this->jCombo('f_account_reporting_group_t','acc_rpt_grp_id','rpt_grp|rpt_seq','');
                        $this->data['rpt_type']='';
                        $this->data['rpt_seq']='';
			$this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
                        $this->data['row']->account_name="";
                        $this->data['row']->concatenated_segments="";
                        $this->data['row']->account_description="";
			$this->data['row']->active=""; 
                }
		else {

			$table = \DB::table('f_account_structure_t')->where('f_account_structure_id',$id)->get();

					$this->data['row']= $table[0];
                        $this->data['company_id'] = $this->jCombo('m_company_t','company_id','company_code|company_name','');
                        $this->data['location_id'] = $this->jCombo('m_location_t','location_id','location_code|location_name',$table[0]->location_id);

                        $this->data['costcenter_id'] = $this->jcustomselect('m_department_lines_t','department_line_id','sub_department_code|sub_department_name',$table[0]->costcenter_id,' and parent_class_id="0"');
                        $this->data['subcostcenter1_id']= $this->jCombo('m_department_lines_t','department_line_id','sub_department_code|sub_department_name',$table[0]->subcostcenter1_id);
                        $this->data['subcostcenter2_id']= $this->jCombo('m_department_lines_t','department_line_id','sub_department_code|sub_department_name',$table[0]->subcostcenter2_id);
                        $this->data['subcostcenter3_id']= $this->jCombo('m_department_lines_t','department_line_id','sub_department_code|sub_department_name',$table[0]->subcostcenter3_id);
			$this->data['main_account_id'] = $this->jCombo('f_account_class_t','account_class_id','main_account_code',$table[0]->main_account_id);
			$this->data['main_account_name'] = $this->jCombo('f_account_class_t','account_class_id','main_account_name',$table[0]->main_account_name);
                        $this->data['sub_account_id']= $this->jCombo('f_account_codes_lines_t','account_codes_line_id','account_code|account_code_meaning',$table[0]->sub_account_id);
                        $this->data['future_reference1']= $this->jCombo('f_account_codes_lines_t','account_codes_line_id','account_code|account_code_meaning',$table[0]->future_reference1);
                        $this->data['future_reference2']= $this->jCombo('f_account_codes_lines_t','account_codes_line_id','account_code|account_code_meaning',$table[0]->future_reference2);
                        $this->data['sub_account4_id']= $this->jCombo('f_account_codes_lines_t','account_codes_line_id','account_code|account_code_meaning',$table[0]->sub_account4_id);
                        $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
                         $getrpt = \DB::select("select * from f_account_reporting_group_t where acc_rpt_grp_id='".$table[0]->rpt_grp."' and rpt_type='".$table[0]->rpt_type."' and rpt_seq='".$table[0]->rpt_seq."'");
                        if(count($getrpt)>0){
                        $this->data['rpt_grp']=$this->jCombo('f_account_reporting_group_t','acc_rpt_grp_id','rpt_grp|rpt_seq',$getrpt[0]->acc_rpt_grp_id);
                        $this->data['rpt_type']=$getrpt[0]->rpt_type;
                        $this->data['rpt_seq']=$getrpt[0]->rpt_seq;   
                        }else{
                        $this->data['rpt_grp']=$this->jCombo('f_account_reporting_group_t','acc_rpt_grp_id','rpt_grp|rpt_seq','');
                        $this->data['rpt_type']='';
                        $this->data['rpt_seq']='';   
                        }
		}
//                dd($this->data);
		return view('accountstructure.form',$this->data);
	}
/*Karthigaa purpose for Save function*/
         public function save(Request $request){

            $id='';
            $form = $request->all();
	        $form = $request->except([
                '_token','form_config','form_data_json','savestatus','submit_type',
                'choosefile','existing_file','process','rpt_seqno',
            ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);
            $this->table = 'f_account_structure_t';
			$data = $this->validatePost($form,$this->table,'header');
			$acc_name = $request->input('account_name');
			$acc_desc = $request->input('account_description');
            $data['rpt_seq'] = $request->input('rpt_seqno');
			$data['account_name'] = ucfirst(strtolower($acc_name));
			$data['account_description'] = ucfirst(strtolower($acc_desc));
			
			\DB::beginTransaction();
                     try{
                        	$id=$this->model->insertRow($data);
				\DB::commit();
				return response()->json(array('status' => 'success', 'message' => 'Account Structure Saved','id' => $id));
			}
			catch (\Illuminate\Database\QueryException$e){
				$message = explode('(', $e->getMessage());
				$dbCode = rtrim($message[0], ']');
				$dbCode = trim($dbCode, '[');
				\DB::rollback();
				return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
			}

        }

	public function getrptgrpdetails()
	{
	    $rpt_id = $_GET['id'];
	    $get_rpt = \DB::select("select * from f_account_reporting_group_t where acc_rpt_grp_id=$rpt_id");
	    return $get_rpt;
	}	
	
/* purpose for Display View function*/
  public function show(request $request,$id=null){
        if(isset($id)){
          $vdata=\DB::table('f_account_structure_t')->select('f_account_structure_t.*','m_company_t.company_name','m_location_t.location_name',
                  'dep.sub_department_name as depart','dep2.sub_department_name as depart2','dep3.sub_department_name as depart3','dep1.sub_department_name as depart1','f_account_class_t.main_account_code',
                  'subacc1.account_code_meaning as sub1','subacc2.account_code_meaning as sub2','subacc3.account_code_meaning as sub3','subacc4.account_code_meaning as sub4','tb_users.first_name')
                  ->leftjoin('m_company_t','m_company_t.company_id','=','f_account_structure_t.company_id')
                  ->leftjoin('m_location_t','m_location_t.location_id','=','f_account_structure_t.location_id')
                  ->leftjoin('m_department_lines_t as dep','dep.department_line_id','=','f_account_structure_t.costcenter_id')
                  ->leftjoin('m_department_lines_t as dep1','dep1.department_line_id','=','f_account_structure_t.subcostcenter1_id')
                  ->leftjoin('m_department_lines_t as dep2','dep2.department_line_id','=','f_account_structure_t.subcostcenter2_id')
                  ->leftjoin('m_department_lines_t as dep3','dep3.department_line_id','=','f_account_structure_t.subcostcenter3_id')
                  ->leftjoin('f_account_class_t','f_account_class_t.account_class_id','=','f_account_structure_t.main_account_id')
                  ->leftjoin('f_account_codes_lines_t as subacc1','subacc1.account_codes_line_id','=','f_account_structure_t.sub_account_id')
                  ->leftjoin('f_account_codes_lines_t as subacc2','subacc2.account_codes_line_id','=','f_account_structure_t.future_reference1')
                  ->leftjoin('f_account_codes_lines_t as subacc3','subacc3.account_codes_line_id','=','f_account_structure_t.future_reference2')
                  ->leftjoin('f_account_codes_lines_t as subacc4','subacc4.account_codes_line_id','=','f_account_structure_t.sub_account4_id')
		  ->leftjoin('tb_users','tb_users.id','=','f_account_structure_t.created_by')
                  ->where('f_account_structure_id',$id)->get();

          $this->data['company_name']=$vdata[0]->company_name;
          $this->data['location_name']=$vdata[0]->location_name;
          $this->data['depart']=$vdata[0]->depart;
          $this->data['depart1']=$vdata[0]->depart1;
          $this->data['depart2']=$vdata[0]->depart2;
          $this->data['depart3']=$vdata[0]->depart3;
          $this->data['main_account_code']=$vdata[0]->main_account_code;
          $this->data['account_code']=$vdata[0]->sub1;
          $this->data['account_name']=$vdata[0]->account_name;
          $this->data['future_reference1']=$vdata[0]->sub2;
          $this->data['future_reference2']=$vdata[0]->sub3;
          $this->data['subaccount4']=$vdata[0]->sub4;
          $this->data['concatenated_segments']=$vdata[0]->concatenated_segments;
          $this->data['account_description']=$vdata[0]->account_description;
          $this->data['active']=$vdata[0]->active;
		  $this->data['first_name']=$vdata[0]->first_name;

          return view('accountstructure.view',$this->data);
        }
    }

 /*Karthigaa purpose for delete function*/
//	public function delete(Request $request,$id=null){
//        	Accountstructure::destroy($id);
//                return redirect('accountstructure');
//	}
     public function delete(Request $request,$id=null)
    {
	$column = array('account_structure_id','account_structure_id','account_code_id','disc_account_code','control_account_id','account_code_id','account_code_id','account_code_id','account_code_id','account_code_id','expense_account_id','input_tax_account_id','output_tax_account_id');
        $table = array('m_supplier_t','m_customers_t','m_products_t','m_products_t','m_products_t','p_payments_t','p_advance_payments_t','s_advance_receipts_t','s_receipts_t','f_adjustments_t','f_expenses_t','m_tax_group_lines_t','m_tax_group_lines_t');
        for($i=0; $i<count($table); $i++)
        {
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$id)->get();
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }
        if($j==0)
        {
            
            $query = \DB::table('f_account_structure_t')->where('f_account_structure_id',$id)->delete();
            /**Auditlog**/
            $action = "Delete";
            $this->auditlog($id,"accountstructure",$action,$id,"f_account_structure_t");
            
        }

	return $j;

    }
    
}
	
	
	

