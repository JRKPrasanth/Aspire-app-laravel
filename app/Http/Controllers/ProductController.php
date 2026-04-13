<?php

namespace App\Http\Controllers;

use App\Product;
use App\Qualityproductspechdr;
use App\Qualityproductspeclines;
use Illuminate\Http\Request;
use Redirect, DB;
use File;
use Config;
use session;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{

    public $module = "product";

    public function __construct()
    {
        $this->data['urlmenu'] = $this->indexs();
        $this->table = "m_products_t";
        $this->spechdrtable = "i_quality_product_specs_hdr_t";
        $this->speclinestable = "i_quality_product_specs_lines_t";
        $this->specmodel = new Qualityproductspechdr;
        $this->specsubmodel = new Qualityproductspeclines;
        $this->model = new Product;
        $this->data['pageModule'] = \Request::route()->getName();
        $this->data['pageMethod'] = \Request::route()->getName();
    }


    /*Index Function For loading table*/
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

        $table = \DB::table('m_products_t')->get();
        $this->data['datas'] = $table;
        $this->data['group'] = $this->jqgridselect('m_product_groups_t', 'product_group_id', 'group_name');
        $this->data['hsn_code'] = $this->jqgridselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code');
        $this->data['Defalut_hsn_code'] = $this->jqgridselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code');
        $this->data['cat'] = $this->jqgridselect('m_product_category_t', 'product_category_id', 'category_name');
        $this->data['uom'] = $this->jqgridselect('m_uom_codes_t', 'uom_code_id', 'uom_code');
        $this->data['pageMethod'] = \Request::route()->getName();

        return view('product.table', $this->data);
    }



    public function ProductgridData()
    {
        $wh = '';
        $wh1 = '';

        $app_id = \Session::get('id');


        if ($_GET['pagemethod'] == 'productapproval') {
            $wh1 .= " and m_products_t.product_status='INITIATED' and json_contains(m_products_t.approver_id ,'" . $app_id . "')=1 ";
        }

        $comp = \Session::get('companyid');


        $SQL = "select * from(SELECT m_products_t.tax_credit,m_products_t.hsn_code,m_products_t.choosefile,m_products_t.product_id,m_products_t.product_status,
    m_products_t.qc_check,m_products_t.active,m_products_t.batch_no,m_products_t.product_classification,m_product_groups_t.group_name,m_products_t.product_group_id,m_products_t.concatenated_product,tb_users.username,tb_users.first_name,
    m_subinventory_t.subinventory_name,m_products_t.min_order_qty,m_sublocators_t.locator_code,m_products_t.max_order_qty,m_products_t.re_order_level,f_gst_code_hdr_t.classification_code,
    m_products_t.product_alternate_name,m_products_t.product_code,m_uom_codes_t.uom_code,m_uom_codes_t.uom_code as trx_code,m_products_t.primary_uom_id,m_product_category_t.category_name,
    trxuomtbl.uom_code as trx_uom_id,m_product_subcategory_t.subcategory_name,i_quality_product_specs_hdr_t.quality_product_specs_hdr_id,(select count(m_product_image_t.product_image_id ) as count from m_product_image_t where m_product_image_t.product_id=m_products_t.product_id )as imgcount from m_products_t
    left join m_uom_codes_t on(m_products_t.primary_uom_id=m_uom_codes_t.uom_code_id) left join m_product_category_t on (m_products_t.product_category_id=m_product_category_t.product_category_id)
    left join f_gst_code_hdr_t on (m_products_t.defalut_hsn_code=f_gst_code_hdr_t.gst_code_hdr_id) left join m_product_groups_t on (m_products_t.product_group_id=m_product_groups_t.product_group_id)
    left join m_product_subcategory_t on (m_products_t.product_subcategory_id=m_product_subcategory_t.product_subcategory_id) left join m_uom_codes_t as trxuomtbl on 
    m_products_t.trx_uom_id=trxuomtbl.uom_code_id left join i_quality_product_specs_hdr_t on(i_quality_product_specs_hdr_t.product_id=m_products_t.product_id) left join
    `tb_users` on (tb_users.id=m_products_t.created_by)
left join m_subinventory_t on(m_subinventory_t.subinventory_id=m_products_t.subinventory_id) left join m_sublocators_t on
(m_sublocators_t.sublocator_id=m_products_t.sublocator_id)where 1=1 and m_products_t.company_id=$comp $wh1) as v1 where 1=1 $wh ORDER by v1.product_id DESC";

        $result = \DB::select($SQL);
        foreach ($result as $key => $val) {
            $code = explode(",", $val->hsn_code);
            $hsncode = \DB::table('f_gst_code_hdr_t')->whereIn('gst_code_hdr_id', $code)->get();
            $multihsn = "";
            foreach ($hsncode as $k => $v) {
                $multihsn .= $v->classification_code . ",";
            }
            $multihsn1 = trim($multihsn, ",");

            $result[$key]->gstcode = $multihsn1;
        }

        return DataTables::of($result)->make(true);
    }


    /*Create Function*/
    public function create($id = null)
    {

        $product = Product::find($id); //dd($product);
        $this->data['pageModule'] = "product";
        $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
        if (isset($id)) {

            $this->data['return_url'] = \Request::route()->getName();

            $this->data['edit_url'] = 'update';
            $this->data['pagemode'] = 'edit';
            $this->data['productdata'] = $product;
            $this->data['product_id'] = $this->jcombo("m_products_t", "product_id", "concatenated_product", "");
            $this->data['product_id'] = $this->data['productdata']->product_id;
            $this->data['parent_id'] = $this->data['productdata']->parent_id;
            $this->data['choosefile'] = $this->data['productdata']->choosefile;
            // dd($this->data['productdata']->variant_group_id);
            if ($product['min_order_qty'] == 0) {
                $product['min_order_qty'] = '';
            }
            if ($product['min_stock_level2'] == 0) {
                $product['min_stock_level2'] = '';
            }
            if ($product['min_stock_level3'] == 0) {
                $product['min_stock_level3'] = '';
            }
            if ($product['max_order_qty'] == 0) {
                $product['max_order_qty'] = '';
            }

            $this->data['product_group_id'] = $this->jcombo('m_product_groups_t', 'product_group_id', 'group_name', $this->data['productdata']->product_group_id);

            $pgrp = \DB::select("select group_name from m_product_groups_t where product_group_id='" . $this->data['productdata']->product_group_id . "'");
            if (count($pgrp) > 0)
                $this->data['group_name'] = $pgrp[0]->group_name;
            else
                $this->data['group_name'] = '';

            $this->data['product_category_id'] = $this->jCombo('m_product_category_t', 'product_category_id', 'category_name', $this->data['productdata']->product_category_id);
            $this->data['product_subcategory_id'] = $this->jCombo('m_product_subcategory_t', 'product_subcategory_id', 'subcategory_name', $this->data['productdata']->product_subcategory_id);
            $prdcat = \DB::select("select category_name from m_product_category_t where product_category_id='" . $this->data['productdata']->product_category_id . "'");
            if (count($prdcat) > 0)
                $this->data['category_name'] = $prdcat[0]->category_name;
            else
                $this->data['category_name'] = '';

            $this->data['product_type_id'] = $this->jCombo('m_product_type_t', 'product_type_id', 'product_type', $this->data['productdata']->product_type_id);
            //dd($this->data['product_type_id ']);

            $this->data['primary_uom_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $this->data['productdata']->primary_uom_id);
            $this->data['trx_uom_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $this->data['productdata']->trx_uom_id);
            $this->data['hsn_code'] = $this->jcustommultiselect1('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $this->data['productdata']->hsn_code, 'and classification_name="HSN"');
            $this->data['defalut_hsn_code'] = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $this->data['productdata']->defalut_hsn_code, 'and classification_name="HSN"');
            $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $this->data['productdata']->account_code_id);
            $this->data['control_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $this->data['productdata']->control_account_id);
            $this->data['disc_account_code'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $this->data['productdata']->disc_account_code);
            $this->data['subinventory_id'] = $this->jCombo('m_subinventory_t', 'subinventory_id', 'subinventory_name', $this->data['productdata']->subinventory_id);

            $this->data['sublocator_id'] = $this->jCombo('m_sublocators_t', 'sublocator_id', 'locator_code', $this->data['productdata']->sublocator_id);

            $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $this->data['productdata']->organization_id);

            $this->data['product_variant_id'] = $this->jCombo('m_product_variants_t', 'product_variant_id', 'product_variant_name', $this->data['productdata']->product_variant_id);


            $this->data['pro_varient_grp'] = $this->jCombo('m_product_variants_t', 'product_variant_id', 'product_variant_name', $this->data['productdata']->variant_group_id);
            // dd($this->data['pro_varient_grp']);
            $this->data['packing_ctn_box'] = $this->jcustomselect('m_products_t', 'product_id', 'concatenated_product', $this->data['productdata']->packing_ctn_box, " and product_group_id='3'");

            if ($this->data['productdata']->product_group_id == 1 || $this->data['productdata']->product_group_id == 4) {
                $this->data['product_classification'] = $this->jcustomselect('a_lookuplines_t', 'lookup_meaning', 'lookup_meaning', $this->data['productdata']->product_classification, 'and lookup_type="PRODUCT_CLASSIFICATION"');
            } else {
                $this->data['product_classification'] = $this->jCombo('m_product_category_t', 'category_name', 'category_name', $this->data['productdata']->product_classification);
            }
            $this->data['group_classification'] = $this->jcustomselect('a_lookuplines_t', 'lookup_meaning', 'lookup_meaning', $this->data['productdata']->group_classification, 'and lookup_type="GROUP_CLASSIFICATION"');

            $this->data['product_pack_id'] = $this->jCombo('i_product_packs', 'packing_id', 'pack_name', $this->data['productdata']->product_pack_id);
            $this->data['product_packtype_id'] = $this->jCombo('i_product_packs_types_t', 'product_packs_type_id', 'product_pack_type_name', $this->data['productdata']->product_packtype_id);
            //dd($this->data);

            return view('product.form', $this->data);
        } else {
            $this->data['return_url'] = \Request::route()->getName();


            $this->data['edit_url'] = 'create';

            //dd($_POST);
            $this->data['group_name'] = '';
            $this->data['category_name'] = '';
            $this->data['product_id'] = $this->jcombo("m_products_t", "product_id", "concatenated_product", "");

            $this->data['product_type_id'] = $this->jCombo('m_product_type_t', 'product_type_id', 'product_type', '');
            //$this->data['product_group_id'] = $this->jcombo('m_product_groups_t','product_group_id','group_name','');
            $groupname = \Session::get('groupname');
            //dd($groupname);
            if ($groupname == "7") {
                $this->data['product_group_id'] = $this->jCombo_purchase('m_product_groups_t', 'product_group_id', 'group_name', '');
            } else if ($groupname == "11") {
                $this->data['product_group_id'] = $this->jCombo_logistics('m_product_groups_t', 'product_group_id', 'group_name', '');
            } else if ($groupname == "8") {
                $this->data['product_group_id'] = $this->jCombo_production('m_product_groups_t', 'product_group_id', 'group_name', '');
            } else {
                $this->data['product_group_id'] = $this->jcombo('m_product_groups_t', 'product_group_id', 'group_name', '');
            }
            $this->data['product_category_id'] = $this->jCombo('m_product_category_t', 'product_category_id', 'category_name', '');
            $this->data['product_subcategory_id'] = $this->jCombo('m_product_subcategory_t', 'product_subcategory_id', 'subcategory_name', '');
            $this->data['primary_uom_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
            $this->data['trx_uom_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');
            $this->data['hsn_code'] = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="HSN"');
            $this->data['defalut_hsn_code'] = $this->jcustomselect('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="HSN"');
            $this->data['account_code_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
            $this->data['control_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
            $this->data['disc_account_code'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
            $this->data['subinventory_id'] = $this->jCombo('m_subinventory_t', 'subinventory_id', 'subinventory_name', '');
            $this->data['product_classification'] = $this->jcustomselect('a_lookuplines_t', 'lookup_meaning', 'lookup_meaning', '', 'and lookup_type="PRODUCT_CLASSIFICATION"');
            $this->data['group_classification'] = $this->jcustomselect('a_lookuplines_t', 'lookup_meaning', 'lookup_meaning', '', 'and lookup_type="GROUP_CLASSIFICATION"');
            $this->data['sublocator_id'] = $this->jCombo('m_sublocators_t', 'sublocator_id', 'locator_code', '');


            $this->data['product_variant_id'] = $this->jCombo('m_product_variants_t', 'product_variant_id', 'product_variant_name', '');
            $this->data['pro_varient_grp'] = $this->jcustomselect('m_product_variants_t', 'variant_group_id', 'product_variant_name', '', 'and variant_group_id !="0"');
            $this->data['product_pack_id'] = $this->jCombo('i_product_packs', 'packing_id', 'pack_name', '');
            $this->data['product_packtype_id'] = $this->jCombo('i_product_packs_types_t', 'product_packs_type_id', 'product_pack_type_name', '');
            $this->data['parent_id'] = '';

            $this->data['packing_ctn_box'] = $this->jcustomselect('m_products_t', 'product_id', 'concatenated_product', '', " and product_group_id='3'");

            $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', '');
            $products = \DB::connection()->getSchemaBuilder()->getColumnListing("m_products_t");

            $product = array();
            foreach ($products as $key => $val) {
                $product[$val] = "";
            }

            $this->data['productdata'] = $product;
            $this->data['products'] = count($products);
            //dd($this->data['productdata']);
            $this->data['product_id'] = '';

            $this->data['pageMethod'] = 'product';
            $this->data['pagemode'] = 'create';

            return view('product.form', $this->data);
        }
    }
    /*End*/


    /*Product Save FUnction*/
    public function save(Request $request)
    {

        $id = '';
        $this->modelname = new Product();

        $primary = self::findPrimarykey('m_products_t');

        /*code purpose for subassembly data store on subassembly table from product creation*/
        $prdsgm1 = $_POST['product_type_id'];
        $prdsgm2 = $_POST['product_variant_id'];
        $sub_asdata = "dfg";

        $subassemblychk = \DB::select("select product_sub_assembly_id,sub_assembly_concate from i_product_subassembly_t where sub_assembly_concate='$sub_asdata'");
        if (!empty($subassemblychk)) {
            $this->data['sub_assembly_concate'] = $subassemblychk[0]->sub_assembly_concate;
            $sbasmblyid = $this->data['product_sub_assembly_id'] = $subassemblychk[0]->product_sub_assembly_id;
        } else {
            $subassmplydata = \DB::table('i_product_subassembly_t')->insertGetId(['product_segment1' => $prdsgm1, 'product_segment2' => $prdsgm2, 'sub_assembly_concate' => $sub_asdata]);
            $sbasmblyid = $subassmplydata;
        }

        try {
            $data['product_status'] = $_POST['product_status'];
            if ($_POST['product_status'] == 'INITIATED') {
                $t = 0;
                $approverid = $this->Approvaldatacheck('product', $t);
                //	dd($approverid);
                if ($approverid == "0") {

                    $data['approver_id'] = \Session::get('id');
                    $data['product_status'] = "APPROVED";
                } else {

                    $data['approver_id'] = $approverid;

                }
            }

            $data['product_id'] = $_POST['product_id'];
            $data['product_group_id'] = $_POST['product_group_id'];
            $data['product_category_id'] = $_POST['product_category_id'];
            $data['product_subcategory_id'] = $_POST['product_subcategory_id'];
            $data['product_type_id'] = $_POST['product_type_id'];

            if ($_POST['product_group_id'] == 4) {
                $data['product_classification'] = $_POST['po_class'];
                $data['expiry_days'] = $_POST['expiry_days_sfg'];
                $data['product_expiry_days'] = $_POST['expiry_days_sfg'];
                $data['variant_group_id'] = $_POST['product_var_grp'];
            } else if ($_POST['product_group_id'] == 1) {
                $data['product_classification'] = $_POST['po_class'];
                $data['variant_group_id'] = $_POST['product_var_grp'];
            } else {
                $cat_id = $_POST['product_category_id'];
                $prd_cat = \DB::select("SELECT category_name FROM m_product_category_t WHERE product_category_id ='$cat_id'");
                $category_name = $prd_cat[0]->category_name;
                $data['product_classification'] = $category_name;
            }

            $data['product_variant_id'] = $_POST['product_variant_id'];
            $data['product_packtype_id'] = $_POST['product_packtype_id'];
            $data['qc_type'] = $_POST['qc_type'];
            $data['qc_check'] = $_POST['qc_check'];
            $data['qc_no_of_days'] = $_POST['qc_no_of_days'];
            $data['active'] = $_POST['active'];
            $data['barcode_number'] = $_POST['barcode_no'];
            $data['group_classification'] = $_POST['group_classification'];
            $data['gross_weight'] = $_POST['gross_weight'];
            $data['net_weight'] = $_POST['net_weight'];

            if ($_POST['linescheck'] == '1') {
                $prdid = $_POST['product_id'];
                if ($prdid != '') {
                    $del = \DB::select("delete from m_products_t where parent_id='" . $_POST['product_id'] . "'");
                }

                for ($i = 0; $i < count($_POST['counter']); $i++) {

                    $data['product_id'] = $_POST['bulk_product_id'][$i];
                    $data['product_code'] = $_POST['bulk_product_code'][$i];
                    $data['product_pack_id'] = $_POST['bulk_product_pack_id'][$i];
                    $data['concatenated_product'] = $_POST['bulk_concatenated_product'][$i];
                    $data['defalut_hsn_code'] = $_POST['bulk_defalut_hsn_code'][$i];
                    $data['primary_uom_id'] = $_POST['bulk_primary_uom_id'][$i];
                    $data['trx_uom_id'] = $_POST['bulk_trx_uom_id'][$i];
                    $data['subinventory_id'] = $_POST['bulk_subinventory_id'][$i];
                    $data['sublocator_id'] = $_POST['bulk_sublocator_id'][$i];
                    $data['account_code_id'] = $_POST['bulk_account_code_id'][$i];
                    $data['control_account_id'] = $_POST['bulk_control_account_id'][$i];
                    $data['disc_account_code'] = $_POST['bulk_disc_account_code'][$i];
                    $data['tax_credit'] = $_POST['bulk_tax_credit'][$i];
                    $data['company_id'] = \Session::get('companyid');
                    $data['location_id'] = \Session::get('location');
                    $data['last_updated_by'] = \Session::get('id');
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    if ($_POST['locator_control'] != "") {
                        $data['locator_control'] = $_POST['locator_control'];
                    } else {
                        $data['locator_control'] = "";
                    }
                    if (isset($_POST['bulk_hsn_code' . $i]))
                        $data['hsn_code'] = implode(",", $_POST['bulk_hsn_code' . $i]);
                    else
                        $data['hsn_code'] = '';
                    /* code for lines level additional details*/
                    $adddetails = $_POST['bulk_adddetails'][$i];
                    if ($_POST['bulk_adddetails'][$i] != "") {
                        $explodata = explode(',', $adddetails);
                        $data['min_order_qty'] = $explodata[0];
                        $data['min_stock_level2'] = $explodata[1];
                        $data['min_stock_level3'] = $explodata[2];
                        $data['expiry_days'] = $explodata[3];

                        $data['max_order_qty'] = $explodata[4];
                        $data['mpq_qty'] = $explodata[5];
                        $data['packing_ctn_box'] = $explodata[6];

                        $data['product_sub_assembly_id'] = $sbasmblyid;

                    } else {
                        $data['min_order_qty'] = '';
                        $data['min_stock_level2'] = '';
                        $data['min_stock_level3'] = '';
                        $data['expiry_days'] = '';

                        $data['max_order_qty'] = '';
                        $data['mpq_qty'] = '';
                        $data['packing_ctn_box'] = '';

                        $data['product_sub_assembly_id'] = $sbasmblyid;
                    }

                    $id = \DB::table('m_products_t')->insertGetId($data);

                    if ($_POST['product_id'] == '') {
                        // dd($request->hasfile('choosefile'));
                        if ($request->hasfile('choosefile')) {
                            foreach ($request->file('choosefile') as $file) {
                                $name = $file->getClientOriginalName();
                                $date = date('Y-m-d H:i:s');
                                $name = $name . '&' . $date;
                                $dataim['choosefile'] = $name;
                                $dataim['image_date'] = $date;
                                $dataim['product_id'] = $id;
                                $dataim['company_id'] = \Session::get('companyid');
                                $dataim['location_id'] = \Session::get('location');
                                $dataim['created_by'] = \Session::get('id');
                                \DB::table('m_product_image_t')->insertGetId($dataim);


                                $file->move(public_path() . '/uploads/product_image/' . $id . '/', $name);
                                $dataupload[] = $name;
                            }
                        }
                        $attachfile_name = json_encode($dataupload);
                        //  dd($attachfile_name);
                        \DB::update("update m_products_t set choosefile='" . $attachfile_name . "' where product_id='$id'");
                        $this->data['notymsg'] = "yes";
                    } else {
                        // dd("FDgfdg");
                        $existing_file = $request->input('existing_file');
                        $choose_file = $request->file('choosefile');
                        $existing_file = explode(",", $existing_file);
                        if (count($choose_file) == 0 && count($existing_file) > 0) {
                            if (count($existing_file) == 1 && $existing_file[0] == '') {

                                \DB::update("update m_products_t set choosefile='' where product_id='$id'");
                            } else {
                                $get_attach = DB::table('m_products_t')->where('product_id', $id)->get();
                                $attach_file = json_decode($get_attach[0]->choosefile);
                                //dd($attach_file);
                                $attach_file1 = array();

                                foreach ($attach_file as $k => $v) {
                                    $attach_file1[] = $v;
                                }
                                $array_diff = array_diff($attach_file1, $existing_file);

                                if (count($array_diff) > 0) {
                                    foreach ($array_diff as $k => $v) {
                                        // unlink(public_path().'/uploads/product_image/'.$id.'/'.$v);
                                    }
                                    $attachfile_name = json_encode($existing_file);

                                    \DB::update("update m_products_t set choosefile='" . $attachfile_name . "' where product_id='$id'");
                                }
                            }
                        } else if (count($choose_file) > 0 && count($existing_file) > 0) {

                            foreach ($request->file('choosefile') as $file) {
                                $name = $file->getClientOriginalName();

                                $date = date('Y-m-d H:i:s');
                                $name = $name . '&' . $date;
                                $dataim['choosefile'] = $name;
                                $dataim['product_id'] = $id;
                                $dataim['image_date'] = $date;

                                $dataim['company_id'] = \Session::get('companyid');
                                $dataim['location_id'] = \Session::get('location');
                                $dataim['created_by'] = \Session::get('id');
                                \DB::table('m_product_image_t')->insertGetId($dataim);


                                $file->move(public_path() . '/uploads/product_image/' . $id . '/', $name);
                                $dataupload[] = $name;
                            }

                            $get_attach = DB::table('m_products_t')->where('product_id', $id)->get();
                            $attach_file = json_decode($get_attach[0]->choosefile);
                            $attach_file1 = array();

                            foreach ($attach_file as $k => $v) {
                                $attach_file1[] = $v;
                            }

                            $array_diff = array_diff($attach_file1, $existing_file);

                            if (count($array_diff) > 0) {
                                foreach ($attach_file as $k => $v) {
                                    // unlink(public_path().'/uploads/product_image/'.$id.'/'.$v);
                                }
                                $attachfile_name = array_merge($existing_file, $dataupload);
                                $attachfile_name = json_encode($attachfile_name);
                            } else {
                                $attachfile_name = array_merge($attach_file1, $dataupload);
                                $attachfile_name = json_encode($attachfile_name);
                            }
                            \DB::update("update m_products_t set choosefile='" . $attachfile_name . "' where product_id='$id'");

                        } else if (count($choose_file) > 0 && count($existing_file) == 0) {
                            dd($request->file('choosefile'));

                            foreach ($request->file('choosefile') as $file) {
                                $name = $file->getClientOriginalName();


                                $date = date('Y-m-d H:i:s');
                                $name = $name . '&' . $date;
                                $dataim['choosefile'] = $name;
                                $dataim['product_id'] = $id;
                                $dataim['image_date'] = $date;

                                $dataim['company_id'] = \Session::get('companyid');
                                $dataim['location_id'] = \Session::get('location');
                                $dataim['created_by'] = \Session::get('id');
                                \DB::table('m_product_image_t')->insertGetId($dataim);

                                $file->move(public_path() . '/uploads/product_image/' . $id . '/', $name);
                                $dataupload[] = $name;
                            }
                            $attachfile_name = json_encode($dataupload);
                            \DB::update("update m_products_t set choosefile='" . $attachfile_name . "' where product_id='$id'");
                        }
                    }
                    /*End File Attachments*/


                    if ($i == 0) {
                        $get_id = $id;
                    }

                    $upd = \DB::table('m_products_t')->where('product_id', $id)->update(['parent_id' => $get_id]);
                    $action = "Edit";
                    $edit_id = $_POST['product_id'];
                    /**Auditlog**/
                    $this->auditlog($edit_id, "products", $action, $_POST, "m_products_t");

                }

            } else {
                if ($_POST['product_id'] == "") {
                    $product_code = $_POST['product_code'];
                    $existingProduct = \DB::table('m_products_t')->where('product_code', $product_code)->first();
                    if ($existingProduct) {
                        return response()->json(['status' => 'error', 'message' => 'Product code already exists']);
                    } else {
                        $data['product_code'] = $_POST['product_code'];
                    }
                    $data['product_pack_id'] = $_POST['product_pack_id'];
                    $data['product_alternate_name'] = $_POST['product_alternate_name'];
                    $data['concatenated_product'] = $_POST['concatenated_product'];
                    $data['defalut_hsn_code'] = $_POST['defalut_hsn_code'];
                    $data['account_code_id'] = $_POST['account_code_id'];
                    $data['control_account_id'] = $_POST['control_account_id'];
                    $data['disc_account_code'] = $_POST['disc_account_code'];
                    $data['primary_uom_id'] = $_POST['primary_uom_id'];
                    $data['trx_uom_id'] = $_POST['trx_uom_id'];
                    $data['active'] = $_POST['active'];

                    if ($_POST['locator_control'] != "") {
                        $data['locator_control'] = $_POST['locator_control'];
                    } else {
                        $data['locator_control'] = "";
                    }

                    $data['subinventory_id'] = $_POST['subinventory_id'];
                    $data['sublocator_id'] = $_POST['sublocator_id'];



                    $data['company_id'] = \Session::get('companyid');
                    $data['location_id'] = \Session::get('location');
                    $data['created_by'] = \Session::get('id');
                    /* code for lines level additional details*/
                    $data['min_order_qty'] = $_POST['min_order_qty'];
                    $data['min_stock_level2'] = $_POST['min_stock_level2'];
                    $data['min_stock_level3'] = $_POST['min_stock_level3'];
                    $data['max_order_qty'] = $_POST['max_order_qty'];
                    $data['tax_credit'] = $_POST['tax_credit'];
                    $data['re_order_level'] = $_POST['re_order_level'];
                    if (isset($_POST['hsn_code'])) {
                        $hsncode = implode(",", $_POST['hsn_code']);
                        $_POST['hsn_code'] = $hsncode;
                        $data['hsn_code'] = $hsncode;
                    } else {
                        $data['hsn_code'] = '';
                    }

                    $id = \DB::table('m_products_t')->insertGetId($data);

                    if ($_POST['product_id'] == '') {
                        if ($request->hasfile('choosefile')) {

                            foreach ($request->file('choosefile') as $file) {
                                $name = $file->getClientOriginalName();
                                $date = date('Y-m-d H:i:s');
                                $name = $name . '&' . $date;
                                $dataim['choosefile'] = $name;
                                $dataim['image_date'] = $date;

                                $dataim['product_id'] = $id;
                                $dataim['company_id'] = \Session::get('companyid');
                                $dataim['location_id'] = \Session::get('location');
                                $dataim['created_by'] = \Session::get('id');
                                \DB::table('m_product_image_t')->insertGetId($dataim);

                                $file->move(public_path() . '/uploads/product_image/' . $id . '/', $name);
                                $dataupload[] = $name;
                            }
                        } else {
                            $dataupload = array();
                        }
                        $attachfile_name = json_encode($dataupload);
                        //  dd($attachfile_name);
                        \DB::update("update m_products_t set choosefile='" . $attachfile_name . "' where product_id='$id'");
                        $this->data['notymsg'] = "yes";
                    } else {
                        $existing_file = $request->input('existing_file');
                        $choose_file = $request->file('choosefile');
                        $existing_file = explode(",", $existing_file);
                        if (count($choose_file) == 0 && count($existing_file) > 0) {
                            if (count($existing_file) == 1 && $existing_file[0] == '') {

                                \DB::update("update m_products_t set choosefile='' where product_id='$id'");
                            } else {
                                $get_attach = DB::table('m_products_t')->where('product_id', $id)->get();
                                $attach_file = json_decode($get_attach[0]->choosefile);
                                $attach_file1 = array();

                                foreach ($attach_file as $k => $v) {
                                    $attach_file1[] = $v;
                                }
                                $array_diff = array_diff($attach_file1, $existing_file);

                                if (count($array_diff) > 0) {
                                    foreach ($array_diff as $k => $v) {
                                        //  dd($v);
                                        //  unlink(public_path().'/uploads/product_image/'.$id.'/'.$v);
                                    }
                                    $attachfile_name = json_encode($existing_file);

                                    \DB::update("update m_products_t set choosefile='" . $attachfile_name . "' where product_id='$id'");
                                }
                            }
                        } else if (count($choose_file) > 0 && count($existing_file) > 0) {

                            foreach ($request->file('choosefile') as $file) {
                                $name = $file->getClientOriginalName();

                                $date = date('Y-m-d H:i:s');
                                $name = $name . '&' . $date;
                                $dataim['choosefile'] = $name;
                                $dataim['product_id'] = $id;
                                $dataim['image_date'] = $date;

                                $dataim['company_id'] = \Session::get('companyid');
                                $dataim['location_id'] = \Session::get('location');
                                $dataim['created_by'] = \Session::get('id');
                                \DB::table('m_product_image_t')->insertGetId($dataim);

                                $file->move(public_path() . '/uploads/product_image/' . $id . '/', $name);
                                $dataupload[] = $name;
                            }

                            $get_attach = DB::table('m_products_t')->where('product_id', $id)->get();
                            $attach_file = json_decode($get_attach[0]->choosefile);
                            $attach_file1 = array();

                            foreach ($attach_file as $k => $v) {
                                $attach_file1[] = $v;
                            }

                            $array_diff = array_diff($attach_file1, $existing_file);

                            if (count($array_diff) > 0) {
                                foreach ($attach_file as $k => $v) {
                                    // unlink(public_path().'/uploads/product_image/'.$id.'/'.$v);
                                }
                                $attachfile_name = array_merge($existing_file, $dataupload);
                                $attachfile_name = json_encode($attachfile_name);
                            } else {
                                $attachfile_name = array_merge($attach_file1, $dataupload);
                                $attachfile_name = json_encode($attachfile_name);
                            }
                            \DB::update("update m_products_t set choosefile='" . $attachfile_name . "' where product_id='$id'");

                        } else if (count($choose_file) > 0 && count($existing_file) == 0) {
                            foreach ($request->file('choosefile') as $file) {
                                $name = $file->getClientOriginalName();

                                $date = date('Y-m-d H:i:s');
                                $name = $name . '&' . $date;
                                $dataim['choosefile'] = $name;
                                $dataim['product_id'] = $id;
                                $dataim['image_date'] = $date;

                                $dataim['company_id'] = \Session::get('companyid');
                                $dataim['location_id'] = \Session::get('location');
                                $dataim['created_by'] = \Session::get('id');
                                \DB::table('m_product_image_t')->insertGetId($dataim);


                                $file->move(public_path() . '/uploads/product_image/' . $id . '/', $name);
                                $dataupload[] = $name;
                            }
                            $attachfile_name = json_encode($dataupload);
                            \DB::update("update m_products_t set choosefile='" . $attachfile_name . "' where product_id='$id'");
                        }
                    }
                    /*End File Attachments*/
                } else {

                    $id = $_POST['product_id'];
                    $new_product_code = $_POST['product_code'];

                    // Check if the new product code is different from the existing one
                    $existingProduct = \DB::table('m_products_t')->where('product_id', $id)->first();
                    if ($existingProduct) {
                        $existing_product_code = $existingProduct->product_code;
                        if ($existing_product_code != $new_product_code) {
                            // If the product code is changed, check if the new product code already exists
                            $existingProductWithNewCode = \DB::table('m_products_t')->where('product_code', $new_product_code)->first();
                            if ($existingProductWithNewCode) {
                                return response()->json(['status' => 'error', 'message' => 'Product code already exists']);
                            }
                        }
                    }

                    // If the product code is not changed or the new product code doesn't exist, proceed with the update
                    $data['product_code'] = $new_product_code;
                    $data['product_pack_id'] = $_POST['product_pack_id'];
                    $data['product_alternate_name'] = $_POST['product_alternate_name'];
                    $data['concatenated_product'] = $_POST['concatenated_product'];
                    $data['defalut_hsn_code'] = $_POST['defalut_hsn_code'];
                    $data['account_code_id'] = $_POST['account_code_id'];
                    $data['control_account_id'] = $_POST['control_account_id'];
                    $data['disc_account_code'] = $_POST['disc_account_code'];
                    $data['primary_uom_id'] = $_POST['primary_uom_id'];
                    $data['trx_uom_id'] = $_POST['trx_uom_id'];
                    $data['active'] = $_POST['active'];

                    if ($_POST['locator_control'] != "") {
                        $data['locator_control'] = $_POST['locator_control'];
                    } else {
                        $data['locator_control'] = "";
                    }

                    $data['subinventory_id'] = $_POST['subinventory_id'];
                    $data['sublocator_id'] = $_POST['sublocator_id'];


                    $data['company_id'] = \Session::get('companyid');
                    $data['location_id'] = \Session::get('location');
                    $data['last_updated_by'] = \Session::get('id');
                    $data['updated_at'] = date('Y-m-d H:i:s');

                    /* code for lines level additional details*/
                    $data['min_order_qty'] = $_POST['min_order_qty'];
                    $data['min_stock_level2'] = $_POST['min_stock_level2'];
                    $data['min_stock_level3'] = $_POST['min_stock_level3'];
                    $data['max_order_qty'] = $_POST['max_order_qty'];
                    $data['tax_credit'] = $_POST['tax_credit'];
                    $data['re_order_level'] = $_POST['re_order_level'];
                    $data['expiry_days'] = $_POST['product_expiry_days'];
                    $data['packing_ctn_box'] = $_POST['packing_ctn_box'];
                    if (isset($_POST['hsn_code']))
                        $hsncode = implode(",", $_POST['hsn_code']);
                    else
                        $hsncode = '';

                    $_POST['hsn_code'] = $hsncode;
                    $data['hsn_code'] = $hsncode;
                    //dd($data);
                    $up_id = \DB::table('m_products_t')->where('product_id', $id)->update($data);
                    $edit_id = DB::getPdo()->lastInsertId();
                    // dd($_POST['product_id']);

                    if ($_POST['product_id'] == '') {
                        //dd($request->hasfile('choosefile'));
                        if ($request->hasfile('choosefile')) {
                            foreach ($request->file('choosefile') as $file) {
                                $name = $file->getClientOriginalName();
                                $date = date('Y-m-d H:i:s');
                                $name = $name . '&' . $date;
                                $dataim['choosefile'] = $name;
                                $dataim['image_date'] = $date;

                                $dataim['product_id'] = $id;
                                $dataim['company_id'] = \Session::get('companyid');
                                $dataim['location_id'] = \Session::get('location');
                                $dataim['created_by'] = \Session::get('id');
                                \DB::table('m_product_image_t')->insertGetId($dataim);


                                $file->move(public_path() . '/uploads/product_image/' . $id . '/', $name);
                                $dataupload[] = $name;
                            }
                        }
                        $attachfile_name = json_encode($dataupload);
                        //  dd($attachfile_name);
                        \DB::update("update m_products_t set choosefile='" . $attachfile_name . "' where product_id='$id'");
                        $this->data['notymsg'] = "yes";
                    } else {
                        $existing_file = $request->input('existing_file');
                        $choose_file = $request->file('choosefile');
                        if ($existing_file != null) {

                            $existing_file = explode(",", $existing_file);
                        } else {
                            $existing_file = array();
                        }

                        if ($choose_file == 'null' || $choose_file == '') {
                            $choose_file = array();
                        }
                        if (count($choose_file) == 0 && count($existing_file) > 0) {
                            if (count($existing_file) == 1 && $existing_file[0] == '') {

                                \DB::update("update m_products_t set choosefile='' where product_id='$id'");
                            } else {
                                $get_attach = DB::table('m_products_t')->where('product_id', $id)->get();
                                $attach_file = json_decode($get_attach[0]->choosefile);
                                $attach_file1 = array();

                                foreach ($attach_file as $k => $v) {
                                    $attach_file1[] = $v;
                                }
                                $array_diff = array_diff($attach_file1, $existing_file);

                                if (count($array_diff) > 0) {
                                    foreach ($array_diff as $k => $v) {
                                        // dd($v);
                                        // unlink(public_path().'/uploads/product_image/'.$id.'/'.$v);
                                    }
                                    $attachfile_name = json_encode($existing_file);

                                    \DB::update("update m_products_t set choosefile='" . $attachfile_name . "' where product_id='$id'");
                                }
                            }
                        } else if (count($choose_file) > 0 && count($existing_file) > 0) {

                            foreach ($request->file('choosefile') as $file) {
                                $name = $file->getClientOriginalName();
                                $date = date('Y-m-d H:i:s');
                                $name = $name . '&' . $date;
                                $dataim['choosefile'] = $name;
                                $dataim['product_id'] = $id;
                                $dataim['image_date'] = $date;

                                $dataim['company_id'] = \Session::get('companyid');
                                $dataim['location_id'] = \Session::get('location');
                                $dataim['created_by'] = \Session::get('id');
                                \DB::table('m_product_image_t')->insertGetId($dataim);


                                $file->move(public_path() . '/uploads/product_image/' . $id . '/', $name);
                                $dataupload[] = $name;
                            }

                            $get_attach = DB::table('m_products_t')->where('product_id', $id)->get();
                            $attach_file = json_decode($get_attach[0]->choosefile);
                            $attach_file1 = array();

                            foreach ($attach_file as $k => $v) {
                                $attach_file1[] = $v;
                            }

                            $array_diff = array_diff($attach_file1, $existing_file);

                            if (count($array_diff) > 0) {
                                foreach ($attach_file as $k => $v) {
                                    //unlink(public_path().'/uploads/product_image/'.$id.'/'.$v);
                                }
                                $attachfile_name = array_merge($existing_file, $dataupload);
                                $attachfile_name = json_encode($attachfile_name);
                            } else {
                                $attachfile_name = array_merge($attach_file1, $dataupload);
                                $attachfile_name = json_encode($attachfile_name);
                            }
                            \DB::update("update m_products_t set choosefile='" . $attachfile_name . "' where product_id='$id'");

                        } else if (count($choose_file) > 0 && count($existing_file) == 0) {
                            foreach ($request->file('choosefile') as $file) {
                                $name = $file->getClientOriginalName();


                                $date = date('Y-m-d H:i:s');
                                $name = $name . '&' . $date;
                                $dataim['choosefile'] = $name;
                                $dataim['image_date'] = $date;

                                $dataim['product_id'] = $id;
                                $dataim['company_id'] = \Session::get('companyid');
                                $dataim['location_id'] = \Session::get('location');
                                $dataim['created_by'] = \Session::get('id');
                                \DB::table('m_product_image_t')->insertGetId($dataim);


                                $file->move(public_path() . '/uploads/product_image/' . $id . '/', $name);
                                $dataupload[] = $name;
                            }
                            $attachfile_name = json_encode($dataupload);
                            \DB::update("update m_products_t set choosefile='" . $attachfile_name . "' where product_id='$id'");
                        }
                    }
                    /*End File Attachments*/

                    $action = "Create";
                    /**Auditlog**/
                    $this->auditlog($edit_id, "product", $action, $_POST, "m_products_t");
                }
            }

            if ($_POST['product_status'] == 'INITIATED') {
                $dat = \DB::select("SELECT m_products_t.product_code, m_products_t.concatenated_product,m_products_t.product_status, m_product_groups_t.group_name, m_product_category_t.category_name,m_product_subcategory_t.subcategory_name, m_sublocators_t.locator_code, f_gst_code_hdr_t.classification_code, acc_code.concatenated_segments as account_code, cont_acc.concatenated_segments as control_account, disc_code.concatenated_segments as discount_account, m_subinventory_t.subinventory_name, tb_users.first_name, tb_users.email FROM m_products_t LEFT JOIN m_product_groups_t ON m_products_t.product_group_id = m_product_groups_t.product_group_id LEFT JOIN m_product_category_t ON m_products_t.product_category_id = m_product_category_t.product_category_id LEFT JOIN m_product_subcategory_t ON m_products_t.product_subcategory_id = m_product_subcategory_t.product_subcategory_id LEFT JOIN m_sublocators_t ON m_products_t.sublocator_id = m_sublocators_t.sublocator_id LEFT JOIN f_gst_code_hdr_t ON m_products_t.defalut_hsn_code = f_gst_code_hdr_t.gst_code_hdr_id LEFT JOIN m_subinventory_t ON m_products_t.subinventory_id = m_subinventory_t.subinventory_id LEFT JOIN tb_users ON m_products_t.created_by = tb_users.id LEFT JOIN f_account_structure_t as acc_code ON m_products_t.account_code_id = acc_code.f_account_structure_id LEFT JOIN f_account_structure_t as cont_acc ON m_products_t.control_account_id = cont_acc.f_account_structure_id LEFT JOIN f_account_structure_t as disc_code ON m_products_t.disc_account_code = disc_code.f_account_structure_id WHERE m_products_t.product_id='" . $id . "'");
                $uid = \Session::get('id');
                $from_user = \DB::select("select first_name, email from tb_users where id= $uid");

                $pur['product_code'] = $dat[0]->product_code;
                $pur['concatenated_product'] = $dat[0]->concatenated_product;
                $pur['group_name'] = $dat[0]->group_name;
                $pur['classification_code'] = $dat[0]->classification_code;
                $pur['account_code'] = $dat[0]->account_code;
                $pur['control_account'] = $dat[0]->control_account;
                $pur['discount_account'] = $dat[0]->discount_account;
                $pur['subinventory_name'] = $dat[0]->subinventory_name;
                $pur['user_clear'] = $from_user[0]->first_name;
                $pur['product_status'] = $dat[0]->product_status;
                $pur['category_name'] = $dat[0]->category_name;
                $pur['subcategory_name'] = $dat[0]->subcategory_name;
                $pur['locator_code'] = $dat[0]->locator_code;

                $po_num_sub = $dat[0]->concatenated_product;
                Session::put('po_num_sub', $po_num_sub);
                $po_email = $from_user[0]->email;
                Session::put('po_email', $po_email);
                if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                    Config::set('mail.username', \Session::get('user_email'));
                    Config::set('mail.password', \Session::get('user_password'));
                    //dd('user_email');
                }

                if (\Session::get('user_email') != '') {
                    //dd($prd);

                    $to_mail_id = "expenses@jrkresearch.com";

                    \Mail::send('product.mail', $pur, function ($message) use ($to_mail_id) {
                        //dd($to_mail_id);    
                        $message->to($to_mail_id);
                        //$message->to('balaji_mohan@jrkresearch.com');
                        //$message->to('expenses@jrkresearch.com');
                        //$message->cc('gayathri_rajagopal@jrkresearch.com');
                        //$message->cc('jagadeesan_k@jrkresearch.com');
                        $message->cc('aspire@jrkresearch.com');

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

                        $message->subject($po_num_sub . " - PRODUCT INITIATED");
                    });
                }

            } else if ($_POST['product_status'] == 'APPROVED') {

                $dat = \DB::select("SELECT m_products_t.product_code, m_products_t.concatenated_product,m_products_t.product_status, m_product_groups_t.group_name,m_product_category_t.category_name,m_product_subcategory_t.subcategory_name, m_sublocators_t.locator_code, f_gst_code_hdr_t.classification_code, acc_code.concatenated_segments as account_code, cont_acc.concatenated_segments as control_account, disc_code.concatenated_segments as discount_account, m_subinventory_t.subinventory_name, tb_users.first_name, tb_users.email FROM m_products_t LEFT JOIN m_product_groups_t ON m_products_t.product_group_id = m_product_groups_t.product_group_id  LEFT JOIN m_product_category_t ON m_products_t.product_category_id = m_product_category_t.product_category_id LEFT JOIN m_product_subcategory_t ON m_products_t.product_subcategory_id = m_product_subcategory_t.product_subcategory_id LEFT JOIN m_sublocators_t ON m_products_t.sublocator_id = m_sublocators_t.sublocator_id LEFT JOIN f_gst_code_hdr_t ON m_products_t.defalut_hsn_code = f_gst_code_hdr_t.gst_code_hdr_id LEFT JOIN m_subinventory_t ON m_products_t.subinventory_id = m_subinventory_t.subinventory_id LEFT JOIN tb_users ON m_products_t.last_updated_by = tb_users.id LEFT JOIN f_account_structure_t as acc_code ON m_products_t.account_code_id = acc_code.f_account_structure_id LEFT JOIN f_account_structure_t as cont_acc ON m_products_t.control_account_id = cont_acc.f_account_structure_id LEFT JOIN f_account_structure_t as disc_code ON m_products_t.disc_account_code = disc_code.f_account_structure_id WHERE m_products_t.product_id='" . $id . "'");

                $to_mail = \DB::select("SELECT * FROM m_products_t LEFT JOIN tb_users ON m_products_t.created_by = tb_users.id WHERE m_products_t.product_id='" . $id . "'");

                $pur['product_code'] = $dat[0]->product_code;
                $pur['concatenated_product'] = $dat[0]->concatenated_product;
                $pur['group_name'] = $dat[0]->group_name;
                $pur['classification_code'] = $dat[0]->classification_code;
                $pur['account_code'] = $dat[0]->account_code;
                $pur['control_account'] = $dat[0]->control_account;
                $pur['discount_account'] = $dat[0]->discount_account;
                $pur['subinventory_name'] = $dat[0]->subinventory_name;
                $pur['user_clear'] = $dat[0]->first_name;
                $pur['product_status'] = $dat[0]->product_status;
                $pur['category_name'] = $dat[0]->category_name;
                $pur['subcategory_name'] = $dat[0]->subcategory_name;
                $pur['locator_code'] = $dat[0]->locator_code;

                $po_num_sub = $dat[0]->concatenated_product;
                Session::put('po_num_sub', $po_num_sub);
                $po_email = $dat[0]->email;
                Session::put('po_email', $po_email);
                if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                    Config::set('mail.username', \Session::get('user_email'));
                    Config::set('mail.password', \Session::get('user_password'));
                    //dd('user_email');
                }

                if (\Session::get('user_email') != '') {
                    //dd($prd);

                    //$to_mail_id = "uma_p@jrkresearch.com";  
                    $to_mail_id = $to_mail[0]->email;

                    if (isset($to_mail_id)) {
                        $to_mail_id = $to_mail[0]->email;
                    } else {
                        $to_mail_id = "aspire@jrkresearch.com";
                    }

                    \Mail::send('product.mail', $pur, function ($message) use ($to_mail_id) {
                        //dd($to_mail_id);    
                        $message->to($to_mail_id);
                        //$message->to('purchase_pm@jrkresearch.com');
                        //$message->to('purchase_rm@jrkresearch.com');
                        //$message->cc('gayathri_rajagopal@jrkresearch.com');
                        //$message->cc('jagadeesan_k@jrkresearch.com');
                        $message->cc('aspire@jrkresearch.com');

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

                        $message->subject($po_num_sub . " - PRODUCT APPROVED");
                    });
                }
            }

            return response()->json(array('status' => 'success', 'message' => 'Saved Successfully', 'id' => $id));

        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');//dd($dbCode);
            dd($dbCode);
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }

    /*End*/

    /*purpose : load acc,dis account,control acc from account setting */
    public function accountassign()
    {

        $sql = DB::table('f_product_accountsetting_t')->where([['product_group_id', $_GET['group']], ['product_category_id', $_GET['category']], ['product_subcategory_id', $_GET['sub']], ['active', 'Yes'], ['company_id', \Session::get('companyid')]])->get();

        $acc_code = array('acccode_id' => '', 'disc_acccode' => '', 'control_acccode' => '');

        if (count($sql) > 0) {
            $acc_code['acccode_id'] = $sql[0]->product_acccode_id;
            $acc_code['control_acccode'] = $sql[0]->control_acccode_id;
            $acc_code['disc_acccode'] = $sql[0]->disc_acccode_id;
        }

        return $acc_code;
    }
    /*End*/

    /*View Function*/
    public function show(Product $product, $id = null)
    {
        $this->data['columns'] = \DB::connection()->getSchemaBuilder()->getColumnListing("m_products_t");
        $this->data['values'] = $table = Product::find($id);
        // dd($table);
        $this->data['product_id'] = $id;
        $this->data['group'] = $this->idname('group_name', 'm_product_groups_t', 'product_group_id', $table->product_group_id);
        $this->data['batch_no'] = $this->idname('batch_no', 'm_products_t', 'product_id', $table->product_id);
        $this->data['category'] = $this->idname('category_name', 'm_product_category_t', 'product_category_id', $table->product_category_id);
        $this->data['sub_category'] = $this->idname('subcategory_name', 'm_product_subcategory_t', 'product_subcategory_id', $table->product_subcategory_id);
        $this->data['product_type_id'] = $this->idname('product_type', 'm_product_type_t', 'product_type_id', $table->product_type_id);
        $this->data['product_variant_id'] = $this->idname('product_variant_name', 'm_product_variants_t', 'product_variant_id', $table->product_variant_id);
        $this->data['product_packtype_id'] = $this->idname('product_pack_type_name', 'i_product_packs_types_t', 'product_packs_type_id', $table->product_packtype_id);
        $this->data['product_pack_id'] = $this->idname('pack_name', 'i_product_packs', 'packing_id', $table->product_pack_id);
        $this->data['primary_uom'] = $this->idname('uom_code', 'm_uom_codes_t', 'uom_code_id', $table->primary_uom_id);
        $this->data['trxuom'] = $this->idname('uom_code', 'm_uom_codes_t', 'uom_code_id', $table->trx_uom_id);
        $this->data['choosefile'] = $table->choosefile;
        if ($table->hsn_code) {
            $sqpl = (explode(",", $table->hsn_code));
            $hsn_code = '';
            foreach ($sqpl as $key => $value) {
                $hsn_code .= $this->idname('classification_code', 'f_gst_code_hdr_t', 'gst_code_hdr_id', $value) . ',';

            }
            $this->data['hsn_code'] = rtrim($hsn_code, ',');
        } else {
            $this->data['hsn_code'] = '';
        }

        $this->data['defalut_hsn_code'] = $this->idname('classification_code', 'f_gst_code_hdr_t', 'gst_code_hdr_id', $table->defalut_hsn_code);
        $this->data['account_code'] = $this->idname('concatenated_segments', 'f_account_structure_t', 'f_account_structure_id', $table->account_code_id);
        $this->data['disc_account_code'] = $this->idname('concatenated_segments', 'f_account_structure_t', 'f_account_structure_id', $table->disc_account_code);
        $this->data['control_account_code'] = $this->idname('concatenated_segments', 'f_account_structure_t', 'f_account_structure_id', $table->control_account_id);
        $this->data['subinv'] = $this->idname('subinventory_name', 'm_subinventory_t', 'subinventory_id', $table->subinventory_id);
        $this->data['locator'] = $this->idname('locator_name', 'm_sublocators_t', 'sublocator_id', $table->sublocator_id);
        $this->data['created_by'] = $this->idname('username', 'tb_users', 'id', $table->created_by);
        // dd($table);

        $this->data['pageMethod'] = 'product';

        return view('product.view', $this->data);

    }
    public function imageshow(Product $product, $id = null)
    {
        $this->data['columns'] = \DB::connection()->getSchemaBuilder()->getColumnListing("m_products_t");
        $this->data['values'] = $table = Product::find($id);
        $this->data['product_id'] = $id;



        $linesdata = \DB::table('m_product_image_t')

            ->select('m_product_image_t.choosefile', 'm_product_image_t.image_date')
            ->where('m_product_image_t.product_id', $id)->orderby('m_product_image_t.product_image_id', 'DESC')
            ->get();
        $this->data['linesdata'] = $linesdata;

        $this->data['pageMethod'] = 'product';

        return view('product.imageview', $this->data);

    }


    //     public function imageshow(Product $product,$id=null)
//     {
//       $this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("m_products_t");
//         $this->data['values'] = $table = Product::find($id);
//         // dd($table);
//         $this->data['product_id'] =$id;
//         $this->data['group'] = $this->idname('group_name','m_product_groups_t','product_group_id',$table->product_group_id);
//         $this->data['batch_no'] = $this->idname('batch_no','m_products_t','product_id',$table->product_id);
//         $this->data['category'] = $this->idname('category_name','m_product_category_t','product_category_id',$table->product_category_id);
//         $this->data['sub_category'] = $this->idname('subcategory_name','m_product_subcategory_t','product_subcategory_id',$table->product_subcategory_id);
//         $this->data['product_type_id'] = $this->idname('product_type','m_product_type_t','product_type_id',$table->product_type_id);
//         $this->data['product_variant_id'] = $this->idname('product_variant_name','m_product_variants_t','product_variant_id',$table->product_variant_id);        
//         $this->data['product_packtype_id'] = $this->idname('product_pack_type_name','i_product_packs_types_t','product_packs_type_id',$table->product_packtype_id);
//         $this->data['product_pack_id'] = $this->idname('pack_name','i_product_packs','packing_id',$table->product_pack_id);
//         $this->data['primary_uom'] = $this->idname('uom_code','m_uom_codes_t','uom_code_id',$table->primary_uom_id);
//         $this->data['trxuom'] = $this->idname('uom_code','m_uom_codes_t','uom_code_id',$table->trx_uom_id);
//         $this->data['choosefile'] =$table->choosefile; 
//         if($table->hsn_code){
//             $sqpl =(explode(",",$table->hsn_code));
//             $hsn_code='';
//             foreach ($sqpl as $key => $value) {
//                 $hsn_code.=$this->idname('classification_code','f_gst_code_hdr_t','gst_code_hdr_id',$value).',';

    //             }
//             $this->data['hsn_code']=rtrim($hsn_code,',');
//         }else{
//             $this->data['hsn_code']='';
//         }

    //         $this->data['defalut_hsn_code'] = $this->idname('classification_code','f_gst_code_hdr_t','gst_code_hdr_id',$table->defalut_hsn_code);
//         $this->data['account_code'] = $this->idname('concatenated_segments','f_account_structure_t','f_account_structure_id',$table->account_code_id);
//         $this->data['disc_account_code'] = $this->idname('concatenated_segments','f_account_structure_t','f_account_structure_id',$table->disc_account_code);
//         $this->data['subinv'] = $this->idname('subinventory_name','m_subinventory_t','subinventory_id',$table->subinventory_id);
//         $this->data['locator'] = $this->idname('locator_name','m_sublocators_t','sublocator_id',$table->sublocator_id);
//         $this->data['created_by'] = $this->idname('username','tb_users','id',$table->created_by);
// // dd($table);

    //         $this->data['pageMethod']='product';

    //   $linesdata  = \DB::table('m_products_t')
//             ->leftjoin('m_product_image_t', 'm_product_image_t.product_id', '=', 'm_products_t.product_id')

    //       ->select('m_products_t.concatenated_product','m_product_image_t.choosefile')
// 		  ->where('m_products_t.product_id',$id)
//             ->get(); 
//         	  $this->data['linesdata']=$linesdata;


    //         return view('product.imageview',$this->data);

    //     }
/*End*/


    /*Delete Function*/
    public function delete($del_id)
    {
        $column = array('product_id', 'product_id', 'product_id', 'product_id', 'product_id', 'product_id', 'product_id', 'product_id', 'product_id', 'product_id', 'product_id', 'product_id', 'product_id', 'product_id', 'product_id', 'product_id');

        $table = array('p_enquiry_lines_t', 'p_requisition_lines_t', 'p_quotation_lines_t', 'p_po_lines_t', 'p_po_invoice_lines_t', 'p_gin_lines_t', 'p_grn_lines_t', 'p_qc_lines_t', 'm_sublocators_t', 'i_pricelist_lines_t', 's_inquiry_lines_t', 's_quote_lines_t', 's_salesorder_lines_t', 's_pickrelease_sub_lines_t', 's_pickrelease_lines_t', 's_invoice_lines_t');

        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $del_id)->get();
            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }

        if ($j == 0) {
            $query = \DB::table('m_products_t')->where('product_id', $del_id)->delete();
            /**Auditlog**/
            $this->auditlog($del_id, "product", "Delete", "", "m_products_t");
        }
        return $j;
    }
    /*End*/


    /*Prodct Specification Function*/
    public function productspec($prdid = null, $id = null)
    {
        $product = Qualityproductspechdr::find($id);
   

        if ($id != 0) {
            $this->data['quality_product_specs_hdr_id'] = $id;
            $this->data['product_id'] = $this->jcombo("m_products_t", "product_id", "concatenated_product", $product->product_id);
            $lines = \DB::table('i_quality_product_specs_lines_t')->where('quality_product_specs_hdr_id', $id)->get();
            $this->data['linedata'] = $lines;
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->spec_criteria = $this->data['spec_criteria'] = $this->jcustomselect("a_lookuplines_t", "lookuplines_id", "lookup_code", $value->spec_criteria, "and lookup_type='SPEC_CRITERIA'");
            }

        } else {

            $this->data['quality_product_specs_hdr_id'] = "";
            $this->data['product_id'] = $this->jcombo("m_products_t", "product_id", "concatenated_product", $prdid);
            $this->data['spec_criteria'] = $this->jcustomselect("a_lookuplines_t", "lookuplines_id", "lookup_code", '', "and lookup_type='SPEC_CRITERIA'");
            $this->data['linedata'] = array();

        }

        return view('product.specform', $this->data);
    }

    /*End*/




    /*Product Specification Save Function*/
    public function productspecsave(Request $request)
    {

        	$id='';
			$form = $request->all();
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file','enable-masterdetail',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->spechdrtable, 'header');
			$lines_data = $this->validatePost($form, $this->speclinestable, 'lines');

        // \DB::beginTransaction();
        try {
            $id = $this->specmodel->insertRow($data);
            $lid = $this->specsubmodel->subgridSave($lines_data, $id);
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

    /*End*/






    public function companyassign($product = null, $company = null)
    {

        $product = explode(',', $product);

        $company = explode(',', $company);

        foreach ($company as $ckey => $cval) {
            //dd($cval,$product);

            foreach ($product as $pkey => $pval) {

                $data["product_id"] = $pval;
                $data["company_id"] = $cval;

                \DB::table('i_product_assigncompany_t')->insert($data);
            }


        }

        return 1;
    }




    public function companydetails()
    {


        $companydata = \DB::table('m_company_t')->select('*')->get();
        return $companydata;

    }

    public function batchnoedit($id = null)
    {
        $batchno = $_GET['batchno'];
        $upd = \DB::table('m_products_t')->where('product_id', $id)->update(['batch_no' => $batchno]);
        return 1;

    }

    public function companyprice()
    {
        $companypricedata = \DB::table('i_pricelist_hdr_t')->select('*')->get();
        return $companypricedata;

    }


    /*Finding Primary Key Function*/
    function findPrimarykey($table)
    {
        $primaryKey = '';
        foreach (\DB::select("show columns from " . $table . " where extra like '%auto_increment%'") as $key) {
            $primaryKey = $key->Field;
        }
        return $primaryKey;
    }
    /*End*/


    /*Concatenate Linesdata Product Check Duplicate  Function*/
    public function concatenateproductcheck()
    {
        //dd($_GET);
        $result = $_GET['result'];
        $edit_id = $_GET['edit_id'];

        if (count($result) > 0) {
            $list = array();
            foreach ($result as $key => $value) {

                $query = DB::table('m_products_t')->where('concatenated_product', $value)->get();
                if (count($query) > 0) {
                    $list['inarray'][] = $key;
                }
                $list['exist'][] = $key;
            }

        }

        return $list;
    }
    /*End*/

    /*Concatenate Headerdata Product Check Duplicate  Function*/
    public function concatenateproductchecksingle(Request $request)
    {

        $edit_id = $_GET['edit_id'];
        $concatenated_product = $_GET['concatenated_product'];
        if ($edit_id == '')
            $department = DB::table('m_products_t')->where('concatenated_product', $concatenated_product)->get();
        else {
            $whereData = [['concatenated_product', $concatenated_product], ['product_id', '!=', $edit_id]];

            $department = DB::table('m_products_t')->where($whereData)->get();
        }


        if (count($department) > 0)
            return 1;
        else
            return 0;


    }
    /*End*/

    /* Header Product Code Duplicate  Function*/
    public function productcodecheck(Request $request)
    {

        $edit_id = $_GET['edit_id'];
        $product_code = $_GET['product_code'];
        if ($edit_id == '') {
            $department = DB::table('m_products_t')->where('product_code', $product_code)->get();
        } else {
            $whereData = [['product_code', $product_code], ['product_id', '!=', $edit_id]];

            $department = DB::table('m_products_t')->where($whereData)->get();
        }


        if (count($department) > 0)
            return 1;
        else
            return 0;


    }
    /*End*/

    /* Linesdata Product Code Duplicate  Function*/
    public function productcodechecklines()
    {

        $result = $_GET['result'];
        $edit_id = $_GET['edit_id'];

        if (count($result) > 0) {
            $list = array();
            foreach ($result as $key => $value) {

                $query = DB::table('m_products_t')->where('product_code', $value)->get();
                if (count($query) > 0) {
                    $list['inarray'][] = $key;
                }
                $list['exist'][] = $key;
            }

        }

        return $list;
    }
    /*End*/

    public function prdroledit()
    {

        $sql = \DB::select("select min_order_qty,min_stock_level2,min_stock_level3,max_order_qty,re_order_level,mpq_qty,gross_weight,net_weight from m_products_t where product_id='" . $_GET['id'] . "'");
        if (isset($sql)) {
            $data['min_order_qty'] = $sql[0]->min_order_qty;
            $data['min_stock_level2'] = $sql[0]->min_stock_level2;
            $data['min_stock_level3'] = $sql[0]->min_stock_level3;
            $data['max_order_qty'] = $sql[0]->max_order_qty;
            $data['re_order_level'] = $sql[0]->re_order_level;
            $data['mpq_qty'] = $sql[0]->mpq_qty;
            $data['gross_weight'] = $sql[0]->gross_weight;
            $data['net_weight'] = $sql[0]->net_weight;
            $data['update'] = 'update';

        }
        return $data;

    }

    public function rolupdate()
    {
        $min_order_qty = $_GET['min_order_qty'];
        $min_stock_level2 = $_GET['min_stock_level2'];
        $min_stock_level3 = $_GET['min_stock_level3'];
        $max_order_qty = $_GET['max_order_qty'];
        $re_order_level = $_GET['re_order_level'];
        $mpq_qty = $_GET['mpq_qty'];
        $gross_weight = $_GET['gross_weight'];
        $net_weight = $_GET['net_weight'];
        $check = \DB::update("update m_products_t set min_order_qty='" . $_GET['min_order_qty'] . "',min_stock_level2='" . $_GET['min_stock_level2'] . "',min_stock_level3='" . $_GET['min_stock_level3'] . "',max_order_qty='" . $_GET['max_order_qty'] . "' ,re_order_level='" . $_GET['re_order_level'] . "',mpq_qty='" . $_GET['mpq_qty'] . "',gross_weight='" . $_GET['gross_weight'] . "',net_weight='" . $_GET['net_weight'] . "' where product_id='" . $_GET['id'] . "'");

        if ($check) {
            return 1;
        } else {
            return 0;
        }
    }

}
