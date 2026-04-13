<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Subledger;
use Illuminate\Http\Request;

class SubledgerController extends Controller
{
     public function __construct() {
        $this->data = array();
        $this->table = "f_journal_entry_t";
        $this->pageModule = "subledger";
        $this->model = new Subledger;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'subledger',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        
        $this->modelname = new Subledger();
        $this->data['pageFormtype'] = 'ajax';
        $this->data['urlmenu']=$this->indexs(); 

    }

 public function filterindex(Request $request)
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

         $this->data['journalopt'] = $this->jqgridselect('f_journal_entry_t', 'journal_entry_id', 'journal_name');
         $table = \DB::table('f_journal_entry_t')->get();
         $this->data['datas'] = $table;
      
       return view('subledger.filtertable',$this->data);
    }
	
     public function getsubledgerfilterData(Request $request) {
		 
        $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');

        $wh='and f_journal_entry_lines_t.company_id='.$compy;

    $start_date = $request->start_date ? date("Y-m-d", strtotime($request->start_date)) : '';
    $end_date = $request->end_date ? date("Y-m-d", strtotime($request->end_date)) : '';

             if($start_date !='')
             { 
                   $wh.= " and f_journal_entry_lines_t.journal_date  between '$start_date' and '$end_date'";
	         }

          
        $SQL = "SELECT 
                 f_journal_entry_lines_t.f_journal_entry_line_id,
                 f_journal_entry_t.journal_name,
                 f_journal_entry_lines_t.journal_date,
                 f_account_structure_t.concatenated_segments as account_id,
                 f_journal_entry_lines_t.debit_amount,
                 f_journal_entry_lines_t.credit_amount
                FROM  f_journal_entry_lines_t 
                left join f_journal_entry_t on (f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id)
                left join f_account_structure_t on (f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id)
                where 1=1  $wh ";

				$result = \DB::select($SQL);
	
				return DataTables::of($result)->make(true);
		 
			}
	
	
      public function getsubledgerload() {
         $wh = '';
        $frmdate=$_GET['fromdate'];
        $todate=$_GET['todate'] ;
       $from_date = date("Y-m-d", strtotime($frmdate));
       $to_date = date("Y-m-d", strtotime($todate));
         if($_GET['fromdate']!='')
          {
                   $wh.= " and f_journal_entry_lines_t.journal_date  between '" . $from_date . "' and '" . $to_date . "'";
	  }
        //dd($wh);
          if(isset($_GET['filters'])){
            if (isset($_GET['_search']) == 'true') {
                $wh.= $this->jqgridsearch('f_journal_entry_lines_t', $_GET['filters']);
            }
          }

	$org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
		$wh.='and f_journal_entry_lines_t.company_id='.$compy;
	if(isset($_GET['page']))
            $page = $_GET['page'];
        else
            $page =0;
        if(isset($_GET['rows']))
            $limit = $_GET['rows'];
        else
            $limit = 0;
        if(isset($_GET['sidx']))
            $sidx = $_GET['sidx'];
        else
            $sidx=0;
        if(isset($_GET['sord']))
            $sord = $_GET['sord'];
        else
            $sord=0;
        if(isset($_GET['sidx'])){
            if (!$sidx)
                $sidx = 1;
        }else{
            $sidx=0;
        }
        $result = \DB::select("SELECT COUNT(f_journal_entry_line_id) AS count FROM f_journal_entry_lines_t where 1=1 $wh ");
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
                 f_journal_entry_lines_t.f_journal_entry_line_id,
                 f_journal_entry_t.journal_name as journal_entry_id,
                 f_journal_entry_lines_t.journal_date,
                 f_account_structure_t.concatenated_segments as account_id,
                 f_journal_entry_lines_t.debit_amount,
                 f_journal_entry_lines_t.credit_amount
                FROM  f_journal_entry_lines_t 
                left join f_journal_entry_t on (f_journal_entry_t.journal_entry_id=f_journal_entry_lines_t.journal_entry_id)
                left join f_account_structure_t on (f_account_structure_t.f_account_structure_id=f_journal_entry_lines_t.account_id)
                where 1=1 ";
//        dd($SQL);
        $result = \DB::select($SQL);
//        dd($result);
        $responce->rows[] = '';
        $responce->rows = $result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        echo json_encode($responce);
    }
    
     public function getledgerpost($id = null){
              $journal=\DB::select("update f_journal_entry_lines_t set status='1'  WHERE f_journal_entry_line_id IN ($id)");
             return response()->json(array('status' => 'success', 'message' => 'Ledger Posted Successfully','id' => $id));
        	
        }
         /*Karthigaa purpose for Display View function*/
  public function show(request $request,$id=null){ 
        if(isset($id)){
          $vdata=\DB::table('f_journal_entry_t')->where('journal_entry_id',$id)->get();
$this->data['journal_entry_id']=$vdata[0]->journal_entry_id;
          $this->data['journal_name']=$vdata[0]->journal_name;
          $this->data['journal_date']=$vdata[0]->journal_date;
          $this->data['journal_type']=$vdata[0]->journal_type;
          $this->data['journal_reference']=$vdata[0]->journal_reference;
          $this->data['journal_status']=$vdata[0]->journal_status;
          
          $vlinesdata=\DB::table('f_journal_entry_lines_t')->leftjoin('f_account_structure_t as credit','credit.f_account_structure_id','=','f_journal_entry_lines_t.account_id')
                  ->where('journal_entry_id',$id)->get();
          
           $this->data['vlinesdata']=$vlinesdata; 

          $this->data['account_id']=$vlinesdata[0]->concatenated_segments;
          $this->data['debit_amount']=$vlinesdata[0]->debit_amount;
          $this->data['credit_amount']=$vlinesdata[0]->credit_amount;
          

          return view('subledger.view',$this->data);
        }
    }
        
}
