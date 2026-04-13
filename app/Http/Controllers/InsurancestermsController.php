<?php

namespace App\Http\Controllers;

use App\Insurancesterms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Yajra\DataTables\DataTables;


class InsurancestermsController extends Controller
{
    public function __construct()
    {
        $this->data=array();
        $this->data['urlmenu']=$this->indexs(); 
        $this->model=new Insurancesterms();
        $this->data['pageFormtype']='ajax';
        $this->data['pageMethod']=\Request::route()->getName();
    }
   /*Karthigaa Purpose for Index Page*/ 
    public function index()
    {
		$this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
		 $this->data['urlname'] =\Request::route()->getName();
            if($this->data['urlname'] == "purchaseinsuranceterms"){
                $this->data['insurance_terms_type'] = "Purchase";}
            else{
                $this->data['insurance_terms_type'] = "Sales";}
                $table = \DB::table('m_insurance_terms_t')->get();
                $this->data['datas']=json_encode($table);
                $this->data['pageMethod']="insurancesterms";
        return view('insurancesterms.form',$this->data);
    }
    
// table data
	
 public function getinsurancetermsgrid($type=null)
    {
        $wh='';

        $compy=\Session::get('companyid');
        $groupname=\Session::get('groupname');
        if($groupname=='1' || $groupname=='Admin'){
        $wh.='and  m_insurance_terms_t.company_id='.$compy;  
        }else{
        $wh.='and  m_insurance_terms_t.company_id='.$compy;  
        }   

		
       $SQL = "SELECT m_insurance_terms_t.*,tb_users.first_name,tb_users.employee_id,tb_users.id as created_id FROM m_insurance_terms_t  left join tb_users on tb_users.id =m_insurance_terms_t.created_by where 1=1 $wh order by m_insurance_terms_t.insurance_term_id DESC";

        $result = \DB::select( $SQL );
	 
		return DataTables::of($result)->make(true);
	 
    }
    

    public function save(Request $request)
    {
         $insuranceterms=new Insurancesterms(); 
        $edit_id = $request->input('insurance_term_id');
        if($edit_id == '')
        { 
            $insuranceterms->insurance_term_name=$_POST['insurance_term_name'];
            $insuranceterms->description=$_POST['description'];
            $insuranceterms->active=$_POST['active'];
            $insuranceterms->location_id='1';
            $insuranceterms->organization_id=\Session::get('organization');
            $insuranceterms->company_id=\Session::get('companyid');
            $insuranceterms->created_by=\Session::get('id');
            $insuranceterms->last_updated_by=\Session::get('id');
            $insuranceterms->save();
             $edit_id= DB::getPdo()->lastInsertId();
            $action="Create";
            /**Auditlog**/
            $this->auditlog($edit_id,"insuranceterms",$action,$_POST,"m_insurance_terms_t");
            return response()->json(array('status' => 'success', 'message' => 'Insurance Terms Saved Successfully','id'=>$edit_id));
        }
         else{
            $action="Edit";
            $edit_id=$_POST['insurance_term_id'];
            $_POST['last_updated_by']=\Session::get('id');
            Insurancesterms::find($edit_id)->update($_POST); 
                /**Auditlog**/
                    $this->auditlog($edit_id,"insuranceterms",$action,$_POST,"m_insurance_terms_t");
            return response()->json(array('status' => 'success', 'message' => 'Insurance Terms Updated Successfully','id'=>$edit_id));
        }
        
    }
	
	
 // Purpose for Create Function
    public function create(Request $request)
    {
        // restrict illegal menu entry purpose - VIGNESH M

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

		$this->data['created_by'] = $this->jCombo('tb_users','id','username',\Session::get('id'));
        $table = \DB::table('m_insurance_terms_t')->get();
        $this->data['datas'] = json_encode($table);
        $this->data['urlname'] =\Request::route()->getName();
         if($this->data['urlname'] == "purchaseinsuranceterms"){
                $this->data['insurance_terms_type'] = "Purchase";}
            else{
                $this->data['insurance_terms_type'] = "Sales";}
     
        return view('insurancesterms.form',$this->data);
    }
 
 /*Karthigaa Purpose for Duplicate Validation Function*/
    public function getCheckname(Request $request)
    { 
        $ins_term_id = $_GET['edit_id']; //dd($payment_term_id);
    	
        if($ins_term_id == ''){
			$whereData = [['insurance_term_name', $_GET['insurance_term_name']]];
           $insurance=DB::table('m_insurance_terms_t')->where($whereData)->get();
        } else {
            $whereData = [['insurance_term_name', $_GET['insurance_term_name']],['insurance_term_id', '!=', $ins_term_id]];
            $insurance=DB::table('m_insurance_terms_t')->where($whereData)->get();
        }
        
        if(count($insurance)>0)
            return 1;
        else
            return 0;
    }

  /*Karthigaa Purpose for Delete Function*/ 
    public function getRemove(Request $request,$id=null)
    {
	$column = array('insurance_term_id','insurance_term_id','insurance_term_id','insurance_term_id');
        $table = array('m_supplier_t','p_po_hdr_t','p_quotation_hdr_t','p_po_invoice_hdr_t');
        for($i=0; $i<count($table); $i++)
        {
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$id)->get();
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }

        if($j==0)
        {
             $query = \DB::table('m_insurance_terms_t')->where('insurance_term_id',$id)->delete();
        }
        /**Auditlog**/
        $action = "Delete";
        $this->auditlog($id,"insurancesterms",$action,$id,"m_insurance_terms_t");

		return $j;
    }
    /*Karthigaa Purpose for Used Data Should Not Allow to Edit Function*/
    public function getedit(Request $request,$id=null)
    {
	$column = array('insurance_term_id','insurance_term_id','insurance_term_id','insurance_term_id');
        $table = array('m_supplier_t','p_po_hdr_t','p_quotation_hdr_t','p_po_invoice_hdr_t');
        for($i=0; $i<count($table); $i++)
        {
            $j=0;
            $query = \DB::table($table[$i])->where($column[$i],$id)->get();
            if(count($query)>0)
            {
                $j=1;
                break;
            }
        }


		return $j;
    }


 


  

}
