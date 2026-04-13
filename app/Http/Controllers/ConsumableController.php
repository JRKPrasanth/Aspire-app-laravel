<?php

namespace App\Http\Controllers;

use App\Consumable;
use App\Consumablelines;
use App\Schemes;
use App\schemeslines;
use Illuminate\Http\Request;
use Config;
use session;
use Yajra\DataTables\DataTables;

class ConsumableController extends Controller
{
    public $module = "Consumable";
    /* purpose: construct function to set values throughout the controller like model,submodel,pagemethod*/
    public function __construct()
    {
        $this->data = array();
        $this->model = new Consumable();
        $this->submodel = new Consumablelines();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageModule'] = 'consumable';
        $this->table = " i_consumable_hdr_t";
        $this->subtable = "i_consumable_lines_t";
        $this->middleware('auth');
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



        return view('consumable.table', $this->data);
    }


    public function getconsumableData(Consumable $consumable)
    {

        $wh = '';
        $wh1 = '';

        if (isset($_GET)) {

            if ($_GET['pageurl'] == 'consumableapproval') {
                $wh .= "and i_consumable_hdr_t.status='INITIATED'";
            }
        }

        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');
        if ($groupname == '1' || $groupname == 'Admin') {
            $wh .= 'and  i_consumable_hdr_t.company_id=' . $compy;
        } else {
            $wh .= 'and  i_consumable_hdr_t.company_id=' . $compy . ' and i_consumable_hdr_t.location_id=' . $loc;
        }

        $sql = "SELECT
		i_consumable_hdr_t.*,
		tb_users.username,
		tb_users.first_name,
		m_subinventory_t.subinventory_name, 
		m_sublocators_t.locator_name
	FROM
		i_consumable_hdr_t
	LEFT JOIN tb_users ON
		(
			tb_users.id = i_consumable_hdr_t.created_by
		)
	LEFT JOIN i_consumable_lines_t ON i_consumable_hdr_t.consumable_hdr_id = i_consumable_lines_t.consumable_hdr_id
	LEFT JOIN m_subinventory_t ON i_consumable_lines_t.subinventory = m_subinventory_t.subinventory_id
	LEFT JOIN m_sublocators_t ON i_consumable_lines_t.sublocator = m_sublocators_t.sublocator_id
	WHERE 1 = 1 $wh $wh1 GROUP BY i_consumable_hdr_t.consumable_hdr_id order by i_consumable_hdr_t.consumable_number DESC";

        $result = \DB::select($sql);
        return DataTables::of($result)->make(true);
    }



    public function create($id = null)
    {
        if (isset($id)) {
            $this->data['id'] = $id;
            $table = \DB::table('i_consumable_hdr_t')->where('consumable_hdr_id', $id)->get();
            $this->data['row'] = $table[0];
            $this->data['product'] = $this->jcombo('m_products_t', 'product_id', 'product_code|concatenated_product', "");
            $this->data['linedata'] = $data = \DB::table('i_consumable_lines_t')->where('consumable_hdr_id', $id)->get();
            $this->data['linedata'] = $data = \DB::table('i_consumable_lines_t')->where('consumable_hdr_id', $id)->get();

            foreach ($data as $k => $val) {

                $product_id = $val->product_id;
                // Store the raw product_id before overwriting with HTML options
                $this->data['linedata'][$k]->product_id_raw = $val->product_id;
                $this->data['linedata'][$k]->product_id = $this->jcustomselectcomp('m_products_t', 'product_id', 'product_code|concatenated_product', $val->product_id, ' and product_id=' . $val->product_id);

                $this->data['linedata'][$k]->batch_no = $this->jcustomselect('i_qoh_detail_t', 'batch_number', 'batch_number', $val->batch_no, ' and product_id=' . $product_id . ' and qoh_trx_qty>0');
                $this->data['linedata'][$k]->subinventory_id = $this->jcustomselect('m_subinventory_t', 'subinventory_id', 'subinventory_name', $val->subinventory, '');

                $this->data['linedata'][$k]->sublocator_id = $this->jcustomselect('m_sublocators_t', 'sublocator_id', 'locator_code', $val->sublocator, '');

                $this->data['qoh'] = $this->data['linedata'][$k]->qoh;
                $this->data['comments'] = $this->data['linedata'][$k]->comments;

                //dd($this->data['qoh']);

            }


            $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);
        } else {

            if (isset($_GET['approval'])) {
                $id = $_GET['approval'];
                $this->data['id'] = $id;

                $table = \DB::table('i_consumable_hdr_t')->where('consumable_hdr_id', $id)->get();
                $this->data['row'] = $table[0];
                $this->data['linedata'] = $data = \DB::table('i_consumable_lines_t')->where('consumable_hdr_id', $id)->get();
                foreach ($data as $k => $val) {
                    if ($val->batch_no != '') {


                        $this->data['linedata'][$k]->batch_no = $this->jcustomselect('i_qoh_detail_t', 'batch_number', 'batch_number', $val->batch_no, " and batch_number='" . $val->batch_no . "'");
                        // dd($val->batch_no);
                    } else {
                        $this->data['linedata'][$k]->batch_no = '';
                    }
                    $this->data['linedata'][$k]->subinventory_id = $this->jcustomselect('m_subinventory_t', 'subinventory_id', 'subinventory_name', $val->subinventory, '');
                    $this->data['linedata'][$k]->sublocator_id = $this->jcustomselect('m_sublocators_t', 'sublocator_id', 'locator_code', $val->sublocator, '');
                    // Store the raw product_id before overwriting with HTML options
                    $this->data['linedata'][$k]->product_id_raw = $val->product_id;
                    $this->data['linedata'][$k]->product_id = $this->jcustomselectcomp('m_products_t', 'product_id', 'product_code|concatenated_product', $val->product_id, ' and product_id=' . $val->product_id);
                    $this->data['qoh'] = $this->data['linedata'][$k]->qoh;
                    $this->data['comments'] = $this->data['linedata'][$k]->comments;
                }
                $this->data['product'] = $this->jcombo('m_products_t', 'product_id', 'product_code|concatenated_product', "");
                $this->data['accounts_structure_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
                $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);
            } else {
                $this->modelname = new Consumable();
                $this->data['row'] = (object) array();

                $this->data['status'] = "INITIATED";
                $table = $this->modelname->getTableColumns();
                foreach ($table as $key => $val) {
                    $this->data['row']->$val = '';
                }
                $this->data['id'] = '';
                $this->data['editcheck'] = "";
                $sesdate = \Session::get('p_date_format');
                $this->data['row']->consumable_date = date('Y-m-d');

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

                $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', '', " $condition");
                $this->data['subinventory_id'] = $this->jcustomselect('m_subinventory_t', 'subinventory_id', 'subinventory_name', "", '');
                $this->data['sublocator_id'] = $this->jcustomselect('m_sublocators_t', 'sublocator_id', 'locator_code', "", '');
                $this->data['product'] = $this->jcombo('m_products_t', 'product_id', 'product_code|concatenated_product', "");
                $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
                $this->data['linedata'] = array();
            }
        }

        return view('consumable.form', $this->data);
    }


    /** Store a newly created data & update data in db  */
    public function save(Request $request)
    {


        $consumable = new Consumable();
        $this->modelname = new Consumable();
        $machinelines = new Consumablelines();
        $this->modelline = new Consumablelines();

        $form = $request->all();
        $dataupload = "";
        $form = $request->except([
            '_token',
            'form_config',
            'form_data_json',
            'savestatus',
            'submit_type',
            'choosefile',
            'existing_file',
            'enable-masterdetail',
        ]);
        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');
        $lines_data = $this->validatePost($form, $this->subtable, 'lines');

        /* purpose:auto generate number*/
        if ($_POST['consumable_number'] == "") {
            $seqno = $this->Seqnoe('C', 'i_consumable_hdr_t', '', 'consumable_count');
            $data['consumable_number'] = $seqno[0];
            $data['consumable_count'] = $seqno[1];
            //	dd($seqno);
        } else {
            $seqno = $_POST['consumable_number'];
        }
        /*end*/
        \DB::beginTransaction();
        try {
            $id = $this->model->insertRow($data);

            $lid = $this->submodel->subgridSave($lines_data, $id);

            if ($data['status'] != 'APPROVED') {

                $dat = \DB::select("SELECT *,i_consumable_hdr_t.status as con_status FROM i_consumable_hdr_t LEFT JOIN i_consumable_lines_t ON i_consumable_hdr_t.consumable_hdr_id = i_consumable_lines_t.consumable_hdr_id LEFT JOIN tb_users ON i_consumable_hdr_t.created_by = tb_users.id LEFT JOIN m_products_t ON m_products_t.product_id = i_consumable_lines_t.product_id WHERE i_consumable_hdr_t.consumable_hdr_id='" . $id . "'");

                $pur['consumable_number'] = $dat[0]->consumable_number;
                $pur['consumable_date'] = $dat[0]->consumable_date;
                $pur['concatenated_product'] = $dat[0]->concatenated_product;
                $pur['batch_no'] = $dat[0]->batch_no;
                $pur['qty'] = $dat[0]->qty;
                $pur['comments'] = $dat[0]->comments;
                $pur['user_clear'] = $dat[0]->first_name;
                $pur['con_status'] = $dat[0]->con_status;

                $po_num_sub = $dat[0]->consumable_number;
                Session::put('po_num_sub', $po_num_sub);
                $po_email = $dat[0]->email;
                Session::put('po_email', $po_email);
                if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                    Config::set('mail.username', \Session::get('user_email'));
                    Config::set('mail.password', \Session::get('user_password'));
                }

                if (\Session::get('user_email') != '') {

                    $to_mail_id = "expenses@jrkresearch.com";

                    \Mail::send('consumable.mail', $pur, function ($message) use ($to_mail_id) {

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

                        $message->subject($po_num_sub . " - CONSUMABLE INITIATED");
                    });
                }
            } else {

                $dat = \DB::select("SELECT *,i_consumable_hdr_t.status as con_status FROM i_consumable_hdr_t LEFT JOIN i_consumable_lines_t ON i_consumable_hdr_t.consumable_hdr_id = i_consumable_lines_t.consumable_hdr_id LEFT JOIN tb_users ON i_consumable_hdr_t.last_updated_by = tb_users.id LEFT JOIN m_products_t ON m_products_t.product_id = i_consumable_lines_t.product_id WHERE i_consumable_hdr_t.consumable_hdr_id='" . $id . "'");

                $to_mail = \DB::select("SELECT * FROM i_consumable_hdr_t LEFT JOIN tb_users ON i_consumable_hdr_t.created_by = tb_users.id WHERE i_consumable_hdr_t.consumable_hdr_id='" . $id . "'");

                $pur['consumable_number'] = $dat[0]->consumable_number;
                $pur['consumable_date'] = $dat[0]->consumable_date;
                $pur['concatenated_product'] = $dat[0]->concatenated_product;
                $pur['batch_no'] = $dat[0]->batch_no;
                $pur['qty'] = $dat[0]->qty;
                $pur['comments'] = $dat[0]->comments;
                $pur['user_clear'] = $dat[0]->first_name;
                $pur['con_status'] = $dat[0]->con_status;

                $po_num_sub = $dat[0]->consumable_number;
                Session::put('po_num_sub', $po_num_sub);
                $po_email = $dat[0]->email;
                Session::put('po_email', $po_email);
                if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                    Config::set('mail.username', \Session::get('user_email'));
                    Config::set('mail.password', \Session::get('user_password'));
                    //dd('user_email');
                }

                if (\Session::get('user_email') != '') {
                    $to_mail_id = $to_mail[0]->email;

                    if (isset($to_mail_id)) {
                        $to_mail_id = $to_mail[0]->email;
                    } else {
                        $to_mail_id = "aspire@jrkresearch.com";
                    }

                    \Mail::send('consumable.mail', $pur, function ($message) use ($to_mail_id) {
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

                        $message->subject($po_num_sub . " - CONSUMABLE APPROVED");
                    });
                }
            }


            if ($data['status'] == "APPROVED") {
                $id = $_POST['consumable_hdr_id'];
                $cons = "CONSUMABLE";
                $dcdate = date('Y-m-d');
                $org = \Session::get('organization');
                $loc = \Session::get('location');
                $compy = \Session::get('companyid');
                $refsrc = "CONSUMABLE-" . $data['consumable_number'];
                $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$refsrc','$cons','$dcdate','$id','APPROVED','$compy','$loc','$org')");
                $jid = \DB::getPdo()->lastInsertId();
                //dd($jid);
                $po_details = \DB::table('m_products_t')->whereIn('product_id', $_POST['bulk_product_id'])->get();

                $collection = collect($po_details);
                // $_POST['bulk_product_id']=['4'];
                $tkey = 0;

                foreach ($_POST['bulk_product_id'] as $index => $value) {

                    $filtereds = $collection->where('product_id', $value);
                    $filtereds->all();
                    $filtered = array();

                    foreach ($filtereds as $val) {
                        $filtered[] = $val;
                    }

                    $batch_no = $_POST['bulk_batch_no'][$index];


                    $datas = \DB::select("SELECT batch_number,cost FROM `i_qoh_detail_t` WHERE product_id='$value' and company_id='$compy' and batch_number='$batch_no'   order by qoh_detail_id asc limit 1");

                    $qty = (float) $_POST['bulk_qty'][$index];

                    if ($qty > 0) {
                        // if($index==1){
                        // 				dd($filtered[0]);
                        // 			}
                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $dcdate;
                        $journal_lines_data[$tkey]['reference_source'] = "";
                        $journal_lines_data[$tkey]['reference_id'] = '';
                        $journal_lines_data[$tkey]['product_qty'] = '';
                        $journal_lines_data[$tkey]['batch_number'] = '';
                        $journal_lines_data[$tkey]['account_id'] = $_POST['bulk_accounts_structure_id'][$index];
                        $journal_lines_data[$tkey]['debit_amount'] = $qty * $datas[0]->cost;
                        $journal_lines_data[$tkey]['credit_amount'] = '';
                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        $tkey++;

                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $dcdate;
                        $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
                        $journal_lines_data[$tkey]['reference_id'] = $value;
                        $journal_lines_data[$tkey]['product_qty'] = $qty;
                        $journal_lines_data[$tkey]['batch_number'] = $batch_no;
                        $journal_lines_data[$tkey]['account_id'] = $filtered[0]->account_code_id;
                        $journal_lines_data[$tkey]['debit_amount'] = '';
                        $journal_lines_data[$tkey]['credit_amount'] = $qty * $datas[0]->cost;

                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        $tkey++;
                    } else {
                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $dcdate;
                        $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
                        $journal_lines_data[$tkey]['reference_id'] = $value;
                        $journal_lines_data[$tkey]['product_qty'] = $qty;
                        $journal_lines_data[$tkey]['batch_number'] = $batch_no;
                        $journal_lines_data[$tkey]['account_id'] = $filtered[0]->account_code_id;
                        $journal_lines_data[$tkey]['debit_amount'] = $qty * $datas[0]->cost * -1;
                        $journal_lines_data[$tkey]['credit_amount'] = '';
                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        $tkey++;
                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $dcdate;
                        $journal_lines_data[$tkey]['reference_source'] = "";
                        $journal_lines_data[$tkey]['reference_id'] = '';
                        //$journal_lines_data[$tkey]['account_id']='';

                        $journal_lines_data[$tkey]['account_id'] = $_POST['bulk_accounts_structure_id'][$index];
                        $journal_lines_data[$tkey]['debit_amount'] = '';
                        $journal_lines_data[$tkey]['credit_amount'] = $qty * $datas[0]->cost * -1;
                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        $tkey++;
                    }
                    /* purpose: insert data into mtl transaction tbl */
                    $trsns = \DB::table('m_transaction_types_t')->where('transaction_type_name', 'CONSUMABLE')->get();
                    $trsns = json_decode(json_encode($trsns), true);
                    $mdata['trx_source_type_id'] = $trsns[0]['transaction_source_id'];
                    $mdata['trx_action_id'] = $trsns[0]['transaction_action_id'];
                    $mdata['trx_type_id'] = $trsns[0]['transaction_type_id'];
                    $mdata['trx_source_hdr_id'] = $id;
                    $mdata['trx_source_line_id'] = $_POST['bulk_consumable_line_id'][$index];
                    $mdata['line_number'] = $index + 1;
                    $mdata['product_id'] = $value;
                    $mdata['trx_uom'] = '';
                    $mdata['subinventory_id'] = $_POST['bulk_subinventory'][$index];
                    $mdata['locator_id'] = $_POST['bulk_sublocator'][$index];
                    $mdata['trx_qty'] = -$_POST['bulk_qty'][$index];
                    $mdata['trx_date'] = date('Y-m-d');
                    $mdata['created_by'] = \Session::get('id');
                    $mdata['created_at'] = date('Y-m-d');
                    $mdata['organization_id'] = \Session::get('organization');
                    $mdata['location_id'] = \Session::get('location');
                    $mdata['company_id'] = \Session::get('companyid');
                    $mtlid = \DB::table('m_material_trx_t')->insertGetId($mdata);
                    /* purpose insert data to reduce stock in qoh detail tbl */

                    $dataqoh['product_id'] = $value;
                    $dataqoh['qoh_uom_code_id'] = '';
                    $dataqoh['create_trx_id'] = $mtlid;
                    $dataqoh['qoh_source'] = "CONSUMABLE";
                    $dataqoh['organization_id'] = \Session::get('organization');
                    $dataqoh['location_id'] = \Session::get('location');
                    $dataqoh['company_id'] = \Session::get('companyid');
                    $dataqoh['created_by'] = \Session::get('id');
                    $dataqoh['created_at'] = date('Y-m-d H:i:s');
                    $dataqoh['qoh_trx_date'] = date('Y-m-d', strtotime($_POST['consumable_date']));
                    $dataqoh['qoh_source_id'] = $_POST['consumable_hdr_id'];
                    $dataqoh['create_trx_id'] = $mtlid;
                    $dataqoh['qoh_trx_qty'] = -$_POST['bulk_qty'][$index];
                    $dataqoh['subinventory_id'] = $_POST['bulk_subinventory'][$index];
                    $dataqoh['locator_id'] = $_POST['bulk_sublocator'][$index];
                    $dataqoh['batch_number'] = $_POST['bulk_batch_no'][$index];
                    $qohid = \DB::table('i_qoh_detail_t')->insertGetId($dataqoh);
                }
                \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);
            }

            if ($_POST['consumable_hdr_id'] == "") {
                $msg = 'Consumable Details Saved Successfully';
            } else if ($_POST['status'] == "INITIATED") {
                $msg = 'Consumable Details Updated Successfully';
            } else {

                $msg = 'Consumable Details Approved Successfully';
            }
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => $msg, 'id' => $id));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }

    public function consumableshow($id = "null")
    {
        //dd($id);
        $vals = array();
        $header = \DB::table('i_consumable_hdr_t')->where('consumable_hdr_id', $id)->get();
        $this->data['consumable_date'] = $header[0]->consumable_date;
        $this->data['status'] = $header[0]->status;
        $this->data['consumable_number'] = $header[0]->consumable_number;



        $tablelines = \DB::table('i_consumable_lines_t')->select('i_consumable_lines_t.*', 'm_products_t.concatenated_product', 'm_products_t.product_code', 'm_subinventory_t.subinventory_name', 'm_sublocators_t.locator_code', 'f_account_structure_t.concatenated_segments')
            ->leftjoin('m_products_t', 'm_products_t.product_id', 'i_consumable_lines_t.product_id')
            ->leftjoin('f_account_structure_t', 'f_account_structure_t.f_account_structure_id', 'i_consumable_lines_t.accounts_structure_id')
            ->leftjoin('m_subinventory_t', 'm_subinventory_t.subinventory_id', 'i_consumable_lines_t.subinventory')
            ->leftjoin('m_sublocators_t', 'm_sublocators_t.sublocator_id', 'i_consumable_lines_t.sublocator')->where('consumable_hdr_id', $id)->get();
        $this->data['linesdata'] = $tablelines;

        return view("consumable.view", $this->data);
    }




    /** * Remove the specified resource from storage.
     */
    public function destroy($id = null)
    {
        /*check columns with table whether the id used there*/
        $column = array('machine_id', 'machine_hdr_id');
        $table = array('w_machine_equipments_hdr_t', 'w_jobcard_hdr_t'); /*end*/
        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $id)->get();
            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }
        if ($j == 0) {
            Machine::destroy($id);/*purpose to delete the record using model with id*/
            $query = \DB::table('w_machine_lines_t')->where('machine_hdr_id', $id)->delete();
            /**Auditlog**/
            $this->auditlog($id, "machine", "Delete", '', "w_machine_hdr_t");
        }
        return $j;
    }
    /* purpose:to check whether data used in anywhere and if used it should be read only*/



    public function editcheck($id = null)
    {
        $column = array('machine_id', 'machine_hdr_id');
        $table = array('w_machine_equipments_hdr_t', 'w_jobcard_hdr_t');
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

    /* purpose:to primary key in table*/
    function findPrimarykey($table)
    {
        $primaryKey = '';
        foreach (\DB::select("show columns from " . $table . " where extra like '%auto_increment%'") as $key) {
            $primaryKey = $key->Field;
        }
        return $primaryKey;
    }
    /*end*/
    /* purpose:to check duplicate name*/
    public function machinenamechk(Request $request)
    {
        $machine_hdr_id = $_GET['edit_id'];

        if ($machine_hdr_id == '') {
            $whereData = [['machine_name', $_GET['machine_name']]];
            $machine_name = \DB::table('w_machine_hdr_t')->where($whereData)->get();
        } else {
            $whereData = [['machine_name', $_GET['machine_name']], ['machine_hdr_id', '!=', $machine_hdr_id]];
            $machine_name = \DB::table('w_machine_hdr_t')->where($whereData)->get();
        }

        if (count($machine_name) > 0)
            return 1;
        else
            return 0;
    }
    /*end*/

    public function getinventlocator()
    {
        $product_id = $_GET['product'];
        $batch_number = $_GET['batch'];
        $sql = \DB::select("select round(sum(i_qoh_detail_t.qoh_trx_qty),2) as qoh,i_qoh_detail_t.* FROM `i_qoh_detail_t` left join m_subinventory_t on(m_subinventory_t.subinventory_id=i_qoh_detail_t.subinventory_id) WHERE product_id='$product_id' and batch_number='$batch_number' group by m_subinventory_t.subinventory_id order by qoh desc");
        $decimal = \Session::get('decimal');
        $data['qoh'] = number_format($sql[0]->qoh, $decimal);
        //dd($data['qoh']);

        $data['subinventory_id'] = $sql[0]->subinventory_id;
        $data['sublocator_id'] = $sql[0]->locator_id;
        return $data;
    }

    public function productbatchno()
    {
        $id = $_GET['product_id'];
        $compy = \Session::get('companyid');
        $data = \DB::select("select * from (SELECT round(sum(qoh_trx_qty),2) as qty,batch_number FROM `i_qoh_detail_t` WHERE product_id='$id' and company_id='$compy'  group by batch_number order by qoh_detail_id asc)f where f.qty>0");
        $options = "<option>--Please Select--</option>";
        foreach ($data as $val) {
            $options .= "<option value='" . $val->batch_number . "'>" . $val->batch_number . "</option>";
        }
        return $options;
    }

    public function consumablereportindex(Request $request)
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

        $this->data['pageMethod'] = \Request::Route()->getName();

        return view('consumable.consumablereport', $this->data);
    }
    public function getconsumablereportdata(Request $request)
    {

        $wh = '';
        $wh1 = '';


        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');
        if ($groupname == '1' || $groupname == 'Admin') {
            $wh .= 'and  i_consumable_hdr_t.company_id=' . $compy;
        } else {
            $wh .= 'and  i_consumable_hdr_t.company_id=' . $compy . ' and i_consumable_hdr_t.location_id=' . $loc;
        }

        $sql = "SELECT
    i_consumable_hdr_t.*,
    tb_users.username,
    tb_users.first_name,
    approve_usr.first_name as approver_name,
    m_subinventory_t.subinventory_name, 
    m_sublocators_t.locator_name,
    m_products_t.concatenated_product,
    f_account_structure_t.concatenated_segments,
    i_consumable_lines_t.comments,
    i_consumable_lines_t.batch_no,
    i_consumable_lines_t.qty
FROM
    i_consumable_hdr_t
LEFT JOIN tb_users ON
    (
        tb_users.id = i_consumable_hdr_t.created_by
    )
    LEFT JOIN tb_users as approve_usr ON
    (
        approve_usr.id = i_consumable_hdr_t.last_updated_by
    )
LEFT JOIN i_consumable_lines_t ON i_consumable_hdr_t.consumable_hdr_id = i_consumable_lines_t.consumable_hdr_id
LEFT JOIN m_subinventory_t ON i_consumable_lines_t.subinventory = m_subinventory_t.subinventory_id
LEFT JOIN m_sublocators_t ON i_consumable_lines_t.sublocator = m_sublocators_t.sublocator_id
LEFT JOIN m_products_t ON i_consumable_lines_t.product_id = m_products_t.product_id
LEFT JOIN f_account_structure_t ON i_consumable_lines_t.accounts_structure_id = f_account_structure_t.f_account_structure_id
WHERE
    1 = 1 and i_consumable_hdr_t.consumable_date BETWEEN '$start_date' and '$end_date' $wh $wh1 ORDER BY i_consumable_hdr_t.consumable_hdr_id DESC";

        $result = \DB::select($sql);

        return DataTables::of($result)->make(true);
    }


    public function schemesreportindex(Request $request)
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

        $this->data['pageMethod'] = \Request::Route()->getName();
        //dd($this->data['pageMethod']);

        return view('consumable.schemesreport', $this->data);
    }

    public function getschemesreportdata(Request $request)
    {

        $wh = '';
        $wh1 = '';

        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';


        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');
        if ($groupname == '1' || $groupname == 'Admin') {
            $wh .= 'and  s_schemes_hdr_t.company_id=' . $compy;
        } else {
            $wh .= 'and  s_schemes_hdr_t.company_id=' . $compy . ' and s_schemes_hdr_t.location_id=' . $loc;
        }


        $sql = "SELECT
	s_schemes_hdr_t.schemes_hdr_id,
	s_schemes_hdr_t.savestatus,
    s_schemes_hdr_t.approvestatus,
    s_schemes_hdr_t.schemes_name as scheme_name,
    s_schemes_hdr_t.start_date as valid_from,
    s_schemes_hdr_t.end_date as valid_till,
    s_schemes_hdr_t.scheme_type as scheme_type,
    s_schemes_hdr_t.active as status,
    tb_users.username as emp_id,
    tb_users.first_name as creater_name,
    approve_usr.first_name as level1_approver_name,
    approve_usr2.first_name as level2_approver_name,
    pri_prod.concatenated_product as primary_product,
    free_prod.concatenated_product as free_product,
    s_schemes_lines_t.scheme_base as base,
    s_schemes_lines_t.schemes_type as type,
    s_schemes_lines_t.scheme_base_value_from as min_qty,
    s_schemes_lines_t.schemes_type_value as free_qty,
    s_schemes_lines_t.comments as remarks
    
FROM
    s_schemes_hdr_t
LEFT JOIN tb_users ON
    (
        tb_users.id = s_schemes_hdr_t.created_by
    )
    LEFT JOIN tb_users as approve_usr ON
    (
        approve_usr.id = s_schemes_hdr_t.level1approver
    ) and approve_usr.id != 0
    LEFT JOIN tb_users as approve_usr2 ON
    (
        approve_usr2.id = s_schemes_hdr_t.level2approver
    ) and approve_usr2.id != 0
LEFT JOIN s_schemes_lines_t ON s_schemes_hdr_t.schemes_hdr_id = s_schemes_lines_t.schemes_hdr_id
LEFT JOIN m_products_t as pri_prod ON s_schemes_lines_t.product_id = pri_prod.product_id
LEFT JOIN m_products_t as free_prod ON s_schemes_lines_t.line_pdt_id = free_prod.product_id
WHERE
    1 = 1 $wh $wh1 AND s_schemes_hdr_t.created_at BETWEEN '$start_date' and '$end_date' ORDER BY s_schemes_hdr_t.schemes_hdr_id DESC";


        $result = \DB::select($sql);

        return DataTables::of($result)->make(true);
    }
}
