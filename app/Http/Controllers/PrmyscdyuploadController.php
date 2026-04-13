<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\prmyscdyupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrmyscdyuploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function __construct(){
        $this->data=array();
        
        $this->table="sd_prmyscdyupload_t";
        $this->pageModule="prmyscdyupload";
		$this->model=new prmyscdyupload;
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
                $filter = 'AND sd_prmyscdyupload_t.batch_name = "' . $_GET['batchname'] . '"';
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

        return view('prmyscdyupload.table', $this->data);
    }
    
    
   public function prmyscdyuploadcreate($id=null)
    {
        if(isset($id)){

      	     $prmyscdydbupload=prmyscdyupload::find($id);
            $this->data['id'] = $id;
            $this->data['pagemode'] = "edit";
            $table = \DB::table('sd_prmyscdyupload_t')->where('prmyscdy_upload_id',$id)->get();
            $this->data['row'] = $table[0];
            $this->data['type_of_data']=$table[0]->type_of_data;
            $this->data['currrent_reporting_MGR']=$table[0]->currrent_reporting_MGR;
            $this->data['state']=$table[0]->state;
            $this->data['region']=$table[0]->region;
            $this->data['zone']=$table[0]->zone;
            $this->data['c_year']=$table[0]->c_year;
            $this->data['f_year']=$table[0]->f_year;
            $this->data['month']=$table[0]->month;
            $this->data['month_y']=$table[0]->month_y;
            $this->data['product_pack_name']=$this->jCombologin("sd_prmyscdyupload_t","product_pack_name","product_pack_name",$table[0]->product_pack_name);
            $this->data['product_name']=$this->jCombologin("sd_prmyscdyupload_t","product_name","product_name",$table[0]->product_name);
            $this->data['pack_size']=$table[0]->pack_size;
            $this->data['product_kit']=$table[0]->product_kit;
            $this->data['division']=$table[0]->division;
            $this->data['HQ_name']=$table[0]->HQ_name;
            $this->data['area']=$table[0]->area;
            $this->data['type_d_s']=$table[0]->type_d_s;
            $this->data['stockist_dist_name']=$table[0]->stockist_dist_name;
            $this->data['sales_kgs_lts']=$table[0]->sales_kgs_lts;
            $this->data['active_inactive']=$table[0]->active_inactive;
            $this->data['employee_name']=$table[0]->employee_name;
            $this->data['product_category']=$table[0]->product_category;
            $this->data['type']=$table[0]->type;
            $this->data['purchase_stock']=$table[0]->purchase_stock;
            $this->data['purchase_stock_value']=$table[0]->purchase_stock_value;
            $this->data['sales_unit']=$table[0]->sales_unit;
            $this->data['sales_value']=$table[0]->sales_value;
            $this->data['closing_stock']=$table[0]->closing_stock;
            $this->data['closing_stock_value']=$table[0]->closing_stock_value;
            $this->data['free_stock']=$table[0]->free_stock;
            $this->data['free_stock_value']=$table[0]->free_stock_value;
            $this->data['sales_free_units']=$table[0]->sales_free_units;
            $this->data['rate']=$table[0]->rate;
            $this->data['concatenate']=$table[0]->concatenate;
            $this->data['extra_offer']=$table[0]->extra_offer;
            $this->data['camp_product']=$table[0]->camp_product;
            $this->data['note']=$table[0]->note;
            $this->data['free_value_classical_sale_value']=$table[0]->free_value_classical_sale_value;
            $this->data['batch_name']=$table[0]->batch_name;
            $this->data['batch_status']=$table[0]->batch_status;
            $this->data['batch_date']=$table[0]->batch_date;
           
        }else{
            $prmyscdydbuploads=\DB::connection()->getSchemaBuilder()->getColumnListing("sd_prmyscdyupload_t");
            $prmyscdydbupload= array();
            foreach($prmyscdydbuploads as $key=>$val)
            {
                $prmyscdydbupload[$val]="";
            }
        }
     
        
	    $this->data['prmyscdydbuploaddata']=$prmyscdydbupload;
		

        return view('prmyscdyupload.form',$this->data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function prmyscdyuploadsave(Request $request)
    {

        $prmyscdydbupload=new prmyscdyupload();
        
            $data['type_of_data']=$_POST['type_of_data'];
            $data['currrent_reporting_MGR']=$_POST['currrent_reporting_MGR'];
            $data['state']=$_POST['state'];
            $data['region']=$_POST['region'];
            $data['zone']=$_POST['zone'];
            $data['c_year']=$_POST['c_year'];
            $data['f_year']=$_POST['f_year'];
            $data['month']=$_POST['month'];
            $data['month_y']=$_POST['month_y'];
            $data['product_pack_name']=$_POST['product_pack_name'];
            $data['product_name']=$_POST['product_name'];
            $data['pack_size']=$_POST['pack_size'];
            $data['product_kit']=$_POST['product_kit'];
            $data['division']=$_POST['division'];
            $data['HQ_name']=$_POST['HQ_name'];
            $data['area']=$_POST['area'];
            $data['type_d_s']=$_POST['type_d_s'];
            $data['stockist_dist_name']=$_POST['stockist_dist_name'];
            $data['sales_kgs_lts']=$_POST['sales_kgs_lts'];
            $data['active_inactive']=$_POST['active_inactive'];
            $data['employee_name']=$_POST['employee_name'];
            $data['product_category']=$_POST['product_category'];
            $data['type']=$_POST['type'];
            $data['purchase_stock']=$_POST['purchase_stock'];
            $data['purchase_stock_value']=$_POST['purchase_stock_value'];
            $data['sales_unit']=$_POST['sales_unit'];
            $data['sales_value']=$_POST['sales_value'];
            $data['closing_stock']=$_POST['closing_stock'];
            $data['closing_stock_value']=$_POST['closing_stock_value'];
            $data['free_stock']=$_POST['free_stock'];
            $data['free_stock_value']=$_POST['free_stock_value'];
            $data['sales_free_units']=$_POST['sales_free_units'];
            $data['rate']=$_POST['rate'];
            $data['concatenate']=$_POST['concatenate'];
            $data['extra_offer']=$_POST['extra_offer'];
            $data['camp_product']=$_POST['camp_product'];
            $data['note']=$_POST['note'];
            $data['free_value_classical_sale_value']=$_POST['free_value_classical_sale_value'];
            
        	$data['batch_name']=$_POST['batch_name'];
			$data['batch_date']=$_POST['batch_date'];
			$data['batch_status']= $_POST['batch_status'];
			$data['company_id']=\Session::get('companyid');
			$data['organization_id']=\Session::get('organization');
			$data['location_id']=\Session::get('location');
			$data['last_updated_by']=\Session::get('id');
			$data['updated_at']=date('Y-m-d H:i:s');
			$id=$_POST['prmyscdy_upload_id'];
       	prmyscdyupload::find($id)->update($data);
		  return response()->json(array('status' => 'success', 'message' => 'Updated successfully!!','id'=>$id));
    }    
    

public function getprmyscdyuploadData(Request $request)
{

    if ($request->ajax()) {
        $data = \DB::table('sd_prmyscdyupload_t')
            ->select(['*']);
        return DataTables::of($data)
            ->rawColumns(['actions'])
            ->make(true);
    }
}
		/*end*/
	/* purpose:To Upload excel*/	
public function prmyscdyuploadexcel(Request $request)
{
    $file = $request->file('choosefile');

    if (!$file || strtolower($file->getClientOriginalExtension()) !== 'csv') {
        return response()->json([
            'status' => 'error',
            'message' => 'Please upload a valid CSV file!'
        ]);
    }

    ini_set('memory_limit', '1024M');
    ini_set('max_execution_time', 0);

    $path = $file->getRealPath();
    $fileObject = new \SplFileObject($path);
    $fileObject->setFlags(\SplFileObject::READ_CSV);
    $fileObject->setCsvControl(',');

    $batchData = [];
    $batchSize = 500; // increased for better performance
    $rowNumber = 0;
    $months = [];

    DB::beginTransaction();

    try {

        // FIRST LOOP → Collect months
        foreach ($fileObject as $line) {

            if ($line === [null]) continue;
            if ($rowNumber == 0) { $rowNumber++; continue; }
            if (count($line) < 38) { $rowNumber++; continue; }

            $month_y = trim($line[8]);
            if ($month_y != '') {
                $months[] = $month_y;
            }

            $rowNumber++;
        }

        $months = array_unique($months);
        
        // DELETE ALL MONTHS IN ONE QUERY
        if (!empty($months)) {
            DB::table('sd_prmyscdyupload_t')
                ->whereIn('month_y', $months)
                ->delete();
        }

        // RESET FILE POINTER
        $fileObject->rewind();
        $rowNumber = 0;

        // SECOND LOOP → Insert data
        foreach ($fileObject as $line) {

            if ($line === [null]) continue;
            if ($rowNumber == 0) { $rowNumber++; continue; }
            if (count($line) < 38) { $rowNumber++; continue; }

            $month_y = trim($line[8]);

            $batchData[] = [
                'type_of_data' => trim($line[0]),
                'currrent_reporting_MGR' => trim($line[1]),
                'state' => trim($line[2]),
                'region' => trim($line[3]),
                'zone' => trim($line[4]),
                'c_year' => trim($line[5]),
                'f_year' => trim($line[6]),
                'month' => trim($line[7]),
                'month_y' => $month_y,
                'product_pack_name' => trim($line[9]),
                'product_name' => trim($line[10]),
                'pack_size' => trim($line[11]),
                'sales_unit' => trim($line[12]),
                'sales_value' => trim($line[13]),
                'HQ_name' => trim($line[14]),
                'area' => trim($line[15]),
                'product_kit' => trim($line[16]),
                'division' => trim($line[17]),
                'type_d_s' => trim($line[18]),
                'stockist_dist_name' => trim($line[19]),
                'sales_kgs_lts' => trim($line[20]),
                'active_inactive' => trim($line[21]),
                'free_stock' => trim($line[22]),
                'employee_name' => trim($line[23]),
                'product_category' => trim($line[24]),
                'type' => trim($line[25]),
                'closing_stock' => trim($line[26]),
                'closing_stock_value' => trim($line[27]),
                'purchase_stock' => trim($line[28]),
                'purchase_stock_value' => trim($line[29]),
                'sales_free_units' => trim($line[30]),
                'rate' => trim($line[31]),
                'free_stock_value' => trim($line[32]),
                'concatenate' => trim($line[33]),
                'extra_offer' => trim($line[34]),
                'camp_product' => trim($line[35]),
                'note' => trim($line[36]),
                'free_value_classical_sale_value' => trim($line[37]),
                'company_id' => session('companyid'),
                'organization_id' => session('organization'),
                'location_id' => session('location'),
                'created_by' => session('id'),
                'created_at' => now(),
                'last_updated_by' => session('id'),
                'updated_at' => now(),
                'batch_date' => now()->format('Y-m-d'),
                'batch_status' => 'LOADED',
                'batch_name' => $request->input('batch_name'),
            ];

                if (count($batchData) >= $batchSize) {

                    $values = [];

                    foreach ($batchData as $row) {
                        $values[] = "('" .
                            implode("','", array_map('addslashes', $row)) .
                        "')";
                    }

                    $columns = implode(',', array_keys($batchData[0]));

                    $sql = "INSERT INTO sd_prmyscdyupload_t ($columns) VALUES " . implode(',', $values);

                    DB::statement($sql);

                    $batchData = [];
                }

            $rowNumber++;
        }

        if (!empty($batchData)) {
            DB::table('sd_prmyscdyupload_t')->insert($batchData);
        }

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Your data loaded successfully!'
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}

	

   
   
}
