<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\distributormapping;
use App\distributormappinglines;
use Illuminate\Http\Request;
use DB;
class DistributormappingController extends Controller
{
    public function __construct()
    {
        $this->data = array();
        $this->model = new distributormapping();
        $this->submodel = new distributormappinglines();
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageModule'] = "distributorbeatmapping";
        $this->data['urlmenu'] = $this->indexs();
        $this->table = 'distributormapping_hdr_tbl';
        $this->subtable = 'distributormapping_lines_tbl';
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

        return view('distributormapping.index', $this->data);
    }

    public function outletindex()
    {
        return view('distributormapping.index', $this->data);
    }

    public function indexbeat()
    {
        return view('distributormapping.indexbeat', $this->data);
    }

    public function branchmappingreportgrid()
    {
        $comp = \Session::get('companyid');
        $wh = '';
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $search_tables = ["branchmapping_hdr_tbl", "hr_employee_t", "m_states_t", "m_cities_t", "tb_users"];
        if ($_GET['_search'] == 'true') {
            $wh = $this->jqgridsearch("branchmapping_hdr_tbl", $_GET['filters'], $search_tables);
        }
        if (!$sidx)
            $sidx = 1;
        $result = \DB::select("SELECT count(branchmapping_hdr_tbl.branchmapping_id) as count from branchmapping_lines_tbl left join  branchmapping_hdr_tbl on branchmapping_hdr_tbl.branchmapping_id=branchmapping_lines_tbl.branchmapping_id left JOIN hr_employee_t on hr_employee_t.employee_id=branchmapping_hdr_tbl.employee_id  left JOIN m_cities_t on hr_employee_t.city_id=m_cities_t.city_id  left JOIN m_states_t on hr_employee_t.state_id=m_states_t.state_id  left JOIN tb_users on tb_users.id=branchmapping_hdr_tbl.created_by left join branch_tbl on branch_tbl.branch_id=branchmapping_lines_tbl.branch_id where 1=1 and branchmapping_hdr_tbl.company_id=$comp $wh");

        $count = $result[0]->count;
        if ($count > 0 && $limit > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }

        if ($page > $total_pages)
            $page = $total_pages;
        $start = $limit * $page - $limit;
        if ($start < 0)
            $start = 0;

        $download_SQL = "SELECT branch_tbl.warhouse_name,
                    tb_users.username,
                    tb_users.first_name,
                    branchmapping_hdr_tbl.*,
                    CONCAT(hr_employee_t.employee_number,'-',hr_employee_t.first_name)  as first_name,
                    m_states_t.state_name,m_cities_t.city_name 
                    from branchmapping_lines_tbl 
                    left join  branchmapping_hdr_tbl on branchmapping_hdr_tbl.branchmapping_id=branchmapping_lines_tbl.branchmapping_id 
                    left JOIN hr_employee_t on hr_employee_t.employee_id=branchmapping_hdr_tbl.employee_id  
                    left JOIN m_cities_t on hr_employee_t.city_id=m_cities_t.city_id  
                    left JOIN m_states_t on branchmapping_lines_tbl.state_id=m_states_t.state_id  
                    left JOIN tb_users on tb_users.id=branchmapping_hdr_tbl.created_by 
                    left join branch_tbl on branch_tbl.branch_id=branchmapping_lines_tbl.branch_id 
                    where 1=1 and branchmapping_hdr_tbl.company_id=$comp $wh ORDER BY $sidx $sord";

        $result1 = \DB::select($download_SQL);

        if (isset($_GET['download'])) {
            $result1 = collect($result1)->map(function ($x) {
                return (array) $x;
            })->toArray();
            return $result1;
        }

        $result = array_slice($result1, $start, $limit);
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }

    public function beatmappingreportgrid()
    {
        $comp = \Session::get('companyid');
        $wh = '';
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $search_tables = ["m_customers_t", "beatmapping_hdr_tbl", "beat_tbl", "m_states_t", "m_cities_t", "tb_users"];
        if ($_GET['_search'] == 'true') {
            $wh = $this->jqgridsearch("beatmapping_hdr_tbl", $_GET['filters'], $search_tables);
        }
        if (!$sidx)
            $sidx = 1;
        $result = \DB::select("SELECT count(beatmapping_hdr_tbl.beatmapping_id) as count from beatmapping_lines_tbl left join beatmapping_hdr_tbl on beatmapping_hdr_tbl.beatmapping_id=beatmapping_lines_tbl.beatmapping_id JOIN m_customers_t on m_customers_t.customer_id=beatmapping_hdr_tbl.employee_id  left JOIN m_cities_t on beatmapping_lines_tbl.city_id=m_cities_t.city_id  left JOIN m_states_t on beatmapping_lines_tbl.state_id=m_states_t.state_id  left JOIN tb_users on tb_users.id=beatmapping_hdr_tbl.created_by left join beat_tbl on beat_tbl.beat_id=beatmapping_lines_tbl.beat_id  where 1=1 $wh ");

        $count = $result[0]->count;
        if ($count > 0 && $limit > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }

        if ($page > $total_pages)
            $page = $total_pages;
        $start = $limit * $page - $limit;
        if ($start < 0)
            $start = 0;



        $download_SQL = "SELECT beat_tbl.beat_id,
                        m_customers_t.customer_id,
                        beat_tbl.beat_name,
                        m_customers_t.active,
                        tb_users.username,
                        tb_users.first_name,
                        beatmapping_hdr_tbl.*,
                        m_customers_t.customer_name,
                        m_states_t.state_name,
                        m_cities_t.city_name,
                         m_customers_t.customer_number,
                        m_customers_t.location_id 
                        from beatmapping_lines_tbl 
                        left join beatmapping_hdr_tbl on beatmapping_hdr_tbl.beatmapping_id=beatmapping_lines_tbl.beatmapping_id 
                        JOIN m_customers_t on m_customers_t.customer_id=beatmapping_hdr_tbl.employee_id  
                        left JOIN m_cities_t on beatmapping_lines_tbl.city_id=m_cities_t.city_id  
                        left JOIN m_states_t on beatmapping_lines_tbl.state_id=m_states_t.state_id  
                        left JOIN tb_users on tb_users.id=beatmapping_hdr_tbl.created_by 
                        left join beat_tbl on beat_tbl.beat_id=beatmapping_lines_tbl.beat_id 
                        where 1=1 and beatmapping_hdr_tbl.company_id=$comp $wh ORDER BY $sidx $sord";

        $result1 = \DB::select($download_SQL);
        if (isset($_GET['download'])) {
            $result1 = collect($result1)->map(function ($x) {
                return (array) $x;
            })->toArray();
            return $result1;
        }

        $result = array_slice($result1, $start, $limit);
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }
    public function outletmappingreportgrid()
    {
        ini_set('memory_limit', '-1');


        $comp = \Session::get('companyid');
        $wh = '';
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        //  $search_tables=["m_customers_t","beatmapping_hdr_tbl","distributormapping_lines_tbl","beat_tbl","m_states_t","m_cities_t","tb_users"];
        if ($_GET['_search'] == 'true') {
            $wh = $this->jqgridsearchnotab("v1", $_GET['filters']);
        }
        if (!$sidx)
            $sidx = 1;

        $result_array = array();

        \DB::table('r_beat_mapping_rpt')->truncate();
        \DB::table('r_distri_mpping_rpt')->truncate();

        \DB::insert("INSERT INTO `r_beat_mapping_rpt`(`beat_id`, `customer_id`, `beat_name`, `state_name`, `city_name`,`customer_name`,`customer_number`,`location_code`)SELECT
    beat_tbl.beat_id,
    m_customers_t.customer_id,
    beat_tbl.beat_name,
    m_states_t.state_name,
    m_cities_t.city_name,
    m_customers_t.customer_name,
    m_customers_t.customer_number,
    m_customers_t.location_code
FROM
    beatmapping_lines_tbl
 JOIN beatmapping_hdr_tbl ON beatmapping_hdr_tbl.beatmapping_id = beatmapping_lines_tbl.beatmapping_id
 JOIN m_customers_t ON m_customers_t.customer_id = beatmapping_hdr_tbl.employee_id 
 JOIN m_cities_t ON beatmapping_lines_tbl.city_id = m_cities_t.city_id
 JOIN m_states_t ON beatmapping_lines_tbl.state_id = m_states_t.state_id
 JOIN beat_tbl ON beat_tbl.beat_id = beatmapping_lines_tbl.beat_id");

        \DB::insert("INSERT INTO `r_distri_mpping_rpt`(`emp_id`, `area_code`, `emp_name`, `emp_code`, `customer_id`,`designation`)SELECT
    
    hr_employee_t.employee_id,
    hr_employee_t.remarks,
    hr_employee_t.first_name,
   
    hr_employee_t.employee_number,
   
    m_customers_t.customer_id,
    designation_tbl.designation_name
    
FROM
    distributormapping_lines_tbl
 JOIN distributormapping_hdr_tbl ON distributormapping_lines_tbl.distributormapping_id = distributormapping_hdr_tbl.distributormapping_id
 JOIN hr_employee_t ON hr_employee_t.employee_id = distributormapping_hdr_tbl.employee_id and hr_employee_t.active='Yes'
join designation_tbl on designation_tbl.designation_id=hr_employee_t.designation_id
 JOIN m_customers_t ON m_customers_t.customer_id = distributormapping_lines_tbl.disti_id and m_customers_t.active='Yes'
WHERE
    1 = 1 AND hr_employee_t.active = 'Yes' AND m_customers_t.active = 'Yes' and hr_employee_t.employee_id!=1");




        $result = \DB::select("select * from(select outlet_tbl.outlet_id,outlet_tbl.store_name,(SELECT region_name FROM `region_tbl` where region_id=outlet_tbl.region_id)as region_name,outlet_tbl.category_id,(SELECT outlet_category_name FROM `outlet_category` where outlet_category_id=outlet_tbl.category_id) as cat_name,outlet_tbl.route_id as outlet_beat_id,r_beat_mapping_rpt.beat_id,r_beat_mapping_rpt.beat_name,r_beat_mapping_rpt.state_name,r_beat_mapping_rpt.city_name,r_beat_mapping_rpt.customer_name,r_beat_mapping_rpt.customer_number,r_beat_mapping_rpt.location_code,r_distri_mpping_rpt.*from outlet_tbl left join r_beat_mapping_rpt on r_beat_mapping_rpt.beat_id=outlet_tbl.route_id left join r_distri_mpping_rpt on r_distri_mpping_rpt.customer_id=r_beat_mapping_rpt.customer_id)v1 where 1=1 $wh ORDER BY $sidx $sord");


        // $result=array();
        $count = count($result);
        if ($count > 0 && $limit > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }

        if ($page > $total_pages)
            $page = $total_pages;
        $start = $limit * $page - $limit;
        if ($start < 0)
            $start = 0;
        if (isset($_GET['download'])) {
            $result1 = collect($result)->map(function ($x) {
                return (array) $x;
            })->toArray();
            $result1 = collect($result)->map(function ($x) {
                return (array) $x;
            })->toArray();

            $data = '';

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=data.csv');
            $output = fopen('outletmappingmaster.csv', 'w');

            fputcsv($output, array_keys($result1[0]));
            foreach ($result1 as $row) {

                fputcsv($output, $row);

            }

            return 1;


        }

        $result = array_slice($result, $start, $limit);
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }
    public function distributorbeatmappingdata($id = null)
    {
        $this->data['id'] = $id;
        $this->data['pagemode'] = "edit";
        $table = \DB::table('beatmapping_lines_tbl')->leftjoin('beatmapping_hdr_tbl', 'beatmapping_hdr_tbl.beatmapping_id', '=', 'beatmapping_lines_tbl.beatmapping_id')->where('beatmappinglines_id', $id)->get();
        $this->data['row'] = $table[0];
        $this->data['row']->distributormapping_id = '';
        $this->data['employee_id'] = $this->jcombo('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]->employee_id);

        $this->data['state_id'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', $table[0]->state_id);
        $this->data['city_id'] = $this->jcustomselect('m_cities_t', 'city_id', 'city_name', $table[0]->city_id, ' and state_id =' . $table[0]->state_id);
        $this->data['disti_id'] = $this->jcustomselect('m_customers_t', 'customer_id', 'customer_number|customer_name', '', ' ');
        $this->data['linedata'] = array();
        return view('distributormapping.form', $this->data);
    }
    public function create($id = null)
    {

        if ($id != 0) {
            $this->data['id'] = $id;
            $this->data['pagemode'] = "edit";
            $table = \DB::table('distributormapping_hdr_tbl')->where('distributormapping_id', $id)->get();
            $this->data['row'] = $table[0];
            //$this->data['employee_id']=$this->jcombologin('hr_employee_t','employee_id','employee_number|first_name',$table[0]->employee_id);
            $depart = \Session::get('groupname');
            if ($depart == "11") {
                $this->data['employee_id'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]->employee_id, ' and position !="20" and group_type="14" and active ="Yes"');
            } else {
                $this->data['employee_id'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'employee_number|first_name', $table[0]->employee_id, ' and active ="Yes"');
            }

            $this->data['linedata'] = \DB::table('distributormapping_lines_tbl')->where('distributormapping_id', $id)->get();
            if (count($this->data['linedata']) > 0) {
                foreach ($this->data['linedata'] as $key => $value) {



                    $state_id = $value->state_id;
                    $city_id = $value->town_id;
                    $this->data['linedata'][$key]->state_id = $this->jCombologin('m_states_t', 'state_id', 'state_name', $value->state_id);
                    $this->data['linedata'][$key]->town_id = $this->jcustomselecttool('m_cities_t', 'city_id', 'city_name', $value->town_id, ' and state_id =' . $state_id);

                    $this->data['linedata'][$key]->disti_id = $value->disti_id;
                    $dist = \DB::table('m_customers_t')->where('customer_id', $value->disti_id)->get();


                    if (count($dist) <= 0) {
                        unset($this->data['linedata'][$key]);
                    } else {
                        $this->data['linedata'][$key]->distributor = $dist[0]->customer_number . "-" . $dist[0]->customer_name;
                    }
                }
            } else {


                $this->data['state_id'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', '');
                $this->data['city_id'] = $this->jCombologin('m_cities_t', 'city_id', 'city_name', '', '');
                $this->data['linedata'] = array();
            }
        } else {

            $this->data['row'] = (object) array();
            $this->data['row']->distributormapping_id = '';
            $this->data['row']->description = '';
            $this->data['row']->active = "";
            //$this->data['employee_id']=$this->jcombo('hr_employee_t','employee_id','employee_number|first_name','');
            $depart = \Session::get('groupname');
            if ($depart == "11") {
                $this->data['employee_id'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'employee_number|first_name', '', ' and position !="20" and group_type="14" and active ="Yes"');
            } else {
                $this->data['employee_id'] = $this->jcustomselect('hr_employee_t', 'employee_id', 'employee_number|first_name', '', ' and active ="Yes"');
            }
            $this->data['state_id'] = $this->jCombologin('m_states_t', 'state_id', 'state_name', '');
            $this->data['city_id'] = $this->jCombologin('m_cities_t', 'city_id', 'city_name', '', '');
            //   dd( $this->data['city_id']);
            $this->data['linedata'] = array();
        }





        return view('distributormapping.form', $this->data);
    }

    public function distributorbeatmappinggrid()
    {
        $comp = \Session::get('companyid');
        $wh = '';
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $search_tables = ["beatmapping_lines_tbl", "beat_tbl", "tb_users"];
        if ($_GET['_search'] == 'true') {
            $wh = $this->jqgridsearch("beatmapping_lines_tbl", $_GET['filters'], $search_tables);
        }
        $wh .= " and map_status=0";
        if (!$sidx)
            $sidx = 1;
        $result = \DB::select("SELECT beatmapping_lines_tbl.beatmappinglines_id as count  FROM `beatmapping_lines_tbl` left join beatmapping_hdr_tbl on beatmapping_hdr_tbl.beatmapping_id=beatmapping_lines_tbl.beatmapping_id left join beat_tbl on  beat_tbl.beat_id=beatmapping_lines_tbl.beat_id left join hr_employee_t on hr_employee_t.employee_id=beatmapping_hdr_tbl.employee_id where 1=1 and beatmapping_lines_tbl.company_id=$comp $wh ");

        $count = $result[0]->count;
        if ($count > 0 && $limit > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }

        if ($page > $total_pages)
            $page = $total_pages;
        $start = $limit * $page - $limit;
        if ($start < 0)
            $start = 0;


        $SQL = "SELECT beatmapping_lines_tbl.*,beat_tbl.beat_name,hr_employee_t.first_name,hr_employee_t.employee_number,tb_users.username,tb_users.first_name FROM `beatmapping_lines_tbl` left join beatmapping_hdr_tbl on beatmapping_hdr_tbl.beatmapping_id=beatmapping_lines_tbl.beatmapping_id left join beat_tbl on  beat_tbl.beat_id=beatmapping_lines_tbl.beat_id left join hr_employee_t on hr_employee_t.employee_id=beatmapping_hdr_tbl.employee_id where 1=1 and beatmapping_lines_tbl.company_id=$comp $wh ORDER BY $sidx $sord LIMIT $start,$limit";


        $result = \DB::select($SQL);
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }
    public function distributormappinggrid()
    {
        $comp = \Session::get('companyid');
        $wh = '';
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        $search_tables = ["distributormapping_hdr_tbl", "designation_tbl", "hr_employee_t", "tb_users", "distributormapping_lines_tbl", "m_states_t", "m_cities_t", "m_customers_t"];
        if ($_GET['_search'] == 'true') {
            $wh .= $this->jqgridsearch("distributormapping_hdr_tbl", $_GET['filters'], $search_tables);
        }
        if (!$sidx)
            $sidx = 1;
        $result = \DB::select("SELECT distributormapping_hdr_tbl.*,
                        designation_tbl.designation_name,
                        hr_employee_t.employee_id,
                        hr_employee_t.first_name as employee_name,
                        repso.first_name as reporting_so,
                        repman.first_name as reporting_manager,
                        repman.email as reporting_email_id,
                        hr_employee_t.employee_number,
                        tb_users.username,tb_users.first_name,
                        m_states_t.state_name,m_cities_t.city_name,
                        m_customers_t.customer_id,m_customers_t.customer_name,
                        m_customers_t.location_id as location_code,m_customers_t.customer_number  
                        from distributormapping_lines_tbl 
                        LEFT JOIN distributormapping_hdr_tbl ON distributormapping_lines_tbl.distributormapping_id=distributormapping_hdr_tbl.distributormapping_id 
                        left JOIN hr_employee_t on hr_employee_t.employee_id=distributormapping_hdr_tbl.employee_id  
                        left join designation_tbl on designation_tbl.designation_id=hr_employee_t.designation
                        left join hr_employee_t as repso on repso.employee_id=hr_employee_t.reporting_manager 
                        left join hr_employee_t as repman on repman.employee_id=hr_employee_t.reporting_manager  
                        left JOIN tb_users on tb_users.id=distributormapping_hdr_tbl.created_by  
                        LEFT JOIN m_states_t ON m_states_t.state_id=distributormapping_lines_tbl.state_id 
                        LEFT JOIN m_cities_t ON m_cities_t.city_id=distributormapping_lines_tbl.city_id 
                        LEFT JOIN m_customers_t ON m_customers_t.customer_id=distributormapping_lines_tbl.disti_id 
                        where 1=1 and hr_employee_t.active='Yes' and m_customers_t.active='Yes' and distributormapping_hdr_tbl.company_id=$comp $wh ");

        $count = count($result);
        if ($count > 0 && $limit > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }

        if ($page > $total_pages)
            $page = $total_pages;
        $start = $limit * $page - $limit;
        if ($start < 0)
            $start = 0;

        if (isset($_GET['download'])) {
            $result1 = collect($result)->map(function ($x) {
                return (array) $x;
            })->toArray();
            return $result1;
        }

        $result = array_slice($result, $start, $limit);
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }

    public function distributormappinggriddata(Request $request)
    {

        $comp = \Session::get('companyid');
        if ($request->ajax()) {

            $data = \DB::table('distributormapping_hdr_tbl')
                ->leftJoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 'distributormapping_hdr_tbl.employee_id')
                ->leftJoin('tb_users', 'tb_users.id', '=', 'distributormapping_hdr_tbl.created_by')
                ->select(
                    'distributormapping_hdr_tbl.*',
                    \DB::raw("CONCAT(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as employee_number"),
                    \DB::raw("CONCAT(tb_users.employee_number,'-',tb_users.first_name) as first_name")
                )
                ->where('distributormapping_hdr_tbl.company_id', $comp)
                ->groupBy('distributormapping_hdr_tbl.distributormapping_id')
                ->orderBy('distributormapping_hdr_tbl.distributormapping_id', 'DESC');

            return DataTables::of($data)
                ->rawColumns(['actions'])
                ->make(true);

        }



    }


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
        ]);
        // Normalize "bulk_" keys once
        $form = $this->normalizeLineFormKeys($form);

        // Build header + lines
        $data = $this->validatePost($form, $this->table, 'header');
        $lines_data = $this->validatePost($form, $this->subtable, 'lines');

        \DB::beginTransaction();
        try {
            if ($_POST['distributormapping_id'] == '') {
                $action = 'create';
                $order_status = "Saved Successfully";
            } else {
                $action = 'update';
                $order_status = "Updated Successfully";
            }


            $id = $this->model->insertRow($data);
            $lid = $this->submodel->subgridSave($lines_data, $id);

            $this->auditlog($id, "distributormapping", $action, $data, "distributormapping_hdr_tbl");

            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => $order_status));
        } catch (\Illuminate\Database\QueryException $e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            dd($dbCode);
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }

    }

    public function show($id = null)
    {

        $headerdata = \DB::table('distributormapping_hdr_tbl as qh')
            ->leftjoin('hr_employee_t as p', 'qh.employee_id', '=', 'p.employee_id')
            ->select('qh.*', 'p.first_name')
            ->where('qh.distributormapping_id', $id)
            ->get();
        $this->data['headerdata'] = $headerdata[0];
        $this->data['linesdata'] = \DB::table('distributormapping_lines_tbl as qh')
            ->leftjoin('m_states_t as p', 'qh.state_id', '=', 'p.state_id')
            ->leftjoin('m_cities_t as s', 'qh.town_id', '=', 's.city_id')
            ->leftjoin('m_customers_t as pl', 'qh.disti_id', '=', 'pl.customer_id')
            ->select('qh.*', 's.city_name', 'pl.customer_name', 'pl.customer_number', 'p.state_name')
            ->where('qh.distributormapping_id', $id)
            ->get();


        return view('distributormapping.view', $this->data);
    }
    public function destroy($id = null)
    {
        /**Auditlog**/
        $action = "Delete";
        $this->auditlog($id, "distributormapping", $action, '', "distributormapping_hdr_tbl");
        $query = DB::table('distributormapping_hdr_tbl')->where('distributormapping_id', $id)->delete();
        $query = DB::table('distributormapping_lines_tbl')->where('distributormapping_id', $id)->delete();

        return 0;
    }
    public function sodistributordetails($id = null)
    {
        $employeedetails = \DB::table('hr_employee_t')->select('employee_id')->where('reporting_manager', $id)->get();
        if (count($employeedetails) > 0) {
            $empid = "";
            foreach ($employeedetails as $key => $value) {
                $empid .= $value->employee_id . ",";
            }
            $empid1 = trim($empid, ",");
            $distributordetails = \DB::table('distributormapping_hdr_tbl')->leftjoin('distributormapping_lines_tbl', 'distributormapping_lines_tbl.distributormapping_id', '=', 'distributormapping_hdr_tbl.distributormapping_id')->select('distributormapping_lines_tbl.state_id', 'distributormapping_lines_tbl.town_id', 'distributormapping_lines_tbl.disti_id')->WhereIn('employee_id', explode(",", $empid1))->get();
            return $distributordetails;
        } else {

            return 0;
        }

    }

    /*deepika purpose: distributor details*/
    public function getdistrigridData(Request $request)
    {

        $wh = "";
        $comp = \Session::get('companyid');

        $wh .= " and m_customers_t.active='Yes' and m_customers_t.company_id=" . $comp;

        $SQL = "SELECT m_customers_t.* from m_customers_t where 1=1 $wh";
        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

    }


    /*end*/
    /* purpose:get distributor name*/
    public function getdistributorname($id = null)
    {
        $bp = \DB::select("select * from m_customers_t where customer_id=" . $id);
        return $bp;
    }
    /*end*/
}
