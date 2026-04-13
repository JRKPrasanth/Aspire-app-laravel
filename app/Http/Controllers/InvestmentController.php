<?php

namespace App\Http\Controllers;

use App\Investment;
use Illuminate\Http\Request;
use DB;

class InvestmentController extends Controller
{
   public function __construct()
    {
            $this->data=array();
            $this->data['pageModule']=\Request::route()->getName();
            $this->data['pageMethod']=\Request::route()->getName();
            $this->data['urlmenu']=$this->indexs(); 
    }
    // investmet form page load functio
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

      $this->data['set1']=\DB::select("SELECT * FROM `hr_inve_columns_t` where inv_type='1'");
      $this->data['set2']=\DB::select("SELECT * FROM `hr_inve_columns_t` where inv_type='2'");
      $this->data['set3']=\DB::select("SELECT * FROM `hr_inve_columns_t` where inv_type='3'");
     
    //   dd($this->data);
      $this->data['old']=json_encode(\DB::select("SELECT * FROM `hr_tax_master` where name='old'"));
      $this->data['new']=json_encode(\DB::select("SELECT * FROM `hr_tax_master` where name='new'"));
        return view('investmentdeclaration.form',$this->data);
    }
    //investmet proff declare
public function proofsubmission(Request $request)
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

        return view('investmentdeclaration.form_declare',$this->data);
    }
    //investmet proof view
  public function proofview(Request $request)
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

        return view('investmentdeclaration.viewindex',$this->data);
    }
  // save investment 
    public function store(Request $request)
    {


    //dd($_POST);
    //error_reporting(0);
    //$data=$request->all();
    //dd(\Session::all());
  $data['id']='';
  $data['emp_id']=$_POST['employee_name'];
  $data['Monthly_House_Rent_Paid']=$_POST['Monthly_House_Rent_Paid'];
  $data['rent_location']=$_POST['rent_location'];
  $data['dmt']=$_POST['dmt'];
  $data['smt']=$_POST['smt'];
  $data['old_tottincome']=$_POST['old_tottincome'];
  $data['new_dus80']=$_POST['new_dus80'];
  $data['old_intax']=$_POST['old_intax'];
  $data['new_intax']=$_POST['new_intax'];
  $data['old_rebate87a']=$_POST['old_rebate87a'];
  $data['new_rebate87a']=$_POST['new_rebate87a'];
  $data['old_btlib']=$_POST['old_btlib'];
  $data['new_btlib']=$_POST['new_btlib'];
  $data['old_adsur']=$_POST['old_adsur'];
  $data['new_adsur']=$_POST['new_adsur'];
  $data['old_totaltax']=$_POST['old_totaltax'];
  $data['new_totaltax']=$_POST['new_totaltax'];
  $data['old_addeduhc']=$_POST['old_addeduhc'];
  $data['new_addeduhc']=$_POST['new_addeduhc'];  
  $data['old_netannualtax']=$_POST['old_netannualtax'];
  $data['new_netannualtax']=$_POST['new_netannualtax'];
  $data['type']=$_POST['type'];

  $data['created_at'] = date('Y-m-d h:i:s');
  $data['updated_at'] = date('Y-m-d h:i:s');
  $data['last_updated_by'] = \Session::get('id');
  $data['company_id'] =\Session::get('companyid');
  $data['location_id'] =\Session::get('location');
  $data['created_by'] = \Session::get('id');
$id = DB::table('hr_inve_declaration')->insertGetId($data);

foreach ($_POST['title'] as $key => $value) {
  
  $lines['title']=$value;
  $lines['old']=$_POST['old'][$key];  
  $lines['new']=$_POST['new'][$key];
  $lines['inv_lines_id']='';
  $lines['inv_id']=$id;
  $lines['created_at'] = date('Y-m-d h:i:s');
  $lines['updated_at'] = date('Y-m-d h:i:s');
  $lines['last_updated_by'] = \Session::get('id');
  $lines['company_id'] =\Session::get('companyid');
  $lines['location_id'] =\Session::get('location');
  $lines['created_by'] = \Session::get('id');

 $linesid = DB::table('hr_inve_lines')->insertGetId($lines); 

}
    // $year = DB::table('account_year')->where('status',1)->get();
    // $year=$year[0]->id;
    // $status=$data['confirm'];
    
    // unset($data['employee_name'],$data['date_of_joining'],$data['pan_number'],$data['date_of_birth'],$data['_token'],$data['confirm'],$data['visibility']);
    
    // foreach($data as $key=>$value)
    // {
  //                   $data_inv=\DB::SELECT("select hr_inve_lines.inv_lines_id,hr_inve_type.inv_name from hr_inve_lines left join hr_inve_type on hr_inve_type.inv_id=hr_inve_lines.inv_id  where hr_inve_lines.inv_id='$value'");
      
  //                           if(count($data_inv)>0){
  //                               $inv_name=$data_inv[0]->inv_name;
  //                     foreach($data_inv as $k=>$v ){
                      
  //                         if(isset($data[$inv_name."_".$v->inv_lines_id])){
  //                             $amount=$data[$inv_name."_".$v->inv_lines_id][0];
    //    $check=DB::table("hr_inve_declaration")->where('emp_id',$id)->where('inv_id',$value)->where('year',$year)->where('inv_name',$v->inv_lines_id)->get();
    //    if(count($check)>0)
    //    {
    //      $inv_dec_id=$check[0]->id;
    //      $update = DB::table("hr_inve_declaration")->where('id',$inv_dec_id)->update(['inv_amount'=>$amount,'status'=>$status,'last_updated_by'=>\Session::get('id'),'updated_at'=>date('Y-m-d')]);
    //        // auditlog
  //                                       $data['amount']=$amount;
  //                                       $data['status']=$status;
  //                                        $this->auditlog($inv_dec_id,"declareinvestment","create",$data,"hr_inve_declaration");
    //    }
    //    else
    //    {

    //       $insert = DB::table("hr_inve_declaration")->insertGetId(['emp_id'=>$id,'inv_id'=>$value,'inv_name'=>$v->inv_lines_id,'inv_amount'=>$amount,'year'=>$year,'status'=>$status,'created_by'=>\Session::get('id'),'created_at'=>date('Y-m-d'),'company_id'=>\Session::get('companyid'),'location_id'=>\Session::get('location')]);
  //                                           // auditlog
  //                                       $data['emp_id']=$id;
  //                                       $data['inv_id']=$value;
  //                                       $data['inv_name']=$v->inv_lines_id;
  //                                       $data['inv_amount']=$amount;
  //                                       $data['year']=$year;
  //                                       $data['status']=$status;
  //                                        $this->auditlog($insert,"declareinvestment","create",$data,"hr_inve_declaration");
    //    }
  //                     }
  //                           }
                       
    //  }
    //  }
    return 1;
    
       }
   //1st get data for investment declaartion
    public function getDeclaration(Investment $investment,$id = null)
    {
  
  
      $emp_id = $id;
      $user_details = DB::table("hr_employee_t")->where('employee_id',$emp_id)->get();
    
      $personal_details = DB::table("hr_emp_personal")->where('employee_id',$emp_id)->get();
  //dd($personal_details);
            $year=DB::table("account_year")->where('status',1)->get();
            $year=$year[0]->id;

            

            $table ="";
    
   
  //  dd($user_details);
    //   dd("a");
      $data['table'] =$table;
      if(count($user_details)>0){
      $data['doj'] =$user_details[0]->date_of_joining;
      $data['pan_no'] =$personal_details[0]->pan_number;
      }else{
      $data['doj'] ="";    
      }
      if(count($personal_details)>0){
      
      $data['dob'] =$personal_details[0]->date_of_birth;
      
     

    }else{
        
      $data['dob'] ="";
      
    }  
      $alltbl='';
   $emppaypro= DB::table("hr_employee_payproposal")->where('employee_id',$emp_id)->get();
   if(count($emppaypro)>0){
$allo=json_decode($emppaypro[0]->allowance);

if($emppaypro[0]->hra!=''){
$hra=$emppaypro[0]->hra;
}else{
  $hra=0;
}
if($emppaypro[0]->da!=""){
$da=$emppaypro[0]->da;
}else{
$da=0;
}
if($emppaypro[0]->basic_pay!=""){
$basic=$emppaypro[0]->basic_pay;
}else{
$basic=0;
}

//dd($da);
   }else{
    $allo='';
   }

   if($allo!=''){
  $alltbl.="<div class='col-md-6 form-group row'> 
               
                          <div class='col-md-4'>
                            <label for='all' class=' control-label text-left'>Basic</label>
                          </div>
                          <div class='col-md-6'>
                           <input type='hidden' class='form-control allowance_id'  value='1' name='allowance_id[]' id='allowance_id'  >
                            <input type='text' class='form-control allowance_name'  name='allowance_name_1[]' id='allowance_name_1' value='$basic' readonly>
                          </div>
                         
                      </div>
  <div class='col-md-6 form-group row'> 
               
                          <div class='col-md-4'>
                            <label for='all' class=' control-label text-left'>HRA</label>
                          </div>
                          <div class='col-md-6'>
                           <input type='hidden' class='form-control allowance_id'  value='2' name='allowance_id[]' id='allowance_id'  >
                            <input type='text' class='form-control allowance_name'  name='allowance_name_2[]' id='allowance_name_2' value='$hra' readonly>
                          </div>
                         
                      </div>
                      <div class='col-md-6 form-group row'> 
               
                          <div class='col-md-4'>
                            <label for='all' class=' control-label text-left'>DA</label>
                          </div>
                          <div class='col-md-6'>
                           <input type='hidden' class='form-control allowance_id'  value='3' name='allowance_id[]' id='allowance_id'  >
                            <input type='text' class='form-control allowance_name'  name='allowance_name_3[]' id='allowance_name_3' value='$da' readonly>
                          </div>
                         
                      </div>";
       // $Allowancetbl = DB::table("m_allowance_tbl")->where('type','Allowance')->where('active','Yes')->get();
  
    // dd($allo);
    foreach ($allo as $ak => $av) {
       $Allowancetbl = DB::table("m_allowance_tbl")->where('type','Allowance')->where('allowance_id',$ak)->get();
           if(count($Allowancetbl)>0){
               $vales = array_values((array)$av);
$val= array_shift($vales);
           
$alname=$Allowancetbl[0]->allowance_name;
      $alltbl  .=" <div class='col-md-6 form-group row'> 
               
                          <div class='col-md-4'>
                            <label for='all' class=' control-label text-left'>$alname</label>
                          </div>
                          <div class='col-md-6'>
                           <input type='hidden' class='form-control allowance_id'  value='$ak' name='allowance_id[]' id='allowance_id'  >
                            <input type='text' class='form-control allowance_name'  name='allowance_name"."_"."$ak"."[]' id='allowance_name"."_"."$ak' value='$val' readonly>
                          </div>
                         
                      </div>";
}
    }
}
$invlines=\DB::select("select * from hr_inve_lines  join hr_inve_declaration on hr_inve_declaration.id=hr_inve_lines.inv_id where hr_inve_declaration.emp_id=$emp_id  ORDER by hr_inve_lines.inv_lines_id ASC");
//dd($invlines);
$htmllins='';
if(count($invlines)>0){
  $data['inv_id']=$invlines[0]->inv_id;
  $data['type']=$invlines[0]->type;
  $data['inv_hdr']=$invlines[0];
  
  foreach ($invlines as $ilk => $il) {
    
$htmllins.="<tr>
        <td>
        </td>
        <td>
          <input type='hidden' id='inv_lines_id' name='inv_lines_id[]' class='form-control inv_lines_id' value='$il->inv_lines_id' readonly='true'> 
          <input type='text' id='title' name='title[]' class='form-control title' value='$il->title' readonly='true'> 
        </td>
        <td class='olddiv'>
              <input type='text' id='old' name='old[]' class='form-control old' value='$il->old' >  
        </td>
        <td class='newdiv'>
              <input type='text' id='new' name='new[]' class='form-control new' value='$il->new' >  
        </td>
        <td class='olddiv'>
              <input type='text' id='actual_old' name='actual_old[]' class='form-control actual_old' value='$il->actual_old' >  
        </td>
        <td class='newdiv'>
              <input type='text' id='actual_new' name='actual_new[]' class='form-control actual_new' value='$il->actual_new' >  
        </td>
         <td>
          <input id='choosefile' class='GetFileSizeNameAndType choosefile' name='choosefile_$il->inv_lines_id[]' value='' type='file' /> 
          
        </td>
      </tr>";

  }
}else{
   $data['inv_id']='';
    $data['inv_hdr']='';
}

$data['htmllins']=$htmllins;
$data['gross_pay']=$emppaypro[0]->gross_pay;
$data['pt_amount']=round($emppaypro[0]->pt_amount*6);



       $data['alltbl'] =$alltbl; 
            return $data;
    
     
    }
//2nd get data for proof view
   public function empview($emp_id = null)
        {
    
      $user_details = DB::table("hr_employee_t")->where('employee_id',$emp_id)->get();
      $personal_details = DB::table("hr_emp_personal")->where('employee_id',$emp_id)->get();
      
            $year=\DB::select("select id from account_year where status=1");
            $year=$year[0]->id;
             $check=\DB::select("select * from hr_inve_declaration where emp_id='$emp_id' and status=1  and year='$year'");
             if(count($check)>0)
                 {
            $check=\DB::select("select * from hr_inve_declaration where emp_id='$emp_id' and status=1 and upload_status=1 and year='$year'");
                $readonly="";
            if(count($check)>0)
                $readonly="readonly";
          
            $table="<input type='hidden' name='visibility' value='$readonly' class='form-control visibility' id='visibility'>";
            $inv_types=\DB::select("select inv_id,inv_name from hr_inve_type");
            foreach($inv_types as $value)
            {
                $inv_id=$value->inv_id;
                $name=$value->inv_name;
                $menu=DB::table('hr_inve_lines')->where('hr_inve_lines.inv_id','=',$inv_id)->get();
                
                if($menu)
                {
                    $table .= ""
                            . "<div class='panel ' style='margin-bottom:0 !important;'>"
                            . "<div class='panel-heading' style='padding:0px;font-weight:bolder;
                            color:#fff;background:rgba(0, 18, 103, 0.89);text-align:center;'>$value->inv_name</div>"
                            . " <div class='panel-body'>"
                            . "<div class='row'>
                                <div class='col-md-3'>
                                <div class='form-group row'>
                                      <label for='Dob' class='col-md-12 form-control-label '> 
                                          <b>Particulars</b>
                                      </label>
                                      </div>
                                </div>
                                        <div class='col-md-3'>
                                       <div class='form-group row'>
                                        <label for='Dob' class='col-md-12 form-control-label '> 
                                            <b>Provisional Amount</b>
          </label></div></div>
                                       <div class='col-md-3'>
                                       <div class='form-group row'>
                                        <label for='Dob' class='col-md-12 form-control-label '> 
                                            <b>Actual Amount</b>
          </label></div></div>
          <div class='col-md-3'>
                    <div class='form-group row'>
                                        <label for='Dob' class='col-md-12 form-control-label'> 
                                            <b>Document</b>
          </label></div></div>
           </div>
                                     ";
                    $data=\DB::select("SELECT id,inv_amount,act_amount,file_upload FROM `hr_inve_declaration` WHERE emp_id='$emp_id' AND status=1 and upload_status=1 AND  inv_id='$inv_id' AND year='$year'");
                  // dd($data);
                    if(count($data)>0)
                    {
                    
                        $i=0;
                       foreach($menu as $value){
                           $data=\DB::select("SELECT id,inv_amount,act_amount,file_upload FROM `hr_inve_declaration` WHERE emp_id='$emp_id' AND status=1 and upload_status=1 AND  inv_name='$value->inv_lines_id' AND year='$year'");
                     $dec_id=  $data[0]->id;    
                    $inv_amount=$data[0]->inv_amount;
                    $act_amount="";
                    if($data[0]->act_amount)
                    {
                       $act_amount= $data[0]->act_amount;
                    }
                    $attachment="";
                    if($data[0]->file_upload)
                    {
                        $file=$data[0]->file_upload;
                       $attachment="<a href='uploads/employee/$emp_id/documents/$year/$file' target='_blank'>$file </a>";
                    }
                        
                        $button = "readonly";
                     
                        $choose_file = "disabled";
                   
                    $table=$table." 
                    <div class='row'>
                    <div class='col-md-3'>
                    <div class='form-group row'>
                            <label for='Dob' class='col-md-12 form-control-label'> 
                                $value->name
                            </label>
                    </div>
                      </div>                  
                    <div class='col-md-3'>
                    <div class='form-group row'>
                        <label for='Dob' class='col-md-12 form-control-label'> 
                            $inv_amount
                        </label>    
                    </div>
                    </div>
                    <div class='col-md-3'>
                    <div class='form-group row'>
                    
                        
                        <input type='text' ".$button." class='form-control' name='$dec_id' value='$act_amount'  >
                    
                    </div></div>
                    <div class='col-md-3'>
                    <div class='form-group row'>
                    
                            <input type='file' ".$choose_file." name='photo$dec_id'>$attachment
                      
                    </div>
                    </div>  
                  </div>  
                 ";
                    $i++;
                    }  
                    }
                     else
                    {
                          
                        $i=0;
                      
                       foreach($menu as $value){
                           $data=\DB::select("SELECT id,inv_amount,act_amount,file_upload FROM `hr_inve_declaration` WHERE emp_id='$emp_id' AND status=1 and upload_status=0 AND  inv_name='$value->inv_lines_id' AND year='$year'");
  
                     $dec_id=  $data[0]->id;    
                    $inv_amount=$data[0]->inv_amount;
                    $act_amount='';
                    if($data[0]->act_amount!=0)
                    {
                       $act_amount= $data[0]->act_amount;
                    }
                    $attachment="";
                    if($data[0]->file_upload)
                    {
                        $file=$data[0]->file_upload;
                       $attachment="<a href='uploads/employee/$emp_id/documents/$year/$file' target='_blank'>$file </a>";
                    }
                    $choose_file='';
                    $button='';
                       if($inv_amount  == 0)
                    {
                        $button = "readonly";
                        $choose_file = "disabled";
                    }
                 
                    $table=$table." 
                    <div class='row'>
                  <div class='col-md-3'>
                  <div class='form-group row'>
          <label for='Dob' class='col-md-12 control-label'> 
                                            $value->name
          </label>
                                        </div></div>
                                        
                                        <div class='col-md-3'>
                                        <div class='form-group row'>
                                        <label for='Dob' class='col-md-12 hh control-label'> 
                                            $inv_amount
          </label>
            
           </div></div>
                                         <div class='col-md-3'>
                                         <div class='form-group row'>
                                        
             <input type='text' class='investment' name='$dec_id' value='$act_amount' $button $readonly>
                         </div>
           </div>
                                          <div class='col-md-3'>
                                  <div class='form-group row'>
                                       
             <input type='file' ".$choose_file."  name='photo$dec_id'>$attachment
           </div></div>
                                        
                 </div>                        
          
                 ";
                    $i++;
                    }  
                    }
                   $table .="</div></div>";
                } }
   
        }else{
            $table='';
              $table  .=" <div class='form-group'> 
                <div class='row'>No record Found</div></div>";
        }
          $data['table'] =$table;
          if(count($personal_details)>0){
      $data['pan_no'] =$personal_details[0]->pan_number;
      $data['dob'] =$personal_details[0]->date_of_birth;
      $data['doj'] =$user_details[0]->date_of_joining;
    }else{
      $data['pan_no'] ="";
      $data['dob'] ="";
      $data['doj'] ="";
    }
                return $data;

               
            
            
        }
        // save function for proof
  function storeproof( Request $request, $id =0)
  {

//dd($request);

foreach ($_POST['inv_lines_id'] as $key => $value) {


 $linid=$value;
 $lines['actual_old']=$_POST['actual_old'][$key];  
 $lines['actual_new']=$_POST['actual_new'][$key];
 $lines['updated_at'] = date('Y-m-d h:i:s');
 $lines['last_updated_by'] = \Session::get('id');

  DB::table('hr_inve_lines')->where('inv_lines_id',$linid)->update($lines);
 // dd($request->hasfile('choosefile_'.$linid));
  if($request->hasfile('choosefile_'.$linid))
            {
 // dd("sdf");
$file=$request->file('choosefile_'.$linid);
//dd($file);
$dataupload=array();
        $name=$file[0]->getClientOriginalName();
        $file[0]->move(public_path().'/uploads/investmentdeclaration/'.$linid.'/', $name);  
        $dataupload[] = $name;  
      //  }
        $attachfile_name=json_encode($dataupload);  
    \DB::update("update hr_inve_lines set choosefile='".$attachfile_name."' where inv_lines_id=".$linid);
  }


}

//foreach($lid['id'] as $k=>$v){   
  
  //               $data=$_POST;
  //               $file=$_FILES;
  //               $status=$data['confirm'];
  //                  //    dd($data);
  //               $d1=0;
  //               if($status == 1)
  //               {
  //                   $d1 = date('m');
  //               }
                
  //               $emp_id=$data['employee_name'];
  //               $year=\DB::select("select id from account_year where status=1");
  //               $year=$year[0]->id;
  //               unset($data['employee_name'],$data['date_of_joining'],$data['pan_number'],$data['date_of_birth'],$data['id'],$data['_token'],$data['confirm']);
  //               //dd($data);
    // //dd($request->file('photo'));
  //               foreach($data as $key=>$value)
  //               {
  //                   $id=$key;

  //                   if ($request->hasfile('photo'.$id))
  //                   {
  //                       $updates = array();
  //                       $file = $request->file('photo'.$id);
  //                       $destinationPath = './uploads/employee/'.$emp_id.'/documents/'.$year.'/';
  //                       $filename = $file->getClientOriginalName();
  //                       $extension = $file->getClientOriginalExtension(); //if you need extension of the file
                        
  //                       $get_name=\DB::select("SELECT hr_inve_declaration.inv_name as name FROM `hr_inve_declaration` JOIN hr_inve_type on hr_inve_type.inv_id=hr_inve_declaration.inv_id WHERE hr_inve_declaration.id='$id'");
                      
  //                       $name=$get_name[0]->name;
  //                       $get_names=DB::table('hr_inve_lines')->where('hr_inve_lines.inv_lines_id','=',$name)->get();
  //                       $name=$get_names[0]->name;
  //                       $newfilename = $name . '.' . $extension;
  //                       //dd($newfilename);
  //                       $uploadSuccess = $file->move($destinationPath, $newfilename);

  //                      if ($uploadSuccess) 
  //                      {
  //                          $updates['photo'] = $newfilename;
  //                      }
  //                          \DB::update("update hr_inve_declaration set file_upload='$newfilename' where id='$id' ");
  //                            // auditlog
  //                       $this->auditlog($id,"proofsubmission","create",$_POST,"hr_inve_declaration");
  //                   }
  //                      \DB::update("update hr_inve_declaration set act_amount='$value',upload_status='$status',month='$d1' where id='$id' ");
  //                        // auditlog
  //                         $this->auditlog($id,"proofsubmission","create",$_POST,"hr_inve_declaration");
  //               }

      return 1;
    
  }
  
// ptoof view grid data 
    public function proofviewgriddata()
    {
        $logged_user = \Session::get('emp_id');
    $wh='';
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
      //  $wh .= "and hr_inve_declaration.emp_id ='$logged_user'";
    $searchable_data=["hr_employee_t"];
      if($_GET['_search']=='true')
  {
            $wh=$this->jqgridsearch('hr_inve_declaration',$_GET['filters'],$searchable_data);

  }
  if(!$sidx) $sidx =1;
        
        

  $result = \DB::select("SELECT COUNT(id) AS count FROM hr_inve_declaration where 1=1 $wh");
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

        
  $SQL = "SELECT hr_inve_declaration.*,concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as first_name,account_year.year_from FROM  hr_inve_declaration
                    LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = hr_inve_declaration.emp_id
                    LEFT JOIN hr_inve_type ON hr_inve_type.inv_id = hr_inve_declaration.inv_id
                    LEFT JOIN account_year  ON account_year.id = hr_inve_declaration.year
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
  public function proofviewshow(Investment $investment,$id = null)
    {
  
  //  dd($id);


    $hdrtbl=\DB::select("select hr_inve_declaration.*,concat(hr_employee_t.employee_number,'-',hr_employee_t.first_name) as employee_name from hr_inve_declaration left join hr_employee_t on hr_employee_t.employee_id=hr_inve_declaration.emp_id where hr_inve_declaration.id=$id ");

$this->data['hdr']=$hdrtbl;
//dd($hdrtbl);
$lines=\DB::select("select * from hr_inve_lines where inv_id=$id ");
$this->data['lines']=$lines;

     $emp_id = $hdrtbl[0]->emp_id;
    
      $emppayproda= DB::table("hr_employee_payproposal")->select('hra','da','basic_pay')->where('employee_id',$emp_id)->get();
        $emppaypro= DB::table("hr_employee_payproposal")->select('allowance')->where('employee_id',$emp_id)->get();
//dd($emppaypro);
//$this->data['emppaypro']=$emppaypro;
  $hra=0;
$da=0;
$basic=0;
  if(count($emppaypro)>0){

if($emppayproda[0]->hra!=''){
$hra=$emppayproda[0]->hra;
}
if($emppayproda[0]->da!=""){
$da=$emppayproda[0]->da;
}
if($emppayproda[0]->basic_pay!=""){
$basic=$emppayproda[0]->basic_pay;
}

$allo=json_decode($emppaypro[0]->allowance);
$key2=0;
//dd($allo);
$this->data['allowance']= $emppaypro;
foreach ($this->data['allowance'] as $key1 => $value2) { 

//$allo=json_decode($value2->allowance);
//dd($allo);
if($allo!=''){
foreach($allo as $key => $value) {
 $Allowancetbl = DB::table("m_allowance_tbl")->where('type','Allowance')->where('allowance_id',$key)->get(); 
 $this->data['allowance'][$key2]->allowance_name=$Allowancetbl[0]->allowance_name; 
 $this->data['allowance'][$key2]->allowance_value=$value; 
$this->data['allowance'][$key2]->allowance_id=$key; 
$key2++;

}
}else{
   $this->data['allowance']=array(); 
}
}

}
$this->data['da']=$da;
$this->data['hra']=$hra;
$this->data['basic']=$basic;


 //dd($this->data);
//$alname=$Allowancetbl[0]->allowance_name;
//dd();

      $user_details = DB::table("hr_employee_t")->where('employee_id',$emp_id)->get();
    

      $personal_details = DB::table("hr_emp_personal")->where('employee_id',$emp_id)->get();
  
            $year=DB::table("account_year")->where('status',1)->get();
            $year=$year[0]->id;

      
//      $data['table'] =$table;
      if(count($user_details)>0){
      $this->data['doj'] =$user_details[0]->date_of_joining;
      }else{
      $$this->data['doj'] ="";    
      }
      if(count($personal_details)>0){
      $this->data['pan_no'] =$personal_details[0]->pan_number;
      $this->data['dob'] =$personal_details[0]->date_of_birth;
      
     

    }else{
          $this->data['pan_no'] ="";
      $this->data['dob'] ="";
      
    }
//       $alltbl='';
//    $emppaypro= DB::table("hr_employee_payproposal")->where('employee_id',$emp_id)->get();
//    if(count($emppaypro)>0){
// $allo=json_decode($emppaypro[0]->allowance);

// if($emppaypro[0]->hra!=''){
// $hra=$emppaypro[0]->hra;
// }else{
//   $hra=0;
// }
// if($emppaypro[0]->da!=""){
// $da=$emppaypro[0]->da;
// }else{
// $da=0;
// }
// if($emppaypro[0]->basic_pay!=""){
// $basic=$emppaypro[0]->basic_pay;
// }else{
// $basic=0;
// }

// //dd($da);
//    }else{
//     $allo='';
//    }

//    if($allo!=''){
//   $alltbl.="<div class='col-md-6 form-group row'> 
               
//                           <div class='col-md-4'>
//                             <label for='all' class=' control-label text-left'>Basic</label>
//                           </div>
//                           <div class='col-md-6'>
//                            <input type='hidden' class='form-control allowance_id'  value='1' name='allowance_id[]' id='allowance_id'  >
//                             <input type='text' class='form-control allowance_name'  name='allowance_name_1[]' id='allowance_name_1' value='$basic' readonly>
//                           </div>
//                       </div>
//   <div class='col-md-6 form-group row'>               
//                           <div class='col-md-4'>
//                             <label for='all' class=' control-label text-left'>HRA</label>
//                           </div>
//                           <div class='col-md-6'>
//                            <input type='hidden' class='form-control allowance_id'  value='2' name='allowance_id[]' id='allowance_id'  >
//                             <input type='text' class='form-control allowance_name'  name='allowance_name_2[]' id='allowance_name_2' value='$hra' readonly>
//                           </div>
                         
//                       </div>
//                       <div class='col-md-6 form-group row'> 
               
//                           <div class='col-md-4'>
//                             <label for='all' class=' control-label text-left'>DA</label>
//                           </div>
//                           <div class='col-md-6'>
//                            <input type='hidden' class='form-control allowance_id'  value='3' name='allowance_id[]' id='allowance_id'  >
//                             <input type='text' class='form-control allowance_name'  name='allowance_name_3[]' id='allowance_name_3' value='$da' readonly>
//                           </div>
                         
//                       </div>";
//        // $Allowancetbl = DB::table("m_allowance_tbl")->where('type','Allowance')->where('active','Yes')->get();
  
//    // dd($allo);
//     foreach ($allo as $ak => $av) {
//        $Allowancetbl = DB::table("m_allowance_tbl")->where('type','Allowance')->where('allowance_id',$ak)->get();
// $alname=$Allowancetbl[0]->allowance_name;
// //dd($ak);
//       $alltbl  .=" <div class='col-md-6 form-group row'> 
               
//                           <div class='col-md-4'>
//                             <label for='all' class=' control-label text-left'>$alname</label>
//                           </div>
//                           <div class='col-md-6'>
//                            <input type='hidden' class='form-control allowance_id'  value='$ak' name='allowance_id[]' id='allowance_id'  >
//                             <input type='text' class='form-control allowance_name'  name='allowance_name"."_"."$ak"."[]' id='allowance_name"."_"."$ak' value='$av' readonly>
//                           </div>
                         
//                       </div>";

//     }

// }
// $invlines=\DB::select("select * from hr_inve_lines left join hr_inve_declaration on hr_inve_declaration.id=hr_inve_lines.inv_id where hr_inve_declaration.emp_id=$emp_id  ORDER by hr_inve_lines.inv_lines_id ASC");
// $htmllins='';
// if(count($invlines)>0){
//   $data['inv_id']=$invlines[0]->inv_id;
//   $data['inv_hdr']=$invlines[0];
  
//   foreach ($invlines as $ilk => $il) {
    
// $htmllins.="<tr>
//         <td>
//         </td>
//         <td>
//           <input type='hidden' id='inv_lines_id' name='inv_lines_id[]' class='form-control inv_lines_id' value='$il->inv_lines_id' readonly='true'> 
//           <input type='text' id='title' name='title[]' class='form-control title' value='$il->title' readonly='true'> 
//         </td>
//         <td>
//               <input type='text' id='old' name='old[]' class='form-control old' value='$il->old' >  
//         </td>
//         <td>
//               <input type='text' id='new' name='new[]' class='form-control new' value='$il->new' >  
//         </td>
//         <td>
//               <input type='text' id='actual_old' name='actual_old[]' class='form-control actual_old' value='$il->actual_old' >  
//         </td>
//         <td>
//               <input type='text' id='actual_new' name='actual_new[]' class='form-control actual_new' value='$il->actual_new' >  
//         </td>
//          <td>
//           <input id='choosefile' class='GetFileSizeNameAndType choosefile' name='choosefile_$il->inv_lines_id[]' value='' type='file' /> 
          
//         </td>
//       </tr>";

//   }
// }else{
//    $data['inv_id']='';
//     $data['inv_hdr']='';
// }

// $data['htmllins']=$htmllins;
// $data['gross_pay']=$emppaypro[0]->gross_pay;
// $data['pt_amount']=$emppaypro[0]->pt_amount;


//  dd($this->data);
//        $data['alltbl'] =$alltbl; 
//        $this->data['data']=$data;

       return    view('investmentdeclaration.view',$this->data);
    }
  
}
