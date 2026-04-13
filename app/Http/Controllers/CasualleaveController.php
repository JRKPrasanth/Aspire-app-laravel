<?php

namespace App\Http\Controllers;

use App\Casualleave;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\View;

class CasualleaveController extends Controller
{
    public function __construct()
	{
            $this->data=array();
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs();
	}
        /** CASUAL LEAVE INDEX page open function start **/
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

       return view('casualleave.form',$this->data);
    }
  /** CASUAL LEAVE INDEX page open function end **/
  
      /** jqgrid load data function start **/
    public function getcasualleave(Casualleave $casualleave)
    {
        $wh='';
        $comp=\Session('companyid');
		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
        $search_tables=["m_company_t","year"];
    if($_GET['_search']=='true'){
         $wh=$this->jqgridsearch("hr_casual_leave_t",$_GET['filters'],$search_tables);
       }       
	if(!$sidx) $sidx =1;
        
        
    
	$result = \DB::select("SELECT COUNT(hr_casual_leave_t.casual_leave_id) AS count FROM  hr_casual_leave_t LEFT JOIN m_company_t ON m_company_t.company_id = hr_casual_leave_t.company_id
                where 1=1  $wh");
	$count = $result[0]->count;
	if( $count > 0 && $limit > 0)
        {
            $total_pages = ceil($count/$limit);
	}
        else
        {
            $total_pages = 0;
	}

	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit;
	if($start <0) $start = 0;

        
	$SQL = "SELECT
                    hr_casual_leave_t.*,
                    hr_casual_leave_t.casual_leave,
                    hr_casual_leave_t.company_id,
                    hr_casual_leave_t.sick_leave,
                    hr_casual_leave_t.earn_leave,
                    hr_casual_leave_t.earn_leave,
                    m_company_t.company_name
                    FROM
                        hr_casual_leave_t
                    LEFT JOIN m_company_t ON m_company_t.company_id = hr_casual_leave_t.company_id
                  
                   
                    WHERE
                        1 = 1   $wh ORDER BY $sidx $sord LIMIT $start,$limit";
        
       
	$result = \DB::select($SQL);
	$responce->rows[]='';
	$responce->rows=$result;
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;

      

	echo json_encode($responce);
    }
	   /** jqgrid load data function end **/
	   /** check dulpicate based on company and year function start **/
    public function getCheckyear(Request $request)
    {
        $edit_id = $_GET['edit_id'];
        $year_id = $_GET['year'];
        $company_id = $_GET['company_id'];
        $employee_type = $_GET['employee_type'];
        
        if($edit_id == "")
        {
            $year_check=DB::table('hr_casual_leave_t')->where('employee_type',$employee_type)->where('year',$year_id)->where('company_id',$company_id)->get();
        }
        else
        {              
            $whereData = [['employee_type', $employee_type],['year', $year_id],['company_id',$company_id],['casual_leave_id', '!=', $edit_id]];
            $year_check=DB::table('hr_casual_leave_t')->where($whereData)->get();
        }
       
        if(count($year_check)>0)
            return 1;
        else
            return 0;
    }
      /** check dulpicate based on company and year function end **/
      /** save function start **/
    public function save(Request $request)
    {
	
        $edit_id = $request->input('edit_id');
        if($edit_id == '')
        {
            $casualleave = new Casualleave();
            $casualleave->company_id = $request->input('company_id');
            $casualleave->year = $request->input('year');
            $casualleave->casual_leave = $data['c_l'] = $request->input('casual_leave');
            $casualleave->sick_leave = $data['s_l'] =$request->input('sick_leave');
            $casualleave->earn_leave = $e_l = $request->input('earn_leave');
            $casualleave->employee_type  = $request->input('employee_type');
            $casualleave->save();
            $id = $casualleave->casual_leave_id;
            $table = $casualleave->getTable();
            $column = $casualleave->getKeyName();
            $this->hrmssaveinsert($table,$column,$id,1);
             // auditlog
              $this->auditlog($id,"casualleave","create",$_POST,"hr_casual_leave_t");
              /** update cl,sl,el in employee based on  company and employee type **/
            $query = DB::table('hr_employee_t')->where('company_id',$request->input('company_id'))->where('employee_type',$request->input('employee_type'))->get(); 
           
            if(count($query)>0)
            {
                foreach($query as $key => $value)
                {
					$e_add=$value->e_l+$e_l;
					$data['e_l'] =$e_add;
                     $update = DB::table('hr_employee_t')->where('employee_id',$value->employee_id)->update($data);    
                } 
            }
            return 1;
        }
        else
        {
                $casual_leave  = Casualleave::findOrFail($edit_id);
                $input = $request->all();
                $casual_leave->fill($input)->save();
                 $table = $casual_leave->getTable();
			$column = $casual_leave->getKeyName();
			$this->hrmssaveinsert($table,$column,$edit_id,2);
                        // auditlog
              $this->auditlog($edit_id,"casualleave","edit",$_POST,"hr_casual_leave_t");
                 /** update cl,sl,el in employee based on  company and employee type **/
		$e_l = $request->input('earn_leave');
		$query = DB::table('hr_employee_t')->where('company_id',$request->input('company_id'))->where('employee_type',$request->input('employee_type'))->get(); 
           
                if(count($query)>0)
                {
                    foreach($query as $key => $value)
                    {
                        $e_add=$value->e_l+$e_l;
                        $data['e_l'] =$e_add;
                        $update = DB::table('hr_employee_t')->where('employee_id',$value->employee_id)->update($data);    
                    } 
                }
                return 2;
        }
    }
	  /** save function end **/
  /** delete function start **/

    public function destroy(Casualleave $casualleave)
    {
        $del_id = $_GET['del_id'];
	
            $query = DB::table('hr_casual_leave_t')->where('casual_leave_id',$del_id)->delete();
               // auditlog
              $this->auditlog($del_id,"casualleave","delete",$_POST,"hr_casual_leave_t");
            return 0;
    }
	
	  /** delete function start **/
}
