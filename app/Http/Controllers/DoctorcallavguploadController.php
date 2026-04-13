<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Primarydataupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DoctorcallavguploadController extends Controller
{

	public function __construct(){
        $this->data=array();
        
        $this->table="sd_docupload_t";
        $this->pageModule="docavgupload";
		$this->model=new Primarydataupload;
        $this->data['pageMethod']=\Request::route()->getName();
    }


    public function Index(Request $request)
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

        return view('primarydataupload.doctable', $this->data);
    }


    public function docavguploaddata(Request $request)
{

    if ($request->ajax()) {
        $data = \DB::table('sd_docupload_t')
            ->select(['*']);
        return DataTables::of($data)
            ->rawColumns(['actions'])
            ->make(true);
    }
}


public function docUploadexcel(Request $request)
{
    
    $file = $request->file('choosefile');

    if (!$file || $file->getClientOriginalExtension() !== 'csv') {
        return response()->json(['status' => 'error', 'message' => 'Please upload a valid CSV file!']);
    }

    DB::beginTransaction();

    try {
            
        DB::table('sd_docupload_t')->delete();

        $path = $file->getRealPath();
        $fileObject = new \SplFileObject($path);
        $fileObject->setFlags(\SplFileObject::READ_CSV);
        $fileObject->setCsvControl(',');

        $batchData = [];
        $batchSize = 1000;
        $rowNumber = 0;
        
        foreach ($fileObject as $line) {
            
            if ($line === [null] || $rowNumber === 0) {  
                $rowNumber++;
                continue;
            }

            if (count($line) < 10) { // you only use up to index 9
                $rowNumber++;
                continue; 
            }
                
            $batchData[] = [
                'employee_name' => trim($line[1]),
                'designation' => trim($line[2]),
                'zone' => trim($line[3]),
                'region' => trim($line[4]),
                'state' => trim($line[5]),
                'hq_name' => trim($line[6]),
                'month' => trim($line[7]),
                'doctor_coverage' => trim($line[8]),
                'doctor_call_average' => trim($line[9]),
                'company_id' => session('companyid'),
                'location_id' => session('location'),
                'created_by' => session('id'),
                'created_at' => now(),
                'last_updated_by' => session('id'),
                'updated_at' => now(),
            ];

            if (count($batchData) >= $batchSize) {
                DB::table('sd_docupload_t')->insert($batchData);
                $batchData = [];
            }

            $rowNumber++;
        }

        // Insert remaining data
        if (!empty($batchData)) {
            DB::table('sd_docupload_t')->insert($batchData);
        }

        DB::commit();

        return response()->json(['status' => 'success', 'message' => 'Table truncated and data uploaded successfully!']);

    } catch (\Exception $e) {
      
        DB::rollBack();
        return response()->json([
            'status' => 'error',
            'message' => 'Upload failed: ' . $e->getMessage()
        ]);
    }
}




}