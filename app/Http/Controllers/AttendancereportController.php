<?php

namespace App\Http\Controllers;

use App\attendancereport;
use Illuminate\Http\Request;
use DateTime;
use DB;
use Yajra\DataTables\DataTables;

class AttendancereportController extends Controller
{
    
	 public function __construct(){

        $this->data=array();
        $this->data['pageMethod']=\Request::route()->getName();
    
    }
	
    public function index()
    {
        return view('attendancemonthlyreport.attendancemonthly',$this->data);
    }
	public function attendancemonthlyreport()
	{	

		$emp_id = isset($_GET['emp_id']) && !empty($_GET['emp_id']) ? $_GET['emp_id'] : '' ;
		$year = isset($_GET['year']) && !empty($_GET['year']) ? $_GET['year'] : '' ;
		$month = isset($_GET['month']) && !empty($_GET['month']) ? $_GET['month'] : '' ;
		
	    $month = sprintf('%02d',$month);
        
		
	    $start_date=date($year.'-'.$month.'-01');
          
		$month1=date('m');
		$my_file = uniqid().'.xls';
		$file = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
		if($month==$month1)
	    {
                
			$end_date=date($year.'-'.$month.'-d',strtotime("-1 days"));
			$d=cal_days_in_month(CAL_GREGORIAN,$month,$year);
              
		}
		else
		{
			$d=cal_days_in_month(CAL_GREGORIAN,$month,$year);  
			$end_date=date($year.'-'.$month.'-'.$d);
		}
         
           
            $begin  = new DateTime($start_date);
            $end    = new DateTime($end_date);
           
	     $data ='';
		 $data ="
		 <div class='row'>
                  <h5 class='heads'>Attendance Report </h5>
					</div>
					<div class='col-md-12'>
		 <table class='table .table-hover'>"
                    . "<th width='30'>  Date </th>"
                    . "<th width='30'>  Check-In </th>"
                    . "<th width='30'>  Check-Out </th>"
                    . "<th width='30'>  Working Hours </th>"
                    . "<th width='30'>  Day </th>"
                    . "<th width='30'>  OT hours</th>" ; 
         
         
       
		 $holidays=\DB::select("SELECT * FROM `hr_holiday_t` WHERE  `year`='$year'");  
		 $total_days = $d;
		 $present_days = 0;
		 $absent_days = 0;
		 $sundays = 0;
		 $holidayss = 0;
		 $c_l= 0;
		 $s_l = 0;
		 $e_l = 0;
		 $a_l = 0;
		 $rowData='';
		 $setData='';
		 $val2='';
		
		 while ($begin <= $end)
		 {
            
			$color = "black";
			$val = "";
			$val1 = "";
		    $c_val ='';
			if($holidays)
			{
				foreach ($holidays as $holiday)
				{
                        $date=$begin->format('Y-m-d');
                        
                        if($date == $holiday->holiday_date)
                        {
                           $color="#bf80ff";
                           $val ="<b>".$holiday->description."</b>";
                           $val1 = "00:00:00";
                           $holidayss++;
                           //$present_days++;
                        }
			     }
			}
			if($val=="")
			{
				if($begin->format("D") == "Sun")
				{
					if($val=="")
					{
                          $class="sunday";
                          $val="<span class='sunday'>SUNDAY</span>";
                          $val1 = "00:00:00";
                          $c_val = "SUNDAY";
                          $sundays++;
                          //$present_days++;
					}
				}
			} 
			$date=(string)$begin->format("Y-m-d");
		
			$atten_detail=\DB::select("SELECT `check_in`,`check_out`,`working_hours` FROM `hr_emp_attendence` WHERE `emp_id`='001' AND `atten_date` like '$date%'");
			$query2 = \DB::select("select * from hr_employee_t where employee_number='001'");
			$employee_id = $query2[0]->employee_id;
                
                if(count($atten_detail)>0)
                {
                   
                    
                    $od_detail_querry=\DB::select("SELECT * FROM hr_leaves_t WHERE employee_id='$employee_id' AND DATE(start_date)='$date' AND DATE(end_date)='$date' AND leave_type='1959' AND leave_status='APPROVED'");
                    if(count($od_detail_querry)>0)
                        $od_hr =$od_detail_querry[0]->alloted_days_y;
                    else
                        $od_hr="00:00:00";
                    
                    if($val!="")
                    {
                       $holidayVal = "<span class='holiday'>".$holiday->description."</span>";
                       $od_hr="00:00:00";
                    }
                    else
                    {
                       $holidayVal ="";
                    }
                    
                    $value =date('H:i:s', $atten_detail[0]->working_hours); 
                    $value=strtotime($value); 
                    $time="09:00:00";
                    $to_time = strtotime($time); 
                    if($value < $to_time)
                        $ot= 000;
                    else
                       $ot  = $value - $to_time; 
					
					
                    
                    $data.="<tr style='color:".$color.";'>
							   <td>".$date."</td>"
                            . "<td>".$atten_detail[0]->check_in."</td>"
                            . "<td>".$atten_detail[0]->check_out."</td>"
                            . "<td>".date('H:i:s',$atten_detail[0]->working_hours)." </td>"
                            . "<td><span class='present'>PRESENT</span>".$holidayVal."</td>"
                            . "<td>".date("H:i:s",$ot)."</td>"
                            . "</tr>";
                    $val="";
                    $c_val="";
					
                    $value = '"' . $date.'"'."\t"
                            .'"'.$atten_detail[0]->check_in.'"'."\t"
                            .'"'.$atten_detail[0]->check_out.'"'."\t"
                            .'"'.date('H:i:s',$atten_detail[0]->working_hours).'"'."\t"
                            .'"PRESENT"'."\t"
                            .'"'.date("H:i:s",$ot).'"'."\t";
                            $rowData .= $value."\n";
                    		$present_days++;
                }
                else 
                {
				    $cas_detail=\DB::select("SELECT * FROM hr_leaves_t WHERE employee_id='$employee_id' AND (DATE(start_date)='$date' OR start_date='$date') AND (DATE(end_date)='$date'OR end_date='$date') AND leave_type='265' AND leave_status='APPROVED' ");
			      
                    if(count($cas_detail)>0)
                    {
                      $type_leave=$cas_detail[0]->type_leave;
                      if($type_leave==1)
                      {
						  $val="<span class='present'>HALFDAY-CASUAL LEAVE</span> ";
						  $val2="HALFDAY-CASUAL LEAVE";
						  $c_val="HALFDAY-CASUAL LEAVE";
						  $c_l_l=0.5;
						  $a_l_l=0.5;
						  $c_l=$c_l+$c_l_l;
						  $a_l=$a_l+$a_l_l;
                      }
                      else 
                      {
						  $val="<span class='present'>FULLDAY-CASUAL LEAVE</span> ";
						  $val2="FULLDAY-CASUAL LEAVE";
						  $c_val="FULLDAY-CASUAL LEAVE";
						  $c_l_l=1;
						  $a_l_l=0;
						  $c_l=$c_l+$c_l_l;
						  $a_l=$a_l+$a_l_l;
                      }
                   }
                   $sick_detail=\DB::select("SELECT * FROM hr_leaves_t WHERE employee_id='$employee_id' AND (DATE(start_date)='$date' OR start_date='$date') AND (DATE(end_date)='$date'OR end_date='$date') AND leave_type='266' AND leave_status='APPROVED' ");
                  
                   if(count($sick_detail)>0)
                   {
                      $type_leave=$sick_detail[0]->type_leave;
                      if($type_leave==1)
                      {
						  $val="<span class='present'>HALFDAY-SICK LEAVE</span> ";
						  $c_val="HALFDAY-SICK LEAVE";
						  $val2="HALFDAY-SICK LEAVE";
						  $s_l_l=0.5;
						  $a_l_l=0.5;
						  $s_l=$s_l+$s_l_l;
						  $a_l=$a_l+$a_l_l;
                      }
                      else 
                      {
						  $val="<span class='present'>FULLDAY-SICK LEAVE</span> ";
						  $c_val="FULLDAY-SICK LEAVE";
						  $val2="FULLDAY-SICK LEAVE";
						  $s_l_l=1;
						  $a_l_l=0;
						  $s_l=$c_l+$s_l_l;
						  $a_l=$a_l+$a_l_l;
                      }
                   }
                   
                   $earn_detail=\DB::select("SELECT * FROM hr_leaves_t WHERE employee_id='$employee_id' AND (DATE(start_date)='$date' OR start_date='$date') AND (DATE(end_date)='$date'OR end_date='$date') AND leave_type='2522' AND leave_status='APPROVED' ");
                  
                   if(count($earn_detail)>0)
                   {
                      $type_leave=$earn_detail[0]->type_leave;
                      if($type_leave==1)
                      {
						  $val="<span class='present'>HALFDAY-EARN LEAVE</span> ";
						  $c_val="HALFDAY-EARN LEAVE";
						  $val2="HALFDAY-EARN LEAVE";
						  $val1="HALFDAY-EARN LEAVE";
						  $e_l_l=0.5;
						  $a_l_l=0.5;
						  $e_l=$e_l+$e_l_l;
						  $a_l=$a_l+$a_l_l;
                      }
                      else 
                      {
						  $val="<span class='present'>FULLDAY-EARN LEAVE</span> ";
						  $c_val="FULLDAY-EARN LEAVE";
						  $val2="FULLDAY-EARN LEAVE";
						  $e_l_l=1;
						  $a_l_l=0.5;
						  $e_l=$e_l+$e_l_l;
						  $a_l=$a_l+$a_l_l;
                      }
                   }
                   else
				   {
						if($val=="")
						{
							$val="<span class='absent'>ABSENT</span>";
							$c_val="ABSENT";
							$val2="ABSENT";
							$val1="00:00:00";
							$absent_days++;
						}
                  }
					
                }
                
                if($val!="")
				{
                    $data.="<tr>"
                                ."<td>".$date."</td>"
                                ."<td>00:00:00</td>"
                                ."<td>00:00:00</td>"
                                ."<td>00:00:00</td>"
                                ."<td>".$val."</td>"
                                ."<td>00:00:00</td>"
                               
                            . "</tr>";
					$zero ='00:00:00';
                    $value = '"'.$date.'"'."\t"
                            .'"'.$zero.'"'."\t"
                            .'"'.$zero.'"'."\t"
                            .'"'.$zero.'"'."\t"
                            .'"'.$c_val.'"'."\t"
                            .'"'.$zero.'"'."\t";
                            $rowData .= $value."\n"; 
                }
                    $begin->modify('+1 day');
            }
		
	    $setData .= trim($rowData)."\n";
   
		

		
		
				$days = array(
					'total_days' => $total_days,
					'present_days' => $present_days,
					'absent_days' => ($absent_days+$a_l),
					'sundays' => $sundays,
					'holidays' => $holidayss,
					'casual' =>$c_l,
					'sick' => $s_l,
					'earn' => $e_l,
				);
           
           
      
              		$data.="</tbody></table></div>"; 
		
		return array('data'=>$data,'days'=>$days);
		
	}
    public function dailyattendancereport()
    {
        return view('attendancemonthlyreport.dailyattendancereport', $this->data);
    }
	
	
    public function datadailyattendancereportget(Request $request)
    {
		
   
$start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : NULL;
		
		
 $overall_data=\DB::select("select * from (SELECT '$start_date' as date,DATE_FORMAT('$start_date','%W')as day ,hr_employee_t.employee_number,hr_employee_t.biometric_empno,hr_employee_t.active,hr_employee_t.first_name,a.lookup_code as dept,COALESCE(a_lookuplines_t.lookup_meaning,'Absent')as status FROM `hr_employee_t` LEFT JOIN  hr_leaves_t on `hr_leaves_t`.employee_id=`hr_employee_t`.employee_id and hr_leaves_t.start_date<='$start_date' and hr_leaves_t.end_date>='$start_date' and hr_leaves_t.leave_status='APPROVE' left JOIN hr_emp_attendence on `hr_employee_t`.employee_number= `hr_emp_attendence`.emp_id and date(hr_emp_attendence.atten_date)='$start_date'  left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=hr_leaves_t.leave_mode LEFT JOIN a_lookuplines_t a ON a.lookuplines_id = hr_employee_t.employee_type  where hr_emp_attendence.check_in is null and hr_employee_t.active = 'yes' AND (a.lookup_code LIKE '%contract%' OR a.lookup_code LIKE '%staff%' OR a.lookup_code LIKE '%board%' ))as v1 HAVING v1.day !=''");

return response()->json(['data' => $overall_data]);

    }    
    
	
}
