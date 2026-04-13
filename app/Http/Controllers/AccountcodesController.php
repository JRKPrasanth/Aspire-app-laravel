<?php

namespace App\Http\Controllers;

use App\Accountcodes;
use App\Accountcodeslines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;
use Validator,DB;
use App\Http\Controllers\Controller;

class AccountcodesController extends Controller
{
  public function __construct(){
            $this->data=array();
             $this->table="f_account_codes_hdr_t";
		$this->subtable="f_account_codes_lines_t";
		$this->pageModule="accountcodes";
		$this->model=new Accountcodes;
		$this->submodel=new Accountcodeslines;
		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
		$this->data=array(
                    'pageModule'=> 'accountcodes',
                    'pageUrl'	=>  url('accountcodes')
                  );
                $this->data['urlmenu']=$this->indexs(); 
		
	}
    public function index()
    {
     	
		$table = \DB::table('f_account_codes_hdr_t')->get();
		$this->data['datas'] = json_encode($table);
		$this->data['opt']=$this->jqgridselect('f_account_class_t','account_class_id','account_class_name');
                 $this->data['pageMethod']='accountcodes';
		return view('accountcodes.table',$this->data);
    }
//       public function index()
//    {
//     	
//		$table = \DB::table('f_account_codes_hdr_t')->get();
//		$this->data['datas'] = json_encode($table);
//		
//		return view('accountcodes.index',$this->data);
//    }
     public function getAccountcodesData()
	{
		$wh='';
		if($_GET['_search']=='true')
		{
		$wh=$this->jqgridsearch('f_account_class_t',$_GET['filters']);
		}

		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
		$result = \DB::select("SELECT COUNT(account_class_id) AS count FROM f_account_class_t where 1=1 $wh");
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
    $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
		$SQL = "SELECT 
f_account_class_t.account_class_id,
f_account_class_t.account_class_name,
f_account_class_t.main_account_code,
f_account_class_t.description,
f_account_class_t.code_startwith,
f_account_codes_hdr_t.account_codes_hdr_id
FROM f_account_class_t 
LEFT JOIN f_account_codes_hdr_t on (f_account_codes_hdr_t.account_class_id=f_account_class_t.account_class_id)  $wh and f_account_codes_hdr_t.company_id=$compy and f_account_codes_hdr_t.location_id=$loc ORDER BY $sidx $sord LIMIT $start , $limit";
//                dd($SQL);
		$result = \DB::select( $SQL );
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);
	}
/*Karthigaa Purpose For Create mode*/
            public function create($id=null,$acc=null){ 
                $data=$this->model::find($id); 
     // dd($id);
	    $accountclass=\DB::table('f_account_class_t')->select('f_account_class_t.*')->where('f_account_class_t.account_class_id','=',$id)->get();
            $this->data['account_codes_hdr_id']=$acc;
        $accountclassid=$accountclass[0]->account_class_id;
         $this->data['main_account_code'] =$accountclass[0]->main_account_code;
//         dd($this->data['main_account_code']);
        $this->data['datas']=$data;
       // $this->data['edit']=0;
        $this->data['id']=$id;
        
        $linedata=\DB::table('f_account_codes_lines_t')->where('account_codes_hdr_id',$acc)->get();
//        dd($linedata);
        $this->data['linedata'] = array();
        $this->data['linedata']=$linedata;        
          
        $this->data['account_class_id'] = $this->jCombo('f_account_class_t','account_class_id','account_class_name',$accountclass[0]->account_class_id);
//  dd($this->data);
        return view('accountcodes.form',$this->data);
    }
        public function edit($id=null){
            $data=$this->model::find($id);
	    $accountclass=\DB::table('f_account_class_t')->select('f_account_class_t.*')->where('f_account_class_t.account_class_id','=',$id)->get();
            $accountclassid=$accountclass[0]->account_class_id;
            $this->data['main_account_code'] =$accountclass[0]->main_account_code;
            $this->data['datas']=$data;
    	   $data = \DB::table('f_account_codes_hdr_t')->where('account_codes_hdr_id',$id)->get();

           $linedata=\DB::table('f_account_codes_lines_t')->where('account_codes_hdr_id',$id)->get();
           
           $this->data['linedata'] = array();
           
           $this->data['account_class_id'] = $this->jCombo('f_account_class_t','account_class_id','account_class_name',$accountclass[0]->account_class_id);
		//dd($this->data);
		return view('accountcodes.form',$this->data);
    }

  /*Karthigaa purpose for Save function*/
	 public function save(Request $request){ 
         $id='';
         $data = $this->validatePost($request->all(),$this->table,'header');		
         $data['organization_id']=\Session::get('organization');
         $data['location_id']=\Session::get('location');
         $data['company_id']=\Session::get('companyid');

         $lines_data = $this->validatePost($request->all(),$this->subtable,'lines');	
          \DB::beginTransaction();
          try
          {
             $id=$this->model->insertRow($data);`
             
             $lid=$this->submodel->subgridSave($lines_data,$id);
            // dd($lid);
              \DB::commit();
              return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id,'lid' => $lid));
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
/*End*/
 /*Karthigaa purpose for Display hdr & Lines View function*/ 
  public function show(request $request,$id=null){
        if(isset($id)){
          $vdata=\DB::table('f_account_codes_hdr_t')->leftjoin('f_account_class_t','f_account_class_t.account_class_id','=','f_account_codes_hdr_t.account_class_id')
                                                  ->where('account_codes_hdr_id',$id)->get(); 

          if (!empty($vdata)) {
              $this->data['account_class_name']= $vdata[0]->account_class_name;
              $this->data['main_account_code']= $vdata[0]->main_account_code;
              $this->data['active']=$vdata[0]->active;  
          }else{
              $this->data['account_class_name']="";
               $this->data['main_account_code']="";
              $this->data['active']="";  
          }
          
          $vlinesdata=\DB::table('f_account_codes_lines_t')->where('account_codes_hdr_id',$id)->get();
            $this->data['vlinesdata'] = array();
          $this->data['vlinesdata']=$vlinesdata; 
          return view('accountcodes.view',$this->data);
        }
    }

 
}
