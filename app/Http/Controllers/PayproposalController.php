<?php

namespace App\Http\Controllers;
use App\Payproposal;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\DataTables;


class PayproposalController extends Controller
{

    public function __construct()
    {
        $this->data['urlmenu'] = $this->indexs();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageModule'] = \Request::route()->getName();
    }
    /** index page load function **/
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

        return view('payproposal.form', $this->data);
    }



    /** payproposal check based on employee **/
    public function payproposalcheck()
    {
        $edit_id = $_GET['edit_id'];
        $employee_id = $_GET['employee_id'];

        if ($edit_id == '') {
            $proposal = DB::table('hr_employee_payproposal')->where('employee_id', '=', $employee_id)->get();
        } else {
            $whereData = [['employee_id', '=', $_GET['employee_id']], ['id', '!=', $edit_id]];
            $proposal = DB::table('hr_employee_payproposal')->where($whereData)->get();
        }

        if (count($proposal) > 0)
            return 1;
        else
            return 0;
    }
    /** payproposal save function **/

    public function store(Request $request)
    {

        $edit_id = $request->input('edit_id');
        if ($edit_id == '') {

            $employee_id = $request->input('employee_id');
            $payroll_type = $request->input('payroll_type');
            $basic_pay = $request->input('basic');
            $annual_allowance = $request->input('annual_allowance');
            $allow_id = $request->input('allowance_id');
            $allow = $request->input('allowance');
            foreach ($allow_id as $k => $v) {
                $allow_id[$k] = array();
                $allow_id[$k][$v] = $allow[$k];
            }
            $allowance = json_encode($allow_id);
            $employee_type = $request->input('employee_type');
            $hra = $request->input('hra');
            $da = $request->input('da');
            $pf = $request->input('pf');
            $esi = $request->input('esi');
            $pt = $request->input('pt');
            $esi_amount = $request->input('esi_amount');
            $pf_amount = $request->input('pf_amount');
            $pt_amount = $request->input('pt_amount');
            $gross_pay = $request->input('gross_pay');
            $gratuity = $request->input('gratuity');
            $effective_date = date('Y-m-d', strtotime($request->input('effective_date')));
            $company_id = \Session::get('companyid');
            $organization_id = \Session::get('organization');
            $location_id = \Session::get('location');
            $created_by = \Session::get('id');
            $created_at = date('Y-m-d');
            $volunter_pf = $request->input('volunter_pf');
            $ctc_pay = $request->input('ctc_pay');
            $net_pay = $request->input('net_pay');
            $pay_proposal = DB::table('hr_employee_payproposal')->insertGetId(array(
                'effective_date' => $effective_date,
                'esi_amount' => $esi_amount,
                'pf_amount' => $pf_amount,
                'pt_amount' => $pt_amount,
                'volunter_pf' => $volunter_pf,
                'employee_id' => $employee_id,
                'payroll_type' => $payroll_type,
                'basic_pay' => $basic_pay,
                'annual_allowance' => $annual_allowance,
                'gratuity' => $gratuity,
                'hra' => $hra,
                'da' => $da,
                'pf' => $pf,
                'pt' => $pt,
                'esi' => $esi,
                'net_pay' => $net_pay,
                'gross_pay' => $gross_pay,
                'ctc_pay' => $ctc_pay,
                'company_id' => $company_id,
                'organization_id' => $organization_id,
                'location_id' => $location_id,
                'created_by' => $created_by,
                'created_at' => $created_at,
                'employee_type' => $employee_type,
                'allowance' => $allowance
            ));
            // auditlog
            $this->auditlog($pay_proposal, "payproposal", "create", $_POST, "hr_employee_payproposal");
            return 1;
        } else {
            $last_updated_by = \Session::get('id');
            $updated_at = date('Y-m-d');
            $data_query = DB::table('hr_employee_payproposal')->where('id', $edit_id)->get();
            $pro = DB::table('hr_employee_payproposal_before')->insertGetId(array('esi_amount' => $data_query[0]->esi_amount, 'pf_amount' => $data_query[0]->pf_amount, 'pt_amount' => $data_query[0]->pt_amount, 'volunter_pf' => $data_query[0]->volunter_pf, 'net_pay' => $data_query[0]->net_pay, 'payroll_id' => $data_query[0]->id, 'employee_id' => $data_query[0]->employee_id, 'payroll_type' => $data_query[0]->payroll_type, 'employee_type' => $data_query[0]->employee_type, 'basic_pay' => $data_query[0]->basic_pay, 'annual_allowance' => $data_query[0]->annual_allowance, 'allowance' => $data_query[0]->allowance, 'hra' => $data_query[0]->hra, 'da' => $data_query[0]->da, 'pf' => $data_query[0]->pf, 'esi' => $data_query[0]->esi, 'gross_pay' => $data_query[0]->gross_pay, 'ctc_pay' => $data_query[0]->ctc_pay, 'company_id' => $data_query[0]->company_id, 'organization_id' => $data_query[0]->organization_id, 'location_id' => $data_query[0]->location_id, 'last_updated_by' => $last_updated_by, 'updated_at' => $updated_at, 'created_at' => $data_query[0]->created_at, 'created_by' => $data_query[0]->created_by, 'allowance' => $data_query[0]->allowance, 'gratuity' => $data_query[0]->gratuity, 'effects_from' => $data_query[0]->effective_date));
            // auditlog
            $this->auditlog($edit_id, "payproposal", "update", $_POST, "hr_employee_payproposal");
            // auditlog
            $this->auditlog($pro, "payproposal", "create", $data_query, "hr_employee_payproposal_before");
            $employee_id = $request->input('employee_id');
            $edit_id = $request->input('edit_id');
            $payroll_type = $request->input('payroll_type');
            $basic_pay = $request->input('basic');
            $annual_allowance = $request->input('annual_allowance');
            $allow_id = $request->input('allowance_id');
            $allow = $request->input('allowance');
            foreach ($allow_id as $k => $v) {
                $allow_id[$k] = array();
                $allow_id[$k][$v] = $allow[$k];
            }
            $allowance = json_encode($allow_id);
            $ctc_pay = $request->input('ctc_pay');
            $employee_type = $request->input('employee_type');
            $hra = $request->input('hra');
            $da = $request->input('da');
            $pf = $request->input('pf');
            $pt = $request->input('pt');
            $esi = $request->input('esi');
            $esi_amount = $request->input('esi_amount');
            $pf_amount = $request->input('pf_amount');
            $pt_amount = $request->input('pt_amount');
            $gross_pay = $request->input('gross_pay');
            $net_pay = $request->input('net_pay');
            $gratuity = $request->input('gratuity');
            $effective_date = date('Y-m-d', strtotime($request->input('effective_date')));
            $company_id = \Session::get('companyid');
            $organization_id = \Session::get('organization');
            $location_id = \Session::get('location');
            $volunter_pf = $request->input('volunter_pf');

            $pay_proposal = DB::table('hr_employee_payproposal')->where('id', $edit_id)->update(array('arrears_status' => 1, 'effective_date' => $effective_date, 'esi_amount' => $esi_amount, 'pf_amount' => $pf_amount, 'pt_amount' => $pt_amount, 'volunter_pf' => $volunter_pf, 'net_pay' => $net_pay, 'employee_id' => $employee_id, 'payroll_type' => $payroll_type, 'employee_type' => $employee_type, 'basic_pay' => $basic_pay, 'allowance' => $allowance, 'gratuity' => $gratuity, 'hra' => $hra, 'da' => $da, 'pf' => $pf, 'pt' => $pt, 'esi' => $esi, 'annual_allowance' => $annual_allowance, 'gross_pay' => $gross_pay, 'ctc_pay' => $ctc_pay, 'company_id' => $company_id, 'organization_id' => $organization_id, 'location_id' => $location_id, 'last_updated_by' => $last_updated_by, 'updated_at' => $updated_at));

            return 2;



        }
    }

    /*** esi pf get function **/
    public function getesipf(Payproposal $payproposal)
    {
        $esi = DB::table("m_emp_esi")
            ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_emp_esi.components')
            ->select('m_emp_esi.employeer_contribute', 'm_emp_esi.company_contribute', 'm_emp_esi.limit', 'm_emp_esi.limitto')->where('lookup_code', 'ESI')->get();
        if (count($esi) > 0) {
            foreach ($esi as $k => $v) {
                $data['esi'][$k] = $v->employeer_contribute;
                $data['esi_c'][$k] = $v->company_contribute;
                $data['esi_from'][$k] = $v->limit;
                $data['esi_to'][$k] = $v->limitto;
            }
        } else {
            $data['esi'] = 0;
            $data['esi_c'] = 0;
            $data['esi_from'] = 0;
            $data['esi_to'] = 0;
        }
        $pf = DB::table("m_emp_esi")
            ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_emp_esi.components')
            ->select('m_emp_esi.employeer_contribute', 'm_emp_esi.company_contribute', 'm_emp_esi.company_contribute1', 'm_emp_esi.limit', 'm_emp_esi.limitto')->where('lookup_code', 'PF')->get();

        if (count($pf) > 0) {
            foreach ($pf as $k => $v) {
                $data['pf'][$k] = $v->employeer_contribute;
                $pf_c = $v->company_contribute + $v->company_contribute1;
                $data['pf_c'][$k] = $pf_c;
                $data['pf_from'][$k] = $v->limit;
                $data['pf_to'][$k] = $v->limitto;
            }
        } else {
            $data['pf'] = 0;
            $data['pf_c'] = 0;
            $data['pf_from'] = 0;
            $data['pf_to'] = 0;
        }
        $local_decode = DB::table("hr_employee_t")->where('hr_employee_t.employee_id', '=', $_GET['emp_id'])->get();
        $emp_type = $local_decode[0]->employee_type;
        $location_d = json_decode($local_decode[0]->location_id);
        $location = $location_d[0];

        $professional_taxs = DB::table('m_location_t')->leftJoin('hr_professional_tax_hdr', 'hr_professional_tax_hdr.ptax_state_id', '=', 'm_location_t.state_id')->leftjoin('hr_professional_tax_lines', 'hr_professional_tax_lines.ptax_id', '=', 'hr_professional_tax_hdr.ptax_id')->select('hr_professional_tax_hdr.*', 'hr_professional_tax_lines.*')->where('m_location_t.location_id', $location)->get();
        if (count($professional_taxs) > 0) {
            foreach ($professional_taxs as $key => $value) {
                $data['pt_from'][$key] = $value->from_value;
                $data['pt_to'][$key] = $value->to_value;
                $data['deduct'][$key] = $value->deduction_amount;
            }
        } else {
            $data['pt_from'] = 0;
            $data['pt_to'] = 0;
            $data['deduct'] = 0;
        }
        $hra = DB::table("m_emp_esi")
            ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_emp_esi.components')
            ->select('m_emp_esi.limitto')->where('lookup_code', 'HRA')->where('employee_type', $emp_type)->get();
        $da = DB::table("m_emp_esi")
            ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_emp_esi.components')
            ->select('m_emp_esi.limitto')->where('lookup_code', 'DA')->where('employee_type', $emp_type)->get();
        if (count($hra) > 0) {
            $data['hra_per'] = $hra[0]->limitto;
        } else {
            $data['hra_per'] = 0;
        }
        if (count($da) > 0) {
            $data['da_per'] = $da[0]->limitto;
        } else {
            $data['da_per'] = 0;
        }
        return $data;
    }



    /*** deduction data **/
    public function getdeduction(Request $request, Payproposal $payproposal)
    {
        $data['components'] = DB::table("m_emp_esi")->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_emp_esi.components')
            ->select('m_emp_esi.components', 'a_lookuplines_t.lookup_code', 'm_emp_esi.limit', 'm_emp_esi.date', 'm_emp_esi.employeer_contribute', 'm_emp_esi.company_contribute')
            ->get();
        return $data;
    }

    /** delete function **/
    public function delete(Request $request, $id = null)
    {


        $query_data = DB::table('hr_employee_payproposal')->where('id', $id)->get();
        $query = DB::table('hr_employee_payproposal')->where('id', $id)->delete();
        // auditlog
        $this->auditlog($id, "payproposal", "delete", $query_data, "hr_employee_payproposal");

        return 2;

    }


    //ajima


    public function employeepayproposalgriddata()
    {

        $compy = \Session::get('companyid');
        $logged_user = \Session::get('emp_id');

        $wh = " and v1.company_id='$compy'";

        $SQL = "select * from (SELECT hr_employee_payproposal.effective_date,hr_employee_payproposal.esi_amount,hr_employee_payproposal.pf_amount,hr_employee_payproposal.pt_amount,hr_employee_payproposal.id,hr_employee_payproposal.volunter_pf,hr_employee_payproposal.company_id,hr_employee_payproposal.employee_id,hr_employee_payproposal.net_pay,concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as first_name, hr_employee_payproposal.employee_type,hr_employee_payproposal.payroll_type, a_lookuplines_t.lookup_code, hr_employee_payproposal.basic_pay,  hr_employee_payproposal.hra,  hr_employee_payproposal.da,hr_employee_payproposal.gratuity,  hr_employee_payproposal.annual_allowance, hr_employee_payproposal.allowance,  hr_employee_payproposal.esi, hr_employee_payproposal.pf, hr_employee_payproposal.pt, hr_employee_payproposal.ctc_pay, hr_employee_payproposal.gross_pay, (case  when hr_employee_payproposal.esi = 1 then 'Yes' when hr_employee_payproposal.esi = 0 then 'No'  end) as esi_status, (case  when hr_employee_payproposal.pf = 1 then 'Yes'   when hr_employee_payproposal.pf = 0 then 'No'     end) as pf_status  FROM hr_employee_payproposal LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_employee_payproposal.employee_id   LEFT JOIN a_lookuplines_t  ON a_lookuplines_t.lookuplines_id = hr_employee_payproposal.payroll_type)v1 where 1=1 $wh ORDER by v1.id ASC";


        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);
    }





    //based on employee get type and allowance
    public function employeetypegetallowance($id)
    {
        $comp = \Session::get('companyid');

        // Step 1: Get employee type
        $emp = DB::table("hr_employee_t")
            ->select('employee_type')
            ->where('employee_id', $id)
            ->first();

        $data = ['html' => '', 'id' => ''];

        if ($emp) {
            $employeeType = $emp->employee_type;

            // Step 2: Get allowance based on employee type
            $result_details = DB::table("m_allowance_tbl")
                ->leftJoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_allowance_tbl.employee_type')
                ->select('m_allowance_tbl.type', 'm_allowance_tbl.allowance_name', 'm_allowance_tbl.allowance_id')
                ->where('m_allowance_tbl.employee_type', $employeeType)
                ->where('m_allowance_tbl.active', 'Yes')
                ->where('m_allowance_tbl.company_id', $comp)
                ->get();

            $html = '';
            foreach ($result_details as $key => $value) {
                $html .= '<div class="mb-3">
                <label class="form-label fw-semibold col-md-5">' . $value->allowance_name . ':</label>
                <div class="col-md-6">
                    <input type="hidden" name="allowance_id[]" value="' . ($value->allowance_id) . '" >
                    <input type="text" id="allowance_id" name="allowance[]" data-index="' . $value->type . '" class="form-control allowance allowance_id' . ($key + 1) . ' col-md-3">
                </div>
            </div>';
            }

            $data['html'] = $html;
            $data['id'] = $employeeType;
        }

        return $data;
    }


    // get id
    public function allowancegetid($id)
    {
        $comp = \Session::get('companyid');
        $result_details = DB::table("m_allowance_tbl")
            ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_allowance_tbl.employee_type')
            ->leftjoin('hr_employee_t', 'hr_employee_t.employee_type', '=', 'm_allowance_tbl.employee_type')
            ->select('m_allowance_tbl.allowance_name', 'm_allowance_tbl.allowance_id', 'hr_employee_t.employee_type')
            ->where('hr_employee_t.employee_id', $id)->where('m_allowance_tbl.active', 'Yes')->where('m_allowance_tbl.company_id', $comp)->get();
        $allow = [];
        if (count($result_details) > 0) {
            foreach ($result_details as $key => $value) {

                $allow[] = $value->allowance_id;
            }
        }
        return $allow;
    }


    public function allowanceindex(Request $request)
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

        $this->data['employee_id'] = $this->jCombo('hr_employee_t', 'employee_id', 'employee_number|first_name', '');
        $this->data['department_id'] = $this->jCombo('m_department_lines_t', 'department_line_id', 'sub_department_name', '');
        $year = date('Y');
        $month = date('m');
        $this->data['year'] = $this->jCombologin('year', 'year_name', 'year_name', $year);
        $this->data['month'] = $this->jCombologin('m_month_t', 'month_id', 'month_name', $month);

        return view('payproposal.allowanceform', $this->data);
    }


    public function getemployeetypeallowancedata($id)
    {
        $comp = \Session::get('companyid');
        $emp_data = DB::table("hr_employee_t")
            ->select('hr_employee_t.employee_type', 'hr_employee_t.department')
            ->where('hr_employee_t.employee_id', $id)
            ->get();

        $result_details = DB::table("m_allowance_tbl")
            ->leftJoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_allowance_tbl.employee_type')
            ->select('m_allowance_tbl.type', 'm_allowance_tbl.allowance_name', 'm_allowance_tbl.allowance_id')
            ->where('m_allowance_tbl.employee_type', $emp_data[0]->employee_type)
            ->where('m_allowance_tbl.active', 'Yes')
            ->where('m_allowance_tbl.company_id', $comp)
            ->orderBy('m_allowance_tbl.type', 'ASC')
            ->get();

        $data = [];
        $html = '';

        // Editable Allowances - 3 fields per row
        if (count($result_details) > 0) {
            $html .= '<div class="row">';
            foreach ($result_details as $key => $value) {
                if ($key > 0 && $key % 3 == 0) {
                    $html .= '</div><div class="row">'; // New row after every 3 fields
                }

                $html .= '<div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">' . $value->allowance_name . ':</label>
                <input type="hidden" name="allowance_id[]" value="' . ($value->allowance_id) . '">
                <input type="text" name="allowance[]" data-index="' . $value->type . '" class="form-control allowance allowance_id' . ($key + 1) . '" >
            </div>';
            }
            $html .= '</div>';
        } else {
            $data['id'] = '';
        }

        // Readonly Payproposal Allowances - 3 fields per row
        $paytbl = \DB::table('hr_employee_payproposal')->where('employee_id', $id)->get();
        $phtml = '';
        if (count($paytbl) > 0) {
            $allown = json_decode($paytbl[0]->allowance);
            $count = 0;
            $phtml .= '<div class="row">';
            foreach ($allown as $kk => $vv) {
                foreach ($vv as $k1 => $v1) {
                    $allname = \DB::select("select * from m_allowance_tbl where allowance_id = $k1");
                    if (!empty($allname)) {
                        if ($count > 0 && $count % 3 == 0) {
                            $phtml .= '</div><div class="row">'; // New row every 3 fields
                        }

                        $phtml .= '<div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">' . $allname[0]->allowance_name . ':</label>
                        <input type="text" class="form-control pallowance_id' . $k1 . '" value="' . $v1 . '" readonly>
                    </div>';
                        $count++;
                    }
                }
            }
            $phtml .= '</div>';
        } else {
            $phtml .= '<div class="col-md-12 mb-3"><h4><p>No Payproposal Data For This Employee</p></h4></div>';
        }

        $data['html'] = $html;
        $data['phtml'] = $phtml;
        $data['id'] = $emp_data[0]->employee_type;
        $depart = json_decode($emp_data[0]->department);
        $data['depart'] = $depart[0];

        $allow = [];
        if (count($result_details) > 0) {
            foreach ($result_details as $key => $value) {
                $allow[] = $value->allowance_id;
            }
        }

        $this->data['allow'] = $allow;

        return $data;
    }




    public function employeemanualallowance(Request $request)
    {
        // dd($_POST);
        $edit_id = $request->input('edit_id');
        if ($edit_id == '') {

            $employee_id = $request->input('employee_id');
            $employee_type = $request->input('employee_type');
            $month = $request->input('month');
            $department_id = $request->input('department_id');
            $year = $request->input('year');
            $allow_id = $request->input('allowance_id');
            $allow = $request->input('allowance');
            foreach ($allow_id as $k => $v) {
                $allow_id[$k] = array();
                $allow_id[$k][$v] = $allow[$k];
            }
            $allowance = json_encode($allow_id);

            $company_id = \Session::get('companyid');
            $organization_id = \Session::get('organization');
            $location_id = \Session::get('location');
            $created_by = \Session::get('id');
            $created_at = date('Y-m-d');

            $pay_proposal = DB::table('hr_employee_payproposal_allowance')->insertGetId(array('employee_id' => $employee_id, 'year' => $year, 'department_id' => $department_id, 'month' => $month, 'company_id' => $company_id, 'organization_id' => $organization_id, 'location_id' => $location_id, 'created_by' => $created_by, 'created_at' => $created_at, 'employee_type' => $employee_type, 'allowance' => $allowance));

            $this->auditlog($pay_proposal, "payproposalallowance", "create", $_POST, "hr_employee_payproposal_allowance");
            return 1;
        } else {

            $data_query = DB::table('hr_employee_payproposal')->where('id', $edit_id)->get();

            $employee_id = $request->input('employee_id');
            $edit_id = $request->input('edit_id');
            $payroll_type = $request->input('payroll_type');
            $year = $request->input('year');
            $department_id = $request->input('department_id');
            $month = $request->input('month');
            $employee_type = $request->input('employee_type');

            $allow_id = $request->input('allowance_id');
            $allow = $request->input('allowance');
            foreach ($allow_id as $k => $v) {
                $allow_id[$k] = array();
                $allow_id[$k][$v] = $allow[$k];
            }
            $allowance = json_encode($allow_id);

            $company_id = \Session::get('companyid');
            $organization_id = \Session::get('organization');
            $location_id = \Session::get('location');
            $last_updated_by = \Session::get('id');
            $updated_at = date('Y-m-d');
            $pay_proposal = DB::table('hr_employee_payproposal_allowance')->where('id', $edit_id)->update(array('employee_id' => $employee_id, 'year' => $year, 'department_id' => $department_id, 'month' => $month, 'company_id' => $company_id, 'organization_id' => $organization_id, 'location_id' => $location_id, 'last_updated_by' => $last_updated_by, 'updated_at' => $updated_at, 'employee_type' => $employee_type, 'allowance' => $allowance));
            // auditlog
            $this->auditlog($edit_id, "payproposalallowance", "update", $_POST, "hr_employee_payproposal_allowance");
            return 2;

        }
    }



    public function allowanceindexgriddata()
    {

        $compy = \Session::get('companyid');
        $logged_user = \Session::get('emp_id');

        $wh = " and v1.company_id='$compy'";



        $result = \DB::select("select * from (SELECT 
        hr_employee_payproposal_allowance.id,
        hr_employee_payproposal_allowance.employee_id,
        concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as first_name,
        hr_employee_payproposal_allowance.employee_type,
        (select a_lookuplines_t.lookup_code from a_lookuplines_t where hr_employee_payproposal_allowance.employee_type=a_lookuplines_t.lookuplines_id) as employee_type_name,
        hr_employee_payproposal_allowance.allowance,
        hr_employee_payproposal_allowance.month,
        hr_employee_payproposal_allowance.year,
        hr_employee_payproposal_allowance.department_id,
        hr_employee_payproposal_allowance.company_id,
        (select m_department_lines_t.sub_department_name from m_department_lines_t where m_department_lines_t.department_line_id=hr_employee_payproposal_allowance.department_id) as department_name
        FROM hr_employee_payproposal_allowance 
        LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = hr_employee_payproposal_allowance.employee_id   
        )v1 
        where 1=1  $wh ORDER by v1.id");


        return DataTables::of($result)->make(true);




    }


    public function allowancegetidasjust($id)
    {
        $comp = \Session::get('companyid');
        $emp_data = DB::table("hr_employee_t")->select('hr_employee_t.employee_type', 'hr_employee_t.department')->where('hr_employee_t.employee_id', $id)->get();
        $result_details = DB::table("m_allowance_tbl")
            ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_allowance_tbl.employee_type')
            ->select('m_allowance_tbl.type', 'm_allowance_tbl.allowance_name', 'm_allowance_tbl.allowance_id')
            ->where('m_allowance_tbl.employee_type', $emp_data[0]->employee_type)->where('m_allowance_tbl.active', 'Yes')->where('m_allowance_tbl.company_id', $comp)->orderBy('m_allowance_tbl.type', 'ASC')->get();

        //  $result_details = DB::table("m_allowance_tbl")
        //             ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_allowance_tbl.employee_type')
        //             ->leftjoin('hr_employee_t', 'hr_employee_t.employee_type', '=', 'm_allowance_tbl.employee_type')
        //             ->select('m_allowance_tbl.allowance_name','m_allowance_tbl.allowance_id','hr_employee_t.employee_type')
        //             ->where('hr_employee_t.employee_id',$id)->where('m_allowance_tbl.active','Yes')->where('m_allowance_tbl.company_id',$comp)->get(); 
        $allow = [];
        if (count($result_details) > 0) {
            foreach ($result_details as $key => $value) {

                $allow[] = $value->allowance_id;
            }
        }
        return $allow;
    }



    public function allowancepayproposaldelete(Request $request, $id = null)
    {


        $query_data = DB::table('hr_employee_payproposal_allowance')->where('id', $id)->get();
        $query = DB::table('hr_employee_payproposal_allowance')->where('id', $id)->delete();
        // auditlog
        $this->auditlog($id, "payproposalallowance", "delete", $query_data, "hr_employee_payproposal_allowance");

        return 2;

    }

}
