<?php
namespace App\Http\Controllers;
use App\Machine;
use App\Machinelines;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MachineController extends Controller
{

	public $module = "Machine";
	/* purpose: construct function to set values throughout the controller like model,submodel,pagemethod*/
	public function __construct()
	{
		$this->data = array();
		$this->model = new Machine();
		$this->submodel = new Machinelines();
		$this->data['pageMethod'] = \Request::route()->getName();
		$this->data['pageFormtype'] = 'ajax';
		$this->data['pageModule'] = 'machine';
		$this->table = " w_machine_hdr_t";
		$this->subtable = "w_machine_lines_t";
		$this->middleware('auth');
		$this->data['urlmenu'] = $this->indexs();

	}
	/*end*/

	/* purpose:index function to redirect table blade*/
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

		$this->data['pageMethod'] = "machine";

		return view('machine.table', $this->data);
	}

	/* purpose:to display data in jqgrid function */
	public function getmachineData(Machine $machine)
	{

		$wh = '';

		$loc = "1";
		$compy = \Session::get('companyid');
		$groupname = \Session::get('groupname');


		$sql = "select * from(SELECT
		w_machine_hdr_t.company_id,
		w_machine_hdr_t.machine_hdr_id,
		w_machine_hdr_t.files,
		w_machine_hdr_t.machine_code,
		w_machine_hdr_t.machine_name,
		m_department_lines_t.sub_department_name as department_name,
		w_machine_hdr_t.capacity,
		m_location_t.location_name as location,
		w_machine_hdr_t.relocated_date,
		w_machine_hdr_t.purchased_date,
		w_machine_hdr_t.machine_make,
		w_machine_hdr_t.cost,
		w_machine_hdr_t.active,
		w_machine_hdr_t.renewal_date,
		w_machine_hdr_t.from_date,
		w_machine_hdr_t.to_date,
		w_machine_hdr_t.remarks,
		w_machine_hdr_t.created_by,
		vendor.vendor_name as vendorname,
		tb_users.first_name,
		amc.vendor_name as amcvendor
		FROM `w_machine_hdr_t`
		left join m_department_lines_t on(
		m_department_lines_t.department_line_id=w_machine_hdr_t.`department_id`)
		 left join m_amc_tb as vendor on(vendor.vendor_id=w_machine_hdr_t.vendor_id)
		  left join tb_users on(tb_users.id=w_machine_hdr_t.created_by)
		  left join m_location_t on(m_location_t.location_id=w_machine_hdr_t.location_id)
		 left join m_amc_tb as amc on(amc.vendor_id=w_machine_hdr_t.amc_vendor_id )order by w_machine_hdr_t.machine_hdr_id desc)v1 where 1=1 $wh ORDER BY v1.machine_hdr_id DESC";

		$result = \DB::select($sql);

		return DataTables::of($result)->make(true);
	}


	/*** Show the form for creating a new data.*/
	public function create($id = null)
	{
		/*deepika purpose:get product type based on fg&sfg*/
		$prd = \DB::table('m_products_t')->leftjoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')->whereIn('group_name', ['FINISHED GOODS', 'SEMI FINISHED GOODS'])->get();
		$prdtype = "";
		foreach ($prd as $key => $val) {
			$prdtype .= $val->product_type_id . ",";
		}
		$prdtypes = rtrim($prdtype, ",");
		/*end*/
		if (isset($id)) {
			$this->data['id'] = $id;
			$this->data['editcheck'] = $this->editcheck($id);


			$table = \DB::table('w_machine_hdr_t')->where('machine_hdr_id', $id)->get();
			$this->data['row'] = $table[0];

			$data = $table[0];
			$linestable = \DB::table('w_machine_lines_t')->leftjoin('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'w_machine_lines_t.machine_hdr_id')->where('w_machine_hdr_t.machine_hdr_id', $id)->get();


			$this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $table[0]->created_by);
			//add machine
			$linestable = \DB::table('w_machine_lines_t')->leftjoin('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'w_machine_lines_t.machine_hdr_id')->where('w_machine_hdr_t.machine_hdr_id', $id)->get();


			$this->data['linedata'] = $linestable;
			if (count($this->data['linedata']) > 0) {


				foreach ($this->data['linedata'] as $key => $value) {


					//dd($value->product_type_id);
					$this->data['linedata'][$key]->product_type_id = $this->jcustomselect('m_product_type_t', 'product_type_id', 'product_type', $value->product_type_id, '');


					$this->data['linedata'][$key]->frequency_date = ($this->data['linedata'][$key]->frequency_date != '0000-00-00') ? date("d-m-Y", strtotime($value->frequency_date)) : $this->data['linedata'][$key]->frequency_date;

					$this->data['linedata'][$key]->frequency_id = $this->jcustomselect('frequency_tbl', 'frequency_name', 'frequency_name', $value->frequency_id, '');


				}

			} else {
				$this->data['frequency_id'] = $this->jCombo('frequency_tbl', 'frequency_name', 'frequency_name', '');

			}


			$this->data['locationid'] = $this->jCombo('m_location_t', 'location_id', 'location_name', $table[0]->locationid);

			$this->data['department_id'] = $this->jcustomselect('m_department_lines_t', 'department_line_id', 'sub_department_code|sub_department_name', $table[0]->department_id, " and sub_department_code LIKE '%c010%'");
			$this->data['vendor_id'] = $this->jCombo('m_amc_tb', 'vendor_id', 'vendor_name', $table[0]->vendor_id);
			$this->data['amc_vendor_id'] = $this->jCombo('m_amc_tb', 'vendor_id', 'vendor_name', $table[0]->vendor_id);

			$this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));

			$this->data['assigned_to'] = $this->jcustommultiselect1('hr_employee_t', 'employee_id', 'employee_number|first_name', $value->assigned_to, " and department LIKE '%53%' and department NOT LIKE '%95%' and active='Yes'");

		} else {
			$this->modelname = new Machine();
			$this->data['row'] = (object) array();
			$table = $this->modelname->getTableColumns();
			foreach ($table as $key => $val) {
				$this->data['row']->$val = '';
			}
			$this->data['row']->relocated_date = date('Y-m-d');
			$this->data['row']->purchased_date = date('Y-m-d');
			$this->data['row']->renewal_date = date('Y-m-d');
			$this->data['row']->from_date = date('Y-m-d');
			$this->data['row']->to_date = date('Y-m-d');

			$this->data['id'] = '';
			$this->data['editcheck'] = "";

			$this->data['product_type_id'] = $this->jcustomselect('m_product_type_t', 'product_type_id', 'product_type', "", ' and product_type_id in(' . $prdtypes . ')');
			$this->data['assigned_to'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'employee_number|first_name', "", " and department LIKE '%53%' and department NOT LIKE '%95%'");
			$this->data['count'] = 0;
			$this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));
			$this->data['linedata'] = array();

			$this->data['department_id'] = $this->jcustomselect('m_department_lines_t', 'department_line_id', 'sub_department_code|sub_department_name', "", " and sub_department_code LIKE '%c010%'");
			$this->data['vendor_id'] = $this->jCombo('m_amc_tb', 'vendor_id', 'vendor_name', '');
			$this->data['locationid'] = $this->jCombo('m_location_t', 'location_id', 'location_name', '');
			$this->data['amc_vendor_id'] = $this->jCombo('m_amc_tb', 'vendor_id', 'vendor_name', '');
			$this->data['frequency_id'] = $this->jCombo('frequency_tbl', 'frequency_name', 'frequency_name', '');

		}
		//dd($this->data);
		return view('machine.form', $this->data);
	}

	/** Store a newly created data & update data in db  */
public function save(Request $request)
{
    $machine = new Machine();
    $this->modelname = new Machine();

    $primary     = self::findPrimarykey('w_machine_hdr_t');
    $primaryline = self::findPrimarykey('w_machine_lines_t');

    // ===================== SAVE HEADER =====================
    if (!empty($_POST['machine_hdr_id'])) {

        $machine1['machine_hdr_id'] = $_POST['machine_hdr_id'];
        $machine1['machine_code']   = $_POST['machine_code'];
        $machine1['machine_name']   = $_POST['machine_name'];

        $assigned_to = isset($_POST['assigned_to']) ? implode(",", $_POST['assigned_to']) : '';
        $machine1['assigned_to'] = $assigned_to;

        $machine1['remarks']          = $_POST['remarks'] ?? '';
        $machine1['electricity_cost'] = $_POST['electricity_cost'] ?? null;
        $machine1['capacity']         = $_POST['capacity'] ?? null;
        $machine1['department_id']    = $_POST['department_id'] ?? null;
        $machine1['active']           = $_POST['active'] ?? null;
        $machine1['locationid']       = $_POST['locationid'] ?? null;

        $machine1['relocated_date'] = !empty($_POST['relocated_date']) ? date('Y-m-d', strtotime($_POST['relocated_date'])) : null;
        $machine1['purchased_date'] = !empty($_POST['purchased_date']) ? date('Y-m-d', strtotime($_POST['purchased_date'])) : null;

        $machine1['machine_make']  = $_POST['machine_make'] ?? '';
        $machine1['cost']          = $_POST['machine_cost'] ?? null;
        $machine1['vendor_id']     = $_POST['vendor_id'] ?? null;
        $machine1['amc_vendor_id'] = $_POST['amc_vendor_id'] ?? null;

        $machine1['from_date']    = !empty($_POST['from_date']) ? date('Y-m-d', strtotime($_POST['from_date'])) : null;
        $machine1['to_date']      = !empty($_POST['to_date']) ? date('Y-m-d', strtotime($_POST['to_date'])) : null;
        $machine1['renewal_date'] = !empty($_POST['renewal_date']) ? date('Y-m-d', strtotime($_POST['renewal_date'])) : null;

        $machine1['asset_code']  = $_POST['asset_code'] ?? '';
        $machine1['critical']    = $_POST['critical'] ?? '';

        $machine1['organization_id']   = \Session::get('organization');
        $machine1['company_id']        = \Session::get('companyid');
        $machine1['location_id']       = \Session::get('location');
        $machine1['created_by']        = \Session::get('id');
        $machine1['last_updated_by']   = \Session::get('id');

        $id = $this->insertData1($this->modelname, $primary, $machine1, $_POST['machine_hdr_id'], 'machine');

    } else {

        $machine->machine_hdr_id = $_POST['machine_hdr_id'];
        $machine->machine_code   = $_POST['machine_code'];
        $machine->machine_name   = $_POST['machine_name'];

        $assigned_to = isset($_POST['assigned_to']) ? implode(",", $_POST['assigned_to']) : '';
        $machine->assigned_to = $assigned_to;

        $machine->remarks          = $_POST['remarks'] ?? '';
        $machine->electricity_cost = $_POST['electricity_cost'] ?? null;
        $machine->capacity         = $_POST['capacity'] ?? null;
        $machine->department_id    = $_POST['department_id'] ?? null;
        $machine->active           = $_POST['active'] ?? null;
        $machine->locationid       = $_POST['locationid'] ?? null;

        $machine->relocated_date = !empty($_POST['relocated_date']) ? date('Y-m-d', strtotime($_POST['relocated_date'])) : null;
        $machine->purchased_date = !empty($_POST['purchased_date']) ? date('Y-m-d', strtotime($_POST['purchased_date'])) : null;

        $machine->machine_make  = $_POST['machine_make'] ?? '';
        $machine->cost          = $_POST['machine_cost'] ?? null;
        $machine->vendor_id     = $_POST['vendor_id'] ?? null;
        $machine->amc_vendor_id = $_POST['amc_vendor_id'] ?? null;

        $machine->from_date    = !empty($_POST['from_date']) ? date('Y-m-d', strtotime($_POST['from_date'])) : null;
        $machine->to_date      = !empty($_POST['to_date']) ? date('Y-m-d', strtotime($_POST['to_date'])) : null;
        $machine->renewal_date = !empty($_POST['renewal_date']) ? date('Y-m-d', strtotime($_POST['renewal_date'])) : null;

        $machine->asset_code = $_POST['asset_code'] ?? '';
        $machine->critical   = $_POST['critical'] ?? '';

        $machine->organization_id = \Session::get('organization');
        $machine->company_id      = \Session::get('companyid');
        $machine->location_id     = \Session::get('location');
        $machine->created_by      = \Session::get('id');
        $machine->last_updated_by = \Session::get('id');

        $id = $this->insertData1($this->modelname, $primary, $machine, $_POST['machine_hdr_id'], 'machine');
    }

    // ===================== SAVE LINES (TAB 1) =====================
    $lineIds   = $request->input('bulk_machine_line_id', []);
    $ptypeIds  = $request->input('bulk_product_type_id', []);
    $caps      = $request->input('bulk_machine_capacity', []);

    $max1 = max(count($lineIds), count($ptypeIds), count($caps));

    for ($i = 0; $i < $max1; $i++) {
        $line_id   = $lineIds[$i]  ?? null;
        $ptype_id  = $ptypeIds[$i] ?? null;
        $capacity  = $caps[$i]     ?? null;

        // skip empty rows
        if (empty($ptype_id) && empty($capacity)) {
            continue;
        }

        $row = [
            'machine_hdr_id'     => $id,
            'product_type_id'    => $ptype_id,
            'machine_capacity'   => $capacity,
            'created_by'         => \Session::get('id'),
            'last_updated_by'    => \Session::get('id'),
            'organization_id'    => \Session::get('organization'),
            'company_id'         => \Session::get('companyid'),
            'location_id'        => \Session::get('location'),
            'updated_at'         => now(),
        ];

        if (!empty($line_id)) {
            // update existing
            \DB::table('w_machine_lines_t')
                ->where('machine_line_id', $line_id)
                ->update($row);
        } else {
            // insert new
            $row['created_at'] = now();
            \DB::table('w_machine_lines_t')->insert($row);
        }
    }

    // so we update those by machine_line_id_pm.
    $pmLineIds = $request->input('bulk_machine_line_id_pm', []);
    $freqIds   = $request->input('bulk_frequency_id', []);
    $freqDates = $request->input('bulk_frequency_date', []);

    $max2 = max(count($pmLineIds), count($freqIds), count($freqDates));

    for ($i = 0; $i < $max2; $i++) {
        $line_id = $pmLineIds[$i] ?? null;
        $freq_id = $freqIds[$i]   ?? null;
        $fdate   = $freqDates[$i] ?? null;

        // skip empty rows
        if (empty($freq_id) && empty($fdate)) {
            continue;
        }

        // normalize date (from dd-mm-yyyy to yyyy-mm-dd)
        $fdate_db = null;
        if (!empty($fdate)) {
            $fdate_db = date('Y-m-d', strtotime($fdate));
        }

        // If line_id exists, update same line row
        if (!empty($line_id)) {
            \DB::table('w_machine_lines_t')
                ->where('machine_line_id', $line_id)
                ->update([
                    'frequency_id'      => $freq_id,
                    'frequency_date'    => $fdate_db,
                    'last_updated_by'   => \Session::get('id'),
                    'updated_at'        => now(),
                ]);
        } else {
            // If you allow PM rows without product rows, insert a new line row:
            \DB::table('w_machine_lines_t')->insert([
                'machine_hdr_id'     => $id,
                'frequency_id'       => $freq_id,
                'frequency_date'     => $fdate_db,
                'created_by'         => \Session::get('id'),
                'last_updated_by'    => \Session::get('id'),
                'organization_id'    => \Session::get('organization'),
                'company_id'         => \Session::get('companyid'),
                'location_id'        => \Session::get('location'),
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }
    }

    // ===================== MACHINE PM DETAIL INSERT (your old code) =====================
    // (fixed the SQL string concat)
    if (isset($_POST['bulk_frequency_id'])) {
        foreach ($_POST['bulk_frequency_id'] as $k => $v) {

            $sql = \DB::select("select * from machine_pm_detail_t where machine_id='$id' and frequency_id='$v'");
            if (count($sql) == 0) {

                $bulk_frequency_date = !empty($_POST['bulk_frequency_date'][$k])
                    ? date("Y-m-d", strtotime($_POST['bulk_frequency_date'][$k]))
                    : null;

                if (empty($bulk_frequency_date)) {
                    continue;
                }

                if ($v == "Daily" || $v == "DAILY") {
                    $data1['actual_pm_date'] = date('Y-m-d', strtotime('+1 day', strtotime($bulk_frequency_date)));
                } else if ($v == "Weekly" || $v == "WEEKLY") {
                    $data1['actual_pm_date'] = date('Y-m-d', strtotime('+7 day', strtotime($bulk_frequency_date)));
                } else if ($v == "Monthly" || $v == "MONTHLY" || $v == "temporary" || $v == "Need Basis" || $v == "NEED BASIS") {
                    $data1['actual_pm_date'] = date('Y-m-d', strtotime('+1 month', strtotime($bulk_frequency_date)));
                } else if ($v == "Annual" || $v == "ANNUAL") {
                    $data1['actual_pm_date'] = date('Y-m-d', strtotime('+1 year', strtotime($bulk_frequency_date)));
                } else if ($v == "Quarterly" || $v == "QUARTERLY") {
                    $data1['actual_pm_date'] = date('Y-m-d', strtotime('+3 month', strtotime($bulk_frequency_date)));
                } else {
                    $data1['actual_pm_date'] = date('Y-m-d', strtotime('+6 month', strtotime($bulk_frequency_date)));
                }

                $seqno = $this->Seqnoe('PM-', 'machine_pm_detail_t', '', 'pm_count');
                $data1['pm_no']      = $seqno[0];
                $data1['pm_count']   = $seqno[1];
                $data1['machine_id'] = $id;
                $data1['department_id'] = $_POST['department_id'];
                $data1['frequency_id']  = $v;

                if ($data1['actual_pm_date'] != '1970-01-01') {
                    \DB::table('machine_pm_detail_t')->insert($data1);
                }
            }
        }
    }

    // ===================== RESPONSE =====================
    if (empty($_POST['machine_hdr_id'])) {
        $msg = 'Machine Details Saved Successfully';
        $mac = $machine->machine_code;
    } else {
        $msg = 'Machine Details Updated Successfully';
        $mac = $machine1['machine_code'];
    }

    return response()->json([
        'status' => 'success',
        'message' => $msg,
        'id' => $id,
        'machine_code' => $mac
    ]);
}




	public function show(Machine $machine, $id = null)
	{
		$machinedata = Machine::find($id);
		$this->data['machine_code'] = $machinedata['machine_code'];
		$this->data['machine_name'] = $machinedata['machine_name'];
		$this->data['electricity_cost'] = $machinedata['electricity_cost'];
		$this->data['remarks'] = $machinedata['remarks'];
		$this->data['capacity'] = $machinedata['capacity'];
		$this->data['created_by'] = $this->idname("username", "tb_users", "id", $machinedata['created_by']);/* idname->to display name based on id,table ref controller for idname*/
		$assigned = explode(",", $machinedata['assigned_to']);
		$assingnedto = "";
		foreach ($assigned as $key1 => $value) {
			$assingnedto .= $this->idname("employee_number|first_name", "hr_employee_t", "employee_id", $value) . ",";
		}
		$this->data['assigned_name'] = rtrim($assingnedto, ",");
		$this->data['organization_id'] = $this->idname("organization_name", "m_organizations_t", "organization_id", $machinedata['organization_id']);
		$tablelines = \DB::table('w_machine_lines_t')->where('machine_hdr_id', $id)->get();
		$this->data['linesdata'] = $tablelines;
		foreach ($this->data['linesdata'] as $key => $value) {
			$this->data['linesdata'][$key]->product_type_name = $this->idname("product_type", "m_product_type_t", "product_type_id", $value->product_type_id);

		}
		return view("machine.view", $this->data);
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
	/*deepika purpose:to check whether data used in anywhere and if used it should be read only*/
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

	/*deepika purpose:to primary key in table*/
	function findPrimarykey($table)
	{
		$primaryKey = '';
		foreach (\DB::select("show columns from " . $table . " where extra like '%auto_increment%'") as $key) {
			$primaryKey = $key->Field;
		}
		return $primaryKey;
	}
	/*end*/
	/*deepika purpose:to check duplicate name*/
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
}
