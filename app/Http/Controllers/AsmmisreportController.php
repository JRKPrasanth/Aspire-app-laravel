<?php

namespace App\Http\Controllers;
use App\Misviewers;
use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;

class AsmmisreportController extends Controller
{

		   public function __construct(){
        
		$this->pageModule="misreportindex";	
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
     ->where('date', $date)
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

			
	 return view('asmmisreport.index', $this->data);
	
	}

    // person wise Report
    public function Personwise(Request $request)
    {
       
        $wh = '';
        // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        // for prakash access all mis purpose
        if ($depart == "14" && $emp_id != '121') {

            $emp_hq = '';
            // emp zone 
            $em_hq = \DB::select("SELECT distinct hq_name from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

            if (!empty($em_hq)) {
                $hq_names = array_column($em_hq, 'hq_name');
                $emp_hq = "'" . implode("','", $hq_names) . "'";
            }

            if (!empty($emp_hq)) {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ($emp_hq)";
            } else {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ('')";
            }

            // raja ram only show andra state purpose

            if ($emp_id == '94') {

                $wh = " AND sd_secondarydataupload_t.state IN ('ANDHRA PRADESH')";
            }
        }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $last_data = \DB::select("SELECT  month_y FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $l_dbmon = \DB::select("SELECT  month FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['till_mon'] = $last_data[0]->month_y;
        $this->data['regions'] = \DB::select("SELECT DISTINCT hq_name from  `sd_secondarydataupload_t` where 1=1 $wh AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)')");
        $region = $request->input('region');
        $this->data['managers'] = \DB::select("SELECT DISTINCT current_reporting_manager AS name from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $manager = $request->input('manager');
        $this->data['persons'] = \DB::select("SELECT DISTINCT field_force_name from  `sd_secondarydataupload_t` where 1=1 $wh AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)')");
        $person = $request->input('person');
        $this->data['areas'] = \DB::select("SELECT DISTINCT area from `sd_secondarydataupload_t` where area NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $area = $request->input('area');
        $condition = '';

        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $cur_fyear = $this->data['fy_year'][0]->f_year;

        // defalt previous fy_years
        $pre_cur_year = substr($cur_fyear, 0, 4) - 1;
        $last_preyear = substr($cur_fyear, 5, 7) - 1;
        // Concatenating the current and next year with appropriate formatting
        $pre_fyear = $pre_cur_year . '-' . $last_preyear;

        $this->data['cur_fy_year'] = $cur_fyear;
        $this->data['pre_fy_year'] = $pre_fyear;
        /// end default fy years   

        // get target name dynamic
        $cur_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$cur_fyear'");
        $cur_target = $cur_tgt[0]->target_name;

        // Get start date from the request
        $select_date = $request->input('date_select');
        // Extract year and month from the start date
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('m', strtotime($select_date));
        $select_mon = date('', strtotime($select_date));
        // Calculate next and previous years
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;

        // if default 
        if ($last_mon < 12) {

            $d_month = $last_mon + 1;
            $d_year = $last_y;
        } else {
            $d_month = 1;
            $d_year = $last_y + 1;
        }

        // Display the next month in the format "M'y"
        if ($d_month == 1) {
            $till = 'Jan' . "'" . substr($d_year, 2);
        } else {
            $till = date('M', strtotime($d_year . '-' . $d_month . '-01')) . "'" . substr($d_year, 2);
        }

        $this->data['till'] = $till;
        $till_date = addslashes($till);
        //dd($till_date);

        // Determine the financial year based on the start month (assuming April is the start of the financial year)
        $fy_start_month = 4;

        if ($select_month >= $fy_start_month) {

            $fy_full_year = $select_year . '-' . ($select_year + 1);
        }
        if ($select_month < $fy_start_month) {

            $fy_full_year = ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);

        $select_pre = substr($fy_year, 0, 4);
        $select_pre_y = substr($fy_year, 5, 2);

        $select_pre_year = ($select_pre - 1) . '-' . ($select_pre_y - 1);
        // if user select date search 
        if ($select_date != '' || $region != '' || $person != '' || $manager != '' || $area != '') {

            if ($select_date != '') {
                $sec_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$fy_year'");
                $select_target = $sec_tgt[0]->target_name;
            }

            // for select date 
            if ($select_month < 12) {

                $month = $select_month + 1;
                $year = $select_year;
            } else {
                $month = 1;
                $year = $select_year + 1;
            }

            // Display the next month in the format "M'y"
            if ($month == 1) {
                $till = 'Jan' . "'" . substr($year, 2);
            } else {
                $till = date('M', strtotime($year . '-' . $month . '-01')) . "'" . substr($year, 2);
            }
            $till_date1 = addslashes($till);

            $this->data['till'] = $till;
            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;

            $this->data['till_mon'] = date('M', strtotime($select_year . '-' . $select_month . '-01')) . "'" . substr($select_year, 2);
            //dd($select_year);

            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_mon;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
                $this->data['select_year'] = $last_y;
                $pre_year = $last_y - 1;
                $this->data['till_mon'] = $last_data[0]->month_y;
                $this->data['till'] = stripslashes($till_date);
                $select_target = $cur_target;
                $till_date1 = $till_date;
            }

            if ($region != '') {
                $condition = "AND hq_name ='$region'";
                //  $wh ='';
            }

            if ($person != '') {
                $condition = "AND (current_reporting_manager ='$person' OR field_force_name = '$person' )";
                //  $wh ='';
            }
            if ($manager != '') {
                $condition = "AND current_reporting_manager ='$manager'";
            }
            if ($area != '') {
              $condition = "AND area='$area'";
            }
            // dd($condition);
            // head q wise   
            // stockist sale

            $this->data['all_ind_sal'] = \DB::select("SELECT
        current_reporting_manager as name,hq_name,f_year,field_force_name as person_name,
          ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' AND (STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')) THEN purchase_stock_value ELSE 0 END), 0) as sale,
          ROUND(SUM(CASE WHEN data_type = '$select_target' AND (STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')) THEN purchase_stock_value ELSE 0 END), 0) as target,
          ROUND(SUM(CASE WHEN data_type = '$select_target' AND month_y = '$till_date1' THEN purchase_stock_value ELSE 0 END), 0) as mon_target
        FROM
          sd_secondarydataupload_t
        WHERE
          f_year='$fy_year' $condition $wh $notin
        
        GROUP BY
        current_reporting_manager,hq_name,field_force_name,f_year HAVING (sale !='0' OR target !='0' OR mon_target !='0')");

            // stockist sale
            $this->data['month_wise_stock'] = \DB::select("SELECT
      f_year,c_year,
      month,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
      ROUND(SUM(CASE WHEN data_type = '$select_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
      sd_secondarydataupload_t
    WHERE
      f_year='$fy_year' $condition $wh $notin
      AND (
          STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
    
      )
    GROUP BY
      f_year, month  
       UNION ALL
      SELECT
      f_year,c_year,
      month,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
      ROUND(SUM(CASE WHEN data_type = '$select_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
      sd_secondarydataupload_t
    WHERE
      f_year='$select_pre_year' $condition $wh $notin
      AND (
          STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
    
      )
    GROUP BY
      f_year, month ORDER BY
c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");

            // distributor sale

            $this->data['month_wise_dist'] = \DB::select("SELECT
      f_year,c_year,
      month,
      ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sale,
      ROUND(SUM(CASE WHEN data_type = '$select_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
      sd_secondarydataupload_t
    WHERE
      f_year='$fy_year' $condition $wh $notin
      AND (
          STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
    
      )
    GROUP BY
      f_year, month
       UNION ALL
      SELECT
      f_year,c_year,
      month,
      ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sale,
      ROUND(SUM(CASE WHEN data_type = '$select_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
      sd_secondarydataupload_t
    WHERE
      f_year='$select_pre_year' $condition $wh $notin
      AND (
          STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
    
      )
    GROUP BY
      f_year, month ORDER BY
c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");

        } else {

            //default values
            // stockist sale

            $this->data['all_ind_sal'] = \DB::select("SELECT
    current_reporting_manager as name,hq_name,f_year,field_force_name as person_name,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' AND (STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%M-%d')) THEN purchase_stock_value ELSE 0 END), 0) as sale,
      ROUND(SUM(CASE WHEN data_type = '$cur_target' AND (STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%M-%d')) THEN purchase_stock_value ELSE 0 END), 0) as target,
      ROUND(SUM(CASE WHEN data_type = '$cur_target' AND month_y = '$till_date' THEN purchase_stock_value ELSE 0 END), 0) as mon_target
    FROM
      sd_secondarydataupload_t
    WHERE
      f_year='$cur_fyear' $wh $notin
    
    GROUP BY
    current_reporting_manager,hq_name,field_force_name,f_year HAVING (sale !='0' OR target !='0' OR mon_target !='0')");


            // stockist sale
            $this->data['month_wise_stock'] = \DB::select("SELECT
      f_year,
      month,c_year,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
      ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
      sd_secondarydataupload_t
    WHERE
      f_year='$cur_fyear' $wh $notin
    
    GROUP BY
      f_year, month HAVING (sale !='0' OR target !='0')
       UNION ALL
      SELECT
      f_year,
      month,c_year,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
      ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
      sd_secondarydataupload_t
    WHERE
      f_year='$pre_fyear' $wh $notin
    GROUP BY
      f_year, month HAVING (sale !='0' OR target !='0') ORDER BY
c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");

            // distributor sale
            $this->data['month_wise_dist'] = \DB::select("SELECT
      f_year,c_year,
      month,
      ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sale,
      ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
      sd_secondarydataupload_t
    WHERE
      f_year='$cur_fyear' $wh $notin
    
    GROUP BY
      f_year, month 
       UNION ALL
      SELECT
      f_year,c_year,
      month,
      ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sale,
      ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
      sd_secondarydataupload_t
    WHERE
      f_year='$pre_fyear' $wh $notin
    GROUP BY
      f_year, month ORDER BY
c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");
        }

        return view('asmmisreport.personwise', $this->data);
    }

    // Heade Quaters Wise Report
    public function HeadquatWise(Request $request)
    {
        $wh = '';
        // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        if ($depart == "14" && $emp_id != '121') {

            $emp_hq = '';
            // emp zone 
            $em_hq = \DB::select("SELECT distinct hq_name from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

            if (!empty($em_hq)) {
                $hq_names = array_column($em_hq, 'hq_name');
                $emp_hq = "'" . implode("','", $hq_names) . "'";
            }

            if (!empty($emp_hq)) {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ($emp_hq)";
            } else {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ('')";
            }
            // raja ram only show andra state purpose

            if ($emp_id == '94') {

                $wh = " AND sd_secondarydataupload_t.state IN ('ANDHRA PRADESH')";
            }
        }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $last_data = \DB::select("SELECT  month_y FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $l_dbmon = \DB::select("SELECT  month FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['managers'] = \DB::select("SELECT DISTINCT current_reporting_manager AS name from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $manager = $request->input('manager');
        $this->data['regions'] = \DB::select("SELECT DISTINCT hq_name from  `sd_secondarydataupload_t` where 1=1 $wh AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)')");
        $region = $request->input('region');
        $this->data['areas'] = \DB::select("SELECT DISTINCT area from `sd_secondarydataupload_t` where area NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $area = $request->input('area');
        $condition = '';

        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $cur_fyear = $this->data['fy_year'][0]->f_year;

        // defalt previous fy_years
        $pre_cur_year = substr($cur_fyear, 0, 4) - 1;
        $last_preyear = substr($cur_fyear, 5, 7) - 1;
        // Concatenating the current and next year with appropriate formatting
        $pre_fyear = $pre_cur_year . '-' . $last_preyear;

        $this->data['cur_fy_year'] = $cur_fyear;
        $this->data['pre_fy_year'] = $pre_fyear;
        /// end default fy years   
        // get target name dynamic
        $cur_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$cur_fyear'");
        $cur_target = $cur_tgt[0]->target_name;
        // Get start date from the request
        $select_date = $request->input('date_select');
        // Extract year and month from the start date
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('m', strtotime($select_date));

        // Calculate next and previous years
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;

        // Determine the financial year based on the start month (assuming April is the start of the financial year)
        $fy_start_month = 4;

        if ($select_month >= $fy_start_month) {

            $fy_full_year = $select_year . '-' . ($select_year + 1);
        }
        if ($select_month < $fy_start_month) {

            $fy_full_year = ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);

        $select_pre = substr($fy_year, 0, 4);
        $select_pre_y = substr($fy_year, 5, 2);

        $select_pre_year = ($select_pre - 1) . '-' . ($select_pre_y - 1);

        // if user select date search 
        if ($select_date != '' || $region != '' || $manager != '' || $area != '') {

            if ($select_date != '') {
                $sec_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$fy_year'");
                $select_target = $sec_tgt[0]->target_name;
            }

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;
            //dd($select_year);

            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_mon;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
                $this->data['select_year'] = $last_y;
                $pre_year = $last_y - 1;
                $select_target = $cur_target;
            }

            if ($region != '') {
                $condition = "AND hq_name ='$region'";
            }
            if ($manager != '') {
                $condition = "AND current_reporting_manager ='$manager'";
            }
            if ($area != '') {
              $condition = "AND area='$area'";
            }
            // head q wise   
            // distributor sale
            $this->data['all_ind_sal'] = \DB::select("SELECT
            hq_name,f_year,
            ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
            ROUND(SUM(CASE WHEN data_type = '$select_target' THEN purchase_stock_value ELSE 0 END), 0) as target
            FROM
               sd_secondarydataupload_t
             WHERE
           f_year='$fy_year' $condition $wh $notin AND `hq_name` NOT LIKE '%dist'
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
    
       )
    GROUP BY
    hq_name,f_year HAVING (sale !='0' OR target !='0')
        UNION ALL
       SELECT
       hq_name,f_year, 
       ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
       ROUND(SUM(CASE WHEN data_type = '$select_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
       sd_secondarydataupload_t
    WHERE
       f_year='$select_pre_year' $condition $wh $notin AND `hq_name` NOT LIKE '%dist'
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
    
       )
    GROUP BY
    hq_name,f_year HAVING (sale !='0' OR target !='0') ");
        } else {
            //      default values

            // distributor sale
            $this->data['all_ind_sal'] = \DB::select("SELECT
    hq_name,f_year,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
          ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
       sd_secondarydataupload_t
    WHERE
       f_year='$cur_fyear' $wh $notin AND `hq_name` NOT LIKE '%dist'
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
    
       )
    GROUP BY
    hq_name,f_year HAVING (sale !='0' OR target !='0')
        UNION ALL
       SELECT
       hq_name,f_year, 
       ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
          ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
       sd_secondarydataupload_t
    WHERE
       f_year='$pre_fyear' $wh $notin AND `hq_name` NOT LIKE '%dist'
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')
    
       )
    GROUP BY
    hq_name,f_year HAVING (sale !='0' OR target !='0')");
        }

        return view('asmmisreport.hqwise', $this->data);
    }


    // sales and purchase trend
    public function Salestrend(Request $request)
    {
        $wh = '';
        // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        if ($depart == "14" && $emp_id != '121') {

            $emp_hq = '';
            // emp zone 
            $em_hq = \DB::select("SELECT distinct hq_name from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

            if (!empty($em_hq)) {
                $hq_names = array_column($em_hq, 'hq_name');
                $emp_hq = "'" . implode("','", $hq_names) . "'";
            }

            if (!empty($emp_hq)) {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ($emp_hq)";
            } else {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ('')";
            }
            // raja ram only show andra state purpose

            if ($emp_id == '94') {

                $wh = " AND sd_secondarydataupload_t.state IN ('ANDHRA PRADESH')";
            }
        }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $l_dbmon = \DB::select("SELECT  month FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        //dd( $this->data['last_update']);
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_secondarydataupload_t` where division!='-'");
        $division = $request->input('division');

        $this->data['regions'] = \DB::select("SELECT DISTINCT hq_name from  `sd_secondarydataupload_t` where 1=1 $wh AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)')");
        $region = $request->input('region');
        $this->data['managers'] = \DB::select("SELECT DISTINCT current_reporting_manager AS name from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $manager = $request->input('manager');
        $l_dbmon = \DB::select("SELECT  month_y FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month_y;
        $month_y = addslashes($l_dbmon[0]->month_y);
        $condition = '';

        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $cur_fyear = $this->data['fy_year'][0]->f_year;

        // defalt previous fy_years
        $pre_cur_year = substr($cur_fyear, 0, 4) - 1;
        $last_preyear = substr($cur_fyear, 5, 7) - 1;
        // Concatenating the current and next year with appropriate formatting
        $pre_fyear = $pre_cur_year . '-' . $last_preyear;

        $this->data['cur_fy_year'] = $cur_fyear;
        $this->data['pre_fy_year'] = $pre_fyear;
        /// end default fy years   
        // get target name dynamic
        $cur_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$cur_fyear'");
        $cur_target = $cur_tgt[0]->target_name;

        $select_date = $request->input('date_select');
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('m', strtotime($select_date));
        $select_Mon = date('M', strtotime($select_date));
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;

        // Determine the financial year based on the start month (assuming April is the start of the financial year)
        $fy_start_month = 4;

        if ($select_month >= $fy_start_month) {

            $fy_full_year = $select_year . '-' . ($select_year + 1);
        }
        if ($select_month < $fy_start_month) {

            $fy_full_year = ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);

        $select_pre = substr($fy_year, 0, 4);
        $select_pre_y = substr($fy_year, 5, 2);

        $select_pre_year = ($select_pre - 1) . '-' . ($select_pre_y - 1);

        // if user select date search 
        if ($select_date != '' || $region != '' || $manager != '') {
            $this->data['mon_yr'] = $select_Mon . '\'' . substr($select_year, -2);

            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month_y;
            }

            if ($region != '') {
                $condition = "AND hq_name ='$region'";
            }
            if ($manager != '') {
                $condition = "AND current_reporting_manager ='$manager'";
            }
            // sales

            $this->data['all_ind_sal'] = \DB::select("SELECT 

        hq_name AS name,
        f_year,
        month,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) AS sale
    FROM
        sd_secondarydataupload_t
    WHERE
        hq_name IN (
            SELECT hq_name
            FROM sd_secondarydataupload_t
            WHERE f_year = '$fy_year'
        )
        AND (f_year = '$fy_year' OR f_year = '$select_pre_year') $condition $wh $notin
    GROUP BY 
        f_year, hq_name, month

    ORDER BY
    FIELD(month, 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec', 'jan', 'feb', 'mar')");


            // purchase

            $this->data['sales_trend'] = \DB::select("SELECT 
    c_year,
    hq_name AS name,
    f_year,
    month,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) AS sale
FROM
    sd_secondarydataupload_t
WHERE
    hq_name IN (
        SELECT hq_name
        FROM sd_secondarydataupload_t
        WHERE f_year = '$fy_year'
    )
    AND  f_year = '$select_pre_year'  $condition $wh $notin

GROUP BY 
    f_year, hq_name, month

    UNION ALL
SELECT 
    c_year,
    hq_name AS name,
    f_year,
    month,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) AS sale
FROM
    sd_secondarydataupload_t
WHERE

     f_year = '$fy_year' $condition $wh $notin

GROUP BY 
    f_year, hq_name, month


ORDER BY
FIELD(month, 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec', 'jan', 'feb', 'mar')");


            // distributor sales

            $this->data['sales_trend_dis'] = \DB::select("SELECT 
    c_year,
    stockist_dist_name AS name,
    f_year,
    month,
    ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) AS sale
FROM
    sd_secondarydataupload_t
WHERE
 (f_year = '$select_pre_year' OR f_year = '$fy_year') AND type_d_s = 'DISTRIBUTOR' AND stockist_dist_name !='Draft TARGET' $condition $wh $notin

GROUP BY 
    f_year, stockist_dist_name, month

ORDER BY
FIELD(month, 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec', 'jan', 'feb', 'mar')");

            // stocksit sales

 $this->data['stock_trend_stk'] = \DB::select("SELECT 

    stockist_dist_name AS name,
    hq_name AS hq_name,
    f_year,
    month,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) AS sale
    FROM
        sd_secondarydataupload_t
    WHERE
    (f_year = '$select_pre_year' OR f_year = '$fy_year') $condition $wh $notin
    GROUP BY 
        f_year,hq_name, stockist_dist_name, month
        HAVING 
            sale != 0
    ORDER BY
    c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");
    
        } else {

            // sales

            $this->data['all_ind_sal'] = \DB::select("SELECT 

        hq_name AS name,
        f_year,
        month,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) AS sale
    FROM
        sd_secondarydataupload_t
    WHERE
        hq_name IN (
            SELECT hq_name
            FROM sd_secondarydataupload_t
            WHERE f_year = '$cur_fyear'
        )
        AND (f_year = '$cur_fyear' OR f_year = '$pre_fyear') $notin $wh
    GROUP BY 
        f_year, hq_name, month
    HAVING 
        sale != 0
    ORDER BY
    c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");

            // purchase

            $this->data['sales_trend'] = \DB::select("SELECT 

        hq_name AS name,
        f_year,
        month,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) AS sale
    FROM
        sd_secondarydataupload_t
    WHERE
        hq_name IN (
            SELECT hq_name
            FROM sd_secondarydataupload_t
            WHERE f_year = '$cur_fyear'
        )
        AND (f_year = '$cur_fyear' OR f_year = '$pre_fyear') $notin $wh
    GROUP BY 
        f_year, hq_name, month
    HAVING 
        sale != 0
    ORDER BY
    c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");


            // distributor sales

            $this->data['sales_trend_dis'] = \DB::select("SELECT 

        stockist_dist_name AS name,
        f_year,
        month,
       ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) AS sale
    FROM
        sd_secondarydataupload_t
    WHERE
    (f_year = '$cur_fyear' OR f_year = '$pre_fyear') $notin $wh
    GROUP BY 
        f_year, stockist_dist_name, month
        HAVING 
            sale != 0
    ORDER BY
    c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");

            // stockist sales

            $this->data['stock_trend_stk'] = \DB::select("SELECT 

    stockist_dist_name AS name,
    hq_name AS hq_name,
    f_year,
    month,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) AS sale
    FROM
        sd_secondarydataupload_t
    WHERE
    (f_year = '$cur_fyear' OR f_year = '$pre_fyear') $notin $wh
    GROUP BY 
        f_year,hq_name, stockist_dist_name, month
        HAVING 
            sale != 0
    ORDER BY
    c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");
        }

        return view('asmmisreport.saleandpurtrend', $this->data);
    }

    public function ClosingBal(Request $request)
    {

        $wh = '';
        // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        if ($depart == "14" && $emp_id != '121') {

            $emp_hq = '';
            // emp zone 
            $em_hq = \DB::select("SELECT distinct hq_name from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

            if (!empty($em_hq)) {
                $hq_names = array_column($em_hq, 'hq_name');
                $emp_hq = "'" . implode("','", $hq_names) . "'";
            }

            if (!empty($emp_hq)) {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ($emp_hq)";
            } else {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ('')";
            }
            // raja ram only show andra state purpose

            if ($emp_id == '94') {

                $wh = " AND sd_secondarydataupload_t.state IN ('ANDHRA PRADESH')";
            }
        }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $l_dbmon = \DB::select("SELECT  month FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        //dd( $this->data['last_update']);
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_secondarydataupload_t` where division!='-'");
        $division = $request->input('division');
        $this->data['areas'] = \DB::select("SELECT DISTINCT area from `sd_secondarydataupload_t` where area NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $area = $request->input('area');
        $this->data['regions'] = \DB::select("SELECT DISTINCT hq_name from  `sd_secondarydataupload_t` where 1=1 $wh AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)')");
        $region = $request->input('region');
        $this->data['managers'] = \DB::select("SELECT DISTINCT current_reporting_manager AS name from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $manager = $request->input('manager');
        $l_dbmon = \DB::select("SELECT  month_y FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month_y;
        $month_y = addslashes($l_dbmon[0]->month_y);

        $condition = '';

        $select_date = $request->input('date_select');
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('M', strtotime($select_date));
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;

        $month_yr = addslashes($select_month . '\'' . substr($select_year, -2));
        // dd($month_yr);
        // if user select date search 
        if ($select_date != '' || $region != '' || $manager != ''| $area != '') {

            $this->data['mon_yr'] = $select_month . '\'' . substr($select_year, -2);

            if ($select_date == '') {
                $select_year = $last_y;
                $select_month = $last_mon;
                $this->data['mon_yr'] = $l_dbmon[0]->month_y;
                $this->data['select_year'] = $last_y;
                $pre_year = $last_y - 1;
                $month_yr = $month_y;
            }

            if ($region != '') {
                $condition = "AND hq_name ='$region'";
            }
            if ($manager != '') {
                $condition = "AND current_reporting_manager ='$manager'";
            }
            
            if ($area != '') {
              $condition = "AND area='$area'";
            }
            // dist col bal
            $this->data['all_ind_sal_per'] = \DB::select("SELECT 
            hq_name,stockist_dist_name as name,f_year,current_reporting_manager as mgr,
            ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN closing_stock_value ELSE 0 END), 0) as sale
        FROM
            sd_secondarydataupload_t
        WHERE
         month_y ='$month_yr' $condition $wh $notin
        GROUP BY 
        hq_name,stockist_dist_name,current_reporting_manager,f_year HAVING sale !='0' ");

            // person col bal
            $this->data['all_ind_sal'] = \DB::select("SELECT 
         current_reporting_manager as mangr,field_force_name as name,f_year,hq_name,
          ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN closing_stock_value ELSE 0 END), 0) as sale
        FROM
            sd_secondarydataupload_t
        WHERE
        month_y ='$month_yr' $condition $wh $notin
        GROUP BY 
        current_reporting_manager,field_force_name,hq_name,f_year HAVING sale !='0' ");
        } else {

            // dist col bal
            $this->data['all_ind_sal_per'] = \DB::select("SELECT 
            hq_name,stockist_dist_name as name,f_year,current_reporting_manager as mgr,
            ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN closing_stock_value ELSE 0 END), 0) as sale
        FROM
            sd_secondarydataupload_t
        WHERE
         month_y ='$month_y' $wh $notin
        GROUP BY 
        hq_name,stockist_dist_name,current_reporting_manager,f_year HAVING sale !='0' ");

            // person col bal
            $this->data['all_ind_sal'] = \DB::select("SELECT 
         current_reporting_manager as mangr,field_force_name as name,f_year,hq_name,
          ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN closing_stock_value ELSE 0 END), 0) as sale
        FROM
            sd_secondarydataupload_t
        WHERE
         month_y ='$month_y' $wh $notin
        GROUP BY 
        current_reporting_manager,field_force_name,hq_name,f_year HAVING sale !='0' ");
        }
        return view('asmmisreport.clobal', $this->data);
    }

    // ProductWise Report
    public function HqProduct(Request $request)
    {


        $wh = '';
        // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        if ($depart == "14" && $emp_id != '121') {

            $emp_hq = '';
            // emp zone 
            $em_hq = \DB::select("SELECT distinct hq_name from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

            if (!empty($em_hq)) {
                $hq_names = array_column($em_hq, 'hq_name');
                $emp_hq = "'" . implode("','", $hq_names) . "'";
            }

            if (!empty($emp_hq)) {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ($emp_hq)";
            } else {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ('')";
            }
            // raja ram only show andra state purpose

            if ($emp_id == '94') {

                $wh = " AND sd_secondarydataupload_t.state IN ('ANDHRA PRADESH')";
            }
        }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $last_data = \DB::select("SELECT  month_y FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $l_dbmon = \DB::select("SELECT  month FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");

        $this->data['regions'] = \DB::select("SELECT DISTINCT hq_name from  `sd_secondarydataupload_t` where 1=1 $wh AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)')");
        $region = $request->input('region');
        $this->data['managers'] = \DB::select("SELECT DISTINCT current_reporting_manager AS name from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $manager = $request->input('manager');
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_secondarydataupload_t` where division!='-'");
        $division = $request->input('division');
        $this->data['products'] = \DB::select("SELECT DISTINCT product_name  from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $product = $request->input('product');
        $this->data['areas'] = \DB::select("SELECT DISTINCT area from `sd_secondarydataupload_t` where area NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $area = $request->input('area');
        $condition = '';
        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $cur_fyear = $this->data['fy_year'][0]->f_year;

        // defalt previous fy_years
        $pre_cur_year = substr($cur_fyear, 0, 4) - 1;
        $last_preyear = substr($cur_fyear, 5, 7) - 1;
        // Concatenating the current and next year with appropriate formatting
        $pre_fyear = $pre_cur_year . '-' . $last_preyear;

        $this->data['cur_fy_year'] = $cur_fyear;
        $this->data['pre_fy_year'] = $pre_fyear;
        /// end default fy years   

        // get target name dynamic
        $cur_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$cur_fyear'");
        $cur_target = $cur_tgt[0]->target_name;

        // next month target show purpose

        $l_dbmon = \DB::select("SELECT  month_y FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month_y;
        $last_month_db = addslashes($l_dbmon[0]->month_y);

        // show next month target purpose
        list($month, $year) = explode("'", $last_month_db);

        // Create a DateTime object using the parsed month and year
        $month_year_str = sprintf("01-%s-20%s", $month, $year);
        $month_yeeer = str_replace("\\", "", $month_year_str);

        $nxt_year = date('Y', strtotime($month_yeeer));
        $nxt_month = date('m', strtotime($month_yeeer));
        $add_mon = $nxt_month + 1;
        $tar_next  = "$nxt_year-$add_mon-01";
        $tar_month = date('M', strtotime($tar_next));

        // default next month target purpose
        $default_yer = substr($nxt_year, 2);
        $def_target_mon = ($tar_month) . '\'' . $default_yer;
        $de_next_month_target = addslashes(($tar_month) . '\'' . $default_yer);

        $this->data['nxt_target'] =   $def_target_mon;


        // Get start date from the request
        $select_date = $request->input('date_select');
        // Extract year and month from the start date
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('m', strtotime($select_date));

        // Calculate next and previous years
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;

        // Determine the financial year based on the start month (assuming April is the start of the financial year)
        $fy_start_month = 4;

        if ($select_month >= $fy_start_month) {

            $fy_full_year = $select_year . '-' . ($select_year + 1);
        }
        if ($select_month < $fy_start_month) {

            $fy_full_year = ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);

        $select_pre = substr($fy_year, 0, 4);
        $select_pre_y = substr($fy_year, 5, 2);

        $select_pre_year = ($select_pre - 1) . '-' . ($select_pre_y - 1);

        // if user select date search 
        if ($select_date != '' || $region != '' || $product != '' || $manager != '' || $area !='') {

            if ($select_date != '') {
                $sec_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$fy_year'");
                $select_target = $sec_tgt[0]->target_name;
            }

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;
            //dd($select_year);

            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_mon;
                $this->data['mon_yr'] = $l_dbmon[0]->month_y;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
                $this->data['select_year'] = $last_y;
                $pre_year = $last_y - 1;
                $select_target = $cur_target;
            }

            if ($region != '') {
                $condition = "AND hq_name='$region'";
            }

            if ($product != '') {
                $condition = "AND product_form_change='" . addslashes($product) . "'";
            }
            if ($manager != '') {
                $condition = "AND current_reporting_manager ='$manager'";
            }
            if ($area != '') {
              $condition = "AND area='$area'";
            }

            $this->data['all_ind_sal'] = \DB::select("SELECT
       hq_name,product_form_change as name,f_year,
       ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sale,
       ROUND(SUM(CASE WHEN data_type = '$select_target' THEN purchase_stock ELSE 0 END), 0) as target
    FROM
       sd_secondarydataupload_t
    WHERE
       f_year='$fy_year' $condition $wh $notin and product_name NOT IN ('payment outstanding')
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
    
       )
        GROUP BY
        hq_name, product_form_change,f_year HAVING (sale !='0' OR target !='0')
            UNION ALL
           SELECT
           hq_name,product_form_change as name,f_year,
           ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sale,
              ROUND(SUM(CASE WHEN data_type = '$select_target' THEN purchase_stock ELSE 0 END), 0) as target
        FROM
           sd_secondarydataupload_t
        WHERE
           f_year='$select_pre_year' $condition $wh $notin and product_name NOT IN ('payment outstanding')
           AND (
               STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
        
           )
            GROUP BY
             hq_name, product_form_change,f_year HAVING (sale !='0' OR target !='0') ");
            // dd( $this->data['all_ind_sal']);
            
            $this->data['all_ind_sal_pro'] = \DB::select("SELECT
       product_form_change as name,f_year,
       ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sale,
       ROUND(SUM(CASE WHEN data_type = '$select_target' THEN purchase_stock ELSE 0 END), 0) as target
    FROM
       sd_secondarydataupload_t
    WHERE
       f_year='$fy_year' $condition $wh $notin and product_name NOT IN ('payment outstanding')
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
    
       )
        GROUP BY
         product_form_change,f_year HAVING (sale !='0' OR target !='0')
            UNION ALL
           SELECT
           product_form_change as name,f_year,
           ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sale,
              ROUND(SUM(CASE WHEN data_type = '$select_target' THEN purchase_stock ELSE 0 END), 0) as target
        FROM
           sd_secondarydataupload_t
        WHERE
           f_year='$select_pre_year' $condition $wh $notin and product_name NOT IN ('payment outstanding')
           AND (
               STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
        
           )
            GROUP BY
             product_form_change,f_year HAVING (sale !='0' OR target !='0') ");
        } else {

            //      default values

            $this->data['all_ind_sal'] = \DB::select("SELECT

      hq_name,product_form_change as name,f_year,
          ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sale,
          ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock ELSE 0 END), 0) as target
          
    FROM
       sd_secondarydataupload_t
    WHERE
       f_year='$cur_fyear' $wh $notin and product_name NOT IN ('payment outstanding')
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d') 
    
       )
    GROUP BY
     hq_name, product_form_change,f_year HAVING (sale !='0' OR target !='0')
    
      UNION ALL
       SELECT
       hq_name,product_form_change as name,f_year,
       ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sale,
       ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock ELSE 0 END), 0) as target

    FROM
       sd_secondarydataupload_t
    WHERE
       f_year='$pre_fyear' $wh $notin and product_name NOT IN ('payment outstanding')
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')
    
       )
    GROUP BY
     hq_name, product_form_change,f_year HAVING (sale !='0' OR target !='0') ");
     
                $this->data['all_ind_sal_pro'] = \DB::select("SELECT

      product_form_change as name,f_year,
          ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sale,
          ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock ELSE 0 END), 0) as target
          
    FROM
       sd_secondarydataupload_t
    WHERE
       f_year='$cur_fyear' $wh $notin and product_name NOT IN ('payment outstanding')
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d') 
    
       )
    GROUP BY
     product_form_change,f_year HAVING (sale !='0' OR target !='0')
    
      UNION ALL
       SELECT
       product_form_change as name,f_year,
       ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sale,
       ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock ELSE 0 END), 0) as target

    FROM
       sd_secondarydataupload_t
    WHERE
       f_year='$pre_fyear' $wh $notin and product_name NOT IN ('payment outstanding')
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')
    
       )
    GROUP BY
     product_form_change,f_year HAVING (sale !='0' OR target !='0') ");
        }

        return view('asmmisreport.hqpro', $this->data);
    }

    // hq cloinsg stock value for product
    public function Hqclobal(Request $request)
    {
        $wh = '';
        // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        if ($depart == "14" && $emp_id != '121') {

            $emp_hq = '';
            // emp zone 
            $em_hq = \DB::select("SELECT distinct hq_name from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

            if (!empty($em_hq)) {
                $hq_names = array_column($em_hq, 'hq_name');
                $emp_hq = "'" . implode("','", $hq_names) . "'";
            }

            if (!empty($emp_hq)) {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ($emp_hq)";
            } else {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ('')";
            }
            // raja ram only show andra state purpose

            if ($emp_id == '94') {

                $wh = " AND sd_secondarydataupload_t.state IN ('ANDHRA PRADESH')";
            }
        }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";
        $last_data = \DB::select("SELECT  month_y FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $l_dbmon = \DB::select("SELECT  month FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['regions'] = \DB::select("SELECT DISTINCT hq_name from  `sd_secondarydataupload_t` where 1=1 $wh AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)')");
        $region = $request->input('region');
        $this->data['managers'] = \DB::select("SELECT DISTINCT current_reporting_manager AS name from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $manager = $request->input('manager');
        $this->data['products'] = \DB::select("SELECT DISTINCT product_name  from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $product = $request->input('product');
        $l_dbmon = \DB::select("SELECT  month_y FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month_y;

        $month_y = addslashes($l_dbmon[0]->month_y);
        $condition = '';

        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $cur_fyear = $this->data['fy_year'][0]->f_year;

        // defalt previous fy_years
        $pre_cur_year = substr($cur_fyear, 0, 4) - 1;
        $last_preyear = substr($cur_fyear, 5, 7) - 1;
        // Concatenating the current and next year with appropriate formatting
        $pre_fyear = $pre_cur_year . '-' . $last_preyear;

        $this->data['cur_fy_year'] = $cur_fyear;
        $this->data['pre_fy_year'] = $pre_fyear;
        /// end default fy years   
        // get target name dynamic
        $cur_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$cur_fyear'");
        $cur_target = $cur_tgt[0]->target_name;

        // Get start date from the request
        $select_date = $request->input('date_select');
        // Extract year and month from the start date
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('m', strtotime($select_date));
        $select_Mon = date('M', strtotime($select_date));
        // Calculate next and previous years
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;

        // Determine the financial year based on the start month (assuming April is the start of the financial year)
        $fy_start_month = 4;

        if ($select_month >= $fy_start_month) {

            $fy_full_year = $select_year . '-' . ($select_year + 1);
        }
        if ($select_month < $fy_start_month) {

            $fy_full_year = ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);

        $select_pre = substr($fy_year, 0, 4);
        $select_pre_y = substr($fy_year, 5, 2);

        $select_pre_year = ($select_pre - 1) . '-' . ($select_pre_y - 1);
        $month_yr = addslashes($select_Mon . '\'' . substr($select_year, -2));
        // if user select date search 
        if ($select_date != '' || $region != '' || $product != '' || $manager != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;
            //dd($select_year);

            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_mon;
                $this->data['mon_yr'] = $l_dbmon[0]->month_y;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
                $this->data['select_year'] = $last_y;
                $month_yr = $month_y;
                $pre_year = $last_y - 1;
            }

            if ($region != '') {
                $condition = "AND hq_name ='$region'";
            }
            if ($product != '') {
                $condition = "AND product_name='" . addslashes($product) . "'";
            }
            if ($manager != '') {
                $condition = "AND current_reporting_manager ='$manager'";
            }
            // distributor wise

            $this->data['distributor_cb'] = \DB::select("SELECT 
            hq_name,product_form_change as name,
            ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN closing_stock ELSE 0 END), 0) as sale
            FROM
                sd_secondarydataupload_t
            WHERE
           f_year='$fy_year' AND month_y ='$month_yr' AND product_name !='PAYMENT OUTSTANDING' $notin $condition $wh
          
            GROUP BY 
            hq_name,product_form_change HAVING sale !='0'");

            // STOCKIST WiSE
            $this->data['stockist_cb'] = \DB::select("SELECT 
            hq_name,product_form_change as name,
            ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN closing_stock ELSE 0 END), 0) as sale
            FROM
                sd_secondarydataupload_t
           WHERE
           f_year='$fy_year' AND month_y ='$month_yr' AND product_name !='PAYMENT OUTSTANDING' $notin $condition $wh
            GROUP BY 
            hq_name,product_form_change HAVING sale !='0'");
        } else {

            //      default values
            //      distributor wise

            $this->data['distributor_cb'] = \DB::select("SELECT 
                      hq_name,product_form_change as name,
                      ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN closing_stock ELSE 0 END), 0) as sale
                      FROM
                          sd_secondarydataupload_t
                      WHERE
                     f_year='$cur_fyear' AND month_y ='$month_y' AND product_name !='PAYMENT OUTSTANDING' $notin $wh
                      GROUP BY 
                      hq_name,product_form_change HAVING sale !='0'");

            // STOCKIST WiSE
            $this->data['stockist_cb'] = \DB::select("SELECT 
                      hq_name,product_form_change as name,
                      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN closing_stock ELSE 0 END), 0) as sale
                      FROM
                          sd_secondarydataupload_t
                     WHERE
                     f_year='$cur_fyear' AND month_y ='$month_y' AND product_name !='PAYMENT OUTSTANDING' $notin $wh
                      GROUP BY 
                      hq_name,product_form_change HAVING sale !='0'");
        }

        return view('asmmisreport.hqclobal', $this->data);
    }

    // pro sale hq 

    public function Prosalehq(Request $request)
    {
        $wh = '';
        // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        if ($depart == "14" && $emp_id != '121') {

            $emp_hq = '';
            // emp zone 
            $em_hq = \DB::select("SELECT distinct hq_name from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

            if (!empty($em_hq)) {
                $hq_names = array_column($em_hq, 'hq_name');
                $emp_hq = "'" . implode("','", $hq_names) . "'";
            }

            if (!empty($emp_hq)) {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ($emp_hq)";
            } else {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ('')";
            }
            // raja ram only show andra state purpose

            if ($emp_id == '94') {

                $wh = " AND sd_secondarydataupload_t.state IN ('ANDHRA PRADESH')";
            }
        }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $last_data = \DB::select("SELECT  month_y FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $l_dbmon = \DB::select("SELECT  month FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['pro_names'] = \DB::select("SELECT DISTINCT product_name from `sd_secondarydataupload_t` where 1=1 $wh $notin");
        $pro_name = $request->input('pro_name');
        // dd($this->data['pro_names']);
        $this->data['regions'] = \DB::select("SELECT DISTINCT hq_name from  `sd_secondarydataupload_t` where 1=1 $wh AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)')");
        $region = $request->input('region');
        $this->data['managers'] = \DB::select("SELECT DISTINCT current_reporting_manager AS name from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $manager = $request->input('manager');
        $this->data['areas'] = \DB::select("SELECT DISTINCT area from `sd_secondarydataupload_t` where area NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $area = $request->input('area');
        $condition = '';

        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $cur_fyear = $this->data['fy_year'][0]->f_year;

        // defalt previous fy_years
        $pre_cur_year = substr($cur_fyear, 0, 4) - 1;
        $last_preyear = substr($cur_fyear, 5, 7) - 1;
        // Concatenating the current and next year with appropriate formatting
        $pre_fyear = $pre_cur_year . '-' . $last_preyear;

        $this->data['cur_fy_year'] = $cur_fyear;
        $this->data['pre_fy_year'] = $pre_fyear;
        /// end default fy years   
        // get target name dynamic
        $cur_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$cur_fyear'");
        $cur_target = $cur_tgt[0]->target_name;

        // Get start date from the request
        $select_date = $request->input('date_select');
        // Extract year and month from the start date
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('m', strtotime($select_date));

        // Calculate next and previous years
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;

        // Determine the financial year based on the start month (assuming April is the start of the financial year)
        $fy_start_month = 4;

        if ($select_month >= $fy_start_month) {

            $fy_full_year = $select_year . '-' . ($select_year + 1);
        }
        if ($select_month < $fy_start_month) {

            $fy_full_year = ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);

        $select_pre = substr($fy_year, 0, 4);
        $select_pre_y = substr($fy_year, 5, 2);

        $select_pre_year = ($select_pre - 1) . '-' . ($select_pre_y - 1);

        // if user select date search 
        if ($select_date != '' || $region != '' || $pro_name != '' || $manager != '' || $area !='') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;
            //dd($select_year);

            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_mon;
                $this->data['mon_yr'] = $last_data[0]->month_y;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
                $this->data['select_year'] = $last_y;
                $pre_year = $last_y - 1;
            }

            if ($region != '') {
                $condition = "AND hq_name ='$region'";
            }
            if ($pro_name != '') {
                $condition = "AND product_name = '" . addslashes($pro_name) . "'";
            }
            if ($manager != '') {
                $condition = "AND current_reporting_manager ='$manager'";
            }
            if ($area != '') {
              $condition = "AND area='$area'";
            }
            // product sale

            $this->data['distributor_cb'] = \DB::select("SELECT 
                      hq_name,product_form_change as name,month_y,c_year,
                      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sale
                      FROM
                          sd_secondarydataupload_t
                      WHERE
                      product_form_change !='PAYMENT OUTSTANDING' $notin $condition $wh
                      AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d'), INTERVAL 5 MONTH)
                      AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
                      GROUP BY 
                      hq_name,product_form_change,month_y HAVING sale !='0' ORDER BY
                      c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");
                      
                      
                    $this->data['sale_unit'] = \DB::select("SELECT 
                      product_form_change as name,month_y,c_year,
                      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sale
                      FROM
                          sd_secondarydataupload_t
                      WHERE
                      product_form_change !='PAYMENT OUTSTANDING' $notin $condition $wh
                      AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d'), INTERVAL 5 MONTH)
                      AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
                      GROUP BY 
                      product_form_change,month_y HAVING sale !='0' ORDER BY
                      c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");
                      
                    } else {
                     //      default values

            // product sale

            $this->data['distributor_cb'] = \DB::select("SELECT 
                      hq_name,product_form_change as name,month_y,c_year,
                      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sale
                      FROM
                          sd_secondarydataupload_t 
                      WHERE
                      product_form_change !='PAYMENT OUTSTANDING' $notin $wh
                     AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
                     AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%M-%d')
                      GROUP BY 
                      hq_name,product_form_change,month_y HAVING sale !='0' ORDER BY
                      c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");
                      
                     $this->data['sale_unit'] = \DB::select("SELECT 
                      product_form_change as name,month_y,c_year,
                      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sale
                      FROM
                          sd_secondarydataupload_t 
                      WHERE
                      product_form_change !='PAYMENT OUTSTANDING' $notin $wh
                     AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
                     AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%M-%d')
                      GROUP BY 
                      product_form_change,month_y HAVING sale !='0' ORDER BY
                      c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");
        }

        return view('asmmisreport.prohqsale', $this->data);
    }

    // stock/dist wise payment out Report

    public function Paymentout(Request $request)
    {
        $wh = '';
        // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        if ($depart == "14" && $emp_id != '121') {

            $emp_hq = '';
            // emp zone 
            $em_hq = \DB::select("SELECT distinct hq_name from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

            if (!empty($em_hq)) {
                $hq_names = array_column($em_hq, 'hq_name');
                $emp_hq = "'" . implode("','", $hq_names) . "'";
            }

            if (!empty($emp_hq)) {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ($emp_hq)";
            } else {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ('')";
            }
            // raja ram only show andra state purpose

            if ($emp_id == '94') {

                $wh = " AND sd_secondarydataupload_t.state IN ('ANDHRA PRADESH')";
            }
        }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $l_dbmon = \DB::select("SELECT  month FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        //dd( $this->data['last_update']);
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_secondarydataupload_t` where division!='-'");
        $division = $request->input('division');

        $this->data['regions'] = \DB::select("SELECT DISTINCT hq_name from  `sd_secondarydataupload_t` where 1=1 $wh AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)')");
        $region = $request->input('region');
        $this->data['managers'] = \DB::select("SELECT DISTINCT current_reporting_manager AS name from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $manager = $request->input('manager');
        $l_dbmon = \DB::select("SELECT  month_y FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month_y;

        $month_y = addslashes($l_dbmon[0]->month_y);
        //dd($month_y);
        $condition = '';



        $select_date = $request->input('date_select');
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('M', strtotime($select_date));
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;
        $this->data['select_year'] = $last_y;
        $month_yr = addslashes($select_month . '\'' . substr($select_year, -2));
        // dd($month_yr);
        // if user select date search 
        if ($select_date != '' || $region != '' || $manager != '') {
            $this->data['mon_yr'] = $select_month . '\'' . substr($select_year, -2);

            if ($select_date == '') {
                $select_year = $last_y;
                $select_month = $last_mon;
                $this->data['mon_yr'] = $l_dbmon[0]->month_y;
                $this->data['select_year'] = $last_y;
                $pre_year = $last_y - 1;
                $month_yr = $month_y;
            }

            if ($region != '') {
                $condition = "AND hq_name='$region'";
            }

            if ($manager != '') {
                $condition = "AND current_reporting_manager ='$manager'";
            }

            $this->data['all_ind_sal'] = \DB::select("SELECT 
        hq_name,stockist_dist_name as name,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN opening_stock ELSE 0 END), 0) as sale
    FROM
        sd_secondarydataupload_t
    WHERE
    month_y ='$month_yr' $condition $wh $notin AND product_name = 'payment outstanding'
    GROUP BY 
    hq_name,stockist_dist_name  HAVING sale !='0'");
        } else {

            $this->data['all_ind_sal'] = \DB::select("SELECT 
        hq_name,stockist_dist_name as name, 
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN opening_stock ELSE 0 END), 0) as sale
    FROM
        sd_secondarydataupload_t
    WHERE
     month_y ='$month_y' $wh $notin AND product_name = 'payment outstanding'
    GROUP BY 
    hq_name,stockist_dist_name  HAVING sale !='0'");
        }

        return view('asmmisreport.payout', $this->data);
    }


    // overall product search report
    public function Productsearch(Request $request)
    {

        $wh = '';
        // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        if ($depart == "14" && $emp_id != '121') {

            $emp_hq = '';
            // emp zone 
            $em_hq = \DB::select("SELECT distinct hq_name from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

            if (!empty($em_hq)) {
                $hq_names = array_column($em_hq, 'hq_name');
                $emp_hq = "'" . implode("','", $hq_names) . "'";
            }

            if (!empty($emp_hq)) {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ($emp_hq)";
            } else {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ('')";
            }
            // raja ram only show andra state purpose

            if ($emp_id == '94') {

                $wh = " AND sd_secondarydataupload_t.state IN ('ANDHRA PRADESH')";
            }
        }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $last_data = \DB::select("SELECT  month_y FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $l_dbmon = \DB::select("SELECT  month FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");

        $this->data['pro_names'] = \DB::select("SELECT DISTINCT product_form_change from  `sd_secondarydataupload_t` where 1=1 $wh AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)')");
        $products = $request->input('product');
        $this->data['managers'] = \DB::select("SELECT DISTINCT current_reporting_manager AS name from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $manager = $request->input('manager');
        $condition = '';

        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
        $cur_fyear = $this->data['fy_year'][0]->f_year;

        // defalt previous fy_years
        $pre_cur_year = substr($cur_fyear, 0, 4) - 1;
        $last_preyear = substr($cur_fyear, 5, 7) - 1;
        // Concatenating the current and next year with appropriate formatting
        $pre_fyear = $pre_cur_year . '-' . $last_preyear;

        // Get start date from the request
        $select_date = $request->input('date_select');
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('m', strtotime($select_date));
        $selected_month = date('M', strtotime($select_date));
        $pre_year = $select_year - 1;
        $this->data['cur_fy_year'] = '';
        $this->data['pre_fy_year'] = '';
        $this->data['mon_yr'] = '';
        $this->data['all_ind_sal'] = '';

        if ($select_date != '' || $manager != '') {
            // Determine the financial year based on the start month (assuming April is the start of the financial year)
            $fy_start_month = 4;

            if ($select_month >= $fy_start_month) {

                $fy_full_year = $select_year . '-' . ($select_year + 1);
            }
            if ($select_month < $fy_start_month) {

                $fy_full_year = ($select_year - 1) . '-' . $select_year;
            }

            $fy_year = substr_replace($fy_full_year, '', 5, 2);

            $select_pre = substr($fy_year, 0, 4);
            $select_pre_y = substr($fy_year, 5, 2);

            $select_pre_year = ($select_pre - 1) . '-' . ($select_pre_y - 1);
            $this->data['mon_yr'] = $selected_month . '\'' . substr($select_year, -2);

            if ($products != '') {
                $condition = " AND product_form_change = '$products'";
            }
            if ($manager != '') {
                $condition = " AND current_reporting_manager ='$manager'";
            }

            if($products !='' && $manager !=''){
                
                $condition = " AND product_form_change = '$products' AND current_reporting_manager ='$manager' ";
            }

             $select_target = '';
            
            if ($select_date != '') {
                $sec_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$fy_year'");
                $select_target = $sec_tgt[0]->target_name;
            }

            // get target name dynamic
            $cur_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$fy_year'");
            $cur_target = $cur_tgt[0]->target_name;

            $this->data['cur_fy_year'] = $fy_year;
            $this->data['pre_fy_year'] = $select_pre_year;


            $this->data['all_ind_sal'] = \DB::select("SELECT
      	  zone,region,state,hq_name,field_force_name as field_name,f_year,
          ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sale,
          ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock ELSE 0 END), 0) as target
      FROM
       sd_secondarydataupload_t
    WHERE
       f_year='$fy_year' $condition $wh $notin and product_name NOT IN ('payment outstanding')
       AND (
        STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
    
       )
    GROUP BY
     zone,region,state,hq_name,field_force_name,f_year HAVING (sale !='0' OR target !='0')
    
      UNION ALL
       SELECT
      zone,region,state,hq_name,field_force_name as field_name,f_year,
       ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sale,
       ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock ELSE 0 END), 0) as target

    FROM
       sd_secondarydataupload_t
    WHERE
       f_year='$select_pre_year' $condition $wh $notin and product_name NOT IN ('payment outstanding')
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
    
       )
    GROUP BY
     zone,region,state,hq_name,field_force_name,f_year HAVING (sale !='0' OR target !='0')");
     
        }
        return view('asmmisreport.overallprorpt', $this->data);
    }
    
    // PersonWise Report
    public function Personproductivity(Request $request)
    {
     $wh = '';
        // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        if ($depart == "14" && $emp_id != '121') {

            $emp_hq = '';
            // emp zone 
            $em_hq = \DB::select("SELECT distinct hq_name from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

            if (!empty($em_hq)) {
                $hq_names = array_column($em_hq, 'hq_name');
                $emp_hq = "'" . implode("','", $hq_names) . "'";
            }

            if (!empty($emp_hq)) {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ($emp_hq)";
            } else {
                $wh = " AND sd_secondarydataupload_t.hq_name IN ('')";
            }
            // raja ram only show andra state purpose

            if ($emp_id == '94') {

                $wh = " AND sd_secondarydataupload_t.state IN ('ANDHRA PRADESH')";
            }
        }


    // end
    $notin = ""; // for not in query purpose
    $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

    $last_data = \DB::select("SELECT  month_y FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
    $this->data['last_data'] = $last_data[0]->month_y;
    $l_dbmon = \DB::select("SELECT  month FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
    $l_dbyear = \DB::select("SELECT  c_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
    $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
    $last_m = $l_dbmon[0]->month;
    $last_mon = date('m', strtotime($last_m));
    $last_y = $l_dbyear[0]->c_year;
    $last_mon = date('m', strtotime($last_m));
    $last_y = $l_dbyear[0]->c_year;
    $last_pre_y = $last_y - 1;
    $this->data['last_yr'] = $l_dbyear[0]->c_year;
    $this->data['last_mon'] = date('m', strtotime($last_m));
    $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
    $l_dbmon = \DB::select("SELECT  month_y FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
    $month_y = addslashes($l_dbmon[0]->month_y);
    
    $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_secondarydataupload_t` where 1=1 $wh $notin");
    $zone = $request->input('zone');
    $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_secondarydataupload_t` where 1=1 $wh $notin");
    $region = $request->input('region');
    $this->data['persons'] = \DB::select("SELECT DISTINCT field_force_name from  `sd_secondarydataupload_t` where 1=1 $wh AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)')");
    $person = $request->input('person');
    $this->data['states'] = \DB::select("SELECT DISTINCT state as state from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
    $state = $request->input('state');
    $this->data['regions'] = \DB::select("SELECT DISTINCT hq_name from  `sd_secondarydataupload_t` where 1=1 $wh AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') and hq_name not like '%dist%'");
    $region = $request->input('region');
    $this->data['managers'] = \DB::select("SELECT DISTINCT current_reporting_manager AS name from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
    $manager = $request->input('manager');
    $this->data['areas'] = \DB::select("SELECT DISTINCT area from `sd_secondarydataupload_t` where area NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
    $area = $request->input('area');
    $condition = '';

    // defalt  fy_years start
    $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
    $cur_fyear = $this->data['fy_year'][0]->f_year;

    // defalt previous fy_years
    $pre_cur_year = substr($cur_fyear, 0, 4) - 1;
    $last_preyear = substr($cur_fyear, 5, 7) - 1;
    // Concatenating the current and next year with appropriate formatting
    $pre_fyear = $pre_cur_year . '-' . $last_preyear;

    $this->data['cur_fy_year'] = $cur_fyear;
    $this->data['pre_fy_year'] = $pre_fyear;
    /// end default fy years   
    // get target name dynamic
    $cur_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$cur_fyear'");
    $cur_target = $cur_tgt[0]->target_name;
    // Get start date from the request
    $select_date = $request->input('date_select');
    // Extract year and month from the start date
    $select_year = date('Y', strtotime($select_date));
    $select_month = date('m', strtotime($select_date));
    $select_Mon = date('M', strtotime($select_date));
    // Calculate next and previous years
    $next_year = $select_year + 1;
    $pre_year = $select_year - 1;

    // Determine the financial year based on the start month (assuming April is the start of the financial year)
    $fy_start_month = 4;

    if ($select_month >= $fy_start_month) {

        $fy_full_year = $select_year . '-' . ($select_year + 1);
    }
    if ($select_month < $fy_start_month) {

        $fy_full_year = ($select_year - 1) . '-' . $select_year;
    }

    $fy_year = substr_replace($fy_full_year, '', 5, 2);

    $select_pre = substr($fy_year, 0, 4);
    $select_pre_y = substr($fy_year, 5, 2);

    $select_pre_year = ($select_pre - 1) . '-' . ($select_pre_y - 1);
    $month_yr = addslashes($select_Mon . '\'' . substr($select_year, -2));
    // if user select date search 
    if ($manager != '' || $select_date != '' || $region != ''  || $state != '' || $area !='') {

        if ($select_date != '') {
            $sec_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$fy_year'");
            $select_target = $sec_tgt[0]->target_name;
        }

        $this->data['select_year'] = $select_year;
        $select_mon = date('M', strtotime($select_date));

        $this->data['cur_fy_year'] = $fy_year;
        $this->data['mon_yr'] = $select_mon . "'" . $select_year;
        $this->data['pre_fy_year'] = $select_pre_year;


        if ($select_date == '') {
            $fy_year = $cur_fyear;
            $select_pre_year = $pre_fyear;
            $select_year = $last_y;
            $select_month = $last_mon;
            $this->data['mon_yr'] = $l_dbmon[0]->month_y;
            $this->data['cur_fy_year'] = $cur_fyear;
            $this->data['pre_fy_year'] = $pre_fyear;
            $this->data['select_year'] = $last_y;
            $pre_year = $last_y - 1;
            $month_yr = $month_y;
            $select_target = $cur_target;
        }

            if ($region != '') {
                $condition = "AND hq_name='$region'";
            }

            if ($manager != '') {
                $condition = "AND current_reporting_manager ='$manager'";
            }
            if ($area != '') {
              $condition = "AND area='$area'";
            }
        // head q wise   
        // distributor sale

       $this->data['all_ind_sal'] = \DB::select("SELECT
hq_name,field_force_name as name,f_year,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$select_target' THEN purchase_stock_value ELSE 0 END), 0) as target
FROM
   sd_secondarydataupload_t
WHERE
f_year='$fy_year' and hq_name not like '%dist%' $condition $wh $notin
AND (
   STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')

)
GROUP BY
hq_name,field_force_name,f_year  
    UNION ALL
   SELECT
   hq_name,field_force_name as name,f_year,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
      ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
FROM
   sd_secondarydataupload_t
WHERE
   f_year='$select_pre_year' and hq_name not like '%dist%' $condition $wh $notin
   AND (
       STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

   )
    GROUP BY
    hq_name,field_force_name,f_year");

        // monthly productivity

        $this->data['zone_wise'] = \DB::select("SELECT
        hq_name,current_reporting_manager,field_force_name,
       month_y,f_year,c_year,
       ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
       ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
       FROM
       sd_secondarydataupload_t
       WHERE  f_year = '$fy_year' and hq_name not like '%dist%' $condition $wh $notin
       AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
       GROUP BY
       hq_name,current_reporting_manager,field_force_name,month_y,f_year HAVING (sale !='0' OR target !='0') AND field_force_name !='BIKASH DUTTA' ORDER BY  c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");
 
    } else {
        
        //  default values
        // distributor sale
        $this->data['all_ind_sal'] = \DB::select("SELECT
  hq_name,field_force_name as name,f_year,
   ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
      ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
FROM
   sd_secondarydataupload_t
WHERE
   f_year='$cur_fyear' and hq_name not like '%dist%' $wh $notin
   AND (
       STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')

   )
GROUP BY
hq_name,field_force_name,f_year 
    UNION ALL
   SELECT
   hq_name,field_force_name as name,f_year,
   ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
      ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
FROM
   sd_secondarydataupload_t
WHERE
   f_year='$pre_fyear' and hq_name not like '%dist%' $wh $notin
   AND (
       STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

   )
GROUP BY
hq_name,field_force_name,f_year ");

        // monthly productiity

        $this->data['zone_wise'] = \DB::select("SELECT
        hq_name,current_reporting_manager,field_force_name,month,
        month_y,f_year,c_year,
       
       ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
       ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
       FROM
       sd_secondarydataupload_t
       WHERE  f_year = '$cur_fyear' and hq_name not like '%dist%' $wh $notin
       AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
       GROUP BY
       hq_name,current_reporting_manager,field_force_name,month,month_y,f_year HAVING (sale !='0' OR target !='0') AND field_force_name !='BIKASH DUTTA' ORDER BY  c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");
    }

    return view('asmmisreport.personwise_production', $this->data);
}

}
