<?php

namespace App\Http\Controllers;

use App\Stockist;
use App\Stockistlines;
use Illuminate\Http\Request;

class StockistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->data=array();
        $this->model    = new Stockist();
        $this->submodel    = new Stockistlines();
        //dd($this->submodel);
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        $this->data['pageModule']='Stockist';
        $this->table="app_stockist_t";
        $this->subtable="app_stockist_lines_t";
        $this->data['urlname']=\Request::route()->getName();
    }

    public function index()
    {
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['urlname']=\Request::route()->getName();
        return view("stockist.table",$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null)
    {
        $this->data['pageMethod']="stockist";
        $sql=\Session::get('id');
        $this->data['created_by']=$this->jCombologin('tb_users','id','first_name',$sql);
        if($id == '0')
        {
            $this->data['row'] = (object) array();
            $this->data['row']->active="";
            $this->data['row']->stockist_id="";
            $this->data['row']->stockist_name="";
            $this->data['row']->stockist_address="";
            $this->data['row']->contact_person="";
            $this->data['row']->stockist_phone="";
            $this->data['row']->stockist_mobile="";
            $this->data['row']->stockist_category="";
            $this->data['linedata'] = array();
            $this->data['super_stockist_id']=$this->jCombo('m_customers_t','customer_id','customer_name','');
            $this->data['teritory_name']=$this->jCombologin('m_area_t','area_id','area_name','');
            $this->data['country_id']=$this->jCombologin('m_countries_t','country_id','country_name','');
            $this->data['state_id']=$this->jCombologin('m_states_t','state_id','state_name','');
            $this->data['city_id']=$this->jCombologin('m_cities_t','city_id','city_name','');
            $this->data['chemist_id']=$this->jCombologin('app_chemist_t','chemist_id','chemist_name','');
            $this->data['area']=$this->jCombologin('m_area_t','area_id','area_name','');

        }
        else
        {
            $sql=\DB::table('app_stockist_t')->where('stockist_id',$id)->get();
          
            if($sql->isNotEmpty())
            {
                $this->data['row']=$sql[0];
                $this->data['teritory_name']=$this->jCombologin('m_area_t','area_id','area_name',$sql[0]->teritory_name);
                $this->data['country_id']=$this->jCombologin('m_countries_t','country_id','country_name',$sql[0]->country_id);
                $this->data['state_id']=$this->jCombologin('m_states_t','state_id','state_name',$sql[0]->state_id);
                $this->data['city_id']=$this->jCombologin('m_cities_t','city_id','city_name',$sql[0]->city_id);
                
                if($sql[0]->chemist_id != "")
                {
                    $chemist_id = implode(",", json_decode($sql[0]->chemist_id));

                    $this->data['chemist_id']=$this->jcustommultiselect('app_chemist_t','chemist_id','chemist_name',$chemist_id,'');
                }
                else
                {
                    $this->data['chemist_id']=$this->jCombologin('app_chemist_t','chemist_id','chemist_name',$sql[0]->chemist_id);
                }
                $this->data['super_stockist_id']=$this->jCombo('m_customers_t','customer_id','customer_name',$sql[0]->super_stockist_id);
            }
            $linestable = \DB::table('app_stockist_lines_t')->where('doctor_id',$id)->get();

            $this->data['linedata'] = $linestable;
            foreach ($linestable as $key => $value) {
                $value->territory_name=$this->jCombologin('m_area_t','area_id','area_name',$value->area);
                $value->country_id=$this->jCombologin('m_countries_t','country_id','country_name',$value->country_id);
                $value->state_id=$this->jCombologin('m_states_t','state_id','state_name',$value->state_id);
                $value->city_id=$this->jCombologin('m_cities_t','city_id','city_name',$value->city_id);    
                $value->area=$this->jCombologin('m_area_t','area_id','area_name',$value->area);    
            }
            
            
        }

        return view('stockist.form',$this->data);
    }

    public function save(Request $request){

        $id='';
        
        $data = $this->validatePost($request->all(),$this->table,'header');
        $lines_data = $this->validatePost($request->all(),$this->subtable,'lines');
        //dd($request->all(),$this->subtable,'lines');
        \DB::beginTransaction();
        try{
            
            $data['chemist_id']=json_encode($_POST['chemist_id']);
            $id=$this->model->insertRow($data);
            unset($lines_data['doctors_adr_id']);
            unset($lines_data['concat_address']);
            unset($lines_data['chemist_id']);
            
            $lines_data = $this->submodel->subgridSave($lines_data, $id);
            //dd($lines_data);
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


    public function stockistareadoctor($area=null){

        if($area){
            $sql = \DB::table('app_chemist_t')
                    ->select('chemist_id','territory_name','chemist_name')
                    ->where('territory_name',$area)
                    ->get();

            
            $html='';

            $html.="<option value=''>-- Please Select --</option>";
            if(count($sql)>0){                           
                foreach ($sql as $key => $val) {
                    $html.= "<option value='".$val->chemist_id."'>".$val->chemist_name."</option>";
                }  
            }         

            return $html;
        }         
    
    } 

    public function show(Stockist $stockist,$id=null)
    {
        $sql = \DB::table('app_stockist_t')->where('stockist_id',$id)->get();
        $vlinesdata = \DB::table('app_stockist_lines_t')
                ->leftjoin('m_countries_t','m_countries_t.country_id','=','app_stockist_lines_t.country_id')
                ->leftjoin('m_states_t','m_states_t.state_id','=','app_stockist_lines_t.state_id')
                ->leftjoin('m_cities_t','m_cities_t.city_id','=','app_stockist_lines_t.city_id')
                ->leftjoin('m_area_t','m_area_t.area_id','=','app_stockist_lines_t.area')
                ->where('app_stockist_lines_t.stockist_id',$id)->select('app_stockist_lines_t.*','m_countries_t.country_name','m_states_t.state_name','m_cities_t.city_name','m_area_t.area_name')->get();

        if($sql->isNotEmpty()){
            $this->data['stockist']=$sql[0];
            $this->data['country_id'] =$this->idname('country_name','m_countries_t','country_id',$sql[0]->country_id);
            $this->data['state_id'] =$this->idname('state_name','m_states_t','state_id',$sql[0]->state_id);
            $this->data['city_id'] =$this->idname('city_name','m_cities_t','city_id',$sql[0]->city_id);
            $this->data['teritory_name'] =$this->idname('area_name','m_area_t','area_id',$sql[0]->teritory_name);
            $this->data['super_stockist_id'] =$this->idname('customer_name','m_customers_t','customer_id',$sql[0]->super_stockist_id);
            
            if($sql[0]->chemist_id){
                $sqpl =(json_decode($sql[0]->chemist_id));
                $chemist_id='';
                foreach ($sqpl as $key => $value) {
                    $chemist_id.=$this->idname('chemist_name','app_chemist_t','chemist_id',$value).',';
                }

                $this->data['chemist_id']=rtrim($chemist_id,',');
            }else{
                $this->data['chemist_id']='';
            }
       }
       
       $this->data['vlinesdata'] = $vlinesdata;

       return view('stockist.view',$this->data);
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
     * @param  \App\Stockist  $stockist
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Stockist  $stockist
     * @return \Illuminate\Http\Response
     */
    public function edit(Stockist $stockist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Stockist  $stockist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stockist $stockist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stockist  $stockist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stockist $stockist,$id=null)
    {
        $count=0;
        if($count <= 0)
        {
            $query = \DB::table('app_stockist_t')->where('stockist_id',$id)->delete();
            
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

    public function getstockistData()   
    {
        $wh='';
        $search_table=[];
        // $search_table[]="m_cities_t";
        // $search_table[]="app_stockist_t";
        if($_GET['_search']=='true')
        {
            $wh=$this->jqgridsearch('app_stockist_t',$_GET['filters'],$search_table);
        }
        

        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;

        $result = \DB::select("SELECT count(stockist_id) as count FROM `app_stockist_t`
          LEFT JOIN app_chemist_detail_t on (app_chemist_detail_t.chemist_id=app_stockist_t.chemist_id) where 1=1 $wh");
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

        $sql="SELECT * FROM `app_stockist_t` left join `tb_users` on (tb_users.id=app_stockist_t.created_by) 
        LEFT JOIN app_chemist_detail_t on (app_chemist_detail_t.chemist_id=app_stockist_t.chemist_id) where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
        //dd($sql);
        $result = \DB::select($sql);
        // dd($sql);
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }
}
