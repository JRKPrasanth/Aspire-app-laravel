<?php
namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Employeeupload;
use App\Employeeuploadupdate;
use App\Employeecreate;
use DB,Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Request;
use Validator,Input;

class EmployeeuploadController extends Controller
{
    public function __construct()
    {
        $this->data=array();
        $this->model=new Employeeupload();
        $this->model=new Employeecreate();
        $this->data['urlmenu']=$this->indexs(); 
		$this->data['pageMethod']=\Request::route()->getName();
    }
    // index page to load
    public function index()
    {
        
        $table = Employeeupload::select("hr_employee_int_t.*",DB::raw("CONCAT(hr_employee_int_t.first_name,' ',hr_employee_int_t.last_name) as full_name"))->get();       
        $this->data['batch_no'] = $this->jCombologin('hr_emp_upload_details_t','batch_no','batch_no','');
        $this->data['datas'] = $table;       
        return view('employeeupload.form',$this->data);
    }
      
    // upload grid data
	
    public function getEmployeeuploaddata(Request $request)
    {
		
        $wh='';
		
		$batchname = $request->batchname ?? null;
            

        if($batchname !=""){
			
        $wh= " and batch_name like '$batchname' ";   
			
        }



        $SQL = "SELECT * from hr_employee_int_t where 1=1 $wh ORDER BY hr_employee_id  ASC";
		
    $results = \DB::select($SQL);

            return DataTables::of($results) ->make(true);

		
    }
    
    // upload save
    public function save(Request $request)
    { 
            $path = Request::file('file_upload');
            $extension = $path->getClientOriginalExtension();
            $data =array();
            $file_path = $path->getPathName();
            $handle = fopen($file_path, "r");
            $c = 0;
            $columns_name = Schema::getColumnListing('hr_employee_int_t');      
            $array_count=count($columns_name)-8;
          
            if ($extension == "csv") 
            {
                $batch_no=$batch['batch_no']="BATCH_".date('Y-m-d')."_".date('Hi');
                $id = DB::table('hr_emp_upload_details_t')->insertGetId(['batch_no' =>$batch_no]);
                $insert['batch_no']=$batch_no;
                $list = '';
               
                while (($filesop = fgetcsv($handle, 10000, ",")) !== false) 
                {

                
                    $insert=[];
                    if($c > 1) 
                    {
                        
                        foreach($columns_name as $key=>$value)
                        {
                            if($key >=2  && $key < $array_count)
                            {
                               
                                
                                $insert['batch_no']     = $batch_no;
                                $insert['batch_status'] = "UPLOADED";
                                $index=$key-2;
                                $column_name=$value;
                                if($column_name == 'birth_date' || $column_name =='pf_date' || $column_name == 'joining_date' || $column_name == 'birth_date_other' || $column_name == 'spouse_dob' || $column_name == 'child1dob' || $column_name == 'child2dob')
                                {
                                    if($filesop[$index]!="")
                                        $insert[$column_name]= date("Y-m-d", strtotime($filesop[$index]));
                                }
                                else 
                                { 
                                    
                                    $insert[$column_name]=$filesop[$index];     
                                }
                            }
                            
                        }
                        
                        
                        $check_emp_number = $insert['emp_number'];
                        
                        $query = DB::table('hr_employee_int_t')->where('emp_number',$check_emp_number)->get();
                        $e_query = DB::table('hr_employee_t')->where('employee_number',$check_emp_number)->get();
                       
                        $num_count = count($query);
                        $num_count_e = count($e_query);
                        
                        if($num_count>0 || $num_count_e>0)
                            $count = 1;
                        else
                        {
                            // insert data from file to table
                        $id=    DB::table('hr_employee_int_t')->insertGetid($insert);
                            // auditlog
                              $this->auditlog($id,"employeeupload","create",$insert,"hr_employee_int_t");
                            $count = 0;
                        }   
                    }
                    $c++;
                }
                if($count == 0)
                {
                  return 1;
                    
                }
                else
                {
               return 3;
                    }
        } 
        else 
        {
            return 2;
        }
             
    }
    
 // view function
    public function show(Employeeupload $employeeupload, $id=null)
    {
        
        $this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("hr_employee_int_t");
    $this->data['values'] = Employeeupload::find($id);
        $this->data['source'] ="create";
    return view('employeeupload.view',$this->data);
    }
    // edit page open
    public function edit(Employeeupload $employeeupload, $id=null)
    {
       
        $this->data['details'] = Employeeupload::find($id);
        
        $this->data['edit_id']= $id;
        $this->data['source']= "create";
       
    return view('employeeupload.edit',$this->data);
    }
    // edit update
    public function update(Request $request, Employeeupload $employeeupload)
    {
        $edit_id                    = $_POST['edit_id'];
        $request->created_by        = '';
        $request->created_date      = '';
        $request->last_updated_by   = '';
        $request->last_updated_date = '';
        $request->batch_status      = 'UPLOADED';
        $request->batch_comments    = '';
        
      
        Employeeupload::find($edit_id)->update($_POST); 
       // auditlog
            $this->auditlog($edit_id,"employeeupload","update",$_POST,"hr_employee_int_t");
        $table = Employeeupload::select("hr_employee_int_t.*",DB::raw("CONCAT(hr_employee_int_t.first_name,' ',hr_employee_int_t.last_name) as full_name"))->get();       
        $this->data['batch_no'] = $this->jCombo('hr_emp_upload_details_t','batch_no','batch_no','');
        $this->data['datas'] = $table;  
    
        return redirect('employeeupload');
        
    }

   
   
    
    
    // validate
    public function getEmployeevalidate(Request $request) 
        {
            
            if (isset($_GET['batchname'])) 
            {
                if (!empty($_GET['batchname'])) 
                {
                    $filter = 'AND hr_emp_upload_details_t.batch_no = "' . $_GET['batchname'] . '"';
                    $batch = $_GET['batchname'];
                }
            }
            
            if (isset($_GET['type'])) 
            {
                $type = 'PRODUCTUPLOAD';
                $message['status'] = 'success';
                switch ($_GET['type']) 
                {
                    case 'verify':
                        $upload = $this->uploadValidation($batch, $message);
                        $this->data['status'] = $upload['status'];
                        $this->data['message'] = $upload['message'];
                        return $upload;
                        break;
                    
                    case 'load':$upload = $this->LoadMaster($batch, $message);
                        $this->data['status'] = $upload['status'];
                        $this->data['message'] = $upload['message'];
                        return $upload;
                        break;
                }
            }
        }
    // validate 
    function uploadValidation($batchno, $status) 
    {
    $status['status']='success';
        $status['message'] =' ';
        $sql = "select * from hr_employee_int_t where batch_status ='UPLOADED'  and batch_no='" . $batchno . "'"; 
        $result_pr = \DB::select($sql); 
        if (!empty($result_pr)) 
        { 
            $check=0;
            
            foreach($result_pr as $key=>$value)
            {
                   $status['status'] ='success';
                   $status['message'] =' ';
                /*************************** Check for Employee Code   ******************************************/
                 if(!empty($value->emp_number))
                {
                }
                else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Employee Code Empty.. Please enter Employee Code' . ' ,';
                    
                }
                /*************************** Check for Employee Code   ******************************************/
/*************************** Check for Prefix   ******************************************/
                if(!empty($value->prefix))
                {
                    $checkvalue =$this->checkvalue($value->prefix,"prefix");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Prefix not exist' . ' ,';
                    }
                }
                else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Prefix Empty.. Please enter Prefix' . ' ,';
                    
                }
/*************************** Check for Prefix  End ******************************************/  
/*************************** Check for Employee first Name   ******************************************/
                 if(!empty($value->first_name))
                {
                }
                else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Employee first Name Empty.. Please enter Employee first Name' . ' ,';
                    
                }
/*************************** Check for Employee first Name    ******************************************/

                 /*************************** Check for Email  ******************************************/
                 if(!empty($value->email))
                {
                    if (!filter_var($value->email, FILTER_VALIDATE_EMAIL)) {
                         $status['status'] = 'error';
                    $status['message'].= 'Email Invalid..' . ' ,';
                    } 
                }
                /**else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Email Empty.. Please enter Email' . ' ,';
                    
                } **/
 /*************************** Check forEmail  ******************************************/
/*************************** Check for Company   ******************************************/
                if(!empty($value->company))
                {
                    $company_id =$this->company($value->company,"name");
                  
                    if ($company_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Company Name does not exist'.' , ' ;
                    }
                }
                else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Company Name Empty.. Please enter Company Name' . ' , ';                    
                }
/*************************** Check for Company End  ******************************************/ 

/*************************** Check for Location  ******************************************/
                if(!empty($value->location))
                {
                    $location_id =$this->location($value->location,"name");
                    if (count($location_id) <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Location Name does not exist'.' , ' ;
                    }
                }
                
                else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Location Name Empty.. Please enter Location Name' . ' , ';                    
                }
/*************************** Check for Location End ******************************************/
        
/*************************** Check for Department  ******************************************/
                if(!empty($value->department))
                {
                    $department_id =$this->department($value->department,"name");   
                    if (count($department_id) <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Department Name does not exist'.' , ' ;
                    }
                }
                
                else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Department Name Empty.. Please enter Department Name' . ' , ';                    
                }
/*************************** Check for Department End ******************************************/
        
                        
/*************************** Check for Reporting check  ******************************************/
                if(!empty($value->reporting_manger))
                {
                    $report =$this->reporting($value->reporting_manger,"name"); 
                  
                    if($report[0]->cnt <= 0)
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Reporting Manager does not exist'.' , ' ;
                    }
                }
                
             /***   else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Reporting Manager  Name Empty.. Please enter Reporting Manager Name' . ' , ';                    
                } **/
                    
/*************************** Check for Reporting check ******************************************/
    
/*************************** Check for Job Title  ******************************************/
                if(!empty($value->job_title))
                {
                    $job_title_id =$this->job_title($value->job_title,"name");  
                    if ($job_title_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Position Name does not exist'.' , ' ;
                    }
                }
                
           /**     else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Position Empty.. Please enter Position' . ' , ';                    
                } **/
/*************************** Check for Job Title End ******************************************/
        
/*************************** Check for Position  ******************************************/
                if(!empty($value->position))
                {
                    $position_id =$this->position($value->position,"name"); 
                    if ($position_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Grade Name does not exist'.' , ' ;
                    }
                }
                
              /**  else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Grade Empty.. Please enter Grade' . ' , ';                    
                } **/
/*************************** Check for Job Title End ******************************************/
                        
/*************************** Check for Joining Date  ******************************************/
                if($value->joining_date=="0000-00-00")
                {
                 $status['status'] = 'error';
                    $status['message'].= 'Joining Date Empty.. Please enter Joining Date' . ' , '; 
                }
                
/*************************** Check for Joining Date End ******************************************/
                
/*************************** Check for Employee Type   ******************************************/
                if(!empty($value->employee_type))
                {
                    $checkvalue =$this->checkvalue($value->employee_type,"employee_type");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Employee Type not exist' . ' ,';
                    }
                }
                else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Employee Type Empty.. Please enter Employee Type' . ' ,';
                    
                }
/*************************** Check for Employee Type  End ******************************************/   
/*************************** Check for Employee Number   ******************************************/
                if(!empty($value->emp_number))
                {
                   
                }
                else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Employee Number Empty.. Please enter Employee Number' . ' ,';
                    
                }
/*************************** Check for Employee Number  End ******************************************/ 
        /*************************** Check for Group  ******************************************/
                if(!empty($value->group_type))
                {
                    $group_id =$this->grouptype($value->group_type,"name"); 
                    if ($group_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Group Name does not exist'.' , ' ;
                    }
                }
                
                else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Group Empty.. Please enter Group' . ' , ';                    
                }
/*************************** Check for Group End ******************************************/
        /*************************** Check for Area  ******************************************/
                if(!empty($value->med_rep_area))
                {
                    $area_id =$this->area($value->med_rep_area,"name"); 
                    if (count($area_id) <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Area Name does not exist'.' , ' ;
                    }
                }
                
               /*** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Area Name Empty.. Please enter Area Name' . ' , ';                    
                } **/
/*************************** Check for Department End ******************************************/
    
/*************************** Check for Gender   ******************************************/
                if(!empty($value->gender))
                {
                    $checkvalue =$this->checkvalue($value->gender,"gender");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Gender not exist' . ' ,';
                    }
                }
              /**  else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Gender Empty.. Please enter Gender' . ' ,';
                    
                } **/
/*************************** Check for Employee Type  End ******************************************/
                
                
/*************************** Check for Marital Status   ******************************************/
                if(!empty($value->marital_status))
                {
                    $checkvalue =$this->checkvalue($value->marital_status,"marital_status");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Marital Status not exist' . ' ,';
                    }
                }
            /**    else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Marital Status Empty.. Please enter Marital Status' . ' ,';
                    
                } **/
/*************************** Check for Marital Status  End ******************************************/
                
/*************************** Check for Nationality   ******************************************/
                if(!empty($value->nationality))
                {
                    $checkvalue =$this->checkvalue($value->nationality,"nationality");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Nationality not exist' . ' ,';
                    }
                }
                
             /***   else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Nationality  Empty.. Please enter Nationality' . ' ,';
                    
                } ***/
/*************************** Check for Nationality  End ******************************************/
                
/*************************** Check for Mother Tongue   ******************************************/
                if(!empty($value->mother_tongue))
                {
                    $checkvalue =$this->checkvalue($value->mother_tongue,"mother_tongue");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Mother Tongue not exist' . ' ,';
                    }
                }
            /**    else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Mother Tongue Empty.. Please enter Mother Tongue' . ' ,';
                    
                } **/
/*************************** Check for Mother Tongue End ******************************************/
                
/*************************** Check for Religion   ******************************************/
                if(!empty($value->religion))
                {
                    $checkvalue =$this->checkvalue($value->religion,"religion");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Religion not exist' . ' ,';
                    }
                }
            /***    else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Religion Empty.. Please enter Religion' . ' ,';
                    
                } **/
/*************************** Check for Religion End ******************************************/
                
/*************************** Check for Blood Group   ******************************************/
                if(!empty($value->blood_group))
                {
                    $checkvalue =$this->checkvalue($value->blood_group,"blood_group");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Blood Group not exist' . ' ,';
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Blood Group Empty.. Please enter Blood Group' . ' ,';
                    
                } **/
/*************************** Check for Blood Group End ******************************************/
                
/*************************** Check for ID Type 2  ******************************************/
                if(!empty($value->id_type_2))
                {
                    $checkvalue =$this->checkvalue($value->id_type_2,"id_type_2");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'ID Type 2 not exist' . ' ,';
                    }
                }
            /***    else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'ID Type 2 Empty.. Please enter ID Type 2' . ' ,';
                    
                } **/
/*************************** Check for ID Type 2 End ******************************************/                
                
/*************************** Check for ID Type 1 ******************************************/
                if(!empty($value->id_type_1))
                {
                    $checkvalue =$this->checkvalue($value->id_type_1,"id_type_1");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'ID Type 1 not exist' . ' ,';
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'ID Type 1 Empty.. Please enter ID Type 1' . ' ,';
                    
                } **/
               
/*************************** Check for ID Type 1 End ******************************************/
                
/*************************** Check for Country  ******************************************/
                if(!empty($value->perm_country))
                {
                    $country_id =$this->country($value->perm_country,"name");   
                    if ($country_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Country Name does not exist'.' , ' ;
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Country Empty.. Please enter Country' . ' , ';                    
                } **/
/*************************** Check for Country End ******************************************/
                
/*************************** Check for State  ******************************************/
                if(!empty($value->perm_state))
                {
                    $state_id =$this->state($value->perm_state,"name"); 
                    if ($state_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'State Name does not exist'.' , ' ;
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'State Empty.. Please enter State' . ' , ';                    
                } **/
/*************************** Check for State End ******************************************/
                
/*************************** Check for City  ******************************************/
                if(!empty($value->perm_city))
                {
                    $city_id =$this->city($value->perm_city,"name");    
                    if ($city_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'City Name does not exist'.' , ' ;
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'City Empty.. Please enter City' . ' , ';                    
                } **/
/*************************** Check for City End ******************************************/             
                
/*************************** Check for Country  ******************************************/
                        if(!empty($value->curr_country))
                        {
                            $country_id =$this->country($value->curr_country,"name");   
                            if ($country_id[0]->cnt <= 0) 
                            {
                                $status['status'] = 'error';
                                $status['message'].= 'Same Address Country Name does not exist'.' , ' ;
                            }
                        }
                     /***   else
                        {
                                $status['status'] = 'error';
                                $status['message'].= 'Same Address Country Empty.. Please enter Country' . ' , ';                    
                        } **/
/*************************** Check for Country End ******************************************/
                
/*************************** Check for State  ******************************************/
                if(!empty($value->curr_state))
                {
                    $state_id =$this->state($value->curr_state,"name"); 
                    if ($state_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Same Address State Name does not exist'.' , ' ;
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Same Address State Empty.. Please enter State' . ' , ';                    
                } **/
/*************************** Check for State End ******************************************/
                
/*************************** Check for City  ******************************************/
                if(!empty($value->curr_city))
                {
                    $city_id =$this->city($value->curr_city,"name");    
                    if ($city_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Same Address City Name does not exist'.' , ' ;
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Same Address City Empty.. Please enter City' . ' , ';                    
                } **/
/*************************** Check for City End ******************************************/             


/*************************** Check for Account Type   ******************************************/
                if(!empty($value->account_type))
                {
                    $checkvalue =$this->checkvalue($value->account_type,"account_type");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Account Type not exist' . ' ,';
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Account Type Empty.. Please enter Account Type' . ' ,';
                    
                } **/
/*************************** Check for Account Type  End ******************************************/
        
                
/*************************** Check for Education Level   ******************************************/
                if(!empty($value->education_level1))
                {
                    $checkvalue =$this->checkvalue($value->education_level1,"education_level");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Education Level not exist' . ' ,';
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Education Level Empty.. Please enter Education Level' . ' ,';
                    
                } **/
/*************************** Check for Education Level  End ******************************************/
                
/*************************** Check for Competency Level   ******************************************/
                if(!empty($value->competency1))
                {
                    $checkvalue =$this->checkvalue($value->competency1,"competency_level");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Competency Level not exist' . ' ,';
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Competency Level Empty.. Please enter Competency Level' . ' ,';
                    
                } **/
/*************************** Check for Competency Level  End ******************************************/

        
                $status['message']=rtrim($status['message'],',');
                              
                if($status['status'] == "error") 
                {
                                    $check=1;
                    $sql = "update hr_employee_int_t set batch_status ='ERROR' , batch_comments='" . $status['message'] . "\n'  WHERE hr_employee_id='" . $value->hr_employee_id . "' ";
                    $result = \DB::update($sql);
                    $status['message']='Employee data have some error';  
                } 
                else
                {
                    $sql = "update hr_employee_int_t set batch_status ='VALIDATED' , batch_comments='' where hr_employee_id='" . $value->hr_employee_id . "' ";
                    $result = \DB::update($sql);
                    $status['status']='success';
                    $status['message']='Employee Data validated successfully';
                }
                
          
        }
        if($check==0){
         return $status;
        }
        else{
            $status['status']='error';
        $status['message'] ='Employee data have some error';
            return $status;
        }
    }
       $status['status']='error';
        $status['message'] ='NO DATA TO VALIDATE';
         return $status;
    }
    //loda in employee table
    public function LoadMaster($loadModname, $status){
        //$comnfun = new ProductuploadController;
        $status['status'] = '';
        $status['message'] = '';
        $sql = "select * from hr_employee_int_t  where  batch_status ='VALIDATED'  and batch_no='" . $loadModname . "'"; 
        $result = \DB::select($sql);
        
        $data=array();
        $loadid=array();
         
        if(count($result)>0)
        { 
            foreach($result  as $key=>$value)
            {
                    
/***************** Load Employee Officia Details ************************************/
                $employeecreate = new Employeecreate(); 
                $employeecreate->employee_number =  $value->emp_number;
                $employeecreate->first_name      = $value->first_name;
                $employeecreate->last_name       = $value->last_name;
                $employeecreate->email           = $value->email;
        $company = $this->company($value->company,"name");
                $employeecreate->company_id      = $company[0]->company_id;
                $location = $this->location($value->location,"name");
                $array_id=[];
                foreach($location as $k=>$v){
                    $array_id[$k]=(string) $v->location_id;
                }
                $employeecreate->location_id     = json_encode($array_id);
                $department = $this->department($value->department,"name");
                  $array_id=[];
                foreach($department as $k=>$v){
                    $array_id[$k]=(string) $v->department_line_id;
                }
                $employeecreate->department      = json_encode($array_id);
                $med_rep_area = $this->area($value->med_rep_area,"name");
                $array_id=[];
              foreach($med_rep_area as $k=>$v){
                  $array_id[$k]=(string)$v->area_id;
              }
                $employeecreate->med_rep_area      = json_encode($array_id);
                $reporting=$this->reporting($value->reporting_manger,"name");
                $employeecreate->reporting_manager =$reporting[0]->employee_id; 
        $job_title = $this->job_title($value->job_title,"name");
                $employeecreate->job_title       = $job_title[0]->job_title_id;
        $position = $this->position($value->position,"name");
                $employeecreate->position        = $position[0]->position_id;
                $employeecreate->date_of_joining = $value->joining_date;
                $employeecreate->esi_no          = $value->esi_number;
                $employeecreate->esi_dispensary  = $value->esi_dispensary;
                $employeecreate->pf_no           = $value->pf_number;
                $employeecreate->pf_date          =$value->pf_date;
                $employeecreate->uan_no          =$value->uan_no;
                $employeecreate->alternative_telephone_number  =$value->alternative_telephone_number;
                $employeecreate->biometric_empno          =$value->biometric_empno;
                $employeecreate->c_l          =$value->c_l;
                $employeecreate->s_l          =$value->s_l;
                $employeecreate->e_l          =$value->e_l;
                $employeecreate->work_telephone_number   = $value->mobile_number;
        $employee_type = $this->checkvalue($value->employee_type,'employee_type');
                $employeecreate->employee_type   = $employee_type['val'];
        $prefix = $this->checkvalue($value->prefix,'prefix');
                $employeecreate->prefix   = $prefix['val'];
                $employeecreate->updated_at      =  "";
                $employeecreate->created_at      =  "";
               $group_type = $this->grouptype($value->group_type,"name");
                $employeecreate->group_type=$group_type       = $group_type[0]->group_id;
                $employeecreate->active="Yes";
                
                $employee_count =  Employeecreate::orderBy('employee_count', 'DESC')->where('company_id',$employeecreate->company_id)->get();
                if(count($employee_count)>0)
                    $count = $employee_count[0]->employee_count+1;
                else
                    $count = 1;    
              
                $employeecreate->employee_count = $count; 
                
                $employeecreate->save(); 
                $name = $employeecreate->getKeyName();
                $id = $employeecreate->$name; 
                $table = $employeecreate->getTable();
                $column = $employeecreate->getKeyName();
                $this->hrmssaveinsert($table,$column,$id,1);
                $insert_id = $employeecreate->employee_id;  
            // auditlog
             $this->auditlog($insert_id,"employeeupload","insert",$value,"hr_employee_t");
                    /*** user table ****/
                $user_details = array('group_id' =>$group_type,'employee_number'=>$employeecreate->employee_number,'username' =>$employeecreate->employee_number,'password' => bcrypt('welcome123'),'email' => $employeecreate->email,'active'=>'Yes','admindept_id'=>$employeecreate->department,'loc_id'=>$employeecreate->location_id,'first_name' => $employeecreate->first_name,'mobile_no'=>$employeecreate->work_telephone_number,'last_name' => $employeecreate->last_name,'company_id'=>$employeecreate->company_id,'employee_id' => $insert_id);
                $tb_user = DB::table('tb_users')->insertGetId($user_details);
                // auditlog
              $this->auditlog($tb_user,"employeeupload","insert",$user_details,"tb_users");
                /*****  user table End *****/
               
                /** user access group table **/
                $group = DB::table('a_group_menu_access_t')->where('group_id',$group_type)->get();
               
                $group_data['user_id'] = $insert_id;
                $group_data['group_id'] = $group_type;
                if(count($group)>0)
                {
                    $group_data['menus'] = $group[0]->menus;
                    $group_data['permission'] = $group[0]->permission;
                }
                else
                {
                    $group_data['menus'] = '';
                    $group_data['permission'] = '';
                }
               
                
                $user_access = DB::table('a_user_access_t')->insertGetId($group_data);   
                 // auditlog
              $this->auditlog($user_access,"employeeupload","insert",$group_data,"a_user_access_t");
                /** end **/
                
              
                
 /***************** Load Employee Officia Details ************************************/     
                $sql = \DB::table('hr_emp_personal')->where('employee_id',$insert_id)->get();   
              
                if($sql->isEmpty())
                {
                    $genderval      = $this->checkvalue($value->gender,'gender');
                    $gender             = $genderval['val'];
                    $marital_statusval  = $this->checkvalue($value->marital_status,'marital_status');
                    $marital_status     = $marital_statusval['val'];
                    $nationalityval     = $this->checkvalue($value->nationality,'nationality');
                    $nationality        = $nationalityval['val'];
                    $date_of_birth      = $value->birth_date;
                    $age                = $value->age;
            $monther_tongueval  = $this->checkvalue($value->mother_tongue,'mother_tongue');
                    $monther_tongue     = $monther_tongueval['val'];
            $religionval        = $this->checkvalue($value->religion,'religion');
                    $religion           = $religionval['val'];
            $blood_groupval     = $this->checkvalue($value->blood_group,'blood_group');
                    $blood_group        = $blood_groupval['val'];               
                    $personal_mail      = $value->email;
                    $personal_mobile    = $value->mobile_number;
                    $id_type_1      = $this->checkvalue($value->id_type_1,'id_type_1');
                    $id_name            = $id_type_1['val'];
                    $id_number          = $value->id_number_1;
                    $id_type_2      = $this->checkvalue($value->id_type_1,'id_type_2');
                    $id_name1           = $id_type_2['val'];
                    $id_number1         = $value->id_number_2;
                    $father_name        = $value->father_name;
                    $mother_name        = $value->mother_name;
                    $spouse_name        = $value->spouse_name;
                    $spouse_dob         = $value->spouse_dob;
                    $no_of_children     = $value->children_number;
                    $children_name1     = $value->child1name;
                    $children_dob1      = $value->child1dob;
                    $children_name2     = $value->child2name;
                    $children_dob2      = $value->child2dob;
                    $updated_at         =  "";
                    $created_at         =  "";
                    
                    $datas = array('employee_id'=>$insert_id,'gender'=>$gender,'marital_status'=>$marital_status,'nationality'=>$nationality,'date_of_birth'=>$date_of_birth
                    ,'age'=>$age,'mother_tongue'=>$monther_tongue,'religion'=>$religion,'blood_group'=>$blood_group,'personal_mail'=>$personal_mail,'personal_mobile'=>$personal_mobile
                    
                    ,'id_name'=>$id_name,'id_number'=>$id_number,'id_name1'=>$id_name1,'id_number1'=>$id_number1,'father_name'=>$father_name,'mother_name'=>$mother_name,
                    'spouse_name'=>$spouse_name,'spouse_dob'=>$spouse_dob,'no_of_children'=>$no_of_children,'children_name1'=>$children_name1,
                    'children_dob1'=>$children_dob1,'children_dob1'=>$children_dob1,'children_name2'=>$children_name2,'children_dob2'=>$children_dob2,'updated_at'=>$updated_at,'created_at'=>$created_at);
                    $user = DB::table('hr_emp_personal')->insertGetId($datas);   
                            // auditlog
              $this->auditlog($user,"employeeupload","insert",$datas,"hr_emp_personal");
                }
               
                /***************** Load Employee Officia Details End ************************************/  
                /***************** Load Contact Details End ************************************/ 
$sql = \DB::table('hr_emp_contact')->where('employee_id',$insert_id)->get();                
              if($sql->isEmpty())
              {               
               $data['employee_id'] = $insert_id;
               $data['permanent_street_address'] = $value->permanent_address;
               $perm_country = $this->country($value->perm_country,"name");
               $data['permanent_country']     = $perm_country[0]->country_id;
               $perm_country = $this->state($value->perm_state,"name");
               $data['permanent_state']     = $perm_country[0]->state_id;
               $perm_country = $this->city($value->perm_city,"name");
               $data['permanent_city']     = $perm_country[0]->city_id;
               $data['permanent_postal_code'] = $value->perm_pincode;               
               $data['current_street_address'] = $value->current_address;
               $curr_country = $this->country($value->curr_country,"name");
               $data['current_country']     = $curr_country[0]->country_id;
               $perm_country = $this->state($value->curr_state,"name");
               $data['current_state']     = $perm_country[0]->state_id;
               $perm_country = $this->city($value->curr_city,"name");
               $data['current_city']     = $perm_country[0]->city_id;
               $data['current_postal_code'] = $value->curr_pincode;
                               
               $emp_contact = DB::table('hr_emp_contact')->insertGetId($data);
                // auditlog
              $this->auditlog($emp_contact,"employeeupload","insert",$data,"hr_emp_contact");
              }
                        
/***************** Load Contact Details End ************************************/
                
/***************** Load Salary Details ************************************/  
                $sqlsal = \DB::table('hr_emp_salary')->where('employee_id',$insert_id)->get();              
              if($sqlsal->isEmpty())
                {
                    
                    $data1['bank_name']   = $value->bank_name;
                    $data1['branch_name'] = $value->branch_name;
                    $data1['ifsc_code']   = $value->ifsc_code;
                    $data1['account_holder_name'] = $value->acc_holder_name;
            $accounttypeval = $this->checkvalue($value->account_type,'account_type');
                    $data1['account_type']        = $accounttypeval['val'];
                    $data1['account_number']      = $value->accunt_number;
                    $data1['employee_id']      = $insert_id;
                            if( $value->bank_name!=''|| $value->branch_name!=''||  $value->ifsc_code!=''|| $value->acc_holder_name!=''||$value->account_type!='' ||$value->accunt_number!=''){
          
                    $emp_salary = DB::table('hr_emp_salary')->insertGetId($data1);
                            
                     // auditlog
              $this->auditlog($emp_salary,"employeeupload","create",$data1,"hr_emp_salary");
                            }
                    
                }
              
/***************** Load Salary Details End ************************************/

/***************** Load Education Details ************************************/
        $sqledu = \DB::table('hr_emp_education')->where('employee_id',$insert_id)->get();               
                if($sqledu->isEmpty())
                {
                    $data2['employee_id']    = $insert_id;
            $education_levelval = $this->checkvalue($value->education_level1,'education_level');
                    $data2['education_level']    = $education_levelval['val'];
                    $data2['institution_name']  = $value->institutionname1;
                    $data2['school_board']      = $value->course_board1;
                    $data2['from_date']       = $value->fromduration1;
                    $data2['to_date']         = $value->toduration1;
                    $data2['percentage']      = $value->percentage1;
                         if($value->education_level1!='' || $value->institutionname1!='' || $value->course_board1!='' || $value->fromduration1!='' || $value->toduration1!=''|| $value->percentage1!=''){
                  
                    $emp_salary = DB::table('hr_emp_education')->insertGetId($data2);
                        // auditlog
              $this->auditlog($emp_salary,"employeeupload","create",$data2,"hr_emp_education");
                         }
              if($value->institutionname2!=""){
                $data2['employee_id']    = $insert_id;
            $education_levelval = $this->checkvalue($value->education_level2,'education_level');
                    $data2['education_level']    = $education_levelval['val'];
                    $data2['institution_name']  = $value->institutionname2;
                    $data2['school_board']      = $value->course_board2;
                    $data2['from_date']       = $value->fromduration2;
                    $data2['to_date']         = $value->to_duration2;
                    $data2['percentage']      = $value->percentage2;
                    $emp_salary = DB::table('hr_emp_education')->insertGetId($data2); 
                          // auditlog
              $this->auditlog($emp_salary,"employeeupload","create",$data2,"hr_emp_education");
              }
               if($value->institution3!=""){
                $data2['employee_id']    = $insert_id;
            $education_levelval = $this->checkvalue($value->education_level3,'education_level');
                    $data2['education_level']    = $education_levelval['val'];
                    $data2['institution_name']  = $value->institution3;
                    $data2['school_board']      = $value->course_board3;
                    $data2['from_date']       = $value->from_duration3;
                    $data2['to_date']         = $value->to_duration3;
                    $data2['percentage']      = $value->percentage3;
                    $emp_salary = DB::table('hr_emp_education')->insertGetId($data2); 
                          // auditlog
              $this->auditlog($emp_salary,"employeeupload","create",$data2,"hr_emp_education");
              }
                }
                            
/***************** Load Education Details End ************************************/
                                
/***************** Load Experience Details ************************************/
                $sqlexp = \DB::table('hr_emp_experience')->where('employee_id',$insert_id)->get();              
                if($sqlexp->isEmpty())
                {
                         if($value->organization_name1!=''){
                $data3['employee_id']    = $insert_id;
                $data3['organization_name']       = $value->organization_name1;
                $data3['organization_website']    = $value->website1;
                $data3['designation']             = $value->designation1;
                $data3['ctc']                     = $value->ctc1;
                $data3['from_date']               = $value->expfrom1;
                $data3['to_date']                 = $value->expto1;
                $emp_experience = DB::table('hr_emp_experience')->insertGetId($data3);
                  // auditlog
              $this->auditlog($emp_experience,"employeeupload","create",$data3,"hr_emp_experience");
                         }
              if($value->organization2!=''){
                $data3['employee_id']    = $insert_id;
                $data3['organization_name']       = $value->organization2;
                $data3['organization_website']    = $value->website2;
                $data3['designation']             = $value->designation2;
                $data3['ctc']                     = $value->ctc2;
                $data3['from_date']               = $value->expfrom2;
                $data3['to_date']                 = $value->expto2;
                $emp_experience = DB::table('hr_emp_experience')->insertGetId($data3);
                  // auditlog
              $this->auditlog($emp_experience,"employeeupload","create",$data3,"hr_emp_experience");
              }
              if($value->organization3!=''){
                $data3['employee_id']    = $insert_id;
                $data3['organization_name']       = $value->organization3;
                $data3['organization_website']    = $value->website3;
                $data3['designation']             = $value->designation3;
                $data3['ctc']                     = $value->ctc3;
                $data3['from_date']               = $value->expfrom3;
                $data3['to_date']                 = $value->expto3;
                $emp_experience = DB::table('hr_emp_experience')->insertGetId($data3);
                  // auditlog
              $this->auditlog($emp_experience,"employeeupload","create",$data3,"hr_emp_experience");
              }
                }
                            
/***************** Load Experience Details End ************************************/
                
/***************** Load Skills Details ************************************/
                $sqlsk = \DB::table('hr_emp_skills')->where('employee_id',$insert_id)->get();               
                if($sqlsk->isEmpty())
                {
                    
                    $data4['employee_id']                = $insert_id;
                    $data4['skill']                = $value->skills1;
                    $data4['version']              = $value->version1;                  
            $competencyval = $this->checkvalue($value->competency1,'competency_level');                 
                    $data4['competency_level']     = $competencyval['val'];
                    $emp_experience = DB::table('hr_emp_skills')->insertGetId($data4);
                       // auditlog
                    $this->auditlog($emp_experience,"employeeupload","create",$data4,"hr_emp_skills");
                    if($value->skills2!=''){
                    $data4['employee_id']                = $insert_id;
                    $data4['skill']                = $value->skills2;
                    $data4['version']              = $value->version2;                  
            $competencyval = $this->checkvalue($value->competency2,'competency_level');                 
                    $data4['competency_level']     = $competencyval['val'];
                    $emp_experience = DB::table('hr_emp_skills')->insertGetId($data4);
                       // auditlog
                    $this->auditlog($emp_experience,"employeeupload","create",$data4,"hr_emp_skills");
                    }if($value->skills3!=''){
                    $data4['employee_id']                = $insert_id;
                    $data4['skill']                = $value->skills3;
                    $data4['version']              = $value->version3;                  
            $competencyval = $this->checkvalue($value->competency3,'competency_level');                 
                    $data4['competency_level']     = $competencyval['val'];
                    $emp_experience = DB::table('hr_emp_skills')->insertGetId($data4);
                       // auditlog
                    $this->auditlog($emp_experience,"employeeupload","create",$data4,"hr_emp_skills");
                    }
                    
                }
                        
/***************** Load Skills Details End ************************************/ 
            }
        $sql = "update hr_employee_int_t set batch_status ='LOADED' , batch_comments='' where hr_employee_id='" . $value->hr_employee_id . "' ";
                $result = \DB::update($sql);
                        // auditlog
                $data['batch_status']=$data_up['batch_status']='LOADED';
               
                $this->auditlog($value->hr_employee_id,"employeeupload","update",$data,"hr_employee_int_t");
                $status['status']='success';
                $status['message']='Employee Data Loaded Sucessfully';
                return $status;
                return true;
        }
        else{

            $sql=\DB::select("select * from hr_employee_int_t where batch_no='".$loadModname."'");
            if($sql[0]->batch_status=="UPLOADED"){
                 $status['status']='info';
                 $status['message']='Pls Validate the Batch First..!';
            return $status;
            }else if($sql[0]->batch_status=="ERROR"){
                $status['status']='error';
                 $status['message']='Batch Error..!';
            return $status;
            }else{
                $status['status']='info';
                 $status['message']='Employee  Data already Loaded';
            return $status;
            }
        }
    }
    
    
    
    function checkvalue($dbval,$name)
    {
        if($name =="prefix")
        {
            $checkarr = array('Mr','Ms','Mrs');
            $key = array_search($dbval, $checkarr); 
            if($key !== false)
            {
                           $result['result'] = 'y';
                           $result['val'] = $key +1;
            }
            else
            {
               $result['result'] = 'n';
               $result['val'] = 0;
            }
        }
        
        if($name =="employee_type")
        {
            $data=\DB::SELECT("select count(*)  as cnt,lookuplines_id from a_lookuplines_t where lookup_code='$dbval' and lookup_type='EMPLOYEE_TYPE' ");   
            
                        if(($data[0]->cnt)>0)
            {
                $result['result'] = 'y';
                $result['val'] = $data[0]->lookuplines_id;
            }
            else
            {
               $result['result'] = 'n';
               $result['val'] = 0;
            }
                       
        }
        if($name =="gender")
        {
            $checkarr = array('Male','Female');
            $key = array_search($dbval, $checkarr);     
            if($key !== false)
            {
                $result['result'] = 'y';
                $result['val'] = $key +1;
            }
            else
            {
               $result['result'] = 'n';
               $result['val'] = 0;
            }
        }
        if($name =="marital_status")
        {
            $checkarr = array('Single','Married');
            $key = array_search($dbval, $checkarr); 
            
            if($key !== false)
            {
                $result['result'] = 'y';
                $result['val'] = $key +1;
            }
            else
            {
               $result['result'] = 'n';
               $result['val'] = 0;
            }
        }
        if($name =="nationality")
        {
            $data=\DB::SELECT("select count(*)  as cnt,lookuplines_id from a_lookuplines_t where lookup_code='$dbval' and lookup_type='NATIONALITY' "); 
            
                        if(($data[0]->cnt)>0)
            {
                $result['result'] = 'y';
                $result['val'] =$data[0]->lookuplines_id;
            }
            else
            {
               $result['result'] = 'n';
               $result['val'] = 0;
            }
        }
        if($name =="mother_tongue")
        {
            $data=\DB::SELECT("select count(*)  as cnt,lookuplines_id from a_lookuplines_t where lookup_code='$dbval' and lookup_type='MOTHER_TONGUE' ");   
            if(($data[0]->cnt)>0)
            {
                $result['result'] = 'y';
                $result['val'] =$data[0]->lookuplines_id;
            }
            else
            {
               $result['result'] = 'n';
               $result['val'] = 0;
            }
        }
        if($name =="religion")
        {
            $data=\DB::SELECT("select count(*)  as cnt,lookuplines_id from a_lookuplines_t where lookup_code='$dbval' and lookup_type='RELIGION' ");    
            if(($data[0]->cnt)>0)
            {
                $result['result'] = 'y';
                $result['val'] = $data[0]->lookuplines_id;
            }
            else
            {
               $result['result'] = 'n';
               $result['val'] = 0;
            }
        }
        if($name =="blood_group")
        {
            $data=\DB::SELECT("select count(*)  as cnt,lookuplines_id from a_lookuplines_t where lookup_code='$dbval' and lookup_type='BLOOD_GROUP' "); 
            if(($data[0]->cnt)>0)
            {
                $result['result'] = 'y';
                $result['val'] = $data[0]->lookuplines_id;
            }
            else
            {
               $result['result'] = 'n';
               $result['val'] = 0;
            }
        }
        if($name =="id_type_2")
        {
            $data=\DB::SELECT("select count(*)  as cnt,lookuplines_id from a_lookuplines_t where lookup_code='$dbval' and lookup_type='idtype1' "); 
            if(($data[0]->cnt)>0)
            {
                $result['result'] = 'y';
                $result['val'] =$data[0]->lookuplines_id;
            }
            else
            {
               $result['result'] = 'n';
               $result['val'] = 0;
            }
        }
        if($name =="id_type_1")
        {
            $data=\DB::SELECT("select count(*)  as cnt,lookuplines_id from a_lookuplines_t where lookup_code='$dbval' and lookup_type='idtype1' "); 
            if(($data[0]->cnt)>0)
            {
                $result['result'] = 'y';
                $result['val'] =$data[0]->lookuplines_id;
            }
            else
            {
               $result['result'] = 'n';
               $result['val'] = 0;
            }
        }
        
        
        if($name =="account_type")
        {
            $data=\DB::SELECT("select count(*)  as cnt,lookuplines_id from a_lookuplines_t where lookup_code='$dbval' and lookup_type='ACCOUNT_TYPE' ");    
            if(($data[0]->cnt)>0)
            {
                $result['result'] = 'y';
                $result['val'] = $data[0]->lookuplines_id;
            }
            else
            {
               $result['result'] = 'n';
               $result['val'] = 0;
            }
        }
        if($name =="education_level")
        {
            $data=\DB::SELECT("select count(*)  as cnt,lookuplines_id from a_lookuplines_t where lookup_code='$dbval' and lookup_type='EDUCATION_LEVEL' "); 
            if(($data[0]->cnt)>0)
            {
                $result['result'] = 'y';
                $result['val'] =$data[0]->lookuplines_id;
            }
            else
            {
               $result['result'] = 'n';
               $result['val'] = 0;
            }
        }
        
        if($name =="competency_level")
        {
            $data=\DB::SELECT("select count(*)  as cnt,lookuplines_id from a_lookuplines_t where lookup_code='$dbval' and lookup_type='COMPETENCY_LEVEL' ");    
            if(($data[0]->cnt)>0)
            {
                $result['result'] = 'y';
                $result['val'] = $data[0]->lookuplines_id;
            }
            else
            {
               $result['result'] = 'n';
               $result['val'] = 0;
            }
        }
        
        
        
        
        return $result;
        
    }
    
    
    // company check exists
    public function company($value=null,$type=null){
        if($type=='name'){
            $cond=' and company_name="'.$value.'"';     
        }else {
            $cond='';
        }
        $sql=\DB::select('select count(*)  as cnt,company_id,company_name,company_code from m_company_t where 1=1 and active="Yes" '.$cond);
        return  $sql;
    }
    
     // location check exists
    public function location($value=null,$type=null){
            $value=explode(",",$value);
            $check='';
            foreach($value as $v){
            $check.="'".$v."',";  
            }
            $check=rtrim($check,',');
        if($type=='name'){
            $cond=" and location_name in (".$check.")";     
        }else {
            $cond="";
        }
        $sql=\DB::select("select location_id,location_name,location_code from m_location_t where 1=1 and active='Yes' $cond");
    
        return  $sql;
    }
     // department check exists
    public function department($value=null,$type=null)
    {
             $value=explode(",",$value);
            $check='';
            foreach($value as $v){
            $check.="'".$v."',";  
            }
            $check=rtrim($check,',');
        if($type=='name'){
            $cond=" and sub_department_name in (".$check.")";          
        }else {
            $cond="";
        }
        $sql=\DB::select("select department_line_id,sub_department_name from m_department_lines_t where 1=1 and active='Yes' $cond");
        return  $sql;
    }
     // reporting check exists
    public function reporting($value=null,$type=null)
    {
        if($type=='name'){
            $cond=" and employee_number='".$value."'";     
        }else {
            $cond="";
        }
        $sql=\DB::select("select count(*)  as cnt,employee_id,employee_number from hr_employee_t where 1=1 and active='Yes' $cond");
       
        return  $sql;
    }
         // job title check exists
    public function job_title($value=null,$type=null)
    {
        if($type=='name'){
            $cond=" and job_title_name='".$value."'";     
        }else {
            $cond="";
        }
        $sql=\DB::select("select count(*)  as cnt,job_title_id,job_title_name from m_job_title where 1=1 and active='Yes' $cond");
        return  $sql;
    }
         // position check exists
    public function position($value=null,$type=null){
        if($type=='name'){
            $cond=" and position='".$value."'";     
        }else {
            $cond="";
        }
        $sql=\DB::select("select count(*)  as cnt,position_id,position from m_position where 1=1 and active='Yes' $cond");
        return  $sql;
    }
    // group check exists
    public function grouptype($value=null,$type=null){
        if($type=='name'){
            $cond=" and group_name='".$value."'";     
        }else {
            $cond="";
        }
        $sql=\DB::select("select count(*)  as cnt,group_id,group_name from a_m_group_t where 1=1 $cond");
        return  $sql;
    }
     // department check exists
    public function area($value=null,$type=null)
    {
             $value=explode(",",$value);
            $check='';
            foreach($value as $v){
            $check.="'".$v."',";  
            }
            $check=rtrim($check,',');
        if($type=='name'){
            $cond=" and area_name in (".$check.")";          
        }else {
            $cond="";
        }
        $sql=\DB::select("select area_id,area_name from m_area_t where 1=1  $cond");
        return  $sql;
    }
    // country check exists
    public function country($value=null,$type=null){
        if($type=='name'){
            $cond=" and country_name='".$value."'";     
        }else {
            $cond="";
        }
        $sql=\DB::select("select count(*)  as cnt,country_id,country_name from m_countries_t where 1=1 $cond");
        return  $sql;
    }
    //state check exists
    public function state($value=null,$type=null){
        if($type=='name'){
            $cond=" and state_name='".$value."'";     
        }else {
            $cond="";
        }
        $sql=\DB::select("select count(*)  as cnt,state_id,state_name from m_states_t where 1=1 $cond");
        return  $sql;
    }
    // city check exists
    public function city($value=null,$type=null){
        if($type=='name'){
            $cond=" and city_name='".$value."'";     
        }else {
            $cond="";
        }
        $sql=\DB::select("select count(*)  as cnt,city_id,city_name from  m_cities_t where 1=1 $cond");
        return  $sql;
    }
    
        // delete function
    public function employeeuploaddelete($del_id=null)
    {
       
       
      $data= DB::table('hr_employee_int_t')->where('hr_employee_id',$del_id)->get();
            $query = DB::table('hr_employee_int_t')->where('hr_employee_id',$del_id)->delete();
              // auditlog
              $this->auditlog($del_id,"employeeupload","delete",$data[0],"hr_employee_int_t");
                  return 0;
    }
      // update index page to load
     public function indexupdate()
    {
        $table = Employeeuploadupdate::select("hr_employee_int_update_t.*",DB::raw("CONCAT(hr_employee_int_update_t.first_name,' ',hr_employee_int_update_t.last_name) as full_name"))->get();       
        $this->data['batch_no'] = $this->jCombologin('hr_emp_upload_details_t','batch_no','batch_no','');
        $this->data['datas'] = $table;       
        return view('employeeupload.formupdate',$this->data);
    }
    //update index data
        public function getEmployeeupdateuploaddata(Request $request)
    {
		
        $wh='';
		
		$batchname = $request->batchname ?? null;
            

        if($batchname !=""){
			
        $wh= " and batch_name like '$batchname' ";   
			
        }



        $SQL = "SELECT * from hr_employee_int_update_t where 1=1 $wh ORDER BY hr_employee_id  ASC";
		
    $results = \DB::select($SQL);

            return DataTables::of($results) ->make(true);

		
    }
	
      // upload update save
    public function updatesave(Request $request)
    { 
            $path = Request::file('file_upload');
            $extension = $path->getClientOriginalExtension();
            $data =array();
            $file_path = $path->getPathName();
            $handle = fopen($file_path, "r");
            $c = 0;
            $columns_name = Schema::getColumnListing('hr_employee_int_update_t');       
            $array_count=count($columns_name)-8;
          
            if ($extension == "csv") 
            {
                $batch_no=$batch['batch_no']="BATCH_".date('Y-m-d')."_".date('Hi');
                $id = DB::table('hr_emp_upload_details_t')->insertGetId(['batch_no' =>$batch_no]);
                $insert['batch_no']=$batch_no;
                $list = '';
               
                while (($filesop = fgetcsv($handle, 10000, ",")) !== false) 
                {

                
                    $insert=[];
                    if($c > 1) 
                    {
                        
                        foreach($columns_name as $key=>$value)
                        {
                            if($key >=2  && $key < $array_count)
                            {
                               
                                
                                $insert['batch_no']     = $batch_no;
                                $insert['batch_status'] = "UPLOADED";
                                $index=$key-2;
                                $column_name=$value;
                                if($column_name == 'birth_date' || $column_name =='pf_date' || $column_name == 'joining_date' || $column_name == 'birth_date_other' || $column_name == 'spouse_dob' || $column_name == 'child1dob' || $column_name == 'child2dob')
                                {
                                    if($filesop[$index]!="")
                                        $insert[$column_name]= date("Y-m-d", strtotime($filesop[$index]));
                                }
                                else 
                                { 
                                    
                                    $insert[$column_name]=$filesop[$index];     
                                }
                            }
                            
                        }           
                        $check_emp_number = $insert['emp_number'];
            // insert data from file to table
                        $id=    DB::table('hr_employee_int_update_t')->insertGetid($insert);
                            // auditlog
                              $this->auditlog($id,"employeeuploadupdate","create",$insert,"hr_employee_int_update_t");
                         
                    }
                    $c++;
                }
              
               return 1;
                    
        } 
        else 
        {
            return 2;
        }
             
    }
    // update validate
    public function getEmployeeupdatevalidate(Request $request) 
        {
            
            if (isset($_GET['batchname'])) 
            {
                if (!empty($_GET['batchname'])) 
                {
                    $filter = 'AND hr_emp_upload_details_t.batch_no = "' . $_GET['batchname'] . '"';
                    $batch = $_GET['batchname'];
                }
            }
            
            if (isset($_GET['type'])) 
            {
                $type = 'PRODUCTUPLOAD';
                $message['status'] = 'success';
                switch ($_GET['type']) 
                {
                    case 'verify':
                        $upload = $this->updateuploadValidation($batch, $message);
                        $this->data['status'] = $upload['status'];
                        $this->data['message'] = $upload['message'];
                        return $upload;
                        break;
                    
                    case 'load':$upload = $this->updateLoadMaster($batch, $message);
                        $this->data['status'] = $upload['status'];
                        $this->data['message'] = $upload['message'];
                        return $upload;
                        break;
                }
            }
        }
        function updateuploadValidation($batchno, $status) 
    {
    $status['status']='success';
        $status['message'] =' ';
        $sql = "select * from hr_employee_int_update_t where batch_status ='UPLOADED'  and batch_no='" . $batchno . "'"; 
        $result_pr = \DB::select($sql); 
        if (!empty($result_pr)) 
        { 
            $check=0;
            
            foreach($result_pr as $key=>$value)
            {
                   $status['status'] ='success';
                   $status['message'] =' ';
                /*************************** Check for Employee Code   ******************************************/
                 if(!empty($value->emp_number))
                {
                            $e_query = DB::table('hr_employee_t')->where('employee_number',$value->emp_number)->get();
                            if(count($e_query)>0){
                                
                            }else{
                                   $status['status'] = 'error';
                    $status['message'].= 'Employee number is not created already.. Please enter Valid Employee Code' . ' ,';
                            }
                }
                else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Employee Code Empty.. Please enter Employee Code' . ' ,';
                    
                }
                /*************************** Check for Employee Code   ******************************************/
/*************************** Check for Prefix   ******************************************/
                if(!empty($value->prefix))
                {
                    $checkvalue =$this->checkvalue($value->prefix,"prefix");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Prefix not exist' . ' ,';
                    }
                }
            
/*************************** Check for Prefix  End ******************************************/  
/*************************** Check for Employee first Name   ******************************************/
                 if(!empty($value->first_name))
                {
                }
            
/*************************** Check for Employee first Name    ******************************************/

                 /*************************** Check for Email  ******************************************/
                 if(!empty($value->email))
                {
                    if (!filter_var($value->email, FILTER_VALIDATE_EMAIL)) {
                         $status['status'] = 'error';
                    $status['message'].= 'Email Invalid..' . ' ,';
                    } 
                }
                /**else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Email Empty.. Please enter Email' . ' ,';
                    
                } **/
 /*************************** Check forEmail  ******************************************/
/*************************** Check for Company   ******************************************/
                if(!empty($value->company))
                {
                    $company_id =$this->company($value->company,"name");
                  
                    if ($company_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Company Name does not exist'.' , ' ;
                    }
                }
                
/*************************** Check for Company End  ******************************************/ 

/*************************** Check for Location  ******************************************/
                if(!empty($value->location))
                {
                    $location_id =$this->location($value->location,"name");
                    if (count($location_id) <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Location Name does not exist'.' , ' ;
                    }
                }
                
               
/*************************** Check for Location End ******************************************/
        
/*************************** Check for Department  ******************************************/
                if(!empty($value->department))
                {
                    $department_id =$this->department($value->department,"name");   
                    if (count($department_id) <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Department Name does not exist'.' , ' ;
                    }
                }
                
           
/*************************** Check for Department End ******************************************/
        
                        
/*************************** Check for Reporting check  ******************************************/
                if(!empty($value->reporting_manger))
                {
                    $report =$this->reporting($value->reporting_manger,"name"); 
                  
                    if($report[0]->cnt <= 0)
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Reporting Manager does not exist'.' , ' ;
                    }
                }
                
             /***   else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Reporting Manager  Name Empty.. Please enter Reporting Manager Name' . ' , ';                    
                } **/
                    
/*************************** Check for Reporting check ******************************************/
    
/*************************** Check for Job Title  ******************************************/
                if(!empty($value->job_title))
                {
                                    dd($value->job_title);
                    $job_title_id =$this->job_title($value->job_title,"name");  
                    if ($job_title_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Position Name does not exist'.' , ' ;
                    }
                }
                
           /**     else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Position Empty.. Please enter Position' . ' , ';                    
                } **/
/*************************** Check for Job Title End ******************************************/
        
/*************************** Check for Position  ******************************************/
                if(!empty($value->position))
                {
                    $position_id =$this->position($value->position,"name"); 
                    if ($position_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Grade Name does not exist'.' , ' ;
                    }
                }
                
              /**  else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Grade Empty.. Please enter Grade' . ' , ';                    
                } **/
/*************************** Check for Job Title End ******************************************/
                        
/*************************** Check for Joining Date  ******************************************/
          
                
/*************************** Check for Joining Date End ******************************************/
                
/*************************** Check for Employee Type   ******************************************/
                if(!empty($value->employee_type))
                {
                    $checkvalue =$this->checkvalue($value->employee_type,"employee_type");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Employee Type not exist' . ' ,';
                    }
                }
                
/*************************** Check for Employee Type  End ******************************************/   
/*************************** Check for Employee Number   ******************************************/
             
               
/*************************** Check for Employee Number  End ******************************************/ 
        /*************************** Check for Group  ******************************************/
                if(!empty($value->group_type))
                {
                    $group_id =$this->grouptype($value->group_type,"name"); 
                    if ($group_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Group Name does not exist'.' , ' ;
                    }
                }
             
/*************************** Check for Group End ******************************************/
        /*************************** Check for Area  ******************************************/
                if(!empty($value->med_rep_area))
                {
                    $area_id =$this->area($value->med_rep_area,"name"); 
                    if (count($area_id) <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Area Name does not exist'.' , ' ;
                    }
                }
                
               /*** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Area Name Empty.. Please enter Area Name' . ' , ';                    
                } **/
/*************************** Check for Department End ******************************************/
    
/*************************** Check for Gender   ******************************************/
                if(!empty($value->gender))
                {
                    $checkvalue =$this->checkvalue($value->gender,"gender");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Gender not exist' . ' ,';
                    }
                }
              /**  else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Gender Empty.. Please enter Gender' . ' ,';
                    
                } **/
/*************************** Check for Employee Type  End ******************************************/
                
                
/*************************** Check for Marital Status   ******************************************/
                if(!empty($value->marital_status))
                {
                    $checkvalue =$this->checkvalue($value->marital_status,"marital_status");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Marital Status not exist' . ' ,';
                    }
                }
            /**    else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Marital Status Empty.. Please enter Marital Status' . ' ,';
                    
                } **/
/*************************** Check for Marital Status  End ******************************************/
                
/*************************** Check for Nationality   ******************************************/
                if(!empty($value->nationality))
                {
                    $checkvalue =$this->checkvalue($value->nationality,"nationality");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Nationality not exist' . ' ,';
                    }
                }
                
             /***   else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Nationality  Empty.. Please enter Nationality' . ' ,';
                    
                } ***/
/*************************** Check for Nationality  End ******************************************/
                
/*************************** Check for Mother Tongue   ******************************************/
                if(!empty($value->mother_tongue))
                {
                    $checkvalue =$this->checkvalue($value->mother_tongue,"mother_tongue");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Mother Tongue not exist' . ' ,';
                    }
                }
            /**    else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Mother Tongue Empty.. Please enter Mother Tongue' . ' ,';
                    
                } **/
/*************************** Check for Mother Tongue End ******************************************/
                
/*************************** Check for Religion   ******************************************/
                if(!empty($value->religion))
                {
                    $checkvalue =$this->checkvalue($value->religion,"religion");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Religion not exist' . ' ,';
                    }
                }
            /***    else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Religion Empty.. Please enter Religion' . ' ,';
                    
                } **/
/*************************** Check for Religion End ******************************************/
                
/*************************** Check for Blood Group   ******************************************/
                if(!empty($value->blood_group))
                {
                    $checkvalue =$this->checkvalue($value->blood_group,"blood_group");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Blood Group not exist' . ' ,';
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Blood Group Empty.. Please enter Blood Group' . ' ,';
                    
                } **/
/*************************** Check for Blood Group End ******************************************/
                
/*************************** Check for ID Type 2  ******************************************/
                if(!empty($value->id_type_2))
                {
                    $checkvalue =$this->checkvalue($value->id_type_2,"id_type_2");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'ID Type 2 not exist' . ' ,';
                    }
                }
            /***    else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'ID Type 2 Empty.. Please enter ID Type 2' . ' ,';
                    
                } **/
/*************************** Check for ID Type 2 End ******************************************/                
                
/*************************** Check for ID Type 1 ******************************************/
                if(!empty($value->id_type_1))
                {
                    $checkvalue =$this->checkvalue($value->id_type_1,"id_type_1");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'ID Type 1 not exist' . ' ,';
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'ID Type 1 Empty.. Please enter ID Type 1' . ' ,';
                    
                } **/
               
/*************************** Check for ID Type 1 End ******************************************/
                
/*************************** Check for Country  ******************************************/
                if(!empty($value->perm_country))
                {
                    $country_id =$this->country($value->perm_country,"name");   
                    if ($country_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Country Name does not exist'.' , ' ;
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Country Empty.. Please enter Country' . ' , ';                    
                } **/
/*************************** Check for Country End ******************************************/
                
/*************************** Check for State  ******************************************/
                if(!empty($value->perm_state))
                {
                    $state_id =$this->state($value->perm_state,"name"); 
                    if ($state_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'State Name does not exist'.' , ' ;
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'State Empty.. Please enter State' . ' , ';                    
                } **/
/*************************** Check for State End ******************************************/
                
/*************************** Check for City  ******************************************/
                if(!empty($value->perm_city))
                {
                    $city_id =$this->city($value->perm_city,"name");    
                    if ($city_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'City Name does not exist'.' , ' ;
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'City Empty.. Please enter City' . ' , ';                    
                } **/
/*************************** Check for City End ******************************************/             
                
/*************************** Check for Country  ******************************************/
                        if(!empty($value->curr_country))
                        {
                            $country_id =$this->country($value->curr_country,"name");   
                            if ($country_id[0]->cnt <= 0) 
                            {
                                $status['status'] = 'error';
                                $status['message'].= 'Same Address Country Name does not exist'.' , ' ;
                            }
                        }
                     /***   else
                        {
                                $status['status'] = 'error';
                                $status['message'].= 'Same Address Country Empty.. Please enter Country' . ' , ';                    
                        } **/
/*************************** Check for Country End ******************************************/
                
/*************************** Check for State  ******************************************/
                if(!empty($value->curr_state))
                {
                    $state_id =$this->state($value->curr_state,"name"); 
                    if ($state_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Same Address State Name does not exist'.' , ' ;
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Same Address State Empty.. Please enter State' . ' , ';                    
                } **/
/*************************** Check for State End ******************************************/
                
/*************************** Check for City  ******************************************/
                if(!empty($value->curr_city))
                {
                    $city_id =$this->city($value->curr_city,"name");    
                    if ($city_id[0]->cnt <= 0) 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Same Address City Name does not exist'.' , ' ;
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Same Address City Empty.. Please enter City' . ' , ';                    
                } **/
/*************************** Check for City End ******************************************/             


/*************************** Check for Account Type   ******************************************/
                if(!empty($value->account_type))
                {
                    $checkvalue =$this->checkvalue($value->account_type,"account_type");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Account Type not exist' . ' ,';
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Account Type Empty.. Please enter Account Type' . ' ,';
                    
                } **/
/*************************** Check for Account Type  End ******************************************/
        
                
/*************************** Check for Education Level   ******************************************/
                if(!empty($value->education_level1))
                {
                    $checkvalue =$this->checkvalue($value->education_level1,"education_level");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Education Level not exist' . ' ,';
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Education Level Empty.. Please enter Education Level' . ' ,';
                    
                } **/
/*************************** Check for Education Level  End ******************************************/
                
/*************************** Check for Competency Level   ******************************************/
                if(!empty($value->competency1))
                {
                    $checkvalue =$this->checkvalue($value->competency1,"competency_level");
                    
                    if ($checkvalue['result'] =='n') 
                    {
                        $status['status'] = 'error';
                        $status['message'].= 'Competency Level not exist' . ' ,';
                    }
                }
            /** else
                {
                    $status['status'] = 'error';
                    $status['message'].= 'Competency Level Empty.. Please enter Competency Level' . ' ,';
                    
                } **/
/*************************** Check for Competency Level  End ******************************************/

        
                $status['message']=rtrim($status['message'],',');
                              
                if($status['status'] == "error") 
                {
                                    $check=1;
                    $sql = "update hr_employee_int_update_t set batch_status ='ERROR' , batch_comments='" . $status['message'] . "\n'  WHERE hr_employee_id='" . $value->hr_employee_id . "' ";
                    $result = \DB::update($sql);
                    $status['message']='Employee data have some error';  
                } 
                else
                {
                    $sql = "update hr_employee_int_update_t set batch_status ='VALIDATED' , batch_comments='' where hr_employee_id='" . $value->hr_employee_id . "' ";
                    $result = \DB::update($sql);
                    $status['status']='success';
                    $status['message']='Employee Data validated successfully';
                }
                
          
        }
        if($check==0){
         return $status;
        }
        else{
            $status['status']='error';
        $status['message'] ='Employee data have some error';
            return $status;
        }
    }
       $status['status']='error';
        $status['message'] ='NO DATA TO VALIDATE';
         return $status;
    }
            public function updateLoadMaster($loadModname, $status){
                  
        //$comnfun = new ProductuploadController;
        $status['status'] = '';
        $status['message'] = '';
        $sql = "select * from hr_employee_int_update_t  where  batch_status ='VALIDATED'  and batch_no='" . $loadModname . "'"; 
        $result = \DB::select($sql);
        
        $data=array();
        $loadid=array();
         
        if(count($result)>0)
        { 
            foreach($result  as $key=>$value)
            {
                    
/***************** Load Employee Officia Details ************************************/
                
                $employee_number =  $value->emp_number;
   $e_query = DB::table('hr_employee_t')->where('employee_number',$value->emp_number)->get();
   
                            if(count($e_query)>0){
                            $employee_id      = $e_query[0]->employee_id;     
                            }
                if($value->first_name!=''){
                $datas['first_name']    = $value->first_name;
                }
                if($value->last_name!=''){
                 $datas['last_name']  = $value->last_name;
                }
             
               if($value->email!=''){
                $datas['email']           = $value->email;
                }
                if($value->company!=''){
                $company = $this->company($value->company,"name");
               $datas['company_id']      = $company[0]->company_id;
                }

                if($value->location!=''){
                $location = $this->location($value->location,"name");
                $array_id=[];
                foreach($location as $k=>$v){
                    $array_id[$k]=(string) $v->location_id;
                }
               $datas['location_id']     = json_encode($array_id);
                }

                if($value->department!=''){
                $department = $this->department($value->department,"name");
                  $array_id=[];
                foreach($department as $k=>$v){
                    $array_id[$k]=(string) $v->department_line_id;
                }
                $datas['department']      = json_encode($array_id);
                 }

                 if($value->med_rep_area!=''){
                $med_rep_area = $this->area($value->med_rep_area,"name");
                $array_id=[];
              foreach($med_rep_area as $k=>$v){
                  $array_id[$k]=(string)$v->area_id;
              }
               $datas['med_rep_area']      = json_encode($array_id);

            }

               if($value->reporting_manger!=''){

                $reporting=$this->reporting($value->reporting_manger,"name");
               $datas['reporting_manager'] =$reporting[0]->employee_id; 

               }

               if($value->job_title!=''){
                $job_title = $this->job_title($value->job_title,"name");
               $datas['job_title']       = $job_title[0]->job_title_id;
               }

               if($value->position!=''){
                $position = $this->position($value->position,"name");
               $datas['position']        = $position[0]->position_id;
                  }

                if($value->joining_date!=''){
      
               $datas['date_of_joining'] = $value->joining_date;
                  }

                if($value->esi_number!=''){
               $datas['esi_no']          = $value->esi_number;

                  }

                if($value->esi_dispensary!=''){
               $datas['esi_dispensary']  = $value->esi_dispensary;
                  }

                if($value->pf_number!=''){ 
               $datas['pf_no']           = $value->pf_number;
                 }

                 if($value->pf_date!=''){ 
               $datas['pf_date']          =$value->pf_date;
                 }

                 if($value->uan_no!=''){ 
               $datas['uan_no']         =$value->uan_no;
                 }
                 if($value->alternative_telephone_number!=''){ 
               $datas['alternative_telephone_number']  =$value->alternative_telephone_number;
            }

               if($value->biometric_empno!=''){ 
               $datas['biometric_empno']          =$value->biometric_empno;
            }
                if($value->c_l!=''){ 
               $datas['c_l']          =$value->c_l;
            }
               if($value->s_l!=''){ 
               $datas['s_l']          =$value->s_l;
            }
                if($value->e_l!=''){ 
               $datas['e_l']          =$value->e_l;
            }

               if($value->mobile_number!=''){ 
               $datas['work_telephone_number']   = $value->mobile_number;
            }

             if($value->employee_type!=''){
              $employee_type = $this->checkvalue($value->employee_type,'employee_type');
             $datas['employee_type']   = $employee_type['val'];

          }
          if($value->prefix!=''){
              $prefix = $this->checkvalue($value->prefix,'prefix');
               $datas['prefix']   = $prefix['val'];
            }
               $datas['updated_at']      =  date('Y-m-d');

               if($value->group_type!=''){ 
               $group_type = $this->grouptype($value->group_type,"name");
               $datas['group_type']=$group_type       = $group_type[0]->group_id;
            }
               $datas['active']="Yes";
               
             
                 Employeecreate::find($employee_id)->update($datas);  
               
              
                
 /***************** Load Employee Officia Details ************************************/     
                $sql = \DB::table('hr_emp_personal')->where('employee_id',$employee_id)->get(); 
             
                if(count($sql)>0)
                {
                   
                    if($value->gender!=''){ 
                    $genderval      = $this->checkvalue($value->gender,'gender');
                    $personal_data['gender']             = $genderval['val'];
                     }
                     if($value->marital_status!=''){ 
                    $marital_statusval  = $this->checkvalue($value->marital_status,'marital_status');
                    $personal_data['marital_status']    = $marital_statusval['val'];
                }

                if($value->nationality!=''){ 

                    $nationalityval     = $this->checkvalue($value->nationality,'nationality');

                    $personal_data['nationality']       = $nationalityval['val'];
                }
                if($value->birth_date!=''){ 
                    $personal_data['date_of_birth']     = $value->birth_date;
                }
                if($value->age!=''){ 
                   $personal_data['age']                = $value->age;
                }
                if($value->mother_tongue!=''){ 
            $monther_tongueval  = $this->checkvalue($value->mother_tongue,'mother_tongue');
                    $personal_data['monther_tongue']    = $monther_tongueval['val'];
                }
                if($value->religion!=''){ 
            $religionval        = $this->checkvalue($value->religion,'religion');
                    $personal_data['religion']          = $religionval['val'];
                }
                if($value->blood_group!=''){ 
            $blood_groupval     = $this->checkvalue($value->blood_group,'blood_group');
                    $personal_data['blood_group']       = $blood_groupval['val'];   
                    }   
                    if($value->email!=''){      
                    $personal_data['personal_mail']     = $value->email;
                }
                   if($value->mobile_number!=''){ 
                    $personal_data['personal_mobile']   = $value->mobile_number;
                }
                    if($value->id_type_1!=''){ 
                    $id_type_1      = $this->checkvalue($value->id_type_1,'id_type_1');
                    $personal_data['id_name']           = $id_type_1['val'];
                }
                if($value->id_number_1!=''){
                    $personal_data['id_number']         = $value->id_number_1;
                }

                if($value->id_type_1!=''){
                    $id_type_2      = $this->checkvalue($value->id_type_1,'id_type_2');
                    $personal_data['id_name1']          = $id_type_2['val'];
                }
                if($value->id_number_2!=''){
                   $personal_data['id_number1']         = $value->id_number_2;
                }
                 if($value->father_name!=''){
                    $personal_data['father_name']       = $value->father_name;
                 }
                  if($value->mother_name!=''){
                    $personal_data['mother_name']       = $value->mother_name;
                  }
                   if($value->spouse_name!=''){
                    $personal_data['spouse_name']       = $value->spouse_name;
                       }
                   if($value->spouse_dob!=''){
                    $personal_data['spouse_dob']        = $value->spouse_dob;
                       }
                   if($value->children_number!=''){
                    $personal_data['no_of_children']    = $value->children_number;
                       }
                   if($value->child1name!=''){
                    $personal_data['children_name1']    = $value->child1name;
                       }
                   if($value->child1dob!=''){
                    $personal_data['children_dob1']     = $value->child1dob;
                       }
                   if($value->child2name!=''){
                    $personal_data['children_name2']    = $value->child2name;
                       }
                   if($value->child2dob!=''){
                    $personal_data['children_dob2']     = $value->child2dob;
                   }
                    $personal_data['updated_at']        =  date('Y-m-d');
                   
                     $user = DB::table('hr_emp_personal')->where('id',$sql[0]->id)->update($personal_data);   
                            // auditlog
              $this->auditlog($user,"employeeupdateupload","update",$personal_data,"hr_emp_personal");
                }
              
                /***************** Load Employee Officia Details End ************************************/  
                /***************** Load Contact Details End ************************************/ 
$sql = \DB::table('hr_emp_contact')->where('employee_id',$employee_id)->get();              
                if(count($sql)>0)
              {               
         if($value->permanent_address!=''){
               $contact_data['permanent_street_address'] = $value->permanent_address;
         }
           if($value->perm_country!=''){
               $perm_country = $this->country($value->perm_country,"name");
               $contact_data['permanent_country']     = $perm_country[0]->country_id;
                  }
           if($value->perm_state!=''){
               $perm_country = $this->state($value->perm_state,"name");
               $contact_data['permanent_state']     = $perm_country[0]->state_id;
                  }
           if($value->perm_city!=''){
               $perm_country = $this->city($value->perm_city,"name");
               $contact_data['permanent_city']     = $perm_country[0]->city_id;
                  }
           if($value->perm_pincode!=''){
               $contact_data['permanent_postal_code'] = $value->perm_pincode;  
                  }
           if($value->current_address!=''){
               $contact_data['current_street_address'] = $value->current_address;
                  }
           if($value->curr_country!=''){
               $curr_country = $this->country($value->curr_country,"name");
               $contact_data['current_country']     = $curr_country[0]->country_id;
                  }
           if($value->curr_state!=''){
               $perm_country = $this->state($value->curr_state,"name");
               $contact_data['current_state']     = $perm_country[0]->state_id;
                  }
           if($value->curr_city!=''){
               $perm_country = $this->city($value->curr_city,"name");
               $contact_data['current_city']     = $perm_country[0]->city_id;
                  }
           if($value->curr_pincode!=''){
               $contact_data['current_postal_code'] = $value->curr_pincode;
           }
                               
               $emp_contact = DB::table('hr_emp_contact')->where('id',$sql[0]->id)->update($contact_data);   
                // auditlog
              $this->auditlog($emp_contact,"employeeupdateupload","update",$data,"hr_emp_contact");
              }
                
/***************** Load Contact Details End ************************************/
                
/***************** Load Salary Details ************************************/  
         if( $value->bank_name!=''){
                    $data1['bank_name']   = $value->bank_name;
                 }
                  if( $value->branch_name!=''){
                    $data1['branch_name'] = $value->branch_name;
                       }
                  if( $value->ifsc_code!=''){
                    $data1['ifsc_code']   = $value->ifsc_code;
                       }
                  if( $value->acc_holder_name!=''){
                    $data1['account_holder_name'] = $value->acc_holder_name;
          
                       }
                  if( $value->account_type!=''){
                        $accounttypeval = $this->checkvalue($value->account_type,'account_type');
                    $data1['account_type']        = $accounttypeval['val'];
                       }
                  if( $value->accunt_number!=''){
                    $data1['account_number']      = $value->accunt_number;
                       }
                 
                if( $value->bank_name!=''|| $value->branch_name!=''||  $value->ifsc_code!=''|| $value->acc_holder_name!=''||$value->account_type!='' ||$value->accunt_number!=''){
            $data1['employee_id']   =$employee_id;
                    $emp_salary = DB::table('hr_emp_salary')->insertGetId($data1); 
                     // auditlog
              $this->auditlog($emp_salary,"employeeupdateupload","create",$data1,"hr_emp_salary");
                }
           
/***************** Load Salary Details End ************************************/

/***************** Load Education Details ************************************/
        
                    $data2['employee_id']    = $employee_id;
                    if($value->education_level1!=''){
            $education_levelval = $this->checkvalue($value->education_level1,'education_level');
                    $data2['education_level']    = $education_levelval['val'];
                    }
                     if($value->institutionname1!=''){
                    $data2['institution_name']  = $value->institutionname1;
                     }
                     if($value->course_board1!=''){
                    $data2['school_board']      = $value->course_board1;
                     }
                     if($value->fromduration1!=''){
                    $data2['from_date']       = $value->fromduration1;
                     }
                     if($value->toduration1!=''){
                    $data2['to_date']         = $value->toduration1;
                     }
                     if($value->percentage1!=''){
                    $data2['percentage']      = $value->percentage1;
                     }
                       if($value->education_level1!='' || $value->institutionname1!='' || $value->course_board1!='' || $value->fromduration1!='' || $value->toduration1!=''|| $value->percentage1!=''){
                    $emp_salary = DB::table('hr_emp_education')->insertGetId($data2);
                      $this->auditlog($emp_salary,"employeeupdateupload","create",$data2,"hr_emp_education");
                       }
                     
                        // auditlog
            
              if($value->institutionname2!=""){
                $data2['employee_id']    = $employee_id;
            $education_levelval = $this->checkvalue($value->education_level2,'education_level');
                    $data2['education_level']    = $education_levelval['val'];
                    $data2['institution_name']  = $value->institutionname2;
                    $data2['school_board']      = $value->course_board2;
                    $data2['from_date']       = $value->fromduration2;
                    $data2['to_date']         = $value->to_duration2;
                    $data2['percentage']      = $value->percentage2;
                    $emp_salary = DB::table('hr_emp_education')->insertGetId($data2); 
                          // auditlog
              $this->auditlog($emp_salary,"employeeupdateupload","create",$data2,"hr_emp_education");
              }
               if($value->institution3!=""){
                $data2['employee_id']    = $employee_id;
            $education_levelval = $this->checkvalue($value->education_level3,'education_level');
                    $data2['education_level']    = $education_levelval['val'];
                    $data2['institution_name']  = $value->institution3;
                    $data2['school_board']      = $value->course_board3;
                    $data2['from_date']       = $value->from_duration3;
                    $data2['to_date']         = $value->to_duration3;
                    $data2['percentage']      = $value->percentage3;
                    $emp_salary = DB::table('hr_emp_education')->insertGetId($data2); 
                          // auditlog
              $this->auditlog($emp_salary,"employeeupdateupload","create",$data2,"hr_emp_education");
              }
                
                            
/***************** Load Education Details End ************************************/
                                
/***************** Load Experience Details ************************************/
        if($value->organization_name1!=''){
                $data3['employee_id']    = $employee_id;
                $data3['organization_name']       = $value->organization_name1;
                $data3['organization_website']    = $value->website1;
                $data3['designation']             = $value->designation1;
                $data3['ctc']                     = $value->ctc1;
                $data3['from_date']               = $value->expfrom1;
                $data3['to_date']                 = $value->expto1;
                $emp_experience = DB::table('hr_emp_experience')->insertGetId($data3);
                  // auditlog
              $this->auditlog($emp_experience,"employeeupdateupload","create",$data3,"hr_emp_experience");
                }
              if($value->organization2!=''){
                $data3['employee_id']    = $employee_id;
                $data3['organization_name']       = $value->organization2;
                $data3['organization_website']    = $value->website2;
                $data3['designation']             = $value->designation2;
                $data3['ctc']                     = $value->ctc2;
                $data3['from_date']               = $value->expfrom2;
                $data3['to_date']                 = $value->expto2;
                $emp_experience = DB::table('hr_emp_experience')->insertGetId($data3);
                  // auditlog
              $this->auditlog($emp_experience,"employeeupdateupload","create",$data3,"hr_emp_experience");
              }
              if($value->organization3!=''){
                $data3['employee_id']    = $employee_id;
                $data3['organization_name']       = $value->organization3;
                $data3['organization_website']    = $value->website3;
                $data3['designation']             = $value->designation3;
                $data3['ctc']                     = $value->ctc3;
                $data3['from_date']               = $value->expfrom3;
                $data3['to_date']                 = $value->expto3;
                $emp_experience = DB::table('hr_emp_experience')->insertGetId($data3);
                  // auditlog
              $this->auditlog($emp_experience,"employeeupdateupload","create",$data3,"hr_emp_experience");
              }
                
                            
/***************** Load Experience Details End ************************************/
                
/***************** Load Skills Details ************************************/
                if($value->skills1!=''){        
                    $data4['employee_id']                = $employee_id;
                    $data4['skill']                = $value->skills1;
                    $data4['version']              = $value->version1;                  
            $competencyval = $this->checkvalue($value->competency1,'competency_level');                 
                    $data4['competency_level']     = $competencyval['val'];
                    $emp_experience = DB::table('hr_emp_skills')->insertGetId($data4);
                       // auditlog
                    $this->auditlog($emp_experience,"employeeupdateupload","create",$data4,"hr_emp_skills");
                                }
                    if($value->skills2!=''){
                    $data4['employee_id']                = $employee_id;
                    $data4['skill']                = $value->skills2;
                    $data4['version']              = $value->version2;                  
            $competencyval = $this->checkvalue($value->competency2,'competency_level');                 
                    $data4['competency_level']     = $competencyval['val'];
                    $emp_experience = DB::table('hr_emp_skills')->insertGetId($data4);
                       // auditlog
                    $this->auditlog($emp_experience,"employeeupdateupload","create",$data4,"hr_emp_skills");
                    }if($value->skills3!=''){
                    $data4['employee_id']                = $employee_id;
                    $data4['skill']                = $value->skills3;
                    $data4['version']              = $value->version3;                  
            $competencyval = $this->checkvalue($value->competency3,'competency_level');                 
                    $data4['competency_level']     = $competencyval['val'];
                    $emp_experience = DB::table('hr_emp_skills')->insertGetId($data4);
                       // auditlog
                    $this->auditlog($emp_experience,"employeeupdateupload","create",$data4,"hr_emp_skills");
                    }
             
                
                        
/***************** Load Skills Details End ************************************/ 
            }
        $sql = "update hr_employee_int_update_t set batch_status ='LOADED' , batch_comments='' where hr_employee_id='" . $value->hr_employee_id . "' ";
                $result = \DB::update($sql);
                        // auditlog
                $data['batch_status']=$data_up['batch_status']='LOADED';
               
                $this->auditlog($value->hr_employee_id,"employeeupdateupload","update",$data,"hr_employee_int_update_t");
                $status['status']='success';
                $status['message']='Employee Data Loaded Sucessfully';
                return $status;
                return true;
        }
        else{

            $sql=\DB::select("select * from hr_employee_int_update_t where batch_no='".$loadModname."'");
            if($sql[0]->batch_status=="UPLOADED"){
                 $status['status']='info';
                 $status['message']='Pls Validate the Batch First..!';
            return $status;
            }else if($sql[0]->batch_status=="ERROR"){
                $status['status']='error';
                 $status['message']='Batch Error..!';
            return $status;
            }else{
                $status['status']='info';
                 $status['message']='Employee  Data already Loaded';
            return $status;
            }
        }
    }
    
    
         //update edit page open
    public function updateedit(Employeeuploadupdate $employeeupload, $id=null)
    {
       
        $this->data['details'] = Employeeuploadupdate::find($id);
        
        $this->data['edit_id']= $id;
         $this->data['source']= "update";
       
    return view('employeeupload.edit',$this->data);
    }
    //update view function
    public function showupdate(Employeeuploadupdate $employeeupload, $id=null)
    {
        
        $this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("hr_employee_int_update_t");
    $this->data['values'] = Employeeuploadupdate::find($id);
         $this->data['source'] ="update";
    return view('employeeupload.view',$this->data);
    }
          // update delete function
    public function employeeuploadupdatedelete($del_id=null)
    {
       
       
      $data= DB::table('hr_employee_int_update_t')->where('hr_employee_id',$del_id)->get();
            $query = DB::table('hr_employee_int_update_t')->where('hr_employee_id',$del_id)->delete();
              // auditlog
              $this->auditlog($del_id,"employeeupdateupload","delete",$data[0],"hr_employee_int_update_t");
                  return 0;
    }
       // update edit update
    public function update2(Request $request, Employeeuploadupdate $employeeupload)
    {
        $edit_id                    = $_POST['edit_id'];
        $request->created_by        = '';
        $request->created_date      = '';
        $request->last_updated_by   = '';
        $request->last_updated_date = '';
        $request->batch_status      = 'UPLOADED';
        $request->batch_comments    = '';
        
      
        Employeeuploadupdate::find($edit_id)->update($_POST); 
       // auditlog
            $this->auditlog($edit_id,"employeeupdateupload","update",$_POST,"hr_employee_int_update_t");
        $table = Employeeuploadupdate::select("hr_employee_int_update_t.*",DB::raw("CONCAT(hr_employee_int_update_t.first_name,' ',hr_employee_int_update_t.last_name) as full_name"))->get();       
        $this->data['batch_no'] = $this->jCombo('hr_emp_upload_details_t','batch_no','batch_no','');
        $this->data['datas'] = $table;  

        return redirect('employeeupdateupload');
        
    }
}
