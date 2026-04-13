<?php

namespace App\Http\Controllers;

use App\Rptdisplayelementshdr;
use App\Http\Controllers\RptdisplayelementsController;
use App\rptdisplayelementslines;
use Illuminate\Http\Request;
use Session;

class RptdisplayelementshdrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
	{
		$this->data['pageFormtype']='ajax';
         $this->data = array();
        $this->table = "a_rpt_displayelements_hdr_t";
        $this->subtable = "a_rpt_displayelements_lines_t";
        
        $this->pageModule = "reportelements";
         $this->model = new rptdisplayelementshdr;
         $this->submodel = new rptdisplayelementslines;
        
          $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        
        $this->data = array(
            'pageModule' => 'reportelements',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
           $this->modelname = new Rptdisplayelementshdr();
        $this->data['urlmenu']=$this->indexs();
    $this->data['pageMethod']=\Request::route()->getName();

	}
    public function index()
    {    $this->data['curlname']=\Request::route()->getName();
        $this->data['pageMethod']=\Request::route()->getName();
       $this->data['opt'] = $this->jqgridcustselect('a_lookuplines_t', 'lookuplines_id', 'lookup_code'," and lookup_type='REPORT_SOURCE'");
      $table = \DB::table('a_rpt_displayelements_hdr_t')->get();
        $this->data['datas'] = json_encode($table);
     $this->data['pageModule'];
     $this->data['start_date']=date('Y-m-d');
	
       return view('rptdisplayelementshdr.table',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null)
    { 
       $rptdsiphdr_data = \DB::table('a_rpt_displayelements_hdr_t')->where('rpt_displayelements_hdr_id', $id)->get();
      
        	$location=Session::get('location'); 
            $org=Session::get('organization'); 
            $comp=Session::get('companyid');
        
       $rpt_lines_data = \DB::table('a_rpt_displayelements_lines_t')->where('rpt_displayelements_hdr_id', $id)->get();
       // dd($id);
        if($id=="0")
        { //dd("saf");
            $this->data['row'] = (object) array();
            $this->data['row']->rpt_displayelements_hdr_id = "";
            $this->data['row']->report_source = $this->jcustomselect('a_lookuplines_t', 'lookuplines_id', 'lookup_code',''," and lookup_type='REPORT_SOURCE'");
            //dd($this->data['row']->report_source);
            $this->data['row']->set_name = "";
            
            $this->data['row']->company_id =$comp;
            $this->data['row']->location_id = $location;
            $this->data['row']->organization_id =$org;
            
            $this->data['row']->description = "";
            $this->data['row']->start_date = date('Y-m-d');
            $this->data['row']->end_date = '';
          
             foreach ($rpt_lines_data as $key => $value) 
             { 
               $this->data['linedatas'][$key] = (object) array();
               $this->data['linedatas'][$key]->rpt_displayelements_line_id = '';
               $this->data['linedatas'][$key]->rpt_displayelements_hdr_id = '';
               $this->data['linedatas'][$key]->line_no = '';
               $this->data['linedatas'][$key]->element_name = '';
               $this->data['linedatas'][$key]->element_content = '';
             }
             $this->data['linedatas']=array();
                 
        }
        else
        { 
            $this->data['id'] = $id; 
            $this->data['row']=$rptdsiphdr_data[0]; //dd($this->data['row']);
       
           $this->data['row']->report_source = $this->jcustomselect('a_lookuplines_t', 'lookuplines_id', 'lookup_code',$rptdsiphdr_data[0]->report_source," and lookup_type='REPORT_SOURCE'");
            
             foreach ($rpt_lines_data as $key => $value) 
             {
               $this->data['linedatas'][$key] = (object) array();
               $this->data['linedatas'][$key]->rpt_displayelements_line_id = $value->rpt_displayelements_line_id;
               $this->data['linedatas'][$key]->rpt_displayelements_hdr_id = $value->rpt_displayelements_line_id;
               $this->data['linedatas'][$key]->line_no = $value->line_no;
               $this->data['linedatas'][$key]->element_name = $value->element_name;
               $this->data['linedatas'][$key]->element_content = $value->element_content;
             }
             
        }
        
        $this->data['return_url'] = \Request::route()->getName();
        $this->data['pageModule']="reportelements";
       return view('rptdisplayelementshdr.form',$this->data);
    }
    
    public function save(Request $request)
    { 
        // dd($_POST['start_date']);
         $id = ''; 
         
        // dd($data['start_date']);
         
        $data = $this->validatePost($request->all(), $this->table, 'header');
        $var = $_POST['start_date'];
         $start_date = str_replace('/', '-', $var);
          $data['start_date'] = date("Y-m-d", strtotime($start_date));
          $var1 = $_POST['end_date'];
         $end_date = str_replace('/', '-', $var1);
        $data['end_date'] = date("Y-m-d", strtotime($end_date));
       

       $lines_data = $this->validatePost($request->all(), $this->subtable, 'lines');
            
        
          \DB::beginTransaction();
         try {
            $id = $this->model->insertRow($data); 
            $lid = $this->submodel->subgridSave($lines_data, $id); 
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Saved successfully', 'id' => $id, 'lid' => $lid));
           } catch (\Illuminate\Database\QueryException$e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
			 //dd($dbCode);
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
      
    }
    
    public function view(request $request, $id = null)
    {  $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        
        if(isset($id))
        {
          $this->data['rptdispdata']=\DB::select("SELECT  
            a_rpt_displayelements_hdr_t.rpt_displayelements_hdr_id,
            a_lookuplines_t.lookup_code, 
            a_rpt_displayelements_hdr_t.set_name,
            a_rpt_displayelements_hdr_t.start_date,
            a_rpt_displayelements_hdr_t.end_date,
            a_rpt_displayelements_hdr_t.description
            FROM `a_rpt_displayelements_hdr_t`
            left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=a_rpt_displayelements_hdr_t.report_source where 1=1 and a_rpt_displayelements_hdr_t.company_id=$compy and a_rpt_displayelements_hdr_t.location_id=$loc");
            //dd($rptdispdata);
            
            $this->data['rptdisplinedate']=\DB::select("SELECT a_rpt_displayelements_lines_t.`element_name`, a_rpt_displayelements_lines_t.element_content FROM `a_rpt_displayelements_lines_t` WHERE a_rpt_displayelements_lines_t.rpt_displayelements_hdr_id=$id");
            
            
             
        }
      return view('rptdisplayelementshdr.view',$this->data);
    }
    
	
	
	
	
    
     public function getrptdispdata() {
        $wh = ''; 
		
        if ($_GET['_search'] == 'true') {
			 $table=array();
		 $table[]="a_lookuplines_t";
            $wh = $this->jqgridsearch('a_rpt_displayelements_hdr_t', $_GET['filters'],$table);
        }
          
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if (!$sidx)
            $sidx = 1;
		 $comp=\Session::get('companyid');
        $result = \DB::select("SELECT COUNT(a_rpt_displayelements_hdr_t.rpt_displayelements_hdr_id) AS count FROM a_rpt_displayelements_hdr_t left join a_lookuplines_t on(a_lookuplines_t.lookuplines_id=a_rpt_displayelements_hdr_t.report_source) where 1=1 and a_rpt_displayelements_hdr_t.company_id=$comp $wh");
        $count = $result[0]->count;
        if ($count > 0 && $limit > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages)
            $page = $total_pages;
        $start = $limit * $page - $limit;
        if ($start < 0)
            $start = 0;


        
        $SQL = "SELECT  
            a_rpt_displayelements_hdr_t.rpt_displayelements_hdr_id,
            a_lookuplines_t.lookup_code,
            a_rpt_displayelements_hdr_t.set_name,
            a_rpt_displayelements_hdr_t.start_date,
            a_rpt_displayelements_hdr_t.end_date,
            a_rpt_displayelements_hdr_t.description
            FROM `a_rpt_displayelements_hdr_t`
            left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=a_rpt_displayelements_hdr_t.report_source where 1=1 $wh and a_rpt_displayelements_hdr_t.company_id=$comp ORDER BY $sidx $sord LIMIT $start , $limit";
             $download_SQL = "SELECT  
            a_rpt_displayelements_hdr_t.rpt_displayelements_hdr_id,
            a_lookuplines_t.lookup_code,
            a_rpt_displayelements_hdr_t.set_name,
            a_rpt_displayelements_hdr_t.start_date,
            a_rpt_displayelements_hdr_t.end_date,
            a_rpt_displayelements_hdr_t.description
            FROM `a_rpt_displayelements_hdr_t`
            left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=a_rpt_displayelements_hdr_t.report_source where 1=1 $wh and a_rpt_displayelements_hdr_t.company_id=$comp ORDER BY $sidx $sord";
             $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }

        $result = \DB::select($SQL);
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        echo json_encode($responce);
    }

    
}
