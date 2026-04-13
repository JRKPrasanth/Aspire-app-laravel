<?php
namespace App\Http\Controllers;
use App\Scheduletraininghdr;
use App\Scheduletraininglines;
use App\Trainingfeedback;
use Illuminate\Http\Request;
use Validator,DB,session;
use Config;
use Datetime;
class TrainingfeedbackController extends Controller
{
  
	public $module="Scheduletraininghdr";

	public function __construct()
	{
		$this->data=array();
               
		$this->table="t_training_feedback";
		$this->pageModule="TrainingFeedback";
		$this->model=new Trainingfeedback;
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
    	$this->data['urlmenu']=$this->indexs(); 

	}
    public function index()
    {

		$this->data['urlname'] = \Request::route()->getName();
		$this->data['pageMethod']=\Request::route()->getName();
		if($this->data['pageMethod']=="scheduletrainingfromrequest"){
        return view('Scheduletraining.reqtable',$this->data);
		}else{
		  return view('Trainingfeedback.table',$this->data);   
		}
    }

    /* JQgrid start */
public function getscheduledtraininggriddata($type=null)
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
	   $SQL = "select * from(SELECT
t_schedule_training_hdr_tbl.`schedule_training_hdr_id`,
t_schedule_training_hdr_tbl.`schedule_date`,
t_schedule_training_hdr_tbl.`schedule_type`,
t_schedule_training_hdr_tbl.`remarks`,
t_schedule_training_hdr_tbl.`trainer_type`,
t_topic_tbl.`topic_name`,
(case when t_schedule_training_hdr_tbl.`trainer_type`='Internal' then (select hr_employee_t.first_name from hr_employee_t where hr_employee_t.employee_id=t_schedule_training_hdr_tbl.`trainer_name`) else t_schedule_training_hdr_tbl.`trainer_name` end) as trainer_name
FROM `t_schedule_training_hdr_tbl` 
left join t_topic_tbl on t_topic_tbl.topic_id = t_schedule_training_hdr_tbl.topic_id
where  1=1 and t_schedule_training_hdr_tbl.company_id=$com) as v1 where 1=1 $wh order by $sidx desc";
	
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
/* End */

	public function create(Trainingfeedback $trainingfeedback, $id=null)
	{
		$this->data['urlname'] = \Request::route()->getName();
        $this->data['index_data']="Training";
	 
		$vlinesdata = \DB::table('t_schedule_training_lines_tbl')->select('t_schedule_training_lines_tbl.*','hr_employee_t.first_name','hr_employee_t.email','tb_users.username','t_schedule_training_hdr_tbl.*','t_topic_tbl.topic_name')
                    ->leftjoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 't_schedule_training_lines_tbl.employee_id')
                    -> leftjoin('tb_users','tb_users.id','=','t_schedule_training_lines_tbl.created_by')
                    ->leftjoin('t_schedule_training_hdr_tbl','t_schedule_training_hdr_tbl.schedule_training_hdr_id','=','t_schedule_training_lines_tbl.schedule_training_hdr_id')
                    ->leftjoin('t_topic_tbl','t_topic_tbl.topic_id','=','t_schedule_training_hdr_tbl.topic_id')
                    ->where('t_schedule_training_lines_tbl.schedule_training_line_id', $id)->get();
    
    if(count($vlinesdata)>0){
                        
        // dd($vlinesdata);
        $location=$vlinesdata[0]->location_id;
        $locationid=DB::table("m_location_t")->where('location_id',$location)->get();
      
        $this->data['location_name']=$locationid[0]->location_name; 
		
		
		$user = \DB::table('tb_users')->where('id',$vlinesdata[0]->created_by)->get();
		if(count($user)>0){
		$this->data['created_by'] = $user[0]->username;
		}
		else{
			$this->data['created_by'] = '';
		}

            $this->data['vlinesdata'] = $vlinesdata[0];
            
            		$str_date = $this->data['vlinesdata']->start_time;
		$end_date = $this->data['vlinesdata']->end_time;
		
		$date_time_format = \Session::get('p_date_format').' '.\Session::get('p_time_format');

		$this->data['start_time'] = date($date_time_format, strtotime($str_date));
		$this->data['end_time'] = date($date_time_format, strtotime($end_date)); 

            $this->data['active']='Yes';
                    }else{
                        $this->data='';
                        
                    }
// dd($this->data);
		return view('Trainingfeedback.feedback',$this->data);
	}
    
    public function save(Request $request){
           
            // dd($_POST);
            $data['feedback_id'] = $_POST['feedback_id'];
            $data['employee_id'] = $_POST['employee_id'];
            $data['topic_id'] = $_POST['topic_id'];
            $data['topic_name'] = $_POST['topic_name'];
            
            for($i=0;$i<$_POST['count'];$i++){
                $j = $i+1;
                $key = $_POST['rate'.$j];
                $value = $_POST['rating'.$j];
                $feedback[$i] = $value;
                $feedback_title[$i] = $key;
            }
            $data['feedback_title'] = json_encode($feedback_title);
            $data['feedback'] = json_encode($feedback);
            $data['comment'] = $_POST['comment'];
            $data['feedback_date'] = date('Y-m-d');
            $data['created_by'] = date('Y-m-d');
            $data['location_id'] = \Session::get('location');
            $data['organization_id'] = \Session::get('organization');
            $data['company_id'] = \Session::get('companyid');
            $data['status'] = $_POST['employee_id'];
            
            // dd($data);
           
	       \DB::beginTransaction();
           
            try
            {
                
            	$id= \DB::table('t_training_feedback')->insert($data);
            	\DB::commit();
			//$this->auditlog($id,"FREIGHT CARRIERS",$action,$_POST,"m_frieghtcarriers_hdr_t");
                return response()->json(array('status' => 'success', 'message' => "Thanks for Your Feedback !!",'id' => $id));
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
     public function sendmail($id){
        
        if(!empty(\Session::get('user_email'))&&!empty(\Session::get('user_password'))){
                Config::set('mail.username', \Session::get('user_email'));
                Config::set('mail.password', \Session::get('user_password'));
        }

        // $hdr_data = \DB::table('t_schedule_training_hdr_tbl')->select('t_schedule_training_hdr_tbl.*','t_topic_tbl.topic_name')
		      //                  ->leftjoin('t_topic_tbl','t_topic_tbl.topic_id','=','t_schedule_training_hdr_tbl.topic_id')
		      //                  ->where('t_schedule_training_hdr_tbl.schedule_training_hdr_id',$id)->get();
// 		$this->data['values'] = $hdr_data[0];
// 		dd($this->data);
        // $employee_list = DB::table('t_schedule_training_lines_tbl')->where('schedule_training_hdr_id',$id)->get();
        $ldata = "SELECT  hr_employee_t.employee_id,hr_employee_t.employee_number,hr_employee_t.first_name,hr_employee_t.email,t_schedule_training_lines_tbl.schedule_training_line_id,
                (select t_topic_tbl.topic_name from t_topic_tbl where t_topic_tbl.topic_id = t_schedule_training_hdr_tbl.topic_id) as topic_name,
                t_schedule_training_hdr_tbl.schedule_date,t_schedule_training_hdr_tbl.start_time,t_schedule_training_hdr_tbl.end_time
                from t_schedule_training_lines_tbl
               left join hr_employee_t on hr_employee_t.employee_id=t_schedule_training_lines_tbl.employee_id
               left join t_schedule_training_hdr_tbl on t_schedule_training_hdr_tbl.schedule_training_hdr_id=t_schedule_training_lines_tbl.schedule_training_hdr_id
                where t_schedule_training_lines_tbl.schedule_training_hdr_id=$id";
                
                $vlinesdata = \DB::select($ldata);
                // dd($vlinesdata);
        foreach($vlinesdata as $k=>$v)  {
            
            $emp = $v->employee_id;
            
            $employeemail=$v->email;
            
            if($employeemail!="" && $employeemail!="-"){
                $this->data['employeemail']=$employeemail;
                $this->data['name']=$v->first_name;
                $this->data['name1']=$v->first_name;
                $this->data['topic']=$v->topic_name;
                $this->data['date']=date('d-m-Y',strtotime($v->schedule_date));
                $stime=date("g:iA", strtotime($v->start_time));
                $this->data['time']=$stime;
                $this->data['schedule_training_line_id']=$v->schedule_training_line_id;

                $this->data['employeemail']='vigneshs@ifive.in';
                $this->data['url'] = url('feedbackcreate').'/'.$this->data['schedule_training_line_id'];
                // dd($this->data);
                $dd= \Mail::send('Trainingfeedback.feedbackview',$this->data, function($message)
                {
                    $cc=array();
                    $msg="Dear ".$this->data['name'].",\n Training Feedback Survey - ".$this->data['topic']." on ".$this->data['date']." at ".$this->data['time'];
                    $msg="<table><body>
                            <tr><td>Dear ".$this->data['name'].",</td></tr>
                            <tr><td> Training Scheduled Details are given below </td></tr>
                            <tr><td> Topic : ".$this->data['topic1']."  </td></tr>
                            <tr><td> Date :".$this->data['date']." </td></tr>
                            <tr><td> Time :".$this->data['time']."</td></tr>
                            <tr><td> Trainer :".$this->data['name1']."</td></tr>
                            <tr><td><a href='".$this->data['url']."'><button style='color:red;margin:10px;border-radius:5;'>Click here to give feedback </button></a></td></tr></body></table>";
                    $m=date('m');
                    $message->to($this->data['employeemail']);
                    $message->from(\Session::get('user_email'));
                    $message->subject("Reg- Training Schedule");
                    $message->setBody($msg);
                   //  dd($message);
                 //  $message->attach('Uploads/payslip/payslip_'.$m.'_'.stripslashes($this->data['employee_number']).'.pdf');
                
                });
                
               
            }
        }

     }
	public function show(Scheduletraininghdr $Scheduletraininghdr, $id=null)
	{
		$this->data['urlname'] = \Request::route()->getName();
        $this->data['index_data']="Training";
	    
	    $this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("t_schedule_training_hdr_tbl");
		$hdr_data = \DB::table('t_schedule_training_hdr_tbl')->select('t_schedule_training_hdr_tbl.*','t_topic_tbl.topic_name')
		                        ->leftjoin('t_topic_tbl','t_topic_tbl.topic_id','=','t_schedule_training_hdr_tbl.topic_id')
		                        ->where('t_schedule_training_hdr_tbl.schedule_training_hdr_id',$id)->get();
		$this->data['values'] = $hdr_data[0];
// 		dd($this->data['values']);
		$str_date = $this->data['values']->start_time;
		$end_date = $this->data['values']->end_time;
		
		$date_time_format = \Session::get('p_date_format').' '.\Session::get('p_time_format');

		$this->data['start_time'] = date($date_time_format, strtotime($str_date));
		$this->data['end_time'] = date($date_time_format, strtotime($end_date)); 	

// 		$active=$this->data['values']->active;

        $ldata = "SELECT  t_training_feedback.*,hr_employee_t.employee_number,hr_employee_t.first_name
                from t_training_feedback
                left join t_schedule_training_hdr_tbl on t_schedule_training_hdr_tbl.topic_id=t_training_feedback.topic_id
                left join hr_employee_t on hr_employee_t.employee_id=t_training_feedback.employee_id
                where t_schedule_training_hdr_tbl.schedule_training_hdr_id=$id";
                
                $vlinesdata = \DB::select($ldata);
// 		$vlinesdata = \DB::table('t_schedule_training_lines_tbl')->select('t_schedule_training_lines_tbl.*','hr_employee_t.first_name','tb_users.username')
//                     ->leftjoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 't_schedule_training_lines_tbl.employee_id')
//                     ->leftjoin('t_training_feedback','t_training_feedback.topic_id','')
//                     -> leftjoin('tb_users','tb_users.id','=','t_schedule_training_lines_tbl.created_by')
//                     ->where('t_schedule_training_lines_tbl.schedule_training_hdr_id', $id)->get();


            // dd($vlinesdata);
            
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
		return view('Trainingfeedback.view',$this->data);
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
