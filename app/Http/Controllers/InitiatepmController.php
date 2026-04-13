<?php

namespace App\Http\Controllers;

use App\initiatepm;
use App\Monthlycheck;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Notifiable;
use DB,Session;
use DateTime,File;
use yajra\datatables\datatables;

class InitiatepmController  extends Controller
{
public function __construct()
    {
        $this->data=array();
        $this->model    = new initiatepm();
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        $this->data['pageModule']='initiatepm';
        $this->table="machine_pm_detail_t";   
        $this->table1="pm_monthly_checking_tbl";
        $this->middleware('auth');
         $this->model=new Monthlycheck;
        $this->data['urlmenu']=$this->indexs(); 
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

        $this->data['pageMethod']="initiatepm";
        return view('initiatepm.table',$this->data);
    }
     public function pmclearanceindex(Request $request)
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

        $this->data['pageMethod']="pmclearance";
        return view('initiatepm.pmclearancetable',$this->data);
    }
        public function pmagencyallocationindex(Request $request)
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

        $this->data['pageMethod']="pmagencyallocation";
        return view('initiatepm.agencytable',$this->data);
    }
        public function pmmonthlycheckindex(Request $request)
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

        return view('initiatepm.pmmonthtable',$this->data);
    }
   
/*Purcpose for pm Initiate grid*/    
    public function initiatepmData(Request $request)   
    {
         

    // Date calculations
    $today = now();
    $addDate = $today->copy()->addDays(30)->format('Y-m-d');
    $subDate = $today->copy()->subDays(30)->format('Y-m-d');

		
    $data = \DB::table('machine_pm_detail_t')
        ->leftJoin('tb_users', 'tb_users.id', '=', 'machine_pm_detail_t.created_by')
        ->leftJoin('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'machine_pm_detail_t.machine_id')
        ->leftJoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'machine_pm_detail_t.department_id')
        ->select([
            'machine_pm_detail_t.*',
            \DB::raw("CASE WHEN machine_pm_detail_t.move_status = 1 THEN 'PM NOT DONE' ELSE '' END as move_stat"),
            'tb_users.username',
            'w_machine_hdr_t.machine_name',
            \DB::raw("CONCAT(m_department_lines_t.sub_department_code, '-', m_department_lines_t.sub_department_name) as department_name"),
        ])
        ->where('machine_pm_detail_t.initiate_status', 0)
        ->where(function ($query) use ($subDate, $addDate) {
            $query->whereBetween('machine_pm_detail_t.actual_pm_date', [$subDate, $addDate])
                  ->orWhereBetween('machine_pm_detail_t.postponed_date', [$subDate, $addDate]);
        })
        ->orderBy('machine_pm_detail_t.actual_pm_date', 'desc')
        ->get();
		
        return DataTables::of($data)->make(true);

    }
	
    /*end*/
	

public function pmclearanceData(Request $request)
{
    if ($request->ajax()) {
        $u_id = \Session::get('id');
        $loc = \Session::get('loc_id');
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');
        $emp_id = \Session::get('empid'); 

        // Get initiate_pm_ids where current user is in clearance list
        $userPmIds = \DB::table('machine_pm_detail_t')
            ->where('status', 0)
            ->where('initiate_status', 1)
            ->get()
            ->filter(function ($item) use ($u_id) {
                $clearance = json_decode($item->user_clearance_by, true);
                return is_array($clearance) && in_array($u_id, $clearance);
            })
            ->pluck('initiate_pm_id')
            ->toArray();

        // Base query
        $query = \DB::table('machine_pm_detail_t')
            ->leftJoin('tb_users', 'tb_users.id', '=', 'machine_pm_detail_t.created_by')
            ->leftJoin('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'machine_pm_detail_t.machine_id')
            ->leftJoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'machine_pm_detail_t.department_id')
            ->select([
                'machine_pm_detail_t.*',
                'tb_users.username',
                'w_machine_hdr_t.machine_name',
                \DB::raw("CONCAT(m_department_lines_t.sub_department_code, '-', m_department_lines_t.sub_department_name) as department_name"),
            ])
            ->where('machine_pm_detail_t.status', 0)
            ->where('machine_pm_detail_t.initiate_status', 1);

        // Apply filters
        if (!empty($userPmIds)) {
            $query->whereIn('machine_pm_detail_t.initiate_pm_id', $userPmIds);
        } elseif (!in_array($groupname, ['1', '4']) && $emp_id != '151') {
            // If not admin/special access, block all
            return DataTables::of(collect())->make(true);
        }

        return DataTables::of($query)
            ->editColumn('user_clearance_by', function ($row) {
                $names = [];
                $ids = json_decode($row->user_clearance_by, true);
                if (is_array($ids)) {
                    $names = \DB::table('tb_users')
                        ->whereIn('id', $ids)
                        ->pluck('first_name')
                        ->toArray();
                }
                return implode(', ', $names);
            })
            ->make(true);
    }
}
	

  /*Purcpose for agency allocation grid*/    
public function pmmonthlycheckData(Request $request)
{
    // Step 1: Fetch the records using Query Builder
    $results = \DB::table('machine_pm_detail_t')
        ->leftJoin('tb_users', 'tb_users.id', '=', 'machine_pm_detail_t.created_by')
        ->leftJoin('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'machine_pm_detail_t.machine_id')
        ->leftJoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'machine_pm_detail_t.department_id')
        ->leftJoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 'machine_pm_detail_t.cleared_by')
        ->select([
            'machine_pm_detail_t.status',
            'machine_pm_detail_t.actual_pm_date',
            'machine_pm_detail_t.user_clearance_by',
            'machine_pm_detail_t.initiate_pm_id',
            'machine_pm_detail_t.pm_no',
            'tb_users.username',
            'hr_employee_t.first_name',
            'w_machine_hdr_t.machine_name',
            \DB::raw("CONCAT(m_department_lines_t.sub_department_code, '-', m_department_lines_t.sub_department_name) as department_name"),
            \DB::raw("0 as pm_checking_id")
        ])
        ->where('machine_pm_detail_t.status', 2)
        ->get();

    // Step 2: Process `user_clearance_by` JSON and fetch usernames
    foreach ($results as $k => $record) {
        $jsonField = $record->user_clearance_by;
        $clearanceIds = json_decode($jsonField, true);

        if (!empty($clearanceIds)) {
            $userNames = \DB::table('tb_users')
                ->whereIn('id', $clearanceIds)
                ->pluck('first_name')
                ->toArray();

            $record->user_clearance_by = implode(', ', $userNames);
        } else {
            $record->user_clearance_by = '';
        }
    }

		return DataTables::of($results)->make(true);
}
    /*end*/
	/*deepika purpose:pmchecklist approval*/
	public function pmmonthlycheckapprovalData()   
{
        $loc=\Session::get('location_id');
        $compy=\Session::get('companyid');   
        $groupname=\Session::get('groupname');
        $emp_id = \Session::get('id');
        $wh='';
        if($groupname=='1' || $groupname=='4' || $emp_id == '151'){
            $wh.=" ";
        }else{
            $wh.="  and machine_pm_detail_t.cleared_by = $emp_id";
        }

        $SQL = "select * from(SELECT pm_monthly_checking_tbl.*,m_department_lines_t.sub_department_name as department_name,machine_pm_detail_t.pm_no,machine_pm_detail_t.actual_pm_date,hr_employee_t.first_name,w_machine_hdr_t.machine_name from pm_monthly_checking_tbl left join machine_pm_detail_t on(machine_pm_detail_t.initiate_pm_id=pm_monthly_checking_tbl.initate_pm_id) left join w_machine_hdr_t on(w_machine_hdr_t.machine_hdr_id=machine_pm_detail_t.machine_id) left join m_department_lines_t on(m_department_lines_t.department_line_id=machine_pm_detail_t.department_id) LEFT JOIN hr_employee_t ON (hr_employee_t.employee_id = machine_pm_detail_t.cleared_by) where 1=1 and pm_monthly_checking_tbl.approval_status!='APPROVED' $wh) as v1";

        $result = \DB::select($SQL);

		return DataTables::of($result)->make(true);
		
    }

     /*Purcpose for MONTHLY CHECK grid*/    
public function pmagencyallocationData(Request $request)
{
    // Add your dynamic conditions here
    $whConditions = []; // optional array of conditions
    $query = \DB::table('machine_pm_detail_t')
        ->leftJoin('tb_users', 'tb_users.id', '=', 'machine_pm_detail_t.created_by')
        ->leftJoin('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'machine_pm_detail_t.machine_id')
        ->leftJoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'machine_pm_detail_t.department_id')
        ->select([
            'machine_pm_detail_t.*',
            'tb_users.username',
            'w_machine_hdr_t.machine_name',
            'm_department_lines_t.sub_department_code as department_name',
        ])
        ->where('machine_pm_detail_t.status', 1);

    // Optional: apply dynamic $wh conditions
    // Example: if you have a date range or department filter
    // $query->whereBetween('actual_pm_date', [$from, $to]);

    $results = $query->get();

    // Post-process each record for user_clearance_by
    foreach ($results as $record) {
        $ids = json_decode($record->user_clearance_by, true);

        if (!empty($ids)) {
            // Fetch first names of users
            $userNames = \DB::table('tb_users')
                ->whereIn('id', $ids)
                ->pluck('first_name')
                ->toArray();

            // Assign comma-separated names back
            $record->user_clearance_by = implode(', ', $userNames);
        } else {
            $record->user_clearance_by = '';
        }
    }

    return DataTables::of($results)->make(true);
}
	
    /*end*/
     public function create($id=null)
    {
        $row= DB::getSchemaBuilder()->getColumnListing('machine_pm_detail_t');
        $this->data['row']=$row;
       $pm=\DB::table('machine_pm_detail_t')->where('initiate_pm_id',$id)->get();
       $this->data['initiate_pm_id']=$pm[0]->initiate_pm_id;
       $this->data['pm_no']=$pm[0]->pm_no;
       $this->data['initiate_date']=date('d-m-Y');
       $this->data['actual_pm_date']=$pm[0]->actual_pm_date;
        $this->data['machine_id'] = $this->jCombologin('w_machine_hdr_t', 'machine_hdr_id', 'machine_code|machine_name', $pm[0]->machine_id);      
        $this->data['department_id'] = $this->jCombologin('m_department_lines_t', 'department_line_id', 'sub_department_code|sub_department_name', $pm[0]->department_id);      
    //    $this->data['user_clearance_by'] = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name','');      
    $this->data['user_clearance_by'] = $this->jcustomselect('hr_employee_t','employee_id','employee_number|first_name','',' and (group_type=7 or group_type=8 or group_type=10 or group_type=11 or group_type=12)');
//        dd($this->data);
        return view('initiatepm.form',$this->data);
    }
       public function pmclearancecreate($id=null)
    {
        $row= DB::getSchemaBuilder()->getColumnListing('machine_pm_detail_t');
        $this->data['row']=$row;
       $pm=\DB::table('machine_pm_detail_t')->where('initiate_pm_id',$id)->get();
       $this->data['initiate_pm_id']=$pm[0]->initiate_pm_id;
       $this->data['pm_no']=$pm[0]->pm_no;
       $this->data['initiate_date']=$pm[0]->initiate_date;
        $this->data['machine_id'] = $this->jCombologin('w_machine_hdr_t', 'machine_hdr_id', 'machine_code|machine_name', $pm[0]->machine_id);     
        $this->data['department_id'] = $this->jCombologin('m_department_lines_t', 'department_line_id', 'sub_department_code|sub_department_name', $pm[0]->department_id);      
        $user_clear=json_decode($pm[0]->user_clearance_by);
        $user_clear_id="";
        foreach($user_clear as $k=>$v){
           $user_clear_id.=$v.","; 
        }
        $user_clearance_by=rtrim($user_clear_id,",");
        
        $this->data['user_clearance_by'] = $this->jcustommultiselect('hr_employee_t', 'employee_id', 'employee_number|first_name',$user_clearance_by,'and employee_id in ('.$user_clearance_by.')');      
        
        return view('initiatepm.pmclearanceform',$this->data);
    }
      public function pmagencyallocationcreate($id=null)
    {
        $row= DB::getSchemaBuilder()->getColumnListing('machine_pm_detail_t');
        $this->data['row']=$row;
       $pm=\DB::table('machine_pm_detail_t')->where('initiate_pm_id',$id)->get();
       $this->data['initiate_pm_id']=$pm[0]->initiate_pm_id;
       $this->data['pm_no']=$pm[0]->pm_no;
       $this->data['initiate_date']=$pm[0]->initiate_date;
       $this->data['change_date']=$pm[0]->change_date;
       $this->data['shift_timing']=$pm[0]->shift_timing;
       $this->data['postponed_date']=$pm[0]->postponed_date;
        $this->data['machine_id'] = $this->jCombologin('w_machine_hdr_t', 'machine_hdr_id', 'machine_code|machine_name', $pm[0]->machine_id);         
        $this->data['agency_allocation'] = $this->jCombologin('ma_agency_t', 'agency_id', 'agency_name', '');      
        $this->data['department_id'] = $this->jCombologin('m_department_lines_t', 'department_line_id', 'sub_department_code|sub_department_name', $pm[0]->department_id);      
        $user_clear=json_decode($pm[0]->user_clearance_by);
        $user_clear_id="";
        foreach($user_clear as $k=>$v){
           $user_clear_id.=$v.","; 
        }
        $user_clearance_by=rtrim($user_clear_id,",");
        
        $this->data['user_clearance_by'] = $this->jcustommultiselect('hr_employee_t', 'employee_id', 'employee_number|first_name',$user_clearance_by,'and employee_id in ('.$user_clearance_by.')');      
        
        return view('initiatepm.agencyallocationform',$this->data);
    }
     public function pmmonthlycheckcreate($id=null)
    {
		 if(isset($_GET['source'])){
           
			if($_GET['source']=="approve"){
       
			$pmcheck=\DB::table('pm_monthly_checking_tbl')->where('pm_checking_id',$id)->get();
			$this->data['row']=$pmcheck;	
				 $pm=\DB::table('machine_pm_detail_t')->where('initiate_pm_id',$pmcheck[0]->initate_pm_id)->get();
       $this->data['initiate_pm_id']=$pmcheck[0]->initate_pm_id;
       $this->data['pm_no']=$pm[0]->pm_no;
				 $this->data['pm_checking_id']=$pmcheck[0]->pm_checking_id;
       $this->data['initiate_date']=$pm[0]->initiate_date;
       $this->data['change_date']=$pm[0]->change_date;
       $this->data['shift_timing']=$pm[0]->shift_timing;
       $this->data['pm_sdate']=$pm[0]->pm_start_time;
       $this->data['pm_edate']=$pm[0]->pm_end_time;
       $this->data['postponed_date']=$pm[0]->postponed_date;
       $this->data['allocation_type']=$pm[0]->allocation_type;
        $this->data['machine_id'] = $this->jCombologin('w_machine_hdr_t', 'machine_hdr_id', 'machine_code|machine_name', $pmcheck[0]->machine_id);        
        if($pm[0]->allocation_type=="agency"){        
        $this->data['agency_allocation'] = $this->jCombologin('ma_agency_t', 'agency_id', 'agency_name', $pm[0]->allocated_agency);
 }else{
      $this->data['agency_allocation'] = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', $pm[0]->allocated_agency);
 }   
        $this->data['department_id'] = $this->jCombologin('m_department_lines_t', 'department_line_id', 'sub_department_code|sub_department_name', $pmcheck[0]->department_id);      
        $user_clear=json_decode($pm[0]->user_clearance_by);
        $user_clear_id="";
				if($user_clear!=null){
        foreach($user_clear as $k=>$v){
           $user_clear_id.=$v.","; 
        }
        $user_clearance_by=rtrim($user_clear_id,",");
					 $this->data['user_clearance_by'] = $this->jcustommultiselect('hr_employee_t', 'employee_id', 'employee_number|first_name',$user_clearance_by,'and employee_id in ('.$user_clearance_by.')');      
				}else{
			$user_clearance_by="";	
					 $this->data['user_clearance_by'] = $this->jcustommultiselect('hr_employee_t', 'employee_id', 'employee_number|first_name',$user_clearance_by,'');      
				}
				 $freq=\DB::select("select * from frequency_tbl where frequency_name='".$pm[0]->frequency_id."'");
       
          $this->data['check_details']=$checkdetails=\DB::select("SELECT checklist_tbl.checklist_id,checklist_tbl.terms,checklist_tbl.file,checklist_tbl.checklist_name from m_checklist_hrd_tbl left join checklist_lines_tbl on checklist_lines_tbl.checklist_hrd_id=m_checklist_hrd_tbl.checklist_hrd_id left join checklist_tbl on checklist_tbl.checklist_id=checklist_lines_tbl.checklist_id where m_checklist_hrd_tbl.machine_id='".$pmcheck[0]->machine_id."' and m_checklist_hrd_tbl.department_id='".$pmcheck[0]->department_id."' and m_checklist_hrd_tbl.frequency_id='".$freq[0]->frequency_id."'");

        $observation=json_decode($pmcheck[0]->observation);
        $status=json_decode($pmcheck[0]->status);
        $remarks=json_decode($pmcheck[0]->remarks);
		$this->data['pagemode']="edit";
				foreach($checkdetails as $key=>$values){
					if(isset($observation[$key])){
				 $this->data['check_details'][$key]->observation=$observation[$key];
					}else{
					$this->data['check_details'][$key]->observation="";	
					}
						if(isset($remarks[$key])){
				 $this->data['check_details'][$key]->remarks=$remarks[$key];	
						}else{
				 $this->data['check_details'][$key]->remarks="";			
						}
						if(isset($status[$key])){
				 $this->data['check_details'][$key]->status=$status[$key];	
						}else{
					 $this->data['check_details'][$key]->status="";		
						}
				}
			
			}
		 }else{
  
	   $row= DB::getSchemaBuilder()->getColumnListing('machine_pm_detail_t');
        $this->data['row']=$row;
			 	$this->data['pagemode']="create";
       $pm=\DB::table('machine_pm_detail_t')->where('initiate_pm_id',$id)->get();
       $this->data['initiate_pm_id']=$pm[0]->initiate_pm_id;
       $this->data['pm_checking_id']="";
       $this->data['pm_no']=$pm[0]->pm_no;
       $this->data['initiate_date']=$pm[0]->initiate_date;
       $this->data['change_date']=$pm[0]->change_date;
       $this->data['shift_timing']=$pm[0]->shift_timing;
       $this->data['pm_sdate']=$pm[0]->pm_start_time;
       $this->data['pm_edate']=$pm[0]->pm_end_time;
       $this->data['postponed_date']=$pm[0]->postponed_date;
         $this->data['allocation_type']=$pm[0]->allocation_type;
        
        $this->data['machine_id'] = $this->jCombologin('w_machine_hdr_t', 'machine_hdr_id', 'machine_code|machine_name', $pm[0]->machine_id);  
 if($pm[0]->allocation_type=="agency"){        
        $this->data['agency_allocation'] = $this->jCombologin('ma_agency_t', 'agency_id', 'agency_name', $pm[0]->allocated_agency);
 }else{
      $this->data['agency_allocation'] = $this->jCombologin('hr_employee_t', 'employee_id', 'employee_number|first_name', $pm[0]->allocated_agency);
 }
        $this->data['department_id'] = $this->jCombologin('m_department_lines_t', 'department_line_id', 'sub_department_code|sub_department_name', $pm[0]->department_id);      
        $user_clear=json_decode($pm[0]->user_clearance_by);
        $user_clear_id="";
        foreach($user_clear as $k=>$v){
           $user_clear_id.=$v.","; 
        }
        $user_clearance_by=rtrim($user_clear_id,",");
        $freq=\DB::select("select * from frequency_tbl where frequency_name='".$pm[0]->frequency_id."'");
        $this->data['user_clearance_by'] = $this->jcustommultiselect('hr_employee_t', 'employee_id', 'employee_number|first_name',$user_clearance_by,'and employee_id in ('.$user_clearance_by.')');      
          $this->data['check_details']=\DB::select("SELECT checklist_tbl.checklist_id,checklist_tbl.terms,checklist_tbl.file,checklist_tbl.checklist_name from m_checklist_hrd_tbl left join checklist_lines_tbl on checklist_lines_tbl.checklist_hrd_id=m_checklist_hrd_tbl.checklist_hrd_id left join checklist_tbl on checklist_tbl.checklist_id=checklist_lines_tbl.checklist_id where m_checklist_hrd_tbl.machine_id='".$pm[0]->machine_id."' and m_checklist_hrd_tbl.department_id='".$pm[0]->department_id."' and m_checklist_hrd_tbl.frequency_id='".$freq[0]->frequency_id."'");
		 }
               
        return view('initiatepm.pmmonthform',$this->data);
    }
    /** Store a newly created data & update data in db  */
     public function save(Request $request)
    {

	  		$id='';
			$form = $request->all();
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');

          \DB::beginTransaction();
            try{
                $initiate_pm_id=$_POST['initiate_pm_id'];
                $initiate_date=$_POST['initiate_date'];
                $initiatedate=date("Y-m-d", strtotime($initiate_date));
                $user_clearance_by=json_encode($_POST['user_clearance_by']);
                \DB::select("UPDATE machine_pm_detail_t set initiate_status='1',initiate_date='$initiatedate',user_clearance_by='$user_clearance_by' where initiate_pm_id='$initiate_pm_id'");

                         	\DB::commit();
                         	$this->pmmailsend($initiate_pm_id);
				return response()->json(array('status' => 'success', 'message' => 'Initiated Successfully','id' => $_POST['initiate_pm_id']));
			}
			catch (\Illuminate\Database\QueryException$e){
				$message = explode('(', $e->getMessage());
				$dbCode = rtrim($message[0], ']');
				$dbCode = trim($dbCode, '[');
                          
				\DB::rollback();
				return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
			}
}

  public function pmclearancesave(Request $request)
    {
         
			$id='';
			$form = $request->all();
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
          \DB::beginTransaction();
            try{
               $status=$_POST['status'];
			$employee_id =\Session::get('id');
			$date=date("Y-m-d H:i:s");
			$initiate_pm_id=$_POST['initiate_pm_id'];
			$shifttiming=$_POST['shift_timing'];
			$postponeddate=$_POST['postponed_date'];
			$change_date=$_POST['change_date'];
            $postponed_date=date("Y-m-d", strtotime($postponeddate));
            $shift_timing=date("Y-m-d H:i:s", strtotime($shifttiming));
			if($status=="1"){
			\DB::update("update machine_pm_detail_t set change_date='$change_date',status='$status',cleared_by='$employee_id',cleared_on='$date',shift_timing='$shift_timing' where initiate_pm_id='$initiate_pm_id'");
		} elseif($status == "0") {
			\DB::update("update machine_pm_detail_t set change_date='$change_date',initiate_status='0',postpone_status='1',cleared_by='$employee_id',cleared_on='$date',postponed_date='$postponed_date' where initiate_pm_id='$initiate_pm_id'");
		}
                         	\DB::commit();
                         	$this->pmclearancemailsend($initiate_pm_id);
				return response()->json(array('status' => 'success', 'message' => 'User Clearanced Successfully','id' => $_POST['initiate_pm_id']));
			}
			catch (\Illuminate\Database\QueryException$e){
				$message = explode('(', $e->getMessage());
				$dbCode = rtrim($message[0], ']');
				$dbCode = trim($dbCode, '[');
                              
				\DB::rollback();
				return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
			}
}

public function pmagencyallocationsave(Request $request)
    {
         
			$id='';
			$form = $request->all();
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
          \DB::beginTransaction();
            try{
            $employee_id =\Session::get('id');
            $date=date("Y-m-d H:i:s");
			$initiate_pm_id=$_POST['initiate_pm_id'];
			$allocated_agency=$_POST['agency_allocation'];
			$allocation_type=$_POST['allocation_type'];
			\DB::update("update machine_pm_detail_t set status='2',allocation_type='$allocation_type',allocated_by='$employee_id',allocated_on='$date',allocated_agency='$allocated_agency' where initiate_pm_id='$initiate_pm_id'");
			 $this->pmagencyallocationmailsend($initiate_pm_id);
            \DB::commit();
				return response()->json(array('status' => 'success', 'message' => 'Agency Allocated Successfully','id' => $_POST['initiate_pm_id']));
			}
			catch (\Illuminate\Database\QueryException$e){
				$message = explode('(', $e->getMessage());
				$dbCode = rtrim($message[0], ']');
				$dbCode = trim($dbCode, '[');
                              //  dd($dbCode);
				\DB::rollback();
				return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
			}
}
    public function pmmonthlychecksave(Request $request)
    {
  
			$form = $request->all();
	        $form = $request->except([
        '_token','form_config','form_data_json','submit_type',
        'choosefile','existing_file',
            ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);
			// Build header + lines
			$data = $this->validatePost($form, $this->table1, 'header');

            if($_POST['pm_checking_id']==""){
            $pm_data['pm_checking_id']='';
            $pm_data['machine_id']=$_POST['machine_id'];
            $pm_data['department_id']=$_POST['department_id'];
            $pm_data['initate_pm_id']=$_POST['initiate_pm_id'];
            if(isset($_POST['checklist'])){
            $pm_data['checklist']=json_encode($_POST['checklist']);
            $pm_data['observation']=json_encode($_POST['observation']);
            $pm_data['status']=json_encode($_POST['status']);
            $pm_data['remarks']=json_encode($_POST['remarks']);
        }
            $pm_data['approval_status']="";
            }else{
            $pm_data['pm_checking_id']=$_POST['pm_checking_id'];
            $pm_data['approval_status']=$_POST['savestatus'];	
            }
                \DB::beginTransaction();
                    try{
                        $done_date=$checked_by=date("Y-m-d");
                        $done_time=date("H:i:s");
                        $done_on_by=\Session::get('id');
                        $pm_sdate = $_POST['pm_start_date_time'] ;
                        $pm_edate = $_POST['pm_end_date_time'] ;
                        
                        $id=$this->model->insertRow($pm_data); 
                       // dd($id);
                        $update_status=\DB::update("UPDATE machine_pm_detail_t set status='3',done_on_by='$done_on_by',done_on_date='$done_date',done_on_time='$done_time', pm_start_time='$pm_sdate',pm_end_time='$pm_edate' where initiate_pm_id='".$_POST['initiate_pm_id']."'");
		             	$data_sleect=\DB::SELECT("SELECT * FROM machine_pm_detail_t where initiate_pm_id='".$_POST['initiate_pm_id']."'");	
                       // $bulk_frequency_date=$done_date;
                        $bulk_frequency_date=$data_sleect[0]->actual_pm_date;
                        $data1=[];
    
                     	if($_POST['savestatus']=="APPROVED" ){
                        $message="PM Approved Successfully";
				        if($data_sleect[0]->frequency_id=="Daily" || $data_sleect[0]->frequency_id=="DAILY"){
						$data1['actual_pm_date']=date('Y-m-d', strtotime('+1 day', strtotime($bulk_frequency_date)));
						}else if($data_sleect[0]->frequency_id=="Weekly" || $data_sleect[0]->frequency_id=="WEEKLY"){
							$data1['actual_pm_date']=date('Y-m-d', strtotime('+7 day', strtotime($bulk_frequency_date)));
						}else if($data_sleect[0]->frequency_id=="Monthly" || $data_sleect[0]->frequency_id=="MONTHLY" ||  $data_sleect[0]->frequency_id=="temporary" || $data_sleect[0]->frequency_id=="Need Basis" || $data_sleect[0]->frequency_id=="NEED BASIS"){
							$data1['actual_pm_date']=date('Y-m-d', strtotime('+1 month', strtotime($bulk_frequency_date)));
						}
                                                else if($data_sleect[0]->frequency_id=="Annual" || $data_sleect[0]->frequency_id=="ANNUAL"){
							$data1['actual_pm_date']=date('Y-m-d', strtotime('+12 month', strtotime($bulk_frequency_date)));
						}
                                                else if($data_sleect[0]->frequency_id=="HalfYearly" || $data_sleect[0]->frequency_id=="HALFYEARLY"){
							$data1['actual_pm_date']=date('Y-m-d', strtotime('+6 month', strtotime($bulk_frequency_date)));
						}
                                                else if($data_sleect[0]->frequency_id=="Quarterly" || $data_sleect[0]->frequency_id=="QUARTERLY"){
							$data1['actual_pm_date']=date('Y-m-d', strtotime('+4 month', strtotime($bulk_frequency_date)));
						}
						 $seqno = $this->Seqnoe('PM-', 'machine_pm_detail_t', '','pm_count');
                         $data1['pm_no'] = $seqno[0];
                         $data1['pm_count'] = $seqno[1];
						 $data1['machine_id']=$data_sleect[0]->machine_id;
						 $data1['department_id']=$data_sleect[0]->department_id;
	                     $data1['frequency_id']=$data_sleect[0]->frequency_id;
						 \DB::table('machine_pm_detail_t')->insert($data1);
                        }else{
                            $message="PM Checklist Checked Successfully";
                        }
                        \DB::commit();
                        $initiate_pm_id = $_POST['initiate_pm_id'];
                        if($_POST['savestatus']=="APPROVED"){
                        $this->pmcheckapprovemailsend($initiate_pm_id);
                        }else{
                        $this->pmcheckmailsend($initiate_pm_id);
                        }
				return response()->json(array('status' => 'success', 'message' => $message,'id' => $_POST['initiate_pm_id']));
			}
			catch (\Illuminate\Database\QueryException$e){
				$message = explode('(', $e->getMessage());
				$dbCode = rtrim($message[0], ']');
				$dbCode = trim($dbCode, '[');
                              
				\DB::rollback();
				return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
			}
}

public function pmedit()
    {

    $sql=\DB::select("SELECT initiate_pm_id,pm_no,actual_pm_date,initiate_date,user_clearance_by,postponed_date,machine_id,move_reason FROM machine_pm_detail_t where initiate_pm_id='".$_GET['id']."'");
          if(isset($sql)){
              if($sql[0]->pm_no!='' || $sql[0]->actual_pm_date!=''){
              $data['pm_no']=$sql[0]->pm_no;
              $data['actual_pm_date']=$sql[0]->actual_pm_date;
              $data['initiate_date']=$sql[0]->initiate_date;
              $data['user_clearance_by']=$sql[0]->user_clearance_by;
              $data['postponed_date']=$sql[0]->postponed_date;
              $data['machine_id']=$sql[0]->machine_id;
              $data['move_reason']=$sql[0]->move_reason;
              $data['update']='update';
              }else if($sql[0]->pm_no=='' &&  $sql[0]->actual_pm_date==''){
              $data['pm_no']=$sql[0]->pm_no;
              $data['actual_pm_date']=$sql[0]->actual_pm_date;
              $data['initiate_date']=$sql[0]->initiate_date;
              $data['user_clearance_by']=$sql[0]->user_clearance_by;
              $data['postponed_date']=$sql[0]->postponed_date;
              $data['machine_id']=$sql[0]->machine_id;
              $data['move_reason']=$sql[0]->move_reason;
              $data['update']='create';  
              }
              
          }
        return $data;

    }
    
public function pmupdate()
    {
        //dd("fdgfd");
        //$pstpneto = new DateTime($_GET['pstpne_to']);
        //dd($d);
        //$pstpne_to=(string)$pstpneto->format('Y-m-d');
        // dd($pstpne_to);
        $reason=$_GET['reason'];
        $move_status= "1";
              /*$check=\DB::update("update machine_pm_detail_t set initiate_date='".$d."',postponed_date='".$pstpne_to."',change_date='".$change_date."',cleared_by='".$cleared_by."' ,postpone_status='".$postpone_status."',cleared_on='".$cleared_on."',user_clearance_by='".$usr_clearance."',initiate_status='".$initiate_status."' where initiate_pm_id='".$_GET['id']."'");*/
            $check=\DB::update("update machine_pm_detail_t set move_status='".$move_status."',move_reason='".$reason."' where initiate_pm_id='".$_GET['id']."'");
            //dd($check);  
            
            $data_sleect=\DB::SELECT("SELECT * FROM machine_pm_detail_t where initiate_pm_id='".$_GET['id']."'");	
                       // $bulk_frequency_date=$done_date;
                        $bulk_frequency_date=$data_sleect[0]->actual_pm_date;
                        $data1=[];
                     	if($move_status=="1" ){
				        if($data_sleect[0]->frequency_id=="Daily" || $data_sleect[0]->frequency_id=="DAILY"){
						$data1['actual_pm_date']=date('Y-m-d', strtotime('+1 day', strtotime($bulk_frequency_date)));
						}else if($data_sleect[0]->frequency_id=="Weekly" || $data_sleect[0]->frequency_id=="WEEKLY"){
							$data1['actual_pm_date']=date('Y-m-d', strtotime('+7 day', strtotime($bulk_frequency_date)));
						}else if($data_sleect[0]->frequency_id=="Monthly" || $data_sleect[0]->frequency_id=="MONTHLY" ||  $data_sleect[0]->frequency_id=="temporary" || $data_sleect[0]->frequency_id=="Need Basis" || $data_sleect[0]->frequency_id=="NEED BASIS"){
							$data1['actual_pm_date']=date('Y-m-d', strtotime('+1 month', strtotime($bulk_frequency_date)));
						}
                                                else if($data_sleect[0]->frequency_id=="Annual" || $data_sleect[0]->frequency_id=="ANNUAL"){
							$data1['actual_pm_date']=date('Y-m-d', strtotime('+12 month', strtotime($bulk_frequency_date)));
						}
                                                else if($data_sleect[0]->frequency_id=="HalfYearly" || $data_sleect[0]->frequency_id=="HALFYEARLY"){
							$data1['actual_pm_date']=date('Y-m-d', strtotime('+6 month', strtotime($bulk_frequency_date)));
						}
                                                else if($data_sleect[0]->frequency_id=="Quarterly" || $data_sleect[0]->frequency_id=="QUARTERLY"){
							$data1['actual_pm_date']=date('Y-m-d', strtotime('+4 month', strtotime($bulk_frequency_date)));
						}
						 $seqno = $this->Seqnoe('PM-', 'machine_pm_detail_t', '','pm_count');
                                                 $data1['pm_no'] = $seqno[0];
                                                 $data1['pm_count'] = $seqno[1];
						 $data1['machine_id']=$data_sleect[0]->machine_id;
						 $data1['department_id']=$data_sleect[0]->department_id;
	                                         $data1['frequency_id']=$data_sleect[0]->frequency_id;
						 \DB::table('machine_pm_detail_t')->insert($data1);
                        }
            
            
              if($check){
                return 1;
              }else{
                  return 0;
            }
}    

}
