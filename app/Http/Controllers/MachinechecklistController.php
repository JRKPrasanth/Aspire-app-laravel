<?php

namespace App\Http\Controllers;

use App\Machinechecklist;
use App\Machinechecklistlines;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use yajra\datatables\datatables;
use DB;
class MachinechecklistController extends Controller
{
    public function __construct()
    {
        $this->data=array();
        $this->table = "m_checklist_hrd_tbl";
        $this->subtable = "checklist_lines_tbl";
        $this->pageModule = "pmchecksheet";
        $this->model = new Machinechecklist;
        $this->submodel = new Machinechecklistlines;
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageModule']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
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

          return view('machinechecklist.table',$this->data);
    }

  
	public function getGridData(Request $request)
{
    if ($request->ajax()) {
        $data = \DB::table('m_checklist_hrd_tbl')
            ->leftJoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'm_checklist_hrd_tbl.department_id')
            ->leftJoin('frequency_tbl', 'frequency_tbl.frequency_id', '=', 'm_checklist_hrd_tbl.frequency_id')
            ->leftJoin('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'm_checklist_hrd_tbl.machine_id')
            ->select([
                'm_department_lines_t.sub_department_name',
                'w_machine_hdr_t.machine_name',
                'frequency_tbl.frequency_name',
                'm_checklist_hrd_tbl.checklist_hrd_id'
            ]);

        return DataTables::of($data)->make(true);
    }
}

	
     public function create($id = null) {
	//dd(session()->all());
      if ($id == null) {

            $this->data['row'] = (object) array();
            $this->data['department_id']=$this->jcustomselect('m_department_lines_t','department_line_id','sub_department_code|sub_department_name',""," and sub_department_code LIKE '%c010%'");
            $this->data['created_by'] = $this->jCombologin('tb_users','id','username',\Session::get('id'));
            $this->data['row']->checklist_hrd_id = '';
            $this->data['row']->machine_id = '';
            $this->data['row']->frequency_id = '';
            $this->data['linedata'] = array();
            $this->data['checklist_id'] = $this->jCombologin('checklist_tbl', 'checklist_id', 'checklist_name', '');
            } else {
            $this->data['id'] = $id;
            $table = \DB::table('m_checklist_hrd_tbl')->where('checklist_hrd_id', $id)->get();
            $this->data['department_id']= $this->jcustomselect('m_department_lines_t','department_line_id','sub_department_code|sub_department_name',$table[0]->department_id," and sub_department_code LIKE '%c010%'");
            $this->data['row'] = $table[0];
            $tablelines = \DB::table('checklist_lines_tbl')->where('checklist_hrd_id', $id)->get();
            $this->data['linedata'] = $tablelines;
			foreach($this->data['linedata'] as $key=>$val){
             $this->data['linedata'][$key]->checklist_id = $this->jCombologin('checklist_tbl', 'checklist_id', 'checklist_name',$val->checklist_id);
			}
				$this->data['created_by'] = $this->jCombologin('tb_users','id','username',\Session::get('id'));
        }

       return view('machinechecklist.form',$this->data);
    }
    
  public function save(Request $request)
    {
       
		$id = '';
		$form = $request->all();
		$form = $request->except([
			'_token',
			'form_config',
			'form_data_json',
			'savestatus',
			'submit_type',
			'choosefile',
			'existing_file',
			'productTable_length', 'bulk_line_no',
		]);
		// Normalize "bulk_" keys once
		$form = $this->normalizeLineFormKeys($form);

		// Build header + lines
		$data = $this->validatePost($form, $this->table, 'header');

        $lines_data = $this->validatePost($form, $this->subtable, 'lines');
        // dd($data,$lines_data);
  		     \DB::beginTransaction();
                try
                {
			         $id=$this->model->insertRow($data);
                     $lid=$this->submodel->subgridSave($lines_data,$id);
					/*deepika purpose:audit log*/
					if($_POST['checklist_hrd_id']==""){
					$action="create";	
					}else{
					$action="update";		
					}
					$this->auditlog($id,"machinechecklist",$action,$data,"m_checklist_hrd_tbl");
					/*end*/
                     \DB::commit();
                     return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id,'lid' => $lid));
                }
                catch (\Illuminate\Database\QueryException $e)
                {
                     $message = explode('(', $e->getMessage());
                     $dbCode = rtrim($message[0], ']');
                     $dbCode = trim($dbCode, '[');

                     \DB::rollback();
                     return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
            
				}
	  
    }
	/*end*/
	
    
    public function show($id=null)
    {
      $header = \DB::table('m_checklist_hrd_tbl')
    ->leftjoin('m_department_lines_t','m_department_lines_t.department_line_id','=','m_checklist_hrd_tbl.department_id')
    	->leftjoin('w_machine_hdr_t','w_machine_hdr_t.machine_hdr_id','=','m_checklist_hrd_tbl.machine_id')
    	 ->leftjoin('frequency_tbl','frequency_tbl.frequency_id','=','m_checklist_hrd_tbl.frequency_id')
    	->select('m_checklist_hrd_tbl.checklist_hrd_id','w_machine_hdr_t.machine_name','m_department_lines_t.sub_department_name as department_name','frequency_tbl.frequency_name')->where('checklist_hrd_id',$id)->get();
			$this->data['header'] = $header[0];
			
			$linesdata=\DB::table('checklist_lines_tbl')->leftjoin('m_checklist_hrd_tbl','m_checklist_hrd_tbl.checklist_hrd_id','=','checklist_lines_tbl.checklist_hrd_id')->leftjoin('checklist_tbl','checklist_tbl.checklist_id','=','checklist_lines_tbl.checklist_id')->where('m_checklist_hrd_tbl.checklist_hrd_id',$id)->get();
						$this->data['linesdata'] = $linesdata;
		return view("machinechecklist.view",$this->data);
    }

    
    public function edit(addmachine $addmachine)
    {
        //
    }

    
    public function update(Request $request, addmachine $addmachine)
    {
        //
    }

    
    public function destroy(addmachine $addmachine)
    {
        //
    }
}
