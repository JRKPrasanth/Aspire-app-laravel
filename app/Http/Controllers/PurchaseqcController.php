<?php

namespace App\Http\Controllers;

use App\purchaseqc;
use App\purchaseqclines;
use App\Purchaseqcspecdetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Config;
use yajra\datatables\datatables;

class PurchaseqcController extends Controller
{
    public $module = "purchaseqc";
    public function __construct()
    {
        $this->data = array();
        $this->model = new Purchaseqc;
        $this->submodel = new Purchaseqclines;
        $this->qcmodel = new Purchaseqcspecdetails;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->table = "p_qc_header_t";
        $this->subtable = "p_qc_lines_t";
        $this->qctable = "p_quality_spec_trx_lines_t";
        $this->middleware('auth');
        $this->data = array(
            'pageModule' => 'purchaseqc',
            'pageUrl' => url('purchaseqc'),
            'pageMethod' => $this->data['pageMethod']
        );
        if ($this->data['pageMethod'] == "qcapproval") {
            $this->data['status'] = 'INITIATED';
        } else if ($this->data['pageMethod'] == "mrapproval") {
            $this->data['status'] = 'INITIATED';
        } else {
            $this->data['status'] = '';
        }

        $this->data['urlmenu'] = $this->indexs();
    }

    /* Purpose For :Index Function to Call Table Blade*/
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

        $this->data['suppliername'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        $this->data['batch_no'] = $this->jqgridselect('w_productionplan_hdr_t', 'productionplan_hdr_id', 'batch_no');
        $this->data['employee_id'] = $this->jqgridselect('hr_employee_t', 'employee_id', 'first_name');
        $this->data['product_name'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');
        $this->data['pageMethod'] = \Request::route()->getName();
        $wh = '';
        $loc = \Session::get('loc_id');
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');
        if ($groupname == '1') {
            $wh .= 'and  p_qc_lines_t.company_id=' . $compy;
        } else {
            $wh .= 'and  p_qc_lines_t.company_id=' . $compy;
        }
        if ($this->data['pageMethod'] == 'qcapproval') {
            $wh .= ' and  p_qc_header_t.qc_status="INITIATED"';
        }

        $SQL = "SELECT
                p_qc_header_t.qc_header_id as qc_header_id,
                p_qc_header_t.qc_number as qc_number,
                p_qc_header_t.qc_date as qc_date,
                p_qc_header_t.qc_status,
                p_qc_lines_t.approval_status,
                p_grn_hdr_t.grn_number as grn_number,
                p_grn_hdr_t.dc_number as dc_number,
                p_qc_header_t.po_number as poid,
                p_po_hdr_t.po_number as ponumber,
                p_po_hdr_t.po_date as po_date,
                m_supplier_t.supplier_name,
                m_subcontract_supplier_t.subcontract_name,
                m_products_t.product_code,
                m_products_t.concatenated_product
                FROM `p_qc_lines_t`
                left join p_qc_header_t on(p_qc_lines_t.qc_header_id=p_qc_header_t.`qc_header_id`)
                left join p_po_hdr_t on(p_po_hdr_t.po_hdr_id=p_qc_header_t.`po_number`)
                left join m_supplier_t on(m_supplier_t.supplier_id=p_qc_header_t.`supplier_id`) 
                left join m_subcontract_supplier_t on(m_subcontract_supplier_t.subcontract_supplier_id=p_qc_header_t.`subcontract_supplier_id`) 
                left join m_products_t on(m_products_t.product_id=p_qc_lines_t.`product_id`) 
                left join p_grn_hdr_t on p_grn_hdr_t.grn_id=p_qc_header_t.grn_number  
                where 1=1 and p_qc_header_t.source_type='PURCHASE QUALITY' $wh ORDER BY p_qc_header_t.qc_header_id DESC";

        $result = \DB::select($SQL);
        $this->data['result'] = json_encode($result);

        return view("purchaseqc.table", $this->data);
    }

    // vignesh - new syntax query

    public function QCData(Request $request)
    {

        $status = $request->status;

        $query = DB::table('p_qc_lines_t')
            ->leftJoin('p_qc_header_t', 'p_qc_lines_t.qc_header_id', '=', 'p_qc_header_t.qc_header_id')
            ->leftJoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'p_qc_header_t.po_number')
            ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_qc_header_t.supplier_id')
            ->leftJoin('m_subcontract_supplier_t', 'm_subcontract_supplier_t.subcontract_supplier_id', '=', 'p_qc_header_t.subcontract_supplier_id')
            ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 'p_qc_lines_t.product_id')
            ->leftJoin('p_grn_hdr_t', 'p_grn_hdr_t.grn_id', '=', 'p_qc_header_t.grn_number')
            ->where('p_qc_header_t.source_type', 'PURCHASE QUALITY')
            ->orderByDesc('p_qc_lines_t.qc_header_id');

        if (!empty($status)) {
            $query->where('p_qc_header_t.qc_status', $status);
        }

        $results = $query->select([
            'p_qc_header_t.qc_header_id as qc_header_id',
            'p_qc_header_t.qc_number as qc_number',
            'p_qc_header_t.qc_date as qc_date',
            'p_qc_header_t.qc_status',
            'p_qc_header_t.company_id',
            'p_qc_header_t.location_id',
            'p_qc_lines_t.approval_status',
            'p_grn_hdr_t.grn_number as grn_number',
            'p_grn_hdr_t.dc_number as dc_number',
            'p_po_hdr_t.po_number',
            'p_po_hdr_t.po_date as po_date',
            'm_supplier_t.supplier_name',
            'm_subcontract_supplier_t.subcontract_name',
            'm_products_t.product_code',
            'm_products_t.concatenated_product',
        ])->get();

        return DataTables::of($query)->make(true);

    }


    /* Purpose For :GRN Index Function to Call Table Blade*/
    public function grntable()
    {

        $this->data['suppliername'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        $this->data['batch_no'] = $this->jqgridselect('w_productionplan_hdr_t', 'productionplan_hdr_id', 'batch_no');
        $this->data['employee_id'] = $this->jqgridselect('hr_employee_t', 'employee_id', 'first_name');
        $this->data['product_name'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');
        $wh = '';
        $app_id = \Session::get('id');
        $loc = \Session::get('loc_id');
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');
        if ($groupname == '1') {
            $wh .= 'and  p_grn_hdr_t.company_id=' . $compy;
        } else {
            $wh .= 'and  p_grn_hdr_t.company_id=' . $compy . ' and  FIND_IN_SET(' . $app_id . ',p_grn_hdr_t.approver_id) or  FIND_IN_SET(' . $app_id . ',p_grn_hdr_t.checker_id)';
        }
        $SQL = "SELECT
                        p_grn_hdr_t.grn_id as grn_id,
                        p_grn_hdr_t.grn_number as grn_number,
                        p_grn_hdr_t.grn_status as grn_status,
                        p_grn_hdr_t.dc_number as dc_number,
			p_grn_hdr_t.dc_date as dc_date,
                        p_po_hdr_t.po_number,
                        p_grn_hdr_t.dc_date as dc_date,
			m_products_t.product_code,
			m_products_t.concatenated_product,
                        m_products_t.qc_type,
			p_grn_lines_t.product_id as product_id,
            p_grn_lines_t.po_hdr_id as po_id,
                        m_supplier_t.supplier_name,
			m_subcontract_supplier_t.subcontract_name
                        FROM p_grn_lines_t left join p_grn_hdr_t on p_grn_hdr_t.grn_id=p_grn_lines_t.grn_id
			left join p_po_hdr_t on(p_po_hdr_t.po_hdr_id=p_grn_hdr_t.`po_number`)
			left join m_subcontract_supplier_t on(m_subcontract_supplier_t.subcontract_supplier_id=p_grn_hdr_t.`subcontract_supplier_id`)
                        left join m_supplier_t on(m_supplier_t.supplier_id=p_grn_hdr_t.`supplier_id`)
			left join m_products_t on (m_products_t.product_id =p_grn_lines_t.product_id)
			where 1=1 and m_products_t.qc_check='Yes' and p_grn_hdr_t.company_id=$compy and p_grn_hdr_t.grn_status='INITIATED' and p_grn_hdr_t.master_grn_id=0 and p_grn_lines_t.qc_status=0 and p_grn_lines_t.inventory_status=0 $wh";

        $result = \DB::select($SQL);
        $this->data['result'] = json_encode($result);

        return view("purchaseqc.grn_table", $this->data);
    }

    /*End*/
    /*  purpose for Display GRN Data in JQgrid function */
    public function Grndata(Request $request)
    {

        $compy = \Session::get('companyid');

        $query = DB::table('p_grn_lines_t')
            ->leftJoin('p_grn_hdr_t', 'p_grn_hdr_t.grn_id', '=', 'p_grn_lines_t.grn_id')
            ->leftJoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'p_grn_hdr_t.po_number')
            ->leftJoin('m_subcontract_supplier_t', 'm_subcontract_supplier_t.subcontract_supplier_id', '=', 'p_grn_hdr_t.subcontract_supplier_id')
            ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_grn_hdr_t.supplier_id')
            ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 'p_grn_lines_t.product_id')
            ->where('m_products_t.qc_check', 'Yes')
            ->where('p_grn_hdr_t.company_id', $compy)
            ->where('p_grn_hdr_t.grn_status', 'INITIATED')
            ->where('p_grn_hdr_t.master_grn_id', 0)
            ->where('p_grn_lines_t.qc_status', 0)
            ->where('p_grn_lines_t.inventory_status', 0)
            ->select([
                    'p_grn_hdr_t.grn_id as grn_id',
                    'p_grn_hdr_t.grn_number as grn_number',
                    'p_grn_hdr_t.grn_status as grn_status',
                    'p_grn_hdr_t.dc_number as dc_number',
                    'p_po_hdr_t.po_number',
                    'p_grn_hdr_t.dc_date as dc_date',
                    'p_grn_hdr_t.company_id',
                    'p_grn_hdr_t.location_id',
                    'p_grn_hdr_t.approver_id',
                    'p_grn_hdr_t.checker_id',
                    'm_products_t.product_code',
                    'm_products_t.concatenated_product',
                    'm_products_t.qc_type',
                    'p_grn_lines_t.product_id as product_id',
                    'p_grn_lines_t.po_hdr_id as po_id',
                    'm_supplier_t.supplier_name',
                    'm_subcontract_supplier_t.subcontract_name',
                ])
            ->get();

        return DataTables::of($query)->make(true);
    }

    /*Karthigaa Purpose for Qc Create Function*/
    public function create($id = null, $id1 = NULL, $id2 = null, $qctype = null)
    {
        $this->data['pageMethod'] = \Request::route()->getName();
        if ($this->data['pageMethod'] == "qcapproval") {
            $this->data['url'] = "qcapproval";
        } else if ($this->data['pageMethod'] == "mrcqchecking") {
            $this->data['url'] = "qualitymr";
        } else {
            $this->data['url'] = "purchaseqc";
        }

        if ($this->data['pageMethod'] != 'mrcqchecking') {
            $table = \DB::table('p_grn_hdr_t')->where('grn_id', $id)->get();
            $this->data['row'] = $table[0];
            $tablelines = \DB::table('p_grn_lines_t')->where('grn_id', $id)->where('product_id', $id1)->where('po_hdr_id', $id2)->get();
            $this->data['linedata'] = $tablelines;

            $this->data['row']->supplier_id = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', $table[0]->supplier_id);
            $this->data['row']->subcontract_supplier_id = $this->jCombo('m_subcontract_supplier_t', 'subcontract_supplier_id', 'subcontract_name', $table[0]->subcontract_supplier_id);
            $this->data['row']->po_number = $this->jCombocomp("p_po_hdr_t", "po_hdr_id", "po_number", $table[0]->po_number);
            $this->data['row']->grn_number = $this->jCombocomp("p_grn_hdr_t", "grn_id", "grn_number", $table[0]->grn_id);
            $this->data['row']->qc_header_id = "";
            $this->data['row']->qc_number = "";
            $this->data['row']->start_date = "";
            $this->data['row']->end_date = "";
            $this->data['row']->batch_no = "";
            $this->data['row']->employee_id = "";
            $this->data['row']->remarks = "";
            $this->data['row']->source_type = "PURCHASE QUALITY";
            $this->data['row']->description = "";
            $this->data['row']->qc_date = date('Y-m-d');
            $this->data['row']->qc_status = "DRAFT";
            if (count($this->data['linedata']) >= 1) {
                foreach ($this->data['linedata'] as $key => $value) {
                    $this->data['qc_type'] = $qctype = $this->productqcttype($value->product_id);
                    $this->data['row']->pid = $value->product_id;
                    $this->data['linedata'][$key]->qc_line_id = "";
                    $this->data['linedata'][$key]->qc_header_id = "";
                    $this->data['linedata'][$key]->qc_type = $qctype;
                    $this->data['linedata'][$key]->serialno = $value->serial_number;
                    $this->data['linedata'][$key]->batchno = $value->batch_number;

                    $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id);
                    $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                    $this->data['linedata'][$key]->qty = $value->qty;
                    $this->data['linedata'][$key]->total_box_qty = $value->receive_qty;
                    $this->data['linedata'][$key]->accept_qty = "0";
                    $this->data['linedata'][$key]->reject_qty = "0";
                    $this->data['linedata'][$key]->reason = "";
                    $this->data['linedata'][$key]->grn_line_id = $value->grn_line_id;
                    $this->data['linedata'][$key]->box_product_qty = $value->box_product;
                }
            }
        } else if ($this->data['pageMethod'] == 'mrcqchecking') {
            $table = \DB::table('qc_materialreq_hdr_t')->where('qc_material_req_id', $id)->get();
            $this->data['row'] = $table[0];
            $tablelines = \DB::table('qc_materialreq_lines_t')->where('qc_material_req_id', $id)->get();
            $this->data['linedata'] = $tablelines;
            // dd($tablelines);
            $this->data['row']->supplier_id = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', '');
            $this->data['row']->po_number = $this->jCombocomp("p_po_hdr_t", "po_hdr_id", "po_number", '');
            $this->data['row']->grn_number = $this->jCombocomp("p_grn_hdr_t", "grn_id", "grn_number", '');

            $this->data['row']->batch_no = $this->jCombocomp("w_productionplan_hdr_t", "productionplan_hdr_id", "batch_no", $table[0]->batch_no);
            $this->data['row']->employee_id = $this->jCombo("hr_employee_t", "employee_id", "first_name", $table[0]->employee_id);
            $this->data['row']->qc_header_id = "";
            $this->data['row']->po_date = "";
            $this->data['row']->dc_number = "";
            $this->data['row']->qc_number = "";
            $this->data['row']->description = "";
            $this->data['row']->qc_date = date('Y-m-d');
            $this->data['row']->qc_status = "";
            $this->data['row']->source_type = "MR QUALITY";
            if (count($this->data['linedata']) >= 1) {
                foreach ($this->data['linedata'] as $key => $value) {
                    $this->data['linedata'][$key]->qc_line_id = "";
                    $this->data['linedata'][$key]->qc_header_id = "";
                    $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id);
                    $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                    $this->data['linedata'][$key]->qty = '0';
                    $this->data['linedata'][$key]->receive_qty = $value->issue_qty;
                    $this->data['linedata'][$key]->accept_qty = "";
                    $this->data['linedata'][$key]->reject_qty = "0";
                    $this->data['linedata'][$key]->reason = "";
                }
            }
        }

        return view('purchaseqc.form', $this->data);

    }

    /* Purpose For Save Function*/
    public function save(Request $request)
    {

        $id = '';
        $serialno = json_decode($_POST['serialno']);
        $batchno = json_decode($_POST['batchno']);
        $boxqty = json_decode($_POST['box_product_qty']);

        $form = $request->all();
        $dataupload = "";
        $form = $request->except([
            '_token',
            'form_config',
            'form_data_json',
            'savestatus',
            'submit_type',
            'choosefile',
            'existing_file',
            'remarks',
            'qc_type',
            'serialno',
            'batchno',
            'grn_line_id',
            'box_product_qty',
            'approval_status','qcstatus0',
        ]);
        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');
        $lines_data = [];

        $rowCount = count($request->bulk_line_no ?? []);

        for ($i = 0; $i < $rowCount; $i++) {

            // skip empty rows
            if (empty($request->bulk_line_no[$i]) || empty($request->bulk_product_id[$i])) {
                continue;
            }

            $lines_data['qc_line_id'][] = $request->bulk_qc_line_id[$i] ?? null;
            $lines_data['line_no'][] = $request->bulk_line_no[$i];
            $lines_data['product_id'][] = $request->bulk_product_id[$i];
            $lines_data['uom_code_id'][] = $request->bulk_uom_code_id[$i];
            $lines_data['qty'][] = $request->bulk_total_box_qty[$i] ?? 0;
            $lines_data['receive_qty'][] = $request->bulk_total_box_qty[$i] ?? 0;
            $lines_data['accept_qty'][] = $request->bulk_accept_qty[$i] ?? 0;
            $lines_data['reject_qty'][] = $request->bulk_reject_qty[$i] ?? 0;
            $lines_data['reason'][] = $request->bulk_reason[$i] ?? null;
            $lines_data['packed_discription'][] = $request->bulk_packed_discription[$i] ?? null;

            // batch / serial (JSON fields)
            $lines_data['box_qty'][] = json_encode($boxqty ?? []);
            $lines_data['inventory_status'][] = 0;

            // approval
            $lines_data['approval_status'][] = "INITIATED";

            // foreign keys
            $lines_data['po_hdr_id'][] = $request->bulk_po_hdr_id[$i] ?? null;

            // audit fields
            $lines_data['company_id'][] = session('companyid');
            $lines_data['organization_id'][] = session('organizationid');
            $lines_data['location_id'][] = session('location_id');
            $lines_data['created_by'][] = session('id');
            $lines_data['created_at'][] = now();
            $lines_data['last_updated_by'][] = session('id');
            $lines_data['updated_at'][] = now();
        }


        if ($_POST['qc_number'] == "") {
            $seqno = $this->Seqnoe('QC-', 'p_qc_header_t', '', 'qc_count');
            $data['qc_number'] = $seqno[0];
            $data['qc_count'] = $seqno[1];
        } else {
            $data['qc_number'] = $_POST['qc_number'];
        }

        $data['po_number'] = $_POST['po_number'];

        $id = $this->model->insertRow($data);

        /* Purpose For Notifications*/
        if ($_POST['qc_status'] == "INITIATED") {
            $noti_message = "Purchase Quality" . $id . "Initiated";
            $send_notification = $this->sendPopUpHomeNoty($id, "QUALITY APPROVAL", $noti_message, 'qcapproval');
            \DB::table('notifications_t')->where('reference_source_id', $_POST['grn_number'])->where('reference_source', 'GRN')->update(['read/unread' => 'read']);
        }
         //   dd($lines_data);
        $lid = $this->submodel->subgridSave($lines_data, $id);

        $grnline = \DB::table('p_grn_lines_t')->where('grn_line_id', $_POST['grn_line_id'])->where('product_id', $_POST['bulk_product_id'][0])->update(['qc_status' => '1']);
        $grnid = $_POST['grn_number'];

        $qclinstatus = \DB::select("select * from p_grn_lines_t where qc_status='1' and grn_id='" . $_POST['grn_number'] . "'");
        $qccnt = \DB::select("select * from p_grn_lines_t where grn_id='" . $_POST['grn_number'] . "'");
        $qc1count = count($qclinstatus);

        $qc2count = count($qccnt);

        if ($qc1count == $qc2count) {
            \DB::table('p_grn_hdr_t')->where('grn_id', $grnid)->update(['qcstatus' => '1']);
        } else {

            \DB::table('p_grn_hdr_t')->where('grn_id', $grnid)->update(['qcstatus' => '0']);

        }

        if ($_POST['qc_type'] == "SERIALWISE") {

            $qcline = $_POST['bulk_qc_line_id'];
            $qcline_id = implode(",", $qcline);
            $qcstatus = $_POST['qc_status'];
            $total_qty = $_POST['bulk_total_box_qty'];
            $box_qty = implode(",", $total_qty);
            $product = $_POST['bulk_product_id'];
            $prd_id = implode(",", $product);



            if ($qcstatus == "APPROVED") {

                if ($_POST['approval_status'] == "APPROVED") {

                    // approve mail function start
                    $emp = \Session::get('id');
                    $user_mail = \DB::select("select user_mail,first_name from tb_users where employee_id='$emp'");
                    if (!empty($user_mail) && !empty($user_mail[0]->user_mail)) {
                        $from_umail = $user_mail[0]->user_mail;
                    } else {
                        $from_umail = "aspire@jrkresearch.com";
                    }


                    $pro_name1 = \DB::SELECT("select concatenated_product from m_products_t where product_id='$prd_id'");

                    $created = \DB::SELECT("select grn_number,grn_date,created_by from p_grn_hdr_t where grn_id='$grnid'");
                    $created_at = $created[0]->created_by;
                    $qc_num = \DB::SELECT("select qc_number from p_qc_header_t where grn_number='$grnid'");
                    $qc_number = $qc_num[0]->qc_number;

                    $user_mail1 = \DB::select("select user_mail from tb_users where employee_id='$created_at' and active='Yes'");
                    if (!empty($user_mail1) && !empty($user_mail1[0]->user_mail)) {
                        $created_mail = $user_mail1[0]->user_mail;
                    } else {

                        $created_mail = "aspire@jrkresearch.com";
                    }

                    $man_id = \DB::select("select reporting_manager from hr_employee_t where employee_id='$created_at'");
                    $managr_id = $man_id[0]->reporting_manager;

                    $managr_mail = \DB::select("select user_mail from tb_users where employee_id ='$managr_id' and group_id !='15'");

                    if (!empty($managr_mail) && !empty($managr_mail[0]->user_mail)) {
                        $manr_mail = $managr_mail[0]->user_mail;
                    } else {

                        $manr_mail = "aspire@jrkresearch.com";
                    }

                    $grn_date = $created[0]->grn_date;
                    $grn_no = $created[0]->grn_number;
                    $from_mail = $from_umail;
                    $cr_mail = $created_mail;
                    $man_mail = $manr_mail;
                    $pro_name = $pro_name1[0]->concatenated_product;
                    $sub = "$grn_no APPROVED";
                    $to_mail = "$cr_mail,$man_mail";
                    $emp_name = $user_mail[0]->first_name;
                    $cur_date = date("Y-m-d");
                    $msg = "<p>Dear Team,<br><br>GRN No - $grn_no <br>GRN Date - $grn_date <br>Product Name - $pro_name<br>QC Approve date - $cur_date<br>QC Number -$qc_number<br>Qty - $box_qty<br>Status - $qcstatus<br><br>Regards, <br> $emp_name";

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
                    $po_number = $_POST['po_number'];
                    \DB::update("update p_qc_lines_t SET approval_status='APPROVED' where qc_line_id=" . $qcline_id);
                    $notifcation = 'Quality Approved ' . $po_number . ' Purchase order';
                    $send_notification = $this->sendPopUpHomeNoty($qcline_id, "QC APPROVAL", $notifcation, 'purchaseqc');
                    \DB::table('notifications_t')->where('reference_source_id', $qcline_id)->where('reference_source', 'QUALITY APPROVAL')->update(['read/unread' => 'read']);
                } else {
                    $po_number = $_POST['po_number'];
                    \DB::update("update p_qc_lines_t SET approval_status='REJECTED' where qc_line_id=" . $qcline_id);
                    $notifcation = 'Quality Approved ' . $po_number . ' Purchase order';
                    $send_notification = $this->sendPopUpHomeNoty($qcline_id, "GRN", $notifcation, 'purchaseqc');
                    \DB::table('notifications_t')->where('reference_source_id', $qcline_id)->where('reference_source', 'QUALITY APPROVAL')->update(['read/unread' => 'read']);
                }

                //Update Received Qty In PO Lines
                if ($po_number != "") {
                    $po_data = \DB::SELECT("SELECT sum(qty) as po_qty,pending_qty,received_qty,product_id,status FROM p_po_lines_t where po_hdr_id=$po_number and product_id='$prd_id'");
                    $po_data1 = \DB::SELECT("SELECT sum(pending_qty) as pending_qty,pending_qty,received_qty,product_id,status FROM p_po_lines_t where po_hdr_id=$po_number");
                    if ($_POST['approval_status'] == "APPROVED") {
                        $pending_qty = $po_data[0]->pending_qty;
                        $received_qty = $po_data[0]->received_qty;
                        $pending_updt = $pending_qty - $box_qty;
                        $received_updt = $received_qty + $box_qty;
                        //                    dd($received_updt);
                        $po_qty = $po_data[0]->po_qty;

                        if ($po_data1[0]->pending_qty <= 0) {
                            \DB::update("update p_po_hdr_t set po_status='COMPLETED' where po_hdr_id='" . $po_number . "'");
                        }
                    }
                }
            } else {

                //reject mail function start
                $emp = \Session::get('id');
                $user_mail = \DB::select("select user_mail,first_name from tb_users where employee_id='$emp'");
                if (!empty($user_mail) && !empty($user_mail[0]->user_mail)) {
                    $from_umail = $user_mail[0]->user_mail;
                } else {
                    $from_umail = "aspire@jrkresearch.com";
                }


                $pro_name1 = \DB::SELECT("select concatenated_product from m_products_t where product_id='$prd_id'");

                $created = \DB::SELECT("select grn_number,grn_date,created_by from p_grn_hdr_t where grn_id='$grnid'");
                $created_at = $created[0]->created_by;
                $qc_num = \DB::SELECT("select qc_number from p_qc_header_t where grn_number='$grnid'");
                $qc_number = $qc_num[0]->qc_number;

                $user_mail1 = \DB::select("select user_mail from tb_users where employee_id='$created_at' and active='Yes'");
                if (!empty($user_mail1) && !empty($user_mail1[0]->user_mail)) {
                    $created_mail = $user_mail1[0]->user_mail;
                } else {

                    $created_mail = "aspire@jrkresearch.com";
                }

                $man_id = \DB::select("select reporting_manager from hr_employee_t where employee_id='$created_at'");
                $managr_id = $man_id[0]->reporting_manager;

                $managr_mail = \DB::select("select user_mail from tb_users where employee_id ='$managr_id' and group_id !='15'");

                if (!empty($managr_mail) && !empty($managr_mail[0]->user_mail)) {
                    $manr_mail = $managr_mail[0]->user_mail;
                } else {

                    $manr_mail = "aspire@jrkresearch.com";
                }


                $grn_date = $created[0]->grn_date;
                $grn_no = $created[0]->grn_number;
                $from_mail = $from_umail;
                $cr_mail = $created_mail;
                $man_mail = $manr_mail;
                $pro_name = $pro_name1[0]->concatenated_product;
                $sub = "$grn_no $qcstatus";
                $to_mail = "$cr_mail,$man_mail";
                $emp_name = $user_mail[0]->first_name;
                $cur_date = date("Y-m-d");
                $msg = "<p>Dear Team,<br><br>GRN No - $grn_no <br>GRN Date - $grn_date <br>Product Name - $pro_name<br>QC Approve date - $cur_date<br>QC Number - $qc_number<br>Qty - $box_qty<br>Status - $qcstatus<br><br>Regards, <br> $emp_name";

                // dd($msg);
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

                if ($qcline_id == "") {

                    // Prefer Request over $_POST in Laravel, but keeping your style for minimal change:
                    $productIds = isset($_POST['bulk_product_id']) ? (array) $_POST['bulk_product_id'] : [];
                    $productIds = implode(",", $productIds);

                    if (empty($productIds)) {
                        // handle missing products however you prefer:
                        throw new \RuntimeException('No product selected.');
                    }

                    // Fetch ONE spec row for any of the selected product IDs (use IN, no string concat)
                    $specRow = \DB::select("select i_quality_product_specs_lines_t.*,i_quality_product_specs_hdr_t.product_id 
							from i_quality_product_specs_lines_t 
							left join i_quality_product_specs_hdr_t on (i_quality_product_specs_hdr_t.quality_product_specs_hdr_id=i_quality_product_specs_lines_t.quality_product_specs_hdr_id) 
							where i_quality_product_specs_hdr_t.product_id='" . $productIds . "'");

                    // Helper to safely split CSV strings under PHP 8.2
                    $csvToArray = function ($val) {
                        $s = (string) ($val ?? '');             // coerce NULL to '' to avoid TypeError
                        if ($s === '')
                            return [];
                        return array_values(array_filter(array_map('trim', explode(',', $s)), 'strlen'));
                    };

                    if (!$specRow) {

                        // Option B: proceed with empty/default fields
                        $params = [];
                        $criteria = [];
                        //$measurement   = [];
                        $specFrom = [];
                        $specTo = [];

                    } else {

                        $params = json_encode(explode(",", $specRow[0]->parameter));
                        $criteria = json_encode(explode(",", $specRow[0]->spec_criteria));
                        $specFrom = json_encode(explode(",", $specRow[0]->spec_value_from));
                        $specTo = json_encode(explode(",", $specRow[0]->spec_value_to));
                    }

                    // Build $data1 safely
                    $data1 = [];
                    $data1['parameter'] = json_encode($params);
                    $data1['spec_criteria'] = json_encode($criteria);
                    $data1['spec_value_from'] = json_encode($specFrom);
                    $data1['spec_value_to'] = json_encode($specTo);
                    $data1['quality_status'] = 'Accepted';

                    // serial / batch from POST (use the correct keys; you were decoding serial twice)
                    $serialArr = json_decode($_POST['serialno'] ?? '[]', true) ?: [];
                    $batchArr = json_decode($_POST['batchno'] ?? '[]', true) ?: [];

                    $data1['serial_number'] = $serialArr[0] ?? null;
                    $data1['batch_number'] = $batchArr[0] ?? null;

                    // total box qty (implode over array)
                    $totalbox = isset($_POST['bulk_total_box_qty']) ? (array) $_POST['bulk_total_box_qty'] : [];
                    $data1['box_product_qty'] = implode(',', $totalbox);
                    $data1['reference_source'] = "PURCHASE QC";
                    $data1['product_id'] = $_POST['bulk_product_id'][0];
                    $data1['reference_source_hdr_id'] = $id;
                    $data1['reference_source_line_id'] = $lid['id'][0];

                    $this->qcmodel->insert($data1);


                }
            }


        } else {
                
            // Decode and guard
            $serialno = json_decode($_POST['serialno'] ?? '[]', true) ?: [];
            $batchno = json_decode($_POST['batchno'] ?? '[]', true) ?: [];
            $boxqty = json_decode($_POST['box_product_qty'] ?? '[]', true) ?: [];

            if (!is_array($serialno))
                $serialno = [];
            if (!is_array($batchno))
                $batchno = [];
            if (!is_array($boxqty))
                $boxqty = [];

            // Helper: fetch either "name{$i}" or "name[$i]" safely (array or scalar)
            $safeGet = function (string $base, int $i, $default = []) {
                // suffix style: name0
                $k1 = $base . $i;
                if (array_key_exists($k1, $_POST)) {
                    return $_POST[$k1];
                }
                // bracket style: name[0]
                if (isset($_POST[$base]) && is_array($_POST[$base]) && array_key_exists($i, $_POST[$base])) {
                    return $_POST[$base][$i];
                }
                return $default;
            };

            $check = 0; // "apply to all" is executed once

            $N = count($serialno);
            for ($i = 0; $i < $N; $i++) {
                // Read row-specific arrays (support both naming styles)
                $params = $safeGet('bulk_parameter', $i, []);
                $criteria = $safeGet('bulk_spec_criteria', $i, []);
                $measure = $safeGet('bulk_measurement', $i, []);
                $valFrom = $safeGet('bulk_spec_value_from', $i, []);
                $valTo = $safeGet('bulk_spec_value_to', $i, []);
                $qcstatus = $safeGet('qcstatus', $i, null);

                // "apply to all" checkbox may be suffix or bracket style
                $applyAll = isset($_POST['serialapplicable' . $i])
                    || (isset($_POST['serialapplicable']) && !empty($_POST['serialapplicable'][$i]));

                // Base payload for this row's spec values
                $payloadBase = [
                    'parameter' => json_encode($params, JSON_UNESCAPED_UNICODE),
                    'spec_criteria' => json_encode($criteria, JSON_UNESCAPED_UNICODE),
                    'measurement' => json_encode($measure, JSON_UNESCAPED_UNICODE),
                    'spec_value_from' => json_encode($valFrom, JSON_UNESCAPED_UNICODE),
                    'spec_value_to' => json_encode($valTo, JSON_UNESCAPED_UNICODE),
                    'quality_status' => $qcstatus,
                ];



                if ($applyAll && $check === 0) {
                    // Use the spec values from index $i for all serials
                    foreach ($serialno as $j => $sn) {
                        $row = $payloadBase + [
                            'serial_number' => $sn,
                            'batch_number' => $batchno[$j] ?? null,
                            'box_product_qty' => $boxqty[$j] ?? null,
                        ];

                        $row['reference_source'] = "PURCHASE QC";
                        $row['product_id'] = $_POST['bulk_product_id'][0];
                        $row['reference_source_hdr_id'] = $id;
                        $row['reference_source_line_id'] = $lid['id'][0];


                        $this->qcmodel->insert($row);
                    }
                    $check = 1;
                } elseif ($check === 0) {
                    // Normal per-serial insert for index $i
                    $row = $payloadBase + [
                        'serial_number' => $serialno[$i] ?? null,
                        'batch_number' => $batchno[$i] ?? null,
                        'box_product_qty' => $boxqty[$i] ?? null,
                    ];

                    $row['reference_source'] = "PURCHASE QC";
                    $row['product_id'] = $_POST['bulk_product_id'][0];
                    $row['reference_source_hdr_id'] = $id;
                    $row['reference_source_line_id'] = $lid['id'][0];

                    $this->qcmodel->insert($row);
                }
            }
        }

        /**Auditlog**/
        if ($_POST['qc_header_id'] == "") {
            $action = "create";
        } else {

            $action = "edit";
        }

        $this->auditlog($id, "purchaseqc", $action, $_POST, "p_qc_header_t");

        return response()->json(array('status' => 'success', 'message' => 'Saved Successfully', 'id' => $id, 'lid' => $lid));

    }



    /* purpose:to save status in Spec Trx Table*/
    public function qcapprovalsave(Request $request)
    {
       
        $remarks = $_GET['remarks'];
        $comments = $_GET['comments'];

        if ($_GET['qcstatusd'] == 'Accepted' || $_GET['qcstatusd'] == 'conditionalaccepted') {
            $SQL = \DB::update("update p_quality_spec_trx_lines_t SET quality_status='Accepted',remarks='$remarks',comments='$comments' where quality_spec_trx_id=" . $_GET['pqcline']);

            $qcspeclines = \DB::select('select * from p_quality_spec_trx_lines_t where reference_source_line_id=' . $_GET['qcline']);
            $acceptqty = 0;
            $rejectqty = 0;
            foreach ($qcspeclines as $key => $value) {
                if ($value->quality_status == "Accepted") {
                    $acceptqty = $acceptqty + 1;
                } else {
                    $rejectqty = $rejectqty + 1;
                }

            }
            \DB::update("update p_qc_lines_t SET accept_qty='$acceptqty',reject_qty='$rejectqty' where qc_line_id=" . $qcspeclines[0]->reference_source_line_id);
            return 'Accepted';
        } else {
            $SQL = \DB::update("update p_quality_spec_trx_lines_t SET quality_status='Rejected',remarks='$remarks',comments='$comments' where quality_spec_trx_id=" . $_GET['pqcline']);
            $qcspeclines = \DB::select('select * from p_quality_spec_trx_lines_t where reference_source_line_id=' . $_GET['qcline']);//dd($qcspeclines);
            $acceptqty = 0;
            $rejectqty = 0;
            foreach ($qcspeclines as $key => $value) {
                if ($value->quality_status == "Accepted") {
                    $acceptqty = $acceptqty + 1;
                } else {
                    $rejectqty = $rejectqty + 1;
                }

            }
            \DB::update("update p_qc_lines_t SET accept_qty='$acceptqty',reject_qty='$rejectqty' where qc_line_id=" . $qcspeclines[0]->reference_source_line_id);
            return 'Rejected';
        }
    }

    /* purpose:to save status in lines and Header*/
    public function qcapprovalstatussave(Request $request)
    {
        $po_number = $_GET['po_number'];
        $product_id = $_GET['product_id'];
        $box_qty = $_GET['box_qty'];

        $qcspeclines = \DB::select('select * from p_quality_spec_trx_lines_t where reference_source_line_id=' . $_GET['qcline']);//dd($qcspeclines);
        $accept = 0;
        $reject = 0;
        $keycnt = 0;

        foreach ($qcspeclines as $key => $value) {
            if ($value->quality_status == "Accepted") {
                $accept = $accept + 1;
            } else {
                $reject = $reject + 1;
            }
            $keycnt = $key + 1;
        }
        /*If All Batch Accept*/
        if ($keycnt == $accept) {
            $SQL1 = \DB::update("update p_qc_lines_t SET approval_status='APPROVED' where qc_line_id=" . $qcspeclines[0]->reference_source_line_id);
            $SQL2 = \DB::update("update p_qc_header_t SET qc_status='APPROVED' where qc_header_id=" . $qcspeclines[0]->reference_source_hdr_id);
            $notifcation = 'Quality Approved ' . $po_number . ' Purchase order';
            $send_notification = $this->sendPopUpHomeNoty($qcspeclines[0]->reference_source_line_id, "QUALITY APPROVAL", $notifcation, 'movetoinventory');
            \DB::table('notifications_t')->where('reference_source_id', $qcspeclines[0]->reference_source_line_id)->where('reference_source', 'QUALITY APPROVAL')->update(['read/unread' => 'read']);
        }
        /*If All Batch Reject*/ else if ($keycnt == $reject) {
            $SQL1 = \DB::update("update p_qc_lines_t SET approval_status='REJECTED' where qc_line_id=" . $qcspeclines[0]->reference_source_line_id);
            $SQL2 = \DB::update("update p_qc_header_t SET qc_status='REJECTED' where qc_header_id=" . $qcspeclines[0]->reference_source_hdr_id);
        } else {
            $SQL1 = \DB::update("update p_qc_lines_t SET approval_status='APPROVED' where qc_line_id=" . $qcspeclines[0]->reference_source_line_id);
            $SQL2 = \DB::update("update p_qc_header_t SET qc_status='APPROVED' where qc_header_id=" . $qcspeclines[0]->reference_source_hdr_id);
        }


        //Update Received Qty In PO Lines
        if ($po_number != "") {
            $po_data = \DB::SELECT("SELECT sum(qty) as po_qty,pending_qty,received_qty,product_id,status FROM p_po_lines_t where po_hdr_id=$po_number and product_id='$product_id'");

            if ($value->quality_status == "Accepted") {
                $pending_qty = $po_data[0]->pending_qty;
                $received_qty = $po_data[0]->received_qty;
                $pending_updt = $pending_qty - $box_qty;
                $received_updt = $received_qty + $box_qty;
                $po_qty = $po_data[0]->po_qty;

                $po_data1 = \DB::SELECT("SELECT sum(pending_qty) as pending_qty,received_qty,product_id,status FROM p_po_lines_t where po_hdr_id=$po_number");
                if ($po_data1[0]->pending_qty <= 0)
                    \DB::update("update p_po_hdr_t set po_status='COMPLETED' where po_hdr_id='" . $po_number . "'");

            }
        }

    }

    /*end*/

    public function edit($id = null, $ids = null)
    {
       
        if ($this->data['pageMethod'] == "qcapproval") {
            $this->data['url'] = "qcapproval";
        } else if ($this->data['pageMethod'] == "mrapprovalcreate") {
            $this->data['url'] = "mrapproval";
        } else {
            $this->data['url'] = "purchaseqc";
        }

        $this->data['id'] = $id;
        $table = \DB::table('p_qc_header_t')->where('qc_header_id', $id)->get();
        $this->data['row'] = $table[0];
        $tablelines = \DB::table('p_qc_lines_t')->where('qc_header_id', $id)->get();

        $this->data['linedata'] = $tablelines;
        $this->data['row']->supplier_id = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', $table[0]->supplier_id);
        $this->data['row']->subcontract_supplier_id = $this->jCombo('m_subcontract_supplier_t', 'subcontract_supplier_id', 'subcontract_name', $table[0]->subcontract_supplier_id);
        $this->data['row']->batch_no = $this->jCombocomp('w_productionplan_hdr_t', 'productionplan_hdr_id', 'batch_no', $table[0]->batch_no);
        $this->data['row']->employee_id = $this->jCombo('hr_employee_t', 'employee_id', 'first_name', $table[0]->employee_id);
 
        if ($ids == null) {
            $this->data['row']->po_number = $this->jCombocomp("p_po_hdr_t", "po_hdr_id", "po_number", $table[0]->po_number);
            $this->data['row']->grn_number = $this->jCombocomp("p_grn_hdr_t", "grn_id", "grn_number", $table[0]->grn_number);
        }

        $this->data['qc_status'] = $table[0]->qc_status;

        if ($ids != null) {
            $this->data['row']->po_number = $this->idname("po_number", "p_po_hdr_t", "po_hdr_id", $table[0]->po_number);
            $this->data['row']->grn_number = $this->idname("grn_number", "p_grn_hdr_t", "grn_id", $table[0]->grn_number);
            $this->data['row']->supplier_name = $this->idname('supplier_name', 'm_supplier_t', 'supplier_id', '');
            $this->data['row']->dc_date = $table[0]->dc_date;
        }

        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->grn_line_id = $value->qc_line_id;
                if ($ids == null) {

                    $this->data['qc_type'] = $qctype = $this->productqcttype($value->product_id);
                    $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id);
                    $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                } else {
                    $this->data['qc_type'] = $qctype = $this->productqcttype($value->product_id);
                    $this->data['linedata'][$key]->product_code = $this->idname("product_code", "m_products_t", "product_id", $value->product_id);
                    $this->data['linedata'][$key]->product_id = $this->idname("concatenated_product", "m_products_t", "product_id", $value->product_id);
                    $this->data['linedata'][$key]->uom_code_id = $this->idname("uom_code", "m_uom_codes_t", "uom_code_id", $value->uom_code_id);

                }

                $this->data['linedata'][$key]->qc_type = $qctype;
                $this->data['linedata'][$key]->total_box_qty = $value->total_box_qty;
                $this->data['linedata'][$key]->box_qty = $value->box_qty;
                $this->data['linedata'][$key]->accept_qty = $value->accept_qty;
                $this->data['linedata'][$key]->reject_qty = $value->reject_qty;
                $this->data['linedata'][$key]->reason = $value->reason;
            }
        }
      //  dd($id);
            $qcline = \DB::table('p_quality_spec_trx_lines_t')
                        ->where('reference_source_hdr_id', $id)
                        ->first();

            $this->data['qclinedata'] = $qcline;

            $this->data['spec']          = json_decode($qcline->spec_criteria ?? '');
            $this->data['specvaluefrom'] = json_decode($qcline->spec_value_from ?? '');
            $this->data['specvalueto']   = json_decode($qcline->spec_value_to ?? '');
            $this->data['param']         = json_decode($qcline->parameter ?? '');
            $this->data['measure']       = json_decode($qcline->measurement ?? '');
            $this->data['remarks']       = $qcline->remarks ?? '';
            $this->data['comments']      = $qcline->comments ?? '';


        if ($ids == null) {

            return view('purchaseqc.form', $this->data);
        } else {
            return view('purchaseqc.view', $this->data);
        }

    }


    public function productqcspecdetails($pid = null)
    {
        $speclines = \DB::select('
        select qph.quality_product_specs_hdr_id,
               qpl.quality_product_specs_line_id,
               qph.product_id,
               qpl.parameter,
               qpl.spec_criteria,
               qpl.spec_value_from,
               qpl.spec_value_to
          from i_quality_product_specs_hdr_t as qph
          left join i_quality_product_specs_lines_t qpl
                 on (qph.quality_product_specs_hdr_id = qpl.quality_product_specs_hdr_id)
         where qph.product_id = ' . (int) $pid . '
    ');

        $grnlineid = (int) ($_GET['line_id'] ?? 0);     // line_id
        $serial = (int) ($_GET['serial'] ?? 0);
        $batch = ($_GET['batch'] ?? '');
        $source = ($_GET['source'] ?? '');

        $html = '';

        // header
        $html .= '<div class="bg-primary modal-header serial serial' . $serial . '">';
        $html .= '  <h5 class="modal-title text-white">Quality Check ( ' . e($batch) . ' )</h5>';
        $html .= '  <button type="button" class="btn-close cancelqc" data-bs-dismiss="modal" aria-label="Close"></button>';
        $html .= '</div>';

        // IMPORTANT: DO NOT add another <div class="modal-body"> here!
        // because your blade already has modal-body.

        // hidden helpers for JS
        $html .= '<input type="hidden" class="qcdata" value="' . $serial . '">';
        $html .= '<input type="hidden" class="qc_line_id" value="' . $grnlineid . '">';

        // table
        $html .= '<table class="table table-sm align-middle serial serial' . $serial . '">
        <thead>
            <tr style="background-color:#05234e;color:#fff;">
                <th scope="col" style="width:68px;">No</th>
                <th scope="col">Parameter</th>
                <th scope="col">Specification Criteria</th>
                <th scope="col">Spec Value From</th>
                <th scope="col">Spec Value To</th>
                <th scope="col">Measurement</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>';

        if ($source != 'qcapproval') {

            $productqc = $this->productqcttype($pid);   // "BATCHWISE" or something else
            $html .= '<input type="hidden" class="qc_type' . $serial . '" value="' . e($productqc) . '">';

            if (count($speclines) > 0) {

                foreach ($speclines as $key => $val) {

                    $spec = \DB::select('
                    select * from a_lookuplines_t
                     where lookup_type="SPEC_CRITERIA"
                       and lookuplines_id=' . (int) $val->spec_criteria . '
                ');

                    $specCode = $spec[0]->lookup_code ?? '';

                    $html .= '<tr class="table' . $key . '">';

                    // no
                    $html .= '<td>
                    <input type="text" name="line_no[]" class="form-control form-control-sm s_no"
                           value="' . ($key + 1) . '" readonly
                           style="width:68px !important; color:black;">
                </td>';

                    // parameter
                    $html .= '<td>
                    <input type="text" name="bulk_parameter' . $serial . '[' . $key . ']"
                           class="form-control form-control-sm bulk_parameter' . $serial . $key . '"
                           value="' . e($val->parameter) . '" readonly
                           style="max-width: 220px; color:black;">
                </td>';

                    // criteria
                    $html .= '<td>
                    <input type="text" name="bulk_spec_criteria' . $serial . '[' . $key . ']"
                           class="form-control form-control-sm bulk_spec_criteria' . $serial . $key . '"
                           value="' . e($specCode) . '" readonly
                           style="max-width: 220px; color:black;">
                </td>';

                    // from
                    $html .= '<td>
                    <input type="text" name="bulk_spec_value_from' . $serial . '[' . $key . ']"
                           class="form-control form-control-sm bulk_spec_value_from' . $serial . $key . '"
                           value="' . e($val->spec_value_from) . '" readonly
                           style="max-width: 140px; color:black;">
                </td>';

                    // to
                    $html .= '<td>
                    <input type="text"  name="bulk_spec_value_to' . $serial . '[' . $key . ']"
                           class="form-control form-control-sm bulk_spec_value_to' . $serial . $key . '"
                           value="' . e($val->spec_value_to) . '" readonly
                           style="max-width: 140px; color:black;">
                </td>';

                    // measurement (IMPORTANT: add data-serial + data-key for cache)
                    if ($specCode == "PASS/FAIL") {
                        $html .= '<td>
                        <select name="bulk_measurement' . $serial . '[' . $key . ']"
                            class="form-select form-select-sm measure bulk_measurement' . $serial . ' bulk_measurement' . $serial . $key . '"
                            data-serial="' . $serial . '"
                            data-key="' . $key . '">
                            <option value="">--Please Select--</option>
                            <option value="1">Pass</option>
                            <option value="2">Fail</option>
                        </select>
                    </td>';
                    } else {
                        $html .= '<td>
                        <input type="text" name="bulk_measurement' . $serial . '[' . $key . ']"
                               class="form-control form-control-sm measure bulk_measurement' . $serial . ' bulk_measurement' . $serial . $key . '"
                               data-serial="' . $serial . '"
                               data-key="' . $key . '"
                               value=""
                               style="max-width: 220px; color:black;">
                    </td>';
                    }

                    $html .= '<td></td></tr>';
                }

                $html .= '</tbody></table>';

                // radios (keep your original naming)
                $html .= '<div class="form-group text-center foot serial serial' . $serial . '">
                <div class="d-inline-block me-3 form-check form-check-inline">
                  <input name="qcstatus' . $serial . '" class="form-check-input pidsa' . $serial . '" type="radio"
                         id="qcaccepted' . $serial . '"
                         value="Accepted" checked>
                  <label class="form-check-label" for="qcaccepted' . $serial . '">Accepted</label>
                </div>
                <div class="d-inline-block me-3 form-check form-check-inline">
                  <input name="qcstatus' . $serial . '" class="form-check-input pidsr' . $serial . '" type="radio"
                         id="qcrejected' . $serial . '"
                         value="Rejected">
                  <label class="form-check-label" for="qcrejected' . $serial . '">Rejected</label>
                </div>';

                if ($productqc == 'BATCHWISE') {
                    $html .= '<div class="d-inline-block ms-3 form-check form-check-inline">
                    <input  class="form-check-input applicable" type="checkbox"
                           id="serialapplicable' . $serial . '" name="serialapplicable' . $serial . '"
                           value="1">
                    <label class="form-check-label" for="serialapplicable' . $serial . '">
                      Same as Applicable for Serial
                    </label>
                </div>';
                }

                $html .= '</div>';

                // ✅ STATUS DISPLAY (this is what your JS will update)
                $html .= '<div class="text-center mt-2">
                <strong>Status:</strong>
                <span class="status_val status_val' . $serial . '">Pending</span>
            </div>';

            } else {
                
                $html .= '<tr>
                <td colspan="7">
                  <p class="mb-0">There is no Product Specification for this product.';
                $url = \URL::to('productspec/' . $pid . '/0');
                $html .= ' <a href="' . $url . '" target="_blank" class="ms-1">Create Specification</a>
                  </p>
                </td>
            </tr></tbody></table>';
            }

        } else {
            // qcapproval view (your original)
            //dd($pid);
            $qctrx = \DB::select('select * from p_quality_spec_trx_lines_t where reference_source_hdr_id=' . (int) $pid);

            $spec = json_decode($qctrx[0]->spec_criteria, true) ?? [];
            $specvaluefrom = json_decode($qctrx[0]->spec_value_from, true) ?? [];
            $specvalueto = json_decode($qctrx[0]->spec_value_to, true) ?? [];
            $param = json_decode($qctrx[0]->parameter, true) ?? [];
            $measure = json_decode($qctrx[0]->measurement, true) ?? [];
            $qc_status = $qctrx[0]->quality_status ?? '';
            $spectrxid = $qctrx[0]->quality_spec_trx_id; // don't json_decode id

            $qcremarks = $this->jcustomselecttool(
                "a_lookuplines_t",
                "lookuplines_id",
                "lookup_code",
                "",
                "and lookup_type='PURCHASEQC_REMARKS'"
            );

            foreach ($param as $key => $v) {
                $html .= '<tr class="table' . $key . '">';
                $html .= '<td><input name="line_no[]" type="text" class="form-control form-control-sm s_no"
                       value="' . ($key + 1) . '" readonly style="width:68px !important; color:black;"></td>';

                $html .= '<td><input name="bulk_parameter[' . $key . ']" type="text" class="form-control form-control-sm"
                       value="' . e($v) . '" readonly style="max-width:220px; color:black;"></td>';

                $html .= '<td><input name="bulk_spec_criteria' . $serial . '[' . $key . ']" type="text" class="form-control form-control-sm"
                       value="' . e($spec[$key] ?? '') . '" readonly style="max-width:220px; color:black;"></td>';

                $html .= '<td><input name="bulk_spec_value_from[' . $key . ']" type="text" class="form-control form-control-sm"
                       value="' . e($specvaluefrom[$key] ?? '') . '" readonly style="max-width:140px; color:black;"></td>';

                $html .= '<td><input name="bulk_spec_value_to[' . $key . ']" type="text" class="form-control form-control-sm"
                       value="' . e($specvalueto[$key] ?? '') . '" readonly style="max-width:140px; color:black;"></td>';

                if (($spec[$key] ?? '') == "PASS/FAIL") {
                    $selected = ((int) ($measure[$key] ?? 0) === 1) ? 'selected' : '';
                    $selected1 = ((int) ($measure[$key] ?? 0) === 2) ? 'selected' : '';
                    $html .= '<td>
                    <select name="bulk_measurement[' . $key . ']" class="form-select form-select-sm" style="pointer-events:none;" tabindex="-1">
                        <option value="">--Please Select--</option>
                        <option ' . $selected . ' value="1">Pass</option>
                        <option ' . $selected1 . ' value="2">Fail</option>
                    </select>
                </td>';
                } else {
                    $html .= '<td><input name="bulk_measurement[' . $key . ']" type="text" class="form-control form-control-sm ddddd"
                      value="'.$measure[$key].'" readonly style="max-width:220px; color:black;"></td>';
                }

                $html .= '</tr>';
            }

            $accepted = ($qc_status == "Accepted") ? "checked" : "";
            $reject = ($qc_status != "Accepted") ? "checked" : "";

            $html .= '</tbody></table>';

            $html .= '<div class="form-group text-center foot serial serial' . $serial . '">
            <div class="form-check form-check-inline">
              <input  class="form-check-input" type="radio" name="qcstatus" value="Accepted" ' . $accepted . '>
              <label class="form-check-label">Accepted</label>
            </div>
            <div class="form-check form-check-inline">
              <input  class="form-check-input" type="radio" name="qcstatus" value="Rejected" ' . $reject . '>
              <label class="form-check-label">Rejected</label>
            </div>
            <div class="form-check form-check-inline">
              <input  class="form-check-input" type="radio" name="qcstatus" value="conditionalaccepted">
              <label class="form-check-label">Conditional Acceptance</label>
            </div>
        </div>';

            $html .= '<input type="hidden" name="quality_spec_trx_id" class="quality_spec_trx_id" value="' . e($spectrxid) . '">';

            $html .= '<div class="row g-3">
            <div class="col-md-6">
              <div class="row align-items-center">
                <label class="col-md-4 col-form-label">Comments</label>
                <div class="col-md-8">
                  <input name="comments" type="text" class="form-control form-control-sm comments" required>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row align-items-center">
                <label class="col-md-4 col-form-label">Remarks</label>
                <div class="col-md-8">
                  <select name="remarks" class="form-select form-select-sm remarks">' . $qcremarks . '</select>
                 
                </div>
              </div>
            </div>
        </div>';

            // status display also
            $html .= '<div class="text-center mt-2">
            <strong>Status:</strong>
            <span class="status_val status_val' . $serial . '">' . e($qc_status ?: 'Pending') . '</span>
        </div>';
        }

        return $html;
    }





    public function update(Request $request, purchaseqc $purchaseqc)
    {
        //
    }


    function findPrimarykey($table)
    {
        $primaryKey = '';
        foreach (\DB::select("show columns from " . $table . " where extra like '%auto_increment%'") as $key) {
            $primaryKey = $key->Field;
        }
        return $primaryKey;
    }

    public function MRData()
    {

        $wh = '';
        $search_tables = [];
        $search_tables[] = 'm_products_t';
        $search_tables[] = 'hr_employee_t';
        $search_tables[] = 'w_productionplan_hdr_t';
        if ($_GET['_search'] == 'true') {
            $wh = $this->jqgridsearch('qc_materialreq_hdr_t', $_GET['filters'], $search_tables);

        }
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if (!$sidx)
            $sidx = 1;
        $result = \DB::select("SELECT COUNT(qc_materialreq_lines_t.qc_material_req_id) AS count FROM qc_materialreq_lines_t left join qc_materialreq_hdr_t on (qc_materialreq_hdr_t.qc_material_req_id=qc_materialreq_lines_t.qc_material_req_id)
            left join m_products_t on m_products_t.product_id =qc_materialreq_lines_t.product_id 
            left join w_productionplan_hdr_t on w_productionplan_hdr_t.productionplan_hdr_id =qc_materialreq_hdr_t.batch_no 
            left join hr_employee_t on hr_employee_t.employee_id =qc_materialreq_hdr_t.employee_id  where 1=1 $wh");
        $count = $result[0]->count;
        if ($count > 0 && $limit > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages)
            $page = $total_pages;
        $start = $limit * $page - $limit;
        if ($start < 0)
            $start = 0;
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $SQL = "SELECT qc_materialreq_hdr_t.qc_material_req_id as qc_material_req_id, w_productionplan_hdr_t.batch_no as batch_no, qc_materialreq_hdr_t.start_date as start_date, qc_materialreq_hdr_t.end_date as end_date, hr_employee_t.first_name as employee_id, m_products_t.concatenated_product as product_name, qc_materialreq_lines_t.product_id as product_id
            FROM qc_materialreq_lines_t 
            left join qc_materialreq_hdr_t on (qc_materialreq_hdr_t.qc_material_req_id=qc_materialreq_lines_t.qc_material_req_id)
            left join m_products_t on m_products_t.product_id =qc_materialreq_lines_t.product_id 
            left join w_productionplan_hdr_t on w_productionplan_hdr_t.productionplan_hdr_id =qc_materialreq_hdr_t.batch_no 
            left join hr_employee_t on hr_employee_t.employee_id =qc_materialreq_hdr_t.employee_id 
            where 1=1 and qc_materialreq_hdr_t.company_id=$compy and qc_materialreq_hdr_t.location_id=$loc $wh 
            ORDER BY $sidx $sord LIMIT $start , $limit";

        $result = \DB::select($SQL);
        // dd($result);        
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }

    public function mrqctable()
    {
        $this->data['suppliername'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        $this->data['batch_no'] = $this->jqgridselect('w_productionplan_hdr_t', 'productionplan_hdr_id', 'batch_no');
        $this->data['employee_id'] = $this->jqgridselect('hr_employee_t', 'employee_id', 'first_name');
        $this->data['product_name'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');
        $this->data['pageMethod'] = "qualitymr";
        return view("purchaseqc.grn_table", $this->data);
    }



    public function productqcttype($pid)
    {
        $productqc = \DB::select('select qc_type from m_products_t where product_id=' . $pid);
        return $productqc[0]->qc_type;
    }


    function productqcserialdetails($id = null)
    {

        $qctrxlines = \DB::select("select * from p_quality_spec_trx_lines_t where reference_source_line_id='$id'");
        foreach ($qctrxlines as $key1 => $value1) {
            $data['serialno'][] = $value1->serial_number;
            $data['batchno'][] = $value1->batch_number;
            $data['quality_status'][] = $value1->quality_status;
            $data['id'][] = $value1->quality_spec_trx_id;

        }
        return $data;
    }
}
