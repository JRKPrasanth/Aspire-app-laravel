<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Primarydataupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PrimarydatauploadController extends Controller
{

	public function __construct(){
        $this->data=array();
        
        $this->table="sd_primarydataupload_t";
        $this->pageModule="Primarydataupload";
		$this->model=new Primarydataupload;
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
                $filter = 'AND sd_primarydataupload_t.batch_name = "' . $_GET['batchname'] . '"';
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

        return view('primarydataupload.table', $this->data);
    }



		public function create($id=null)
			{
				if(isset($id)){

      	     $pridbupload=Primarydataupload::find($id);
            $this->data['id'] = $id;
            $this->data['pagemode'] = "edit";
            $table = \DB::table('sd_primarydataupload_t')->where('primarydata_upload_id',$id)->get();
            $this->data['row'] = $table[0];
            $this->data['data_type']=$table[0]->data_type;
            $this->data['emp_id']=$table[0]->emp_id;
            $this->data['stockist_dist_name']=$table[0]->stockist_dist_name;
            $this->data['invoice_no']=$table[0]->invoice_no;
            $this->data['invoice_date']=$table[0]->invoice_date;
            $this->data['sales_month']=$table[0]->sales_month;
            $this->data['name_type']=$table[0]->name_type;
            $this->data['active']=$table[0]->active;
            $this->data['current_manager']=$table[0]->current_manager;
            $this->data['cost_type']=$table[0]->cost_type;
            $this->data['area']=$table[0]->area;
            $this->data['state']=$table[0]->state;
            $this->data['region']=$table[0]->region;
            $this->data['zone']=$table[0]->zone;
            $this->data['c_year']=$table[0]->c_year;
            $this->data['f_year']=$table[0]->f_year;
            $this->data['month']=$table[0]->month;
            $this->data['month_y']=$table[0]->month_y;
            $this->data['till_month']=$table[0]->till_month;
            $this->data['product_name']=$this->jCombologin("sd_primarydataupload_t","product_name","product_name",$table[0]->product_name);
            $this->data['sfg_product_name']=$this->jCombologin("sd_primarydataupload_t","sfg_product_name","sfg_product_name",$table[0]->sfg_product_name);
            $this->data['packing_qty']=$table[0]->packing_qty;
            $this->data['kit']=$table[0]->kit;
            $this->data['product_form_change']=$table[0]->product_form_change;
            $this->data['product_category']=$table[0]->product_category;
            $this->data['division']=$table[0]->division;
            $this->data['weight']=$table[0]->weight;
            $this->data['rate']=$table[0]->rate;
            $this->data['unit_sold']=$table[0]->unit_sold;
            $this->data['asseesable_value']=$table[0]->asseesable_value;
            $this->data['discount']=$table[0]->discount;
            $this->data['igst']=$table[0]->igst;
            $this->data['cgst']=$table[0]->cgst;
            $this->data['sgst']=$table[0]->sgst;
            $this->data['taxable_value']=$table[0]->taxable_value;
            $this->data['med_cost_bf_bulk_dis']=$table[0]->med_cost_bf_bulk_dis;
            $this->data['cash_amount']=$table[0]->cash_amount;
            $this->data['total_invoice_amount']=$table[0]->total_invoice_amount;
            $this->data['app_man_power']=$table[0]->app_man_power;
            $this->data['mrp']=$table[0]->mrp;
            $this->data['MPQ']=$table[0]->MPQ;
            $this->data['free_unit']=$table[0]->free_unit;
            $this->data['package_weight']=$table[0]->package_weight;
            $this->data['batch_number']=$table[0]->batch_number;
            $this->data['last_6montth']=$table[0]->last_6montth;
            $this->data['working_days']=$table[0]->working_days;
            $this->data['status']=$table[0]->status;
            $this->data['state_consolidated']=$table[0]->state_consolidated;
            $this->data['hq']=$table[0]->hq;
            $this->data['sales_free_unit']=$table[0]->sales_free_unit;
            $this->data['batch_name']=$table[0]->batch_name;
            $this->data['batch_status']=$table[0]->batch_status;
            $this->data['batch_date']=$table[0]->batch_date;
           
        }else{
            $pridbuploads=\DB::connection()->getSchemaBuilder()->getColumnListing("sd_primarydataupload_t");
            $pridbupload= array();
            foreach($pridbuploads as $key=>$val)
            {
                $pridbupload[$val]="";
            }
        }
     
        
	    $this->data['pridbuploaddata']=$pridbupload;
		

        return view('primarydataupload.form',$this->data);
    }

    public function save(Request $request)
    {

        $pridbupload=new Primarydataupload();
        
            $data['data_type']=$_POST['data_type'];
            $data['emp_id']=$_POST['emp_id'];
            $data['stockist_dist_name']=$_POST['stockist_dist_name'];
            $data['invoice_no']=$_POST['invoice_no'];
            $data['invoice_date']=date('Y-m-d',strtotime($_POST['invoice_date']));
            $data['sales_month']=$_POST['sales_month'];
            $data['name_type']=$_POST['name_type'];
            $data['active']=$_POST['active'];
            $data['current_manager']=$_POST['current_manager'];
            $data['cost_type']=$_POST['cost_type'];
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
            $data['packing_qty']=$_POST['packing_qty'];
            $data['weight']=$_POST['weight'];
            $data['division']=$_POST['division'];
            $data['rate']=$_POST['rate'];
            $data['unit_sold']=$_POST['unit_sold'];
            $data['asseesable_value']=$_POST['asseesable_value'];
            $data['discount']=$_POST['discount'];
            $data['igst']=$_POST['igst'];
            $data['cgst']=$_POST['cgst'];
            $data['sgst']=$_POST['sgst'];
            $data['taxable_value']=$_POST['taxable_value'];
            $data['med_cost_bf_bulk_dis']=$_POST['med_cost_bf_bulk_dis'];
            $data['cash_amount']=$_POST['cash_amount'];
            $data['total_invoice_amount']=$_POST['total_invoice_amount'];
            $data['app_man_power']=$_POST['app_man_power'];
            $data['mrp']=$_POST['mrp'];
            $data['MPQ']=$_POST['MPQ'];
            $data['free_unit']=$_POST['free_unit'];
            $data['package_weight']=$_POST['package_weight'];
            $data['batch_number']=$_POST['batch_number'];
            $data['last_6montth']=$_POST['last_6montth'];
            $data['working_days']=date('Y-m-d',strtotime($_POST['working_days']));
            $data['status']=$_POST['status'];
            $data['state_consolidated']=$_POST['state_consolidated'];
            $data['hq']=$_POST['hq'];
            $data['sales_free_unit']=$_POST['sales_free_unit'];
            
        	$data['batch_name']=$_POST['batch_name'];
			$data['batch_date']=$_POST['batch_date'];
			$data['batch_status']= $_POST['batch_status'];
			$data['company_id']=\Session::get('companyid');
			$data['organization_id']=\Session::get('organization');
			$data['location_id']=\Session::get('location');
			$data['last_updated_by']=\Session::get('id');
			$data['updated_at']=date('Y-m-d H:i:s');
			$id=$_POST['primarydata_upload_id'];
       	Primarydataupload::find($id)->update($data);
		  return response()->json(array('status' => 'success', 'message' => 'Updated successfully!!','id'=>$id));
    }


public function getprimarydatauploadData(Request $request)
{

    if ($request->ajax()) {
        $data = \DB::table('sd_primarydataupload_t')
            ->select(['*']);
        return DataTables::of($data)
            ->rawColumns(['actions'])
            ->make(true);
    }
}


public function primarydataUploadexcel(Request $request)
{
    if (!$request->hasFile('choosefile')) {
        return response()->json([
            'status' => 'error',
            'message' => 'File not found!'
        ]);
    }

    $file = $request->file('choosefile');

    if (strtolower($file->getClientOriginalExtension()) !== 'csv') {
        return response()->json([
            'status' => 'error',
            'message' => 'Please upload a valid CSV file!'
        ]);
    }

    ini_set('memory_limit', '1024M');
    ini_set('max_execution_time', 0);

    $handle = fopen($file->getRealPath(), "r");

    // Skip header
    fgetcsv($handle);

    $months = [];
    $rows = [];
    $batchSize = 400; // SAFE for 50+ columns
    $batchName = $request->input('batch_name');

    DB::beginTransaction();

    try {

        // -------- FIRST LOOP : Collect All Months --------
        while (($line = fgetcsv($handle, 0, ",")) !== false) {

            if (count($line) < 50) continue;

            $month_y = trim($line[17]);

            if ($month_y != '') {
                $months[] = $month_y;
            }
        }

        $months = array_unique($months);

        if (!empty($months)) {
            DB::table('sd_primarydataupload_t')
                ->whereIn('month_y', $months)
                ->delete();
        }

        // -------- RESET FILE POINTER --------
        rewind($handle);
        fgetcsv($handle); // skip header again

        // -------- SECOND LOOP : Insert --------
        while (($line = fgetcsv($handle, 0, ",")) !== false) {

            if (count($line) < 50) continue;

            $month_y = trim($line[17]);

            $rows[] = [
                'data_type' => trim($line[0]),
                'emp_id' => trim($line[1]),
                'stockist_dist_name' => trim($line[2]),
                'invoice_no' => trim($line[3]),
                'invoice_date' => trim($line[4]),
                'sales_month' => trim($line[5]),
                'name_type' => trim($line[6]),
                'active' => trim($line[7]),
                'current_manager' => trim($line[8]),
                'cost_type' => trim($line[9]),
                'area' => trim($line[10]),
                'state' => trim($line[11]),
                'region' => trim($line[12]),
                'zone' => trim($line[13]),
                'c_year' => trim($line[14]),
                'f_year' => trim($line[15]),
                'month' => trim($line[16]),
                'month_y' => $month_y,
                'till_month' => trim($line[18]),
                'product_name' => trim($line[19]),
                'sfg_product_name' => trim($line[20]),
                'packing_qty' => trim($line[21]),
                'kit' => trim($line[22]),
                'product_form_change' => trim($line[23]),
                'product_category' => trim($line[24]),
                'division' => trim($line[25]),
                'weight' => trim($line[26]),
                'rate' => trim($line[27]),
                'unit_sold' => trim($line[28]),
                'asseesable_value' => trim($line[29]),
                'discount' => trim($line[30]),
                'igst' => trim($line[31]),
                'cgst' => trim($line[32]),
                'sgst' => trim($line[33]),
                'taxable_value' => trim($line[34]),
                'med_cost_bf_bulk_dis' => trim($line[35]),
                'cash_amount' => trim($line[36]),
                'total_invoice_amount' => trim($line[37]),
                'app_man_power' => trim($line[38]),
                'mrp' => trim($line[39]),
                'MPQ' => trim($line[40]),
                'free_unit' => trim($line[41]),
                'package_weight' => trim($line[42]),
                'batch_number' => trim($line[43]),
                'last_6montth' => trim($line[44]),
                'working_days' => trim($line[45]),
                'status' => trim($line[46]),
                'state_consolidated' => trim($line[47]),
                'hq' => trim($line[48]),
                'sales_free_unit' => trim($line[49]),
                'company_id' => session('companyid'),
                'organization_id' => session('organization'),
                'location_id' => session('location'),
                'created_by' => session('id'),
                'created_at' => now(),
                'last_updated_by' => session('id'),
                'updated_at' => now(),
                'batch_date' => now()->format('Y-m-d'),
                'batch_status' => 'LOADED',
                'batch_name' => $batchName,
            ];

            if (count($rows) >= $batchSize) {
                DB::table('sd_primarydataupload_t')->insert($rows);
                $rows = [];
            }
        }

        if (!empty($rows)) {
            DB::table('sd_primarydataupload_t')->insert($rows);
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