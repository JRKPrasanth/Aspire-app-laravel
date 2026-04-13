<?php

namespace App\Http\Controllers;
use App\Misviewers;
use Illuminate\Http\Request;
use Validator, DB, Input;
use Session, Config;
use DateTime, File;

class BusinessmisController extends Controller

{

	public function __construct(){
        
		$this->pageModule="primarybusinessmis";	
		$this->data=array();
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();
       
    }
	
    public function Zonewise(Request $request)
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
        $wh1  = '';
        
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
        if ($depart == "14" && $emp_id != '121') {

            // RSM region compare purpose
            if ($emp_reg == '41') {

                $emp_region = '';
                // emp region 
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name'");

                if (!empty($em_region)) {
                    $regions = array_column($em_region, 'region');
                    $emp_region = "'" . implode("','", $regions) . "'";
                    $wh = " AND sd_prmyscdyupload_t.region IN ($emp_region)";
                    $wh1 = " AND sd_docupload_t.region IN ($emp_region)";
                } else {
                    $wh = " AND sd_prmyscdyupload_t.region IN ('')";
                    $wh1 = " AND sd_docupload_t.region IN ('')";
                }
                //end
            } else {
                //others

                $emp_zone = '';
                // emp zone 
                $em_zone = \DB::select("SELECT zone from sd_mar_employee_detail_t where employee_name='$emp_name'");

                if (!empty($em_zone)) {
                    if (isset($em_zone[0]->zone) && $em_zone[0]->zone != '') {
                        $emp_zone = $em_zone[0]->zone;
                    } else {
                        $emp_zone = '';
                    }
                }

                if (!empty($emp_zone)) {
                    $wh = "  AND sd_prmyscdyupload_t.zone ='$emp_zone'";
                    $wh1 = "  AND sd_docupload_t.zone ='$emp_zone'";
                } else {
                    $wh = " AND sd_prmyscdyupload_t.zone IN ('')";
                    $wh1 = " AND sd_docupload_t.zone IN ('')";
                }
            }
            // raja ram only show andra state purpose

            if ($emp_id == '94') {

                $wh = " AND sd_prmyscdyupload_t.state IN ('ANDHRA PRADESH')";
                $wh1 = " AND sd_docupload_t.state IN ('ANDHRA PRADESH')";
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
        $this->data['last_yr'] =  $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $last_m_avg = addslashes($last_data[0]->month_y);
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        //dd( $this->data['last_update']);
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
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
        
        // get target name dynamic
        $cur_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$cur_fyear'");
        $cur_target = $cur_tgt[0]->target_name;
        //dd($cur_target);
        // Get start date from the request
        $select_date = $request->input('date_select');
        // Extract year and month from the start date
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('m', strtotime($select_date));

        $select_Mon  = date('M', strtotime($select_date));
        // Calculate next and previous years
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;

        // Determine the financial year based on the start month (assuming April is the start of the financial year)
        $fy_start_month = 4;

        if ($select_month >= $fy_start_month) {

            $fy_full_year =  $select_year . '-' . ($select_year + 1);
        }
        if ($select_month < $fy_start_month) {

            $fy_full_year =  ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);

        $select_pre = substr($fy_year, 0, 4);
        $select_pre_y = substr($fy_year, 5, 2);

        $select_pre_year =  ($select_pre - 1) . '-' . ($select_pre_y - 1);

        $month_yr = addslashes($select_Mon . '\'' . substr($select_year, -2));

        $this->data['last_avg_mon'] = $last_data[0]->month_y;



        if ($zone != '' ||  $select_date != '' || $region != '') {

            if ($select_date != ''){ 
            $sec_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$fy_year'");
            $select_target = $sec_tgt[0]->target_name;
            }
            
            $this->data['last_avg_mon'] = stripslashes($month_yr);
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

            // if user select zone or ___ search 
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = date('m', strtotime($last_m));
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
                $month_yr = $last_m_avg;
                $select_target = $cur_target;
                $this->data['last_avg_mon'] = $last_data[0]->month_y;
            }

            // current financial year record month wise
            $this->data['zone_wise'] = \DB::select("select v1.zone,v1.region,v1.target,v1.sales,v1.man,v1.expense,v1.sample,v1.sample,v1.sales_return,v1.gift,v1.transport,v1.free_value,v1.sec_sales,v1.sec_target,SUM(v1.doc_cov_avg) as doc_cov_avg,SUM(v1.doc_cl_avg) as doc_cl_avg from  (SELECT sd_prmyscdyupload_t.zone,sd_prmyscdyupload_t.region,
    ROUND(SUM(CASE WHEN (type_of_data = '$select_target' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as target,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales,
    ROUND(SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'primary') THEN sales_value ELSE 0 END),2) as man,
    ROUND(SUM(CASE WHEN (type_of_data = 'Salary\\\\Exp.\\\\Incen.' AND type = 'primary')  THEN sales_value ELSE 0 END), 0) as expense,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sample' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sample,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales Return' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales_return,
    ROUND(SUM(CASE WHEN (type_of_data = 'PM/Gifts' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as gift,
    ROUND(SUM(CASE WHEN (type_of_data = 'Transport' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as transport,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales' AND type = 'primary') THEN free_stock_value ELSE 0 END), 0) as free_value,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type_d_s='STOCKIST') THEN purchase_stock_value ELSE 0 END), 0) as sec_sales,
    ROUND(SUM(CASE WHEN (type_of_data = '$select_target' AND type='Secondary') THEN purchase_stock_value ELSE 0 END), 0) as sec_target,
   '' as doc_cov_avg,
   '' as doc_cl_avg

    FROM
      sd_prmyscdyupload_t
    WHERE
    f_year='$fy_year'  $notin $wh $condition
      AND STR_TO_DATE(CONCAT(c_year, '-', sd_prmyscdyupload_t.month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
  
    GROUP BY
     sd_prmyscdyupload_t.zone,sd_prmyscdyupload_t.region
     
     UNION ALL

    SELECT zone,region, '' as target ,'' as sales,
    '' as man,'' as expense, '' as sample,'' as sales_return, '' as gift,'' as transport,
    '' as free_value,'' as sec_sales,'' as sec_target,
    ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$month_yr') THEN sd_docupload_t.doctor_coverage ELSE 0 END), 0) as doc_cov_avg, 
        ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$month_yr') THEN sd_docupload_t.doctor_call_average ELSE 0 END), 1) as doc_cl_avg  
         FROM `sd_docupload_t` WHERE month='$month_yr' $condition $wh1 GROUP by zone,region)v1 GROUP BY v1.zone,v1.region");
         
            // previous financial year record month wise
            $this->data['zone_wise_pre'] = \DB::select("select v1.zone,v1.region,v1.target,v1.sales,v1.man,v1.expense,v1.sample,v1.sample,v1.sales_return,v1.gift,v1.transport,v1.free_value,v1.sec_sales,v1.sec_target,SUM(v1.doc_cov_avg) as doc_cov_avg,SUM(v1.doc_cl_avg) as doc_cl_avg from   (SELECT sd_prmyscdyupload_t.zone,sd_prmyscdyupload_t.region,
    ROUND(SUM(CASE WHEN (type_of_data = '$select_target' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as target,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales,
    ROUND(SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'primary') THEN sales_value ELSE 0 END),2) as man,
    ROUND(SUM(CASE WHEN (type_of_data = 'Salary\\\\Exp.\\\\Incen.' AND type = 'primary')  THEN sales_value ELSE 0 END), 0) as expense,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sample' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sample,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales Return' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales_return,
    ROUND(SUM(CASE WHEN (type_of_data = 'PM/Gifts' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as gift,
    ROUND(SUM(CASE WHEN (type_of_data = 'Transport' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as transport,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales' AND type = 'primary') THEN free_stock_value ELSE 0 END), 0) as free_value,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type_d_s='STOCKIST') THEN purchase_stock_value ELSE 0 END), 0) as sec_sales,
    ROUND(SUM(CASE WHEN (type_of_data = '$select_target' AND type='Secondary') THEN purchase_stock_value ELSE 0 END), 0) as sec_target,
   '' as doc_cov_avg,
   '' as doc_cl_avg

    FROM
      sd_prmyscdyupload_t
    WHERE
    f_year='$select_pre_year' $condition $notin $wh
      AND STR_TO_DATE(CONCAT(c_year, '-', sd_prmyscdyupload_t.month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
  
    GROUP BY
     sd_prmyscdyupload_t.zone,sd_prmyscdyupload_t.region
     
     UNION ALL

    SELECT zone,region, '' as target ,'' as sales,
    '' as man,'' as expense, '' as sample,'' as sales_return, '' as gift,'' as transport,
    '' as free_value,'' as sec_sales,'' as sec_target,
    ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$month_yr') THEN sd_docupload_t.doctor_coverage ELSE 0 END), 0) as doc_cov_avg, 
        ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$month_yr') THEN sd_docupload_t.doctor_call_average ELSE 0 END), 1) as doc_cl_avg  
         FROM `sd_docupload_t` WHERE month='$month_yr' $condition $wh1 GROUP by zone,region)v1 GROUP BY v1.zone,v1.region");

//dd( $this->data['zone_wise']);
            // for ratio calculation

            $zone_report = $this->data['zone_wise'];
            // dd($zone_report);
            $zone_report_pre = $this->data['zone_wise_pre'];

            $comparisonData = [];

            foreach ($zone_report as $saleRow) {
                foreach ($zone_report_pre as $preSaleRow) {
                    if ($saleRow->region == $preSaleRow->region) {

                        $comparisonData[] = [
                            'zone' => $saleRow->zone,
                            'region' => $saleRow->region,
                            'growth' => (is_numeric($saleRow->sales) && is_numeric($preSaleRow->sales) && $preSaleRow->sales != 0) ? number_format((($saleRow->sales / $preSaleRow->sales - 1) * 100), 0) . "%": "0",
                            'achive' => $saleRow->target != 0 ? number_format(($saleRow->sales / $saleRow->target) * 100, 0) . "%" : "0",
                            'personproduction' => $saleRow->man != 0 ? str_replace(",", "", number_format(($saleRow->sales / $saleRow->man), 0)) : "0",
                            'empcostratio' => $saleRow->sales != 0 ?  number_format(($saleRow->expense / $saleRow->sales) * 100, 0) . "%" : "0",
                            'samplecost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->sample / $saleRow->sales) * 100, 0) . "%" : "0",
                            'sales_return_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->sales_return / $saleRow->sales) * 100, 0) . "%" : "0",
                            'pm_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->gift / $saleRow->sales) * 100, 0) . "%" : "0",
                            'trans_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->transport / $saleRow->sales) * 100, 0) . "%" : "0",
                            'free_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->free_value / $saleRow->sales) * 100, 0) . "%" : "0",
                            'total_cost_ratio' => $saleRow->sales != 0 ? number_format((($saleRow->expense + $saleRow->sample + $saleRow->sales_return + $saleRow->gift + $saleRow->transport + $saleRow->free_value) / $saleRow->sales) * 100, 0) . "%" : "0",
                            'growth_two' => (is_numeric($saleRow->sec_sales) && is_numeric($preSaleRow->sec_sales) && $preSaleRow->sec_sales != 0)     ? number_format((($saleRow->sec_sales / $preSaleRow->sec_sales - 1) * 100), 0) . "%"     : "0",
                            'achive_two' => $saleRow->sec_target != 0 ? number_format(($saleRow->sec_sales / $saleRow->sec_target) * 100, 0) . "%" : "0",
                            'doc_cov' => $saleRow->doc_cov_avg,
                            'doc_cl_avg' => $saleRow->doc_cl_avg,

                        ];

                        break;
                    }
                }
            }

            $this->data['zone_wise_mis'] = $comparisonData;

            // for grand total purpose

            $this->data['zone_wise_gt'] = \DB::select("SELECT zone,region,
    ROUND(SUM(CASE WHEN (type_of_data = '$select_target' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as target,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales,
    ROUND(SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'primary') THEN sales_value ELSE 0 END),2) as man,
    ROUND(SUM(CASE WHEN (type_of_data = 'Salary\\\\Exp.\\\\Incen.' AND type = 'primary')  THEN sales_value ELSE 0 END), 0) as expense,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sample' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sample,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales Return' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales_return,
    ROUND(SUM(CASE WHEN (type_of_data = 'PM/Gifts' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as gift,
    ROUND(SUM(CASE WHEN (type_of_data = 'Transport' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as transport,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales' AND type = 'primary') THEN free_stock_value ELSE 0 END), 0) as free_value,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type_d_s='STOCKIST') THEN purchase_stock_value ELSE 0 END), 0) as sec_sales,
    ROUND(SUM(CASE WHEN (type_of_data = '$select_target' AND type='Secondary') THEN purchase_stock_value ELSE 0 END), 0) as sec_target
      
    FROM
      sd_prmyscdyupload_t
    WHERE
    f_year='$fy_year' $condition  $wh
      AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
      $notin");


            // previous financial year record month wise
            $this->data['zone_wise_pre'] = \DB::select("SELECT zone,region,
            
    ROUND(SUM(CASE WHEN (type_of_data = '$select_target' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as target,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales,
    ROUND(SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'primary') THEN sales_value ELSE 0 END),2) as man,
    ROUND(SUM(CASE WHEN (type_of_data = 'Salary\\\\Exp.\\\\Incen.' AND type = 'primary')  THEN sales_value ELSE 0 END), 0) as expense,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sample' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sample,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales Return' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales_return,
    ROUND(SUM(CASE WHEN (type_of_data = 'PM/Gifts' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as gift,
    ROUND(SUM(CASE WHEN (type_of_data = 'Transport' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as transport,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales' AND type = 'primary') THEN free_stock_value ELSE 0 END), 0) as free_value,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type_d_s='STOCKIST') THEN purchase_stock_value ELSE 0 END), 0) as sec_sales,
    ROUND(SUM(CASE WHEN (type_of_data = '$select_target' AND type='Secondary') THEN purchase_stock_value ELSE 0 END), 0) as sec_target
      
    FROM
      sd_prmyscdyupload_t
    WHERE
    f_year='$select_pre_year' $condition $wh
      AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
      $notin");

            // for ratio calculation

            $zone_report_gt = $this->data['zone_wise_gt'];
            // dd($zone_report_gt);
            $zone_report_pre_gt = $this->data['zone_wise_pre'];

            $grandtotalData = [];

            foreach ($zone_report_gt as $saleRow) {
                foreach ($zone_report_pre_gt as $preSaleRow) {

                    $grandtotalData[] = [
                        'growth' => (is_numeric($saleRow->sales) && is_numeric($preSaleRow->sales) && $preSaleRow->sales != 0) ? number_format((($saleRow->sales / $preSaleRow->sales - 1) * 100), 0) . "%": "0",
                        'achive' => $saleRow->target != 0 ? number_format(($saleRow->sales / $saleRow->target) * 100, 0) . "%" : "0",
                        'personproduction' => $saleRow->man != 0 ? str_replace(",", "", number_format(($saleRow->sales / $saleRow->man), 0)) : "0",
                        'empcostratio' => $saleRow->sales != 0 ?  number_format(($saleRow->expense / $saleRow->sales) * 100, 0) . "%" : "0",
                        'samplecost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->sample / $saleRow->sales) * 100, 0) . "%" : "0",
                        'sales_return_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->sales_return / $saleRow->sales) * 100, 0) . "%" : "0",
                        'pm_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->gift / $saleRow->sales) * 100, 0) . "%" : "0",
                        'trans_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->transport / $saleRow->sales) * 100, 0) . "%" : "0",
                        'free_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->free_value / $saleRow->sales) * 100, 0) . "%" : "0",
                        'total_cost_ratio' => $saleRow->sales != 0 ? number_format((($saleRow->expense + $saleRow->sample + $saleRow->sales_return + $saleRow->gift + $saleRow->transport + $saleRow->free_value) / $saleRow->sales) * 100, 0) . "%" : "0",
                        'growth_two' => (is_numeric($saleRow->sec_sales) && is_numeric($preSaleRow->sec_sales) && $preSaleRow->sec_sales != 0)     ? number_format((($saleRow->sec_sales / $preSaleRow->sec_sales - 1) * 100), 0) . "%"     : "0",
                        'achive_two' => $saleRow->sec_target != 0 ? number_format(($saleRow->sec_sales / $saleRow->sec_target) * 100, 0) . "%" : "0",

                    ];

                    break;
                }
            }

            $this->data['zone_wise_mis_gt'] = $grandtotalData;

            // dr call average grand total

            $this->data['doc_cl_avg'] = \DB::select("select
            ROUND(AVG (CASE WHEN (sd_docupload_t.month = '$month_yr') THEN sd_docupload_t.doctor_coverage ELSE 0 END), 0) as doc_cov_avg, 
            ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$month_yr') THEN sd_docupload_t.doctor_call_average ELSE 0 END), 1) as doc_cl_avg 
            FROM `sd_docupload_t` WHERE month = '$month_yr' $condition $wh1");
      
        } else {


            // current financial year record month wise
            $this->data['zone_wise'] = \DB::select("select v1.zone,v1.region,v1.target,v1.sales,v1.man,v1.expense,v1.sample,v1.sample,v1.sales_return,v1.gift,v1.transport,v1.free_value,v1.sec_sales,v1.sec_target,SUM(v1.doc_cov_avg) as doc_cov_avg,SUM(v1.doc_cl_avg) as doc_cl_avg from  (SELECT sd_prmyscdyupload_t.zone,sd_prmyscdyupload_t.region,
    ROUND(SUM(CASE WHEN (type_of_data = '$cur_target' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as target,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales,
    ROUND(SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'primary') THEN sales_value ELSE 0 END),2) as man,
    ROUND(SUM(CASE WHEN (type_of_data = 'Salary\\\\Exp.\\\\Incen.' AND type = 'primary')  THEN sales_value ELSE 0 END), 0) as expense,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sample' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sample,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales Return' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales_return,
    ROUND(SUM(CASE WHEN (type_of_data = 'PM/Gifts' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as gift,
    ROUND(SUM(CASE WHEN (type_of_data = 'Transport' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as transport,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales' AND type = 'primary') THEN free_stock_value ELSE 0 END), 0) as free_value,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type_d_s='STOCKIST') THEN purchase_stock_value ELSE 0 END), 0) as sec_sales,
    ROUND(SUM(CASE WHEN (type_of_data = '$cur_target' AND type='Secondary') THEN purchase_stock_value ELSE 0 END), 0) as sec_target,
   '' as doc_cov_avg,
   '' as doc_cl_avg

    FROM
      sd_prmyscdyupload_t
    WHERE
    f_year='$cur_fyear'  $notin $wh
      AND STR_TO_DATE(CONCAT(c_year, '-', sd_prmyscdyupload_t.month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%M-%d')
  
    GROUP BY
     sd_prmyscdyupload_t.zone,sd_prmyscdyupload_t.region
     
     UNION ALL

SELECT zone,region, '' as target ,'' as sales,
'' as man,'' as expense, '' as sample,'' as sales_return, '' as gift,'' as transport,
'' as free_value,'' as sec_sales,'' as sec_target,
ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$last_m_avg') THEN sd_docupload_t.doctor_coverage ELSE 0 END), 0) as doc_cov_avg, 
    ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$last_m_avg') THEN sd_docupload_t.doctor_call_average ELSE 0 END), 1) as doc_cl_avg  
     FROM `sd_docupload_t` WHERE month='$last_m_avg' $wh1 GROUP by zone,region)v1 GROUP BY v1.zone,v1.region");


            // previous financial year record month wise
            $this->data['zone_wise_pre'] = \DB::select("select v1.zone,v1.region,v1.target,v1.sales,v1.man,v1.expense,v1.sample,v1.sample,v1.sales_return,v1.gift,v1.transport,v1.free_value,v1.sec_sales,v1.sec_target,SUM(v1.doc_cov_avg) as doc_cov_avg,SUM(v1.doc_cl_avg) as doc_cl_avg from   (SELECT sd_prmyscdyupload_t.zone,sd_prmyscdyupload_t.region,
    ROUND(SUM(CASE WHEN (type_of_data = '$cur_target' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as target,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales,
    ROUND(SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'primary') THEN sales_value ELSE 0 END),2) as man,
    ROUND(SUM(CASE WHEN (type_of_data = 'Salary\\\\Exp.\\\\Incen.' AND type = 'primary')  THEN sales_value ELSE 0 END), 0) as expense,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sample' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sample,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales Return' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales_return,
    ROUND(SUM(CASE WHEN (type_of_data = 'PM/Gifts' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as gift,
    ROUND(SUM(CASE WHEN (type_of_data = 'Transport' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as transport,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales' AND type = 'primary') THEN free_stock_value ELSE 0 END), 0) as free_value,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type_d_s='STOCKIST') THEN purchase_stock_value ELSE 0 END), 0) as sec_sales,
    ROUND(SUM(CASE WHEN (type_of_data = '$cur_target' AND type='Secondary') THEN purchase_stock_value ELSE 0 END), 0) as sec_target,
   '' as doc_cov_avg,
   '' as doc_cl_avg

    FROM
      sd_prmyscdyupload_t
    WHERE
    f_year='$pre_fyear'  $notin $wh
      AND STR_TO_DATE(CONCAT(c_year, '-', sd_prmyscdyupload_t.month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_last_y-$last_m-01', '%Y-%M-%d')
  
    GROUP BY
     sd_prmyscdyupload_t.zone,sd_prmyscdyupload_t.region
     
     UNION ALL

    SELECT zone,region, '' as target ,'' as sales,
    '' as man,'' as expense, '' as sample,'' as sales_return, '' as gift,'' as transport,
    '' as free_value,'' as sec_sales,'' as sec_target,
    ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$last_m_avg') THEN sd_docupload_t.doctor_coverage ELSE 0 END), 0) as doc_cov_avg, 
        ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$last_m_avg') THEN sd_docupload_t.doctor_call_average ELSE 0 END), 1) as doc_cl_avg  
         FROM `sd_docupload_t` WHERE month='$last_m_avg' $wh1 GROUP by zone,region)v1 GROUP BY v1.zone,v1.region");

            // for ratio calculation

            $zone_report = $this->data['zone_wise'];
            // dd($zone_report);
            $zone_report_pre = $this->data['zone_wise_pre'];

            $comparisonData = [];

            foreach ($zone_report as $saleRow) {
                foreach ($zone_report_pre as $preSaleRow) {
                    if ($saleRow->region == $preSaleRow->region) {

                        $comparisonData[] = [
                            'zone' => $saleRow->zone,
                            'region' => $saleRow->region,
                            'growth' => (is_numeric($saleRow->sales) && is_numeric($preSaleRow->sales) && $preSaleRow->sales != 0) ? number_format((($saleRow->sales / $preSaleRow->sales - 1) * 100), 0) . "%": "0",
                            'achive' => $saleRow->target != 0 ? number_format(($saleRow->sales / $saleRow->target) * 100, 0) . "%" : "0",
                            'personproduction' => $saleRow->man != 0 ? str_replace(",", "", number_format(($saleRow->sales / $saleRow->man), 0)) : "0",
                            'empcostratio' => $saleRow->sales != 0 ?  number_format(($saleRow->expense / $saleRow->sales) * 100, 0) . "%" : "0",
                            'samplecost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->sample / $saleRow->sales) * 100, 0) . "%" : "0",
                            'sales_return_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->sales_return / $saleRow->sales) * 100, 0) . "%" : "0",
                            'pm_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->gift / $saleRow->sales) * 100, 0) . "%" : "0",
                            'trans_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->transport / $saleRow->sales) * 100, 0) . "%" : "0",
                            'free_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->free_value / $saleRow->sales) * 100, 0) . "%" : "0",
                            'total_cost_ratio' => $saleRow->sales != 0 ? number_format((($saleRow->expense + $saleRow->sample + $saleRow->sales_return + $saleRow->gift + $saleRow->transport + $saleRow->free_value) / $saleRow->sales) * 100, 0) . "%" : "0",
                            'growth_two' => (is_numeric($saleRow->sec_sales) && is_numeric($preSaleRow->sec_sales) && $preSaleRow->sec_sales != 0)     ? number_format((($saleRow->sec_sales / $preSaleRow->sec_sales - 1) * 100), 0) . "%"     : "0",
                            'achive_two' => $saleRow->sec_target != 0 ? number_format(($saleRow->sec_sales / $saleRow->sec_target) * 100, 0) . "%" : "0",
                            'doc_cov' => $saleRow->doc_cov_avg,
                            'doc_cl_avg' => $saleRow->doc_cl_avg,

                        ];

                        break;
                    }
                }
            }

            $this->data['zone_wise_mis'] = $comparisonData;

            // for grand total purpose

            $this->data['zone_wise_gt'] = \DB::select("SELECT zone,region,
    ROUND(SUM(CASE WHEN (type_of_data = '$cur_target' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as target,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales,
    ROUND(SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'primary') THEN sales_value ELSE 0 END),2) as man,
    ROUND(SUM(CASE WHEN (type_of_data = 'Salary\\\\Exp.\\\\Incen.' AND type = 'primary')  THEN sales_value ELSE 0 END), 0) as expense,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sample' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sample,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales Return' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales_return,
    ROUND(SUM(CASE WHEN (type_of_data = 'PM/Gifts' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as gift,
    ROUND(SUM(CASE WHEN (type_of_data = 'Transport' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as transport,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales' AND type = 'primary') THEN free_stock_value ELSE 0 END), 0) as free_value,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type_d_s='STOCKIST') THEN purchase_stock_value ELSE 0 END), 0) as sec_sales,
    ROUND(SUM(CASE WHEN (type_of_data = '$cur_target' AND type='Secondary') THEN purchase_stock_value ELSE 0 END), 0) as sec_target
      
    FROM
      sd_prmyscdyupload_t
    WHERE
    f_year='$cur_fyear'  $wh
      AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%M-%d')
      $notin");


            // previous financial year record month wise
            $this->data['zone_wise_pre'] = \DB::select("SELECT zone,region,
            
    ROUND(SUM(CASE WHEN (type_of_data = '$cur_target' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as target,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales,
    ROUND(SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'primary') THEN sales_value ELSE 0 END),2) as man,
    ROUND(SUM(CASE WHEN (type_of_data = 'Salary\\\\Exp.\\\\Incen.' AND type = 'primary')  THEN sales_value ELSE 0 END), 0) as expense,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sample' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sample,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales Return' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales_return,
    ROUND(SUM(CASE WHEN (type_of_data = 'PM/Gifts' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as gift,
    ROUND(SUM(CASE WHEN (type_of_data = 'Transport' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as transport,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales' AND type = 'primary') THEN free_stock_value ELSE 0 END), 0) as free_value,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type_d_s='STOCKIST') THEN purchase_stock_value ELSE 0 END), 0) as sec_sales,
    ROUND(SUM(CASE WHEN (type_of_data = '$cur_target' AND type='Secondary') THEN purchase_stock_value ELSE 0 END), 0) as sec_target
      
    FROM
      sd_prmyscdyupload_t
    WHERE
    f_year='$pre_fyear' $wh
      AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_last_y-$last_m-01', '%Y-%M-%d')
      $notin");

            // for ratio calculation

            $zone_report_gt = $this->data['zone_wise_gt'];
            // dd($zone_report_gt);
            $zone_report_pre_gt = $this->data['zone_wise_pre'];

            $grandtotalData = [];

            foreach ($zone_report_gt as $saleRow) {
                foreach ($zone_report_pre_gt as $preSaleRow) {

                    $grandtotalData[] = [
                        'growth' => (is_numeric($saleRow->sales) && is_numeric($preSaleRow->sales) && $preSaleRow->sales != 0) ? number_format((($saleRow->sales / $preSaleRow->sales - 1) * 100), 0) . "%": "0",
                        'achive' => $saleRow->target != 0 ? number_format(($saleRow->sales / $saleRow->target) * 100, 0) . "%" : "0",
                        'personproduction' => $saleRow->man != 0 ? str_replace(",", "", number_format(($saleRow->sales / $saleRow->man), 0)) : "0",
                        'empcostratio' => $saleRow->sales != 0 ?  number_format(($saleRow->expense / $saleRow->sales) * 100, 0) . "%" : "0",
                        'samplecost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->sample / $saleRow->sales) * 100, 0) . "%" : "0",
                        'sales_return_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->sales_return / $saleRow->sales) * 100, 0) . "%" : "0",
                        'pm_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->gift / $saleRow->sales) * 100, 0) . "%" : "0",
                        'trans_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->transport / $saleRow->sales) * 100, 0) . "%" : "0",
                        'free_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->free_value / $saleRow->sales) * 100, 0) . "%" : "0",
                        'total_cost_ratio' => $saleRow->sales != 0 ? number_format((($saleRow->expense + $saleRow->sample + $saleRow->sales_return + $saleRow->gift + $saleRow->transport + $saleRow->free_value) / $saleRow->sales) * 100, 0) . "%" : "0",
                        'growth_two' => (is_numeric($saleRow->sec_sales) && is_numeric($preSaleRow->sec_sales) && $preSaleRow->sec_sales != 0)     ? number_format((($saleRow->sec_sales / $preSaleRow->sec_sales - 1) * 100), 0) . "%"     : "0",
                        'achive_two' => $saleRow->sec_target != 0 ? number_format(($saleRow->sec_sales / $saleRow->sec_target) * 100, 0) . "%" : "0",

                    ];

                    break;
                }
            }

            $this->data['zone_wise_mis_gt'] = $grandtotalData;

            // dr call average grand total

            $this->data['doc_cl_avg'] = \DB::select("select
            ROUND(AVG (CASE WHEN (sd_docupload_t.month = '$last_m_avg') THEN sd_docupload_t.doctor_coverage ELSE 0 END), 0) as doc_cov_avg, 
            ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$last_m_avg') THEN sd_docupload_t.doctor_call_average ELSE 0 END), 1) as doc_cl_avg 
            FROM `sd_docupload_t` WHERE month = '$last_m_avg' $wh1");
        }

        return view('business_mis.zonewise', $this->data);
    }

    // state wise
    public function Statewise(Request $request)
    {

        // login viewers track purpose End
    
        $wh = '';
        $wh1  = '';
        
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
        if ($depart == "14" && $emp_id != '121') {

            // RSM region compare purpose
            if ($emp_reg == '41') {

                $emp_region = '';
                // emp region 
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name'");

                if (!empty($em_region)) {
                    $regions = array_column($em_region, 'region');
                    $emp_region = "'" . implode("','", $regions) . "'";
                    $wh = " AND sd_prmyscdyupload_t.region IN ($emp_region)";
                    $wh1 = " AND sd_docupload_t.region IN ($emp_region)";
                } else {
                    $wh = " AND sd_prmyscdyupload_t.region IN ('')";
                    $wh1 = " AND sd_docupload_t.region IN ('')";
                }
                //end
            } else {
                //others

                $emp_zone = '';
                // emp zone 
                $em_zone = \DB::select("SELECT zone from sd_mar_employee_detail_t where employee_name='$emp_name'");

                if (!empty($em_zone)) {
                    if (isset($em_zone[0]->zone) && $em_zone[0]->zone != '') {
                        $emp_zone = $em_zone[0]->zone;
                    } else {
                        $emp_zone = '';
                    }
                }

                if (!empty($emp_zone)) {
                    $wh = "  AND sd_prmyscdyupload_t.zone ='$emp_zone'";
                    $wh1 = "  AND sd_docupload_t.zone ='$emp_zone'";
                } else {
                    $wh = " AND sd_prmyscdyupload_t.zone IN ('')";
                    $wh1 = " AND sd_docupload_t.zone IN ('')";
                }
            }
            // raja ram only show andra state purpose

            if ($emp_id == '94') {

                $wh = " AND sd_prmyscdyupload_t.state IN ('ANDHRA PRADESH')";
                $wh1 = " AND sd_docupload_t.state IN ('ANDHRA PRADESH')";
            }
        }

        // end
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
        $this->data['last_yr'] =  $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $last_m_avg = addslashes($last_data[0]->month_y);
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        //dd( $this->data['last_update']);
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
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

        // get target name dynamic
        $cur_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$cur_fyear'");
        $cur_target = $cur_tgt[0]->target_name;

        // Get start date from the request
        $select_date = $request->input('date_select');
        // Extract year and month from the start date
        $select_year = date('Y', strtotime($select_date));
        $select_month = date('m', strtotime($select_date));

        $select_Mon  = date('M', strtotime($select_date));
        // Calculate next and previous years
        $next_year = $select_year + 1;
        $pre_year = $select_year - 1;

        // Determine the financial year based on the start month (assuming April is the start of the financial year)
        $fy_start_month = 4;

        if ($select_month >= $fy_start_month) {

            $fy_full_year =  $select_year . '-' . ($select_year + 1);
        }
        if ($select_month < $fy_start_month) {

            $fy_full_year =  ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);

        $select_pre = substr($fy_year, 0, 4);
        $select_pre_y = substr($fy_year, 5, 2);

        $select_pre_year =  ($select_pre - 1) . '-' . ($select_pre_y - 1);

        $month_yr = addslashes($select_Mon . '\'' . substr($select_year, -2));

        $this->data['last_avg_mon'] = $last_data[0]->month_y;;



        if ($zone != '' ||  $select_date != '' || $region != '') {

            if ($select_date != ''){ 
            $sec_tgt = \DB::select("SELECT target_name FROM `sd_targetname_t` where f_year='$fy_year'");
            $select_target = $sec_tgt[0]->target_name;
            }

            $this->data['last_avg_mon'] = stripslashes($month_yr);
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

            // if user select zone or ___ search 
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = date('m', strtotime($last_m));
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
                $month_yr = $last_m_avg;
                $select_target = $cur_target;
                $this->data['last_avg_mon'] = $last_data[0]->month_y;
            }

            // current financial year record month wise
            $this->data['state_wise'] = \DB::select("select v1.state,v1.region,v1.target,v1.sales,v1.man,v1.expense,v1.sample,v1.sample,v1.sales_return,v1.gift,v1.transport,v1.free_value,v1.sec_sales,v1.sec_target,SUM(v1.doc_cov_avg) as doc_cov_avg,SUM(v1.doc_cl_avg) as doc_cl_avg from  (SELECT sd_prmyscdyupload_t.state,sd_prmyscdyupload_t.region,
    ROUND(SUM(CASE WHEN (type_of_data = '$select_target' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as target,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales,
    ROUND(SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'primary') THEN sales_value ELSE 0 END),2) as man,
    ROUND(SUM(CASE WHEN (type_of_data = 'Salary\\\\Exp.\\\\Incen.' AND type = 'primary')  THEN sales_value ELSE 0 END), 0) as expense,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sample' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sample,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales Return' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales_return,
    ROUND(SUM(CASE WHEN (type_of_data = 'PM/Gifts' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as gift,
    ROUND(SUM(CASE WHEN (type_of_data = 'Transport' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as transport,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales' AND type = 'primary') THEN free_stock_value ELSE 0 END), 0) as free_value,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type_d_s='STOCKIST') THEN purchase_stock_value ELSE 0 END), 0) as sec_sales,
    ROUND(SUM(CASE WHEN (type_of_data = '$select_target' AND type='Secondary') THEN purchase_stock_value ELSE 0 END), 0) as sec_target,
   '' as doc_cov_avg,
   '' as doc_cl_avg

    FROM
      sd_prmyscdyupload_t
    WHERE
    f_year='$fy_year'  $notin $wh $condition
      AND STR_TO_DATE(CONCAT(c_year, '-', sd_prmyscdyupload_t.month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
  
    GROUP BY
     sd_prmyscdyupload_t.state,sd_prmyscdyupload_t.region
     
     UNION ALL

    SELECT state,region, '' as target ,'' as sales,
    '' as man,'' as expense, '' as sample,'' as sales_return, '' as gift,'' as transport,
    '' as free_value,'' as sec_sales,'' as sec_target,
    ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$month_yr') THEN sd_docupload_t.doctor_coverage ELSE 0 END), 0) as doc_cov_avg, 
        ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$month_yr') THEN sd_docupload_t.doctor_call_average ELSE 0 END), 1) as doc_cl_avg  
         FROM `sd_docupload_t` WHERE month='$month_yr' $condition $wh1 GROUP by state,region)v1 GROUP BY v1.state,v1.region");


            // previous financial year record month wise
            $this->data['state_wise_pre'] = \DB::select("select v1.state,v1.region,v1.target,v1.sales,v1.man,v1.expense,v1.sample,v1.sample,v1.sales_return,v1.gift,v1.transport,v1.free_value,v1.sec_sales,v1.sec_target,SUM(v1.doc_cov_avg) as doc_cov_avg,SUM(v1.doc_cl_avg) as doc_cl_avg from   (SELECT sd_prmyscdyupload_t.state,sd_prmyscdyupload_t.region,
    ROUND(SUM(CASE WHEN (type_of_data = '$select_target' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as target,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales,
    ROUND(SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'primary') THEN sales_value ELSE 0 END),2) as man,
    ROUND(SUM(CASE WHEN (type_of_data = 'Salary\\\\Exp.\\\\Incen.' AND type = 'primary')  THEN sales_value ELSE 0 END), 0) as expense,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sample' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sample,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales Return' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales_return,
    ROUND(SUM(CASE WHEN (type_of_data = 'PM/Gifts' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as gift,
    ROUND(SUM(CASE WHEN (type_of_data = 'Transport' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as transport,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales' AND type = 'primary') THEN free_stock_value ELSE 0 END), 0) as free_value,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type_d_s='STOCKIST') THEN purchase_stock_value ELSE 0 END), 0) as sec_sales,
    ROUND(SUM(CASE WHEN (type_of_data = '$select_target' AND type='Secondary') THEN purchase_stock_value ELSE 0 END), 0) as sec_target,
   '' as doc_cov_avg,
   '' as doc_cl_avg

    FROM
      sd_prmyscdyupload_t
    WHERE
    f_year='$select_pre_year' $condition $notin $wh
      AND STR_TO_DATE(CONCAT(c_year, '-', sd_prmyscdyupload_t.month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
  
    GROUP BY
     sd_prmyscdyupload_t.state,sd_prmyscdyupload_t.region
     
     UNION ALL

    SELECT state,region, '' as target ,'' as sales,
    '' as man,'' as expense, '' as sample,'' as sales_return, '' as gift,'' as transport,
    '' as free_value,'' as sec_sales,'' as sec_target,
    ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$month_yr') THEN sd_docupload_t.doctor_coverage ELSE 0 END), 0) as doc_cov_avg, 
        ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$month_yr') THEN sd_docupload_t.doctor_call_average ELSE 0 END), 1) as doc_cl_avg  
         FROM `sd_docupload_t` WHERE month='$month_yr' $condition $wh1 GROUP by state,region)v1 GROUP BY v1.state,v1.region");

            // for ratio calculation

            $zone_report = $this->data['state_wise'];
            // dd($zone_report);
            $zone_report_pre = $this->data['state_wise_pre'];

            $comparisonData = [];

            foreach ($zone_report as $saleRow) {
                foreach ($zone_report_pre as $preSaleRow) {
                    if ($saleRow->state == $preSaleRow->state) {

                        $comparisonData[] = [
                            'state' => $saleRow->state,
                            'region' => $saleRow->region,
                            'growth' => (is_numeric($saleRow->sales) && is_numeric($preSaleRow->sales) && $preSaleRow->sales != 0) ? number_format((($saleRow->sales / $preSaleRow->sales - 1) * 100), 0) . "%": "0",
                            'achive' => $saleRow->target != 0 ? number_format(($saleRow->sales / $saleRow->target) * 100, 0) . "%" : "0",
                            'personproduction' => $saleRow->man != 0 ? str_replace(",", "", number_format(($saleRow->sales / $saleRow->man), 0)) : "0",
                            'empcostratio' => $saleRow->sales != 0 ?  number_format(($saleRow->expense / $saleRow->sales) * 100, 0) . "%" : "0",
                            'samplecost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->sample / $saleRow->sales) * 100, 0) . "%" : "0",
                            'sales_return_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->sales_return / $saleRow->sales) * 100, 0) . "%" : "0",
                            'pm_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->gift / $saleRow->sales) * 100, 0) . "%" : "0",
                            'trans_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->transport / $saleRow->sales) * 100, 0) . "%" : "0",
                            'free_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->free_value / $saleRow->sales) * 100, 0) . "%" : "0",
                            'total_cost_ratio' => $saleRow->sales != 0 ? number_format((($saleRow->expense + $saleRow->sample + $saleRow->sales_return + $saleRow->gift + $saleRow->transport + $saleRow->free_value) / $saleRow->sales) * 100, 0) . "%" : "0",
                            'growth_two' => (is_numeric($saleRow->sec_sales) && is_numeric($preSaleRow->sec_sales) && $preSaleRow->sec_sales != 0)     ? number_format((($saleRow->sec_sales / $preSaleRow->sec_sales - 1) * 100), 0) . "%"     : "0",
                            'achive_two' => $saleRow->sec_target != 0 ? number_format(($saleRow->sec_sales / $saleRow->sec_target) * 100, 0) . "%" : "0",
                            'doc_cov' => $saleRow->doc_cov_avg,
                            'doc_cl_avg' => $saleRow->doc_cl_avg,

                        ];

                        break;
                    }
                }
            }

            $this->data['state_wise_mis'] = $comparisonData;

            // for grand total purpose

            $this->data['zone_wise_gt'] = \DB::select("SELECT state,region,
    ROUND(SUM(CASE WHEN (type_of_data = '$select_target' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as target,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales,
    ROUND(SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'primary') THEN sales_value ELSE 0 END),2) as man,
    ROUND(SUM(CASE WHEN (type_of_data = 'Salary\\\\Exp.\\\\Incen.' AND type = 'primary')  THEN sales_value ELSE 0 END), 0) as expense,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sample' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sample,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales Return' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales_return,
    ROUND(SUM(CASE WHEN (type_of_data = 'PM/Gifts' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as gift,
    ROUND(SUM(CASE WHEN (type_of_data = 'Transport' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as transport,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales' AND type = 'primary') THEN free_stock_value ELSE 0 END), 0) as free_value,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type_d_s='STOCKIST') THEN purchase_stock_value ELSE 0 END), 0) as sec_sales,
    ROUND(SUM(CASE WHEN (type_of_data = '$select_target' AND type='Secondary') THEN purchase_stock_value ELSE 0 END), 0) as sec_target
      
    FROM
      sd_prmyscdyupload_t
    WHERE
    f_year='$fy_year' $condition  $wh
      AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d')
      $notin");


            // previous financial year record month wise
            $this->data['state_wise_pre'] = \DB::select("SELECT state,region,
            
    ROUND(SUM(CASE WHEN (type_of_data = '$select_target' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as target,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales,
    ROUND(SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'primary') THEN sales_value ELSE 0 END),2) as man,
    ROUND(SUM(CASE WHEN (type_of_data = 'Salary\\\\Exp.\\\\Incen.' AND type = 'primary')  THEN sales_value ELSE 0 END), 0) as expense,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sample' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sample,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales Return' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales_return,
    ROUND(SUM(CASE WHEN (type_of_data = 'PM/Gifts' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as gift,
    ROUND(SUM(CASE WHEN (type_of_data = 'Transport' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as transport,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales' AND type = 'primary') THEN free_stock_value ELSE 0 END), 0) as free_value,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type_d_s='STOCKIST') THEN purchase_stock_value ELSE 0 END), 0) as sec_sales,
    ROUND(SUM(CASE WHEN (type_of_data = '$select_target' AND type='Secondary') THEN purchase_stock_value ELSE 0 END), 0) as sec_target
      
    FROM
      sd_prmyscdyupload_t
    WHERE
    f_year='$select_pre_year' $condition $wh
      AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_year-$select_month-01', '%Y-%m-%d')
      $notin");

            // for ratio calculation

            $zone_report_gt = $this->data['zone_wise_gt'];
            // dd($zone_report_gt);
            $zone_report_pre_gt = $this->data['state_wise_pre'];

            $grandtotalData = [];

            foreach ($zone_report_gt as $saleRow) {
                foreach ($zone_report_pre_gt as $preSaleRow) {

                    $grandtotalData[] = [
                        'growth' => (is_numeric($saleRow->sales) && is_numeric($preSaleRow->sales) && $preSaleRow->sales != 0) ? number_format((($saleRow->sales / $preSaleRow->sales - 1) * 100), 0) . "%": "0",
                        'achive' => $saleRow->target != 0 ? number_format(($saleRow->sales / $saleRow->target) * 100, 0) . "%" : "0",
                        'personproduction' => $saleRow->man != 0 ? str_replace(",", "", number_format(($saleRow->sales / $saleRow->man), 0)) : "0",
                        'empcostratio' => $saleRow->sales != 0 ?  number_format(($saleRow->expense / $saleRow->sales) * 100, 0) . "%" : "0",
                        'samplecost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->sample / $saleRow->sales) * 100, 0) . "%" : "0",
                        'sales_return_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->sales_return / $saleRow->sales) * 100, 0) . "%" : "0",
                        'pm_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->gift / $saleRow->sales) * 100, 0) . "%" : "0",
                        'trans_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->transport / $saleRow->sales) * 100, 0) . "%" : "0",
                        'free_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->free_value / $saleRow->sales) * 100, 0) . "%" : "0",
                        'total_cost_ratio' => $saleRow->sales != 0 ? number_format((($saleRow->expense + $saleRow->sample + $saleRow->sales_return + $saleRow->gift + $saleRow->transport + $saleRow->free_value) / $saleRow->sales) * 100, 0) . "%" : "0",
                        'growth_two' => (is_numeric($saleRow->sec_sales) && is_numeric($preSaleRow->sec_sales) && $preSaleRow->sec_sales != 0)     ? number_format((($saleRow->sec_sales / $preSaleRow->sec_sales - 1) * 100), 0) . "%"     : "0",
                        'achive_two' => $saleRow->sec_target != 0 ? number_format(($saleRow->sec_sales / $saleRow->sec_target) * 100, 0) . "%" : "0",

                    ];

                    break;
                }
            }

            $this->data['state_wise_mis_gt'] = $grandtotalData;

            // dr call average grand total

            $this->data['doc_cl_avg'] = \DB::select("select
            ROUND(AVG (CASE WHEN (sd_docupload_t.month = '$month_yr') THEN sd_docupload_t.doctor_coverage ELSE 0 END), 0) as doc_cov_avg, 
            ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$month_yr') THEN sd_docupload_t.doctor_call_average ELSE 0 END), 1) as doc_cl_avg 
            FROM `sd_docupload_t` WHERE month = '$month_yr' $wh1");
            
        } else {


            // current financial year record month wise
            $this->data['state_wise'] = \DB::select("select v1.state,v1.region,v1.target,v1.sales,v1.man,v1.expense,v1.sample,v1.sample,v1.sales_return,v1.gift,v1.transport,v1.free_value,v1.sec_sales,v1.sec_target,SUM(v1.doc_cov_avg) as doc_cov_avg,SUM(v1.doc_cl_avg) as doc_cl_avg from  (SELECT sd_prmyscdyupload_t.state,sd_prmyscdyupload_t.region,
    ROUND(SUM(CASE WHEN (type_of_data = '$cur_target' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as target,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales,
    ROUND(SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'primary') THEN sales_value ELSE 0 END),2) as man,
    ROUND(SUM(CASE WHEN (type_of_data = 'Salary\\\\Exp.\\\\Incen.' AND type = 'primary')  THEN sales_value ELSE 0 END), 0) as expense,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sample' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sample,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales Return' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales_return,
    ROUND(SUM(CASE WHEN (type_of_data = 'PM/Gifts' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as gift,
    ROUND(SUM(CASE WHEN (type_of_data = 'Transport' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as transport,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales' AND type = 'primary') THEN free_stock_value ELSE 0 END), 0) as free_value,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type_d_s='STOCKIST') THEN purchase_stock_value ELSE 0 END), 0) as sec_sales,
    ROUND(SUM(CASE WHEN (type_of_data = '$cur_target' AND type='Secondary') THEN purchase_stock_value ELSE 0 END), 0) as sec_target,
   '' as doc_cov_avg,
   '' as doc_cl_avg

    FROM
      sd_prmyscdyupload_t
    WHERE
    f_year='$cur_fyear'  $notin $wh
      AND STR_TO_DATE(CONCAT(c_year, '-', sd_prmyscdyupload_t.month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%M-%d')
  
    GROUP BY
     sd_prmyscdyupload_t.state,sd_prmyscdyupload_t.region
     
     UNION ALL

SELECT state,region, '' as target ,'' as sales,
'' as man,'' as expense, '' as sample,'' as sales_return, '' as gift,'' as transport,
'' as free_value,'' as sec_sales,'' as sec_target,
ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$last_m_avg') THEN sd_docupload_t.doctor_coverage ELSE 0 END), 0) as doc_cov_avg, 
    ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$last_m_avg') THEN sd_docupload_t.doctor_call_average ELSE 0 END), 1) as doc_cl_avg  
     FROM `sd_docupload_t` WHERE month='$last_m_avg' $wh1 GROUP by state,region)v1 GROUP BY v1.state,v1.region");


            // previous financial year record month wise
            $this->data['state_wise_pre'] = \DB::select("select v1.state,v1.region,v1.target,v1.sales,v1.man,v1.expense,v1.sample,v1.sample,v1.sales_return,v1.gift,v1.transport,v1.free_value,v1.sec_sales,v1.sec_target,SUM(v1.doc_cov_avg) as doc_cov_avg,SUM(v1.doc_cl_avg) as doc_cl_avg from   (SELECT sd_prmyscdyupload_t.state,sd_prmyscdyupload_t.region,
    ROUND(SUM(CASE WHEN (type_of_data = '$cur_target' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as target,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales,
    ROUND(SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'primary') THEN sales_value ELSE 0 END),2) as man,
    ROUND(SUM(CASE WHEN (type_of_data = 'Salary\\\\Exp.\\\\Incen.' AND type = 'primary')  THEN sales_value ELSE 0 END), 0) as expense,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sample' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sample,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales Return' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales_return,
    ROUND(SUM(CASE WHEN (type_of_data = 'PM/Gifts' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as gift,
    ROUND(SUM(CASE WHEN (type_of_data = 'Transport' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as transport,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales' AND type = 'primary') THEN free_stock_value ELSE 0 END), 0) as free_value,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type_d_s='STOCKIST') THEN purchase_stock_value ELSE 0 END), 0) as sec_sales,
    ROUND(SUM(CASE WHEN (type_of_data = '$cur_target' AND type='Secondary') THEN purchase_stock_value ELSE 0 END), 0) as sec_target,
   '' as doc_cov_avg,
   '' as doc_cl_avg

    FROM
      sd_prmyscdyupload_t
    WHERE
    f_year='$pre_fyear'  $notin $wh
      AND STR_TO_DATE(CONCAT(c_year, '-', sd_prmyscdyupload_t.month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_last_y-$last_m-01', '%Y-%M-%d')
  
    GROUP BY
     sd_prmyscdyupload_t.state,sd_prmyscdyupload_t.region
     
     UNION ALL

    SELECT state,region, '' as target ,'' as sales,
    '' as man,'' as expense, '' as sample,'' as sales_return, '' as gift,'' as transport,
    '' as free_value,'' as sec_sales,'' as sec_target,
    ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$last_m_avg') THEN sd_docupload_t.doctor_coverage ELSE 0 END), 0) as doc_cov_avg, 
        ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$last_m_avg') THEN sd_docupload_t.doctor_call_average ELSE 0 END), 1) as doc_cl_avg  
         FROM `sd_docupload_t` WHERE month='$last_m_avg' $wh1 GROUP by state,region)v1 GROUP BY v1.state,v1.region");

            // for ratio calculation

            $zone_report = $this->data['state_wise'];
            // dd($zone_report);
            $zone_report_pre = $this->data['state_wise_pre'];

            $comparisonData = [];

            foreach ($zone_report as $saleRow) {
                foreach ($zone_report_pre as $preSaleRow) {
                    if ($saleRow->state == $preSaleRow->state) {

                        $comparisonData[] = [
                            'state' => $saleRow->state,
                            'region' => $saleRow->region,
                            'growth' => (is_numeric($saleRow->sales) && is_numeric($preSaleRow->sales) && $preSaleRow->sales != 0) ? number_format((($saleRow->sales / $preSaleRow->sales - 1) * 100), 0) . "%": "0",
                            'achive' => $saleRow->target != 0 ? number_format(($saleRow->sales / $saleRow->target) * 100, 0) . "%" : "0",
                            'personproduction' => $saleRow->man != 0 ? str_replace(",", "", number_format(($saleRow->sales / $saleRow->man), 0)) : "0",
                            'empcostratio' => $saleRow->sales != 0 ?  number_format(($saleRow->expense / $saleRow->sales) * 100, 0) . "%" : "0",
                            'samplecost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->sample / $saleRow->sales) * 100, 0) . "%" : "0",
                            'sales_return_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->sales_return / $saleRow->sales) * 100, 0) . "%" : "0",
                            'pm_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->gift / $saleRow->sales) * 100, 0) . "%" : "0",
                            'trans_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->transport / $saleRow->sales) * 100, 0) . "%" : "0",
                            'free_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->free_value / $saleRow->sales) * 100, 0) . "%" : "0",
                            'total_cost_ratio' => $saleRow->sales != 0 ? number_format((($saleRow->expense + $saleRow->sample + $saleRow->sales_return + $saleRow->gift + $saleRow->transport + $saleRow->free_value) / $saleRow->sales) * 100, 0) . "%" : "0",
                            'growth_two' => (is_numeric($saleRow->sec_sales) && is_numeric($preSaleRow->sec_sales) && $preSaleRow->sec_sales != 0)     ? number_format((($saleRow->sec_sales / $preSaleRow->sec_sales - 1) * 100), 0) . "%"     : "0",
                            'achive_two' => $saleRow->sec_target != 0 ? number_format(($saleRow->sec_sales / $saleRow->sec_target) * 100, 0) . "%" : "0",
                            'doc_cov' => $saleRow->doc_cov_avg,
                            'doc_cl_avg' => $saleRow->doc_cl_avg,

                        ];

                        break;
                    }
                }
            }

            $this->data['state_wise_mis'] = $comparisonData;

            // for grand total purpose

            $this->data['zone_wise_gt'] = \DB::select("SELECT state,region,
    ROUND(SUM(CASE WHEN (type_of_data = '$cur_target' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as target,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales,
    ROUND(SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'primary') THEN sales_value ELSE 0 END),2) as man,
    ROUND(SUM(CASE WHEN (type_of_data = 'Salary\\\\Exp.\\\\Incen.' AND type = 'primary')  THEN sales_value ELSE 0 END), 0) as expense,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sample' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sample,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales Return' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales_return,
    ROUND(SUM(CASE WHEN (type_of_data = 'PM/Gifts' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as gift,
    ROUND(SUM(CASE WHEN (type_of_data = 'Transport' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as transport,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales' AND type = 'primary') THEN free_stock_value ELSE 0 END), 0) as free_value,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type_d_s='STOCKIST') THEN purchase_stock_value ELSE 0 END), 0) as sec_sales,
    ROUND(SUM(CASE WHEN (type_of_data = '$cur_target' AND type='Secondary') THEN purchase_stock_value ELSE 0 END), 0) as sec_target
      
    FROM
      sd_prmyscdyupload_t
    WHERE
    f_year='$cur_fyear'  $wh
      AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%M-%d')
      $notin");


            // previous financial year record month wise
            $this->data['state_wise_pre'] = \DB::select("SELECT state,region,
            
    ROUND(SUM(CASE WHEN (type_of_data = '$cur_target' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as target,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales,
    ROUND(SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'primary') THEN sales_value ELSE 0 END),2) as man,
    ROUND(SUM(CASE WHEN (type_of_data = 'Salary\\\\Exp.\\\\Incen.' AND type = 'primary')  THEN sales_value ELSE 0 END), 0) as expense,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sample' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sample,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales Return' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as sales_return,
    ROUND(SUM(CASE WHEN (type_of_data = 'PM/Gifts' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as gift,
    ROUND(SUM(CASE WHEN (type_of_data = 'Transport' AND type = 'primary') THEN sales_value ELSE 0 END), 0) as transport,
    ROUND(SUM(CASE WHEN (type_of_data = 'Sales' AND type = 'primary') THEN free_stock_value ELSE 0 END), 0) as free_value,
    ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type_d_s='STOCKIST') THEN purchase_stock_value ELSE 0 END), 0) as sec_sales,
    ROUND(SUM(CASE WHEN (type_of_data = '$cur_target' AND type='Secondary') THEN purchase_stock_value ELSE 0 END), 0) as sec_target
      
    FROM
      sd_prmyscdyupload_t
    WHERE
    f_year='$pre_fyear' $wh
      AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$pre_last_y-$last_m-01', '%Y-%M-%d')
      $notin");

            // for ratio calculation

            $zone_report_gt = $this->data['zone_wise_gt'];
            // dd($zone_report_gt);
            $zone_report_pre_gt = $this->data['state_wise_pre'];

            $grandtotalData = [];

            foreach ($zone_report_gt as $saleRow) {
                foreach ($zone_report_pre_gt as $preSaleRow) {

                    $grandtotalData[] = [
                        'growth' => (is_numeric($saleRow->sales) && is_numeric($preSaleRow->sales) && $preSaleRow->sales != 0) ? number_format((($saleRow->sales / $preSaleRow->sales - 1) * 100), 0) . "%": "0",
                        'achive' => $saleRow->target != 0 ? number_format(($saleRow->sales / $saleRow->target) * 100, 0) . "%" : "0",
                        'personproduction' => $saleRow->man != 0 ? str_replace(",", "", number_format(($saleRow->sales / $saleRow->man), 0)) : "0",
                        'empcostratio' => $saleRow->sales != 0 ?  number_format(($saleRow->expense / $saleRow->sales) * 100, 0) . "%" : "0",
                        'samplecost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->sample / $saleRow->sales) * 100, 0) . "%" : "0",
                        'sales_return_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->sales_return / $saleRow->sales) * 100, 0) . "%" : "0",
                        'pm_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->gift / $saleRow->sales) * 100, 0) . "%" : "0",
                        'trans_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->transport / $saleRow->sales) * 100, 0) . "%" : "0",
                        'free_cost_ratio' => $saleRow->sales != 0 ? number_format(($saleRow->free_value / $saleRow->sales) * 100, 0) . "%" : "0",
                        'total_cost_ratio' => $saleRow->sales != 0 ? number_format((($saleRow->expense + $saleRow->sample + $saleRow->sales_return + $saleRow->gift + $saleRow->transport + $saleRow->free_value) / $saleRow->sales) * 100, 0) . "%" : "0",
                        'growth_two' => (is_numeric($saleRow->sec_sales) && is_numeric($preSaleRow->sec_sales) && $preSaleRow->sec_sales != 0)     ? number_format((($saleRow->sec_sales / $preSaleRow->sec_sales - 1) * 100), 0) . "%"     : "0",
                        'achive_two' => $saleRow->sec_target != 0 ? number_format(($saleRow->sec_sales / $saleRow->sec_target) * 100, 0) . "%" : "0",

                    ];

                    break;
                }
            }

            $this->data['state_wise_mis_gt'] = $grandtotalData;

            // dr call average grand total

            $this->data['doc_cl_avg'] = \DB::select("select
            ROUND(AVG (CASE WHEN (sd_docupload_t.month = '$last_m_avg') THEN sd_docupload_t.doctor_coverage ELSE 0 END), 0) as doc_cov_avg, 
            ROUND(AVG(CASE WHEN (sd_docupload_t.month = '$last_m_avg') THEN sd_docupload_t.doctor_call_average ELSE 0 END), 1) as doc_cl_avg 
            FROM `sd_docupload_t` WHERE month = '$last_m_avg' $wh1");
        }

        return view('business_mis.statewise', $this->data);
    }


    // person wise

    public function Personwise(Request $request)
    {

        // login viewers track purpose End
    
        $wh = '';
        $wh1  = '';
        
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
        if ($depart == "14" && $emp_id != '121') {

            // RSM region compare purpose
            if ($emp_reg == '41') {

                $emp_region = '';
                // emp region 
                $em_region = \DB::select("SELECT distinct region from sd_mar_employee_detail_t where employee_name='$emp_name'");

                if (!empty($em_region)) {
                    $regions = array_column($em_region, 'region');
                    $emp_region = "'" . implode("','", $regions) . "'";
                    $wh = " AND sd_prmyscdyupload_t.region IN ($emp_region)";
                    $wh1 = " AND sd_docupload_t.region IN ($emp_region)";
                } else {
                    $wh = " AND sd_prmyscdyupload_t.region IN ('')";
                    $wh1 = " AND sd_docupload_t.region IN ('')";
                }
                //end
            } else {
                //others

                $emp_zone = '';
                // emp zone 
                $em_zone = \DB::select("SELECT zone from sd_mar_employee_detail_t where employee_name='$emp_name'");

                if (!empty($em_zone)) {
                    if (isset($em_zone[0]->zone) && $em_zone[0]->zone != '') {
                        $emp_zone = $em_zone[0]->zone;
                    } else {
                        $emp_zone = '';
                    }
                }

                if (!empty($emp_zone)) {
                    $wh = "  AND sd_prmyscdyupload_t.zone ='$emp_zone'";
                    $wh1 = "  AND sd_docupload_t.zone ='$emp_zone'";
                } else {
                    $wh = " AND sd_prmyscdyupload_t.zone IN ('')";
                    $wh1 = " AND sd_docupload_t.zone IN ('')";
                }
            }
            // raja ram only show andra state purpose

            if ($emp_id == '94') {

                $wh = " AND sd_prmyscdyupload_t.state IN ('ANDHRA PRADESH')";
                $wh1 = " AND sd_docupload_t.state IN ('ANDHRA PRADESH')";
            }
        }

        // end
        
        $notin = ""; // for not in query purpose
        $notin = " $notin AND region NOT IN('EXPORT','LOCAL','GOVT SUPPLY','WB(DERMA)') AND state NOT IN('EXPORT','LOCAL','GOVT SUPPLY')";
        $l_dbmon = \DB::select("SELECT  month FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $l_dbyear = \DB::select("SELECT  c_year FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
        $last_m = $l_dbmon[0]->month;
        $last_mon = date('m', strtotime($last_m));
        $last_y = $l_dbyear[0]->c_year;
        $pre_last_y = $last_y - 1;
        $this->data['last_yr'] =  $l_dbyear[0]->c_year;
        $this->data['last_mon'] = date('m', strtotime($last_m));
        $last_data = \DB::select("SELECT  month_y FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        $this->data['last_data'] = $last_data[0]->month_y;
        $this->data['last_update'] = \DB::select("SELECT created_at FROM `sd_prmyscdyupload_t` ORDER BY `prmyscdy_upload_id` DESC LIMIT 1");
        //dd( $this->data['last_update']);
        $this->data['zones'] = \DB::select("SELECT DISTINCT zone from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $zone = $request->input('zone');
        $this->data['regions'] = \DB::select("SELECT DISTINCT region from  `sd_prmyscdyupload_t` where 1=1 $wh $notin");
        $region = $request->input('region');
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

            $fy_full_year =  $select_year . '-' . ($select_year + 1);
        }
        if ($select_month < $fy_start_month) {

            $fy_full_year =  ($select_year - 1) . '-' . $select_year;
        }

        $fy_year = substr_replace($fy_full_year, '', 5, 2);

        $select_pre = substr($fy_year, 0, 4);
        $select_pre_y = substr($fy_year, 5, 2);

        $select_pre_year =  ($select_pre - 1) . '-' . ($select_pre_y - 1);


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
            // if user select zone or ___ search 
            if ($select_date == '') {
                $fy_year = $cur_fyear;
                $select_pre_year = $pre_fyear;
                $select_year = $last_y;
                $select_month = date('m', strtotime($last_m));
                $pre_year = $last_y - 1;
                $this->data['mon_yr'] = $l_dbmon[0]->month . "'" . $l_dbyear[0]->c_year;
                $this->data['cur_fy_year'] = $cur_fyear;
                $this->data['pre_fy_year'] = $pre_fyear;
                $this->data['last_avg_mon'] = $last_data[0]->month_y;
            }


               // dd($region);
                if($region =="WB(CLASSICAL)"){
                    
                    $division_query =" AND division IN ('INSPIRE','IMPACT','CLASSICAL')";
                    
                }else{
                    
                    $division_query =" AND division IN ('INSPIRE','IMPACT')";
                }

            // current financial year record month wise

            $this->data['person_wise'] = \DB::select("SELECT u.zone, u.division, u.state, u.product_name, u.sales, v.man, ROUND((u.sales / NULLIF(v.man, 0)), 0) AS sample
        FROM (
            SELECT zone, division, state, product_name, ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'Secondary' AND type_d_s = 'stockist') THEN sales_unit END), 0) AS sales
            FROM `sd_prmyscdyupload_t`
            WHERE `f_year` LIKE '$fy_year' $condition $notin $division_query     
            GROUP BY zone, state, division, product_name
        ) u
        JOIN (
            SELECT zone, state, SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'primary') THEN sales_value ELSE 0 END) AS man
            FROM `sd_prmyscdyupload_t`
            WHERE `f_year` LIKE '$fy_year' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$select_year-$select_month-01', '%Y-%m-%d') $notin and division NOT IN ('ALPHA') $condition $wh
            GROUP BY zone, state
        ) v ON v.zone = u.zone AND v.state = u.state
        HAVING sales != 0 order by division,product_name,state ASC");
        
        } else {

            // current financial year record month wise
            $this->data['person_wise'] = \DB::select("SELECT u.zone, u.division, u.state, u.product_name, u.sales, v.man, ROUND((u.sales / NULLIF(v.man, 0)), 0) AS sample
        FROM (
            SELECT zone, division, state, product_name, ROUND(SUM(CASE WHEN (type_of_data = 'SALES' AND type = 'Secondary' AND type_d_s = 'stockist') THEN sales_unit END), 0) AS sales
            FROM `sd_prmyscdyupload_t`
            WHERE `f_year` LIKE '$cur_fyear' $notin AND division IN ('INSPIRE', 'IMPACT')
            GROUP BY zone, state, division, product_name
        ) u
        JOIN (
            SELECT zone, state, SUM(CASE WHEN (type_of_data = 'Manpower' AND type = 'Primary') THEN sales_value ELSE 0 END) AS man
            FROM `sd_prmyscdyupload_t`
            WHERE `f_year` LIKE '$cur_fyear' AND STR_TO_DATE(CONCAT(c_year, '-', month, '-01'), '%Y-%b-%d') <= STR_TO_DATE('$last_y-$last_m-01', '%Y-%M-%d') $notin and division NOT IN ('ALPHA') $wh
            GROUP BY zone, state
        ) v ON v.zone = u.zone AND v.state = u.state
        HAVING sales != 0 order by division,product_name,state ASC");
        }

        return view('business_mis.personwise', $this->data);
    }
}
