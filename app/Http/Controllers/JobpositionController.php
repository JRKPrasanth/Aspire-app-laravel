<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Jobposition;
use Illuminate\Http\Request;
use DB;

class JobpositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->data=array();
        $this->model=new Jobposition();
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['urlmenu']=$this->indexs();
    }
    /**** employee grade page start **/
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

        
        $table = \DB::table('m_position')->get();
        
        $this->data['datas'] = $table;
      
        
        return view('employeeposition.form',$this->data);
    }
        /**** employee grade page end **/
        /**** employee grade save start **/
   
    public function save(Request $request)
    {
        
        $edit_id = $request->input('edit_id');
        if($edit_id == '')
        {
            $position = new Jobposition();
            $position->position =  $request->input('position');
            $position->position_description =  $request->input('position_description');
            $position->updated_at =  "";
            $position->created_at =  "";
            $position->location_id =  "1";
            $position->company_id =  \Session::get('companyid');
            $position->organization_id =  \Session::get('organization');  
            $position->active       = $request->input('active');
            
            $position->save(); 
            $name = $position->getKeyName();
            $id = $position->$name; 
            $table = $position->getTable();
            $column = $position->getKeyName();
            $this->hrmssaveinsert($table,$column,$id,1);
               /**Auditlog**/
            $this->auditlog($id,"employeegrade","create",$_POST,"m_position");

            return 1;
        }
        else
        { 
            $position = new Jobposition();
            $edit_id=$_POST['edit_id'];
            Jobposition::find($edit_id)->update($_POST); 
            $table = $position->getTable();
            $column = $position->getKeyName();
            $this->hrmssaveinsert($table,$column,$edit_id,2);
                    /**Auditlog**/
            $this->auditlog($edit_id,"employeegrade","edit",$_POST,"m_position");
            return 2;
        }
    }
     /**** employee grade save end **/
  
    
   /**** employee duplicate name check start **/
    public function getCheckname(Request $request)
    {
      
        
        $edit_id = $_GET['edit_id'];
        
        if($edit_id == ''){
            $department=DB::table('m_position')->where('position',$_GET['position'])->get();
        }
        else
        {              
            $whereData = [['position', $_GET['position']],['position_id', '!=', $edit_id]];
            $department=DB::table('m_position')->where($whereData)->get();
        }
       
        if(count($department)>0)
            return 1;
        else
            return 0;
    }
       /**** employee duplicate name check end **/
       /**** employeeremove start **/
    public function getRemove(Request $request,$id=null)
    {
      
        $column = array('position');
        $table = array('hr_employee_t');
        for($i=0; $i<count($table); $i++)
        {
            $j=0;
            $query = DB::table($table[$i])->where($column[$i],$id)->get();
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }
         if($j==0)
        {
        $query = \DB::table('m_position')->where('position_id',$id)->delete();
 // auditlog
              $this->auditlog($id,"employeegrade","delete",'',"m_position");
         }
       if($j == 1)
            return 1;
        else if($j == 0)
            return 2;
        else if($j == 3)
            return 3;
    }
      /**** employeeremove end **/
      /**** employee grade grid data load start */

	
      public function getposition(Request $request){  

      if ($request->ajax()) {
        $data = \DB::table('m_position')
            ->select([
                'm_position.position_description',
                'm_position.position_id',
                'm_position.position',
                'm_position.active'
            ]);

        return DataTables::of($data) ->make(true);
    }
		  
    }


   
      /**** employee grade grid data load end */
}
