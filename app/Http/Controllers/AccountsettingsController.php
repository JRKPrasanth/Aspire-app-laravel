<?php

namespace App\Http\Controllers;

use App\Accountsettings;
use Illuminate\Http\Request;

class AccountsettingsController extends Controller
{

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

         $this->data['urlmenu']=$this->indexs(); 
        $this->data['otherfreight_acccode_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
        $this->data['othertax_acccode_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
        $this->data['transport_acccode_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
        $this->data['insurance_acccode_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
        $this->data['packaging_acccode_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
        $this->data['unloading_acccode_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
        $this->data['inventory_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
        $this->data['cash_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
        $this->data['revcharge_acccode_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
        $this->data['service_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
        $this->data['roundoff_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
		
		$this->data['production'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
		$this->data['wip'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
		$this->data['scrab'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
		$this->data['rework'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
		$this->data['packingcontrol_accid'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
		$this->data['eletricity'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
		$this->data['labour'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
		
        //Sales Account
        $this->data['sample_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
        $this->data['cogs_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
        $this->data['domestic_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
        $this->data['export_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
        //HRMS Account
        $this->data['travelclaim_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
        $this->data['imprest_account_id'] = $this->jCombo('f_account_structure_t','f_account_structure_id','concatenated_segments','');
        //End HRMS Account
        
        return view('accountsettings.table',$this->data);
      
    }

  
	public function getaccountsetting(Request $request){
            
        $accdata=array();
        $type=$_GET['type'];
        $accdata = \DB::select("select * from  f_account_setting_t where module_name='$type'");
        
        if(!empty($accdata)){
			return $accdata;
		}else{
			return 0;
		}
    }
      

    
public function accountsettingsave(Request $request){
    
      	$type=$_POST['type'];
        
        if($type=="purchaseinvoice"){
            $otherfreight_acccode_id=$_POST['otherfreight_acccode_id'][0];
        $othertax_acccode_id=$_POST['othertax_acccode_id'][0];
        $transport_acccode_id=$_POST['transport_acccode_id'][0];
        $insurance_acccode_id=$_POST['insurance_acccode_id'][0];
        $packaging_acccode_id=$_POST['packaging_acccode_id'][0];
        $unloading_acccode_id=$_POST['unloading_acccode_id'][0];
        \DB::update("UPDATE f_account_setting_t SET otherfreight_acccode_id = '$otherfreight_acccode_id',othertax_acccode_id='$othertax_acccode_id',transport_acccode_id='$transport_acccode_id',insurance_acccode_id='$insurance_acccode_id',packaging_acccode_id='$packaging_acccode_id',unloading_acccode_id='$unloading_acccode_id' WHERE  module_name='$type'");    
        }
        else if($type=="salesinvoice"){
            $otherfreight_acccode_id=$_POST['otherfreight_acccode_id'][0];
            $othertax_acccode_id=$_POST['othertax_acccode_id'][0];
            $transport_acccode_id=$_POST['transport_acccode_id'][0];
            $insurance_acccode_id=$_POST['insurance_acccode_id'][0];
            $packaging_acccode_id=$_POST['packaging_acccode_id'][0];
        
        \DB::update("UPDATE f_account_setting_t SET otherfreight_acccode_id = '$otherfreight_acccode_id',othertax_acccode_id='$othertax_acccode_id',transport_acccode_id='$transport_acccode_id',insurance_acccode_id='$insurance_acccode_id',packaging_acccode_id='$packaging_acccode_id' WHERE  module_name='$type'");    
        }
        else if($type=="srn"){
              $service_account_id=$_POST['service_account_id'][0];
            \DB::update("UPDATE f_account_setting_t SET service_account_id='$service_account_id' WHERE  module_name='$type'");
        }
         else if($type=="grn"){
              $inventory_account_id=$_POST['inventory_account_id'][0];
            \DB::update("UPDATE f_account_setting_t SET inventory_account_id='$inventory_account_id' WHERE  module_name='$type'");
        }
        else if($type=="cashaccount"){
            $cash_account_id=$_POST['cash_account_id'][0];
            \DB::update("UPDATE f_account_setting_t SET cash_account_id='$cash_account_id' WHERE  module_name='$type'");
        }
         else if($type=="roundoff"){
            $roundoff_account_id=$_POST['roundoff_account_id'][0];
            \DB::update("UPDATE f_account_setting_t SET roundoff_account_id='$roundoff_account_id' WHERE  module_name='$type'");
        }
        
        else if($type=="salesaccount"){
            $sample_account_id=$_POST['sample_account_id'][0];
            $export_account_id=$_POST['export_account_id'][0];
            $domestic_account_id=$_POST['domestic_account_id'][0];
            $cogs_account_id=$_POST['cogs_account_id'][0];
            \DB::update("UPDATE f_account_setting_t SET sample_account_id='$sample_account_id',export_account_id='$export_account_id',domestic_account_id='$domestic_account_id',cogs_account_id='$cogs_account_id' WHERE  module_name='$type'");
        } 
        else if($type=="production"){

        $production=$_POST['production'][0];
        $wip=$_POST['wip'][0];
        $scrab=$_POST['scrab'][0];
        $rework=$_POST['rework'][0];
        $labour=$_POST['labour'][0];
        $eletricity=$_POST['eletricity'][0];
 $packingcontrol_accid=$_POST['packingcontrol_accid'][0];
        \DB::update("UPDATE f_account_setting_t SET production = '$production',wip='$wip',scrab='$scrab',rework='$rework',labour='$labour',eletricity='$eletricity',packingcontrol_accid='$packingcontrol_accid' WHERE  module_name='$type'");
        }
        else if($type=="hrms"){

        $travelclaim_account_id=$_POST['travelclaim_account_id'][0];
        $imprest_account_id=$_POST['imprest_account_id'][0];
       

        \DB::update("UPDATE f_account_setting_t SET travelclaim_account_id = '$travelclaim_account_id',imprest_account_id='$imprest_account_id' WHERE  module_name='$type'");
        }
        else{
            $revcharge_acccode_id=$_POST['revcharge_acccode_id'][0];
            \DB::update("UPDATE f_account_setting_t SET revcharge_acccode_id='$revcharge_acccode_id' WHERE  module_name='$type'");
            
        }
      	
        return redirect('accountsettings');
        return response()->json(array('status' => 'success', 'message' => 'Account Settings Saved Successfully'));
	}
  
 
}
