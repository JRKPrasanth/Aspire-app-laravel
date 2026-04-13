<?php

namespace App\Http\Controllers;

use App\Chemist;
use App\chemistdetail;
use Illuminate\Http\Request;

class ChemistController extends Controller
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
        $this->data['pageModule']='Chemist';
        $this->table="app_chemist_t";
        $this->subtable="app_chemist_detail_t";
        $this->model = new Chemist();
        $this->submodel = new Chemistdetail();
        $this->data['urlname']=\Request::route()->getName();
        $this->data['urlmenu']=$this->indexs(); 
    }

    public function index()
    {
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['urlname']=\Request::route()->getName();
        return view("chemist.table",$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null)
    {
        $this->data['pageMethod']="Chemist";
        $sql=\Session::get('id');
        $this->data['created_by']=$this->jCombologin('tb_users','id','first_name',$sql);

        if($id == '0')
        {   
            $this->data['row'] = (object) array();
            $this->data['linedata'] = array();
            $chemist =\DB::connection()->getSchemaBuilder()->getColumnListing("app_chemist_t");
            foreach($chemist as $key=>$value)
            {
                $this->data['row']->$value = '';
            }
            $this->data['product_id']=$this->jCombo('m_products_t','product_id','concatenated_product','');
            $this->data['country_id']=$this->jCombologin('m_countries_t','country_id','country_name','');
            $this->data['state_id']=$this->jCombologin('m_states_t','state_id','state_name','');
            $this->data['city_id']=$this->jCombologin('m_cities_t','city_id','city_name','');
            $this->data['territory_name']=$this->jCombologin('m_area_t','area_id','area_name','');
            $this->data['chemist_area']=$this->jCombologin('m_area_t','area_id','area_name','');
            $this->data['stockist_id']=$this->jCombo('app_stockist_t','stockist_id','stockist_name','');
            $this->data['doctor_id']=$this->jCombologin('app_doctors_t','doctor_id','doctor_name','');
            
        }
        else
        {
            
            $sql=\DB::table('app_chemist_t')->where('chemist_id',$id)->get();
            //dd($sql);
            if($sql->isNotEmpty()){
                $this->data['row']=$sql[0];
                
                $this->data['doctor_id']=$this->jCombologin('app_doctors_t','doctor_id','doctor_name',$sql[0]->doctor_id);
                $this->data['territory_name']=$this->jCombologin('m_area_t','area_id','area_name',$sql[0]->territory_name);

                if($sql[0]->doctor_id != "" && $sql[0]->doctor_id != "null"){
                    $doctor_id = implode(",", json_decode($sql[0]->doctor_id));

                    $this->data['doctor_id']=$this->jcustommultiselect('app_doctors_t','doctor_id','doctor_name',$doctor_id,'');
                }else{
                    $this->data['doctor_id']=$this->jcustommultiselect('app_doctors_t','doctor_id','doctor_name',$sql[0]->doctor_id,'');
                }

                if($sql[0]->product_id != "" && $sql[0]->product_id != "null"){
                    $product_id = implode(",", json_decode($sql[0]->product_id));

                    $this->data['product_id']=$this->jcustommultiselect('m_products_t','product_id','concatenated_product',$product_id,'');
                }else{
                    $this->data['product_id']=$this->jcustommultiselect('m_products_t','product_id','concatenated_product',$sql[0]->product_id,'');
                }
                if($sql[0]->stockist_id != "" && $sql[0]->stockist_id != "null"){
                    $stockist_id = implode(",", json_decode($sql[0]->stockist_id));

                    $this->data['stockist_id']=$this->jcustommultiselect('app_stockist_t','stockist_id','stockist_name',$stockist_id,'');
                }else{
                    $this->data['stockist_id']=$this->jcustommultiselect('app_stockist_t','stockist_id','stockist_name',$sql[0]->stockist_id,'');
                }
                
            }
            $linestable = \DB::table('app_chemist_detail_t')->where('chemist_id',$id)->get();
            $this->data['linedata'] = $linestable;
            $this->data['territory_name']=$this->jCombologin('m_area_t','area_id','area_name',$linestable[0]->area);
            $this->data['country_id']=$this->jCombologin('m_countries_t','country_id','country_name',$linestable[0]->country_id);
            $this->data['state_id']=$this->jCombologin('m_states_t','state_id','state_name',$linestable[0]->state_id);
            $this->data['city_id']=$this->jCombologin('m_cities_t','city_id','city_name',$linestable[0]->city_id);
            if($linestable->isNotEmpty()){
                $area =$sql[0]->territory_name;
                $doc_sql = \DB::table('app_doctors_adr_t')->where('area',$area)->select('doctor_id')->get();
                if(count($doc_sql) > 0 ){
                    foreach ($doc_sql as $key => $value) {
                        $doc_id[] = $value->doctor_id;
                        
                    }
                
                    foreach($this->data['linedata']  as $key=>$value)
                    {
                        $this->data['linedata'][$key]->doctor_id=$this->jcustomselecttool('app_doctors_t','doctor_id','doctor_name',$value->doctor_id," and doctor_id in(".implode(',',$doc_id).")");
                        
                        
                    }
                }else{
                    foreach ($linestable as $key => $value) {
                        $this->data['linedata'][$key]->doctor_id=$this->jcustomselecttool('app_doctors_t','doctor_id','doctor_name',''," ");
                    }                    
                }

            }
        }
        
        return view('chemist.form',$this->data);

    }

    public function chemistareadoctor($area=null){

        if($area){
            $sql = \DB::table('app_doctors_adr_t')
                    ->leftjoin('app_doctors_t','app_doctors_t.doctor_id','=','app_doctors_adr_t.doctor_id')
                    ->select('app_doctors_adr_t.doctor_id','app_doctors_adr_t.area','app_doctors_t.doctor_name','app_doctors_adr_t.doctor_address')
                    ->where('app_doctors_adr_t.area',$area)
                    ->get();

            
            $html='';

            $html.="<option value=''>-- Please Select --</option>";
            if(count($sql)>0){                           
                foreach ($sql as $key => $val) {
                    $html.= "<option value='".$val->doctor_id."'>".$val->doctor_name."</option>";
                }  
            }              
            return $html;
        }         
    
    }    

    public function chemistdoctordetail($id=null,$area=null){

        if($id){
            $sql = \DB::table('app_doctors_adr_t')
                    ->select('app_doctors_adr_t.doctor_id','app_doctors_adr_t.doctor_address')
                    ->where('app_doctors_adr_t.doctor_id',$id)
                    ->where('app_doctors_adr_t.area',$area)
                    ->get();

            
            $html=$sql[0]->doctor_address;

                        
            return $html;
        }         
    
    }

    public function save(Request $request)
    {
        $id='';
        $data = $this->validatePost($request->all(),$this->table,'header');
        $lines_data = $this->validatePost($request->all(),$this->subtable,'lines'); 
        //dd($data,$lines_data);
        \DB::beginTransaction();
        try{
           $data['stockist_id'] = json_encode($_POST['stockist_id']);
           $data['product_id'] = json_encode($_POST['product_id']);
           $data['doctor_id'] = json_encode($_POST['doctor_id']);

           $id=$this->model->insertRow($data);
           unset($lines_data['stockist_id']);
           unset($lines_data['doctor_id']);
           unset($lines_data['product_id']);
           $lines_data = $this->submodel->subgridSave($lines_data, $id);
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
     * @param  \App\Chemist  $chemist
     * @return \Illuminate\Http\Response
     */
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Chemist  $chemist
     * @return \Illuminate\Http\Response
     */
    public function edit(Chemist $chemist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chemist  $chemist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chemist $chemist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chemist  $chemist
     * @return \Illuminate\Http\Response
     */

    public function show(Chemist $chemist,$id=null)
    {
       $sql = \DB::table('app_chemist_t')->where('chemist_id',$id)->get();
       if($sql->isNotEmpty()){
            $this->data['chemist']=$sql[0];           
            
       }

        if($sql[0]->doctor_id != '' && $sql[0]->doctor_id !="null"){
            $sqpl =(json_decode($sql[0]->doctor_id));
            $doctor_id ='';
            if(count($sqpl )>0 ){
                foreach ($sqpl as $k => $v) {
                    $doctor_id.=$this->idname('doctor_name','app_doctors_t','doctor_id',$v).',';
                }
            }else{
                $doctor_id=$this->idname('doctor_name','app_doctors_t','doctor_id',$sqpl);
            }

            $this->data['chemist']->doctor_id=rtrim($doctor_id,',');
        }
        else{
            $this->data['chemist']->doctor_id='';
        }
        if($sql[0]->product_id != '' && $sql[0]->product_id !="null"){
            $sqpl =(json_decode($sql[0]->product_id));
            $product_id ='';

            if(count($sqpl)>0 ){
                foreach ($sqpl as $k => $v) {
                    $product_id.=$this->idname('concatenated_product','m_products_t','product_id',$v).',';
                }
            }else{
                $product_id=$this->idname('concatenated_product','m_products_t','product_id',$sqpl);
            }
            $this->data['chemist']->product_id=rtrim($product_id,',');
        }
        else{
            $this->data['chemist']->product_id='';
        }
        if($sql[0]->stockist_id != '' && $sql[0]->stockist_id !="null"){
            $sqpl =(json_decode($sql[0]->stockist_id));
            $stockist_id ='';
            if(count($sqpl ) >0 ){
                foreach ($sqpl as $k => $v) {
                    $stockist_id.=$this->idname('stockist_name','app_stockist_t','stockist_id',$v).',';
                }
            }else{
                $stockist_id=$this->idname('stockist_name','app_stockist_t','stockist_id',$sqpl);
            }
            $this->data['chemist']->stockist_id=rtrim($stockist_id,',');
        }
        else{
            $this->data['chemist']->stockist_id='';
        }

       $linedata = \DB::table('app_chemist_detail_t')
                ->leftjoin('m_countries_t','m_countries_t.country_id','=','app_chemist_detail_t.country_id')
                ->leftjoin('m_states_t','m_states_t.state_id','=','app_chemist_detail_t.state_id')
                ->leftjoin('m_cities_t','m_cities_t.city_id','=','app_chemist_detail_t.city_id')
                ->leftjoin('m_area_t','m_area_t.area_id','=','app_chemist_detail_t.area')
                ->select('*')
                ->where('app_chemist_detail_t.chemist_id',$id)->get();
      
       $this->data['linesdata']=$linedata;
       return view('chemist.view',$this->data);

    }

    public function destroy(Chemist $chemist,$id=null)
    {
        $count=0;
        if($count <= 0)
        {
            $query = \DB::table('app_chemist_t')->where('chemist_id',$id)->delete();
            
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

    public function getchemistData()   
    {
        $wh='';
        $search_table=[];
        $search_table[]="m_cities_t";
        
        // $search_table[]="m_tour_type_tbl";
        if($_GET['_search']=='true')
        {
            $wh=$this->jqgridsearch('app_chemist_t',$_GET['filters'],$search_table);
        }
        

        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;

        $result = \DB::select("SELECT count(chemist_id) as count FROM `app_chemist_t` where 1=1 $wh");

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
        // dd($emp);
        $sql="SELECT * FROM `app_chemist_t` left join `tb_users` on (tb_users.id=app_chemist_t.created_by)  where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
        
        $result = \DB::select($sql);
         //dd($result);
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }
}
