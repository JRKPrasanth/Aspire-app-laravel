<?php

namespace App\Http\Controllers;
use App\Misviewers;
use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;

class PrimarycostratioController extends Controller

{
	
		   public function __construct(){
        
		$this->pageModule="primarycostsaleratioindex";	
		$this->data=array();
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();
       
    }
	
	    public function Index(Request $request)
    {


// login viewers track purpose start
 
         $emp_id = auth()->user()->id;
         $date = date('Y-m-d');
         $date_time = date('Y-m-d h:s:i');
         $url = $request->path();
         $created_at = date('Y-m-d h:s:i');
         $updated_at = date('Y-m-d h:s:i');
         $com_id = \Session::get('organization');
         
      $existingRecord = Misviewers::where('emp_id', $emp_id)
     ->where ('date', $date)
     ->where('url', $url)
     ->first(); // Use first() to retrieve a single model instance
 
 if ($existingRecord) {
     $existingRecord->update(['date_time' => $date_time]); // Call update() on the model instance
     
     } else {
         $trackingData = [
             'emp_id' => $emp_id,
             'date' => $date,
             'date_time' => $date_time,
             'url' => $url,
             'created_at' => $created_at,
             'updated_at' => $updated_at,
             'organization_id' => $com_id,
         ];
     
         Misviewers::create($trackingData);
     }

 $zoneWiseData = DB::table('sd_tracker_t')->get();
      
 // login viewers track purpose End
			
	 return view('primarycostratio.index', $this->data);
	
	}
    // monthWise Report
    public function MonthWise(Request $request)
    {

    
        $wh = '';
               // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        // RSM region compare purpose
        $em_reg = \DB::select("SELECT job_title from hr_employee_t where employee_id='$emp_id'");
        $emp_reg = $em_reg[0]->job_title;
        
          // for prakash access all mis purpose
          if ($depart == "14" && $emp_id !='121' ) {

            // RSM region compare purpose
            if ($emp_reg == '41') {

                $emp_region = '';
                // emp region 
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

                if (!empty($em_region)) {
                    $regions = array_column($em_region, 'region');
                    $emp_region = "'" . implode("','", $regions) . "'";
                    $wh = " AND sd_primarydataupload_t.region IN ($emp_region)";  
                } else {
                    $wh = " AND sd_primarydataupload_t.region IN ('')";
                }
                //end
            } else {
                //others

                $emp_zone = '';
                // emp zone 
                $em_zone = \DB::select("SELECT zone from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

                if (!empty($em_zone)) {
                    if (isset($em_zone[0]->zone) && $em_zone[0]->zone != '') {
                        $emp_zone = $em_zone[0]->zone;
                    } else {
                        $emp_zone = '';
                    }
                }

                if (!empty($emp_zone)) {
                    $wh = "  AND sd_primarydataupload_t.zone ='$emp_zone'";
                } else {
                    $wh = " AND sd_primarydataupload_t.zone IN ('')";
                }
            }
       
            // raja ram only show andra state purpose
            
            if($emp_id == '94'){
                   
                $wh = " AND sd_primarydataupload_t.state IN ('ANDHRA PRADESH')";
                
            }
     }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $l_dbmon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $this->data['last_yr'] =  $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $condition = '';
        $current_y = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $current_year = $current_y[0]->c_year;

        $current_mon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $current_month = $current_mon[0]->month;
        // dd($current_month);

        $this->data['select_year'] = $current_year - 1;
        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $cur_fyear = $this->data['fy_year'][0]->f_year;

        // defalt previous fy_years
        $pre_cur_year = substr($cur_fyear, 0, 4) - 1;
        $last_preyear = substr($cur_fyear, 5, 7) - 1;
        // Concatenating the current and next year with appropriate formatting
        $pre_fyear = $pre_cur_year . '-' . $last_preyear;

        $this->data['cur_fy_year'] = $cur_fyear;
        $this->data['pre_fy_year'] = $pre_fyear;
        /// end default fy years   

        // Get start date from the request
        $select_date = $request->input('date_select');
        // Extract year and month from the start date
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('M', strtotime($select_date));
        $selected_month = date('m', strtotime($select_date));

        // Calculate next and previous years
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;

        // Determine the financial year based on the start month (assuming April is the start of the financial year)

        $fy_full_year = '';


        if ($selected_month >= 4) {

            $fy_full_year =  $select_year  . '-' . ($select_year + 1);
        } else {

            $fy_full_year =  ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);

        $select_pre = substr($fy_year, 0, 4);
        $select_pre_y = substr($fy_year, 5, 2);

        $select_pre_year =  ($select_pre - 1) . '-' . ($select_pre_y - 1);

        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;

            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = date('M', strtotime($last_m));
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
            }

            // month wise cur FY

            $this->data['month_wise'] = \DB::select("SELECT month,c_year,
        
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = 'Salary\\\\Exp.\\\\Incen.' THEN asseesable_value ELSE 0 END), 0) as expence,
    ROUND(SUM(CASE WHEN data_type = 'Manpower' THEN asseesable_value END), 0) AS man
    FROM
      sd_primarydataupload_t
    WHERE
     f_year='$fy_year' $condition $wh $notin  AND  STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%M-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%M-%d') 
    GROUP BY
     month ORDER BY c_year,STR_TO_DATE(CONCAT('001', month, ' 01'), '%Y %M %d')");

            // month wise Pre FY

            $this->data['month_wise_prefy'] = \DB::select("SELECT month,c_year, 
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = 'Salary\\\\Exp.\\\\Incen.' THEN asseesable_value ELSE 0 END), 0) as expence,
    ROUND(SUM(CASE WHEN data_type = 'Manpower' THEN asseesable_value END), 0) AS man

    FROM
      sd_primarydataupload_t
    WHERE
     f_year='$select_pre_year' $condition $wh $notin 
    GROUP BY
     month ORDER BY c_year,STR_TO_DATE(CONCAT('001', month, ' 01'), '%Y %M %d')");

            // month chart cur fy 
            $pro_sumchart = $this->data['month_wise'];

            $primData = [];
            foreach ($pro_sumchart as $primary) {

                if ($primary->month == $primary->month) {

                    $ratio = $primary->man != 0 ? (($primary->sale / $primary->man)) : null;
                    $cost = $primary->sale != 0 ? (($primary->expence / $primary->sale * 100)) : null;
                    $ratio = str_replace(',', '', $ratio);
                    $primData[] = [
                        'name' => $primary->month,
                        'ratio' => $ratio !== 0 ? number_format($ratio, 0, '', '') : "0",
                        'cost' => $cost !== 0 ? number_format($cost, 0) : "0",

                    ];
                }
            }

            $this->data['pro_sumchart'] = json_encode($primData);

            // month chart pre fy

            $month_sumchart = $this->data['month_wise_prefy'];

            $monthData = [];

        foreach ($month_sumchart as $primary) {
            if ($primary->month == $primary->month) {
                $ratio = $primary->sale != 0 ? (($primary->sale / $primary->man)) : 0;
                $cost = $primary->sale != 0 ? (($primary->expence / $primary->sale * 100)) : 0;
        
                // Cast ratio and cost to float to ensure they are passed correctly to number_format
                $ratio = (float)str_replace(',', '', $ratio);
                $cost = (float)$cost;
        
                $monthData[] = [
                    'name' => $primary->month,
                    'ratio' => $ratio !== 0 ? number_format($ratio, 0, '', '') : "0",
                    'cost' => $cost !== 0 ? number_format($cost, 0) : "0",
                ];
            }
        }


            $this->data['month_pre_fy'] = json_encode($monthData);
        } else {
            // month wise cur FY

            $this->data['month_wise'] = \DB::select("SELECT month,c_year,
        
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = 'Salary\\\\Exp.\\\\Incen.' THEN asseesable_value ELSE 0 END), 0) as expence,
    ROUND(SUM(CASE WHEN data_type = 'Manpower' THEN asseesable_value END), 0) AS man

    FROM
      sd_primarydataupload_t
    WHERE
     f_year='$cur_fyear' $wh $notin  AND  STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%M-%d') <= STR_TO_DATE('$current_year-$current_month-01', '%Y-%M-%d')
    GROUP BY
     month ORDER BY c_year,STR_TO_DATE(CONCAT('001', month, ' 01'), '%Y %M %d')");


            // month wise Pre FY

            $this->data['month_wise_prefy'] = \DB::select("SELECT month,c_year,
        
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = 'Salary\\\\Exp.\\\\Incen.' THEN asseesable_value ELSE 0 END), 0) as expence,
    ROUND(SUM(CASE WHEN data_type = 'Manpower' THEN asseesable_value END), 0) AS man

    FROM
      sd_primarydataupload_t
    WHERE
     f_year='$pre_fyear' $wh $notin 
    GROUP BY
     month ORDER BY c_year,STR_TO_DATE(CONCAT('001', month, ' 01'), '%Y %M %d')");

            // month chart cur fy 
            $pro_sumchart = $this->data['month_wise'];

            $primData = [];
            foreach ($pro_sumchart as $primary) {

                if ($primary->month == $primary->month) {

                    $ratio = $primary->man != 0 ? (($primary->sale / $primary->man)) : null;
                    $cost = $primary->sale != 0 ? (($primary->expence / $primary->sale * 100)) : null;
                    $ratio = str_replace(',', '', $ratio);
                    $primData[] = [
                        'name' => $primary->month,
                        'ratio' => $ratio != 0 ? number_format($ratio, 0, '', '') : "0",
                        'cost' => $cost != 0 ? number_format($cost, 0) : "0",

                    ];
                }
            }

            $this->data['pro_sumchart'] = json_encode($primData);
            // month chart pre fy

            $month_sumchart = $this->data['month_wise_prefy'];

            $monthData = [];

            foreach ($month_sumchart as $primary) {

                if ($primary->month == $primary->month) {

                    $ratio = $primary->man != 0 ? (($primary->sale / $primary->man)) : null;
                    $cost = $primary->sale != 0 ? (($primary->expence / $primary->sale * 100)) : null;
                    $ratio = str_replace(',', '', $ratio);

                    $monthData[] = [
                        'name' => $primary->month,
                        'ratio' => $ratio != 0 ? number_format($ratio, 0, '', '') : "0",
                        'cost' => $cost != 0 ? number_format($cost, 0) : "0",

                    ];
                }
            }

            $this->data['month_pre_fy'] = json_encode($monthData);
        }
        return view('primarycostratio.month', $this->data);
    }

    // ZoneWise Report
    public function ZoneWise(Request $request)
    {

        $wh = '';
               // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        // RSM region compare purpose
        $em_reg = \DB::select("SELECT job_title from hr_employee_t where employee_id='$emp_id'");
        $emp_reg = $em_reg[0]->job_title;

          if ($depart == "14" && $emp_id !='121' ) {

            // RSM region compare purpose
            if ($emp_reg == '41') {

                $emp_region = '';
                // emp region 
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

                if (!empty($em_region)) {
                    $regions = array_column($em_region, 'region');
                    $emp_region = "'" . implode("','", $regions) . "'";
                    $wh = " AND sd_primarydataupload_t.region IN ($emp_region)";  
                } else {
                    $wh = " AND sd_primarydataupload_t.region IN ('')";
                }
                //end
            } else {
                //others

                $emp_zone = '';
                // emp zone 
                $em_zone = \DB::select("SELECT zone from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

                if (!empty($em_zone)) {
                    if (isset($em_zone[0]->zone) && $em_zone[0]->zone != '') {
                        $emp_zone = $em_zone[0]->zone;
                    } else {
                        $emp_zone = '';
                    }
                }

                if (!empty($emp_zone)) {
                    $wh = "  AND sd_primarydataupload_t.zone ='$emp_zone'";
                } else {
                    $wh = " AND sd_primarydataupload_t.zone IN ('')";
                }
            }
        
            // raja ram only show andra state purpose
            
            if($emp_id == '94'){
                   
                $wh = " AND sd_primarydataupload_t.state IN ('ANDHRA PRADESH')";
                
            }
     }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $l_dbmon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $this->data['last_yr'] =  $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $condition = '';
        $current_y = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $current_year = $current_y[0]->c_year;

        $current_mon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $current_month = $current_mon[0]->month;
        // dd($current_month);

        $this->data['select_year'] = $current_year - 1;
        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $cur_fyear = $this->data['fy_year'][0]->f_year;

        // defalt previous fy_years
        $pre_cur_year = substr($cur_fyear, 0, 4) - 1;
        $last_preyear = substr($cur_fyear, 5, 7) - 1;
        // Concatenating the current and next year with appropriate formatting
        $pre_fyear = $pre_cur_year . '-' . $last_preyear;

        $this->data['cur_fy_year'] = $cur_fyear;
        $this->data['pre_fy_year'] = $pre_fyear;
        /// end default fy years   

        // Get start date from the request
        $select_date = $request->input('date_select');
        // Extract year and month from the start date
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('M', strtotime($select_date));
        $selected_month = date('m', strtotime($select_date));

        // Calculate next and previous years
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;

        // Determine the financial year based on the start month (assuming April is the start of the financial year)

        $fy_full_year = '';


        if ($selected_month >= 4) {

            $fy_full_year =  $select_year  . '-' . ($select_year + 1);
        } else {

            $fy_full_year =  ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);

        $select_pre = substr($fy_year, 0, 4);
        $select_pre_y = substr($fy_year, 5, 2);

        $select_pre_year =  ($select_pre - 1) . '-' . ($select_pre_y - 1);

        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;

            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = date('M', strtotime($last_m));
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
            }

            // zone wise cur FY

            $this->data['month_wise'] = \DB::select("SELECT zone,c_year,
    
            ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
            ROUND(SUM(CASE WHEN data_type = 'Salary\\\\Exp.\\\\Incen.' THEN asseesable_value ELSE 0 END), 0) as expence,
            ROUND(SUM(CASE WHEN data_type = 'Manpower' THEN asseesable_value END), 0) AS man

            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$fy_year' $condition $wh $notin  AND  STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%M-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%M-%d') 
            GROUP BY zone ");


            // month wise Pre FY

            $this->data['month_wise_prefy'] = \DB::select("SELECT zone,c_year,
                
            ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
            ROUND(SUM(CASE WHEN data_type = 'Salary\\\\Exp.\\\\Incen.' THEN asseesable_value ELSE 0 END), 0) as expence,
            ROUND(SUM(CASE WHEN data_type = 'Manpower' THEN asseesable_value END), 0) AS man

            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$select_pre_year' $condition $wh $notin 
            GROUP BY zone");

            // month chart cur fy 
            $pro_sumchart = $this->data['month_wise'];

            $primData = [];
            foreach ($pro_sumchart as $primary) {

                if ($primary->zone == $primary->zone) {

                    $ratio = $primary->man != 0 ? (($primary->sale / $primary->man)) : null;
                    $cost = $primary->sale != 0 ? (($primary->expence / $primary->sale * 100)) : null;
                    $ratio = str_replace(',', '', $ratio);
                    $primData[] = [
                        'name' => $primary->zone,
                        'ratio' => $ratio !== 0 ? number_format($ratio, 0, '', '') : "0",
                        'cost' => $cost !== 0 ? number_format($cost, 0) : "0",

                    ];
                }
            }

            $this->data['pro_sumchart'] = json_encode($primData);

            // zone chart pre fy

            $month_sumchart = $this->data['month_wise_prefy'];

            $monthData = [];

            foreach ($month_sumchart as $primary) {

                if ($primary->zone == $primary->zone) {

                    $ratio = $primary->man != 0 ? (($primary->sale / $primary->man)) : null;
                    $cost = $primary->sale != 0 ? (($primary->expence / $primary->sale * 100)) : null;
                    $ratio = str_replace(',', '', $ratio);

                    $monthData[] = [
                        'name' => $primary->zone,
                        'ratio' => $ratio !== 0 ? number_format($ratio, 0, '', '') : "0",
                        'cost' => $cost !== 0 ? number_format($cost, 0) : "0",

                    ];
                }
            }

            $this->data['month_pre_fy'] = json_encode($monthData);
        } else {

            // zone wise cur FY

            $this->data['month_wise'] = \DB::select("SELECT zone,c_year,
    
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = 'Salary\\\\Exp.\\\\Incen.' THEN asseesable_value ELSE 0 END), 0) as expence,
        ROUND(SUM(CASE WHEN data_type = 'Manpower' THEN asseesable_value END), 0) AS man

        FROM
        sd_primarydataupload_t
        WHERE
        f_year='$cur_fyear' $wh AND  STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%M-%d') <= STR_TO_DATE('$current_year-$current_month-01', '%Y-%M-%d') $notin 
        GROUP BY zone ");


            // month wise Pre FY

            $this->data['month_wise_prefy'] = \DB::select("SELECT zone,c_year,
    
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = 'Salary\\\\Exp.\\\\Incen.' THEN asseesable_value ELSE 0 END), 0) as expence,
        ROUND(SUM(CASE WHEN data_type = 'Manpower' THEN asseesable_value END), 0) AS man

        FROM
        sd_primarydataupload_t
        WHERE
        f_year='$pre_fyear' $wh $notin 
        GROUP BY
        zone");

            // zone chart cur fy 
            $pro_sumchart = $this->data['month_wise'];

            $primData = [];
            foreach ($pro_sumchart as $primary) {

                if ($primary->zone == $primary->zone) {

                    $ratio = $primary->man != 0 ? (($primary->sale / $primary->man)) :  "0";
                    $cost = $primary->sale != 0 ? (($primary->expence / $primary->sale * 100)) :  "0";
                    $ratio = str_replace(',', '', $ratio);
                    $primData[] = [
                        'name' => $primary->zone,
                        'ratio' => $ratio !== 0 ? number_format($ratio, 0, '', '') : "0",
                        'cost' => $cost !== 0 ? number_format($cost, 0) : "0",

                    ];
                }
            }

            $this->data['pro_sumchart'] = json_encode($primData);

            // zone chart pre fy

            $month_sumchart = $this->data['month_wise_prefy'];

            $monthData = [];

            foreach ($month_sumchart as $primary) {

                if ($primary->zone == $primary->zone) {

                    $ratio = $primary->sale != 0 ? (($primary->sale / $primary->man)) : "0";
                    $cost = $primary->sale != 0 ? (($primary->expence / $primary->sale * 100)) : "0";
                    $ratio = str_replace(',', '', $ratio);

                    $monthData[] = [
                        'name' => $primary->zone,
                        'ratio' => $ratio !== 0 ? number_format($ratio, 0, '', '') : "0",
                        'cost' => $cost !== 0 ? number_format($cost, 0) : "0",

                    ];
                }
            }

            $this->data['month_pre_fy'] = json_encode($monthData);
        }
        return view('primarycostratio.zone', $this->data);
    }

    // RegionWise Report
    public function RegionWise(Request $request)
    {

        $wh = '';
               // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        // RSM region compare purpose
        $em_reg = \DB::select("SELECT job_title from hr_employee_t where employee_id='$emp_id'");
        $emp_reg = $em_reg[0]->job_title;

          if ($depart == "14" && $emp_id !='121' ) {

            // RSM region compare purpose
            if ($emp_reg == '41') {

                $emp_region = '';
                // emp region 
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

                if (!empty($em_region)) {
                    $regions = array_column($em_region, 'region');
                    $emp_region = "'" . implode("','", $regions) . "'";
                    $wh = " AND sd_primarydataupload_t.region IN ($emp_region)";  
                } else {
                    $wh = " AND sd_primarydataupload_t.region IN ('')";
                }
                //end
            } else {
                //others

                $emp_zone = '';
                // emp zone 
                $em_zone = \DB::select("SELECT zone from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

                if (!empty($em_zone)) {
                    if (isset($em_zone[0]->zone) && $em_zone[0]->zone != '') {
                        $emp_zone = $em_zone[0]->zone;
                    } else {
                        $emp_zone = '';
                    }
                }

                if (!empty($emp_zone)) {
                    $wh = "  AND sd_primarydataupload_t.zone ='$emp_zone'";
                } else {
                    $wh = " AND sd_primarydataupload_t.zone IN ('')";
                }
            }
      
            // raja ram only show andra state purpose
            
            if($emp_id == '94'){
                   
                $wh = " AND sd_primarydataupload_t.state IN ('ANDHRA PRADESH')";
                
            }
     }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $l_dbmon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $this->data['last_yr'] =  $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $condition = '';
        $current_y = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $current_year = $current_y[0]->c_year;

        $current_mon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $current_month = $current_mon[0]->month;
        // dd($current_month);

        $this->data['select_year'] = $current_year - 1;
        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $cur_fyear = $this->data['fy_year'][0]->f_year;

        // defalt previous fy_years
        $pre_cur_year = substr($cur_fyear, 0, 4) - 1;
        $last_preyear = substr($cur_fyear, 5, 7) - 1;
        // Concatenating the current and next year with appropriate formatting
        $pre_fyear = $pre_cur_year . '-' . $last_preyear;

        $this->data['cur_fy_year'] = $cur_fyear;
        $this->data['pre_fy_year'] = $pre_fyear;
        /// end default fy years   

        // Get start date from the request
        $select_date = $request->input('date_select');
        // Extract year and month from the start date
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('M', strtotime($select_date));
        $selected_month = date('m', strtotime($select_date));

        // Calculate next and previous years
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;

        // Determine the financial year based on the start month (assuming April is the start of the financial year)

        $fy_full_year = '';


        if ($selected_month >= 4) {

            $fy_full_year =  $select_year  . '-' . ($select_year + 1);
        } else {

            $fy_full_year =  ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);

        $select_pre = substr($fy_year, 0, 4);
        $select_pre_y = substr($fy_year, 5, 2);

        $select_pre_year =  ($select_pre - 1) . '-' . ($select_pre_y - 1);

        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;

            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = date('M', strtotime($last_m));
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
            }

            // month wise cur FY

            $this->data['month_wise'] = \DB::select("SELECT region,c_year,
        
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = 'Salary\\\\Exp.\\\\Incen.' THEN asseesable_value ELSE 0 END), 0) as expence,
    ROUND(SUM(CASE WHEN data_type = 'Manpower' THEN asseesable_value END), 0) AS man
    
    FROM
        sd_primarydataupload_t
    WHERE
        f_year='$fy_year' $condition $wh AND  STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%M-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%M-%d') $notin 
    GROUP BY region HAVING (sale !='0' OR expence !='0' OR man !='0')");


            // month wise Pre FY

            $this->data['month_wise_prefy'] = \DB::select("SELECT region,c_year,
       
   ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
   ROUND(SUM(CASE WHEN data_type = 'Salary\\\\Exp.\\\\Incen.' THEN asseesable_value ELSE 0 END), 0) as expence,
   ROUND(SUM(CASE WHEN data_type = 'Manpower' THEN asseesable_value END), 0) AS man
   
   FROM
     sd_primarydataupload_t
   WHERE
    f_year='$select_pre_year' $condition $wh $notin 
   GROUP BY region HAVING (sale !='0' OR expence !='0' OR man !='0')");

            // month chart cur fy 
            $pro_sumchart = $this->data['month_wise'];

            $primData = [];
            foreach ($pro_sumchart as $primary) {

                if ($primary->region == $primary->region) {

                    $ratio = $primary->man != 0 ? (($primary->sale / $primary->man)) : "0";
                    $cost = $primary->sale != 0 ? (($primary->expence / $primary->sale * 100)) : "0";
                    $ratio = str_replace(',', '', $ratio);
                    $primData[] = [
                        'name' => $primary->region,
                        'ratio' => $ratio !== 0 ? number_format($ratio, 0, '', '') : "0",
                        'cost' => $cost !== 0 ? number_format($cost, 0) : "0",

                    ];
                }
            }

            $this->data['pro_sumchart'] = json_encode($primData);

            // region chart pre fy

            $month_sumchart = $this->data['month_wise_prefy'];

            $monthData = [];

            foreach ($month_sumchart as $primary) {

                if ($primary->region == $primary->region) {

                    $ratio = $primary->sale != 0 ? (($primary->man / $primary->man)) : "0";
                    $cost = $primary->sale != 0 ? (($primary->expence / $primary->sale * 100)) : "0";
                    $ratio = str_replace(',', '', $ratio);

                    $monthData[] = [

                        'name' => $primary->region,
                        'ratio' => $ratio !== 0 ? number_format(floatval($ratio), 0, '', '') : "0",
                        'cost' => $cost !== 0 ? number_format(floatval($cost), 0) : "0",



                    ];
                }
            }

            $this->data['month_pre_fy'] = json_encode($monthData);
        } else {

            // region wise cur FY

            $this->data['month_wise'] = \DB::select("SELECT region,c_year,
       
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = 'Salary\\\\Exp.\\\\Incen.' THEN asseesable_value ELSE 0 END), 0) as expence,
    ROUND(SUM(CASE WHEN data_type = 'Manpower' THEN asseesable_value END), 0) AS man
    
    FROM
        sd_primarydataupload_t
    WHERE
        f_year='$cur_fyear' $wh AND  STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%M-%d') <= STR_TO_DATE('$current_year-$current_month-01', '%Y-%M-%d') $notin 
    GROUP BY region HAVING (sale !='0' OR expence !='0' OR man !='0') ");


            // month wise Pre FY

            $this->data['month_wise_prefy'] = \DB::select("SELECT region,c_year,
       
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = 'Salary\\\\Exp.\\\\Incen.' THEN asseesable_value ELSE 0 END), 0) as expence,
        ROUND(SUM(CASE WHEN data_type = 'Manpower' THEN asseesable_value END), 0) AS man
        
        FROM
            sd_primarydataupload_t
        WHERE
            f_year='$pre_fyear' $wh $notin 
        GROUP BY
        region HAVING (sale !='0' OR expence !='0' OR man !='0')");

            // region chart cur fy 
            $pro_sumchart = $this->data['month_wise'];

            $primData = [];
            foreach ($pro_sumchart as $primary) {

                if ($primary->region == $primary->region) {

                    $ratio = $primary->man != 0 ? (($primary->sale / $primary->man)) : "0";
                    $cost = $primary->sale != 0 ? (($primary->expence / $primary->sale * 100)) : "0";
                    $ratio = str_replace(',', '', $ratio);
                    $primData[] = [
                        'name' => $primary->region,
                        'ratio' => $ratio !== 0 ? number_format($ratio, 0, '', '') : "0",
                        'cost' => $cost !== 0 ? number_format($cost, 0) : "0",

                    ];
                }
            }

            $this->data['pro_sumchart'] = json_encode($primData);

            // region chart pre fy

            $month_sumchart = $this->data['month_wise_prefy'];

            $monthData = [];

            foreach ($month_sumchart as $primary) {

                if ($primary->region == $primary->region) {

                    $ratio = $primary->man != 0 ? (($primary->sale / $primary->man)) : "0";
                    $cost = $primary->sale != 0 ? (($primary->expence / $primary->sale * 100)) : "0";
                    $ratio = str_replace(',', '', $ratio);

                    $monthData[] = [
                        'name' => $primary->region,
                        'ratio' => $ratio !== 0 ? number_format($ratio, 0, '', '') : "0",
                        'cost' => $cost !== 0 ? number_format($cost, 0) : "0",

                    ];
                }
            }

            $this->data['month_pre_fy'] = json_encode($monthData);
        }
        return view('primarycostratio.region', $this->data);
    }

    // StateWise Report
    public function StateWise(Request $request)
    {

        $wh = '';
               // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        // RSM region compare purpose
        $em_reg = \DB::select("SELECT job_title from hr_employee_t where employee_id='$emp_id'");
        $emp_reg = $em_reg[0]->job_title;

          if ($depart == "14" && $emp_id !='121' ) {

            // RSM region compare purpose
            if ($emp_reg == '41') {

                $emp_region = '';
                // emp region 
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

                if (!empty($em_region)) {
                    $regions = array_column($em_region, 'region');
                    $emp_region = "'" . implode("','", $regions) . "'";
                    $wh = " AND sd_primarydataupload_t.region IN ($emp_region)";  
                } else {
                    $wh = " AND sd_primarydataupload_t.region IN ('')";
                }
                //end
            } else {
                //others

                $emp_zone = '';
                // emp zone 
                $em_zone = \DB::select("SELECT zone from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

                if (!empty($em_zone)) {
                    if (isset($em_zone[0]->zone) && $em_zone[0]->zone != '') {
                        $emp_zone = $em_zone[0]->zone;
                    } else {
                        $emp_zone = '';
                    }
                }

                if (!empty($emp_zone)) {
                    $wh = "  AND sd_primarydataupload_t.zone ='$emp_zone'";
                } else {
                    $wh = " AND sd_primarydataupload_t.zone IN ('')";
                }
            }
       
            // raja ram only show andra state purpose
            
            if($emp_id == '94'){
                   
                $wh = " AND sd_primarydataupload_t.state IN ('ANDHRA PRADESH')";
                
            }
     }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $l_dbmon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $this->data['last_yr'] =  $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $condition = '';
        $current_y = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $current_year = $current_y[0]->c_year;

        $current_mon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $current_month = $current_mon[0]->month;
        // dd($current_month);

        $this->data['select_year'] = $current_year - 1;
        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $cur_fyear = $this->data['fy_year'][0]->f_year;

        // defalt previous fy_years
        $pre_cur_year = substr($cur_fyear, 0, 4) - 1;
        $last_preyear = substr($cur_fyear, 5, 7) - 1;
        // Concatenating the current and next year with appropriate formatting
        $pre_fyear = $pre_cur_year . '-' . $last_preyear;

        $this->data['cur_fy_year'] = $cur_fyear;
        $this->data['pre_fy_year'] = $pre_fyear;
        /// end default fy years   

        // Get start date from the request
        $select_date = $request->input('date_select');
        // Extract year and month from the start date
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('M', strtotime($select_date));
        $selected_month = date('m', strtotime($select_date));

        // Calculate next and previous years
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;

        // Determine the financial year based on the start month (assuming April is the start of the financial year)

        $fy_full_year = '';


        if ($selected_month >= 4) {

            $fy_full_year =  $select_year  . '-' . ($select_year + 1);
        } else {

            $fy_full_year =  ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);

        $select_pre = substr($fy_year, 0, 4);
        $select_pre_y = substr($fy_year, 5, 2);

        $select_pre_year =  ($select_pre - 1) . '-' . ($select_pre_y - 1);

        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;

            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = date('M', strtotime($last_m));
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
            }

            // month wise cur FY

            $this->data['month_wise'] = \DB::select("SELECT state,c_year,
       
   ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
   ROUND(SUM(CASE WHEN data_type = 'Salary\\\\Exp.\\\\Incen.' THEN asseesable_value ELSE 0 END), 0) as expence,
   ROUND(SUM(CASE WHEN data_type = 'Manpower' THEN asseesable_value END), 0) AS man
   
   FROM
     sd_primarydataupload_t
   WHERE
    f_year='$fy_year' $condition $wh AND  STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%M-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%M-%d')  $notin 
   GROUP BY state ");


            // month wise Pre FY

            $this->data['month_wise_prefy'] = \DB::select("SELECT state,c_year,
       
   ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
   ROUND(SUM(CASE WHEN data_type = 'Salary\\\\Exp.\\\\Incen.' THEN asseesable_value ELSE 0 END), 0) as expence,
   ROUND(SUM(CASE WHEN data_type = 'Manpower' THEN asseesable_value END), 0) AS man
   
   FROM
     sd_primarydataupload_t
   WHERE
    f_year='$select_pre_year' $condition $wh  $notin 
   GROUP BY state ");

            // month chart cur fy 
            $pro_sumchart = $this->data['month_wise'];

            $primData = [];
            foreach ($pro_sumchart as $primary) {

                if ($primary->state == $primary->state) {

                    $ratio = $primary->man != 0 ? (($primary->sale / $primary->man)) : "0";
                    $cost = $primary->sale != 0 ? (($primary->expence / $primary->sale * 100)) : null;
                    $ratio = str_replace(',', '', $ratio);
                    $primData[] = [
                        'name' => $primary->state,
                        'ratio' => $ratio !== 0 ? number_format($ratio, 0, '', '') : "0",
                        'cost' => $cost !== 0 ? number_format($cost, 0) : "0",

                    ];
                }
            }

            $this->data['pro_sumchart'] = json_encode($primData);

            // state chart pre fy

            $month_sumchart = $this->data['month_wise_prefy'];

            $monthData = [];

            foreach ($month_sumchart as $primary) {

                if ($primary->state == $primary->state) {

                    $ratio = $primary->man != 0 ? (($primary->sale / $primary->man)) : "0";
                    $cost = $primary->sale != 0 ? (($primary->expence / $primary->sale * 100)) : "0";
                    $ratio = str_replace(',', '', $ratio);

                    $monthData[] = [
                        'name' => $primary->state,
                        'ratio' => $ratio !== 0 ? number_format($ratio, 0, '', '') : "0",
                        'cost' => $cost !== 0 ? number_format($cost, 0) : "0",

                    ];
                }
            }

            $this->data['month_pre_fy'] = json_encode($monthData);
        } else {

            // state wise cur FY

            $this->data['month_wise'] = \DB::select("SELECT state,c_year,
       
   ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
   ROUND(SUM(CASE WHEN data_type = 'Salary\\\\Exp.\\\\Incen.' THEN asseesable_value ELSE 0 END), 0) as expence,
   ROUND(SUM(CASE WHEN data_type = 'Manpower' THEN asseesable_value END), 0) AS man
   
   FROM
     sd_primarydataupload_t
   WHERE
    f_year='$cur_fyear' $wh AND  STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%M-%d') <= STR_TO_DATE('$current_year-$current_month-01', '%Y-%M-%d')  $notin 
   GROUP BY state ");


            // month wise Pre FY

            $this->data['month_wise_prefy'] = \DB::select("SELECT state,c_year,
       
   ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
   ROUND(SUM(CASE WHEN data_type = 'Salary\\\\Exp.\\\\Incen.' THEN asseesable_value ELSE 0 END), 0) as expence,
   ROUND(SUM(CASE WHEN data_type = 'Manpower' THEN asseesable_value END), 0) AS man
   
   FROM
     sd_primarydataupload_t
   WHERE
    f_year='$pre_fyear' $wh  $notin 
   GROUP BY
   state ");

            // state chart cur fy 
            $pro_sumchart = $this->data['month_wise'];

            $primData = [];
            foreach ($pro_sumchart as $primary) {

                if ($primary->state == $primary->state) {

                    $ratio = $primary->man != 0 ? (($primary->sale / $primary->man)) : "0";
                    $cost = $primary->sale != 0 ? (($primary->expence / $primary->sale * 100)) : "0";
                    $ratio = str_replace(',', '', $ratio);
                    $primData[] = [
                        'name' => $primary->state,
                        'ratio' => $ratio !== 0 ? number_format($ratio, 0) : "0",
                        'cost' => $cost !== 0 ? number_format($cost, 0) : "0",

                    ];
                }
            }

            $this->data['pro_sumchart'] = json_encode($primData);

            // state chart pre fy

            $month_sumchart = $this->data['month_wise_prefy'];

            $monthData = [];

            foreach ($month_sumchart as $primary) {

                if ($primary->state == $primary->state) {

                    $ratio = $primary->man != 0 ? (($primary->sale / $primary->man)) : "0";
                    $cost = $primary->sale != 0 ? (($primary->expence / $primary->sale * 100)) : "0";
                    $ratio = str_replace(',', '', $ratio);

                    $monthData[] = [
                        'name' => $primary->state,
                        'ratio' => $ratio !== 0 ? number_format($ratio, 0) : "0",
                        'cost' => $cost !== 0 ? number_format($cost, 0) : "0",

                    ];
                }
            }

            $this->data['month_pre_fy'] = json_encode($monthData);
        }
        return view('primarycostratio.state', $this->data);
    }

    // Costdistribution Report
    public function Costdistribution(Request $request)
    {

        $wh = '';
               // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        // RSM region compare purpose
        $em_reg = \DB::select("SELECT job_title from hr_employee_t where employee_id='$emp_id'");
        $emp_reg = $em_reg[0]->job_title;

          if ($depart == "14" && $emp_id !='121' ) {

            // RSM region compare purpose
            if ($emp_reg == '41') {

                $emp_region = '';
                // emp region 
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

                if (!empty($em_region)) {
                    $regions = array_column($em_region, 'region');
                    $emp_region = "'" . implode("','", $regions) . "'";
                    $wh = " AND sd_primarydataupload_t.region IN ($emp_region)";  
                } else {
                    $wh = " AND sd_primarydataupload_t.region IN ('')";
                }
                //end
            } else {
                //others

                $emp_zone = '';
                // emp zone 
                $em_zone = \DB::select("SELECT zone from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

                if (!empty($em_zone)) {
                    if (isset($em_zone[0]->zone) && $em_zone[0]->zone != '') {
                        $emp_zone = $em_zone[0]->zone;
                    } else {
                        $emp_zone = '';
                    }
                }

                if (!empty($emp_zone)) {
                    $wh = "  AND sd_primarydataupload_t.zone ='$emp_zone'";
                } else {
                    $wh = " AND sd_primarydataupload_t.zone IN ('')";
                }
            }
        
            // raja ram only show andra state purpose
            
            if($emp_id == '94'){
                   
                $wh = " AND sd_primarydataupload_t.state IN ('ANDHRA PRADESH')";
                
            }
     }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $l_dbmon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $this->data['last_yr'] =  $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['states'] = \DB::select("SELECT DISTINCT state_consolidated as state from `sd_primarydataupload_t` where data_type NOT IN ('Oversease') $notin");
        $state = $request->input('state');
        $condition = '';
        $current_y = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $current_year = $current_y[0]->c_year;

        $current_mon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $current_month = $current_mon[0]->month;
        // dd($current_month);

        $this->data['select_year'] = $current_year - 1;
        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $cur_fyear = $this->data['fy_year'][0]->f_year;

        // defalt previous fy_years
        $pre_cur_year = substr($cur_fyear, 0, 4) - 1;
        $last_preyear = substr($cur_fyear, 5, 7) - 1;
        // Concatenating the current and next year with appropriate formatting
        $pre_fyear = $pre_cur_year . '-' . $last_preyear;

        $this->data['cur_fy_year'] = $cur_fyear;
        $this->data['pre_fy_year'] = $pre_fyear;
        /// end default fy years   

        // Get start date from the request
        $select_date = $request->input('date_select');
        // Extract year and month from the start date
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('M', strtotime($select_date));
        $selected_month = date('m', strtotime($select_date));

        // Calculate next and previous years
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;

        // Determine the financial year based on the start month (assuming April is the start of the financial year)

        $fy_full_year = '';


        if ($selected_month >= 4) {

            $fy_full_year =  $select_year  . '-' . ($select_year + 1);
        } else {

            $fy_full_year =  ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);

        $select_pre = substr($fy_year, 0, 4);
        $select_pre_y = substr($fy_year, 5, 2);

        $select_pre_year =  ($select_pre - 1) . '-' . ($select_pre_y - 1);

        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '' || $state != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;


            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }

            if ($state != '') {
                $condition = "AND state ='$state'";
            }
            if ($state != '' && $region != '') {
                $condition = "AND state_consolidated='$state' AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($zone != '' && $region != '' && $state != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND state_consolidated='$state'";
            }

            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = date('M', strtotime($last_m));
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
            }

            // month wise cur FY
            $this->data['sales_trend'] = \DB::select("SELECT region,state_consolidated as state,f_year,month,stockist_dist_name as name,ROUND(ROUND(SUM(asseesable_value),0),0) as sale
        
            FROM
            sd_primarydataupload_t
        WHERE
        f_year='$fy_year' AND data_type='Salary\\\\Exp.\\\\Incen.' AND name_type='PERSONS' 
        
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%M-%d') $notin  $condition $wh

        GROUP BY 
            region, month,state_consolidated,stockist_dist_name,f_year ORDER BY STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        } else {

            $this->data['sales_trend'] = \DB::select("SELECT region,state_consolidated as state,f_year,month,stockist_dist_name as name,ROUND(ROUND(SUM(asseesable_value),0),0) as sale
        
            FROM
            sd_primarydataupload_t
        WHERE
        f_year='$cur_fyear' AND data_type='Salary\\\\Exp.\\\\Incen.' AND name_type='PERSONS' 
        
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$current_year-$current_month-01', '%Y-%b-%d') $notin  $wh

        GROUP BY 
            region, month,state_consolidated,stockist_dist_name,f_year ORDER BY STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        }

        return view('primarycostratio.percostdist', $this->data);
    }


    // tdistribution Saletrend Report
    public function Saletrend(Request $request)
    {

        $wh = '';
             // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        // RSM region compare purpose
        $em_reg = \DB::select("SELECT job_title from hr_employee_t where employee_id='$emp_id'");
        $emp_reg = $em_reg[0]->job_title;

          if ($depart == "14" && $emp_id !='121' ) {

            // RSM region compare purpose
            if ($emp_reg == '41') {

                $emp_region = '';
                // emp region 
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

                if (!empty($em_region)) {
                    $regions = array_column($em_region, 'region');
                    $emp_region = "'" . implode("','", $regions) . "'";
                    $wh = " AND sd_primarydataupload_t.region IN ($emp_region)";  
                } else {
                    $wh = " AND sd_primarydataupload_t.region IN ('')";
                }
                //end
            } else {
                //others

                $emp_zone = '';
                // emp zone 
                $em_zone = \DB::select("SELECT zone from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

                if (!empty($em_zone)) {
                    if (isset($em_zone[0]->zone) && $em_zone[0]->zone != '') {
                        $emp_zone = $em_zone[0]->zone;
                    } else {
                        $emp_zone = '';
                    }
                }

                if (!empty($emp_zone)) {
                    $wh = "  AND sd_primarydataupload_t.zone ='$emp_zone'";
                } else {
                    $wh = " AND sd_primarydataupload_t.zone IN ('')";
                }
            }
        
            // raja ram only show andra state purpose
            
            if($emp_id == '94'){
                   
                $wh = " AND sd_primarydataupload_t.state IN ('ANDHRA PRADESH')";
                
            }
     }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $l_dbmon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $this->data['last_yr'] =  $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['states'] = \DB::select("SELECT DISTINCT state_consolidated as state from `sd_primarydataupload_t` where data_type NOT IN ('Oversease') $notin");
        $state = $request->input('state');
        $condition = '';
        $current_y = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $current_year = $current_y[0]->c_year;

        $current_mon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $current_month = $current_mon[0]->month;
        // dd($current_month);

        $this->data['select_year'] = $current_year - 1;
        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $cur_fyear = $this->data['fy_year'][0]->f_year;

        // defalt previous fy_years
        $pre_cur_year = substr($cur_fyear, 0, 4) - 1;
        $last_preyear = substr($cur_fyear, 5, 7) - 1;
        // Concatenating the current and next year with appropriate formatting
        $pre_fyear = $pre_cur_year . '-' . $last_preyear;

        $this->data['cur_fy_year'] = $cur_fyear;
        $this->data['pre_fy_year'] = $pre_fyear;
        /// end default fy years   

        // Get start date from the request
        $select_date = $request->input('date_select');
        // Extract year and month from the start date
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('M', strtotime($select_date));
        $selected_month = date('m', strtotime($select_date));

        // Calculate next and previous years
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;

        // Determine the financial year based on the start month (assuming April is the start of the financial year)

        $fy_full_year = '';


        if ($selected_month >= 4) {

            $fy_full_year =  $select_year  . '-' . ($select_year + 1);
        } else {

            $fy_full_year =  ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);

        $select_pre = substr($fy_year, 0, 4);
        $select_pre_y = substr($fy_year, 5, 2);

        $select_pre_year =  ($select_pre - 1) . '-' . ($select_pre_y - 1);

        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '' || $state != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;

            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }

            if ($state != '') {
                $condition = "AND state ='$state'";
            }
            if ($state != '' && $region != '') {
                $condition = "AND state_consolidated='$state' AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($zone != '' && $region != '' && $state != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND state_consolidated='$state'";
            }

            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = date('M', strtotime($last_m));
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
            }

            // month wise cur FY
            $this->data['sales_trend'] = \DB::select("SELECT region,state_consolidated as state,f_year,month,stockist_dist_name as name,ROUND(SUM(asseesable_value),0) as sale
        
            FROM
            sd_primarydataupload_t
        WHERE
        f_year='$fy_year' AND name_type='DISTRIBUTORS' 
        
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%M-%d')  $notin  $condition $wh

        GROUP BY 
            region, month,state_consolidated,stockist_dist_name,f_year HAVING sale !='0' ORDER BY STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        } else {

            $this->data['sales_trend'] = \DB::select("SELECT region,state_consolidated as state,f_year,month,stockist_dist_name as name,ROUND(SUM(asseesable_value),0) as sale
        
            FROM
            sd_primarydataupload_t
        WHERE
        f_year='$cur_fyear' AND name_type='DISTRIBUTORS' 
        
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$current_year-$current_month-01', '%Y-%b-%d')  $notin  $wh

        GROUP BY 
            region, month,state_consolidated,stockist_dist_name,f_year HAVING sale !='0' ORDER BY STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        }

        return view('primarycostratio.distwisesale', $this->data);
    }

    // Manpower Report
    public function Manpower(Request $request)
    {

        $wh = '';
               // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        // RSM region compare purpose
        $em_reg = \DB::select("SELECT job_title from hr_employee_t where employee_id='$emp_id'");
        $emp_reg = $em_reg[0]->job_title;

          if ($depart == "14" && $emp_id !='121' ) {

            // RSM region compare purpose
            if ($emp_reg == '41') {

                $emp_region = '';
                // emp region 
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

                if (!empty($em_region)) {
                    $regions = array_column($em_region, 'region');
                    $emp_region = "'" . implode("','", $regions) . "'";
                    $wh = " AND sd_primarydataupload_t.region IN ($emp_region)";  
                } else {
                    $wh = " AND sd_primarydataupload_t.region IN ('')";
                }
                //end
            } else {
                //others

                $emp_zone = '';
                // emp zone 
                $em_zone = \DB::select("SELECT zone from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

                if (!empty($em_zone)) {
                    if (isset($em_zone[0]->zone) && $em_zone[0]->zone != '') {
                        $emp_zone = $em_zone[0]->zone;
                    } else {
                        $emp_zone = '';
                    }
                }

                if (!empty($emp_zone)) {
                    $wh = "  AND sd_primarydataupload_t.zone ='$emp_zone'";
                } else {
                    $wh = " AND sd_primarydataupload_t.zone IN ('')";
                }
            }
       
            // raja ram only show andra state purpose
            
            if($emp_id == '94'){
                   
                $wh = " AND sd_primarydataupload_t.state IN ('ANDHRA PRADESH')";
                
            }
     }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $l_dbmon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $this->data['last_yr'] =  $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['states'] = \DB::select("SELECT DISTINCT state_consolidated as state from `sd_primarydataupload_t` where data_type NOT IN ('Oversease') $notin");
        $state = $request->input('state');
        $condition = '';
        $current_y = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $current_year = $current_y[0]->c_year;

        $current_mon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $current_month = $current_mon[0]->month;
        // dd($current_month);

        $this->data['select_year'] = $current_year - 1;
        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $cur_fyear = $this->data['fy_year'][0]->f_year;

        // defalt previous fy_years
        $pre_cur_year = substr($cur_fyear, 0, 4) - 1;
        $last_preyear = substr($cur_fyear, 5, 7) - 1;
        // Concatenating the current and next year with appropriate formatting
        $pre_fyear = $pre_cur_year . '-' . $last_preyear;

        $this->data['cur_fy_year'] = $cur_fyear;
        $this->data['pre_fy_year'] = $pre_fyear;
        /// end default fy years   

        // Get start date from the request
        $select_date = $request->input('date_select');
        // Extract year and month from the start date
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('M', strtotime($select_date));
        $selected_month = date('m', strtotime($select_date));

        // Calculate next and previous years
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;

        // Determine the financial year based on the start month (assuming April is the start of the financial year)

        $fy_full_year = '';


        if ($selected_month >= 4) {

            $fy_full_year =  $select_year  . '-' . ($select_year + 1);
        } else {

            $fy_full_year =  ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);

        $select_pre = substr($fy_year, 0, 4);
        $select_pre_y = substr($fy_year, 5, 2);

        $select_pre_year =  ($select_pre - 1) . '-' . ($select_pre_y - 1);

        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '' || $state != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;

            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }

            if ($state != '') {
                $condition = "AND state ='$state'";
            }
            if ($state != '' && $region != '') {
                $condition = "AND state_consolidated='$state' AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($zone != '' && $region != '' && $state != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND state_consolidated='$state'";
            }

            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = date('M', strtotime($last_m));
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
            }

            // month wise cur FY
            $this->data['sales_trend'] = \DB::select("SELECT region,state_consolidated as state,f_year,month,stockist_dist_name as name,(SUM(app_man_power) / 100) as sale
        
            FROM
            sd_primarydataupload_t
        WHERE
        f_year='$fy_year' AND name_type='PERSONS' AND data_type = 'Manpower'
        
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%M-%d') $notin  $condition $wh

        GROUP BY 
            region, month,state_consolidated,stockist_dist_name,f_year ORDER BY STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        } else {

            $this->data['sales_trend'] = \DB::select("SELECT region,state_consolidated as state,f_year,month,stockist_dist_name as name,(SUM(app_man_power) / 100) as sale
        
            FROM
            sd_primarydataupload_t
        WHERE
        f_year='$cur_fyear' AND name_type='PERSONS' AND data_type = 'Manpower'  $notin 
        
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$current_year-$current_month-01', '%Y-%b-%d') $wh

        GROUP BY 
            region, month,state_consolidated,stockist_dist_name,f_year ORDER BY STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        }

        return view('primarycostratio.manpower', $this->data);
    }

    // Manpower Report
    public function ManpowerState(Request $request)
    {

        $wh = '';
               // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        // RSM region compare purpose
        $em_reg = \DB::select("SELECT job_title from hr_employee_t where employee_id='$emp_id'");
        $emp_reg = $em_reg[0]->job_title;

          if ($depart == "14" && $emp_id !='121' ) {

            // RSM region compare purpose
            if ($emp_reg == '41') {

                $emp_region = '';
                // emp region 
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

                if (!empty($em_region)) {
                    $regions = array_column($em_region, 'region');
                    $emp_region = "'" . implode("','", $regions) . "'";
                    $wh = " AND sd_primarydataupload_t.region IN ($emp_region)";  
                } else {
                    $wh = " AND sd_primarydataupload_t.region IN ('')";
                }
                //end
            } else {
                //others

                $emp_zone = '';
                // emp zone 
                $em_zone = \DB::select("SELECT zone from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

                if (!empty($em_zone)) {
                    if (isset($em_zone[0]->zone) && $em_zone[0]->zone != '') {
                        $emp_zone = $em_zone[0]->zone;
                    } else {
                        $emp_zone = '';
                    }
                }

                if (!empty($emp_zone)) {
                    $wh = "  AND sd_primarydataupload_t.zone ='$emp_zone'";
                } else {
                    $wh = " AND sd_primarydataupload_t.zone IN ('')";
                }
            }
       
            // raja ram only show andra state purpose
            
            if($emp_id == '94'){
                   
                $wh = " AND sd_primarydataupload_t.state IN ('ANDHRA PRADESH')";
                
            }
     }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $l_dbmon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $this->data['last_yr'] =  $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['states'] = \DB::select("SELECT DISTINCT state_consolidated as state from `sd_primarydataupload_t` where data_type NOT IN ('Oversease') $notin");
        $state = $request->input('state');
        $condition = '';
        $current_y = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $current_year = $current_y[0]->c_year;

        $current_mon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $current_month = $current_mon[0]->month;
        // dd($current_month);

        $this->data['select_year'] = $current_year - 1;
        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $cur_fyear = $this->data['fy_year'][0]->f_year;

        // defalt previous fy_years
        $pre_cur_year = substr($cur_fyear, 0, 4) - 1;
        $last_preyear = substr($cur_fyear, 5, 7) - 1;
        // Concatenating the current and next year with appropriate formatting
        $pre_fyear = $pre_cur_year . '-' . $last_preyear;

        $this->data['cur_fy_year'] = $cur_fyear;
        $this->data['pre_fy_year'] = $pre_fyear;
        /// end default fy years   

        // Get start date from the request
        $select_date = $request->input('date_select');
        // Extract year and month from the start date
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('M', strtotime($select_date));
        $selected_month = date('m', strtotime($select_date));

        // Calculate next and previous years
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;

        // Determine the financial year based on the start month (assuming April is the start of the financial year)

        $fy_full_year = '';


        if ($selected_month >= 4) {

            $fy_full_year =  $select_year  . '-' . ($select_year + 1);
        } else {

            $fy_full_year =  ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);

        $select_pre = substr($fy_year, 0, 4);
        $select_pre_y = substr($fy_year, 5, 2);

        $select_pre_year =  ($select_pre - 1) . '-' . ($select_pre_y - 1);

        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '' || $state != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;

            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }

            if ($state != '') {
                $condition = "AND state ='$state'";
            }
            if ($state != '' && $region != '') {
                $condition = "AND state_consolidated='$state' AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($zone != '' && $region != '' && $state != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND state_consolidated='$state'";
            }

            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = date('M', strtotime($last_m));
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
            }

            // month wise cur FY
            $this->data['sales_trend'] = \DB::select("SELECT region,state_consolidated as state,f_year,month,(SUM(app_man_power) / 100) as sale
        
            FROM
            sd_primarydataupload_t
        WHERE
        f_year='$fy_year' AND name_type='PERSONS' AND data_type = 'Manpower' $notin 
        
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%M-%d') $condition $wh

        GROUP BY 
            region,state_consolidated, month,f_year ORDER BY STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
            
        } else {

            $this->data['sales_trend'] = \DB::select("SELECT region,state_consolidated as state,f_year,month,(SUM(app_man_power) / 100) as sale
        
            FROM

            sd_primarydataupload_t
        WHERE
        f_year='$cur_fyear' AND name_type='PERSONS' AND data_type = 'Manpower'
        
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$current_year-$current_month-01', '%Y-%b-%d')  $notin  $wh

        GROUP BY 
           region,state_consolidated, month,f_year ORDER BY STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        }

        return view('primarycostratio.statemanpower', $this->data);
    }
}
