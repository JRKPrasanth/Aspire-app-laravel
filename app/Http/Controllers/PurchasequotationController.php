<?php

namespace App\Http\Controllers;
use App\Purchasequotation;
use App\Purchasequotationlines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Validator, DB;
use Yajra\DataTables\DataTables;

class PurchasequotationController extends Controller
{

    public $module = "purchasequotation";
    public function __construct()
    {
        $this->data = array();
        $this->table = "p_quotation_hdr_t";
        $this->subtable = "p_quotation_lines_t";
        $this->pageModule = "purchasequotation";
        $this->model = new Purchasequotation;
        $this->submodel = new Purchasequotationlines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'purchasequotation',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu'] = $this->indexs();
        if ($this->data['pageMethod'] == 'purchasequtoetionapprove' || $this->data['pageMethod'] == "purchasecopyquotation") {
            $this->data['status'] = "INITIATED";
        } elseif ($this->data['pageMethod'] == "purchasequotation") {
            $this->data['status'] = "";
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
        $this->data['pageMethod'] = \Request::route()->getName();
        $table = \DB::table('p_quotation_hdr_t')->get();
        $this->data['datas'] = $table;

        return view('purchasequotation.table', $this->data);
    }

    /*  purpose for Display Data in JQgrid function */
    public function getPurchasequotationData()
    {
        $wh = '';
        $app_id = \Session::get('id');


        if ($_GET['status'] != '' && $_GET['pagemethod'] == 'purchasecopyquotation') {

            $wh .= " and p_quotation_hdr_t.quote_status!='DRAFT' and p_quotation_hdr_t.quote_status!='REJECTED' and p_quotation_hdr_t.quote_status!='CANCELLED' ";
            $op = "NOT IN ";
            $val_status = "('DRAFT','REJECTED','CANCELLED')";
        } else if ($_GET['status'] != '' && $_GET['pagemethod'] == 'purchasequtoetionapprove') {

            $wh .= " and p_quotation_hdr_t.quote_status!='DRAFT' and p_quotation_hdr_t.quote_status!='APPROVED' and p_quotation_hdr_t.quote_status!='REJECTED' and p_quotation_hdr_t.quote_status!='CANCELLED' and json_contains(p_quotation_hdr_t.approver_id,'" . $app_id . "')=1 ";
            $op = "NOT IN ";
            $val_status = "('DRAFT','REJECTED','APPROVED','CANCELLED')";

        } else if ($_GET['status'] != '' && $_GET['pagemethod'] == 'purchasequtoetopo') {

            $wh .= " and p_quotation_hdr_t.quote_status!='DRAFT' and p_quotation_hdr_t.quote_status!='INITIATED' and p_quotation_hdr_t.quote_status!='REJECTED' and p_quotation_hdr_t.quote_status!='CANCELLED' ";
            $op = "NOT IN ";
            $val_status = "('DRAFT','REJECTED','INITIATED','CANCELLED')";
        } else {
            $wh .= " ";
            $op = "=";
            $val_status = "'APPROVED'";
        }


        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');

        $emp_id = \Session::get('emp_id');
        if ($groupname == '3' && $emp_id != '157' && $_GET['pagemethod'] == 'purchasequtoetionapprove') {
            $wh .= " and m_supplier_t.supplier_name in ('OM SAKTHI STATIONERY & FANCY','APPLE STATIONARY','HICARE SERVICES PVT LTD','USHA FIRE SAFETY EQUIPMENTS PVT LTD','AGQR CERTIFICATIONS PRIVATE LIMITED')";
        }

        if ($_GET['pagemethod'] == 'purchasequtoetopo') {
            $SQL = "SELECT
                        p_quotation_hdr_t.quotation_hdr_id as quotation_hdr_id,
                        p_quotation_hdr_t.quotation_no as quotation_no,
                        p_quotation_hdr_t.quotation_date as quotation_date,
                        p_quotation_hdr_t.quotation_type as quotation_type,
                        p_quotation_hdr_t.quote_status as quote_status,
                        p_quotation_hdr_t.supplier_ref_no as supplier_ref_no,
                        p_quotation_hdr_t.remarks as remarks,
                        p_quotation_hdr_t.reference_id as reference_id,
                        p_quotation_hdr_t.reference_number as reference_number,
                        p_quotation_hdr_t.source as source,
                        p_quotation_hdr_t.quote_grand_total as grand_total,
                        p_quotation_hdr_t.quote_tax_total as tax_total,
                        m_supplier_t.supplier_name,
						tbb_users.first_name as created_by,
						CASE WHEN tb_users.first_name = tbb_users.first_name  AND p_quotation_hdr_t.quote_status != 'APPROVED' THEN
                        'Yet to Approve'
               			ELSE
               			tb_users.first_name
               			END as approved_by
                        FROM `p_quotation_hdr_t`
						LEFT JOIN tb_users as tbb_users ON tbb_users.id = p_quotation_hdr_t.created_by
						left JOIN tb_users on tb_users.id = p_quotation_hdr_t.last_updated_by
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_quotation_hdr_t.`supplier_id`) where NOT EXISTS
                        (
                        SELECT  *
                        FROM    p_po_hdr_t p
                        WHERE   p.reference_id = p_quotation_hdr_t.quotation_hdr_id and p.source = 'QUOTATION' and p.po_date >= '2024-04-01'
                        ) and 1=1 $wh ORDER BY p_quotation_hdr_t.quotation_hdr_id DESC";

        } else {

            $SQL = "SELECT
                        p_quotation_hdr_t.quotation_hdr_id as quotation_hdr_id,
                        p_quotation_hdr_t.quotation_no as quotation_no,
                        p_quotation_hdr_t.quotation_date as quotation_date,
                        p_quotation_hdr_t.quotation_type as quotation_type,
                        p_quotation_hdr_t.quote_status as quote_status,
                        p_quotation_hdr_t.supplier_ref_no as supplier_ref_no,
                        p_quotation_hdr_t.remarks as remarks,
                        p_quotation_hdr_t.reference_id as reference_id,
                        p_quotation_hdr_t.reference_number as reference_number,
                        p_quotation_hdr_t.source as source,
                        p_quotation_hdr_t.quote_grand_total as grand_total,
                        p_quotation_hdr_t.quote_tax_total as tax_total,
                        m_supplier_t.supplier_name,
                        tbb_users.first_name as created_by,
                        CASE WHEN tb_users.first_name = tbb_users.first_name  AND p_quotation_hdr_t.quote_status != 'APPROVED' THEN
                        'Yet to Approve'
               			ELSE
               			tb_users.first_name
               			END as approved_by
                        FROM `p_quotation_hdr_t`
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_quotation_hdr_t.`supplier_id`) 
                        LEFT JOIN tb_users as tbb_users ON tbb_users.id = p_quotation_hdr_t.created_by
                        left JOIN tb_users on tb_users.id = p_quotation_hdr_t.last_updated_by
                        where 1=1 $wh ORDER BY p_quotation_hdr_t.quotation_hdr_id DESC";
        }

        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }


    /* Purpose For :Create Function to Call Form Blade*/
    public function create(Request $request, $id = null, $quotetype = null, $quote_status = null)
    {

        $this->data['curlname'] = \Request::route()->getName();
        $this->data['pageMethod'] = \Request::route()->getName();
        $returnUrl = $request->query('return', 'purchasequotation');
        $this->data['pageModule'] = url($returnUrl);


        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'purchasequotation')->get();
        $this->data['supnameopt'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        $this->data['suptypeopt'] = $this->jqgridselect('m_suppliertypes_t', 'suppliertype_id', 'suppliertype_name');
        $this->data['supsitopt'] = $this->jqgridselect('m_supplier_sites_t', 'supplier_site_id', 'supplier_site_name');
        $this->data['country'] = $this->jqgridselect('m_countries_t', 'country_id', 'country_name');
        $this->data['state'] = $this->jqgridselect('m_states_t', 'state_id', 'state_name');
        $this->data['city'] = $this->jqgridselect('m_cities_t', 'city_id', 'city_name');
        $this->data['prdcatopt'] = $this->jqgridselect('m_product_category_t', 'product_category_id', 'category_name');
        $this->data['prdnameopt'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');
        $this->data['group'] = $this->jqgridselect('m_product_groups_t', 'product_group_id', 'group_name');
        $this->data['tax_group_id_pop'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
        /*Get Current Location Address*/
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
        /*Get Current Location Address*/
        $this->data['closebtn'] = str_replace('create', '', $this->data['curlname']);

        if (isset($_GET['status']) && $_GET['status'] != 'COPYQUOTE') {
            /*Convertion From Enquiry*/
            if ($_GET['status'] == "ENQUIRY") {
                $enquiry_data = \DB::table('p_enquiry_hdr_t')->where('enquiry_hdr_id', $id)->get();
                $enquiry_lines_data = \DB::table('p_enquiry_lines_t')->where('enquiry_hdr_id', $id)->get(); //dd($enquiry_lines_data);
                $this->data['row'] = (object) array();
                $this->data['row']->quotation_hdr_id = "";
                $this->data['row']->quotation_date = date('Y-m-d');
                $this->data['row']->supplier_ref_no = "";
                $this->data['row']->supplier_quotation_date = date('Y-m-d');
                $this->data['row']->delivery_date = date('Y-m-d');
                $this->data['row']->quote_status = "";
                $this->data['row']->quotation_no = "";
                $this->data['row']->quote_tax_total = "";
                $this->data['row']->supplier_id = $enquiry_data[0]->supplier_id;
                $this->data['row']->quote_grand_total = "";
                $this->data['row']->remarks = "";
                $this->data['row']->reference_number = $enquiry_data[0]->enquiry_number;
                $this->data['row']->source = "ENQUIRY";
                $this->data['row']->reference_id = $id;
                $this->data['row']->quotation_type = $enquiry_data[0]->enquiry_type_id;
                $this->data['id'] = '';
                $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', $enquiry_data[0]->supplier_id);
                $supplier_id = $enquiry_data[0]->supplier_id;
                $this->data['supplier_site_id'] = $this->jcustomselect('m_supplier_sites_t', 'supplier_site_id', 'supplier_site_number|supplier_site_name', $enquiry_data[0]->suppliersite_id, " and supplier_id='$supplier_id'");
                $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $enquiry_data[0]->organization_id);
                $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
                $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $enquiry_data[0]->project_id);
                $price = $this->supplierdata($enquiry_data[0]->supplier_id);
                $this->data['quote_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $price['default_pricelist_id'], ' and price_list_type="Purchase"');
                $this->data['delivery_terms_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $price['delivery_terms_id'], ' and source_type_id="Purchase"');
                $this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', '', ' and source_type_id="Purchase"');
                $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $price['default_payment_terms_id']);
                $this->data['insurance_term_id'] = $this->jCombo('m_insurance_terms_t', 'insurance_term_id', 'insurance_term_name', $price['insurance_term_id']);
                $this->data['default_payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $price['default_payment_method_id']);
                $this->data['bill_to_location_id'] = $this->jcustomselect('m_location_t', 'location_id', 'location_name', $currentloc, 'and location_id in ' . "(" . $location . ")");
                $this->data['ship_to_location_id'] = $this->jcustomselect('m_location_t', 'location_id', 'location_name', $currentloc, 'and location_id in ' . "(" . $location . ")");
                $result = $this->location_details($currentloc);
                $result1 = $this->location_details($currentloc);
                $this->data['ship_to_address'] = $result;
                $this->data['bill_to_address'] = $result1;
                $this->data['linedata'] = array();
                $this->data['productid'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '', 'purchasequotation');
                $this->data['uomcodeid'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
                $this->data['partno'] = $this->jCombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '');
                $grandtotal = 0;
                $taxamount_total = 0;
                $quotecount = 0;
                $key = -1;
                foreach ($enquiry_lines_data as $key1 => $value) {
                    if ($this->data['row']->quotation_type == "STANDARD") {
                        $unitprice = $this->pricelist($enquiry_data[0]->supplier_id, $value->product_id);
                        if ($unitprice != "0.00") {
                            $key++;
                            $this->data['linedata'][$key] = (object) array();
                            $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                            $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id, 'and uom_code_id=' . $value->uom_code_id);
                            $this->data['linedata'][$key]->part_no = $this->jcustomselectcomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and manufacturer_partno_id=' . $value->part_no);
                            $hsn = \DB::select("select hsn_code,product_id,defalut_hsn_code from m_products_t where product_id=" . $value->product_id);
                            $this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $hsn[0]->defalut_hsn_code, ' and gst_code_hdr_id in(' . $hsn[0]->hsn_code . ')');
                            $tax = $this->taxdetails($hsn[0]->defalut_hsn_code, $enquiry_data[0]->suppliersite_id, "PURCHASE");
                            $taxgrp = $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $tax['tax_group_id']);
                            $this->data['linedata'][$key]->qty = $value->qty;
                            $this->data['linedata'][$key]->unit_price = $unitprice;
                            $tax1 = \DB::table("m_tax_group_t")->select('display_name')->where('tax_group_id', $tax['tax_group_id'])->get();
                            if ($tax1 != '') {
                                $taxamount = (($value->qty * $unitprice) * $tax1[0]->display_name) / 100;
                            } else {
                                $taxamount = 0;
                            }
                            $linetotal = ($taxamount + ($value->qty * $unitprice));
                            $taxamount_total += $taxamount;
                            $grandtotal = $grandtotal + $linetotal;
                            $this->data['linedata'][$key]->tax_amount = $taxamount;
                            $this->data['linedata'][$key]->line_total = $linetotal;
                            $this->data['linedata'][$key]->promised_date = $value->promised_date;
                            $this->data['linedata'][$key]->comments = $value->comments;
                            $this->data['row']->quote_grand_total = $grandtotal;
                            $this->data['row']->quote_tax_total = $taxamount_total;
                        } else {
                            $quotecount++;
                        }
                    } else {
                        $key++;
                        $this->data['linedata'][$key] = (object) array();
                        $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', $value->product_id, 'purchasequotation');
                        $this->data['linedata'][$key]->product_description = $value->product_description;
                        $this->data['linedata'][$key]->part_no = $this->jCombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no);
                        $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                        $this->data['linedata'][$key]->qty = $value->qty;
                        $unitprice = $this->pricelist($enquiry_data[0]->supplier_id, $value->product_id);
                        if ($unitprice != "0.00") {
                            $this->data['linedata'][$key]->unit_price = $unitprice;
                        } else {
                            $this->data['linedata'][$key]->unit_price = '';
                        }
                        $this->data['linedata'][$key]->unit_price = '';
                        $this->data['linedata'][$key]->tax_amount = '';
                        $this->data['linedata'][$key]->line_total = '';
                        $this->data['linedata'][$key]->promised_date = $value->promised_date;
                        $this->data['linedata'][$key]->comments = $value->comments;
                        $this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="SAC"');
                        $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');

                    }
                    $this->data['quotecount'] = $quotecount;
                }
            }

            /** purpose::Convertion From Requisition**/ else if ($_GET['status'] == "REQUISITION") {
                $req_data = \DB::table('p_requisition_hdr_t')->where('requisition_hdr_id', $id)->get();
                $req_lines_data = \DB::table('p_requisition_lines_t')->where('requisition_hdr_id', $id)->where('status', 0)->get();
                $this->data['row'] = (object) array();
                $this->data['row']->quotation_hdr_id = "";
                $this->data['row']->quotation_date = date('Y-m-d');
                $this->data['row']->supplier_ref_no = "";
                $this->data['row']->supplier_quotation_date = date('Y-m-d');
                $this->data['row']->delivery_date = date('Y-m-d');
                $this->data['row']->quote_status = "";
                $this->data['row']->quotation_no = "";
                $this->data['row']->quote_tax_total = "";
                $this->data['row']->quote_grand_total = "";
                $this->data['row']->remarks = "";
                $this->data['row']->reference_number = $req_data[0]->requisition_no;
                $this->data['row']->source = "REQUISITION";
                $this->data['row']->reference_id = $id;
                $this->data['row']->quotation_type = "STANDARD";
                $this->data['id'] = '';
                $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', '');
                $this->data['supplier_site_id'] = $this->jCombo('m_supplier_sites_t', 'supplier_site_id', 'supplier_site_number|supplier_site_name', '');
                $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $req_data[0]->organization_id);
                $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $req_data[0]->created_by);
                $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $req_data[0]->project_id);
                $this->data['quote_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', '', ' and price_list_type="Purchase"');
                $this->data['delivery_terms_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', '', ' and source_type_id="Purchase"');
                $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', '');
                $this->data['insurance_term_id'] = $this->jCombo('m_insurance_terms_t', 'insurance_term_id', 'insurance_term_name', '');
                $this->data['default_payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', '');
                $this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', '', ' and source_type_id="Purchase"');
                $this->data['linedata'] = array();
                $this->data['productid'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '', 'purchasequotation');
                $this->data['uomcodeid'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
                $this->data['partno'] = $this->jCombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '');

                foreach ($req_lines_data as $key => $value) {
                    $this->data['linedata'][$key] = (object) array();
                    $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                    $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                    $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');

                    if ($value->po_qty == 0) {
                        $qty = $value->qty;
                    } else {
                        $qty = ABS($value->balance_qty);
                    }
                    $this->data['linedata'][$key]->qty = $qty;
                    $this->data['linedata'][$key]->unit_price = '0.00';
                    $hsn = \DB::select("select hsn_code,product_id,defalut_hsn_code from m_products_t where product_id=" . $value->product_id);
                    $this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', ' and gst_code_hdr_id in(' . $hsn[0]->hsn_code . ')');
                    $this->data['linedata'][$key]->part_no = $this->jCombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '');
                }
            }
        }
        /*Create Quotation*/ elseif ($id == "0") {
            $this->data['row'] = (object) array();
            $this->data['row']->quotation_hdr_id = "";
            $this->data['row']->quotation_date = date('Y-m-d');
            $this->data['row']->supplier_ref_no = "";
            $this->data['row']->supplier_quotation_date = date('Y-m-d');
            $this->data['row']->delivery_date = date('Y-m-d');
            $this->data['row']->quote_status = "";
            $this->data['row']->quotation_no = "";
            $this->data['row']->quote_tax_total = "";
            $this->data['row']->quote_grand_total = "";
            $this->data['row']->remarks = "";
            $this->data['row']->reference_number = "";
            $this->data['row']->reference_id = "";
            $this->data['row']->attachfile_name = "";
            $this->data['row']->check = "1";
            $this->data['id'] = '';
            if ($quotetype != "LABOUR")
                $quotetype = "STANDARD";
            else
                $quotetype = $quotetype;
            $this->data['row']->quotation_type = $quotetype;
            $this->data['row']->source = 'STANDARD';
            $this->data['linedata'] = array();
            $this->data['quote_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', '', ' and price_list_type="Purchase"');
            $this->data['delivery_terms_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', '', ' and source_type_id="Purchase"');
            $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', '');
            $this->data['insurance_term_id'] = $this->jCombo('m_insurance_terms_t', 'insurance_term_id', 'insurance_term_name', '');
            $this->data['part_no'] = $this->jCombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '');
            $this->data['default_payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', '');
            $this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', '', ' and source_type_id="Purchase"');
            $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', '');
            $this->data['supplier_site_id'] = $this->jCombo('m_supplier_sites_t', 'supplier_site_id', 'supplier_site_number|supplier_site_name', '');
            $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', '');
            $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', '');
            $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', '');
            $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '', 'purchasequotation');
            $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
            $this->data['tax_group_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
            if ($quotetype == "LABOUR") {
                $this->data['hsn_code'] = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', '');
            } else {
                $this->data['hsn_code'] = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="HSN"');
            }
            $result = $this->location_details($currentloc);
            $result1 = $this->location_details($currentloc);
            $this->data['ship_to_address'] = $result;
            $this->data['bill_to_address'] = $result1;
        }
        /*Edit Quotation*/ else {
            $this->data['id'] = $id;
            $table = \DB::table('p_quotation_hdr_t')->where('quotation_hdr_id', $id)->get();
            $this->data['row'] = $table[0];
            $tablelines = \DB::table('p_quotation_lines_t')->where('quotation_hdr_id', $id)->get();
            $this->data['linedata'] = $tablelines;
            $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', $table[0]->supplier_id);
            $this->data['supplier_site_id'] = $this->jcustomselect('m_supplier_sites_t', 'supplier_site_id', 'supplier_site_number|supplier_site_name', $table[0]->supplier_site_id, ' and supplier_id="' . $table[0]->supplier_id . '"');
            $this->data['delivery_terms_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $table[0]->delivery_terms_id, ' and source_type_id="Purchase"');
            $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $table[0]->payment_term_id);
            $this->data['insurance_term_id'] = $this->jCombo('m_insurance_terms_t', 'insurance_term_id', 'insurance_term_name', $table[0]->insurance_term_id);
            $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);
            $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $table[0]->project_id);
            $this->data['default_payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $table[0]->default_payment_method_id);
            $this->data['ship_to_location_id'] = $this->jcustomselect('m_location_t', 'location_id', 'location_name', $table[0]->ship_to_location_id, 'and location_id in ' . "(" . $location . ")");
            $this->data['bill_to_location_id'] = $this->jcustomselect('m_location_t', 'location_id', 'location_name', $table[0]->bill_to_location_id, 'and location_id in ' . "(" . $location . ")");
            $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);
            $this->data['freight_carrier_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $table[0]->freight_carrier_id, ' and source_type_id="Purchase"');
            $this->data['quote_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $table[0]->quote_pricelist_id, ' and price_list_type="Purchase"');
            /*Get Ship to Bill ADDRESS in Edit*/
            $ship_to_details = DB::table('m_location_t')
                ->join('m_countries_t', 'm_countries_t.country_id', '=', 'm_location_t.country_id')
                ->join('m_states_t', 'm_states_t.state_id', '=', 'm_location_t.state_id')
                ->join('m_cities_t', 'm_cities_t.city_id', '=', 'm_location_t.city_id')
                ->select('m_location_t.location_code', 'm_location_t.address', 'm_location_t.street_name', 'm_location_t.area', 'm_location_t.pincode', 'm_location_t.Phone', 'm_countries_t.country_name', 'm_states_t.state_name', 'm_cities_t.city_name')
                ->where('m_location_t.location_id', $table[0]->ship_to_location_id)
                ->first();


            $bill_to_details = DB::table('m_location_t')
                ->join('m_countries_t', 'm_countries_t.country_id', '=', 'm_location_t.country_id')
                ->join('m_states_t', 'm_states_t.state_id', '=', 'm_location_t.state_id')
                ->join('m_cities_t', 'm_cities_t.city_id', '=', 'm_location_t.city_id')
                ->select('m_location_t.location_code', 'm_location_t.address', 'm_location_t.street_name', 'm_location_t.area', 'm_location_t.pincode', 'm_location_t.Phone', 'm_countries_t.country_name', 'm_states_t.state_name', 'm_cities_t.city_name')
                ->where('m_location_t.location_id', $table[0]->bill_to_location_id)
                ->first();
            if ($ship_to_details != '') {
                $result1 = $ship_to_details->country_name . '-' . $ship_to_details->state_name . '-' . $ship_to_details->city_name . '-' . $ship_to_details->address . '-' . $ship_to_details->street_name . '-' . $ship_to_details->area . '-' . $ship_to_details->pincode;
            } else {
                $result1 = '';
            }

            if ($bill_to_details != '') {
                $result2 = $bill_to_details->country_name . '-' . $bill_to_details->state_name . '-' . $bill_to_details->city_name . '-' . $bill_to_details->address . '-' . $bill_to_details->street_name . '-' . $bill_to_details->area . '-' . $bill_to_details->pincode;
            } else {
                $result2 = '';
            }
            $this->data['ship_to_address'] = $result1;
            $this->data['bill_to_address'] = $result2;
            /*End*/
            $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'concatenated_product', '');
            $this->data['tax_group_id'] = '';
            $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');

            $this->data['productid'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '', 'purchasequotation');
            $this->data['uomcodeid'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
            $this->data['partno'] = $this->jCombocomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '');

            if (count($this->data['linedata']) >= 1) {
                foreach ($this->data['linedata'] as $key => $value) {
                    if ($table[0]->quotation_type != "LABOUR") {
                        $hsn = \DB::select("select hsn_code,product_id,defalut_hsn_code from m_products_t where product_id=" . $value->product_id);
                        if (!empty($hsn)) {
                            $this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->hsn_code, ' and gst_code_hdr_id in(' . $hsn[0]->hsn_code . ')');
                        } else {
                            $this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->hsn_code, 'and classification_name="HSN"');
                        }
                    } else {
                        $this->data['linedata'][$key]->hsn_code = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->hsn_code, '');
                    }
                    if ($value->product_id != '0') {
                        $this->data['linedata'][$key]->product_id = $this->jcustomdataselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'and product_id=' . $value->product_id);
                    } elseif ($table[0]->supplier_id != "0") {
                        $this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '', 'purchasequotation');
                    } else {
                        $this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', '');
                    }

                    if ($value->manufacturer_partno_id != '0') {
                        $this->data['linedata'][$key]->part_no = $this->jcustomselectcomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '', 'and manufacturer_partno_id=' . $value->manufacturer_partno_id);
                    } else {
                        $this->data['linedata'][$key]->part_no = $this->jcustomselectcomp('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', '', '');
                    }
                    if ($value->uom_code_id != '0') {
                        $this->data['linedata'][$key]->uom_code_id = $this->jcustomdataselect('m_uom_codes_t', 'uom_code_id', 'uom_code', '', 'and uom_code_id=' . $value->uom_code_id);
                    } else {
                        $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', '', '');
                    }

                    $this->data['linedata'][$key]->tax_group_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
                    $this->data['linedata'][$key]->unit_price = $value->unit_price;

                    if ($this->data['linedata'][$key]->qty == 0) {
                        $this->data['linedata'][$key]->qty = "";
                    }
                    if (isset($_GET['status']) && $_GET['status'] == 'COPYQUOTE') {
                        $this->data['linedata'][$key]->quotation_line_id = "";
                        $this->data['linedata'][$key]->quotation_hdr_id = "";
                        $this->data['row']->quotation_hdr_id = '';
                        $this->data['copy_quotation_no'] = $this->data['row']->quotation_no;
                        $this->data['row']->quotation_no = '';
                    }
                }

            }

        }

        if (isset($_GET['approve_status'])) {
            $this->data['return_url'] = 'purchasequtoetionapprove';
        } else {
            $this->data['return_url'] = 'purchasequotation';
        }
        $this->data['pageMethod'] = \Request::route()->getName();
        return view('purchasequotation.form', $this->data);
    }

    /* purpose for Save function*/

    public function save(Request $request)
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
            'existing_file','bulk_quotation_line_id'
        ]);
        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');
        $lines_data = $this->validatePost($form, $this->subtable, 'lines');

        /* Purpose for Value Based Approval*/
        if ($data['quote_grand_total'] == "") {
            $data['quote_grand_total'] = 0;
        }
        $approverid = $this->Approvaldatacheck('poquote', $data['quote_grand_total']);
        if ($approverid == "0") {
            $data['approver_id'] = \Session::get('id');
            if ($_POST['quote_status'] != "DRAFT") {
                $data['quote_status'] = "APPROVED";
            }
        } else {
            $data['approver_id'] = $approverid;
        }
        /*End*/
        /*karthigaa Purpose for Auto Number*/
        if ($_POST['quotation_no'] == "") {
            $seqno = $this->Seqnoe('QTN-', 'p_quotation_hdr_t', $_POST['quotation_type'], 'poquote_count');
            $data['quotation_no'] = $seqno[0];
            $data['poquote_count'] = $seqno[1];
        } else {
            $seqno[0] = $_POST['quotation_no'];
        }
        /*End*/

        \DB::beginTransaction();
        try {
            $id = $this->model->insertRow($data);

            $lid = $this->submodel->subgridSave($lines_data, $id);

            /*Purpose For File Attachments*/

            $poquote_id = $id;
            $quote_hdr_id = $request->input('quotation_hdr_id');
            if ($quote_hdr_id == '') {
                if ($request->hasfile('choosefile')) {
                    foreach ($request->file('choosefile') as $file) {
                        $name = $file->getClientOriginalName();
                        $file->move(public_path() . '/Uploads/poquoteattachment/PO' . $poquote_id . '/', $name);
                        $dataupload[] = $name;
                    }
                }
                $attachfile_name = json_encode($dataupload);
                \DB::update("update p_quotation_hdr_t set attachfile_name='" . $attachfile_name . "' where quotation_hdr_id='$poquote_id'");
                $this->data['notymsg'] = "yes";
            } else {
                $existing_file = $request->input('existing_file', '');
                $existing_file = array_filter(explode(',', $existing_file));

                $choose_file = $request->file('choosefile');
                $choose_file = is_array($choose_file) ? $choose_file : [];

                // Fetch DB attachments safely
                $get_attach = DB::table('p_quotation_hdr_t')
                    ->where('quotation_hdr_id', $poquote_id)
                    ->first();

                $db_files = [];

                if (!empty($get_attach->attachfile_name)) {
                    $db_files = json_decode($get_attach->attachfile_name, true);
                }

                $db_files = is_array($db_files) ? $db_files : [];

                /*
                |--------------------------------------------------------------------------
                | CASE 1: No new upload → only delete
                |--------------------------------------------------------------------------
                */
                if (count($choose_file) == 0) {

                    $deleted_files = array_diff($db_files, $existing_file);

                    foreach ($deleted_files as $file) {
                        $path = public_path("Uploads/poquoteattachment/PO{$poquote_id}/{$file}");
                        if (file_exists($path)) {
                            unlink($path);
                        }
                    }

                    DB::update(
                        "update p_quotation_hdr_t set attachfile_name=? where quotation_hdr_id=?",
                        [json_encode($existing_file), $poquote_id]
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | CASE 2: New uploads (with or without existing files)
                |--------------------------------------------------------------------------
                */
                if (count($choose_file) > 0) {

                    $new_files = [];

                    foreach ($choose_file as $file) {
                        $name = $file->getClientOriginalName();
                        $file->move(
                            public_path("Uploads/poquoteattachment/PO{$poquote_id}/"),
                            $name
                        );
                        $new_files[] = $name;
                    }

                    $final_files = array_merge($existing_file, $new_files);

                    DB::update(
                        "update p_quotation_hdr_t set attachfile_name=? where quotation_hdr_id=?",
                        [json_encode($final_files), $poquote_id]
                    );
                }
            }

            /*Purpose For Notifications*/
            if ($_POST['quote_status'] == "APPROVED") {
                $noti_message = "Purchase Quotation " . $_POST['quotation_no'] . " Approved";
                $da = \DB::table('p_quotation_hdr_t')->where('quotation_hdr_id', $_POST['quotation_hdr_id'])->get();
                $this->returnnotification('QUOTATION APPROVAL', $_POST['quotation_hdr_id'], 'purchasequotation', $da[0]->created_by, $noti_message);

                $save_s = "Approved";
                \DB::table('notifications_t')->where('reference_source_id', $_POST['quotation_hdr_id'])->where('reference_source', 'QUOTATION APPROVAL')->update(['read/unread' => 'read']);

            } else if ($_POST['quote_status'] == "INITIATED") {
                $noti_msg = "Purchase Quotation " . $seqno[0] . " Initiated";
                //   $send_notification = $this->sendPopUpNotification($id,"QUOTATION APPROVAL",$noti_msg,'purchasequtoetionapprove');
                $save_s = "Saved";
            } else if ($_POST['quote_status'] == "REJECTED") {
                $noti_message = "Purchase Quotation " . $_POST['quotation_no'] . " Rejected";
                $da = \DB::table('p_quotation_hdr_t')->where('quotation_hdr_id', $_POST['quotation_hdr_id'])->get();
                //  $this->returnnotification('QUOTATION APPROVAL',$_POST['quotation_hdr_id'],'purchasequotation',$da[0]->created_by,$noti_message);
                $save_s = "Rejected";
            } else if ($_POST['quotation_hdr_id'] == "") {
                $save_s = "Saved";
            } else {
                $save_s = "Updated";
            }

            /* End Purpose For Notifications*/
            /**Auditlog**/
            if ($_POST['quotation_hdr_id'] == "") {
                $action = "create";
            } else {
                $action = "edit";
            }
            $this->auditlog($id, "purchasequotation", $action, $_POST, "p_quotation_hdr_t");
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Purchase Quote ' . $save_s . ' Successfully', 'id' => $id, 'lid' => $lid, 'auto_no' => $seqno[0]));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            \DB::rollback();
            //   dd($dbCode);
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }

    /*End function*/
    /* purpose for Display hdr & Lines View function*/

    public function getproductpvscost($id)
    {

        //dd($id);
        $html = '';
        $comp = \Session::get('companyid');
        /*                 $data1=DB::table("p_po_invoice_lines_t")
                         ->leftjoin('p_po_invoice_hdr_t', 'p_po_invoice_lines_t.po_invoice_id', '=', 'p_po_invoice_hdr_t.po_invoice_id')
                         ->leftjoin('p_quotation_lines_t', 'p_po_invoice_lines_t.product_id', '=', 'p_quotation_lines_t.product_id')
                         ->leftjoin('m_supplier_t', 'p_po_invoice_hdr_t.supplier_id', '=', 'm_supplier_t.supplier_id')
                         ->leftjoin('m_products_t', 'p_quotation_lines_t.product_id', '=', 'm_products_t.product_id')
                         ->select('m_products_t.concatenated_product as prd_name','m_supplier_t.supplier_name','p_po_invoice_hdr_t.supplier_invoice_no','p_po_invoice_hdr_t.supplier_invoice_date','p_po_invoice_lines_t.unit_price as pvs_cost','p_quotation_lines_t.unit_price as quoted_cost')->where('p_quotation_lines_t.product_id',$id)->orderBy('p_po_invoice_lines_t.po_invoice_lines_id','DESC LIMIT 3' )->get();
                        if(count($data1)>1){
                        $html.= "<table class='sub_class' style='display:block;width:100%;overflow-y:auto;'>";
                        $html .= "<thead style='background:#00224e;color:#FFF'><th >Product</th><th >Supplier</th><th>Invoice No</th><th>Invoice Date</th><th style='width:150px;'>Previous Cost</th><th style=''>Current Quote</th></thead><tbody  class='subinv_class_body'>";     

                        }
                        else
                        {
                            $html.='No Previous Purchase';
                        }
                         */

        /*    $data1 = DB::SELECT("SELECT
            p_po_invoice_hdr_t.supplier_id,
            m_supplier_t.supplier_name,
            p_po_invoice_hdr_t.supplier_invoice_no,
            p_po_invoice_hdr_t.supplier_invoice_date,
            p_po_invoice_lines_t.product_id,
            m_products_t.concatenated_product as prd_name,
            p_po_invoice_lines_t.unit_price as pvs_cost,
            p_po_invoice_lines_t.qty as inv_qty,
            p_quotation_lines_t.unit_price as quoted_cost

        FROM
            `p_po_invoice_lines_t`
        LEFT JOIN p_po_invoice_hdr_t ON p_po_invoice_lines_t.po_invoice_id = p_po_invoice_hdr_t.po_invoice_id
        LEFT JOIN p_quotation_lines_t ON p_po_invoice_lines_t.product_id = p_quotation_lines_t.product_id
        LEFT JOIN m_supplier_t ON p_po_invoice_hdr_t.supplier_id = m_supplier_t.supplier_id
        LEFT JOIN m_products_t ON p_quotation_lines_t.product_id = m_products_t.product_id

        WHERE
            p_quotation_lines_t.product_id = $id

        GROUP BY p_po_invoice_hdr_t.bill_number

        ORDER BY
            p_po_invoice_lines_t.po_invoice_lines_id
        DESC
        LIMIT 3;");*/

        $data1 = DB::SELECT("select * from (SELECT
        p_po_invoice_hdr_t.supplier_id as supplier_id,
        m_supplier_t.supplier_name as supplier_name,
        p_po_invoice_hdr_t.supplier_invoice_no as supplier_invoice_no,
        p_po_invoice_hdr_t.supplier_invoice_date as supplier_invoice_date,
        p_po_invoice_lines_t.product_id as product_id,
        m_products_t.concatenated_product AS prd_name,
        p_po_invoice_lines_t.unit_price AS pvs_cost,
        p_po_invoice_lines_t.qty AS inv_qty,
        p_quotation_lines_t.unit_price AS quoted_cost
    FROM
        p_po_invoice_lines_t
    LEFT JOIN p_po_invoice_hdr_t ON p_po_invoice_lines_t.po_invoice_id = p_po_invoice_hdr_t.po_invoice_id
    LEFT JOIN p_quotation_lines_t ON p_po_invoice_lines_t.product_id = p_quotation_lines_t.product_id
    LEFT JOIN m_supplier_t ON p_po_invoice_hdr_t.supplier_id = m_supplier_t.supplier_id
    LEFT JOIN m_products_t ON p_quotation_lines_t.product_id = m_products_t.product_id
    WHERE
        p_quotation_lines_t.product_id = $id
    GROUP BY
        p_po_invoice_hdr_t.bill_number
    ORDER BY
        p_po_invoice_lines_t.po_invoice_lines_id DESC
    ) v
    
UNION ALL

(SELECT
        p_po_invoice_hdr_t_bk.supplier_id as supplier_id,
        m_supplier_t.supplier_name as supplier_name,
        p_po_invoice_hdr_t_bk.supplier_invoice_no as supplier_invoice_no,
        p_po_invoice_hdr_t_bk.supplier_invoice_date as supplier_invoice_date,
        p_po_invoice_lines_t_bk.product_id as product_id,
        m_products_t.concatenated_product AS prd_name,
        p_po_invoice_lines_t_bk.unit_price AS pvs_cost,
        p_po_invoice_lines_t_bk.qty AS inv_qty,
        p_quotation_lines_t.unit_price AS quoted_cost
    FROM
        p_po_invoice_lines_t_bk
    LEFT JOIN p_po_invoice_hdr_t_bk ON p_po_invoice_lines_t_bk.po_invoice_id = p_po_invoice_hdr_t_bk.po_invoice_id
    LEFT JOIN p_quotation_lines_t ON p_po_invoice_lines_t_bk.product_id = p_quotation_lines_t.product_id
    LEFT JOIN m_supplier_t ON p_po_invoice_hdr_t_bk.supplier_id = m_supplier_t.supplier_id
    LEFT JOIN m_products_t ON p_quotation_lines_t.product_id = m_products_t.product_id
    WHERE
        p_quotation_lines_t.product_id = $id
    GROUP BY
        p_po_invoice_hdr_t_bk.bill_number
    ORDER BY
        p_po_invoice_lines_t_bk.po_invoice_lines_id DESC)
    LIMIT 0,3 ");

        //dd($data1);
        if (count($data1) >= 1) {
            //dd($data1);
            $html .= "<table class='table table-bordered ' style='display:block;width:100%;overflow-y:auto;'>";
            $html .= "<thead style='background:#00224e;color:#FFF'><th >Product</th><th >Supplier</th><th>Invoice No</th><th>Invoice Date</th><th style='width:150px;'>Previous Cost</th><th style='width:150px;'>Invoice Qty</th></thead><tbody  class='subinv_class_body'>";
            foreach ($data1 as $k => $v) {
                $html .= "<tr>";
                $html .= "<td>" . $v->prd_name . "</td>";
                $html .= "<td>" . $v->supplier_name . "</td>";
                $html .= "<td>" . $v->supplier_invoice_no . "</td>";
                $html .= "<td>" . $v->supplier_invoice_date . "</td>";
                $html .= "<td>" . $v->pvs_cost . "</td>";
                $html .= "<td>" . $v->inv_qty . "</td>";
                //$html.= "<td>".$v->quoted_cost."</td>";
                $html .= "</tr>";
            }
        } else {
            $html .= 'No Previous Purchase';
        }

        //dd($data1[0]->supplier_name);
        return $html;
        //return $data1;
    }

    public function show(Request $request, $id = null, $msg = null)
    {

        $returnUrl = $request->query('return', 'purchasequotation');
        $this->data['pageModule'] = url($returnUrl);

        if (isset($id)) {
            $vdata = \DB::table('p_quotation_hdr_t')->select(
                'p_quotation_hdr_t.*',
                'm_supplier_t.supplier_name',
                'm_supplier_sites_t.supplier_site_name',
                'm_organizations_t.organization_name',
                'i_pricelist_hdr_t.pricelist_name',
                'm_projects_t.project_name',
                'm_frieghtcarriers_hdr_t.carrier_name',
                'm_payment_methods_t.payment_method_name',
                'm_payment_terms_t.payment_term_name',
                'm_delivery_terms_t.delivery_term_name',
                'm_insurance_terms_t.insurance_term_name',
                'bill.location_name as bill_location',
                'm_location_t.location_name',
                'tb_users.username'
            )
                ->leftjoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_quotation_hdr_t.supplier_id')
                ->leftjoin('m_supplier_sites_t', 'm_supplier_sites_t.supplier_site_id', '=', 'p_quotation_hdr_t.supplier_site_id')
                ->leftjoin('m_organizations_t', 'm_organizations_t.organization_id', '=', 'p_quotation_hdr_t.organization_id')
                ->leftjoin('i_pricelist_hdr_t', 'i_pricelist_hdr_t.pricelist_hdr_id', '=', 'p_quotation_hdr_t.quote_pricelist_id')
                ->leftjoin('m_projects_t', 'm_projects_t.project_id', '=', 'p_quotation_hdr_t.project_id')
                ->leftjoin('m_frieghtcarriers_hdr_t', 'm_frieghtcarriers_hdr_t.ar_frieghtcarriers_hdr_id', '=', 'p_quotation_hdr_t.freight_carrier_id')
                ->leftjoin('m_payment_methods_t', 'm_payment_methods_t.payment_method_id', '=', 'p_quotation_hdr_t.default_payment_method_id')
                ->leftjoin('m_payment_terms_t', 'm_payment_terms_t.payment_term_id', '=', 'p_quotation_hdr_t.payment_term_id')
                ->leftjoin('m_delivery_terms_t', 'm_delivery_terms_t.delivery_terms_id', '=', 'p_quotation_hdr_t.delivery_terms_id')
                ->leftjoin('m_insurance_terms_t', 'm_insurance_terms_t.insurance_term_id', '=', 'p_quotation_hdr_t.insurance_term_id')
                ->leftjoin('m_location_t as bill', 'bill.location_id', '=', 'p_quotation_hdr_t.bill_to_location_id')
                ->leftjoin('m_location_t', 'm_location_t.location_id', '=', 'p_quotation_hdr_t.ship_to_location_id')
                ->leftjoin('tb_users', 'tb_users.id', '=', 'p_quotation_hdr_t.created_by')
                ->where('quotation_hdr_id', $id)->get();

            $this->data['quotation_no'] = $vdata[0]->quotation_no;
            $quote_date = $vdata[0]->quotation_date;
            $this->data['quotation_date'] = date(\Session::get('p_date_format'), strtotime($quote_date));
            $supplier_quote_date = $vdata[0]->supplier_quotation_date;
            $this->data['supplier_quotation_date'] = date(\Session::get('p_date_format'), strtotime($supplier_quote_date));
            $this->data['payment_method_name'] = $vdata[0]->payment_method_name;
            $this->data['payment_term_name'] = $vdata[0]->payment_term_name;
            $this->data['delivery_term_name'] = $vdata[0]->delivery_term_name;
            $this->data['insurance_term_name'] = $vdata[0]->insurance_term_name;
            $this->data['quotation_type'] = $vdata[0]->quotation_type;
            $this->data['quote_status'] = $vdata[0]->quote_status;
            $this->data['supplier_ref_no'] = $vdata[0]->supplier_ref_no;
            $delivery_date = $vdata[0]->delivery_date;
            $this->data['delivery_date'] = date(\Session::get('p_date_format'), strtotime($delivery_date));
            $this->data['supplier_name'] = $vdata[0]->supplier_name;
            $this->data['supplier_site_name'] = $vdata[0]->supplier_site_name;
            $this->data['carrier_name'] = $vdata[0]->carrier_name;
            $this->data['pricelist_name'] = $vdata[0]->pricelist_name;
            $this->data['project_name'] = $vdata[0]->project_name;
            $this->data['organization_name'] = $vdata[0]->organization_name;
            $this->data['remarks'] = $vdata[0]->remarks;
            $this->data['unloading_charges'] = $vdata[0]->unloading_charges;
            $this->data['transport_charges'] = $vdata[0]->transport_charges;
            $this->data['insurance_charges'] = $vdata[0]->insurance_charges;
            $this->data['packing_charges'] = $vdata[0]->packing_charges;
            $this->data['bill_to_location'] = $vdata[0]->bill_location;
            $this->data['ship_to_location'] = $vdata[0]->location_name;
            $this->data['other_frieght_amount'] = $vdata[0]->other_frieght_amount;
            $this->data['other_tax_amount'] = $vdata[0]->other_tax_amount;
            $result = $this->location_details($vdata[0]->ship_to_location_id);
            $result1 = $this->location_details($vdata[0]->bill_to_location_id);
            $this->data['ship_to_address'] = $result;
            $this->data['bill_to_address'] = $result1;


            $this->data['source'] = $vdata[0]->source;
            $this->data['reference_number'] = $vdata[0]->reference_number;
            $this->data['quote_tax_total'] = $vdata[0]->quote_tax_total;
            $this->data['created_by'] = $vdata[0]->username;
            $this->data['attachements'] = $vdata[0]->attachfile_name;
            $this->data['row_id'] = $id;
            $this->data['quote_grand_total'] = $vdata[0]->quote_grand_total;
            $a = \DB::table('p_quotation_lines_t')->where('quotation_hdr_id', $id)->get();
            $vlinesdata = \DB::table('p_quotation_lines_t')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'p_quotation_lines_t.product_id')
                ->leftjoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'p_quotation_lines_t.uom_code_id')
                ->leftjoin('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'p_quotation_lines_t.tax_group_id')
                ->leftjoin('f_gst_code_hdr_t', 'f_gst_code_hdr_t.gst_code_hdr_id', '=', 'p_quotation_lines_t.hsn_code')
                ->leftjoin('m_manufacturer_partno_t', 'm_manufacturer_partno_t.manufacturer_partno_id', '=', 'p_quotation_lines_t.manufacturer_partno_id')
                ->where('p_quotation_lines_t.quotation_hdr_id', $id)->get();

            $this->data['vlinesdata'] = $vlinesdata;
            $this->data['uom_code'] = $vlinesdata[0]->uom_code;
            $this->data['tax_group_name'] = $vlinesdata[0]->tax_group_name;
            $this->data['part_no'] = $vlinesdata[0]->part_no;


            $this->data['classification_code'] = $vlinesdata[0]->classification_code;
            if ($msg != null) {
                return $this->data;
            } else {
                $this->data['url'] = $_GET['return'];
                return view('purchasequotation.view', $this->data);
            }

        }
    }


    /* purpose for delete function */

    public function delete(Request $request, $id = null, $type = null)
    {
        $source = "PO";
        $column = array('reference_id');
        $table = array('p_po_hdr_t');
        $column1 = array('source');

        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $id)->where($column1[$i], $source)->get();
            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }
        if ($j == 0) {
            Purchasequotation::destroy($id);
            $query = \DB::table('p_quotation_lines_t')->where('quotation_hdr_id', $id)->delete();
            /**Auditlog**/
            $action = "Delete";
            $this->auditlog($id, "purchasequotation", $action, $id, "p_quotation_hdr_t");
        }
        return $j;
    }


    /*  purpose for Get Price for Supplier function */
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
            return $this->data['product_id'];    //dd($this->data['productid']);
        } else {
            return 0;
        }
    }

    /*  purpose To Get Supplier Details function */
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
    /*  purpose To Get Supplier Price function */
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

    /* Purpose For Get Bill To & Ship To Location Address*/
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
    /*END*/
    /* Purpose For Attachment*/
    public function poquoteattachment(Request $request)
    {
        $quoteid = $_POST['quoteid'];
        if ($request->hasfile('choosefile')) {
            foreach ($request->file('choosefile') as $file) {
                $name = $file->getClientOriginalName();
                $file->move(public_path() . '/Uploads/poquoteattachment/POQUOTE' . $quoteid . '/', $name);
                $data[] = $name;
            }
        }
        $datas = \DB::select("select attachfile_name from p_quotation_hdr_t where quotation_hdr_id='$quoteid'");
        if ($datas[0]->attachfile_name != "") {
            $datas = json_decode($datas[0]->attachfile_name);
            $result = array_diff($datas, $_POST['file']);
            foreach ($result as $k => $v) {
                @unlink(public_path() . '/Uploads/poquoteattachment/POQUOTE' . $quoteid . '/', $v);
            }
            $data = array_merge($_POST['file'], $data);
        }
        $attachfile_name = json_encode($data);
        \DB::update("update p_quotation_hdr_t set attachfile_name='" . $attachfile_name . "' where quotation_hdr_id='$quoteid'");
        $this->data['notymsg'] = "yes";
        return redirect('purchasequotation');
    }
    /* Purpose For Attachment View*/
    public function poquoteattachmentdata($id)
    {
        $data = \DB::select("select attachfile_name from p_quotation_hdr_t where quotation_hdr_id='$id'");

        if (!empty($data)) {
            $data = json_decode($data[0]->attachfile_name);
            return $data;
        }

    }
}