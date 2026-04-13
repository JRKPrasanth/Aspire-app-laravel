<?php

namespace App\Http\Controllers;
use yajra\datatables\datatables;
use App\Breakdowntype;
use Illuminate\Http\Request;
use DB;
class BreakdowntypeController extends Controller
{
    public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
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


        $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
                $table = \DB::table('m_breakdowntype_t')->get();
                $this->data['datas']=json_encode($table);
                $this->data['pageMethod']="breakdowntype";
          return view('breakdowntype.form',$this->data);
    }

      public function create()
    {
        return view('breakdowntype.form');
    }

public function getGridData(Request $request)
{
    if ($request->ajax()) {
        $data = \DB::table('m_breakdowntype_t')
            ->leftJoin('tb_users', 'tb_users.id', '=', 'm_breakdowntype_t.created_by')
            ->select('m_breakdowntype_t.*','tb_users.username','tb_users.id');

        return DataTables::of($data)->make(true);
    }
}
	
        
 public function save(Request $request)
    {
       //dd($request);
         $edit_id = $request->input('breakdowntype_id');
       if($edit_id == ''){ 
       // dd("dfh");
        $breakdowntype=new Breakdowntype(); 

            $breakdowntype->breakdown_name=$_POST['breakdown_name'];
            $breakdowntype->description=$_POST['description'];
            $breakdowntype->location_id=\Session::get('loc_id');
            $breakdowntype->organization_id=\Session::get('organization');
            $breakdowntype->company_id=\Session::get('companyid');
            $breakdowntype->created_by=\Session::get('id');
            $breakdowntype->last_updated_by=\Session::get('id');
            $breakdowntype->save();
          //  dd($breakdowntype);
            $edit_id= DB::getPdo()->lastInsertId();
            $action="Create";
            /**Auditlog**/
            $this->auditlog($edit_id,"breakdowntype",$action,$_POST,"m_breakdowntype_t");
            return response()->json(array('status' => 'success', 'message' => 'Breakdowntype Saved Successfully','id'=>$edit_id));
        }
        else{
            $action="Edit";
            $edit_id=$_POST['breakdowntype_id'];
            breakdowntype::find($edit_id)->update($_POST); 
            /**Auditlog**/
            $this->auditlog($edit_id,"breakdowntype",$action,$_POST,"m_breakdowntype_t");
            return response()->json(array('status' => 'success', 'message' => 'Breakdowntype Updated Successfully','id'=>$edit_id));
        }
          
        
 
    }

    
    /*Ajith Purpose for Used Data Should Not Allow to Edit Function*/
    public function edit(Request $request, $id=null) 
    {
       // dd($id);
       $column = array('break_type_id');
        $table = array('b_maintenance_t');
        for($i=0; $i<count($table); $i++)
        {
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$id)->get();
            if(count($query)>0)
            {
                $j=1;
               return $j;
            }
        }
    return $j;
    }

   public function getCheckname(Request $request)
    { 
       // dd($_GET['edit_id']);
        $break_type_id = $_GET['edit_id']; //dd($vendor_id);
        
        if($break_type_id == ''){
            $whereData = [['breakdown_name', $_GET['breakdown_name']]];
            $breakdown=DB::table('m_breakdowntype_t')->where($whereData)->get();
        } else {
            $whereData = [['breakdown_name', $_GET['breakdown_name']],['breakdowntype_id', '!=', $break_type_id]];
            $breakdown=DB::table('m_breakdowntype_t')->where($whereData)->get();
        }
        
        if(count($breakdown)>0)
            return 1;
        else
            return 0;
    }

    public function destroy(Request $request,$id=null)
    {

    $column = array('break_type_id');

        $table = array('b_maintenance_t');
        for($i=0; $i<count($table); $i++)
        {
            // dd($id);
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
           // dd("fdg");
            $query = \DB::table('m_breakdowntype_t')->where('breakdowntype_id',$id)->delete();
            /**Auditlog**/
            $action = "Delete";
            $this->auditlog($id,"breakdowntype",$action,$id,"m_breakdowntype_t");
            
        }

    return $j;

    }  
}
