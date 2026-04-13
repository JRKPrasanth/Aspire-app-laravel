<?php
namespace App\Http\Controllers;
use App\topic;
use App\departmentTopic;
use App\Trainingrequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use yajra\datatables\datatables;
use DB;

class TrainingrequestController extends Controller
{

	public function __construct()
	{
		  
		    $this->data=array(
             'pageModule'=> 'TrainingrequestController',
             'pageUrl'	=>  url('trainingrequest')
              );
        $this->data['urlmenu']=$this->indexs(); 
		    $this->data['pageFormtype']='ajax';
        $this->data['pageMethod']=\Request::route()->getName();
	}


/*Grid Data Load for Subcategory*/
   public function gettrainingrequestgrid(Request $request)
    {
        if ($request->ajax()) {
    
    

            $data = \DB::table('t_training_request_tbl')
            ->leftJoin('t_department_topic_tbl', 't_department_topic_tbl.department_topic_id', '=', 't_training_request_tbl.department_topic_id')
            ->leftJoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 't_training_request_tbl.created_by')
            ->leftJoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 't_department_topic_tbl.department_id')
            ->leftJoin('t_topic_tbl', 't_topic_tbl.topic_id', '=', 't_department_topic_tbl.topic_id')
            ->leftJoin('tb_users', 'tb_users.id', '=', 't_training_request_tbl.created_by')
            ->select([
                'tb_users.first_name',
                't_training_request_tbl.topic_id',
                't_training_request_tbl.department_id',
                't_training_request_tbl.remarks',
                't_training_request_tbl.request_type',
                't_training_request_tbl.active',
                't_training_request_tbl.department_topic_id',
                't_topic_tbl.topic_name',
                'm_department_lines_t.sub_department_name',
                't_training_request_tbl.training_request_id',
                't_training_request_tbl.employee_id',
                \DB::raw("CONCAT(hr_employee_t.first_name, '-', hr_employee_t.employee_number) as employee_name"),
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

	   // dd(\Session::all());
		$com=\Session::get('companyid');
		
		$dept_ids = json_decode(\Session::get('dept_id'));
		//dd($dept_ids);
		$this->data['dept_id'] = implode("','",$dept_ids);
		$dept1=implode("','",$dept_ids);
		
		$condition = $this->getjsoncondition('hr_employee_t.department',$dept_ids);
		
		$this->data['dept_condition'] = $condition;
// 		dd($condition);
		$this->data['emp_id'] = \Session::get('emp_id');
    $this->data['created_by']=$this->jCombologin('tb_users','id','username',\Session::get('id'));
    $this->data['country_id']=$this->jCombologin('m_countries_t','country_id','country_name','');
    
    
    // $this->data['department_topic_id']=\DB::select("select t_department_topic_tbl.department_topic_id,t_department_topic_tbl.topic_id,t_department_topic_tbl.department_id,t_topic_tbl.topic_name 
    //                                             from t_department_topic_tbl left join t_topic_tbl on t_topic_tbl.topic_id=t_department_topic_tbl.topic_id 
    //                                             where t_department_topic_tbl.department_id in ('".$this->data['dept_id']."')");
                                                
        $this->data['topic_id'] = $this->jCombo('t_topic_tbl','topic_id','topic_name','');
        
    // $this->data['employee_id']=$this->jCombologin('hr_employee_t','employee_id','first_name','');
    // $this->data['department_id']=$this->jCombologin('m_department_lines_t','department_line_id','sub_department_code|sub_department_name','');
         $this->data['pageMethod']=\Request::route()->getName();
// 		dd($this->data);
      return view('trainingrequest.form',$this->data);
   }
/*End*/

/*Save Function*/
	public function save(Request $request)
	{

	  $topic = new Trainingrequest();
    // dd($_POST);
       $edit_id = $request->input('edit_id'); 
       
        if($edit_id == '')
        {
	           //$topic->department_topic_id=$_POST['department_topic_id'];
	           //$department_topic = DB::table('t_department_topic_tbl')->where('department_topic_id',$_POST['department_topic_id'])->first();

	           //$topic->topic_id=$department_topic->topic_id;
	           //$topic->department_id=$department_topic->department_id;
   	           
   	           $topic->topic_id=$_POST['topic_id'];
   	           
	           $topic->employee_id=json_encode($_POST['employee_id']);
	           $topic->request_type=$_POST['request_type'];
	           $topic->remarks='';
	           $topic->active=$_POST['active'];
	           $topic->approve_status = 0;
	           //dd($topic);
	           $topic->created_by=$_POST['created_by'];
             $topic->created_at =date('Y-m-d H:i:s');
             $topic->company_id =\Session::get('companyid');
             $topic->organization_id =\Session::get('organization');
             $topic->location_id =\Session::get('location');
            //  dd($topic);
			       $topic->save();
             $edit_id= DB::getPdo()->lastInsertId();
             $action="Create"; 
            /**Auditlog**/
            $this->auditlog($edit_id,"TrainingRequest",$action,$_POST,"t_training_request_tbl");
          return response()->json(array('status' => 'success', 'message' => 'Topic Saved Successfully!!','id'=>$edit_id));
        }
        else
        {
            $department_topic = DB::table('t_department_topic_tbl')->where('department_topic_id',$_POST['department_topic_id'])->first();
	           $_POST['topic_id']=$department_topic->topic_id;
	           $_POST['department_id']=$department_topic->department_id;
	           $_POST['remarks']='';
	           $_POST['employee_id'] = json_encode($_POST['employee_id']);
	           //dd($_POST);
             Trainingrequest::find($edit_id)->update($_POST);
             $action="Edit";
            /**Auditlog**/
            $this->auditlog($edit_id,"TrainingRequest",$action,$_POST,"t_training_request_tbl");
			  return response()->json(array('status' => 'success', 'message' => 'Topic updated Successfully!!','id'=>$edit_id));
        }



	}
	/*End*/

  /*Edit Function*/

/*End*/

/*Delete Function*/
  public function destroy($del_id)
    {


           $query = \DB::table('t_training_request_tbl')->where('training_request_id',$del_id)->delete();
            /**Auditlog**/
            $this->auditlog($del_id,"trqiningrequest","delete","","t_training_request_tbl");

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
