<?php

namespace App\Http\Controllers;

use App\amc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use yajra\datatables\datatables;

class VendorController extends Controller
{
    public function __construct()
    {
         $this->data=array();
        $this->model=new amc();
        $this->data['pageFormtype']='ajax';
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['urlmenu']=$this->indexs(); 
    }



    public function vendorcheckname(Request $request)
    {
        
        $edit_id = $_GET['vendor_id'];
       
        if($edit_id == '')
        {
           // dd($_GET['vendor_name']);
            $group=\DB::table('m_amc_tb')->where('vendor_name',$_GET['vendor_name'])->get();
            
        }
        else
        {
                        

            $whereData = [['vendor_name', $_GET['vendor_name']],['vendor_id', '!=', $edit_id]];

            $group=\DB::table('m_amc_tb')->where($whereData)->get();
        }


        if(count($group)>0)
            return 1;
        else
            return 0;
    }


     public function index()
    {
        
        
        $table = \DB::table('m_amc_tb')->get();
        $this->data['data'] = json_encode($table);
       // $this->data['organization_name']=$this->jqgridselect('m_organizations_t','organization_id','organization_name');
        return view("vendor.table",$this->data);
    }
    public function  vendorform($id="null")
    {
        if($id==0)
        {
            $table = \DB::table('m_amc_tb')->get(); 
            $this->data['data']=$table;
            // dd($table[0]);
            $this->data['row']= (object)array(); 
            $this->data['row']->vendor_id="";   
            $this->data['row']->vendor_name="";
            $this->data['row']->email_id="";
            $this->data['row']->contact_no="";
            $this->data['row']->address="";
            $this->data['row']->active="";
            $this->data['pageMethod']="createvendor";
            //$this->data['linedata'] = array();
            // $this->data['location_id'] = $this->jCombo('m_location_t','location_id','location_name','');
            // // $org=\Session::get('organization');
            // $this->data['row']->default_org_id= $this->jCombo('m_organizations_t','organization_id','organization_name',$org);
        }
        else{

           $table = \DB::table('m_amc_tb')->where('vendor_id',$id)->get();
            
            $this->data['data']=$table;
            $this->data['row']= (object)array(); 
            $this->data['row']->vendor_id=$table[0]->vendor_id;
            $this->data['row']->vendor_name=$table[0]->vendor_name;
            $this->data['row']->address=$table[0]->address;
            $this->data['row']->email_id=$table[0]->email_id;
            $this->data['row']->contact_no=$table[0]->contact_no;
            $this->data['row']->active=$table[0]->active;
            $this->data['pageMethod']="vendoredit";
            
            // $this->data['row']->default_org_id= $this->jCombo('m_organizations_t','organization_id','organization_name',$table[0]->default_org_id);
            // $tablelines = \DB::table('m_company_line_t')->where('companyid',$id)->get();
            //  $this->data['location_id'] = $this->jCombo('m_location_t','location_id','location_name','');
            // $this->data['linedata'] = $tablelines;
            
        }
    //dd($tablelines);
        // if(count($this->data['linedata']) >= 1)
        // {
        //     foreach ($this->data['linedata'] as $key => $value)
        //     {
        //         $this->data['linedata'][$key]->location_id = $this->jCombo('m_location_t','location_id','location_name',$value->locationid);
        //     }
        // }
             
        return view("vendor.form",$this->data);

    } 

    public function vendorsave(Request $request)
    {
               
       // dd($request);
       // $data = $this->validatePost($request->all(),$this->table,'header');      

        \DB::beginTransaction();
        try
        {
           // dd("dsf");
            $amc = new amc();  
            $edit_id = $request->input('vendor_id');

            if($edit_id=="")
            {
                $amc->vendor_name=$_POST['vendor_name'];
                $amc->address=$_POST['address'];
              
                $amc->email_id=$_POST['email_id'];
                $amc->contact_no=$_POST['contact_no'];
 
                $amc->active=$_POST['active'];

                $amc->save();
                $id = $amc->vendor_id;
               \DB::commit();             
             return response()->json(array('status' => 'success', 'message' => 'Saved Successfully','id' => $id));
            }else{

                $amc1['vendor_name']=$_POST['vendor_name'];
                $amc1['address']=$_POST['address'];
                $amc1['email_id'] =$_POST['email_id'];
                $amc1['contact_no'] =$_POST['contact_no'];
                $amc1['active']=$_POST['active'];
                   // dd($amc1);
                $update = \DB::table('m_amc_tb')->where('vendor_id',$edit_id)->update($amc1);
                $id = $edit_id;

             \DB::commit();             
             return response()->json(array('status' => 'success', 'message' => 'Update Successfully','id' => $id));
        }
         }
        catch (\Illuminate\Database\QueryException $e)
        {
             $message = explode('(', $e->getMessage());
             $dbCode = rtrim($message[0], ']');
             $dbCode = trim($dbCode, '[');
 //dd($dbCode);
             \DB::rollback();
            return response()->json(array('status' => 'error', 'message' => 'DatabaseError:=>' . $dbCode . "\n"));
        }
                       
    }

public function getvendorData(Request $request)
{
    if ($request->ajax()) {
        $data = \DB::table('m_amc_tb')
            ->leftJoin('tb_users', 'tb_users.id', '=', 'm_amc_tb.created_by')
            ->select('m_amc_tb.*', 'tb_users.username');

        return DataTables::of($data)->make(true);
    }
}

    function vendorview($id=null)
    {

        $headerdata = \DB::table('m_amc_tb as ih')
            ->select('ih.*')
            ->where('ih.vendor_id',$id)
            ->get();
           // dd($headerdata);
        $this->data['headerdata']=$headerdata[0];    

        return view('vendor.view',$this->data);

    }

    public function vendordelete(Request $request,$id=null)
    {

        $count = 0;
        $queryquote = \DB::table('machine_hdr_t')->where('vendor_id',$id)->count();
        if($queryquote >=1)
        {
            $count++;
        }
        if($count <= 0)
            {
               // dd($id);
            $query = \DB::table('m_amc_tb')->where('vendor_id',$id)->delete();
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