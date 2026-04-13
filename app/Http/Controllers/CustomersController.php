<?php

namespace App\Http\Controllers;
use App\customers;
use App\Customersites;
use App\Customeroverdue;
use App\Overdue;
use DB;
use Illuminate\Http\Request;
use Config;
use session;
use Yajra\DataTables\DataTables;

class CustomersController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->model = new customers();

        $this->model = new customers;
        $this->pageModule = "customers";
        $this->submodel = new customersites;
        $this->overduemodel = new Overdue;
        $this->table = "m_customers_t";
        $this->subtable = "m_customer_sites_t";
        $this->overduetable = "over_due_t";
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';

        $this->data['pageModule'] = $this->pageModule;
        $this->middleware('auth');
        $this->data = array(
            'pageModule' => 'customers',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu'] = $this->indexs();



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


        $this->data['opts'] = $this->jqgridselect('m_customer_types_t', 'customer_type_id', 'customer_type');
        $this->data['customer'] = $this->jqgridselect('m_customers_t', 'customer_name', 'customer_name');
        $this->data['salesperson'] = $this->jqgridselect('hr_employee_t', 'employee_id', 'first_name');
        $this->data['price'] = $this->jqgridselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name');
        $table = \DB::table('m_customers_t')->get();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['opt'] = $table;
        $this->data['datas'] = json_encode($table);
        return view('customers.table', $this->data);

    }
	
    /*  purpose for Jq Grid Edit data*/
    public function getGridData()
    {
        $wh = '';


        $com = \Session::get('companyid');
        $loc = \Session::get('location');
        $org = \Session::get('organization');
        $groupname = \Session::get('groupname');
        if ($groupname == '1' || $groupname == 'Admin') {
            $wh .= 'and  m_customers_t.company_id=' . $com;
        } else {
            $wh .= 'and  m_customers_t.company_id=' . $com . ' and m_customers_t.location_id=' . $loc;
        }
        $app_id = \Session::get('id');

        if ($_GET['pageMethod'] == 'customersapproval') {
            $wh .= " and m_customers_t.savestatus='INITIATED' and json_contains(m_customers_t.approver_id ,'" . $app_id . "')=1 ";
        }

        $com = \Session::get('companyid');
        $loc = \Session::get('location');
        $org = \Session::get('organization');

        $SQL = "SELECT
    m_customers_t.customer_id,
    m_customers_t.customer_name,
    m_customers_t.customer_number,
    m_customers_t.active,
    case when m_customers_t.savestatus='SAVE' then 'APPROVED' else m_customers_t.savestatus end as savestatus,
    m_delivery_terms_t.delivery_term_name,
    m_frieghtterms_t.fob_point_name,
    hr_employee_t.first_name,
    m_customer_types_t.customer_type,
    f_account_structure_t.concatenated_segments,
    i_pricelist_hdr_t.pricelist_name,
    tb_users.first_name as created_by
FROM
    `m_customers_t`
    left join m_delivery_terms_t on m_customers_t.default_payment_terms_id=m_delivery_terms_t.delivery_terms_id
    left join m_frieghtterms_t on m_customers_t.frieghtterm_id=m_frieghtterms_t.frieghtterm_id
left JOIN hr_employee_t ON
    (
        hr_employee_t.employee_id = m_customers_t.`sales_person`
    )
left JOIN m_customer_types_t  ON
    (
        m_customer_types_t.customer_type_id = m_customers_t.`customer_type_id`
    )
LEFT JOIN i_pricelist_hdr_t ON i_pricelist_hdr_t.pricelist_hdr_id = m_customers_t.pricelist_id 
left join tb_users on m_customers_t.created_by=tb_users.id 
LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = m_customers_t.account_structure_id 
WHERE 1 = 1 $wh ";

     
        $result = \DB::select($SQL);
		return DataTables::of($result)->make(true);
		
    }

    public function jqedit()
    {
        return "successs";
    }
    /*end*/

    /* Create data function */
    public function create($id = null, $save_type = null, $copy_site = null)
    {

        $this->data['row'] = (object) array();
        $this->data['row']->customer_id = "";
        $this->data['row']->customer_name = "";
        $this->data['row']->customer_number = "";
        $this->data['row']->customer_type_id = "";
        $this->data['row']->savetatus = "";
        if (\Request::route()->getName() == "customers") {
            $this->data['row']->status = "CUSTOMER";
        } else {
            $this->data['row']->status = "QUICKCUSTOMER";
        }
        //$this->data['customer_type_id'] = $this->jCombo('m_customer_types_t','customer_type_id','customer_type','');
        $this->data['customer_type_id'] = $this->jcustomselect('m_customer_types_t', 'customer_type_id', 'customer_type', '', " and customer_type_id NOT IN (17,20)");
        $this->data['sales_person'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', '');
        $this->data['country_id'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name', '');
        $this->data['default_bank'] = $this->jCombo('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '');
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
        $this->data['pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', '', ' and price_list_type="Sales"');
        $this->data['schemes'] = $this->jcustomselect('s_schemes_hdr_t', 'schemes_hdr_id', 'schemes_name', '', ' and active="Yes" and savestatus="APPROVED"');
        $this->data['tds_percentage'] = $this->jCombo('f_tds_slab_t', 'tds_slab_id', 'tds_percentage', '');
        $this->data['tcs_percentage'] = $this->jCombo('f_tcs_slab_t', 'tcs_percentage', 'tcs_percentage', '');
        $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
        $this->data['tcs_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
        $this->data['default_payment_terms_id'] = $this->jcustomselect('m_payment_terms_t', 'payment_term_id', 'payment_term_name', '', '');
        $this->data['default_payment_method_id'] = $this->jcustomselect('m_payment_methods_t', 'payment_method_id', 'payment_method_name', '', '');
        $this->data['account_structure_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
        $this->data['delivery_terms_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', '', ' and source_type_id="Sales"');
        $this->data['frieghtterm_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', '', ' and source_type_id="Sales"');
        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'customers')->get();
        $this->data['state_id'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', '');
        $this->data['city_id'] = $this->jCombologin('m_cities_t', 'city_id', 'city_name', '');
        $this->data['ar_discount_hdr_id'] = $this->jCombo('m_discounts_hdr_t', 'ar_discount_hdr_id', 'discount_name', '');

        $this->data['customer_category'] = $this->jcustomselecttool('a_lookuplines_t', 'lookuplines_id', 'lookup_meaning', '', "and lookup_type='CUSTOMER_CATEGORY'");
        $this->data['credit_check'] = $this->jcustomselecttool('a_lookuplines_t', 'lookuplines_id', 'lookup_meaning', '', "and lookup_type='CREDIT_CHECK'");

        $this->data['ar_frieghtcarriers_hdr_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', '', ' and source_type_id="Sales"');

        $this->data['row']->billing_address = "";
        $this->data['row']->pf_registration_no = "";
        $this->data['row']->contact_person = "";
        $this->data['row']->contact_number = "";
        $this->data['row']->pan_no = "";
        $this->data['row']->alternate_name = "";
        $this->data['row']->payment_terms_id = "";
        $this->data['row']->line_of_business = "";
        $this->data['linedata'] = array();
        $seqno = $this->Seqnoe('SITE', 'm_customer_sites_t', "", 'customer_site_count');
        $this->data['customer_site_number'] = $seqno[0];
        $this->data['customer_site_count'] = $seqno[1];
        $this->data['site_count'] = $this->siteno();
        $this->data['save_type'] = $save_type;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['url'] = \Request::route()->getName();

        return view('customers.form', $this->data);
    }

    /* Start save */
    public function save(Request $request)
    {

        $customer = new customers();
        $this->modelname = new customers();
        $customersites = new Customersites();
        $this->modelline = new Customersites();
        $this->modeloverdue = new Customeroverdue();
        $primary = self::findPrimarykey('m_customers_t');
        $primaryline = self::findPrimarykey('m_customer_sites_t');

        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $upid = \Session::get('id');
        if ($_POST['customer_id'] != "") {
            $customer->customer_id = $_POST['customer_id'];
        }

        $customer->customer_name = $_POST['customer_name'];

        if ($_POST['status'] == "CUSTOMER") {


            $seqno = $this->Seqnoe('C', 'm_customers_t', "", 'customer_count');
            $customer->customer_number = $seqno[0];
            $customer->customer_count = $seqno[1];
            $customer->status = "CUSTOMER";

        } else if ($_POST['status'] == "QUICKCUSTOMER" && $_POST['customer_id'] != '') {
            $seqno = $this->Seqnoe('C', 'm_customers_t', "", 'customer_count');
            $_POST['customer_number'] = $seqno[0];
            $_POST['customer_count'] = $seqno[1];
            $_POST['status'] = "CUSTOMER";
        } else {

            $customer->customer_number = '';
            $customer->customer_count = '';
            $customer->status = $_POST['status'];

        }

        $customer->savestatus = $_POST['savestatus'];
        if ($_POST['savestatus'] == 'INITIATED') {
            $t = 0;
            $approverid = $this->Approvaldatacheck('customers', $t);
            //	dd($approverid);
            if ($approverid == "0") {

                $customer->approver_id = \Session::get('id');
                $customer->savestatus = "SAVE";
            } else {

                $customer->approver_id = $approverid;
            }
        }

        // attachement - vignesh m

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();

            // If it's a new customer, save to generate an ID first
            if (empty($_POST['customer_id']) || $_POST['customer_id'] == "0") {
                $customer->save();
                $_POST['customer_id'] = $customer->customer_id;  // Retrieve the new ID
            } else {
                // For update, fetch the existing customer record
                $customer = customers::find($_POST['customer_id']);
            }

            // Define the upload path based on customer_id
            $relativePath = 'uploads/cus_file_attach/' . $_POST['customer_id'];
            $destinationPath = public_path($relativePath);

            // Create directory if it doesn't exist
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Move the file to the destination folder
            $file->move($destinationPath, $filename);

            // Update the attachment field and save to the database
            $customer->attachment = $filename;
            $customer->save();  // This ensures the attachment filename is stored in DB
        } else if (!empty($_POST['customer_id'])) {
            $customer = customers::find($_POST['customer_id']);
        }


        $customer->pricelist_id = $_POST['pricelist_id'];
        $customer->schemes = $_POST['schemes'];

        $customer->default_payment_method_id = $_POST['default_payment_method_id'];
        $customer->default_payment_terms_id = $_POST['default_payment_terms_id'];

        $customer->customer_type_id = $_POST['customer_type_id'];

        $customer->pan_no = $_POST['pan_no'];
        $customer->sales_person = $_POST['sales_person'];
        $customer->alternate_name = $_POST['alternate_name'];


        $customer->customer_category = $_POST['customer_category'];
        $customer->line_of_business = $_POST['line_of_business'];

        $customer->ar_frieghtcarriers_hdr_id = $_POST['ar_frieghtcarriers_hdr_id'];
        $customer->reward_opening_point = $_POST['reward_opening_point'];
        $customer->ar_discount_hdr_id = $_POST['ar_discount_hdr_id'];

        $customer->company_additional_info = $_POST['company_additional_info'];
        $customer->reward_point = $_POST['reward_point'];
        $customer->maximum_credit = $_POST['maximum_credit'];
        $customer->credit_check = $_POST['credit_check'];
        $customer->default_bank = $_POST['default_bank'];
        $customer->account_structure_id = $_POST['account_structure_id'];

        $customer->frieghtterm_id = $_POST['frieghtterm_id'];
        $customer->delivery_terms_id = $_POST['delivery_terms_id'];
        $customer->created_by = $_POST['created_by'];
        $customer->active = $_POST['active'];
        $customer->tds_applicable = $_POST['tds_applicable'];
        $customer->tds_percentage = $_POST['tds_percentage'];
        $customer->tds_account_id = $_POST['tds_account_id'];

        $customer->tcs_applicable = $_POST['tcs_applicable'];
        $customer->tcs_percentage = $_POST['tcs_percentage'];
        $customer->tcs_account_id = $_POST['tcs_account_id'];


        $customer->company_id = $compy;
        $customer->location_id = $loc;
        $customer->organization_id = $org;
        //dd($_POST['status']);
        if ($_POST['status'] == "QUICKCUSTOMER")
            $customer_type_c = $_POST['status'];
        else
            $customer_type_c = $_POST['status'];
        //dd($customer);
        $id = $this->insertData($this->modelname, $primary, $customer, $_POST['customer_id'], $customer_type_c);

        if (($_POST['customer_id'] != "0") || ($_POST['customer_id'] != "")) {
            $sql = \DB::table('m_customers_t')->where('customer_id', $_POST['customer_id'])->update(['last_updated_by' => $upid]);

        }

        $oldid = \DB::table('m_customer_sites_t')->where('customer_id', $id)->get();

        if ($oldid->isEmpty()) {

            for ($i = 0; $i < count($_POST['counter']); $i++) {


                $data['customer_id'] = $id;
                $data['customer_site_id'] = $_POST['bulk_customer_site_id'][$i] ? $_POST['bulk_customer_site_id'][$i] : 0;
                $data['customer_site_number'] = $_POST['bulk_customer_site_number'][$i];
                $data['customer_site_name'] = $_POST['bulk_customer_site_name'][$i];
                $data['copy_site_row'] = $_POST['copy_site_row_h'][$i];
                $data['site_type'] = $_POST['bulk_site_type'][$i];
                $data['address'] = $_POST['bulk_address'][$i];
                $data['country'] = $_POST['bulk_country'][$i];
                $data['state'] = $_POST['bulk_state'][$i];
                $data['city'] = $_POST['bulk_city'][$i];
                $data['gst_no'] = $_POST['bulk_gst_no'][$i];
                $data['tan_no'] = $_POST['bulk_tan_no'][$i];
                $data['pincode'] = $_POST['bulk_pincode'][$i];
                $data['contact_person'] = $_POST['bulk_contact_person'][$i];
                $data['contact_number'] = $_POST['bulk_contact_number'][$i];
                $data['contact_mail'] = $_POST['bulk_contact_mail'][$i];
                $data['primary_address'] = $_POST['bulk_primary_address'][$i];
                $data['active'] = $_POST['bulk_active'][$i];
                $data['company_id'] = $compy;
                $data['location_id'] = $loc;
                $data['organization_id'] = $org;


                \DB::table('m_customer_sites_t')->insert($data);

            }


            $action = "Create";
            $this->auditlog($id, "Customer", $action, $_POST, "m_customers_t");
            return response()->json(array('status' => 'success', 'message' => 'Customers Saved Successfully', 'id' => $id));
        } else {

            $existingId = array();
            foreach ($oldid as $key => $value) {
                $oldIds[] = $value->$primaryline;
            }

            foreach ($_POST['bulk_' . $primaryline] as $val) {
                $newIds[] = $val;
            }

            $existingId = array_replace($newIds, $oldIds);

            $oldcount = count($oldIds);
            $newcount = count($newIds);

            if ($oldcount <= $newcount) {
                for ($i = 0; $i < $newcount; $i++) {

                    $data['customer_id'] = $id;
                    $data['customer_site_id'] = $_POST['bulk_customer_site_id'][$i] ? $_POST['bulk_customer_site_id'][$i] : 0;

                    $data['customer_site_number'] = $_POST['bulk_customer_site_number'][$i];
                    $data['customer_site_name'] = $_POST['bulk_customer_site_name'][$i];
                    $data['copy_site_row'] = $_POST['copy_site_row_h'][$i];
                    $data['site_type'] = $_POST['bulk_site_type'][$i];
                    $data['address'] = $_POST['bulk_address'][$i];
                    $data['country'] = $_POST['bulk_country'][$i];
                    $data['state'] = $_POST['bulk_state'][$i];
                    $data['city'] = $_POST['bulk_city'][$i];
                    $data['gst_no'] = $_POST['bulk_gst_no'][$i];
                    $data['tan_no'] = $_POST['bulk_tan_no'][$i];
                    $data['pincode'] = $_POST['bulk_pincode'][$i];
                    $data['contact_person'] = $_POST['bulk_contact_person'][$i];
                    $data['contact_number'] = $_POST['bulk_contact_number'][$i];
                    $data['contact_mail'] = $_POST['bulk_contact_mail'][$i];
                    $data['primary_address'] = $_POST['bulk_primary_address'][$i];
                    $data['active'] = $_POST['bulk_active'][$i];
                    $data['company_id'] = $compy;
                    $data['location_id'] = $loc;
                    $data['organization_id'] = $org;
                    // dd($data);
                    if ($data['customer_site_id'] = $existingId[$i]) {
                        $action = "Edit";
                        $this->auditlog($id, "Customer", $action, $_POST, "m_customers_t");
                        $this->modelline::find($data['customer_site_id'])->update($data);

                    } else {
                        $action = "Create";
                        $this->auditlog($id, "Customer", $action, $_POST, "m_customers_t");
                        \DB::table('m_customer_sites_t')->insert($data);

                    }

                }

            } else {

                $arraydiff = array_diff($oldIds, $newIds);

                foreach ($arraydiff as $key) {
                    if (($key = array_search($key, $oldIds)) !== false) {
                        unset($oldIds[$key]);
                    }
                }
                foreach ($arraydiff as $val) {
                    \DB::table('m_customer_sites_t')->where('customer_site_id', $val)->delete();
                }

                for ($i = 0; $i < count($oldIds); $i++) {
                    $data['customer_id'] = $id;
                    $data['customer_site_id'] = $_POST['customer_site_id'][$i] ? $_POST['customer_site_id'][$i] : 0;
                    $data['customer_site_number'] = $_POST['bulk_customer_site_number'][$i];
                    $data['customer_site_name'] = $_POST['bulk_customer_site_name'][$i];
                    $data['site_type'] = $_POST['bulk_site_type'][$i];
                    $data['copy_site_row'] = $_POST['copy_site_row_h'][$i];
                    $data['address'] = $_POST['bulk_address'][$i];
                    $data['country'] = $_POST['bulk_country'][$i];
                    $data['state'] = $_POST['bulk_state'][$i];
                    $data['city'] = $_POST['bulk_city'][$i];
                    $data['gst_no'] = $_POST['bulk_gst_no'][$i];
                    $data['tan_no'] = $_POST['bulk_tan_no'][$i];
                    $data['pincode'] = $_POST['bulk_pincode'][$i];
                    $data['contact_person'] = $_POST['bulk_contact_person'][$i];
                    $data['contact_number'] = $_POST['bulk_contact_number'][$i];
                    $data['contact_mail'] = $_POST['bulk_contact_mail'][$i];
                    $data['primary_address'] = $_POST['bulk_primary_address'][$i];
                    $data['active'] = $_POST['bulk_active'][$i];

                    $this->modelline::find($data['customer_site_id'])->update($data);
                }
            }
        }

        if ($_POST['savestatus'] == 'INITIATED') {
            $dat = \DB::select("SELECT m_customers_t.customer_number, m_customers_t.customer_name,m_customers_t.savestatus, m_customer_types_t.customer_type,i_pricelist_hdr_t.pricelist_name,f_account_structure_t.concatenated_segments,tb_users.first_name, tb_users.email FROM m_customers_t LEFT JOIN m_customer_types_t ON m_customers_t.customer_type_id = m_customer_types_t.customer_type_id LEFT JOIN i_pricelist_hdr_t ON m_customers_t.pricelist_id = i_pricelist_hdr_t.pricelist_hdr_id LEFT JOIN f_account_structure_t ON m_customers_t.account_structure_id = f_account_structure_t.f_account_structure_id LEFT JOIN tb_users ON m_customers_t.created_by = tb_users.id WHERE m_customers_t.customer_id='" . $id . "'");

            $uid = \Session::get('id');
            $from_user = \DB::select("select first_name, email from tb_users where id= $uid");

            $pur['customer_number'] = $dat[0]->customer_number;
            $pur['customer_name'] = $dat[0]->customer_name;
            $pur['customer_type'] = $dat[0]->customer_type;
            $pur['pricelist_name'] = $dat[0]->pricelist_name;
            $pur['concatenated_segments'] = $dat[0]->concatenated_segments;
            $pur['user_clear'] = $from_user[0]->first_name;
            $pur['savestatus'] = $dat[0]->savestatus;

            $po_num_sub = $dat[0]->customer_name;
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

                \Mail::send('customers.mail', $pur, function ($message) use ($to_mail_id) {
                    //dd($to_mail_id);    
                    $message->to($to_mail_id);
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

                    $message->subject($po_num_sub . " - CUSTOMER INITIATED");
                });
            }

        } else if ($_POST['savestatus'] == 'SAVE') {

            $dat = \DB::select("SELECT m_customers_t.customer_number, m_customers_t.customer_name,m_customers_t.savestatus, m_customer_types_t.customer_type,i_pricelist_hdr_t.pricelist_name,f_account_structure_t.concatenated_segments,tb_users.first_name, tb_users.email FROM m_customers_t LEFT JOIN m_customer_types_t ON m_customers_t.customer_type_id = m_customer_types_t.customer_type_id LEFT JOIN i_pricelist_hdr_t ON m_customers_t.pricelist_id = i_pricelist_hdr_t.pricelist_hdr_id LEFT JOIN f_account_structure_t ON m_customers_t.account_structure_id = f_account_structure_t.f_account_structure_id LEFT JOIN tb_users ON m_customers_t.last_updated_by = tb_users.id WHERE m_customers_t.customer_id='" . $id . "'");

            $to_mail = \DB::select("SELECT * FROM m_customers_t LEFT JOIN tb_users ON m_customers_t.created_by = tb_users.id WHERE m_customers_t.customer_id='" . $id . "'");

            $pur['customer_number'] = $dat[0]->customer_number;
            $pur['customer_name'] = $dat[0]->customer_name;
            $pur['customer_type'] = $dat[0]->customer_type;
            $pur['pricelist_name'] = $dat[0]->pricelist_name;
            $pur['concatenated_segments'] = $dat[0]->concatenated_segments;
            $pur['user_clear'] = $dat[0]->first_name;
            $pur['savestatus'] = $dat[0]->savestatus;

            $po_num_sub = $dat[0]->customer_name;
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

                \Mail::send('customers.mail', $pur, function ($message) use ($to_mail_id) {
                    $message->to($to_mail_id);
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

                    $message->subject($po_num_sub . " - CUSTOMER APPROVED");
                });
            }
        }

        $table = \DB::table('m_customers_t')->get();
        $this->data['datas'] = json_encode($table);

        /** Auditlog **/
        $action = "Edit";
        $this->auditlog($id, "Customer", $action, $_POST, "m_customers_t");

        return response()->json(array('status' => 'success', 'message' => 'Customer Updated Successfully!!!', 'id' => $id));
    }

    /*  Edit data  */
    public function edit(Request $request, $id)
    {
        $this->data['pageModule'] = "customers";

        $this->data = array('pageModule' => 'customers', 'pageUrl' => url('customers'));
        //dd($this->data);
        $this->data['id'] = $id;

        $table = \DB::table('m_customers_t')->where('customer_id', $id)->get();


        $this->data['row'] = $table[0];
        //dd($this->data['row']);
        $this->data['tcs_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $table[0]->tcs_account_id);
        $this->data['tcs_percentage'] = $this->jCombo('f_tcs_slab_t', 'tcs_percentage', 'tcs_percentage', $table[0]->tcs_percentage);
        $tablelines = \DB::table('m_customer_sites_t')->where('customer_id', $id)->get(); //dd($id);
        $custno = $this->data['row1'] = $table[0]->customer_number;

        if ($table[0]->pf_registration_no != "") {
            $this->data['row']->pf_registration_no = $table[0]->pf_registration_no;
            dd();
        } else {
            $this->data['row']->pf_registration_no = "";
        }
        if (count($tablelines) > 0) {
            $this->data['country_id'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name', $tablelines[0]->country);
            $this->data['state_id'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', $tablelines[0]->state);
            $this->data['city_id'] = $this->jCombologin('m_cities_t', 'city_id', 'city_name', $tablelines[0]->city);
        } else {
            $this->data['country_id'] = $this->jCombologin('m_countries_t', 'country_id', 'country_name', '');
            $this->data['state_id'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', '');
            $this->data['city_id'] = $this->jCombologin('m_cities_t', 'city_id', 'city_name', '');
        }

        //dd($this->data);
        // dd($tablelines);
        $this->data['linedata'] = $tablelines;
        $this->data['pageMethod'] = \Request::route()->getName();

        if ($this->data['pageMethod'] == "customersapprovalcreate") {
            $this->data['pageMethod'] = "customersapproval";

        } else {
            $this->data['pageMethod'] = "customers";

        }
        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'customers')->get();
        $this->data['customer_type_id'] = $this->jCombo('m_customer_types_t', 'customer_type_id', 'customer_type', $table[0]->customer_type_id);
        $this->data['sales_person'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]->sales_person);
        $this->data['default_bank'] = $this->jCombo('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', $table[0]->default_bank);
        $this->data['pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t', 'pricelist_hdr_id', 'pricelist_name', $table[0]->pricelist_id, ' and price_list_type="Sales"');
        $this->data['schemes'] = $this->jcustomselect('s_schemes_hdr_t', 'schemes_hdr_id', 'schemes_name', $table[0]->schemes, ' and active="Yes" and savestatus="APPROVED"');
        $this->data['account_structure_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $table[0]->account_structure_id);
        $this->data['tds_percentage'] = $this->jCombo('f_tds_slab_t', 'tds_slab_id', 'tds_percentage', $table[0]->tds_percentage);
        $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $table[0]->tds_account_id);
        $this->data['default_payment_terms_id'] = $this->jcustomselect('m_payment_terms_t', 'payment_term_id', 'payment_term_name', $table[0]->default_payment_terms_id, '');
        $this->data['default_payment_method_id'] = $this->jcustomselect('m_payment_methods_t', 'payment_method_id', 'payment_method_name', $table[0]->default_payment_method_id, '');
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);

        $this->data['delivery_terms_id'] = $this->jcustomselect('m_delivery_terms_t', 'delivery_terms_id', 'delivery_term_name', $table[0]->delivery_terms_id, ' and source_type_id="Sales"');
        $this->data['frieghtterm_id'] = $this->jcustomselect('m_frieghtterms_t', 'frieghtterm_id', 'fob_point_name', $table[0]->frieghtterm_id, ' and source_type_id="Sales"');
        $this->data['ar_discount_hdr_id'] = $this->jCombo('m_discounts_hdr_t', 'ar_discount_hdr_id', 'discount_name', $table[0]->ar_discount_hdr_id);
        $this->data['ar_frieghtcarriers_hdr_id'] = $this->jcustomselect('m_frieghtcarriers_hdr_t', 'ar_frieghtcarriers_hdr_id', 'carrier_name', $table[0]->ar_frieghtcarriers_hdr_id, ' and source_type_id="Sales"');
        $this->data['customer_category'] = $this->jcustomselecttool('a_lookuplines_t', 'lookuplines_id', 'lookup_meaning', $table[0]->customer_category, "and lookup_type='CUSTOMER_CATEGORY'");
        $this->data['credit_check'] = $this->jcustomselecttool('a_lookuplines_t', 'lookuplines_id', 'lookup_meaning', $table[0]->credit_check, "and lookup_type='CREDIT_CHECK'");
        // dd($this->data['linedata']);
        // dd($this->data);
        foreach ($this->data['linedata'] as $key => $value) {
            $a = sprintf("%03d ", ($key + 1));
            if ($custno != "") {
                $seqno = $custno . '/CS' . $a;
            } else {
                $seqno = '';
            }

            $this->data['linedata'][$key] = (object) array();
            $this->data['linedata'][$key]->customer_site_number = $seqno;
            $this->data['linedata'][$key]->customer_site_name = $value->customer_site_name;
            $this->data['linedata'][$key]->site_type = $value->site_type;
            $this->data['linedata'][$key]->address = $value->address;
            $this->data['linedata'][$key]->pincode = $value->pincode;
            $this->data['linedata'][$key]->gst_no = $value->gst_no;
            $this->data['linedata'][$key]->tan_no = $value->tan_no;
            $this->data['linedata'][$key]->contact_number = $value->contact_number;
            $this->data['linedata'][$key]->contact_person = $value->contact_person;
            $this->data['linedata'][$key]->contact_mail = $value->contact_mail;
            $this->data['linedata'][$key]->customer_site_id = $value->customer_site_id;
            $this->data['linedata'][$key]->primary_address = $value->primary_address;
            $this->data['linedata'][$key]->active = $value->active;
            $this->data['linedata'][$key]->copy_site_row_h = $value->copy_site_row;
            //dd($this->data['linedata'][$key]->bulk_primary_address);
        }

        $overduetable = \DB::table('over_due_t')->where('customer_id', $id)->get();
        $this->data['lineoverdue'] = $overduetable;
        $this->data['url'] = \Request::route()->getName();

        // dd($this->data);
        return view('customers.form', $this->data);
    }
    /* End Edit data  */

    /* Start Delete data  Function */
    public function delete(Request $request, $id = null)
    {

        $column = array('ship_to_customer_id', 'customerid', 'customerid', 'ship_to_customer_id', 'ship_to_customer_id', 'ship_to_customer_id');

        $table = array('s_salesorder_hdr_t', 's_inquiry_hdr_t', 's_quote_hdr_t', 's_invoice_hdr_t', 's_pickrelease_hdr_t', 's_dispatch_hdr_t');


        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $id)->get();
            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }

        if ($j == 0) {
            Customers::destroy($id);
            $query = \DB::table('m_customer_sites_t')->where('customer_id', $id)->delete();
        }
        /**Auditlog**/
        $action = "Delete";
        $this->auditlog($id, "Customers", $action, $id, "m_customers_t");
        return $j;
    }
    /* end Delete data  Function */

    /* Start Edit data  Function */
    public function getedit($edit_id)
    {
        $column = array('ship_to_customer_id', 'customerid', 'customerid', 'ship_to_customer_id', 'ship_to_customer_id', 'ship_to_customer_id');

        $table = array('s_salesorder_hdr_t', 's_inquiry_hdr_t', 's_quote_hdr_t', 's_invoice_hdr_t', 's_pickrelease_hdr_t', 's_dispatch_hdr_t');
        //dd($table);
        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $edit_id)->get();
            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }

        return $j;
    }
    /* End Edit data Function */

    /* Start View data  Function */
    public function show(customers $customers, $id = null)
    {
        if (isset($id)) {
            //$this->data['pageModule'] ='customers';
//$this->data['pageUrl'] =url('customers');
            $customer = DB::table('m_customers_t')->leftjoin('m_customer_types_t', 'm_customer_types_t.customer_type_id', '=', 'm_customers_t.customer_type_id')->where('customer_id', $id)->get();

            if ($customer[0]->overdue == "Yes") {
                $over = \DB::table('over_due_t')->where('customer_id', $id)->get();
                $this->data['overdata'] = $over;
            }
            $this->data['customer_number'] = $customer[0]->customer_number;
            $this->data['tds_applicable'] = $customer[0]->tds_applicable;
            $this->data['tcs_applicable'] = $customer[0]->tcs_applicable;
            $this->data['tds_percentage'] = $customer[0]->tds_percentage;
            $this->data['tcs_percentage'] = $customer[0]->tcs_percentage;
            $this->data['customer_name'] = $customer[0]->customer_name;
            $this->data['alternate_name'] = $customer[0]->alternate_name;
            $this->data['customer_type_id'] = $customer[0]->customer_type;
            $this->data['billing_address'] = $customer[0]->billing_address;
            $this->data['line_of_business'] = $customer[0]->line_of_business;
            $this->data['reward_opening_point'] = $customer[0]->reward_opening_point;
            $this->data['credit_limit'] = $customer[0]->credit_limit;
            $this->data['available_credit'] = $customer[0]->available_credit;
            $this->data['pan_no'] = $customer[0]->pan_no;
            $this->data['active'] = $customer[0]->active;
            $this->data['ar_discount_hdr_id'] = $this->idname("discount_name", "m_discounts_hdr_t", "ar_discount_hdr_id", $customer[0]->ar_discount_hdr_id);
            $this->data['delivery_terms_id'] = $this->idname("delivery_term_name", "m_delivery_terms_t", "delivery_terms_id", $customer[0]->delivery_terms_id);
            $this->data['frieghtterm_id'] = $this->idname("fob_point_name", "m_frieghtterms_t", "frieghtterm_id", $customer[0]->frieghtterm_id);

            $this->data['company_additional_info'] = $customer[0]->company_additional_info;
            $this->data['reward_point'] = $customer[0]->reward_point;
            $this->data['maximum_credit'] = $customer[0]->maximum_credit;
            $this->data['credit_check'] = $this->idname("lookup_code", "a_lookuplines_t", "lookuplines_id", $customer[0]->credit_check);
            $this->data['default_bank'] = $this->idname("bank_name", "f_bank_account_hdr_t", "bank_account_hdr_id", $customer[0]->default_bank);
            $this->data['tds_account_id'] = $this->idname("concatenated_segments", "f_account_structure_t", "f_account_structure_id", $customer[0]->tds_account_id);
            $this->data['tcs_account_id'] = $this->idname("concatenated_segments", "f_account_structure_t", "f_account_structure_id", $customer[0]->tcs_account_id);
            $this->data['overdue'] = $customer[0]->overdue;
            $this->data['gst_no'] = $customer[0]->pan_no;
            $this->data['ar_frieghtcarriers_hdr_id'] = $this->idname("carrier_name", "m_frieghtcarriers_hdr_t", "ar_frieghtcarriers_hdr_id", $customer[0]->ar_frieghtcarriers_hdr_id);
            $this->data['pricelist_id'] = $this->idname("pricelist_name", "i_pricelist_hdr_t", "pricelist_hdr_id", $customer[0]->pricelist_id);
            $this->data['default_payment_terms_id'] = $this->idname("payment_term_name", "m_payment_terms_t", "payment_term_id", $customer[0]->default_payment_terms_id);
            $this->data['created_by'] = $this->idname("username", "tb_users", "id", $customer[0]->created_by);
            $this->data['default_payment_method_id'] = $this->idname("payment_method_name", "m_payment_methods_t", "payment_method_id", $customer[0]->default_payment_method_id);

            $this->data['sales_person'] = $this->idname("first_name", "hr_employee_t", "employee_number|employee_id", $customer[0]->sales_person);
            $this->data['customer_category'] = $this->idname('lookup_meaning', 'a_lookuplines_t', 'lookuplines_id', $customer[0]->customer_category);
            $this->data['contact_person'] = $customer[0]->contact_person;
            $this->data['contact_number'] = $customer[0]->contact_number;
            $vlinesdata = \DB::table('m_customer_sites_t')->where('m_customer_sites_t.customer_id', $id)->get();
            $this->data['vlinesdata'] = $vlinesdata;

            $conatct = [];

            if (count($vlinesdata) > 0) {
                foreach ($vlinesdata as $k => $v) {

                    $contact_name = explode(",", $v->contact_person);
                    $contact_no = explode(",", $v->contact_number);
                    //dd($contact_no);
                    $contact_mail = explode(",", $v->contact_mail);

                    foreach ($contact_name as $k1 => $v1) {

                        if (strlen($v1) == 1) {
                            $c_name = '';
                        } else {

                            $c_name = $v1;
                        }
                        //dd($contact_no[$k1]);
                        if (strlen($contact_no[$k1]) == 0) {
                            $c_no = '';
                        } else {
                            $c_no = $contact_no[$k1];
                        }
                        if (strlen($contact_mail[$k1]) == 0) {
                            $c_mail = '';
                        } else {
                            $c_mail = $contact_mail[$k1];
                        }
                        $contact[$k][$k1] = $c_name . " " . $c_no . " " . $c_mail;

                    }

                }
            }
            $this->data['contact'] = $contact;
            $country = \DB::table('m_countries_t')->where('country_id', $vlinesdata[0]->country)->get();
            if (count($country) > 0) {
                $this->data['country'] = $country[0]->country_name;
            } else {
                $this->data['country'] = "";
            }

            $state = \DB::table('m_states_t')->where('state_id', $vlinesdata[0]->state)->get();
            if (count($state) > 0) {
                $this->data['state'] = $state[0]->state_name;
            } else {
                $this->data['state'] = "";
            }

            $city = \DB::table('m_cities_t')->where('city_id', $vlinesdata[0]->city)->get();
            if (count($city) > 0) {
                $this->data['city'] = $city[0]->city_name;
            } else {
                $this->data['city'] = "";
            }


            $this->data['return_url'] = $_GET['return'];
            return view('customers.view', $this->data);
        }

    }/* End View data  Function */


    public function cusschmesedit()
    {

        $sql = \DB::select("select schemes from m_customers_t where customer_id='" . $_GET['id'] . "'");
        if (isset($sql)) {
            $data['schemes'] = $sql[0]->schemes;
            $data['update'] = 'update';

        }
        return $data;

    }

    public function cusschemesupdate()
    {
        $schemes = $_GET['schemes'];
        $check = \DB::update("update m_customers_t set schemes='" . $_GET['schemes'] . "' where customer_id='" . $_GET['id'] . "'");

        if ($check) {
            return 1;
        } else {
            return 0;
        }
    }



    /* Start Customer Site Function */
    function siteno()
    {
        $result = \DB::select("SELECT COUNT(customer_site_id) AS count FROM m_customer_sites_t ");
        return ($result[0]->count);
    }

    function findPrimarykey($table)
    {
        $primaryKey = '';
        foreach (\DB::select("show columns from " . $table . " where extra like '%auto_increment%'") as $key) {
            $primaryKey = $key->Field;
        }
        return $primaryKey;
    }

    public function getstatecode($state)
    {
        $result = \DB::select("SELECT  state_code_no FROM m_states_t where state_id ='" . $state . "'");
        return $result[0]->state_code_no;
    }
    public function gstrequired($customertype)
    {
        $result = \DB::select("SELECT  gst_required FROM m_customer_types_t where customer_type_id ='" . $customertype . "'");
        return $result[0]->gst_required;
    }
}
