<?php

namespace App\Http\Controllers;

use App\Sfaexpenses;
use Illuminate\Http\Request;
use DB;

class SfaexpensesController extends Controller
{
    public function __construct()
    {
        $this->model    = new Sfaexpenses();
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['urlname']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        $this->data['pageModule']='sfaexpenses';
        // $this->table="app_doctors_t";
        $this->subtable="app_sfaexpenses_t";
    }

    public function index()
    {
        $this->model    = new Sfaexpenses();
        $this->data=array();
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['urlname']=\Request::route()->getName();
            
        return view("sfaexpenses.table",$this->data);
    }

    public function sfaexpensesdata($month=null,$year=null){
        
        $id = \Session::get('id');
        $emp_id = \Session::get('emp_id');
        $linedata = $sql = DB::table('app_tourprogram_t')->where('status','=','APPROVED')->where('created_by',$id)->whereMonth('tour_date',$month)->whereYear('tour_date',$year)->select('tour_date','tour_details as from_area','tour_area as to_area','approval_remarks as doctor_ct','remarks as chemist_ct','employee_id as town','last_updated_by as distance','organization_id as fare')->get();
        
        if(count($sql) > 0){
            foreach ($sql as $key => $value) {
                $doc_count = DB::table('app_doctor_dcr_t')->where('created_by',$id)->whereDate('tp_date',$value->tour_date)->select(DB::raw('count(doctor_id) as doc_count'))->get();
                $che_count = DB::table('app_chemist_dcr_t')->where('created_by',$id)->whereDate('tp_date',$value->tour_date)->select(DB::raw('count(chemist_name) as che_count'))->get();
                $towm_id = DB::select('select m_area_t.city_id,m_area_t.teritory_type,m_teritory_type_t.allowance_amt from m_area_t left join m_teritory_type_t on(m_teritory_type_t.teritory_type_id = m_area_t.teritory_type ) where m_area_t.area_id in ('.$value->to_area.') group by m_area_t.city_id ');
                
                if(count($doc_count)>0){
                   $value->doctor_ct =$doc_count[0]->doc_count;
                }else{
                    $value->doctor_ct = 0;
                }
                if(count($che_count)>0){
                   $value->chemist_ct =$che_count[0]->che_count;
                }else{
                    $value->chemist_ct = 0;
                }
                if(count($towm_id)>0){
                   $value->town =$this->jCombologin('m_cities_t','city_id','city_name',$towm_id[0]->city_id);
                   $value->fare = $towm_id[0]->teritory_type;
                }else{
                    $value->town =$this->jCombologin('m_cities_t','city_id','city_name','');
                    $value->fare =0;
                }
                $value->from_area=$this->jCombologin('m_area_t','area_id','area_name','');
                $value->to_area=$this->jCombologin('m_area_t','area_id','area_name','');

                $login = DB::select("SELECT * FROM app_employee_log_t where employee_id=".$emp_id." and date(s_timestamp)='".$value->tour_date."' and login_status='3' order by s_timestamp asc");    

                $logout = DB::select("SELECT * FROM app_employee_log_t where employee_id=".$emp_id." and date(s_timestamp)='".$value->tour_date."' and login_status='2' ");  
                $lat2=0;
                $long2=0;
                $dist=0;
             
                if((count($login)) > 0 && (count($logout) > 0 )){
                    for ($j=0; $j < sizeof($login); $j++) { 
                        
                        $lat1 = $login[$j]->s_latitude;
                        $long1 =$login[$j]->s_longitude;

                        if($lat2!=0 || $long2!=0)
                        {

                            $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=pl-PL&key=AIzaSyCzvVOOlkO8F185YimtbF47H3efcT5e5jY";
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                            $response = curl_exec($ch);
                            curl_close($ch);
                            $response_a = json_decode($response, true);
                            $dist += (float)$response_a['rows'][0]['elements'][0]['distance']['text'];
 
                        }
                        $lat2=$lat1;
                        $long2=$long1;
                    }
                    if($logout[0]->e_latitude != 0 && $logout[0]->e_longtitude != 0){
                        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat2.",".$long2."&destinations=".$logout[0]->e_latitude.",".$logout[0]->e_longtitude."&mode=driving&language=pl-PL&key=AIzaSyCzvVOOlkO8F185YimtbF47H3efcT5e5jY";
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        $response = curl_exec($ch);
                        curl_close($ch);
                        $response_a = json_decode($response, true);
                        $dist += (float)$response_a['rows'][0]['elements'][0]['distance']['text'];
                    }
                    $value->distance = round(($dist * 1.609),2);

                    $value->fare = $value->distance * $towm_id[0]->allowance_amt;
                }else{
                    $value->distance =0;
                    $value->fare =0;
                }

                $value->tour_date = date('d-m-Y',strtotime($value->tour_date));
            } 
        }
        // dd($sql);
        return $sql;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(){

        $cur_yr = date('Y');
        $mth = date('m');
        $curyr_val = DB::table('year')->where('year_name',$cur_yr)->get();
        $cur_mth = DB::table('m_month_t')->where('month_id',$mth)->get();

        $this->data['month']=$this->jCombologin('m_month_t','month_id','month_name',$cur_mth[0]->month_id);
        $this->data['year']=$this->jcustomselecttool('year','id','year_name',$curyr_val[0]->id,' and year_name >= '.$cur_yr);

        $this->data['linedata'] = array();
        $this->data['linedata'] = $this->sfaexpensesdata($mth,$cur_yr);
        
        
        // dd($this->data);
        
        return view('sfaexpenses.form',$this->data);
        
    }

    public function getsfaexpensesData()   
    {
        $wh='';
        $search_table=array("m_cities_t");

        if($_GET['_search']=='true')
        {
            $wh=$this->jqgridsearch('app_sfaexpenses_t',$_GET['filters'],$search_table);
        }
        

        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;
         
        $result = \DB::select("SELECT count(app_sfaexpenses_t.sfaexpenses_id) as count FROM `app_sfaexpenses_t` left join m_cities_t on(m_cities_t.city_id = app_sfaexpenses_t.town_id) left join `tb_users` on (tb_users.id=app_sfaexpenses_t.created_by) where 1=1 $wh");

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
        $sql="SELECT * FROM `app_sfaexpenses_t` left join m_cities_t on(m_cities_t.city_id = app_sfaexpenses_t.town_id) left join `tb_users` on (tb_users.id=app_sfaexpenses_t.created_by) where 1=1 and app_sfaexpenses_t.created_by='".$emp."' $wh ORDER BY $sidx $sord LIMIT $start , $limit";
          
        $result = \DB::select($sql);
        // dd($result);
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }

    public function old_function()
    {

        $this->data = array();
        $cur_yr = date('Y');
        $mth = date('m');
        $curyr_val = DB::table('year')->where('year_name',$cur_yr)->get();
        $cur_mth = DB::table('m_month_t')->where('month_id',$mth)->get();

        $this->data['month']=$this->jCombologin('m_month_t','month_id','month_name',$cur_mth[0]->month_id);
        $this->data['year']=$this->jcustomselecttool('year','id','year_name',$curyr_val[0]->id,' and year_name >= '.$cur_yr);

        $month = 11;
        $year = 2018;
        $emp_id = \Session::get('emp_id');
        $id = \Session::get('id');
        $date =  date('Y-m-d');
        $over_km =0;
        $query = DB::table('app_tourprogram_t')->where('status','=','APPROVED')->where('created_by',$id)->whereMonth('tour_date',$month)->whereYear('tour_date',$year)->get();
        for ($i=0; $i < sizeof($query); $i++) { 
            $dt = $query[$i]->tour_date;
            $sql1 = DB::select("SELECT * FROM app_employee_log_t where employee_id=".$emp_id." and date(s_timestamp)='".$dt."' and login_status='2' order by s_timestamp asc");    

            $sql2 = DB::select("SELECT * FROM app_employee_log_t where employee_id=".$emp_id." and date(s_timestamp)='".$dt."' and login_status='1' order by s_timestamp desc");  
            if((count($sql2)) > 0 && (count($sql2) > 0 )){

                for ($j=0; $j < sizeof($sql1); $j++) { 

                    $lat1 = $sql1[$j]->s_latitude;
                    $lat2 =$sql1[$j]->s_longitude;
                    $lon1 = $sql2[$j]->s_latitude;
                    $lon2 =$sql2[$j]->s_longitude;
                    
                    // convert from degrees to radians  
                    $theta = $lon1 - $lon2;
                    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                    $dist = acos($dist);
                    $dist = rad2deg($dist);
                    $miles = $dist * 60 * 1.1515;
                        
                    $km = round(($miles * 1.609344),2);
                    $over_km = $over_km+ $km;
                }
            }
        }
        // dd($this->data);
        
       

        return view('sfaexpenses.form',$this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $id='';
        $lines_data = $this->validatePost($request->all(), $this->subtable, 'lines');
        \DB::beginTransaction();
        try{
            foreach ($_POST['expenses_id'] as $key => $value) {
                $exp['tour_date']=date('Y-m-d',strtotime($_POST['tour_date'][$key]));
                $exp['town_id']=$_POST['town_id'][$key];
                $exp['doctor_count']=$_POST['doctor_ct'][$key];
                $exp['chemist_count']=$_POST['chemist_ct'][$key];
                $exp['travelled_from']=$_POST['from_area'][$key];
                $exp['travelled_to']=$_POST['to_area'][$key];
                $exp['distance']=$_POST['distance'][$key];
                $exp['fare']=$_POST['fare'][$key];
                $exp['daily_allow']=$_POST['daily_allow'][$key];
                $exp['post_tele']=$_POST['post_tele'][$key];
                $exp['line_total']=$_POST['total_exp'][$key];
                $exp['overall_total']=$_POST['total_expenses'];
                $exp['location_id'] = \Session::get('location');
                $exp['company_id'] = \Session::get('companyid');
                $exp['organization_id'] = \Session::get('organization');
                $exp['created_by'] = \Session::get('id');
                $exp['updated_by'] = \Session::get('id');
                $exp['created_at'] = date('Y-m-d H:s:i');
                $exp['updated_at'] = date('Y-m-d H:s:i');
// dd($exp);
                \DB::table('app_sfaexpenses_t')->insert($exp);
            }
            
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' =>'Saved Successfully','id' => $id));
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
            
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sfaexpenses  $sfaexpenses
     * @return \Illuminate\Http\Response
     */
    public function view(Sfaexpenses $sfaexpenses,$id=null)
    {
        $sql = \DB::table('app_sfaexpenses_t')
                ->leftjoin('m_cities_t','m_cities_t.city_id','=','app_sfaexpenses_t.town_id')
                ->leftjoin('m_area_t as from','from.area_id','=','app_sfaexpenses_t.travelled_from')
                ->leftjoin('m_area_t as to','to.area_id','=','app_sfaexpenses_t.travelled_to')
                ->select('app_sfaexpenses_t.*','m_cities_t.city_name','from.area_name as from_area','to.area_name as to_area')
                ->where('app_sfaexpenses_t.sfaexpenses_id',$id)->get();

        $this->data['data']=$sql[0];
// dd($sql[0]);
        return view('sfaexpenses.view',$this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sfaexpenses  $sfaexpenses
     * @return \Illuminate\Http\Response
     */
    public function edit(Sfaexpenses $sfaexpenses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sfaexpenses  $sfaexpenses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sfaexpenses $sfaexpenses)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sfaexpenses  $sfaexpenses
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sfaexpenses $sfaexpenses)
    {
        //
    }
}
