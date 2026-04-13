<?php

namespace App\Http\Controllers;

use App\agency;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Notifiable;
use yajra\datatables\datatables;

class AgencyController extends Controller
{
 
public function __construct()
    {
        $this->data=array();
        $this->model    = new agency();
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        $this->data['pageModule']='agency';
        $this->table=" ma_agency_t";   
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

        $this->data['pageMethod']="agency";
return view('Agency.table',$this->data);
    }

    public function create($id=null)
    {
        if($id !='0')
        {   
            $this->data['id'] = $id;
            $this->data['pagemode'] = "edit";
            $table = \DB::table('ma_agency_t')->where('agency_id',$id)->get();
    //     dd($table);
            $this->data['row'] = $table[0];
             $this->data['country_id'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name', $table[0]->country);
        $this->data['state_id'] =$table[0]->state; 
        $this->data['city_id'] = $table[0]->city;     
 }else{
     $this->data['pagemode'] = "create";
                $this->modelname = new agency();
               // dd($this->modelname);
                $this->data['row']= (object)array();
                // dd($this->data['row']);
               $table = $this->modelname->getTableColumns();
            // dd($table);
                foreach($table as $key=>$val)
                {       
                  $this->data['row']->$val='';
                }
            $this->data['country_id'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name','');
             $this->data['state_id'] =""; 
             $this->data['city_id'] =""; 
             } 
            // dd($this->data);
return view('Agency.form',$this->data);
    }

    public function getagencyData(Request $request) {



    if ($request->ajax()) {

        $org = \Session::get('organization');
        $com = \Session::get('companyid');
        $employee_id = \Session::get('emp_id');

		$data = \DB::table('ma_agency_t')
			->leftJoin('tb_users', 'tb_users.id', '=', 'ma_agency_t.created_by')
			->select('ma_agency_t.*', 'tb_users.username')
			->get();


        return DataTables::of($data)->make(true);
    }  
	}
    // 
     public function save(Request $request)
    {

         $edit_id = $request->input('agency_id');
       if($edit_id == ''){ 
 
        $agency=new agency(); 

            $agency->agency_name=$_POST['agency_name'];
            $agency->agency_id=$_POST['agency_id'];
            $agency->mobile_no=$_POST['mobile_no'];
             $agency->email=$_POST['email'];
            $agency->address=$_POST['address'];
             $agency->country=$_POST['country'];
            $agency->state=$_POST['state'];
            $agency->city=$_POST['city'];
            $agency->active=$_POST['active'];
            
            $agency->created_by=\Session::get('created_by');
            $agency->location_id=\Session::get('loc_id');
           // $agency->organisation_id=\Session::get('organization_id');
            $agency->company_id=\Session::get('companyid');
            $agency->created_by=\Session::get('id');
            $agency->last_updated_by=\Session::get('id');
            $agency->save();
          //  dd($breakdowntype);
            $edit_id= \DB::getPdo()->lastInsertId();
            $action="Create";

            /**Auditlog**/
            $this->auditlog($edit_id,"agencycreate",$action,$_POST,"ma_agency_t");
            return response()->json(array('status' => 'success', 'message' => 'Agency Saved Successfully','id'=>$edit_id));
        }
        else{

            $action="Edit";
            $edit_id=$_POST['agency_id'];
          //   dd($_POST);
            agency::find($edit_id)->update($_POST); 

            /**Auditlog**/
            $this->auditlog($edit_id,"agencycreate",$action,$_POST,"ma_agency_t");
            return response()->json(array('status' => 'success', 'message' => 'Agency Updated Successfully','id'=>$edit_id));
        }
      }
    public function destroy(Request $request,$id=null)
    {  
//dd($id);
    $column = array('allocated_agency');

        $table = array('machine_pm_detail_t');
        for($i=0; $i<count($table); $i++)
        {
            // dd($id);
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$id)->get();
           // dd($query);
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }
//dd($j);
        if($j==0)
        {
           // dd("fdg");
            $query = \DB::table('ma_agency_t')->where('agency_id',$id)->delete();
           // dd($query);
            /**Auditlog**/
            $action = "Delete";
            $this->auditlog($id,"agency",$action,$id,"ma_agency_t");
            
        }

    return $j;

    }
        public function editcheck($id=null) 
    {
              $column = array('agency_id');
         $table = array('ma_agency_t'); 
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
public function view($id=null)
{
    
        $header = \DB::table('ma_agency_t')
        ->leftjoin('m_countries_t','m_countries_t.country_id','=','ma_agency_t.country')
        ->leftjoin('m_states_t','m_states_t.state_id','=','ma_agency_t.state')
        ->leftjoin('m_cities_t','m_cities_t.city_id','=','ma_agency_t.city')
        ->leftjoin('tb_users','tb_users.id','=','ma_agency_t.created_by')
        ->select('ma_agency_t.*','m_states_t.state_name','m_cities_t.city_name','m_countries_t.country_name','tb_users.username')
        ->where('agency_id',$id)->get();
            $this->data['header'] = $header[0];

    return view('Agency.view',$this->data);

}
// /*deepika purpose:to check duplicate name*/ 
      public function agencynamechk(Request $request)
    { 
        $agency_id = $_GET['edit_id'];
        
        if($agency_id == ''){
            $whereData = [['agency_name', $_GET['agency_name']]];
            $agency=\DB::table('ma_agency_t')->where($whereData)->get();
        //    dd($agency);
        } else {
            $whereData = [['agency_name', $_GET['agency_name']],['agency_id', '!=', $agency_id]];
            $agency=\DB::table('ma_agency_t')->where($whereData)->get();
        }
      
        if(count($agency)>0)
                     return 1;
        else
                      return 0;
    }
	
	
	
}
