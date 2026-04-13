<?php
namespace App\Http\Controllers;
use App\tasksubcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use DB;

class TasksubcategoryController extends Controller
{

	public function __construct()
	{
		  
		    $this->data=array(
             'pageModule'=> 'Tasksubcategory',
             'pageUrl'	=>  url('taskcategory')
              );
        $this->data['urlmenu']=$this->indexs(); 
		    $this->data['pageFormtype']='ajax';
        $this->data['pageMethod']=\Request::route()->getName();
	}


/*Grid Data Load for Subcategory*/
	public function getTasksubcategoryData(Request $request)
	{

    if ($request->ajax()) {
        $data = \DB::table('a_task_subcategory_t')
        ->leftJoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'a_task_subcategory_t.department_id')
        ->leftJoin('tb_users', 'tb_users.id', '=', 'a_task_subcategory_t.created_by')
        ->leftJoin('a_task_category_t', 'a_task_category_t.task_category_id', '=', 'a_task_subcategory_t.task_category_id')
        ->select([
				'tb_users.first_name',
                'a_task_subcategory_t.task_subcategory_id',
                'a_task_subcategory_t.subcategory_name',
                'a_task_subcategory_t.description',
                'a_task_subcategory_t.active',
                'm_department_lines_t.sub_department_name',
                'a_task_category_t.category_name',
			    'a_task_category_t.task_category_id',
                'm_department_lines_t.department_line_id'
            ]);


        return DataTables::of($data)
            ->rawColumns(['actions'])
            ->make(true);
    }
	}
    /*end*/

/*Main Page for task Sub Category*/
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
		$this->data['group']=$this->jqgridselect('m_department_lines_t','department_line_id','sub_department_name');
		$this->data['cat']=$this->jqgridselect('a_task_category_t','task_category_id','category_name');
		
		 $this->data['task_category_id']=$this->jcustomselect("a_task_category_t","task_category_id","category_name",'','and company_id='.$com);
		
	     $this->data['department_line_id']=$this->jcustomselect("m_department_lines_t","department_line_id","sub_department_name",'',' and parent_class_id=0 and company_id='.$com);
         $this->data['pageMethod']=\Request::route()->getName();
		
      return view('tasksubcategory.form',$this->data);
   }
/*End*/

/*Save Function*/
	public function save(Request $request)
	{
	  $tasksubcat = new tasksubcategory();

       $edit_id = $request->input('edit_id'); 
       
        if($edit_id == '')
        {
             $tasksubcat->subcategory_name=$_POST['subcategory_name'];
			 $tasksubcat->department_id=$_POST['department_line_id'];
			 $tasksubcat->task_category_id=$_POST['task_category_id'];
	         $tasksubcat->description=$_POST['description'];
	         $tasksubcat->active=$_POST['active'];
             $tasksubcat->created_by=$_POST['created_by'];
             $tasksubcat->last_updated_by=\Session::get('id');
             $tasksubcat->updated_at=date('Y-m-d H:i:s');
             $tasksubcat->created_at =date('Y-m-d H:i:s');
             $tasksubcat->company_id =\Session::get('companyid');
             $tasksubcat->organization_id =\Session::get('organization');
             $tasksubcat->location_id =\Session::get('loc_id');
			       $tasksubcat->save();
             $edit_id= DB::getPdo()->lastInsertId();
             $action="Create"; 
            /**Auditlog**/
            $this->auditlog($edit_id,"tasksubcategory",$action,$_POST,"a_task_subcategory_t");
          return response()->json(array('status' => 'success', 'message' => 'task Subcategory Saved Successfully!!','id'=>$edit_id));
        }
        else
        {
             tasksubcategory::find($edit_id)->update($_POST);
             $action="Edit";
            /**Auditlog**/
            $this->auditlog($edit_id,"tasksubcategory",$action,$_POST,"a_task_subcategory_t");
			  return response()->json(array('status' => 'success', 'message' => 'task Subcategory updated Successfully!!','id'=>$edit_id));
        }



	}
	/*End*/

  /*Edit Function*/
	public function tasksubcategoryedit($id=null)
	{
	  $id=$_GET['id'];
		$column = array('task_subcategory_id');
        $table = array('a_task_subcategory_t');
        
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
/*End*/

/*Delete Function*/
  public function destroy($id)
    {
try {


        // Try to delete
       $deleted = \DB::table('a_task_subcategory_t')->where('task_subcategory_id',$id)->delete();

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
/*end*/

	/*deepika purpose:duplicate name function*/
		public function getCheckname(Request $request)
    {

        $edit_id = $_GET['edit_id'];
        $sub_department_name = $_GET['department_line_id'];
        $task_category_id = $_GET['task_category_id'];
        
        if($edit_id == '')
                $group=\DB::table('a_task_subcategory_t')->where('department_line_id',$sub_department_name)->where('task_category_id',$task_category_id)->where('subcategory_name',$_GET['subcategory_name'])->get();
        else
        {
            $whereData = [['subcategory_name', $_GET['subcategory_name']],['department_line_id', '=', $sub_department_name],['task_category_id','=',$task_category_id],['task_subcategory_id', '!=', $edit_id]];
            
            $group=\DB::table('a_task_subcategory_t')->where($whereData)->get();
           
        }
        if(count($group)>0)
            return 1;
        else
            return 0;
    }
/*End*/



}
