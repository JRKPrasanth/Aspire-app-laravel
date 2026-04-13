<?php

namespace App\Http\Controllers;

use Yajra\DataTables\DataTables;
use App\Accountclass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class AccountclassController extends Controller
{
    public function __construct()
    {
        $this->data = [
            'pageModule' => 'Accountclass',
            'pageUrl'    => url('accountclass'),
        ];

        $this->data['urlmenu'] = $this->indexs();
        $this->model = new Accountclass();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageMethod'] = \Request::route()->getName();
    }

	
    public function getAccountclassData(Request $request)
    {
		
		$wh='';

		$compy=\Session::get('companyid');


		$SQL = "SELECT f_account_class_t.account_class_id,f_account_class_t.account_class_name,
		f_account_class_t.main_account_code,f_account_class_t.description,f_account_class_t.active,tb_users.first_name
		FROM f_account_class_t left join tb_users on tb_users.id =f_account_class_t.created_by where 1=1 and
		f_account_class_t.company_id=$compy $wh";


		$result = \DB::select($SQL);
		return DataTables::of($result)->make(true);
		
	}
	
    public function create(Request $request, $id = null)
    {
        // Restrict illegal menu entry
        $url = $request->path();
        $Controller = new Controller();
        $access = $Controller->Accessdined();
        $userAccess = json_decode($access, true);

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

        $id = $request->input('id', $id);

        if (!empty($id)) {
            $acc_classs = DB::table('f_account_class_t')
                ->where('account_class_id', $id)
                ->first();

            if (!$acc_classs) {
                $acc_class = \DB::connection()->getSchemaBuilder()->getColumnListing('f_account_class_t');
                $acc_classs = (object)array();
                foreach ($acc_class as $key => $value) {
                    $acc_classs->$value = "";
                }
            }
        } else {
            $acc_class = \DB::connection()->getSchemaBuilder()->getColumnListing('f_account_class_t');
            $acc_classs = (object)array();
            foreach ($acc_class as $key => $value) {
                $acc_classs->$value = "";
            }
        }

        $this->data['row'] = $acc_classs;
        $user = \Session::get('id');
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', $user);

        return view('accountclass.form', $this->data);
    }

public function save(Request $request)
{
    $edit_id = $request->input('edit_id');
    $org = session('organization');
    $loc = session('location');
    $compy = session('companyid');

    // Common data
    $data = [
        'account_class_name' => $request->input('account_class_name'),
        'main_account_code' => $request->input('main_account_code'),
        'description' => $request->input('description'),
        'code_startwith' => $request->input('code_startwith', ''), // force empty string if null
        'active' => $request->input('active'),
        'last_updated_by' => session('id'),
    ];

    if (empty($edit_id)) {
        // Insert new
        $data['created_by'] = session('id');
        $data['company_id'] = $compy;
        $data['organization_id'] = $org;
        $data['location_id'] = $loc;

        $accountclass = Accountclass::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Account Class Saved Successfully!!',
            'id' => $accountclass->account_class_id
        ]);
    } else {
        // Update existing
        Accountclass::where('account_class_id', $edit_id)->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Account Class Updated Successfully!!',
            'id' => $edit_id
        ]);
    }
}


    public function getCheckname(Request $request)
    {
        $edit_id = $request->input('edit_id');
        $account_class_name = $request->input('account_class_name');

        if (empty($edit_id)) {
            $acc_class = DB::table('f_account_class_t')
                ->where('account_class_name', $account_class_name)
                ->get();
        } else {
            $acc_class = DB::table('f_account_class_t')
                ->where('account_class_name', $account_class_name)
                ->where('account_class_id', '!=', $edit_id)
                ->get();
        }

        return count($acc_class) > 0 ? 1 : 0;
    }

    public function delete($del_id)
    {
        // Check references first
        $query = DB::table('f_account_codes_lines_t')
            ->where('account_class_id', $del_id)
            ->get();

        if (count($query) > 0) {
            return 1; // Cannot delete → used somewhere
        }

        DB::table('f_account_class_t')
            ->where('account_class_id', $del_id)
            ->delete();

        return 0; // Deleted
    }
}