<?php

namespace App\Http\Controllers;

use App\Mapping;
use Illuminate\Http\Request;

class MappingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['employee_id'] =$this->jCombo('hr_employee_t','employee_id','first_name','');
        $this->data['urlmenu']=$this->indexs(); 
        return view("mapping.form",$this->data);
    }


    public function locationdata($emp_id=null,$date=null)
    {
        $org=\Session::get('organization');

        if($emp_id!=0)
        {
            $date = date('Y-m-d',strtotime($date));
            $user_id=$emp_id;
            if($date!=0)
            {
                // if($from_time!=0)
                // {
                //     if($to_time!=0)  
                //     {
                //         $from_time=$from_time.":00";  
                //         $to_time=$to_time.":00";  

                //         $location_data=\DB::select("select * from app_employee_log_t where employee_id='$user_id' and s_timestamp='$date' and org_id='$org' ORDER BY s_timestamp ASC");  
                //     } else {
                //         $from_time=explode(":",$from_time);
                //         $from_time=$from_time[0];
                //         $location_data=\DB::select("select * from app_employee_log_t where employee_id='$user_id' and s_timestamp='$date' and org_id='$org' and s_timestamp like '$from_time%' ORDER BY s_timestamp ASC");   
                //     }
                // } else {
                    $location_data=\DB::select("select * from app_employee_log_t where employee_id=".$user_id." and org_id=".$org." and DATE(s_timestamp)='".$date."'");
                    // dd($location_data);
                    
                }
            } else {
                $location_data=\DB::select("select * from app_employee_log_t where employee_id=".$user_id." and org_id=".$org." ORDER BY s_timestamp ASC");     
            }

        // } elseif($date!=0){
            // if($from_time!=0)
            // {
            //     if($to_time!=0)  
            //     {
            //         $from_time=$from_time.":00";  
            //         $to_time=$to_time.":00";  
            //         $location_data=\DB::select("select * from app_employee_log_t where s_timestamp='$date' and org_id='$org' and s_timestamp between '$from_time' and '$to_time' ORDER BY s_timestamp ASC");  
            //     } else {
            //         $from_time=explode(":",$from_time);
            //         $from_time=$from_time[0];
            //         $location_data=\DB::select("select * from app_employee_log_t where s_timestamp='$date' and org_id='$org' and s_timestamp like '$from_time%' ORDER BY s_timestamp ASC");    
            //     }
            // } else {
            //     $location_data=\DB::select("select * from app_employee_log_t where DATE(s_timestamp)='$date' and org_id='$org' ORDER BY s_timestamp ASC");     
            // } 
        // } elseif($from_time!=0) {
        //     if($to_time!=0)  
        //     {
        //         $from_time=$from_time.":00";  
        //         $to_time=$to_time.":00"; 
        //         $location_data=\DB::select("select * from app_employee_log_t where org_id='$org' and s_timestamp between '$from_time' and '$to_time' ORDER BY s_timestamp ASC"); 
        //     } else {
        //         $from_time=explode(":",$from_time);
        //         $from_time=$from_time[0];
        //         $location_data=\DB::select("select * from app_employee_log_t where  org_id='$org' and s_timestamp like '$from_time%' ORDER BY s_timestamp ASC");    
        //     } 
        // } else {
            // $location_data=0;
        // }
        
        if($location_data=="")
            $location_data=0;
       
      
       return $location_data;  
    }

    public function getMaplivedata()
    {
        $org=\Session::get('organization');
        $date=date('Y-m-d');
        $emp_data=\DB::select("select DISTINCT(app_employee_log_t.employee_id),tb_users.username,hr_employee_t.work_telephone_number,hr_employee_t.first_name,hr_employee_t.last_name from app_employee_log_t JOIN tb_users ON tb_users.id=app_employee_log_t.employee_id JOIN hr_employee_t ON hr_employee_t.employee_id=tb_users.id where DATE(s_timestamp)='$date' and app_employee_log_t.org_id='$org'");   
        $latlon_data=[];
        foreach($emp_data as $value)
        {
            $u_id=$value->employee_id;
            $latlon=\DB::select("select app_employee_log_t.s_latitude,app_employee_log_t.s_longitude,tb_users.username from app_employee_log_t JOIN tb_users ON tb_users.id=app_employee_log_t.employee_id  where DATE(app_employee_log_t.s_timestamp)='$date' and app_employee_log_t.employee_id='$u_id'  and app_employee_log_t.org_id='$org' ORDER by app_employee_log_t.id DESC limit 1");
            $latlon_data[]=[$latlon[0]->s_latitude,$latlon[0]->s_longitude,$latlon[0]->username];
        }
     $data['location_data']=$latlon_data;
     $data['emp_data']=$emp_data;
     return $data;
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
     * @param  \App\Mapping  $mapping
     * @return \Illuminate\Http\Response
     */
    public function show(Mapping $mapping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Mapping  $mapping
     * @return \Illuminate\Http\Response
     */
    public function edit(Mapping $mapping)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Mapping  $mapping
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mapping $mapping)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Mapping  $mapping
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mapping $mapping)
    {
        //
    }
}
