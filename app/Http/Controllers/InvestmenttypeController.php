<?php

namespace App\Http\Controllers;

use App\investmenttype;
use App\Accounthrms;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\DataTables;

class InvestmenttypeController extends Controller
{
     public function __construct()
	{
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs(); 
	 }
	
	public function index($id=null)
    {
		if($id == 0)
		{
			
			$this->data['inv_limit'] = '';
			$this->data['inv_name'] = '';
            $this->data['name'] = '';
			$this->data['inv_id']=0;
			$this->data['line_data'] = array();
		
		}
		else
		{
			$result = DB::table('hr_inve_type')->where('inv_id',$id)->get();

			$this->data['inv_id']=$id;
			
			$this->data['inv_limit'] = $result[0]->inv_limit;
			$this->data['inv_name'] = $result[0]->inv_name;
				
			$lines_result = DB::table('hr_inve_lines')->where('inv_id',$id)->get();
			$this->data['line_data'] = $lines_result;
			
			
		}
		$this->data['urlmenu']=$this->indexs();
        return view('investmenttype.form',$this->data);
		
    }
	
	/** Page loading data **/
	public function indextable(Request $request)
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
        return view('investmenttype.table',$this->data);
    }

    	/** hrms accounts Page loading data **/
	public function hrmsallowancesettings(Request $request)
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
        return view('investmenttype.tableaccount',$this->data);
    }
    // form page load
    public function hrmsallowancesettingsindex($id=null)
    {
		if($id == 0)
		{
			
			$this->data['account_allowance_setting_id'] = '';
			$this->data['department_id'] = '';
			$this->data['line_data'] = array();
            $this->data['allowance_id']                   = $this->jcustomselect('m_allowance_tbl','allowance_id','allowance_name','',"");
                              $this->data['account_structure_id']         = $this->jcustomselect('f_account_structure_t','f_account_structure_id','concatenated_segments','',"");
		
		}
		else
		{
			$result = DB::table('f_hr_account_allowance_setting_t')->where('account_allowance_setting_id',$id)->get();
			$this->data['account_allowance_setting_id']=$id;
			$this->data['department_id'] = $result[0]->department_id;
				
			$lines_result = DB::table('f_hr_account_allowance_setting_lines_t')->where('account_allowance_setting_id',$id)->get();
			$this->data['line_data'] = $lines_result;	
                        foreach($lines_result as $key=>$value){
                           $lines_result[$key]->allowance_id=$this->jcustomselect('m_allowance_tbl','allowance_id','allowance_name',$value->allowance_id,"");
                            $lines_result[$key]->account_structure_id=   $this->jcustomselect('f_account_structure_t','f_account_structure_id','concatenated_segments',$value->account_structure_id,"");
                        }
		}
		$this->data['urlmenu']=$this->indexs();
        return view('investmenttype.formaccount',$this->data);
	//	dd("fg");
    }
      /**  Save Start **/
    public function hrmsallowancesettingsindexstore(Request $request)
    {

       $edit_id = $request->input('edit_id');
		if($edit_id == 0)
		{
			$investmenttype = new Accounthrms();
			$investmenttype->account_allowance_setting_id  = $request->input('account_allowance_setting_id'); 
			$investmenttype->department_id  = $request->input('department_id');
            $investmenttype->created_by  = \Session::get('id');
            $investmenttype->location_id  = \Session::get('location');
            $investmenttype->created_at  =date('Y-m-d');
            $investmenttype->company_id  = \Session::get('companyid');
			$investmenttype->save();

			$insertedId = $investmenttype->account_allowance_setting_id;
				$this->auditlog($insertedId,"hrmsallowancesettings","create",$_POST,"f_hr_account_allowance_setting_t");
			for($i=0; $i<count($request->input('account_allowance_setting_line_id')); $i++)
			{
				$name = $request->input('allowance_id')[$i];
				$description = $request->input('account_structure_id')[$i];
			 $created_by  = \Session::get('id');
            $location_id  = \Session::get('location');
            $created_at  =date('Y-m-d');
            $company_id  = \Session::get('companyid');
				
				$result = DB::table('f_hr_account_allowance_setting_lines_t')->insertGetId(['allowance_id'=>$name,'account_structure_id'=>$description,'account_allowance_setting_id'=>$insertedId,'created_by'=>$created_by,'location_id'=>$location_id,'created_at'=>$created_at,'company_id'=>$company_id]);
				// auditlog
				$this->auditlog($result,"hrmsallowancesettings","create",$_POST,"f_hr_account_allowance_setting_lines_t");
            
			}
		return 1;
		}
		else
		{
			$company_id  = \Session::get('companyid');
		    $edit_id  = $request->input('edit_id'); 
			$inv_id  = $request->input('edit_id');
			$inv_name  = $request->input('department_id');
		
               $last_updated_by  =\Session::get('id');
			$update = DB::table('f_hr_account_allowance_setting_t')->where('company_id',$company_id)->where('account_allowance_setting_id',$edit_id)->update(['department_id'=>$inv_name,'last_updated_by'=>$last_updated_by]);
			$query = DB::table('f_hr_account_allowance_setting_lines_t')->where('account_allowance_setting_id',$edit_id)->select('account_allowance_setting_line_id')->get();
			// auditlog
			$this->auditlog($edit_id,"hrmsallowancesettings","Update",$_POST,"f_hr_account_allowance_setting_t");
			$data  =[];
			if(count($query)!= 0) {

				foreach ($query as $key => $value) {
						$data[] = $value->account_allowance_setting_line_id;
							}

			}
		
				$newid = [];
			for($i=0; $i<count($request->input('allowance_id')); $i++)
			{
				$inv_lines_id = $request->input('account_allowance_setting_line_id')[$i];
				$newid[] = $inv_lines_id;
				if($inv_lines_id != "")
				{
					
					$data['allowance_id']=$name = $request->input('allowance_id')[$i];
					$data['account_structure_id']=$description = $request->input('account_structure_id')[$i];
					$data['account_allowance_setting_id']=$inv_id;
					$result = DB::table('f_hr_account_allowance_setting_lines_t')->where('account_allowance_setting_line_id',$inv_lines_id)->update(['account_allowance_setting_id'=>$inv_id,'allowance_id'=>$name,'account_structure_id'=>$description]);
					// auditlog
					$this->auditlog($inv_lines_id,"Investment Type","Update",$data,"hr_inve_lines");
				}
				else
				{
					
					$data['allowance_id']=$name = $request->input('allowance_id')[$i];
					$data['account_structure_id']=$description = $request->input('account_structure_id')[$i];
					$data['account_allowance_setting_id']=$inv_id;
					$result = DB::table('f_hr_account_allowance_setting_lines_t')->insertGetId(['account_allowance_setting_id'=>$inv_id,'allowance_id'=>$name,'account_structure_id'=>$description]);
				
					// auditlog
					$this->auditlog($result,"Investment Type","create",$data,"hr_inve_lines");
				}
				
				
			}
			$arrayvalue = array_diff($data,$newid);
			foreach ($arrayvalue as $value) {
			$query_update=	\DB::table('f_hr_account_allowance_setting_lines_t')->where('account_allowance_setting_line_id',$value)->get();
				\DB::table('f_hr_account_allowance_setting_lines_t')->where('account_allowance_setting_line_id',$value)->delete();
		$this->auditlog($value,"Investment Type","delete",$query_update,"f_hr_account_allowance_setting_lines_t");
			}
			 
			return 1;
		
		}
	
    }
	/**  **/

   /**  Save Start **/
    public function store(Request $request)
    {

       $edit_id = $request->input('edit_id');
		if($edit_id == 0)
		{
			$investmenttype = new investmenttype();
			$investmenttype->inv_id  = $request->input('inv_id'); 
			$investmenttype->inv_name  = $request->input('inv_name');
			$investmenttype->inv_limit  = $request->input('inv_limit');
            $investmenttype->created_by  = \Session::get('id');
            $investmenttype->location_id  = \Session::get('location');
            $investmenttype->created_at  =date('Y-m-d');
            $investmenttype->company_id  = \Session::get('companyid');
			$investmenttype->save();

			$insertedId = $investmenttype->inv_id;
				$this->auditlog($insertedId,"Investment Type","create",$_POST,"hr_inve_type");
			for($i=0; $i<count($request->input('inv_lines_id')); $i++)
			{
				$name = $request->input('name')[$i];
				$description = $request->input('description')[$i];
			 $created_by  = \Session::get('id');
            $location_id  = \Session::get('location');
            $created_at  =date('Y-m-d');
            $company_id  = \Session::get('companyid');
				
				$result = DB::table('hr_inve_lines')->insertGetId(['name'=>$name,'description'=>$description,'inv_id'=>$insertedId,'created_by'=>$created_by,'location_id'=>$location_id,'created_at'=>$created_at,'company_id'=>$company_id]);
				// auditlog
				$this->auditlog($result,"Investment Type","create",$_POST,"hr_inve_lines");
            
			}
		return 1;
		}
		else
		{
			$company_id  = \Session::get('companyid');
		    $edit_id  = $request->input('edit_id'); 
			$inv_id  = $request->input('edit_id');
			$inv_name  = $request->input('inv_name');
			$inv_limit  = $request->input('inv_limit');
		
               $last_updated_by  =\Session::get('id');
			$update = DB::table('hr_inve_type')->where('company_id',$company_id)->where('inv_id',$edit_id)->update(['inv_id'=>$inv_id,'inv_limit'=>$inv_limit,'inv_name'=>$inv_name,'last_updated_by'=>$last_updated_by]);
			$query = DB::table('hr_inve_lines')->where('inv_id',$edit_id)->select('inv_lines_id')->get();
			// auditlog
			$this->auditlog($edit_id,"Investment Type","Update",$_POST,"hr_inve_type");
			$data  =[];
			if(count($query)!= 0) {

				foreach ($query as $key => $value) {
						$data[] = $value->inv_lines_id;
							}

			}
		
				$newid = [];
			for($i=0; $i<count($request->input('name')); $i++)
			{
				$inv_lines_id = $request->input('inv_lines_id')[$i];
				$newid[] = $inv_lines_id;
				if($inv_lines_id != "")
				{
					
					$data['name']=$name = $request->input('name')[$i];
					$data['description']=$description = $request->input('description')[$i];
					$data['inv_id']=$inv_id;
					$result = DB::table('hr_inve_lines')->where('inv_lines_id',$inv_lines_id)->update(['inv_id'=>$inv_id,'name'=>$name,'description'=>$description]);
					// auditlog
					$this->auditlog($inv_lines_id,"Investment Type","Update",$data,"hr_inve_lines");
				}
				else
				{
					
					$data['name']=$name = $request->input('name')[$i];
					$data['description']=$description = $request->input('description')[$i];
					$data['inv_id']=$inv_id;
					$result = DB::table('hr_inve_lines')->insertGetId(['inv_id'=>$inv_id,'name'=>$name,'description'=>$description]);
				
					// auditlog
						$this->auditlog($result,"Investment Type","Update",$data,"hr_inve_lines");
				}
				
				
			}
			$arrayvalue = array_diff($data,$newid);
			foreach ($arrayvalue as $value) {
			$query_update=	\DB::table('hr_inve_lines')->where('inv_lines_id',$value)->get();
				\DB::table('hr_inve_lines')->where('inv_lines_id',$value)->delete();
		$this->auditlog($value,"Investment Type","Update",$query_update,"hr_inve_lines");
			}
			 
			return 1;
		
		}
	
    }
	/**  **/
    
  

    /**  Delete Start **/
   public function destroy(Request $request)
    {
		
        $delete_id=  Input::get('del_id');
        //get header
		   $query_data= DB::table('hr_inve_type')->where('inv_id',$delete_id)->get();
		   //get lines
		   	$query1_data= DB::table('hr_inve_lines')->where('inv_id',$delete_id)->get();
		   	// delete header
            $query = DB::table('hr_inve_type')->where('inv_id',$delete_id)->delete();
            //delete lines
			$query1 = DB::table('hr_inve_lines')->where('inv_id',$delete_id)->delete();
		   // auditlog for header
			$this->auditlog($delete_id,"Investment Type","Delete",$query1_data,"hr_inve_lines");
			  // auditlog for lines
			$this->auditlog($delete_id,"Investment Type","Delete",$query_data,"hr_inve_type");
		
			
     
        return 1;
		
    } 

       /**  Delete Start **/
	
   public function hrmsallowancesettingsdelete(Request $request, $delete_id = null)
    {
		

		   $query_data= DB::table('f_hr_account_allowance_setting_t')->where('account_allowance_setting_id',$delete_id)->get();
		   //get lines
		   	$query1_data= DB::table('f_hr_account_allowance_setting_lines_t')->where('account_allowance_setting_id',$delete_id)->get();
		   	// delete header
            $query = DB::table('f_hr_account_allowance_setting_t')->where('account_allowance_setting_id',$delete_id)->delete();
            //delete lines
			$query1 = DB::table('f_hr_account_allowance_setting_lines_t')->where('account_allowance_setting_id',$delete_id)->delete();
		   // auditlog for header
			$this->auditlog($delete_id,"Investment Type","Delete",$query1_data,"f_hr_account_allowance_setting_lines_t");
			  // auditlog for lines
			$this->auditlog($delete_id,"Investment Type","Delete",$query_data,"f_hr_account_allowance_setting_t");
		
			
     
        return 1;
		
    } 
	
/**Jqgrudload data Start **/
    public function investmenttypetaxgrid(investmenttype $investmenttype)
    {
		$company_id  = \Session::get('companyid');
		$wh = '';
		$page = $_GET['page'];
		$limit = $_GET['rows'];
		$sidx = $_GET['sidx'];
		$sord = $_GET['sord'];
		$search_tables=[""];
		  if($_GET['_search']=='true'){
         $wh=$this->jqgridsearch("hr_inve_type",$_GET['filters'],$search_tables);
       } 
	if(!$sidx) $sidx =1;
        
           $result = \DB::select("SELECT COUNT(inv_id) AS count FROM hr_inve_type where 1=1 $wh");

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
                    hr_inve_type.inv_id,
                    hr_inve_type.inv_name,
                    hr_inve_type.inv_limit
        
                    FROM
                        hr_inve_type
                    WHERE
                        1 = 1 $wh ORDER BY $sidx $sord LIMIT $start,$limit";
        

	$result = \DB::select($SQL);
	$responce->rows[]='';
	$responce->rows=$result;
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;

       

	echo json_encode($responce);
	}

	
    public function hrmsallowancesettingsgrid(investmenttype $investmenttype)
    {
		
		$company_id  = \Session::get('companyid');
		$wh = '';
        
		$SQL = "SELECT
                    f_hr_account_allowance_setting_t.account_allowance_setting_id,
                    f_hr_account_allowance_setting_t.department_id,
                    m_department_lines_t.sub_department_name
        
                    FROM
                        f_hr_account_allowance_setting_t
                        left join m_department_lines_t on m_department_lines_t.department_line_id=f_hr_account_allowance_setting_t.department_id
                    WHERE
                        1 = 1 $wh";
        

					$result = \DB::select($SQL);
					return DataTables::of($result)->make(true);
		
	}
	


}
