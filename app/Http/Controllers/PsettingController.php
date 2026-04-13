<?php

namespace App\Http\Controllers;

use App\Psetting;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;
use DateTime;
use Yajra\DataTables\DataTables;

class PsettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
			public function __construct()
	{
		$this->data=array();
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
		$this->data['pageModule']=\Request::route()->getName();
		$this->middleware('auth');
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

        $company_id=\Session::get('companyid');
		$result = DB::table('hr_emp_payroll_settings_t')->where('company_id',$company_id)->orderBy('emp_payroll_settings_id','DESC')->take(1)->get();
                if(count($result)>0){
		$this->data['week_off']=json_decode($result[0]->week_off);
		$this->data['month_off']=json_decode($result[0]->month_off);
                $this->data['week_period']=json_decode($result[0]->week_period); 
                }else{
                    $this->data['week_off']=[];
		$this->data['month_off']=[];
                $this->data['week_period']=[];
                }
		
		return view('psetting.form',$this->data);
    }
	
	
	/** Setting save and update **/
    public function store(Request $request)
    {
		$week_off = json_encode($request->input('week_off'));
        $month_off = json_encode($request->input('month_off'));
		$week_period = json_encode($request->input('week_period'));
		$company_id=\Session::get('companyid');
		$location_id=\Session::get('location');
		$organization=\Session::get('organization');
		$created_by=\Session::get('id');
		$created_date=date('Y-m-d');
		$result = DB::table('hr_emp_payroll_settings_t')->where('company_id',$company_id)->orderBy('emp_payroll_settings_id','DESC')->take(1)->get();
		if(count($result)==0)
		{
			$result = DB::table('hr_emp_payroll_settings_t')->insertGetId(['week_off'=>$week_off,'month_off'=>$month_off,'week_period'=>$week_period,'company_id'=>$company_id,'location_id'=>$location_id,'organization_id'=>$organization,'created_by'=>$created_by,'created_date'=>$created_date]);
			//auditlog
			$this->auditlog($result,"Setting","Update",$_POST,"hr_emp_payroll_settings_t");
		}
		else
		{
			$result = DB::table('hr_emp_payroll_settings_t')->where('company_id',$company_id)->update(['week_off'=>$week_off,'month_off'=>$month_off,'week_period'=>$week_period,'company_id'=>$company_id,'location_id'=>$location_id,'organization_id'=>$organization,'created_by'=>$created_by,'created_date'=>$created_date]);
			$result = DB::table('hr_emp_payroll_settings_t')->where('company_id',$company_id)->orderBy('emp_payroll_settings_id','DESC')->take(1)->get();
			$this->data['week_off']=json_decode($result[0]->week_off);
			//auditlog
			$this->auditlog($result,"Setting","Update",$_POST,"hr_emp_payroll_settings_t");
		}
		
	      return 1;	
    }

	
	/****** payroll cuttoff  ****/
	public function create(Request $request)
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
	
		return view('payrollcutoff.form',$this->data);
        
    }
	
	/****** holidaysave  ****/
    public function holidaysave(Request $request)
    {
		$edit_id = $request->input('edit_id');
		if($edit_id =='')
		{
			$company = $request->input('company');
			$payroll_cuttoff = $request->input('holiday_name');
			
			$description 	 = $request->input('description');
			list($year,$month,$date) 	 = explode('-',$payroll_cuttoff);
			$description 	 = $request->input('description');
		
			$result = DB::table('hr_emp_payroll_cutoff')->insertGetId(['cutoff_date'=>$payroll_cuttoff,'company'=>$company,'description'=>$description,'month'=>$month,'year'=>$year]);
			//auditlog
			$this->auditlog($result,"Holiday","Create",$_POST,"hr_emp_payroll_cutoff");
			return 1;
		}
		else
		{
			
			$company = $request->input('company');
			$payroll_cuttoff = $request->input('payroll_cuttoff');
			$description 	 = $request->input('description');
			list($year,$month,$date) 	 = explode('-',$payroll_cuttoff);
			$description 	 = $request->input('description');
			//dd($data);
			$result = DB::table('hr_emp_payroll_cutoff')->where('id',$edit_id)->update(['company'=>$company,'cutoff_date'=>$payroll_cuttoff,'description'=>$description,'month'=>$month,'year'=>$year]);
			//auditlog
			$this->auditlog($edit_id,"Holiday","Update",$_POST,"hr_emp_payroll_cutoff");
			return 2;
			
		}
        
	}
	/****** holidaysave  End ****/

    
   
	
	/****** Company Cut Off  Start ****/
	public function companycheckcutoff($id)
	{
		$id1=$_GET['id'];
		if($_GET['id']==0){
		$result = \DB::select("SELECT COUNT(id) AS count FROM hr_emp_payroll_cutoff  where company='$id' ");
		}
		else{
		$result = \DB::select("SELECT COUNT(id) AS count FROM hr_emp_payroll_cutoff  where company='$id' and id!='$id1'  ");
		}
		
		return $result[0]->count;
	}

	
	public function payrollcutoffgriddata()
    {
        $comp=\Session::get('companyid');
        
		$SQL = "SELECT
                   m_company_t.company_name,
				   hr_emp_payroll_cutoff.cutoff_date,
				   hr_emp_payroll_cutoff.id,
				   hr_emp_payroll_cutoff.company,
				   hr_emp_payroll_cutoff.description,	
				   hr_emp_payroll_cutoff.active	
				   FROM
                        hr_emp_payroll_cutoff
				   LEFT JOIN m_company_t ON m_company_t.company_id = hr_emp_payroll_cutoff.company
				   WHERE 1 = 1 and hr_emp_payroll_cutoff.company_id=$comp ORDER BY cutoff_date DESC";
           
			$result = \DB::select($SQL);
			return DataTables::of($result)->make(true);
		
    }

    public function save(Request $request)
    {
		$edit_id = $request->input('edit_id');
		if($edit_id =='')
		{
			
			$company = $request->input('company');	
			$payroll_cuttoff=date('Y-m-d', strtotime($request->input('payroll_cuttoff')));
			
			$description 	 = $request->input('description');
			$active 	 = $request->input('active');
			$created_by 	 = \Session::get('id');
			$created_date 	 = date('Y-m-d');
		    $company_id=\Session::get('companyid');
		    $location_id="1";
		    $organization=\Session::get('organization');
			
			$result = DB::table('hr_emp_payroll_cutoff')->insertGetId(['cutoff_date'=>$payroll_cuttoff,'company'=>$company,'description'=>$description,'active'=>$active,'created_by'=>$created_by,'created_date'=>$created_date,'location_id'=>$location_id,'organization_id'=>$organization,'company_id'=>$company_id]);
			//auditlog
			$this->auditlog($result,"Emp Payroll Cutoff","Create",$_POST,"hr_emp_payroll_cutoff");
			return 1;
		}
		else
		{
			
			$company = $request->input('company');
                        
						  $payroll_cuttoff=date('Y-m-d', strtotime($request->input('payroll_cuttoff')));
			
			$active 	 = $request->input('active');
			$description 	 = $request->input('description');
			$last_updated_by 	 = \Session::get('id');
			$last_updated_date 	 = date('Y-m-d');
			  $company_id=\Session::get('companyid');
		    $location_id="1";
		    $organization=\Session::get('organization');
			$result = DB::table('hr_emp_payroll_cutoff')->where('id',$edit_id)->update(['company'=>$company,'cutoff_date'=>$payroll_cuttoff,'description'=>$description,'active'=>$active,'last_updated_by'=>$last_updated_by,'last_updated_date'=>$last_updated_date,'location_id'=>$location_id,'organization_id'=>$organization,'company_id'=>$company_id]);
			//auditlog
			$this->auditlog($edit_id,"Emp Payroll Cutoff","Update",$_POST,"hr_emp_payroll_cutoff");
			
			return 2;
			
		}
        
	}
	/****** PayRoll cutoff Save data Start ****/

	/****** PayRoll cutoff Delete data Start ****/
	public function payrolldelete(Request $request,$id=null)
    {

		$j=0;
		
        if($j==0)
        {
			$query = DB::table('hr_emp_payroll_cutoff')->where('id',$id)->delete();
			//auditlog
			$this->auditlog($id,"Emp Payroll Cutoff","Delete",$query,"hr_emp_payroll_cutoff");
        }
       
        if($j == 1)
            return 1;
        else if($j == 0)
            return 2;
        else if($j == 3)
            return 3;
		
		
        return 1;
		
	} 

	
}
