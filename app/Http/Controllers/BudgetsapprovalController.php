<?php

namespace App\Http\Controllers;

use App\Budgetsapproval;
use Illuminate\Http\Request;

class BudgetsapprovalController extends Controller
{
        public function __construct() {
        $this->data = array();
        $this->table = "f_budget_hdr_t";
        $this->pageModule = "budgetsapproval";
        $this->model = new Budgetsapproval;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'budgetsapproval',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu']=$this->indexs(); 
        $this->modelname = new Budgetsapproval();
        $this->data['pageFormtype'] = 'ajax';
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

       $table = \DB::table('f_budget_hdr_t')->get();
       $this->data['datas'] = $table;
       return view('budgetsapproval.table',$this->data);

    }

    public function getBudgetsapprovalData() {
        $wh = '';
        if ($_GET['_search'] == 'true') {
            $wh = $this->jqgridsearch('f_budget_hdr_t', $_GET['filters']);
        }
		$org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
		$wh.='and f_budget_hdr_t.company_id='.$compy;
		
        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if (!$sidx)
            $sidx = 1;
        $result = \DB::select("SELECT COUNT(budget_hdr_id) AS count FROM f_budget_hdr_t where 1=1 and f_budget_hdr_t.budget_status='INITIATED'  $wh");
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
                f_budget_hdr_t.budget_hdr_id,
                f_budget_hdr_t.budget_name,
                f_budget_hdr_t.budget_date,
                f_budget_hdr_t.budget_status,
                f_budget_hdr_t.budget_year,
                f_budget_hdr_t.budget_line_total
                FROM f_budget_hdr_t where f_budget_hdr_t.budget_status='INITIATED' $wh  ORDER BY $sidx $sord LIMIT $start , $limit";
		
		$download_SQL = "SELECT
                f_budget_hdr_t.budget_hdr_id,
                f_budget_hdr_t.budget_name,
                f_budget_hdr_t.budget_date,
                f_budget_hdr_t.budget_status,
                f_budget_hdr_t.budget_year,
                f_budget_hdr_t.budget_line_total
                FROM f_budget_hdr_t where f_budget_hdr_t.budget_status='INITIATED' $wh  ORDER BY $sidx $sord";
		
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
   
   public function getbudgetapproval($id = null,$status=null){
      
        	if($status!="" ){
        		$budget=\DB::table('f_budget_hdr_t')->where('budget_hdr_id', $id)->update(['budget_status' => $status]);
        		return response()->json(array('status' => 'success', 'message' => 'Budget Status Changed Successfully'));
        	}
        }
	

   
}
