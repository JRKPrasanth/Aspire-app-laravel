<?php

namespace App\Http\Controllers;
use App\Misviewers;
use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;

class SecondarysalesreportController extends Controller
{

			   public function __construct(){
        
		$this->pageModule="secondarysalesreport";	
		$this->data=array();
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();
       
    }
	
    public function Monthwise(Request $request)
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
                    $wh = " AND sd_prmyscdyupload_t.region IN ($emp_region)"; 
                    
                } else {
                    
                    $wh = " AND sd_prmyscdyupload_t.region IN ('')";
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
                    $wh = "  AND sd_prmyscdyupload_t.zone ='$emp_zone'";
                } else {
                    $wh = " AND sd_prmyscdyupload_t.zone IN ('')";
                }
            }
            
                // raja ram only show andra state purpose
            
               if($emp_id == '94'){
                   
                   $wh = " AND sd_prmyscdyupload_t.state IN ('ANDHRA PRADESH')";
                   
               }
        }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";

        $l_dbmon = \DB::select("SELECT  month FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $pre_last_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $last_data = \DB::select("SELECT  month_y FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['month'] = date('M', strtotime($last_m));
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");

        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['states'] = \DB::select("SELECT DISTINCT state as state from `sd_prmyscdyupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh $notin");
        $state = $request->input('state');
        $condition = '';
        $this->data['select_year'] = $last_y;

        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $cur_fyear = $this->data['fy_year'][0]->f_year;

        // defalt previous fy_years
        $pre_cur_year = substr($cur_fyear, 0, 4) - 1;
        $last_preyear = substr($cur_fyear, 5, 7) - 1;
        // Concatenating the current and next year with appropriate formatting
        $pre_fyear = $pre_cur_year . '-' . $last_preyear;

        $this->data['cur_fy_year'] = $cur_fyear;
        $this->data['pre_fy_year'] = $pre_fyear;
        /// end default fy years     
//dd($wh);
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
        if ($zone != '' || $select_date != '' || $region != '' || $state !='') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));
            $this->data['month'] = date('M', strtotime($select_date));
            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;

            // if user select zone or ___ search 
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_mon;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
                $this->data['select_year'] = $last_y;
                $this->data['month'] = date('M', strtotime($last_m));
                $pre_year = $last_y - 1;
            }

            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($state !=''){
                $condition ="AND state='$state'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }

            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }

            // current financial year record month wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
          month,
          f_year,
          c_year,
          ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'SALES') THEN sales_value ELSE 0 END), 0) as primary_dis,
          ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
          ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
          ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
        FROM
          sd_prmyscdyupload_t
        WHERE
          type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') 
          AND f_year = '$fy_year' $condition $wh $notin
         AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
        
        GROUP BY
          c_year, month
        ORDER BY
          c_year, STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d')");

            // previous financial year record month wise
            $this->data['all_ind_sal_pre'] = \DB::select("SELECT month,c_year,
      ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'SALES') THEN sales_value ELSE 0 END), 0) as primary_dis,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
      ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
    FROM
      sd_prmyscdyupload_t
    WHERE
      type_d_s IN ('DISTRIBUTOR', 'DISTRIBUTORS', 'STOCKIST') and f_year = '$select_pre_year' $condition $wh $notin
    GROUP BY c_year, month
       ORDER BY c_year, STR_TO_DATE(CONCAT('001', month, ' 01'), '%Y %b %d')");

            // sales growth and  stock liqudation ratio month wise
            $sale_report = $this->data['all_ind_sal'];
            $pre_sale_report = $this->data['all_ind_sal_pre'];

            $comparisonData = [];

            foreach ($sale_report as $saleRow) {
                foreach ($pre_sale_report as $preSaleRow) {
                    if ($saleRow->month == $preSaleRow->month) {
                        $comparisonData[] = [
                            'month' => $saleRow->month,
                            'c_year' => $saleRow->c_year,
                            'primary_dis_ratio' => $preSaleRow->primary_dis != 0 ? number_format(($saleRow->primary_dis / $preSaleRow->primary_dis - 1) * 100, 0) . "%" : "0",
                            'sales_dist_ratio' => $preSaleRow->sales_dist != 0 ? number_format(($saleRow->sales_dist / $preSaleRow->sales_dist - 1) * 100, 0) . "%" : "0",
                            'purchase_ratio' => $preSaleRow->purchase != 0 ? number_format(($saleRow->purchase / $preSaleRow->purchase - 1) * 100, 0) . "%" : "0",
                            'sales_stock_ratio' => $preSaleRow->sales_stock != 0 ? number_format(($saleRow->sales_stock / $preSaleRow->sales_stock - 1) * 100, 0) . "%" : "0",
                            'dist_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_dist / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                            'stock_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_stock / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                        ];

                        break;
                    }
                }
            }
            $this->data['ratio_val'] = $comparisonData;

            // Grand total for sales growth and  stock liqudation ratio month wise
            $this->data['grand_total'] = \DB::select("SELECT
    f_year,
    ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'SALES') THEN sales_value ELSE 0 END), 0) as primary_dis,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
    ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
    sd_prmyscdyupload_t
WHERE
    type_d_s IN ('DISTRIBUTOR', 'DISTRIBUTORS', 'STOCKIST') and f_year = '$fy_year' $condition $wh $notin
     AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
GROUP BY
    f_year
ORDER BY
    STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %m %d')");



            $this->data['grand_total_pre'] = \DB::select("SELECT
    f_year,
    ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'SALES') THEN sales_value ELSE 0 END), 0) as primary_dis,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
    ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
    sd_prmyscdyupload_t
WHERE
    type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year = '$select_pre_year' $condition $wh $notin
    AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
GROUP BY
    f_year
ORDER BY
    STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %m %d')");


            $grand_total_cur = $this->data['grand_total'];
            $grand_total_prey = $this->data['grand_total_pre'];

            $GrandData = [];

            foreach ($grand_total_cur as $saleRow) {
                foreach ($grand_total_prey as $preSaleRow) {

                    $GrandData[] = [

                        'primary_dis_ratio' => $preSaleRow->primary_dis != 0 ? number_format(($saleRow->primary_dis / $preSaleRow->primary_dis - 1) * 100, 0) . "%" : "0",
                        'sales_dist_ratio' => $preSaleRow->sales_dist != 0 ? number_format(($saleRow->sales_dist / $preSaleRow->sales_dist - 1) * 100, 0) . "%" : "0",
                        'purchase_ratio' => $preSaleRow->purchase != 0 ? number_format(($saleRow->purchase / $preSaleRow->purchase - 1) * 100, 0) . "%" : "0",
                        'sales_stock_ratio' => $preSaleRow->sales_stock != 0 ? number_format(($saleRow->sales_stock / $preSaleRow->sales_stock - 1) * 100, 0) . "%" : "0",
                        'dist_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_dist / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                        'stock_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_stock / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                    ];

                    break;
                }
            }

            $this->data['grand_val'] = $GrandData;

            //  default values report month wise     
        } else {

            // current financial year record month wise
            $this->data['all_ind_sal'] = \DB::select("SELECT month,c_year,
        
    ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'SALES') THEN sales_value ELSE 0 END), 0) as primary_dis,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
    ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
    FROM
      sd_prmyscdyupload_t
    WHERE
      type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year='$cur_fyear'   AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_mon-01', '%Y-%m-%d')
      $wh $notin 
    GROUP BY
     month ORDER BY c_year,STR_TO_DATE(CONCAT('001', month, ' 01'), '%Y %M %d')");

            // previous financial year record month wise
            $this->data['all_ind_sal_pre'] = \DB::select("SELECT month,c_year,
      ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'SALES') THEN sales_value ELSE 0 END), 0) as primary_dis,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
      ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
    FROM
      sd_prmyscdyupload_t
    WHERE
      type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year='$pre_fyear'  $wh $notin
    GROUP BY
      month ORDER BY c_year, STR_TO_DATE(CONCAT('001', month, ' 01'), '%Y %M %d')");
            // sales growth and  stock liqudation ratio month wise
            $sale_report = $this->data['all_ind_sal'];
            $pre_sale_report = $this->data['all_ind_sal_pre'];

            $comparisonData = [];

            foreach ($sale_report as $saleRow) {
                foreach ($pre_sale_report as $preSaleRow) {
                    if ($saleRow->month == $preSaleRow->month) {
                        $comparisonData[] = [
                            'month' => $saleRow->month,
                            'c_year' => $saleRow->c_year,
                            'primary_dis_ratio' => $preSaleRow->primary_dis != 0 ? number_format(($saleRow->primary_dis / $preSaleRow->primary_dis - 1) * 100, 0) . "%" : "0",
                            'sales_dist_ratio' => $preSaleRow->sales_dist != 0 ? number_format(($saleRow->sales_dist / $preSaleRow->sales_dist - 1) * 100, 0) . "%" : "0",
                            'purchase_ratio' => $preSaleRow->purchase != 0 ? number_format(($saleRow->purchase / $preSaleRow->purchase - 1) * 100, 0) . "%" : "0",
                            'sales_stock_ratio' => $preSaleRow->sales_stock != 0 ? number_format(($saleRow->sales_stock / $preSaleRow->sales_stock - 1) * 100, 0) . "%" : "0",
                            'dist_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_dist / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                            'stock_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_stock / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                        ];

                        break;
                    }
                }
            }

            $this->data['ratio_val'] = $comparisonData;

            // Grand total for sales growth and  stock liqudation ratio month wise
            $this->data['grand_total'] = \DB::select("SELECT
    f_year,month,
    ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'SALES') THEN sales_value ELSE 0 END), 0) as primary_dis,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
    ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
    FROM
        sd_prmyscdyupload_t
    WHERE
        type_d_s IN ('DISTRIBUTOR', 'DISTRIBUTORS', 'STOCKIST') and f_year='$cur_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_mon-01', '%Y-%m-%d') $wh $notin
        AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
    GROUP BY
        f_year
    ORDER BY
        STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");

            $this->data['grand_total_pre'] = \DB::select("SELECT
    f_year,month,
    ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'SALES') THEN sales_value ELSE 0 END), 0) as primary_dis,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
    ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
    sd_prmyscdyupload_t
WHERE
    type_d_s IN ('DISTRIBUTOR', 'DISTRIBUTORS', 'STOCKIST') and f_year='$pre_fyear' $wh $notin
     AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_last_y-$last_m-01', '%Y-%b-%d')
GROUP BY
    f_year
ORDER BY
    STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");

            $grand_total_cur = $this->data['grand_total'];
            $grand_total_prey = $this->data['grand_total_pre'];


            $GrandData = [];

            foreach ($grand_total_cur as $saleRow) {
                foreach ($grand_total_prey as $preSaleRow) {
                    if ($saleRow->month == $preSaleRow->month) {
                        $GrandData[] = [

                            'primary_dis_ratio' => $preSaleRow->primary_dis != 0 ? number_format(($saleRow->primary_dis / $preSaleRow->primary_dis - 1) * 100, 0) . "%" : "0",
                            'sales_dist_ratio' => $preSaleRow->sales_dist != 0 ? number_format(($saleRow->sales_dist / $preSaleRow->sales_dist - 1) * 100, 0) . "%" : "0",
                            'purchase_ratio' => $preSaleRow->purchase != 0 ? number_format(($saleRow->purchase / $preSaleRow->purchase - 1) * 100, 0) . "%" : "0",
                            'sales_stock_ratio' => $preSaleRow->sales_stock != 0 ? number_format(($saleRow->sales_stock / $preSaleRow->sales_stock - 1) * 100, 0) . "%" : "0",
                            'dist_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_dist / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                            'stock_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_stock / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                        ];

                        break;
                    }
                }
            }
            $this->data['grand_val'] = $GrandData;
        }

        return view('secondarysales.monthreport', $this->data);
    }

    // zone wise report
    public function Zonewise(Request $request)
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
                    $wh = " AND sd_prmyscdyupload_t.region IN ($emp_region)";  
                } else {
                    $wh = " AND sd_prmyscdyupload_t.region IN ('')";
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
                    $wh = "  AND sd_prmyscdyupload_t.zone ='$emp_zone'";
                } else {
                    $wh = " AND sd_prmyscdyupload_t.zone IN ('')";
                }
            }
            // raja ram only show andra state purpose
            
               if($emp_id == '94'){
                   
                   $wh = " AND sd_prmyscdyupload_t.state IN ('ANDHRA PRADESH')";
                   
               }
        }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";
        $l_dbmon = \DB::select("SELECT  month FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $pre_last_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_month'] = date('M', strtotime($last_m));
        $this->data['month'] = date('M', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['select_year'] = $last_y - 1;

        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
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

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));
            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;
            $this->data['select_year'] = $select_year - 1;
            $this->data['last_month'] = date('M', strtotime($select_date));
            $this->data['month'] = date('M', strtotime($select_date));
            // if user select zone or ___ search 
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_mon;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
                $this->data['select_year'] = $last_y - 1;
                $this->data['last_month'] = $l_dbmon[0]->month;
                $this->data['month'] = date('M', strtotime($last_m));
                $pre_year = $last_y - 1;
            }
            $condition = '';
            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND Zone='$zone' AND region='$region'";
            }
            // current financial year record month wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
      zone,
      f_year,
      ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
      ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
    FROM
      sd_prmyscdyupload_t
    WHERE
      type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') 
      and f_year = '$fy_year' $condition $wh $notin
     AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d') GROUP BY zone");

            // previous financial year record zone wise
            $this->data['all_ind_sal_pre'] = \DB::select("SELECT zone,
  ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
  ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
  ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
  ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
  sd_prmyscdyupload_t
WHERE
  type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year = '$select_pre_year' $condition $wh $notin
  AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
GROUP BY zone");

            // sales growth and  stock liqudation ratio month wise
            $sale_report = $this->data['all_ind_sal'];
            $pre_sale_report = $this->data['all_ind_sal_pre'];

            $comparisonData = [];

            foreach ($sale_report as $saleRow) {
                foreach ($pre_sale_report as $preSaleRow) {
                    if ($saleRow->zone == $preSaleRow->zone) {
                        $comparisonData[] = [
                            'zone' => $saleRow->zone,
                            'primary_dis_ratio' => $preSaleRow->primary_dis != 0 ? number_format(($saleRow->primary_dis / $preSaleRow->primary_dis - 1) * 100, 0) . "%" : "0",
                            'sales_dist_ratio' => $preSaleRow->sales_dist != 0 ? number_format(($saleRow->sales_dist / $preSaleRow->sales_dist - 1) * 100, 0) . "%" : "0",
                            'purchase_ratio' => $preSaleRow->purchase != 0 ? number_format(($saleRow->purchase / $preSaleRow->purchase - 1) * 100, 0) . "%" : "0",
                            'sales_stock_ratio' => $preSaleRow->sales_stock != 0 ? number_format(($saleRow->sales_stock / $preSaleRow->sales_stock - 1) * 100, 0) . "%" : "0",
                            'dist_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_dist / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                            'stock_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_stock / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                        ];

                        break;
                    }
                }
            }
            $this->data['ratio_val'] = $comparisonData;

            // Grand total for sales growth and  stock liqudation ratio month wise
            $this->data['grand_total'] = \DB::select("SELECT
f_year,
ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
sd_prmyscdyupload_t
WHERE
type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year = '$fy_year' $condition $wh $notin
 AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
GROUP BY
f_year
ORDER BY
STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %m %d')");


            $this->data['grand_total_pre'] = \DB::select("SELECT
f_year,
ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
sd_prmyscdyupload_t
WHERE
type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year = '$select_pre_year' $condition $wh $notin
AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
GROUP BY
f_year
ORDER BY
STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %m %d')");


            $grand_total_cur = $this->data['grand_total'];
            $grand_total_prey = $this->data['grand_total_pre'];

            $GrandData = [];

            foreach ($grand_total_cur as $saleRow) {
                foreach ($grand_total_prey as $preSaleRow) {

                    $GrandData[] = [

                        'primary_dis_ratio' => $preSaleRow->primary_dis != 0 ? number_format(($saleRow->primary_dis / $preSaleRow->primary_dis - 1) * 100, 0) . "%" : "0",
                        'sales_dist_ratio' => $preSaleRow->sales_dist != 0 ? number_format(($saleRow->sales_dist / $preSaleRow->sales_dist - 1) * 100, 0) . "%" : "0",
                        'purchase_ratio' => $preSaleRow->purchase != 0 ? number_format(($saleRow->purchase / $preSaleRow->purchase - 1) * 100, 0) . "%" : "0",
                        'sales_stock_ratio' => $preSaleRow->sales_stock != 0 ? number_format(($saleRow->sales_stock / $preSaleRow->sales_stock - 1) * 100, 0) . "%" : "0",
                        'dist_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_dist / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                        'stock_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_stock / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                    ];

                    break;
                }
            }

            $this->data['grand_val'] = $GrandData;

            // current financial year record region wise
            $this->data['region_sale'] = \DB::select("SELECT region,
    
ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
sd_prmyscdyupload_t
WHERE
type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year = '$fy_year' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d') $condition $wh $notin AND region NOT IN ('WB(DERMA)','SOUTH 1(CLASSICAL)') GROUP BY region");

            // previous financial year record region wise
            $this->data['region_sale_pre'] = \DB::select("SELECT region,
ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
sd_prmyscdyupload_t
WHERE
type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year = '$select_pre_year' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')  $condition $wh $notin
GROUP BY region ");
            // sales growth and  stock liqudation ratio region wise
            $sale_report_region = $this->data['region_sale'];
            $pre_sale_report_region = $this->data['region_sale_pre'];

            $RegionData = [];

            foreach ($sale_report_region as $saleRow) {
                foreach ($pre_sale_report_region as $preSaleRow) {
                    if ($saleRow->region == $preSaleRow->region) {
                        $RegionData[] = [
                            'region' => $saleRow->region,
                            'primary_dis_ratio' => $preSaleRow->primary_dis != 0 ? number_format(($saleRow->primary_dis / $preSaleRow->primary_dis - 1) * 100, 0) . "%" : "0",
                            'sales_dist_ratio' => $preSaleRow->sales_dist != 0 ? number_format(($saleRow->sales_dist / $preSaleRow->sales_dist - 1) * 100, 0) . "%" : "0",
                            'purchase_ratio' => $preSaleRow->purchase != 0 ? number_format(($saleRow->purchase / $preSaleRow->purchase - 1) * 100, 0) . "%" : "0",
                            'sales_stock_ratio' => $preSaleRow->sales_stock != 0 ? number_format(($saleRow->sales_stock / $preSaleRow->sales_stock - 1) * 100, 0) . "%" : "0",
                            'dist_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_dist / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                            'stock_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_stock / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                        ];

                        break;
                    }
                }
            }

            $this->data['ratio_val_region'] = $RegionData;

            // Grand total for sales growth and  stock liqudation ratio region wise
            $this->data['grand_total_region'] = \DB::select("SELECT
f_year,region,
ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
  sd_prmyscdyupload_t
WHERE
  type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year = '$fy_year' $condition $wh $notin
  AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
GROUP BY
  f_year");

            $this->data['grand_total_pre_region'] = \DB::select("SELECT
f_year,region,
ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
sd_prmyscdyupload_t
WHERE
type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year = '$select_pre_year' $condition $wh $notin
AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
GROUP BY
f_year");

            $grand_total_region_cur = $this->data['grand_total_region'];
            $grand_total_region_prey = $this->data['grand_total_pre_region'];


            $RegionGrandData = [];

            foreach ($grand_total_region_cur as $saleRow) {
                foreach ($grand_total_region_prey as $preSaleRow) {


                    $RegionGrandData[] = [
                        'primary_dis_ratio' => $preSaleRow->primary_dis != 0 ? number_format(($saleRow->primary_dis / $preSaleRow->primary_dis - 1) * 100, 0) . "%" : "0",
                        'sales_dist_ratio' => $preSaleRow->sales_dist != 0 ? number_format(($saleRow->sales_dist / $preSaleRow->sales_dist - 1) * 100, 0) . "%" : "0",
                        'purchase_ratio' => $preSaleRow->purchase != 0 ? number_format(($saleRow->purchase / $preSaleRow->purchase - 1) * 100, 0) . "%" : "0",
                        'sales_stock_ratio' => $preSaleRow->sales_stock != 0 ? number_format(($saleRow->sales_stock / $preSaleRow->sales_stock - 1) * 100, 0) . "%" : "0",
                        'dist_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_dist / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                        'stock_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_stock / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                    ];

                    break;
                }
            }
            $this->data['grand_val_region'] = $RegionGrandData;

            //  default values report zone wise     
        } else {

            // current financial year record zone wise
            $this->data['all_ind_sal'] = \DB::select("SELECT zone,
    
ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
  sd_prmyscdyupload_t
WHERE
  type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year='$cur_fyear'  AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
  $wh $notin
GROUP BY zone");

            // previous financial year record zone wise
            $this->data['all_ind_sal_pre'] = \DB::select("SELECT zone,
  ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
  ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
  ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
  ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
  sd_prmyscdyupload_t
WHERE
  type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year='$pre_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_last_y-$last_m-01', '%Y-%b-%d') $wh $notin
GROUP BY zone ");
            // sales growth and  stock liqudation ratio zone wise
            $sale_report = $this->data['all_ind_sal'];
            $pre_sale_report = $this->data['all_ind_sal_pre'];

            $comparisonData = [];

            foreach ($sale_report as $saleRow) {
                foreach ($pre_sale_report as $preSaleRow) {
                    if ($saleRow->zone == $preSaleRow->zone) {
                        $comparisonData[] = [
                            'zone' => $saleRow->zone,
                            'primary_dis_ratio' => $preSaleRow->primary_dis != 0 ? number_format(($saleRow->primary_dis / $preSaleRow->primary_dis - 1) * 100, 0) . "%" : "0",
                            'sales_dist_ratio' => $preSaleRow->sales_dist != 0 ? number_format(($saleRow->sales_dist / $preSaleRow->sales_dist - 1) * 100, 0) . "%" : "0",
                            'purchase_ratio' => $preSaleRow->purchase != 0 ? number_format(($saleRow->purchase / $preSaleRow->purchase - 1) * 100, 0) . "%" : "0",
                            'sales_stock_ratio' => $preSaleRow->sales_stock != 0 ? number_format(($saleRow->sales_stock / $preSaleRow->sales_stock - 1) * 100, 0) . "%" : "0",
                            'dist_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_dist / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                            'stock_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_stock / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                        ];

                        break;
                    }
                }
            }

            $this->data['ratio_val'] = $comparisonData;

            // Grand total for sales growth and  stock liqudation ratio zone wise
            $this->data['grand_total'] = \DB::select("SELECT
f_year,zone,
ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
    sd_prmyscdyupload_t
WHERE
    type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year='$cur_fyear' $wh $notin
    AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
GROUP BY
    f_year");

            $this->data['grand_total_pre'] = \DB::select("SELECT
f_year,zone,
ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
sd_prmyscdyupload_t
WHERE
type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year='$pre_fyear' $wh $notin
AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_last_y-$last_m-01', '%Y-%b-%d')
GROUP BY
f_year");

            $grand_total_cur = $this->data['grand_total'];
            $grand_total_prey = $this->data['grand_total_pre'];

            $GrandData = [];

            foreach ($grand_total_cur as $saleRow) {
                foreach ($grand_total_prey as $preSaleRow) {

                    $GrandData[] = [

                        'primary_dis_ratio' => $preSaleRow->primary_dis != 0 ? number_format(($saleRow->primary_dis / $preSaleRow->primary_dis - 1) * 100, 0) . "%" : "0",
                        'sales_dist_ratio' => $preSaleRow->sales_dist != 0 ? number_format(($saleRow->sales_dist / $preSaleRow->sales_dist - 1) * 100, 0) . "%" : "0",
                        'purchase_ratio' => $preSaleRow->purchase != 0 ? number_format(($saleRow->purchase / $preSaleRow->purchase - 1) * 100, 0) . "%" : "0",
                        'sales_stock_ratio' => $preSaleRow->sales_stock != 0 ? number_format(($saleRow->sales_stock / $preSaleRow->sales_stock - 1) * 100, 0) . "%" : "0",
                        'dist_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_dist / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                        'stock_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_stock / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                    ];

                    break;
                }
            }
            $this->data['grand_val'] = $GrandData;

            // current financial year record region wise
            $this->data['region_sale'] = \DB::select("SELECT region,c_year,
    
 ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
 ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
 ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
 ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
 FROM
   sd_prmyscdyupload_t
 WHERE
   type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year='$cur_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d') $wh $notin AND region NOT IN ('WB(DERMA)','SOUTH 1(CLASSICAL)')
 GROUP BY region");

            // previous financial year record region wise
            $this->data['region_sale_pre'] = \DB::select("SELECT region,c_year,
   ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
   ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
   ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
   ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
 FROM
   sd_prmyscdyupload_t
 WHERE
   type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year='$pre_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_last_y-$last_m-01', '%Y-%b-%d') $wh $notin AND region NOT IN ('WB(DERMA)','SOUTH 1(CLASSICAL)')
 GROUP BY region ");
            // sales growth and  stock liqudation ratio region wise
            $sale_report_region = $this->data['region_sale'];
            $pre_sale_report_region = $this->data['region_sale_pre'];

            $RegionData = [];

            foreach ($sale_report_region as $saleRow) {
                foreach ($pre_sale_report_region as $preSaleRow) {
                    if ($saleRow->region == $preSaleRow->region) {
                        $RegionData[] = [
                            'region' => $saleRow->region,
                            'c_year' => $saleRow->c_year,
                            'primary_dis_ratio' => $preSaleRow->primary_dis != 0 ? number_format(($saleRow->primary_dis / $preSaleRow->primary_dis - 1) * 100, 0) . "%" : "0",
                            'sales_dist_ratio' => $preSaleRow->sales_dist != 0 ? number_format(($saleRow->sales_dist / $preSaleRow->sales_dist - 1) * 100, 0) . "%" : "0",
                            'purchase_ratio' => $preSaleRow->purchase != 0 ? number_format(($saleRow->purchase / $preSaleRow->purchase - 1) * 100, 0) . "%" : "0",
                            'sales_stock_ratio' => $preSaleRow->sales_stock != 0 ? number_format(($saleRow->sales_stock / $preSaleRow->sales_stock - 1) * 100, 0) . "%" : "0",
                            'dist_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_dist / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                            'stock_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_stock / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                        ];

                        break;
                    }
                }
            }

            $this->data['ratio_val_region'] = $RegionData;

            // Grand total for sales growth and  stock liqudation ratio region wise
            $this->data['grand_total_region'] = \DB::select("SELECT
 f_year,region,
 ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
 ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
 ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
 ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
 FROM
     sd_prmyscdyupload_t
 WHERE
     type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year='$cur_fyear' $wh $notin
     AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%b-%d')
 GROUP BY
     f_year");

            $this->data['grand_total_pre_region'] = \DB::select("SELECT
 f_year,region,
 ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
 ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
 ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
 ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
 sd_prmyscdyupload_t
WHERE
 type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year='$pre_fyear' $wh $notin
 AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_last_y-$last_m-01', '%Y-%b-%d')
GROUP BY
 f_year");

            $grand_total_region_cur = $this->data['grand_total_region'];
            $grand_total_region_prey = $this->data['grand_total_pre_region'];


            $RegionGrandData = [];

            foreach ($grand_total_region_cur as $saleRow) {
                foreach ($grand_total_region_prey as $preSaleRow) {


                    $RegionGrandData[] = [

                        'primary_dis_ratio' => $preSaleRow->primary_dis != 0 ? number_format(($saleRow->primary_dis / $preSaleRow->primary_dis - 1) * 100, 0) . "%" : "0",
                        'sales_dist_ratio' => $preSaleRow->sales_dist != 0 ? number_format(($saleRow->sales_dist / $preSaleRow->sales_dist - 1) * 100, 0) . "%" : "0",
                        'purchase_ratio' => $preSaleRow->purchase != 0 ? number_format(($saleRow->purchase / $preSaleRow->purchase - 1) * 100, 0) . "%" : "0",
                        'sales_stock_ratio' => $preSaleRow->sales_stock != 0 ? number_format(($saleRow->sales_stock / $preSaleRow->sales_stock - 1) * 100, 0) . "%" : "0",
                        'dist_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_dist / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                        'stock_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_stock / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                    ];

                    break;
                }
            }
            $this->data['grand_val_region'] = $RegionGrandData;
        }


        return view('secondarysales.zonereport', $this->data);
    }


    public function Statewise(Request $request)
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

             //  dd($emp_id);
            // RSM region compare purpose
            if ($emp_reg == '41') {

                $emp_region = '';
                // emp region 
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name' AND active='Yes'");

                if (!empty($em_region)) {
                    $regions = array_column($em_region, 'region');
                    $emp_region = "'" . implode("','", $regions) . "'";
                    $wh = " AND sd_prmyscdyupload_t.region IN ($emp_region)";  
                } else {
                    $wh = " AND sd_prmyscdyupload_t.region IN ('')";
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
                    $wh = "  AND sd_prmyscdyupload_t.zone ='$emp_zone'";
                } else {
                    $wh = " AND sd_prmyscdyupload_t.zone IN ('')";
                }
            }
            
          // raja ram only show andra state purpose
            
               if($emp_id == '94'){
                   
                   $wh = " AND sd_prmyscdyupload_t.state IN ('ANDHRA PRADESH')";
                   
               }
        }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";
        $l_dbmon = \DB::select("SELECT  month FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $pre_last_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_month'] = date('M', strtotime($last_m));
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $last_data = \DB::select("SELECT  month_y FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['month'] = date('M', strtotime($last_m));
        $this->data['select_year'] = $last_y - 1;
        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
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
            $condition = '';
            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));
            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;
            $this->data['select_year'] = $select_year - 1;
            $this->data['last_month'] = date('M', strtotime($select_date));
            $this->data['month'] = date('M', strtotime($select_date));
            // if user select zone or ___ search 
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_mon;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
                $this->data['select_year'] = $last_y - 1;
                $this->data['last_month'] = $l_dbmon[0]->month;
                $pre_year = $last_y - 1;
                $this->data['month'] = date('M', strtotime($last_m));
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
            // current financial year record month wise
            $this->data['all_ind_sal'] = \DB::select("SELECT
      state,
      f_year,
      ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
      ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
    FROM
      sd_prmyscdyupload_t
    WHERE
      type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') 
      AND f_year = '$fy_year' $condition $wh $notin
     AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
    GROUP BY
       state");

            // previous financial year record month wise
            $this->data['all_ind_sal_pre'] = \DB::select("SELECT state,
      ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
      ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
      ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
    FROM
      sd_prmyscdyupload_t
    WHERE
      type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year = '$select_pre_year' $condition $wh $notin
      AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
    GROUP BY state");

            // sales growth and  stock liqudation ratio month wise
            $sale_report = $this->data['all_ind_sal'];
            $pre_sale_report = $this->data['all_ind_sal_pre'];

            $comparisonData = [];

            foreach ($sale_report as $saleRow) {
                foreach ($pre_sale_report as $preSaleRow) {
                    if ($saleRow->state == $preSaleRow->state) {
                        $primary_dis_ratio = $preSaleRow->primary_dis != 0 ? (($saleRow->primary_dis / $preSaleRow->primary_dis - 1) * 100) : null;
                        $sales_dist_ratio = $preSaleRow->sales_dist != 0 ? (($saleRow->sales_dist / $preSaleRow->sales_dist - 1) * 100) : null;
                        $purchase_ratio = $preSaleRow->purchase != 0 ? (($saleRow->purchase / $preSaleRow->purchase - 1) * 100) : null;
                        $sales_stock_ratio = $preSaleRow->sales_stock != 0 ? (($saleRow->sales_stock / $preSaleRow->sales_stock - 1) * 100) : null;
                        $dist_sale_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_dist / $saleRow->primary_dis) * 100) : null;
                        $stock_sale_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_stock / $saleRow->primary_dis) * 100) : null;

                        $comparisonData[] = [
                            'state' => $saleRow->state,
                            'primary_dis_ratio' => $primary_dis_ratio !== null ? number_format($primary_dis_ratio, 0) . "%" : "0",
                            'sales_dist_ratio' => $sales_dist_ratio !== null ? number_format($sales_dist_ratio, 0) . "%" : "0",
                            'purchase_ratio' => $purchase_ratio !== null ? number_format($purchase_ratio, 0) . "%" : "0",
                            'sales_stock_ratio' => $sales_stock_ratio !== null ? number_format($sales_stock_ratio, 0) . "%" : "0",
                            'dist_sale_ratio' => $dist_sale_ratio !== null ? number_format($dist_sale_ratio, 0) . "%" : "0",
                            'stock_sale_ratio' => $stock_sale_ratio !== null ? number_format($stock_sale_ratio, 0) . "%" : "0",
                        ];

                        break;
                    }
                }
            }

            $this->data['ratio_val'] = $comparisonData;

            // Grand total for sales growth and  stock liqudation ratio month wise
            $this->data['grand_total'] = \DB::select("SELECT
f_year,state,
ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
sd_prmyscdyupload_t
WHERE
type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year = '$fy_year' $condition $wh $notin
 AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
GROUP BY 
f_year");


            $this->data['grand_total_pre'] = \DB::select("SELECT
f_year,state,
ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
sd_prmyscdyupload_t
WHERE
type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year = '$select_pre_year' $condition $wh $notin
AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
GROUP BY
f_year");

            $grand_total_cur = $this->data['grand_total'];
            $grand_total_prey = $this->data['grand_total_pre'];

            $GrandData = [];

            foreach ($grand_total_cur as $saleRow) {
                foreach ($grand_total_prey as $preSaleRow) {

                    $GrandData[] = [

                        'primary_dis_ratio' => $preSaleRow->primary_dis != 0 ? number_format(($saleRow->primary_dis / $preSaleRow->primary_dis - 1) * 100, 0) . "%" : "0",
                        'sales_dist_ratio' => $preSaleRow->sales_dist != 0 ? number_format(($saleRow->sales_dist / $preSaleRow->sales_dist - 1) * 100, 0) . "%" : "0",
                        'purchase_ratio' => $preSaleRow->purchase != 0 ? number_format(($saleRow->purchase / $preSaleRow->purchase - 1) * 100, 0) . "%" : "0",
                        'sales_stock_ratio' => $preSaleRow->sales_stock != 0 ? number_format(($saleRow->sales_stock / $preSaleRow->sales_stock - 1) * 100, 0) . "%" : "0",
                        'dist_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_dist / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                        'stock_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_stock / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                    ];

                    break;
                }
            }

            $this->data['grand_val'] = $GrandData;

            //  default values report month wise     
        } else {

            // current financial year record month wise
            $this->data['all_ind_sal'] = \DB::select("SELECT state,
    
ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
  sd_prmyscdyupload_t
WHERE
  type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year='$cur_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_mon-01', '%Y-%m-%d')
   $wh $notin
GROUP BY state");

            // previous financial year record month wise
            $this->data['all_ind_sal_pre'] = \DB::select("SELECT state,
  ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
  ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
  ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
  ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
  sd_prmyscdyupload_t
WHERE
  type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year='$pre_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_last_y-$last_mon-01', '%Y-%m-%d')
  $wh $notin
GROUP BY state");
            // sales growth and  stock liqudation ratio month wise
            $sale_report = $this->data['all_ind_sal'];
            $pre_sale_report = $this->data['all_ind_sal_pre'];

            $comparisonData = [];

            foreach ($sale_report as $saleRow) {
                foreach ($pre_sale_report as $preSaleRow) {
                    if ($saleRow->state == $preSaleRow->state) {
                        $primary_dis_ratio = $preSaleRow->primary_dis != 0 ? (($saleRow->primary_dis / $preSaleRow->primary_dis - 1) * 100) : null;
                        $sales_dist_ratio = $preSaleRow->sales_dist != 0 ? (($saleRow->sales_dist / $preSaleRow->sales_dist - 1) * 100) : null;
                        $purchase_ratio = $preSaleRow->purchase != 0 ? (($saleRow->purchase / $preSaleRow->purchase - 1) * 100) : null;
                        $sales_stock_ratio = $preSaleRow->sales_stock != 0 ? (($saleRow->sales_stock / $preSaleRow->sales_stock - 1) * 100) : null;
                        $dist_sale_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_dist / $saleRow->primary_dis) * 100) : null;
                        $stock_sale_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_stock / $saleRow->primary_dis) * 100) : null;

                        $comparisonData[] = [
                            'state' => $saleRow->state,
                            'primary_dis_ratio' => $primary_dis_ratio !== null ? number_format($primary_dis_ratio, 0) . "%" : "0",
                            'sales_dist_ratio' => $sales_dist_ratio !== null ? number_format($sales_dist_ratio, 0) . "%" : "0",
                            'purchase_ratio' => $purchase_ratio !== null ? number_format($purchase_ratio, 0) . "%" : "0",
                            'sales_stock_ratio' => $sales_stock_ratio !== null ? number_format($sales_stock_ratio, 0) . "%" : "0",
                            'dist_sale_ratio' => $dist_sale_ratio !== null ? number_format($dist_sale_ratio, 0) . "%" : "0",
                            'stock_sale_ratio' => $stock_sale_ratio !== null ? number_format($stock_sale_ratio, 0) . "%" : "0",
                        ];

                        break;
                    }
                }
            }

            $this->data['ratio_val'] = $comparisonData;


            // Grand total for sales growth and  stock liqudation ratio month wise
            $this->data['grand_total'] = \DB::select("SELECT
f_year,state,
ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
    sd_prmyscdyupload_t
WHERE
    type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year='$cur_fyear' $wh $notin
    AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_mon-01', '%Y-%m-%d')
GROUP BY
f_year");

            $this->data['grand_total_pre'] = \DB::select("SELECT
f_year,state,
ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN purchase_stock_value ELSE 0 END), 0) as purchase,
ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
FROM
sd_prmyscdyupload_t
WHERE
type_d_s IN ('DISTRIBUTOR','DISTRIBUTORS', 'STOCKIST') and f_year='$pre_fyear' $wh $notin
AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_last_y-$last_mon-01', '%Y-%m-%d')
GROUP BY
f_year");

            $grand_total_cur = $this->data['grand_total'];
            $grand_total_prey = $this->data['grand_total_pre'];


            $GrandData = [];

            foreach ($grand_total_cur as $saleRow) {
                foreach ($grand_total_prey as $preSaleRow) {

                    $GrandData[] = [

                        'primary_dis_ratio' => $preSaleRow->primary_dis != 0 ? number_format(($saleRow->primary_dis / $preSaleRow->primary_dis - 1) * 100, 0) . "%" : "0",
                        'sales_dist_ratio' => $preSaleRow->sales_dist != 0 ? number_format(($saleRow->sales_dist / $preSaleRow->sales_dist - 1) * 100, 0) . "%" : "0",
                        'purchase_ratio' => $preSaleRow->purchase != 0 ? number_format(($saleRow->purchase / $preSaleRow->purchase - 1) * 100, 0) . "%" : "0",
                        'sales_stock_ratio' => $preSaleRow->sales_stock != 0 ? number_format(($saleRow->sales_stock / $preSaleRow->sales_stock - 1) * 100, 0) . "%" : "0",
                        'dist_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_dist / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                        'stock_sale_ratio' => $saleRow->primary_dis != 0 ? number_format(($saleRow->sales_stock / $saleRow->primary_dis) * 100, 0) . "%" : "0",
                    ];

                    break;
                }
            }
            $this->data['grand_val'] = $GrandData;
        }


        return view('secondarysales.statereport', $this->data);
    }

    // product wise report
    public function Productwise(Request $request)
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
                    $wh = " AND sd_prmyscdyupload_t.region IN ($emp_region)";  
                } else {
                    $wh = " AND sd_prmyscdyupload_t.region IN ('')";
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
                    $wh = "  AND sd_prmyscdyupload_t.zone ='$emp_zone'";
                } else {
                    $wh = " AND sd_prmyscdyupload_t.zone IN ('')";
                }
            }
                      // raja ram only show andra state purpose
            
               if($emp_id == '94'){
                   
                   $wh = " AND sd_prmyscdyupload_t.state IN ('ANDHRA PRADESH')";
                   
               }
        }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";
        $l_dbmon = \DB::select("SELECT  month FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $pre_last_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_month'] = date('M', strtotime($last_m));
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from  `sd_prmyscdyupload_t` where division not in ('-','GIFTS','OTHERS','VISUAL AID','POSTERS','FLYERS','BROUCHERS','GIFT ITEMS','')");
        $division = $request->input('division');
        $this->data['states'] = \DB::select("SELECT DISTINCT state from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
       // dd($this->data['states'] );
        $state= $request->input('state');
        $this->data['select_year'] = $last_y - 1;
        $last_data = \DB::select("SELECT  month_y FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $condition = '';
        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
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
        if ($zone != '' || $state != '' ||  $select_date != '' || $region != '' || $division != '') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;
            $this->data['select_year'] = $select_year - 1;
            $this->data['last_month'] = date('M', strtotime($select_date));

            // if user select zone or ___ search 
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_mon;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
                $this->data['select_year'] = $last_y - 1;
                $this->data['last_month'] = $l_dbmon[0]->month;
                $pre_year = $last_y - 1;
            }
            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
             if ($state != '') {
                $condition = "AND state='$state'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($division != '') {
                $condition = "AND division = '$division'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND zone='$zone' AND region='$region'";
            }
            if ($zone != '' && $division != '') {
                $condition = "AND zone='$zone' AND division='$division'";
            }
            if ($region != '' && $division != '') {
                $condition = "AND region='$region' AND division='$division'";
            }
            if ($zone != '' && $region != '' && $division != '') {
                $condition = "AND zone='$zone' AND region='$region' AND division='$division'";
            }

            //   product wise table

            $this->data['all_ind_sal'] = \DB::select("SELECT
        f_year,
        division,
        product_pack_name,
        ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_free_units ELSE 0 END), 0) as primary_dis,
        ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_free_units ELSE 0 END), 0) as sales_dist,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_free_units ELSE 0 END), 0) as sales_stock
        FROM
        sd_prmyscdyupload_t
        WHERE f_year='$fy_year' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d') $condition $wh AND division not in ('-','')
        AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_free_units != 0) $notin
        GROUP BY
        f_year, division, product_pack_name,division HAVING
        primary_dis != 0 OR sales_dist != 0 OR sales_stock != 0
        UNION ALL 
        SELECT
        f_year,
        division,
        product_pack_name,
        ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_free_units ELSE 0 END), 0) as primary_dis,
        ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_free_units ELSE 0 END), 0) as sales_dist,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_free_units ELSE 0 END), 0) as sales_stock
        FROM
        sd_prmyscdyupload_t
        WHERE f_year='$select_pre_year' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')  $condition $wh AND division not in ('-','')
        AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_free_units != 0) $notin
        GROUP BY
        f_year, division, product_pack_name,division HAVING
        primary_dis != 0 OR sales_dist != 0 OR sales_stock != 0 ");

            // ratio
            $this->data['all_ind_sal_cfy'] = \DB::select("SELECT
        f_year,
        division,
        product_pack_name,
        ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_free_units ELSE 0 END), 0) as primary_dis,
        ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_free_units ELSE 0 END), 0) as sales_dist,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_free_units ELSE 0 END), 0) as sales_stock
        FROM
        sd_prmyscdyupload_t
        WHERE f_year='$fy_year' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d') $condition $wh AND division not in ('-','')
        AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_free_units != 0) $notin
        GROUP BY
        f_year, division, product_pack_name,division HAVING
        primary_dis != 0 OR sales_dist != 0 OR sales_stock != 0");


            $this->data['all_ind_sal_pre'] = \DB::select("SELECT
        f_year,
        division,
        product_pack_name,
        ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_free_units ELSE 0 END), 0) as primary_dis,
        ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_free_units ELSE 0 END), 0) as sales_dist,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_free_units ELSE 0 END), 0) as sales_stock
        FROM
        sd_prmyscdyupload_t
        WHERE f_year='$select_pre_year' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d') $condition $wh AND division not in ('-','')
        AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_free_units != 0) $notin
        GROUP BY
        f_year, division, product_pack_name,division HAVING
        primary_dis != 0 OR sales_dist != 0 OR sales_stock != 0");


            $Product_data_cfy = $this->data['all_ind_sal_cfy'];
            $Product_data_pfy = $this->data['all_ind_sal_pre'];

            $comparisonData = [];

            foreach ($Product_data_cfy as $saleRow) {
                foreach ($Product_data_pfy as $salepreRow) {
                    if ($saleRow->product_pack_name == $salepreRow->product_pack_name) {


                        $primary_dis_ratio = $salepreRow->primary_dis != 0 ? (($saleRow->primary_dis / $salepreRow->primary_dis - 1) * 100) : null;
                        $sales_dist_ratio = $salepreRow->sales_dist != 0 ? (($saleRow->sales_dist / $salepreRow->sales_dist - 1) * 100) : null;
                        $purchase_ratio = $salepreRow->sales_stock != 0 ? (($saleRow->sales_stock / $salepreRow->sales_stock - 1) * 100) : null;

                        $sale_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_dist / $saleRow->primary_dis) * 100) : null;
                        $stock_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_stock / $saleRow->primary_dis) * 100) : null;

                        $comparisonData[] = [
                            'product' => $saleRow->product_pack_name,
                            'primary_dis_ratio' => $primary_dis_ratio !== null ? number_format($primary_dis_ratio, 0) . "%" : "0",
                            'sales_dist_ratio' => $sales_dist_ratio !== null ? number_format($sales_dist_ratio, 0) . "%" : "0",
                            'purchase_ratio' => $purchase_ratio !== null ? number_format($purchase_ratio, 0) . "%" : "0",
                            'slae_ratio' => $sale_ratio !== null ? number_format($sale_ratio, 0) . "%" : "0",
                            'stock_ratio' => $stock_ratio !== null ? number_format($stock_ratio, 0) . "%" : "0",
                        ];

                        break;
                    }
                }
            }

            $this->data['sale_value'] = $comparisonData;

            // ratio of grand total
            $this->data['grand_total_cfy'] = \DB::select("SELECT
        f_year,
        division,
        product_pack_name,
        ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_free_units ELSE 0 END), 0) as primary_dis,
        ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_free_units ELSE 0 END), 0) as sales_dist,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_free_units ELSE 0 END), 0) as sales_stock
        FROM
        sd_prmyscdyupload_t
        WHERE f_year='$fy_year' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d') $condition $wh AND division not in ('-','')
        AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_free_units != 0) $notin
        GROUP BY
        f_year HAVING
        primary_dis != 0 OR sales_dist != 0 OR sales_stock != 0");

            $this->data['grand_total_pfy'] = \DB::select("SELECT
        f_year,
        division,
        product_pack_name,
        ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_free_units ELSE 0 END), 0) as primary_dis,
        ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_free_units ELSE 0 END), 0) as sales_dist,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_free_units ELSE 0 END), 0) as sales_stock
        FROM
        sd_prmyscdyupload_t
        WHERE f_year='$select_pre_year' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d') $condition $wh AND division not in ('-','')
        AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_free_units != 0) $notin
        GROUP BY
        f_year HAVING
        primary_dis != 0 OR sales_dist != 0 OR sales_stock != 0");

            $Grand_total_cfy = $this->data['grand_total_cfy'];
            $Grand_total_pfy = $this->data['grand_total_pfy'];

            $GrandData = [];

            foreach ($Grand_total_cfy as $saleRow) {
                foreach ($Grand_total_pfy as $salepreRow) {

                    $primary_dis_ratio = $salepreRow->primary_dis != 0 ? (($saleRow->primary_dis / $salepreRow->primary_dis - 1) * 100) : null;
                    $sales_dist_ratio = $salepreRow->sales_dist != 0 ? (($saleRow->sales_dist / $salepreRow->sales_dist - 1) * 100) : null;
                    $purchase_ratio = $salepreRow->sales_stock != 0 ? (($saleRow->sales_stock / $salepreRow->sales_stock - 1) * 100) : null;

                    $sale_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_dist / $saleRow->primary_dis) * 100) : null;
                    $stock_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_stock / $saleRow->primary_dis) * 100) : null;

                    $GrandData[] = [

                        'primary_dis_ratio' => $primary_dis_ratio !== null ? number_format($primary_dis_ratio, 0) . "%" : "0",
                        'sales_dist_ratio' => $sales_dist_ratio !== null ? number_format($sales_dist_ratio, 0) . "%" : "0",
                        'purchase_ratio' => $purchase_ratio !== null ? number_format($purchase_ratio, 0) . "%" : "0",
                        'slae_ratio' => $sale_ratio !== null ? number_format($sale_ratio, 0) . "%" : "0",
                        'stock_ratio' => $stock_ratio !== null ? number_format($stock_ratio, 0) . "%" : "0",
                    ];
                }
            }

            $this->data['grand_value'] = $GrandData;
            
        } else {

        $this->data['all_ind_sal'] = \DB::select("SELECT
        f_year,
        division,
        product_pack_name,
        ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_free_units ELSE 0 END), 0) as primary_dis,
        ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_free_units ELSE 0 END), 0) as sales_dist,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_free_units ELSE 0 END), 0) as sales_stock
        FROM
        sd_prmyscdyupload_t
        WHERE f_year='$cur_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_mon-01', '%Y-%m-%d')  $wh AND division not in ('-','')
        AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_free_units != 0) $notin
        GROUP BY
         division, product_pack_name,f_year HAVING
        (primary_dis != 0 OR sales_dist != 0 OR sales_stock != 0)
        UNION ALL 
        SELECT
        f_year,
        division,
        product_pack_name,
        ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_free_units ELSE 0 END), 0) as primary_dis,
        ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_free_units ELSE 0 END), 0) as sales_dist,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_free_units ELSE 0 END), 0) as sales_stock
        FROM
        sd_prmyscdyupload_t
        WHERE f_year='$pre_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_last_y-$last_mon-01', '%Y-%m-%d')  $wh AND division not in ('-','')
        AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_free_units != 0) $notin
        GROUP BY
        division, product_pack_name,f_year HAVING
        (primary_dis != 0 OR sales_dist != 0 OR sales_stock != 0)");

            // ratio
        $this->data['all_ind_sal_cfy'] = \DB::select("SELECT
        f_year,
        division,
        product_pack_name,
        ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_free_units ELSE 0 END), 0) as primary_dis,
        ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_free_units ELSE 0 END), 0) as sales_dist,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_free_units ELSE 0 END), 0) as sales_stock
        FROM
        sd_prmyscdyupload_t
        WHERE f_year='$cur_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_mon-01', '%Y-%m-%d')  $wh AND division not in ('-','')
        AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_free_units != 0) $notin
        GROUP BY
        product_pack_name,f_year HAVING
        (primary_dis != 0 OR sales_dist != 0 OR sales_stock != 0)");

        $this->data['all_ind_sal_pre'] = \DB::select("SELECT
        f_year,
        division,
        product_pack_name,
        ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_free_units ELSE 0 END), 0) as primary_dis,
        ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_free_units ELSE 0 END), 0) as sales_dist,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_free_units ELSE 0 END), 0) as sales_stock
        FROM
        sd_prmyscdyupload_t
        WHERE f_year='$pre_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_last_y-$last_mon-01', '%Y-%m-%d')  $wh AND division not in ('-','')
        AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_free_units != 0) $notin
        GROUP BY
          product_pack_name,f_year HAVING
        (primary_dis != 0 OR sales_dist != 0 OR sales_stock != 0)");

            $Product_data_cfy = $this->data['all_ind_sal_cfy'];
            $Product_data_pfy = $this->data['all_ind_sal_pre'];

            $comparisonData = [];

            foreach ($Product_data_cfy as $saleRow) {
                foreach ($Product_data_pfy as $salepreRow) {
                    if ($saleRow->product_pack_name == $salepreRow->product_pack_name) {

                        $primary_dis_ratio = $salepreRow->primary_dis != 0 ? (($saleRow->primary_dis / $salepreRow->primary_dis - 1) * 100) : null;
                        $sales_dist_ratio = $salepreRow->sales_dist != 0 ? (($saleRow->sales_dist / $salepreRow->sales_dist - 1) * 100) : null;
                        $purchase_ratio = $salepreRow->sales_stock != 0 ? (($saleRow->sales_stock / $salepreRow->sales_stock - 1) * 100) : null;
                        $sale_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_dist / $saleRow->primary_dis) * 100) : null;
                        $stock_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_stock / $saleRow->primary_dis) * 100) : null;

                        $comparisonData[] = [
                            
                            'product' => $saleRow->product_pack_name,
                            'primary_dis_ratio' => $primary_dis_ratio !== null ? number_format($primary_dis_ratio, 0) . "%" : "0",
                            'sales_dist_ratio' => $sales_dist_ratio !== null ? number_format($sales_dist_ratio, 0) . "%" : "0",
                            'purchase_ratio' => $purchase_ratio !== null ? number_format($purchase_ratio, 0) . "%" : "0",
                            'slae_ratio' => $sale_ratio !== null ? number_format($sale_ratio, 0) . "%" : "0",
                            'stock_ratio' => $stock_ratio !== null ? number_format($stock_ratio, 0) . "%" : "0",
                            
                        ];

                        break;
                    }
                }
            }

            $this->data['sale_value'] = $comparisonData;
           // dd($this->data['sale_value'] );
            // ratio of grand total
            $this->data['grand_total_cfy'] = \DB::select("SELECT
        f_year,
        division,
        product_pack_name,
        ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_free_units ELSE 0 END), 0) as primary_dis,
        ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_free_units ELSE 0 END), 0) as sales_dist,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_free_units ELSE 0 END), 0) as sales_stock
        FROM
        sd_prmyscdyupload_t
        WHERE f_year='$cur_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_mon-01', '%Y-%m-%d')  $wh AND division not in ('-','')
        AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_free_units != 0) $notin
        GROUP BY
        f_year HAVING
        (primary_dis != 0 OR sales_dist != 0 OR sales_stock != 0)");

            $this->data['grand_total_pfy'] = \DB::select("SELECT
        f_year,
        division,
        product_pack_name,
        ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_free_units ELSE 0 END), 0) as primary_dis,
        ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_free_units ELSE 0 END), 0) as sales_dist,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_free_units ELSE 0 END), 0) as sales_stock
        FROM
        sd_prmyscdyupload_t
        WHERE f_year='$pre_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_last_y-$last_mon-01', '%Y-%m-%d')  $wh AND division not in ('-','')
        AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_free_units != 0) $notin
        GROUP BY
        f_year HAVING
        (primary_dis != 0 OR sales_dist != 0 OR sales_stock != 0)");

            $Grand_total_cfy = $this->data['grand_total_cfy'];
            $Grand_total_pfy = $this->data['grand_total_pfy'];

            $GrandData = [];

            foreach ($Grand_total_cfy as $saleRow) {
                foreach ($Grand_total_pfy as $salepreRow) {


                    $primary_dis_ratio = $salepreRow->primary_dis != 0 ? (($saleRow->primary_dis / $salepreRow->primary_dis - 1) * 100) : null;
                    $sales_dist_ratio = $salepreRow->sales_dist != 0 ? (($saleRow->sales_dist / $salepreRow->sales_dist - 1) * 100) : null;
                    $purchase_ratio = $salepreRow->sales_stock != 0 ? (($saleRow->sales_stock / $salepreRow->sales_stock - 1) * 100) : null;
                    $sale_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_dist / $saleRow->primary_dis) * 100) : null;
                    $stock_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_stock / $saleRow->primary_dis) * 100) : null;

                    $GrandData[] = [

                        'primary_dis_ratio' => $primary_dis_ratio !== null ? number_format($primary_dis_ratio, 0) . "%" : "0",
                        'sales_dist_ratio' => $sales_dist_ratio !== null ? number_format($sales_dist_ratio, 0) . "%" : "0",
                        'purchase_ratio' => $purchase_ratio !== null ? number_format($purchase_ratio, 0) . "%" : "0",
                        'slae_ratio' => $sale_ratio !== null ? number_format($sale_ratio, 0) . "%" : "0",
                        'stock_ratio' => $stock_ratio !== null ? number_format($stock_ratio, 0) . "%" : "0",
                    ];
                }
            }

            $this->data['grand_value'] = $GrandData;
            
            //
        }

        return view('secondarysales.productreport', $this->data);
    
    }

    // trans product wise
    public function ZoneProductwise(Request $request)
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
                    $wh = " AND sd_prmyscdyupload_t.region IN ($emp_region)";  
                } else {
                    $wh = " AND sd_prmyscdyupload_t.region IN ('')";
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
                    $wh = "  AND sd_prmyscdyupload_t.zone ='$emp_zone'";
                } else {
                    $wh = " AND sd_prmyscdyupload_t.zone IN ('')";
                }
            }
            
                      // raja ram only show andra state purpose
            
               if($emp_id == '94'){
                   
                   $wh = " AND sd_prmyscdyupload_t.state IN ('ANDHRA PRADESH')";
                   
               }
        }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";
        // end
        $l_dbmon = \DB::select("SELECT  month FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $pre_last_y = $last_y - 1;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $this->data['last_month'] = date('M', strtotime($last_m));
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['divisions'] = \DB::select("SELECT DISTINCT division from  `sd_prmyscdyupload_t` where division not in ('-','GIFTS','OTHERS','VISUAL AID','POSTERS','FLYERS','BROUCHERS','GIFT ITEMS','')");
        $division = $request->input('division');
        $this->data['select_year'] = $last_y - 1;
        $condition = '';
        $last_data = \DB::select("SELECT  month_y FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;

        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
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

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            $this->data['pre_fy_year'] = $select_pre_year;
            $this->data['select_year'] = $select_year - 1;
            $this->data['last_month'] = date('M', strtotime($select_date));
            // if user select zone or ___ search 
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = $last_mon;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
                $this->data['select_year'] = $last_y - 1;
                $this->data['last_month'] = $l_dbmon[0]->month;
                $pre_year = $last_y - 1;
            }

            if ($zone != '') {
                $condition = "AND zone='$zone'";
            }
            if ($region != '') {
                $condition = "AND region='$region'";
            }
            if ($division != '') {
                $condition = "AND division = '$division'";
            }
            if ($zone != '' && $region != '') {
                $condition = "AND zone='$zone' AND region='$region'";
            }
            if ($zone != '' && $division != '') {
                $condition = "AND zone='$zone' AND division='$division'";
            }
            if ($region != '' && $division != '') {
                $condition = "AND region='$region' AND division='$division'";
            }
            if ($zone != '' && $region != '' && $division != '') {
                $condition = "AND zone='$zone' AND region='$region' AND division='$division'";
            }

            //   product wise table
            $this->data['all_ind_sal'] = \DB::select("SELECT
        product_name as change_name,f_year,product_pack_name,
        ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_unit ELSE 0 END), 0) as primary_dis,
        ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_unit ELSE 0 END), 0) as sales_dist,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sales_stock
        FROM
        sd_prmyscdyupload_t
        WHERE
        (f_year='$fy_year' OR f_year='$select_pre_year') AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
         AND product_pack_name IN ('JRKS DANO ACTIVE AD OIL 100 ML','JRKS DANO ACTIVE AD OIL 1000 ML','JRKS EVEFRESH SKINBRITE CREAM 25 GM','JRKS EVEFRESH SKINBRITE CREAM 500 GM','JRKS IMMDIS IMMUNITY DROPS 30 ML','JRKS KESH RAKSHA HAIR VITALIZER OIL 100 ML','JRKS LUMINA AD HERBAL SHAMPOO 100 ML','JRKS PSOROLIN DERMA SKIN CARE SOAP 75 GM','PSOROLIN B OINTMENT')
        $condition $wh
        AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_unit != 0) $notin
        GROUP BY
        f_year, division, product_pack_name HAVING
        primary_dis != 0 OR sales_dist != 0 OR sales_stock != 0");

            // ratio


            $this->data['all_ind_sal_cfy'] = \DB::select("SELECT
        product_name as change_name,f_year,product_pack_name,
        ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_value ELSE 0 END), 0) as primary_dis,
        ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_value ELSE 0 END), 0) as sales_dist,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_value ELSE 0 END), 0) as sales_stock
        FROM
        sd_prmyscdyupload_t
        WHERE f_year='$fy_year' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
        AND product_pack_name IN ('JRKS DANO ACTIVE AD OIL 100 ML','JRKS DANO ACTIVE AD OIL 1000 ML','JRKS EVEFRESH SKINBRITE CREAM 25 GM','JRKS EVEFRESH SKINBRITE CREAM 500 GM','JRKS IMMDIS IMMUNITY DROPS 30 ML','JRKS KESH RAKSHA HAIR VITALIZER OIL 100 ML','JRKS LUMINA AD HERBAL SHAMPOO 100 ML','JRKS PSOROLIN DERMA SKIN CARE SOAP 75 GM','PSOROLIN B OINTMENT')
        $condition $wh
        AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_value != 0) $notin
        GROUP BY
        product_pack_name");


            $this->data['all_ind_sal_pre'] = \DB::select("SELECT
        product_name as change_name,f_year,product_pack_name,
        ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_unit ELSE 0 END), 0) as primary_dis,
        ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_unit ELSE 0 END), 0) as sales_dist,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sales_stock
        FROM
        sd_prmyscdyupload_t
        WHERE f_year='$select_pre_year' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
        AND product_pack_name IN ('JRKS DANO ACTIVE AD OIL 100 ML','JRKS DANO ACTIVE AD OIL 1000 ML','JRKS EVEFRESH SKINBRITE CREAM 25 GM','JRKS EVEFRESH SKINBRITE CREAM 500 GM','JRKS IMMDIS IMMUNITY DROPS 30 ML','JRKS KESH RAKSHA HAIR VITALIZER OIL 100 ML','JRKS LUMINA AD HERBAL SHAMPOO 100 ML','JRKS PSOROLIN DERMA SKIN CARE SOAP 75 GM','PSOROLIN B OINTMENT')
        $condition $wh
        AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_unit != 0) $notin
        GROUP BY
        product_pack_name ");


            $Product_data_cfy = $this->data['all_ind_sal_cfy'];
            $Product_data_pfy = $this->data['all_ind_sal_pre'];

            $comparisonData = [];

            foreach ($Product_data_cfy as $saleRow) {
                foreach ($Product_data_pfy as $salepreRow) {
                    if ($saleRow->product_pack_name == $salepreRow->product_pack_name) {


                        $primary_dis_ratio = $salepreRow->primary_dis != 0 ? (($saleRow->primary_dis / $salepreRow->primary_dis - 1) * 100) : null;
                        $sales_dist_ratio = $salepreRow->sales_dist != 0 ? (($saleRow->sales_dist / $salepreRow->sales_dist - 1) * 100) : null;
                        $purchase_ratio = $salepreRow->sales_stock != 0 ? (($saleRow->sales_stock / $salepreRow->sales_stock - 1) * 100) : null;

                        $sale_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_dist / $saleRow->primary_dis) * 100) : null;
                        $stock_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_stock / $saleRow->primary_dis) * 100) : null;

                        $comparisonData[] = [
                            'product' => $saleRow->product_pack_name,
                            'primary_dis_ratio' => $primary_dis_ratio !== null ? number_format($primary_dis_ratio, 0) . "%" : "0",
                            'sales_dist_ratio' => $sales_dist_ratio !== null ? number_format($sales_dist_ratio, 0) . "%" : "0",
                            'purchase_ratio' => $purchase_ratio !== null ? number_format($purchase_ratio, 0) . "%" : "0",
                            'slae_ratio' => $sale_ratio !== null ? number_format($sale_ratio, 0) . "%" : "0",
                            'stock_ratio' => $stock_ratio !== null ? number_format($stock_ratio, 0) . "%" : "0",
                        ];

                        break;
                    }
                }
            }

            $this->data['sale_value'] = $comparisonData;


            // ratio of grand total
            $this->data['grand_total_cfy'] = \DB::select("SELECT
        product_name as change_name,f_year,product_pack_name,
        ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_unit ELSE 0 END), 0) as primary_dis,
        ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_unit ELSE 0 END), 0) as sales_dist,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sales_stock
        FROM
        sd_prmyscdyupload_t
        WHERE f_year='$fy_year' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
        AND product_pack_name IN ('JRKS DANO ACTIVE AD OIL 100 ML','JRKS DANO ACTIVE AD OIL 1000 ML','JRKS EVEFRESH SKINBRITE CREAM 25 GM','JRKS EVEFRESH SKINBRITE CREAM 500 GM','JRKS IMMDIS IMMUNITY DROPS 30 ML','JRKS KESH RAKSHA HAIR VITALIZER OIL 100 ML','JRKS LUMINA AD HERBAL SHAMPOO 100 ML','JRKS PSOROLIN DERMA SKIN CARE SOAP 75 GM','PSOROLIN B OINTMENT')
        $condition $wh
        AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_unit != 0) $notin
        GROUP BY
        f_year");


            $this->data['grand_total_pfy'] = \DB::select("SELECT
        product_name as change_name,f_year,product_pack_name,
        ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTORS' and type_of_data = 'sales') THEN sales_unit ELSE 0 END), 0) as primary_dis,
        ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN sales_unit ELSE 0 END), 0) as sales_dist,
        ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sales_stock
        FROM
        sd_prmyscdyupload_t
        WHERE f_year='$select_pre_year' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
        AND product_pack_name IN ('JRKS DANO ACTIVE AD OIL 100 ML','JRKS DANO ACTIVE AD OIL 1000 ML','JRKS EVEFRESH SKINBRITE CREAM 25 GM','JRKS EVEFRESH SKINBRITE CREAM 500 GM','JRKS IMMDIS IMMUNITY DROPS 30 ML','JRKS KESH RAKSHA HAIR VITALIZER OIL 100 ML','JRKS LUMINA AD HERBAL SHAMPOO 100 ML','JRKS PSOROLIN DERMA SKIN CARE SOAP 75 GM','PSOROLIN B OINTMENT')
        $condition $wh
        AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_value != 0) $notin
        GROUP BY
        f_year");


            $Grand_total_cfy = $this->data['grand_total_cfy'];
            $Grand_total_pfy = $this->data['grand_total_pfy'];

            $GrandData = [];

            foreach ($Grand_total_cfy as $saleRow) {
                foreach ($Grand_total_pfy as $salepreRow) {


                    $primary_dis_ratio = $salepreRow->primary_dis != 0 ? (($saleRow->primary_dis / $salepreRow->primary_dis - 1) * 100) : null;
                    $sales_dist_ratio = $salepreRow->sales_dist != 0 ? (($saleRow->sales_dist / $salepreRow->sales_dist - 1) * 100) : null;
                    $purchase_ratio = $salepreRow->sales_stock != 0 ? (($saleRow->sales_stock / $salepreRow->sales_stock - 1) * 100) : null;

                    $sale_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_dist / $saleRow->primary_dis) * 100) : null;
                    $stock_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_stock / $saleRow->primary_dis) * 100) : null;

                    $GrandData[] = [

                        'primary_dis_ratio' => $primary_dis_ratio !== null ? number_format($primary_dis_ratio, 0) . "%" : "0",
                        'sales_dist_ratio' => $sales_dist_ratio !== null ? number_format($sales_dist_ratio, 0) . "%" : "0",
                        'purchase_ratio' => $purchase_ratio !== null ? number_format($purchase_ratio, 0) . "%" : "0",
                        'slae_ratio' => $sale_ratio !== null ? number_format($sale_ratio, 0) . "%" : "0",
                        'stock_ratio' => $stock_ratio !== null ? number_format($stock_ratio, 0) . "%" : "0",
                    ];
                }
            }

            $this->data['grand_value'] = $GrandData;

            //  default values report product wise     
        } else {

            //   product wise table
            $this->data['all_ind_sal'] = \DB::select("SELECT
    product_name as change_name,f_year,product_pack_name,
    ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTOR' and type_of_data = 'sales') THEN sales_unit ELSE 0 END), 0) as primary_dis,
    ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTORS' THEN sales_unit ELSE 0 END), 0) as sales_dist,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sales_stock
FROM
    sd_prmyscdyupload_t
WHERE f_year='$cur_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_mon-01', '%Y-%m-%d')
AND product_pack_name IN ('JRKS DANO ACTIVE AD OIL 100 ML','JRKS DANO ACTIVE AD OIL 1000 ML','JRKS EVEFRESH SKINBRITE CREAM 25 GM','JRKS EVEFRESH SKINBRITE CREAM 500 GM','JRKS IMMDIS IMMUNITY DROPS 30 ML','JRKS KESH RAKSHA HAIR VITALIZER OIL 100 ML','JRKS LUMINA AD HERBAL SHAMPOO 100 ML','JRKS PSOROLIN DERMA SKIN CARE SOAP 75 GM','PSOROLIN B OINTMENT')
  $wh  AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_unit != 0) $notin
GROUP BY
    product_pack_name
    UNION ALL
    SELECT
    product_name as change_name,f_year,product_pack_name,
    ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTOR' and type_of_data = 'sales') THEN sales_unit ELSE 0 END), 0) as primary_dis,
    ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTORS' THEN sales_unit ELSE 0 END), 0) as sales_dist,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sales_stock
FROM
    sd_prmyscdyupload_t
WHERE f_year='$pre_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_last_y-$last_mon-01', '%Y-%m-%d')
AND product_pack_name IN ('JRKS DANO ACTIVE AD OIL 100 ML','JRKS DANO ACTIVE AD OIL 1000 ML','JRKS EVEFRESH SKINBRITE CREAM 25 GM','JRKS EVEFRESH SKINBRITE CREAM 500 GM','JRKS IMMDIS IMMUNITY DROPS 30 ML','JRKS KESH RAKSHA HAIR VITALIZER OIL 100 ML','JRKS LUMINA AD HERBAL SHAMPOO 100 ML','JRKS PSOROLIN DERMA SKIN CARE SOAP 75 GM','PSOROLIN B OINTMENT')
  $wh  AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_unit != 0) $notin
GROUP BY
     product_pack_name");

            // ratio
            $this->data['all_ind_sal_cfy'] = \DB::select("SELECT
    product_name as change_name,f_year,product_pack_name,
    ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTOR' and type_of_data = 'sales') THEN sales_unit ELSE 0 END), 0) as primary_dis,
    ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTORS' THEN sales_unit ELSE 0 END), 0) as sales_dist,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sales_stock
FROM
    sd_prmyscdyupload_t
WHERE f_year='$cur_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_mon-01', '%Y-%m-%d')
AND product_pack_name IN ('JRKS DANO ACTIVE AD OIL 100 ML','JRKS DANO ACTIVE AD OIL 1000 ML','JRKS EVEFRESH SKINBRITE CREAM 25 GM','JRKS EVEFRESH SKINBRITE CREAM 500 GM','JRKS IMMDIS IMMUNITY DROPS 30 ML','JRKS KESH RAKSHA HAIR VITALIZER OIL 100 ML','JRKS LUMINA AD HERBAL SHAMPOO 100 ML','JRKS PSOROLIN DERMA SKIN CARE SOAP 75 GM','PSOROLIN B OINTMENT')
  $wh  AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_unit != 0) $notin
GROUP BY
    product_pack_name");

            $this->data['all_ind_sal_pre'] = \DB::select("SELECT
    product_name as change_name,f_year,product_pack_name,
    ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTOR' and type_of_data = 'sales') THEN sales_unit ELSE 0 END), 0) as primary_dis,
    ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTORS' THEN sales_unit ELSE 0 END), 0) as sales_dist,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sales_stock
FROM
    sd_prmyscdyupload_t
WHERE f_year='$pre_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_last_y-$last_mon-01', '%Y-%m-%d')
AND product_pack_name IN ('JRKS DANO ACTIVE AD OIL 100 ML','JRKS DANO ACTIVE AD OIL 1000 ML','JRKS EVEFRESH SKINBRITE CREAM 25 GM','JRKS EVEFRESH SKINBRITE CREAM 500 GM','JRKS IMMDIS IMMUNITY DROPS 30 ML','JRKS KESH RAKSHA HAIR VITALIZER OIL 100 ML','JRKS LUMINA AD HERBAL SHAMPOO 100 ML','JRKS PSOROLIN DERMA SKIN CARE SOAP 75 GM','PSOROLIN B OINTMENT')
  $wh  AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_unit != 0) $notin
GROUP BY
     product_pack_name");

            $Product_data_cfy = $this->data['all_ind_sal_cfy'];
            $Product_data_pfy = $this->data['all_ind_sal_pre'];

            $comparisonData = [];

            foreach ($Product_data_cfy as $saleRow) {
                foreach ($Product_data_pfy as $salepreRow) {
                    if ($saleRow->product_pack_name == $salepreRow->product_pack_name) {


                        $primary_dis_ratio = $salepreRow->primary_dis != 0 ? (($saleRow->primary_dis / $salepreRow->primary_dis - 1) * 100) : null;
                        $sales_dist_ratio = $salepreRow->sales_dist != 0 ? (($saleRow->sales_dist / $salepreRow->sales_dist - 1) * 100) : null;
                        $purchase_ratio = $salepreRow->sales_stock != 0 ? (($saleRow->sales_stock / $salepreRow->sales_stock - 1) * 100) : null;
                        $sale_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_dist / $saleRow->primary_dis) * 100) : null;
                        $stock_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_stock / $saleRow->primary_dis) * 100) : null;

                        $comparisonData[] = [
                            'product' => $saleRow->product_pack_name,
                            'primary_dis_ratio' => $primary_dis_ratio !== null ? number_format($primary_dis_ratio, 0) . "%" : "0",
                            'sales_dist_ratio' => $sales_dist_ratio !== null ? number_format($sales_dist_ratio, 0) . "%" : "0",
                            'purchase_ratio' => $purchase_ratio !== null ? number_format($purchase_ratio, 0) . "%" : "0",
                            'slae_ratio' => $sale_ratio !== null ? number_format($sale_ratio, 0) . "%" : "0",
                            'stock_ratio' => $stock_ratio !== null ? number_format($stock_ratio, 0) . "%" : "0",
                        ];

                        break;
                    }
                }
            }

            $this->data['sale_value'] = $comparisonData;


            // ratio of grand total
            $this->data['grand_total_cfy'] = \DB::select("SELECT
    product_name as change_name,f_year,product_pack_name,
    ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTOR' and type_of_data = 'sales') THEN sales_unit ELSE 0 END), 0) as primary_dis,
    ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTORS' THEN sales_unit ELSE 0 END), 0) as sales_dist,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sales_stock
    FROM
        sd_prmyscdyupload_t
    WHERE f_year='$cur_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_mon-01', '%Y-%m-%d')
    AND product_pack_name IN ('JRKS DANO ACTIVE AD OIL 100 ML','JRKS DANO ACTIVE AD OIL 1000 ML','JRKS EVEFRESH SKINBRITE CREAM 25 GM','JRKS EVEFRESH SKINBRITE CREAM 500 GM','JRKS IMMDIS IMMUNITY DROPS 30 ML','JRKS KESH RAKSHA HAIR VITALIZER OIL 100 ML','JRKS LUMINA AD HERBAL SHAMPOO 100 ML','JRKS PSOROLIN DERMA SKIN CARE SOAP 75 GM','PSOROLIN B OINTMENT')
    $wh  AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_value != 0) $notin
    GROUP BY
        f_year HAVING
        primary_dis != 0 OR sales_dist != 0 OR sales_stock != 0");

            $this->data['grand_total_pfy'] = \DB::select("SELECT
    product_name as change_name,f_year,product_pack_name,
    ROUND(SUM(CASE WHEN (type_d_s = 'DISTRIBUTOR' and type_of_data = 'sales') THEN sales_unit ELSE 0 END), 0) as primary_dis,
    ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTORS' THEN sales_unit ELSE 0 END), 0) as sales_dist,
    ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN sales_unit ELSE 0 END), 0) as sales_stock
    FROM
        sd_prmyscdyupload_t
    WHERE f_year='$pre_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_last_y-$last_mon-01', '%Y-%m-%d')
    AND product_pack_name IN ('JRKS DANO ACTIVE AD OIL 100 ML','JRKS DANO ACTIVE AD OIL 1000 ML','JRKS EVEFRESH SKINBRITE CREAM 25 GM','JRKS EVEFRESH SKINBRITE CREAM 500 GM','JRKS IMMDIS IMMUNITY DROPS 30 ML','JRKS KESH RAKSHA HAIR VITALIZER OIL 100 ML','JRKS LUMINA AD HERBAL SHAMPOO 100 ML','JRKS PSOROLIN DERMA SKIN CARE SOAP 75 GM','PSOROLIN B OINTMENT')
    $wh   AND (purchase_stock_value != 0 OR sales_value != 0 OR sales_value != 0) $notin
    GROUP BY
        f_year HAVING
        primary_dis != 0 OR sales_dist != 0 OR sales_stock != 0");

            $Grand_total_cfy = $this->data['grand_total_cfy'];
            $Grand_total_pfy = $this->data['grand_total_pfy'];

            $GrandData = [];

            foreach ($Grand_total_cfy as $saleRow) {
                foreach ($Grand_total_pfy as $salepreRow) {

                    $primary_dis_ratio = $salepreRow->primary_dis != 0 ? (($saleRow->primary_dis / $salepreRow->primary_dis - 1) * 100) : null;
                    $sales_dist_ratio = $salepreRow->sales_dist != 0 ? (($saleRow->sales_dist / $salepreRow->sales_dist - 1) * 100) : null;
                    $purchase_ratio = $salepreRow->sales_stock != 0 ? (($saleRow->sales_stock / $salepreRow->sales_stock - 1) * 100) : null;
                    $sale_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_dist / $saleRow->primary_dis) * 100) : null;
                    $stock_ratio = $saleRow->primary_dis != 0 ? (($saleRow->sales_stock / $saleRow->primary_dis) * 100) : null;

                    $GrandData[] = [

                        'primary_dis_ratio' => $primary_dis_ratio !== null ? number_format($primary_dis_ratio, 0) . "%" : "0",
                        'sales_dist_ratio' => $sales_dist_ratio !== null ? number_format($sales_dist_ratio, 0) . "%" : "0",
                        'purchase_ratio' => $purchase_ratio !== null ? number_format($purchase_ratio, 0) . "%" : "0",
                        'slae_ratio' => $sale_ratio !== null ? number_format($sale_ratio, 0) . "%" : "0",
                        'stock_ratio' => $stock_ratio !== null ? number_format($stock_ratio, 0) . "%" : "0",
                    ];
                }
            }

            $this->data['grand_value'] = $GrandData;
        }


        return view('secondarysales.zoneproductreport', $this->data);
    }

    // last 6 months sales value
    public function LastSale(Request $request)
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
                    $wh = " AND sd_prmyscdyupload_t.region IN ($emp_region)";  
                } else {
                    $wh = " AND sd_prmyscdyupload_t.region IN ('')";
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
                    $wh = "  AND sd_prmyscdyupload_t.zone ='$emp_zone'";
                } else {
                    $wh = " AND sd_prmyscdyupload_t.zone IN ('')";
                }
            }
            
               // raja ram only show andra state purpose
            
               if($emp_id == '94'){
                   
                   $wh = " AND sd_prmyscdyupload_t.state IN ('ANDHRA PRADESH')";
                   
               }
        }

        // end
        $notin = ""; // for not in query purpose
        $notin = " AND zone NOT IN('EXPORT','LOCAL','GOVT SUPPLY') AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";
        $l_dbmon = \DB::select("SELECT  month FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $this->data['last_yr'] = $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));

        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
        $this->data['states'] = \DB::select("SELECT DISTINCT state as state from `sd_prmyscdyupload_t` where state NOT IN('EXPORT','LOCAL','GOVT SUPPLY') $wh $notin");
        $state = $request->input('state');
        $condition = '';
        $this->data['select_year'] = $last_y;
        // defalt  fy_years start
        $this->data['fy_year'] = \DB::select("SELECT f_year FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $cur_fyear = $this->data['fy_year'][0]->f_year;
        $last_data = \DB::select("SELECT  month_y FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
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

        // zone based days claculation
        $zonee = '';

        if ($wh != '') {
            if ($emp_reg == '41') {
                $zonee = " AND region IN ($emp_region)";
            } else {
                $zonee = " AND zone = '$emp_zone' ";
            }
        }

        if ($zone != '') {

            $zonee = " AND zone = '$zone' ";
        }

        if ($region != '') {
            $zonee = " AND region = '$region' ";
        }


        // if user select date search 
        if ($zone != '' || $select_date != '' || $region != '' || $state !='') {

            $this->data['select_year'] = $select_year;
            $select_mon = date('M', strtotime($select_date));

            $this->data['cur_fy_year'] = $fy_year;
            $this->data['mon_yr'] = $select_mon . "'" . $select_year;
            // if user select zone or ___ search 
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

            $this->data['last_sale'] = \DB::select("SELECT
             f_year,
             month,
             c_year,
             month_y,
             ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN
        closing_stock_value ELSE 0 END), 0) as distributor,
             ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN closing_stock_value
        ELSE 0 END), 0) as stockist,
             ROUND(
                 (SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN closing_stock_value
        ELSE 0 END) /
                 (COALESCE(
            (SELECT SUM(dis.sales_value)
             FROM `sd_prmyscdyupload_t` dis
             WHERE dis.type_d_s = 'distributor'
             $zonee
               AND STR_TO_DATE(CONCAT(dis.c_year, '-', dis.month, '-01'),
        '%Y-%b-%d')
                   BETWEEN DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d'), INTERVAL
        3 MONTH)
                       AND DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d'), INTERVAL
        1 MONTH)), 0)/3)*30),0) as distributor_days,
             ROUND(
                 (SUM(CASE WHEN type_d_s = 'STOCKIST' THEN closing_stock_value
        ELSE 0 END) /
                 (COALESCE(
            (SELECT SUM(dis.sales_value)
             FROM `sd_prmyscdyupload_t` dis
             WHERE dis.type_d_s = 'STOCKIST'
             $zonee
               AND STR_TO_DATE(CONCAT(dis.c_year, '-', dis.month, '-01'),
        '%Y-%b-%d')
                   BETWEEN DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d'), INTERVAL
        3 MONTH)
                       AND DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d'), INTERVAL
        1 MONTH)), 0)/3)*30),0) as stockist_days
        FROM sd_prmyscdyupload_t
        WHERE
         1=1 $condition $wh

           $notin  AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >=
        DATE_SUB(STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d'), INTERVAL 5 MONTH)
             AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <=
        STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
        GROUP BY MONTH,c_year
        ORDER BY STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        
        } else {

            $this->data['last_sale'] = \DB::select("SELECT
             f_year,
             month,
             c_year,
             month_y,
             ROUND(SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN
        closing_stock_value ELSE 0 END), 0) as distributor,
             ROUND(SUM(CASE WHEN type_d_s = 'STOCKIST' THEN closing_stock_value
        ELSE 0 END), 0) as stockist,
             ROUND(
                 (SUM(CASE WHEN type_d_s = 'DISTRIBUTOR' THEN closing_stock_value
        ELSE 0 END) /
                 (COALESCE(
            (SELECT SUM(dis.sales_value)
             FROM `sd_prmyscdyupload_t` dis
             WHERE dis.type_d_s = 'distributor' $zonee
               AND STR_TO_DATE(CONCAT(dis.c_year, '-', dis.month, '-01'),
        '%Y-%b-%d')
                   BETWEEN DATE_SUB(STR_TO_DATE('$last_y-$last_mon-01', '%Y-%m-%d'), INTERVAL
        3 MONTH)
                       AND DATE_SUB(STR_TO_DATE('$last_y-$last_mon-01', '%Y-%m-%d'), INTERVAL
        1 MONTH)), 0)/3)*30),0) as distributor_days,
             ROUND(
                 (SUM(CASE WHEN type_d_s = 'STOCKIST' THEN closing_stock_value
        ELSE 0 END) /
                 (COALESCE(
            (SELECT SUM(dis.sales_value)
             FROM `sd_prmyscdyupload_t` dis
             WHERE dis.type_d_s = 'STOCKIST' $zonee
               AND STR_TO_DATE(CONCAT(dis.c_year, '-', dis.month, '-01'),
        '%Y-%b-%d')
                   BETWEEN DATE_SUB(STR_TO_DATE('$last_y-$last_mon-01', '%Y-%m-%d'), INTERVAL
        3 MONTH)
                       AND DATE_SUB(STR_TO_DATE('$last_y-$last_mon-01', '%Y-%m-%d'), INTERVAL
        1 MONTH)), 0)/3)*30),0) as stockist_days
        FROM sd_prmyscdyupload_t
        WHERE
             1=1 $wh $notin
             
             AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') >=
        DATE_SUB(STR_TO_DATE('$last_y-$last_mon-01', '%Y-%m-%d'), INTERVAL 5 MONTH)
             AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <=
        STR_TO_DATE('$last_y-$last_mon-01', '%Y-%m-%d')
        GROUP BY MONTH,c_year
        ORDER BY STR_TO_DATE(CONCAT('001', '01', ' 01'), '%Y %M %d')");
        
        }

        return view('secondarysales.lastsalereport', $this->data);
    }
}
