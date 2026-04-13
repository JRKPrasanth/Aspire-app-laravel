<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use DB;
use App\Misviewers;

class BestofmonthmisController extends Controller
{


  public function index(Request $request)
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

    $wh = "";
    $notin = ""; // for not in query purpose
    $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

    $last_data = \DB::select("SELECT  month_y FROM `sd_secondarydataupload_t` ORDER BY `secondarydate_upload_id` DESC LIMIT 1");
    $this->data['last_data'] = $last_data[0]->month_y;
    $month_y = addslashes($last_data[0]->month_y);
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

    $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_secondarydataupload_t` where 1=1 $wh $notin");
    $zone = $request->input('zone');
    $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_secondarydataupload_t` where 1=1 $wh $notin");
    $region = $request->input('region');
    $this->data['states'] = \DB::select("SELECT DISTINCT state as state from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
    $state = $request->input('state');
    $this->data['areas'] = \DB::select("SELECT DISTINCT area from `sd_secondarydataupload_t` where area NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
    $area = $request->input('area');
    $this->data['managers'] = \DB::select("SELECT DISTINCT current_reporting_manager AS name from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
    $manager = $request->input('manager');
    $this->data['hqs'] = \DB::select("SELECT DISTINCT hq_name from  `sd_secondarydataupload_t` where 1=1 $wh AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)')");
    $hq = $request->input('hq');

    $condition = '';

    $select_date = $request->input('date_select');

    if($select_date !=''){
    // Get start date from the request
    
    $select_year = date('Y', strtotime($select_date));
    $select_month = date('M', strtotime($select_date));
    $select_mon = date('', strtotime($select_date));
    $this->data['mon_yr'] = ($select_month) . "'" . substr($select_year, 2);
    $till_date1 = ($select_month) . "'" . substr($select_year, 2);
    $till_date1 = addslashes($till_date1);
    }



    // if user select date search 
    if ($select_date != '' || $region != '' || $zone != '' || $manager != '' || $area != '' || $hq != '' || $state != '') {



      if ($select_date == '') {
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $this->data['till_mon'] = $last_data[0]->month_y;
        $till_date1 = $month_y;
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
            if ($state != '') {
                $condition = "AND state ='$state'";
            }
            if ($hq != '') {
                $condition = "AND hq_name='$hq'";
            }
             if ($manager != '') {
                $condition = "AND current_reporting_manager='$manager'";
            }



      $this->data['sql'] = \DB::select("SELECT product_name,SUM(sales_unit) as unit FROM `sd_secondarydataupload_t` WHERE month_y = '$till_date1' AND type_d_s = 'STOCKIST' AND product_name !='PAYMENT OUTSTANDING' $condition $notin
        GROUP BY product_name
        ORDER BY unit DESC
        LIMIT 15");

      $this->data['sql1'] = \DB::select("SELECT product_name,SUM(sales_value) as unit FROM `sd_secondarydataupload_t` WHERE month_y = '$till_date1' AND type_d_s = 'STOCKIST' AND product_name !='PAYMENT OUTSTANDING' $condition $notin
        GROUP BY product_name
        ORDER BY unit DESC
        LIMIT 15");

      $this->data['sql2'] = \DB::select("

            WITH agg AS (
  SELECT
    product_name,
    SUM(sales_unit) AS all_india_units,
    SUM(CASE WHEN zone = 'CW'       THEN sales_unit ELSE 0 END) AS cw_units,
    SUM(CASE WHEN zone = 'EAST'     THEN sales_unit ELSE 0 END) AS east_units,
    SUM(CASE WHEN zone = 'NORTH'    THEN sales_unit ELSE 0 END) AS north_units,
    SUM(CASE WHEN zone = 'SOUTH 1'  THEN sales_unit ELSE 0 END) AS south1_units,
    SUM(CASE WHEN zone = 'SOUTH 2'  THEN sales_unit ELSE 0 END) AS south2_units
  FROM sd_secondarydataupload_t
  WHERE month_y = '$till_date1'
    AND type_d_s = 'STOCKIST'
    AND product_name <> 'PAYMENT OUTSTANDING'
  GROUP BY product_name
),
rnk AS (
  SELECT
    product_name,

    DENSE_RANK() OVER (ORDER BY all_india_units DESC) AS all_india_rank,

    CASE WHEN cw_units     > 0 THEN DENSE_RANK() OVER (ORDER BY cw_units DESC)     END AS cw_rank,
    CASE WHEN east_units   > 0 THEN DENSE_RANK() OVER (ORDER BY east_units DESC)   END AS east_rank,
    CASE WHEN north_units  > 0 THEN DENSE_RANK() OVER (ORDER BY north_units DESC)  END AS north_rank,
    CASE WHEN south1_units > 0 THEN DENSE_RANK() OVER (ORDER BY south1_units DESC) END AS south1_rank,
    CASE WHEN south2_units > 0 THEN DENSE_RANK() OVER (ORDER BY south2_units DESC) END AS south2_rank

  FROM agg
),
final AS (
  SELECT *
  FROM rnk
  WHERE
    all_india_rank <= 15
    OR cw_rank     <= 15
    OR east_rank   <= 15
    OR north_rank  <= 15
    OR south1_rank <= 15
    OR south2_rank <= 15
)
SELECT
  product_name,

  CASE 
    WHEN all_india_rank <= 15 THEN all_india_rank 
    ELSE '-' 
  END AS all_india_rank,

  CASE 
    WHEN cw_rank <= 15 THEN cw_rank 
    ELSE '-' 
  END AS `cw`,

  CASE 
    WHEN east_rank <= 15 THEN east_rank 
    ELSE '-' 
  END AS `east`,

  CASE 
    WHEN north_rank <= 15 THEN north_rank 
    ELSE '-' 
  END AS `north`,

  CASE 
    WHEN south1_rank <= 15 THEN south1_rank 
    ELSE '-' 
  END AS `south1`,

  CASE 
    WHEN south2_rank <= 15 THEN south2_rank 
    ELSE '-' 
  END AS `south2`

FROM final
ORDER BY
  LEAST(
    COALESCE(all_india_rank, 9999),
    COALESCE(cw_rank, 9999),
    COALESCE(east_rank, 9999),
    COALESCE(north_rank, 9999),
    COALESCE(south1_rank, 9999),
    COALESCE(south2_rank, 9999)
  ),
  product_name");

    } else {
      // dd($month_y);
      $this->data['sql'] = \DB::select("SELECT product_name,SUM(sales_unit) as unit FROM `sd_secondarydataupload_t` WHERE month_y = '$month_y' AND type_d_s = 'STOCKIST' AND product_name !='PAYMENT OUTSTANDING' $notin
        GROUP BY product_name
        ORDER BY unit DESC
        LIMIT 15");
      //dd($this->data['sql']);
      $this->data['sql1'] = \DB::select("SELECT product_name,SUM(sales_value) as unit FROM `sd_secondarydataupload_t` WHERE month_y = '$month_y' AND type_d_s = 'STOCKIST' AND product_name !='PAYMENT OUTSTANDING' $notin
        GROUP BY product_name
        ORDER BY unit DESC
        LIMIT 15");

      $this->data['sql2'] = \DB::select("

WITH agg AS (
  SELECT
    product_name,
    SUM(sales_unit) AS all_india_units,
    SUM(CASE WHEN zone = 'CW'       THEN sales_unit ELSE 0 END) AS cw_units,
    SUM(CASE WHEN zone = 'EAST'     THEN sales_unit ELSE 0 END) AS east_units,
    SUM(CASE WHEN zone = 'NORTH'    THEN sales_unit ELSE 0 END) AS north_units,
    SUM(CASE WHEN zone = 'SOUTH 1'  THEN sales_unit ELSE 0 END) AS south1_units,
    SUM(CASE WHEN zone = 'SOUTH 2'  THEN sales_unit ELSE 0 END) AS south2_units
  FROM sd_secondarydataupload_t
  WHERE month_y = '$month_y'
    AND type_d_s = 'STOCKIST'
    AND product_name <> 'PAYMENT OUTSTANDING'
  GROUP BY product_name
),
rnk AS (
  SELECT
    product_name,

    DENSE_RANK() OVER (ORDER BY all_india_units DESC) AS all_india_rank,

    CASE WHEN cw_units     > 0 THEN DENSE_RANK() OVER (ORDER BY cw_units DESC)     END AS cw_rank,
    CASE WHEN east_units   > 0 THEN DENSE_RANK() OVER (ORDER BY east_units DESC)   END AS east_rank,
    CASE WHEN north_units  > 0 THEN DENSE_RANK() OVER (ORDER BY north_units DESC)  END AS north_rank,
    CASE WHEN south1_units > 0 THEN DENSE_RANK() OVER (ORDER BY south1_units DESC) END AS south1_rank,
    CASE WHEN south2_units > 0 THEN DENSE_RANK() OVER (ORDER BY south2_units DESC) END AS south2_rank

  FROM agg
),
final AS (
  SELECT *
  FROM rnk
  WHERE
    all_india_rank <= 15
    OR cw_rank     <= 15
    OR east_rank   <= 15
    OR north_rank  <= 15
    OR south1_rank <= 15
    OR south2_rank <= 15
)
SELECT
  product_name,

  CASE 
    WHEN all_india_rank <= 15 THEN all_india_rank 
    ELSE '-' 
  END AS all_india_rank,

  CASE 
    WHEN cw_rank <= 15 THEN cw_rank 
    ELSE '-' 
  END AS `cw`,

  CASE 
    WHEN east_rank <= 15 THEN east_rank 
    ELSE '-' 
  END AS `east`,

  CASE 
    WHEN north_rank <= 15 THEN north_rank 
    ELSE '-' 
  END AS `north`,

  CASE 
    WHEN south1_rank <= 15 THEN south1_rank 
    ELSE '-' 
  END AS `south1`,

  CASE 
    WHEN south2_rank <= 15 THEN south2_rank 
    ELSE '-' 
  END AS `south2`

FROM final
ORDER BY
  LEAST(
    COALESCE(all_india_rank, 9999),
    COALESCE(cw_rank, 9999),
    COALESCE(east_rank, 9999),
    COALESCE(north_rank, 9999),
    COALESCE(south1_rank, 9999),
    COALESCE(south2_rank, 9999)
  ),
  product_name
            
");

    }

    return view('bestofmonth.index', $this->data);

  }


  public function topsellingmis(Request $request)
  {
    $wh = "";
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

    $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_secondarydataupload_t` where 1=1 $wh $notin");
    $zone = $request->input('zone');
    $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_secondarydataupload_t` where 1=1 $wh $notin");
    $region = $request->input('region');
    $this->data['states'] = \DB::select("SELECT DISTINCT state as state from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
    $state = $request->input('state');
    $this->data['areas'] = \DB::select("SELECT DISTINCT area from `sd_secondarydataupload_t` where area NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
    $area = $request->input('area');
    $this->data['managers'] = \DB::select("SELECT DISTINCT current_reporting_manager AS name from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh");
    $manager = $request->input('manager');
    $hq = $request->input('hq');
    $this->data['hqs'] = \DB::select("SELECT DISTINCT hq_name from  `sd_secondarydataupload_t` where 1=1 $wh AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)')");
    $region = $request->input('hq');

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


    // Get start date from the request
    $select_date = $request->input('date_select');
    $select_year = date('Y', strtotime($select_date));
    $select_month = date('m', strtotime($select_date));
    $select_mon = date('', strtotime($select_date));
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
    if ($select_date != '' || $region != '' || $zone != '' || $manager != '' || $area != '' || $hq != '') {


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
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $this->data['till_mon'] = $last_data[0]->month_y;
        $this->data['till'] = stripslashes($till_date);
        $till_date1 = $till_date;
        $fy_year = $cur_fyear;
        $select_year= $last_y;
        $select_month= $last_mon;
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
            if ($state != '') {
                $condition = "AND state ='$state'";
            }
            if ($hq != '') {
                $condition = "AND hq_name='$hq'";
            }
             if ($manager != '') {
                $condition = "AND current_reporting_manager='$manager'";
            }

      $this->data['sql'] = \DB::select("SELECT product_name,SUM(sales_unit) as unit FROM `sd_secondarydataupload_t` WHERE 
            
                           f_year='$fy_year' 
               AND (
                   STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
            
               )
            
           AND type_d_s = 'STOCKIST' AND product_name !='PAYMENT OUTSTANDING' $condition $notin
        GROUP BY product_name
        ORDER BY unit DESC
        LIMIT 15");

      $this->data['sql1'] = \DB::select("SELECT product_name,SUM(sales_value) as unit FROM `sd_secondarydataupload_t` WHERE 
            
                                      f_year='$fy_year' 
               AND (
                   STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
            
               ) 
            
             AND type_d_s = 'STOCKIST' AND product_name !='PAYMENT OUTSTANDING' $condition $notin
        GROUP BY product_name
        ORDER BY unit DESC
        LIMIT 15");

      $this->data['sql2'] = \DB::select("

            WITH agg AS (
  SELECT
    product_name,
    SUM(sales_unit) AS all_india_units,
    SUM(CASE WHEN zone = 'CW'       THEN sales_unit ELSE 0 END) AS cw_units,
    SUM(CASE WHEN zone = 'EAST'     THEN sales_unit ELSE 0 END) AS east_units,
    SUM(CASE WHEN zone = 'NORTH'    THEN sales_unit ELSE 0 END) AS north_units,
    SUM(CASE WHEN zone = 'SOUTH 1'  THEN sales_unit ELSE 0 END) AS south1_units,
    SUM(CASE WHEN zone = 'SOUTH 2'  THEN sales_unit ELSE 0 END) AS south2_units
  FROM sd_secondarydataupload_t
  WHERE                            f_year='$fy_year' 
               AND (
                   STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
            
               )
    AND type_d_s = 'STOCKIST'
    AND product_name <> 'PAYMENT OUTSTANDING'
  GROUP BY product_name
),
rnk AS (
  SELECT
    product_name,

    DENSE_RANK() OVER (ORDER BY all_india_units DESC) AS all_india_rank,

    CASE WHEN cw_units     > 0 THEN DENSE_RANK() OVER (ORDER BY cw_units DESC)     END AS cw_rank,
    CASE WHEN east_units   > 0 THEN DENSE_RANK() OVER (ORDER BY east_units DESC)   END AS east_rank,
    CASE WHEN north_units  > 0 THEN DENSE_RANK() OVER (ORDER BY north_units DESC)  END AS north_rank,
    CASE WHEN south1_units > 0 THEN DENSE_RANK() OVER (ORDER BY south1_units DESC) END AS south1_rank,
    CASE WHEN south2_units > 0 THEN DENSE_RANK() OVER (ORDER BY south2_units DESC) END AS south2_rank

  FROM agg
),
final AS (
  SELECT *
  FROM rnk
  WHERE
    all_india_rank <= 15
    OR cw_rank     <= 15
    OR east_rank   <= 15
    OR north_rank  <= 15
    OR south1_rank <= 15
    OR south2_rank <= 15
)
SELECT
  product_name,

  CASE 
    WHEN all_india_rank <= 15 THEN all_india_rank 
    ELSE '-' 
  END AS all_india_rank,

  CASE 
    WHEN cw_rank <= 15 THEN cw_rank 
    ELSE '-' 
  END AS `cw`,

  CASE 
    WHEN east_rank <= 15 THEN east_rank 
    ELSE '-' 
  END AS `east`,

  CASE 
    WHEN north_rank <= 15 THEN north_rank 
    ELSE '-' 
  END AS `north`,

  CASE 
    WHEN south1_rank <= 15 THEN south1_rank 
    ELSE '-' 
  END AS `south1`,

  CASE 
    WHEN south2_rank <= 15 THEN south2_rank 
    ELSE '-' 
  END AS `south2`

FROM final
ORDER BY
  LEAST(
    COALESCE(all_india_rank, 9999),
    COALESCE(cw_rank, 9999),
    COALESCE(east_rank, 9999),
    COALESCE(north_rank, 9999),
    COALESCE(south1_rank, 9999),
    COALESCE(south2_rank, 9999)
  ),
  product_name
");

    } else {

      $this->data['sql'] = \DB::select("SELECT product_name,SUM(sales_unit) as unit FROM `sd_secondarydataupload_t` WHERE
            
                       f_year='$cur_fyear' 
           AND (
               STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
        
           )
            
             AND type_d_s = 'STOCKIST' AND product_name !='PAYMENT OUTSTANDING' $notin
        GROUP BY product_name
        ORDER BY unit DESC
        LIMIT 15");

      $this->data['sql1'] = \DB::select("SELECT product_name,SUM(sales_value) as unit FROM `sd_secondarydataupload_t` WHERE
            
                       f_year='$cur_fyear' 
           AND (
               STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
        
           )
            
             AND type_d_s = 'STOCKIST' AND product_name !='PAYMENT OUTSTANDING' $notin
        GROUP BY product_name
        ORDER BY unit DESC
        LIMIT 15");


      $this->data['sql2'] = \DB::select("
            
           WITH agg AS (
  SELECT
    product_name,
    SUM(sales_unit) AS all_india_units,
    SUM(CASE WHEN zone = 'CW'       THEN sales_unit ELSE 0 END) AS cw_units,
    SUM(CASE WHEN zone = 'EAST'     THEN sales_unit ELSE 0 END) AS east_units,
    SUM(CASE WHEN zone = 'NORTH'    THEN sales_unit ELSE 0 END) AS north_units,
    SUM(CASE WHEN zone = 'SOUTH 1'  THEN sales_unit ELSE 0 END) AS south1_units,
    SUM(CASE WHEN zone = 'SOUTH 2'  THEN sales_unit ELSE 0 END) AS south2_units
  FROM sd_secondarydataupload_t
  WHERE f_year='$cur_fyear' 
           AND (
               STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
        
           )
    AND type_d_s = 'STOCKIST'
    AND product_name <> 'PAYMENT OUTSTANDING'
  GROUP BY product_name
),
rnk AS (
  SELECT
    product_name,

    DENSE_RANK() OVER (ORDER BY all_india_units DESC) AS all_india_rank,

    CASE WHEN cw_units     > 0 THEN DENSE_RANK() OVER (ORDER BY cw_units DESC)     END AS cw_rank,
    CASE WHEN east_units   > 0 THEN DENSE_RANK() OVER (ORDER BY east_units DESC)   END AS east_rank,
    CASE WHEN north_units  > 0 THEN DENSE_RANK() OVER (ORDER BY north_units DESC)  END AS north_rank,
    CASE WHEN south1_units > 0 THEN DENSE_RANK() OVER (ORDER BY south1_units DESC) END AS south1_rank,
    CASE WHEN south2_units > 0 THEN DENSE_RANK() OVER (ORDER BY south2_units DESC) END AS south2_rank

  FROM agg
),
final AS (
  SELECT *
  FROM rnk
  WHERE
    all_india_rank <= 15
    OR cw_rank     <= 15
    OR east_rank   <= 15
    OR north_rank  <= 15
    OR south1_rank <= 15
    OR south2_rank <= 15
)
SELECT
  product_name,

  CASE 
    WHEN all_india_rank <= 15 THEN all_india_rank 
    ELSE '-' 
  END AS all_india_rank,

  CASE 
    WHEN cw_rank <= 15 THEN cw_rank 
    ELSE '-' 
  END AS `cw`,

  CASE 
    WHEN east_rank <= 15 THEN east_rank 
    ELSE '-' 
  END AS `east`,

  CASE 
    WHEN north_rank <= 15 THEN north_rank 
    ELSE '-' 
  END AS `north`,

  CASE 
    WHEN south1_rank <= 15 THEN south1_rank 
    ELSE '-' 
  END AS `south1`,

  CASE 
    WHEN south2_rank <= 15 THEN south2_rank 
    ELSE '-' 
  END AS `south2`

FROM final
ORDER BY
  LEAST(
    COALESCE(all_india_rank, 9999),
    COALESCE(cw_rank, 9999),
    COALESCE(east_rank, 9999),
    COALESCE(north_rank, 9999),
    COALESCE(south1_rank, 9999),
    COALESCE(south2_rank, 9999)
  ),
  product_name
");

    }

    return view('bestofmonth.topselling', $this->data);

  }

}