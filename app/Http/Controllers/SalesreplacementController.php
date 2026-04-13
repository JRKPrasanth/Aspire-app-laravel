<?php
namespace App\Http\Controllers;

use App\Salesreplacement;
use App\Salesreplacementlines;
use App\Soorder;
use App\Soorderlines;
use App\Customersites;
use App\Deliveryterms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use DB, Session;
use DateTime, File;
use Yajra\DataTables\DataTables;

class SalesreplacementController extends Controller
{
    public $module = "Salesreplacement";
    public function __construct()
    {

        $this->somodel = new Soorder;
        $this->sosubmodel = new Soorderlines;

        $this->model = new Salesreplacement;
        $this->submodel = new Salesreplacementlines;


        $this->data['pageMethod'] = \Request::route()->getName();

        $this->table = "s_replacement_hdr_t";
        $this->subtable = "s_replacement_lines_t";
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => $this->data['pageMethod'],
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );

        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageFormtype'] = 'ajax';

    }

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

        $this->data['pageMethod'] = \Request::route()->getName();
        ;
        if ($this->data['pageMethod'] == "salesreplacementapproval") {
            $this->data['status'] = "INITIATED";
        } else if ($this->data['pageMethod'] == "dispatchfrmreplace") {
            $this->data['status'] = "APPROVED";
        } else {
            $this->data['status'] = '';
        }

        return view('salesreplacement.table', $this->data);
    }

    public function replaceindex()
    {

        $this->data['pageMethod'] = \Request::route()->getName();
        ;


        return view('salesreplacement.replacetable', $this->data);
    }


    public function getSalesreplacementData()
    {

        $wh = '';
        $app_id = \Session::get('id');
        $col_name = '';

        if (isset($_GET['status'])) {
            if ($_GET['status'] != "") {
                $wh .= ' and s_replacement_hdr_t.invoice_status="' . $_GET['status'] . '"';
                $col_name = "invoice_status";
                $op = "=";
                $status_val = "'" . $_GET['status'] . "'";
            }
        }


        if ($col_name != '') {
            $wh .= $grid_data = $this->grid_statuscheck('s_replacement_hdr_t', 'invoice_date', $col_name, $op, $status_val);
        } else {
            $wh .= $grid_data = $this->grid_check('s_replacement_hdr_t', 'invoice_date');
        }

        $SQL = "SELECT s_replacement_hdr_t.`replacement_hdr_id`,s_replacement_hdr_t.`invoice_type`,s_replacement_hdr_t.`replacement_number`, s_replacement_hdr_t.`invoice_date`, s_replacement_hdr_t.remarks, s_replacement_hdr_t.invoice_status, s_replacement_hdr_t.savestatus, s_replacement_hdr_t.shiped_status, s_replacement_hdr_t.source,hr_employee_t.first_name, i_pricelist_hdr_t.pricelist_name, m_customers_t.customer_id, m_customers_t.customer_name  FROM `s_replacement_hdr_t` left JOIN m_customers_t  on(m_customers_t.customer_id=s_replacement_hdr_t.`ship_to_customer_id` ) left join hr_employee_t on (hr_employee_t.employee_id = s_replacement_hdr_t.employee_id) left join i_pricelist_hdr_t  on ( i_pricelist_hdr_t.pricelist_hdr_id=s_replacement_hdr_t.invoice_pricelist_id) where 1=1 $wh";


        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);

    }


    public function getSalesreplacementinvoiceData()
    {


        $SQL = "SELECT
s_invoice_hdr_t.`invoice_hdr_id`,
s_invoice_hdr_t.`invoice_type`,
s_invoice_hdr_t.`invoice_number`,
s_invoice_hdr_t.`invoice_date`,
s_invoice_hdr_t.remarks,
s_invoice_hdr_t.invoice_status,
s_invoice_hdr_t.savestatus,
s_invoice_hdr_t.shiped_status,
s_invoice_hdr_t.source,hr_employee_t.first_name,
i_pricelist_hdr_t.pricelist_name,
m_customers_t.customer_id, 
m_customers_t.customer_name 
FROM `s_invoice_hdr_t`
left JOIN m_customers_t  on(
m_customers_t.customer_id=s_invoice_hdr_t.`ship_to_customer_id`
) left join hr_employee_t on (hr_employee_t.employee_id = s_invoice_hdr_t.employee_id)
 left join i_pricelist_hdr_t  on (
    i_pricelist_hdr_t.pricelist_hdr_id=s_invoice_hdr_t.invoice_pricelist_id)
where 1=1 and s_invoice_hdr_t.shiped_status='SHIPPED' and s_invoice_hdr_t.invoice_type!='SAMPLE'";

        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);

    }



    public function replace($id = null, $invtype = null)
    {
        $custaddress1 = new SoorderController;
        $this->data['pageModule'] = "Salesreplacement";
        $this->data['return_url'] = \Request::route()->getName();
        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'Salesreplacement')->get();
        $this->data['country_new'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name', '');
        $this->data['state_new'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', '');
        $this->data['city_new'] = $this->jCombologin('m_cities_t', 'city_id', 'city_name', '');
        $this->data['tax_group_id_pop'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
        $this->data['id'] = '';
        $this->data['labour'] = '';
        $this->data['pagemode'] = 'replace';
        $table = \DB::table('s_invoice_hdr_t')->where('invoice_hdr_id', $id)->get();
        // dd($table);
        $this->data['row'] = $table[0];
        $this->data['row']->savestatus = '';
        $this->data['row']->replacement_hdr_id = '';
        $this->data['row']->replacement_number = '';
        $this->data['row']->shiped_status = '';
        $this->data['row']->discount_id = '';
        $this->data['row']->schemes = '';
        $this->data['row']->trade_discount = '';
        $this->data['row']->invoice_type = 'REPLACEMENT';
        $this->data['row']->source = 'REPLACEMENT';
        $this->data['row']->trade_discount_pre = '';
        $this->data['row']->round_off = '';
        $this->data['savestatus'] = $table[0]->savestatus;
        $this->data['approver_comments'] = $table[0]->approver_comments;
        $this->data['approved_date'] = $table[0]->approved_date;
        $this->data['lr_date'] = $table[0]->lr_date;
        $this->data['customer_comments'] = $table[0]->customer_comments;
        $this->data['remarks'] = $table[0]->remarks;
        $this->data['lr_no'] = $table[0]->lr_no;
        if ($table[0]->schemes != "null" && $table[0]->schemes != "") {
            $schemes = implode(',', json_decode($table[0]->schemes));
        } else {
            $schemes = '';
        }

        $this->data['schemes'] = $this->jcustommultiselect('s_schemes_hdr_t', 'schemes_hdr_id', 'schemes_name', $schemes, '');
        $this->data['row']->trade_discount = $table[0]->trade_discount;
        $this->data['row']->round_off = $table[0]->round_off;
        $this->data['row']->trade_discount_pre = $table[0]->trade_discount_pre;
        $sesdate = \Session::get('p_date_format');
        $this->data['row']->due_date = date($sesdate, strtotime($table[0]->due_date));
        $this->data['ship_to_customer_id'] = $table[0]->ship_to_customer_id;
        $this->data['ship_to_address_id'] = $table[0]->ship_to_address_id;
        $this->data['bill_to_address_id'] = $table[0]->bill_to_address_id;
        $this->data['row']->employee_id = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]->employee_id);
        $this->data['discount'] = $this->jCombodiscount('m_discounts_hdr_t', 'ar_discount_hdr_id', 'discount_name', $table[0]->discount_id);
        $this->data['invoice_currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $table[0]->invoice_currency);
        $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);
        $this->data['ship_to_customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_number|customer_name', $table[0]->ship_to_customer_id);
        $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $table[0]->tds_account_id);
        $this->data['salesperson_id'] = $this->jCombo('s_salesperson_t', 'salesperson_id', 'salesperson_name', $table[0]->salesperson_id);
        $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $table[0]->project_id);
        $this->data['pricelist'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $table[0]->invoice_pricelist_id, ' and price_list_type="Sales"');
        $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $table[0]->payment_term_id);
        $this->data['payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $table[0]->payment_method_id);
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);
        $this->data['ar_frieghtcarriers_hdr_id'] = $this->jCombo('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $table[0]->ar_frieghtcarriers_hdr_id);
        $this->data['delivery_term_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $table[0]->delivery_term_id, ' and source_type_id="Sales"');
        $this->data['bill_to_address'] = $custaddress1->sobilladdress($table[0]->bill_to_address_id);
        $this->data['ship_to_address'] = $custaddress1->soshipaddress($table[0]->ship_to_address_id);
        $tablelines = \DB::table('s_invoice_lines_t')->where('invoice_hdr_id', $id)->get();
        // dd($tablelines);
        $this->data['linedata'] = $tablelines;
        $comp = \Session::get('companyid');
        foreach ($this->data['linedata'] as $key => $value) {
            $this->data['linedata'][$key]->taxgroup_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
            $this->data['linedata'][$key]->part_no = $this->jcustomselecttool('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $table[0]->ship_to_customer_id);
            $this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
            $this->data['linedata'][$key]->hsn_code = $this->jCombo('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->hsn_code);
            $this->data['linedata'][$key]->tax_excemption = $value->tax_excemption;
            $this->data['linedata'][$key]->description = $value->description;
            $this->data['linedata'][$key]->sales_order = $value->sales_order;
            $this->data['linedata'][$key]->sales_order_qty = $value->sales_order_qty;
            $this->data['linedata'][$key]->sales_order_invoice = $value->sales_order_invoice;
            $this->data['linedata'][$key]->invoiced_qty = $value->invoice_qty;
            $this->data['linedata'][$key]->invoice_line_id = '';
            $this->data['linedata'][$key]->invoice_hdr_id = '';
            $this->data['linedata'][$key]->salesorder_qty = 0;
            $this->data['linedata'][$key]->discount_percentage = $value->discount_percentage;
            $this->data['linedata'][$key]->discount_amount = $value->discount_amount;
            $this->data['linedata'][$key]->reference_line_id = '';
            $this->data['linedata'][$key]->reference_hdr_id = '';
            $this->data['linedata'][$key]->unit_price = $value->unit_price;
            //dd($value);
            $this->data['product'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'salesreplacement');
            if ($table[0]->invoice_type != "LABOUR") {
                $qoh_qty = DB::SELECT("select sum(f.qty-f.qtyy) as qoh_qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id=" . $value->product_id . "  and company_id=" . $comp . " and i_qoh_detail_t.qualitystatus=1 GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id=" . $value->product_id . "  and company_id=" . $comp . " GROUP by product_id)f");
            } else {
                $qoh_qty = [];
            }
            if (count($qoh_qty) > 0) {
                $qty = $qoh_qty[0]->qoh_qty;
            } else {
                $qty = 0;
            }
            $this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
            $this->data['linedata'][$key]->qoh = $qty;
        }
        //dd($this->data);
        return view('salesreplacement.form', $this->data);
    }


    public function create($id = null, $invtype = null, $labid = null)
    {

        if ($id != '') {
            $custaddress1 = new SoorderController;
            if ($this->data['pageMethod'] == "Salesreplacementapprovalcreate") {
                $this->data['pageModule'] = "Salesreplacementapproval";
            }
            $this->data['tax_group_id_pop'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');


            $this->data['id'] = $id;
            $this->data['labour'] = $labid;
            $this->data['pagemode'] = 'edit';
            $table = \DB::table('s_replacement_hdr_t')->where('replacement_hdr_id', $id)->get();
            $this->data['row'] = $table[0];
            $this->data['savestatus'] = "SAVE";
            $this->data['approver_comments'] = $table[0]->approver_comments;
            $this->data['approved_date'] = $table[0]->approved_date;
            $this->data['lr_date'] = $table[0]->lr_date;
            $this->data['customer_comments'] = $table[0]->customer_comments;
            $this->data['remarks'] = $table[0]->remarks;
            $this->data['lr_no'] = $table[0]->lr_no;
            if ($table[0]->schemes != "null" && $table[0]->schemes != "") {
                $schemes = implode(',', json_decode($table[0]->schemes));
            } else {
                $schemes = '';
            }
            $this->data['schemes'] = $this->jcustommultiselect('s_schemes_hdr_t', 'schemes_hdr_id', 'schemes_name', $schemes, '');


            $this->data['row']->trade_discount = $table[0]->trade_discount;
            $this->data['row']->round_off = $table[0]->round_off;

            $this->data['row']->trade_discount_pre = $table[0]->trade_discount_pre;
            // dd($this->data['schemes']);
            $sesdate = \Session::get('p_date_format');
            $this->data['row']->due_date = date($sesdate, strtotime($table[0]->due_date));
            $this->data['ship_to_customer_id'] = $table[0]->ship_to_customer_id;
            $this->data['ship_to_address_id'] = $table[0]->ship_to_address_id;
            $this->data['bill_to_address_id'] = $table[0]->bill_to_address_id;
            $this->data['row']->employee_id = $this->jCombo('hr_employee_t', 'employee_id', 'first_name|last_name', $table[0]->employee_id);

            $this->data['discount'] = $this->jCombodiscount('m_discounts_hdr_t', 'ar_discount_hdr_id', 'discount_name', $table[0]->discount_id);
            $this->data['invoice_currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $table[0]->invoice_currency);

            $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);
            $this->data['ship_to_customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_number|customer_name', $table[0]->ship_to_customer_id);
            $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $table[0]->tds_account_id);
            $this->data['salesperson_id'] = $this->jCombo('s_salesperson_t', 'salesperson_id', 'salesperson_name', $table[0]->salesperson_id);
            $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $table[0]->project_id);
            $this->data['pricelist'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $table[0]->invoice_pricelist_id, ' and price_list_type="Sales"');
            $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $table[0]->payment_term_id);
            $this->data['payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $table[0]->payment_method_id);
            $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);
            $this->data['ar_frieghtcarriers_hdr_id'] = $this->jCombo('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $table[0]->ar_frieghtcarriers_hdr_id);
            $this->data['delivery_term_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $table[0]->delivery_term_id, ' and source_type_id="Sales"');
            $this->data['bill_to_address'] = $custaddress1->sobilladdress($table[0]->bill_to_address_id);
            $this->data['ship_to_address'] = $custaddress1->soshipaddress($table[0]->ship_to_address_id);
            /****/
            $tablelines = \DB::table('s_replacement_lines_t')->where('replacement_hdr_id', $id)->get();
            $this->data['linedata'] = $tablelines;
            foreach ($this->data['linedata'] as $key => $value) {

                $this->data['linedata'][$key]->taxgroup_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
                $this->data['linedata'][$key]->part_no = $this->jcustomselecttool('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $table[0]->ship_to_customer_id);

                $this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                $this->data['linedata'][$key]->hsn_code = $this->jCombo('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->hsn_code);
                $this->data['linedata'][$key]->tax_excemption = $value->tax_excemption;
                $this->data['linedata'][$key]->description = $value->description;
                $this->data['linedata'][$key]->sales_order = $value->sales_order;
                $this->data['linedata'][$key]->sales_order_qty = $value->sales_order_qty;
                $this->data['linedata'][$key]->sales_order_invoice = $value->sales_order_invoice;
                $this->data['linedata'][$key]->invoiced_qty = $value->invoice_qty;
                $this->data['linedata'][$key]->replacement = $value->replacement;
                $comp = \Session::get('companyid');
                if ($table[0]->invoice_type != "LABOUR") {
                    $qoh_qty = DB::SELECT("select sum(f.qty-f.qtyy) as qoh_qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id=" . $value->product_id . "  and company_id=" . $comp . " and i_qoh_detail_t.qualitystatus=1 GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id=" . $value->product_id . "  and company_id=" . $comp . " GROUP by product_id)f");
                } else {
                    $qoh_qty = [];
                }


                if ($table[0]->ship_to_customer_id == "") {
                    $this->data['product'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'salesreplacement');
                } else {
                    $this->data['product'] = $custaddress1->getcustomerpriceproduct($table[0]->ship_to_customer_id, '');
                }
                if ($table[0]->ship_to_customer_id == "" && $value->product_id == "") {
                    $this->data['linedata'][$key]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'salesreplacement');
                } else if ($table[0]->ship_to_customer_id != "" && $value->product_id == "") {
                    $this->data['linedata'][$key]->product_id = $custaddress1->getcustomerpriceproduct($table[0]->ship_to_customer_id, $value->product_id);
                } else {
                    $this->data['linedata'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                }

                if (count($qoh_qty) > 0) {
                    $qty = $qoh_qty[0]->qoh_qty;
                } else {
                    $qty = 0;
                }
                $this->data['linedata'][$key]->qoh = $qty;


            }
        } else {
            $this->data['tax_group_id_pop'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');


            $this->data['id'] = '';
            $this->data['labour'] = '';
            $this->data['pagemode'] = 'edit';
            $this->data['row'] = '';
            $this->data['savestatus'] = "SAVE";
            $this->data['approver_comments'] = '';
            $this->data['approved_date'] = '';
            $this->data['lr_date'] = '';
            $this->data['customer_comments'] = '';
            $this->data['remarks'] = '';
            $this->data['lr_no'] = '';

            $schemes = '';

            $this->data['schemes'] = $this->jcustommultiselect('s_schemes_hdr_t', 'schemes_hdr_id', 'schemes_name', '', '');

            $this->data['row'] = (object) array();
            $this->data['row']->trade_discount = '';
            $this->data['row']->round_off = '';
            $this->data['row']->invoice_type = 'DIRECT REPLACEMENT';
            $this->data['row']->source = 'REPLACEMENT';

            $this->data['row']->trade_discount_pre = '';
            $sesdate = \Session::get('p_date_format');
            $this->data['row']->employee_id = $this->jCombo('hr_employee_t', 'employee_id', 'first_name|last_name', '');

            $this->data['discount'] = $this->jCombodiscount('m_discounts_hdr_t', 'ar_discount_hdr_id', 'discount_name', '');
            $this->data['invoice_currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', '');

            $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', '');
            $this->data['ship_to_customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_number|customer_name', '');
            $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
            $this->data['salesperson_id'] = $this->jCombo('s_salesperson_t', 'salesperson_id', 'salesperson_name', '');
            $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', '');
            $this->data['pricelist'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', '', ' and price_list_type="Sales"');
            $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', '');
            $this->data['payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', '');
            $logged_id = \Session::get('id');
            $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $logged_id);
            $this->data['ar_frieghtcarriers_hdr_id'] = $this->jCombo('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', '');
            $this->data['delivery_term_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', '', ' and source_type_id="Sales"');
            $this->data['pageMethod'] = "directreplacement";
            $this->data['row']->invoice_date = date("Y-m-d");
            /****/

        }


        $this->data['return_url'] = \Request::route()->getName();

        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'salesreplacement')->get();

        $this->data['country_new'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name', '');
        $this->data['state_new'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', '');
        $this->data['city_new'] = $this->jCombologin('m_cities_t', 'city_id', 'city_name', '');

        return view('salesreplacement.form', $this->data);
    }

    public function invoicefilesave(Request $request)
    {


        if ($request->hasfile('email_attachment')) {
            File::deleteDirectory(public_path('uploads/soinvoiceupload/SOINV' . $_POST['invoice_hdr_id']));

            foreach ($request->file('email_attachment') as $file) {

                $name = $file->getClientOriginalName();

                $file->move(public_path() . '/uploads/soinvoiceupload/SOINV' . $_POST['invoice_hdr_id'] . '/', $name);
                $data[] = $name;
            }
            $attachfile_name = json_encode($data);
            \DB::update("update s_invoice_hdr_t set attachfile_name='" . $attachfile_name . "' where invoice_hdr_id=" . $_POST['invoice_hdr_id']);
            return 1;
        } else {
            $var = File::deleteDirectory(public_path('uploads/soinvoiceupload/SOINV' . $_POST['invoice_hdr_id']));

            \DB::update("update s_invoice_hdr_t set attachfile_name='' where so_dispatch_hdr_id=" . $_POST['invoice_hdr_id']);
            return 2;
        }

    }

    public function save(Request $request)
    {

        $id = '';
        $data = $this->validatePost($request->all(), $this->table, 'header');

        $lines_data = $this->validatePost($request->all(), $this->subtable, 'lines');

        if ($data['source'] == "DISPATCH") {
            $lines_data['replacement'] = $lines_data['qty'];
            $data['source'] = "REPLACEMENT";
        }
        //   dd($lines_data);
        $status = $request->input('invoice_status');
        // dd($status);
        if ($status != "DRAFT" && $status != "REJECTED") {
            if ($data['invoice_grand_total'] == 0) {
                $data['invoice_grand_total'] = "0";
            }
            $approverid = $this->Approvaldatacheck('soinvoice', $data['invoice_grand_total']);
            //dd($approverid);
            if ($approverid == "0") {
                $data['approver_id'] = \Session::get('id');
                $status = "APPROVED";
                $data['invoice_status'] = "APPROVED";
            } else {
                $data['approver_id'] = $approverid;
            }
        }


        if ($_POST['replacement_number'] == "") {
            $seqno = $this->Seqnoapproved('SOINV', 's_replacement_hdr_t', $_POST['invoice_type'], 'invoice_status', $data['invoice_status'], $this->data['pageModule'], 'replace_count');

            $data['replace_count'] = $seqno[1];

            $data['replacement_number'] = $seqno[0];
            // dd($data);  

        } else {
            $seqno[0] = $_POST['replacement_number'];
        }

        if ($data['invoice_status'] == "INITIATED") {
            $invoice_status = 'Saved' . ' Successfully';
        } else {
            $invoice_status = $data['invoice_status'] . ' Successfully';
        }
        if ($data['invoice_status'] == "APPROVED" || $data['invoice_status'] == "REJECTED")
            $msg = $data['invoice_status'] . '  Successfully ';
        else
            $msg = 'Saved Successfully ';

        \DB::beginTransaction();
        try {
            $data['due_date'] = date('Y-m-d', strtotime($_POST['due_date']));
            $data['schemes'] = json_encode($_POST['schemes']);

            $id = $this->model->insertRow($data);

            unset($lines_data['schemes']);

            if ($_POST['replacement_hdr_id'] == "") {
                $action = 'create';
            } else {
                $action = 'edit';
            }

            $this->auditlog($id, "Sales replace", $action, $data, "s_replacement_hdr_t");

            $lid = $this->submodel->subgridSave($lines_data, $id);

            $sales_id = $id;
            $invoice_hdr_id = $_POST['replacement_hdr_id'];



            if ($invoice_hdr_id == '') {

                if ($request->hasfile('choosefile')) {

                    foreach ($request->file('choosefile') as $file) {
                        $name = $file->getClientOriginalName();

                        $file->move(public_path() . '/uploads/soinvoiceupload/SORINV' . $sales_id . '/', $name);
                        $dataupload[] = $name;
                    }
                }
                $attachfile_name = json_encode($dataupload);

                \DB::update("update s_replacement_hdr_t set attachfile_name='" . $attachfile_name . "' where replacement_hdr_id='$sales_id'");
                $this->data['notymsg'] = "yes";

            } else {

                $existing_file = $request->input('existing_file');
                $choose_file = $request->file('choosefile');

                $existing_file = explode(",", $existing_file);
                if (count($choose_file) == 0 && count($existing_file) > 0) {
                    if (count($existing_file) == 1 && $existing_file[0] == '') {

                        \DB::update("update s_replacement_hdr_t set attachfile_name='' where replacement_hdr_id='$sales_id'");
                    } else {
                        $get_attach = DB::table('s_replacement_hdr_t')->where('replacement_hdr_id', $sales_id)->get();
                        $attach_file = json_decode($get_attach[0]->attachfile_name);
                        $attach_file1 = array();

                        foreach ($attach_file as $k => $v) {
                            $attach_file1[] = $v;
                        }
                        $array_diff = array_diff($attach_file1, $existing_file);

                        if (count($array_diff) > 0) {
                            foreach ($array_diff as $k => $v) {
                                unlink(public_path() . '/uploads/soinvoiceupload/SORINV' . $sales_id . '/' . $v);
                            }
                            $attachfile_name = json_encode($existing_file);

                            \DB::update("update s_replacement_hdr_t set attachfile_name='" . $attachfile_name . "' where replacement_hdr_id='$sales_id'");
                        }
                    }
                } else if (count($choose_file) > 0 && count($existing_file) > 0) {

                    foreach ($request->file('choosefile') as $file) {
                        $name = $file->getClientOriginalName();

                        $file->move(public_path() . '/uploads/soinvoiceupload/SORINV' . $sales_id . '/', $name);
                        $dataupload[] = $name;
                    }

                    $get_attach = DB::table('s_replacement_hdr_t')->where('replacement_hdr_id', $sales_id)->get();
                    $attach_file = json_decode($get_attach[0]->attachfile_name);

                    $attach_file1 = array();

                    foreach ($attach_file as $k => $v) {
                        $attach_file1[] = $v;
                    }

                    $array_diff = array_diff($attach_file1, $existing_file);

                    if (count($array_diff) > 0) {
                        foreach ($attach_file as $k => $v) {
                            unlink(public_path() . '/uploads/soinvoiceupload/SORINV' . $sales_id . '/' . $v);


                        }

                        $attachfile_name = array_merge($existing_file, $dataupload);
                        $attachfile_name = json_encode($attachfile_name);

                    } else {
                        $attachfile_name = array_merge($attach_file1, $dataupload);
                        $attachfile_name = json_encode($attachfile_name);
                    }


                    \DB::update("update s_replacement_hdr_t set attachfile_name='" . $attachfile_name . "' where replacement_hdr_id='$sales_id'");

                } else if (count($choose_file) > 0 && count($existing_file) == 0) {
                    foreach ($request->file('choosefile') as $file) {
                        $name = $file->getClientOriginalName();

                        $file->move(public_path() . '/uploads/soinvoiceupload/SORINV' . $sales_id . '/', $name);
                        $dataupload[] = $name;
                    }
                    $attachfile_name = json_encode($dataupload);
                    \DB::update("update s_replacement_hdr_t set attachfile_name='" . $attachfile_name . "' where replacement_hdr_id='$sales_id'");
                }
            }


            \DB::commit();



            return response()->json(array('status' => 'success', 'message' => $invoice_status, 'id' => $id, 'lid' => $lid, 'auto_no' => $seqno[0]));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            dd($dbCode);
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }



    public function edit(Request $request, $id, $labid = null)
    {
        $this->data['id'] = $id;
        $this->data['labour'] = $labid;
        $this->data['pagemode'] = 'edit';
        //dd($labid);
        $table = \DB::table('s_invoice_hdr_t')->where('invoice_hdr_id', $id)->get();
        $this->data['row'] = $table[0];
        $this->data['savestatus'] = $table[0]->savestatus;
        $this->data['row']->trade_discount = $table[0]->trade_discount;
        $this->data['row']->round_off = $table[0]->round_off;
        $this->data['row']->trade_discount_pre = $table[0]->trade_discount_pre;
        $this->data['schemes'] = $this->jCombo('s_schemes_hdr_t', 'schemes_hdr_id', 'schemes_name', $table[0]->schemes);
        $tablelines = \DB::table('s_invoice_lines_t')->where('invoice_hdr_id', $id)->get();
        $this->data['linedata'] = $tablelines;
        $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);
        $this->data['ship_to_customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_number|customer_name', $table[0]->ship_to_customer_id);

        $this->data['pricelist'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $table[0]->invoice_pricelist_id, ' and price_list_type="Sales"');

        $this->data['ship_to_address_id'] = $this->jCombo('m_customer_sites_t', 'customer_site_id', 'customer_site_name', $table[0]->ship_to_address_id);
        $this->data['salesperson_id'] = $this->jCombo('s_salesperson_t', 'salesperson_id', 'salesperson_name', $table[0]->salesperson_id);
        $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $table[0]->project_id);
        $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'Salesreplacement');
        $this->data['uom_code_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');

        $this->data['cusnameopt'] = $this->jqgridselect('m_customers_t', 'customer_name', 'customer_name');
        $this->data['prdcatopt'] = $this->jqgridselect('m_product_category_t', 'product_category_id', 'category_name');
        $this->data['prdnameopt'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');

        $this->data['discount'] = $this->jCombodiscount('m_discounts_hdr_t', 'ar_discount_hdr_id', 'discount_name', $table[0]->discount_id);

        $this->data['return_url'] = \Request::route()->getName();

        foreach ($this->data['linedata'] as $key => $value) {
            $this->data['tax_group_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
            $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'Salesreplacement');
            $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
            $this->data['linedata'][$key]->hsn_code = $this->jCombo('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->hsn_code);
        }
        $this->data['cusnameopt'] = $this->jqgridselect('m_customers_t', 'customer_name', 'customer_name');

        return view('Salesreplacement.form', $this->data);
    }

    public function getPrint($id = null, $invoice = null)
    {
        $customer = DB::table('s_invoice_hdr_t')->leftjoin('m_customers_t', 'm_customers_t.customer_id', '=', 's_invoice_hdr_t.ship_to_customer_id')
            ->leftjoin('i_pricelist_hdr_t', 'i_pricelist_hdr_t.pricelist_hdr_id', '=', 's_invoice_hdr_t.invoice_pricelist_id')
            ->leftjoin('m_projects_t', 'm_projects_t.project_id', '=', 's_invoice_hdr_t.project_id')
            ->leftjoin('s_salesperson_t', 's_salesperson_t.salesperson_id', '=', 's_invoice_hdr_t.salesperson_id')
            ->leftjoin('s_salesorder_hdr_t', 's_salesorder_hdr_t.sales_hdr_id', '=', 's_invoice_hdr_t.salesperson_id')
            ->select(
                's_invoice_hdr_t.invoice_number',
                's_invoice_hdr_t.invoice_date',
                's_invoice_hdr_t.invoice_type',
                's_invoice_hdr_t.invoice_status',
                's_invoice_hdr_t.source',
                's_invoice_hdr_t.lr_no',
                's_invoice_hdr_t.ship_to_customer_id',
                's_invoice_hdr_t.bill_to_address_id',
                's_invoice_hdr_t.ship_to_address_id',
                's_invoice_hdr_t.payment_term_id',
                's_invoice_hdr_t.invoice_hdr_id',
                's_invoice_hdr_t.company_id',
                's_invoice_hdr_t.reference_number',
                's_invoice_hdr_t.delivery_term_id',
                's_invoice_hdr_t.trade_discount',
                's_invoice_hdr_t.round_off',
                's_invoice_hdr_t.reference_source_id',
                's_invoice_hdr_t.company_id',
                's_invoice_hdr_t.location_id',
                's_invoice_hdr_t.employee_id',
                's_invoice_hdr_t.ar_frieghtcarriers_hdr_id',
                'm_customers_t.customer_name',
                's_salesorder_hdr_t.sales_order_no',
                's_salesorder_hdr_t.sales_order_date',
                'i_pricelist_hdr_t.pricelist_name',
                's_invoice_hdr_t.trade_discount_pre',
                'i_pricelist_hdr_t.pricelist_hdr_id',
                'm_projects_t.project_name',
                's_salesperson_t.salesperson_name',
                's_invoice_hdr_t.remarks',
                's_invoice_hdr_t.invoice_tax_total',
                's_invoice_hdr_t.invoice_grand_total'
            )->where('invoice_hdr_id', $id)->get();

        if (!empty($customer)) {
            $this->data['lr_no'] = $customer[0]->lr_no;
            $this->data['pack_weight'] = '';
            $this->data['packaging_qty'] = '';
            $this->data['invoice_no'] = $customer[0]->invoice_number;
            $this->data['invoice_hdr_id'] = $customer[0]->invoice_hdr_id;
            $this->data['invoice_date'] = $customer[0]->invoice_date;
            $this->data['invoice_type'] = $customer[0]->invoice_type;
            $this->data['trade_discount'] = $customer[0]->trade_discount;
            $this->data['round_off'] = $customer[0]->round_off;
            $this->data['reference_number'] = $customer[0]->reference_number;
            $this->data['sales_order_no'] = $customer[0]->sales_order_no;
            $this->data['sales_order_date'] = $customer[0]->sales_order_date;
            $this->data['remarks'] = $customer[0]->remarks;
            $deliveryterm = Deliveryterms::where('delivery_terms_id', $customer[0]->delivery_term_id)->pluck('delivery_term_name')->first();
            $this->data['deliveryterm'] = $deliveryterm;
            $this->data['despatch_through'] = $this->getFreight($customer[0]->ar_frieghtcarriers_hdr_id);
            $comp = \DB::table('m_company_t')->where('company_id', $customer[0]->company_id)->get();
            // dd($comp[0]);
            if ($comp->isNotEmpty()) {
                $this->data['company_name'] = $comp[0]->company_name;
                $this->data['cmp_gst_no'] = $comp[0]->gst_no;
                $this->data['pan_no'] = $comp[0]->pan_no;
                $this->data['email_id'] = $comp[0]->email_id;
                $this->data['cin_no'] = $comp[0]->cin_no;
                $this->data['excise_registration_no'] = $comp[0]->excise_registration_no;
                $this->data['tax_reg_no'] = $comp[0]->tax_reg_no;
                $this->data['website_address'] = $comp[0]->website_address;
            } else {
                $this->data['company_name'] = "";
                $this->data['cmp_gst_no'] = "";
                $this->data['pan_no'] = "";
                $this->data['email_id'] = "";
                $this->data['cin_no'] = "";
                $this->data['excise_registration_no'] = "";
                $this->data['tax_reg_no'] = "";
                $this->data['website_address'] = "";
            }
            $this->data['customer_po_number'] = '';
            $this->data['sales_order_no'] = '';
            $this->data['sales_order_date'] = '';
            $this->data['dispatch_no'] = '';
            $this->data['dispatch_date'] = '';
            if ($customer[0]->source == "SALES ORDER") {
                $so = $this->custpo($customer[0]->reference_source_id);
                $this->data['customer_po_number'] = $so['customer_po_number'];
                $this->data['sales_order_no'] = $so['sales_order_no'];
                $this->data['sales_order_date'] = $so['sales_order_date'];
                $disp = $this->dipfromino($customer[0]->invoice_hdr_id);
                $this->data['dispatch_no'] = $disp['number'];
                $this->data['dispatch_date'] = $disp['date'];
            }

            if ($customer[0]->source == "DISPATCH") {
                $this->data['customer_po_number'] = '';
                $disp = $this->dispno($customer[0]->reference_source_id);
                $this->data['dispatch_no'] = $disp['number'];
                $this->data['dispatch_date'] = $disp['date'];
            }

            if ($customer[0]->source == "STANDARD" || $customer[0]->source == "LABOUR") {
                $disp = $this->dipfromino($customer[0]->invoice_hdr_id);
                $this->data['dispatch_no'] = $disp['number'];
                $this->data['dispatch_date'] = $disp['date'];
            }

            $this->data['payment_term_name'] = $this->getPaymentterm($customer[0]->payment_term_id);
        } else {
            $this->data['payment_term_name'] = "";
            $this->data['trade_discount'] = "";
            $this->data['round_off'] = "";
            $this->data['despatch_through'] = '';
            $this->data['lr_no'] = '';
            $this->data['pack_weight'] = '';
            $this->data['packaging_qty'] = '';
            $this->data['invoice_no'] = '';
            $this->data['invoice_date'] = '';
            $this->data['invoice_type'] = '';
            $this->data['reference_number'] = '';
            $this->data['sales_order_no'] = '';
            $this->data['sales_order_date'] = '';
            $this->data['despatch_through'] = '';
            $this->data['remarks'] = '';
            $this->data['invoice_hdr_id'] = '';
            $this->data['deliveryterm'] = '';
            $this->data['gst_no'] = "";
            $this->data['company_name'] = "";
            $this->data['cmp_gst_no'] = "";
            $this->data['pan_no'] = "";
            $this->data['email_id'] = "";
            $this->data['cin_no'] = "";
            $this->data['excise_registration_no'] = "";
            $this->data['tax_reg_no'] = "";
            $this->data['website_address'] = "";
            $this->data['customer_po_number'] = "";
            $this->data['sales_order_no'] = "";
            $this->data['sales_order_date'] = "";
            $this->data['dispatch_no'] = "";
            $this->data['dispatch_date'] = "";
        }

        $lid = $customer[0]->invoice_hdr_id;
        /******************** current Date ********************/
        $this->data['date'] = date('d/m/Y');
        /****************** End *******************************/

        /************************* location based addrss ************************/

        $address = $this->getLocationwiseaddress($customer[0]->location_id);
        // dd($address);

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
            //GET COMPANY ADDRESS
            $this->data['company_gst_no'] = $address[0]->gst_no;
            $this->data['pan_no'] = $address[0]->pan_no;
            $city = $this->data['city'] = $this->getCity($address[0]->city_id);
            $state = $this->data['state'] = $this->getState($address[0]->state_id);

            $state_no = $this->data['state_no'] = $state = $this->data['state'][0]->state_code_no;
            $state_name = $this->data['state_name'] = $state = $this->data['state'][0]->state_name;
            $state_code = $this->data['state_code'] = $state = $this->data['state'][0]->state_code;
            $country = $this->data['country'] = $this->getCountry($address[0]->country_id);
            $this->data['gst_no'] = $address[0]->gst_no;
            $this->data['e_mail'] = $address[0]->e_mail;
            $this->data['state_code'] = $this->data['state'][0]->state_code;
        } else {
            $this->data['location_name'] = "";
            $this->data['street_name'] = "";
            $this->data['state_name'] = "";
            $this->data['area'] = "";
            $this->data['gst_no'] = '';
            $this->data['e_mail'] = "";
            $this->data['state_code'] = "";
            $this->data['address'] = "";
            $this->data['location'] = '';
            $this->data['state_no'] = "";
            $this->data['company_gst_no'] = "";
            $this->data['pan_no'] = "";
            $city = "";
            $country = "";
        }

        $this->data['company_address'] = $this->data['address'] . "," . $this->data['street_name'] . "" . $this->data['area'] . "," . $city . "," . $this->data['state_name'] . "," . $country;



        /************************* Customer ************************************/
        $cus_id = $customer[0]->ship_to_customer_id;
        $cust = $this->getCustomer($customer[0]->ship_to_customer_id);

        if (!empty($cust)) {
            $this->data['customer_name'] = $cust[0]->customer_name;
            $this->data['cus_gst_no'] = "";
        } else {
            $this->data['customer_name'] = '';
            $this->data['cus_gst_no'] = '';
        }

        /*************************** end ***************************************/

        /************************** bill  and ship to address  ****************/


        $bill_to_ship_address = $this->getBill_to_address($cus_id);

        if ($bill_to_ship_address != 0) {
            foreach ($bill_to_ship_address as $key => $value) {

                if ($value->site_type == "SHIP_TO") {
                    $this->data['address'] = $value->address;
                    $this->data['city'] = $this->getCity($value->city);
                    //$this->data['city_bill']=$this->data['city'][0]->city_name;
                    $dest = \DB::SELECT('select * from m_location_t where location_id=' . $bill_to_ship_address[0]->location_id . '');
                    $this->data['destination'] = $dest[0]->location_name;
                    $states = $this->getState($value->state);
                    if ($states != 0) {
                        $this->data['state_id_ship'] = $states[0]->state_code_no;
                        $this->data['state_name_ship'] = $states[0]->state_name;
                        $this->data['state_code_bill'] = $states[0]->state_code;
                    } else {
                        $this->data['state_id_ship'] = $states;
                        $this->data['state_name_ship'] = $states;
                        $this->data['state_code_bill'] = $states;
                    }
                    $this->data['country'] = $this->getCountry($value->country);
                    //$this->data['country_bill']=$this->data['country'][0]->country_name;
                    $this->data['pincode'] = $value->pincode;
                    $this->data['contact_number'] = $value->contact_number;
                    $this->data['contact_person'] = $value->contact_person;
                    $this->data['ship_gst_no'] = $value->gst_no;

                    $this->data['ship_to_address_1'] = $this->data['address'] . "," . $this->data['city'] . "," . $this->data['state_name_ship'] . "," . $this->data['country'] . "," . $this->data['pincode'];

                }



            }
        } else {
            $this->data['address'] = "";
            $this->data['city'] = "";
            //$this->data['city_bill']=$this->data['city'][0]->city_name;
            $this->data['state'] = "";
            $this->data['state_id_bill'] = "";
            $this->data['bill_name_ship'] = "";
            $this->data['state_code_bill'] = "";
            $this->data['country'] = "";
            //$this->data['country_bill']=$this->data['country'][0]->country_name;
            $this->data['pincode'] = "";
            $this->data['contact_number'] = "";
            $this->data['contact_person'] = "";
            $this->data['bill_gst_no'] = "";
            $this->data['ship_gst_no'] = "";
            $this->data['state_name_ship'] = "";
            $this->data['ship_to_address_1'] = "";
            $this->data['bill_to_address_1'] = "";
            $this->data['destination'] = '';
        }


        $this->data['company_logo'] = \Session::get('companylogo');
        /*************************** End *********************************/

        $so_table = \DB::table('s_invoice_lines_t')->where('invoice_hdr_id', $lid)->get();
        if (count($so_table) > 0) {

            $this->data['tax'] = $so_table[0]->tax_group_id;
            $tax = $so_table[0]->tax_group_id;
            $tax = \DB::table('m_tax_group_t')->where('tax_group_id', $so_table[0]->tax_group_id)->get();
            if (count($tax) > 0) {
                $this->data['tax_id'] = $tax[0]->tax_group_name;
            } else {
                $this->data['tax_id'] = "";
            }
        } else {
            $this->data['tax'] = '';
            $this->data['tax_id'] = "";
        }
        $sql = \DB::SELECT("select * from s_invoice_lines_t  WHERE invoice_line_id='" . $lid . "' group by tax_group_id");

        $linetable = DB::table('s_invoice_lines_t')->where('invoice_hdr_id', $id)->get();
        //$this->data['linetable']=$linetable;

        //************** Tax calculation ****************//

        $tax_cal = DB::table('m_tax_group_t')->leftjoin('m_tax_group_lines_t', 'm_tax_group_lines_t.tax_group_id', '=', 'm_tax_group_t.tax_group_id')->leftjoin('f_tax_code_t', 'f_tax_code_t.tax_code_id', '=', 'm_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*', 'm_tax_group_t.*', 'f_tax_code_t.*')->where('m_tax_group_t.tax_group_id', $linetable[0]->tax_group_id)->get();
        // dd($tax_cal);  
        if (count($tax_cal) > 0) {
            $this->data['tax_group'] = $tax_cal[0]->tax_group_name;
            $this->data['tax_group_name'] = $tax_cal[0]->display_name;
            $taxcode = "";
            foreach ($tax_cal as $k => $v) {
                $taxcode .= $v->tax_code_name . ",";
            }
            $taxcode1 = rtrim($taxcode, ",");
            $this->data['taxcode1'] = explode(",", $taxcode1);
        } else {
            $this->data['tax_group'] = "";
            $this->data['tax_group_name'] = "";
            $this->data['taxcode1'] = "";
        }
        $tax_split = $this->data['tax_group_name'];
        $split = preg_split('#(?<=\d)(?=[a-z])#i', $tax_split);
        $this->data['tax_value'] = $split;

        $total = 0;
        foreach ($linetable as $key => $value) {
            $discount = $value->discount_amount;
            $qty = $value->qty;
            $unit_price = $value->unit_price;
            $total += $qty * $unit_price;
            $taxable_amount = $total - $discount;
            //dd($taxable_amount);
            $this->data['taxable_amount'] = $taxable_amount;
            $product = \DB::table('m_products_t')->where('product_id', $linetable[0]->product_id)->get();
            $this->data['product'] = $product[0]->concatenated_product;
            $this->data['product_code'] = $product[0]->product_code;
            $uom_code = \DB::table('m_uom_codes_t')->where('uom_code_id', $linetable[0]->uom_code_id)->get();
            $this->data['uom_code'] = $uom_code[0]->uom_code;
            $this->data['tot_amt'] = $total;

        }

        /*----------------------- Sales Order Details -----------------------------*/

        $sales_order_details = \DB::table('s_salesorder_hdr_t')->where('sales_hdr_id', $cus_id)->get();

        if ($sales_order_details->isNotEmpty()) {
            $this->data['so_order'] = $sales_order_details[0]->sales_order_no;
            $this->data['so_date'] = $sales_order_details[0]->sales_order_date;
            $this->data['so_ref_no'] = $sales_order_details[0]->so_ref_no;
            $this->data['ref_no'] = $sales_order_details[0]->reference_number;
        } else {
            $this->data['so_order'] = '';
            $this->data['so_date'] = '';
            $this->data['so_ref_no'] = '';
            $this->data['ref_no'] = '';
        }

        /**************************** End ***************************************/

        $lines = \DB::table('s_invoice_lines_t')->where('invoice_hdr_id', $id)->get();
        // dd($lines);
        $this->data['subgrid'] = $lines;
        $subtotal = 0;
        $sub_total = 0;
        $tax_amount = 0;
        $X = 0;
        $Y = 0;
        $total = 0;
        $tot_desc = 0;
        $key = 0;
        $total1 = 0;
        $tot_tax_amt = 0;

        foreach ($this->data['subgrid'] as $ke => $value) {
            // dd($value);
            $dis_data = \DB::select("select s_dispatched_qty_t.* from s_dispatch_hdr_t left join s_dispatch_lines_t on s_dispatch_lines_t.so_dispatch_hdr_id=s_dispatch_hdr_t.so_dispatch_hdr_id  join s_dispatched_qty_t on s_dispatched_qty_t.so_dispatch_line_id=s_dispatch_lines_t.so_dispatch_line_id where s_dispatch_hdr_t.so_dispatch_hdr_id='" . $value->reference_hdr_id . "' and s_dispatched_qty_t.issue_qoh!=0 and s_dispatch_lines_t.product_id='" . $value->product_id . "'");

            $polines = array();
            foreach ($dis_data as $dis_val) {
                $date = "01/" . $dis_val->manufacture_date;
                $time = strtotime($dis_val->expiry_date);
                $expdate = date('M-Y', $time);
                $time = strtotime($date);
                $mfgdate = date('M-Y', $time);

                $polines[$key]['batch_mfg'] = "(Batch No." . $dis_val->batch_no . ",Mfg Dt." . $mfgdate . ",Exp Dt." . $expdate . ")";
                $tax = \DB::table("m_tax_group_t")->select('display_name', 'tax_group_name')->where('tax_group_id', $value->tax_group_id)->get();

                $polines[$key]['line_no'] = $value->line_no;
                $polines[$key]['qty'] = $dis_val->issue_qoh;
                if (count($tax) > 0) {
                    $polines[$key]['gst_tax'] = $tax[0]->display_name;
                    $polines[$key]['gst_per'] = $tax[0]->tax_group_name;
                    $polines[$key]['gst_per_gst'] = explode(" ", $tax[0]->tax_group_name);
                    $polines[$key]['gst_igst'] = $polines[$key]['gst_per_gst'][0];
                } else {
                    $polines[$key]['gst_tax'] = "";
                    $polines[$key]['gst_per'] = "";
                    $polines[$key]['gst_per_gst'] = "";
                    $polines[$key]['gst_igst'] = "";
                }

                if ($value->product_id != '0') {
                    $arr = $this->getProduct($value->product_id);
                    $polines[$key]['product'] = $arr['concat_segment'];
                    $polines[$key]['product_code'] = $arr['product_code'];
                    $polines[$key]['hsn_code'] = $arr['hsn_code'];
                    $polines[$key]['batch_mfg'] = $polines[$key]['batch_mfg'] . " HSN:" . $polines[$key]['hsn_code'];
                    $polines[$key]['uom_code'] = $arr['primary_uom_code'];
                } else {
                    $polines[$key]['product'] = '';
                    $polines[$key]['hsn_code'] = '0';
                    $polines[$key]['product_code'] = '';
                }
                if ($polines[$key]['gst_tax'] == "28") {
                    $X += $value->tax_amount;
                    $tot_tax_amt_x = $X / 2;
                } else {
                    $Y += $value->tax_amount;
                    $tot_tax_amt_y = $Y / 2;
                }

                $date = date('Y-m-d');
                $mrp = \DB::table('i_pricelist_lines_t')->where('pricelist_hdr_id', $customer[0]->pricelist_hdr_id)->where('product_id', $value->product_id)->where('active', '=', 'Yes')->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date)->select('*')->get();

                if (count($mrp) > 0) {
                    $polines[$key]['mrp_price'] = $mrp[0]->std_price;
                } else {
                    $polines[$key]['mrp_price'] = '';
                }

                $polines[$key]['unit_price'] = $value->unit_price;
                $polines[$key]['discount_amount'] = $value->discount_amount;
                $tot_desc += $polines[$key]['discount_amount'];
                $polines[$key]['discount_per'] = $value->discount_percentage;
                $polines[$key]['tax_amount'] = $value->tax_amount;
                $polines[$key]['tax_amount_1'] = $polines[$key]['tax_amount'] / 2;
                $polines[$key]['tax_amount_2'] = $polines[$key]['tax_amount'] / 2;
                $total += $value->unit_price * $dis_val->issue_qoh;
                $total1 = ($value->unit_price * $dis_val->issue_qoh) - (($dis_val->issue_qoh * $value->unit_price) * ($value->discount_percentage / 100));
                $polines[$key]['amount'] = $total1;

                $dis_amt = ($value->unit_price) - ($value->unit_price * ($value->discount_percentage / 100));

                $polines[$key]['taxable_amount'] = $polines[$key]['amount'] - $polines[$key]['discount_amount'];
                $amount = $dis_amt * $dis_val->issue_qoh;
                $subtotal = $amount + $subtotal;
                $sub_total = $sub_total + $amount;
                $tot_tax_amt = $X + $Y;
                $key++;
            }
        }


        if (!empty($tot_tax_amt_y)) {
            $this->data['tot_tax_amt_y'] = $tot_tax_amt_y;
        } else {
            $this->data['tot_tax_amt_y'] = '';
        }

        $this->data['gst_amt_x'] = $X;
        $this->data['gst_amt_y'] = $Y;
        if (!empty($tot_tax_amt_x)) {
            $this->data['tot_tax_amt_x'] = $tot_tax_amt_x;
        } else {
            $this->data['tot_tax_amt_x'] = '';
        }
        $this->data['tot_amt_aftr_tax'] = $total1 + $tot_tax_amt - $tot_desc;
        $this->data['total'] = $total1;
        $this->data['tot_desc'] = $tot_desc;
        $this->data['tot_tax_amt'] = $tot_tax_amt;
        //$this->data['total_amount_after_tax']=$total_amount_after_tax;
        $this->data['linedata'] = $polines;
        $this->data['sub_total'] = $subtotal;
        $line_total = $subtotal + $tot_tax_amt;
        $this->data['line_total'] = $line_total;

        $gstdata = \DB::select("select * from s_invoice_lines_t where invoice_hdr_id='" . $id . "' and tax_group_id!='0' group by tax_group_id");
        // dd($gstdata);
        $this->trd = $customer[0]->trade_discount_pre;

        $gstvalue = array();
        $sgst = 0;
        $cgst = 0;
        $gsttotal = 0;
        $grand_total = 0;

        foreach ($gstdata as $gst_key => $gst_value) {

            $arr_sgcgst = array('sgst', 'cgst');
            $arr = $this->getProduct($gst_value->product_id);

            $tax = \DB::table("m_tax_group_t")->select('display_name', 'tax_group_name')->where('tax_group_id', $gst_value->tax_group_id)->get();

            if ($tax->isNotEmpty()) {
                $display_name = $tax[0]->display_name;
                $gstvalue[$gst_key]['tax_group_name'] = $tax[0]->tax_group_name;/* important */
            } else {
                $gstvalue[$gst_key]['tax_group_name'] = '';
            }

            $gst = $this->getGst($gst_value->tax_group_id, $id);

            $gstvalue[$gst_key]['gst'] = $display_name;/* important */


            $gstvalue[$gst_key]['sgcgst'] = $display_name / 2;

            $gstvalue[$gst_key]['hsn'] = $arr['hsn_code'];

            $gstvalue[$gst_key]['amount'] = $gst['amount'];
            $gstvalue[$gst_key]['gst_id'] = $gst_value->tax_group_id;
            $gstvalue[$gst_key]['gst_val'] = $gst['amount'] * ($display_name / 100); /* important */

            $gstvalue[$gst_key]['sgst_val'] = $gst['amount'] * ($display_name / 100) / 2;

            $gstvalue[$gst_key]['cgst_val'] = $gst['amount'] * ($display_name / 100) / 2;

            $this->data['sgcgstt'] = $arr_sgcgst;

            $gsttotal = $gstvalue[$gst_key]['gst_val'] + $gsttotal;

        }
        $ka = $gsttotal / 2;

        $grand_total = $gsttotal + $sub_total;
        // dd($gsttotal);
        //Maruthu Purpose to GST calculation End
        $this->data['gst'] = $gstvalue;
        $this->data['gsttotal'] = $gsttotal;
        $this->data['value'] = $polines;
        $this->data['sub_total'] = $subtotal;
        $this->data['subtotal'] = $sub_total;
        $this->data['id'] = $id;

        /*------------------------end -------------------------------------->*/


        $sales_ref = \DB::select("select *,s_salesorder_hdr_t.sales_order_date,s_salesorder_hdr_t.customer_po from s_dispatch_hdr_t join s_salesorder_hdr_t on s_salesorder_hdr_t.sales_hdr_id=s_dispatch_hdr_t.reference_source_id where s_dispatch_hdr_t.dispatch_number='" . $this->data['dispatch_no'] . "'");
        if (count($sales_ref) > 0) {

            $this->data['so_ref'] = $sales_ref[0]->reference_no;
            $this->data['sales_order_date'] = $sales_ref[0]->sales_order_date;
            $this->data['customer_po_number'] = $sales_ref[0]->customer_po;
        } else {
            $this->data['so_ref'] = '';
            $this->data['sales_order_date'] = '';
            $this->data['customer_po_number'] = '';
        }

        $this->data['print'] = "PRINT";
        $this->data['print_val'] = '1';
        $terms_condition = \DB::table('a_rpt_displayelements_hdr_t')->leftjoin('a_rpt_displayelements_lines_t', 'a_rpt_displayelements_lines_t.rpt_displayelements_hdr_id', '=', 'a_rpt_displayelements_hdr_t.rpt_displayelements_hdr_id')->select('a_rpt_displayelements_lines_t.element_content')->where('a_rpt_displayelements_hdr_t.report_source', 121)->where('a_rpt_displayelements_lines_t.element_name', 'TERMS&CONDITIONS')->get();
        if (count($terms_condition) > 0) {
            $this->data['terms_condition'] = $terms_condition;
        } else {
            $this->data['terms_condition'] = [];
        }


        if (isset($_GET['mail'])) {
            if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                Config::set('mail.username', \Session::get('user_email'));
                Config::set('mail.password', \Session::get('user_password'));
            }

            $this->data['print'] = "PRINTS";



            \Mail::send('Salesreplacement.printform', $this->data, function ($message) {
                if (!empty($_GET['cc'])) {

                    $cc = explode(',', $_GET['cc']);
                    $message->cc($cc);
                } else {
                    $cc = array();
                }

                $msg = $_GET['msg'];


                $message->to(explode(",", $_GET['mail']));
                $message->subject("Invoice" . $this->data['invoice_no']);

                $message->setBody($msg);
                //$message->from('Saipavan9010@gmail.com');
                if (!empty(\Session::get('user_email'))) {
                    $message->from(\Session::get('user_email'));
                } else {
                    $message->from(\Config::get('mail.username'));
                }
                $return = DB::table('s_invoice_hdr_t')->where('invoice_hdr_id', $this->data['invoice_hdr_id'])->get();
                $message->attach('uploads/soinvoiceupload/SOINV_' . stripslashes($this->data['invoice_no']) . '.pdf');
                if ($return[0]->attachfile_name != '') {
                    $file_a = json_decode($return[0]->attachfile_name);

                    foreach ($file_a as $k1 => $v1) {

                        $message->attach('uploads/soinvoiceupload/SOINV' . $this->data['invoice_hdr_id'] . '/' . $v1);
                    }
                }


            });
            return 1;


        }

        $this->data['print'] = "PRINT";
        $this->data['print_val'] = '1';

        if (isset($_GET['mails'])) {

            $this->data['print'] = "PRINTS";
            // dd($this->data);
            return view('Salesreplacement.printform', $this->data);
        }

        return view('Salesreplacement.printform', $this->data);
    }

    public function Salesreplacementuploaddata($id)
    {
        $data = \DB::select("select attachfile_name from s_invoice_hdr_t where invoice_hdr_id='$id'");

        if (!empty($data)) {
            $data = json_decode($data[0]->attachfile_name);
            return $data;
        }

    }
    public function Salesreplacementuploads(Request $request)
    {

        $sales_id = $_POST['invoice_id'];
        if ($request->hasfile('choosefile')) {
            foreach ($request->file('choosefile') as $file) {
                $name = $file->getClientOriginalName();

                $file->move(public_path() . '/uploads/soinvoiceupload/IN_' . $sales_id . '/', $name);
                $data[] = $name;
            }
        }

        $datas = \DB::select("select attachfile_name from s_invoice_hdr_t where invoice_hdr_id='$sales_id'");


        if ($datas[0]->attachfile_name != "") {
            $datas = json_decode($datas[0]->attachfile_name);
            $result = array_diff($datas, $_POST['file']);
            foreach ($result as $k => $v) {
                @unlink(public_path() . '/uploads/soinvoiceupload/IN_' . $sales_id . '/', $v);
            }
            $data = array_merge($_POST['file'], $data);

        }
        $attachfile_name = json_encode($data);
        \DB::update("update s_invoice_hdr_t set attachfile_name='" . $attachfile_name . "' where invoice_hdr_id='$sales_id'");
        $this->data['notymsg'] = "yes";
        return redirect('Salesreplacement');
    }

    //kaviya purpose to get soorder qty from order,dispacth
    public function invoicesoqty($pid = null, $sid = null)
    {
        $issue_qty = [];

        $sql = \DB::select('SELECT s_salesorder_hdr_t.sales_hdr_id,s_salesorder_lines_t.sales_line_id,s_salesorder_lines_t.qty,s_salesorder_lines_t.product_id, m_products_t.concatenated_product,s_salesorder_lines_t.dispatched_qty,s_salesorder_lines_t.invoiced_qty,s_salesorder_hdr_t.sales_order_no from s_salesorder_hdr_t left join s_salesorder_lines_t on(s_salesorder_lines_t.sales_hdr_id = s_salesorder_hdr_t.sales_hdr_id) left join m_products_t on m_products_t.product_id= s_salesorder_lines_t.product_id  where s_salesorder_hdr_t.sales_hdr_id in(' . $sid . ') and s_salesorder_lines_t.product_id=' . $pid);

        $html1 = '';

        $html1 .= '<div id="preview-area" class="chandru"><table class="overflow-y preview batch_table"><thead><tr style="background-color:#05234e;color:#fff;"><th>Line No</th><th>Sales Order No</th><th>Order Qty</th><th>Dispatched Qty</th><th>Invoiced Qty</th><th>Invoice Qty</th></tr></thead><tbody>';
        $check_qty = $_GET['so_qty'];
        $dispatch = $_GET['dispatch'];

        if ($check_qty == '0') {
            if ($dispatch != 0) {

                //salesorder from dispacth
                $dispact_data = \DB::select('SELECT * from s_dispatch_lines_t where so_dispatch_hdr_id=' . $dispatch . ' and product_id=' . $pid);

                $data = explode(',', $dispact_data[0]->soorder_id);
                $data1 = explode(',', $dispact_data[0]->sowise_qty);
                foreach ($data as $k => $v) {

                    $sales_data = \DB::SELECT("select sales_order_no from s_salesorder_hdr_t where  sales_hdr_id='$v'");
                    $sales_data_invoice = \DB::SELECT("select invoiced_qty,qty from s_salesorder_lines_t where  sales_hdr_id='$v' and product_id=" . $pid);

                    $html1 .= '<tr><td></td>
                <td>
                    <input type="text"  class="form-control input-sm line_no" value="' . ($k + 1) . '" readonly="readonly" style="width:68px !important;"> 
                </td>
                <td>
                    <input type="text"  class="form-control input-sm sales_order_no input_qty_width" readonly value="' . $sales_data[0]->sales_order_no . '" style="width:100px !important;">
                    <input type="hidden"  class="form-control input-sm so_id input_qty_width so_id' . $k . '" readonly value="' . $v . '" style="width:100px !important;">
                </td>
                <td>
                    <input type="text"  class="form-control qty qty' . $k . ' input_qty_width"  readonly value="' . $sales_data_invoice[0]->qty . '" style="width:100px !important;">
                </td>
                <td>
                    <input type="text"  class="form-control dispatchedqty dispatchedqty' . $k . ' input_qty_width"  readonly value=' . $dispact_data[0]->dispatched_qty . ' style="width:100px !important;">
                </td>
                <td>
                    <input type="text"  class="form-control invoicedqty invoicedqty' . $k . ' input_qty_width"  readonly value=' . $sales_data_invoice[0]->invoiced_qty . ' style="width:100px !important;">
                </td>
                <td>
                    <input type="text"  class="form-control invoice_issue_qty invoice_issue_qty' . $k . ' input_qty_width"  data-index="' . $k . '" value="' . $sales_data_invoice[0]->qty . '" style="width:100px !important;">
                </td></tr>';
                }
            } else {
                foreach ($sql as $k => $v) {
                    if ($v->qty == 0) {
                        $qty = '';
                    } else {
                        $qty = $v->qty - $v->invoiced_qty;
                    }

                    //soorder for create from so
                    $html1 .= '<tr><td></td>
                <td>
                    <input type="text"  class="form-control input-sm line_no" value="' . ($k + 1) . '" readonly="readonly" style="width:68px !important;"> 
                </td>
                <td>
                    <input type="text"  class="form-control input-sm sales_order_no input_qty_width" readonly value="' . $v->sales_order_no . '" style="width:100px !important;">
                    <input type="hidden"  class="form-control input-sm so_id input_qty_width so_id' . $k . '" readonly value="' . $v->sales_hdr_id . '" style="width:100px !important;">
                </td>
                <td>
                    <input type="text"  class="form-control qty qty' . $k . ' input_qty_width"  readonly value="' . $v->qty . '" style="width:100px !important;">
                </td>
                <td>
                    <input type="text"  class="form-control dispatchedqty dispatchedqty' . $k . ' input_qty_width"  readonly value=' . $v->dispatched_qty . ' style="width:100px !important;">
                </td>
                <td>
                    <input type="text"  class="form-control invoicedqty invoicedqty' . $k . ' input_qty_width"  readonly value=' . $v->invoiced_qty . ' style="width:100px !important;">
                </td>
                <td>
                    <input type="text"  class="form-control invoice_issue_qty invoice_issue_qty' . $k . ' input_qty_width"  data-index="' . $k . '" value="' . $qty . '" style="width:100px !important;">
                </td></tr>';
                }
            }
        } else {
            //soorder for edit
            $so_qty = explode(',', $_GET['so_qty']);
            $so_id = explode(',', $_GET['so_id']);

            $invoice_qty = explode(',', $_GET['invoice_qty']);
            $invoiced_qty = explode(',', $_GET['invoiced_qty']);
            foreach ($so_id as $k => $value) {
                $sales_data = \DB::SELECT("select sales_order_no from s_salesorder_hdr_t where  sales_hdr_id='$value'");

                if ($invoice_qty[$k] != 0) {
                    $qty = $invoice_qty[$k];
                } else {
                    $qty = '';
                }
                $html1 .= '<tr><td></td>
                <td>
                    <input type="text"  class="form-control input-sm line_no" value="' . ($k + 1) . '" readonly="readonly" style="width:68px !important;"> 
                </td>
                <td>
                    <input type="text"  class="form-control input-sm sales_order_no input_qty_width" readonly value="' . $sales_data[0]->sales_order_no . '" style="width:100px !important;">
                    <input type="hidden"  class="form-control input-sm so_id input_qty_width so_id' . $k . '" readonly value="' . $value . '" style="width:100px !important;">
                </td>
                <td>
                    <input type="text"  class="form-control qty qty' . $k . ' input_qty_width"  readonly value="' . $so_qty[$k] . '" style="width:100px !important;">
                </td>
                <td>
                    <input type="text"  class="form-control dispatchedqty dispatchedqty' . $k . ' input_qty_width"  readonly value=' . $_GET['so_qty'] . ' style="width:100px !important;">
                </td>
                <td>
                    <input type="text"  class="form-control invoicedqty invoicedqty' . $k . ' input_qty_width"  readonly value=' . $invoiced_qty[$k] . ' style="width:100px !important;">
                </td>
                <td>
                    <input type="text"  class="form-control invoice_issue_qty invoice_issue_qty' . $k . ' input_qty_width"  data-index="' . $k . '" value="' . $qty . '" style="width:100px !important;">
                </td></tr>';
            }
        }
        $html1 .= '</tbody></table>
            <button class="qtyok btn btn-large vie " >Add Invoice Qty </button>
            </div>';
        return $html1;
    }

    public function getorderupdate(Request $request, $id = null, $labid = null)
    {

        $custaddress1 = new SoorderController;
        $this->data['tax_group_id_pop'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
        $id1 = explode(',', $id);
        $table = \DB::table('s_salesorder_hdr_t')->whereIn('sales_hdr_id', $id1)->get();
        $orde_no = '';
        foreach ($table as $ke0 => $va0) {
            $orde_no .= $va0->sales_order_no . ',';
        }
        $decimal = \Session::get('decimal');
        $orde_no = substr($orde_no, 0, -1);
        $table1 = \DB::table('s_salesorder_hdr_t')->whereIn('sales_hdr_id', $id1)->sum('order_tax');
        $table2 = \DB::table('s_salesorder_hdr_t')->whereIn('sales_hdr_id', $id1)->sum('order_total');
        //dd($table2);
        $this->data['row'] = (object) array();
        $this->data['row']->invoice_hdr_id = '';
        $this->data['row']->invoice_number = '';
        $this->data['row']->invoice_status = 'INITIATED';
        $this->data['row']->source = "SALES ORDER";
        $this->data['row']->reference_number = $orde_no;
        $this->data['row']->invoice_type = $table[0]->order_type_id;
        $this->data['row']->invoice_date = $table[0]->sales_order_date;
        $this->data['row']->reference_source_id = $id;
        $this->data['row']->ar_sales_hdr_id = $id;
        $this->data['project_id'] = $table[0]->project_id;
        $this->data['salesperson_id'] = $table[0]->salesperson_id;
        $this->data['row']->invoice_tax_total = $table1;
        $this->data['row']->invoice_grand_total = $table2;
        $this->data['ship_to_customer_id'] = $table[0]->ship_to_customer_id;
        $this->data['row']->lr_no = "";
        $this->data['savestatus'] = "";
        $this->data['pagemode'] = 'create';
        $sesdate = \Session::get('p_date_format');
        $originalDate = date('d-m-Y');
        $this->data['lr_date'] = date($sesdate, strtotime($originalDate));
        $this->data['approved_date'] = date($sesdate, strtotime($originalDate));
        $this->data['row']->due_date = date($sesdate, strtotime($table[0]->delivery_date));
        $this->data['row']->pack_weight = "";
        $this->data['row']->trade_discount = '';
        $this->data['row']->round_off = '';
        $this->data['row']->trade_discount_pre = '';
        $this->data['schemes'] = $this->jCombo('s_schemes_hdr_t', 'schemes_hdr_id', 'schemes_name', '');
        $this->data['row']->packaging_qty = "";
        $this->data['bill_to_address'] = $custaddress1->sobilladdress($table[0]->bill_to_address_id);
        $this->data['ship_to_address'] = $custaddress1->soshipaddress($table[0]->ship_to_address_id);

        $this->data['bill_to_address_id'] = $table[0]->bill_to_address_id;
        $this->data['ship_to_address_id'] = $table[0]->ship_to_address_id;
        $this->data['row']->contact_person = $table[0]->contact_person;
        $this->data['row']->contact_number = $table[0]->contact_number;
        $this->data['organization_id'] = $table[0]->organization_id;
        $this->data['row']->remarks = $table[0]->remarks;
        $this->data['row']->payment_term_id = '';
        $this->data['row']->payment_method_id = '';
        $this->data['row']->employee_id = $this->jCombo('hr_employee_t', 'employee_id', 'first_name|last_name', $table[0]->employee_id);
        $this->data['row']->invoice_pricelist_id = $table[0]->pricelist_id;
        $this->data['row']->packaging_charges = str_replace(",", "", (number_format($table[0]->packaging_charges, $decimal)));
        $this->data['row']->packaging_charges_tax = $table[0]->packaging_charges_tax;
        $this->data['row']->insurance_charges_tax = $table[0]->insurance_charges_tax;
        $this->data['row']->insurance_charges = str_replace(",", "", (number_format($table[0]->insurance_charges, $decimal)));
        $this->data['row']->other_tax_amount = str_replace(",", "", (number_format($table[0]->other_tax_amount, $decimal)));
        $this->data['row']->other_tax_amount_tax = $table[0]->other_tax_amount_tax;
        $this->data['row']->other_frieght_amount = str_replace(",", "", (number_format($table[0]->other_frieght_amount, $decimal)));
        $this->data['row']->other_frieght_amount_tax = $table[0]->other_frieght_amount_tax;
        $this->data['row']->transport_charges = str_replace(",", "", (number_format($table[0]->transport_charges, $decimal)));
        $this->data['row']->transport_charges_tax = $table[0]->transport_charges_tax;

        $this->data['row']->created_by = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
        $this->data['discount'] = $this->jCombodiscount('m_discounts_hdr_t', 'ar_discount_hdr_id', 'discount_name', $table[0]->discount_id);
        $tablelines = \DB::table('s_salesorder_lines_t')->whereIn('sales_hdr_id', $id1)->groupBy('product_id')->orderby('sales_line_id', 'asc')->get();
        $this->data['subgrid']['label_data'] = $table;
        $tablefield = $this->submodel->getTableColumns();


        foreach ($tablefield as $key => $val) {
            $data[$val] = $val;
        }

        foreach ($tablelines as $key => $value) {
            $tablelines_data = \DB::table('s_salesorder_lines_t')->whereIn('sales_hdr_id', $id1)->where('product_id', $value->product_id)->orderby('sales_line_id', 'asc')->get();
            //dd($tablelines_data);
            $sum_qty = 0;
            $invoice_qty = 0;
            $hrd_id = '';
            $line_id = '';
            foreach ($tablelines_data as $k0 => $v0) {
                $sum_qty = $v0->qty + $sum_qty;
                $invoice_qty = $v0->invoiced_qty + $invoice_qty;
                $hrd_id .= $v0->sales_hdr_id . ",";
                $line_id .= $v0->sales_line_id . ",";
            }
            $order_qty = $sum_qty;
            $sum_qty = $sum_qty - $invoice_qty;
            $ty = ($sum_qty * $value->unit_price);
            $dis_amount = (($ty * $value->discount_percentage) / 100);
            $tot = $ty - $dis_amount;
            $tax_group_data = \DB::table('m_tax_group_t')->where('tax_group_id', $value->tax_group_id)->get();
            if (count($tax_group_data) > 0) {
                $disp = $tax_group_data[0]->display_name;
                $tax_amount = (($tot * $disp) / 100);
            } else {
                $tax_amount = 0;
            }
            $total = $tot + $tax_amount;
            $hrd_id = substr($hrd_id, 0, -1);
            $line_id = substr($line_id, 0, -1);
            if ($sum_qty != 0) {
                $this->data['subgrid']['rowData'][$key] = (object) array();
                $this->data['subgrid']['rowData'][$key]->invoice_hdr_id = '';
                $this->data['subgrid']['rowData'][$key]->invoice_line_id = '';
                $this->data['subgrid']['rowData'][$key]->reference_hdr_id = $hrd_id;
                $this->data['subgrid']['rowData'][$key]->reference_line_id = $line_id;
                $this->data['subgrid']['rowData'][$key]->ar_sales_hdr_id = $hrd_id;
                $this->data['subgrid']['rowData'][$key]->tax_excemption = $value->tax_excemption;
                $this->data['subgrid']['rowData'][$key]->hsn_code = $this->jCombo('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->hsn_code);
                $this->data['subgrid']['rowData'][$key]->description = $value->product_description;

                $this->data['subgrid']['rowData'][$key]->part_no = $this->jcustomselecttool('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $table[0]->ship_to_customer_id);
                $this->data['subgrid']['rowData'][$key]->line_no = $key;

                $pid = $value->product_id;

                $compy = \Session::get('companyid');
                $stock_qty = DB::SELECT("select sum(f.qty-f.qtyy) as qoh,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id=" . $value->product_id . " and  i_qoh_detail_t. company_id=" . $compy . "  and i_qoh_detail_t.qualitystatus=1 GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id=" . $value->product_id . " and  i_reservation_detail_t. company_id=" . $compy . "  GROUP by product_id)f");
                $res = DB::SELECT("select sum(reserv_trx_qty) as resqty from i_reservation_detail_t where product_id=" . $value->product_id . " and  i_reservation_detail_t.reference_no in ('" . $orde_no . "') and i_reservation_detail_t. company_id=" . $compy . "  GROUP by product_id");
                if (count($res) > 0) {
                    $reserveqty = $res[0]->resqty;
                } else {
                    $reserveqty = 0;
                }
                if (!empty($stock_qty)) {
                    if (is_null($stock_qty[0]->qoh)) {
                        $this->data['subgrid']['rowData'][$key]->qoh = 0;
                    } else {
                        $this->data['subgrid']['rowData'][$key]->qoh = number_format($stock_qty[0]->qoh + $reserveqty, $decimal, ".", "");
                    }
                    if ($stock_qty[0]->qoh < 0) {
                        $this->data['subgrid']['rowData'][$key]->qoh = 0;
                    }
                } else {
                    $this->data['subgrid']['rowData'][$key]->qoh = 0;
                }

                $this->data['subgrid']['rowData'][$key]->product_id = $value->product_id;
                $this->data['subgrid']['rowData'][$key]->uom_code_id = $value->uom_code_id;
                $this->data['subgrid']['rowData'][$key]->qty = str_replace(",", "", (number_format($sum_qty, $decimal)));
                $this->data['subgrid']['rowData'][$key]->salesorder_qty = str_replace(",", "", (number_format($order_qty, $decimal)));
                $this->data['subgrid']['rowData'][$key]->invoiced_qty = str_replace(",", "", (number_format($invoice_qty, $decimal)));
                $this->data['subgrid']['rowData'][$key]->unit_price = $value->unit_price;
                $this->data['subgrid']['rowData'][$key]->tax_group_id = $value->tax_group_id;
                $this->data['subgrid']['rowData'][$key]->tax_amount = str_replace(",", "", (number_format($tax_amount, $decimal)));
                $this->data['subgrid']['rowData'][$key]->line_total = str_replace(",", "", (number_format($total, $decimal)));
                $this->data['subgrid']['rowData'][$key]->discount_percentage = $value->discount_percentage;
                $this->data['subgrid']['rowData'][$key]->discount_amount = str_replace(",", "", (number_format($dis_amount, $decimal)));
                $this->data['subgrid']['rowData'][$key]->comments = '';
                $this->data['subgrid']['rowData'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);

                $this->data['subgrid']['rowData'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                $this->data['subgrid']['rowData'][$key]->taxgroup_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
            }

        }
        $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $table[0]->ar_payment_term_id);
        $this->data['payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $table[0]->ar_payment_method_id);
        $this->data['delivery_term_id'] = $this->jCombo('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $table[0]->ar_delivery_terms_id);

        $this->data['ar_frieghtcarriers_hdr_id'] = $this->jCustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $table[0]->freight_carrier_id, 'and source_type_id="Sales"');
        $this->data['salesperson_id'] = $this->jCombo('s_salesperson_t', 'salesperson_id', 'salesperson_name', $table[0]->salesperson_id);
        $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);
        $this->data['ship_to_customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_number|customer_name', $table[0]->ship_to_customer_id);

        $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', $table[0]->project_id);
        $this->data['pricelist'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $table[0]->pricelist_id, ' and price_list_type="Sales"');
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);
        $this->data['taxgroup_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
        $this->data['invoice_currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', '');
        $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'Salesreplacement');
        $this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
        $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
        $this->data['linedata'] = $this->data['subgrid']['rowData'];

        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'Salesreplacement')->get();
        if (isset($_GET['status'])) {
            $this->data['return_url'] = "invoicefromorder";

        } else {
            $this->data['return_url'] = \Request::route()->getName();
        }
        // dd($this->data);
        return view('Salesreplacement.form', $this->data);
    }

    public function getpickorderupdate($id = null)
    {

        $table = \DB::table('s_pickrelease_hdr_t')->where('so_pickrelease_hdr_id', $id)->get();
        // dd($table);
        $this->data['row'] = (object) array();
        $this->data['row']->invoice_hdr_id = '';
        $this->data['row']->invoice_number = '';
        $this->data['row']->invoice_status = 'INITIATED';
        $this->data['row']->source = "PICK ORDER";
        $this->data['row']->invoice_type = '';
        $this->data['row']->reference_number = '';
        $this->data['row']->invoice_date = $table[0]->release_date;
        $this->data['row']->reference_source_id = $table[0]->so_pickrelease_hdr_id;
        $this->data['project_id'] = '';
        $this->data['salesperson_id'] = '';
        $this->data['row']->lr_no = "";
        $this->data['row']->pack_weight = "";
        $this->data['row']->packaging_qty = "";
        $this->data['row']->due_date = "";
        $this->data['row']->trade_discount = '';
        $this->data['row']->round_off = '';
        $this->data['schemes'] = $this->jCombo('s_schemes_hdr_t', 'schemes_hdr_id', 'schemes_name', '');
        $this->data['savestatus'] = $table[0]->savestatus;
        $this->data['pagemode'] = 'create';
        $this->data['row']->invoice_tax_total = '';
        $this->data['row']->invoice_grand_total = '';
        $this->data['row']->ship_to_customer_id = $table[0]->ship_to_customer_id;
        $custaddress1 = new SoorderController;
        $custaddress2 = new SoquoteController;

        $this->data['ship_to_address'] = $custaddress1->soshipaddress($table[0]->deliver_to_location);
        $address = $custaddress2->getaddress($table[0]->ship_to_customer_id);

        $this->data['bill_to_address'] = $address[0];

        $this->data['row']->contact_person = '';
        $this->data['row']->contact_number = '';
        $this->data['row']->invoice_pricelist_id = $table[0]->pricelist_id;
        $org = \Session::get('organization');
        $this->data['row']->organization_id = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $org);
        $this->data['row']->remarks = $table[0]->remarks;
        $this->data['row']->payment_term_id = '';
        $this->data['row']->payment_method_id = '';
        //$ids=\Session::get('id');
        $this->data['row']->created_by = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));

        $tablelines = \DB::select("SELECT `so_pickrelease_line_id`, `so_pickrelease_hdr_id`, `line_no`, `ar_sales_hdr_id`, `ar_sales_line_id`, `product_id`, `uom_code_id`, SUM(so_qty) AS so_qty, SUM(release_qty) AS release_qty, `picked_qty`, `comments`,`location_id`,`organization_id` FROM s_pickrelease_lines_t WHERE so_pickrelease_hdr_id IN('$id') GROUP BY product_id");

        $this->data['subgrid']['label_data'] = $table;

        //$this->data['subgrid']['label_data']= (object)array();
        $tablefield = $this->submodel->getTableColumns();


        foreach ($tablefield as $key => $val) {
            $data[$val] = $val;
        }
        //  $this->data['subgrid']=$this->submodel->subgridRead($id);

        $grandtotal = 0;
        $taxamount_total = 0;



        foreach ($tablelines as $key => $value) {
            // dd($value);
            $result = \DB::table('s_salesorder_lines_t')->select('qty as qty', 'discount_percentage', 'discount_amount', 'hsn_code')->where('sales_line_id', $value->ar_sales_line_id)->get();
            if ($result->isNotEmpty()) {
                $qty = $result[0]->qty;
            } else {
                $qty = 0;
            }
            // dd($result);
            $this->data['subgrid']['rowData'][$key] = (object) array();
            $this->data['subgrid']['rowData'][$key]->invoice_hdr_id = '';
            $this->data['subgrid']['rowData'][$key]->invoice_line_id = '';
            $this->data['subgrid']['rowData'][$key]->reference_hdr_id = $value->so_pickrelease_hdr_id;
            $this->data['subgrid']['rowData'][$key]->reference_line_id = $value->so_pickrelease_line_id;
            $this->data['subgrid']['rowData'][$key]->line_no = $key;
            $pid = $value->product_id;
            $stock_qty = \DB::select("select sum(f.qty-f.qtyy) as qoh,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id=" . $value->product_id . " and i_qoh_detail_t.qualitystatus=1 GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id=" . $value->product_id . " GROUP by product_id)f ");

            if (!empty($stock_qty)) {
                if (is_null($stock_qty[0]->qoh)) {
                    $this->data['subgrid']['rowData'][$key]->qoh = 0;
                } else {
                    $this->data['subgrid']['rowData'][$key]->qoh = $stock_qty[0]->qoh;
                }
                if ($stock_qty[0]->qoh < 0) {
                    $this->data['subgrid']['rowData'][$key]->qoh = 0;
                }
            } else {
                $this->data['subgrid']['rowData'][$key]->qoh = 0;
            }

            $this->data['subgrid']['rowData'][$key]->product_id = $value->product_id;
            $this->data['subgrid']['rowData'][$key]->uom_code_id = $value->uom_code_id;
            $this->data['subgrid']['rowData'][$key]->salesorder_qty = $value->so_qty;
            $this->data['subgrid']['rowData'][$key]->qty = $value->release_qty;
            $tax = $this->gettax($value->product_id);
            if ($this->data['row']->invoice_type != 'LABOUR')

                $unitprice = $this->pricelistorder($value->ar_sales_line_id, $value->product_id);
            // dd($value);
            $taxamount = (($value->release_qty * $unitprice) * $tax['display_name']) / 100;
            $linetotal = ($taxamount + ($value->release_qty * $unitprice));
            $taxamount_total += $taxamount;
            $grandtotal = $grandtotal + $linetotal;

            $this->data['subgrid']['rowData'][$key]->unit_price = $unitprice;

            $this->data['subgrid']['rowData'][$key]->tax_group_id = $tax['taxgroup_id'];
            $this->data['subgrid']['rowData'][$key]->tax_amount = $taxamount;
            $this->data['subgrid']['rowData'][$key]->line_total = $linetotal;
            $this->data['subgrid']['rowData'][$key]->discount_percentage = $result[0]->discount_percentage;
            $this->data['subgrid']['rowData'][$key]->discount_amount = $result[0]->discount_amount;
            $this->data['subgrid']['rowData'][$key]->comments = '';

            $this->data['subgrid']['rowData'][$key]->productid = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'Salesreplacement');

            $this->data['subgrid']['rowData'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
            $this->data['subgrid']['rowData'][$key]->taxgroup_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $this->taxgroup($value->product_id));

        }


        $this->data['row']->invoice_grand_total = $grandtotal;
        $this->data['row']->invoice_tax_total = $taxamount_total;

        $this->data['ship_to_customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_number|customer_name', $table[0]->ship_to_customer_id);
        $customerdata = $this->getcustomerdata($table[0]->ship_to_customer_id);
        // dd($customerdata);
        if ($customerdata != '') {
            $this->data['ar_frieghtcarriers_hdr_id'] = $this->jCombo('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $customerdata[0]->ar_frieghtcarriers_hdr_id);
            $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $customerdata[0]->default_payment_terms_id);
            $this->data['payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $customerdata[0]->default_payment_method_id);
            $this->data['salesperson_id'] = $this->jCombo('s_salesperson_t', 'salesperson_id', 'salesperson_name', $customerdata[0]->sales_person);
        } else {
            $this->data['ar_frieghtcarriers_hdr_id'] = '';
            $this->data['payment_term_id'] = '';
            $this->data['payment_method_id'] = '';
            $this->data['salesperson_id'] = '';
        }




        $this->data['delivery_term_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', '', ' and source_type_id="Sales"');
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);


        $this->data['pricelist'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $table[0]->pricelist_id, ' and price_list_type="Sales"');
        $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);

        $this->data['ship_to_address_id'] = $this->jCombo('m_customer_sites_t', 'customer_site_id', 'customer_site_name', '');
        $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', '');

        $this->data['taxgroup_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');

        $this->data['invoice_currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', '');
        // $this->data['product_id'] = $this->jcustomproductselect('m_products_t','product_id','concatenated_product',$value->product_id,'soquote');   
        $this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);


        $this->data['linedata'] = $this->data['subgrid']['rowData'];


        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'Salesreplacement')->get();
        $this->data['return_url'] = \Request::route()->getName();
        $this->data['return_url'] = 'invoicefrompickorder';
        $this->data['route'] = 'invoicefrompickorder';

        return view('Salesreplacement.form', $this->data);
    }

    public function getcustomerdata($id = null)
    {
        if ($id != '') {
            $cusdata = \DB::select("select * from m_customers_t where customer_id=$id");
            return $cusdata;
        }

    }


    function ewaybillgenerate($invid = null, $customerid = null)
    {
        $invoicehdr = array();
        $invoicelines = array();

        $invoicehdr = \DB::select('select company.gst_no as userGstin,null as supplyType,null as subSupplyType,null as docType,invoicehdr.invoice_number as docNo,invoicehdr.invoice_date as docDate,company.gst_no as fromGstin,company.company_name as fromTrdName,loc.address as fromAddr1,loc.street_name as fromAddr2,city.city_name as fromPlace,loc.pincode as fromPincode,locstate.state_code as fromStateCode,locstate.state_code as actualFromStateCode,customer.pan_no as toPanin,customer.customer_name as toTrdName,customersite.customer_site_name as toAddr1,customersite.address as toAddr2,customersite.country,customersite.state,state.state_name as toPlace,customersite.pincode as toPincode,state.state_code as toStateCode,state.state_code as actualToStateCode,null as sgstRate,null as cgstRate,null as igstRate,null as cessRate,carrier.shipping_method as transMode,carrier.minimum_distance as transDistance,carrier.carrier_name as transporterName,invoicehdr.invoice_grand_total as totalValue from s_invoice_hdr_t as invoicehdr left JOIN m_customers_t as customer on(invoicehdr.ship_to_customer_id=customer.customer_id)left JOIN m_customer_sites_t as customersite on(invoicehdr.ship_to_address_id=customersite.customer_site_id)left JOIN m_states_t as state on(state.state_id=customersite.state)left JOIN m_company_t as company on(company.company_id=invoicehdr.company_id)LEFT JOIN m_frieghtcarriers_hdr_t as carrier on(carrier.ar_frieghtcarriers_hdr_id=invoicehdr.ar_frieghtcarriers_hdr_id)left JOIN m_location_t as loc on(loc.location_id=invoicehdr.location_id)left JOIN m_states_t as locstate on(locstate.state_id=loc.state_id)left JOIN m_cities_t as city on(city.city_id=loc.city_id)where invoice_hdr_id=' . $invid . '');
        //        dd($invoicehdr);
        $invheader = json_encode($invoicehdr);

        $invoicelines = \DB::select('select 
                                    solines.line_no as ItemNo,
                                    prd.concatenated_product as productName,
                                    solines.description as productDesc,
                                    hsn.classification_code as hsncode,
                                    solines.qty as quantity,
                                    uom.uom_code as qtyUnit,
                                    solines.tax_amount as TaxableAmount,
                                    null as sgstRate,
                                    null as cgstRate,
                                    null as igstRate,
                                    null as cessRate
                                    from s_invoice_lines_t as solines
                                    LEFT JOIN m_products_t prd on(solines.product_id=prd.product_id)
                                    LEFT JOIN m_uom_codes_t uom on(uom.uom_code_id=solines.uom_code_id)
                                    LEFT JOIN f_gst_code_hdr_t hsn on(hsn.gst_code_hdr_id=prd.hsn_code)
                                    where invoice_hdr_id=' . $invid . '');
        $invlines = json_encode($invoicelines);
        //                    dd($invlines);
        $invoice[] = json_decode($invheader, true);
        $invoice[] = json_decode($invlines, true);
        $json_merge = json_encode($invoice);


        $my_file = uniqid() . '.txt';
        $handle = fopen($my_file, 'w') or die('Cannot open file:  ' . $my_file);
        fwrite($handle, $json_merge);

        return response()->json(array('data' => $json_merge, 'download_ling' => $my_file));

    }

    public function getdispatchupdate($id = null)
    {
        $orderid = [];
        $table = \DB::table('s_dispatch_hdr_t')->whereIn('so_dispatch_hdr_id', explode(',', $id))->get();
        foreach ($table as $k => $v) {
            $orderid[] = $v->ar_sales_hdr_id;
        }

        $salestable = \DB::table('s_salesorder_hdr_t')->where('sales_hdr_id', $orderid)->get();
        //dd($salestable);
        $this->data['row'] = (object) array();

        $this->data['row']->invoice_hdr_id = '';
        $this->data['row']->invoice_number = '';
        $this->data['row']->ar_sales_hdr_id = $orderid[0];
        $this->data['row']->invoice_status = 'INITIATED';
        $this->data['row']->source = "DISPATCH";
        // dd($this->data);
        if (count($salestable) > 0) {
            $this->data['row']->invoice_type = $salestable[0]->order_type_id;
            $soorderno = $salestable[0]->sales_order_no;
            $order_status = $salestable[0]->order_type_id;
        } else {
            $this->data['row']->invoice_type = "STANDARD";
            $soorderno = "";
            $order_status = "";
        }

        if (count($salestable) > 0) {
            $this->data['row']->invoice_type = $salestable[0]->order_type_id;
        } else {
            $this->data['row']->invoice_type = $table[0]->dispatch_status;
        }

        $this->data['row']->reference_number = $table[0]->dispatch_number;
        $this->data['row']->invoice_date = $table[0]->dispatch_date;
        $this->data['row']->reference_source_id = $table[0]->so_dispatch_hdr_id;
        $this->data['project_id'] = '';
        $this->data['salesperson_id'] = '';
        $this->data['row']->due_date = "";
        $this->data['row']->lr_no = "";
        $this->data['row']->invoice_tax_total = '';
        $this->data['row']->invoice_grand_total = '';
        $this->data['row']->trade_discount = '';
        $this->data['row']->round_off = '';
        $this->data['schemes'] = $this->jCombo('s_schemes_hdr_t', 'schemes_hdr_id', 'schemes_name', '');
        $this->data['row']->employee_id = $this->jCombo('hr_employee_t', 'employee_id', 'first_name|last_name', $table[0]->employee_id);
        $this->data['bill_to_address_id'] = $table[0]->ship_to_customer_id;
        //dd($this->data['bill_to_address_id']);
        $this->data['savestatus'] = "";
        $this->data['pagemode'] = 'create';
        $custaddress1 = new SoorderController;
        $custaddress2 = new SoquoteController;

        if ($table[0]->ar_sales_hdr_id != '') {
            $tables = \DB::select("select * from s_salesorder_hdr_t where sales_hdr_id in(" . $table[0]->ar_sales_hdr_id . ")");
            if (count($tables) > 0) {
                $this->data['discount'] = $this->jCombodiscount('m_discounts_hdr_t', 'ar_discount_hdr_id', 'discount_name', $tables[0]->discount_id);
            } else {
                $this->data['discount'] = $this->jCombodiscount('m_discounts_hdr_t', 'ar_discount_hdr_id', 'discount_name', '');
            }
        } else {
            $this->data['discount'] = $this->jCombodiscount('m_discounts_hdr_t', 'ar_discount_hdr_id', 'discount_name', '');
        }

        $this->data['ship_to_address'] = $custaddress1->soshipaddress($table[0]->deliver_to_location);

        $address = $custaddress2->getaddress($table[0]->ship_to_customer_id);

        if (count($address) > 0) {
            $bill_add = explode('~', $address[1]);
            $bill_address = $bill_add[0];
        } else {
            $bill_add = '';
            $bill_address = '';
        }

        $this->data['bill_to_address'] = $bill_address;
        if ($order_status == "SAMPLE") {
            $emp = \DB::table('hr_emp_contact')->where('hr_emp_contact.employee_id', $table[0]->employee_id)->get();
            if (count($emp) > 0) {
                $this->data['bill_to_address'] = $emp[0]->current_street . "," . $emp[0]->current_street_address . "," . $emp[0]->current_postal_code;
                $this->data['ship_to_address'] = $emp[0]->current_street . "," . $emp[0]->current_street_address . "," . $emp[0]->current_postal_code;
            }
        }
        $this->data['row']->contact_person = '';
        $this->data['row']->contact_number = '';
        $this->data['row']->invoice_pricelist_id = $table[0]->pricelist_id;
        $org = \Session::get('organization');
        $this->data['row']->organization_id = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $org);
        $this->data['row']->remarks = $table[0]->remarks;
        $this->data['row']->payment_method_id = '';
        $decimal = \Session::get('decimal');
        if (count($salestable) > 0) {
            $this->data['row']->payment_term_id = $salestable[0]->ar_payment_term_id;
            $this->data['row']->packaging_charges = number_format($salestable[0]->packaging_charges, $decimal, '.', '');
            $this->data['row']->packaging_charges_tax = $salestable[0]->packaging_charges_tax;
            $this->data['row']->insurance_charges_tax = $salestable[0]->insurance_charges_tax;
            $this->data['row']->insurance_charges = str_replace(",", "", (number_format($salestable[0]->insurance_charges, $decimal)));
            $this->data['row']->other_tax_amount = str_replace(",", "", (number_format($salestable[0]->other_tax_amount, $decimal)));
            $this->data['row']->other_tax_amount_tax = $salestable[0]->other_tax_amount_tax;
            $this->data['row']->other_frieght_amount = str_replace(",", "", (number_format($salestable[0]->other_frieght_amount, $decimal)));
            $this->data['row']->other_frieght_amount_tax = $salestable[0]->other_frieght_amount_tax;
            $this->data['row']->transport_charges = str_replace(",", "", (number_format($salestable[0]->transport_charges, $decimal)));
            $this->data['row']->transport_charges_tax = $salestable[0]->transport_charges_tax;
            $this->data['invoice_currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $salestable[0]->currency_code_id);
            $this->data['delivery_term_id'] = $this->jCombo('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $salestable[0]->ar_delivery_terms_id);
        } else {
            $this->data['row']->payment_term_id = "";
            $this->data['row']->packaging_charges = '';
            $this->data['row']->packaging_charges_tax = '';
            $this->data['row']->insurance_charges_tax = '';
            $this->data['row']->insurance_charges = '';
            $this->data['row']->other_tax_amount = '';
            $this->data['row']->other_tax_amount_tax = '';
            $this->data['row']->other_frieght_amount = '';
            $this->data['row']->other_frieght_amount_tax = '';
            $this->data['row']->transport_charges = '';
            $this->data['row']->transport_charges_tax = '';
            $this->data['invoice_currency'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', '');
            $this->data['delivery_term_id'] = $this->jCombo('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', '');
        }

        $this->data['row']->created_by = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));

        $tablelines = \DB::table('s_dispatch_lines_t')->whereIn('so_dispatch_hdr_id', explode(',', $id))->groupBy('product_id')->orderby('so_dispatch_line_id', 'asc')->get();
        $this->data['subgrid']['label_data'] = $table;
        // dd($tablelines);
        $tablefield = $this->submodel->getTableColumns();


        foreach ($tablefield as $key => $val) {
            $data[$val] = $val;
        }

        $grandtotal = 0;

        $taxamount_total = 0;
        foreach ($tablelines as $key => $value) {
            $tablelines_data = \DB::table('s_dispatch_lines_t')->whereIn('so_dispatch_hdr_id', explode(',', $id))->where('product_id', $value->product_id)->orderby('so_dispatch_line_id', 'asc')->get();
            // dd($tablelines_data);
            $sum_qty = 0;
            $dispatch_qty = 0;
            $hrd_id = '';
            $line_id = '';
            foreach ($tablelines_data as $k0 => $v0) {
                $sum_qty = $v0->sototqty + $sum_qty;
                $dispatch_qty = $v0->dispatched_qty + $dispatch_qty;
                $hrd_id .= $v0->ar_sales_hdr_id . ",";
                $line_id .= $v0->ar_sales_line_id . ",";
            }

            $order_qty = $sum_qty;
            $sum_qty = $sum_qty - $dispatch_qty;

            $result = \DB::table('s_salesorder_lines_t')->select('qty as qty', 'discount_percentage', 'discount_amount', 'unit_price', 'tax_group_id', 'hsn_code', 'sales_hdr_id')->where('sales_hdr_id', $value->reference_hdr_id)->where('product_id', $value->product_id)->get();

            if ($result->isNotEmpty()) {
                $qty = $result[0]->qty;
                $discount_percentage = $result[0]->discount_percentage;
                $hsn_code = $result[0]->hsn_code;
            } else {
                $qty = 0;
                $discount_percentage = 0;
                $hsn_code = '';
            }

            $this->data['subgrid']['rowData'][$key] = (object) array();
            $this->data['subgrid']['rowData'][$key]->invoice_hdr_id = $id;
            $this->data['subgrid']['rowData'][$key]->invoice_line_id = '';
            $this->data['subgrid']['rowData'][$key]->reference_hdr_id = $value->so_dispatch_hdr_id;
            $this->data['subgrid']['rowData'][$key]->reference_line_id = $value->so_dispatch_line_id;
            $this->data['subgrid']['rowData'][$key]->ar_sales_line_id = $value->ar_sales_line_id;
            $this->data['subgrid']['rowData'][$key]->tax_excemption = 'No';
            $this->data['subgrid']['rowData'][$key]->part_no = $this->jcustomselecttool('m_manufacturer_partno_t', 'manufacturer_partno_id', 'part_no', $value->part_no, 'and product_id=' . $value->product_id . ' and manufacturer_source_value_id=' . $table[0]->ship_to_customer_id);
            $this->data['subgrid']['rowData'][$key]->line_no = $key;

            $pid = $value->product_id;
            $comp = \Session::get('companyid');

            $stock_qty = DB::SELECT("select sum(qoh_trx_qty) as qoh FROM i_qoh_detail_t where product_id=" . $pid . " and company_id=" . $comp . " and i_qoh_detail_t.qualitystatus=1");

            $res = DB::SELECT("select sum(reserv_trx_qty) as resqty from i_reservation_detail_t where product_id=" . $value->product_id . " and  i_reservation_detail_t.reference_no ='" . $soorderno . "' and i_reservation_detail_t. company_id=" . $comp . "  GROUP by product_id");
            if (count($res) > 0) {
                $reserveqty = $res[0]->resqty;
            } else {
                $reserveqty = 0;
            }
            if (count($stock_qty) > 0) {
                if (is_null($stock_qty[0]->qoh)) {
                    $this->data['subgrid']['rowData'][$key]->qoh = 0;
                } else {
                    $this->data['subgrid']['rowData'][$key]->qoh = number_format(($stock_qty[0]->qoh + $reserveqty), $decimal, ".", "");
                }
                if ($stock_qty[0]->qoh < 0) {
                    $this->data['subgrid']['rowData'][$key]->qoh = 0;
                }
            } else {
                $this->data['subgrid']['rowData'][$key]->qoh = 0;
            }

            $this->data['subgrid']['rowData'][$key]->product_id = $value->product_id;
            //  $this->data['subgrid']['rowData'][$key]->salesorder_qty=$value->so_qty;
            $this->data['subgrid']['rowData'][$key]->salesorder_qty = str_replace(",", "", (number_format($value->dispatch_qty, $decimal)));

            $this->data['subgrid']['rowData'][$key]->uom_code_id = $value->uom_code_id;

            //$this->data['subgrid']['rowData'][$key]->salesorder_qty=$value->so_qty;
            $this->data['subgrid']['rowData'][$key]->qty = str_replace(",", "", (number_format($value->dispatch_qty, $decimal)));
            // dd($value);
            $this->data['subgrid']['rowData'][$key]->comments = $value->comments;


            //     $tax = $this->gettax($value->product_id);

            if ($this->data['row']->invoice_type != 'LABOUR')
                $unitprice = $this->getprice($table[0]->dispatch_source, $table[0]->so_dispatch_hdr_id, $value->product_id);

            if ($table[0]->dispatch_source == "DISPATCH") {

                $u_price = $this->productdetails_so($value->product_id, $table[0]->pricelist_id, $table[0]->ship_to_customer_id, '');

                if (!empty($u_price['unit_price'])) {
                    $unitprice = $u_price['unit_price'];
                } else {
                    $unitprice = "0.00";
                }
                $hsn_code = $u_price['hsn_code'];
                $tax_g = $u_price['tax_group_id'];
                // dd($u_price);
            } else {

                $u_price = [];
                if (count($result) > 0) {
                    $tax_g = $result[0]->tax_group_id;
                    $unitprice = $result[0]->unit_price;
                } else {
                    $tax_g = 0;
                    $unitprice = 0;
                }


            }
            $result_tax = \DB::table('m_tax_group_t')->select('display_name')->where('tax_group_id', $tax_g)->get();
            // dd($result_tax);
            //dd($address[1]);
            if (count($result_tax) != 0) {
                $t = $result_tax[0]->display_name;
            } else {
                $t = 1;

            }
            // dd($t);
            $discount_amount = ($value->dispatch_qty * $unitprice) * ($discount_percentage / 100);
            $taxamount = (($value->dispatch_qty * $unitprice - ($discount_amount)) * $t) / 100;

            $linetotal = ($taxamount + ($value->dispatch_qty * $unitprice - ($discount_amount)));

            $taxamount_total += $taxamount;

            $grandtotal = $grandtotal + $linetotal;



            $this->data['subgrid']['rowData'][$key]->unit_price = str_replace(",", "", (number_format($unitprice, $decimal)));
            //$this->data['subgrid']['rowData'][$key]->tax_group_id=$this->taxgroup($value->product_id);
            $this->data['subgrid']['rowData'][$key]->tax_group_id = $tax_g;

            $this->data['subgrid']['rowData'][$key]->tax_amount = str_replace(",", "", (number_format($taxamount, $decimal)));
            $this->data['subgrid']['rowData'][$key]->line_total = str_replace(",", "", (number_format($linetotal, $decimal)));
            $this->data['subgrid']['rowData'][$key]->hsn_code = $this->jCombo('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $hsn_code);

            //   ($value->dispatch_qty * $unitprice) * 

            //$grandtotal = $grandtotal + $linetotal;
            $this->data['subgrid']['rowData'][$key]->discount_percentage = $discount_percentage;
            $this->data['subgrid']['rowData'][$key]->discount_amount = str_replace(",", "", (number_format($discount_amount, $decimal)));

            $this->data['subgrid']['rowData'][$key]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'Salesreplacement');
            //dd($this->data['subgrid']['rowData'][$key]->productid);
            $this->data['subgrid']['rowData'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);

            $this->data['subgrid']['rowData'][$key]->taxgroup_id = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $tax_g);
            if ($table[0]->ship_to_customer_id == "") {
                $this->data['product'] = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'soquote');
            } else {
                $this->data['product'] = $custaddress1->getcustomerpriceproduct($table[0]->ship_to_customer_id, '');
            }
            if ($table[0]->ship_to_customer_id == "" && $value->product_id == "") {
                $this->data['subgrid']['rowData'][$key]->product_id = $this->jcustomproductselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', 'soquote');
            } else if ($table[0]->ship_to_customer_id != "" && $value->product_id == "") {
                $this->data['subgrid']['rowData'][$key]->product_id = $custaddress1->getcustomerpriceproduct($table[0]->ship_to_customer_id, $value->product_id);
            } else {
                $this->data['subgrid']['rowData'][$key]->product_id = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
            }
        }
        $this->data['row']->invoice_tax_total = str_replace(",", "", (number_format($taxamount_total, $decimal)));
        ;
        $this->data['row']->invoice_grand_total = str_replace(",", "", (number_format($grandtotal, $decimal)));
        $this->data['row']->balance_amount = str_replace(",", "", (number_format($grandtotal, $decimal)));

        $cusalladata = $this->getcustomerinvdatas($table[0]->ship_to_customer_id);
        if ($cusalladata) {
            $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $cusalladata[0]->default_payment_terms_id);
            $this->data['payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $cusalladata[0]->default_payment_method_id);
            $this->data['salesperson_id'] = $this->jCombo('s_salesperson_t', 'salesperson_id', 'salesperson_name', $cusalladata[0]->sales_person);
        } else {
            $this->data['payment_term_id'] = $this->jCombo('m_payment_terms_t', 'payment_term_id', 'payment_term_name', '');
            $this->data['payment_method_id'] = $this->jCombo('m_payment_methods_t', 'payment_method_id', 'payment_method_name', '');
            $this->data['salesperson_id'] = $this->jCombo('s_salesperson_t', 'salesperson_id', 'salesperson_name', '');
        }

        $this->data['ar_frieghtcarriers_hdr_id'] = $this->jCombo('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $table[0]->freight_carrier_id);

        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);

        $this->data['pricelist'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $table[0]->pricelist_id, ' and price_list_type="Sales"');
        $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);
        //dd($table[0]->bill_to_customerid);
        $this->data['ship_to_customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_number|customer_name', $table[0]->ship_to_customer_id);

        $this->data['project_id'] = $this->jCombo('m_projects_t', 'project_id', 'project_name', '');

        $this->data['taxgroup_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');




        $this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);


        $this->data['linedata'] = $this->data['subgrid']['rowData'];


        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'Salesreplacement')->get();
        $this->data['return_url'] = \Request::route()->getName();
        $this->data['return_url'] = 'invoicefromdispatch';
        $this->data['route'] = 'invoicefromdispatch';
        if (isset($_GET['pageMethod'])) {
            $this->data['pageMethod'] = $_GET['pageMethod'];
        }

        // dd($this->data);
        return view('Salesreplacement.form', $this->data);

    }


    public function getcustomerinvdatas($id = null)
    {
        if ($id != "") {
            $cusdata = \DB::select("select * from m_customers_t where customer_id=$id");
            return $cusdata;
        }
    }



    function getprice($source, $sourceid, $pdt_id)
    {
        $unitprice = "0.0";
        $sql = \DB::table('s_dispatch_hdr_t')->where('so_dispatch_hdr_id', $sourceid)->where('dispatch_source', $source)->get(); //dd($sql);
        if ($sql->isNotEmpty()) {
            if ($sql[0]->dispatch_source == "PICK ORDER") {

                $sqlpic = \DB::table('s_pickrelease_hdr_t')->where('so_pickrelease_hdr_id', $sql[0]->reference_source_id)->get();
                if ($sqlpic->isNotEmpty()) {
                    $sqlpri = \DB::table('s_salesorder_lines_t')->where('sales_hdr_id', $sqlpic[0]->ar_sales_hdr_id)->where('product_id', $pdt_id)->get();
                    if ($sqlpic->isNotEmpty()) {
                        $unitprice = $sqlpri[0]->unit_price;
                    } else {
                        $unitprice = "0.00";
                    }
                }
            }
            if ($sql[0]->dispatch_source == "SALES ORDER") {

                $sqlpri = \DB::table('s_salesorder_lines_t')->where('sales_hdr_id', $sql[0]->reference_source_id)->where('product_id', $pdt_id)->get();
                if ($sqlpri->isNotEmpty()) {
                    $unitprice = $sqlpri[0]->unit_price;
                } else {
                    $unitprice = "0.00";
                }
            }
        } else {
            $unitprice = "0.00";
        }
        return $unitprice;
    }


    public function taxgroup($pid = null)
    {
        $pro_details = \DB::table("m_products_t")->select('trx_uom_id', 'hsn_code')->where('product_id', $pid)->get();

        if ($pro_details->isNotEmpty()) {
            $prd_data['uom_code_id'] = $pro_details[0]->trx_uom_id;
            $hsn_code = $pro_details[0]->hsn_code;
            $date = date('Y-m-d');
            $tax = \DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='$hsn_code' and start_date<='$date' and end_date>='$date' and active='Yes'");
            if (!empty($tax)) {
                return $prd_data['tax_group_id'] = $tax[0]->tax_group_id;
            } else {


                return 0;
            }
        } else {
            return 0;
        }
    }

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
                //return 1;
                return $prd_data;
            }
        } else {
            $prd_data['uom_code_id'] = 0;
            $prd_data['taxgroup_id'] = 0;
            $prd_data['display_name'] = 1;
            //return 1;
            return $prd_data;

        }
    }



    public function newshiptocustomer(Request $request, $id = null)
    {
        // dd($_POST);
        $customersites = new Customersites();

        $data1['customer_site_id'] = $_POST['customer_site_id'] ? $_POST['bulk_customer_site_id'] : 0;
        $data1['customer_site_number'] = '';

        $data1['customer_site_name'] = $_POST['customer_site_name'];
        $data1['site_type'] = $_POST['custype'];
        $data1['address'] = $_POST['address'];
        $data1['country'] = $_POST['country'];
        $data1['state'] = $_POST['state'];
        $data1['city'] = $_POST['city'];
        $data1['pincode'] = $_POST['pin_code'];

        $data1['customer_id'] = $id;
        $data1['contact_number'] = $_POST['contact_number'];
        $data1['contact_person'] = $_POST['contact_person'];
        $data1['contact_mail'] = $_POST['contact_mail'];
        $data1['active'] = "YES";
        $data1['primary_address'] = $_POST['primary_address'];
        $data1['location_id'] = \Session::get('location');
        $data1['company_id'] = \Session::get('companyid');
        $data1['organization_id'] = \Session::get('organization');
        //dd($data1);
        $result = [];

        if ($_POST['primary_address'] == "YES") {
            \DB::table('m_customer_sites_t')->where('customer_id', $id)->where('site_type', '=', $_POST['custype'])->update(['primary_address' => "NO"]);
        }

        $result[0] = $s_id = \DB::table('m_customer_sites_t')->insertGetId($data1);

        $address = new SoorderController;
        $result[1] = $address->custaddresstype($id, $s_id);
        return $result;

        /*
        if($result)
          return 0;
        else
        return 1;
        */

    }


    function pricelist_cust($customer_id)
    {
        $result = \DB::table('m_customers_t')->select('pricelist_id as pricelist_id')->where('customer_id', $customer_id)->get();

        if ($result->isNotEmpty()) {
            return $pricelist = $result[0]->pricelist_id;
        } else {
            return 0;
        }

    }

    function pricelist($customer, $product_id)
    {
        $result = \DB::table('m_customers_t')->select('pricelist_id as pricelist_id')->where('customer_id', $customer)->get();
        //dd($result);
        if ($result->isNotEmpty()) {
            $pricelist = $result[0]->pricelist_id;
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

    function pricelistorder($sales_line_id, $product_id)
    {
        //dd($sales_line_id);
        $result = \DB::table('s_salesorder_lines_t')->select('unit_price as unit_price')->where('sales_line_id', $sales_line_id)->where('product_id', $product_id)->get();
        //  dd($result);
        if ($result->isNotEmpty()) {
            $unit_price = $result[0]->unit_price;
            //dd($unitprice);
        } else {
            $unit_price = '0.00';
        }

        return $unit_price;
    }


    function findPrimarykey($table)
    {
        $primaryKey = '';
        foreach (\DB::select("show columns from " . $table . " where extra like '%auto_increment%'") as $key) {
            $primaryKey = $key->Field;
        }
        return $primaryKey;
    }

    public function show($id = null, $mesg = null)
    {
        if (isset($id)) {

            $customer = DB::table('s_invoice_hdr_t')->leftjoin('m_customers_t', 'm_customers_t.customer_id', '=', 's_invoice_hdr_t.ship_to_customer_id')
                ->leftjoin('i_pricelist_hdr_t', 'i_pricelist_hdr_t.pricelist_hdr_id', '=', 's_invoice_hdr_t.invoice_pricelist_id')
                ->leftjoin('m_projects_t', 'm_projects_t.project_id', '=', 's_invoice_hdr_t.project_id')
                ->leftjoin('s_salesperson_t', 's_salesperson_t.salesperson_id', '=', 's_invoice_hdr_t.salesperson_id')
                ->select(
                    's_invoice_hdr_t.invoice_number',
                    's_invoice_hdr_t.invoice_date',
                    's_invoice_hdr_t.invoice_type',
                    's_invoice_hdr_t.invoice_status',
                    's_invoice_hdr_t.source',
                    's_invoice_hdr_t.employee_id',
                    's_invoice_hdr_t.reference_number',
                    'm_customers_t.customer_name',
                    'i_pricelist_hdr_t.pricelist_name',
                    'i_pricelist_hdr_t.pricelist_hdr_id',
                    'm_projects_t.project_name',
                    's_salesperson_t.salesperson_name',
                    's_invoice_hdr_t.*'
                )->where('invoice_hdr_id', $id)->get();

            $this->data['header_data'] = $customer;

            if ($customer[0]->employee_id != 0) {
                $this->data['empname'] = $this->idname("first_name|last_name", "hr_employee_t", "employee_id", $customer[0]->employee_id);
            } else {
                $this->data['empname'] = "";
            }
            // dd($this->data['header_data']->bill_to_address_id );
            $this->data['freightcarrier'] = $this->idname('carrier_name', 'm_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', $customer[0]->ar_frieghtcarriers_hdr_id);
            $this->data['pay_term'] = $this->idname('payment_term_name', 'm_payment_terms_t', 'payment_term_id', $customer[0]->payment_term_id);
            $this->data['pay_method'] = $this->idname('payment_method_name', 'm_payment_methods_t', 'payment_method_id', $customer[0]->payment_method_id);
            $this->data['created'] = $this->idname('username', 'tb_users', 'id', $customer[0]->created_by);
            $this->data['currency'] = $this->idname('currency_code', 'f_account_currency_t', 'account_currency_id', $customer[0]->invoice_currency);
            $this->data['delivery_term'] = $this->idname('delivery_term_name', 'm_delivery_terms_t', 'delivery_terms_id', $customer[0]->delivery_term_id);
            $this->data['invocie_date'] = date(\Session::get('p_date_format'), strtotime($customer[0]->invoice_date));
            $this->data['approved_date'] = date(\Session::get('p_date_format'), strtotime($customer[0]->approved_date));
            $this->data['lr_date'] = date(\Session::get('p_date_format'), strtotime($customer[0]->lr_date));
            $result_id = DB::table('s_invoice_hdr_t')->where('invoice_hdr_id', $id)->select('ship_to_customer_id')->get();

            $vlinesdata = DB::table('s_invoice_lines_t')
                ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 's_invoice_lines_t.product_id')
                ->leftJoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 's_invoice_lines_t.uom_code_id')
                ->leftJoin('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 's_invoice_lines_t.tax_group_id')
                ->leftJoin('f_gst_code_hdr_t', 'f_gst_code_hdr_t.gst_code_hdr_id', '=', 's_invoice_lines_t.hsn_code')
                ->leftJoin('m_manufacturer_partno_t', 'm_manufacturer_partno_t.manufacturer_partno_id', '=', 's_invoice_lines_t.part_no')
                ->select(
                    's_invoice_lines_t.line_no',
                    's_invoice_lines_t.unit_price',
                    's_invoice_lines_t.discount_percentage',
                    's_invoice_lines_t.discount_amount',
                    's_invoice_lines_t.tax_amount',
                    's_invoice_lines_t.reference_line_id',
                    's_invoice_lines_t.tax_excemption',
                    's_invoice_lines_t.tax_excemption',
                    's_invoice_lines_t.line_total',
                    's_invoice_lines_t.description',
                    's_invoice_lines_t.comments',
                    's_invoice_lines_t.salesorder_qty',
                    's_invoice_lines_t.qty',
                    'm_products_t.concatenated_product',
                    'm_products_t.product_code',
                    'm_manufacturer_partno_t.part_no',
                    'm_products_t.product_id',
                    'm_uom_codes_t.uom_code',
                    'f_gst_code_hdr_t.classification_code',
                    'm_tax_group_t.tax_group_name'
                )->where('invoice_hdr_id', $id)->get();

            $date = date('Y-m-d');

            foreach ($vlinesdata as $k => $v) {
                $mrp = DB::table('i_pricelist_lines_t')->where('pricelist_hdr_id', $customer[0]->pricelist_hdr_id)->where('product_id', $v->product_id)->where('active', 'Yes')->whereDate('start_date', '>=', $date)->whereDate('end_date', '<=', $date)->select('*')->get();
                if (count($mrp) > 0) {
                    $v->mrp = $mrp[0]->std_price;
                } else {
                    $v->mrp = 0;
                }
                $v->batch_no = '';
                $v->issues_qoh = '';
                $batch_no = '';
                $issueqoh = '';
                $issueqoh1 = '';
                if ($customer[0]->source == "DISPATCH") {
                    $sql = DB::table("s_dispatch_lines_t")->where('s_dispatch_lines_t.so_dispatch_line_id', $v->reference_line_id)
                        ->leftjoin('s_dispatched_qty_t', 's_dispatched_qty_t.so_dispatch_line_id', '=', 's_dispatch_lines_t.so_dispatch_line_id')
                        ->select('*')->get();
                    if (count($sql) > 0) {
                        foreach ($sql as $ke => $va) {
                            $batch_no .= $va->batch_no . ",";
                            $issueqoh .= $va->issue_qoh . ",";
                        }
                        $batch_no = rtrim($batch_no, ',');
                        $issueqoh1 = rtrim($issueqoh, ',');
                    }
                }
                $v->batch_no = $batch_no;
                $v->issues_qoh = $issueqoh1;
            }
            $address_result = DB::table('s_invoice_hdr_t')->leftJoin('m_customer_sites_t', 'm_customer_sites_t.customer_id', '=', 's_invoice_hdr_t.ship_to_address_id')
                ->leftJoin('m_cities_t', 'm_cities_t.city_id', '=', 'm_customer_sites_t.city')
                ->leftJoin('m_states_t', 'm_states_t.state_id', '=', 'm_customer_sites_t.state')
                ->leftJoin('m_countries_t', 'm_countries_t.country_id', '=', 'm_customer_sites_t.country')
                ->select(DB::raw('CONCAT(m_customer_sites_t.address,", ",m_cities_t.city_name,",",m_states_t.state_name,",",m_countries_t.country_name,",",m_customer_sites_t.pincode) as address'), 'm_customer_sites_t.customer_site_name', 'm_customer_sites_t.site_type', 'm_customer_sites_t.customer_site_id', 'm_customer_sites_t.customer_id')->groupBy('m_customer_sites_t.customer_site_id')->where('m_customer_sites_t.customer_id', $result_id[0]->ship_to_customer_id)->where('s_invoice_hdr_t.invoice_hdr_id', $id)->get();

            $this->data['vlinesdata'] = $vlinesdata;
            $this->data['address_result'] = $address_result;
            $this->data['header_data']->bill_to_address_id = $this->addressget($customer[0]->bill_to_address_id);
            $this->data['header_data']->ship_to_address_id = $this->addressget($customer[0]->ship_to_address_id);
            $this->data['header_data']->discount = $this->discountget($customer[0]->discount_id);
            //dd($this->data['header_data']);
            if ($mesg != null) {
                return $this->data;
            } else {
                $this->data['return_url'] = $_GET['return'];
                return view('Salesreplacement.view', $this->data);
            }
        }


    }


    public function update(Request $request, Salesreplacement $Salesreplacement)
    {
        //
    }

    /*Karthigaa purpose for delete function*/
    //  public function delete(Request $request,$id=null)
//        {
//          Salesreplacement::destroy($id);
//                \DB::table('s_invoice_lines_t')->where('invoice_hdr_id', $id)->delete();
//                return redirect('Salesreplacement');
//  }

    public function getStatus($id = null, $status_type = null)
    {

        $soquote_id = $id;
        $soquotequery = DB::table('s_invoice_hdr_t')->where('invoice_hdr_id', $soquote_id)->get();


        if ($status_type == "savestatus")
            $status_type1 = $soquotequery[0]->savestatus;
        else if ($status_type == "invoice_type")
            $status_type1 = $soquotequery[0]->invoice_status;


        // echo $status_type1; exit;
        return $status_type1;

    }



    function getFreight($id)
    {
        $sql = \DB::SELECT("select * from m_frieghtcarriers_hdr_t where ar_frieghtcarriers_hdr_id='" . $id . "'");
        if (!empty($sql))
            return $sql[0]->carrier_name;
        else
            return '';
    }
    function custpo($id)
    {
        $sql = \DB::SELECT("select * from s_salesorder_hdr_t where sales_hdr_id='" . $id . "'");

        if (!empty($sql)) {
            $data['customer_po_number'] = $sql[0]->customer_po_number;
            $data['sales_order_no'] = $sql[0]->sales_order_no;
            $data['sales_order_date'] = $sql[0]->sales_order_date;
            return $data;
        } else {
            $data['customer_po_number'] = '';
            $data['sales_order_no'] = '';
            $data['sales_order_date'] = '';
            return $data;
        }
    }
    function dispno($id)
    {
        $sql = \DB::SELECT("select * from s_dispatch_hdr_t where so_dispatch_hdr_id='" . $id . "'");
        if (!empty($sql)) {
            $data['number'] = $sql[0]->dispatch_number;
            $data['date'] = $sql[0]->dispatch_date;
            return $data;
        } else
            $data['number'] = '';
        $data['date'] = '';
        return $data;
    }
    function dipfromino($id)
    {
        $sql = \DB::SELECT("select * from s_dispatch_hdr_t where dispatch_source ='INVOICE' and reference_source_id='" . $id . "'");
        if (!empty($sql)) {
            $data['number'] = $sql[0]->dispatch_number;
            $data['date'] = $sql[0]->dispatch_date;
            return $data;
        } else
            $data['number'] = '';
        $data['date'] = '';
        return $data;
    }


    function getPaymentterm($payment_id = null)
    {
        $payment_term = \DB::table('m_payment_terms_t')->where('payment_term_id', $payment_id)->get();
        if (($payment_term->isNotEmpty())) {
            return $payment_term[0]->payment_term_name;
        } else {
            return '';
        }
    }

    function getLocationwiseaddress($location = null)
    {
        $sql = array();

        $sql = \DB::SELECT('select * from m_location_t where location_id=' . $location . '');
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }

    function getCustomer($id = null)
    {

        $sql = array();
        $sql = \DB::SELECT('select * from m_customers_t where customer_id=' . $id . '');

        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }

    }

    function getBill_to_address($bill_to_id = null)
    {
        //dd($bill_to_id);
        $bill_to_address = array();
        $bill_to_address = \DB::select("select * from m_customer_sites_t where customer_id=" . $bill_to_id . " ");
        // $bill_to_address=\DB::select("SELECT * FROM m_customer_sites_t WHERE customer_id=".$bill_to_id." and customer_site_number!=''");
        // dd($bill_to_address);
        if (!empty($bill_to_address)) {
            return $bill_to_address;
        } else {
            return 0;
        }
    }

    function getSuppliersite($id = null)
    {
        $sql = array();
        $sql = \DB::SELECT('select * from m_supplier_sites_t where supplier_id=' . $id . '');
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
            return 0;
        }

    }
    function getState($id = null)
    {
        $sql = array();

        $sql = \DB::SELECT('select state_id,state_name,state_code,state_code_no from m_states_t where state_id=' . $id . '');
        if (!empty($sql)) {
            return $sql;

        } else {
            return 0;
        }

    }
    function getCountry($id = null)
    {
        $sql = array();
        $sql = \DB::SELECT('select country_id,country_name from m_countries_t where country_id =' . $id . '');
        if (!empty($sql)) {
            return $sql[0]->country_name;
        } else {
            return 0;
        }
    }

    function getCompany($company = null)
    {

        $company = \DB::SELECT("select company_name,gst_no from m_company_t where company_id='$company'");
        if (!empty($company)) {
            return $company;
        } else {
            return 0;
        }
    }


    function getHsncode($hsn_id = null)
    {
        $hsn_code = \DB::select("select classification_code from f_gst_code_hdr_t where gst_code_hdr_id='$hsn_id'");
        if (!empty($hsn_code)) {
            return $hsn_code[0]->classification_code;
        } else {
            return 0;
        }
    }






    public function delete($id = null)
    {

        $del_id = $id;

        $column = array('reference_source_id');
        $table = array('s_dispatch_hdr_t');
        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = DB::table($table[$i])->where($column[$i], $del_id)->get();
            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }

        if ($j == 0) {
            $query = DB::table('s_invoice_hdr_t')->where('invoice_hdr_id', $del_id)->delete();
            $query1 = DB::table('s_invoice_lines_t')->where('invoice_hdr_id', $del_id)->delete();
        }

        if ($j == 1)
            return 1;
        else if ($j == 0)
            return 0;
    }


    function getProduct($id = null)
    {
        $product = array();
        $product = \DB::table('m_products_t as pdt')
            ->leftJoin('m_uom_codes_t as uom', 'uom.uom_code_id', '=', 'pdt.trx_uom_id')
            ->select('uom.uom_code', 'pdt.concatenated_product', 'pdt.hsn_code', 'pdt.product_code')
            ->where('pdt.product_id', $id)->get();

        if ($product->isNotEmpty()) {

            $date = date('Y-m-d');
            $tax = \DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='" . $product[0]->hsn_code . "' and start_date<='$date' and end_date>='$date' and active='Yes'");

            if (!empty($tax)) {
                $product['tax_group_id'] = $tax[0]->tax_group_id;
            } else {
                $product['tax_group_id'] = 0;
            }
            $parts = explode(',', $product[0]->hsn_code);
            $hsn = \DB::table('f_gst_code_hdr_t')->whereIn('gst_code_hdr_id', $parts)->get()->toArray();
            $ARRAY = array_column($hsn, 'classification_code');
            $hsnvalue = implode(',', $ARRAY);
            $product['concat_segment'] = $product[0]->concatenated_product;
            $product['primary_uom_code'] = $product[0]->uom_code;
            $product['hsn_code'] = $hsnvalue;
            $product['product_code'] = $product[0]->product_code;


            return $product;


        } else {
            return 0;
        }

    }

    function getGst($gst, $po_id)
    {

        $sql = array();
        $location = \Session::get('ss_defaultloc_id');
        $sql = \DB::SELECT("select * from s_invoice_lines_t where invoice_hdr_id='" . $po_id . "' and tax_group_id='" . $gst . "'");
        $hsn = array();
        $gst = array();
        $sub_total = 0;

        foreach ($sql as $key => $value) {
            $ass = $value->unit_price * $value->qty;
            $dis_amt = $ass * $value->discount_percentage / 100;
            $amount = $ass - $dis_amt;
            if ($this->trd != '') {
                $trade_amt = $amount * $this->trd / 100;
            } else {
                $trade_amt = 0;
            }

            $amount1 = $amount - $trade_amt;
            $sub_total = $sub_total + $amount1;
        }

        $gst['amount'] = $sub_total;


        return $gst;
    }

    public function getcustomeralldata()
    {
        $id = $_GET['id'];

        if ($_GET['id'] != '') {
            $cusdata = \DB::select("select * from m_customers_t where customer_id=$id");
            return $cusdata;
        } else {
            return 0;
        }

    }




    public function lrupdate()
    {
        $d = new DateTime($_GET['lr_date']);
        $d = (string) $d->format('Y-m-d');
        if (isset($_GET['lr_status'])) {
            $status = $_GET['lr_status'];
        } else {
            $status = "";
        }

        $check = \DB::update("update s_invoice_hdr_t set lr_no='" . $_GET['lr_no'] . "',lr_date='" . $d . "',lr_status='" . $status . "' where invoice_hdr_id='" . $_GET['id'] . "'");

        if ($check) {
            return 1;
        } else {

            return 0;

        }
    }

    public function invoiceshipconfirm($id)
    {
        $data = \DB::select("SELECT source FROM `s_invoice_hdr_t` where invoice_hdr_id='$id'");
        if ($data[0]->source == "DISPATCH") {
            $sql = \DB::select("SELECT * FROM `s_invoice_hdr_t` left join s_dispatch_hdr_t on s_dispatch_hdr_t.so_dispatch_hdr_id=s_invoice_hdr_t.reference_source_id where s_invoice_hdr_t.source='DISPATCH' AND s_dispatch_hdr_t.dispatch_status='SHIPPED' and s_invoice_hdr_t.invoice_hdr_id='$id'");
        } else {
            $sql = \DB::select("SELECT * FROM `s_invoice_hdr_t` left join s_dispatch_hdr_t on s_dispatch_hdr_t.reference_source_id=s_invoice_hdr_t.invoice_hdr_id where s_dispatch_hdr_t.dispatch_source='INVOICE' AND s_dispatch_hdr_t.dispatch_status='SHIPPED' and s_invoice_hdr_t.invoice_hdr_id='$id'");
        }


        if (count($sql) > 0) {
            return 1;
        } else {
            return 0;
        }
    }
    public function lredit()
    {

        $sql = \DB::select("select lr_no,lr_date,lr_status from s_invoice_hdr_t where invoice_hdr_id='" . $_GET['id'] . "'");
        if (isset($sql)) {
            if ($sql[0]->lr_no != '' || $sql[0]->lr_date != '') {
                $data['lr_no'] = $sql[0]->lr_no;
                $data['lr_date'] = $sql[0]->lr_date;
                $data['lr_status'] = $sql[0]->lr_status;
                $data['update'] = 'update';
            } else if ($sql[0]->lr_no == '' && $sql[0]->lr_date == '') {
                $data['lr_no'] = $sql[0]->lr_no;
                $data['lr_date'] = $sql[0]->lr_date;
                $data['lr_status'] = $sql[0]->lr_status;
                $data['update'] = 'create';
            }

        }
        return $data;

    }

    //Purpose for TDS
    function salesloadtds($id = null, $tds = null)
    {
        $tds = array();
        $tds['tds_percentage'] = '';
        $tds['tds_account_id'] = '';
        $customer = \DB::table('m_customers_t')->where('customer_id', $id)->get();
        if ($customer->isNotEmpty()) {
            $tds['tds_percentage'] = $customer[0]->tds_percentage;
            $tds['tds_account_id'] = $customer[0]->tds_account_id;
        } else {
            $tds = 0;
        }
        return $tds;
    }



    public function addressget($id = null)
    {
        $sql = "SELECT
m_customer_sites_t.address,
m_customer_sites_t.customer_site_name,
m_customer_sites_t.pincode,
m_countries_t.country_name,
m_states_t.state_name,
m_cities_t.city_name
FROM `m_customer_sites_t`
left join m_countries_t ON
m_customer_sites_t.country=m_countries_t.country_id
left join m_states_t ON m_customer_sites_t.state=m_states_t.state_id
left join m_cities_t ON m_customer_sites_t.city=m_cities_t.city_id
where m_customer_sites_t.customer_site_id='$id' ";
        $result = \DB::select($sql);
        $address = '';
        if (count($result) > 0) {
            if ($result[0]->pincode == 0) {
                $address = $result[0]->customer_site_name . ',' . $result[0]->address . ',' . $result[0]->city_name . ',' . $result[0]->state_name . ',' . $result[0]->country_name;
            } else {
                $address = $result[0]->customer_site_name . ',' . $result[0]->address . ',' . $result[0]->city_name . ',' . $result[0]->state_name . '-' . $result[0]->pincode . ',' . $result[0]->country_name;
            }
        }
        return $address;
    }

    public function discountget($id = null)
    {
        $sql = "SELECT discount_name
FROM `m_discounts_hdr_t`
where ar_discount_hdr_id = '$id' ";
        $result = \DB::select($sql);
        if (count($result) > 0) {
            $discount = $result[0]->discount_name;
        } else {
            $discount = 0;
        }
        return $discount;
    }


    public function Salesreplacementcurrency($id = null)
    {
        $date = date('Y-m-d');
        $comp = \Session::get('companyid');
        $currency_rate = 0;
        $data = \DB::table('f_account_exchangerates_t')->whereDate('from_date', '<=', $date)->whereDate('to_date', '>=', $date)->where('from_currency_id', $id)->where('to_currency_id', 37)->where('active', 'Yes')->where('company_id', $comp)->select('*')->get();

        if (count($data) > 0) {

            $currency_rate = $data[0]->conversion_rate;
        }

        return $currency_rate;
    }
    /*Scehems Order Value Checking -Isac Naveen*/
    public function schemesordercheck()
    {
        $schemes = explode(",", $_GET['schemes']);
        $total = $_GET['total'];

        $check = \DB::table('s_schemes_hdr_t')->whereIn('schemes_hdr_id', $schemes)->where('scheme_type', '=', 'order_based')->get();
        $sch = array();
        foreach ($check as $val) {
            $sch[] = $val->schemes_hdr_id;
        }

        $data = \DB::table('s_schemes_lines_t')->whereIn('schemes_hdr_id', $sch)->where('scheme_base_value_from', '<=', $total)->where('scheme_base_value_to', '>=', $total)->get();

        return $data;
    }
    /*End*/
}
