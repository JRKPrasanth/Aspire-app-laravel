<?php
namespace App\Http\Controllers;
use App\Freightterms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;


class FreighttermsController extends Controller
{

    public function __construct()
    {
        $this->data['pageFormtype'] = 'ajax';
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['urlmenu'] = $this->indexs();
    }
    public function index()
    {

        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageModule'] = \Request::route()->getName();
        $table = \DB::table('m_frieghtterms_t')->get();

        $this->data['datas'] = $table;

        return view('freightterms.form', $this->data);
    }

    // table data
    public function getfreightGridData($type = null)
    {

        $wh = '';

        if ($type != '') {
            $wh .= " and source_type_id='" . $type . "'";
        }


        $com = \Session::get('companyid');

        $groupname = \Session::get('groupname');
        if ($groupname == '1' || $groupname == 'Admin') {
            $wh .= 'and  m_frieghtterms_t.company_id=' . $com;
        }

        $SQL = "SELECT m_frieghtterms_t.*,tb_users.id,tb_users.employee_id,tb_users.first_name,m_location_t.location_name FROM `m_frieghtterms_t` left join m_location_t on m_frieghtterms_t.fob_location=m_location_t.location_id left join tb_users on m_frieghtterms_t.created_by=tb_users.id where  1=1  $wh order by m_frieghtterms_t.frieghtterm_id DESC";

        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);
    }




    // Purpose for Create Function
    public function create(Request $request)
    {
        // restrict illegal menu entry purpose - VIGNESH M

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

        $table = \DB::table('m_frieghtterms_t')->get();

        $this->data['urlname'] = \Request::route()->getName();
        $this->data['active'] = '';

        $this->data['start_date'] = date("d-m-Y");

        if ($this->data['urlname'] == "purchasefreightterms") {
            $this->data['source_type_id'] = "Purchase";
        } else {
            $this->data['source_type_id'] = "Sales";
        }

        $location = \Session::get('location');
        $comp = \Session::get('companyid');
        $company = \DB::table('m_company_line_t')->where('companyid', $comp)->get();

        if (count($company) > 0) {
            $c = '';
            foreach ($company as $k => $y) {
                $c .= $y->locationid . ",";
            }

            $c = rtrim($c, ',');

            $this->data['fob_location'] = $this->jcustomselect('m_location_t', 'location_id', 'location_name', $comp, " and location_id in(" . $c . ")");
        } else {
            $this->data['fob_location'] = $this->jCombologin('m_location_t', 'location_id', 'location_name', '');
        }
        // $this->data['fob_location']=$this->jCombologin('m_location_t','location_id','location_name','');

        $this->data['datas'] = json_encode($table);
        $this->data['pageModule'] = \Request::route()->getName();
        $this->data['created_by'] = $this->jCombo('tb_users', 'id', 'username', \Session::get('id'));

        return view('freightterms.form', $this->data);

    }

    // Purpose For Save Function
    public function save(Request $request)
    {
        $freightterms = new Freightterms();
        $edit_id = $request->input('edit_id');
        $freightterms->fob_point_name = $_POST['fob_point_name'];
        $freightterms->fob_location = $_POST['fob_location'];
        $freightterms->fob_barriers = $_POST['fob_barriers'];
        $freightterms->fob_payment = $_POST['fob_payment'];
        $freightterms->source_type_id = $_POST['source_type_id'];
        $freightterms->created_by = $_POST['created_by'];
        $freightterms->active = $_POST['active'];

        $_POST['organization_id'] = $freightterms->organization_id = \Session::get('organization');
        $_POST['company_id'] = $freightterms->company_id = \Session::get('companyid');
        $_POST['location_id'] = $freightterms->location_id = \Session::get('location');
        if ($edit_id == '') {
            $freightterms->save();
            $id = $freightterms->frieghtterm_id;
            $edit_id = DB::getPdo()->lastInsertId();
            $action = "Create";
            /**Auditlog**/
            $this->auditlog($edit_id, "freightterms", $action, $_POST, "m_frieghtterms_t");
            return response()->json(array('status' => 'success', 'message' => 'Freight Terms Saved Successfully', 'id' => $id));
        } else {
            $action = "Edit";
            Freightterms::find($edit_id)->update($_POST);
            /**Auditlog**/
            $this->auditlog($edit_id, "freightterms", $action, $_POST, "m_frieghtterms_t");
            return response()->json(array('status' => 'success', 'message' => 'Freight Terms Updated Successfully', 'id' => $edit_id));
        }



    }
    /*End*/



    /*Karthigaa Purpose for Used Data Should Not Allow to Edit Function*/
    public function getedit($edit_id, $type)
    {
        if ($type == "Sales") {
            $column = array('frieghtterm_id', 'frieghtterm_id', 'ar_frieghtterm_id', 'frieghtterm_id');
            $table = array('m_customers_t', 's_quote_hdr_t', 's_salesorder_hdr_t', 's_invoice_hdr_t');
        } else {
            $column = array('frieghtterm_id', 'freight_terms_id');
            $table = array('m_supplier_t', 'p_po_hdr_t');
        }
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
    /*End*/


    /*Karthigaa Purpose For Delete Function*/
    public function getRemove(Request $request, $id = null, $type = null)
    {

        if ($type == "Sales") {
            $column = array('frieghtterm_id', 'frieghtterm_id', 'ar_frieghtterm_id', 'frieghtterm_id');
            $table = array('m_customers_t', 's_quote_hdr_t', 's_salesorder_hdr_t', 's_invoice_hdr_t');
        } else {
            $column = array('frieghtterm_id', 'freight_terms_id');
            $table = array('m_supplier_t', 'p_po_hdr_t');
        }
        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $id)->get();
            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }
        if ($j == 0) {
            $query = \DB::table('m_frieghtterms_t')->where('frieghtterm_id', $id)->delete();
        }
        $action = "Delete";
        $this->auditlog($id, "freightterms", $action, $id, "m_frieghtterms_t");
        return $j;


    }

    /*End*/

    /* Edit data*/
    public function getCheckname(Request $request)
    {
        $edit_id = $_GET['edit_id'];

        if ($edit_id == '') {
            $department = \DB::table('m_frieghtterms_t')->where('fob_point_name', $_GET['fob_point_name'])->where('source_type_id', $_GET['source_type_id'])->get();
        } else {
            $whereData = [['fob_point_name', $_GET['fob_point_name']], ['source_type_id', $_GET['source_type_id']], ['frieghtterm_id', '!=', $edit_id]];
            $department = DB::table('m_frieghtterms_t')->where($whereData)->get();
        }

        if (count($department) > 0)
            return 1;
        else
            return 0;
    }




}
