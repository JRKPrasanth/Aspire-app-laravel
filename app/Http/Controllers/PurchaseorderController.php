<?php
namespace App\Http\Controllers;
use App\Purchaseorder;
use App\Purchaseorderlines;
use App\Paymentterms;
use App\Deliveryterms;
use App\Freightterms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Validator, DB;
use File;
use Config;
use session;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;


class PurchaseorderController extends Controller
{
    public $module = "purchaseorder";

    public function __construct()
    {
        $this->data = array();
        $this->table = "p_po_hdr_t";
        $this->subtable = "p_po_lines_t";
        $this->pageModule = "   ";
        $this->model = new Purchaseorder;
        $this->submodel = new Purchaseorderlines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['nav_menuname'] = "Purchase Order";
        $this->data = array(
            'pageModule' => 'purchaseorder',
            'pageUrl' => url('purchaseorder'),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu'] = $this->indexs();
        if ($this->data['pageMethod'] == 'poapproval') {
            $this->data['status'] = "INITIATED";
            $this->data['pageMethod'] == 'poapproval';
        } else if ($this->data['pageMethod'] == "purchasecopypo") {
            $this->data['status'] = "APPROVED";
            $this->data['pageMethod'] == 'purchasecopypo';
        } else if ($this->data['pageMethod'] == "purchaseorder") {
            $this->data['status'] = "";
            $this->data['pageMethod'] == 'purchaseorder';
        } else if ($this->data['pageMethod'] == "poamendment") {
            $this->data['status'] = "APPROVED";
            $this->data['pageMethod'] == 'purchaseorder';
        } else {
            $this->data['status'] = "APPROVED";
        }
    }

    /* Purpose For :Index Function to Call Table Blade*/
    public function index(Request $request)
    {
        // restrict illegal menu entry purpose - VIGNESH M

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

        $this->data['opt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        $table = \DB::table('p_po_hdr_t')->get();
        $this->data['datas'] = $table;

        if (\Request::route()->getName() == "poapproval") {
            $this->data['id'] = $ids = 1;
        } else if (\Request::route()->getName() == "pocancellation") {
            $this->data['id'] = $ids = 3;
        } else if (\Request::route()->getName() == "purchasecopypo") {
            $this->data['id'] = $ids = 4;
        } else if (\Request::route()->getName() == "poamendment") {
            $this->data['id'] = $ids = 5;
        } else {
            $this->data['id'] = $ids = 2;
        }
        $this->data['pageMethod'] = \Request::route()->getName();

        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));

        return view('purchaseorder.table', $this->data);

    }

    //  purpose for Display Data in JQgrid function 

    public function getPurchaseorderData($id = null, $status = null)
    {


        $app_id = \Session::get('id');

        $wh = '';
        $op = "=";
        $val_status = "'APPROVED'";
        if ($_GET['page_status'] == 'purchasecopypo') {
            $wh .= " and (p_po_hdr_t.po_status='APPROVED' or p_po_hdr_t.po_status='INITIATED' or p_po_hdr_t.po_status='CLOSED' or p_po_hdr_t.po_status='COMPLETED')";
            $op = "IN";
            $val_status = "('INITIATED','APPROVED','CLOSED','COMPLETED')";
        }
        if ($_GET['page_status'] == 'pocancellation') {
            $wh .= " and (p_po_hdr_t.po_status='APPROVED')";
            $op = "=";
            $val_status = "'APPROVED'";
        }
        if ($_GET['page_status'] == 'poapproval') {
            $wh .= " and (p_po_hdr_t.po_status='INITIATED') and json_contains(p_po_hdr_t.approver_id,'" . $app_id . "')=1 ";
            $op = "=";
            $val_status = "'INITIATED'";
        }
        if ($_GET['page_status'] == 'poamendment') {
            $wh .= " and (p_po_hdr_t.po_type='STANDARD' and p_po_hdr_t.po_status='APPROVED')  ";
            $op = "=";
            $val_status = "'APPROVED'";
        }


        $loc = "1";
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');


        $wh .= $grid_data = $this->grid_statuscheck('p_po_hdr_t', 'po_date', 'po_status', $op, $val_status);


        $SQL = "SELECT * FROM (SELECT
                        p_po_hdr_t.po_hdr_id as po_hdr_id,
                        p_po_hdr_t.po_number as po_number,
                        p_po_hdr_t.po_date as po_date,
                        p_po_hdr_t.po_type as po_type,
                        round(p_po_hdr_t.po_grand_total,0) as po_grand_total,
                        p_po_hdr_t.po_tax_total as po_tax_total,
                        p_po_hdr_t.po_status as po_status,
                        tb_users.first_name,
                        CASE WHEN tb_users.first_name = tbb_users.first_name  AND p_po_hdr_t.po_status != 'APPROVED' AND p_po_hdr_t.po_status != 'CLOSED' THEN
                        'Yet to Approve'
               			ELSE
               			tbb_users.first_name
               			END as approvedby,
                        p_po_hdr_t.reference_number as reference_number,
                        p_po_hdr_t.supplier_id as sub_id,
                        sum(p_po_lines_t.qty) as qty,
                        IF(p_po_hdr_t.quality_status = 1,'QUALITY CHECKED','PENDING')
                        AS quality_status,
                        p_po_hdr_t.remarks as remarks,
                        m_supplier_t.supplier_name
                        FROM `p_po_hdr_t`
                        left join p_po_lines_t on p_po_hdr_t.po_hdr_id = p_po_lines_t.po_hdr_id
                        LEFT JOIN tb_users ON p_po_hdr_t.created_by = tb_users.id
                        LEFT JOIN tb_users as tbb_users ON p_po_hdr_t.last_updated_by = tbb_users.id
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_po_hdr_t.`supplier_id`) where 1=1 and  need_to_close=0 $wh group by p_po_lines_t.po_hdr_id order by p_po_hdr_t.po_hdr_id DESC)v1";


        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);

    }


    /* Purpose for create and update mode*/
    public function create($id = null, $potype = null, $ids = null)
    {

        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'purchaseorder')->get();
        $this->data['ids'] = $ids;
        $this->data['supnameopt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        $this->data['suptypeopt'] = $this->jqgridselect('m_suppliertypes_t', 'suppliertype_id', 'suppliertype_name');
        $this->data['country'] = $this->jqgridselect('m_countries_t', 'country_id', 'country_name');
        $this->data['state'] = $this->jqgridselect('m_states_t', 'state_id', 'state_name');
        $this->data['city'] = $this->jqgridselect('m_cities_t', 'city_id', 'city_name');
        $this->data['prdcatopt'] = $this->jqgridselect('m_product_category_t', 'product_category_id', 'category_name');
        $this->data['prdgrpopt'] = $this->jqgridselect('m_product_groups_t', 'product_group_id', 'group_name');
        $this->data['prdnameopt'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');
        $this->data['group'] = $this->jqgridselect('m_product_groups_t', 'product_group_id', 'group_name');
        $this->data['tax_group_id_pop'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
        $this->data['product_clone'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '', 'purchaseorder');
        $comp = \Session::get('companyid');
        $currentloc = \Session::get('location');

        $billto = \DB::select("select locationid,location_name from m_location_t left join m_company_line_t on (m_company_line_t.locationid=m_location_t.location_id) WHERE m_company_line_t.companyid=$comp");
        $loc = '';
        foreach ($billto as $key => $value) {
            $loc .= $value->locationid . ",";
        }
        $location = rtrim($loc, ",");
        $this->data['comp_location'] = $location;

        $this->data['bill_to_location_id'] = $this->jcustomselect('m_location_t', 'location_id', 'location_name', $currentloc, 'and location_id in ' . "(" . $location . ")");
        $this->data['ship_to_location_id'] = $this->jcustomselect('m_location_t', 'location_id', 'location_name', $currentloc, 'and location_id in ' . "(" . $location . ")");

        if (isset($_GET['status']) && $_GET['status'] != 'COPYPO' && $_GET['status'] != 'POAMENDMENT') {

            if ($_GET['status'] == "ENQUIRY") {
                $enquiry_data = \DB::table('p_enquiry_hdr_t')->where('enquiry_hdr_id', $id)->get();
                $enquiry_lines_data = \DB::table('p_enquiry_lines_t')->where('enquiry_hdr_id', $id)->get();
                $this->data['ids'] = 2;

                $this->data['row'] = (object) array();
                $this->data['row']->po_hdr_id = "";
                $this->data['row']->po_number = "";
                $this->data['row']->po_date = date('Y-m-d');
                $this->data['row']->po_type = $enquiry_data[0]->enquiry_type_id;
                $this->data['row']->delivery_date = date('Y-m-d');
                $this->data['row']->po_for_verdura = "";
                $this->data['row']->po_status = "";
                $this->data['row']->po_tax_total = "";
                $this->data['row']->po_grand_total = "";
                $this->data['row']->remarks = "";
                $this->data['row']->round_off = "";
                $this->data['row']->received_qty = "";
                $this->data['row']->tax_credit = "";
                $this->data['row']->reverse_charge = "";
                $this->data['row']->supplier_reference_no = "";
                $this->data['row']->reference_id = $id;
                $this->data['row']->productcheck = "0";
                $this->data['row']->reference_number = $enquiry_data[0]->enquiry_number;
                $this->data['row']->source = "ENQUIRY";
                $this->data['id'] = '';

                $result = $this->location_details($currentloc);
                $result1 = $this->location_details($currentloc);
                $this->data['ship_to_address'] = $result;
                $this->data['bill_to_address'] = $result1;

                $supplierdata = $this->supplierdata($enquiry_data[0]->supplier_id);
                $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', $enquiry_data[0]->supplier_id);
                $this->data['suppliersite_id'] = $this->jcustomselect('m_supplier_sites_t', 'supplier_site_id', 'supplier_site_number|supplier_site_name', $enquiry_data[0]->suppliersite_id, ' and supplier_id="' . $enquiry_data[0]->supplier_id . '"');
                $this->data['delivery_terms_id'] = $this->jCombo('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $supplierdata['delivery_terms_id'], ' and source_type_id="Purchase"');
                $this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', '', ' and source_type_id="Purchase"');
                $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $supplierdata['default_payment_terms_id']);
                $this->data['insurance_term_id'] = $this->jCombo('m_insurance_terms_t', 'insurance_term_id', 'insurance_term_name', $supplierdata['insurance_term_id']);
                $this->data['freight_terms_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', '', ' and source_type_id="Purchase"');
                $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $enquiry_data[0]->project_id);
                $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $enquiry_data[0]->organization_id);
                $this->data['currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', '37');
                $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
                $priceid = $supplierdata['default_pricelist_id'];
                $this->data['po_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $supplierdata['default_pricelist_id'], ' and price_list_type="Purchase"');
                $this->data['default_payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $supplierdata['default_payment_method_id']);
                $this->data['linedata'] = array();

                $this->data['productid'] = $this->getsupplierpriceproduct($enquiry_data[0]->supplier_id, '');
                $this->data['uomcodeid'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
                $this->data['partno'] = $this->jCombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '');
                $grandtotal = 0;
                $taxamount_total = 0;
                $key = -1;
                $pocount = 0;
                foreach ($enquiry_lines_data as $key1 => $value) {
                    if ($this->data['row']->po_type == "STANDARD") {
                        $tax = $this->gettax($value->product_id);
                        $unitprice = $this->pricelist($enquiry_data[0]->supplier_id, $value->product_id);
                        if ($unitprice != "0.00") {
                            $key++;
                            $this->data['linedata'][$key] = (object) array();

                            $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                            $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id, 'and uom_code_id=' . $value->uom_code_id);
                            $this->data['linedata'][$key]->part_no = $this->jcustomselectcomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and manufacturer_partno_id=' . $value->part_no);
                            $hsn = \DB::select("select hsn_code,product_id,defalut_hsn_code from m_products_t where product_id=" . $value->product_id);
                            $this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $hsn[0]->defalut_hsn_code, ' and gst_code_hdr_id in(' . $hsn[0]->hsn_code . ')');
                            $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $tax['taxgroup_id']);
                            $this->data['linedata'][$key]->qty = $value->qty;
                            $this->data['linedata'][$key]->qoh_qty = $this->getproductqoh($value->product_id);
                            $this->data['linedata'][$key]->unit_price = $unitprice;
                            $taxamount = (($value->qty * $unitprice) * $tax['display_name']) / 100;
                            $subtotal = ($value->qty * $unitprice);
                            $linetotal = ($taxamount + ($value->qty * $unitprice));
                            $taxamount_total += $taxamount;
                            $grandtotal = $grandtotal + $linetotal;
                            $this->data['linedata'][$key]->tax_amount = $taxamount;
                            $this->data['linedata'][$key]->sub_line_total = $subtotal;
                            $this->data['linedata'][$key]->line_total = $linetotal;
                            $this->data['linedata'][$key]->promised_date = $value->promised_date;
                            $this->data['linedata'][$key]->comments = $value->comments;
                        } else {
                            $pocount++;
                        }
                    } else {
                        $key++;
                        $this->data['linedata'][$key] = (object) array();
                        $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', $value->product_id, 'purchaseorder');
                        $this->data['linedata'][$key]->product_description = $value->product_description;
                        $this->data['linedata'][$key]->manufacturer_partno_id = '';
                        $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                        $this->data['linedata'][$key]->qty = $value->qty;
                        $unitprice = $this->pricelist($enquiry_data[0]->supplier_id, $value->product_id);
                        if ($unitprice != "0.00") {
                            $this->data['linedata'][$key]->unit_price = $unitprice;
                        } else {
                            $this->data['linedata'][$key]->unit_price = '';
                        }
                        $this->data['linedata'][$key]->tax_amount = '';
                        $this->data['linedata'][$key]->sub_line_total = '';
                        $this->data['linedata'][$key]->line_total = '';
                        $this->data['linedata'][$key]->promised_date = $value->promised_date;
                        $this->data['linedata'][$key]->comments = $value->comments;
                        $this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="SAC"');
                        $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
                    }
                }
                $this->data['pocount'] = $pocount;
            } else if ($_GET['status'] == 'REQUESTION') {
                $this->data['ids'] = 2;
                $requisition_data = \DB::table('p_requisition_hdr_t')->where('requisition_hdr_id', $id)->get();
                $requisition_lines_data = \DB::table('p_requisition_lines_t')->where('requisition_hdr_id', $id)->where('convertpoline_status', 0)->where('status', 0)->get();

                $this->data['row'] = (object) array();
                $this->data['row']->po_hdr_id = "";
                $this->data['row']->po_number = "";
                $this->data['row']->po_date = date('Y-m-d');
                $this->data['row']->po_type = "STANDARD";
                $this->data['row']->delivery_date = date('Y-m-d');
                $this->data['row']->po_for_verdura = "";
                $this->data['row']->po_status = "";
                $this->data['row']->po_tax_total = "";
                $this->data['row']->po_grand_total = "";
                $this->data['row']->remarks = "";
                $this->data['row']->round_off = "";
                $this->data['row']->received_qty = "";
                $this->data['row']->tax_credit = "";
                $this->data['row']->reverse_charge = "";
                $this->data['row']->productcheck = "0";
                $this->data['row']->reference_number = $requisition_data[0]->requisition_no;
                $this->data['row']->reference_id = $id;
                $this->data['row']->source = "REQUISITION";
                $this->data['id'] = '';
                $this->data['row']->supplier_reference_no = "";

                $result = $this->location_details($currentloc);
                $result1 = $this->location_details($currentloc);
                $this->data['ship_to_address'] = $result;
                $this->data['bill_to_address'] = $result1;

                $this->data['po_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', '', ' and price_list_type="Purchase"');
                $this->data['delivery_terms_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', '', ' and source_type_id="Purchase"');
                $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', '');
                $this->data['insurance_term_id'] = $this->jCombo('m_insurance_terms_t', 'insurance_term_id', 'insurance_term_name', '');
                $this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', '', ' and source_type_id="Purchase"');
                $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', '');
                $this->data['freight_terms_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', '', ' and source_type_id="Purchase"');
                $this->data['suppliersite_id'] = $this->jCombo('m_supplier_sites_t', 'supplier_site_id', 'supplier_site_number|supplier_site_name', ' ');
                $this->data['default_payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', ' ');
                $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $requisition_data[0]->project_id);
                $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $requisition_data[0]->organization_id);
                $this->data['currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', '37');
                $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
                $this->data['linedata'] = array();

                foreach ($requisition_lines_data as $key => $value) {
                    $this->data['linedata'][$key] = (object) array();
                    $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                    $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id, 'and uom_code_id=' . $value->uom_code_id);
                    if ($value->po_qty == 0) {
                        $qty = $value->qty;
                    } else {

                        $qty = ABS($value->balance_qty);
                    }
                    $this->data['linedata'][$key]->qty = $qty;
                    $this->data['linedata'][$key]->qoh_qty = $this->getproductqoh($value->product_id);
                    $hsn = \DB::select("select hsn_code,product_id,defalut_hsn_code from m_products_t where product_id=" . $value->product_id);
                    $this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $hsn[0]->defalut_hsn_code, 'and gst_code_hdr_id in(' . $hsn[0]->hsn_code . ')');
                    //                dd($this->data['linedata'][$key]->hsn_code);
                    $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
                    $this->data['linedata'][$key]->promised_date = $value->need_by_date;

                }


            } else if ($_GET['status'] == 'QUOTATION') {

                $this->data['ids'] = 2;
                $quote_data = \DB::table('p_quotation_hdr_t')->where('quotation_hdr_id', $id)->get();
                $quotation_lines_data = \DB::table('p_quotation_lines_t')->where('quotation_hdr_id', $id)->get();
                $this->data['row'] = (object) array();
                $this->data['row']->po_hdr_id = "";
                $this->data['row']->po_number = "";
                $this->data['row']->po_date = date('Y-m-d');
                $this->data['row']->po_type = $quote_data[0]->quotation_type;
                $this->data['row']->delivery_date = date('Y-m-d');
                $this->data['row']->po_for_verdura = "";
                $this->data['row']->po_status = "";
                $this->data['row']->po_tax_total = $quote_data[0]->quote_tax_total;
                $this->data['row']->po_grand_total = $quote_data[0]->quote_grand_total;
                $this->data['row']->transport_charges = $quote_data[0]->transport_charges;
                $this->data['row']->unloading_charges = $quote_data[0]->unloading_charges;
                $this->data['row']->insurance_charges = $quote_data[0]->insurance_charges;
                $this->data['row']->packing_charges = $quote_data[0]->packing_charges;
                $this->data['row']->other_frieght_amount = $quote_data[0]->other_frieght_amount;
                $this->data['row']->other_tax_amount = $quote_data[0]->other_tax_amount;
                $this->data['currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', '37');
                $this->data['row']->supplier_reference_no = $quote_data[0]->supplier_ref_no;
                $this->data['row']->remarks = $quote_data[0]->remarks;
                $this->data['row']->received_qty = "";
                $this->data['row']->tax_credit = "";
                $this->data['row']->reverse_charge = "";
                $this->data['row']->reference_number = $quote_data[0]->quotation_no;
                $this->data['row']->reference_id = $id;
                $this->data['row']->productcheck = "0";
                $this->data['row']->source = "QUOTATION";
                $this->data['row']->quo_id = $quote_data[0]->quotation_hdr_id;
                $this->data['row']->attachfile_name = $quote_data[0]->attachfile_name;

                $this->data['id'] = '';

                $result = $this->location_details($quote_data[0]->ship_to_location_id);
                $result1 = $this->location_details($quote_data[0]->bill_to_location_id);
                $this->data['ship_to_address'] = $result;
                $this->data['bill_to_address'] = $result1;


                $this->data['po_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $quote_data[0]->quote_pricelist_id, ' and price_list_type="Purchase"');
                $this->data['delivery_terms_id'] = $this->jCombo('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $quote_data[0]->delivery_terms_id, ' and source_type_id="Purchase"');
                $this->data['default_payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $quote_data[0]->default_payment_method_id);
                $this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $quote_data[0]->freight_carrier_id, ' and source_type_id="Purchase"');
                $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $quote_data[0]->payment_term_id);
                $this->data['insurance_term_id'] = $this->jCombo('m_insurance_terms_t', 'insurance_term_id', 'insurance_term_name', $quote_data[0]->insurance_term_id);
                $this->data['ship_to_location_id'] = $this->jcustomselect('m_location_t', 'location_id', 'location_name', $quote_data[0]->ship_to_location_id, 'and location_id in ' . "(" . $location . ")");
                $this->data['bill_to_location_id'] = $this->jcustomselect('m_location_t', 'location_id', 'location_name', $quote_data[0]->bill_to_location_id, 'and location_id in ' . "(" . $location . ")");
                $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', $quote_data[0]->supplier_id);
                $this->data['freight_terms_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', '', ' and source_type_id="Purchase"');
                $this->data['suppliersite_id'] = $this->jCombo('m_supplier_sites_t', 'supplier_site_id', 'supplier_site_number|supplier_site_name', $quote_data[0]->supplier_site_id);
                $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $quote_data[0]->project_id);

                $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $quote_data[0]->organization_id);
                $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
                $this->data['productid'] = $this->getproductpricelist($quote_data[0]->quote_pricelist_id, '');
                $this->data['uomcodeid'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
                $this->data['partno'] = $this->jCombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '');

                $this->data['linedata'] = array();
                foreach ($quotation_lines_data as $key => $value) {
                    $this->data['linedata'][$key] = (object) array();
                    if ($quote_data[0]->quotation_type == "STANDARD") {
                        $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                    } else {
                        $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', $value->product_id, 'purchaseorder');
                    }
                    $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id, 'and uom_code_id=' . $value->uom_code_id);
                    $this->data['linedata'][$key]->part_no = $this->jcustomselectcomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->manufacturer_partno_id, 'and manufacturer_partno_id=' . $value->manufacturer_partno_id);
                    $this->data['linedata'][$key]->product_description = $value->product_description;
                    $this->data['linedata'][$key]->unit_price = $value->unit_price;
                    $this->data['linedata'][$key]->qty = $value->qty;
                    $this->data['linedata'][$key]->qoh_qty = $this->getproductqoh($value->product_id);
                    $this->data['linedata'][$key]->discount_percentage = $value->discount_percentage;
                    $this->data['linedata'][$key]->discount_amount = $value->discount_amount;
                    if ($quote_data[0]->quotation_type == "STANDARD") {
                        $this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->hsn_code, 'and classification_name="HSN"');
                    } else {
                        $this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->hsn_code, 'and classification_name="SAC"');
                    }
                    $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);

                    $this->data['linedata'][$key]->tax_amount = $value->tax_amount;
                    $this->data['linedata'][$key]->sub_line_total = $value->line_sub_total;
                    $this->data['linedata'][$key]->line_total = $value->line_total;
                    $this->data['linedata'][$key]->promised_date = $value->promised_date;
                    $this->data['linedata'][$key]->promised_alternate_date = $value->promised_date;
                    $this->data['linedata'][$key]->comments = $value->comments;
                }

            }


        }
        /*Create PO*/ elseif ($id == "0") {

            $this->data['row'] = (object) array();

            $this->data['row']->po_hdr_id = "";
            $this->data['row']->po_number = "";
            $this->data['row']->po_date = date('Y-m-d');
            $this->data['row']->po_type = "";
            $this->data['row']->source = "STANDARD";
            $this->data['row']->delivery_date = date('Y-m-d');
            $this->data['row']->po_for_verdura = "";
            $this->data['row']->po_status = "";
            $this->data['row']->po_tax_total = "";
            $this->data['row']->po_grand_total = "";
            $this->data['row']->remarks = "";

            $this->data['row']->round_off = "";

            $this->data['row']->received_qty = "";
            $this->data['row']->tax_credit = "";
            $this->data['row']->reverse_charge = "";
            $this->data['row']->supplier_reference_no = "";
            $this->data['row']->productcheck = "1";
            $this->data['id'] = '';
            if ($potype != "LABOUR") {
                $potype = "STANDARD";
            } else {
                $potype = $potype;
            }

            $this->data['row']->po_type = $potype;

            $result = $this->location_details($currentloc);
            $result1 = $this->location_details($currentloc);
            $this->data['ship_to_address'] = $result;
            $this->data['bill_to_address'] = $result1;

            $this->data['linedata'] = array();
            $this->data['po_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', '', ' and price_list_type="Purchase"');
            $this->data['delivery_terms_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', '', ' and source_type_id="Purchase"');
            $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', '');
            $this->data['insurance_term_id'] = $this->jCombo('m_insurance_terms_t', 'insurance_term_id', 'insurance_term_name', '');
            $this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', '', ' and source_type_id="Purchase"');
            $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', '');
            $this->data['freight_terms_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', '', ' and source_type_id="Purchase"');
            $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', '');
            $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', '');
            $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
            $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'purchaseorder');
            $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
            $this->data['tax_group_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
            if ($potype != "LABOUR") {
                $this->data['hsn_code'] = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="HSN"');
            } else {
                $this->data['hsn_code'] = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="SAC"');
            }

            $this->data['supnameopt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
            $this->data['suptypeopt'] = $this->jqgridselect('m_suppliertypes_t', 'suppliertype_id', 'suppliertype_name');
            $this->data['country'] = $this->jqgridselect('m_countries_t', 'country_id', 'country_name');
            $this->data['state'] = $this->jqgridselect('m_states_t', 'state_id', 'state_name');
            $this->data['city'] = $this->jqgridselect('m_cities_t', 'city_id', 'city_name');
            $this->data['default_payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', '');
            $this->data['prdcatopt'] = $this->jqgridselect('m_product_category_t', 'product_category_id', 'category_name');
            $this->data['prdnameopt'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');
            $this->data['part_no'] = $this->jCombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '');
            $this->data['currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', '37');

        } else {

            $this->data['id'] = $id;
            $table = \DB::table('p_po_hdr_t')->where('po_hdr_id', $id)->get();
            $this->data['pageMethod'] = \Request::route()->getName();
            $this->data['row'] = $table[0];
            $this->data['row']->productcheck = "0";
            $tablelines = \DB::table('p_po_lines_t')->where('po_hdr_id', $id)->get();
            $this->data['linedata'] = $tablelines;
            if (isset($_GET['status'])) {
                if ($_GET['status'] == 'COPYPO') {
                    $this->data['row']->po_date = date('Y-m-d');
                }
            }
            $this->data['row']->supplier_reference_no = $table[0]->supplier_reference_no;
            $this->data['row']->po_for_verdura = $table[0]->po_for_verdura;
            $this->data['currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $table[0]->currency);
            $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', $table[0]->supplier_id);
            $this->data['freight_terms_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', $table[0]->freight_terms_id, ' and source_type_id="Purchase"');
            $this->data['ship_to_location_id'] = $this->jcustomselect('m_location_t', 'location_id', 'location_name', $table[0]->ship_to_location_id, 'and location_id in ' . "(" . $location . ")");
            $this->data['bill_to_location_id'] = $this->jcustomselect('m_location_t', 'location_id', 'location_name', $table[0]->bill_to_location_id, 'and location_id in ' . "(" . $location . ")");
            $result = $this->location_details($table[0]->ship_to_location_id);
            $result1 = $this->location_details($table[0]->bill_to_location_id);
            $this->data['ship_to_address'] = $result;
            $this->data['bill_to_address'] = $result1;
            $this->data['suppliersite_id'] = $this->jcustomselect('m_supplier_sites_t', 'supplier_site_id', 'supplier_site_number|supplier_site_name', $table[0]->suppliersite_id, ' and supplier_id="' . $table[0]->supplier_id . '"');
            $this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $table[0]->freight_carrier_id, ' and source_type_id="Purchase"');
            $this->data['default_payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $table[0]->default_payment_method_id);
            $this->data['delivery_terms_id'] = $this->jCombo('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $table[0]->delivery_terms_id, ' and source_type_id="Purchase"');
            $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $table[0]->payment_term_id);
            $this->data['insurance_term_id'] = $this->jCombo('m_insurance_terms_t', 'insurance_term_id', 'insurance_term_name', $table[0]->insurance_term_id);
            $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);
            $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', '', $table[0]->created_by);
            $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
            $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $table[0]->project_id);
            $this->data['po_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $table[0]->po_pricelist_id, ' and price_list_type="Purchase"');
            $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'purchaseorder');
            $this->data['part_no'] = $this->jCombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '');
            $this->data['tax_group_id'] = '';
            $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
            $this->data['supnameopt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
            $this->data['suptypeopt'] = $this->jqgridselect('m_suppliertypes_t', 'suppliertype_id', 'suppliertype_name');
            $this->data['country'] = $this->jqgridselect('m_countries_t', 'country_id', 'country_name');
            $this->data['state'] = $this->jqgridselect('m_states_t', 'state_id', 'state_name');
            $this->data['city'] = $this->jqgridselect('m_cities_t', 'city_id', 'city_name');
            $this->data['prdcatopt'] = $this->jqgridselect('m_product_category_t', 'product_category_id', 'category_name');
            $this->data['prdnameopt'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');
            $acptqty = \DB::select("select
                        sum(pplt.accept_qty) as sum
                        from p_po_invoice_hdr_t pit
                        left join p_po_invoice_lines_t pplt on pplt.po_invoice_id = pit.po_invoice_id where pit.po_number=$id group by pplt.product_id ");
            $this->data['productid'] = $this->getproductpricelist($table[0]->po_pricelist_id, '');
            $this->data['uomcodeid'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
            $this->data['partno'] = $this->jCombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '');

            if (count($this->data['linedata']) >= 1) {
                //  dd($this->data['linedata']);
                foreach ($this->data['linedata'] as $key => $value) {
                    if ($table[0]->po_type != "LABOUR") {
                        $hsn = \DB::select("select hsn_code,product_id,defalut_hsn_code from m_products_t where product_id=" . $value->product_id);

                        if ($hsn != null) {

                            $this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->hsn_code, '');
                            // $this->data['linedata'][$key]->hsn_code =  $this->jcustomselect('f_gst_code_hdr_t','gst_code_hdr_id','classification_code',$value->hsn_code,' and gst_code_hdr_id in('.$hsn[0]->hsn_code.')');

                        }
                    } else {
                        $this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->hsn_code, '');

                    }
                    if ($table[0]->po_type != "LABOUR") {
                        if ($value->product_id != '0') {
                            $this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                        } elseif ($table[0]->supplier_id != "0") {
                            $this->data['linedata'][$key]->product_id = $this->getproductpricelist($table[0]->po_pricelist_id, '');
                        } else {
                            $this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', '');
                        }
                    } else {
                        $this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, '');
                    }

                    if ($value->uom_code_id != "") {
                        $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id, 'and uom_code_id=' . $value->uom_code_id);
                    } else {
                        $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id, '');
                    }
                    $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
                    if ($value->part_no != '0') {
                        $this->data['linedata'][$key]->part_no = $this->jcustomselectcomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and manufacturer_partno_id=' . $value->part_no);
                    } else {
                        $this->data['linedata'][$key]->part_no = $this->jcustomselectcomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '', '');
                    }
                    if ($id == "3") {
                        if (empty($acptqty)) {
                            $this->data['linedata'][$key]->received_qty = "";
                        } else {
                            $this->data['linedata'][$key]->received_qty = $acptqty[$key]->sum;
                        }
                    }
                    if ($this->data['linedata'][$key]->qty == 0) {
                        $this->data['linedata'][$key]->qty = "";
                    }
                    if (isset($_GET['status']) && $_GET['status'] == 'COPYPO') {

                        $this->data['linedata'][$key]->po_line_id = "";
                        $this->data['linedata'][$key]->po_hdr_id = "";
                    }
                    if (isset($_GET['status']) && $_GET['status'] == 'POAMENDMENT') {

                        $this->data['linedata'][$key]->po_line_id = "";
                        $this->data['linedata'][$key]->po_hdr_id = "";
                    }
                }
            }

        }
        if (isset($_GET['status']) && $_GET['status'] != 'COPYPO' && $_GET['status'] != 'POAMENDMENT') {
            $this->data['return_url'] = \Request::route()->getName();
        }
        if ($ids == 1) {
            $this->data['return_url'] = "poapproval";
        } else if ($ids == 3) {
            $this->data['return_url'] = "pocancellation";
        } else if ($ids == 4) {
            $this->data['return_url'] = "purchasecopypo";
        } else if ($ids == 5) {
            $this->data['return_url'] = "poamendment";
        } else if (isset($_GET['status']) && $_GET['status'] == "ENQUIRY") {
            $this->data['return_url'] = "purchaseenquirytopo";
        } else if (isset($_GET['status']) && $_GET['status'] == "QUOTATION") {
            //                    dd('sad');
            $this->data['return_url'] = "purchasequtoetopo";
        } elseif (isset($_GET['status']) && $_GET['status'] == "REQUESTION") {
            $this->data['return_url'] = "purchaserequestiontopo";
        } else {
            $this->data['return_url'] = "purchaseorder";
        }
        /*KARTHIGAA Purpose For COPY PO*/
        if (isset($_GET['status']) && $_GET['status'] == 'COPYPO') {

            $this->data['ids'] = 4;
            $this->data['row']->po_hdr_id = '';
            $this->data['copy_po_number'] = $this->data['row']->po_number;
            $this->data['row']->po_number = '';
            $this->data['return_url'] = "purchasecopypo";

        }
        if (isset($_GET['status']) && $_GET['status'] == 'POAMENDMENT') {

            $this->data['ids'] = 5;
            $this->data['copy_po_number'] = $this->data['row']->po_number;
            $this->data['row']->amendment_status = 1;
            $this->data['row']->reference_id = $this->data['row']->po_hdr_id;
            $this->data['row']->reference_number = $this->data['row']->po_number;
            $this->data['row']->po_hdr_id = '';
            $this->data['row']->po_number = '';
            $this->data['row']->source = "PO";

            $this->data['return_url'] = "poamendment";

        }

        return view('purchaseorder.form', $this->data);
    }


    /* purpose for Save function*/
    public function save(Request $request)
    {
       // dd($request->all());
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
            'po_date_hidden',
            'bill_to_address',
            'ship_to_address',
        ]);
        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');
        $lines_data = $this->validatePost($form, $this->subtable, 'lines');
        /* Purpose for Value Based Approval*/
        $approverid = $this->Approvaldatacheck('poorder', $data['po_grand_total']);
        if ($approverid == "0") {
            $data['approver_id'] = \Session::get('id');
            if ($_POST['po_status'] != "DRAFT") {
                $data['po_status'] = "APPROVED";
            }
        } else {
            $data['approver_id'] = $approverid;
        }

        unset($lines_data['reverse_charge']);
        for ($i = 0; $i < count($_POST['bulk_product_id']); $i++) {
            $lines_data['pending_qty'][$i] = $_POST['bulk_qty'][$i];
            if ($_POST['bulk_promised_alternate_date'][$i] == "")
                $lines_data['promised_alternate_date'][$i] = $_POST['bulk_promised_date'][$i];
        }
        /* Purpose For Update Partially Convert Requisition to Po */
        $prdid = $_POST['bulk_product_id'];
        $product = '';
        foreach ($prdid as $k => $v) {
            $product .= $v . ",";
        }
        $prd = rtrim($product, ",");
        $ref_id = $_POST['reference_id'];
        if ($_POST['source'] == "REQUISITION") {
            \DB::update("update p_requisition_lines_t set convertpoline_status='1' where requisition_hdr_id='" . $ref_id . "' and product_id in ($prd)");
            $reqlinesupdate = \DB::select("select * from p_requisition_lines_t where convertpoline_status='1' and requisition_hdr_id='" . $ref_id . "' ");
            $reqlinesupdate_count = count($reqlinesupdate);
            $reqlines = \DB::select("select * from p_requisition_lines_t where requisition_hdr_id='" . $ref_id . "' ");
            $reqlines_count = count($reqlines);

            if ($reqlines_count == $reqlinesupdate_count) {
                \DB::table('p_requisition_hdr_t')->where("requisition_hdr_id", $ref_id)->update(['convertpo_status' => '1']);
            } else {
                \DB::table('p_requisition_hdr_t')->where("requisition_hdr_id", $ref_id)->update(['convertpo_status' => '0']);
            }
        }

        $seqmon = date('m');
        if ($seqmon > 3) {
            $seqyear = date('Y');
            $nxt = date('y', strtotime('+1 year'));
        } else {
            $seqyear = date('Y', strtotime('-1 year'));
            $nxt = date('y');
        }


        if ($_POST['po_number'] == "") {
            if ($_POST['amendment_status'] == "1") {
                $apo_id = $_POST['reference_id'];
                $po_tbl = \DB::SELECT("select * From p_po_hdr_t where po_hdr_id='" . $apo_id . "' ");

                $seqno1 = $this->Seqnoe('APO-', 'p_po_hdr_t', '', 'po_count');
                $sqlqq = (($seqno1[1]) - (1));
                $data['po_number'] = 'APO-' . $po_tbl[0]->po_number;
                $data['po_count'] = $sqlqq;
                $data['amendment_status'] = "";
                \DB::update("update p_po_hdr_t set amendment='Yes',po_status='CLOSED',amendment_status='1' where po_hdr_id='" . $apo_id . "'");
            } else {
                //$seqno=$this->Seqnoe('PO-','p_po_hdr_t',$_POST['po_type'],'po_count');
                $seqno = $this->Seqnoe('', 'p_po_hdr_t', '', 'po_count');
                $data['po_number'] = $seqyear . "-" . $nxt . "/" . $seqno[0];
                $data['po_count'] = $seqno[1];
            }

        } else {
            $seqno[0] = $_POST['po_number'];
        }


        \DB::beginTransaction();
        try {

            $id = $this->model->insertRow($data);

            $po_id = $id;
            $po_hdr_id = $request->input('po_hdr_id');

            if ($po_hdr_id == '') {

                if ($request->hasfile('choosefile')) {
                    foreach ($request->file('choosefile') as $file) {
                        $name = $file->getClientOriginalName();
                        $file->move(public_path() . '/Uploads/purchaseorder/PO' . $po_id . '/', $name);
                        $dataupload[] = $name;
                    }
                }
                $attachfile_name = json_encode($dataupload);
                \DB::update("update p_po_hdr_t set attachfile_name='" . $attachfile_name . "' where po_hdr_id='$po_id'");
                $this->data['notymsg'] = "yes";

            } else {

                // Normalize inputs
                $existing_file = $request->input('existing_file', '');
                $existing_file = array_filter(explode(',', $existing_file));

                $choose_file = $request->file('choosefile');
                $choose_file = is_array($choose_file) ? $choose_file : [];

                // Get DB files safely
                $get_attach = DB::table('p_po_hdr_t')->where('po_hdr_id', $po_id)->first();
                $db_files = [];

                if (!empty($get_attach->attachfile_name)) {
                    $db_files = json_decode($get_attach->attachfile_name, true);
                }
                $db_files = is_array($db_files) ? $db_files : [];

                /**
                 * CASE 1: No new upload, only delete
                 */
                if (count($choose_file) == 0 && count($existing_file) >= 0) {

                    $deleted_files = array_diff($db_files, $existing_file);

                    foreach ($deleted_files as $file) {
                        $path = public_path("Uploads/purchaseorder/PO{$po_id}/{$file}");
                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }

                    DB::update(
                        "update p_po_hdr_t set attachfile_name=? where po_hdr_id=?",
                        [json_encode($existing_file), $po_id]
                    );
                }

                /**
                 * CASE 2: New upload + existing files
                 */
                if (count($choose_file) > 0) {

                    $new_files = [];

                    foreach ($choose_file as $file) {
                        $name = $file->getClientOriginalName();
                        $file->move(public_path("Uploads/purchaseorder/PO{$po_id}/"), $name);
                        $new_files[] = $name;
                    }

                    $final_files = array_merge($existing_file, $new_files);

                    DB::update(
                        "update p_po_hdr_t set attachfile_name=? where po_hdr_id=?",
                        [json_encode($final_files), $po_id]
                    );
                }

            }

            if (isset($_POST['reverse_charge']) && $_POST['reverse_charge'] != null && $_POST['reverse_charge'] != '') {
                $reverse_charge = $_POST['reverse_charge'];
                $charge = implode(",", $reverse_charge);
                \DB::update("update  p_po_hdr_t set reverse_charge='" . $charge . "' where po_hdr_id='" . $id . "'");
            }

            if ($_POST['po_status'] == "INITIATED") {
                \DB::table('notifications_t')->where('reference_source_id', $_POST['po_hdr_id'])->where('reference_source', 'PO APPROVAL')->update(['read/unread' => 'read']);
                //    $notifcation = 'Purchase Order '.$seqno[0].' Initiated';
                // $send_notification = $this->sendPopUpNotification($id,"PO APPROVAL",$notifcation,'poapproval');

                $dat = \DB::select("SELECT * FROM p_po_hdr_t LEFT JOIN m_supplier_t ON p_po_hdr_t.supplier_id = m_supplier_t.supplier_id LEFT JOIN tb_users ON p_po_hdr_t.created_by = tb_users.id WHERE p_po_hdr_t.po_hdr_id='" . $id . "'");

                $pur['po_number'] = $dat[0]->po_number;
                $pur['po_date'] = $dat[0]->po_date;
                $pur['po_type'] = $dat[0]->po_type;
                $pur['supplier_name'] = $dat[0]->supplier_name;
                $pur['po_tax_total'] = $dat[0]->po_tax_total;
                $pur['po_grand_total'] = $dat[0]->po_grand_total;
                $pur['user_clear'] = $dat[0]->first_name;
                $pur['po_status'] = $dat[0]->po_status;

                $po_num_sub = $dat[0]->po_number;
                Session::put('po_num_sub', $po_num_sub);
                $po_email = $dat[0]->email;
                Session::put('po_email', $po_email);
                if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                    Config::set('mail.username', \Session::get('user_email'));
                    Config::set('mail.password', \Session::get('user_password'));
                    //dd('user_email');
                }

                if (\Session::get('user_email') != '') {

                    $to_mail_id = "neelavathy_b@jrkresearch.com";

                    \Mail::send('purchaseorder.mail', $pur, function ($message) use ($to_mail_id) {

                        $message->to($to_mail_id);
                        $message->to('poornima_s@jrkresearch.com');
                        $message->to('expenses@jrkresearch.com');

                        if (!empty(Session::get('po_email'))) {
                            $po_email = Session::get('po_email');
                        } else {
                            $po_email = \Session::get('user_email');
                        }
                        $message->from($po_email);
                        if (!empty(Session::get('po_num_sub'))) {
                            $po_num_sub = Session::get('po_num_sub');
                        } else {
                            $po_num_sub = " ";
                        }

                        $message->subject($po_num_sub . " - PO INITIATED");
                    });
                }


            } else if ($_POST['po_status'] == "REJECTED") {
                $da = \DB::select("select * from p_po_hdr_t where po_hdr_id='" . $id . "'");
                $notifcation = 'Purchase Order ' . $seqno[0] . ' Rejected';
                //$send_notification = $this->sendPopUpHomeNoty($id,"PO REJECTED",$notifcation,'purchaseorder',$da[0]->created_by);

                $dat = \DB::select("SELECT * FROM p_po_hdr_t LEFT JOIN m_supplier_t ON p_po_hdr_t.supplier_id = m_supplier_t.supplier_id LEFT JOIN tb_users ON p_po_hdr_t.last_updated_by = tb_users.id WHERE p_po_hdr_t.po_hdr_id='" . $id . "'");

                $to_mail = \DB::select("SELECT * FROM p_po_hdr_t LEFT JOIN tb_users ON p_po_hdr_t.created_by = tb_users.id WHERE p_po_hdr_t.po_hdr_id='" . $id . "'");


                $pur['po_number'] = $dat[0]->po_number;
                $pur['po_date'] = $dat[0]->po_date;
                $pur['po_type'] = $dat[0]->po_type;
                $pur['supplier_name'] = $dat[0]->supplier_name;
                $pur['po_tax_total'] = $dat[0]->po_tax_total;
                $pur['po_grand_total'] = $dat[0]->po_grand_total;
                $pur['user_clear'] = $dat[0]->first_name;
                $pur['po_status'] = $dat[0]->po_status;

                $po_num_sub = $dat[0]->po_number;
                Session::put('po_num_sub', $po_num_sub);
                $po_email = $dat[0]->email;
                Session::put('po_email', $po_email);
                if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                    Config::set('mail.username', \Session::get('user_email'));
                    Config::set('mail.password', \Session::get('user_password'));

                }

                if (\Session::get('user_email') != '') {
                    $to_mail_id = $to_mail[0]->email;

                    if (isset($to_mail_id)) {
                        $to_mail_id = $to_mail[0]->email;
                    } else {
                        $to_mail_id = "aspire@jrkresearch.com";
                    }

                    \Mail::send('purchaseorder.mail', $pur, function ($message) use ($to_mail_id) {

                        $message->to($to_mail_id);

                        if (!empty(Session::get('po_email'))) {
                            $po_email = Session::get('po_email');
                        } else {
                            $po_email = \Session::get('user_email');
                        }
                        $message->from($po_email);
                        if (!empty(Session::get('po_num_sub'))) {
                            $po_num_sub = Session::get('po_num_sub');
                        } else {
                            $po_num_sub = " ";
                        }

                        $message->subject($po_num_sub . " - PO REJECTED");
                    });
                }


            } else if ($_POST['po_status'] == "APPROVED") {
                $da = \DB::select("select * from p_po_hdr_t where po_hdr_id='" . $id . "'");
                \DB::table('notifications_t')->where('reference_source_id', $_POST['po_hdr_id'])->where('reference_source', 'PO APPROVAL')->update(['read/unread' => 'read']);
                //    $notifcation = 'Purchase Order '.$seqno[0].' Approved';
                //   $send_notification = $this->sendPopUpHomeNoty($id,"PO APPROVED",$notifcation,'purchaseorder',$da[0]->created_by);

                $dat = \DB::select("SELECT * FROM p_po_hdr_t LEFT JOIN m_supplier_t ON p_po_hdr_t.supplier_id = m_supplier_t.supplier_id LEFT JOIN tb_users ON p_po_hdr_t.last_updated_by = tb_users.id WHERE p_po_hdr_t.po_hdr_id='" . $id . "'");

                $to_mail = \DB::select("SELECT * FROM p_po_hdr_t LEFT JOIN tb_users ON p_po_hdr_t.created_by = tb_users.id WHERE p_po_hdr_t.po_hdr_id='" . $id . "'");

                $pur['po_number'] = $dat[0]->po_number;
                $pur['po_date'] = $dat[0]->po_date;
                $pur['po_type'] = $dat[0]->po_type;
                $pur['supplier_name'] = $dat[0]->supplier_name;
                $pur['po_tax_total'] = $dat[0]->po_tax_total;
                $pur['po_grand_total'] = $dat[0]->po_grand_total;
                $pur['user_clear'] = $dat[0]->first_name;
                $pur['po_status'] = $dat[0]->po_status;

                $po_num_sub = $dat[0]->po_number;
                Session::put('po_num_sub', $po_num_sub);
                $po_email = $dat[0]->email;
                Session::put('po_email', $po_email);
                if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                    Config::set('mail.username', \Session::get('user_email'));
                    Config::set('mail.password', \Session::get('user_password'));

                }

                if (\Session::get('user_email') != '') {

                    $to_mail_id = $to_mail[0]->email;

                    if (isset($to_mail_id)) {
                        $to_mail_id = $to_mail[0]->email;
                    } else {
                        $to_mail_id = "aspire@jrkresearch.com";
                    }

                    \Mail::send('purchaseorder.mail', $pur, function ($message) use ($to_mail_id) {

                        $message->to($to_mail_id);


                        if (!empty(Session::get('po_email'))) {
                            $po_email = Session::get('po_email');
                        } else {
                            $po_email = \Session::get('user_email');
                        }
                        $message->from($po_email);
                        if (!empty(Session::get('po_num_sub'))) {
                            $po_num_sub = Session::get('po_num_sub');
                        } else {
                            $po_num_sub = " ";
                        }

                        $message->subject($po_num_sub . " - PO APPROVED");
                    });
                }


            }

            $lid = $this->submodel->subgridSave($lines_data, $id);
            \DB::commit();

            if ($_POST['po_hdr_id'] == "") {
                $action = "create";
            } else {
                $action = "edit";
            }
            $this->auditlog($id, "purchaseorder", $action, $_POST, "p_po_hdr_t");
            return response()->json(array('status' => 'success', 'message' => 'Purchase Order Saved', 'id' => $id, 'lid' => $lid));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            dd($dbCode);
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }


    /* purpose for Display hdr & Lines View function*/
    public function show(Request $request, $id = null, $mesg = null)
    {

        $returnUrl = $request->query('return', 'purchaseorder');
        $this->data['pageModule'] = url($returnUrl);

        if (isset($id)) {
            $vdata = \DB::table('p_po_hdr_t')->select(
                'p_po_hdr_t.*',
                'm_supplier_t.supplier_name',
                'm_supplier_sites_t.supplier_site_name',
                'm_countries_t.country_name',
                'm_states_t.state_name',
                'm_cities_t.city_name',
                'm_supplier_sites_t.address as siteaddress',
                'm_organizations_t.organization_name',
                'i_pricelist_hdr_t.pricelist_name',
                'm_projects_t.project_name',
                'm_frieghtcarriers_hdr_t.carrier_name',
                'm_payment_methods_t.payment_method_name',
                'm_payment_terms_t.payment_term_name',
                'm_frieghtterms_t.fob_point_name',
                'm_delivery_terms_t.delivery_term_name',
                'm_insurance_terms_t.insurance_term_name',
                'bill.location_name as bill_location',
                'm_location_t.location_name',
                'tb_users.username',
                'tb_users.first_name',
                'app_user.first_name as approver'
            )
                ->leftjoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_po_hdr_t.supplier_id')
                ->leftjoin('m_supplier_sites_t', 'm_supplier_sites_t.supplier_site_id', '=', 'p_po_hdr_t.suppliersite_id')
                ->leftjoin('i_pricelist_hdr_t', 'i_pricelist_hdr_t.pricelist_hdr_id', '=', 'p_po_hdr_t.po_pricelist_id')
                ->leftjoin('m_organizations_t', 'm_organizations_t.organization_id', '=', 'p_po_hdr_t.organization_id')
                ->leftjoin('m_projects_t', 'm_projects_t.project_id', '=', 'p_po_hdr_t.project_id')
                ->leftjoin('m_payment_terms_t', 'm_payment_terms_t.payment_term_id', '=', 'p_po_hdr_t.payment_term_id')
                ->leftjoin('m_delivery_terms_t', 'm_delivery_terms_t.delivery_terms_id', '=', 'p_po_hdr_t.delivery_terms_id')
                ->leftjoin('m_frieghtcarriers_hdr_t', 'm_frieghtcarriers_hdr_t.ar_frieghtcarriers_hdr_id', '=', 'p_po_hdr_t.freight_carrier_id')
                ->leftjoin('m_frieghtterms_t', 'm_frieghtterms_t.frieghtterm_id', '=', 'p_po_hdr_t.freight_terms_id')
                ->leftjoin('m_payment_methods_t', 'm_payment_methods_t.payment_method_id', '=', 'p_po_hdr_t.default_payment_method_id')
                ->leftjoin('m_insurance_terms_t', 'm_insurance_terms_t.insurance_term_id', '=', 'p_po_hdr_t.insurance_term_id')
                ->leftjoin('tb_users', 'tb_users.id', '=', 'p_po_hdr_t.created_by')
                ->leftjoin('tb_users as app_user', 'app_user.id', '=', 'p_po_hdr_t.last_updated_by')
                ->leftjoin('m_location_t as bill', 'bill.location_id', '=', 'p_po_hdr_t.bill_to_location_id')
                ->leftjoin('m_location_t', 'm_location_t.location_id', '=', 'p_po_hdr_t.ship_to_location_id')
                ->leftjoin('f_account_currency_t', 'f_account_currency_t.account_currency_id', '=', 'p_po_hdr_t.currency')
                ->leftjoin('m_countries_t', 'm_countries_t.country_id', '=', 'm_supplier_sites_t.country')
                ->leftjoin('m_states_t', 'm_states_t.state_id', '=', 'm_supplier_sites_t.state')
                ->leftjoin('m_cities_t', 'm_cities_t.city_id', '=', 'm_supplier_sites_t.city')
                ->where('po_hdr_id', $id)->get();



            $this->data['po_number'] = $vdata[0]->po_number;
            $this->data['other_frieght_amount'] = $vdata[0]->other_frieght_amount;
            $this->data['other_tax_amount'] = $vdata[0]->other_tax_amount;
            $podate = $vdata[0]->po_date;
            $this->data['po_date'] = date(\Session::get('p_date_format'), strtotime($podate));
            $this->data['po_type'] = $vdata[0]->po_type;
            $this->data['po_status'] = $vdata[0]->po_status;
            $this->data['payment_term_name'] = $vdata[0]->payment_term_name;
            $delivery_date = $vdata[0]->delivery_date;
            $this->data['delivery_date'] = date(\Session::get('p_date_format'), strtotime($delivery_date));
            $this->data['payment_term_name'] = $vdata[0]->payment_term_name;
            $this->data['payment_method_name'] = $vdata[0]->payment_method_name;
            $this->data['insurance_term_name'] = $vdata[0]->insurance_term_name;
            $this->data['delivery_term_name'] = $vdata[0]->delivery_term_name;
            $this->data['supplier_name'] = $vdata[0]->supplier_name;
            $this->data['supplier_site_name'] = $vdata[0]->supplier_site_name;
            $this->data['pricelist_name'] = $vdata[0]->pricelist_name;
            $this->data['project_name'] = $vdata[0]->project_name;
            $this->data['organization_name'] = $vdata[0]->organization_name;
            $this->data['remarks'] = $vdata[0]->remarks;
            $this->data['round_off'] = $vdata[0]->round_off;
            $this->data['unloading_charges'] = $vdata[0]->unloading_charges;
            $this->data['transport_charges'] = $vdata[0]->transport_charges;
            $this->data['insurance_charges'] = $vdata[0]->insurance_charges;
            $this->data['packing_charges'] = $vdata[0]->packing_charges;
            $this->data['freight_amount'] = $vdata[0]->freight_amount;
            $this->data['po_tax_total'] = round($vdata[0]->po_tax_total);
            $this->data['po_grand_total'] = round($vdata[0]->po_grand_total);
            $this->data['source'] = $vdata[0]->source;
            $this->data['reference_number'] = $vdata[0]->reference_number;
            $this->data['bill_to_location'] = $vdata[0]->bill_location;
            $this->data['ship_to_location'] = $vdata[0]->location_name;
            $this->data['country_name'] = $vdata[0]->country_name;
            $this->data['state_name'] = $vdata[0]->state_name;
            $this->data['city_name'] = $vdata[0]->city_name;
            $this->data['siteaddress'] = $vdata[0]->siteaddress;
            $this->data['fob_point_name'] = $vdata[0]->fob_point_name;

            $result = $this->location_details($vdata[0]->ship_to_location_id);
            $result1 = $this->location_details($vdata[0]->bill_to_location_id);
            $this->data['ship_to_address'] = $result;
            $this->data['bill_to_address'] = $result1;


            $this->data['attachements'] = $vdata[0]->attachfile_name;
            $this->data['created_by'] = $vdata[0]->first_name;
            $this->data['created_user'] = $vdata[0]->username;
            $this->data['approved_by'] = $vdata[0]->approver;
            $this->data['carrier_name'] = $vdata[0]->carrier_name;
            $this->data['supplier_reference_no'] = $vdata[0]->supplier_reference_no;
            $this->data['currency'] = $vdata[0]->supplier_reference_no;


            $a = \DB::table('p_po_lines_t')->where('po_hdr_id', $id)->get();

            $vlinesdata = \DB::table('p_po_lines_t')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'p_po_lines_t.product_id')
                ->leftjoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'p_po_lines_t.uom_code_id')
                ->leftjoin('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'p_po_lines_t.tax_group_id')
                ->leftjoin('f_gst_code_hdr_t', 'f_gst_code_hdr_t.gst_code_hdr_id', '=', 'p_po_lines_t.hsn_code')
                ->leftjoin('m_manufacturer_partno_t', 'm_manufacturer_partno_t.manufacturer_partno_id', '=', 'p_po_lines_t.part_no')
                ->where('p_po_lines_t.po_hdr_id', $id)->get();

            $this->data['vlinesdata'] = $vlinesdata;
            $this->data['uom_code'] = $vlinesdata[0]->uom_code;
            $this->data['tax_group_name'] = $vlinesdata[0]->tax_group_name;
            $this->data['classification_code'] = $vlinesdata[0]->classification_code;
            $promised_date = $vlinesdata[0]->promised_date;
            $this->data['promised_date'] = date(\Session::get('p_date_format'), strtotime($promised_date));
            $promised_alternate_date = $vlinesdata[0]->promised_alternate_date;
            $this->data['promised_alternate_date'] = date(\Session::get('p_date_format'), strtotime($promised_alternate_date));
            $this->data['part_no'] = $vlinesdata[0]->part_no;
            $this->data['row_id'] = $id;

            if ($mesg != null) {
                return $this->data;
            } else if (isset($_GET['report'])) {

                return view('purchaseorder.report_view', $this->data);
            } else if (isset($_GET['invoice'])) {
                return view('purchaseorder.poviewinvoice', $this->data);
            } else {

                $this->data['return_url'] = $_GET['return'];
                return view('purchaseorder.view', $this->data);
            }
        }
    }
    /*End View Function*/

    /* purpose for PO Print */

    public function poprint($id)
    {

        require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');

        $row = \DB::table('p_po_hdr_t')->leftjoin('f_account_currency_t', 'f_account_currency_t.account_currency_id', 'p_po_hdr_t.currency')->where('po_hdr_id', $id)->get();
        //  dd($row);
        $hdr_id = $row[0]->po_hdr_id;
        $this->data['po_hdr_id'] = $hdr_id;
        $this->data['po_date'] = date('d-m-Y', strtotime($row[0]->po_date));
        $this->data['po_number'] = $row[0]->po_number;
        $this->data['currency'] = $row[0]->currency_symbol;
        $this->data['po_type'] = $row[0]->po_type;
        $this->data['reference_number'] = $row[0]->reference_number;
        $this->data['round_off'] = $row[0]->round_off;
        $this->data['reverse_charge'] = $row[0]->reverse_charge;
        //dd($this->data);
        $this->data['supplier_reference_no'] = $row[0]->supplier_reference_no;
        $this->data['po_for_verdura'] = $row[0]->po_for_verdura;
        $this->data['despatch_through'] = $this->getFreight($row[0]->freight_carrier_id);
        $this->data['delivery_date'] = date('d-m-Y', strtotime($row[0]->delivery_date));
        $comp = \DB::table('m_company_t')->select('m_company_t.*', 'm_company_line_t.*')->leftjoin('m_company_line_t', 'm_company_line_t.companyid', '=', 'm_company_t.company_id')
            ->where('m_company_line_t.locationid', $row[0]->ship_to_location_id)->get();
        // dd($row);
        if (count($comp) > 0) {
            $this->data['gstno'] = $comp[0]->gst_no;
            $this->data['panno'] = $comp[0]->pan_no;
            $this->data['email_id'] = $comp[0]->email_id;
            $this->data['website_address'] = $comp[0]->website_address;
            $this->data['cin_no'] = $comp[0]->cin_no;
            $this->data['excise_registration_no'] = $comp[0]->excise_registration_no;
            $this->data['tax_reg_no'] = $comp[0]->tax_reg_no;
        } else {
            $this->data['gstno'] = "";
            $this->data['panno'] = "";
            $this->data['email_id'] = "";
            $this->data['cin_no'] = "";
            $this->data['excise_registration_no'] = "";
            $this->data['tax_reg_no'] = "";
            $this->data['website_address'] = "";
        }

        $location = $this->getLocationaddress();
        $paymentterm = Paymentterms::where('payment_term_id', $row[0]->payment_term_id)->pluck('payment_term_name')->first();
        $deliveryterm = Deliveryterms::where('delivery_terms_id', $row[0]->delivery_terms_id)->pluck('delivery_term_name')->first();
        $fob_point_name = Freightterms::where('frieghtterm_id', $row[0]->freight_terms_id)->pluck('fob_point_name')->first();

        $this->data['paymentterm'] = $paymentterm;
        $this->data['deliveryterm'] = $deliveryterm;
        $this->data['fob_point_name'] = $fob_point_name;
        $this->data['location_name'] = $location[0]->location_name;
        $this->data['country'] = $this->getCountry($location[0]->country_id);
        $this->data['state'] = $this->getState($location[0]->state_id);
        $this->data['state_name'] = $this->data['state'][0]->state_name;
        $this->data['loc_state_code'] = $this->data['state'][0]->state_code;
        $this->data['state_code_no'] = $this->data['state'][0]->state_code_no;
        $this->data['pincode'] = $location[0]->pincode;
        $this->data['city'] = $this->getCity($location[0]->city_id);
        ;
        $this->data['gst_no'] = $location[0]->gst_no;
        $this->data['pan_no'] = $location[0]->pan_no;
        $this->data['street'] = $location[0]->street_name;
        $companyadd = $this->data['address'] = $location[0]->address;

        if ($companyadd == "null") {
            $this->data['company_address'] = $this->data['street'] . "," . $this->data['city'] . "," . $this->data['state_name'] . "," . $this->data['country'];
        } else {
            $this->data['company_address'] = $this->data['address'] . "," . $this->data['city'] . "," . $this->data['state_name'] . "," . $this->data['country'];
        }

        $billaddress = $this->getSupplier($row[0]->supplier_id);
        $sup_address = $this->getSuppliersite($row[0]->suppliersite_id);
        //  dd($row[0]->supplier_id);

        $location = "1";
        $comp = \DB::SELECT('select * from m_location_t where location_id=' . $location . '');
        $dest = \DB::SELECT('select * from m_location_t where location_id=' . $sup_address[0]->location_id . '');
        if (count($dest) > 0) {
            $this->data['destination'] = $dest[0]->location_name;
        } else {
            $this->data['destination'] = "";
        }
        if ($billaddress != 0) {
            $this->data['bcustomer'] = ucwords(strtolower($billaddress[0]->supplier_name));
            $this->data['bcustomer_gst'] = $billaddress[0]->gst_no;
        } else {
            $this->data['bcustomer'] = '';
            $this->data['bcustomer_gst'] = '';
        }
        if ($sup_address != 0) {
            $this->data['baddress'] = ucwords(strtolower($sup_address[0]->address));
            $this->data['bcity'] = $this->getCity($sup_address[0]->city);
            $this->data['bcountry'] = $this->getCountry($sup_address[0]->country);
            $this->data['bstate'] = $this->getState($sup_address[0]->state);
            $this->data['bstate_name'] = $this->data['bstate'][0]->state_name;
            $this->data['bstate_code'] = $this->data['bstate'][0]->state_code;
            $this->data['bpincode'] = $sup_address[0]->pincode;
            $this->data['bcontact_number'] = $sup_address[0]->contact_number;
            $this->data['bgst_no'] = $sup_address[0]->gst_number;
        } else {
            $this->data['baddress'] = "";
            $this->data['bcity'] = "";
            $this->data['bcountry'] = "";
            $this->data['bstate'] = "";
            $this->data['bpincode'] = "";
            $this->data['bcontact_number'] = "";
            $this->data['bgst_no'] = "";
        }
        $this->data['gst_no'] = $comp[0]->gst_no;

        $this->data['supplier_address'] = $this->data['baddress'] . "," . $this->data['bcity'] . "," . $this->data['bstate_name'] . "," . $this->data['bcountry'] . "," . $this->data['bpincode'];
        if ($this->data['po_type'] == "STANDARD") {
            $lines = \DB::table('p_po_lines_t')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'p_po_lines_t.product_id')
                ->leftjoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'p_po_lines_t.uom_code_id')
                ->leftjoin('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'p_po_lines_t.tax_group_id')
                ->leftjoin('f_gst_code_hdr_t', 'f_gst_code_hdr_t.gst_code_hdr_id', '=', 'p_po_lines_t.hsn_code')
                ->where('p_po_lines_t.po_hdr_id', $id)->get();
        } else {
            $lines = \DB::table('p_po_lines_t')
                ->leftjoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'p_po_lines_t.uom_code_id')
                ->leftjoin('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'p_po_lines_t.tax_group_id')
                ->leftjoin('f_gst_code_hdr_t', 'f_gst_code_hdr_t.gst_code_hdr_id', '=', 'p_po_lines_t.hsn_code')
                ->where('p_po_lines_t.po_hdr_id', $id)->get();
        }
        $other_charges = $row[0]->transport_charges + $row[0]->unloading_charges + $row[0]->insurance_charges + $row[0]->packing_charges + $row[0]->other_tax_amount + $row[0]->other_frieght_amount;
        $this->data['subgrid'] = $lines;
        $subtotal = 0;
        $sub_total = 0;
        $tot_dis = 0;

        foreach ($this->data['subgrid'] as $key => $value) {
            if ($value->product_id != null) {
                $arr = $this->getProduct($value->product_id);
                $polines[$key]['product'] = $arr['concat_segment'];
                $polines[$key]['comments'] = $value->comments;
                $polines[$key]['hsn_code'] = $value->classification_code;
                //$polines[$key]['uom']=$arr['primary_uom_code'];
                $polines[$key]['uom'] = $value->uom_code;
                $polines[$key]['product_description'] = $value->product_description;
            } else {
                $polines[$key]['product'] = $value->product_description;
                $polines[$key]['hsn_code'] = $value->classification_code;
                $polines[$key]['uom'] = '';
                $polines[$key]['product_description'] = '';
            }

            if ($value->tax_group_id != null) {
                $tax = \DB::table("m_tax_group_t")->select('display_name')->where('tax_group_id', $value->tax_group_id)->get();
                if (!empty($tax)) {
                    $polines[$key]['disp_name'] = $tax[0]->display_name;
                    $polines[$key]['gst'] = $polines[$key]['disp_name'];
                    $polines[$key]['qty'] = $value->qty;
                    $polines[$key]['tax_amount'] = $value->tax_amount;
                    $polines[$key]['promised_date'] = $value->promised_date;
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
        $this->data['linedata'] = $polines;

        $this->data['sub_total'] = $subtotal;
        $this->data['total_discount'] = $tot_dis;
        $gstdata = \DB::select("select * from p_po_lines_t where po_hdr_id='" . $id . "' and tax_group_id!='0' group by tax_group_id");
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
                $gsttotal = $gsttotal + $gstvalue[$gst_key]['gst_val'];
                $gstvalue[$gst_key]['sgst_val'] = $gst['amount'] * ($display_name / 100) / 2;
                $gstvalue[$gst_key]['cgst_val'] = $gst['amount'] * ($display_name / 100) / 2;
                $this->data['sgcgstt'] = $arr_sgcgst;
            }

            $this->data['net_amount'] = $gstvalue[$gst_key]['amount'] + $gstvalue[$gst_key]['gst_val'] - $discount;
        }

        /***Get Company Details***/
        $company = $this->getCompany();
        if (!empty($company)) {
            $company_name = $company[0]->company_name;
            $company_logo_name = $company[0]->company_logo_name;
            $this->data['company_name'] = $company_name;
            $this->data['company_logo_name'] = $company_logo_name;
        }
        /***Get Company Details***/
        /********Get Payments Term *****/
        $ap_payment_term_name = $this->getPaymentterms($row[0]->payment_term_id);
        $this->data['ap_payment_term_name'] = $ap_payment_term_name;
        /***** end *******/
        $gst_total = $gsttotal;
        $grand_total = $gst_total + $sub_total + $other_charges;
        //Maruthu Purpose to GST calculation End
        $this->data['other_charges'] = $other_charges;
        $this->data['gst'] = $gstvalue;
        $this->data['gsttotal'] = $gsttotal;
        $this->data['value'] = $polines;
        $this->data['sub_total'] = $subtotal;
        $this->data['subtotal'] = $sub_total;
        $this->data['grand_total'] = $grand_total;
        $this->data['id'] = $id;

        $terms_condition = \DB::table('a_rpt_displayelements_hdr_t')->leftjoin('a_rpt_displayelements_lines_t', 'a_rpt_displayelements_lines_t.rpt_displayelements_hdr_id', '=', 'a_rpt_displayelements_hdr_t.rpt_displayelements_hdr_id')->select('a_rpt_displayelements_lines_t.element_content')->where('a_rpt_displayelements_hdr_t.report_source', 122)->where('a_rpt_displayelements_lines_t.element_name', 'TERMS&CONDITIONS')->get();

        if (count($terms_condition) > 0) {
            $this->data['terms_condition'] = $terms_condition;
        } else {
            $this->data['terms_condition'] = [];
        }

        $this->data['print'] = "PRINT";
        $this->data['print_val'] = '1';
        /*Purpose For Mail Function */
        if (isset($_GET['mail'])) {

            if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                Config::set('mail.username', \Session::get('user_email'));
                Config::set('mail.password', \Session::get('user_password'));
            }
            if (isset($this->data['subgrid'])) {
                $this->data['po_hdr_id'] = $this->data['subgrid'][0]->po_hdr_id;
            }
            $this->data['print'] = "PRINTS";


            \Mail::send('purchaseorder.print', $this->data, function ($message) {

                if (!empty($_GET['cc'])) {
                    $cc = explode(',', $_GET['cc']);
                    $message->cc($cc);
                } else {
                    $cc = array();
                }
                $msg = $_GET['msg'];
                //$message->from(Config::get('mail.username'));
                if (!empty(\Session::get('user_email'))) {
                    $message->from(\Session::get('user_email'));
                } else {
                    $message->from(\Config::get('mail.username'));
                }
                $message->to(explode(",", $_GET['mail']));
                $message->subject("Purchase Order" . $this->data['po_number']);
                $message->setBody($msg);
                $row = \DB::table('p_po_hdr_t')->where('po_hdr_id', $this->data['po_hdr_id'])->get();
                $message->attach('uploads/purchaseorder/PO_' . $this->data['po_hdr_id'] . '.pdf');
                if ($row[0]->attachment_file != '') {
                    $file_a = json_decode($row[0]->attachment_file);
                    foreach ($file_a as $k1 => $v1) {
                        $message->attach('uploads/purchaseorder/PO_' . $this->data['po_hdr_id'] . '/' . $v1);
                    }
                }

            });
            return 1;
        }

        if (isset($_GET['mails'])) {
            $this->data['print'] = "PRINTS";
            return view('purchaseorder.print', $this->data);
        }
        /*End Purpose For Mail Function */
        return view('purchaseorder.print', $this->data);
    }
    /*End Print Function*/
    /*Karthigaa purpose for delete function*/
    public function delete(Request $request, $id = null)
    {
        $count = 0;
        $queryquote = \DB::table('p_grn_hdr_t')->where('po_number', $id)->count();
        if ($queryquote >= 1) {
            $count++;
        }
        if ($count <= 0) {
            Purchaseorder::destroy($id);
            $query = \DB::table('p_po_lines_t')->where('po_hdr_id', $id)->delete();
            /**Auditlog**/
            $action = "Delete";
            $this->auditlog($id, "purchaseorder", $action, $id, "p_po_hdr_t");
            if ($query) {
                return 0;
            } else {
                return 1;
            }
        } else {
            return 2;
        }
    }
    /*End*/
    public function getpricelist(Request $request, $id = null)
    {
        $result = DB::table('m_supplier_t')->where('supplier_id', $id)->get();

        if (count($result) > 0) {
            $price_list_id = $result[0]->default_pricelist_id;
        } else {
            $price_list_id = '';
        }
        return $price_list_id;

    }
    public function pofilesave(Request $request)
    {
        //      dd($_POST);
        if ($request->hasfile('email_attachment')) {
            File::deleteDirectory(public_path('Uploads/purchaseorder/PO_' . $_POST['po_hdr_id']));

            foreach ($request->file('email_attachment') as $file) {
                $name = $file->getClientOriginalName();

                $file->move(public_path() . '/Uploads/purchaseorder/PO_' . $_POST['po_hdr_id'] . '/', $name);
                $data[] = $name;
            }
            $attachfile_name = json_encode($data);
            \DB::update("update p_po_hdr_t set attachment_file='" . $attachfile_name . "' where po_hdr_id=" . $_POST['po_hdr_id']);
            return 1;
        } else {
            $var = File::deleteDirectory(public_path('Uploads/purchaseorder/PO_' . $_POST['po_hdr_id']));

            \DB::update("update p_po_hdr_t set attachment_file='' where po_hdr_id=" . $_POST['po_hdr_id']);
            return 2;
        }

    }



    /* purpose for load uom code based on product */
    public function uomcode($product_id = null)
    {
        $query = \DB::table('m_products_t')->where('product_id', $product_id)->get();
        if (count($query) > 0) {
            $uom_code = $query[0]->trx_uom_id;
        }
        return $uom_code;
    }

    function getFreight($id)
    {
        $sql = \DB::SELECT("select * from m_frieghtcarriers_hdr_t where ar_frieghtcarriers_hdr_id='" . $id . "'");
        if (!empty($sql))
            return $sql[0]->carrier_name;
        else
            return '';
    }
    /*Purpose To get GST Details For Print*/
    function getGst($gst, $po_id)
    {
        $sql = array();
        $location = \Session::get('location');
        $sql = \DB::SELECT("select * from p_po_lines_t where po_hdr_id='" . $po_id . "' and tax_group_id='" . $gst . "'");

        $hsn = array();
        $gst = array();
        $sub_total = 0;
        foreach ($sql as $key => $value) {
            $dis_amt = ($value->unit_price) - ($value->unit_price * ($value->discount_percentage / 100));
            $amount = $dis_amt * $value->qty;
            $sub_total = $sub_total + $amount;
        }
        $gst['amount'] = $sub_total;
        return $gst;
    }
    /*End*/
    /*Purpose To get Location Details For Print*/
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
    /*Purpose To get Company Details For Print*/
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

    /*Purpose To get Supplier Details For Print*/
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
    /*Purpose To get Supplier Site Details For Print*/
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
    /*Purpose To get Supplier Site Details For Print*/
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
    /*Purpose To get State For Print*/
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
    /*Purpose To get Country For Print*/
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
    /*Purpose To get Payment Terms Details For Print*/
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
    /*Purpose To get Product Details For Print*/
    function getProduct($id = null)
    {
        $product = array();
        $product = \DB::table('m_products_t as pdt')
            ->leftJoin('m_uom_codes_t as uom', 'uom.uom_code_id', '=', 'pdt.trx_uom_id')
            ->leftJoin('f_gst_code_hdr_t as gst', 'gst.gst_code_hdr_id', '=', 'pdt.hsn_code')
            ->select('uom.uom_code', 'pdt.concatenated_product', 'gst.classification_code as hsn_code')
            ->where('pdt.product_id', $id)->get();
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
    /*Karthigaa Purpose For Mail Function*/
    function getSuppliermaildetails($id = null)
    {
        $sql = "SELECT
                        m_supplier_sites_t.contact_person,
                        m_supplier_sites_t.contact_number,
                        m_supplier_sites_t.contact_mail as email_id,
                        m_supplier_sites_t.supplier_site_id
                        FROM `m_supplier_t`
                        left join m_supplier_sites_t ON
                        m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id
                        where m_supplier_t.supplier_id='$id'";
        $result1 = \DB::select($sql);
        if (!empty($result1)) {
            foreach ($result1 as $key => $value) {
                $contactperson = explode(',', $value->contact_person);
                $contact_number = explode(',', $value->contact_number);
                $email = explode(',', $value->email_id);
                foreach ($email as $k => $v) {
                    $result[] = array($value->supplier_site_id, $contactperson[$k], $contact_number[$k], $v);
                }
            }
        } else {
            $result[] = array();
        }
        return $result;
    }
    /*end Mail Function*/

    /*Karthigaa Purpose For Load Supplier Details*/
    public function supplierdata($id = null)
    {
        $sql = \DB::table('m_supplier_t')->select('default_pricelist_id', 'default_payment_method_id', 'default_payment_terms_id', 'delivery_terms_id', 'insurance_term_id')->where('supplier_id', $id)->get();
        if (!empty($sql)) {
            $data['insurance_term_id'] = $sql[0]->insurance_term_id;
            $data['default_pricelist_id'] = $sql[0]->default_pricelist_id;
            $data['default_payment_method_id'] = $sql[0]->default_payment_method_id;
            $data['default_payment_terms_id'] = $sql[0]->default_payment_terms_id;
            $data['delivery_terms_id'] = $sql[0]->delivery_terms_id;
        }
        return $data;
    }
    /*End*/
    /*Karthigaa Purpose For Load Supplier Based Price load*/
    function pricelist($supplier_id, $product_id)
    {
        $result = \DB::table('m_supplier_t')->select('default_pricelist_id as default_pricelist_id')->where('supplier_id', $supplier_id)->get();
        if ($result->isNotEmpty()) {
            $pricelist = $result[0]->default_pricelist_id;
            $result_pri = \DB::table('i_pricelist_lines_t')->select('unit_price')->where('pricelist_hdr_id', $pricelist)->where('product_id', $product_id)->get();
            if ($result_pri->isNotEmpty()) {
                return $result_pri[0]->unit_price;
            } else {
                return '0.00';
            }
        } else {
            return '0.00';
        }
    }
    /*END*/
    /*Purpose For Get Tax Details From Enquiry*/
    public function gettax($pid)
    {
        $pro_details = \DB::table("m_products_t")->select('trx_uom_id', 'hsn_code')->where('product_id', $pid)->get();
        if ($pro_details->isNotEmpty()) {
            $prd_data['uom_code_id'] = $pro_details[0]->trx_uom_id;
            $hsn_code = $pro_details[0]->hsn_code;
            $date = date('Y-m-d');
            $tax = \DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='$hsn_code' and start_date<='$date' and end_date>='$date' and active='Yes'");
            if (!empty($tax)) {
                $prd_data['taxgroup_id'] = $tax[0]->tax_group_id;
                $tax1 = \DB::table("m_tax_group_t")->select('display_name')->where('tax_group_id', $tax[0]->tax_group_id)->get();
                $prd_data['display_name'] = $tax1[0]->display_name;
                return $prd_data;
            } else {
                $prd_data['uom_code_id'] = $pro_details[0]->trx_uom_id;
                $prd_data['taxgroup_id'] = 0;
                $prd_data['display_name'] = 1;
                return $prd_data;
            }
        } else {
            $prd_data['uom_code_id'] = 0;
            $prd_data['taxgroup_id'] = 0;
            $prd_data['display_name'] = 1;
            return $prd_data;
        }
    }
    /*END*/

    /*Purpose For PO Alternate Date**/
    public function getpoaltdatedatas($id = null)
    {
        $pohdrid = $id;
        $sql = \DB::select("select ph.po_number,ph.po_date,pl.po_hdr_id,pl.product_id,pl.po_line_id,pl.promised_date,prd.concatenated_product from p_po_lines_t pl left join m_products_t prd on prd.product_id=pl.product_id left join p_po_hdr_t ph on pl.po_hdr_id=ph.po_hdr_id where ph.po_hdr_id=$pohdrid");
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }
    /*Purpose For PO Alternate Date Save Function**/
    public function alternatedatesave(Request $request)
    {
        $altdate = $request['bulk_promised_alternate_date'];
        $blklineid = $request['bulk_po_line_id'];
        foreach ($blklineid as $key => $val) {
            $datefmch = date('Y-m-d', strtotime($altdate[$key]));
            $alterdate = \DB::select("update p_po_lines_t set promised_alternate_date='$datefmch' where po_line_id=$val");
        }
        \DB::commit();
        return response()->json(array('status' => 'success', 'message' => 'Promised Alternate date Saved Successfully '));
    }
    /*End*/
    /*Purpose For Supplier Based Product Load Function**/
    public function getsupplierpriceproduct($id = null, $pdtid = null)
    {
        if ($id != '') {
            $productid = \DB::select("select i_pricelist_lines_t.pricelist_hdr_id,i_pricelist_lines_t.product_id,m_supplier_t.supplier_id from i_pricelist_lines_t left join i_pricelist_hdr_t on (i_pricelist_hdr_t.pricelist_hdr_id=i_pricelist_lines_t.pricelist_hdr_id) left join m_supplier_t on (m_supplier_t.default_pricelist_id=i_pricelist_hdr_t.pricelist_hdr_id) where m_supplier_t.supplier_id= $id");
            foreach ($productid as $key => $value) {
                $prdoducts[] = $value->product_id;
            }
            $productsplit = implode(",", $prdoducts);
            $prd = \DB::select("select product_id,concatenated_product from m_products_t where product_id in($productsplit)");
            $this->data['product_id'] = '<option>-- Please Select --</option>';
            foreach ($prd as $key => $value) {
                $this->data['product_id'] .= $this->jcustomdataselect('m_products_t', 'product_id', 'product_code|concatenated_product', $pdtid, 'and product_id=' . $value->product_id);
            }
            return $this->data['product_id'];
        } else {
            return 0;
        }
    }
    /*Purpose For Supplier Based Product Load Function**/
    public function getproductpricelist($id = null, $pid = null)
    {
        $con = \DB::select("SELECT select_option FROM `m_product_setting_t` WHERE module_name='purchaseorder'");
        if (count($con) > 0) {
            $cond = $con[0]->select_option;
        } else {
            $cond = 'concatenated_product';
        }
        $productid = \DB::select("SELECT   CONCAT(" . $cond . ") as name,m_products_t.product_id as id FROM i_pricelist_lines_t JOIN m_products_t ON i_pricelist_lines_t.product_id=m_products_t.product_id WHERE i_pricelist_lines_t.pricelist_hdr_id='$id' and i_pricelist_lines_t.active='Yes'");
        $this->data['productid'] = "<option value=''>-- Please Select --</option>";
        foreach ($productid as $key => $value) {
            if ($value->id == $pid)
                $select = "selected";
            else
                $select = '';
            $this->data['productid'] .= "<option value='" . $value->id . "' " . $select . ">" . $value->name . "</option>";
        }
        //        dd($this->data['productid']);
        return $this->data['productid'];
    }

    public function productprice($productid = null)
    {

        $productprice = array();
        $productprice = \DB::select("select
                            poh.po_hdr_id AS po_header_id,
                            poh.po_number AS po_number,
                            poh.po_date AS po_date,
                            poh.supplier_id AS supplier_id,
                            sp.supplier_name AS supplier_name,
                            pol.product_id AS product_id,
                            pr.concatenated_product AS concatenated_product,
                            pol.qty AS qty,
                            pol.unit_price AS unit_price,
                            pol.line_total AS total
                            FROM
                            p_po_hdr_t poh
                            JOIN p_po_lines_t pol
                            JOIN m_supplier_t sp
                            JOIN m_products_t pr
                            WHERE poh.po_hdr_id = pol.po_hdr_id AND poh.supplier_id = sp.supplier_id AND pol.product_id = pr.product_id and pol.product_id='$productid' and poh.grn_status='1'
                            ORDER BY poh.po_hdr_id");

        if (!empty($productprice)) {
            return json_encode($productprice);
        } else {
            return 0;
        }
    }
    public function getaddress(Request $request)
    {
        $location_id = $_GET['location_id'];
        if ($location_id != '') {
            $users = DB::table('m_location_t')
                ->join('m_countries_t', 'm_countries_t.country_id', '=', 'm_location_t.country_id')
                ->join('m_states_t', 'm_states_t.state_id', '=', 'm_location_t.state_id')
                ->join('m_cities_t', 'm_cities_t.city_id', '=', 'm_location_t.city_id')
                ->select('m_location_t.location_code', 'm_location_t.address', 'm_location_t.street_name', 'm_location_t.area', 'm_location_t.pincode', 'm_location_t.Phone', 'm_countries_t.country_name', 'm_states_t.state_name', 'm_cities_t.city_name')
                ->where('m_location_t.location_id', $location_id)
                ->first();


            if ($users != '') {
                $result = $users->country_name . '-' . $users->state_name . '-' . $users->city_name . '-' . $users->address . '-' . $users->street_name . '-' . $users->area . '-' . $users->pincode;
            } else {
                $result = '';
            }

        } else {
            $result = '';
        }
        return $result;

    }
    /*Karthigaa Purpose For Location Details Load*/
    public function location_details($location_id = null)
    {
        if ($location_id != '') {
            $users = DB::table('m_location_t')
                ->join('m_countries_t', 'm_countries_t.country_id', '=', 'm_location_t.country_id')
                ->join('m_states_t', 'm_states_t.state_id', '=', 'm_location_t.state_id')
                ->join('m_cities_t', 'm_cities_t.city_id', '=', 'm_location_t.city_id')
                ->select('m_location_t.location_code', 'm_location_t.address', 'm_location_t.street_name', 'm_location_t.area', 'm_location_t.pincode', 'm_location_t.Phone', 'm_countries_t.country_name', 'm_states_t.state_name', 'm_cities_t.city_name')
                ->where('m_location_t.location_id', $location_id)
                ->first();


            if ($users != '') {
                if ($users->address == "null") {
                    $result = $users->street_name . '-' . $users->area . '-' . $users->city_name . '-' . $users->state_name . '-' . $users->country_name . '-' . $users->pincode;
                } else {
                    $result = $users->address . '-' . $users->street_name . '-' . $users->area . '-' . $users->city_name . '-' . $users->state_name . '-' . $users->country_name . '-' . $users->pincode;
                }
            } else {
                $result = '';
            }

        } else {
            $result = '';
        }
        return $result;
    }
    /* Purpose For Load Product Based QOH*/

    public function getproductqoh($pid = null)
    {
        $compy = \Session::get('companyid');
        $qoh_qty = \DB::SELECT("select round(sum(f.qty-IFNULL(f.qtyy,0)),2) as qoh_qty,f.product_id from (select round(sum(qoh_trx_qty),2)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id=" . $pid . " and  i_qoh_detail_t. company_id=" . $compy . "  GROUP by product_id UNION ALL SELECT 0 as qty,round(sum(reserv_trx_qty),2) as qtyy,product_id as fdfd from i_reservation_detail_t 
            where product_id=" . $pid . " and  i_reservation_detail_t.company_id=" . $compy . " GROUP by product_id)f");


        //dd($qoh_qty);

        if (!empty($qoh_qty)) {
            $prd_data['qoh_qty'] = $qoh_qty[0]->qoh_qty;

        } else {
            $prd_data['qoh_qty'] = 0;
        }
        return $prd_data['qoh_qty'];
    }


    /* purpose: update order Status*/
    public function poorderupdatestatus($id = null)
    {
        //   dd("fdg");
        \DB::table('p_po_hdr_t')->where('po_hdr_id', $id)->update(['po_status' => 'CLOSED']);
        return 1;
    }
    /* end*/

}
