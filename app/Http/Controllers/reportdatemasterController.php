<?php

namespace App\Http\Controllers;
use App\Rptdatemaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use DB;

class reportdatemasterController extends Controller
{
     public $module="expenses";
	public function __construct()
	{
        $this->data = array();
        $this->table = "m_rpt_date_tbl";
        $this->pageModule = "gstcode";
        $this->model = new Rptdatemaster;
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        
	}
    public function reportdatemaster()
    {
        $table = \DB::table('m_rpt_date_tbl')->get();
		$this->data['datas'] = $table;
		
        return view('reportdatemaster.index',$this->data);
    }
    
 public function getreportdatemasterData(){
	$wh='';
		if($_GET['_search']=='true')
		{
                    
		$wh=$this->jqgridsearchnotab('v1',$_GET['filters']);
		}
$compy=\Session::get('companyid');
		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
		  $result = \DB::select("select * from (SELECT 
  m_rpt_date_tbl.id,
  m_rpt_date_tbl.date,
  m_rpt_date_tbl.comments,
  concat(tb_users.employee_number,'-',tb_users.first_name) as username
  FROM m_rpt_date_tbl 
  left join tb_users on (tb_users.id =m_rpt_date_tbl.created_by) where 1=1 ) as v1 where 1=1 $wh");
   
		$count = COUNT($result);
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
		
    if(isset($_GET['download']))
    {
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
        return $result1;
    }
	 
		$result = array_slice($result,$start , $limit);
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);

//                 $wh='';
           
//               if(isset($_GET['pq_filter']))
//     {
//     $data=json_decode($_GET['pq_filter']);
//     $data=$data->data;
      
//       $wh.=$this->pqgridsearch('v1',$data);
//     }
//         $org=\Session::get('organization');
//         $loc=\Session::get('location');
//         $compy=\Session::get('companyid');
        
//         $page = $_GET['pq_curpage'];
//         $limit = $_GET['pq_rpp'];
//          // $sord = $_GET['sord'];

//       $sidx='';
//         if (!$sidx)
//             $sidx = 1;
//   $result = \DB::select("select * from (SELECT 
//   m_rpt_date_tbl.id,
//   m_rpt_date_tbl.date,
//   m_rpt_date_tbl.comments,
//   concat(tb_users.employee_number,'-','tb_users.first_name') as username
//   FROM m_rpt_date_tbl 
//   left join tb_users on (tb_users.id =m_rpt_date_tbl.created_by) where 1=1 ) as v1 where 1=1 $wh");
   
//     $count = COUNT($result);
//     if( $count > 0 && $limit > 0)
//     {
//     $total_pages = ceil($count/$limit);
//     } else {
//     $total_pages = 0;
//     }
//     if ($page > $total_pages)
//     $page=$total_pages;
//     $start = $limit*$page - $limit;
//     if($start <0) $start = 0;

//         $compy=\Session::get('companyid');

        
//      $result =array_slice($result,$start,$limit);

//     $responce->rows[]='';
//     $responce->data=$result;
//     $responce->curPage = $page;
//     $responce->total = $total_pages;
//     $responce->totalRecords = $count;
//     echo json_encode($responce);
  }
  
     public function save(Request $request)
    {
//dd($_POST);
         $edit_id = $request->input('id');
       if($edit_id == ''){ 
 
        $Rptdatemaster=new Rptdatemaster(); 

            $Rptdatemaster->id=$_POST['id'];
            $Rptdatemaster->date=date('Y-m-d',strtotime($_POST['date']));
            $Rptdatemaster->comments=$_POST['comments'];
            $Rptdatemaster->created_by=\Session::get('created_by');
            $Rptdatemaster->location_id=\Session::get('location');
            $Rptdatemaster->organisation_id=\Session::get('organization_id');
            $Rptdatemaster->company_id=\Session::get('companyid');
            $Rptdatemaster->created_by=\Session::get('id');
            $Rptdatemaster->last_updated_by=\Session::get('id');
            $Rptdatemaster->save();
            $edit_id= \DB::getPdo()->lastInsertId();
            $action="Create";

            /**Auditlog**/
            $this->auditlog($edit_id,"reportmasterdate",$action,$_POST,"m_rpt_date_tbl");
            return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id'=>$edit_id));
        }
        else{

            $action="Edit";
            $edit_id=$_POST['id'];
            
            Rptdatemaster::find($edit_id)->update($_POST); 
            
            /**Auditlog**/
            $this->auditlog($edit_id,"reportmasterdate",$action,$_POST,"m_rpt_date_tbl");
            return response()->json(array('status' => 'success', 'message' => 'Updated Successfully','id'=>$edit_id));
        }
      }

}
