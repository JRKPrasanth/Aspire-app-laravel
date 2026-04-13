<?php

namespace App\Http\Controllers;

use App\Doctor;
use App\doctoraddresslines;
use Illuminate\Http\Request,DB;
use Illuminate\Support\Facades\Schema;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->model    = new Doctor();
        $this->submodel    = new doctoraddresslines();
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['urlname']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        $this->data['pageModule']='Doctor';
        $this->table="app_doctors_t";
        $this->subtable="app_doctors_adr_t";
    }

    public function index()
    {
        $this->model    = new Doctor();
        $this->submodel    = new doctoraddresslines();
        $this->data=array();
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['urlname']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        $this->data['pageModule']='Doctor';
        $this->table="app_doctors_t";
        $this->subtable="app_doctors_adr_t";
        return view("doctor.table",$this->data);
    }

    public function create($id=null)
    {
        $this->data['pageMethod']="doctordcr";
        $doctor=Doctor::find($id);
        $empl_id=\Session::get('id');
        $this->data['country_id']=$this->jCombologin('m_countries_t','country_id','country_name','');
        $this->data['state_id']=$this->jCombologin('m_states_t','state_id','state_name','');
        $this->data['city_id']=$this->jCombologin('m_cities_t','city_id','city_name','');
        $this->data['area']=$this->jCombologin('m_area_t','area_id','area_name','');
        if($id == '0')
        {
            $doctor =\DB::connection()->getSchemaBuilder()->getColumnListing("app_doctors_t");

            $this->data['row'] = (object) array();

            foreach($doctor as $key=>$value)
            {
                $this->data['row']->$value = '';
            }
            $this->data['row']->active="Yes";
            $this->data['row']->grade=$this->jCombologin('app_grade_t','grade_id','grade','');
            $this->data['row']->degree=$this->jCombologin('app_degree_t','degree_id','degree','');
            $this->data['row']->specialization =$this->jCombologin('app_specialization_t','specialization_id','specialization','');
            $this->data['row']->days=$this->jCombologin('app_days_t','days_id','days','');
            $this->data['row']->created_by=$this->jCombologin('tb_users','id','first_name',$empl_id);
            $this->data['row']->chemist_id=$this->jCombo('app_chemist_t','chemist_id','chemist_name','');
            $this->data['row']->stockist_id=$this->jCombo('app_stockist_t','stockist_id','stockist_name','');
            $this->data['linedata'] = array();
            $this->data['clinic_name']='';
            

        }else{
            $sql=\DB::table('app_doctors_t')->where('doctor_id',$id)->get();
            $this->data['row'] = (object) array();
            foreach($sql[0] as $key => $value ){
                $this->data['row']->$key=$value;
            }

            if($sql->isNotEmpty()){
                $this->data['row']->doa =  date( 'd-m-Y',strtotime($sql[0]->doa));
                $this->data['row']->dob =  date( 'd-m-Y',strtotime($sql[0]->dob));                
                $this->data['row']->grade=$this->jCombologin('app_grade_t','grade_id','grade',$sql[0]->grade);
                $this->data['row']->created_by=$this->jCombologin('tb_users','id','first_name',$empl_id);
                $this->data['row']->chemist_id=$this->jCombo('app_chemist_t','chemist_id','chemist_name',$sql[0]->chemist_id);
                $this->data['row']->stockist_id=$this->jCombo('app_stockist_t','stockist_id','stockist_name',$sql[0]->stockist_id);
                if($sql[0]->specialization != "null" && $sql[0]->specialization != ""){
                    $specialization = implode(',',json_decode($sql[0]->specialization));
                }else{
                    $specialization = '';
                }
                $specialization = implode(',',json_decode($sql[0]->specialization));
                $this->data['row']->specialization =$this->jcustommultiselect('app_specialization_t','specialization_id','specialization',$specialization,'');

                if($sql[0]->days != "null" && $sql[0]->days !=""){
                    $days = implode(',',json_decode($sql[0]->days));
                }else{
                    $days = '';
                }
                $this->data['row']->days=$this->jcustommultiselect('app_days_t','days_id','days',$days,'');

                if($sql[0]->stockist_id != "null" && $sql[0]->stockist_id !='')
                {

                    $stockist= implode(',',json_decode($sql[0]->stockist_id));

                }else{

                    $stockist = '';

                }
                $this->data['row']->stockist_id=$this->jcustommultiselect('app_stockist_t','stockist_id','stockist_name',$stockist,'');

                if($sql[0]->chemist_id != "null" && $sql[0]->chemist_id != ""){
                    $chemist = implode(',',json_decode($sql[0]->chemist_id));
                }else{
                    $chemist = '';
                }
                $this->data['row']->chemist_id=$this->jcustommultiselect('app_chemist_t','chemist_id','chemist_name',$chemist,'');

                if($sql[0]->degree != "null" && $sql[0]->degree != ""){
                    $degree = implode(',',json_decode($sql[0]->degree));
                }else{
                    $degree = '';
                }
                $this->data['row']->degree=$this->jcustommultiselect('app_degree_t','degree_id','degree',$degree,'');
                
                $linestable = \DB::table('app_doctors_adr_t')->where('doctor_id',$id)->get();
                $this->data['linedata'] = $linestable;
                if($linestable->isNotEmpty()){
                    foreach($this->data['linedata']  as $key=>$value)
                    {
                        $this->data['linedata'][$key]->area=$this->jCombologin('m_area_t','area_id','area_name',$value->area);
                        $this->data['linedata'][$key]->country_id=$this->jCombologin('m_countries_t','country_id','country_name',$value->country_id);
                        $this->data['linedata'][$key]->state_id=$this->jCombologin('m_states_t','state_id','state_name',$value->state_id);
                        $this->data['linedata'][$key]->city_id=$this->jCombologin('m_cities_t','city_id','city_name',$value->city_id);
                    }
                }
            }
        }

        return view('doctor.form',$this->data);
    }

  
    public function save(Request $request)
    {
        $id='';
        $data = $this->validatePost($request->all(),$this->table,'header');
        $lines_data = $this->validatePost($request->all(),$this->subtable,'lines'); 
        \DB::beginTransaction();
        try{
            $data['days']=json_encode($_POST['days']);
            $data['degree']=json_encode($_POST['degree']);
            $data['chemist_id']=json_encode($_POST['chemist_id']);
            $data['stockist_id']=json_encode($_POST['stockist_id']);
            $data['doctor_name'] = $_POST['doctor_name'];
            $data['doctor_name'] = $_POST['doctor_name'];
            $data['specialization']=json_encode($_POST['specialization']);
            $data['doa']=date('Y-m-d',strtotime($_POST['doa']));
            $data['dob']=date('Y-m-d',strtotime($_POST['dob']));
            
            $id=$this->model->insertRow($data);
            unset($lines_data['degree']);
            unset($lines_data['chemist_id']);
            unset($lines_data['days']);
            unset($lines_data['specialization']);
            unset($lines_data['stockist_id']);
            $lid = $this->submodel->subgridSave($lines_data, $id);
            unset($lines_data['removed_line_id']);
            unset($data['removed_line_id']);

            $insert_data=[];
            $history_id = \DB::table('app_historydoctors_t')->insertGetId($data);
            foreach ($_POST['doctors_adr_id'] as $key => $value)
            {
                $insert_data['doctor_his_id']=$history_id;
                $insert_data['area']=$lines_data['area'][$key];
                $insert_data['doctor_address']=$lines_data['doctor_address'][$key];
                $insert_data['concat_address']=$lines_data['concat_address'][$key];
                $insert_data['area']=$lines_data['area'][$key];
                $insert_data['pin_code']=$lines_data['pin_code'][$key];
                $insert_data['created_by']=$lines_data['created_by'][$key];
                $insert_data['created_at']=$lines_data['created_at'][$key];
                $insert_data['last_updated_by']=$lines_data['last_updated_by'][$key];
                $insert_data['updated_at']=$lines_data['updated_at'][$key];
                $history_line_id = \DB::table('app_historydoctors_adr_t')->insert($insert_data);
            }
            
            
             \DB::commit();
            return response()->json(array('status' => 'success', 'message' =>'Saved Successfully','id' => $id));
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
             // dd($dbCode);
            $dbCode = trim($dbCode, '[');
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }

    public function show(Doctor $doctor,$id=null)
    {   
        $sql = \DB::table('app_doctors_t')->where('doctor_id',$id)->get();
        $linesdata = \DB::table('app_doctors_adr_t')->where('doctor_id',$id)->get();
        $this->data['linesdata']=$linesdata;
        $this->data['doctor']=$sql[0];
        
        if($sql[0]->grade){
            $this->data['grade']=$this->idname('grade','app_grade_t','grade_id',$sql[0]->grade);
        }else{
            $this->data['grade']='';
        }
        if($sql[0]->specialization && $sql[0]->specialization != "null"){
            $sqpl =(json_decode($sql[0]->specialization));
            $specialization='';
            foreach ($sqpl as $key => $value) {
                $specialization.=$this->idname('specialization','app_specialization_t','specialization_id',$value).',';
            
            }
            $this->data['specialization']=rtrim($specialization,',');
        }else{
            $this->data['specialization']='';
        }
        if($sql[0]->chemist_id != "null" && $sql[0]->chemist_id != ''){
            $sqpl =(json_decode($sql[0]->chemist_id));
            $chemist_id='';
            foreach ($sqpl as $key => $value) {
                $chemist_id.=$this->idname('chemist_name','app_chemist_t','chemist_id',$value).',';
            
            }
            $this->data['chemist']=rtrim($chemist_id,',');                
        }else{
            $this->data['chemist']='';
        }
         if($sql[0]->stockist_id != "null" && $sql[0]->stockist_id != ''){
            $sqpl =(json_decode($sql[0]->stockist_id));
            $stockist='';
            foreach ($sqpl as $key => $value) {
                $stockist.=$this->idname('stockist_name','app_stockist_t','stockist_id',$value).',';
            
            }
            $this->data['stockist']=rtrim($stockist,',');                
        }else{
            $this->data['stockist']='';
         
                 }
        if($sql[0]->days != "null" && $sql[0]->days != ''){
            $sqpl =(json_decode($sql[0]->days));
            $days='';
            foreach ($sqpl as $key => $value) {
                $days.=$this->idname('days','app_days_t','days_id',$value).',';
            
            }
            $this->data['days']=rtrim($days,',');                
        }else{
            $this->data['days']='';
        }
        if($sql[0]->degree != "null" && $sql[0]->degree != ''){
            $sqpl =(json_decode($sql[0]->degree));
            $degree='';
            foreach ($sqpl as $key => $value) {
                $degree.=$this->idname('degree','app_degree_t','degree_id',$value).',';
            
            }
            $this->data['degree']=rtrim($degree,',');                
        }else{
            $this->data['degree']='';
        }
        
        if($sql[0]->area)
            $this->data['area']=$this->idname('area_name','m_area_t','area_id',$sql[0]->area);
        else
            $this->data['area']='';
        
        

        if(count($linesdata) > 0 ){
            foreach($linesdata as $k => $v){
                if($v->area){
                    $v->country_id =$this->idname('country_name','m_countries_t','country_id',$v->country_id);
                    $v->state_id =$this->idname('state_name','m_states_t','state_id',$v->state_id);
                    $v->city_id =$this->idname('city_name','m_cities_t','city_id',$v->city_id);
                    $v->area = $this->idname('area_name','m_area_t','area_id',$v->area);
                }
            }
        }

        return view('doctor.view',$this->data);
    }

    public function getDrphonechk(Request $request)
    { 
        $arr = array();
        $edit_id = $_GET['edit_id'];

        if($edit_id == ''){
            $department=DB::table('app_doctors_t')->where('doctor_phone_number',$_GET['doctor_phone_number'])->get();
            $email=DB::table('app_doctors_t')->where('doctor_email_id',$_GET['doctor_email_id'])->get();
        }
        else
        {
            $whereData = [['doctor_phone_number', $_GET['doctor_phone_number']],['doctor_id', '!=', $edit_id]];
            $emailData = [['doctor_email_id', $_GET['doctor_email_id']],['doctor_id', '!=', $edit_id]];

            $department=DB::table('app_doctors_t')->where($whereData)->get();
            $email=DB::table('app_doctors_t')->where($emailData)->get();
        }

        if(count($department)>0 || count($email) > 0)
        {
            if(count($department) > 0 )
                array_push($arr, 1);
            if(count($email) > 0 )
                array_push($arr, 2);

            return $arr;
        }
        else{
            array_push($arr, 0);
            return $arr;
        }
    }


    public function destroy(Doctor $doctor,$id=null)
    {
        $count=0;
        if($count <= 0)
        {
            $query = \DB::table('app_doctors_t')->where('doctor_id',$id)->delete();
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

    public function getdoctorData()   
    {
        $wh='';
        $search_table=array("");
        if($_GET['_search']=='true')
        {
            $wh=$this->jqgridsearch('app_doctors_t',$_GET['filters'],$search_table);
        }
        

        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;
         
        $result = \DB::select("SELECT count(app_doctors_t.doctor_id) as count FROM `app_doctors_t` where 1=1 $wh");

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
        
        $emp=\Session::get('id');
        $sql="SELECT * FROM `app_doctors_t` left join `tb_users` on (tb_users.id=app_doctors_t.created_by) where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
          
        $result = \DB::select($sql);
        // dd($result);
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }

      /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Doctor $doctor)
    {
        //
    }
}
