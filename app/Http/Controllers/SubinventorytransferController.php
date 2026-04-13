<?php

namespace App\Http\Controllers;

use App\Subinventorytransfer;
use App\Subinventorytransferlines;
use App\Product;
use DB;
use Illuminate\Http\Request;
use Validator, Input, Redirect, Session;
use Yajra\DataTables\DataTables;

class SubinventorytransferController extends Controller
{
    public function __construct()
    {
        $this->data = array();
        $this->table = "i_subinventory_transfer_hdr_t";
        $this->subtable = "i_subinventory_transfer_lines_t";
        $this->pageModule = "subinventorytransfer";
        $this->model = new Subinventorytransfer;
        $this->submodel = new Subinventorytransferlines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'subinventorytransfer',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu'] = $this->indexs();
    }

    /*Main Page Load Function */
    public function index()
    {
        $table = \DB::table('i_subinventory_transfer_hdr_t')->get();
        $this->data['datas'] = json_encode($table);
        $this->data['pageMethod'] = \Request::route()->getName();

        return view('subinventorytransfer.table', $this->data);
    }



    public function receiveindex(Request $request)
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

        $this->data['groupopt'] = $this->jqgridselect('m_product_groups_t', 'product_group_id', 'group_name');
        $this->data['prdopt'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');
        $table = \DB::table('i_subinventory_transfer_hdr_t')->get();
        $this->data['datas'] = json_encode($table);
        $this->data['pageMethod'] = \Request::route()->getName();

        return view('subinventorytransfer.receivetable', $this->data);
    }


    /*JQgrid Data get Function*/
    public function getreceiveData()
    {

        $wh = '';

        $comp = \Session::get('companyid');
        $loc = \Session::get('location');
        $groupname = \Session::get('groupname');
		
        if ($groupname == "1" || $groupname == "Admin") {
            $wh .= "and i_subinventory_transfer_lines_t.company_id=$comp";
        } else {
            $wh .= "and i_subinventory_transfer_lines_t.company_id=$comp  and i_subinventory_transfer_lines_t.location_id=$loc";
        }
        $wh .= $this->accyearcondition('i_subinventory_transfer_hdr_t.trx_date');

        $SQL = "select  i_subinventory_transfer_hdr_t.subtransfer_no,i_subinventory_transfer_hdr_t.trx_date, tb_users.username,m_products_t.concatenated_product,m_product_groups_t.group_name,(
    SELECT
        subinventory_name
    FROM
        m_subinventory_t
    WHERE
        subinventory_id = i_subinventory_transfer_hdr_t.frm_subinv_id
    LIMIT 1
) AS frm_subinv_id,(
    SELECT
        subinventory_name
    FROM
        m_subinventory_t
    WHERE
        subinventory_id = i_subinventory_transfer_hdr_t.to_subinv_id
    LIMIT 1
) AS to_subinv_id,i_subinventory_transfer_lines_t.subinventory_transfer_line_id,i_subinventory_transfer_lines_t.active,i_subinventory_transfer_lines_t.batch_no,  i_subinventory_transfer_lines_t.from_transfer_qty, i_subinventory_transfer_lines_t.to_transfer_qty, i_subinventory_transfer_lines_t.qoh,i_subinventory_transfer_lines_t.manufacture_date,i_subinventory_transfer_lines_t.product_expire_date,i_subinventory_transfer_lines_t.remarks from i_subinventory_transfer_lines_t left join m_product_groups_t on i_subinventory_transfer_lines_t.product_group_id = m_product_groups_t.product_group_id left join m_products_t  on i_subinventory_transfer_lines_t.product_id = m_products_t.product_id left join  i_subinventory_transfer_hdr_t on  i_subinventory_transfer_hdr_t.subinventory_transfer_hdr_id=i_subinventory_transfer_lines_t.subinventory_transfer_hdr_id  left join `tb_users` on (tb_users.id=i_subinventory_transfer_lines_t.created_by) where 1=1 and i_subinventory_transfer_lines_t.status!='APPROVED' $wh order by i_subinventory_transfer_hdr_t.subinventory_transfer_hdr_id DESC";

        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);
    }


    /*Recieve Create Function*/
    public function receivecreate($id = null)
    {
        ///dd($id);
        $com = \Session::get('companyid');

        $this->data['cmp_id'] = $this->jCombologin("m_company_t", "company_id", "company_name", "", "");
        // $tab = \DB::table('i_subinventory_transfer_hdr_t')->where('subinventory_transfer_hdr_id',$id)->get(); 

        //$this->data['to_subinv_id']=$tab[0]->to_subinv_id;


        $table = \DB::table('i_subinventory_transfer_lines_t')->leftjoin('i_subinventory_transfer_hdr_t', 'i_subinventory_transfer_hdr_t.subinventory_transfer_hdr_id', '=', 'i_subinventory_transfer_lines_t.subinventory_transfer_hdr_id')->select('i_subinventory_transfer_hdr_t.to_subinv_id', 'i_subinventory_transfer_hdr_t.subtransfer_no', 'i_subinventory_transfer_hdr_t.frm_subinv_id', 'i_subinventory_transfer_hdr_t.trx_date', 'i_subinventory_transfer_lines_t.*')->where('subinventory_transfer_line_id', $id)->get();
        //dd($table);
        $this->data['product_id'] = $this->jcustomselect("m_products_t", "product_id", "concatenated_product", $table[0]->product_id, "and company_id=" . $com);
        $this->data['product_group_id'] = $this->jcustomselect("m_product_groups_t", "product_group_id", "group_name", $table[0]->product_group_id, "and company_id=" . $com);
        $this->data['qoh'] = $table[0]->qoh;
        $this->data['from_transfer_qty'] = $table[0]->from_transfer_qty;
        $this->data['frm_subinv_id'] = $table[0]->frm_subinv_id;
        $this->data['to_subinv_id'] = $table[0]->to_subinv_id;
        $this->data['subtransfer_no'] = $table[0]->subtransfer_no;
        $this->data['trx_date'] = $table[0]->trx_date;
        $this->data['remarks'] = $table[0]->remarks;

        $this->data['subinventory_transfer_line_id'] = $table[0]->subinventory_transfer_line_id;
        $this->data['receive_qty'] = '';
        $this->data['batch_no'] = $table[0]->batch_no;
        $this->data['manufacture_date'] = $table[0]->manufacture_date;
        $sesdate = \Session::get('p_date_format');
        $expire = date($sesdate, strtotime($table[0]->product_expire_date));
        $this->data['product_expire_date'] = $expire;
        $this->data['linedata'] = array();
        $this->data['return_url'] = $this->data['pageMethod'] = \Request::route()->getName();

        return view('subinventorytransfer.receiveform', $this->data);

    }
    /*End*/

    /*Recieve Update Function*/
    public function Receiveupdate($id = null, $rcvqty = null)
    {
        $to_sub = $_GET['sub'];
        $to_loc = $_GET['loc'];
        $created_by = \Session::get('id');
        $created_at = date('Y-m-d H:i:s');
        $last_updated_by = \Session::get('id');
        $updated_at = date('Y-m-d H:i:s');
        $org_id = \Session::get('organization');
        $location_id = \Session::get('location');

        $expiryDate = $_GET['expiryDate'];
        $mfgDate = $_GET['mfgDate'];
        $product_expire_date = $expiryDate ?? null;
        $manufacture_date = $mfgDate ?? null;
        
        \DB::update("update i_subinventory_transfer_lines_t set receive_qty='$rcvqty',status='APPROVED',to_sub=$to_sub,to_loc=$to_loc,created_by=$created_by,created_at='$created_at',last_updated_by=$last_updated_by,updated_at='$updated_at',product_expire_date='$product_expire_date',manufacture_date='$manufacture_date'  WHERE subinventory_transfer_line_id ='$id' ");
    
        $sql = \DB::table('i_subinventory_transfer_hdr_t')
            ->leftjoin('i_subinventory_transfer_lines_t', 'i_subinventory_transfer_lines_t.subinventory_transfer_hdr_id', '=', 'i_subinventory_transfer_hdr_t.subinventory_transfer_hdr_id')
            ->where('i_subinventory_transfer_lines_t.subinventory_transfer_line_id', $id)->select('i_subinventory_transfer_lines_t.*', 'i_subinventory_transfer_hdr_t.*')->get();
        // dd($sql);
        
        $product_id = $sql[0]->product_id;
        $frm_subinv_id = $sql[0]->frm_subinv_id;
        $frm_loc_id = $sql[0]->from_loc;
        $receive_qty = $sql[0]->receive_qty;
        $to_subinv_id = $sql[0]->to_sub;
        $to_loc_id = $sql[0]->to_loc;
        $frm_cmp_id = $sql[0]->company_id;
        $to_cmp_id = $sql[0]->company_id;
        $batch_no = $sql[0]->batch_no;

        $from_data_mtl_insert_id_array = $to_data_mtl_insert_id_array = array();
        //dd($frm_loc_id);

        $product = Product::find($product_id);
        $product = json_decode(json_encode($product), true);

        $trsnsType = \DB::table('m_transaction_types_t')->where('transaction_type_code', 'SUB INVENTORY TRANSFER')->get(); //dd($trsnsType);
        $trsnsType = json_decode(json_encode($trsnsType), true);

        $trx_source_type_id = $trsnsType[0]['transaction_source_id'];
        $trx_action_id = $trsnsType[0]['transaction_action_id'];
        $trx_type_id = $trsnsType[0]['transaction_type_id'];
        $trx_source_hdr_id = $sql[0]->subinventory_transfer_hdr_id;
        $trx_source_date = $sql[0]->trx_date;
        $trx_source_line_id = $id;

        $trx_uom = $product['trx_uom_id'];
        $line_number = '';
        $trx_date = date('Y-m-d');
        $trx_cost = '';
        $period_id = '';
        $trx_reference = '';
        $gl_codecombination_id = '';
        $currency_code = '';
        $project_id = '';

        $created_by = '';
        $created_date = date('Y-m-d');


        // FROM SAVE ON MTL TRANSACTION
        $from_data_mtl_and_qoh = array(

            //FROM 	MTL SAVE ARRAY  
            "trx_source_type_id" => $trx_source_type_id,
            "trx_source_hdr_id" => $trx_source_hdr_id,//
            "trx_source_line_id" => $trx_source_line_id,//
            "trx_type_id" => $trx_type_id,
            "trx_action_id" => $trx_action_id,
            "line_number" => $line_number,
            "product_id" => $product_id,
            "subinventory_id" => $frm_subinv_id,
            "locator_id" => $frm_loc_id,
            "trx_qty" => "-" . $receive_qty,
            "trx_uom" => $trx_uom,
            "trx_date" => $trx_date,  //cur date
            "period_id" => $period_id, //
            "trx_reference" => "SUBINVENTORY TRANSFER",
            "gl_codecombination_id" => $gl_codecombination_id, // 
            "trx_cost" => $trx_cost,  //
            "currency_code" => $currency_code, //currency code table
            "project_id" => $project_id,//
            "created_by" => $created_by,//
            "created_at" => $created_at,//
            "company_id" => $frm_cmp_id

            //FROM 	MTL SAVE ARRAY  
        );


        $from_data_mtl_insert_id = DB::table('m_material_trx_t')->insertGetId($from_data_mtl_and_qoh);
        array_push($from_data_mtl_insert_id_array, $from_data_mtl_insert_id);


        $data_from_qoh = array(

            "product_id" => $product_id,
            "subinventory_id" => $frm_subinv_id,
            "locator_id" => $frm_loc_id,
            "qoh_trx_qty" => "-" . $receive_qty,
            "qoh_uom_code_id" => $trx_uom,
            "company_id" => $frm_cmp_id,
            "qoh_source" => "SUBINVENTORY TRANSFER",
            "create_trx_id" => $from_data_mtl_insert_id,
            "update_trx_id" => '',
            "batch_number" => $batch_no,
            "created_by" => $created_by,
            "created_at" => $created_at,
            "qoh_trx_date" => $trx_source_date,
            "qoh_source_id" => $trx_source_hdr_id,
            "last_updated_by" => $last_updated_by,
            "updated_at" => $updated_at,
            "location_id" => $location_id,
            "organization_id" => $org_id
        );
        //FROM 	QOH SAVE ARRAY  



        $data_qoh_insert_id = \DB::table('i_qoh_detail_t')->insert($data_from_qoh);
        //dd($data_from_qoh);

        // FROM SAVE ON MTL TRANSACTION

        //******FROM MTL TRANSACTION & QOH AREA*****************************************************

        //************************TO MTL TRANSACTION & QOH AREA*****************************************************

        // TO SAVE ON MTL TRANSACTION
        $to_data_mtl_and_qoh = array(
            //TO MTL SAVE ARRAY  
            "trx_source_type_id" => $trx_source_type_id,
            "trx_source_hdr_id" => $trx_source_hdr_id,//
            "trx_source_line_id" => $trx_source_line_id,//
            "trx_type_id" => $trx_type_id,
            "trx_action_id" => $trx_action_id,
            "line_number" => $line_number,
            "product_id" => $product_id,
            "subinventory_id" => $to_subinv_id,
            "locator_id" => $to_loc_id,
            "trx_qty" => $receive_qty,
            "trx_uom" => $trx_uom,
            "trx_date" => $trx_date,  //cur date
            "period_id" => $period_id, //
            "trx_reference" => "SUBINVENTORY TRANSFER RECEIVE",
            "gl_codecombination_id" => $gl_codecombination_id, // 
            "trx_cost" => $trx_cost,  //
            "currency_code" => $currency_code, //currency code table
            "project_id" => $project_id,//
            "created_by" => $created_by,//
            "created_at" => $created_date,//
            "company_id" => $to_cmp_id
            //TO MTL SAVE ARRAY  

        );

        $to_data_mtl_insert_id = DB::table('m_material_trx_t')->insertGetId($to_data_mtl_and_qoh);
        array_push($to_data_mtl_insert_id_array, $to_data_mtl_insert_id);
        //TO QOH SAVE ARRAY  

        $data_to_qoh = array(

            "product_id" => $product_id,
            "subinventory_id" => $to_subinv_id,
            "locator_id" => $to_loc_id,
            "qoh_source" => "SUBINVENTORY TRANSFER RECEIVE",
            "qoh_trx_qty" => $receive_qty,
            "qoh_uom_code_id" => $trx_uom,
            "company_id" => $to_cmp_id,
            "create_trx_id" => $to_data_mtl_insert_id,
            "update_trx_id" => '',
            "batch_number" => $batch_no,
            "product_expire_date" => $product_expire_date,
            "manufacturer_date" => $manufacture_date,
            "created_by" => $created_by,
            "created_at" => $created_at,
            "qoh_trx_date" => $trx_source_date,
            "qoh_source_id" => $trx_source_hdr_id,
            "last_updated_by" => $last_updated_by,
            "updated_at" => $updated_at,
            "location_id" => $location_id,
            "organization_id" => $org_id
        );


        //TO QOH SAVE ARRAY 
        //*************************************************************TO MTL TRANSACTION & QOH AREA********************
        $data_qoh_insert_id = \DB::table('i_qoh_detail_t')->insert($data_to_qoh);
        $fcnt = count($from_data_mtl_insert_id_array);
        $tcnt = count($to_data_mtl_insert_id_array);

        return response()->json(array('status' => 'success', 'message' => 'Subinventory Transfer Received Successfully', 'id' => $id));



    }
    /*End*/
    /*Jqrid function*/
    public function getGridData()
    {

        $wh = '';
        if ($_GET['_search'] == 'true') {

            $wh = $this->jqgridsearch($_GET['filters']);
        }

        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if (!$sidx)
            $sidx = 1;
        $result = \DB::select("SELECT COUNT(product_id) AS count FROM m_products_t where 1=1 $wh");
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
        $SQL = "select
			m.product_id,
			m.concatenated_product,
			m.product_group_id,
			pg.group_name,
			pc.product_category_id,
			pc.category_name,
			qoh.qoh_trx_qty,
			m.frm_trx_qty,
			m.to_trx_qty
			from m_products_t m
			left join m_product_groups_t pg on m.product_group_id = pg.product_group_id
			left join m_product_category_t pc on m.product_category_id = pc.product_category_id
			left join i_qoh_detail_t qoh on qoh.product_id = m.product_id where qoh.qoh_trx_qty >0 ORDER BY $sidx $sord LIMIT $start , $limit";

        $result = \DB::select($SQL);
        //dd($result);
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        echo json_encode($responce);

    }
    /*End*/

    /*Create Function*/
    public function create(Request $request, $id = null)
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


        $group = \Session::get('groupname');
        //dd($group);
        $this->data['group_name'] = $group;
        $com = \Session::get('companyid');
        $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
        $this->data['cmp_id'] = $this->jCombologin("m_company_t", "company_id", "company_name", "", "");
        $this->data['subinv_id'] = $this->jCombologin("m_subinventory_t", "subinventory_id", "subinventory_name", "", "");
        $this->data['loc_id'] = $this->jCombologin("m_sublocators_t", "sublocator_id", "locator_code", "", "");
        $this->data['employee'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'first_name', '', '  and group_type=10');
        $this->data['product_id'] = $this->jcustomselect("m_products_t", "product_id", "concatenated_product", "", "and company_id=" . $com);
        $this->data['product_group_id'] = $this->jcustomselect("m_product_groups_t", "product_group_id", "group_name", "", "and company_id=" . $com);
        $this->data['form_date'] = date('Y-m-d');
        $this->data['prdcatopt'] = $this->jqgridselect('m_product_category_t', 'product_category_id', 'category_name');
        $this->data['group'] = $this->jqgridselect('m_product_groups_t', 'product_group_id', 'group_name');
        $this->data['prdnameopt'] = $this->jqgridselect('m_products_t', 'product_id', 'concatenated_product');
        $this->data['linedata'] = array();
        $this->data['return_url'] = $this->data['pageMethod'] = \Request::route()->getName();

        return view('subinventorytransfer.form', $this->data);
    }
    /*End*/
	
    /*Save Function*/
    public function save(Request $request)
    {
     

        $linedata = $request->all();


        $form_post_only_bulk = $line_data_array = $hdrdataarray = array();
        foreach ($linedata as $key => $value) {
            if (substr_count($key, 'bulk') > 0) {
                //seperate the bulk from form column
                $key1 = $key;
                $form_post_only_bulk[$key] = $value;
            }
        }
        $cnt = count($form_post_only_bulk);

        for ($i = 0; $i < $cnt; $i++) {
            foreach ($form_post_only_bulk as $key => $value) {
                $cnt1 = count($value);
                $k = is_array($form_post_only_bulk[$key]);
                for ($j = 0; $j < $cnt1; $j++) {
                    $key = str_replace("bulk_", "", $key);

                    // TO IDENTIFY THE POSTED DATA WHETHER SINGLE DIMENSION ARRAY
                    if ($k == true) {
                        $line_data_array[$j][$key] = $value[$j];
                        $line_data_array[$j]['trx_date'] = $linedata['trx_date'];
                        $line_data_array[$j]['frm_cmp_id'] = $linedata['frm_cmp_id'];
                        $line_data_array[$j]['frm_subinv_id'] = $linedata['frm_subinv_id'];
                        $line_data_array[$j]['frm_loc_id'] = $linedata['frm_loc_id'];
                        $line_data_array[$j]['to_cmp_id'] = $linedata['to_cmp_id'];
                        $line_data_array[$j]['to_subinv_id'] = $linedata['to_subinv_id'];
                        $line_data_array[$j]['to_loc_id'] = $linedata['to_loc_id'];
                        $line_data_array[$j]['remarks'] = $linedata['remarks'];
                        // dd( $line_data_array[$j]['to_loc_id']);
                    } else {
                        $line_data_array[$j][$key] = $value;
                        $line_data_array[$j]['trx_date'] = $linedata['trx_date'];
                        $line_data_array[$j]['frm_cmp_id'] = $linedata['frm_cmp_id'];
                        $line_data_array[$j]['frm_subinv_id'] = $linedata['frm_subinv_id'];
                        $line_data_array[$j]['frm_loc_id'] = $linedata['frm_loc_id'];
                        $line_data_array[$j]['to_cmp_id'] = $linedata['to_cmp_id'];
                        $line_data_array[$j]['to_subinv_id'] = $linedata['to_subinv_id'];
                        $line_data_array[$j]['to_loc_id'] = $linedata['to_loc_id'];
                        $line_data_array[$j]['remarks'] = $linedata['remarks'];
                        // 	dd('single');
                    }
                }
            }

        }


        $InventoryutilityController = new \App\Http\Controllers\InventoryutilityController();
        $mtl_qoh = $InventoryutilityController->Mtlsubtransaction($line_data_array);


        if ($mtl_qoh == 1) {
            $data['status'] = "Success";
            $data['message'] = "Transfer Success...";
        } else {
            $data['status'] = "Error";
            $data['message'] = "Transfer Failed....";
        }
        return response()->json(array('status' => $data['status'], 'message' => $data['message']));
        //return Redirect::to('subinventorytransfer');		
    }

    /*End*/

    /*Transfer Save Function*/
    public function transfersave(Request $request)
    {


        // Job Activity Table Entry
        $jobAssignedTo = $request->input('employee_id');
        $start_time = $request->input('process_start_date');
        $end_time = $request->input('process_end_date');
        $duration = $request->input('machine_time');

        $baseHdrData = [
            'created_by' => \Session::get('id'),
            'created_at' => now(),
            'organization_id' => \Session::get('organization'),
            'location_id' => \Session::get('location'),
            'company_id' => \Session::get('companyid'),
            'last_updated_by' => \Session::get('id'),
            'updated_at' => now(),
        ];

        if (is_array($jobAssignedTo) && !empty($jobAssignedTo)) {
            foreach ($jobAssignedTo as $key => $employeeId) {
                if (!empty($employeeId)) {
                    // Save to job activity header
                    $hdrData = $baseHdrData;
                    $hdrData['employee_id'] = $employeeId;
                    $hdrData['job_id'] = "0";
                    $hdrId = \DB::table('w_jobactivity_hdr_t')->insertGetId($hdrData);

                    // Save to job activity lines
                    $lineData = [
                        'created_by' => \Session::get('id'),
                        'created_at' => now(),
                        'organization_id' => \Session::get('organization'),
                        'location_id' => \Session::get('location'),
                        'company_id' => \Session::get('companyid'),
                        'last_updated_by' => \Session::get('id'),
                        'updated_at' => now(),
                        'type' => 'SUB',
                        'activity_name' => 'SUBINVENTORY TRANSFER',
                        'start_datetime' => $start_time,
                        'end_datetime' => $end_time,
                        'duration' => $duration,
                        'job_activity_hdr_id' => $hdrId,
                        'line_no' => $key + 1,
                    ];
                    \DB::table('w_jobactivity_lines_t')->insert($lineData);
                }
            }
        }
		
		  
        // Subinventory Transfer Logic
			$form = $request->all();
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file','enable-masterdetail','process_start_date','machine_time','process_end_date',
    ]);
    
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
			    $lines_data = [];

    $lineCount = count($request -> bulk_product_id);

    for ($i = 0; $i < $lineCount; $i++) {

        // Skip empty lines
        if (empty($request -> bulk_product_id[$i])) {
            continue;
        }

        $lines_data['line_no'][$i] = $request -> bulk_line_no[$i];
        $lines_data['product_id'][$i] = $request -> bulk_product_id[$i];
        $lines_data['product_group_id'][$i] = $request -> bulk_product_group_id[$i];
        $lines_data['qoh'][$i] = $request -> bulk_qoh[$i];
        $lines_data['from_transfer_qty'][$i] = $request -> bulk_from_transfer_qty[$i];
        $lines_data['status'][$i] = $request -> bulk_status[$i];
        $lines_data['to_transfer_qty'][$i] = "0";
       // $lines_data['receive_qty'][$i] = $request -> bulk_receive_qty[$i];
       // $lines_data['active'][$i] = $request -> bulk_active[$i];
        $lines_data['batch_no'][$i] = $request -> bulk_batch_no[$i];
        $lines_data['from_loc'][$i] = $request -> bulk_from_loc[$i];
       // $lines_data['to_sub'][$i] = $request -> bulk_to_sub[$i];
      //  $lines_data['to_loc'][$i] = $request -> bulk_to_loc[$i];
        $lines_data['product_expire_date'][$i] = $request -> bulk_product_expire_date[$i];
        $lines_data['manufacture_date'][$i] = $request -> bulk_manufacture_date[$i];
        $lines_data['remarks'][$i] = $request -> bulk_remarks[$i];
       // $lines_data['employee_id'][$i] = $request -> employee_id[$i];
        // common audit fields
        $lines_data['created_by'][$i] = auth() -> id();
        $lines_data['last_updated_by'][$i] = auth() -> id();
        $lines_data['created_at'][$i] = now();
        $lines_data['updated_at'][$i] = now();
        $lines_data['location_id'][$i] = session('location');
        $lines_data['company_id'][$i] = session('companyid');
        $lines_data['organization_id'][$i] = session('organization');
    }
      // dd($lines_data);
        \DB::beginTransaction();
        try {
            // Auto-generate number if not provided
            if (empty($request->input('subtransfer_no'))) {
                $seqno = $this->Seqno('SI', 'i_subinventory_transfer_hdr_t', '');
                $data['subtransfer_no'] = $seqno;
            } else {
                $seqno = $request->input('subtransfer_no');
            }

            $id = $this->model->insertRow($data);
            $lid = $this->submodel->subgridSave($lines_data, $id);

            \DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Subinventory Transfer Saved', 'id' => $id, 'lid' => $lid]);
        } catch (\Illuminate\Database\QueryException $e) {
            \DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'DatabaseError:=> ' . $e->getMessage()]);
        }
    }
    /*End*/
    /* purpose:To get Product group details*/
    public function prddetails()
    {
        $product = array();
        if (!empty($_GET['frm_subinv_id'])) {
            $sbid = "and subinventory_id=" . $_GET['frm_subinv_id'];
        }
        if (!empty($_GET['frm_loc_id'])) {
            $sbid .= " and locator_id=" . $_GET['frm_loc_id'];
        }
        // dd($sbid);
        $sql = \DB::select("select product_id from i_qoh_detail_t where 1=1 $sbid group by product_id");
        // dd("select product_id from i_qoh_detail_t where 1=1 $sbid group by product_id");
        if (!empty($sql)) {
            $product_id = $this->convert_column_to_row($sql);
            $productid = implode(",", $product_id);
            //dd($productid);
            // subinv trans reciver initated product dont show
            $fid = \DB::select("SELECT product_id FROM `i_subinventory_transfer_lines_t` WHERE (`status` = 'INITIATED' or `status` = '')");
            $fidProductIds = array_column($fid, 'product_id');
            $fgid = implode(',', $fidProductIds);

            // cunsumable initated product dont show
            $cun_id = \DB::select("SELECT product_id FROM i_consumable_lines_t INNER JOIN i_consumable_hdr_t ON i_consumable_lines_t.consumable_hdr_id = i_consumable_hdr_t.consumable_hdr_id WHERE i_consumable_hdr_t.status = 'INITIATED'");
            $cun_ProductIds = array_column($cun_id, 'product_id');
            $cunpg_id = implode(',', $cun_ProductIds);

            $condition = '';
            if (!empty($fgid) && !empty($cunpg_id)) {
                $condition .= " and product_id NOT IN ($fgid) and product_id NOT IN ($cunpg_id)";
            } elseif (!empty($fgid)) {

                $condition .= " and product_id NOT IN ($fgid) ";

            } elseif (!empty($cunpg_id)) {

                $condition .= " and product_id NOT IN ($cunpg_id) ";
            } else {

                $condition = '';
            }
            $emp_id = \Session::get('emp_id');
            if ($emp_id == "537" || $emp_id == "152") {
                $product = $this->jCombo("m_products_t", "product_id", "concatenated_product", "");
            } else {
                $product = $this->jcustomselect("m_products_t", "product_id", "concatenated_product", "", "and product_id in($productid) $condition");
            }
        }  //dd($product);
        return $product;
    }
    /*end*/
    public function productbatchno()
    {

        $company_id = Session::get('companyid');

        $id = $_GET['product_id'];
        $sub_id = $_GET['frm_subinv_id'];
        $loc_id = $_GET['frm_loc_id'];
        $data = \DB::select("select * from (SELECT round(sum(qoh_trx_qty),2) as qty,batch_number FROM `i_qoh_detail_t` WHERE product_id='$id' and subinventory_id='$sub_id' and company_id='$company_id'  group by batch_number order by qoh_detail_id asc)f where f.qty>0");
        $options = "<option>--Please Select--</option>";
        if (!empty($data)) {
            $prod_grp = \DB::table('m_products_t')->where('product_id', $id)->select('product_group_id')->get();
            $prd_grp = $prod_grp[0]->product_group_id;
            foreach ($data as $val) {
                $options .= "<option value='" . $val->batch_number . "'>" . $val->batch_number . "</option>";
            }
        }
        $result = array($prd_grp, $options);
        return $result;
    }

    public function productbatchno1()
    {

        $company_id = Session::get('companyid');

        $id = $_GET['product_id'];
        $sub_id = $_GET['frm_subinv_id'];
        /*	$data=\DB::select("select * from (SELECT sum(qoh_trx_qty) as qty,batch_number FROM `i_qoh_detail_t` WHERE product_id='$id' and subinventory_id='$sub_id' and company_id='$company_id'  group by batch_number order by qoh_detail_id asc)f where f.qty>0");*/
        $data = \DB::select("select * from i_qoh_detail_t left join m_sublocators_t on m_sublocators_t.sublocator_id=i_qoh_detail_t.locator_id where i_qoh_detail_t.subinventory_id='$sub_id' and i_qoh_detail_t.product_id='$id' group by m_sublocators_t.sublocator_id");
        // dd($data);
        $options = "<option>--Please Select--</option>";
        if (!empty($data)) {
            $prod_grp = \DB::table('m_products_t')->where('product_id', $id)->select('product_group_id')->get();
            $prd_grp = $prod_grp[0]->product_group_id;
            //dd(prd_grp);
            foreach ($data as $val) {
                $options .= "<option value='" . $val->locator_id . "'>" . $val->locator_code . "</option>";
            }
        }
        $result = array($prd_grp, $options);
        return $result;
    }
    /* purpose:To get Product Qoh details*/
    public function productqohdetails()
    {

        $company_id = Session::get('companyid');


        $sql = \DB::select("select sum(f.qty) as qoh_trx_qty,f.product_id as product_group_id,product_expire_date,manufacturer_date  from (select round(sum(qoh_trx_qty),2)as qty,0 as qtyy,product_id,product_expire_date,manufacturer_date FROM i_qoh_detail_t where product_id='" . $_GET['product_id'] . "' and subinventory_id='" . $_GET['frm_subinv_id'] . "' and locator_id='" . $_GET['frm_loc_id'] . "' and batch_number='" . $_GET['batch_no'] . "' and company_id='" . $company_id . "' GROUP by product_id)f
");
        if (!empty($sql)) {
            if ($sql[0]->qoh_trx_qty != '') {
                $prod_grp = \DB::table('m_products_t')->where('product_id', $sql[0]->product_group_id)->select('product_group_id')->get();
                $prd_grp = $prod_grp[0]->product_group_id;
                if (($sql[0]->qoh_trx_qty) < 0) {
                    $sql[0]->qoh_trx_qty = 0;
                }
                $sesdate = \Session::get('p_date_format');
                $expire = date($sesdate, strtotime($sql[0]->product_expire_date));
                $result = array($sql[0]->qoh_trx_qty, $sql[0]->product_group_id, $prd_grp, $expire, $sql[0]->manufacturer_date);
                return $result;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    /*end*/
    function findPrimarykey($table)
    {
        $primaryKey = '';
        foreach (\DB::select("show columns from " . $table . " where extra like '%auto_increment%'") as $key) {
            $primaryKey = $key->Field;
        }
        return $primaryKey;
    }

    function convert_column_to_row($data)
    {

        $data1 = array();


        foreach ($data as $key => $value) {
            $key1 = $key;
            $data1[$key] = $value->product_id;

        }
        return ($data1);

    }
}
