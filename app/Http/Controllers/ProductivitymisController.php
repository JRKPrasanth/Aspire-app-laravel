<?php

namespace App\Http\Controllers;
use App\Misviewers;
use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;

class ProductivitymisController extends Controller
{

    public function __construct()
    {

        $this->pageModule = "personwiseproductivity";
        $this->data = array();
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();

    }

    // sales and purchase trend
    public function Salestrend(Request $request)
    {

        $depart = \Session::get('groupname');
        $this->data['groupname'] = \Session::get('groupname');
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
        $this->data['states'] = \DB::select("SELECT DISTINCT state as state from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')");
        $state = $request->input('state');
        $this->data['regions'] = \DB::select("SELECT DISTINCT hq_name from  `sd_secondarydataupload_t` where 1=1  AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)')");
        $region = $request->input('region');
        $this->data['managers'] = \DB::select("SELECT DISTINCT current_reporting_manager AS name from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') ");
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
        if ($select_date != '' || $region != '' || $state != '' || $manager != '') {
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
            if ($state != '') {
                $condition = "AND state ='$state'";
            }
            // sales

            $this->data['all_ind_sal'] = \DB::select("SELECT state,current_reporting_manager as manager,
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
        AND (f_year = '$fy_year' OR f_year = '$select_pre_year') $condition  $notin
    GROUP BY 
       state,current_reporting_manager,f_year, hq_name, month

    ORDER BY
    FIELD(month, 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec', 'jan', 'feb', 'mar')");


            // purchase

            $this->data['sales_trend'] = \DB::select("SELECT state,current_reporting_manager as manager,
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
    AND  f_year = '$select_pre_year'  $condition  $notin

GROUP BY 
    state,current_reporting_manager,f_year, hq_name, month

    UNION ALL
SELECT state,current_reporting_manager as manager,
    c_year,
    hq_name AS name,
    f_year,
    month,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) AS sale
FROM
    sd_secondarydataupload_t
WHERE

     f_year = '$fy_year' $condition  $notin

GROUP BY 
   state,current_reporting_manager,f_year, hq_name, month

ORDER BY
FIELD(month, 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec', 'jan', 'feb', 'mar')");


        } else {

            // sales

            $this->data['all_ind_sal'] = \DB::select("SELECT state,current_reporting_manager as manager,
        state,hq_name AS name,
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
        AND (f_year = '$cur_fyear' OR f_year = '$pre_fyear') $notin 
    GROUP BY 
        state,current_reporting_manager,f_year, hq_name, month
    HAVING 
        sale != 0
    ORDER BY
    c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");

            // purchase

            $this->data['sales_trend'] = \DB::select("SELECT state,current_reporting_manager as manager,
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
        AND (f_year = '$cur_fyear' OR f_year = '$pre_fyear') $notin 
    GROUP BY 
        state,current_reporting_manager,f_year, hq_name, month
    HAVING 
        sale != 0
    ORDER BY
    c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");

        }

        return view('productivitymis.saleandpurtrend', $this->data);
    }

    // PersonWise Report
    public function Personproductivity(Request $request)
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

        $depart = \Session::get('groupname');
        $this->data['groupname'] = \Session::get('groupname');
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

        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_secondarydataupload_t` where 1=1  $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_secondarydataupload_t` where 1=1  $notin");
        $region = $request->input('region');
        $this->data['persons'] = \DB::select("SELECT DISTINCT field_force_name from  `sd_secondarydataupload_t` where 1=1  AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)')");
        $person = $request->input('person');
        $this->data['states'] = \DB::select("SELECT DISTINCT state as state from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') ");
        $state = $request->input('state');
        $this->data['regions'] = \DB::select("SELECT DISTINCT hq_name from  `sd_secondarydataupload_t` where 1=1  AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') and hq_name not like '%dist%'");
        $region = $request->input('region');
        $this->data['managers'] = \DB::select("SELECT DISTINCT current_reporting_manager AS name from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') ");
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
        if ($manager != '' || $select_date != '' || $region != '' || $state != '') {

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
            if ($state != '') {
                $condition = "AND state ='$state'";
            }
            // head q wise   
            // distributor sale

            $this->data['all_ind_sal'] = \DB::select("SELECT  state,current_reporting_manager as manager,
    hq_name,field_force_name as name,f_year,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
    ROUND(SUM(CASE WHEN data_type = '$select_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
       sd_secondarydataupload_t
    WHERE
    f_year='$fy_year' and hq_name not like '%dist%' $condition  $notin
    AND (
       STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
    
    )
    GROUP BY
    state,current_reporting_manager,hq_name,field_force_name,f_year  
        UNION ALL
       SELECT  state,current_reporting_manager as manager,
       hq_name,field_force_name as name,f_year,
          ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
          ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
       sd_secondarydataupload_t
    WHERE
       f_year='$select_pre_year' and hq_name not like '%dist%' $condition  $notin
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')

   )
    GROUP BY  state,current_reporting_manager,
    hq_name,field_force_name,f_year");

            // monthly productivity

            $this->data['zone_wise'] = \DB::select("SELECT state,
        hq_name,current_reporting_manager,field_force_name,
       month_y,f_year,c_year,
       ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
       ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
       FROM
       sd_secondarydataupload_t
       WHERE  f_year = '$fy_year' and hq_name not like '%dist%' $condition  $notin
       AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
       GROUP BY
        state,hq_name,current_reporting_manager,field_force_name,month_y,f_year HAVING (sale !='0' OR target !='0') AND field_force_name !='BIKASH DUTTA' ORDER BY  c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");

        } else {

            //  default values
            // distributor sale
            $this->data['all_ind_sal'] = \DB::select("SELECT state,current_reporting_manager as manager,
       hq_name,field_force_name as name,f_year,
       ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
          ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
       sd_secondarydataupload_t
    WHERE
       f_year='$cur_fyear' and hq_name not like '%dist%'  $notin
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
    
       )
    GROUP BY
    state,current_reporting_manager,hq_name,field_force_name,f_year 
        
      UNION ALL
       SELECT
      state,current_reporting_manager as manager,hq_name,field_force_name as name,f_year,
       ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
          ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
       sd_secondarydataupload_t
    WHERE
       f_year='$pre_fyear' and hq_name not like '%dist%'  $notin
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')
    
       )
    GROUP BY
    state,current_reporting_manager,hq_name,field_force_name,f_year ");

            // monthly productiity

            $this->data['zone_wise'] = \DB::select("SELECT
        state,hq_name,current_reporting_manager,field_force_name,month,
        month_y,f_year,c_year,
       
       ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
       ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
       FROM
       sd_secondarydataupload_t
       WHERE  f_year = '$cur_fyear' and hq_name not like '%dist%'  $notin
       AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
       GROUP BY
      state,hq_name,current_reporting_manager,field_force_name,month,month_y,f_year HAVING (sale !='0' OR target !='0') AND field_force_name !='BIKASH DUTTA' ORDER BY  c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");

        }

        return view('productivitymis.personwise_production', $this->data);
    }

    // Heade Quaters Wise Report
    public function HeadquatWise(Request $request)
    {

        $depart = \Session::get('groupname');
        $this->data['groupname'] = \Session::get('groupname');
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
        $this->data['managers'] = \DB::select("SELECT DISTINCT current_reporting_manager AS name from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') ");
        $manager = $request->input('manager');
        $this->data['states'] = \DB::select("SELECT DISTINCT state as state from `sd_secondarydataupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') ");
        $state = $request->input('state');
        $this->data['regions'] = \DB::select("SELECT DISTINCT hq_name from  `sd_secondarydataupload_t` where 1=1  AND hq_name NOT IN ('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)')");
        $region = $request->input('region');
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
        if ($manager != '' || $select_date != '' || $region != '' || $state != '') {

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
            if ($state != '') {
                $condition = "AND state ='$state'";
            }
            // head q wise   
            // distributor sale
            $this->data['all_ind_sal'] = \DB::select("SELECT
            state,current_reporting_manager as manager,hq_name,f_year,
               ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
                  ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
            FROM
               sd_secondarydataupload_t
             WHERE
       f_year='$fy_year' $condition  $notin AND `hq_name` NOT LIKE '%dist'
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
    
       )
    GROUP BY
    state,current_reporting_manager,hq_name,f_year 
        UNION ALL
       SELECT
       state,current_reporting_manager as manager,hq_name,f_year, 
       ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
          ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
       sd_secondarydataupload_t
    WHERE
       f_year='$select_pre_year' $condition  $notin AND `hq_name` NOT LIKE '%dist'
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
    
       )
    GROUP BY
    state,current_reporting_manager,hq_name,f_year HAVING (sale !='0' OR target !='0')");

        } else {
            //      default values

            // distributor sale
            $this->data['all_ind_sal'] = \DB::select("SELECT
    state,current_reporting_manager as manager,hq_name,f_year,
       ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
       ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
       sd_secondarydataupload_t
    WHERE
       f_year='$cur_fyear'  $notin AND `hq_name` NOT LIKE '%dist'
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
    
       )
    GROUP BY
    state,current_reporting_manager,hq_name,f_year HAVING (sale !='0' OR target !='0')
        UNION ALL
       SELECT
       state,current_reporting_manager as manager,hq_name,f_year, 
       ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as sale,
          ROUND(SUM(CASE WHEN data_type = '$cur_target' THEN purchase_stock_value ELSE 0 END), 0) as target
    FROM
       sd_secondarydataupload_t
    WHERE
       f_year='$pre_fyear'  $notin AND `hq_name` NOT LIKE '%dist'
       AND (
           STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_pre_y-$last_m-01', '%Y-%b-%d')
    
       )
    GROUP BY
    state,current_reporting_manager,hq_name,f_year HAVING (sale !='0' OR target !='0')");
        }

        return view('productivitymis.hqwise', $this->data);
    }



}

