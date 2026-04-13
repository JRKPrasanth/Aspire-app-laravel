<?php

namespace App\Http\Controllers;

use App\Bankcheque;
use App\Bankchequelines;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BankchequeController extends Controller
{
    public function __construct()
    {
        $this->data = array();
        $this->table = "f_bank_cheque_hdr_t";
        $this->subtable = "f_bank_cheque_lines_t";
        $this->pageModule = "bankcheque";
        $this->model = new Bankcheque;
        $this->submodel = new Bankchequelines;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'bankcheque',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu'] = $this->indexs();
        $this->modelname = new Bankcheque();
        $this->data['pageFormtype'] = 'ajax';
    }


    public function getBankchequeData()
    {
        $wh = '';

        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $wh .= 'and f_bank_account_hdr_t.company_id=' . $compy;

        $SQL = "SELECT 
f_bank_account_hdr_t.bank_account_hdr_id,
f_bank_cheque_lines_t.bank_cheque_line_id,
f_bank_account_hdr_t.bank_name,
f_bank_account_lines_t.bank_account_line_id,
f_bank_account_lines_t.branch_name,
f_bank_account_lines_t.account_number,
tb_users.first_name
FROM f_bank_account_hdr_t
left JOIN f_bank_account_lines_t  ON (f_bank_account_lines_t.bank_account_hdr_id=f_bank_account_hdr_t.bank_account_hdr_id)
left JOIN f_bank_cheque_lines_t  ON (f_bank_account_lines_t.bank_account_hdr_id=f_bank_cheque_lines_t.bank_cheque_hdr_id)
left join tb_users on (tb_users.id =f_bank_account_hdr_t.created_by)
where 1=1  $wh  group by f_bank_account_hdr_t.bank_account_hdr_id";

        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);
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

        $table = \DB::table('f_bank_account_hdr_t')->get();
        $this->data['datas'] = $table;
        return view('bankcheque.table', $this->data);
    }


    /*Karthigaa Purpose For Create mode*/
    public function create($id = null, $ids = null)
    {
        //dd($ids);
        $data = $this->model::find($id);
        $bankacc = \DB::table('f_bank_account_hdr_t')->leftjoin('f_bank_account_lines_t', 'f_bank_account_lines_t.bank_account_hdr_id', '=', 'f_bank_account_hdr_t.bank_account_hdr_id')
            ->where('bank_account_line_id', $ids)->get();
        $bankaccid = $bankacc[0]->bank_account_hdr_id;
        $this->data['bank_cheque_hdr_id'] = $ids;
        $this->data['bank_id'] = $this->jCombo('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', $bankacc[0]->bank_account_hdr_id);
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
        $this->data['datas'] = $data;
        $this->data['id'] = $id;
        $this->data['linedata'] = array();
        $bankacclines = \DB::table('f_bank_cheque_lines_t')->where('bank_cheque_hdr_id', $id)->get();
        $this->data['linedata'] = $bankacclines;
        //            dd($bankacclines);
        $this->data['bank_branch_id'] = $this->jCombo('f_bank_account_lines_t', 'bank_account_line_id', 'branch_name', $bankacc[0]->bank_account_line_id);
        $this->data['account_number'] = $this->jCombo('f_bank_account_lines_t', 'bank_account_line_id', 'account_number', $bankacc[0]->bank_account_line_id);

        return view('bankcheque.form', $this->data);
    }

    /* Karthigaa purpose for Save function */
    public function save(Request $request)
    {

			$id='';
			$form = $request->all();
			$dataupload ="";
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file','enable-masterdetail',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
			$lines_data = $this->validatePost($form, $this->subtable, 'lines');

        \DB::beginTransaction();
        try {
            $id = $this->model->insertRow($data);
            $lid = $this->submodel->subgridSave($lines_data, $id);
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Bank Cheque Saved Successfully', 'id' => $id, 'lid' => $lid));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }


    /*Karthigaa purpose for Display hdr & Lines View function*/
    public function show(request $request, $id = null)
    {
        if (isset($id)) {
            //          $vdata=\DB::table('f_bank_cheque_hdr_t')->leftjoin('f_bank_account_hdr_t','f_bank_account_hdr_t.bank_account_hdr_id','=','f_bank_cheque_hdr_t.bank_cheque_hdr_id')
//                                                  ->leftjoin('f_bank_account_lines_t','f_bank_account_lines_t.bank_account_line_id','=','f_bank_account_hdr_t.bank_account_hdr_id')
//                  ->where('bank_cheque_hdr_id',$id)->get();
            $vdata = \DB::table('f_bank_account_hdr_t')->leftjoin('f_bank_account_lines_t', 'f_bank_account_lines_t.bank_account_hdr_id', '=', 'f_bank_account_hdr_t.bank_account_hdr_id')
                ->where('f_bank_account_hdr_t.bank_account_hdr_id', $id)->get();
            //          dd($vdata);
            if ($vdata->isNotEmpty()) {
                $this->data['bank_name'] = $vdata[0]->bank_name;
                $this->data['branch_name'] = $vdata[0]->branch_name;
                $this->data['account_number'] = $vdata[0]->account_number;
            } else {
                $this->data['bank_name'] = "";
                $this->data['branch_name'] = "";
                $this->data['account_number'] = "";
            }

            $vlinesdata = \DB::table('f_bank_cheque_lines_t')->where('bank_cheque_hdr_id', $id)->get();
            //          dd($vlinesdata);
            $this->data['vlinesdata'] = $vlinesdata;

            if ($vdata->isNotEmpty()) {
                $this->data['cheque_book_no'] = $vlinesdata[0]->cheque_book_no;
                $this->data['cheque_from_no'] = $vlinesdata[0]->cheque_from_no;
                $this->data['cheque_to_no'] = $vlinesdata[0]->cheque_to_no;
                $this->data['start_date'] = $vlinesdata[0]->start_date;
                $this->data['end_date'] = $vlinesdata[0]->end_date;
            } else {
                $this->data['cheque_from_no'] = "";
                $this->data['cheque_to_no'] = "";
                $this->data['cheque_book_no'] = "";
                $this->data['start_date'] = "";
                $this->data['end_date'] = "";
            }
            return view('bankcheque.view', $this->data);
        }
    }



}
