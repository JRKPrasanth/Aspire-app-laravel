<?php

namespace App\Http\Controllers;

use App\glbalances;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class GlbalancesController extends Controller
{
     public function __construct() {
        $this->data = array();
        $this->table = "f_journal_entry_t";
        $this->pageModule = "glbalances";
        $this->model = new glbalances;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'glbalances',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->modelname = new glbalances();
        $this->data['pageFormtype'] = 'ajax';
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

     $this->data['journalopt'] = $this->jqgridselect('f_journal_entry_t', 'journal_entry_id', 'journal_name');
     $table = \DB::table('f_journal_entry_t')->get();
     $this->data['datas'] = $table;
     return view('glbalances.table',$this->data);
    }

      public function getglbalanceData() {
        $wh = '';

		$org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
		$wh.='and f_journal_entry_lines_t.company_id='.$compy;

            $wh.=$grid_data=$this->grid_check('f_journal_entry_lines_t','journal_date');
       
          
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
                where 1=1 and f_journal_entry_lines_t.status='1' $wh" ;
        
        $result = \DB::select($SQL);
		return DataTables::of($result)->make(true);
		  
    }
	
}
