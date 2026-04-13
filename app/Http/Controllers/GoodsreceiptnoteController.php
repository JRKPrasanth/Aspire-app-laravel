<?php

namespace App\Http\Controllers;

use App\goodsreceiptnote;
use Mail;
use App\grnlines;
use App\Purchaseorderlines;
use App\Product;
use App\uomcodes;
use Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class GoodsreceiptnoteController extends Controller
{
    public $module = "grn";
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
        $this->data['pageMethod'] = "grn";
        $SQL = "SELECT p_grn_hdr_t.grn_id AS grn_id,
                    p_grn_hdr_t.grn_number AS grn_number,
                    p_grn_hdr_t.grn_status AS grn_status,
                    p_grn_hdr_t.dc_number AS dc_number,
                    p_grn_hdr_t.source,
                    p_grn_hdr_t.dc_date AS dc_date,
                    p_po_hdr_t.po_number AS po_number,
                    p_po_hdr_t.po_date AS po_date,
                    m_supplier_t.supplier_name,
                    m_subcontract_supplier_t.subcontract_name,
                    p_po_invoice_hdr_t.po_invoice_status
                    FROM `p_grn_hdr_t` LEFT JOIN p_po_hdr_t ON(p_po_hdr_t.po_hdr_id = p_grn_hdr_t.`po_number`)
                    LEFT JOIN m_supplier_t ON(m_supplier_t.supplier_id = p_grn_hdr_t.`supplier_id`)
                    LEFT JOIN m_subcontract_supplier_t ON(m_subcontract_supplier_t.subcontract_supplier_id = p_grn_hdr_t.`subcontract_supplier_id`)
                    LEFT JOIN p_po_invoice_hdr_t ON(p_po_invoice_hdr_t.grn_number = p_grn_hdr_t.`grn_id`)
                    WHERE 1 = 1 GROUP BY p_grn_hdr_t.grn_id";

        $download_SQL = "SELECT p_grn_hdr_t.grn_id AS grn_id,
                    p_grn_hdr_t.grn_number AS grn_number,
                    p_grn_hdr_t.grn_status AS grn_status,
                    p_grn_hdr_t.dc_number AS dc_number,
                    p_grn_hdr_t.source,
                    p_grn_hdr_t.dc_date AS dc_date,
                    p_po_hdr_t.po_number AS po_number,
                    p_po_hdr_t.po_date AS po_date,
                    m_supplier_t.supplier_name,
                    m_subcontract_supplier_t.subcontract_name,
                    p_po_invoice_hdr_t.po_invoice_status
                    FROM `p_grn_hdr_t` LEFT JOIN p_po_hdr_t ON(p_po_hdr_t.po_hdr_id = p_grn_hdr_t.`po_number`)
                    LEFT JOIN m_supplier_t ON(m_supplier_t.supplier_id = p_grn_hdr_t.`supplier_id`)
                    LEFT JOIN m_subcontract_supplier_t ON(m_subcontract_supplier_t.subcontract_supplier_id = p_grn_hdr_t.`subcontract_supplier_id`)
                    LEFT JOIN p_po_invoice_hdr_t ON(p_po_invoice_hdr_t.grn_number = p_grn_hdr_t.`grn_id`)
                    WHERE 1 = 1 GROUP BY p_grn_hdr_t.grn_id";

        $result1 = \DB::select($download_SQL);
        $result1 = collect($result1)->map(function ($x) {
            return (array) $x;
        })->toArray();
        if (isset($_GET['download'])) {
            return $result1;
        }
        $result = \DB::select($SQL);
        $this->data['result'] = json_encode($result);

        return view("grn.table", $this->data);
    }

    /*  purpose for Display GRN Data in JQgrid function */
    public function getGrnData()
    {
        $wh = '';

        $loc = "1";
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');


        $wh .= $grid_data = $this->grid_check('v1', 'dc_date');

        $SQL = "select * from (SELECT p_grn_hdr_t.grn_id AS grn_id,
                    p_grn_hdr_t.grn_number AS grn_number,
                    p_grn_hdr_t.grn_status AS grn_status,
                    p_grn_hdr_t.dc_number AS dc_number,
                    p_grn_hdr_t.source,
                    p_grn_hdr_t.dc_date AS dc_date,
                    p_po_hdr_t.po_hdr_id AS po_hdr_id,
                    p_grn_hdr_t.po_number AS po_number,
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
                    WHERE 1 = 1 and p_grn_hdr_t.source!='LABOURPO')v1 where 1=1 $wh GROUP BY v1.grn_id";

        $result = \DB::select($SQL);

        foreach ($result as $k => $v) {
            $po_num = $v->po_number;
            if ($po_num != null) {
                $po_numb = \DB::select("select po_number from p_po_hdr_t where po_hdr_id in ($po_num)");

                $po = "";
                foreach ($po_numb as $pk => $pv) {

                    $po .= $pv->po_number . ",";
                }
                $po_number = rtrim($po, ",");
                $result[$k]->po_number = $po_number;
            }
        }

        return DataTables::of($result)->make(true);
    }

    /* Purpose For :Create From PO Index Function to Call Table Blade*/
    public function purchasetable()
    {
        $this->data['suppliername'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        $this->data['pageMethod'] = "purchasetable";
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
                        m_supplier_t.supplier_id=p_po_hdr_t.`supplier_id`) where 1=1 and p_po_hdr_t.po_type!='LABOUR' and p_po_hdr_t.grn_status!=1  and p_po_hdr_t.po_status='APPROVED'
                        and p_po_hdr_t.need_to_close=0 and p_po_hdr_t.amendment_status!=1";

        $result = \DB::select($SQL);
        //dd($result);
        $poline = Purchaseorderlines::get();
        foreach ($result as $key => $value) {
            $totorder = $poline->where('po_hdr_id', $value->po_hdr_id)->where('grn_status', '!=', 1)->sum('qty');

            //   $sql =\DB::select("select sum(receive_qty) as qty from p_grn_hdr_t left join p_grn_lines_t on p_grn_hdr_t.grn_id =p_grn_lines_t.grn_id where p_grn_hdr_t.po_number = '".$value->po_hdr_id."'");
            $sql = \DB::select("select sum(received_qty) as qty from p_po_lines_t where 1=1 and grn_status=0 and p_po_lines_t.po_hdr_id = '" . $value->po_hdr_id . "'");
            if ($sql[0]->qty !== null) {
                $grn = $sql[0]->qty;
            } else {
                $grn = 0;
            }

            if ($grn == $totorder) {
                $status = "INITIATED";
            } else if ($grn < $totorder) {
                $status = "PARTIALLY COMPLETED";
            }

            $result[$key]->grn_status = $status;
        }

        $result = array_values($result);
        $this->data['result'] = json_encode($result);
        return view("grn.po_table", $this->data);
    }

    /*  purpose for Display PO Data in JQgrid function */
    public function getPurchaseorderData()
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
                        m_supplier_t.supplier_id=p_po_hdr_t.`supplier_id`) where 1=1 $wh and p_po_hdr_t.po_type!='LABOUR' and p_po_hdr_t.po_status='APPROVED' and p_po_hdr_t.need_to_close=0 and p_po_hdr_t.amendment_status!=1";


        $result = \DB::select($SQL);
        $poline = Purchaseorderlines::get();
        foreach ($result as $key => $value) {
            $totorder = $poline->where('po_hdr_id', $value->po_hdr_id)->sum('qty');
            $sql = \DB::select("select sum(receive_qty) as qty from p_grn_hdr_t left join p_grn_lines_t on p_grn_hdr_t.grn_id =p_grn_lines_t.grn_id where p_grn_hdr_t.po_number = '" . $value->po_hdr_id . "'");
            if ($sql[0]->qty !== null) {
                $grn = $sql[0]->qty;
            } else {
                $grn = 0;
            }

            if ($totorder == $grn || $grn > $totorder) {
                $status = "COMPLETED";
            } else if ($grn == 0) {
                $status = "INITIATED";
            } else if ($grn < $totorder) {
                $status = "PARTIALLY COMPLETED";
            }

            $result[$key]->grn_status = $status;
        }
        foreach ($result as $k => $v) {
            if ($v->grn_status == "COMPLETED") {
                unset($result[$k]);
            }
        }

        $result = array_values($result);

        return DataTables::of($result)->make(true);
    }

    /* Purpose For :Create From GIN Index Function to Call Table Blade*/
    public function gintable()
    {
        $this->data['suppliername'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        $this->data['pageMethod'] = "gintable";
        return view("grn.gin_table", $this->data);
    }

    /*  purpose for Display GIN Data in JQgrid function */
    public function getgoodsinwardData()
    {
        $wh = '';

        $loc = "1";
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');
        if ($groupname == '1' || $groupname == 'Admin') {
            $wh .= 'and  p_gin_hdr_t.company_id=' . $compy;
        } else {
            $wh .= 'and  p_gin_hdr_t.company_id=' . $compy . ' and p_gin_hdr_t.location_id=' . $loc;
        }

        $SQL = "SELECT
                        p_gin_hdr_t.p_gin_hdr_id,
                        p_gin_hdr_t.gin_number,
                        p_gin_hdr_t.dc_number,
                        p_gin_hdr_t.dc_date,
                        p_gin_hdr_t.total_packs,
                        m_supplier_t.supplier_name
                        FROM
                        p_gin_hdr_t
                        LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = p_gin_hdr_t.supplier_id
                        where 1=1 and p_gin_hdr_t.gin_status='INITIATED' and p_gin_hdr_t.grn_status='0' $wh order by p_gin_hdr_t.p_gin_hdr_id DESC";

        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);
    }

    /* Purpose for Create Function*/
    public function create($id = null, $idss = null)
    {

    $this->data['groupname'] = \Session::get('groupname');
        if ($id == 0) {
            $this->data['row'] = (object) array();
            $this->data['row']->grn_id = "";
            $this->data['row']->grn_number = "";
            $this->data['row']->grn_description = "";
            $this->data['row']->gin_id = "";
            $this->data['row']->grn_status = "";
            $this->data['row']->source = "GRN";
            $this->data['checked_by'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'first_name', '', " and group_type IN(8,9)");
            $this->data['approved_by'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'first_name', '', " and group_type IN(8,9)");
            $this->data['po_number'] = "";
            $this->data['row']->po_number = "";
            $this->data['row']->po_date = "";
            $this->data['row']->po_hdr_id = "";
            $this->data['row']->dc_number = "";
            $this->data['row']->dc_date = date('Y-m-d');
            $this->data['row']->grn_date = date('d-m-Y');
            $this->data['row']->supplier_type = "";
            $this->data['row']->invoice = "";
            $this->data['row']->reference_number = "DIRECT GRN";
            $this->data['row']->reference_id = "";
            $this->data['row']->total_packs = "";
            $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', '');
            $this->data['subcontract_supplier_id'] = $this->jCombo('m_subcontract_supplier_t', 'subcontract_supplier_id', 'subcontract_number|subcontract_name', '');

            $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'GRN');
            $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '', '');
            $this->data['po_no'] = $this->jCombocomp('p_po_hdr_t', 'po_hdr_id', 'po_number', '', '');
            $this->data['po_hdr_id'] = "";
            $this->data['linedata'] = array();
            $this->data['idss'] = 2;
            $idss = 2;
        } else if ($idss != "2") {

            //get from PO Table
            $this->data['id'] = $id;

            $table = \DB::table('p_po_hdr_t')->whereIn('po_hdr_id', explode(',', $id))->get();

            $this->data['row'] = $table[0];
            $tablelines = \DB::table('p_po_lines_t')->whereIn('po_hdr_id', explode(',', $id))->where('status', '0')->get();

            $this->data['row']->grn_id = "";
            $this->data['row']->reference_id = $id;
            $ref = '';
            $pohdrid = '';
            foreach ($table as $k => $v) {
                $ref .= $v->po_number . ",";
                $pohdrid .= $v->po_hdr_id . ",";
            }
            $reference_no = rtrim($ref, ",");
            $poheaderid = rtrim($pohdrid, ",");

            $this->data['row']->reference_number = $reference_no;
            $this->data['row']->source = "PO";
            $this->data['row']->dc_number = "";
            $this->data['row']->dc_date = date('Y-m-d');
            $this->data['row']->grn_date = date('Y-m-d');
            $this->data['row']->bill_date = "";
            $this->data['row']->bill_due_date = "";
            $this->data['row']->gin_id = "";
            $this->data['row']->total_packs = "";
            $this->data['checked_by'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'first_name', '', " and group_type IN(8,9)");
            $this->data['approved_by'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'first_name', '', " and group_type IN(8,9)");
            $this->data['row']->supplier_type = "SUPPLIER";
            $this->data['idss'] = "";
            $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', $table[0]->supplier_id);
            $this->data['subcontract_supplier_id'] = $this->jCombo('m_subcontract_supplier_t', 'subcontract_supplier_id', 'subcontract_number|subcontract_name', '');

            // $refid=$table[0]->reference_id;
            $ref_id = '';

            foreach ($table as $k => $v) {
                $ref_id .= $v->reference_id . ",";
            }
            $refid = rtrim($ref_id, ",");


            $amendmenttable = \DB::table('p_po_hdr_t')->whereIn('reference_id', explode(',', $id))->get();

            if (count($amendmenttable) > 0) {
                $source = $amendmenttable[0]->source;
            } else {
                $source = "";
            }
            // dd($source);
            if ($source == "PO") {
                //to get amendment PO Qty
                $this->data['po_number'] = $this->data['po_number'] = $this->jcustommultiselect('p_po_hdr_t', 'po_hdr_id', 'po_number', $poheaderid, 'and po_hdr_id in ' . "(" . $poheaderid . ")");

                $tablelines1 = \DB::table('p_po_lines_t')->leftjoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'p_po_lines_t.po_hdr_id')->whereIn('p_po_hdr_t.reference_id', explode(',', $refid))->where('status', '0')->get();
                $this->data['linedata'] = $tablelines1;
            } else {

                $this->data['po_number'] = $this->jcustommultiselect('p_po_hdr_t', 'po_hdr_id', 'po_number', $poheaderid, 'and po_hdr_id in ' . "(" . $poheaderid . ")");

                $this->data['linedata'] = $tablelines;
            }
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->po_no = $this->jcustommultiselect('p_po_hdr_t', 'po_hdr_id', 'po_number', $value->po_hdr_id, '');
                $this->data['linedata'][$key]->po_hdr_id = $value->po_hdr_id;
            }
            $this->data['grn_status'] = "";
        } else {
            //get from GIN Table
            $this->data['id'] = $id;
            $table = \DB::table('p_gin_hdr_t')->where('p_gin_hdr_id', $id)->get();
            $this->data['row'] = $table[0];

            $this->data['linedata'] = array();
            $this->data['row']->gin_id = $this->jCombocomp('p_gin_hdr_t', 'p_gin_hdr_id', 'gin_number', $table[0]->p_gin_hdr_id);

            $this->data['idss'] = $idss;
            $this->data['row']->reference_id = $id;
            $this->data['row']->reference_number = $table[0]->gin_number;
            $this->data['row']->source = "GIN";
            $this->data['row']->po_hdr_id = "";
            $this->data['row']->po_date = "";
            $this->data['checked_by'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'first_name', '', " and group_type IN(8,9)");
            $this->data['approved_by'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'first_name', '', " and group_type IN(8,9)");
            $this->data['row']->supplier_type = $table[0]->supplier_type;

            if ($table[0]->supplier_type == "SUPPLIER") {

                $this->data['po_number'] = $this->jcustomselectcomp('p_po_hdr_t', 'po_hdr_id', 'po_number', '', ' and supplier_id=' . $table[0]->supplier_id . ' and  po_status !="CANCELLED"' . ' and  po_type !="LABOUR"' . ' and  amendment !="Yes"' . ' and  po_status ="APPROVED"');
            } else {
                $this->data['po_number'] = "";
            }
            $this->data['row']->dc_date = date('Y-m-d');
            $this->data['row']->grn_date = date('Y-m-d');

            $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', $table[0]->supplier_id);
            $this->data['subcontract_supplier_id'] = $this->jCombo('m_subcontract_supplier_t', 'subcontract_supplier_id', 'subcontract_number|subcontract_name', $table[0]->subcontract_supplier_id);

            $this->data['row']->total_packs = $table[0]->total_packs;
            $this->data['grn_status'] = "1";
        }

        $this->data['supnameopt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        $this->data['prdcatopt'] = $this->jqgridselect('m_product_category_t', 'product_category_id', 'category_name');
        $this->data['prdnameopt'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');
        $this->data['row']->grn_id = "";
        $this->data['row']->grn_number = "";
        $this->data['row']->grn_status = "DRAFT";
        $this->data['row']->grn_description = "";

        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {

                $this->data['linedata'][$key]->grn_line_id = "";
                $product = $value->product_id;

                $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                if ($value->uom_code_id != "") {
                    $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id, 'and uom_code_id=' . $value->uom_code_id);
                }


                //get GRN from Po data
                if ($table[0]->source == "PO") {
                    $tablelines = \DB::table('p_po_lines_t')->where('po_hdr_id', $id)->where('status', '0')->get();
                    $amendmenttable = \DB::table('p_po_hdr_t')->where('reference_id', $refid)->get();
                    $poqty = $value->qty;
                    $pendingqty = $value->pending_qty;
                    $unit_price = $value->unit_price;
                } else {
                    $poqty = $value->qty;
                    $pendingqty = $value->pending_qty;
                    $unit_price = $value->unit_price;
                }
                $poqty = $value->qty;
                $pendingqty = $value->pending_qty;
                $this->data['linedata'][$key]->po_hdr_id = $value->po_hdr_id;
                $this->data['linedata'][$key]->unit_price = $unit_price;
                $this->data['linedata'][$key]->receive_qty = "";
                $this->data['linedata'][$key]->qty = $poqty;
                $this->data['linedata'][$key]->pending_qty = $pendingqty;
                $this->data['linedata'][$key]->gin_qty = "";


                $this->data['linedata'][$key]->box_qty = '';
                $this->data['linedata'][$key]->packed_discription = '';
                $this->data['linedata'][$key]->box_product = '0';
                $this->data['linedata'][$key]->batch_number = '0';
                $this->data['linedata'][$key]->serial_number = '0';
            }
        } else {

            $this->data['linedata'][0] = (object) array();
            $this->data['linedata'][0]->grn_line_id = "";
            $this->data['linedata'][0]->product_id = $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'GRN');

            $this->data['linedata'][0]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
            $this->data['linedata'][0]->po_no = $this->jCombocomp('p_po_hdr_t', 'po_hdr_id', 'po_number', '', '');
            $this->data['linedata'][0]->box_qty = '';
            $this->data['linedata'][0]->packed_discription = '';
            $this->data['linedata'][0]->box_product = '0';
            $this->data['linedata'][0]->batch_number = '0';
            $this->data['linedata'][0]->serial_number = '0';
            $this->data['linedata'][0]->arrived_qty = "";
            $this->data['linedata'][0]->receive_qty = "";
            $this->data['linedata'][0]->qty = "";
            $this->data['linedata'][0]->pending_qty = "";
            $this->data['linedata'][0]->unit_price = "";
        }

        return view('grn.form', $this->data);
    }

    /* Purpose for Edit Function*/
    public function edit($id = null, $ids = null)
    {
        //dd($id);
         $this->data['groupname'] = \Session::get('groupname');
        $this->data['id'] = $id;
        $table = \DB::table('p_grn_hdr_t')->where('grn_id', $id)->get();

        $this->data['row'] = $table[0];
        $tablelines = \DB::table('p_grn_lines_t')->where('grn_id', $id)->get();
        $this->data['linedata'] = $tablelines;

        $this->data['row']->po_hdr_id = $table[0]->po_number;
        $this->data['po_number'] = $this->jcustommultiselect1('p_po_hdr_t', 'po_hdr_id', 'po_number', $table[0]->po_number, ' and supplier_id=' . $table[0]->supplier_id . ' and  po_status !="CANCELLED"' . ' and  po_type !="LABOUR"' . ' and  po_status !="DRAFT"');
        $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', $table[0]->supplier_id);
        $this->data['subcontract_supplier_id'] = $this->jCombo('m_subcontract_supplier_t', 'subcontract_supplier_id', 'subcontract_number|subcontract_name', $table[0]->subcontract_supplier_id);
        // dd($table[0]->po_number);
        if ($table[0]->reference_number != 'DIRECT GRN') {
            if ($ids == null) {
                $this->data['po_number_name'] = $this->idname('po_number', 'p_po_hdr_t', 'po_hdr_id', $table[0]->po_number);
            } else {
                $this->data['po_number_name'] = $this->idmultiname('po_number', 'p_po_hdr_t', 'po_hdr_id', $table[0]->po_number);
            }
        }
        $this->data['supplier_name'] = $this->idname('supplier_name', 'm_supplier_t', 'supplier_id', $table[0]->supplier_id);
        $this->data['subcontract_supplier_name'] = $this->idname('subcontract_name', 'm_subcontract_supplier_t', 'subcontract_supplier_id', $table[0]->subcontract_supplier_id);
        $this->data['grn_status'] = $table[0]->grn_status;
        $this->data['checked_by'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'first_name', $table[0]->checked_by, " and group_type IN(8,9)");
        $this->data['approved_by'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'first_name', $table[0]->approved_by, " and group_type IN(8,9)");
        //$this->data['grn_date'] =$table[0]->grn_date;
        $this->data['row']->reference_id = $table[0]->reference_id;
        $this->data['row']->reference_number = $table[0]->reference_number;
        $this->data['row']->source = $table[0]->source;
        $this->data['row']->total_packs = $table[0]->total_packs;
        $this->data['row']->gin_id = $gin_id = $table[0]->gin_number;
        $dc_date = $table[0]->dc_date;
        $grn_date = $table[0]->grn_date;
        //dd($grn_date);
        $this->data['row']->dc_date = date(\Session::get('p_date_format'), strtotime($dc_date));
        $this->data['row']->grn_date = date(\Session::get('p_date_format'), strtotime($grn_date));
        // dd($this->data['row']->grn_date);
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
                    $this->data['linedata'][$key]->po_no = $this->jCombocomp('p_po_hdr_t', 'po_hdr_id', 'po_number', $value->po_no);
                    $this->data['linedata'][$key]->qty = $qty;
                    $this->data['linedata'][$key]->arrived_qty = $receive_qty;
                    $this->data['linedata'][$key]->receive_qty = $value->receive_qty;
                    $this->data['linedata'][$key]->remaining_qty = $remaining_qty;
                    $this->data['linedata'][$key]->gin_qty = '';
                    if ($value->box_product != "0" && $value->box_qty != "0.0") {
                        $this->data['linedata'][$key]->box_product = implode(',', json_decode($value->box_product));
                        $this->data['linedata'][$key]->batch_number = implode(',', json_decode($value->batch_number));
                        $this->data['linedata'][$key]->serial_number = implode(',', json_decode($value->serial_number));
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
                    if (!empty($value->box_product) && $value->box_product != "0" && $value->box_qty != "0.0") {
                        //dd($value->box_product);
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

        if ($ids == null) {
            //		dd($this->data);
            return view('grn.form', $this->data);
        } else {
            return view('grn.view', $this->data);
        }
    }

    /* Purpose To Generate Box Qty Popup */
    public function boxdetails($id = null, $data = null, $data2 = null, $data1 = null)
    {
        $box_edit = ($data != "0") ? explode(",", $data) : [];
        $box_edit1 = ($data1 != "0") ? explode(",", $data1) : [];
        $box_edit2 = ($data2 != "0") ? explode(",", $data2) : [];

        // Get PO details if available
        if (isset($_GET['po_id']) && isset($_GET['product_id'])) {
            $poid = $_GET['po_id'];
            $product_id = $_GET['product_id'];
            $podetail = \DB::select("select product_id,qty from p_po_lines_t where po_hdr_id in ($poid) and product_id='$product_id'");
            $orderqty = (count($podetail) > 0) ? $podetail[0]->qty : "";
        } else {
            $orderqty = "";
        }

        $html = '';
        $html .= '
        <div class="row mb-3">
            <label for="orderqty" class="col-form-label col-md-4 fw-bold">Order Qty</label>
            <div class="col-md-4">
                <input type="text" class="form-control form-control-sm orderqty" id="orderqty" value="' . $orderqty . '" readonly>
            </div>
        </div>';

        $html .= '
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle batch_table_box">
                <thead class="table-dark text-center">
                    <tr>
                        <th style="width:60px;">#</th>
                        <th style="width:120px;">Product Qty</th>
                        <th style="width:150px;">Batch Number</th>
                        <th style="width:150px;">Serial Number</th>
                    </tr>
                </thead>
                <tbody>';

        for ($i = 0; $i < $id; $i++) {
            $html .= '<tr>';
            // Line No
            $html .= '
            <td class="text-center">
                <input type="text" class="form-control form-control-sm line_no text-center" 
                       value="' . ($i + 1) . '" readonly>
            </td>';

            // Product Qty
            $html .= '
            <td>
                <input type="text" class="form-control form-control-sm product_qty" required
                       value="' . (!empty($box_edit) ? $box_edit[$i] : '') . '">
            </td>';

            // Batch Number
            $html .= '
            <td>
                <input type="text" class="form-control form-control-sm batch_number" required
                       value="' . (!empty($box_edit1) ? $box_edit1[$i] : '') . '">
            </td>';

            // Serial Number
            $html .= '
            <td>
                <input type="text" class="form-control form-control-sm serial_number"
                       value="' . (!empty($box_edit2) ? $box_edit2[$i] : '') . '">
            </td>';

            $html .= '</tr>';
        }

        $html .= '
            </tbody>
        </table>
        </div>
        <div class="mt-3 text-end">
            <button type="button" class="btn btn-primary btn-sm addbox" id="addbox">
                <i class="bi bi-plus-circle"></i> Add Box Product
            </button>
        </div>';

        return $html;
    }

    /* Purpose for Save Function*/
    public function save(Request $request)
    {
        
        $po_number = $_POST['bulk_po_no'];
        $po_no = implode(",", $po_number);


        if ($_POST['source'] == "GIN") {
            $po_number = $_POST['bulk_po_no'];
            $po_no = implode(",", $po_number);
            $po_details = \DB::table('p_po_lines_t')->join(
                'm_products_t',
                'm_products_t.product_id',
                '=',
                'p_po_lines_t.product_id'
            )->join(
                    'm_tax_group_t',
                    'm_tax_group_t.tax_group_id',
                    '=',
                    'p_po_lines_t.tax_group_id'
                )->select(
                    'p_po_lines_t.*',
                    'm_products_t.tax_credit',
                    'm_products_t.account_code_id',
                    'm_products_t.disc_account_code',
                    'm_products_t.control_account_id',
                    'm_tax_group_t.display_name'
                )->whereIn("p_po_lines_t.po_hdr_id", explode(",", $po_no))->get();
        }

        $id = '';
        $form = $request->all();
        $form = $request->except([
            '_token',
            'form_config',
            'form_data_json',
            'savestatus',
            'submit_type',
            'choosefile',
            'existing_file',
            'enable-masterdetail',
            'product_code',
            'group_name',
            'category_name',
            'concatenated_product',
            'checked_by',
        ]);

        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');

        $lines_data = [];

        $lineCount = count($request->bulk_product_id);

        for ($i = 0; $i < $lineCount; $i++) {

            // Skip empty lines
            if (empty($request->bulk_product_id[$i])) {
                continue;
            }

            $lines_data['grn_line_id'][$i] = $request->bulk_grn_line_id[$i] ?? null;
            $lines_data['line_no'][$i] = $request->bulk_line_no[$i];
            $lines_data['product_id'][$i] = $request->bulk_product_id[$i];
            $lines_data['po_no'][$i] = $request->bulk_po_no[$i];
            $lines_data['uom_code_id'][$i] = $request->bulk_uom_code_id[$i];
            $lines_data['qty'][$i] = $request->bulk_qty[$i];
            $lines_data['pending_qty'][$i] = $request->bulk_pending_qty[$i];
            $lines_data['box_qty'][$i] = $request->bulk_box_qty[$i];
            $lines_data['receive_qty'][$i] = $request->bulk_receive_qty[$i];
            $lines_data['inventory_status'][$i] = "0";
            $lines_data['qc_status'][$i] = "0";

            // batch related (already arrays)
            $lines_data['batch_number'][$i] = json_encode(explode(',', $request->bulk_batch_number[$i]));
            $lines_data['box_product'][$i] = json_encode(explode(',', $request->bulk_box_product[$i]));
            $lines_data['serial_number'][$i] = json_encode(explode(',', $request->bulk_serial_number[$i]));

            // common audit fields
            $lines_data['created_by'][$i] = auth()->id();
            $lines_data['last_updated_by'][$i] = auth()->id();
            $lines_data['created_at'][$i] = now();
            $lines_data['updated_at'][$i] = now();
            $lines_data['location_id'][$i] = session('location');
            $lines_data['company_id'][$i] = session('companyid');
            $lines_data['organization_id'][$i] = session('organization');
        }

        // GIN specific
        if ($request->source === 'GIN') {
            $lines_data['po_hdr_id'] = $request->bulk_po_no;
        }else{
            $lines_data['po_hdr_id'] = '0';
        }

        $box = $_POST['bulk_box_qty'];
        $batch = $_POST['bulk_batch_number'];
        $box_product = $_POST['bulk_box_product'];
        $serial_number = $_POST['bulk_serial_number'];

        foreach ($batch as $k => $v) {
            if ($box[$k] != 0) {
                $lines_data['batch_number'][$k] = json_encode(explode(',', $v));
                $lines_data['box_product'][$k] = json_encode(explode(',', $box_product[$k]));
                $lines_data['serial_number'][$k] = json_encode(explode(',', $serial_number[$k]));
            } else {
                $lines_data['batch_number'][$k] = 0;
                $lines_data['box_product'][$k] = 0;
                $lines_data['serial_number'][$k] = 0;
            }
        }
        
        if ($_POST['bulk_po_no'] == "") {
             foreach ($lines_data as &$line) 
            $line['po_hdr_id'] = "0";
            unset($line);
        }else{
             $lines_data['po_hdr_id'] = $_POST['bulk_po_no'];
        }
        
        if ($_POST['grn_number'] == "") {
            if ($_POST['source'] != "GRN") {
                $seqno = $this->Seqnoe('GRN-', 'p_grn_hdr_t', '', 'grn_count');
                $data['grn_number'] = $seqno[0];
                $data['grn_count'] = $seqno[1];
            } else {
                $seqno = $this->Seqnoe('DGRN-', 'p_grn_hdr_t', '', 'directgrn_count');
                $data['grn_number'] = $seqno[0];
                $data['directgrn_count'] = $seqno[1];
            }
        } else {
            $seqno[0] = $_POST['grn_number'];
        }
        
        $data['grn_date'] = date("Y-m-d", strtotime($_POST['grn_date']));
        \DB::beginTransaction();
        try {
            if (isset($_POST['gin_number'])) {
                $data['gin_number'] = $_POST['gin_number'];
            }

            if (isset($_POST['po_number'])) {
                $data['po_number'] = implode(",", $_POST['po_number']);
                unset($lines_data['po_number']);
            }

            $id = $this->model->insertRow($data);
           // dd($lines_data);
            $lid = $this->submodel->subgridSave($lines_data, $id);


            $sql = (object) array();
            foreach ($_POST['bulk_product_id'] as $key => $value) {



                if ($_POST['grn_status'] == "INITIATED" && $_POST['bulk_po_no'][$key] != "") {

                    $podel = \DB::table('p_po_lines_t')->where('po_hdr_id', $_POST['bulk_po_no'][$key])->where('product_id', $value)->get();
                    $po_number = $_POST['bulk_po_no'][$key];
                    $prd_id = $value;
                    $balqty = $podel[0]->pending_qty;
                    $rcvqty = $podel[0]->received_qty;
                    $receiveqty = $_POST['bulk_receive_qty'][$key];
                    $bqty = $balqty - $receiveqty;
                    $rcvqty1 = $receiveqty + $rcvqty;

                    \DB::update("update p_po_lines_t set pending_qty='$bqty',received_qty='$rcvqty1' where po_hdr_id='" . $po_number . "'
        and product_id='$prd_id'");
                    if ($_POST['bulk_pending_qty'][$key] == $_POST['bulk_receive_qty'][$key]) {

                        \DB::table('p_po_lines_t')->where('po_hdr_id', $po_number)->where('product_id', $prd_id)->update(['grn_status' => 1]);
                    }

                    $statuss = \DB::table('p_po_lines_t')->select('grn_status')->where('po_hdr_id', $po_number)->where(
                        'grn_status',
                        0
                    )->get();

                    $status = \DB::table('p_po_lines_t')->select('grn_status')->where('po_hdr_id', $po_number)->get();

                    if (count($statuss) == 0) {
                        \DB::update("update p_po_hdr_t set grn_status='1' where po_hdr_id='" . $_POST['bulk_po_no'][$key] . "'");
                    }
                }


                if ($value != "") {
                    $sql = \DB::table('m_products_t')
                        ->select('product_group_id', 'product_category_id')
                        ->where('product_id', $value)
                        ->first();

                    if ($sql) {
                        $query = \DB::table('m_qcapproval_settings_t')
                            ->select('qc_approver_id', 'qc_checker_id', 'qcapproval_id')
                            ->where('product_group_id', $sql->product_group_id);

                        if ($sql->product_category_id != "9999") {
                            $query->where('product_category_id', $sql->product_category_id);
                        }

                        $query = $query->first();

                        if ($query) {
                            \DB::table('p_grn_hdr_t')
                                ->where('grn_id', $id)
                                ->update([
                                        'approver_id' => $query->qc_approver_id,
                                        'checker_id' => $query->qc_checker_id
                                    ]);
                        }
                    }
                }
            }

            if (isset($_POST['gin_number'])) {
                if ($_POST['gin_number'] != '') {
                    \DB::update("update p_gin_hdr_t set grn_status='1' where p_gin_hdr_id='" . $_POST['gin_number'] . "'");
                }
            }

    
            $poid = $_POST['po_number'][0] ?? null;

            if ($poid) {
                \DB::table('p_po_hdr_t')
                    ->where('po_hdr_id', $poid)
                    ->update(['grn_status' => 1]);
            }

                $po_number = $_POST['po_number'] ?? '';
            if ($_POST['grn_status'] == "INITIATED" && $po_number != "") {
                $send_notification = $this->sendPopUpHomeNoty($id, "GRN", 'Material Inward', 'grn');
                \DB::table('notifications_t')->where('reference_source_id', $_POST['po_number'])->where('reference_source', 'PO
        APPROVED')->update(['read/unread' => 'read']);
            }

            /* Purpose For Journal Entry Insert*/
            if ($poid != "") {

                if ($_POST['grn_status'] == "INITIATED") {
                    $grn_no = $data['grn_number'];
                    $dcdate = $_POST['dc_date'];
                    $org = \Session::get('organization');
                    $loc = \Session::get('location');
                    $compy = \Session::get('companyid');

                    $journalhdr = \DB::insert("insert into
        f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$grn_no','GRN','$dcdate','$id','APPROVED','$compy','$loc','$org')");
                    $jid = DB::getPdo()->lastInsertId();
                    $po_details = \DB::table('p_po_lines_t')->join(
                        'm_products_t',
                        'm_products_t.product_id',
                        '=',
                        'p_po_lines_t.product_id'
                    )->join(
                            'm_tax_group_t',
                            'm_tax_group_t.tax_group_id',
                            '=',
                            'p_po_lines_t.tax_group_id'
                        )->select(
                            'p_po_lines_t.*',
                            'm_products_t.tax_credit',
                            'm_products_t.account_code_id',
                            'm_products_t.disc_account_code',
                            'm_products_t.control_account_id',
                            'm_tax_group_t.display_name'
                        )->whereIn("p_po_lines_t.po_hdr_id", explode(",", $po_no))->get();
                    $collection = collect($po_details);
                    $inventory_acc = \DB::table('f_account_setting_t')->where('module_name', 'grn')->get();

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
                        $journal_lines_data[$tkey]['account_id'] = $filtered[0]->control_account_id;
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
                    $journal_lines_data[$tkey]['account_id'] = $inventory_acc[0]->inventory_account_id;
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

            // Email Sending Section

            $emp = \Session::get('id');

            $user_mail = \DB::select("select user_mail,first_name from tb_users where employee_id='$emp'");
            if (!empty($user_mail) && !empty($user_mail[0]->user_mail)) {
                $from_umail = $user_mail[0]->user_mail;
            } else {

                $from_umail = "aspire@jrkresearch.com";
            }

            $man_id = \DB::select("select reporting_manager from hr_employee_t where employee_id='$emp'");
            $managr_id = $man_id[0]->reporting_manager;

            $managr_mail = \DB::select("select user_mail from tb_users where employee_id ='$managr_id' and group_id !='15'");

            if (!empty($managr_mail) && !empty($managr_mail[0]->user_mail)) {
                $manr_mail = $managr_mail[0]->user_mail;
            } else {

                $manr_mail = "aspire@jrkresearch.com";
            }

            $emp_name = $user_mail[0]->first_name;
            $grn_date = $_POST['grn_date'];
            if ($_POST['grn_number'] != '') {
                $grn_no = $_POST['grn_number'];
            } else {
                $grn_no = $data['grn_number'] = $seqno[0];
            }
            $product = $_POST['bulk_product_id'];
            $product_count = count($product);
            $from_mail = $from_umail;
            $man_mail = $manr_mail;
            $sub = "$grn_no INITIATED";
            $pro_ids = "'" . implode("','", $product) . "'";

            $qc_pro = \DB::select("SELECT COUNT(product_id) as yes_pro FROM `m_products_t` where product_id IN ($pro_ids) AND
        qc_check = 'Yes'");
            $qc_yes_pro = $qc_pro[0]->yes_pro;
            $qc_pro1 = \DB::select("SELECT COUNT(product_id) as no_pro FROM `m_products_t` where product_id IN ($pro_ids) AND
        qc_check = 'No'");
            $qc_no_pro = $qc_pro1[0]->no_pro;

            if ($qc_yes_pro > 0) {

                $to_mail = "labs@jrkresearch.com,aruna_v@jrkresearch.com,hemashree_k@jrkresearch.com";
                $cc = "$from_mail,$man_mail";
            } else {

                $cc = "aspire@jrkresearch.com";
                $to_mail = "$from_mail,$man_mail";
            }


            $msg = "<p>Dear Team,<br><br>GRN No - $grn_no <br>GRN Date - $grn_date <br>No of Products - $product_count<br>QC Check -
            $qc_yes_pro<br>Non QC Check - $qc_no_pro<br><br>Regards, <br> $emp_name";


            if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                Config::set('mail.username', \Session::get('user_email'));
                Config::set('mail.password', \Session::get('user_password'));
            }

            \Mail::send([], [], function ($message) use ($from_mail, $to_mail, $sub, $cc, $msg) {

                $message->from($from_mail)
                    ->to(explode(',', $to_mail))
                    ->cc(explode(',', $cc))
                    ->subject($sub)
                    ->setBody($msg, 'text/html');
            });


            \DB::commit();
            /**Auditlog**/
            if ($_POST['grn_id'] == "") {
                $action = "create";
            } else {
                $action = "edit";
            }
            $this->auditlog($id, "grn", $action, $_POST, "p_grn_hdr_t");
            return response()->json(array(
                'status' => 'success',
                'message' => 'Saved Successfully',
                'id' => $id,
                'lid' => $lid,
                'auto_no' => $seqno[0]
            ));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');

            \DB::rollback();

            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }
    /* Purpose for GRN Print Function*/
    public function pogrnprint($id)
    {

        require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');

        $row = \DB::table('p_grn_hdr_t')->where('grn_id', $id)->get();
        $po = \DB::table('p_po_hdr_t')->where('po_hdr_id', $row[0]->po_number)->get();
        if ($row[0]->checked_by > 0) {
            $emp_chk = \DB::table('hr_employee_t')->where('employee_id', $row[0]->checked_by)->get();
        } else {
            $emp_chk = \DB::table('hr_employee_t')->where('employee_id', '122')->get();
        }
        if ($row[0]->approved_by > 0) {
            $emp_app = \DB::table('hr_employee_t')->where('employee_id', $row[0]->approved_by)->get();
        } else {
            $emp_app = \DB::table('hr_employee_t')->where('employee_id', '122')->get();
        }
        if (count($po) > 0) {
            $this->data['po_id'] = $row[0]->po_number;
            $this->data['po_number'] = $po[0]->po_number;
            $this->data['po_date'] = $po[0]->po_date;
            $this->data['dc_number'] = $row[0]->dc_number;
            $this->data['remarks'] = $po[0]->remarks;
            $this->data['dc_date'] = $row[0]->dc_date;
            $this->data['checked_by'] = $emp_chk[0]->first_name;
            $this->data['approved_by'] = $emp_app[0]->first_name;
            $this->data['grn_number'] = $row[0]->grn_number;
            $this->data['supplier_id'] = $row[0]->supplier_id;
            $this->data['subcontract_supplier_id'] = $row[0]->subcontract_supplier_id;
            $this->data['other_reference'] = $row[0]->other_reference;
            $sup = \DB::table('m_supplier_t')->where('supplier_id', $row[0]->supplier_id)->get();
            $subcontract = \DB::table('m_subcontract_supplier_t')->where('subcontract_supplier_id', $row[0]->subcontract_supplier_id)->get();

            if (count($subcontract) > 0) {

                $this->data['supplier_name'] = $subcontract[0]->subcontract_name;
            } else {

                $this->data['supplier_name'] = $sup[0]->supplier_name;
            }
        } else {
            $this->data['po_id'] = '';
            $this->data['po_number'] = '';
            $this->data['po_date'] = '';
            $this->data['dc_number'] = $row[0]->dc_number;
            $this->data['checked_by'] = $emp_chk[0]->first_name;
            $this->data['approved_by'] = $emp_app[0]->first_name;
            $this->data['remarks'] = '';
            $this->data['dc_date'] = $row[0]->dc_date;
            $this->data['grn_number'] = $row[0]->grn_number;
            $this->data['supplier_id'] = $row[0]->supplier_id;
            $this->data['subcontract_supplier_id'] = $row[0]->subcontract_supplier_id;
            $this->data['other_reference'] = $row[0]->other_reference;
            $sup = \DB::table('m_supplier_t')->where('supplier_id', $row[0]->supplier_id)->get();
            $subcontract = \DB::table('m_subcontract_supplier_t')->where('subcontract_supplier_id', $row[0]->subcontract_supplier_id)->get();

            if (count($subcontract) > 0) {

                $this->data['supplier_name'] = $subcontract[0]->subcontract_name;
            } else {

                $this->data['supplier_name'] = $sup[0]->supplier_name;
            }
        }

        $company = $this->getCompany($row[0]->company_id);
        if (!empty($company)) {
            $company_name = $company[0]->company_name;
            $this->data['company_name'] = $company_name;
            $this->data['company_logo_name'] = \Session::get('companylogo');
        }

        $grnline = \DB::table('p_grn_lines_t')->where('grn_id', $id)->get();

        if ($row[0]->invoice_created == "Yes") {
            $inv = $this->getInvoice($row[0]->grn_id);
            $this->data['bill_number'] = $inv['bill_number'];
            $this->data['invoice_date'] = $inv['invoice_date'];
        } else {
            $this->data['bill_number'] = "";
            $this->data['invoice_date'] = "";
        }
        $pdt = Product::get();
        $uom = Uomcodes::get();

        foreach ($grnline as $key1 => $value1) {
            if ($value1->qc_status == 1) {
                $getqty = \DB::select("SELECT grnhdr.grn_id,grnlines.grn_line_id,grnlines.qc_status,grnlines.product_id,grnlines.uom_code_id,grnlines.receive_qty,qcline.qc_line_id,case when qcline.reject_qty != 0 then ROUND(qcline.accept_qty,1) else ROUND(grnlines.receive_qty,1) end as accept_qty,qcline.reject_qty,qcline.reason FROM p_grn_hdr_t grnhdr LEFT JOIN p_grn_lines_t grnlines on (grnlines.grn_id=grnhdr.grn_id)  LEFT JOIN  p_qc_header_t qchdr on (qchdr.grn_number=grnhdr.grn_id)  LEFT JOIN  p_qc_lines_t qcline on (qcline.qc_header_id=qchdr.qc_header_id) WHERE grnlines.grn_id= $value1->grn_id  GROUP BY grnlines.grn_line_id");
            } else {
                $getqty = \DB::select("SELECT grnhdr.grn_id,grnlines.grn_line_id,grnlines.qc_status,grnlines.product_id,grnlines.uom_code_id,grnlines.receive_qty,grnlines.receive_qty as accept_qty,0 as reject_qty,'' as reason FROM p_grn_hdr_t grnhdr LEFT JOIN p_grn_lines_t grnlines on (grnlines.grn_id=grnhdr.grn_id) WHERE grnlines.grn_id= $value1->grn_id  group by grnlines.grn_line_id");
            }
        }
        //	dd($getqty);
        foreach ($getqty as $key => $value) {
            $grnlines[$key1][$key] = (object) array();
            $grnlines[$key1][$key]->grn_id = $value->grn_id;
            $grnlines[$key1][$key]->grn_line_id = $value->grn_line_id;
            $grnlines[$key1][$key]->qc_status = $value->qc_status;
            $grnlines[$key1][$key]->product_code = $pdt->where('product_id', $value->product_id)->pluck('product_code')->first();
            $grnlines[$key1][$key]->product = $pdt->where('product_id', $value->product_id)->pluck('concatenated_product')->first();
            $grnlines[$key1][$key]->uom = $uom->where('uom_code_id', $value->uom_code_id)->pluck('uom_code')->first();
            $grnlines[$key1][$key]->received_qty = $value->receive_qty;
            $grnlines[$key1][$key]->accepted_qty = $getqty[$key]->accept_qty;
            $grnlines[$key1][$key]->rejected_qty = $getqty[$key]->reject_qty;

            $grnlines[$key1][$key]->reason = $getqty[$key]->reason;
            $grnlines[$key1][$key]->reference = "";
            $grnlines[$key1][$key]->remarks = "";
        }


        $this->data['grnlines'] = $grnlines;
        return view('grn.printform', $this->data);
    }

    /* purpose to load po based product*/
    public function getpodetails($po_hdrid = null)
    {

        $sql = \DB::select("select p_po_lines_t.product_id,qty,p_po_lines_t.uom_code_id,p_po_lines_t.pending_qty,p_po_lines_t.unit_price,p_po_hdr_t.po_number,p_po_hdr_t.po_hdr_id from p_po_lines_t left join p_po_hdr_t on (p_po_hdr_t.po_hdr_id=p_po_lines_t.po_hdr_id) where p_po_lines_t.po_hdr_id in ($po_hdrid) and p_po_lines_t.pending_qty>'0'");
        //dd($sql);
        foreach ($sql as $key => $val) {
            $data[$key]['po_number'] = $val->po_number;
            $data[$key]['po_hdr_id'] = $val->po_hdr_id;
            $data[$key]['product_id'] = $val->product_id;
            $data[$key]['uom_code_id'] = $val->uom_code_id;
            $data[$key]['qty'] = $val->qty;
            $data[$key]['pending_qty'] = $val->pending_qty;
            $data[$key]['unit_price'] = $val->unit_price;


            $received_qty = \DB::select("select sum(receive_qty) as receivedqty 
                                from p_grn_lines_t 
                                left join p_grn_hdr_t ON
                                p_grn_hdr_t.grn_id=p_grn_lines_t.grn_id
                                where p_grn_hdr_t.po_number in ($po_hdrid) and product_id='$val->product_id'");
            //              $pendingqty=($val->qty)-($received_qty[0]->receivedqty);  
            $pendingqty = $val->pending_qty;

            if ($pendingqty < 0) {
                $data[$key]['pendingqty'] = 0;
            } else {
                $data[$key]['pendingqty'] = $pendingqty;
            }
        }

        return $data;
    }

    /*Purpose To get Company Details For Print*/
    function getCompany($company = null)
    {
        $sql = array();
        $sql = \DB::SELECT("SELECT company_id,company_name,cin_no,gst_no,company_logo_name FROM `m_company_t` WHERE `company_id`=" . $company . "");
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }

    /*Purpose To get Invoice Details For Print*/
    function getInvoice($id)
    {
        $sql = \DB::SELECT("select * from p_po_invoice_hdr_t where grn_number='" . $id . "'");
        if (!empty($sql)) {
            $data['bill_number'] = $sql[0]->bill_number;
            $data['invoice_date'] = $sql[0]->invoice_date;
            return $data;
        } else {
            $data['bill_number'] = "";
            $data['invoice_date'] = "";
            return $data;
        }
    }
    /*Purpose To get Uom Code For Print*/
    public function productuom($id = null)
    {
        $table = \DB::table('m_products_t')->where('product_id', $id)->get();
        $data = [];
        if (count($table) > 0) {
            $data[0] = $table[0]->primary_uom_id;
        }
        return $data;
    }
    /*Purpose To Show Only PO pending Qty in Grid*/
    public function checkgrnqty($id)
    {
        $po_data = \DB::SELECT("SELECT sum(qty) as po_qty,product_id FROM `p_po_lines_t` where po_hdr_id=$id group by product_id ORDER BY `p_po_lines_t`.`product_id` ASC");
        $grn_data = \DB::SELECT("SELECT sum(p_grn_lines_t.receive_qty)as grn_qty,p_grn_lines_t.product_id FROM `p_grn_lines_t` left join p_grn_hdr_t on p_grn_hdr_t.grn_id=p_grn_lines_t.grn_id where p_grn_hdr_t.po_number=$id group by p_grn_lines_t.product_id ORDER BY `p_grn_lines_t`.`product_id` ASC");
        $close = 0;
        $podata = array();
        if (count($grn_data) > 0) {
            foreach ($grn_data as $key => $value) {
                $podata[$value->product_id] = $value->grn_qty;
            }
        }
        if ((count($po_data) > 0) && (count($grn_data) > 0)) {
            foreach ($po_data as $key => $value) {
                $p_qty = $podata[$value->product_id];
                if ($p_qty < $value->po_qty) {
                    $close++;
                }
            }
        } else {
            $close = 1;
        }
        return $close;
    }
    /*End*/
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
