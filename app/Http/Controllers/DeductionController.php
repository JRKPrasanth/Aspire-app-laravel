<?php

namespace App\Http\Controllers;

use App\Deduction;
use Illuminate\Http\Request;
use DB;
use DateTime;
use Yajra\DataTables\DataTables;

class DeductionController extends Controller
{
    public function __construct()
	{
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs(); 
	 }
    public function index()
    {
        //
        $this->data['urlmenu']=$this->indexs();
    }

    /***** Deduction Look UP load Component Start ***/
    public function create(Request $request)
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
        $this->data['deduction_type']=$this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','','AND lookup_type="deduction_type"');
        //$this->data['po_pricelist_id'] = $this->jcustomselect('i_pricelist_hdr_t','pricelist_hdr_id','pricelist_name','',' and price_list_type="Purchase"');
        return view('deductions.form',$this->data);
    }
    /***** Deduction Look UP load Component End ***/

    /***** Deduction Look UP load Component validation check Start ***/
    public function componenetcheckdedcution()
    {
              $edit_id = $_GET['edit_id'];
              $component = $_GET['component'];
             
        if($edit_id == ''){
            $esi=DB::table('m_emp_esi')->where('components','=',$component)->get();
		}
        else
        {              
            $whereData = [['components','=',$component],['id', '!=', $edit_id]];
            $esi=DB::table('m_emp_esi')->where($whereData)->get();
        }
       
        if(count($esi)>0)
            return 1;
        else
            return 0;
    }
    /***** Deduction Look UP load Component validation check End ***/
    
    /***** Deduction Save data Start ***/
    public function store(Request $request)
    {
        
        $edit_id = $request->input('edit_id');
        if($edit_id == '')
        {
            $deduction = new Deduction();
            $deduction->components = $request->input('component');
            $deduction->limitto = $request->input('limitto');
            $deduction->limit = 0;
            $deduction->date =$request->input('date') ;
            $deduction->employeer_contribute = $request->input('employeer_contribute');
            $deduction->employee_type = $request->input('employee_type');
            $deduction->company_contribute = $request->input('company_contribute');  
            $deduction->company_contribute1 = $request->input('company_contribute1');  
            $deduction->company_id =\Session::get('companyid');
            $deduction->location_id ="1";
            $deduction->organization_id =\Session::get('organization');
            $deduction->created_by =\Session::get('id');
            $deduction->save();
            $id = $deduction->id;
            // auditlog
            $this->auditlog($id,"Deduction","Create",$deduction,"m_emp_esi");
            return 1;
        }
        else
        {
           
            $deduction  = Deduction::findOrFail($edit_id);
            $input = $request->all();
            $deduction->fill($input)->save();
            // auditlog
            $this->auditlog($edit_id,"Deduction","Update",$_POST,"m_emp_esi");
            return 2;

        }
    }
    /***** Deduction Save data End ***/
    
  

   /***** Deduction Delete data Start ***/
    public function destroy($id=null)
    {

       
        
        $j = 0;
        if($j==0)
        {
            $query = DB::table('m_emp_esi')->where('id',$id)->delete();
            // auditlog
            $this->auditlog($id,"Deduction","Create",$query,"m_emp_esi");
        }
       
        if($j == 1)
            return 1;
        else if($j == 0)
            return 2;
        else if($j == 3)
            return 3;
    }

	
    public function deductiongriddata()
    {
		
    $company_id = \Session::get('companyid');

        
	$SQL = "SELECT  m_emp_esi.employee_type,look.lookup_meaning as employee_type_name,m_emp_esi.company_contribute1,m_emp_esi.components,m_emp_esi.id,m_emp_esi.limitto,m_emp_esi.date,m_emp_esi.employeer_contribute,m_emp_esi.company_contribute,a_lookuplines_t.lookup_meaning from m_emp_esi left join a_lookuplines_t on a_lookuplines_t.lookuplines_id=m_emp_esi.components left join a_lookuplines_t  as look on look.lookuplines_id=m_emp_esi.employee_type where 1=1 and m_emp_esi.company_id = $company_id"; 
        
		$result = \DB::select($SQL);
        return DataTables::of($result)->make(true);

}

	
        public function getComponents($component_id)
        {
           
            $result=DB::table('a_lookuplines_t')->where('lookuplines_id',$component_id)->where('lookup_type','deduction_type')->get();
           
            if(count($result)>0)
            {
                 return $result[0]->lookup_code;
            }
                 
        }
        
        /***** Deduction Component data End ***/


        /***** Deduction Check Category data Start ***/
        public function Checkcategory(Request $request)
        {
            $edit_id = $_GET['edit_id'];
            $component_name = $_GET['component_name'];
             $employee_type = $_GET['employee_type'];
       
            $componentname = '';     
            if($edit_id == ''){
                if($employee_type!=''){
                $whereData = [['components',$component_name],['employee_type',$employee_type]];
                }
                else{
                  $whereData = [['components',$component_name]];  
                }
                $result=DB::table('m_emp_esi')->where($whereData)->get();
                if(count($result)>0){
                    $componentname_result  = $this->getComponents($component_name);
                    
                    $componentname = $componentname_result;
                }
            }
            else
            {
               
                 if($employee_type!=''){
              $whereData = [['components',$component_name],['employee_type',$employee_type],['id', '!=', $edit_id]];
                }
                else{
                 $whereData = [['components',$component_name],['id', '!=', $edit_id]]; 
                }
                
                $result=DB::table('m_emp_esi')->where($whereData)->get();
                if(count($result)>0){
                    $componentname_result  = $this->getComponents($component_name);
                    $componentname = $componentname_result;
                }
                
            }
            if(count($result)>0)
                return array(1,$componentname);
            else
                return array(0,$componentname);
           
        }
        /***** Deduction Check Category data End ***/
}
