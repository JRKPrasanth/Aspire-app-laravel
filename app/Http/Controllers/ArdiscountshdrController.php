<?php

namespace App\Http\Controllers;
use App\ardiscountshdr;
use App\Discountlines;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DB;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\DataTables;

class ArdiscountshdrController extends Controller
{
    public $module = "ardiscountshdr";
    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->model = new Ardiscountshdr();
        $this->model = new Ardiscountshdr;
        $this->submodel = new Discountlines;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->table = "m_discounts_hdr_t";
        $this->subtable = "m_discounts_lines_t";
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

        $table = \DB::table('m_discounts_hdr_t')->get();
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
        $this->data['datas'] = $table;
        $this->data['start_date'] = date('Y-m-d');
        return view('ardiscountshdr.table', $this->data);

    }


    public function getGridData()
    {
        $wh = '';

        $comp = \Session::get('companyid');

        $SQL = "SELECT m_discounts_hdr_t.*,tb_users.username FROM m_discounts_hdr_t left join tb_users on tb_users.id=m_discounts_hdr_t.created_by where 1=1  and m_discounts_hdr_t.company_id=$comp $wh";


        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);

    }

    public function create($id = null)
    {
        $maindate = $this->dateform('date');
        $this->data = array('pageModule' => 'ardiscountshdr', 'pageUrl' => url('ardiscountshdr'));

        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'ardiscountshdr')->get();

        $active = $this->data['enabled_columns'];

        $actived = $active[0]->active;
        $this->data['active'] = $actived;



        if ($id == '0') {
            $this->data['row'] = (object) array();
            $this->data['row']->discount_name = "";
            $this->data['row']->ar_discount_hdr_id = "";
            $today = date("d-m-Y");
            $newDate = date("d-m-Y", strtotime($today));
            $this->data['row']->start_date = $newDate;
            $this->data['row']->end_date = "";
            $this->data['row']->default_discount_amount = "";
            $this->data['row']->discount_currency_id = "";
            $this->data['row']->discount_applylevel = "";
            $this->data['row']->active = "";

            $this->data['row']->save_status = "";
            $this->data['row']->discount_at_partialpayment = "";


            $this->data['row']->remarks = "";
            $this->data['maindate'] = $maindate;
            $this->data['linedata'] = array();



            $this->data['discount_currency_id'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', '');
        } else {

            $table = \DB::table('m_discounts_hdr_t')->where('ar_discount_hdr_id', $id)->get();
            $this->data['row'] = $table[0];



            $this->data['row']->ar_discount_hdr_id = $id;
            $table = \DB::table('m_discounts_lines_t')->where('ar_discount_hdr_id', $id)->get();

            $this->data['maindate'] = $maindate;

            $this->data['linedata'] = $table;
            $table = \DB::table('m_discounts_hdr_t')->where('ar_discount_hdr_id', $id)->get();


            $this->data['discount_currency_id'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $table[0]->discount_currency_id);
        }

        //dd($this->data);
        return view('ardiscountshdr.form', $this->data);
    }

    /* Start Save data function */
    public function save(Request $request)
    {

			$id='';
			$form = $request->all();
			$dataupload ="";
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');

        \DB::beginTransaction();
        try {
            $id = $this->model->insertRow($data);

            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Saved Successfully', 'id' => $id));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');

            \DB::rollback();
            //dd($dbCode);
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }
    /* End Save data function */

    /* Start Save data function */
    public function save_old(Request $request)
    {
        $ardiscountshdr = new Ardiscountshdr();
        $this->modelname = new Ardiscountshdr();
        $discolines = new Discountlines();
        $this->modelline = new Discountlines();
        $primary = self::findPrimarykey('m_discounts_hdr_t');
        $primaryline = self::findPrimarykey('m_discounts_lines_t');
        $ardiscountshdr->discount_name = $_POST['discount_name'];
        $ardiscountshdr->discount_applylevel = $_POST['discount_applylevel'];
        $ardiscountshdr->discount_currency_id = $_POST['discount_currency_id'];
        $ardiscountshdr->default_discount_amount = $_POST['default_discount_amount'];
        $ardiscountshdr->source_type_id = $_POST['source_type_id'];
        $ardiscountshdr->active = $_POST['active'];
        $ardiscountshdr->start_date = $_POST['start_date'];
        $ardiscountshdr->end_date = $_POST['end_date'];
        $ardiscountshdr->remarks = $_POST['remarks'];
        $id = $this->insertData($this->modelname, $primary, $ardiscountshdr, $_POST['ar_discount_hdr_id']);
        $oldid = \DB::table('m_discounts_lines_t')->where('ar_discount_hdr_id', $id)->get();


        if ($oldid->isEmpty()) {
            for ($i = 0; $i < count($_POST['counter']); $i++) {

                $data['ar_discount_hdr_id'] = $id;
                $data['ar_discount_line_id'] = $_POST['bulk_ar_discount_line_id'][$i] ? $_POST['bulk_ar_discount_line_id'][$i] : 0;
                $data['discount_percentage'] = $_POST['bulk_discount_percentage'][$i];
                $data['discount_days'] = $_POST['bulk_discount_days'][$i];
                $data['comments'] = $_POST['bulk_comments'][$i];
                \DB::table('m_discounts_lines_t')->insert($data);

            }
            return response()->json(array('status' => 'success', 'status' => 'edit', 'message' => 'Discount Saved', 'id' => $id));
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
                    $data['ar_discount_hdr_id'] = $id;
                    $data['ar_discount_line_id'] = $_POST['bulk_ar_discount_line_id'][$i] ? $_POST['bulk_ar_discount_line_id'][$i] : 0;
                    $data['discount_percentage'] = $_POST['bulk_discount_percentage'][$i];
                    $data['discount_days'] = $_POST['bulk_discount_days'][$i];
                    $data['comments'] = $_POST['bulk_comments'][$i];
                    //dd($existingId);
                    if ($data['ar_discount_line_id'] = $existingId[$i]) {
                        $this->modelline::find($data['ar_discount_line_id'])->update($data);
                    } else {
                        //  dd('sad');
                        \DB::table('m_discounts_lines_t')->insert($data);
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
                    \DB::table('m_discounts_lines_t')->where('ar_discount_line_id', $val)->delete();
                }
                for ($i = 0; $i < count($oldIds); $i++) {
                    $data['ar_discount_hdr_id'] = $id;
                    $data['ar_discount_line_id'] = $_POST['bulk_ar_discount_line_id'][$i] ? $_POST['bulk_ar_discount_line_id'][$i] : 0;
                    $data['discount_percentage'] = $_POST['bulk_discount_percentage'][$i];
                    $data['discount_days'] = $_POST['bulk_discount_days'][$i];
                    $data['comments'] = $_POST['bulk_comments'][$i];

                    $this->modelline::find($data['ar_discount_line_id'])->update($data);
                }

            }
            return response()->json(array('status' => 'success', 'status' => 'update', 'message' => 'Discount Update', 'id' => $id));

        }

        $table = \DB::table('m_discounts_hdr_t')->get();
        $this->data['datas'] = json_encode($table);

        return view('ardiscountshdr.table', $this->data);

    }
    /* End Save data function */



    /* purpose for set value in edit*/
    public function edit(Request $request, $id)
    {

        $this->data = array('pageModule' => 'ardiscountshdr', 'pageUrl' => url('ardiscountshdr'));
        $maindate = $this->dateform('date');
        $this->data['id'] = $id;
        $this->data['maindate'] = $maindate;
        $table = \DB::table('m_discounts_hdr_t')->where('ar_discount_hdr_id', $id)->get();
        $this->data['row'] = $table[0];
        //$this->data['row'] = $table[0]->discount_at_partialpayment;
        $this->data['actived'] = $table[0]->discount_at_partialpayment;


        $this->data['enabled_columns'] = \DB::table('m_column_permission_t')->where('module_name', 'ardiscountshdr')->get();
        $active = $this->data['enabled_columns'];

        $actived = $active[0]->active;
        $this->data['active'] = $actived;

        $tablelines = \DB::table('m_discounts_lines_t')->where('ar_discount_hdr_id', $id)->get();
        $this->data['linedata'] = $tablelines;



        $this->data['discount_currency_id'] = $this->jCombo('f_account_currency_t', 'account_currency_id', 'currency_code', $table[0]->discount_currency_id);
        return view('ardiscountshdr.form', $this->data);
    }
    /* End */

    /* Delete function */
    public function delete(Request $request, $id = null)
    {

        $column = array('discount_id', 'discount_id');
        $table = array('s_salesorder_hdr_t', 's_invoice_hdr_t');
        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $id)->get();

            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }

        if ($j == 0) {
            /**Auditlog**/
            $action = "Delete";
            $this->auditlog($id, "Discount", $action, $id, "m_discounts_hdr_t");
            $query = \DB::table('m_discounts_hdr_t')->where('ar_discount_hdr_id', $id)->delete();
        }

        return $j;

    }/* End function */

    /* Edit function */
    public function editdata($id)
    {
        $column = array('discount_id', 'discount_id');
        $table = array('s_salesorder_hdr_t', 's_invoice_hdr_t');
        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $id)->get();

            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }

        return $j;

    }/* Edit function */


    /* purpose for Display hdr & Lines View function*/
    public function show(Ardiscountshdr $ardiscountshdr, $id = null)
    {
        $this->data['columns'] = \DB::connection()->getSchemaBuilder()->getColumnListing("m_discounts_hdr_t");
        $this->data['values'] = Ardiscountshdr::find($id);
        $curr = $this->data['values'];

        $this->data['active'] = $curr->active;

        $linetable = \DB::table('m_discounts_lines_t')->where('ar_discount_hdr_id', $id)->get();

        $id = $linetable[0]->ar_discount_hdr_id;

        $currency = \DB::table('f_account_currency_t')->where('account_currency_id', $curr->discount_currency_id)->get();
        if (count($currency) > 0) {
            $this->data['currency'] = $currency[0]->currency_code;
        } else {
            $this->data['currency'] = '';
        }




        $this->data['linesvalue'] = $linetable;
        return view('ardiscountshdr.view', $this->data);
    }

    function findPrimarykey($table)
    {
        $primaryKey = '';
        foreach (\DB::select("show columns from " . $table . " where extra like '%auto_increment%'") as $key) {

            $primaryKey = $key->Field;
        }
        return $primaryKey;
    }

    public function discountchkname(Request $request)
    {

        $ar_discount_hdr_id = $_GET['edit_id']; //dd($payment_term_id);

        if ($ar_discount_hdr_id == '') {
            $whereData = [['discount_name', $_GET['discount_name']], ['default_discount_amount', $_GET['default_discount_amount']]];
            $department = DB::table('m_discounts_hdr_t')->where($whereData)->get();
        } else {
            $whereData = [['discount_name', $_GET['discount_name']], ['default_discount_amount', $_GET['default_discount_amount']], ['ar_discount_hdr_id', '!=', $ar_discount_hdr_id]];
            $department = DB::table('m_discounts_hdr_t')->where($whereData)->get();
        }

        if (count($department) > 0)
            return 1;
        else
            return 0;
    }


}
