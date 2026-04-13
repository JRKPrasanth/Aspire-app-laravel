<?php

namespace App\Http\Controllers;
use DB;
use DateTime;
use Session;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class ApprovepayrollController extends Controller
{
    public function __construct()
    {
        $this->data['pageModule'] = \Request::route()->getName();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['urlmenu'] = $this->indexs();
    }
    // approve index page load function start
    public function approvepayroll(Request $request)
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

        $result = DB::table('hr_employee_payroll_lists')->where('approved_status', 0)->get();

        return view('approvepayroll.approvepayroll', $this->data);
    }
    // approve index page load function end


    public function releasepayroll(Request $request)
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

        $result = DB::table('hr_employee_payroll_lists')->where('re_status', 0)->get();

        return view('approvepayroll.releasepayroll', $this->data);
    }


    public function arrearpayroll(Request $request)
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


        return view('approvepayroll.arrearpayroll', $this->data);
    }
    public function arrearpayrollgriddata()
    {

        $compy = \Session::get('companyid');
        $logged_user = \Session::get('emp_id');

        $wh = " and v1.company_id='$compy'";


        $SQL = "select * from (SELECT   hr_employee_payproposal.arrears_status,hr_employee_payproposal.effective_date,hr_employee_payproposal.esi_amount,hr_employee_payproposal.pf_amount,hr_employee_payproposal.pt_amount,hr_employee_payproposal.id,hr_employee_payproposal.volunter_pf,hr_employee_payproposal.company_id,hr_employee_payproposal.employee_id,hr_employee_payproposal.net_pay,concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as first_name, hr_employee_payproposal.employee_type,hr_employee_payproposal.payroll_type, a_lookuplines_t.lookup_code, hr_employee_payproposal.basic_pay,  hr_employee_payproposal.hra,  hr_employee_payproposal.da,hr_employee_payproposal.gratuity,  hr_employee_payproposal.annual_allowance, hr_employee_payproposal.allowance,  hr_employee_payproposal.esi, hr_employee_payproposal.pf, hr_employee_payproposal.pt, hr_employee_payproposal.ctc_pay, hr_employee_payproposal.gross_pay, (case  when hr_employee_payproposal.esi = 1 then 'Yes' when hr_employee_payproposal.esi = 0 then 'No'  end) as esi_status, (case  when hr_employee_payproposal.pf = 1 then 'Yes'   when hr_employee_payproposal.pf = 0 then 'No'     end) as pf_status  FROM hr_employee_payproposal LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_employee_payproposal.employee_id   LEFT JOIN a_lookuplines_t  ON a_lookuplines_t.lookuplines_id = hr_employee_payproposal.payroll_type)v1 where 1=1  $wh ORDER by v1.id DESC";

        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }


    public function arrearpayrollgenerate()
    {

        $row_id = $_GET['row_id'];
        $payroll_id = explode(',', $row_id);
        $esi_cutoff_months = array('09', '03');
        if ($row_id != '') {
            foreach ($payroll_id as $key => $value) {

                $basic_account = 0;
                $hra_account = 0;
                $da_account = 0;
                $net_account = 0;
                $esi_employee_account = 0;
                $esi_company_account = 0;
                $pf_employee_account = 0;
                $pf_company_account = 0;
                $pf_company1_account = 0;
                $pt_account = 0;
                $tax_account = 0;
                $ot_account = 0;
                $advance_account = 0;
                $allow_acc = array();
                $deduct_acc = array();
                $annual_allowance_account = 0;
                $gratuity_account = 0;
                $gross_salary_account = 0;
                $volunter_account = 0;
                $cl = 0;
                $el = 0;
                $od = 0;
                $sl = 0;
                $partial = 0;
                $compensated_days = 0;
                $pay_id = $value;
                $query = DB::table('hr_employee_payproposal')->where('id', $value)->get();
                //   dd($query);
                $emp_id = $query[0]->employee_id;
                $query_emp = DB::table('hr_employee_t')->where('employee_id', $emp_id)->get();
                $dep = json_decode($query_emp[0]->department);
                $department = 0;
                if (count($dep) > 0) {
                    $department = $dep[0];
                }
                $effective_date = $query[0]->effective_date;
                $effective_date_month = date('m', strtotime($query[0]->effective_date));
                $effective_date_year = date('Y', strtotime($query[0]->effective_date));
                $updated_date = $query[0]->updated_at;
                $updated_date_month = date('n', strtotime($query[0]->updated_at));
                $updated_date_year = date('Y', strtotime($query[0]->updated_at));

                $check_current_query = DB::table('hr_employee_payroll_lists')->where('employee_id', $query[0]->employee_id)->where('month', $updated_date_month)->where('year', $updated_date_year)->get();
                //   dd($check_current_query);
                $updated_date = $end_effective_date = date("Y-m-d", strtotime($updated_date));
                //  dd($updated_date);
                if (count($check_current_query) > 0) {
                    $check_date = date("Y-m-d", strtotime($check_current_query[0]->date));
                    //   dd($check_date);
                    if ($check_date >= $updated_date) {
                        $end_effective_date = date("Y-m-d", strtotime($query[0]->updated_at . "-1 months"));
                        //   dd($end_effective_date);
                    }
                } else {
                    $end_effective_date = date("Y-m-d", strtotime($query[0]->updated_at));
                }
                $end_effective_month = date('m', strtotime($end_effective_date));
                $end_effective_year = date('Y', strtotime($end_effective_date));
                $begin_date = new DateTime($effective_date_year . "-" . $effective_date_month);
                $end_date = new DateTime($end_effective_year . "-" . $end_effective_month);
                //   dd($end_date,$begin_date);
                while ($begin_date <= $end_date) {
                    $date = (string) $begin_date->format("Y-m-d");
                    $date_month = date('n', strtotime($date));
                    $date_year = date('Y', strtotime($date));
                    //dd($date_month);
                    $attendance_exists_employee = DB::table("hr_employee_payroll_lists")->where('month', $date_month)->where('year', $date_year)->where('employee_id', $emp_id)->get();
                    $attendance_exists_employee_volun = DB::table("hr_company_contribute_pf")->where('month', $date_month)->where('year', $date_year)->where('emp_id', $emp_id)->get();
                    // dd($attendance_exists_employee);  
                    error_reporting(0);

                    if (count($attendance_exists_employee) > 0) {

                        // no of days
                        $totaldays = $attendance_exists_employee[0]->total_days;
                        $no_of_days_employee = $attendance_exists_employee[0]->no_of_days_employee;
                        $attendance_exists = DB::table("hr_monthly_attendance")->where('month', $date_month)->where('year', $date_year)->where('employee_id', $emp_id)->get();
                        $loan = $attendance_exists[0]->loan_deduction;
                        // working days
                        $working_days = $attendance_exists_employee[0]->attendance_days;

                        //$grade_details_before =\DB::select("SELECT *  FROM `hr_employee_payproposal_before` WHERE `employee_id` = $emp_id ORDER BY id DESC LIMIT 1");

                        $grade_details = DB::table("hr_employee_payproposal")->where('employee_id', $emp_id)->get();

                        $old_gross_pay = $attendance_exists_employee[0]->gross_salary;
                        $old_emp_con = $attendance_exists_employee[0]->esi;
                        $ctc_pay = $grade_details[0]->ctc_pay;
                        $basic_pay = $grade_details[0]->basic_pay;
                        $hra = $grade_details[0]->hra;
                        $annual_allowance = $grade_details[0]->annual_allowance;
                        $da = $grade_details[0]->da;
                        $gratuity = $grade_details[0]->gratuity;
                        $allowance = $grade_details[0]->allowance;
                        $pt_deduct = $attendance_exists_employee[0]->pt;
                        $pf_deduct = $grade_details[0]->pf;
                        $pf_value_check = $basic_pay + $da;
                        $esi_value_check = $grade_details[0]->gross_pay;
                        $volunter_pf = 0;
                        if ($volunter_pf == '') {
                            $volunter_pf = 0;
                        }
                        $total_basic = $basic_pay / $totaldays;
                        $total_hra = $hra / $totaldays;
                        $total_da = $da / $totaldays;
                        $basic_pay = round($total_basic * $working_days);
                        $basic_pay = $basic_pay - $attendance_exists_employee[0]->basic_salary;
                        $hra = round($total_hra * $working_days);
                        $hra = $hra - $attendance_exists_employee[0]->hra;
                        $da = round($total_da * $working_days);
                        $da = $da - $attendance_exists_employee[0]->da;
                        $ctc_pay = $ctc_pay - $attendance_exists_employee[0]->ctc_pay;
                        $basic_pay_prop = $grade_details[0]->basic_pay - $attendance_exists_employee[0]->basic_salary;
                        $hra_pay_prop = $grade_details[0]->hra - $attendance_exists_employee[0]->hra;
                        $da_pay_prop = $grade_details[0]->da - $attendance_exists_employee[0]->da;
                        $esi_pay_prop = $grade_details[0]->esi;
                        $pf_pay_prop = $grade_details[0]->pf;
                        $pt_pay_prop = $grade_details[0]->pt;
                        $gross_pay_prop = $grade_details[0]->gross_pay - $attendance_exists_employee[0]->gross_salary;
                        $net_pay_prop = $grade_details[0]->net_pay - $attendance_exists_employee[0]->net_salary;
                        $esi_amt_prop = $grade_details[0]->esi_amount - $attendance_exists_employee[0]->esi_amount_pay;
                        $pf_amt_prop = $grade_details[0]->pf_amount - $attendance_exists_employee[0]->pf_amount_pay;
                        $pt_amt_prop = $grade_details[0]->pt_amount - $attendance_exists_employee[0]->pt_amount_pay;


                        $allow = json_decode($allowance);
                        //     dd($attendance_exists_employee[0]->allowance);
                        $allow_new = json_decode($attendance_exists_employee[0]->allowance);
                        $allow_new = collect($allow_new)->map(function ($x) {
                            return (array) $x; })->toArray();
                        $deduct_allowance = 0;
                        $additon_allowance = 0;
                        $allow_en = [];

                        //total days and working days not equal mean calculate
                        foreach ($allow as $k => $v) {
                            foreach ($v as $k1 => $v1) {
                                $allowance_deduction = \DB::SELECT("SELECT type from m_allowance_tbl where allowance_id=" . $k1);
                                if (count($allowance_deduction) > 0) {
                                    if ($allowance_deduction[0]->type == "Allowance") {
                                        $total_allow = $v1 / $totaldays;
                                        $add = ($total_allow * $working_days);

                                        foreach ($allow_new as $alk => $alv) {
                                            $add1 = $add - ($alv[$k1]);
                                        }

                                        // dd($add1);
                                        $additon_allowance = $add1 + $additon_allowance;
                                        $allow_en[$k][$k1] = (string) round($add1, 2);
                                        if (isset($allow_acc[$k1])) {
                                            $allow_acc[$k1] = round($allow_acc[$k1] + $add1, 2);
                                        } else {
                                            $allow_acc[$k1] = round($add1, 2);
                                        }
                                    }

                                    if ($allowance_deduction[0]->type == "Deduction") {

                                        $total_allow = $v1 / $totaldays;

                                        $add = ($total_allow * $totaldays);
                                        foreach ($allow_new as $alk => $alv) {
                                            $add1 = $add - ($alv[$k1]);
                                        }
                                        // $deduct_allowance=$add1+$deduct_allowance;
                                        $allow_en[$k][$k1] = (string) round($add1, 2);
                                        if (isset($deduct_acc[$k1])) {

                                            $deduct_acc[$k1] = round($deduct_acc[$k1] + $add1, 2);
                                        } else {
                                            $deduct_acc[$k1] = round($add1, 2);
                                        }

                                    }

                                }

                            }
                        }

                        // dd(json_encode($allow_en));
                        $allowance = json_encode($allow_en);

                        $allowance_prop = $grade_details[0]->allowance;
                        $allow_pay = json_decode($allowance_prop);
                        //     dd($attendance_exists_employee[0]->allowance);
                        $allow_pay_new = json_decode($attendance_exists_employee[0]->allowance);
                        $allow_pay_new = collect($allow_pay_new)->map(function ($x) {
                            return (array) $x; })->toArray();
                        $deduct_allowance_pay = 0;
                        $additon_allowance_pay = 0;
                        $allow_pay_en = [];

                        //total days and working days not equal mean calculate
                        foreach ($allow_pay as $k => $v) {
                            foreach ($v as $k1 => $v1) {
                                $allowance_deduction_pay = \DB::SELECT("SELECT type from m_allowance_tbl where allowance_id=" . $k1);
                                if (count($allowance_deduction_pay) > 0) {
                                    if ($allowance_deduction_pay[0]->type == "Allowance") {
                                        $total_allow_pay = $v1;
                                        $add_pay = $total_allow_pay;

                                        foreach ($allow_pay_new as $alk => $alv) {
                                            $add1_pay = $add_pay - ($alv[$k1]);
                                        }

                                        // dd($add1);
                                        $additon_allowance_pay = $add1_pay + $additon_allowance_pay;
                                        $allow_pay_en[$k][$k1] = (string) round($add1_pay, 2);
                                        if (isset($allow_acc[$k1])) {
                                            $allow_acc[$k1] = round($allow_acc[$k1] + $add1_pay, 2);
                                        } else {
                                            $allow_acc[$k1] = round($add1_pay, 2);
                                        }
                                    }

                                    if ($allowance_deduction_pay[0]->type == "Deduction") {

                                        $total_allow_pay = $v1;

                                        $add_pay = $total_allow_pay;
                                        //   $add1=$add-$allow_new[$k]->$k1;
                                        //   $add1=$add-$allow_new->$k->$k1;  
                                        foreach ($allow_pay_new as $alk => $alv) {
                                            $add1_pay = $add_pay - ($alv[$k1]);
                                        }
                                        // $deduct_allowance=$add1_pay+$deduct_allowance;
                                        $allow_pay_en[$k][$k1] = (string) round($add1_pay, 2);
                                        if (isset($deduct_acc[$k1])) {

                                            $deduct_acc[$k1] = round($deduct_acc[$k1] + $add1_pay, 2);
                                        } else {
                                            $deduct_acc[$k1] = round($add1_pay, 2);
                                        }

                                    }

                                }

                            }
                        }

                        // dd(json_encode($allow_pay_en));
                        $allowance_pro_pay = json_encode($allow_pay_en);



                        $basic_account = $basic_account + $basic_pay;
                        $hra_account = $hra_account + $hra;
                        $da_account = $da_account + $da;
                        $volunter_account = $volunter_account + $volunter_pf;

                        $year_net = $net_pay_tax * 12;
                        //dd($year_net);
                        $all = ($basic_pay + $hra + $da);
                        $esi = 0;
                        $pf = 0;
                        $gross_salary = $all + $additon_allowance;

                        $ot = 0;
                        //ESI calculation and deduction
                        // esi percentage get from master
                        $esi_detatil = $esi = DB::table("m_emp_esi")
                            ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_emp_esi.components')
                            ->select('m_emp_esi.limitto', 'm_emp_esi.employeer_contribute', 'm_emp_esi.company_contribute')->where('lookup_code', 'ESI')->get();
                        // pf percentage get from master
                        $pf_detatil = DB::table("m_emp_esi")
                            ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_emp_esi.components')
                            ->select('m_emp_esi.limitto', 'm_emp_esi.employeer_contribute', 'm_emp_esi.company_contribute', 'm_emp_esi.company_contribute1')->where('lookup_code', 'PF')->get();
                        // pf basic
                        $pf_value = $basic_pay + $da;
                        // esi basic
                        $esi_value = $gross_salary;
                        // esi employee and company contribute
                        $overall_gross = $gross_salary + $old_gross_pay;

                        $company_contribute = array();
                        $emp_con = 0;
                        // dd($grade_details);

                        $prev_cont_esi = DB::table('hr_company_contribute_esi')->where('emp_id', $emp_id)->where('month', $date_month)->where('year', $date_year)->get();
                        // dd($prev_cont_esi);
                        if (count($prev_cont_esi) > 0) {
                            $old_comp_con = $prev_cont_esi[0]->amount;
                        } else {
                            $old_comp_con = 0;
                        }

                        if ($grade_details[0]->esi == 1) {



                            $esi_cutoff = 0;
                            // dd($old_comp_con,$emp_id,$date_month,$date_year);
                            if (count($esi_detatil) > 0) {
                                $limit = $esi_detatil[0]->limitto;

                                if ($esi_value_check >= $limit) {
                                    if ($date_month != 4 && $date_month != 10) {
                                        if ($date_month == 1) {
                                            $prev_month = 12;
                                            $prev_year = $date_year - 1;
                                        } else {
                                            $prev_month = $date_month - 1;
                                            $prev_year = $date_year;
                                        }
                                        $prev_cont_esi = DB::table('hr_company_contribute_esi')->where('emp_id', $emp_id)->where('amount_employee', '!=', 0)->where('month', $prev_month)->where('year', $prev_year)->where('amount_employee', '!=', 0)->get();
                                        if (count($prev_cont_esi) > 0) {
                                            $emp_con = ceil($overall_gross * $esi_detatil[0]->employeer_contribute / 100);
                                            $com_con = round($overall_gross * $esi_detatil[0]->company_contribute / 100, 2);
                                            $emp_con = $emp_con - $old_emp_con;
                                            $com_con = $com_con - $old_comp_con;
                                            $esi_cutoff = 1;
                                        } else {
                                            $emp_con = 0;
                                            $com_con = 0;
                                        }
                                    } else {
                                        $emp_con = 0;
                                        $com_con = 0;
                                    }


                                } else {
                                    $emp_con = ceil($overall_gross * $esi_detatil[0]->employeer_contribute / 100);
                                    $com_con = round($overall_gross * $esi_detatil[0]->company_contribute / 100, 2);
                                    $emp_con = $emp_con - $old_emp_con;
                                    $com_con = $com_con - $old_comp_con;
                                }

                                // dd($com_con,$old_comp_con,$com_con);
                                $esi = $emp_con;
                                $company_contribute['emp_id'] = $emp_id;
                                $company_contribute['month'] = $date_month;
                                $company_contribute['year'] = $date_year;
                                $company_contribute['amount'] = round($com_con, 2);
                                $company_contribute['amount_employee'] = $emp_con;
                                $company_contribute['date'] = date('Y-m-d');
                                $company_contribute['arrear_status'] = 1;
                                $result = DB::table('hr_company_contribute_esi')->insertGetId($company_contribute);
                                // auditlog
                                $this->auditlog($result, "createemployee", "create", $company_contribute, "hr_company_contribute_esi");
                                $esi_company_account = $esi_company_account + round($com_con);
                            }
                        } else {
                            // dd($overall_gross,$esi_detatil[0]->employeer_contribute,($overall_gross*$esi_detatil[0]->employeer_contribute/100));
                            if ($date_month != 4 && $date_month != 10) {
                                if ($date_month == 1) {
                                    $prev_month = 12;
                                    $prev_year = $date_year - 1;
                                } else {
                                    $prev_month = $date_month - 1;
                                    $prev_year = $date_year;
                                }
                                // dd($prev_month,$prev_year);
                                $prev_cont_esi = DB::table('hr_company_contribute_esi')->where('emp_id', $emp_id)->where('amount_employee', '!=', 0)->where('month', $prev_month)->where('year', $prev_year)->where('amount_employee', '!=', 0)->get();
                                // dd(count($prev_cont_esi));
                                if (count($prev_cont_esi) > 0) {
                                    $emp_con = ceil($overall_gross * $esi_detatil[0]->employeer_contribute / 100);
                                    $com_con = round($overall_gross * $esi_detatil[0]->company_contribute / 100, 2);
                                    $emp_con = $emp_con - $old_emp_con;
                                    $com_con = $com_con - $old_comp_con;
                                    $esi_cutoff = 1;
                                } else {
                                    $emp_con = 0;
                                    $com_con = 0;
                                }
                            } else {
                                if ($old_emp_con != 0) {
                                    $emp_con = ceil($overall_gross * $esi_detatil[0]->employeer_contribute / 100);
                                    $com_con = round($overall_gross * $esi_detatil[0]->company_contribute / 100, 2);
                                    $emp_con = $emp_con - $old_emp_con;
                                    $com_con = $com_con - $old_comp_con;
                                    $esi_cutoff = 1;
                                } else {
                                    $emp_con = 0;
                                    $com_con = 0;
                                }
                            }

                            // dd($emp_con,$com_con);
                            if ($emp_con != 0) {

                                $esi = $emp_con;
                                $company_contribute['emp_id'] = $emp_id;
                                $company_contribute['month'] = $date_month;
                                $company_contribute['year'] = $date_year;
                                $company_contribute['amount'] = round($com_con, 2);
                                $company_contribute['amount_employee'] = $emp_con;
                                $company_contribute['date'] = date('Y-m-d');
                                $company_contribute['arrear_status'] = 1;
                                $result = DB::table('hr_company_contribute_esi')->insertGetId($company_contribute);
                                // auditlog
                                $this->auditlog($result, "createemployee", "create", $company_contribute, "hr_company_contribute_esi");
                                $esi_company_account = $esi_company_account + round($com_con);
                            }

                            $esi = $emp_con;
                        }
                        $esi = ceil($esi);
                        // dd($esi);
                        // pf company and employee contibute
                        if ($grade_details[0]->pf == 1) {
                            if (count($pf_detatil) > 0) {
                                $limit = $pf_detatil[0]->limitto;
                                if ($pf_value_check >= $limit) {

                                    //employee contribute
                                    $limit_pf = round($limit * $pf_detatil[0]->employeer_contribute / 100);

                                    $pf = round($pf_value * $pf_detatil[0]->employeer_contribute / 100);
                                    $add_pf = $pf + ($attendance_exists_employee[0]->pf - $attendance_exists_employee_volun[0]->volunter_pf);
                                    if ($add_pf >= $limit_pf) {
                                        $pf = $limit_pf - ($attendance_exists_employee[0]->pf - $attendance_exists_employee_volun[0]->volunter_pf);
                                    } else {
                                        $pf = $pf;
                                    }
                                    $emp_con1 = round($pf);
                                    //company contibure
                                    $limit_pf_c = round($limit * $pf_detatil[0]->company_contribute / 100);
                                    $pf_c = round($pf_value * $pf_detatil[0]->company_contribute / 100);
                                    $add_pf_c = $pf_c + ($attendance_exists_employee_volun[0]->amount);
                                    if ($add_pf_c >= $limit_pf_c) {
                                        $pf_c = $limit_pf_c - ($attendance_exists_employee_volun[0]->amount);
                                    } else {
                                        $pf_c = $pf_c;
                                    }

                                    $com_con1 = round($pf_c);


                                    //company contibute1
                                    $limit_pf_c1 = round($limit * $pf_detatil[0]->company_contribute1 / 100);

                                    $pf_c1 = round($pf_value * $pf_detatil[0]->company_contribute1 / 100);
                                    $add_pf_c1 = $pf_c1 + ($attendance_exists_employee_volun[0]->amount1);
                                    if ($add_pf_c1 >= $limit_pf_c1) {
                                        $pf_c1 = $limit_pf_c1 - ($attendance_exists_employee_volun[0]->amount1);
                                    } else {
                                        $pf_c1 = $pf_c1;
                                    }

                                    $com_con2 = round($pf_c1);



                                    $edli == round(($pf * 0.5) / 100);

                                } else {

                                    $pf = round($pf_value * $pf_detatil[0]->employeer_contribute / 100);
                                    $emp_con1 = round($pf);
                                    $com_con1 = round($pf_value * $pf_detatil[0]->company_contribute / 100);
                                    $com_con2 = round($pf_value * $pf_detatil[0]->company_contribute1 / 100);
                                    $edli = round(($pf_value * 0.5) / 100);
                                }
                                $epf = $pf;
                                $pf = $pf;
                                $vpf = 0;
                                $company_contribute['emp_id'] = $emp_id;
                                $company_contribute['volunter_pf'] = 0;
                                $company_contribute['month'] = $date_month;
                                $company_contribute['year'] = $date_year;

                                if ($attendance_exists_employee_volun[0]->amount_employee != '1800') {
                                    $company_contribute['amount'] = round($com_con1);
                                    $company_contribute['amount1'] = round($com_con2);
                                    $company_contribute['edli_charges'] = round($edli);
                                    $company_contribute['admin_charges'] = round($edli);
                                    $company_contribute['amount_employee'] = round($emp_con1);
                                } else {
                                    $company_contribute['amount'] = 0;
                                    $company_contribute['amount1'] = 0;
                                    $company_contribute['edli_charges'] = 0;
                                    $company_contribute['admin_charges'] = 0;
                                    $company_contribute['amount_employee'] = 0;
                                }

                                $company_contribute['date'] = date('Y-m-d');
                                $company_contribute['arrear_status'] = 1;
                                $result = DB::table('hr_company_contribute_pf')->insertGetId($company_contribute);
                                // auditlog
                                $this->auditlog($result, "createemployee", "create", $company_contribute, "hr_company_contribute_pf");

                                $pf_company_account = $pf_company_account + round($com_con1);
                                $pf_company1_account = $pf_company1_account + round($com_con2);
                            }
                        } else {
                            $pf = 0;
                            $epf = 0;
                        }
                        $pf = round($pf);


                        // IF Professional tax   
                        $location = json_decode(Session::get('location'));
                        $professional_taxs = DB::table('m_location_t')->leftJoin('hr_professional_tax_hdr', 'hr_professional_tax_hdr.ptax_state_id', '=', 'm_location_t.state_id')->leftjoin('hr_professional_tax_lines', 'hr_professional_tax_lines.ptax_id', '=', 'hr_professional_tax_hdr.ptax_id')->select('hr_professional_tax_hdr.*', 'hr_professional_tax_lines.*')->where('m_location_t.location_id', $location)->get();
                        $professional_tax_gross = 0;

                        if ($grade_details[0]->pt == 1) {
                            if (count($professional_taxs) > 0) {


                                foreach ($professional_taxs as $index => $value) {

                                    if ($overall_gross >= $value->from_value && $overall_gross <= $value->to_value) {
                                        $professional_tax = $value->deduction_amount;
                                        break;
                                    } else {
                                        $professional_tax = 0;
                                    }
                                }

                                $professional_tax = $professional_tax - $pt_deduct;

                            } else {
                                $professional_tax = 0;

                            }
                        } else {
                            $professional_tax = 0;
                        }

                        $esi_employee_account = $esi_employee_account + $esi;
                        $pf_employee_account = $pf_employee_account + round($epf);
                        $pt_account = $pt_account + round($professional_tax);
                        $net_salary = round($gross_salary - $tax_amount, 2);
                        $net_salary = round($gross_salary - $pf - $esi - $professional_tax - $deduct_allowance, 2);
                        $net_account = $net_account + $net_salary;
                        $advance_account = $advance_account + $total_loan_amount;
                        $ot_account = $ot_account + $ot_amount;
                        $annual_allowance_account = $annual_allowance + $annual_allowance_account;
                        $gratuity_account = $gratuity_account + $gratuity;
                        $gross_salary_account = $gross_salary_account + $gross_salary;
                        $current_date = date('Y-m-d');
                        $data = array(
                            'employee_id' => $emp_id,
                            'month' => $date_month,
                            'year' => $date_year,
                            'ctc_pay' => round($ctc_pay),
                            'basic_salary' => round($basic_pay),
                            'hra' => round($hra),
                            'da' => round($da),
                            'pf' => round($pf),
                            'vpf' => round($volunter_pf),
                            'annual_allowance' => round($annual_allowance),
                            'gratuity' => round($gratuity),
                            'allowance' => $allowance,
                            'esi' => round($esi),
                            'pt' => round($professional_tax),
                            'earn_salary' => round($earn_salary),
                            'gross_salary' => round($gross_salary),
                            'net_salary' => round($net_salary),
                            'basic_pay' => round($basic_pay_prop),
                            'hra_pay' => round($hra_pay_prop),
                            'da_pay' => round($da_pay_prop),
                            'esi_pay' => $esi_pay_prop,
                            'pf_pay' => $pf_pay_prop,
                            'pt_pay' => $pt_pay_prop,
                            'gross_pay' => round($gross_pay_prop),
                            'net_pay' => round($net_pay_prop),
                            'esi_amount_pay' => round($esi_amt_prop),
                            'pf_amount_pay' => round($pf_amt_prop),
                            'pt_amount_pay' => round($pt_amt_prop),
                            'allowance_pay' => $allowance_pro_pay,
                            'date' => $current_date,
                            'total_days' => $totaldays,
                            'no_of_days_employee' => $no_of_days_employee,
                            'attendance_days' => $working_days,
                            'loan_deduction' => 0,
                            'atten_status' => "ARREARPAYROLL",
                            'tax_amount' => 0,
                            'cl' => $cl,
                            'el' => $el,
                            'od' => $od,
                            'sl' => $sl,
                            'ot' => $ot_amount,
                            'partial_day' => $partial,
                            'compensated_day' => $compensated_days,
                            'company_id' => \Session::get('companyid'),
                            'location_id' => \Session::get('location'),
                            'organization_id' => \Session::get('organization'),
                            'created_at' => date('Y-m-d'),
                            'arrear_status' => 1,
                            'esi_cutoff' => $esi_cutoff,
                            'created_by' => \Session::get('id')
                        );


                        $emp_id_leave = DB::table('hr_employee_payroll_lists')->insertGetId($data);

                        $datasss['arrears_status'] = 0;
                        DB::table('hr_employee_payproposal')->where('id', $pay_id)->update($datasss);
                        //auditlog
                        $this->auditlog($emp_id_leave, "payrollgenerate", "update", $data, "hr_employee_payroll_lists");


                    }

                    $begin_date->modify('+1 months');

                }

            }

        }

        return 1;

    }

    //row based approve
    public function approvedpayroll()
    {

        $row_id = $_GET['row_id'];
        $list_id = explode(',', $row_id);
        if ($row_id != '') {
            foreach ($list_id as $key => $value) {

                $query = DB::table('hr_employee_payroll_lists')->where('id', $value)->update(['approved_status' => 1, 're_status' => 2]);
                //auditlog
                $update['approved_status'] = 1;
                $this->auditlog($value, "approvepayroll", "update", $update, "hr_employee_payroll_lists");
            }
        }
        return 1;
    }
    // approve based on month source year company
    public function approvepayrollsource()
    {
        $month = $m_id = $_GET['month'];
        $year = $_GET['year'];
        $source = $_GET['source'];
        $comp = $_GET['company'];
        DB::table('hr_employee_payroll_lists')->leftJoin('hr_employee_payproposal', 'hr_employee_payproposal.employee_id', '=', 'hr_employee_payroll_lists.employee_id')->leftJoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'hr_employee_payroll_lists.atten_status')->where('hr_employee_payroll_lists.atten_status', $source)->where('hr_employee_payroll_lists.month', 'like', '%' . $month . '%')->where('hr_employee_payroll_lists.year', $year)->where('hr_employee_payroll_lists.company_id', $comp)->where('hr_employee_payroll_lists.approved_status', 0)->where('hr_employee_payroll_lists.payment_status', 0)->update(['approved_status' => 1, 're_status' => 2]);
        //auditlog
        $this->auditlog('1', "approvepayroll", "update", $_GET, "hr_employee_payroll_lists");
        return 1;
    }



    public function employeepayrollgriddataapprove()
    {

        $comp = \Session::get('companyid');

        $SQL = " select * from (SELECT  hr_employee_payroll_lists.id,
      hr_employee_t.employee_number,
      hr_employee_t.first_name,
      m_department_lines_t.sub_department_name,
      hr_employee_payroll_lists.month,
      hr_employee_payroll_lists.year,
      hr_employee_payroll_lists.date,
      hr_employee_payroll_lists.basic_salary,
	    hr_employee_payroll_lists.annual_allowance,
      hr_employee_payroll_lists.hra,
      hr_employee_payroll_lists.da,
      hr_employee_payroll_lists.attendance_days,
      sum(hr_employee_payroll_lists.pf-COALESCE(hr_employee_payproposal.volunter_pf,0)) as pf_val,
      hr_employee_payroll_lists.esi as esi_val,
      hr_employee_payroll_lists.pt as pt_val,
      hr_employee_payproposal.volunter_pf,
      hr_employee_payproposal.employee_type,
    hr_employee_payroll_lists.gross_salary,
      hr_employee_payroll_lists.net_salary,
      hr_employee_payproposal.ctc_pay,
      
      a_lookuplines_t.lookup_code,
      (
      CASE WHEN hr_employee_payroll_lists.approved_status = 0 THEN 'Pending' WHEN hr_employee_payroll_lists.approved_status = 1 THEN 'Approved'
                        END
      ) 
        AS approved_status,
        (
      CASE WHEN hr_employee_payproposal.esi = 0 THEN 'No' WHEN hr_employee_payproposal.esi = 1 THEN 'Yes'
                        END
      ) 
        AS esi1,
        (
      CASE WHEN hr_employee_payproposal.pf = 0 THEN 'No' WHEN hr_employee_payproposal.pf = 1 THEN 'Yes'
                        END
      ) 
        AS pf1,
    (
      CASE WHEN hr_employee_payproposal.pt = 0 THEN 'No' WHEN hr_employee_payproposal.pt = 1 THEN 'Yes'
                        END
      )    
        AS pt1

      FROM hr_employee_payroll_lists left join hr_employee_payproposal on hr_employee_payproposal.employee_id=hr_employee_payroll_lists.employee_id left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=hr_employee_payproposal.payroll_type
       LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id
       left join m_department_lines_t on m_department_lines_t.department_line_id=REPLACE (JSON_EXTRACT(hr_employee_t.department, '$[0]'),'" . '"' . "','') where hr_employee_payroll_lists.company_id=$comp and hr_employee_payroll_lists.approved_status=0 group by  hr_employee_payroll_lists.id)v1";


        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);
    }


    //row based Release
    public function releasedpayroll()
    {

        $row_id = $_GET['row_id'];
        $list_id = explode(',', $row_id);
        if ($row_id != '') {
            foreach ($list_id as $key => $value) {

                $query = DB::table('hr_employee_payroll_lists')->where('id', $value)->update(['re_status' => 1, 'ac_status' => 1, 'acapprove_status' => 'APPROVED']);
                //auditlog
                $update['re_status'] = 1;
                $update['ac_status'] = 1;
                $this->auditlog($value, "approvepayroll", "update", $update, "hr_employee_payroll_lists");
            }
        }
        return 1;
    }
    // Release based on month source year company duplicate
    public function releasepayrollsource()
    {
        $month = $m_id = $_GET['month'];

        $year = $_GET['year'];
        //$source =   $_GET['source'];
        $comp = $_GET['company'];
        //->where('hr_employee_payroll_lists.atten_status',$source)
        DB::table('hr_employee_payroll_lists')->leftJoin('hr_employee_payproposal', 'hr_employee_payproposal.employee_id', '=', 'hr_employee_payroll_lists.employee_id')->leftJoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'hr_employee_payroll_lists.atten_status')->where('hr_employee_payroll_lists.month', 'like', '%' . $month . '%')->where('hr_employee_payroll_lists.year', $year)->where('hr_employee_payroll_lists.company_id', $comp)->where('hr_employee_payroll_lists.approved_status', 1)->where('hr_employee_payroll_lists.payment_status', 0)->update(['re_status' => 1, 'ac_status' => 1]);
        //auditlog
        $this->auditlog('1', "approvepayroll", "update", $_GET, "hr_employee_payroll_lists");
        return 1;
    }

    // employee payroll grid data Release
    public function employeepayrollgriddatarelease()
    {
        $comp = \Session::get('companyid');

        $SQL = " select * from (SELECT  hr_employee_payroll_lists.id,
      hr_employee_t.employee_number,
      hr_employee_t.first_name,
      m_department_lines_t.sub_department_name,
      hr_employee_payroll_lists.month,
      hr_employee_payroll_lists.year,
      hr_employee_payroll_lists.date,
      hr_employee_payroll_lists.basic_salary,
	  hr_employee_payroll_lists.annual_allowance,
      hr_employee_payroll_lists.hra,
      hr_employee_payroll_lists.da,
      hr_employee_payroll_lists.attendance_days,
      sum(hr_employee_payroll_lists.pf-COALESCE(hr_employee_payproposal.volunter_pf,0)) as pf_val,
      hr_employee_payroll_lists.esi as esi_val,
      hr_employee_payroll_lists.pt as pt_val,
      hr_employee_payproposal.volunter_pf,
      hr_employee_payproposal.employee_type,
    hr_employee_payroll_lists.gross_salary,
      hr_employee_payroll_lists.net_salary,
      hr_employee_payproposal.ctc_pay,
      
      a_lookuplines_t.lookup_code,
      (
      CASE WHEN hr_employee_payroll_lists.re_status = 2 THEN 'Hold' WHEN hr_employee_payroll_lists.re_status = 1 THEN 'Released'
                        END
      ) 
        AS approved_status,
        (
      CASE WHEN hr_employee_payproposal.esi = 0 THEN 'No' WHEN hr_employee_payproposal.esi = 1 THEN 'Yes'
                        END
      ) 
        AS esi1,
        (
      CASE WHEN hr_employee_payproposal.pf = 0 THEN 'No' WHEN hr_employee_payproposal.pf = 1 THEN 'Yes'
                        END
      ) 
        AS pf1,
    (
      CASE WHEN hr_employee_payproposal.pt = 0 THEN 'No' WHEN hr_employee_payproposal.pt = 1 THEN 'Yes'
                        END
      )    
        AS pt1

      FROM hr_employee_payroll_lists left join hr_employee_payproposal on hr_employee_payproposal.employee_id=hr_employee_payroll_lists.employee_id left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=hr_employee_payproposal.payroll_type
       LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_payroll_lists.employee_id
       left join m_department_lines_t on m_department_lines_t.department_line_id=REPLACE (JSON_EXTRACT(hr_employee_t.department, '$[0]'),'" . '"' . "','') where hr_employee_payroll_lists.company_id=$comp and hr_employee_payroll_lists.re_status = 2 and hr_employee_payroll_lists.approved_status = 1 group by  hr_employee_payroll_lists.id)v1";


        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }

    //row based Hold
    public function holdpayroll()
    {

        $row_id = $_GET['row_id'];
        $reason = $_GET['reason'];
        $list_id = explode(',', $row_id);
        if ($row_id != '') {
            foreach ($list_id as $key => $value) {

                $query = DB::table('hr_employee_payroll_lists')->where('id', $value)->update(['re_status' => 2, 're_reason' => $reason]);
                //auditlog
                $update['re_status'] = 2;
                $update['re_reason'] = $reason;
                $this->auditlog($value, "approvepayroll", "update", $update, "hr_employee_payroll_lists");
            }
        }
        return 1;
    }

}
