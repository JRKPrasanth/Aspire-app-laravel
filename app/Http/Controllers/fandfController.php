<?php
namespace App\Http\Controllers;
use App\fandf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Validator, DB;
use Yajra\DataTables\DataTables;
use File;
use Config;
use App\Http\Controllers\Controller;

class fandfController extends Controller
{
    public $module = 'fandf';
    public function __construct()
    {
        $this->data = array();
        $this->table = 'hr_ff_t';
        $this->subtable = '';
        $this->model = new fandf;
        $this->data = array(
            'pageModule' => 'fandf',
            'pageUrl' => url('fandf'),
            'pageMethod' => \Request::route()->getName()
        );
        $this->data["urlmenu"] = $this->indexs();

    }

    /*Deepika Purpose For :Index Function to Call Table Blade*/
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

        $this->data["pageMethod"] = \Request::route()->getName();
        return view('fandf.table', $this->data);
    }
    /* Deepika purpose for Display Data in JQgrid function */


    /* bharathi final settlement screen */

    public function finalsettlement(Request $request)
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


        $this->data["pageMethod"] = \Request::route()->getName();
        return view('finalsettlement.table', $this->data);
    }


    /* F and F data*/

    public function getfandfData(Request $request, $id = null)
    {
        $app_id = \Session::get("id");


        $SQL = "select * from (select hr_ff_t.company_id,hr_ff_t.location_id, hr_ff_t.hr_ff_id , ( select concat(employee_number,'-',first_name)  from  hr_employee_t where hr_employee_t.employee_id=hr_ff_t.emp_id) as emp_id, hr_ff_t.imp_amount , hr_ff_t.exp_amount , hr_ff_t.sal_amount , hr_ff_t.balance_amount , hr_ff_t.paid_amount from hr_ff_t where 1=1 and hr_ff_t.status !='APPROVED')t1";


        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }


    public function getfinalsettlementData(Request $request)
    {


        $SQL = " select * from (select hr_ff_t.company_id,hr_ff_t.location_id, hr_ff_t.hr_ff_id , ( select concat(employee_number,'-',first_name)  from  hr_employee_t where hr_employee_t.employee_id=hr_ff_t.emp_id) as emp_id, hr_ff_t.imp_amount , hr_ff_t.exp_amount , hr_ff_t.sal_amount , hr_ff_t.balance_amount , hr_ff_t.paid_amount  from hr_ff_t where 1=1 and hr_ff_t.status='APPROVED')t1";



        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }
    /*Deepika Purpose for create and update mode*/
    public function create($id = null)
    {
        if ($id == "0") {
            $this->data["pageMethod"] = \Request::route()->getName();
            $this->modelname = new fandf();
            $this->data["row"] = (object) array();
            $table = $this->modelname->getTableColumns();
            foreach ($table as $key => $val) {
                $this->data["row"]->$val = "";
            }
            $emp_id = $_GET['emp_id'];
            $da = \DB::select("SELECT sum(payment_amount) as imprest FROM `p_payments_t`  where employee_id='$emp_id' and (payment_source='IMPREST' or payment_source='DIRECTEXPENSE')
                                    union all 
                                    SELECT sum(payment_amount) as imprest_again FROM `p_payments_t`  where employee_id='$emp_id' and payment_type_id='IMPREST'
                                    union all 
                                    SELECT sum(amount) FROM `hr_imprest_tbl` where imprest_number='OPENING BALANCE' and employee_id='$emp_id'
                                    union all
                                    SELECT sum(balance_amount)   FROM `f_expenses_t` WHERE `employee_id` = '$emp_id 'and expense_status='APPROVED'
                                    union ALL
                                    SELECT sum(net_salary-balance_salary)  FROM `hr_employee_payroll_lists` WHERE `payment_status` = 0  and employee_id='$emp_id'");


            $empp = \DB::select("select relieving_t.notice_period,
                                    relieving_t.created_date as resign_date,
                                    hr_employee_t.date_of_leaving,hr_employee_t.date_of_joining,
                                    (datediff(hr_employee_t.date_of_leaving,hr_employee_t.date_of_joining)/365) as total_service,
                                    if((relieving_t.releive_date_actual!='1970-01-01' && relieving_t.releive_date_actual!='' && relieving_t.releive_date_actual is not null),relieving_t.releive_date_actual,relieving_t.relieve_date) as relieve_date_actual,
                                    if((relieving_t.releive_date_actual!='1970-01-01' && relieving_t.releive_date_actual!='' && relieving_t.releive_date_actual is not null),datediff(relieving_t.releive_date_actual,relieving_t.relieve_date),0) as serve_days,
                                    (case when relieving_t.notice_period=167 then 0
                                        when relieving_t.notice_period=167 then 0
                                        when relieving_t.notice_period=168 then 15
                                        when relieving_t.notice_period=169 then 30
                                        when relieving_t.notice_period=170 then 45
                                        when relieving_t.notice_period=171 then 60
                                        when relieving_t.notice_period=172 then 90
                                        else datediff(relieving_t.releive_date_actual,relieving_t.relieve_date) end) as notice_days
                                    from hr_employee_t
                                    LEFT JOIN relieving_t ON relieving_t.emp_id = hr_employee_t.employee_id 
                                    where employee_id='$emp_id'");

            //	dd($empp);
            // dd($empp);
            $this->data['row']->date_of_leaving = $empp[0]->date_of_leaving;
            $this->data['row']->date_of_joining = $empp[0]->date_of_joining;
            $this->data['row']->resign_date = $empp[0]->resign_date;
            $this->data['row']->total_service = $empp[0]->total_service;
            $this->data['row']->relieve_date_actual = $empp[0]->relieve_date_actual;
            $this->data['row']->serve_days = $empp[0]->serve_days;
            $this->data['row']->notice_days = $empp[0]->notice_days;
            $this->data["row"]->imp_amount = $da[0]->imprest - $da[1]->imprest + $da[2]->imprest;
            $this->data["row"]->exp_amount = $da[3]->imprest;
            $this->data["row"]->sal_amount = $da[4]->imprest;
            $this->data["row"]->balance_amount = ($da[3]->imprest + $da[4]->imprest) - ($da[0]->imprest - $da[1]->imprest + $da[2]->imprest);
            $this->data["row"]->addition = 0;
            $this->data["row"]->deduction = 0;
            $this->data["row"]->salary_advance = 0;
            $this->data["row"]->imprest_cash = 0;
            $this->data["row"]->damages = 0;
            $this->data["row"]->unpaid_loans = 0;
            $this->data["row"]->training_expenses = 0;
            $this->data["row"]->others_staff_welfare = 0;
            $this->data["row"]->notice_period_pay = 0;
            $this->data["row"]->non_receipt_of_clearnce_form = 0;
            $this->data["row"]->non_receipt_of_noc = 0;
            $this->data["row"]->paid_amount_if_any = 0;
            $this->data["row"]->leave_profile = '';
            $this->data["row"]->el = '';
            $this->data["row"]->others = '';

            $this->data["row"]->emp_id = $this->jCombowithoutactive("hr_employee_t", "employee_id", "employee_number|first_name", $_GET['emp_id']);
            $this->data["linedata"] = array();
        } else {

            $this->data["id"] = $id;
            $table = \DB::table('hr_ff_t')->where('hr_ff_id', $id)->get();
            $this->data["pageMethod"] = \Request::route()->getName();
            $this->data["row"] = $table[0];
            $this->data["row"]->emp_id = $this->jCombowithoutactive("hr_employee_t", "employee_id", "employee_number|first_name", $table[0]->emp_id);
        }
        //dd($this->data);
        return view('fandf.form', $this->data);
    }


    public function print($id = null)
    {

        require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');

        $this->data["pageMethod"] = \Request::route()->getName();
        $this->modelname = new fandf();
        $this->data["row"] = (object) array();
        $table = $this->modelname->getTableColumns();
        foreach ($table as $key => $val) {
            $this->data["row"]->$val = "";
        }

        $sql = "select * from 
                (select hr_employee_t.employee_id, 
                hr_employee_t.employee_number,
                hr_employee_t.first_name, 
                hr_employee_t.date_of_joining,
                hr_employee_t.date_of_leaving,
                hr_employee_t.date_of_birth,
                m_job_title.job_title_name,
                hr_employee_t.pf_no,
                hr_employee_t.esi_no,
                hr_employee_t.uan_no,
                hr_employee_t.c_l,
                hr_employee_t.s_l,
                hr_employee_t.e_l,
                hr_emp_personal.pan_number,
                
                hr_emp_salary.account_number,
                hr_emp_salary.bank_name,
                hr_emp_salary.branch_name,
                location.location_name,
                (select loc.address from m_location_t loc where loc.location_id=m_company_t.location_id) as address,
                hr_ff_t.*,
                relieving_t.notice_period as notice_period_id,
                hr_employee_payproposal.gross_pay,
                hr_employee_payproposal.basic_pay,
                (select a_lookuplines_t.lookup_code from a_lookuplines_t  where a_lookuplines_t.lookuplines_id=notice_period_id and lookup_type ='noticeperiod') as notice_period
                
                from hr_ff_t 
                
                LEFT JOIN hr_employee_t on hr_employee_t.employee_id=hr_ff_t.emp_id
                LEFT JOIN m_job_title on m_job_title.job_title_id=hr_employee_t.job_title
                LEFT JOIN m_location_t as location ON location.location_id = hr_employee_t.location_id
                LEFT JOIN hr_emp_salary ON hr_emp_salary.employee_id = hr_employee_t.employee_id
                LEFT JOIN hr_emp_personal ON hr_emp_personal.employee_id = hr_employee_t.employee_id
                LEFT JOIN m_company_t ON m_company_t.company_id = hr_employee_t.company_id
                LEFT JOIN hr_employee_payproposal ON hr_employee_payproposal.employee_id = hr_employee_t.employee_id
                LEFT JOIN relieving_t ON relieving_t.emp_id = hr_employee_t.employee_id
                
                where hr_ff_t.hr_ff_id= $id)t1";

        $result = \DB::select($sql);



        $this->data['employee_number'] = $result[0]->employee_number;
        $this->data['first_name'] = $result[0]->first_name;
        $this->data['date_of_joining'] = $result[0]->date_of_joining;
        $this->data['date_of_leaving'] = $result[0]->date_of_leaving;
        $this->data['date_of_birth'] = $result[0]->date_of_birth;
        $this->data['notice_period'] = $result[0]->notice_period;
        $this->data['job_title_name'] = $result[0]->job_title_name;
        $this->data['address'] = $result[0]->address;
        $this->data['salary_advance'] = $result[0]->salary_advance;
        $this->data['imprest_cash'] = $result[0]->imprest_cash;
        $this->data['damages'] = $result[0]->damages;
        $this->data['unpaid_loans'] = $result[0]->unpaid_loans;
        $this->data['training_expenses'] = $result[0]->training_expenses;
        $this->data['others_staff_welfare'] = $result[0]->others_staff_welfare;
        $this->data['notice_period_pay'] = $result[0]->notice_period_pay;
        $this->data['non_receipt_of_clearnce_form'] = $result[0]->non_receipt_of_clearnce_form;
        $this->data['non_receipt_of_noc'] = $result[0]->non_receipt_of_noc;
        $this->data['paid_amount_if_any'] = $result[0]->paid_amount_if_any;
        $this->data['leave_profile'] = $result[0]->leave_profile;
        $this->data['el'] = $result[0]->el;
        $this->data['others'] = $result[0]->others;
        $this->data['remarks'] = $result[0]->remarks;
        $this->data['gross_pay'] = $result[0]->gross_pay;
        $this->data['basic_pay'] = $result[0]->basic_pay;
        $this->data['grandtotal_b'] = $result[0]->salary_advance + $result[0]->imprest_cash + $result[0]->damages + $result[0]->unpaid_loans + $result[0]->training_expenses + $result[0]->others_staff_welfare + $result[0]->notice_period_pay + $result[0]->non_receipt_of_clearnce_form + $result[0]->non_receipt_of_noc + $result[0]->paid_amount_if_any;
        //dd($result[0]->others);
        $this->data['grandtotal_a'] = (float) $result[0]->leave_profile + (float) $result[0]->el + (float) $result[0]->others;


        $this->data['grandtotal'] = $this->data['grandtotal_a'] - $this->data['grandtotal_b'];

        $this->data["company_logo"] = 'jrks.png';
        $this->data["print"] = 'PRINT';

        //dd($this->data);
        return view('fandf.print', $this->data);
    }

    /*Deepika purpose for Save function*/
    public function save(Request $request)
    {
        $form_id = $request->hr_ff_id;  // primary key

        // Remove unwanted inputs
        $form = $request->except([
            '_token',
            'form_config',
            'form_data_json',
            'savestatus',
            'submit_type',
            'choosefile',
            'existing_file'
        ]);

        // Normalize keys
        $form = $this->normalizeLineFormKeys($form);

        \DB::beginTransaction();

        try {

            // ===== CASE 1 : UPDATE ONLY STATUS WHEN APPROVED =====
            if ($request->status == "APPROVED") {

                if (empty($form_id)) {
                    return response()->json([
                        "status" => "error",
                        "message" => "Cannot approve. Record ID missing."
                    ]);
                }

                // Update existing record status only
                $this->model->where('hr_ff_id', $form_id)->update([
                    'status' => 'APPROVED'
                ]);

                // send mail
                $emp_id = $request->emp_id;
                $ename = \DB::select("SELECT first_name FROM hr_employee_t WHERE employee_id = $emp_id");
                $emp_name = $ename[0]->first_name;

                 $from_mail = "payroll@jrkresearch.com";
                 $to_mail = "accounts@jrkresearch.com";
                 $cc = "payroll@jrkresearch.com,aspire@jrkresearch.com";
                 $sub = "Full and Final Settlement For $emp_name";

                $msg = "<p>Dear Team,<br><br>Please find the full and final settlement for the employee $emp_name.<br><br>Regards,<br>Team HR";

                \Mail::send([], [], function ($message) use ($from_mail, $to_mail, $cc, $sub, $msg) {
                    $message->from($from_mail)
                        ->to($to_mail)
                        ->cc(explode(',', $cc))
                        ->subject($sub)
                        ->setBody($msg, 'text/html');
                });

                \DB::commit();

                return response()->json([
                    "status" => "success",
                    "message" => "Approved Successfully"
                ]);
            }


            // ===== CASE 2 : CREATE NEW RECORD =====
            $data = $this->validatePost($form, $this->table, 'header');
            $insert = $this->model->create($data);

            \DB::commit();

            return response()->json([
                "status" => "success",
                "message" => "Full And Final Settlement Saved"
            ]);

        } catch (\Illuminate\Database\QueryException $e) {

            \DB::rollback();

            return response()->json([
                "status" => "error",
                "message" => "DatabaseError:=> " . $e->getMessage()
            ]);
        }
    }

    public function show($id = null)
    {

        $vdata = \DB::select("select * from (select  ( select concat(employee_number,'-',first_name)  from  hr_employee_t where hr_employee_t.employee_id=hr_ff_t.emp_id) as emp_id, hr_ff_t.imp_amount , hr_ff_t.exp_amount , hr_ff_t.sal_amount , hr_ff_t.balance_amount , hr_ff_t.paid_amount  from hr_ff_t where hr_ff_t.hr_ff_id= $id)t1");
        $this->data["data"] = $vdata;
        return view("fandf.view", $this->data);
    }      /*Deepika purpose for delete function*/
    public function delete(Request $request, $id = null)
    {

        fandf::destroy($id);
        $query = \DB::table("")->where("hr_ff_id", $id)->delete();
        /**Auditlog**/
        $action = "Delete";
        $this->auditlog($id, "fandf", $action, $id, "hr_ff_t");
        if ($query) {
            return 0;
        } else {
            return 1;
        }

    }
    /*End*/
}