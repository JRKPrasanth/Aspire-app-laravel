<?php
namespace App\Http\Controllers;
use App\Movetoinventory;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;

class MovetoinventoryController extends Controller
{
    public $module = "movetoinventory";
    public function __construct()
    {
        $this->data = array();
        $this->model = new Movetoinventory;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageModule'] = 'movetoinventory';
        $this->middleware('auth');
        $this->data['urlmenu'] = $this->indexs();
    }

    /* Purpose For Show QC Approved data in Index*/
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

        $this->data['supplier_id'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        $this->data['productname'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');
        $this->data['pageMethod'] = 'movetoinventory';

        return view('movetoinventory.table', $this->data);
    }


    // table Data

    public function MovinventoryData(Request $request)
    {

        $SQL = "SELECT
                p_qc_lines_t.qc_line_id,
                0 as grn_line_id,
                p_qc_header_t.qc_number,
                p_grn_hdr_t.grn_number,
                p_grn_hdr_t.grn_date,
                m_products_t.qc_check as quality_check,
                p_qc_header_t.qc_date,
                p_qc_lines_t.product_id,
                m_products_t.concatenated_product,
                p_po_hdr_t.po_number AS po_number,
                m_supplier_t.supplier_name,
                m_subcontract_supplier_t.subcontract_name,
                hr_employee_t.first_name,
                datediff(CURRENT_DATE() , p_grn_hdr_t.grn_date ) as due_days
                FROM
                p_qc_lines_t
                LEFT JOIN m_products_t ON m_products_t.product_id = p_qc_lines_t.product_id
                LEFT JOIN p_qc_header_t ON p_qc_header_t.qc_header_id = p_qc_lines_t.qc_header_id
                LEFT JOIN p_grn_hdr_t ON p_grn_hdr_t.grn_id = p_qc_header_t.grn_number
                LEFT JOIN p_po_hdr_t ON p_po_hdr_t.po_hdr_id = p_qc_header_t.po_number
                LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = p_qc_header_t.supplier_id
                LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = p_grn_hdr_t.created_by
                LEFT JOIN m_subcontract_supplier_t ON m_subcontract_supplier_t.subcontract_supplier_id = p_qc_header_t.subcontract_supplier_id
                WHERE
                p_qc_lines_t.inventory_status = '0' AND p_qc_lines_t.approval_status = 'APPROVED'  
                UNION ALL
                SELECT
                0,
                p_grn_lines_t.grn_line_id,
                0,
                p_grn_hdr_t.grn_number,
                p_grn_hdr_t.grn_date,
                m_products_t.qc_check as quality_check,
                0,
                p_grn_lines_t.product_id,
                m_products_t.concatenated_product,
                p_po_hdr_t.po_number AS po_number,
                m_supplier_t.supplier_name,
                m_subcontract_supplier_t.subcontract_name,
                hr_employee_t.first_name,
                datediff(CURRENT_DATE() , p_grn_hdr_t.grn_date ) as due_days
                FROM
                p_grn_lines_t
                LEFT JOIN p_grn_hdr_t ON p_grn_hdr_t.grn_id = p_grn_lines_t.grn_id
                LEFT JOIN m_products_t ON m_products_t.product_id = p_grn_lines_t.product_id
                LEFT JOIN p_po_hdr_t ON p_po_hdr_t.po_hdr_id = p_grn_hdr_t.po_number
                LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = p_grn_hdr_t.supplier_id
                LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = p_grn_hdr_t.created_by
                LEFT JOIN m_subcontract_supplier_t ON m_subcontract_supplier_t.subcontract_supplier_id = p_grn_hdr_t.subcontract_supplier_id
                WHERE
                (
                (p_grn_hdr_t.quality_check IS NULL 
                OR p_grn_hdr_t.quality_check = '' 
                OR p_grn_hdr_t.quality_check = 'No')
                AND m_products_t.qc_check = 'No'
            )
            AND p_grn_lines_t.inventory_status = '0'";

        // Combine both using unionAll
        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);
    }



    /* Purpose For Show QC Approved data in Index*/
    public function movetoinventorydata()
    {
        $wh = '';
        if ($_GET['_search'] == 'true') {
            $search_tables = array('p_qc_header_t', 'm_products_t', 'm_supplier_t', 'p_grn_lines_t');
            $wh = $this->jqgridsearch('p_qc_lines_t', $_GET['filters'], $search_tables);
        }
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');
        if ($groupname == 'Superadmin' || $groupname == 'Admin') {
            $wh .= 'and p_grn_hdr_t.company_id=' . $compy;
        } else {
            $wh .= 'and p_grn_hdr_t.company_id=' . $compy . ' and p_grn_hdr_t.location_id=' . $loc;
        }

        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if (!$sidx)
            $sidx = 1;
        $result = \DB::select("SELECT COUNT(p_qc_lines_t.qc_header_id) AS count  from
                        p_qc_lines_t 
                        left join m_products_t on m_products_t.product_id=p_qc_lines_t.product_id 
                        left join p_qc_header_t on p_qc_header_t.qc_header_id=p_qc_lines_t.qc_header_id 
                        left join m_supplier_t on m_supplier_t.supplier_id=p_qc_header_t.supplier_id 
                        LEFT JOIN p_grn_hdr_t ON p_grn_hdr_t.grn_id = p_qc_header_t.grn_number 
                        where p_qc_lines_t.inventory_status='0' and  p_qc_lines_t.approval_status='APPROVED' $wh");
        //dd($result);
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

        $SQL = "SELECT
				p_qc_lines_t.qc_line_id,
				0 as grn_line_id,
				p_qc_header_t.qc_number,
				p_grn_hdr_t.grn_number,
  				p_grn_hdr_t.quality_check,
				p_qc_lines_t.product_id,
                                m_products_t.product_code,
				m_products_t.concatenated_product,
				p_po_hdr_t.po_number AS po_number,
				m_supplier_t.supplier_name
				FROM
				p_qc_lines_t
				LEFT JOIN m_products_t ON m_products_t.product_id = p_qc_lines_t.product_id
				LEFT JOIN p_qc_header_t ON p_qc_header_t.qc_header_id = p_qc_lines_t.qc_header_id
				LEFT JOIN p_grn_hdr_t ON p_grn_hdr_t.grn_id = p_qc_header_t.grn_number
				LEFT JOIN p_po_hdr_t ON p_po_hdr_t.po_hdr_id = p_qc_header_t.po_number
				LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = p_qc_header_t.supplier_id
				WHERE
				p_qc_lines_t.inventory_status = '0' AND p_qc_lines_t.approval_status = 'APPROVED'  and p_grn_hdr_t.quality_check = 'Yes'
				UNION ALL
				SELECT
				0,
				p_grn_lines_t.grn_line_id,
				0,
				p_grn_hdr_t.grn_number,
				p_grn_hdr_t.quality_check,
				p_grn_lines_t.product_id,
                                m_products_t.product_code,
				m_products_t.concatenated_product,
				p_po_hdr_t.po_number AS po_number,
				m_supplier_t.supplier_name
				FROM
				p_grn_lines_t
				LEFT JOIN m_products_t ON m_products_t.product_id = p_grn_lines_t.product_id
				LEFT JOIN p_grn_hdr_t ON p_grn_hdr_t.grn_id = p_grn_lines_t.grn_id
				LEFT JOIN p_po_hdr_t ON p_po_hdr_t.po_hdr_id = p_grn_hdr_t.po_number
				LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = p_grn_hdr_t.supplier_id
				WHERE
				p_grn_lines_t.inventory_status = '0' and  p_grn_hdr_t.quality_check = 'No'  $wh ORDER BY $sidx $sord LIMIT $start , $limit";

        $download_SQL = "SELECT
        p_qc_lines_t.qc_line_id,
        0 as grn_line_id,
        p_qc_header_t.qc_number,
        p_grn_hdr_t.grn_number,
          p_grn_hdr_t.quality_check,
        p_qc_lines_t.product_id,
        m_products_t.product_code,
        m_products_t.concatenated_product,
        p_po_hdr_t.po_number AS po_number,
        m_supplier_t.supplier_name
        FROM
        p_qc_lines_t
        LEFT JOIN m_products_t ON m_products_t.product_id = p_qc_lines_t.product_id
        LEFT JOIN p_qc_header_t ON p_qc_header_t.qc_header_id = p_qc_lines_t.qc_header_id
        LEFT JOIN p_grn_hdr_t ON p_grn_hdr_t.grn_id = p_qc_header_t.grn_number
        LEFT JOIN p_po_hdr_t ON p_po_hdr_t.po_hdr_id = p_qc_header_t.po_number
        LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = p_qc_header_t.supplier_id
        WHERE
        p_qc_lines_t.inventory_status = '0' AND p_qc_lines_t.approval_status = 'APPROVED'  and p_grn_hdr_t.quality_check = 'Yes'
        UNION ALL
        SELECT
        0,
        p_grn_lines_t.grn_line_id,
        0,
        p_grn_hdr_t.grn_number,
        p_grn_hdr_t.quality_check,
        p_grn_lines_t.product_id,
        m_products_t.product_code,
        m_products_t.concatenated_product,
        p_po_hdr_t.po_number AS po_number,
        m_supplier_t.supplier_name
        FROM
        p_grn_lines_t
        LEFT JOIN m_products_t ON m_products_t.product_id = p_grn_lines_t.product_id
        LEFT JOIN p_grn_hdr_t ON p_grn_hdr_t.grn_id = p_grn_lines_t.grn_id
        LEFT JOIN p_po_hdr_t ON p_po_hdr_t.po_hdr_id = p_grn_hdr_t.po_number
        LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = p_grn_hdr_t.supplier_id
        WHERE
        p_grn_lines_t.inventory_status = '0' and  p_grn_hdr_t.quality_check = 'No'  $wh ORDER BY $sidx $sord";
        $result1 = \DB::select($download_SQL);
        $result1 = collect($result1)->map(function ($x) {
            return (array) $x;
        })->toArray();
        if (isset($_GET['download'])) {
            return $result1;
        }

        $result = \DB::select($SQL);
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        echo json_encode($responce);
    }
    /* Purpose For Create Function*/
    public function create($id = null, $qccheck = null)
    {
        if (isset($id)) {

            if ($qccheck == "QC") {

                $tablelines = \DB::table('p_quality_spec_trx_lines_t')->select('p_quality_spec_trx_lines_t.*')->selectRaw('sum(box_product_qty) as box_product_qty')->where('quality_status', 'Accepted')->where('reference_source_line_id', $id)->get();

                $qcHdrId = $tablelines
                    ->pluck('reference_source_hdr_id')
                    ->filter()
                    ->first();

                if ($qcHdrId == null) {

                    $qc_hdr_id = $id;
                    $this->data['qc_header_id'] = $qc_hdr_id;
                    $this->data['qc_line_id'] = $id;
                    //  dd("here");
                    $this->data['invdata'] = $table = DB::table('p_qc_header_t')->select('grn.grn_number as grn_name', 'grn.po_number', 'm_products_t.qc_check as quality_check', 'p_qc_header_t.qc_header_id', 'p_grn_lines_t.qc_status', 'p_qc_header_t.qc_number', 'p_qc_header_t.grn_number', 'p_qc_header_t.po_number', 'p_grn_lines_t.po_hdr_id')->leftjoin('p_grn_hdr_t  as grn', 'grn.grn_id', '=', 'p_qc_header_t.grn_number')
                        ->leftjoin('p_grn_lines_t', 'grn.grn_id', '=', 'p_grn_lines_t.grn_id')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'p_grn_lines_t.product_id')
                        ->where('qc_header_id', $id)->where('p_grn_lines_t.qc_status', 1)->get();

                    $this->data['qccheckdata'] = $table = DB::table('m_products_t')->select('m_products_t.qc_check as quality_check')->leftjoin('p_qc_lines_t', 'm_products_t.product_id', '=', 'p_qc_lines_t.product_id')->where('p_qc_lines_t.qc_header_id', $id)->get();

                } else {

                    $qc_hdr_id = $tablelines[0]->reference_source_hdr_id ?? '0';
                    $this->data['qc_header_id'] = $qc_hdr_id;
                    $this->data['qc_line_id'] = $id;
                    $this->data['invdata'] = $table = DB::table('p_qc_header_t')->select('grn.grn_number as grn_name', 'grn.po_number', 'm_products_t.qc_check as quality_check', 'p_qc_header_t.qc_header_id', 'p_grn_lines_t.qc_status', 'p_qc_header_t.qc_number', 'p_qc_header_t.grn_number', 'p_qc_header_t.po_number', 'p_grn_lines_t.po_hdr_id')->leftjoin('p_grn_hdr_t  as grn', 'grn.grn_id', '=', 'p_qc_header_t.grn_number')
                        ->leftjoin('p_grn_lines_t', 'grn.grn_id', '=', 'p_grn_lines_t.grn_id')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'p_grn_lines_t.product_id')
                        ->where('qc_header_id', $qc_hdr_id)->where('p_grn_lines_t.qc_status', 1)->get();
                    $this->data['qccheckdata'] = $table = DB::table('m_products_t')->select('m_products_t.qc_check as quality_check')->leftjoin('p_quality_spec_trx_lines_t', 'm_products_t.product_id', '=', 'p_quality_spec_trx_lines_t.product_id')->where('p_quality_spec_trx_lines_t.reference_source_hdr_id', $qc_hdr_id)->get();
                }
                // dd($this->data['qccheckdata']);
                $this->data['linedata'] = $tablelines;
            } else {
                $tablelines = \DB::table('p_grn_lines_t')->select('p_grn_lines_t.*')->selectRaw('sum(box_qty) as box_product_qty')->where('grn_line_id', $id)->get();
                $grn_id = $tablelines[0]->grn_id ?? 0;
                $this->data['qc_header_id'] = $grn_id;
                $this->data['qc_line_id'] = $id;
                $this->data['invdata'] = $table = DB::table('p_grn_hdr_t')->select('p_grn_hdr_t.grn_id as grn_number', 'p_grn_hdr_t.grn_number as grn_name', 'p_grn_hdr_t.po_number', 'm_products_t.qc_check as quality_check', 'p_grn_lines_t.po_hdr_id')->leftjoin('p_grn_lines_t', 'p_grn_hdr_t.grn_id', '=', 'p_grn_lines_t.grn_id')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'p_grn_lines_t.product_id')->where('p_grn_hdr_t.grn_id', $grn_id)->get();
                $this->data['qccheckdata'] = $table = DB::table('m_products_t')->select('m_products_t.qc_check as quality_check')->leftjoin('p_grn_lines_t', 'm_products_t.product_id', '=', 'p_grn_lines_t.product_id')->where('p_grn_lines_t.grn_line_id', $id)->get();
                $this->data['linedata'] = $tablelines;
            }
        }
        return view('movetoinventory.form', $this->data);
    }


    /* Purpose For Popup Create Function*/
    public function popupcreate($id = null, $qccheck = null)
    {
        if (isset($id)) {
            if ($qccheck == "QC") {
                $tablelines = \DB::table('p_quality_spec_trx_lines_t')->select('p_quality_spec_trx_lines_t.*')->selectRaw('sum(box_product_qty) as box_product_qty')->where('quality_status', 'Accepted')->where('reference_source_line_id', $id)->get();
                $qc_hdr_id = $tablelines[0]->reference_source_hdr_id;
                $this->data['qc_header_id'] = $qc_hdr_id;
                $this->data['qc_line_id'] = $id;
                $this->data['invdata'] = $table = DB::table('p_qc_header_t')->select('grn.grn_number as grn_name', 'grn.quality_check', 'p_qc_header_t.qc_header_id', 'p_qc_header_t.qc_number', 'p_qc_header_t.grn_number', 'p_qc_header_t.po_number', 'grn.quality_check')->leftjoin('p_grn_hdr_t  as grn', 'grn.grn_id', '=', 'p_qc_header_t.grn_number')->where('qc_header_id', $qc_hdr_id)->get();
                $this->data['linedata'] = $tablelines;
            } else {
                $tablelines = \DB::table('p_grn_lines_t')->select('p_grn_lines_t.*')->selectRaw('sum(box_qty) as box_product_qty')->where('grn_line_id', $id)->get();
                $grn_id = $tablelines[0]->grn_id;
                $this->data['qc_header_id'] = $grn_id;
                $this->data['qc_line_id'] = $id;
                $this->data['invdata'] = $table = DB::table('p_grn_hdr_t')->select('p_grn_hdr_t.grn_id as grn_number', 'p_grn_hdr_t.grn_number as grn_name', 'p_grn_hdr_t.po_number', 'p_grn_hdr_t.quality_check')->where('grn_id', $grn_id)->get();
                $this->data['linedata'] = $tablelines;
            }
        }
        return view('movetoinventory.movpopupform', $this->data);
    }
    /* Purpose For Save Function*/
    public function inventorysave(Request $id)
    {

        $mvinvdata = new Movetoinventory();
        $qc_line_id = $_POST['qc_line_id'];
        $qc_header_id = $_POST['qc_header_id'];
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $product_id = $_POST['product_id'];
        $boxqty = $_POST['box_product_qty'];
        $qty = array_sum($boxqty);
        $pid = $product_id[0];
        $poid = $_POST['po_number'];
        $poids = explode(",", $poid);

        $po_details = \DB::table('p_po_lines_t')->join('m_products_t', 'm_products_t.product_id', '=', 'p_po_lines_t.product_id')->join('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'p_po_lines_t.tax_group_id')->select('p_po_lines_t.*', 'm_products_t.tax_credit', 'm_products_t.account_code_id', 'm_products_t.disc_account_code', 'm_products_t.control_account_id', 'm_tax_group_t.display_name')->where('m_products_t.product_id', $product_id)->whereIn("p_po_lines_t.po_hdr_id", $poids)->get();
        $collection = collect($po_details);
        $productdata = \DB::select('select qc_no_of_days from m_products_t where product_id=' . $pid);
        $qcdata = \DB::select('select qc_date from p_qc_header_t where qc_header_id=' . $qc_header_id);
        if (!empty($qcdata)) {
            $qcdate = $qcdata[0]->qc_date;
            $qcnoofdays = $productdata[0]->qc_no_of_days;
            $qcnodate = date('Y-m-d', strtotime($qcdate . '+' . $qcnoofdays . 'days'));
        } else {
            $qcnodate = "";
        }
        //-- Purpose for PO Qty Update--------------------------------- 
        $qccheck = $_POST['quality_check'];
        $po_number = $_POST['po_number'];
        if ($qccheck == "No" && $po_number != "") {
            $po_data1 = \DB::SELECT("SELECT sum(pending_qty) as pending_qty,received_qty,product_id,status FROM p_po_lines_t where po_hdr_id in ($po_number)");
            if ($po_data1[0]->pending_qty <= 0) {
                \DB::update("update p_po_hdr_t set po_status='COMPLETED' where po_hdr_id in ($po_number)");
            }

            //         }
        }
        //--End---
        /*QOH Insert and Material Transactions Insert*/

        if ($qc_line_id != '') {
            \DB::update("update p_qc_lines_t set inventory_status=1 where qc_line_id='$qc_line_id'");
            \DB::update("update p_grn_lines_t set inventory_status=1 where grn_line_id='$qc_line_id'");
            \DB::update("update  p_quality_spec_trx_lines_t set movetoinv_status=1 where reference_source_line_id='$qc_line_id' and quality_status='Accepted' ");
            //Purpose for Notifications
            \DB::table('notifications_t')->where('reference_source_id', $qc_line_id)->where('reference_source', 'GRN')->update(['read/unread' => 'read']);

            for ($i = 0; $i < count($_POST['line_no']); $i++) {
                $prdbox_qty = $_POST['box_product_qty'][$i];
                //Uom Conversion
                $uomproduct = \DB::select("select product_id,primary_uom,trx_uom,uom_value from m_uom_conversion_t where product_id='$pid' ");

                if ($uomproduct != null) {
                    $uom_value = $uomproduct[0]->uom_value;
                    $conversion_qty = $prdbox_qty * $uom_value;
                    //Material Trx Insert
                    $trsns = \DB::table('m_transaction_types_t')->where('transaction_type_name', 'PURCHASE_STOREMOVE')->get();
                    $trsns = json_decode(json_encode($trsns), true);
                    if (!empty($trsns)) {
                        $mtl['trx_source_type_id'] = $trsns[0]['transaction_source_id'];
                        $mtl['trx_action_id'] = $trsns[0]['transaction_action_id'];
                        $mtl['trx_type_id'] = $trsns[0]['transaction_type_id'];
                    } else {
                        $mtl['trx_source_type_id'] = 0;
                        $mtl['trx_action_id'] = 0;
                        $mtl['trx_type_id'] = 0;
                    }
                    $mtl['trx_source_hdr_id'] = $_POST['grn_id'];
                    $mtl['trx_source_line_id'] = "";
                    $mtl['product_id'] = $_POST['product_id'][$i];
                    $mtl['subinventory_id'] = $_POST['subinventory_id'][$i];
                    $mtl['locator_id'] = $_POST['sublocator_id'][$i];
                    $mtl['trx_reference'] = "PURCHASE_STOREMOVE";
                    $mtl['trx_qty'] = $conversion_qty;
                    $mtl['trx_uom'] = $uomproduct[0]->primary_uom;
                    $mtl['company_id'] = $compy;
                    $mtl['location_id'] = $loc;
                    $mtl['organization_id'] = $org;
                    $mtlid = \DB::table('m_material_trx_t')->insertGetId($mtl);

                    /**Auditlog**/
                    $this->auditlog($mtlid, "movetoinventory", "create", $_POST, "m_material_trx_t");
                    /*isac Purpose for get Product Cost*/
                    $filtered = $collection->where('product_id', $_POST['product_id'][$i]);
                    $filtered->all();
                    foreach ($filtered as $val) {
                        $filtered = array();
                        $filtered[] = $val;
                        break;
                    }
                    if (count($filtered) > 0) {
                        $rate = $filtered[0]->unit_price - (($filtered[0]->unit_price * $filtered[0]->discount_percentage) / 100);
                    } else {

                        $product_id = $_POST['product_id'][$i];
                        $cost = \DB::select("select cost from i_qoh_detail_t where product_id='$product_id' and (qoh_source='OPENSTOCK' OR qoh_source='PURCHASE_STOREMOVE') and cost >0 order by qoh_detail_id desc limit 1");
                        $rate = $cost[0]->cost;
                    }
                    /*QOH Insert*/
                    $ins = DB::table('i_qoh_detail_t')->insertGetId(['product_id' => $_POST['product_id'][$i], 'qoh_trx_qty' => $conversion_qty, 'subinventory_id' => $_POST['subinventory_id'][$i], 'locator_id' => $_POST['sublocator_id'][$i], 'batch_number' => $_POST['inventrory_number'][$i], 'company_id' => $compy, 'created_by' => \Session::get('id'), 'created_at' => date('Y-m-d H:i:s'), 'qoh_source' => 'PURCHASE_STOREMOVE', 'cost' => $rate, 'grn_id' => $_POST['grn_id'], 'qc_date' => $qcnodate, 'qoh_trx_date' => date('Y-m-d'), 'qoh_source_id' => $_POST['grn_id']]);
                    /* Purpose for SemiFinished Goods Quality Status Update*/
                    $productgroup = \DB::table('m_products_t')->select('m_products_t.product_group_id', 'm_product_groups_t.group_name')->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')->where('product_id', $_POST['product_id'][$i])->get();
                    $group_name = $productgroup[0]->group_name;
                    if ($group_name == "SEMI FINISHED GOODS") {
                        \DB::table('i_qoh_detail_t')->where('qoh_detail_id', $ins)->where('qoh_source', 'PURCHASE_STOREMOVE')->update(['qualitystatus' => '1']);
                    }
                    /**Auditlog**/
                    $this->auditlog($ins, "movetoinventory", "create", $_POST, "i_qoh_detail_t");

                } else {

                    $productuom = \DB::select("select product_id,primary_uom_id from m_products_t where product_id='$pid' ");
                    //Material Trx Insert
                    $trsns = \DB::table('m_transaction_types_t')->where('transaction_type_name', 'PURCHASE_STOREMOVE')->get();
                    $trsns = json_decode(json_encode($trsns), true);
                    if (!empty($trsns)) {
                        $mtl['trx_source_type_id'] = $trsns[0]['transaction_source_id'];
                        $mtl['trx_action_id'] = $trsns[0]['transaction_action_id'];
                        $mtl['trx_type_id'] = $trsns[0]['transaction_type_id'];
                    } else {
                        $mtl['trx_source_type_id'] = 0;
                        $mtl['trx_action_id'] = 0;
                        $mtl['trx_type_id'] = 0;
                    }
                    $mtl['trx_source_hdr_id'] = $_POST['grn_id'];
                    $mtl['trx_source_line_id'] = "";
                    $mtl['product_id'] = $_POST['product_id'][$i];
                    $mtl['subinventory_id'] = $_POST['subinventory_id'][$i];
                    $mtl['locator_id'] = $_POST['sublocator_id'][$i];
                    $mtl['trx_reference'] = "PURCHASE_STOREMOVE";
                    $mtl['trx_qty'] = $_POST['box_product_qty'][$i];
                    $mtl['trx_uom'] = $productuom[0]->primary_uom_id;
                    ;
                    $mtl['company_id'] = $compy;
                    $mtl['location_id'] = $loc;
                    $mtl['organization_id'] = $org;
                    $mtlid = \DB::table('m_material_trx_t')->insertGetId($mtl);
                    /**Auditlog**/
                    $this->auditlog($mtlid, "movetoinventory", "create", $_POST, "m_material_trx_t");

                    /*isac Purpose for get Product Cost*/
                    $filtered = $collection->where('product_id', $_POST['product_id'][$i]);
                    $filtered->all();
                    foreach ($filtered as $val) {
                        $filtered = array();
                        $filtered[] = $val;
                        break;
                    }

                    if (count($filtered) > 0) {
                        $rate = $filtered[0]->unit_price - (($filtered[0]->unit_price * $filtered[0]->discount_percentage) / 100);
                    } else {
                        $product_id = $_POST['product_id'][$i];
                        $cost = \DB::select("select cost from i_qoh_detail_t where product_id='$product_id' and (qoh_source='OPENSTOCK' OR qoh_source='PURCHASE_STOREMOVE') and cost >0 order by qoh_detail_id desc limit 1");

                        if (count($cost) > 0) {
                            $rate = $cost[0]->cost;
                        } else {
                            $rate = "0.1";
                        }

                    }

                    /*QOH Insert*/
                    $ins = DB::table('i_qoh_detail_t')->insertGetId([
                        'product_id' => $_POST['product_id'][$i],
                        'qoh_trx_qty' => $_POST['box_product_qty'][$i],
                        'subinventory_id' => $_POST['subinventory_id'][$i],
                        'locator_id' => $_POST['sublocator_id'][$i]
                        ,
                        'batch_number' => $_POST['inventrory_number'][$i],
                        'create_trx_id' => $mtlid,
                        'company_id' => $compy,
                        'created_by' => \Session::get('id'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'qoh_source' => 'PURCHASE_STOREMOVE',
                        'cost' => $rate,
                        'grn_id' => $_POST['grn_id'],
                        'qc_date' => $qcnodate,
                        'qoh_trx_date' => date('Y-m-d'),
                        'qoh_source_id' => $_POST['grn_id']
                    ]);

                    /* Purpose for SemiFinished Goods Quality Status Update*/
                    $productgroup = \DB::table('m_products_t')->select('m_products_t.product_group_id', 'm_product_groups_t.group_name')->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')->where('product_id', $_POST['product_id'][$i])->get();
                    $group_name = $productgroup[0]->group_name;

                    if ($group_name == "SEMI FINISHED GOODS") {
                        \DB::table('i_qoh_detail_t')->where('qoh_detail_id', $ins)->where('qoh_source', 'PURCHASE_STOREMOVE')->update(['qualitystatus' => '1']);
                    }

                    /**Auditlog**/
                    $this->auditlog($ins, "movetoinventory", "create", $_POST, "i_qoh_detail_t");
                }
            }
        }

        /**END QOH Insert and Material Transactions Insert**/
        /**----- Purpose For Journal Entry------**/
        if ($po_number != "") {

            $current_date = date('Y-m-d');
            $org = \Session::get('organization');
            $mov_number = "MOV-" . $_POST['grn_number'];
            $loc = \Session::get('location');
            $compy = \Session::get('companyid');
            $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$mov_number','MOVE TO INVENTORY','$current_date','$ins','APPROVED','$compy','$loc','$org')");
            $jid = DB::getPdo()->lastInsertId();
            $total = 0;
            $key2 = 0;
            foreach ($_POST['product_id'] as $tkey => $tvalue) {
                $filtered = $collection->where('product_id', $tvalue);
                $filtered->all();
                foreach ($filtered as $val) {
                    $filtered = array();
                    $filtered[] = $val;
                    break;
                }
                $journal_lines_data[$key2]['journal_entry_id'] = $jid;
                $journal_lines_data[$key2]['journal_date'] = $current_date;
                $journal_lines_data[$key2]['reference_source'] = "PRODUCT";
                $journal_lines_data[$key2]['reference_id'] = $tvalue;
                $journal_lines_data[$key2]['product_qty'] = $_POST['box_product_qty'][$tkey];
                $journal_lines_data[$key2]['batch_number'] = $_POST['inventrory_number'][$tkey];
                $journal_lines_data[$key2]['account_id'] = $filtered[0]->account_code_id;
                $rate = $filtered[0]->unit_price - (($filtered[0]->unit_price * $filtered[0]->discount_percentage) / 100);
                $rate = $rate * $_POST['box_product_qty'][$tkey];
                if ($filtered[0]->tax_credit != "Yes") {
                    $taxval = (float) $filtered[0]->display_name;
                    $rate = $rate + (($rate * $taxval) / 100);
                }


                $journal_lines_data[$key2]['debit_amount'] = $rate;
                $journal_lines_data[$key2]['credit_amount'] = '';
                $journal_lines_data[$key2]['line_no'] = $key2 + 1;
                $journal_lines_data[$key2]['created_by'] = \Session::get('id');
                $journal_lines_data[$key2]['created_at'] = date('Y-m-d H:i:s');
                $journal_lines_data[$key2]['last_updated_by'] = \Session::get('id');
                $journal_lines_data[$key2]['updated_at'] = date('Y-m-d H:i:s');
                ;
                $journal_lines_data[$key2]['location_id'] = \Session::get('companyid');
                $journal_lines_data[$key2]['company_id'] = \Session::get('location');
                /* Credit Account */
                $key2++;
                $journal_lines_data[$key2]['journal_entry_id'] = $jid;
                $journal_lines_data[$key2]['journal_date'] = $current_date;
                $journal_lines_data[$key2]['reference_source'] = "PRODUCT";
                $journal_lines_data[$key2]['reference_id'] = $tvalue;
                $journal_lines_data[$key2]['product_qty'] = $_POST['box_product_qty'][$tkey];
                $journal_lines_data[$key2]['batch_number'] = $_POST['inventrory_number'][$tkey];
                $journal_lines_data[$key2]['account_id'] = $filtered[0]->control_account_id;
                $rate = $filtered[0]->unit_price - (($filtered[0]->unit_price * $filtered[0]->discount_percentage) / 100);
                $rate = $rate * $_POST['box_product_qty'][$tkey];
                if ($filtered[0]->tax_credit != "Yes") {
                    $taxval = (float) $filtered[0]->display_name;
                    $rate = $rate + (($rate * $taxval) / 100);
                }


                $journal_lines_data[$key2]['debit_amount'] = '';
                $journal_lines_data[$key2]['credit_amount'] = $rate;
                $journal_lines_data[$key2]['line_no'] = $key2 + 1;
                $journal_lines_data[$key2]['created_by'] = \Session::get('id');
                $journal_lines_data[$key2]['created_at'] = date('Y-m-d H:i:s');
                $journal_lines_data[$key2]['last_updated_by'] = \Session::get('id');
                $journal_lines_data[$key2]['updated_at'] = date('Y-m-d H:i:s');
                ;
                $journal_lines_data[$key2]['location_id'] = \Session::get('companyid');
                $journal_lines_data[$key2]['company_id'] = \Session::get('location');
                $key2++;
            }
            \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);
        } else {

            $current_date = date('Y-m-d');
            $org = \Session::get('organization');
            $mov_number = "MOV-" . $_POST['grn_number'];
            $loc = \Session::get('location');
            $compy = \Session::get('companyid');
            $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$mov_number','MOVE TO INVENTORY','$current_date','$ins','APPROVED','$compy','$loc','$org')");
            $jid = DB::getPdo()->lastInsertId();
            $total = 0;
            $key2 = 0;

            foreach ($_POST['product_id'] as $tkey => $tvalue) {

                $account = \DB::select("select account_code_id from m_products_t where product_id='$tvalue'");
                $acc_id = $account[0]->account_code_id;
                $cost = \DB::select("select cost from i_qoh_detail_t where product_id='$tvalue' and (qoh_source='OPENSTOCK' OR qoh_source='PURCHASE_STOREMOVE') and cost >0 order by qoh_detail_id desc limit 1");
                $rate_dgrn = $cost[0]->cost;


                $journal_lines_data[$key2]['journal_entry_id'] = $jid;

                $journal_lines_data[$key2]['journal_date'] = $current_date;
                $journal_lines_data[$key2]['reference_source'] = "PRODUCT";
                $journal_lines_data[$key2]['reference_id'] = $tvalue;
                $journal_lines_data[$key2]['product_qty'] = $_POST['box_product_qty'][$tkey];
                $journal_lines_data[$key2]['batch_number'] = $_POST['inventrory_number'][$tkey];
                $journal_lines_data[$key2]['account_id'] = $acc_id;
                //	dd($journal_lines_data[$key2]['account_id']);

                $rate = $rate_dgrn * $_POST['box_product_qty'][$tkey];



                $journal_lines_data[$key2]['debit_amount'] = $rate;
                $journal_lines_data[$key2]['credit_amount'] = '';
                $journal_lines_data[$key2]['line_no'] = $key2 + 1;
                $journal_lines_data[$key2]['created_by'] = \Session::get('id');
                $journal_lines_data[$key2]['created_at'] = date('Y-m-d H:i:s');
                $journal_lines_data[$key2]['last_updated_by'] = \Session::get('id');
                $journal_lines_data[$key2]['updated_at'] = date('Y-m-d H:i:s');
                ;
                $journal_lines_data[$key2]['location_id'] = \Session::get('companyid');
                $journal_lines_data[$key2]['company_id'] = \Session::get('location');

                $key2++;
                $journal_lines_data[$key2]['journal_entry_id'] = $jid;
                $journal_lines_data[$key2]['journal_date'] = $current_date;
                $journal_lines_data[$key2]['reference_source'] = "PRODUCT";
                $journal_lines_data[$key2]['reference_id'] = $tvalue;
                $journal_lines_data[$key2]['product_qty'] = $_POST['box_product_qty'][$tkey];
                $journal_lines_data[$key2]['batch_number'] = $_POST['inventrory_number'][$tkey];
                $journal_lines_data[$key2]['account_id'] = $acc_id;
                $rate = $rate_dgrn * $_POST['box_product_qty'][$tkey];


                $journal_lines_data[$key2]['debit_amount'] = '';
                $journal_lines_data[$key2]['credit_amount'] = $rate;
                $journal_lines_data[$key2]['line_no'] = $key2 + 1;
                $journal_lines_data[$key2]['created_by'] = \Session::get('id');
                $journal_lines_data[$key2]['created_at'] = date('Y-m-d H:i:s');
                $journal_lines_data[$key2]['last_updated_by'] = \Session::get('id');
                $journal_lines_data[$key2]['updated_at'] = date('Y-m-d H:i:s');
                ;
                $journal_lines_data[$key2]['location_id'] = \Session::get('companyid');
                $journal_lines_data[$key2]['company_id'] = \Session::get('location');
                $key2++;
            }
            \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);
        }

        return response()->json(array('status' => 'success', 'message' => 'Inventory datas moved Successfully'));
    }
    /*END*/

    /*Purpose For Update Move to Inventory Data In Modal Popup*/

    public function movetoinventoryupdate($id = null, $grnid = null, $qualitycheck = null)
    {

        if ($qualitycheck == "Yes") {




            $data = \DB::select("SELECT
    p_quality_spec_trx_lines_t.reference_source_hdr_id as hdrid,
	p_quality_spec_trx_lines_t.product_id,
	m_products_t.qc_type,
	p_quality_spec_trx_lines_t.*,
    concat(m_products_t.product_code,'-',m_products_t.concatenated_product)concatenated_product
FROM
    p_quality_spec_trx_lines_t
    LEFT JOIN m_products_t ON m_products_t.product_id = p_quality_spec_trx_lines_t.product_id
WHERE
    p_quality_spec_trx_lines_t.reference_source_line_id = '$id' AND quality_status = 'Accepted'
    ");


            if (empty($data)) {

                $data = \DB::select("SELECT
    p_qc_lines_t.qc_header_id as hdrid,
	p_qc_lines_t.product_id,
	m_products_t.qc_type,
	p_qc_lines_t.*,
    p_qc_lines_t.qty as box_product_qty,
    concat(m_products_t.product_code,'-',m_products_t.concatenated_product)concatenated_product
FROM
    p_qc_lines_t
    LEFT JOIN m_products_t ON m_products_t.product_id = p_qc_lines_t.product_id
WHERE
    p_qc_lines_t.qc_line_id = '$id' ");

            }

            $product_id = $data[0]->product_id;
            $getsubinventory = \DB::table('m_products_t')->select('subinventory_id', 'sublocator_id')->where('product_id', '=', $product_id)->get();
            $sub_inventory = $this->jCombo('m_subinventory_t', 'subinventory_id', 'subinventory_name', $getsubinventory[0]->subinventory_id);
            $sublocators = $this->jcustomselect('m_sublocators_t', 'sublocator_id', 'locator_code', $getsubinventory[0]->sublocator_id, 'and subinventory_id=' . $getsubinventory[0]->subinventory_id . '');
          
            if ($data[0]->qc_type == "BATCHWISE") {
                $html = '';
                if (count($data) > 0) {
                    $html .= '<div  class="table-responsive">
			<table class=" table table-bordered clone_table">
                          <style>
            .line_no{width:70px;text-align:center;}
            .product{width:250px;}
            .batch_number{width:100px;}
            .serial_number{width:100px;}
            .subinventory_id{width:120px;} 
            .sublocator_id{width:120px;}
            .box_product_qty{width:120px;}
            .inventrory_number{width:120px;} 
        </style> 
                        <thead class="table-light">
                        <tr style="background-color:#05234e;color:#fff;"> 
                        <th>Line No</th>
                        <th>Product</th>
                        <th>Inward Batch Number</th>
                        <th>Inward Serial Number</th>
                        <th>Sub Inventory</th>
                        <th>Sub Locator</th>
                        <th>Box Quantity</th>
						<th>Lot Number</th>
                        </tr>
                        </thead>
                        <tbody class="clone_lines_body">';
                    $data_l = \DB::select("SELECT i_qoh_detail_t.batch_number from i_qoh_detail_t where qoh_source='PURCHASE_STOREMOVE'");
                    $d_count = count($data_l);
                    $batch_no_1 = "1000";
                    if ($d_count == 0) {
                        $batch_no1 = $batch_no_1;
                    } else {
                        $batch_no1 = $batch_no_1 + $d_count;
                    }
                    foreach ($data as $key => $value) {
                        
                        $batch_number = $value->batch_number ?? '1';
                        $serial_number = $value->serial_number ?? '1';
                        $batch_no = $batch_no1 + $key;
                        $html .= '<td><input type="text" name="line_no[]" class="form-control input-sm line_no" value="' . ($key + 1) . '" readonly="readonly" >
                            <input type="hidden" name="qc_header_id" class="form-control input-sm qc_header_id input_qty_width" value="' . $value->hdrid . '"</td>
                            <input type="hidden" name="qc_line_id" class="form-control input-sm qc_line_id input_qty_width" value="' . $id . '"</td>';
                        $html .= '<td> <input type="text" name="product_name[]" class="form-control input-sm product input_qty_width" readonly required value="' . $value->concatenated_product . '"><input type="hidden" name="product_id[]" class="form-control input-sm product_id input_qty_width" value="' . $value->product_id . '">';
                        $html .= '</td>';
                        $html .= '<td><input type="text" name="batch_number[]" class="form-control input-sm batch_number input_qty_width" readonly required value="' . $batch_number . '"';
                        $html .= '</td>';
                        $html .= '<td><input type="text"  name="serial_number[]" class="form-control input-sm serial_number input_qty_width" readonly required value="' . $serial_number . '"';
                        $html .= '</td>';
                        $html .= '<td><select name="subinventory_id[]" class="form-control input-sm subinventory_id input_qty_width" required>' . $sub_inventory;
                        $html .= '</select></td>';
                        $html .= '<td><select name="sublocator_id[]" class="form-control input-sm sublocator_id input_qty_width" required>' . $sublocators;
                        $html .= '</select></td>';
                        $html .= '<td><input type="text"  name="box_product_qty[]" class="form-control input-sm box_product_qty   input_qty_width" required readonly value="' . $value->box_product_qty . '"';
                        $html .= '</td>';
                        $html .= '<td><input type="text"  name="inventrory_number[]" class="form-control input-sm inventrory_number  input_qty_width" readonly required value="' . $batch_no . '"';
                        $html .= '</td>';
                        $html .= '</tr>';
                    }
                    $html .= '</tbody></table><div class="form-group text-center"><button type="button" class="btn btn-success me-2 px-4 addbox" id="addbox"> Save</button><button type="button" class="btn btn-secondary px-4"> Cancel</button></div></div>';
                }
            } else {
                $html = '';
                if (count($data) > 0) {
                    $html .= '<div  class="table-responsive">
			<table class=" table table-bordered clone_table">
                       <style>
            .line_no{width:70px;text-align:center;}
            .product{width:350px;}
            .batch_number{width:120px;}
            .serial_number{width:120px;}
            .subinventory_id{width:150px;} 
            .sublocator_id{width:150px;}
            .box_product_qty{width:120px;}
            .inventrory_number{width:120px;} 
			 .accept_qty{width:60px;} 
        </style> 
                         <thead class="table-light">
                         <tr style="background-color:#05234e;color:#fff;"> 
                         <th>Line No</th>
                         <th>Product</th>
                         <th>Inward Batch Number</th>
                        <th>Inward Serial Number</th>
                        <th>Sub Inventory</th>
                        <th>Sub Locator</th>
                        <th>Box Quantity</th>
                        <th>Lot Number</th>
                        </tr>
                            </thead>
                            <tbody>
                                    ';
                    $serialdata = \DB::select("SELECT p_grn_lines_t.grn_id as hdrid,
                                p_grn_hdr_t.quality_check,
                                p_grn_lines_t.product_id,
                                p_grn_lines_t.batch_number,
                                p_grn_lines_t.serial_number,
                                p_grn_lines_t.box_product,
                                p_qc_lines_t.qc_line_id,
                                p_qc_lines_t.accept_qty,
                                p_qc_lines_t.product_id,
                                concat(m_products_t.product_code,'-',m_products_t.concatenated_product)concatenated_product
                                FROM
                                p_qc_header_t
                                LEFT JOIN p_qc_lines_t ON p_qc_lines_t.qc_header_id = p_qc_header_t.qc_header_id
                                LEFT JOIN p_grn_hdr_t ON p_grn_hdr_t.grn_id = p_qc_header_t.grn_number
                                LEFT JOIN p_grn_lines_t ON p_grn_lines_t.grn_id = p_grn_hdr_t.grn_id
                                LEFT JOIN m_products_t ON m_products_t.product_id = p_qc_lines_t.product_id
                                WHERE p_qc_lines_t.qc_line_id ='$id' group by p_grn_lines_t.grn_id ");

                    $batch_number = json_decode($serialdata[0]->batch_number);
                    $serial_number = json_decode($serialdata[0]->serial_number);
                    $box_product = json_decode($serialdata[0]->box_product);
                    $hdrid = $serialdata[0]->hdrid;
                    $product_name = $serialdata[0]->concatenated_product;
                    $accept_qty = $serialdata[0]->accept_qty;
                    $html .= '<span><style width:150px;></style>SERIALWISE Accepted Qty in QC :  </span>';
                    $html .= '<input type="text" name="accept_qty[]" class="form-control input-sm accept_qty btn btn-outline-info" value="' . $accept_qty . '" readonly="readonly">';
                    $data_l = \DB::select("SELECT i_qoh_detail_t.batch_number from i_qoh_detail_t where qoh_source='PURCHASE_STOREMOVE'");
                    $d_count = count($data_l);
                    $batch_no_1 = "1000";
                    if ($d_count == 0) {
                        $batch_no1 = $batch_no_1;
                    } else {
                        $batch_no1 = $batch_no_1 + $d_count;
                    }
                    foreach ($batch_number as $key => $value) {

                        $batch_no = $batch_no1 + $key;
                        $html .= '<td><input type="text" name="line_no[]" class="form-control input-sm line_no" value="' . ($key + 1) . '" readonly="readonly" >
                                   <input type="hidden" name="qc_header_id" class="form-control input-sm qc_header_id input_qty_width" value="' . $hdrid . '"</td>
                                   <input type="hidden" name="qc_line_id" class="form-control input-sm qc_line_id input_qty_width" value="' . $id . '"</td>';
                        $html .= '<td> <input type="text" name="product_name[]" class="form-control input-sm product input_qty_width" readonly required value="' . $product_name . '"><input type="hidden" name="product_id[]" class="form-control input-sm product_id input_qty_width" value="' . $data[0]->product_id . '">';
                        $html .= '</td>';
                        $html .= '<td><input type="text" name="batch_number[]" class="form-control input-sm batch_number input_qty_width" readonly required value="' . $value . '"';
                        $html .= '</td>';
                        $html .= '<td><input type="text"  name="serial_number[]" class="form-control input-sm serial_number input_qty_width" readonly required value="' . $serial_number[$key] . '"';
                        $html .= '</td>';
                        $html .= '<td><select name="subinventory_id[]" class="form-control input-sm subinventory_id input_qty_width" required>' . $sub_inventory;
                        $html .= '</select></td>';
                        $html .= '<td><select name="sublocator_id[]" class="form-control input-sm sublocator_id input_qty_width" required>' . $sublocators;
                        $html .= '</select></td>';
                        $html .= '<td><input type="text"  name="box_product_qty[]" class="form-control input-sm box_product_qty   input_qty_width" required value="' . $accept_qty . '"';
                        $html .= '</td>';
                        $html .= '<td><input type="text"  name="inventrory_number[]" class="form-control input-sm inventrory_number  input_qty_width" readonly required value="' . $batch_no . '"';
                        $html .= '</td>';
                        $html .= '</tr>';
                    }
                    $html .= '</tbody></table><div class="form-group text-center"><button type="button" class="btn btn-success me-2 px-4 addbox" id="addbox"> Save</button><button type="button" class="btn btn-secondary px-4"> Cancel</button></div></div>';
                }

            }
        } else {
            $data = \DB::select("SELECT
                    p_grn_lines_t.grn_id as hdrid,
                    p_grn_hdr_t.quality_check,
                    p_grn_lines_t.product_id,
                    p_grn_lines_t.batch_number,
                    p_grn_lines_t.serial_number,
                    p_grn_lines_t.box_product,
                    p_grn_lines_t.receive_qty as box_product_qty,
                    concat(m_products_t.product_code,'-',m_products_t.concatenated_product)concatenated_product
            FROM
                p_grn_lines_t
                LEFT JOIN p_grn_hdr_t ON p_grn_lines_t.grn_id = p_grn_hdr_t.grn_id
            LEFT JOIN m_products_t ON m_products_t.product_id = p_grn_lines_t.product_id
            WHERE
                p_grn_lines_t.grn_line_id = '$id' AND (p_grn_hdr_t.quality_check = '' OR p_grn_hdr_t.quality_check IS NULL) and m_products_t.qc_check ='No'
                GROUP BY p_grn_lines_t.grn_id");
            $html = '';
            if (count($data) > 0) {
                $product_id = $data[0]->product_id;
                $getsubinventory = \DB::table('m_products_t')->select('subinventory_id', 'sublocator_id')->where('product_id', '=', $product_id)->get();
                $html .= '<div  class="table-responsive">
        <table class=" table table-bordered clone_table">
        <style>
            .line_no{width:70px;text-align:center;}
            .product{width:350px;}
            .batch_number{width:120px;}
            .serial_number{width:120px;}
            .subinventory_id{width:150px;} 
            .sublocator_id{width:150px;}
            .box_product_qty{width:120px;}
            .inventrory_number{width:120px;} 
        </style>
        <thead class="table-light">
        <tr style="background-color:#05234e;color:#fff;"> 
         <th>Line No</th>
         <th>Product</th>
         <th>Inward Batch Number</th>
            <th>Inward Serial Number</th>
            <th>Sub Inventory</th>
            <th>Sub Locator</th>
            <th>Box Quantity</th>
            <th>Lot Number</th>
        </tr>
            </thead>
            <tbody class="clone_lines_body">';
                $sub_inventory = $this->jCombo('m_subinventory_t', 'subinventory_id', 'subinventory_name', $getsubinventory[0]->subinventory_id);
                $sublocators = $this->jcustomselect('m_sublocators_t', 'sublocator_id', 'locator_code', $getsubinventory[0]->sublocator_id, 'and subinventory_id=' . $getsubinventory[0]->subinventory_id . '');
                $batch_number = json_decode($data[0]->batch_number);
                $serial_number = json_decode($data[0]->serial_number);
                $box_product = json_decode($data[0]->box_product);
                $hdrid = $data[0]->hdrid;
                $product_name = $data[0]->concatenated_product;
                $data_l = \DB::select("SELECT i_qoh_detail_t.batch_number from i_qoh_detail_t where qoh_source='PURCHASE_STOREMOVE'");
                $d_count = count($data_l);
                $batch_no_1 = "1000";
                if ($d_count == 0) {
                    $batch_no1 = $batch_no_1;
                } else {
                    $batch_no1 = $batch_no_1 + $d_count;
                }
                foreach ($batch_number as $key => $value) {

                    $batch_no = $batch_no1 + $key;

                    $html .= '<td><input type="text" name="line_no[]" class="form-control input-sm line_no" value="' . ($key + 1) . '" readonly="readonly" >
                 <input type="hidden" name="qc_header_id" class="form-control input-sm qc_header_id input_qty_width" value="' . $hdrid . '"</td>
                                   <input type="hidden" name="qc_line_id" class="form-control input-sm qc_line_id input_qty_width" value="' . $id . '"</td>';

                    $html .= '<td> <input type="text" name="product_name[]" class="form-control input-sm product input_qty_width" readonly required value="' . $product_name . '"><input type="hidden" name="product_id[]" class="form-control input-sm product_id input_qty_width" value="' . $data[0]->product_id . '">';

                    $html .= '</td>';
                    $html .= '<td><input type="text" name="batch_number[]" class="form-control input-sm batch_number input_qty_width" readonly required value="' . $value . '"';

                    $html .= '</td>';
                    $html .= '<td><input type="text"  name="serial_number[]" class="form-control input-sm serial_number input_qty_width" readonly required value="' . $serial_number[$key] . '"';

                    $html .= '</td>';
                    $html .= '<td><select name="subinventory_id[]" class="form-control input-sm subinventory_id input_qty_width" required>' . $sub_inventory;

                    $html .= '</select></td>';
                    $html .= '<td><select name="sublocator_id[]" class="form-control input-sm sublocator_id input_qty_width" required>' . $sublocators;

                    $html .= '</select></td>';
                    $html .= '<td><input type="text"  name="box_product_qty[]" class="form-control input-sm box_product_qty   input_qty_width" required readonly value="' . $box_product[$key] . '"';
                    $html .= '</td>';
                    $html .= '<td><input type="text"  name="inventrory_number[]" class="form-control input-sm inventrory_number  input_qty_width" readonly required value="' . $batch_no . '"';

                    $html .= '</td>';
                    $html .= '</tr>';
                }



                $html .= '</tbody></table><div class="form-group text-center mt-4"><button type="button" class="btn btn-success px-4 me-2 addbox" id="addbox"> Save</button><button type="button" class="btn btn-secondary px-4"> Cancel</button></div></div>';

            }

        }
        return $html;

    }


}
