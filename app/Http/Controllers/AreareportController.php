<?php

namespace App\Http\Controllers;

use App\areareport;
use Illuminate\Http\Request;

class AreareportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
          
            $this->data['tour_area'] ='';
            $this->data['month'] =$this->jCombologin('m_month_t','month_id','month_name','');
            $this->data['urlmenu']=$this->indexs(); 
            return view("areareport.form",$this->data);
        
    }

    public function chemistreport(){
        $employee_id=\Session::get('emp_id');

        $areasql = \DB::table('hr_employee_t')->where('employee_id',$employee_id)->get();
        $area = json_decode($areasql[0]->med_rep_area);
        $days=[];
        $sql = \DB::table('app_chemist_t')
                ->leftjoin('m_area_t','m_area_t.area_id','=','app_chemist_t.territory_name')
                ->leftjoin('app_chemist_detail_t','app_chemist_detail_t.chemist_id','=','app_chemist_t.chemist_id')
                ->leftjoin('app_doctors_t','app_doctors_t.doctor_id','=','app_chemist_detail_t.doctor_id')
                ->whereIn('app_chemist_t.territory_name',$area)
                ->select('app_chemist_t.chemist_id','app_chemist_t.chemist_name','app_chemist_t.chemist_address','app_doctors_t.doctor_name','m_area_t.area_name as territory_name','app_chemist_t.chemist_phone','app_chemist_t.chemist_mobile')
                ->orderBy('app_chemist_t.chemist_name')
                ->get();

        return $sql;

    }   

    public function tourplanreport(){
        $employee_id=\Session::get('emp_id');
        $areasql = \DB::table('hr_employee_t')->where('employee_id',$employee_id)->get();

        if($areasql->isNotEmpty()){
            if($areasql[0]->department == 1){
                // Area Manager
                $emp_id=[];
                $empl = \DB::table('hr_employee_t')->where('reporting_manager',$employee_id)->select('employee_id')->get();
                foreach ($empl as $key => $value) {
                    $emp_id[] = $value->employee_id;
                }
                $tp = \DB::table('app_tourprogram_t')
                    ->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','app_tourprogram_t.employee_id')
                    ->select('app_tourprogram_t.tour_date','app_tourprogram_t.tour_area','app_tourprogram_t.doctor_id','hr_employee_t.first_name','app_tourprogram_t.status')
                    ->whereIn('app_tourprogram_t.employee_id',$emp_id)
                    ->orderBy('hr_employee_t.first_name','app_tourprogram_t.status')
                    ->get();
                foreach ($tp as $ke => $val) {
                    if($val->tour_area !=''){
                        $t_ar='';
                        $t_id = json_decode($val->tour_area);
                        $t_ql =\DB::table('m_area_t')->select('area_name')->whereIn('area_id',$t_id)->get();
                        foreach ($t_ql as $k1 => $v1) {
                            $t_ar.=$v1->area_name.',';
                        }
                        $area_name = rtrim($t_ar,',');
                        $val->tour_area=$area_name;
                    }else{
                        $val->tour_area='';
                    }

                    if($val->doctor_id !=''){
                        $dc_id = [];
                        $doc_id = json_decode($val->doctor_id);
                        foreach ($doc_id as $k => $v) {
                            $dc_id[]=$k;
                        }
                        
                        $doc_dd = \DB::table('app_doctors_adr_t')
                                ->leftjoin('app_doctors_t','app_doctors_t.doctor_id','=','app_doctors_adr_t.doctor_id')
                                ->leftjoin('m_area_t','m_area_t.area_id','=','app_doctors_adr_t.area')
                                ->whereIn('app_doctors_adr_t.doctors_adr_id',$dc_id)
                                ->select('app_doctors_adr_t.doctor_id','app_doctors_t.doctor_name','m_area_t.area_name')
                                ->get();
                        // dd($doc_dd);
                        $d_name='';
                        foreach ($doc_dd as $key1 => $value1) {
                            $d_name.=$value1->doctor_name.'-'.$value1->area_name.',';
                        }
                        $doctor_name = rtrim($d_name,',');
                        $val->doctor_id=$doctor_name;
                    }else{
                        $val->doctor_id='';
                    }
                }                 
            }else{
                //employee
                $tp = \DB::table('app_tourprogram_t')
                    ->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','app_tourprogram_t.employee_id')
                    ->select('app_tourprogram_t.tour_date','app_tourprogram_t.tour_area','app_tourprogram_t.doctor_id','hr_employee_t.first_name','app_tourprogram_t.status')
                    ->where('app_tourprogram_t.employee_id',$employee_id)
                    ->orderBy('hr_employee_t.first_name','app_tourprogram_t.status')
                    ->get();
            
                foreach ($tp as $ke => $val) {
                    if($val->tour_area !=''){
                        $t_ar='';
                        $t_id = json_decode($val->tour_area);
                        $t_ql =\DB::table('m_area_t')->select('area_name')->whereIn('area_id',$t_id)->get();
                        foreach ($t_ql as $k1 => $v1) {
                            $t_ar.=$v1->city_name.',';
                        }
                        $area_name = rtrim($t_ar,',');
                        $val->tour_area=$area_name;
                    }else{
                        $val->tour_area='';
                    }

                    if($val->doctor_id !=''){
                        $dc_id = [];
                        $doc_id = json_decode($val->doctor_id);
                        foreach ($doc_id as $k => $v) {
                            $dc_id[]=$k;
                        }
                        
                        $doc_dd = \DB::table('app_doctors_adr_t')
                                ->leftjoin('app_doctors_t','app_doctors_t.doctor_id','=','app_doctors_adr_t.doctor_id')
                                ->leftjoin('m_area_t','m_area_t.area_id','=','app_doctors_adr_t.area')
                                ->whereIn('app_doctors_adr_t.doctors_adr_id',$dc_id)
                                ->select('app_doctors_adr_t.doctor_id','app_doctors_t.doctor_name','m_area_t.area_name')
                                ->get();
                        // dd($doc_dd);
                        $d_name='';
                        foreach ($doc_dd as $key1 => $value1) {
                            $d_name.=$value1->doctor_name.'-'.$value1->area_name.',';
                        }
                        $doctor_name = rtrim($d_name,',');
                        $val->doctor_id=$doctor_name;
                    }else{
                        $val->doctor_id='';
                    }
                } 
            }
        }
        // dd($tp);
        return $tp;
    } 

    public function stockistreport(){
        $employee_id=\Session::get('emp_id');

        $areasql = \DB::table('hr_employee_t')->where('employee_id',$employee_id)->get();
        $area = json_decode($areasql[0]->med_rep_area);
        $days=[];
        $sql = \DB::table('app_stockist_t')
                ->leftjoin('m_area_t','m_area_t.area_id','=','app_stockist_t.teritory_name')
                ->whereIn('app_stockist_t.teritory_name',$area)
                ->select('app_stockist_t.stockist_id','app_stockist_t.stockist_name','app_stockist_t.stockist_category','app_stockist_t.stockist_address','m_area_t.area_name as teritory_name','app_stockist_t.contact_person','app_stockist_t.stockist_phone')
                ->orderBy('app_stockist_t.stockist_name')
                ->get();


        return $sql;

    }

    public function areachangerpt($mth= null){

        $sql = \DB::table('app_historydoctors_adr_t')
                    ->leftjoin('app_historydoctors_t','app_historydoctors_t.doctor_his_id','=','app_historydoctors_adr_t.doctor_his_id')
                    ->leftjoin('m_area_t','m_area_t.area_id','=','app_historydoctors_adr_t.area')
                    ->leftjoin('app_grade_t','app_grade_t.grade_id','=','app_historydoctors_t.grade')
                    ->whereMonth('app_historydoctors_adr_t.created_at',$mth)
                    ->select('app_historydoctors_t.doctor_name','app_historydoctors_t.doctor_phone_number','app_historydoctors_t.doctor_email_id','app_historydoctors_adr_t.doctor_address','app_historydoctors_adr_t.area','m_area_t.area_name','app_grade_t.grade','app_historydoctors_t.created_at')
                    ->orderBy('app_historydoctors_t.created_at','app_historydoctors_t.city_name')
                    ->get();
        // dd($sql);
        return $sql;
    }

    public function doccategory($mth=null){
        $sql = \DB::table('app_historydoctors_adr_t')
                    ->leftjoin('app_historydoctors_t','app_historydoctors_t.doctor_his_id','=','app_historydoctors_adr_t.doctor_his_id')
                    ->leftjoin('m_area_t','m_area_t.area_id','=','app_historydoctors_adr_t.area')
                    ->leftjoin('app_grade_t','app_grade_t.grade_id','=','app_historydoctors_t.grade')
                    ->whereMonth('app_historydoctors_t.created_at',$mth)
                    ->select('app_historydoctors_t.doctor_name','app_historydoctors_t.doctor_phone_number','app_historydoctors_t.doctor_email_id','app_historydoctors_adr_t.doctor_address','app_historydoctors_adr_t.area','m_area_t.area_name','app_grade_t.grade','app_historydoctors_t.created_at')
                    ->orderBy('app_historydoctors_t.created_at','app_historydoctors_t.grade')
                    ->get();

        return $sql;
    }


    public function doctorreport(){

        $employee_id=\Session::get('emp_id');
        $areasql = \DB::table('hr_employee_t')->where('employee_id',$employee_id)->get();
        $area = json_decode($areasql[0]->med_rep_area);
        // dd($area);
        $days=[];
        $days['result']= $sql = \DB::table('app_doctors_adr_t')
                    ->leftjoin('app_doctors_t','app_doctors_t.doctor_id','=','app_doctors_adr_t.doctor_id')
                    ->leftjoin('m_area_t','m_area_t.area_id','=','app_doctors_adr_t.area')
                    ->leftjoin('app_grade_t','app_grade_t.grade_id','=','app_doctors_t.grade')
                    ->whereIn('app_doctors_adr_t.area',$area)
                    ->select('app_doctors_t.doctor_name','app_doctors_t.doctor_phone_number','app_doctors_t.doctor_email_id','app_doctors_t.days','app_doctors_adr_t.doctor_address','app_doctors_adr_t.area','m_area_t.area_name','app_doctors_t.specialization','app_grade_t.grade')
                    ->orderBy('m_area_t.area_name')
                    ->get();
// dd($sql);
        
        if(count($sql) > 0 ){

            foreach ($sql as $key => $value) {
            
                $ddd = json_decode($value->days);
                $specialization = json_decode($value->specialization);
                if($ddd){
                    $days['days'][]= \DB::table('app_days_t')->whereIn('days_id',$ddd)->select('days')->get();
                }else{
                    $days['days'][]='';
                }

                if($specialization){
                    $days['specialization'][]= \DB::table('app_specialization_t')->whereIn('specialization_id',$specialization)->select('specialization')->get();
                }else{
                    $days['specialization'][]='';
                }
            }
        }else{
            $days['days'][]='';
            $days['specialization'][]='';
        }
        // dd($days['specialization']);
        return $days;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\areareport  $areareport
     * @return \Illuminate\Http\Response
     */
    public function show(areareport $areareport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\areareport  $areareport
     * @return \Illuminate\Http\Response
     */
    public function edit(areareport $areareport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\areareport  $areareport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, areareport $areareport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\areareport  $areareport
     * @return \Illuminate\Http\Response
     */
    public function destroy(areareport $areareport)
    {
        //
    }
}
