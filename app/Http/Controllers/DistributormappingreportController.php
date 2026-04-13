<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Productbasedcostreport;
use Illuminate\Http\Request;

class DistributormappingreportController extends Controller
{
     public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
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

       return view('distributormappingreport.distributormappingdetailrpt',$this->data);    
    }
    
    public function productmaprptindex(Request $request)
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

       return view('distributormappingreport.productmappingdetailrpt',$this->data);    
    }
     
	   public function getdistributormappingdetailrpt(Request $request)
	{

    if ($request->ajax()) {
        $data = \DB::table('distributormapping_hdr_tbl')
		->leftJoin('distributormapping_lines_tbl', 'distributormapping_hdr_tbl.distributormapping_id', '=', 'distributormapping_lines_tbl.distributormapping_id')
        ->leftJoin('hr_employee_t', 'distributormapping_hdr_tbl.employee_id', '=', 'hr_employee_t.employee_id')
        ->leftJoin('m_states_t', 'distributormapping_lines_tbl.state_id', '=', 'm_states_t.state_id')
        ->leftJoin('m_cities_t', 'distributormapping_lines_tbl.town_id', '=', 'm_cities_t.city_id')
        ->leftJoin('m_customers_t', 'distributormapping_lines_tbl.disti_id', '=', 'm_customers_t.customer_id')


        ->select([
            'distributormapping_hdr_tbl.distributormapping_id',
            'hr_employee_t.employee_number',
            'hr_employee_t.first_name',
            'm_states_t.state_name',
            'm_cities_t.city_name',
            'm_customers_t.customer_name',
            'm_customers_t.active as dist_status',
            'distributormapping_hdr_tbl.created_at',
            'hr_employee_t.active',
            'hr_employee_t.date_of_leaving'
            ]);


        return DataTables::of($data)

            ->rawColumns(['actions'])
            ->make(true);
    }
	}
	
	   public function getproductmappingdetailrpt(Request $request)
        {
            
        $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
        $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';
    
        $SQL = "SELECT * from (
select * from (SELECT
    productmapping_hdr_tbl.productmapping_id,
    productmapping_lines_tbl.active,
    hr_employee_t.employee_number,
    hr_employee_t.first_name,
    m_product_groups_t.group_name,
    m_product_category_t.category_name,
    m_products_t.concatenated_product,
    productmapping_hdr_tbl.created_at
    FROM
    productmapping_hdr_tbl
    LEFT JOIN productmapping_lines_tbl ON productmapping_hdr_tbl.productmapping_id = productmapping_lines_tbl.productmapping_id
    LEFT JOIN hr_employee_t ON productmapping_hdr_tbl.employee_id = hr_employee_t.employee_id
    LEFT JOIN m_product_groups_t ON productmapping_lines_tbl.prd_group_id = m_product_groups_t.product_group_id
    LEFT JOIN m_product_category_t ON productmapping_lines_tbl.prd_category_id = m_product_category_t.product_category_id
    LEFT JOIN m_products_t ON productmapping_lines_tbl.prd_id = m_products_t.product_id ) v1 where 1=1 and date(created_at) >= ? AND date(created_at) <= ?) AS v1";
    
        $results = \DB::select($SQL, [$start_date, $end_date]);
    
        return response()->json(['data' => $results]);
    }
	
	
}
