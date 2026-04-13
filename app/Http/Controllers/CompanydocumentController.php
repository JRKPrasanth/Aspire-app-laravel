<?php

namespace App\Http\Controllers;
use yajra\datatables\datatables;
use App\Companydocument;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CompanydocumentController extends Controller
{


    public function __construct()
    {
        $this->data = array();
        $this->model = new Companydocument();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['urlmenu'] = $this->indexs();
    }
    /** company document form index function start **/
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

        $table = \DB::table('m_company_check_list')->get();
        $this->data['datas'] = $table;

        return view('companydocument.form', $this->data);
    }
    /** company document form index function end **/

    /** company document form save function start **/
    public function save(Request $request)
    {

        $edit_id = $request->input('edit_id');
        if ($edit_id == '') {

            $position = new Companydocument();
            $position->document = $_POST['document'];
            $position->active = $request->input('active');
            $position->save();



            $name = $position->getKeyName();
            $id = $position->$name;
            $table = $position->getTable();
            $column = $position->getKeyName();
            $this->hrmssaveinsert($table, $column, $id, 1);
            // auditlog
            $this->auditlog($id, "companydocument", "create", $_POST, "m_company_check_list");
            return 1;
        } else {
            $position = new Companydocument();
            $edit_id = $_POST['edit_id'];
            Companydocument::find($edit_id)->update($_POST);
            $table = $position->getTable();
            $column = $position->getKeyName();
            $this->hrmssaveinsert($table, $column, $edit_id, 2);
            // auditlog
            $this->auditlog($edit_id, "companydocument", "edit", $_POST, "m_company_check_list");
            return 2;
        }
    }

    public function getRemove(Request $request, $id = null)
    {

        $j = 0;
        if ($j == 0) {
            $query = DB::table('m_company_check_list')->where('id', $id)->delete();
            // auditlog
            $this->auditlog($id, "companydocument", "delete", '', "m_company_check_list");
        }

        if ($j == 1)
            return 1;
        else if ($j == 0)
            return 2;
        else if ($j == 3)
            return 3;
    }
    /** company document form remove function end **/
    /** company document name duplicate function start **/
    public function getCheckname(Request $request)
    {
        $edit_id = $_GET['edit_id'];
        if ($edit_id == '') {
            $query = DB::table('m_company_check_list')->where('document', $_GET['cmp_document'])->get();
        } else {
            $whereData = [['document', $_GET['cmp_document']], ['id', '!=', $edit_id]];
            $query = DB::table('m_company_check_list')->where($whereData)->get();
        }

        if (count($query) > 0)
            return 1;
        else
            return 0;
    }
    /** company document name duplicate function end **/


    public function companydocumentgrid(Request $request)
    {

        if ($request->ajax()) {
            $data = \DB::table('m_company_check_list')
                ->select(['*']);

            return DataTables::of($data)->make(true);
        }

    }

}
