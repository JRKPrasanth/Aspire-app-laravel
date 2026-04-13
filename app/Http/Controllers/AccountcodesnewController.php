<?php

namespace App\Http\Controllers;

use App\Accountcodes;
use App\Accountcodeslines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AccountcodesnewController extends Controller
{
    public function __construct()
    {
        $this->data = array();
        $this->table = "f_account_codes_hdr_t";
        $this->subtable = "f_account_codes_lines_t";
        $this->pageModule = "accountcodesnew";
        $this->model = new Accountcodes;
        $this->submodel = new Accountcodeslines;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageUrl'] = url('accountcodesnew');
        $this->data['urlmenu'] = $this->indexs(); // if you have this method globally
    }

    public function index(Request $request)
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

        // Add pageMethod so header.blade.php works
        $this->data['pageMethod'] = \Request::route()->getName();

        $accountClasses = DB::table('f_account_class_t')->get();
        $this->data['accountClasses'] = $accountClasses;

        return view('accountcodesnew.index', $this->data);
    }

    public function getAccountcodesnewData(Request $request)
    {

        $wh = '';
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $wh .= 'and f_account_class_t.company_id=' . $compy;

        $SQL = "SELECT * FROM f_account_class_t where 1=1 $wh ";

        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);
    }

    public function detailcreate($classid = null, $acchdr = null)
    {

        $data = $this->model::find($classid);
        $accountclass = \DB::table('f_account_class_t')->select('f_account_class_t.*')->where('f_account_class_t.account_class_id', '=', $classid)->get();
        $accountclassid = $accountclass[0]->account_class_id;
        $this->data['main_account_code'] = $accountclass[0]->main_account_code;
        $this->data['account_class_id'] = $accountclass[0]->account_class_id;
        $this->data['account_class_name'] = $accountclass[0]->account_class_name;
        $this->data['datas'] = $data;
        $this->data['id'] = $classid;
        $linedata = \DB::table('f_account_codes_lines_t')->where('account_class_id', $accountclassid)->get();
        $this->data['linedata'] = array();

        /**Tree Menu Structure*/
        $query1 = \DB::select("SELECT f_account_codes_lines_t.* FROM `f_account_class_t` JOIN f_account_codes_lines_t ON
    (f_account_codes_lines_t.account_class_id=f_account_class_t.account_class_id) WHERE
    f_account_class_t.account_class_id='$classid'");
        $query2 = \DB::select("SELECT f_account_codes_lines_t.* FROM `f_account_class_t` JOIN f_account_codes_lines_t ON
    f_account_codes_lines_t.account_class_id=f_account_class_t.account_class_id and
    f_account_codes_lines_t.parent_class_id='0' WHERE f_account_class_t.account_class_id='$classid'");
        $menus = [];
        foreach ($query1 as $key => $value) {
            $menus['items'][$value->account_codes_line_id] = $value; // Creates list of all items with children
            $menus['parents'][$value->parent_class_id][] = $value->account_codes_line_id;
        }
        if ($menus) {
            $this->data['tree_menu'] = $this->createTreeView(0, $menus);
        } else {
            $this->data['tree_menu'] = '';
        }

        $this->data['sub_menu'] = $query2;

        return view('accountcodesnew.form', $this->data);

    }

    public function save(Request $request)
    {
        DB::beginTransaction();

        try {
            $org = session('organization');
            $loc = session('location');
            $compy = session('companyid');
            $userid = session('id');

            $data = [
                'account_class_id' => $request->input('account_class_id'),
                'active' => $request->input('active'),
                'company_id' => $compy,
                'organization_id' => $org,
                'location_id' => $loc,
                'created_by' => $userid,
                'last_updated_by' => $userid,
                'created_at' => now(),
                'updated_at' => now()
            ];

            if ($request->filled('account_codes_hdr_id')) {
                // Update
                DB::table('f_account_codes_hdr_t')
                    ->where('account_codes_hdr_id', $request->input('account_codes_hdr_id'))
                    ->update($data);

                $id = $request->input('account_codes_hdr_id');
                $msg = 'Account Codes Updated Successfully!';
            } else {
                // Insert
                $id = DB::table('f_account_codes_hdr_t')->insertGetId($data);
                $msg = 'Account Codes Saved Successfully!';
            }

            DB::commit();

            return response()->json(['status' => 'success', 'message' => $msg, 'id' => $id]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['status' => 'error', 'message' => 'Error occurred: ' . $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            // Optionally check if lines exist before delete
            $linesCount = DB::table('f_account_codes_lines_t')->where('account_codes_hdr_id', $id)->count();

            if ($linesCount > 0) {
                return response()->json(['status' => 'error', 'message' => 'Cannot delete. Lines exist for this header.']);
            }

            DB::table('f_account_codes_hdr_t')->where('account_codes_hdr_id', $id)->delete();

            return response()->json(['status' => 'success', 'message' => 'Deleted Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function getCheckname(Request $request)
    {
        $account_class_id = $request->input('account_class_id');
        $edit_id = $request->input('account_codes_hdr_id');

        $query = DB::table('f_account_codes_hdr_t')
            ->where('account_class_id', $account_class_id);

        if ($edit_id) {
            $query->where('account_codes_hdr_id', '!=', $edit_id);
        }

        $exists = $query->exists();

        return response()->json($exists ? 1 : 0);
    }

    function createTreeView($parent, $menu)
    {
        //         dd($menu);
        $html = "";
        if (isset($menu['parents'][$parent])) {
            $html .= "<ol class='tree' id='expList'>";
            foreach ($menu['parents'][$parent] as $itemId) {

                if (!isset($menu['parents'][$itemId])) {
                    $id = $menu['items'][$itemId]->account_codes_line_id;
                    $html .= "<li class='dir'><a href='javascript:void(0);' id='subacc_btn' class='list-group-item subacc_btn'  data-id='$id'>" . $menu['items'][$itemId]->account_code . "(" . $menu['items'][$itemId]->account_code_meaning . ")</a></li>";
                }
                if (isset($menu['parents'][$itemId])) {
                    $id = $menu['items'][$itemId]->account_codes_line_id;
                    $html .= "<li class='dir'><a href='javascript:void(0);' id='subacc_btn' class='subacc_btn' data-id='$id'>" . $menu['items'][$itemId]->account_code . "(" . $menu['items'][$itemId]->account_code_meaning . ")</a></li>";
                    $html .= $this->createTreeView($itemId, $menu);
                    $html .= "</li>";
                }
            }
            $html .= "</ol>";
        }
        return $html;
    }

    public function subaccountcode($accclass_id = null)
    {
        $data['query'] = $query1 = \DB::select("SELECT * FROM `f_account_codes_lines_t`  WHERE account_codes_line_id='$accclass_id'");
        $data['sub'] = \DB::select("SELECT * FROM `f_account_codes_lines_t`  WHERE parent_class_id='$accclass_id'");
        return $data;
    }

}