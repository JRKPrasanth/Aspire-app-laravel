<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Hash;
use PDO;
use DatePeriod;
use DateInterval;
use DateTime;

class MobileapputilityController extends Controller
{
	public function __construct()
	{
		
	}
	public function mobileLogin(Request $request){
		$posted_data= $request->json()->all();

		if((isset($posted_data['username']))&&(isset($posted_data['password']))){
			$data = $this->getUser($posted_data);

		}else{
			$data1['message']="Username/Password Missing";
		}
		return response()->json($data);
	}
	
		public function getdatearealist(Request $request){

		$posted_data= $request->json()->all(); 
		$tour_date=date("Y-m-d",strtotime($posted_data['tour_date']));

		 $token = $request->header('token');
	   $user_details = $this->getTokenUser($token);
	  
		$empid=$user_details->id;
		$sql=\DB::select("SELECT * FROM `app_tourprogram_t` where tour_date='$tour_date' and created_by='$empid' ");

		if(count($sql) >0 ){
		$areas=explode(",",$sql[0]->tour_area);
		
		$all_areas=[];
		foreach ($areas as $key => $value) {
			$city=\DB::select("select area_name from m_area_t where area_id='$value'");
			
			$area['city_id']=$value;
			$area['city_name']=$city[0]->area_name;
			$all_areas[]=$area;
		} 
		
			$area_list['areas']=$all_areas;
		} else {
				$area_list['message']="List not available";
		}
			return response()->json($area_list);
		
	}

   public function tourprogram(Request $request){
  	   $posted_data= $request->json()->all();
	   $token = $request->header('token');
	   $user_details = $this->getTokenUser($token);
	   $emp_id = $user_details->id;
		$eid= $appstatus=[];
		
	   	foreach ($posted_data as $key => $value) {
	   		$dt=date('Y-m-d',strtotime($value['date_selected']));
	   		$edit_data = \DB::table('app_tourprogram_t')->whereDate('tour_date',$dt)->where('created_by',$emp_id)->select('tourprogram_id','status')->get();

	   		if(count($edit_data) > 0 ){
	   			$id = $edit_id = $edit_data[0]->tourprogram_id;	
	   			
	   			if($edit_data[0]->status == "INITIATED"){
	   				if(isset($value['tour_type'])){
	   					if($value['tour_type'] != 0 ){
		   					$data['tour_date']= date('Y-m-d',strtotime($value['date_selected']));
						   	$data['tour_details']=isset($value['tour_type']) ? $value['tour_type'] : "";
						   	$data['remarks']=isset($value['remarks']) ? $value['remarks'] : "";
					   		$data['tour_area']=isset($value['town_name']) ? $value['town_name'] : '';		

						   	$loc = json_decode($user_details->loc_id);

						   	$data['location_id']=$loc[0];
						   	$data['organization_id']=$user_details->org_id;
						   	$data['company_id']=$user_details->company_id;
						   	$data['created_by']=$user_details->id;
						   	$data['status']="INITIATED";
						   	$data['employee_id']=$user_details->employee_id;

						   	DB::table('app_tourprogram_t')->where('tourprogram_id',$edit_id)->update($data);
						   	$eid[]= date('d-m-Y',strtotime($value['date_selected']));
					   	}
	   				}	   				
	   			}else{
		   			$appstatus[]= date('d-m-Y',strtotime($value['date_selected']));
			   	}
			   	
	   		}else{
	   			$data['tour_date']= date('Y-m-d',strtotime($value['date_selected']));
			   	$data['tour_details']=isset($value['tour_type']) ? $value['tour_type'] : "";
			   	$data['remarks']=isset($value['remarks']) ? $value['remarks'] : "";
			   	
		   		$data['tour_area']=isset($value['town_name']) ? $value['town_name'] : '';		

			   	$loc = json_decode($user_details->loc_id);

			   	$data['location_id']=$loc[0];
			   	$data['organization_id']=$user_details->org_id;
			   	$data['company_id']=$user_details->company_id;
			   	$data['created_by']=$user_details->id;
			   	$data['status']="INITIATED";
			   	$data['employee_id']=$user_details->employee_id;

			   	$id= DB::table('app_tourprogram_t')->insertGetid($data);
			   	
	   		}
	   		
	   }
	   
	   	if($id!=""){
	   		$data1['message']="Success";
	   	}else{
		 	$data1['message']="Failed";  
	   	}
	   	if(count($eid) > 0){
	   		$data1['eid']=$eid;
	   	}else{
	   		$data1['eid']="";
	   	}
	   	if(count($appstatus) > 0){
	   		$data1['appstatus']=$appstatus;
	   	}else{
	   		$data1['appstatus']="";
	   	}

      	$hr_employee_t = DB::table('hr_employee_t')
				->select('*')
				->where('hr_employee_t.department', 5)
				->get();

        define('FIREBASE_API_KEY', 'AAAArMFlRgs:APA91bGh7qI5ND6FXsEQVLNTEitudnRUQmEDCCeVfIwHt597L8lNjDW5qKx_cbdfA6sskB5q3_1rMd3NcUpewNIljQWV4Zz5UiidmoSgneXzILkBvaQUNc2zxFgh72L1o8vKR9QpCZzw');

		for($i=0;$i<sizeof($hr_employee_t);$i++){
			$res['slug']="tour_entry";
			$res['data']=$data;
			$message = $this->fcmMsgNotification($hr_employee_t[$i]->fcm_token,$res);

		}

	  return response()->json($data1);
   }
	
	public function leaveapproval(Request $request){
  	   $posted_data= $request->json()->all();
	   $token = $request->header('token');
	   $user_details = $this->getTokenUser($token);
	   
	   $leave_id=$posted_data['leave_id'];
	   
	   $id= DB::table('hr_leaves_t')->where('leave_id',$leave_id)->update(['leave_status' => $posted_data['status'],'approval_reason'=>$posted_data['comment']]);

	   $hr_employee_t = DB::table('hr_employee_t')
						->select('*')
						->where('hr_employee_t.department', 4)
						->get();
	   
	   if($id!=""){
	   $data1['message']="Success";
	   }else{
		 $data1['message']="Failed";  
	   }

$hr_employee_t = DB::table('hr_employee_t')
						->select('*')
						->where('hr_employee_t.department', 4)
						->get();

        define('FIREBASE_API_KEY', 'AAAArMFlRgs:APA91bGh7qI5ND6FXsEQVLNTEitudnRUQmEDCCeVfIwHt597L8lNjDW5qKx_cbdfA6sskB5q3_1rMd3NcUpewNIljQWV4Zz5UiidmoSgneXzILkBvaQUNc2zxFgh72L1o8vKR9QpCZzw');

		for($i=0;$i<sizeof($hr_employee_t);$i++){
			$res['slug']="leave_approved";
			$res['data']=$posted_data;

			$message = $this->fcmMsgNotification($hr_employee_t[$i]->fcm_token,$res);
			///dd($id);
		}

	  return response()->json($data1);
   }

   	public function tourplanapproval(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		// dd($posted_data);
		foreach ($posted_data as $key => $value) {
			$approve_id=$value['tourprogram_id'];
			$remarks = isset($value['comment']) ? $value['comment'] : '';
			$id= DB::table('app_tourprogram_t')->where('tourprogram_id',$approve_id)->update(['status' => $value['status'],'approval_remarks'=> $remarks]);			
		}	

	   	if($id!=""){
	   		$data1['message']="Success";
	   	}else{
		 	$data1['message']="Failed";  
	   	}

        $hr_employee_t = DB::table('hr_employee_t')
						->select('*')
						->where('hr_employee_t.job_title', 3)
						->get();

		define('FIREBASE_API_KEY', 'AAAArMFlRgs:APA91bGh7qI5ND6FXsEQVLNTEitudnRUQmEDCCeVfIwHt597L8lNjDW5qKx_cbdfA6sskB5q3_1rMd3NcUpewNIljQWV4Zz5UiidmoSgneXzILkBvaQUNc2zxFgh72L1o8vKR9QpCZzw');

		for($i=0;$i< sizeof($hr_employee_t);$i++){
			$res['slug']="tour_approved";
			$res['data']=$posted_data;
			$message = $this->fcmMsgNotification($hr_employee_t[$i]->fcm_token,$res);
		}

	  	return response()->json($data1);
	}

	public function gettourprogram(Request $request){
		
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$nextPageStatus=false;
		$tourprogram=array();
		$paginationDetails = $this->setPagination($posted_data);
		$user_details = $this->getTokenUser($token);
		$tourprogramdata = DB::table('app_tourprogram_t')
			->where('app_tourprogram_t.created_by', $user_details->id)
			->get();
	    $tourprogram['tourprogram']=$tourprogramdata;
		$tourprogram['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		
	  return response()->json($tourprogram);
	}
	public function gettourdatalist(Request $request){
        $posted_data= $request->json()->all();
		$token = $request->header('token');
		$nextPageStatus=false;
		$token = $request->header('token');

		$user_details = $this->getTokenUser($token);
		$employee_id=$user_details->id;
        //dd($employee_id);
        $paginationDetails = $this->setPagination($posted_data);
		//$rep_man=\DB::select("SELECT t.employee_id,t.tour_area,t.tour_date,e.reporting_manager,e.first_name,t.status FROM `app_tourprogram_t` as t left join hr_employee_t as e on t.employee_id=e.employee_id where t.status='Pending' and e.reporting_manager='$employee_id'");
		$grp_emp=\DB::select("SELECT e.first_name, count(t.employee_id) as total,t.employee_id FROM `app_tourprogram_t` as t left join hr_employee_t as e on t.employee_id=e.employee_id where t.status='INITIATED' and e.reporting_manager='$employee_id' group by t.employee_id");
		$total_emp=0;
		foreach ($grp_emp as $key => $value) {
			$total_emp+=$value->total;
		}
		//$data['rep_man']=$rep_man;
		$data['grp_emp']=$grp_emp;
		$data['total_emp']=$total_emp;
		$data['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		
	  return response()->json($data);
	}
	public function getdetailtourview(Request $request){
		 $posted_data= $request->json()->all();
		 $nextPageStatus=false;
		 $paginationDetails = $this->setPagination($posted_data);
	
		$rep_man=\DB::select("SELECT t.tourprogram_id,t.employee_id,t.tour_area,t.tour_date,e.reporting_manager,e.first_name,t.status FROM `app_tourprogram_t` as t left join hr_employee_t as e on t.employee_id=e.employee_id where t.status='INITIATED' and t.employee_id='".$posted_data['employee_id']."'");
		$tourprogram['approvaldetails']=$rep_man;
		$tourprogram['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		return response()->json($tourprogram);
	}
	public function getapprovedemp(Request $request){
		 $posted_data= $request->json()->all();
		
		 // dd($posted_data);

	}
	
	public function getarea($empid){
		 
		$area=\DB::select("select med_rep_area from hr_employee_t where employee_id='$empid'");
		
		$areaid=json_decode($area[0]->med_rep_area,true);

		$areas=[];
		foreach ($areaid as $key => $value) {
			$city=\DB::select("select area_name from m_area_t where area_id='$value'");
			$area1['area_id']=$value;
			$area1['area_name']=$city[0]->area_name;
			$areas[]=$area1;
		}
		return $areas;
	}

	 public function adddoctor(Request $request){
  	   $posted_data= $request->json()->all();
	   $token = $request->header('token');
	   $user_details = $this->getTokenUser($token);

	   $data['doctor_name']="Dr.".$posted_data['name'];
	   $data['last_name']=$posted_data['dlname'];
	   $data['doctor_type']=$posted_data['dtype'];
	   $data['doctor_phone_number']=$posted_data['mobile'];
	   $data['doctor_email_id']=$posted_data['email'];
	   $data['gender']=$posted_data['gender'];
	   $data['marital_status']=$posted_data['marital_status'];
	   $data['location_address']=$posted_data['location_address'];
	   $data['grade']=$posted_data['grade'];
		$data['degree']=json_encode($posted_data['degree']);
		$data['specialization']=json_encode($posted_data['specialization']);
	   $data['doa']=date('Y-m-d',strtotime($posted_data['doa']));
	   $data['dob']=date('Y-m-d',strtotime($posted_data['dob']));
	   /*$data['state_group']=$posted_data['gender'];
	   $data['focus_product']=$posted_data['gender'];*/

	   $data['latitude']=$posted_data['latitude'];
	   $data['longtitude']=$posted_data['longtitude'];
	   $data['from_time']=$posted_data['from_time'];
	   $data['to_time']=$posted_data['to_time'];
	   $data['remarks']=$posted_data['remarks'];
	   $data['association']=$posted_data['association'];
	   $data['active']=$posted_data['active'];
	   $data['days']=json_encode($posted_data['days']);
	   /*$data['chemist_id']=json_encode($posted_data['specialization']);
		$data['stockist_id']=json_encode($posted_data['specialization']);*/
	   $data['created_at']=date("d-m-Y H:i");
	   $data['updated_at']=date("d-m-Y H:i");
	   $data['last_updated_by']=$user_details->id;
	   $loc = json_decode($user_details->loc_id);
	   $data['location_id']=$loc[0];
	   $data['organization_id']=$user_details->org_id;
	   $data['company_id']=$user_details->company_id;
	   $data['created_by']=$user_details->id;
 	 	
 	 	
 	 $id= DB::table('app_doctors_t')->insertGetid($data);

		 $data2['doctor_id']=$id;
 	 	$data2['area']=$posted_data['area'];
 	 	$data2['doctor_address']=$posted_data['address'];
 	 	$data2['location_id']=$loc[0];
	   $data2['organization_id']=$user_details->org_id;
	   $data2['company_id']=$user_details->company_id;
	   

 	 $line_id= DB::table('app_doctors_adr_t')->insertGetid($data2);

	   if($id!=""){
	   $data1['message']="Success";
	   }else{
		 $data1['message']="Failed";  
	   }
	  return response()->json($data1);
   }

	public function getdoctorlist(Request $request){
	
		$posted_data= $request->json()->all(); 
		$area_id=$posted_data['area'];
		$token = $request->header('token');
	   	$user_details = $this->getTokenUser($token);
	  
		$empid=$user_details->employee_id; 
		
		$sql1=\DB::select("SELECT * FROM `app_doctors_adr_t` where area='$area_id'");
		if(count($sql1) >0 ){
			$doctors=[];
			foreach ($sql1 as $key => $value) {
				$sql2=\DB::select("SELECT doctor_name FROM `app_doctors_t` where doctor_id='".$value->doctor_id."'");
				$doc_list['doc_name']=$sql2[0]->doctor_name;
				$doc_list['doc_id']=$value->doctors_adr_id;
				$doctors[]=$doc_list;
			}
			$doclist['doctor_list']=$doctors; 
		} else {
			$doclist['message']="List not available";
		}
			return response()->json($doclist);
		
		
	}

	public function getchemistlist(Request $request){
		$posted_data= $request->json()->all(); 
		$area_id=$posted_data['area'];
		$token = $request->header('token');
	   	$user_details = $this->getTokenUser($token);
	  
		$empid=$user_details->employee_id; 
		
			$sql1=\DB::select("SELECT * FROM `app_chemist_t` where territory_name='$area_id' and active ='Yes' ");
			if(count($sql1) >0 ){
			$chemist=[];
			foreach ($sql1 as $key => $value) {
			$che_list['chemist_name']=$value->chemist_name;
			$che_list['chemist_id']=$value->chemist_id;
			$chemist[]=$che_list;
			}
			$chemlist['chemist_list']=$chemist; 
		} else {
			$chemlist['message']="List not available";
		}
			return response()->json($chemlist);
	}

	public function getactivitylist(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);

		$sql = DB::table('m_activity_t')->select('*')->get();
		if(count($sql) > 0){
			$activity=[];
			foreach ($sql as $key => $value) {
				$act_list['activity_name']=$value->activity_name;
				$act_list['activity_id']=$value->activity_id;
				$activity[]=$act_list;
			}
			$actlist['activity_list']=$activity;
		}else{
			$actlist['activity_list']="List not available";
		}
	  return response()->json($actlist);
	}

	public function getoutcomelist(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);

		$sql = DB::table('m_outcome_t')->select('*')->get();
		if(count($sql) > 0){
			$outcome=[];
			foreach ($sql as $key => $value) {
				$out_list['outcome_name']=$value->outcome_name;
				$out_list['outcome_id']=$value->outcome_id;
				$outcome[]=$out_list;
			}
			$actlist['outcome_list']=$outcome;
		}else{
			$actlist['outcome_list']="List not available";
		}
	  return response()->json($actlist);

	}

	public function getstockistlist(Request $request){
		$posted_data= $request->json()->all(); 
		$area_id=$posted_data['area'];

		$token = $request->header('token');
	   	$user_details = $this->getTokenUser($token);
	  
		$empid=$user_details->employee_id; 
		
			$sql1=\DB::select("SELECT * FROM `app_stockist_t` where teritory_name='$area_id' and active='Yes'");

			if(count($sql1) >0 ){
			$stockist=[];
			foreach ($sql1 as $key => $value) {
			$sto_list['stockist_name']=$value->stockist_name;
			$sto_list['stockist_id']=$value->stockist_id;
			$stockist[]=$sto_list;
			}
			$stolist['stockist_list']=$stockist; 
		} else {
			$stolist['message']="List not available";
		}
			return response()->json($stolist);
				
	}

	public function addchemist(Request $request){
  	   $posted_data= $request->json()->all();
	   $token = $request->header('token');
	   $user_details = $this->getTokenUser($token);

	   $data['territory_name']=$posted_data['chemist_area'];
	   $data['chemist_name']=$posted_data['chemist_name'];
	   $data['chemist_type']=$posted_data['chemist_type'];
	   $data['chemist_lat']=$posted_data['chemist_lat'];
	   $data['chemist_lon']=$posted_data['chemist_lon'];
	   $data['chemist_address']=$posted_data['chemist_address'];
	   $data['chemist_phone']=$posted_data['chemist_phone'];
	   $data['chemist_mobile']=$posted_data['chemist_mobile'];   
	   $data['group_of_trade']=$posted_data['group_of_trade'];   
	   $data['product_id']=json_encode($posted_data['other_product']); 

	   $data['created_at']=date("d-m-Y H:i a");
	   $data['updated_at']=date("d-m-Y H:i a");
	   $data['last_updated_by']=$user_details->id;
	  	$location_id=json_decode($user_details->loc_id);
	  	$data['location_id']=$location_id[0];
	  	$data['organization_id']=$user_details->org_id;
	   $data['company_id']=$user_details->company_id;
	   $data['created_by']=$user_details->id;

	   $id= DB::table('app_chemist_t')->insertGetid($data);
	   
	   foreach ($posted_data['refDoctor'] as $key => $value) {
	   		$linedata['chemist_id']=$id;
	   	$doc_id = $value['doc_id'];
	   	$doc_adr_id = $value['doc_adr_id'];
	   		$d_address = \DB::table('app_doctors_adr_t')->where('doctor_id',$doc_id)->where('doctors_adr_id',$doc_adr_id)->select('doctor_address')->get();
	   		$linedata['doctor_id']=$value['doc_id'];		
	   		$linedata['doctor_address']=$d_address[0]->doctor_address;
		   $linedata['location_id']=$location_id[0];
		  	$linedata['organization_id']=$user_details->org_id;
		   $linedata['company_id']=$user_details->company_id;
		   $linedata['created_by']=$user_details->id;
		   $linedata['last_updated_by']=$user_details->id;
		   $linedata['created_at']=date("d-m-Y H:i a");
		   $linedata['updated_at']=date("d-m-Y H:i a");

		$lid= DB::table('app_chemist_detail_t')->insertGetid($linedata);
	   }

 	 	
	   if($id!=""){
	   $data1['message']="Success";
	   }else{
		 $data1['message']="Failed";  
	   }
	  return response()->json($data1);
   	}

   public function superstockist(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
	   	$user_details = $this->getTokenUser($token);
	   	$superstockist = \DB::table('m_customers_t')->select('customer_id','customer_name')->get();

	   	$superstk['superstockist']=$superstockist;

	   	return response()->json($superstk);
   }


   public function addstockist(Request $request){
  	   $posted_data= $request->json()->all();
	   $token = $request->header('token');
	   $user_details = $this->getTokenUser($token);

	   // $data['teritory_name']=$posted_data['stockist_area'];
	   $data['stockist_name']=$posted_data['stockist_name'];
	   $data['stockist_lat']=$posted_data['stockist_lat'];
	   $data['stockist_lon']=$posted_data['stockist_lon'];
	   $data['stockist_address']=$posted_data['stockist_address'];
	   $data['stockist_phone']=$posted_data['stockist_phone'];
	   /*$data['stockist_mobile']=$posted_data['stockist_mobile'];*/
	   $data['active']="Yes";
	   
	   $location_id=json_decode($user_details->loc_id);

	   $data['created_at']=date("d-m-Y H:i a");
	   $data['updated_at']=date("d-m-Y H:i a");
	   $data['last_updated_by']=$user_details->id;
	   $data['location_id']=$location_id[0];
	  	$data['organization_id']=$user_details->org_id;
	   $data['company_id']=$user_details->company_id;
	   $data['created_by']=$user_details->id;

 	 $id= DB::table('app_stockist_t')->insertGetid($data);

	   if($id!=""){
	   $data1['message']="Success";
	   }else{
		 $data1['message']="Failed";  
	   }
	  return response()->json($data1);
   }

	 public function extraacitivitydata(Request $request){
		// dd($request);
  	   $posted_data= $request->json()->all();
	   $token = $request->header('token');
	   $user_details = $this->getTokenUser($token);
	   $data['tp_date']=date('Y-m-d',strtotime($posted_data['tp_date']));
	   $data['activity']=$posted_data['activity'];
	   $data['da_type']=$posted_data['da_type'];
	   $data['distance']=$posted_data['distance'];
	   $data['details_area_name']=$posted_data['details_area_name'];
	   $data['created_by']=$user_details->id;
	   $id= DB::table('app_extraacitivity_t')->insertGetid($data);
	   if($id!=""){
	   $data1['message']="Success";
	   }else{
		 $data1['message']="Failed";  
	   }
	  return response()->json($data1);
   }
	public function getextraacitivitydata(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$nextPageStatus=false;
		$extraacitivity=array();
		$paginationDetails = $this->setPagination($posted_data);
		$user_details = $this->getTokenUser($token);
		$extraacitivitydata = DB::table('app_extraacitivity_t')
			->where('app_extraacitivity_t.created_by', $user_details->id)
			->get();
	    $extraacitivity['extraacitivity']=$extraacitivitydata;
		$extraacitivity['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		
	  return response()->json($extraacitivity);
	}

	public function leaveentry(Request $request){
		// dd($request);
  	   $posted_data= $request->json()->all();
	   $token = $request->header('token');
	   $user_details = $this->getTokenUser($token);
	   $data['start_date']=date('Y-m-d',strtotime($posted_data['request_date']));
	   $data['end_date']=date('Y-m-d',strtotime($posted_data['end_date']));
	   $data['leave_resaon']=$posted_data['reason'];
	   $data['employee_id']=$user_details->id;
	   $data['status']="INITIATED";
	   $id= DB::table('hr_leaves_t')->insertGetid($data);
	   if($id!=""){
	   $data1['message']="Success";
	   }else{
		 $data1['message']="Failed";  
	   }
	  return response()->json($data1);
   }

   public function myLeaveRequestList(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$nextPageStatus=false;
		$leaverequest=array();
		$paginationDetails = $this->setPagination($posted_data);
		$user_details = $this->getTokenUser($token);
		$leaverequestdata = DB::table('hr_leaves_t')
			->where('hr_leaves_t.created_by', $user_details->id)
			->get();
	    $leaverequest['leaverequest']=$leaverequestdata;
		$leaverequest['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		
	  return response()->json($leaverequest);
	}

	public function getMissCallReport(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$nextPageStatus=false;
		$leaverequest=array();
		$paginationDetails = $this->setPagination($posted_data);
		$user_details = $this->getTokenUser($token);
		
		$act_doc_id=[];
        $mcr_id=[];
        $visit_id=[];
        $dates=[];
        $re=[];
        
		$start = date( 'Y-m-d', strtotime($posted_data['report_date']));
	
		$stop =date("Y-m-d", time() + 86400);
		
		$period = new DatePeriod( new DateTime($start), new DateInterval('P1D'), new DateTime($stop) );

		foreach ($period as $key => $value) {
		    $dates[]=$value->format('Y-m-d');
		}
		$result=array();
		foreach ($dates as $key => $value) {
			
	        $sql =\DB::table('app_tourprogram_t')->select('*')->wheredate('tour_date',$value)->where('status','=','APPROVE')->get();

	        $mcr_id=array();
	        if(count($sql) > 0){
		        $doc_id = json_decode($sql[0]->doctor_id);
		        $tp_date = $sql[0]->tour_date;
		        
		        foreach ($doc_id as $ke => $val) {
		            if(!in_array($val,$visit_id)){
		                $mcr_id[] = $ke;
		            }
		        }
		    }

	        $plan = \DB::table('app_doctor_dcr_t')->select('*')->where('tp_date',$value)->where('employee_id',$user_details->id)->get();

	        foreach($plan as $k => $v){
	            $visit_id[] = $v->doctor_id;            
	        }

			$result = \DB::table('app_doctors_t')
	                ->select('app_doctors_adr_t.doctors_adr_id','app_doctors_t.doctor_name')
	                ->whereIn('app_doctors_adr_t.doctors_adr_id',$mcr_id)
	                ->leftjoin('app_doctors_adr_t','app_doctors_adr_t.doctor_id','=','app_doctors_t.doctor_id')
	                ->get();
      
	        if($result->isNotEmpty()){
        		foreach ($result as $ke => $val) {
        			$re[] =array('doc_name' => $val->doctor_name,'doc_adr_id' => $val->doctors_adr_id,'date' => $value);
		        }
	        }else{
	        	$re[]= array('date' => $value);
	        }
        }

       
	       	$mcr['result']=$re;
		$mcr['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		
	  return response()->json($mcr);
	}

	public function  postLeaveentry(Request $request){
    $posted_data= $request->json()->all();
    $token = $request->header('token');
	   $user_details = $this->getTokenUser($token);

    $data["employee_id"]=$posted_data['employee_id'];

    $data["leave_type"]=$posted_data["leave_type"];
    $data["start_date"]=$posted_data["start_date"];
    $data["end_date"]=$posted_data["end_date"];
    $data["no_of_days"]=$posted_data["no_of_days"];
    $data["leave_resaon"]=$posted_data["leave_resaon"];
   
    $data["leave_status"]=$posted_data["leave_status"];
    $data["forwarded_id"]=$posted_data["forwarded_id"];
    $data["organization_id"]=$posted_data["organization_id"];
    $data["type_leave"]=$posted_data["type_leave"];
    $data["no_of_days_y"]=$posted_data["no_of_days_y"];
    if($posted_data["start_date_y"]==""){
    	$s_date="0000-00-00 00:00:00";
    }
    else{
    	$s_date=$posted_data["start_date_y"];
    }
      if($posted_data["end_date_y"]==""){
    	$e_date="0000-00-00 00:00:00";
    }
    else{
    	$e_date=$posted_data["end_date_y"];
    }

    $data['created_date']=date("Y-m-d");
   	$data['last_updated_date']=date("Y-m-d");
    $data['last_updated_by']=$user_details->id;
  	$data['organization_id']=1;
    
    $data['created_by']=$user_details->id;

    $data["start_date_y"]=$s_date;
    $data["end_date_y"]=$e_date;

   $id= \DB::table('hr_leaves_t')->insertGetid($data);
    /*$polist = (object) [] ;
    $polist->error = false;*/
    //dd($data);
     $hr_employee_t = DB::table('hr_employee_t')
						->select('*')
						->where('hr_employee_t.department', 5)
						->get();

    if($id!=""){
    	 
	   $polist['message']="Leave Updated successfully!";
	   }else{
		 $polist['message']="Failed";  
	   }

       $hr_employee_t = DB::table('hr_employee_t')
						->select('*')
						->where('hr_employee_t.department', 5)
						->get();

        define('FIREBASE_API_KEY', 'AAAArMFlRgs:APA91bGh7qI5ND6FXsEQVLNTEitudnRUQmEDCCeVfIwHt597L8lNjDW5qKx_cbdfA6sskB5q3_1rMd3NcUpewNIljQWV4Zz5UiidmoSgneXzILkBvaQUNc2zxFgh72L1o8vKR9QpCZzw');

		for($i=0;$i<sizeof($hr_employee_t);$i++){
			$res['slug']="leave_entry";
			$res['data']=$data;
//dd($res);
			$message = $this->fcmMsgNotification($hr_employee_t[$i]->fcm_token,$res);
			
		}

    //$polist->message = 'Leave Updated successfully!';
    return response()->json($polist);

}

 public function leaveapprovallist(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$nextPageStatus=false;
		$leaveapproval=array();
		$paginationDetails = $this->setPagination($posted_data);
		$user_details = $this->getTokenUser($token);


		$emp_id = DB::table('hr_employee_t')->select('hr_employee_t.employee_id','hr_employee_t.reporting_manager','hr_employee_t.department')
                ->where('hr_employee_t.employee_id', $user_details->employee_id)
				->get();
		
		if(count($emp_id) > 0 ){
			if($emp_id[0]->department == 5){
				//Area Manager
				$emld_id = DB::table('hr_employee_t')->select('hr_employee_t.employee_id')
		                ->where('hr_employee_t.reporting_manager', $user_details->employee_id)
						->get();
				$emp_ids=[];
				foreach ($emld_id as $key => $value) {
					$emp_ids[]=$value->employee_id;
				}

				$emp_det=\DB::table('hr_leaves_t')
						->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','hr_leaves_t.employee_id')
						->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','hr_leaves_t.leave_type')
						->select('hr_leaves_t.leave_status','hr_leaves_t.start_date','hr_leaves_t.end_date','hr_leaves_t.no_of_days','hr_leaves_t.alloted_days','hr_employee_t.first_name','a_lookuplines_t.lookup_meaning','hr_leaves_t.created_date','hr_leaves_t.leave_resaon','hr_leaves_t.leave_id')
						->whereIn('hr_leaves_t.employee_id',$emp_ids)
						->where('hr_leaves_t.leave_status','=','INITIATED')
						->orderBy('hr_employee_t.first_name','hr_leaves_t.leave_status')
						->get();

			}else{
				// Employee
				$emp_det=\DB::table('hr_leaves_t')
					->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','hr_leaves_t.employee_id')
					->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','hr_leaves_t.leave_type')
					->select('hr_leaves_t.leave_status','hr_leaves_t.start_date','hr_leaves_t.end_date','hr_leaves_t.no_of_days','hr_leaves_t.alloted_days','hr_employee_t.first_name','a_lookuplines_t.lookup_meaning','hr_leaves_t.created_date','hr_leaves_t.leave_resaon','hr_leaves_t.leave_id')
					->where('hr_leaves_t.employee_id',$user_details->employee_id)
					->where('hr_leaves_t.leave_status','=','INITIATED')
					->orderBy('hr_employee_t.first_name','hr_leaves_t.leave_status')
					->get();
			}

		}
	    $leaveapproval['leaveapproval']=$emp_det;
		$leaveapproval['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		
	  return response()->json($leaveapproval);
	}

	public function listDatas(Request $request){

		$posted_data= $request->json()->all();


        if((isset($posted_data['fcm_token']))&&($posted_data['fcm_token'])){


			$token = $request->header('token');
			$user_details = $this->getTokenUser($token);

			$this->updateFCMToken($posted_data['fcm_token'],$user_details);
		}
		$data['message']="SUCCESS";	
		
		return response()->json($data);


	}

	public function fcmMsgNotification($token,$res){
		$fields = array(
			'to' => $token,
			'data' => $res
		);
		// Set POST variables
		$url = 'https://fcm.googleapis.com/fcm/send';
		$headers = array(
		'Authorization: key=' . FIREBASE_API_KEY,
		'Content-Type: application/json'
		);
		// Open connection
		$ch = curl_init();
		// Set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Disabling SSL Certificate support temporarly
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		// Execute post
		$result = curl_exec($ch);
		if ($result === FALSE) {
		die('Curl failed: ' . curl_error($ch));
		}
		// Close connection
		curl_close($ch);
		// echo $result;
		return $result;
	}

	public function updateFCMToken($fcm_token,$user_details){
            // DB::enableQueryLog();
		//dd($user_details['employee_id']);
		DB::table('hr_employee_t')
            ->where('employee_id', $user_details['employee_id'])
            ->update(['fcm_token' ,'=', $fcm_token]);
            // print_r(DB::getQueryLog());
	}
	public function updateFCMToken1($fcm_token,$user_details){
            // DB::enableQueryLog();
		//dd($user_details['employee_id']);
//dd($user_details);
	
            DB::table('hr_employee_t')
            ->where('employee_id', $user_details)
            ->update(['fcm_token' => $fcm_token]);
            // print_r(DB::getQueryLog());
	}

	public function leaveapprovedlist(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$nextPageStatus=false;
		$leaveapproved=array();
		$paginationDetails = $this->setPagination($posted_data);
		$user_details = $this->getTokenUser($token);


		$emp_id = DB::table('hr_employee_t')->select('hr_employee_t.employee_id','hr_employee_t.reporting_manager','hr_employee_t.department')
                ->where('hr_employee_t.employee_id', $user_details->employee_id)
				->get();
		if(count($emp_id) > 0 ){
			if($emp_id[0]->department == 5){
				//Area Manager
				$emld_id = DB::table('hr_employee_t')->select('hr_employee_t.employee_id')
		                ->where('hr_employee_t.reporting_manager', $user_details->employee_id)
						->get();
				$emp_ids=[];
				foreach ($emld_id as $key => $value) {
					$emp_ids[]=$value->employee_id;
				}
				$emp_det=\DB::table('hr_leaves_t')
						->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','hr_leaves_t.employee_id')
						->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','hr_leaves_t.leave_type')
						->select('hr_leaves_t.leave_status','hr_leaves_t.start_date','hr_leaves_t.end_date','hr_leaves_t.no_of_days','hr_leaves_t.alloted_days','hr_employee_t.first_name','a_lookuplines_t.lookup_meaning','hr_leaves_t.created_date','hr_leaves_t.leave_resaon')
						->whereIn('hr_leaves_t.employee_id',$emp_ids)
						->where('hr_leaves_t.leave_status','=','APPROVED')
						->orderBy('hr_employee_t.first_name','hr_leaves_t.leave_status')
						->get();

			}else{
				// Employee
				$emp_det=\DB::table('hr_leaves_t')
					->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','hr_leaves_t.employee_id')
					->leftjoin('a_lookuplines_t','a_lookuplines_t.lookuplines_id','=','hr_leaves_t.leave_type')
					->select('hr_leaves_t.leave_status','hr_leaves_t.start_date','hr_leaves_t.end_date','hr_leaves_t.no_of_days','hr_leaves_t.alloted_days','hr_employee_t.first_name','a_lookuplines_t.lookup_meaning','hr_leaves_t.created_date','hr_leaves_t.leave_resaon')
					->where('hr_leaves_t.employee_id',$user_details->employee_id)
					->where('hr_leaves_t.leave_status','=','APPROVED')
					->orderBy('hr_employee_t.first_name','hr_leaves_t.leave_status')
					->get();
			}

		}
	    $leaveapproved['leaveapproved']=$emp_det;
		$leaveapproved['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		
	  return response()->json($leaveapproved);
	}


	public function leaveapprovallistcount(Request $request){

		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$nextPageStatus=false;
		$leaveapprovalcount=array();
		$paginationDetails = $this->setPagination($posted_data);
		$user_details = $this->getTokenUser($token);
		
		$leaveapproval_count = DB::table('hr_leaves_t')
			->where('hr_leaves_t.forwarded_id', $user_details->employee_id)
			->where('hr_leaves_t.leave_status', "INITIATED")
			->get();

		$leaveapproved_count = DB::table('hr_leaves_t')
			->where('hr_leaves_t.forwarded_id', $user_details->employee_id)
			->where('hr_leaves_t.leave_status', "APPROVED")
			->get();
		
		$sql = DB::table('hr_employee_t')
			->where('hr_employee_t.employee_id', $user_details->employee_id)
			->select('hr_employee_t.med_rep_area')
			->get();
		
		$area=json_decode($sql[0]->med_rep_area);

		$doctor = \DB::table('app_doctors_adr_t')
				->leftjoin('app_doctors_t','app_doctors_t.doctor_id','=','app_doctors_adr_t.doctor_id')
				->whereIn('app_doctors_adr_t.area',$area)
				->select('app_doctors_adr_t.doctors_adr_id','app_doctors_t.doctor_name')
				->get();

		$chemist = \DB::table('app_chemist_t')
				->whereIn('app_chemist_t.territory_name',$area)
				->select('app_chemist_t.chemist_id','app_chemist_t.chemist_name')
				->get();

		$stockist = \DB::table('app_stockist_t')
				->whereIn('app_stockist_t.teritory_name',$area)
				->select('app_stockist_t.stockist_id','app_stockist_t.stockist_name')
				->get();

		$area_name = \DB::table('m_area_t')
				->whereIn('m_area_t.area_id',$area)
				->select('m_area_t.area_id','m_area_t.area_name')
				->get();


		$tour_emp =\DB::table('hr_employee_t')
				->where('hr_employee_t.reporting_manager',$user_details->employee_id)
				->select('hr_employee_t.employee_id')
				->get('*');
		$emp=[];
		if(count($tour_emp)	> 0 ){
			foreach ($tour_emp as $key => $value) { 
				$empids = \DB::table('tb_users')->where('employee_id',$value->employee_id)->select('id')->get();
				$emp[] = $empids[0]->id;
			}
		}
		
		$tour_pen = \DB::table('app_tourprogram_t')
				->select('app_tourprogram_t.tourprogram_id','app_tourprogram_t.tour_date','app_tourprogram_t.doctor_id','app_tourprogram_t.status','app_tourprogram_t.created_by')
				->whereIn('app_tourprogram_t.created_by',$emp)
				->where('app_tourprogram_t.status','=','INITIATED')
				->get();

		$tour_approved = \DB::table('app_tourprogram_t')
				->select('app_tourprogram_t.tourprogram_id','app_tourprogram_t.tour_date','app_tourprogram_t.doctor_id','app_tourprogram_t.status','app_tourprogram_t.created_by')
				->whereIn('app_tourprogram_t.created_by',$emp)
				->where('app_tourprogram_t.status','=','APPROVED')
				->get();

		$leaveapprovalcount['doctor_count']=count($doctor);
		$leaveapprovalcount['chemist_count']=count($chemist);
		$leaveapprovalcount['stockist_count']=count($stockist);
		$leaveapprovalcount['area_count']=count($area_name);
		$leaveapprovalcount['tour_pending']=count($tour_pen);
		$leaveapprovalcount['tour_approved']=count($tour_approved);
		$leaveapprovalcount['leave_approved_count']=count($leaveapproved_count);
	    $leaveapprovalcount['leave_approval_count']=count($leaveapproval_count);
		// $leaveapprovalcount['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		
	  return response()->json($leaveapprovalcount);
	}



	public function tourpendinglist(Request $request){

		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$nextPageStatus=false;
		$leaveapprovalcount=array();
		$paginationDetails = $this->setPagination($posted_data);
		$user_details = $this->getTokenUser($token);

		$tour_pen = \DB::table('app_tourprogram_t')
				->leftjoin('tb_users','tb_users.id','=','app_tourprogram_t.created_by')
				->leftjoin('m_area_t','m_area_t.area_id','=','app_tourprogram_t.tour_area')
				->select('app_tourprogram_t.tourprogram_id','app_tourprogram_t.tour_date','app_tourprogram_t.status','app_tourprogram_t.created_by','tb_users.first_name','app_tourprogram_t.tour_area','app_tourprogram_t.tourprogram_id','app_tourprogram_t.created_at')
				->where('app_tourprogram_t.created_by',$posted_data['created_by'])
				->where('app_tourprogram_t.status','=','INITIATED')
				->get();
		
		$area_name=[];
		
		
		foreach ($tour_pen as $key => $value) {

			$area=explode(',',$value->tour_area);
			$tou_id=$value->tourprogram_id;
			$area_name = \DB::table('m_area_t')->whereIn('area_id',$area)->select('area_name')->get();
			$name='';
			foreach ($area_name as $k ) {
				$name.= $k->area_name.",";
			}
			$n = rtrim($name,',');
			$value->tour_area = $n;

			list($first,$second)=explode(" ",$value->created_at);
			$value->created_at=$first;

		}

		$leaveapprovalcount['tourdata']=$tour_pen;
		$leaveapprovalcount['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);

		return response()->json($leaveapprovalcount);
	}


	public function tourpending(Request $request){

		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$nextPageStatus=false;
		$leaveapprovalcount=array();
		$paginationDetails = $this->setPagination($posted_data);
		$user_details = $this->getTokenUser($token);
		
		$tour_emp =\DB::table('hr_employee_t')
				->where('hr_employee_t.reporting_manager',$user_details->employee_id)
				->select('hr_employee_t.employee_id')
				->get('*');
		
		$empids=$data=[];

		if(count($tour_emp)	> 0 ){
			foreach ($tour_emp as $key => $value) { 
				$empids=\DB::table('tb_users')->where('employee_id',$value->employee_id)->select('id')->get();
				$emp[]=$empids[0]->id;
			}

			$data = \DB::table('app_tourprogram_t')
				->leftjoin('tb_users','tb_users.id','=','app_tourprogram_t.created_by')
				->whereIn('app_tourprogram_t.created_by',$emp)->where('app_tourprogram_t.status','=','INITIATED')
				->select('status','created_by','tb_users.first_name')->groupBy('app_tourprogram_t.status')->groupBy('app_tourprogram_t.created_by')->get();	
		}

		$leaveapprovalcount['tourpending']=$data;
		$leaveapprovalcount['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		
	  return response()->json($leaveapprovalcount);
	}

	public function tourapproved(Request $request){

		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$nextPageStatus=false;
		$leaveapprovalcount=array();
		$paginationDetails = $this->setPagination($posted_data);
		$user_details = $this->getTokenUser($token);
		
		$tour_emp =\DB::table('hr_employee_t')
				->where('hr_employee_t.reporting_manager',$user_details->employee_id)
				->select('hr_employee_t.employee_id')
				->get('*');

		$empids=[];
		if(count($tour_emp)	> 0 ){
			foreach ($tour_emp as $key => $value) { 
				$empids = \DB::table('tb_users')->where('employee_id',$value->employee_id)->select('id')->get();
				$emp[] = $empids[0]->id;
			}
		}

		$tour_pen = \DB::table('app_tourprogram_t')
				->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','app_tourprogram_t.employee_id')
				->leftjoin('m_area_t','m_area_t.area_id','=','app_tourprogram_t.tour_area')
				->select('app_tourprogram_t.tourprogram_id','app_tourprogram_t.tour_date','app_tourprogram_t.status','app_tourprogram_t.employee_id','hr_employee_t.first_name','app_tourprogram_t.tour_area','app_tourprogram_t.tourprogram_id','app_tourprogram_t.created_at')
				->whereIn('app_tourprogram_t.created_by',$emp)
				->where('app_tourprogram_t.status','=','APPROVE')
				->get();

		$area_name=[];
		
		foreach ($tour_pen as $key => $value) {
			$area=explode(",",$value->tour_area);
			$tou_id=$value->tourprogram_id;
			$ar_name = \DB::table('m_area_t')->whereIn('area_id',$area)->select('area_name')->get();
			$name='';
			foreach ($ar_name as $k ) {
				$name.= $k->area_name.",";
			}
			$n = rtrim($name,',');
			$value->tour_area = $n;

			list($first,$second)=explode(" ",$value->created_at);
			$value->created_at=$first;
		}
		
		$leaveapprovalcount['tourapproved']=$tour_pen;
		$leaveapprovalcount['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		
	  return response()->json($leaveapprovalcount);
	}

	public function doctordetails(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$nextPageStatus=false;
		$leaveapprovalcount=array();
		$paginationDetails = $this->setPagination($posted_data);
		$user_details = $this->getTokenUser($token);
		
		$sql = DB::table('hr_employee_t')
			->where('hr_employee_t.employee_id', $user_details->employee_id)
			->select('hr_employee_t.med_rep_area')
			->get();
		
		$area=json_decode($sql[0]->med_rep_area);
		$doctor = \DB::table('app_doctors_adr_t')
				->leftjoin('app_doctors_t','app_doctors_t.doctor_id','=','app_doctors_adr_t.doctor_id')
				->whereIn('app_doctors_adr_t.area',$area)
				->select('app_doctors_adr_t.doctors_adr_id','app_doctors_t.doctor_name','app_doctors_t.doctor_phone_number','app_doctors_adr_t.doctor_address')
				->get();

		$leaveapprovalcount['doctor']=$doctor;
		$leaveapprovalcount['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		
	  return response()->json($leaveapprovalcount);
	}

	public function chemistdetails(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$nextPageStatus=false;
		$leaveapprovalcount=array();
		$paginationDetails = $this->setPagination($posted_data);
		$user_details = $this->getTokenUser($token);
		
		$sql = DB::table('hr_employee_t')
			->where('hr_employee_t.employee_id', $user_details->employee_id)
			->select('hr_employee_t.med_rep_area')
			->get();
		
		$area=json_decode($sql[0]->med_rep_area);

		$chemist = \DB::table('app_chemist_t')
				->whereIn('app_chemist_t.territory_name',$area)
				->select('app_chemist_t.chemist_id','app_chemist_t.chemist_name','app_chemist_t.chemist_address','app_chemist_t.chemist_phone')
				->get();

		$leaveapprovalcount['chemist']=$chemist;
		$leaveapprovalcount['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		
	  return response()->json($leaveapprovalcount);
	}

	public function stockistdetails(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$nextPageStatus=false;
		$leaveapprovalcount=array();
		$paginationDetails = $this->setPagination($posted_data);
		$user_details = $this->getTokenUser($token);
		
		$sql = DB::table('hr_employee_t')
			->where('hr_employee_t.employee_id', $user_details->employee_id)
			->select('hr_employee_t.med_rep_area')
			->get();
		
		$area=json_decode($sql[0]->med_rep_area);
		
		$stockist = \DB::table('app_stockist_t')
				->whereIn('app_stockist_t.teritory_name',$area)
				->select('app_stockist_t.stockist_id','app_stockist_t.stockist_name','app_stockist_t.stockist_address','app_stockist_t.stockist_phone')
				->get();

		$leaveapprovalcount['stockist']=$stockist;
		$leaveapprovalcount['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		
	  return response()->json($leaveapprovalcount);
	}

	public function areadetails(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$nextPageStatus=false;
		$leaveapprovalcount=array();
		$paginationDetails = $this->setPagination($posted_data);
		$user_details = $this->getTokenUser($token);
		
		$sql = DB::table('hr_employee_t')
			->where('hr_employee_t.employee_id', $user_details->employee_id)
			->select('hr_employee_t.med_rep_area')
			->get();
		
		$area=json_decode($sql[0]->med_rep_area);
		
		$arealist = \DB::table('m_area_t')
				->whereIn('m_area_t.area_id',$area)
				->select('m_area_t.area_id as city_id','m_area_t.area_name as city_name')
				->get();

		$leaveapprovalcount['arealist']=$arealist;
		$leaveapprovalcount['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		
	  return response()->json($leaveapprovalcount);
	}


public function addexpense(Request $request){
		// dd($request);
  	   $posted_data= $request->json()->all();
	   $token = $request->header('token');
	   $user_details = $this->getTokenUser($token);
	   $data['date_selected']=date('Y-m-d',strtotime($posted_data['date_selected']));
	   $data['da']=$posted_data['da'];
	   $data['remarks']=$posted_data['remarks'];
	   $data['boarding']=$posted_data['boarding'];
	   $data['postage']=$posted_data['postage'];
	   $data['ta']=$posted_data['ta'];
	   $data['others']=$posted_data['others'];
	    $data['total']=$posted_data['total'];
	   $data['mobile']=$posted_data['mobile'];


       $data['created_at']=date("d-m-Y H:i a");
	   $data['updated_at']=date("d-m-Y H:i a");
	   $data['last_updated_by']=$user_details->id;
	   $data['location_id']=1;
	   //$data['organization_id']=\Session::get('organization');
	   $data['organization_id']=1;
	   //$data['company_id']=\Session::get('companyid');
	   $data['company_id']=1;
	   
	   $data['created_by']=$user_details->id;

	   $id= DB::table('app_expense_t')->insertGetid($data);
	   if($id!=""){
	   $data1['message']="Success";
	   }else{
		 $data1['message']="Failed";  
	   }
	  return response()->json($data1);
   }

public function getexpense(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$nextPageStatus=false;
		$leaverequest=array();
		$paginationDetails = $this->setPagination($posted_data);
		$user_details = $this->getTokenUser($token);
		$leaverequestdata = DB::table('app_expense_t')
			->where('app_expense_t.created_by', $user_details->id)
			->get();
	    $leaverequest['expenses']=$leaverequestdata;
		$leaverequest['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		
	  return response()->json($leaverequest);
	}

	public function getLeavetype(){
$sql="SELECT * FROM `a_lookuplines_t` WHERE `lookup_type`= 'leave_type'";
$result=\DB::select($sql);
return json_encode($result);
//ar_customers_t
}



	 public function adddoctordcr(Request $request){
  	   $posted_data= $request->json()->all();
	   $token = $request->header('token');
	   $user_details = $this->getTokenUser($token);
	   
	   $data['tp_date']=date('Y-m-d',strtotime($posted_data['tp_date']));
	   $data['divert_detail']=$posted_data['divert_detail'];
	   $data['from_area']=$posted_data['area_id'];
   	   $data['doctor_id']=$posted_data['doctor_id'];
   	   $data['activity_id']=$posted_data['activity_id'];
   	   $data['outcome_id']=$posted_data['outcome_id'];
	   $data['visit_with']=json_encode($posted_data['visit_with']);
	   $data['dcr_reminder_reason']=$posted_data['dcr_reminder_reason'];
	   $data['focus_product']=json_encode($posted_data['focus_product']);
	   $data['dcr_reminder_date']=date('Y-m-d',strtotime($posted_data['dcr_reminder_date']));
	   $data['timing']=$posted_data['timing'];
	   $data['remarks']=$posted_data['remarks'];
	 	$data['employee_id']=$user_details->employee_id;

	   $data['created_at']=date("d-m-Y H:i a");
	   $data['updated_at']=date("d-m-Y H:i a");
	   $data['last_updated_by']=$user_details->id;
	   $data['location_id']=\Session::get('location');
	   $data['organization_id']=\Session::get('organization');
	   $data['company_id']=\Session::get('companyid');
	   $data['created_by']=$user_details->id;

 	 	$id= DB::table('app_doctor_dcr_t')->insertGetid($data);

		foreach ($posted_data['gift_selected'] as $key => $value) {
			$data2['doctor_dcr_id']=$id;
			$data2['gift_sample']=$value['product_id'];
			$data2['gift_qty']=$value['quantity'];
			$gift_id= DB::table('app_gift_sampledetail_t')->insertGetid($data2);
		}

		foreach ($posted_data['sampleSelected'] as $key => $value) {
			$data3['doctor_dcr_id']=$id;
			$data3['product_sample']=$value['product_id'];
			$data3['prd_qty']=$value['quantity'];
			$gift_id= DB::table('app_product_sampledetail_t')->insertGetid($data3);
		}

		foreach ($posted_data['pobSelected'] as $key => $value) {
			$data4['doctor_dcr_id']=$id;
			$data4['product_order']=$value['product_id'];
			$data4['pob_qty']=$value['quantity'];
			$gift_id= DB::table('app_pob_detail_t')->insertGetid($data4);
		}

	   if($id!=""){
	   $data1['message']="Success";
	   }else{
		 $data1['message']="Failed";  
	   }
	  return response()->json($data1);
   }
	public function getdoctordcr(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$doctordata = DB::table('app_doctor_dcr_t')
			->where('app_doctor_dcr_t.created_by', $user_details->id)
			->get();
	  return response()->json($doctordata);
	}

	 public function addchemistdcr(Request $request){
  	   $posted_data= $request->json()->all();
	   $token = $request->header('token');
	   $user_details = $this->getTokenUser($token);
	   $data['tp_date']=date('Y-m-d',strtotime($posted_data['tp_date']));
	   $data['from_area']=$posted_data['from_area'];
	   $data['chemist_name']=$posted_data['chemist'];
	   $data['order_no']=$posted_data['order_no'];
	   $data['value']=$posted_data['value'];
	   
	   $data['created_at']=date("d-m-Y H:i a");
	   $data['updated_at']=date("d-m-Y H:i a");
	   $data['last_updated_by']=$user_details->id;
	   $data['location_id']=\Session::get('location');
	   $data['organization_id']=\Session::get('organization');
	   $data['company_id']=\Session::get('companyid');
	   $data['created_by']=$user_details->id;
	   
 	 $id= DB::table('app_chemist_dcr_t')->insertGetid($data);

 	 	foreach ($posted_data['pob_details'] as $key => $value) {
	   		$data2['chemist_dcr_id'] = $id;
	   		$data2['product_order'] = $value['product_id'];
	   		$data2['pob_qty'] = $value['quantity'];
	   		$line_id= DB::table('app_chemist_odr_detail_t')->insertGetid($data2);
   		}
   		
   		$data['chemist_dcr_id']=$id;
 	 	$history_id= DB::table('app_chemistdcr_history_t')->insertGetid($data);
	   	
	   	foreach ($posted_data['pob_details'] as $key => $value) {
	   		$data3['chemistdcr_history_id'] = $history_id;
	   		$data3['product_order'] = $value['product_id'];
	   		$data3['pob_qty'] = $value['quantity'];
	   		$his_id= DB::table('app_chemist_odr_history_t')->insertGetid($data3);
	   	}

	   if($id!=""){
	   $data1['message']="Success";
	   }else{
		 $data1['message']="Failed";  
	   }
	  return response()->json($data1);
   }
	public function getchemistdcr(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$doctordata = DB::table('app_chemist_dcr_t')
			->where('app_chemist_dcr_t.created_by', $user_details->id)
			->get();
	  return response()->json($doctordata);
	}
	 public function addstockistdcr(Request $request){
  	   $posted_data= $request->json()->all();
	   $token = $request->header('token');
	   $user_details = $this->getTokenUser($token);
	   $data['tp_date']=date('Y-m-d',strtotime($posted_data['tp_date']));
	   $data['from_area']=$posted_data['from_area'];
	   $data['stockist_name']=$posted_data['stockist'];
	   $data['order_no']=$posted_data['order_no'];
	   $data['value']=$posted_data['value'];
	   $data['remarks']=$posted_data['stockist_remark'];
	   
	   $data['created_at']=date("d-m-Y H:i a");
	   $data['updated_at']=date("d-m-Y H:i a");
	   $data['last_updated_by']=$user_details->id;
	   $data['location_id']=\Session::get('location');
	   $data['organization_id']=\Session::get('organization');
	   $data['company_id']=\Session::get('companyid');
	   $data['created_by']=$user_details->id;

 	 $id= DB::table('app_stockist_dcr_t')->insertGetid($data);

 	 	foreach ($posted_data['pob_details'] as $key => $value) {
	   		$data2['stockist_dcr_id'] = $id;
	   		$data2['product_order'] = $value['product_id'];
	   		$data2['pob_qty'] = $value['quantity'];
	   		$line_id= DB::table('app_stockist_detail_t')->insertGetid($data2);
   		}
   		$data['stockist_dcr_id']=$id;
   		$history_id= DB::table('app_stockistdcr_his_t')->insertGetid($data);

   		foreach ($posted_data['pob_details'] as $key => $value) {
	   		$data3['stockistdcr_his_id'] = $history_id;
	   		$data3['product_order'] = $value['product_id'];
	   		$data3['pob_qty'] = $value['quantity'];
	   		$line_id= DB::table('app_stockist_dtl_his_t')->insertGetid($data3);
   		}

	   if($id!=""){
	   $data1['message']="Success";
	   }else{
		 $data1['message']="Failed";  
	   }
	  return response()->json($data1);
   }
	public function getstockistdcr(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$doctordata = DB::table('app_stockist_dcr_t')
			->where('app_stockist_dcr_t.created_by', $user_details->id)
			->get();
	  return response()->json($doctordata);
	}
	public function mobileGeoUpdate(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		if(isset($posted_data['work'])&&($posted_data['work'])){
			$status=4;
		}else if(isset($posted_data['work'])&&(!$posted_data['work'])){
			$status=5;
		}else if(isset($posted_data['tracking'])&&($posted_data['tracking'])){
			$status=3;
		}else{
			$status=2;
		}
		$data['employee_log_id'] = $this->setEmployeeGeoLocation($user_details->employee_id,$status,$posted_data['geo_location'],$posted_data['battery']);
		$data['message']="SUCCESS";
		return response()->json($data);
	}
	public function updateBulkGeo(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$data['gps_list_ids']= $this->insertAllGPSList($posted_data,$user_details);
		return response()->json($data);
	}
	
	public function listAllDatas(Request $request){
		$posted_data= $request->json()->all();
		  $token = $request->header('token');
	   $user_details = $this->getTokenUser($token);
	  
		$empid=$user_details->employee_id;

		$data['all_states'] = $this->getAllStates();
		
		$data['all_towns'] =$area= $this->getarea($empid); 

		$data['tour_type'] =$area= $this->gettourtype(); 
		$data['all_work_type'] =$area= $this->getworktype($empid); 
		
		$data['all_products'] = $this->getpobProducts();
		$data['all_employees'] = $this->getAllEmployees();
		$data['all_rep_manager'] = $this->getAllvisit();
		
		$data['all_grades'] = $this->getAllGrades();
		$data['all_specializations'] = $this->getAllSpecializations();
		$data['all_degrees'] = $this->getAllDegrees();
		$data['all_days'] = $this->getAlldays();
		//dd($data['all_doctors']);
		return response()->json($data);
	}

	public function getdoctor(Request $request){
		
		$posted_data= $request->json()->all(); 
		$doc_id=$doc_list=[];
		$town_id = $posted_data;
		$sql=	DB::table('app_doctors_adr_t')
					->select('app_doctors_t.doctor_name','app_doctors_t.doctor_id','app_doctors_adr_t.doctors_adr_id')
					->leftJoin('app_doctors_t','app_doctors_adr_t.doctor_id','=','app_doctors_t.doctor_id')							
					->where('app_doctors_adr_t.area', $town_id)->get(); 
			foreach ($sql as $key => $value) {
				$doc_id['doc_name']=$value->doctor_name;		
				$doc_id['doc_adr_id']=$value->doctors_adr_id;		
				$doc_id['doc_id']=$value->doctor_id;		
					
				$doc_list[]=$doc_id;
			}
	
		$doc_list1['doctor_list']=$doc_list;
		return response()->json($doc_list1);
	}
	public function getlistGiftSamples(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$data['app_sample_t'] = $this->listGiftSamples();
		return response()->json($data);
	}
	
	public function getdoctordata(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);

		$nextPageStatus=false;
		$doctor_data=array();
		$paginationDetails = $this->setPagination($posted_data);

		$details = \DB::table('app_doctors_t')
				->leftjoin('app_doctors_adr_t','app_doctors_adr_t.doctor_id','=','app_doctors_t.doctor_id')
				->select('app_doctors_adr_t.*','app_doctors_t.*')
				->where('app_doctors_t.created_by',$user_details->id)->get();

		$doctor_data['doctor_view'] = $details;
		$doctor_data['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		return response()->json($doctor_data);
	}

	public function getchemistdata(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$nextPageStatus=false;
		$chemist_data=array();
		$paginationDetails = $this->setPagination($posted_data);
		
		$details = \DB::table('app_chemist_t')
				->leftjoin('app_chemist_detail_t','app_chemist_detail_t.chemist_id','=','app_chemist_t.chemist_id')
				->select('app_chemist_t.*','app_chemist_detail_t.*')
				->where('app_chemist_t.created_by',$user_details->id)->get();

		$chemist_data['chemist_view'] = $details;
		$chemist_data['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		return response()->json($chemist_data);
	}

	public function getstockistdata(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$nextPageStatus=false;
		$stockist_data=array();
		$paginationDetails = $this->setPagination($posted_data);
		
		$details = \DB::table('app_stockist_t')
				->select('app_stockist_t.*')
				->where('app_stockist_t.created_by',$user_details->id)->get();

		$stockist_data['stockist_view'] = $details;
		$stockist_data['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		return response()->json($stockist_data);
	}	

	public function doctordcrdata(Request $request){

		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$nextPageStatus=false;
		$doctordcr_data=array();
		$paginationDetails = $this->setPagination($posted_data);
		
		$details = \DB::table('app_doctor_dcr_t')
				->leftjoin('app_gift_sampledetail_t','app_gift_sampledetail_t.doctor_dcr_id','=','app_doctor_dcr_t.doctor_dcr_id')
				->leftjoin('app_pob_detail_t','app_pob_detail_t.doctor_dcr_id','=','app_doctor_dcr_t.doctor_dcr_id')
				->leftjoin('app_product_sampledetail_t','app_product_sampledetail_t.doctor_dcr_id','=','app_doctor_dcr_t.doctor_dcr_id')
				->leftjoin('m_area_t','m_area_t.area_id','=','app_doctor_dcr_t.from_area')
				->leftjoin('app_doctors_t','app_doctors_t.doctor_id','=','app_doctor_dcr_t.doctor_id')
				->leftjoin('m_activity_t','m_activity_t.activity_id','=','app_doctor_dcr_t.activity_id')
				->leftjoin('m_outcome_t','m_outcome_t.outcome_id','=','app_doctor_dcr_t.outcome_id')
				->leftjoin('m_products_t AS sam','sam.product_id','=','app_product_sampledetail_t.product_sample')
				->leftjoin('m_products_t AS gift','gift.product_id','=','app_gift_sampledetail_t.gift_sample')
				->leftjoin('m_products_t AS pob','pob.product_id','=','app_pob_detail_t.product_order')
				->select('app_doctors_t.doctor_name','m_area_t.area_name','app_doctor_dcr_t.doctor_dcr_id','app_doctor_dcr_t.employee_id','app_doctor_dcr_t.tp_date','app_doctor_dcr_t.divert_detail','app_doctor_dcr_t.focus_product','app_doctor_dcr_t.timing','app_doctor_dcr_t.visit_with','m_activity_t.activity_name','m_outcome_t.outcome_name','app_doctor_dcr_t.dcr_reminder_date','app_doctor_dcr_t.dcr_reminder_reason','sam.concatenated_product as sam_prd', 'app_product_sampledetail_t.prd_qty','gift.concatenated_product as gift_prd','app_gift_sampledetail_t.gift_qty','pob.concatenated_product as pob_prd','app_pob_detail_t.pob_qty')
				->where('app_doctor_dcr_t.created_by',$user_details->id)->get();


		if(count($details) > 0){
			foreach ($details as $key => $val) {
				$focus = json_decode($val->focus_product);
				$visit = json_decode($val->visit_with);
				$f_prd=$fprd=$vit= $v_name= '';

				if(count($focus) > 0){
					foreach ($focus as $k => $v) {
						$f_prd= \DB::table('m_products_t')->where('product_id',$v)->select('concatenated_product')->get();	
						
                      	$fprd.=$f_prd[0]->concatenated_product.",";
					}
					$val->focus_product=rtrim($fprd,",");				
				}	
				if(count($visit) > 0){
					foreach ($visit as $k1 => $v1) {
						$vit= \DB::table('hr_employee_t')->where('employee_id',$v1)->select('first_name')->get();	
						$v_name.=$vit[0]->first_name.",";
					}
					
					$val->visit_with=rtrim($v_name,",");
				}				
			}

		}

		$doctordcr_data['doctordcr_view'] = $details;
		$doctordcr_data['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		return response()->json($doctordcr_data);
	}

	public function getchemistdcrdata(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$nextPageStatus=false;
		$chemistdcr_data=array();
		$paginationDetails = $this->setPagination($posted_data);
		$details = \DB::table('app_chemist_dcr_t')
				->leftjoin('app_chemist_odr_detail_t','app_chemist_odr_detail_t.chemist_dcr_id','=','app_chemist_dcr_t.chemist_dcr_id')
				->leftjoin('app_chemist_t','app_chemist_t.chemist_id','=','app_chemist_dcr_t.chemist_name')
				->leftjoin('m_products_t','m_products_t.product_id','=','app_chemist_odr_detail_t.product_order')
				->leftjoin('m_area_t','m_area_t.area_id','=','app_chemist_dcr_t.from_area')
				->select('app_chemist_dcr_t.tp_date','app_chemist_t.chemist_name','app_chemist_dcr_t.order_no','app_chemist_dcr_t.value','app_chemist_dcr_t.remarks','m_area_t.area_name','m_products_t.concatenated_product','app_chemist_odr_detail_t.pob_qty')
				->where('app_chemist_dcr_t.created_by',$user_details->id)->get();

		$chemistdcr_data['chemistdcr_view'] = $details;
		$chemistdcr_data['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		return response()->json($chemistdcr_data);
	}

	public function getstockistdcrdata(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$nextPageStatus=false;
		$stockistdcr_data=array();
		$paginationDetails = $this->setPagination($posted_data);
		
		$details = \DB::table('app_stockist_dcr_t')
				->leftjoin('app_stockist_detail_t','app_stockist_detail_t.stockist_dcr_id','=','app_stockist_dcr_t.stockist_dcr_id')
				->leftjoin('app_stockist_t','app_stockist_t.stockist_id','=','app_stockist_dcr_t.stockist_name')
				->leftjoin('m_products_t','m_products_t.product_id','=','app_stockist_detail_t.product_order')
				->leftjoin('m_area_t','m_area_t.area_id','=','app_stockist_dcr_t.from_area')
				->select('app_stockist_dcr_t.stockist_dcr_id','app_stockist_dcr_t.tp_date','m_area_t.area_name','app_stockist_t.stockist_name','app_stockist_dcr_t.order_no','app_stockist_dcr_t.value','app_stockist_dcr_t.remarks','app_stockist_detail_t.stockist_detail_id','m_products_t.concatenated_product','app_stockist_detail_t.pob_qty')
				->where('app_stockist_dcr_t.created_by',$user_details->id)->get();

		$stockistdcr_data['stockistdcr_view'] = $details;
		$stockistdcr_data['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		return response()->json($stockistdcr_data);
	}

	public function getmremployeedata(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$nextPageStatus=false;
		$mremp_data=array();
		$paginationDetails = $this->setPagination($posted_data);
		
		$details = \DB::table('hr_employee_t')
				->select('hr_employee_t.*')
				->where('hr_employee_t.reporting_manager',$user_details->employee_id)->get();
// dd($details);
		$mremp_data['mremp_view'] = $details;
		// $mremp_data['pagination']= $this->getPagination($paginationDetails,$nextPageStatus);
		return response()->json($details);
	}

	public function getlistposterProduct(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$data['app_sample_t'] = $this->listposter();
		return response()->json($data);
	}
	public function getlistSamplesProduct(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$data['app_sample_t'] = $this->listSamples();
		return response()->json($data);
	}
	public function getAllProducts(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$data['app_sample_t'] = $this->getpobProducts();
		return response()->json($data);
	}
	
	public function location(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$data/*['attendance_data'] */= $this->getlocs($posted_data['date_attendance']);
		return response()->json($data);
	}

	public function listMyUserAttendance(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$userDetails = $this->getTokenUser($token);
		$user_details= $this->getIDUser($posted_data['employee_id']);
		$data['user_details'] = $user_details;
		$data['attendance_data'] = $this->listMyAttendance($user_details,$posted_data);
		
		return response()->json($data);
	}

	public function listMyAttendance($userDetails,$posted_data){
		//DB::setFetchMode(PDO::FETCH_ASSOC);
		$employeeLogs =  DB::table('app_employee_log_t')
						->select('*')
						->join('hr_employee_t', 'hr_employee_t.employee_id', '=', 'app_employee_log_t.employee_id')
						->join('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'hr_employee_t.department')
					//	->leftJoin('geo_location_addresses', function($join){
						//    $join->on('geo_location_addresses.latitude', '=','app_employee_log_t.s_latitude');
						//    $join->on('geo_location_addresses.longitude','=','app_employee_log_t.s_longitude');
						//})
						->where('app_employee_log_t.employee_id','=',$userDetails->employee_id)
						->whereMonth('app_employee_log_t.s_timestamp','=', date('m',strtotime($posted_data['month_selected'])))
						->where('app_employee_log_t.login_status','=', 2)
						->orderBy('app_employee_log_t.employee_id', 'asc')
						->orderBy('app_employee_log_t.s_timestamp', 'asc')
						->get();
						
		return $employeeLogs;
	}

	public function getIDUser($employeeID){
	//	DB::setFetchMode(PDO::FETCH_ASSOC);
		$users = DB::table('hr_employee_t')
						->select('*')
						->where('employee_id', $employeeID)
						->leftJoin('m_area_t', 'm_area_t.city_id', '=', 'hr_employee_t.med_rep_area')
						->leftJoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'hr_employee_t.department')
						->first();
		return $users;
	}

	public function getLastGeoLocation(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$data = $this->getRecentGeoLocations($user_details);
		return response()->json($data);
	}

public function overallAttendance(Request $request){
	
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$emp_id[] = $user_details->employee_id;
		$dep = $user_details->department;
		$sub_emp=[];

		if($dep == 5){
			$ar_ids = \DB::table('hr_employee_t')->where('reporting_manager',$emp_id)->select('employee_id')->get();			
			foreach ($ar_ids as $k => $v) {
				$sub_emp[]=$v->employee_id;	
			}			
		}
		if(count($sub_emp) > 0 ){
			$emp_id = array_merge($emp_id,$sub_emp);
		}
		$posted_data= $request->json()->all();
		$date = $posted_data['date_attendance'];
		
		$data = $this->getoverallAttendance($date,$emp_id);

		return response()->json($data);
	}

/*functions used for api*/	
	public function getAllStates(){
		$state = DB::table('m_states_t')
						->select('*')
						->orderBy('m_states_t.state_name', 'asc')
						->get();
		return $state;
	}

	public function getAlldays(){
		$state = DB::table('app_days_t')
						->select('*')
						->orderBy('app_days_t.days_id', 'asc')
						->get();
		return $state;
	}

	public function listGiftSamples(){
		$gift_sample = DB::table('m_products_t')
						->select('*')->where('product_group_id','=','5')
						->orderBy('m_products_t.concatenated_product', 'asc')
						->get();
		return $gift_sample;
	}
	public function listSamples(){
		$sample = DB::table('m_products_t')
						->select('*')->where('product_group_id','=','6')
						->orderBy('m_products_t.concatenated_product', 'asc')
						->get();
		return $sample;
	}
	public function listposter(){
		$sample = DB::table('m_products_t')
						->select('*')->where('product_group_id','=','7')
						->orderBy('m_products_t.concatenated_product', 'asc')
						->get();
		return $sample;
	}

	public function getAllTown(){
		$city = DB::table('m_area_t')
						->select('m_area_t.area_id as city_id','m_area_t.area_name as city_name')
						->orderBy('m_area_t.area_name', 'asc')
						->get();
		return $city;
	}

	public function getoverallAttendance($date,$emp_id){
		
		date_default_timezone_set('Asia/Kolkata');
		$attendances = DB::table('app_employee_log_t')
						->where('app_employee_log_t.login_status', '2')
						->leftjoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 'app_employee_log_t.employee_id')
						->whereDate('app_employee_log_t.s_timestamp',$date)
						->whereIn('hr_employee_t.employee_id',$emp_id)
						->select('app_employee_log_t.*','hr_employee_t.*')
						->orderBy('app_employee_log_t.employee_log_id', 'desc')
                     	->groupBy('hr_employee_t.employee_id')
						->get();
		
		return $attendances;
	}

	public function getAllDoctors(){
		$doctor = DB::table('app_doctors_t')
						->select('*')
						->orderBy('app_doctors_t.doctor_name', 'asc')
						->get();
		return $doctor;
	}
	
	
	public function getlocs($date){
		$date = $date;
		$product = DB::table('app_employee_log_t')->whereDate('createdOn',$date)
						->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','app_employee_log_t.employee_id')
						->select('hr_employee_t.first_name as employee_name', 'app_employee_log_t.*')
						->orderBy('app_employee_log_t.employee_id', 'asc')
						->get();
						//dd($product);
		
		return $product;
	}

	public function getRecentGeoLocations($userDetails){
		//DB::setFetchMode(PDO::FETCH_ASSOC);

	$id=$userDetails->employee_id;
	
		date_default_timezone_set('Asia/Kolkata');
		$employee_logs = DB::select("SELECT * FROM app_employee_log_t inner join hr_employee_t ON (hr_employee_t.employee_id=app_employee_log_t.employee_id) WHERE employee_log_id IN (SELECT MAX(employee_log_id) FROM app_employee_log_t where s_timestamp > NOW() - INTERVAL 45 MINUTE GROUP BY app_employee_log_t .employee_id) AND (hr_employee_t.employee_id IN ( ".implode(', ', $this->knowEmployeeReporting($id)).") OR (hr_employee_t.employee_id=".$userDetails->employee_id.")) AND s_latitude!='0'");

		return $employee_logs;
	}
	public function knowEmployeeReporting($userDetails){

		$employee_ids=[];
		
			$employee_ids[]=$userDetails;
				$employee_ids = array_merge($employee_ids,$this->getReportingEmployee($userDetails));
		
		return $employee_ids;
	}
public function getReportingEmployee($employeeID){
		$employee_ids=[];
		$employee_tbl = DB::table('hr_employee_t')
						->where('hr_employee_t.reporting_manager', $employeeID)
						->select('*')
						->get();
					
		for($i=0;$i<sizeof($employee_tbl);$i++){
			$employee_ids[]=$employee_tbl[$i]->employee_id;
			$employee_ids=array_merge($employee_ids);
		}

		return $employee_ids;
	}
	public function getpobProducts(){
		$product = DB::table('m_products_t')
						->select('*')->where('product_group_id','=','1')
						->orderBy('m_products_t.concatenated_product', 'asc')
						->get();
		return $product;
	}

	public function getAllGrades(){
		$grade = DB::table('app_grade_t')
						->select('*')
						->get();
		return $grade;
	}

	public function getworktype(){
		$grade = DB::table('app_worktype_t')
						->select('*')
						->get();
		return $grade;
	}

	public function getAllSpecializations(){
		$spec = DB::table('app_specialization_t')
						->select('*')
						->get();
		return $spec;
	}

	public function getAllDegrees(){
		$degree = DB::table('app_degree_t')
						->select('*')
						->get();
		return $degree;
	}

	public function gettourtype(){
		$type = DB::table('app_worktype_t')
						->select('*')
						->orderBy('app_worktype_t.work_type', 'asc')
						->get();
		return $type;
	}


	public function getAllEmployees(){
		$emp = DB::table('hr_employee_t')
						->select('*')
						->orderBy('hr_employee_t.first_name', 'asc')
						->get();
		return $emp;
	}

	public function getAllvisit(){
		$emp = DB::table('hr_employee_t')
						->select('hr_employee_t.employee_id','hr_employee_t.first_name as employee_number')
						->orderBy('hr_employee_t.first_name', 'asc')
						->get();
		return $emp;
	}

	public function getTokenUser($token){
			//DB::setFetchMode(PDO::FETCH_ASSOC);
			$users = DB::table('tb_users')
							->select('*','hr_employee_t.*')
							->leftJoin('hr_employee_t','hr_employee_t.employee_id','=','tb_users.employee_id')
							->leftJoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'hr_employee_t.department')
							->leftJoin('m_location_t', 'm_location_t.location_id', '=', 'tb_users.loc_id')
							->where('remember_token', $token)
							->first();
			if($users==null){
				$data['message']="Invalid Token";
				response()->json($data)->send();
				die();
			}else{
				return $users;
			}
		}

	
	public function insertAllGPSList($data,$user){
		//DB::setFetchMode(PDO::FETCH_ASSOC);
		$id=[];
		for($i=0;$i<sizeof($data);$i++){
			$datas['employee_id']=$user->employee_id;
			$datas['login_status']=3;
			$datas['s_latitude']=$data[$i]['geo_location']['latitude'];
			$datas['s_longitude']=$data[$i]['geo_location']['longitude'];
			$datas['s_timestamp']=$data[$i]['timestamp'];
			$id[] = DB::table('app_employee_log_t')
							->insertGetId($datas);
		}
		return $id;
	}
	
	
	public function setPagination($pass){
	    $pagination=[];
	    if((isset($pass['limit']))&&($pass['limit']!=0)){
	      $pagination['limit']=(int)$pass['limit'];
	    }else{
	      $pagination['limit']=10;
	    }
	    if((isset($pass['page_no']))&&($pass['page_no']!=0)){
	      $pagination['page_no']=(int)$pass['page_no'];
	    }else{
	      $pagination['page_no']=1;
	    }
	    if($pagination['page_no']<=1){
	      $pagination['start_limit']=0;
	    }else{
	      $pagination['start_limit']=($pagination['page_no']-1)*$pagination['limit'];
	    }
	    return $pagination;
	  }
	public function getPagination($paginationDetails,$status){
	    $pagination['page_no']=$paginationDetails['page_no'];
	    $pagination['limit']=$paginationDetails['limit'];
	    if($paginationDetails['page_no'] <= 1){
	      $pagination['prev_page']=null;
	    }else{
	      $pagination['prev_page']=$paginationDetails['page_no'] -1;
	    }
	    if($status){
	      $pagination['next_page']=$paginationDetails['page_no'] + 1;
	    }else{
	      $pagination['next_page']=null;
	    }

	    return $pagination;
	}

public function getAttendance(Request $request){
		$posted_data= $request->json()->all();
		$token = $request->header('token');
		$user_details = $this->getTokenUser($token);
		$data = $this->getAttendanceStatus($user_details);
		$data['message']="SUCCESS";
		return response()->json($data);
	}

	public function getUser($posted_data){
		//DB::setFetchMode(PDO::FETCH_ASSOC);
		date_default_timezone_set('Asia/Kolkata');
		DB::enableQueryLog();

		$data = DB::table('tb_users')
						->select('hr_employee_t.*','emp1.first_name as report_name','m_department_lines_t.sub_department_name','tb_users.id','tb_users.group_id','tb_users.employee_number','tb_users.password','tb_users.username','tb_users.remember_token','tb_users.active','tb_users.login_attempt','tb_users.last_login','tb_users.last_activity','tb_users.password',
								 'tb_users.email')
			            ->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','tb_users.employee_id')
						->leftjoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'hr_employee_t.department')
						->leftjoin('hr_employee_t as emp1', 'emp1.employee_id', '=', 'hr_employee_t.reporting_manager')
						->where('tb_users.username', $posted_data['username'])
						->first();

		if($data!=null){
			$status = password_verify($posted_data['password'], $data->password);
			
		if($status == true) {
				
					DB::table('tb_users')
						->where('id', $data->id)
						->update(['remember_token' => $this->getUserUniqueToken()]);
					$data = DB::table('tb_users')
								->select('hr_employee_t.*','emp1.first_name as report_name','m_department_lines_t.sub_department_name','tb_users.id','tb_users.group_id','tb_users.employee_number','tb_users.password','tb_users.username','tb_users.remember_token','tb_users.active','tb_users.login_attempt','tb_users.last_login','tb_users.last_activity',
								 'tb_users.email')
								->leftjoin('hr_employee_t','hr_employee_t.employee_id','=','tb_users.employee_id')
								->leftjoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'hr_employee_t.department')
						       	->leftjoin('hr_employee_t as emp1', 'emp1.employee_id', '=', 'hr_employee_t.reporting_manager')
								->where('id', $data->id)
								->first();

				DB::table('tb_users')
					->where('id', $data->id)
					->update(['imei' => $posted_data['imei']]);
				$this->setEmployeeGeoLocation($data->employee_id,1,$posted_data['geo_location'],$posted_data['battery']);
				$this->updateFCMToken1($posted_data['fcm_token'],$data->employee_id);
			    $data->message="Login Successfully !!!";
			     $data->fcm_token=$posted_data['fcm_token'];
			    
				$data->attendance_status = $this->getAttendanceStatus($data);
			}
			if($status == false)
			{
				$data = (object)array();
				$data->message="Invalid Password";
				//return $data1;
			}
		}else{
			$data = (object)array();
				$data->message="Invalid Username";
		}
		return $data;
	}
	
	public function getUserUniqueToken(){
			//DB::setFetchMode(PDO::FETCH_ASSOC);
			do {
				$token = md5(rand());
				$tokenDetails = DB::table('tb_users')
								->select('*')
								->where('remember_token', $token)
								->first();
			} while ($tokenDetails!=null);
		return $token;
		}
		public function getAttendanceStatus($userDetails){
		//DB::setFetchMode(PDO::FETCH_ASSOC);
		date_default_timezone_set('Asia/Kolkata');
		$attendance['work_details'] = DB::table('app_employee_log_t')
						->select('*')
						->where('employee_id','=', $userDetails->employee_id)
						->where('login_status','=', 2)
						->whereDate('createdOn','=', date('Y-m-d'))
						->orderBy('app_employee_log_t.employee_log_id', 'desc')
						->first();//dd($attendance);
		if($attendance['work_details']==null){
			$attendance['work_details']['s_timestamp']=null;
			$attendance['work_details']['e_timestamp']=null;
		}
		return $attendance;
	}

	public function setEmployeeGeoLocation($employeeID,$status,$geo_location,$battery){
		//DB::setFetchMode(PDO::FETCH_ASSOC);
		date_default_timezone_set('Asia/Kolkata');
		$datas = array();
		if((int)$status==1){
			$datas['login_status']=1;
			$datas['s_battery']=$battery;
			$datas['s_latitude']=$geo_location['latitude'];
			$datas['s_longitude']=$geo_location['longitude'];
		}else if((int)$status==2){
			$datas['login_status']=1;
			$datas['e_battery']=$battery;
			$datas['e_latitude']=$geo_location['latitude'];
			$datas['e_longtitude']=$geo_location['longitude'];
			$datas['e_timestamp']=date('Y-m-d H:i:s');
		}else if((int)$status==4){
			$datas['login_status']=2;
			$datas['s_longitude']=$battery;
			$datas['s_latitude']=$geo_location['latitude'];
			$datas['s_longitude']=$geo_location['longitude'];
		}else if((int)$status==5){
			$datas['login_status']=2;
			$datas['e_battery']=$battery;
			$datas['e_latitude']=$geo_location['latitude'];
			$datas['e_longtitude']=$geo_location['longitude'];
			$datas['e_timestamp']=date('Y-m-d H:i:s');
		}
		if((int)$status==3){
			$datas['login_status']=3;
			$datas['s_battery']=$battery;
			$datas['s_latitude']=$geo_location['latitude'];
			$datas['s_longitude']=$geo_location['longitude'];
			$datas['employee_id']=$employeeID;
			$id =DB::table('app_employee_log_t')->insertGetId($datas);
		}else{
			$geoDetails = DB::table('app_employee_log_t')
							->whereDate('app_employee_log_t.createdOn','=', date('Y-m-d'))
							->where('app_employee_log_t.employee_id','=', $employeeID)
							->where('app_employee_log_t.login_status','=', $datas['login_status'])
							->first();
			if($geoDetails!=null){
				// dd($datas);
				// exit;
				DB::table('app_employee_log_t')
		            ->where('employee_log_id', $geoDetails->employee_log_id)
		            ->update($datas);
		        $id = $geoDetails->employee_log_id;
			}else{
				$datas['employee_id']=$employeeID;
				$id =DB::table('app_employee_log_t')->insertGetId($datas);
			}
		}
		return $id;
	}
	/*end*/
	
}

