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
    public function index()
    {
        return view('investmentdeclaration.form',$this->data);
    }
    //investmet proff declare
public function proofsubmission()
    {
        return view('investmentdeclaration.form_declare',$this->data);
    }
    //investmet proof view
	public function proofview()
    {
        return view('investmentdeclaration.view',$this->data);
    }
	// save investment 
    public function store(Request $request)
    {
		
		error_reporting(0);
		$data=$request->all();
		
		$id=$data['employee_name'];
		$year = DB::table('account_year')->where('status',1)->get();
		$year=$year[0]->id;
		$status=$data['confirm'];
		
		unset($data['employee_name'],$data['date_of_joining'],$data['pan_number'],$data['date_of_birth'],$data['_token'],$data['confirm'],$data['visibility']);
		
		foreach($data as $key=>$value)
		{
                    $data_inv=\DB::SELECT("select hr_inve_lines.inv_lines_id,hr_inve_type.inv_name from hr_inve_lines left join hr_inve_type on hr_inve_type.inv_id=hr_inve_lines.inv_id  where hr_inve_lines.inv_id='$value'");
			
                            if(count($data_inv)>0){
                                $inv_name=$data_inv[0]->inv_name;
                      foreach($data_inv as $k=>$v ){
                      
                          if(isset($data[$inv_name."_".$v->inv_lines_id])){
                              $amount=$data[$inv_name."_".$v->inv_lines_id][0];
				$check=DB::table("hr_inve_declaration")->where('emp_id',$id)->where('inv_id',$value)->where('year',$year)->where('inv_name',$v->inv_lines_id)->get();
				if(count($check)>0)
				{
					$inv_dec_id=$check[0]->id;
					$update = DB::table("hr_inve_declaration")->where('id',$inv_dec_id)->update(['inv_amount'=>$amount,'status'=>$status,'last_updated_by'=>\Session::get('id'),'updated_at'=>date('Y-m-d')]);
					  // auditlog
                                        $data['amount']=$amount;
                                        $data['status']=$status;
                                         $this->auditlog($inv_dec_id,"declareinvestment","create",$data,"hr_inve_declaration");
				}
				else
				{

				   $insert = DB::table("hr_inve_declaration")->insertGetId(['emp_id'=>$id,'inv_id'=>$value,'inv_name'=>$v->inv_lines_id,'inv_amount'=>$amount,'year'=>$year,'status'=>$status,'created_by'=>\Session::get('id'),'created_at'=>date('Y-m-d'),'company_id'=>\Session::get('companyid'),'location_id'=>\Session::get('location')]);
                                            // auditlog
                                        $data['emp_id']=$id;
                                        $data['inv_id']=$value;
                                        $data['inv_name']=$v->inv_lines_id;
                                        $data['inv_amount']=$amount;
                                        $data['year']=$year;
                                        $data['status']=$status;
                                         $this->auditlog($insert,"declareinvestment","create",$data,"hr_inve_declaration");
				}
                      }
                            }
                       
			}
		 }
		return 1;
    }
   //1st get data for investment declaartion
    public function getDeclaration(Investment $investment,$id = null)
    {
	
			$emp_id = $id;
			$user_details = DB::table("hr_employee_t")->where('employee_id',$emp_id)->get();
			$personal_details = DB::table("hr_emp_personal")->where('employee_id',$emp_id)->get();

            $year=DB::table("account_year")->where('status',1)->get();
            $year=$year[0]->id;
            $check=DB::table("hr_inve_declaration")->where('emp_id',$emp_id)->where('year',$year)->where('status',1)->get();
		
				$readonly="";
                            
            if(count($check)>0){
                $readonly="readonly";
             
            }
            

            $table ="<input type='hidden' name='visibility' value='$readonly' class='form-control visibility' id='visibility'>";
		
		 	$inv_types=DB::table("hr_inve_type")->get();
             
            foreach($inv_types as $value)
            {
                $inv_id =   $value->inv_id;
                $name   =   $value->inv_name;
                $menu   =   DB::table('hr_inve_lines')->where('hr_inve_lines.inv_id','=',$inv_id)->get();
                if(count($menu)>0)
                {
					
					$table  .= "<div class='col-md-12'>"
                             ."<div class='panel panel-success'>"
                             ."<div class='panel-heading'>$value->inv_name</div>"
                             ." <div class='panel-body'>"
                             ."<div class='form-group row'> 
                            <div class='col-md-6'>
                                <label for='Dob' class=' control-label text-left'><b>Particulars</b></label>
                            </div>
                                        
                            <div class='col-md-6'>
                                <label for='Dob' class=' control-label text-left'><b>Provisional Amount</b></label>
                            </div>
                                   </div>   
                           
							
                            <input type='hidden' name='inv_type$inv_id' value='$inv_id'>";
					
                            $data= DB::table("hr_inve_declaration")->where('emp_id',$emp_id)->where('inv_id',$inv_id)->where('year',$year)->get();
					
                            $val ="";
					
                            if(count($data)>0)
                            {
                                
                    
                                foreach($menu as $value)
                                {
                                      $data= DB::table("hr_inve_declaration")->leftjoin('hr_inve_lines','hr_inve_lines.inv_lines_id','=','hr_inve_declaration.inv_name')->where('hr_inve_declaration.emp_id',$emp_id)->where('hr_inve_declaration.inv_id',$inv_id)->where('hr_inve_declaration.inv_name',$value->inv_lines_id)->where('year',$year)->get();
                    
                                      $val=$data[0]->inv_amount;
                                      $names=$data[0]->name;
                                      $id_lines=$data[0]->inv_lines_id;
                                    
                                    
                                $table .="<div class='col-md-12'><div class='form-group row'> 

                                                                <label for='Dob' class='col-md-6 control-label text-left'>$names</label>
                                                <div class='col-md-2'>	

                                        <input type='text' class='form-control investment' $readonly name='$name"."_"."$id_lines"."[]' id='$id_lines'  value='$val'></div>

                                                </div></div>";
                                   
                            
                                }  
                            }
                            else
                            {
                             
								foreach($menu as $value)
								{
									$table 	.=" <div class='form-group row'> 
								<div class='row'>
													<div class='col-md-6'>
														<label for='Dob' class=' control-label text-left'>$value->name</label>
													</div>
													<div class='col-md-2'>
														<input type='text' class='form-control investment'  value=''name='$name"."_"."$value->inv_lines_id"."[]' id='$value->inv_lines_id' value='$val' $readonly>
													</div>
													</div>
											</div>";
                                }
                            }
					
							$table .="</div></div></div>";
				}
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
            	$table 	.=" <div class='form-group'> 
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

 
                $data=$_POST;
                $file=$_FILES;
                $status=$data['confirm'];
                   //    dd($data);
                $d1=0;
                if($status == 1)
                {
                    $d1 = date('m');
                }
                
                $emp_id=$data['employee_name'];
                $year=\DB::select("select id from account_year where status=1");
                $year=$year[0]->id;
                unset($data['employee_name'],$data['date_of_joining'],$data['pan_number'],$data['date_of_birth'],$data['id'],$data['_token'],$data['confirm']);
                //dd($data);
		//dd($request->file('photo'));
                foreach($data as $key=>$value)
                {
                    $id=$key;

                    if ($request->hasfile('photo'.$id))
                    {
                        $updates = array();
                        $file = $request->file('photo'.$id);
                        $destinationPath = './uploads/employee/'.$emp_id.'/documents/'.$year.'/';
                        $filename = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension(); //if you need extension of the file
                        
                        $get_name=\DB::select("SELECT hr_inve_declaration.inv_name as name FROM `hr_inve_declaration` JOIN hr_inve_type on hr_inve_type.inv_id=hr_inve_declaration.inv_id WHERE hr_inve_declaration.id='$id'");
                      
                        $name=$get_name[0]->name;
                        $get_names=DB::table('hr_inve_lines')->where('hr_inve_lines.inv_lines_id','=',$name)->get();
                        $name=$get_names[0]->name;
                        $newfilename = $name . '.' . $extension;
                        //dd($newfilename);
                        $uploadSuccess = $file->move($destinationPath, $newfilename);

                       if ($uploadSuccess) 
                       {
                           $updates['photo'] = $newfilename;
                       }
                           \DB::update("update hr_inve_declaration set file_upload='$newfilename' where id='$id' ");
                             // auditlog
                        $this->auditlog($id,"proofsubmission","create",$_POST,"hr_inve_declaration");
                    }
                       \DB::update("update hr_inve_declaration set act_amount='$value',upload_status='$status',month='$d1' where id='$id' ");
                         // auditlog
                          $this->auditlog($id,"proofsubmission","create",$_POST,"hr_inve_declaration");
                }

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
        $wh .= "and hr_inve_declaration.emp_id ='$logged_user'";
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
	
	
}
