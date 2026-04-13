<?php

namespace App\Http\Controllers;
use Log;
use App\Trialbalancesrpt;
use Illuminate\Http\Request;
use yajra\datatables\datatables;
use DateTime;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf;
require 'vendor/autoload.php';


class TrialbalancesrptController extends Controller
{
  public function __construct()
  {
    $this->data = array();
    $this->data['urlmenu'] = $this->indexs();
    $this->data['pageMethod'] = \Request::route()->getName();
    $this->data['pageFormtype'] = 'ajax';
  }
  public function index()
  {
    $this->data['pageMethod'] = 'customerbalancesrpt';

    $org = \Session::get('organization');
    $loc = \Session::get('location');
    $compy = \Session::get('companyid');

    $SQL = "SELECT 
                s_invoice_hdr_t.invoice_number,
                s_invoice_hdr_t.invoice_hdr_id,
				m_customers_t.customer_id as customer_id,
                m_customers_t.customer_name as ship_to_customer_id,
                s_invoice_hdr_t.invoice_date,
                SUM(ROUND(s_invoice_hdr_t.invoice_grand_total,2)) as invoice_grand_total,
                SUM(ROUND(s_invoice_hdr_t.paid_amount,2))as paid_amount,
                SUM(ROUND(s_invoice_hdr_t.balance_amount,2))as balance_amount,
                SUM(s_invoice_hdr_t.balance_amount) as total_balance,
                IF(s_invoice_hdr_t.paid_amount <= 0,'UnPaid','Partital Paid') AS paid_status
                FROM s_invoice_hdr_t 
                left join m_customers_t on(m_customers_t.customer_id=s_invoice_hdr_t.ship_to_customer_id) 
                where 1=1  and s_invoice_hdr_t.balance_amount!=0 and s_invoice_hdr_t.company_id=$compy and s_invoice_hdr_t.location_id=$loc GROUP BY s_invoice_hdr_t.ship_to_customer_id ";

    $result = \DB::select($SQL);
    $this->data['result'] = json_encode($result);



  }



  public function accounttransaction()
  {
    $this->data['result'] = [];
    return view('trailbalance.accounttrxrpt', $this->data);
  }


  public function getaccounttransaction(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
    $cash = $request->cash ? '1' : '';

    $acc_id1 = $acc_id = -1;

    if ($cash == 1) {
      $ac_set = \DB::select("select cash_account_id from f_account_setting_t where
f_account_setting_t.module_name='cashaccount'");
      $acc_id = $ac_set[0]->cash_account_id;
    }

    $account_code_id = $acc_id . ',' . $acc_id1;

    $ledger_data = \DB::select("select
f_journal_entry_t.journal_name,f_journal_entry_t.journal_type,f_journal_entry_lines_t.journal_entry_id,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount,f_journal_entry_lines_t.journal_date,f_journal_entry_lines_t.`f_journal_entry_line_id`,
f_journal_entry_lines_t.reference_source,
(case WHEN f_journal_entry_lines_t.reference_source='PRODUCT' then m_products_t.concatenated_product
WHEN f_journal_entry_lines_t.reference_source='EMPLOYEE' then hr_employee_t.first_name
WHEN f_journal_entry_lines_t.reference_source='SUPPLIER' then m_supplier_t.supplier_name
WHEN f_journal_entry_lines_t.reference_source='CUSTOMER' then m_customers_t.customer_name
WHEN f_journal_entry_lines_t.reference_source='MACHINE' then w_machine_hdr_t.machine_name
WHEN f_journal_entry_lines_t.reference_source='PAYROLL/PF/ESI' then m_department_lines_t.sub_department_name
WHEN f_journal_entry_lines_t.reference_source='PAYROLL' then m_department_lines_t.sub_department_name
else '' end) as name,
(case when journal_type like '%PAYMENT%' then (select pmt.remarks from p_payments_t pmt where pmt.payment_id =
journal_reference)
WHEN journal_type like '%EXPENSE%' then (select exp.remarks from f_expenses_t exp where exp.expense_id =
journal_reference)
ELSE '' END) as narration
from f_journal_entry_lines_t
left JOIN m_products_t ON m_products_t.product_id=f_journal_entry_lines_t.reference_id
left JOIN hr_employee_t ON hr_employee_t.employee_id=f_journal_entry_lines_t.reference_id
left JOIN m_supplier_t ON m_supplier_t.supplier_id=f_journal_entry_lines_t.reference_id
left JOIN m_customers_t ON m_customers_t.customer_id=f_journal_entry_lines_t.reference_id
left join w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id=f_journal_entry_lines_t.reference_id
left join m_department_lines_t ON m_department_lines_t.department_line_id=f_journal_entry_lines_t.reference_id
JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id
where f_journal_entry_lines_t.account_id in ($account_code_id) and f_journal_entry_lines_t.journal_date between
'$start_date' and '$end_date'
order by f_journal_entry_lines_t.journal_date asc");

    $balance = \DB::select("select sum(f.debit-f.credit)as balance from( select COALESCE(SUM(debit_amount),0) as
debit,COALESCE(SUM(credit_amount),0)as credit from f_journal_entry_lines_t where f_journal_entry_lines_t.account_id in
($account_code_id) and f_journal_entry_lines_t.journal_date>='2021-08-31' and f_journal_entry_lines_t.journal_date
< '$start_date' )f");
    $compy = \Session::get('companyid');
    $balance = round($balance[0]->balance, 2);


    $overall_datas[0] = (object) array();
    $cop = 0;
    $dop = 0;

    if ($balance > 0) {
      $overall_datas[0]->balance = $balance;

      $dop = $balance;
      $overall_datas[0]->debit_amounts = $balance;
      $overall_datas[0]->credit_amounts = 0;
      $overall_datas[0]->net_salary = 0;
      $overall_datas[0]->journal_date = $start_date;
      $overall_datas[0]->journal_type = "Opening Balance";
      $overall_datas[0]->journal_name = "Opening Balance";
      $overall_datas[0]->concatenated_segments = '';

      $overall_datas[0]->reference_source = '';
      $overall_datas[0]->reference_name = '';
    } else {
      $overall_datas[0]->balance = $balance;
      $cop = $balance;
      $overall_datas[0]->debit_amounts = 0;
      $overall_datas[0]->credit_amounts = $balance * -1;
      $overall_datas[0]->net_salary = 0;
      $overall_datas[0]->journal_date = $start_date;
      $overall_datas[0]->journal_type = "Opening Balance";
      $overall_datas[0]->journal_name = "Opening Balance";
      $overall_datas[0]->concatenated_segments = '';
      $overall_datas[0]->reference_source = '';
      $overall_datas[0]->reference_name = '';
    }
    $key = 1;

    foreach ($ledger_data as $k => $value) {

      if ($value->reference_source != '') {
        $id = $value->journal_entry_id;
        $reference_name = $value->name;
        $reference_source = $value->reference_source;
        $debit_amount = $value->debit_amount;
        $credit_amount = $value->credit_amount;
        if ($debit_amount > 0) {
          $d = \DB::select("SELECT account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name
    from f_journal_entry_lines_t JOIN f_account_structure_t ON
    f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE
    f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.debit_amount=0");

          if (COUNT($d) == 0) {
            $acc_name = '';
          } else {
            $acc_name = $d[0]->account_name;
          }
        } else {

          $d = \DB::select("SELECT account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name
    from f_journal_entry_lines_t JOIN f_account_structure_t ON
    f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE
    f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.credit_amount=0");
          if (COUNT($d) == 0) {
            $acc_name = '';
          } else {
            $acc_name = $d[0]->account_name;
          }
        }

      } else {
        $id = $value->journal_entry_id;
        $debit_amount = $value->debit_amount;
        $credit_amount = $value->credit_amount;
        if ($debit_amount > 0) {
          $wh1 = " and f_journal_entry_lines_t.debit_amount=0";
        } else {
          $wh1 = " and f_journal_entry_lines_t.credit_amount=0";
        }
        $d = \DB::select("select
    f_journal_entry_lines_t.journal_entry_id,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount,f_journal_entry_lines_t.journal_date,f_journal_entry_lines_t.`f_journal_entry_line_id`,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name
    ,f_journal_entry_lines_t.reference_source,
    (case WHEN f_journal_entry_lines_t.reference_source='PRODUCT' then m_products_t.concatenated_product
    WHEN f_journal_entry_lines_t.reference_source='EMPLOYEE' then hr_employee_t.first_name
    WHEN f_journal_entry_lines_t.reference_source='SUPPLIER' then m_supplier_t.supplier_name
    WHEN f_journal_entry_lines_t.reference_source='CUSTOMER' then m_customers_t.customer_name
    WHEN f_journal_entry_lines_t.reference_source='MACHINE' then w_machine_hdr_t.machine_name
    WHEN f_journal_entry_lines_t.reference_source='PAYROLL/PF/ESI' then m_department_lines_t.sub_department_name
    WHEN f_journal_entry_lines_t.reference_source='PAYROLL' then m_department_lines_t.sub_department_name
    else '' end ) as name from f_journal_entry_lines_t left JOIN m_products_t ON
    m_products_t.product_id=f_journal_entry_lines_t.reference_id left JOIN hr_employee_t ON
    hr_employee_t.employee_id=f_journal_entry_lines_t.reference_id left JOIN m_supplier_t ON
    m_supplier_t.supplier_id=f_journal_entry_lines_t.reference_id left JOIN m_customers_t ON
    m_customers_t.customer_id=f_journal_entry_lines_t.reference_id left join w_machine_hdr_t ON
    w_machine_hdr_t.machine_hdr_id=f_journal_entry_lines_t.reference_id left join m_department_lines_t ON
    m_department_lines_t.department_line_id=f_journal_entry_lines_t.reference_id JOIN f_account_structure_t ON
    f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id where
    f_journal_entry_lines_t.journal_entry_id='$id' $wh1");

        $reference_name = $d[0]->name;
        $reference_source = $d[0]->reference_source;
        $acc_name = $d[0]->account_name;

      }

      $overall_datas[$key] = (object) array();

      if ($debit_amount > 0) {
        $overall_datas[$key]->concatenated_segments = $acc_name;
        $overall_datas[$key]->balance = round($balance + $debit_amount, 2);
        $dbal = $balance = $balance + $debit_amount;
        $overall_datas[$key]->debit_amounts = $debit_amount;
        $overall_datas[$key]->net_salary = 0;
        $overall_datas[$key]->journal_date = $value->journal_date;
        $overall_datas[$key]->journal_type = $value->journal_type;
        $overall_datas[$key]->journal_name = $value->journal_name;
        $overall_datas[$key]->narration = $value->narration;
        $overall_datas[$key]->reference_source = $reference_source;
        $overall_datas[$key]->reference_name = $reference_name;

      } else {

        $overall_datas[$key]->concatenated_segments = $acc_name;
        $overall_datas[$key]->balance = round($balance - $credit_amount, 2);
        $cbal = $balance = $balance - $credit_amount;
        $overall_datas[$key]->credit_amounts = $credit_amount;
        $overall_datas[$key]->net_salary = 0;
        $overall_datas[$key]->journal_date = $value->journal_date;
        $overall_datas[$key]->journal_type = $value->journal_type;
        $overall_datas[$key]->journal_name = $value->journal_name;
        $overall_datas[$key]->narration = $value->narration;
        $overall_datas[$key]->reference_source = $reference_source;
        $overall_datas[$key]->reference_name = $reference_name;
      }
      $key++;

    }

    $count1 = COUNT($overall_datas);

    $csum = array_column($overall_datas, 'credit_amounts');

    $csum1 = array_sum($csum) - $cop;

    $csum2 = array_sum($csum);
    $dsum = array_column($overall_datas, 'debit_amounts');
    $dsum1 = array_sum($dsum) - $dop;
    $dsum2 = array_sum($dsum);
    $tsum1 = $dsum1 - $csum1;
    $tsum2 = $dsum2 - $csum2;

    $overall_datas[$count1] = (object) array();
    $overall_datas[$count1]->credit_amounts = number_format(round($csum2, 3), 3);
    $overall_datas[$count1]->debit_amounts = number_format(round($dsum2, 3), 3);
    $overall_datas[$count1]->balance = number_format(round($tsum2, 3), 3);
    $overall_datas[$count1]->concatenated_segments = "Current Total";
    $ref_so = $overall_datas[1]->reference_source = '';


    $results = $overall_datas;

    return DataTables::of($results)->make(true);


  }


  public function accountbanktransaction()
  {
    $this->data['result'] = [];
    $this->data['account_no'] = $this->jCombo('f_bank_account_lines_t', 'bank_account_line_id', 'account_number', '');

    return view('trailbalance.accountbanktrxrpt', $this->data);
  }


  public function getaccountbanktransaction()
  {
    $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? date("Y-m-d", strtotime($_GET['start_date'])) : '';
    $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
    // 		$cash =  isset($_GET['cash']) && !empty($_GET['cash']) ? '1' : $_GET['cash'];
    $bank = isset($_GET['bank']) && !empty($_GET['bank']) ? '1' : $_GET['bank'];
    // 		dd($cash,$bank);


    $acc_id1 = $acc_id = -1;


    if ($bank == 1) {
      $ac_set = \DB::select("select group_concat(account_code_id) as acc_id from f_bank_account_lines_t");
      if (count($ac_set) > 0 && $ac_set[0]->acc_id != null) {
        $acc_id1 = $ac_set[0]->acc_id;
      }
    }

    $account_code_id = $acc_id . ',' . $acc_id1;


    $wh1 = "";
    if (isset($_GET['pq_filter'])) {
      $data = json_decode($_GET['pq_filter']);
      $data = $data->data;
      $wh1 .= $this->pqgridsearchsum('v1', $data);
    }

    $whh1 = '';

    $page = $_GET['pq_curpage'];
    $limit = $_GET['pq_rpp'];
    $sidx = '';
    if (!$sidx)
      $sidx = 1;

    $ledger_data = \DB::select("select * from(select f_journal_entry_t.journal_name,f_journal_entry_t.journal_type,f_bank_account_lines_t.account_number,f_journal_entry_lines_t.journal_entry_id,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount,f_journal_entry_lines_t.journal_date,f_journal_entry_lines_t.`f_journal_entry_line_id`, f_journal_entry_lines_t.reference_source, (case WHEN f_journal_entry_lines_t.reference_source='PRODUCT' then m_products_t.concatenated_product
                                           WHEN f_journal_entry_lines_t.reference_source='EMPLOYEE' then hr_employee_t.first_name
                                           WHEN f_journal_entry_lines_t.reference_source='SUPPLIER' then m_supplier_t.supplier_name
                                           WHEN f_journal_entry_lines_t.reference_source='CUSTOMER' then m_customers_t.customer_name
                                           WHEN f_journal_entry_lines_t.reference_source='MACHINE' then w_machine_hdr_t.machine_name
                                           WHEN f_journal_entry_lines_t.reference_source='PAYROLL/PF/ESI' then m_department_lines_t.sub_department_name
                                           WHEN f_journal_entry_lines_t.reference_source='PAYROLL' then  m_department_lines_t.sub_department_name
                                          else '' end ) as name,
                                          (case when journal_type like '%PAYMENT%' then (select pmt.remarks from p_payments_t pmt where pmt.payment_id = journal_reference) 
  		                                    WHEN journal_type like '%EXPENSE%' then (select exp.remarks from f_expenses_t exp where exp.expense_id = journal_reference)
                                            ELSE '' END) as narration
                                        from f_journal_entry_lines_t 
                                        left JOIN m_products_t ON m_products_t.product_id=f_journal_entry_lines_t.reference_id 
                                        left JOIN hr_employee_t ON hr_employee_t.employee_id=f_journal_entry_lines_t.reference_id 
                                        left JOIN m_supplier_t ON m_supplier_t.supplier_id=f_journal_entry_lines_t.reference_id 
                                        left JOIN m_customers_t ON m_customers_t.customer_id=f_journal_entry_lines_t.reference_id 
                                        left join w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id=f_journal_entry_lines_t.reference_id 
                                        left join m_department_lines_t ON m_department_lines_t.department_line_id=f_journal_entry_lines_t.reference_id 
                                        left join f_bank_account_lines_t ON f_bank_account_lines_t.account_code_id=f_journal_entry_lines_t.account_id 
                                        JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id 
                                        
                                        where f_journal_entry_lines_t.account_id in ($account_code_id) and f_journal_entry_lines_t.journal_date between '$start_date' and '$end_date' $whh1 order by f_journal_entry_lines_t.journal_date asc)v1 where 1=1 $wh1");

    // dd($ledger_data);
    $balance = \DB::select("select sum(f.debit-f.credit)as balance from( select COALESCE(SUM(debit_amount),0) as debit,COALESCE(SUM(credit_amount),0)as credit from f_journal_entry_lines_t where  f_journal_entry_lines_t.account_id in ($account_code_id) and f_journal_entry_lines_t.journal_date>='2021-08-31' and f_journal_entry_lines_t.journal_date < '$start_date')f");
    //dd($balance);
    $compy = \Session::get('companyid');
    $balance = round($balance[0]->balance, 2);
    $overall_datas[0] = (object) array();
    $cop = 0;
    $dop = 0;

    if ($balance > 0) {
      $overall_datas[0]->balance = $balance;
      //$balance=$balance+$v->debit_amount;
      $dop = $balance;
      $overall_datas[0]->debit_amounts = $balance;
      $overall_datas[0]->credit_amounts = 0;
      $overall_datas[0]->net_salary = 0;
      $overall_datas[0]->journal_date = $start_date;
      $overall_datas[0]->journal_type = "Opening Balance";
      $overall_datas[0]->journal_name = "Opening Balance";
      $overall_datas[0]->concatenated_segments = '';

      $overall_datas[0]->reference_source = '';
      $overall_datas[0]->reference_name = '';
    } else {
      $overall_datas[0]->balance = $balance;
      //$balance=$balance+$v->debit_amount;
      $cop = $balance;
      $overall_datas[0]->debit_amounts = 0;
      $overall_datas[0]->credit_amounts = $balance * -1;
      $overall_datas[0]->net_salary = 0;
      $overall_datas[0]->journal_date = $start_date;
      $overall_datas[0]->journal_type = "Opening Balance";
      $overall_datas[0]->journal_name = "Opening Balance";
      $overall_datas[0]->concatenated_segments = '';

      $overall_datas[0]->reference_source = '';
      $overall_datas[0]->reference_name = '';
    }

    $key = 1;
    //dd($ledger_data);

    foreach ($ledger_data as $k => $value) {
      // dd($value->reference_source);
      // dd($value);
      if ($value->reference_source != '') {
        $id = $value->journal_entry_id;
        $reference_name = $value->name;
        $reference_source = $value->reference_source;
        $account_number = $value->account_number;
        $debit_amount = $value->debit_amount;
        $credit_amount = $value->credit_amount;
        //   dd($debit_amount);
        if ($debit_amount > 0) {
          $d = \DB::select("SELECT account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.debit_amount=0");
          // dd($d);
          if (COUNT($d) == 0) {
            $acc_name = '';
          } else {
            $acc_name = $d[0]->account_name;
          }

        } else {
          $d = \DB::select("SELECT account_id,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.credit_amount=0");
          ///dd($d);
          if (COUNT($d) == 0) {
            $acc_name = '';
          } else {
            $acc_name = $d[0]->account_name;
          }
        }

      } else {
        $id = $value->journal_entry_id;
        $account_number = $value->account_number;
        $debit_amount = $value->debit_amount;
        $credit_amount = $value->credit_amount;
        if ($debit_amount > 0) {
          $wh1 = " and f_journal_entry_lines_t.debit_amount=0";
        } else {
          $wh1 = " and f_journal_entry_lines_t.credit_amount=0";
        }
        $d = \DB::select("select f_journal_entry_lines_t.journal_entry_id,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount,f_journal_entry_lines_t.journal_date,f_journal_entry_lines_t.`f_journal_entry_line_id`,f_account_structure_t.concatenated_segments,f_account_structure_t.account_name ,f_journal_entry_lines_t.reference_source, 
                            (case WHEN f_journal_entry_lines_t.reference_source='PRODUCT' then m_products_t.concatenated_product
                                   WHEN f_journal_entry_lines_t.reference_source='EMPLOYEE' then hr_employee_t.first_name
                                   WHEN f_journal_entry_lines_t.reference_source='SUPPLIER' then m_supplier_t.supplier_name
                                   WHEN f_journal_entry_lines_t.reference_source='CUSTOMER' then m_customers_t.customer_name
                                   WHEN f_journal_entry_lines_t.reference_source='MACHINE' then w_machine_hdr_t.machine_name
                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL/PF/ESI' then m_department_lines_t.sub_department_name
                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL' then  m_department_lines_t.sub_department_name
                                  else '' end ) as name   from f_journal_entry_lines_t 
                                  left JOIN m_products_t ON m_products_t.product_id=f_journal_entry_lines_t.reference_id 
                                  left JOIN hr_employee_t ON hr_employee_t.employee_id=f_journal_entry_lines_t.reference_id 
                                  left JOIN m_supplier_t ON m_supplier_t.supplier_id=f_journal_entry_lines_t.reference_id 
                                  left JOIN m_customers_t ON m_customers_t.customer_id=f_journal_entry_lines_t.reference_id 
                                  left join w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id=f_journal_entry_lines_t.reference_id 
                                  left join m_department_lines_t ON m_department_lines_t.department_line_id=f_journal_entry_lines_t.reference_id 
                                  JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id 
                                  where f_journal_entry_lines_t.journal_entry_id='$id' $wh1");
        // dd($d);
        if (COUNT($d) > 0) {
          $reference_name = $d[0]->name;
          $reference_source = $d[0]->reference_source;
          $acc_name = $d[0]->account_name;
        }
      }
      $overall_datas[$key] = (object) array();

      if ($debit_amount > 0) {
        $overall_datas[$key]->concatenated_segments = $acc_name;
        $overall_datas[$key]->balance = round($balance + $debit_amount, 2);
        $dbal = $balance = $balance + $debit_amount;
        $overall_datas[$key]->debit_amounts = $debit_amount;
        $overall_datas[$key]->net_salary = 0;
        $overall_datas[$key]->journal_date = $value->journal_date;
        $overall_datas[$key]->journal_type = $value->journal_type;
        $overall_datas[$key]->journal_name = $value->journal_name;
        $overall_datas[$key]->narration = $value->narration;
        $overall_datas[$key]->account_number = $account_number;
        $overall_datas[$key]->reference_source = $reference_source;
        $overall_datas[$key]->reference_name = $reference_name;

      } else {
        $overall_datas[$key]->concatenated_segments = $acc_name;
        $overall_datas[$key]->balance = round($balance - $credit_amount, 2);
        $cbal = $balance = $balance - $credit_amount;
        $overall_datas[$key]->credit_amounts = $credit_amount;
        $overall_datas[$key]->net_salary = 0;
        $overall_datas[$key]->journal_date = $value->journal_date;
        $overall_datas[$key]->journal_type = $value->journal_type;
        $overall_datas[$key]->journal_name = $value->journal_name;
        $overall_datas[$key]->narration = $value->narration;
        $overall_datas[$key]->account_number = $account_number;
        $overall_datas[$key]->reference_source = $reference_source;
        $overall_datas[$key]->reference_name = $reference_name;
      }
      $key++;
    }

    // dd($overall_datas);
    $count1 = COUNT($overall_datas);
    //dd($overall_datas[$count1-1]);
    $csum = array_column($overall_datas, 'credit_amounts');

    $csum1 = array_sum($csum) - $cop;
    //dd($csum1);
    $csum2 = array_sum($csum);
    $dsum = array_column($overall_datas, 'debit_amounts');
    $dsum1 = array_sum($dsum) - $dop;
    $dsum2 = array_sum($dsum);
    $tsum1 = $dsum1 - $csum1;
    $tsum2 = $dsum2 - $csum2;
    //$tsum1 = array_sum($tsum);

    $overall_datas[$count1] = (object) array();
    $overall_datas[$count1]->credit_amounts = money_format('%!n', round($csum2, 3));
    $overall_datas[$count1]->debit_amounts = money_format('%!n', round($dsum2, 3));
    $overall_datas[$count1]->balance = money_format('%!n', round($tsum2, 3));
    $overall_datas[$count1]->concatenated_segments = "Current Total";

    $ref_so = $overall_datas[0]->reference_source;

    $count = count($overall_datas);
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

    if (isset($_GET['print'])) {

      $this->data['customer_dat'] = '';
      $this->data['start_date'] = $start_date;
      $this->data['end_date'] = $end_date;

      $this->data['results'] = $overall_datas;
      $this->data['ddd'] = "Dssd";
      // dd($this->data);
      return view('trailbalance.accountbanktrxrptprint', $this->data);
    }

    if (isset($_GET['download'])) {

      $result1 = collect($overall_datas)->map(function ($x) {
        return (array) $x; })->toArray();
      return $result1;
    }


    $result = array_slice($overall_datas, $start, $limit);
    //  dd($result);
    $responce->rows[] = '';
    $responce->data = $result;
    $responce->curPage = $page;
    $responce->total = $total_pages;
    $responce->totalRecords = $count;
    echo json_encode($responce);

  }
  public function getaccounttransaction_old()
  {
    $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? date("Y-m-d", strtotime($_GET['start_date'])) : '';
    $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';

    $sql = \DB::select("SELECT f_journal_entry_t.journal_entry_id,f_journal_entry_t.journal_name,f_journal_entry_lines_t.journal_date,f_journal_entry_t.journal_type,f_journal_entry_lines_t.account_id,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount,f_account_structure_t.concatenated_segments, f_account_codes_lines_t.account_code,f_account_codes_lines_t.account_code_meaning,f_journal_entry_lines_t.f_journal_entry_line_id FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t on (f_journal_entry_lines_t.journal_entry_id=f_journal_entry_t.journal_entry_id) LEFT JOIN f_account_structure_t ON(f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id) LEFT JOIN f_account_codes_lines_t ON (f_account_codes_lines_t.account_codes_line_id=f_account_structure_t.future_reference2) where f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'");
    $this->data['start_date'] = $start_date;
    $this->data['end_date'] = $end_date;
    $this->data['result'] = $sql;

    return view('trailbalance.accounttrxtable', $this->data);
  }

  // Old - Balance Sheet -jrk
  public function balancesheet()
  {

    $this->data['ship_date'] = \DB::select("SELECT f_journal_entry_t.journal_date,f_journal_entry_lines_t.created_at FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id WHERE f_journal_entry_t.journal_name LIKE '%SHIP-SO%' ORDER BY f_journal_entry_t.journal_entry_id DESC LIMIT 1");


    $this->data['result'] = [];

    return view('trailbalance.balancesheet_jrk', $this->data);
  }

  // balance Sheet New -Baseer - skm
  public function balancesheetnew()
  {
    $this->data['result'] = [];
    // $this->data['branch_id'] = $this->jCombo('m_branch_t','branch_id','branch_name','1');

    return view('trailbalance.balancesheet', $this->data);
  }


  // Old(JRK) - getbalancesheetdata1 
  public function getbalancesheetdata1()
  {

    $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? date("Y-m-d", strtotime($_GET['start_date'])) : '';
    $start_date = '2019-04-01';
    $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';

    $profit = $this->getprofitandloss($start_date, $end_date);


    $SQL = "select account_id,main_account_code,main,sub1,sub2,sub3,sub4,
        (Case
  when account_id='553' then amount+$profit
  else amount
  end) as amount,`main_account_id`,
  `sub_account_id`,
  `future_reference1`,
  `future_reference2`,
  `sub_account4_id` 
 from ((select v1.account_id,v1.main_account_code,v1.main,v1.sub1,v1.sub2,v1.sub3,v1.sub4,round(sum(v1.amount),2)  as amount,v1.`main_account_id`,
 v1.`sub_account_id`,
 v1.`future_reference1`,
 v1.`future_reference2`,
 v1.`sub_account4_id` from(SELECT
        f.f_account_structure_id as account_id,m.main_account_code,
        m.account_class_name AS main,
        r1.account_code_meaning AS sub1,
        IF(
            f.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4, 0 as amount,
        f.`main_account_id`,
        f.`sub_account_id`,
        f.`future_reference1`,
        f.`future_reference2`,
        f.`sub_account4_id`
    FROM
        f_account_structure_t AS f
    LEFT JOIN f_account_class_t AS m
    ON
        m.account_class_id = f.main_account_id
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_codes_line_id = f.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_codes_line_id = f.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_codes_line_id = f.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_codes_line_id = f.sub_account4_id
        where m.main_account_code=100000 or m.main_account_code=200000 or m.main_account_code=300000  union all (SELECT 
            f_journal_entry_lines_t.account_id,f_account_class_t.main_account_code,'' as main,'' as sub1,'' as sub2,'' as sub3,'' as sub4,
          ROUND(
                SUM(
                    f_journal_entry_lines_t.credit_amount- f_journal_entry_lines_t.debit_amount
                ),
                2
            )  AS amount,
'' as `main_account_id`,
        '' as `sub_account_id`,
        '' as `future_reference1`,
        '' as `future_reference2`,
        '' as `sub_account4_id`           
        FROM
            `f_journal_entry_t`
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
        WHERE
           ( f_account_class_t.main_account_code=100000 or f_account_class_t.main_account_code=200000 or f_account_class_t.main_account_code=300000 ) AND f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY
            f_journal_entry_lines_t.account_id
        ORDER BY
            1))v1 group by v1.account_id ORDER by v1.main_account_code asc) union all (select v2.account_id,v2.main_account_code,v2.main,v2.sub1,v2.sub2,v2.sub3,v2.sub4,round(sum(v2.amount),2)  as amount, v2.`main_account_id`,
            v2.`sub_account_id`,
            v2.`future_reference1`,
            v2.`future_reference2`,
            v2.`sub_account4_id` from(SELECT
        f.f_account_structure_id as account_id,m.main_account_code,
        m.account_class_name AS main,
        r1.account_code_meaning AS sub1,
        IF(
            f.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4, 0 as amount,

        f.`main_account_id`,
        f.`sub_account_id`,
        f.`future_reference1`,
        f.`future_reference2`,
        f.`sub_account4_id`

    FROM
        f_account_structure_t AS f
    LEFT JOIN f_account_class_t AS m
    ON
        m.account_class_id = f.main_account_id
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_codes_line_id = f.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_codes_line_id = f.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_codes_line_id = f.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_codes_line_id = f.sub_account4_id
        where m.main_account_code=400000 or m.main_account_code=500000 or m.main_account_code=800000  union all (SELECT
            f_journal_entry_lines_t.account_id,f_account_class_t.main_account_code,'' as main,'' as sub1,'' as sub2,'' as sub3,'' as sub4,
          ROUND(
                SUM(
                    f_journal_entry_lines_t.debit_amount- f_journal_entry_lines_t.credit_amount
                ),
                2
            )  AS amount,'' as `main_account_id`,
            '' as `sub_account_id`,
            '' as `future_reference1`,
            '' as `future_reference2`,
            '' as `sub_account4_id` 
           
        FROM
            `f_journal_entry_t`
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
        WHERE
           ( f_account_class_t.main_account_code=400000 or f_account_class_t.main_account_code=500000 or f_account_class_t.main_account_code=800000 ) AND f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY
            f_journal_entry_lines_t.account_id
        ORDER BY
            1))v2 group by v2.account_id ORDER by v2.main_account_code asc) )v3 order by v3.main_account_code asc";

    $result = \DB::select($SQL);


    if (isset($_GET['download'])) {
      $result1 = collect($result)->map(function ($x) {
        return (array) $x; })->toArray();
      $data = '';

      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename=data.xls');
      $output = fopen('balancesheet.xls', 'w');

      $rows = array();
      $rows = "Account Class\t";
      $rows .= "Account Type\t";
      $rows .= "Sub1\t";
      $rows .= "Sub2\t";
      $rows .= "Sub3\t";
      $rows .= "Sub4\t";
      $rows .= "Amount(in Rs.)\n";

      //fputcsv($output, $rows); 

      foreach ($result1 as $row) {
        // $rows=array();
        $rows .= $row['main_account_code'] . "\t";
        $rows .= $row['main'] . "\t";
        $rows .= $row['sub1'] . "\t";
        $rows .= $row['sub2'] . "\t";
        $rows .= $row['sub3'] . "\t";
        $rows .= $row['sub4'] . "\t";
        $rows .= $row['amount'] . "\n";

        //fputcsv($output, $rows); 

      }
      fwrite($output, $rows);

      return 1;
    }

    $result = collect($result);
    $data = \DB::select("SELECT * FROM `f_account_class_t` where account_class_id in (11,12,2) order by main_account_code asc");

    setlocale(LC_MONETARY, 'en_IN');
    $total = 0;
    error_reporting(0);
    $html = '<table class="table table-bordered table-striped table-hover align-middle" style="    width: 100%;"><thead><th>Particulars</th><th>Amount in (Rs.)</th><th>Amount in (Rs.)</th></thead><tbody>';
    $html .= "<tr class='heading'><td><b>EQUITY & LIABILITIES</b></td><td></td><td></td></tr><tr><td></td><td></td><td></td></tr>";
    foreach ($data as $k => $val) {


      $id = $val->account_class_id;
      $name0 = ucwords(strtolower($val->account_class_name));

      $filter = $result->where('main_account_id', $id);
      $filter->all();
      $filter0 = collect($filter);
      $filter = collect($filter)->map(function ($x) {
        return (array) $x; })->toArray();

      $arr_new = round(array_sum(array_column($filter, 'amount')), 2);
      if ($arr_new == 0)
        $arr_new = '-';

      $html .= "<tr class='heading'><td><b>$name0</b></td><td></td><td></td></tr><tr><td></td><td></td><td></td></tr>";

      $sub1 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where account_class_id='$id'and parent_class_id='0'");


      foreach ($sub1 as $val1) {
        $p_id = $id = $val1->account_codes_line_id;
        $name1 = ucwords(strtolower($val1->account_code_meaning));
        $code1 = $val1->account_code;
        $filter1 = $filter0->where('sub_account_id', $id);
        $filter1->all();
        $filter11 = collect($filter1);
        $filter1 = collect($filter1)->map(function ($x) {
          return (array) $x; })->toArray();

        $arr_new1 = round(array_sum(array_column($filter1, 'amount')), 2);

        if ($arr_new1 == 0)
          $arr_new1 = '-';

        $html .= "<tr><td class='parent' data='$p_id' col='0'>$code1  $name1</td><td style='
    text-align: right;
'></td><td style='
text-align: right;
'>" . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new1, 2)) . "</td></tr>";

        //Sub2 
        $sub2 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");

        if (count($sub2) > 0) {
          //dd($filter11);
          foreach ($sub2 as $val2) {
            $id = $subid = $val2->account_codes_line_id;
            $name2 = ucwords(strtolower($val2->account_code_meaning));
            $code2 = $val2->account_code;
            $filter1 = $filter11->where('future_reference1', $id);
            $filter1->all();
            //$filter11=collect($filter1);
            $filter1 = collect($filter1)->map(function ($x) {
              return (array) $x; })->toArray();

            $arr_new2 = round(array_sum(array_column($filter1, 'amount')), 2);
            if ($arr_new2 == 0)
              $arr_new2 = '-';



            $html .= "<tr class='child child$p_id'><td  class='parent' data='$id' col=0>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code2  $name2</td><td style='
    text-align: right;
'>" . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round((float)$arr_new2, 2)) . "</td><td></td></tr>";




            $sub3 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
            if (count($sub3) > 0) {
              foreach ($sub3 as $val3) {
                //   // dd($val3);
                $id = $sub3id = $val3->account_codes_line_id;
                $name3 = ucwords(strtolower($val3->account_code_meaning));
                $code3 = $val3->account_code;
                $subfilter1 = $filter11->where('future_reference2', $id);
                $subfilter1->all();
                $subfilter12 = collect($subfilter1);
                $subfilter2 = $subfilter1 = collect($subfilter1)->map(function ($x) {
                  return (array) $x; })->toArray();

                $arr_new3 = round(array_sum(array_column($subfilter1, 'amount')), 2);
                if ($arr_new3 == 0)
                  $arr_new3 = '-';

$html .= "<tr class='child child$subid'>
    <td class='parent' data='$sub3id' col=0>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code3  $name3
    </td>
    <td style='text-align: right;'>"
    . number_format((float)$arr_new3, 2) .
    "</td>
    <td></td>
</tr>";



                foreach ($subfilter12 as $val4) {
                  $accid = $val4->account_id;
                  $accn = \DB::select('select account_name from f_account_structure_t where f_account_structure_id=' . $val4->account_id);
                  $accountname = $accn[0]->account_name;
                  $amount4 = $val4->amount;
                  $html .= "<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>" . number_format((float)$amount4, 2) ."</td><td></td></tr>";   

                }
              }
            } else {
              foreach ($filter11 as $val4) {

                $accountname = '';
                if ($val4->account_id != '') {
                  $accn = \DB::select('select account_name from f_account_structure_t where f_account_structure_id=' . $val4->account_id);
                  $accountname = $accn[0]->account_name;
                }
                $amount4 = $val4->amount;
                $html .= "<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
     text-align: right;
 '>" . number_format((float)$amount4, 2) . "</td><td></td></tr>";

              }
            }

          }
        } else {
          foreach ($filter1 as $val4) {



            $accn = \DB::select('select account_name from f_account_structure_t where f_account_structure_id=' . $val4->account_id);
            $accountname = $accn[0]->account_name;
            $amount4 = $val4->amount;
            $html .= "<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
     text-align: right;
 '>" . number_format((float)$amount4, 2) . "</td><td></td></tr>";

          }
        }
        // $html.="<tr><td><b>$code1  $name1 Total</b></td><td ></td><td style='
// text-align: right;
// '>".money_format('%!i',$arr_new1)."</td></tr><tr><td></td><td></td><td></td></tr>";

      }
      $html .= "<tr><td><b>$name0 Total</b></td><td></td><td style='
text-align: right;
'><b>" . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new, 2)) . "</b></td></tr>";

      $total = $total + $arr_new;


    }

    $html .= "<tr class='heading'><td><b>EQUITY & LIABILITIES Total</b></td><td></td><td style='
text-align: right;
'><b>" . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($total, 2)) . "</b></td></tr>";



    $data = \DB::select("SELECT * FROM `f_account_class_t` where account_class_id in (10,5,13) order by main_account_code asc");
    //dd($data);
    setlocale(LC_MONETARY, 'en_IN');
    $total = 0;
    error_reporting(0);

    $html .= "<tr class='heading'><td><b>ASSEST</b></td><td></td><td></td></tr><tr><td></td><td></td><td></td></tr>";
    foreach ($data as $k => $val) {


      $id = $val->account_class_id;
      $name0 = ucwords(strtolower($val->account_class_name));

      $filter = $result->where('main_account_id', $id);
      $filter->all();
      $filter0 = collect($filter);
      $filter = collect($filter)->map(function ($x) {
        return (array) $x; })->toArray();

      $arr_new = round(array_sum(array_column($filter, 'amount')), 2);
      if ($arr_new == 0)
        $arr_new = '-';

      $html .= "<tr class='heading'><td><b>$name0</b></td><td></td><td></td></tr><tr><td></td><td></td><td></td></tr>";

      $sub1 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where account_class_id='$id'and parent_class_id='0'");

      foreach ($sub1 as $val1) {
        $p_id = $id = $val1->account_codes_line_id;
        $name1 = ucwords(strtolower($val1->account_code_meaning));
        $code1 = $val1->account_code;
        $filter1 = $filter0->where('sub_account_id', $id);
        $filter1->all();
        $filter11 = collect($filter1);
        $filter1 = collect($filter1)->map(function ($x) {
          return (array) $x; })->toArray();

        $arr_new1 = round(array_sum(array_column($filter1, 'amount')), 2);

        if ($arr_new1 == 0)
          $arr_new1 = '-';


        $html .= "<tr><td class='parent' data='$p_id' col='0'>$code1  $name1</td><td style='
    text-align: right;
'></td><td style='
text-align: right;
'>" . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round((float)$arr_new1, 2)) . "</td></tr>";
        //Sub2 
        $sub2 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");

        if (count($sub2) > 0) {
          foreach ($sub2 as $val2) {
            $id = $val2->account_codes_line_id;
            $name2 = ucwords(strtolower($val2->account_code_meaning));
            $code2 = $val2->account_code;
            $filter1 = $filter11->where('future_reference1', $id);
            $filter1->all();
            //$filter11=collect($filter1);
            $filter1 = collect($filter1)->map(function ($x) {
              return (array) $x; })->toArray();

            $arr_new2 = round(array_sum(array_column($filter1, 'amount')), 2);
            if ($arr_new2 == 0)
              $arr_new2 = '-';

            $html .= "<tr class='child child$p_id'><td class='parent' data='$id' col=0>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code2  $name2</td><td style='
    text-align: right;
'>" . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round((float)$arr_new2, 2)) . "</td><td></td></tr>";
            $sub3 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
            if (count($sub3) > 0) {
              foreach ($sub3 as $val3) {
                //   // dd($val3);
                $sub3id = $val3->account_codes_line_id;
                $name3 = ucwords(strtolower($val3->account_code_meaning));
                $code3 = $val3->account_code;
                $subfilter1 = $filter11->where('future_reference2', $sub3id);
                $subfilter1->all();
                $subfilter12 = collect($subfilter1);
                $subfilter2 = $subfilter1 = collect($subfilter1)->map(function ($x) {
                  return (array) $x; })->toArray();

                $arr_new3 = round(array_sum(array_column($subfilter1, 'amount')), 2);
                if ($arr_new3 == 0)
                  $arr_new3 = '-';

                $html .= "<tr class='child child$id'><td class='parent' data='$sub3id' col=0>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code3  $name3</td><td style='
     text-align: right;
 '>" . number_format((float)$arr_new3, 2) . "</td><td></td></tr>";


                foreach ($subfilter12 as $val4) {
                  $accid = $val4->account_id;
                  $accn = \DB::select('select account_name from f_account_structure_t where f_account_structure_id=' . $val4->account_id);
                  $accountname = $accn[0]->account_name;
                  $amount4 = $val4->amount;
                  $html .= "<tr class='child child$sub3id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>" . number_format((float)$amount4, 2) . "</td><td></td></tr>";

                }
              }
            } else {
              foreach ($filter1 as $val4) {
                //  dd($filter1);
                $accountname = '';
                $accid = $val4['account_id'];
                $accn = \DB::select('select account_name from f_account_structure_t where f_account_structure_id=' . $val4['account_id']);
                $accountname = $accn[0]->account_name;

                $amount4 = $val4['amount'];
                $html .= "<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
     text-align: right;
 '>" . number_format($amount4, 2) . "</td><td></td></tr>";

              }

            }
          }
        } else {
          foreach ($filter1 as $val4) {



            $accn = \DB::select('select account_name from f_account_structure_t where f_account_structure_id=' . $val4->account_id);
            $accountname = $accn[0]->account_name;
            $amount4 = $val4->amount;
            $html .= "<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
     text-align: right;
 '>" . number_format($amount4, 2) . "</td><td></td></tr>";

          }
        }
      }
      $html .= "<tr><td><b>$name0 Total</b></td><td></td><td style='
text-align: right;
'><b>" . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new, 2)) . "</b></td></tr>";

      $total = $total + $arr_new;


    }

    $html .= "<tr class='heading'><td><b>ASSEST Total</b></td><td></td><td style='
text-align: right;
'><b>" . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($total, 2)) . "</b></td></tr>";



    return $html;


  }

  // New (SKM)- getbalancesheetdata1
  public function getbalancesheetdata1new()
  {


    $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? date("Y-m-d", strtotime($_GET['start_date'])) : '';
    $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';


    $bwh1 = '';

    $clo = " and date(i_qoh_detail_t.created_at) >='$start_date' and  date(i_qoh_detail_t.created_at)<='$end_date'";


    $closingbal = \DB::select("select * from (select * from (select f.product_group_id,sum(cost_rate)as stock_val
     from (SELECT i_qoh_detail_t.product_id,i_qoh_detail_t.batch_number,
                         round(sum(i_qoh_detail_t.qoh_trx_qty),2) as qoh, round(sum(i_qoh_detail_t.qoh_trx_qty)*(select i.cost from i_qoh_detail_t as i where i.batch_number=i_qoh_detail_t.batch_number and  i.cost >0 and i.product_id=i_qoh_detail_t.product_id limit 1),3) as cost_rate,m_products_t.product_group_id
             
                  FROM `i_qoh_detail_t` 
          LEFT JOIN m_products_t ON m_products_t.product_id = i_qoh_detail_t.product_id
              
                where 1=1   and m_products_t.product_group_id!='8' $clo group by i_qoh_detail_t.product_id,i_qoh_detail_t.batch_number) f GROUP by f.product_group_id) v2 where 1=1)v1");






    $currentprofit = $this->getprofitandloss($start_date, $end_date);
    // $currentprofit=$this->getprofitandloss($start_date,$end_date,$branch_id);
    $openingprofit = 21580449;//$this->getprofitandloss("2022-03-01",'2022-03-31',$branch_id);


    $SQL = "select v1.*,(CASE 
          WHEN v1.account_id='409' THEN '" . ($closingbal[1]->stock_val + $closingbal[3]->stock_val) . "'
          when v1.account_id='410' then '" . $closingbal[2]->stock_val . "'
          when v1.account_id='411' then '" . $closingbal[0]->stock_val . "'
          else amounts end)as amount from(SELECT 
            f_account_structure_t.f_account_structure_id as account_id,f_account_class_t.main_account_code,f_account_class_t.account_class_name AS main,r1.account_code_meaning AS sub1,
        IF(
            f_account_structure_t.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f_account_structure_t.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f_account_structure_t.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4,
        if(f_account_class_t.main_account_code=2,ROUND(
                SUM(
                    f_journal_entry_lines_t.credit_amount- f_journal_entry_lines_t.debit_amount
                ),
                2
            ),ROUND(
                SUM(
                    f_journal_entry_lines_t.debit_amount- f_journal_entry_lines_t.credit_amount
                ),
                2
            ))  AS amounts,
 f_account_structure_t.`main_account_id`,
        f_account_structure_t.`sub_account_id`,
        f_account_structure_t.`future_reference1`,
        f_account_structure_t.`future_reference2`,
        f_account_structure_t.`sub_account4_id`,
        f_account_structure_t.account_name
        FROM
        f_account_structure_t
        LEFT JOIN f_journal_entry_lines_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id 
         left JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
         
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_code = f_account_structure_t.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_code = f_account_structure_t.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_code = f_account_structure_t.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_code = f_account_structure_t.sub_account4_id
        WHERE
           ( f_account_class_t.main_account_code='2' or  f_account_class_t.main_account_code='1')  AND (f_journal_entry_lines_t.journal_date  <= '$end_date' or f_journal_entry_lines_t.journal_date  is null) $bwh1
        GROUP BY
            f_account_structure_t.f_account_structure_id  
ORDER BY `f_account_class_t`.`main_account_code` ASC)v1 ";


    //dd($SQL);

    $result = \DB::select($SQL);

    //
//    
    $result = collect($result);
    //  dd($result);
    $datas = $data = \DB::select("SELECT * FROM `f_account_class_t` where account_class_id in (2) order by main_account_code asc");
    //dd($data);
    setlocale(LC_MONETARY, 'en_IN');
    $total = 0;
    error_reporting(0);
    $html = '<table align="left" class="table" style="    width: 50%;"><thead><th>Particulars</th><th>Amount in (Rs.)</th><th>Amount in (Rs.)</th></thead><tbody>';
    foreach ($data as $k => $val) {


      $id = $val->account_class_id;
      $idcode = $val->main_account_code;
      $name0 = ucwords(strtolower($val->account_class_name));

      $filter = $result->where('main_account_id', $idcode);
      $filter->all();
      $filter0 = collect($filter);
      $filter = collect($filter)->map(function ($x) {
        return (array) $x; })->toArray();

      $arr_new = round(array_sum(array_column($filter, 'amount')), 2);
      if ($arr_new == 0)
        $arr_new = '-';

      $html .= "<tr class='heading'><td><b>$name0</b></td><td></td><td></td></tr><tr><td></td><td></td><td></td></tr>";

      $sub1 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where account_class_id='$id'and parent_class_id='0'");

      foreach ($sub1 as $val1) {
        $p_id = $id = $val1->account_codes_line_id;

        $name1 = ucwords(strtolower($val1->account_code_meaning));
        $code1 = $val1->account_code;
        $filter1 = $filter0->where('sub_account_id', $code1);
        $filter1->all();
        $filter11 = collect($filter1);
        $subfilter2 = $filter1 = collect($filter1)->map(function ($x) {
          return (array) $x; })->toArray();

        $arr_new1 = round(array_sum(array_column($filter1, 'amount')), 2);

        if ($arr_new1 == 0)
          $arr_new1 = '-';
        //  dd($arr_new1);
        $html .= "<tr><td class='parent' data='$id' col='0'>$code1  $name1</td><td style='
    text-align: right;
'></td><td style='
text-align: right;
'>" . money_format('%!i', $arr_new1) . "</td></tr>";
        //dd($code1);
//Sub2 
        $sub2 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");


        foreach ($sub2 as $val2) {
          $id = $subid = $val2->account_codes_line_id;
          $name2 = ucwords(strtolower($val2->account_code_meaning));
          $code2 = $val2->account_code;
          $filter1 = $filter11->where('future_reference1', $code2);
          $filter1->all();
          $subfilter11 = collect($filter1);
          $subfilter2 = $filter1 = collect($filter1)->map(function ($x) {
            return (array) $x; })->toArray();

          $arr_new2 = round(array_sum(array_column($filter1, 'amount')), 2);
          if ($arr_new2 == 0)
            $arr_new2 = '-';

          $html .= "<tr class='child child$p_id'><td class='parent parent_hide$p_id' data='$subid' col=0>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code2  $name2</td><td style='
    text-align: right;
'>" . money_format('%!i', $arr_new2) . "</td><td></td></tr>";

          $sub3 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$subid'");
          if (count($sub3) > 0) {
            foreach ($sub3 as $val3) {
              // dd($val3);
              $id = $sub3id = $val3->account_codes_line_id;
              $name3 = ucwords(strtolower($val3->account_code_meaning));
              $code3 = $val3->account_code;
              $subfilter1 = $subfilter11->where('future_reference2', $code3);
              $subfilter1->all();
              $subfilter12 = collect($subfilter1);
              $subfilter2 = $subfilter1 = collect($subfilter1)->map(function ($x) {
                return (array) $x; })->toArray();

              $arr_new3 = round(array_sum(array_column($subfilter1, 'amount')), 2);
              if ($arr_new3 == 0)
                $arr_new3 = '-';

              $html .= "<tr class='child child$subid'><td class='parent parent_hide$subid' data='$sub3id' col=0>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code3  $name3</td><td style='
    text-align: right;
'>" . money_format('%!i', $arr_new3) . "</td><td></td></tr>";


              $sub4 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$sub3id'");

              if (count($sub) > 0) {

                foreach ($sub4 as $val4) {
                  $id = $sub4id = $val4->account_codes_line_id;
                  $name4 = ucwords(strtolower($val4->account_code_meaning));
                  $code4 = $val4->account_code;
                  $subfilter2 = $subfilter12->where('sub_account4_id', $code4);
                  $subfilter2->all();
                  $subfilter2 = collect($subfilter2)->map(function ($x) {
                    return (array) $x; })->toArray();

                  $arr_new4 = round(array_sum(array_column($subfilter2, 'amount')), 2);
                  if ($arr_new4 == 0)
                    $arr_new4 = '-';

                  $html .= "<tr class='child child$sub3id '><td class='parent parent_hide$sub3id' data='$sub4id' col=0>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code4  $name4</td><td style='
    text-align: right;
'>" . money_format('%!i', $arr_new4) . "</td><td></td></tr>";

                  foreach ($subfilter2 as $val4) {


                    $accid = $val4['account_id'];

                    $accountname = $val4['account_name'];
                    $amount4 = $val4['amount'];
                    //rr
                    $html .= "<tr class='child clicklink child$id '><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)'  value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>" . money_format('%!i', $amount4) . "</td><td></td></tr>";

                  }
                }
              } else {
                foreach ($subfilter2 as $val4) {

                  $accid = $val4['account_id'];

                  //rr
                  $accountname = $val4['account_name'];
                  $amount4 = $val4['amount'];
                  $html .= "<tr class='child clicklink child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>" . money_format('%!i', $amount4) . "</td><td></td></tr>";

                }
              }
            }
          } else {
            foreach ($subfilter2 as $val4) {



              $accountname = $val4['account_name'];
              $amount4 = $val4['amount'];
              $html .= "<tr class='child child$id'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</td><td style='
    text-align: right;
'>" . money_format('%!i', $amount4) . "</td><td></td></tr>";

            }
          }


        }

      }


      $html .= "<tr ><td class='parent' data='987654321'>Profit & Loss A/C</td><td style='
    text-align: right;
'></td><td style='
text-align: right;
'>" . money_format('%!i', ($currentprofit + $openingprofit)) . "</td></tr>";


      $html .= "<tr class='child child987654321' ><td class='parent parent_hide987654321'>Opening</td><td style='
    text-align: right;
'></td><td style='
text-align: right;
'>" . money_format('%!i', ($openingprofit)) . "</td></tr>";




      $html .= "<tr class='child child987654321' ><td class='parent parent_hide987654321'>Current</td><td style='
    text-align: right;
'></td><td style='
text-align: right;
'>" . money_format('%!i', ($currentprofit)) . "</td></tr>";


      $html .= "<tr><td><b>$name0 Total</b></td><td></td><td style='
text-align: right;
'><b>" . money_format('%!i', $arr_new + $currentprofit + $openingprofit) . "</b></td></tr>";

      $total = $total + $arr_new;


    }



    $html .= '<table align="right" class="table" style="width: 50%;"><thead><th>Particulars</th><th>Amount in (Rs.)</th><th>Amount in (Rs.)</th></thead><tbody>';

    $data = \DB::select("SELECT * FROM `f_account_class_t` where account_class_id in (1) order by main_account_code asc");

    setlocale(LC_MONETARY, 'en_IN');
    $total = 0;
    error_reporting(0);

    foreach ($data as $k => $val) {


      $id = $val->account_class_id;
      $name0 = ucwords(strtolower($val->account_class_name));
      $codeid = $val->main_account_code;
      $filter = $result->where('main_account_id', $codeid);
      $filter->all();
      $filter0 = collect($filter);
      $filter = collect($filter)->map(function ($x) {
        return (array) $x; })->toArray();
      //dd($filter);
      $arr_new = round(array_sum(array_column($filter, 'amount')), 2);
      if ($arr_new == 0)
        $arr_new = '-';

      $html .= "<tr class='heading'><td><b>$name0</b></td><td></td><td></td></tr><tr><td></td><td></td><td></td></tr>";

      $sub1 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where account_class_id='$id'and parent_class_id='0'");

      foreach ($sub1 as $val1) {
        $p_id = $id = $val1->account_codes_line_id;
        $name1 = ucwords(strtolower($val1->account_code_meaning));
        $code1 = $val1->account_code;
        $filter1 = $filter0->where('sub_account_id', $code1);
        $filter1->all();
        $filter11 = collect($filter1);
        $filter1 = collect($filter1)->map(function ($x) {
          return (array) $x; })->toArray();

        $arr_new1 = round(array_sum(array_column($filter1, 'amount')), 2);

        if ($arr_new1 == 0)
          $arr_new1 = '-';


        //  dd($code1.'--'.$name1."===".$arr_new1);


        $html .= "<tr><td class='parent' data='$p_id' col='0'>$code1  $name1</td><td style='
    text-align: right;
'></td><td style='
text-align: right;
'>" . money_format('%!i', $arr_new1) . "</td></tr>";
        //Sub2 
        $sub2 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");

        // if(count($sub2)>0)
// {
//      $html.="<tr><td>$code1  $name1</td><td style='
//     text-align: right;
// '></td><td></td></tr>";
// }
// else
// {
//      $html.="<tr><td>$code1  $name1</td><td style='
//     text-align: right;
// '>".money_format('%!i',$arr_new1)."</td><td></td></tr>";
// }


        foreach ($sub2 as $val2) {
          $id = $subid = $val2->account_codes_line_id;
          $name2 = ucwords(strtolower($val2->account_code_meaning));
          $code2 = $val2->account_code;
          $filter1 = $filter11->where('future_reference1', $code2);
          $filter1->all();


          //$filter11=collect($filter1);





          $subfilter2 = $filter1 = collect($filter1)->map(function ($x) {
            return (array) $x; })->toArray();

          $arr_new2 = round(array_sum(array_column($filter1, 'amount')), 2);
          if ($arr_new2 == 0)
            $arr_new2 = '-';

          $html .= "<tr class='child child$p_id'><td class='parent parent_hide$p_id' data=$subid col=0>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code2  $name2</td><td style='
    text-align: right;
'>" . money_format('%!i', $arr_new2) . "</td><td></td></tr>";
          $sub3 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$subid'");

          if (count($sub3) > 0) {
            foreach ($sub3 as $val3) {



              $id = $sub3id = $val3->account_codes_line_id;
              $name3 = ucwords(strtolower($val3->account_code_meaning));
              $code3 = $val3->account_code;


              $subfilter1 = $filter11->where('future_reference2', $code3);
              $subfilter1->all();


              $subfilter2 = $subfilter1 = collect($subfilter1)->map(function ($x) {
                return (array) $x; })->toArray();

              $arr_new3 = round(array_sum(array_column($subfilter1, 'amount')), 2);
              if ($arr_new3 == 0)
                $arr_new3 = '-';

              $html .= "<tr class='child child$subid'><td class='parent parent_hide$subid' data=$sub3id col=0>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code3  $name3</td><td style='
    text-align: right;
'>" . money_format('%!i', $arr_new3) . "</td><td></td></tr>";


              $sub4 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$sub3id'");

              if (COUNT($sub4) > 0) {
                //  dd($sub4);  
                foreach ($sub4 as $val4) {
                  $id = $sub4id = $val4->account_codes_line_id;
                  $name4 = ucwords(strtolower($val4->account_code_meaning));
                  $code4 = $val4->account_code;

                  $subfilter1 = $filter11->where('sub_account4_id', $code4);




                  $subfilter2 = $subfilter1 = collect($subfilter1)->map(function ($x) {
                    return (array) $x; })->toArray();
                  // dd($subfilter2);
                  $arr_new4 = round(array_sum(array_column($subfilter2, 'amount')), 2);
                  if ($arr_new4 == 0)
                    $arr_new4 = '-';

                  if (count($subfilter2) > 0) {
                    foreach ($subfilter2 as $val4) {


                      $accid = $val4['account_id'];
                      $accountname = $val4['account_name'];
                      $amount4 = $val4['amount'];

                      $html .= "<tr class='child clicklink child$sub3id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   $accountname</a></td>
 <td style='
    text-align: right;
'>" . money_format('%!i', $amount4) . "</td><td></td></tr>";

                    }
                  } else {
                    $html .= "<tr class='child clicklink child$sub3id'><td><a class='linkeid' name='linkeid'  value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   $name4</a></td>
 <td style='
    text-align: right;
'>" . money_format('%!i', $arr_new4) . "</td><td></td></tr>";
                  }
                }
              } else {
                foreach ($subfilter2 as $val4) {

                  $accountname = $val4['account_name'];
                  $amount4 = $val4['amount'];


                  $html .= "<tr class='child clicklink child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   $accountname</a></td>
 <td style='
    text-align: right;
'>" . money_format('%!i', $amount4) . "</td><td></td></tr>";

                }
              }
            }
          } else {
            foreach ($subfilter2 as $val4) {



              $accountname = $val4['account_name'];
              $amount4 = $val4['amount'];
              $html .= "<tr class='child child$id'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</td><td style='
    text-align: right;
'>" . money_format('%!i', $amount4) . "</td><td></td></tr>";

            }
          }
        }

      }

      //rohi
      $html .= "<tr><td><b>$name0 Total</b></td><td></td><td style='
text-align: right;
'><b>" . money_format('%!i', $arr_new) . "</b></td></tr>";

      $total = $total + $arr_new;


    }


    if (isset($_GET['download'])) {
      $result1 = collect($result)->map(function ($x) {
        return (array) $x; })->toArray();
      $data = '';


      $comp = \Session::get('companyid');

      $comp = \DB::table('m_company_t')->where('company_id', $comp)->get();
      if ($comp->isNotEmpty()) {
        $this->data['company_name'] = $comp[0]->company_name;
      } else {
        $this->data['company_name'] = '';
      }

      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();







      $sheet->setCellValue('A1', $this->data['company_name']);
      //style bold for name

      $styleArray = array(
        'font' => array(
          'bold' => true
        )
      );
      $sheet->getStyle('A1')->applyFromArray($styleArray);

      $sheet->mergeCells('A1:C1');

      $sheet->getColumnDimension('c1')->setWidth(170, 'pt');



      $sheet->setCellValue('A2', 'Plot No.34 & 35 I Link Street,');
      $sheet->setCellValue('A3', 'Nehru Nagar Chennai - 600 041');
      $sheet->setCellValue('A4', 'Ph : 044-24540345,24542510');
      $sheet->mergeCells('A2:C2');
      $sheet->mergeCells('A3:C3');
      $sheet->mergeCells('A4:C4');
      $sheet->setCellValue('A5', 'Balence Sheet');

      $sheet->mergeCells('A5:C5');

      $sheet->getStyle('A5:C5')->applyFromArray($styleArray);


      $sheet->getColumnDimension('A')->setWidth(40);
      $sheet->getColumnDimension('B')->setWidth(10);
      $sheet->getColumnDimension('C')->setWidth(15);
      $sheet->getColumnDimension('D')->setWidth(40);
      $sheet->getColumnDimension('E')->setWidth(10);
      $sheet->getColumnDimension('F')->setWidth(15);

      $a = 6;
      $sheet->setCellValue('A' . $a, $start_date . ' to ' . $end_date);

      $sheet->mergeCells('A6:C6');


      $sheet->setCellValue('b7', $this->data['company_name']);
      $sheet->mergeCells('b7:C7');
      $sheet->getStyle('b7')->applyFromArray($styleArray);


      $sheet->setCellValue('e7', $this->data['company_name']);
      $sheet->mergeCells('e7:f7');
      $sheet->getStyle('e7')->applyFromArray($styleArray);

      $sheet->setCellValue('A8', 'Particulars');
      $sheet->setCellValue('D8', 'Particulars');
      $sheet->getStyle('A8', 'D8')->applyFromArray($styleArray);
      $sheet->getStyle('D8')->applyFromArray($styleArray);



      $sheet->setCellValue('A9', 'Asset');
      $sheet->setCellValue('D9', 'Liability');
      $sheet->getStyle('A9')->applyFromArray($styleArray);
      $sheet->getStyle('D9')->applyFromArray($styleArray);



      $data = \DB::select("SELECT * FROM `f_account_class_t` where account_class_id in (1) order by main_account_code asc");

      setlocale(LC_MONETARY, 'en_IN');
      $total = 0;
      error_reporting(0);

      foreach ($data as $k => $val) {



        $id = $val->account_class_id;
        $name0 = ucwords(strtolower($val->account_class_name));
        $codeid = $val->main_account_code;
        $filter = $result->where('main_account_id', $codeid);
        $filter->all();
        $filter0 = collect($filter);
        $filter = collect($filter)->map(function ($x) {
          return (array) $x; })->toArray();
        $arr_new = round(array_sum(array_column($filter, 'amount')), 2);
        if ($arr_new == 0)
          $arr_new = '-';


        $sub1 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where account_class_id='$id'and parent_class_id='0'");

        $total = 0;
        //lines
        $n = '';
        $y = 10;
        foreach ($sub1 as $val1) {
          $p_id = $id = $val1->account_codes_line_id;
          $name1 = ucwords(strtolower($val1->account_code_meaning));
          $code1 = $val1->account_code;
          $filter1 = $filter0->where('sub_account_id', $code1);
          $filter1->all();
          $filter11 = collect($filter1);
          $filter1 = collect($filter1)->map(function ($x) {
            return (array) $x; })->toArray();

          $arr_new1 = round(array_sum(array_column($filter1, 'amount')), 2);

          if ($arr_new1 == 0)
            $arr_new1 = '-';

          $m = money_format('%!i', $arr_new1);
          $sheet->setCellValue('d' . $y, $code1 . ' ' . $name1);
          $sheet->getStyle('d' . $y)->applyFromArray($styleArray);

          $sheet->setCellValue('f' . $y, $m);

          $sub2 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");

          $s21 = $y + 1;
          $s2 = $s21;
          foreach ($sub2 as $val2) {
            $id = $subid = $val2->account_codes_line_id;
            $name2 = ucwords(strtolower($val2->account_code_meaning));
            $code2 = $val2->account_code;
            $filter1 = $filter11->where('future_reference1', $code2);
            $filter1->all();





            $sub3 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$subid'");

            $subfilter2 = $filter1 = collect($filter1)->map(function ($x) {
              return (array) $x; })->toArray();

            $arr_new2 = round(array_sum(array_column($filter1, 'amount')), 2);
            if ($arr_new2 == 0)
              $arr_new2 = '-';

            $sheet->setCellValue('d' . $s2, '   ' . $code2 . ' ' . $name2);

            if (count($sub3) < 0) {
              //$m2=money_format('%!i',$arr_new2);
              $m2 = $arr_new2;
              $sheet->setCellValue('e' . $s2, $m2);


            }
            $s22 = $s2 + 1;
            $s3 = $s22;
            if (count($sub3) > 0) {
              foreach ($sub3 as $val3) {



                $id = $sub3id = $val3->account_codes_line_id;
                $name3 = ucwords(strtolower($val3->account_code_meaning));
                $code3 = $val3->account_code;


                $subfilter1 = $filter11->where('future_reference2', $code3);
                $subfilter1->all();


                $subfilter2 = $subfilter1 = collect($subfilter1)->map(function ($x) {
                  return (array) $x; })->toArray();

                $arr_new3 = round(array_sum(array_column($subfilter1, 'amount')), 2);
                if ($arr_new3 == 0)
                  $arr_new3 = '-';
                $sub4 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$sub3id'");


                $sheet->setCellValue('d' . $s3, '          ' . $code3 . ' ' . $name3);

                if (COUNT($sub4) < 0) {
                  $m3 = money_format('%!i', $arr_new3);
                  $sheet->setCellValue('e' . $s3, $m3);

                }

                $s33 = $s3 + 1;
                $s4 = $s33;
                if (COUNT($sub4) > 0) {
                  //  dd($sub4);  
                  foreach ($sub4 as $val4) {
                    $id = $sub4id = $val4->account_codes_line_id;
                    $name4 = ucwords(strtolower($val4->account_code_meaning));
                    $code4 = $val4->account_code;

                    $subfilter1 = $filter11->where('sub_account4_id', $code4);

                    $subfilter2 = $subfilter1 = collect($subfilter1)->map(function ($x) {
                      return (array) $x; })->toArray();
                    // dd($subfilter2);
                    $arr_new4 = round(array_sum(array_column($subfilter2, 'amount')), 2);
                    if ($arr_new4 == 0)
                      $arr_new4 = '-';
                    $s5 = $s4;
                    //dd($subfilter2);
                    foreach ($subfilter2 as $val4) {



                      $accountname = $val4['account_name'];
                      $amount4 = $val4['amount'];
                      //  $html.="<tr class='child child$id'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</td><td style='
//     text-align: right;
// '>".money_format('%!i',$amount4)."</td><td></td></tr>";
//$m4=money_format('%!i',$amount4);
                      $sheet->setCellValue('d' . $s5, '                  ' . $accountname);
                      if ($amount4 != 0) {
                        $sheet->setCellValue('e' . $s5, $amount4);
                      }

                      $s5 = $s5 + 1;
                    }
                    $s4 = $s5;
                  }
                } else {

                  $s5 = $s4;
                  foreach ($subfilter2 as $val4) {

                    $accountname = $val4['account_name'];

                    $amount4 = $val4['amount'];


                    // $m4=money_format('%!i',$amount4);
                    $sheet->setCellValue('d' . $s5, '                 ' . $accountname);
                    if ($amount4 != 0) {
                      $sheet->setCellValue('e' . $s5, $amount4);
                    }
                    $s5 = $s5 + 1;
                  }
                  $s4 = $s5;
                }
                $s3 = $s4;
              }

              $s2 = $s3;

            } else {
              foreach ($subfilter2 as $val4) {



                $accountname = $val4['account_name'];
                $amount4 = $val4['amount'];

                //   $m2=money_format('%!i',$amount4);
                $sheet->setCellValue('d' . $s3, '          ' . $accountname);
                if ($amount4 != 0) {
                  $sheet->setCellValue('e' . $s3, $amount4);
                }
                $s3 = $s3 + 1;

              }
              $s2 = $s3;

            }
          }

          $y = $s2;
        }
        $s = $y;

        $t = $arr_new;
        $m2 = money_format('%!i', $t);
        $sheet->setCellValue('d' . $s, ' ' . $name0 . ' Total ');
        $sheet->getStyle('d' . $s)->applyFromArray($styleArray);
        $sheet->getStyle('f' . $s)->applyFromArray($styleArray);

        $sheet->setCellValue('f' . $s, $m2);



      }
      setlocale(LC_MONETARY, 'en_IN');
      $total = 0;
      error_reporting(0);

      $datas = \DB::select("SELECT * FROM `f_account_class_t` where account_class_id in (2) order by main_account_code asc");

      foreach ($datas as $k => $val) {


        $id = $val->account_class_id;
        $idcode = $val->main_account_code;
        $name0 = ucwords(strtolower($val->account_class_name));

        $filter = $result->where('main_account_id', $idcode);
        $filter->all();
        $filter0 = collect($filter);
        $filter = collect($filter)->map(function ($x) {
          return (array) $x; })->toArray();

        $arr_new = round(array_sum(array_column($filter, 'amount')), 2);
        //dd($arr_new);
        if ($arr_new == 0)
          $arr_new = '-';
        $yn = 10;


        $sub1 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where account_class_id='$id'and parent_class_id='0'");


        foreach ($sub1 as $val1) {
          $p_id = $id = $val1->account_codes_line_id;

          $name1 = ucwords(strtolower($val1->account_code_meaning));
          $code1 = $val1->account_code;
          $filter1 = $filter0->where('sub_account_id', $code1);
          $filter1->all();
          $filter11 = collect($filter1);
          $subfilter2 = $filter1 = collect($filter1)->map(function ($x) {
            return (array) $x; })->toArray();

          $arr_new1 = round(array_sum(array_column($filter1, 'amount')), 2);

          if ($arr_new1 == 0)
            $arr_new1 = '-';


          $m = money_format('%!i', $arr_new1);
          $sheet->setCellValue('a' . $yn, $code1 . ' ' . $name1);
          $sheet->getStyle('a' . $yn)->applyFromArray($styleArray);
          $sheet->setCellValue('c' . $yn, $m);


          $xs2 = $yn + 1;
          $st2 = $xs2;
          //dd($code1);
//Sub2 
          $sub2 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");

          foreach ($sub2 as $val2) {
            $id = $subid = $val2->account_codes_line_id;
            $name2 = ucwords(strtolower($val2->account_code_meaning));
            $code2 = $val2->account_code;
            $filter1 = $filter11->where('future_reference1', $code2);
            $filter1->all();
            $subfilter11 = collect($filter1);
            $subfilter2 = $filter1 = collect($filter1)->map(function ($x) {
              return (array) $x; })->toArray();

            $arr_new2 = round(array_sum(array_column($filter1, 'amount')), 2);
            if ($arr_new2 == 0)
              $arr_new2 = '-';


            $m1 = $arr_new2;
            $sheet->setCellValue('a' . $st2, '    ' . $code2 . ' ' . $name2);

            $sub3 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$subid'");

            if (count($sub3) < 0) {
              if ($m1 != 0) {
                $sheet->setCellValue('b' . $st2, $m1);
              }
            }

            $stx3 = $st2 + 1;
            $st3 = $stx3;
            if (count($sub3) > 0) {
              foreach ($sub3 as $val3) {
                // dd($val3);
                $id = $sub3id = $val3->account_codes_line_id;
                $name3 = ucwords(strtolower($val3->account_code_meaning));
                $code3 = $val3->account_code;
                $subfilter1 = $subfilter11->where('future_reference2', $code3);
                $subfilter1->all();
                $subfilter12 = collect($subfilter1);
                $subfilter2 = $subfilter1 = collect($subfilter1)->map(function ($x) {
                  return (array) $x; })->toArray();

                $arr_new3 = round(array_sum(array_column($subfilter1, 'amount')), 2);
                if ($arr_new3 == 0)
                  $arr_new3 = '-';


                $m2 = $arr_new3;
                $sheet->setCellValue('a' . $st3, '         ' . $code3 . ' ' . $name3);

                $sub4 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$sub3id'");

                if (count($sub4) < 0) {
                  $sheet->setCellValue('b' . $st3, $m2);
                }


                $stx4 = $st3 + 1;
                $st4 = $stx4;
                if (count($sub4) > 0) {

                  foreach ($sub4 as $val4) {

                    $id = $sub4id = $val4->account_codes_line_id;
                    $name4 = ucwords(strtolower($val4->account_code_meaning));
                    $code4 = $val4->account_code;
                    $subfilter2 = $subfilter12->where('sub_account4_id', $code4);
                    $subfilter2->all();
                    $subfilter2 = collect($subfilter2)->map(function ($x) {
                      return (array) $x; })->toArray();

                    $arr_new4 = round(array_sum(array_column($subfilter2, 'amount')), 2);
                    if ($arr_new4 == 0)
                      $arr_new4 = '-';




                    $sheet->setCellValue('a' . $st4, '                ' . $code4 . ' ' . $name4);
                    if ($arr_new4 != 0) {
                      $sheet->setCellValue('b' . $st4, $arr_new4);
                    }
                    $st5 = $st4;

                    foreach ($subfilter2 as $val4) {
                      //  dd($subfilter2);

                      $accid = $val4['account_id'];

                      $accountname = $val4['account_name'];
                      $amount4 = $val4['amount'];

                      $sheet->setCellValue('a' . $st5, '                  ' . $accountname);
                      if ($amount4 != 0) {
                        $sheet->setCellValue('b' . $st5, $amount4);
                      }

                      $st5 = $st5 + 1;
                    }
                    $st4 = $st5;
                  }
                } else {

                  $st5 = $st4;
                  foreach ($subfilter2 as $val4) {

                    $accid = $val4['account_id'];

                    //rr
                    $accountname = $val4['account_name'];
                    $amount4 = $val4['amount'];

                    $sheet->setCellValue('a' . $st5, '                ' . $accountname);

                    if ($amount4 != 0) {
                      $sheet->setCellValue('b' . $st5, $amount4);
                    }

                    $st5 = $st5 + 1;
                  }
                  $st4 = $st5;
                }

                $st3 = $st4;
              }
              $st2 = $st3;
            } else {

              foreach ($subfilter2 as $val4) {



                $accountname = $val4['account_name'];
                $amount4 = $val4['amount'];


                $m = money_format('%!i', $amount4);
                $sheet->setCellValue('a' . $st3, '                     ' . $accountname);
                $sheet->setCellValue('b' . $st3, $m);



                $st3 = $st3 + 1;

              }
              $st2 = $st3;

            }




          }

          $yn = $st2;




        }



        $m2 = money_format('%!i', $profit);
        $sheet->setCellValue('a' . $yn, ' Profit & Loss A/C ');
        $sheet->getStyle('a' . $yn)->applyFromArray($styleArray);
        $sheet->getStyle('c' . $yn)->applyFromArray($styleArray);

        $sheet->setCellValue('c' . $yn, $m2);
        $yn = $yn + 1;

        $m = money_format('%!i', $arr_new + $profit);
        $sheet->setCellValue('a' . $yn, ' ' . $name0 . ' Total');
        $sheet->getStyle('a' . $yn)->applyFromArray($styleArray);
        $sheet->getStyle('c' . $yn)->applyFromArray($styleArray);

        $sheet->setCellValue('c' . $yn, $m);

      }

      //lines end

      $writer1 = new Xlsx($spreadsheet);

      // Save the new .xlsx file
      $writer1->save('balencesheetnew.xlsx');


      $class = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf::class;
      \PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', $class);
      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Pdf');
      // $pdf_path   ='tcpdf_output.pdf'; 
// $writer->save($pdf_path);



      $filepath = 'balencesheetnew.xlsx';
      $myfile = $filepath;

      return $myfile;


    }

    return $html;

  }


  //  profitandlossbalstdtindex
  public function profitandlossbalstdtindex()
  {
    $this->data['result'] = [];
    $this->data['location_id'] = $this->jCombo('m_location_t', 'location_id', 'location_name', '1');
    return view('trailbalance.profitandlossbalstd', $this->data);
  }

  // profitandlossbalstdtindex -Skm
  public function getprofitandlossbalstd($start_date = null, $end_date = null)
  {
    error_reporting(0);

    $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? date("Y-m-d", strtotime($_GET['start_date'])) : '';
    $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
    $location_id = isset($_GET['location_id']) && !empty($_GET['location_id']) ? $_GET['location_id'] : '';
    $bwh1 = '';

    if ($location_id != 1 && $location_id != '') {
      $bwh1 .= " and f_journal_entry_lines_t.location_id=$location_id";
      //$qohwh.=" and "; 
    }
    $org = \Session::get('organization');
    $loc = \Session::get('location');
    $compy = \Session::get('companyid');


    $cost = \DB::select("select sum(total) as total from(SELECT round(sum(m_products_t.`Std Cost`*s_invoice_lines_t.qty),2)as total FROM `s_invoice_lines_t` join s_invoice_hdr_t on s_invoice_hdr_t.invoice_hdr_id=s_invoice_lines_t.invoice_hdr_id and s_invoice_hdr_t.invoice_status='APPROVED' and s_invoice_hdr_t.invoice_date between '$start_date' and '$end_date' join m_products_t on m_products_t.product_id=s_invoice_lines_t.product_id and m_products_t.product_group_id=1
union ALL
SELECT round(sum(i_qoh_detail_t.cost*s_invoice_lines_t.qty),2)as total FROM `s_invoice_lines_t` join s_invoice_hdr_t on s_invoice_hdr_t.invoice_hdr_id=s_invoice_lines_t.invoice_hdr_id and s_invoice_hdr_t.invoice_status='APPROVED'  and s_invoice_hdr_t.invoice_date between '$start_date' and '$end_date' join m_products_t on m_products_t.product_id=s_invoice_lines_t.product_id and m_products_t.product_group_id!=1
join i_qoh_detail_t on i_qoh_detail_t.batch_number=s_invoice_lines_t.batch_number and i_qoh_detail_t.product_id=s_invoice_lines_t.product_id and i_qoh_detail_t.qoh_source='PURCHASE_STOREMOVE')f");


    $total = $cost[0]->total;

    $SQL = "select account_id,main_account_code,main,sub1,sub2,sub3,sub4,
       amount as amount,`main_account_id`,
  `sub_account_id`,
  `future_reference1`,
  `future_reference2`,
  `sub_account4_id`,
  `account_name`            
 from ((select v1.account_id,v1.main_account_code,v1.main,v1.sub1,v1.sub2,v1.sub3,v1.sub4,round(sum(v1.amount),2)  as amount,v1.`main_account_id`,
 v1.`sub_account_id`,
 v1.`future_reference1`,
 v1.`future_reference2`,
 v1.`sub_account4_id`,
 v1.`account_name` from(SELECT
        f.f_account_structure_id as account_id,m.main_account_code,
        m.account_class_name AS main,
        r1.account_code_meaning AS sub1,
        IF(
            f.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4, 0 as amount,
        f.`main_account_id`,
        f.`sub_account_id`,
        f.`future_reference1`,
        f.`future_reference2`,
        f.`sub_account4_id`,
        f.`account_name`           
    FROM
        f_account_structure_t AS f
    LEFT JOIN f_account_class_t AS m
    ON
        m.main_account_code = f.main_account_id
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_code = f.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_code = f.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_code = f.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_code = f.sub_account4_id
        where m.main_account_code=3  union all (SELECT 
            f_journal_entry_lines_t.account_id,f_account_class_t.main_account_code,'' as main,'' as sub1,'' as sub2,'' as sub3,'' as sub4,
          ROUND(
                SUM(
                    f_journal_entry_lines_t.credit_amount- f_journal_entry_lines_t.debit_amount
                ),
                2
            )  AS amount,
'' as `main_account_id`,
        '' as `sub_account_id`,
        '' as `future_reference1`,
        '' as `future_reference2`,
        '' as `sub_account4_id`,
        '' as `account_name`           
        FROM
            `f_journal_entry_t`
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        LEFT JOIN f_account_class_t on f_account_class_t.main_account_code=f_account_structure_t.main_account_id
        WHERE
           ( f_account_class_t.main_account_code=3 ) $bwh1  AND f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY
            f_journal_entry_lines_t.account_id
        ORDER BY
            1))v1 group by v1.account_id ORDER by v1.main_account_code asc) union all (select v2.account_id,v2.main_account_code,v2.main,v2.sub1,v2.sub2,v2.sub3,v2.sub4,round(sum(v2.amount),2)  as amount, v2.`main_account_id`,
            v2.`sub_account_id`,
            v2.`future_reference1`,
            v2.`future_reference2`,
            v2.`sub_account4_id`,
            v2.`account_name` from(SELECT
        f.f_account_structure_id as account_id,m.main_account_code,
        m.account_class_name AS main,
        r1.account_code_meaning AS sub1,
        IF(
            f.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4, 0 as amount,

        f.`main_account_id`,
        f.`sub_account_id`,
        f.`future_reference1`,
        f.`future_reference2`,
        f.`sub_account4_id`,
        f.`account_name`

    FROM
        f_account_structure_t AS f
    LEFT JOIN f_account_class_t AS m
    ON
        m.main_account_code = f.main_account_id
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_code = f.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_code = f.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_code = f.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_code = f.sub_account4_id
        where m.main_account_code=4  union all (SELECT
            f_journal_entry_lines_t.account_id,f_account_class_t.main_account_code,'' as main,'' as sub1,'' as sub2,'' as sub3,'' as sub4,
          ROUND(
                SUM(
                    f_journal_entry_lines_t.debit_amount- f_journal_entry_lines_t.credit_amount
                ),
                2
            )  AS amount,'' as `main_account_id`,
            '' as `sub_account_id`,
            '' as `future_reference1`,
            '' as `future_reference2`,
            '' as `sub_account4_id`, 
            '' as `account_name`
           
        FROM
            `f_journal_entry_t`
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        LEFT JOIN f_account_class_t on f_account_class_t.main_account_code=f_account_structure_t.main_account_id
        WHERE
           ( f_account_class_t.main_account_code=4 ) $bwh1 AND f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY
            f_journal_entry_lines_t.account_id
        ORDER BY
            1))v2 group by v2.account_id ORDER by v2.main_account_code asc) )v3 order by v3.main_account_code asc";

    $result = \DB::select($SQL);

    Log::info("DB -> " . $SQL);
    if (isset($_GET['download'])) {
      $result1 = collect($result)->map(function ($x) {
        return (array) $x; })->toArray();
      $data = '';

      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename=data.xls');
      $output = fopen('profitandloss.xls', 'w');

      $rows = array();
      $rows = "Account Class\t";
      $rows .= "Account Type\t";
      $rows .= "Sub1\t";
      $rows .= "Sub2\t";
      $rows .= "Sub3\t";
      // $rows.="Sub4\t";
      $rows .= "Name\t";
      $rows .= "Amount(in Rs.)\n";

      //fputcsv($output, $rows); 

      foreach ($result1 as $row) {
        // $rows=array();
        $rows .= $row['main_account_code'] . "\t";
        $rows .= $row['main'] . "\t";
        $rows .= $row['sub1'] . "\t";
        $rows .= $row['sub2'] . "\t";
        $rows .= $row['sub3'] . "\t";
        // $rows.=$row['sub4']."\t";
        $rows .= $row['account_name'] . "\t";
        $rows .= $row['amount'] . "\n";

        //fputcsv($output, $rows); 

      }
      fwrite($output, $rows);

      return 1;
    }

    //dd($result);

    $result = collect($result);


    $data = \DB::select("SELECT * FROM `f_account_class_t` where account_class_id in (1,2,3,4)");

    setlocale(LC_MONETARY, 'en_IN');
    $total = 0;
    error_reporting(0);
    $html = '<table class="table" style="    width: 100%;"><thead><th>Particulars</th><th>Amount in (Rs.)</th><th>Amount in (Rs.)</th></thead><tbody>';
    foreach ($data as $k => $val) {


      $id = $val->account_class_id;
      $idcode = $val->main_account_code;
      $name0 = ucwords(strtolower($val->account_class_name));

      $filter = $result->where('main_account_id', $idcode);
      $filter->all();
      $filter0 = collect($filter);
      $filter = collect($filter)->map(function ($x) {
        return (array) $x; })->toArray();

      $arr_new = round(array_sum(array_column($filter, 'amount')), 2);








      $html .= "<tr class='heading'><td><b>$name0</b></td><td></td><td></td></tr><tr><td></td><td></td><td></td></tr>";

      $sub1 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where account_class_id='$id'and parent_class_id='0'");




      if ($k == 1) {
        $closing = \DB::select("select sum(debit_amount-credit_amount)as t from f_journal_entry_lines_t join f_account_structure_t on f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id where f_journal_entry_lines_t.journal_date<'$start_date' and f_account_structure_t.main_account_id='4' $bwh1");

        $closingval = 0;
        if (count($closing)) {
          $closingval = $closing[0]->t;
        }






        $html .= "<tr><td><b>Opening Stock</b></td><td></td><td style='
text-align: right;
'><b>" . money_format('%!i', $closingval) . "</b></td></tr>";
        $arr_new = $arr_new + $closingval;

      }

      if ($arr_new == 0)
        $arr_new = '-';

      foreach ($sub1 as $val1) {
        $p_id = $id = $val1->account_codes_line_id;
        $p_id = $id = $val1->account_codes_line_id;
        $name1 = ucwords(strtolower($val1->account_code_meaning));
        $code1 = $val1->account_code;
        $filter1 = $filter0->where('sub_account_id', $code1);
        $filter1->all();
        $filter11 = collect($filter1);
        $filter1 = collect($filter1)->map(function ($x) {
          return (array) $x; })->toArray();

        $arr_new1 = round(array_sum(array_column($filter1, 'amount')), 2);

        if ($arr_new1 == 0)
          $arr_new1 = '-';

        $html .= "<tr><td class='parent' data='$id' col='0'>$code1  $name1</td><td ></td><td style='
    text-align: right;
'>" . money_format('%!i', $arr_new1) . "</td></tr>";

        //Sub2 
        $sub2 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");

        foreach ($sub2 as $val2) {
          $id = $val2->account_codes_line_id;
          $p_id1 = $id = $val2->account_codes_line_id;
          $name2 = ucwords(strtolower($val2->account_code_meaning));
          $code2 = $val2->account_code;
          $filter1 = $filter11->where('future_reference1', $code2);
          $filter1->all();
          //$filter11=collect($filter1);
          $filter1 = collect($filter1)->map(function ($x) {
            return (array) $x; })->toArray();

          $arr_new2 = round(array_sum(array_column($filter1, 'amount')), 2);
          if ($arr_new2 == 0)
            $arr_new2 = '-';

          $html .= "<tr class='child child$p_id' ><td class='parent parent_hide$p_id' data='$id' col='0' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code2  $name2</td><td style='
    text-align: right;
'>" . money_format('%!i', $arr_new2) . "</td><td></td></tr>";

          $sub3 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");

          foreach ($sub3 as $val3) {
            $p_id2 = $id = $val3->account_codes_line_id;
            $name3 = ucwords(strtolower($val3->account_code_meaning));
            $code3 = $val3->account_code;
            $filter1 = $filter11->where('future_reference2', $code3);
            $filter1->all();
            //$filter11=collect($filter1);
            $filter1 = collect($filter1)->map(function ($x) {
              return (array) $x; })->toArray();
            //dd($filter1);
            $arr_new3 = round(array_sum(array_column($filter1, 'amount')), 2);
            if ($arr_new3 == 0)
              $arr_new3 = '-';

            $html .= "<tr class='child child$p_id1'><td class='parent parent_hide$p_id1' data='$id' col='0'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code3  $name3</td><td style='
    text-align: right;
'>" . money_format('%!i', $arr_new3) . "</td><td></td></tr>";

            foreach ($filter1 as $val4) {

              $accountname = $val4['account_name'];
              $amount4 = $val4['amount'];
              $html .= "<tr class='child child$p_id2'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</td><td style='
    text-align: right;
'>" . money_format('%!i', $amount4) . "</td><td></td></tr>";

            }

          }

        }

      }

      if ($k == 0) {
        $closing = \DB::select("select sum(credit_amount-debit_amount)as t from f_journal_entry_lines_t join f_account_structure_t on f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id where f_journal_entry_lines_t.journal_date<'$start_date' and f_account_structure_t.main_account_id='3' $bwh1");

        $closingval = 0;
        if (count($closing)) {
          $closingval = $closing[0]->t;
        }

        $html .= "<tr><td><b>Closing Stock</b></td><td></td><td style='
text-align: right;
'><b>" . money_format('%!i', $closingval) . "</b></td></tr>";
        $arr_new = $arr_new + $closingval;

      }


      if ($k == 0) {

        $html .= "<tr><td><b>$name0 Total</b></td><td></td><td style='
text-align: right;
'><b>" . money_format('%!i', $arr_new) . "</b></td></tr>";

        $total = $arr_new;
      } else {
        $total = $total - $arr_new;


        $html .= "<tr class='heading'><td><b>Net Income</b></td><td></td><td style='
text-align: right;
'><b>" . money_format('%!i', $total) . "</b></td></tr>";

        $arr_new = $total + $arr_new;

        $html .= "<tr><td><b>$name0 Total</b></td><td></td><td style='
text-align: right;
'><b>" . money_format('%!i', $arr_new) . "</b></td></tr>";

      }

    }

    // $html.="<tr class='heading'><td><b>Net Income</b></td><td></td><td style='
// text-align: right;
// '><b>".money_format('%!i',$total)."</b></td></tr>";
    return $html;


  }





  // OLd _ PL JRK 
  public function profitandloss()
  {

    $this->data['ship_date'] = \DB::select("SELECT f_journal_entry_t.journal_date,f_journal_entry_lines_t.created_at FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id WHERE f_journal_entry_t.journal_name LIKE '%SHIP-SO%' ORDER BY f_journal_entry_t.journal_entry_id DESC LIMIT 1");


    $this->data['result'] = [];
    return view('trailbalance.profitandloss', $this->data);
    //	return view('trailbalance.profitandlossbalstd',$this->data);	
  }

  public function getprofitandloss($start_date = null, $end_date = null)
  {

    $id = 0;

    if ($start_date) {
      $id = 1;
    } else {
      $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? date("Y-m-d", strtotime($_GET['start_date'])) : '';
      $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
    }

         
    $SQL = "select v3.*,f_account_structure_t.main_account_id,f_account_structure_t.sub_account_id,f_account_structure_t.future_reference1,f_account_structure_t.future_reference2 from ((select v1.account_id,v1.main_account_code,v1.main,v1.sub1,v1.sub2,v1.sub3,v1.sub4,round(sum(v1.amount),2)  as amount from(SELECT
        f.f_account_structure_id as account_id,m.main_account_code,
        m.account_class_name AS main,
        r1.account_code_meaning AS sub1,
        IF(
            f.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4, 0 as amount
    FROM
        f_account_structure_t AS f
    LEFT JOIN f_account_class_t AS m
    ON
        m.account_class_id = f.main_account_id
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_codes_line_id = f.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_codes_line_id = f.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_codes_line_id = f.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_codes_line_id = f.sub_account4_id
        where m.main_account_code=600000  union all (SELECT 
            f_journal_entry_lines_t.account_id,f_account_class_t.main_account_code,'' as main,'' as sub1,'' as sub2,'' as sub3,'' as sub4,
          ROUND(
                SUM(
                    f_journal_entry_lines_t.credit_amount- f_journal_entry_lines_t.debit_amount
                ),
                2
            )  AS amount
           
        FROM
            `f_journal_entry_t`
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
        WHERE
           ( f_account_class_t.main_account_code=600000 ) AND f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY
            f_journal_entry_lines_t.account_id
        ORDER BY
            1))v1 group by v1.account_id ORDER by v1.main_account_code asc) union all (select v2.account_id,v2.main_account_code,v2.main,v2.sub1,v2.sub2,v2.sub3,v2.sub4,round(sum(v2.amount),2)  as amount from(SELECT
        f.f_account_structure_id as account_id,m.main_account_code,
        m.account_class_name AS main,
        r1.account_code_meaning AS sub1,
        IF(
            f.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4, 0 as amount
    FROM
        f_account_structure_t AS f
    LEFT JOIN f_account_class_t AS m
    ON
        m.account_class_id = f.main_account_id
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_codes_line_id = f.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_codes_line_id = f.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_codes_line_id = f.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_codes_line_id = f.sub_account4_id
        where m.main_account_code=700000  union all (SELECT
            f_journal_entry_lines_t.account_id,f_account_class_t.main_account_code,'' as main,'' as sub1,'' as sub2,'' as sub3,'' as sub4,
          ROUND(
                SUM(
                    f_journal_entry_lines_t.debit_amount- f_journal_entry_lines_t.credit_amount
                ),
                2
            )  AS amount
           
        FROM
            `f_journal_entry_t`
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
        WHERE
           ( f_account_class_t.main_account_code=700000 ) AND f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY
            f_journal_entry_lines_t.account_id
        ORDER BY
            1))v2 group by v2.account_id ORDER by v2.main_account_code asc) )v3 join f_account_structure_t on f_account_structure_t.f_account_structure_id=v3.account_id  order by v3.main_account_code asc";

    $result1 = \DB::select($SQL);
    if ($id == 1) {


      $result = collect($result1);

      //$arr_new = array_sum(array_column($result, 'amount'));

      $lia = $result->where('main_account_code', 'like', '600000');
      $lia->all();
      $lia = json_decode(json_encode($lia));
      $arr_new1 = array_sum(array_column($lia, 'amount'));



      $arr_new = array_sum(array_column($result1, 'amount'));

      $sum = $arr_new1 - ($arr_new - $arr_new1);
      return $sum;
    }


    if (isset($_GET['download'])) {
      $result1 = collect($result1)->map(function ($x) {
        return (array) $x; })->toArray();
      $data = '';

      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename=data.xls');
      $output = fopen('profitandlossregular.xls', 'w');

      $rows = array();
      $rows = "Account Class\t";
      $rows .= "Account Type\t";
      $rows .= "Sub1\t";
      $rows .= "Sub2\t";
      $rows .= "Sub3\t";
      $rows .= "Sub4\t";
      $rows .= "Amount(in Rs.)\n";

      //fputcsv($output, $rows); 

      foreach ($result1 as $row) {
        // $rows=array();
        $rows .= $row['main_account_code'] . "\t";
        $rows .= $row['main'] . "\t";
        $rows .= $row['sub1'] . "\t";
        $rows .= $row['sub2'] . "\t";
        $rows .= $row['sub3'] . "\t";
        $rows .= $row['sub4'] . "\t";
        $rows .= $row['amount'] . "\n";

        //fputcsv($output, $rows); 

      }
      fwrite($output, $rows);

      return 1;
    }



    $result = collect($result1);


    $data = \DB::select("SELECT * FROM `f_account_class_t` where account_class_id in (3,9)");

    setlocale(LC_MONETARY, 'en_IN');
    $total = 0;
    error_reporting(0);
    $html = '<table class="table table-bordered table-striped table-hover align-middle" style="width: 100%;"><thead><th>Particulars</th><th>Amount in (Rs.)</th><th>Amount in (Rs.)</th></thead><tbody>';
    foreach ($data as $k => $val) {


      $id = $val->account_class_id;
      $name0 = ucwords(strtolower($val->account_class_name));

      $filter = $result->where('main_account_id', $id);
      $filter->all();
      $filter0 = collect($filter);
      $filter = collect($filter)->map(function ($x) {
        return (array) $x; })->toArray();

      $arr_new = round(array_sum(array_column($filter, 'amount')), 2);
      if ($arr_new == 0)
        $arr_new = '-';

      $html .= "<tr class='heading'><td><b>$name0</b></td><td></td><td></td></tr><tr><td></td><td></td><td></td></tr>";

      $sub1 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where account_class_id='$id'and parent_class_id='0'");

      foreach ($sub1 as $val1) {
        $p_id = $id = $val1->account_codes_line_id;
        $name1 = ucwords(strtolower($val1->account_code_meaning));
        $code1 = $val1->account_code;
        $filter1 = $filter0->where('sub_account_id', $id);
        $filter1->all();
        $filter11 = collect($filter1);
        $filter1 = collect($filter1)->map(function ($x) {
          return (array) $x; })->toArray();

        $arr_new1 = round(array_sum(array_column($filter1, 'amount')), 2);

        if ($arr_new1 == 0)
          $arr_new1 = '-';

        $html .= "<tr><td class=parent' data='$id' col='0'>$code1  $name1</td><td ></td><td style='
    text-align: right;
'>" . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new1, 2)) . "</td></tr>";

        //Sub2 
        $sub2 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
        if (count($sub2) > 0) {
          foreach ($sub2 as $val2) {
            $p_id1 = $id = $val2->account_codes_line_id;
            $name2 = ucwords(strtolower($val2->account_code_meaning));
            $code2 = $val2->account_code;
            $filter1 = $filter11->where('future_reference1', $id);
            $filter1->all();
            //$filter11=collect($filter1);
            $filter1 = collect($filter1)->map(function ($x) {
              return (array) $x; })->toArray();

            $arr_new2 = round(array_sum(array_column($filter1, 'amount')), 2);
            if ($arr_new2 == 0)
              $arr_new2 = '-';

            $html .= "<tr class='child child$p_id'>
    <td class='parent' data='$id' col='0' >
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code2  $name2
    </td>
    <td style='text-align: right;'>"
              . preg_replace(
                "/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i",
                "$1,",
                round((float) $arr_new2, 2)
              ) .
              "</td>
    <td></td>
</tr>";

            //Sub3 

            $sub3 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
            if (count($sub3) > 0) {
              foreach ($sub3 as $val3) {

                $id = $val3->account_codes_line_id;
                $p_id2 = $val3->account_codes_line_id;
                $name3 = ucwords(strtolower($val3->account_code_meaning));
                $code3 = $val3->account_code;
                $filter1 = $filter11->where('future_reference2', $id);
                $filter1->all();
                //$filter11=collect($filter1);
                $filter1 = collect($filter1)->map(function ($x) {
                  return (array) $x; })->toArray();

                $arr_new2 = round(array_sum(array_column($filter1, 'amount')), 2);
                if ($arr_new2 == 0)
                  $arr_new2 = '-';

                $html .= "<tr class='child child$p_id1'>
    <td class='parent' data='$id' col='0' >
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code3  $name3
    </td>
    <td style='text-align: right;'>"
                  . preg_replace(
                    "/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i",
                    "$1,",
                    round((float) $arr_new2, 2)
                  ) .
                  "</td>
    <td></td>
</tr>";





                foreach ($filter1 as $val4) {
                  $accid = $val4['account_id'];
                  $accn = \DB::select('select account_name from f_account_structure_t where f_account_structure_id=' . $val4['account_id']);
                  $accountname = $accn[0]->account_name;
                  $amount4 = $val4['amount'];
                  $html .= "<tr class='child child$p_id2'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>" . number_format($amount4, 2) . "</td><td></td></tr>";

                }
              }
            } else {
              foreach ($filter1 as $val4) {
                $accid = $val4['account_id'];
                $accn = \DB::select('select account_name from f_account_structure_t where f_account_structure_id=' . $val4['account_id']);
                $accountname = $accn[0]->account_name;
                $amount4 = $val4['amount'];
                $html .= "<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>" . number_format($amount4, 2) . "</td><td></td></tr>";

              }
            }
          }
        } else {
          foreach ($filter1 as $val4) {
            $accid = $val4['account_id'];
            $accn = \DB::select('select account_name from f_account_structure_t where f_account_structure_id=' . $val4['account_id']);
            $accountname = $accn[0]->account_name;
            $amount4 = $val4['amount'];
            $html .= "<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>" . number_format('%!i', $amount4) . "</td><td></td></tr>";

          }
        }

      }
      $html .= "<tr><td><b>$name0 Total</b></td><td></td><td style='
text-align: right;
'><b>" . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new, 2)) . "</b></td></tr>";
      if ($k == 0) {
        $total = $arr_new;
      } else {
        $total = $total - $arr_new;
      }

    }

    $html .= "<tr class='heading'><td><b>Net Income</b></td><td></td><td style='
text-align: right;
'><b>" . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($total, 2)) . "</b></td></tr>";

    return $html;

  }
	
  public function profitandlossstdtindex()
  {
    $this->data['ship_date'] = \DB::select("SELECT f_journal_entry_t.journal_date,f_journal_entry_lines_t.created_at FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id WHERE f_journal_entry_t.journal_name LIKE '%SHIP-SO%' ORDER BY f_journal_entry_t.journal_entry_id DESC LIMIT 1");
    $this->data['result'] = [];
	  
    return view('trailbalance.profitandlossstd', $this->data);
  }
	
	
  public function getprofitandlossstd($start_date = null, $end_date = null)
  {
    $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? date("Y-m-d", strtotime($_GET['start_date'])) : '';
    $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';

    $org = \Session::get('organization');
    $loc = \Session::get('location');
    $compy = \Session::get('companyid');


    $cost = \DB::select("select sum(total) as total from(SELECT round(sum(m_products_t.`Std Cost`*s_invoice_lines_t.qty),2)as total FROM `s_invoice_lines_t` join s_invoice_hdr_t on s_invoice_hdr_t.invoice_hdr_id=s_invoice_lines_t.invoice_hdr_id and s_invoice_hdr_t.invoice_status='APPROVED' and s_invoice_hdr_t.invoice_date between '$start_date' and '$end_date' join m_products_t on m_products_t.product_id=s_invoice_lines_t.product_id and m_products_t.product_group_id=1
union ALL
SELECT round(sum(i_qoh_detail_t.cost*s_invoice_lines_t.qty),2)as total FROM `s_invoice_lines_t` join s_invoice_hdr_t on s_invoice_hdr_t.invoice_hdr_id=s_invoice_lines_t.invoice_hdr_id and s_invoice_hdr_t.invoice_status='APPROVED'  and s_invoice_hdr_t.invoice_date between '$start_date' and '$end_date' join m_products_t on m_products_t.product_id=s_invoice_lines_t.product_id and m_products_t.product_group_id!=1
join i_qoh_detail_t on i_qoh_detail_t.batch_number=s_invoice_lines_t.batch_number and i_qoh_detail_t.product_id=s_invoice_lines_t.product_id and i_qoh_detail_t.qoh_source='PURCHASE_STOREMOVE')f");

    $total = $cost[0]->total;

    $SQL = "select account_id,main_account_code,main,sub1,sub2,sub3,sub4,
        (Case
  when account_id='11' then $total
  when account_id='85' then 0
  else amount
  end) as amount,`main_account_id`,
  `sub_account_id`,
  `future_reference1`,
  `future_reference2`,
  `sub_account4_id` 
 from ((select v1.account_id,v1.main_account_code,v1.main,v1.sub1,v1.sub2,v1.sub3,v1.sub4,round(sum(v1.amount),2)  as amount,v1.`main_account_id`,
 v1.`sub_account_id`,
 v1.`future_reference1`,
 v1.`future_reference2`,
 v1.`sub_account4_id` from(SELECT
        f.f_account_structure_id as account_id,m.main_account_code,
        m.account_class_name AS main,
        r1.account_code_meaning AS sub1,
        IF(
            f.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4, 0 as amount,
        f.`main_account_id`,
        f.`sub_account_id`,
        f.`future_reference1`,
        f.`future_reference2`,
        f.`sub_account4_id`
    FROM
        f_account_structure_t AS f
    LEFT JOIN f_account_class_t AS m
    ON
        m.account_class_id = f.main_account_id
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_codes_line_id = f.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_codes_line_id = f.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_codes_line_id = f.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_codes_line_id = f.sub_account4_id
        where m.main_account_code=600000  union all (SELECT 
            f_journal_entry_lines_t.account_id,f_account_class_t.main_account_code,'' as main,'' as sub1,'' as sub2,'' as sub3,'' as sub4,
          ROUND(
                SUM(
                    f_journal_entry_lines_t.credit_amount- f_journal_entry_lines_t.debit_amount
                ),
                2
            )  AS amount,
'' as `main_account_id`,
        '' as `sub_account_id`,
        '' as `future_reference1`,
        '' as `future_reference2`,
        '' as `sub_account4_id`           
        FROM
            `f_journal_entry_t`
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
        WHERE
           ( f_account_class_t.main_account_code=600000 ) AND f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY
            f_journal_entry_lines_t.account_id
        ORDER BY
            1))v1 group by v1.account_id ORDER by v1.main_account_code asc) union all (select v2.account_id,v2.main_account_code,v2.main,v2.sub1,v2.sub2,v2.sub3,v2.sub4,round(sum(v2.amount),2)  as amount, v2.`main_account_id`,
            v2.`sub_account_id`,
            v2.`future_reference1`,
            v2.`future_reference2`,
            v2.`sub_account4_id` from(SELECT
        f.f_account_structure_id as account_id,m.main_account_code,
        m.account_class_name AS main,
        r1.account_code_meaning AS sub1,
        IF(
            f.future_reference1 > 0,
            r2.account_code_meaning,
            ''
        ) AS sub2,
        IF(
            f.future_reference2 > 0,
            r3.account_code_meaning,
            ''
        ) AS sub3,
        IF(
            f.sub_account4_id > 0,
            r4.account_code_meaning,
            ''
        ) AS sub4, 0 as amount,

        f.`main_account_id`,
        f.`sub_account_id`,
        f.`future_reference1`,
        f.`future_reference2`,
        f.`sub_account4_id`

    FROM
        f_account_structure_t AS f
    LEFT JOIN f_account_class_t AS m
    ON
        m.account_class_id = f.main_account_id
    LEFT JOIN f_account_codes_lines_t AS r1
    ON
        r1.account_codes_line_id = f.sub_account_id
    JOIN f_account_codes_lines_t AS r2
    ON
        r2.account_codes_line_id = f.future_reference1
    LEFT JOIN f_account_codes_lines_t AS r3
    ON
        r3.account_codes_line_id = f.future_reference2
    LEFT JOIN f_account_codes_lines_t AS r4
    ON
        r4.account_codes_line_id = f.sub_account4_id
        where m.main_account_code=700000  union all (SELECT
            f_journal_entry_lines_t.account_id,f_account_class_t.main_account_code,'' as main,'' as sub1,'' as sub2,'' as sub3,'' as sub4,
          ROUND(
                SUM(
                    f_journal_entry_lines_t.debit_amount- f_journal_entry_lines_t.credit_amount
                ),
                2
            )  AS amount,'' as `main_account_id`,
            '' as `sub_account_id`,
            '' as `future_reference1`,
            '' as `future_reference2`,
            '' as `sub_account4_id` 
           
        FROM
            `f_journal_entry_t`
        LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
        LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
        LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
        WHERE
           ( f_account_class_t.main_account_code=700000 ) AND f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date'
        GROUP BY
            f_journal_entry_lines_t.account_id
        ORDER BY
            1))v2 group by v2.account_id ORDER by v2.main_account_code asc) )v3 order by v3.main_account_code asc";

    $result = \DB::select($SQL);


    if (isset($_GET['download'])) {
      $result1 = collect($result)->map(function ($x) {
        return (array) $x; })->toArray();
      $data = '';

      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename=data.xls');
      $output = fopen('profitandloss.xls', 'w');

      $rows = array();
      $rows = "Account Class\t";
      $rows .= "Account Type\t";
      $rows .= "Sub1\t";
      $rows .= "Sub2\t";
      $rows .= "Sub3\t";
      $rows .= "Sub4\t";
      $rows .= "Amount(in Rs.)\n";

      //fputcsv($output, $rows); 

      foreach ($result1 as $row) {
        // $rows=array();
        $rows .= $row['main_account_code'] . "\t";
        $rows .= $row['main'] . "\t";
        $rows .= $row['sub1'] . "\t";
        $rows .= $row['sub2'] . "\t";
        $rows .= $row['sub3'] . "\t";
        $rows .= $row['sub4'] . "\t";
        $rows .= $row['amount'] . "\n";

        //fputcsv($output, $rows); 

      }
      fwrite($output, $rows);

      return 1;
    }

    //dd($result);

    $result = collect($result);


    $data = \DB::select("SELECT * FROM `f_account_class_t` where account_class_id in (3,9)");

    setlocale(LC_MONETARY, 'en_IN');
    $total = 0;
    error_reporting(0);
    $html = '<table class="table" style="    width: 100%;"><thead><th>Particulars</th><th>Amount in (Rs.)</th><th>Amount in (Rs.)</th></thead><tbody>';
    foreach ($data as $k => $val) {


      $id = $val->account_class_id;
      $name0 = ucwords(strtolower($val->account_class_name));

      $filter = $result->where('main_account_id', $id);
      $filter->all();
      $filter0 = collect($filter);
      $filter = collect($filter)->map(function ($x) {
        return (array) $x; })->toArray();

      $arr_new = round(array_sum(array_column($filter, 'amount')), 2);
      if ($arr_new == 0)
        $arr_new = '-';

      $html .= "<tr class='heading'><td><b>$name0</b></td><td></td><td></td></tr><tr><td></td><td></td><td></td></tr>";

      $sub1 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where account_class_id='$id'and parent_class_id='0'");

      foreach ($sub1 as $val1) {
        $p_id = $id = $val1->account_codes_line_id;
        $name1 = ucwords(strtolower($val1->account_code_meaning));
        $code1 = $val1->account_code;
        $filter1 = $filter0->where('sub_account_id', $id);
        $filter1->all();
        $filter11 = collect($filter1);
        $filter1 = collect($filter1)->map(function ($x) {
          return (array) $x; })->toArray();

        $arr_new1 = round(array_sum(array_column($filter1, 'amount')), 2);

        if ($arr_new1 == 0)
          $arr_new1 = '-';

        $html .= "<tr><td class=parent' data='$id' col='0'>$code1  $name1</td><td ></td><td style='
    text-align: right;
'>" . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new1, 2)) . "</td></tr>";

        //Sub2 
        $sub2 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
        if (count($sub2) > 0) {
          foreach ($sub2 as $val2) {
            $id = $val2->account_codes_line_id;
            $p_id1 = $val2->account_codes_line_id;
            $name2 = ucwords(strtolower($val2->account_code_meaning));
            $code2 = $val2->account_code;
            $filter1 = $filter11->where('future_reference1', $id);
            $filter1->all();
            //$filter11=collect($filter1);
            $filter1 = collect($filter1)->map(function ($x) {
              return (array) $x; })->toArray();

            $arr_new2 = round(array_sum(array_column($filter1, 'amount')), 2);
            if ($arr_new2 == 0)
              $arr_new2 = '-';

            $html .= "<tr class='child child$p_id'><td class='parent' data='$id' col='0' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code2  $name2</td><td style='
    text-align: right;
'>" . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round((float)$arr_new2, 2)) . "</td><td></td></tr>";

            //Sub3 

            $sub3 = \DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
            if (count($sub3) > 0) {
              foreach ($sub3 as $val3) {
                // dd($val3);
                $id = $val3->account_codes_line_id;
                $p_id2 = $val3->account_codes_line_id;
                $name3 = ucwords(strtolower($val3->account_code_meaning));
                $code3 = $val3->account_code;
                $filter1 = $filter11->where('future_reference2', $id);
                $filter1->all();
                //$filter11=collect($filter1);
                $filter1 = collect($filter1)->map(function ($x) {
                  return (array) $x; })->toArray();

                $arr_new2 = round(array_sum(array_column($filter1, 'amount')), 2);
                if ($arr_new2 == 0)
                  $arr_new2 = '-';

                $html .= "<tr class='child child$p_id1'><td class='parent' data='$id' col='0' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code3  $name3</td><td style='
    text-align: right;
'>" . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round((float)$arr_new2, 2)) . "</td><td></td></tr>";
                foreach ($filter1 as $val4) {
                  $accid = $val4['account_id'];
                  $accn = \DB::select('select account_name from f_account_structure_t where f_account_structure_id=' . $val4['account_id']);
                  $accountname = $accn[0]->account_name;
                  $amount4 = $val4['amount'];
                  $html .= "<tr class='child child$p_id2'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>" . number_format($amount4, 2) . "</td><td></td></tr>";

                }
              }
            } else {
              foreach ($filter1 as $val4) {
                $accid = $val4['account_id'];
                $accn = \DB::select('select account_name from f_account_structure_t where f_account_structure_id=' . $val4['account_id']);
                $accountname = $accn[0]->account_name;
                $amount4 = $val4['amount'];
                $html .= "<tr class='child child$id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>" . number_format($amount4, 2) . "</td><td></td></tr>";

              }
            }
          }

        } else {

          foreach ($filter1 as $val4) {
            $accid = $val4['account_id'];
            $accn = \DB::select('select account_name from f_account_structure_t where f_account_structure_id=' . $val4['account_id']);
            $accountname = $accn[0]->account_name;
            $amount4 = $val4['amount'];
            $html .= "<tr class='child child$p_id'><td><a class='linkeid' name='linkeid' onclick='ledgerpop($accid)' value='$accid'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</a></td><td style='
    text-align: right;
'>" . money_format('%!i', $amount4) . "</td><td></td></tr>";

          }
        }
      }
      $html .= "<tr><td><b>$name0 Total</b></td><td></td><td style='
text-align: right;
'><b>" . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($arr_new, 2)) . "</b></td></tr>";
      if ($k == 0) {
        $total = $arr_new;
      } else {
        $total = $total - $arr_new;
      }

    }

    $html .= "<tr class='heading'><td><b>Net Income</b></td><td></td><td style='
text-align: right;
'><b>" . preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", round($total, 2)) . "</b></td></tr>";

    return $html;
  }


  public function getledgerpandlData(Request $request)
  {


    $app_id = \Session::get('id');
    $wh = '';

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
    $ledger_id = $request->ledger_id;

    $ledger_data = \DB::select("SELECT * FROM (select f_journal_entry_t.journal_name,f_journal_entry_t.journal_type,f_journal_entry_lines_t.journal_entry_id,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount,f_journal_entry_lines_t.journal_date,f_journal_entry_lines_t.`f_journal_entry_line_id`, f_journal_entry_lines_t.reference_source, (case WHEN f_journal_entry_lines_t.reference_source='PRODUCT' then m_products_t.concatenated_product
                                                   WHEN f_journal_entry_lines_t.reference_source='EMPLOYEE' then hr_employee_t.first_name
                                                   WHEN f_journal_entry_lines_t.reference_source='SUPPLIER' then m_supplier_t.supplier_name
                                                   WHEN f_journal_entry_lines_t.reference_source='CUSTOMER' then m_customers_t.customer_name
                                                   WHEN f_journal_entry_lines_t.reference_source='MACHINE' then w_machine_hdr_t.machine_name
                                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL/PF/ESI' then m_department_lines_t.sub_department_name
                                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL' then  m_department_lines_t.sub_department_name
                                                  else '' end ) as name   from f_journal_entry_lines_t left JOIN m_products_t ON m_products_t.product_id=f_journal_entry_lines_t.reference_id left JOIN hr_employee_t ON hr_employee_t.employee_id=f_journal_entry_lines_t.reference_id left JOIN m_supplier_t ON m_supplier_t.supplier_id=f_journal_entry_lines_t.reference_id left JOIN m_customers_t ON m_customers_t.customer_id=f_journal_entry_lines_t.reference_id left join w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id=f_journal_entry_lines_t.reference_id left join m_department_lines_t ON m_department_lines_t.department_line_id=f_journal_entry_lines_t.reference_id JOIN f_journal_entry_t ON f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id where f_journal_entry_lines_t.account_id='$ledger_id' and f_journal_entry_lines_t.journal_date between '$start_date' and  '$end_date' and f_journal_entry_t.journal_type!='OPENING BALANCE' order by f_journal_entry_lines_t.journal_date  asc) as v1");

    $balance = \DB::select("select sum(f.debit-f.credit)as balance from( select COALESCE(SUM(debit_amount),0) as debit,COALESCE(SUM(credit_amount),0)as credit from f_journal_entry_lines_t join f_journal_entry_t on f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id where  f_journal_entry_lines_t.account_id='$ledger_id' and (f_journal_entry_lines_t.journal_date < '$start_date' or f_journal_entry_t.journal_type='OPENING BALANCE'))f");
    $balance = round($balance[0]->balance, 2);
    $overall_datas = []; 
    $cop = 0;
    $dop = 0;

    $key = 0;

    foreach ($ledger_data as $k => $value) {

      if ($value->reference_source != '') {
        $id = $value->journal_entry_id;
        $reference_name = $value->name;
        $reference_source = $value->reference_source;
        $debit_amount = $value->debit_amount;
        $credit_amount = $value->credit_amount;

        if ($debit_amount > 0) {
          $d = \DB::select("SELECT account_id,f_account_structure_t.concatenated_segments from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.debit_amount=0");

          if (COUNT($d) == 0) {
            $acc_name = '';
          } else {
            $acc_name = $d[0]->concatenated_segments;
          }

        } else {

          $d = \DB::select("SELECT account_id,f_account_structure_t.concatenated_segments from f_journal_entry_lines_t JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE f_journal_entry_lines_t.journal_entry_id='$id' and f_journal_entry_lines_t.credit_amount=0");
          //dd($d);
          if (COUNT($d) == 0) {
            $acc_name = '';
          } else {
            $acc_name = $d[0]->concatenated_segments;
          }
        }

      } else {
        $id = $value->journal_entry_id;
        $debit_amount = $value->debit_amount;
        $credit_amount = $value->credit_amount;
        if ($debit_amount > 0) {
          $wh1 = " and f_journal_entry_lines_t.debit_amount=0";
        } else {
          $wh1 = " and f_journal_entry_lines_t.credit_amount=0";
        }
        $d = \DB::select("select f_journal_entry_lines_t.journal_entry_id,f_journal_entry_lines_t.debit_amount,f_journal_entry_lines_t.credit_amount,f_journal_entry_lines_t.journal_date,f_journal_entry_lines_t.`f_journal_entry_line_id`,f_account_structure_t.concatenated_segments ,f_journal_entry_lines_t.reference_source, (case WHEN f_journal_entry_lines_t.reference_source='PRODUCT' then m_products_t.concatenated_product
                                                   WHEN f_journal_entry_lines_t.reference_source='EMPLOYEE' then hr_employee_t.first_name
                                                   WHEN f_journal_entry_lines_t.reference_source='SUPPLIER' then m_supplier_t.supplier_name
                                                   WHEN f_journal_entry_lines_t.reference_source='CUSTOMER' then m_customers_t.customer_name
                                                   WHEN f_journal_entry_lines_t.reference_source='MACHINE' then w_machine_hdr_t.machine_name
                                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL/PF/ESI' then m_department_lines_t.sub_department_name
                                                   WHEN f_journal_entry_lines_t.reference_source='PAYROLL' then  m_department_lines_t.sub_department_name
                                                  else '' end ) as name   from f_journal_entry_lines_t left JOIN m_products_t ON m_products_t.product_id=f_journal_entry_lines_t.reference_id left JOIN hr_employee_t ON hr_employee_t.employee_id=f_journal_entry_lines_t.reference_id left JOIN m_supplier_t ON m_supplier_t.supplier_id=f_journal_entry_lines_t.reference_id left JOIN m_customers_t ON m_customers_t.customer_id=f_journal_entry_lines_t.reference_id left join w_machine_hdr_t ON w_machine_hdr_t.machine_hdr_id=f_journal_entry_lines_t.reference_id left join m_department_lines_t ON m_department_lines_t.department_line_id=f_journal_entry_lines_t.reference_id JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id where f_journal_entry_lines_t.journal_entry_id='$id' $wh1");

        $dd = collect($d);
        $dk = $dd->where('credit_amount', $debit_amount)->where('debit_amount', $credit_amount);
        $dk->all();
        if (count($dk) > 0) {
          foreach ($dk as $dkv) {
            $reference_name = $dkv->name;
            $reference_source = $dkv->reference_source;
            $acc_name = $dkv->concatenated_segments;
          }
        } else {

          $reference_name = $d[0]->name;
          $reference_source = $d[0]->reference_source;
          $acc_name = $d[0]->concatenated_segments;
        }

      }


      $overall_datas[$key] = (object) array();

      if ($debit_amount > 0) {
        $overall_datas[$key]->concatenated_segments = $acc_name;
        $overall_datas[$key]->balance = round($balance + $debit_amount, 2);
        $dbal = $balance = $balance + $debit_amount;
        $overall_datas[$key]->debit_amounts = $debit_amount;
        $overall_datas[$key]->credit_amounts = $debit_amount;
        $overall_datas[$key]->net_salary = 0;
        $overall_datas[$key]->journal_date = $value->journal_date;
        $overall_datas[$key]->journal_type = $value->journal_type;
        $overall_datas[$key]->journal_name = $value->journal_name;
        $overall_datas[$key]->reference_source = $reference_source;
        $overall_datas[$key]->reference_name = $reference_name;

      } else {
        $overall_datas[$key]->concatenated_segments = $acc_name;
        $overall_datas[$key]->balance = round($balance - $credit_amount, 2);
        $cbal = $balance = $balance - $credit_amount;
        $overall_datas[$key]->credit_amounts = $credit_amount;
         $overall_datas[$key]->debit_amounts = '';
        $overall_datas[$key]->net_salary = 0;
        $overall_datas[$key]->journal_date = $value->journal_date;
        $overall_datas[$key]->journal_type = $value->journal_type;
        $overall_datas[$key]->journal_name = $value->journal_name;
        $overall_datas[$key]->reference_source = $reference_source;
        $overall_datas[$key]->reference_name = $reference_name;
      }

      $key++;


    }

    $count1 = COUNT($overall_datas);
    $csum = array_column($overall_datas, 'credit_amounts');
    $csum1 = array_sum($csum) - $cop;
    $csum2 = array_sum($csum);
    $dsum = array_column($overall_datas, 'debit_amounts');
    $dsum1 = array_sum($dsum) - $dop;
    $dsum2 = array_sum($dsum);
    $tsum1 = $dsum1 - $csum1;
    $tsum2 = $dsum2 - $csum2;
    $overall_datas[$count1] = new \stdClass();
    $overall_datas[$count1 + 1] = new \stdClass();
    $overall_datas[$count1]->journal_name ='';
    $overall_datas[$count1]->journal_date ='';
    $overall_datas[$count1]->journal_type ='';
    $overall_datas[$count1]->reference_source ='';
     $overall_datas[$count1]->reference_name ='';
    $overall_datas[$count1]->credit_amounts = number_format((float) round($csum1, 3), 3);
    $overall_datas[$count1]->debit_amounts = number_format((float) round($dsum1, 3), 3);
    $overall_datas[$count1]->balance = number_format((float) round($tsum1, 3), 3);
    $overall_datas[$count1]->concatenated_segments = "Current Total";
    $overall_datas[$count1 + 1]->journal_name ='';
    $overall_datas[$count1 + 1]->journal_date ='';
    $overall_datas[$count1 + 1]->journal_type='';
    $overall_datas[$count1 + 1]->reference_source='';
    $overall_datas[$count1 + 1]->reference_name='';
    $overall_datas[$count1 + 1]->credit_amounts = number_format((float) round($csum2, 3), 3);
    $overall_datas[$count1 + 1]->debit_amounts = number_format((float) round($dsum2, 3), 3);
    $overall_datas[$count1 + 1]->balance = number_format((float) round($tsum2, 3), 3);
    $overall_datas[$count1 + 1]->concatenated_segments = "Total";


    return response()->json(['data' => $overall_datas]);

  }

  /* END PROFIT AND LOSS BALANCE*/

  //  Old (JRK) - trialbalance
  public function trialbalance()
  {
    $this->data['result'] = [];
    return view('trailbalance.trialbalancerpt', $this->data);
  }

  //  New (SKM)- trialbalance

  public function trialbalancenew()
  {
    $this->data['result'] = [];

    // $this->data['branch_id'] = $this->jCombo('m_branch_t','branch_id','branch_name','');
    $this->data['location_id'] = $this->jCombo('m_location_t', 'location_id', 'location_name', '1');
    return view('trailbalance.trialbalancerptnew', $this->data);
  }

   public function trialbalancejrk()
{
    // Determine current financial year (April–March)
    $currentMonth = (int) date('n'); // Month number (1–12)
    $currentYear  = (int) date('Y');

    if ($currentMonth >= 4) {
        // April or later → FY starts this April
        $start_date = date('Y-04-01', strtotime("{$currentYear}-04-01"));
        $end_date   = date('Y-03-31', strtotime(($currentYear + 1) . '-03-31'));
    } else {
        // Jan–Mar → FY started last April
        $start_date = date('Y-04-01', strtotime(($currentYear - 1) . '-04-01'));
        $end_date   = date('Y-03-31', strtotime("{$currentYear}-03-31"));
    }

    // Fetch distinct ledgers
    $ledgers = \DB::table('f_account_structure_t')
        ->select('f_account_structure_id as account_id', 'account_description')
        ->groupBy('account_description', 'f_account_structure_id')
        ->orderBy('account_description')
        ->get();

    // Return view with default financial-year dates and ledger list
    return view('trailbalance.trialbalancejrk', compact('start_date', 'end_date', 'ledgers'),$this->data);
}


  // Old (JRK) - gettrialbalance

  public function gettrialbalance(Request $request)
  {

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
    $type = $request->type ?: '';

    if ($type == "POSTED") {
      $type = "f_journal_entry_t.journal_status='POSTED' and";
    } else {
      $type = "";
    }

    $SQL = "SELECT
    MAX(journal_name) AS journal_name,
    SUM(deb1 + deb2) AS debit,
    SUM(crd1 + crd2) AS credit,
    MAX(concatenated_segments) AS concatenated_segments,
    SUM(opening_balance) AS opening_balance,
    ROUND(SUM(opening_balance + (deb1 + deb2) - (crd1 + crd2)), 2) AS balance,
    MAX(FS) AS FS,
    account_id
FROM (
    -- Entry 1: Manual + Customer + Sales
    SELECT
        je.journal_name,
        ROUND(SUM(jel.credit_amount), 2) AS deb1,
        ROUND(SUM(jel.debit_amount), 2) AS crd1,
        0 AS deb2,
        0 AS crd2,
        fas.concatenated_segments,
        0 AS opening_balance,
        jel.account_id,
        CASE
            WHEN fas.concatenated_segments LIKE '%60000%' OR fas.concatenated_segments LIKE '%70000%' THEN 'P&L'
            ELSE 'BS'
        END AS FS
    FROM f_journal_entry_t je
    JOIN f_journal_entry_lines_t jel ON je.journal_entry_id = jel.journal_entry_id
    JOIN f_account_structure_t fas ON fas.f_account_structure_id = jel.account_id
    WHERE
        jel.journal_date BETWEEN '$start_date' AND '$end_date'
        AND jel.reference_source = 'customer'
        AND je.journal_type = 'manual'
        AND je.journal_category = 'sales'
    GROUP BY jel.account_id

    UNION ALL

    -- Entry 2: All except sales
    SELECT
        je.journal_name,
        0 AS deb1,
        0 AS crd1,
        ROUND(SUM(jel.debit_amount), 2) AS deb2,
        ROUND(SUM(jel.credit_amount), 2) AS crd2,
        fas.concatenated_segments,
        0 AS opening_balance,
        jel.account_id,
        CASE
            WHEN fas.concatenated_segments LIKE '%60000%' OR fas.concatenated_segments LIKE '%70000%' THEN 'P&L'
            ELSE 'BS'
        END AS FS
    FROM f_journal_entry_t je
    JOIN f_journal_entry_lines_t jel ON je.journal_entry_id = jel.journal_entry_id
    JOIN f_account_structure_t fas ON fas.f_account_structure_id = jel.account_id
    WHERE
        jel.journal_date BETWEEN '$start_date' AND '$end_date'
        AND je.journal_category != 'sales'
    GROUP BY jel.account_id

    UNION ALL

    -- Entry 3: Opening Balance Pre-Aggregated
    SELECT
        '' AS journal_name,
        0 AS deb1,
        0 AS crd1,
        0 AS deb2,
        0 AS crd2,
        fas.concatenated_segments,
        ROUND(SUM(
            CASE 
                WHEN je.journal_type = 'MANUAL' AND jel.reference_source = 'CUSTOMER' AND jel.credit_amount > 0 THEN jel.credit_amount
                ELSE 0
            END
            +
            CASE 
                WHEN je.journal_type != 'MANUAL' THEN jel.debit_amount
                ELSE 0
            END
            +
            CASE 
                WHEN je.journal_type = 'MANUAL' AND jel.reference_source != 'CUSTOMER' THEN jel.debit_amount
                ELSE 0
            END
            -
            CASE 
                WHEN je.journal_type = 'MANUAL' AND jel.reference_source = 'CUSTOMER' AND jel.debit_amount > 0 THEN jel.debit_amount
                ELSE 0
            END
            -
             CASE 
                WHEN je.journal_type = 'MANUAL' AND jel.reference_source != 'CUSTOMER'  THEN jel.credit_amount
                ELSE 0
            END
            -
            CASE 
                WHEN je.journal_type != 'MANUAL' THEN jel.credit_amount
                ELSE 0
            END
        ), 2) AS opening_balance,
        jel.account_id,
        CASE
            WHEN fas.concatenated_segments LIKE '%60000%' OR fas.concatenated_segments LIKE '%70000%' THEN 'P&L'
            ELSE 'BS'
        END AS FS
    FROM f_journal_entry_t je
    JOIN f_journal_entry_lines_t jel ON je.journal_entry_id = jel.journal_entry_id
    JOIN f_account_structure_t fas ON fas.f_account_structure_id = jel.account_id
    WHERE
        (jel.journal_date < '$start_date' OR je.journal_type = 'OPENING BALANCE')
        AND jel.journal_date >= '2019-04-01'
        AND (fas.concatenated_segments NOT LIKE '%60000%' AND fas.concatenated_segments NOT LIKE '%70000%') -- Exclude P&L accounts
    GROUP BY jel.account_id, fas.concatenated_segments
) v1
GROUP BY v1.account_id";

    $results = \DB::select($SQL);

    return DataTables::of($results)->make(true);

  }


  // New (Skm ) - gettrialbalance

  public function gettrialbalancenew()
  {
    $type = '';
    $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? date("Y-m-d", strtotime($_GET['start_date'])) : '';
    $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';
    // $branch_id =  isset($_GET['branch_id']) && !empty($_GET['branch_id']) ? $_GET['branch_id'] : '';
    $location_id = isset($_GET['location_id']) && !empty($_GET['location_id']) ? $_GET['location_id'] : '';

    $bwh1 = '';

    if ($location_id != 1 && $location_id != '') {
      $bwh1 .= " and f_journal_entry_t.location_id=$location_id";
      //$qohwh.=" and "; 
    }
    $org = \Session::get('organization');
    $loc = \Session::get('location');
    $compy = \Session::get('companyid');
    // $data=\DB::select("SELECT * FROM `f_account_class_t` where account_class_id in (1,2,3,4,5) order by account_class_id asc ");
    $data = \DB::select("SELECT * FROM `f_account_class_t` where 1=1");
    $rpt_grp = \DB::select("SELECT f_account_structure_t.rpt_grp,
                                f_account_reporting_group_t.rpt_grp as grp_name,
                                f_account_structure_t.rpt_type,
                                group_concat(DISTINCT(f_account_structure_t.main_account_id)) as main_acc 
                                FROM `f_account_structure_t` 
                                left join f_account_reporting_group_t on f_account_reporting_group_t.acc_rpt_grp_id = f_account_structure_t.rpt_grp
                                where f_account_structure_t.rpt_grp is not null 
                                group by f_account_structure_t.rpt_grp 
                                order by f_account_reporting_group_t.rpt_seq asc");

    $rndcost = \DB::select("select v1.*,(v1.qty*v1.cost) as totcost from (select product_id,batch_number,sum(qoh_trx_qty*-1) as qty,
        (select i.cost from i_qoh_detail_t as i where i.product_id=i_qoh_detail_t.product_id and i.batch_number=i_qoh_detail_t.batch_number order by i.qoh_detail_id asc limit 1) as cost
        from i_qoh_detail_t where qoh_source='Consumable Material Issue' and date(created_at)<='$end_date' ) as v1 where 1=1  ");


    if (count($rndcost)) {
      $rndproductcost = $rndcost[0]->totcost;
    } else {
      $rndproductcost = 0;
    }


    $SQL = "select account_id,main_account_code,main,sub1,sub2,sub3,sub4,
        if(op_debitamount-op_creditamount>=0,op_debitamount-op_creditamount,0) as op_debitamount,
        if(op_debitamount-op_creditamount<0,op_creditamount-op_debitamount,0) as op_creditamount,
        if(debitamount-creditamount>=0,debitamount-creditamount,0) as debitamount, 
        if(debitamount-creditamount<0,creditamount-debitamount,0) as creditamount,
        if(cls_debitamount-cls_creditamount>=0,cls_debitamount-cls_creditamount,0) as cls_debitamount,
        if(cls_debitamount-cls_creditamount<0,cls_creditamount-cls_debitamount,0) as cls_creditamount,
        
       
        `main_account_id`,
        `sub_account_id`,
        `future_reference1`,
        `future_reference2`,
        `sub_account4_id`,
        `account_name`,
        rpt_grp,
        rpt_type
        from (
            (select v1.account_id,
             v1.main_account_code,
             v1.main,
             v1.sub1,
             v1.sub2,
             v1.sub3,
             v1.sub4,
             round(sum(v1.op_creditamount),2)  as op_creditamount,
             round(sum(v1.op_debitamount),2)  as op_debitamount,
             round(sum(v1.creditamount),2)  as creditamount,
             round(sum(v1.debitamount),2)  as debitamount,
             round(sum(v1.cls_creditamount),2)  as cls_creditamount,
             round(sum(v1.cls_debitamount),2)  as cls_debitamount,
             v1.`main_account_id`,
             v1.`sub_account_id`,
             v1.`future_reference1`,
             v1.`future_reference2`,
             v1.`sub_account4_id`,
             v1.`account_name`,
             v1.rpt_grp,
             v1.rpt_type
             from
                (SELECT
                    f.f_account_structure_id as account_id,m.main_account_code,
                    m.account_class_name AS main,
                    r1.account_code_meaning AS sub1,
                    IF(f.future_reference1 > 0,r2.account_code_meaning,'') AS sub2,
                    IF(f.future_reference2 > 0,r3.account_code_meaning,'') AS sub3,
                    IF(f.sub_account4_id > 0,r4.account_code_meaning,'') AS sub4,
                    0 AS op_creditamount,
                    0 AS op_debitamount,
                    0 as creditamount,
                    0 as debitamount,
                    0 as cls_creditamount,
                    0 as cls_debitamount,
                    f.`main_account_id`,
                    f.`sub_account_id`,
                    f.`future_reference1`,
                    f.`future_reference2`,
                    f.`sub_account4_id`,
                    f.`account_name`,
                    f.rpt_grp,
                    f.rpt_type
                FROM f_account_structure_t AS f
                JOIN f_account_class_t AS m ON m.account_class_id = f.main_account_id
                LEFT JOIN f_account_codes_lines_t AS r1 ON r1.account_code = f.sub_account_id
                left JOIN f_account_codes_lines_t AS r2 ON r2.account_code = f.future_reference1
                LEFT JOIN f_account_codes_lines_t AS r3 ON r3.account_code = f.future_reference2
                LEFT JOIN f_account_codes_lines_t AS r4 ON r4.account_code = f.sub_account4_id
                where 1=1
                
                union all 
                
                (SELECT f_journal_entry_lines_t.account_id,
                    f_account_class_t.main_account_code,
                    '' as main,
                    '' as sub1,
                    '' as sub2,
                    '' as sub3,
                    '' as sub4,
                    0 AS op_creditamount,
                    0 AS op_debitamount,
                    0 as creditamount,
                    0 as debitamount,
                    ROUND(SUM(f_journal_entry_lines_t.credit_amount),2)  AS cls_creditamount,
                    ROUND(SUM(f_journal_entry_lines_t.debit_amount),2)  AS cls_debitamount,
                    '' as `main_account_id`,
                    '' as `sub_account_id`,
                    '' as `future_reference1`,
                    '' as `future_reference2`,
                    '' as `sub_account4_id`,
                    '' as `account_name`,
                    f_account_structure_t.rpt_grp,
                    f_account_structure_t.rpt_type
                    FROM `f_journal_entry_t`
               LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
               LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
               LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
                WHERE 1=1 $bwh1   AND f_journal_entry_t.journal_date <= '$end_date'
                GROUP BY f_journal_entry_lines_t.account_id
                ORDER BY 1
                )
                
                union all 
                
                (SELECT f_journal_entry_lines_t.account_id,
                    f_account_class_t.main_account_code,
                    '' as main,
                    '' as sub1,
                    '' as sub2,
                    '' as sub3,
                    '' as sub4,
                    0 AS op_creditamount,
                    0 AS op_debitamount,
                    ROUND(SUM(f_journal_entry_lines_t.credit_amount),2)  AS creditamount,
                    ROUND(SUM(f_journal_entry_lines_t.debit_amount),2)  AS debitamount,
                    0 as cls_creditamount,
                    0 as cls_debitamount,
                    '' as `main_account_id`,
                    '' as `sub_account_id`,
                    '' as `future_reference1`,
                    '' as `future_reference2`,
                    '' as `sub_account4_id`,
                    '' as `account_name`,
                    f_account_structure_t.rpt_grp,
                    f_account_structure_t.rpt_type
                    FROM `f_journal_entry_t`
               LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
               LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
               LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
                WHERE 1=1 $bwh1   AND f_journal_entry_t.journal_date between '$start_date' and '$end_date'
                GROUP BY f_journal_entry_lines_t.account_id
                ORDER BY 1
                )
                union all 
                (SELECT 
                    f_journal_entry_lines_t.account_id,
                    f_account_class_t.main_account_code,
                    '' as main,
                    '' as sub1,
                    '' as sub2,
                    '' as sub3,
                    '' as sub4,
                    ROUND(SUM(f_journal_entry_lines_t.credit_amount),2)  AS op_creditamount,
                    ROUND(SUM(f_journal_entry_lines_t.debit_amount),2)  AS op_debitamount,
                    0 AS creditamount,
                    0 AS debitamount,
                    0 as cls_creditamount,
                    0 as cls_debitamount,
                    '' as `main_account_id`,
                    '' as `sub_account_id`,
                    '' as `future_reference1`,
                    '' as `future_reference2`,
                    '' as `sub_account4_id`,
                    '' as `account_name`,
                    f_account_structure_t.rpt_grp,
                    f_account_structure_t.rpt_type
                    FROM `f_journal_entry_t`
                    LEFT JOIN f_journal_entry_lines_t ON f_journal_entry_t.journal_entry_id = f_journal_entry_lines_t.journal_entry_id
                    LEFT JOIN f_account_structure_t ON f_account_structure_t.f_account_structure_id = f_journal_entry_lines_t.account_id
                    LEFT JOIN f_account_class_t on f_account_class_t.account_class_id=f_account_structure_t.main_account_id
                    WHERE 1=1 $bwh1  AND f_journal_entry_t.journal_date < '$start_date'
                    GROUP BY f_journal_entry_lines_t.account_id
                    ORDER BY 1
                )
            )v1 where v1.account_id   
        group by v1.account_id ORDER by v1.main_account_code asc) 
            )v3 order by v3.main_account_code asc";

    // echo($SQL);die;

    $result = \DB::select($SQL);

    $result = collect($result);

    setlocale(LC_MONETARY, 'en_IN');
    $total = 0;
    error_reporting(0);

    $comp = \Session::get('companyid');
    $comp = \DB::table('m_company_t')->where('company_id', $comp)->get();
    if ($comp->isNotEmpty()) {
      $this->data['company_name'] = $comp[0]->company_name;
    } else {
      $this->data['company_name'] = '';
    }
    $result = collect($result);

    $spreadsheet = new Spreadsheet();

    // Retrieve the current active worksheet
    $sheet = $spreadsheet->getActiveSheet();
    $sheet1 = $spreadsheet->createSheet();
    $sheet1 = $spreadsheet->getSheet(1);
    $comp = \Session::get('companyid');
    $comp = \DB::table('m_company_t')->where('company_id', $comp)->get();
    if ($comp->isNotEmpty()) {
      $this->data['company_name'] = $comp[0]->company_name;
      $this->data['cinno'] = $comp[0]->cin_no;
    } else {
      $this->data['company_name'] = '';
      $this->data['cinno'] = '';
    }
    $sheet->setCellValue('A1', $this->data['company_name']);

    $sheet1->setCellValue('A1', $this->data['company_name']);

    //style bold for name
    $styleArray = array(
      'font' => array('bold' => true)
    );


    $sheet->getStyle('A1')->applyFromArray($styleArray);
    $sheet->mergeCells('A1:C1');
    $sheet->getColumnDimension('c1')->setWidth(170, 'pt');
    $sheet->setCellValue('A2', 'Plot No.34 & 35 I Link Street,');
    $sheet->setCellValue('A3', 'Nehru Nagar Chennai - 600 041');
    $sheet->setCellValue('A4', 'Ph : 044-24540345,24542510');
    $sheet->mergeCells('A2:C2');
    $sheet->mergeCells('A3:C3');
    $sheet->mergeCells('A4:C4');
    $sheet->setCellValue('A5', 'Trial Balance');
    $sheet->mergeCells('A5:C5');
    $sheet->getStyle('A5:C5')->applyFromArray($styleArray);
    $sheet->setCellValue('A6', $start_date . ' to ' . $end_date);
    $sheet->mergeCells('A6:C6');
    //width
    $sheet->getColumnDimension('A')->setWidth(50);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('d')->setWidth(15);
    $sheet->getColumnDimension('E')->setWidth(15);

    $sheet->setCellValue('A8', 'Particulars');
    // $sheet->mergeCells('A8:A10');
    $sheet->setCellValue('b7', $this->data['company_name']);
    $sheet->mergeCells('b7:C7');
    $sheet->getStyle('b7')->applyFromArray($styleArray);
    $sheet->setCellValue('B8', $start_date . ' to ' . $end_date);
    $sheet->mergeCells('B8:C8');

    $sheet->setCellValue('B9', 'Opening Balance');
    $sheet->getStyle('b9')->applyFromArray($styleArray);
    $sheet->mergeCells('B9:C9');

    $sheet->setCellValue('D9', 'Transaction');
    $sheet->getStyle('d9')->applyFromArray($styleArray);
    $sheet->mergeCells('D9:E9');

    $sheet->setCellValue('F9', 'Closing Balance');
    $sheet->getStyle('f9')->applyFromArray($styleArray);
    $sheet->mergeCells('F9:G9');

    $sheet->setCellValue('B10', 'Debit');
    $sheet->setCellValue('c10', 'Credit');

    $sheet->setCellValue('D10', 'Debit');
    $sheet->setCellValue('E10', 'Credit');

    $sheet->setCellValue('F10', 'Debit');
    $sheet->setCellValue('G10', 'Credit');


    $sheet1->getStyle('A1')->applyFromArray($styleArray);
    $sheet1->mergeCells('A1:C1');
    $sheet1->getColumnDimension('c1')->setWidth(170, 'pt');
    $sheet1->setCellValue('A2', 'Plot No.34 & 35 I Link Street,');
    $sheet1->setCellValue('A3', 'Nehru Nagar Chennai - 600 041');
    $sheet1->setCellValue('A4', 'Ph : 044-24540345,24542510');
    $sheet1->mergeCells('A2:C2');
    $sheet1->mergeCells('A3:C3');
    $sheet1->mergeCells('A4:C4');
    $sheet1->setCellValue('A5', 'Trial Balance');
    $sheet1->mergeCells('A5:C5');
    $sheet1->getStyle('A5:C5')->applyFromArray($styleArray);
    $sheet1->setCellValue('A6', $start_date . ' to ' . $end_date);
    $sheet1->mergeCells('A6:C6');
    //width
    $sheet1->getColumnDimension('A')->setWidth(50);
    $sheet1->getColumnDimension('C')->setWidth(15);
    $sheet1->getColumnDimension('B')->setWidth(15);
    $sheet1->getColumnDimension('d')->setWidth(15);
    $sheet1->getColumnDimension('E')->setWidth(15);

    $sheet1->setCellValue('A8', 'Particulars');
    // $sheet->mergeCells('A8:A10');
    $sheet1->setCellValue('b7', $this->data['company_name']);
    $sheet1->mergeCells('b7:C7');
    $sheet1->getStyle('b7')->applyFromArray($styleArray);
    $sheet1->setCellValue('B8', $start_date . ' to ' . $end_date);
    $sheet1->mergeCells('B8:C8');

    $sheet1->setCellValue('B9', 'Opening Balance');
    $sheet1->getStyle('b9')->applyFromArray($styleArray);
    $sheet1->mergeCells('B9:C9');

    $sheet1->setCellValue('D9', 'Transaction');
    $sheet1->getStyle('d9')->applyFromArray($styleArray);
    $sheet1->mergeCells('D9:E9');

    $sheet1->setCellValue('F9', 'Closing Balance');
    $sheet1->getStyle('f9')->applyFromArray($styleArray);
    $sheet1->mergeCells('F9:G9');

    $sheet1->setCellValue('B10', 'Debit');
    $sheet1->setCellValue('c10', 'Credit');

    $sheet1->setCellValue('D10', 'Debit');
    $sheet1->setCellValue('E10', 'Credit');

    $sheet1->setCellValue('F10', 'Debit');
    $sheet1->setCellValue('G10', 'Credit');

    $total = 0;
    $xy = 11;
    //dd($data);
    // $sheet->setCellValue('b11',  $opeingbal[0]->opening);
    // $sheet->setCellValue('A11', 'Opening Balance');
    // $sheet->getStyle('a11:b11')->applyFromArray($styleArray);
    $y = $xy;
    $newy = $y;
    $newy1 = $y;


    $html .= "<table class='table' align='right' style='width: 100%;'> 
                    <thead>
                        <tr>
                            <th >Particulars</th>
                            <th colspan='1'>Opening Balance</th>
                            <th colspan='2'>Transaction</th>
                            <th colspan='3' style='text-align:center';>Closing Balance</th>
                        </tr>
                    </thead>";
    $html .= "<tr>
                <td class='parent' data='' col='0'></td>
                <td>DEBIT</td><td>Credit</td>
                <td>DEBIT</td><td>Credit</td>
                <td >Debit</td>
                <td style='text-align: right;'>Credit</td>
            </tr>";
    // dd($rpt_grp);
    foreach ($rpt_grp as $rptk => $rptv) {
      // if($rptk==3)dd($html);
      $id = 0;
      $idcode = 0;
      $p_id00 = 'thdr' . str_replace(" ", "", strtolower($rptv->rpt_grp));
      $main_acc = $rptv->main_acc;
      // dd($rptv->rpt_grp);
      $name00 = strtoupper($rptv->grp_name);
      $seqno = $rptk + 1;
      $filter = $result->where('rpt_grp', $rptv->rpt_grp);
      $filter->all();

      $filter00 = collect($filter);
      $filter = collect($filter)->map(function ($x) {
        return (array) $x; })->toArray();


      $arr_new00 = round(array_sum(array_column($filter, 'cls_creditamount')), 2);
      $darr_new00 = round(array_sum(array_column($filter, 'cls_debitamount')), 2);

      if ($arr_new00 == 0)
        $arr_new00 = '-';

      if ($darr_new00 == 0)
        $darr_new00 = '-';

      $arr_new_00 = round(array_sum(array_column($filter, 'op_creditamount')), 2);
      $darr_new_00 = round(array_sum(array_column($filter, 'op_debitamount')), 2);

      if ($arr_new_00 == 0)
        $arr_new_00 = '-';

      if ($darr_new_00 == 0)
        $darr_new_00 = '-';


      $t_camt00 = round(array_sum(array_column($filter, 'creditamount')), 2);
      $t_damt00 = round(array_sum(array_column($filter, 'debitamount')), 2);

      if ($t_camt00 == 0)
        $t_camt00 = '-';

      if ($t_damt00 == 0)
        $t_damt00 = '-';

      $html .= "<tr>
                            <td class='parent' data='$p_id00' col='0'>$seqno $name00</td>
                            <td>" . money_format('%!i', $darr_new_00) . "</td>
                            <td>" . money_format('%!i', $arr_new_00) . "</td>
                            <td>" . money_format('%!i', $t_damt00) . "</td>
                            <td>" . money_format('%!i', $t_camt00) . "</td><td >" . money_format('%!i', $darr_new00) . "</td>
                            <td style='text-align: right;'>" . money_format('%!i', $arr_new00) . "</td>
                        </tr>";


      $sheet->setCellValue('a' . $newy, $seqno . ' ' . $name00);
      $sheet->getStyle('a' . $newy)->applyFromArray($styleArray);

      $sheet->setCellValue('b' . $newy, $darr_new_00);
      $sheet->getStyle('b' . $newy)->applyFromArray($styleArray);


      $sheet->setCellValue('c' . $newy, $arr_new_00);
      $sheet->getStyle('c' . $newy)->applyFromArray($styleArray);

      $sheet->setCellValue('d' . $newy, $t_damt00);
      $sheet->getStyle('b' . $newy)->applyFromArray($styleArray);


      $sheet->setCellValue('e' . $newy, $t_camt00);
      $sheet->getStyle('c' . $newy)->applyFromArray($styleArray);

      $sheet->setCellValue('f' . $newy, $darr_new00);
      $sheet->getStyle('b' . $newy)->applyFromArray($styleArray);


      $sheet->setCellValue('g' . $newy, $arr_new00);
      $sheet->getStyle('c' . $newy)->applyFromArray($styleArray);

      $newy = $newy + 1;


      $sheet1->setCellValue('a' . $newy1, $seqno . ' ' . $name00);
      $sheet1->getStyle('a' . $newy1)->applyFromArray($styleArray);

      $sheet1->setCellValue('b' . $newy1, $darr_new_00);
      $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


      $sheet1->setCellValue('c' . $newy1, $arr_new_00);
      $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

      $sheet1->setCellValue('d' . $newy1, $t_damt00);
      $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


      $sheet1->setCellValue('e' . $newy1, $t_camt00);
      $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

      $sheet1->setCellValue('f' . $newy1, $darr_new00);
      $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


      $sheet1->setCellValue('g' . $newy1, $arr_new00);
      $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

      $newy1 = $newy1 + 1;

      if (count($sub1) > 0) {
        foreach ($sub1 as $val1) {

          $p_id1 = $rptk . '_' . $val1->account_codes_line_id;
          $id = $val1->account_codes_line_id;
          $name1 = ucwords(strtolower($val1->account_code_meaning));
          $code1 = $val1->account_codes_line_id;

          $filter1 = $filter00->where('sub_account_id', $code1);
          $filter1->all();

          $filter11 = collect($filter1);
          $filter1 = collect($filter1)->map(function ($x) {
            return (array) $x; })->toArray();

          $arr_new1 = round(array_sum(array_column($filter1, 'cls_creditamount')), 2);
          $darr_new1 = round(array_sum(array_column($filter1, 'cls_debitamount')), 2);

          $arr_new_1 = round(array_sum(array_column($filter1, 'op_creditamount')), 2);
          $darr_new_1 = round(array_sum(array_column($filter1, 'op_debitamount')), 2);


          $t_camt1 = round(array_sum(array_column($filter1, 'creditamount')), 2);
          $t_damt1 = round(array_sum(array_column($filter1, 'debitamount')), 2);



          if ($darr_new_1 == 0 && $arr_new_1 == 0 && $t_damt1 == 0 && $t_camt1 == 0 && $darr_new1 == 0 && $arr_new1 == 0) {
          } else {

            if ($arr_new1 == 0)
              $arr_new1 = '-';
            if ($darr_new1 == 0)
              $darr_new1 = '-';

            if ($arr_new_1 == 0)
              $arr_new_1 = '-';
            if ($darr_new_1 == 0)
              $darr_new_1 = '-';

            if ($t_camt1 == 0)
              $t_camt1 = '-';
            if ($t_damt1 == 0)
              $t_damt1 = '-';


            $html .= "<tr class='child child$p_id00'>
                                        <td class='parent parent_hide$p_id00' data='$p_id1' data='$p_id1' col='0'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code1  $name1</td>
                                        <td>" . money_format('%!i', $darr_new_1) . "</td>
                                        <td>" . money_format('%!i', $arr_new_1) . "</td>
                                        <td>" . money_format('%!i', $t_damt1) . "</td>
                                        <td>" . money_format('%!i', $t_camt1) . "</td>
                                        <td >" . money_format('%!i', $darr_new1) . "</td>
                                        <td style='text-align: right;'>" . money_format('%!i', $arr_new1) . "</td>
                                    </tr>";


            $sheet1->setCellValue('a' . $newy1, '  ' . $code1 . ' ' . $name1);
            $sheet1->getStyle('a' . $newy1)->applyFromArray($styleArray);

            $sheet1->setCellValue('b' . $newy1, $darr_new_1);
            $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


            $sheet1->setCellValue('c' . $newy1, $arr_new_1);
            $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

            $sheet1->setCellValue('d' . $newy1, $t_damt1);
            $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


            $sheet1->setCellValue('e' . $newy1, $t_camt1);
            $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

            $sheet1->setCellValue('f' . $newy1, $darr_new1);
            $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


            $sheet1->setCellValue('g' . $newy1, $arr_new1);
            $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

            $newy1 = $newy1 + 1;

            //Sub2 
            // $sub2=\DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
            $sub2 = \DB::select("SELECT * FROM `f_account_codes_lines_t` join f_account_structure_t on f_account_structure_t.future_reference1 =f_account_codes_lines_t.account_codes_line_id and f_account_structure_t.sub_account_id='$code1' and f_account_structure_t.rpt_grp='" . $rptv->rpt_grp . "'  where parent_class_id='$id'  group by f_account_codes_lines_t.account_codes_line_id");

            if (count($sub2) > 0) {
              foreach ($sub2 as $val2) {

                $id = $val2->account_codes_line_id;
                $p_id2 = $rptk . '_' . $val2->account_codes_line_id;
                $name2 = ucwords(strtolower($val2->account_code_meaning));
                $code2 = $val2->account_codes_line_id;
                $filter1 = $filter11->where('future_reference1', $code2)->where('sub_account_id', $code1);
                $filter1->all();
                $filter111 = collect($filter1);
                $filter1 = collect($filter1)->map(function ($x) {
                  return (array) $x; })->toArray();

                $arr_new2 = round(array_sum(array_column($filter1, 'cls_creditamount')), 2);
                $darr_new2 = round(array_sum(array_column($filter1, 'cls_debitamount')), 2);


                $filter1_acc = $filter11->where('future_reference1', $code2)->where('future_reference2', 0)->where('sub_account_id', $code1);
                $filter1_acc->all();
                $filter111_acc = collect($filter1_acc);
                $filter1_acc = collect($filter1_acc)->map(function ($x) {
                  return (array) $x; })->toArray();


                $arr_new_2 = round(array_sum(array_column($filter1, 'op_creditamount')), 2);
                $darr_new_2 = round(array_sum(array_column($filter1, 'op_debitamount')), 2);



                $t_camt2 = round(array_sum(array_column($filter1, 'creditamount')), 2);
                $t_damt2 = round(array_sum(array_column($filter1, 'debitamount')), 2);


                if ($darr_new_2 == 0 && $arr_new_2 == 0 && $t_damt2 == 0 && $t_camt2 == 0 && $darr_new2 == 0 && $arr_new2 == 0) {
                } else {

                  if ($darr_new2 == 0)
                    $darr_new2 = '-';
                  if ($arr_new2 == 0)
                    $arr_new2 = '-';

                  if ($arr_new_2 == 0)
                    $arr_new_2 = '-';
                  if ($darr_new_2 == 0)
                    $darr_new_2 = '-';

                  if ($t_camt2 == 0)
                    $t_camt2 = '-';
                  if ($t_damt2 == 0)
                    $t_damt2 = '-';


                  $sheet1->setCellValue('a' . $newy1, '          ' . $code2 . ' ' . $name2);
                  $sheet1->getStyle('a' . $newy1)->applyFromArray($styleArray);

                  $sheet1->setCellValue('b' . $newy1, $darr_new_2);
                  $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                  $sheet1->setCellValue('c' . $newy1, $arr_new_2);
                  $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                  $sheet1->setCellValue('d' . $newy1, $t_damt2);
                  $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                  $sheet1->setCellValue('e' . $newy1, $t_camt2);
                  $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                  $sheet1->setCellValue('f' . $newy1, $darr_new2);
                  $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                  $sheet1->setCellValue('g' . $newy1, $arr_new2);
                  $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                  $newy1 = $newy1 + 1;
                  $html .= "<tr class='child child$p_id1'>
                                                        <td class='parent parent_hide$p_id1' data='$p_id2' col='0'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code2  $name2</td>
                                                        <td >" . money_format('%!i', $darr_new_2) . "</td>
                                                        <td >" . money_format('%!i', $arr_new_2) . "</td>
                                                        <td >" . money_format('%!i', $t_damt2) . "</td>
                                                        <td >" . money_format('%!i', $t_camt2) . "</td>
                                                        <td >" . money_format('%!i', $darr_new2) . "</td>
                                                        <td style='text-align: right;'>" . money_format('%!i', $arr_new2) . "</td>
                                                    </tr>";


                  // $sub3=\DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
                  $sub3 = \DB::select("SELECT * FROM `f_account_codes_lines_t` join f_account_structure_t on f_account_structure_t.future_reference2 =f_account_codes_lines_t.account_codes_line_id and f_account_structure_t.future_reference1= $code2 and f_account_structure_t.sub_account_id='$code1' and f_account_structure_t.rpt_grp='" . $rptv->rpt_grp . "'  where parent_class_id='$id' group by f_account_codes_lines_t.account_codes_line_id");
                  // dd($sub3,$val2,$filter1);
                  //dd($filter1);

                  if (count($sub3) > 0) {
                    foreach ($sub3 as $val3) {
                      //  dd($filter11);
                      $p_id3 = $rptk . '_' . $val3->account_codes_line_id;
                      $id = $val3->account_codes_line_id;
                      $name3 = ucwords(strtolower($val3->account_code_meaning));
                      $code3 = $val3->account_codes_line_id;
                      $filter1 = $filter111->where('future_reference2', $code3)->where('future_reference1', $code2)->where('sub_account_id', $code1);
                      $filter1->all();
                      $filter1111 = collect($filter1);
                      $filter1 = collect($filter1)->map(function ($x) {
                        return (array) $x; })->toArray();
                      //dd($filter1);



                      $filter1_acc2 = $filter11->where('future_reference2', $code3)->where('future_reference1', $code2)->where('sub_account4_id', 0)->where('sub_account_id', $code1);
                      $filter1_acc2->all();
                      $filter111_acc2 = collect($filter1_acc2);
                      $filter1_acc2 = collect($filter1_acc2)->map(function ($x) {
                        return (array) $x; })->toArray();

                      $arr_new3 = round(array_sum(array_column($filter1, 'cls_creditamount')), 2);
                      $darr_new3 = round(array_sum(array_column($filter1, 'cls_debitamount')), 2);

                      $arr_new_3 = round(array_sum(array_column($filter1, 'op_creditamount')), 2);
                      $darr_new_3 = round(array_sum(array_column($filter1, 'op_debitamount')), 2);

                      $t_camt3 = round(array_sum(array_column($filter1, 'creditamount')), 2);
                      $t_damt3 = round(array_sum(array_column($filter1, 'debitamount')), 2);


                      if ($darr_new_3 == 0 && $arr_new_3 == 0 && $t_damt3 == 0 && $t_camt3 == 0 && $darr_new3 == 0 && $arr_new3 == 0) {
                      } else {
                        if ($arr_new3 == 0)
                          $arr_new3 = '-';
                        if ($darr_new3 == 0)
                          $darr_new3 = '-';

                        if ($arr_new_3 == 0)
                          $arr_new_3 = '-';
                        if ($darr_new_3 == 0)
                          $darr_new_3 = '-';

                        if ($t_camt3 == 0)
                          $t_camt3 = '-';
                        if ($t_damt3 == 0)
                          $t_damt3 = '-';

                        $html .= "<tr class='child child$p_id2'>
                                                                <td class='parent parent_hide$p_id2' data='$p_id3' col='0'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code3  $name3</td>
                                                                <td >" . money_format('%!i', $darr_new_3) . "</td>
                                                                <td >" . money_format('%!i', $arr_new_3) . "</td>
                                                                <td >" . money_format('%!i', $t_damt3) . "</td>
                                                                <td >" . money_format('%!i', $t_camt3) . "</td>
                                                                <td >" . money_format('%!i', $darr_new3) . "</td>
                                                                <td style='text-align: right;'>" . money_format('%!i', $arr_new3) . "</td>
                                                            </tr>";


                        $sheet1->setCellValue('a' . $newy1, '              ' . $code3 . ' ' . $name3);
                        $sheet1->getStyle('a' . $newy1)->applyFromArray($styleArray);

                        $sheet1->setCellValue('b' . $newy1, $darr_new_3);
                        $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                        $sheet1->setCellValue('c' . $newy1, $arr_new_3);
                        $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                        $sheet1->setCellValue('d' . $newy1, $t_damt3);
                        $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                        $sheet1->setCellValue('e' . $newy1, $t_camt3);
                        $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                        $sheet1->setCellValue('f' . $newy1, $darr_new3);
                        $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                        $sheet1->setCellValue('g' . $newy1, $arr_new3);
                        $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                        $newy1 = $newy1 + 1;

                        // $sub4=\DB::select("SELECT * FROM `f_account_codes_lines_t` where parent_class_id='$id'");
                        $sub3 = \DB::select("SELECT * FROM `f_account_codes_lines_t` join f_account_structure_t on f_account_structure_t.sub_account4_id =f_account_codes_lines_t.account_codes_line_id and f_account_structure_t.future_reference2=$code3 and f_account_structure_t.future_reference1= $code2 and f_account_structure_t.sub_account_id='$code1' and f_account_structure_t.rpt_grp='" . $rptv->rpt_grp . "'  where parent_class_id='$id' group by f_account_codes_lines_t.account_codes_line_id");

                        if (count($sub4) > 0) {
                          foreach ($sub4 as $val4) {
                            //  dd($filter11);
                            $p_id4 = $rptk . '_' . $val4->account_codes_line_id;
                            $id = $val4->account_codes_line_id;
                            $name4 = ucwords(strtolower($val4->account_code_meaning));
                            $code4 = $val4->account_codes_line_id;
                            $filter1 = $filter1111->where('sub_account4_id', $code4);
                            $filter1->all();
                            //$filter11=collect($filter1);
                            $filter1 = collect($filter1)->map(function ($x) {
                              return (array) $x; })->toArray();
                            // dd($filter1);

                            $arr_new4 = round(array_sum(array_column($filter1, 'cls_creditamount')), 2);
                            $darr_new4 = round(array_sum(array_column($filter1, 'cls_debitamount')), 2);

                            $arr_new_4 = round(array_sum(array_column($filter1, 'op_creditamount')), 2);
                            $darr_new_4 = round(array_sum(array_column($filter1, 'op_debitamount')), 2);

                            $t_camt4 = round(array_sum(array_column($filter1, 'creditamount')), 2);
                            $t_damt4 = round(array_sum(array_column($filter1, 'debitamount')), 2);

                            if ($darr_new_4 == 0 && $arr_new_4 == 0 && $t_damt4 == 0 && $t_camt4 == 0 && $darr_new4 == 0 && $arr_new4 == 0) {
                            } else {

                              if ($arr_new4 == 0)
                                $arr_new4 = '-';
                              if ($darr_new4 == 0)
                                $darr_new4 = '-';

                              if ($arr_new_4 == 0)
                                $arr_new_4 = '-';
                              if ($darr_new_4 == 0)
                                $darr_new_4 = '-';

                              if ($t_camt4 == 0)
                                $t_camt4 = '-';
                              if ($t_damt4 == 0)
                                $t_damt4 = '-';

                              $html .= "<tr class='child child$p_id3'>
                                                                        <td class='parent parent_hide$p_id3' data='$p_id4' col='0'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$code4  $name4</td>
                                                                        <td >" . money_format('%!i', $darr_new_4) . "</td>
                                                                        <td >" . money_format('%!i', $arr_new_4) . "</td>
                                                                        <td >" . money_format('%!i', $t_damt4) . "</td>
                                                                        <td >" . money_format('%!i', $t_camt4) . "</td>
                                                                        <td >" . money_format('%!i', $darr_new4) . "</td>
                                                                        <td style='text-align: right;'>" . money_format('%!i', $arr_new4) . "</td>
                                                                    </tr>";


                              $sheet1->setCellValue('a' . $newy1, '                  ' . $code4 . ' ' . $name4);
                              $sheet1->getStyle('a' . $newy1)->applyFromArray($styleArray);

                              $sheet1->setCellValue('b' . $newy1, $darr_new_4);
                              $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                              $sheet1->setCellValue('c' . $newy1, $arr_new_4);
                              $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                              $sheet1->setCellValue('d' . $newy1, $t_damt4);
                              $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                              $sheet1->setCellValue('e' . $newy1, $t_camt4);
                              $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                              $sheet1->setCellValue('f' . $newy1, $darr_new4);
                              $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                              $sheet1->setCellValue('g' . $newy1, $arr_new4);
                              $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                              $newy1 = $newy1 + 1;

                              foreach ($filter1 as $val4) {
                                $id = $val4['f_account_structure_id'];
                                $accountname = $val4['account_name'];
                                $cred = $val4['creditamount'];
                                $o_cred = $val4['op_creditamount'];
                                $c_cred = $val4['cls_creditamount'];
                                $deb = $val4['debitamount'];
                                $o_deb = $val4['op_debitamount'];
                                $c_deb = $val4['cls_debitamount'];
                                $amount4 = $val4['amount'];
                                //  $html.="<tr class='child child$p_id2'><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $accountname</td><td style='
                                //     text-align: right;
                                // '>".money_format('%!i',$amount4)."</td><td></td></tr>";
                                if ($o_deb == 0 && $o_cred == 0 && $c_deb == 0 && $c_cred == 0 && $deb == 0 && $cred == 0) {
                                } else {
                                  $html .= "<tr class='child child$p_id4'>
                                                                                <td class='parent parent_hide$p_id4' data='$id' col='0'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$accountname</td>
                                                                                <td >" . money_format('%!i', $o_deb) . "</td>
                                                                                <td >" . money_format('%!i', $o_cred) . "</td>
                                                                                <td >" . money_format('%!i', $c_deb) . "</td>
                                                                                <td >" . money_format('%!i', $c_cred) . "</td>
                                                                                <td >" . money_format('%!i', $deb) . "</td>
                                                                                <td style='text-align: right;'>" . money_format('%!i', $cred) . "</td>
                                                                                </tr>";

                                  $sheet1->setCellValue('a' . $newy1, '                      ' . $code4 . ' ' . $name4);
                                  $sheet1->getStyle('a' . $newy1)->applyFromArray($styleArray);

                                  $sheet1->setCellValue('b' . $newy1, $darr_new_4);
                                  $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                                  $sheet1->setCellValue('c' . $newy1, $arr_new_4);
                                  $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                                  $sheet1->setCellValue('d' . $newy1, $t_damt4);
                                  $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                                  $sheet1->setCellValue('e' . $newy1, $t_camt4);
                                  $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                                  $sheet1->setCellValue('f' . $newy1, $darr_new4);
                                  $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                                  $sheet1->setCellValue('g' . $newy1, $arr_new4);
                                  $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                                  $newy1 = $newy1 + 1;

                                }

                              }
                            }
                          }
                          if (count($filter1_acc2) > 0) {
                            foreach ($filter1_acc2 as $val4) {
                              $id = $val4['f_account_structure_id'];
                              $accountname = $val4['account_name'];
                              $clsdebt = $val4['cls_debitamount'];
                              $clscred = $val4['cls_creditamount'];
                              $opdebt = $val4['op_debitamount'];
                              $opcred = $val4['op_creditamount'];
                              $debt = $val4['debitamount'];
                              $cred = $val4['creditamount'];

                              // $amount4=$val4['amount'];
                              if ($opdebt == 0 && $opcred == 0 && $debt == 0 && $cred == 0 && $clsdebt == 0 && $clscred == 0) {
                              } else {
                                $html .= "<tr class='child child$p_id3'>
                                                                        <td  class='parent parent_hide$p_id3'  data='$id' col='0'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$accountname</td>
                                                                        <td >" . money_format('%!i', $opdebt) . "</td>
                                                                        <td >" . money_format('%!i', $opcred) . "</td>
                                                                        <td >" . money_format('%!i', $debt) . "</td>
                                                                        <td >" . money_format('%!i', $cred) . "</td>
                                                                        <td >" . money_format('%!i', $clsdebt) . "</td>
                                                                        <td >" . money_format('%!i', $clscred) . "</td>
                                                                        <td></td>
                                                                    </tr>";

                                $sheet1->setCellValue('a' . $newy1, $accountname);
                                $sheet1->getStyle('a' . $newy1)->applyFromArray($styleArray);

                                $sheet1->setCellValue('b' . $newy1, $opdebt);
                                $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                                $sheet1->setCellValue('c' . $newy1, $opcred);
                                $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                                $sheet1->setCellValue('d' . $newy1, $debt);
                                $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                                $sheet1->setCellValue('e' . $newy1, $cred);
                                $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                                $sheet1->setCellValue('f' . $newy1, $clsdebt);
                                $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                                $sheet1->setCellValue('g' . $newy1, $clscred);
                                $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                                $newy1 = $newy1 + 1;

                              }
                            }
                          }
                        } else {
                          foreach ($filter1 as $val4) {
                            $id = $val4['f_account_structure_id'];
                            $accountname = $val4['account_name'];
                            $clsdebt = $val4['cls_debitamount'];
                            $clscred = $val4['cls_creditamount'];
                            $opdebt = $val4['op_debitamount'];
                            $opcred = $val4['op_creditamount'];
                            $debt = $val4['debitamount'];
                            $cred = $val4['creditamount'];
                            if ($opdebt == 0 && $opcred == 0 && $debt == 0 && $cred == 0 && $clsdebt == 0 && $clscred == 0) {
                            } else {
                              // $amount4=$val4['amount'];
                              $html .= "<tr class='child child$p_id3'>
                                                                            <td  class='parent parent_hide$p_id3'  data='$id' col='0'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$accountname</td>
                                                                            <td style='text-align: right;'>" . money_format('%!i', $opdebt) . "</td>
                                                                            <td style='text-align: right;'>" . money_format('%!i', $opcred) . "</td>
                                                                            <td style='text-align: right;'>" . money_format('%!i', $debt) . "</td>
                                                                            <td style='text-align: right;'>" . money_format('%!i', $cred) . "</td>
                                                                            <td style='text-align: right;'>" . money_format('%!i', $clsdebt) . "</td>
                                                                            <td style='text-align: right;'>" . money_format('%!i', $clscred) . "</td>
                                                                            <td></td>
                                                                        </tr>";

                              $sheet1->setCellValue('a' . $newy1, '                  ' . $accountname);
                              $sheet1->getStyle('a' . $newy1)->applyFromArray($styleArray);

                              $sheet1->setCellValue('b' . $newy1, $opdebt);
                              $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                              $sheet1->setCellValue('c' . $newy1, $opcred);
                              $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                              $sheet1->setCellValue('d' . $newy1, $debt);
                              $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                              $sheet1->setCellValue('e' . $newy1, $cred);
                              $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                              $sheet1->setCellValue('f' . $newy1, $clsdebt);
                              $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                              $sheet1->setCellValue('g' . $newy1, $clscred);
                              $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                              $newy1 = $newy1 + 1;
                            }
                          }
                        }
                      }
                    }
                    if (count($filter1_acc) > 0) {
                      foreach ($filter1_acc as $val4) {
                        $id = $val4['f_account_structure_id'];
                        $accountname = $val4['account_name'];
                        $clsdebt = $val4['cls_debitamount'];
                        $clscred = $val4['cls_creditamount'];
                        $opdebt = $val4['op_debitamount'];
                        $opcred = $val4['op_creditamount'];
                        $debt = $val4['debitamount'];
                        $cred = $val4['creditamount'];

                        if ($opdebt == 0 && $opcred == 0 && $debt == 0 && $cred == 0 && $clsdebt == 0 && $clscred == 0) {
                        } else {
                          // $amount4=$val4['amount'];
                          $html .= "<tr class='child child$p_id2'>
                                                                <td  class='parent parent_hide$p_id2'  data='$id' col='0'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$accountname</td>
                                                                <td >" . money_format('%!i', $opdebt) . "</td>
                                                                <td >" . money_format('%!i', $opcred) . "</td>
                                                                <td >" . money_format('%!i', $debt) . "</td>
                                                                <td >" . money_format('%!i', $cred) . "</td>
                                                                <td >" . money_format('%!i', $clsdebt) . "</td>
                                                                <td >" . money_format('%!i', $clscred) . "</td>
                                                                <td></td>
                                                            </tr>";

                          $sheet1->setCellValue('a' . $newy1, $accountname);
                          $sheet1->getStyle('a' . $newy1)->applyFromArray($styleArray);

                          $sheet1->setCellValue('b' . $newy1, $opdebt);
                          $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                          $sheet1->setCellValue('c' . $newy1, $opcred);
                          $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                          $sheet1->setCellValue('d' . $newy1, $debt);
                          $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                          $sheet1->setCellValue('e' . $newy1, $cred);
                          $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                          $sheet1->setCellValue('f' . $newy1, $clsdebt);
                          $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                          $sheet1->setCellValue('g' . $newy1, $clscred);
                          $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                          $newy1 = $newy1 + 1;
                        }
                      }
                    }
                  } else {
                    foreach ($filter1 as $val4) {
                      $id = $val4['f_account_structure_id'];
                      $accountname = $val4['account_name'];
                      $clsdebt = $val4['cls_debitamount'];
                      $clscred = $val4['cls_creditamount'];
                      $opdebt = $val4['op_debitamount'];
                      $opcred = $val4['op_creditamount'];
                      $debt = $val4['debitamount'];
                      $cred = $val4['creditamount'];

                      if ($opdebt == 0 && $opcred == 0 && $debt == 0 && $cred == 0 && $clsdebt == 0 && $clscred == 0) {
                      } else {
                        // $amount4=$val4['amount'];
                        $html .= "<tr class='child child$p_id2'>
                                                            <td  class='parent parent_hide$p_id2'  data='$id' col='0'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$accountname</td>
                                                            <td style='text-align: right;'>" . money_format('%!i', $opdebt) . "</td>
                                                            <td style='text-align: right;'>" . money_format('%!i', $opcred) . "</td>
                                                            <td style='text-align: right;'>" . money_format('%!i', $debt) . "</td>
                                                            <td style='text-align: right;'>" . money_format('%!i', $cred) . "</td>
                                                            <td style='text-align: right;'>" . money_format('%!i', $clsdebt) . "</td>
                                                            <td style='text-align: right;'>" . money_format('%!i', $clscred) . "</td>
                                                            <td></td>
                                                        </tr>";

                        $sheet1->setCellValue('a' . $newy1, '              ' . $accountname);
                        $sheet1->getStyle('a' . $newy1)->applyFromArray($styleArray);

                        $sheet1->setCellValue('b' . $newy1, $opdebt);
                        $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                        $sheet1->setCellValue('c' . $newy1, $opcred);
                        $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                        $sheet1->setCellValue('d' . $newy1, $debt);
                        $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                        $sheet1->setCellValue('e' . $newy1, $cred);
                        $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                        $sheet1->setCellValue('f' . $newy1, $clsdebt);
                        $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                        $sheet1->setCellValue('g' . $newy1, $clscred);
                        $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                        $newy1 = $newy1 + 1;
                      }
                    }
                  }
                }
              }
            } else {
              // dd($filter1);
              foreach ($filter1 as $val4) {
                $id = $val4['f_account_structure_id'];
                $accountname = $val4['account_name'];
                $clsdebt = $val4['cls_debitamount'];
                $clscred = $val4['cls_creditamount'];
                $opdebt = $val4['op_debitamount'];
                $opcred = $val4['op_creditamount'];
                $debt = $val4['debitamount'];
                $cred = $val4['creditamount'];

                if ($opdebt == 0 && $opcred == 0 && $debt == 0 && $cred == 0 && $clsdebt == 0 && $clscred == 0) {
                } else {
                  $html .= "<tr class='child child$p_id1'>
                                                <td  class='parent parent_hide$p_id1'  data='$id' col='0'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$accountname</td>
                                                <td style='text-align: right;'>" . money_format('%!i', $opdebt) . "</td>
                                                <td style='text-align: right;'>" . money_format('%!i', $opcred) . "</td>
                                                <td style='text-align: right;'>" . money_format('%!i', $debt) . "</td>
                                                <td style='text-align: right;'>" . money_format('%!i', $cred) . "</td>
                                                <td style='text-align: right;'>" . money_format('%!i', $clsdebt) . "</td>
                                                <td style='text-align: right;'>" . money_format('%!i', $clscred) . "</td>
                                                <td></td>
                                            </tr>";

                  $sheet1->setCellValue('a' . $newy1, '      ' . $accountname);
                  $sheet1->getStyle('a' . $newy1)->applyFromArray($styleArray);

                  $sheet1->setCellValue('b' . $newy1, $opdebt);
                  $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                  $sheet1->setCellValue('c' . $newy1, $opcred);
                  $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                  $sheet1->setCellValue('d' . $newy1, $debt);
                  $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                  $sheet1->setCellValue('e' . $newy1, $cred);
                  $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                  $sheet1->setCellValue('f' . $newy1, $clsdebt);
                  $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


                  $sheet1->setCellValue('g' . $newy1, $clscred);
                  $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

                  $newy1 = $newy1 + 1;
                }
              }
            }
          }
        }
      } else {

        foreach ($filter as $val4) {
          $id = $val4['f_account_structure_id'];
          $accountname = $val4['account_name'];
          $clsdebt = $val4['cls_debitamount'];
          $clscred = $val4['cls_creditamount'];
          $opdebt = $val4['op_debitamount'];
          $opcred = $val4['op_creditamount'];
          $debt = $val4['debitamount'];
          $cred = $val4['creditamount'];

          if ($opdebt == 0 && $opcred == 0 && $debt == 0 && $cred == 0 && $clsdebt == 0 && $clscred == 0) {
          } else {
            $html .= "<tr class='child child$p_id00'>
                                    <td  class='parent parent_hide$p_id00'  data='$id' col='0'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$accountname</td>
                                    <td style='text-align: right;'>" . money_format('%!i', $opdebt) . "</td>
                                    <td style='text-align: right;'>" . money_format('%!i', $opcred) . "</td>
                                    <td style='text-align: right;'>" . money_format('%!i', $debt) . "</td>
                                    <td style='text-align: right;'>" . money_format('%!i', $cred) . "</td>
                                    <td style='text-align: right;'>" . money_format('%!i', $clsdebt) . "</td>
                                    <td style='text-align: right;'>" . money_format('%!i', $clscred) . "</td>
                                    <td></td>
                                </tr>";

            $sheet1->setCellValue('a' . $newy1, '  ' . $accountname);
            $sheet1->getStyle('a' . $newy1)->applyFromArray($styleArray);

            $sheet1->setCellValue('b' . $newy1, $opdebt);
            $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


            $sheet1->setCellValue('c' . $newy1, $opcred);
            $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

            $sheet1->setCellValue('d' . $newy1, $debt);
            $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


            $sheet1->setCellValue('e' . $newy1, $cred);
            $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

            $sheet1->setCellValue('f' . $newy1, $clsdebt);
            $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


            $sheet1->setCellValue('g' . $newy1, $clscred);
            $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

            $newy1 = $newy1 + 1;
          }
        }


      }

    }

    $dbtot = 0;
    $op_dbtot = 0;
    $op_cdtot = 0;
    $cls_dbtot = 0;
    $cls_cdtot = 0;
    $cdtot = 0;
    // dd($result);
    foreach ($result as $k => $val) {
      // $html.="<tr><td>".$val->concatenated_segments."</td><td>".$val->debit."</td><td>".$val->credit."</td></tr>";
      $dbtot = $dbtot + $val->debitamount;
      $cdtot = $cdtot + $val->creditamount;

      $op_dbtot = $op_dbtot + $val->op_debitamount;
      $op_cdtot = $op_cdtot + $val->op_creditamount;

      $cls_dbtot = $cls_dbtot + $val->cls_debitamount;
      $cls_cdtot = $cls_cdtot + $val->cls_creditamount;
    }


    $html .= "<tr><td><b>Grand Total</b></td><td><b>" . $op_dbtot . "</b></td><td><b>" . $op_cdtot . "</b></td><td><b>" . $dbtot . "</b></td><td><b>" . $cdtot . "</b></td><td><b>" . $cls_dbtot . "</b></td><td style='text-align: right;'><b>" . $cls_cdtot . "</b></td></tr>";


    $sheet1->setCellValue('a' . $newy1, "Grand Total");
    $sheet1->getStyle('a' . $newy1)->applyFromArray($styleArray);

    $sheet1->setCellValue('b' . $newy1, $op_dbtot);
    $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


    $sheet1->setCellValue('c' . $newy1, $op_cdtot);
    $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

    $sheet1->setCellValue('d' . $newy1, $dbtot);
    $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


    $sheet1->setCellValue('e' . $newy1, $cdtot);
    $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

    $sheet1->setCellValue('f' . $newy1, $cls_dbtot);
    $sheet1->getStyle('b' . $newy1)->applyFromArray($styleArray);


    $sheet1->setCellValue('g' . $newy1, $cls_cdtot);
    $sheet1->getStyle('c' . $newy1)->applyFromArray($styleArray);

    $newy1 = $newy1 + 1;


    $sheet->setCellValue('a' . $newy, "Grand Total");
    $sheet->getStyle('a' . $newy)->applyFromArray($styleArray);

    $sheet->setCellValue('b' . $newy, $op_dbtot);
    $sheet->getStyle('b' . $newy)->applyFromArray($styleArray);


    $sheet->setCellValue('c' . $newy, $op_cdtot);
    $sheet->getStyle('c' . $newy)->applyFromArray($styleArray);

    $sheet->setCellValue('d' . $newy, $dbtot);
    $sheet->getStyle('b' . $newy)->applyFromArray($styleArray);


    $sheet->setCellValue('e' . $newy, $cdtot);
    $sheet->getStyle('c' . $newy)->applyFromArray($styleArray);

    $sheet->setCellValue('f' . $newy, $cls_dbtot);
    $sheet->getStyle('b' . $newy)->applyFromArray($styleArray);


    $sheet->setCellValue('g' . $newy, $cls_cdtot);
    $sheet->getStyle('c' . $newy)->applyFromArray($styleArray);

    $newy = $newy + 1;

    $writer1 = new Xlsx($spreadsheet);

    // Save the new .xlsx file
    $writer1->save('trialbalance.xlsx');

    $class = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf::class;
    \PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', $class);
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Pdf');
    // $pdf_path   ='tcpdf_output.pdf'; 
    // $writer->save($pdf_path);

    $filepath = 'trialbalance.xlsx';
    $myfile = $filepath;




    return $html;
  }

public function gettrialbalancejrk(Request $request)
{
    $start = $request->input('start_date')
        ? date("Y-m-d", strtotime($request->input('start_date')))
        : null;

    $end = $request->input('end_date')
        ? date("Y-m-d", strtotime($request->input('end_date')))
        : null;

    $exclude_ledgers = $request->input('exclude_ledgers', []);
    $filterMode = $request->input('filter_mode', null);

    // 🔹 Exclusion condition
    $excludeCondition = '';
    if ($filterMode === 'exclude' && !empty($exclude_ledgers)) {
        $ids = implode(',', array_map('intval', $exclude_ledgers));
        $excludeCondition = " AND jel.account_id NOT IN ($ids)";
    }

    // ✅ Fix: use unique parameter names per section
    $sql = "
        SELECT
            GROUP_CONCAT(DISTINCT v1.account_id ORDER BY v1.account_id) AS account_id,
            account_description,
            SUM(deb1 + deb2) AS debit,
            SUM(crd1 + crd2) AS credit,
            SUM(opening_balance) AS opening_balance,
            ROUND(SUM(opening_balance + (deb1 + deb2) - (crd1 + crd2)), 2) AS balance,
            MAX(FS) AS FS
        FROM (
            -- 🔹 Current Period Journals (Sales)
            SELECT
                je.journal_name,
                ROUND(SUM(jel.credit_amount), 2) AS deb1,
                ROUND(SUM(jel.debit_amount), 2) AS crd1,
                0 AS deb2,
                0 AS crd2,
                fas.account_description,
                fas.concatenated_segments,
                0 AS opening_balance,
                jel.account_id,
                CASE
                    WHEN fas.concatenated_segments LIKE '%60000%' OR fas.concatenated_segments LIKE '%70000%' THEN 'P&L'
                    ELSE 'BS'
                END AS FS
            FROM f_journal_entry_t je
            JOIN f_journal_entry_lines_t jel ON je.journal_entry_id = jel.journal_entry_id
            JOIN f_account_structure_t fas   ON fas.f_account_structure_id = jel.account_id
            WHERE jel.journal_date BETWEEN :start1 AND :end1
              AND jel.reference_source = 'customer'
              AND je.journal_type = 'manual'
              AND je.journal_category = 'sales'
              $excludeCondition
            GROUP BY fas.account_description

            UNION ALL

            -- 🔹 Current Period Journals (Non-Sales)
            SELECT
                je.journal_name,
                0 AS deb1,
                0 AS crd1,
                ROUND(SUM(jel.debit_amount), 2) AS deb2,
                ROUND(SUM(jel.credit_amount), 2) AS crd2,
                fas.account_description,
                fas.concatenated_segments,
                0 AS opening_balance,
                jel.account_id,
                CASE
                    WHEN fas.concatenated_segments LIKE '%60000%' OR fas.concatenated_segments LIKE '%70000%' THEN 'P&L'
                    ELSE 'BS'
                END AS FS
            FROM f_journal_entry_t je
            JOIN f_journal_entry_lines_t jel ON je.journal_entry_id = jel.journal_entry_id
            JOIN f_account_structure_t fas   ON fas.f_account_structure_id = jel.account_id
            WHERE jel.journal_date BETWEEN :start2 AND :end2
              AND je.journal_category != 'sales'
              $excludeCondition
            GROUP BY fas.account_description

            UNION ALL

            -- 🔹 Opening Balances
            SELECT
                '' AS journal_name,
                0 AS deb1,
                0 AS crd1,
                0 AS deb2,
                0 AS crd2,
                fas.account_description,
                fas.concatenated_segments,
                ROUND(SUM(
                    CASE 
                        WHEN je.journal_type = 'MANUAL' AND jel.reference_source = 'CUSTOMER' AND jel.credit_amount > 0 THEN jel.credit_amount
                        ELSE 0
                    END
                    +
                    CASE 
                        WHEN je.journal_type != 'MANUAL' THEN jel.debit_amount
                        ELSE 0
                    END
                    +
                    CASE 
                        WHEN je.journal_type = 'MANUAL' AND jel.reference_source != 'CUSTOMER' THEN jel.debit_amount
                        ELSE 0
                    END
                    -
                    CASE 
                        WHEN je.journal_type = 'MANUAL' AND jel.reference_source = 'CUSTOMER' AND jel.debit_amount > 0 THEN jel.debit_amount
                        ELSE 0
                    END
                    -
                    CASE 
                        WHEN je.journal_type = 'MANUAL' AND jel.reference_source != 'CUSTOMER' THEN jel.credit_amount
                        ELSE 0
                    END
                    -
                    CASE 
                        WHEN je.journal_type != 'MANUAL' THEN jel.credit_amount
                        ELSE 0
                    END
                ), 2) AS opening_balance,
                jel.account_id,
                CASE
                    WHEN fas.concatenated_segments LIKE '%60000%' OR fas.concatenated_segments LIKE '%70000%' THEN 'P&L'
                    ELSE 'BS'
                END AS FS
            FROM f_journal_entry_t je
            JOIN f_journal_entry_lines_t jel ON je.journal_entry_id = jel.journal_entry_id
            JOIN f_account_structure_t fas ON fas.f_account_structure_id = jel.account_id
            WHERE
                (jel.journal_date < :start3 OR je.journal_type = 'OPENING BALANCE')
                AND jel.journal_date >= '2019-04-01'
                AND (fas.concatenated_segments NOT LIKE '%60000%' AND fas.concatenated_segments NOT LIKE '%70000%')
                $excludeCondition
            GROUP BY fas.account_description
        ) v1
        GROUP BY v1.account_description
    ";

    // ✅ Correct parameter bindings for all placeholders
    $data = \DB::select($sql, [
        'start1' => $start,
        'end1'   => $end,
        'start2' => $start,
        'end2'   => $end,
        'start3' => $start,
    ]);

    return response()->json(['data' => $data]);
}


public function getTrialBalanceDetailsjrk($account_description, Request $request)
{
    $start = $request->input('start_date')
        ? date("Y-m-d", strtotime($request->input('start_date')))
        : null;

    $end = $request->input('end_date')
        ? date("Y-m-d", strtotime($request->input('end_date')))
        : null;

    $exclude_ledgers = $request->input('exclude_ledgers', []);
    $filterMode = $request->input('filter_mode', null);

    // 🔹 Exclusion condition
    $excludeCondition = '';
    if ($filterMode === 'exclude' && !empty($exclude_ledgers)) {
        $ids = implode(',', array_map('intval', $exclude_ledgers));
        $excludeCondition = " AND jel.account_id NOT IN ($ids)";
    }

    // ✅ Each placeholder name is unique for PDO
    $sql = "
        SELECT
            v1.account_id,
            v1.account_description,
            v1.concatenated_segments,
            SUM(deb1 + deb2) AS debit,
            SUM(crd1 + crd2) AS credit,
            SUM(opening_balance) AS opening_balance,
            ROUND(SUM(opening_balance + (deb1 + deb2) - (crd1 + crd2)), 2) AS balance,
            MAX(FS) AS FS
        FROM (
            -- 🔹 Sales (Manual + Customer)
            SELECT
                je.journal_name,
                ROUND(SUM(jel.credit_amount), 2) AS deb1,
                ROUND(SUM(jel.debit_amount), 2) AS crd1,
                0 AS deb2,
                0 AS crd2,
                fas.account_description,
                fas.concatenated_segments,
                0 AS opening_balance,
                jel.account_id,
                CASE
                    WHEN fas.concatenated_segments LIKE '%60000%' OR fas.concatenated_segments LIKE '%70000%' THEN 'P&L'
                    ELSE 'BS'
                END AS FS
            FROM f_journal_entry_t je
            JOIN f_journal_entry_lines_t jel ON je.journal_entry_id = jel.journal_entry_id
            JOIN f_account_structure_t fas   ON fas.f_account_structure_id = jel.account_id
            WHERE
                jel.journal_date BETWEEN :start1 AND :end1
                AND jel.reference_source = 'customer'
                AND je.journal_type = 'manual'
                AND je.journal_category = 'sales'
                $excludeCondition
            GROUP BY jel.account_id

            UNION ALL

            -- 🔹 Non-Sales
            SELECT
                je.journal_name,
                0 AS deb1,
                0 AS crd1,
                ROUND(SUM(jel.debit_amount), 2) AS deb2,
                ROUND(SUM(jel.credit_amount), 2) AS crd2,
                fas.account_description,
                fas.concatenated_segments,
                0 AS opening_balance,
                jel.account_id,
                CASE
                    WHEN fas.concatenated_segments LIKE '%60000%' OR fas.concatenated_segments LIKE '%70000%' THEN 'P&L'
                    ELSE 'BS'
                END AS FS
            FROM f_journal_entry_t je
            JOIN f_journal_entry_lines_t jel ON je.journal_entry_id = jel.journal_entry_id
            JOIN f_account_structure_t fas   ON fas.f_account_structure_id = jel.account_id
            WHERE
                jel.journal_date BETWEEN :start2 AND :end2
                AND je.journal_category != 'sales'
                $excludeCondition
            GROUP BY jel.account_id

            UNION ALL

            -- 🔹 Opening Balance
            SELECT
                '' AS journal_name,
                0 AS deb1,
                0 AS crd1,
                0 AS deb2,
                0 AS crd2,
                fas.account_description,
                fas.concatenated_segments,
                ROUND(SUM(
                    CASE 
                        WHEN je.journal_type = 'MANUAL' AND jel.reference_source = 'CUSTOMER' AND jel.credit_amount > 0 THEN jel.credit_amount
                        ELSE 0
                    END
                    +
                    CASE 
                        WHEN je.journal_type != 'MANUAL' THEN jel.debit_amount
                        ELSE 0
                    END
                    +
                    CASE 
                        WHEN je.journal_type = 'MANUAL' AND jel.reference_source != 'CUSTOMER' THEN jel.debit_amount
                        ELSE 0
                    END
                    -
                    CASE 
                        WHEN je.journal_type = 'MANUAL' AND jel.reference_source = 'CUSTOMER' AND jel.debit_amount > 0 THEN jel.debit_amount
                        ELSE 0
                    END
                    -
                    CASE 
                        WHEN je.journal_type = 'MANUAL' AND jel.reference_source != 'CUSTOMER' THEN jel.credit_amount
                        ELSE 0
                    END
                    -
                    CASE 
                        WHEN je.journal_type != 'MANUAL' THEN jel.credit_amount
                        ELSE 0
                    END
                ), 2) AS opening_balance,
                jel.account_id,
                CASE
                    WHEN fas.concatenated_segments LIKE '%60000%' OR fas.concatenated_segments LIKE '%70000%' THEN 'P&L'
                    ELSE 'BS'
                END AS FS
            FROM f_journal_entry_t je
            JOIN f_journal_entry_lines_t jel ON je.journal_entry_id = jel.journal_entry_id
            JOIN f_account_structure_t fas ON fas.f_account_structure_id = jel.account_id
            WHERE
                (jel.journal_date < :start3 OR je.journal_type = 'OPENING BALANCE')
                AND jel.journal_date >= '2019-04-01'
                AND (fas.concatenated_segments NOT LIKE '%60000%' AND fas.concatenated_segments NOT LIKE '%70000%')
                $excludeCondition
            GROUP BY jel.account_id
        ) v1
        WHERE v1.account_description = :account_description
        GROUP BY v1.account_id
    ";

    // ✅ Safe bindings for PDO
    $data = \DB::select($sql, [
        'start1' => $start,
        'end1'   => $end,
        'start2' => $start,
        'end2'   => $end,
        'start3' => $start,
        'account_description' => $account_description,
    ]);

    return response()->json(['data' => $data]);
}

public function getledgerbalancejrk(Request $request)
{
    $account_id = $request->account_id;

    $start = isset($_GET['start_date']) && !empty($_GET['start_date'])
        ? date("Y-m-d", strtotime($_GET['start_date']))
        : '';
    $end = isset($_GET['end_date']) && !empty($_GET['end_date'])
        ? date("Y-m-d", strtotime($_GET['end_date']))
        : '';

    $exclude_ledgers = $request->get('exclude_ledgers') ?? [];
    $filterMode = $request->get('filter_mode');

    // Exclude condition placeholder
    $excludeConditionSql = '';
    /*if ($filterMode === 'exclude' && !empty($exclude_ledgers)) {
        $ids = implode(',', array_map('intval', $exclude_ledgers));
        $excludeConditionSql = " AND fjel.account_id NOT IN ($ids)";
    }*/

    // ==============================
    // 🔹 FETCH LEDGER TRANSACTIONS
    // ==============================
    $ledger_data = \DB::select("
        SELECT * FROM (
            SELECT 
                fje.journal_name,
                fje.journal_type,
                fjel.journal_entry_id,
                fjel.debit_amount,
                fjel.credit_amount,
                fjel.journal_date,
                fjel.f_journal_entry_line_id,
                fjel.reference_source,
                CASE 
                    WHEN fjel.reference_source='PRODUCT' THEN mp.concatenated_product
                    WHEN fjel.reference_source='EMPLOYEE' THEN he.first_name
                    WHEN fjel.reference_source='SUPPLIER' THEN ms.supplier_name
                    WHEN fjel.reference_source='CUSTOMER' THEN mc.customer_name
                    WHEN fjel.reference_source='MACHINE' THEN wm.machine_name
                    WHEN fjel.reference_source IN ('PAYROLL', 'PAYROLL/PF/ESI') THEN mdl.sub_department_name
                    ELSE ''
                END AS reference_name
            FROM f_journal_entry_lines_t fjel
            LEFT JOIN m_products_t mp ON mp.product_id=fjel.reference_id
            LEFT JOIN hr_employee_t he ON he.employee_id=fjel.reference_id
            LEFT JOIN m_supplier_t ms ON ms.supplier_id=fjel.reference_id
            LEFT JOIN m_customers_t mc ON mc.customer_id=fjel.reference_id
            LEFT JOIN w_machine_hdr_t wm ON wm.machine_hdr_id=fjel.reference_id
            LEFT JOIN m_department_lines_t mdl ON mdl.department_line_id=fjel.reference_id
            JOIN f_journal_entry_t fje ON fje.journal_entry_id=fjel.journal_entry_id
            WHERE fjel.account_id='$account_id'
              AND fjel.journal_date BETWEEN '$start' AND '$end'
              AND fje.journal_type!='OPENING BALANCE'
              $excludeConditionSql
            ORDER BY fjel.journal_date ASC
        ) AS v1
        WHERE 1=1
    ");

    // ==============================
    // 🔹 FETCH OPENING BALANCE (fixed logic)
    // ==============================
    $openingSql = "
        SELECT (
            CASE 
                WHEN (fas.concatenated_segments LIKE '%60000%' OR fas.concatenated_segments LIKE '%70000%')
                THEN 0
                ELSE ROUND(SUM(
                    CASE 
                        WHEN fje.journal_type = 'MANUAL' AND fjel.reference_source = 'CUSTOMER' AND fjel.credit_amount > 0 THEN   fjel.credit_amount
                        ELSE 0
                    END
                    +
                    CASE 
                        WHEN fje.journal_type != 'MANUAL' THEN fjel.debit_amount
                        ELSE 0
                    END
                    +
                    CASE 
                        WHEN fje.journal_type = 'MANUAL' AND fjel.reference_source != 'CUSTOMER' THEN fjel.debit_amount
                        ELSE 0
                    END
                    -
                    CASE 
                        WHEN fje.journal_type = 'MANUAL' AND fjel.reference_source = 'CUSTOMER' AND fjel.debit_amount > 0 THEN fjel.debit_amount
                        ELSE 0
                    END
                    -
                    CASE 
                        WHEN fje.journal_type = 'MANUAL' AND fjel.reference_source != 'CUSTOMER' THEN fjel.credit_amount
                        ELSE 0
                    END
                    -
                    CASE 
                        WHEN fje.journal_type != 'MANUAL' THEN fjel.credit_amount
                        ELSE 0
                    END
                ), 2)
            END
        ) AS opening_balance
        FROM f_journal_entry_lines_t fjel
        JOIN f_journal_entry_t fje ON fje.journal_entry_id = fjel.journal_entry_id
        JOIN f_account_structure_t fas ON fas.f_account_structure_id = fjel.account_id
        WHERE fjel.account_id = :account_id
          AND (DATE(fjel.journal_date) < :start_date OR fje.journal_type = 'OPENING BALANCE')
          $excludeConditionSql
    ";

    $openingRow = \DB::selectOne($openingSql, [
        'account_id' => $account_id,
        'start_date' => $start
    ]);

    $balance = round($openingRow->opening_balance ?? 0, 2);

    // ==============================
    // 🔹 BUILD OPENING ENTRY ROW
    // ==============================
    $overall_datas = [];
    $overall_datas[0] = (object)[];

    if ($balance > 0) {
        $overall_datas[0]->balance = $balance;
        $overall_datas[0]->debit_amounts = $balance;
        $overall_datas[0]->credit_amounts = 0;
    } else {
        $overall_datas[0]->balance = $balance;
        $overall_datas[0]->debit_amounts = 0;
        $overall_datas[0]->credit_amounts = $balance * -1;
    }

    $overall_datas[0]->net_salary = 0;
    $overall_datas[0]->journal_date = $start;
    $overall_datas[0]->journal_type = "Opening Balance";
    $overall_datas[0]->journal_name = "Opening Balance";
    $overall_datas[0]->concatenated_segments = '';
    $overall_datas[0]->reference_source = '';
    $overall_datas[0]->reference_name = '';

    // ==============================
    // 🔹 LOOP THROUGH LEDGER DATA
    // ==============================
    $key = 1;
    foreach ($ledger_data as $value) {
        $id = $value->journal_entry_id;
        $reference_name = $value->reference_name;
        $reference_source = $value->reference_source;
        $debit_amount = $value->debit_amount;
        $credit_amount = $value->credit_amount;

        // Get the counter-account segment name
        if ($debit_amount > 0) {
            $accQuery = \DB::select("
                SELECT fas.concatenated_segments
                FROM f_journal_entry_lines_t fjel
                JOIN f_account_structure_t fas 
                  ON fas.f_account_structure_id = fjel.account_id
                WHERE fjel.journal_entry_id = '$id'
                  AND fjel.debit_amount = 0
                LIMIT 1
            ");
        } else {
            $accQuery = \DB::select("
                SELECT fas.concatenated_segments
                FROM f_journal_entry_lines_t fjel
                JOIN f_account_structure_t fas 
                  ON fas.f_account_structure_id = fjel.account_id
                WHERE fjel.journal_entry_id = '$id'
                  AND fjel.credit_amount = 0
                LIMIT 1
            ");
        }

        $acc_name = !empty($accQuery) ? $accQuery[0]->concatenated_segments : '';

        $overall_datas[$key] = (object)[];
        $overall_datas[$key]->concatenated_segments = $acc_name;
        $overall_datas[$key]->journal_entry_id = $id;
        $overall_datas[$key]->journal_date = $value->journal_date;
        $overall_datas[$key]->journal_type = $value->journal_type;
        $overall_datas[$key]->journal_name = $value->journal_name;
        $overall_datas[$key]->reference_source = $reference_source;
        $overall_datas[$key]->reference_name = $reference_name;

        // Debit or Credit Handling
        if ($debit_amount > 0) {
            if ($value->journal_type == 'MANUAL' && $reference_source == 'CUSTOMER') {
                $overall_datas[$key]->credit_amounts = abs($debit_amount);
                $overall_datas[$key]->debit_amounts = 0;
                $balance -= abs($debit_amount);
            } else {
                $overall_datas[$key]->debit_amounts = $debit_amount;
                $overall_datas[$key]->credit_amounts = 0;
                $balance += $debit_amount;
            }
        } else {
            if ($value->journal_type == 'MANUAL' && $reference_source == 'CUSTOMER') {
                $overall_datas[$key]->debit_amounts = abs($credit_amount);
                $overall_datas[$key]->credit_amounts = 0;
                $balance += abs($credit_amount);
            } else {
                $overall_datas[$key]->credit_amounts = $credit_amount;
                $overall_datas[$key]->debit_amounts = 0;
                $balance -= $credit_amount;
            }
        }

        $overall_datas[$key]->balance = round($balance, 2);
        $overall_datas[$key]->net_salary = 0;
        $key++;
    }

    // Return JSON response
    $data = collect($overall_datas)->map(function ($x) {
        return (array) $x;
    })->toArray();

    return response()->json($data);
}


  public function getledgerreport()
  {


    $wh = '';
    if (isset($_GET['pq_filter'])) {
      $data = json_decode($_GET['pq_filter']);
      $data = $data->data;
      $table = array('f_journal_entry_lines_t', 'f_account_structure_t');

      $wh .= $this->pqgridsearch('f_journal_entry_t', $data, $table);
    }


    $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? date("Y-m-d", strtotime($_GET['start_date'])) : '';
    $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';


    $org = \Session::get('organization');
    $loc = \Session::get('location');
    $compy = \Session::get('companyid');


    $sidx = '';
    if (!$sidx)
      $sidx = 1;



    $compy = \Session::get('companyid');

    $SQL = "select v2.*,sum(v1.debit) as debit,sum(v1.credit)credit,sum(v1.balance)as opening_balance,round(sum(v1.balance+v1.debit-v1.credit),2) as balance  from((SELECT f_journal_entry_t.journal_name,round(sum(f_journal_entry_lines_t.debit_amount),2) as debit,round(sum(f_journal_entry_lines_t.credit_amount),'2') as credit,f_account_structure_t.concatenated_segments,0 as balance,f_journal_entry_lines_t.account_id FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t on f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id LEFT JOIN f_account_structure_t on f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE   f_journal_entry_lines_t.journal_date between '$start_date' and '$end_date'   GROUP BY f_journal_entry_lines_t.account_id  ORDER BY $sidx) union all (SELECT '' as journal_name,0 as debit,0 as credit,f_account_structure_t.concatenated_segments,round(sum(f_journal_entry_lines_t.debit_amount-f_journal_entry_lines_t.credit_amount),2) as balance,f_journal_entry_lines_t.account_id FROM `f_journal_entry_t` LEFT JOIN f_journal_entry_lines_t on f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id LEFT JOIN f_account_structure_t on f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id WHERE  f_journal_entry_lines_t.journal_date < '$start_date' and  f_journal_entry_lines_t.journal_date >='2019-04-01'   GROUP BY f_journal_entry_lines_t.account_id  ORDER BY $sidx))v1 join (select f.f_account_structure_id,m.account_class_name as main,r1.account_code_meaning as sub1, if(f.future_reference1>0,r2.account_code_meaning,'')as sub2,if(f.future_reference2>0,r3.account_code_meaning,'')as sub3,if(f.sub_account4_id>0,r4.account_code_meaning,'')as sub4 from f_account_structure_t as f left JOIN f_account_class_t as m ON m.account_class_id=f.main_account_id left JOIN f_account_codes_lines_t as r1 ON r1.account_codes_line_id=f.sub_account_id
JOIN f_account_codes_lines_t as r2 ON r2.account_codes_line_id=f.future_reference1 left JOIN f_account_codes_lines_t as r3 ON r3.account_codes_line_id=f.future_reference2 left JOIN f_account_codes_lines_t as r4 ON r4.account_codes_line_id=f.sub_account4_id) as v2 on v2.f_account_structure_id=v1.account_id group by f_account_structure_id";


    //dd($SQL);



    $result = \DB::select($SQL);

    $responce->rows[] = '';
    $responce->data = $result;
    $responce->curPage = 1;
    $responce->total = 1;
    $responce->totalRecords = count($result);
    echo json_encode($responce);
  }

  public function generalledger()
  {
    $this->data['result'] = [];
    return view('trailbalance.generalledgerrpt', $this->data);
  }
  public function getgeneralledger()
  {
    $start_date = isset($_GET['start_date']) && !empty($_GET['start_date']) ? date("Y-m-d", strtotime($_GET['start_date'])) : '';
    $end_date = isset($_GET['end_date']) && !empty($_GET['end_date']) ? date("Y-m-d", strtotime($_GET['end_date'])) : '';

    $sql = \DB::select("SELECT
    f_account_codes_lines_t.account_codes_line_id,
    f_account_codes_lines_t.account_class_id,
f_account_codes_lines_t.account_code,
f_account_codes_lines_t.account_code_meaning,
    SUM(
        f_journal_entry_lines_t.debit_amount
    ) AS total_debit,
    SUM(
        f_journal_entry_lines_t.credit_amount
    ) AS total_credit,
    f_journal_entry_lines_t.f_journal_entry_line_id,
    f_account_structure_t.future_reference2
FROM
    `f_account_codes_lines_t`
LEFT JOIN f_account_structure_t ON
    (
        f_account_structure_t.future_reference2 = f_account_codes_lines_t.account_codes_line_id
    )
LEFT JOIN f_journal_entry_lines_t ON(
        f_journal_entry_lines_t.account_id = f_account_structure_t.f_account_structure_id
    )
	left join f_journal_entry_t on(f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id)
where f_journal_entry_lines_t.journal_date BETWEEN '$start_date' AND '$end_date' and (f_journal_entry_t.journal_status='POSTED' or f_journal_entry_t.journal_status='APPROVED')
GROUP BY
    f_account_codes_lines_t.account_codes_line_id");
    $this->data['start_date'] = $start_date;
    $this->data['end_date'] = $end_date;

    $this->data['result'] = $sql;
    return view('trailbalance.gltable', $this->data);
  }
}