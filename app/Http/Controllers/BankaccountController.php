<?php

namespace App\Http\Controllers;

use App\Bankaccount;
use App\Bankaccountlines;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class BankaccountController extends Controller
{
     public function __construct() {
        $this->data = array();
      
        $this->table = "f_bank_account_hdr_t";
        $this->subtable = "f_bank_account_lines_t";
        $this->pageModule = "bankaccount";
        $this->model = new Bankaccount;
        $this->submodel = new Bankaccountlines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'bankaccount',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->modelname = new Bankaccount();
        $this->data['pageFormtype'] = 'ajax';
          $this->data['urlmenu']=$this->indexs(); 
    }
	
    public function index(Request $request)
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

       $table = \DB::table('f_bank_account_hdr_t')->get();
       $this->data['datas'] = $table;
       return view('bankaccount.table',$this->data);
    }



    

    public function getBankaccountData() {
        $wh = '';

		$org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
		$wh.=' and v1.company_id='.$compy;
          
        $SQL = "select * from (SELECT f_bank_account_hdr_t.*,tb_users.first_name,m_supplier_t.supplier_name,m_customers_t.customer_name,m_company_t.company_name FROM f_bank_account_hdr_t left join tb_users on (tb_users.id =f_bank_account_hdr_t.created_by) left join m_supplier_t on (m_supplier_t.supplier_id=f_bank_account_hdr_t.supplierid) left join m_customers_t on (m_customers_t.customer_id=f_bank_account_hdr_t.customerid) left join m_company_t on (m_company_t.company_id=f_bank_account_hdr_t.companyid)  where 1=1 )v1 where 1=1 $wh";
	
        
        $result = \DB::select($SQL);
		return DataTables::of($result)->make(true);
    }
	
      public function create($id = null) {
      if ($id == null) {
            $this->data['row'] = (object) array();
            $this->data['row']->bank_account_hdr_id = "";
            $this->data['row']->bank_name = "";
            $this->data['row']->bank_type = "";
            $this->data['row']->active = "";
             $this->data['row']->bank_source = "";
            $this->data['id'] = '';
            $this->data['linedata'] = array();
            $this->data['account_type'] = $this->jcustomselectactive('a_lookuplines_t','lookuplines_id','lookup_code','','AND lookup_type="ACCOUNT_TYPE"');
            $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
            $this->data['account_code_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
         //   dd($this->data['account_code_id']);
            $this->data['supplierid'] = $this->jCombo('m_supplier_t','supplier_id','supplier_name','');
            $this->data['customerid'] = $this->jCombo('m_customers_t','customer_id','customer_name','');
            $this->data['companyid'] = $this->jCombo('m_company_t','company_id','company_name','');
          // dd($this->data);
            }
        else {
            $this->data['id'] = $id;
			 $this->data['linedata'] = array();
            $table = \DB::table('f_bank_account_hdr_t')->where('bank_account_hdr_id', $id)->get();
            $this->data['row'] = $table[0];
            $this->data['row']->bank_name = $table[0]->bank_name;
            $this->data['row']->bank_type = $table[0]->bank_type;
            $this->data['row']->bank_source = $table[0]->bank_source;
            $this->data['row']->active = $table[0]->active;
            $tablelines = \DB::table('f_bank_account_lines_t')->where('bank_account_hdr_id', $id)->get();
          //  dd($table);
            $this->data['account_type'] = $this->jcustomselectactive('a_lookuplines_t','lookuplines_id','lookup_code',$tablelines[0]->account_type,'AND lookup_type="ACCOUNT_TYPE"');
            //dd($this->data['account_type']);
            $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
            $this->data['account_code_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$tablelines[0]->account_code_id);
            $this->data['supplierid'] = $this->jCombo('m_supplier_t','supplier_id','supplier_name',$table[0]->supplierid);
          //  dd($table[0]->supplierid);
            $this->data['customerid'] = $this->jCombo('m_customers_t','customer_id','customer_name',$table[0]->customerid);
            $this->data['companyid'] = $this->jCombo('m_company_t','company_id','company_name',$table[0]->companyid);
			$this->data['created_by'] = $this->jCombo('tb_users','id','username',$table[0]->created_by);
          //  dd($this->data['account_type']);
            $this->data['linedata'] = $tablelines;
            
        }
		 if (count($this->data['linedata']) >= 1) {

            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->account_type = $this->data['account_type'] = $this->jcustomselectactive('a_lookuplines_t','lookuplines_id','lookup_code',$value->account_type,'AND lookup_type="ACCOUNT_TYPE"');
                $this->data['linedata'][$key]->account_code_id = $this->data['account_code_id']=$this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$value->account_code_id);
                }
        }		
              return view('bankaccount.form', $this->data);
    }
   
         /* Karthigaa purpose for Save function */
    public function save(Request $request) {
       
			$id='';
			$form = $request->all();
			$dataupload ="";
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file','enable-masterdetail',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
			$lines_data = $this->validatePost($form, $this->subtable, 'lines');

        \DB::beginTransaction();
        try {
            $id = $this->model->insertRow($data);
            $lid = $this->submodel->subgridSave($lines_data, $id);
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Bank Details Saved Successfully', 'id' => $id, 'lid' => $lid));
        } catch (\Illuminate\Database\QueryException$e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
             dd($dbCode);
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }
  
 /*Karthigaa purpose for Display hdr & Lines View function*/ 
  public function show(request $request,$id=null){
        if(isset($id)){
          $vdata=\DB::table('f_bank_account_hdr_t')->select('f_bank_account_hdr_t.*','tb_users.username','m_supplier_t.supplier_name','m_customers_t.customer_name','m_company_t.company_name')
                  ->leftjoin('tb_users','tb_users.id','=','f_bank_account_hdr_t.created_by')
                  ->leftjoin('m_supplier_t','m_supplier_t.supplier_id','=','f_bank_account_hdr_t.supplierid')
                  ->leftjoin('m_customers_t','m_customers_t.customer_id','=','f_bank_account_hdr_t.customerid')
                  ->leftjoin('m_company_t','m_company_t.company_id','=','f_bank_account_hdr_t.companyid')
		  ->where('bank_account_hdr_id',$id)->get(); 
          $this->data['bank_name']=$vdata[0]->bank_name;  
          $this->data['bank_type']=$vdata[0]->bank_type;
           $this->data['bank_source']=$vdata[0]->bank_source;
           $this->data['supplier_name']=$vdata[0]->supplier_name;
           $this->data['customer_name']=$vdata[0]->customer_name;
           $this->data['company_name']=$vdata[0]->company_name;
          $this->data['active']=$vdata[0]->active;
		  $this->data['username']=$vdata[0]->username;
          $vlinesdata=\DB::table('f_bank_account_lines_t')->select('f_bank_account_lines_t.*','a_lookuplines_t.lookup_code')->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','f_bank_account_lines_t.account_type')->where('bank_account_hdr_id',$id)->get();
         
          $this->data['vlinesdata']=$vlinesdata; 
          $this->data['branch_name']=$vlinesdata[0]->branch_name;
          $this->data['branch_address']=$vlinesdata[0]->branch_address;
          $this->data['ifsc_code']=$vlinesdata[0]->ifsc_code;
          $this->data['MICR_code']=$vlinesdata[0]->MICR_code;
//          $this->data['account_type']=$vlinesdata[0]->account_type;
          $this->data['name_in_account']=$vlinesdata[0]->name_in_account;
          $this->data['nickname_in_acoount']=$vlinesdata[0]->nickname_in_acoount;
          $this->data['account_number']=$vlinesdata[0]->account_number;
          $this->data['favouring_name']=$vlinesdata[0]->favouring_name;
          $this->data['start_date']=$vlinesdata[0]->start_date;
          $this->data['end_date']=$vlinesdata[0]->end_date;
          $this->data['active']=$vlinesdata[0]->active;
          $this->data['comments']=$vlinesdata[0]->comments;
          $this->data['account_type']=$vlinesdata[0]->lookup_code;
          //dd($this->data['account_type']);
          return view('bankaccount.view',$this->data);
        }
    }

   public function delete($id=null){
		$count=0;
		$queryquote = \DB::table('f_bank_cheque_hdr_t')->where('bank_id',$id)->count();
		if($queryquote >=1){
		 $count++;
		}
		if($count <= 0){
			$query = \DB::table('f_bank_account_hdr_t')->where('bank_account_hdr_id',$id)->delete();
			$query = \DB::table('f_bank_account_lines_t')->where('bank_account_hdr_id',$id)->delete();
			if($query){
				return 0;
			}
			else{
				return 1;
			}
		}
		else{
			return 2;
		}
	}
}
