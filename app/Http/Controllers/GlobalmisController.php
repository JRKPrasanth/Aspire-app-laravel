<?php

namespace App\Http\Controllers;
use App\Misviewers;
use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;
use Illuminate\Support\Facades;

    class GlobalmisController extends Controller
    {
        
        	   public function __construct(){
        
		$this->pageModule="globalmisreportindex";	
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

			
	 return view('globalmis.index', $this->data);
	
	}
		
        // month Wise 
    public function MonthWise(Request $request)
    {
    
        $l_dbmon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 ");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 ");
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
        if ($zone != '' || $select_date != '' || $region != '') {

            if ($select_date != ''){ 
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
            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            // month wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
            f_year,
            month,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$fy_year' $condition
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')

            )
            GROUP BY
            f_year, month
            UNION ALL
            SELECT
            f_year,
            month,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year ='$select_pre_year'  $condition
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

            )
            GROUP BY
            f_year, month ORDER BY FIELD (month, 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec', 'jan', 'feb', 'mar')");

            // chart
            
          $prim_sumchart = $this->data['all_ind_sal'];
          
            $primData = [];
        
        // Create an associative array to store growth values for each month
        $monthGrowth = [];
        
        // Iterate through each record
        foreach ($prim_sumchart as $primary) {
            $month = $primary->month;
            
            // Find the corresponding sale for the current year
            $currentYearSale = $primary->sale;
        
            // Find the corresponding sale for the same month in the previous year
            $previousYearSale = null;
            foreach ($prim_sumchart as $record) {
                if ($record->f_year == $select_pre_year && $record->month == $month) {
                    $previousYearSale = $record->sale;
                    break;
                }
            }
            
                // Calculate growth if previous year sale is not null and not 0
                if ($previousYearSale !== null && $previousYearSale != 0) {
                    $growth = (($currentYearSale / $previousYearSale) - 1) * 100;
                } else {
                    $growth = null;
                }
            
                // Store the growth value for the month
                $monthGrowth[$month] = $growth;
            }
            
            // Format the data for output
            foreach ($monthGrowth as $month => $growth) {
                $primData[] = [
                    'month' => $month,
                    'growth' => $growth !== null ? number_format($growth, 0) . "%" : "0",
                ];
            }
            
            $this->data['prim_sumchart'] = json_encode($primData);

            
        } else {
            //      default values

            // region wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
            f_year,
            month,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$cur_fyear' 
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')

            )
            GROUP BY
            f_year, month
            UNION ALL
            SELECT
            f_year,
            month,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year ='$pre_fyear' 
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

            )
            GROUP BY
            f_year, month ORDER BY FIELD (month, 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec', 'jan', 'feb', 'mar')");


             // chart
            
          $prim_sumchart = $this->data['all_ind_sal'];
          
            $primData = [];
        
        // Create an associative array to store growth values for each month
           $monthGrowth = [];
        
        // Iterate through each record
        foreach ($prim_sumchart as $primary) {
            $month = $primary->month;
            
            // Find the corresponding sale for the current year
            $currentYearSale = $primary->sale;
        
            // Find the corresponding sale for the same month in the previous year
            $previousYearSale = null;
            foreach ($prim_sumchart as $record) {
                if ($record->f_year == $pre_fyear && $record->month == $month) {
                    $previousYearSale = $record->sale;
                    break;
                }
            }
            
                // Calculate growth if previous year sale is not null and not 0
                if ($previousYearSale !== null && $previousYearSale != 0) {
                    $growth = (($currentYearSale / $previousYearSale) - 1) * 100;
                } else {
                    $growth = null;
                }
            
                // Store the growth value for the month
                $monthGrowth[$month] = $growth;
            }
            
            // Format the data for output
            foreach ($monthGrowth as $month => $growth) {
                $primData[] = [
                    'month' => $month,
                    'growth' => $growth !== null ? number_format($growth, 0) . "%" : "0",
                ];
            }
            
            $this->data['prim_sumchart'] = json_encode($primData);

        }

        return view('globalmis.monthreport', $this->data);
    }   
        
        
             // zone Wise 
    public function Zonewise(Request $request)
    {
        
 
        $l_dbmon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 ");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 ");
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
        if ($zone != '' || $select_date != '' || $region != '') {

            if ($select_date != ''){ 
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
            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            // month wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
            f_year,
            zone,
            c_year,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$fy_year' $condition
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')

            )
            GROUP BY
            f_year, zone
            UNION ALL
            SELECT
            f_year,
            zone,
            c_year,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year ='$select_pre_year'  $condition
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

            )
            GROUP BY
            f_year, zone");

            // chart
            
              $prim_sumchart = $this->data['all_ind_sal'];
           $primData = [];
       // Iterate through each record to find growth for each zone
    foreach ($prim_sumchart as $primary) {
        $zone = $primary->zone;
    
        // Find the corresponding sale for the current year
        $currentYearSale = $primary->sale;
    
        // Find the corresponding sale for the same zone in the previous year
        $previousYearSale = null;
        foreach ($prim_sumchart as $record) {
            if ($record->f_year == $select_pre_year && $record->zone == $zone) {
                $previousYearSale = $record->sale;
                break;
            }
        }
    
        // Calculate growth if previous year sale is not null and not 0
        if ($previousYearSale !== null && $previousYearSale != 0) {
            $growth = (($currentYearSale / $previousYearSale) - 1) * 100;
        } else {
            $growth = null;
        }
    
        // Store the growth value for the zone only once
        if (!isset($monthGrowth[$zone])) {
            $monthGrowth[$zone] = $growth;
        }
    }
    
    // Format the data for output
    foreach ($monthGrowth as $zone => $growth) {
        $primData[] = [
            'zone' => $zone,
            'growth' => $growth !== null ? number_format($growth, 0) . "%" : "0",
        ];
    }
    
    $this->data['prim_sumchart'] = json_encode($primData);

        } else {
            //      default values

            // region wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
            f_year,
            zone,
            c_year,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$cur_fyear' 
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')

            )
            GROUP BY
            f_year, zone
            UNION ALL
            SELECT
            f_year,
            zone,
            c_year,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year ='$pre_fyear' 
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

            )
            GROUP BY
            f_year, zone");


             // chart
                
     $prim_sumchart = $this->data['all_ind_sal'];
    $primData = [];
       // Iterate through each record to find growth for each zone
    foreach ($prim_sumchart as $primary) {
        $zone = $primary->zone;
    
        // Find the corresponding sale for the current year
        $currentYearSale = $primary->sale;
    
        // Find the corresponding sale for the same zone in the previous year
        $previousYearSale = null;
        foreach ($prim_sumchart as $record) {
            if ($record->f_year == $pre_fyear && $record->zone == $zone) {
                $previousYearSale = $record->sale;
                break;
            }
        }
    
        // Calculate growth if previous year sale is not null and not 0
        if ($previousYearSale !== null && $previousYearSale != 0) {
            $growth = (($currentYearSale / $previousYearSale) - 1) * 100;
        } else {
            $growth = null;
        }
    
        // Store the growth value for the zone only once
        if (!isset($monthGrowth[$zone])) {
            $monthGrowth[$zone] = $growth;
        }
    }
    
    // Format the data for output
    foreach ($monthGrowth as $zone => $growth) {
        $primData[] = [
            'zone' => $zone,
            'growth' => $growth !== null ? number_format($growth, 0) . "%" : "0",
        ];
    }
    
    $this->data['prim_sumchart'] = json_encode($primData);

        }

        return view('globalmis.zonerpt', $this->data);
    }      
        
        
                  // region Wise 
    public function Regionwise(Request $request)
    {
        
 
        $l_dbmon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 ");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 ");
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
        if ($zone != '' || $select_date != '' || $region != '') {

            if ($select_date != ''){ 
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
            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            // month wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
            f_year,
            region,
            c_year,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$fy_year' $condition
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')

            )
            GROUP BY
            f_year, region
            UNION ALL
            SELECT
            f_year,
            region,
            c_year,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year ='$select_pre_year'  $condition
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

            )
            GROUP BY
            f_year, region");

             // chart
                
     $prim_sumchart = $this->data['all_ind_sal'];
     $primData = [];
       // Iterate through each record to find growth for each zone
    foreach ($prim_sumchart as $primary) {
        $zone = $primary->region;
    
        // Find the corresponding sale for the current year
        $currentYearSale = $primary->sale;
    
        // Find the corresponding sale for the same zone in the previous year
        $previousYearSale = null;
        foreach ($prim_sumchart as $record) {
            if ($record->f_year == $select_pre_year && $record->region == $zone) {
                $previousYearSale = $record->sale;
                break;
            }
        }
    
        // Calculate growth if previous year sale is not null and not 0
        if ($previousYearSale !== null && $previousYearSale != 0) {
            $growth = (($currentYearSale / $previousYearSale) - 1) * 100;
        } else {
            $growth = null;
        }
    
        // Store the growth value for the zone only once
        if (!isset($monthGrowth[$zone])) {
            $monthGrowth[$zone] = $growth;
        }
    }
    
    // Format the data for output
    foreach ($monthGrowth as $zone => $growth) {
        $primData[] = [
            'zone' => $zone,
            'growth' => $growth !== null ? number_format($growth, 0) . "%" : "0",
        ];
    }
    
    $this->data['prim_sumchart'] = json_encode($primData);

        } else {
            //      default values

            // region wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
            f_year,
            region,
            c_year,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$cur_fyear' 
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')

            )
            GROUP BY
            f_year, region
            UNION ALL
            SELECT
            f_year,
            region,
            c_year,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year ='$pre_fyear' 
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

            )
            GROUP BY
            f_year, region");


             // chart
                
     $prim_sumchart = $this->data['all_ind_sal'];
    $primData = [];
       // Iterate through each record to find growth for each zone
    foreach ($prim_sumchart as $primary) {
        $zone = $primary->region;
    
        // Find the corresponding sale for the current year
        $currentYearSale = $primary->sale;
    
        // Find the corresponding sale for the same zone in the previous year
        $previousYearSale = null;
        foreach ($prim_sumchart as $record) {
            if ($record->f_year == $pre_fyear && $record->region == $zone) {
                $previousYearSale = $record->sale;
                break;
            }
        }
    
        // Calculate growth if previous year sale is not null and not 0
        if ($previousYearSale !== null && $previousYearSale != 0) {
            $growth = (($currentYearSale / $previousYearSale) - 1) * 100;
        } else {
            $growth = null;
        }
    
        // Store the growth value for the zone only once
        if (!isset($monthGrowth[$zone])) {
            $monthGrowth[$zone] = $growth;
        }
    }
    
    // Format the data for output
    foreach ($monthGrowth as $zone => $growth) {
        $primData[] = [
            'zone' => $zone,
            'growth' => $growth !== null ? number_format($growth, 0) . "%" : "0",
        ];
    }
    
    $this->data['prim_sumchart'] = json_encode($primData);

        }

        return view('globalmis.regionrpt', $this->data);
    }     
      
      
      
      
          public function Distributorwise(Request $request)
    {
        
 
        $l_dbmon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 ");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 ");
        $region = $request->input('region');
        $this->data['states'] = \DB::select("SELECT DISTINCT state as state from `sd_primarydataupload_t`");
        $state = $request->input('state');
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
        if ($zone != '' || $select_date != '' || $region != '' || $state!='') {

            if ($select_date != ''){ 
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
            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($state != '') {
                $condition = "AND state='$state'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            // month wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
            f_year,
            state,stockist_dist_name as name,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$fy_year' $condition
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')

            )
            GROUP BY
            f_year,  state,stockist_dist_name HAVING sale !='0'
            UNION ALL
            SELECT
            f_year,
            state,stockist_dist_name as name,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year ='$select_pre_year'  $condition
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

            )
            GROUP BY
            f_year,state,stockist_dist_name HAVING sale !='0'");

           // product wise
            
            $this->data['all_ind_sal_product'] = \DB::select("SELECT
            f_year,
            product_name as product,stockist_dist_name as name,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$fy_year' $condition
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')

            )
            GROUP BY
            f_year,product_name,stockist_dist_name HAVING sale !='0'
            
            UNION ALL
            SELECT
            f_year,
             product_name as product,stockist_dist_name as name,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year ='$select_pre_year' $condition
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

            )
            GROUP BY
            f_year,product_name,stockist_dist_name HAVING sale !='0'");
            
        } else {
            //      default values

            // dist wise
            
            $this->data['all_ind_sal'] = \DB::select("SELECT
            f_year,
            state,stockist_dist_name as name,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$cur_fyear' 
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')

            )
            GROUP BY
            f_year,state,stockist_dist_name HAVING sale !='0'
            
            UNION ALL
            SELECT
            f_year,
             state,stockist_dist_name as name,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year ='$pre_fyear' 
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

            )
            GROUP BY
            f_year,state,stockist_dist_name HAVING sale !='0'");


            // product wise
            
            $this->data['all_ind_sal_product'] = \DB::select("SELECT
            f_year,
            product_name as product,stockist_dist_name as name,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$cur_fyear' 
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')

            )
            GROUP BY
            f_year,product_name,stockist_dist_name HAVING sale !='0'
            
            UNION ALL
            SELECT
            f_year,
             product_name as product,stockist_dist_name as name,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year ='$pre_fyear' 
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

            )
            GROUP BY
            f_year,product_name,stockist_dist_name HAVING sale !='0'");

        }

        return view('globalmis.distrpt', $this->data);
    }  
      
      
      // month wise trend
      public function Salestrend(Request $request)
    {
        
 
        $l_dbmon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 ");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 ");
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
        if ($zone != '' || $select_date != '' || $region != '') {

            if ($select_date != ''){ 
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
            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            // zone wise 
            $this->data['month_sample'] = \DB::select("SELECT
           f_year,zone,
           region,
           c_year,month_y,
           ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
           FROM
           sd_primarydataupload_t
           WHERE 
           f_year='$fy_year' $condition
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
           GROUP BY
           month_y, f_year,zone,region HAVING  sale !='0'
           ORDER BY
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
           
           
        } else {

     
            $this->data['month_sample'] = \DB::select("SELECT
           f_year,zone,
           region,
           c_year,month_y,
           ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 			END), 0) as sale
           FROM
           sd_primarydataupload_t
           WHERE 
           f_year='$cur_fyear'
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           month_y, f_year,zone,region HAVING  sale !='0'
           ORDER BY
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        

        }

        return view('globalmis.monthtrend', $this->data);
    }  
    
    
    // distributor wise trend
    
          public function Distrend(Request $request)
    {
        
 
        $l_dbmon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 ");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 ");
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
        if ($zone != '' || $select_date != '' || $region != '') {

            if ($select_date != ''){ 
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
            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            // zone wise 
            $this->data['month_sample'] = \DB::select("SELECT
           f_year,zone,
           stockist_dist_name as name,
           c_year,month_y,
           ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
           FROM
           sd_primarydataupload_t
           WHERE 
           f_year='$fy_year' $condition
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
           GROUP BY
           month_y,stockist_dist_name,zone, f_year HAVING  sale !='0'
           ORDER BY
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
           
           
        } else {

     
            $this->data['month_sample'] = \DB::select("SELECT
           f_year,zone,
           stockist_dist_name as name,
           c_year,month_y,
           ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
           FROM
           sd_primarydataupload_t
           WHERE 
           f_year='$cur_fyear'
           AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
           GROUP BY
           month_y,stockist_dist_name,zone, f_year HAVING  sale !='0'
           ORDER BY
           STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        

        }

        return view('globalmis.distrend', $this->data);
    } 
    
    
    
       public function Productwise(Request $request)
    {
        
 
        $l_dbmon = \DB::select("SELECT  month FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t`");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t`");
        $region = $request->input('region');
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_primarydataupload_t` where division!='-'");
        $division = $request->input('division');
        $this->data['states'] = \DB::select("SELECT DISTINCT state as state from `sd_primarydataupload_t`");
        $state = $request->input('state');
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
        if ($zone != '' || $select_date != '' || $region != '' || $division != '') {

            if ($select_date != ''){ 
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

            // month wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
            f_year,
            division,product_name as name,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$fy_year' $condition
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')

            )
            GROUP BY
            f_year, division,product_name HAVING sale !='0'
            UNION ALL
            SELECT
            f_year,
            division,product_name as name,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year ='$select_pre_year'  $condition
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

            )
            GROUP BY
            f_year,division,product_name HAVING sale !='0'");

            
        } else {
            //      default values

            // dist wise
            
            $this->data['all_ind_sal'] = \DB::select("SELECT
            f_year,
            division,product_name as name,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$cur_fyear' 
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')

            )
            GROUP BY
            f_year,division,product_name HAVING sale !='0'
            
            UNION ALL
            SELECT
            f_year,
            division,product_name as name,
            ROUND(SUM(CASE WHEN (data_type = 'Sales' OR data_type = 'Oversease') THEN asseesable_value ELSE 0 END), 0) as sale
            FROM
            sd_primarydataupload_t
            WHERE
            f_year ='$pre_fyear' 
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

            )
            GROUP BY
            f_year,division,product_name HAVING sale !='0'");


        }

        return view('globalmis.productwise', $this->data);
    }  
      
    
        
    }