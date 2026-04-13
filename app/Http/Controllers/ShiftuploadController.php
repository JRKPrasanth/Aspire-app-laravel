<?php

namespace App\Http\Controllers;

use App\Shiftupload;
use Illuminate\Http\Request,DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\DataTables;

class ShiftuploadController extends Controller
{

	    public function __construct(){
		
        $this->data=array();
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

    	$this->data['pageMethod']=\Request::route()->getName();
    	$this->data['urlmenu']=$this->indexs();
        return view('shiftupload.table',$this->data);
    }
	

    public function employeeshiftedit($id = null)
    {
           $shiftupload  = DB::table('hr_employee_shift_details')->where('shift_id',$id)->get();
     
        if(count($shiftupload)>0)
        {
            $shift_details = json_decode($shiftupload[0]->shift_type);
        }
        $name = $shiftupload[0]->employee_id;
        
        return view('shiftupload.edit',  compact(['shift_details','name','id']), $this->data);
    }
	


    public function employeeshiftview($id = null)
    {
        $shiftupload  = DB::table('hr_employee_shift_details')->where('shift_id',$id)->get();
        $employee_name  = DB::table('hr_employee_t')->select('first_name')->where('employee_id',$shiftupload[0]->employee_id)->get();
        if(count($shiftupload)>0)
        {
            $shift_details = json_decode($shiftupload[0]->shift_type);
        }
        $name = $employee_name[0]->first_name;
        
        return view('shiftupload.view',  compact(['shift_details','name']), $this->data);
        
    }
	

	
	public function employeeshiftdetailsgriddata(Request $request)
    {
       

	
       $comp=\Session::get('companyid');
       $wh=" and hr_employee_t.company_id='$comp'";


        
	$SQL = "SELECT hr_employee_shift_details.shift_id,concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as first_name,hr_employee_shift_details.shift_type
	FROM
		hr_employee_shift_details
	LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_employee_shift_details.employee_id
		where 1=1 $wh ";
	
	$result = \DB::select($SQL);
   return DataTables::of($result)->make(true);

    }
	
	
    /** upload shift data update **/
	public function shiftuploadupdate(Request $request)
    {   
          
            $datas=array();
            $datas['shift_id']=$id=$_POST['edit_id'];
            $datas['employee_id']=$_POST['employee_id'];
              $data= explode(',',$_POST['shift_type']);
      
              for($i=0; $i<=30; $i++)
            {

                    if($data[$i] != "")
                    {
                        $l=explode('-',$data[$i]);
                      
                            $list_time[] = array($l[1]);
                    }
            }
        
            $datas['shift_type']= json_encode($list_time);
            $datas['updated_by']= \Session::get('id');
            $datas['updated_at']= date('Y-m-d');
            	$id=DB::table("hr_employee_shift_details")->where('shift_id',$id)->update($datas);
                // auditlog
                  $this->auditlog($id,"shiftupload","update",$datas,"hr_employee_shift_details");
                  return 1;
    }
        
	/** upload shift data save  **/
	public function save(Request $request)
    {        

			$path = $_FILES['file_upload']['name'];
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			$data = array();
			$file = $_FILES['file_upload']['tmp_name'];
			$handle = fopen($file, "r");
			$c = 0;
           
			if ($ext == "csv") 
			{
				while (($filesop = fgetcsv($handle, 1000, ",")) !== false) 
				{
					$list_time = array();
					
					if ($c >= 2)
					{ 
						
						$employee_number = $filesop[0];
						$query2 = DB::table("hr_employee_t")->where('employee_number',$employee_number)->get();
						
						if(count($query2)>0)
						{
							$employee_id = $datas['employee_id'] = $query2[0]->employee_id;
							for($i=1; $i<=31; $i++)
							{
								
								if($filesop[$i] != "")
								{
									$list_time[] = array($filesop[$i]);
								}
							}
							$datas['shift_type']= json_encode($list_time);
							$datas['created_by']= \Session::get('id');
							$datas['created_at']= date('Y-m-d');
                                                        	$id=DB::table("hr_employee_shift_details")->insertGetId($datas);
                                                  // auditlog
                  $this->auditlog($id,"shiftupload","create",$datas,"hr_employee_shift_details");
						}
						
					
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
    /** delete a  upload details**/
    public function destroy(Request $request, $id=null)
    {
   
      
    	$j=0;
       
        if($j==0)
        {
             $query_data = DB::table('hr_employee_shift_details')->where('shift_id',$id)->get();
            $query = DB::table('hr_employee_shift_details')->where('shift_id',$id)->delete();
              // auditlog
              $this->auditlog($id,"shiftupload","delete",$query_data,"hr_employee_shift_details");
        }
       
        if($j == 1)
            return 1;
        else if($j == 0)
            return 2;
        else if($j == 3)
            return 3;
    }
	
}
