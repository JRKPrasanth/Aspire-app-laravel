<?php
namespace App\Http\Controllers;

use App\Workorder;
use App\Workorderlines;
use App\Soorder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Validator, DB;
use Config;
use session;
use Yajra\DataTables\DataTables;


class WorkorderController extends Controller
{

    /* purpose: construct function to set values throughout the controller like model,submodel,pagemethod*/
    public $module = "workorder";
    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->model = new Workorder();
        $this->submodel = new Workorderlines();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageModule'] = 'workorder';
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->table = " w_workorder_hdr_t";
        $this->subtable = "w_workorder_lines_t";
        $this->middleware('auth');
    }


    /* purpose:index function to redirect table blade*/
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
        return view('workorder.table', $this->data);
    }
    /*end*/
    /*deepika purpose:index function to redirect sotable blade*/
    public function soindex(Request $request)
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

        return view('workorder.sotable', $this->data);
    }
    /*end*/

    /* purpose:index function to redirect workorder status table blade*/
    public function woindex(Request $request)
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

        return view('workorder.wostatustable', $this->data);
    }


    /* purpose:to load workorder details */

    public function getWorkorderDatas(Workorder $workorder)
    {
		
        $wh = '';
        $loc = "1";
        $compy = \Session::get('companyid');
        $org = \Session::get('organization');
        $groupname = \Session::get('groupname');

        $wh .= $grid_data = $this->grid_check('w_workorder_hdr_t', 'workorder_date');

        $sql = "SELECT * from w_workorder_hdr_t left join tb_users on(tb_users.id=w_workorder_hdr_t.created_by)  where 1=1  $wh order by w_workorder_hdr_t.workorder_hdr_id DESC";

        $result = \DB::select($sql);

        return DataTables::of($result)->make(true);

    }


    /* purpose:to load sales order details */
    public function sogriddata()
    {
        $wh = '';
        $app_id = \Session::get('id');
        $wh .= " and ( s_salesorder_lines_t.workorder_status != 1 and s_salesorder_hdr_t.approver_id = 0 and s_salesorder_hdr_t.order_status_id='INITIATED') or (s_salesorder_lines_t.workorder_status != 1 and s_salesorder_hdr_t.approver_id =" . $app_id . " and s_salesorder_hdr_t.order_status_id='APPROVED')";
        if ($_GET['_search'] == 'true') {
            $table = array('m_products_t', 's_salesorder_hdr_t', 'm_customers_t');

            $wh .= $this->jqgridsearch('s_salesorder_lines_t', $_GET['filters'], $table);
        }
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $org = \Session::get('organization');
        $groupname = \Session::get('groupname');
        // if($groupname=='Superadmin' || $groupname=='Admin'){
        // $wh.='and  s_salesorder_lines_t.company_id='.$compy;  
        // }else{
        //     $wh.='and  s_salesorder_lines_t.company_id='.$compy.' and s_salesorder_lines_t.location_id='.$loc;      
        // }   
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if (!$sidx)
            $sidx = 1;
        $wh .= $grid_data = $this->grid_check('s_salesorder_hdr_t', 'sales_order_date');
        $result = \DB::select("SELECT COUNT(s_salesorder_lines_t.sales_line_id) AS count FROM s_salesorder_lines_t left join s_salesorder_hdr_t on(s_salesorder_hdr_t.sales_hdr_id = s_salesorder_lines_t.sales_hdr_id) LEFT JOIN m_products_t ON m_products_t.product_id = s_salesorder_lines_t.`product_id` LEFT JOIN m_customers_t ON m_customers_t.customer_id = s_salesorder_hdr_t.`ship_to_customer_id` where  1=1 AND s_salesorder_hdr_t.order_status_id='APPROVED' $wh");
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
    s_salesorder_lines_t.sales_line_id,
    s_salesorder_lines_t.sales_hdr_id,
    s_salesorder_lines_t.product_id,
    m_products_t.concatenated_product,
    m_products_t.product_code,
    s_salesorder_lines_t.qty,
    SUM(
        i_qoh_detail_t.qoh_trx_qty - i_reservation_detail_t.reserv_trx_qty
    ) AS qoh_qty,
    s_salesorder_hdr_t.`sales_order_no`,
    s_salesorder_hdr_t.`sales_order_date`,
    m_customers_t.customer_name,
    s_salesorder_lines_t.`delivery_date`
FROM
    s_salesorder_lines_t
LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = s_salesorder_lines_t.product_id
LEFT JOIN i_reservation_detail_t ON i_reservation_detail_t.product_id = s_salesorder_lines_t.product_id
LEFT JOIN m_products_t ON m_products_t.product_id = s_salesorder_lines_t.`product_id`
LEFT JOIN s_salesorder_hdr_t ON s_salesorder_lines_t.sales_hdr_id = s_salesorder_hdr_t.`sales_hdr_id`
LEFT JOIN m_customers_t ON m_customers_t.customer_id = s_salesorder_hdr_t.`ship_to_customer_id`
WHERE 1=1 $wh
GROUP BY
    s_salesorder_lines_t.sales_line_id ORDER BY $sidx $sord LIMIT $start , $limit";

        $download_SQL = "SELECT
    s_salesorder_lines_t.sales_line_id,
    s_salesorder_lines_t.sales_hdr_id,
    s_salesorder_lines_t.product_id,
    m_products_t.concatenated_product,
    m_products_t.product_code,
    s_salesorder_lines_t.qty,
    SUM(
        i_qoh_detail_t.qoh_trx_qty - i_reservation_detail_t.reserv_trx_qty
    ) AS qoh_qty,
    s_salesorder_hdr_t.`sales_order_no`,
    s_salesorder_hdr_t.`sales_order_date`,
    m_customers_t.customer_name,
    s_salesorder_lines_t.`delivery_date`
FROM
    s_salesorder_lines_t
LEFT JOIN i_qoh_detail_t ON i_qoh_detail_t.product_id = s_salesorder_lines_t.product_id
LEFT JOIN i_reservation_detail_t ON i_reservation_detail_t.product_id = s_salesorder_lines_t.product_id
LEFT JOIN m_products_t ON m_products_t.product_id = s_salesorder_lines_t.`product_id`
LEFT JOIN s_salesorder_hdr_t ON s_salesorder_lines_t.sales_hdr_id = s_salesorder_hdr_t.`sales_hdr_id`
LEFT JOIN m_customers_t ON m_customers_t.customer_id = s_salesorder_hdr_t.`ship_to_customer_id`
WHERE 1=1 $wh
GROUP BY
    s_salesorder_lines_t.sales_line_id ORDER BY $sidx $sord";





        $result1 = \DB::select($download_SQL);
        $result1 = collect($result1)->map(function ($x) {
            return (array) $x;
        })->toArray();

        if (isset($_GET['download'])) {
            return $result1;
        }

        $result = DB::select($SQL);
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);

    }
    /*end*/

    /* purpose:workorder status*/
    public function getWorkorderstatusData(Workorder $workorder)
    {
        $wh = '';


        $loc = "1";
        $compy = \Session::get('companyid');
        $org = \Session::get('organization');
        $groupname = \Session::get('groupname');


        $sql = "SELECT w_workorder_hdr_t.workorder_hdr_id,w_workorder_hdr_t.workorder_no,w_workorder_hdr_t.workorder_no,w_workorder_hdr_t.workorder_date,w_workorder_lines_t.workorder_line_id,m_products_t.concatenated_product, m_products_t.product_code,w_productionplan_hdr_t.production_qty,w_productionplan_hdr_t.plan_qty,w_productionplan_hdr_t.pending_qty,w_workorder_lines_t.qty,w_productionplan_hdr_t.plan_no from w_workorder_hdr_t left join w_workorder_lines_t on(w_workorder_lines_t.workorder_hdr_id=w_workorder_hdr_t.workorder_hdr_id) left join w_productionplan_hdr_t on(w_productionplan_hdr_t.reference_id=w_workorder_hdr_t.workorder_hdr_id) left join m_products_t on(m_products_t.product_id=w_workorder_lines_t.product_id) where 1=1 $wh group by w_workorder_lines_t.workorder_line_id order by w_workorder_hdr_t.workorder_hdr_id DESC";

        $result = \DB::select($sql);
		
        return DataTables::of($result)->make(true);

    }


    /* purpose: to create new workorder */
    public function create($id = null)
    {
        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'workorder')->get();
        /*deepika purpose:to create workorder from sales order*/
        if (isset($_GET['source'])) {
            if ($_GET['source'] == "SALESORDER") {
                $salesorder = Soorder::find($id);
                $this->modelname = new Workorder();
                $this->data['row'] = (object) array();
                $this->data['pagemode'] = "edit";
                $table = $this->modelname->getTableColumns();
                foreach ($table as $key => $val) {
                    $this->data['row']->$val = '';
                }
                $this->data['row']->source = "SALESORDER";
                $sesdate = \Session::get('p_date_format');
                $this->data['row']->workorder_date = date($sesdate);
                $sql = \DB::select("select sales_order_no,sales_hdr_id from s_salesorder_hdr_t where sales_hdr_id in ($id)");
                $sono = "";
                $soid = "";
                foreach ($sql as $key => $value) {
                    $soid .= $value->sales_hdr_id . ",";
                    $sono .= $value->sales_order_no . ",";
                }
                $soorderno = rtrim($sono, ',');
                $salesid = rtrim($soid, ',');
                $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
                $this->data['row']->so_reference_number = $soorderno;
                $this->data['row']->reference_id = $salesid;
                $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));
                $prd = $_GET['prdid'];
                $slid = $_GET['slid'];
                $this->data['productid'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '', 'workorder');
                $tablelines = \DB::select("select sl.sales_hdr_id,sl.sales_line_id,sl.product_id,sum(sl.qty) as qty, sl.uom_code_id,sl.delivery_date from s_salesorder_lines_t sl WHERE  sl.sales_hdr_id in($id) and sl.sales_line_id in($slid) and sl.product_id in($prd) group by sl.product_id,sl.delivery_date");
                $this->data['linedata'] = $tablelines;
                foreach ($this->data['linedata'] as $key => $value) {
                    $this->data['linedata'][$key]->line_no = $key + 1;
                    $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                    $this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                    $this->data['linedata'][$key]->reference_line_id = $value->sales_line_id;
                    $this->data['linedata'][$key]->workorder_hdr_id = "";
                    $this->data['linedata'][$key]->workorder_line_id = "";
                    $decimal = \Session::get("decimal");
                    $this->data['linedata'][$key]->qty = round($value->qty, $decimal);
                    $sesdate = \Session::get('p_date_format');
                    $this->data['linedata'][$key]->due_date = date($sesdate, strtotime($value->delivery_date));
                    $this->data['linedata'][$key]->comments = '';
                }
            }
            if ($_GET['source'] == "SALESORDER") {
                $this->data['pageModule'] = 'workorderfromso';
            }
        }/*end*//*deepika purpose:to create workorder directly*/ else if ($id == 0) {
            $this->modelname = new Workorder();
            $this->data['row'] = (object) array();

            $table = $this->modelname->getTableColumns();
            foreach ($table as $key => $val) {
                $this->data['row']->$val = '';
            }
            $this->data['row']->shift = '';
            //dd($this->data['row']);
            $this->data['pagemode'] = "create";
            $this->data['row']->source = "STANDARD";
            $sesdate = \Session::get('p_date_format');
            $this->data['row']->workorder_date = date($sesdate);
            $this->data['id'] = '';
            $this->data['linedata'] = array();
            $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
            $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', \Session::get('organization'));

            $group = $this->groupname('FINISHED GOODS', 'group');
            $group1 = $this->groupname('SEMI FINISHED GOODS', 'group');

            $depart = \Session::get('groupname');
            $wh = '';
            if ($depart == "8") {
                $emp_id = \Session::get('emp_id');
                $prd_id = \DB::select("SELECT CONCAT(productmapping_lines_tbl.prd_id)as prd FROM `productmapping_hdr_tbl` join productmapping_lines_tbl on productmapping_lines_tbl.productmapping_id=productmapping_hdr_tbl.productmapping_id where productmapping_hdr_tbl.employee_id=$emp_id");
                //dd(json_encode($prd_id));
                $json_data = json_encode($prd_id);
                $decoded_array = json_decode($json_data, true); // Convert JSON to associative array
                $prd_values = array_column($decoded_array, 'prd'); // Extract only the "prd" values

                // Convert to a comma-separated string for MySQL IN condition
                $prd_list = implode(',', array_map('intval', $prd_values));

                $wh .= " and product_id in (" . $prd_list . ")";
            }
            if ($depart == "8") {
                $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'concatenated_product', '', "$wh");
            } else {
                $this->data['product_id'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '', 'workorder');
            }
            $this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', '');

        }/*end*/
        /* purpose:workorder edit workorder*/ else {
            $this->data['id'] = $id;
            $this->data['pagemode'] = "edit";
            $table = \DB::table('w_workorder_hdr_t')->where('workorder_hdr_id', $id)->get();
            $this->data['row'] = $table[0];
            $this->data['organization_id'] = $this->jCombo('m_organizations_t', 'organization_id', 'organization_name', $table[0]->organization_id);
            $this->data['productid'] = $this->jcustomproductselect('m_products_t', 'product_id', 'concatenated_product', '', 'workorder');
            $tablelines = \DB::table('w_workorder_lines_t')->where('workorder_hdr_id', $id)->get();
            $this->data['linedata'] = $tablelines;
            $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);

            if (count($this->data['linedata']) >= 1) {
                foreach ($this->data['linedata'] as $key => $value) {
                    $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'concatenated_product', $value->product_id, "and product_id=" . $value->product_id);
                    $this->data['linedata'][$key]->uomcode_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                }
            }


        }
        $this->data['prdcatopt'] = $this->jqgridselect('m_product_category_t', 'product_category_id', 'category_name');
        return view('workorder.form', $this->data);
    }

     public function save(Request $request){

		$product_id = implode(",", $_POST['bulk_product_id']);
		$qty        = implode(",", $_POST['bulk_qty']);
		$dd_date    = implode(",", $_POST['bulk_due_date']);


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
		 
        if ($_POST['workorder_no'] == "") {
            $seqno = $this->Seqno('WOR', 'w_workorder_hdr_t', '');
            $data['workorder_no'] = $seqno;
        } else {
            $seqno = $_POST['workorder_no'];
        }
		 
        \DB::beginTransaction();
        try {
            $id = $this->model->insertRow($data);
            $lid = $this->submodel->subgridSave($lines_data, $id);

            /*deepika purpose:audit log*/
            if ($_POST['workorder_hdr_id'] == "") {
                $action = "create";
            } else {
                $action = "update";
            }
            $this->auditlog($id, "workorder", $action, $data, "w_workorder_hdr_t");
            $prd_grp = \DB::select("select concatenated_product,product_group_id,product_subcategory_id from m_products_t where product_id =" . $product_id);
            $uom_code = \DB::table('m_uom_codes_t')->select('uom_code', 'uom_code_id', 'active')->where('uom_code_id', '=', $_POST['bulk_uom_code_id'])->get();
            $user_clear = \DB::table('hr_employee_t')->select('first_name AS full_name', 'email')->where('employee_id', '=', $_POST['created_by'])->get();
            $prd['workorder_no'] = $data['workorder_no'];
            $prd['workorder_date'] = $_POST['workorder_date'];
            $prd['product_name'] = $prd_grp[0]->concatenated_product;
            $prd['prd_grp'] = $prd_grp[0]->product_group_id;
            $prd['prd_sub_cat'] = $prd_grp[0]->product_subcategory_id;
            $prd['uom_code'] = $uom_code[0]->uom_code;
            $prd['quantity'] = $qty;
            $prd['wo_due_date'] = $dd_date;
            $prd['user_clear'] = $user_clear[0]->full_name;
            $wo_num_sub = $prd['workorder_no'];
            Session::put('wo_num_sub', $wo_num_sub);
            $wo_email = $user_clear[0]->email;
            Session::put('wo_email', $wo_email);
            $prd_name = $prd['product_name'];
            Session::put('prd_name', $prd_name);
            if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                Config::set('mail.username', \Session::get('user_email'));
                Config::set('mail.password', \Session::get('user_password'));
           
            }

            if ($prd['prd_grp'] == 1) {

                if (\Session::get('user_email') != '') {
                    //dd($prd);
                    $to_mail_id = "gayathri_rajagopal@jrkresearch.com";
                    \Mail::send('workorder.mail', $prd, function ($message) use ($to_mail_id) {
                        //dd($to_mail_id);    
                        $message->to($to_mail_id);
                        //   $message->to('gayathri_rajagopal@jrkresearch.com');
                        $message->cc('operations@jrkresearch.com');
                        $message->cc('uma_p@jrkresearch.com');
                        $message->cc('purchase_pm@jrkresearch.com');

                        if (!empty(Session::get('wo_email'))) {
                            $wo_email = Session::get('wo_email');
                        } else {
                            $wo_email = \Session::get('user_email');
                        }
                        $message->from($wo_email);
                        if (!empty(Session::get('wo_num_sub'))) {
                            $wo_num_sub = Session::get('wo_num_sub');
                        } else {
                            $wo_num_sub = " ";
                        }
                        if (!empty(Session::get('prd_name'))) {
                            $prd_name = Session::get('prd_name');
                        } else {
                            $prd_name = " ";
                        }

                        $message->subject($wo_num_sub . " - Operation Workorder details - " . $prd_name);
                    });
                }

            } else if ($prd['prd_sub_cat'] == 29 || $prd['prd_sub_cat'] == 30 || $prd['prd_sub_cat'] == 31 || $prd['prd_sub_cat'] == 50) {

                if (\Session::get('user_email') != '') {
                    //dd($prd);
                    $to_mail_id = "cheran_senguttuvan@jrkresearch.com";

                    \Mail::send('workorder.mail', $prd, function ($message) use ($to_mail_id) {
                        //dd($to_mail_id);    
                        $message->to($to_mail_id);
                        //    $message->to('cheran_senguttuvan@jrkresearch.com');
                        $message->to('drabbas@jrkresearch.com');
                        $message->to('aruna_v@jrkresearch.com');
                        $message->to('hemashree_k@jrkresearch.com');
                        $message->to('labs@jrkresearch.com');
                        $message->to('uma_p@jrkresearch.com');
                        $message->to('purchase_rm@jrkresearch.com');
                        $message->to('operations@jrkresearch.com');
                        $message->cc('gayathri_rajagopal@jrkresearch.com');
                        //$message->cc('jagadeesan_k@jrkresearch.com');
                        //$message->cc('aspire@jrkresearch.com');

                        if (!empty(Session::get('wo_email'))) {
                            $wo_email = Session::get('wo_email');
                        } else {
                            $wo_email = \Session::get('user_email');
                        }
                        $message->from($wo_email);
                        if (!empty(Session::get('wo_num_sub'))) {
                            $wo_num_sub = Session::get('wo_num_sub');
                        } else {
                            $wo_num_sub = " ";
                        }
                        if (!empty(Session::get('prd_name'))) {
                            $prd_name = Session::get('prd_name');
                        } else {
                            $prd_name = " ";
                        }

                        $message->subject($wo_num_sub . " - Production Workorder details - " . $prd_name);
                    });
                }

            }


            /*deepika purpose:to update workorder status in sales order when create workorder from salesorder*/
            $sohdrid = explode(",", $_POST['reference_id']);
            $salesline = \DB::table('s_salesorder_lines_t')->whereIn('sales_hdr_id', explode(",", $_POST['reference_id']))->whereIn('product_id', $_POST['bulk_product_id'])->update(['workorder_status' => '1']);
            $sid = $_POST['reference_id'];
            if ($sid != "") {
                $so = \DB::select("select * from s_salesorder_lines_t where workorder_status=1 and sales_hdr_id in($sid)");
                $socnt = \DB::select("select * from s_salesorder_lines_t where sales_hdr_id in($sid)");
                $so1 = count($so);
                $sohdrcunt = count($socnt);
                if ($so1 == $sohdrcunt) {
                    $sohdr = \DB::table('s_salesorder_hdr_t')->whereIn('sales_hdr_id', $sohdrid)->update(['workorder_status' => '1']);
                }

            }/*end*/
            /*vimala purpose:to update notification read*/
            \DB::table('notifications_t')->where('reference_source_id', $_POST['reference_id'])->where('reference_source', '=', 'SO APPROVED')->update(['read/unread' => 'read']);
            /*end*/
            \DB::commit();
            /*vimala purpose:to show notification when create wo*/
            $notifcation = 'Workorder ' . $seqno . ' Initiated';
            $send_notification = $this->sendPopUpHomeNoty($id, "WORKORDER FROM SO", $notifcation, 'productionplananalyse');
            /*end*/
            return response()->json(array('status' => 'success', 'message' => 'Saved Successfully', 'id' => $id, 'lid' => $lid, 'auto_no' => $seqno));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }
    /*end*/
    /* purpose:to show the workorder*/
    public function show($id = null)
    {
        if (isset($id)) {
            $wo = DB::table('w_workorder_hdr_t')->where('workorder_hdr_id', $id)->get();
            $this->data['workorder_no'] = $wo[0]->workorder_no;
            $this->data['so_reference_number'] = $wo[0]->so_reference_number;
            $this->data['source'] = $wo[0]->source;
            $this->data['shift'] = $wo[0]->shift;
            $this->data['workorder_date'] = date(\Session::get('p_date_format'), strtotime($wo[0]->workorder_date));
            $this->data['remarks'] = $wo[0]->remarks;
            $this->data['created_by'] = $this->idname("username", "tb_users", "id", $wo[0]->created_by);
            $vlinesdata = \DB::table('w_workorder_lines_t')->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'w_workorder_lines_t.product_id')->leftjoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'w_workorder_lines_t.uom_code_id')->where('w_workorder_lines_t.workorder_hdr_id', $id)->get();
            $this->data['vlinesdata'] = $vlinesdata;

            return view('workorder.view', $this->data);
        }
    }
    /*end*/
    /*deepika purpose:to check the workorder already used in material plan*/
    public function editcheck($id = null)
    {
        $column = array('reference_id');
        $table = array('w_productionplan_hdr_t');
        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $id)->get();
            if (count($query) > 0) {
                $j = 1;
                return $j;
            }
        }
        return $j;
    }
    /*end*/


    /*deepika purpose:to delete the workorder*/
    public function delete($id = null)
    {
        $j = 0;
        $del_id = $id;
        $column = array('workorder_hdr_id');
        $table = array('w_workorder_hdr_t');
        /*  for($i=0; $i<count($table); $i++)
          {
              $j=0;
              $query = \DB::table($table[$i])->where($column[$i],$del_id)->get();
              if(count($query)>0)
              {
                  $j=1;
                  break;
              }
          }*/

        if ($j == 0) {
            $query = DB::table('w_workorder_hdr_t')->where('workorder_hdr_id', $del_id)->delete();
            $query1 = DB::table('w_workorder_lines_t')->where('workorder_hdr_id', $del_id)->delete();
        }

        if ($j == 1)
            return 1;
        else if ($j == 0)
            return 0;
    }
    /*end*/
    /*deepika purpose:to load uom based on product*/
    public function uomcode($product_id = null)
    {

        $uom = \DB::table('m_products_t')->select('trx_uom_id')->where('product_id', $product_id)->get();
        if ($uom->isNotEmpty()) {

            $uomcode = $uom[0]->trx_uom_id;
        } else {
            $uomcode = '';
        }

        return $uomcode;
    }
    /*end*/

    /* purpose:to get group id based on group name*/

    public function groupname($name = null, $type = null)
    {
        if ($type == "group") {
            $group = \DB::table('m_product_groups_t')->where('group_name', $name)->get();
            if ($group->isNotEmpty()) {

                $group_id = $group[0]->product_group_id;
                return $group_id;
            } else {
                return 0;
            }

        }

    }
    /*end*/


}