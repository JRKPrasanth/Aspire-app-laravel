<?php

namespace App\Http\Controllers;

use App\Journalposting;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JournalpostingController extends Controller
{
    public function __construct() {
        $this->data = array();
        $this->table = "f_journal_entry_t";
        $this->pageModule = "journalentry";
        $this->model = new Journalposting;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'journalposting',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu']=$this->indexs(); 
        $this->modelname = new Journalposting();
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

       $table = \DB::table('f_journal_entry_t')->get();
       $this->data['datas'] = $table;
       return view('journalposting.table',$this->data);
    }

   
     public function getJournalpostingData() {
        $wh = '';

		$org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
		$wh.='and f_journal_entry_t.company_id='.$compy;

            $wh.=$grid_data=$this->grid_statuscheck('f_journal_entry_t','journal_date','journal_status','=',"'APPROVED'");
     
          
        $SQL = "SELECT 
                f_journal_entry_t.journal_entry_id,
                f_journal_entry_t.journal_name,
                f_journal_entry_t.journal_type,
                f_journal_entry_t.journal_reference,
                f_journal_entry_t.journal_date,
                f_journal_entry_t.journal_status
                FROM f_journal_entry_t where f_journal_entry_t.journal_status='APPROVED' $wh ORDER BY f_journal_entry_t.journal_entry_id DESC";


        
        $result = \DB::select($SQL);
		return DataTables::of($result)->make(true);

    }
  
    public function getJournalpost($id = null,$status=null){
        	if($status!="" ){
                        $journal=\DB::select("update f_journal_entry_t set journal_status='POSTED'  WHERE journal_entry_id IN ($id)");
        		return response()->json(array('status' => 'success', 'message' => 'Journal Posted Successfully','id' => $id));
        	}
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
          

          return view('journalposting.view',$this->data);
        }
    }

 
  
}
