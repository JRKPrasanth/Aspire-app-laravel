<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Lookuphdr;
use App\Lookuplines;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Validator,DB;

class LookuphdrController extends Controller
{
    
    public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->model = new lookuphdr();

        $this->submodel=new Lookuplines();
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        $this->data['pageModule']='lookup';
        $this->table="a_lookup_hdr_t";
        $this->subtable="a_lookuplines_t";
        $this->middleware('auth');

    }


    public function index()
    {     
        $this->data['pageMethod'] = 'lookup';

        return view('lookups.table',$this->data);
        
    }

    			// data	
	public function lookuptblData(Request $request)
     {
         if ($request->ajax()) {
             $query = \DB::table('a_lookup_hdr_t')
                 ->join('tb_menus', 'a_lookup_hdr_t.lookup_menu', '=', 'tb_menus.menus_id')
                 ->select('a_lookup_hdr_t.*');
     
             return DataTables::of($query)->make(true);
         }
     }
	
	
    public function create($id=null)
    {
	$this->modelname = new lookuphdr();
		if($id==null){
	
		$this->data['row']= (object)array();
	
		$table = $this->modelname->getTableColumns();
		foreach($table as $key=>$val)
		{		
		$this->data['row']->$val='';
		}
		
	
		$this->data['id'] = '';
		$this->data['linedata'] = array();
           // dd($this->data['linedata']);
		$this->data['lookup_menu'] = $this->jcustomselecttool('tb_menus','menus_id','menus_name','',"and parent_id='0'");
            //dd($this->data['lookup_menu']);
		$this->data['lookup_submenu'] = $this->jcustomselecttool('tb_menus','menus_id','menus_name','',"and parent_id <> '0'");
}else{
	
			$this->data['id'] = $id;
			$table = \DB::table('a_lookup_hdr_t')->where('lookuphdr_id',$id)->get();
			$this->data['row'] = $table[0];
		
			$tablelines = \DB::table('a_lookuplines_t')->where('lookuphdr_id',$id)->get();
			$this->data['linedata'] = $tablelines;
					$this->data['lookup_menu'] = $this->jcustomselecttool('tb_menus','menus_id','menus_name',$table[0]->lookup_menu,"and parent_id='0'");
		$this->data['lookup_submenu'] = $this->jcustomselecttool('tb_menus','menus_id','menus_name',$table[0]->lookup_submenu,"and parent_id <> '0'");
	
}

		return view('lookups.form',$this->data);
 }
public function lookupdata()
    {
        
        $wh='';
		$search_table=array("tb_menus");
        if($_GET['_search']=='true')
        {
        $wh.=$this->jqgridsearch('a_lookup_hdr_t',$_GET['filters'],$search_table);
        }
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;
        $result = \DB::select("SELECT COUNT(lookuphdr_id) AS count FROM a_lookup_hdr_t  where 1=1 $wh");
if($limit==0)
  $limit=$count;
        $count = $result[0]->count;
        if( $count > 0 && $limit > 0)
        {
        $total_pages = ceil($count/$limit);
        } else {
        $total_pages = 0;
        }
        if ($page > $total_pages)
        $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

		$result = \DB::select("select a_lookup_hdr_t.*,tbl.menus_name as lookup_menu,tb2.menus_name as lookup_submenu from a_lookup_hdr_t left join tb_menus as tbl on (a_lookup_hdr_t.lookup_menu=tbl.menus_id) left join tb_menus as tb2 on (a_lookup_hdr_t.lookup_menu=tb2.menus_id)  where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit");
      $download_SQL = \DB::select("select a_lookup_hdr_t.*,tbl.menus_name as lookup_menu,tb2.menus_name as lookup_submenu from a_lookup_hdr_t left join tb_menus as tbl on (a_lookup_hdr_t.lookup_menu=tbl.menus_id) left join tb_menus as tb2 on (a_lookup_hdr_t.lookup_menu=tb2.menus_id)  where 1=1 $wh ORDER BY $sidx $sord");
      $result1 = \DB::select( $download_SQL );
      
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }

    
    public function save(Request $request)
        {
			$id='';
			$form = $request->all();
			$dataupload ="";
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
			$lines_data = $this->validatePost($form, $this->subtable, 'lines');
				
                \DB::beginTransaction();
                try
                {
                    //dd($data);
                     $id=$this->model->insertRow($data);
				//	dd($id);
                     $lid=$this->submodel->subgridSave($lines_data,$id);
                   
                    $look_name=DB::select("select * from a_lookup_hdr_t where lookuphdr_id='$id'");
                 
                    $a['lookup_type']=$look_name[0]->lookup_type;
                  \DB::table("a_lookuplines_t")->where('lookuphdr_id', $id)->update($a);
                     \DB::commit();
                     return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id,'lid' => $lid));
                }
                catch (\Illuminate\Database\QueryException $e)
                {
                     $message = explode('(', $e->getMessage());
                     $dbCode = rtrim($message[0], ']');
                     $dbCode = trim($dbCode, '[');
                    //dd($message);
                     \DB::rollback();
                     return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
                }

        }


/*Karthigaa purpose for Display hdr & Lines View function*/ 
  public function getShow(request $request,$id=null){
        if(isset($id)){
          $vdata=\DB::table('a_lookup_hdr_t')->where('lookuphdr_id',$id)->get();
           $this->data['vdata']=$vdata;
           
         
          //dd($this->data['description']);
          $vlinesdata=\DB::table('a_lookuplines_t')->where('lookuphdr_id',$id)->get();
          $this->data['vlinesdata']=$vlinesdata; 
         //dd($this->data['vlinesdata']);
     //   dd($this->data);
          return view('lookups.view',$this->data);
        }
    }
    
    
    
    
 public function edit(Lookuphdr $lookuphdr)
    {
       
    }

   
    public function update(Request $request, Lookuphdr $lookuphdr)
    {
       
    }

    
    public function destroy(Lookuphdr $lookuphdr)
    {
       
    }
}
