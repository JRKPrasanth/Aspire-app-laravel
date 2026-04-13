<?php

namespace App\Http\Controllers;

use App\productupload;
use App\Product;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Validator,
Input,
Redirect,
DB;

class ProductuploadController extends Controller
{
    public function __construct()
    {
        $this->customermodel = new CustomeruploadController;
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
    }


    public function getProductuploaddata()
    {
        $wh = '';


        if (isset($_GET['batchname'])) {
            if ($_GET['batchname'] != "") {
                $wh = " and batch_name like '" . $_GET['batchname'] . "'";
            }
        }


        $SQL = "SELECT * from i_productupload_t where 1=1 $wh";
        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);

    }


    /*Main Page Load Function*/
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

        $batch = $type = '';
        $this->data['status'] = $this->data['message'] = ''; //dd($_GET['batchname']);
        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND i_productupload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
        if (isset($_GET['type'])) {
            $type = 'PRODUCTUPLOAD';
            $message['status'] = 'success';
            switch ($_GET['type']) {
                case 'verify':
                    $upload = $this->UploadValidation($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    break;
                case 'load':
                    $upload = $this->LoadMaster($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    break;
            }
        }


        return view('productupload.table', $this->data);
    }

    /*Create Function*/
    public function create($id = null)
    {

        if (isset($id)) {
            $productupload = productupload::find($id);
            // dd($productupload);
        } else {
            $productuploads = \DB::connection()->getSchemaBuilder()->getColumnListing("s_customerupload_t");
            $productupload = array();
            foreach ($customeruploads as $key => $val) {
                $productupload[$val] = "";
            }
        }

        $this->data['productuploaddata'] = $productupload;


        $this->data['product_group_name'] = $this->jcombo("m_product_groups_t", "group_name", "group_name", $productupload['product_group_name']);
        $this->data['product_category_name'] = $this->jcombo("m_product_category_t", "category_name", "category_name", $productupload['product_category_name']);
        $this->data['product_subcategory_name'] = $this->jcombo("m_product_subcategory_t", "subcategory_name", "subcategory_name", $productupload['product_subcategory_name']);
        $this->data['product_type_name'] = $this->jcombo("m_product_type_t", "product_type", "product_type", $productupload['product_type_name']);
        $this->data['product_variant_name'] = $this->jcombo("m_product_variants_t", "product_variant_name", "product_variant_name", $productupload['product_variant_name']);
        $this->data['product_packtype_name'] = $this->jcombo("i_product_packs_types_t", "product_pack_type_name", "product_pack_type_name", $productupload['product_packtype_name']);
        $this->data['product_pack_name'] = $this->jcombo("i_product_packs", "pack_name", "pack_name", $productupload['product_pack_name']);
        $this->data['primary_uom_name'] = $this->jcombo("m_uom_codes_t", "uom_code", "uom_code", $productupload['primary_uom_name']);
        $this->data['trx_uom_name'] = $this->jcombo("m_uom_codes_t", "uom_code", "uom_code", $productupload['trx_uom_name']);
        $this->data['hsn_code'] = $this->jcustomselect("f_gst_code_hdr_t", "classification_code", "classification_code", $productupload['hsn_code'], " and classification_name='HSN'");
        $this->data['default_hsn_code'] = $this->jcustomselect("f_gst_code_hdr_t", "classification_code", "classification_code", $productupload['default_hsn_code'], " and classification_name='HSN'");
        $this->data['account_code_name'] = $this->jcombo("f_account_structure_t", "concatenated_segments", "concatenated_segments", $productupload['account_code_name']);
        $this->data['control_account'] = $this->jcombo("f_account_structure_t", "concatenated_segments", "concatenated_segments", $productupload['control_account']);
        $this->data['disc_account_code_name'] = $this->jcombo("f_account_structure_t", "concatenated_segments", "concatenated_segments", $productupload['disc_account_code_name']);
        $this->data['packing_cotton_box'] = $this->jcustomselect("m_products_t", "concatenated_product", "concatenated_product", $productupload['packing_cotton_box'], " and product_group_id='3'");


        $this->data['subinventory_name'] = $this->jcombo("m_subinventory_t", "subinventory_id", "subinventory_name", $productupload['subinventory_name_ref']);

        if (!empty($productupload['subinventory_name_ref']))
            $this->data['sublocator_name'] = $this->jcustomselect("m_sublocators_t", "locator_code", "locator_code", $productupload['sublocator_name'], " and subinventory_id =" . $productupload['subinventory_name_ref']);
        else
            $this->data['sublocator_name'] = $this->jcombo("m_sublocators_t", "locator_code", "locator_code", $productupload['sublocator_name']);



        $this->data['company_code'] = $this->jcombo("m_company_t", "company_code", "company_code", $productupload['company_id']);

        // dd($this->data['sublocator_name']);
        return view('productupload.form', $this->data);

    }
    /*End*/

    /*Save Function*/
    public function save(Request $request)
    {

        $productupload = new productupload();
        $_POST['batch_status'] = 'UPLOADED';
        $id = $_POST['product_upload_id'];

        $data['concatenated_product'] = '';

        $data['product_group_name'] = $_POST['product_group_name'];
        $data['product_category_name'] = $_POST['product_category_name'];
        $data['product_subcategory_name'] = $_POST['product_subcategory_name'];
        $data['product_type_name'] = $_POST['product_type_name'];
        $data['product_code'] = $_POST['product_code'];
        $data['product_variant_name'] = $_POST['product_variant_name'];
        $data['product_packtype_name'] = $_POST['product_packtype_name'];
        $data['product_pack_name'] = $_POST['product_pack_name'];
        $data['product_alternate_name'] = $_POST['product_alternate_name'];
        $data['primary_uom_name'] = $_POST['primary_uom_name'];
        $data['trx_uom_name'] = $_POST['trx_uom_name'];
        $data['hsn_code'] = $_POST['hsn_code'];
        $data['default_hsn_code'] = $_POST['default_hsn_code'];
        $data['control_account'] = $_POST['control_account'];
        $data['account_code_name'] = $_POST['account_code_name'];
        $data['disc_account_code_name'] = $_POST['disc_account_code_name'];

        $sub_id = $this->subinventory($_POST['subinventory_name'], 'id');

        $data['subinventory_name'] = $sub_id[0]->subinventory_name;
        $data['subinventory_name_ref'] = $_POST['subinventory_name'];
        $data['locator_control'] = $_POST['locator_control'];
        $data['sublocator_name'] = $_POST['sublocator_name'];
        $data['min_stock_level1'] = $_POST['min_stock_level1'];
        $data['min_stock_level2'] = $_POST['min_stock_level2'];
        $data['min_stock_level3'] = $_POST['min_stock_level3'];
        $data['max_order_qty'] = $_POST['max_order_qty'];
        $data['re_order_level'] = $_POST['re_order_level'];
        $data['tax_credit'] = $_POST['tax_credit'];
        $data['qc_type'] = $_POST['qc_type'];
        $data['mpq_qty'] = $_POST['mpq_qty'];
        $data['expiry_days'] = $_POST['expiry_days'];
        $data['packing_cotton_box'] = $_POST['packing_cotton_box'];
        $data['company_code'] = $_POST['company_code'];
        $data['batch_name'] = $_POST['batch_name'];
        $data['batch_date'] = $_POST['batch_date'];
        $data['batch_comments'] = '';
        $data['batch_status'] = $_POST['batch_status'];

        //dd($data);

        productupload::find($id)->update($data);


        return response()->json(array('status' => 'success', 'message' => 'your data Updated successfully!!', 'id' => $id));

    }
    /*End*/



    /* purpose:To Upload excel*/
    public function Uploadexcel(Request $request)
    {

        $path = $request->file('choosefile');
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $extension = $path->getClientOriginalExtension();
        $data = array();
        $return = 'productupload';
        if ($extension == "csv") {
            $file = $request->file('choosefile');
            $handle = fopen($file, "r");
            $c = 0;
            while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {
                if ($c > 0) {
                    // dd($filesop);
                    $productdata[$c]['product_group_name'] = strtoupper(trim($filesop[0]));
                    $productdata[$c]['product_category_name'] = strtoupper(trim($filesop[1]));
                    $productdata[$c]['product_subcategory_name'] = strtoupper(trim($filesop[2]));
                    $productdata[$c]['product_type_name'] = strtoupper(trim($filesop[3]));
                    $productdata[$c]['product_code'] = strtoupper(trim($filesop[4]));
                    $productdata[$c]['product_variant_name'] = strtoupper(trim($filesop[5]));
                    $productdata[$c]['product_packtype_name'] = strtoupper(trim($filesop[6]));
                    $productdata[$c]['product_pack_name'] = strtoupper(trim($filesop[7]));
                    $productdata[$c]['product_alternate_name'] = strtoupper(trim($filesop[8]));
                    $productdata[$c]['primary_uom_name'] = strtoupper(trim($filesop[9]));
                    $productdata[$c]['trx_uom_name'] = strtoupper(trim($filesop[10]));
                    $productdata[$c]['hsn_code'] = strtoupper(trim($filesop[11]));
                    $productdata[$c]['default_hsn_code'] = strtoupper(trim($filesop[12]));
                    $productdata[$c]['account_code_name'] = trim($filesop[13]);
                    $productdata[$c]['control_account'] = trim($filesop[14]);
                    $productdata[$c]['disc_account_code_name'] = trim($filesop[15]);
                    $productdata[$c]['subinventory_name'] = strtoupper(trim($filesop[16]));
                    $productdata[$c]['locator_control'] = strtoupper(trim($filesop[17]));
                    $productdata[$c]['sublocator_name'] = strtoupper(trim($filesop[18]));
                    $productdata[$c]['min_stock_level1'] = trim($filesop[19]);
                    $productdata[$c]['min_stock_level2'] = trim($filesop[20]);
                    $productdata[$c]['min_stock_level3'] = trim($filesop[21]);
                    $productdata[$c]['max_order_qty'] = trim($filesop[22]);
                    $productdata[$c]['re_order_level'] = strtoupper(trim($filesop[23]));
                    $productdata[$c]['tax_credit'] = ucfirst(trim($filesop[24]));
                    $productdata[$c]['qc_type'] = strtoupper(trim($filesop[25]));
                    $productdata[$c]['active'] = strtoupper(trim($filesop[26]));
                    $productdata[$c]['company_code'] = strtoupper(trim($filesop[27]));
                    $productdata[$c]['product_name'] = strtoupper(trim($filesop[28]));
                    $productdata[$c]['expiry_days'] = strtoupper(trim($filesop[29]));
                    $productdata[$c]['packing_cotton_box'] = strtoupper(trim($filesop[30]));
                    $productdata[$c]['mpq_qty'] = ucfirst(trim($filesop[31]));
                    $productdata[$c]['qc_check'] = ucfirst(trim($filesop[32]));
                    $productdata[$c]['product_classification'] = ucfirst(trim($filesop[36]));
                    $productdata[$c]['group_classification'] = ucfirst(trim($filesop[37]));
                    $productdata[$c]['batch_date'] = date('Y-m-d');
                    $productdata[$c]['batch_status'] = "UPLOADED";
                    $productdata[$c]['batch_name'] = $_POST['batch_name'];
                    //insert record from csv        
                }
                $c = $c + 1;
            }
            $id = \DB::table('i_productupload_t')->insert($productdata);
        } else {

            $message = "Please upload an valid CSV file";
            // return Redirect::to($return)->with('messagetext',$message)->with('msgstatus','error');      
            return response()->json(array('status' => 'error', 'message' => 'Please upload an valid CSV file!!'));
        }


        // return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');    
        return response()->json(array('status' => 'success', 'message' => 'Your data Uploaded successfully!!'));


    }
    /*end*/

    /*Validation For Uploaded File*/
    function uploadValidation($valModname, $valBatch, $status)
    {

        $status['status'] = 'success';
        $status['message'] = ' ';
        $sql = "select * from i_productupload_t where batch_status ='UPLOADED'  and batch_name='" . $valModname . "'";
        $result_pr = \DB::select($sql);
        // dd($result_pr);
        if (!empty($result_pr)) {
            foreach ($result_pr as $key => $value) {
                $status['message'] = '';
                $status['status'] = 'success';
                if (!empty($value->product_group_name)) {
                    $group_name = $this->group($value->product_group_name, "name");
                    if ($group_name[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Group Name not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Group Name Empty.. Please enter Group name' . ' ,';

                }

                if (!empty($value->product_subcategory_name)) {
                    $sub_cat_name = $this->subcatagory($value->product_subcategory_name, "name");
                    if ($sub_cat_name[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Sub Category Name not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Sub Category Name Empty.. Please enter Sub Category name' . ' ,';

                }

                if ($value->product_group_name == "FINISHED GOODS" || $value->product_group_name == "SEMI FINISHED GOODS" || $value->product_group_name == "SAMPLES PRODUCTS") {
                    if (!empty($value->product_type_name)) {
                        $packtype_name = $this->product_type($value->product_type_name, "name");
                        if ($packtype_name[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'Product Type does not exist' . ' ,';
                        }
                    } else {
                        $status['status'] = 'error';
                        $status['message'] .= 'Product Type Empty.. Please enter Product type' . ',';
                    }
                }

                if ($value->product_group_name == "FINISHED GOODS" && $value->product_category_name == "FINISHED") {
                    if (!empty($value->product_variant_name)) {
                        $color_name = $this->productvariant($value->product_variant_name, "name");
                        if ($color_name[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'Product Variant not exist' . ' ,';
                        }
                    } else {
                        $status['status'] = 'error';
                        $status['message'] .= 'Product Variant Empty.. Please enter Color ' . ' ,';
                    }

                    if (!empty($value->product_type_name)) {
                        $packtype_name = $this->product_type($value->product_type_name, "name");
                        if ($packtype_name[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'Product Type does not exist' . ' ,';
                        }
                    } else {
                        $status['status'] = 'error';
                        $status['message'] .= 'Product Type Empty.. Please enter Product type' . ',';
                    }

                    if (!empty($value->product_pack_name)) {
                        $pack_name = $this->product_pack($value->product_pack_name, "name");
                        if ($pack_name[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'Product Pack name not exist' . ' ,';
                        }
                    } else {
                        $status['status'] = 'error';
                        $status['message'] .= 'Product Pack Empty.. Please enter Product Pack name' . ',';
                    }

                } else {
                    if (!empty($value->product_variant_name)) {
                        $color_name = $this->productvariant($value->product_variant_name, "name");
                        //dd($color_name);
                        if ($color_name[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'Product Variant not exist' . ' ,';
                        }
                    }
                    /*else{
                        $status['status'] = 'error';
                        $status['message'].= 'Color Empty.. Please enter Color ' . ' ,';
                    }*/
                    if (!empty($value->product_type_name)) {
                        $packtype_name = $this->product_type($value->product_type_name, "name");
                        if ($packtype_name[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'Product Type does not exist' . ' ,';
                        }
                    }
                    /*else{
                        $status['status'] = 'error';
                        $status['message'].= 'Pack Type Empty.. Please enter Pack type' . ',';
                    }*/

                }


                if (!empty($value->product_category_name)) {
                    $category_name = $this->category($value->product_category_name, "name");
                    if ($category_name[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Category not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Category Empty.. Please enter Category name' . ' ,';
                }

                if (!empty($value->product_classification)) {
                    $calssification_name = $this->product_class($value->product_classification, "name");
                    if ($calssification_name[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Product Classification not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Product Classification Empty.. Please enter Product Classification name' . ' ,';
                }
                if (!empty($value->group_classification)) {
                    $groupcalssification_name = $this->group_class($value->group_classification, "name");
                    if ($groupcalssification_name[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Group Product Classification not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Group Product Classification Empty.. Please enter Group Product Classification name' . ' ,';
                }
                if (!empty($value->primary_uom_name)) {
                    $uom_name = $this->uom($value->primary_uom_name, "name");
                    if ($uom_name[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Primary Uom does not exist' . ' ,';
                    }
                }

                if (!empty($value->barcode_number)) {
                    $bar_code = $this->bar_code($value->barcode_number, "name");
                    if ($bar_code[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Barcode does not exist' . ' ,';
                    }
                }
                if (!empty($value->gross_weight)) {
                    $gross_weight = $this->gross_weight($value->gross_weight, "name");
                    if ($gross_weight[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Gross Weight does not exist' . ' ,';
                    }
                }
                if (!empty($value->net_weight)) {
                    $net_weight = $this->net_weight($value->net_weight, "name");
                    if ($net_weight[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Net Weight does not exist' . ' ,';
                    }
                }
                if (!empty($value->trx_uom_name)) {
                    $trx_name = $this->uom($value->trx_uom_name, "name");
                    if ($trx_name[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Trx Uom does not exist' . ' ,';
                    }
                }
                if ($value->product_group_name != "SEMI FINISHED GOODS") {
                    if (!empty($value->default_hsn_code)) {
                        $default_hsn_code = $this->hsn($value->default_hsn_code, "name", "HSN");
                        if ($default_hsn_code[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'Default HSN Code does not exist' . ' ,';
                        }
                    } else {
                        $status['status'] = 'error';
                        $status['message'] .= 'Default HSN Code Empty.. Please enter Default Hsn Code' . ' ,';
                    }
                    if (!empty($value->hsn_code)) {
                        $hsn_code = $this->hsn($value->hsn_code, "name", "HSN");
                        if ($hsn_code[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'HSN Code does not exist' . ' ,';
                        }
                    } else {
                        $status['status'] = 'error';
                        $status['message'] .= 'HSN Code Empty.. Please enter Hsn Code' . ' ,';
                    }
                    if (!empty($value->account_code_name)) {
                        $account_code_name = $this->account($value->account_code_name, "name");
                        if ($account_code_name[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'Account Code does not exist' . ' ,';
                        }
                    } else {
                        $status['status'] = 'error';
                        $status['message'] .= 'Account Code Empty.. Please enter Account Code' . ' ,';
                    }

                    if (!empty($value->disc_account_code_name)) {
                        $disc_account_code_name = $this->account($value->disc_account_code_name, "name");
                        if ($disc_account_code_name[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'Discount Account Code does not exist' . ' ,';
                        }
                    } else {
                        $status['status'] = 'error';
                        $status['message'] .= 'Discount Account Code Empty.. Please enter Discount Account Code' . ' ,';
                    }

                    if (!empty($value->control_account)) {
                        $control_account = $this->account($value->control_account, "name");
                        if ($control_account[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'Control Account Code does not exist' . ' ,';
                        }
                    } else {
                        $status['status'] = 'error';
                        $status['message'] .= 'Control Account Code Empty.. Please enter Control Account Code' . ' ,';
                    }
                } else {
                    if (!empty($value->default_hsn_code)) {
                        $default_hsn_code = $this->hsn($value->default_hsn_code, "name", "HSN");
                        if ($default_hsn_code[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'Default HSN Code does not exist' . ' ,';
                        }
                    }
                    if (!empty($value->hsn_code)) {
                        $hsn_code = $this->hsn($value->hsn_code, "name", "HSN");
                        if ($hsn_code[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'HSN Code does not exist' . ' ,';
                        }
                    }
                    if (!empty($value->account_code_name)) {
                        $account_code_name = $this->account($value->account_code_name, "name");
                        if ($account_code_name[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'Account Code does not exist' . ' ,';
                        }
                    }

                    if (!empty($value->disc_account_code_name)) {
                        $disc_account_code_name = $this->account($value->disc_account_code_name, "name");
                        if ($disc_account_code_name[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'Discount Account Code does not exist' . ' ,';
                        }
                    }

                    if (!empty($value->control_account)) {
                        $control_account = $this->account($value->control_account, "name");
                        if ($control_account[0]->cnt <= 0) {
                            $status['status'] = 'error';
                            $status['message'] .= 'Control Account Code does not exist' . ' ,';
                        }
                    }
                }



                if (!empty($value->subinventory_name)) {
                    $subinventory_name = $this->subinventory($value->subinventory_name, "name");
                    if ($subinventory_name[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Subinventory Name does not exist' . ' ,';
                    }
                    $subinventory_id = $subinventory_name[0]->subinventory_id;
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Subinventory Name empty.. Please enter subinventory name' . ' ,';
                }
                // $subinventory_id = $subinventory_name[0]->subinventory_id;

                if (!empty($subinventory_id)) {
                    if (!empty($value->locator_control)) {
                        if ($value->locator_control == "YES" && $value->locator_control != "NO") {
                            $sublocator_name = $this->locator($value->sublocator_name, "name", $subinventory_id);
                            // dd($sublocator_name);
                            if ($sublocator_name[0]->cnt <= 0) {
                                $status['status'] = 'error';
                                $status['message'] .= 'Locator Control does not exist' . ' ,';
                            }
                        } else if ($value->locator_control == "") {
                            $status['status'] = 'error';
                            $status['message'] .= 'Please enter Locator Control is only (Yes/No) ' . ' ,';
                        }
                    }
                }

                if (!empty($value->tax_credit)) {
                    if ($value->tax_credit != "Yes" && $value->tax_credit != "No") {
                        $status['status'] = 'error';
                        $status['message'] .= 'Tax Creadit does not exist' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Please enter Tax Creadit..' . ' ,';
                }
                if (!empty($value->qc_type)) {
                    if ($value->qc_type != "BATCHWISE" && $value->qc_type != "SERIALWISE") {
                        $status['status'] = 'error';
                        $status['message'] .= 'Qc Type(Batchwise/Serialwise)' . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Please enter Qc Type..' . ' ,';
                }
                // dd($value->company_code);
                if (!empty($value->company_code)) {
                    $company_id = $this->company($value->company_code, "name");
                    // dd($value->company_code);
                    if ($company_id[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Company Name does not exist';
                    }
                }

                if (!empty($value->primary_uom_name)) {
                    $primary_uom_name = $this->uom($value->primary_uom_name, "name");
                    if ($primary_uom_name[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= "Primary Uom Does not exists";
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Primary uom empty.. Please enter Primary uom' . ' ,';
                }


                if (!empty($value->trx_uom_name)) {
                    $trx_uom_name = $this->uom($value->trx_uom_name, "name");
                    if ($trx_uom_name[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= "Trx Uom Does not exists" . ' ,';
                    }
                } else {
                    $status['status'] = 'error';
                    $status['message'] .= 'Trx uom empty.. Please enter Trx uom' . ' ,';
                }


                if (!empty($value->packing_cotton_box)) {
                    $group_name = $this->pcb_group($value->packing_cotton_box, "name");
                    if ($group_name[0]->cnt <= 0) {
                        $status['status'] = 'error';
                        $status['message'] .= 'Packing cotton box Name not exist';
                    }
                }

                if ($status['status'] == "error") {
                    $sql = "update i_productupload_t set batch_status ='ERROR' , batch_comments='" . $status['message'] . "\n'  WHERE product_upload_id='" . $value->product_upload_id . "' ";
                    $result = \DB::update($sql);
                    $status['message'] = 'Uploaded data have some error';
                } else {
                    $sql = "update i_productupload_t set batch_status ='VALIDATED' , batch_comments='' where product_upload_id='" . $value->product_upload_id . "' ";
                    $result = \DB::update($sql);
                    $status['status'] == 'success';
                    $status['message'] = 'Product Data validated successfully';

                }
            }
            // dd($status);
            return $status;
        } else {
            $status['status'] == 'info';
            $status['message'] = 'Batch Already Validated';
            return $status;
        }

    }
    /*end(array)*/
    // public function packstyle($value=null,$type=null)
    // {
    //   if($type=='name')
    //   {
    //     $cond=" and lookup_code='".$value."'";
    //   }
    //     else if($type=='id')
    //   {
    //    $cond=" and lookuplines_id='".$value."'";
    //   }else
    //   { $cond =""; }

    //   $sql =\DB::select("select count(*) as cnt,lookuplines_id,lookup_code from a_lookuplines_t where 1=1 $cond");  
    //     return $sql;
    // }


    public function company($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and company_code='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and company_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,company_id,company_name,company_code from m_company_t where 1=1 $cond");
        return $sql;
    }
    public function locator($value = null, $type = null, $subinventory = null)
    {

        if ($type == 'name') {
            $cond = " and locator_code='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and sublocator_id='" . $value . "'";
        } else {
            $cond = "";
        }

        $sql = \DB::select("select count(*)  as cnt,sublocator_id,locator_code,subinventory_id,locator_name from m_sublocators_t where 1=1 $cond and subinventory_id='$subinventory' ");

        return $sql;
    }
    public function subinventory($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and subinventory_name='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and subinventory_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,subinventory_id,subinventory_name from m_subinventory_t where 1=1 $cond");

        return $sql;
    }
    public function account($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and concatenated_segments='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and f_account_structure_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,f_account_structure_id,concatenated_segments from f_account_structure_t where 1=1 $cond");
        return $sql;
    }
    public function hsn($value = null, $type = null, $cls_name = null)
    {
        if ($type == 'name') {
            $cond = " and classification_code='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and gst_code_hdr_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,gst_code_hdr_id,classification_name,classification_code from f_gst_code_hdr_t where 1=1 $cond and classification_name='$cls_name' ");
        return $sql;
    }
    public function uom($value = null, $type = null)
    {

        if ($type == 'name') {
            $cond = " and uom_code='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and uom_code_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,uom_code_id,code_meaning,uom_code from m_uom_codes_t where 1=1 $cond");

        return $sql;
    }
    public function product($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and concatenated_product='" . $value . "'";
        } else if ($type == 'alter') {
            $cond = " and product_alternate_name='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and product_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,product_id,concatenated_product,product_alternate_name from m_products_t where 1=1 $cond");
        return $sql;
    }
    public function pcb_group($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and concatenated_product='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and product_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,product_id,concatenated_product from m_products_t where 1=1 and product_group_id ='3' $cond");
        return $sql;
    }

    public function group($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and group_name='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and product_group_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,product_group_id,group_name from m_product_groups_t where 1=1 $cond");
        return $sql;
    }

    public function category($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and category_name='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and product_category_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,product_category_id,category_name from m_product_category_t where 1=1 $cond");
        return $sql;
    }

    public function product_class($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and product_classification='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and product_classification='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,product_classification from m_products_t where 1=1 $cond");
        return $sql;
    }

    public function group_class($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and group_classification='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and group_classification='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,group_classification from m_products_t where 1=1 $cond");
        return $sql;
    }

    public function bar_code($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and barcode_number='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and barcode_number='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,barcode_number from m_products_t where 1=1 $cond");
        return $sql;
    }

    public function gross_weight($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and gross_weight='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and gross_weight='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,gross_weight from m_products_t where 1=1 $cond");
        return $sql;
    }

    public function net_weight($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and net_weight='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and net_weight='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,net_weight from m_products_t where 1=1 $cond");
        return $sql;
    }



    public function subcatagory($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and subcategory_name='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and product_subcategory_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,product_subcategory_id,subcategory_name from m_product_subcategory_t where 1=1 $cond");
        return $sql;
    }

    public function product_type($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = ' and product_type="' . $value . '"';
        } else if ($type == 'id') {
            $cond = " and product_type_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,product_type_id,product_type from m_product_type_t where 1=1 $cond");
        return $sql;
    }

    public function productvariant($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and product_variant_name='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and product_variant_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,product_variant_id,product_variant_name from m_product_variants_t where 1=1 $cond");
        return $sql;
    }

    public function pack_type_name($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and product_pack_type_name='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and product_packs_type_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,product_packs_type_id,product_pack_type_name from i_product_packs_types_t where 1=1 $cond");
        return $sql;
    }

    public function product_pack($value = null, $type = null)
    {
        if ($type == 'name') {
            $cond = " and pack_name='" . $value . "'";
        } else if ($type == 'id') {
            $cond = " and packing_id='" . $value . "'";
        } else {
            $cond = "";
        }
        $sql = \DB::select("select count(*)  as cnt,packing_id,pack_name from i_product_packs where 1=1 $cond");
        return $sql;
    }
    /*Load Function*/
    public function LoadMaster($loadModname, $valBatch, $status)
    {
        $comnfun = new ProductuploadController;
        $status['status'] = '';
        $status['message'] = '';
        $sql = "select * from i_productupload_t  where  batch_status ='VALIDATED'  and batch_name='" . $loadModname . "'";
        $result = \DB::select($sql); 
        $data = array();
        $loadid = array();
        // dd($result);    
        if (count($result) > 0) {
            foreach ($result as $key => $value) {

                $group = $this->group($value->product_group_name, 'name');
                $data['product_group_id'] = $group[0]->product_group_id;

                $type_name = $this->product_type($value->product_type_name, "name");
                $pack_type_name = $this->pack_type_name($value->product_packtype_name, "name");

                $variant_name = $this->productvariant($value->product_variant_name, "name");
                $packing_cotton_box = $this->pcb_group($value->packing_cotton_box, 'name');
                $pack_name = $this->product_pack($value->product_pack_name, "name");
                $category = $this->category($value->product_category_name, 'name');
                $subcatagory = $this->subcatagory($value->product_subcategory_name, 'name');
                if ($group[0]->group_name == "FINISHED GOODS") {
                    $data['concatenated_product'] = $type_name[0]->product_type . " " . $variant_name[0]->product_variant_name . " " . $pack_name[0]->pack_name;
                } else if ($group[0]->group_name == "SEMI FINISHED GOODS") {
                    $data['concatenated_product'] = $type_name[0]->product_type . " " . $variant_name[0]->product_variant_name;
                } else {
                    $data['concatenated_product'] = $value->product_name;
                }

                $data['product_category_id'] = $category[0]->product_category_id;
                $data['product_subcategory_id'] = $subcatagory[0]->product_subcategory_id;
                $data['product_code'] = $value->product_code;
                $data['product_variant_id'] = $variant_name[0]->product_variant_id;
                $data['product_packtype_id'] = $pack_type_name[0]->product_packs_type_id;
                $data['product_pack_id'] = $pack_name[0]->packing_id;
                $data['product_type_id'] = $type_name[0]->product_type_id;
                $data['product_alternate_name'] = $value->product_alternate_name;
                $data['product_classification'] = $value->product_classification;
                $data['group_classification'] = $value->group_classification;

                $prm_uom_name = $this->uom($value->primary_uom_name, "name");

                $data['primary_uom_id'] = $prm_uom_name[0]->uom_code_id;
                $trx_name = $this->uom($value->trx_uom_name, "name");
                $data['trx_uom_id'] = $trx_name[0]->uom_code_id;
                $hsn_code = $this->hsn($value->hsn_code, "name", "HSN");
                $data['hsn_code'] = $hsn_code[0]->gst_code_hdr_id;
                $default_hsn_code = $this->hsn($value->default_hsn_code, "name", "HSN");
                $data['defalut_hsn_code'] = $default_hsn_code[0]->gst_code_hdr_id;
                $acc_name = $this->account($value->account_code_name, "name");
                $data['account_code_id'] = $acc_name[0]->f_account_structure_id;
                $control_account = $this->account($value->control_account, "name");
                $data['control_account_id'] = $control_account[0]->f_account_structure_id;
                $dis_acc_name = $this->account($value->disc_account_code_name, "name");
                $data['disc_account_code'] = $dis_acc_name[0]->f_account_structure_id;
                $subinventory_name = $this->subinventory($value->subinventory_name, "name");

                $data['subinventory_id'] = $subinventory_name[0]->subinventory_id;
                if ($value->locator_control == "YES")
                    $data['locator_control'] = "Yes";
                else
                    $data['locator_control'] = "No";

                $sublocator_name = $this->locator($value->sublocator_name, "name", $subinventory_name[0]->subinventory_id);

                $data['sublocator_id'] = $sublocator_name[0]->sublocator_id;

                $data['min_order_qty'] = $value->min_stock_level1;
                $data['min_stock_level2'] = $value->min_stock_level2;
                $data['min_stock_level3'] = $value->min_stock_level3;
                $data['max_order_qty'] = $value->max_order_qty;
                $data['re_order_level'] = $value->re_order_level;
                $data['commodity_code'] = $value->company_code;
                if ($value->tax_credit == "YES")
                    $data['tax_credit'] = "Yes";
                else
                    $data['tax_credit'] = "No";
                $data['qc_type'] = $value->qc_type;
                $data['mpq_qty'] = $value->mpq_qty;

                if ($value->active == "YES")
                    $data['active'] = "Yes";
                else
                    $data['active'] = "No";
                if ($value->qc_check == "YES")
                    $data['qc_check'] = "Yes";
                else
                    $data['qc_check'] = "No";
                $data['product_sub_assembly_id'] = "";
                $data['packing_ctn_box'] = $packing_cotton_box[0]->product_id;
                $data['product_expiry_days'] = $value->expiry_days;
                $data['expiry_days'] = $value->expiry_days;
                $uploadid = $value->product_upload_id;
                $loadid[$key] = $value->product_upload_id;
                $data['company_id'] = \Session::get('companyid');
                $data['created_by'] = \Session::get('id');
                $data['location_id'] = \Session::get('location');
                $data['organization_id'] = \Session::get('organization');
                $data['product_status'] = "INITIATED";

                $t = 0;
                $approverid = $this->Approvaldatacheck('product', $t);
                //	dd($approverid);
                if ($approverid == "0") {

                    $data['approver_id'] = \Session::get('id');
                    $data['product_status'] = "APPROVED";
                } else {

                    $data['approver_id'] = $approverid;

                }

                try {

                    $id = \DB::table('m_products_t')->insertGetId($data);

                    $in = implode(',', $loadid);
                    $sql = "UPDATE i_productupload_t set batch_status='LOADED'  where product_upload_id in ($uploadid)";

                    \DB::update($sql);

                } catch (\Illuminate\Database\QueryException $e) {
                    $message = explode('(', $e->getMessage());
                    $dbCode = rtrim($message[0], ']');
                    $dbCode = trim($dbCode, '[');
                    $error = explode(":", $dbCode);

                    $status['status'] = 'error';
                    $status['message'] = $error[2];
                    $comment = '"' . $error[2] . '"';

                    $in = implode(',', $loadid);
                    $sql = "UPDATE i_productupload_t set batch_status='ERROR',batch_comments=$comment where product_upload_id in ($uploadid)";
                    \DB::update($sql);
                    return $status;
                }


            }
            $status['status'] = 'success';
            $status['message'] = 'Product Data Loaded Sucessfully';
            return $status;
            return true;
        } else {

            $sql = \DB::select("select * from i_productupload_t where batch_name='" . $loadModname . "'");
            if ($sql[0]->batch_status == "UPLOADED") {
                $status['status'] = 'info';
                $status['message'] = 'Pls Validate the Batch First..!';
                return $status;
            } else if ($sql[0]->batch_status == "ERROR") {
                $status['status'] = 'error';
                $status['message'] = 'Batch Error..!';
                return $status;
            } else {
                $status['status'] = 'info';
                $status['message'] = 'Product Data already Loaded';
                return $status;
            }
        }
    }
    /*End*/

    /* purpose:to return status*/
    public function getProductvalidate(Request $request)
    {
        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND i_productupload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
        if (isset($_GET['type'])) {
            $type = 'PRODUCTUPLOAD';
            $message['status'] = 'success';
            switch ($_GET['type']) {
                case 'verify':
                    $upload = $this->uploadValidation($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    return $upload;
                    break;
                case 'load':
                    $upload = $this->LoadMaster($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    return $upload;
                    break;
            }
        }
    }
    /*end*/


}
