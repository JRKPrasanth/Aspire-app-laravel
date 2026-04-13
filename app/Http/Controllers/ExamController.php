<?php
namespace App\Http\Controllers;
use App\Scheduleexamhdr;
use App\Scheduleexamlines;
use App\Examhdr;
use App\Examlines;
use Illuminate\Http\Request;
use Validator,DB,session;
use Config;
use Datetime;
use Yajra\DataTables\DataTables;

class ExamController extends Controller
{
  
	public $module="Scheduleexamhdr";

	public function __construct()
	{
		$this->data=array();
               
		$this->table="t_exam_hdr_tbl";
		$this->subtable="t_exam_lines_tbl";
		$this->pageModule="Examhdr";
		$this->model=new Examhdr;
		$this->submodel=new Examlines;
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
    	$this->data['urlmenu']=$this->indexs(); 

	}
    public function index(Request $request)
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
		$this->data['urlname'] = \Request::route()->getName();
		$this->data['pageMethod']=\Request::route()->getName();
		
    	  return view('Exam.table',$this->data);   
	
    }

   public function resultindex(Request $request)
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


		$this->data['urlname'] = \Request::route()->getName();
		$this->data['pageMethod']=\Request::route()->getName();
		
    	  return view('Exam.resulttable',$this->data);   
	
    }
    
       public function examlist($id)
    {

        $this->data['exam_id'] = $id;
		$this->data['urlname'] = \Request::route()->getName();
		$this->data['pageMethod']=\Request::route()->getName();
// 		dd($id);
    	  return view('Exam.examlisttable',$this->data);   
	
    }
    
    public function examresultindex(Request $request)
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

		$this->data['urlname'] = \Request::route()->getName();
		$this->data['pageMethod']=\Request::route()->getName();
// 		dd($id);
    	  return view('Exam.examresulttable',$this->data);   
	
    }
    /* JQgrid start */
public function getexamgriddata(Request $request)
{

	  $companyId=\Session::get('companyid');
	  $employeeId = \Session::get('emp_id');

	
	if ($request->ajax()) {

        $data = \DB::table('t_schedule_exam_lines_tbl')
            ->leftJoin('t_schedule_exam_hdr_tbl', 't_schedule_exam_lines_tbl.schedule_exam_hdr_id', '=', 't_schedule_exam_hdr_tbl.schedule_exam_hdr_id')
            ->leftJoin('t_topic_tbl', 't_topic_tbl.topic_id', '=', 't_schedule_exam_hdr_tbl.topic_id')
            ->select([
                't_schedule_exam_hdr_tbl.schedule_exam_hdr_id',
                't_schedule_exam_lines_tbl.schedule_exam_line_id',
                \DB::raw("(CASE WHEN t_schedule_exam_lines_tbl.exam_attend = 1 THEN 'Attended' ELSE 'Not Attended' END) as attend"),
                't_schedule_exam_hdr_tbl.schedule_date',
                't_schedule_exam_hdr_tbl.start_time',
                't_schedule_exam_hdr_tbl.end_time',
                't_schedule_exam_hdr_tbl.schedule_type',
                't_schedule_exam_hdr_tbl.remarks',
                't_topic_tbl.topic_name',
            ])
            ->where('t_schedule_exam_lines_tbl.employee_id', $employeeId)
            ->where('t_schedule_exam_hdr_tbl.company_id', $companyId);

        return DataTables::of($data)->make(true);
	}
}

public function getfinishedexamgriddata(Request $request){
   
    if ($request->ajax()) {

        $org = \Session::get('organization');
        $com = \Session::get('companyid');
        $employee_id = \Session::get('emp_id');
        $current_date = now(); // current datetime

        $data = \DB::table('t_schedule_exam_hdr_tbl')
            ->leftJoin('t_topic_tbl', 't_topic_tbl.topic_id', '=', 't_schedule_exam_hdr_tbl.topic_id')
            ->select([
                't_schedule_exam_hdr_tbl.schedule_exam_hdr_id',
                \DB::raw("(CASE WHEN t_schedule_exam_hdr_tbl.result_generated = 1 THEN 'Result Generated' ELSE 'Not Generated' END) as generate"),
                't_schedule_exam_hdr_tbl.schedule_date',
                't_schedule_exam_hdr_tbl.start_time',
                't_schedule_exam_hdr_tbl.end_time',
                't_schedule_exam_hdr_tbl.schedule_type',
                't_schedule_exam_hdr_tbl.remarks',
                't_topic_tbl.topic_name',
            ])
            ->where('t_schedule_exam_hdr_tbl.end_time', '<', $current_date)
            ->where('t_schedule_exam_hdr_tbl.company_id', $com);

        return DataTables::of($data)->make(true);
    }

 
}


public function examlistgriddata($id=null){
   

  	$wh='';

  	if($_GET['_search']=='true')
	{
  		$wh=$this->jqgridsearchnotab('v1',$_GET['filters']);
  	
  	}

	  $org=\Session::get('organization'); 
	  $com=\Session::get('companyid');
	  $employee_id = \Session::get('emp_id');
	  $current_date=date('Y-m-d H:i:s');
	  $page = $_GET['page'];
	  $limit = $_GET['rows'];
	  $sidx = $_GET['sidx'];
	  $sord = $_GET['sord'];
	  if(!$sidx) $sidx =1;
	   $SQL = "select * from(SELECT t_schedule_exam_lines_tbl.*,
	            (CASE WHEN exam_attend='1' THEN 'Attended' ELSE 'NOT Attended' END) as attend_status,
	            hr_employee_t.first_name,
	            tb_users.username,
	            t_exam_hdr_tbl.total_questions,
	            t_exam_hdr_tbl.exam_hdr_id,
	            t_exam_hdr_tbl.exam_start_time,
	            t_exam_hdr_tbl.exam_end_time,
	            t_exam_hdr_tbl.exam_date,
	            t_exam_hdr_tbl.result_generated,
	            (CASE WHEN t_exam_hdr_tbl.result_generated='1' THEN 'Generated' ELSE 'NOT Generated' END) as generate_status,
	            TIMESTAMPDIFF(MINUTE, t_exam_hdr_tbl.exam_start_time, t_exam_hdr_tbl.exam_end_time) as duration,
	            t_exam_hdr_tbl.total_answered_questions,
	            t_exam_hdr_tbl.total_correct_answers,
	            t_exam_hdr_tbl.percentage as perc,
	            t_exam_hdr_tbl.result
FROM t_schedule_exam_lines_tbl 
left join t_exam_hdr_tbl on t_schedule_exam_lines_tbl.schedule_exam_line_id = t_exam_hdr_tbl.schedule_exam_line_id
left join hr_employee_t on hr_employee_t.employee_id = t_schedule_exam_lines_tbl.employee_id
left join tb_users  on tb_users.id=t_schedule_exam_lines_tbl.created_by
where  1=1 and t_schedule_exam_lines_tbl.schedule_exam_hdr_id=$id ) as v1 where 1=1 $wh order by $sidx desc";
	
// 	$result = \DB::table('t_schedule_exam_lines_tbl')->select('t_schedule_exam_lines_tbl.*','hr_employee_t.first_name','tb_users.username','t_exam_hdr_tbl.total_questions','t_exam_hdr_tbl.total_answered_questions','t_exam_hdr_tbl.total_correct_answers','t_exam_hdr_tbl.percentage','t_exam_hdr_tbl.result')
//                     ->leftjoin('t_exam_hdr_tbl','t_schedule_exam_lines_tbl.schedule_exam_line_id','=','t_exam_hdr_tbl.schedule_exam_line_id')
//                     ->leftjoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 't_schedule_exam_lines_tbl.employee_id')
//                     ->leftjoin('tb_users','tb_users.id','=','t_schedule_exam_lines_tbl.created_by')
//                     ->where('t_schedule_exam_lines_tbl.schedule_exam_hdr_id', $id)->get();
                    
                    // dd($result);
                    
	  $result = \DB::select($SQL);
	   //dd($result);
	  $count = count($result);
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
	  $responce->rows[]='';
	  $responce->rows=array_slice($result,$start,$limit);
	  $responce->page = $page;
	  $responce->total = $total_pages;
	  $responce->records = $count;
	  echo json_encode($responce);
 
}
/* End */
/** exam results grid **/
public function examresultgriddata(Request $request)
{
    if ($request->ajax()) {

        $org = \Session::get('organization');
        $com = \Session::get('companyid');
        $employee_id = \Session::get('emp_id');

        $data = \DB::table('t_exam_hdr_tbl')
            ->leftJoin('t_schedule_exam_lines_tbl', 't_schedule_exam_lines_tbl.schedule_exam_line_id', '=', 't_exam_hdr_tbl.schedule_exam_line_id')
            ->leftJoin('t_schedule_exam_hdr_tbl', 't_schedule_exam_lines_tbl.schedule_exam_hdr_id', '=', 't_schedule_exam_hdr_tbl.schedule_exam_hdr_id')
            ->leftJoin('t_topic_tbl', 't_topic_tbl.topic_id', '=', 't_schedule_exam_hdr_tbl.topic_id')
            ->select([
                't_exam_hdr_tbl.exam_hdr_id',
                't_schedule_exam_hdr_tbl.schedule_exam_hdr_id',
                't_schedule_exam_lines_tbl.schedule_exam_line_id',
                \DB::raw("(CASE WHEN t_schedule_exam_lines_tbl.exam_attend = 1 THEN 'Attended' ELSE 'Not Attended' END) as attend"),
                't_schedule_exam_hdr_tbl.schedule_date',
                't_schedule_exam_hdr_tbl.start_time',
                't_schedule_exam_hdr_tbl.end_time',
                't_schedule_exam_hdr_tbl.schedule_type',
                't_schedule_exam_hdr_tbl.remarks',
                't_topic_tbl.topic_name',
            ])
            ->where('t_schedule_exam_lines_tbl.employee_id', $employee_id)
            ->where('t_schedule_exam_lines_tbl.exam_attend', 1)
            ->where('t_schedule_exam_hdr_tbl.result_generated', 1)
            ->where('t_schedule_exam_hdr_tbl.company_id', $com);

        return DataTables::of($data)->make(true);
    }
  
}
	
/** end**/
/**from request grid**/
public function getrequestexamgriddata($type=null)
{

  	$wh='';

  	if($_GET['_search']=='true')
	{
  		$wh=$this->jqgridsearchnotab('v1',$_GET['filters']);
  	
  	}

	  $org=\Session::get('organization'); 
	  $com=\Session::get('companyid');
	  $page = $_GET['page'];
	  $limit = $_GET['rows'];
	  $sidx = $_GET['sidx'];
	  $sord = $_GET['sord'];
	  if(!$sidx) $sidx =1;
	   $SQL = "select * from(select t_training_request_tbl.training_request_id,request_type,(select t_topic_tbl.`topic_name` from t_topic_tbl where t_topic_tbl.topic_id=t_training_request_tbl.topic_id) as topic_name,(select first_name from hr_employee_t where hr_employee_t.employee_id=t_training_request_tbl.employee_id) as employee_name  from t_training_request_tbl where  1=1 and t_training_request_tbl.company_id=$com and t_training_request_tbl.schedule_raised=0) as v1 where 1=1 $wh order by $sidx desc";
	
	  $result = \DB::select($SQL);
	  // dd($result);
	  $count = count($result);
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
	  $responce->rows[]='';
	  $responce->rows=array_slice($result,$start,$limit);
	  $responce->page = $page;
	  $responce->total = $total_pages;
	  $responce->records = $count;
	  echo json_encode($responce);
}

/**end**/
/* Create function */
public function create($id=null)
    {
        // dd(\Session::all());
  		$urlName = \Request::route()->getName();
		$this->data['pageMethod']="scheduleexam";
		if($id == '0')
		{
		    
		} else {

			$this->data['id'] = $id; 
			$this->data['pagemode'] = 'edit';
        // 	$table = \DB::table('t_schedule_exam_hdr_tbl')->where('schedule_exam_hdr_id',$id)->get();
        	$table = \DB::select("select t_schedule_exam_lines_tbl.*,t_topic_tbl.topic_name,t_schedule_exam_hdr_tbl.* 
        	            from t_schedule_exam_lines_tbl 
        	            left join t_schedule_exam_hdr_tbl on t_schedule_exam_hdr_tbl.schedule_exam_hdr_id=t_schedule_exam_lines_tbl.schedule_exam_hdr_id
        	            left join t_topic_tbl on t_topic_tbl.topic_id = t_schedule_exam_hdr_tbl.topic_id
        	            where t_schedule_exam_lines_tbl.schedule_exam_line_id=$id
        	            ");
        	           // dd($table);
          	$this->data['row'] =$table[0] ;
          	$this->data['row']->exam_hdr_id = '';
          	$this->data['row']->exam_start_time = date('Y-m-d H:i:s');
          	
	 		$this->data['topic_id'] = $this->jCombo('t_topic_tbl','topic_id','topic_name',$table[0]->topic_id);
        //   	$linestable = \DB::table('t_exam_questions_hdr_tbl')->where('schedule_exam_hdr_id',$id)->get();
          	$linestable = \DB::select("select * from t_exam_questions_lines_tbl 
          	                left join t_exam_questions_hdr_tbl on t_exam_questions_hdr_tbl.exam_questions_hdr_id=t_exam_questions_lines_tbl.exam_questions_hdr_id
          	                where t_exam_questions_hdr_tbl.topic_id='".$table[0]->topic_id."'");
          	$this->data['row']->total_questions = count($linestable);
	 		$this->data['linedata'] = $linestable;
            foreach($this->data['linedata']  as $key=>$value)
            {			
            $this->data['linedata'][$key]->exam_line_id ='';
            // $this->data['linedata'][$key]->employee_id= $this->jCombo('hr_employee_t','employee_id','first_name|last_name',$value->employee_id);
		    }

			
	}
        // dd($this->data);
	return view('Exam.form',$this->data);

    }
    
    /* Create function */
public function resultgenerate($id=null)
    {
  		$urlName = \Request::route()->getName();
		$this->data['pageMethod']="scheduleexam";
		if($id == '0')
		{
		    
		} else {

			$this->data['id'] = $id; 
			$this->data['pagemode'] = 'edit';
        // 	$table = \DB::table('t_schedule_exam_hdr_tbl')->where('schedule_exam_hdr_id',$id)->get();
        	$table = \DB::select("select t_schedule_exam_lines_tbl.schedule_exam_line_id,t_exam_hdr_tbl.percentage,t_exam_hdr_tbl.result_generated
        	            from  t_schedule_exam_lines_tbl
        	            join t_exam_hdr_tbl on t_exam_hdr_tbl.schedule_exam_line_id=t_schedule_exam_lines_tbl.schedule_exam_line_id
        	            left join t_schedule_exam_hdr_tbl on t_schedule_exam_hdr_tbl.schedule_exam_hdr_id=t_schedule_exam_lines_tbl.schedule_exam_hdr_id
        	            left join t_topic_tbl on t_topic_tbl.topic_id = t_schedule_exam_hdr_tbl.topic_id
        	            where t_schedule_exam_lines_tbl.schedule_exam_hdr_id=$id
        	            ");
        	           // dd($table);
        	           $result_generated=1;
        	if(count($table)>0){
            	foreach($table as $key=>$val){
            	    if($val->result_generated!=1){$result_generated=0;}
            	    \DB::table('t_schedule_exam_lines_tbl')->where('schedule_exam_line_id',$val->schedule_exam_line_id)->update(['percentage'=>$val->percentage]);
            	}
            	if($result_generated==1){
            	    \DB::table('t_schedule_exam_hdr_tbl')->where('schedule_exam_hdr_id',$id)->update(['result_generated'=>1]);
            	}
            	
            	return response()->json(array('status' => 'Sucess', 'message' => "Results Generated Sucessfully",'id' => ''));
        	}else{
        	    return response()->json(array('status' => 'Info', 'message' => "No Data to generate Result",'id' => ''));
        	} 
			
	    }
        

    }
    public function validatesave($id=null)
    {
        // dd($_POST);
        $total_crct_ans = 0;
        $exam_hdr_id = $_POST['exam_hdr_id'];
        $total_questions = $_POST['total_questions'];
        
        foreach($_POST['exam_line_id'] as $key=>$value){
            
            $exam_line_id = $value;
            $status = $_POST['status'][$key];
            if($status==1){
                $total_crct_ans = $total_crct_ans+1;
            }
            
            \DB::table('t_exam_lines_tbl')->where('exam_line_id',$exam_line_id)->update(['status'=>$status]);
        }
        $result_generated = 1;
        $percentage = ($total_crct_ans/$total_questions)*100;
        // result_generated
        
        
        \DB::table('t_exam_hdr_tbl')->where('exam_hdr_id',$exam_hdr_id)->update(['total_correct_answers'=>$total_crct_ans,'percentage'=>$percentage,'result_generated'=>$result_generated]);
       return response()->json(array('status' => 'success', 'message' => "Result Saved Successfully"));
    }
public function result_chk($id=null){
    $data = \DB::table('t_schedule_exam_hdr_tbl')->where('schedule_exam_hdr_id',$id)->where('result_generated',1)->get();
    if(count($data)>0){
        return response()->json(array('status' => 'Success', 'message' => "Results Generated",'ret' => 1));
    }else{
        return response()->json(array('status' => 'Info', 'message' => "Result Not Yet Generated",'ret' => 0));
    }
}
/* Save function */
public function save(Request $request)
        { 
        
            // $data = $this->validatePost($request->all(),$this->table,'header');
            
            // $lines_data = $this->validatePost($request->all(),$this->subtable,'lines');
            
            $data['schedule_exam_line_id'] = $_POST['schedule_exam_line_id'];
            $data['exam_date'] = $_POST['exam_date'];
            $data['employee_id'] = $_POST['employee_id'];
            $data['topic_id'] = $_POST['topic_id'];
            $data['total_questions'] = $_POST['total_questions'];
            $data['start_time'] = $_POST['start_time'];
            $data['end_time'] = $_POST['end_time'];
            $data['exam_start_time'] = $_POST['exam_start_time'];
            if($_POST['exam_end_time']!=''){
                $data['exam_end_time'] = $_POST['exam_end_time'];
            }else{
                $data['exam_end_time'] = date('Y-m-d H:i:s');
            }
            $data['remarks'] = '';
            $data['source'] = $_POST['source'];
            $data['organization_id'] = \Session::get('organization');
            $data['location_id'] = \Session::get('location');
			$data['company_id'] =\Session::get('companyid');
			$data['created_by'] =\Session::get('emp_id');
            
            // dd($data);
	       \DB::beginTransaction();
        
        //   dd($data);
            try
            {

            	$id=Examhdr::create($data);
            	
            	    $total_ans_q =0;
            	    $total_crct_ans = 0;
            	    
            	foreach($_POST['exam_questions_line_id'] as $key=>$val){
            	   // $lines_data['exam_line_id'] = '';
            	    $lines_data['exam_hdr_id'] = $id->exam_hdr_id;
            	    $lines_data['exam_question_type'] = $_POST['exam_question_type'][$key];
            	    $answers = DB::table('t_exam_questions_lines_tbl')->where('exam_questions_line_id',$val)->get();
            	   // dd($answers);
            	    $ans = $answers[0]->answer;
            	    
            	    
            	    $lines_data['exam_questions_line_id'] = $val;
            	    if(isset($_POST['answer'][$key])){
            	        $lines_data['answer'] = $_POST['answer'][$key];
            	        $total_ans_q=$total_ans_q+1;
            	    }else{
            	        $lines_data['answer'] = '';
            	    }
            	    
            	    if($lines_data['exam_question_type']!='text'){
                	    if($answers[0]->$ans==$lines_data['answer']){
                	        $lines_data['status']='1';
                	        $total_crct_ans = $total_crct_ans+1;
                	    }else{
                	        $lines_data['status']='0';
                	    }
            	    }else{
            	        $lines_data['status'] = '';
            	    }
            	   // dd($lines_data);
            	   
            	   
                    $lines_data['organization_id'] = \Session::get('organization');
                    $lines_data['location_id'] = \Session::get('location');
        			$lines_data['company_id'] =\Session::get('companyid');
        			$lines_data['created_by'] =\Session::get('emp_id');
        			
            	    $lines=Examlines::create($lines_data);
            	   // dd($lines_data);
            	   $lid = $lines->exam_line_id;
            	}
            	$percentage = ($total_crct_ans/$_POST['total_questions'])*100;
            // 	die;
            \DB::table('t_exam_hdr_tbl')->where('exam_hdr_id',$id->exam_hdr_id)->update(['total_answered_questions'=>$total_ans_q,'total_correct_answers'=>$total_crct_ans,'percentage'=>$percentage]);
				
				// if($data['schedule_type']=="Request"){
				    \DB::table('t_schedule_exam_lines_tbl')->where('schedule_exam_line_id',$data['schedule_exam_line_id'])->update(['exam_attend'=>'1']);
				// }
				

/** end**/
   
				\DB::commit();
				/**Auditlog**/
			$action="Create";
			//$this->auditlog($id,"FREIGHT CARRIERS",$action,$_POST,"m_frieghtcarriers_hdr_t");
                return response()->json(array('status' => 'success', 'message' => "Saved Successfully",'id' => $id,'lid' => $lid));
            }
            catch (\Illuminate\Database\QueryException $e)
            {
                 $message = explode('(', $e->getMessage());
                 $dbCode = rtrim($message[0], ']');
                 $dbCode = trim($dbCode, '[');
dd($dbCode);
                 \DB::rollback();
                 $action="Edit";
		//	$this->auditlog($id,"FREIGHT CARRIERS",$action,$_POST,"m_frieghtcarriers_hdr_t");
                 return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
            }

        }
	//Edit data
    public function edit(Request $request, $id=null)
	{
	$this->data['pageModule']="freightcarriershdr";
		   $this->data['pageUrl']=url('freightcarriershdr');
              

		$this->data['enabled_columns']=\DB::table('m_column_permission_t')->where('module_name','freightcarriershdr')->get();
		$this->data['urlName']=\Request::route()->getName();

		if($this->data['urlName']=="purchasefreightcarriershdredit")
        {
			$this->data['source_type_id']="Purchase";
		}
		else
        {
			$this->data['source_type_id']="Sales";
		}
  
		$this->data['url_type'] ='';
		$table = \DB::table('m_frieghtcarriers_hdr_t')->where('ar_frieghtcarriers_hdr_id',$id)->get();
        $this->data['created_by'] = $this->jCombo('tb_users','id','username',$table[0]->created_by);
		$this->data['row'] = $table[0];
		$str_date =$this->data['row']->start_date;
		$end_date =$this->data['row']->end_date;
		
		if($str_date!='0000-00-00')
		$this->data['row']->start_date = date("m-d-Y", strtotime($str_date));
		else
		$this->data['row']->start_date ='';
		
		if($end_date!='0000-00-00')
		$this->data['row']->end_date = date("m-d-Y", strtotime($end_date));
		else
		$this->data['row']->end_date ='';	
		$this->data['row']->remarks = $table[0]->remarks;

		$this->data['row']->ar_frieghtcarriers_hdr_id  = $id;


		$tablelines = \DB::table('m_frieghtcarriers_lines_t')->where('ar_frieghtcarriers_hdr_id',$id)->get();
		

		$this->data['default_currency'] = $this->jCombo('f_account_currency_t','account_currency_id','currency_code',$this->data['row']->default_currency);
		 $location=\Session::get('location');
			$comp=\Session::get('companyid');
            $company=\DB::table('m_company_line_t')->where('companyid',$comp)->get();
		
			if(count($company)>0){
				$c='';
				foreach($company as $k=>$y){
					$c.=$y->locationid.",";
				}
				
				$c=rtrim($c,',');
	
			$this->data['location_id'] = $this->jcustomselect('m_location_t','location_id','location_name',$this->data['row']->location_id," and location_id in(".$c.")");
			}
			else{
				$this->data['location_id'] = $this->jCombologin('m_location_t','location_id','location_name','');
			}
	
		$this->data['linedata'] = $tablelines;

		$this->data['country'] = $this->data['country'] = $this->jCombologin('m_countries_t','country_id','country_name',$tablelines[0]->country);
		
		$this->data['state'] = $this->data['state'] = $this->jCombologin('m_states_t','state_id','state_name',$tablelines[0]->state);
		$this->data['city'] = $this->data['city'] = $this->jCombologin('m_cities_t','city_id','city_name',$tablelines[0]->city);
				
		foreach($this->data['linedata'] as $key=>$value)
		{

			$this->data['linedata'][$key]->country = $this->data['country'] = $this->jCombologin('m_countries_t','country_id','country_name',$value->country);

			$this->data['linedata'][$key]->state = $this->data['state'] = $this->jCombologin('m_states_t','state_id','state_name',$value->state);

			$this->data['linedata'][$key]->city = $this->data['city'] = $this->jCombologin('m_cities_t','city_id','city_name',$value->city);

		}
             
	if(isset($_GET['status'])){
			$this->data['used_some']="readonly";
		}
		$this->data['pagemode'] = 'edit';
		
		return view('Scheduletraining.form',$this->data);
	}
	
	public function examschedulechk($id)
	{
	   // dd(\Session::all());
	    $exam_schedule = DB::select("select t_schedule_exam_hdr_tbl.start_time,t_schedule_exam_hdr_tbl.end_time,t_schedule_exam_lines_tbl.exam_attend from t_schedule_exam_lines_tbl  
	        left join t_schedule_exam_hdr_tbl on t_schedule_exam_hdr_tbl.schedule_exam_hdr_id=t_schedule_exam_lines_tbl.schedule_exam_hdr_id 
	        where t_schedule_exam_lines_tbl.schedule_exam_line_id=$id");
        $current_date=date('Y-m-d H:i:s');
        $exam_date = date('Y-m-d H:i:s',strtotime($exam_schedule[0]->start_time));
        $exam_end_date = date('Y-m-d H:i:s',strtotime($exam_schedule[0]->end_time));
        // dd($exam_schedule);
        if($current_date<$exam_date){
            return response()->json(array('status' => 'Info', 'message' => "Exam date : $exam_date",'ret' => 0));
        }else if($current_date>$exam_end_date){
            return response()->json(array('status' => 'Info', 'message' => "Exam Schedule Expired",'ret' => 0));
        }else{
            if($exam_schedule[0]->exam_attend==1){
                return response()->json(array('status' => 'Info', 'message' => "Exam Already Attended",'ret' => 0));
            }else{
            return response()->json(array('status' => 'Success', 'message' => "Exam date : $exam_date",'ret' => 1));
            }
            
        }
        // dd($exam_schedule);
        
	}
	// View function
	public function show( $id=null)
	{
		$this->data['urlname'] = \Request::route()->getName();
        $this->data['index_data']="Exam";
	    
	    $this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("t_schedule_exam_hdr_tbl");
		$hdr_data = \DB::table('t_schedule_exam_hdr_tbl')->select('t_schedule_exam_hdr_tbl.*','t_topic_tbl.topic_name')
		                        ->leftjoin('t_topic_tbl','t_topic_tbl.topic_id','=','t_schedule_exam_hdr_tbl.topic_id')
		                        ->where('t_schedule_exam_hdr_tbl.schedule_exam_hdr_id',$id)->get();
		$this->data['values'] = $hdr_data[0];
// 		dd($this->data['values']);
		$str_date = $this->data['values']->start_time;
		$end_date = $this->data['values']->end_time;
		
		$date_time_format = \Session::get('p_date_format').' '.\Session::get('p_time_format');

		$this->data['start_time'] = date($date_time_format, strtotime($str_date));
		$this->data['end_time'] = date($date_time_format, strtotime($end_date)); 	

// 		$active=$this->data['values']->active;

		$vlinesdata = \DB::table('t_schedule_exam_lines_tbl')->select('t_schedule_exam_lines_tbl.*','hr_employee_t.first_name','tb_users.username')
                    ->leftjoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 't_schedule_exam_lines_tbl.employee_id')
                    -> leftjoin('tb_users','tb_users.id','=','t_schedule_exam_lines_tbl.created_by')
                    ->where('t_schedule_exam_lines_tbl.schedule_exam_hdr_id', $id)->get();

        $location=$this->data['values']->location_id;
        $locationid=DB::table("m_location_t")->where('location_id',$location)->get();
      
        $this->data['location_name']=$locationid[0]->location_name; 
		
		
		$user = \DB::table('tb_users')->where('id',$this->data['values']->created_by)->get();
		if(count($user)>0){
		$this->data['created_by'] = $user[0]->username;
		}
		else{
			$this->data['created_by'] = '';
		}

            $this->data['vlinesdata'] = $vlinesdata;
            
            $this->data['active']='Yes';
		
// dd($this->data);
		return view('Scheduleexam.view',$this->data);
	}
	
	public function resultsview($id=null)
		{
	    
		$this->data['urlname'] = \Request::route()->getName();
        $this->data['index_data']="ExamResults";
	    
	    $this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("t_schedule_exam_hdr_tbl");
		$hdr_data = \DB::table('t_schedule_exam_hdr_tbl')->select('t_schedule_exam_hdr_tbl.*','t_topic_tbl.topic_name')
		                        ->leftjoin('t_topic_tbl','t_topic_tbl.topic_id','=','t_schedule_exam_hdr_tbl.topic_id')
		                        ->where('t_schedule_exam_hdr_tbl.schedule_exam_hdr_id',$id)->get();
		$this->data['values'] = $hdr_data[0];
// 		dd($this->data['values']);
		$str_date = $this->data['values']->start_time;
		$end_date = $this->data['values']->end_time;
		
		$date_time_format = \Session::get('p_date_format').' '.\Session::get('p_time_format');

// 		$this->data['start_time'] = date($date_time_format, strtotime($str_date));
// 		$this->data['end_time'] = date($date_time_format, strtotime($end_date)); 	
		
		$this->data['schedule_date'] = date('d-m-Y',strtotime( $this->data['values']->schedule_date));
		$this->data['start_time'] = date("g:iA", strtotime($str_date));
		$this->data['end_time'] = date("g:iA", strtotime($end_date));
// 		$active=$this->data['values']->active;

// 		$vlinesdata = \DB::table('t_schedule_exam_lines_tbl')->select('t_schedule_exam_lines_tbl.*','hr_employee_t.first_name','tb_users.username')
//                     ->leftjoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 't_schedule_exam_lines_tbl.employee_id')
//                     -> leftjoin('tb_users','tb_users.id','=','t_schedule_exam_lines_tbl.created_by')
//                     ->where('t_schedule_exam_lines_tbl.schedule_exam_hdr_id', $id)->get();
        $vlinesdata = \DB::table('t_schedule_exam_lines_tbl')->select('t_schedule_exam_lines_tbl.*','hr_employee_t.first_name','tb_users.username','t_exam_hdr_tbl.total_questions','t_exam_hdr_tbl.total_answered_questions','t_exam_hdr_tbl.total_correct_answers','t_exam_hdr_tbl.percentage','t_exam_hdr_tbl.result')
                    ->leftjoin('t_exam_hdr_tbl','t_schedule_exam_lines_tbl.schedule_exam_line_id','=','t_exam_hdr_tbl.schedule_exam_line_id')
                    ->leftjoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 't_schedule_exam_lines_tbl.employee_id')
                    ->leftjoin('tb_users','tb_users.id','=','t_schedule_exam_lines_tbl.created_by')
                    ->where('t_schedule_exam_lines_tbl.schedule_exam_hdr_id', $id)->get();

        $location=$this->data['values']->location_id;
        $locationid=DB::table("m_location_t")->where('location_id',$location)->get();
      
        $this->data['location_name']=$locationid[0]->location_name; 
		
		
		$user = \DB::table('tb_users')->where('id',$this->data['values']->created_by)->get();
		if(count($user)>0){
		$this->data['created_by'] = $user[0]->username;
		}
		else{
			$this->data['created_by'] = '';
		}

            $this->data['vlinesdata'] = $vlinesdata;
            
            $this->data['active']='Yes';
		
// dd($this->data);
		return view('Exam.view',$this->data);
	}
		public function validateexam($id=null)
		{
	    
		$this->data['urlname'] = \Request::route()->getName();
        $this->data['index_data']="ExamResults";
	    
	    $this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("t_schedule_exam_hdr_tbl");
		$hdr_data = \DB::table('t_exam_hdr_tbl')->select('t_exam_hdr_tbl.*','t_topic_tbl.topic_name','hr_employee_t.first_name','t_schedule_exam_lines_tbl.schedule_exam_hdr_id')
		                        ->leftjoin('t_topic_tbl','t_topic_tbl.topic_id','=','t_exam_hdr_tbl.topic_id')
		                        ->leftjoin('t_schedule_exam_lines_tbl','t_schedule_exam_lines_tbl.schedule_exam_line_id','=','t_exam_hdr_tbl.schedule_exam_line_id')
		                        ->leftjoin('hr_employee_t','hr_employee_t.employee_id','t_exam_hdr_tbl.employee_id')
		                        ->where('t_exam_hdr_tbl.exam_hdr_id',$id)->get();
		$this->data['values'] = $hdr_data[0];
// 		dd($this->data['values']);
		$str_date = $this->data['values']->start_time;
		$end_date = $this->data['values']->end_time;
		
		$date_time_format = \Session::get('p_date_format').' '.\Session::get('p_time_format');

// 		$this->data['start_time'] = date($date_time_format, strtotime($str_date));
// 		$this->data['end_time'] = date($date_time_format, strtotime($end_date)); 	
		
		$this->data['schedule_date'] = date('d-m-Y',strtotime( $this->data['values']->exam_date));
		$this->data['start_time'] = date("g:iA", strtotime($str_date));
		$this->data['end_time'] = date("g:iA", strtotime($end_date));
		
		
		$start_time = new DateTime($this->data['values']->exam_start_time);
        $end_time = new DateTime($this->data['values']->exam_end_time);
        
		$this->data['started_at'] = date("g:iA", strtotime($this->data['values']->exam_start_time));
		$this->data['ended_at'] = date("g:iA", strtotime($this->data['values']->exam_end_time));
		
        $diff = $end_time->diff($start_time);
        $this->data['duration_taken'] = $diff->format('%H:%I:%S');

// 		$active=$this->data['values']->active;

        $vlinesdata = \DB::table('t_exam_lines_tbl')
                    ->select('t_exam_lines_tbl.*','t_exam_questions_lines_tbl.question','t_exam_questions_lines_tbl.answer as actual_answer','t_exam_questions_lines_tbl.text_answer as actual_text_answer','t_exam_questions_lines_tbl.option1','t_exam_questions_lines_tbl.option2','t_exam_questions_lines_tbl.option3','t_exam_questions_lines_tbl.option4')
                    ->leftjoin('t_exam_questions_lines_tbl','t_exam_questions_lines_tbl.exam_questions_line_id','=','t_exam_lines_tbl.exam_questions_line_id')
                    ->where('t_exam_lines_tbl.exam_hdr_id', $id)->get();

        $location=$this->data['values']->location_id;
        $locationid=DB::table("m_location_t")->where('location_id',$location)->get();
      
        $this->data['location_name']=$locationid[0]->location_name; 
		
		
		$user = \DB::table('tb_users')->where('id',$this->data['values']->created_by)->get();
		if(count($user)>0){
		$this->data['created_by'] = $user[0]->username;
		}
		else{
			$this->data['created_by'] = '';
		}

            $this->data['vlinesdata'] = $vlinesdata;
            
            $this->data['active']='Yes';
		
// dd($this->data);
		return view('Exam.validateexam',$this->data);
	}
	/* Delete  data function*/
	public function delete(Request $request,$id=null,$type=null)
    {
	     if($type=="Sales")
        {
        $column = array('ar_frieghtcarriers_hdr_id','frieghtcarriers_hdr_id','freight_carrier_id','ar_frieghtcarriers_hdr_id');
        $table = array('m_customers_t','s_quote_hdr_t','s_salesorder_hdr_t','s_invoice_hdr_t');
        }
        else
        {
        $column = array('freight_carrier_id','freight_carrier_id');
        $table = array('p_quotation_hdr_t','p_po_hdr_t');   
        }
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
        if($j==0){
            Arfreightcarriershdr::destroy($id);

            /**Auditlog**/
			$action = "Delete";
			$this->auditlog($id,"FREIGHT CARRIERS",$action,$id,"m_frieghtcarriers_hdr_t");
            $query = \DB::table('m_frieghtcarriers_lines_t')->where('ar_frieghtcarriers_hdr_id',$id)->delete();
        }
     return $j;
    }

    public function locationget()
    {
		 $location=\Session::get('location');
			$comp=\Session::get('companyid');
            $company=\DB::table('m_company_line_t')->where('companyid',$comp)->get();
            
			if(count($company)>0){
				$c='';
				foreach($company as $k=>$y){
					$c.=$y->locationid.",";
				}
				
				$c=rtrim($c,',');

			return $c;
			}else{
				return 0;
			}
	}

	function findPrimarykey( $table )
	{
		$primaryKey = '';
		foreach(\DB::select("show columns from ".$table." where extra like '%auto_increment%'") as $key)
		{
			$primaryKey = $key->Field;
		}
		return $primaryKey;
	}
	function findPrimarykeylines( $tablelines )
	{
		$primaryKey = '';
		foreach(\DB::select("show columns from ".$tablelines." where extra like '%auto_increment%'") as $key)
		{
			$primaryKey = $key->Field;
		}
		return $primaryKey;
	}
	function validateForm($request=null)
	{
	$form_config=json_decode(urldecode($request['form_data_json']),true);
	$forms = array();
	$forms['header']=$form_config['header'];
	$forms['lines']=$form_config['lines'];
	$rules = array();
	foreach($forms as $form_type=>$form_type_val)
	{
		foreach($form_type_val as $form_field_name=>$form)
		{
			if($form['required']=='')
			{
				$rules[$form_type][$form['field']] = 'required';
			}
			elseif ($form['required'] == 'alpa')
			{
				$rules[$form_type][$form['field']] = 'required|alpa';
			}
			elseif ($form['required'] == 'alpa_num')
			{
				$rules[$form_type][$form['field']] = 'required|alpa_num';
			}
			elseif ($form['required'] == 'alpa_dash')
			{
				$rules[$form_type][$form['field']]='required|alpa_dash';
			}
			elseif ($form['required'] == 'email')
			{
				$rules[$form_type][$form['field']] ='required|email';
			}
			elseif ($form['required'] == 'numeric')
			{
				$rules[$form_type][$form['field']] = 'required|numeric';
			}
			elseif ($form['required'] == 'date')
			{
				$rules[$form_type][$form['field']]='required|date';
			}
			else if($form['required'] == 'url')
			{
				$rules[$form_type][$form['field']] = 'required|active_url';
			}
			else
			{

			}

		}

	}

	return $rules;
}

public function carrierchck(Request $request)
{
  
  $ar_frieghtcarriers_hdr_id = $_GET['ar_frieghtcarriers_hdr_id']; 
  $source_type_id = $_GET['source_type_id']; 
    
    if($ar_frieghtcarriers_hdr_id=='')
    {
      $frcarriersdata=DB::table('m_frieghtcarriers_hdr_t')->where('carrier_name',$_GET['carrier_name'])->where('source_type_id',$_GET['source_type_id'])->get();
    }
    else
    {
      $whereData = [['carrier_name', $_GET['carrier_name']],['ar_frieghtcarriers_hdr_id', '!=', $ar_frieghtcarriers_hdr_id]];
        $frcarriersdata=DB::table('m_frieghtcarriers_hdr_t')->where($whereData)->where('source_type_id',$_GET['source_type_id'])->get();
    } 
    if(count($frcarriersdata)>0)
            return 1;
        else
            return 0;
         
}
	public function freightcarnamechk($frightcarhdrid=null){
	
		$yes = 0;
		if($frightcarhdrid!=""){
			$tablesCheck=[];
			$tableNew['table_name']="p_quotation_hdr_t";
			$tableNew['column_name']="freight_carrier_id";
			$tableNew['value']=$frightcarhdrid;
			$tablesCheck[]=$tableNew;
                        
                        $tableNew['table_name']="p_po_hdr_t";
			$tableNew['column_name']="freight_carrier_id";
			$tableNew['value']=$frightcarhdrid;
			$tablesCheck[]=$tableNew;
                        
                        $tableNew['table_name']="m_customers_t";
			$tableNew['column_name']="ar_frieghtcarriers_hdr_id";
			$tableNew['value']=$frightcarhdrid;
			$tablesCheck[]=$tableNew;
                        
                        $tableNew['table_name']="s_quote_hdr_t";
			$tableNew['column_name']="frieghtcarriers_hdr_id";
			$tableNew['value']=$frightcarhdrid;
			$tablesCheck[]=$tableNew;

			$tableNew['table_name']="s_salesorder_hdr_t";
			$tableNew['column_name']="freight_carrier_id";
			$tableNew['value']=$frightcarhdrid;
			$tablesCheck[]=$tableNew;
                        
                        $tableNew['table_name']="s_invoice_hdr_t";
			$tableNew['column_name']="ar_frieghtcarriers_hdr_id";
			$tableNew['value']=$frightcarhdrid;
			$tablesCheck[]=$tableNew;
                        

			foreach($tablesCheck as $tableToCheck){
				if($yes!=1){
					$frieght = \DB::select("select ".$tableToCheck['column_name']." from ".$tableToCheck['table_name']." where ".$tableToCheck['column_name']."=".$tableToCheck['value']);
					
					if(count($frieght)>0){
						$yes = 1;
					}
				}
			}
		}
		return $yes;
	}
	public function examresultview($id=null)
		{
	    
		$this->data['urlname'] = \Request::route()->getName();
        $this->data['index_data']="ExamResults";
	    
	    $this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("t_schedule_exam_hdr_tbl");
		$hdr_data = \DB::table('t_exam_hdr_tbl')->select('t_exam_hdr_tbl.*','t_topic_tbl.topic_name','hr_employee_t.first_name','t_schedule_exam_lines_tbl.schedule_exam_hdr_id')
		                        ->leftjoin('t_topic_tbl','t_topic_tbl.topic_id','=','t_exam_hdr_tbl.topic_id')
		                        ->leftjoin('t_schedule_exam_lines_tbl','t_schedule_exam_lines_tbl.schedule_exam_line_id','=','t_exam_hdr_tbl.schedule_exam_line_id')
		                        ->leftjoin('hr_employee_t','hr_employee_t.employee_id','t_exam_hdr_tbl.employee_id')
		                        ->where('t_exam_hdr_tbl.exam_hdr_id',$id)->get();
		$this->data['values'] = $hdr_data[0];
// 		dd($this->data['values']);
		$str_date = $this->data['values']->start_time;
		$end_date = $this->data['values']->end_time;
		
		$date_time_format = \Session::get('p_date_format').' '.\Session::get('p_time_format');

// 		$this->data['start_time'] = date($date_time_format, strtotime($str_date));
// 		$this->data['end_time'] = date($date_time_format, strtotime($end_date)); 	
		
		$this->data['schedule_date'] = date('d-m-Y',strtotime( $this->data['values']->exam_date));
		$this->data['start_time'] = date("g:iA", strtotime($str_date));
		$this->data['end_time'] = date("g:iA", strtotime($end_date));
		
		
		$start_time = new DateTime($this->data['values']->exam_start_time);
        $end_time = new DateTime($this->data['values']->exam_end_time);
        
		$this->data['started_at'] = date("g:iA", strtotime($this->data['values']->exam_start_time));
		$this->data['ended_at'] = date("g:iA", strtotime($this->data['values']->exam_end_time));
		
        $diff = $end_time->diff($start_time);
        $this->data['duration_taken'] = $diff->format('%H:%I:%S');

// 		$active=$this->data['values']->active;

        $vlinesdata = \DB::table('t_exam_lines_tbl')
                    ->select('t_exam_lines_tbl.*','t_exam_questions_lines_tbl.question','t_exam_questions_lines_tbl.answer as actual_answer','t_exam_questions_lines_tbl.text_answer as actual_text_answer','t_exam_questions_lines_tbl.option1','t_exam_questions_lines_tbl.option2','t_exam_questions_lines_tbl.option3','t_exam_questions_lines_tbl.option4')
                    ->leftjoin('t_exam_questions_lines_tbl','t_exam_questions_lines_tbl.exam_questions_line_id','=','t_exam_lines_tbl.exam_questions_line_id')
                    ->where('t_exam_lines_tbl.exam_hdr_id', $id)->get();

        $location=$this->data['values']->location_id;
        $locationid=DB::table("m_location_t")->where('location_id',$location)->get();
      
        $this->data['location_name']=$locationid[0]->location_name; 
		
		
		$user = \DB::table('tb_users')->where('id',$this->data['values']->created_by)->get();
		if(count($user)>0){
		$this->data['created_by'] = $user[0]->username;
		}
		else{
			$this->data['created_by'] = '';
		}

            $this->data['vlinesdata'] = $vlinesdata;
            
            $this->data['active']='Yes';
		
// dd($this->data);
		return view('Exam.examresultview',$this->data);
	}


}
