<?php
namespace App\Http\Controllers;

use App\Subinventory;
use App\Sublocators;
use App\Http\Controllers\SoorderController;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;

class SubinventoryController extends Controller
{

    public function __construct()
    {
        $this->data = array();
        $this->data['urlmenu'] = $this->indexs();
        $this->table = "m_subinventory_t";
        $this->subtable = "m_sublocators_t";
        $this->pageModule = "subinventory";
        $this->model = new Subinventory;
        $this->submodel = new Sublocators;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
    }


    public function getinvlocData()
    {
        $wh = '';
        $comp = \Session::get('companyid');
        $loc = \Session::get('location');
        $groupname = \Session::get('groupname');
        if ($groupname == "1" || $groupname == "Admin") {
            $wh .= "and m_subinventory_t.company_id=$comp";
        } else {
            $wh .= "and m_subinventory_t.company_id=$comp  and m_subinventory_t.location_id=$loc";
        }

        $SQL = "select 
		tb_users.first_name,
		m_subinventory_t.subinventory_id,
		m_subinventory_t.subinventory_name,
		m_subinventory_t.description,
		m_subinventory_t.active,
		org.organization_name as organization_id from m_subinventory_t
		left join m_organizations_t org on org.organization_id = m_subinventory_t.organization_id left join `tb_users` on (tb_users.id=m_subinventory_t.created_by) where 1=1 $wh";


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

        $subinvdata = \DB::table('m_subinventory_t')->get();
        $this->data['pageMethod'] = "subinventory";
        return view('subinventory.table', $this->data);
    }

    /*Create Function*/
    public function create($id = null)
    {
        $this->data['pageurl'] = \Request::route()->getName();
        $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
        $this->data['prdgrpopt'] = $this->jqgridselect('m_product_groups_t', 'product_group_id', 'group_name');
        $this->data['prdcatopt'] = $this->jqgridselect('m_product_category_t', 'product_category_id', 'category_name');
        if (isset($id)) {
            $subinvdata = Subinventory::find($id);
            $this->data['datas'] = $subinvdata;

            $company_id = $this->data['datas']->company_id;

            $linestable = \DB::table('m_sublocators_t')->where('subinventory_id', $id)->get();
            $this->data['linedata'] = $linestable;

            foreach ($this->data['linedata'] as $key => $value) {

                $this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'concatenated_product', $value->product_id);
                ;
            }
            $this->data['pageMethod'] = "subinventory";
            return view('subinventory.form', $this->data);

        } else {
            $this->data['datas'] = Subinventory::all();

            $this->data['linedata'] = array();
            $this->data['product_id'] = $this->jCombo('m_products_t', 'product_id', 'concatenated_product', '');
        }

        $this->data['pageMethod'] = "subinventory";
        return view('subinventory.form', $this->data);

    }
    /*End*/

    /*Edit Function*/
    public function getedit($edit_id)
    {
        $column = array('subinventory_id', 'subinventory_id', 'subinventory_id', 'subinventory_id');
        $table = array('m_material_trx_t', 'm_products_t', 'i_qoh_detail_t', 'i_reservation_detail_t');
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

    /*Save Function*/
    public function save(Request $request)
    {
       
        $id = '';
        $form = $request->all();
        $dataupload = "";
        $form = $request->except([
            '_token',
            'form_config',
            'form_data_json',
            'savestatus',
            'submit_type',
            'choosefile',
            'existing_file',
            'enable-masterdetail',
        ]);
        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');
        $data['active'] = $_POST['active'];
        $lines_data = $this->validatePost($form, $this->subtable, 'lines');
       // dd($lines_data);
        \DB::beginTransaction();
        try {

            $id = $this->model->insertRow($data);
            // dd($lines_data);
            $lid = $this->submodel->subgridSave($lines_data, $id);
            $edit_id = DB::getPdo()->lastInsertId();
            $action = "Create";
            /**Auditlog**/
            $this->auditlog($edit_id, "subinventory", $action, $_POST, "m_subinventory_t");
            \DB::commit();

            if ($_POST['subinventory_id'] == '')
                $msg = "Saved ";
            else
                $msg = "Update ";
            return response()->json(array('status' => 'success', 'message' => $msg . 'Successfully', 'id' => $id, 'lid' => $lid));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');

            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }


    /*View Fuction */
    public function view(request $request, $id = null)
    {

        if (isset($id)) {
            $vdata = DB::table('m_subinventory_t')->where('subinventory_id', $id)->select('m_subinventory_t.subinventory_name', 'm_subinventory_t.description', 'm_subinventory_t.active', 'm_subinventory_t.production_store', 'm_subinventory_t.created_by')->get();

            $this->data['subinventory_name'] = $vdata[0]->subinventory_name;
            $this->data['description'] = $vdata[0]->description;
            $this->data['active'] = $vdata[0]->active;
            $this->data['production_store'] = $vdata[0]->production_store;
            $this->data['created_by'] = $this->idname('username', 'tb_users', 'id', $vdata[0]->created_by);

            $a = \DB::table('m_sublocators_t')->where('subinventory_id', $id)->get();

            $vlinesdata = \DB::table('m_sublocators_t')->where('m_sublocators_t.subinventory_id', $id)->get();

            // dd($a );
            $this->data['vlinesdata'] = $vlinesdata;

            $this->data['pageMethod'] = "subinventory";
            return view('subinventory.view', $this->data);

        }


    }
    /*End View*/

    /*deepika purpose: delete records*/
    public function destroy($del_id)
    {

        // $del_id = $_GET['del_id'];
        $column = array('locator_id', 'subinventory_id', 'locator_id', 'subinventory_id', 'sublocator_id', 'subinventory_id');
        $table = array('i_qoh_detail_t', 'i_qoh_detail_t', 'i_reservation_detail_t', 'i_reservation_detail_t', 'm_products_t', 'm_products_t');
        for ($i = 0; $i < count($table); $i++) {
            $j = 0;
            $query = \DB::table($table[$i])->where($column[$i], $del_id)->get();
            // dd($query);
            if (count($query) > 0) {
                $j = 1;
                break;
            }
        }

        if ($j == 0) {
            $query = \DB::table('m_subinventory_t')->where('subinventory_id', $del_id)->delete();
            $query1 = \DB::table('m_sublocators_t')->where('subinventory_id', $del_id)->delete();
            /**Auditlog**/
            $this->auditlog($del_id, "subinventory", "delete", "", "m_subinventory_t");
        }
        return $j;


    }

    /*end*/

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function save1(Request $request)
    {
        //   dd($_POST);
        $subinvdata = new Subinventory();
        $this->modelname = new Subinventory();

        $sublocdata = new Sublocators();
        $this->sublocdtls = new Sublocators();



        $primary = self::findPrimaryKey('m_subinventory_t');
        $primaryline = self::findPrimaryKey('m_sublocators_t');

        $subinvdata->subinventory_name = $_POST['subinventory_name'];
        $subinvdata->description = $_POST['description'];
        $subinvdata->organization_id = $_POST['organization_id'];
        $subinvdata->active = $_POST['active'];
        $subinvdata->created_by = $_POST['created_by'];
        $subinvdata->remarks = $_POST['remarks'];
        $subinvdata->status = $_POST['submit_type'];
        if ($_POST['subinventory_id'] == "") {
            $id = $this->insertData($this->modelname, $primary, $subinvdata, $_POST['subinventory_id']);
        } else {


        }
        $oldid = \DB::table('m_sublocators_t')->where('subinventory_id', $id)->get();
        /************** If already lineitems there for this header *******************/
        if ($oldid->isEmpty()) {
            for ($i = 0; $i < count($_POST['counter']); $i++) {
                //dd($_POST);
                $data['subinventory_id'] = $id;
                $data['sublocator_id'] = $_POST['bulk_sublocator_id'][$i] ? $_POST['bulk_sublocator_id'][$i] : 0;
                $data['line_no'] = 1;
                $data['rack_no'] = $_POST['bulk_rack_no'][$i];
                $data['row_no'] = $_POST['bulk_row_no'][$i];
                $data['bin_no'] = $_POST['bulk_bin_no'][$i];
                $data['locator_code'] = $_POST['bulk_locator_code'][$i];
                $data['locator_name'] = $_POST['bulk_locator_name'][$i];
                $data['product_id'] = $_POST['bulk_product_id'][$i];
                $data['active'] = $_POST['bulk_active'][$i];
                $data['organization_id'] = '';
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');
                \DB::table('m_sublocators_t')->insert($data);

            }

        }
        /************** If already lineitems there for this header End *******************/ else {
            $existingId = array();
            foreach ($oldid as $key => $value) {
                $oldIds[] = $value->$primaryline;
            }

            foreach ($_POST['bulk_' . $primaryline] as $val) {
                $newIds[] = $val;
            }

            $existingId = array_replace($newIds, $oldIds);
            $oldcount = count($oldIds);
            $newcount = count($newIds);

            if ($oldcount <= $newcount) {

                for ($i = 0; $i < $newcount; $i++) {
                    $data1['subinventory_id'] = $id;
                    $data1['sublocator_id'] = $_POST['bulk_sublocator_id'][$i] ? $_POST['bulk_sublocator_id'][$i] : 0;
                    $data1['line_no'] = 1;
                    $data1['rack_no'] = $_POST['bulk_rack_no'][$i];
                    $data1['row_no'] = $_POST['bulk_row_no'][$i];
                    $data1['bin_no'] = $_POST['bulk_bin_no'][$i];
                    $data1['locator_code'] = $_POST['bulk_locator_code'][$i];
                    $data1['locator_name'] = $_POST['bulk_locator_name'][$i];
                    $data1['product_id'] = $_POST['bulk_product_id'][$i];
                    $data1['active'] = $_POST['bulk_active'][$i];
                    $data1['organization_id'] = '';
                    $data1['created_at'] = date('Y-m-d H:i:s');
                    $data1['updated_at'] = date('Y-m-d H:i:s');

                    if ($data1['sublocator_id'] = $existingId[$i]) {
                        //dd($this->sublocdtls);
                        $this->sublocdtls::find($data1['sublocator_id'])->update($data1);
                        //dd('sd');
                    } else {
                        \DB::table('m_sublocators_t')->insert($data1);
                    }
                }

            } else {
                $arraydiff = array_diff($oldIds, $newIds);
                foreach ($arraydiff as $key) {
                    if (($key = array_search($key, $oldIds)) !== false) {
                        unset($oldIds[$key]);
                    }
                }

                foreach ($arraydiff as $val) {
                    \DB::table('m_sublocators_t')->where('sublocator_id', $val)->delete();
                }

                for ($i = 0; $i < count($oldIds); $i++) {
                    $data['subinventory_id'] = $id;
                    $data['sublocator_id'] = $_POST['bulk_sublocator_id'][$i] ? $_POST['bulk_sublocator_id'][$i] : 0;
                    $data['line_no'] = 1;
                    $data['rack_no'] = $_POST['bulk_rack_no'][$i];
                    $data['row_no'] = $_POST['bulk_row_no'][$i];
                    $data['bin_no'] = $_POST['bulk_bin_no'][$i];
                    $data1['active'] = $_POST['bulk_active'][$i];
                    $data['locator_code'] = $_POST['bulk_locator_code'][$i];
                    $data['locator_name'] = $_POST['bulk_locator_name'][$i];
                    $data['product_id'] = $_POST['bulk_product_id'][$i];
                    $data['organization_id'] = '';
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['updated_at'] = date('Y-m-d H:i:s');
                    $this->sublocdtls::find($data['sublocator_id'])->update($data);
                }
            }
        }
        /*********************** LineItems Save End *****************************/


        return response()->json(array('status' => 'success', 'message' => 'Saved Successfully!!', 'id' => $id));


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subinventorylocator  $subinventorylocator
     * @return \Illuminate\Http\Response
     */



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subinventorylocator  $subinventorylocator
     * @return \Illuminate\Http\Response
     */



    /* deepika :purpose for check duplicate entry*/
    public function Checkname(Request $request)
    {
        //dd($request);
        $edit_id = $_REQUEST['edit_id'];
        if ($edit_id == '')
            $subinventory = \DB::table('m_subinventory_t')->where('subinventory_name', $_REQUEST['subinventory_name'])->get();
        else {
            $whereData = [['subinventory_name', $_REQUEST['subinventory_name']], ['subinventory_id', '!=', $edit_id]];

            $subinventory = \DB::table('m_subinventory_t')->where($whereData)->get();
        }


        if (count($subinventory) > 0)
            return 1;
        else
            return 0;


    }
    /*end*/
    function findPrimarykey($table)
    {
        $primaryKey = '';
        foreach (\DB::select("show columns from " . $table . " where extra like '%auto_increment%'") as $key) {
            $primaryKey = $key->Field;
        }
        return $primaryKey;
    }
}
