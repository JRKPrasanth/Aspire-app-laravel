<?php

    namespace App\Http\Controllers;
    use App\Misemployee;
    use Illuminate\Http\Request;
	use Yajra\DataTables\DataTables;
    use DB;
    use Validator,Input, Redirect, Session;
    
    class MisemployeedetailController extends Controller
    {
         public $module="Misemployee";

            public function __construct() {
                
             // $this->data['urlmenu']=$this->indexs(); 
              $this->table="sd_mar_employee_detail_t";
              $this->model=new Misemployee;
              $this->data['pageModule']=\Request::route()->getName();
             
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

        $this->data['employee_detail'] = DB::table('sd_mar_employee_detail_t')
            ->select('sd_mar_employee_detail_t.*')
            ->get();
            $this->data['pageMethod'] = \Request::route()->getName();
        return view('misemployeedetail.table', $this->data);
    }
  //---- END ------
  
public function salesmisreportsdetildata(Request $request)
{

    if ($request->ajax()) {
        $data = \DB::table('sd_mar_employee_detail_t')
            ->select(['*']);
        return DataTables::of($data)
            ->rawColumns(['actions'])
            ->make(true);
    }
}
		
        public function Create($id = null)
    {
        
        if ($id == 0) {
            
            $this->data['employee_name'] = $this->jcustomselecttool("hr_employee_t", "first_name", "first_name", "", " and group_type=14");
            $this->data['desigination'] = $this->jcustomselecttool("m_job_title", "job_description", "job_description", "", " and (`job_title_name` LIKE '%manager%' OR job_title_name  LIKE '%Marketing%') AND job_description !=''");
            $table = \DB::table('sd_mar_employee_detail_t')->get();
            $this->data['data'] = $table;
            $this->data['row'] = (object)[];
            $this->data['row']->id = "";
            $this->data['pageMethod'] = "createmaremployeedetail";
            $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
             $this->data['linedata'] = array();
            return view('misemployeedetail.form', $this->data);
            
        } else {

            $table = \DB::table('sd_mar_employee_detail_t')->where('id', $id)->get();
            
            $this->data['employee_name'] = $this->jcustomselecttool("hr_employee_t", "first_name", "first_name", $table[0]->employee_name, " and group_type=14");
            $this->data['desigination'] = $this->jcustomselecttool("m_job_title", "job_description", "job_description", $table[0]->desigination, " and (`job_title_name` LIKE '%manager%' OR job_title_name  LIKE '%Marketing%') AND job_description !=''");
            $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
            $this->data['data'] = $table;
            $this->data['row'] = (object)[];

            $this->data['row']->zone = $table[0]->zone;
            $this->data['row']->region = $table[0]->region;
            $this->data['row']->state = $table[0]->state;
            $this->data['row']->hq_name = $table[0]->hq_name;

            $this->data['row']->id = $table[0]->id;
            $this->data['pageMethod'] = "salesmisreportsdetiledit";

            $tablelines = \DB::table('sd_mar_employee_detail_t')->where('id', $id)->get();
            $this->data['linedata'] = $tablelines;

        }

        return view('misemployeedetail.form', $this->data);
      }
    
    
    public function Save(Request $request)  
    { 
        try {
            
        // Get data from the request
        $employee_name = $request->input('employee_name');
        $active = $request->input('active');
        $desigination = $request->input('desigination');
        $created_by = \Session::get('id');
        $organization_id = \Session::get('organization');
        $location_id = \Session::get('loc_id');
        $company_id = \Session::get('companyid');

        // Check if bulk data exists
        if (!$request->has('bulk_zone')) {
            throw new \Exception('Bulk data missing');
        }

        // Get ID if provided
        $id = $request->input('id');

        // Iterate over bulk data
        for ($i = 0; $i < count($_POST['bulk_zone']); $i++) {   
            $data = [
                'employee_name' => $employee_name,
                'active' => $active,
                'desigination' => $desigination,
                'created_by' => $created_by,
                'organization_id' => $organization_id,
                'location_id' => $location_id,
                'company_id' => $company_id,
                'zone' => $_POST['bulk_zone'][$i],
                'region' => $_POST['bulk_region'][$i] ?? null,
                'state' => $_POST['bulk_state'][$i] ?? null,
                'hq_name' => $_POST['bulk_hq_name'][$i] ?? null
            ];

            if ($id) {
                // Update the existing record
                $misemployee = Misemployee::find($id);
                if ($misemployee) {
                    $misemployee->update($data);

                    // If active status is being updated, update all entries for this employee
                    if (!is_null($active)) {
                        Misemployee::where('employee_name', $employee_name)
                                   ->update(['active' => $active]);
                    }
                } else {
                    throw new \Exception('Record not found');
                }
            } else {
                // Create a new record
                Misemployee::create($data);
            }
        }

        // Return success response
        return response()->json(['status' => 'success', 'message' => 'Saved Successfully']);
    } catch (\Exception $e) {
        // Handle exceptions
        $errorMessage = $e->getMessage();
        return response()->json(['status' => 'error', 'message' => $errorMessage]);
    }
    }


    // delete function

    public function Delete(Request $request, $id = null)
    {
        if ($id === null) {
            return 2;
        }
        
        $query = \DB::table('sd_mar_employee_detail_t')->where('id', $id)->delete();

        if ($query) {
            return 1;
            
        } else {
            return 2;
        }
    }


}
