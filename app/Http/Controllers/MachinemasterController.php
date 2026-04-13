<?php

namespace App\Http\Controllers;

use App\Machinemaster;
use Illuminate\Http\Request;

class MachinemasterController extends Controller
{
    public function __construct()
    {
        $this->data=array();
        $this->model=new Machinemaster();
               $this->table="w_machine_t";
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
    }

    public function index()
    {
		
		 $this->data['urlname'] =\Request::route()->getName();
         $this->data['pageMethod']=\Request::route()->getName();
         $this->data['machine_name'] = $this->jcustomselectactive('a_lookuplines_t','lookuplines_id','lookup_code','', 'and lookup_type="Machine_name"');
                $this->data['comp_id'] = $this->jCombologin('m_company_t','company_id','company_code',\Session::get('companyid'));
                $this->data['created_by'] = $this->jCombologin('tb_users','id','username',\Session::get('id'));
		
       return view('machinemaster.table',$this->data);
    }
      public function getmachineGridData($type=null)
    {
        $wh='';
        if($_GET['_search']=='true')
        {
		$table=array("a_lookuplines_t","m_company_t");
        $wh=$this->jqgridsearch('w_machine_t',$_GET['filters'],$table);
        }
         $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $org=\Session::get('organization');
		$groupname=\Session::get('groupname');
	    if($groupname=='Superadmin' || $groupname=='Admin'){
		$wh.='and w_machine_t.company_id='.$compy;	
		}else{
			$wh.='and w_machine_t.company_id='.$compy.' and w_machine_t.location_id='.$loc;		
		}
		
	
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;
        $result = \DB::select("SELECT COUNT(w_machine_t.machine_id) AS count FROM w_machine_t left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=w_machine_t.machine_name left join m_company_t on m_company_t.company_id=w_machine_t.company_id where 1=1 $wh");
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
        
       
        
        $SQL = "SELECT w_machine_t.machine_id,w_machine_t.machine_name,w_machine_t.machine_code,w_machine_t.description,w_machine_t.company_id ,m_company_t.company_code ,a_lookuplines_t.lookuplines_id,a_lookuplines_t.lookup_code FROM w_machine_t left join m_company_t on m_company_t.company_id=w_machine_t.company_id  left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=w_machine_t.machine_name where 1=1  $wh ORDER BY $sidx $sord LIMIT $start , $limit";
       
		  $result = \DB::select( $SQL );

        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        echo json_encode($responce);
    }
      public function save(Request $request)
        {
          $machinemaster=new Machinemaster(); 
        $edit_id = $request->input('machine_id');
		
        if($edit_id == '')
        { 
           
            $machinemaster->machine_name=$_POST['machine_name'];
            $machinemaster->machine_code=$_POST['machine_code'];
            $machinemaster->description=$_POST['description'];
              $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $org=\Session::get('organization');
        $machinemaster->location_id=$loc;
            $machinemaster->company_id=$compy;
            $machinemaster->organization_id=$org;
            $machinemaster->save();

            return response()->json(array('status' => 'success', 'message' => 'Machine Saved Successfully!!','id'=>$edit_id));
        }
        else{

            $edit_id=$_POST['machine_id'];

            Machinemaster::find($edit_id)->update($_POST); 

            return response()->json(array('status' => 'success', 'message' => 'Machine Updated Successfully!!','id'=>$edit_id));
        }

        }
           public function machinedelete(Request $request,$id=null)
    {
		
			$query = \DB::table('w_machine_t')->where('machine_id',$id)->delete();
			if($query)
			{
				return 0;
			}
			else
			{
				return 1;
			}
	
		
		
	
    }
	
}
