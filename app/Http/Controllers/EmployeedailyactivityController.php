<?php

namespace App\Http\Controllers;

use App\employeedailyactivity;
use Illuminate\Http\Request;

class EmployeedailyactivityController extends Controller
{
		public function __construct()
	{
           $this->data=array();
           $this->table="hr_emp_daily_activity_t";
		   $this->model=new employeedailyactivity;
		   $this->pageModule="employeedailyactivity";
	       $this->data['pageModule']=$this->pageModule;
		   $this->data['pageMethod']=\Request::route()->getName();
           $this->data['pageFormtype']='ajax';
		$this->data=array(
                    'pageModule'=> 'empdailyactivity',
                    'pageUrl'	=>  url('empdailyactivity'),
                     'pageMethod'=>$this->data['pageMethod']
        );   
                $this->data['urlmenu']=$this->indexs(); 

	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('employeedailyactivity.table',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null)
    {
        	if($id!=0)
	{
        $dailyactivity =employeedailyactivity::find($id);
		$this->data['row']=$dailyactivity;
		 $this->data['employee_id']=$this->jcombo("hr_employee_t","employee_id","first_name|last_name",$this->data['row']->employee_id);
		}else{    
		$dailyactivity=\DB::connection()->getSchemaBuilder()->getColumnListing('hr_emp_daily_activity_t');
		$dailyactivitys=(object)array();
	foreach($dailyactivity as $key=>$value){
		$dailyactivitys->$value="";
	}
	     $this->data['row']=$dailyactivitys;
		 $this->data['employee_id']=$this->jcombo("hr_employee_t","employee_id","first_name|last_name","");
		 $this->data['row']->activity_date=date("d-m-Y");
	}
		$this->data['return_url']="empdailyactivity";
		return view('employeedailyactivity.form',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
		$data = $this->validatePost($request->all(),$this->table,'header');
             \DB::beginTransaction();
            try
            {
          if($data['emp_daily_activity_id']=="")
            $msg="Daily Activity Saved Successfully";
        else
            $msg="Daily Activity Updated Successfully";
            $id=$this->model->insertRow($data);
               \DB::commit();

                return response()->json(array('status' => 'success', 'message' => $msg,'id' => $id));
            }
            catch (\Illuminate\Database\QueryException$e)
            {
                $message = explode('(', $e->getMessage());
                $dbCode = rtrim($message[0], ']');
                $dbCode = trim($dbCode, '[');
                \DB::rollback();
             return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\employeedailyactivity  $employeedailyactivity
     * @return \Illuminate\Http\Response
     */
    public function show($id=null)
    {
       $this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("hr_emp_daily_activity_t");
		$this->data['values'] = employeedailyactivity::find($id);
		$sql=\DB::Select('select employee_id,concat(first_name,last_name) as emp_name from hr_employee_t where employee_id='.$this->data['values']['employee_id']);
	    $this->data['empname']=$sql[0]->emp_name;
		return view('employeedailyactivity.view',$this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\employeedailyactivity  $employeedailyactivity
     * @return \Illuminate\Http\Response
     */
    public function edit(employeedailyactivity $employeedailyactivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\employeedailyactivity  $employeedailyactivity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, employeedailyactivity $employeedailyactivity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\employeedailyactivity  $employeedailyactivity
     * @return \Illuminate\Http\Response
     */
    public function destroy(employeedailyactivity $employeedailyactivity)
    {
        //
    }
	public function getEmployeedailydata($id=null){
      // dd($_GET['status']);
    $wh='';
  

		if($_GET['_search']=='true'){
			$tables=array();
			$tables[]="hr_employee_t";
		$wh .=$this->jqgridsearch('hr_emp_daily_activity_t',$_GET['filters']);
		}



		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
		$result = \DB::select("SELECT COUNT(emp_daily_activity_id) AS count FROM hr_emp_daily_activity_t where 1=1 $wh");
//dd($result);
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
                $SQL = "SELECT
				hr_emp_daily_activity_t.*,
                     concat(hr_employee_t.first_name,' ',hr_employee_t.last_name) as empname
                        FROM `hr_emp_daily_activity_t`
                        left join hr_employee_t on(
                        hr_employee_t.employee_id=hr_emp_daily_activity_t.`employee_id`) where 1=1  $wh ORDER BY $sidx $sord LIMIT $start , $limit";
     //dd($SQL);


		$result = \DB::select( $SQL );
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);
	}
}
