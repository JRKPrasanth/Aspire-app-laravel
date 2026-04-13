<?php

namespace App\Http\Controllers;

use App\Employeecreate;
use App\Employeeseparation;
use App\employeereporting;
use Illuminate\Http\Request;
use DB,Session,DateTime;
use Yajra\DataTables\DataTables;


class EmployeeseparationController extends Controller
{
    public $module="paymentforinvoice";
    public function __construct()
    {
            $this->data=array();
             $this->data=array();
            $this->table="p_payments_t";
            $this->pageModule="separation";
            $this->model=new Employeeseparation();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageFormtype']='ajax';
            $this->data['urlmenu']=$this->indexs();

    }
    /** department name get funcation **/
    public function getdepartment($dep)
    {
        $list ='';
        
        if(count($dep)>0)
        {
            foreach($dep as $key=>$value)
            {
                $query = DB::table('m_department_lines_t')->where('department_line_id',$value)->get();
if(count($query)>0)
                $list .= $query[0]->sub_department_name."-";
            }
             $list = rtrim($list, '-');
        }
        else{
         $list ='';   
        }
       
  
        return $list;
    }





    /*** get company data **/
     public function getcompanydata($dep)
    {
        $list ='';
       
        if($dep!= '')
        {
        
            $query = DB::table('m_company_t')->where('company_id',$dep)->get();
            $list = $query[0]->company_name;
        }
        else
        {
            $list ='';   
        }
     
        return $list;
    }
	
    /** employe sepration view  **/
    public function view($id=null)
    {
        $list = DB::table('hr_employee_t')->leftjoin('relieving_t','relieving_t.emp_id','=',"hr_employee_t.employee_id")->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','relieving_t.notice_period')->select('a_lookuplines_t.lookup_code','hr_employee_t.employee_number','hr_employee_t.first_name','hr_employee_t.last_name','hr_employee_t.date_of_joining','hr_employee_t.date_of_leaving','hr_employee_t.notice_period')->where('employee_id',$id)->first();
		
        return view('employeeseperation.show',compact(['list']), $this->data);
		
    }
	
    /*** employee data to separation data  **/
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
 
        $query = DB::table('hr_employee_t')->get();
        
        $list = $query; 
        
        if(count($query)>0){
        foreach($query as $key=>$value)
        {
if(json_decode($value->department)!=''){
    $list[$key]->department = $this->getdepartment(json_decode($value->department));
}
            
            $list[$key]->company_id = $this->getcompanydata($value->company_id);
        }
        }
             
    $dept=$this->jcombo('m_department_lines_t','department_line_id','sub_department_name','');
   $this->data['list']=$list;
   $this->data['dept']=$dept;
        
        return view('employeeseperation.view',$this->data);
    }

     
    public function employeesearch(Request $request){

        $dept=$this->jcombo('m_department_lines_t','department_line_id','sub_department_name','');

  if($request->dept=='0' || $request->dept=='' ){
     
     $query =\DB::select("select hr_employee_t.*,m_company_t.company_name as company_id from hr_employee_t left join  m_company_t on hr_employee_t.company_id=m_company_t.company_id where hr_employee_t.email like '%".$request->searchdata."%' or hr_employee_t.first_name like '%".$request->searchdata."%' or  hr_employee_t.employee_number like '%".$request->searchdata."%' or  m_company_t.company_name like '%".$request->searchdata."%'");
//          $dept=array($request->searchdata);
           $list = $query;
            if(count($query)>0){
        foreach($query as $key=>$value)
        {
            $list[$key]->department = $this->getdepartment(json_decode($value->department));
            
        }
        }
} else {

$deptname = DB::table('m_department_lines_t')->where('department_line_id',$request->dept)->get();

$employee=DB::table('hr_employee_t')->get();
$list=array();
foreach(  $employee  as $k =>$val ) {
$employdep=json_decode($val->department);
if(in_array($deptname[0]->department_line_id,$employdep)){
    $query=\DB::select("select hr_employee_t.*,m_company_t.company_name as company_id from hr_employee_t left join  m_company_t on hr_employee_t.company_id=m_company_t.company_id where (hr_employee_t.email like '%".$request->searchdata."%' or hr_employee_t.first_name like '%".$request->searchdata."%' or  hr_employee_t.employee_number like '%".$request->searchdata."%' or  m_company_t.company_name like '%".$request->searchdata."%') and hr_employee_t.employee_id =".$val->employee_id);
 
 if(count($query)){  
$list[$k]=$query[0];
$list[$k]->department= $this->getdepartment(json_decode($query[0]->department));
}
}
}
 }
          
              return view('employeeseperation.view',compact(['list','dept']), $this->data); 

    } 







    /*** check reprting of employee to relieve **/
     public function checkreporting()
    {
            $employee_id = $_GET['user_id'];
            $emp_id = $_GET['emp_id'];
            
            $check_department = DB::table('hr_employee_t')->where('employee_id',$employee_id)->where('active','Yes')->get();
              $dept=json_decode($check_department[0]->department);
           if (in_array(28, $dept))
           { 
                $data['count'] = 2;
                $data['list'] = array();
           
           }else{
            $check = DB::table('hr_employee_t')->where('reporting_manager',$employee_id)->where('employee_id',$emp_id)->where('active','Yes')->get();
            if(count($check)>0){
            
                $data['count'] = 2;
                $data['list'] = array();
            
            }else{
            
            $query = DB::table('hr_employee_t')->where('reporting_manager',$employee_id)->where('active','Yes')->get();
            $list = array();
            if(count($query)>0)
            {
                $data['count'] = 1;
                foreach($query as $key => $value)
                {
                    $data['list'][] = $value->first_name."--".$value->last_name;
                }
                
            }
            else{
                $data['count'] = 3;
                $data['list'] = array();
            }
            }
           }
            return $data;
    }
    /** direct relieve  **/
    public function releiveprocess(Request $request)
    {
    
        $employee_id=$datas['emp_id'] = $request->input('employee_id');        
        $data['relieve_description']=$datas['relieve_reason'] = $request->input('releive_reason');
         if(isset($_POST['releive_date'])){
             $data['date_of_leaving']=$datas['relieve_date'] = $request->input('releive_date');
     
         }
           if(isset($_POST['releive_date1'])){
           
               if(is_null($_POST['releive_date1']) || $_POST['releive_date1']==''){
       
               }else{
             $data['date_of_leaving']=$datas['relieve_date'] = $request->input('releive_date1');
               }
        
           }
       
      $datas['relieving_hr_status']=$datas['relieve_status']= 1;
        $datas['relieving_by_hr'] = Session::get('emp_id');
        $datas['notice_period'] =$request->input('notice_period');
        $data['releive_date_actual'] = $datas['releive_date_actual']=date("Y-m-d",strtotime($request->input('releive_date_actual')));
        $date_notice=new DateTime($datas['relieve_date']);
        $data['date_of_leaving']=$datas['relieve_date'] = $notice=$date_notice->format('Y-m-d');
        $date=date('Y-m-d');
        if($notice<=$date){
        $data['active'] = 'No';
        $datass['active'] = 'No';
          $data['releive_status'] =1;
        }
    else {
         $data['active'] = 'Yes';
         $datass['active'] = 'Yes';
           $data['releive_status'] =1;
    }
        $datas['created_by'] =\Session::get('id');
        $datas['created_date'] =date('Y-m-d');   
        
        $query = DB::table('hr_employee_t')->where('employee_id',$employee_id)->update($data);
       DB::table('tb_users')->where('employee_id',$employee_id)->update($datass);
    
        $query1 = DB::table('relieving_t')->insertGetId($datas);  
           // auditlog
              $this->auditlog($employee_id,"separation-employeeid","update",$_POST,"tb_users");
              $this->auditlog($employee_id,"separation","update",$_POST,"hr_employee_t");
              $this->auditlog($query1,"separation","save",$_POST,"relieving_t");
        return 1;
        
    }
    /*** relieve request ***/
      public function releiverequest(Request $request)
    {
          $datas['emp_id'] = $request->input('employee_id');        
        $datas['relieve_reason'] = $request->input('releive_reason');
        $datas['relieve_date'] = $request->input('releive_date');
        $datas['relieving_hr_status']=$datas['relieve_status']= 0;
        $datas['relieving_by_hr'] =$request->input('reporting_id');
        $datas['notice_period'] =$request->input('notice_period');
          if($request->input('edit_id')==""){        
        $datas['created_by'] =\Session::get('id');
        $datas['created_date'] =date('Y-m-d');
        $query1 = DB::table('relieving_t')->insertGetId($datas);
 
        // auditlog
        $this->auditlog($query1,"separationrequest","save",$_POST,"relieving_t");
          return 1;
          }else{
                 $datas['last_updated_by'] =\Session::get('id');
        $datas['last_updated_date'] =date('Y-m-d');
              $query1 = DB::table('relieving_t')->where('relieve_id', $request->input('edit_id'))->update($datas);
               // auditlog
        $this->auditlog($query1,"separationrequest","update",$_POST,"relieving_t");
           return 2;     
          }
      
        
    }
    /*8** REPORTING REASSIGN OR NOT **/
      public function relievecheckreassign(){      
            $relivee=DB::table('hr_employee_t')->where('reporting_manager',$_GET['emp_id'])->where('active','Yes')->get();
        if(count($relivee)>0)
            return 1;
        else
            return 0; 
        }
    /** relieve requast duplicate check **/
        public function relivecheck(){      
            $relivee=DB::table('relieving_t')->where('emp_id',$_GET['emp_id'])->get();
        if(count($relivee)>0)
            return 1;
        else
            return 0; 
        }
    /** request index open page **/

    public function separationindex()
    {
        $emp_id = \Session::get('emp_id');
        $list = Employeecreate::find($emp_id);
        $rep_id = $list->reporting_manager;
        $this->data['employee']  = $this->jcombo('hr_employee_t','employee_id','employee_number|first_name',$emp_id);
        $this->data['reporting']  = $this->jcombo('hr_employee_t','employee_id','employee_number|first_name',$rep_id);
         $SQL = "SELECT relieving_t.*,hr_employee_t.first_name,rel.first_name as rel_name,hr_employee_t.employee_number as employee_number,rel.employee_number as rel_number,a_lookuplines_t.lookup_code,relieving_t.notice_period,(case when relieving_t.relieve_status = 0 then 'Initiated' when relieving_t.relieve_status = 1 then 'Approved'  end) as relieve_status_approve,(case when relieving_t.relieving_hr_status = 0 then 'Initiated' when relieving_t.relieving_hr_status = 1 then 'Approved'  end) as relieving_hr_status_approve FROM relieving_t  left join hr_employee_t on hr_employee_t.employee_id=relieving_t.emp_id  left join hr_employee_t as rel on rel.employee_id=relieving_t.relieving_by_hr left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=relieving_t.notice_period  where 
                        1 = 1";
                    
                $result = \DB::select($SQL);
             
              // dd(json_encode($result));
                 $this->data['result']=json_encode($result);

        return view('employeeseperation.form',$this->data);
         
    }
	
    
	
    public function employeeseperationgrid(Request $request){ 
		
          
		$compy=\Session::get('companyid');  
         $logged_user =\Session::get('emp_id');
        $wh = " and v1.emp_id ='$logged_user'";


    $SQL = "select * from (SELECT relieving_t.*,hr_employee_t.first_name,rel.first_name as rel_name,hr_employee_t.employee_id,hr_employee_t.employee_number as employee_number,rel.employee_number as rel_number,a_lookuplines_t.lookup_code,(case when relieving_t.relieve_status = 0 then 'Initiated' when relieving_t.relieve_status = 1 then 'Approved'  end) as relieve_status_approve,(case when relieving_t.relieving_hr_status = 0 then 'Initiated' when relieving_t.relieving_hr_status = 1 then 'Approved'  end) as relieving_hr_status_approve FROM relieving_t  left join hr_employee_t on hr_employee_t.employee_id=relieving_t.emp_id  left join hr_employee_t as rel on rel.employee_id=relieving_t.relieving_by_hr left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=relieving_t.notice_period order by relieving_t.relieve_id DESC )v1 where 1=1   ";

        $result = \DB::select( $SQL );
        
		return datatables()->of($result)->make(true);
    }



    public function employeeseparationdata(Request $request)
    {  
          
	     $compy=\Session::get('companyid');  
         $logged_user =\Session::get('emp_id');

        $wh = " and v1.relieving_by_hr ='$logged_user' and v1.relieving_hr_status=0";

    $SQL = "select * from (SELECT relieving_t.*,hr_employee_t.first_name,rel.first_name as rel_name,hr_employee_t.employee_id,hr_employee_t.employee_number as employee_number,rel.employee_number as rel_number,a_lookuplines_t.lookup_code,(case when relieving_t.relieve_status = 0 then 'Initiated' when relieving_t.relieve_status = 1 then 'Approved'  end) as relieve_status_approve,(case when relieving_t.relieving_hr_status = 0 then 'Initiated' when relieving_t.relieving_hr_status = 1 then 'Approved'  end) as relieving_hr_status_approve FROM relieving_t  left join hr_employee_t on hr_employee_t.employee_id=relieving_t.emp_id  left join hr_employee_t as rel on rel.employee_id=relieving_t.relieving_by_hr left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=relieving_t.notice_period )v1 where 1=1  $wh ORDER by v1.relieve_id";
    
        $result = \DB::select( $SQL );

		return datatables()->of($result)->make(true);
    }

    //change
      public function employeeseparationchange(Request $request)
    { 


    $SQL = "select * from (SELECT hr_reporting_tbl.employees,hr_reporting_tbl.reporting_id,hr_reporting_tbl.reporting_manager,hr_reporting_tbl.assing_employee,concat(report.employee_number,'-',report.first_name) as report_name,concat(assign.employee_number,'-',assign.first_name) as ass_name FROM hr_reporting_tbl LEFT JOIN hr_employee_t as report ON report.employee_id = hr_reporting_tbl.reporting_manager LEFT JOIN hr_employee_t as assign ON assign.employee_id = hr_reporting_tbl.assing_employee )v1";
    
        $result = \DB::select( $SQL );
		  
        return DataTables::of($result) ->make(true);
    }


    public function approveindex()
    {
        $this->data['pageMethod']=\Request::route()->getName();
        return view('employeeseperation.approveseparation',$this->data);
    }
	
    /*** employeeseparationapprove page open function **/
    public function employeeseparationrequest(Request $request,$id= null)
    {
        
        $query = DB::table('hr_employee_t')->leftjoin('relieving_t','relieving_t.emp_id','=','hr_employee_t.employee_id')->where('employee_id',$id)->first();
        $employee = $this->jCombo('hr_employee_t','employee_id','employee_number|first_name',$query->employee_id);
        $reporting = $this->jCombo('hr_employee_t','employee_id','employee_number|first_name',$query->reporting_manager);
        $notice_period = $this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code',$query->notice_period,"");
        $leaving_date = $query->date_of_leaving;
        $description =$query->relieve_description;
        $relieve_id =$query->relieve_id;
		
        return view('employeeseperation.approve',compact(['relieve_id','employee','reporting','notice_period','description','leaving_date']),$this->data);
    }
    /** approve relieve sepraartion **/
    public function releiveprocessupdate(Request $request)
    {
     
        $employee_id  = $request->input('employee_id');
       $datas['relieve_id']=$relieve_id=$request->input('relieve_id');
     
         $datas['relieve_status'] =1;
        $date_notice=new DateTime($request->input('relieve_date'));
       $notice=$date_notice->format('Y-m-d');
        $date=date('Y-m-d');
        if($notice==$date){
        $data['active'] = 'Yes';
          $data['releive_status'] =1;
            $data['date_of_leaving'] =$notice;
        }
    else {
         $data['active'] = 'No';
           $data['releive_status'] =1;
               $data['date_of_leaving'] =$notice;
    }

        $query = DB::table('hr_employee_t')->where('employee_id',$employee_id)->update($data);
          // auditlog
              $this->auditlog($employee_id,"separationapproval","update",$_POST,"hr_employee_t");
          
        $query1 = DB::table('relieving_t')->where('relieve_id',$datas['relieve_id'])->update($datas);
         // auditlog
              $this->auditlog($relieve_id,"separationapproval","approve",$_POST,"relieving_t");
        return 1;
        
        
    }
    /** hierachy list **/
    public  function hierarchylist(Request $request)
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

         $pa_id[] = Session::get('emp_id');
            $select=\DB::select("SELECT m_company_t.company_name as loc, m_position.position as pos,hr_employee_t.* FROM hr_employee_t LEFT JOIN m_position on m_position.position_id = hr_employee_t.position LEFT JOIN m_company_t on m_company_t.company_id = hr_employee_t.company_id where employee_id='$pa_id[0]'  and hr_employee_t.active='Yes'");
           
            $parent_id=$select[0]->reporting_manager;
         
            if($parent_id != 0)
            {
                //dd("SELECT m_location_t.location_name as loc, m_position.position as pos,hr_employee_t.* FROM hr_employee_t LEFT JOIN m_position on m_position.position_id = hr_employee_t.position LEFT JOIN m_location_t on m_location_t.location_id = hr_employee_t.location_id where employee_id='$parent_id'");
                $parent=\DB::select("SELECT m_company_t.company_name as loc, m_position.position as pos,hr_employee_t.* FROM hr_employee_t LEFT JOIN m_position on m_position.position_id = hr_employee_t.position LEFT JOIN m_company_t on m_company_t.company_id = hr_employee_t.company_id where employee_id='$parent_id'  and hr_employee_t.active='Yes'");
                $data='{"key":"'.$select[0]->employee_id.'", "name":"'.$select[0]->employee_number."-".$select[0]->first_name.'", "title":"'.$select[0]->pos.'","photo":"'.$select[0]->photo.'","parent":"'.$parent_id.'"},'
                . '{"key":"'.$parent[0]->employee_id.'", "name":"'.$select[0]->employee_number."-".$parent[0]->first_name.'", "title":"'.$parent[0]->pos.'","photo":"'.$parent[0]->photo.'","loc":"'.$select[0]->loc.'"},';
            }
            else
            {
                $data='{"key":"'.$select[0]->employee_id.'", "name":"'.$select[0]->employee_number."-".$select[0]->first_name.'", "title":"'.$select[0]->pos.'",  "photo":"'.$select[0]->photo.'", "parent":"'.$parent_id.'","loc":"'.$select[0]->loc.'"},';
            }
            
            $j=0;
            for($i = 0;$i <= $j;$i++)
            {
                $child=$pa_id[$i];
                $details= \DB::select("SELECT m_company_t.company_name as loc, m_position.position as pos,hr_employee_t.* FROM hr_employee_t LEFT JOIN m_position on m_position.position_id = hr_employee_t.position LEFT JOIN m_company_t on m_company_t.company_id = hr_employee_t.company_id WHERE reporting_manager ='$child'  and hr_employee_t.active='Yes'");
                if($details)
                {
                    foreach ($details as $value) 
                    {
                        $data = $data.'{"key":"'.$value->employee_id.'", "name":"'.$value->first_name.'", "title":"'.$value->pos.'", "photo":"'.$value->photo.'","parent":"'.$value->reporting_manager.'","loc":"'.$value->loc.'"}'; 
                        $data .= ",";
                        $child_id=$value->employee_id;
                        $child_detail=\DB::select("SELECT m_company_t.company_name as loc, m_position.position as pos,hr_employee_t.* FROM hr_employee_t LEFT JOIN m_position on m_position.position_id = hr_employee_t.position LEFT JOIN m_company_t on m_company_t.company_id = hr_employee_t.company_id WHERE reporting_manager='$child_id' and hr_employee_t.active='Yes'");
                        if($child_detail)
                        {
                            $j++;
                            $pa_id[]= $child_id;
                        }
                    }
                }
                //dd($child);
            } 
            $this->data['fchart']=rtrim($data,',');
          // dd($this->data['fchart']);
            return view('hierarchy.hierarchy',$this->data);
    }
   /** reporting manager reassign data load function ***/
    public function reportingsindex(Request $request)
    {
        $reporting_list = DB::table('hr_employee_t')->select('reporting_manager as employee_id')->where('reporting_manager','!=','')->groupBy('reporting_manager')->get();
        $list = array();
    
        foreach($reporting_list as $key => $value)
        {
            $list[] = $value->employee_id;
        }
       
        $imploded_list = join("','", $list);
      
        $reporting_list = $this->jcustomselect('hr_employee_t','employee_id','employee_number|first_name','',"and employee_id IN('$imploded_list')");
        $result = "SELECT hr_reporting_tbl.employees,hr_reporting_tbl.reporting_id,hr_reporting_tbl.reporting_manager,hr_reporting_tbl.assing_employee,concat(report.employee_number,'-',report.first_name) as report_name,concat(assign.employee_number,'-',assign.first_name) as ass_name FROM hr_reporting_tbl LEFT JOIN hr_employee_t as report ON report.employee_id = hr_reporting_tbl.reporting_manager LEFT JOIN hr_employee_t as assign ON assign.employee_id = hr_reporting_tbl.assing_employee where 1=1 ";
   $datas=\DB::select($result);

        foreach($datas as $key=>$value){
       $data_emp=json_decode($value->employees);
      
        foreach($data_emp as $key1=>$value1){
            
        $data_name=DB::SELECT("select * from hr_employee_t where employee_id='".$value1."'");
       // dd($data_name[0]->first_name);
        if (!empty($data_name)) {
            $data_emp[$key1] = $data_name[0]->first_name;
        } else {
            $data_emp[$key1] = ''; 
        }
        }
        $datas[$key]->employees=$data_emp;
    }

        $this->data['result'] = json_encode($datas);
        $this->data['reporting_list'] = $reporting_list;
       
        return view('changereporting.form',$this->data);
        
    }
    /** reporting dataa load **/
    public function reportingschange(Request $request)
    {
        $employee_id = $request->input('employee_id');
        
        $user_list = DB::table('hr_employee_t')->where('reporting_manager',$employee_id)->get();
        
        $employee_list = array();
        $result ='';
        if(count($user_list)>0)
        {
            $result .= '<table border="1" class="table myTable">   
                    <thead> 
                        <tr>
                            <th width="2%">Sno</th>
                            <th width="20%">Employee Name</th>
                            <th width="2%"><input type="checkbox" name="check" id="check" value=""></th>
                            
                        </tr>
                    </thead>
                    <tbody>';
            foreach($user_list as $key=>$value)
            {
                $employee_list[$key] = $value->first_name."-".$value->last_name;
                $employee_name = $value->first_name."-".$value->last_name;
                 $k =$key;
        
            $result .= '  <tr>
                                <td>'.++$key.'</td>
                                <td><strong>'.$employee_name.'</strong></td> 
                                <input type="hidden" name="employees[]" id="employees" value="'.$value->employee_id.'" >
                                <td><input type="checkbox" class="check_employee  check_employee'.$k.'" name="check_employee[]" id="check_employee" value="'.$value->employee_id.'" ></td>
                            </tr>';
                         
                    
            }
            $result .= '</tbody>
                </table>';
        }
        
        $reporting_list = $this->jcustomselect('hr_employee_t','employee_id','employee_number|first_name','',"and employee_id !='$employee_id'");
        $data['reporting_list'] = $reporting_list;
        $data['result'] = $result;
        
        return $data;
    }
   /** reporting save  **/ 
    public function store(Request $request)
    {
       
         $edit_id = $request->input('reporting_id');
        if($edit_id == '')
        {
            $employeereporting = new employeereporting();
             $employeereporting->assing_employee= $assing_employee = $request->input('assing_employee');
         $employeereporting->reporting_manager=$reporting_manager = $request->input('reporting_manager');

       $employees = $request->input('employees');
       $employeereporting->employees=json_encode($request->input('check_employee'));
        $query = DB::table('hr_employee_t')->where('reporting_manager',$reporting_manager)->get();

        for($i=0; $i<count($query); $i++)
        {
            if(isset($request->input('check_employee')[$i]))
            {
                $emp_id  = $request->input('check_employee')[$i];
                $data['reporting_manager'] = $assing_employee;
                $query_update = DB::table('hr_employee_t')->where('employee_id',$emp_id)->update($data);
            }
            
        }
            //dd($employeereporting);
            $employeereporting->save(); 
            $name = $employeereporting->getKeyName();
            $id = $employeereporting->$name; 
            $table = $employeereporting->getTable();
            $column = $employeereporting->getKeyName();
            $this->hrmssaveinsert($table,$column,$id,1);
                   // auditlog
              $this->auditlog($id,"changereporting","create",$_POST,"hr_reporting_tbl");
            return 1;
        }
        else
        { 
            $employeereporting = new employeereporting();
            $edit_id=$_POST['reporting_id'];
            $reporting_manager = $request->input('reporting_manager');
            $assing_employee = $request->input('assing_employee');
            $_POST['employees']=json_encode($request->input('check_employee'));
            employeereporting::find($edit_id)->update($_POST); 
       $query = DB::table('hr_employee_t')->where('reporting_manager',$reporting_manager)->get();

        for($i=0; $i<count($query); $i++)
        {
            if(isset($request->input('check_employee')[$i]))
            {
                $emp_id  = $request->input('check_employee')[$i];
                $data['reporting_manager'] = $assing_employee;
                $query_update = DB::table('hr_employee_t')->where('employee_id',$emp_id)->update($data);
            }
            
        }
            $table = $employeereporting->getTable();
            $column = $employeereporting->getKeyName();
            $this->hrmssaveinsert($table,$column,$edit_id,2);
            return 2;
        }
        return 1;
    }
    /** chaneg reporting load data **/
     public function changereportingdata(Request $request)
    {
        
        $wh='';
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
    $search_tables=["hr_reporting_tbl"];
    if($_GET['_search']=='true'){
         $wh=$this->jqgridsearch("hr_reporting_tbl",$_GET['filters'],$search_tables);
       }
    if(!$sidx) $sidx =1;
    $result = \DB::select("SELECT COUNT(reporting_id) AS count FROM hr_reporting_tbl LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = m_position.job_title_id where 1=1 $wh");
    $count = $result[0]->count;
    if( $count > 0 && $limit > 0)
        {
            $total_pages = ceil($count/$limit);
    }
        else
        {
            $total_pages = 0;
    }

    if ($page > $total_pages) $page=$total_pages;
    $start = $limit*$page - $limit;
    if($start <0) $start = 0;

        
    $SQL = "SELECT 
            m_job_title.job_title_name,
            IFNULL(m_position.position_description,'') as position_description,
            m_position.position_id,
            m_position.position,
            m_position.position_description,
            m_position.job_title_id,
            m_position.active
            from m_position 
            LEFT JOIN m_job_title ON m_job_title.job_title_id = m_position.job_title_id
            where 1=1 $wh ORDER BY $sidx $sord LIMIT $start,$limit";
    $result = \DB::select($SQL);
    $responce->rows[]='';
    $responce->rows=$result;
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;

    echo json_encode($responce);
        
        
    }
}
