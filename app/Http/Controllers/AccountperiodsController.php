<?php

namespace App\Http\Controllers;

use App\Accountperiods;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AccountperiodsController extends Controller
{
    public function __construct() {
        $this->data = array();
        
        $this->table = "f_account_periods_t";
        $this->pageModule = "accountperiods";
        $this->model = new Accountperiods;
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'accountperiods',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->modelname = new Accountperiods();
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

       $table = \DB::table('f_account_periods_t')->get();
       $this->data['datas'] = $table;
       return view('accountperiods.table',$this->data);
    }

      public function getAccountperiodsData() {
		  
        $wh = '';

		$org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid'); 
		$wh.='and f_account_periods_t.company_id='.$compy;  
		  
         
        $SQL = "SELECT 
                f_account_periods_t.account_period_id,
                f_account_periods_t.period_name,
                f_account_periods_t.month,
                f_account_periods_t.year,
                f_account_periods_t.from_date,
                f_account_periods_t.to_date,
                f_account_periods_t.quarter_no,
                f_account_periods_t.period_status,
				tb_users.first_name
                FROM f_account_periods_t left join tb_users on (tb_users.id=f_account_periods_t.created_by)where 1=1 $wh";
		 
				$result = \DB::select($SQL);

			  return DataTables::of($result)->make(true);
    }
    
     public function create($id=null)
    {

		if($id ==null)
		{
		 $this->data['row'] = (object) array();
            $this->data['row']->account_period_id = "";
            $this->data['row']->year = "";
            $this->data['row']->from_date = "";
            $this->data['row']->to_date = "";
            $this->data['row']->current_trx_date = "";
            $this->data['row']->period_name = "";
            $this->data['row']->quarter_no = "";
            $this->data['row']->period_status = "OPEN";
            
             $this->data['month'] = $this->jCombo('a_lookuplines_t','lookuplines_id','lookup_code','');
            $this->data['company_id'] = $this->jCombo('m_company_t','company_id','company_name',\Session::get('companyid'));
           $this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id')); 
		}
		else
		{		
		$row = \DB::table('f_account_periods_t')->where('account_period_id',$id)->get();
		$this->data['row'] = $row[0];
		
                $this->data['month'] = $this->jCombo('a_lookuplines_t','lookup_code','lookup_code',$row[0]->account_period_id);
//                dd($this->data['month']);
		$this->data['company_id'] = $this->jCombo('m_company_t','company_id','company_name',\Session::get('companyid'));
		$this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
            	}
			
      return view('accountperiods.form',$this->data);  
    }

    /* Karthigaa purpose for Save function */
    public function save(Request $request) {
        $id = '';

        $data = $this->validatePost($request->all(), $this->table, 'header');
        \DB::beginTransaction();
        try {
            $id = $this->model->insertRow($data);
            \DB::commit();
            return response()->json(array('status' => 'success', 'message' => 'Account Periods Saved', 'id' => $id));
        } catch (\Illuminate\Database\QueryException$e) {
            $message = explode('(', $e->getMessage());
            $dbCode = rtrim($message[0], ']');
            $dbCode = trim($dbCode, '[');
           //dd($dbCode);
            \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
    }

   
 /*Karthigaa purpose for Display View function*/
  public function show(request $request,$id=null){
        if(isset($id)){
          $vdata=\DB::table('f_account_periods_t')->leftjoin('m_company_t','m_company_t.company_id','=','f_account_periods_t.company_id')
												  ->leftjoin('tb_users','tb_users.id','=','f_account_periods_t.created_by')
                                                  ->where('account_period_id',$id)->get();

          $this->data['company_name']=$vdata[0]->company_name;
          $this->data['month']=$vdata[0]->month;
          $this->data['year']=$vdata[0]->year;
          $this->data['period_name']=$vdata[0]->period_name;
          $this->data['from_date']=$vdata[0]->from_date;
          $this->data['to_date']=$vdata[0]->to_date;
          $this->data['quarter_no']=$vdata[0]->quarter_no;
          $this->data['period_status']=$vdata[0]->period_status;
		  $this->data['first_name']=$vdata[0]->first_name;
          
          return view('accountperiods.view',$this->data);
        }
    }

  
  
    public function destroy(Accountperiods $accountperiods)
    {
        //
    }
public function getcurrentdate($year=null,$month=null){
//    dd($year);
//    dd($month);
    $nmonth = date("m", strtotime("$month"));
    $a=date($year.'-'.$nmonth.'-d');
    $data['from_date']= date('Y-m-01',strtotime($a));
    $data['to_date'] =date('Y-m-t',strtotime($a));
    $data['quater'] =ceil(date($nmonth, time()) / 3);;
    return $data;
}

}
