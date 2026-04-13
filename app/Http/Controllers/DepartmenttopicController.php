<?php
namespace App\Http\Controllers;
use App\topic;
use App\departmentTopic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class DepartmenttopicController extends Controller
{

	public function __construct()
	{
		  
		    $this->data=array(
             'pageModule'=> 'DepartmentTopicController',
             'pageUrl'	=>  url('topic')
              );
        $this->data['urlmenu']=$this->indexs(); 
		    $this->data['pageFormtype']='ajax';
        $this->data['pageMethod']=\Request::route()->getName();
	}


/*Grid Data Load for Subcategory*/

	    public function getdepartmenttopicgrid(Request $request)
    {
        if ($request->ajax()) {
    
    
            $data = \DB::table('t_department_topic_tbl')
                ->leftJoin('tb_users', 'tb_users.id', '=', 't_department_topic_tbl.created_by')
                ->leftJoin('t_topic_tbl', 't_topic_tbl.topic_id', '=', 't_department_topic_tbl.topic_id')
                ->leftJoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 't_department_topic_tbl.department_id')
                ->select([
                    'tb_users.username',
                    't_department_topic_tbl.topic_id',
                    't_department_topic_tbl.department_id',
                    't_department_topic_tbl.remarks',
                    't_department_topic_tbl.active',
                    't_department_topic_tbl.department_topic_id',
                    't_topic_tbl.topic_name',
                    'm_department_lines_t.sub_department_name'
                ]);

    
            return DataTables::of($data)
                ->rawColumns(['actions']) 
                ->make(true);
        }
    }
/*Main Page for Topic*/
	public function create(Request $request,$id=null,$type=null)
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

		$com=\Session::get('companyid');
    $this->data['created_by']=$this->jCombologin('tb_users','id','username',\Session::get('id'));
    $this->data['country_id']=$this->jCombologin('m_countries_t','country_id','country_name','');
    $this->data['topic_id']=$this->jCombologin('t_topic_tbl','topic_id','topic_name','');
    $this->data['department_id']=$this->jcustomselect('m_department_lines_t','department_line_id','sub_department_code|sub_department_name','',' and parent_class_id=0');
         $this->data['pageMethod']=\Request::route()->getName();
		
      return view('departmenttopic.form',$this->data);
   }
/*End*/

/*Save Function*/
	public function save(Request $request)
	{

	  $topic = new departmentTopic();
    // dd($_POST);
       $edit_id = $request->input('edit_id'); 
       
        if($edit_id == '')
        {
	           $topic->topic_id=$_POST['topic_id'];
	           $topic->department_id=$_POST['department_id'];
	           $topic->remarks=$_POST['remarks'];
	           $topic->active=$_POST['active'];
	           
	           $topic->created_by=$_POST['created_by'];
             $topic->created_at =date('Y-m-d H:i:s');
             $topic->company_id =\Session::get('companyid');
             $topic->organization_id =\Session::get('organization');
             $topic->location_id =\Session::get('loc_id');
            //  dd($topic);
			       $topic->save();
             $edit_id= DB::getPdo()->lastInsertId();
             $action="Create"; 
            /**Auditlog**/
            $this->auditlog($edit_id,"DepartmentTopic",$action,$_POST,"t_department_topic_tbl");
          return response()->json(array('status' => 'success', 'message' => 'Topic Saved Successfully!!','id'=>$edit_id));
        }
        else
        {
             departmenttopic::find($edit_id)->update($_POST);
             $action="Edit";
            /**Auditlog**/
            $this->auditlog($edit_id,"DepartmentTopic",$action,$_POST,"t_department_topic_tbl");
			  return response()->json(array('status' => 'success', 'message' => 'Topic updated Successfully!!','id'=>$edit_id));
        }



	}
	/*End*/

  /*Edit Function*/

/*End*/

/*Delete Function*/
  public function destroy($del_id)
    {


           $query = \DB::table('t_department_topic_tbl')->where('department_topic_id',$del_id)->delete();
            /**Auditlog**/
            $this->auditlog($del_id,"department_topic","delete","","t_department_topic_tbl");

	          if ($query) {
            return response()->json([
                'status' => 'success',
                'message' => 'Deleted successfully.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Delete failed. Please try again.'
            ], 500);
        }

    }	
/*end*/

	/*deepika purpose:duplicate name function*/
		public function getCheckname(Request $request)
    {

        
            return 0;
    }
/*End*/



}
