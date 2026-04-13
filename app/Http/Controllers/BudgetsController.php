<?php

namespace App\Http\Controllers;

use App\Budgets;
use App\Budgetlines;
use App\Budgetamount;
use DB;
use Illuminate\Http\Request;

class BudgetsController extends Controller
{

      public function __construct() {
        $this->data = array();
        $this->table = "f_budget_hdr_t";
        $this->subtable = "f_budget_lines_t";
        $this->pageModule = "budgets";
        $this->model = new Budgets;
        $this->submodel = new Budgetlines;
          $this->modelline = new Budgetamount();
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'budgets',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['urlmenu']=$this->indexs(); 
        $this->modelname = new Budgets();
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
       $this->data['pageMethod']="budgets";
       return view('budgets.table',$this->data);
    }
   public function getBudgetsData() {
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
        $result = \DB::select("SELECT COUNT(budget_hdr_id) AS count FROM f_budget_hdr_t where 1=1 $wh");
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
                FROM f_budget_hdr_t where 1=1  $wh ORDER BY $sidx $sord LIMIT $start , $limit";
	   
	   $download_SQL = "SELECT
                f_budget_hdr_t.budget_hdr_id,
                f_budget_hdr_t.budget_name,
                f_budget_hdr_t.budget_date,
                f_budget_hdr_t.budget_status,
                f_budget_hdr_t.budget_year,
                f_budget_hdr_t.budget_line_total
                FROM f_budget_hdr_t where 1=1  $wh ORDER BY $sidx $sord";
	   
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

      public function create($id=null)
	{
   		if($id == null)
		{

		$this->data['row']= (object) array();
                $this->data['row']->budget_hdr_id = "";
                $this->data['row']->budget_date = date('Y-m-d');
                $this->data['row']->budget_name = "";
                $this->data['row']->budget_description  = "";
                $this->data['row']->budget_status = "";
                $this->data['row']->budget_check_level  = "";
                $this->data['row']->current_budget_level  = "";
                $this->data['row']->budget_from_date = "";
                $this->data['row']->budget_to_date = "";
                $this->data['row']->budget_line_total = "";
                $this->data['row']->actual_line_total = "";
                $this->data['row']->variance_total = "";
                $this->data['row']->active = "";
		$this->data['id'] = '';

                $this->data['parent_budget_id'] = $this->jCombo('f_budget_hdr_t','budget_hdr_id','budget_name','');
                $this->data['original_budget_id'] = $this->jCombo('f_budget_hdr_t','budget_hdr_id','budget_name','');
                $this->data['budget_currency_id'] = $this->jCombo('f_account_currency_t','account_currency_id','currency_code','');
                $this->data['budget_year'] = $this->jCombocomp('f_account_periods_t','year','year','');
                $this->data['budget_from_period_id'] = $this->jCombocomp('f_account_periods_t','account_period_id','month','');
                $this->data['budget_to_period_id'] = $this->jCombocomp('f_account_periods_t','account_period_id','month','');
                $this->data['company_id'] = $this->jCombo('m_company_t','company_id','company_name',\Session::get('companyid'));
                $this->data['created_by'] = $this->jCombo('tb_users','id','username','');

                $this->data['line_account1'] = $this->jCombo('m_company_t','company_id','company_name','');
                $this->data['line_account2'] = $this->jCombo('m_location_t','location_id','location_name','');
		$this->data['line_account3'] = $this->jCombo('m_department_lines_t','department_line_id','sub_department_name','');
		$this->data['line_account4'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');

                $this->data['linedata'] = array();
                 $this->data['lineamount'] = array();

		}
		  else
	{
			$this->data['id'] = $id;
		$table = \DB::table('f_budget_hdr_t')->where('budget_hdr_id',$id)->get();
		$this->data['row'] = $table[0];
		$tablelines = \DB::table('f_budget_lines_t')->where('budget_hdr_id',$id)->get();
		$this->data['linedata'] = $tablelines;

                $tableamt = \DB::table('f_budget_amount_t')->where('budget_line_id',$id)->get();
              //  dd($tableamt);
		$this->data['lineamount'] = $tableamt;

                $this->data['parent_budget_id'] = $this->jCombo('f_budget_hdr_t','budget_hdr_id','budget_name',$table[0]->parent_budget_id);
                $this->data['original_budget_id'] = $this->jCombo('f_budget_hdr_t','budget_hdr_id','budget_name',$table[0]->original_budget_id);
                $this->data['budget_currency_id'] = $this->jCombo('f_account_currency_t','account_currency_id','currency_code',$table[0]->budget_currency_id);
                $this->data['budget_year'] = $this->jCombocomp('f_account_periods_t','year','year',$table[0]->budget_year);
                $this->data['budget_from_period_id'] = $this->jCombocomp('f_account_periods_t','account_period_id','month',$table[0]->budget_from_period_id);
//                dd($this->data['budget_from_period_id']);
                $this->data['budget_to_period_id'] = $this->jCombocomp('f_account_periods_t','account_period_id','month',$table[0]->budget_to_period_id);

                $this->data['company_id'] = $this->jCombo('m_company_t','company_id','company_name',\Session::get('company'));
                $this->data['created_by'] = $this->jCombo('tb_users','id','username','');
                $this->data['budget_status'] = $table[0]->budget_status;

	  }
		if(count($this->data['linedata']) >= 1)
		{

                foreach ($this->data['linedata'] as $key => $value) {
						$this->data['linedata'][$key]->line_account1 = $this->data['line_account1'] = $this->jCombo('m_company_t','company_id','company_name',$value->line_account1);
						$this->data['linedata'][$key]->line_account2 =  $this->jCombo('m_location_t','location_id','location_name',$value->line_account2);
                        $this->data['linedata'][$key]->line_account3 =  $this->jCombo('m_department_lines_t','department_line_id','sub_department_name',$value->line_account3);
                        $this->data['linedata'][$key]->line_account4 =  $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments',$value->line_account4);
                }
                foreach ($this->data['lineamount'] as $akey => $avalue) {
			$this->data['lineamount'][$akey]->account_period = $avalue->account_period;
			$this->data['lineamount'][$akey]->monthly_amount = $avalue->monthly_amount;
                }
		}
//                dd($this->data);
          	return view('budgets.form',$this->data);

        }
    /*Karthigaa purpose for Save function*/
         public function save(Request $request){
//             dd($_POST);
                     $id='';
        	     $data = $this->validatePost($request->all(),$this->table,'header');
                     $id=$this->model->insertRow($data);
                     $lines_data= $_POST['bulk_budget_amount'];
                     $budgtamt=  $_POST['bulk_monthly_amount'];
                     $lineid= $_POST['bulk_budget_line_id'];

                     $count=count($lineid);
                     $hdrid=$_POST['budget_hdr_id'];
                     if($hdrid==""){
        if($count>0){

            foreach($lineid as $key=>$value){
                    if(empty($value)){
                                $linedata['budget_hdr_id']=$id;
                                $linedata['line_no']=$_POST['bulk_line_no'][$key];
				$linedata['line_account1']=$_POST['bulk_line_account1'][$key];
				$linedata['line_account2']=$_POST['bulk_line_account2'][$key];
                                $linedata['line_account3']=$_POST['bulk_line_account3'][$key];
                                $linedata['line_account4']=$_POST['bulk_line_account4'][$key];
                                $linedata['budget_amount']=$_POST['bulk_budget_amount'][$key];
                                $linedata['overallmonthamt']=$_POST['bulk_overallmonthamt'][$key];
                                $linedata['overaccperiod']=$_POST['bulk_overaccperiod'][$key];
                                $linedata['company_id']=\Session::get('companyid');
                                $linedata['location_id']=\Session::get('location');
                                $linedata['organization_id']=\Session::get('organization');
				\DB::table('f_budget_lines_t')->insert($linedata);
                                 $lid = DB::getPdo()->lastInsertId();
                                      /*Budget Modal*/
        $budlid=\DB::table('f_budget_lines_t')->where('budget_line_id',$lid)->get();
             $accperiod= $budlid[0]->overaccperiod;
             $budgtamt= $budlid[0]->overallmonthamt;
             $budgtamt = explode(',',$budgtamt);
             $accperiod = explode(',',$accperiod);
        foreach($budlid as $lkey=>$lvalue){
                         foreach($budgtamt as $akey=>$avalue){
                            $datas['budget_line_id']=$lvalue->budget_line_id;
                            $datas['account_period']=$accperiod[$akey];
                            $datas['monthly_amount']=$avalue;
                            \DB::table('f_budget_amount_t')->insert($datas);
                            }
                        }
                    }
                     }
        }
                     }
else{
$lnid=$_POST['bulk_budget_line_id'];
//dd('dsfsdf');
//echo count($lnid); exit;
foreach($lnid as $lnkey=>$lnvalue){
  //  dd($lnvalue);
     if($lnvalue!=""){


                               $hdrid=$lnid['budget_hdr_id']=$_POST['bulk_budget_hdr_id'][$lnkey];
                               $bud_lineid=$lnid['budget_line_id']=$_POST['bulk_budget_line_id'][$lnkey];
				$line_account1=$lnid['line_account1']=$_POST['bulk_line_account1'][$lnkey];
				$line_account2=$lnid['line_account2']=$_POST['bulk_line_account2'][$lnkey];
                                $line_account3=$lnid['line_account3']=$_POST['bulk_line_account3'][$lnkey];
                                $line_account4=$lnid['line_account4']=$_POST['bulk_line_account4'][$lnkey];
                                $budget_amount=$lnid['budget_amount']=$_POST['bulk_budget_amount'][$lnkey];
                                $overallmonthamt=$lnid['overallmonthamt']=$_POST['bulk_overallmonthamt'][$lnkey];
                                $overaccperiod=$lnid['overaccperiod']=$_POST['bulk_overaccperiod'][$lnkey];
                                //dd("UPDATE f_budget_lines_t SET line_account1 = '$line_account1',line_account1 = '$line_account1',line_account2 = '$line_account2',line_account3 = '$line_account3',line_account4 = '$line_account4',budget_amount = '$budget_amount',overallmonthamt = '$overallmonthamt',overaccperiod = '$overaccperiod' WHERE budget_line_id='$bud_lineid'");
                              \DB::update("UPDATE f_budget_lines_t SET line_account1 = '$line_account1',line_account1 = '$line_account1',line_account2 = '$line_account2',line_account3 = '$line_account3',line_account4 = '$line_account4',budget_amount = '$budget_amount',overallmonthamt = '$overallmonthamt',overaccperiod = '$overaccperiod' WHERE budget_line_id='$bud_lineid'");

        $budamt=\DB::table('f_budget_amount_t')->where('budget_line_id',$lnvalue)->get();
       // dd($budamt);
        $budacc= $overaccperiod;
         $budacc = explode(',',$budacc);
          $amount= $overallmonthamt;
           $amount1 = explode(',',$amount);
//           dd($amount1);
     foreach($amount1 as $amtkey=>$amtvalue){
         $budamtid=$budamt[$amtkey]->budget_amount_id;
         $budgetlnid=$budamt[$amtkey]->budget_line_id;
         $monthamt=$amtvalue;
         $accperiod=$budacc[$amtkey];

         \DB::update("UPDATE f_budget_amount_t SET monthly_amount = '$monthamt',account_period = '$accperiod' WHERE budget_amount_id='$budamtid'");
            }
        }
        else{
//            dd($lnvalue);

                                $lnid1['budget_hdr_id']=$id;
                                $lnid1['budget_line_id']="";
                                $lnid1['line_no']=$_POST['bulk_line_no'][$lnkey];
				$lnid1['line_account1']=$_POST['bulk_line_account1'][$lnkey];
				$lnid1['line_account2']=$_POST['bulk_line_account2'][$lnkey];
                                $lnid1['line_account3']=$_POST['bulk_line_account3'][$lnkey];
                                $lnid1['line_account4']=$_POST['bulk_line_account4'][$lnkey];
                                $lnid1['budget_amount']=$_POST['bulk_budget_amount'][$lnkey];
                                $lnid1['overallmonthamt']=$_POST['bulk_overallmonthamt'][$lnkey];
                                $lnid1['overaccperiod']=$_POST['bulk_overaccperiod'][$lnkey];
//                                print_r($lnid1);
				\DB::table('f_budget_lines_t')->insert($lnid1);
                                 $lid = DB::getPdo()->lastInsertId();
                                      /*Budget Modal*/
        $budlid=\DB::table('f_budget_lines_t')->where('budget_line_id',$lid)->get();
             $accperiod= $budlid[0]->overaccperiod;
             $budgtamt= $budlid[0]->overallmonthamt;
             $budgtamt = explode(',',$budgtamt);
             $accperiod = explode(',',$accperiod);
        foreach($budlid as $lkey=>$lvalue){
                         foreach($budgtamt as $akey=>$avalue){
                            $datas['budget_line_id']=$lvalue->budget_line_id;
                            $datas['account_period']=$accperiod[$akey];
                            $datas['monthly_amount']=$avalue;
                            $datas['company_id']=\Session::get('companyid');
                                $datas['location_id']=\Session::get('location');
                                $datas['organization_id']=\Session::get('organization');
                            \DB::table('f_budget_amount_t')->insert($datas);
                            }
                        }
      // dd("insert");
}
}

      }



   return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id));
}

/*Karthigaa purpose for Display View function*/
  public function show(request $request,$id=null){
        if(isset($id)){
          $vdata=\DB::table('f_budget_hdr_t')->select('f_budget_hdr_t.*','fromperiod.month as from_month','toperiod.month as to_month',
                  'f_account_currency_t.currency_code','year.year','m_company_t.company_name')
                  ->leftjoin('f_account_periods_t as fromperiod','fromperiod.account_period_id','=','f_budget_hdr_t.budget_from_period_id')
                  ->leftjoin('f_account_periods_t as toperiod','toperiod.account_period_id','=','f_budget_hdr_t.budget_to_period_id')
                  ->leftjoin('f_account_periods_t as year','year.account_period_id','=','f_budget_hdr_t.budget_year')
                  ->leftjoin('f_account_currency_t','f_account_currency_t.account_currency_id','=','f_budget_hdr_t.budget_currency_id')
                  ->leftjoin('m_company_t','m_company_t.company_id','=','f_budget_hdr_t.company_id')
                  ->where('budget_hdr_id',$id)->get();

          $this->data['budget_name']=$vdata[0]->budget_name;
          $this->data['budget_date']=$vdata[0]->budget_date;
          $this->data['budget_description']=$vdata[0]->budget_description;
          $this->data['current_budget_level']=$vdata[0]->current_budget_level;
          $this->data['budget_check_level']=$vdata[0]->budget_check_level;
          $this->data['parent_budget_id']=$vdata[0]->parent_budget_id;
          $this->data['original_budget_id']=$vdata[0]->original_budget_id;
          $this->data['budget_status']=$vdata[0]->budget_status;
          $this->data['budget_year']=$vdata[0]->budget_year;
          $this->data['budget_from_period_id']=$vdata[0]->from_month;
          $this->data['budget_to_period_id']=$vdata[0]->to_month;
          $this->data['budget_from_date']=$vdata[0]->budget_from_date;
          $this->data['budget_to_date']=$vdata[0]->budget_to_date;
          $this->data['budget_currency_id']=$vdata[0]->currency_code;
          $this->data['budget_line_total']=$vdata[0]->budget_line_total;
          $this->data['variance_total']=$vdata[0]->variance_total;
          $this->data['active']=$vdata[0]->active;
          $this->data['company_name']=$vdata[0]->company_name;
          
          
          $vlinesdata=\DB::table('f_budget_lines_t')
                  ->leftjoin('m_company_t','m_company_t.company_id','=','f_budget_lines_t.line_account1')
                  ->leftjoin('m_location_t','m_location_t.location_id','=','f_budget_lines_t.line_account2')
                  ->leftjoin('m_department_lines_t','m_department_lines_t.department_line_id','=','f_budget_lines_t.line_account3')
                  ->leftjoin('f_account_structure_t','f_account_structure_t.f_account_structure_id','=','f_budget_lines_t.line_account4')
                  ->where('budget_hdr_id',$id)->get();
//          dd($vlinesdata);
           $this->data['vlinesdata']=$vlinesdata; 
          $this->data['line_no']=$vlinesdata[0]->line_no;
          $this->data['company_name']=$vlinesdata[0]->company_name;
          $this->data['location_name']=$vlinesdata[0]->location_name;
          $this->data['department_name']=$vlinesdata[0]->department_name;
          $this->data['concatenated_segments']=$vlinesdata[0]->concatenated_segments;
          $this->data['budget_amount']=$vlinesdata[0]->budget_amount;
          
          
          return view('budgets.view',$this->data);
        }
    }

   /*Karthigaa Purpose for detail Budgets*/
 public function detailcreate($id=null)
	{
       		$this->data['id'] = $id;//master id
		$table = \DB::table('f_budget_hdr_t')->where('budget_hdr_id',$id)->get();
                  $this->data['row']= (object) array();
		$this->data['row'] = $table[0];

                 $this->data['row']->budget_date = date('Y-m-d');
                 $this->data['row']->line_id ="";
                 $this->data['row']->budget_status ="";
                 $parentid=$this->data['parent_budget_id'] = $this->jCombo('f_budget_hdr_t','budget_hdr_id','budget_name','');
//                 dd($parentid);
                $this->data['original_budget_id'] = $this->jCombo('f_budget_hdr_t','budget_hdr_id','budget_name','');
                $this->data['budget_currency_id'] = $this->jCombo('f_account_currency_t','account_currency_id','currency_code','');
                $this->data['budget_year'] = $this->jCombocomp('f_account_periods_t','year','year','');
                $this->data['budget_from_period_id'] = $this->jCombocomp('f_account_periods_t','account_period_id','month','');
                $this->data['budget_to_period_id'] = $this->jCombocomp('f_account_periods_t','account_period_id','month','');

                $this->data['linedata'] = array();

                $this->data['company_id'] = $this->jCombo('m_company_t','company_id','company_name',\Session::get('company'));
                $this->data['created_by'] = $this->jCombo('tb_users','id','username','');
//                $this->data['budget_status'] = $table[0]->budget_status;
                $this->data['line_account1'] =  $this->jCombo('m_company_t','company_id','company_name','');
                $this->data['line_account2'] =  $this->jCombo('m_location_t','location_id','location_name','');
                $this->data['line_account3'] =  $this->jCombo('m_department_lines_t','department_line_id','sub_department_name','');
                $this->data['line_account4']=  $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');

				if(count($this->data['linedata']) >= 1)
				{
				  foreach ($this->data['linedata'] as $key => $value) {
								$this->data['linedata'][$key]->line_account1 = $this->data['line_account1'] = $this->jCombo('m_company_t','company_id','company_name','');
								$this->data['linedata'][$key]->line_account2 =  $this->jCombo('m_location_t','location_id','location_name','');
								$this->data['linedata'][$key]->line_account3 =  $this->jCombo('m_department_lines_t','department_line_id','sub_department_name','');
								$this->data['linedata'][$key]->line_account4 =  $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
						}
				}

             $hdr_id=$id;
             $budgethdr=\DB::select("SELECT * FROM `f_budget_hdr_t` WHERE `original_budget_id`='$hdr_id' or budget_hdr_id='$hdr_id'");
             $budget_lines=\DB::select("select f_budget_lines_t.* from f_budget_hdr_t left join f_budget_lines_t on (f_budget_hdr_t.budget_hdr_id=f_budget_lines_t.budget_hdr_id) where f_budget_hdr_t.original_budget_id='$hdr_id' or f_budget_hdr_t.budget_hdr_id='$hdr_id'");
//             dd($budget_lines);
             $menu=[];
                  foreach ($budgethdr as $key => $value)
                {
                    $menus['items'][$value->line_id] = $value ;

                }
                foreach($budget_lines as $key=>$value){
                     $menus['parents'][$value->budget_hdr_id][] = $value;
//                    dd($menu);
                }
                $html =$this->createTreeView($hdr_id, $menus);
                $this->data['tree']=$html;
          	return view('budgets.detailform',$this->data);

        }


         /*Karthigaa purpose:to get Month Wise Budget Amount details*/
	public function monthwisebudget($from_date=null,$to_date=null)
	{
	  $sql=\DB::select("SELECT f_account_periods_t.month FROM `f_account_periods_t` WHERE to_date BETWEEN '".date('Y-m-d',strtotime($from_date))."  ' and '".date('Y-m-d',strtotime($to_date))."'");
		$html = '';

                foreach($sql as $key=>$val)
		{
                     //  $budamt=\DB::select("select * from f_budget_amount_t where budget_line_id = '$budlineid' ");
//                         if(empty($budamt)){
//                                $amtid=0;
//                               $monthamt=0;
//                              }
//                           else{
//                               $amtid=$budamt[$key]->budget_amount_id;
//                               $monthamt=$budamt[$key]->monthly_amount;
//                                }
			$html.='<tr class="table'.$key.'"><body>
                            <td><input type="hidden" name="bulk_budget_amount_id[]" class="form-control input-sm bulk_budget_amount_id"  value=""></td>
					<td>
					<input type="text" name="bulk_account_period[]" class="input-sm bulk_account_period" readonly="readonly" value="'.$val->month.'" style="color:black;background-color:#00000014;">
					</td>
					<td >
                                        <input type="text" name="bulk_monthly_amount[]"  class="input-sm bulk_monthly_amount" value=""  required style="color:black;">
                                        </td>
                                        <input type="hidden" name="amtcounter[]">
					</td></body>
					</tr>';
                }
		return $html;
	}
	/*end*/

       /*Karthigaa Purpose for Budget Amount Save*/
    public function budamtsave(){
           $lineid=$_POST['bulk_budget_amount_id'];
           $lineid=$_POST['bulk_budget_line_id'];
           $budgtamt= $_POST['bulk_monthly_amount'];

            $oldid=\DB::table('f_budget_amount_t')->where('budget_line_id',$lineid)->get();
             if($oldid->isEmpty())
            {
            foreach($budgtamt as $key=>$value){
                                $datas['budget_line_id']=$_POST['bulk_budget_line_id'][$key];
				$datas['account_period']=$_POST['bulk_account_period'][$key];
				$datas['monthly_amount']=$_POST['bulk_monthly_amount'][$key];
				\DB::table('f_budget_amount_t')->insert($datas);

            }
              return response()->json(array('status' => 'success', 'message' => 'Budget Amount Saved'));
            }
            else {
//                 dd($oldid);
                foreach($budgtamt as $key=>$value){
                               $budamtid= $datas['budget_amount_id']=$_POST['bulk_budget_amount_id'][$key];
                                $budlineid=$datas['budget_line_id']=$_POST['bulk_budget_line_id'][$key];
				$datas['account_period']=$_POST['bulk_account_period'][$key];
				$mnthamt=$datas['monthly_amount']=$_POST['bulk_monthly_amount'][$key];
                               \DB::update("UPDATE f_budget_amount_t SET monthly_amount = '$mnthamt' WHERE budget_amount_id='$budamtid'");
            }
              return response()->json(array('status' => 'success', 'message' => 'Budget Amount Updated Successfully'));
            }
        }
   /*end*/
/*Karthigaa Purpose for Tree Structure*/
  public function createTreeView($parent, $menu) {

        $html = '';
//        dd($menu['items']);
        $html .= '<a href="javascript:void(0);" class="list-group-item detail_btn" data-id="$id" data-value="0" data-toggle="collapse">'."
        <i class='fa fa-chevron'></i><span class='badge'></span>". $menu['parents'][$parent][0]->line_account1 . "(" . $menu['parents'][$parent][0]->budget_amount . ")</a>";

       $html.='<div class="list-group collapse">';
      foreach ($menu['parents'][$parent] as $itemId) {

     if (!isset($menu['items'][$itemId->budget_line_id])) {

      $html .="<a href='javascript:void(0);'' class='list-group-item detail_btn' data-id='$itemId->budget_hdr_id' data-value='$itemId->budget_line_id'><span class='badge'></span>"  . $itemId->line_account1 . "(" . $itemId->budget_amount . ")</a>";

      }
  if (isset($menu['items'][$itemId->budget_line_id])) {
     //$id = $menu['items'][$itemId->budget_line_id]->budget_hdr_id;

    $id = $menu['items'][$itemId->budget_line_id]->budget_hdr_id;
    $html .= $this->createTreeView($id, $menu);
  }
        }
        $html .= "</div>";
        return $html;
    }
  //----------------------------------------------------------------
public function budgetdetails($id=null,$childid=null){
     $data['sub'] = \DB::select("SELECT * FROM `f_budget_lines_t`  WHERE budget_hdr_id='$id' and budget_line_id='$childid'");
     $data['query']=$query1 = \DB::select("SELECT * FROM `f_budget_hdr_t`  WHERE budget_hdr_id='$id'");
     $from_date=$query1[0]->budget_from_date;
     $to_date=$query1[0]->budget_to_date;

     $data['query2']=$acc=\DB::select("SELECT f_account_periods_t.account_period_id,f_account_periods_t.month FROM `f_account_periods_t` WHERE to_date BETWEEN '".date('Y-m-d',strtotime($from_date))."  ' and '".date('Y-m-d',strtotime($to_date))."'");

     $html='';
     $html='<option value="">--Please Select--</option><option value=""></option>';
     foreach($acc as $key=>$value){

         $html .='<option value="'.$value->account_period_id.'">'.$value->month.'</option>';
     }
    $data['html']=$html;
     return  $data;
        }


     public function budgetlines($childid=null){

         $data['sub']=$lineid = \DB::select("SELECT * FROM `f_budget_lines_t`  WHERE budget_line_id='$childid'");
         $id= $lineid[0]->budget_hdr_id;
         $data['query']=$query1 = \DB::select("SELECT * FROM `f_budget_hdr_t`  WHERE budget_hdr_id='$id'");
         $from_date=$query1[0]->budget_from_date;
         $to_date=$query1[0]->budget_to_date;

      $data['query2']=$acc=\DB::select("SELECT f_account_periods_t.account_period_id,f_account_periods_t.month FROM `f_account_periods_t` WHERE to_date BETWEEN '".date('Y-m-d',strtotime($from_date))."  ' and '".date('Y-m-d',strtotime($to_date))."'");

      $html='';
     $html='<option value="">--Please Select--</option><option value=""></option>';
     foreach($acc as $key=>$value){

         $html .='<option value="'.$value->account_period_id.'">'.$value->month.'</option>';
     }
    $data['html']=$html;
     return  $data;
  }

}
