<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Secondarydataupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SecondarydatauploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function __construct(){
        $this->data=array();
        
        $this->table="sd_secondarydataupload_t";
        $this->pageModule="Secondarydataupload";
		$this->model=new Secondarydataupload;
		  $this->data['pageMethod']=\Request::route()->getName();
       
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

      		   $batch = $type = '';
        $this->data['status'] = $this->data['message'] = '';
		        if (isset($_GET['batchname'])) {
            if (!empty($_GET['batchname'])) {
                $filter = 'AND sd_secondarydataupload_t.batch_name = "' . $_GET['batchname'] . '"';
                $batch = $_GET['batchname'];
            }
        }
		if (isset($_GET['type'])) {
            $type = 'SECDBUPLOAD';
            $message['status'] = 'success';
            switch ($_GET['type']) {
                case 'verify':$upload = $this->UploadValidation($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    break;
                case 'load':$upload = $this->LoadMaster($batch, $type, $message);
                    $this->data['status'] = $upload['status'];
                    $this->data['message'] = $upload['message'];
                    break;
			}

        }
		
        $this->data['pageMethod']=\Request::route()->getName();

        return view('secondarydataupload.table', $this->data);
    }
 
    
        public function create($id=null)
    {
        if(isset($id)){

      	     $secdbupload=Secondarydataupload::find($id);
            $this->data['id'] = $id;
            $this->data['pagemode'] = "edit";
            $table = \DB::table('sd_secondarydataupload_t')->where('secondarydate_upload_id',$id)->get();
            $this->data['row'] = $table[0];
            $this->data['data_type']=$table[0]->data_type;
            $this->data['emp_id']=$table[0]->emp_id;
            $this->data['field_force_name']=$table[0]->field_force_name;
            $this->data['position']=$table[0]->position;
            $this->data['emp_with_level']=$table[0]->emp_with_level;
            $this->data['ff_status']=$table[0]->ff_status;
            $this->data['hq_name']=$table[0]->hq_name;
            $this->data['current_reporting_manager']=$table[0]->current_reporting_manager;
            $this->data['stockist_dist_name']=$table[0]->stockist_dist_name;
            $this->data['stockist_code']=$table[0]->stockist_code;
            $this->data['type_d_s']=$table[0]->type_d_s;
            $this->data['type_sales_target']=$table[0]->type_sales_target;
            $this->data['area']=$table[0]->area;
            $this->data['state']=$table[0]->state;
            $this->data['region']=$table[0]->region;
            $this->data['zone']=$table[0]->zone;
            $this->data['c_year']=$table[0]->c_year;
            $this->data['f_year']=$table[0]->f_year;
            $this->data['month']=$table[0]->month;
            $this->data['month_y']=$table[0]->month_y;
            $this->data['till_month']=$table[0]->till_month;
            $this->data['product_name']=$this->jCombologin("sd_secondarydataupload_t","product_name","product_name",$table[0]->product_name);
            $this->data['sfg_product_name']=$this->jCombologin("sd_secondarydataupload_t","sfg_product_name","sfg_product_name",$table[0]->sfg_product_name);
            $this->data['product_category']=$table[0]->product_category;
            $this->data['kit']=$table[0]->kit;
            $this->data['product_form_change']=$table[0]->product_form_change;
            $this->data['product_division']=$table[0]->product_division;
            $this->data['hq_division']=$table[0]->hq_division;
            $this->data['division']=$table[0]->division;
            $this->data['branc_name']=$table[0]->branc_name;
            $this->data['product_code']=$table[0]->product_code;
            $this->data['package']=$table[0]->package;
            $this->data['sales_kgs_lts']=$table[0]->sales_kgs_lts;
            $this->data['price_per_unit']=$table[0]->price_per_unit;
            $this->data['opening_stock']=$table[0]->opening_stock;
            $this->data['opening_stock_value']=$table[0]->opening_stock_value;
            $this->data['purchase_stock']=$table[0]->purchase_stock;
            $this->data['sales_unit']=$table[0]->sales_unit;
            $this->data['sales_value']=$table[0]->sales_value;
            $this->data['sales_return_stock']=$table[0]->sales_return_stock;
            $this->data['sales_return_stock_value']=$table[0]->sales_return_stock_value;
            $this->data['closing_stock']=$table[0]->closing_stock;
            $this->data['closing_stock_value']=$table[0]->closing_stock_value;
            $this->data['free_stock']=$table[0]->free_stock;
            $this->data['free_stock_value']=$table[0]->free_stock_value;
            $this->data['purchase_return_stock']=$table[0]->purchase_return_stock;
            $this->data['purchse_retrun_stock_value']=$table[0]->purchse_retrun_stock_value;
            $this->data['transit_stock']=$table[0]->transit_stock;
            $this->data['transit_stock_valu']=$table[0]->transit_stock_valu;
            $this->data['expired_stock']=$table[0]->expired_stock;
            $this->data['expired_stock_value']=$table[0]->expired_stock_value;
            $this->data['damaged_stock']=$table[0]->damaged_stock;
            $this->data['damaged_stock_value']=$table[0]->damaged_stock_value;
            $this->data['status']=$table[0]->status;
            $this->data['stockist_peroid_validity']=$table[0]->stockist_peroid_validity;
            $this->data['approved_date']=$table[0]->approved_date;
            $this->data['user_name']=$table[0]->user_name;
            $this->data['date_of_upon']=$table[0]->date_of_upon;
            $this->data['hq_old_name']=$table[0]->hq_old_name;
            $this->data['cur_mgr_status']=$table[0]->cur_mgr_status;
            $this->data['local_area_city']=$table[0]->local_area_city;
            $this->data['batch_name']=$table[0]->batch_name;
            $this->data['batch_status']=$table[0]->batch_status;
            $this->data['batch_date']=$table[0]->batch_date;
           
        }else{
            $secdbuploads=\DB::connection()->getSchemaBuilder()->getColumnListing("sd_secondarydataupload_t");
            $secdbupload= array();
            foreach($secdbuploads as $key=>$val)
            {
                $secdbupload[$val]="";
            }
        }
     
        
	    $this->data['secdbuploaddata']=$secdbupload;
		

        return view('secondarydataupload.form',$this->data);
    }

    public function save(Request $request)
    {

        $secdbupload=new Secondarydataupload();
        
            $data['data_type']=$_POST['data_type'];
            $data['emp_id']=$_POST['emp_id'];
            $data['field_force_name']=$_POST['field_force_name'];
            $data['position']=$_POST['position'];
            $data['emp_with_level']=$_POST['emp_with_level'];
            $data['ff_status']=$_POST['ff_status'];
            $data['hq_name']=$_POST['hq_name'];
            $data['current_reporting_manager']=$_POST['current_reporting_manager'];
            $data['stockist_dist_name']=$_POST['stockist_dist_name'];
            $data['stockist_code']=$_POST['stockist_code'];
            $data['type_d_s']=$_POST['type_d_s'];
            $data['type_sales_target']=$_POST['type_sales_target'];
            $data['area']=$_POST['area'];
            $data['state']=$_POST['state'];
            $data['region']=$_POST['region'];
            $data['zone']=$_POST['zone'];
            $data['c_year']=$_POST['c_year'];
            $data['f_year']=$_POST['f_year'];
            $data['month']=$_POST['month'];
            $data['month_y']=$_POST['month_y'];
            $data['till_month']=$_POST['till_month'];
            $data['product_name']=$_POST['product_name'];
            $data['sfg_product_name']=$_POST['sfg_product_name'];
            $data['product_category']=$_POST['product_category'];
            $data['kit']=$_POST['kit'];
            $data['product_form_change']=$_POST['product_form_change'];
            $data['product_division']=$_POST['product_division'];
            $data['hq_division']=$_POST['hq_division'];
            $data['division']=$_POST['division'];
            $data['branc_name']=$_POST['branc_name'];
            $data['product_code']=$_POST['product_code'];
            $data['package']=$_POST['package'];
            $data['sales_kgs_lts']=$_POST['sales_kgs_lts'];
            $data['price_per_unit']=$_POST['price_per_unit'];
            $data['opening_stock']=$_POST['opening_stock'];
            $data['opening_stock_value']=$_POST['opening_stock_value'];
            $data['purchase_stock']=$_POST['purchase_stock'];
            $data['purchase_stock_value']=$_POST['purchase_stock_value'];
            $data['sales_unit']=$_POST['sales_unit'];
            $data['sales_value']=$_POST['sales_value'];
            $data['sales_return_stock']=$_POST['sales_return_stock'];
            $data['sales_return_stock_value']=$_POST['sales_return_stock_value'];
            $data['closing_stock']=$_POST['closing_stock'];
            $data['closing_stock_value']=$_POST['closing_stock_value'];
            $data['free_stock']=$_POST['free_stock'];
            $data['free_stock_value']=$_POST['free_stock_value'];
            $data['purchase_return_stock']=$_POST['purchase_return_stock'];
            $data['purchse_retrun_stock_value']=$_POST['purchse_retrun_stock_value'];
            $data['transit_stock']=$_POST['transit_stock'];
            $data['transit_stock_valu']=$_POST['transit_stock_valu'];
            $data['expired_stock']=$_POST['expired_stock'];
            $data['expired_stock_value']=$_POST['expired_stock_value'];
            $data['damaged_stock']=$_POST['damaged_stock'];
            $data['damaged_stock_value']=$_POST['damaged_stock_value'];
            $data['status']=$_POST['status'];
            $data['stockist_peroid_validity']=$_POST['stockist_peroid_validity'];
            $data['approved_date']=date('Y-m-d',strtotime($_POST['approved_date']));
            $data['user_name']=$_POST['user_name'];
            $data['date_of_upon']=date('Y-m-d',strtotime($_POST['date_of_upon']));
            $data['hq_old_name']=$_POST['hq_old_name'];
            $data['cur_mgr_status']=$_POST['cur_mgr_status'];
            $data['local_area_city']=$_POST['local_area_city'];
            
        	$data['batch_name']=$_POST['batch_name'];
			$data['batch_date']=$_POST['batch_date'];
			$data['batch_status']= $_POST['batch_status'];
			$data['company_id']=\Session::get('companyid');
			$data['organization_id']=\Session::get('organization');
			$data['location_id']=\Session::get('location');
			$data['last_updated_by']=\Session::get('id');
			$data['updated_at']=date('Y-m-d H:i:s');
			$id=$_POST['secondarydate_upload_id'];
       	Secondarydataupload::find($id)->update($data);
		  return response()->json(array('status' => 'success', 'message' => 'Updated successfully!!','id'=>$id));
    }
    

public function getsecondarydatauploadData(Request $request)
{

    if ($request->ajax()) {
        $data = \DB::table('sd_secondarydataupload_t')
            ->select(['*']);
        return DataTables::of($data)
            ->rawColumns(['actions'])
            ->make(true);
    }
}
public function secondarydataUploadexcel(Request $request)
{
    if (!$request->hasFile('choosefile')) {
        return response()->json(['status' => 'error', 'message' => 'File not found!']);
    }

    $file = $request->file('choosefile');

    if (strtolower($file->getClientOriginalExtension()) !== 'csv') {
        return response()->json(['status' => 'error', 'message' => 'Please upload a valid CSV file!']);
    }

    ini_set('memory_limit', '1024M');
    ini_set('max_execution_time', 0);

    $batchName = $request->input('batch_name');
    $handle = fopen($file->getRealPath(), "r");

    $header = fgetcsv($handle); // skip header

    $months = [];
    $rows = [];
    $batchSize = 300; // SAFE for 63+ columns

    DB::beginTransaction();

    try {

        // ---------- FIRST LOOP : Collect Months ----------
        while (($line = fgetcsv($handle, 0, ",")) !== false) {

            if (count($line) < 63) continue;

            $month_y = trim($line[19]);
            if ($month_y != '') {
                $months[] = $month_y;
            }
        }

        $months = array_unique($months);

        if (!empty($months)) {
            DB::table('sd_secondarydataupload_t')
                ->whereIn('month_y', $months)
                ->delete();
        }

        // ---------- RESET FILE POINTER ----------
        rewind($handle);
        fgetcsv($handle); // skip header again

        // ---------- SECOND LOOP : Insert ----------
        while (($line = fgetcsv($handle, 0, ",")) !== false) {

            if (count($line) < 63) continue;

            $month_y = trim($line[19]);

            $rows[] = [
                'data_type' => trim($line[0]),
                'emp_id' => trim($line[1]),
                'field_force_name' => trim($line[2]),
                'position' => trim($line[3]),
                'emp_with_level' => trim($line[4]),
                'ff_status' => trim($line[5]),
                'hq_name' => trim($line[6]),
                'current_reporting_manager' => trim($line[7]),
                'stockist_dist_name' => trim($line[8]),
                'stockist_code' => trim($line[9]),
                'type_d_s' => trim($line[10]),
                'type_sales_target' => trim($line[11]),
                'area' => trim($line[12]),
                'state' => trim($line[13]),
                'region' => trim($line[14]),
                'zone' => trim($line[15]),
                'c_year' => trim($line[16]),
                'f_year' => trim($line[17]),
                'month' => trim($line[18]),
                'month_y' => $month_y,
                'till_month' => trim($line[20]),
                'product_name' => trim($line[21]),
                'sfg_product_name' => trim($line[22]),
                'product_category' => trim($line[23]),
                'kit' => trim($line[24]),
                'product_form_change' => trim($line[25]),
                'product_division' => trim($line[26]),
                'hq_division' => trim($line[27]),
                'division' => trim($line[28]),
                'branc_name' => trim($line[29]),
                'product_code' => trim($line[30]),
                'package' => trim($line[31]),
                'sales_kgs_lts' => trim($line[32]),
                'price_per_unit' => trim($line[33]),
                'opening_stock' => trim($line[34]),
                'opening_stock_value' => trim($line[35]),
                'purchase_stock' => trim($line[36]),
                'purchase_stock_value' => trim($line[37]),
                'sales_unit' => trim($line[38]),
                'sales_value' => trim($line[39]),
                'sales_return_stock' => trim($line[40]),
                'sales_return_stock_value' => trim($line[41]),
                'closing_stock' => trim($line[42]),
                'closing_stock_value' => trim($line[43]),
                'free_stock' => trim($line[44]),
                'free_stock_value' => trim($line[45]),
                'purchase_return_stock' => trim($line[46]),
                'purchse_retrun_stock_value' => trim($line[47]),
                'transit_stock' => trim($line[48]),
                'transit_stock_valu' => trim($line[49]),
                'expired_stock' => trim($line[50]),
                'expired_stock_value' => trim($line[51]),
                'damaged_stock' => trim($line[52]),
                'damaged_stock_value' => trim($line[53]),
                'status' => trim($line[54]),
                'stockist_peroid_validity' => trim($line[55]),
                'approved_date' => trim($line[56]),
                'user_name' => trim($line[57]),
                'date_of_upon' => trim($line[58]),
                'hq_old_name' => trim($line[59]),
                'cur_mgr_status' => trim($line[60]),
                'local_area_city' => trim($line[61]),
                'focus_product' => trim($line[62]),
                'company_id' => session('companyid'),
                'organization_id' => session('organization'),
                'location_id' => session('location'),
                'created_by' => session('id'),
                'created_at' => now(),
                'last_updated_by' => session('id'),
                'updated_at' => now(),
                'batch_date' => now()->toDateString(),
                'batch_status' => "LOADED",
                'batch_name' => $batchName,
            ];

            if (count($rows) >= $batchSize) {
                DB::table('sd_secondarydataupload_t')->insert($rows);
                $rows = [];
            }
        }

        if (!empty($rows)) {
            DB::table('sd_secondarydataupload_t')->insert($rows);
        }

        fclose($handle);
        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Your data loaded successfully!'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();
        fclose($handle);

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}

	

}
