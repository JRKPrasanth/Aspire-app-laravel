<?php

namespace App\Http\Controllers;

use App\resumecollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use DB;

class ResumecollectionController extends Controller
{  
 
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

        $this->data['job_desc'] = 0;
        $comp=\Session::get('companyid');
        $this->data['resume_list'] = resumecollection::where('company_id', $comp)->get();
        $this->data['urlmenu']=$this->indexs();
        $this->data['pageMethod']=\Request::route()->getName();
        return view('resumecollection.table',$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    
    public function create()
    {
        
        $employee_resume     = Schema::getColumnListing('add_resume');
        $this->data['row']= (object)array();
        foreach($employee_resume as $key=>$value)
        {
            $this->data['row']->{$value}= '';
        }
               $this->data['urlmenu']=$this->indexs();
        $this->data['pageMethod']=\Request::route()->getName();
        
        return view('resumecollection.form',$this->data);
       
    }
    /** Resume Upload Validation Start **/
    public function chkfile(Request $request)
    {
      $path =  $request->file('upload_file');
      $ext = pathinfo($path,PATHINFO_EXTENSION);
      $extension = $path->getClientOriginalExtension();
      if(($extension == "pdf") || ($extension  == "docx")){
        return 1; }

        else{
            return response()->json(array('status'=>'error','message'=>'Please Choosean Valid Pdf or Word file.'));        
        }
    }
    /** Resume Upload Validation End **/
    
    /** Resume Collection Save Start **/
    public function store(Request $request)
    {
      $edit_id = $request->input('edit_id');
        if($edit_id != "")
        {
            $resumecollection  = resumecollection::findOrFail($edit_id); 
            $file_upload = $request->file('upload_file');
            if($file_upload != "")
            {
                $old_file = $resumecollection->resume_upload;
                
                $name = uniqid().'.'.$file_upload->getClientOriginalExtension();
                $destinationPath = public_path('/resumeupload');
                $file_upload->move($destinationPath, $name);
                $resumecollection->resume_upload = $name;
                
                $myPublicFolder = public_path();
                $old_file = public_path().'/resumeupload/'.$old_file;
                unlink($old_file);
            }
            $input_data = $request->all();
            $resumecollection->fill($input_data)->save();
            $table = $resumecollection->getTable();
            $column = $resumecollection->getKeyName();
            $id = $resumecollection->resume_id;
            $this->hrmssaveinsert($table,$column,$edit_id,2);
            // auditlogresume_id
            $this->auditlog($id,"Resume Collection","create",$_POST,"add_resume");
            return 2; 
        }
        else
        {
            $resumecollection = new resumecollection();
            $resumecollection->name_of_the_candidate = $request->input('name_of_the_candidate');
            $resumecollection->gender = $request->input('gender');
            $resumecollection->email = $request->input('email');  
            $resumecollection->mobile_no = $request->input('mobile_no');  
            $resumecollection->marital_status = $request->input('marital_status');  
            $resumecollection->qualificaition = $request->input('qualificaition');  
            $resumecollection->skills = $request->input('skills');  
            $resumecollection->exp_level = $request->input('exp_level');  
            $resumecollection->location = $request->input('location');  
            $resumecollection->expected_salary = $request->input('expected_salary');  
            $resumecollection->address = $request->input('address');
            $resumecollection->years_of_experience = $request->input('years_of_experience');
            $resumecollection->current_company = $request->input('current_company');
            $resumecollection->current_company_exp = $request->input('current_company_exp');
            $resumecollection->current_position = $request->input('current_position');
            $resumecollection->current_salary = $request->input('current_salary');
            $resumecollection->notice_period = $request->input('notice_period');
             $resumecollection->city = $request->input('city');
            $resumecollection->select_status = 0;  
            $resumecollection->schedule_status = 0;  
            
            $image = $request->file('upload_file');
            if($image != "")
            {
                $name = uniqid().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/resumeupload');
                $image->move($destinationPath, $name);
                $resumecollection->resume_upload = $name;
            }
            $result = $resumecollection->save($request->all());
            $id = $resumecollection->resume_id;
			$table = $resumecollection->getTable();
			$column = $resumecollection->getKeyName();
            $this->hrmssaveinsert($table,$column,$id,1);
            // auditlog
            $this->auditlog($id,"Resume Collection","Create",$resumecollection,"add_resume");
            return 1;
            
        }
    }
    /** Resume Collection Save End **/


    public function show(resumecollection $resumecollection)
    {
        //
    }

    /** Resume Collection Edit Start **/
    public function edit(resumecollection $resumecollection,$id=null)
    {
        $edit_id = $id;
        if($edit_id != "")
        {
            $resumecollection = DB::table('add_resume')->where('resume_id',$edit_id)->where('resume_id',$edit_id)->get();
            $this->data['row']= (object)array();
            
            foreach($resumecollection[0] as $key=>$value){
                $this->data['row']->{$key}=$value;
            }
            // auditlog
            $this->auditlog($edit_id,"Resume Collection","Update",$resumecollection,"add_resume");

            return view('resumecollection.form',$this->data);
        }
    }
    /** Resume Collection Edit End **/

    public function destroy(resumecollection $resumecollection)
    {
        //
    }
    
    /** Resume Search Start **/
    public function resumesearch($first_key=null,$second_key=null,$third_key=null)
    {
        $this->data['pageMethod']=\Request::route()->getName();
            $search_key = [];
            $wh='';
		if(isset($_GET['search'])){
            $this->data['pageMethod']="searchcandidate";
			$this->data['job_desc']=$_GET['search'];
			if($first_key=='0'){
		}else{
				$wh.=" and ( skills  LIKE '%".$first_key."%')";
		}
			if($second_key!=0 && $second_key!=0)
				$wh.=" and years_of_experience <= ".$second_key." and years_of_experience  >= ".$third_key;
			if($third_key=="0"){
					$wh.=" and years_of_experience IS NULL";
			}
			 if($third_key=='Fresher'){
		
			$wh.=" and years_of_experience IS NULL";
		}
		}
		else{
			$this->data['job_desc']='0';
		if($first_key=='0'){
		
		}
			else{
			
			$wh.=" and (name_of_the_candidate LIKE '%".$first_key."%' or email='".$first_key."' or mobile_no='".$first_key."' or gender='".$first_key."' or qualificaition ='".$first_key."' or location ='".$first_key."' or marital_status ='".$first_key."' or current_salary ='".$first_key."' or expected_salary ='".$first_key."' or skills  LIKE '%".$first_key."%')";
		}
		if($second_key=='0'){
			
		}else{
			$wh.=" and location LIKE '%".$second_key."%'";
		}
		
		if($third_key=='0'){
			
		}else if($third_key=='Fresher'){
		
			$wh.=" and years_of_experience IS NULL";
		}else{
			
			$t_k=explode('>',$third_key);
		if(count($t_k)==2){
			$wh.=" and years_of_experience >= ".$t_k[1].'';
		}else{
			$wh.=" and years_of_experience = ".$t_k[0].'';
		}
		}	
			
		}
		//dd($wh);
		
            $search_result = \DB::select("select * from add_resume where 1=1 ".$wh);
                   
	
            
            $search = '';
            //$search = isset($first_key) && !empty($first_key) ? " name_of_the_candidate like '%$first_key%' "; : '';
           // dd($search_result);
            $this->data['resume_list'] = $search_result;
            //dd($this->data);
            return view('resumecollection.table',$this->data);
            
    }
    /** Resume Search End **/
}
