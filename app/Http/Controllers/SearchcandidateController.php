<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\searchcandidate;
use Illuminate\Http\Request;

class SearchcandidateController extends Controller
{
 public function __construct()
	{
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
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

        return view('searchcandidate.approveleave',$this->data);
    }

   /** Jqgrid Search candidate Data Load Start **/
	public function searchcandidategrid(Request $request)
	{
       $comp=\Session::get('companyid');

	$wh='';
       
        
        $wh .="and approve_status='3'";
        
		
	$SQL = "SELECT m_department_lines_t.sub_department_name,
    m_job_title.job_title_name,
    hr_job_description.reqired_skills,
    hr_job_description.description_name,
    hr_job_description.description_id,
    year.year_name,
    month.month_name,
    hr_job_description.min_salary,
    hr_job_description.max_salary,
    hr_job_description.min_experience,
    hr_job_description.max_experience
    from hr_job_description 
    LEFT JOIN m_department_lines_t ON m_department_lines_t.department_line_id = hr_job_description.department
	LEFT JOIN year ON year.id = hr_job_description.year
	LEFT JOIN month ON month.id = hr_job_description.month
    LEFT JOIN m_job_title ON m_job_title.job_title_id = hr_job_description.job_title where 1=1 and hr_job_description.company_id='$comp'  $wh ";
	
		
	$result = \DB::select($SQL);

    return DataTables::of($result)->make(true);

}


}
