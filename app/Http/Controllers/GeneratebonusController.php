<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB,Session;
use DateTime;
use Yajra\DataTables\DataTables;

class GeneratebonusController extends Controller
{   
        public function __construct()
    {
            $this->data['pageModule']=\Request::route()->getName();
           
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs();    
     }
    
    //payrollgenerate index page load function start 
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

    $this->data['zone'] = $this->jCombo('a_zone_master_t', 'zone_id', 'zone_name', '');
    $this->data['groupname']  = \Session::get('groupname');

       return view('generatebonus.index',$this->data);
    }
    
    
    public function bonusgenerate(Request $request) {
        
        // Retrieve query parameters
        $group = $request->input('group');
        $zone = $request->input('zone');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
       
        
       $loc=\Session::get('location');
       $compy=\Session::get('companyid'); 
       $org_id =\Session::get('organization');
       $emp_id = \Session::get('id');
       
       if($group == "HO/CO"){
           $final_gross= "hr_employee_payproposal.gross_pay/2 as final_gross_bonus,";
           
           $bonus_query = "GROUP_CONCAT(
    DISTINCT CASE 
        WHEN hr_employee_payproposal_before.updated_at IS NOT NULL 
        AND DATE(hr_employee_payproposal_before.updated_at) BETWEEN '$start_date' AND '$end_date'
        AND hr_employee_payproposal.effective_date > '$start_date'
        AND hr_employee_payproposal_before.gross_pay != hr_employee_payproposal.gross_pay
        THEN CONCAT(hr_employee_payproposal_before.gross_pay / 2, ',', hr_employee_payproposal.gross_pay / 2)
        ELSE hr_employee_payproposal.gross_pay / 2
    END
    ) AS overall_bonus,
    GROUP_CONCAT(
        DISTINCT CASE 
            WHEN hr_employee_payproposal_before.updated_at IS NOT NULL 
            AND DATE(hr_employee_payproposal_before.updated_at) BETWEEN '$start_date' AND '$end_date'
            AND hr_employee_payproposal.effective_date > '$start_date'
            AND hr_employee_payproposal_before.gross_pay != hr_employee_payproposal.gross_pay
            THEN CONCAT(hr_employee_payproposal_before.gross_pay, ',', hr_employee_payproposal.gross_pay)
            ELSE hr_employee_payproposal.gross_pay
        END
    ) AS overall_gross_pay";
    
           $group_id = " AND (hr_employee_t.employee_type = '150' or hr_employee_t.employee_type = '229' or hr_employee_t.employee_type = '237' or hr_employee_t.employee_type = '238' )";
     
           $acc_code_id = "593";
     
       }
          if($group == "Factory"){
              
             $final_gross= "hr_employee_payproposal.gross_pay as final_gross_bonus,";
             $bonus_query = "GROUP_CONCAT(
    DISTINCT CASE 
        WHEN hr_employee_payproposal_before.updated_at IS NOT NULL 
        AND DATE(hr_employee_payproposal_before.updated_at) BETWEEN '$start_date' AND '$end_date'
        AND hr_employee_payproposal.effective_date > '$start_date'
        AND hr_employee_payproposal_before.gross_pay != hr_employee_payproposal.gross_pay
        THEN CONCAT(hr_employee_payproposal_before.gross_pay, ',', hr_employee_payproposal.gross_pay)
        ELSE hr_employee_payproposal.gross_pay
        END
    ) AS overall_bonus,
    GROUP_CONCAT(
        DISTINCT CASE 
            WHEN hr_employee_payproposal_before.updated_at IS NOT NULL 
            AND DATE(hr_employee_payproposal_before.updated_at) BETWEEN '$start_date' AND '$end_date'
            AND hr_employee_payproposal.effective_date > '$start_date'
            AND hr_employee_payproposal_before.gross_pay != hr_employee_payproposal.gross_pay
            THEN CONCAT(hr_employee_payproposal_before.gross_pay, ',', hr_employee_payproposal.gross_pay)
            ELSE hr_employee_payproposal.gross_pay
        END
    ) AS overall_gross_pay";  
             $group_id = " AND (hr_employee_t.employee_type = '151' or hr_employee_t.employee_type = '152' or hr_employee_t.employee_type = '249')";
             $acc_code_id = "593";
       }
       
          if($group == "Marketing"){
              
           $final_gross= "SUM(hr_employee_payproposal.basic_pay+hr_employee_payproposal.hra+hr_employee_payproposal.da)/2 as final_gross_bonus,";
           
           $bonus_query = "GROUP_CONCAT(
            DISTINCT CASE 
                WHEN hr_employee_payproposal_before.updated_at IS NOT NULL 
                AND DATE(hr_employee_payproposal_before.updated_at) BETWEEN '$start_date' AND '$end_date'
                AND hr_employee_payproposal.effective_date > '$start_date'
                AND hr_employee_payproposal_before.gross_pay != hr_employee_payproposal.gross_pay
                THEN CONCAT(hr_employee_payproposal_before.basic_pay + hr_employee_payproposal_before.hra + hr_employee_payproposal_before.da / 2, ',', hr_employee_payproposal.basic_pay + hr_employee_payproposal.hra + hr_employee_payproposal.da / 2)
                ELSE hr_employee_payproposal.basic_pay + hr_employee_payproposal.hra + hr_employee_payproposal.da / 2
            END
        ) AS overall_bonus,
        GROUP_CONCAT(
            DISTINCT CASE 
                WHEN hr_employee_payproposal_before.updated_at IS NOT NULL 
                AND DATE(hr_employee_payproposal_before.updated_at) BETWEEN '$start_date' AND '$end_date'
                AND hr_employee_payproposal.effective_date > '$start_date'
                AND hr_employee_payproposal_before.gross_pay != hr_employee_payproposal.gross_pay
                THEN CONCAT(hr_employee_payproposal_before.gross_pay, ',', hr_employee_payproposal.gross_pay)
                ELSE hr_employee_payproposal.gross_pay
            END
        ) AS overall_gross_pay"; 
    
           $group_id = " AND (hr_employee_t.employee_type = '231' or hr_employee_t.employee_type = '248' or hr_employee_t.employee_type = '275') AND hr_employee_t.zone_id='$zone' ";
       
              
          }
       // dd($group);
       
    $bonus = \DB::select("SELECT 
    v4.employee_id,
    v4.employee_number,
    v4.first_name,
    v4.department,
    v4.date_of_joining,
    v4.zone_name,
    v4.last_sal_rev,
    v4.num_of_apprisal,
    v4.effective_date,
    v4.start_date,
    v4.end_date,
    v4.fst_appraisal,
    v4.sec_appraisal,
    v4.third_appraisal,
    v4.fourth_appraisal,
    v4.days_befst_app,
    v4.days_fst_sec,
    v4.days_sec_third,
    v4.days_third_fourth,
    v4.days_belast_app,
    v4.payable1,
    v4.payable2,
    v4.payable3,
    v4.payable4,
    v4.final_payable,
    v4.arrear_bonus,
    v4.arrear_days,
    v4.gross1, 
    v4.gross2,
    v4.gross3,
    v4.gross4,
    v4.bonus1, 
    v4.bonus2,
    v4.bonus3, 
    v4.bonus4,
     v4.group_type,
    v4.final_gross_bonus,
    v4.last_period,
    v4.overall_bonus,
    v4.overall_gross_pay,
    SUM(
        COALESCE(v4.payable1, 0) + 
        COALESCE(v4.payable2, 0) + 
        COALESCE(v4.payable3, 0) + 
        COALESCE(v4.payable4, 0) + 
        COALESCE(v4.arrear_bonus, 0) +  
        COALESCE(v4.final_payable, 0)
    ) AS total_bonus
FROM (
    SELECT 
        v3.*,
        ROUND((v3.days_befst_app) * v3.bonus1 / 365, 0) AS payable1,
        ROUND((v3.days_fst_sec) * v3.bonus2 / 365, 0) AS payable2,
        ROUND((v3.days_sec_third) * v3.bonus3 / 365, 0) AS payable3,
        ROUND((v3.days_third_fourth) * v3.bonus4 / 365, 0) AS payable4,
        ROUND((v3.days_belast_app) * v3.final_gross_bonus / 365, 0) AS final_payable,
        ROUND((v3.arrear_days) * v3.bonus1 / 365, 0) AS arrear_bonus
    FROM (
        SELECT 
            v2.*,
            COALESCE(ABS(DATEDIFF(v2.start_date, v2.fst_appraisal)), 365) AS days_befst_app,
            ABS(DATEDIFF(v2.fst_appraisal, v2.sec_appraisal)) AS days_fst_sec,
            ABS(DATEDIFF(v2.sec_appraisal, v2.third_appraisal)) AS days_sec_third,
            ABS(DATEDIFF(v2.third_appraisal, v2.fourth_appraisal)) AS days_third_fourth,
            ABS(DATEDIFF(v2.last_appraisal, v2.end_date)) AS days_belast_app
        FROM (
            SELECT 
                v1.*,
                GREATEST(
                    COALESCE(v1.fst_appraisal, ''), 
                    COALESCE(v1.sec_appraisal, ''), 
                    COALESCE(v1.third_appraisal, ''), 
                    COALESCE(v1.fourth_appraisal, '')
                ) AS last_appraisal,   
    COALESCE(SUBSTRING_INDEX(overall_gross_pay, ',', 1), 0) AS gross1,
    COALESCE(SUBSTRING_INDEX(SUBSTRING_INDEX(overall_gross_pay, ',', 2), ',', -1), 0) AS gross2,
    CASE  WHEN v1.third_appraisal IS NOT NULL THEN     
    COALESCE(SUBSTRING_INDEX(SUBSTRING_INDEX(overall_gross_pay, ',', 3), ',', -1), 0) ELSE NULL END AS gross3,
    CASE  WHEN v1.fourth_appraisal IS NOT NULL THEN  
    COALESCE(SUBSTRING_INDEX(SUBSTRING_INDEX(overall_gross_pay, ',', 4), ',', -1), 0) ELSE NULL END AS gross4,
    COALESCE(SUBSTRING_INDEX(overall_bonus, ',', 1), 0) AS bonus1,
    COALESCE(SUBSTRING_INDEX(SUBSTRING_INDEX(overall_bonus, ',', 2), ',', -1), 0) AS bonus2,
    CASE WHEN v1.third_appraisal IS NOT NULL THEN        
    COALESCE(SUBSTRING_INDEX(SUBSTRING_INDEX(overall_bonus, ',', 3), ',', -1), 0)  ELSE NULL END AS bonus3,
    CASE WHEN v1.fourth_appraisal IS NOT NULL THEN  
    COALESCE(SUBSTRING_INDEX(SUBSTRING_INDEX(overall_bonus, ',', 4), ',', -1), 0) ELSE NULL END AS bonus4

            FROM (
                SELECT 
                    hr_employee_t.employee_id,
                    hr_employee_t.employee_number,
                    hr_employee_t.first_name,
                    hr_employee_t.group_type,
                    hr_employee_t.date_of_joining,
                    REPLACE(REPLACE(SUBSTRING_INDEX(SUBSTRING_INDEX(department, ',', 1), '[', -1), ']', ''), '\"', '') AS department,
                    a_zone_master_t.zone_name,
                    DATE(hr_employee_payproposal.updated_at) AS last_sal_rev,
                    $final_gross
                    SUM(CASE 
                        WHEN DATE(hr_employee_payproposal_before.updated_at) BETWEEN '$start_date' AND '$end_date' 
                        THEN 1 
                        ELSE 0 
                    END) AS num_of_apprisal,
                    '$start_date' AS start_date,
                    '$end_date' AS end_date,
                    hr_employee_payproposal.effective_date,
                    MIN(CASE 
                        WHEN DATE(hr_employee_payproposal_before.updated_at) BETWEEN '$start_date' AND '$end_date' 
                            AND hr_employee_payproposal_before.updated_at > hr_employee_payproposal.effective_date
                            AND hr_employee_payproposal_before.gross_pay <> hr_employee_payproposal.gross_pay
                        THEN DATE(hr_employee_payproposal_before.updated_at)  
                        WHEN DATE(hr_employee_payproposal.effective_date) BETWEEN '$start_date' AND '$end_date' 
                        THEN hr_employee_payproposal.effective_date 
                        ELSE NULL
                    END) AS fst_appraisal,

                    -- Second Appraisal Date
                    MIN(CASE  
                        WHEN DATE(hr_employee_payproposal_before.updated_at) BETWEEN '$start_date' AND '$end_date' 
                            AND hr_employee_payproposal_before.updated_at > (
                                SELECT MIN(sub.updated_at)
                                FROM hr_employee_payproposal_before sub
                                WHERE sub.employee_id = hr_employee_t.employee_id
                                AND DATE(sub.updated_at) BETWEEN '$start_date' AND '$end_date'
                            )
                            AND hr_employee_payproposal_before.gross_pay <> (
                                SELECT MAX(prev.gross_pay)
                                FROM hr_employee_payproposal_before prev
                                WHERE prev.employee_id = hr_employee_t.employee_id
                                AND DATE(prev.updated_at) BETWEEN '$start_date' AND '$end_date'
                                AND prev.updated_at < hr_employee_payproposal_before.updated_at
                            )
                        THEN DATE(hr_employee_payproposal_before.updated_at) 
                        ELSE NULL 
                    END) AS sec_appraisal,

                    -- Third Appraisal Date
                    MIN(CASE 
                        WHEN DATE(hr_employee_payproposal_before.updated_at) BETWEEN '$start_date' AND '$end_date' 
                            AND hr_employee_payproposal_before.updated_at > (
                                SELECT MAX(second_app.updated_at)
                                FROM hr_employee_payproposal_before second_app
                                WHERE second_app.employee_id = hr_employee_t.employee_id
                                AND DATE(second_app.updated_at) BETWEEN '$start_date' AND '$end_date'
                            )
                            AND hr_employee_payproposal_before.gross_pay <> (
                                SELECT MAX(prev.gross_pay)
                                FROM hr_employee_payproposal_before prev
                                WHERE prev.employee_id = hr_employee_t.employee_id
                                AND DATE(prev.updated_at) BETWEEN '$start_date' AND '$end_date'
                                AND prev.updated_at < hr_employee_payproposal_before.updated_at
                            )
                        THEN DATE(hr_employee_payproposal_before.updated_at) 
                        ELSE NULL 
                    END) AS third_appraisal,

                    -- Fourth Appraisal Date
                    MIN(CASE 
                        WHEN DATE(hr_employee_payproposal_before.updated_at) BETWEEN '$start_date' AND '$end_date' 
                            AND hr_employee_payproposal_before.updated_at > (
                                SELECT MAX(third_app.updated_at)
                                FROM hr_employee_payproposal_before third_app
                                WHERE third_app.employee_id = hr_employee_t.employee_id
                                AND DATE(third_app.updated_at) BETWEEN '$start_date' AND '$end_date'
                            )
                            AND hr_employee_payproposal_before.gross_pay <> (
                                SELECT MAX(prev.gross_pay)
                                FROM hr_employee_payproposal_before prev
                                WHERE prev.employee_id = hr_employee_t.employee_id
                                AND DATE(prev.updated_at) BETWEEN '$start_date' AND '$end_date'
                                AND prev.updated_at < hr_employee_payproposal_before.updated_at
                            )
                        THEN DATE(hr_employee_payproposal_before.updated_at) 
                        ELSE NULL 
                    END) AS fourth_appraisal,
                    DATEDIFF(
                        CASE 
                            WHEN DATEDIFF('$start_date', hr_employee_t.date_of_joining) >= 365 THEN NULL
                            ELSE '$start_date' 
                        END, 
                        hr_employee_t.date_of_joining
                    ) AS arrear_days,
                       MAX(CASE 
                WHEN DATE(hr_employee_payproposal_before.updated_at) BETWEEN '$start_date' AND '$end_date' 
                THEN DATE(hr_employee_payproposal_before.updated_at) 
                ELSE NULL 
            END) as last_period,  
            $bonus_query


                FROM 
                    hr_employee_t 
                LEFT JOIN 
                    hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_t.employee_id
                LEFT JOIN 
                    hr_employee_payproposal_before ON hr_employee_payproposal_before.employee_id = hr_employee_t.employee_id
                LEFT JOIN 
                    a_zone_master_t ON hr_employee_t.zone_id = a_zone_master_t.zone_id
WHERE 
    hr_employee_t.date_of_joining <= '$start_date' 
    AND hr_employee_t.employee_id != '1' 
    AND hr_employee_t.active = 'Yes' AND date_of_leaving IS NULL  $group_id
                GROUP BY 
                    hr_employee_t.employee_id
            ) v1
        ) v2
    ) v3
) v4
GROUP BY 
    v4.employee_id
ORDER BY 
    v4.employee_id");
    
 // dd($bonus);
  
    foreach ($bonus as $data) {
         $zone_id = $data->zone_name;
          //  dd($group);
        if ($zone_id =='' && $group == "HO/CO"){
            $zone_id = "HO/CO";
        }
         if ($zone_id =='' && $group == "Factory"){
            $zone_id = "Factory";
        }
 
       if($zone_id == 'EAST') {
       
       $acc_code_id = "583";
       
       }if($zone_id == 'WEST'){
            $acc_code_id = "584";
       }if($zone_id == 'NORTH'){
            $acc_code_id = "584";
       }
        if($zone_id == 'CORPORATE'){
                $acc_code_id = "584";
        }if($zone_id == 'CENTRAL'){
                $acc_code_id = "585";
        }if($zone_id == 'SOUTH 2'){
                $acc_code_id = "577";
        }if($zone_id == 'SOUTH 1'){
                $acc_code_id = "577";
        }if($zone_id == 'SOUTH 3'){
                $acc_code_id = "577";
           }


    $result = \DB::table('hr_employee_bonus_lists')->insert([
        'employee_id' => $data->employee_id,
        'employee_number' => $data->employee_number,
        'employee_name' => $data->first_name,
        'department' => $data->department,
        'group_type' => $data->group_type,
        'month' => date('m'),
        'year' => date('Y'),
        'date' => date('Y/m/d'),
        'start_date' => $data->start_date,
        'last_period' => $data->last_period,
        'end_date' => $data->end_date,
        'last_revision_date' => $data->last_sal_rev,
        'effective_date' => $data->effective_date,
        'doj' => $data->date_of_joining,
        'zone' => $zone_id,
        'bonus1' => $data->bonus1,
        'bonus2' => $data->bonus2,
        'bonus3' => $data->bonus3,
        'bonus4' => $data->bonus4,
        'from1' => $data->start_date,
        'to1' => $data->fst_appraisal,
        'from2' => $data->fst_appraisal,
        'to2' => $data->sec_appraisal,
        'from3' => $data->sec_appraisal,
        'to3' => $data->third_appraisal,
        'from4' => $data->third_appraisal,
        'to4' => $data->fourth_appraisal,
        'payable1' => $data->payable1,
        'arrear_days' => $data->arrear_days,
        'arrear_bonus' => $data->arrear_bonus,
        'payable2' => $data->payable2,
        'payable3' => $data->payable3,
        'payable4' => $data->payable4,
        'final_payable' => $data->final_payable,
        'total_bonus' => $data->total_bonus,
        'bonus_status' => '0',
        'payment_status' =>'0',
        'account_code_id' =>$acc_code_id,
        'no_of_appraisal' => $data->num_of_apprisal,
        'total_gross' => $data->overall_gross_pay,
        'total_gros_bonus' => $data->overall_bonus,
        'created_by' => $emp_id,
        'created_at' => date('Y/m/d'),
        'last_updated_by' => $emp_id,
        'updated_at' => date('Y/m/d'),
        'company_id' => $compy,
        'location_id' => $loc,
        'organization_id' => $org_id
        ]);
    }

    return response()->json(['success' => true]);

 }

    
    
        public function employeebonusgriddataapprove(Request $request) {
            
        
        $group_name = \Session::get('groupname');
                
            $condition ='';    
                
        if ($group_name =="16"){
            
            $condition = " AND hr_employee_bonus_lists.group_type='14' ";
        }        
         
                
		$wh=' and bonus_status=0';


        
	$SQL = "SELECT 
	                hr_employee_bonus_lists.id,
                    hr_employee_bonus_lists.employee_id,
                    hr_employee_bonus_lists.employee_number,
                    hr_employee_bonus_lists.employee_name,
                    m_department_lines_t.sub_department_name as department,
                    hr_employee_bonus_lists.zone,
                    hr_employee_bonus_lists.last_revision_date,
                    hr_employee_bonus_lists.doj,
                    hr_employee_bonus_lists.effective_date,
                    hr_employee_bonus_lists.bonus1,
                    hr_employee_bonus_lists.bonus2,
                    hr_employee_bonus_lists.bonus3,
					hr_employee_bonus_lists.bonus4,
					hr_employee_bonus_lists.from1,
					hr_employee_bonus_lists.to1,
					hr_employee_bonus_lists.payable1,
					hr_employee_bonus_lists.from2,
					hr_employee_bonus_lists.to2,
					hr_employee_bonus_lists.payable2,
                    hr_employee_bonus_lists.from3,
					hr_employee_bonus_lists.to3,
					hr_employee_bonus_lists.payable3,
                    hr_employee_bonus_lists.from4,
					hr_employee_bonus_lists.to4,
					hr_employee_bonus_lists.payable4,
                    hr_employee_bonus_lists.last_period,
                    hr_employee_bonus_lists.end_date,
                    hr_employee_bonus_lists.final_payable,
                    hr_employee_bonus_lists.arrear_bonus,
                        CASE
                        WHEN bonus_status = '0' THEN 'INITIATED'
                        WHEN bonus_status = '1' THEN 'APPROVED'
                        WHEN bonus_status = '2' THEN 'RELEASED'
                        ELSE NULL
                    END AS status,
                    hr_employee_bonus_lists.total_bonus
	 FROM hr_employee_bonus_lists LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = hr_employee_bonus_lists.department  WHERE 1=1 $wh $condition";
        
        
  
	$result = \DB::select($SQL);

	return DataTables::of($result)->make(true);
      
     }

    
    
}