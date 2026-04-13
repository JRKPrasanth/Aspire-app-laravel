<?php

namespace App\Http\Controllers;
use App\purchaseinvoice;
use App\Purchaseinvoicelines;
use App\Paymentterms;
use App\Freightterms;
use App\Deliveryterms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use DateTime;
use Yajra\DataTables\DataTables;

class PurchaseinvoiceController extends Controller
{
    public function __construct()
    {
        $this->data = array();
        $this->model = new purchaseinvoice;
        $this->submodel = new Purchaseinvoicelines;

        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageModule'] = 'purchaseinvoice';
        $this->table = "p_po_invoice_hdr_t";
        $this->subtable = "p_po_invoice_lines_t";
        $this->middleware('auth');
        $this->data['pageMethod'] == "poinvoiceapproval";
        $this->data = array(
            'pageModule' => 'purchaseinvoice',
            'pageUrl' => url('purchaseinvoice'),
            'pageMethod' => $this->data['pageMethod']

        );
        $this->data['urlmenu'] = $this->indexs();
        if ($this->data['pageMethod'] == "poinvoiceapproval") {
            $this->data['status'] = "INITIATED";
        } else {
            $this->data['status'] = "";
        }
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
        $this->data['pageMethod'] = \Request::route()->getName();
        return view("purchaseinvoice.table", $this->data);
    }
    /* Purpose For :Index Function to Call Labour Table Blade*/
    public function labourindex()
    {
        $this->data['suppliername'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        $this->data['pageMethod'] = \Request::route()->getName();
        return view("purchaseinvoice.table", $this->data);
    }

    /*  purpose for Display Labour PO Data in JQgrid function */
    public function getPolabourData()
    {

        $wh = '';

        $loc = "1";
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');
        if ($groupname == '1' || $groupname == 'Admin') {
            $wh .= 'and  v1.company_id=' . $compy;
        } else {
            $wh .= 'and  v1.company_id=' . $compy . ' and v1.location_id=' . $loc;
        }

        $SQL = "select * from (SELECT p_grn_hdr_t.grn_id AS grn_id,
                    p_grn_hdr_t.grn_number AS grn_number,
                    p_grn_hdr_t.grn_status AS grn_status,
                    p_grn_hdr_t.dc_number AS dc_number,
                    p_grn_hdr_t.source,
                    p_grn_hdr_t.dc_date AS dc_date,
                    p_po_hdr_t.po_hdr_id AS po_hdr_id,
                    p_po_hdr_t.po_number AS po_number,
                    p_po_hdr_t.po_date AS po_date,
                    m_supplier_t.supplier_name,
                    m_subcontract_supplier_t.subcontract_name,
                    p_po_invoice_hdr_t.po_invoice_status,
                    p_grn_hdr_t.company_id,
                    p_grn_hdr_t.location_id
                    FROM p_grn_hdr_t LEFT JOIN p_po_hdr_t ON(p_po_hdr_t.po_hdr_id = p_grn_hdr_t.`po_number`)
                    LEFT JOIN m_supplier_t ON(m_supplier_t.supplier_id = p_grn_hdr_t.`supplier_id`)
                    LEFT JOIN m_subcontract_supplier_t ON(m_subcontract_supplier_t.subcontract_supplier_id = p_grn_hdr_t.`subcontract_supplier_id`)
                    LEFT JOIN p_po_invoice_hdr_t ON(p_po_invoice_hdr_t.grn_number = p_grn_hdr_t.`grn_id`)
                    WHERE 1 = 1 and p_grn_hdr_t.source='LABOURPO' and p_grn_hdr_t.grn_status='INITIATED' and ( p_grn_hdr_t.invoice_created!='Yes' OR p_grn_hdr_t.invoice_created IS NULL))v1 where 1=1 $wh GROUP BY v1.grn_id";


        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }

    /*  purpose for Display GRN Data in JQgrid function */
    public function getpoData()
    {

        $wh = '';

        $loc = "1";
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');
        if ($groupname == '1' || $groupname == '4') {
            $wh .= 'and  v1.company_id=' . $compy;
        } else {
            $wh .= 'and  v1.company_id=' . $compy . ' and v1.location_id=' . $loc;
        }

        $SQL = "select * from (SELECT
                    p_grn_hdr_t.grn_id,
                    p_grn_hdr_t.grn_number,
                    p_grn_hdr_t.grn_status,
                    p_grn_hdr_t.dc_number,
                    p_grn_hdr_t.dc_date,
                    p_grn_hdr_t.po_number,
                    p_po_hdr_t.po_date,
                    p_grn_hdr_t.source,
                    p_grn_hdr_t.supplier_type,
                    m_subcontract_supplier_t.subcontract_name,
                    m_supplier_t.supplier_name,
                    p_grn_hdr_t.company_id,
                    p_grn_hdr_t.location_id
                FROM p_grn_hdr_t
                LEFT JOIN p_po_hdr_t ON(p_po_hdr_t.po_hdr_id = p_grn_hdr_t.`po_number`)
                LEFT JOIN m_subcontract_supplier_t ON(m_subcontract_supplier_t.subcontract_supplier_id = p_grn_hdr_t.`subcontract_supplier_id`)
                LEFT JOIN m_supplier_t ON(m_supplier_t.supplier_id = p_grn_hdr_t.`supplier_id`)
                WHERE 1 = 1  and p_grn_hdr_t.source!='LABOURPO' AND p_grn_hdr_t.invoice_created != 'Yes' and p_grn_hdr_t.grn_status ='INITIATED' and p_grn_hdr_t.source !='GRN')v1 where 1=1 $wh";

        $result = \DB::select($SQL);

        $po_no = '';
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

    /*  purpose for Display Invoice Data in JQgrid function */

    public function getinvoiceData()
    {
        $wh = '';

        $app_id = \Session::get('id');
        if (isset($_GET['pagemethod'])) {
            if ($_GET['pagemethod'] == 'poinvoiceapproval') {
                $wh .= " and json_contains(v1.approver_id,'" . $app_id . "')=1 ";

            }
        }
        if ($_GET['status'] != '') {
            $wh .= " and  v1.po_invoice_status='" . $_GET['status'] . "'";
            $op = "=";
            $val_status = "'" . $_GET['status'] . "'";
            $wh .= $grid_data = $this->grid_statuscheck('v1', 'po_date', 'po_invoice_status', $op, $val_status);
        } else {
            $wh .= $grid_data = $this->grid_check('v1', 'invoice_date');
        }
        $loc = "1";
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');

        $SQL = "select * from (SELECT
                        p_po_invoice_hdr_t.po_invoice_id,
                        p_po_invoice_hdr_t.bill_number,
                        p_po_invoice_hdr_t.invoice_date,
                        p_po_invoice_hdr_t.remarks,
                        p_po_invoice_hdr_t.po_invoice_status,
                        p_po_invoice_hdr_t.payment_status,
                        FORMAT(p_po_invoice_hdr_t.invoice_grand_total,2) as invoice_grand_total,
                        p_po_invoice_hdr_t.approver_id,
                        p_po_hdr_t.po_hdr_id,
                        p_po_invoice_hdr_t.po_date,
                        p_po_invoice_hdr_t.po_number,
                        m_supplier_t.supplier_name,
                        m_subcontract_supplier_t.subcontract_name,
                        p_grn_hdr_t.grn_number,
                         p_grn_hdr_t.grn_date,
                        p_po_invoice_hdr_t.company_id,
                        hr_employee_t.first_name,
                        CASE WHEN hr_employee_t.first_name = a.first_name  AND p_po_invoice_hdr_t.po_invoice_status != 'APPROVED' THEN
                        'Yet to Approve'
                        WHEN p_po_invoice_hdr_t.po_invoice_status != 'APPROVED' and p_po_invoice_hdr_t.po_invoice_status != 'CANCELLED' THEN
                        'Yet to Approve'
               			ELSE
               			a.first_name
               			END as approvedby,
                        p_po_invoice_hdr_t.location_id,
                        p_po_invoice_hdr_t.credit_taken,
                      CONCAT(LEFT(MONTHNAME(p_po_invoice_hdr_t.credit_date),3),'-',YEAR(p_po_invoice_hdr_t.credit_date))as credit_date
                        FROM  p_po_invoice_hdr_t
                        left join p_grn_hdr_t on(
                        p_grn_hdr_t.grn_id=p_po_invoice_hdr_t.`grn_number`)
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_po_invoice_hdr_t.`supplier_id`)
                        left join m_subcontract_supplier_t on(
                        m_subcontract_supplier_t.subcontract_supplier_id=p_po_invoice_hdr_t.`subcontract_supplier_id`)
                        left join p_po_hdr_t on(
                        p_po_hdr_t.po_hdr_id=p_po_invoice_hdr_t.`po_number`) 
                        LEFT JOIN hr_employee_t ON(hr_employee_t.employee_id = p_po_invoice_hdr_t.created_by)
                        LEFT JOIN hr_employee_t AS a ON(a.employee_id = p_po_invoice_hdr_t.last_updated_by) where 1=1 )v1 where 1=1 $wh";

        $result = \DB::select($SQL);

        $po_no = '';
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

    public function purchaseinvoicecreatefrompo()
    {
        $this->data['suppliername'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        $this->data['pageMethod'] = 'purchaseinvoicetable';

        $SQL = "SELECT
                    p_grn_hdr_t.grn_id,
                    p_grn_hdr_t.grn_number,
                    p_grn_hdr_t.grn_status,
                    p_grn_hdr_t.dc_number,
                    p_grn_hdr_t.dc_date,
                    p_po_hdr_t.po_number,
                    p_po_hdr_t.po_date,
                    p_grn_hdr_t.source,
                    p_grn_hdr_t.supplier_type,
                    m_subcontract_supplier_t.subcontract_name,
                    m_supplier_t.supplier_name
                FROM
                    `p_grn_hdr_t`
                LEFT JOIN p_po_hdr_t ON
                    (
                        p_po_hdr_t.po_hdr_id = p_grn_hdr_t.`po_number`
                    )
                    LEFT JOIN m_subcontract_supplier_t ON
                    (
                        m_subcontract_supplier_t.subcontract_supplier_id = p_grn_hdr_t.`subcontract_supplier_id`
                    )
                LEFT JOIN m_supplier_t ON
                    (
                        m_supplier_t.supplier_id = p_grn_hdr_t.`supplier_id`
                    ) WHERE
                    1 = 1  AND p_grn_hdr_t.invoice_created != 'Yes' and p_grn_hdr_t.grn_status ='INITIATED'";
        
        $result = \DB::select($SQL);
        $this->data['result'] = json_encode($result);
        return view('purchaseinvoice.po_table', $this->data);
    }
    public function polabourtable()
    {
        $this->data['suppliername'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        $this->data['pageMethod'] = 'polabourtable';
        return view('purchaseinvoice.polabour', $this->data);
    }


    public function createpoinvoice($id = null)
    {

        $this->data['tax_group_id_pop'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'purchaseinvoice')->get();
        $this->data['row'] = $row = \DB::table('p_grn_hdr_t')->select(
            'p_grn_hdr_t.*',
            'p_grn_hdr_t.dc_number',
            'p_grn_hdr_t.dc_date',
            'p_grn_hdr_t.source as grn_source',
            'p_grn_hdr_t.grn_number',
            'p_grn_hdr_t.grn_id',
            'p_po_hdr_t.po_hdr_id as po_hdr_id',
            'p_po_hdr_t.*',
            'p_grn_hdr_t.po_number',
            'p_po_hdr_t.payment_term_id',
            'p_po_hdr_t.freight_terms_id',
            'p_po_hdr_t.suppliersite_id',
            'p_po_hdr_t.project_id',
            'p_po_hdr_t.po_pricelist_id',
            'p_po_hdr_t.po_date',
            'm_supplier_t.supplier_id',
            'm_supplier_t.supplier_name',
            'm_supplier_sites_t.supplier_site_id',
            'm_supplier_sites_t.supplier_site_name',
            'm_subcontract_supplier_t.subcontract_name',
            'm_subcontract_sites_t.subcontract_site_id'
        )
            ->leftJoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'p_grn_hdr_t.po_number')
            ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_grn_hdr_t.supplier_id')
            ->leftJoin('m_supplier_sites_t', 'm_supplier_sites_t.supplier_id', '=', 'p_grn_hdr_t.supplier_id')
            ->leftJoin('m_subcontract_supplier_t', 'm_subcontract_supplier_t.subcontract_supplier_id', '=', 'p_grn_hdr_t.subcontract_supplier_id')
            ->leftJoin('m_subcontract_sites_t', 'm_subcontract_sites_t.subcontract_supplier_id', '=', 'm_subcontract_supplier_t.subcontract_supplier_id')
            ->where('grn_id', $id)->get();
        // dd($row);
        if ($row[0]->supplier_type == "SUPPLIER") {
            $this->data['supplier_id'] = $supplier_id = $this->data['row'][0]->supplier_id;
            $this->data['suppliersite_id']=$suppliersite_id=$this->data['row'][0]->suppliersite_id;
            $suppname = \DB::table('m_supplier_t')->where('supplier_id', $supplier_id)->get();
            $this->data['suppdata'] = $suppname;
            $suppsitename = \DB::table('m_supplier_sites_t')->where('supplier_id', $supplier_id)->where('primary_address', 'Yes')->get();
            $this->data['suppsitedata'] = $suppsitename;
            $suppsitegst=\DB::table('m_supplier_sites_t')->where('supplier_id',$supplier_id)->where('supplier_site_id',$suppliersite_id)->get();
            $this->data['suppsitedatagst']=$suppsitegst; 
            $po_hdr_id = $this->data['row'][0]->po_number;//To get Po Header Id

            /*Karthigaa Purcpose for Show Multiple Po Number*/
            $po_numb = \DB::select("select po_number from p_po_hdr_t where po_hdr_id in ($po_hdr_id)");
            $po = "";
            foreach ($po_numb as $pk => $pv) {
                $po .= $pv->po_number . ",";
            }
            $po_number = rtrim($po, ",");
            $this->data['row'][0]->ponumber = $po_number;
            /*End Purcpose for Show Multiple Po Number*/
            $this->data['suppliersite_id_id'] = $this->data['row'][0]->suppliersite_id;
            $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $row[0]->payment_term_id);
            $this->data['freight_terms_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', $row[0]->freight_terms_id, ' and source_type_id="Purchase"');
            $this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $row[0]->freight_carrier_id, ' and source_type_id="Purchase"');
            $this->data['delivery_terms_id'] = $this->jCombo('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $row[0]->delivery_terms_id);
            $this->data['payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $row[0]->default_payment_method_id);
            $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
            $this->data['tcs_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');

            $this->data['suppliersite_id'] = $this->jCombo('m_supplier_sites_t', 'supplier_site_id', 'supplier_site_name', $row[0]->suppliersite_id);
            $source = $row[0]->grn_source;
            if ($source == "PO") {
                $currency = $row[0]->currency;
            } else {
                $currency = '';
            }
            $this->data['invoice_currency_id'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $currency);
            $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $row[0]->project_id);
            $this->data['invoice_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $row[0]->po_pricelist_id, ' and price_list_type="Purchase"');

            $grn_header_id = $row[0]->grn_id;
            $tablelines = \DB::table('p_grn_lines_t')->where('grn_id', $grn_header_id)->get();

            $dateformat = ($this->data['row'][0]->po_date);
            $this->data['linedata'] = $tablelines;
            $this->data['po_invoice_id'] = "";
            $this->data['row']->invoice_number = $row[0]->dc_number;
            $this->data['invoice_type'] = "STANDARD";
            $this->data['row']->invoice_date = date(\Session::get('p_date_format'));
            $sessdate = \Session::get('p_date_format');
            $this->data['row']->po_date = date($sessdate, strtotime($dateformat));
            $this->data['row']->supplier_invoice_date = date(\Session::get('p_date_format'));

            $dcdate = $row[0]->dc_date;
            $this->data['row']->dc_date = date($sessdate, strtotime($dcdate));

            $payment_days = \DB::table('m_payment_terms_t')->where('payment_term_id', $row[0]->payment_term_id)->select('payment_days')->get();

            if (count($payment_days) > 0) {
                $payment_days1 = $payment_days[0]->payment_days;
            } else {
                $payment_days1 = 0;
            }

            $this->data['row']->due_date = date('d-m-Y', strtotime($row[0]->dc_date . '' . $payment_days1 . 'days'));

            $this->data['row']->po_invoice_status = "";
            $this->data['row']->tds_applicable = "";
            $this->data['row']->supplier_invoice_no = $row[0]->supplier_reference_no;
            $this->data['row']->dc_number = $row[0]->dc_number;
            $this->data['row']->transport_charges = $row[0]->transport_charges;
            $this->data['row']->unloading_charges = $row[0]->unloading_charges;
            $this->data['row']->insurance_charges = $row[0]->insurance_charges;
            $this->data['row']->packing_charges = $row[0]->packing_charges;
            $this->data['row']->packing_charges_tax = $row[0]->packing_charges_tax;
            $this->data['row']->insurance_charges_tax = $row[0]->insurance_charges_tax;
            $this->data['row']->transport_charges_tax = $row[0]->transport_charges_tax;
            $this->data['row']->unloading_charges_tax = $row[0]->unloading_charges_tax;
            $this->data['row']->other_tax_amount = $row[0]->other_tax_amount;
            $this->data['row']->other_tax_amount_tax = $row[0]->other_tax_amount_tax;
            $this->data['row']->other_freight_amount = $row[0]->other_frieght_amount;
            $this->data['row']->other_frieght_amount_tax = $row[0]->other_frieght_amount_tax;
            $this->data['row']->remarks = "";
            $this->data['row']->need_to_close = "";
            $this->data['row']->reverse_charge = "";
            $this->data['row']->attachfile_name = "";
            $allcharges = $this->data['row']->other_tax_amount + $this->data['row']->other_freight_amount + $this->data['row']->packing_charges + $this->data['row']->insurance_charges + $this->data['row']->unloading_charges + $this->data['row']->transport_charges;

            if ($source == "GRN") {

                $grand_total = 0;
                $tax_total = 0;

                if (count($this->data['linedata']) >= 1) {
                    foreach ($this->data['linedata'] as $key => $value) {
                        $product = $value->product_id;
                        $hsn = \DB::select("select hsn_code,product_id,defalut_hsn_code from m_products_t where product_id=" . $value->product_id);
                        $tax = $this->taxdetails($hsn[0]->hsn_code, $row[0]->supplier_site_id, "PURCHASE");
                        $this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                        $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id, 'and uom_code_id=' . $value->uom_code_id);
                        $this->data['linedata'][$key]->qty = $receive_qty = $value->receive_qty;
                        $this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $hsn[0]->defalut_hsn_code, ' and gst_code_hdr_id in(' . $hsn[0]->hsn_code . ')');
                        $this->data['linedata'][$key]->unit_price = '';
                        $this->data['linedata'][$key]->discount_percentage = '';
                        $this->data['linedata'][$key]->discount_amount = '';
                        $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $tax['tax_group_id']);
                        $this->data['linedata'][$key]->tax_amount = '';
                        $this->data['linedata'][$key]->line_total = '';
                        $this->data['linedata'][$key]->promised_date = "";
                        $this->data['linedata'][$key]->comments = '';
                        $this->data['linedata'][$key]->po_invoice_lines_id = "";
                    }
                }
                $this->data['row']->invoice_grand_total = '';
                $this->data['row']->invoice_tax_total = '';
                $this->data['row']->transport_charges_value = 0;
            } else {
                $grand_total = 0;
                $tax_total = 0;

                if (count($this->data['linedata']) >= 1) {
                    foreach ($this->data['linedata'] as $key => $value) {

                        $product = $value->product_id;
                        $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                        $polinedata = \DB::table('p_po_lines_t')->whereIn('po_hdr_id', explode(',', $po_hdr_id))->where('product_id', $product)->get();

                        $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id, 'and uom_code_id=' . $value->uom_code_id);
                        $hsn = \DB::select("select hsn_code,product_id,defalut_hsn_code from m_products_t where product_id=" . $product);
                        //dd($hsn);
                        $this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $polinedata[0]->hsn_code, ' and gst_code_hdr_id in(' . $hsn[0]->hsn_code . ')');

                        $this->data['linedata'][$key]->qty = $receive_qty = $value->receive_qty;
                        if (count($polinedata) > 0) {
                            $unitprice = $polinedata[0]->unit_price;
                            $discount_percentage = $polinedata[0]->discount_percentage;
                            $total = $unitprice * $receive_qty;

                            $dis_amt = ($total * $discount_percentage) / 100;
                            //                       dd($dis_amt);
                            $dis_minus = $total - $dis_amt;

                            $discount_amount = $dis_amt;
                            $tax_group_id = $polinedata[0]->tax_group_id;
                            $tax1 = \DB::table("m_tax_group_t")->select('display_name')->where('tax_group_id', $tax_group_id)->get();

                            $display = $tax1[0]->display_name;

                            $tax_amount = (($dis_minus) * $display) / 100;

                            //                $tax_amount=$polinedata[0]->tax_amount;
                            $overall_total = $dis_minus + $tax_amount;
                            $grand_total = $grand_total + $overall_total;
                            $tax_total = $tax_total + $tax_amount;
                            $line_total = $overall_total;
                            $comments = $polinedata[0]->comments;

                        } else {
                            $unitprice = 0;
                            $discount_percentage = 0;
                            $discount_amount = 0;
                            $tax_group_id = 0;
                            $tax_amount = 0;
                            $line_total = 0;
                            $comments = 0;
                        }

                        $this->data['linedata'][$key]->unit_price = $unitprice;
                        $this->data['linedata'][$key]->discount_percentage = $discount_percentage;
                        $this->data['linedata'][$key]->discount_amount = $discount_amount;
                        $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $tax_group_id);
                        $this->data['linedata'][$key]->tax_amount = $tax_amount;
                        $this->data['linedata'][$key]->line_total = $line_total;
                        $this->data['linedata'][$key]->promised_date = "";
                        $this->data['linedata'][$key]->comments = $comments;
                        $this->data['linedata'][$key]->po_invoice_lines_id = "";

                    }

                }

                $this->data['row']->invoice_grand_total = $grand_total + $allcharges;
                $this->data['row']->invoice_tax_total = $tax_total;
                $this->data['row']->transport_charges_value = 0;
            }
        } else {
            $this->data['subcontract_supplier_id'] = $subcontract_supplier_id = $this->data['row'][0]->subcontract_supplier_id;
            $this->data['subcontract_site_id'] = $subcontract_site_id = $this->data['row'][0]->subcontract_site_id;
            $suppconname = \DB::table('m_subcontract_supplier_t')->where('subcontract_supplier_id', $subcontract_supplier_id)->get();
            $this->data['suppcondata'] = $suppconname;
            $suppconsitename = \DB::table('m_subcontract_sites_t')->where('subcontract_supplier_id', $subcontract_supplier_id)->where('primary_address', 'Yes')->get();
            $this->data['suppconsitedata'] = $suppconsitename;
            $po_hdr_id = $this->data['row'][0]->po_hdr_id;

            $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $row[0]->payment_term_id);
            $this->data['freight_terms_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', $row[0]->freight_terms_id, ' and source_type_id="Purchase"');
            $this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $row[0]->freight_carrier_id, ' and source_type_id="Purchase"');
            $this->data['delivery_terms_id'] = $this->jCombo('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $row[0]->delivery_terms_id);
            $this->data['payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $row[0]->default_payment_method_id);
            $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
            $this->data['tcs_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');

            $source = $row[0]->grn_source;

            if ($source == "PO") {
                $currency = $row[0]->currency;
            } else {
                $currency = '';
            }
            $this->data['invoice_currency_id'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $currency);
            $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $row[0]->project_id);
            $this->data['invoice_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $row[0]->po_pricelist_id, ' and price_list_type="Purchase"');

            $grn_header_id = $row[0]->grn_id;

            $tablelines = \DB::table('p_grn_lines_t')->where('grn_id', $grn_header_id)->get();
            $dateformat = ($this->data['row'][0]->po_date);
            $this->data['linedata'] = $tablelines;
            $this->data['po_invoice_id'] = "";
            $this->data['row']->invoice_number = $row[0]->dc_number;
            $this->data['row']->invoice_date = date(\Session::get('p_date_format'));
            $sessdate = \Session::get('p_date_format');
            $this->data['row']->po_date = date($sessdate, strtotime($dateformat));
            $this->data['row']->supplier_invoice_date = date(\Session::get('p_date_format'));
            $dcdate = $row[0]->dc_date;
            $this->data['row']->dc_date = date($sessdate, strtotime($dcdate));
            //dd($row);
            $this->data['row']->due_date = date(\Session::get('p_date_format'));
            $this->data['row']->po_invoice_status = "";
            $this->data['row']->tds_applicable = "";
            $this->data['row']->supplier_invoice_no = $row[0]->supplier_reference_no;
            $this->data['row']->dc_number = $row[0]->dc_number;
            $this->data['row']->transport_charges = $row[0]->transport_charges;
            $this->data['row']->unloading_charges = $row[0]->unloading_charges;
            $this->data['row']->insurance_charges = $row[0]->insurance_charges;
            $this->data['row']->packing_charges = $row[0]->packing_charges;
            $this->data['row']->packing_charges_tax = $row[0]->packing_charges_tax;
            $this->data['row']->insurance_charges_tax = $row[0]->insurance_charges_tax;
            $this->data['row']->transport_charges_tax = $row[0]->transport_charges_tax;
            $this->data['row']->unloading_charges_tax = $row[0]->unloading_charges_tax;
            $this->data['row']->other_tax_amount = $row[0]->other_tax_amount;
            $this->data['row']->other_tax_amount_tax = $row[0]->other_tax_amount_tax;
            $this->data['row']->other_freight_amount = $row[0]->other_frieght_amount;
            $this->data['row']->other_frieght_amount_tax = $row[0]->other_frieght_amount_tax;
            $this->data['row']->remarks = "";
            $this->data['row']->need_to_close = "0";
            $this->data['row']->reverse_charge = "";
            $this->data['row']->attachfile_name = "";
            $this->data['invoice_type'] = "STANDARD";


            $grand_total = 0;
            $tax_total = 0;

            if (count($this->data['linedata']) >= 1) {
                foreach ($this->data['linedata'] as $key => $value) {
                    $product = $value->product_id;
                    $hsn = \DB::select("select hsn_code,product_id,defalut_hsn_code from m_products_t where product_id=" . $value->product_id);
                    $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                    $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id, 'and uom_code_id=' . $value->uom_code_id);
                    $this->data['linedata'][$key]->qty = $receive_qty = $value->receive_qty;
                    $this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="SAC"');
                    $this->data['linedata'][$key]->unit_price = '';
                    $this->data['linedata'][$key]->discount_percentage = '';
                    $this->data['linedata'][$key]->discount_amount = '';
                    $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
                    $this->data['linedata'][$key]->tax_amount = '';
                    $this->data['linedata'][$key]->line_total = '';
                    $this->data['linedata'][$key]->promised_date = "";
                    $this->data['linedata'][$key]->comments = '';
                    $this->data['linedata'][$key]->po_invoice_lines_id = "";
                }
            }
            $this->data['row']->invoice_grand_total = '';
            $this->data['row']->invoice_tax_total = '';
            $this->data['row']->transport_charges_value = 0;
            


        }
        $this->data['row']->source = $source;
        $this->data['row']->tds_amount = 0;
        return view('purchaseinvoice.form', $this->data);
    }
    public function duedatecal($id = null, $ssid = null)
    {
        //  dd($_GET);

        $invoice_date = new DateTime(date("d-m-Y", strtotime($_GET['invoice_date'])));
        //dd($invoice_date);
        $payment_term = $_GET['payment_term_id'];
        //dd($payment_term);

        $payment = \DB::table('m_payment_terms_t')->select('payment_days')->WHERE('payment_term_id', $payment_term)->get();

        $payterm = $payment[0]->payment_days;
        //  dd($payterm);
        $invoice_date->modify('+' . $payterm . ' day');
        $duedatecal = $invoice_date->FORMAT('d-m-Y');

        //dd($duedatecal);
        $data = $duedatecal;
        return $data;

    }

    public function createpolabourinvoice($id = null)
    {
        $this->data['tax_group_id_pop'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'purchaseinvoice')->get();
        if ($id != "") {
            $this->data['row'] = $row = \DB::table('p_grn_hdr_t')
                ->select(
                    'p_grn_hdr_t.*',
                    'p_po_hdr_t.po_hdr_id',
                    'p_po_hdr_t.*',
                    'p_grn_hdr_t.po_number',
                    'p_grn_hdr_t.dc_date',
                    'p_po_hdr_t.payment_term_id',
                    'p_po_hdr_t.freight_terms_id',
                    'p_po_hdr_t.suppliersite_id',
                    'p_po_hdr_t.project_id',
                    'p_po_hdr_t.po_pricelist_id',
                    'p_po_hdr_t.po_date',
                    'm_supplier_t.supplier_id',
                    'm_supplier_t.supplier_name'
                )
                ->leftJoin('p_grn_lines_t', 'p_grn_lines_t.grn_id', '=', 'p_grn_hdr_t.grn_id')
                ->leftJoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'p_grn_hdr_t.po_number')
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_po_hdr_t.supplier_id')
                ->where('p_grn_hdr_t.grn_id', $id)->get();
            //                dd($row);

            $this->data['supplier_id'] = $supplier_id = $this->data['row'][0]->supplier_id;
            $this->data['suppliersite_id']=$suppliersite_id=$this->data['row'][0]->suppliersite_id;
            $suppname = \DB::table('m_supplier_t')->where('supplier_id', $supplier_id)->get();
            $this->data['suppdata'] = $suppname;
            $suppsitename = \DB::table('m_supplier_sites_t')->where('supplier_id', $supplier_id)->where('primary_address', 'Yes')->get();
            $this->data['suppsitedata'] = $suppsitename;
            $suppsitegst=\DB::table('m_supplier_sites_t')->where('supplier_id',$supplier_id)->where('supplier_site_id',$suppliersite_id)->get();
            $this->data['suppsitedatagst']=$suppsitegst;
            $po_hdr_id = $this->data['row'][0]->po_hdr_id;
            $this->data['suppliersite_id_id'] = $this->data['row'][0]->suppliersite_id;

            $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $row[0]->payment_term_id);
            $this->data['freight_terms_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', $row[0]->freight_terms_id, ' and source_type_id="Purchase"');
            $this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $row[0]->freight_carrier_id, ' and source_type_id="Purchase"');
            $this->data['delivery_terms_id'] = $this->jCombo('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $row[0]->delivery_terms_id);
            $this->data['payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $row[0]->default_payment_method_id);
            $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
            $this->data['suppliersite_id'] = $this->jCombo('m_supplier_sites_t', 'supplier_site_id', 'supplier_site_name', $row[0]->suppliersite_id);
            $this->data['tcs_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');

            $this->data['invoice_currency_id'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $row[0]->currency);
            $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $row[0]->project_id);
            $this->data['invoice_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $row[0]->po_pricelist_id, ' and price_list_type="Purchase"');

            $po_hdr_id = $row[0]->po_hdr_id;
            $grn_hdr_id = $row[0]->grn_id;
            //dd($grn_hdr_id);
            /*Karthigaa Purcpose for Show Multiple Po Number*/
            $po_numb = \DB::select("select po_number from p_po_hdr_t where po_hdr_id in ($po_hdr_id)");
            $po = "";
            foreach ($po_numb as $pk => $pv) {
                $po .= $pv->po_number . ",";
            }
            $po_number = rtrim($po, ",");

            $this->data['row'][0]->ponumber = $po_number;
            /*End Purcpose for Show Multiple Po Number*/

            //             $tablelines = \DB::table('p_po_lines_t')->where('po_hdr_id',$po_hdr_id)->get();
            $tablelines = \DB::table('p_grn_lines_t')->select('p_grn_lines_t.*')->where('p_grn_lines_t.grn_id', $grn_hdr_id)->get();
            //             dd($tablelines);
            $dateformat = ($this->data['row'][0]->po_date);
            $this->data['linedata'] = $tablelines;
            $this->data['po_invoice_id'] = "";
            $this->data['row']->invoice_number = "";
            $this->data['row']->invoice_date = date(\Session::get('p_date_format'));
            $sessdate = \Session::get('p_date_format');
            $this->data['row']->po_date = date($sessdate, strtotime($dateformat));
            $this->data['row']->supplier_invoice_date = date(\Session::get('p_date_format'));
            $dcdate = $row[0]->dc_date;
            $this->data['row']->dc_date = date(\Session::get('p_date_format'), strtotime($dcdate));
            $payment_days = \DB::table('m_payment_terms_t')->where('payment_term_id', $row[0]->payment_term_id)->select('payment_days')->get();
            //dd($payment_days);
            if (count($payment_days) > 0) {
                $payment_days1 = $payment_days[0]->payment_days;
            } else {
                $payment_days1 = 0;
            }
            //$vvv=$row[0]->grn_date+4;
//dd($vvv);
            //      dd($this->data['payment_days']);$payment_days1
            $this->data['row']->due_date = date('d-m-Y', strtotime($row[0]->dc_date . '' . $payment_days1 . 'days'));
            $this->data['row']->po_invoice_status = "";
            $this->data['row']->tds_applicable = "";
            $this->data['row']->supplier_invoice_no = $row[0]->supplier_reference_no;
            $this->data['row']->dc_number = $row[0]->dc_number;
            $this->data['row']->transport_charges = $row[0]->transport_charges;
            $this->data['row']->unloading_charges = $row[0]->unloading_charges;
            $this->data['row']->insurance_charges = $row[0]->insurance_charges;
            $this->data['row']->packing_charges = $row[0]->packing_charges;
            $this->data['row']->packing_charges_tax = $row[0]->packing_charges_tax;
            $this->data['row']->insurance_charges_tax = $row[0]->insurance_charges_tax;
            $this->data['row']->transport_charges_tax = $row[0]->transport_charges_tax;
            $this->data['row']->unloading_charges_tax = $row[0]->unloading_charges_tax;
            $this->data['row']->other_tax_amount = $row[0]->other_tax_amount;
            $this->data['row']->other_tax_amount_tax = $row[0]->other_tax_amount_tax;
            $this->data['row']->other_freight_amount = $row[0]->other_frieght_amount;
            $this->data['row']->other_frieght_amount_tax = $row[0]->other_frieght_amount_tax;
            $this->data['row']->remarks = "";
            $this->data['row']->need_to_close = "";
            $this->data['row']->reverse_charge = "";
            $this->data['row']->supplier_type = "SUPPLIER";
            $this->data['invoice_type'] = "LABOUR FROM PO";
            $this->data['row']->attachfile_name = "";
            $allcharges = $this->data['row']->other_tax_amount + $this->data['row']->other_freight_amount + $this->data['row']->packing_charges + $this->data['row']->insurance_charges + $this->data['row']->unloading_charges + $this->data['row']->transport_charges;

            $grand_total = 0;
            $tax_total = 0;
            //        dd($this->data['linedata']);
            if (count($this->data['linedata']) >= 1) {
                // dd(($this->data['linedata']));

                foreach ($this->data['linedata'] as $key => $value) {
                    $product = $value->product_id;
                    $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                    $polinedata = \DB::table('p_po_lines_t')->where('po_hdr_id', $po_hdr_id)->where('product_id', $product)->get();
                    $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id, "and uom_code_id='" . $value->uom_code_id . "'");
                    $hsn = \DB::select("select hsn_code,product_id,defalut_hsn_code from m_products_t where product_id=" . $product);
                    $this->data['linedata'][$key]->hsn_code = $this->jCombo('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $polinedata[0]->hsn_code);
                    $this->data['linedata'][$key]->product_description = $value->packed_discription;
                    $this->data['linedata'][$key]->qty = $receive_qty = $value->qty;
                    if (count($polinedata) > 0) {
                        $unitprice = $polinedata[0]->unit_price;
                        $discount_percentage = $polinedata[0]->discount_percentage;
                        $total = $unitprice * $receive_qty;


                        //                       $discount_perct=$discount_percentage;
                        $dis_amt = ($total * $discount_percentage) / 100;
                        //                       dd($dis_amt);
                        $dis_minus = $total - $dis_amt;

                        $discount_amount = $dis_amt;
                        $tax_group_id = $polinedata[0]->tax_group_id;
                        $tax1 = \DB::table("m_tax_group_t")->select('display_name')->where('tax_group_id', $tax_group_id)->get();

                        $display = $tax1[0]->display_name;

                        $tax_amount = (($dis_minus) * $display) / 100;

                        //                $tax_amount=$polinedata[0]->tax_amount;
                        $overall_total = $dis_minus + $tax_amount;
                        $grand_total = $grand_total + $overall_total;
                        $tax_total = $tax_total + $tax_amount;
                        $line_total = $overall_total;
                        $comments = $polinedata[0]->comments;

                    } else {
                        $unitprice = 0;
                        $discount_percentage = 0;
                        $discount_amount = 0;
                        $tax_group_id = 0;
                        $tax_amount = 0;
                        $line_total = 0;
                        $comments = 0;
                    }

                    $this->data['linedata'][$key]->unit_price = $unitprice;
                    $this->data['linedata'][$key]->discount_percentage = $discount_percentage;
                    $this->data['linedata'][$key]->discount_amount = $discount_amount;
                    $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $tax_group_id);
                    $this->data['linedata'][$key]->tax_amount = $tax_amount;
                    $this->data['linedata'][$key]->line_total = $line_total;
                    $this->data['linedata'][$key]->promised_date = "";
                    $this->data['linedata'][$key]->comments = $comments;
                    $this->data['linedata'][$key]->po_invoice_lines_id = "";
                }

            }
            $this->data['row']->invoice_grand_total = $grand_total + $allcharges;
            $this->data['row']->invoice_tax_total = $tax_total;
            $this->data['row']->transport_charges_value = 0;
            
        } else {
            $this->data['invoice_type'] = "LABOUR";
            $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', '');
            $this->data['freight_terms_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', '', ' and source_type_id="Purchase"');
            $this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', '', ' and source_type_id="Purchase"');
            $this->data['delivery_terms_id'] = $this->jCombo('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', '');
            $this->data['payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', '');
            $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
            $this->data['tcs_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');

            $this->data['supplierid'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', '');
            $this->data['suppliersite_id'] = $this->jCombo('m_supplier_sites_t', 'supplier_site_id', 'supplier_site_name', '');
            $this->data['invoice_currency_id'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', '');
            $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', '');
            $this->data['invoice_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', '', ' and price_list_type="Purchase"');
            $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'purchaseorder');
            $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
            $this->data['tax_group_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
            $this->data['hsn_code'] = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="SAC"');

            $this->data['linedata'] = array();


        }
        $this->data['row']->tds_amount = 0;
        return view('purchaseinvoice.form', $this->data);
    }

    public function invoiceDataedit($id = null, $aprv = null)
    {

        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'purchaseinvoice')->get();
        $this->data['tax_group_id_pop'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
        $this->data['row'] = $row = \DB::table('p_po_invoice_hdr_t')->select(
            'p_po_invoice_hdr_t.*',
            'p_po_invoice_hdr_t.invoice_tax_total',
            'p_po_invoice_hdr_t.po_invoice_status',
            'p_po_invoice_hdr_t.invoice_grand_total',
            'p_po_invoice_hdr_t.po_invoice_id',
            'p_po_invoice_hdr_t.dc_number',
            'p_po_invoice_hdr_t.invoice_date',
            'p_po_invoice_hdr_t.payment_term_id',
            'p_po_invoice_hdr_t.freight_terms_id',
            'p_po_invoice_hdr_t.suppliersite_id',
            'p_po_hdr_t.suppliersite_id as suppliersiteid',
            'p_po_invoice_hdr_t.invoice_currency_id',
            'p_po_invoice_hdr_t.project_id',
            'p_po_invoice_hdr_t.invoice_pricelist_id',
            'p_po_invoice_hdr_t.po_number',
            'p_po_invoice_hdr_t.po_date',
            'm_supplier_t.supplier_id',
            'm_supplier_t.supplier_name',
            'p_qc_header_t.qc_header_id',
            'p_qc_header_t.qc_number',
            'p_grn_hdr_t.grn_number',
            'p_po_invoice_hdr_t.dc_number',
            'p_grn_hdr_t.grn_id',
            'p_grn_hdr_t.supplier_type'
        )
            ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_po_invoice_hdr_t.supplier_id')
            ->leftJoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'p_po_invoice_hdr_t.po_number')
            ->leftJoin('p_grn_hdr_t', 'p_grn_hdr_t.grn_id', '=', 'p_po_invoice_hdr_t.grn_number')
            ->leftJoin('p_qc_header_t', 'p_qc_header_t.qc_header_id', '=', 'p_po_invoice_hdr_t.qc_id')
            ->where('po_invoice_id', $id)->get();
        $po_hdr_id = $row[0]->po_number;//get po id
        /*Karthigaa Purcpose for Show Multiple Po Number*/
        $po_numb = \DB::select("select po_number from p_po_hdr_t where po_hdr_id in ($po_hdr_id)");
        $po = "";
        foreach ($po_numb as $pk => $pv) {
            $po .= $pv->po_number . ",";
        }
        $po_number = rtrim($po, ",");

        $this->data['row'][0]->ponumber = $po_number;
        /*End Purcpose for Show Multiple Po Number*/
        $supplier_id = $this->data['row'][0]->supplier_id;
        $suppliersite_id=$this->data['row'][0]->suppliersiteid;
        $dateformat = ($this->data['row'][0]->po_date);
        $invoicedate = ($this->data['row'][0]->invoice_date);
        $sessdate = \Session::get('p_date_format');
        $this->data['row']->po_date = date($sessdate, strtotime($dateformat));
        $this->data['row']->invoice_date = date($sessdate, strtotime($invoicedate));
        //         $tds=$this->data['row'][0]->tds_applicable;
        $suppname = \DB::table('m_supplier_t')->where('supplier_id', $supplier_id)->get();
        $this->data['suppdata'] = $suppname;
        $this->data['supplier_id'] = $supplier_id;

        $suppsitename = \DB::table('m_supplier_sites_t')->where('supplier_id', $supplier_id)->where('primary_address', "Yes")->get();
        $this->data['suppsitedata'] = $suppsitename;
        $suppsitegst=\DB::table('m_supplier_sites_t')->where('supplier_id',$supplier_id)->where('supplier_site_id',$suppliersite_id)->get();
        $this->data['suppsitedatagst']=$suppsitegst; 

        //        $this->data['row'][0]->tds_applicable="";
        $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $row[0]->payment_term_id);
        $this->data['freight_terms_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', $row[0]->freight_terms_id, ' and source_type_id="Purchase"');
        $this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $row[0]->freight_carrier_id, ' and source_type_id="Purchase"');
        $this->data['delivery_terms_id'] = $this->jCombo('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $row[0]->delivery_terms_id);
        $this->data['payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $row[0]->payment_method_id);
        $this->data['tcs_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $row[0]->tcs_account_id);

        $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $row[0]->tds_account_id);
        $this->data['suppliersite_id'] = $this->jCombo('m_supplier_sites_t', 'supplier_site_id', 'supplier_site_name', $row[0]->suppliersiteid);
        $this->data['invoice_currency_id'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $row[0]->invoice_currency_id);
        $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $row[0]->project_id);
        $this->data['invoice_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $row[0]->invoice_pricelist_id, ' and price_list_type="Purchase"');


        $tablelines = \DB::table('p_po_invoice_lines_t')->where('po_invoice_id', $id)->get();

        $this->data['linedata'] = $tablelines;
        $this->data['po_invoice_id'] = $row[0]->po_invoice_id;
        $this->data['row']->invoice_date = $row[0]->invoice_date;
        $this->data['invoice_type'] = $row[0]->invoice_type;
        $this->data['row'][0]->invoice_number = $row[0]->dc_number;
        $this->data['row'][0]->po_invoice_status = $row[0]->po_invoice_status;
        $this->data['row']->invoice_tax_total = $row[0]->invoice_tax_total;
        $this->data['row']->invoice_grand_total = $row[0]->invoice_grand_total;
        $this->data['row']->tds_amount = 0;
        $this->data['row']->balance_amount = $row[0]->balance_amount;
        $this->data['row']->po_invoice_status = $row[0]->po_invoice_status;
        $this->data['row']->tds_applicable = $row[0]->tds_applicable;
        $this->data['row']->transport_charges = $row[0]->transport_charges;
        $this->data['row']->unloading_charges = $row[0]->unloading_charges;
        $this->data['row']->insurance_charges = $row[0]->insurance_charges;
        $this->data['row']->packing_charges = $row[0]->packing_charges;
        $this->data['row']->supplier_invoice_no = $row[0]->supplier_invoice_no;
        $this->data['row']->dc_number = $row[0]->dc_number;
        $this->data['row']->dc_date = $row[0]->dc_date;
        $this->data['row']->due_date = $row[0]->due_date;

        $this->data['row']->remarks = $row[0]->remarks;

        $this->data['row']->packing_charges_tax = $row[0]->packing_charges_tax;
        $this->data['row']->insurance_charges_tax = $row[0]->insurance_charges_tax;
        $this->data['row']->transport_charges_tax = $row[0]->transport_charges_tax;
        $this->data['row']->unloading_charges_tax = $row[0]->unloading_charges_tax;
        $this->data['row']->other_tax_amount = $row[0]->other_tax_amount;
        $this->data['row']->other_tax_amount_tax = $row[0]->other_tax_amount_tax;
        $this->data['row']->other_freight_amount = $row[0]->other_freight_amount;
        $this->data['row']->other_frieght_amount_tax = $row[0]->other_frieght_amount_tax;
        $this->data['row']->supplier_invoice_date = $row[0]->supplier_invoice_date;
        $this->data['row']->need_to_close = $row[0]->need_to_close;
        $this->data['row']->reverse_charge = $row[0]->reverse_charge;
        $this->data['row']->attachfile_name = $row[0]->attachfile_name;
        $this->data['row']->transport_charges_value = 0;
        if ($aprv != "") {
            $this->data['aprvidenty'] = $aprv;
        } else {
            $this->data['aprvidenty'] = "";
        }


        //           $this->data['po_invoice_status'] = $row[0]->po_invoice_status;
        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                /* old approval data */
                $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id, 'and uom_code_id=' . $value->uom_code_id);
                $this->data['linedata'][$key]->hsn_code = $this->jCombo('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->hsn_code);
                $this->data['linedata'][$key]->unit_price = $value->unit_price;
                //dd($this->data['linedata'][$key]);
                $this->data['linedata'][$key]->discount_percentage = $value->discount_percentage;
                $this->data['linedata'][$key]->discount_amount = $value->discount_amount;
                $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
                $this->data['linedata'][$key]->accept_qty = $value->accept_qty;

                $this->data['linedata'][$key]->tax_amount = $value->tax_amount;
                $this->data['linedata'][$key]->line_total = $value->line_total;
                $this->data['linedata'][$key]->promised_date = $value->promised_date;
                $this->data['linedata'][$key]->comments = $value->comments;
                $this->data['linedata'][$key]->po_invoice_lines_id = $value->po_invoice_lines_id;
                /* old approval data */


            }
        }

        return view('purchaseinvoice.form', $this->data);
    }


    public function poinvoiceformsave(Request $request)
    {
     

        $id = '';
        $form = $request->all();
        $dataupload = [];
        $form = $request->except([
            '_token',
            'form_config',
            'form_data_json',
            'savestatus',
            'submit_type',
            'choosefile',
            'existing_file',
            'invoice_number',
        ]);
        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        
        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');
        $lines_data = [];

        // total rows
        $rowCount = count($request->bulk_line_no ?? []);

        for ($i = 0; $i < $rowCount; $i++) {

            // skip empty rows
            if (empty($request->bulk_line_no[$i]) || empty($request->bulk_product_id[$i])) {
                continue;
            }

            $lines_data['po_invoice_lines_id'][] = $request->bulk_po_invoice_lines_id[$i] ?? null;
            $lines_data['line_no'][]             = $request->bulk_line_no[$i];
            $lines_data['product_id'][]          = $request->bulk_product_id[$i];
            $lines_data['product_description'][] = $request->bulk_product_description[$i] ?? null;
            $lines_data['uom_code_id'][]         = $request->bulk_uom_code_id[$i];
            $lines_data['qty'][]                 = $request->bulk_qty[$i];
            $lines_data['unit_price'][]          = $request->bulk_unit_price[$i];
            $lines_data['discount_percentage'][] = $request->bulk_discount_percentage[$i];
            $lines_data['discount_amount'][]     = $request->bulk_discount_amount[$i];
            $lines_data['hsn_code'][]            = $request->bulk_hsn_code[$i] ?? null;
            $lines_data['tax_group_id'][]        = $request->bulk_tax_group_id[$i];
            $lines_data['tax_amount'][]          = $request->bulk_tax_amount[$i];
            $lines_data['line_total'][]          = $request->bulk_line_total[$i];
            $lines_data['comments'][]            = $request->bulk_comments[$i] ?? null;

            // audit fields (REQUIRED)
            $lines_data['company_id'][]          = session('companyid');
            $lines_data['organization_id'][]     = session('organizationid');
            $lines_data['location_id'][]         = session('location_id');
            $lines_data['created_by'][]          = auth()->id();
            $lines_data['created_at'][]          = now();
            $lines_data['last_updated_by'][]     = auth()->id();
            $lines_data['updated_at'][]          = now();
        }

        
        $data['invoice_date'] = date('Y-m-d', strtotime($_POST['invoice_date']));
        $data['supplier_invoice_date'] = date('Y-m-d', strtotime($_POST['supplier_invoice_date']));
        $data['due_date'] = date('Y-m-d', strtotime($_POST['due_date']));
        if ($data['invoice_grand_total'] != "") {
            $grandtotal =$request->invoice_grand_total;
            DB::update(
                "update p_po_invoice_hdr_t set invoice_grand_total=? where po_invoice_id=?",
                [$grandtotal, $id]
            );
            $approverid = $this->Approvaldatacheck('poinvoice', $data['invoice_grand_total']);
            if ($approverid == "0") {
                $data['approver_id'] = \Session::get('id');
                if ($_POST['po_invoice_status'] != "DRAFT") {
                    $data['po_invoice_status'] = "APPROVED";
                }
            } else {
                $data['approver_id'] = $approverid;
            }
        }

        $data['balance_amount'] = $_POST['invoice_grand_total'];
        $data['po_number'] = implode(",", $_POST['po_number']);

        unset($lines_data['po_number']);

        $id = $this->model->insertRow($data);
        /*File Attachments*/
        $invoice_id = $id;
        $po_invoice_id = $request->input('po_invoice_id');


        $choose_file = $request->file('choosefile');

        // ✅ Normalize file input to array
        if ($choose_file instanceof \Illuminate\Http\UploadedFile) {
            $choose_file = [$choose_file];
        }

        if ($po_invoice_id == '') {

            if (!empty($choose_file)) {
                foreach ($choose_file as $file) {
                    $name = $file->getClientOriginalName();
                    $file->move(public_path('uploads/purchaseinvoice/PO' . $invoice_id), $name);
                    $dataupload[] = $name;
                }
            }

            $attachfile_name = json_encode($dataupload);
            DB::update(
                "update p_po_invoice_hdr_t set attachfile_name=? where po_invoice_id=?",
                [$attachfile_name, $invoice_id]
            );

            $this->data['notymsg'] = "yes";

        } else {

            $existing_file = $request->input('existing_file', '');
            $existing_file = array_filter(explode(',', $existing_file));

            // get existing attachments
            $get_attach = DB::table('p_po_invoice_hdr_t')
                ->where('po_invoice_id', $invoice_id)
                ->first();

            $attach_file = json_decode($get_attach->attachfile_name ?? '[]', true); // ✅ array
            $attach_file = is_array($attach_file) ? $attach_file : [];

            // ❌ No new file, only existing
            if (empty($choose_file) && count($existing_file) > 0) {

                $deleted = array_diff($attach_file, $existing_file);

                foreach ($deleted as $file) {
                    $path = public_path('uploads/purchaseinvoice/PO' . $invoice_id . '/' . $file);
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }

                DB::update(
                    "update p_po_invoice_hdr_t set attachfile_name=? where po_invoice_id=?",
                    [json_encode($existing_file), $invoice_id]
                );
            } elseif (!empty($choose_file)) {

                foreach ($choose_file as $file) {
                    $name = $file->getClientOriginalName();
                    $file->move(public_path('uploads/purchaseinvoice/PO' . $invoice_id), $name);
                    $dataupload[] = $name;
                }

                $final_files = array_unique(array_merge($existing_file, $dataupload));

                DB::update(
                    "update p_po_invoice_hdr_t set attachfile_name=? where po_invoice_id=?",
                    [json_encode($final_files), $invoice_id]
                );
            }
        }


        /*End File Attachments*/
        unset($lines_data['reverse_charge']);
        /**Reverse Charge**/
        if (isset($_POST['reverse_charge']) && $_POST['reverse_charge'] != null && $_POST['reverse_charge'] != '') {
            $reverse_charge = $_POST['reverse_charge'];
            $charge = implode(",", $reverse_charge);
            \DB::update("update  p_po_invoice_hdr_t set reverse_charge='" . $charge . "' where po_invoice_id='" . $id . "'");
        }
        /** End Reverse Charge**/
        $lid = $this->submodel->subgridSave($lines_data, $id);
        /*Purpose for Pricelist Update*/
        $pricelist = $_POST['invoice_pricelist_id'];
        $invoiceid = DB::table('p_po_invoice_lines_t')->where('po_invoice_id', $id)->get();
        //dd($invoiceid);
        foreach ($invoiceid as $pricekey => $pricevalue) {
            $price['pricelist_hdr_id'] = $pricelist;
            $product_id = $price['product_id'] = $pricevalue->product_id;
            $price['unit_price'] = $pricevalue->unit_price;
            $price['std_price'] = $pricevalue->unit_price;
            $price['start_date'] = date("Y-m-d", time() + 86400);
            $price['end_date'] = date('Y-m-d', strtotime("+30 days"));
            $price['active'] = "Yes";
            $pricelines = \DB::select("select product_id,unit_price from i_pricelist_lines_t where pricelist_hdr_id='$pricelist' and product_id='$product_id'");

            if (!empty($pricelines)) {
                if ($pricevalue->unit_price != $pricelines[0]->unit_price) {
                    \DB::select("update i_pricelist_lines_t set active='No' where pricelist_hdr_id='$pricelist' and product_id='$product_id'");
                    \DB::table('i_pricelist_lines_t')->insert($price);
                }
            } else {
                \DB::table('i_pricelist_lines_t')->insert($price);
            }
        }

        if ($_POST['po_invoice_status'] == "INITIATED") {
            $noti_message = "Purchase Invoice " . $_POST['bill_number'] . " Initiated";
            //  $send_notification = $this->sendPopUpNotification($id,"INVOICE APPROVAL",$noti_message,"poinvoiceapproval");
        } else if ($_POST['po_invoice_status'] == "APPROVED") {
            $da = \DB::select("select * from p_po_invoice_hdr_t where po_invoice_id='" . $id . "'");
            \DB::table('notifications_t')->where('reference_source_id', $id)->where('reference_source', 'INVOICE APPROVAL')->update(['read/unread' => 'read']);
            $notifcation = 'Purchase Invoice ' . $_POST['bill_number'] . ' Approved';
            //    $send_notification = $this->sendPopUpHomeNoty($id,"INVOICE APPROVAL",$notifcation,'purchaseinvoice',$da[0]->created_by);
        }

        if ($_POST['po_invoice_status'] == "APPROVED" || $data['po_invoice_status'] == "APPROVED") {
            $inv_no = "POINVOICE-" . $_POST['bill_number'];
            $invdate = $_POST['invoice_date'];
            $org = \Session::get('organization');
            $loc = "1";
            $compy = \Session::get('companyid');
            $grn_no = "GRN REVERSE";
            $grndate = date('Y-m-d');
            if (isset($id)) {
                //Journal Header Insert
                //Invoice Entry
                $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$inv_no','PO INVOICE','$invdate','$id','APPROVED','$compy','$loc','$org')");
                $jid = DB::getPdo()->lastInsertId();

                //get values
                $tds_applicable = $_POST['tds_applicable'];
                $tds_account = $_POST['tds_account_id'];
                $tds_amt = $_POST['tds_amount'];
                $sid = $_POST['supplier_id'];
                $subid = $_POST['subcontract_supplier_id'];

                if ($sid != "") {
                    $supacc = \DB::select("select account_structure_id from m_supplier_t where supplier_id=$sid");
                } else {
                    $supacc = \DB::select("select account_structure_id from m_subcontract_supplier_t where subcontract_supplier_id=$subid");
                }
                $supsiteid = $_POST['suppliersite_id'];
                $subsupsiteid = $_POST['subcontract_site_id'];
                if ($supsiteid != "") {
                    $state = \DB::select("SELECT state FROM m_supplier_sites_t  WHERE `supplier_site_id`='$supsiteid'");
                } else {
                    $state = \DB::select("SELECT state FROM  m_subcontract_sites_t  WHERE `subcontract_site_id`='$subsupsiteid'");
                }
                $stateid = $state[0]->state;
                $tax = $_POST['bulk_tax_group_id'];
                $taxgroup = \DB::table('m_tax_group_lines_t')->where('tax_group_id', $tax)->get();

                $invoiceid = \DB::table('p_po_invoice_lines_t')->select('p_po_invoice_lines_t.*', 'm_tax_group_t.display_name', 'm_products_t.tax_credit', 'm_products_t.account_code_id', 'm_products_t.disc_account_code', 'm_products_t.tax_credit')
                    ->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'p_po_invoice_lines_t.product_id')
                    ->join('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'p_po_invoice_lines_t.tax_group_id')->where('po_invoice_id', $id)->get();


                //Invoice Debit Insert
                $ins_arr = [];
                $rw = 0;

                if ($_POST['invoice_type'] == 'STANDARD') {
                    $inventory_acc = \DB::table('f_account_setting_t')->where('module_name', 'grn')->get();
                    $inventory_acc = $inventory_acc[0]->inventory_account_id;
                } else {
                    $inventory_acc = \DB::table('f_account_setting_t')->where('module_name', 'srn')->get();
                    $inventory_acc = $inventory_acc[0]->service_account_id;
                }


                $reversechargeaccount = \DB::table('f_account_setting_t')->where('module_name', 'reversechargeaccount')->get();
                $tkey = 0;

                foreach ($invoiceid as $key => $value) {

                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
                    $journal_lines_data[$tkey]['reference_id'] = $value->product_id;
                    $journal_lines_data[$tkey]['product_qty'] = $value->qty;
                    $journal_lines_data[$tkey]['account_id'] = $inventory_acc;
                    $rate = $value->unit_price - (($value->unit_price * $value->discount_percentage) / 100);
                    $rate = $rate * $value->qty;
                    $tax_details = array();
                    if ($value->tax_credit != "Yes") {
                        $taxval = (float) $value->display_name;
                        $rate = $rate + (($rate * $taxval) / 100);
                    } else {
                        $tax_details = \DB::table('m_tax_group_lines_t')->join('f_tax_code_t', 'f_tax_code_t.tax_code_id', '=', 'm_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*', 'f_tax_code_t.tax_code_percent')->where('m_tax_group_lines_t.tax_group_id', $value->tax_group_id)->get();
                    }

                    $journal_lines_data[$tkey]['debit_amount'] = $rate;
                    $journal_lines_data[$tkey]['credit_amount'] = '';
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    if (!isset($_POST['reverse_charge'])) {
                        foreach ($tax_details as $taxval) {
                            $rate = $value->unit_price - (($value->unit_price * $value->discount_percentage) / 100);
                            $rate = $rate * $value->qty;


                            $taxval1 = (float) $taxval->tax_code_percent;
                            $rate = ($rate * $taxval1) / 100;
                            if ($rate > 0) {
                                $tkey++;
                                $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                                $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                                $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
                                $journal_lines_data[$tkey]['reference_id'] = $value->product_id;
                                $journal_lines_data[$tkey]['product_qty'] = $value->qty;
                                $journal_lines_data[$tkey]['account_id'] = $taxval->input_tax_account_id;
                                $journal_lines_data[$tkey]['debit_amount'] = $rate;
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
                        }
                    }
                    $tkey++;


                }

                $transport_chargestotal = $_POST['transport_charges'];
                $unloading_chargestotal = $_POST['unloading_charges'];
                $insurance_chargestotal = $_POST['insurance_charges'];
                $round_off = $_POST['round_off'];
                $packing_chargestotal = $_POST['packing_charges'];
                $other_tax_amounttotal = $_POST['other_tax_amount'];
                $other_freight_amounttotal = $_POST['other_freight_amount'];

                if ($other_freight_amounttotal != "0") {
                    $otherfamttax = explode(',', $_POST['other_frieght_amount_tax']);
                    $othertax_details = \DB::table('m_tax_group_lines_t')->join('f_tax_code_t', 'f_tax_code_t.tax_code_id', '=', 'm_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*', 'f_tax_code_t.tax_code_percent')->where('m_tax_group_lines_t.tax_group_id', $otherfamttax[0])->get();
                    foreach ($othertax_details as $kk => $vv) {
                        $othertaxamt = ($otherfamttax[1] * $vv->tax_code_percent) / 100;
                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                        $journal_lines_data[$tkey]['reference_source'] = "";
                        $journal_lines_data[$tkey]['reference_id'] = "";
                        $journal_lines_data[$tkey]['product_qty'] = "";
                        $journal_lines_data[$tkey]['account_id'] = $vv->input_tax_account_id;
                        $journal_lines_data[$tkey]['debit_amount'] = round($othertaxamt, 2);
                        $journal_lines_data[$tkey]['credit_amount'] = '';
                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                        ;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        $tkey++;
                    }
                    $other_freight_amount = $otherfamttax[1];
                    $getfreight = \DB::select("select otherfreight_acccode_id from f_account_setting_t where module_name='purchaseinvoice'");
                    $getfeightacc = $getfreight[0]->otherfreight_acccode_id;
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "";
                    $journal_lines_data[$tkey]['reference_id'] = "";
                    $journal_lines_data[$tkey]['product_qty'] = "";
                    $journal_lines_data[$tkey]['account_id'] = $getfeightacc;
                    $journal_lines_data[$tkey]['debit_amount'] = round($other_freight_amount, 2);
                    $journal_lines_data[$tkey]['credit_amount'] = '';
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    $tkey++;
                }
                if ($other_tax_amounttotal != "0") {
                    $rw = $rw + 1;
                    $othrtaxamttax = explode(',', $_POST['other_tax_amount_tax']);
                    $othrtaxtax_details = \DB::table('m_tax_group_lines_t')->join('f_tax_code_t', 'f_tax_code_t.tax_code_id', '=', 'm_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*', 'f_tax_code_t.tax_code_percent')->where('m_tax_group_lines_t.tax_group_id', $othrtaxamttax[0])->get();
                    //  dd($othrtaxtax_details);
                    foreach ($othrtaxtax_details as $kkt => $vvot) {
                        $othrtaxtaxamt = ($othrtaxamttax[1] * $vvot->tax_code_percent) / 100;
                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                        $journal_lines_data[$tkey]['reference_source'] = "";
                        $journal_lines_data[$tkey]['reference_id'] = "";
                        $journal_lines_data[$tkey]['product_qty'] = "";
                        $journal_lines_data[$tkey]['account_id'] = $vvot->input_tax_account_id;
                        $journal_lines_data[$tkey]['debit_amount'] = round($othrtaxtaxamt, 2);
                        $journal_lines_data[$tkey]['credit_amount'] = '';
                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                        ;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        $tkey++;
                    }
                    $other_tax_amount = $othrtaxamttax[1];
                    $gettax = \DB::select("select othertax_acccode_id from f_account_setting_t where module_name='purchaseinvoice'");
                    $gettaxacc = $gettax[0]->othertax_acccode_id;
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "";
                    $journal_lines_data[$tkey]['reference_id'] = "";
                    $journal_lines_data[$tkey]['product_qty'] = "";
                    $journal_lines_data[$tkey]['account_id'] = $gettaxacc;
                    $journal_lines_data[$tkey]['debit_amount'] = round($other_tax_amount, 2);
                    $journal_lines_data[$tkey]['credit_amount'] = '';
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    $tkey++;
                }
                if ($transport_chargestotal != "0") {
                    $rw = $rw + 1;
                    $trnsamttax = explode(',', $_POST['transport_charges_tax']);
                    $trnstax_details = \DB::table('m_tax_group_lines_t')->join('f_tax_code_t', 'f_tax_code_t.tax_code_id', '=', 'm_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*', 'f_tax_code_t.tax_code_percent')->where('m_tax_group_lines_t.tax_group_id', $trnsamttax[0])->get();
                    foreach ($trnstax_details as $kkt => $vvt) {
                        $trnsptaxamt = ($trnsamttax[1] * $vvt->tax_code_percent) / 100;
                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                        $journal_lines_data[$tkey]['reference_source'] = "";
                        $journal_lines_data[$tkey]['reference_id'] = "";
                        $journal_lines_data[$tkey]['product_qty'] = "";
                        $journal_lines_data[$tkey]['account_id'] = $vvt->input_tax_account_id;
                        $journal_lines_data[$tkey]['debit_amount'] = round($trnsptaxamt, 2);
                        $journal_lines_data[$tkey]['credit_amount'] = '';
                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                        ;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        $tkey++;
                    }
                    $transport_charges = $trnsamttax[1] ?? 0;

                    $gettransport = \DB::select("select transport_acccode_id from f_account_setting_t where module_name='purchaseinvoice'");
                    $gettransportacc = $gettransport[0]->transport_acccode_id;
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "";
                    $journal_lines_data[$tkey]['reference_id'] = "";
                    $journal_lines_data[$tkey]['product_qty'] = "";
                    $journal_lines_data[$tkey]['account_id'] = $gettransportacc;
                    $journal_lines_data[$tkey]['debit_amount'] = round($transport_charges, 2);
                    $journal_lines_data[$tkey]['credit_amount'] = '';
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    $tkey++;
                }
                if ($insurance_chargestotal != "0") {
                    $rw = $rw + 1;
                    $insuranceamttax = explode(',', $_POST['insurance_charges_tax']);
                    $insurancetax_details = \DB::table('m_tax_group_lines_t')->join('f_tax_code_t', 'f_tax_code_t.tax_code_id', '=', 'm_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*', 'f_tax_code_t.tax_code_percent')->where('m_tax_group_lines_t.tax_group_id', $insuranceamttax[0])->get();
                    foreach ($insurancetax_details as $kki => $vvi) {
                        $insurancetaxamt = ($insuranceamttax[1] * $vvi->tax_code_percent) / 100;
                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                        $journal_lines_data[$tkey]['reference_source'] = "";
                        $journal_lines_data[$tkey]['reference_id'] = "";
                        $journal_lines_data[$tkey]['product_qty'] = "";
                        $journal_lines_data[$tkey]['account_id'] = $vvi->input_tax_account_id;
                        $journal_lines_data[$tkey]['debit_amount'] = round($insurancetaxamt, 2);
                        $journal_lines_data[$tkey]['credit_amount'] = '';
                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                        ;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        $tkey++;
                    }
                    $insurance_charges = $insuranceamttax[1] ?? 0;
                    $getinsurance = \DB::select("select insurance_acccode_id from f_account_setting_t where module_name='purchaseinvoice'");
                    $getinsuranceacc = $getinsurance[0]->insurance_acccode_id;

                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "";
                    $journal_lines_data[$tkey]['reference_id'] = "";
                    $journal_lines_data[$tkey]['product_qty'] = "";
                    $journal_lines_data[$tkey]['account_id'] = $getinsuranceacc;
                    $journal_lines_data[$tkey]['debit_amount'] = round($insurance_charges, 2);
                    $journal_lines_data[$tkey]['credit_amount'] = '';
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    $tkey++;
                }
                if ($packing_chargestotal != "0") {
                    $rw = $rw + 1;
                    $packingamttax = explode(',', $_POST['packing_charges_tax']);
                    $packingtax_details = \DB::table('m_tax_group_lines_t')->join('f_tax_code_t', 'f_tax_code_t.tax_code_id', '=', 'm_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*', 'f_tax_code_t.tax_code_percent')->where('m_tax_group_lines_t.tax_group_id', $packingamttax[0])->get();
                    foreach ($packingtax_details as $kkp => $vvp) {
                        $trnsptaxamt = ($packingamttax[1] * $vvp->tax_code_percent) / 100;
                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                        $journal_lines_data[$tkey]['reference_source'] = "";
                        $journal_lines_data[$tkey]['reference_id'] = "";
                        $journal_lines_data[$tkey]['product_qty'] = "";
                        $journal_lines_data[$tkey]['account_id'] = $vvp->input_tax_account_id;
                        $journal_lines_data[$tkey]['debit_amount'] = round($packingtaxamt, 2);
                        $journal_lines_data[$tkey]['credit_amount'] = '';
                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                        ;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        $tkey++;
                    }
                    $packing_charges = $packingamttax[1] ?? 0;

                    $getpacking = \DB::select("select packaging_acccode_id from f_account_setting_t where module_name='purchaseinvoice'");
                    $getpackingacc = $getpacking[0]->packaging_acccode_id;

                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "";
                    $journal_lines_data[$tkey]['reference_id'] = "";
                    $journal_lines_data[$tkey]['product_qty'] = "";
                    $journal_lines_data[$tkey]['account_id'] = $getpackingacc;
                    $journal_lines_data[$tkey]['debit_amount'] = round($packing_charges, 2);
                    $journal_lines_data[$tkey]['credit_amount'] = '';
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    $tkey++;
                }
                if ($unloading_chargestotal != "0") {
                    $rw = $rw + 1;

                    $unloadingamttax = explode(',', $_POST['unloading_charges_tax']);
                    $unloadingtax_details = \DB::table('m_tax_group_lines_t')->join('f_tax_code_t', 'f_tax_code_t.tax_code_id', '=', 'm_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*', 'f_tax_code_t.tax_code_percent')->where('m_tax_group_lines_t.tax_group_id', $unloadingamttax[0])->get();
                    foreach ($unloadingtax_details as $kku => $vvu) {
                        $unloadingtaxamt = ($unloadingamttax[1] * $vvu->tax_code_percent) / 100;
                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                        $journal_lines_data[$tkey]['reference_source'] = "";
                        $journal_lines_data[$tkey]['reference_id'] = "";
                        $journal_lines_data[$tkey]['product_qty'] = "";
                        $journal_lines_data[$tkey]['account_id'] = $vvu->input_tax_account_id;
                        $journal_lines_data[$tkey]['debit_amount'] = round($unloadingtaxamt, 2);
                        $journal_lines_data[$tkey]['credit_amount'] = '';
                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                        ;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        $tkey++;
                    }
                    $unloading_charges = $unloadingamttax[1] ?? 0;
                    $getunloading = \DB::select("select unloading_acccode_id from f_account_setting_t where module_name='purchaseinvoice'");
                    $getunloadingacc = $getunloading[0]->unloading_acccode_id;
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "";
                    $journal_lines_data[$tkey]['reference_id'] = "";
                    $journal_lines_data[$tkey]['product_qty'] = "";
                    $journal_lines_data[$tkey]['account_id'] = $getunloadingacc;
                    $journal_lines_data[$tkey]['debit_amount'] = round($unloading_charges, 2);
                    $journal_lines_data[$tkey]['credit_amount'] = '';
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    $tkey++;
                }
                //Credit Insert


                $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                $journal_lines_data[$tkey]['reference_source'] = "SUPPLIER";
                $journal_lines_data[$tkey]['reference_id'] = $_POST['supplier_id'];
                $journal_lines_data[$tkey]['product_qty'] = "";
                $journal_lines_data[$tkey]['account_id'] = $supacc[0]->account_structure_id;
                $journal_lines_data[$tkey]['debit_amount'] = '';
                $journal_lines_data[$tkey]['credit_amount'] = round(($_POST['invoice_grand_total']), 2);
                $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                ;
                $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                $tkey++;

                //TDS  Insert
                if ($tds_applicable == "YES") {
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "";
                    $journal_lines_data[$tkey]['reference_id'] = "";
                    $journal_lines_data[$tkey]['product_qty'] = "";
                    $journal_lines_data[$tkey]['account_id'] = $tds_account;
                    $journal_lines_data[$tkey]['debit_amount'] = '';
                    $journal_lines_data[$tkey]['credit_amount'] = round($tds_amt, 2);
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    $tkey++;
                }
                //Round off
                if ($round_off != "" && $round_off != 0) {
                    $getunloading = \DB::select("select roundoff_account_id from f_account_setting_t where module_name='roundoff'");
                    $roundoffaccountacc = $getunloading[0]->roundoff_account_id;

                    if ($round_off > 0) {
                        $roundoffvalpls = Round($round_off, 2);
                        $roundoffvalles = 0;
                    } else {
                        $roundoffvalpls = 0;
                        $roundoffvalles = Round((-1) * ($round_off), 2);
                    }
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                    $journal_lines_data[$tkey]['reference_source'] = "";
                    $journal_lines_data[$tkey]['reference_id'] = "";
                    $journal_lines_data[$tkey]['product_qty'] = "";
                    $journal_lines_data[$tkey]['account_id'] = $roundoffaccountacc;
                    $journal_lines_data[$tkey]['debit_amount'] = $roundoffvalpls;
                    $journal_lines_data[$tkey]['credit_amount'] = $roundoffvalles;
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    $tkey++;
                }
                // dd($ins_arr);
                \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);

                if (isset($_POST['reverse_charge'])) {
                    $inv_no = "REVERSE CHARGE-" . $_POST['bill_number'];
                    $invdate = $_POST['invoice_date'];
                    $org = \Session::get('organization');
                    $loc = "1";
                    $compy = \Session::get('companyid');

                    $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$inv_no','PO INVOICE','$invdate','$id','APPROVED','$compy','$loc','$org')");
                    $jid = DB::getPdo()->lastInsertId();
                    $tkey = 0;
                    $total = 0;
                    $journal_lines_data = array();
                    foreach ($invoiceid as $key => $value) {
                        $tax_details = \DB::table('m_tax_group_lines_t')->join('f_tax_code_t', 'f_tax_code_t.tax_code_id', '=', 'm_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*', 'f_tax_code_t.tax_code_percent')->where('m_tax_group_lines_t.tax_group_id', $value->tax_group_id)->get();
                        foreach ($tax_details as $taxval) {
                            $rate = $value->unit_price - (($value->unit_price * $value->discount_percentage) / 100);
                            $rate = $rate * $value->qty;


                            $taxval1 = (float) $taxval->tax_code_percent;
                            $total += $rate = ($rate * $taxval1) / 100;
                            if ($rate > 0) {
                                $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                                $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                                $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
                                $journal_lines_data[$tkey]['reference_id'] = $value->product_id;
                                $journal_lines_data[$tkey]['product_qty'] = $value->qty;
                                $journal_lines_data[$tkey]['account_id'] = $taxval->input_tax_account_id;


                                $journal_lines_data[$tkey]['debit_amount'] = $rate;
                                $journal_lines_data[$tkey]['credit_amount'] = '';
                                $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                                $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                                $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                                $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                                $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                                ;
                                $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                                $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                                $tkey++;
                            }
                        }
                    }

                    foreach ($invoiceid as $key => $value) {
                        $tax_details1 = \DB::table('m_tax_group_lines_t')->join('f_tax_code_t', 'f_tax_code_t.tax_code_id', '=', 'm_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*', 'f_tax_code_t.tax_code_percent')->where('m_tax_group_lines_t.tax_group_id', $value->tax_group_id)->get();
                        foreach ($tax_details1 as $taxval1) {
                            $rate = $value->unit_price - (($value->unit_price * $value->discount_percentage) / 100);
                            $rate = $rate * $value->qty;


                            $taxval = (float) $taxval1->tax_code_percent;
                            $total += $rate = ($rate * $taxval) / 100;
                            if ($rate > 0) {
                                $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                                $journal_lines_data[$tkey]['journal_date'] = $_POST['invoice_date'];
                                $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
                                $journal_lines_data[$tkey]['reference_id'] = $value->product_id;
                                $journal_lines_data[$tkey]['product_qty'] = $value->qty;
                                $journal_lines_data[$tkey]['account_id'] = $taxval1->output_tax_account_id;


                                $journal_lines_data[$tkey]['debit_amount'] = '';
                                $journal_lines_data[$tkey]['credit_amount'] = $rate;
                                $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                                $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                                $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                                $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                                $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                                ;
                                $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                                $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                                $tkey++;
                            }

                        }
                    }


                    \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);

                }



            }
        }

        \DB::select("update p_grn_hdr_t set invoice_created='Yes' where grn_id='" . $_POST['grn_number'] . "'");
        if (isset($_POST['need_to_close'])) {
            if ($_POST['need_to_close'] == "YES") {
                $po_numb = $_POST['po_number'][0];
                \DB::select("update p_po_invoice_hdr_t set need_to_close='1' where po_number in ($po_numb)");
                \DB::select("update p_po_hdr_t set po_status='CLOSED' where po_hdr_id in ($po_numb)");
            }
        }
        /**Auditlog**/
        if ($_POST['po_invoice_id'] == "") {
            $action = "create";
        } else {
            $action = "edit";
        }
        $this->auditlog($id, "purchaseinvoice", $action, $_POST, "p_po_invoice_hdr_t");

        return response()->json(array('status' => 'success', 'message' => $_POST['po_invoice_status'] . ' Successfully', 'id' => $id, 'lid' => $lid));

    }


    public function poinvapprovdatashow($id = null, $aprv = null, $message = null)
    {
        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'purchaseinvoice')->get();
        $this->data['tax_group_id_pop'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');

        $this->data['row'] = $row = \DB::table('p_po_invoice_hdr_t')->select(
            'p_po_invoice_hdr_t.*',
            'p_po_invoice_hdr_t.invoice_tax_total',
            'p_po_invoice_hdr_t.po_invoice_status',
            'p_po_invoice_hdr_t.invoice_grand_total',
            'p_po_invoice_hdr_t.po_invoice_id',
            'p_po_invoice_hdr_t.dc_number',
            'p_po_invoice_hdr_t.invoice_date',
            'p_po_invoice_hdr_t.payment_term_id',
            'p_po_invoice_hdr_t.freight_terms_id',
            'p_po_invoice_hdr_t.suppliersite_id',
            'p_po_invoice_hdr_t.invoice_currency_id',
            'p_po_invoice_hdr_t.project_id',
            'p_po_invoice_hdr_t.invoice_pricelist_id',
            'p_po_invoice_hdr_t.po_number',
            'p_grn_hdr_t.source',
            'p_po_invoice_hdr_t.po_date',
            'm_supplier_t.supplier_id',
            'm_supplier_t.supplier_name',
            'p_qc_header_t.qc_header_id',
            'p_qc_header_t.qc_number',
            'p_grn_hdr_t.grn_number',
            'p_po_invoice_hdr_t.dc_number',
            'p_grn_hdr_t.grn_id',
            'p_grn_hdr_t.supplier_type'
        )
            ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_po_invoice_hdr_t.supplier_id')
            ->leftJoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'p_po_invoice_hdr_t.po_number')
            ->leftJoin('p_grn_hdr_t', 'p_grn_hdr_t.grn_id', '=', 'p_po_invoice_hdr_t.grn_number')
            ->leftJoin('p_qc_header_t', 'p_qc_header_t.qc_header_id', '=', 'p_po_invoice_hdr_t.qc_id')
            ->where('po_invoice_id', $id)->get();
        $supplier_id = $this->data['row'][0]->supplier_id;
        $suppliersite_id=$this->data['row'][0]->suppliersite_id;
        $source = $this->data['row'][0]->source;
        $this->data['row']->source = $source;
        $suppname = \DB::table('m_supplier_t')->where('supplier_id', $supplier_id)->get();
        $this->data['suppdata'] = $suppname;
        $this->data['supplier_id'] = $supplier_id;
        $suppsitename = \DB::table('m_supplier_sites_t')->where('supplier_id', $supplier_id)->where('primary_address', "Yes")->get();
        //                  dd($suppsitename);
        $this->data['suppsitedata'] = $suppsitename;
        $suppsitegst=\DB::table('m_supplier_sites_t')->where('supplier_id',$supplier_id)->where('supplier_site_id',$suppliersite_id)->get();
        $this->data['suppsitedatagst']=$suppsitegst;
        $po_hdr_id = $this->data['row'][0]->po_number;//To get Po Header Id

        /*Karthigaa Purcpose for Show Multiple Po Number*/
        $po_numb = \DB::select("select po_number from p_po_hdr_t where po_hdr_id in ($po_hdr_id)");
        $po = "";
        foreach ($po_numb as $pk => $pv) {
            $po .= $pv->po_number . ",";
        }
        $po_number = rtrim($po, ",");
        $this->data['row'][0]->ponumber = $po_number;
        $this->data['subcontract_supplier_id'] = $subcontract_supplier_id = $this->data['row'][0]->subcontract_supplier_id;
        $this->data['subcontract_site_id'] = $subcontract_site_id = $this->data['row'][0]->subcontract_site_id;
        $suppconname = \DB::table('m_subcontract_supplier_t')->where('subcontract_supplier_id', $subcontract_supplier_id)->get();
        $this->data['suppcondata'] = $suppconname;
        $suppconsitename = \DB::table('m_subcontract_sites_t')->where('subcontract_supplier_id', $subcontract_supplier_id)->where('primary_address', 'Yes')->get();
        $this->data['suppconsitedata'] = $suppconsitename;
        if ($aprv != "") {
            $this->data['aprvidenty'] = $aprv;
        } else {
            $this->data['aprvidenty'] = "";
        }

        //  $pohdrid =$this->data['row'][0]->po_hdr_id;
        $this->data['invoice_type'] = $this->data['row'][0]->invoice_type;
        //                  $tablelines = \DB::table('p_po_lines_t')->where('po_hdr_id',$pohdrid)->get();
        $tablelines = \DB::table('p_po_invoice_lines_t')->where('po_invoice_id', $id)->get();

        $this->data['linedata'] = $tablelines;
        $this->data['po_invoice_id'] = $row[0]->po_invoice_id;
        $this->data['row']->invoice_date = $row[0]->invoice_date;
        $this->data['row']->po_date = $row[0]->po_date;
        //                    $this->data['row']->invoice_date = date(\Session::get('p_date_format'), strtotime($inv_date));
        $this->data['row'][0]->bill_number = $row[0]->bill_number;

        $this->data['row'][0]->po_invoice_status = $row[0]->po_invoice_status;
        $this->data['row']->invoice_tax_total = $row[0]->invoice_tax_total;
        $this->data['row']->invoice_grand_total = $row[0]->invoice_grand_total;
        $this->data['row']->tds_applicable = $row[0]->tds_applicable;
        $this->data['row']->tds_prcnt = $row[0]->tds_prcnt;
        $this->data['row']->tds_amount = $row[0]->tds_amount;
        $this->data['row']->packing_charges_tax = $row[0]->packing_charges_tax;
        $this->data['row']->insurance_charges_tax = $row[0]->insurance_charges_tax;
        $this->data['row']->transport_charges_tax = $row[0]->transport_charges_tax;
        $this->data['row']->unloading_charges_tax = $row[0]->unloading_charges_tax;
        $this->data['row']->other_tax_amount_tax = $row[0]->other_tax_amount_tax;
        $this->data['row']->other_frieght_amount_tax = $row[0]->other_frieght_amount_tax;
        $this->data['row']->need_to_close = $row[0]->need_to_close;
        $this->data['row']->reverse_charge = $row[0]->reverse_charge;

        $this->data['row']->remarks = $row[0]->remarks; 

        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->po_invoice_lines_id = $value->po_invoice_lines_id;
                $p_id = $value->product_id;
                $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id, 'and uom_code_id=' . $value->uom_code_id);
                $this->data['linedata'][$key]->unit_price = $value->unit_price;
                $this->data['linedata'][$key]->discount_percentage = $value->discount_percentage;
                $this->data['linedata'][$key]->discount_amount = $value->discount_amount;
                $this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->hsn_code, 'and gst_code_hdr_id=' . $value->hsn_code);
                $this->data['linedata'][$key]->tax_group_id = $this->jcustomselect('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id, 'and tax_group_id=' . $value->tax_group_id);

                $tax_crd = \DB::select("select tax_credit from m_products_t where product_id=" . $p_id);
                $this->data['linedata'][$key]->tax_credit = $tax_crd[0]->tax_credit;
            }
        }
        $qchkdtls = \DB::table('p_po_invoice_lines_t')->where('po_invoice_id', $id)->get();

        $this->data['qcheckdata'] = $qchkdtls;
        $qchdr = \DB::table('p_po_invoice_hdr_t')->where('po_invoice_id', $id)->get();
        $qc_id = $qchdr[0]->qc_id;
        //dd($qchdr);
        $this->data['row']->transport_charges = $qchdr[0]->transport_charges;
        $this->data['row']->unloading_charges = $qchdr[0]->unloading_charges;
        $this->data['row']->insurance_charges = $qchdr[0]->insurance_charges;
        $this->data['row']->packing_charges = $qchdr[0]->packing_charges;
        $this->data['row']->other_freight_amount = $qchdr[0]->other_freight_amount;
        $this->data['row']->other_tax_amount = $qchdr[0]->other_tax_amount;
        $this->data['row']->tds_applicable = $qchdr[0]->tds_applicable;
        $this->data['row']->tds_prcnt = $qchdr[0]->tds_prcnt;
        $this->data['row']->tds_amount = $qchdr[0]->tds_amount;
        $this->data['row']->supplier_invoice_date = $qchdr[0]->supplier_invoice_date;
        $this->data['row']->supplier_invoice_no = $qchdr[0]->supplier_invoice_no;
        $this->data['row']->dc_number = $qchdr[0]->dc_number;
        $this->data['row']->attachfile_name = $qchdr[0]->attachfile_name;
        $this->data['row']->dc_date = $qchdr[0]->dc_date;
        $this->data['row']->due_date = $qchdr[0]->due_date;
        $this->data['row']->freight_amount = $qchdr[0]->freight_amount;

        $transportTax = $this->data['row']->transport_charges_tax; 

            $trans_tax_group_id   = null;
            $trans_taxable_value  = null;

            if (!empty($transportTax)) {
                [$trans_tax_group_id, $trans_taxable_value] = array_pad(explode(',', $transportTax), 2, null);
            }

            if($trans_tax_group_id ==8){
                $transport_charges_tax = $this->data['row']->transport_charges;
            }else{
                $transport_charges_tax = $this->data['row']->transport_charges - $trans_taxable_value;
            }

            if(!empty($transport_charges_tax)){
                $transport_charges_tax = $transport_charges_tax;
            }else{
                $transport_charges_tax = '0';
            }
        $this->data['row']->transport_charges_value = $transport_charges_tax;   

        $this->data[] = $qchdr[0]->qc_id;

        $this->data['suppliersid'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', $supplier_id);
        $this->data['suppliersite_id'] = $this->jCombo('m_supplier_sites_t', 'supplier_site_id', 'supplier_site_name', $qchdr[0]->suppliersite_id);
        $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $qchdr[0]->project_id);
        $this->data['freight_terms_id'] = $this->jCombo('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', $qchdr[0]->freight_terms_id);
        $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $qchdr[0]->tds_account_id);
        $this->data['invoice_currency_id'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $qchdr[0]->invoice_currency_id);
        $this->data['invoice_pricelist_id'] = $this->jCombo('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $qchdr[0]->invoice_pricelist_id, ' and price_list_type="Sales"');
        $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name ', $qchdr[0]->payment_term_id);
        $this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $qchdr[0]->freight_carrier_id, ' and source_type_id="Purchase"');
        $this->data['delivery_terms_id'] = $this->jCombo('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $qchdr[0]->delivery_terms_id);
        $this->data['payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $qchdr[0]->payment_method_id);
        $this->data['pageMethod'] = "poinvoiceapproval";


        if ($message == "welcome") {
            return $this->data;
        } else {
            return view('purchaseinvoice.form', $this->data);
        }
    }

    public function invoiceDataview($id = null)
    {

        $this->data['row'] = $row = \DB::table('p_po_invoice_hdr_t')->select(
            'p_po_invoice_hdr_t.*',
            'f_account_currency_t.currency_code',
            'f_account_currency_t.currency_code',
            'p_po_invoice_hdr_t.attachfile_name',
            'm_payment_terms_t.payment_term_name',
            'i_pricelist_hdr_t.pricelist_name',
            'f_account_structure_t.concatenated_segments',
            'f.concatenated_segments as concatenated_segments1',
            'p_po_invoice_hdr_t.bill_number',
            'p_po_invoice_hdr_t.invoice_tax_total',
            'p_po_invoice_hdr_t.dc_date',
            'p_po_invoice_hdr_t.due_date',
            'm_payment_methods_t.payment_method_name',
            'p_po_invoice_hdr_t.po_invoice_id',
            'p_po_invoice_hdr_t.po_number as po_hdr_id',
            'p_po_invoice_hdr_t.invoice_date',
            'p_po_invoice_hdr_t.bill_number',
            'p_po_invoice_hdr_t.po_date',
            'm_frieghtcarriers_hdr_t.carrier_name',
            'm_supplier_t.supplier_id',
            'm_supplier_t.supplier_name',
            'm_frieghtterms_t.fob_point_name',
            'm_projects_t.project_name',
            'm_delivery_terms_t.delivery_term_name',
            'm_supplier_sites_t.supplier_site_name',
            'p_qc_header_t.qc_number',
            'p_grn_hdr_t.grn_number'
        )
            ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_po_invoice_hdr_t.supplier_id')
            ->leftJoin('m_supplier_sites_t', 'm_supplier_sites_t.supplier_site_id', '=', 'p_po_invoice_hdr_t.suppliersite_id')
            ->leftJoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'p_po_invoice_hdr_t.po_number')
            ->leftJoin('p_grn_hdr_t', 'p_grn_hdr_t.grn_id', '=', 'p_po_invoice_hdr_t.grn_number')
            ->leftJoin('p_qc_header_t', 'p_qc_header_t.qc_header_id', '=', 'p_po_invoice_hdr_t.qc_id')
            ->leftJoin('m_projects_t', 'm_projects_t.project_id', '=', 'p_po_invoice_hdr_t.project_id')
            ->leftJoin('i_pricelist_hdr_t', 'i_pricelist_hdr_t.pricelist_hdr_id', '=', 'p_po_invoice_hdr_t.invoice_pricelist_id')
            ->leftJoin('m_frieghtterms_t', 'm_frieghtterms_t.frieghtterm_id', '=', 'p_po_invoice_hdr_t.freight_terms_id')
            ->leftJoin('m_payment_terms_t', 'm_payment_terms_t.payment_term_id', '=', 'p_po_invoice_hdr_t.payment_term_id')
            ->leftJoin('f_account_currency_t', 'f_account_currency_t.account_currency_id', '=', 'p_po_invoice_hdr_t.invoice_currency_id')
            ->leftJoin('m_payment_methods_t', 'm_payment_methods_t.payment_method_id', '=', 'p_po_invoice_hdr_t.payment_method_id')
            ->leftJoin('m_delivery_terms_t', 'm_delivery_terms_t.delivery_terms_id', '=', 'p_po_invoice_hdr_t.delivery_terms_id')
            ->leftJoin('m_frieghtcarriers_hdr_t', 'm_frieghtcarriers_hdr_t.ar_frieghtcarriers_hdr_id', '=', 'p_po_invoice_hdr_t.freight_carrier_id')
            ->leftJoin('f_account_structure_t', 'f_account_structure_t.f_account_structure_id', '=', 'p_po_invoice_hdr_t.tds_account_id')
            ->leftJoin('f_account_structure_t as f', 'f.f_account_structure_id', '=', 'p_po_invoice_hdr_t.tcs_account_id')

            ->where('po_invoice_id', $id)->get();
        // dd($this->data['row']);
        $this->data['po_num'] = \DB::select("select * from p_po_hdr_t where po_hdr_id in (" . $row[0]->po_number . ")");
        //   dd($this->data['po_num']);
        $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', $row[0]->supplier_id);
        $tablelines = \DB::table('p_po_invoice_lines_t')->where('po_invoice_id', $id)->get();
        //        dd($tablelines);
        $this->data['linedata'] = $tablelines;
        $this->data['choosefile'] = $row[0]->attachfile_name;

        $this->data['po_invoice_id'] = $row[0]->po_invoice_id;
        $invoice_date = $row[0]->invoice_date;
        $this->data['row'][0]->invoice_date = date(\Session::get('p_date_format'), strtotime($invoice_date));
        $po_date = $row[0]->po_date;
        $this->data['row'][0]->po_date = date(\Session::get('p_date_format'), strtotime($po_date));
        $this->data['row'][0]->invoice_number = $row[0]->bill_number;

        if ($row[0]->need_to_close == "1") {
            $need_to_close = "YES";
        } else {
            $need_to_close = "NO";
        }
        $this->data['row'][0]->need_to_close = $need_to_close;
        $this->data['row'][0]->invoice_tax_total = $row[0]->invoice_tax_total;
        $this->data['row'][0]->invoice_grand_total = $row[0]->invoice_grand_total;
        $dc_date = $row[0]->dc_date;
        $this->data['row']->dc_date = date(\Session::get('p_date_format'), strtotime($dc_date));
        $due_date = $row[0]->due_date;
        $this->data['row']->due_date = date(\Session::get('p_date_format'), strtotime($due_date));
        $supplier_inv_date = $row[0]->supplier_invoice_date;
        $this->data['row']->supplier_invoice_date = date(\Session::get('p_date_format'), strtotime($supplier_inv_date));

        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                if (isset($_GET['return'])) {
                    if ($_GET['return'] == 'paymentrequest' || $_GET['return'] == 'paymentrequestapproval') {
                        $this->data['choosefile'] = $this->idname('choosefile', 'm_products_t', 'product_id', $value->product_id);
                        $this->data['product_id'] = $value->product_id;
                    }
                }
                $this->data['linedata'][$key]->product_code = $this->data['product_code'] = $this->idname('product_code', 'm_products_t', 'product_id', $value->product_id);
                $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->idname('concatenated_product', 'm_products_t', 'product_id', $value->product_id);


                $this->data['linedata'][$key]->uom_code_id = $this->idname('uom_code', 'm_uom_codes_t', 'uom_code_id', $value->uom_code_id);
                $this->data['linedata'][$key]->unit_price = $value->unit_price;
                //dd($this->data['linedata'][$key]->unit_price);
                $this->data['linedata'][$key]->discount_percentage = $value->discount_percentage;
                $this->data['linedata'][$key]->discount_amount = $value->discount_amount;
                $this->data['linedata'][$key]->hsn_code = $this->idname('classification_code', 'f_gst_code_hdr_t', 'gst_code_hdr_id', $value->hsn_code);
                $this->data['linedata'][$key]->tax_group_id = $this->idname('tax_group_name', 'm_tax_group_t', 'tax_group_id', $value->tax_group_id);
                $this->data['linedata'][$key]->accept_qty = $value->accept_qty;
                $this->data['linedata'][$key]->tax_amount = $value->tax_amount;
                $this->data['linedata'][$key]->line_total = $value->line_total;
                $this->data['linedata'][$key]->promised_date = $value->promised_date;
                $this->data['linedata'][$key]->comments = $value->comments;
                $this->data['linedata'][$key]->po_invoice_lines_id = $value->po_invoice_lines_id;
            }
        }
        //dd($this->data);
        //  $this->data['url']=$_GET['return'];
        if (isset($_GET['return'])) {
            $this->data['url'] = $_GET['return'];
        } else {
            $this->data['url'] = '';
        }
        return view('purchaseinvoice.view', $this->data);
    }






    function loadtds($id = null, $tds = null)
    {
        $tds = array();
        $tds['tds_percentage'] = '';
        $tds['tds_account_id'] = '';
        $supplier = \DB::table('m_supplier_t')->where('supplier_id', $id)->get();
        //                dd($supplier);
        if ($supplier->isNotEmpty()) {
            $tds['tds_percentage'] = $supplier[0]->tds_percentage;
            $tds['tds_account_id'] = $supplier[0]->tds_account_id;
            $tds['tcs_percentage'] = $supplier[0]->tcs_percentage;
            $tds['tcs_account_id'] = $supplier[0]->tcs_account_id;

            //                          dd($tds);
        } else {
            $tds = 0;
        }
        return $tds;
    }

    function loadtdssubcontract($id = null, $tds = null)
    {
        $tds = array();
        $tds['tds_percentage'] = '';
        $tds['tds_account_id'] = '';
        $supplier = \DB::table('m_subcontract_supplier_t')->where('subcontract_supplier_id', $id)->get();
        //                dd($supplier);
        if ($supplier->isNotEmpty()) {
            $tds['tds_percentage'] = $supplier[0]->tds_percentage;
            $tds['tcs_percentage'] = $supplier[0]->tcs_percentage;
            $tds['tds_account_id'] = $supplier[0]->tds_account_id;
            $tds['tcs_account_id'] = $supplier[0]->tcs_account_id;
            //                          dd($tds);
        } else {
            $tds = 0;
        }
        return $tds;
    }



    function getFreight($id)
    {
        $sql = \DB::SELECT("select * from m_frieghtcarriers_hdr_t where ar_frieghtcarriers_hdr_id='" . $id . "'");
        if (!empty($sql))
            return $sql[0]->carrier_name;
        else
            return '';
    }

    public function poinvoiceprint($id)
    {

        require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');

        $row = \DB::table('p_po_invoice_hdr_t')->where('po_invoice_id', $id)->get();
        $hdr_id = $row[0]->po_invoice_id;

        $this->data['invoice_date'] = date('d-m-Y', strtotime($row[0]->invoice_date));
        $this->data['invoice_number'] = $row[0]->bill_number;
        $this->data['remarks'] = $row[0]->remarks;
        $this->data['reference_number'] = $row[0]->reference_number;
        $this->data['supplier_ref_no'] = $row[0]->supplier_invoice_no;
        $this->data['transport_charges'] = $row[0]->transport_charges;
        $this->data['payment_status'] = $row[0]->payment_status;

        $po = \DB::table('p_po_hdr_t')->where('po_hdr_id', $row[0]->po_number)->get();

        if ($po->isNotEmpty()) {
            $this->data['po_for_verdura'] = $po[0]->po_for_verdura;
        } else {
            $this->data['po_for_verdura'] = '';
        }


        $this->data['despatch_through'] = $this->getFreight($row[0]->freight_carrier_id);
        $comp = \DB::table('m_company_t')->where('company_id', $row[0]->company_id)->get();

        if ($comp->isNotEmpty()) {
            $this->data['gstno'] = $comp[0]->gst_no;
            $this->data['pan_no'] = $comp[0]->pan_no;
            $this->data['email_id'] = $comp[0]->email_id;
            $this->data['cin_no'] = $comp[0]->cin_no;
            $this->data['excise_registration_no'] = $comp[0]->excise_registration_no;
            $this->data['tax_reg_no'] = $comp[0]->tax_reg_no;
        } else {
            $this->data['gst_no'] = "";
            $this->data['pan_no'] = "";
            $this->data['email_id'] = "";
            $this->data['cin_no'] = "";
            $this->data['excise_registration_no'] = "";
            $this->data['tax_reg_no'] = "";
        }

        $location = $this->getLocationaddress();
        $deliveryterm = Deliveryterms::where('delivery_terms_id', $row[0]->delivery_terms_id)->pluck('delivery_term_name')->first();
        $this->data['deliveryterm'] = $deliveryterm;
        error_reporting(0);
        $this->data['location_name'] = $location[0]->location_name;
        $this->data['country'] = $this->getCountry($location[0]->country_id);
        $this->data['state'] = $this->getState($location[0]->state_id);
        $this->data['state_name'] = $this->data['state'][0]->state_name;
        $this->data['loc_state_code'] = $this->data['state'][0]->state_code;
        $this->data['city'] = $this->getCity($location[0]->city_id);
        ;
        $this->data['pan_no'] = $location[0]->pan_no;
        $this->data['street'] = $location[0]->street_name;
        $companyadd = $this->data['address'] = $location[0]->address;
        $this->data['state_code_no'] = $this->data['state'][0]->state_code_no;
        $this->data['pincode'] = $location[0]->pincode;
        if ($companyadd == "null") {
            $this->data['company_address'] = $this->data['street'] . "," . $this->data['city'] . "," . $this->data['state_name'] . "," . $this->data['country'];
        } else {
            $this->data['company_address'] = $this->data['address'] . "," . $this->data['street'] . "," . $this->data['city'] . "," . $this->data['state_name'] . "," . $this->data['country'];
        }


        $location = "1";
        $comp = \DB::SELECT('select * from m_location_t where location_id=' . $location . '');

        if ($row[0]->supplier_id) {
            $billaddress = $this->getSupplier($row[0]->supplier_id);
            $sup_address = $this->getSuppliersite($po[0]->suppliersite_id);
            if ($billaddress != 0) {
                $this->data['bcustomer'] = $billaddress[0]->supplier_name;
                $this->data['bcustomer_gst'] = $billaddress[0]->gst_no;
            } else {
                $this->data['bcustomer'] = '';
                $this->data['bcustomer_gst'] = '';
            }


            if ($sup_address != 0) {
                $this->data['baddress'] = $sup_address[0]->address;
                $this->data['bcity'] = $this->getCity($sup_address[0]->city);
                $this->data['bcountry'] = $this->getCountry($sup_address[0]->country);
                $this->data['bstate'] = $this->getState($sup_address[0]->state);
                $this->data['bstate_name'] = $this->data['bstate'][0]->state_name;
                $this->data['bstate_code'] = $this->data['bstate'][0]->state_code;
                $this->data['bpincode'] = $sup_address[0]->pincode;
                $this->data['bcontact_number'] = $sup_address[0]->contact_number;
                $this->data['bgst_no'] = $sup_address[0]->gst_number;
                $dest = \DB::SELECT('select * from m_location_t where location_id=' . $sup_address[0]->location_id . '');
                $this->data['destination'] = $dest[0]->location_name;
            } else {
                $this->data['baddress'] = "";
                $this->data['bcity'] = "";
                $this->data['bcountry'] = "";
                $this->data['bstate'] = "";
                $this->data['bpincode'] = "";
                $this->data['bcontact_number'] = "";
                $this->data['bgst_no'] = "";
            }
        } else {
            $subcontract_name = $this->getSubcontractSupplier($row[0]->subcontract_supplier_id);
            $subcontract_address = $this->getSubcontractSuppliersite($subcontract_name[0]->subcontract_supplier_id);

            if ($subcontract_name != 0) {
                $this->data['bcustomer'] = $subcontract_name[0]->subcontract_name;
                $this->data['bcustomer_gst'] = $subcontract_name[0]->gst_no;
            } else {
                $this->data['bcustomer'] = '';
                $this->data['bcustomer_gst'] = '';
            }
            if ($subcontract_address != 0) {
                $this->data['baddress'] = $subcontract_address[0]->address;
                $this->data['bcity'] = $this->getCity($subcontract_address[0]->city);
                $this->data['bcountry'] = $this->getCountry($subcontract_address[0]->country);
                $this->data['bstate'] = $this->getState($subcontract_address[0]->state);
                $this->data['bstate_name'] = $this->data['bstate'][0]->state_name;
                $this->data['bstate_code'] = $this->data['bstate'][0]->state_code;
                $this->data['bpincode'] = $subcontract_address[0]->pincode;
                $this->data['bcontact_number'] = $subcontract_address[0]->contact_number;
                $this->data['bgst_no'] = $subcontract_address[0]->gst_number;
                $dest = \DB::SELECT('select * from m_location_t where location_id=' . $subcontract_address[0]->location_id . '');
                $this->data['destination'] = $dest[0]->location_name;
            } else {
                $this->data['baddress'] = "";
                $this->data['bcity'] = "";
                $this->data['bcountry'] = "";
                $this->data['bstate'] = "";
                $this->data['bpincode'] = "";
                $this->data['bcontact_number'] = "";
                $this->data['bgst_no'] = "";
            }
        }
        // dd($subcontract_name);
        $this->data['gst_no'] = $comp[0]->gst_no;

        $this->data['supplier_address'] = $this->data['baddress'] . "," . $this->data['bcity'] . "-" . $this->data['bpincode'] . "," . $this->data['bstate_name'] . "," . $this->data['bcountry'] . ".";

        $lines = \DB::table('p_po_invoice_lines_t')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'p_po_invoice_lines_t.product_id')
            ->leftjoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'p_po_invoice_lines_t.uom_code_id')
            ->leftjoin('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'p_po_invoice_lines_t.tax_group_id')
            ->where('p_po_invoice_lines_t.po_invoice_id', $id)->get();

        $other_charges = $row[0]->transport_charges + $row[0]->unloading_charges + $row[0]->insurance_charges + $row[0]->packing_charges + $row[0]->other_tax_amount + $row[0]->other_freight_amount;
        $this->data['subgrid'] = $lines;
        $subtotal = 0;
        $sub_total = 0;
        $tot_dis = 0;
        foreach ($this->data['subgrid'] as $key => $value) {


            if ($value->product_id != null) {

                $arr = $this->getProduct($value->product_id);
                $polines[$key]['product'] = $arr['concat_segment'];
                $polines[$key]['hsn_code'] = $arr['hsn_code'];
                $polines[$key]['uom'] = $arr['primary_uom_code'];
            } else {

                $polines[$key]['product'] = $value->product_description;
                $polines[$key]['hsn_code'] = $value->classification_code;
                $polines[$key]['uom'] = '';

            }

            if ($value->tax_group_id != null) {
                $tax = \DB::table("m_tax_group_t")->select('display_name')->where('tax_group_id', $value->tax_group_id)->get();

                if (!empty($tax)) {
                    $polines[$key]['disp_name'] = $tax[0]->display_name;

                    $polines[$key]['gst'] = $polines[$key]['disp_name'];
                    $polines[$key]['qty'] = $value->qty;
                    $polines[$key]['tax_amount'] = $value->tax_amount;
                    if ($value->promised_date != "0000-00-00")
                        $date = date('d-m-Y', strtotime($value->promised_date));
                    else
                        $date = "";
                    $polines[$key]['promised_date'] = $date;
                    $polines[$key]['unit_price'] = $value->unit_price;
                    $polines[$key]['discount_amount'] = $value->discount_percentage;
                    $polines[$key]['disc_amount'] = $value->discount_amount;

                    $tot_dis += $polines[$key]['disc_amount'];
                    $polines[$key]['amount'] = $value->unit_price * $value->qty;

                    $polines[$key]['gst'] = $polines[$key]['disp_name'];
                    $polines[$key]['tax'] = $polines[$key]['amount'] * $polines[$key]['gst'] / 100;
                    $polines[$key]['sgst_val2'] = $polines[$key]['amount'] * ($polines[$key]['gst'] / 100) / 2;
                    $polines[$key]['cgst_val2'] = $polines[$key]['amount'] * ($polines[$key]['gst'] / 100) / 2;
                } else {
                    $polines[$key]['disp_name'] = "";
                    $polines[$key]['gst'] = "";
                }
            } else {
                $polines[$key]['disp_name'] = "";
                $polines[$key]['gst'] = "";
            }




            $discount = $value->qty * ($value->unit_price * ($value->discount_percentage / 100));
            $dis_amt = ($value->unit_price) - ($value->unit_price * ($value->discount_percentage / 100));

            $tot_dis = $tot_dis + $discount;
            $amount = $dis_amt * $value->qty;

            $polines[$key]['amount'] = $amount;
            $subtotal = $amount + $subtotal;
            $sub_total = $sub_total + $amount;

        }
        $this->data['linedata'] = $polines;//dd($this->data['linedata']);
        $this->data['sub_total'] = $subtotal;
        $this->data['total_discount'] = $tot_dis;
        $gstdata = \DB::select("select * from p_po_invoice_lines_t where po_invoice_id='" . $id . "' and tax_group_id!='0' group by tax_group_id");

        if ($gstdata == null) {
            $gstvalue = array();
            $gstdata = 0;
            $sgst = 0;
            $cgst = 0;
            $gsttotal = 0;
            $grand_total = 0;
        } else {

            $gstvalue = array();
            $sgst = 0;
            $cgst = 0;
            $gsttotal = 0;
            $grand_total = 0;
            foreach ($gstdata as $gst_key => $gst_value) {
                $arr_sgcgst = array('sgst', 'cgst');
                $arr = $this->getProduct($gst_value->product_id);
                $tax = \DB::table("m_tax_group_t")->where('tax_group_id', $gst_value->tax_group_id)->get();
                if (strpos($tax[0]->tax_group_name, 'IGST') !== false) {
                    $gstvalue[$gst_key]['gsttype'] = "IGST";
                } else {
                    $gstvalue[$gst_key]['gsttype'] = "GST";
                }
                if ($tax->isNotEmpty())
                    $display_name = $tax[0]->display_name;

                $gst = $this->getGst($gst_value->tax_group_id, $id);
                $gstvalue[$gst_key]['gst'][] = $display_name;/* important */

                $gstvalue[$gst_key]['sgcgst'][] = $display_name / 2;

                $gstvalue[$gst_key]['hsn'] = $arr['hsn_code'];
                $gstvalue[$gst_key]['amount'] = $gst['amount'];
                $gstvalue[$gst_key]['gst_id'] = $gst_value->tax_group_id;
                $gstvalue[$gst_key]['gst_val'] = $gst['amount'] * ($display_name / 100); /* important */

                $gstvalue[$gst_key]['sgst_val'] = $gst['amount'] * ($display_name / 100) / 2;
                $gstvalue[$gst_key]['cgst_val'] = $gst['amount'] * ($display_name / 100) / 2;
                $gsttotal = $gsttotal + $gstvalue[$gst_key]['gst_val'];
                $this->data['sgcgstt'] = $arr_sgcgst;

            }

            $this->data['net_amount'] = $gstvalue[$gst_key]['amount'] + $gstvalue[$gst_key]['gst_val'] - $discount;
        }

        /********************* company *******************/
        $company = $this->getCompany();

        if (!empty($company)) {
            $company_name = $company[0]->company_name;
            $company_logo_name = $company[0]->company_logo_name;
            $this->data['company_name'] = $company_name;
            $this->data['company_logo_name'] = $company_logo_name;
        }

        /******************** end ************************/

        /******************** Payments Term **************/

        $ap_payment_term_name = $this->getPaymentterms($row[0]->payment_term_id);
        $this->data['ap_payment_term_name'] = $ap_payment_term_name;

        $fob_point_name = $this->getFreightterms($row[0]->freight_terms_id);
        $this->data['fob_point_name'] = $fob_point_name;

        /******************** end ************************/

        //dd($gstvalue);
        /*$tot_sgst=$sgst;
        $tot_cgst=$cgst;*/
        //dd($gsttotal);
        $gst_total = $gsttotal;
        $grand_total = $gst_total + $sub_total + $other_charges;
        //dd($gst_total);
        //Maruthu Purpose to GST calculation End
        $this->data['gst'] = $gstvalue;
        $this->data['gsttotal'] = $gsttotal;
        $this->data['value'] = $polines;
        $this->data['sub_total'] = round($subtotal);
        $this->data['subtotal'] = $sub_total;
        $this->data['grand_total'] = $grand_total;
        $this->data['id'] = $id;
        $this->data['print'] = "PRINT";
        $this->data['print_val'] = '1';


        $terms_condition = \DB::table('a_rpt_displayelements_hdr_t')->leftjoin('a_rpt_displayelements_lines_t', 'a_rpt_displayelements_lines_t.rpt_displayelements_hdr_id', '=', 'a_rpt_displayelements_hdr_t.rpt_displayelements_hdr_id')->select('a_rpt_displayelements_lines_t.element_content')->where('a_rpt_displayelements_hdr_t.report_source', 124)->where('a_rpt_displayelements_lines_t.element_name', 'TERMS&CONDITIONS')->get();

        if (count($terms_condition) > 0) {
            $this->data['terms_condition'] = $terms_condition;
        } else {
            $this->data['terms_condition'] = [];
        }

        return view('purchaseinvoice.printform', $this->data);

    }

    function getLocationaddress()
    {
        $sql = array();
        $location = "1";

        $sql = \DB::SELECT('select * from m_location_t where location_id=' . $location . '');
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }


    function getCity($id = null)
    {
        $sql = array();

        $sql = \DB::SELECT('select city_id,city_name from m_cities_t where city_id=' . $id . '');
        if (!empty($sql)) {
            return $sql[0]->city_name;
        } else {
            return '';
        }

    }
    function getState($id = null)
    {
        $sql = array();

        $sql = \DB::SELECT('select state_id,state_name,state_code,state_code_no from m_states_t where state_id=' . $id . '');
        if (!empty($sql)) {
            return $sql;
        } else {
            return '';
        }

    }
    function getCountry($id = null)
    {
        $sql = array();
        $sql = \DB::SELECT('select country_id,country_name from m_countries_t where country_id =' . $id . '');
        if (!empty($sql)) {
            return $sql[0]->country_name;
        } else {
            return '';
        }
    }
    function getProduct($id = null)
    {
        $product = array();
        $product = \DB::table('m_products_t as pdt')
            ->leftJoin('m_uom_codes_t as uom', 'uom.uom_code_id', '=', 'pdt.trx_uom_id')
            ->leftJoin('f_gst_code_hdr_t as gst', 'gst.gst_code_hdr_id', '=', 'pdt.hsn_code')
            ->select('uom.uom_code', 'pdt.concatenated_product', 'gst.classification_code as hsn_code')
            ->where('pdt.product_id', $id)->get();
        //dd($product);
        if ($product->isNotEmpty()) {

            $date = date('Y-m-d');
            $tax = \DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='" . $product[0]->hsn_code . "' and start_date<='$date' and end_date>='$date' and active='Yes'");

            if (!empty($tax)) {
                $product['tax_group_id'] = $tax[0]->tax_group_id;
            } else {
                $product['tax_group_id'] = 0;
            }
            $product['concat_segment'] = $product[0]->concatenated_product;
            $product['primary_uom_code'] = $product[0]->uom_code;
            $product['hsn_code'] = $product[0]->hsn_code;


            return $product;


        } else {
            return 0;
        }

    }

    function getGst($gst, $po_id)
    {

        $sql = array();
        $location = \Session::get('location');
        $sql = \DB::SELECT("select * from p_po_invoice_lines_t where po_invoice_id='" . $po_id . "' and tax_group_id='" . $gst . "'");
        $hsn = array();
        $gst = array();
        $sub_total = 0;
        foreach ($sql as $key => $value) {
            //$hsn[]=$value->hsn_code;
            $dis_amt = ($value->unit_price) - ($value->unit_price * ($value->discount_percentage / 100));
            $amount = $dis_amt * $value->qty;
            $sub_total = $sub_total + $amount;

        }
        //$hsn_code=implode(' , ',$hsn);
        //$gst['hsn_code']=$hsn_code;
        //$gst['total']=$hsn_code;
        $gst['amount'] = $sub_total;
        return $gst;
    }



    function getCompany()
    {
        $sql = array();
        $company = \Session::get('companyid');
        $sql = \DB::SELECT("SELECT company_id,company_name,company_logo_name FROM `m_company_t` WHERE `company_id`=" . $company . "");
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }
    function getPaymentterms($id = null)
    {
        $sql = array();
        $sql = \DB::SELECT('select payment_term_id,payment_term_name from m_payment_terms_t where payment_term_id =' . $id . '');
        if (!empty($sql)) {
            return $sql[0]->payment_term_name;
        } else {
            return 0;
        }
    }
    function getFreightterms($id = null)
    {
        $sql = array();
        $sql = \DB::SELECT('select frieghtterm_id,fob_point_name from m_frieghtterms_t where frieghtterm_id =' . $id . '');
        if (!empty($sql)) {
            return $sql[0]->fob_point_name;
        } else {
            return 0;
        }
    }


    function getSupplier($id = null)
    {

        $sql = array();
        $sql = \DB::SELECT('select * from m_supplier_t where supplier_id=' . $id . '');
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }

    }

    function getSubcontractSupplier($id = null)
    {

        $sql = array();
        $sql = \DB::SELECT('select * from m_subcontract_supplier_t where subcontract_supplier_id=' . $id . '');
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }

    }

    function getSuppliersite($id = null)
    {

        $sql = array();
        $sql = \DB::SELECT('select * from m_supplier_sites_t where supplier_site_id=' . $id . '');
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }


    function getSubcontractSuppliersite($id = null)
    {

        $sql = array();
        $sql = \DB::SELECT('select * from m_subcontract_sites_t where subcontract_supplier_id=' . $id . '');
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }

    public function pofilesave(Request $request)
    {
        //       dd($_POST);
        if ($request->hasfile('email_attachment')) {
            File::deleteDirectory(public_path('uploads/purchaseorder/PO_' . $_POST['po_hdr_id']));

            foreach ($request->file('email_attachment') as $file) {
                $name = $file->getClientOriginalName();

                $file->move(public_path() . '/uploads/purchaseorder/PO_' . $_POST['po_hdr_id'] . '/', $name);
                $data[] = $name;
            }
            $attachfile_name = json_encode($data);
            \DB::update("update p_po_hdr_t set attachment_file='" . $attachfile_name . "' where po_hdr_id=" . $_POST['po_hdr_id']);
            return 1;
        } else {
            $var = File::deleteDirectory(public_path('uploads/purchaseorder/PO_' . $_POST['po_hdr_id']));

            \DB::update("update p_po_hdr_t set attachment_file='' where po_hdr_id=" . $_POST['po_hdr_id']);
            return 2;
        }

    }

    public function creditupdate($id = null)
    {
        $po_invoice_id = $id;
        $credit_taken = $_POST['credit_taken'];
        $credit_date = date('Y-m-d', strtotime($_POST['credit_date']));
        if ($po_invoice_id != "") {
            $po_id = explode(",", $po_invoice_id);

            \DB::table('p_po_invoice_hdr_t')->whereIn('po_invoice_id', $po_id)->update(['credit_taken' => $credit_taken, 'credit_date' => $credit_date]);
            return response()->json(array('status' => 'success', 'message' => 'Updated Successfully'));
        }
    }

    public function expensecreditupdate($id = null)
    {
        $expense_id = $id;
        $credit_taken = $_POST['credit_taken'];
        $credit_date = date('Y-m-d', strtotime($_POST['credit_date']));
        if ($expense_id != "") {
            $exp_id = explode(",", $expense_id);

            \DB::table('f_expenses_t')->whereIn('expense_id', $exp_id)->update(['credit_taken' => $credit_taken, 'credit_date' => $credit_date]);
            return response()->json(array('status' => 'success', 'message' => 'Updated Successfully'));
        }
    }

    public function reverseInvoice($id)
{
    DB::beginTransaction();

    try {

        $invoice = DB::table('p_po_invoice_hdr_t')
            ->select('po_invoice_id','bill_number','grn_number')
            ->where('po_invoice_id', $id)
            ->first();

        if (!$invoice) {
            return response()->json(['status' => false, 'message' => 'Invoice not found'], 404);
        }

        DB::table('p_po_invoice_hdr_t')
            ->where('po_invoice_id', $id)
            ->update([
                'po_invoice_status' => 'CANCELLED'
            ]);

        if (!empty($invoice->grn_number)) {

            DB::table('p_grn_hdr_t')
                ->where('grn_id', $invoice->grn_number)
                ->update([
                    'invoice_created' => 'No'
                ]);
        }
        
        $journal = DB::table('f_journal_entry_t')
            ->where('journal_reference', $id)
            ->where('journal_type', 'PO INVOICE')
            ->first();

        if (!$journal) {
            throw new \Exception('Journal entry not found for reversal');
        }

        $reverseJournalId = DB::table('f_journal_entry_t')->insertGetId([
            'journal_date'       => date('Y-m-d'),
            'journal_type'       => 'DEBIT NOTE',
            'journal_reference'  => $id,
            'journal_name'       => 'Reverse-POINVOICE-' . $invoice->bill_number,
            'journal_status'     => 'APPROVED',
            'created_by'         => \Session::get('id'),
            'created_at'         => date('Y-m-d H:i:s'),
            'updated_at'         => date('Y-m-d H:i:s'),
            'last_updated_by'    => \Session::get('id'),
            'location_id'        => \Session::get('location'),
            'organization_id'    => \Session::get('organization'),
            'company_id'         => \Session::get('companyid')
        ]);

        $lines = DB::table('f_journal_entry_lines_t')
            ->where('journal_entry_id', $journal->journal_entry_id)
            ->get();

        if ($lines->isEmpty()) {
            throw new \Exception('Journal lines not found');
        }

        foreach ($lines as $line) {

            DB::table('f_journal_entry_lines_t')->insert([
                'journal_entry_id' => $reverseJournalId,
                'journal_date'     => date('Y-m-d'),
                'reference_source' => $line->reference_source,
                'reference_id'     => $line->reference_id,
                'line_no'          => $line->line_no,
                'account_id'       => $line->account_id,
                'debit_amount'     => $line->credit_amount,  // swapped
                'credit_amount'    => $line->debit_amount,   // swapped
                'product_qty'      => $line->product_qty,
                'created_by'       => \Session::get('id'),
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
                'last_updated_by'  => \Session::get('id'),
                'location_id'      => \Session::get('location'),
                'organization_id'  => \Session::get('organization'),
                'company_id'       => \Session::get('companyid')
            ]);
        }



        DB::commit();

        return response()->json(['status' => true]);

    } catch (\Exception $e) {

        DB::rollback();

        \Log::error($e);

        return response()->json([
            'status' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}


}
