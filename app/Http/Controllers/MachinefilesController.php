<?php

namespace App\Http\Controllers;
use yajra\datatables\datatables;
use App\machinefiles;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use File;

class MachinefilesController extends Controller
{
   public function __construct()
    {
        $this->data=array();
        $this->model    = new machinefiles();
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        $this->data['pageModule']='filemachine';
        $this->table="w_machine_hdr_t";   
        $this->middleware('auth');
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

        $this->data['pageMethod']="filemachine";
     return view('machinefiles.table',$this->data);
    }

    public function create($id=null)
    {
       if(isset($id))
        {   
            $this->data['id'] = $id;
            $this->data['pagemode'] = "edit";
            $table = \DB::table('m_machine_file_t')
            ->select('m_machine_file_t.*','m_department_lines_t.sub_department_name AS department_name')
            ->leftjoin('m_department_lines_t','m_department_lines_t.department_line_id','=','m_machine_file_t.department_id')
            ->where('machine_file_id',$id)->get();
            $this->data['row'] = $table[0];
           
            $this->data['department_id'] =  $this->jcustomselect('m_department_lines_t','department_line_id','sub_department_code|sub_department_name',$table[0]->department_id," and sub_department_code LIKE '%c010%'");    
                     $this->data['machine_id'] = $table[0]->machine_id;

        }
      else{
        $this->data['pagemode'] = "create";
                $this->modelname = new machinefiles();
                $this->data['row']= (object)array();
               $table = $this->modelname->getTableColumns();
                foreach($table as $key=>$val)
                {       
                  $this->data['row']->$val='';
                }
                 $this->data['department_id'] = $this->jcustomselect('m_department_lines_t','department_line_id','sub_department_code|sub_department_name',""," and sub_department_code LIKE '%c010%'");
                   $this->data['machine_id'] = '';
      }
       // dd($this->data);
  return view('machinefiles.form',$this->data);
    
    }
	
	
public function getmachinefilesData(Request $request)
{
    if ($request->ajax()) {
        $data = \DB::table('m_machine_file_t')
            ->leftJoin('tb_users', 'tb_users.id', '=', 'm_machine_file_t.created_by')
            ->leftJoin('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'm_machine_file_t.machine_id')
            ->leftJoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'm_machine_file_t.department_id')
            ->select([
                'm_machine_file_t.*',
                'm_department_lines_t.sub_department_name',
                'tb_users.username',
                'w_machine_hdr_t.machine_name',
                'w_machine_hdr_t.machine_code'
            ]);

        return DataTables::of($data)->make(true);
    }
}	
	
	
	
    /** Store a newly created data & update data in db  */
     public function save(Request $request)
    {  
       // dd($request);
      $edit_id = $request->input('machine_file_id');
      // 
       if($edit_id == ''){ 
// dd($edit_id);
        $machinefiles=new machinefiles(); 
            $machinefiles->department_id=$_POST['department_id'];
            $machinefiles->machine_id=$_POST['machine_id'];
            $machinefiles->file_name=$_POST['file_name'];          
            $machinefiles->created_by=\Session::get('created_by');
            $machinefiles->location_id=\Session::get('loc_id');
            $machinefiles->company_id=\Session::get('companyid');
            $machinefiles->created_by=\Session::get('id');
            $machinefiles->last_updated_by=\Session::get('id');
            $edit_id= \DB::getPdo()->lastInsertId();
            $action="Create";
            if($request->hasfile('upload_file'))
         {
             $file=$request->file('upload_file'); 
              $name=$file->getClientOriginalName();
                $file->move(public_path().'/upload/machinefile', $name);  
                $machinefiles->upload_file=$name;
            }
             $machinefiles->save();
            /**Auditlog**/

            $this->auditlog($edit_id,"machinefilescreate",$action,$_POST,"w_machine_hdr_t");
            return response()->json(array('status' => 'success', 'message' => 'Machinefiles Saved Successfully','id'=>$edit_id));
    }
        else{

            $action="Edit";
            $edit_id=$_POST['machine_hdr_id'];

                $machinefiles['department_id']=$_POST['department_name'];
                $machinefiles['machine_name']=$_POST['machine_name'];
                $machinefiles['machine_code'] =$_POST['machine_code'];
                $machinefiles['file_name'] =$_POST['file_name'];
                // $machinefiles['files'] =$_POST['files'];
            machinefiles::find($edit_id)->update($_POST); 
            /**Auditlog**/
            $this->auditlog($edit_id,"machinefilescreate",$action,$_POST,"w_machine_hdr_t");
            return response()->json(array('status' => 'success', 'message' => 'Machinefiles Updated Successfully','id'=>$edit_id));
        }
    }
        
        
 public function destroy(Request $request,$id=null)
    {  
            $query = \DB::table('w_machine_hdr_t')->where('machine_hdr_id',$id)->delete();
            /**Auditlog**/
            $action = "Delete";
            $this->auditlog($id,"machinefiles",$action,$id,"w_machine_hdr_t");
			return 0;
    }

 /*deepika purpose:to check duplicate name*/ 
      public function machinefilesnamechk(Request $request)
    { 
        $machine_id = $_GET['edit_id'];
      //  dd($machine_id);
        if($machine_id == ''){
            $whereData = [['machine_name', $_GET['machine_name']]];
            $machine_name=\DB::table('w_machine_hdr_t')->where($whereData)->get();
        } else {
            $whereData = [['machine_name', $_GET['machine_name']],['machine_hdr_id', '!=', $machine_id]];
            $machine_name=\DB::table('w_machine_hdr_t')->where($whereData)->get();
        }
        
        if(count($machine_name)>0)
            return 1;
        else
            return 0;
    }
        /*end*/
}
