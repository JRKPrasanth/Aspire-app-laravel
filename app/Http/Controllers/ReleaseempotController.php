<?php

namespace App\Http\Controllers;
use DB;
use DateTime;
use Session;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReleaseempotController extends Controller
{
    public function __construct()
  {
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs(); 
   }
    // approve index page load function start
  public function index(Request $request)
  {
      
      // restrict menu illegal entry purpose - VIGNESH M

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

 $this->data['sum_inv_tot'] = \DB::select("SELECT ROUND(SUM(total_amt),0) as total from hr_emp_ot_details_t where status='APPROVED'");
    return view('employeeot.releaseot',$this->data);
  } 

 
 
      public function employeeotgrid2(Request $request) {


	$SQL = "SELECT 
hr_emp_ot_details_t.emp_id,
hr_emp_ot_details_t.ot_run_date,
hr_emp_ot_details_t.ot_month,
hr_emp_ot_details_t.ot_year,
hr_emp_ot_details_t.emp_number,
hr_emp_ot_details_t.emp_name,
m_department_lines_t.sub_department_name,
m_job_title.job_title_name,
TIME_FORMAT(SEC_TO_TIME(SUM(hr_emp_ot_details_t.overall_ot_hrs) * 3600),'%H:%i')  AS overall_ot_hrs,
CONCAT(LEFT(MONTHNAME(STR_TO_DATE(ot_month, '%m')), 3), '-', ot_year) AS yr_month, 
SUM(hr_emp_ot_details_t.ot_amount) AS ot_amount,
SUM(hr_emp_ot_details_t.food_amount) AS food_amount,
SUM(hr_emp_ot_details_t.total_amt) total_amt,
hr_emp_ot_details_t.status

FROM `hr_emp_ot_details_t` 

LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = hr_emp_ot_details_t.department
LEFT JOIN m_job_title ON m_job_title.job_title_id = hr_emp_ot_details_t.job_tittle WHERE 1=1 AND hr_emp_ot_details_t.status='APPROVED' GROUP BY hr_emp_ot_details_t.emp_id ORDER BY month(check_in) ASC";


	$data = \DB::select($SQL);

	  return DataTables::of($data)->make(true);
      
     }
  
  
  public function releaseot(){
        
      $emp_id = $_GET['row_id'];
      $amount = $_GET['amount']; 
      $amountArray = explode(',', $amount);
      $totalBalance = array_sum($amountArray);
      $endDate = date("Y-m-d"); 
      $ot_month = $_GET['month_ot'];
      $ot_month = (int) $ot_month;  // force integer
      $ot_mon = date("M", mktime(0, 0, 0, $ot_month, 1));

      $ot_year = $_GET['year_ot'];
      
    //expense insert purpose
    
    $data = [
        'expense_no' => '',
        'expenses_count' => '',
        'expense_date' => $endDate,
        'expense_status' => 'INITIATED',
        'expense_amount' => $totalBalance,
        'remarks' => '',
        'pay_amount' => '0',
        'company_id' => \Session::get('companyid'),
        'created_by' => \Session::get('id'),
        'location_id' => "1",
        'organization_id' => \Session::get('organization'),
        'created_at' => date("Y-m-d"),
        'last_updated_by' => \Session::get('id'),
        'updated_at' => date("Y-m-d"),
        'reference_id' => '0',
        'paid_amount' => '0',
        'payment_status' => '0',
        'balance_amount' => $totalBalance, 
        'tax_amount' => '0',
        'round_off' => '0',
        'source' => '',
    ];

    $expense_load = \DB::table('f_emp_expenses_t')->insertGetId($data);
      
    $lastInsertId = \DB::getPdo()->lastInsertId();   
      
      // expense line insert purpose

    $emp_ids = explode(',', $_GET['row_id']); 
    $amounts = explode(',', $_GET['amount']); 
    $bill_no = "EP$ot_mon$ot_year";
    $Remarks = "Being extra productivity paid for the month of $ot_mon $ot_year";


    $data1 = []; 

    foreach ($emp_ids as $key => $emp_id) {
        if (isset($amounts[$key])) { // Ensure there's a corresponding amount
            $data1[] = [
                'bill_no' => $bill_no,
                'bill_date' => date("Y-m-d"),
                'choosefile' => "NO",
                'employee_id' => $emp_id,
                'remarks' => $Remarks,
                'tds_percentage' => '',
                'tds_applicable' => "NO",
                'tds_amount' => '',
                'emp_exp_total' => $amounts[$key], 
                'tds_account_id' => '',
                'payment_request_status' => '0',
                'payment_status' => '0',
                'balance_amounts' => '0',
                'company_id' => \Session::get('companyid'),
                'created_by' => \Session::get('id'),
                'location_id' => "1",
                'organization_id' => \Session::get('organization'),
                'created_at' => date("Y-m-d"),
                'last_updated_by' => \Session::get('id'),
                'updated_at' => date("Y-m-d"),
                'expense_id' => $lastInsertId,
                'expense_account_id' => "207",
                'expense_line_amount' => $amounts[$key], 
            ];
        }
    }
    
    // Insert all data into the table
    if (!empty($data1)) {
        \DB::table('f_emp_expenses_lines_t')->insert($data1);
    }

   $emp_id1 = $_GET['row_id'];   
   $date = $_GET['month'];
   
      // update status for ot table 
      $list_id = explode(',',$emp_id1);
      $ot_month= explode(',',$date);
      
    if($emp_id1 != '')
    {
      foreach($list_id as $key=>$value){
       foreach($ot_month as $key=>$value1){
           
        $query = DB::table('hr_emp_ot_details_t')->where('emp_id',$value)->where('ot_run_date',$value1)->update(['status'=>"RELEASED"]);
       // dd($query);
        
                                //auditlog
                                  $update['status']="RELEASED";
                                   $this->auditlog($value,"releaseot","update",$update,"hr_emp_ot_details_t");
      }
    }
    }
    return 1;
  }

  
  
  
  
  
}