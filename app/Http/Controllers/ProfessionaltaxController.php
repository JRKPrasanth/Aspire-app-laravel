<?php

namespace App\Http\Controllers;

use App\Professionaltax;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\DataTables;

class ProfessionaltaxController extends Controller
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
			$this->data['state'] = $this->jcombologin('m_states_t','state_id','state_name','');
			$this->data['description'] = '';
            $this->data['active'] = '';
			$this->data['ptax_state_id']=0;
			$this->data['line_data'] = array();
			
		}
		else
		{
			$result = DB::table('hr_professional_tax_hdr')->where('ptax_id',$id)->get();
			$state_id = $result[0]->ptax_state_id;
			$this->data['ptax_state_id']=$id;
			$this->data['state'] = $this->jcombologin('m_states_t','state_id','state_name',$state_id);
			$this->data['description'] = $result[0]->description;
			$this->data['active'] = $result[0]->active;
			$lines_result = DB::table('hr_professional_tax_lines')->where('ptax_id',$id)->get();
			$this->data['line_data'] = $lines_result;
			
			
		}
		$this->data['urlmenu']=$this->indexs();
        return view('professionaltax.form',$this->data);
		
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
		
        return view('professionaltax.table',$this->data);
    }

    
    

   /** Professional Tax Save Start **/
    public function store(Request $request)
    {
		
       $edit_id = $request->input('edit_id');
		
		if($edit_id == 0)
		{
			$professionaltax = new Professionaltax();
			$professionaltax->ptax_state_id  = $request->input('state_id'); 
			$professionaltax->description  = $request->input('description');
			$professionaltax->active  = $request->input('active');
            $professionaltax->created_by  = \Session::get('id');
            $professionaltax->last_updated_by  = \Session::get('id');
            $professionaltax->location_id  = "1";
            $professionaltax->company_id  = \Session::get('companyid');
            $professionaltax->organization_id  = \Session::get('organization');
			$professionaltax->save();
			$insertedId = $professionaltax->ptax_id;
			
			for($i=0; $i<count($request->input('from_value')); $i++)
			{
				$from_value = $request->input('from_value')[$i];
				$to_value = $request->input('to_value')[$i];
				$deduction_amount = $request->input('deduction_amount')[$i];
				
				$result = DB::table('hr_professional_tax_lines')->insert(['from_value'=>$from_value,'to_value'=>$to_value,'deduction_amount'=>$deduction_amount,'ptax_id'=>$insertedId]);
				// auditlog
				$this->auditlog($insertedId,"Professional Tax","create",$_POST,"hr_professional_tax_lines");
            
			}
		return 1;
		}
		else
		{
			$company_id  = \Session::get('companyid');
		    $edit_id  = $request->input('edit_id'); 
                   
			$ptax_state_id  = $request->input('state_id'); 
			$description  = $request->input('description');
			$active  = $request->input('active');
                        $last_updated_by  =\Session::get('id');
			$update = DB::table('hr_professional_tax_hdr')->where('company_id',$company_id)->where('ptax_id',$edit_id)->update(['ptax_state_id'=>$ptax_state_id,'description'=>$description,'active'=>$active,'last_updated_by'=>$last_updated_by]);
			$query = DB::table('hr_professional_tax_lines')->where('ptax_id',$edit_id)->select('ptax_details_id')->get();
			// auditlog
			$this->auditlog($edit_id,"Professional Tax","Update",$_POST,"hr_professional_tax_hdr");
			$data  =[];
			if(count($query)!= 0) {
				foreach ($query as $key => $value) {
						$data[] = $value->ptax_details_id;
							}

			}
				$newid = [];
			for($i=0; $i<count($request->input('from_value')); $i++)
			{
				$professional_tax_id = $request->input('professional_tax_id')[$i];
				$newid[] = $professional_tax_id;
				if($professional_tax_id != "")
				{
					$from_value = $request->input('from_value')[$i];
					$to_value = $request->input('to_value')[$i];
					$deduction_amount = $request->input('deduction_amount')[$i];
					$result = DB::table('hr_professional_tax_lines')->where('ptax_details_id',$professional_tax_id)->update(['from_value'=>$from_value,'to_value'=>$to_value,'deduction_amount'=>$deduction_amount]);
					// auditlog
					$this->auditlog($professional_tax_id,"Professional Tax","Update",$result,"hr_professional_tax_lines");
				}
				else
				{
					$from_value = $request->input('from_value')[$i];
					$to_value = $request->input('to_value')[$i];
					$deduction_amount = $request->input('deduction_amount')[$i];
					$result = DB::table('hr_professional_tax_lines')->insertGetId(['from_value'=>$from_value,'to_value'=>$to_value,'deduction_amount'=>$deduction_amount,'ptax_id'=>$edit_id]);
					// auditlog
					$this->auditlog($result,"Professional Tax","Update",$result,"hr_professional_tax_lines");
				}
				
				
			}
			$arrayvalue = array_diff($data,$newid);
			foreach ($arrayvalue as $value) {
				\DB::table('hr_professional_tax_lines')->where('ptax_details_id',$value)->delete();
			}
			 
			return 1;
		
		}
	
    }
	/** Professional Tax Save End **/
    
    public function show(Professionaltax $professionaltax)
    {
        //
    }

   
    public function edit(Professionaltax $professionaltax)
    {
        //
    }

    /** Professional Tax Delete Start **/
   public function destroy(Request $request, $id=null)
    {
	   
		$j=0;
		
        if($j==0)
        {

            $query = DB::table('hr_professional_tax_hdr')->where('ptax_id',$id)->delete();
			$query1 = DB::table('hr_professional_tax_lines')->where('ptax_id',$id)->delete();

		}
	   
        if($j == 1)
            return 1;
        else if($j == 0)
            return 2;
        else if($j == 3)
            return 3;
		
		
        return 1;
		
    } 

	
	
    public function professionaltaxgrid(Professionaltax $professionaltax)
    {
		$company_id  = \Session::get('companyid');
        
	$SQL = "SELECT
                    hr_professional_tax_hdr.ptax_id,
                    hr_professional_tax_hdr.ptax_state_id,
                    hr_professional_tax_hdr.description,
                    m_states_t.state_name,
                    hr_professional_tax_lines.from_value,
                    hr_professional_tax_lines.to_value,
                    hr_professional_tax_lines.deduction_amount

                    FROM
                        hr_professional_tax_hdr
                    LEFT JOIN m_states_t ON (m_states_t.state_id = hr_professional_tax_hdr.ptax_state_id)
                    LEFT JOIN hr_professional_tax_lines ON (hr_professional_tax_lines.ptax_id = hr_professional_tax_hdr.ptax_id)
                    WHERE
                        1 = 1";
        

	$result = \DB::select($SQL);
	return DataTables::of($result)->make(true);
		
		
	}
	/**Jqgrud Professional Tax load data End **/

	/** State name Duplicate check Start **/
	public function getCheckname(Request $request)
    {
       //dd('asd');
        $edit_id = $_GET['edit_id'];
  
        
        if($edit_id == ''){
            $department=DB::table('hr_professional_tax_hdr')->where('ptax_state_id','like',$_GET['ptax_state_id'])->get();
		}
        else
        {              
            $whereData = [['ptax_state_id','like',$_GET['state_id']],['ptax_id', '!=', $edit_id]];
            $department=DB::table('hr_professional_tax_hdr')->where($whereData)->get();
        }
       
        if(count($department)>0)
            return 1;
        else
            return 0;
        
        
	}
	/** State name Duplicate check End **/
}
