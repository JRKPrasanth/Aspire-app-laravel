<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
     public function __construct(){
        $this->data=array();
        $this->table="m_location_t";
        
        $this->pageModule="location";
        $this->model=new Location;
        
        $this->data['pageModule']=$this->pageModule;
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
                $this->data=array(
                    'pageModule'=> 'location',
                    'pageUrl'   =>  url($this->data['pageMethod']),
            'pageMethod'=>$this->data['pageMethod']
                  );
                $this->data['urlmenu']=$this->indexs(); 
              
        $this->modelname = new Location();
        $this->data['pageFormtype']='ajax';
      
    }
     public function index()
    {
           
         $table = \DB::table('m_location_t')->get();   
        $this->data['data']=$table;
        $this->data['row']= (object)array(); 
        $this->data['row']->location_id="";   
        $this->data['row']->location_code="";   
        $this->data['row']->location_name="";
        $this->data['row']->active="";
        $this->data['row']->location_type="";
        $this->data['row']->country_id="";
        $this->data['row']->state_id="";
        $this->data['row']->city_id="";
        $this->data['row']->address="";
        $this->data['row']->street_name="";
        $this->data['row']->pincode="";
              
        return view("location.form",$this->data);
    }


public function loccheckname(Request $request)
    {
       $edit_id = $_GET['location_id'];
        if($edit_id == '')
        {
            
            $group=\DB::table('m_location_t')->where('location_name',$_GET['location_name'])->get();
            
        }
        else
        {
                        

            $whereData = [['location_name', $_GET['location_name']],['location_id', '!=', $edit_id]];

            $group=\DB::table('m_location_t')->where($whereData)->get();
        }


        if(count($group)>0)
            return 1;
        else
            return 0;
    }



    public function locationsave(Request $request)
    {
     
      // dd($request->all());
                        $id='';
            $data = $request->all();
		
            unset($data['_token']);
                        //dd($request->all());
              
            \DB::beginTransaction();
            try
            {
          if($data['location_id']==""){
            $msg="Location Saved";
           $id = \DB::table('m_location_t')->insertGetId($data);
			  $data['company_id'] = \Session::get('companyid');
        }
        else{
            $msg="Location Updated";
			$data['company_id'] = \Session::get('companyid');
			//dd($data);
            $id=\DB::table('m_location_t')->where('location_id', $data['location_id'])->update($data);

        }
                //$lid=$this->submodel->subgridSave($lines_data,$id);
                \DB::commit();

                return response()->json(array('status' => 'success', 'message' => $msg,'id' => $id));
            }
            catch (\Illuminate\Database\QueryException$e)
            {
                $message = explode('(', $e->getMessage());
                $dbCode = rtrim($message[0], ']');
                $dbCode = trim($dbCode, '[');
                dd($dbCode);
                \DB::rollback();
               
                return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
            }
    }

 public function getlocationData(Request $request)
{
    if ($request->ajax()) {
        $data = \DB::table('m_location_t')
            ->leftJoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_location_t.location_type')
            ->leftJoin('m_cities_t', 'm_cities_t.city_id', '=', 'm_location_t.city_id')
            ->leftJoin('m_countries_t', 'm_countries_t.country_id', '=', 'm_location_t.country_id')
            ->leftJoin('m_states_t', 'm_states_t.state_id', '=', 'm_location_t.state_id')
            ->leftJoin('m_area_t', 'm_area_t.area_id', '=', 'm_location_t.area')
            ->select(
                'm_location_t.*',
                'm_cities_t.city_name',
                'm_countries_t.country_name',
                'm_states_t.state_name',
                'a_lookuplines_t.lookup_code',
                'm_area_t.area_id',
                'm_cities_t.city_id',
                'm_countries_t.country_id',
                'm_states_t.state_id',
                'a_lookuplines_t.lookuplines_id'
            );

        return DataTables::of($data)
            ->addColumn('actions', function ($row) {
                return '
                    <a href="/locationform/' . $row->location_id . '" class="btn btn-sm btn-primary">Edit</a>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->location_id . '" data-url="' . url('locationdelete') . '">Delete</button>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
  
    public function locationdelete(Request $request,$id=null)
    {

        $count = 0;
        $queryquote = \DB::table('tb_users')->where('loc_id',$id)->count();
        if($queryquote >=1)
        {
            $count++;
        }
        if($count <= 0)
            {
            $query = \DB::table('m_location_t')->where('location_id',$id)->delete();
            if($query)
            {
                return 0;
            }
            else
            {
                return 1;
            }
        }
        else{
            return 2;
        }
    }
}
