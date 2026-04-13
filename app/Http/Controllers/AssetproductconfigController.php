<?php

namespace App\Http\Controllers;

use App\Product;
use App\Assetproductconfig;
use Illuminate\Http\Request;
use Redirect,DB;
use File;
use Yajra\DataTables\DataTables;

class AssetproductconfigController extends Controller
{
    
     public $module="assetproductconfig";

            public function __construct(){
               $this->data['urlmenu']=$this->indexs(); 
              $this->table="asset_product_config";
              $this->model=new Assetproductconfig;
              $this->data['pageModule']=\Request::route()->getName();
              $this->data['pageMethod']="assetproductconfig";
            }


/*Index Function For loading table*/
    public function assetproductconfigindex(Request $request)
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

$table = \DB::table('asset_product_config')->get();
$this->data['datas'] = $table;
		
    return view('assetproductconfig.table',$this->data);
   }
/*End*/


	public function assetproductconfiggridData()
    {
	 $wh='';

        
		$comp=\Session::get('companyid');
        
        
    $SQL = "select * from (SELECT
    asset_product_config.asset_config_id,
    asset_product_config.brand_name,
    asset_product_config.qty,
    asset_product_config.asset_number,
    asset_product_config.life_period,
    m_uom_codes_t.uom_code,
    asset_product_config.serial_number,
    asset_product_config.warrenty_from,
    asset_product_config.warrenty,
    asset_product_config.purchase_date,
    m_supplier_t.supplier_name,
    asset_product_config.replace_date,
    m_department_lines_t.sub_department_name,
    hr_employee_t.first_name,
    f_asset_types_t.asset_type_name,
    f_asset_category_t.asset_category_name,
    asset_product_config.asset_status,
    m_products_t.concatenated_product
FROM
    asset_product_config
LEFT JOIN m_products_t ON asset_product_config.product_id = m_products_t.product_id
LEFT JOIN m_uom_codes_t ON asset_product_config.uom = m_uom_codes_t.uom_code_id
LEFT JOIN m_supplier_t ON asset_product_config.supplier_id = m_supplier_t.supplier_id
LEFT JOIN m_department_lines_t ON asset_product_config.department = m_department_lines_t.department_line_id
LEFT JOIN hr_employee_t ON asset_product_config.assigned_to = hr_employee_t.employee_id
LEFT JOIN f_asset_types_t ON asset_product_config.asset_type = f_asset_types_t.asset_type_id
LEFT JOIN f_asset_category_t ON asset_product_config.asset_category = f_asset_category_t.asset_category_id
WHERE
    1 = 1 AND asset_product_config.company_id = $comp ) as v1 where 1=1 $wh ORDER by v1.asset_config_id DESC";


        $result = \DB::select( $SQL );
    
		return DataTables::of($result)->make(true);

    }
    
	
	
    /*Create Function*/
    public function assetproductconfigcreate($id=null)
    
    {
        
        $assetproduct=Assetproductconfig::find($id); 
        //dd($assetproduct);
        $this->data['pageModule']="assetproductconfig";     
         if(isset($id))
         {
        $this->data['return_url']=\Request::route()->getName();
        $this->data['edit_url']='update';
        $this->data['pagemode']='edit';    
        $this->data['assetproductdata']=$assetproduct; 
         
         $this->data['asset_config_id'] = $id; 
         $this->data['product_id']=$this->jcustomselect("m_products_t","product_id","concatenated_product",$assetproduct->product_id,' and (product_group_id=10 or product_group_id=13 or product_group_id=14 or product_group_id=15 or product_group_id=17)');
         $this->data['asset_number'] = $assetproduct->asset_number;   
         $this->data['brand_name'] = $this->jcustomselect('a_lookuplines_t','lookup_code','lookup_code',$assetproduct->brand_name,'and lookup_type="ASSET_BRAND"');
         $this->data['area'] = $this->jcustomselect('a_lookuplines_t','lookup_code','lookup_code',$assetproduct->area,'and lookup_type="ASSET_AREA"');            
        $this->data['qty'] = $assetproduct->qty;
        $this->data['serial_number'] = $assetproduct->serial_number;
        $this->data['uom'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code',$assetproduct->uom);
        $this->data['life_period'] = $assetproduct->life_period;
        $this->data['warrenty_from'] = $assetproduct->warrenty_from;
        $this->data['expiry_on'] = $assetproduct->expiry_on;
        $this->data['renewal_date'] = $assetproduct->renewal_date;
        $this->data['warrenty'] = $assetproduct->warrenty;
        $this->data['department'] = $this->jCombo('m_department_lines_t','department_line_id','sub_department_name',$assetproduct->department);
        $this->data['location'] = $assetproduct->location;
        $this->data['purchase_date'] =  $assetproduct->purchase_date;
        $this->data['po_number'] = $assetproduct->po_number;
        $this->data['capacity'] = $assetproduct->capacity;
        $this->data['remarks'] = $assetproduct->remarks;
        $this->data['po_invoice_number'] = $assetproduct->po_invoice_number;
        $this->data['supplier_id'] = $this->jCombo('m_supplier_t','supplier_id','supplier_name',$assetproduct->supplier_id);
        $this->data['replace_date'] = $assetproduct->replace_date;
        $this->data['assigned_to'] = $this->jCombo('hr_employee_t','employee_id','first_name',$assetproduct->assigned_to);
        $this->data['asset_type'] = $this->jCombo('f_asset_types_t','asset_type_id','asset_type_name',$assetproduct->asset_type);
        $this->data['asset_category'] = $this->jCombo('f_asset_category_t','asset_category_id','asset_category_name',$assetproduct->asset_category);
        $this->data['asset_status'] = $assetproduct->asset_status;
        $this->data['work_group'] = $assetproduct->work_group;
        $this->data['system_name'] = $assetproduct->system_name; 
        $this->data['operating_system'] = $assetproduct->operating_system; 
        $this->data['windows_key'] = $assetproduct->windows_key; 
        $this->data['processor_name'] = $assetproduct->processor_name; 
        $this->data['hdd_size'] = $assetproduct->hdd_size; 
        $this->data['expiry_on'] = $assetproduct->expiry_on;
        $this->data['renewal_date'] = $assetproduct->renewal_date;
        $this->data['ram_size'] = $assetproduct->ram_size; 
        $this->data['printer_name'] = $assetproduct->printer_name; 
        $this->data['monitor'] = $assetproduct->monitor; 
        $this->data['anti_virus'] = $assetproduct->anti_virus; 
        $this->data['ip_address'] = $assetproduct->ip_address; 
        $this->data['ms_office'] = $assetproduct->ms_office; 
        $this->data['ms_office_key'] = $assetproduct->ms_office_key; 
        $this->data['add_software'] = $assetproduct->add_software; 
        $this->data['mouse'] = $assetproduct->mouse; 
        $this->data['anydesk_number'] = $assetproduct->anydesk_number; 
        $this->data['anydesk_pw'] = $assetproduct->anydesk_pw; 
        $this->data['keyboard'] = $assetproduct->keyboard; 
        $this->data['network_type'] = $assetproduct->network_type; 
        $this->data['cd_dvd_drive'] = $assetproduct->cd_dvd_drive; 
        $this->data['login_type'] = $assetproduct->login_type; 
        $this->data['attachfile_name'] = $assetproduct->attachfile_name;
         
        $this->data['company_id'] = \Session::get('companyid'); 
        $this->data['location_id'] = \Session::get('location');
        $this->data['organization_id'] = \Session::get('organization');
        $this->data['created_by'] = \Session::get('id');
        $this->data['created_at'] = date('Y-m-d H:i:s');
        $this->data['updated_by'] = \Session::get('id');
        $this->data['updated_at'] = date('Y-m-d H:i:s');
            
             //dd($this->data);
    
        return view('assetproductconfig.form',$this->data);
        
            }
        else
        
        {
		$this->data['return_url']=\Request::route()->getName();


        $this->data['edit_url']='create';
        $sesdate=\Session::get('p_date_format');
        $originalDate=date('d-m-Y');
          //dd($_POST);
        $this->data['asset_config_id'] = '';  
        $this->data['product_id']=$this->jcustomselect("m_products_t","product_id","concatenated_product","",' and (product_group_id=10 or product_group_id=13 or product_group_id=14 or product_group_id=15 or product_group_id=17)');
        $this->data['asset_number'] = '';
        $this->data['po_number'] = '';
        $this->data['capacity'] = '';
        $this->data['remarks'] = '';
        $this->data['po_invoice_number'] = '';
        $this->data['brand_name'] = $this->jcustomselect('a_lookuplines_t','lookup_code','lookup_code','','and lookup_type="ASSET_BRAND"');
        $this->data['area'] = $this->jcustomselect('a_lookuplines_t','lookup_code','lookup_code','','and lookup_type="ASSET_AREA"');
        $this->data['qty'] = '';
        $this->data['serial_number'] = '';
        $this->data['uom'] = $this->jCombo('m_uom_codes_t','uom_code_id','uom_code','');
        $this->data['life_period'] = '';
        $this->data['warrenty_from'] = date($sesdate, strtotime($originalDate));
        $this->data['warrenty'] = '';
        $this->data['department'] = $this->jCombo('m_department_lines_t','department_line_id','sub_department_name','');
        $this->data['location'] = '';
        $this->data['purchase_date'] =  date($sesdate, strtotime($originalDate));
        $this->data['supplier_id'] = $this->jCombo('m_supplier_t','supplier_id','supplier_name','');
        $this->data['replace_date'] = date($sesdate, strtotime($originalDate));
        $this->data['assigned_to'] = $this->jCombo('hr_employee_t','employee_id','first_name','');
        $this->data['asset_type'] = $this->jCombo('f_asset_types_t','asset_type_id','asset_type_name','');
        $this->data['asset_category'] = $this->jCombo('f_asset_category_t','asset_category_id','asset_category_name','');
        $this->data['asset_status'] = '';
        $this->data['work_group'] = '';
        $this->data['system_name'] = ''; 
        $this->data['operating_system'] = ''; 
        $this->data['windows_key'] = ''; 
        $this->data['processor_name'] = ''; 
        $this->data['hdd_size'] = ''; 
        $this->data['expiry_on'] = '';
        $this->data['renewal_date'] = '';
        $this->data['ram_size'] = ''; 
        $this->data['printer_name'] = ''; 
        $this->data['monitor'] = ''; 
        $this->data['anti_virus'] = ''; 
        $this->data['ip_address'] = ''; 
        $this->data['ms_office'] = ''; 
        $this->data['ms_office_key'] = ''; 
        $this->data['add_software'] = ''; 
        $this->data['mouse'] = ''; 
        $this->data['anydesk_number'] = ''; 
        $this->data['anydesk_pw'] = ''; 
        $this->data['keyboard'] = ''; 
        $this->data['network_type'] = ''; 
        $this->data['cd_dvd_drive'] = ''; 
        $this->data['login_type'] = '';
        $this->data['attachfile_name'] = '';
        
        $this->data['company_id'] = \Session::get('companyid'); 
        $this->data['location_id'] = \Session::get('location');
        $this->data['organization_id'] = \Session::get('organization');
        $this->data['created_by'] = \Session::get('id');
        $this->data['created_at'] = date('Y-m-d H:i:s');
        $this->data['updated_by'] = \Session::get('id');
        $this->data['updated_at'] = date('Y-m-d H:i:s');
        
        $assetproducts=\DB::connection()->getSchemaBuilder()->getColumnListing("asset_product_config");

        $assetproduct=array();
        foreach($assetproducts as $key=>$val)
        {
            $assetproduct[$val]="";
        }

         $this->data['assetproductdata']=$assetproduct; 
         $this->data['assetproducts']=count($assetproducts); 
        //dd($this->data['productdata']);
            
            $this->data['pageMethod']='product';
            $this->data['pagemode']='create';

        return view('assetproductconfig.form',$this->data);
        }     
    }
/*End*/
  

      /*AssetProduct Save FUnction*/
    public function assetproductconfigsave(Request $request)
    { 
   //  dd($_POST);
   
        $edit_id = $request->input('asset_config_id');
		
		if($edit_id == '')
		{
		    
			$assetproduct = new Assetproductconfig();
			$assetproduct->product_id = $request->input('product_id');
			$assetproduct->asset_number = $request->input('asset_number');
			$assetproduct->po_number = $request->input('po_number');
			$assetproduct->capacity = $request->input('capacity');
			$assetproduct->remarks = $request->input('remarks');
			$assetproduct->po_invoice_number = $request->input('po_invoice_number');
			$assetproduct->brand_name = $request->input('brand_name');
			$assetproduct->area = $request->input('area');
			$assetproduct->qty = $request->input('qty');
			$assetproduct->serial_number = $request->input('serial_number');
			$assetproduct->uom = $request->input('uom');
			$assetproduct->life_period = $request->input('life_period');
			$assetproduct->warrenty_from = $request->input('warrenty_from');
			$assetproduct->warrenty = $request->input('warrenty');
			$assetproduct->department = $request->input('department');
			$assetproduct->purchase_date = $request->input('purchase_date');
			$assetproduct->supplier_id = $request->input('supplier_id');
			$assetproduct->replace_date = $request->input('replace_date');
			$assetproduct->assigned_to = $request->input('assigned_to');
			$assetproduct->asset_type = $request->input('asset_type');
			$assetproduct->asset_category = $request->input('asset_category');
			$assetproduct->asset_status = $request->input('asset_status');
			$assetproduct->location = $request->input('location');
			
			if($request->input('asset_category') == '16'){
			    
			$assetproduct->work_group = $request->input('work_group');
			$assetproduct->system_name = $request->input('system_name');
			$assetproduct->operating_system = $request->input('operating_system');
			$assetproduct->windows_key = $request->input('windows_key');
			$assetproduct->processor_name = $request->input('processor_name');
			$assetproduct->hdd_size = $request->input('hdd_size');
			$assetproduct->ram_size = $request->input('ram_size');
			$assetproduct->printer_name = $request->input('printer_name');
			$assetproduct->monitor = $request->input('monitor');
			$assetproduct->anti_virus = $request->input('anti_virus');
			$assetproduct->ip_address = $request->input('ip_address');
			$assetproduct->ms_office = $request->input('ms_office');
			$assetproduct->ms_office_key = $request->input('ms_office_key');
			$assetproduct->add_software = $request->input('add_software');
			$assetproduct->mouse = $request->input('mouse');
			$assetproduct->anydesk_number = $request->input('anydesk_number');
			$assetproduct->anydesk_pw = $request->input('anydesk_pw');
			$assetproduct->keyboard = $request->input('keyboard');
			$assetproduct->network_type = $request->input('network_type');
			$assetproduct->cd_dvd_drive = $request->input('cd_dvd_drive');
			$assetproduct->login_type = $request->input('login_type');
			}
			
			if($request->input('asset_category') == '12'){
			    
			$assetproduct->expiry_on = $request->input('expiry_on');
			$assetproduct->renewal_date = $request->input('renewal_date');
			
			}
			
			$assetproduct->created_at= date('Y-m-d h:i:s');
			$assetproduct->updated_at = date('Y-m-d h:i:s');
			$assetproduct->created_by = \Session::get('id');
			$assetproduct->updated_by = \Session::get('id');
			$assetproduct->company_id = \Session::get('companyid'); 
			$assetproduct->location_id = \Session::get('location');
			$assetproduct->organization_id = \Session::get('organization');
			
            if($request->hasfile('choosefile'))
            {
                $dataupload = [];
                foreach($request->file('choosefile') as $file)
                {
                    $name = $file->getClientOriginalName();
                    $file->move(public_path().'/Uploads/assets/', $name);
                    $dataupload[] = $name;
                }
                $attachfile_name = json_encode($dataupload);
                $assetproduct->attachfile_name = $attachfile_name;
            }    
			
			//dd($assetproduct);
			$assetproduct->save();
			$id = $assetproduct->asset_config_id;
            // auditlog
            $this->auditlog($id,"assetproduct","create",$_POST,"asset_product_config");
             $this->data['status']="success";
             $this->data['message']="Asset Product Config Saved Successfully";
             
       	return response()->json(array('status' => 'success', 'message' => 'Asset Product Config Saved Successfully'));
       	
		}
		else
		{
			$data['product_id'] = $request->input('product_id');
			$data['asset_number'] = $request->input('asset_number');
			$data['po_number'] = $request->input('po_number');
			$data['capacity'] = $request->input('capacity');
			$data['remarks'] = $request->input('remarks');
			$data['po_invoice_number'] = $request->input('po_invoice_number');
			$data['brand_name'] = $request->input('brand_name');
			$data['area'] = $request->input('area');
			$data['qty'] = $request->input('qty');
			$data['serial_number'] = $request->input('serial_number');
			$data['uom'] = $request->input('uom');
			$data['life_period'] = $request->input('life_period');
			$data['warrenty_from'] = $request->input('warrenty_from');
			$data['warrenty'] = $request->input('warrenty');
			$data['department'] = $request->input('department');
			$data['purchase_date'] = $request->input('purchase_date');
			$data['supplier_id'] = $request->input('supplier_id');
			$data['replace_date'] = $request->input('replace_date');
			$data['assigned_to'] = $request->input('assigned_to');
			$data['asset_type'] = $request->input('asset_type');
			$data['asset_category'] = $request->input('asset_category');
			$data['asset_status'] = $request->input('asset_status');
			$data['location'] = $request->input('location');
			
			if($request->input('asset_category') == '16'){
			    
            $data['work_group'] = $request->input('work_group');
            $data['system_name'] = $request->input('system_name');
            $data['operating_system'] = $request->input('operating_system');
            $data['windows_key'] = $request->input('windows_key');
            $data['processor_name'] = $request->input('processor_name');
            $data['hdd_size'] = $request->input('hdd_size');
            $data['ram_size'] = $request->input('ram_size');
            $data['printer_name'] = $request->input('printer_name');
            $data['monitor'] = $request->input('monitor');
            $data['anti_virus'] = $request->input('anti_virus');
            $data['ip_address'] = $request->input('ip_address');
            $data['ms_office'] = $request->input('ms_office');
            $data['ms_office_key'] = $request->input('ms_office_key');
            $data['add_software'] = $request->input('add_software');
            $data['mouse'] = $request->input('mouse');
            $data['anydesk_number'] = $request->input('anydesk_number');
            $data['anydesk_pw'] = $request->input('anydesk_pw');
            $data['keyboard'] = $request->input('keyboard');
            $data['network_type'] = $request->input('network_type');
            $data['cd_dvd_drive'] = $request->input('cd_dvd_drive');
            $data['login_type'] = $request->input('login_type');
			}
				if($request->input('asset_category') == '12'){

            $data['expiry_on'] = $request->input('expiry_on');
            $data['renewal_date'] = $request->input('renewal_date');     
            
				}
                    $existing_file = $request->input('existing_file');
                    $choose_file = $request->file('choosefile');
                    $existing_file = $existing_file ? explode(",", $existing_file) : [];
    
            if ((!is_array($choose_file) && !is_countable($choose_file)) && count($existing_file) > 0) {
                if (count($existing_file) == 1 && $existing_file[0] == '') {
                    \DB::update("UPDATE asset_product_config SET attachfile_name='' WHERE asset_config_id='$edit_id'");
                } else {
                    $get_attach = DB::table('asset_product_config')->where('asset_config_id', $edit_id)->first();
                    $attach_file = json_decode($get_attach->attachfile_name, true);
                    $attach_file1 = $attach_file ?? [];

        $array_diff = array_diff($attach_file1, $existing_file);
    
            if (count($array_diff) > 0) {
                foreach ($array_diff as $v) {
                    unlink(public_path() . '/Uploads/assets/' . $v);
                }
                $attachfile_name = json_encode($existing_file);
                \DB::update("UPDATE asset_product_config SET attachfile_name='$attachfile_name' WHERE asset_config_id='$edit_id'");
            }
        }
    } elseif (is_array($choose_file) && count($choose_file) > 0) {
        foreach ($choose_file as $file) {
            $name = $file->getClientOriginalName();
            $file->move(public_path() . '/Uploads/assets/', $name);
            $dataupload[] = $name;
        }
    
        $get_attach = DB::table('asset_product_config')->where('asset_config_id', $edit_id)->first();
        $attach_file = json_decode($get_attach->attachfile_name, true);
        $attach_file1 = $attach_file ?? [];
        $array_diff = array_diff($attach_file1, $existing_file);
    
        if (count($array_diff) > 0) {
            foreach ($array_diff as $v) {
                unlink(public_path() . '/Uploads/assets/' . $v);
            }
            $attachfile_name = array_merge($existing_file, $dataupload);
        } else {
            $attachfile_name = array_merge($attach_file1, $dataupload);
        }
        $attachfile_name = json_encode($attachfile_name);
        \DB::update("UPDATE asset_product_config SET attachfile_name='$attachfile_name' WHERE asset_config_id='$edit_id'");
    } elseif (is_array($choose_file) && count($choose_file) > 0) {
        foreach ($choose_file as $file) {
            $name = $file->getClientOriginalName();
            $file->move(public_path() . '/Uploads/assets/', $name);
            $dataupload[] = $name;
        }
        $attachfile_name = json_encode($dataupload);
        \DB::update("UPDATE asset_product_config SET attachfile_name='$attachfile_name' WHERE asset_config_id='$edit_id'");
        }
			
			$data['updated_at'] = date('Y-m-d h:i:s');
			$data['updated_by'] = \Session::get('id');
			$update = DB::table('asset_product_config')->where('asset_config_id',$edit_id)->update($data);
                           // auditlog
              $this->auditlog($edit_id,"assetproduct","edit",$_POST,"asset_product_config");
              $this->data['status']="success";
             $this->data['message']="Asset Product Config Updated Successfully";
			return response()->json(array('status' => 'success', 'message' => 'Asset Product Config Updated Successfully'));
		}

    }   
  
    /*End*/
    
    /*purpose : load acc,dis account,control acc from account setting */
    public function accountassign(){
        
        $sql = DB::table('f_product_accountsetting_t')->where([['product_group_id' ,$_GET['group']],['product_category_id',$_GET['category']],['product_subcategory_id',$_GET['sub']],['active','Yes'],['company_id',\Session::get('companyid')]])->get();

        $acc_code = array('acccode_id'=>'','disc_acccode'=>'','control_acccode'=>'');

        if(count($sql)>0){
            $acc_code['acccode_id'] = $sql[0]->product_acccode_id;
            $acc_code['control_acccode'] = $sql[0]->control_acccode_id;
            $acc_code['disc_acccode'] = $sql[0]->disc_acccode_id;
        }

        return $acc_code;
    }
    /*End*/

    /*View Function*/
    public function assetproductconfigshow($id=null)
    {
        $headerdata = \DB::select("SELECT
    asset_product_config.asset_config_id,
    asset_product_config.brand_name,
    asset_product_config.qty,
    asset_product_config.asset_number,
    m_uom_codes_t.uom_code,
    asset_product_config.life_period,
    asset_product_config.serial_number,
    asset_product_config.warrenty_from,
    asset_product_config.warrenty,
    asset_product_config.purchase_date,
    m_supplier_t.supplier_name,
    asset_product_config.replace_date,
    m_department_lines_t.sub_department_name,
    hr_employee_t.first_name,
    f_asset_types_t.asset_type_name,
    f_asset_category_t.asset_category_name,
    asset_product_config.asset_status,
    m_products_t.concatenated_product,
    m_products_t.product_alternate_name,
    m_products_t.active,
    m_products_t.product_code,
    f_gst_code_hdr_t.classification_code,
    m_products_t.product_status,
    m_product_groups_t.group_name,
    m_product_category_t.category_name,
    m_product_subcategory_t.subcategory_name
FROM
    asset_product_config
LEFT JOIN m_products_t ON asset_product_config.product_id = m_products_t.product_id
LEFT JOIN m_product_groups_t ON m_products_t.product_group_id = m_product_groups_t.product_group_id
LEFT JOIN m_product_category_t ON m_products_t.product_category_id = m_product_category_t.product_category_id
LEFT JOIN m_product_subcategory_t ON m_products_t.product_subcategory_id = m_product_subcategory_t.product_subcategory_id
LEFT JOIN f_gst_code_hdr_t ON m_products_t.defalut_hsn_code = f_gst_code_hdr_t.gst_code_hdr_id
LEFT JOIN m_uom_codes_t ON asset_product_config.uom = m_uom_codes_t.uom_code_id
LEFT JOIN m_supplier_t ON asset_product_config.supplier_id = m_supplier_t.supplier_id
LEFT JOIN m_department_lines_t ON asset_product_config.department = m_department_lines_t.department_line_id
LEFT JOIN hr_employee_t ON asset_product_config.assigned_to = hr_employee_t.employee_id
LEFT JOIN f_asset_types_t ON asset_product_config.asset_type = f_asset_types_t.asset_type_id
LEFT JOIN f_asset_category_t ON asset_product_config.asset_category = f_asset_category_t.asset_category_id
WHERE
    1 = 1 AND asset_product_config.asset_config_id = '$id'");
       $this->data['headerdata'] = $headerdata[0];
       
// dd($table);
        
        $this->data['pageMethod']='product';

        return view('assetproductconfig.view',$this->data);

    }    
    
    public function imageshow(Product $product,$id=null)
    {
        $this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("m_products_t");
        $this->data['values'] = $table = Product::find($id);
        $this->data['product_id'] =$id;
      
        
        
          $linesdata  = \DB::table('m_product_image_t')

      ->select('m_product_image_t.choosefile','m_product_image_t.image_date')
		  ->where('m_product_image_t.product_id',$id)->orderby('m_product_image_t.product_image_id','DESC')
            ->get(); 
        	  $this->data['linesdata']=$linesdata;

        $this->data['pageMethod']='product';

        return view('product.imageview',$this->data);

    } 
    
    
//     public function imageshow(Product $product,$id=null)
//     {
//       $this->data['columns']=\DB::connection()->getSchemaBuilder()->getColumnListing("m_products_t");
//         $this->data['values'] = $table = Product::find($id);
//         // dd($table);
//         $this->data['product_id'] =$id;
//         $this->data['group'] = $this->idname('group_name','m_product_groups_t','product_group_id',$table->product_group_id);
//         $this->data['batch_no'] = $this->idname('batch_no','m_products_t','product_id',$table->product_id);
//         $this->data['category'] = $this->idname('category_name','m_product_category_t','product_category_id',$table->product_category_id);
//         $this->data['sub_category'] = $this->idname('subcategory_name','m_product_subcategory_t','product_subcategory_id',$table->product_subcategory_id);
//         $this->data['product_type_id'] = $this->idname('product_type','m_product_type_t','product_type_id',$table->product_type_id);
//         $this->data['product_variant_id'] = $this->idname('product_variant_name','m_product_variants_t','product_variant_id',$table->product_variant_id);        
//         $this->data['product_packtype_id'] = $this->idname('product_pack_type_name','i_product_packs_types_t','product_packs_type_id',$table->product_packtype_id);
//         $this->data['product_pack_id'] = $this->idname('pack_name','i_product_packs','packing_id',$table->product_pack_id);
//         $this->data['primary_uom'] = $this->idname('uom_code','m_uom_codes_t','uom_code_id',$table->primary_uom_id);
//         $this->data['trxuom'] = $this->idname('uom_code','m_uom_codes_t','uom_code_id',$table->trx_uom_id);
//         $this->data['choosefile'] =$table->choosefile; 
//         if($table->hsn_code){
//             $sqpl =(explode(",",$table->hsn_code));
//             $hsn_code='';
//             foreach ($sqpl as $key => $value) {
//                 $hsn_code.=$this->idname('classification_code','f_gst_code_hdr_t','gst_code_hdr_id',$value).',';
            
//             }
//             $this->data['hsn_code']=rtrim($hsn_code,',');
//         }else{
//             $this->data['hsn_code']='';
//         }
        
//         $this->data['defalut_hsn_code'] = $this->idname('classification_code','f_gst_code_hdr_t','gst_code_hdr_id',$table->defalut_hsn_code);
//         $this->data['account_code'] = $this->idname('concatenated_segments','f_account_structure_t','f_account_structure_id',$table->account_code_id);
//         $this->data['disc_account_code'] = $this->idname('concatenated_segments','f_account_structure_t','f_account_structure_id',$table->disc_account_code);
//         $this->data['subinv'] = $this->idname('subinventory_name','m_subinventory_t','subinventory_id',$table->subinventory_id);
//         $this->data['locator'] = $this->idname('locator_name','m_sublocators_t','sublocator_id',$table->sublocator_id);
//         $this->data['created_by'] = $this->idname('username','tb_users','id',$table->created_by);
// // dd($table);
        
//         $this->data['pageMethod']='product';

//   $linesdata  = \DB::table('m_products_t')
//             ->leftjoin('m_product_image_t', 'm_product_image_t.product_id', '=', 'm_products_t.product_id')
            
//       ->select('m_products_t.concatenated_product','m_product_image_t.choosefile')
// 		  ->where('m_products_t.product_id',$id)
//             ->get(); 
//         	  $this->data['linesdata']=$linesdata;


//         return view('product.imageview',$this->data);

//     }
/*End*/


/*Delete Function*/
    public function assetproductconfigdelete($del_id)
    {
             $query = \DB::table('asset_product_config')->where('asset_config_id',$del_id)->delete();
             /**Auditlog**/
            $this->auditlog($del_id,"assetproduct","Delete","","asset_product_config");
        
        return 0;
    }
  /*End*/     
        

    /*Prodct Specification Function*/
    public function productspec($prdid=null,$id=null)
    {
        $product=Qualityproductspechdr::find($id);

        if($id!=0)
        { 
            $this->data['quality_product_specs_hdr_id']=$id;
            $this->data['product_id']=$this->jcombo("m_products_t","product_id","concatenated_product",$product->product_id);
            $lines=\DB::table('i_quality_product_specs_lines_t')->where('quality_product_specs_hdr_id',$id)->get();
            $this->data['linedata'] = $lines;
            foreach ($this->data['linedata'] as $key => $value) {
                $this->data['linedata'][$key]->spec_criteria = $this->data['spec_criteria'] = $this->jcustomselect("a_lookuplines_t","lookuplines_id","lookup_code",$value->spec_criteria,"and lookup_type='SPEC_CRITERIA'");
            }
        }
        else
        {
            $this->data['quality_product_specs_hdr_id']="";
            $this->data['product_id']=$this->jcombo("m_products_t","product_id","concatenated_product",$prdid);
            $this->data['spec_criteria']=$this->jcustomselect("a_lookuplines_t","lookuplines_id","lookup_code",'',"and lookup_type='SPEC_CRITERIA'");
            $this->data['linedata'] = array();            
       
        }     

        return view('product.specform',$this->data);
    }
    
/*End*/ 
    
    
    
    
/*Product Specification Save Function*/
     public function productspecsave(Request $request)
     { 
         $id='';
          //dd($_POST);
                $data = $this->validatePost($request->all(),$this->spechdrtable,'header');      
                $lines_data = $this->validatePost($request->all(),$this->speclinestable,'lines'); 
               // \DB::beginTransaction();
                try
                {
                     $id=$this->specmodel->insertRow($data);
                     $lid=$this->specsubmodel->subgridSave($lines_data,$id);
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

    /*End*/ 






public function companyassign($product=null,$company=null)
        {

    $product=explode(',',$product);

    $company=explode(',',$company);

    foreach($company as $ckey=>$cval)
    {
        //dd($cval,$product);

        foreach($product as $pkey=>$pval)
        {

            $data["product_id"]=$pval;
            $data["company_id"]=$cval;

        \DB::table('i_product_assigncompany_t')->insert($data);
        }


    }

        return 1;
        }




    public function companydetails()
    {


    $companydata = \DB::table('m_company_t')->select('*')->get();
        return $companydata;

    }

public function batchnoedit($id=null)
    {
        $batchno=$_GET['batchno'];
        $upd=\DB::table('m_products_t')->where('product_id',$id)->update(['batch_no'=>$batchno]);
        return 1;

        }

    public function companyprice()
    {
        $companypricedata=\DB::table('i_pricelist_hdr_t')->select('*')->get();
        return $companypricedata;

        }


/*Finding Primary Key Function*/
function findPrimarykey( $table )
    {
    $primaryKey = '';
    foreach(\DB::select("show columns from ".$table." where extra like '%auto_increment%'") as $key)
    {
    $primaryKey = $key->Field;
    }
    return $primaryKey;
    }
/*End*/


        /*Concatenate Linesdata Product Check Duplicate  Function*/
        public function concatenateproductcheck()
        {
            //dd($_GET);
            $result = $_GET['result'];
            $edit_id = $_GET['edit_id'];
            
            if(count($result)>0)
            {
                $list = array();
                foreach($result as $key => $value)
                {
                    
                    $query = DB::table('m_products_t')->where('concatenated_product',$value)->get();
                    if(count($query)>0)
                    {
                        $list['inarray'][] = $key;
                    }
                    $list['exist'][] = $key;
                }
                 
            }
        
           return $list;
        }
     /*End*/ 

     /*Concatenate Headerdata Product Check Duplicate  Function*/  
        public function concatenateproductchecksingle(Request $request)
        {

            $edit_id = $_GET['edit_id'];
            $concatenated_product = $_GET['concatenated_product'];
            if($edit_id == '')
                $department=DB::table('m_products_t')->where('concatenated_product',$concatenated_product)->get();
            else
            {
                $whereData = [['concatenated_product',$concatenated_product],['product_id', '!=', $edit_id]];

                $department=DB::table('m_products_t')->where($whereData)->get();
            }


            if(count($department)>0)
                return 1;
            else
                return 0;


        }
    /*End*/  

    /* Header Product Code Duplicate  Function*/  
        public function productcodecheck(Request $request)
        {

            $edit_id = $_GET['edit_id'];
            $product_code = $_GET['product_code'];
            if($edit_id == ''){
                $department=DB::table('m_products_t')->where('product_code',$product_code)->get();
            }else
            {
                $whereData = [['product_code',$product_code],['product_id', '!=', $edit_id]];

                $department=DB::table('m_products_t')->where($whereData)->get();
            }


            if(count($department)>0)
                return 1;
            else
                return 0;


        }
    /*End*/

    /* Linesdata Product Code Duplicate  Function*/
    public function productcodechecklines()
        {
            
            $result = $_GET['result'];
            $edit_id = $_GET['edit_id'];
            
            if(count($result)>0)
            {
                $list = array();
                foreach($result as $key => $value)
                {
                    
                    $query = DB::table('m_products_t')->where('product_code',$value)->get();
                    if(count($query)>0)
                    {
                        $list['inarray'][] = $key;
                    }
                    $list['exist'][] = $key;
                }
                 
            }
        
           return $list;
        }
        /*End*/
        

}
