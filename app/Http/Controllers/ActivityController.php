<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\activity;
class ActivityController extends Controller
{

	public function __construct()
	{
			$this->data['pageFormtype']='ajax';
			$this->data=array();
			$this->data['urlmenu']=$this->indexs(); 
			$this->data['pageMethod']=\Request::route()->getName();
	}
	public function index()
	{
	$table = \DB::table('m_activity_t')->get();
	//dd($table);
	//$arinvoiceheader=arinvoiceheader::all();
	$this->data['datas'] = $table;
	$this->data['pageMethod']="activity";
	return view('activity.table',$this->data);
	}
   	public function getactivityData()
   	{
		$wh='';
	   $table=array("tb_users");
		if($_GET['_search']=='true')
		{
		$wh.=$this->jqgridsearch('m_activity_t',$_GET['filters'],$table);
		}
		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];          
	   $com=\Session::get('companyid');
		if(!$sidx) $sidx =1;
		$result = \DB::select("SELECT COUNT(m_activity_t.activity_id) AS count FROM m_activity_t where 1=1 and m_activity_t.company_id=$com  $wh");
		//dd($result);
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
		$SQL = "SELECT * FROM m_activity_t where 1=1 and m_activity_t.company_id=$com  $wh ORDER BY $sidx $sord LIMIT $start , $limit";
		//dd($SQL);
		$download_SQL = "SELECT * FROM m_activity_t where 1=1 and m_activity_t.company_id=$com  $wh ORDER BY $sidx $sord";
		$result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
		$result = \DB::select( $SQL );
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);
	}
public function create($id=null)
	{
	//$table = \DB::table('so_inquiry_hdr_t')->where('so_inquiry_hdr_id',0)->get();
	$activity=activity::find($id);
	$this->data['row']= (object) array();
	$this->data['row']->activity_name = "";
	$this->data['row']->description = "";
	$this->data['row']->active = "";
	return view('activity.table',$this->data);
	}
	public function save(Request $request)
	{
   		   $activity_id = $request->input('activity_id');
   		   
        if($activity_id == '')
        {
			$activity=new activity();
			//$activity->activity_id=$_POST['activity_id'];
			$activity->activity_name=$_POST['activity_name'];
			$activity->description=$_POST['description'];
			$activity->active=$_POST['active'];
			$activity->company_id=\Session::get('companyid');
			//dd($activity->company_id);
			$activity->location_id=\Session::get('location');
			$activity->organization_id=\Session::get('organization');
			$activity->save();
			$activity_id=DB::getPdo()->lastInsertId();
			$action="Create";
			/**Auditlog**/
			$this->auditlog($activity_id,"activity_name",$action,$_POST,"m_activity_t");
			return response()->json(array('status' => 'success', 'message' => 'Activity Saved Successfully!!','id'=>$activity_id));
		}
		else
		{
			$activity_id=$_POST['activity_id'];
			activity::find($activity_id)->update($_POST);
			$action="Edit";
			/**Auditlog**/
			$this->auditlog($activity_id,"activity_name",$action,$_POST,"m_activity_t");
			return response()->json(array('status' => 'success', 'message' => 'Activity Saved Successfully!!','id'=>$activity_id));
		}
	}
	public function getedit($id)
    {

        $column = array('activity_id');
        $table = array('app_doctor_dcr_t');
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
		return $j; 
    }
	public function edit(Request $request, $id=null)
	{
	$this->data['id'] = $id;

	$table = \DB::table('m_activity_t')->where('activity_id',$id)->get();
	$this->data['row'] = $table[0];

	return view('activity.table',$this->data);
	}
 public function getRemove($id=null)
    {
		$column = array('activity_id');
        $table = array('app_doctor_dcr_t');
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
             $query = \DB::table('m_activity_t')->where('activity_id',$id)->delete();
        } 
        //dd($j);
			/**Auditlog**/
			$action = "Delete";
			$this->auditlog($id,"activity_id",$action,'',"m_activity_t");
		return $j;
	}
	public function show(activity_name $activity,$id=null)
	{
	$this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("m_activity_t");
	$this->data['values'] = activity_name::find($id);
	//dd($this->data);
	return view('activity.view',$this->data);
	}
	 public function getShow(Request $request,$id=null)
    {
    	
    	$column = array('activity_id');
        $table = array('m_activity_t');
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
		return $j;  
    }
	public function getCheckname(Request $request)
	{
			$edit_id = $_GET['edit_id'];
			if($edit_id == '')
			$department=DB::table('m_activity_t')->where('activity_name',$_GET['activity_name'])->get();
			else
			{
			$whereData = [['activity_name', $_GET['activity_name']],['activity_id', '!=', $edit_id]];
			$department=DB::table('m_activity_t')->where($whereData)->get();
			}
			if(count($department)>0)
					return 1;
			else
					return 0;
	}
}