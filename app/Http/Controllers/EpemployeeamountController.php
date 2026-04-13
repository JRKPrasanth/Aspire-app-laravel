<?php

    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use DB;
    use Validator,Input, Redirect, Session;
    use Yajra\DataTables\DataTables;



    class EpemployeeamountController extends Controller
    {

   
    
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

        $this->data['employee_detail'] = DB::table('hr_ot_amount_t')
            ->select('hr_ot_amount_t.*')
            ->get();
            $this->data['pageMethod'] = \Request::route()->getName();
		
        return view('epamountentry.table', $this->data);
    }
  //---- END ------ 
  
		
  public function epamountentrydata(Request $request)
	{


		$SQL = "SELECT hr_ot_amount_t.*,m_department_lines_t.sub_department_name,m_job_title.job_title_name FROM `hr_ot_amount_t`
LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = hr_ot_amount_t.department
LEFT JOIN m_job_title ON m_job_title.job_title_id = hr_ot_amount_t.job_tittle where 1=1";
	

		
		$data = \DB::select($SQL);
		 return DataTables::of($data)->make(true);

	}

        public function Create($id = null)
    {
			
		//	dd($id);
        
        if ($id == 0) {
            
            $this->data['department'] = $this->jcustomselecttool("m_department_lines_t", "department_line_id", "sub_department_name", "", "");
            $this->data['desigination'] = $this->jcustomselecttool("m_job_title", "job_title_id", "job_title_name", "", "");
            $table = \DB::table('hr_ot_amount_t')->get();
            $this->data['data'] = $table;
            $this->data['row'] = (object)[];
            $this->data['row']->id = "";
			$this->data['row']->ot_type = '';
			$this->data['linedata'] = array();
            $this->data['pageMethod'] = "createepamountentry";
            $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
            
    
            
        } else {

            $table = \DB::table('hr_ot_amount_t')->where('id', $id)->get();
            
            $this->data['department'] = $this->jcustomselecttool("m_department_lines_t", "department_line_id", "sub_department_name", $table[0]->department, "");
            $this->data['desigination'] = $this->jcustomselecttool("m_job_title", "job_title_id", "job_title_name", $table[0]->job_tittle, "");
            $this->data['created_by'] = $this->jCombologin('tb_users', 'id', 'username', \Session::get('id'));
            $this->data['data'] = $table;
            $this->data['row'] = (object)[];

            $this->data['row']->ot_type = $table[0]->ot_type;
            $this->data['row']->ot_hrs = $table[0]->ot_hrs;
            $this->data['row']->ot_amount = $table[0]->ot_amount;
            $this->data['row']->food_amount = $table[0]->food_amount;

            $this->data['row']->id = $table[0]->id;
            $this->data['pageMethod'] = "salesmisreportsdetiledit";

            $tablelines = \DB::table('hr_ot_amount_t')->where('id', $id)->get();
            $this->data['linedata'] = $tablelines;

        }

        return view('epamountentry.form', $this->data);
      }
    

public function Save(Request $request)  
{ 
    $id = $request->input('id');
    $bulk_ot_hrs = $request->input('bulk_ot_hrs');
    $bulk_ot_amt = $request->input('bulk_ot_amt');
    $bulk_food_amount = $request->input('bulk_food_amount');
    $department = $request->input('department');
    $desigination = $request->input('desigination');
    $ottype = $request->input('ottype');

    if ($id == NULL) {
                // INSERT NEW ROWS
        $baseLineData = [
            'created_by' => \Session::get('id'),
            'created_at' => date('Y-m-d'),
            'organization_id' => \Session::get('organization'),
            'location_id' => \Session::get('location'),
            'company_id' => \Session::get('companyid'),
            'last_updated_by' => \Session::get('id'),
            'updated_at' => date('Y-m-d'),
            'department' => $department,
            'job_tittle' => $desigination,
            'ot_type' => $ottype,
            'active' => "Yes",
        ];

        $insertData = [];

        foreach ($bulk_ot_hrs as $key => $ot_hrs) {
            if (!empty($ot_hrs) && isset($bulk_ot_amt[$key], $bulk_food_amount[$key])) {
                $insertData[] = array_merge($baseLineData, [
                    'ot_hrs' => $ot_hrs, 
                    'ot_amount' => $bulk_ot_amt[$key], 
                    'food_amount' => $bulk_food_amount[$key]
                ]);
            }
        }

        if (!empty($insertData)) {
            \DB::table('hr_ot_amount_t')->insert($insertData);
        }

        return response()->json(['status' => 'success', 'message' => 'Saved Successfully']);
       
    } else {
        
 // UPDATE SINGLE ROW
        $updateData = [
            'ot_hrs' => $bulk_ot_hrs[0] ?? 0, 
            'ot_amount' => $bulk_ot_amt[0] ?? 0, 
            'food_amount' => $bulk_food_amount[0] ?? 0, 
            'department' => $department,
            'job_tittle' => $desigination,
            'ot_type' => $ottype,
            'last_updated_by' => \Session::get('id'),
            'updated_at' => date('Y-m-d'),
        ];

        \DB::table('hr_ot_amount_t')->where('id', $id)->update($updateData);

        return response()->json(['status' => 'success', 'message' => 'Updated Successfully']);
    }
}



    // delete function

    public function Delete(Request $request, $id = null)
    {
        
       
        if ($id === null) {
            return 2;
        }
        
        $query = \DB::table('hr_ot_amount_t')->where('id', $id)->delete();

        if ($query) {
            return 1;
            
        } else {
            return 2;
        }
    }


}
