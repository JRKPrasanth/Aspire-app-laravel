<?php

namespace App\Http\Controllers;
use Session;
use DateTime,DB;
use Illuminate\Http\Request;

class EsidumpController extends Controller
{
    public function __construct()
	{
            $this->data=array();
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
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

	     return view('esidump.table',$this->data);
             
             
         } 
         
         
           public function dumpreportesi($month=null,$year=null)
           
         {
            $year = $year;
            $month = $month;
            
            // Get the total number of days in the month
           $total_days = date('t', mktime(0, 0, 0, $month, 1)); // 't' gives total days in the month
           
          // dd($total_days);
            
            $my_file = uniqid().'.xls';
            $excel_file = uniqid().'12.xls';
            $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
            $handle1 = fopen($excel_file, 'w') or die('Cannot open file:  '.$excel_file);
            $groupname=\Session::get('groupname');

              if($groupname == "2"){
                $query1 = \DB::select("select hr_employee_t.*,hr_employee_payroll_lists.id from hr_employee_payroll_lists  join hr_employee_t on hr_employee_t.employee_id=hr_employee_payroll_lists.employee_id  join hr_employee_payproposal on hr_employee_payproposal.employee_id=hr_employee_payroll_lists.employee_id  where hr_employee_t.active='Yes' and 1=1 and hr_employee_t.employee_id!=1 and (hr_employee_payproposal.gross_pay<21000 or hr_employee_payroll_lists.esi>0) and hr_employee_payroll_lists.month='$month' and year='$year'  group by hr_employee_payroll_lists.id");  
              }else{
                $query1 = \DB::select("select hr_employee_t.*,hr_employee_payroll_lists.id from hr_employee_payroll_lists  join hr_employee_t on hr_employee_t.employee_id=hr_employee_payroll_lists.employee_id  join hr_employee_payproposal on hr_employee_payproposal.employee_id=hr_employee_payroll_lists.employee_id  where 1=1 and hr_employee_t.employee_id!=1 and (hr_employee_payproposal.gross_pay<21000 or hr_employee_payroll_lists.esi>0) and hr_employee_payroll_lists.month='$month' and year='$year'  group by hr_employee_payroll_lists.id");  
              }
              
$data1 = '';
$data1 .= "IP Number\tIP Name\tNo of Days for which wages paid/payable during the month\tTotal Monthly Wages\tReason Code for Zero working days\tLast Working Day\r\n";

$check_arr = array();

$wages = 0;    
$earned_wages = 0;
$esi_total = 0;
$esi_comp_total = 0;
$gran_total = 0;

$data = '<div class="table-responsive">';
$data .= '<table class="table table-bordered table-hover align-middle">';
$data .= '<thead class="table-primary text-center"><tr>
    <th>S.No</th>
    <th>IP Number</th>
    <th>IP Name</th>
    <th>No of Days for which wages paid/payable during the month</th>
    <th>Total Monthly Wages</th>
    <th class="table-success">Reason Code for Zero working days</th>
    <th class="table-success">Last Working Day</th>
</tr></thead><tbody>';

$i = 1;

foreach($query1 as $row) {
    if (isset($check_arr[$row->employee_id])) {
        $check_arr[$row->employee_id]++;
    } else {
        $check_arr[$row->employee_id] = 0;
    }

    $result = $this->contributelop($row->employee_id, $month, $year, $row->id, $check_arr[$row->employee_id]);

    $data .= "<tr>
        <td>{$i}</td>
        <td>{$row->esi_no}</td>
        <td>{$row->first_name}</td>
        <td>{$result['day']}</td>
        <td>{$result['fixed_wages']}</td>
        <td>{$result['code']}</td>
        <td>{$result['dol']}</td>
    </tr>";

    $wages += $result['fixed_wages'];
    $earned_wages += $result['earned_wages'];
    $esi_total += $result['esi'];
    $esi_comp_total += $result['comp_esi'];

    if (($result['esi'] + $result['comp_esi']) != 0) {
        $data1 .= $row->esi_no . "\t" . $row->first_name . "\t" . round($result['day']) . "\t" . round($result['earned_wages']) . "\t" . round($result['code']) . "\t" . $result['dol'] . "\r\n";
    }

    $i++;
}

$data .= '</tbody></table></div>';

// Write file content
fwrite($handle, $data1);
fclose($handle);

// Download buttons (Bootstrap styled)
$data .= '
<div class="mt-4 text-center">
    <a href="' . $my_file . '" class="btn btn-outline-danger me-2" download>
        <i class="fa fa-file-download me-1"></i>Download Text
    </a>
    <a href="' . $my_file . '" class="btn btn-outline-success" download>
        <i class="fa fa-file-excel me-1"></i>Download Excel
    </a>
</div>';

return response()->json([
    'data' => $data,
    'download_ling' => $my_file,
    'excel_file' => $my_file
]);

              
             
         } 
         
         
        function contributelop($employe_id,$month,$year,$id,$index)
        {

           
           $total_days = date('t', mktime(0, 0, 0, $month, 1)); // 't' gives total days in the month
             
             
            $index1=$index+1;
            $query2 = \DB::select("select * from hr_employee_payroll_lists where id='$id' and month='$month' and year='$year'");  
            $query = \DB::select("select * from hr_employee_payproposal where employee_id='$employe_id'");  
            $query_pf = \DB::select("select * from hr_company_contribute_esi where emp_id='$employe_id' and month='$month' and year='$year' order by company_conribute_id asc limit $index, $index1 ");  
            

            $query_dol = \DB::select("select date_of_leaving from hr_employee_t where employee_id='$employe_id'");  
            
            if(count($query2)>0)
            { 
                $no_of_days     = $query2[0]->attendance_days;
                $fixed          = $query[0]->gross_pay;
                $earn_wages     = $query2[0]->gross_salary;
				if($query2[0]->esi>0)
				{
				// dd($query_pf);
				if(count($query_pf)>0){
				     $esi            = $query_pf[0]->amount;
                     $esi_comp       = $query_pf[0]->amount_employee;
			    }else{
			        $esi=0;
			        $esi_comp =0;
			    }
               	}
				else
				{
				 $esi            = 0;
                $esi_comp       = 0;	
				}
				
                $dol = null;
                $code = "0";
                
                if (count($query_dol) > 0) {
                    if ($query_dol[0]->date_of_leaving != null) {
                        $dol = $query_dol[0]->date_of_leaving;
                        $code = "2";
                    }
                } else {
                    $code = "1";
                }
                    
            return  array("day"=>$no_of_days,"fixed_wages"=>$fixed,"earned_wages"=>$earn_wages,"esi"=>$esi,"comp_esi"=>$esi_comp,"code"=>$code,"dol"=>$dol);
            }
            else
            {
                return  array("day"=>"0","fixed_wages"=>'0',"earned_wages"=>"0","esi"=>"0","comp_esi"=>"0","code"=>"0","dol"=>"0");
            }
            return  array("day"=>"0","fixed_wages"=>'0',"earned_wages"=>"0","esi"=>"0","comp_esi"=>"0","code"=>"0","dol"=>"0");
        }   
         
}