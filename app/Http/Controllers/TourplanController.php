<?php

namespace App\Http\Controllers;

use App\Tourplan;
use Illuminate\Http\Request;
use DB;
class TourplanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $module="Tourplan";
    public function __construct()
    {
        $this->data=array();
        $this->model    = new Tourplan();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        $this->data['pageModule']='Tourplan';
        $this->table="app_tourprogram_t";
        $this->data['urlname']=\Request::route()->getName();
        $this->middleware('auth');

    }
    public function index()
    { 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['urlname']=\Request::route()->getName();
        $this->data['edit_type']="new";
        $this->data['emp_id1']="";
        $this->data['emp_name']="";
        $this->data['employee_log']=\Session::get('emp_id');
        
        return view("tourplan.table",$this->data);
    }

    public function disdata(){

        if($_GET['employee_id'] != ''){
            $emp_id = $_GET['employee_id'];
        }else{
            $emp_id = \Session::get('id');    
        }
        
        $holidays = \DB::table('app_tourprogram_t')
                ->leftjoin('app_worktype_t','app_worktype_t.worktype_id','=','app_tourprogram_t.tour_details')
                ->select('app_tourprogram_t.tourprogram_id','app_tourprogram_t.remarks','app_worktype_t.work_type','app_tourprogram_t.tour_area','app_tourprogram_t.tour_date')
                ->where('app_tourprogram_t.created_by',$emp_id)->get();
              
        $ara_namett='';

        if(count($holidays) > 0){
            foreach ($holidays as $k => $v) {
                if($v->tour_area != '')
                    $area = json_decode($v->tour_area);
                else
                    $area = [];
                // print_r($area);
                if(count($area) > 0 ){
                    $ar = \DB::table('m_area_t')->whereIn('area_id',$area)->select('area_name')->get();
                    if(count($ar) > 0 ){
                        $ara_namett='';
                        foreach ($ar as $ark => $arv) {
                            $ara_namett.= $arv->area_name.",";
                        }
                        $ara_name1 = rtrim($ara_namett,',');
                        $v->tour_area = $ara_name1;
                    }
                }
            }
        }
        
        $holiday=[];

        if($holidays)
        {
            foreach ($holidays as $h){
                $h->remarks = $h->work_type ."-" .$h->tour_area;
                if($_GET['employee_id'] != ''){
                	$holiday[] = array(
    			        'title' => strtoupper($h->remarks),
    			        'start' => $h->tour_date,
    			        'textColor' => "#ffffff",
    			        'bordercolor' => "#182874",
    			        'color' => "#182874",
    			        'html' =>"<input type='checkbox' id='checkme' name='checkme[]' class='checkme' value='".$h->tourprogram_id."' style='width:24px;height:21px;' >"
    		        );
                }else{
                	$holiday[] = array(
    			        'title' => strtoupper($h->remarks),
    			        'start' => $h->tour_date,
    			        'textColor' => "#ffffff",
    			        'bordercolor' => "#182874",
    			        'color' => "#182874",
    			        'html' =>""
    		        );
                }
            
            }
        }   
        $data['calendar']=$holiday;

        return $data;
    }


    public function approvalindex()
    { 

        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['urlname']=\Request::route()->getName();
       
        return view("tourplan.app_table",$this->data);
    }
    
    public function detailindex($id=null)
    { 

        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['urlname']=\Request::route()->getName();
        $this->data['edit_type']="approve";
        $this->data['emp_id1']=$emp_id1=$id;

        $emp_name=\DB::select("Select hr_employee_t.first_name FROM hr_employee_t left join tb_users on tb_users.employee_id = hr_employee_t.employee_id where tb_users.id='".$id."'");
         $this->data['emp_name']=$emp_name[0]->first_name;
        return view("tourplan.table",$this->data);
    }  

   
    
    public function getapprovaltourplanData()   
    {
        $wh='';
        $search_table=[];
        $search_table[]="m_cities_t";
        $search_table[]="hr_employee_t";
        $search_table[]="app_worktype_t";
        if($_GET['_search']=='true')
        {
            $wh=$this->jqgridsearch('app_tourprogram_t',$_GET['filters'],$search_table);

        }
        
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;
        $empid = \Session::get('emp_id');

        $result = \DB::select("SELECT hr_employee_t.first_name, tb_users.id,count(app_tourprogram_t.created_by) as total,app_tourprogram_t.created_by FROM `app_tourprogram_t` left join tb_users on tb_users.id = app_tourprogram_t.created_by left join hr_employee_t on tb_users.employee_id =hr_employee_t.employee_id where 1=1 AND app_tourprogram_t.status='INITIATED' and hr_employee_t.reporting_manager='$empid' $wh group by app_tourprogram_t.employee_id ");
        $count = count($result);
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


          
        $sql="SELECT hr_employee_t.first_name, tb_users.id,count(app_tourprogram_t.created_by) as total,app_tourprogram_t.created_by FROM `app_tourprogram_t` left join tb_users on tb_users.id = app_tourprogram_t.created_by left join hr_employee_t on tb_users.employee_id =hr_employee_t.employee_id where 1=1 AND app_tourprogram_t.status='INITIATED' and hr_employee_t.reporting_manager='$empid' $wh group by app_tourprogram_t.created_by ORDER BY app_tourprogram_t.created_by desc";
        // dd($sql);
        $result = \DB::select($sql);
        
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
        
        
    }

    
    public function save(Request $request)
    { 

        
       if($_POST['tour_status']=="" && $_POST['save_status']=="")
       {
            if($_POST['tour_area']){
                $tour_area = json_encode($_POST['tour_area']);
            }

            $tour_date = date("Y-m-d", strtotime($_POST['tour_date']));
            $tour_details = $_POST['tour_details'];
            $remarks = $_POST['remarks'];
            if($_POST['employee_id'] != ''){
                $employee_id = $_POST['employee_id'];
                $created_by = $_POST['employee_id'];
            }else{
                $employee_id = \Session::get('id');
                $created_by = \Session::get('id');
            }
           
                $status= "INITIATED";
          $company_id = \Session::get('companyid');
            $organization_id = \Session::get('organization');
            $location_id = \Session::get('location');
            
            $exist = DB::table('app_tourprogram_t')->where('tour_date',$tour_date)->get();
            if(count($exist)>0){

                $query = DB::table('app_tourprogram_t')->where('tour_date',$tour_date)->update(['tour_area'=>$tour_area,'tour_date'=>$tour_date,'tour_details'=>$tour_details,'remarks'=>$remarks,'employee_id'=>$employee_id,'created_by'=>$created_by,'company_id'=>$company_id,'organization_id'=>$organization_id,'location_id'=>$location_id,'status'=>$status]);

                $ret=1;
                $query=DB::table('app_tourprogram_t')->where('tour_date',$tour_date)->get();
     
                $query=$query[0]->tourprogram_id;

            }else{
                $data['tour_area'] = $tour_area;
                $data['tour_date'] = $tour_date; 
                $data['tour_details'] =  $tour_details;
                $data['remarks'] =  $remarks;
                $data['status'] =  $status;
                $data['employee_id'] =  $employee_id;
                $data['created_by'] =  $created_by;
                $data['company_id'] =  $company_id;
                $data['organization_id'] =  $organization_id;
                $data['location_id'] =  $location_id;

                $query = DB::table('app_tourprogram_t')->insertGetId($data);
                $ret=2;

            }

            return $ret;
       }else if($_POST['tour_status']=="" && $_POST['save_status']!=""){
        $query = DB::table('app_tourprogram_t')->where('tourprogram_id',$_POST['tourprogram_id'])->update(['status'=>$_POST['save_status']]);
        
        return response()->json(array('status'=>'success','message'=>$_POST['save_status'].'  successfully'));
       }
       else{

        $tour_status = explode(',', $_POST['tour_status']);
       
        for ($i=0; $i < count($tour_status); $i++) { 
            
            $query = DB::table('app_tourprogram_t')->where('tourprogram_id',$tour_status[$i])->update(['status'=>$_POST['save_status']]);
        }
        
            return response()->json(array('status'=>'success','message'=>$_POST['save_status'].'  successfully'));
       
       }
       
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function touraeditdata($date=null){
        $date = date('Y-m-d',strtotime($date));
        if(isset($_GET['emp_id'])){
            if($_GET['emp_id'] == ''){
                $c_id = \Session::get('id');        
            }else{
                $c_id = $_GET['emp_id'];
            }
        }else{
            $c_id = \Session::get('id');
        }
        
        $data = \DB::table('app_tourprogram_t')->whereDate('tour_date',$date)->where('created_by',$c_id)->select('tour_date','tour_details','tour_area','remarks','status','tourprogram_id')->get();
        
        if(count($data) > 0 ){
            foreach ($data as $key => $val) {
                $val->tour_date = date('d-m-Y',strtotime($val->tour_date));
            }
        }

        return $data;
    }

    public function create($id=null)
    {

        $this->data['employee_id'] = $id;
        
        $this->data['employee_log'] = $tourarea =\Session::get('emp_id');


        if($id){

            $tour_sql=\DB::table('hr_employee_t')->where('employee_id',$id)->get();
            
            $t_area = json_decode($tour_sql[0]->med_rep_area,true);

            $area=implode(",",$t_area);

            $sql=\DB::table('app_tourprogram_t')->where('tourprogram_id',$id)->get();

            if(count($sql)>0){
                $tour_area = $sql[0]->tour_area;

                $this->data['tour_area'] = $this->jcustommultiselect('m_area_t','area_id','area_name',$tour_area,"and area_id in ($area)");            
                $this->data['tour_type'] = $this->jCombologin('app_worktype_t','worktype_id','work_type',$sql[0]->tour_details);
                $this->data['doctor_id'] = $this->jCombo('app_doctors_t','doctor_id','doctor_name',$sql[0]->doctor_id);
                
                $this->data['row']=$sql[0];

            }
        }else{

            $tour_sql=\DB::table('hr_employee_t')->where('employee_id',$tourarea)->get();
            $t_area = json_decode($tour_sql[0]->med_rep_area,true);
            
            $area=implode(",",$t_area);
        	$this->data['tour_area'] = $this->jcustommultiselect('m_area_t','area_id','area_name','',"and area_id in ($area)");
                      
            $this->data['tour_type'] = $this->jCombologin('app_worktype_t','worktype_id','work_type','');
            $this->data['doctor_id'] = $this->jCombo('app_doctors_t','doctor_id','doctor_name','');
            $this->data['month_id'] = $this->jCombologin('m_month_t','month_id','month_name','');
            $this->data['row']='';
        }
              
        
        $this->data['urlname']=$urlname=\Request::route()->getName();

        // if($urlname=="tourapproval"){
        //      $this->data['return_url']='tourplanapproval/'.$sql[0]->employee_id;
        // }
      
        return view("tourplan.form",$this->data);
    }

    public function areadoctor($id=null)
    {
        $ids = explode(",", $id);

        $doctor = \DB::table('app_doctors_adr_t')
                    ->leftjoin('app_doctors_t','app_doctors_t.doctor_id','=','app_doctors_adr_t.doctor_id')
                    ->whereIn('app_doctors_adr_t.area',$ids)
                    ->select('app_doctors_adr_t.doctor_id','app_doctors_t.doctor_name','app_doctors_adr_t.area','app_doctors_adr_t.doctors_adr_id')
                    ->get();

        return $doctor;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tourplan  $tourplan
     * @return \Illuminate\Http\Response
     */
    public function show(Tourplan $tourplan ,$id=null)
    {
        $area='';
        $tourplan =\DB::table('app_tourprogram_t')->where('tourprogram_id',$id)->get(); 
        //dd($tourplan);
        $this->data['tourplan']=$tourplan[0];
        $this->data['tour_type']= $this->idname('work_type','app_worktype_t','worktype_id',$tourplan[0]->tour_details);

      //  $tour_area = json_decode($tourplan[0]->tour_area);
        $tour_area = explode(",", $tourplan[0]->tour_area);
         // dd($tourarea);
        if($tour_area!=null){
        foreach ($tour_area as $key => $value) {
            $area .= $this->idname('area_name','m_area_t','area_id',$value).",";    
        }
        $areaname=rtrim($area,',');
      }
      else
      {
           $areaname = $this->idname('area_name','m_area_t','area_id',"");    

      }
        $this->data['tour_area'] = $areaname;
        return view('tourplan.view',$this->data);  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tourplan  $tourplan
     * @return \Illuminate\Http\Response
     */
    public function edit(Tourplan $tourplan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tourplan  $tourplan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tourplan $tourplan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tourplan  $tourplan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tourplan $tourplan)
    {
        //
    }
    public function gettourplanData()   
    {
        $wh='';
        $search_table=[];
        $search_table[]="m_area_t";
        $search_table[]="hr_employee_t";
        $search_table[]="app_worktype_t";
        if($_GET['_search']=='true')
        {
            $wh=$this->jqgridsearch('app_tourprogram_t',$_GET['filters'],$search_table);
        }
        

        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;

        $result = \DB::select("SELECT COUNT(tourprogram_id) AS count FROM app_tourprogram_t left join hr_employee_t on (app_tourprogram_t.employee_id=hr_employee_t.employee_id) left join m_area_t on (app_tourprogram_t.tour_area=m_area_t.area_id) left join app_worktype_t on (app_tourprogram_t.tour_details=app_worktype_t.worktype_id) where 1=1 $wh");

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
        $emp = \Session::get('id');
        $sql="SELECT app_tourprogram_t.tourprogram_id,app_tourprogram_t.tour_date,app_worktype_t.work_type,app_tourprogram_t.tour_details,m_area_t.area_name,hr_employee_t.employee_id,hr_employee_t.first_name,m_area_t.area_id,m_area_t.area_name from app_tourprogram_t left join hr_employee_t on (app_tourprogram_t.employee_id=hr_employee_t.employee_id) left join app_worktype_t on (app_tourprogram_t.tour_details=app_worktype_t.worktype_id) left join m_area_t on (app_tourprogram_t.tour_area=m_area_t.area_id) where 1=1 and app_tourprogram_t.created_by = '".$emp."' $wh ORDER BY app_tourprogram_t.tourprogram_id desc";

        $result = \DB::select($sql);
        
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
        
        
    }

    public function gettourapprovaldata()   
    { 

        //$emp_id=1;
        $emp_id=$_GET['emp_id'];

        $wh='';
        $search_table=[];
        $search_table[]="m_cities_t";
        $search_table[]="hr_employee_t";
        $search_table[]="app_worktype_t";
        if($_GET['_search']=='true')
        {
            $wh=$this->jqgridsearch('app_tourprogram_t',$_GET['filters'],$search_table);

        }
        

        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;

        $result = \DB::select("SELECT COUNT(tourprogram_id) AS count FROM app_tourprogram_t left join hr_employee_t on (app_tourprogram_t.employee_id=hr_employee_t.employee_id) left join m_area_t on (app_tourprogram_t.tour_area=m_area_t.area_id) left join app_worktype_t on (app_tourprogram_t.tour_details=app_worktype_t.worktype_id) where 1=1 AND app_tourprogram_t.status='INITIATED'  $wh");

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
        
        $sql="SELECT app_tourprogram_t.tourprogram_id,app_tourprogram_t.tour_date,app_worktype_t.work_type,app_tourprogram_t.tour_details,m_area_t.area_name,hr_employee_t.employee_id,hr_employee_t.first_name,m_area_t.area_id,m_area_t.area_name from app_tourprogram_t left join hr_employee_t on (app_tourprogram_t.employee_id=hr_employee_t.employee_id) left join app_worktype_t on (app_tourprogram_t.tour_details=app_worktype_t.worktype_id) left join m_area_t on (app_tourprogram_t.tour_area=m_area_t.area_id) where 1=1 AND app_tourprogram_t.status='INITIATED' $wh ORDER BY app_tourprogram_t.tourprogram_id desc";

        $result = \DB::select($sql);
        
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
            
        
    }

    public function delete(Request $request,$id=null)
    { 
        $count=0;
        // $queryquote = \DB::table('app_tourprogram_t')->where('tourprogram_id',$id)->count(); 
        // if($queryquote >=1)
        // {
        //  $count++;
        // }
        if($count <= 0)
        {
            $query = \DB::table('app_tourprogram_t')->where('tourprogram_id',$id)->delete();

            if($query)
            {
                return 0;
            }
            else
            {
                return 1;
            }
        }
        else
        {
            return 2;
        }

    }


}
