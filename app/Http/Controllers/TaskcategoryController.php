<?php
namespace App\Http\Controllers;
use App\Taskcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use DB;
class TaskcategoryController extends Controller
{
public function __construct()
	{
	    $this->data=array(
         'pageModule'=> 'Taskcategory',
         'pageUrl'	=>  url('taskcategory')
          );
                $this->data['urlmenu']=$this->indexs(); 
		$this->model=new Taskcategory();
	    $this->data['pageFormtype']='ajax';
		$this->data['pageMethod']='taskcategory';
		$this->data['pageModule']='taskcategory';
	}

    /*Jqgrid  Function  Task Category*/
	public function getTaskcategoryData(Request $request)
	{

    if ($request->ajax()) {
        $data = \DB::table('a_task_category_t')
        ->leftJoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'a_task_category_t.department_id')
        ->leftJoin('tb_users', 'tb_users.id', '=', 'a_task_category_t.created_by')
        ->select([
				'tb_users.first_name',
				'a_task_category_t.task_category_id',
				'a_task_category_t.category_name',
				'a_task_category_t.description',
				'a_task_category_t.active',
			'm_department_lines_t.department_line_id',
			'm_department_lines_t.sub_department_name'
            ]);


        return DataTables::of($data)
            ->rawColumns(['actions'])
            ->make(true);
    }
	}
/*End*/

/*Create Function*/
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

        $this->data['created_by']=$this->jCombologin('tb_users','id','username',\Session::get('id'));
   
		if(isset($id ))
	{   
        $taskcategory =Taskcategory::find($id);
       
		$this->data['row']=$taskcategory;
		 $this->data['department_line_id']=$this->jcustomselect("m_department_lines_t","department_line_id","sub_department_name",$this->data['row']->department_line_id,' and parent_class_id=0');
     
		if($type=='g'){
		return $taskcategory;
		}


	}else{    
		$taskcategorydata=\DB::connection()->getSchemaBuilder()->getColumnListing('a_task_category_t');
		$taskcategorydatas=(object)array();
	foreach($taskcategorydata as $key=>$value){
		$taskcategorydatas->$value="";
	}
	$this->data['row']=$taskcategorydatas;
		 $this->data['department_line_id']=$this->jcustomselect("m_department_lines_t","department_line_id","sub_department_name","",' and parent_class_id=0');
	}
		$this->data['opt']=$this->jqgridselect('m_department_lines_t','department_line_id','sub_department_name');

		$this->data['pageMethod']="taskcategory";


      return view('taskcategory.form',$this->data);
    }
/*End*/


/*Save Function*/
	 public function save(Request $request)
    {
        // dd($_POST);
	  $taskcate=new taskcategory();
	  $edit_id = $request->input('edit_id');
        if($edit_id == '')
        {
			$taskcate->category_name = $_POST['category_name'];
            $taskcate->department_id = $_POST['department_line_id'];
            $taskcate->description =  $_POST['description'];
            $taskcate->active =  $_POST['active'];
            $taskcate->created_by =  $_POST['created_by'];
            $taskcate->company_id = \Session::get('companyid');
            $taskcate->location_id = \Session::get('loc_id');
            $taskcate->save();
            $edit_id= DB::getPdo()->lastInsertId();
            $action="Create"; 
            /**Auditlog**/
            $this->auditlog($edit_id,"taskcategory",$action,$_POST,"a_task_category_t");
           return response()->json(array('status' => 'success', 'message' => 'task Category Saved Successfully','id'=>$edit_id));
		  }
        else
        {
              Taskcategory::find($edit_id)->update($_POST);
              $action="Edit";
              /**Auditlog**/
              $this->auditlog($edit_id,"taskcategory",$action,$_POST,"a_task_category_t");
			  return response()->json(array('status' => 'success', 'message' => 'task Category Updated Successfully','id'=>$edit_id));
        }

    }
/*End*/

/*Delete Function*/
		public function getRemove(Request $request,$id=null)
	 {
try {


        // Try to delete
        $deleted = \DB::table('a_task_category_t')->where('task_category_id', $id)->delete();

        if ($deleted) {
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

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
			 
	 }

/*End Delete Function*/

/*Checking task Category Name*/
 public function taskcategoryeditchk($id=null,$grpid=null)
    {
        $prdgrpid=$id=$_GET['id'];
        
         $column = array('task_category_id');
        $table = array('a_task_category_t');
        
        for($i=0; $i<count($table); $i++)
        {
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$prdgrpid)->get();
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        } 
		return $j;
      
    }
    /*End*/


}
