<?php

namespace App\Http\Controllers;

use App\contraentry;
use App\Paymentdetails;
use App\Receiptdetails;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;

class ContraentryController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->table1 = "s_receipts_t";
        $this->table2 = "p_payments_t";
        $this->model = new contraentry();
        $this->model1 = new Paymentdetails();
        $this->model2 = new Receiptdetails();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['urlmenu'] = $this->indexs();
    }

    /* Purpose for Index Page*/
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

        $this->data['urlname'] = \Request::route()->getName();
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
        $this->data['from_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
        $this->data['to_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
        $this->data['to_bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");
        $this->data['from_bank_id'] = $this->jcustomselect('f_bank_account_hdr_t', 'bank_account_hdr_id', 'bank_name', '', "and bank_source='Company Account'");
        $bank = \DB::select('select * from f_bank_account_hdr_t where bank_source="Company Account"');
        $bnk = "<option value=''>-- Please Select --</option>";
        $bnk .= "<option value='9999' >Cash</option>";
        foreach ($bank as $key => $value) {
            $bnk .= "<option value='" . $value->bank_account_hdr_id . "' >" . $value->bank_name . "</option>";
        }
        $this->data['from_bank_id'] = $bnk;
        $this->data['to_bank_id'] = $bnk;
        $sesdate = \Session::get('p_date_format');
        $this->data['contra_date'] = date($sesdate);
        $table = \DB::table('f_account_contraentry_t')->get();
        $this->data['datas'] = json_encode($table);
        return view('contraentry.form', $this->data);

    }
    /* end */

    /* Purpose for Display JQgrid Function*/

    public function contraentrydata($type = null)
    {

        $wh = '';

        $org = \Session::get('organization');
        $com = \Session::get('companyid');
        $loc = \Session::get('location');


        $SQL = "select * from (SELECT f_account_contraentry_t.* ,facc.concatenated_segments as fromaccount,facc1.concatenated_segments as toaccount,tb_users.first_name,tb_users.id as created_id FROM f_account_contraentry_t left join tb_users on tb_users.id =f_account_contraentry_t.created_by left join 	f_account_structure_t as facc on(facc.f_account_structure_id=f_account_contraentry_t.from_account_id) left join f_account_structure_t as facc1 on(facc1.f_account_structure_id=f_account_contraentry_t.to_account_id) where 1=1 and f_account_contraentry_t.company_id=$com) as t where 1=1 $wh ORDER BY t.contraentry_id DESC";

        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }

    /* end */

    /* Purpose for Save Function*/

    public function save(Request $request)
    {

        $contraentry_id = $request->input('contraentry_id');
        if ($_POST['contraentry_id'] == "") {
            $seqno = $this->Seqno('CE', 'f_account_contraentry_t', '');

        } else {
            $seqno = $_POST['contraentry_no'];
        }

        \DB::beginTransaction();
        try {
            if ($contraentry_id == '') {
                $contraentry = new contraentry();
                $contraentry->company_id = \Session::get('companyid');
                $contraentry->location_id = \Session::get('location');
                $contraentry->created_by = $_POST['created_by'];
                $contraentry->contraentry_no = $seqno;
                $contraentry->contra_date = $_POST['contra_date'];
                $contraentry->reference_no = $_POST['reference_no'];
                $contraentry->remarks = $_POST['remarks'];
                $contraentry->payment_type_id = $_POST['payment_type_id'];
                $contraentry->cheque_no = $_POST['cheque_no'];
                $contraentry->favouring_name = $_POST['favouring_name'];
                $contraentry->cheque_date = date('Y-m-d', strtotime($_POST['cheque_date']));
                $contraentry->from_account_id = $_POST['from_account_id'];
                $contraentry->to_account_id = $_POST['to_account_id'];
                $contraentry->to_bank_id = $_POST['to_bank_id'];
                $contraentry->from_bank_id = $_POST['from_bank_id'];
                if (isset($_POST['from_account_no'])) {
                    $contraentry->from_account_no = $_POST['from_account_no'];
                } else {
                    $contraentry->from_account_no = "";
                }
                if (isset($_POST['to_account_no'])) {
                    $contraentry->to_account_no = $_POST['to_account_no'];
                } else {
                    $contraentry->to_account_no = "";
                }
                $contraentry->amount = $_POST['amount'];

                $contraentry->save();
                $contraentry_id = \DB::getPdo()->lastInsertId();

                if ($_POST['from_account_id'] != '') {
                    if ($_POST['from_bank_id'] != '9999') {
                        $Paymentdetails = new Paymentdetails();

                        $seqno1 = $this->Seqnoe('PMT-', 'p_payments_t', '', 'payment_count');
                        $Paymentdetails->company_id = \Session::get('companyid');
                        $Paymentdetails->location_id = \Session::get('location');
                        $Paymentdetails['created_by'] = \Session::get('id');
                        $Paymentdetails['created_at'] = date('Y-m-d H:i:s');
                        $Paymentdetails['last_updated_by'] = \Session::get('id');
                        $Paymentdetails['updated_at'] = date('Y-m-d H:i:s');
                        $Paymentdetails->created_by = $_POST['created_by'];
                        $Paymentdetails->payment_number = $seqno1[0];
                        $Paymentdetails->payment_count = $seqno1[1];
                        $Paymentdetails->payment_date = $_POST['contra_date'];
                        $Paymentdetails->payment_type_id = $_POST['payment_type_id'];
                        $Paymentdetails->cheque_no = $_POST['cheque_no'];
                        $Paymentdetails->cheque_date = date('Y-m-d', strtotime($_POST['cheque_date']));
                        $Paymentdetails->payment_reference = $_POST['reference_no'];
                        $Paymentdetails->reference_id = $contraentry_id;
                        $Paymentdetails->payment_source = 'Contraentry';
                        $Paymentdetails->remarks = $_POST['remarks'];
                        $Paymentdetails->bank_id = $_POST['from_bank_id'];
                        if (isset($_POST['from_account_no'])) {
                            $Paymentdetails->account_no = $_POST['from_account_no'];
                        } else {
                            $Paymentdetails->account_no = "";
                        }
                        $Paymentdetails->account_code_id = $_POST['from_account_id'];
                        $Paymentdetails->payment_amount = $_POST['amount'];
                        $Paymentdetails->save();
                        // dd($Paymentdetails);             
                    }
                }

                if ($_POST['to_account_id'] != '') {
                    if ($_POST['to_bank_id'] != '9999') {
                        $Receiptdetails = new Receiptdetails();

                        $seqno2 = $this->Seqnoe('RCPT-', 's_receipts_t', '', 'receipt_count');

                        $Receiptdetails->company_id = \Session::get('companyid');
                        $Receiptdetails->location_id = \Session::get('location');
                        $Receiptdetails['created_by'] = \Session::get('id');
                        $Receiptdetails['created_at'] = date('Y-m-d H:i:s');
                        $Receiptdetails['last_updated_by'] = \Session::get('id');
                        $Receiptdetails['updated_at'] = date('Y-m-d H:i:s');
                        $Receiptdetails->created_by = $_POST['created_by'];
                        $Receiptdetails->created_by = $_POST['created_by'];
                        $Receiptdetails->receipt_number = $seqno2[0];
                        $Receiptdetails->receipt_count = $seqno2[1];
                        $Receiptdetails->receipt_date = $_POST['contra_date'];
                        $Receiptdetails->receipt_type_id = $_POST['payment_type_id'];
                        $Receiptdetails->cheque_no = $_POST['cheque_no'];
                        $Receiptdetails->cheque_date = date('Y-m-d', strtotime($_POST['cheque_date']));
                        $Receiptdetails->receipt_reference = $contraentry_id;
                        $Receiptdetails->remarks = $_POST['remarks'];
                        $Receiptdetails->receipt_source = 'Contraentry';
                        $Receiptdetails->bank_id = $_POST['to_bank_id'];
                        if (isset($_POST['to_account_no'])) {
                            $Receiptdetails->account_no = $_POST['to_account_no'];
                        } else {
                            $Receiptdetails->account_no = "";
                        }
                        $Receiptdetails->account_code_id = $_POST['to_account_id'];
                        $Receiptdetails->receipt_amount = $_POST['amount'];
                        $Receiptdetails->save();

                    }
                }

                /*Journal Header Insert*/
                $journal_name = "Contra Entry-" . $seqno;
                $date = $_POST['contra_date'];
                $org = \Session::get('organization');
                $loc = \Session::get('location');
                $compy = \Session::get('companyid');
                $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$journal_name','CONTRA ENTRY','$date','$contraentry_id','APPROVED','$compy','$loc','$org')");
                $jid = DB::getPdo()->lastInsertId();
                $journal_lines_data1 = array();
                $journal_lines_data1['journal_entry_id'] = $jid;
                $journal_lines_data1['journal_date'] = $date;
                $journal_lines_data1['reference_source'] = "";
                $journal_lines_data1['reference_id'] = "";
                $journal_lines_data1['account_id'] = $_POST['to_account_id'];
                $journal_lines_data1['debit_amount'] = $_POST['amount'];
                $journal_lines_data1['credit_amount'] = "";
                $journal_lines_data1['line_no'] = 2;
                $journal_lines_data1['created_by'] = \Session::get('id');
                $journal_lines_data1['created_at'] = date('Y-m-d H:i:s');
                $journal_lines_data1['last_updated_by'] = \Session::get('id');
                $journal_lines_data1['updated_at'] = date('Y-m-d H:i:s');
                ;
                $journal_lines_data1['location_id'] = \Session::get('companyid');
                $journal_lines_data1['company_id'] = \Session::get('location');
                \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data1);

                $journal_lines_data = array();
                $journal_lines_data['journal_entry_id'] = $jid;
                $journal_lines_data['journal_date'] = $date;
                $journal_lines_data['reference_source'] = "";
                $journal_lines_data['reference_id'] = "";
                $journal_lines_data['account_id'] = $_POST['from_account_id'];
                $journal_lines_data['debit_amount'] = '';
                $journal_lines_data['credit_amount'] = $_POST['amount'];
                $journal_lines_data['line_no'] = 1;
                $journal_lines_data['created_by'] = \Session::get('id');
                $journal_lines_data['created_at'] = date('Y-m-d H:i:s');
                $journal_lines_data['last_updated_by'] = \Session::get('id');
                $journal_lines_data['updated_at'] = date('Y-m-d H:i:s');
                $journal_lines_data['location_id'] = \Session::get('companyid');
                $journal_lines_data['company_id'] = \Session::get('location');
                \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);

                $action = "Create";
                /**Auditlog**/
                $this->auditlog($contraentry_id, "contraentry", $action, $_POST, "f_account_contraentry_t");
            } else {
                $action = "Edit";
                $contraentry_id = $_POST['contraentry_id'];
                contraentry::find($contraentry_id)->update($_POST);
                $this->auditlog($contraentry_id, "contraentry", $action, $_POST, "f_account_contraentry_t");

            }

            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Contra Entry Saved', 'id' => $contraentry_id));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }


    }



}
