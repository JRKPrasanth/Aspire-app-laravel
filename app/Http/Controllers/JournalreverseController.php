<?php

namespace App\Http\Controllers;

use App\Journalreverse;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\DataTables;

class JournalreverseController extends Controller
{
     public function __construct() {
        $this->data = array();
        $this->table = "f_journal_entry_t";
        $this->pageModule = "journalreverse";
        $this->model = new Journalreverse;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'journalreverse',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu']=$this->indexs(); 
        $this->modelname = new Journalreverse();
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
       return view('journalreverse.table',$this->data);
    }

      public function getJournalreverseData() {
		  
        $wh = '';

		$org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
		$wh.=' and f_journal_entry_t.company_id='.$compy;
		 

            $wh.=$grid_data=$this->grid_statuscheck('f_journal_entry_t','journal_date','journal_status','=',"'POSTED' or 'APPROVED'  or 'SUBMITTED'");

          
        $SQL = "SELECT 
                f_journal_entry_t.journal_entry_id,
                f_journal_entry_t.journal_name,
                f_journal_entry_t.journal_type,
                f_journal_entry_t.journal_reference,
                f_journal_entry_t.journal_date,
                f_journal_entry_t.journal_status
                FROM f_journal_entry_t where (f_journal_entry_t.journal_status='POSTED' or f_journal_entry_t.journal_status='APPROVED' or f_journal_entry_t.journal_status= 'SUBMITTED') $wh ORDER BY f_journal_entry_t.journal_entry_id DESC";

        
        $result = \DB::select($SQL);
		return DataTables::of($result)->make(true);
		  
    }
	
     /* purpose for Display View function*/
  public function show(request $request,$id=null){
        if(isset($id)){
          $vdata=\DB::table('f_journal_entry_t')->where('journal_entry_id',$id)->get();
            $this->data['journal_entry_id']=$vdata[0]->journal_entry_id;
          $this->data['journal_name']=$vdata[0]->journal_name;
          $this->data['journal_date']=$vdata[0]->journal_date;
          $this->data['journal_type']=$vdata[0]->journal_type;
          $this->data['journal_reference']=$vdata[0]->journal_reference;
          $this->data['journal_status']=$vdata[0]->journal_status;
          
          $vlinesdata=\DB::table('f_journal_entry_lines_t')->leftjoin('f_account_structure_t','f_account_structure_t.f_account_structure_id','=','f_journal_entry_lines_t.account_id')
                  ->where('journal_entry_id',$id)->get();
//          dd($vlinesdata);
           $this->data['vlinesdata']=$vlinesdata; 
           $this->data['f_journal_entry_line_id']=$vlinesdata[0]->f_journal_entry_line_id;
          $this->data['journal_date']=$vlinesdata[0]->journal_date;
          $this->data['account_id']=$vlinesdata[0]->account_id;
          $this->data['concatenated_segments']=$vlinesdata[0]->concatenated_segments;
          $this->data['debit_amount']=$vlinesdata[0]->debit_amount;
          $this->data['credit_amount']=$vlinesdata[0]->credit_amount;
          

          return view('journalreverse.view',$this->data);
        }
    }
         public function reversejournal($id=null)
    {
		//Journal Hdr Insert 
                $reverse="REVERSE";  
                $date=date('Y-m-d');
           $org=\Session::get('organization');
           $loc=\Session::get('location');
           $compy=\Session::get('companyid');
            $journalhdr= \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$reverse','REVERSE','$date','$id','REVERSED','$compy','$loc','$org')");
            $jid = DB::getPdo()->lastInsertId();
            
      

             	   $jlinesid=DB::table('f_journal_entry_lines_t')->where('journal_entry_id',$id)->get();

               foreach($jlinesid as $key => $value) {
                    $inv_acc['journal_entry_id']   = $jid; 
                    $inv_acc['account_id']   =$value->account_id;
                     $inv_acc['journal_date']   =$date;
                    $inv_acc['debit_amount']   =$value->credit_amount;
                    $inv_acc['credit_amount']   = $value->debit_amount;
                    $inv_acc['organization_id']   = $org;
                    $inv_acc['location_id']   = $loc;
                    $inv_acc['company_id']   = $compy;
                    \DB::table('f_journal_entry_lines_t')->insert($inv_acc);
                    }

              return response()->json(array('status' => 'success', 'message' => 'Jouranl Reversed Successfully','id' => $id));

    }
}
