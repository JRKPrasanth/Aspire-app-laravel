<?php

namespace App\Http\Controllers;
use App\Misviewers;
use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;

    class PrimarysalesreturnController extends Controller
    {
		
		   public function __construct(){
        
		$this->pageModule="primarysalesreturnindex";	
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
			
	 return view('primarysalesreturn.index', $this->data);
	
	}
		
    // ZoneWise Report
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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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
        $this->data['select_year'] = $last_y;

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
            // if without date wise search
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }

            // month wise 

            $this->data['month_wise'] = \DB::select("SELECT
             f_year,
             month,month_y,
             c_year,
             ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
             ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN asseesable_value ELSE 0 END), 0) as sample
             FROM
             sd_primarydataupload_t
             WHERE
             f_year = '$fy_year' $condition $wh  $notin
  
             AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
             GROUP BY
             month_y,month, f_year
             ORDER BY
             STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");

            // zone wise
            $this->data['zone_wise'] = \DB::select("SELECT
         f_year,
         zone,
         c_year,
         
         ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
         ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN asseesable_value ELSE 0 END), 0) as sample
         FROM
         sd_primarydataupload_t
         WHERE
         f_year = '$fy_year' $condition $wh $notin
         AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
         GROUP BY
         zone,f_year
         ORDER BY
         STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");

            // month chart

            $month_sumchart = $this->data['month_wise'];

            $primData = [];
            foreach ($month_sumchart as $primary) {

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : "0";

                $primData[] = [
                    'month' => $primary->month_y,
                    'ratio' => $ratio !== null ? number_format($ratio, 0) . "%" : "0",

                ];
            }

            $this->data['month_sumchart'] = json_encode($primData);

            // zone chart

            $zone_sumchart = $this->data['zone_wise'];

            $zoneData = [];
            foreach ($zone_sumchart as $primary) {

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : "0";

                $zoneData[] = [
                    'zone' => $primary->zone,
                    'ratio' => $ratio !== null ? number_format($ratio, 0) . "%" : "0",

                ];
            }

            $this->data['zone_sumchart'] = json_encode($zoneData);
        } else {
            // month wise 

            $this->data['month_wise'] = \DB::select("SELECT
        f_year,
        month,month_y,
        c_year,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN asseesable_value ELSE 0 END), 0) as sample
        FROM
        sd_primarydataupload_t
        WHERE
        f_year = '$cur_fyear' $wh $notin
       AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
        GROUP BY
        month_y,month, f_year
        ORDER BY
        STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");

            // zone wise
            $this->data['zone_wise'] = \DB::select("SELECT
            f_year,
            zone,
            c_year,
            
            ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
            ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN asseesable_value ELSE 0 END), 0) as sample
            FROM
            sd_primarydataupload_t
            WHERE
            f_year = '$cur_fyear' $wh $notin
            AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
            GROUP BY
            zone,f_year
            ORDER BY
            STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");

            // month chart

            $month_sumchart = $this->data['month_wise'];

            $primData = [];
            foreach ($month_sumchart as $primary) {

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : "0";

                $primData[] = [
                    'month' => $primary->month_y,
                    'ratio' => $ratio !== null ? number_format($ratio, 0) . "%" : "0",

                ];
            }

            $this->data['month_sumchart'] = json_encode($primData);

            // zone chart

            $zone_sumchart = $this->data['zone_wise'];

            $zoneData = [];
            foreach ($zone_sumchart as $primary) {

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : "0";

                $zoneData[] = [
                    'zone' => $primary->zone,
                    'ratio' => $ratio !== null ? number_format($ratio, 0) . "%" : "0",

                ];
            }

            $this->data['zone_sumchart'] = json_encode($zoneData);
            //dd($this->data['zone_sumchart']);

        }

        return view('primarysalesreturn.month', $this->data);
    }

    // Region Wise Report
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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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
            // if without date wise search
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // month wise 

            $this->data['month_wise'] = \DB::select("SELECT
     f_year,region,
     c_year,
     
     ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
     ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN asseesable_value ELSE 0 END), 0) as sample
     FROM
     sd_primarydataupload_t
     WHERE
     f_year = '$fy_year' $condition $wh $notin
     AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
     GROUP BY
     region,f_year
     ORDER BY
     STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");

            // month chart

            $month_sumchart = $this->data['month_wise'];

            $primData = [];
            foreach ($month_sumchart as $primary) {

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : "0";

                $primData[] = [
                    'region' => $primary->region,
                    'ratio' => $ratio !== null ? number_format($ratio, 0) . "%" : "0",

                ];
            }

            $this->data['month_sumchart'] = json_encode($primData);
        } else {

            // month wise 

            $this->data['month_wise'] = \DB::select("SELECT
        f_year,
        region,
        c_year,
        
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN asseesable_value ELSE 0 END), 0) as sample
        FROM
        sd_primarydataupload_t
        WHERE
        f_year = '$cur_fyear' $wh $notin
        AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
        GROUP BY
        region, f_year
        ORDER BY
        STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");


            // month chart

            $month_sumchart = $this->data['month_wise'];

            $primData = [];
            foreach ($month_sumchart as $primary) {

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : "0";

                $primData[] = [
                    'region' => $primary->region,
                    'ratio' => $ratio !== null ? number_format($ratio, 0) . "%" : "0",

                ];
            }

            $this->data['month_sumchart'] = json_encode($primData);
        }

        return view('primarysalesreturn.region', $this->data);
    }

    // state Wise  

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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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
        $this->data['select_year'] = $last_y;
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
            // if without date wise search
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // state_wise

            $this->data['state_wise'] = \DB::select("SELECT
             f_year,state,
             c_year,
             
             ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
             ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN asseesable_value ELSE 0 END), 0) as sample
             FROM
             sd_primarydataupload_t
             WHERE
             f_year = '$fy_year' $condition $wh $notin
             AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
             GROUP BY
             state,f_year");

            // state chart
            $month_sumchart = $this->data['state_wise'];

            $primData = [];
            foreach ($month_sumchart as $primary) {

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : "0";

                $primData[] = [
                    'state' => $primary->state,
                    'ratio' => $ratio !== null ? number_format($ratio, 0) . "%" : "0",

                ];
            }

            $this->data['state_sumchart'] = json_encode($primData);
        } else {

            // state wise 
            $this->data['state_wise'] = \DB::select("SELECT
        f_year,
        state,
        c_year, 
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN asseesable_value ELSE 0 END), 0) as sample
        FROM
        sd_primarydataupload_t
        WHERE
        f_year = '$cur_fyear' $wh $notin
        AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
        GROUP BY
        state, f_year");

            // state chart
            $month_sumchart = $this->data['state_wise'];

            $primData = [];
            foreach ($month_sumchart as $primary) {

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : "0";

                $primData[] = [
                    'state' => $primary->state,
                    'ratio' => $ratio !== null ? number_format($ratio, 0) . "%" : "0",

                ];
            }

            $this->data['state_sumchart'] = json_encode($primData);
        }
        return view('primarysalesreturn.state', $this->data);
    }

    // product wise

    public function ProductWise(Request $request)
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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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

        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from  `sd_primarydataupload_t` where 1=1 $wh AND division NOT IN('GIFTS','OTHERS','POSTERS','FLYERS','BROUCHERS','GIFT ITEMS','VISUAL AID','')");
        $division = $request->input('division');
        $condition = '';

        $this->data['select_year'] = $last_y;
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
        if ($zone != '' ||  $select_date != '' || $region != '' || $division != '') {

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
            if ($division != '') {
                $condition = "AND division='$division'";
            }

            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($zone != '' && $region != '' && $division != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND division='$division'";
            }

            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // product wise 

            $this->data['state_wise'] = \DB::select("SELECT
        f_year,
        division,sfg_product_name as name,
        c_year,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN asseesable_value ELSE 0 END), 0) as sample
        FROM
        sd_primarydataupload_t
        WHERE
        f_year = '$fy_year' $condition $wh $notin
        AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
        GROUP BY
        division,sfg_product_name,f_year HAVING sale !='0'");
        
        } else {

            // product wise 

            $this->data['state_wise'] = \DB::select("SELECT
        f_year,
        division,sfg_product_name as name,
        c_year,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN asseesable_value ELSE 0 END), 0) as sample
        FROM
        sd_primarydataupload_t
        WHERE
        f_year = '$cur_fyear' $wh $notin
        AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
        GROUP BY
        division,sfg_product_name, f_year HAVING sale !='0'");
        }

        return view('primarysalesreturn.product', $this->data);
    }

    // zone wise ratio

    public function Zoneratio(Request $request)
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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $l_dbmon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $this->data['last_yr'] =  $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        //  dd($this->data['last_mon'] );
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_secondarydataupload_t` where division!='-'");
        $division = $request->input('division');
        $condition = '';
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['select_year'] = $last_y;
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
        if ($zone != '' ||  $select_date != '' || $region != '' || $division != '') {

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
            if ($division != '') {
                $condition = "AND division='$division'";
            }

            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($zone != '' && $region != '' && $division != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND division='$division'";
            }

            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // zone wise 

            $this->data['zone_wise'] = \DB::select("SELECT
           f_year,division,
           sfg_product_name,
           zone,
           ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
           ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN asseesable_value ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $condition $wh $notin
            AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%M-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%M-%d')
           GROUP BY
           division,sfg_product_name,zone HAVING (sale !='0' or sample !='0')");
           
        } else {

            // zone ratio 

            $this->data['zone_wise'] = \DB::select("SELECT
           f_year,division,
           sfg_product_name,
           zone,
           ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
           ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN asseesable_value ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE 1=1
            $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           division,sfg_product_name,zone, f_year HAVING (sale !='0' or sample !='0')
           ORDER BY
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
           
        }

        return view('primarysalesreturn.zoneratio', $this->data);
    }

    // region ratio

    public function Regionratio(Request $request)
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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_secondarydataupload_t` where division!='-'");
        $division = $request->input('division');
        $condition = '';

        $this->data['select_year'] = $last_y;
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
        if ($zone != '' ||  $select_date != '' || $region != '' || $division != '') {

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
            if ($division != '') {
                $condition = "AND division='$division'";
            }

            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($zone != '' && $region != '' && $division != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND division='$division'";
            }
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // region wise 

            $this->data['zone_wise'] = \DB::select("SELECT
           f_year,division,
           sfg_product_name,
           region,
           ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
           ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN asseesable_value ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE 1=1
            $condition $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
           GROUP BY
           division,sfg_product_name,region, f_year HAVING (sale !='0' or sample !='0')
           ORDER BY
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
           
        } else {

            // region wise 

            $this->data['zone_wise'] = \DB::select("SELECT
           f_year,division,
           sfg_product_name,
           region,
           ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
           ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN asseesable_value ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE 1=1
            $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           division,sfg_product_name,region, f_year HAVING (sale !='0' or sample !='0')
           ORDER BY
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        }

        return view('primarysalesreturn.region_ratio', $this->data);
    }

    // state ratio

    public function Stateratio(Request $request)
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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_secondarydataupload_t` where division!='-'");
        $division = $request->input('division');
        $condition = '';

        $this->data['select_year'] = $last_y;
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
        if ($zone != '' ||  $select_date != '' || $region != '' || $division != '') {

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
            if ($division != '') {
                $condition = "AND division='$division'";
            }

            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($zone != '' && $region != '' && $division != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND division='$division'";
            }
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // state wise 

            $this->data['zone_wise'] = \DB::select("SELECT
           f_year,division,
           sfg_product_name,
           state,
           ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
           ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN asseesable_value ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $condition $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
           GROUP BY
           division,sfg_product_name,state, f_year HAVING (sale !='0' or sample !='0')
           ORDER BY
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        } else {

            // region wise 

            $this->data['zone_wise'] = \DB::select("SELECT
           f_year,division,
           sfg_product_name,
           state,
           ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
           ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN asseesable_value ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           division,sfg_product_name,state, f_year HAVING (sale !='0' or sample !='0')");
        }

        return view('primarysalesreturn.state_ratio', $this->data);
    }


    // month  Wise return
    public function Monthreturn(Request $request)
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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_secondarydataupload_t` where division!='-'");
        $division = $request->input('division');
        $condition = '';

        $this->data['select_year'] = $last_y;
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
        if ($zone != '' ||  $select_date != '' || $region != '' || $division != '') {

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
            if ($division != '') {
                $condition = "AND division='$division'";
            }

            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($zone != '' && $region != '' && $division != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND division='$division'";
            }
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // zone wise 

            $this->data['month_sample'] = \DB::select("SELECT
           division,
           product_form_change as sfg_product_name,
           month_y,
           ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1  $condition $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
           GROUP BY
           division,product_form_change,month_y  HAVING sample !='0'
           ORDER BY
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
           
        } else {

            // zone wise 
            $this->data['month_sample'] = \DB::select("SELECT
           division,
          product_form_change as sfg_product_name,
           month_y,
           ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           division,product_form_change,month_y  HAVING sample !='0'
           ORDER BY
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        }

        return view('primarysalesreturn.mon_return', $this->data);
    }

    // zone  Wise return
    public function Zonereturn(Request $request)
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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_secondarydataupload_t` where division!='-'");
        $division = $request->input('division');
        $condition = '';

        $this->data['select_year'] = $last_y;
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
        if ($zone != '' ||  $select_date != '' || $region != '' || $division != '') {

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
            if ($division != '') {
                $condition = "AND division='$division'";
            }

            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($zone != '' && $region != '' && $division != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND division='$division'";
            }
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // zone wise 

            $this->data['month_sample'] = \DB::select("SELECT
           division,
           	product_form_change as sfg_product_name,
           zone,
           ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $condition $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
           GROUP BY
           division,product_form_change,zone HAVING  sample !='0'
           ORDER BY
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
           
        } else {

            // zone wise 
            $this->data['month_sample'] = \DB::select("SELECT
           division,
           product_form_change as sfg_product_name,
           zone,
           ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           division,product_form_change,zone HAVING  sample !='0'
           ORDER BY
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        }

        return view('primarysalesreturn.zone_return', $this->data);
    }


    // Region  Wise return
    public function Regionreturn(Request $request)
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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_secondarydataupload_t` where division!='-'");
        $division = $request->input('division');
        $condition = '';

        $this->data['select_year'] = $last_y;
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
        if ($zone != '' ||  $select_date != '' || $region != '' || $division != '') {

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
            if ($division != '') {
                $condition = "AND division='$division'";
            }

            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($zone != '' && $region != '' && $division != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND division='$division'";
            }
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // region wise 

            $this->data['month_sample'] = \DB::select("SELECT
           division,
           product_form_change as sfg_product_name,
           region,
          ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $condition $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
           GROUP BY
           division,product_form_change,region HAVING  sample !='0'
           ORDER BY
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
           
        } else {

            // region wise 
            $this->data['month_sample'] = \DB::select("SELECT
           division,
           product_form_change as sfg_product_name,
           region,
           ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           division,product_form_change,region HAVING  sample !='0'
           ORDER BY
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        }

        return view('primarysalesreturn.region_return', $this->data);
    }

    // state  Wise return
    public function Statereturn(Request $request)
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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_secondarydataupload_t` where division!='-'");
        $division = $request->input('division');
        $condition = '';

        $this->data['select_year'] = $last_y;

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
        if ($zone != '' ||  $select_date != '' || $region != '' || $division != '') {

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
            if ($division != '') {
                $condition = "AND division='$division'";
            }

            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($zone != '' && $region != '' && $division != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND division='$division'";
            }
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // state wise 

            $this->data['month_sample'] = \DB::select("SELECT
            division,
           product_form_change as sfg_product_name,
           state_consolidated as state,
          ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $condition $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
           GROUP BY
           division,product_form_change,state HAVING  sample !='0'
           ORDER BY
           state_consolidated ASC");
           
        } else {

            // state wise 
            $this->data['month_sample'] = \DB::select("SELECT
           division,
           product_form_change as sfg_product_name,
           state_consolidated as state,
           ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           division,product_form_change,state HAVING  sample !='0'
           ORDER BY
          state_consolidated ASC");
        }

        return view('primarysalesreturn.state_return', $this->data);
    }

    // person wise sales return 

    public function Personreturn(Request $request)
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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_secondarydataupload_t` where division!='-'");
        $division = $request->input('division');
        $condition = '';

        $this->data['select_year'] = $last_y;
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
        if ($zone != '' ||  $select_date != '' || $region != '' || $division != '') {

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
            if ($division != '') {
                $condition = "AND division='$division'";
            }

            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($zone != '' && $region != '' && $division != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND division='$division'";
            }
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // distributor wise 

            $this->data['month_sample'] = \DB::select("SELECT
           state_consolidated as state,
           	product_form_change as sfg_product_name,stockist_dist_name as name,month_y,
           ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1  $condition $wh  AND name_type='DISTRIBUTORS' $notin
            AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
           GROUP BY
           state_consolidated,month_y,product_form_change,stockist_dist_name HAVING sample !='0'
           ORDER BY
              STR_TO_DATE(CONCAT('20', SUBSTRING(month_y, -2), ' ', SUBSTRING(month_y, 1, 3), ' 01'), '%Y %b %d')");
        } else {

            // person wise 

            $this->data['month_sample'] = \DB::select("SELECT
           division,
           	product_form_change as sfg_product_name,stockist_dist_name as name,month_y,
           state_consolidated as state,
           ROUND(SUM(CASE WHEN data_type = 'Sales Return' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $wh AND name_type='DISTRIBUTORS' $notin
            AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           state_consolidated,month_y,product_form_change,stockist_dist_name HAVING sample !='0'
           ORDER BY
               STR_TO_DATE(CONCAT('20', SUBSTRING(month_y, -2), ' ', SUBSTRING(month_y, 1, 3), ' 01'), '%Y %b %d')");
        }

        return view('primarysalesreturn.person_return', $this->data);
    }
}
