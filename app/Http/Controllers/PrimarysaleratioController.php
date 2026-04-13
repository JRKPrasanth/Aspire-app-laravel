<?php

namespace App\Http\Controllers;
use App\Misviewers;
use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;

class PrimarysaleratioController extends Controller

{
	
	   public function __construct(){
        
		$this->pageModule="primarysaleratioindex";	
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

        // login viewers track purpose End
			
	 return view('primarysaleratio.index', $this->data);
	
	}
	
    // ZoneWise Report
    public function MonzoneWise(Request $request)
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
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes' AND active='Yes'");

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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $l_dbmon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $this->data['last_yr'] =  $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));

        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
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

        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['mon_yr'] = $select_mon . "'" . $select_year;

            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }
            // without date search
            if ($select_date == '') {
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
     ROUND(SUM(CASE WHEN data_type = 'Sample' THEN asseesable_value ELSE 0 END), 0) as sample
     FROM
     sd_primarydataupload_t
     WHERE 1=1
      $condition $wh $notin
     AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
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
         ROUND(SUM(CASE WHEN data_type = 'Sample' THEN asseesable_value ELSE 0 END), 0) as sample
         FROM
         sd_primarydataupload_t
         WHERE 1=1
         $condition $wh $notin
         AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
         AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
         GROUP BY
         zone,f_year
         ORDER BY zone,
         STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");

            // month chart

            $month_sumchart = $this->data['month_wise'];

            $primData = [];
            foreach ($month_sumchart as $primary) {

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : null;

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

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : null;

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
        ROUND(SUM(CASE WHEN data_type = 'Sample' THEN asseesable_value ELSE 0 END), 0) as sample
        FROM
        sd_primarydataupload_t
        WHERE 1=1
         $wh $notin
        AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
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
            ROUND(SUM(CASE WHEN data_type = 'Sample' THEN asseesable_value ELSE 0 END), 0) as sample
            FROM
            sd_primarydataupload_t
            WHERE
            1=1 $wh $notin
            AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
            AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
            GROUP BY
            zone,f_year
            ORDER BY
            zone,STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");

            // month chart

            $month_sumchart = $this->data['month_wise'];

            $primData = [];
            foreach ($month_sumchart as $primary) {

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : null;

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

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : null;

                $zoneData[] = [
                    'zone' => $primary->zone,
                    'ratio' => $ratio !== null ? number_format($ratio, 0) . "%" : "0",

                ];
            }

            $this->data['zone_sumchart'] = json_encode($zoneData);
            //dd($this->data['zone_sumchart']);

        }

        return view('primarysaleratio.month', $this->data);
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
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes' AND active='Yes'");

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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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
        // dd($last_m);

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


        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));


            $this->data['mon_yr'] = $select_mon . "'" . $select_year;



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

                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // month wise 

            $this->data['month_wise'] = \DB::select("SELECT
     f_year,region,
     ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
     ROUND(SUM(CASE WHEN data_type = 'Sample' THEN asseesable_value ELSE 0 END), 0) as sample
     FROM
     sd_primarydataupload_t
     WHERE 1=1
      $condition $wh $notin
     AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
     AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
     GROUP BY
     region,f_year
     ORDER BY
     STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");



            // month chart

            $month_sumchart = $this->data['month_wise'];

            $primData = [];
            foreach ($month_sumchart as $primary) {

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : null;

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
        ROUND(SUM(CASE WHEN data_type = 'Sample' THEN asseesable_value ELSE 0 END), 0) as sample
        FROM
        sd_primarydataupload_t
        WHERE
        1=1 $wh $notin
        AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
        AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
        GROUP BY
        region,f_year
        ORDER BY
        STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");


            // month chart

            $month_sumchart = $this->data['month_wise'];

            $primData = [];
            foreach ($month_sumchart as $primary) {

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : null;

                $primData[] = [
                    'region' => $primary->region,
                    'ratio' => $ratio !== null ? number_format($ratio, 0) . "%" : "0",

                ];
            }

            $this->data['month_sumchart'] = json_encode($primData);
        }

        return view('primarysaleratio.region', $this->data);
    }

    // StateWise Wise Report
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
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes' AND active='Yes'");

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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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


        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));


            $this->data['mon_yr'] = $select_mon . "'" . $select_year;



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


                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // month wise 

            $this->data['state_wise'] = \DB::select("SELECT
        f_year,state,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = 'Sample' THEN asseesable_value ELSE 0 END), 0) as sample
        FROM
        sd_primarydataupload_t
        WHERE 1=1
         $condition $wh $notin  
        AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
        AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
        GROUP BY
        state
        ORDER BY
  state ASC");

            // month chart

            $state_sumchart = $this->data['state_wise'];
            $primData = [];
            foreach ($state_sumchart as $primary) {

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : null;

                $primData[] = [
                    'state' => $primary->state,
                    'ratio' => $ratio !== null ? number_format($ratio, 0) . "%" : "0",

                ];
            }

            $this->data['state_sumchart'] = json_encode($primData);
        } else {
            // month wise 

            $this->data['state_wise'] = \DB::select("SELECT
           f_year,
           state,
           ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN asseesable_value ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE 1=1
            $wh $notin  
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           state
           ORDER BY
           state ASC");


            // month chart

            $state_sumchart = $this->data['state_wise'];

            $primData = [];
            foreach ($state_sumchart as $primary) {

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : null;

                $primData[] = [
                    'state' => $primary->state,
                    'ratio' => $ratio !== null ? number_format($ratio, 0) . "%" : "0",

                ];
            }

            $this->data['state_sumchart'] = json_encode($primData);
        }

        return view('primarysaleratio.state', $this->data);
    }

    // product Wise Report
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
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes' AND active='Yes'");

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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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


        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '' || $division != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));


            $this->data['mon_yr'] = $select_mon . "'" . $select_year;


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

                $select_year = $last_y;
                $select_month = date('M', strtotime($last_m));
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // month wise 

            $this->data['product_wise'] = \DB::select("SELECT
        sfg_product_name,division,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = 'Sample' THEN asseesable_value ELSE 0 END), 0) as sample
        FROM
        sd_primarydataupload_t
        WHERE
        1=1 $condition $wh $notin
        AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
        AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
        GROUP BY
        division,sfg_product_name HAVING (sale !='0' OR sample !='0')
        ORDER BY sfg_product_name ASC");

            // month chart

            $pro_sumchart = $this->data['product_wise'];

            $primData = [];
            foreach ($pro_sumchart as $primary) {

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : null;

                $primData[] = [
                    'name' => $primary->sfg_product_name,
                    'ratio' => $ratio !== null ? number_format($ratio, 0) . "%" : "0",

                ];
            }

            $this->data['pro_sumchart'] = json_encode($primData);
        } else {
            // month wise 

            $this->data['product_wise'] = \DB::select("SELECT
           division,
           sfg_product_name,
           ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN asseesable_value ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           division,sfg_product_name HAVING (sale !='0' OR sample !='0')
           ORDER BY sfg_product_name ASC");

            // month chart
            $pro_sumchart = $this->data['product_wise'];

            $primData = [];
            foreach ($pro_sumchart as $primary) {

                $ratio = $primary->sale != 0 ? (($primary->sample / $primary->sale) * 100) : null;

                $primData[] = [
                    'name' => $primary->sfg_product_name,
                    'ratio' => $ratio !== null ? number_format($ratio, 0) . "%" : "0",

                ];
            }

            $this->data['pro_sumchart'] = json_encode($primData);
        }

        return view('primarysaleratio.product', $this->data);
    }

    // zone  Wise Ratio
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
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes' AND active='Yes'");

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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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


        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '' || $division != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));


            $this->data['mon_yr'] = $select_mon . "'" . $select_year;



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


                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // zone wise 

            $this->data['zone_wise'] = \DB::select("SELECT
           division,
           upper(sfg_product_name) as sfg_product_name,
           zone,
           
           ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN asseesable_value ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE 1=1
           $condition $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
           GROUP BY
           zone,division,sfg_product_name HAVING (sale !='0' or sample !='0')");
           
        } else {

            // zone wise 

            $this->data['zone_wise'] = \DB::select("SELECT
           division,
           upper(sfg_product_name) as sfg_product_name,
           zone,
           ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN asseesable_value ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           zone,division,sfg_product_name HAVING (sale !='0' or sample !='0')");
        }

        return view('primarysaleratio.zone_ratio', $this->data);
    }

    // Region  Wise Ratio
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
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes' AND active='Yes'");

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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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

        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '' || $division != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));


            $this->data['mon_yr'] = $select_mon . "'" . $select_year;



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


                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // zone wise 

            $this->data['zone_wise'] = \DB::select("SELECT
           division,
           upper(sfg_product_name) as sfg_product_name,
           region,
           ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN asseesable_value ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE 1=1
            $condition $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
           GROUP BY
           division,sfg_product_name,region HAVING (sale !='0' or sample !='0')
           ORDER BY
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
           
        } else {

            // zone wise 

            $this->data['zone_wise'] = \DB::select("SELECT
           division,
           upper(sfg_product_name) as sfg_product_name,
           region,
           
           ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN asseesable_value ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           division,sfg_product_name,region HAVING (sale !='0' or sample !='0')
           ORDER BY
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        }

        return view('primarysaleratio.region_ratio', $this->data);
    }

    // state  Wise Ratio
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
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes' AND active='Yes'");

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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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


        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '' || $division != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));


            $this->data['mon_yr'] = $select_mon . "'" . $select_year;



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


                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // zone wise 

            $this->data['zone_wise'] = \DB::select("SELECT
           division,
           upper(sfg_product_name) as sfg_product_name,
           state,
           ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN asseesable_value ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $condition $wh $notin 
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
           GROUP BY
           division,sfg_product_name,state HAVING (sale !='0' or sample !='0')
           ORDER BY state,
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
           
        } else {

            // zone wise 

            $this->data['zone_wise'] = \DB::select("SELECT
           division,
           upper(sfg_product_name) as sfg_product_name,
           state,
           
           ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN asseesable_value ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $wh $notin  
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           division,sfg_product_name,state HAVING (sale !='0' or sample !='0')
           ORDER BY state,
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        }

        return view('primarysaleratio.state_ratio', $this->data);
    }

    // month  Wise sample
    public function Monthsample(Request $request)
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
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes' AND active='Yes'");

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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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

        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '' || $division != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));


            $this->data['mon_yr'] = $select_mon . "'" . $select_year;



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


                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // zone wise 

            $this->data['month_sample'] = \DB::select("SELECT
           division,
           upper(product_form_change) as sfg_product_name,
           month_y,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $condition $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
           GROUP BY
           division,product_form_change,month_y HAVING  sample !='0'
           ORDER BY
           STR_TO_DATE(CONCAT('20', SUBSTRING(month_y, -2), ' ', SUBSTRING(month_y, 1, 3), ' 01'), '%Y %b %d')");
           
        } else {

            // zone wise 
            $this->data['month_sample'] = \DB::select("SELECT
           division,
           upper(product_form_change) as sfg_product_name,
           month_y,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           division,product_form_change,month_y HAVING  sample !='0'
           ORDER BY
          STR_TO_DATE(CONCAT('20', SUBSTRING(month_y, -2), ' ', SUBSTRING(month_y, 1, 3), ' 01'), '%Y %b %d')");
        }

        return view('primarysaleratio.month_sample', $this->data);
    }

    // zone  Wise sample
    public function Zonesample(Request $request)
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
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes' AND active='Yes'");

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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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

        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '' || $division != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));


            $this->data['mon_yr'] = $select_mon . "'" . $select_year;



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


                $select_year = $last_y;
                $select_month = $last_m;
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // zone wise 

            $this->data['month_sample'] = \DB::select("SELECT
           division,
           upper(product_form_change) as sfg_product_name,
           c_year,zone,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $condition $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
           GROUP BY
           division,product_form_change,zone HAVING  sample !='0'
           ORDER BY zone,
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
           
        } else {

            // zone wise 

            $this->data['month_sample'] = \DB::select("SELECT
           division,
           upper(product_form_change) as sfg_product_name,
           c_year,zone,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           division,product_form_change,zone, f_year HAVING  sample !='0'
           ORDER BY zone,
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        }

        return view('primarysaleratio.zone_sample', $this->data);
    }

    // region  Wise sample
    public function Regionsample(Request $request)
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
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes' AND active='Yes'");

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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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

        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '' || $division != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['mon_yr'] = $select_mon . "'" . $select_year;

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


                $select_year = $last_y;
                $select_month = date('M', strtotime($last_m));
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // zone wise 

            $this->data['month_sample'] = \DB::select("SELECT
           division,
           upper(product_form_change) as sfg_product_name,
           c_year,region,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $condition $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
           GROUP BY
           division,product_form_change,region HAVING  sample !='0'
           ORDER BY region,
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        } else {

            // zone wise 

            $this->data['month_sample'] = \DB::select("SELECT
           division,
           upper(product_form_change) as sfg_product_name,
           c_year,region,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $wh $notin
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           division,product_form_change,region HAVING  sample !='0'
           ORDER BY region,
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        }

        return view('primarysaleratio.region_sample', $this->data);
    }

    // region  Wise sample
    public function Statesample(Request $request)
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
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes' AND active='Yes'");

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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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
        $current_y = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_y = $current_y[0]->c_year;

        $current_mon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_m = $current_mon[0]->month;
        // dd($last_m);

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


        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '' || $division != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));


            $this->data['mon_yr'] = $select_mon . "'" . $select_year;



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


                $select_year = $last_y;
                $select_month = date('M', strtotime($last_m));
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // zone wise 

            $this->data['month_sample'] = \DB::select("SELECT
           division,
           upper(product_form_change) as sfg_product_name,
           c_year,state,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $condition  $wh $notin 
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
           GROUP BY
           division,product_form_change,state HAVING  sample !='0'
           ORDER BY state,
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        } else {

            // zone wise 

            $this->data['month_sample'] = \DB::select("SELECT
           division,
           upper(product_form_change) as sfg_product_name,
           state,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN sales_free_unit ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $wh $notin  
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           division,product_form_change,state HAVING  sample !='0'
           ORDER BY state,
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        }

        return view('primarysaleratio.state_sample', $this->data);
    }

    // region  Wise sample
    public function Personsample(Request $request)
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
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes' AND active='Yes'");

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
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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
        $this->data['persons'] = \DB::select("SELECT DISTINCT stockist_dist_name as name from  `sd_primarydataupload_t` where 1=1 $wh  AND name_type='PERSONS'");
        $person = $request->input('person');

        $this->data['states'] = \DB::select("SELECT DISTINCT state_consolidated as state from `sd_primarydataupload_t` where state_consolidated NOT IN('EXPORT','LOCAL','GOVT SUPPLY') and data_type NOT IN ('Oversease')");;
        $state = $request->input('state');

        $condition = '';
        $current_y = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_y = $current_y[0]->c_year;

        $current_mon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_m = $current_mon[0]->month;
        // dd($last_m);

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


        // if user select date search 
        if ($zone != '' ||  $select_date != '' || $region != '' || $person != '' || $state != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));


            $this->data['mon_yr'] = $select_mon . "'" . $select_year;


            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($division != '') {
                $condition = "AND division='$division'";
            }
            if ($state != '') {
                $condition = "AND state ='$state'";
            }
            if ($state != '' && $region != '') {
                $condition = "AND state='$state' AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            if ($zone != '' && $region != '' && $division != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND division='$division'";
            }

            if ($zone != '' && $region != '' && $state != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND state='$state'";
            }
            if ($person != '') {
                $condition = "AND stockist_dist_name ='$person'";
            }

            if ($select_date == '') {


                $select_year = $last_y;
                $select_month = date('M', strtotime($last_m));
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
            }
            // zone wise 

            $this->data['month_sample'] = \DB::select("SELECT
           state,
           upper(product_form_change) as sfg_product_name,stockist_dist_name as name,month_y,
           c_year,state,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN unit_sold ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $condition $wh $notin AND name_type='PERSONS'
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%b-%d')
           GROUP BY
           state,month_y,product_form_change,stockist_dist_name HAVING  sample !='0'
           ORDER BY state,
              STR_TO_DATE(CONCAT('20', SUBSTRING(month_y, -2), ' ', SUBSTRING(month_y, 1, 3), ' 01'), '%Y %b %d')");
        } else {

            // zone wise 

            $this->data['month_sample'] = \DB::select("SELECT
           f_year,division,
           upper(product_form_change) as sfg_product_name,stockist_dist_name as name,month_y,
           state,
           ROUND(SUM(CASE WHEN data_type = 'Sample' THEN unit_sold ELSE 0 END), 0) as sample
           FROM
           sd_primarydataupload_t
           WHERE
           1=1 $wh $notin AND name_type='PERSONS'
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >= DATE_SUB(STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d'), INTERVAL 5 MONTH)
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           state,month_y,product_form_change,stockist_dist_name HAVING  sample !='0'
           ORDER BY state,
               STR_TO_DATE(CONCAT('20', SUBSTRING(month_y, -2), ' ', SUBSTRING(month_y, 1, 3), ' 01'), '%Y %b %d')");
        }

        return view('primarysaleratio.person_sample', $this->data);
    }
}
