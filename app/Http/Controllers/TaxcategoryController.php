<?php

namespace App\Http\Controllers;
use App\Taxcategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;


class TaxcategoryController extends Controller
{
    public function __construct()
    {
        $this->data = array(
            'pageModule' => 'Taxcategory',
            'pageUrl' => url('taxcategory')
        );
        $this->data['urlmenu'] = $this->indexs();
        $this->model = new Taxcategory();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageMethod'] = \Request::route()->getname();
    }


    public function getTaxcategoryData($type = null)
    {

        $wh = '';
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $wh .= 'and f_tax_category_t.company_id=' . $compy;

        if ($type != '') {
            $wh .= " and source_type_id='" . $type . "'";
        }


        $SQL = "SELECT 
                        f_tax_category_t.tax_category_id,
                        f_tax_category_t.tax_category_name,
                        f_tax_category_t.description,
                        a_lookuplines_t.lookup_code as tax_location_type,
                        f_tax_category_t.active,
			tb_users.first_name,
			f_tax_category_t.created_by
			FROM f_tax_category_t 
                        left join a_lookuplines_t on(f_tax_category_t.tax_location_type=a_lookuplines_t.lookup_code) 
			left join tb_users on (tb_users.id =f_tax_category_t.created_by)
						where 1=1 $wh";

        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

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

        $this->data['urlname'] = \Request::route()->getName();
        $table = \DB::table('f_tax_category_t')->get();
        $this->data['datas'] = json_encode($table);
        $this->data['active'] = "";
        $this->data['tax_location_type'] = $this->jcustomselectactive('a_lookuplines_t', 'lookup_code', 'lookup_code', '', 'AND lookup_type="TAX_LOCATION_TYPE"');
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));

        return view('taxcategory.form', $this->data);

    }



    public function save(Request $request)
    {

        $edit_id = $request->input('edit_id');
        if ($edit_id == '') {
            $taxcategory = new Taxcategory();
            $taxcategory->tax_category_name = $_POST['tax_category_name'];
            $taxcategory->tax_location_type = $_POST['tax_location_type'];
            $taxcategory->description = $_POST['description'];
            $taxcategory->active = $_POST['active'];
            $taxcategory->company_id = \Session::get('companyid');
            $taxcategory->location_id = \Session::get('location');
            $taxcategory->organization_id = \Session::get('organization');
            $taxcategory->created_by = \Session::get('id');
            $taxcategory->save();
            return response()->json(array('status' => 'success', 'message' => 'Tax Category Saved Successfully!!', 'id' => $edit_id));
        } else {
            $edit_id = $_POST['edit_id'];
            Taxcategory::find($edit_id)->update($_POST);
            return response()->json(array('status' => 'success', 'message' => 'Tax Category Updated Successfully!!', 'id' => $edit_id));
        }
    }
    /*Karthigaa purpose for check Duplicate function*/
    public function getCheckname(Request $request)
    {
        $edit_id = $_GET['edit_id'];
        if ($edit_id == '')
            $acc_class = \DB::table('f_tax_category_t')->where('tax_category_name', $_GET['tax_category_name'])->get();
        else {
            $whereData = [['tax_category_name', $_GET['tax_category_name']], ['tax_category_id', '!=', $edit_id]];
            $acc_class = \DB::table('f_tax_category_t')->where($whereData)->get();
        }
        if (count($acc_class) > 0)
            return 1;
        else
            return 0;
    }
    /*Karthigaa purpose for delete function*/
    public function delete($del_id)
    {
        $column = array('tax_category_id');
        $table = array('f_tax_code_t');
        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $del_id)->get();
            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }

        if ($j == 0) {
            $query = \DB::table('f_tax_category_t')->where('tax_category_id', $del_id)->delete();
        }
        return $j;
    }

    public function getedit($edit_id)
    {

        $column = array('tax_category_id');
        $table = array('f_tax_code_t');

        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $edit_id)->get();
            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }
        return $j;


    }


}
