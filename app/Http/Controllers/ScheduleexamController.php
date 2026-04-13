<?php
namespace App\Http\Controllers;
use App\Scheduleexamhdr;
use App\Scheduleexamlines;
use Illuminate\Http\Request;
use Validator,DB,session;
use Config;
use Yajra\DataTables\DataTables;
use Datetime;

class ScheduleexamController extends Controller
{
  
	public $module="Scheduleexamhdr";

	public function __construct()
	{
		$this->data=array();
               
		$this->table="t_schedule_exam_hdr_tbl";
		$this->subtable="t_schedule_exam_lines_tbl";
		$this->pageModule="Scheduleexamhdr";
		$this->model=new Scheduleexamhdr;
		$this->submodel=new Scheduleexamlines;
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
		if($this->data['pageMethod']=="scheduletrainingfromrequest"){
        return view('Scheduletraining.reqtable',$this->data);
		}else{
		  return view('Scheduleexam.table',$this->data);   
		}
    }

    /* JQgrid start */
public function getscheduleexamgriddata(Request $request)
{
        $data = \DB::table('t_schedule_exam_hdr_tbl')
            ->leftJoin('t_topic_tbl', 't_topic_tbl.topic_id', '=', 't_schedule_exam_hdr_tbl.topic_id')
            ->select([
                't_schedule_exam_hdr_tbl.schedule_exam_hdr_id',
                't_schedule_exam_hdr_tbl.schedule_date',
                't_schedule_exam_hdr_tbl.schedule_type',
                't_schedule_exam_hdr_tbl.remarks',
                't_topic_tbl.topic_name',
            ]);

        return DataTables::of($data)
            ->make(true);

}
/* End */

    
    public function getexamemployeelistbytopic($id){
        $employees = DB::table('t_schedule_training_hdr_tbl')
                        ->select('t_schedule_training_lines_tbl.employee_id')
                        ->leftjoin('t_schedule_training_lines_tbl','t_schedule_training_lines_tbl.schedule_training_hdr_id','=','t_schedule_training_hdr_tbl.schedule_training_hdr_id')
                        ->where('t_schedule_training_hdr_tbl.topic_id',$id)->where('t_schedule_training_hdr_tbl.need_exam','Yes')->get();
        // dd($employees);
        $condition = ' 1=1 ';
        $html = '';
        $compy=\Session::get('companyid');
        if(count($employees)>0){
            foreach($employees as $val){
                $emp[] = $val->employee_id;
            }
        
        $emps=implode("','",$emp);
        $condition.= " and employee_id in ('".$emps."')";
        }else{
            $emps="('')";
            $condition.= " and employee_id in ('".$emps."')";
        }
         $employee_data = DB::select("SELECT `employee_number` as emp_number,hr_employee_t.`first_name` as employee_name,hr_employee_t.`employee_id` from hr_employee_t where $condition and company_id='$compy' and active='Yes'");
                
                    foreach($employee_data as $key=>$value){
                    $html.= '<tr class="rcopy clone" >
                            <td></td>
                            <td>
                                <input type="hidden" name="bulk_schedule_exam_line_id[]" id="bulk_schedule_exam_line_id" class=" form-control input-sm bulk_schedule_exam_line_id"  value="" >
                                <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no " value="'.($key+1).'" readonly="readonly">
                            </td>
                            <td>
                                <input type="text" name="bulk_employee_number[]" id="bulk_employee_number" class="form-control input-sm bulk_employee_number " value="'.$value->emp_number.'" readonly="readonly">
                            </td>
                            <td>
                                <input type="hidden" name="bulk_employee_id[]" id="bulk_employee_id" class="form-control input-sm bulk_employee_id " value="'.$value->employee_id.'" readonly="readonly">
                                <input type="text" name="bulk_employee_name[]" id="bulk_employee_name" class="form-control input-sm bulk_employee_name " value="'.$value->employee_name.'" readonly="readonly">
                                
                            </td>
                              <td>
                                   <input type="checkbox" name="bulk_check[]" class="form-control  bulk_check" value="'.$value->employee_id.'">
                                   <!--<input type="text" name="bulk_from_date[]" class="form-control datepicker input-sm bulk_from_date " value="" required="required">-->
                              </td>
                            <td style="display:none;">
                                <input type="text" name="bulk_remarks[]" id="bulk_remarks" class=" form-control input-sm bulk_remarks"  value="" >
                            </td>
                            <td style="display:none;">
                                        <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                                        <input type="hidden" name="counter[]">
                            </td>
                        </tr>';
                    }
        
        // $options = $this->jcustomselect('hr_employee_t','employee_id','employee_number|first_name','',$condition);
        
        
        return $html;
    }


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
  		$urlName = \Request::route()->getName();
		$this->data['pageMethod']="scheduleexam";
		$compy=\Session::get('companyid');
		if($id == '0')
		{
		    $this->data['row'] = (object) array();
		   
		     $this->data['row']->schedule_type= "Manual"; 
		     $this->data['topic_id'] = $this->jCombo('t_topic_tbl','topic_id','topic_name','');
		     	$this->data['row']->reference_id = "";
		     	$this->data['linedata'] = array();
		     	$this->data['employee_id'] = DB::select("SELECT `employee_number` as emp_number,hr_employee_t.`first_name` as employee_name,hr_employee_t.`employee_id` from hr_employee_t where company_id='$compy' and active='Yes'");
		    
	 		
	 		$this->data['row']->schedule_exam_hdr_id = "";
			$this->data['row']->schedule_date= "";
			$this->data['row']->start_time = "";
			$this->data['row']->end_time = "";
		 	$this->data['row']->remarks = "";
			$this->data['row']->active = "";
		    $this->data['pagemode'] = 'create';
			
	       // $this->data['employee_id'] = $this->jCombo('hr_employee_t','employee_id','first_name|last_name','');
	       //$this->data['employee_id'] = '';
	        $this->data['result'] = '';
	        
		    
		} else {

			$this->data['id'] = $id; 
			$this->data['pagemode'] = 'edit';
        	$table = \DB::table('t_schedule_exam_hdr_tbl')->where('schedule_exam_hdr_id',$id)->get();
          	$this->data['row'] =$table[0] ;
          	 $this->data['topic_id'] = $this->jCombo('t_topic_tbl','topic_id','topic_name',$table[0]->topic_id);
          	$linestable = \DB::table('t_schedule_exam_lines_tbl')->where('schedule_exam_hdr_id',$id)->get();
	 		$this->data['linedata'] = $linestable;
            foreach($this->data['linedata']  as $key=>$value)
            {			
            $this->data['linedata'][$key]->employee_id= $this->jCombo('hr_employee_t','employee_id','first_name|last_name',$value->employee_id);
		    }

			
	}
        // dd($this->data);
	return view('Scheduleexam.form',$this->data);

    }

/* Save function */
public function save(Request $request)
        { 
        
            $data = $this->validatePost($request->all(),$this->table,'header');
            
            // $lines_data = $this->validatePost($request->all(),$this->subtable,'lines');
	       \DB::beginTransaction();
        //   unset($lines_data['line_no']);
        //   dd($data);
            try
            {
                // dd($_POST);
            	$id=$this->model->insertRow($data);
				// $lid=$this->submodel->subgridSave($lines_data,$id);
				
				
				 foreach($_POST['bulk_employee_id'] as $key=>$value){
                        $lines_data['schedule_exam_hdr_id'] = $id;
                       // dd("bvbn");
                          if(in_array($value,$_POST['bulk_check'])){
                              $lines_data1['emp_id'][$key] = $_POST['bulk_employee_id'][$key] ;
                                $lines_data['schedule_exam_line_id']  = $_POST['bulk_schedule_exam_line_id'][$key];
                                // $lines_data['line_no']=$key+1;
                                $lines_data['employee_id'] = $_POST['bulk_employee_id'][$key] ;
                                // $lines_data['exam_attend'] = '0' ;
                                $lines_data['created_at'] = date('Y-m-d');
                                $lines_data['updated_at'] = date('Y-m-d');
                                $lines_data['organization_id'] = \Session::get("organization");
                                $lines_data['company_id'] = \Session::get("companyid");
                                $lines_data['location_id'] = \Session::get("location");
                                $lines_data['created_by'] = \Session::get("id");
                                $lines_data['last_updated_by'] =\Session::get("id");
                                
                                if($lines_data['schedule_exam_line_id'] ==''){
                                    
                                    $lid = DB::table('t_schedule_exam_lines_tbl')->insertGetId($lines_data);    
                                }else{
                                    // dd($lines_data);
                                    $lid = DB::table('t_schedule_exam_lines_tbl')->where('schedule_exam_line_id',$lines_data['schedule_exam_line_id'] )->update($lines_data);    
                                }
                                // print_r($lines_data); 
                          }
                          
                       
                   }
if(!empty(\Session::get('user_email'))&&!empty(\Session::get('user_password'))){
                Config::set('mail.username', \Session::get('user_email'));
                Config::set('mail.password', \Session::get('user_password'));
}

/**mail for trainer**/
/*if($_POST['trainer_name']!=""){
$emmail=\DB::select('select email,first_name from hr_employee_t where employee_id='.$_POST['trainer_name']);
}else{
  $employeemail1=$_POST['trainer_mail'];
}
$topic=\DB::select('select topic_name from t_topic_tbl where topic_id='.$data['topic_id']);
$employeemail1=$emmail[0]->email;
if($employeemail1!="" && $employeemail1!="-"){
    $this->data['employeemail1']=$employeemail1;
    $this->data['name1']=$emmail[0]->first_name;
    $this->data['topic1']=$topic[0]->topic_name;
    $this->data['date1']=date('d-m-Y',strtotime($data['schedule_date']));
    $stime=date("g:iA", strtotime($data['start_time']));
    $this->data['time1']=$stime;
           $dd=    \Mail::send('Scheduletraining.view',$this->data, function($message)
            {
               $cc=array();
           $msg="Dear ".$this->data['name1'].",\n Training Scheduled for Topic - ".$this->data['topic1']." on ".$this->data['date1']." at ".$this->data['time1'];

      $m=date('m');
           $message->to($this->data['employeemail1']);
        $message->from(\Session::get('user_email'));
           $message->subject("Reg- Training Schedule");
           $message->setBody($msg);
          
            });
   }*/
/** end**/
   foreach($lines_data1['emp_id'] as $k=>$v)  {
$empmail=\DB::select('select email,first_name from hr_employee_t where employee_id='.$v);
$topic=\DB::select('select topic_name from t_topic_tbl where topic_id='.$data['topic_id']);
$employeemail=$empmail[0]->email;
if($employeemail!="" && $employeemail!="-"){
    
     $this->data['values'] = (object) array();
    
    $this->data['values']->topic_name = $topic[0]->topic_name;
    $this->data['values']->remarks = $_POST['remarks'];
    $this->data['values']->reference_id = $_POST['reference_id'];
    
    $this->data['employeemail']=$employeemail;
    $this->data['name']=$empmail[0]->first_name;
    $this->data['topic']=$topic[0]->topic_name;
    $this->data['date']=date('d-m-Y',strtotime($data['schedule_date']));
    $stime=date("g:iA", strtotime($data['start_time']));
    
    $this->data['schedule_date'] = date('d-m-Y',strtotime($data['schedule_date']));
    $this->data['start_time'] = $stime;
    $this->data['end_time'] = $stime;
    $this->data['created_by'] = '';
    $this->data['active'] = 'Yes';
    
   $this->data['vlinesdata'][0] = (object) array();
    $this->data['vlinesdata'][0]->first_name = $empmail[0]->first_name;
    
    $this->data['time']=$stime;
           $dd=    \Mail::send('Scheduleexam.view',$this->data, function($message)
            {
               $cc=array();
           $msg="Dear ".$this->data['name'].",\n Exam Scheduled for Topic - ".$this->data['topic']." on ".$this->data['date']." at ".$this->data['time'];

      $m=date('m');
           $message->to($this->data['employeemail']);
        $message->from(\Session::get('user_email'));
           $message->subject("Reg- Exam Schedule");
           $message->setBody($msg);
         //  $message->attach('Uploads/payslip/payslip_'.$m.'_'.stripslashes($this->data['employee_number']).'.pdf');
          
            });
   }
   }
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
	
	public function getedit($edit_id,$type)
	{
		echo $edit_id;
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

// 		$this->data['start_time'] = date($date_time_format, strtotime($str_date));
// 		$this->data['end_time'] = date($date_time_format, strtotime($end_date));
		$this->data['schedule_date'] = date('d-m-Y',strtotime( $this->data['values']->schedule_date));
		$this->data['start_time'] = date("g:iA", strtotime($str_date));
		$this->data['end_time'] = date("g:iA", strtotime($end_date));
// 		dd($this->data);
// dd(date("g:iA", strtotime($str_date)));
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


}
