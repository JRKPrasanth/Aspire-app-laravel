<?php

namespace App\Http\Controllers;
use App\toolsmenuconfighdr;
use App\toolsmenuconfiglines;
use Illuminate\Http\Request;
use DB;

class toolsmenuconfighdrController extends Controller
{
	
	public function __construct()
	{
		$this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
		$this->model=new toolsmenuconfighdr();
		$this->model=new toolsmenuconfighdr;
			$this->submodel=new toolsmenuconfiglines;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
                $this->table="m_toolmenus_listing_hdr_t";
		$this->subtable="m_toolmenus_listing_lines_t";
	}
	
	
	
    public function index()
	{
	$this->data['pageMethod']=\Request::route()->getName();
	return view('toolsmenuconfig.table',$this->data);
	}

	public function create($id=null){
	
		
		$this->data =array('pageModule'=>'toolsmenuconfighdr','pageUrl'=>url('toolsmenuconfighdr'));

		if($id == '0' )
		{
			$this->data['row']= (object) array();
			$this->data['row']->pagemodule = "";
			
			
			$this->data['row']->m_toolmenus_listing_hdr_id = "";
			

			$this->data['row']->description = "";
		
			$this->data['linedata'] = array();
$this->data['pagemodule'] = $this->jcustomselecttool('tb_menus', 'controller_name', 'controller_name', '',' and controller_name<>"" ');
			
		}else {

			$table = \DB::table('m_toolmenus_listing_hdr_t')->where('m_toolmenus_listing_hdr_id',$id)->get();
			$this->data['row']= $table[0];

			$this->data['row']->m_toolmenus_listing_hdr_id = $id;
			$table = \DB::table('m_toolmenus_listing_lines_t')->where('m_toolmenus_listing_hdr_id',$id)->get();

			$this->data['maindate']="";
			//$this->data['maindate']=$maindate;

			$this->data['linedata'] = $table;
      $table = \DB::table('m_toolmenus_listing_hdr_t')->where('m_toolmenus_listing_hdr_id',$id)->get();

		}
	return view('toolsmenuconfig.form',$this->data);
	}
	
	
	 public function edit(Request $request, $id){

		$this->data =array('pageModule'=>'toolsmenuconfighdr','pageUrl'=>url('toolsmenuconfighdr'));
				
		$this->data['id'] = $id;
		
		$table = \DB::table('m_toolmenus_listing_hdr_t')->where('m_toolmenus_listing_hdr_id',$id)->get();
		$this->data['row'] = $table[0];
   
$this->data['pagemodule'] = $this->jcustomselecttool('tb_menus', 'controller_name', 'controller_name', $table[0]->pagemodule,' and controller_name<>"" ');
		$tablelines = \DB::table('m_toolmenus_listing_lines_t')->where('m_toolmenus_listing_hdr_id',$id)->get();
		$this->data['linedata'] = $tablelines;
	
		return view('toolsmenuconfig.form',$this->data);
	}


	public function toolsmenucheck()
    {
              // $edit_id = $_GET['edit_id'];
        $pagemodule = $_GET['employee_id'];
        
        
        $chkdata=DB::table('m_toolmenus_listing_hdr_t')->where('pagemodule','=',$pagemodule)->get();
		
        if(count($chkdata)>0)
            return 1;
        else
            return 0;
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
					//dd($dbCode);
                     return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
                }

        }

	public function show(toolsmenuconfighdr $toolsmenuconfighdr,$id=null){
	$this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("m_toolmenus_listing_hdr_t");
	$this->data['values'] = toolsmenuconfighdr::find($id);
  $curr=$this->data['values'];

  
  $linetable = \DB::table('m_toolmenus_listing_lines_t')->where('m_toolmenus_listing_hdr_id',$id)->get();
  $id=$linetable[0]->m_toolmenus_listing_hdr_id;

  $this->data['linesvalue'] = $linetable;
		
	return view('toolsmenuconfig.view',$this->data);
	}
	
	public function delete(Request $request,$id=null){
        	toolsmenuconfighdr::destroy($id);
                \DB::table('m_toolmenus_listing_lines_t')->where('m_toolmenus_listing_hdr_id', $id)->delete();

                return 0;


	//	$count=0;
	//	$queryquote = \DB::table('s_salesorder_hdr_t')->where('discount_id',$id)->count();
	//	if($queryquote >=1)
	//	{
	//	 $count++;
	//	}
	//	if($count <= 0)
	//		{
	///		Ardiscountshdr::destroy($id);
	//		$query = \DB::table('m_discounts_lines_t')->where('ar_discount_hdr_id',$id)->delete();
		//	if($query)
		//	{
		//		return 0;
		//	}
		//	else
		//	{
		//		return 1;
		//	}
	//	}
	//	else
	//	{
	//		return 2;
	//	}





	}

	
	
	
	
	
	public function getToolData()
	{
		$wh='';
		if($_GET['_search']=='true')
		{
		$wh=$this->jqgridsearch('m_toolmenus_listing_hdr_t',$_GET['filters']);
		}

		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
		$result = \DB::select("SELECT COUNT(m_toolmenus_listing_hdr_id) AS count FROM m_toolmenus_listing_hdr_t where 1=1 $wh");
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
		$SQL = "SELECT * FROM m_toolmenus_listing_hdr_t where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
		$result = \DB::select( $SQL );
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);
	}

	
	
	
	
	
}
