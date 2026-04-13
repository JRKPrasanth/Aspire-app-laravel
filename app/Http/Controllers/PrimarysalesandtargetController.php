<?php

namespace App\Http\Controllers;

use App\Misviewers;
use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;

class PrimarysalesandtargetController extends Controller
{

		public function __construct(){
        
		$this->pageModule="primarysalesandtargetindex";	
		$this->data=array();
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();
       
    }
	
	    public function index(Request $request)
    {
	   // restrict illegal menu entry purpose - RATHI R

        $url = "primarysalesandtargetindex";
        
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
			
	 return view('primarysales&target.primdata', $this->data);
	
	}
	
    // ZoneWise Report
    public function ZoneWise(Request $request)
    {

        $wh = '';
        // hireachy query
        $depart = \Session::get('groupname');
        $emp_id = \Session::get('emp_id');
        $this->data['groupname'] = \Session::get('groupname');

        // for marketing persons login reason

        $em_name = \DB::select("SELECT first_name from hr_employee_t where employee_id='$emp_id'");
        $emp_name = $em_name[0]->first_name;

        // RSM region compare purpose
        $em_reg = \DB::select("SELECT job_title from hr_employee_t where employee_id='$emp_id'");
        $emp_reg = $em_reg[0]->job_title;

        // for prakash swaminathan access all mis purpose
        if ($depart == "14" && $emp_id != '121') {

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

            if ($emp_id == '94') {

                $wh = " AND sd_primarydataupload_t.state IN ('ANDHRA PRADESH')";
            }
        }

        // for marketing persons login reason end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

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
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['states'] = \DB::select("SELECT DISTINCT state from `sd_primarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $state = $request->input('state');
        $this->data['areas'] = \DB::select("SELECT DISTINCT area from `sd_primarydataupload_t` where area NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $area = $request->input('area');
        $this->data['categorys'] = \DB::select("SELECT DISTINCT division from `sd_primarydataupload_t` where division NOT IN('EXPORT','LOCAL','GOVT SUPPLY','0') $wh");
        $category = $request->input('category');
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
        if ($zone != '' || $select_date != '' || $region != '' || $state != '' || $area != '' || $category != '') {

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

            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
             if ($category != '') {
                $condition = "AND division='$category'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($area != '') {
                $condition = "AND area='$area'";
            }
            if ($state != '') {
                $condition = "AND state ='$state'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            // month wise
            $this->data['month_wise'] = \DB::select("SELECT
    f_year,
    month,
    c_year,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
    FROM
        sd_primarydataupload_t
    WHERE
        f_year='$fy_year' $condition $wh $notin
        AND (
            STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')

        )
    GROUP BY
        f_year, month
        UNION ALL
        SELECT
        f_year,
        month,
        c_year,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
    FROM
        sd_primarydataupload_t
    WHERE
        f_year='$select_pre_year' $condition $wh $notin
        AND (
            STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

        )
    GROUP BY
        f_year, month ORDER BY c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");

            // chart
            $this->data['prim_sumchart_month'] = \DB::select("SELECT
    f_year,
    month,
    c_year,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
    FROM
        sd_primarydataupload_t
    WHERE
        f_year='$fy_year' $condition $wh $notin
        AND (
            STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')

        )
    GROUP BY
        f_year, month ORDER BY c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");

            $this->data['zone_sumchart_month'] = \DB::select("SELECT
        f_year,
        month,
        c_year,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
    FROM
        sd_primarydataupload_t
    WHERE
        f_year='$select_pre_year' $condition $wh $notin
        AND (
            STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

        )
    GROUP BY
        f_year, month ORDER BY c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");


            $month_chart = $this->data['prim_sumchart_month'];
            $month_pre_chart = $this->data['zone_sumchart_month'];

            $primData = [];

            foreach ($month_chart as $primary) {
                foreach ($month_pre_chart as $secondary) {

                    if ($primary->month == $secondary->month) {

                        $growth = $secondary->sale != 0 ? (($primary->sale / $secondary->sale - 1) * 100) : null;
                        $achivement = $primary->target != 0 ? (($primary->sale / $primary->target) * 100) : null;

                        $primData[] = [
                            'month' => $primary->month,
                            'growth' => $growth !== null ? number_format($growth, 0) . "%" : "0",
                            'achive' => $achivement !== null ? number_format($achivement, 0) . "%" : "0",

                        ];

                        break;
                    }
                }
            }
            $this->data['month_chart'] = json_encode($primData);

            // zone wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
    f_year,
    zone,
    c_year,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
 FROM
    sd_primarydataupload_t
 WHERE
    f_year='$fy_year' $condition $wh $notin
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
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
    sd_primarydataupload_t
WHERE
    f_year='$select_pre_year' $condition $wh $notin
    AND (
        STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

    )
GROUP BY
    f_year, zone");

            // chart
            $this->data['prim_sumchart'] = \DB::select("SELECT
    f_year,
    zone,
    c_year,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
    sd_primarydataupload_t
WHERE
    f_year='$fy_year' $condition $wh $notin
    AND (
        STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')

    )
GROUP BY
    f_year, zone");

            $this->data['zone_sumchart'] = \DB::select("SELECT
    f_year,
    zone,
    c_year,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
    sd_primarydataupload_t
WHERE
    f_year='$select_pre_year' $condition $wh $notin
    AND (
        STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

    )
GROUP BY
    f_year, zone");


            $prim_sumchart = $this->data['prim_sumchart'];
            $zone_sumchart = $this->data['zone_sumchart'];

            $primData = [];

            foreach ($prim_sumchart as $primary) {
                foreach ($zone_sumchart as $secondary) {

                    if ($primary->zone == $secondary->zone) {

                        $growth = $secondary->sale != 0 ? (($primary->sale / $secondary->sale - 1) * 100) : null;
                        $achivement = $primary->target != 0 ? (($primary->sale / $primary->target) * 100) : null;

                        $primData[] = [
                            'zone' => $primary->zone,
                            'growth' => $growth !== null ? number_format($growth, 0) . "%" : "0",
                            'achive' => $achivement !== null ? number_format($achivement, 0) . "%" : "0",

                        ];

                        break;
                    }
                }
            }
            $this->data['prim_sumchart'] = json_encode($primData);
        } else {
            //      default values

            // month wise
            $this->data['month_wise'] = \DB::select("SELECT
    f_year,
    month,
    c_year,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
    sd_primarydataupload_t
WHERE
    f_year='$cur_fyear' $wh $notin

GROUP BY
    f_year, month
     UNION ALL
    SELECT
    f_year,
    month,
    c_year,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
    sd_primarydataupload_t
WHERE
    f_year='$pre_fyear' $wh $notin

GROUP BY
    f_year, month ORDER BY  STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");


            // chart
            $this->data['prim_sumchart_month'] = \DB::select("SELECT
    f_year,
    month,
    c_year,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
    sd_primarydataupload_t
WHERE
    f_year='$cur_fyear' $wh $notin
    AND (
        STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')

    )
GROUP BY
    f_year, month ORDER BY c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");

            $this->data['zone_sumchart_month'] = \DB::select("SELECT
    f_year,
    month,
    c_year,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
    sd_primarydataupload_t
WHERE
    f_year='$pre_fyear' $wh $notin
    AND (
        STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

    )
GROUP BY
    f_year, month ORDER BY c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");


            $month_sumchart = $this->data['prim_sumchart_month'];
            $month_pre_chart = $this->data['zone_sumchart_month'];

            $primData = [];

            foreach ($month_sumchart as $primary) {
                foreach ($month_pre_chart as $secondary) {

                    if ($primary->month == $secondary->month) {

                        $growth = $secondary->sale != 0 ? (($primary->sale / $secondary->sale - 1) * 100) : null;
                        $achivement = $primary->target != 0 ? (($primary->sale / $primary->target) * 100) : null;

                        $primData[] = [
                            'month' => $primary->month,
                            'growth' => $growth !== null ? number_format($growth, 0) . "%" : "0",
                            'achive' => $achivement !== null ? number_format($achivement, 0) . "%" : "0",

                        ];

                        break;
                    }
                }
            }
            $this->data['month_chart'] = json_encode($primData);

            // zone wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
    f_year,
    zone,
    c_year,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
    sd_primarydataupload_t
WHERE
    f_year='$cur_fyear' $wh $notin
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
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
    sd_primarydataupload_t
WHERE
    f_year='$pre_fyear' $wh $notin
    AND (
        STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

    )
GROUP BY
    f_year, zone");


            // chart
            $this->data['prim_sumchart'] = \DB::select("SELECT
    f_year,
    zone,
    c_year,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
    sd_primarydataupload_t
WHERE
    f_year='$cur_fyear' $wh $notin
    AND (
        STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')

    )
GROUP BY
    f_year, zone");

            $this->data['zone_sumchart'] = \DB::select("SELECT
    f_year,
    zone,
    c_year,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
    sd_primarydataupload_t
WHERE
    f_year='$pre_fyear' $wh $notin
    AND (
        STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

    )
GROUP BY
    f_year, zone");


            $prim_sumchart = $this->data['prim_sumchart'];
            $zone_sumchart = $this->data['zone_sumchart'];

            $primData = [];

            foreach ($prim_sumchart as $primary) {
                foreach ($zone_sumchart as $secondary) {

                    if ($primary->zone == $secondary->zone) {

                        $growth = $secondary->sale != 0 ? (($primary->sale / $secondary->sale - 1) * 100) : null;
                        $achivement = $primary->target != 0 ? (($primary->sale / $primary->target) * 100) : null;

                        $primData[] = [
                            'zone' => $primary->zone,
                            'growth' => $growth !== null ? number_format($growth, 0) . "%" : "0",
                            'achive' => $achivement !== null ? number_format($achivement, 0) . "%" : "0",

                        ];

                        break;
                    }
                }
            }
            $this->data['prim_sumchart'] = json_encode($primData);
        }

        return view('primarysales&target.zone', $this->data);
    }


    // Region Wise 
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

        if ($depart == "14" && $emp_id != '121') {

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

            if ($emp_id == '94') {

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
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
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
            $this->data['all_ind_sal'] = \DB::select("SELECT
        f_year,
        region,
        c_year,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
        FROM
        sd_primarydataupload_t
        WHERE
        f_year='$fy_year' $condition $wh $notin
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
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
        FROM
        sd_primarydataupload_t
        WHERE
        f_year='$select_pre_year' $condition $wh $notin
        AND (
            STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

        )
        GROUP BY
        f_year, region");

            // chart
            $this->data['prim_sumchart'] = \DB::select("SELECT
        f_year,
        region,
        c_year,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
        FROM
        sd_primarydataupload_t
        WHERE
        f_year='$fy_year' $condition $wh $notin
        AND (
            STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')

        )
        GROUP BY
        f_year, region");

            $this->data['zone_sumchart'] = \DB::select("SELECT
        f_year,
        region,
        c_year,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
        FROM
        sd_primarydataupload_t
        WHERE
        f_year='$select_pre_year' $condition $wh $notin
        AND (
            STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

        )
        GROUP BY
        f_year, region");


            $prim_sumchart = $this->data['prim_sumchart'];
            $zone_sumchart = $this->data['zone_sumchart'];

            $primData = [];

            foreach ($prim_sumchart as $primary) {
                foreach ($zone_sumchart as $secondary) {

                    if ($primary->region == $secondary->region) {

                        $growth = $secondary->sale != 0 ? (($primary->sale / $secondary->sale - 1) * 100) : null;
                        $achivement = $primary->target != 0 ? (($primary->sale / $primary->target) * 100) : null;

                        $primData[] = [
                            'region' => $primary->region,
                            'growth' => $growth !== null ? number_format($growth, 0) . "%" : "0",
                            'achive' => $achivement !== null ? number_format($achivement, 0) . "%" : "0",

                        ];

                        break;
                    }
                }
            }
            $this->data['prim_sumchart'] = json_encode($primData);
        } else {
            //      default values

            // region wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
            f_year,
            region,
            c_year,
            ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
            ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$cur_fyear' $wh  $notin
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
            ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
            ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$pre_fyear' $wh $notin
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

            )
            GROUP BY
            f_year, region");


            // chart
            $this->data['prim_sumchart'] = \DB::select("SELECT
            f_year,
            region,
            c_year,
            ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
            ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$cur_fyear' $wh $notin
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')

            )
            GROUP BY
            f_year, region");

            $this->data['zone_sumchart'] = \DB::select("SELECT
            f_year,
            region,
            c_year,
            ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
            ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
            FROM
            sd_primarydataupload_t
            WHERE
            f_year='$pre_fyear' $wh $notin
            AND (
                STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

            )
            GROUP BY
            f_year, region");


            $prim_sumchart = $this->data['prim_sumchart'];
            $zone_sumchart = $this->data['zone_sumchart'];

            $primData = [];

            foreach ($prim_sumchart as $primary) {
                foreach ($zone_sumchart as $secondary) {

                    if ($primary->region == $secondary->region) {

                        $growth = $secondary->sale != 0 ? (($primary->sale / $secondary->sale - 1) * 100) : null;
                        $achivement = $primary->target != 0 ? (($primary->sale / $primary->target) * 100) : null;

                        $primData[] = [
                            'region' => $primary->region,
                            'growth' => $growth !== null ? number_format($growth, 0) . "%" : "0",
                            'achive' => $achivement !== null ? number_format($achivement, 0) . "%" : "0",

                        ];

                        break;
                    }
                }
            }
            $this->data['prim_sumchart'] = json_encode($primData);
        }

        return view('primarysales&target.region', $this->data);
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

        if ($depart == "14" && $emp_id != '121') {

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

            if ($emp_id == '94') {

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
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
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
            $this->data['all_ind_sal'] = \DB::select("SELECT
    f_year,region,state as state,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
    FROM
    sd_primarydataupload_t
    WHERE
    f_year='$fy_year' $condition $wh $notin
    AND (
        STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
    
    )
    GROUP BY
    f_year,region,state HAVING (sale !='0' OR target !='0')
     UNION ALL
    SELECT
    f_year,region,state as state,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
    FROM
    sd_primarydataupload_t
    WHERE
    f_year='$select_pre_year' $condition $wh $notin
    AND (
        STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
    
    )
    GROUP BY
    f_year, region,state HAVING (sale !='0' OR target !='0')");

            // chart
            $this->data['prim_sumchart'] = \DB::select("SELECT
    f_year,region,state as state,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
    FROM
    sd_primarydataupload_t
    WHERE
    f_year='$fy_year' $condition $wh $notin
    AND (
        STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
    
    )
    GROUP BY
    f_year, region,state HAVING (sale !='0' OR target !='0')");

            $this->data['zone_sumchart'] = \DB::select("SELECT
    f_year,region,state as state,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
    FROM
    sd_primarydataupload_t
    WHERE
    f_year='$select_pre_year' $condition $wh $notin
    AND (
        STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
    
    )
    GROUP BY
    f_year, region,state HAVING (sale !='0' OR target !='0')");


            $prim_sumchart = $this->data['prim_sumchart'];
            $zone_sumchart = $this->data['zone_sumchart'];

            $primData = [];

            foreach ($prim_sumchart as $primary) {
                foreach ($zone_sumchart as $secondary) {

                    if ($primary->state == $secondary->state) {

                        $growth = $secondary->sale != 0 ? (($primary->sale / $secondary->sale - 1) * 100) : null;
                        $achivement = $primary->target != 0 ? (($primary->sale / $primary->target) * 100) : null;

                        $primData[] = [
                            'state' => $primary->state,
                            'growth' => $growth !== null ? number_format($growth, 0) . "%" : "0",
                            'achive' => $achivement !== null ? number_format($achivement, 0) . "%" : "0",

                        ];

                        break;
                    }
                }
            }
            $this->data['prim_sumchart'] = json_encode($primData);
        } else {
            //      default values

            // state wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
        f_year,region,state as state,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
        FROM
        sd_primarydataupload_t
        WHERE
        f_year='$cur_fyear' $wh $notin
        AND (
            STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')

        )
        GROUP BY
        f_year, region, state HAVING (sale !='0' OR target !='0')
        UNION ALL
        SELECT
        f_year,region,state as state,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
        FROM
        sd_primarydataupload_t
        WHERE
        f_year='$pre_fyear' $wh  $notin
        AND (
            STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

        )
        GROUP BY
        f_year, region,state HAVING (sale !='0' OR target !='0')");


            // chart
            $this->data['prim_sumchart'] = \DB::select("SELECT
    f_year,region,state as state,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
    FROM
    sd_primarydataupload_t
    WHERE
    f_year='$cur_fyear' $wh $notin
    AND (
        STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')

    )
    GROUP BY
    f_year, region, state HAVING (sale !='0' OR target !='0')");

            $this->data['zone_sumchart'] = \DB::select("SELECT
    f_year,region,state as state,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
    FROM
    sd_primarydataupload_t
    WHERE
    f_year='$pre_fyear' $wh $notin
    AND (
        STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

    )
    GROUP BY
    f_year, region, state HAVING (sale !='0' OR target !='0')");


            $prim_sumchart = $this->data['prim_sumchart'];
            $zone_sumchart = $this->data['zone_sumchart'];

            $primData = [];

            foreach ($prim_sumchart as $primary) {
                foreach ($zone_sumchart as $secondary) {

                    if ($primary->state == $secondary->state) {

                        $growth = $secondary->sale != 0 ? (($primary->sale / $secondary->sale - 1) * 100) : null;
                        $achivement = $primary->target != 0 ? (($primary->sale / $primary->target) * 100) : null;

                        $primData[] = [
                            'state' => $primary->state,
                            'growth' => $growth !== null ? number_format($growth, 0) . "%" : "0",
                            'achive' => $achivement !== null ? number_format($achivement, 0) . "%" : "0",

                        ];

                        break;
                    }
                }
            }
            $this->data['prim_sumchart'] = json_encode($primData);
        }

        return view('primarysales&target.state', $this->data);
    }

    // area wise

    public function AreaWise(Request $request)
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

        if ($depart == "14" && $emp_id != '121') {

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

            if ($emp_id == '94') {

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
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
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
            $this->data['all_ind_sal'] = \DB::select("SELECT
        f_year,area,state,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
        FROM
        sd_primarydataupload_t
        WHERE
        f_year='$fy_year' $condition $wh $notin
        AND (
            STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d') 
        
        )
        GROUP BY
        area,state HAVING (sale !='0' OR target !='0')
         UNION ALL
        SELECT
        f_year,area,state,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
        FROM
        sd_primarydataupload_t
        WHERE
        f_year='$select_pre_year' $condition $wh  $notin
        AND (
            STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
        
        )
        GROUP BY
        f_year, area,state HAVING (sale !='0' OR target !='0')");

            // chart
            $this->data['prim_sumchart'] = \DB::select("SELECT
f_year,area,state,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$fy_year' $condition $wh $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')

)
GROUP BY
f_year, area,state HAVING (sale !='0' OR target !='0')");

            $this->data['zone_sumchart'] = \DB::select("SELECT
f_year,area,state,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$select_pre_year' $condition $wh $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

)
GROUP BY
f_year, area,state HAVING (sale !='0' OR target !='0')");


            $prim_sumchart = $this->data['prim_sumchart'];
            $zone_sumchart = $this->data['zone_sumchart'];

            $primData = [];

            foreach ($prim_sumchart as $primary) {
                foreach ($zone_sumchart as $secondary) {

                    if ($primary->area == $secondary->area) {

                        $growth = $secondary->sale != 0 ? (($primary->sale / $secondary->sale - 1) * 100) : null;
                        $achivement = $primary->target != 0 ? (($primary->sale / $primary->target) * 100) : null;

                        $primData[] = [
                            'state' => $primary->area,
                            'growth' => $growth !== null ? number_format($growth, 0) . "%" : "0",
                            'achive' => $achivement !== null ? number_format($achivement, 0) . "%" : "0",

                        ];

                        break;
                    }
                }
            }
            $this->data['prim_sumchart'] = json_encode($primData);
        } else {
            //      default values

            // state wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
f_year,area,state,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$cur_fyear' $wh  $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')

)
GROUP BY
f_year, area, state HAVING (sale !='0' OR target !='0')
 UNION ALL
SELECT
f_year,area,state,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$pre_fyear' $wh $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

)
GROUP BY
f_year, area,state HAVING (sale !='0' OR target !='0')");


            // chart
            $this->data['prim_sumchart'] = \DB::select("SELECT
f_year,area,state,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$cur_fyear' $wh $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')

)
GROUP BY
f_year, area, state HAVING (sale !='0' OR target !='0')");

            $this->data['zone_sumchart'] = \DB::select("SELECT
f_year,area,state,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$pre_fyear' $wh $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

)
GROUP BY
f_year, area, state HAVING (sale !='0' OR target !='0')");


            $prim_sumchart = $this->data['prim_sumchart'];
            $zone_sumchart = $this->data['zone_sumchart'];

            $primData = [];

            foreach ($prim_sumchart as $primary) {
                foreach ($zone_sumchart as $secondary) {

                    if ($primary->area == $secondary->area) {

                        $growth = $secondary->sale != 0 ? (($primary->sale / $secondary->sale - 1) * 100) : null;
                        $achivement = $primary->target != 0 ? (($primary->sale / $primary->target) * 100) : null;

                        $primData[] = [
                            'state' => $primary->area,
                            'growth' => $growth !== null ? number_format($growth, 0) . "%" : "0",
                            'achive' => $achivement !== null ? number_format($achivement, 0) . "%" : "0",

                        ];

                        break;
                    }
                }
            }
            $this->data['prim_sumchart'] = json_encode($primData);
        }

        return view('primarysales&target.area', $this->data);
    }

    // DistributorWise Report
    public function DistributorWise(Request $request)
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

        if ($depart == "14" && $emp_id != '121') {

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

            if ($emp_id == '94') {

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
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['areas'] = \DB::select("SELECT DISTINCT area from `sd_primarydataupload_t` where area NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $area = $request->input('area');
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
        if ($zone != '' || $select_date != '' || $region != '' || $area !='') {

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
            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($area != '') {
                $condition = "AND area='$area'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            // distributor wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
f_year,stockist_dist_name as name,state,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$fy_year' $condition $wh $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')

)
GROUP BY
state,stockist_dist_name,f_year HAVING (sale !='0' OR target !='0')
 UNION ALL
SELECT
f_year,stockist_dist_name as name,state,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$select_pre_year' $condition $wh AND name_type='DISTRIBUTORS' $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

)
GROUP BY
state,stockist_dist_name,f_year HAVING (sale !='0' OR target !='0')");
        } else {
            //      default values

            // state wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
      f_year,stockist_dist_name as name,state,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$cur_fyear' $wh  AND name_type='DISTRIBUTORS' $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')

)
GROUP BY
state,stockist_dist_name,f_year HAVING (sale !='0' OR target !='0')
 UNION ALL
SELECT
      f_year,stockist_dist_name as name,state,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$pre_fyear' $wh $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

)
GROUP BY
state,stockist_dist_name,f_year HAVING (sale !='0' OR target !='0')");
        }

        return view('primarysales&target.distributor', $this->data);
    }

    // Product Wise Report
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

        if ($depart == "14" && $emp_id != '121') {

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

            if ($emp_id == '94') {

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
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_secondarydataupload_t` where division!='-'");
        $division = $request->input('division');
        $this->data['states'] = \DB::select("SELECT DISTINCT state from `sd_primarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $state = $request->input('state');
        $this->data['distributor_name'] = \DB::select("SELECT DISTINCT stockist_dist_name AS name from `sd_primarydataupload_t` where data_type = 'Sales' $wh ");
        $dist_name = $request->input('dist_name');
        $this->data['areas'] = \DB::select("SELECT DISTINCT area from `sd_primarydataupload_t` where area NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $area = $request->input('area');
     
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
        if ($zone != '' || $select_date != '' || $region != '' || $division != '' || $state != '' || $dist_name != '' || $area != '') {

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
            if ($area != '') {
              $condition = "AND area='$area'";
            }
            if ($dist_name != '') {
                $condition = "AND stockist_dist_name ='$dist_name'";
            }
            if ($state != '' && $region != '') {
                $condition = "AND state='$state' AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }
            if ($state != '' && $region != '' && $division != '') {
                $condition = "AND state='$state' AND region='$region' AND division='$division'";
            }
            if ($zone != '' && $region != '' && $division != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND division='$division'";
            }

            if ($zone != '' && $region != '' && $state != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND state='$state'";
            }

            // distributor wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
              f_year,division,sfg_product_name as product_name,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
        FROM
        sd_primarydataupload_t
        WHERE
        f_year='$fy_year' $condition $wh $notin
        AND (
            STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
        
        )
        GROUP BY
        division,sfg_product_name,f_year HAVING (sale !='0' OR target !='0')
         UNION ALL
        SELECT
              f_year,division,sfg_product_name as product_name,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
        FROM
        sd_primarydataupload_t
        WHERE
        f_year='$select_pre_year' $condition $wh $notin
        AND (
            STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
        
        )
        GROUP BY
        division,sfg_product_name,f_year HAVING (sale !='0' OR target !='0')");
        } else {
            //      default values

            // state wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
            f_year,division,sfg_product_name as product_name,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$cur_fyear' $wh  $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')

)
GROUP BY
division,sfg_product_name,f_year HAVING (sale !='0' OR target !='0')
 UNION ALL
SELECT
            f_year,division,sfg_product_name as product_name,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$pre_fyear' $wh $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

)
GROUP BY
division,sfg_product_name,f_year HAVING (sale !='0' OR target !='0')");
        }

        return view('primarysales&target.product', $this->data);
    }

    // Product Pack Wise Report
    public function ProductpackWise(Request $request)
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

        if ($depart == "14" && $emp_id != '121') {

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

            if ($emp_id == '94') {

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
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_secondarydataupload_t` where division!='-'");
        $division = $request->input('division');
        $this->data['states'] = \DB::select("SELECT DISTINCT state from `sd_primarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $state = $request->input('state');
        $this->data['distributor_name'] = \DB::select("SELECT DISTINCT stockist_dist_name AS name from `sd_primarydataupload_t` where data_type = 'Sales' $wh ");
        $dist_name = $request->input('dist_name');
        $this->data['areas'] = \DB::select("SELECT DISTINCT area from `sd_primarydataupload_t` where area NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $area = $request->input('area');
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
        if ($zone != '' || $select_date != '' || $region != '' || $division != '' || $state != '' || $dist_name != '' || $area != '') {

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
            if ($area != '') {
                $condition = "AND area ='$area'";
            }
            if ($dist_name != '') {
                $condition = "AND stockist_dist_name ='$dist_name'";
            }
            if ($state != '' && $region != '') {
                $condition = "AND state='$state' AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }
            if ($state != '' && $region != '' && $division != '') {
                $condition = "AND state='$state' AND region='$region' AND division='$division'";
            }
            if ($zone != '' && $region != '' && $division != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND division='$division'";
            }

            if ($zone != '' && $region != '' && $state != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND state='$state'";
            }
            // distributor wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
      f_year,division,	product_form_change as product_name,packing_qty,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN sales_free_unit ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$select_target' THEN sales_free_unit ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$fy_year' $condition $wh $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')

)
GROUP BY
division,product_form_change,packing_qty,f_year HAVING (sale !='0' OR target !='0')
 UNION ALL
SELECT
      f_year,division,product_form_change as product_name,packing_qty,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN sales_free_unit ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$select_target' THEN sales_free_unit ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$select_pre_year' $condition $wh $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

)
GROUP BY
division,product_form_change,packing_qty,f_year HAVING (sale !='0' OR target !='0')");
        } else {
            //      default values

            // pack wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
            f_year,division,product_form_change as product_name,packing_qty,
    ROUND(SUM(CASE WHEN data_type = 'Sales' THEN sales_free_unit ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN sales_free_unit ELSE 0 END), 0) as target
    FROM
    sd_primarydataupload_t
    WHERE
    f_year='$cur_fyear' $wh $notin
    AND (
        STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
    
    )
    GROUP BY
    division,product_form_change,packing_qty,f_year HAVING (sale !='0' OR target !='0')
    
 UNION ALL
SELECT
            f_year,division,product_form_change as product_name,packing_qty,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN sales_free_unit ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN sales_free_unit ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$pre_fyear' $wh $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

)
GROUP BY
division,product_form_change,packing_qty,f_year HAVING (sale !='0' OR target !='0')");

            //  dd($this->data['all_ind_sal']);

        }

        return view('primarysales&target.propack', $this->data);
    }

    // Plan and Shortage Report

    public function PlanandShort(Request $request)
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

        if ($depart == "14" && $emp_id != '121') {

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

            if ($emp_id == '94') {

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
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_secondarydataupload_t` where division!='-'");
        $division = $request->input('division');
        $this->data['states'] = \DB::select("SELECT DISTINCT state from `sd_primarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
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
        if ($zone != '' || $select_date != '' || $region != '' || $division != '' || $state!='') {

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
            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
             if ($state != '') {
                 $condition = "AND state ='$state'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }
            if ($division != '') {
                $condition = "AND division='$division'";
            }
            // distributor wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
           stockist_dist_name as name,state,f_year,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$fy_year' $condition $wh $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')

)
GROUP BY
state,stockist_dist_name,f_year HAVING (sale!='0' or target!='0')
 UNION ALL
SELECT
           stockist_dist_name as name,state,f_year,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$select_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$select_pre_year' $condition $wh $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

)
GROUP BY
state,stockist_dist_name,f_year HAVING (sale!='0' or target!='0')");
        } else {
            //      default values

            // state wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
                 stockist_dist_name as name,state,f_year,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$cur_fyear' $wh $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')

)
GROUP BY
state,stockist_dist_name,f_year HAVING (sale!='0' or target!='0')
 UNION ALL
SELECT
                 stockist_dist_name as name,state,f_year,
ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale,
ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN asseesable_value ELSE 0 END), 0) as target
FROM
sd_primarydataupload_t
WHERE
f_year='$pre_fyear' $wh $notin
AND (
    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

)
GROUP BY
state,stockist_dist_name,f_year HAVING (sale!='0' or target!='0')");
        }

        return view('primarysales&target.planwithshort', $this->data);
    }

    // Month Achivement

    public function Monthachivement(Request $request)
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

        if ($depart == "14" && $emp_id != '121') {

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

            if ($emp_id == '94') {

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
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_y = $l_dbyear[0]->c_year;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        //dd( $this->data['last_update']);
        $this->data['states'] = \DB::select("SELECT DISTINCT state from `sd_primarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $state = $request->input('state');
        $this->data['areas'] = \DB::select("SELECT DISTINCT area from `sd_primarydataupload_t` where area NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $area = $request->input('area');
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');

        $l_dbmon = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month_y;
        $month_y = addslashes($l_dbmon[0]->month_y);
        // show next month target purpose
        list($month, $year) = explode("'", $month_y);

        // Create a DateTime object using the parsed month and year
        $month_year_str = sprintf("01-%s-20%s", $month, $year); // 
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
        
       // dd($tar_month);
        $this->data['nxt_target'] =   $def_target_mon;
        // show next month target purpose end 
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

        $select_date = $request->input('date_select');
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('m', strtotime($select_date));
        $select_Mon = date('M', strtotime($select_date));
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;
        $selected_month = date('m', strtotime($select_date));
        $next_mon = $selected_month + 1;
        $date_next = $date_string = "$select_year-$next_mon-01";
        $next_month = date('M', strtotime($date_next));

        $select_pre_year = $pre_year . '-' . substr($select_year, -2);

        $this->data['select_year'] = $last_y;

        $twodigit = substr($next_year, -2);
 
        // Determine the financial year based on the start month (assuming April is the start of the financial year)
        $fy_start_month = 4;

        if ($select_month >= $fy_start_month) {

            $fy_full_year = $select_year . '-' . ($select_year + 1);
        }
        if ($select_month < $fy_start_month) {

            $fy_full_year = ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);


        $month_yr = addslashes($select_Mon . '\'' . substr($select_year, -2));

        //dd($month_yr);
        // dd($month_yr);
        // if user select date search 
        if ($zone != '' || $select_date != '' || $region != '' || $state != '' || $area !='') {

            if ($select_date != '') {
                $sec_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$fy_year'");
                $select_target = $sec_tgt[0]->target_name;
            }
            $select_yr = substr($select_year, 2);
            $last_select_mon = ($select_Mon) . '\'' . $select_yr;
            $last_select_month = addslashes(($select_Mon) . '\'' . $select_yr);
            // next month target purpose
            $select_yer = substr($select_year, 2);
            
            if( $next_month=="Jan"){
               
               $select_yr = $select_yr + 1; 
           }
           
            $last_target_mon = ($next_month) . '\'' . $select_yr;
           
          // dd($select_yr);
           
            $next_month_target = addslashes(($next_month) . '\'' . $select_yr);
             
            
            $this->data['mon_yr'] = $select_Mon . '\'' . substr($select_year, -2);
            $this->data['nxt_target'] = $last_target_mon;

            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $month_yr = $month_y;
                $this->data['mon_yr'] = $l_dbmon[0]->month_y;
                $select_target = $cur_target;
                $last_select_month = $month_y;
                $next_month_target = $de_next_month_target ;
                $this->data['nxt_target'] = stripslashes($de_next_month_target);
            }

            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($state != '') {
                $condition = "AND state ='$state'";
            }
             if ($area != '') {
                $condition = "AND area='$area'";
            }
            if ($state != '' && $region != '') {
                $condition = "AND state='$state' AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }
            if ($zone != '' && $region != '' && $state != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND state='$state'";
            }


            $this->data['all_ind_sal'] = \DB::select("SELECT
          zone,region,state as state,stockist_dist_name as name,
          ROUND(SUM(CASE WHEN data_type = 'Sales' AND month_y = '$last_select_month' THEN asseesable_value ELSE 0 END), 0) AS sale,
          ROUND(SUM(CASE WHEN data_type = '$select_target' AND month_y = '$last_select_month' THEN asseesable_value ELSE 0 END), 0) AS target,
           ROUND(SUM(CASE WHEN data_type = '$select_target' AND month_y = '$next_month_target' THEN asseesable_value ELSE 0 END), 0) AS nxt_target
          FROM
          sd_primarydataupload_t
          WHERE 1=1
          $condition $wh 
          $notin
          GROUP BY
          zone,region,state,stockist_dist_name HAVING (sale !='0' OR target !='0')");
          
        } else {

            $this->data['all_ind_sal'] = \DB::select("SELECT
                zone,region,state as state,stockist_dist_name as name,
                ROUND(SUM(CASE WHEN data_type = 'Sales' AND month_y = '$month_y' THEN asseesable_value ELSE 0 END), 0) AS sale,
                ROUND(SUM(CASE WHEN data_type = '$cur_target' AND month_y = '$month_y' THEN asseesable_value ELSE 0 END), 0) AS target,
                ROUND(SUM(CASE WHEN data_type = '$cur_target' AND month_y = '$de_next_month_target' THEN asseesable_value ELSE 0 END), 0) AS nxt_target
                FROM
                sd_primarydataupload_t
                WHERE
                1=1 $wh $notin
                GROUP BY
                 zone,region,state,stockist_dist_name ,f_year HAVING (sale !='0' OR target !='0')");
        }

        return view('primarysales&target.monachivement', $this->data);
    }

    // state sale with month wise

    public function StateMonthWise(Request $request)
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

        if ($depart == "14" && $emp_id != '121') {

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

            if ($emp_id == '94') {

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
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        //dd( $this->data['last_update']);
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_primarydataupload_t` where division!='-'");
        $division = $request->input('division');
        $this->data['areas'] = \DB::select("SELECT DISTINCT area from `sd_primarydataupload_t` where area NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $area = $request->input('area');
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['states'] = \DB::select("SELECT DISTINCT state from `sd_primarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $state = $request->input('state');
        $l_dbmon = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month_y;

        $month_y = addslashes($l_dbmon[0]->month_y);

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

        $select_date = $request->input('date_select');
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('m', strtotime($select_date));
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

        // dd($month_yr);
        // if user select date search 
        if ($zone != '' || $select_date != '' || $region != '' || $area !='' || $state !='' ) {
            $this->data['mon_yr'] = date('M', strtotime($select_date)) . '\'' . substr($select_year, -2);

            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($area != '') {
                $condition = "AND area='$area'";
            }
             if ($state != '') {
                $condition = "AND state='$state'";
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
            }

            // purchase
            
             $this->data['all_ind_sal'] = \DB::select("SELECT 
            state,f_year, month,c_year,
            ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale
        FROM
            sd_primarydataupload_t
        WHERE
       (f_year='$fy_year' OR f_year='$select_pre_year') $condition $wh $notin

        GROUP BY 
            state, month, f_year
        HAVING
            sale != 0
        ORDER BY state,
            c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");
            
        } else {

            // purchase

            $this->data['all_ind_sal'] = \DB::select("SELECT 
            state,f_year, month,c_year,
            ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale
        FROM
            sd_primarydataupload_t
        WHERE
       (f_year='$cur_fyear' OR f_year='$pre_fyear') $wh $notin

        GROUP BY 
            state, month, f_year
        HAVING
            sale != 0
        ORDER BY state,
            c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");
        }

        return view('primarysales&target.statesale', $this->data);
    }

    // distributor sale with month wise
    public function DistributormonthWise(Request $request)
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

        if ($depart == "14" && $emp_id != '121') {

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

            if ($emp_id == '94') {

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
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        //dd( $this->data['last_update']);
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_primarydataupload_t` where division!='-'");
        $division = $request->input('division');
        $this->data['areas'] = \DB::select("SELECT DISTINCT area from `sd_primarydataupload_t` where area NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $area = $request->input('area');
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['states'] = \DB::select("SELECT DISTINCT state from `sd_primarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $state = $request->input('state');
        $l_dbmon = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month_y;

        $month_y = addslashes($l_dbmon[0]->month_y);
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

        $select_date = $request->input('date_select');
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('m', strtotime($select_date));
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

        // dd($month_yr);
        // if user select date search 
        if ($zone != '' || $select_date != '' || $region != '' || $state != '' || $area !='') {

            $this->data['mon_yr'] = date('M', strtotime($select_date)) . '\'' . substr($select_year, -2);

            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
             if ($area != '') {
                $condition = "AND area='$area'";
            }
            if ($state != '') {
                $condition = "AND state ='$state'";
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
            }

            // purchase
            $this->data['all_ind_sal'] = \DB::select("SELECT 
              state,f_year, month,stockist_dist_name as name,c_year,
            ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) AS sale
        FROM
            sd_primarydataupload_t
        WHERE
           f_year='$fy_year' $condition $wh

        GROUP BY 
            state,stockist_dist_name, month, f_year
        HAVING
            sale != 0

       UNION ALL 

        SELECT 
              state,f_year, month,stockist_dist_name as name,c_year,
            ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) AS sale
        FROM
            sd_primarydataupload_t
        WHERE
           f_year='$select_pre_year' $condition $wh $notin
    
        GROUP BY 
            state,stockist_dist_name, month, f_year
        HAVING
            sale != 0
        ORDER BY
            c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");
            
        } else {

            // purchase

            $this->data['all_ind_sal'] = \DB::select("SELECT 
             state,f_year, month,stockist_dist_name as name,c_year,
            ROUND(SUM(CASE WHEN data_type = 'Sales' THEN asseesable_value ELSE 0 END), 0) as sale
        FROM
            sd_primarydataupload_t
        WHERE
       (f_year='$cur_fyear' OR f_year='$pre_fyear') $wh $notin

        GROUP BY 
        state,stockist_dist_name, month, f_year
        HAVING
            sale != 0
        ORDER BY state,
            c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");
        }

        return view('primarysales&target.distsale', $this->data);
    }

    // zone product wise

    public function Zoneproduct(Request $request)
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

        if ($depart == "14" && $emp_id != '121') {

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

            if ($emp_id == '94') {

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
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $last_pre_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $last_data = \DB::select("SELECT  month_y FROM `sd_primarydataupload_t` ORDER BY `primarydata_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_primarydataupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from `sd_secondarydataupload_t` where division!='-'");
        $division = $request->input('division');
        $this->data['states'] = \DB::select("SELECT DISTINCT state from `sd_primarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
        $state = $request->input('state');
        $this->data['distributor_name'] = \DB::select("SELECT DISTINCT stockist_dist_name AS name from `sd_primarydataupload_t` where data_type = 'Sales' $wh ");
        $dist_name = $request->input('dist_name');
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
        if ($zone != '' || $select_date != '' || $region != '' || $division != '' || $state != '' || $dist_name != '') {

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
            if ($dist_name != '') {
                $condition = "AND stockist_dist_name ='$dist_name'";
            }
            if ($state != '' && $region != '') {
                $condition = "AND state='$state' AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }
            if ($state != '' && $region != '' && $division != '') {
                $condition = "AND state='$state' AND region='$region' AND division='$division'";
            }
            if ($zone != '' && $region != '' && $division != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND division='$division'";
            }

            if ($zone != '' && $region != '' && $state != '') {
                $condition = "AND Zone='$zone' AND region='$region' AND state='$state'";
            }
            // distributor wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
                zone,division,product_form_change as name,f_year,
                ROUND(SUM(CASE WHEN data_type = 'Sales' THEN sales_free_unit ELSE 0 END), 0) as sale,
                ROUND(SUM(CASE WHEN data_type = '$select_target' THEN sales_free_unit ELSE 0 END), 0) as target
                FROM
                sd_primarydataupload_t
                WHERE
                f_year='$fy_year' $condition $wh $notin
                AND (
                    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
                
                )
                GROUP BY
                zone,division,product_form_change,f_year HAVING (sale!='0' or target!='0')
                 UNION ALL
                SELECT
                zone,division,product_form_change as name,f_year,
                ROUND(SUM(CASE WHEN data_type = 'Sales' THEN sales_free_unit ELSE 0 END), 0) as sale,
                ROUND(SUM(CASE WHEN data_type = '$select_target' THEN sales_free_unit ELSE 0 END), 0) as target
                FROM
                sd_primarydataupload_t
                WHERE
                f_year='$select_pre_year' $condition $wh $notin
                AND (
                    STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
                
                )
                GROUP BY
                zone,division,product_form_change,f_year HAVING (sale!='0' or target!='0')");
        } else {
            //      default values

            // zone product wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
         zone,division,product_form_change as name,f_year,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN sales_free_unit ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN sales_free_unit ELSE 0 END), 0) as target
        FROM
        sd_primarydataupload_t
        WHERE
        f_year='$cur_fyear' $wh  $notin
        AND (
            STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
        
        )
        GROUP BY
        zone,division,product_form_change,f_year HAVING (sale!='0' or target!='0')
        UNION ALL
        SELECT
        zone,division,product_form_change as name,f_year,
        ROUND(SUM(CASE WHEN data_type = 'Sales' THEN sales_free_unit ELSE 0 END), 0) as sale,
        ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN sales_free_unit ELSE 0 END), 0) as target
        FROM
        sd_primarydataupload_t
        WHERE
        f_year='$pre_fyear' $wh $notin
        AND (
            STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')

        )
        GROUP BY
        zone,division,product_form_change,f_year HAVING (sale!='0' or target!='0')");

            // dd( $this->data['all_ind_sal']);

        }

        return view('primarysales&target.zoneproduct', $this->data);
    }
}
