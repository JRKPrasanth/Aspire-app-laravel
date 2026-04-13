<?php

namespace App\Http\Controllers;

use App\materialissue;
use App\materialissueline;
use Illuminate\Http\Request;
use Redirect;
use Config;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class materialissueController extends Controller
{
    /* purpose: construct function to set values throughout the controller like model,submodel,pagemethod*/
    public $module = "materialissue";

    public function __construct()
    {
        $this->data = array();
        $this->model = new materialissue;
        $this->submodel = new materialissueline;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageModule'] = 'materialissues';
        $this->table = "w_materialissue_hdr_t";
        $this->subtable = "w_materialissue_line_t";

        if ($this->data['pageMethod'] == "materialrequest" || $this->data['pageMethod'] == "materialrequestview") {
            $this->data['pageurl'] = "materialrequest";
            $this->data['source'] = 'MATERIALREQUEST';
            $this->data['url'] = 'materialrequest';
            $this->data['type'] = '';
            $this->data['status'] = 'OPEN';
        } else if ($this->data['pageMethod'] == "materialissues") {
            $this->data['pageurl'] = "materialissues";
            $this->data['url'] = 'materialissues';
            $this->data['source'] = 'MATERIALISSUE';
            $this->data['type'] = 'sfg';
            $this->data['status'] = '';
        } else if ($this->data['pageMethod'] == "packingmaterialissues") {
            $this->data['pageurl'] = "packingmaterialissues";
            $this->data['url'] = 'packingmaterialissues';
            $this->data['source'] = 'PACKINGMATERIALISSUE';
            $this->data['type'] = 'fg';
            $this->data['status'] = '';
        } else if ($this->data['pageMethod'] == "packingmtlissuedetails") {
            $this->data['pageurl'] = "packingmtlissuedetails";
            $this->data['url'] = 'packingmtlissuedetails';
            $this->data['source'] = 'PACKINGMATERIALISSUEDETAILS';
            $this->data['status'] = 'packingmtlissuedetails';
            $this->data['type'] = 'fg';
        } else {
            $this->data['pageurl'] = "mtlissuedetails";
            $this->data['url'] = 'mtlissuedetails';
            $this->data['source'] = 'MATERIALISSUEDETAILS';
            $this->data['status'] = 'mtlissuedetails';
            $this->data['type'] = 'sfg';
        }
    }
    /*end*/
    /*Index Function*/
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

        return view('materialIssue.table', $this->data);
    }


    /*Material Issue Data*/
    public function getmaterialissueData()
    {
        $wh = '';

        $wh .= " and w_jobcard_hdr_t.qasubmit_status='0'";


        $loc = "1";
        $compy = \Session::get('companyid');
        $org = \Session::get('organization');
        $groupname = \Session::get('groupname');


        if (isset($_GET['type'])) {
            if ($_GET['type'] == 'fg') {
                $wh .= " and m_product_groups_t.group_name='FINISHED GOODS'";
            } else {
                $wh .= " and m_product_groups_t.group_name='SEMI FINISHED GOODS' ";
            }
        }

        if (isset($_GET['status'])) {
            if ($_GET['status'] != 'mtlissuedetails' && $_GET['status'] != 'packingmtlissuedetails') {
                $wh .= " and (w_jobcard_hdr_t.job_status='OPEN' or w_jobcard_hdr_t.job_status='MATERIAL ISSUED' or w_jobcard_hdr_t.job_status='JOBCARD REWORK')";
                $op = "IN";
                $status_val = "('OPEN','MATERIAL ISSUED','JOBCARD REWORK')";
                $wh .= $grid_data = $this->grid_statuscheck('w_jobcard_hdr_t', 'job_date', 'job_status', $op, $status_val);
            } else {
                $wh .= $grid_data = $this->grid_check('w_jobcard_hdr_t', 'job_date');
            }
        }


        $sql = "SELECT
    w_jobcard_hdr_t.w_jobs_hdr_id,
    w_jobcard_hdr_t.job_no,
    CONCAT(w_jobcard_hdr_t.job_date,' ',TIME_FORMAT(w_jobcard_hdr_t.created_at, '%H:%i:%s')) as job_date,
    w_jobcard_hdr_t.job_adjusted_qty,
    w_jobcard_hdr_t.batch_no,
    w_jobcard_hdr_t.job_status,
    w_jobcard_hdr_t.job_process,
    w_jobcard_hdr_t.remarks,
    m_products_t.concatenated_product,
    m_products_t.product_code,
    m_uom_codes_t.uom_code,
    w_materialissue_hdr_t.w_materialissue_hdr_id,
    w_materialissue_hdr_t.mtl_issue_date,
    w_productionplan_hdr_t.plan_no,
    hr_employee_t.first_name
    
FROM
    w_jobcard_hdr_t
LEFT JOIN m_products_t ON (m_products_t.product_id = w_jobcard_hdr_t.product_id)
LEFT JOIN m_product_groups_t ON(m_products_t.product_group_id= m_product_groups_t.product_group_id)
LEFT JOIN m_uom_codes_t ON (m_uom_codes_t.uom_code_id = w_jobcard_hdr_t.uom_code_id) 
LEFT JOIN w_materialissue_hdr_t ON	(w_materialissue_hdr_t.w_jobs_hdr_id = w_jobcard_hdr_t.w_jobs_hdr_id)
LEFT JOIN w_productionplan_hdr_t ON (w_productionplan_hdr_t.productionplan_hdr_id=w_jobcard_hdr_t.reference_source_id)
LEFT JOIN hr_employee_t ON (hr_employee_t.employee_id = w_jobcard_hdr_t.job_created_by)

	where 1=1  $wh ORDER BY w_jobcard_hdr_t.w_jobs_hdr_id DESC";

        $result = \DB::select($sql);
        return DataTables::of($result)->make(true);

    }



    /*Jqgrid issue List data*/
    public function issuelistdata()
    {

        $wh = '';

        if (isset($_GET)) {
            if ($_GET['type'] == 'fg') {
                $wh .= " and m_product_groups_t.group_name='FINISHED GOODS' ";
            } else {
                $wh .= " and m_product_groups_t.group_name='SEMI FINISHED GOODS' ";
            }
        }

        $loc = "1";
        $compy = \Session::get('companyid');
        $org = \Session::get('organization');
        $groupname = \Session::get('groupname');
        $grid_date = \Session::get('griddate');
        $gridenddate = \Session::get('gridenddate');

        if ($groupname == '1' || $groupname == 'Admin') {
            $wh .= 'and  w_materialissue_line_t.company_id=' . $compy;
        } else {
            $wh .= " and ( ( DATE(w_materialissue_hdr_t.mtl_issue_date) < '$grid_date') or  ( DATE(w_materialissue_hdr_t.mtl_issue_date) BETWEEN '$grid_date' and '$gridenddate' ) )  ";
            $wh .= 'and  w_materialissue_line_t.company_id=' . $compy . ' and w_materialissue_line_t.location_id=' . $loc;
        }

        $sql = "SELECT w_materialissue_line_t.w_materialissue_line_id,w_materialissue_line_t.batchnumber,w_materialissue_line_t.issueqty,w_materialissue_line_t.w_materialissue_hdr_id,m_products_t.concatenated_product ,m_products_t.product_code,round(w_materialissue_line_t.receive_qty,3) as receive_qty,round(w_materialissue_line_t.mtl_issue_qty,3) as mtl_issue_qty ,w_jobcard_hdr_t.job_no,round((w_materialissue_line_t.receive_qty-w_materialissue_line_t.mtl_issue_qty),3) as variance_qty,m_uom_codes_t.uom_code,w_materialissue_hdr_t.mtl_issue_date FROM `w_materialissue_line_t` left join w_materialissue_hdr_t on w_materialissue_hdr_t.w_materialissue_hdr_id=w_materialissue_line_t.w_materialissue_hdr_id left join  m_products_t on m_products_t.product_id=w_materialissue_line_t.product_id  LEFT JOIN m_product_groups_t ON(m_products_t.product_group_id= m_product_groups_t.product_group_id) left join  m_uom_codes_t on m_uom_codes_t.uom_code_id=w_materialissue_line_t.uom_code_id left join w_jobcard_hdr_t on w_jobcard_hdr_t.w_jobs_hdr_id=w_materialissue_hdr_t.w_jobs_hdr_id where 1=1 $wh order by w_materialissue_line_t.w_materialissue_line_id DESC";

        $result = \DB::select($sql);

        $data = (object) array();
        foreach ($result as $key => $val) {
            $bno = explode(",", $val->batchnumber);
            $issueqty = explode(",", $val->issueqty);
            $receive_qty = explode(",", $val->receive_qty);
            $data->$key['w_materialissue_line_id'] = $val->w_materialissue_line_id;
            $data->$key['w_materialissue_hdr_id'] = $val->w_materialissue_hdr_id;
            $data->$key['concatenated_product'] = $val->concatenated_product;
            $data->$key['product_code'] = $val->product_code;
            $data->$key['job_no'] = $val->job_no;

            if (count($bno) != count($issueqty)) {
                foreach ($issueqty as $k3 => $v3) {

                }
                foreach ($issueqty as $k1 => $v1) {

                }
            }
            foreach ($bno as $k => $v) {
                $data->$k['batchnumber'] = $v;
                $data->$k['receive_qty'] = isset($receive_qty[$k]) ? $receive_qty[$k] : '';
                $data->$k['issueqty'] = $issueqty[$k];
            }


        }

        return DataTables::of($result)->make(true);

    }


    /* purpose:Create Function*/
       public function create($id = null)
    {
        //dd($_GET['source']);
        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'Materialissue')->get();
        $decimal = \Session::get('decimal');
        if (isset($_GET['source'])) {
            if ($_GET['source'] == "MATERIALISSUE" || $_GET['source'] == "PACKINGMATERIALISSUE") {
                if ($this->data['pageMethod'] == "materialrequestview") {
                    $this->data['url'] = "materialrequestview";
                    $this->data['source'] = "";
                }
                if ($_GET['source'] == "MATERIALISSUE") {
                    $this->data['url'] = "materialissues";
                } else if ($_GET['source'] == "PACKINGMATERIALISSUE") {
                    $this->data['url'] = "packingmaterialissues";
                }
                $this->data['id'] = $id;
                $this->data['pagemode'] = "edit";
                $this->data['source'] = $_GET['source'];
                $table = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $id)->get();
                $this->data['organization_id'] = $this->jcombo("m_organizations_t", "organization_id", "organization_name", \Session::get('organization'));
                $this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $table[0]->uom_code_id);
                $this->data['ass_product_id'] = $this->jCombo('m_products_t', 'product_id', 'concatenated_product|product_code', $table[0]->product_id);
                $this->data['w_jobs_hdr_id'] = $this->jCombocomp('w_jobcard_hdr_t', 'w_jobs_hdr_id', 'job_no', $table[0]->w_jobs_hdr_id);
                $this->data['job_qty'] = $table[0]->job_adjusted_qty;
                $this->data['job_status'] = $table[0]->job_status;
                $this->data['job_process'] = $table[0]->job_process;
                $this->data['batch_no'] = $table[0]->batch_no;
                $this->data['group'] = $this->groupname($table[0]->product_id);
                date_default_timezone_set('Asia/Calcutta');
                $this->data['mtl_issue_date'] = date("d-m-Y H:i:s");
                //$tim = date("H:i:s");
                //$this->data['mtl_issue_date']="31-03-2023 ".$tim;

                if ($table[0]->bom_process == '') {
                    if ($table[0]->reworksrc == 'jobrework') {
                        $getplanid = \DB::select('select w_jobcard_hdr_t.reference_source_id  from i_qoh_detail_t join w_jobcard_hdr_t on(w_jobcard_hdr_t.w_jobs_hdr_id=i_qoh_detail_t.job_id) where i_qoh_detail_t.qoh_detail_id=' . $table[0]->reference_source_id);
                        $planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $getplanid[0]->reference_source_id)->where('parent_product', $table[0]->product_id)->where('product_id', '!=', 0)->get();
                    } else {
                        $planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $table[0]->reference_source_id)->where('parent_product', $table[0]->product_id)->where('product_id', '!=', 0)->get();
                    }
                    //	}else if($table[0]->bom_process=='PROCESS-1'){
                } else if ($table[0]->bom_process == '0') {
                    $planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $table[0]->reference_source_id)->where('product_id', '!=', $table[0]->product_id)->where('parent_product', $table[0]->product_id)->where('product_id', '!=', 0)->get();
                } else {
                    $planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $table[0]->reference_source_id)->where('parent_product', $table[0]->product_id)->where('process_level', $table[0]->bom_process)->where('product_id', '!=', 0)->get();
                }
                if ($this->data['group'] == "FINISHED GOODS") {
                    $prdgrpid = '3,12';
                } else {
                    $prdgrpid = 2;
                }


                $this->data['productid'] = $this->jcustomselect('m_products_t', 'product_id', 'concatenated_product|product_code', '', " and product_group_id IN ($prdgrpid)");
                /* purpose:jobcard rework*/
                if ($table[0]->job_status == 'JOBCARD REWORK') {
                    $this->data['linedata'] = array();
                    if ($table[0]->reworksrc == 'salesrework' || $table[0]->reworksrc == 'qcrework') {
                        $this->data['linedata'] = $table;
                    } else {
                        $this->data['linedata'] = $planlines;
                    }
                    foreach ($this->data['linedata'] as $key => $value) {
                        $prdid = $value->product_id;
                        $this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'concatenated_product|product_code', $prdid, '');
                        $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                        if ($table[0]->reworksrc != 'jobrework') {
                            $this->data['linedata'][$key]->qty = number_format($value->job_qty, $decimal, '.', '');
                            $this->data['linedata'][$key]->issue_qty = "";
                            $this->data['linedata'][$key]->issued_qty = "";
                            $this->data['linedata'][$key]->balance_qty = "";
                            $this->data['linedata'][$key]->comments = "";
                        } else {
                            $sql2 = \DB::select("SELECT w_materialissue_hdr_t.*,w_materialissue_line_t.*,sum(w_materialissue_line_t.mtl_issue_qty)as issuedqty FROM w_materialissue_hdr_t LEFT JOIN w_materialissue_line_t ON(w_materialissue_hdr_t.w_materialissue_hdr_id = w_materialissue_line_t.w_materialissue_hdr_id)  where w_materialissue_line_t.product_id=" . $prdid . " and  w_materialissue_hdr_t.w_jobs_hdr_id=" . $table[0]->w_jobs_hdr_id . " GROUP BY w_materialissue_line_t.product_id");
                            $this->data['linedata'][$key]->qty = number_format($value->component_qty, $decimal, '.', '');
                            $this->data['linedata'][$key]->issue_qty = number_format(($value->component_qty) * ($this->data['job_qty']), $decimal, '.', '');
                            if (count($sql2) > 0) {
                                $balqty = number_format(($sql2[0]->issue_qty) - ($sql2[0]->issuedqty), $decimal, '.', '');
                                if ($balqty > 0) {
                                    $this->data['linedata'][$key]->balance_qty = $balqty;
                                } else {
                                    $this->data['linedata'][$key]->balance_qty = 0;
                                }

                                $this->data['linedata'][$key]->issued_qty = number_format($sql2[0]->issuedqty, $decimal, '.', '');
                            } else {
                                $this->data['linedata'][$key]->balance_qty = number_format(($value->component_qty) * ($this->data['job_qty']), $decimal, '.', '');
                                $this->data['linedata'][$key]->issued_qty = 0;
                            }
                            $this->data['linedata'][$key]->subinventory_id = $this->jCombo('m_subinventory_t', 'subinventory_id', 'subinventory_name', '');
                            $this->data['linedata'][$key]->locator_id = $this->jCombo('m_sublocators_t', 'sublocator_id', 'locator_code', '');
                            $this->data['linedata'][$key]->qoh = "0";
                            $sql = \DB::select("select * from m_products_t where product_id=" . $prdid);
                            if (count($sql) > 0) {
                                if (!empty($sql[0]->subinventory_id)) {
                                    $this->data['linedata'][$key]->subinventory_id = $this->jCombo('m_subinventory_t', 'subinventory_id', 'subinventory_name', $sql[0]->subinventory_id);
                                    $this->data['linedata'][$key]->locator_id = $this->jcustomselect('m_sublocators_t', 'sublocator_id', 'locator_code', $sql[0]->sublocator_id, 'and subinventory_id=' . $sql[0]->subinventory_id);
                                }
                                $org = \Session::get('organization');
                                $qoh = \DB::Select("select sum(qoh_trx_qty) as qoh_qty from i_qoh_detail_t where product_id='" . $prdid . "' and subinventory_id='" . $sql[0]->subinventory_id . "' and locator_id='" . $sql[0]->sublocator_id . "' and organization_id='" . $org . "' group by product_id ");



                                if (isset($qoh[0]->qoh_qty)) {
                                    if ($qoh[0]->qoh_qty > 0) {
                                        $this->data['linedata'][$key]->qoh = $qoh[0]->qoh_qty;
                                    } else {
                                        $this->data['linedata'][$key]->qoh = "0";
                                    }
                                }
                            }

                        }
                    }
                }
                /*end*/ else {
                    $this->data['linedata'] = $planlines;
                    if (count($this->data['linedata']) >= 1) {
                        foreach ($this->data['linedata'] as $key => $value) {
                            $product_id = $value->product_id;
                            $sql2 = \DB::select("SELECT w_materialissue_hdr_t.*,w_materialissue_line_t.*,sum(w_materialissue_line_t.mtl_issue_qty)as issuedqty FROM w_materialissue_hdr_t LEFT JOIN w_materialissue_line_t ON(w_materialissue_hdr_t.w_materialissue_hdr_id = w_materialissue_line_t.w_materialissue_hdr_id)  where w_materialissue_line_t.product_id=" . $value->product_id . " and  w_materialissue_hdr_t.w_jobs_hdr_id=" . $table[0]->w_jobs_hdr_id . " GROUP BY w_materialissue_line_t.product_id");

                            // subinv trans reciver initated product dont show
                            $fid = \DB::select("SELECT product_id FROM `i_subinventory_transfer_lines_t` WHERE (`status` = 'INITIATED' or `status` = '')");
                            $fidProductIds = array_column($fid, 'product_id');
                            $fgid = implode(',', $fidProductIds);

                            // cunsumable initated product dont show
                            $cun_id = \DB::select("SELECT product_id FROM i_consumable_lines_t INNER JOIN i_consumable_hdr_t ON i_consumable_lines_t.consumable_hdr_id = i_consumable_hdr_t.consumable_hdr_id WHERE i_consumable_hdr_t.status = 'INITIATED'");
                            $cun_ProductIds = array_column($cun_id, 'product_id');
                            $cunpg_id = implode(',', $cun_ProductIds);

                            $condition = '';
                            if (!empty($fgid) && !empty($cunpg_id)) {
                                $condition .= " and product_id NOT IN ($fgid) and product_id NOT IN ($cunpg_id)";
                            } elseif (!empty($fgid)) {

                                $condition .= " and product_id NOT IN ($fgid) ";

                            } elseif (!empty($cunpg_id)) {

                                $condition .= " and product_id NOT IN ($cunpg_id) ";
                            } else {

                                $condition = '';
                            }

                            $this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'concatenated_product|product_code', $product_id, 'and product_id=' . $product_id . $condition);
                            $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                            $this->data['linedata'][$key]->qty = number_format($value->component_qty, $decimal, '.', '');
                            $this->data['linedata'][$key]->issue_qty = number_format(($value->component_qty) * ($this->data['job_qty']), $decimal, '.', '');
                            if (count($sql2) > 0) {
                                $balqty = number_format(($sql2[0]->issue_qty) - ($sql2[0]->issuedqty), $decimal, '.', '');
                                if ($balqty > 0) {
                                    $this->data['linedata'][$key]->balance_qty = $balqty;
                                } else {
                                    $this->data['linedata'][$key]->balance_qty = 0;
                                }

                                $this->data['linedata'][$key]->issued_qty = number_format($sql2[0]->issuedqty, $decimal, '.', '');
                            } else {
                                $this->data['linedata'][$key]->balance_qty = number_format(($value->component_qty) * ($this->data['job_qty']), $decimal, '.', '');
                                $this->data['linedata'][$key]->issued_qty = 0;
                            }
                            $this->data['linedata'][$key]->subinventory_id = $this->jCombo('m_subinventory_t', 'subinventory_id', 'subinventory_name', '');
                            $this->data['linedata'][$key]->locator_id = $this->jCombo('m_sublocators_t', 'sublocator_id', 'locator_code', '');
                            $this->data['linedata'][$key]->qoh = "0";
                            $sql = \DB::select("select * from m_products_t where product_id=" . $product_id);
                            if (count($sql) > 0) {
                                if (!empty($sql[0]->subinventory_id)) {
                                    $this->data['linedata'][$key]->subinventory_id = $this->jCombo('m_subinventory_t', 'subinventory_id', 'subinventory_name', $sql[0]->subinventory_id);
                                    $this->data['linedata'][$key]->locator_id = $this->jcustomselect('m_sublocators_t', 'sublocator_id', 'locator_code', $sql[0]->sublocator_id, 'and subinventory_id=' . $sql[0]->subinventory_id);
                                }
                                $org = \Session::get('organization');
                                $qoh = \DB::Select("select sum(qoh_trx_qty) as qoh_qty from i_qoh_detail_t where product_id='" . $product_id . "' and subinventory_id='" . $sql[0]->subinventory_id . "' and locator_id='" . $sql[0]->sublocator_id . "' and organization_id='" . $org . "' group by product_id ");



                                if (isset($qoh[0]->qoh_qty)) {
                                    if ($qoh[0]->qoh_qty > 0) {
                                        $this->data['linedata'][$key]->qoh = $qoh[0]->qoh_qty;
                                    } else {
                                        $this->data['linedata'][$key]->qoh = "0";
                                    }
                                }
                            }
                        }

                    }
                }

            }
        } else {
            if (isset($id)) {
                if ($this->data['pageMethod'] == "materialrequestview") {
                    $this->data['url'] = "materialrequest";
                    $this->data['source'] = "";
                } else {
                    $this->data['url'] = "materialissuescreate";
                    $this->data['source'] = "";
                }
                $this->data['id'] = $id;
                $this->data['pagemode'] = "edit";
                $table = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $id)->get();
                $this->data['organization_id'] = $this->jcombo("m_organizations_t", "organization_id", "organization_name", \Session::get('organization'));
                $this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $table[0]->uom_code_id);
                $this->data['ass_product_id'] = $this->jCombo('m_products_t', 'product_id', 'concatenated_product|product_code', $table[0]->product_id);
                $this->data['group'] = $this->groupname($table[0]->product_id);
                $this->data['w_jobs_hdr_id'] = $this->jCombocomp('w_jobcard_hdr_t', 'w_jobs_hdr_id', 'job_no', $table[0]->w_jobs_hdr_id);
                $this->data['job_qty'] = $table[0]->job_adjusted_qty;
                $this->data['batch_no'] = $table[0]->batch_no;
                $this->data['job_status'] = $table[0]->job_status;

                date_default_timezone_set('Asia/Calcutta');
                $this->data['mtl_issue_date'] = date("d-m-Y H:i:s");

                $this->data['productid'] = $this->jCombo('m_products_t', 'product_id', 'concatenated_product|product_code', '');
                /* purpose:jobcard rework*/
                if ($table[0]->job_status == 'JOBCARD REWORK') {
                    $this->data['linedata'] = array();
                    $this->data['linedata'] = $table;
                    foreach ($this->data['linedata'] as $key => $value) {

                        $this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'concatenated_product|product_code', $value->product_id, 'and product_id=' . $value->product_id);
                        $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                        $this->data['linedata'][$key]->qty = number_format($value->job_qty, $decimal, '.', '');
                        $this->data['linedata'][$key]->issue_qty = "";
                        $this->data['linedata'][$key]->issued_qty = "";
                        $this->data['linedata'][$key]->balance_qty = "";
                        $this->data['linedata'][$key]->comments = "";
                    }
                } else {
                    /*end*/
                    if ($table[0]->bom_process == '0') {
                        $planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $table[0]->reference_source_id)->where('parent_product', $table[0]->product_id)->get();
                    } else {
                        $planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $table[0]->reference_source_id)->where('parent_product', $table[0]->product_id)->where('process_level', $table[0]->bom_process)->get();
                    }
                    $this->data['process'] = $planlines[0]->process_level;
                    $this->data['linedata'] = $planlines;
                    if (count($this->data['linedata']) >= 1) {
                        foreach ($this->data['linedata'] as $key => $value) {
                            $productid = $value->product_id;
                            $sql2 = \DB::select("SELECT w_materialissue_hdr_t.*,w_materialissue_line_t.*,sum(w_materialissue_line_t.mtl_issue_qty)as issuedqty FROM w_materialissue_hdr_t LEFT JOIN w_materialissue_line_t ON(w_materialissue_hdr_t.w_materialissue_hdr_id = w_materialissue_line_t.w_materialissue_hdr_id)  where w_materialissue_line_t.product_id=" . $value->product_id . " and  w_materialissue_hdr_t.w_jobs_hdr_id=" . $table[0]->w_jobs_hdr_id . " GROUP BY w_materialissue_line_t.product_id");
                            $this->data['linedata'][$key]->product_id = $this->jCombo('m_products_t', 'product_id', 'concatenated_product|product_code', $value->product_id);
                            $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                            $this->data['linedata'][$key]->qty = $value->component_qty;
                            $this->data['linedata'][$key]->issue_qty = ($value->component_qty) * ($this->data['job_qty']);
                            if (count($sql2) > 0) {
                                $this->data['linedata'][$key]->balance_qty = ($sql2[0]->issue_qty) - ($sql2[0]->issuedqty);
                                $this->data['linedata'][$key]->issued_qty = $sql2[0]->issuedqty;
                            } else {
                                $this->data['linedata'][$key]->balance_qty = ($value->component_qty) * ($this->data['job_qty']);
                                $this->data['linedata'][$key]->issued_qty = 0;
                            }
                            if ($productid == $table[0]->product_id) {
                                unset($this->data['linedata'][$key]);
                            }
                        }

                    }
                }

            }
        }
        return view("materialIssue.form", $this->data);
    }
    /*End*/


    /*Save Function*/
    public function save(Request $request)
    {

        $compid = \Session('companyid');

        // matirial issue  mail function start

        $emp = \Session::get('id');
        $prd_id = $_POST['product_id'];
        $job_id = $_POST['w_jobs_hdr_id'];
        $batch_no = $_POST['batch_no'];
        $qty = $_POST['job_qty'];
        $status = "ISSUED";

        $user_mail = \DB::select("select user_mail,first_name from tb_users where employee_id='$emp'");
        if (!empty($user_mail) && !empty($user_mail[0]->user_mail)) {
            $from_umail = $user_mail[0]->user_mail;
        } else {
            $from_umail = "aspire@jrkresearch.com";
        }

        $pro_name1 = \DB::SELECT("select concatenated_product from m_products_t where product_id='$prd_id'");
        $created = \DB::SELECT("select job_no,job_date,job_created_by from w_jobcard_hdr_t where w_jobs_hdr_id ='$job_id'");
        $created_at = $created[0]->job_created_by;
        $job_no = $created[0]->job_no;
        $job_date = $created[0]->job_date;
        //dd($qty);
        $created = \DB::SELECT("select job_no,job_date,job_created_by from w_jobcard_hdr_t where w_jobs_hdr_id ='$job_id'");
        $created_at = $created[0]->job_created_by;
        $job_no = $created[0]->job_no;
        $job_date = $created[0]->job_date;

        $user_mail1 = \DB::select("select user_mail from tb_users where employee_id='$created_at' and active='Yes'");
        if (!empty($user_mail1) && !empty($user_mail1[0]->user_mail)) {
            $created_mail = $user_mail1[0]->user_mail;
        } else {

            $created_mail = "aspire@jrkresearch.com";
        }

        $man_id = \DB::select("select reporting_manager from hr_employee_t where employee_id='$created_at'");
        $managr_id = $man_id[0]->reporting_manager;

        $managr_mail = \DB::select("select user_mail from tb_users where employee_id ='$managr_id' and group_id !='15' and active='Yes'");

        if (!empty($managr_mail) && !empty($managr_mail[0]->user_mail)) {
            $manr_mail = $managr_mail[0]->user_mail;
        } else {

            $manr_mail = "aspire@jrkresearch.com";
        }


        $from_mail = $from_umail;
        $cr_mail = $created_mail;
        $man_mail = $manr_mail;
        $pro_name = $pro_name1[0]->concatenated_product;
        $sub = "MATERIAL ISSUED FOR - $job_no ";
        $to_mail = "$cr_mail,$man_mail";
        $emp_name = $user_mail[0]->first_name;

        $msg = "<p>Dear Team,<br><br>The request material has been issued,<br><br>Jobcard No - $job_no <br>Jobcard Date - $job_date <br>Batch No - $batch_no <br>Product Name - $pro_name<br>Qty - $qty<br>Status - ISSUED<br><br>Regards, <br> $emp_name";

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

        // mail functrion end 

			$form = $request->all();
			$dataupload ="";
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file','process','job_status',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
			$lines_data = $this->validatePost($form, $this->subtable, 'lines');
            $data['product_id'] = $request->product_id;
            $data['uom_code_id'] = $request->uom_code_id;
            $data['mtl_issue_date'] = date("Y-m-d H:i:s", strtotime($_POST['mtl_issue_date']));
        $batch = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $_POST['w_jobs_hdr_id'])->get();
 
        \DB::beginTransaction();
        try {
            $id = $this->model->insertRow($data);
            $lid = $this->submodel->subgridSave($lines_data, $id);
            /* purpose:audit log*/
            $this->auditlog($id, "materialissue", 'create', $_POST, "w_materialissue_hdr_t");
            /*end*/
            /* purpose: to insert mtltrx & qoh*/
            $Wiputlites = new \App\Http\Controllers\CommonutilityController();
            $status = '';
            if (!$Wiputlites->Materialissue($id)) {
                return response()->json(array(
                    'status' => 'error',
                    'message' => $status,
                ));
            }
   
            /* purpose:msg for stock when it reduce against minorder*/
            if ($batch[0]->batch_no == " " || $batch[0]->batch_no == "") {
                $sub_product = $batch[0]->bom_product_id;
                foreach ($_POST['bulk_product_id'] as $ke => $va) {
                    if ($va == $sub_product) {
                        $batch_no = explode(',', $_POST['bulk_batchnumber'][$ke]);
                        \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $_POST['w_jobs_hdr_id'])->update(['batch_no' => $batch_no[0]]);
                        $prd = \DB::table('m_products_t')->where('product_id', $va)->select('min_order_qty')->get();
                        $qoh = \DB::select("SELECT SUM(f.qty - IFNULL(f.qtyy, 0)) AS qty, f.product_id, m_products_t.product_group_id, m_products_t.concatenated_product, m_product_groups_t.group_name,f.company_id FROM ( SELECT SUM(qoh_trx_qty) AS qty, 0 AS qtyy, product_id,company_id FROM i_qoh_detail_t GROUP BY product_id UNION ALL SELECT 0 AS qty, SUM(reserv_trx_qty) AS qtyy, product_id AS fdfd,company_id FROM i_reservation_detail_t GROUP BY product_id ) f LEFT JOIN m_products_t ON( m_products_t.product_id = f.product_id ) LEFT JOIN m_product_groups_t ON( m_products_t.product_group_id = m_product_groups_t.product_group_id) WHERE m_product_groups_t.group_name = 'RAW MATERIALS' and f.product_id=" . $va . " and f.company_id = " . $compid . " GROUP BY f.product_id");
                        if (count($qoh) > 0) {
                            if ($qoh[0]->qty < $prd[0]->min_order_qty) {
                                $notifcation = 'Stock level low for this' . $va . ' Product';
                                $send_notification = $this->sendPopUpHomeNoty($va, "STOCK LEVEL(RM)", $notifcation, 'ouantityonhand');
                            }
                        }
                    }
                }
            }
            /*end*/
            DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $_POST['w_jobs_hdr_id'])->update(['job_status' => 'MATERIAL ISSUED']);
            \DB::commit();
            /*vimala purpose:notymsg for material receive against material issue*/
            $notifcation = 'Materialissue Initiated for this ' . $batch[0]->job_no . ' Jobcard';
            $send_notification = $this->sendPopUpHomeNoty($id, "MATERIALISSUE", $notifcation, 'materialreceive');
            /*end*/

            return response()->json(array('status' => 'success', 'message' => 'Material Issued Successfully', 'id' => $id, 'lid' => $lid));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');

            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }
	
    /* purpose:material request print*/
    public function materialprint($id = null)
    {

        require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');

        $header = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $id)->get();
        date_default_timezone_set('Asia/Calcutta');
        $this->data['date'] = date("d-m-Y H:i:s");
        $this->data['job_qty'] = $header[0]->job_adjusted_qty;
        $this->data['batch_no'] = $header[0]->batch_no;
        $this->data['job_no'] = $header[0]->job_no;
        $this->data['assembly_product'] = $this->idname('product_code|concatenated_product', 'm_products_t', 'product_id', $header[0]->product_id);
        $this->data['uom_code'] = $this->idname('uom_code', 'm_uom_codes_t', 'uom_code_id', $header[0]->uom_code_id);
        /* purpose:jobcard rework*/
        if ($header[0]->job_status == 'JOBCARD REWORK') {
            $planlines = "";
            foreach ($header as $key => $value) {
                $header[$key]->concatenated_product = $this->idname('product_code|concatenated_product', 'm_products_t', 'product_id', $value->product_id);
                $header[$key]->uom_code = $this->idname('uom_code', 'm_uom_codes_t', 'uom_code_id', $value->uom_code_id);

                $header[$key]->qty = 1;
                $header[$key]->issue_qty = $this->data['job_qty'];
            }
        } else {
            if ($header[0]->bom_process == '0') {
                $planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $header[0]->reference_source_id)->where('parent_product', $header[0]->product_id)->get();
            } else {
                $planlines = \DB::table('w_productionplan_lines_t')->where('productionplan_hdr_id', $header[0]->reference_source_id)->where('parent_product', $header[0]->product_id)->where('process_level', $header[0]->bom_process)->get();
            }

            if ($planlines->isNotEmpty()) {
                foreach ($planlines as $key => $value) {
                    $planlines[$key]->concatenated_product = $this->idname('product_code|concatenated_product', 'm_products_t', 'product_id', $value->product_id);
                    $planlines[$key]->uom_code = $this->idname('uom_code', 'm_uom_codes_t', 'uom_code_id', $value->uom_code_id);

                    $planlines[$key]->qty = $value->component_qty;
                    $planlines[$key]->issue_qty = ($value->component_qty) * ($this->data['job_qty']);
                }
            } else {
                $key = 0;
                $planlines[$key]->concatenated_product == '';
                $planlines[$key]->uom_code = '';
                $planlines[$key]->qty = '';
                $planlines[$key]->issue_qty = '';


            }
        }
        $address = $this->getLocationwiseaddress();

        if ($address != 0) {
            $location_name = $address[0]->location_name;
            $this->data['location_name'] = $location_name;
            $address1 = $address[0]->address;
            $this->data['address'] = $address1;
            $street = $address[0]->street_name;
            $this->data['street_name'] = $street;
            $location = $address[0]->location_name;
            $this->data['location'] = $location;
            $area = $address[0]->area;
            $this->data['area'] = $area;
            $this->data['pincode'] = $address[0]->pincode;
            //GET COMPANY ADDRESS
            $this->data['company_gst_no'] = $address[0]->gst_no;

            $this->data['city'] = $this->idname('city_name', 'm_cities_t', 'city_id', $address[0]->city_id);
            $this->data['state'] = $this->idname('state_name', 'm_states_t', 'state_id', $address[0]->state_id);
            $this->data['country'] = $this->idname('country_name', 'm_countries_t', 'country_id', $address[0]->country_id);
            $this->data['gst_no'] = $address[0]->gst_no;

        }

        $this->data['company_address'] = $address1 . "," . $street . "," . $area . "," . $this->data['city'] . "-" . $this->data['pincode'] . "," . $this->data['state'] . "," . $this->data['country'];

        if ($address == 0) {
            array_push($l_error, "Check Organization or company Details");
        }

        /********************* company *******************/
        $company = $this->getCompany();
        if (!empty($company)) {
            $company_name = $company[0]->company_name;
            $this->data['company_name'] = $company_name;
        }



        /******************** end ************************/
        if ($planlines != "") {
            $this->data['lines'] = $planlines;
        } else {
            $this->data['lines'] = $header;
        }
        $this->data['print'] = "PRINT";
        $this->data['print_val'] = '1';
        return view('materialIssue.mtl_print', $this->data);
    }
    /*end*/
    /* purpose: to get product subinventory details*/
    public function prdsubinventorydetails($id = null)
    {
        $sql = \DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $id)->get();
        $html = "";
        $product = $_GET['product'];
        $comp = \Session::get('companyid');
        $group = \DB::select("select m_products_t.product_group_id,m_product_groups_t.group_name,m_products_t.concatenated_product from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) where product_id='" . $product . "'");
        if ($group[0]->group_name == 'SEMI FINISHED GOODS') {

            $sql = \DB::select("select * from(select *,ROUND(sum(qoh_trx_qty),3)as qohqty  from i_qoh_detail_t where product_id='" . $_GET['product'] . "'   and qualitystatus='1'  and company_id=" . $comp . " group by batch_number ORDER BY `qoh_detail_id` DESC )f ");
        } else {
            if ($_GET['source'] == "MATERIALISSUE") {
                $sql = \DB::select("select i_qoh_detail_t.product_id,i_qoh_detail_t.locator_id,i_qoh_detail_t.subinventory_id,i_qoh_detail_t.batch_number,ROUND(sum(i_qoh_detail_t.qoh_trx_qty),3)as qohqty,m_sublocators_t.rack_no  from i_qoh_detail_t left join m_sublocators_t on(m_sublocators_t.sublocator_id=i_qoh_detail_t.locator_id) where i_qoh_detail_t.product_id=" . $_GET['product'] . "   and i_qoh_detail_t.company_id=" . $comp . "  and case when i_qoh_detail_t.subinventory_id = 5 then m_sublocators_t.rack_no IN ('PRM','PP','WIP') else i_qoh_detail_t.subinventory_id != 5  end  group by i_qoh_detail_t.batch_number ORDER BY i_qoh_detail_t.`qoh_detail_id` desc  ");
            } else {
            //    $sql = \DB::select("select *,ROUND(sum(qoh_trx_qty),3)as qohqty  from i_qoh_detail_t where product_id=" . $_GET['product'] . "   and company_id=" . $comp . " and ((subinventory_id=5 and locator_id=191) or subinventory_id!=5) group by batch_number ORDER BY `qoh_detail_id` desc  ");
                 $compress_prd = $group[0]->concatenated_product;
			     //dd(str_contains($compress_prd, 'COMPRESSION'));
                    if((str_contains($compress_prd, 'COMPRESSION') !== false || str_contains($compress_prd, 'SOAP KG') !== false || str_contains($compress_prd, 'BAR KG') !== false) && $group[0]->group_name=="FINISHED GOODS"){
                        $sql=\DB::select("select * from(select *,ROUND(sum(qoh_trx_qty),3)as qohqty  from i_qoh_detail_t where product_id='".$_GET['product']."'  and qualitystatus='0' and (job_process='FINALPROCESS' or qoh_source='BATCH CONVERSION') group by batch_number ORDER BY `qoh_detail_id` DESC )f ");
                            //dd($sql);			     
                    }   else {
                    $sql=\DB::select("select *,ROUND(sum(qoh_trx_qty),3)as qohqty  from i_qoh_detail_t where product_id=".$_GET['product']." and subinventory_id!=5 group by batch_number ORDER BY `qoh_detail_id` desc  ");	 	 
                        //dd($sql);
                    }
                        
                        
            }
        }
        
        if (count($sql) > 0) {
            $subid = "";
            if (!empty($sql)) {
                foreach ($sql as $k => $v) {
                    if ($v->qohqty > 0) {
                        $subid .= $v->subinventory_id . ",";
                    }
                }
            }
            $subinv_id = rtrim($subid, ",");
            $str = implode(',', array_unique(explode(',', $subinv_id)));

            $prdgrp = \DB::select('select m_products_t.product_id,m_products_t.product_group_id,m_product_groups_t.group_name,m_products_t.concatenated_product from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) where product_id=' . $product);
            if (isset($prdgrp)) {
                $group = $prdgrp[0]->group_name;
                $prdgrpid = $prdgrp[0]->product_group_id;
                $comprd=$prdgrp[0]->concatenated_product;
            }

            if ($group == "SEMI FINISHED GOODS") {
                $prdid = \DB::select("select * from m_products_t where product_id='" . $product . "' and product_group_id=" . $prdgrpid);
                $prdbatchid = \DB::select("select * from (select *,ROUND(sum(qoh_trx_qty),3) as qohqty from i_qoh_detail_t where product_id='" . $prdid[0]->product_id . "' and batch_number!='' and qualitystatus=1 group by batch_number order by qoh_detail_id desc)f where f.qohqty >0");
                $bdata = $prdbatchid[0]->batch_number;
                if (count($prdbatchid) > 0) {
                    $batch = $this->jcustomselectcomp1('i_qoh_detail_t', 'batch_number', 'batch_number', '', 'and product_id=' . $product . ' and qoh_trx_qty>0 and batch_number!="" and qualitystatus=1', 'batch_number');
                    $sub = $this->jcustomselect('m_subinventory_t', 'subinventory_id', 'subinventory_name', '', 'and subinventory_id in(' . $str . ')');
                    $loc = $this->jCombo('m_sublocators_t', 'sublocator_id', 'locator_code', '');
					$qoh = round((float) (\Session::get('qoh') ?? 0), \Session::get('decimal'));

                }

            } else {
                if ($_GET['source'] == "MATERIALISSUE") {
                    $prdqoh = \DB::select("select i_qoh_detail_t.product_id,i_qoh_detail_t.locator_id,i_qoh_detail_t.subinventory_id,i_qoh_detail_t.batch_number,ROUND(sum(i_qoh_detail_t.qoh_trx_qty),3)as qty,m_sublocators_t.rack_no  from i_qoh_detail_t left join m_sublocators_t on(m_sublocators_t.sublocator_id=i_qoh_detail_t.locator_id) where i_qoh_detail_t.product_id='" . $product . "'   and i_qoh_detail_t.company_id=" . $comp . "  and case when i_qoh_detail_t.subinventory_id = 5 then m_sublocators_t.rack_no IN ('PRM','PP','WIP') else i_qoh_detail_t.subinventory_id != 5  end  group by i_qoh_detail_t.batch_number   ORDER BY i_qoh_detail_t.`qoh_detail_id` desc");
                } else {
                //    $prdqoh = \DB::select("select *,ROUND(sum(qoh_trx_qty),3)as qty  from i_qoh_detail_t where product_id='" . $product . "'   and company_id=" . $comp . " and ((subinventory_id=5 and locator_id=191) or subinventory_id!=5) group by batch_number ORDER BY `qoh_detail_id` desc  ");
                        if((str_contains($comprd, 'COMPRESSION') !== false || str_contains($comprd, 'SOAP KG') !== false || str_contains($comprd, 'BAR KG') !== false) && $group=="FINISHED GOODS"){
                            $prdqoh=\DB::select("select * from (select *,ROUND(sum(qoh_trx_qty),3) as qty from i_qoh_detail_t where product_id='".$product."' and batch_number!='' and qualitystatus='0' and (job_process='FINALPROCESS' or qoh_source='BATCH CONVERSION') group by batch_number order by qoh_detail_id desc)f where f.qty >0");
                        }   else {    
                            
                            $prdqoh=\DB::select("select *,ROUND(sum(qoh_trx_qty),3)as qty  from i_qoh_detail_t where product_id='".$product."' and subinventory_id!=5 group by batch_number ORDER BY `qoh_detail_id` desc  ");	
                        }
                }
                //$prdqoh=\DB::select("select *,ROUND(sum(qoh_trx_qty),3) as qty from i_qoh_detail_t where product_id='".$product."'  and company_id=$comp and subinventory_id!=5 group by batch_number order by qoh_detail_id desc");
                $bno = "";
                $rmqoh = 0;
                $btchno = "";
                $subinventory_id = "";
                $locator_id = "";
                if (count($prdqoh) > 0) {
                    foreach ($prdqoh as $k => $v) {
                        if ($v->qty > 0) {
                            $bno .= "'" . $v->batch_number . "'" . ",";

                            $rmqoh = $prdqoh[0]->qty;
                            $btchno = $prdqoh[0]->batch_number;
                            $subinventory_id = $prdqoh[0]->subinventory_id;
                            $locator_id = $prdqoh[0]->locator_id;
                        }
                    }
                }
                $bno1 = rtrim($bno, ",");
                $bdata = $bno1;
                if ($bno1 != "") {
                    $batch = $this->jcustomselectcomp1('i_qoh_detail_t', 'batch_number', 'batch_number', '', 'and product_id=' . $product . ' and batch_number in (' . $bno1 . ') and qoh_trx_qty>0 and batch_number!=""', 'batch_number');

                    $sub = $this->jcustomselect('m_subinventory_t', 'subinventory_id', 'subinventory_name', '', 'and subinventory_id in(' . $str . ')', '');
                    $loc = $this->jCombo('m_sublocators_t', 'sublocator_id', 'locator_code', '');
                }
                $qoh = round(0, 2);
            }
            if ($bdata != "") {
                $html .= "<b class='text-primary'>Needed Qty :</b> " . $_GET['needqty'] . "<br><br>";
                $html .= "<table class='table table-bordered clone_table1'>";
                $html .= "<thead class='table-light'><th >S.No</th><th >Lot Number</th><th >Subinventory</th><th >Locator</th><th>Qoh</th><th >Qty</th><th></th></thead><tbody  class='clone_lines_body1'>";

                $html .= "<tr class='subinv_clone'>";
                $html .= "<td ><input type='hidden' class='product' value=" . $_GET['product'] . " ><input type='hidden' class='index1' value=" . $_GET['index'] . " ><input type='text' class='form-control line_no' value='1' ></td>
				<td><select class='form-control batch_number'>" . $batch . "</select></td>";
                $html .= "<td>
	<select class='form-control subinventory_id' >" . $sub . "</select>
			
			</td>";
                $html .= "<td>
	<select class='form-control locator_id' >" . $loc . "</select>
			
			</td>";
                $html .= "<td ><input type='text' class='form-control qoh' value=" . $qoh . " readonly ></td>";
                $html .= "<td ><input type='text' class='form-control mtlqty' value='' ></td>         <td class='text-center'>
          <button type='button' class='btn btn-sm btn-danger remove-row1'>
            <i class='fas fa-minus-circle'></i>
          </button>
        </td>";
                $html .= "</tr>";
                $html .= "</tbody></table>";
            } else {
                $html .= "No QOH Available";
            }
        } else {
            $html .= "No QOH Available";

        }
        return $html;
    }
    /*end*/
    /* purpose: to get company details*/
    function getCompany()
    {
        $sql = array();
        $company = \Session::get('companyid');
        $sql = \DB::SELECT("SELECT company_id,company_name FROM `m_company_t` WHERE `company_id`=" . $company . "");
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }
    /*end*/
    /* purpose: to get location details*/
    function getLocationwiseaddress()
    {
        $sql = array();
        $location = "1";
        $sql = \DB::SELECT("SELECT * from `m_location_t` where `location_id`=" . $location . "");

        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }
    /*end*/
    /* purpose: to display  issuelist table*/
    public function issuelist(Request $request)
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
        if ($this->data['pageMethod'] == "packingissuelist") {
            $this->data['type'] = 'fg';
        } else {
            $this->data['type'] = 'sfg';
        }

        return view('materialIssue.issuelisttable', $this->data);
    }

    /* purpose:to get product group */
    public function groupname($id = null)
    {
        $group = \DB::table('m_products_t')->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')->where('m_products_t.product_id', $id)->get();
        if (count($group) > 0) {
            $group_id = $group[0]->group_name;
            return $group_id;
        } else {
            return 0;
        }

    }
    /*end*/
    /* purpose:to get product stock details*/
    public function productuom($product_id = null)
    {
        $uom = \DB::table('m_products_t')->select('*')->where('product_id', $product_id)->get();
        $uom_code = \DB::table('m_uom_codes_t')->select('uom_code')->where('uom_code_id', $product_id)->get();
        $com = \Session::get('companyid');
        $qoh = \DB::Select("select sum(qoh_trx_qty) as qoh_qty from i_qoh_detail_t where product_id='" . $product_id . "' and subinventory_id='" . $uom[0]->subinventory_id . "' and locator_id='" . $uom[0]->sublocator_id . "' and company_id='" . $com . "' group by product_id ");

        $data = array();


        if ($uom->isNotEmpty()) {
            $uom_code_id = $uom[0]->trx_uom_id;
            $uom_code = \DB::table('m_uom_codes_t')->select('uom_code')->where('uom_code_id', $uom_code_id)->get();
            if ($uom_code_id != 0) {
                $data['uomname'] = $uom_code[0]->uom_code;
                $data['uomcode'] = $uom_code_id;
                $data['sub'] = $uom[0]->subinventory_id;
                $data['subloc'] = $uom[0]->sublocator_id;
            } else {
                $data['uomcode'] = '';
                 $data['uomname'] = '';
                $data['sub'] = '';
                $data['subloc'] = "";
            }

        } else {
            $data['uomcode'] = "";
             $data['uomname'] = '';
            $data['sub'] = '';
            $data['subloc'] = "";
        }

        if (!empty($qoh)) {

            if ($qoh[0]->qoh_qty != 0) {
                $data['qoh'] = $qoh[0]->qoh_qty;
            } else {
                $data['qoh'] = '';
            }

        } else {
            $data['qoh'] = '';
        }
        return $data;
    }
    /*end*/
    /* purpose:to get product stock details*/
    public function prdstockdata($id = null)
    {
        $batch = $_GET['batch'];
        $decimal = \Session::get('decimal');
        $company = \Session::get('companyid');
        $grp = $this->groupname($id);

        if ($grp != 0 || $grp != "") {
            if ($grp == 'SEMI FINISHED GOODS' || $grp == 'FINISHED GOODS') {
                $group = \DB::select("select m_products_t.product_group_id,m_product_groups_t.group_name,m_products_t.concatenated_product from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id) where product_id='" . $id . "'");
                $compress_prd = $group[0]->concatenated_product;
			     //dd(str_contains($compress_prd, 'COMPRESSION'));
                    if((str_contains($compress_prd, 'COMPRESSION') !== false || str_contains($compress_prd, 'SOAP KG') !== false || str_contains($compress_prd, 'BAR KG') !== false) && $group[0]->group_name=="FINISHED GOODS"){
                        $sql=\DB::select("select subinventory_id,locator_id,round(sum(qoh_trx_qty),4)as qty,product_id,qualitystatus FROM i_qoh_detail_t WHERE trim(batch_number) = '$batch' and product_id = '$id' and company_id=$company and subinventory_id='5' GROUP BY product_id,subinventory_id,locator_id");
                            //dd($sql);			     
                    }   else {
                    $sql = \DB::select("select subinventory_id,locator_id,round(sum(qoh_trx_qty),4)as qty,product_id,qualitystatus FROM i_qoh_detail_t WHERE trim(batch_number) = '$batch' and product_id = '$id' and qualitystatus=1 and company_id=$company and subinventory_id='5' GROUP BY product_id,subinventory_id,locator_id");
                        //dd($sql);
                    }

            } else {
                if ($_GET['source'] == "MATERIALISSUE" || $_GET['source'] == "MATERIALRECEIVE") {
                    $sql = \DB::select("select i_qoh_detail_t.subinventory_id,i_qoh_detail_t.locator_id,round(sum(i_qoh_detail_t.qoh_trx_qty),4)as qty,i_qoh_detail_t.product_id,m_sublocators_t.rack_no FROM i_qoh_detail_t  left join m_sublocators_t on(m_sublocators_t.sublocator_id=i_qoh_detail_t.locator_id) WHERE trim(i_qoh_detail_t.batch_number) = '$batch' and i_qoh_detail_t.product_id = '$id' and i_qoh_detail_t.company_id=$company and case when i_qoh_detail_t.subinventory_id = 5 then m_sublocators_t.rack_no IN ('PRM','PP') else i_qoh_detail_t.subinventory_id != 5 end GROUP BY i_qoh_detail_t.product_id,i_qoh_detail_t.subinventory_id,i_qoh_detail_t.locator_id order by i_qoh_detail_t.qoh_detail_id desc");
                } else if ($_GET['source'] == "PACKINGMATERIALRECEIVE") {
                    $sql = \DB::select("select i_qoh_detail_t.subinventory_id,i_qoh_detail_t.locator_id,round(sum(i_qoh_detail_t.qoh_trx_qty),4)as qty,i_qoh_detail_t.product_id,m_sublocators_t.rack_no FROM i_qoh_detail_t  left join m_sublocators_t on(m_sublocators_t.sublocator_id=i_qoh_detail_t.locator_id) WHERE trim(i_qoh_detail_t.batch_number) = '$batch' and i_qoh_detail_t.product_id = '$id' and i_qoh_detail_t.company_id=$company and case when i_qoh_detail_t.subinventory_id = 5 then m_sublocators_t.rack_no IN ('WIP','PRD','1') end GROUP BY i_qoh_detail_t.product_id,i_qoh_detail_t.subinventory_id,i_qoh_detail_t.locator_id order by i_qoh_detail_t.qoh_detail_id desc");
                } else {
                    $sql = \DB::select("select i_qoh_detail_t.subinventory_id,i_qoh_detail_t.locator_id,round(sum(i_qoh_detail_t.qoh_trx_qty),4)as qty,i_qoh_detail_t.product_id,m_sublocators_t.rack_no FROM i_qoh_detail_t  left join m_sublocators_t on(m_sublocators_t.sublocator_id=i_qoh_detail_t.locator_id) WHERE trim(i_qoh_detail_t.batch_number) = '$batch' and i_qoh_detail_t.product_id = '$id' and i_qoh_detail_t.company_id=$company and case when i_qoh_detail_t.subinventory_id = 5 then m_sublocators_t.rack_no IN ('WIP','PRD','1') else i_qoh_detail_t.subinventory_id != 5 end GROUP BY i_qoh_detail_t.product_id,i_qoh_detail_t.subinventory_id,i_qoh_detail_t.locator_id order by i_qoh_detail_t.qoh_detail_id desc");
                }
            }

            $data = [];
            $inventory = "";
            $locator = "";
            if (count($sql) > 0) {
                foreach ($sql as $k => $v) {
                    if ($v->qty > 0) {

                        $inventory .= $v->subinventory_id . ",";
                    }
                }
                $inventory1 = rtrim($inventory, ",");
                $inv = implode(',', array_unique(explode(',', $inventory1)));

                $data['inventory'] = $inv;
                $data['locator_id'] = "";
                $data['qoh'] = "";
            } else {
                $data['inventory'] = "";
                $data['locator_id'] = "";
                $data['qoh'] = "";
            }

            return $data;
        }
    }


    public function prdstocksubinvdata($id = null)
    {
        $batch = $_GET['batch'];
        $subinv = $_GET['subid'];
        $decimal = \Session::get('decimal');
        $company = \Session::get('companyid');
        $grp = $this->groupname($id);
        if ($grp != 0 || $grp != "") {
            if ($grp == 'SEMI FINISHED GOODS' || $grp == 'FINISHED GOODS') {
                /*$sql=\DB::select("select subinventory_id,locator_id,round(sum(qoh_trx_qty),4)as qty,product_id,qualitystatus FROM i_qoh_detail_t WHERE batch_number = '$batch' and product_id = '$id' and qualitystatus=1 and company_id=$company and subinventory_id='5' GROUP BY product_id,subinventory_id,locator_id");*/
                $sql = \DB::select("select subinventory_id,locator_id,round(sum(qoh_trx_qty),4)as qty,product_id,qualitystatus FROM i_qoh_detail_t WHERE trim(batch_number) = '$batch' and product_id = '$id' and company_id=$company and subinventory_id='5' GROUP BY product_id,subinventory_id,locator_id");
            } else {
                if ($_GET['source'] == "MATERIALISSUE" || $_GET['source'] == "MATERIALRECEIVE") {
                    $sql = \DB::select("select i_qoh_detail_t.subinventory_id,i_qoh_detail_t.locator_id,round(sum(i_qoh_detail_t.qoh_trx_qty),4)as qty,i_qoh_detail_t.product_id,m_sublocators_t.rack_no FROM i_qoh_detail_t  left join m_sublocators_t on(m_sublocators_t.sublocator_id=i_qoh_detail_t.locator_id) WHERE trim(i_qoh_detail_t.batch_number) = '$batch' and i_qoh_detail_t.product_id = '$id' and i_qoh_detail_t.subinventory_id='$subinv' and i_qoh_detail_t.company_id=$company and case when i_qoh_detail_t.subinventory_id = 5 then m_sublocators_t.rack_no IN ('PRM','PP') else i_qoh_detail_t.subinventory_id != 5 end GROUP BY i_qoh_detail_t.product_id,i_qoh_detail_t.subinventory_id,i_qoh_detail_t.locator_id order by i_qoh_detail_t.qoh_detail_id desc");
                } else {
                    $sql = \DB::select("select i_qoh_detail_t.subinventory_id,i_qoh_detail_t.locator_id,round(sum(i_qoh_detail_t.qoh_trx_qty),4)as qty,i_qoh_detail_t.product_id,m_sublocators_t.rack_no FROM i_qoh_detail_t  left join m_sublocators_t on(m_sublocators_t.sublocator_id=i_qoh_detail_t.locator_id) WHERE trim(i_qoh_detail_t.batch_number) = '$batch' and i_qoh_detail_t.product_id = '$id' and i_qoh_detail_t.subinventory_id='$subinv' and i_qoh_detail_t.company_id=$company and case when i_qoh_detail_t.subinventory_id = 5 then m_sublocators_t.rack_no IN ('WIP','PRD','1') else i_qoh_detail_t.subinventory_id != 5 end GROUP BY i_qoh_detail_t.product_id,i_qoh_detail_t.subinventory_id,i_qoh_detail_t.locator_id order by i_qoh_detail_t.qoh_detail_id desc");
                }
            }

            $data = [];
            $locator = "";
            if (count($sql) > 0) {
                foreach ($sql as $k => $v) {
                    if ($v->qty > 0) {
                        $locator .= "'" . $v->locator_id . "'" . ",";
                    }
                }
                $locator1 = rtrim($locator, ",");
                $loc = implode(',', array_unique(explode(',', $locator1)));

                if ($_GET['source'] == "PACKINGMATERIALISSUE" || $_GET['source'] == "MATERIALISSUE") {
                    $data['qty'] = $sql[0]->qty;
                    $data['locator'] = str_replace("'", "", $locator1);
                } else {
                    $data['locator'] = $loc;
                    $data['qty'] = "";
                }
            }

            return $data;
        }
    }

    public function prdstocksublocdata($id = null)
    {
        $batch = $_GET['batch'];
        $subinv = $_GET['subid'];
        $sublocid = $_GET['sublocid'];
        $decimal = \Session::get('decimal');
        $company = \Session::get('companyid');
        $grp = $this->groupname($id);
        /*if($grp!=0 || $grp!=""){
        if($grp=='SEMI FINISHED GOODS' || $grp=='FINISHED GOODS'){
            $sql=\DB::select("select subinventory_id,locator_id,round(sum(qoh_trx_qty),4)as qty,product_id,qualitystatus FROM i_qoh_detail_t WHERE batch_number = '$batch' and product_id = '$id' and qualitystatus=1 and company_id=$company and subinventory_id='5' GROUP BY product_id,subinventory_id,locator_id");
        }else{*/
        if ($_GET['source'] == "MATERIALISSUE" || $_GET['source'] == "MATERIALRECEIVE") {
            $sql = \DB::select("select i_qoh_detail_t.subinventory_id,i_qoh_detail_t.locator_id,round(sum(i_qoh_detail_t.qoh_trx_qty),4)as qty,i_qoh_detail_t.product_id,m_sublocators_t.rack_no FROM i_qoh_detail_t  left join m_sublocators_t on(m_sublocators_t.sublocator_id=i_qoh_detail_t.locator_id) WHERE trim(i_qoh_detail_t.batch_number) = '$batch' and i_qoh_detail_t.product_id = '$id' and i_qoh_detail_t.subinventory_id='$subinv' and i_qoh_detail_t.company_id=$company and case when i_qoh_detail_t.subinventory_id = 5 then m_sublocators_t.rack_no IN ('PRM','PP') else i_qoh_detail_t.subinventory_id != 5 end GROUP BY i_qoh_detail_t.product_id,i_qoh_detail_t.subinventory_id,i_qoh_detail_t.locator_id order by i_qoh_detail_t.qoh_detail_id desc");
            //	$sql=\DB::select("select i_qoh_detail_t.subinventory_id,i_qoh_detail_t.locator_id,round(sum(i_qoh_detail_t.qoh_trx_qty),4)as qty,i_qoh_detail_t.product_id,m_sublocators_t.rack_no FROM i_qoh_detail_t  left join m_sublocators_t on(m_sublocators_t.sublocator_id=i_qoh_detail_t.locator_id) WHERE trim(i_qoh_detail_t.batch_number) = '$batch' and i_qoh_detail_t.product_id = '$id' and i_qoh_detail_t.subinventory_id='$subinv' and i_qoh_detail_t.company_id=$company and case when i_qoh_detail_t.subinventory_id = 5 then m_sublocators_t.rack_no IN ('PRM','PP') else i_qoh_detail_t.subinventory_id != 5 end GROUP BY i_qoh_detail_t.product_id,i_qoh_detail_t.subinventory_id order by i_qoh_detail_t.qoh_detail_id desc");	

        } else {
            $sql = \DB::select("select i_qoh_detail_t.subinventory_id,i_qoh_detail_t.locator_id,round(sum(i_qoh_detail_t.qoh_trx_qty),4)as qty,i_qoh_detail_t.product_id,m_sublocators_t.rack_no FROM i_qoh_detail_t  left join m_sublocators_t on(m_sublocators_t.sublocator_id=i_qoh_detail_t.locator_id) WHERE trim(i_qoh_detail_t.batch_number) = '$batch' and i_qoh_detail_t.product_id = '$id' and i_qoh_detail_t.subinventory_id='$subinv' and i_qoh_detail_t.locator_id='$sublocid' and i_qoh_detail_t.company_id=$company and case when i_qoh_detail_t.subinventory_id = 5 then m_sublocators_t.rack_no IN ('WIP','PRD','1') else i_qoh_detail_t.subinventory_id != 5 end GROUP BY i_qoh_detail_t.product_id,i_qoh_detail_t.subinventory_id,i_qoh_detail_t.locator_id order by i_qoh_detail_t.qoh_detail_id desc");
        }
        //}
        //dd($sql);
        $data = [];
        $locator = "";
        if (count($sql) > 0) {


            $data['qty'] = $sql[0]->qty;

        }

        return $data;
        //}
    }


    public function show(materialissue $materialissue, $id = null)
    {
        $data = \DB::table('w_materialissue_hdr_t')->leftjoin('w_jobcard_hdr_t', 'w_jobcard_hdr_t.w_jobs_hdr_id', '=', 'w_materialissue_hdr_t.w_jobs_hdr_id')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'w_materialissue_hdr_t.product_id')->leftjoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'w_materialissue_hdr_t.uom_code_id')->where('w_materialissue_hdr_t.w_materialissue_hdr_id', $id)->get();

        $this->data['job_no'] = $data[0]->job_no;
        $this->data['assembly_product'] = $data[0]->product_code . "-" . $data[0]->concatenated_product;
        $this->data['uom_code_id'] = $data[0]->uom_code;
        $this->data['job_date'] = date(\Session::get('p_date_format'), strtotime($data[0]->job_date));

        $this->data['job_qty'] = $data[0]->job_qty;
        $this->data['batch_no'] = $data[0]->batch_no;

        $this->data['job_status'] = $data[0]->job_status;
        $this->data['job_process'] = $data[0]->job_process;
        $this->data['mtl_issue_date'] = date(\Session::get('p_date_format'), strtotime($data[0]->mtl_issue_date));
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
        $this->data['active'] = $data[0]->active;

        $this->data['remarks'] = $data[0]->remarks;
        if (isset($_GET)) {
            $this->data['pageurl'] = $_GET['pageurl'];
        }
        $vlinesdata = \DB::table('w_materialissue_line_t')
            ->leftJoin('w_materialissue_hdr_t', 'w_materialissue_line_t.w_materialissue_hdr_id', '=', 'w_materialissue_hdr_t.w_materialissue_hdr_id')
            ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 'w_materialissue_line_t.product_id')
            ->leftJoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'w_materialissue_line_t.uom_code_id')
            ->leftJoin('m_sublocators_t', function ($join) {
                $join->on(\DB::raw('FIND_IN_SET(m_sublocators_t.sublocator_id, w_materialissue_line_t.locator_id)'), '>', \DB::raw('0'));
            })
            ->select(
                'w_materialissue_line_t.*',
                'm_products_t.*',
                'm_uom_codes_t.uom_code',
                \DB::raw('GROUP_CONCAT(m_sublocators_t.locator_code ORDER BY m_sublocators_t.sublocator_id SEPARATOR ", ") as locator_codes')
            )
            ->where('w_materialissue_hdr_t.w_materialissue_hdr_id', $id)
            ->groupBy('w_materialissue_line_t.w_materialissue_line_id') // Assuming line_id is the unique identifier for each line.
            ->get();

        $this->data['vlinesdata'] = $vlinesdata;
        return view('materialIssue.view', $this->data);
    }
    /*end*/
}

