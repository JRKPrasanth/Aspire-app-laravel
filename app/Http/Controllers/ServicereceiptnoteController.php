<?php

namespace App\Http\Controllers;
use App\goodsreceiptnote;
use Mail;
use App\grnlines;
use App\Purchaseorderlines;
use App\Product;
use App\uomcodes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class ServicereceiptnoteController extends Controller
{
    public $module = "srn";
    public function __construct()
    {
        $this->data = array();

        $this->model = new Goodsreceiptnote;
        $this->submodel = new GrnLines;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageModule'] = 'grn';
        $this->table = "p_grn_hdr_t";
        $this->subtable = "p_grn_lines_t";
        $this->middleware('auth');
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
        $this->data['pageMethod'] = "srn";
        return view("srn.table", $this->data);
    }

    /*  purpose for Display GRN Data in JQgrid function */
    public function getSrnData()
    {

        $wh = '';

        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');

        $SQL = "select * from (SELECT p_grn_hdr_t.grn_id AS grn_id,
                    p_grn_hdr_t.grn_number AS grn_number,
                    p_grn_hdr_t.grn_status AS grn_status,
                    p_grn_hdr_t.dc_number AS dc_number,
                    p_grn_hdr_t.source,
                    p_grn_hdr_t.dc_date AS dc_date,
                    p_po_hdr_t.po_number AS po_number,
                    p_po_hdr_t.po_date AS po_date,
                    m_supplier_t.supplier_name,
                    m_subcontract_supplier_t.subcontract_name,
                    p_po_invoice_hdr_t.po_invoice_status,
					p_grn_hdr_t.company_id,
					p_grn_hdr_t.location_id
                    FROM `p_grn_hdr_t` LEFT JOIN p_po_hdr_t ON(p_po_hdr_t.po_hdr_id = p_grn_hdr_t.`po_number`)
                    LEFT JOIN m_supplier_t ON(m_supplier_t.supplier_id = p_grn_hdr_t.`supplier_id`)
                    LEFT JOIN m_subcontract_supplier_t ON(m_subcontract_supplier_t.subcontract_supplier_id = p_grn_hdr_t.`subcontract_supplier_id`)
                    LEFT JOIN p_po_invoice_hdr_t ON(p_po_invoice_hdr_t.grn_number = p_grn_hdr_t.`grn_id`) 
                    WHERE 1 = 1 and p_grn_hdr_t.source='LABOURPO')v1 where 1=1 $wh GROUP BY v1.grn_id ORDER BY v1.grn_id DESC";


        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);
    }


    /* Purpose For :Create From PO Index Function to Call Table Blade*/
    public function purchaselabourtable()
    {

        $this->data['suppliername'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        $this->data['pageMethod'] = "purchaselabourtable";
        return view("srn.purchaselabourtable", $this->data);
    }

    /*  purpose for Display PO Data in JQgrid function */
    public function getPurchaselabourData()
    {
        $wh = '';
        $loc = "1";
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');
        if ($groupname == '1' || $groupname == 'Admin') {
            $wh .= 'and  p_po_hdr_t.company_id=' . $compy;
        } else {
            $wh .= 'and  p_po_hdr_t.company_id=' . $compy . ' and p_po_hdr_t.location_id=' . $loc;
        }

        $SQL = "SELECT
                        p_po_hdr_t.po_hdr_id as po_hdr_id,
                        p_po_hdr_t.po_number as po_number,
                        p_po_hdr_t.po_date as po_date,
                        p_po_hdr_t.po_type as po_type,
                        p_po_hdr_t.po_grand_total as po_grand_total,
                        p_po_hdr_t.remarks as remarks,
                        p_po_hdr_t.po_status as status,
                        m_supplier_t.supplier_name
                        FROM `p_po_hdr_t`
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_po_hdr_t.`supplier_id`) where 1=1 $wh and p_po_hdr_t.po_type='LABOUR' and p_po_hdr_t.po_status='APPROVED' and p_po_hdr_t.need_to_close=0 and (p_po_hdr_t.amendment_status!=1 OR amendment_status IS NULL) ORDER BY p_po_hdr_t.po_hdr_id DESC";

        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);
    }


    /* Purpose for Create Function*/
    public function create($id = null, $idss = null)
    {
        //get from PO Table

        $this->data['id'] = $id;
        $table = \DB::table('p_po_hdr_t')->where('po_hdr_id', $id)->get();
        $this->data['row'] = $table[0];
        $tablelines = \DB::table('p_po_lines_t')->where('po_hdr_id', $id)->where('status', '0')->get();

        $this->data['row']->grn_id = "";
        $this->data['row']->grn_number = "";
        $this->data['row']->grn_status = "DRAFT";
        $this->data['row']->grn_description = "";
        $this->data['row']->reference_id = $id;
        $this->data['row']->reference_number = $table[0]->po_number;
        $this->data['row']->source = "LABOURPO";
        $this->data['row']->dc_number = "";
        $this->data['row']->dc_date = date('Y-m-d');
        $this->data['row']->grn_date = date('Y-m-d');
        $this->data['row']->bill_date = "";
        $this->data['row']->bill_due_date = "";
        $this->data['row']->gin_id = "";
        $this->data['row']->total_packs = "";
        $this->data['row']->quality_check = "No";
        $this->data['row']->supplier_type = "SUPPLIER";
        $this->data['idss'] = "";


        $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', $table[0]->supplier_id);
        $this->data['po_number'] = $this->jCombocomp('p_po_hdr_t', 'po_hdr_id', 'po_number', $table[0]->po_hdr_id);
        $this->data['linedata'] = $tablelines;
        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->grn_line_id = "";
                $product = $value->product_id;
                $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                if ($value->uom_code_id != "") {
                    $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id, 'and uom_code_id=' . $value->uom_code_id);
                }
            }
        }

        return view('srn.form', $this->data);

    }
    /* Purpose for Edit Function*/
    public function edit($id = null, $ids = null)
    {

        $this->data['id'] = $id;
        $table = \DB::table('p_grn_hdr_t')->where('grn_id', $id)->get();

        $this->data['row'] = $table[0];
        $tablelines = \DB::table('p_grn_lines_t')->where('grn_id', $id)->get();
        $this->data['linedata'] = $tablelines;

        $this->data['row']->po_hdr_id = $table[0]->po_number;

        $this->data['po_number'] = $this->jCombocomp('p_po_hdr_t', 'po_hdr_id', 'po_number', $table[0]->po_number);
        $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', $table[0]->supplier_id);
        $this->data['subcontract_supplier_id'] = $this->jCombo('m_subcontract_supplier_t', 'subcontract_supplier_id', 'subcontract_number|subcontract_name', $table[0]->subcontract_supplier_id);

        $this->data['po_number_name'] = $this->idname('po_number', 'p_po_hdr_t', 'po_hdr_id', $table[0]->po_number);
        $this->data['supplier_name'] = $this->idname('supplier_name', 'm_supplier_t', 'supplier_id', $table[0]->supplier_id);
        $this->data['grn_status'] = $table[0]->grn_status;
        $this->data['grn_date'] = $table[0]->grn_date;
        $this->data['row']->reference_id = $table[0]->reference_id;
        $this->data['row']->reference_number = $table[0]->reference_number;
        $this->data['row']->source = $table[0]->source;
        $this->data['row']->total_packs = $table[0]->total_packs;
        $this->data['row']->gin_id = $gin_id = $table[0]->gin_number;
        $dc_date = $table[0]->dc_date;
        $grn_date = $table[0]->grn_date;
        $this->data['row']->dc_date = date(\Session::get('p_date_format'), strtotime($dc_date));
        $this->data['row']->grn_date = date(\Session::get('p_date_format'), strtotime($grn_date));
        $po_date = $table[0]->po_date;
        $this->data['row']->po_date = date(\Session::get('p_date_format'), strtotime($po_date));
        $gin_number = $table[0]->gin_number;
        if ($gin_number != "") {
            //gin
            $this->data['idss'] = $idss = "2";

            $this->data['row']->gin_id = $this->jCombocomp('p_gin_hdr_t', 'p_gin_hdr_id', 'gin_number', $gin_number);
            $this->data['row']->supplier_id = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', $table[0]->supplier_id);

        } else {
            //po
            $this->data['idss'] = $idss = "";

        }

        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                //				dd($value);
                $this->data['linedata'][$key]->grn_line_id = $value->grn_line_id;
                if ($ids == null) {


                    $product = $value->product_id;
                    $po_data = \DB::select("select sum(p_po_invoice_lines_t.accept_qty) as receive_qty from p_po_invoice_hdr_t left join p_po_invoice_lines_t on p_po_invoice_lines_t.po_invoice_id=p_po_invoice_hdr_t.po_invoice_id  where p_po_invoice_hdr_t.po_number='" . $table[0]->po_number . "' and p_po_invoice_lines_t.product_id='$product'");
                    $gin_data = \DB::select("select * from p_gin_lines_t where p_gin_hdr_id='$gin_id' and product_id='$product'");


                    $check = $po_data[0]->receive_qty;
                    $qty = $value->qty;
                    if (!empty($check)) {
                        $receive_qty = $po_data[0]->receive_qty;
                        $remaining_qty = $qty - $receive_qty;
                    } else {

                        $receive_qty = "0";
                        $remaining_qty = $qty;
                    }
                    $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id);
                    $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                    $this->data['linedata'][$key]->qty = $qty;
                    $this->data['linedata'][$key]->product_description = $value->packed_discription;
                    $this->data['linedata'][$key]->arrived_qty = $receive_qty;
                    $this->data['linedata'][$key]->receive_qty = $value->receive_qty;
                    $this->data['linedata'][$key]->remaining_qty = $remaining_qty;
                    $this->data['linedata'][$key]->gin_qty = '';
if ($value->box_product != "0" && $value->box_qty != "0.0") {

    $boxProduct = json_decode($value->box_product, true);
    $batchNumber = json_decode($value->batch_number, true);
    $serialNumber = json_decode($value->serial_number, true);

    $this->data['linedata'][$key]->box_product =
        is_array($boxProduct) ? implode(',', $boxProduct) : $value->box_product;

    $this->data['linedata'][$key]->batch_number =
        is_array($batchNumber) ? implode(',', $batchNumber) : $value->batch_number;

    $this->data['linedata'][$key]->serial_number =
        is_array($serialNumber) ? implode(',', $serialNumber) : $value->serial_number;
}


                } else {
                    $product = $value->product_id;
                    $po_data = \DB::select("select sum(p_po_invoice_lines_t.accept_qty) as receive_qty from p_po_invoice_hdr_t left join p_po_invoice_lines_t ON p_po_invoice_hdr_t.po_invoice_id=p_po_invoice_lines_t.po_invoice_id where p_po_invoice_hdr_t.po_number='" . $table[0]->po_number . "' and p_po_invoice_lines_t.product_id='$product'");
                    $check = $po_data[0]->receive_qty;
                    $qty = $value->qty;
                    if (!empty($check)) {
                        $receive_qty = $po_data[0]->receive_qty;
                        $remaining_qty = $qty - $receive_qty;
                    } else {
                        $receive_qty = "0";
                        $remaining_qty = $qty;
                    }

                    $this->data['linedata'][$key]->arrived_qty = $receive_qty;
                    $this->data['linedata'][$key]->remaining_qty = $remaining_qty;
                    if ($value->box_product != "0" && $value->box_qty != "0.0") {
                        $this->data['linedata'][$key]->box_product = implode(',', json_decode($value->box_product));
                        $this->data['linedata'][$key]->batch_number = implode(',', json_decode($value->batch_number));
                        $this->data['linedata'][$key]->serial_number = implode(',', json_decode($value->serial_number));
                    }
                    $this->data['linedata'][$key]->product_code = $this->idname("product_code", "m_products_t", "product_id", $value->product_id);
                    $this->data['linedata'][$key]->product_id = $this->idname("concatenated_product", "m_products_t", "product_id", $value->product_id);
                    $this->data['linedata'][$key]->uom_code_id = $this->idname("uom_code", "m_uom_codes_t", "uom_code_id", $value->uom_code_id);
                }
                $this->data['linedata'][$key]->receive_qty = $value->receive_qty;

                $this->data['linedata'][$key]->qty = $value->qty;

            }
        }


        return view('srn.form', $this->data);

    }
	
    /* Purpose for Save Function*/
    public function save(Request $request)
    {


			$id='';
			$form = $request->all();
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
			$lines_data = $this->validatePost($form, $this->subtable, 'lines');

        if ($_POST['grn_number'] == "") {
            $seqno = $this->Seqnoe('SRN-', 'p_grn_hdr_t', '', 'srn_count');
            $data['grn_number'] = $seqno[0];
            $data['srn_count'] = $seqno[1];
        } else {
            $seqno[0] = $_POST['grn_number'];
        }

        \DB::beginTransaction();
        try {
            if (isset($_POST['gin_id'])) {
                $data['gin_number'] = $_POST['gin_id'];
            }

            $id = $this->model->insertRow($data);
            $lid = $this->submodel->subgridSave($lines_data, $id);
            $sql = (object) array();

            foreach ($_POST['bulk_product_id'] as $key => $value) {
                if ($value != "") {
                    $sql = \DB::SELECT("select m_products_t.product_group_id,m_products_t.product_category_id from m_products_t where product_id=" . $value);
                    if (count($sql) > 0) {
                        $category = \DB::table('m_qcapproval_settings_t')->select('product_group_id', 'product_category_id')->where('product_group_id', $sql[0]->product_group_id)->get();
                        if (!empty($category[0]->product_category_id) && $category[0]->product_category_id != "9999") {
                            $query = \DB::table('m_qcapproval_settings_t')->select('qc_approver_id', 'qc_checker_id', 'qcapproval_id')->where('product_group_id', $sql[0]->product_group_id)->where('product_category_id', $sql[0]->product_category_id)->get();
                        } else {
                            $query = \DB::table('m_qcapproval_settings_t')->select('qc_approver_id', 'qc_checker_id', 'qcapproval_id')->where('product_group_id', $sql[0]->product_group_id)->get();
                        }
                        if (count($query) > 0) {
                            \DB::update("update p_grn_hdr_t set approver_id = '" . $query[0]->qc_approver_id . "',checker_id= '" . $query[0]->qc_checker_id . "' where grn_id=" . $id);
                        }
                    }
                }
            }

            if (isset($_POST['gin_id'])) {
                if ($_POST['gin_id'] != '') {
                    \DB::update("update p_gin_hdr_t set grn_status='1' where p_gin_hdr_id='" . $_POST['gin_id'] . "'");
                }
            }
            $poid = $_POST['po_number'];
            \DB::update("update p_po_hdr_t set grn_status='1' where po_hdr_id='" . $poid . "'");
            \DB::update("update p_grn_lines_t set inventory_status=1 where grn_id='" . $id . "'");

            if ($_POST['grn_status'] == "INITIATED") {
                $send_notification = $this->sendPopUpHomeNoty($id, "GRN", 'Material Inward', 'grn');
                \DB::table('notifications_t')->where('reference_source_id', $_POST['po_number'])->where('reference_source', 'PO APPROVED')->update(['read/unread' => 'read']);
            }

            /* Purpose For Journal Entry Insert*/
            if ($poid != "") {

                if ($_POST['grn_status'] == "INITIATED") {

                    $grn_no = $data['grn_number'];
                    $dcdate = $_POST['dc_date'];
                    $org = \Session::get('organization');
                    $loc = \Session::get('location');
                    $compy = \Session::get('companyid');
                    /*Journal Header Insert*/
                    $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$grn_no','SRN','$dcdate','$id','APPROVED','$compy','$loc','$org')");
                    $jid = DB::getPdo()->lastInsertId();
                    $po_details = \DB::table('p_po_lines_t')->join('m_products_t', 'm_products_t.product_id', '=', 'p_po_lines_t.product_id')->join('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'p_po_lines_t.tax_group_id')->select('p_po_lines_t.*', 'm_products_t.tax_credit', 'm_products_t.account_code_id', 'm_products_t.disc_account_code', 'm_products_t.control_account_id', 'm_tax_group_t.display_name')->where("p_po_lines_t.po_hdr_id", $_POST['po_number'])->get();
                    $collection = collect($po_details);
                    $srn_acc = \DB::table('f_account_setting_t')->where('module_name', 'srn')->get();
                    $journal_lines_data = array();
                    $total = 0;
                    foreach ($_POST['bulk_product_id'] as $tkey => $tvalue) {
                        $filtered = $collection->where('product_id', $tvalue);
                        $filtered->all();
                        foreach ($filtered as $val) {
                            $filtered = array();
                            $filtered[] = $val;
                            break;
                        }

                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $_POST['dc_date'];
                        $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
                        $journal_lines_data[$tkey]['reference_id'] = $tvalue;
                        $journal_lines_data[$tkey]['product_qty'] = $_POST['bulk_receive_qty'][$tkey];
                        $journal_lines_data[$tkey]['account_id'] = $filtered[0]->account_code_id;
                        $rate = $filtered[0]->unit_price - (($filtered[0]->unit_price * $filtered[0]->discount_percentage) / 100);
                        $rate = $rate * $_POST['bulk_receive_qty'][$tkey];
                        if ($filtered[0]->tax_credit != "Yes") {
                            $taxval = (float) $filtered[0]->display_name;
                            $rate = $rate + (($rate * $taxval) / 100);
                        }



                        $total += $journal_lines_data[$tkey]['debit_amount'] = $rate;
                        $journal_lines_data[$tkey]['credit_amount'] = '';
                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                        ;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

                    }
                    $tkey++;
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['dc_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "";
                    $journal_lines_data[$tkey]['reference_id'] = '';
                    $journal_lines_data[$tkey]['product_qty'] = '';
                    $journal_lines_data[$tkey]['account_id'] = $srn_acc[0]->service_account_id;
                    $journal_lines_data[$tkey]['debit_amount'] = '';
                    $journal_lines_data[$tkey]['credit_amount'] = $total;
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');

                    \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);


                }
            }
            /*End*/

            \DB::commit();
            /**Auditlog**/
            if ($_POST['grn_id'] == "") {
                $action = "create";
            } else {
                $action = "edit";
            }
            $this->auditlog($id, "grn", $action, $_POST, "p_grn_hdr_t");
            return response()->json(array('status' => 'success', 'message' => 'Saved Successfully', 'id' => $id, 'lid' => $lid, 'auto_no' => $seqno[0]));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            dd($message);
            \DB::rollback();

            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }

    /*  :purpose for check duplicate entry*/
    public function Checkname(Request $request)
    {

        $edit_id = $_REQUEST['edit_id'];
        if ($edit_id == '')
            $grn = \DB::table('p_grn_hdr_t')->where('dc_number', $_REQUEST['dc_number'])->where('supplier_id', $_REQUEST['supplier_id'])->get();
        else {
            $whereData = [['dc_number', $_REQUEST['dc_number']], ['grn_id', '!=', $edit_id]];

            $grn = \DB::table('p_grn_hdr_t')->where($whereData)->get();
        }


        if (count($grn) > 0)
            return 1;
        else
            return 0;
    }
    /*end*/
}
