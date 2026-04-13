<?php

namespace App\Http\Controllers;
use App\Groupaccess;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \DB;
use Yajra\DataTables\DataTables;
class GroupaccessController extends Controller
{
      public function __construct()
    {
        $this->data=array();
        $this->model=new Groupaccess();
        $this->data['urlmenu']=$this->indexs();
        
    }
    public function index()
    {
        $table = \DB::table('a_m_group_t')->get();
        $this->data['datas'] = $table;
        $this->data['pageMethod'] = 'group';
        return view('groupaccess.form',$this->data);
    }

	// group data	
	public function GroupsData(Request $request)
	{
		if ($request->ajax()) {
			$query = \DB::table('a_m_group_t')->select('*');

			return DataTables::of($query)->make(true);
		}
	}
	
	// save
	      public function save(Request $request)
    {
       
        $edit_id = $request->input('edit_id');
        if($edit_id == '')
        {
            
            $groupaccess = new Groupaccess();
          
            $groupaccess->group_name = $_POST['group_name'];
            $groupaccess->description =  $_POST['description'];
          
         
 
            $groupaccess->save(); 
			$company=\Session::get('companyid');
			$menu_access = DB::table('a_company_menu_access_t')->where('company_id',$company)->get();
          $data['menus'] = $menu_access[0]->menus;
          $data['permission'] = $menu_access[0]->permission;
          $data['companyaccess_id'] = $company;
          $data['group_id'] = $groupaccess->group_id;
         
            $user_access = DB::table('a_group_menu_access_t')->insert($data); 

          return response()->json(['status' => 'success', 'message' => 'Saved successfully']);
        }
        else
        { 
			$edit_id=$_POST['edit_id'];
            Groupaccess::find($edit_id)->update($_POST); 
            return response()->json(['status' => 'success', 'message' => 'Updated successfully']);
        }
    }
	

    public function getCheckname(Request $request)
    {
       
        $edit_id = $_GET['edit_id'];
        if($edit_id == '')
            $department=DB::table('a_m_group_t')->where('group_name',$_GET['group_name'])->get();
        else
        {
            $whereData = [['group_name', $_GET['group_name']],['group_id', '!=', $edit_id]];
            
            $department=DB::table('a_m_group_t')->where($whereData)->get();
        }
        
        
        if(count($department)>0)
            return 1;
        else
            return 0;
        
        
    }

   public function remove(Request $request,$id=null)
    {

            $query = DB::table('a_m_group_t')->where('group_id',$id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Deleted successfully']);
    }

	public function getgroupgriddata($type=null)
	{
	
    $wh='';
	$search_table=array("a_m_group_t");
		
    if($_GET['_search']=='true')
    {
		
    $wh=$this->jqgridsearch('a_m_group_t',$_GET['filters'],$search_table);
    }
      $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        //$wh.='and tb_users.company_id='.$compy; 
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
    if(!$sidx) $sidx =1;
$result = \DB::select("SELECT COUNT(group_id) AS count FROM a_m_group_t  where 1=1 $wh");
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
    
   
   
   
    $SQL = "SELECT * from a_m_group_t where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
        $download_SQL = "SELECT * from a_m_group_t where 1=1 $wh ORDER BY $sidx $sord";
 $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
         
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
}
