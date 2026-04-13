<?php

namespace App\Http\Controllers;

use App\Qualitycheck;
use App\Qualitychecklines;
use Illuminate\Http\Request;
use App\Http\qasubmitstageController;
use App\qasubmitstage;
use Illuminate\Support\Facades\View;
use Config;
use TCPDF;
use Illuminate\Support\Facades\DB;
use App\qasubmitstagelines;
use yajra\datatables\datatables;

class QualitycheckController extends Controller
{
    /* purpose: construct function to set values throughout the controller like model,submodel,pagemethod*/
    public function __construct()
    {
        $this->data = array(
            'pageModule' => 'qualitycheck',
            'pageUrl' => url('qualitycheck')
        );
        $this->data['urlmenu'] = $this->indexs();
        $this->model = new Qualitycheck();
        $this->submodel = new Qualitychecklines();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->table = 'i_quality_spec_trx_hdr_t';
        $this->subtable = 'i_quality_spec_trx_lines_t';
        $this->data['pageFormtype'] = 'ajax';
        $this->middleware('auth');
    }
    /*end*/
    /* purpose:index function to redirect table blade*/
    public function index(Request $request)
    {

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

        $this->data['pageMethod'] = \Request::route()->getName();
        if ($this->data['pageMethod'] == "qualitycheck") {
            $this->data['pageMethod'] = "qualitycheck";
            $this->data['status'] = "qccheck";
        } else if ($this->data['pageMethod'] == "totalqualitycheck") {
            $this->data['pageMethod'] = "totalqualitycheck";
            $this->data['status'] = "";
        } else if ($this->data['pageMethod'] == "qualitycheckview") {
            $this->data['pageMethod'] = "qualitycheckview";
            $this->data['status'] = "";
        } else if ($this->data['pageMethod'] == "qcanalytical") {
            $this->data['pageMethod'] = "qcanalytical";
            $this->data['status'] = "qccheck";
        } else {
            $this->data['pageMethod'] = "qaapproval";
            $this->data['status'] = "INITIATED";
        }

        return view('qualitycheck.table', $this->data);
    }
    /*end*/
    /* purpose:index function to redirect qctable blade*/
    public function viewindex(Request $request)
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

        $this->data['pageMethod'] = \Request::route()->getName();
        if ($this->data['pageMethod'] == "qualitycheck") {
            $this->data['pageMethod'] = "qualitycheck";
            $this->data['status'] = "";
        } else if ($this->data['pageMethod'] == "totalqualitycheck") {
            $this->data['pageMethod'] = "totalqualitycheck";
            $this->data['status'] = "";
        } else if ($this->data['pageMethod'] == "qualitycheckview") {
            $this->data['pageMethod'] = "qualitycheckview";
            $this->data['status'] = "";
        } else if ($this->data['pageMethod'] == "qcanalytical") {
            $this->data['pageMethod'] = "qcanalytical";
            $this->data['status'] = "qccheck";
        } else {
            $this->data['pageMethod'] = "qaapproval";
            $this->data['status'] = "INITIATED";
        }
        return view('qualitycheck.qctable', $this->data);
    }
    /*end*  / 

      /* purpose:to show qasubmit data*/

    public function qasubmitstageappData(Request $request)
    {
        $loc = session('loc_id');
        $compy = session('companyid');
        $org = session('organization');
        $groupname = session('groupname');
        $grid_date = session('griddate');
        $gridenddate = session('gridenddate');

        $tbl_name = "w_qa_submitstage_trx_t";
        $date_col = "job_date";

        $query = DB::table('w_qa_submitstage_trx_t')
            ->leftJoin('i_quality_spec_trx_hdr_t', function ($join) use ($grid_date) {
                $join->on('w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id', '=', 'i_quality_spec_trx_hdr_t.qa_submitstage_trx_hdr_id')
                    ->where('i_quality_spec_trx_hdr_t.qatrx_date', '>=', $grid_date);
            })
            ->leftJoin('w_jobcard_hdr_t', 'w_jobcard_hdr_t.w_jobs_hdr_id', '=', 'w_qa_submitstage_trx_t.job_no')
            ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 'w_qa_submitstage_trx_t.product_id')
            ->select([
                'w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id',
                'w_qa_submitstage_trx_t.reference_no',
                'i_quality_spec_trx_hdr_t.trx_status',
                'w_qa_submitstage_trx_t.qa_status',
                'w_qa_submitstage_trx_t.job_no',
                'w_qa_submitstage_trx_t.production_qty',
                'w_qa_submitstage_trx_t.batch_no',
                'w_qa_submitstage_trx_t.job_date',
                'm_products_t.concatenated_product',
                'm_products_t.product_code',
                'w_qa_submitstage_trx_t.remarks',
                'w_qa_submitstage_trx_t.organization_id',
                'i_quality_spec_trx_hdr_t.quality_spec_trx_hdr_id',
                DB::raw('i_quality_spec_trx_hdr_t.qa_status as status'),
                'w_jobcard_hdr_t.job_no',
                'w_qa_submitstage_trx_t.quality_check',
                DB::raw("CASE 
                        WHEN i_quality_spec_trx_hdr_t.qualitycheck_status = 1 THEN 'Microbial' 
                        WHEN i_quality_spec_trx_hdr_t.qualityanalytical_status = 1 THEN 'Analytical' 
                        ELSE '' 
                     END AS quality_type")
            ])
            ->where('w_qa_submitstage_trx_t.quality_check', 'Yes');

        // Apply pageMethod logic
        $status = $request->get('status');
        $pageMethod = $request->get('pageMethod');

        if ($pageMethod == 'qaapproval') {
            if ($status == 'INITIATED') {
                $query->where('i_quality_spec_trx_hdr_t.qa_status', 'INITIATED')
                    ->where('w_qa_submitstage_trx_t.qa_status', 'INITIATED')
                    ->where(function ($q) {
                        $q->where('i_quality_spec_trx_hdr_t.qualitycheck_status', 1)
                            ->orWhere('i_quality_spec_trx_hdr_t.qualityanalytical_status', 1);
                    });
            }
        } elseif ($pageMethod == 'totalqualitycheck') {
            $query->where('i_quality_spec_trx_hdr_t.qa_status', 'INITIATED')
                ->where('w_qa_submitstage_trx_t.qc_status', 0);
        } elseif ($pageMethod == 'qcanalytical') {
            $query->where('w_qa_submitstage_trx_t.qa_status', '!=', 'APPROVED')
                ->where('w_qa_submitstage_trx_t.qanalytical_status', 0);
        } else {
            $query->where('w_qa_submitstage_trx_t.qa_status', '!=', 'APPROVED')
                ->where('w_qa_submitstage_trx_t.qc_status', 0);
        }

        // Apply session-based company/location filters
        if ($groupname == 'Superadmin' || $groupname == 'Admin') {
            $query->where('w_qa_submitstage_trx_t.company_id', $compy);
        } else {
            $query->where(function ($q) use ($tbl_name, $date_col, $grid_date, $gridenddate, $pageMethod, $status) {
                if ($pageMethod != 'qaapproval') {
                    $q->where(function ($q1) use ($tbl_name, $date_col, $grid_date, $gridenddate) {
                        $q1->where($tbl_name . '.' . $date_col, '<', $grid_date)
                            ->orWhereBetween($tbl_name . '.' . $date_col, [$grid_date, $gridenddate]);
                    });
                } else {
                    $q->where(function ($q2) use ($tbl_name, $date_col, $grid_date, $gridenddate, $status) {
                        $q2->where($tbl_name . '.' . $date_col, '<', $grid_date)
                            ->orWhereBetween($tbl_name . '.' . $date_col, [$grid_date, $gridenddate]);
                    });
                }
            })->where('w_qa_submitstage_trx_t.company_id', $compy)
                ->where('w_qa_submitstage_trx_t.location_id', $loc);
        }

        return DataTables::of($query)->make(true);
    }
    /*end*/


    /* purpose:to show qc data*/
    public function qualitycheckData(Request $request)
    {
        $compy = session('companyid');
        $griddate = session('griddate');
        $gridenddate = session('gridenddate');

        $query = DB::table('i_quality_spec_trx_hdr_t')
            ->leftJoin('w_jobcard_hdr_t', 'w_jobcard_hdr_t.w_jobs_hdr_id', '=', 'i_quality_spec_trx_hdr_t.job_hdr_id')
            ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 'i_quality_spec_trx_hdr_t.product_id')
            ->whereBetween('i_quality_spec_trx_hdr_t.qatrx_date', [$griddate, $gridenddate])
            ->where('i_quality_spec_trx_hdr_t.company_id', $compy)
            ->select([
                'i_quality_spec_trx_hdr_t.quality_spec_trx_hdr_id',
                'i_quality_spec_trx_hdr_t.reference_no',
                'i_quality_spec_trx_hdr_t.remarks',
                'i_quality_spec_trx_hdr_t.qa_status as status',
                'w_jobcard_hdr_t.job_no',
                'i_quality_spec_trx_hdr_t.production_qty',
                'i_quality_spec_trx_hdr_t.batch_no',
                'm_products_t.concatenated_product',
                'm_products_t.product_code',
            ]);

        return DataTables::of($query)

            // 🔹 Reference No Search
            ->filterColumn('reference_no', function ($query, $keyword) {
                $query->where('i_quality_spec_trx_hdr_t.reference_no', 'like', "%{$keyword}%");
            })

            // 🔹 QA Status Search
            ->filterColumn('status', function ($query, $keyword) {
                $query->where('i_quality_spec_trx_hdr_t.qa_status', 'like', "%{$keyword}%");
            })

            // 🔹 Job No Search
            ->filterColumn('job_no', function ($query, $keyword) {
                $query->where('w_jobcard_hdr_t.job_no', 'like', "%{$keyword}%");
            })

            // 🔹 Batch No Search
            ->filterColumn('batch_no', function ($query, $keyword) {
                $query->where('i_quality_spec_trx_hdr_t.batch_no', 'like', "%{$keyword}%");
            })

            // 🔹 Product Code Search
            ->filterColumn('product_code', function ($query, $keyword) {
                $query->where('m_products_t.product_code', 'like', "%{$keyword}%");
            })

            // 🔹 Product Search
            ->filterColumn('concatenated_product', function ($query, $keyword) {
                $query->where('m_products_t.concatenated_product', 'like', "%{$keyword}%");
            })

            // 🔹 Production Qty Search
            ->filterColumn('production_qty', function ($query, $keyword) {
                $query->where('i_quality_spec_trx_hdr_t.production_qty', 'like', "%{$keyword}%");
            })

            // 🔹 Remarks Search
            ->filterColumn('remarks', function ($query, $keyword) {
                $query->where('i_quality_spec_trx_hdr_t.remarks', 'like', "%{$keyword}%");
            })

            ->make(true);
    }


    /*end*/

    /** purpose:qc create*/
    public function create($id = null, $type = null)
    {

        if ($type == 'QCCHECK') {

            if (isset($_GET['pageMethod'])) {
                if ($_GET['pageMethod'] == "qcanalytical") {
                    $this->data['type'] = "QCANALYTICAL";
                } else {
                    $this->data['type'] = $type;
                }
            }

            if ($_GET['source'] == "QASUBMIT") {
                $qasubmitstage = qasubmitstage::find($id);
                $this->data['row'] = (object) array();
                $this->data['quality_spec_trx_hdr_id'] = "";
                $this->data['prdid'] = $qasubmitstage['product_id'];
                $this->data['row']->reference_no = $qasubmitstage['reference_no'];
                $this->data['row']->qa_submitstage_trx_hdr_id = $qasubmitstage['qa_submitstage_trx_hdr_id'];
                $qastatus = "INITIATED";
                $this->data['qa_status'] = $this->jcustomselectactive('a_lookuplines_t', 'lookup_code', 'lookup_code', $qastatus, "");
                $this->data['trx_status'] = $this->jcustomselectactive('a_lookuplines_t', 'lookup_code', 'lookup_code', '', " and lookup_type='QUALITY_CHECK_STATUS'");
                $this->data['job_no'] = $this->jCombocomp('w_jobcard_hdr_t', 'w_jobs_hdr_id', 'job_no', $qasubmitstage['job_no']);
                $jobdata = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $qasubmitstage['job_no'])->get();
                $this->data['job_status'] = $jobdata[0]->job_status;
                $this->data['batch_no'] = $qasubmitstage['batch_no'];
                $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $qasubmitstage['product_id']);
                $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $qasubmitstage['organization_id']);
                $this->data['rejection_type'] = $this->jcustomselectactive('a_lookuplines_t', 'lookup_code', 'lookup_code', '', " and lookup_type='REJECTION_TYPE'");
                $this->data['subinventory_id'] = $this->jCombo('m_subinventory_t', 'subinventory_id', 'subinventory_name', '');
                $this->data['sublocator_id'] = $this->jCombo('m_sublocators_t', 'sublocator_id', 'locator_code', '');
                $this->data['row']->jobcard_qty = $qasubmitstage['jobcard_qty'];
                $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $qasubmitstage['uom_code_id']);
                $this->data['verifier'] = $this->jCombo('hr_employee_t', 'employee_id', 'first_name', $qasubmitstage['verifier']);
                $this->data['row']->job_date = $qasubmitstage['job_date'];
                $this->data['row']->qatrx_date = $qasubmitstage['qatrx_date'];
                $this->data['row']->production_qty = $qasubmitstage['production_qty'];
                $this->data['row']->remarks = '';
                $this->data['row']->accepted_qty = '';
                $this->data['row']->rejected_qty = '';
                $this->data['row']->qc_type = '';
                $productid = $this->product($qasubmitstage['product_id']);
                $prddata = \DB::select("select m_products_t.subinventory_id,m_products_t.sublocator_id,m_products_t.product_id,m_product_groups_t.group_name,m_products_t.expiry_days from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) where m_products_t.product_id='" . $qasubmitstage['product_id'] . "'");
                if ($prddata[0]->group_name == 'FINISHED GOODS' || $prddata[0]->group_name == "SEMI FINISHED GOODS") {
                    if ($prddata[0]->expiry_days != "" && $prddata[0]->expiry_days != 0) {
                        $curdate = date('Y-m-01');
                        $curdate = new \DateTime($curdate);
                        $curdate->modify('+' . $prddata[0]->expiry_days . ' day');
                        /*dd($curdate);*/
                        $sesdate = \Session::get('p_date_format');
                        $this->data['row']->product_expiry_date = date($sesdate, strtotime($curdate->format('Y-m-d')));
                    } else {
                        $this->data['row']->product_expiry_date = "";
                    }
                } else {
                    $this->data['row']->product_expiry_date = "";
                    $this->data['row']->manufacturer_date = "";
                }
                if ($prddata[0]->group_name == "SEMI FINISHED GOODS") {
                    $qoh = \DB::select("select * from i_qoh_detail_t where product_id=" . $qasubmitstage['product_id'] . " and batch_number='" . $qasubmitstage['batch_no'] . "' ORDER BY `qoh_detail_id` DESC");
                    if (count($qoh) > 0) {
                        if ($qoh[0]->manufacturer_date != "") {
                            $this->data['row']->manufacturer_date = $qoh[0]->manufacturer_date;
                        } else {
                            $this->data['row']->manufacturer_date = "";
                        }
                    } else {
                        $this->data['row']->manufacturer_date = "";
                    }
                } else {
                    $job = \DB::select('select * from w_jobcard_hdr_t where w_jobs_hdr_id=' . $qasubmitstage['job_no']);
                    if (count($job) > 0) {
                        $jobdata = \DB::select('select * from w_jobcard_hdr_t where w_jobs_hdr_id=' . $job[0]->reference_source_id . ' and product_id=' . $job[0]->product_id . ' and bom_process="PROCESS-1"');
                        if (count($jobdata) > 0) {
                            $qoh = \DB::select("select * from i_qoh_detail_t where product_id=" . $jobdata[0]->bom_product_id . " and batch_number='" . $jobdata[0]->batch_no . "' and qoh_source='PRODUCTION STORE MOVE' ORDER BY `qoh_detail_id` DESC");
                            if ($qoh[0]->manufacturer_date != "") {
                                $this->data['row']->manufacturer_date = $qoh[0]->manufacturer_date;
                            } else {
                                $this->data['row']->manufacturer_date = "";
                            }
                        } else {
                            $this->data['row']->manufacturer_date = "";
                        }
                    } else {
                        $this->data['row']->manufacturer_date = "";
                    }
                }


                $qaspechdr = \DB::table('i_quality_product_specs_hdr_t')
                    ->where('product_id', $productid)
                    ->get();

                $this->data['row']->quality_type = "";

                if ($qaspechdr->isNotEmpty()) {

                    // Get all header IDs as array
                    $hdrIds = $qaspechdr->pluck('quality_product_specs_hdr_id')->toArray();

                    if ($_GET['pageMethod'] == "qcanalytical") {

                        $qaspeclines = \DB::table('i_quality_product_specs_lines_t')
                            ->whereIn('quality_product_specs_hdr_id', $hdrIds)
                            ->where('quality_type', 'Analytical')
                            ->get();

                    } else {

                        $qaspeclines = \DB::table('i_quality_product_specs_lines_t')
                            ->whereIn('quality_product_specs_hdr_id', $hdrIds)
                            ->where('quality_type', '!=', 'Analytical')
                            ->get();
                    }

                } else {
                    $qaspeclines = array();
                }

                $this->data['linedata'] = $qaspeclines;
                if (count($this->data['linedata']) >= 1) {
                    foreach ($this->data['linedata'] as $key => $value) {
                        $this->data['linedata'][$key]->quality_spec_trx_line_id = "";
                        $this->data['linedata'][$key]->parameter = $value->parameter;
                        $this->data['row']->quality_type = $value->quality_type;
                        $specval = \DB::table('a_lookuplines_t')->where('lookuplines_id', $value->spec_criteria)->get();
                        $this->data['linedata'][$key]->spec_criteriacode = $specval[0]->lookup_code;
                        $this->data['linedata'][$key]->spec_criteria = $this->jcustomselectactive('a_lookuplines_t', 'lookuplines_id', 'lookup_code', $value->spec_criteria, "");
                        $this->data['linedata'][$key]->spec_value_from = $value->spec_value_from;
                        $this->data['linedata'][$key]->spec_value_to = $value->spec_value_to;
                        $this->data['linedata'][$key]->uom = $value->uom;
                        $this->data['linedata'][$key]->measurement = '';
                        $qchdr = \DB::table('i_quality_spec_trx_hdr_t')->leftjoin('i_quality_spec_trx_lines_t', 'i_quality_spec_trx_lines_t.quality_spec_trx_hdr_id', '=', 'i_quality_spec_trx_hdr_t.quality_spec_trx_hdr_id')->leftjoin('w_jobcard_hdr_t', 'w_jobcard_hdr_t.w_jobs_hdr_id', '=', 'i_quality_spec_trx_hdr_t.job_hdr_id')->where('i_quality_spec_trx_hdr_t.job_hdr_id', $qasubmitstage['job_no'])->where('w_jobcard_hdr_t.job_status', 'REWORK')->orderby('i_quality_spec_trx_hdr_t.quality_spec_trx_hdr_id', 'desc')->get();
                        if (count($qchdr) > 0) {
                            $this->data['linedata'][$key]->old_measurement = $qchdr[0]->measurement;
                        } else {
                            $this->data['linedata'][$key]->old_measurement = '';
                        }
                        $this->data['linedata'][$key]->comments = '';
                        $this->data['linedata'][$key]->qc_comments = '';
                    }
                }
            } else {
                $appdata = \DB::table('i_quality_spec_trx_hdr_t')->where('quality_spec_trx_hdr_id', $id)->get();
                $this->data['row'] = (object) array();
                $this->data['prdid'] = $appdata[0]->product_id;
                $this->data['quality_spec_trx_hdr_id'] = $appdata[0]->quality_spec_trx_hdr_id;
                $this->data['row']->reference_no = $appdata[0]->reference_no;
                $jobdata = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $appdata[0]->job_hdr_id)->get();
                $this->data['job_status'] = $jobdata[0]->job_status;
                $this->data['row']->qa_submitstage_trx_hdr_id = $appdata[0]->qa_submitstage_trx_hdr_id;
                $this->data['qa_status'] = $this->jCombo('a_lookuplines_t', 'lookup_code', 'lookup_code', $appdata[0]->qa_status);
                $this->data['trx_status'] = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', $appdata[0]->trx_status, " and lookup_type='QUALITY_CHECK_STATUS'");
                $this->data['job_no'] = $this->jCombocomp('w_jobcard_hdr_t', 'w_jobs_hdr_id', 'job_no', $appdata[0]->job_hdr_id);
                $this->data['batch_no'] = $appdata[0]->batch_no;
                $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $appdata[0]->product_id);
                $this->data['organization_id'] = $this->jCombologin('m_organizations_t', 'organization_id', 'organization_name', $appdata[0]->organization_id);
                $this->data['rejection_type'] = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', $appdata[0]->rejection_type, " and lookup_type='REJECTION_TYPE'");
                $this->data['subinventory_id'] = $this->jCombo('m_subinventory_t', 'subinventory_id', 'subinventory_name', $appdata[0]->subinventory_id);
                $this->data['sublocator_id'] = $this->jCombo('m_sublocators_t', 'sublocator_id', 'locator_code', $appdata[0]->sublocator_id);
                $this->data['row']->jobcard_qty = $appdata[0]->jobcard_qty;
                $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $appdata[0]->uom_code_id);
                $this->data['verifier'] = $this->jCombo('hr_employee_t', 'employee_id', 'first_name', $appdata[0]->verifier);
                $this->data['row']->job_date = $appdata[0]->job_date;
                $this->data['row']->qatrx_date = $appdata[0]->qatrx_date;
                $this->data['row']->production_qty = $appdata[0]->production_qty;
                $this->data['row']->remarks = $appdata[0]->remarks;
                $this->data['row']->qc_type = $appdata[0]->qc_type;
                $this->data['row']->accepted_qty = $appdata[0]->accepted_qty;
                $this->data['row']->rejected_qty = $appdata[0]->rejected_qty;
                $this->data['row']->manufacturer_date = $appdata[0]->manufacturer_date;
                $this->data['row']->product_expiry_date = $appdata[0]->product_expiry_date;
                $this->data['row']->quality_type = $appdata[0]->quality_type;
                $qaspechdr = \DB::table('i_quality_spec_trx_lines_t')->where('quality_spec_trx_hdr_id', $id)->get();
                $this->data['linedata'] = $qaspechdr;
                if (count($this->data['linedata']) >= 1) {
                    foreach ($this->data['linedata'] as $key => $value) {

                        $this->data['linedata'][$key]->parameter = $value->parameter;
                        $specval = \DB::table('a_lookuplines_t')->where('lookuplines_id', $value->spec_criteria)->get();

                        $this->data['linedata'][$key]->spec_criteriacode = $specval[0]->lookup_code;
                        $this->data['linedata'][$key]->spec_criteria = $this->jCombo('a_lookuplines_t', 'lookuplines_id', 'lookup_code', $value->spec_criteria);
                        $this->data['linedata'][$key]->spec_value_from = $value->spec_value_from;
                        $this->data['linedata'][$key]->spec_value_to = $value->spec_value_to;
                        $this->data['linedata'][$key]->measurement = $value->measurement;
                        $this->data['linedata'][$key]->old_measurement = $value->old_measurement;
                        $this->data['linedata'][$key]->comments = $value->comments;
                    }
                }

            }
        }
        /* purpose:qc approve*/ else if ($type == 'QCAPPROVE') {
            $this->data['type'] = $type;
            $appdata = \DB::table('i_quality_spec_trx_hdr_t')->where('quality_spec_trx_hdr_id', $id)->get();
            $this->data['row'] = (object) array();
            $this->data['prdid'] = $appdata[0]->product_id;
            $this->data['quality_spec_trx_hdr_id'] = $appdata[0]->quality_spec_trx_hdr_id;
            $this->data['row']->reference_no = $appdata[0]->reference_no;
            $this->data['row']->qa_submitstage_trx_hdr_id = $appdata[0]->qa_submitstage_trx_hdr_id;
            $jobdata = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $appdata[0]->job_hdr_id)->get();
            $this->data['job_status'] = $jobdata[0]->job_status;
            $this->data['qa_status'] = $this->jcustomselectactive('a_lookuplines_t', 'lookup_code', 'lookup_code', $appdata[0]->qa_status, "");
            $this->data['trx_status'] = $this->jcustomselectactive('a_lookuplines_t', 'lookup_code', 'lookup_code', $appdata[0]->trx_status, " and lookup_type='QUALITY_CHECK_STATUS'");
            $this->data['job_no'] = $this->jCombocomp('w_jobcard_hdr_t', 'w_jobs_hdr_id', 'job_no', $appdata[0]->job_hdr_id);
            $this->data['batch_no'] = $appdata[0]->batch_no;
            $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $appdata[0]->product_id);
            $this->data['organization_id'] = $this->jCombologin('m_organizations_t', 'organization_id', 'organization_name', $appdata[0]->organization_id);
            $this->data['rejection_type'] = $this->jcustomselectactive('a_lookuplines_t', 'lookup_code', 'lookup_code', $appdata[0]->rejection_type, " and lookup_type='REJECTION_TYPE'");
            $this->data['subinventory_id'] = $this->jCombo('m_subinventory_t', 'subinventory_id', 'subinventory_name', $appdata[0]->subinventory_id);
            $this->data['sublocator_id'] = $this->jCombo('m_sublocators_t', 'sublocator_id', 'locator_code', $appdata[0]->sublocator_id);
            $this->data['row']->jobcard_qty = $appdata[0]->jobcard_qty;
            $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $appdata[0]->uom_code_id);
            $this->data['verifier'] = $this->jCombo('hr_employee_t', 'employee_id', 'first_name', $appdata[0]->verifier);
            $this->data['row']->job_date = $appdata[0]->job_date;
            $this->data['row']->qatrx_date = $appdata[0]->qatrx_date;
            $this->data['row']->production_qty = $appdata[0]->production_qty;
            $this->data['row']->remarks = $appdata[0]->remarks;
            $this->data['row']->qc_type = $appdata[0]->qc_type;
            $this->data['row']->accepted_qty = $appdata[0]->accepted_qty;
            $this->data['row']->rejected_qty = $appdata[0]->rejected_qty;
            $this->data['row']->quality_type = $appdata[0]->quality_type;
            $this->data['row']->product_expiry_date = $appdata[0]->product_expiry_date;
            $this->data['row']->manufacturer_date = $appdata[0]->manufacturer_date;
            $qaspechdr = \DB::table('i_quality_spec_trx_lines_t')->where('quality_spec_trx_hdr_id', $id)->get();
            $this->data['linedata'] = $qaspechdr;
            if (count($this->data['linedata']) >= 1) {
                foreach ($this->data['linedata'] as $key => $value) {

                    $this->data['linedata'][$key]->quality_spec_trx_line_id = $value->quality_spec_trx_line_id;
                    $this->data['linedata'][$key]->parameter = $value->parameter;
                    $specval = \DB::table('a_lookuplines_t')->where('lookuplines_id', $value->spec_criteria)->get();
                    $this->data['linedata'][$key]->spec_criteriacode = $specval[0]->lookup_code;
                    $this->data['linedata'][$key]->spec_criteria = $this->jcustomselectactive('a_lookuplines_t', 'lookuplines_id', 'lookup_code', $value->spec_criteria, "");
                    $this->data['linedata'][$key]->spec_value_from = $value->spec_value_from;
                    $this->data['linedata'][$key]->spec_value_to = $value->spec_value_to;
                    $this->data['linedata'][$key]->measurement = $value->measurement;
                    $this->data['linedata'][$key]->old_measurement = $value->old_measurement;
                    $this->data['linedata'][$key]->comments = $value->comments;
                    $this->data['linedata'][$key]->qc_comments = $this->jcustomselectactive('a_lookuplines_t', 'lookup_code', 'lookup_code', '', 'and lookup_type="QC_REMARKS"');
                }
            }
        }
        /*end*/

        return view('qualitycheck.form', $this->data);
    }


    /* purpose:save function for qc*/

    public function save(Request $request)
    {

        $form = $request->all();
        $form = $request->except([
            '_token',
            'form_config',
            'form_data_json',
            'savestatus',
            'submit_type',
            'choosefile',
            'existing_file',
            'typeurl',
            'enable-masterdetail'
        ]);
        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');
        $lines_data = $this->validatePost($form, $this->subtable, 'lines');

        \DB::beginTransaction();
        //  
        try {

            $jobid = $_POST['job_hdr_id'];
            $job = \DB::select('select * from w_jobcard_hdr_t where w_jobs_hdr_id=' . $jobid);

            if ($_POST['typeurl'] == 'QCANALYTICAL') {

                $data['qualityanalytical_status'] = 1;
                if ($data['qa_status'] == 'REJECTED') {
                    $data['qa_status'] = "INITIATED";
                    \DB::table('w_qa_submitstage_trx_t')->where('qa_submitstage_trx_hdr_id', $_POST['qa_submitstage_trx_hdr_id'])->update(['qanalytical_status' => 0, 'qa_status' => $data['qa_status']]);

                } else {

                    \DB::table('w_qa_submitstage_trx_t')->where('qa_submitstage_trx_hdr_id', $_POST['qa_submitstage_trx_hdr_id'])->update(['qanalytical_status' => 1, 'qa_status' => $_POST['qa_status']]);
                }
                //    dd($data);
                $id = $this->model->insertRow($data);
                $this->auditlog($id, "Qualitycheck", 'create', $data, "i_quality_spec_trx_hdr_t");
                $lid = $this->submodel->subgridSave($lines_data, $id);

            } else if ($_POST['typeurl'] == 'QCCHECK') {
                $data['qualitycheck_status'] = 1;
                if ($data['qa_status'] == 'REJECTED') {
                    $data['qa_status'] = "INITIATED";

                    \DB::table('w_qa_submitstage_trx_t')->where('qa_submitstage_trx_hdr_id', $_POST['qa_submitstage_trx_hdr_id'])->update(['qc_status' => 0, 'qa_status' => $data['qa_status']]);

                } else {

                    \DB::table('w_qa_submitstage_trx_t')->where('qa_submitstage_trx_hdr_id', $_POST['qa_submitstage_trx_hdr_id'])->update(['qc_status' => 1, 'qa_status' => $data['qa_status']]);

                }
                $id = $this->model->insertRow($data);
                /* purpose:audit log*/
                $this->auditlog($id, "Qualitycheck", 'create', $data, "i_quality_spec_trx_hdr_t");
                /*end*/
                //dd($data);
                $lid = $this->submodel->subgridSave($lines_data, $id);
                if ($_POST['rejection_type'] == "REWORK") {
                    $dataqoh['job_status'] = "REWORK";
                    $rewok = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $jobid)->update(['job_status' => 'REWORK']);
                    \DB::table('notifications_t')->where('reference_source_id', $jobid)->where('reference_source', '=', 'JOBCARD COMPLETION')->update(['read/unread' => 'read']);
                } else {
                    if ($_POST['rejection_type'] == "SCRAP") {
                        $rewok = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $jobid)->update(['job_status' => 'SCRAP']);
                    }

                }
                if ($_POST['trx_status'] == "ACCEPTED") {
                    $notifcation = $_POST['reference_no'] . 'Quality Accepted';
                    $send_notification = $this->sendPopUpHomeNoty($id, "QA SUBMIT", $notifcation, 'qcapproval');

                }
            } else if ($_POST['typeurl'] == 'QCAPPROVE') {
                if ($_POST['qa_status'] == "APPROVED") {
                    $id = $_POST['qa_submitstage_trx_hdr_id'];
                    /* Journal Entry For Production Product When  */
                    $journal_name = "Quality Approve";
                    $date = date('Y-m-d');
                    $org = \Session::get('organization');
                    $loc = \Session::get('loc_id');
                    $compy = \Session::get('companyid');
                    $jobid = $_POST['job_hdr_id'];
                    /*Journal Header Insert*/
                    $jid = 0;

                    // production qc approve mail purpose
                    $emp = \Session::get('id');
                    $user_mail = \DB::select("select user_mail,first_name from tb_users where employee_id='$emp'");
                    if (!empty($user_mail) && !empty($user_mail[0]->user_mail)) {
                        $from_umail = $user_mail[0]->user_mail;
                    } else {
                        $from_umail = "aspire@jrkresearch.com";
                    }

                    $job_created = \DB::select("select job_created_by,job_no from w_jobcard_hdr_t where w_jobs_hdr_id='$jobid'");
                    $job_created_id = $job_created[0]->job_created_by;
                    $pro_id = $_POST['product_id'];
                    $pro_name1 = \DB::SELECT("select concatenated_product from m_products_t where product_id='$pro_id'");
                    $to_mail_id = \DB::select("select user_mail from tb_users where employee_id='$job_created_id'");
                    if (!empty($to_mail_id) && !empty($to_mail_id[0]->user_mail)) {
                        $to_mail = $to_mail_id[0]->user_mail;
                    } else {
                        $to_mail = "aspire@jrkresearch.com";
                    }
                    $hdr_id = $_POST['qa_submitstage_trx_hdr_id'];
                    $details = \DB::SELECT("select reference_no,batch_no,qatrx_date,production_qty from w_qa_submitstage_trx_t where qa_submitstage_trx_hdr_id='$hdr_id'");
                    $emp_name = $user_mail[0]->first_name;
                    $job_no = $job_created[0]->job_no;
                    $qa_number = $details[0]->reference_no;
                    $job_date = $details[0]->qatrx_date;
                    $batch_no = $details[0]->batch_no;
                    $sub = "$job_no APPROVED";
                    $qty = $details[0]->production_qty;
                    $cur_date = date("Y-m-d");
                    $pro_name = $pro_name1[0]->concatenated_product;
                    $status = $_POST['qa_status'];
                    $msg = "<p>Dear Team,<br><br>Job No - $job_no <br>Job Completion Date - $job_date <br>Product Name - $pro_name<br>Batch No- $batch_no<br>QA Number - $qa_number<br>Qty - $qty<br>Qc Approve Date -$cur_date<br>Status - $status<br><br>Regards, <br> $emp_name";

                    if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                        Config::set('mail.username', \Session::get('user_email'));
                        Config::set('mail.password', \Session::get('user_password'));
                    }

                    \Mail::send([], [], function ($message) use ($from_umail, $to_mail, $sub, $msg) {

                        $message->from($from_umail)
                            ->to(explode(',', $to_mail))
                            ->cc('operations@jrkresearch.com')
                            ->subject($sub)
                            ->setBody($msg, 'text/html');
                    });

                    $notifcation = 'Quality Approved ' . $_POST['reference_no'] . ' for quality check';
                    $send_notification = $this->sendPopUpHomeNoty($_POST['quality_spec_trx_hdr_id'], "QA APPROVAL", $notifcation, 'prodmovetoinventory');
                    \DB::table('notifications_t')->where('reference_source_id', $jobid)->where('reference_source', '=', 'JOBCARD COMPLETION')->update(['read/unread' => 'read']);
                    $products = DB::table('m_products_t')
                        ->where('product_id', $pro_id)
                        ->get();
                    if ($products[0]->product_group_id == 1 && $job[0]->bom_process == "FINALPROCESS") {
                        $qualitystatus = 1;
                    } else if ($products[0]->product_group_id == 4) {
                        $qualitystatus = 1;
                    } else {
                        $qualitystatus = 0;
                    }
                    /* purpose:to update quality status based on trx status*/
                    if ($_POST['trx_status'] == "ACCEPTED" || $_POST['trx_status'] == "CONDITIONAL ACCEPTED") {


                        \DB::table('i_qoh_detail_t')->where('job_id', $jobid)
                            ->update(['qualitystatus' => $qualitystatus, 'qualitytype' => 'accepted']);
                    } else if ($_POST['trx_status'] == "REJECTED" && $_POST['rejection_type'] == "REWORK") {

                        $qohdata = \DB::table('i_qoh_detail_t')->where('job_id', $jobid)->where('product_id', $_POST['product_id'])->get();
                        if (count($qohdata) > 0) {
                            \DB::table('i_qoh_detail_t')->where('qoh_detail_id', $qohdata[0]->qoh_detail_id)
                                ->update(['qualitystatus' => 0, 'qualitytype' => 'rework', 'rework_qty' => $_POST['production_qty'], 'qoh_trx_qty' => 0, 'scrap_qty' => 0]);
                        }
                    } else if ($_POST['trx_status'] == "REJECTED" && $_POST['rejection_type'] == "SCRAP") {

                        \DB::table('i_qoh_detail_t')->where('job_id', $jobid)
                            ->update(['qualitystatus' => 0, 'qualitytype' => 'scrap', 'scrap_qty' => $_POST['production_qty'], 'qoh_trx_qty' => 0]);
                    }
                    /*end*/


                    if ($_POST['quality_type'] == "Analytical") {
                        \DB::table('w_qa_submitstage_trx_t')->where('qa_submitstage_trx_hdr_id', $_POST['qa_submitstage_trx_hdr_id'])->update(['qanalytical_status' => 1, 'qa_status' => $data['qa_status']]);
                    } else {
                        \DB::table('w_qa_submitstage_trx_t')->where('qa_submitstage_trx_hdr_id', $_POST['qa_submitstage_trx_hdr_id'])->update(['qc_status' => 1, 'qa_status' => $data['qa_status']]);
                    }
                } else if ($_POST['qa_status'] == "REJECTED") {

                    // production qc approve mail purpose
                    $emp = \Session::get('id');
                    $user_mail = \DB::select("select user_mail,first_name from tb_users where employee_id='$emp'");
                    if (!empty($user_mail) && !empty($user_mail[0]->user_mail)) {
                        $from_umail = $user_mail[0]->user_mail;
                    } else {
                        $from_umail = "aspire@jrkresearch.com";
                    }

                    $job_created = \DB::select('select job_created_by,job_no from w_jobcard_hdr_t where w_jobs_hdr_id=' . $jobid);
                    $job_created_id = $job_created[0]->job_created_by;
                    $pro_id = $_POST['product_id'];
                    $pro_name1 = \DB::SELECT("select concatenated_product from m_products_t where product_id='$pro_id'");
                    $to_mail_id = \DB::select("select user_mail from tb_users where employee_id='$job_created_id'");
                    if (!empty($to_mail_id) && !empty($to_mail_id[0]->user_mail)) {
                        $to_mail = $to_mail_id[0]->user_mail;
                    } else {
                        $to_mail = "aspire@jrkresearch.com";
                    }
                    $hdr_id = $_POST['qa_submitstage_trx_hdr_id'];
                    $details = \DB::SELECT("select reference_no,batch_no,qatrx_date,production_qty from w_qa_submitstage_trx_t where qa_submitstage_trx_hdr_id='$hdr_id'");
                    $emp_name = $user_mail[0]->first_name;
                    $job_no = $job_created[0]->job_no;
                    $qa_number = $details[0]->reference_no;
                    $job_date = $details[0]->qatrx_date;
                    $batch_no = $details[0]->batch_no;
                    $sub = "$job_no APPROVED";
                    $qty = $details[0]->production_qty;
                    $cur_date = date("Y-m-d");
                    $pro_name = $pro_name1[0]->concatenated_product;
                    $status = $_POST['qa_status'];
                    $msg = "<p>Dear Team,<br><br>Job No - $job_no <br>Job Completion Date - $job_date <br>Product Name - $pro_name<br>Batch No- $batch_no<br>QA Number - $qa_number<br>Qty - $qty<br>Qc Approve Date -$cur_date<br>Status - $status<br><br>Regards, <br> $emp_name";

                    if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                        Config::set('mail.username', \Session::get('user_email'));
                        Config::set('mail.password', \Session::get('user_password'));
                    }

                    \Mail::send([], [], function ($message) use ($from_umail, $to_mail, $sub, $msg) {

                        $message->from($from_umail)
                            ->to(explode(',', $to_mail))
                            ->cc('operations@jrkresearch.com')
                            ->subject($sub)
                            ->setBody($msg, 'text/html');
                    });

                    $notifcation = 'Quality ' . $_POST['reference_no'] . ' Quality Rejected. Add these components' . $_POST['remarks'];
                    $send_notification = $this->sendPopUpHomeNoty($_POST['quality_spec_trx_hdr_id'], "QA APPROVAL", $notifcation, 'qcapproval');
                    if ($_POST['quality_type'] == "Analytical") {
                        \DB::table('w_qa_submitstage_trx_t')->where('qa_submitstage_trx_hdr_id', $_POST['qa_submitstage_trx_hdr_id'])->update(['qanalytical_status' => 0, 'qa_status' => $data['qa_status']]);
                    } else {
                        \DB::table('w_qa_submitstage_trx_t')->where('qa_submitstage_trx_hdr_id', $_POST['qa_submitstage_trx_hdr_id'])->update(['qc_status' => 0, 'qa_status' => $data['qa_status']]);
                    }
                }

                \DB::table('i_quality_spec_trx_hdr_t')->where('quality_spec_trx_hdr_id', $_POST['quality_spec_trx_hdr_id'])
                    ->update(['qa_status' => $_POST["qa_status"], 'trx_status' => $_POST['trx_status']]);

                foreach ($_POST['bulk_quality_spec_trx_line_id'] as $key => $val) {
                    \DB::table('i_quality_spec_trx_lines_t')->where('quality_spec_trx_hdr_id', $_POST['quality_spec_trx_hdr_id'])->where('quality_spec_trx_line_id', $val)
                        ->update(['qc_comments' => $_POST["bulk_qc_comments"][$key]]);
                }


                $plan = \DB::select('select * from w_productionplan_hdr_t where productionplan_hdr_id=' . $job[0]->reference_source_id);
                $planjobqty = $plan[0]->plan_qty - $job[0]->job_adjusted_qty;
                $pendjobqty = $plan[0]->pending_qty + $job[0]->job_adjusted_qty;
                if ($job[0]->bom_process == 'FINALPROCESS' || $job[0]->bom_process == '0' || $job[0]->bom_process == '') {
                    \DB::table('w_productionplan_hdr_t')->where('productionplan_hdr_id', $job[0]->reference_source_id)->where('product_id', $_POST['product_id'])->update(['plan_qty' => $planjobqty, 'pending_qty' => $pendjobqty]);
                }
                $plan1 = \DB::select('select * from w_productionplan_hdr_t where productionplan_hdr_id=' . $job[0]->reference_source_id);
                $planqty = $plan1[0]->plan_qty + $_POST['production_qty'];
                $pendqty = $plan1[0]->production_qty - $planqty;
                if ($planqty == $plan1[0]->production_qty) {
                    $planstatus = "COMPLETED";
                } else {
                    $planstatus = $plan1[0]->plan_status;
                }

                if (count($job) > 0) {
                    if ($job[0]->bom_process == 'FINALPROCESS' || $job[0]->bom_process == '0' || $job[0]->bom_process == '') {
                        \DB::table('w_productionplan_hdr_t')->where('productionplan_hdr_id', $job[0]->reference_source_id)->where('product_id', $_POST['product_id'])->update(['plan_qty' => $planqty, 'pending_qty' => $pendqty, 'plan_status' => $planstatus]);
                    }
                    if ($job[0]->bom_process == '0' || $job[0]->bom_process == '') {
                        $planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $job[0]->reference_source_id)->where('product_id', $_POST['product_id'])->get();
                    } else {
                        $planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $job[0]->reference_source_id)->where('parent_product', $_POST['product_id'])->where('process_level', $job[0]->bom_process)->get();

                    }
                    $qty = $plan[0]->production_qty;
                    if (count($planlines) > 0) {
                        $prdqtyjob = $planlines[0]->production_qty - $job[0]->job_adjusted_qty;
                        $pendingqtyjob = $planlines[0]->pending_qty + $job[0]->job_adjusted_qty;
                        if ($job[0]->bom_process == '0' || $job[0]->bom_process == '') {
                            \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $job[0]->reference_source_id)->where('product_id', $_POST['product_id'])->update(['production_qty' => $prdqtyjob, 'pending_qty' => $pendingqtyjob]);
                        } else {
                            \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $job[0]->reference_source_id)->where('process_level', $job[0]->bom_process)->where('parent_product', $_POST['product_id'])->update(['production_qty' => $prdqtyjob, 'pending_qty' => $pendingqtyjob]);
                        }
                    }
                    if ($job[0]->bom_process == '0' || $job[0]->bom_process == '') {
                        $planlines1 = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $job[0]->reference_source_id)->where('product_id', $_POST['product_id'])->get();
                    } else {
                        $planlines1 = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $job[0]->reference_source_id)->where('parent_product', $_POST['product_id'])->where('process_level', $job[0]->bom_process)->get();

                    }
                    if (count($planlines1) > 0) {

                        $qty = $plan1[0]->production_qty;
                        if ($planlines1[0]->production_qty != 0) {
                            $prdqty = $planlines1[0]->production_qty - $_POST['production_qty'];
                        } else {
                            $prdqty = $_POST['production_qty'];
                        }
                        $pendingqty = $qty - $prdqty;
                        if ($pendingqty < 0) {
                            $pendingqty = 0;
                        } else {
                            $pendingqty = $pendingqty;
                        }
                        if ($job[0]->bom_process == '0' || $job[0]->bom_process == '') {
                            \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $job[0]->reference_source_id)->where('product_id', $_POST['product_id'])->update(['production_qty' => $prdqty, 'pending_qty' => $pendingqty]);
                        } else {
                            \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $job[0]->reference_source_id)->where('process_level', $job[0]->bom_process)->where('parent_product', $_POST['product_id'])->update(['production_qty' => $prdqty, 'pending_qty' => $pendingqty]);
                        }

                    }

                }
            }

            \DB::commit();

            return response()->json(array('status' => 'success', 'message' => 'Saved Successfully', 'id' => $id));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }
    /*end*/


    public function view($id = null)
    {


        $headerdata = \DB::SELECT("SELECT
    i_quality_spec_trx_hdr_t.quality_spec_trx_hdr_id,
    i_quality_spec_trx_hdr_t.reference_no, i_quality_spec_trx_hdr_t.trx_status,
    i_quality_spec_trx_hdr_t.qa_status,
    w_jobcard_hdr_t.job_no,
    i_quality_spec_trx_hdr_t.production_qty,
    i_quality_spec_trx_hdr_t.batch_no,
    i_quality_spec_trx_hdr_t.qatrx_date,
    m_products_t.concatenated_product,
    m_products_t.product_code,
    m_uom_codes_t.uom_code,
    i_quality_spec_trx_hdr_t.quality_spec_trx_hdr_id,
i_quality_spec_trx_hdr_t.qa_status as status,
    w_jobcard_hdr_t.job_no
FROM
    `i_quality_spec_trx_hdr_t`
left join w_jobcard_hdr_t on w_jobcard_hdr_t.w_jobs_hdr_id=i_quality_spec_trx_hdr_t.job_hdr_id
left join m_products_t on m_products_t.product_id=i_quality_spec_trx_hdr_t.product_id
left join m_uom_codes_t on i_quality_spec_trx_hdr_t.uom_code_id=m_uom_codes_t.uom_code_id

WHERE
    1 = 1 and  i_quality_spec_trx_hdr_t.quality_spec_trx_hdr_id=$id");
        $lineardata = \DB::select("select i_quality_spec_trx_lines_t.parameter,i_quality_spec_trx_lines_t.spec_criteria,i_quality_spec_trx_lines_t.measurement,i_quality_spec_trx_lines_t.spec_value_from,i_quality_spec_trx_lines_t.spec_value_to,i_quality_spec_trx_lines_t.uom,i_quality_spec_trx_lines_t.old_measurement,i_quality_spec_trx_lines_t.accepted_qty,a_lookuplines_t.lookup_code,i_quality_spec_trx_lines_t.rejected_qty,a_lookuplines_t.lookup_code from i_quality_spec_trx_lines_t  left join a_lookuplines_t on(a_lookuplines_t.lookuplines_id=i_quality_spec_trx_lines_t.spec_criteria) where quality_spec_trx_hdr_id=" . $id);
        $nddata = \DB::select('select i_quality_spec_trx_hdr_t.product_id,i_quality_spec_trx_hdr_t.uom_code_id,i_quality_spec_trx_hdr_t.batch_no,m_products_t.product_code,m_products_t.concatenated_product,m_uom_codes_t.uom_code,i_quality_spec_trx_hdr_t.trx_status,i_quality_spec_trx_hdr_t.remarks from i_quality_spec_trx_hdr_t left join m_products_t on(m_products_t.product_id=i_quality_spec_trx_hdr_t.product_id) left join m_uom_codes_t on(m_uom_codes_t.uom_code_id=i_quality_spec_trx_hdr_t.uom_code_id) where i_quality_spec_trx_hdr_t.quality_spec_trx_hdr_id=' . $id);
        $this->data['nddata'] = $nddata;
        $this->data['headerdata'] = $headerdata[0];
        $this->data['lineardata'] = $lineardata;

        return view('qualitycheck.qctableview', $this->data);
    }


    public function print($id)
    {

        require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');

        $company_id = \Session::get('companyid');
        $sql = \DB::select('select i_quality_spec_trx_hdr_t.product_id ,i_quality_spec_trx_hdr_t.uom_code_id,i_quality_spec_trx_hdr_t.batch_no,m_products_t.product_code,m_products_t.concatenated_product,m_uom_codes_t.uom_code,i_quality_spec_trx_hdr_t.trx_status,i_quality_spec_trx_hdr_t.remarks from i_quality_spec_trx_hdr_t left join m_products_t on(m_products_t.product_id=i_quality_spec_trx_hdr_t.product_id) left join m_uom_codes_t on(m_uom_codes_t.uom_code_id=i_quality_spec_trx_hdr_t.uom_code_id) where i_quality_spec_trx_hdr_t.quality_spec_trx_hdr_id=' . $id);
        $qcsql = \DB::select("select i_quality_spec_trx_lines_t.parameter,i_quality_spec_trx_lines_t.spec_criteria,i_quality_spec_trx_lines_t.measurement,i_quality_spec_trx_lines_t.spec_value_from,i_quality_spec_trx_lines_t.spec_value_to,i_quality_spec_trx_lines_t.accepted_qty,i_quality_spec_trx_lines_t.rejected_qty,a_lookuplines_t.lookup_code from i_quality_spec_trx_lines_t left join a_lookuplines_t on(a_lookuplines_t.lookuplines_id=i_quality_spec_trx_lines_t.spec_criteria) where quality_spec_trx_hdr_id=" . $id);
        $this->data['linedata'] = $qcsql;


        $this->data['batch_no'] = $sql[0]->batch_no;
        $this->data['product_code'] = $sql[0]->product_code;
        $this->data['product_description'] = $sql[0]->concatenated_product;
        $this->data['unit'] = $sql[0]->uom_code;
        $this->data['status'] = $sql[0]->trx_status;
        $this->data['correction'] = $sql[0]->remarks;
        /************** location *******************/

        $location = $this->getLocationaddress();
        $this->data['location_name'] = $location[0]->location_name;
        $this->data['country'] = $this->getCountry($location[0]->country_id);
        $this->data['state'] = $this->getState($location[0]->state_id);
        $this->data['city'] = $this->getCity($location[0]->city_id);
        $this->data['area'] = $location[0]->area;

        /********************* company *******************/
        $company = $this->getCompany();
        if (!empty($company)) {
            $company_name = $company[0]->company_name;
            $this->data['company_name'] = $company_name;
        } else {
            $this->data['company_name'] = '';
        }
        /******************** end ************************/
        /************** date ***************/
        $this->data['date'] = date('d/m/y');

        /************ End ******************/
        /******************* location *******************/

        $location = $this->getLocationaddress();
        if (!empty($location)) {
            $this->data['location_name'] = $location[0]->location_name;
            $this->data['country'] = $this->getCountry($location[0]->country_id);
            $this->data['state_l'] = $this->getState($location[0]->state_id);
            $this->data['city'] = $this->getCity($location[0]->city_id);
            $this->data['area'] = $location[0]->area;
            $this->data['pincode'] = $location[0]->pincode;
            $this->data['gst_no'] = $location[0]->gst_no;
            $this->data['pan_no'] = $location[0]->pan_no;
            $this->data['street'] = $location[0]->street_name;
            $this->data['address'] = $location[0]->address;
        } else {
            $this->data['location_name'] = '';
            $this->data['country'] = '';
            $this->data['state_l'] = '';
            $this->data['state'] = '';
            $this->data['city'] = '';
            $this->data['area'] = '';
            $this->data['pincode'] = '';
            $this->data['gst_no'] = '';
            $this->data['pan_no'] = '';
            $this->data['street'] = '';
            $this->data['address'] = '';
        }

        $this->data['company_address'] = $this->data['address'] . "," . $this->data['street'] . "," . $this->data['area'] . "," . $this->data['city'] . ", " . $this->data['state'] . "," . $this->data['country'] . "," . $this->data['pincode'];

        $terms_lines = array();
        $terms_lines == array("element_content" => "");
        $this->data['terms'] = $terms_lines;
        $this->data['print'] = "PRINT";
        return view('qualitycheck.printfrom', $this->data);

    }


    public function product($id = null)
    {
        $product = \DB::table('m_products_t')->where('product_id', $id)->get();
        if ($product->isNotEmpty()) {
            $productid = $product[0]->product_id;
            return $productid;
        } else {
            return 0;
        }

    }
}