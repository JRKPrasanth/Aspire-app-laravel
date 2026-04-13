<?php

namespace App\Http\Controllers;

use App\Processspecification;
use App\Processspecificationline;
use Illuminate\Http\Request;

class ProcessspecificationController extends Controller
{
	public function __construct()
{
  $this->data=array();
  $this->model=new Processspecification;
  $this->submodel=new Processspecificationline;
  $this->data['pageMethod']=\Request::route()->getName();
  $this->data['pageFormtype']='ajax';
  $this->data['pageModule']='processspecification';
   $this->table="w_production_specification_t";
	$this->subtable="w_production_specification_lines_t";
	
}
    public function index()
    {
       
		return view('processspecification.table',$this->data);
    }

 
    public function create($id=null)
    {
		
    if($id==null){
     	$table = \DB::table('w_production_specification_t')->get();
    	$this->data['datas'] = $table;
    	$this->data['production_specification_id'] = '';
		$this->data['machine_id']=$this->jcombocomp("w_machine_t","machine_id","machine_name",'');
		$this->data['pageMethod']=\Request::route()->getName();
		 $this->data['product_id']=$this->jcombo("m_products_t","product_id","product_code",'');
		 $this->data['spec_criteria']=$this->jcustomselectactive("a_lookuplines_t","lookuplines_id","lookup_code",'',"and lookup_type='SPEC_CRITERIA'");
		$this->data['linedata'] = array();
	}
	   else{
		     $this->data['machineplan_hdr_id']=$id;
		   
		   $table_data=\DB::table('w_machineplan_hdr_t')->where('machineplan_hdr_id',$id)->get();
		   $m_id=$table_data[0]->machine_name;
		   $table=\DB::table('w_production_specification_t')->where('machine_id',$m_id)->get();
		   $this->data['production_specification_id']=$production_specification_id = $table[0]->production_specification_id;
		   $this->data['machine_id']=$this->jcombocomp("w_machine_t","machine_id","machine_name",$table[0]->machine_id);
		$this->data['test']=$this->jcustomselectactive("a_lookuplines_t","lookuplines_id","lookup_code",$table[0]->test,"and lookup_type='PRODUCTION_SPECIFICATION_TEST'");
  		 $this->data['product_id']=$this->jcombo("m_products_t","product_id","concatenated_product",$table[0]->product_id);
		 $lines=\DB::table('w_production_specification_lines_t')->where('production_specification_id',$production_specification_id)->get();
		 $this->data['linedata'] = $lines;
			   foreach ($this->data['linedata'] as $key => $value) {
                 $this->data['linedata'][$key]->spec_criteria = $this->data['spec_criteria'] = $this->jcustomselectactive("a_lookuplines_t","lookuplines_id","lookup_code",$value->spec_criteria,"and lookup_type='SPEC_CRITERIA'");
			    }
	   }
		
    return view('processspecification.form',$this->data);
   }
	public function ProcessmachinegridData()
	{  //dd($_GET['filters']);

		$wh='';
		 $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $org=\Session::get('organization');
		$groupname=\Session::get('groupname');
	    if($groupname=='Superadmin' || $groupname=='Admin'){
		$wh.='and w_production_specification_t.company_id='.$compy;	
		}else{
			$wh.='and w_production_specification_t.company_id='.$compy.' and w_production_specification_t.location_id='.$loc;		
		}
		
		if($_GET['_search']=='true')
		{
		$wh=$this->jqgridsearch('w_production_specification_t',$_GET['filters']); //dd($wh);
		}

		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];

		if(!$sidx) $sidx =1;
		$result = \DB::select("SELECT COUNT(production_specification_id) AS count FROM w_production_specification_t where 1=1 $wh");
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
		
		
        
    $SQL = "SELECT w_production_specification_t.*,p.product_code,m.machine_name from w_production_specification_t left join w_machine_t as m on m.machine_id=w_production_specification_t.machine_id  left join m_products_t as p on p.product_id=w_production_specification_t.product_id  where 1=1  $wh ORDER BY w_production_specification_t.production_specification_id $sord ";
   // dd($SQL);
        $result = \DB::select( $SQL );
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
		  
                $data = $this->validatePost($request->all(),$this->table,'header');		
                $lines_data = $this->validatePost($request->all(),$this->subtable,'lines');
	
                \DB::beginTransaction();
                try
                {
                     $id=$this->model->insertRow($data);
                     $lid=$this->submodel->subgridSave($lines_data,$id);
                     \DB::commit();
                
                     return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id));
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
     * @param  \App\Processspecification  $processspecification
     * @return \Illuminate\Http\Response
     */
    public function show(Processspecification $processspecification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Processspecification  $processspecification
     * @return \Illuminate\Http\Response
     */
    public function edit(Processspecification $processspecification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Processspecification  $processspecification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Processspecification $processspecification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Processspecification  $processspecification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Processspecification $processspecification)
    {
        //
    }
}
