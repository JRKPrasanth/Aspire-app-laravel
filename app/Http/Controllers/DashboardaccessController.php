<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class DashboardaccessController extends Controller
{
       public $module="Dashboardaccess";
	public function __construct()
	{
		$this->data=array(
                    'pageModule'=> 'Dashboardaccess',
                    'pageUrl'	=>  url('Dashboardaccess')
                  );
                $this->data['urlmenu']=$this->indexs(); 
		$this->pageModule="Dashboardaccess";

		$this->data['pageModule']=$this->pageModule;
		$this->data['pageMethod']=\Request::route()->getName();
		$this->data['pageFormtype']='ajax';
	}

   public function index(){

      return view('dashboardaccess.table',$this->data);
   }
    
	// Table Data
		public function DashboardData(Request $request)
{
    if ($request->ajax()) {
        $query = \DB::table('a_dashboard_access_t')
            ->join('tb_users', 'a_dashboard_access_t.user_id', '=', 'tb_users.id')
            ->select('a_dashboard_access_t.a_dashboard_access_id as id', 'tb_users.username','tb_users.first_name');

        return DataTables::of($query)->make(true);
    }
}
	
  public function create($id=null)
    {
  
        if($id != null && $id != 0)
        {
        $headhtml=""; 
        $saleshtml=""; 
        $invhtml=""; 
        $prdhtml=""; 
        $ophtml=""; 
        $acchtml="";
        $levhtml="";
        $dashtml="";
        
      $access_data=  \DB::select("SELECT * FROM `a_dashboard_access_t` where a_dashboard_access_id='$id'");
      $access=json_decode($access_data[0]->dashboard_option);
      //dd($access);
        
     $purchase = array("PO Draft","PO Approval","GRN Pending","PO Quality Pending","Invoice Pending","Invoice Draft","PO Invoice Approval","Product Approval","BOM Approval","Supplier Approval","Purchase Pricelist Approval","Purchase By Supplier","Purchase By Products","Purchase By Products Qty","GRN Overdue","Purchase Invoice Overdue","Purchase Order Piechart","Purchase Invoice Piechart");
     $sales = array("SO Draft","SO Approval","Dispatch Draft","S Invoice Pending","SO Invoice Draft","SO Invoice Approval","Customer Approval","Sales Pricelist Approval","SO Return Approval","Ship Confirm Pending","Sales By Customer","Sales By Products","Dispatch Overdue","Sales Invoice Overdue","Sales Return Direct","Sales Return Invoice","Sales Return From Invoice","Sales Return Overall");
  // subinventory menu
     $inventory=array("Raw Materials","Packing Materials","Semi Finished Goods","Finished Goods","Sample Products","Promotional Items","ROL FG Details","ROL RM Details","ROL PM Details","Subinventory Transfer Receive-Finished Goods","Subinventory Transfer Receive-Semi Goods","Subinventory Transfer Receive-Sample Goods","Subinventory Transfer Receive-Store","Subinventory Transfer Receive-accessories","Subinventory Transfer Receive-lab");
     $operation=array("Operation Material Issue Pending","Operation Material Acknowledgement Pending","Operation Completion Pending","Operation Quality Pending","Operation Quality Approve Pending","Operation Job Card Details","Operation Open Jobcard","Operation Product Qty");
     $production=array("Production Material Issue Pending","Production Material Acknowledgement Pending","Production Completion Pending","Production Quality Pending","Production Quality Approve Pending","Production Job Card Details","Production Product Qty");
     $accounts=array("Expense Draft","Expense Approval","Advance Set-Off PO","Payment Batch Pending","Payment Overdue Pending","Payment BRS Pending","Receipt BRS Pending","Consumables","Consumables Approval Pending");
     $leave=array("CL Balance","EL Balance","Comp-Off Balance","Leave Approve Pending","Leave Approval");
     $dashboard=array("More Dashboards","HRMS Dashboard","Accounts Dashboard","Purchase Dashboard","Operation Dashboard","Maintenance Dashboard","Production Dashboard","Quality Dashboard");
       
    $this->data['user_id']=$this->jCombo('tb_users','id','username',$access_data[0]->user_id);
    $this->data['a_dashboard_access_id']=$id;
        
 
 $headhtml .="<div class='col-md-3 headmenu'>  <div class='table-responsive'> <table class='table myTable'> <thead><tr><th>sno</th><th>Purchase Menu Name</th><th><input type='checkbox' class='head check_head' name='checkall'  value='po'></th></tr> </thead> <tbody>";
 $saleshtml .="<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table salesTable'> <thead><tr><th>sno</th><th>Sales Menu Name</th><th><input type='checkbox' class='sales check_head' name='salescheckall'  value='so'></th></tr> </thead> <tbody>";
 $invhtml .="<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table invTable'> <thead><tr><th>sno</th><th>Inventory Menu Name</th><th><input type='checkbox' class='inventory check_head' name='invcheckall'  value='inv'></th></tr> </thead> <tbody>";
 $ophtml .="<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table opTable'> <thead><tr><th>sno</th><th>Operation Menu Name</th><th><input type='checkbox' class='operation check_head' name='opcheckall'  value='op'></th></tr> </thead> <tbody>";
 $prdhtml .="<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table prdTable'> <thead><tr><th>sno</th><th>Production Menu Name</th><th><input type='checkbox' class='production check_head' name='prdcheckall'  value='prd'></th></tr> </thead> <tbody>";
 $acchtml .="<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table accTable'> <thead><tr><th>sno</th><th>Accounts Menu Name</th><th><input type='checkbox' class='accounts check_head' name='acccheckall'  value='acc'></th></tr> </thead> <tbody>";
 $levhtml .="<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table levTable'> <thead><tr><th>sno</th><th>Leave Menu Name</th><th><input type='checkbox' class='leave check_head' name='levcheckall'  value='lev'></th></tr> </thead> <tbody>";
 
 $dashtml .="<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table dasTable'> <thead><tr><th>sno</th><th>Dashboard Menu Name</th><th><input type='checkbox' class='dashboard check_head' name='dascheckall'  value='das'></th></tr> </thead> <tbody>";
 /*deepika purpose:purchase menu*/
 
 
 foreach($purchase as $k=>$v){  
          $checks='';
          $sno=$k+1;
          $val=str_replace(' ', '', $v);
          //dd($val);
         
         $checked="";
         if(in_array($val,$access))
         {
         $checked="checked='true'";
        
         }
         
        
    $headhtml .= "<tr><td>".$sno."</td><td><a class='main_menu headmenus head".$val."'  data-value=".$val.">".$v."</a></td><td><input type='checkbox' $checked class='headpo header".$val."' name='menu_id[]' myval=".$v."  ".$checks." value=".$val."></td>  </tr> ";
        }
        //dd("sfs");
        $headhtml .="</tbody>  </table>     </div></div>";
                    $this->data['headhtml']=$headhtml;
          
        /*end*/
        /*deepika purpose:sales menu*/
         foreach($sales as $k=>$v){  
          $checks='';
          $sno=$k+1;
         $val=str_replace(' ', '', $v);
         
         $checked='';
         if(in_array($val,$access))
         {
         $checked="checked='true'";
        
         }
         
         
    $saleshtml .= "<tr><td>".$sno."</td><td><a class='sales_menu salesmenus sales".$val."'  data-value=".$val.">".$v."</a></td><td><input type='checkbox' $checked class='headso salescheck".$val."' name='menu_id[]' myval=".$v."  ".$checks." value=".$val."></td>  </tr> ";
        }
        $saleshtml .="</tbody>  </table>     </div></div>";
                     $this->data['saleshtml']=$saleshtml;
                     
        /*end*/
        /*deepika purpose:production menu*/
 foreach($production as $k=>$v){  
          $checks='';
          $sno=$k+1;
          $val=str_replace(' ', '', $v);
          
         $checked='';
         if(in_array($val,$access))
         {
         $checked="checked='true'";
        
         }
         
          
    $prdhtml .= "<tr><td>".$sno."</td><td><a class='main_menu headmenus head".$val."'  data-value=".$val.">".$v."</a></td><td><input type='checkbox' $checked class='headprd header".$val."' name='menu_id[]' myval=".$v."  ".$checks." value=".$val."></td>  </tr> ";
        }
         $prdhtml .="</tbody>  </table>     </div></div>";
                     $this->data['prdhtml']=$prdhtml;
        /*end*/
        /*deepika purpose:operation menu*/
 foreach($operation as $k=>$v){  
          $checks='';
          $sno=$k+1;
          $val=str_replace(' ', '', $v);
          
         $checked='';
         if(in_array($val,$access))
         {
         $checked="checked='true'";
        
         }
         
          
    $ophtml .= "<tr><td>".$sno."</td><td><a class='main_menu headmenus head".$val."'  data-value=".$val.">".$v."</a></td><td><input type='checkbox' $checked class='headop header".$val."' name='menu_id[]' myval=".$v."  ".$checks." value=".$val."></td>  </tr> ";
        }
         $ophtml .="</tbody>  </table>     </div></div>";
                     $this->data['ophtml']=$ophtml;
        /*end*/            
          /*deepika purpose:accounts menu*/
 foreach($accounts as $k=>$v){  
          $checks='';
          $sno=$k+1;
          $val=str_replace(' ', '', $v);
          
          
         $checked='';
         if(in_array($val,$access))
         {
         $checked="checked='true'";
        
         }
         
          
    $acchtml .= "<tr><td>".$sno."</td><td><a class='main_menu headmenus head".$val."'  data-value=".$val.">".$v."</a></td><td><input type='checkbox' $checked class='headacc header".$val."' name='menu_id[]' myval=".$v."  ".$checks." value=".$val."></td>  </tr> ";
        }
         $acchtml .="</tbody>  </table>     </div></div>";
                     $this->data['acchtml']=$acchtml;
                     
                     
                     
        /*end*/             
       /*deepika purpose:accounts menu*/
       /*vj purpose:leave menu*/
 foreach($leave as $k=>$v){  
          $checks='';
          $sno=$k+1;
          $val=str_replace(' ', '', $v);
          
          
         $checked='';
         if(in_array($val,$access))
         {
         $checked="checked='true'";
        
         }
         
          
    $levhtml .= "<tr><td>".$sno."</td><td><a class='main_menu headmenus head".$val."'  data-value=".$val.">".$v."</a></td><td><input type='checkbox' $checked class='headlev header".$val."' name='menu_id[]' myval=".$v."  ".$checks." value=".$val."></td>  </tr> ";
        }
         $levhtml .="</tbody>  </table>     </div></div>";
                     $this->data['levhtml']=$levhtml;
        /*end*/             
       /*vj purpose:leave menu*/
 foreach($inventory as $k=>$v){  
          $checks='';
          $sno=$k+1;
          $val=str_replace(' ', '', $v);
          
           
         $checked='';
        if(in_array($val,$access))
         {
         $checked="checked='true'";
        
         }
         
          
    $invhtml .= "<tr><td>".$sno."</td><td><a class='main_menu headmenus head".$val."'  data-value=".$val.">".$v."</a></td><td><input type='checkbox' $checked class='headinv header".$val."' name='menu_id[]' myval=".$v."  ".$checks." value=".$val."></td>  </tr> ";
        }
         $invhtml .="</tbody>  </table>     </div></div>";
                     $this->data['invhtml']=$invhtml;
      
      //dashboard accesss menu
      foreach($dashboard as $k=>$v){  
          $checks='';
          $sno=$k+1;
          $val=str_replace(' ', '', $v);
          
          
         $checked='';
         if(in_array($val,$access))
         {
         $checked="checked='true'";
        
         }
         
          
    $dashtml .= "<tr><td>".$sno."</td><td><a class='main_menu headmenus head".$val."'  data-value=".$val.">".$v."</a></td><td><input type='checkbox' $checked class='headdas header".$val."' name='menu_id[]' myval=".$v."  ".$checks." value=".$val."></td>  </tr> ";
        }
         $dashtml .="</tbody>  </table>     </div></div>";
                     $this->data['dashtml']=$dashtml;
                     
                 // end    
      
        }else{
        $headhtml=""; 
        $saleshtml=""; 
        $invhtml=""; 
        $prdhtml=""; 
        $ophtml=""; 
        $acchtml=""; 
        $levhtml="";
        $dashtml="";
   
        
    $purchase = array("PO Draft","PO Approval","GRN Pending","PO Quality Pending","Invoice Pending","Invoice Draft","PO Invoice Approval","Purchase By Supplier","Purchase By Products","Purchase By Products Qty","GRN Overdue","Purchase Invoice Overdue","Purchase Order Piechart","Purchase Invoice Piechart");
    $sales = array("SO Draft","SO Approval","Dispatch Draft","S Invoice Pending","SO Invoice Draft","SO Invoice Approval","Sales By Customer","Sales By Products","Dispatch Overdue","Sales Invoice Overdue","Sales Return Direct","Sales Return Invoice","Sales Return From Invoice","Sales Return Overall");
 // subinventory menu
    $inventory=array("Raw Materials","Packing Materials","Semi Finished Goods","Finished Goods","Sample Products","Promotional Items","ROL FG Details","ROL RM Details","ROL PM Details","Subinventory Transfer Receive-Finished Goods","Subinventory Transfer Receive-Semi Goods","Subinventory Transfer Receive-Sample Goods","Subinventory Transfer Receive-Store","Subinventory Transfer Receive-accessories","Subinventory Transfer Receive-lab");
 $operation=array("Operation Material Issue Pending","Operation Material Acknowledgement Pending","Operation Completion Pending","Operation Quality Pending","Operation Quality Approve Pending","Operation Job Card Details","Operation Open Jobcard","Operation Product Qty");
 $production=array("Production Material Issue Pending","Production Material Acknowledgement Pending","Production Completion Pending","Production Quality Pending","Production Quality Approve Pending","Production Job Card Details","Production Product Qty");
 $accounts=array("Expense Draft","Expense Approval","Payment Batch Pending","Payment Overdue Pending","Payment BRS Pending","Receipt BRS Pending","Consumables","Consumables Approval Pending");
 $leave=array("CL Balance","EL Balance","Comp-Off Balance","Leave Approve Pending","Leave Approval");
  $dashboard=array("More Dashboards","HRMS Dashboard","Accounts Dashboard","Purchase Dashboard","Operation Dashboard","Maintenance Dashboard","Production Dashboard","Quality Dashboard");
$this->data['user_id']=$this->jCombo('tb_users','id','username','');
$this->data['a_dashboard_access_id']='';
    
 
 $headhtml .="<div class='col-md-3 headmenu'>  <div class='table-responsive'> <table class='table myTable'> <thead><tr><th>sno</th><th>Purchase Menu Name</th><th><input type='checkbox' class='head check_head' name='checkall'  value='po'></th></tr> </thead> <tbody>";
 $saleshtml .="<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table salesTable'> <thead><tr><th>sno</th><th>Sales Menu Name</th><th><input type='checkbox' class='sales check_head' name='salescheckall'  value='so'></th></tr> </thead> <tbody>";
 $invhtml .="<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table invTable'> <thead><tr><th>sno</th><th>Inventory Menu Name</th><th><input type='checkbox' class='inventory check_head' name='invcheckall'  value='inv'></th></tr> </thead> <tbody>";
 $ophtml .="<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table opTable'> <thead><tr><th>sno</th><th>Operation Menu Name</th><th><input type='checkbox' class='operation check_head' name='opcheckall'  value='op'></th></tr> </thead> <tbody>";
 $prdhtml .="<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table prdTable'> <thead><tr><th>sno</th><th>Production Menu Name</th><th><input type='checkbox' class='production check_head' name='prdcheckall'  value='prd'></th></tr> </thead> <tbody>";
 $acchtml .="<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table accTable'> <thead><tr><th>sno</th><th>Accounts Menu Name</th><th><input type='checkbox' class='accounts check_head' name='acccheckall'  value='acc'></th></tr> </thead> <tbody>";
 $levhtml .="<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table levTable'> <thead><tr><th>sno</th><th>Leave Menu Name</th><th><input type='checkbox' class='leave check_head' name='levcheckall'  value='lev'></th></tr> </thead> <tbody>";
 
 $dashtml .="<div class='col-md-3 sub_menu'>  <div class='table-responsive'> <table class='table dasTable'> <thead><tr><th>sno</th><th>Dashboard Menu Name</th><th><input type='checkbox' class='dashboard check_head' name='dascheckall'  value='das'></th></tr> </thead> <tbody>";
 /*deepika purpose:purchase menu*/
 foreach($purchase as $k=>$v){  
          $checks='';
          $sno=$k+1;
          $val=str_replace(' ', '', $v);
    $headhtml .= "<tr><td>".$sno."</td><td><a class='main_menu headmenus head".$val."'  data-value=".$val.">".$v."</a></td><td><input type='checkbox' class='headpo header".$val."' name='menu_id[]' myval=".$v."  ".$checks." value=".$val."></td>  </tr> ";
        }
        $headhtml .="</tbody>  </table>     </div></div>";
                    $this->data['headhtml']=$headhtml;
          
        /*end*/
        /*deepika purpose:sales menu*/
         foreach($sales as $k=>$v){  
          $checks='';
          $sno=$k+1;
         $val=str_replace(' ', '', $v);
    $saleshtml .= "<tr><td>".$sno."</td><td><a class='sales_menu salesmenus sales".$val."'  data-value=".$val.">".$v."</a></td><td><input type='checkbox' class='headso salescheck".$val."' name='menu_id[]' myval=".$v."  ".$checks." value=".$val."></td>  </tr> ";
        }
        $saleshtml .="</tbody>  </table>     </div></div>";
                     $this->data['saleshtml']=$saleshtml;
                     
        /*end*/
        /*deepika purpose:production menu*/
 foreach($production as $k=>$v){  
          $checks='';
          $sno=$k+1;
          $val=str_replace(' ', '', $v);
    $prdhtml .= "<tr><td>".$sno."</td><td><a class='main_menu headmenus head".$val."'  data-value=".$val.">".$v."</a></td><td><input type='checkbox' class='headprd header".$val."' name='menu_id[]' myval=".$v."  ".$checks." value=".$val."></td>  </tr> ";
        }
         $prdhtml .="</tbody>  </table>     </div></div>";
                     $this->data['prdhtml']=$prdhtml;
        /*end*/
        /*deepika purpose:operation menu*/
 foreach($operation as $k=>$v){  
          $checks='';
          $sno=$k+1;
          $val=str_replace(' ', '', $v);
    $ophtml .= "<tr><td>".$sno."</td><td><a class='main_menu headmenus head".$val."'  data-value=".$val.">".$v."</a></td><td><input type='checkbox' class='headop header".$val."' name='menu_id[]' myval=".$v."  ".$checks." value=".$val."></td>  </tr> ";
        }
         $ophtml .="</tbody>  </table>     </div></div>";
                     $this->data['ophtml']=$ophtml;
        /*end*/            
          /*deepika purpose:accounts menu*/
 foreach($accounts as $k=>$v){  
          $checks='';
          $sno=$k+1;
          $val=str_replace(' ', '', $v);
    $acchtml .= "<tr><td>".$sno."</td><td><a class='main_menu headmenus head".$val."'  data-value=".$val.">".$v."</a></td><td><input type='checkbox' class='headacc header".$val."' name='menu_id[]' myval=".$v."  ".$checks." value=".$val."></td>  </tr> ";
        }
         $acchtml .="</tbody>  </table>     </div></div>";
                     $this->data['acchtml']=$acchtml;
        /*end*/        
        /*vj purpose:leave menu*/
 foreach($leave as $k=>$v){  
          $checks='';
          $sno=$k+1;
          $val=str_replace(' ', '', $v);
          
          
    $levhtml .= "<tr><td>".$sno."</td><td><a class='main_menu headmenus head".$val."'  data-value=".$val.">".$v."</a></td><td><input type='checkbox' class='headlev header".$val."' name='menu_id[]' myval=".$v."  ".$checks." value=".$val."></td>  </tr> ";
        }
         $levhtml .="</tbody>  </table>     </div></div>";
                     $this->data['levhtml']=$levhtml;
        /*end*/                  
       /*deepika purpose:inventory menu*/
 foreach($inventory as $k=>$v){  
          $checks='';
          $sno=$k+1;
          $val=str_replace(' ', '', $v);
    $invhtml .= "<tr><td>".$sno."</td><td><a class='main_menu headmenus head".$val."'  data-value=".$val.">".$v."</a></td><td><input type='checkbox' class='headinv header".$val."' name='menu_id[]' myval=".$v."  ".$checks." value=".$val."></td>  </tr> ";
        }
         $invhtml .="</tbody>  </table>     </div></div>";
                     $this->data['invhtml']=$invhtml;
        /*end*/    
          //dashboard accesss menu
         foreach($dashboard as $k=>$v){  
          $checks='';
          $sno=$k+1;
          $val=str_replace(' ', '', $v);
    $dashtml .= "<tr><td>".$sno."</td><td><a class='main_menu headmenus head".$val."'  data-value=".$val.">".$v."</a></td><td><input type='checkbox' class='headdas header".$val."' name='menu_id[]' myval=".$v."  ".$checks." value=".$val."></td>  </tr> ";
        }
         $dashtml .="</tbody>  </table>     </div></div>";
                     $this->data['dashtml']=$dashtml;
                     
      
        }
          
        return view('dashboardaccess.form',$this->data);
    }
 

    public function save(Request $request)
    {
        
        if(!empty($_POST['a_dashboard_access_id']))
    {
      
        $data['user_id']=$_POST['user_id'];
        
         
         $data['dashboard_option']=  json_encode($_POST['menu_id']);
       $data['company_id']=\Session::get('companyid');
        $data['location_id']=\Session::get('location');
        $data['last_updated_by']=\Session::get('id');
        $data['updated_at']=date('Y-m-d H:i:s');
          $result = \DB::table('a_dashboard_access_t')->where('a_dashboard_access_id',"=",$_POST['a_dashboard_access_id'])->update($data);
      
   return response()->json(['status' => 'success', 'message' => 'Updated successfully']);
        } 
      else
    {

        $data['user_id']=$_POST['user_id'];
        
         
        $data['dashboard_option']=  json_encode($_POST['menu_id']);
        $data['company_id']=\Session::get('companyid');
        $data['location_id']=\Session::get('location');
        $data['created_by']=\Session::get('id');
        $data['last_updated_by']=\Session::get('id');
        $data['created_at']=date('Y-m-d H:i:s');
        $data['updated_at']=date('Y-m-d H:i:s');
        $result= $inserted_id =\DB::table('a_dashboard_access_t')->insertGetId($data);
        
      return response()->json(['status' => 'success', 'message' => 'Saved successfully']);
                }
    }
   
    public function show(Groupmenuaccess $groupmenuaccess)
    {
        //
    }


    public function edit($id=null)
    {
         $data=\DB::table("a_group_menu_access_t")->select("*")->where("a_group_menu_access_id",$id)->get();
         $this->data['group_name']=$this->jcombologin("a_m_group_t","group_id","group_name","");
         $this->data['header']=\DB::table("tb_menus")->select("*")->where("parent_id","=","0")->get();
         

        return view('groupaccessmenu.form',$this->data);
    }


    public function update(Request $request, Groupmenuaccess $groupmenuaccess)
    {
        //
    }

    public function destroy(Groupmenuaccess $groupmenuaccess)
    {
        //
    }
}