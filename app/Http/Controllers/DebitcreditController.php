<?php

namespace App\Http\Controllers;
use App\Debitcredit;
use App\Debitcreditlines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;

class DebitcreditController extends Controller
{
    public $module = "debitcreditnote";
    public function __construct()
    {
        $this->data = array();
        $this->table = "f_debitcredit_t";
        $this->subtable = "f_debitcredit_lines_t";
        $this->pageModule = \Request::route()->getName();
        $this->model = new Debitcredit;
        $this->submodel = new Debitcreditlines;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->table = "f_debitcredit_t";
        if ($this->data['pageMethod'] == "debitcreditapproval") {
            $this->data['status'] = "INITIATED";
        } else {
            $this->data['status'] = "";
        }

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
        $this->data['suppliername'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        $table = \DB::table('f_debitcredit_t')->get();
        $this->data['datas'] = $table;
        return view('debitcreditnote.table', $this->data);
    }


    public function getdebitcreditData()
    {

        $wh = '';
        if ($_GET['status'] != '') {
            $wh = " and  f_debitcredit_t.debitcredit_status='" . $_GET['status'] . "'";
            $wh .= $grid_data = $this->grid_statuscheck('f_debitcredit_t', 'debitcredit_date', 'debitcredit_status', '=', "'" . $_GET['status'] . "'");
        } else {
            $wh .= $grid_data = $this->grid_check('f_debitcredit_t', 'debitcredit_date');
        }


        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');
        if ($compy != 8) {
            if ($groupname == 'Superadmin' || $groupname == 'Admin') {
                $wh .= ' and  f_debitcredit_t.company_id=' . $compy;
            } else {
                $wh .= ' and  f_debitcredit_t.company_id=' . $compy . ' and f_debitcredit_t.location_id=' . $loc;
            }
        } else {
            $wh .= '';
        }


        $SQL = "SELECT f_debitcredit_t.debitcredit_id,
						m_company_t.company_name,
				        f_debitcredit_t.debitcredit_no,
                    f_debitcredit_t.debitcredit_date,
                    f_debitcredit_t.debitcredit_type,
                    f_debitcredit_t.source_type ,
                    f_debitcredit_t.reference_no ,
                    f_debitcredit_t.invoice_no ,
                    f_debitcredit_t.debitcredit_status ,
                    f_debitcredit_lines_t.remarks,
                    m_supplier_t.supplier_name as supplier_id,
                    m_customers_t.customer_name,
                    f_debitcredit_t.debitcredit_amount,
                    tb_users.username,
                    f_debitcredit_t.credit_taken,
                      CONCAT(LEFT(MONTHNAME(f_debitcredit_t.credit_date),3),'-',YEAR(f_debitcredit_t.credit_date))as credit_date
                    FROM f_debitcredit_t
                    LEFT JOIN m_company_t ON(m_company_t.company_id = f_debitcredit_t.company_id)
                    left join f_account_structure_t on(f_account_structure_t.f_account_structure_id=f_debitcredit_t.debitcredit_account_id) 
                    left join m_supplier_t on(m_supplier_t.supplier_id=f_debitcredit_t.supplier_id)
                    left join m_customers_t on(m_customers_t.customer_id=f_debitcredit_t.customer_id)
                    left join f_debitcredit_lines_t on(f_debitcredit_lines_t.debitcredit_id=f_debitcredit_t.debitcredit_id )
                    left join tb_users on (tb_users.id =f_debitcredit_t.created_by)
                    where 1=1 $wh group by f_debitcredit_t.debitcredit_id ORDER BY f_debitcredit_t.debitcredit_id DESC";

        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }

    public function create($id = null, $aprv = null)
    {
        $this->data['pageModule'] = \Request::route()->getName();
        $this->data['pageUrl'] = \Request::route()->getName();
        if ($id == null) {
            $this->data['row'] = (object) array();
            $this->data['row']->debitcredit_id = "";
            $this->data['row']->debitcredit_no = "";
            $this->data['row']->debitcredit_type = "";
            $this->data['row']->debitcredit_amount = "";
            $this->data['row']->debitcredit_status = "INITIATED";
            $this->data['row']->gst_code_id = "";
            $this->data['row']->source = "";
            $this->data['row']->source_type = "";
            $this->data['row']->debitcredit_date = date('Y-m-d');
            //$this->data['row']->debitcredit_date=$_GET['debitcredit_date'];
            $this->data['row']->tds_amount = "";
            $this->data['row']->concatenated_segments = "";
            $this->data['row']->reference_no = "";
            $this->data['row']->debitcredit_account_id = "";
            $this->data['row']->reference_id = "";
            $this->data['row']->remarks = "";
            $this->data['row']->round_off = "0";
            $this->data['invoice_no'] = "";
            $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', '');
            $this->data['customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_number|customer_name', '');
            $this->data['debitcredit_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
            $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', '');
            $this->data['gst_code_id'] = $this->jCombo('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', '', 'and classification_name="HSN"');
            $this->data['tax_group_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', '');
            // dd($this->data['tax_group_id']);
            $this->data['linedata'] = array();
            //   dd($this->data['linedata']);
        } else {
            $table = \DB::table('f_debitcredit_t')->where('debitcredit_id', $id)->get();
            $tablelines = \DB::table('f_debitcredit_lines_t')->where('debitcredit_id', $id)->get();
            $this->data['linedata'] = $tablelines;
            $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', $table[0]->supplier_id);
            $this->data['customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_number|customer_name', $table[0]->customer_id);
            $this->data['debitcredit_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $table[0]->debitcredit_account_id);
            $this->data['tds_account_id'] = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $table[0]->tds_account_id);
            if ($table[0]->debitcredit_type == "SUPPLIER") {
                $this->data['invoice_no'] = $this->jCombologin('p_po_invoice_hdr_t', 'po_invoice_id', 'bill_number', $table[0]->invoice_no);
            } else {
                $this->data['invoice_no'] = $this->jCombologin('s_invoice_hdr_t', 'invoice_hdr_id', 'invoice_number', $table[0]->invoice_no);
            }
            $this->data['row'] = $table[0];
            $this->data['row']->reference_id = "";
        }
        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->debitcredit_account_id = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $value->debitcredit_account_id);
                $this->data['linedata'][$key]->tax_group_id = $this->data['tax_group_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
                // dd($this->data['linedata'][$key]->tax_group_id); 
                $this->data['linedata'][$key]->gst_code_id = $this->data['gst_code_id'] = $this->jCombo('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->gst_code_id, 'and classification_name="HSN"');
            }
        }
        if ($aprv != "") {
            $this->data['aprvidenty'] = $aprv;
        } else {
            $this->data['aprvidenty'] = "";
        }
        return view('debitcreditnote.form', $this->data);
    }

    /* purpose for Save function*/
    public function save(Request $request)
    {

        $id = '';
        $form = $request->all();
        $dataupload = [];
        $form = $request->except([
            '_token',
            'form_config',
            'form_data_json',
            'savestatus',
            'submit_type',
            'choosefile',
            'existing_file',
            'debitcredit_date1',
            'debitcredit_date2',
        ]);
        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');
        $lines_data = $this->validatePost($form, $this->subtable, 'lines');
        \DB::beginTransaction();
            $data['balance_amount'] = $_POST['debitcredit_amount'];
        if ($_POST['debitcredit_status'] == "APPROVED") {
            if ($_POST['debitcredit_no'] == "") {
                if ($_POST['source_type'] == "CREDIT") {
                    $seqno = $this->Seqnoe('CR-', 'f_debitcredit_t', $_POST['debitcredit_id'], 'debitcredit_count');
                } else {
                    $seqno = $this->Seqnoe('DB-', 'f_debitcredit_t', $_POST['debitcredit_id'], 'debitcredit_count');
                }

                $data['debitcredit_no'] = $seqno[0];
                $data['debitcredit_count'] = $seqno[1];
            }
        }
        if ($_POST['source_type'] == "CREDIT") {
            $data['debitcredit_date'] = $_POST['debitcredit_date2'];
        } else {
            $data['debitcredit_date'] = $_POST['debitcredit_date1'];
        }

        try {


            if (!empty($_POST['debitcredit_id'])) {
                $id = $_POST['debitcredit_id'];

                // Update header record
                \DB::table($this->table)->where('debitcredit_id', $id)->update($data);

            } else {
                $id = $this->model->insertRow($data);
            }

            unset($lines_data['existing_file']);
            $lid = $this->submodel->subgridSave($lines_data, $id);


            foreach ($lid['id'] as $k => $v) {
                if ($request->hasfile('bulk_choosefile' . $k) && $_POST['bulk_existing_file'][$k] == '') {
                    foreach ($request->file('bulk_choosefile' . $k) as $file) {
                        $name = $file->getClientOriginalName();
                        $file->move(public_path() . '/Uploads/debitcredit/' . $v . '/', $name);
                        $dataupload[$v][] = $name;
                    }
                    $attachfile_name = json_encode($dataupload[$v]);
                    \DB::update("update f_debitcredit_lines_t set choosefile='" . $attachfile_name . "' where debitcredit_line_id=" . $v);

                } else if (isset($_POST['bulk_existing_file'][$k])) {
                    if ($_POST['bulk_existing_file'][$k] != '') {
                        $linefile = \DB::select("select * from f_debitcredit_lines_t where debitcredit_line_id=" . $v);
                        $filess = explode(",", $_POST['bulk_existing_file'][$k]);
                        //   dd($filess);
                        foreach ($request->file('bulk_choosefile' . $k) as $file) {
                            $name = $file->getClientOriginalName();
                            $file->move(public_path() . '/Uploads/debitcredit/' . $v . '/', $name);
                            $dataupload[$v][] = $name;
                            $filess[] = $name;
                        }
                        $upfile = json_encode($filess);
                        \DB::update("update f_debitcredit_lines_t set choosefile='" . $upfile . "' where debitcredit_line_id=" . $v);
                    } else {
                        \DB::update("update f_debitcredit_lines_t set choosefile='' where debitcredit_line_id=" . $v);
                    }

                }
            }

            /* Purpose For Journal Entry Insert*/
            if ($_POST['debitcredit_status'] == "APPROVED") {

                //   $debitcreditdate=$_POST['debitcredit_date'];
                if ($_POST['source_type'] == "CREDIT") {
                    $debitcreditdate = $_POST['debitcredit_date2'];
                } else {
                    $debitcreditdate = $_POST['debitcredit_date1'];
                }
                //   dd($debitcreditdate);
                $org = \Session::get('organization');
                $loc = \Session::get('location');
                $compy = \Session::get('companyid');
                $name = $data['debitcredit_no'];
                //Journal Header Insert
                if ($_POST['source_type'] == "CREDIT") {
                    $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$name','CREDIT','$debitcreditdate','$id','APPROVED','$compy','$loc','$org')");
                } else {
                    $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$name','DEBIT','$debitcreditdate','$id','APPROVED','$compy','$loc','$org')");
                }
                //    dd($journalhdr);
                $jid = DB::getPdo()->lastInsertId();
                
                if ($_POST['debitcredit_type'] == "SUPPLIER") {
                    $supplier_acc = \DB::table('m_supplier_t')->where('supplier_id', $_POST['supplier_id'])->get();
                    
                    $supplier_acc = $supplier_acc[0]->account_structure_id;
                    $sec_id = $_POST['supplier_id'];
                    $sec_name = "SUPPLIER";
                    /*deepika purpose:update debit & credit note in invoice*/
                    $invoice = \DB::table('p_po_invoice_hdr_t')->where('po_invoice_id', $_POST['invoice_no'])->get();
                   
                   $debit_amount = is_numeric($_POST['debitcredit_amount']) ? floatval($_POST['debitcredit_amount']) : 0;
                    if ($_POST['source_type'] == "DEBIT") {
                        $debitnote = $invoice[0]->debit_note + $debit_amount;
                         //dd("ss");
                        \DB::table('p_po_invoice_hdr_t')->where('po_invoice_id', $_POST['invoice_no'])->update(['debit_note' => $debitnote]);
                    } else {
                        $creditnote = $invoice[0]->credit_note + $debit_amount;
                        \DB::table('p_po_invoice_hdr_t')->where('po_invoice_id', $_POST['invoice_no'])->update(['credit_note' => $creditnote]);
                    }

                } elseif ($_POST['debitcredit_type'] == "CUSTOMER") {
                    $supplier_acc = \DB::table('m_customers_t')->where('customer_id', $_POST['customer_id'])->get();
                    $supplier_acc = $supplier_acc[0]->account_structure_id;
                    $sec_id = $_POST['customer_id'];
                    $sec_name = "CUSTOMER";
                    $debit_amount = is_numeric($_POST['debitcredit_amount']) ? floatval($_POST['debitcredit_amount']) : 0;
                    /*deepika purpose:update debit & credit note in invoice*/
                    $sinvoice = \DB::table('s_invoice_hdr_t')->where('invoice_hdr_id', $_POST['invoice_no'])->get();
                    if ($_POST['source_type'] == "DEBIT") {
                        $debitnote = $sinvoice[0]->debit_note + $debit_amount;
                        \DB::table('s_invoice_hdr_t')->where('invoice_hdr_id', $_POST['invoice_no'])->update(['debit_note' => $debitnote]);
                    } else {
                        $creditnote = $sinvoice[0]->credit_note + $debit_amount;
                        \DB::table('s_invoice_hdr_t')->where('invoice_hdr_id', $_POST['invoice_no'])->update(['credit_note' => $creditnote]);
                    }

                }

                //Journal Lines Insert

                if ($_POST['source_type'] == "DEBIT") {
                    $tkey = 0;
                   

                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['debitcredit_date1'];
                    $journal_lines_data[$tkey]['reference_source'] = $sec_name;
                    $journal_lines_data[$tkey]['reference_id'] = $sec_id;
                    $journal_lines_data[$tkey]['account_id'] = $supplier_acc;
                    $journal_lines_data[$tkey]['debit_amount'] = $_POST['debitcredit_amount'];
                    $journal_lines_data[$tkey]['credit_amount'] = '';
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    $tkey++;
                    foreach ($_POST['bulk_debitcredit_account_id'] as $key => $val) {
                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $_POST['debitcredit_date1'];
                        $journal_lines_data[$tkey]['reference_source'] = "";
                        $journal_lines_data[$tkey]['reference_id'] = '';
                        $journal_lines_data[$tkey]['account_id'] = $val;
                        $journal_lines_data[$tkey]['debit_amount'] = '';
                        $journal_lines_data[$tkey]['credit_amount'] = $_POST['bulk_debitcredit_line_amount'][$key];
                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                        ;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        $total = 0;
                        $tax_details = \DB::table('m_tax_group_lines_t')->join('f_tax_code_t', 'f_tax_code_t.tax_code_id', '=', 'm_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*', 'f_tax_code_t.tax_code_percent')->where('m_tax_group_lines_t.tax_group_id', $_POST['bulk_tax_group_id'][$key])->get();

                        foreach ($tax_details as $taxval) {
                            $rate = $_POST['bulk_tax_amount'][$key];
                            $count = count($tax_details);
                            if ($rate > 0) {
                                $tkey++;
                                $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                                $journal_lines_data[$tkey]['journal_date'] = $_POST['debitcredit_date1'];
                                $journal_lines_data[$tkey]['reference_source'] = "";
                                $journal_lines_data[$tkey]['reference_id'] = '';
                                $journal_lines_data[$tkey]['account_id'] = $taxval->input_tax_account_id;

                                $rate = $_POST['bulk_tax_amount'][$key];
                                $rate = round($rate / $count, 2);

                                $journal_lines_data[$tkey]['debit_amount'] = '';
                                $journal_lines_data[$tkey]['credit_amount'] = $rate;
                                $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                                $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                                $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                                $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                                $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                                ;
                                $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                                $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                            }
                        }



                        $tkey++;
                    }

                    if (!empty($_POST['round_off']) && $_POST['round_off'] != "" && $_POST['round_off'] != 0) {

                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $_POST['debitcredit_date1'];
                        $journal_lines_data[$tkey]['reference_source'] = "";
                        $journal_lines_data[$tkey]['reference_id'] = '';
                        $roundoff_acc = \DB::select("select roundoff_account_id from f_account_setting_t where module_name='roundoff'");
                        $journal_lines_data[$tkey]['account_id'] = $roundoff_acc[0]->roundoff_account_id;
                        if ($_POST['round_off'] > 0) {
                            $journal_lines_data[$tkey]['debit_amount'] = ABS($_POST['round_off']);
                            $journal_lines_data[$tkey]['credit_amount'] = 0;
                        } else {
                            $journal_lines_data[$tkey]['debit_amount'] = 0;
                            $journal_lines_data[$tkey]['credit_amount'] = ABS($_POST['round_off']);
                        }

                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                        ;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        $tkey++;
                    }


                    //                 dd($journal_lines_data);
                    \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);
                } else {
                    /*Debit Account Entry Start*/
                    $tkey = 0;


                    foreach ($_POST['bulk_debitcredit_account_id'] as $key => $val) {
                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $_POST['debitcredit_date2'];
                        $journal_lines_data[$tkey]['reference_source'] = "";
                        $journal_lines_data[$tkey]['reference_id'] = '';
                        $journal_lines_data[$tkey]['account_id'] = $val;
                        $journal_lines_data[$tkey]['credit_amount'] = '';
                        $journal_lines_data[$tkey]['debit_amount'] = $_POST['bulk_debitcredit_line_amount'][$key];
                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                        ;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        $total = 0;
                        $tax_details = \DB::table('m_tax_group_lines_t')->join('f_tax_code_t', 'f_tax_code_t.tax_code_id', '=', 'm_tax_group_lines_t.tax_code_name')->select('m_tax_group_lines_t.*', 'f_tax_code_t.tax_code_percent')->where('m_tax_group_lines_t.tax_group_id', $_POST['bulk_tax_group_id'][$key])->get();

                        foreach ($tax_details as $taxval) {
                            $rate = $_POST['bulk_tax_amount'][$key];
                            $count = count($tax_details);
                            if ($rate > 0) {
                                $tkey++;
                                $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                                $journal_lines_data[$tkey]['journal_date'] = $_POST['debitcredit_date2'];
                                $journal_lines_data[$tkey]['reference_source'] = "";
                                $journal_lines_data[$tkey]['reference_id'] = '';
                                $journal_lines_data[$tkey]['account_id'] = $taxval->input_tax_account_id;

                                $rate = $_POST['bulk_tax_amount'][$key];
                                $rate = round($rate / $count, 2);

                                $journal_lines_data[$tkey]['credit_amount'] = '';
                                $journal_lines_data[$tkey]['debit_amount'] = $rate;
                                $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                                $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                                $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                                $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                                $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                                ;
                                $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                                $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                            }
                        }



                        $tkey++;
                    }

                    if (!empty($_POST['round_off']) && $_POST['round_off'] != "" && $_POST['round_off'] != 0) {
                        $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                        $journal_lines_data[$tkey]['journal_date'] = $_POST['debitcredit_date2'];
                        $journal_lines_data[$tkey]['reference_source'] = "";
                        $journal_lines_data[$tkey]['reference_id'] = '';
                        $roundoff_acc = \DB::select("select roundoff_account_id from f_account_setting_t where module_name='roundoff'");
                        $journal_lines_data[$tkey]['account_id'] = $roundoff_acc[0]->roundoff_account_id;
                        if ($_POST['round_off'] > 0) {
                            $journal_lines_data[$tkey]['credit_amount'] = 0;
                            $journal_lines_data[$tkey]['debit_amount'] = ABS($_POST['round_off']);
                        } else {
                            $journal_lines_data[$tkey]['credit_amount'] = ABS($_POST['round_off']);
                            $journal_lines_data[$tkey]['debit_amount'] = 0;
                        }

                        $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                        $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                        $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                        $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                        ;
                        $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                        $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                        $tkey++;
                    }
                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $_POST['debitcredit_date2'];
                    $journal_lines_data[$tkey]['reference_source'] = $sec_name;
                    $journal_lines_data[$tkey]['reference_id'] = $sec_id;
                    $journal_lines_data[$tkey]['account_id'] = $supplier_acc;
                    $journal_lines_data[$tkey]['credit_amount'] = $_POST['debitcredit_amount'];
                    $journal_lines_data[$tkey]['debit_amount'] = '';
                    $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                    $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                    $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                    $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                    ;
                    $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                    $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                    //    dd($journal_lines_data);
                    \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);
                }
            }
            /*End*/
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Saved Successfully', 'id' => $id));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            //                     dd($dbCode);
            $dbCode = trim($dbCode, '[');
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }

    /* purpose for Display View function*/
    public function show(request $request, $id = null)
    {
        if (isset($id)) {
            $vdata = \DB::table('f_debitcredit_t')->select(
                'f_debitcredit_t.*',
                'm_supplier_t.supplier_id',
                'tb_users.username',
                'm_supplier_t.supplier_name',
                'm_customers_t.customer_name',
                'tds.concatenated_segments as tds_account',
                'f_tds_slab_t.tds_percentage'
            )
                ->leftjoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'f_debitcredit_t.supplier_id')
                ->leftjoin('m_customers_t', 'm_customers_t.customer_id', '=', 'f_debitcredit_t.customer_id')
                ->leftjoin('f_account_structure_t as tds', 'tds.f_account_structure_id', '=', 'f_debitcredit_t.tds_account_id')
                ->leftjoin('f_tds_slab_t', 'f_tds_slab_t.tds_slab_id', '=', 'f_debitcredit_t.tds_prcnt')
                ->leftjoin('tb_users', 'tb_users.id', '=', 'f_debitcredit_t.created_by')
                ->where('debitcredit_id', $id)->get();
            //             dd($vdata);
            $this->data['debitcredit_no'] = $vdata[0]->debitcredit_no;
            $this->data['debitcredit_date'] = $vdata[0]->debitcredit_date;
            $this->data['username'] = $vdata[0]->username;
            $this->data['debitcredit_type'] = $vdata[0]->debitcredit_type;
            $this->data['source_type'] = $vdata[0]->source_type;

            $this->data['debitcredit_amount'] = $vdata[0]->debitcredit_amount;
            //$this->data['employee_name']=$vdata[0]->first_name;
            $this->data['supplier_name'] = $vdata[0]->supplier_name;
            $this->data['customer_name'] = $vdata[0]->customer_name;


            $this->data['invoice'] = $vdata[0]->invoice_no;
            //$this->data['remarks']=$vdata[0]->remarks;
            //$this->data['tds_applicable']=$vdata[0]->tds_applicable;
            // $this->data['tds_percentage']=$vdata[0]->tds_percentage;
            $this->data['tds_amount'] = $vdata[0]->tds_amount;
            $this->data['tds_account'] = $vdata[0]->tds_account;
            $vlinesdata = \DB::table('f_debitcredit_lines_t')->select(
                'f_debitcredit_lines_t.*',
                'expense.concatenated_segments as creditdebit_account',
                'm_tax_group_t.tax_group_id',
                'm_tax_group_t.tax_group_name',
                'f_gst_code_hdr_t.classification_code'
            )
                ->leftjoin('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'f_debitcredit_lines_t.tax_group_id')
                ->leftjoin('f_gst_code_hdr_t', 'f_gst_code_hdr_t.gst_code_hdr_id', '=', 'f_debitcredit_lines_t.gst_code_id')
                ->leftjoin('f_account_structure_t as expense', 'expense.f_account_structure_id', '=', 'f_debitcredit_lines_t.debitcredit_account_id')
                ->where('debitcredit_id', $id)->get();
            // dd($vlinesdata);

            $this->data['vlinesdata'] = $vlinesdata;
            //$this->data['debitcredit_account']=$vlinesdata[0]->debitcredit_account;
            $this->data['tax_group_name'] = $vlinesdata[0]->tax_group_name;
            $this->data['debitcredit_line_amount'] = $vlinesdata[0]->debitcredit_line_amount;
            $this->data['gst_code_id'] = $vlinesdata[0]->classification_code;
            $this->data['tax_amount'] = $vlinesdata[0]->tax_amount;
            $this->data['remarks'] = $vlinesdata[0]->remarks;
            //dd($this->data);
            return view('debitcreditnote.view', $this->data);
        }
    }
    public function debitcreditapproval($id = null, $aprv = null)
    {
        $this->data['pageModule'] = \Request::route()->getName();
        $this->data['pageUrl'] = \Request::route()->getName();
        $this->data['id'] = $id;
        $table = \DB::table('f_debitcredit_t')->where('debitcredit_id', $id)->get();
        $this->data['row'] = $table[0];

        $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_number|supplier_name', $table[0]->supplier_id);
        $this->data['customer_id'] = $this->jCombo('m_customers_t', 'customer_id', 'customer_number|customer_name', $table[0]->customer_id);
        if ($table[0]->debitcredit_type == "SUPPLIER") {
            $this->data['invoice_no'] = $this->jCombologin('p_po_invoice_hdr_t', 'po_invoice_id', 'bill_number', $table[0]->invoice_no);
        } else {
            $this->data['invoice_no'] = $this->jCombologin('s_invoice_hdr_t', 'invoice_hdr_id', 'invoice_number', $table[0]->invoice_no);
        }

        $tablelines = \DB::table('f_debitcredit_lines_t')->where('debitcredit_id', $id)->get();
        $this->data['linedata'] = $tablelines;

        if (count($this->data['linedata']) >= 1) {
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->debitcredit_account_id = $this->jCombo('f_account_structure_t', 'f_account_structure_id', 'concatenated_segments', $value->debitcredit_account_id);
                $this->data['linedata'][$key]->tax_group_id = $this->data['tax_group_id'] = $this->jCombotax('m_tax_group_t', 'tax_group_id', 'tax_group_name', $value->tax_group_id);
                $this->data['linedata'][$key]->gst_code_id = $this->data['gst_code_id'] = $this->jCombo('f_gst_code_hdr_t', 'gst_code_hdr_id', 'classification_code', $value->gst_code_id, 'and classification_name="HSN"');
            }
        }
        if ($aprv != "") {
            $this->data['aprvidenty'] = $aprv;
        } else {
            $this->data['aprvidenty'] = "";
        }

        //        	dd($this->data);	
        return view('debitcreditnote.form', $this->data);
    }
    function getCompany()
    {
        $sql = array();
        $company = \Session::get('companyid');
        $sql = \DB::SELECT("SELECT company_id,company_name,company_logo_name,gst_no,pan_no,cin_no,email_id from m_company_t where company_id=" . $company . "");
        if ($sql != '') {
            return $sql;

        } else {
            return 0;

        }
    }

    /*Purpose To get Location Details For Print*/
    function getLocationaddress()
    {
        $sql = array();
        $location = \Session::get('location');

        $sql = \DB::SELECT('select * from m_location_t where location_id=' . $location . '');
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }

    /*Purpose To get Supplier Site Details For Print*/
    function getCity($id = null)
    {
        $sql = array();

        $sql = \DB::SELECT('select city_id,city_name from m_cities_t where city_id=' . $id . '');
        if (!empty($sql)) {
            return $sql[0]->city_name;
        } else {
            return '';
        }
    }
    /*Purpose To get State For Print*/
    function getState($id = null)
    {
        $sql = array();

        $sql = \DB::SELECT('select state_id,state_name,state_code,state_code_no from m_states_t where state_id=' . $id . '');
        if (!empty($sql)) {
            return $sql;
        } else {
            return '';
        }

    }

    /*Purpose To get State For Print*/
    function getsiteState($id = null)
    {
        $sql = array();

        $sql = \DB::SELECT('select state_id,state_name,state_code,state_code_no from m_supplier_sites_t where state=' . $id . '');
        // dd($sql);
        if (!empty($sql)) {
            return $sql;
        } else {
            return '';
        }

    }



    /*Purpose To get Country For Print*/
    function getCountry($id = null)
    {
        $sql = array();
        $sql = \DB::SELECT('select country_id,country_name from m_countries_t where country_id =' . $id . '');

        if (!empty($sql)) {
            return $sql[0]->country_name;
        } else {
            return '';
        }
    }

    function getGst($gst = null, $po_id = null)
    {
        $sql = array();
        $location = \Session::get('ss_defaultloc_id');
        $sql = \DB::SELECT("select * from f_debitcredit_lines_t where debitcredit_id='" . $po_id . "' and tax_group_id='" . $gst . "'");
        //dd($sql);
        $hsn = array();
        $gst = array();
        $sub_total = 0;

        foreach ($sql as $key => $value) {

            $dis_amt = $value->tax_amount / 100;
            $amount = $dis_amt;
            if ($this->trd != '') {
                $trade_amt = $amount * $this->trd / 100;
            } else {
                $trade_amt = 0;
            }

            $amount1 = $amount - $trade_amt;
            $sub_total = $sub_total + $amount1;
        }

        $gst['amount'] = $sub_total;
        //dd($gst);

        return $gst;
    }

    function getsupplier($id = null)
    {
        $val = array();
        $val = \DB::SELECT('select * from m_supplier_t where supplier_id=' . $id . '');
    }
    function getProduct($id = null)
    {
        // dd("sdf");
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

    public function debitprint($id)
    {

        require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');

        if (isset($id)) {
            $vdata = \DB::table('f_debitcredit_t')
                ->select(
                    'f_debitcredit_t.*',
                    'm_supplier_t.supplier_name',
                    'm_supplier_sites_t.address',
                    'm_supplier_sites_t.pincode',
                    'm_supplier_sites_t.state',
                    'm_supplier_sites_t.contact_number',
                    'm_supplier_sites_t.gst_number',
                    'm_company_t.tax_reg_no',
                    'm_customers_t.customer_name',
                    'm_customer_sites_t.address as c_addr',
                    'm_customer_sites_t.pincode as c_pincode',
                    'm_customer_sites_t.state as c_state',
                    'm_customer_sites_t.contact_number as c_contactno',
                    'm_customer_sites_t.gst_no'
                )
                ->leftjoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'f_debitcredit_t.supplier_id')
                ->leftjoin('m_customers_t', 'm_customers_t.customer_id', '=', 'f_debitcredit_t.customer_id')
                ->leftjoin('m_customer_sites_t', 'm_customer_sites_t.customer_id', '=', 'm_customers_t.customer_id', 'and', 'm_customer_sites_t.primary_address', '=', 'YES')
                ->leftjoin('m_supplier_sites_t', 'm_supplier_sites_t.supplier_id', '=', 'f_debitcredit_t.supplier_id')
                ->leftjoin('m_company_t', 'm_company_t.company_id', '=', 'f_debitcredit_t.company_id')
                ->where('debitcredit_id', $id)->get();
            //  dd($vdata);
            $this->data['source_type'] = $vdata[0]->source_type;
            if ($vdata[0]->buyer_refno != "") {
                $this->data['buyer_refno'] = $vdata[0]->buyer_refno;
                $this->data['buyer_refdate'] = $vdata[0]->buyer_refdate;
            } else {
                $this->data['buyer_refno'] = "";
                $this->data['buyer_refdate'] = "";
            }
            if ($vdata[0]->supplier_id != 0) {
                $this->data['supplier_name'] = $vdata[0]->supplier_name;
                $this->data['addres'] = $vdata[0]->address;
                $this->data['pincode'] = $vdata[0]->pincode;
                $this->data['contact_number'] = $vdata[0]->contact_number;
                $this->data['gst_number'] = $vdata[0]->gst_number;
            } elseif ($vdata[0]->customer_id != 0) {
                $this->data['supplier_name'] = $vdata[0]->customer_name;
                $this->data['addres'] = $vdata[0]->c_addr;
                $this->data['pincode'] = $vdata[0]->c_pincode;
                $this->data['contact_number'] = $vdata[0]->c_contactno;
                $this->data['gst_number'] = $vdata[0]->gst_no;
            }

            /* else{
             $this->data['supplier_name']= "";
             $this->data['addres']= "";
             $this->data['pincode']= "";
             $this->data['contact_number']= "";
             $this->data['gst_number']= "";
             }*/
            $this->data['debitcredit_no'] = $vdata[0]->debitcredit_no;
            $this->data['debitcredit_date'] = $vdata[0]->debitcredit_date;
            //$this->data['supplier_name']= $vdata[0]->supplier_name;
            $this->data['debitcredit_amount'] = $vdata[0]->debitcredit_amount;
            $this->data['reference_no'] = $vdata[0]->reference_no;
            $this->data['tax_reg_no'] = $vdata[0]->tax_reg_no;
            if ($vdata[0]->round_off != "") {
                $this->data['round_off'] = $vdata[0]->round_off;
            } else {
                $this->data['round_off'] = "0";
            }

            $linesdata = \DB::table('f_debitcredit_lines_t')
                ->select(
                    'f_debitcredit_lines_t.*',
                    'f_gst_code_hdr_t.classification_code',
                    'm_tax_group_t.tax_group_name'
                )
                ->leftjoin('f_gst_code_hdr_t', 'f_gst_code_hdr_t.gst_code_hdr_id', '=', 'f_debitcredit_lines_t.gst_code_id')
                ->leftjoin('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'f_debitcredit_lines_t.tax_group_id')
                ->where('debitcredit_id', $id)->get();
            //dd($linesdata);
            $this->data['classification_code'] = $linesdata[0]->classification_code;
            $this->data['debitcredit_line_amount'] = $linesdata[0]->debitcredit_line_amount;
            $this->data['tax_amount'] = $linesdata[0]->tax_amount;
            $this->data['tax_group_name'] = $linesdata[0]->tax_group_name;

            $this->data['data'] = $linesdata;
            //dd($linesdata
            $taxamount = 0;
            foreach ($this->data['data'] as $key => $value) {
                if ($value->debitcredit_line_id != null) {

                    $polines[$key]['description'] = $value->description;
                    $polines[$key]['debitcredit_line_amount'] = $value->debitcredit_line_amount;
                    $polines[$key]['classification_code'] = $value->classification_code;
                    $taxamount += $polines[$key]['tax_amount'] = $value->tax_amount;
                    $polines[$key]['tax_group_name'] = $value->tax_group_name;
                } else {
                    $polines[$key]['description'] = '';
                    $polines[$key]['classification_code'] = '';
                    $polines[$key]['debitcredit_line_amount'] = 0;
                    $taxamount += $polines[$key]['tax_amount'] = 0;
                }
            }
            //dd($taxamount);
            $this->data['taxamount'] = $taxamount;
            $this->data['linesdata'] = $linesdata;
            $this->data['description'] = $linesdata[0]->description;


            $sql = $this->getCompany();
            // dd($sql);
            if (!empty($sql)) {
                $company_name = $sql[0]->company_name;
                $company_logo_name = $sql[0]->company_logo_name;
                $this->data['company_name'] = $company_name;
                $this->data['company_logo_name'] = $company_logo_name;
                $this->data['gst_no'] = $sql[0]->gst_no;
                $this->data['email_id'] = $sql[0]->email_id;
                $this->data['cin'] = $sql[0]->cin_no;
                $this->data['pan_no'] = $sql[0]->pan_no;

            }


            $location = $this->getLocationaddress();

            $sql = $this->getCity($location[0]->city_id);
            //dd($vdata);
            $con = $this->getState($location[0]->state_id);
            if ($vdata[0]->state != '') {
                $con1 = $this->getState($vdata[0]->state);
            } else {
                $con1 = $this->getState($vdata[0]->c_state);
            }


            $coun = $this->getCountry($location[0]->country_id);
            $user = $location[0]->address . "," . $sql . "-" . $location[0]->pincode . "," . $con[0]->state_name . "," . $coun;

            $this->data['address'] = $user;

            $this->data['stcode'] = $con[0]->state_code;
            $this->data['statecode'] = $con[0]->state_code_no;

            $this->data['state1code'] = $con1[0]->state_code;
            $this->data['statenocode'] = $con1[0]->state_code_no;

            $address = $this->getLocationaddress($linesdata[0]->location_id);
            $this->data['print'] = "PRINTS";

            return view('debitcreditnote.print', $this->data);
        }
    }

    public function debitcreditrefno()
    {
        //dd($_GET['id']);
        $sql = \DB::select("select debitcredit_no,debitcredit_date from f_debitcredit_t where debitcredit_id='" . $_GET['id'] . "'");
        if (isset($sql)) {
            if ($sql[0]->debitcredit_no != '') {
                $data['debitcredit_no'] = $sql[0]->debitcredit_no;
                $data['debitcredit_date'] = $sql[0]->debitcredit_date;
                //			  $data['update']='update';
            }
            //                          else if($sql[0]->payment_reference==''){
//			  $data['payment_reference']=$sql[0]->payment_reference;
//			  $data['update']='create';  
//			  }

        }
        return $data;

    }

    public function debitcreditrefnoupdate()
    {
        //dd($_GET);
        $buyer_refno = $_GET['buyer_refno'];
        $buyer_refdate = $_GET['buyer_refdate'];

        $check = \DB::update("update f_debitcredit_t set buyer_refno='$buyer_refno' , buyer_refdate='$buyer_refdate' where debitcredit_id='" . $_GET['id'] . "'");
        if ($check) {
            return 1;
        } else {

            return 0;

        }
    }

    public function crdrcreditupdate($id = null)
    {
        $creditdebit_id = $id;
        $credit_taken = $_POST['credit_taken'];
        $credit_date = date('Y-m-d', strtotime($_POST['credit_date']));
        if ($creditdebit_id != "") {
            $cd_id = explode(",", $creditdebit_id);

            \DB::table('f_debitcredit_t')->whereIn('debitcredit_id', $cd_id)->update(['credit_taken' => $credit_taken, 'credit_date' => $credit_date]);
            return response()->json(array('status' => 'success', 'message' => 'Updated Successfully'));
        }
    }


}
