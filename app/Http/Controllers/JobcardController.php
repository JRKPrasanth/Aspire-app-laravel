<?php
namespace App\Http\Controllers;
use App\jobcard;
use App\Qualitycheck;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Config;
use Session;
use Yajra\DataTables\DataTables;

class JobcardController extends Controller
{
    /* purpose: construct function to set values throughout the controller like model,submodel,pagemethod*/
    public function __construct()
    {
        $this->data = array(
            'pageModule' => 'jobcard',
            'pageUrl' => url('jobcard')
        );
        $this->data['urlmenu'] = $this->indexs();
        $this->model = new jobcard();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->table = "w_jobcard_hdr_t";
        $this->data['pageFormtype'] = 'ajax';

        if ($this->data['pageMethod'] == "jobcard") {
            $this->data['status'] = "";
        } else if ($this->data['pageMethod'] == "jobcardrework") {
            $this->data['status'] = "REWORK";
            $this->data['type'] = "fg";
            $this->data['jobtype'] = "jobcardrework";
        } else {
            $this->data['status'] = "";

        }
    }
    /*end*/
    /* purpose:index function to redirect table blade*/
    public function index(Request $request)
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

        $this->data['pageurl'] = $this->data['pageMethod'];
        if ($this->data['pageurl'] == "jobcardrework") {
            $this->data['editurl'] = 'jobcardreworkedit';
            $this->data['source'] = 'REWORK';
        } else {
            $this->data['editurl'] = 'jobcardedit';
            $this->data['source'] = '';
        }
        if ($this->data['pageMethod'] == 'jobcard') {
            $this->data['type'] = "sfg";
            $this->data['jobtype'] = "jobcard";
        } else if ($this->data['pageMethod'] == "packingjobcardstatus") {
            $this->data['type'] = "fg";
            $this->data['jobtype'] = "packingjobcard";
        }


        $this->data['subinventory_id'] = $this->jcustomselect('m_subinventory_t', 'subinventory_id', 'subinventory_name', '5', 'and production_store="Yes"');
        $this->data['locator_id'] = $this->jcustomselect('m_sublocators_t', 'sublocator_id', 'locator_code', '191', ' and sublocator_id in(191,192)');
        $this->data['machine_seg'] = $this->jcustomselect('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', '', '');
        $this->data['emp_seg'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'employee_number|first_name', '', '');
        $this->data['machine_tray'] = $this->jcustomselect('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', '154', ' and machine_hdr_id="154"');
        $this->data['emp_tray'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'employee_number|first_name', '', '');

        return view('jobcard.table', $this->data);
    }
    /*end*/

    /* purpose: to display jobcard data */
    public function getjobcardData()
    {
			$wh = '';
        if ($_GET['status'] != '') {
            $wh .= " and  w_jobcard_hdr_t.job_status='" . $_GET['status'] . "'";

        }

        $loc = "1";
        $compy = \Session::get('companyid');
        $org = \Session::get('organization');
        $groupname = \Session::get('groupname');

        if ($_GET['status'] != 'REWORK') {

            if ($_GET['status'] != '') {

                $op = "=";
                $status_val = "'" . $_GET['status'] . "'";
                $wh .= $grid_data = $this->grid_statuscheck('t1', 'job_completion_date', 'job_status', $op, $status_val);
            } else {
                $wh .= $grid_data = $this->grid_check('t1', 'job_completion_date');
            }

        }
		
        $prdgrp = "";
        if (isset($_GET)) {
            if ($_GET['type'] == 'fg') {
                $prdgrp .= " and (m_products_t.product_group_id='1' or w_jobcard_hdr_t.job_no LIKE '%JOBOP%') ";
            } else {
                $prdgrp .= " and (m_products_t.product_group_id='4' AND w_jobcard_hdr_t.job_no NOT LIKE '%JOBOP%') ";
            }
        }

		
        if ($_GET['status'] != 'REWORK') {

            $sql = "select * from(SELECT
    w_jobcard_hdr_t.w_jobs_hdr_id,
    w_jobcard_hdr_t.segregation_status,
    w_jobcard_hdr_t.traydryer_status,
    case when w_jobcard_hdr_t.job_status='QA SUBMITTED' then 'COMPLETED' else w_jobcard_hdr_t.job_status end as job_status,
    w_jobcard_hdr_t.job_no,
	'' as reworksource,
    w_jobcard_hdr_t.job_date,
    w_jobcard_hdr_t.job_completion_date,
    w_jobcard_hdr_t.remarks,
    w_jobcard_hdr_t.job_adjusted_qty,
    w_jobcard_hdr_t.job_qty,
    w_jobcard_hdr_t.product_id,
    w_jobcard_hdr_t.reference_source_id,
	'' as qoh_detail_id,
	'' as quality_spec_trx_hdr_id,
	'' as qa_submitstage_trx_hdr_id,
w_jobcard_hdr_t.store_move_qty,
(select hr_employee_t.first_name from hr_employee_t where hr_employee_t.employee_id=w_jobcard_hdr_t.job_created_by) as first_name,
    w_jobcard_hdr_t.batch_no,
     m_products_t.concatenated_product,
     m_products_t.segregation,
     m_products_t.traydryer,
     m_products_t.product_code,
    (select m_uom_codes_t.uom_code from m_uom_codes_t where m_uom_codes_t.uom_code_id= w_jobcard_hdr_t.uom_code_id) as uom_code,
    ROUND(COALESCE(w_qa_submitstage_trx_t.production_qty,0),2) as production_qty,
	   ROUND((COALESCE(w_jobcard_hdr_t.job_adjusted_qty,0)-COALESCE(w_qa_submitstage_trx_t.production_qty,0)),2) as balancejob_qty,
    (select w_productionplan_hdr_t.plan_no from w_productionplan_hdr_t where w_productionplan_hdr_t.productionplan_hdr_id= w_jobcard_hdr_t.reference_source_id) as plan_no,
    w_jobcard_hdr_t.job_process,w_jobcard_hdr_t.company_id,w_jobcard_hdr_t.location_id
FROM
    w_jobcard_hdr_t
 JOIN m_products_t ON(
        w_jobcard_hdr_t.product_id = m_products_t.product_id
    )


    left join w_qa_submitstage_trx_t on(w_qa_submitstage_trx_t.job_no= w_jobcard_hdr_t.w_jobs_hdr_id)
    
    where 1=1 $prdgrp group by w_jobcard_hdr_t.w_jobs_hdr_id ORDER BY w_jobcard_hdr_t.w_jobs_hdr_id desc) as t1  where 1=1 $wh";

        } else {

            $sql = "select
    *
from
    (
        select
            'jobrework' as reworksource,
            i_qoh_detail_t.job_id as w_jobs_hdr_id,
            i_qoh_detail_t.job_process_name as job_process,
            i_qoh_detail_t.product_id,
            w_jobcard_hdr_t.reference_source_id,
            i_qoh_detail_t.qoh_trx_qty as store_move_qty,
            (
                select
                    w_productionplan_hdr_t.plan_no
                from
                    w_productionplan_hdr_t
                where
                    w_jobcard_hdr_t.reference_source_id = w_productionplan_hdr_t.productionplan_hdr_id
            ) as plan_no,
            'REWORK' as job_status,
            i_qoh_detail_t.batch_number as batch_no,
            w_jobcard_hdr_t.job_no as job_no,
            w_jobcard_hdr_t.job_date as job_date,
            w_jobcard_hdr_t.job_completion_date as job_completion_date,
            w_jobcard_hdr_t.remarks as remarks,
            w_jobcard_hdr_t.job_adjusted_qty,
            0 as qa_submitstage_trx_hdr_id,
            (
                select
                    tb_users.first_name
                from
                    tb_users
                where
                    tb_users.id = w_jobcard_hdr_t.job_created_by
            ) as first_name,
            w_jobcard_hdr_t.job_qty,
            m_products_t.concatenated_product,
            m_products_t.segregation,
			'' as production_qty,
			'' as balancejob_qty,
			'' as segregation_status,
            m_products_t.product_code,
            (
                select
                    m_uom_codes_t.uom_code
                from
                    m_uom_codes_t
                where
                    m_uom_codes_t.uom_code_id = i_qoh_detail_t.qoh_uom_code_id
            ) as uom_code,
            0 as quality_spec_trx_hdr_id,
            i_qoh_detail_t.rework_status,
            0 as rejection_type,
            0 as qa_status,
            i_qoh_detail_t.qoh_detail_id
        FROM
            i_qoh_detail_t
            LEFT JOIN m_products_t ON (
                i_qoh_detail_t.product_id = m_products_t.product_id
            )
            JOIN w_jobcard_hdr_t ON (
                i_qoh_detail_t.job_id = w_jobcard_hdr_t.w_jobs_hdr_id
                and i_qoh_detail_t.job_process_name = 'FILLING'
            )
        WHERE
            1 = 1
            AND i_qoh_detail_t.rework_status = 0
        union all
        SELECT
            'qcrework' as reworksource,
            w_jobcard_hdr_t.w_jobs_hdr_id,
            0 as job_process,
            w_jobcard_hdr_t.product_id,
            w_jobcard_hdr_t.reference_source_id,
            w_jobcard_hdr_t.store_move_qty,
            w_productionplan_hdr_t.plan_no,
            w_jobcard_hdr_t.job_status,
            w_jobcard_hdr_t.batch_no,
            w_jobcard_hdr_t.job_no,
            w_jobcard_hdr_t.job_date,
            w_jobcard_hdr_t.job_completion_date,
            w_jobcard_hdr_t.remarks,
            w_jobcard_hdr_t.job_adjusted_qty,
            w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id,
            tb_users.first_name,
            w_jobcard_hdr_t.job_qty,
            m_products_t.concatenated_product,
            m_products_t.segregation,
			'' as production_qty,
			'' as balancejob_qty,
			'' as segregation_status,
            m_products_t.product_code,
            m_uom_codes_t.uom_code,
            i_quality_spec_trx_hdr_t.quality_spec_trx_hdr_id,
            i_quality_spec_trx_hdr_t.rework_status,
            i_quality_spec_trx_hdr_t.rejection_type,
            i_quality_spec_trx_hdr_t.qa_status,
            0 as qoh_detail_id
        FROM
            w_jobcard_hdr_t
            LEFT JOIN m_products_t ON (
                w_jobcard_hdr_t.product_id = m_products_t.product_id
            )
            LEFT JOIN m_uom_codes_t ON (
                w_jobcard_hdr_t.uom_code_id = m_uom_codes_t.uom_code_id
            )
            LEFT JOIN tb_users ON (w_jobcard_hdr_t.job_created_by = tb_users.id)
            LEFT JOIN w_productionplan_hdr_t ON (
                w_jobcard_hdr_t.reference_source_id = w_productionplan_hdr_t.productionplan_hdr_id
            )
            LEFT JOIN i_quality_spec_trx_hdr_t ON (
                i_quality_spec_trx_hdr_t.job_hdr_id = w_jobcard_hdr_t.w_jobs_hdr_id
            )
            left join w_qa_submitstage_trx_t on (
                w_qa_submitstage_trx_t.job_no = w_jobcard_hdr_t.w_jobs_hdr_id
            )
        where
            1 = 1
            and i_quality_spec_trx_hdr_t.rejection_type = 'REWORK'
            and i_quality_spec_trx_hdr_t.qa_status = 'APPROVED'
            and w_qa_submitstage_trx_t.rework_status = 0 $wh 
        union all
        SELECT
            'salesrework' as reworksource,
            0 as w_jobs_hdr_id,
            0 as job_process,
            m_products_t.product_id,
            0 as reference_source_id,
            0 as store_move_qty,
            0 as plan_no,
            0 as job_status,
            i_qoh_detail_t.batch_number as batch_no,
            0 as job_no,
            0 as job_date,
            0 as job_completion_date,
            0 as remarks,
            0 as job_adjusted_qty,
            0 as qa_submitstage_trx_hdr_id,
            0 as first_name,
            i_qoh_detail_t.rework_qty as job_qty,
            m_products_t.concatenated_product,
            m_products_t.segregation,
			'' as production_qty,
			'' as balancejob_qty,
			'' as segregation_status,
            m_products_t.product_code,
            m_uom_codes_t.uom_code,
            0 as quality_spec_trx_hdr_id,
            i_qoh_detail_t.rework_status,
            0 as rejection_type,
            0 as qa_status,
            i_qoh_detail_t.qoh_detail_id
        FROM
            i_qoh_detail_t
            LEFT JOIN m_products_t ON (
                i_qoh_detail_t.product_id = m_products_t.product_id
            )
            LEFT JOIN m_uom_codes_t ON (
                i_qoh_detail_t.qoh_uom_code_id = m_uom_codes_t.uom_code_id
            )
        WHERE
            1 = 1
            AND i_qoh_detail_t.qoh_source = 'SALES RETURN-REWORK'
            AND i_qoh_detail_t.rework_status = 0
            AND i_qoh_detail_t.company_id = $compy
    ) as t1";

        }

        $result = \DB::select($sql);

        return DataTables::of($result)->make(true);

    }


    /* purpose: to create & update jobcard */
    public function create($id = null)
    {

        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'jobcard')->get();
        $this->data['noprd'] = "";
        $this->data['reworksrc'] = "";
		
        /* purpose: to create jobcard based on productionplan */
        if (isset($_GET['source'])) {
            if ($_GET['source'] == "PLAN") {
                $this->modelname = new jobcard();
                $this->data['row'] = (object) array();
                $table = $this->modelname->getTableColumns();
                foreach ($table as $key => $val) {
                    $this->data['row']->$val = '';
                }
                $this->data['row']->job_date = date("Y-m-d");
				$this->data['row']->job_completed_qty = 0;
                //$this->data['row']->job_date = "2023-03-31";
                if (isset($_GET['hdr'])) {
                    /*purpose: to create job card based on production plan hdr*/
                    if ($_GET['sub'] == 'hdr') {

                        $planhdr = \DB::table('w_productionplan_hdr_t')->where('productionplan_hdr_id', $_GET['hdr'])->get();
                        $planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $_GET['hdr'])->where('parent_product', $id)->where('process_level', $_GET['prs'])->get();
                        $planlinesnoprd = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $_GET['hdr'])->where('parent_product', $id)->where('product_id', '=', '0')->get();

                        if (count($planlinesnoprd) > 0) {
                            $this->data['noprd'] = "1";
                        }
                        $group = \DB::table('m_products_t')->select('m_products_t.*', 'm_product_groups_t.*')->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')->where('m_products_t.product_id', $planhdr[0]->product_id)->get();
                        if (isset($_GET['prs'])) {
                            if ($_GET['prs'] == 'FINALPROCESS') {
                                $this->data['kitpack_no'] = $group[0]->mpq_qty;
                            } else {
                                $this->data['kitpack_no'] = 0;
                            }
                        }
                        $this->data['group'] = $group[0]->group_name;

                        $sub_data = \DB::select("select `w_productionplan_lines_t`.`product_id`, `m_product_groups_t`.`group_name` from `w_productionplan_lines_t` left join `m_products_t` on `m_products_t`.`product_id` = `w_productionplan_lines_t`.`product_id` left join `m_product_groups_t` on `m_product_groups_t`.`product_group_id` = `m_products_t`.`product_group_id` where `w_productionplan_lines_t`.`productionplan_hdr_id` = '" . $planhdr[0]->productionplan_hdr_id . "' and `w_productionplan_lines_t`.`parent_product` = '" . $planhdr[0]->product_id . "' and (`m_product_groups_t`.`group_name` = 'SEMI FINISHED GOODS' OR m_products_t.concatenated_product LIKE '%COMPRESSION%')");
                        $a_in = '';
                        if (count($sub_data) > 0) {
                            foreach ($sub_data as $key1 => $value1) {
                                $a_in .= "'$value1->product_id'" . ",";
                            }

                            $b_in = rtrim($a_in, ',');

                            $comp = \Session::get('companyid');
                            $html = "<option value=''>-- Please Select --</option>";
                            $product = \DB::select('select product_id,concatenated_product from m_products_t where product_id in(' . $b_in . ')');
                            foreach ($product as $pk => $pv) {
                                $qoh = \DB::select("select * from(select *,round(sum(qoh_trx_qty),4) as qoh  from i_qoh_detail_t where product_id=" . $pv->product_id . "   and company_id=" . $comp . " and subinventory_id='5' and locator_id='191' group by batch_number ORDER BY `qoh_detail_id` desc) as f where f.qoh>0");
                                // dd($pv->product_id);
                                if (count($qoh) > 0) {
                                    foreach ($qoh as $qk => $qv) {
                                        $prdname = $qv->batch_number . "-" . $pv->concatenated_product;
                                        $html .= "<option value='" . $pv->product_id . "' >" . $prdname . "</option>";
                                    }
                                }
                            }
                            $this->data['bom_product_id'] = $html;
                        } else {
                            $this->data['bom_product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', '');
                        }
                        $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $planhdr[0]->product_id);
                        $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $planhdr[0]->uom_code_id);

                        $machineid = $this->getprdtype($planhdr[0]->product_id);


                        if ($machineid != "") {
                            $this->data['machine_hdr_id'] = $this->jcustomselectcomp('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', '', ' and machine_hdr_id in(' . $machineid . ')');
                        } else {
                            $this->data['machine_hdr_id'] = "";
                        }
                        /***** vimala purpose : assign hour based on range start **/
                        $hourdetail = $this->machinehourdetails($machineid, $planhdr[0]->product_id, $planhdr[0]->production_qty);
                        $this->data['row']->hour = $hourdetail;
                        /**** purpose : assign hour based on range end **/

                        $this->data['row']->machine_capacity = "";
                        $this->data['row']->seq_count = '';
                        $this->data['row']->job_qty = $_GET['pqty'];
                        // $this->data['row']->job_completed_qty=$_GET['pqty'];
                        $this->data['row']->bom_process = $_GET['prs'];
                        if ($_GET['jobtype'] == "packingjobcard") {
                            if ($_GET['prs'] == "") {
                                $this->data['row']->bom_process = 0;
                            } else {
                                $this->data['row']->bom_process = $_GET['prs'];
                            }
                        }
                        if (count($planlines) > 0) {
                            $this->data['row']->job_process = $planlines[0]->process_name;
                        } else {
                            $this->data['row']->job_process = "";
                        }
                        /* purpose: to get batch no based on process level*/
                        if (isset($_GET['prs'])) {
                            if ($group[0]->group_name == "FINISHED GOODS") {
                                if (($_GET['prs'] != "") && ($_GET['prs'] != '0')) {
                                    $jobprsdata = \DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='" . $_GET['prs'] . "' and product_id=" . $planhdr[0]->product_id . " and reference_source_id=" . $_GET['hdr'] . " order by w_jobs_hdr_id desc");

                                    if ($_GET['prs'] == "PROCESS-2") {
                                        $jobprs = \DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='PROCESS-1' and product_id=" . $planhdr[0]->product_id . " and reference_source_id=" . $_GET['hdr'] . " order by w_jobs_hdr_id desc");
                                    } else if ($_GET['prs'] == "PROCESS-3") {
                                        $jobprs = \DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='PROCESS-2' and product_id=" . $planhdr[0]->product_id . " and reference_source_id=" . $_GET['hdr'] . " order by w_jobs_hdr_id desc");
                                    } else if ($_GET['prs'] == "PROCESS-4") {
                                        $jobprs = \DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='PROCESS-3' and product_id=" . $planhdr[0]->product_id . " and reference_source_id=" . $_GET['hdr'] . " order by w_jobs_hdr_id desc");
                                    } else if ($_GET['prs'] == "FINALPROCESS") {
                                        $jobprs = \DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='PROCESS-4' and product_id=" . $planhdr[0]->product_id . " and reference_source_id=" . $_GET['hdr'] . " order by w_jobs_hdr_id desc");
                                        if ($jobprs[0]->w_jobs_hdr_id == null) {
                                            $job = \DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where product_id=" . $planhdr[0]->product_id . " and reference_source_id=" . $_GET['hdr'] . " group by bom_process order by w_jobs_hdr_id desc");
                                            $jobprs = \DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='" . $job[0]->bom_process . "' and product_id=" . $planhdr[0]->product_id . " and reference_source_id=" . $_GET['hdr'] . " order by w_jobs_hdr_id desc");

                                        }
                                    } else {
                                        $jobprs = array();
                                        $this->data['row']->job_adjusted_qty = $_GET['pqty'];
                                        $this->data['row']->job_completed_qty = $_GET['pqty'];
                                    }
                                    if (count($jobprs) > 0) {
                                        $this->data['row']->batch_no = $jobprs[0]->batch_no;
                                        $this->data['row']->job_adjusted_qty = $jobprs[0]->job_adjusted_qty1;
                                        $this->data['row']->job_completed_qty = $jobprs[0]->job_adjusted_qty1;
                                    } else {
                                        $this->data['row']->batch_no = "";
                                        $this->data['row']->job_adjusted_qty = $_GET['pqty'];
                                        $this->data['row']->job_completed_qty = $_GET['pqty'];
                                    }
                                    if (count($jobprsdata) > 0) {
                                        $qadata = \DB::table('w_qa_submitstage_trx_t')->where('job_no', $jobprsdata[0]->w_jobs_hdr_id)->get();
                                        $this->data['row']->process_completed_qty = $jobprsdata[0]->job_adjusted_qty1;
                                        $this->data['row']->job_adjusted_qty = $_GET['pqty'];
                                        if (count($qadata) > 0) {
                                            $this->data['row']->job_completed_qty = $qadata[0]->production_qty;
                                            $this->data['row']->process_completed_qty = $qadata[0]->production_qty;
                                        } else {
                                            $this->data['row']->job_completed_qty = 0;
                                            $this->data['row']->process_completed_qty = 0;
                                        }
                                    } else {
                                        $this->data['row']->process_completed_qty = 0;
                                    }

                                }
                            } else {
                                $this->data['row']->job_completed_qty = 0;
                                $this->data['row']->job_adjusted_qty = $_GET['pqty'];
                                $jobprsdata = \DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where product_id=" . $planhdr[0]->product_id . " and reference_source_id=" . $_GET['hdr'] . " order by w_jobs_hdr_id desc");
                                if (count($jobprsdata) > 0) {
                                    if ($jobprsdata[0]->w_jobs_hdr_id != null) {
                                        $qadata = \DB::table('w_qa_submitstage_trx_t')->where('job_no', $jobprsdata[0]->w_jobs_hdr_id)->get();
                                        $this->data['row']->process_completed_qty = $jobprsdata[0]->job_adjusted_qty1;
                                        $this->data['row']->job_adjusted_qty = $_GET['pqty'];
                                        if (count($qadata) > 0) {
                                            $this->data['row']->job_completed_qty = $qadata[0]->production_qty;
                                        } else {
                                            $this->data['row']->job_completed_qty = 0;
                                        }
                                    }
                                }
                            }
                        } else {
                            $this->data['row']->batch_no = "";
                            $this->data['row']->job_adjusted_qty = $_GET['pqty'];
                            $this->data['row']->process_completed_qty = 0;
                        }
                        /*end*/
                        $this->data['job_status'] = "OPEN";
                        $this->data['row']->reference_source = 'PLAN';
                        $this->data['row']->reference_source_id = $planhdr[0]->productionplan_hdr_id;
                        if (isset($_GET['capacityqty'])) {
                            $this->data['quantity_capacity'] = str_replace(",", "", $_GET['capacityqty']);
                        }
                    }
                    /*end*/ else {
                        /*purpose: to create job card based on production plan lines*/

                        $planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $_GET['hdr'])->where('product_id', $id)->get();
                        $group = \DB::table('m_products_t')->select('m_products_t.*', 'm_product_groups_t.*')->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')->where('m_products_t.product_id', $id)->get();
                        $this->data['group'] = $group[0]->group_name;
                        if (isset($_GET['prs'])) {
                            if ($_GET['prs'] == 'FINALPROCESS') {
                                $this->data['kitpack_no'] = $group[0]->mpq_qty;
                            } else {
                                $this->data['kitpack_no'] = 0;
                            }
                        }
                        $planhdr1 = \DB::table('w_productionplan_hdr_t')->where('productionplan_hdr_id', $_GET['hdr'])->get();
                        $sub_data = \DB::table('w_productionplan_lines_t')->select('w_productionplan_lines_t.product_id', 'm_product_groups_t.group_name')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'w_productionplan_lines_t.product_id')->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')->where('w_productionplan_lines_t.productionplan_hdr_id', $planhdr1[0]->productionplan_hdr_id)->where('w_productionplan_lines_t.parent_product', $planlines[0]->product_id)->where('m_product_groups_t.group_name', 'SEMI FINISHED GOODS')->get();
                        $a_in = '';
                        if (count($sub_data) > 0) {
                            foreach ($sub_data as $key1 => $value1) {
                                $a_in .= "'$value1->product_id'" . ",";
                            }
                            $b_in = rtrim($a_in, ',');
                            $comp = \Session::get('companyid');
                            $html = "<option value=''>-- Please Select --</option>";
                            $product = \DB::select('select product_id,concatenated_product from m_products_t where product_id in(' . $b_in . ')');
                            foreach ($product as $pk => $pv) {
                                $qoh = \DB::select("select * from(select *,round(sum(qoh_trx_qty),4) as qoh   from i_qoh_detail_t where product_id=" . $pv->product_id . "   and company_id=" . $comp . " group by batch_number ORDER BY `qoh_detail_id` desc) as f where f.qoh>0");
                                if (count($qoh) > 0) {
                                    foreach ($qoh as $qk => $qv) {
                                        $prdname = $qv->batch_number . "-" . $pv->concatenated_product;
                                        $html .= "<option value='" . $pv->product_id . "' >" . $prdname . "</option>";
                                    }
                                }
                            }
                            $this->data['bom_product_id'] = $html;
                        } else {
                            $this->data['bom_product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', '');
                        }
                        $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $planlines[0]->product_id);
                        $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $planlines[0]->uom_code_id);

                        $machineid = $this->getprdtype($planlines[0]->product_id);
                        if ($machineid != "") {
                            $this->data['machine_hdr_id'] = $this->jcustomselectcomp('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', '', ' and machine_hdr_id in(' . $machineid . ')');
                        } else {
                            $this->data['machine_hdr_id'] = "";
                        }
                        if ($planlines[0]->production_qty != 0) {
                            $this->data['row']->job_qty = $planlines[0]->pending_qty;
                            $this->data['row']->job_adjusted_qty = $planlines[0]->pending_qty;
                        } else {
                            $this->data['row']->job_qty = $planlines[0]->qty;
                            $this->data['row']->job_adjusted_qty = $planlines[0]->qty;


                        }
                        $planlines1 = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $_GET['hdr'])->where('parent_product', $id)->where('process_level', $_GET['prs'])->get();
                        if (!isset($planlines1)) {
                            $this->data['row']->job_process = $planlines1[0]->process_name;
                        } else {
                            $this->data['row']->job_process = "";
                        }
                        $this->data['row']->job_completed_qty = 0;
                        /***** vimala purpose : assign hour based on range start **/
                        $hourdetail = $this->machinehourdetails($machineid, $id, $planlines[0]->qty);
                        $this->data['row']->hour = $hourdetail;
                        /**** purpose : assign hour based on range end **/
                        $this->data['row']->machine_capacity = "";

                        /* purpose: to get batch no based on process level*/
                        if (isset($_GET['prs'])) {
                            if ($group[0]->group_name == "FINISHED GOODS") {

                                if (($_GET['prs'] != "") && ($_GET['prs'] != '0')) {
                                    $jobprsdata = \DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='" . $_GET['prs'] . "' and product_id=" . $planhdr[0]->product_id . " and reference_source_id=" . $_GET['hdr'] . " order by w_jobs_hdr_id desc");
                                    if ($_GET['prs'] == "PROCESS-2") {
                                        $jobprs = \DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='PROCESS-1' and product_id=" . $planhdr[0]->product_id . " and reference_source_id=" . $_GET['hdr'] . " order by w_jobs_hdr_id desc");
                                    } else if ($_GET['prs'] == "PROCESS-3") {
                                        $jobprs = \DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='PROCESS-2' and product_id=" . $planhdr[0]->product_id . " and reference_source_id=" . $_GET['hdr'] . " order by w_jobs_hdr_id desc");
                                    } else if ($_GET['prs'] == "PROCESS-4") {
                                        $jobprs = \DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='PROCESS-3' and product_id=" . $planhdr[0]->product_id . " and reference_source_id=" . $_GET['hdr'] . " order by w_jobs_hdr_id desc");
                                    } else if ($_GET['prs'] == "FINALPROCESS") {
                                        $jobprs = \DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='PROCESS-4' and product_id=" . $planhdr[0]->product_id . " and reference_source_id=" . $_GET['hdr'] . " order by w_jobs_hdr_id desc");
                                        if ($jobprs[0]->w_jobs_hdr_id == null) {
                                            $job = \DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where product_id=" . $planhdr[0]->product_id . " and reference_source_id=" . $_GET['hdr'] . " group by bom_process order by w_jobs_hdr_id desc");
                                            $jobprs = \DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='" . $job[0]->bom_process . "' and product_id=" . $planhdr[0]->product_id . " and reference_source_id=" . $_GET['hdr'] . " order by w_jobs_hdr_id desc");

                                        }
                                    } else {
                                        $jobprs = array();
                                        $this->data['row']->job_adjusted_qty = $_GET['pqty'];
                                        $this->data['row']->job_completed_qty = $_GET['pqty'];
                                    }
                                    if (count($jobprs) > 0) {
                                        $this->data['row']->batch_no = $jobprs[0]->batch_no;
                                        $this->data['row']->job_adjusted_qty = $jobprs[0]->job_adjusted_qty1;
                                        $this->data['row']->job_completed_qty = $jobprs[0]->job_adjusted_qty1;
                                    } else {
                                        $this->data['row']->batch_no = "";
                                        $this->data['row']->job_adjusted_qty = $_GET['pqty'];
                                        $this->data['row']->job_completed_qty = $_GET['pqty'];
                                    }
                                    if (count($jobprsdata) > 0) {

                                        $this->data['row']->process_completed_qty = $jobprsdata[0]->job_adjusted_qty1;
                                        $this->data['row']->job_adjusted_qty = $_GET['pqty'];
                                    } else {
                                        $this->data['row']->process_completed_qty = 0;
                                    }

                                }
                            }
                        } else {
                            $this->data['row']->batch_no = "";
                            $this->data['row']->process_completed_qty = 0;

                        }
                        /*end*/
                        $this->data['job_status'] = "OPEN";
                        $this->data['row']->reference_source = 'PLAN';
                        $this->data['row']->reference_source_id = $_GET['hdr'];
                        $this->data['row']->bom_process = $_GET['prs'];

                        if (isset($_GET['capacityqty'])) {
                            $this->data['quantity_capacity'] = $_GET['capacityqty'];
                        }
                    }
                    /*end*/
                }

                $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));

                if (isset($_GET)) {
                    if ($_GET['jobtype'] == 'packingjobcard') {

                        $this->data['job_assigned_to'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'employee_number|first_name', '', ' and group_type=10');
                    } else {
                        $this->data['job_assigned_to'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'employee_number|first_name', '', ' and group_type=8');
                    }
                }
                $this->data['job_created_by'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', \Session::get('emp_id'));
                $this->data['row']->qa_submitstage_trx_hdr_id = "";
                $this->data['pagemode'] = "create";
            }
            /* purpose: job card rework*/ else if ($_GET['source'] == "Rework") {
                $this->data['reworksrc'] = $_GET['reworksrc'];
                if ($_GET['reworksrc'] == 'qcrework') {
                    $jobcardrk = Qualitycheck::find($id);
                    $jobid = $jobcardrk['job_hdr_id'];
                    $refid = $jobcardrk['quality_spec_trx_hdr_id'];
                    $bomprcs = $jobcardrk['bom_process'];
                    $jobprcs = $jobcardrk['job_process'];
                } else {
                    $jobcardrk = \DB::select("select * from i_qoh_detail_t where qoh_detail_id=" . $id);
                    $jobid = $jobcardrk[0]->job_id;
                    $refid = $id;
                    $bomprcs = '';
                    $jobprcs = $jobcardrk[0]->job_process_name;
                }
                $this->data['row'] = (object) array();
                $jobcarddetails = \DB::select("select * from w_jobcard_hdr_t where w_jobs_hdr_id=" . $jobid);
                $this->data['pagemode'] = "create";
                $this->data['row']->w_jobs_hdr_id = '';
                $this->data['row']->qa_submitstage_trx_hdr_id = $_GET['qaid'];
                $this->data['row']->seq_count = $jobcarddetails[0]->seq_count;
                $this->data['row']->job_no = '';
                $this->data['row']->remarks = '';
                $this->data['row']->job_completion_date = '';
                $this->data['row']->start_date = date("Y-m-d");
                $this->data['row']->end_date = '';
                $this->data['row']->job_date = date("Y-m-d");
                $this->data['row']->reference_source = 'REWORK';
                $this->data['row']->reference_source_id = $refid;
                $this->data['row']->bom_process = $bomprcs;
                $this->data['row']->job_process = $jobprcs;
                $this->data['row']->job_completed_qty = 0;
                $this->data['row']->process_completed_qty = 0;
                $this->data['kitpack_no'] = $jobcarddetails[0]->kitpack_no;
                $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $jobcarddetails[0]->product_id);
                $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $jobcarddetails[0]->uom_code_id);
                $this->data['row']->batch_no = $jobcarddetails[0]->batch_no;
                $group = \DB::table('m_products_t')->select('m_products_t.*', 'm_product_groups_t.*')->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')->where('m_products_t.product_id', $jobcarddetails[0]->product_id)->get();
                $this->data['group'] = $group[0]->group_name;
                $sub_data = \DB::table('w_productionplan_lines_t')->select('w_productionplan_lines_t.product_id', 'm_product_groups_t.group_name')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'w_productionplan_lines_t.product_id')->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')->where('w_productionplan_lines_t.productionplan_hdr_id', $jobcarddetails[0]->reference_source_id)->where('w_productionplan_lines_t.parent_product', $jobcarddetails[0]->product_id)->where('m_product_groups_t.group_name', 'SEMI FINISHED GOODS')->get();
                $this->data['quantity_capacity'] = 0;
                $a_in = '';
                if (count($sub_data) > 0) {
                    foreach ($sub_data as $key1 => $value1) {
                        $a_in .= "'$value1->product_id'" . ",";
                    }
                    $b_in = rtrim($a_in, ',');
                    $comp = \Session::get('companyid');
                    $html = "<option value=''>-- Please Select --</option>";
                    $product = \DB::select('select product_id,concatenated_product from m_products_t where product_id in(' . $b_in . ')');
                    foreach ($product as $pk => $pv) {
                        $qoh = \DB::select("select * from(select *,round(sum(qoh_trx_qty),4) as qoh   from i_qoh_detail_t where product_id=" . $pv->product_id . "   and company_id=" . $comp . " group by batch_number ORDER BY `qoh_detail_id` desc) as f where f.qoh>0");
                        if (count($qoh) > 0) {
                            foreach ($qoh as $qk => $qv) {
                                $prdname = $qv->batch_number . "-" . $pv->concatenated_product;
                                $html .= "<option value='" . $pv->product_id . "' >" . $prdname . "</option>";
                            }
                        }
                    }
                    $this->data['bom_product_id'] = $html;
                } else {
                    $this->data['bom_product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', '');
                }
                $machineid = $this->getprdtype($jobcarddetails[0]->product_id);
                if ($machineid != "") {
                    $this->data['machine_hdr_id'] = $this->jcustomselectcomp('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', '', ' and machine_hdr_id in(' . $machineid . ')');
                } else {
                    $this->data['machine_hdr_id'] = $this->jcustomselectcomp('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', '');
                }
                $this->data['row']->machine_capacity = "";

                if ($_GET['reworksrc'] == 'qcrework') {
                    if ($jobcardrk['qc_type'] == "qtywise") {
                        $this->data['row']->job_qty = $jobcardrk['accepted_qty'];
                        $this->data['row']->job_adjusted_qty = $jobcardrk['accepted_qty'];
                        /***** vimala purpose : assign hour based on range start **/
                        $hourdetail = $this->machinehourdetails($machineid, $jobcarddetails[0]->product_id, $jobcardrk['accepted_qty']);
                        $this->data['row']->hour = $hourdetail;
                        /**** purpose : assign hour based on range end **/
                    } else {
                        $this->data['row']->job_qty = $jobcardrk['production_qty'];
                        $this->data['row']->job_adjusted_qty = $jobcardrk['production_qty'];
                        /***** vimala purpose : assign hour based on range start **/
                        $hourdetail = $this->machinehourdetails($machineid, $jobcarddetails[0]->product_id, $jobcardrk['production_qty']);
                        $this->data['row']->hour = $hourdetail;
                        /**** purpose : assign hour based on range end **/
                    }
                } else {
                    $this->data['row']->job_qty = $jobcardrk[0]->qoh_trx_qty;
                    $this->data['row']->job_adjusted_qty = $jobcardrk[0]->qoh_trx_qty;
                    /***** vimala purpose : assign hour based on range start **/
                    $hourdetail = $this->machinehourdetails($machineid, $jobcarddetails[0]->product_id, $jobcardrk[0]->qoh_trx_qty);
                    $this->data['row']->hour = $hourdetail;
                }
                $this->data['job_status'] = "JOBCARD REWORK";
                $this->data['job_assigned_to'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'employee_number|first_name', '', ' and group_type=10');

                $this->data['job_created_by'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', \Session::get('emp_id'));
            }
            /*end*/ else if ($_GET['source'] == "Returnrework") {
                $job = \DB::select('select seq_count from w_jobcard_hdr_t where 1=1 order by w_jobs_hdr_id desc');
                $qohdetail = \DB::select('select * from i_qoh_detail_t where qoh_detail_id=' . $id);
                $this->data['row'] = (object) array();
                $this->data['pagemode'] = "create";
                $this->data['reworksrc'] = $_GET['reworksrc'];
                $this->data['row']->w_jobs_hdr_id = '';
                $this->data['row']->qa_submitstage_trx_hdr_id = $_GET['qaid'];
                $this->data['row']->seq_count = $job[0]->seq_count;
                $this->data['row']->job_no = '';
                $this->data['row']->remarks = '';
                $this->data['row']->job_completion_date = '';
                $this->data['row']->start_date = date("Y-m-d");
                $this->data['row']->end_date = '';
                $this->data['row']->job_date = date("Y-m-d");
                $this->data['row']->reference_source = 'RETURNREWORK';
                $this->data['row']->reference_source_id = $qohdetail[0]->qoh_detail_id;
                $this->data['row']->bom_process = "";
                $this->data['row']->job_process = "";
                $this->data['row']->job_completed_qty = 0;
                $this->data['row']->process_completed_qty = 0;
                $this->data['kitpack_no'] = "";
                $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $qohdetail[0]->product_id);
                $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $qohdetail[0]->qoh_uom_code_id);
                $this->data['row']->batch_no = $qohdetail[0]->batch_number;
                $group = \DB::table('m_products_t')->select('m_products_t.*', 'm_product_groups_t.*')->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')->where('m_products_t.product_id', $qohdetail[0]->product_id)->get();
                $this->data['group'] = $group[0]->group_name;

                $this->data['quantity_capacity'] = 0;

                $this->data['bom_product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'and product_id=' . $qohdetail[0]->product_id);
                /*}*/
                $machineid = $this->getprdtype($qohdetail[0]->product_id);
                if ($machineid != "") {
                    $this->data['machine_hdr_id'] = $this->jcustomselectcomp('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', '', ' and machine_hdr_id in(' . $machineid . ')');
                } else {
                    $this->data['machine_hdr_id'] = $this->jcustomselectcomp('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', '', '');
                }
                $this->data['row']->machine_capacity = "";
                $this->data['row']->job_qty = $qohdetail[0]->rework_qty;
                $this->data['row']->job_adjusted_qty = $qohdetail[0]->rework_qty;
                /***** vimala purpose : assign hour based on range start **/
                $hourdetail = $this->machinehourdetails($machineid, $qohdetail[0]->product_id, $qohdetail[0]->rework_qty);
                $this->data['row']->hour = $hourdetail;
                /**** purpose : assign hour based on range end **/

                $this->data['job_status'] = "JOBCARD REWORK";

                $this->data['job_assigned_to'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'employee_number|first_name', '', ' and group_type=10');

                $this->data['job_created_by'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', \Session::get('emp_id'));
            }
        } else {
            if ($id == "") {
                $this->modelname = new jobcard();
                $this->data['row'] = (object) array();
                $table = $this->modelname->getTableColumns();
                foreach ($table as $key => $val) {
                    $this->data['row']->$val = '';
                }
                $this->data['pagemode'] = "create";
                $this->data['row']->qa_submitstage_trx_hdr_id = "";
                $this->data['row']->job_date = date("Y-m-d");
                //$this->data['row']->job_date = "2023-03-31";
                $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $table[0]->product_id);
                $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $table[0]->uom_code_id);
                $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);

                if (isset($_GET)) {
                    if ($_GET['jobtype'] == 'packingjobcard') {
                        $this->data['job_assigned_to'] = $this->jcustommultiselect('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]->job_assigned_to, ' and group_type=10');
                    } else {
                        $this->data['job_assigned_to'] = $this->jcustommultiselect('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]->job_assigned_to, ' and group_type=8');
                    }
                }
                $this->data['job_created_by'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]->job_created_by);
                $this->data['row']->bom_process = "";
                $this->data['row']->job_process = "";
                $this->data['row']->job_completed_qty = 0;
                $this->data['row']->process_completed_qty = 0;
            } else {

                /* purpose: to update jobcard*/
                $this->data['pagemode'] = "edit";
                $this->data['id'] = $id;
                $table = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $id)->get();
                $this->data['row'] = $table[0];
                $this->data['reworksrc'] = $table[0]->reworksrc;
                $this->data['kitpack_no'] = $table[0]->kitpack_no;
                $this->data['row']->qa_submitstage_trx_hdr_id = "";
                $this->data['bom_product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $table[0]->bom_product_id);
                $group = \DB::table('m_products_t')->select('m_products_t.*', 'm_product_groups_t.*')->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')->where('m_products_t.product_id', $table[0]->product_id)->get();
                $this->data['group'] = $group[0]->group_name;
                $this->data['quantity_capacity'] = '100';
                $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $table[0]->product_id);
                $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $table[0]->uom_code_id);
                $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);

                if (isset($_GET)) {
                    if ($_GET['jobtype'] == 'packingjobcard') {
                        $this->data['job_assigned_to'] = $this->jcustommultiselect('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]->job_assigned_to, ' and group_type=10');
                    } else {
                        $this->data['job_assigned_to'] = $this->jcustommultiselect('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]->job_assigned_to, ' and group_type=8');
                    }
                }
                $this->data['job_created_by'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]->job_created_by);
                //dd($table[0]->batch_no);
                $this->data['batch_no'] = $table[0]->batch_no;
                $this->data['machine_hdr_id'] = $this->jCombocomp('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', $table[0]->machine_hdr_id);
                $this->data['row']->machine_capacity = $table[0]->machine_capacity;
                $this->data['job_status'] = "OPEN";
                $this->data['row']->batch_no = $table[0]->batch_no;
                $this->data['row']->bom_process = $table[0]->bom_process;
                $this->data['row']->job_process = $table[0]->job_process;
                $this->data['row']->job_completed_qty = $table[0]->job_adjusted_qty;
                $jobprsdata = \DB::select("select *,sum(job_adjusted_qty) as job_adjusted_qty1 from w_jobcard_hdr_t where bom_process='" . $table[0]->bom_process . "' and product_id=" . $table[0]->product_id . " and reference_source_id=" . $table[0]->reference_source_id . " order by w_jobs_hdr_id desc");
                $processqty = $jobprsdata[0]->job_adjusted_qty1 - $table[0]->job_qty;
                $this->data['row']->process_completed_qty = $processqty;
                /*end*/
            }
        }
        if (isset($_GET['jobtype'])) {
            if ($_GET['jobtype'] == 'packingjobcard') {

                $this->data['pgurl'] = 'packingjobcardstatus';
            } else {
                $this->data['pgurl'] = 'jobcard';
            }
        } else {
            $this->data['pgurl'] = 'jobcard';
        }
        $this->data['row']->process_completed_qty = 0;
		
        return view('jobcard.form', $this->data);

    }



    /* purpose: to save jobcard*/
    public function save(Request $request)
    {
       
        $id = '';
        if (isset($_POST['bom_product_id'])) {
            $bomproduct = $_POST['bom_product_id'];
        } else {
            $bomproduct = "";
        }
        
        $primary = self::findPrimarykey('w_jobcard_hdr_t');
        $jobcard = new jobcard();
        if ($_POST['job_no'] == "") {
            if ($_POST['reference_source'] == 'REWORK' || $_POST['reference_source'] == 'RETURNREWORK') {
                $seqno = $this->Seqno('JOBRK', 'w_jobcard_hdr_t', '');
                $jobcard->job_no = $seqno;
                $jobcard->batch_no = $_POST['batch_no'];
            } else {

                if ($_POST['pgurl'] == "packingjobcardstatus") {
                    $seqno = $this->Seqno('JOBOP', 'w_jobcard_hdr_t', '');
                } else {
                    $seqno = $this->Seqno('JOBPR', 'w_jobcard_hdr_t', '');
                }

                $jobcard->job_no = $seqno;
                $month = date('m');
                $year = date('y');
                $sql = \DB::select('select * from w_jobcard_hdr_t where product_id=' . $_POST['product_id'] . ' order by w_jobs_hdr_id desc');
                if (count($sql) > 0) {
                    $seq = $sql[0]->seq_count;
                } else {
                    $seq = 0;
                }
                $cat = \DB::table('m_products_t')->select('m_products_t.*', 'm_product_category_t.*')->leftjoin('m_product_category_t', 'm_product_category_t.product_category_id', '=', 'm_products_t.product_category_id')->where('m_products_t.product_id', $_POST['product_id'])->get();
                $catname = "";
                if (count($cat) > 0) {
                    $catname = $cat[0]->category_name;
                }
                if ($catname == "COSMETICS") {
                    $seqno1 = $this->BatchSeqno("V", "/" . $year, $seq, 'w_jobcard_hdr_t', '');
                } else {
                    $seqno1 = $this->BatchSeqno("", "/" . $year, $seq, 'w_jobcard_hdr_t', '');
                }

                if ($bomproduct == "") {
                    if ($_POST['batch_no'] == "0") {

                        $jobcard->batch_no = $seqno1;

                    } else {

                        $jobcard->batch_no = $_POST['batch_no'];

                    }
                } else {

                    $group = \DB::table('m_products_t')->select('m_products_t.*', 'm_product_groups_t.*')->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')->where('m_products_t.product_id', $_POST['product_id'])->get();
                    if ($group[0]->group_name != 'FINISHED GOODS') {
                        $jobcard->batch_no = '';

                    } else {

                        if (isset($_POST['batch_no'])) {
                            $jobcard->batch_no = $_POST['batch_no'];
                        } else {
                            $jobcard->batch_no = "";
                        }
                    }
                }
            
                $sequence = explode("/", $seqno1);

            }

        } else {
            $seqno = $_POST['job_no'];

        }
        \DB::beginTransaction();

        if ($_POST['w_jobs_hdr_id'] != "") {
            $jobcard->w_jobs_hdr_id = $_POST['w_jobs_hdr_id'];
        }

        $jobcard->job_date = $_POST['job_date'];
        $jobcard->reworksrc = $_POST['reworksrc'];
        $jobcard->job_process = $_POST['job_process'];
        $jobcard->job_completion_date = $_POST['job_completion_date'];
        $jobcard->remarks = $_POST['remarks'];
        $jobcard->product_id = $_POST['product_id'];
        $jobcard->uom_code_id = $_POST['uom_code_id'];
        $jobcard->job_qty = $_POST['job_qty'];
        if (isset($_POST['kitpack_no'])) {
            $jobcard->kitpack_no = $_POST['kitpack_no'];
        } else {
            $jobcard->kitpack_no = "";
        }
        $jobcard->job_status = $_POST['job_status'];
        $jobcard->bom_product_id = $bomproduct;
        $jobcard->job_adjusted_qty = $_POST['job_adjusted_qty'];
        $jobcard->job_created_by = $_POST['job_created_by'];
        $jobcard->reference_source = $_POST['reference_source'];
        $jobcard->reference_source_id = $_POST['reference_source_id'];
        $jobcard->machine_hdr_id = $_POST['machine_hdr_id'];
        $jobcard->machine_capacity = $_POST['machine_capacity'];
        $jobcard->hour = $_POST['hour'];
        $assigned_to = implode(",", $_POST['job_assigned_to']);
        $jobcard->job_assigned_to = $assigned_to;
        $jobcard->remarks = $_POST['remarks'];
        $jobcard->bom_process = $_POST['bom_process'];
        $jobcard->organization_id = \Session::get('organization');
        $jobcard->company_id = \Session::get('companyid');
        $jobcard->location_id = "1";
        try {

            if ($_POST['w_jobs_hdr_id'] == "") {


                $id = $this->insertData($this->model, $primary, $jobcard, $_POST['w_jobs_hdr_id'], 'jobcard');
                if ($_POST['seq_count'] == "") {
                    if ($catname == "COSMETICS") {
                        $sequenceno = $sequence[1];
                    } else {
                        $sequenceno = $sequence[0];
                    }
                    \DB::table('w_jobcard_hdr_t')
                        ->where('w_jobs_hdr_id', $id)
                        ->update(['seq_count' => $sequenceno]);
                }

                if ($_POST['reference_source'] == "REWORK") {

                    $product_id = $_POST['product_id'];
                    $qaid = $_POST['qa_submitstage_trx_hdr_id'];
                    \DB::table('w_qa_submitstage_trx_t')
                        ->where('qa_submitstage_trx_hdr_id', $qaid)
                        ->where('product_id', $product_id)
                        ->update(['rework_status' => 1]);
                    if ($_POST['reworksrc'] == "qcrework") {
                        $p_id = $_POST['reference_source_id'];
                        $product_id = $_POST['product_id'];
                        \DB::table('i_quality_spec_trx_hdr_t')
                            ->where('quality_spec_trx_hdr_id', $p_id)
                            ->where('product_id', $product_id)
                            ->update(['rework_status' => 1]);

                    } else if ($_POST['reworksrc'] == "jobrework") {
                        $p_id = $_POST['reference_source_id'];
                        $product_id = $_POST['product_id'];
                        \DB::table('i_qoh_detail_t')
                            ->where('qoh_detail_id', $p_id)
                            ->where('product_id', $product_id)
                            ->update(['rework_status' => 1]);
                    }
                } else {
                    $p_id = explode(",", $_POST['reference_source_id']);
                    $product_id = $_POST['product_id'];
                    if ($_POST['pgurl'] != "packingjobcardstatus") {
                        foreach ($p_id as $key => $value) {
                            $plan = \DB::select('select * from w_productionplan_hdr_t where productionplan_hdr_id=' . $value . ' and product_id=' . $product_id);
                            if (count($plan) > 0) {
                                $planqty = $plan[0]->plan_qty + $_POST['job_adjusted_qty'];
                                if ($plan[0]->pending_qty == 0) {
                                    $pendqty = $_POST['job_adjusted_qty'];
                                } else {
                                    $pendqty = $plan[0]->production_qty - $planqty;
                                }
                                if ($_POST['bom_process'] == '' || $_POST['bom_process'] == "FINALPROCESS") {
                                    \DB::table('w_productionplan_hdr_t')->where('productionplan_hdr_id', $value)->where('product_id', $_POST['product_id'])->update(['plan_qty' => $planqty, 'pending_qty' => $pendqty]);
                                }
                            }
                            if ($_POST['bom_process'] == '') {
                                $planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $value)->where('product_id', $_POST['product_id'])->get();
                            } else {
                                $planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $value)->where('parent_product', $_POST['product_id'])->where('process_level', $_POST['bom_process'])->get();

                            }

                            if (count($planlines) > 0) {

                                $qty = $plan[0]->production_qty;
                                $prdqty = $planlines[0]->production_qty + $_POST['job_adjusted_qty'];
                                if ($planlines[0]->pending_qty == 0) {
                                    $pendingqty = $planlines[0]->pending_qty - $_POST['job_adjusted_qty'];
                                } else {
                                    $pendingqty = $qty - $prdqty;
                                }
                                if ($_POST['bom_process'] == '') {
                                    \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $value)->where('product_id', $_POST['product_id'])->update(['production_qty' => $prdqty, 'pending_qty' => $pendingqty]);
                                } else {
                                    \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $value)->where('parent_product', $_POST['product_id'])->where('process_level', $_POST['bom_process'])->update(['production_qty' => $prdqty, 'pending_qty' => $pendingqty]);

                                }

                            }

                            if ($_POST['bom_process'] == "FINALPROCESS") {
                                \DB::table('w_productionplan_hdr_t')
                                    ->where('productionplan_hdr_id', $value)
                                    ->where('product_id', $product_id)
                                    ->update(['job_card_status' => 1, 'status' => 1]);
                            } else if ($_POST['bom_process'] != "" && $_POST['bom_process'] != "FINALPROCESS" && $_POST['bom_process'] != "0") {
                                \DB::table('w_productionplan_lines_t')
                                    ->where('productionplan_hdr_id', $value)
                                    ->where('parent_product', $product_id)
                                    ->where('process_level', $_POST['bom_process'])
                                    ->update(['job_card_status' => 1]);
                            }

                        }
                    } else {
                        foreach ($p_id as $key => $value) {
                            \DB::table('w_productionplan_hdr_t')
                                ->where('productionplan_hdr_id', $value)
                                ->where('product_id', $product_id)
                                ->update(['job_card_status' => 1]);
                        }
                    }
                }
                if ($_POST['reference_source'] == "RETURNREWORK") {

                    $p_id = $_POST['reference_source_id'];
                    $product_id = $_POST['product_id'];
                    \DB::table('i_qoh_detail_t')
                        ->where('qoh_detail_id', $p_id)
                        ->where('product_id', $product_id)
                        ->update(['rework_status' => 1]);



                }
            } else {
                if (isset($_POST['kitpack_no'])) {
                    $kitpck = $_POST['kitpack_no'];
                } else {
                    $kitpck = "";
                }


                $data = \DB::table('w_jobcard_hdr_t')
                    ->where($primary, $_POST['w_jobs_hdr_id'])
                    ->update([
                        'job_no' => $_POST['job_no'],
                        'w_jobs_hdr_id' => $_POST['w_jobs_hdr_id'],
                        'job_date' => $_POST['job_date'],
                        'job_completion_date' => $_POST['job_completion_date'],
                        'remarks' => $_POST['remarks'],
                        'uom_code_id' => $_POST['uom_code_id'],
                        'product_id' => $_POST['product_id'],
                        'job_qty' => $_POST['job_qty'],
                        'batch_no' => $_POST['batch_no'],
                        'job_status' => $_POST['job_status'],
                        'kitpack_no' => $kitpck,
                        'bom_process' => $_POST['bom_process'],
                        'job_process' => $_POST['job_process'],
                        'bom_product_id' => $bomproduct,
                        'hour' => $_POST['hour'],
                        'job_adjusted_qty' => $_POST['job_adjusted_qty'],
                        'job_created_by' => $_POST['job_created_by'],
                        'machine_hdr_id' => $_POST['machine_hdr_id'],
                        'job_assigned_to' => $assigned_to,
                        'organization_id' => \Session::get('organization'),
                        'location_id' => \Session::get('location'),
                        'company_id' => \Session::get('companyid'),
                        'reference_source' => $_POST['reference_source']
                    ]);
                $id = $_POST['w_jobs_hdr_id'];
                /* purpose:audit log*/
                $this->auditlog($id, "jobcard", 'update', $_POST, "w_jobcard_hdr_t");
                /*end*/
            }
            /* purpose:jobcard reserve qoh*/
            $jobhdr = \DB::select('select * from w_jobcard_hdr_t where w_jobs_hdr_id=' . $id);
            $planhdr = \DB::select('select * from w_productionplan_hdr_t where productionplan_hdr_id=' . $jobhdr[0]->reference_source_id);
            if ($_POST['reference_source'] != "REWORK" && $_POST['reference_source'] != "RETURNREWORK") {

                $plan = \DB::table('w_productionplan_lines_t')->where('parent_product', $_POST['product_id'])->where('productionplan_hdr_id', $jobhdr[0]->reference_source_id)->get();
                foreach ($plan as $key => $value) {
                    $comp = \Session::get('companyid');
                    /* purpose:find other plan reserve entry and insert data based on other plan*/
                    $opreserveqoh = \DB::select("SELECT reserv_trx_qty,product_id,reference_no from i_reservation_detail_t where product_id='" . $value->product_id . "' and company_id='" . $comp . "' and reference_no!='" . $planhdr[0]->plan_no . "' GROUP by reference_no ");
                    $ref_no = "";
                    foreach ($opreserveqoh as $rk => $rval) {
                        $plandata = \DB::select("SELECT * FROM w_productionplan_hdr_t where plan_no='" . $rval->reference_no . "'");
                        if (count($plandata) > 0) {
                            $ref_no .= $plandata[0]->productionplan_hdr_id . ",";
                        }
                    }
                    $ref_no1 = rtrim($ref_no, ",");
                    $refarray = explode(",", $ref_no1);
                    $jobcheckdata = \DB::table('w_jobcard_hdr_t')->select('reference_source_id')->whereIn('reference_source_id', explode(",", $ref_no1))->get();
                    $jobarray = array();
                    $job = "";
                    foreach ($jobcheckdata as $jk => $jval) {
                        $job .= $jval->reference_source_id . ",";
                    }
                    $job1 = rtrim($job, ",");
                    $jobarray = explode(",", $job1);
                    $refdata = array_diff($refarray, $jobarray);
                    $refno = "";
                    foreach ($refdata as $dk => $dval) {
                        $plandata = \DB::select("SELECT * FROM w_productionplan_hdr_t where productionplan_hdr_id='" . $dval . "'");
                        if (count($plandata) > 0) {
                            $refno .= "'" . $plandata[0]->plan_no . "'" . ",";
                        }
                    }
                    $refno1 = rtrim($refno, ",");
                    $rqty11 = 0;
                    if ($refno1 != "") {
                        $rqty1 = "";
                        $refno11 = explode(",", $refno1);


                    }
                    /*end*/
                    $trsnsType = \DB::table('m_transaction_types_t')->where('transaction_type_code', 'JOB CARD RESERVE')->get();
                    $trsnsType = json_decode(json_encode($trsnsType), true);
                    $data1['trx_source_type_id'] = $trsnsType[0]['transaction_source_id'];
                    $data1['trx_action_id'] = $trsnsType[0]['transaction_action_id'];
                    $data1['trx_type_id'] = $trsnsType[0]['transaction_type_id'];
                    $data1['product_id'] = $value->product_id;
                    $data1['trx_qty'] = $value->component_qty * $_POST['job_adjusted_qty'];
                    $data1['trx_source_hdr_id'] = $id;
                    $data1['trx_reference'] = "JOB CARD RESERVE";
                    $data1['trx_uom'] = $value->uom_code_id;
                    $prd = \DB::select("select * from m_products_t where product_id=" . $value->product_id);
                    if (!empty($prd[0]->subinventory_id)) {
                        $data1['subinventory_id'] = $prd[0]->subinventory_id;
                    } else {
                        $data1['subinventory_id'] = 0;
                    }
                    if (!empty($prd[0]->locator_id)) {
                        $data1['locator_id'] = $prd[0]->sublocator_id;
                    } else {
                        $data1['locator_id'] = 0;
                    }
                    $mtlid = \DB::table('m_material_trx_t')->insertGetId($data1);
                    $data1['trx_qty'] = -($value->component_qty * $_POST['job_adjusted_qty']);
                    $id1 = \DB::table('m_material_trx_t')->insertGetId($data1);

                    $QOHdata['product_id'] = $value->product_id;
                    $QOHdata['reserv_trx_qty'] = $value->component_qty * $_POST['job_adjusted_qty'];
                    $QOHdata['reserv_uom_code_id'] = $value->uom_code_id;
                    $QOHdata['reference_no'] = $jobcard->job_no;
                    $QOHdata['reference_source'] = 'JOBCARD RESERVE';
                    if (!empty($prd[0]->subinventory_id)) {
                        $QOHdata['subinventory_id'] = $prd[0]->subinventory_id;
                    } else {
                        $QOHdata['subinventory_id'] = 0;
                    }
                    if (!empty($prd[0]->sublocator_id)) {
                        $QOHdata['locator_id'] = $prd[0]->sublocator_id;
                    } else {

                        $QOHdata['locator_id'] = 0;
                    }
                    $QOHdata['organization_id'] = \Session::get('organization');
                    $QOHdata['company_id'] = \Session::get('companyid');
                    $QOHdata['location_id'] = \Session::get('location');
                    $QOHdata['create_trx_id'] = $mtlid;
                    \DB::table('i_reservation_detail_t')->insert($QOHdata);
                    $res = \DB::select('select * from i_reservation_detail_t where product_id=' . $value->product_id . ' and reference_no="' . $planhdr[0]->plan_no . '" and reference_source="MATERIAL PLAN"');
                    if (count($res) > 0) {
                        $QOHdata['reference_no'] = $planhdr[0]->plan_no;
                        $QOHdata['reference_source'] = "MATERIAL PLAN";
                        $QOHdata['reserv_trx_qty'] = -($value->component_qty * $_POST['job_adjusted_qty']);
                        $QOHdata['create_trx_id'] = $id1;
                        \DB::table('i_reservation_detail_t')->insert($QOHdata);
                    } else {
                        $tqty = $value->component_qty * $_POST['job_adjusted_qty'];
                        $res_qty = 0;
                        $resbal_qty = 0;
                        $i = 0;
                        if ($refno1 != "") {
                            foreach ($refno11 as $qk => $qval) {
                                $opreserveqty1 = \DB::select("SELECT reserv_trx_qty,product_id,reference_no from i_reservation_detail_t where product_id='" . $value->product_id . "' and company_id='" . $comp . "' and reference_no=$qval GROUP by reference_no order by reference_no asc");
                                if (count($opreserveqty1) > 0) {
                                    $rqty1 = $opreserveqty1[0]->reserv_trx_qty;
                                } else {
                                    $rqty1 = 0;
                                }
                                if ($tqty <= $rqty1) {
                                    $res_qty = $tqty;
                                    break;
                                } else if ($tqty > $rqty1) {
                                    $res_qty = $rqty1;
                                    $resbal_qty = $res_qty + $resbal_qty;
                                }
                                $QOHdata1[$qk]['product_id'] = $value->product_id;
                                $QOHdata1[$qk]['reserv_uom_code_id'] = $value->uom_code_id;
                                $QOHdata1[$qk]['reference_no'] = str_replace("'", "", $qval);
                                $QOHdata1[$qk]['reference_source'] = "MATERIAL PLAN";
                                $QOHdata1[$qk]['reserv_trx_qty'] = -$res_qty;
                                $QOHdata1[$qk]['create_trx_id'] = $id1;
                                $QOHdata1[$qk]['organization_id'] = \Session::get('organization');
                                $QOHdata1[$qk]['company_id'] = \Session::get('companyid');
                                $QOHdata1[$qk]['location_id'] = \Session::get('location');
                                \DB::table('i_reservation_detail_t')->insert($QOHdata1[$qk]);
                            }

                        }
                    }
                }
            }


            $emp = \Session::get('id');
            $prd_id = $_POST['product_id'];
            $user_mail = \DB::select("select user_mail,first_name from tb_users where employee_id='$emp'");
            if (!empty($user_mail) && !empty($user_mail[0]->user_mail)) {
                $from_umail = $user_mail[0]->user_mail;
            } else {

                $from_umail = "aspire@jrkresearch.com";
            }

            $pro_name1 = \DB::SELECT("select concatenated_product from m_products_t where product_id='$prd_id'");


            if ($_POST['job_no'] != '') {
                $jobcard_no = $_POST['job_no'];
            } else {
                $jobcard_no = $jobcard->job_no = $seqno;
            }
            $from_mail = $from_umail;
            $pro_name = $pro_name1[0]->concatenated_product;
            $datetime = $jobcard->created_at;
            if ($_POST['batch_no'] != '') {
                $batch_no = $_POST['batch_no'];
            } else {
                $batch_no = $jobcard->batch_no = $seqno1;
            }
            $qty = $_POST['job_qty'];
            $status = "OPEN";
            $sub = " JOBCARD CREATED -$jobcard_no -$pro_name";
            $to_mail = "purchase_rm@jrkresearch.com,purchase_pm@jrkresearch.com,uma_p@jrkresearch.com";
            $emp_name = $user_mail[0]->first_name;
            $msg = "<p>Dear Team,<br><br>Please issue the required material against the jobcard given below,<br><br>Jobcard No - $jobcard_no <br>Batch No - $batch_no <br>Product Name - $pro_name<br>Qty - $qty<br>Date And Time - $datetime<br>Status - OPEN<br><br>Regards, <br> $emp_name";

            if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                Config::set('mail.username', \Session::get('user_email'));
                Config::set('mail.password', \Session::get('user_password'));
            }

            \Mail::send([], [], function ($message) use ($from_mail, $to_mail, $sub, $msg) {

                $message->from($from_mail)
                    ->to(explode(',', $to_mail))
                    ->subject($sub)
                    ->setBody($msg, 'text/html');
            });
            // mail function end
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Saved Successfully', 'id' => $id, 'jobno' => $seqno));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }

    /* purpose: to show jobcard*/
    public function show(jobcard $jobcard, $id = null)
    {

        $data = DB::table('w_jobcard_hdr_t')->select('w_jobcard_hdr_t.*', 'm_products_t.product_code', 'm_products_t.concatenated_product', 'm_uom_codes_t.uom_code')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'w_jobcard_hdr_t.product_id')->leftjoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'w_jobcard_hdr_t.uom_code_id')->leftjoin('m_organizations_t', 'm_organizations_t.organization_id', '=', 'w_jobcard_hdr_t.organization_id')->where('w_jobs_hdr_id', $id)->get();
        $this->data['job_no'] = $data[0]->job_no;
        $this->data['assembly_product'] = $data[0]->product_code . "-" . $data[0]->concatenated_product;
        $this->data['uom_code_id'] = $data[0]->uom_code;
        $this->data['job_date'] = date(\Session::get('p_date_format'), strtotime($data[0]->job_date));
        $this->data['job_completion_date'] = date(\Session::get('p_date_format'), strtotime($data[0]->job_completion_date));
        $this->data['job_qty'] = $data[0]->job_qty;
        $this->data['batch_no'] = $data[0]->batch_no;
        $this->data['job_adjusted_qty'] = $data[0]->job_adjusted_qty;
        $this->data['reference_source'] = $data[0]->reference_source;
        $this->data['hour'] = $data[0]->hour;
        $this->data['bom_process'] = $data[0]->bom_process;
        $this->data['kitpack_no'] = $data[0]->kitpack_no;
        $this->data['job_status'] = $data[0]->job_status;
        $this->data['job_created_by'] = $this->idname("employee_number|first_name", "hr_employee_t", "employee_id", $data[0]->job_created_by);
        $this->data['bom_product_id'] = $this->idname("product_code|concatenated_product", "m_products_t", "product_id", $data[0]->bom_product_id);
        $assigned = explode(",", $data[0]->job_assigned_to);
        $assingnedto = "";
        foreach ($assigned as $key1 => $value) {
            $assingnedto .= $this->idname("employee_number|first_name", "hr_employee_t", "employee_id", $value) . ",";
        }
        $this->data['assigned_name'] = rtrim($assingnedto, ",");
        $this->data['machine'] = $this->idname("machine_name", "w_machine_hdr_t", "machine_hdr_id", $data[0]->machine_hdr_id);
        $this->data['machine_capacity'] = $data[0]->machine_capacity;

        $this->data['remarks'] = $data[0]->remarks;
        if (isset($_GET)) {
            $this->data['pageurl'] = $_GET['pageurl'];
        }
        return view('jobcard.view', $this->data);
    }
    /*end*/
    /* purpose: to get hour details based on machine*/
    public function machinehourdetails($machineid = null, $product_id = null, $production_qty = null)
    {
        $sql = \DB::table('w_machine_equipments_hdr_t')
            ->leftjoin('w_machine_equipments_lines_t', 'w_machine_equipments_lines_t.machine_equipments_hdr_id', '=', 'w_machine_equipments_hdr_t.machine_equipments_hdr_id')
            ->where('w_machine_equipments_hdr_t.machine_id', $machineid)
            ->select('w_machine_equipments_hdr_t.*', 'w_machine_equipments_lines_t.*')
            ->get();
        if ($sql->isNotEmpty()) {
            foreach ($sql as $key => $value) {
                if ($value->range_from <= $production_qty && $value->range_to >= $production_qty) {
                    $hour = $value->hours;
                    //dd($hour);
                    return $hour;
                }
            }
        } else {
            $hour = 0;
            return $hour;
        }
    }
    /*end*/
    /* purpose:get machine name based on product type*/
    public function getprdtype($id = null)
    {
        $sql = \DB::table('m_products_t')->where('m_products_t.product_id', $id)->get();

        if ($sql->isNotEmpty()) {
            $sql1 = \DB::table('w_machine_hdr_t as m')->leftjoin('w_machine_lines_t as ml', 'm.machine_hdr_id', '=', 'ml.machine_hdr_id')
                ->select('m.machine_hdr_id', 'ml.product_type_id', 'm.capacity', 'm.assigned_to')->where('ml.product_type_id', $sql[0]->product_type_id)->get();
        }
        if (!empty($sql1)) {
            $machid = "";
            foreach ($sql1 as $key => $value) {
                $machid .= $value->machine_hdr_id . ",";
            }
            $machid = rtrim($machid, ',');
            return $machid;
        } else {
            return 0;
        }
    }
    /*end*/
    /* purpose:get  machine details*/
    public function machinedetails($id = null)
    {
        if (isset($_GET['prdid'])) {
            $sql = \DB::table('m_products_t')->where('m_products_t.product_id', $_GET['prdid'])->get();
        }
        $sql1 = \DB::select("select w_machine_hdr_t.*,w_machine_lines_t.* from w_machine_hdr_t left join w_machine_lines_t on(w_machine_lines_t.machine_hdr_id=w_machine_hdr_t.machine_hdr_id)  where w_machine_hdr_t.machine_hdr_id=" . $id . " and product_type_id=" . $sql[0]->product_type_id);
        if (!empty($sql1)) {
            return $sql1;
        } else {
            return 0;
        }
    }
    /*end*/
    /* purpose: to get primary key for table*/
    function findPrimarykey($table)
    {
        $primaryKey = '';
        foreach (\DB::select("show columns from " . $table . " where extra like '%auto_increment%'") as $key) {
            $primaryKey = $key->Field;
        }
        return $primaryKey;
    }
    /*saravanan purpose: to display jobreport*/
    public function report()
    {
        $this->data['vdata'] = [];
        $this->data['jobcard_no'] = $this->jCombocomp('w_jobcard_hdr_t', 'w_jobs_hdr_id', 'job_no', '');
        return view('jobcardreport.reportledger', $this->data);
    }
    /*end*/
    /* purpose:get  machine pm  details*/
    public function machinepmdetails($id = null)
    {
        $jobdate = date('Y-m-d', strtotime($_GET['jobcldate']));
        $pm = \DB::select("select machine_pm_detail_t.*,pm_monthly_checking_tbl.approval_status from machine_pm_detail_t left join pm_monthly_checking_tbl on(machine_pm_detail_t.initiate_pm_id=pm_monthly_checking_tbl.initate_pm_id) where machine_pm_detail_t.machine_id=" . $id . " and machine_pm_detail_t.initiate_status=1 and machine_pm_detail_t.initiate_date <= '$jobdate' and (machine_pm_detail_t.status!=0) and pm_monthly_checking_tbl.approval_status!='APPROVED'");
        $breakdown = \DB::select("select * from b_maintenance_t bm where bm.machine_id = $id and date(issue_date)<='$jobdate' and request_status!='CLOSED'");

        if (count($pm) > 0) {
            return 1;
        } else {
            if (count($breakdown) > 0) {
                return 1;
            } else {
                return 0;
            }
        }
    }
    /*end*/
    public function moveqty(Request $request, $id)
    {

        $job = \DB::select('select sum(qoh_trx_qty) as moveqty from i_qoh_detail_t where job_id=' . $id);
        $mqty = $job[0]->moveqty;
        if ($mqty != null) {
            return $mqty;
        } else {
            return 0;
        }

    }
    public function subinventory(Request $request, $id)
    {

        $sub = \DB::select('select subinventory_id,locator_id from m_products_t where product_id=' . $id);
        return $sub;

    }

    /* purpose:move to store based on machine location*/
    public function movetostore($jobid = null)
    {

        $trsns = \DB::table('m_transaction_types_t')->where('transaction_type_name', 'STORE MOVE')->get();
        $trsns = json_decode(json_encode($trsns), true);
        $job = \DB::select('select * from w_jobcard_hdr_t where w_jobs_hdr_id=' . $jobid);
        $pid = $job[0]->product_id;
        $prcs = $_POST['process_level'];
        $mvqty = $job[0]->store_move_qty;
        $group = \DB::select('select * from m_products_t where product_id=' . $pid);
        $mac = $_POST['machine_id'];
        $mactime = $_POST['machine_time'];


        $jobass = $_POST['job_assigned_to'];
        $st = $_POST['start_time'];
        $et = $_POST['end_time'];
        $ahrs = $_POST['actual_hrs'];
        $whrs = $_POST['working_hrs'];
        $empqty = $_POST['emp_qty'];


        // purpose for jobcard activity table entry - VIGNESH M

        $type = $_POST['type'];
        $jobAssignedTo = $_POST['job_assigned_to'];
        $activities = $_POST['activity_name'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $duration = $_POST['working_hrs'];

        // Common data for hdr records
        $baseHdrData = [
            'created_by' => \Session::get('id'),
            'created_at' => date('Y-m-d'),
            'organization_id' => \Session::get('organization'),
            'location_id' => \Session::get('location'),
            'company_id' => \Session::get('companyid'),
            'last_updated_by' => \Session::get('id'),
            'updated_at' => date('Y-m-d'),
        ];

        foreach ($jobAssignedTo as $key => $employeeId) {

            if (isset($activities[$key]) && $activities[$key] != "") {
                // Save to hdr table
                $hdrData = $baseHdrData;
                $hdrData['employee_id'] = $employeeId;
                $hdrData['job_id'] = $jobid;
                $hdrId = \DB::table('w_jobactivity_hdr_t')->insertGetId($hdrData);

                // Common data for lines table
                $baseLineData = [
                    'created_by' => \Session::get('id'),
                    'created_at' => date('Y-m-d'),
                    'organization_id' => \Session::get('organization'),
                    'location_id' => \Session::get('location'),
                    'company_id' => \Session::get('companyid'),
                    'last_updated_by' => \Session::get('id'),
                    'updated_at' => date('Y-m-d'),
                ];

                // Check if corresponding data exists for the same index
                if (isset($activities[$key], $start_time[$key], $end_time[$key], $type[$key], $duration[$key])) {
                    $lineData = $baseLineData; // Copy base data
                    $lineData['activity_name'] = $activities[$key];
                    $lineData['start_datetime'] = $start_time[$key];
                    $lineData['end_datetime'] = $end_time[$key];
                    $lineData['duration'] = $duration[$key];
                    $lineData['job_activity_hdr_id'] = $hdrId;
                    $lineData['type'] = $type[$key];
                    $lineData['line_no'] = $key + 1;

                    \DB::table('w_jobactivity_lines_t')->insert($lineData);
                }
            }
        }

        // purpose for jobcard activity table entry END

        // purpose for machine log entry - vignesh m

        $machineData['process_dept'] = "OPERATION";
        $machineData['machine_id'] = $_POST['machine_id'];
        $machineData['date'] = $_POST['process_start_date'];
        $machineData['product_id'] = $_POST['productid'];
        $machineData['batch_number'] = $_POST['batch_no'];
        $machineData['quantity'] = $_POST['qty'];
        $machineData['running_hours'] = $_POST['machine_time'];
        $machineData['created_by'] = \Session::get('id');
        $machineData['created_at'] = date('Y-m-d');
        $machineData['last_updated_by'] = \Session::get('id');
        $machineData['updated_at'] = date('Y-m-d');
        $machineData['company_id'] = \Session::get('companyid');
        $machineData['organization_id'] = \Session::get('organization');
        $machineData['location_id'] = \Session::get('location');

        \DB::table('b_machine_log_t')->insert($machineData);

        // END

        // Filter for MAJOR type employees
        $filteredJobass = [];
        $filteredSt = [];
        $filteredEt = [];
        $filteredAhrs = [];
        $filteredWhrs = [];
        $filteredEmpQty = [];

        foreach ($jobass as $key => $value) {
            if ($_POST['type'][$key] == 'MAJOR') {
                $filteredJobass[] = $value;
                $filteredSt[] = $st[$key];
                $filteredEt[] = $et[$key];
                $filteredAhrs[] = $ahrs[$key];
                $filteredWhrs[] = $whrs[$key];
                $filteredEmpQty[] = $empqty[$key];
            }
        }

        // Proceed only if there are MAJOR records
        if (!empty($filteredJobass)) {
            $pdata['job_id'] = $jobid;
            $pdata['machine_id'] = $mac;
            if ($_POST['process_level'] == "PROCESS-1") {
                $pdata['calibration_checked_by'] = $_POST['calibration_checked_by'];
            } else {
                $pdata['calibration_checked_by'] = '';
            }
            $pdata['machine_time'] = $mactime;
            $pdata['jobassigned_to'] = implode(',', $filteredJobass);
            $pdata['actual_hrs'] = implode(',', $filteredAhrs);
            $pdata['start_time'] = implode(',', $filteredSt);
            $pdata['end_time'] = implode(',', $filteredEt);
            $pdata['working_hrs'] = implode(',', $filteredWhrs);
            $pdata['emp_qty'] = implode(',', $filteredEmpQty);
            $pdata['process_level'] = $_POST['process_level'];
            $pdata['process_name'] = $_POST['process_name'];
            $pdata['move_qty'] = $_POST['qty'];
            $pdata['process_date'] = date("Y-m-d", strtotime($_POST['trx_date']));
            $pdata['created_by'] = \Session::get('id');
            $pdata['created_at'] = date('Y-m-d');
            $pdata['organization_id'] = \Session::get('organization');
            $pdata['location_id'] = \Session::get('location');
            $pdata['company_id'] = \Session::get('companyid');
            $pdata['subinventory_id'] = $_POST['subinventory_id'];
            $pdata['locator_id'] = $_POST['sublocator_id'];
            $pdata['process_start_date'] = date('Y-m-d H:i:s', strtotime($_POST['process_start_date']));
            $pdata['process_end_date'] = date('Y-m-d H:i:s', strtotime($_POST['process_end_date']));

            \DB::table('w_jobcard_process_details_t')->insert($pdata);

            // Additional checks for FINALPROCESS
            if ($_POST['process_level'] == "FINALPROCESS") {
                $jobprcs1 = \DB::table('w_jobcard_process_details_t')
                    ->where('job_id', $jobid)
                    ->where('process_level', 'PROCESS-1')
                    ->select(DB::raw('SUM(move_qty) as move_qty'))
                    ->get();
                $jobfinalprcs = \DB::table('w_jobcard_process_details_t')
                    ->where('job_id', $jobid)
                    ->where('process_level', 'FINALPROCESS')
                    ->select(DB::raw('SUM(move_qty) as move_qty'))
                    ->get();

                if ($jobprcs1[0]->move_qty == $jobfinalprcs[0]->move_qty) {
                    \DB::table('w_jobcard_hdr_t')
                        ->where('w_jobs_hdr_id', $jobid)
                        ->update(['store_move_status' => 1]);
                }
            }
        }

        $plan = \DB::select('select * from w_productionplan_hdr_t where productionplan_hdr_id=' . $job[0]->reference_source_id . ' and product_id=' . $pid);
        if (count($plan) > 0) {
            $planqty = $plan[0]->plan_qty + $_POST['qty'];
            if ($plan[0]->pending_qty == 0) {
                //$pendqty=$_POST['qty'];
            } else {
                $pendqty = $plan[0]->pending_qty - $_POST['qty'];
            }

            /*$planqty=$plan[0]->plan_qty+$mvqty;
                     if($plan[0]->pending_qty==0){
            $pendqty=$mvqty;
                     }else{
                $pendqty=$plan[0]->production_qty-$mvqty;      
                     }*/
            if ($prcs == "FINALPROCESS") {
                \DB::table('w_productionplan_hdr_t')->where('productionplan_hdr_id', $job[0]->reference_source_id)->where('product_id', $pid)->update(['plan_qty' => $planqty, 'pending_qty' => $pendqty]);
            }
        }
        if ($prcs != '') {
            $planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $job[0]->reference_source_id)->where('parent_product', $pid)->where('process_level', $prcs)->get();

        }

        if (count($planlines) > 0) {

            /* $qty=$plan[0]->production_qty;
               $prdqty=$planlines[0]->production_qty+$mvqty;
           if($planlines[0]->pending_qty==0){
               $pendingqty=$planlines[0]->pending_qty-$mvqty;
           }else{
               $pendingqty=$qty-$prdqty;
           }*/
            $qty = $plan[0]->production_qty;
            $prdqty = $planlines[0]->production_qty + $_POST['qty'];
            if ($planlines[0]->pending_qty == 0) {
                $pendingqty = 0;
                // $pendingqty=$planlines[0]->pending_qty-$_POST['qty'];
            } else {
                $pendingqty = $planlines[0]->pending_qty - $_POST['qty'];
            }
            \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $job[0]->reference_source_id)->where('parent_product', $pid)->where('process_level', $prcs)->update(['production_qty' => $prdqty, 'pending_qty' => $pendingqty]);
        }

        /* insert data into mtl transaction tbl */
        $data1['trx_source_type_id'] = $trsns[0]['transaction_source_id'];
        $data1['trx_action_id'] = $trsns[0]['transaction_action_id'];
        $data1['trx_type_id'] = $trsns[0]['transaction_type_id'];

        $data1['trx_source_hdr_id'] = $jobid;
        $data1['trx_source_line_id'] = "";

        $data1['line_number'] = 1;
        $data1['product_id'] = $job[0]->product_id;
        $data1['trx_uom'] = $group[0]->primary_uom_id;
        $data1['trx_date'] = date('Y-m-d');
        $data1['created_by'] = \Session::get('id');
        $data1['created_at'] = date('Y-m-d');
        $data1['organization_id'] = \Session::get('organization');
        $data1['location_id'] = \Session::get('location');
        $data1['company_id'] = \Session::get('companyid');
        $data1['subinventory_id'] = $_POST['subinventory_id'];
        $data1['locator_id'] = $_POST['sublocator_id'];
        $data1['trx_qty'] = $_POST['qty'];
        $mtlid = \DB::table('m_material_trx_t')->insertGetId($data1);
        /* insert data into qoh detail tbl */

        $dataqoh['job_id'] = $jobid;
        $dataqoh['product_id'] = $job[0]->product_id;
        $dataqoh['qoh_uom_code_id'] = $group[0]->primary_uom_id;
        $dataqoh['create_trx_id'] = $mtlid;
        $dataqoh['qoh_source'] = "JOB STORE MOVE";
        $dataqoh['qoh_trx_date'] = date('Y-m-d');
        $dataqoh['qoh_source_id'] = $jobid;
        $dataqoh['job_process'] = $_POST['process_level'];
        $dataqoh['job_process_name'] = $_POST['process_name'];
        $dataqoh['created_by'] = \Session::get('id');
        $dataqoh['created_at'] = date('Y-m-d');

        $dataqoh['organization_id'] = \Session::get('organization');
        $dataqoh['location_id'] = \Session::get('location');
        $dataqoh['company_id'] = \Session::get('companyid');

        if ($prcs == "FINALPROCESS") {
            $dataqoh['qualitystatus'] = 0;
        } else {
            $dataqoh['qualitystatus'] = 1;
        }
        $dataqoh['create_trx_id'] = $mtlid;
        $dataqoh['qoh_trx_qty'] = $_POST['qty'];
        ;
        $dataqoh['subinventory_id'] = $_POST['subinventory_id'];
        ;
        $dataqoh['job_move_date'] = date('Y-m-d', strtotime($_POST['trx_date']));
        $dataqoh['locator_id'] = $_POST['sublocator_id'];
        $dataqoh['batch_number'] = $job[0]->batch_no;
        $qohid = \DB::table('i_qoh_detail_t')->insertGetId($dataqoh);
        $trsns = \DB::table('m_transaction_types_t')->where('transaction_type_name', 'JOB ISSUE')->get();
        $trsns = json_decode(json_encode($trsns), true);

        if ($_POST['prev_process_level'] != "0") {
            /* insert data into mtl transaction tbl */
            $data2['trx_source_type_id'] = $trsns[0]['transaction_source_id'];
            $data2['trx_action_id'] = $trsns[0]['transaction_action_id'];
            $data2['trx_type_id'] = $trsns[0]['transaction_type_id'];

            $data2['trx_source_hdr_id'] = $jobid;
            $data2['trx_source_line_id'] = "";

            $data2['line_number'] = 1;
            $data2['product_id'] = $job[0]->product_id;
            $data2['trx_uom'] = $group[0]->primary_uom_id;
            $data2['trx_date'] = date('Y-m-d');
            $data2['created_by'] = \Session::get('id');
            $data2['created_at'] = date('Y-m-d');
            $data2['organization_id'] = \Session::get('organization');
            $data2['location_id'] = \Session::get('location');
            $data2['company_id'] = \Session::get('companyid');
            $data2['subinventory_id'] = $_POST['subinventory_id'];
            $data2['locator_id'] = $_POST['sublocator_id'];
            $data2['trx_qty'] = -$_POST['qty'];
            $mtlid = \DB::table('m_material_trx_t')->insertGetId($data2);

            $dataqoh1['job_id'] = $jobid;
            $dataqoh1['product_id'] = $job[0]->product_id;
            $dataqoh1['qoh_uom_code_id'] = $group[0]->primary_uom_id;
            $dataqoh1['create_trx_id'] = $mtlid;
            $dataqoh1['qoh_source'] = "JOB ISSUE";
            $dataqoh1['qoh_trx_date'] = date('Y-m-d');
            $dataqoh1['qoh_source_id'] = $jobid;
            $dataqoh1['job_process'] = $_POST['prev_process_level'];
            $dataqoh1['job_process_name'] = $_POST['prev_job_process_name'];
            $dataqoh1['created_by'] = \Session::get('id');
            $dataqoh1['created_at'] = date('Y-m-d');

            $dataqoh1['organization_id'] = \Session::get('organization');
            $dataqoh1['location_id'] = \Session::get('location');
            $dataqoh1['company_id'] = \Session::get('companyid');
            $dataqoh1['qualitystatus'] = 1;
            $dataqoh1['create_trx_id'] = $mtlid;
            $dataqoh1['qoh_trx_qty'] = -$_POST['qty'];
            $dataqoh1['subinventory_id'] = $_POST['subinventory_id'];
            $dataqoh1['job_move_date'] = date('Y-m-d', strtotime($_POST['trx_date']));
            $dataqoh1['locator_id'] = $_POST['sublocator_id'];
            $dataqoh1['batch_number'] = $job[0]->batch_no;
            $qohid = \DB::table('i_qoh_detail_t')->insertGetId($dataqoh1);
        }

        return 1;
    }
    /*end*/
    /* purpose:process based on product bom*/
    public function prsdetails($pid = null)
    {
	
        if (isset($_GET['level'])) {

            $data = array();
            $data['movedqty'] = 0;
            $bomprocess = \DB::table('m_material_bom_hdr_t')->leftjoin('m_material_bom_lines_t', 'm_material_bom_lines_t.material_bom_hdr_id', '=', 'm_material_bom_hdr_t.material_bom_hdr_id')->select('m_material_bom_lines_t.process_level', 'm_material_bom_lines_t.process_name')->where('m_material_bom_hdr_t.active', 'Yes')->where('m_material_bom_hdr_t.assembly_product_id', $pid)->where('m_material_bom_lines_t.process_level', $_GET['level'])->get();
            if (isset($_GET['jobid'])) {
                $qoh = \DB::table('i_qoh_detail_t')->where('job_process', $_GET['level'])->where('qoh_source', 'JOB STORE MOVE')->where('job_id', $_GET['jobid'])->where('product_id', $pid)->select(DB::raw('sum(qoh_trx_qty) as qty'))->get();
                if (count($qoh) > 0) {
                    if ($qoh[0]->qty != null) {
                        $data['movedqty'] = $qoh[0]->qty;

                    }
                }
            }
            $data['pname'] = $bomprocess[0]->process_name;
            $data['p_prcsname'] = "";
            $data['pmovedqty'] = 0;
            $data['pstatus'] = 0;
            if (isset($_GET['plevel'])) {
                if ($_GET['plevel'] != '0') {

                    $pqoh = \DB::table('i_qoh_detail_t')->where('job_process', $_GET['plevel'])->where('qoh_source', 'JOB STORE MOVE')->where('job_id', $_GET['jobid'])->where('product_id', $pid)->select(DB::raw('sum(qoh_trx_qty) as qty'), 'job_process_name')->get();
                    if (count($pqoh) > 0) {
                        if ($pqoh[0]->qty != null) {
                            $data['pmovedqty'] = $pqoh[0]->qty;
                            $data['p_prcsname'] = $pqoh[0]->job_process_name;
                            $data['pstatus'] = 1;
                        }
                    }
                }
            }
            if ($data['pmovedqty'] == $data['movedqty']) {
                $data['pstatus'] = 0;
            }
            return $data;
        } else {
            $bomprocess = \DB::table('m_material_bom_hdr_t')->leftjoin('m_material_bom_lines_t', 'm_material_bom_lines_t.material_bom_hdr_id', '=', 'm_material_bom_hdr_t.material_bom_hdr_id')->select('m_material_bom_lines_t.process_level', 'm_material_bom_lines_t.process_name')->where('m_material_bom_hdr_t.active', 'Yes')->where('m_material_bom_hdr_t.assembly_product_id', $pid)->groupBy('m_material_bom_lines_t.process_level')->orderby('m_material_bom_lines_t.material_bom_line_id', 'asc')->get();
            $prc = array();
            if (count($bomprocess) > 0) {
                $prcslvl = "";
                $prcsname = "";
                foreach ($bomprocess as $k => $v) {
                    $prcslvl .= $v->process_level . ',';
                    $prcsname .= $v->process_name . ',';
                }

                $prc['prcslvl'] = trim($prcslvl, ",");
                $prc['prcsname'] = trim($prcsname, ",");
                return $prc;
            } else {
                return 0;
            }
        }
	
    }

    /* purpose employee working hrs*/
    public function employeeworkdetails($id = null)
    {

        $sql = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $id)->get();
        $jobassid = explode(",", $sql[0]->job_assigned_to);
        $jobhr = $sql[0]->hour;


        $html = "<table class='table table-bordered clone_table table-responsive' style='width: 104%;'>";
        $html .= "<thead class='table-light topfreeze'><th >S.No</th><th class='freeze'>Employee Name</th><th style='width: 5%;'>Type</th><th>Activity Name</th><th >Actual Hours</th><th style='width: 15%;'>Start Time</th><th style='width: 15%;'>End Time</th><th >Working Hours</th>";

        $html .= "<th style='width:10%'>Qty</th><th></th></thead><tbody  class='clone_lines_body'>";

        foreach ($jobassid as $key => $val) {
            //$sel = $this->jcustomselect('hr_employee_t','employee_id','first_name',$val,'  and employee_type!=231 and employee_type in (151,152,249)');	
            $sel = $this->jcustomselect('hr_employee_t', 'employee_id', 'first_name', $val, '  and group_type=10');
            $plevel = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', '', 'and lookup_type="PROCESS_LEVEL"');
            $pname = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', '', 'and lookup_type="PROCESS_NAME"');
            $activity_name = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', '', 'and lookup_type="OPERATION_MANUAL_ACTIVITIES"');
            $res = \DB::table('hr_employee_t')->select('first_name')->where('employee_id', $val)->get();
            $html .= "<tr class='emp_clone rcopy1 clone1'>";


            $html .= "<td ><input type='text' name='line_no' class='form-control line_no' value=" . ($key + 1) . "></td>";
            $html .= "<td class='freeze'><div class='form-group'><select name='job_assigned_to[]'  class='form-control  select2 job_assigned_to'>" . $sel . "</select></div></td>";
            $html .= "<td ><div class='form-group'><select name='type[]'  class='form-control  select2 type' required>
                    <option  value=''>--please select--</option>
                    <option  value='MAJOR'>MAJOR</option>
                    <option  value='SUB'>SUB</option>
                </select></div></td>";
            $html .= "<td ><div class='form-group'><select name='activity_name[]'  class='form-control  select2 activity_name' >" . $activity_name . "</select></div></td>";
            $html .= "<td><input type='text' name='actual_hrs[]' class='actual_hrs form-control actual_hrs" . $key . "' value='" . $jobhr . "'></td>";
            $html .= "<td><input type='tect' name='start_time[]' class=' start_time form-control start_time" . $key . "' value='' style='border:1px solid #07234e;' placeholder='ensure date and time' onkeydown='return false;'  onpaste='return false;'  ondrop='return false;'></td>";
            $html .= "<td ><input type='text' name='end_time[]' class='end_time  form-control end_time" . $key . "' value='' style='border:1px solid #07234e;' placeholder='ensure date and time' onkeydown='return false;'  onpaste='return false;'  ondrop='return false;'></td>";
            $html .= "<td ><input type='text' name='working_hrs[]' class='working_hrs form-control working_hrs" . $key . "' value='' required readonly></td>";
            $html .= "<td ><input type='text' name='emp_qty[]' class='emp_qty form-control emp_qty" . $key . "' value='' required></td>";
            $html .= "        <td class='text-center'>
          <button type='button' class='btn btn-sm btn-danger remove-row'>
            <i class='fas fa-minus-circle'></i>
          </button>
        </td>";
            $html .= "</tr>";
        }
        $html .= "</tbody></table>";
        return $html;
    }

    /*end*/
    /*saravanan purpose: to display jobreport*/
    public function reportnew()
    {
        $this->data['vdata'] = [];
        $this->data['jobcard_no'] = $this->jCombocomp('w_jobcard_hdr_t', 'w_jobs_hdr_id', 'job_no', '');
        return view('jobcardreport.reportledgernew', $this->data);
    }
    /*end*/
    public function jobcardresultnew($id = null)
    {
        error_reporting(0);
        $job_card_id = $id;

        $job_card_query = \DB::select("SELECT
    m_products_t.concatenated_product,
    job_card_report_hdr.date,
    job_card_report_hdr.actual_cost,
    job_card_report_hdr.palnning_cost
FROM
    job_card_report_hdr
LEFT JOIN m_products_t on m_products_t.product_id = job_card_report_hdr.product_id
where job_card_report_hdr.job_card_id='$id'");
        $job_card_query1 = \DB::select("SELECT
   *
FROM
    job_card_report_lines

where job_card_id='$id'");
        if (count($job_card_query1) > 0) {
            $p_emp_list = array();
            $p_prd_list = array();
            $a_emp_list = array();
            $a_prd_list = array();

            foreach ($job_card_query1 as $key => $value) {


                $type = $value->type;
                if ($value->costtype == 1) {
                    if ($type == 1) {
                        $employee = $this->employee($value->typeid);
                        $p_emp_list[] = array($employee, $value->rate, $value->hour, 'planning');
                    } else {
                        $product = $this->product($value->typeid);
                        $p_prd_list[] = array($product, $value->rate, $value->hour, 'planning');
                    }
                } else {
                    if ($type == 1) {
                        $employee = $this->employee($value->typeid);
                        $a_emp_list[] = array($employee, $value->rate, $value->hour, 'actual');
                    } else {
                        $product = $this->product($value->typeid);
                        $a_prd_list[] = array($product, $value->rate, $value->hour, 'actual');
                    }
                }
            }

        }



        $html = '';
        $html = '<div class="invoice-box" id="section-to-print">
      
        <table cellpadding="0" cellspacing="0">
            <tbody>


            <h2 class="heads1">Report</h2>
            
            <tr class="information">
                <td colspan="6">

					<table border="1" >
								<tbody>
								<tr>
									<td>
										<p><b>Product Name:</b>' . $job_card_query[0]->concatenated_product . '</p> <br>
										<p><b>Date:</b>' . $job_card_query[0]->date . '</p><br>

									</td>
								</tr>
								</tbody>
					</table>

                    <table border="1" >
                        <tbody>
                        <tr>
                            <td >
                                <p><b>PLANNING COST:</b>' . $job_card_query[0]->palnning_cost . '</p> <br>
                                
                            </td>
                            <td class="text-right">
                                <p><b>ACTUAL COST</b>' . $job_card_query[0]->actual_cost . '</p><br>
                                                     
                            </td>
                        </tr>
                    </tbody>
                </table>
                </td>
            </tr>  
           
                       

            
     
        </tbody></table>
		<br>
		<div class="row">
		<div class="col-md-6">
		<table class="table table-bordered table-hover">
				<thead>
					<th width="40%" >Employee</th>
					<th width="30%">Cost</th>
					
				</thead>
				<tbody>';
        foreach ($p_emp_list as $key => $value) {
            $html .= '<tr>
						<td>' . $value[0] . '</td>
						<td>' . $value[1] . '</td>
						
						
					</tr>';
        }
        $html .= '</tbody>
				
		</table>
		<table class="table table-bordered table-hover">
				<thead>
					<th width="40%" >Product</th>
					<th width="30%">Cost</th>
					
				</thead>
				<tbody>';
        foreach ($p_prd_list as $key => $value) {
            $html .= '<tr>
						<td>' . $value[0] . '</td>
						<td>' . $value[1] . '</td>
						
						
					</tr>';
        }
        $html .= '</tbody>
				
		</table>
		</div>
		
		<div class="col-md-6">
		<table class="table table-bordered table-hover">
				<thead>
					<th width="40%" >Employee</th>
					<th width="30%">Cost</th>
					
				</thead>
				<tbody>';
        foreach ($a_emp_list as $key => $value) {
            $html .= '<tr>
						<td>' . $value[0] . '</td>
						<td>' . $value[1] . '</td>
					
						
					</tr>';
        }
        $html .= '</tbody>
				
		</table>
		<table class="table table-bordered table-hover">
				<thead>
					<th width="40%" >Product</th>
					<th width="30%">Cost</th>
					
				</thead>
				<tbody>';
        foreach ($a_prd_list as $key => $value) {
            $html .= '<tr>
						<td>' . $value[0] . '</td>
						<td>' . $value[1] . '</td>
					
						
					</tr>';
        }
        $html .= '</tbody>
				
		</table>
		</div>
		</div>
		
		
    </div>';

        return $html;
    }


    public function jobcardcloedit()
    {

        $sql = \DB::select("SELECT w_jobs_hdr_id,job_no,job_date,remarks,created_by FROM w_jobcard_hdr_t where w_jobs_hdr_id='" . $_GET['id'] . "'");
        if (isset($sql)) {
            if ($sql[0]->job_no != '' || $sql[0]->job_date != '') {
                $data['job_no'] = $sql[0]->job_no;
                $data['job_date'] = $sql[0]->job_date;
                $data['remarks'] = $sql[0]->remarks;
                $data['created_by'] = $sql[0]->created_by;
                $data['update'] = 'update';
            } else if ($sql[0]->job_no == '' && $sql[0]->job_date == '') {
                $data['job_no'] = $sql[0]->job_no;
                $data['job_date'] = $sql[0]->job_date;
                $data['remarks'] = $sql[0]->remarks;
                $data['created_by'] = $sql[0]->created_by;
                $data['update'] = 'create';
            }

        }
        return $data;

    }

    public function jobcloseupdate()
    {
     
        $job_status = "CLOSED";
        $prd_id = $_GET['intprd_id'];
        $check = \DB::update("update w_jobcard_hdr_t set remarks='" . $_GET['remarks'] . "',created_by='" . $_GET['closed_by'] . "',job_status='" . $job_status . "' where w_jobs_hdr_id='" . $_GET['id'] . "'");

        $jobno = \DB::table('w_jobcard_hdr_t')->select('job_no', 'batch_no', 'job_date', 'job_created_by', 'job_adjusted_qty', 'remarks')->where('w_jobs_hdr_id', '=', $_GET['id'])->get();

        $user_clear = \DB::select("SELECT * FROM hr_employee_t where employee_id = '" . $_GET['closed_by'] . "'");

        $prdname = \DB::select("SELECT * FROM m_products_t where concatenated_product = '" . $_GET['intprd_id'] . "'");
    

        $prd['job_no'] = $jobno[0]->job_no;
        $prd['created_date'] = $jobno[0]->job_date;
        $prd['product_name'] = $_GET['intprd_id'];
        $prd['prd_grp'] = $prdname[0]->product_group_id;
        $prd['prd_sub_cat'] = $prdname[0]->product_subcategory_id;
        $prd['job_status'] = $job_status;
        $prd['remarks'] = $jobno[0]->remarks;
        $prd['user_clear'] = $user_clear[0]->first_name;

        $job_num_sub = $jobno[0]->job_no;
        Session::put('job_num_sub', $job_num_sub);
        $job_email = $user_clear[0]->email;
        Session::put('job_email', $job_email);
        $prd_name = $prdname[0]->concatenated_product;
        Session::put('prd_name', $prd_name);
        if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
            Config::set('mail.username', \Session::get('user_email'));
            Config::set('mail.password', \Session::get('user_password'));
        }

        if ($prd['prd_grp'] == 1) {

            if (\Session::get('user_email') != '') {

                $to_mail_id = "murali_e@jrkresearch.com";

                \Mail::send('jobcard.mail', $prd, function ($message) use ($to_mail_id) {
  
                    $message->to($to_mail_id);
                    $message->to('logistics@jrkresearch.com');
                    $message->to('operations@jrkresearch.com');
                    $message->cc('uma_p@jrkresearch.com');
                    $message->cc('purchase_pm@jrkresearch.com');
                    $message->cc('gayathri_rajagopal@jrkresearch.com');
                    $message->cc('aspire@jrkresearch.com');

                    if (!empty(Session::get('job_email'))) {
                        $job_email = Session::get('job_email');
                    } else {
                        $job_email = \Session::get('user_email');
                    }
                    $message->from($job_email);
                    if (!empty(Session::get('job_num_sub'))) {
                        $job_num_sub = Session::get('job_num_sub');
                    } else {
                        $job_num_sub = " ";
                    }
                    if (!empty(Session::get('prd_name'))) {
                        $prd_name = Session::get('prd_name');
                    } else {
                        $prd_name = " ";
                    }

                    $message->subject($job_num_sub . " - Operation Jobcard Pre-Closure - " . $prd_name);
                });
            }

        } else {

            if (\Session::get('user_email') != '') {

                $to_mail_id = "operations@jrkresearch.com";

                \Mail::send('jobcard.mail', $prd, function ($message) use ($to_mail_id) {
                    $message->to($to_mail_id);
                    $message->to('cheran_senguttuvan@jrkresearch.com');
                    $message->to('drabbas@jrkresearch.com');
                    $message->to('aruna_v@jrkresearch.com');
                    $message->to('hemashree_k@jrkresearch.com');
                    $message->to('labs@jrkresearch.com');
                    $message->to('uma_p@jrkresearch.com');
                    $message->to('purchase_rm@jrkresearch.com');
                    $message->cc('gayathri_rajagopal@jrkresearch.com');
                    $message->cc('aspire@jrkresearch.com');

                    if (!empty(Session::get('job_email'))) {
                        $job_email = Session::get('job_email');
                    } else {
                        $job_email = \Session::get('user_email');
                    }
                    $message->from($job_email);
                    if (!empty(Session::get('job_num_sub'))) {
                        $job_num_sub = Session::get('job_num_sub');
                    } else {
                        $job_num_sub = " ";
                    }

                    if (!empty(Session::get('prd_name'))) {
                        $prd_name = Session::get('prd_name');
                    } else {
                        $prd_name = " ";
                    }

                    $message->subject($job_num_sub . " - Production Jobcard Pre-Closure - " . $prd_name);
                });
            }

        }
   
        if ($check) {
            return 1;
        } else {
            return 0;
        }
    }


    // segregation purpose - Vignesh m


    public function segregationsave($jobid = null)
    {

        $pdata['job_id'] = $jobid;
        $pdata['process_name'] = "SEGREGATION";
        $pdata['process_level'] = "PROCESS-SEG";
        $pdata['process_date'] = date("Y-m-d");
        $pdata['subinventory_id'] = $_POST['subinventory_id_seg'];
        $pdata['locator_id'] = $_POST['sublocator_id_seg'];
        $pdata['move_qty'] = ($_POST['qty_seg'] / $_POST['seg_kg']) * $_POST['output_seg'];
        $pdata['jobassigned_to'] = "52,42,567";

        $pdata['actual_hrs'] = implode(',', array_fill(0, 3, $_POST['total_seg']));
        $pdata['start_time'] = implode(',', array_fill(0, 3, $_POST['emp_start_date']));
        $pdata['end_time'] = implode(',', array_fill(0, 3, $_POST['emp_end_date']));
        $pdata['working_hrs'] = implode(',', array_fill(0, 3, $_POST['total_seg']));



        $pdata['emp_qty'] = ($_POST['qty_seg'] / $_POST['seg_kg']) * $_POST['output_seg'];
        $pdata['machine_id'] = $_POST['machine_name1'];
        $pdata['machine_time'] = $_POST['total_seg'];
        $pdata['calibration_checked_by'] = '';
        $pdata['created_by'] = \Session::get('id');
        $pdata['created_at'] = date('Y-m-d');
        $pdata['organization_id'] = \Session::get('organization');
        $pdata['location_id'] = \Session::get('location');
        $pdata['company_id'] = \Session::get('companyid');
        $pdata['process_start_date'] = date('Y-m-d H:i:s', strtotime($_POST['emp_start_date']));
        $pdata['process_end_date'] = date('Y-m-d H:i:s', strtotime($_POST['emp_end_date']));

        \DB::table('w_jobcard_process_details_t')->insert($pdata);

        \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $jobid)->update(['segregation_status' => '1']);


        return 1;
    }


    // traydryer purpose 


    public function traydryersave($jobid = null)
    {

        $pdata['job_id'] = $jobid;
        $pdata['process_name'] = "TRAY DRYER";
        $pdata['process_level'] = "PROCESS-TRAY";
        $pdata['process_date'] = date("Y-m-d");
        $pdata['subinventory_id'] = $_POST['subinventory_id_tray'];
        $pdata['locator_id'] = $_POST['sublocator_id_tray'];
        $pdata['move_qty'] = ($_POST['qty_tray'] / $_POST['tray_kg']) * $_POST['output_tray'];
        $pdata['jobassigned_to'] = "52,42,567";

        $pdata['actual_hrs'] = implode(',', array_fill(0, 3, $_POST['total_tray']));
        $pdata['start_time'] = implode(',', array_fill(0, 3, $_POST['emp_start_date_tray']));
        $pdata['end_time'] = implode(',', array_fill(0, 3, $_POST['emp_end_date_tray']));
        $pdata['working_hrs'] = implode(',', array_fill(0, 3, $_POST['total_tray']));



        $pdata['emp_qty'] = ($_POST['qty_tray'] / $_POST['tray_kg']) * $_POST['output_tray'];
        $pdata['machine_id'] = $_POST['machine_name2'];
        $pdata['machine_time'] = $_POST['total_tray'];
        $pdata['calibration_checked_by'] = '';
        $pdata['created_by'] = \Session::get('id');
        $pdata['created_at'] = date('Y-m-d');
        $pdata['organization_id'] = \Session::get('organization');
        $pdata['location_id'] = \Session::get('location');
        $pdata['company_id'] = \Session::get('companyid');
        $pdata['process_start_date'] = date('Y-m-d H:i:s', strtotime($_POST['emp_start_date_tray']));
        $pdata['process_end_date'] = date('Y-m-d H:i:s', strtotime($_POST['emp_end_date_tray']));

        \DB::table('w_jobcard_process_details_t')->insert($pdata);

        \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $jobid)->update(['traydryer_status' => '1']);


        return 1;
    }


}