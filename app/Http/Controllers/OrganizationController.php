<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Organization;
use Illuminate\Http\Request;


class OrganizationController extends Controller
{
     public function __construct(){
        $this->data=array();
        $this->table="m_organizations_t";
        
        $this->pageModule="organization";
        $this->model=new Organization;
        
        $this->data['pageModule']=$this->pageModule;
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
                $this->data=array(
                    'pageModule'=> 'organization',
                    'pageUrl'   =>  url($this->data['pageMethod']),
            'pageMethod'=>$this->data['pageMethod']
                  );
        $this->modelname = new Organization();
        $this->data['pageFormtype']='ajax';
        $this->data['urlmenu']=$this->indexs(); 
    }
    public function index()
    {
		//dd("hii");
         $table = \DB::table('m_organizations_t')->get();   
        $this->data['data']=$table;
        $this->data['row']= (object)array(); 
        $this->data['row']->organization_id="";   
        $this->data['row']->organization_name="";
        $this->data['row']->organization_code="";
        $this->data['row']->organization_type=$this->jcustomselect('a_lookuplines_t','lookuplines_id','lookup_code','','AND lookup_type="ORGANIZATION_TYPES"');
        $this->data['row']->company_id="";
        $this->data['row']->location_id="";
        $this->data['row']->active="";
        $this->data['organization_type']=$this->jqgridcustselect('a_lookuplines_t','lookuplines_id','lookup_code','AND lookup_type="ORGANIZATION_TYPES"');
        $this->data['company_id']=$this->jqgridselect('m_company_t','company_id','company_name');
        $this->data['location_id']=$this->jqgridselect('m_location_t','location_id','location_name');
        $this->data['active']=":--Please Select--;YES:YES;NO:NO";
        

        return view("organization.form",$this->data);
    }

    public function orgcheckname(Request $request)
    {
       $edit_id = $_GET['organization_id'];
        if($edit_id == '')
        {
            
            $group=\DB::table('m_organizations_t')->where('organization_name',$_GET['organization_name'])->get();
            
        }
        else
        {
                        

            $whereData = [['organization_name', $_GET['organization_name']],['organization_id', '!=', $edit_id]];

            $group=\DB::table('m_organizations_t')->where($whereData)->get();
        }


        if(count($group)>0)
            return 1;
        else
            return 0;
    }

    public function organizationsave(Request $request)
    {
      
        $organization = new Organization(); 
        $edit_id = $request->input('organization_id');
        if($edit_id=="")
        {
            $organization->organization_code=$_POST['organization_code'];
            $organization->organization_name=$_POST['organization_name'];
            $organization->location_id=\Session::get('location');
            $organization->company_id=\Session::get('companyid');
            $organization->organization_type=$_POST['organization_type'];
            $organization->active=$_POST['active'];
            $organization->save();
            
            return response()->json(array('status' => 'success', 'message' => 'Organization Saved Successfully!!','id'=>$edit_id));
        }else{
            $org['organization_code']=$_POST['organization_code'];
            $org['organization_name']=$_POST['organization_name'];
            $org['location_id']=\Session::get('location');
            $org['company_id']=\Session::get('location');
            $org['organization_type']=$_POST['organization_type'];
            $org['active']=$_POST['active'];

            
            $update = \DB::table('m_organizations_t')->where('organization_id',$edit_id)->update($org);

            return response()->json(array('status' => 'success', 'message' => 'Organization Updated Successfully','id'=>$edit_id));
        }
       
    }

public function getOrganizationData(Request $request)
{

    if ($request->ajax()) {
        $data = \DB::table('m_organizations_t')
            ->leftJoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'm_organizations_t.organization_type')
            ->select([
                'm_organizations_t.organization_id',
                'm_organizations_t.organization_code',
                'm_organizations_t.organization_name',
                'm_organizations_t.active',
                'a_lookuplines_t.lookup_code'
            ]);

        return DataTables::of($data)
            ->addColumn('actions', function ($row) {
                return '<a href="/organization/edit/'.$row->organization_id.'" class="btn btn-sm btn-primary">Edit</a>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}


  
public function organizationdelete(Request $request, $id = null)
{

    try {
        // Check if the organization is used in users table
        $isUsed = \DB::table('tb_users')->where('org_id', $id)->exists();

        if ($isUsed) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete. Record is in use.'
            ], 409); // 409 Conflict
        }

        // Try to delete
        $deleted = \DB::table('m_organizations_t')->where('organization_id', $id)->delete();

        if ($deleted) {
            return response()->json([
                'status' => 'success',
                'message' => 'Deleted successfully.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Delete failed. Please try again.'
            ], 500);
        }

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
}


  
}
