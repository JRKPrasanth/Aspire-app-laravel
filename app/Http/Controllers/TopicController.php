<?php
namespace App\Http\Controllers;
use App\area;
use App\topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use DB;

class TopicController extends Controller
{

	public function __construct()
	{
		  
		    $this->data=array(
             'pageModule'=> 'TopicController',
             'pageUrl'	=>  url('topic')
              );
        $this->data['urlmenu']=$this->indexs(); 
		    $this->data['pageFormtype']='ajax';
        $this->data['pageMethod']=\Request::route()->getName();
	}


/*Grid Data Load for Subcategory*/
      public function gettopicgrid(Request $request)
{

    if ($request->ajax()) {
        $data = \DB::table('t_topic_tbl')
            ->leftJoin('tb_users', 't_topic_tbl.created_by', '=', 'tb_users.id')
            ->select([
                'tb_users.username',
                't_topic_tbl.topic_name',
                't_topic_tbl.remarks',
                't_topic_tbl.active',
                't_topic_tbl.topic_id'
            ]);

        return DataTables::of($data)
            ->rawColumns(['actions'])
            ->make(true);
    }
}
    /*end*/

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
         $this->data['pageMethod']=\Request::route()->getName();
		
      return view('topic.form',$this->data);
   }
/*End*/

/*Save Function*/
	public function save(Request $request)
	{

	  $topic = new topic();
    // dd($_POST);
       $edit_id = $request->input('edit_id'); 
       
        if($edit_id == '')
        {
	           $topic->topic_name=$_POST['topic_name'];
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
            $this->auditlog($edit_id,"Topic",$action,$_POST,"t_topic_tbl");
          return response()->json(array('status' => 'success', 'message' => 'Topic Saved Successfully!!','id'=>$edit_id));
        }
        else
        {
             topic::find($edit_id)->update($_POST);
             $action="Edit";
            /**Auditlog**/
            $this->auditlog($edit_id,"Topic",$action,$_POST,"t_topic_tbl");
			  return response()->json(array('status' => 'success', 'message' => 'Topic updated Successfully!!','id'=>$edit_id));
        }



	}

/*Delete Function*/
  public function destroy($del_id)
    {


           $query = \DB::table('t_topic_tbl')->where('topic_id',$del_id)->delete();

            $this->auditlog($del_id,"topic","delete","","t_topic_tbl");

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
