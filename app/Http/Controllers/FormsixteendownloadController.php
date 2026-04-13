<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class FormsixteendownloadController extends Controller
{

  public function __construct() {
        $this->data = array();
      
        $this->pageModule = "formsixteendownload";
        $this->data['pageModule'] = $this->pageModule;
        $this->data['pageMethod'] = \Request::route()->getName();
        $this->data['pageFormtype'] = 'ajax';
        $this->data = array(
            'pageModule' => 'formsixteenupld',
            'pageUrl' => url($this->data['pageMethod']),
            'pageMethod' => $this->data['pageMethod']
        );
        $this->data['pageFormtype'] = 'ajax';
          $this->data['urlmenu']=$this->indexs(); 
    }
    
 public function index()
    {
       $table = \DB::table('f_formsixteen_upload_t')->get();
       $this->data['datas'] = $table;
       return view('formsixteendwnlod.table',$this->data);
    }

    
   // FORM 16 GRID PURPOSE 
    public function getFormsixteendnlodData(Request $request) {

        $wh='';

        $emp_id=Session::get('emp_id');
        
         $wh = " and (hr_employee_t.employee_id='$emp_id' or hr_employee_t.reporting_manager='$emp_id')";
         
         $SQL = "select * from (SELECT
    f_formsixteen_upload_t.formsixteenupld_hdr_id, 
    hr_employee_t.employee_id,    
    hr_employee_t.employee_number,
    hr_employee_t.first_name,
    hr_employee_t.active,
    emp_type_t.lookup_code AS emp_type_name,
    m_position.position AS position_name,
    m_job_title.job_title_name,
    account_year.year,
    f_formsixteen_upload_t.remarks,
    f_formsixteen_upload_t.choosefile
FROM
    f_formsixteen_upload_t
    
    LEFT JOIN hr_employee_t ON
    (
        hr_employee_t.employee_id = f_formsixteen_upload_t.emp_id 
    )
LEFT JOIN m_position ON
    (
        m_position.position_id = hr_employee_t.position
    )
LEFT JOIN m_job_title ON
    (
        m_job_title.job_title_id = hr_employee_t.job_title
    )
LEFT JOIN a_lookuplines_t AS emp_type_t
ON
    (
        emp_type_t.lookuplines_id = hr_employee_t.employee_type
    )

    LEFT JOIN account_year ON
    (
        account_year.id = f_formsixteen_upload_t.acc_year
    )
WHERE
    hr_employee_t.active = 'Yes' $wh)v1";
  
        $result = \DB::select($SQL);
        
        foreach($result as $k => $v) {
            if(!empty($v->choosefile)) {
                $file = $v->choosefile;
                $file_id = $v->formsixteenupld_hdr_id;            
                $dw = url("uploads/form16/$file_id/$file");
				
                $result[$k]->choosefile = $dw;
            } else {
                $result[$k]->choosefile = "";
            }
         }
        //  dd($result);
		  return DataTables::of($result)->make(true);
        
    }
   
	
    
}