<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\qasubmitstage;
use App\qasubmitstagelines;
use App\jobcard;
use App\Http\Controllers\Controller;
use DB;
use Config;
use session;
use Yajra\DataTables\DataTables;


class qasubmitstageController extends Controller
{
    /* purpose: construct function to set values throughout the controller like model,submodel,pagemethod*/
    public function __construct()
    {
        $this->data = array(
            'pageModule' => 'qasubmitstage',
            'pageUrl' => url('qasubmitstage')
        );
        $this->model = new qasubmitstage();
        $this->data['pageMethod'] = \Request::Route()->getName();
        $this->model = new qasubmitstage();
        $this->submodel = new qasubmitstagelines();
        $this->table = 'w_qa_submitstage_trx_t';
        $this->subtable = 'w_qa_submitstage_line_t';
        $this->data['pageFormtype'] = 'ajax';
        $this->middleware('auth');
        $this->data['urlmenu'] = $this->indexs();
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

        if ($this->data['pageMethod'] == "qasubmitstage") {
            $this->data['pageurl'] = "qasubmitstage";
        } else if ($this->data['pageMethod'] == "packingqasubmitstage") {
            $this->data['pageurl'] = "packingqasubmitstage";
        } else if ($this->data['pageMethod'] == "jobcardcompletiondetails") {
            $this->data['pageurl'] = "jobcardcompletiondetails";
        } else {
            $this->data['pageurl'] = "packingjobcardcompletiondetails";
        }
        return view('qasubmitstage.table', $this->data);
    }


    /* purpose: to display material received jobcard data */
    public function getqasubmitData()
    {

        $wh = '';

        $loc = "1";
        $compy = \Session::get('companyid');
        $org = \Session::get('organization');
        $groupname = \Session::get('groupname');

        if ($_GET['pagemethod'] == "qasubmitstage") {
            $wh .= " and job_status = 'MATERIAL RECEIVED'";
            $wh .= " and m_product_groups_t.group_name='SEMI FINISHED GOODS' ";
            $op = "=";
            $status_val = "'MATERIAL RECEIVED'";
        } else if ($_GET['pagemethod'] == "packingqasubmitstage") {
            $wh .= " and job_status = 'MATERIAL RECEIVED'";
            $wh .= " and m_product_groups_t.group_name='FINISHED GOODS' ";
            $op = "=";
            $status_val = "'MATERIAL RECEIVED'";
        } else if ($_GET['pagemethod'] == "jobcardcompletiondetails") {
            $wh .= " and (job_status = 'QA SUBMITTED'  or job_status = 'STORE MOVED')";
            $wh .= " and m_product_groups_t.group_name='SEMI FINISHED GOODS' ";
            $op = "IN";
            $status_val = "('QA SUBMITTED','STORE MOVED')";
        } else {
            $wh .= " and (job_status = 'QA SUBMITTED' or job_status = 'STORE MOVED') ";
            $wh .= " and m_product_groups_t.group_name='FINISHED GOODS' ";
            $op = "IN";
            $status_val = "('QA SUBMITTED','STORE MOVED')";
        }

        $wh .= $grid_data = $this->grid_statuscheck('w_jobcard_hdr_t', 'job_date', 'job_status', $op, $status_val);

        $SQL = "SELECT
  w_jobcard_hdr_t.w_jobs_hdr_id,
   w_materialreceive_hdr_t.w_materialreceive_hdr_id,
    w_jobcard_hdr_t.job_no,
    
    w_jobcard_hdr_t.job_date,
    w_jobcard_hdr_t.job_adjusted_qty,
    w_jobcard_hdr_t.store_move_status,
    w_jobcard_hdr_t.job_status,
    w_jobcard_hdr_t.batch_no,
    w_jobcard_hdr_t.bom_process,
    w_jobcard_hdr_t.job_process,
    m_products_t.concatenated_product,
	w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id,
    m_products_t.product_code,
    w_productionplan_hdr_t.plan_no,
    w_productionplan_hdr_t.reference_no
FROM
    `w_jobcard_hdr_t`
    
    left join m_products_t ON m_products_t.product_id=w_jobcard_hdr_t.product_id
	 LEFT JOIN m_product_groups_t ON(m_products_t.product_group_id= m_product_groups_t.product_group_id)
    left join w_materialreceive_hdr_t ON w_materialreceive_hdr_t.w_jobs_hdr_id=w_jobcard_hdr_t.w_jobs_hdr_id
    left join w_qa_submitstage_trx_t ON w_qa_submitstage_trx_t.job_no=w_jobcard_hdr_t.w_jobs_hdr_id
      left join w_productionplan_hdr_t ON w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.reference_source_id
WHERE
    1 = 1 $wh order by w_jobcard_hdr_t.w_jobs_hdr_id DESC";

        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);
    }


    /* purpose: to create & update jobcard */
    public function create($id = null)
    {

        $maindate = $this->dateform('date');
        $this->data['route'] = \Request::Route()->getName();
        if (isset($_GET['pageurl'])) {
            $this->data['pageurl'] = $_GET['pageurl'];
            $this->data['pageMethod'] = $_GET['pageurl'];
        }


        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'qasubmitstage')->get();
        if (isset($_GET['src'])) {
            if ($_GET['src'] == "JOB") {

                $jobcard = jobcard::find($id);
                $this->data['pagemode'] = "edit";
                $this->data['row'] = (object) array();
                $this->data['row']->w_jobs_hdr_id = $jobcard['w_jobs_hdr_id'];
                $this->data['qtyvalid'] = "";
                $this->data['row']->from_time = "";
                $this->data['row']->to_time = "";

                $prddata1 = \DB::select("select m_products_t.subinventory_id,m_products_t.sublocator_id,m_products_t.product_subcategory_id,m_products_t.product_id,m_product_groups_t.group_name,m_products_t.expiry_days from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) where m_products_t.product_id='" . $jobcard['product_id'] . "'");

                if ($this->data['pageurl'] == "packingqasubmitstage") {

                    if ($jobcard['product_id'] != '1380' && $jobcard['product_id'] != '2066') {

                        $sfg_prd = \DB::select("select reference_source_id,product_id,bom_product_id from w_jobcard_hdr_t where w_jobs_hdr_id = '" . $jobcard['w_jobs_hdr_id'] . "'");
                        $sfg_prd_id = $sfg_prd[0]->bom_product_id;
                        $compres_prod = \DB::select("select * from m_products_t where product_id = $sfg_prd_id");
                        $com_prd = $compres_prod[0]->concatenated_product;
                        $com_prd_id = $compres_prod[0]->product_id;

                        if (str_contains($com_prd, 'COMPRESSION') || str_contains($com_prd, 'SOAP KG') || str_contains($com_prd, 'BAR KG')) {
                            $com_sfg_prd = \DB::select("select reference_source_id,product_id,bom_product_id from w_jobcard_hdr_t where product_id = '" . $com_prd_id . "'");

                            if (count($com_sfg_prd) > 0) {
                                $sfg_prd_id = $com_sfg_prd[0]->bom_product_id;
                            } else {
                                $sfg_prd_id = $com_prd_id;
                            }

                        } else {
                            $sfg_prd_id = $sfg_prd[0]->bom_product_id;
                        }

                        $date_frm_qoh = \DB::select("select * from i_qoh_detail_t where (qoh_source='PRODUCTION STORE MOVE' OR qoh_source='OPENSTOCK' OR qoh_source='BATCH CONVERSION' OR qoh_source='CONSUMABLE' OR qoh_source='SUBINVENTORY TRANSFER RECEIVE') and batch_number='" . $jobcard['batch_no'] . "' and product_id = $sfg_prd_id");

                        $qoh_mfg_date = $date_frm_qoh[0]->manufacturer_date;
                        $qoh_exp_date = $date_frm_qoh[0]->product_expire_date;
                        $this->data['row']->manufacturer_date = $qoh_mfg_date;
                        $this->data['row']->product_expire_date = $qoh_exp_date;
                    } else {
                        $manuf_date = explode("-", $jobcard['job_date']);
                        $manuf_date1 = $manuf_date[0];
                        $manuf_date2 = $manuf_date[1];
                        $this->data['row']->manufacturer_date = $manuf_date2 . "/" . $manuf_date1;


                        if ($prddata1[0]->expiry_days != "" && $prddata1[0]->expiry_days != 0 && $prddata1[0]->product_subcategory_id != 22) {
                            $curdate = date('Y-m-01');
                            $curdate = new \DateTime($curdate);
                            $curdate->modify('+' . $prddata1[0]->expiry_days . ' day');
                            $sesdate = \Session::get('p_date_format');
                            $this->data['row']->product_expire_date = date($sesdate, strtotime($curdate->format('Y-m-d')));
                        } else {
                            $this->data['row']->product_expire_date = "";
                        }
                    }

                } else {

                    $manuf_date = explode("-", $jobcard['job_date']);
                    $manuf_date1 = $manuf_date[0];
                    $manuf_date2 = $manuf_date[1];
                    $this->data['row']->manufacturer_date = $manuf_date2 . "/" . $manuf_date1;


                    if ($prddata1[0]->expiry_days != "" && $prddata1[0]->expiry_days != 0 && $prddata1[0]->product_subcategory_id != 22) {
                        $curdate = date('Y-m-01');
                        //dd($curdate);
                        $leap_year = date('L', strtotime($curdate));
                        // dd($leap_year);
                        $curdate = new \DateTime($curdate);
                        //dd($curdate);

                        if ($leap_year == 1) {
                            $prddata1[0]->expiry_days = $prddata1[0]->expiry_days - 1;
                            $curdate->modify('+' . $prddata1[0]->expiry_days . ' day');
                        } else {
                            $curdate->modify('+' . $prddata1[0]->expiry_days . ' day');
                        }
                        //dd($curdate);
                        $sesdate = \Session::get('p_date_format');
                        $this->data['row']->product_expire_date = date($sesdate, strtotime($curdate->format('Y-m-d')));
                    } else {
                        $this->data['row']->product_expire_date = "";
                    }
                }

                $prddata = \DB::select("select * from m_products_t where product_id=" . $jobcard['product_id'] . " and concatenated_product LIKE '%oil%' ");

                if ($this->data['pageurl'] == "packingqasubmitstage") {
                    $this->data['row']->quality_check = "Yes";
                } else if ($this->data['pageurl'] == "qasubmitstage" && $prddata1[0]->product_subcategory_id == 21 || $prddata1[0]->product_subcategory_id == 22 || $prddata1[0]->product_subcategory_id == 23 || $prddata1[0]->product_subcategory_id == 58) {
                    $this->data['row']->quality_check = "No";
                    $this->data['row']->store_move = "No";
                    $this->data['row']->moveto_subinventory = "Yes";
                    if (count($prddata) > 0) {
                        $this->data['qtyvalid'] = 1;
                    }
                } else if ($this->data['pageurl'] == "qasubmitstage" && $prddata1[0]->product_subcategory_id == 29 || $prddata1[0]->product_subcategory_id == 30 || $prddata1[0]->product_subcategory_id == 31 || $prddata1[0]->product_subcategory_id == 50) {
                    $this->data['row']->quality_check = "Yes";
                    $this->data['row']->store_move = "Yes";
                    $this->data['row']->moveto_subinventory = "No";
                    if (count($prddata) > 0) {
                        $this->data['qtyvalid'] = 1;
                    }
                } else {
                    $this->data['row']->quality_check = "No";
                    $this->data['row']->store_move = "No";
                    $this->data['row']->moveto_subinventory = "Yes";
                }

                $this->data['row']->reference_no = "";

                $this->data['subinventory_id'] = $this->jcustomselect('m_subinventory_t', 'subinventory_id', 'subinventory_name', '', 'and production_store="Yes"');
                $this->data['sublocator_id'] = $this->jCombo('m_sublocators_t', 'sublocator_id', 'locator_code', '');

                $qastatus = "INITIATED";
                $this->data['qa_status'] = $this->jcustomselectactive('a_lookuplines_t', 'lookup_code', 'lookup_code', $qastatus, "and lookup_type='QA_STATUS'");
                $this->data['job_no'] = $this->jCombocomp('w_jobcard_hdr_t', 'w_jobs_hdr_id', 'job_no', $jobcard['w_jobs_hdr_id']);
                $this->data['batch_no'] = $jobcard['batch_no'];

                $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $jobcard['product_id']);
                $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
                $this->data['verifier'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', \Session::get('emp_id'));
                $this->data['row']->jobcard_qty = $jobcard['job_adjusted_qty'];
                $this->data['row']->machine_id = $jobcard['machine_hdr_id'];
                $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $jobcard['uom_code_id']);
                $this->data['row']->job_date = $jobcard['job_date'];
                $this->data['row']->remarks = "";
                $this->data['row']->qatrx_date = date("Y-m-d");
                $this->data['row']->production_qty = "";

                $mtlhdr = \DB::table('w_materialreceive_hdr_t')->where('w_jobs_hdr_id', $jobcard['w_jobs_hdr_id'])->get();
                if (count($mtlhdr) > 0) {
                    $mtlhdrid = "";
                    foreach ($mtlhdr as $k => $v) {
                        $mtlhdrid .= $v->w_materialreceive_hdr_id . ",";
                    }
                    $mtlhdrid1 = rtrim($mtlhdrid, ",");

                    $mtllines = \DB::table('w_materialreceive_line_t')->whereIn('w_materialreceive_hdr_id', explode(",", $mtlhdrid1))->selectRaw('w_materialreceive_line_t.receive_qty')->select('*')->groupBy('product_id')->get();
                }
                if ($this->data['pageurl'] == "packingqasubmitstage") {
                    $bomprocess = \DB::table('m_material_bom_hdr_t')->leftjoin('m_material_bom_lines_t', 'm_material_bom_lines_t.material_bom_hdr_id', '=', 'm_material_bom_hdr_t.material_bom_hdr_id')->select('m_material_bom_lines_t.process_level', 'm_material_bom_lines_t.process_name')->where('m_material_bom_hdr_t.active', 'Yes')->where('m_material_bom_hdr_t.assembly_product_id', $jobcard['product_id'])->groupBy('m_material_bom_lines_t.process_level')->orderby('m_material_bom_lines_t.material_bom_line_id', 'asc')->get();
                    $prc = "";
                    if (count($bomprocess) > 0) {
                        $prcslvl = "";
                        foreach ($bomprocess as $k => $v) {
                            $prcslvl .= '"' . $v->process_level . '",';

                        }

                        $prc = trim($prcslvl, ",");
                    }
                    $qoh = \DB::select("select job_process,sum(qoh_trx_qty) as qoh from i_qoh_detail_t where product_id=" . $jobcard['product_id'] . " and job_id=" . $jobcard['w_jobs_hdr_id'] . " and job_process in($prc) and qoh_source='JOB STORE MOVE' group by product_id,job_process,job_id ");
                    if (count($qoh) > 0) {
                        $this->data['prcs'] = $qoh;
                    }
                    $fqoh = \DB::select("select job_process,sum(qoh_trx_qty) as qoh from i_qoh_detail_t where product_id=" . $jobcard['product_id'] . " and job_id=" . $jobcard['w_jobs_hdr_id'] . " and job_process='FINALPROCESS' and qoh_source='JOB STORE MOVE' group by product_id,job_process,job_id ");
                    if (count($fqoh) > 0) {
                        $this->data['row']->production_qty = $fqoh[0]->qoh;
                    }
                }

                $this->data['productid'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', '');
                $this->data['linedata'] = $mtllines;
                if (count($this->data['linedata']) >= 1) {

                    foreach ($this->data['linedata'] as $key => $value) {
                        $productid = $value->product_id;
                        $planlines = \DB::table('w_productionplan_lines_t')->where('parent_product', $jobcard['product_id'])->where('product_id', $productid)->where('productionplan_hdr_id', $jobcard['reference_source_id'])->get();
                        $productionqty = "";
                        if (count($planlines) > 0) {
                            $compqty = $planlines[0]->component_qty;
                            $this->data['linedata'][$key]->process_level = $planlines[0]->process_level;
                            $qoh = \DB::select("select job_process,sum(qoh_trx_qty) as qoh from i_qoh_detail_t where product_id=" . $jobcard['product_id'] . " and job_id=" . $jobcard['w_jobs_hdr_id'] . " and job_process ='" . $planlines[0]->process_level . "' and qoh_source='JOB STORE MOVE' group by product_id,job_process,job_id ");
                            if (count($qoh) > 0) {
                                $productionqty = $compqty * $qoh[0]->qoh;
                            }
                        } else {
                            $compqty = 0;
                            $this->data['linedata'][$key]->process_level = "";

                        }

                        //$this->data['linedata'][$key]->process_level=$planlines[0]->process_level;
                        $sql = \DB::select("select * from m_products_t where product_id=" . $value->product_id);
                        $this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $productid, ' and product_id=' . $productid);
                        $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id, ' and uom_code_id=' . $value->uom_code_id);
                        $this->data['linedata'][$key]->qty = $value->receive_qty;
                        $this->data['linedata'][$key]->compqty = $compqty;
                        $this->data['linedata'][$key]->production_qty = 0;
                        if ($this->data['pageurl'] == "packingqasubmitstage") {
                            $this->data['linedata'][$key]->production_qty = $productionqty;

                        }
                        $this->data['linedata'][$key]->return_qty = 0;
                        $this->data['linedata'][$key]->exceed_qty = 0;

                        $bno = ("'" . str_replace(',', "','", $value->batchnumber) . "'");
                        $bno1 = \DB::select('select * from i_qoh_detail_t where batch_number in(' . $bno . ') group by batch_number');
                        $b = "<option value=''>--Please Select--</option>";
                        foreach ($bno1 as $k1 => $v1) {
                            $b .= "<option value='" . $v1->batch_number . "'>" . $v1->batch_number . "</option>";
                        }

                        $this->data['linedata'][$key]->batchno = $b;
                        $this->data['linedata'][$key]->subinventory_id = $this->jCombo('m_subinventory_t', 'subinventory_id', 'subinventory_name', '');
                        $this->data['linedata'][$key]->locator_id = $this->jCombo('m_sublocators_t', 'sublocator_id', 'locator_code', '');
                        $comp = \Session::get('companyid');

                        if (count($sql) > 0) {

                            $qoh = \DB::Select("select sum(qoh_trx_qty) as qoh_qty from i_qoh_detail_t where product_id='" . $productid . "' and subinventory_id='" . $sql[0]->subinventory_id . "' and locator_id='" . $sql[0]->sublocator_id . "' and company_id='" . $comp . "' group by product_id ");
                            //dd(count($qoh));
                            if (count($qoh) > 0) {

                                if ($qoh[0]->qoh_qty > 0) {

                                    $this->data['linedata'][$key]->qoh = $qoh[0]->qoh_qty;

                                } else {
                                    $this->data['linedata'][$key]->qoh = "0";
                                }
                            } else {
                                $this->data['linedata'][$key]->qoh = "0";
                            }
                        } else {
                            $this->data['linedata'][$key]->qoh = "0";
                        }
                    }

                }

            } else {

                $this->data['id'] = $id;
                $table = \DB::table('w_qa_submitstage_trx_t')->where('qa_submitstage_trx_hdr_id', $id)->get();
                $lid = $table[0]->qa_submitstage_trx_hdr_id;
                $this->data['row'] = $table[0];
                $this->data['pagemode'] = "edit";
                $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $table[0]->product_id);
                $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $table[0]->uom_code_id);
                $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);
                $this->data['verifier'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]->verifier);

            }
        }

        return view('qasubmitstage.form', $this->data);
    }


    /* purpose:to save qasubmit*/

    public function save(Request $request)
    {

        // machine log inserted purpose - vignesh m
        if($_POST['pagemode']!="packingqasubmitstage"){          
            $macine_line_no = $_POST['mac_line_no'];
            $machine_ids = $_POST['machine_assigned_to'];     // array
            $running_hours = $_POST['machine_working_hrs'];   // array
            
            for ($i = 0; $i < $macine_line_no; $i++) {
                $machineData = [
                    'machine_id' => $machine_ids[$i],
                    'running_hours' => $running_hours[$i],
                    'process_dept' => "PRODUCTION",
                    'quantity' => $_POST['production_qty'],
                    'batch_number' => $_POST['batch_no'],
                    'date' => date("Y-m-d",strtotime($_POST['qatrx_date'])),
                    'product_id' => $_POST['product_id'],
                     'created_by' => \Session::get('id'),
                     'created_at' => date('Y-m-d'),
                     'organization_id' => \Session::get('organization'),
                     'location_id' => \Session::get('location'),
                     'company_id' => \Session::get('companyid'),
                     'last_updated_by' => \Session::get('id'),
                     'updated_at' => date('Y-m-d'),
                ];
                
                \DB::table('b_machine_log_t')->insert($machineData);
            }
             
        } 
        // machine log inserted purpose END

        $form = $request->all();
        $form = $request->except([
            '_token',
            'form_config',
            'form_data_json',
            'save_status',
            'submit_type',
            'choosefile',
            'existing_file',
            'machineid',
            'enable-masterdetail','mac_line_no',
        ]);
        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');
        $lines_data = $this->validatePost($form, $this->subtable, 'lines');
        
        if ($_POST['reference_no'] == "") {
            $seqno = $this->Seqno('QA', 'w_qa_submitstage_trx_t', '');
            $data['reference_no'] = $seqno;
        } else {
            $seqno = $_POST['reference_no'];
        }
        \DB::beginTransaction();
        try {

            $product_gfroup = \DB::table('m_products_t')->where('product_id', $_POST['product_id'])->get();
            $start_times = ($_POST['start_time']);
            $end_times = ($_POST['end_time']);
            sort($start_times);
            rsort($end_times);


            $date = date('Y-m-d');
            $org = \Session::get('organization');
            $loc = \Session::get('location');
            $compy = \Session::get('companyid');
            $jobid = $request->input('job_no');
            $job_no = \DB::select("SELECT `job_no` FROM `w_jobcard_hdr_t` WHERE `w_jobs_hdr_id`='$jobid'");
            $product_id = $request->input('product_id');
            $job_no = $job_no[0]->job_no;
            $job = \DB::table('w_jobcard_hdr_t')
                ->where('w_jobs_hdr_id', $jobid)
                ->first();

            if (!$job) {
                return response()->json([
                    'status' => false,
                    'message' => 'Job not found'
                ]);
            }

            $data["job_assigned_to"] = implode(",", $_POST['job_assigned_to']);
            $data["actual_hrs"] = implode(",", $_POST['actual_hrs']);
            $data["working_hrs"] = implode(",", $_POST['working_hrs']);
            $data["start_time"] = implode(",", $_POST['start_time']);
            $data["end_time"] = implode(",", $_POST['end_time']);
            $data["emp_qty"] = implode(",", $_POST['emp_qty']);
            $data["machine_assigned_to"] = implode(",", $_POST['machine_assigned_to'] ?? []);
            $data["machine_actual_hrs"] = implode(",", $_POST['machine_actual_hrs'] ?? []);
            $data["machine_start_time"] = implode(",", $_POST['machine_start_time'] ?? []);
            $data["machine_end_time"] = implode(",", $_POST['machine_end_time'] ?? []);
            $data["machine_working_hrs"] = implode(",", $_POST['machine_working_hrs'] ?? []);
            $data["machine_qty"] = implode(",", $_POST['machine_qty'] ?? []);
            $data["from_time"] = date("Y-m-d H:i:s", strtotime($_POST['from_time']));
            $data["to_time"] = date("Y-m-d H:i:s", strtotime($_POST['to_time']));
            $data["product_expire_date"] = date("Y-m-d", strtotime($_POST['product_expire_date']));
            $data["manufacturer_date"] = $_POST['manufacturer_date'];
            $data["product_id"] = $request->input('product_id');
            $data["uom_code_id"] = $request->input('uom_code_id');
            $data["total_machine_working_hrs"] = $request->input('total_machine_working_hrs') ?? '';
            $data["production_qty"] = $request->input('production_qty');
            $data["job_date"] = date("Y-m-d", strtotime($_POST['job_date']));
            $data["qatrx_date"] = date("Y-m-d", strtotime($_POST['qatrx_date']));
           
            unset($data['pagemode']);
            $id = $this->model->insertRow($data);

            /* purpose:audit log*/
            if ($_POST['qa_submitstage_trx_hdr_id'] == "") {
                $action = "create";
            } else {
                $action = "edit";
            }
            $this->auditlog($id, "qasubmitstage", $action, $data, "w_qa_submitstage_trx_t");
             
            /*end*/
            unset($lines_data['job_assigned_to']);
            unset($lines_data['processlevel']);
            unset($lines_data['process_name']);
            unset($lines_data['actual_hrs']);
            unset($lines_data['working_hrs']);
            unset($lines_data['start_time']);
            unset($lines_data['end_time']);
            unset($lines_data['emp_qty']);
            unset($lines_data['counter1']);
            unset($lines_data['machine_assigned_to']);
            unset($lines_data['machine_actual_hrs']);
            unset($lines_data['machine_start_time']);
            unset($lines_data['machine_end_time']);
            unset($lines_data['machine_working_hrs']);
            unset($lines_data['machine_qty']);

            $lid = $this->submodel->subgridSave($lines_data, $id);
            
            /* purpose: update job status in jobcard*/
            DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $_POST['job_no'])->update([
                'job_status' => 'QA SUBMITTED',
                'qasubmit_status' => 1
            ]);

            /*mail*/
            $jobno = \DB::table('w_jobcard_hdr_t')->select(
                'job_no',
                'batch_no',
                'job_date',
                'job_created_by',
                'job_adjusted_qty',
                'remarks'
            )->where('w_jobs_hdr_id', '=', $_POST['job_no'])->get();

            $main = \DB::table('w_qa_submitstage_trx_t')->select(
                'created_by',
                'job_date',
                'qatrx_date',
                'production_qty',
                'batch_no',
                'qa_status',
                'manufacturer_date',
                'product_expire_date'
            )->where('job_no', '=', $_POST['job_no'])->get();

            $user_clear = \DB::table('hr_employee_t')
                ->where('employee_id', $main[0]->created_by)
                ->first(['first_name as full_name', 'email']); // -> stdClass with full_name, email

            $prdname = \DB::table('m_products_t')->select(
                'concatenated_product',
                'product_group_id',
                'product_subcategory_id'
            )->where('product_id', '=', $_POST['product_id'])->get();

            $prd['job_no'] = $jobno[0]->job_no;
            $prd['created_date'] = $jobno[0]->job_date;
            $prd['product_name'] = $prdname[0]->concatenated_product;
            $prd['prd_grp'] = $prdname[0]->product_group_id;
            $prd['prd_sub_cat'] = $prdname[0]->product_subcategory_id;
            $prd['batch_no'] = $main[0]->batch_no;
            $prd['mfg_date'] = $main[0]->manufacturer_date;
            $prd['exp_date'] = $main[0]->product_expire_date;
            $prd['job_adjusted_qty'] = $jobno[0]->job_adjusted_qty;
            $prd['production_qty'] = $main[0]->production_qty;
            $qtyloss = $jobno[0]->job_adjusted_qty - $main[0]->production_qty;
            if ($qtyloss < 0) {
                $prd['loss_qty'] = 0;
            } else {
                $prd['loss_qty'] = $qtyloss;
            }
            $prd['completed_on'] = $main[0]->qatrx_date;
            $prd['user_clear'] = $user_clear->full_name;
            $prd['qa_status'] = $main[0]->qa_status;
            $prd['remarks'] = $jobno[0]->remarks;
            $prd['pa_status'] = $_POST['pagemode'];

            $job_num_sub = $jobno[0]->job_no;
            Session::put('job_num_sub', $job_num_sub);
            $job_email = $user_clear->email;
            Session::put('job_email', $job_email);
            $prd_name = $prdname[0]->concatenated_product;
            Session::put('prd_name', $prd_name);
            if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                Config::set('mail.username', \Session::get('user_email'));
                Config::set('mail.password', \Session::get('user_password'));

            }

            if ($prd['prd_grp'] == 1) {

                if (\Session::get('user_email') != '') {

                    if ($_POST['pagemode'] == "packingqasubmitstage") {
                        $to_mail_id = "murali_e@jrkresearch.com";
                    } else {
                        $to_mail_id = "aspire@jrkresearch.com";
                    }
                    \Mail::send('qasubmitstage.mail', $prd, function ($message) use ($to_mail_id) {

                        $message->to($to_mail_id);
                        $message->to('logistics@jrkresearch.com');
                        $message->to('operations@jrkresearch.com');
                        $message->cc('gayathri_rajagopal@jrkresearch.com');

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

                        $message->subject($job_num_sub . " - Operation Job Card details - " . $prd_name);
                    });
                }

            } else if (
                $prd['prd_sub_cat'] == 29 || $prd['prd_sub_cat'] == 30 || $prd['prd_sub_cat'] == 31 ||
                $prd['prd_sub_cat'] == 50
            ) {

                if (\Session::get('user_email') != '') {

                    if ($_POST['pagemode'] == "packingqasubmitstage") {
                        $to_mail_id = "aspire@jrkresearch.com";
                    } else {
                        $to_mail_id = "operations@jrkresearch.com";
                    }
                    \Mail::send('qasubmitstage.mail', $prd, function ($message) use ($to_mail_id) {

                        $message->to($to_mail_id);
                        $message->to('cheran_senguttuvan@jrkresearch.com');
                        $message->to('drabbas@jrkresearch.com');
                        $message->to('aruna_v@jrkresearch.com');
                        $message->to('hemashree_k@jrkresearch.com');
                        $message->to('labs@jrkresearch.com');
                        $message->to('uma_p@jrkresearch.com');
                        $message->to('purchase_rm@jrkresearch.com');
                        $message->cc('gayathri_rajagopal@jrkresearch.com');


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

                        $message->subject($job_num_sub . " - Production details - " . $prd_name);
                    });
                }

            }

            /* purpose: update qty in plan*/
            $job = \DB::select('select * from w_jobcard_hdr_t where w_jobs_hdr_id=' . $_POST['job_no']);
            if ($_POST['quality_check'] == "No") {
                $job = \DB::select('select * from w_jobcard_hdr_t where w_jobs_hdr_id=' . $_POST['job_no']);
                $plan = \DB::table('w_productionplan_hdr_t')
                    ->where('productionplan_hdr_id', $job[0]->reference_source_id)
                    ->first();

                if (!$plan) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Production plan not found'
                    ]);
                }

                if ($job[0]->store_move_qty == "0") {
                    $planjobqty = $plan->plan_qty - $job[0]->job_adjusted_qty;
                    $pendjobqty = $plan->pending_qty + $job[0]->job_adjusted_qty;

                    if ($job[0]->bom_process == 'FINALPROCESS' || $job[0]->bom_process == '' || $job[0]->bom_process == '0') {
                        \DB::table('w_productionplan_hdr_t')->where(
                            'productionplan_hdr_id',
                            $job[0]->reference_source_id
                        )->where('product_id', $_POST['product_id'])->update([
                                    'plan_qty' => $planjobqty,
                                    'pending_qty' => $pendjobqty
                                ]);
                    }
                    $plan1 = \DB::select('select * from w_productionplan_hdr_t where productionplan_hdr_id=' .
                        $job[0]->reference_source_id);

                    $planqty = $plan1[0]->plan_qty + $_POST['production_qty'];
                    $pendqty = $plan1[0]->production_qty - $planqty;
                    if ($planqty == $plan1[0]->production_qty) {
                        $planstatus = "COMPLETED";
                    } else {
                        $planstatus = "PARTIALLY COMPLETED";
                    }

                    if (count($job) > 0) {
                        if ($_POST['pagemode'] == "packingqasubmitstage") {
                            $qohdata = \DB::table('i_qoh_detail_t')->where('product_id', $_POST['product_id'])->where(
                                'job_id',
                                $_POST['job_no']
                            )->where('job_process', 'FINALPROCESS')->where('qoh_source', 'JOB STORE MOVE')->get();
                            if (count($qohdata) > 0) {
                                foreach ($qohdata as $qk => $qv) {

                                    \DB::table('i_qoh_detail_t')->where('qoh_detail_id', $qv->qoh_detail_id)->update([
                                        'qoh_source' => 'WIP Store Move',
                                        'qualitystatus' => 1
                                    ]);
                                }
                            }
                            if ($job[0]->bom_process == '0') {
                                \DB::table('w_productionplan_hdr_t')->where(
                                    'productionplan_hdr_id',
                                    $job[0]->reference_source_id
                                )->where('product_id', $_POST['product_id'])->update([
                                            'plan_qty' => $planqty,
                                            'pending_qty' => $pendqty,
                                            'plan_status' => $planstatus
                                        ]);
                            }
                        } else {

                            if ($job[0]->bom_process == 'FINALPROCESS' || $job[0]->bom_process == '') {
                                \DB::table('w_productionplan_hdr_t')->where(
                                    'productionplan_hdr_id',
                                    $job[0]->reference_source_id
                                )->where('product_id', $_POST['product_id'])->update([
                                            'plan_qty' => $planqty,
                                            'pending_qty' => $pendqty,
                                            'plan_status' => $planstatus
                                        ]);
                            }
                            if ($job[0]->bom_process == '') {
                                $planlines = \DB::table('w_productionplan_lines_t')
                                    ->where('productionplan_hdr_id', $job[0]->reference_source_id)
                                    ->where('parent_product', $product_id)
                                    ->first();

                                if (!$planlines) {
                                    return response()->json([
                                        'status' => false,
                                        'message' => 'Production plan line not found'
                                    ]);
                                }
                            } else {
                                $planlines = \DB::table('w_productionplan_lines_t')
                                    ->where('productionplan_hdr_id', $job[0]->reference_source_id)
                                    ->where('parent_product', $product_id)
                                    ->where('process_level', $job->bom_process)
                                    ->first();

                                if (!$planlines) {
                                    return response()->json([
                                        'status' => false,
                                        'message' => 'Production plan line not found'
                                    ]);
                                }

                            }

                            $qty = $plan->production_qty;
                            $prdqtyjob = $planlines->production_qty - $job[0]->job_adjusted_qty;
                            $pendingqtyjob = $planlines->pending_qty + $job[0]->job_adjusted_qty;

                            if ($job[0]->bom_process == '0' || $job[0]->bom_process == '') {
                                \DB::table('w_productionplan_lines_t')->where(
                                    'productionplan_hdr_id',
                                    $job[0]->reference_source_id
                                )->where('product_id', $_POST['product_id'])->update([
                                            'production_qty' => $prdqtyjob,
                                            'pending_qty' => $pendingqtyjob
                                        ]);
                            } else {
                                \DB::table('w_productionplan_lines_t')->where(
                                    'productionplan_hdr_id',
                                    $job[0]->reference_source_id
                                )->where('parent_product', $_POST['product_id'])->where(
                                        'process_level',
                                        $job[0]->bom_process
                                    )->update(['production_qty' => $prdqtyjob, 'pending_qty' => $pendingqtyjob]);

                            }
                            if ($job[0]->bom_process == '0' || $job[0]->bom_process == '') {
                                $planlines1 = \DB::table('w_productionplan_lines_t')->where(
                                    'productionplan_hdr_id',
                                    $job[0]->reference_source_id
                                )->where('product_id', $_POST['product_id'])->get();
                            } else {
                                $planlines1 = \DB::table('w_productionplan_lines_t')->where(
                                    'productionplan_hdr_id',
                                    $job[0]->reference_source_id
                                )->where('parent_product', $_POST['product_id'])->where(
                                        'process_level',
                                        $job[0]->bom_process
                                    )->get();

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
                                if (
                                    $job[0]->bom_process == '0' ||
                                    $job[0]->bom_process == ''
                                ) {
                                    \DB::table('w_productionplan_lines_t')->where(
                                        'productionplan_hdr_id',
                                        $job[0]->reference_source_id
                                    )->where('product_id', $_POST['product_id'])->update([
                                                'production_qty' => $prdqty,
                                                'pending_qty' => $pendingqty
                                            ]);
                                } else {
                                    \DB::table('w_productionplan_lines_t')->where(
                                        'productionplan_hdr_id',
                                        $job[0]->reference_source_id
                                    )->where('process_level', $job[0]->bom_process)->where(
                                            'parent_product',
                                            $_POST['product_id']
                                        )->update(['production_qty' => $prdqty, 'pending_qty' => $pendingqty]);
                                }
                            }
                        }
                    }
                }
            }
            /*end*/
            $result = \DB::select("SELECT * FROM w_qa_submitstage_line_t where qa_submitstage_trx_hdr_id = '$id'");

            $trsns = \DB::table('m_transaction_types_t')->where('transaction_type_name', 'RETURN QTY STORE MOVE')->get();
            foreach ($result as $k => $v) {
                if ($_POST['pagemode'] == "packingqasubmitstage") {

                    if ($v->returnqty != "" || $v->returnqty != 0) {

                        $returnqty = explode(",", $v->returnqty);
                        $subinvid = explode(",", $v->subinventoryid);
                        $locid = explode(",", $v->locatorid);
                        $btno = explode(",", $v->batchnum);
                        foreach ($returnqty as $rk => $rv) {
                            /* purpose:insert data in job close return table*/
                            $returndata['qa_submitsatge_hdr_id'] = $id;
                            $returndata['qa_submitstage_line_id'] = $v->qa_submitstage_trx_line_id;
                            $returndata['product_id'] = $v->product_id;
                            $returndata['uom_code_id'] = $v->uom_code_id;
                            $returndata['total_return_qty'] = $v->return_qty;
                            $returndata['return_qty'] = $rv;
                            $returndata['batch_no'] = $btno[$rk];
                            $returndata['subinventory_id'] = $subinvid[$rk];
                            $returndata['locator_id'] = $locid[$rk];
                            $returndata['created_by'] = \Session::get('id');
                            $returndata['created_at'] = date('Y-m-d');
                            $returndata['updated_at'] = date('Y-m-d');
                            $returndata['last_updated_by'] = \Session::get('id');
                            $returndata['company_id'] = \Session::get('companyid');
                            $returndata['location_id'] = \Session::get('location');
                            \DB::table('w_qa_materialreturn_t')->insertGetId($returndata);

                            /*end*/
                            if ($subinvid[$rk] != 0 && $subinvid[$rk] != '') {
                                /* insert data into mtl transaction tbl */
                                $mtdata['trx_source_type_id'] = $trsns[0]->transaction_source_id;
                                $mtdata['trx_action_id'] = $trsns[0]->transaction_action_id;
                                $mtdata['trx_type_id'] = $trsns[0]->transaction_type_id;
                                $mtdata['trx_source_hdr_id'] = $id;
                                $mtdata['trx_source_line_id'] = '';
                                $mtdata['line_number'] = $k + 1;
                                $mtdata['trx_reference'] = "Return Store Move";
                                $mtdata['product_id'] = $v->product_id;
                                $mtdata['trx_qty'] = $rv;
                                $mtdata['trx_uom'] = $v->uom_code_id;
                                $mtdata['trx_date'] = date('Y-m-d');
                                $mtdata['created_by'] = \Session::get('id');
                                $mtdata['created_at'] = date('Y-m-d');
                                $mtdata['updated_at'] = date('Y-m-d');
                                $mtdata['last_updated_by'] = \Session::get('id');
                                $mtdata['subinventory_id'] = $subinvid[$rk];
                                $mtdata['locator_id'] = $locid[$rk];
                                $mtdata['organization_id'] = \Session::get('organization');
                                $mtdata['company_id'] = \Session::get('companyid');
                                $mtdata['location_id'] = \Session::get('location');
                                $mtlid = \DB::table('m_material_trx_t')->insertGetId($mtdata);

                                /* insert data into qoh detail tbl */

                                $qohdata['product_id'] = $v->product_id;
                                $qohdata['qoh_trx_qty'] = $rv;
                                $qohdata['qoh_uom_code_id'] = $v->uom_code_id;
                                $qohdata['create_trx_id'] = $mtlid;
                                $qohdata['subinventory_id'] = $subinvid[$rk];
                                $qohdata['locator_id'] = $locid[$rk];
                                ;
                                $qohdata['batch_number'] = trim($btno[$rk]);
                                $qohdata['job_id'] = $request->input('job_no');
                                $qohdata['qoh_source'] = "Return Store Move";
                                $qohdata['organization_id'] = \Session::get('organization');
                                $qohdata['company_id'] = \Session::get('companyid');
                                $qohdata['location_id'] = \Session::get('location');
                                $qohdata['created_by'] = \Session::get('id');
                                $qohdata['created_at'] = date('Y-m-d');
                                $qohdata['updated_at'] = date('Y-m-d');
                                $qohdata['qoh_trx_date'] = date('Y-m-d');
                                $qohdata['qoh_source_id'] = $id;
                                $qohdata['last_updated_by'] = \Session::get('id');
                                $qohid = \DB::table('i_qoh_detail_t')->insertGetId($qohdata);
                            }
                        }
                    }
                } else {

                    if ($v->subinventory_id != "" && ($v->return_qty != "" || $v->return_qty != 0)) {

                        /* insert data into mtl transaction tbl */
                        $mtdata['trx_source_type_id'] = $trsns[0]->transaction_source_id;
                        $mtdata['trx_action_id'] = $trsns[0]->transaction_action_id;
                        $mtdata['trx_type_id'] = $trsns[0]->transaction_type_id;
                        $mtdata['trx_source_hdr_id'] = $id;
                        $mtdata['trx_source_line_id'] = '';
                        $mtdata['line_number'] = $k + 1;
                        $mtdata['trx_reference'] = "Return Store Move";
                        $mtdata['product_id'] = $v->product_id;
                        $mtdata['trx_qty'] = $v->return_qty;
                        $mtdata['trx_uom'] = $v->uom_code_id;
                        $mtdata['trx_date'] = date('Y-m-d');
                        $mtdata['created_by'] = \Session::get('id');
                        $mtdata['created_at'] = date('Y-m-d');
                        $mtdata['updated_at'] = date('Y-m-d');
                        $mtdata['last_updated_by'] = \Session::get('id');
                        $mtdata['subinventory_id'] = $v->subinventory_id;
                        $mtdata['locator_id'] = $v->sublocator_id;
                        $mtdata['organization_id'] = \Session::get('organization');
                        $mtdata['company_id'] = \Session::get('companyid');
                        $mtdata['location_id'] = \Session::get('location');
                        $mtlid = \DB::table('m_material_trx_t')->insertGetId($mtdata);

                        /* insert data into qoh detail tbl */

                        $qohdata['product_id'] = $v->product_id;
                        $qohdata['qoh_trx_qty'] = $v->return_qty;
                        $qohdata['qoh_uom_code_id'] = $v->uom_code_id;
                        $qohdata['create_trx_id'] = $mtlid;
                        $qohdata['subinventory_id'] = $v->subinventory_id;
                        $qohdata['locator_id'] = $v->sublocator_id;
                        $qohdata['batch_number'] = trim($v->batchno);
                        $qohdata['job_id'] = $request->input('job_no');
                        $qohdata['qoh_source'] = "Return Store Move";
                        $qohdata['organization_id'] = \Session::get('organization');
                        $qohdata['company_id'] = \Session::get('companyid');
                        $qohdata['location_id'] = \Session::get('location');
                        $qohdata['created_by'] = \Session::get('id');
                        $qohdata['created_at'] = date('Y-m-d');
                        $qohdata['updated_at'] = date('Y-m-d');
                        $qohdata['qoh_trx_date'] = date('Y-m-d');
                        $qohdata['qoh_source_id'] = $id;
                        $qohdata['last_updated_by'] = \Session::get('id');
                        $qohid = \DB::table('i_qoh_detail_t')->insertGetId($qohdata);
                    }
                }
            }

            /* purpose move assembly qoh to qoh table*/
            $trsns2 = \DB::table('m_transaction_types_t')->where('transaction_type_name', 'STORE MOVE')->get();
            if ($_POST['pagemode'] != 'packingqasubmitstage') {
                if ($_POST['moveto_subinventory'] == 'Yes') {
                    /* insert data into mtl transaction tbl */
                    $mtdata['trx_source_type_id'] = $trsns2[0]->transaction_source_id;
                    $mtdata['trx_action_id'] = $trsns2[0]->transaction_action_id;
                    $mtdata['trx_type_id'] = $trsns2[0]->transaction_type_id;
                    $mtdata['trx_source_hdr_id'] = $id;
                    $mtdata['trx_source_line_id'] = '';
                    $mtdata['line_number'] = $k + 1;
                    $mtdata['product_id'] = $_POST['product_id'];
                    $mtdata['trx_qty'] = $_POST['production_qty'];
                    $mtdata['trx_uom'] = $_POST['uom_code_id'];
                    $mtdata['trx_reference'] = "WIP Store Move";
                    $mtdata['trx_date'] = date('Y-m-d');
                    $mtdata['created_by'] = \Session::get('id');
                    $mtdata['created_at'] = date('Y-m-d');
                    $mtdata['updated_at'] = date('Y-m-d');
                    $mtdata['last_updated_by'] = \Session::get('id');
                    $mtdata['subinventory_id'] = $_POST['subinventory_id'];
                    $mtdata['locator_id'] = $_POST['sublocator_id'];
                    $mtdata['organization_id'] = \Session::get('organization');
                    $mtdata['location_id'] = \Session::get('location');
                    $mtdata['company_id'] = \Session::get('companyid');
                    $mtlid = \DB::table('m_material_trx_t')->insertGetId($mtdata);


                    /* insert data into qoh detail tbl */

                    $qohdata['product_id'] = $_POST['product_id'];
                    $qohdata['qoh_trx_qty'] = $_POST['production_qty'];
                    $qohdata['qoh_uom_code_id'] = $_POST['uom_code_id'];
                    $qohdata['create_trx_id'] = $mtlid;
                    $qohdata['subinventory_id'] = $_POST['subinventory_id'];
                    $qohdata['locator_id'] = $_POST['sublocator_id'];
                    $qohdata['cost'] = "0";//round($overallcost / $_POST['production_qty'], 2);
                    $qohdata['qoh_source'] = "WIP Store Move";
                    $qohdata['qoh_trx_date'] = date('Y-m-d');
                    $qohdata['qoh_source_id'] = $jobid;
                    $qohdata['batch_number'] = trim($_POST['batch_no']);
                    $qohdata['job_id'] = $request->input('job_no');
                    $qohdata['job_process'] = $job[0]->bom_process;

                    $group = \DB::select("select m_products_t.product_group_id,m_product_groups_t.group_name from m_products_t left
        join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) where
        product_id='" . $_POST['product_id'] . "'");
                    if ($group[0]->group_name == "SEMI FINISHED GOODS") {
                        $manuf_date = explode("-", $_POST['job_date']);
                        $manuf_date1 = $manuf_date[0];
                        $manuf_date2 = $manuf_date[1];
                        $qohdata['manufacturer_date'] = $manuf_date2 . "/" . $manuf_date1;
                    }
                    $qohdata['organization_id'] = \Session::get('organization');
                    $qohdata['company_id'] = \Session::get('companyid');
                    $qohdata['location_id'] = \Session::get('location');
                    $qohdata['created_by'] = \Session::get('id');
                    $qohdata['created_at'] = date('Y-m-d');
                    $qohdata['updated_at'] = date('Y-m-d');
                    $qohdata['last_updated_by'] = \Session::get('id');
                    if ($_POST['quality_check'] == 'No') {
                        $qohdata['qualitystatus'] = 1;
                        $qohdata['qualitytype'] = "JOBCARD STORE MOVE";
                    } else {
                        $qohdata['qualitystatus'] = 0;
                    }
                    $qohid = \DB::table('i_qoh_detail_t')->insertGetId($qohdata);
                }
            }
            /*end*/

            $notifcation = 'Jobcard completion ' . $job[0]->job_no . ' for quality check';
            $send_notification = $this->sendPopUpHomeNoty($id, "JOBCARD COMPLETION", $notifcation, 'qasubmitstage');
            \DB::table('notifications_t')->where('reference_source_id', '')->where(
                'reference_source',
                '=',
                'MATERIALRECEIVE'
            )->update(['read/unread' => 'read']);

            $mtlhdr = \DB::table('w_materialissue_hdr_t')->where('w_jobs_hdr_id', $_POST['job_no'])->get();
            if (count($mtlhdr) > 0) {
                $mtlhdrid = "";
                foreach ($mtlhdr as $k => $v) {
                    $mtlhdrid .= $v->w_materialissue_hdr_id . ",";
                }
                $mtlhdrid1 = rtrim($mtlhdrid, ",");

                $mtllines = \DB::table('w_materialreceive_line_t')->whereIn('reference_source_hdr_id', explode(
                    ",",
                    $mtlhdrid1
                ))->select('*')->groupBy('product_id')->get();

                \DB::table('notifications_t')->where(
                    'reference_source_id',
                    $mtllines[0]->w_materialreceive_hdr_id
                )->where('reference_source', '=', 'JOBCARD
        COMPLETION')->update(['read/unread' => 'read']);
            }

            \DB::commit();


            return response()->json(array(
                'status' => 'success',
                'message' => 'Saved Successfully',
                'id' => $id,
                'reference_no' => $seqno
            ));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            dd($dbCode);
            \DB::rollback();

            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }


    /* purpose:to view qasubmit*/
    public function show(qasubmitstage $qasubmitstage, $id = null)
    {

        if (isset($id)) {
            /*$data=DB::table('w_qa_submitstage_trx_t')->leftjoin('m_products_t','m_products_t.product_id','=','w_qa_submitstage_trx_t.product_id')->leftjoin('m_uom_codes_t','m_uom_codes_t.uom_code_id','=','w_qa_submitstage_trx_t.uom_code_id')->leftjoin('w_jobcard_hdr_t','w_jobcard_hdr_t.w_jobs_hdr_id','=','w_qa_submitstage_trx_t.job_no')->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','w_qa_submitstage_trx_t.verifier')
                ->leftjoin('m_subinventory_t','m_subinventory_t.subinventory_id','=','w_qa_submitstage_trx_t.subinventory_id')->leftjoin('m_sublocators_t','m_sublocators_t.sublocator_id','=','w_qa_submitstage_trx_t.sublocator_id')->where('w_qa_submitstage_trx_t.qa_submitstage_trx_hdr_id',$id)->get();*/

            $data = \DB::select(" select *,w_qa_submitstage_trx_t.job_assigned_to as assigned_job,w_qa_submitstage_trx_t.machine_assigned_to as assigned_machine,w_qa_submitstage_trx_t.working_hrs as emp_wrk_hrs from `w_qa_submitstage_trx_t` left join `m_products_t` on `m_products_t`.`product_id` = `w_qa_submitstage_trx_t`.`product_id` left join `m_uom_codes_t` on `m_uom_codes_t`.`uom_code_id` = `w_qa_submitstage_trx_t`.`uom_code_id` left join `w_jobcard_hdr_t` on `w_jobcard_hdr_t`.`w_jobs_hdr_id` = `w_qa_submitstage_trx_t`.`job_no` left join `hr_employee_t` on `hr_employee_t`.`employee_id` = `w_qa_submitstage_trx_t`.`verifier` left join `m_subinventory_t` on `m_subinventory_t`.`subinventory_id` = `w_qa_submitstage_trx_t`.`subinventory_id` left join `m_sublocators_t` on `m_sublocators_t`.`sublocator_id` = `w_qa_submitstage_trx_t`.`sublocator_id` where `w_qa_submitstage_trx_t`.`qa_submitstage_trx_hdr_id` = $id");

            $assignedIds = explode(',', $data[0]->assigned_job);
            $workingHoursArr = explode(',', $data[0]->emp_wrk_hrs);

            $employees = DB::table('hr_employee_t')
                ->whereIn('employee_id', $assignedIds)
                ->pluck('first_name', 'employee_id')
                ->toArray();

            $data[0]->assigned_names = implode(', ', $employees);

            // Map employee with working hours
            $employeeMinutes = [];

            foreach ($assignedIds as $index => $empId) {

                $time = $workingHoursArr[$index] ?? '0';

                // Split hours and minutes
                $parts = explode('.', $time);
                $hrs = isset($parts[0]) ? (int)$parts[0] : 0;
                $mins = isset($parts[1]) ? (int)$parts[1] : 0;

                // Convert to total minutes
                $totalMinutes = ($hrs * 60) + $mins;

                if (!isset($employeeMinutes[$empId])) {
                    $employeeMinutes[$empId] = 0;
                }

                $employeeMinutes[$empId] += $totalMinutes;
            }

            $employeeDetails = [];

            foreach ($employeeMinutes as $empId => $minutes) {

                $hrs = floor($minutes / 60);
                $mins = $minutes % 60;

                $formattedTime = $hrs . '.' . str_pad($mins, 2, '0', STR_PAD_LEFT);

                $employeeDetails[] = [
                    'name' => $employees[$empId] ?? 'Unknown',
                    'working_hours' => $formattedTime
                ];
            }

            // Pass to blade
            $data[0]->employee_details = $employeeDetails;

            $assignedMachines = explode(',', $data[0]->assigned_machine);

            $machines = DB::table('w_machine_hdr_t')
                ->whereIn('machine_hdr_id', $assignedMachines)
                ->pluck('machine_name')
                ->toArray();

            $data[0]->assigned_machines = implode(', ', $machines);


            //dd($data);
            $this->data['header'] = $data[0];
            $this->data['pageurl'] = $_GET['pageurl'];
            //$linesdata = \DB::table('w_qa_submitstage_line_t')->leftjoin('m_products_t','m_products_t.product_id','=','w_qa_submitstage_line_t.product_id')->leftjoin('m_uom_codes_t','m_uom_codes_t.uom_code_id','=','m_uom_codes_t.uom_code_id') ->leftjoin('m_subinventory_t','m_subinventory_t.subinventory_id','=','w_qa_submitstage_line_t.subinventoryid')->leftjoin('m_sublocators_t','m_sublocators_t.sublocator_id','=','w_qa_submitstage_line_t.locatorid')->where('w_qa_submitstage_line_t.qa_submitstage_trx_hdr_id',$id)->groupBy('w_qa_submitstage_line_t.qa_submitstage_trx_line_id')->get(); 
            $linesdata = \DB::select("SELECT
    *,
    w_qa_submitstage_line_t.comments as comment
FROM
    `w_qa_submitstage_line_t`
LEFT JOIN `m_products_t` ON `m_products_t`.`product_id` = `w_qa_submitstage_line_t`.`product_id`
LEFT JOIN `m_uom_codes_t` ON `m_uom_codes_t`.`uom_code_id` = `m_uom_codes_t`.`uom_code_id`
LEFT JOIN `m_subinventory_t` ON `m_subinventory_t`.`subinventory_id` = `w_qa_submitstage_line_t`.`subinventoryid`
LEFT JOIN `m_sublocators_t` ON `m_sublocators_t`.`sublocator_id` = `w_qa_submitstage_line_t`.`locatorid`
WHERE
    `w_qa_submitstage_line_t`.`qa_submitstage_trx_hdr_id` = $id
GROUP BY
    `w_qa_submitstage_line_t`.`qa_submitstage_trx_line_id`");
            $this->data['linesdata'] = $linesdata;
            //dd($linesdata);
            return view('qasubmitstage.view', $this->data);
        }
    }
    /*end*/

    /*purpose:to show employee hour details based on machine capacity*/
    public function employeedetails($id = null)
    {

        $job = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $id)->get();
        $jobprcs = \DB::table('w_jobcard_process_details_t')->where('job_id', $id)->get();

        $jobassid = explode(",", $job[0]->job_assigned_to);
        $jobhr = $job[0]->hour;

        if ($_GET['pgurl'] == "packingqasubmitstage") {
            $html = "<table class='table table-bordered emp_table'>";
            $html .= "<thead class='table-light'><th >S.No</th><th >Employee Name</th><th >Actual Hours</th><th >Start Time</th><th >End Time</th><th >Working Hours</th>";
            $html .= "<th>Process Level</th><th>Process Name</th><th>Qty</th><th></th></thead><tbody  class='emp_lines_body'>";
            $ln = 0;
            foreach ($jobprcs as $key => $val) {
                $pkjobassid = explode(",", $val->jobassigned_to);
                $pl = $val->process_level;
                $pn = $val->process_name;
                $ah = explode(",", $val->actual_hrs);
                $wh = explode(",", $val->working_hrs);
                $eqty = explode(",", $val->emp_qty);
                $st = explode(",", $val->start_time);
                $et = explode(",", $val->end_time);

                foreach ($pkjobassid as $pk => $pv) {
                    $ln++;

                    $sel = $this->jcustomselect('hr_employee_t', 'employee_id', 'first_name', $pv, ' and group_type=10');
                    $plevel = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', $pl, 'and lookup_type="PROCESS_LEVEL"');
                    $pname = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', $pn, 'and lookup_type="PROCESS_NAME"');

                    $res = \DB::table('hr_employee_t')->select('first_name')->where('employee_id', $pv)->get();
                    $html .= "<tr class='emp_clone rcopy1 clone1'>";


                    $html .= "<td ><input type='text' name='line_no' class='form-control line_no' value=" . $ln . "></td>";
                    $html .= "<td style='pointer-events:none;'>" . "<input type='hidden' class='form-control w_jobs_hdr_id" . $pk . "' value='" . $id . "'>";
                    $html .= "<div class='form-group'>
	<select name='job_assigned_to[]' class='form-control select2 job_assigned_to'>" . $sel . "</select>
			</div>
			</td>";
                    $html .= "<td><input type='text' name='actual_hrs[]' class='actual_hrs form-control actual_hrs" . $pk . "' value='" . $ah[$pk] . "' readonly></td>";
                    $html .= "<td style='pointer-events:none;'><input type='text' name='start_time[]' class=' start_time form-control start_time" . $pk . "' value='" . $st[$pk] . "' style='border:1px solid #07234e;' readonly></td>";
                    $html .= "<td style='pointer-events:none;'><input type='text' name='end_time[]' class='end_time form-control " . $pk . "' value='" . $et[$pk] . "' style='border:1px solid #07234e;' readonly></td>";
                    $html .= "<td ><input type='text' name='working_hrs[]' class='working_hrs form-control working_hrs" . $pk . "' value='" . $wh[$pk] . "' readonly></td>";

                    $html .= "<td style='pointer-events:none;'><div class='form-group'>
	<select name='processlevel[]' class='form-control processlevel processlevel" . $pk . "'>" . $plevel . "</select>
			</div>
			</td>";
                    $html .= "<td style='pointer-events:none;'><div class='form-group'>
	<select name='process_name[]' class='form-control process_name process_name" . $pk . "'>" . $pname . "</select>
			</div>
			</td>";
                    $html .= "<td ><input type='text' name='emp_qty[]' class='emp_qty form-control emp_qty" . $pk . "' value='" . $eqty[$pk] . "' readonly></td>";
                    $html .= "<td style='pointer-events:none;' class='text-center'>
        <button type='button' class='btn btn-sm btn-danger removerow_empdel'>
          <i class='fas fa-minus-circle'></i>
        </button>
      </td>";
                    $html .= "</tr>";
                }
            }
            $html .= "</tbody></table>";

        } else {

            $html = "<table class='table table-bordered emp_table'>";
            $html .= "<thead class='table-light'><th >S.No</th><th >Employee Name</th><th >Actual Hours</th><th >Start Time</th><th >End Time</th><th >Working Hours</th>";
            $html .= "<tbody  class='emp_lines_body'>";
            foreach ($jobassid as $key => $val) {

                $sel = $this->jcustomselect('hr_employee_t', 'employee_id', 'first_name', $val, ' and group_type in (8,9)');
                $plevel = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', '', 'and lookup_type="PROCESS_LEVEL"');
                $pname = $this->jcustomselect('a_lookuplines_t', 'lookup_code', 'lookup_code', '', 'and lookup_type="PROCESS_NAME"');

                $res = \DB::table('hr_employee_t')->select('first_name')->where('employee_id', $val)->get();
                $html .= "<tr class='emp_clone rcopy1 clone1'>";


                $html .= "<td ><input type='text' name='line_no' class='form-control line_no' value=" . ($key + 1) . "></td>";
                $html .= "<td >" . "<input type='hidden' class='form-control w_jobs_hdr_id" . $key . "' value='" . $id . "'>";
                $html .= "<div class='form-group'>
	<select name='job_assigned_to[]' class='form-control select2 job_assigned_to'>" . $sel . "</select>
			</div>
			</td>";
                $html .= "<td><input type='text' name='actual_hrs[]' class='actual_hrs form-control actual_hrs" . $key . "' value='" . $jobhr . "' required='required'></td>";
                $html .= "<td><input type='text' name='start_time[]' class=' start_time form-control start_time" . $key . "' value='' style='border:1px solid #07234e;'></td>";
                $html .= "<td ><input type='text' name='end_time[]' class='end_time  form-control end_time" . $key . "' value='' style='border:1px solid #07234e;'></td>";
                $html .= "<td ><input type='text' name='working_hrs[]' class='working_hrs form-control working_hrs" . $key . "' value=''><input type='hidden' name='emp_qty[]' class='emp_qty form-control emp_qty" . $key . "' value=''></td>";

                $html .= "<td class='text-center'>
        <button type='button' class='btn btn-sm btn-danger removerow_empdel'>
          <i class='fas fa-minus-circle'></i>
        </button>
      </td>";
                $html .= "</tr>";
            }
            $html .= "</tbody></table>";
        }
        return $html;
    }


    /* purpose: to get material issue subinventory details*/
    public function prdmtlsubinventorydetails($id = null)
    {
        $product = $_GET['product'];
        $jobid = $_GET['jobid'];
        $sql = \DB::table('w_materialissue_hdr_t')->leftjoin('w_materialissue_line_t', 'w_materialissue_line_t.w_materialissue_hdr_id', '=', 'w_materialissue_hdr_t.w_materialissue_hdr_id')->select('w_materialissue_hdr_t.w_jobs_hdr_id', 'w_materialissue_line_t.*')->where('w_materialissue_line_t.product_id', $product)->where('w_materialissue_hdr_t.w_jobs_hdr_id', $jobid)->get();

        $html = "";

        $comp = \Session::get('companyid');
        $sub = $this->jcustomselect('m_subinventory_t', 'subinventory_id', 'subinventory_name', '', 'and subinventory_id in(5,6)');
        $prdbtdata = 0;
        if (count($sql) > 0) {
            $prdbtdata = 1;
        } else if (count($sql) <= 0) {
            $prdbtdata = 1;
        }

        if (count($sql) <= 0) {
            $job = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $jobid)->get();
            $refid = $job[0]->reference_source_id;
            $jobref = \DB::table('w_jobcard_hdr_t')->where('reference_source_id', $refid)->orderBy('w_jobs_hdr_id', 'asc')->get();

            $sql = \DB::table('w_materialissue_hdr_t')->leftjoin('w_materialissue_line_t', 'w_materialissue_line_t.w_materialissue_hdr_id', '=', 'w_materialissue_hdr_t.w_materialissue_hdr_id')->select('w_materialissue_hdr_t.w_jobs_hdr_id', 'w_materialissue_line_t.*')->where('w_materialissue_line_t.product_id', $product)->where('w_jobs_hdr_id', $jobref[0]->w_jobs_hdr_id)->get();

        }
        if ($prdbtdata == 1) {
            $batchno = explode(',', $sql[0]->batchnumber);
            $bno = "<option value=''>--Please Select--</option>";
            foreach ($batchno as $k => $v) {
                $bno .= "<option value='" . $v . "'>$v</option>";
            }
            $subinv = implode(',', array_unique(explode(',', $sql[0]->subinventory_id)));
            $subloc = implode(',', array_unique(explode(',', $sql[0]->locator_id)));



            $loc = $this->jcustomselect('m_sublocators_t', 'sublocator_id', 'locator_code', '', '');

            $html .= "<table class='table table-bordered clone_table_pop'>";
            $html .= "<thead class='table-primary'><th >S.No</th><th >Lot Number</th><th >Subinventory</th><th >Locator</th><th>Issued Qty</th><th >Qty</th><th></th></thead><tbody  class='subinv_class_body'>";

            $html .= "<tr class='subinv_clone rcopy1'>";
            $html .= "<td ><input type='hidden' class='product' value=" . $_GET['product'] . " ><input type='hidden' class='index1' value=" . $_GET['index'] . " ><input type='text' class='form-control linno' value='1' ></td>
				<td><select class='form-control batchnumber'>" . $bno . "</select></td>";
            $html .= "<td>
	<select class='form-control subinventoryid' >" . $sub . "</select>
			
			</td>";
            $html .= "<td>
	<select class='form-control locatorid' >" . $loc . "</select>
			
			</td>";

            $html .= "<td ><input type='text' class='form-control mtlissueqty' value='' readonly></td> <td ><input type='text' class='form-control mtlqty' value='' ></td> <td><button type='button' class='btn btn-sm btn-danger remove-rowpop'>
            <i class='fas fa-minus-circle'></i>
          </button>
                    <input type='hidden'>
                </td>";
            $html .= "</tr>";
            $html .= "</tbody></table>";
        } else {
            $html .= "Batch Not Available";
        }

        return $html;
    }
    /*end*/
    public function mtlbatchdetails($prdid = null)
    {
        $product = $prdid;
        $jobid = $_GET['jobid'];
        $batch = $_GET['batch'];
        $issueqty = "";

        $sql = \DB::table('w_materialissue_hdr_t')->leftjoin('w_materialissue_line_t', 'w_materialissue_line_t.w_materialissue_hdr_id', '=', 'w_materialissue_hdr_t.w_materialissue_hdr_id')->select('w_materialissue_hdr_t.w_jobs_hdr_id', 'w_materialissue_line_t.*')->where('w_materialissue_line_t.product_id', $product)->where('w_materialissue_hdr_t.w_jobs_hdr_id', $jobid)->where('batchnumber', 'LIKE', '%' . $batch . '%')->get();
        if (count($sql) <= 0) {
            $job = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $jobid)->get();
            $refid = $job[0]->reference_source_id;
            $jobref = \DB::table('w_jobcard_hdr_t')->where('reference_source_id', $refid)->orderBy('w_jobs_hdr_id', 'asc')->get();

            $sql = \DB::table('w_materialissue_hdr_t')->leftjoin('w_materialissue_line_t', 'w_materialissue_line_t.w_materialissue_hdr_id', '=', 'w_materialissue_hdr_t.w_materialissue_hdr_id')->select('w_materialissue_hdr_t.w_jobs_hdr_id', 'w_materialissue_line_t.*')->where('w_materialissue_line_t.product_id', $product)->where('w_materialissue_hdr_t.w_jobs_hdr_id', $jobref[0]->w_jobs_hdr_id)->where('batchnumber', 'LIKE', '%' . $batch . '%')->get();
        }
        if (count($sql) > 0) {
            $batchno = explode(',', $sql[0]->batchnumber);
            $issuqty = explode(',', $sql[0]->issueqty);
            foreach ($batchno as $k => $v) {
                if ($v == $batch) {
                    $issueqty = $issuqty[$k];
                }

            }
        }

        return $issueqty;
    }

    /* purpose:to show machine hour details based on machine capacity*/
    public function getallmachinedetails($id = null)
    {

        $machine_job = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $id)->get();

        $machine_jobassid = explode(",", $machine_job[0]->machine_hdr_id);

        $machine_jobhr = $machine_job[0]->hour;

        if ($_GET['pgurl'] != "packingqasubmitstage") {

            $html = "<table class='table table-bordered mac_class fixed_table'>";
            $html .= "<thead class='table-light'><th >S.No</th><th >Machine Name</th><th >Actual Hours</th><th >Start Time</th><th >End Time</th><th >Working Hours</th>";
            $html .= "<tbody  class='mac_class_body'>";
            foreach ($machine_jobassid as $key => $val) {


                $sel = $this->jcustomselectcomp('w_machine_hdr_t', 'machine_hdr_id', 'machine_name', $val, ' and company_id=1');

                $res = \DB::table('w_machine_hdr_t')->select('machine_name')->where('machine_hdr_id', $val)->get();
                $html .= "<tr class='mac_clone rcopy1 clone1'>";


                $html .= "<td ><input type='text' name='mac_line_no' class='form-control mac_line_no' value=" . ($key + 1) . "></td>";
                $html .= "<td >" . "<input type='hidden' class='form-control w_jobs_hdr_id" . $key . "' value='" . $id . "'>";
                $html .= "<div class='form-group'>
	<select name='machine_assigned_to[]' class='form-control select2 machine_assigned_to'>" . $sel . "</select>
			</div>
			</td>";
                $html .= "<td><input type='text' name='machine_actual_hrs[]' class='machine_actual_hrs form-control machine_actual_hrs" . $key . "' value='" . $machine_jobhr . "' required='required'></td>";
                $html .= "<td><input type='text' name='machine_start_time[]' class=' machine_start_time form-control machine_start_time" . $key . "' value='' style='border:1px solid #07234e;'></td>";
                $html .= "<td ><input type='text' name='machine_end_time[]' class='machine_end_time  form-control machine_end_time" . $key . "' value='' style='border:1px solid #07234e;'></td>";
                $html .= "<td ><input type='text' name='machine_working_hrs[]' class='machine_working_hrs form-control machine_working_hrs" . $key . "' value=''><input type='hidden' name='machine_qty[]' class='machine_qty form-control machine_qty" . $key . "' value=''></td>";

                $html .= "<td class='text-center'>
        <button type='button' class='btn btn-sm btn-danger removerow_macdet'>
          <i class='fas fa-minus-circle'></i>
        </button>
      </td>";
                $html .= "</tr>";
            }
            $html .= "</tbody></table>";
        }
        return $html;
    }
    /*end*/


}