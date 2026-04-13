<?php

namespace App\Http\Controllers;

use App\Attendancesync;
use Illuminate\Http\Request;
use DateTime;
use PDOException;
use Session;
use Yajra\DataTables\DataTables;

class AttendancesyncController extends Controller
{
  public function __construct()
	{
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs(); 
	 }
	
         //index page load function
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

    
        return view('attendancesync.table',$this->data);
    }



// attenndace sync function
	
    public function attedancesync($param = null) 
    {
          $connectionInfo = array( "UID" => "", "PWD" => "","DATABASE"=>"cosec","ReturnDatesAsStrings"=>true);   
        /* Connect using SQL Server Authentication. */    
        $conn = sqlsrv_connect( "122.185.116.122:3388\SQLEXPRESS", $connectionInfo);
        if (!$conn) {
               die( print_r( sqlsrv_errors(), true));
        }

	$connection=mysqli_connect('jrkaspire.com','jrkasaae_jaspireerp','?kHbI8d9ZeQ4','jrkasaae_jaspireerp');

	$employee_data=mysqli_query($connection,'select * from hr_employee_t where active="Yes"');

	error_reporting(0);

	while($row=mysqli_fetch_array($employee_data))
	{
	$emp_number=$row['biometric_empno'];

    $emp_id=$row['employee_id'];

    $employee_number=$row['employee_number'];

    $start_date=new DateTime(date('Y-m-d'));
    $end_date=new DateTime(date('Y-m-d'));

    while($start_date <= $end_date) 
    {
    $date=(string) $start_date->format('Y-m-d');

 $start_time = sqlsrv_query($conn,"SELECT top 1 [UserID],[Edatetime] FROM [cosec].[dbo].[Mx_ATDEventTrn] where convert(date,[Edatetime])='$date' and [UserId]='$emp_number' order by [Edatetime] asc");


 $end_time = sqlsrv_query($conn,"SELECT top 1 [UserID],[Edatetime] FROM [cosec].[dbo].[Mx_ATDEventTrn] where convert(date,[Edatetime])='$date' and [UserId]='$emp_number' order by [Edatetime] desc");

       while( $start_time =sqlsrv_fetch_array($start_time))
	   {
		
		$check_in=$start_time['Edatetime'];
		
	   }
	   while( $end_time =sqlsrv_fetch_array($end_time))
	   {
		
		$check_out=$end_time['Edatetime'];


$check=mysqli_query($connection,"select * from hr_emp_attendence where bio_id='$emp_number' and date(atten_date)='$date'");
$val=mysqli_fetch_array($check);
if(isset($val['atten_id']))
{
	$id=$val['atten_id'];

	mysqli_query($connection,"update hr_emp_attendence set check_in='$check_in',check_out='$check_out' where atten_id='$id'");
}
else
{
	$check=mysqli_query($connection,"insert into hr_emp_attendence(bio_id,emp_id,atten_date,check_in,check_out,company_id,location_id) values('$emp_number','$employee_number','$date','$check_in','$check_out','1','1')");

  if (!$check) {
               die( print_r( mysqli_error($connection), true));
        }

}

	   }
    $start_date->modify('+1 days');
    }

   }


        if (!$connection) {
               die( print_r( mysql_error(), true));
        }

    
        if ($param != null) 
		{
            return "Sync Successfully";
        }
		else
		{
            if ($data == "success") 
			{
                return Redirect::to($this->data['pageModule'])->with('messagetext', 'Sync Successfully')->with('msgstatus', 'success');
            } 
			else
			{
                return Redirect::to($this->data['pageModule'])->with('messagetext', 'Already Synced')->with('msgstatus', 'success');
            }
        }
    }
	
	
	
	
	
	// insert in attenndace table
	function getInsert($begin = null, $end = null, $filename = null) 
	{
            $connStr = 'odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)}; Dbq=C:\xampp\htdocs\candour\employee_log.accdb;';
            $dbh = new \PDO($connStr);
            $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $intime = "SELECT * FROM emp_logs";
            $details = $dbh->prepare($intime);
            $details->execute();
            $detail = $details->fetch();
            $shift_id =array();
            $shift_time =  array();
            $timing = array();
		
        if($detail)
        {
            $val = 1;
        }
        else
        {
            $val = 0;
        }
        if ($val == 1) 
        {
            $start_time1='';
            while ($begin <= $end) 
            {
                $emp_ids = \DB::select("select * from hr_employee_t ");
                
                foreach ($emp_ids as $emp_id) 
                {
                    $date   =   (string) $begin->format("Y-m-d");
                    $month1 =   (string) $begin->format('n');
                    $year   =   (string) $begin->format('Y');
                    $employee_id = $emp_id->employee_number;
                    $employee_id1 = $emp_id->employee_id;
                    $date_modify = date('Y-m-d',strtotime($date . "+1 days"));;
                    $day2        = $date_modify;
                    $n_date = $day2;
                    $emp_id      = $emp_id->biometric_empno;    
                   
                    $week_no=$this->weeknumber($date); 
                    $date_explode = explode('-',$date);
                    $y1 =   $date_explode[0];
                    $m1 =   $date_explode[1];
                    $d1 =   $date_explode[2];
                    $d1 =   $d1-1;
                   
                    $query2   = \DB::select("select * from employee_shift_details1 where employee_id='$employee_id1'");
                    $list_of_timig  = json_decode($query2[0]->week1);
                    $shift_type  = $list_of_timig[$d1][0];
                    $query3 = \DB::select("select * from shift_details where shift_name='$shift_type'");
                    
                    if($shift_type  == "general")
                    {
                        $start_time = $query3[0]->start_time;
                        $end_time   = $query3[0]->end_time;
                        $test1      = strtotime("$date $start_time");
                        $test2      = strtotime("$date $end_time"); 
                        
                        $intime     = "SELECT MIN(LogDate) as intime, MAX(LogDate) as outtime FROM emp_logs WHERE EmployeeCodInDevice='$employee_id' AND `LogDate` LIKE '$date%'";
                        
                        $details    = $dbh->prepare($intime);
                        $details->execute();
                        $detail     = $details->fetch();
                    }
                    else
                    {
                        $start_time = $query3[0]->start_time;
                        $end_time   = $query3[0]->end_time;
                        
                        $test1      = strtotime("$date $start_time");
                        $test2      = strtotime("$date $end_time");
                        
                        $intime     = "SELECT MIN(LogDate) as first_log_time1, MAX(LogDate) as first_log_time2 FROM emp_logs WHERE EmployeeCodInDevice='$employee_id' AND `LogDate` LIKE '$date%'";
                        $intime1     = "SELECT MIN(LogDate) as second_log_time1, MAX(LogDate) as second_log_time2 FROM emp_logs WHERE EmployeeCodInDevice='$employee_id' AND `LogDate` LIKE '$n_date%'";
                        
                        $details    = $dbh->prepare($intime);
                        $details->execute();
                        $detail     = $details->fetch();
                       
                        $start_time1 = $detail['first_log_time1'];
                        $end_time1 = $detail['first_log_time2'];

                        $start_time = strtotime("$date $start_time");
                        $end_time = strtotime("$n_date $end_time");
                        
                        
                        $details1    = $dbh->prepare($intime1);
                        $details1->execute();
                        $detail1     = $details1->fetch();
                        
                        $start_time2    = $detail1['second_log_time1'];
                        $end_time2      = $detail1['second_log_time2'];

                        $firt_timing    = strtotime("$date $start_time");
                        $test2          = strtotime("$date $end_time");
                        
                        $detail['intime']  =  $end_time1;
                        $detail['outtime'] =$start_time2;
                    }                  
                    
                    $data['emp_id']     = $employee_id;
                    $data['atten_date'] = $begin;
                    $data['check_in']   = $detail['intime'];
                    $data['check_out']  = $detail['outtime'];
                    $data['upload_status'] = 1;
                    $data['month']      = $month1;
                    $data['year']       = $year;                       
                    $to_time            = strtotime($data['check_out']);
                    $from_time          = strtotime($data['check_in']);
                    $data['working_hours'] = $time = $to_time - $from_time;
                    $val = "success";
                    
                   $id= \DB::table('emp_attendence')->insertGetId($data);
                      // auditlog
              $this->auditlog($id,"attendancesync","create",$data,"emp_attendence");
                }
                $begin->modify('+1 day');
            }
        }
        return $val;
			
	}
	// week number get function
	public function weeknumber($date)
	{

            $week_name=array("1"=>"first","2"=>"second","3"=>"third","4"=>"fourth","5"=>"fifth");
            $firstOfMonth = date("Y-m-01", strtotime($date));
            $Month =(string) date("m", strtotime($date));
            $Date =(string) date("d", strtotime($date));

            if($Month=="01" && $Date!="01")
                $week_month=intval(date("W", strtotime($date)));
            else
                $week_month=intval(date("W", strtotime($date))) - intval(date("W", strtotime($firstOfMonth)))+1;
                return $week_name[$week_month];

	}
	
	// attendance grid data
    public function attendancedetailsgriddata(Request $request)
    {

	$SQL = "SELECT
        hr_emp_attendence.atten_id,
        hr_emp_attendence.emp_id,
        hr_emp_attendence.atten_date,
        hr_emp_attendence.bio_id,
        hr_emp_attendence.check_in,
        hr_emp_attendence.check_out,
        hr_emp_attendence.working_hours,
        hr_emp_attendence.ot,
       concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name)as first_name,
      a_lookuplines_t.lookup_code
            FROM
        hr_emp_attendence
            LEFT JOIN hr_employee_t ON hr_employee_t.employee_number = hr_emp_attendence.emp_id
            LEFT JOIN a_lookuplines_t ON a_lookuplines_t.lookuplines_id = hr_emp_attendence.atten_type
        where 1=1 ";
		
	$data = \DB::select($SQL);
	return DataTables::of($data)->make(true);	

    }
}
