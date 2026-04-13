<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Misspunch;
use Illuminate\Http\Request;
use Session;
use DB;
use DatePeriod;
use DateTime;
use DateInterval;
use Illuminate\Support\Facades\Input;
use App\Employeecreate;

class MisspunchController extends Controller
{
    public function __construct()
	{
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs(); 
	 }
         //** Leave page index open funcation start **/
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

        
        $logged_user = Session::get('emp_id');

        $result = DB::table('hr_employee_t')->where('employee_id',$logged_user)->get();

        $this->data['logged_id'] = $logged_user;
        $this->data['forwarded_id'] = $result[0]->reporting_manager;
        $logged_user = Session::get('emp_id');
        $company_id = Session::get('companyid');
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['misspunch']=$this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','','AND lookup_type="misspunch"');

        $wh='';
        $wh .= "and misspunch_tbl.employee_id ='$logged_user' and misspunch_tbl.company_id=' $company_id' ";
      //dd($wh);  
        $SQL = "SELECT misspunch_tbl.miss_id,misspunch_tbl.employee_id, misspunch_tbl.date,misspunch_tbl.time,misspunch_tbl.in_time,misspunch_tbl.out_time, misspunch_tbl.forwarded_id, misspunch_tbl.status, misspunch_tbl.in_time, misspunch_tbl.in_out,misspunch_tbl.reason,misspunch_tbl.reason1, CONCAT(hr_employee_t.employee_number,'-',hr_employee_t.first_name )as forwarded_name,concat(hr_employee_name.employee_number,'-',hr_employee_name.first_name) as employee_name,misspunch_tbl.employee_id,DATE(misspunch_tbl.created_at) as created_at FROM   misspunch_tbl  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = misspunch_tbl.forwarded_id LEFT JOIN hr_employee_t as hr_employee_name ON hr_employee_name.employee_id = misspunch_tbl.employee_id WHERE  1 = 1 $wh order by  misspunch_tbl.miss_id  desc ";
		
        $this->data['result'] = json_encode(\DB::select($SQL));

      
        return view('misspunch.form',$this->data);
    }
    
	// approve
      public function punchsave(Request $request,$id=null) {
		  
		$id = $_POST['edit_id'];
		  
        $emp_id = $_POST['employee_id'];
        
        $em_name = \DB::select("SELECT biometric_empno,employee_number from hr_employee_t where employee_id='$emp_id'");
        $emp_num = $em_name[0]->employee_number;
        $bio_num = $em_name[0]->biometric_empno;
        
        $result = DB::table('hr_emp_attendence')->where('emp_id',$emp_num)->where('atten_date',$_POST['date'])->get();
        
        $miss_tbl = DB::table('misspunch_tbl')->where('miss_id',$id)->get();

      if($_POST['status']=='APPROVED') {
        
      if(count($result) > 0)
      {
    
           $in_time = $_POST['in_time'];
           $out_time = $_POST['out_time'];
           $status = $_POST['status'];
		  if($in_time !=''){
			  
			  DB::table('hr_emp_attendence')->where('atten_id',$result[0]->atten_id)->where('emp_id',$emp_num)->update(['check_in'=>$in_time]);
			  
		  }if($out_time !=''){
			  
			  DB::table('hr_emp_attendence')->where('atten_id',$result[0]->atten_id)->where('emp_id',$emp_num)->update(['check_out'=>$out_time]);
			  
		  }

         $db =   DB::table('misspunch_tbl')->where('miss_id',$id)->update(['status'=>$status]);
	
           return 1;
           
	  }else{

            DB::table('hr_emp_attendence')->insert([
                'bio_id'      => $bio_num,
                'emp_id'      => $emp_num,
                'atten_date'  => $request->input('date'),
                'check_in'    => $request->input('in_time'),
                'check_out'   => $request->input('out_time'),
                'company_id'  => session('companyid'),
                'location_id'  => session('location'),
                'organization_id' => session('organization'),

            ]);

            	$status = $_POST['status'];

               DB::table('misspunch_tbl')->where('miss_id',$id)->update(['status'=> $status]);
               return 2;
      }
		  
      }else{
		  
		       $status = $_POST['status'];

               DB::table('misspunch_tbl')->where('miss_id',$id)->update(['status'=> $status]);
               return 2;

   }

      
    }
       /*** leave save function end **/

    public function punchoverallrequest(Request $request){
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

        
         $logged_user = Session::get('emp_id');

        $result = DB::table('hr_employee_t')->where('employee_id',$logged_user)->get();
        
        $this->data['employee'] = $this->jcustomselect('hr_employee_t','employee_id','first_name','','AND group_type !="14"');
        
        //dd($this->data['employee']);
       //  dd($result);
        $this->data['logged_id'] = $logged_user;
        $this->data['forwarded_id'] = $logged_user;
        $this->data['reporting'] = $this->jcustommultiselect1forw('hr_employee_t','employee_id','employee_number|first_name',$result[0]->employee_id,$result[0]->employee_id);
    // dd($this->data['reporting']);
        $logged_user = Session::get('emp_id');
        $company_id = Session::get('companyid');
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['misspunch']=$this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','','AND lookup_type="misspunch"');
         //$result = DB::table('hr_employee_t')->where('employee_id',$logged_user)->get();
        // dd($result);
        $wh='';
        $wh .= "and misspunch_tbl.employee_id ='$logged_user' and misspunch_tbl.company_id=' $company_id' ";
        //dd($wh);  
        $SQL = "SELECT misspunch_tbl.miss_id,misspunch_tbl.employee_id, misspunch_tbl.date,misspunch_tbl.time,misspunch_tbl.in_time,misspunch_tbl.out_time, misspunch_tbl.forwarded_id, misspunch_tbl.status, misspunch_tbl.in_time, misspunch_tbl.in_out,misspunch_tbl.reason,misspunch_tbl.reason1, CONCAT(hr_employee_t.employee_number,'-',hr_employee_t.first_name )as forwarded_name,concat(hr_employee_name.employee_number,'-',hr_employee_name.first_name) as employee_name,misspunch_tbl.employee_id FROM   misspunch_tbl  LEFT JOIN hr_employee_t as hr_employee_t ON hr_employee_t.employee_id = misspunch_tbl.forwarded_id LEFT JOIN hr_employee_t as hr_employee_name ON hr_employee_name.employee_id = misspunch_tbl.employee_id WHERE  1 = 1 $wh order by  misspunch_tbl.miss_id  desc ";
        $this->data['result'] = json_encode(\DB::select($SQL));
   
        return view('misspunch.punchoverallrequest',$this->data);
    
        
    }
    
    public function misspunchdatecheck()
{
    $emp_id=$_GET['employee_id'];
    $date=date('Y-m-d',strtotime($_GET['date']));
    
    $data=\DB::select("SELECT * FROM `hr_leaves_t` where employee_id='$emp_id' and start_date>='$date' and end_date<='$date' ");
    //dd($data);
    return count($data);
}
	 //** Leave page index open funcation end **/
   /*** leave save function start **/
    public function misspunchsaveap(Request $request)
    
    {
        
        $edit_id = $request->input('edit_id');
		
        if($edit_id == '')
        {
            $misspunch = new Misspunch();
            $misspunch->employee_id = $request->input('employee_id');
            $misspunch->in_time = $request->input('in_time');
			$misspunch->misspunch = $request->input('misspunch');
            $misspunch->out_time = $request->input('out_time');
            $misspunch->date =$request->input('date') ;
            $misspunch->reason =  $request->input('reason');   
            $misspunch->status = 'INITIATED'; 
            $misspunch->forwarded_id = $request->input('forwarded_id'); 
            $misspunch->company_id =\Session::get('companyid');
            $misspunch->location_id ="1";
            $misspunch->created_by =\Session::get('id');
            $misspunch->save();
            $miss_id = $misspunch->miss_id;
            
            $this->auditlog($miss_id,"Missing Punch","Create",$misspunch,"misspunch_tbl");
            return 1;
        }
        else
        {
           
            $misspunch  = Misspunch::findOrFail($edit_id);
            $request->last_updated_by=\Session::get('id');
            $input = $request->all();
            $misspunch->fill($input)->save();
            $this->auditlog($edit_id,"Missing Punch","Update",$_POST,"misspunch_tbl");
            return 2;

        }
    }
       /*** leave save function end **/

      
  
																				  
      /** holiday grid load function start **/
	public function punchdata(Request $request)
    {

	   $comp=\Session::get('companyid');
		$wh='';

		$SQL = "SELECT
                   misspunch_tbl.miss_id,
                   misspunch_tbl.misspunch,
				   misspunch_tbl.in_time,
				   misspunch_tbl.out_time,
				   misspunch_tbl.date,
				   misspunch_tbl.time,
				   misspunch_tbl.reason,
				   misspunch_tbl.reason1,
                   misspunch_tbl.status,
                   concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as first_name,
                   concat(report.employee_number,'-',report.first_name) as reporting_name,
                   a_lookuplines_t.lookup_meaning,
                   misspunch_tbl.forwarded_id,
                   appvl.first_name as appvl_name,
                   misspunch_tbl.employee_id,
                   DATE(misspunch_tbl.created_at) as created_at
				   FROM
                        misspunch_tbl
                        left join hr_employee_t on hr_employee_t.employee_id=misspunch_tbl.employee_id
                        left join hr_employee_t as report on report.employee_id=misspunch_tbl.forwarded_id
                        left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=misspunch_tbl.misspunch
                        left join tb_users on tb_users.id=misspunch_tbl.last_updated_by
                        left join hr_employee_t as appvl on appvl.employee_id=tb_users.employee_id and  misspunch_tbl.status='APPROVED'
				   LEFT JOIN m_company_t ON m_company_t.company_id = misspunch_tbl.company_id
				   LEFT JOIN m_location_t ON m_location_t.location_id = misspunch_tbl.location_id
				   WHERE 1 = 1 and misspunch_tbl.company_id=$comp  ORDER BY miss_id DESC";
           
			$result = \DB::select($SQL);
        return DataTables::of($result)->make(true);
		
    }

    public function missapprove(Request $request)
    {
		
     $this->data['pageMethod']=\Request::route()->getName();

   	    $logged_user = Session::get('emp_id');
		$wh='';
		$gid=\Session('groupid');

     //   $wh .= "and (misspunch_tbl.forwarded_id like '$logged_user' or  misspunch_tbl.forwarded_id like '$logged_user,%' or misspunch_tbl.forwarded_id like '%,$logged_user' or misspunch_tbl.forwarded_id like '%,$logged_user,%' )";


             
            return view('misspunch.punchrequestapproval',$this->data);
    }
	
		public function punchrequestapprovaldata(Request $request)
	{
            $SQL = "SELECT
                   misspunch_tbl.miss_id,
                   misspunch_tbl.misspunch,
				   misspunch_tbl.in_time,
				   misspunch_tbl.out_time,
				   misspunch_tbl.date,
				   misspunch_tbl.time,
				   misspunch_tbl.reason,
				   misspunch_tbl.reason1,
                   misspunch_tbl.status,
                   concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as first_name,
                   concat(report.employee_number,'-',report.first_name) as reporting_name,
                   a_lookuplines_t.lookup_meaning,
                   misspunch_tbl.forwarded_id,
                   misspunch_tbl.employee_id,
                   concat(appvl.employee_number,'-',appvl.first_name) as appvl_name,
                Date(misspunch_tbl.updated_at) as updated_at, 
                Date (misspunch_tbl.created_at)as created_at
				   FROM
                        misspunch_tbl
                        left join hr_employee_t on hr_employee_t.employee_id=misspunch_tbl.employee_id
                        left join hr_employee_t as report on report.employee_id=misspunch_tbl.forwarded_id
                        left join hr_employee_t as appvl on appvl.employee_id=misspunch_tbl.last_updated_by
                        left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=misspunch_tbl.misspunch
				   LEFT JOIN m_company_t ON m_company_t.company_id = misspunch_tbl.company_id
				   LEFT JOIN m_location_t ON m_location_t.location_id = misspunch_tbl.location_id
				   WHERE  misspunch_tbl.status='INITIATED' ";
                     
				 $result = \DB::select($SQL);
   

                   return DataTables::of($result) ->make(true);
	}
	
	
	
          /** holiday grid load function end **/

      public function punchmiss(Request  $request,$id =null)
    {
    
        $edit_id = $id;
        
        $result = DB::table('misspunch_tbl')->where('miss_id',$edit_id )->get();
        
        if(count($result)>0)
        {
            
            $this->data['edit_id'] = $edit_id;
            $this->data['logged_id'] = $result[0]->employee_id;
            $this->data['in_time'] = $result[0]->in_time; 
            $this->data['out_time'] = $result[0]->out_time; 
            $this->data['date'] = $result[0]->date; 
            $this->data['reason'] = $result[0]->reason; 
            $this->data['status'] = $result[0]->status;
          	$this->data['forwarded_id'] = $this->jcustommultiselect('hr_employee_t','employee_id','employee_number|first_name',$result[0]->forwarded_id,'');
	
                   	$this->data['emp_id'] = $this->jcustommultiselect('hr_employee_t','employee_id','employee_number|first_name',$result[0]->employee_id,'');
 
 $this->data['misspunch'] = $this->jcustommultiselect('a_lookuplines_t','lookuplines_id','lookup_code',$result[0]->misspunch,'AND lookup_type="misspunch"'); 
			
        }
		  
        return view('misspunch.punchapprove',$this->data);
            
    }    

      public function destroy(holiday $holiday,$id=null)
    {

     
         $del_id = $_GET['del_id'];
       
        
        $j=0;   
       
        if($j==0)
        {
            $query = DB::table('hr_holiday_t')->where('holiday_id',$del_id)->delete();
            // auditlog
            $this->auditlog($del_id,"holiday","create",$_GET,"hr_holiday_t");
            
        }
       
        if($j == 1)
            return 1;
        else if($j == 0)
            return 2;
        else if($j == 3)
            return 3;
        
       
    }


     public function partialdestroy(holiday $holiday,$id=null)
    {

     
         $del_id = $_GET['del_id'];
       
        
        $j=0;   
       
        if($j==0)
        {
            $query = DB::table('hr_partial_day')->where('id',$del_id)->delete();
            // auditlog
            $this->auditlog($del_id,"partial","create",$_GET,"hr_partial_day");
            
        }
       
        if($j == 1)
            return 1;
        else if($j == 0)
            return 2;
        else if($j == 3)
            return 3;
        
       
    }

}

