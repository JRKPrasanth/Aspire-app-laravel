<?php

namespace App\Http\Controllers;

use App\Missedcallreport;
use Illuminate\Http\Request;

class MissedcallreportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

      public function index()
    {
        $this->data['tour_area'] ='';
        $this->data['urlmenu']=$this->indexs(); 
            
        return view("missedcallreport.form",$this->data);
    }

    public function mcrreport($date=null){

        $dt = date( 'Y-m-d', strtotime($date));
        $t_dr=[];
        $ar_dr=[];
        $m_dr=[];
        $m_ar=[];
        $sql=[];
        
        $plan = \DB::table('app_doctor_dcr_t')
                ->select('*')
                ->where('tp_date',$dt)
                ->get();
        
        foreach ($plan as $key => $value) {
            $t_dr[] = $value->doctor_id;
            $ar_dr[] = $value->from_area;
        }
        
        $sql1 =\DB::table('app_tourprogram_t')
                ->select('*')
                ->wheredate('tour_date',$dt)
                ->where('status','=','APPROVED')
                ->get();
        
// dd($sql1);
        if(count($sql1) >0 ){
            if(count($ar_dr) > 0){
                $tour_area = $sql1[0]->tour_area;
                $t_ar = explode(',', $tour_area);
                $mcrrep['result'] = $sql  = \DB::table('app_doctors_adr_t')
                        ->select('app_doctors_t.doctor_id','app_doctors_t.doctor_name','app_doctors_t.doctor_phone_number','app_doctors_t.doctor_email_id','app_doctors_t.days','app_doctors_adr_t.doctor_address','app_doctors_adr_t.area','m_area_t.area_name','app_grade_t.grade')
                        ->leftjoin('app_doctors_t','app_doctors_adr_t.doctor_id','=','app_doctors_t.doctor_id')
                        ->leftjoin('app_doctor_dcr_t','app_doctor_dcr_t.doctor_id','=','app_doctors_t.doctor_id')
                        ->leftjoin('m_area_t','m_area_t.area_id','=','app_doctors_adr_t.area')
                        ->leftjoin('app_grade_t','app_grade_t.grade_id','=','app_doctors_t.grade')
                        ->where('app_doctor_dcr_t.tp_date','=',$dt)
                        ->where('app_doctors_adr_t.area','!=',$ar_dr)
                        ->groupby('app_doctors_adr_t.doctors_adr_id')
                        ->get();
        //  dd($sql);
                foreach ($sql as $k => $v) {
                    $m_dr[] = $v->doctor_id;
                    $m_ar[] = $v->area;
                }
            }else{
                $mcrrep['status']="DCRNULL";
            }
        }else{
            $mcrrep['status']="NULL";
        }
     
       if(count($sql) > 0 ){

            foreach ($sql as $key => $value) {            
                $ddd = json_decode($value->days);
                if($ddd){
                    $mcrrep['days'][]= \DB::table('app_days_t')->whereIn('days_id',$ddd)->select('days')->get();
                }else{
                    $mcrrep['days'][]='';
                }
            }
        }else{
            $mcrrep['days'][]='';
        }
        
        return $mcrrep;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null)
    {
       
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
     * @param  \App\Missedcallreport  $missedcallreport
     * @return \Illuminate\Http\Response
     */
    public function show(Missedcallreport $missedcallreport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Missedcallreport  $missedcallreport
     * @return \Illuminate\Http\Response
     */
    public function edit(Missedcallreport $missedcallreport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Missedcallreport  $missedcallreport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Missedcallreport $missedcallreport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Missedcallreport  $missedcallreport
     * @return \Illuminate\Http\Response
     */
    public function destroy(Missedcallreport $missedcallreport)
    {
        //
    }

    public function getmcrdata()   
    { 

        //$emp_id=1;
        
        $wh='';
        $search_table=[];
        // $search_table[]="m_area_t";
        // $search_table[]="hr_employee_t";
        // $search_table[]="m_tour_type_tbl";
        if($_GET['_search']=='true')
        {
            $wh=$this->jqgridsearch('app_missedcall_rpt_t',$_GET['filters'],$search_table);

        }
        

        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;

        $result = \DB::select("SELECT COUNT(missedcall_rpt_id) AS count FROM app_missedcall_rpt_t where 1=1 $wh");

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
        
        $sql="SELECT * from app_missedcall_rpt_t where 1=1 $wh ORDER BY missedcall_rpt_id desc";

        $result = \DB::select($sql);
        
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
        
        
    }

}
