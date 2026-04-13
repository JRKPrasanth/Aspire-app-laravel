<?php
namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Company;
use App\Companylines;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CompanyController extends Controller
	{
    public function __construct(){
		
        $this->data=array();
		
        $this->table="m_company_t";
        
        $this->pageModule="company";
       $this->modelname = new Company();
         $this->model=new Company;
        $this->submodel=new Companylines;
        $this->data['pageFormtype']='ajax';
        $this->table="m_company_t";
		$this->subtable="m_company_line_t";
        $this->data['pageModule']=$this->pageModule;
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
                $this->data=array(
                    'pageModule'=> 'company',
                    'pageUrl'   =>  url($this->data['pageMethod']),
            'pageMethod'=>$this->data['pageMethod']
                  );
        $this->data['urlmenu']=$this->indexs();
    }



    public function companycheckname(Request $request)
    {
        
        $edit_id = $_GET['company_id'];
       
        if($edit_id == '')
        {
            
            $group=\DB::table('m_company_t')->where('company_name',$_GET['company_name'])->get();
            
        }
        else
        {
                        

            $whereData = [['company_name', $_GET['company_name']],['company_id', '!=', $edit_id]];

            $group=\DB::table('m_company_t')->where($whereData)->get();
        }


        if(count($group)>0)
            return 1;
        else
            return 0;
    }


     public function index()
    {

        $table = \DB::table('m_company_t')->get();
        $this->data['data'] = json_encode($table);
        $this->data['organization_name']=$this->jqgridselect('m_organizations_t','organization_id','organization_name');
        return view("company.table",$this->data);
    }
	
     public function  companyform($id="null"){
        if($id==0)
        {
            $table = \DB::table('m_company_t')->get(); 
            $this->data['data']=$table;
            // dd($table[0]);
            $this->data['row']= (object)array(); 
            $this->data['row']->company_id="";   
            $this->data['row']->company_name="";
            $this->data['row']->company_code="";
            $this->data['row']->website_address="";
            $this->data['row']->email_id="";
            $this->data['row']->contact_no="";
            $this->data['row']->gst_no="";
            $this->data['row']->tax_reg_no="";
            $this->data['row']->excise_registration_no="";
            $this->data['row']->cin_no="";
            $this->data['row']->pan_no="";
            $this->data['row']->active="";
            $this->data['pageMethod']="companycreate";
            $this->data['linedata'] = array();
            $this->data['location_id'] = $this->jCombo('m_location_t','location_id','location_name','');
			$org=\Session::get('organization');
            $this->data['row']->default_org_id= $this->jCombo('m_organizations_t','organization_id','organization_name',$org);
        	
		   }else{

           $table = \DB::table('m_company_t')->where('company_id',$id)->get();
            
            $this->data['data']=$table;
            $this->data['row']= (object)array(); 
            $this->data['row']->company_id=$table[0]->company_id;
            $this->data['row']->company_name=$table[0]->company_name;
            $this->data['row']->company_code=$table[0]->company_code;
            $this->data['row']->website_address=$table[0]->website_address;
            $this->data['row']->email_id=$table[0]->email_id;
            $this->data['row']->contact_no=$table[0]->contact_no;
            $this->data['row']->gst_no=$table[0]->gst_no;
            $this->data['row']->tax_reg_no=$table[0]->tax_reg_no;
            $this->data['row']->excise_registration_no=$table[0]->excise_registration_no;
            $this->data['row']->cin_no=$table[0]->cin_no;
            $this->data['row']->pan_no=$table[0]->pan_no;
            
            $this->data['row']->active=$table[0]->active;
            $this->data['pageMethod']="companyedit";
            
            $this->data['row']->default_org_id= $this->jCombo('m_organizations_t','organization_id','organization_name',$table[0]->default_org_id);
            $tablelines = \DB::table('m_company_line_t')->where('companyid',$id)->get();
			 $this->data['location_id'] = $this->jCombo('m_location_t','location_id','location_name','');
            $this->data['linedata'] = $tablelines;
            
        }

        if(count($this->data['linedata']) >= 1)
		{
		    foreach ($this->data['linedata'] as $key => $value)
		    {
		        $this->data['linedata'][$key]->location_id = $this->jCombo('m_location_t','location_id','location_name',$value->locationid);
		    }
		}
             
        return view("company.form",$this->data);

    } 

    public function companysave(Request $request)
    {
               
        $org=\Session::get('organization');

			$form = $request->all();
			$dataupload ="";
	        $form = $request->except([
        '_token','form_config','form_data_json','savestatus','submit_type',
        'choosefile','existing_file',
    ]);
			// Normalize "bulk_" keys once
			$form = $this->normalizeLineFormKeys($form);

			// Build header + lines
			$data = $this->validatePost($form, $this->table, 'header');
			$lines_data = $this->validatePost($form, $this->subtable, 'lines');
         // dd($lines_data);
        \DB::beginTransaction();
        try
        {
            $company = new Company();  
            $edit_id = $request->input('company_id');

            if($edit_id=="")
            {
                $company->company_name=$_POST['company_name'];
                $company->company_code=$_POST['company_code'];
                $company->website_address=$_POST['website_address'];
                $company->email_id=$_POST['email_id'];
                $company->contact_no=$_POST['contact_no'];
                $company->gst_no=$_POST['gst_no'];
                $company->tax_reg_no=$_POST['tax_reg_no'];
                $company->excise_registration_no=$_POST['excise_registration_no'];
                $company->cin_no=$_POST['cin_no'];
                $company->pan_no=$_POST['pan_no'];
                $company->default_org_id= $org;
                $company->active=$_POST['active'];

                $company->save();
                $id = $company->company_id;
              
            }else{
                $comp['company_name']=$_POST['company_name'];
                $comp['company_code']=$_POST['company_code'];
                $comp['website_address']=$_POST['website_address'];
                $comp['email_id'] =$_POST['email_id'];
                $comp['contact_no'] =$_POST['contact_no'];
                $comp['gst_no'] =$_POST['gst_no'];
                $comp['tax_reg_no'] =$_POST['tax_reg_no'];
                $comp['excise_registration_no'] =$_POST['excise_registration_no'];
                $comp['cin_no'] =$_POST['cin_no'];
                $comp['pan_no'] =$_POST['pan_no'];
                $comp['default_org_id']=$org;
                $comp['active']=$_POST['active'];

                $update = \DB::table('m_company_t')->where('company_id',$edit_id)->update($comp);
                $id = $edit_id;
            }
            
               /******************Company Lines save***************************/

            $company_line_id=$_POST['bulk_company_line_id'];

            $check = \DB::table('m_company_line_t')->whereIn('company_line_id',$company_line_id)->select('*')->get();
            $oldid = \DB::table('m_company_line_t')->where('companyid',$id)->get();
            
            if($oldid->isNotEmpty()){
								
                $existingId = array();
                $oldIds = array();
                $newIds = array();

                foreach ($oldid as $key=> $value){
                    $oldIds[]= $value->company_line_id;
                }
                foreach ($_POST['bulk_company_line_id'] as $val){
                    $newIds[]= $val;
                }
                $existingId=  array_replace($newIds, $oldIds);
                $oldcount=count($oldIds);
                $newcount=count($newIds);
                
                
                    $arraydiff=  array_diff($oldIds, $newIds);

                    foreach ($arraydiff as $key)
                    {
                        if (($key = array_search($key, $oldIds)) !== false)
                        {
                            unset($oldIds[$key]);
                        }
                    }
                    
                    foreach($arraydiff as $val)
                    {
                        \DB::table('m_company_line_t')->where('company_line_id',$val)->delete();
                    }

 
                    foreach ( $company_line_id as $key => $value) 
                    {
                    
                        $dataup1['companyid']=$id;
                        $dataup1['locationid']=$_POST['bulk_locationid'][$key];
                        $dataup1['line_no']=$_POST['bulk_line_no'][$key];
                        $dataup1['description']=$_POST['bulk_description'][$key];
                        $dataup1['created_by']=\Session::get('id');
                        $dataup1['last_updated_by']=\Session::get('id');
                        $dataup1['updated_at']=date('Y-m-d h:s:i');
                        $dataup1['created_at']=date('Y-m-d h:s:i');
                        $dataup1['organization_id']=\Session::get('organization');
                        $dataup1['location_id']=\Session::get('loc_id');
                        $dataup1['company_id']=\Session::get('companyid');
                
                        if($value != ""){
                            $company_line_id=$_POST['bulk_company_line_id'][$key];
                            \DB::table('m_company_line_t')->where('company_line_id',$value)->update($dataup1);
                        }else{
                            \DB::table('m_company_line_t')->insert($dataup1);
                        }
                    }           
            }else{

                foreach ( $company_line_id as $key => $value) {
                    $dataup1['companyid']=$id;
                    $dataup1['locationid']=$_POST['bulk_locationid'][$key];
                    $dataup1['line_no']=$_POST['bulk_line_no'][$key];
                    $dataup1['description']=$_POST['bulk_description'][$key];
                    $dataup1['created_by']=\Session::get('id');
                    $dataup1['last_updated_by']=\Session::get('id');
                    $dataup1['updated_at']=date('Y-m-d h:s:i');
                    $dataup1['created_at']=date('Y-m-d h:s:i');
                    $dataup1['organization_id']=\Session::get('organization');
                    $dataup1['location_id']=\Session::get('loc_id');
                    $dataup1['company_id']=\Session::get('companyid');

                    \DB::table('m_company_line_t')->insert($dataup1);
                }
            }
        
            /***********************Company Lines save end *********************/

             \DB::commit();

             
             return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id));
        }
        catch (\Illuminate\Database\QueryException $e)
        {
             $message = explode('(', $e->getMessage());
             $dbCode = rtrim($message[0], ']');
             $dbCode = trim($dbCode, '[');
             \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
                       
    }

	public function getCompanyData(Request $request)
	{
		//dd("hii");
    if ($request->ajax()) {
        $data = \DB::table('m_company_t')
        ->leftJoin('m_organizations_t', 'm_company_t.default_org_id', '=', 'm_organizations_t.organization_id')
            ->select([
				'm_company_t.company_id',
				'm_company_t.company_code',
				'm_company_t.company_name',
				'm_company_t.website_address',
				'm_company_t.gst_no',
				'm_organizations_t.organization_name'
            ]);


        return DataTables::of($data)
            ->addColumn('actions', function ($row) {
                return '<a href="/company/edit/'.$row->company_id.'" class="btn btn-sm btn-primary">Edit</a>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
	}
	
    function companyview($id=null)
	{

	    $headerdata = \DB::table('m_company_t as ih')
            ->leftjoin('m_organizations_t as org', 'ih.default_org_id', '=', 'org.organization_id')
            ->select('ih.*','org.organization_name')
		    ->where('ih.company_id',$id)
            ->get();
		// dd($headerdata);
	    $linesdata  = \DB::table('m_company_line_t')
            ->leftjoin('m_location_t', 'm_location_t.location_id', '=', 'm_company_line_t.locationid')
            ->select('m_company_line_t.description','m_location_t.location_name')
		    ->where('m_company_line_t.companyid',$id)
            ->get();
	    // dd($linesdata);
        $this->data['headerdata']=$headerdata[0];    
	    $this->data['linesdata']=$linesdata;

	    return view('company.view',$this->data);

	}

    public function companydelete(Request $request,$id=null)
    {

        $count = 0;
        $queryquote = \DB::table('tb_users')->where('company_id',$id)->count();
		
        if($queryquote >=1)
        {
            $count++;
        }
        if($count <= 0)
            {
            $query = \DB::table('m_company_t')->where('company_id',$id)->delete();
            $query = \DB::table('m_company_line_t')->where('companyid',$id)->delete();
            if($query)
            {
                return 0;
            }
            else
            {
                return 1;
            }
        }
        else{
            return 2;
        }
    }

}