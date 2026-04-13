<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Primarydataupload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MismanagerchangeController extends Controller
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


    $this->data['managers'] = \DB::select("SELECT DISTINCT current_reporting_manager AS name from `sd_secondarydataupload_t`");
    $this->data['hqs'] = \DB::select("SELECT DISTINCT hq_name from  `sd_secondarydataupload_t`");

    return view('managerchange.secondary', $this->data);

    }

    // secondary update
public function save(Request $request)
{
    $from_manager = $request->input('from_manager');
    $to_manager   = $request->input('new_manager');
    $hqs          = $request->input('hq'); 

    // Validation (Recommended)
    if (empty($from_manager) || empty($to_manager) || empty($hqs)) {
        return response()->json([
            'status' => 'error',
            'message' => 'All fields are required'
        ]);
    }

    // Update first table
    $update = \DB::table('sd_secondarydataupload_t')
        ->where('current_reporting_manager', $from_manager)
        ->whereIn('hq_name', $hqs)
        ->update([
            'current_reporting_manager' => $to_manager
        ]);

    // Update second table
    $update1 = \DB::table('sd_prmyscdyupload_t')
        ->where('currrent_reporting_MGR', $from_manager)
        ->whereIn('HQ_name', $hqs)
        ->update([
            'currrent_reporting_MGR' => $to_manager
        ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Updated Successfully'
    ]);
}



}