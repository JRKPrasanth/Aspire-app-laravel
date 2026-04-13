<?php
namespace App\Http\Controllers;
use App\salesenquiry;
use App\soinquirylines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;

class SalesenquiryController extends Controller
{
	public $module="salesenquiry";
	public function __contruct()
	{
		$this->data =array('pageModule'=>'salesenquiry','pageUrl'=>url('soinquiryhdr'));
		$this->data['pageMethod']=\Request::route()->getName();
	}
        
        public function index(Request $request)
	{

	// restrict illegal menu entry purpose - RATHI R

        $url = $request->path();
        
        $Controller = new Controller();
        $access = $Controller->Accessdined();
        
        $userAccess = json_decode($access, true);
        
        // Flatten the $userAccess array
        $flatUserAccess = [];
        foreach ($userAccess as $accessItem) {
            if (is_array($accessItem)) {
                $flatUserAccess = array_merge($flatUserAccess, $accessItem);
            } else {
                $flatUserAccess[] = $accessItem;
            }
        }
        
        if (!in_array($url, $flatUserAccess)) {
            return view('accessresticted.view');
               
        }

        // END

		$table = \DB::table('s_inquiry_hdr_t')->get();
		$this->data['datas'] = json_encode($table);
		return view('salesenquiry.table',$this->data);
	}
        
	public function create($id=null)
	{ //dd("dsfdf");
		$this->data =array('pageModule'=>'soinquiryhdr','pageUrl'=>url('soinquiryhdr'));
		$this->data['row']= (object) array();
		$this->data['row']->inquiry_no = "";
		$this->data['row']->inquiry_category = "";
		$this->data['row']->so_inquiry_hdr_id = "";
		$this->data['row']->inquiry_type = "";
		$this->data['row']->inquiry_date = "";
		$this->data['row']->remarks = "";
		$this->data['id'] = '';
		$this->data['linedata'] = array();
		$this->data['customer_id'] = $this->jCombo('m_customers_t','customer_id','customer_number|customer_name','');
		//dd($this->data['customer_id']);
		
		$this->data['organization_id'] = $this->jCombo('m_organizations_t','organization_id','organization_name','');		
		$this->data['project_id'] = $this->jCombo('m_projects_t','project_id','project_name','');
		$this->data['salespersons_id'] = $this->jCombo('s_salesperson_t','salesperson_id','salesperson_name','');
		$this->data['product_id'] = $this->jCombo('products_final_t','product_id','concatenated_product','');
		$this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
		
		return view('salesenquiry.form',$this->data);
	}
        
	public function tabledata()
	{

		$table = \DB::table('users')->get();
		$this->data['datas'] = $table;
		return $table;
	}
	
	public function edit(Request $request, $id)
	{
		$this->data =array('pageModule'=>'soinquiryhdr','pageUrl'=>url('soinquiryhdr'));
		$this->data['id'] = $id;
		$table = \DB::table('s_inquiry_hdr_t')->where('so_inquiry_hdr_id',$id)->get();
		$this->data['row'] = $table[0];
		$tablelines = \DB::table('s_inquiry_lines_t')->where('so_inquiry_hdr_id',$id)->get();
		$this->data['linedata'] = $tablelines;
		$this->data['organization_id'] = $this->jCombo('m_organizations_t','organization_id','organization_name',$table[0]->organization_id);
		$this->data['customer_id'] = $this->jCombo('m_customers_t','customer_id','customer_name',$table[0]->customer_id);
		$this->data['project_id'] = $this->jCombo('m_projects_t','project_id','project_name',$table[0]->project_id);
		$this->data['product_id'] = $this->jCombo('products_final_t','product_id','concatenated_product','');
		$this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
		foreach ($this->data['linedata'] as $key => $value) 
		{
		$this->data['linedata'][$key]->product_id = $this->data['product_id'] = $this->jCombo('products_final_t','product_id','concatenated_product',$value->product_id);	
		$this->data['linedata'][$key]->uomcode_id =  $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$value->uom_code_id);	
		}
		return view('salesenquiry.form',$this->data);
	}
        public function save(Request $request)
        {
	 
	 $soinq  = new salesenquiry();	 
	 $this->modelname = new Salesenquiry();
	 $soinql = new soinquirylines();
	 $this->modelline = new Soinquirylines();
	 
	 $primary	  = self::findPrimarykey('s_inquiry_hdr_t');
	 $primaryline = self::findPrimarykey('s_inquiry_lines_t');
	
	$soinq->inquiry_no		 = $_POST['inquiry_no'];
	$soinq->inquiry_type     = $_POST['inquiry_type'];
	$soinq->inquiry_date	 = $_POST['inquiry_date'];
	$soinq->customer_id	     = $_POST['customer_id']?$_POST['customer_id']:0;
	$soinq->project_id		 = $_POST['project_id'];
	$soinq->organization_id  = $_POST['organization_id'];
	$soinq->remarks		     = $_POST['remarks'];
	
	 $id=$this->insertData($this->modelname,$primary,$soinq,$_POST['so_inquiry_hdr_id']);	 
	 /**************************** LineItems Save *****************************/
	// $data = request()->except(['_token']);
	 
	 //$this->insertLines($this->modelline,$primaryline,$data,$_POST['so_inquiry_hdr_id']);
	 $oldid=\DB::table('s_inquiry_lines_t')->where('so_inquiry_hdr_id',$id)->get();
	 if($oldid->isEmpty())		 
	 {
		  for($i=0;$i<count($_POST['counter']);$i++)
	 		{
			$data['so_inquiry_hdr_id']=$id; 
			$data['so_inquiry_lines_id']=$_POST['bulk_so_inquiry_lines_id'][$i]?$_POST['bulk_so_inquiry_lines_id'][$i]:0; 
			$data['line_no']=1; 
			$data['product_id']=$_POST['bulk_product_id'][$i]; 
			$data['uom_code_id']=$_POST['bulk_uom_code_id'][$i]; 
			$data['required_qty']=$_POST['bulk_required_qty'][$i]; 
			$data['need_by_date']=$_POST['bulk_need_by_date'][$i]; 
			$data['created_at']=date('Y-m-d H:i:s'); 
			$data['updated_at']=date('Y-m-d H:i:s');									
			\DB::table('s_inquiry_lines_t')->insert($data); 
			}
	 }
	 else
	 {
	 $existingId = array();
		foreach ($oldid as $key=> $value) 
		{
                    $oldIds[]= $value->$primaryline;
		}	
		 
		foreach ($_POST['bulk_'.$primaryline] as $val) 
		{
                    $newIds[]= $val;
		}	
                $existingId=  array_replace($newIds, $oldIds);
                $oldcount=count($oldIds);
                $newcount=count($newIds);
                
                if($oldcount <= $newcount)
                {
                    for($i=0;$i<$newcount;$i++)
                    {
                            $data['so_inquiry_hdr_id']   = $id; 
                            $data['so_inquiry_lines_id'] = $_POST['bulk_so_inquiry_lines_id'][$i] ? $_POST['bulk_so_inquiry_lines_id'][$i] : 0; 
                            $data['line_no']        = 1; 
                            $data['product_id']     = $_POST['bulk_product_id'][$i]; 
                            $data['uom_code_id']    = $_POST['bulk_uom_code_id'][$i]; 
                            $data['required_qty']   = $_POST['bulk_required_qty'][$i]; 
                            $data['need_by_date']   = $_POST['bulk_need_by_date'][$i]; 
                            $data['created_at']     = date('Y-m-d H:i:s'); 
                            $data['updated_at']     = date('Y-m-d H:i:s');
                            if($data['so_inquiry_lines_id']=$existingId[$i])
                            {
                                $this->modelline::find($data['so_inquiry_lines_id'])->update($data);
                            }
                            else
                            {					
                                \DB::table('s_inquiry_lines_t')->insert($data); 
                            }
                    }
                }
                else
                {
                    $arraydiff = array_diff($oldIds, $newIds);
                    foreach ($arraydiff as $key) 
                    {
                        if (($key = array_search($key, $oldIds)) !== false) 
                        {
                            unset($oldIds[$key]);
                        }
                    }
                    foreach($arraydiff as $val)
                    {			  
                        \DB::table('s_inquiry_lines_t')->where('so_inquiry_lines_id',$val)->delete();
                    }
                    for($i=0;$i<count($oldIds);$i++)
                    {	
                        $data['so_inquiry_hdr_id']   =   $id; 
                        $data['so_inquiry_lines_id'] =   $_POST['bulk_so_inquiry_lines_id'][$i]?$_POST['bulk_so_inquiry_lines_id'][$i]:0; 
                        $data['line_no']             =   1; 
                        $data['product_id']          =   $_POST['bulk_product_id'][$i]; 
                        $data['uom_code_id']         =   $_POST['bulk_uom_code_id'][$i]; 
                        $data['required_qty']        =   $_POST['bulk_required_qty'][$i]; 
                        $data['need_by_date']        =   $_POST['bulk_need_by_date'][$i]; 
                        $data['created_at']          =   date('Y-m-d H:i:s'); 
                        $data['updated_at']          =   date('Y-m-d H:i:s');
                        $this->modelline::find($data['so_inquiry_lines_id'])->update($data);
                    } 
                }	
        }
	 /**************************** LineItems Save End *****************************/
            $table = \DB::table('s_inquiry_hdr_t')->get();
            $this->data['datas'] = json_encode($table);

            return view('salesenquiry.table',$this->data);
        }
	
	function insertLines($model_name,$primaryline,$data,$id)
	{
		
		$input = Input::all();
		//dd($input);
                $oldid=\DB::table('s_inquiry_lines_t')->where('so_inquiry_hdr_id',$id)->get();
		
            if($oldid->isEmpty())		 
            {
                     for($i=0;$i<count($_POST['counter']);$i++)
                           {
                           $data['so_inquiry_hdr_id']=$id; 
                           $data['so_inquiry_lines_id']=$_POST['bulk_so_inquiry_lines_id'][$i]?$_POST['bulk_so_inquiry_lines_id'][$i]:0; 
                           $data['line_no']=1; 
                           $data['product_id']=$_POST['bulk_product_id'][$i]; 
                           $data['uom_code_id']=$_POST['bulk_uom_code_id'][$i]; 
                           $data['required_qty']=$_POST['bulk_required_qty'][$i]; 
                           $data['need_by_date']=$_POST['bulk_need_by_date'][$i]; 
                           $data['created_at']=date('Y-m-d H:i:s'); 
                           $data['updated_at']=date('Y-m-d H:i:s');									
                           \DB::table('s_inquiry_lines_t')->insert($data); 
                           }
            }
            else
            {
                $existingId = array();
                foreach ($oldid as $key=> $value) 
                {
                    $oldIds[]= $value->$primaryline;
                }	
                foreach ($_POST['bulk_'.$primaryline] as $val) 
                {
                    $newIds[]= $val;
                }	
                $existingId=  array_replace($newIds, $oldIds);
                $oldcount=count($oldIds);
                $newcount=count($newIds);
                if($oldcount <= $newcount)
                {

                    for($i=0;$i<$newcount;$i++)
                    {
                        $data1['so_inquiry_hdr_id']=$id; 
                        $data1['so_inquiry_lines_id']=$_POST['bulk_so_inquiry_lines_id'][$i]?$_POST['bulk_so_inquiry_lines_id'][$i]:0; 
                        $data1['line_no']=1; 
                        $data1['product_id']=$_POST['bulk_product_id'][$i]; 
                        $data1['uom_code_id']=$_POST['bulk_uom_code_id'][$i]; 
                        $data1['required_qty']=$_POST['bulk_required_qty'][$i]; 
                        $data1['need_by_date']=$_POST['bulk_need_by_date'][$i]; 
                        $data1['created_at']=date('Y-m-d H:i:s'); 
                        $data1['updated_at']=date('Y-m-d H:i:s');

                        if($data['so_inquiry_lines_id']=$existingId[$i])
                        {
                            $this->modelline::find($data1['so_inquiry_lines_id'])->update($data1);
                        }
                        else
                        {		
                            \DB::table('s_inquiry_lines_t')->insert($data1); 
                        }
                    }
                }
                else
                {
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
                        \DB::table('s_inquiry_lines_t')->where('so_inquiry_lines_id',$val)->delete();
                    }
                    for($i=0;$i<count($oldIds);$i++)
                    {	
                        $data['so_inquiry_hdr_id']  = $id; 
                        $data['so_inquiry_lines_id']= $_POST['bulk_so_inquiry_lines_id'][$i]?$_POST['bulk_so_inquiry_lines_id'][$i]:0; 
                        $data['line_no']            = 1; 
                        $data['product_id']         = $_POST['bulk_product_id'][$i]; 
                        $data['uom_code_id']        = $_POST['bulk_uom_code_id'][$i]; 
                        $data['required_qty']       = $_POST['bulk_required_qty'][$i]; 
                        $data['need_by_date']       = $_POST['bulk_need_by_date'][$i]; 
                        $data['created_at']         = date('Y-m-d H:i:s'); 
                        $data['updated_at']         = date('Y-m-d H:i:s');
                        $this->modelline::find($data['so_inquiry_lines_id'])->update($data);
                    }
                }

            }
	}
	public function design($id=null)
	{
		//$table = \DB::table('s_inquiry_hdr_t')->where('so_inquiry_hdr_id',0)->get();
		$this->data['row']= (object) array();
		$this->data['row']->inquiry_no = "";
		$this->data['row']->inquiry_category = "";
		$this->data['row']->so_inquiry_hdr_id = "";
		$this->data['row']->inquiry_type = "";
		$this->data['row']->inquiry_date = "";
		$this->data['row']->remarks = "";
		$this->data['id'] = '';
		$this->data['customer_id'] = $this->jCombo('m_customers_t','customer_id','customer_name','');
		$this->data['project_id'] = $this->jCombo('projects_t','project_id','project_name','');
		$this->data['salespersons_id'] = $this->jCombo('s_salesperson_t','salesperson_id','salesperson_name','');
		$this->data['product_id'] = $this->jCombo('products_final_t','product_id','concatenated_product','');
		$this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
		return view('soinquiryhdr.form_design',$this->data);
	}
	
	public function getGridData()
	{
		$wh='';
		if($_GET['_search']=='true')
		{
		$wh=$this->jqgridsearch($_GET['filters']);
		}

		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		if(!$sidx) $sidx =1;
		$result = \DB::select("SELECT COUNT(so_inquiry_hdr_id) AS count FROM s_inquiry_hdr_t where 1=1 $wh");
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
		$SQL = "SELECT * FROM s_inquiry_hdr_t where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
		$download_SQL = "SELECT * FROM s_inquiry_hdr_t where 1=1 $wh ORDER BY $sidx $sord";
		
		$result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }
		
		$result = \DB::select( $SQL );
		$responce->rows[]='';
		$responce->rows=$result;
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $count;
		echo json_encode($responce);
	}
	function findPrimarykey( $table )
	{
	$primaryKey = '';
	foreach(\DB::select("show columns from ".$table." where extra like '%auto_increment%'") as $key)
	{
	$primaryKey = $key->Field;
	}
	return $primaryKey;
	}
	
}
