<?php
namespace App\Http\Controllers;
use App\Examquestionshdr;
use App\Examquestionslines;
use Illuminate\Http\Request;
use Validator,DB,session;
use Config;
use Datetime;
use Yajra\DataTables\DataTables;

class ExamquestionsController extends Controller
{
  
	public $module="Examquestionshdr";

	public function __construct()
	{
		$this->data=array();
               
		$this->table="t_exam_questions_hdr_tbl";
		$this->subtable="t_exam_questions_lines_tbl";
		$this->pageModule="Examquestionshdr";
		$this->model=new Examquestionshdr;
		$this->submodel=new Examquestionslines;
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

		$this->data['urlname'] = \Request::route()->getName();
		$this->data['pageMethod']=\Request::route()->getName();
// 		if($this->data['pageMethod']=="scheduletrainingfromrequest"){
//         return view('Examquestions.reqtable',$this->data);
// 		}else{
		  return view('Examquestions.table',$this->data);   
// 		}
    }

    /* JQgrid start */
public function getexamquestionsgriddata(Request $request)
{

 if ($request->ajax()) {

        $data = \DB::table('t_exam_questions_hdr_tbl')
            ->leftJoin('t_topic_tbl', 't_topic_tbl.topic_id', '=', 't_exam_questions_hdr_tbl.topic_id')
            ->select([
                't_exam_questions_hdr_tbl.exam_questions_hdr_id',
                't_exam_questions_hdr_tbl.topic_id',
                't_exam_questions_hdr_tbl.department_id',
                't_exam_questions_hdr_tbl.remarks',
                't_exam_questions_hdr_tbl.department_topic_id',
                't_topic_tbl.topic_name',
            ]);

        return DataTables::of($data)
            ->make(true);
    }
	

}
/* End */
/**from request grid**/
public function getrequesttraininggriddata($type=null)
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
  		$urlName = \Request::route()->getName();
		$this->data['pageMethod']="examquestions";
		if($id == '0')
		{
		    $this->data['row'] = (object) array();
		    
		     
            $this->data['department_topic_id']=\DB::select("select t_department_topic_tbl.department_topic_id,m_department_lines_t.sub_department_name,
                                                t_department_topic_tbl.topic_id,t_department_topic_tbl.department_id,t_topic_tbl.topic_name 
                                                from t_department_topic_tbl 
                                                left join m_department_lines_t on m_department_lines_t.department_line_id=t_department_topic_tbl.department_id 
                                                left join t_topic_tbl on t_topic_tbl.topic_id=t_department_topic_tbl.topic_id ");
                                                
            $this->data['topic_id'] = $this->jCombo('t_topic_tbl','topic_id','topic_name','');
		     $this->data['row']->department_topic_id = "";
		     $this->data['row']->reference_id = "";
		     
		    
	 		$this->data['row']->exam_questions_hdr_id = "";
		    $this->data['pagemode'] = 'create';
			
	        $this->data['employee_id'] = $this->jCombo('hr_employee_t','employee_id','first_name|last_name','');
	        $this->data['linedata'] = array();
		    
		} else {
                
            $this->data['department_topic_id']=\DB::select("select t_department_topic_tbl.department_topic_id,m_department_lines_t.sub_department_name,
                                                t_department_topic_tbl.topic_id,t_department_topic_tbl.department_id,t_topic_tbl.topic_name 
                                                from t_department_topic_tbl 
                                                left join m_department_lines_t on m_department_lines_t.department_line_id=t_department_topic_tbl.department_id 
                                                left join t_topic_tbl on t_topic_tbl.topic_id=t_department_topic_tbl.topic_id ");
        
			$this->data['id'] = $id; 
			$this->data['pagemode'] = 'edit';
        	$table = \DB::table('t_exam_questions_hdr_tbl')->where('exam_questions_hdr_id',$id)->get();
          	$this->data['row'] =$table[0] ;
          	$this->data['topic_id'] = $this->jCombo('t_topic_tbl','topic_id','topic_name',$table[0]->topic_id);
          	$linestable = \DB::table('t_exam_questions_lines_tbl')->where('exam_questions_hdr_id',$id)->get();
	 		$this->data['linedata'] = $linestable;

	}
        // dd($this->data);
	return view('Examquestions.form',$this->data);

    }

/* Save function */
public function save(Request $request)
        { 
        
			$form = $request->all();
			$dataupload ="";
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
            $data['topic_id']=$_POST['topic_id'];
            $data['department_topic_id']='';
            $data['department_id']='';
            
            $lines_data = $this->validatePost($form, $this->subtable, 'lines');
	       \DB::beginTransaction();
           unset($lines_data['line_no']);
           
            try
            {

            	$id=$this->model->create($data);
				$lid=$this->submodel->subgridSave($lines_data,$id);

				\DB::commit();
				/**Auditlog**/
			$action="Create";
                return response()->json(array('status' => 'success', 'message' => "Saved Successfully",'id' => $id,'lid' => $lid));
            }
            catch (\Illuminate\Database\QueryException $e)
            {
                 $message = explode('(', $e->getMessage());
                 $dbCode = rtrim($message[0], ']');
                 $dbCode = trim($dbCode, '[');
                 \DB::rollback();
                 $action="Edit";
                 return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
            }

        }
	
	public function getedit($edit_id,$type)
	{
		echo $edit_id;
	}
	// View function
	public function show($id=null)
	{
		$this->data['urlname'] = \Request::route()->getName();
        $this->data['index_data']="Training";
	    
	    $this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("t_exam_questions_hdr_tbl");
		$hdr_data = \DB::table('t_exam_questions_hdr_tbl')->select('t_exam_questions_hdr_tbl.*','t_topic_tbl.topic_name','m_department_lines_t.subdepartment_name')
		                        ->leftjoin('t_topic_tbl','t_topic_tbl.topic_id','=','t_exam_questions_hdr_tbl.topic_id')
		                        ->leftjoin('m_department_lines_t','m_department_lines_t.department_line_id','=','t_exam_questions_hdr_tbl.department_id')
		                        ->where('t_exam_questions_hdr_tbl.exam_questions_hdr_id',$id)->get();
		$this->data['values'] = $hdr_data[0];
// 		dd($this->data['values']);
// 		$str_date = $this->data['values']->start_time;
// 		$end_date = $this->data['values']->end_time;
		
// 		$date_time_format = \Session::get('p_date_format').' '.\Session::get('p_time_format');

// 		$this->data['start_time'] = date($date_time_format, strtotime($str_date));
// 		$this->data['end_time'] = date($date_time_format, strtotime($end_date)); 	

// 		$active=$this->data['values']->active;

		$vlinesdata = \DB::table('t_exam_questions_lines_tbl')->select('t_exam_questions_lines_tbl.*')
                    -> leftjoin('tb_users','tb_users.id','=','t_exam_questions_lines_tbl.created_by')
                    ->where('t_exam_questions_lines_tbl.exam_questions_hdr_id', $id)->get();

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
		return view('Examquestions.view',$this->data);
	}
	/* Delete  data function*/
	public function delete(Request $request,$id=null,$type=null)
    {}

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



}
