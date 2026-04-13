<?php

namespace App\Http\Controllers;

use App\Chemistdcr;
use Illuminate\Http\Request;

class ChemistdcrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->data=array();
        $this->model    = new Chemistdcr();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        $this->data['pageModule']='Chemistdcr';
        $this->table="app_chemist_dcr_t";
        
        $this->data['urlname']=\Request::route()->getName();
        
    }

    public function index()
    {
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['urlname']=\Request::route()->getName();
        $this->data['f_area'] = $this->jqgridselect('m_area_t','area_id','area_name');
        $this->data['ch_name'] = $this->jqgridselect('app_chemist_t','chemist_id','chemist_name');

        return view("chemistdcr.table",$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null)
    {
        $this->data['pageMethod']="chemistdcr";
         $sql=\Session::get('id');
   // dd($sql);
        $this->data['created_by']=$this->jCombologin('tb_users','id','first_name',$sql);
        if($id == '0')
        {
            $this->data['row'] = (object) array();
            $this->data['row']->chemist_dcr_id="";
            $this->data['row']->tp_date="";
            $this->data['pob_product']=$this->jcustomselect('m_products_t','product_id','concatenated_product','',"and product_group_id = '1'");
            $this->data['from_area']=$this->jCombologin('m_area_t','area_id','area_name','');
            $this->data['chemist_name']=$this->jCombologin('app_chemist_t','chemist_id','chemist_name','');
            $this->data['row']->order_no='';
            $this->data['row']->value='';
            $this->data['row']->remarks="";

        }else{

          
            $sql=\DB::table('app_chemist_dcr_t')->where('chemist_dcr_id',$id)->get();
          
            if($sql->isNotEmpty()){
                $this->data['row']=$sql[0];
                $this->data['row']->tp_date = date('d-m-Y',strtotime($sql[0]->tp_date));
                $this->data['from_area']=$this->jCombologin('m_area_t','area_id','area_name',$sql[0]->from_area);
                $this->data['chemist_name']=$this->jCombologin('app_chemist_t','chemist_id','chemist_name',$sql[0]->chemist_name);
                             
            }
        }

        /**************POB Details ***********/
        
        $poblines=\DB::table('app_chemist_odr_detail_t')->where('chemist_dcr_id',$id)->select('*')->get();
        $this->data['linepob']=$poblines;

        if($poblines->isNotEmpty()){
            foreach ($poblines as $k => $v) {
                $this->data['linepob'][$k]->product_order=$this->jcustomselect('m_products_t','product_id','concatenated_product',$v->product_order,"and product_group_id = '1'");   
            }
        }else{
            $this->data['linepob']->product_order=$this->jcustomselect('m_products_t','product_id','concatenated_product','',"and product_group_id = '1'");            
        }

        /**************POB Details End ***********/


        return view('chemistdcr.form',$this->data);

    }


    public function chemistdetail($id=null){
        
        $html='';
        if($id){
            $sql=\DB::table('app_chemist_t')->where('territory_name',$id)->select('chemist_name','chemist_id')->get();
                        
            $html.="<option value=''>-- Please Select --</option>";

            if($sql->isNotEmpty()){
                foreach ($sql as $key => $value) {
                    $html.= "<option value='".$value->chemist_id."'>".$value->chemist_name."</option>";
                }
            }
        }

        return $html;
    }

    public function save(Request $request){

        
        $id='';
        $data = $this->validatePost($request->all(),$this->table,'header');
        
        \DB::beginTransaction();
        try{

            $data['tp_date']=date('Y-m-d',strtotime($_POST['tp_date']));
            $id=$this->model->insertRow($data);
       
            /******************POB save start ***************************/

            $chemist_odr_detail_id=$_POST['bulk_chemist_odr_detail_id'];

            $check = \DB::table('app_chemist_odr_detail_t')->whereIn('chemist_odr_detail_id',$chemist_odr_detail_id)->select('*')->get();
            $oldid = \DB::table('app_chemist_odr_detail_t')->where('chemist_dcr_id',$id)->get();
            
            if($oldid->isNotEmpty()){
                $existingId = array();
                $oldIds = array();
                $newIds = array();
                foreach ($oldid as $key=> $value){
                    $oldIds[]= $value->chemist_odr_detail_id;
                }
                foreach ($_POST['bulk_chemist_odr_detail_id'] as $val){
                    $newIds[]= $val;
                }
                $existingId=  array_replace($newIds, $oldIds);
                $oldcount=count($oldIds);
                $newcount=count($newIds);
                
                
                    $arraydiff=  array_diff($oldIds, $newIds);
                    foreach ($arraydiff as $key)
                    {
                        if (($key = array_search($key, $oldIds)) !== false)
                        {
                            unset($oldIds[$key]);
                        }
                    }
                    
                    foreach($arraydiff as $val)
                    {
                        \DB::table('app_chemist_odr_detail_t')->where('chemist_odr_detail_id',$val)->delete();
                    }
                    foreach ( $chemist_odr_detail_id as $key => $value) 
                    {
                    
                        $datapob1['chemist_dcr_id']=$id;
                        $datapob1['product_order']=$_POST['bulk_product_order'][$key];
                        $datapob1['pob_qty']=$_POST['bulk_pob_qty'][$key];

                        if($value != ""){
                            $chemist_odr_detail_id=$_POST['bulk_chemist_odr_detail_id'][$key];
                            \DB::table('app_chemist_odr_detail_t')->where('chemist_odr_detail_id',$chemist_odr_detail_id)->update($datapob1);
                        }else{
                            \DB::table('app_chemist_odr_detail_t')->insert($datapob1);
                        }
                    }           
            }else{
                foreach ( $chemist_odr_detail_id as $key => $value) {
                    $datapob1['chemist_dcr_id']=$id;
                    $datapob1['product_order']=$_POST['bulk_product_order'][$key];
                    $datapob1['pob_qty']=$_POST['bulk_pob_qty'][$key];
                    \DB::table('app_chemist_odr_detail_t')->insert($datapob1);
                }
            }
            
            /***********************POB save end *********************/

            unset($data['removed_line_id']);

            $chemist_odr_detail_id=$_POST['bulk_chemist_odr_detail_id'];
            $data['chemist_dcr_id']=$id;

            $history_id =\DB::table('app_chemistdcr_history_t')->insertGetId($data); // for history table
            
            // for history table
            foreach ( $chemist_odr_detail_id as $key => $value) {
                $datahis['chemistdcr_history_id']=$history_id;
                $datahis['product_order']=$_POST['bulk_product_order'][$key];
                $datahis['pob_qty']=$_POST['bulk_pob_qty'][$key];
                \DB::table('app_chemist_odr_history_t')->insert($datahis);
            }
            // end


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

    public function destroy(Chemistdcr $chemistdcr,$id=null)
    {
        $count=0;
        if($count <= 0)
        {
            $query = \DB::table('app_chemist_dcr_t')->where('chemist_dcr_id',$id)->delete();
            
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
     * @param  \App\Chemistdcr  $chemistdcr
     * @return \Illuminate\Http\Response
     */
    public function show(Chemistdcr $chemistdcr,$id=null)
    {
        $sql = \DB::table('app_chemist_dcr_t')->where('chemist_dcr_id',$id)->get();   
        $linesdata = \DB::table('app_chemist_odr_detail_t')
                ->leftjoin('m_products_t','m_products_t.product_id','=','app_chemist_odr_detail_t.product_order')
                ->where('chemist_dcr_id',$id)->get();   
        if($sql->isNotEmpty()){
            $this->data['chemist_dcr']=$sql[0];        
            $this->data['from_area'] =$this->idname('area_name','m_area_t','area_id',$sql[0]->from_area);
            $this->data['chemist_name'] =$this->idname('chemist_name','app_chemist_t','chemist_id',$sql[0]->chemist_name);
            $this->data['linesdata'] =$linesdata;

        }
        return view('chemistdcr.view',$this->data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chemistdcr  $chemistdcr
     * @return \Illuminate\Http\Response
     */
    public function edit(Chemistdcr $chemistdcr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chemistdcr  $chemistdcr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chemistdcr $chemistdcr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chemistdcr  $chemistdcr
     * @return \Illuminate\Http\Response
     */

    public function chemistareas($date=null,$id=null){
        $emp_id = \Session::get('emp_id');
        $details=[];
        $n_area_id=[];
        $htmldata=[];
        
        $n_date=date('Y-m-d', strtotime($date));
        $sql = \DB::table('app_tourprogram_t')->wheredate('tour_date',$n_date)->where('status','APPROVED')->where('employee_id',$emp_id)->get();

        $dt = \DB::table('app_chemist_dcr_t')->where('chemist_dcr_id',$id)->get();

        if($dt->isNotEmpty()){
            if($sql->isNotEmpty()){
                foreach ($sql as $key => $value) {
                    $ctiy =json_decode($value->tour_area);
                    $details[]=\DB::table('m_area_t')->whereIn('area_id',$ctiy)->select('area_id')->get();
                }
                if( count($details) > 0 ){
                    foreach ($details as $k => $v) {
                        foreach ($v as $sk => $sv) {
                            $n_area_id[] = $sv->area_id;
                        }
                    }
                    $arr =array_unique($n_area_id);

                    $str = implode(',', $arr);
                    $htmldata['from_area'] = $this->jcustomselect('m_area_t','area_id','area_name',$dt[0]->from_area,"and area_id in ($str)");
                      
                }
            }
        }else{
            if($sql->isNotEmpty()){
                foreach ($sql as $key => $value) {
                    $ctiy =json_decode($value->tour_area);
                    $details[]=\DB::table('m_cities_t')->whereIn('city_id',$ctiy)->select('city_id')->get();
                }
                if( count($details) > 0 ){
                    foreach ($details as $k => $v) {
                        foreach ($v as $sk => $sv) {
                            $n_area_id[] = $sv->area_id;
                        }
                    }
                    $arr =array_unique($n_area_id);

                    $str = implode(',', $arr);
                    $htmldata['from_area'] = $this->jcustomselect('m_area_t','area_id','area_name','',"and area_id in ($str)");
                      
                }
            }
        }

        return $htmldata;
    }
  
    public function getchemistdcrData()   
    {
        $wh='';
        $search_table=[];
        $search_table[]="m_area_t";
        $search_table[]="app_chemist_t";
        // $search_table[]="m_tour_type_tbl";
        if($_GET['_search']=='true')
        {
            $wh=$this->jqgridsearch('app_chemist_dcr_t',$_GET['filters'],$search_table);
        }
        

        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;

        $result = \DB::select("SELECT count(app_chemist_dcr_t.chemist_dcr_id) as count FROM `app_chemist_dcr_t` left join m_area_t as from_area on(from_area.area_id = app_chemist_dcr_t.from_area) left join app_chemist_t on(app_chemist_t.chemist_id = app_chemist_dcr_t.chemist_name) where 1=1 $wh");

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

        $sql="SELECT tb_users.username,app_chemist_dcr_t.chemist_dcr_id,app_chemist_t.chemist_name,app_chemist_dcr_t.tp_date,from_area.area_name AS from_area FROM `app_chemist_dcr_t` left join m_area_t as from_area on(from_area.area_id = app_chemist_dcr_t.from_area) left join `tb_users` on (tb_users.id=app_chemist_dcr_t.created_by) left join app_chemist_t on(app_chemist_t.chemist_id = app_chemist_dcr_t.chemist_name) where 1=1 and app_chemist_dcr_t.created_by='".$emp."' $wh ORDER BY $sidx $sord LIMIT $start , $limit";
        
        $result = \DB::select($sql);
       // dd($result);
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }

}
