<?php

namespace App\Http\Controllers;

use App\purchasereplacement;
use App\purchasereplacementlines;
use Illuminate\Http\Request, DB;
use App\Http\Controllers\Controller;
use File;
use Config;
use Yajra\DataTables\DataTables;


class PurchasereplacementController extends Controller
{
    public function __construct()
    {
        $this->data = array();
        $this->model = new purchasereplacement;
        $this->submodel = new purchasereplacementlines;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageModule'] = 'purchasereplacement';
        $this->table = "p_replacement_hdr_t";
        $this->subtable = "p_replacement_lines_t";
        $this->middleware('auth');
        $this->data['pageMethod'] == "purchasereplacementapproval";

        $this->data = array(
            'pageModule' => 'purchasereplacement',
            'pageUrl' => url('purchasereplacement'),
            'pageMethod' => $this->data['pageMethod']

        );
        $this->data['urlmenu'] = $this->indexs();
        if ($this->data['pageMethod'] == "purchasereplacementapproval") {
            // $this->data['pageMethod']="poinvoiceapproval";
            $this->data['status'] = "INITIATED";
        } else {
            $this->data['status'] = "";
        }

    }

    /* Purpose For :Index Function to Call Table Blade*/

    public function index(Request $request)
    {// restrict illegal menu entry purpose - RATHI R

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

        $this->data['suppliername'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');

        $this->data['pageMethod'] = \Request::route()->getName();

        return view("purchasereplacement.table", $this->data);

    }

    /*  purpose for Display Purchase Return Data in JQgrid function */
    public function purchasereplacementData()
    {

        $wh = '';
        if ($_GET['status'] != '') {
            $wh = " and  p_replacement_hdr_t.replacement_status='" . $_GET['status'] . "'";
            $op = "=";
            $val_status = "'" . $_GET['status'] . "'";
            $wh .= $grid_data = $this->grid_statuscheck('p_replacement_hdr_t', 'replacement_date', 'replacement_status', $op, $val_status);
        } else {
            $wh .= $grid_data = $this->grid_check('p_replacement_hdr_t', 'replacement_date');
        }


        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');

        $SQL = "SELECT
                        p_replacement_hdr_t.replacement_hdr_id,
                        p_replacement_hdr_t.replacement_date,
                        p_replacement_hdr_t.replacement_no,
                        p_replacement_hdr_t.replacement_status,
                        p_grn_hdr_t.grn_number,
                        p_grn_hdr_t.dc_number,
                        p_replacement_hdr_t.po_number,
                        p_po_hdr_t.po_date,
                        m_supplier_t.supplier_name,
                        m_supplier_t.supplier_id
                        FROM `p_replacement_hdr_t`
                        left join p_po_hdr_t on(
                        p_po_hdr_t.po_hdr_id=p_replacement_hdr_t.`po_number`)
                        left join m_supplier_t on(
                        m_supplier_t.supplier_id=p_replacement_hdr_t.`supplier_id`) left join p_grn_hdr_t on p_grn_hdr_t.grn_id=p_replacement_hdr_t.grn_number  where 1=1 $wh";


        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);

    }


    /* Purpose For :Index Function to Call Invoice Table Blade*/

    public function invoicetable()
    {
        $this->data['suppliername'] = $this->jqgridselect('m_supplier_t', 'supplier_id', 'supplier_name');
        return view("purchasereplacement.qc_table", $this->data);
    }

    /*  purpose for Display Invoice Data in JQgrid function */
    public function invoiceData()
    {

        $wh = '';

        $loc = "1";
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');
        if ($groupname == '1' || $groupname == 'Admin') {
            $wh .= 'and  p_grn_hdr_t.company_id=' . $compy;
        } else {
            $wh .= 'and  p_grn_hdr_t.company_id=' . $compy . ' and p_grn_hdr_t.location_id=' . $loc;
        }

        $SQL = "SELECT p_grn_hdr_t.*,m_subcontract_supplier_t.subcontract_name,m_supplier_t.supplier_name FROM `p_grn_hdr_t`
						LEFT JOIN m_subcontract_supplier_t ON(m_subcontract_supplier_t.subcontract_supplier_id = 		p_grn_hdr_t.`subcontract_supplier_id`)
                    LEFT JOIN m_supplier_t ON(m_supplier_t.supplier_id = p_grn_hdr_t.`supplier_id`)
                            WHERE 1 = 1 AND p_grn_hdr_t.invoice_created = 'Yes' $wh ORDER BY grn_id DESC";

        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);
    }


    /* Purpose for create and update Function*/
    public function purchasereplacementcreate($id = null, $aprv = null)
    {
        if ($aprv != "") {
            $this->data['aprvidenty'] = $aprv;
        } else {
            $this->data['aprvidenty'] = "";
        }
        if (isset($id)) {
            $this->data['row'] = $row = \DB::table('p_grn_hdr_t')->select(
                'p_po_hdr_t.po_number',
                'p_po_hdr_t.po_hdr_id',
                'm_supplier_t.supplier_id',
                'm_subcontract_supplier_t.subcontract_supplier_id',
                'p_po_hdr_t.po_number',
                'p_po_hdr_t.po_date',
                'p_grn_hdr_t.dc_number',
                'p_grn_hdr_t.grn_number',
                'p_grn_hdr_t.grn_id',
                'p_grn_hdr_t.po_number as po',
                'p_grn_hdr_t.dc_number'
            )
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_grn_hdr_t.supplier_id')
                ->leftJoin('m_subcontract_supplier_t', 'm_subcontract_supplier_t.subcontract_supplier_id', '=', 'p_grn_hdr_t.subcontract_supplier_id')
                ->leftJoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'p_grn_hdr_t.po_number')
                ->where('grn_id', $id)->get();

            $x = $this->data['row'][0]->po;
            $po = explode(",", $x);
            $po_hdr_id1 = "";
            $po_number1 = "";
            foreach ($po as $key => $value) {
                $ponum = \DB::table('p_po_hdr_t')->select('p_po_hdr_t.po_number', 'p_po_hdr_t.po_hdr_id')->where('p_po_hdr_t.po_hdr_id', $value)->get();
                $po_hdr_id1 .= $ponum[0]->po_hdr_id . ",";
                $po_number1 .= $ponum[0]->po_number . ",";

            }
            $po_hdr_id = rtrim($po_hdr_id1, ",");
            $po_number = rtrim($po_number1, ",");

            $this->data['row'][0]->po_hdr_id = $po_hdr_id;
            $this->data['row'][0]->po_number = $po_number;
            $this->data['row'][0]->replacement_hdr_id = "";
            $this->data['row'][0]->replacement_date = date('Y-m-d');
            $this->data['row'][0]->replacement_no = "";
            $this->data['row'][0]->replacement_status = "DRAFT";
            $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', '');
            $this->data['subcontract_supplier_id'] = $this->jCombo('m_subcontract_supplier_t', 'subcontract_supplier_id', 'subcontract_name', '');
            if ($row[0]->supplier_id != "") {
                $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', $row[0]->supplier_id);
            } else {
                $this->data['subcontract_supplier_id'] = $this->jCombo('m_subcontract_supplier_t', 'subcontract_supplier_id', 'subcontract_name', $row[0]->subcontract_supplier_id);
            }

            $grnlines = \DB::SELECT("SELECT p_grn_lines_t.* from p_grn_lines_t where grn_id='$id'");
            //            dd($grnlines);
            $this->data['linedata'] = $grnlines;


            if (count($this->data['linedata']) >= 1) {

                foreach ($this->data['linedata'] as $key => $value) {
                    $this->data['linedata'][$key] = (object) array();
                    $this->data['linedata'][$key]->replacement_line_id = "";
                    $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                    $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id, 'and uom_code_id=' . $value->uom_code_id);
                    $this->data['linedata'][$key]->qty = $value->qty;
                    $this->data['linedata'][$key]->comments = "";
                    $this->data['linedata'][$key]->stock_update = "";
                    $this->data['linedata'][$key]->batch_number = $this->jcombo('i_qoh_detail_t', 'batch_number', 'batch_number', '');
                    //                  $this->data['linedata'][$key]->batch_number =  $this->jcustomselect('i_qoh_detail_t','qoh_detail_id','batch_number','','and grn_id in '."(".$row[0]->grn_id.")" );
                }
            }
        }

        return view('purchasereplacement.form', $this->data);
    }
    /*Karthigaa Purpose For Edit Function*/
    public function purchasereplacementedit($id = null, $aprv = null)
    {

        if ($aprv != "") {
            $this->data['aprvidenty'] = $aprv;
        } else {
            $this->data['aprvidenty'] = "";
        }
        if (isset($id)) {

            $this->data['row'] = $return_table = \DB::table('p_replacement_hdr_t')
                ->select('p_replacement_hdr_t.*', 'p_replacement_hdr_t.invoice_number', 'p_po_hdr_t.po_number', 'p_po_hdr_t.po_hdr_id', 'm_supplier_t.supplier_id', 'p_po_hdr_t.po_number', 'p_po_hdr_t.po_date', 'p_replacement_hdr_t.dc_number', 'p_grn_hdr_t.grn_number', 'p_grn_hdr_t.grn_id')
                ->leftJoin('m_subcontract_supplier_t', 'm_subcontract_supplier_t.subcontract_supplier_id', '=', 'p_replacement_hdr_t.subcontract_supplier_id')
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_replacement_hdr_t.supplier_id')
                ->leftJoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'p_replacement_hdr_t.po_number')
                ->leftJoin('p_grn_hdr_t', 'p_grn_hdr_t.grn_id', '=', 'p_replacement_hdr_t.grn_number')
                ->where('replacement_hdr_id', $id)->get();
            //                dd($return_table);
            $this->data['row'][0]->replacement_hdr_id = $id;
            $this->data['row'][0]->replacement_date = $return_table[0]->replacement_date;
            $this->data['row'][0]->replacement_no = $return_table[0]->replacement_no;
            $this->data['row'][0]->replacement_status = $return_table[0]->replacement_status;
            if ($return_table[0]->supplier_id != "") {
                $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', $return_table[0]->supplier_id);
                $this->data['subcontract_supplier_id'] = $this->jCombo('m_subcontract_supplier_t', 'subcontract_supplier_id', 'subcontract_name', '');
            } else {
                $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', '');
                $this->data['subcontract_supplier_id'] = $this->jCombo('m_subcontract_supplier_t', 'subcontract_supplier_id', 'subcontract_name', $return_table[0]->subcontract_supplier_id);
            }

            $this->data['replacement_status'] = $return_table[0]->replacement_status;



            $tablelines = \DB::table('p_replacement_lines_t')->where('replacement_hdr_id', $id)->get();
            // dd($tablelines);
            $this->data['linedata'] = $tablelines;

            if (count($this->data['linedata']) >= 1) {
                foreach ($this->data['linedata'] as $key => $value) {

                    $this->data['linedata'][$key]->replacement_hdr_id = $value->replacement_hdr_id;
                    $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                    $this->data['linedata'][$key]->uom_code_id = $this->jCombo('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id);
                    $this->data['linedata'][$key]->qty = $value->qty;
                    $this->data['linedata'][$key]->stock_update = $value->stock_update;
                    $this->data['linedata'][$key]->comments = $value->comments;
                    $this->data['linedata'][$key]->batch_number = $this->jcombo('i_qoh_detail_t', 'batch_number', 'batch_number', $value->batch_number);
                }
            }
        }

        return view('purchasereplacement.form', $this->data);
    }



    /* Purpose For Save Function*/

    public function purchasereplacementsave(Request $request)
    {


        $id = '';
        $form = $request->all();
        $dataupload = "";
        $form = $request->except([
            '_token',
            'form_config',
            'form_data_json',
            'savestatus',
            'submit_type',
            'choosefile',
            'existing_file','return_header_id',
        ]);
        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');
        $lines_data = $this->validatePost($form, $this->subtable, 'lines');

        if ($_POST['replacement_no'] == "") {
            $seqno = $this->Seqno('RPC-', 'p_replacement_hdr_t', $_POST['replacement_no']);
        } else {
            $seqno = $_POST['replacement_no'];
        }


        \DB::beginTransaction();

        try {
            $data['replacement_no'] = $seqno;

            $id = $this->model->insertRow($data);
            $lid = $this->submodel->subgridSave($lines_data, $id);
            if ($_POST['replacement_status'] == "APPROVED") {
                foreach ($lines_data['product_id'] as $key => $value) {

                    if ($_POST['bulk_stock_update'][$key] == "YES") {
                        $qohdata['product_id'] = $value;
                        $product_id = $value;
                        $getsubinventory = \DB::table('m_products_t')->select('subinventory_id', 'sublocator_id')->where('product_id', '=', $product_id)->get();
                        $qohdata['subinventory_id'] = $getsubinventory[0]->subinventory_id;
                        $qohdata['locator_id'] = $getsubinventory[0]->sublocator_id;
                        $qohdata['qoh_trx_qty'] = -$_POST['bulk_qty'][$key];
                        $qohdata['qoh_uom_code_id'] = $_POST['bulk_uom_code_id'][$key];
                        $qohdata['batch_number'] = $_POST['bulk_batch_number'][$key];
                        $qohdata['location_id'] = "1";
                        $qohdata['company_id'] = \Session::get('companyid');
                        $qohdata['grn_id'] = $_POST['grn_number'];
                        $qohdata['qoh_source'] = 'PURCHASEREPLACEMENT';
                        $qohdata['qoh_trx_date'] = date('Y-m-d');
                        $qohdata['qoh_source_id'] = $id;

                        \DB::table('i_qoh_detail_t')->insert($qohdata);
                    }
                }
                $grn_no = $data['grn_number'];
                $dcdate = date('Y-m-d');
                $org = \Session::get('organization');
                $loc = "1";
                $compy = \Session::get('companyid');
                $grn_no = \DB::select("select grn_number from p_grn_hdr_t where grn_id='$grn_no'");
                $grn_no = "RE-" . $grn_no[0]->grn_number;
                /*Journal Header Insert*/
                $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$grn_no','REPLACEMENT','$dcdate','$id','APPROVED','$compy','$loc','$org')");
                $jid = DB::getPdo()->lastInsertId();
                $po_details = \DB::table('p_po_lines_t')->join('m_products_t', 'm_products_t.product_id', '=', 'p_po_lines_t.product_id')->join('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'p_po_lines_t.tax_group_id')->select('p_po_lines_t.*', 'm_products_t.tax_credit', 'm_products_t.account_code_id', 'm_products_t.disc_account_code', 'm_products_t.control_account_id', 'm_tax_group_t.display_name')->where("p_po_lines_t.po_hdr_id", $_POST['po_number'])->get();

                $collection = collect($po_details);
                $inventory_acc = \DB::table('f_account_setting_t')->where('module_name', 'grn')->get();

                $journal_lines_data = array();
                $total = 0;
                $tkey = 1;
                foreach ($_POST['bulk_product_id'] as $tkey1 => $tvalue) {
                    $filtered = $collection->where('product_id', $tvalue);
                    $filtered->all();
                    foreach ($filtered as $val) {
                        $filtered = array();
                        $filtered[] = $val;
                        break;
                    }

                    $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                    $journal_lines_data[$tkey]['journal_date'] = $dcdate;
                    $journal_lines_data[$tkey]['reference_source'] = "PRODUCT";
                    $journal_lines_data[$tkey]['reference_id'] = $tvalue;
                    $journal_lines_data[$tkey]['account_id'] = $filtered[0]->control_account_id;
                    $rate = $filtered[0]->unit_price - (($filtered[0]->unit_price * $filtered[0]->discount_percentage) / 100);
                    $rate = $rate * $_POST['bulk_qty'][$tkey1];
                    if ($filtered[0]->tax_credit != "Yes") {
                        $taxval = (float) $filtered[0]->display_name;
                        $rate = $rate + (($rate * $taxval) / 100);
                    }



                    $journal_lines_data[$tkey]['debit_amount'] = '';
                    $total += $journal_lines_data[$tkey]['credit_amount'] = $rate;
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
                $tkey = 0;
                $journal_lines_data[$tkey]['journal_entry_id'] = $jid;
                $journal_lines_data[$tkey]['journal_date'] = $dcdate;
                $journal_lines_data[$tkey]['reference_source'] = "";
                $journal_lines_data[$tkey]['reference_id'] = '';
                $journal_lines_data[$tkey]['account_id'] = $inventory_acc[0]->inventory_account_id;
                $journal_lines_data[$tkey]['debit_amount'] = $total;
                $journal_lines_data[$tkey]['credit_amount'] = '';
                $journal_lines_data[$tkey]['line_no'] = $tkey + 1;
                $journal_lines_data[$tkey]['created_by'] = \Session::get('id');
                $journal_lines_data[$tkey]['created_at'] = date('Y-m-d H:i:s');
                $journal_lines_data[$tkey]['last_updated_by'] = \Session::get('id');
                $journal_lines_data[$tkey]['updated_at'] = date('Y-m-d H:i:s');
                ;
                $journal_lines_data[$tkey]['location_id'] = \Session::get('companyid');
                $journal_lines_data[$tkey]['company_id'] = \Session::get('location');
                ksort($journal_lines_data);

                \DB::table('f_journal_entry_lines_t')->insert($journal_lines_data);
            }

            \DB::commit();
            /**Auditlog**/
            if ($_POST['replacement_hdr_id'] == "") {
                $action = "create";
            } else {
                $action = "edit";
            }
            $this->auditlog($id, "purchasereplacement", $action, $_POST, "p_replacement_hdr_t");
            return response()->json(array('status' => 'success', 'message' => 'Saved Successfully', 'id' => $id, 'lid' => $lid, 'auto_no' => $seqno));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            DD($dbCode);
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }

    /*End Purpose For Save Function*/

    /* Purpose For View Function*/
    public function purchasereplacementview($id = null)
    {
        if (isset($id)) {

            $this->data['row'] = $gin_table = \DB::table('p_replacement_hdr_t')->select('p_replacement_hdr_t.*', 'p_po_hdr_t.po_number', 'p_po_hdr_t.po_hdr_id', 'm_supplier_t.supplier_id', 'p_po_hdr_t.po_number', 'p_po_hdr_t.po_date', 'p_replacement_hdr_t.dc_number', 'p_grn_hdr_t.grn_number', 'p_grn_hdr_t.grn_id')
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_replacement_hdr_t.supplier_id')
                ->leftJoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'p_replacement_hdr_t.po_number')
                ->leftJoin('p_grn_hdr_t', 'p_grn_hdr_t.grn_id', '=', 'p_replacement_hdr_t.grn_number')
                ->where('replacement_hdr_id', $id)->get();
            // dd($gin_table);
            $this->data['row'][0]->replacement_hdr_id = $id;
            $replacement_date = $gin_table[0]->replacement_date;
            $this->data['row'][0]->replacement_date = date(\Session::get('p_date_format'), strtotime($replacement_date));
            $po_date = $gin_table[0]->po_date;
            $this->data['row'][0]->po_date = date(\Session::get('p_date_format'), strtotime($po_date));
            $this->data['row'][0]->replacement_status = $gin_table[0]->replacement_status;
            $this->data['row'][0]->replacement_no = $gin_table[0]->replacement_no;
            $this->data['supplier_id'] = $this->idname('supplier_name', 'm_supplier_t', 'supplier_id', $gin_table[0]->supplier_id);
            $tablelines = \DB::table('p_replacement_lines_t')->where('replacement_hdr_id', $id)->get();
            $this->data['linedata'] = $tablelines;
            //  dd($tablelines);
            if (count($this->data['linedata']) >= 1) {
                foreach ($this->data['linedata'] as $key => $value) {
                    $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->idname('concatenated_product', 'm_products_t', 'product_id', $value->product_id);
                    $this->data['linedata'][$key]->uom_code_id = $this->idname('uom_code', 'm_uom_codes_t', 'uom_code_id', $value->uom_code_id);
                    $this->data['linedata'][$key]->qty = $value->qty;
                    $this->data['linedata'][$key]->stock_update = $value->stock_update;
                    $this->data['linedata'][$key]->batch_number = $value->batch_number;
                    $this->data['linedata'][$key]->comments = $value->comments;

                }
            }
        }
        $this->data['url'] = $_GET['return'];
        return view('purchasereplacement.view', $this->data);
    }
    /*End Purpose For View Function*/

    /*Karthigaa Purpose For Purchase Return Approval Function*/
    public function purchasereplacementapproval($id = null, $aprv = null)
    {

        if ($aprv != "") {
            $this->data['aprvidenty'] = $aprv;
        } else {
            $this->data['aprvidenty'] = "";
        }
        if (isset($id)) {

            $this->data['row'] = $return_table = \DB::table('p_replacement_hdr_t')
                ->select('p_replacement_hdr_t.*', 'p_po_hdr_t.po_number', 'p_po_hdr_t.po_hdr_id', 'm_supplier_t.supplier_id', 'm_subcontract_supplier_t.subcontract_supplier_id', 'p_po_hdr_t.po_number', 'p_po_hdr_t.po_date', 'p_replacement_hdr_t.dc_number', 'p_grn_hdr_t.grn_number', 'p_grn_hdr_t.grn_id')
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_replacement_hdr_t.supplier_id')
                ->leftJoin('m_subcontract_supplier_t', 'm_subcontract_supplier_t.subcontract_supplier_id', '=', 'p_replacement_hdr_t.subcontract_supplier_id')
                ->leftJoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'p_replacement_hdr_t.po_number')
                ->leftJoin('p_grn_hdr_t', 'p_grn_hdr_t.grn_id', '=', 'p_replacement_hdr_t.grn_number')
                ->where('replacement_hdr_id', $id)->get();
            $this->data['row'][0]->replacement_hdr_id = $id;
            $this->data['row'][0]->replacement_date = $return_table[0]->replacement_date;
            $this->data['row'][0]->replacement_no = $return_table[0]->replacement_no;
            $this->data['row'][0]->replacement_status = $return_table[0]->replacement_status;


            $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', '');
            $this->data['subcontract_supplier_id'] = $this->jCombo('m_subcontract_supplier_t', 'subcontract_supplier_id', 'subcontract_name', '');
            if ($return_table[0]->supplier_id != "") {
                $this->data['supplier_id'] = $this->jCombo('m_supplier_t', 'supplier_id', 'supplier_name', $return_table[0]->supplier_id);
            } else {
                $this->data['subcontract_supplier_id'] = $this->jCombo('m_subcontract_supplier_t', 'subcontract_supplier_id', 'subcontract_name', $return_table[0]->subcontract_supplier_id);
            }

            $this->data['replacement_status'] = $return_table[0]->replacement_status;
            $tablelines = \DB::table('p_replacement_lines_t')->where('replacement_hdr_id', $id)->get();
            $this->data['linedata'] = $tablelines;
            if (count($this->data['linedata']) >= 1) {
                foreach ($this->data['linedata'] as $key => $value) {
                    $this->data['linedata'][$key]->replacement_hdr_id = $value->replacement_hdr_id;
                    $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jcustomselect('m_products_t', 'product_id', 'product_code|concatenated_product', $value->product_id, 'and product_id=' . $value->product_id);
                    $this->data['linedata'][$key]->uom_code_id = $this->jcustomselect('m_uom_codes_t', 'uom_code_id', 'uom_code', $value->uom_code_id, 'and uom_code_id=' . $value->uom_code_id);
                    $this->data['linedata'][$key]->qty = $value->qty;
                    $this->data['linedata'][$key]->stock_update = $value->stock_update;
                    $this->data['linedata'][$key]->batch_number = $this->jcombo('i_qoh_detail_t', 'batch_number', 'batch_number', $value->batch_number);
                }
            }
        }
        $this->data['pageMethod'] = "purchasereplacementapproval";
        return view('purchasereplacement.form', $this->data);
    }

    /* Purpose for Print*/
    public function getprint($id = null, $ids = null)
    {

        require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');

        if (isset($id)) {

            $gin_table = \DB::table('p_replacement_hdr_t')->select('p_po_hdr_t.po_number', 'm_subcontract_supplier_t.subcontract_supplier_id', 'p_po_hdr_t.po_hdr_id', 'm_supplier_t.supplier_id', 'p_po_hdr_t.po_number', 'p_po_hdr_t.po_date', 'p_replacement_hdr_t.dc_number', 'p_grn_hdr_t.grn_number', 'p_grn_hdr_t.grn_id', 'p_grn_hdr_t.dc_date', 'p_replacement_hdr_t.*', 'p_po_invoice_hdr_t.invoice_date', 'p_po_invoice_hdr_t.bill_number')
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_replacement_hdr_t.supplier_id')
                ->leftJoin('m_subcontract_supplier_t', 'm_subcontract_supplier_t.subcontract_supplier_id', '=', 'p_replacement_hdr_t.subcontract_supplier_id')
                ->leftJoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'p_replacement_hdr_t.po_number')
                ->leftJoin('p_po_invoice_hdr_t', 'p_po_invoice_hdr_t.po_number', '=', 'p_po_hdr_t.po_hdr_id')
                ->leftJoin('p_grn_hdr_t', 'p_grn_hdr_t.grn_id', '=', 'p_replacement_hdr_t.grn_number')
                ->where('replacement_hdr_id', $id)->get();

            //dd($gin_table);
            if (($gin_table->isNotEmpty())) {
                $this->data['replacement_hdr_id'] = $id;
                $this->data['replacement_date'] = date(\Session::get('p_date_format'), strtotime($gin_table[0]->replacement_date));
                $this->data['po_number'] = $this->idname('po_number', 'p_po_hdr_t', 'po_hdr_id', $gin_table[0]->po_number);

                $this->data['po_date'] = date(\Session::get('p_date_format'), strtotime($gin_table[0]->po_date));

                $this->data['replacement_no'] = $gin_table[0]->replacement_no;
                $this->data['bill_number'] = $gin_table[0]->bill_number;
                $this->data['dc_date'] = date(\Session::get('p_date_format'), strtotime($gin_table[0]->dc_date));
                // $this->data['dc_date']=$gin_table[0]->dc_date;
                $this->data['grn_number'] = $this->idname('grn_number', 'p_grn_hdr_t', 'grn_id', $gin_table[0]->grn_number);
                $this->data['invoice_date'] = date(\Session::get('p_date_format'), strtotime($gin_table[0]->invoice_date));
                $this->data['supplier_id'] = $this->idname('supplier_name', 'm_supplier_t', 'supplier_id', $gin_table[0]->supplier_id);
                $this->data['subcontract_supplier_id'] = $this->idname('subcontract_name', 'm_subcontract_supplier_t', 'subcontract_supplier_id', $gin_table[0]->subcontract_supplier_id);

            } else {
                $this->data['dc_date'] = '';
                $this->data['replacement_hdr_id'] = '';
                $this->data['replacement_date'] = '';
                $this->data['po_tax_total'] = '';
                $this->data['po_date'] = '';
                $this->data['replacement_no'] = '';
                $this->data['supplier_id'] = '';
                $this->data['bill_number'] = '';
                $this->data['invoice_date'] = '';
            }
            $po = explode(",", $ids);

            $tablelines = \DB::table('p_replacement_lines_t')->select('p_replacement_lines_t.*', 'p_po_lines_t.discount_percentage', 'p_po_lines_t.discount_amount', 'p_po_lines_t.tax_group_id', 'p_po_lines_t.tax_amount', 'p_po_lines_t.line_total', 'p_po_lines_t.unit_price', 'f_gst_code_hdr_t.classification_code', 'm_tax_group_t.tax_group_name', 'p_po_hdr_t.po_hdr_id', 'm_tax_group_t.display_name')
                ->leftJoin('p_replacement_hdr_t', 'p_replacement_hdr_t.replacement_hdr_id', '=', 'p_replacement_lines_t.replacement_hdr_id')
                ->leftJoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'p_replacement_hdr_t.po_number')
                ->leftJoin('p_po_lines_t', function ($join1) {
                    $join1->on('p_po_lines_t.po_hdr_id', '=', 'p_po_hdr_t.po_hdr_id');
                    $join1->on('p_po_lines_t.product_id', '=', 'p_replacement_lines_t.product_id');
                })
                ->leftJoin('f_gst_code_hdr_t', 'f_gst_code_hdr_t.gst_code_hdr_id', '=', 'p_po_lines_t.hsn_code')
                ->leftJoin('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'p_po_lines_t.tax_group_id')
                ->where('p_replacement_lines_t.replacement_hdr_id', $id)->get();
            $this->data['linedata'] = $tablelines;
            // dd($tablelines);

            /*$tableline = \DB::table('p_po_lines_t') ->select('f_gst_code_hdr_t.classification_code','p_po_lines_t.po_hdr_id')
                         ->leftJoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'p_po_lines_t.po_hdr_id')
                           ->leftJoin('p_replacement_lines_t', 'p_replacement_lines_t.product_id', '=', 'p_po_lines_t.product_id')

              ->leftJoin('f_gst_code_hdr_t', 'f_gst_code_hdr_t.gst_code_hdr_id', '=', 'p_po_lines_t.hsn_code')
             ->leftJoin('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'p_po_lines_t.tax_group_id')->
             whereIn('p_po_lines_t.po_hdr_id',$po)->get();

     dd($tableline);*/
            //  m_department_lines_t.department_line_id=REPLACE (JSON_EXTRACT(hr_employee_t.department, '$[0]'),'".'"'."','')
            $subtotal = 0;
            $sub_total = 0;
            $tot_dis = 0;
            if ($tablelines->isNotEmpty()) {
                $po_hdr_id = $tablelines[0]->po_hdr_id;
                foreach ($this->data['linedata'] as $key => $value) {
                    // dd($value);

                    $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->idname('concatenated_product', 'm_products_t', 'product_id', $value->product_id);
                    $this->data['linedata'][$key]->uom_code_id = $this->idname('uom_code', 'm_uom_codes_t', 'uom_code_id', $value->uom_code_id);

                    //  $this->data['linedata'][$key]->hsn_code =  $this->idname('uom_code','m_uom_codes_t','uom_code_id',$value->hsn_code);
                    //dd($this->data['linedata'][$key]->hsn_code);
                    $this->data['linedata'][$key]->qty = $value->qty;
                    $this->data['linedata'][$key]->unit_price = $value->unit_price;
                    $this->data['linedata'][$key]->discount_percentage = $value->discount_percentage;
                    //$this->data['linedata'][$key]->discount_amount = $value->discount_amount;
                    $this->data['linedata'][$key]->tax_amount = $value->tax_amount;
                    $this->data['linedata'][$key]->line_total = $value->line_total;
                    $this->data['linedata'][$key]->amount = $value->qty * $value->unit_price;
                    $this->data['linedata'][$key]->classification_code = $value->classification_code;
                    //  dd($this->data['linedata'][$key]->classification_code);
                    $this->data['linedata'][$key]->tax_group_name = $value->tax_group_name;
                    $discount = $value->qty * ($value->unit_price * ($value->discount_percentage / 100));
                    $dis_amt = ($value->unit_price) - ($value->unit_price * ($value->discount_percentage / 100));
                    $tot_dis = $tot_dis + $discount;
                    $amount = $dis_amt * $value->qty;
                    $polines[$key]['amount'] = $amount;
                    $subtotal = $amount + $subtotal;
                    $sub_total = $sub_total + $amount;

                }
            }
            $gstdata2 = \DB::table('p_replacement_lines_t')->select('p_replacement_lines_t.*', 'p_po_lines_t.discount_percentage', 'p_po_lines_t.discount_amount', 'p_po_lines_t.tax_group_id', 'p_po_lines_t.tax_amount', 'p_po_lines_t.line_total', 'p_po_lines_t.unit_price', 'f_gst_code_hdr_t.classification_code', 'm_tax_group_t.tax_group_name', 'p_po_hdr_t.po_hdr_id', 'm_tax_group_t.display_name')
                ->leftJoin('p_replacement_hdr_t', 'p_replacement_hdr_t.replacement_hdr_id', '=', 'p_replacement_lines_t.replacement_hdr_id')
                ->leftJoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'p_replacement_hdr_t.po_number')
                ->leftJoin('p_po_lines_t', function ($join1) {
                    $join1->on('p_po_lines_t.po_hdr_id', '=', 'p_po_hdr_t.po_hdr_id');
                    $join1->on('p_po_lines_t.product_id', '=', 'p_replacement_lines_t.product_id');
                })
                ->leftJoin('f_gst_code_hdr_t', 'f_gst_code_hdr_t.gst_code_hdr_id', '=', 'p_po_lines_t.hsn_code')
                ->leftJoin('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'p_po_lines_t.tax_group_id')
                ->where('p_replacement_lines_t.replacement_hdr_id', $id)->where('p_po_lines_t.tax_group_id', '!=', '0')->groupBy('p_po_lines_t.tax_group_id')
                ->get();


            $gstvalue1 = array();
            foreach ($gstdata2 as $gst_key1 => $gst_val) {
                //  $arr_sgcgst=array('sgst','cgst');
                //$arr=$this->getProduct($gst_value->product_id);
                $tax1 = \DB::table("m_tax_group_t")->where('tax_group_id', $gst_val->tax_group_id)->groupBy('tax_group_id')->get();
                //  dd($tax);
                if (strpos($tax1[0]->tax_group_name, 'IGST') !== false) {
                    $gstvalue1[$gst_key1]['gsttype'] = "IGST";
                } else {
                    $gstvalue1[$gst_key1]['gsttype'] = "GST";
                }
                if ($tax1->isNotEmpty())
                    $display_name1 = $tax1[0]->display_name;
                $gstvalue1[$gst_key1]['gst'] = $tax1[0]->display_name;
                $gstvalue1[$gst_key1]['sgst_val'] = $display_name1 / 2;
                $gstvalue1[$gst_key1]['cgst_val'] = $display_name1 / 2;
            }
            //dd($gstvalue1);
            // $this->data['net_amount']= $gstvalue[$gst_key]['amount']+$gstvalue[$gst_key]['gst_val']-$discount;


            $gstdata = \DB::table('p_replacement_lines_t')->select('p_replacement_lines_t.*', 'p_po_lines_t.discount_percentage', 'p_po_lines_t.discount_amount', 'p_po_lines_t.tax_group_id', 'p_po_lines_t.tax_amount', 'p_po_lines_t.line_total', 'p_po_lines_t.unit_price', 'f_gst_code_hdr_t.classification_code', 'm_tax_group_t.tax_group_name', 'p_po_hdr_t.po_hdr_id', 'm_tax_group_t.display_name')
                ->leftJoin('p_replacement_hdr_t', 'p_replacement_hdr_t.replacement_hdr_id', '=', 'p_replacement_lines_t.replacement_hdr_id')
                ->leftJoin('p_po_hdr_t', 'p_po_hdr_t.po_hdr_id', '=', 'p_replacement_hdr_t.po_number')
                ->leftJoin('p_po_lines_t', function ($join1) {
                    $join1->on('p_po_lines_t.po_hdr_id', '=', 'p_po_hdr_t.po_hdr_id');
                    $join1->on('p_po_lines_t.product_id', '=', 'p_replacement_lines_t.product_id');
                })
                ->leftJoin('f_gst_code_hdr_t', 'f_gst_code_hdr_t.gst_code_hdr_id', '=', 'p_po_lines_t.hsn_code')
                ->leftJoin('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'p_po_lines_t.tax_group_id')
                ->where('p_replacement_lines_t.replacement_hdr_id', $id)->where('p_po_lines_t.tax_group_id', '!=', '0')
                ->get();
            //dd($gstdata);
            $this->data['gstdata'] = $gstdata;

            if ($gstdata == null) {
                $gstvalue = array();
                $gstdata = 0;
                $sgst = 0;
                $cgst = 0;
                $gsttotal = 0;
                $grand_total = 0;
            } else {
                $gstvalue = array();
                $sgst = 0;
                $cgst = 0;
                $gsttotal = 0;
                $grand_total = 0;
            }

            foreach ($gstdata as $gst_key => $gst_value) {
                $arr_sgcgst = array('sgst', 'cgst');
                //$arr=$this->getProduct($gst_value->product_id);
                $tax = \DB::table("m_tax_group_t")->where('tax_group_id', $gst_value->tax_group_id)->groupBy('tax_group_id')->get();
                //  dd($tax);
                // if (strpos($tax[0]->tax_group_name, 'IGST') !== false) 
                // {
                //     $gstvalue[$gst_key]['gsttype']="IGST";
                // }
                // else
                // {
                //     $gstvalue[$gst_key]['gsttype']="GST";
                // }

                if ($tax->isNotEmpty())
                    $display_name = $tax[0]->display_name;
                $gst = $this->getGst($gst_value->tax_group_id, $po_hdr_id);
                //dd($gstdata);
                //$gstvalue[$gst_key]['gst'][]=$display_name;/* important */
                $gstvalue[$gst_key]['sgcgst'][] = $display_name / 2;
                $gstvalue[$gst_key]['hsn'] = '';
                $amount = $gst_value->unit_price * $gst_value->qty;

                $gstvalue[$gst_key]['amount'] = $amount;
                $gstvalue[$gst_key]['gst_id'] = $gst_value->tax_group_id;
                $gstvalue[$gst_key]['gst_val'] = $amount * ($display_name / 100); /* important */
                //  dd($gstvalue[$gst_key]['gst_val']);
                $gsttotal = $gsttotal + $gstvalue[$gst_key]['gst_val'];
                // $gstvalue[$gst_key]['sgst_val']=$gsttotal/2;
                // $gstvalue[$gst_key]['cgst_val']=$gsttotal/2;
                $this->data['sgcgstt'] = $arr_sgcgst;
                //unit_price
            }

            // $this->data['net_amount']= $gstvalue[$gst_key]['amount']+$gstvalue[$gst_key]['gst_val']-$discount;
        }
        // dd($gstvalue);
        $gst_total = $gsttotal;
        $gst_val1 = $gsttotal / 2;
        $grand_total = $gst_total + $sub_total;
        // dd($grand_total);
        //Maruthu Purpose to GST calculation End
        $this->data['gst1'] = $gstvalue1;
        $this->data['gst'] = $gstvalue;
        $this->data['gst_val1'] = $gst_val1;
        $this->data['gsttotal'] = $gsttotal;
        $this->data['grand_total'] = $grand_total;
        /********************* company *******************/
        $company = $this->getCompany();
        // dd($company);
        if (!empty($company)) {
            $company_name = $company[0]->company_name;
            $company_logo_name = $company[0]->company_logo_name;
            $this->data['company_name'] = $company_name;
            $this->data['company_logo_name'] = $company_logo_name;
            $this->data['gst_no'] = $company[0]->gst_no;
            $this->data['pan_no'] = $company[0]->pan_no;
            $this->data['gst_number'] = $company[0]->gst_no;
            $this->data['cin_no'] = $company[0]->cin_no;
            $this->data['Web'] = $company[0]->website_address;
            $this->data['e_mail'] = $company[0]->email_id;
        } else {
            $this->data['company_name'] = '';
        }
        //         /******************** end ************************/

        //         /******************* location *******************/

        $location = $this->getLocationaddress();
        // dd($location);
        if (!empty($location)) {
            $this->data['location_name'] = $location[0]->location_name;
            $this->data['country'] = $this->getCountry($location[0]->country_id);
            $this->data['state_l'] = $this->getState($location[0]->state_id);
            $this->data['state_name'] = $this->data['state_l'][0]->state_name;
            $this->data['state_code_no'] = $this->data['state_l'][0]->state_code;
            $this->data['city'] = $this->getCity($location[0]->city_id);
            $this->data['area'] = $location[0]->area;
            $this->data['pincode'] = $location[0]->pincode;
            $this->data['street'] = $location[0]->street_name;
            $this->data['address'] = $location[0]->address;

            $this->data['Phone'] = $location[0]->Phone;
        } else {
            $this->data['location_name'] = '';
            $this->data['country'] = '';
            $this->data['state_l'] = '';
            $this->data['state_name'] = '';
            $this->data['state_code_no'] = $this->data['state_l'][0]->state_code;
            $this->data['city'] = '';
            $this->data['area'] = '';
            $this->data['pincode'] = '';
            $this->data['street'] = '';
            $this->data['address'] = '';
            $this->data['cin_no'] = '';
            $this->data['Web'] = '';
            $this->data['e_mail'] = '';
            $this->data['Phone'] = '';
        }

        $this->data['company_address'] = $this->data['address'] . "," . $this->data['street'] . "," . $this->data['area'] . "," . $this->data['city'] . ", " . $this->data['state_name'] . "," . $this->data['country'] . "," . $this->data['pincode'];

        /************** End **********************/

        /****** supplier Name ********************/
        if (!empty($gin_table[0]->supplier_id)) {
            $supplier = $this->getSupplier($gin_table[0]->supplier_id);
            // dd($supplier);
            $this->data['bcustomer'] = $supplier[0]->supplier_name;
            // $this->data['bgst_no'] = $supplier[0]->gst_no;

        } else if (!empty($gin_table[0]->subcontract_supplier_id)) {
            $supplier = $this->getSubSupplier($gin_table[0]->subcontract_supplier_id);

            $this->data['supplier_name'] = $supplier[0]->subcontract_name;
        } else {
            $this->data['supplier_name'] = '';
            $this->data['sup_gst_no'] = '';
        }

        /*********** End **************************/

        /*********** supplier Address *************/

        if (!empty($gin_table[0]->supplier_id)) {
            $supplier_address = $this->getSupplieraddress($gin_table[0]->supplier_id);
            // dd($supplier_address);
            if (!empty($supplier_address)) {
                $this->data['address'] = $supplier_address[0]->address;
                $this->data['state'] = $this->getState_s($supplier_address[0]->state);
                $this->data['bstate_name'] = $this->data['state'][0]->state_name;
                $this->data['state_code'] = $this->data['state'][0]->state_code;

                $this->data['city'] = $this->getCity($supplier_address[0]->city);
                $this->data['country'] = $this->getCountry($supplier_address[0]->country);
                $this->data['pincode'] = $supplier_address[0]->pincode;
                $this->data['bgst_no'] = $supplier_address[0]->gst_number;
                $this->data['bcontact_number'] = $supplier_address[0]->contact_number;
                //  $this->data['email_id']=$supplier_address[0]->email_id;
                // $this->data['email_id']=$supplier_address[0]->email_id;
            } else {
                $this->data['address'] = '';
                $this->data['state'] = '';
                $this->data['bstate_name'] = '';
                $this->data['state_code'] = '';
                $this->data['city'] = '';
                $this->data['country'] = '';
                $this->data['pincode'] = '';
                $this->data['bcontact_number'] = '';
                $this->data['email_id'] = '';
                $this->data['gstno'] = '';
            }
        } else {
            $supplier_address = $this->getSubSupplieraddress($gin_table[0]->subcontract_supplier_id);
            //                 dd($supplier_address);
            if (!empty($supplier_address)) {
                $this->data['address'] = $supplier_address[0]->address;
                $this->data['state'] = $this->getState_s($supplier_address[0]->state);
                $this->data['bstate_name'] = $this->data['state'][0]->state_name;
                $this->data['state_code'] = $this->data['state'][0]->state_code;
                $this->data['city'] = $this->getCity($supplier_address[0]->city);
                $this->data['country'] = $this->getCountry($supplier_address[0]->country);
                $this->data['pincode'] = $supplier_address[0]->pincode;
                $this->data['bgst_no'] = $supplier_address[0]->gst_number;
                $this->data['bcontact_number'] = $supplier_address[0]->contact_number;
            } else {
                $this->data['address'] = '';
                $this->data['state'] = '';
                $this->data['bstate_name'] = '';
                $this->data['state_code'] = '';
                $this->data['city'] = '';
                $this->data['country'] = '';
                $this->data['pincode'] = '';
                $this->data['bcontact_number'] = '';
                $this->data['bgst_no'] = '';
            }
        }

        $this->data['supplier_address'] = $this->data['address'] . "," . $this->data['bstate_name'] . "," . $this->data['city'] . "," . $this->data['country'] . "," . $this->data['pincode'];
        /************** date ***************/
        $this->data['date'] = date(\Session::get('p_date_format'));
        $this->data['linedata'] = $this->data['linedata'];

        $this->data['print'] = "PRINT";
        $this->data['print_val'] = '1';
        //dd($this->data);
        return view('purchasereplacement.print', $this->data);
    }

    /*Purpose To get GST Details For Print*/
    function getGst($gst, $po_id)
    {
        $sql = array();
        $location = \Session::get('location');
        $sql = \DB::SELECT("select * from p_po_lines_t where po_hdr_id='" . $po_id . "' and tax_group_id='" . $gst . "'");

        $hsn = array();
        $gst = array();
        $sub_total = 0;
        foreach ($sql as $key => $value) {
            $dis_amt = ($value->unit_price) - ($value->unit_price * ($value->discount_percentage / 100));
            $amount = $dis_amt * $value->qty;
            $sub_total = $sub_total + $amount;
        }
        $gst['amount'] = $sub_total;
        return $gst;
    }
    /*Karthigaa Purpose For Mail Function*/
    function getSuppliermaildetails($id = null)
    {
        $sql = "SELECT
                    m_supplier_sites_t.contact_person,
                    m_supplier_sites_t.contact_number,
                    m_supplier_sites_t.contact_mail as email_id,
                    m_supplier_sites_t.supplier_site_id
                    FROM `m_supplier_t`
                    left join m_supplier_sites_t ON
                    m_supplier_sites_t.supplier_id=m_supplier_t.supplier_id
                    where m_supplier_t.supplier_id='$id'";
        $result1 = \DB::select($sql);
        if (!empty($result1)) {
            foreach ($result1 as $key => $value) {
                $contactperson = explode(',', $value->contact_person);
                $contact_number = explode(',', $value->contact_number);
                $email = explode(',', $value->email_id);
                foreach ($email as $k => $v) {
                    $result[] = array($value->supplier_site_id, $contactperson[$k], $contact_number[$k], $v);
                }
            }
        } else {
            $result[] = array();
        }
        return $result;
    }
    /*Karthigaa Purpose to get Unit Price From Invoice for Print*/
    function podetails($po_hdr_id, $p_id)
    {
        $result = \DB::table('p_po_invoice_lines_t')->select('p_po_invoice_lines_t.unit_price')->where('po_invoice_id', $po_hdr_id)->where('product_id', $p_id)->get();
        if ($result->isNotEmpty()) {
            $unit_price = $result[0]->unit_price;
        } else {
            $unit_price = '0.00';
        }
        return $unit_price;
    }
    /*Karthigaa Purpose to get Discount Amount From Invoice for Print*/
    function discountdetails($po_hdr_id, $p_id)
    {
        $result = \DB::table('p_po_invoice_lines_t')->select('p_po_invoice_lines_t.discount_percentage')->where('po_invoice_id', $po_hdr_id)->where('product_id', $p_id)->get();
        if ($result->isNotEmpty()) {
            $discount_percentage = $result[0]->discount_percentage;
        } else {
            $discount_percentage = '0.00';
        }
        return $discount_percentage;
    }
    /*Karthigaa Purpose to get Tax*/
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

    /*Karthigaa Purpose to get SubContract Supplier for Print*/
    function getSubSupplier($sup_id = null)
    {

        $subsupplier = \DB::table('m_subcontract_supplier_t')->where('subcontract_supplier_id', $sup_id)->get();

        if (!empty($subsupplier)) {
            return $subsupplier;
        } else {
            return 0;
        }
    }
    /*Karthigaa Purpose to get SubContract Supplier Address for Print*/
    function getSubSupplieraddress($sub_id = null)
    {
        $subsupplier_address = array();
        $subsupplier_address = \DB::select("select * from m_subcontract_sites_t where subcontract_supplier_id='$sub_id'");
        if (!empty($subsupplier_address)) {
            return $subsupplier_address;
        } else {
            return 0;
        }

    }
    /*Karthigaa Purpose to get Company State for Print*/
    function getState($state_id = null)
    {
        $state = \DB::table('m_states_t')->where('state_id', $state_id)->get();
        if (!empty($state)) {
            return $state;
        } else {
            return 0;
        }

    }
    /*Karthigaa Purpose to get Supplier State for Print*/
    function getState_s($state_id = null)
    {
        $state = \DB::table('m_states_t')->where('state_id', $state_id)->get();
        if (!empty($state)) {
            return $state;
        } else {
            return 0;
        }
    }

    /*Karthigaa Purpose to get Company City for Print*/
    function getCity($city_id = null)
    {
        $city = \DB::table('m_cities_t')->where('city_id', $city_id)->get();
        if (!empty($city)) {
            return $city[0]->city_name;
        } else {
            return 0;
        }

    }
    /*Karthigaa Purpose to get Company Country for Print*/
    function getCountry($country_id = null)
    {
        $country = \DB::table('m_countries_t')->where('country_id', $country_id)->get();
        if (!empty($country)) {
            return $country[0]->country_name;
        } else {
            return 0;
        }

    }
    /*Karthigaa Purpose to get Company Details for Print*/
    function getCompany()
    {
        $sql = array();
        $company = \Session::get('companyid');
        $sql = \DB::SELECT("SELECT company_id,company_name,gst_no,pan_no,company_logo_name,website_address,cin_no,email_id FROM `m_company_t` WHERE `company_id`=" . $company . "");
        //  dd($sql);
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }
    /*Karthigaa Purpose to get Locaion Address for Print*/
    function getLocationaddress()
    {
        $sql = array();
        $location = "1";

        $sql = \DB::SELECT('select * from m_location_t where location_id=' . $location . '');
        //  dd($sql);
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }
    /*Karthigaa Purpose to get Supplier Location Address for Print*/
    function getSupplieraddress($sub_id = null)
    {
        $supplier_address = array();
        $supplier_address = \DB::select("select * from m_supplier_sites_t where supplier_id='$sub_id'");
        if (!empty($supplier_address)) {
            return $supplier_address;
        } else {
            return 0;
        }
    }
    /*Karthigaa Purpose to get Supplier for Print*/
    function getSupplier($sup_id = null)
    {

        $supplier = \DB::table('m_supplier_t')->where('supplier_id', $sup_id)->get();
        //dd($supplier);
        if (!empty($supplier)) {
            return $supplier;
        } else {
            return 0;
        }
    }
}
