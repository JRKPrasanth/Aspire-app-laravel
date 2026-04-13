<?php

namespace App\Http\Controllers;

use App\Soquotecopy;
use Illuminate\Http\Request;

class SoquotecopyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

	public function __construct()
	{
		$this->data=array();
		$this->table="s_quote_hdr_t";
		$this->pageModule="soquotecopy";
		$this->model=new Soquotecopy;
		$this->data['pageModule']=$this->pageModule;
                $this->data['urlmenu']=$this->indexs(); 
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

        //

		return view('soquotecopy.table',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null)
		
    { 
		$maindate = $this->dateform('date'); 
		
		
        	$this->data['row']= (object) array();
		
					$this->data['row']->quote_no="";
					$this->data['row']->quote_name="";
					$this->data['row']->quote_date="";
					$this->data['row']->quote_expiry_date="";
          $this->data['row']->remarks="";
					$this->data['id'] = '';
					$this->data['linedata'] = array();
          $this->data['uomcode_id'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
		          
		  $this->data['maindate']=$maindate; 
					$this->data['product_id'] = $this->jCombo('m_products_t','product_id','concatenated_product',''); //dd($this->data['product_id']);
		
		return view('soquotecopy.form',$this->data);
    }

   
    public function store(Request $request)
    {
        //
    }

   
    public function show(Soquote $soquote)
    {
        //
    }

   
    public function edit(Soquote $soquote)
    {
        //
    }

    public function update(Request $request, Soquote $soquote)
    {
        //
    }

    public function destroy(Soquote $soquote)
    {
        //
    }

	public function soquotegriddata()
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

	$result = \DB::select("SELECT COUNT(quote_hdr_id) AS count FROM s_quote_hdr_t where 1=1 $wh");
	$count = $result[0]->count;
	if( $count > 0 && $limit > 0) {
	$total_pages = ceil($count/$limit);
	} else {
	$total_pages = 0;
	}

	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit;
	if($start <0) $start = 0;

	$SQL = "SELECT * FROM `s_quote_hdr_t`  where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";
	$result = \DB::select( $SQL );


	$responce->rows[]='';
	$responce->rows=$result;
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;

	echo json_encode($responce);

}
}
