<?php

namespace App\Http\Controllers;

use App\Ticketsystemmaintenance;
use Illuminate\Http\Request;
use DB;
use Config;
class TicketsystemmaintenanceController extends Controller
{
    public function __construct()
    {
        $this->data=array();
        $this->model=new Ticketsystemmaintenance;
                $this->data['pageMethod']=\Request::route()->getName();
                        $this->data['pageModule']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
$this->data=array(
            'pageModule'=> $this->data['pageModule'],
            'pageUrl' =>  url($this->data['pageMethod']),
            'pageMethod'=>$this->data['pageMethod']
          );
        $this->table="b_ticketsystem_t";

                $this->data['urlmenu']=$this->indexs(); 

        
    }
    public function dashboardindex()
    {

                $this->data['pageMethod']=\Request::route()->getName();
          return view('ticketsystem.ticket_dashboard',$this->data);
    }
    
    public function ticketrequestindex()
    {

                $this->data['pageMethod']=\Request::route()->getName();
          return view('ticketsystem.table',$this->data);
    }

      public function create($id=null)
    {
        if($id!='')
        {
        $sql = \DB::SELECT("select * from b_ticketsystem_t where id=$id");
        
       // dd($sql);
        $this->data['row']= (object)array();
       //  $this->data['row']->causes=$sql[0]->causes;
      //  $this->data['lastmaintenance']= (object)array();
      $this->data['row']->error_code=$sql[0]->error_code;
      $this->data['parent_id'] ='0';
         $this->data['row']->department_id=  $this->jcustomselect('m_department_lines_t','department_line_id','sub_department_code|sub_department_name',$sql[0]->department_id," and sub_department_code LIKE '%c010%'");
        $this->data['row']->breakdown_sevearity=$this->jCombologin('breakdown_severity','breakdownseverity_id','severity_name',$sql[0]->breakdown_sevearity);
        $this->data['row']->break_type_id=$this->jCombologin('m_breakdowntype_t','breakdowntype_id','breakdown_name',$sql[0]->break_type_id);
        $this->data['row']->engineer=$this->jcustomselect('hr_employee_t','employee_id','employee_number|first_name',$sql[0]->engineer," and department LIKE '%53%'");
        $this->data['row']->spares_id= $this->jcustomproductselect('m_products_t','product_id','concatenated_product','','closerequest');
        $this->data['row']->machine_id=$sql[0]->machine_id;
                            $mid=$sql[0]->machine_id;

       $this->data['lastmaintenance']=$sql12=\DB::select("select ticket_number,maintenance_type,corrective_action,preventive_action,issue_date FROM b_maintenance_t WHERE machine_id='$mid' and request_status='CLOSED' ORDER By id DESC LIMIT 0,5 ");
      
        $this->data['row']->maintenance_type=$sql[0]->maintenance_type;
        $this->data['row']->causes=$sql[0]->causes;
        $this->data['row']->request_remark=$sql[0]->request_remark;
        $this->data['row']->approve_remarks=$sql[0]->approve_remarks;
        //dd($this->data['row']->causes);
       $this->data['row']->ticket_number=$sql[0]->ticket_number;
        $this->data['row']->active=$sql[0]->active;
        $this->data['row']->shift=$sql[0]->shift;
        $this->data['row']->issue_date=$sql[0]->issue_date;
        $this->data['row']->id=$sql[0]->id;
        $this->data['row']->others=$sql[0]->others;
        $this->data['row']->approve_remarks=$sql[0]->approve_remarks;
        $this->data['row']->is_breakdown=$sql[0]->is_breakdown;
        $this->data['row']->critical_spare=$sql[0]->critical_spare;
        if($_GET['btnval']=="closerequest"){
            $this->data['row']->start_date=$sql[0]->issue_date;
        $this->data['row']->end_date="";
        }else{
        $this->data['row']->start_date=$sql[0]->start_date;
        $this->data['row']->end_date=$sql[0]->end_date;
    }
        $this->data['row']->preventive_action=$sql[0]->preventive_action;
        $this->data['row']->corrective_action=$sql[0]->corrective_action;
        // var_dump($this->data['row']);die();
        $this->data['pageMethod']=$_GET['btnval'];
        $technician=json_decode($sql[0]->technician);
                if($technician !==null)
                {
                $technician=implode($technician,",");
                }
                else                    
                {
                $technician=''; 
                }
                $this->data['row']->technician=$this->jcustomselect('hr_employee_t','employee_id','employee_number|first_name',$technician," and department LIKE '%53%'");
        }else{
        $this->data['row']= (object)array();
                   $userid=(\Session::get('department_id'));
                     $this->data['dept_id']=$userid;
                       $table= DB::getSchemaBuilder()->getColumnListing("b_maintenance_t");

   foreach ($table as $key => $val) {
      $this->data['row']->$val="";
     }
        $this->data['row']->breakdown_sevearity=$this->jCombologin('breakdown_severity','breakdownseverity_id','severity_name','');
       $this->data['row']->department_id=$this->jcustomselect('m_department_lines_t','department_line_id','sub_department_code|sub_department_name',$userid," and sub_department_code LIKE '%c010%'");
        $this->data['row']->break_type_id=$this->jCombologin('m_breakdowntype_t','breakdowntype_id','breakdown_name','');
        // $this->data['row']->machine_id=$this->jCombologin('machine_hdr_t','department_id','asset_code|machine_name',$userid);
       $this->data['row']->issue_date=date('Y-m-d H:i');
      $this->data['parent_id'] ='0';

        $this->data['lastmaintenance']=array();
      $this->data['row']->spares_id='';
     //    $this->data['row']->engineer='';
     //    $this->data['row']->machine_id='';
     //    $this->data['row']->maintenance_type='';
     //    $this->data['row']->causes='';
     //   $this->data['row']->ticket_number='';
     //    $this->data['row']->active='';
     //    $this->data['row']->issue_date='';
     //    $this->data['row']->id='';
     //    $this->data['pageMethod']='';
     //    $this->data['row']->technician='';
     //    $this->data['row']->critical_spare='';
     //    $this->data['row']->others='';
     //    $this->data['row']->start_date ='';
     //     $this->data['row']->preventive_action='';
     //    $this->data['row']->end_date='';
     //    $this->data['row']->corrective_action='';
      


        }//dd($this->data);
        return view('ticketsystem.form',$this->data);
    }

    
    public function save(Request $request)
    {
      //dd($_POST);
       /* print_r($_POST);
    dd($_POST);*/
  //  var_dump($_POST);die();
                $edit_id = $request->input('id');
                if($edit_id=="")
                {
                $breakdown = new Breakdownmaintenance();
                $breakdown->ticket_number = $request->input('ticket_number');
                $breakdown->department_id = $request->input('department_id');
                $breakdown->machine_id = $request->input('machine_id');
                $breakdown->break_type_id = $request->input('break_type_id');
                $breakdown->active      = $request->input('active');
                $breakdown->issue_date       = date("Y-m-d h:i:sa", strtotime($request->input('issue_date')));
                $breakdown->causes           = $request->input('causes');

               // $breakdown->ticket_number           = $request->input('');
                $breakdown->breakdown_sevearity           = $request->input('breakdown_severity');
                $breakdown->maintenance_type           = $request->input('maintenance_type');
                $breakdown->shift           = $request->input('shift');
                $breakdown->company_id      = \Session::get('companyid');
                $breakdown->organization_id          = \Session::get('organization');
                $breakdown->location_id          = \Session::get('location');
                $breakdown->issue_created_by          = \Session::get('id');
                $breakdown->issue_created_on          = date("Y-m-d");
                $breakdown->request_status          = "OPEN";
              
              $seqno = $this->Seqnoe("TICKET", 'b_maintenance_t', '','ticket_count');
                $breakdown->ticket_number = $seqno[0];
                $breakdown->ticket_count = $seqno[1];
               $breakdown->save();
        
            $edit_id= DB::getPdo()->lastInsertId();
            $action="Create";
            
               $main=\DB::table('b_maintenance_t')->select('issue_created_by','ticket_number','causes','breakdown_sevearity')->where('id','=',$edit_id)->get();
               $severity = \DB::table('breakdown_severity')->select('severity_name')->where('breakdownseverity_id','=',$main[0]->breakdown_sevearity)->get();
            //  $getmail=\DB::table('hr_employee_t')->select('email',DB::raw("CONCAT(employee_number,'-',first_name) AS full_name"))->where('employee_id','=',$_POST['engineer'])->get();
                $user_clear=\DB::table('hr_employee_t')->select(DB::raw("CONCAT(employee_number,'-',first_name) AS full_name","email"))->where('employee_id','=',$main[0]->issue_created_by)->get();
               // dd($user_clear);
                // array_push($_POST['cc'],$user_clear[0]->email);
               $machine=\DB::table('w_machine_hdr_t')->select(DB::raw("CONCAT(machine_code,'-',machine_name) AS full_name"))->where('machine_hdr_id','=',$_POST['machine_id'])->get();
                       //   $_POST['mail']=$getmail[0]->email;
                          $maintenance['ticket_number']=$main[0]->ticket_number;
                          $maintenance['issue_date']=$_POST['issue_date'];
                          $maintenance['machine_name']=$machine[0]->full_name;
                          $maintenance['user_clear']=$user_clear[0]->full_name;
                          $maintenance['causes']=$main[0]->causes;
                          $maintenance['severity_name']=$severity[0]->severity_name;
                          $maintenance['ticket_status'] = "OPEN";
                          //dd($maintenance);
             //dd($_POST['cc']);
                           if(!empty(\Session::get('user_email'))&&!empty(\Session::get('user_password'))){
                Config::set('mail.username', \Session::get('user_email'));
                Config::set('mail.password', \Session::get('user_password'));

            } 
                if(\Session::get('user_email')!=''){         
             \Mail::send('Breakdownmaintenance.mail',$maintenance, function($message){
                 $message->cc("gayathri_rajagopal@jrkresearch.com");
                 $message->cc("aruna_v@jrkresearch.com");
                 $message->cc("hemashree_k@jrkresearch.com");
                 $message->cc("uma_p@jrkresearch.com");
                  $message->to("maintenance@jrkresearch.com");
                // $message->to($_POST['mail']);
                 $message->from(\Session::get('user_email'));
                        // $message->from('ifivetechteam@gmail.com');
                         $message->subject("Breakdown Ticket Generated");
             });
           }
               
            $this->auditlog($edit_id,"Breakdownmaintenance",$action,$_POST,"b_maintenance_t");
            return response()->json(array('status' => 'success', 'message' => 'Ticket Generated Successfully','id'=>$edit_id));
        }else if($_POST['request_status']=="CLOSED")
            {

        
             $data['request_status']=$_POST['request_status'];
             $data['start_date']=date("Y-m-d h:i:sa", strtotime($request->input('start_date')));
             $data['end_date']=date("Y-m-d h:i:sa", strtotime($request->input('end_date')));
             $data['is_breakdown']=$_POST['is_breakdown'];
             $data['preventive_action']=$_POST['preventive_action'];
             $data['corrective_action']=$_POST['corrective_action'];
             $data['critical_spare']=$_POST['critical_spare'];
             $data['others']=$_POST['others'];
              $data['closed_engineer_by'] = \Session::get('id');
             $data['closed_engineer_on'] = date("Y-m-d");
            
             $lines_data = $this->validatePost($request->all(),$this->subtable,'lines');
          /*deepika purpose:subtract qty from spare qty*/
            if($_POST['critical_spare']=="Yes"){
                 $trsns =\DB::table('m_transaction_types_t')->where('transaction_type_name','SPARE USED')->get();
                $trsns= json_decode( json_encode($trsns),true);
        foreach($_POST['bulk_spares_id'] as $k=>$v){
                                    $data1['trx_source_type_id']=$trsns[0]['transaction_source_id'];
      $data1['trx_action_id']=$trsns[0]['transaction_action_id'];
      $data1['trx_type_id']=$trsns[0]['transaction_type_id'];
      $data1['trx_source_hdr_id']=$edit_id;
      $data1['product_id']=$v;
      $data1['trx_date']= date('Y-m-d');
      $data1['created_by']= \Session::get('id');
      $data1['created_at']= date('Y-m-d');
            $data1['organization_id']=\Session::get('organization');
      $data1['location_id']=\Session::get('location');
      $data1['company_id']=\Session::get('companyid');
                                    $data1['trx_qty']= -$_POST['bulk_qty'][$k];
                                    $mtlid = \DB::table('m_material_trx_t')->insertGetId($data1);
                                    $dataqoh['create_trx_id']=$mtlid;
                                    $dataqoh['qoh_source']="SPARE USED";
                  $dataqoh['organization_id']=\Session::get('organization');
                  $dataqoh['location_id']=\Session::get('location');
                  $dataqoh['company_id']=\Session::get('companyid');
                        $dataqoh['qoh_trx_qty']=-$_POST['bulk_qty'][$k];
                                    $dataqoh['product_id']=$v;
                                 $qohid = \DB::table('i_qoh_detail_t')->insertGetId($dataqoh);
        //DB::table('m_spares_t')->where('spares_id',$v)->update(['spare_quantity'=>$spareqty]);    
        }
      }

          /* print_r($data);
              dd($data);*/
             
          /*end*/
              Breakdownmaintenance::find($edit_id)->update($data); 
             unset($lines_data['technician']);
            $lid=$this->submodel->subgridSave($lines_data,$edit_id); 
            $this->auditlog($edit_id,"Breakdownmaintenance","closed",$_POST,"b_maintenance_t");
              
            $tech='';
            if(isset($_POST['technician'])){
                 $_POST['cc']=array();
                foreach($_POST['technician'] as $key=>$value){
                     $getmail=\DB::table('hr_employee_t')->select('email',DB::raw("CONCAT(employee_number,'-',first_name) AS full_name"))->where('employee_id','=',$value)->get();
                     if(count($getmail)>0){
                  array_push($_POST['cc'],$getmail[0]->email);
                  $tech.=$getmail[0]->full_name.",";
                     }
                }
           $tech=rtrim($tech, ',');
           
               $main=\DB::table('b_maintenance_t')->select('issue_created_by')->where('id','=',$_POST['id'])->get();
              $getmail=\DB::table('hr_employee_t')->select('email',DB::raw("CONCAT(employee_number,'-',first_name) AS full_name"))->where('employee_id','=',$_POST['engineer'])->get();
                $tomail = \DB::table('tb_users')->select('email')->where('employee_id','=',$main[0]->issue_created_by)->get();
                $user_clear=\DB::table('hr_employee_t')->select(DB::raw("CONCAT(employee_number,'-',first_name) AS full_name"))->where('employee_id','=',$main[0]->issue_created_by)->get();
                 array_push($_POST['cc'],$user_clear[0]->email);
               $machine=\DB::table('w_machine_hdr_t')->select(DB::raw("CONCAT(machine_code,'-',machine_name) AS full_name"))->where('machine_hdr_id','=',$_POST['machine_id'])->get();
                          $_POST['mail']=$tomail[0]->email;
                          $maintenance['ticket_number']=$_POST['ticket_number'];
                          $maintenance['issue_date']=$_POST['issue_date'];
                          $maintenance['machine_name']=$machine[0]->full_name;
                          $maintenance['user_clear']=$user_clear[0]->full_name;
                          $maintenance['full_name']=$getmail[0]->full_name;
                          $maintenance['tech']=$tech;
                          $maintenance['ticket_status'] = $_POST['request_status'];
                          //dd($maintenance);
             //dd($_POST['cc']);
                           if(!empty(\Session::get('user_email'))&&!empty(\Session::get('user_password'))){
                Config::set('mail.username', \Session::get('user_email'));
                Config::set('mail.password', \Session::get('user_password'));

            } 
                if(\Session::get('user_email')!=''){         
             \Mail::send('Breakdownmaintenance.mail',$maintenance, function($message){
                 $message->cc("gayathri_rajagopal@jrkresearch.com");
                 $message->cc("aruna_v@jrkresearch.com");
                 $message->cc("hemashree_k@jrkresearch.com");
                 $message->cc("uma_p@jrkresearch.com");
                //  $message->to("aspire@jrkresearch.com");
                 $message->to($_POST['mail']);
                 $message->from(\Session::get('user_email'));
                        // $message->from('ifivetechteam@gmail.com');
                         $message->subject($_POST['ticket_number']." - Ticket Closed");
             });
           }
            return response()->json(array('status' => 'success', 'message' => 'Closed Successfully','id'=>$edit_id));
            
       }

        }else{
         
             $action="Edit";
             $data['engineer']=$_POST['engineer'];
             if(isset($_POST['engineer'])){
             $data['allocate_engineer_by'] = \Session::get('id');
             $data['allocate_engineer_on'] = date("Y-m-d");
             $message="Allocate Engineer Successfully";
//       /  dd("Dfd");
            
          
            $tech='';
         //  dd($_POST['request_status']);
            if(isset($_POST['technician'])){
                if($_POST['request_status']!="REQUESTED"&& $_POST['request_status']!="APPROVED" && $_POST['request_status']!="REJECT"){
                 $_POST['cc']=array();
                foreach($_POST['technician'] as $key=>$value){
                     $getmail=\DB::table('hr_employee_t')->select('email',DB::raw("CONCAT(employee_number,'-',first_name) AS full_name"))->where('employee_id','=',$value)->get();
                     if(count($getmail)>0){
                  array_push($_POST['cc'],$getmail[0]->email);
                  $tech.=$getmail[0]->full_name.",";
                     }
                }
            
           $tech=rtrim($tech, ',');
          
          //  dd($_POST['cc']);
    
               $main=\DB::table('b_maintenance_t')->select('issue_created_by')->where('id','=',$_POST['id'])->get();
               $getmail=\DB::table('hr_employee_t')->select('email',DB::raw("CONCAT(employee_number,'-',first_name) AS full_name"))->where('employee_id','=',$_POST['engineer'])->get();
               $user_clear=\DB::table('hr_employee_t')->select('email',DB::raw("CONCAT(employee_number,'-',first_name) AS full_name"))->where('employee_id','=',$main[0]->issue_created_by)->get();
              array_push($_POST['cc'],$user_clear[0]->email);
               $machine=\DB::table('w_machine_hdr_t')->select(DB::raw("CONCAT(machine_code,'-',machine_name) AS full_name"))->where('machine_hdr_id','=',$_POST['machine_id'])->get();
                          $_POST['mail']=$getmail[0]->email;
                          $maintenance['ticket_number']=$_POST['ticket_number'];
                          $maintenance['issue_date']=$_POST['issue_date'];
                          $maintenance['machine_name']=$machine[0]->full_name;
                          $maintenance['user_clear']=$user_clear[0]->full_name;
                          $maintenance['full_name']=$getmail[0]->full_name;
                          $maintenance['tech']=$tech;
                          //dd($maintenance['ticket_number']);
                            
           //  dd($_POST['cc']);
                  //  dd(\Session::get('user_email'));
                           if(!empty(\Session::get('user_email'))&&!empty(\Session::get('user_password'))){
               Config::set('mail.username', \Session::get('user_email'));
               Config::set('mail.password', \Session::get('user_password'));

           }  
/*if(\Session::get('user_email')!=''){
                        //  dd($maintenance);
             \Mail::send('Breakdownmaintenance.mail',$maintenance, function($message){
                 $message->cc($_POST['cc']);
                $message->to($_POST['mail']);
             // $message->to('ifivetechteam@gmail.com');
                     $message->from(\Session::get('user_email'));

                         // $message->from('ifivetechteam@gmail.com');
                         $message->subject("Ticket Allocated");
             });
         //  dd("gfdg");
          }*/
            }
            }
             }
              if(isset($_POST['technician'])){
              $data['allocate_technician_by'] = \Session::get('id');
             $data['allocate_technician_on'] = date("Y-m-d");
              $message="Allocate Engineer Successfully";
             }
              if($_POST['request_status']=="REQUESTED"){
              $data['request_request_by'] = \Session::get('id');
             $data['request_request_on'] = date("Y-m-d");
             $data['request_remark'] = $request->input('request_remark');
                 $message="Requested Successfully";
             }
             if($_POST['request_status']=="APPROVED"){
              $data['request_approve_by'] = \Session::get('id');
             $data['request_approve_on'] = date("Y-m-d");
             $data['approve_remarks'] = $request->input('approve_remarks');
              $message="Approved Successfully";
             }
             if($_POST['request_status']=="REJECTED"){
             $data['request_approve_by'] = \Session::get('id');
             $data['request_approve_on'] = date("Y-m-d");
             $data['approve_remarks'] = $request->input('approve_remarks');
              $message="Rejected Successfully";
             }
             if(isset($_POST['technician']))
             {
               $data['technician']=json_encode($_POST['technician']);
                 
             }

            $data['request_status']=$_POST['request_status'];
             if(isset($_POST['error_code'])){
 if($request->hasfile('choosefile'))
                    {
                        $file = $request->file('choosefile');
                        
                            $name=$file->getClientOriginalName();

                            $file->move(public_path().'/upload/sop/', $name);  
                            $dataupload = $name;  
                        
                    
                     $attachfile_name=$dataupload;  
                     $data['error_code']=$_POST['error_code'];
                     $data['request_status']="CLOSED";
                     $message="SOP Uploaded Successfully";
                     // print_r($attachfile_name);
                     // dd($attachfile_name);
                     \DB::update("update b_maintenance_t set files='".$attachfile_name."' where id='$edit_id'");
                 }
                 }
                 //var_dump($data);
               //  dd($data);
            Breakdownmaintenance::find($edit_id)->update($data);
            
            $tech='';
    if($_POST['request_status']=="REQUESTED"){
            if(isset($_POST['technician'])){
                 $_POST['cc']=array();
                foreach($_POST['technician'] as $key=>$value){
                     $getmail=\DB::table('hr_employee_t')->select('email',DB::raw("CONCAT(employee_number,'-',first_name) AS full_name"))->where('employee_id','=',$value)->get();
                     if(count($getmail)>0){
                  array_push($_POST['cc'],$getmail[0]->email);
                  $tech.=$getmail[0]->full_name.",";
                     }
                }
           $tech=rtrim($tech, ',');
           
               $main=\DB::table('b_maintenance_t')->select('issue_created_by','request_remark','request_request_on','request_request_by')->where('id','=',$_POST['id'])->get();
               $request_by = \DB::table('hr_employee_t')->select(DB::raw("CONCAT(employee_number,'-',first_name) AS request_name"))->where('employee_id','=',$main[0]->request_request_by)->get();
              $getmail=\DB::table('hr_employee_t')->select('email',DB::raw("CONCAT(employee_number,'-',first_name) AS full_name"))->where('employee_id','=',$_POST['engineer'])->get();
                $tomail = \DB::table('tb_users')->select('email')->where('employee_id','=',$main[0]->issue_created_by)->get();
                $user_clear=\DB::table('hr_employee_t')->select(DB::raw("CONCAT(employee_number,'-',first_name) AS full_name"),"email")->where('employee_id','=',$main[0]->issue_created_by)->get();
                 array_push($_POST['cc'],$user_clear[0]->email);
               $machine=\DB::table('w_machine_hdr_t')->select(DB::raw("CONCAT(machine_code,'-',machine_name) AS full_name"))->where('machine_hdr_id','=',$_POST['machine_id'])->get();
                          $_POST['mail']=$tomail[0]->email;
                          $maintenance['ticket_number']=$_POST['ticket_number'];
                          $maintenance['issue_date']=$_POST['issue_date'];
                          $maintenance['machine_name']=$machine[0]->full_name;
                          $maintenance['user_clear']=$user_clear[0]->full_name;
                          $maintenance['full_name']=$getmail[0]->full_name;
                          $maintenance['tech']=$tech;
                          $maintenance['request_by'] = $request_by[0]->request_name;
                          $maintenance['request_on'] = $main[0]->request_request_on;
                          $maintenance['request_remark'] = $main[0]->request_remark;
                          $maintenance['ticket_status'] = $_POST['request_status'];
                          //dd($maintenance);
             //dd($_POST['cc']);
                           if(!empty(\Session::get('user_email'))&&!empty(\Session::get('user_password'))){
                Config::set('mail.username', \Session::get('user_email'));
                Config::set('mail.password', \Session::get('user_password'));

            } 
                if(\Session::get('user_email')!=''){         
             \Mail::send('Breakdownmaintenance.mail',$maintenance, function($message){
                 $message->cc("gayathri_rajagopal@jrkresearch.com");
                 $message->cc("aruna_v@jrkresearch.com");
                 $message->cc("hemashree_k@jrkresearch.com");
                 $message->cc("uma_p@jrkresearch.com");
                //  $message->to("aspire@jrkresearch.com");
                 $message->to($_POST['mail']);
                 $message->from(\Session::get('user_email'));
                        // $message->from('ifivetechteam@gmail.com');
                         $message->subject($_POST['ticket_number']." - Ticket Closure Requested");
             });
           }
            }    
    }      
    
    if($_POST['request_status']=="APPROVED" || $_POST['request_status']=="REJECTED"){
            if(isset($_POST['technician'])){
                 $_POST['cc']=array();
                foreach($_POST['technician'] as $key=>$value){
                     $getmail=\DB::table('hr_employee_t')->select('email',DB::raw("CONCAT(employee_number,'-',first_name) AS full_name"))->where('employee_id','=',$value)->get();
                     if(count($getmail)>0){
                  array_push($_POST['cc'],$getmail[0]->email);
                  $tech.=$getmail[0]->full_name.",";
                     }
                }
           $tech=rtrim($tech, ',');
           
               $main=\DB::table('b_maintenance_t')->select('issue_created_by','approve_remarks','request_approve_on','request_approve_by','request_request_by')->where('id','=',$_POST['id'])->get();
               $approve_by = \DB::table('hr_employee_t')->select(DB::raw("CONCAT(employee_number,'-',first_name) AS approve_name"))->where('employee_id','=',$main[0]->request_approve_by)->get();
              $getmail=\DB::table('hr_employee_t')->select('email',DB::raw("CONCAT(employee_number,'-',first_name) AS full_name"))->where('employee_id','=',$_POST['engineer'])->get();
                $tomail = \DB::table('tb_users')->select('email')->where('employee_id','=',$main[0]->request_request_by)->get();
                $user_clear=\DB::table('hr_employee_t')->select(DB::raw("CONCAT(employee_number,'-',first_name) AS full_name"),"email")->where('employee_id','=',$main[0]->issue_created_by)->get();
                 array_push($_POST['cc'],$user_clear[0]->email);
               $machine=\DB::table('w_machine_hdr_t')->select(DB::raw("CONCAT(machine_code,'-',machine_name) AS full_name"))->where('machine_hdr_id','=',$_POST['machine_id'])->get();
                          $_POST['mail']=$tomail[0]->email;
                          $maintenance['ticket_number']=$_POST['ticket_number'];
                          $maintenance['issue_date']=$_POST['issue_date'];
                          $maintenance['machine_name']=$machine[0]->full_name;
                          $maintenance['user_clear']=$user_clear[0]->full_name;
                          $maintenance['full_name']=$getmail[0]->full_name;
                          $maintenance['tech']=$tech;
                          if($_POST['request_status']=="APPROVED" || $_POST['request_status']=="REJECTED" ){
                          $maintenance['approve_by'] = $approve_by[0]->approve_name;
                          $maintenance['approve_on'] = $main[0]->request_approve_on;
                          $maintenance['approve_remark'] = $main[0]->approve_remarks;
                          }
                          $maintenance['ticket_status'] = $_POST['request_status'];
                          //dd($maintenance);
             //dd($_POST['cc']);
                           if(!empty(\Session::get('user_email'))&&!empty(\Session::get('user_password'))){
                Config::set('mail.username', \Session::get('user_email'));
                Config::set('mail.password', \Session::get('user_password'));

            } 
                if(\Session::get('user_email')!=''){         
             \Mail::send('Breakdownmaintenance.mail',$maintenance, function($message){
                 $message->cc("gayathri_rajagopal@jrkresearch.com");
                 $message->cc("aruna_v@jrkresearch.com");
                 $message->cc("hemashree_k@jrkresearch.com");
                 $message->cc("uma_p@jrkresearch.com");
                  $message->to("maintenance@jrkresearch.com");
               //  $message->to($_POST['mail']);
                 $message->from(\Session::get('user_email'));
                        // $message->from('ifivetechteam@gmail.com');
                        if($_POST['request_status']=="APPROVED"){
                         $message->subject($_POST['ticket_number']." - Ticket Closure Approved");
                        }else{
                         $message->subject($_POST['ticket_number']." - Ticket Closure Rejected");   
                        }
             });
           }
            }    
    }
    
            /**Auditlog**/
            $this->auditlog($edit_id,"Breakdownmaintenance",$action,$_POST,"b_maintenance_t");
            return response()->json(array('status' => 'success', 'message' =>$message,'id'=>$edit_id));
        }
    }

         public function ticketrequestData($type=null){
    
    $wh='';
    $search_table=array("");
        
    if($_GET['_search']=='true')
    {
        
    $wh=$this->jqgridsearch('b_ticketsystem_t',$_GET['filters'],$search_table);
    }
      $org=\Session::get('organization');
        $loc=\Session::get('location');
        $compy=\Session::get('companyid');
        $wh.='and b_ticketsystem_t.company_id='.$compy; 
      
    $page = $_GET['page'];
    $limit = $_GET['rows'];
    $sidx = $_GET['sidx'];
    $sord = $_GET['sord'];
    if(!$sidx) $sidx =1;

$result = \DB::select("SELECT COUNT(id) AS count FROM b_maintenance_t left join(m_department_lines_t) on (m_department_lines_t.department_line_id=b_maintenance_t.department_id) left join(m_breakdowntype_t) on (m_breakdowntype_t.breakdowntype_id=b_maintenance_t.break_type_id) left join breakdown_severity on breakdown_severity.breakdownseverity_id=b_maintenance_t.breakdown_sevearity where 1=1 $wh");
    $count = $result[0]->count;
    if( $count > 0 && $limit > 0)
    {
    $total_pages = ceil($count/$limit);
    } else {
    $total_pages = 0;
    }
    if ($page > $total_pages)
    $page=$total_pages;
    $start = $limit*$page - $limit;
    if($start <0) $start = 0;
    
   
   
   
    $SQL = "SELECT b_maintenance_t.*,m_department_lines_t.sub_department_name as department_name ,m_breakdowntype_t.breakdown_name,breakdown_severity.severity_name,w_machine_hdr_t.machine_name

FROM
    b_maintenance_t left join(m_department_lines_t) on (m_department_lines_t.department_line_id=b_maintenance_t.department_id) left join(m_breakdowntype_t) on (m_breakdowntype_t.breakdowntype_id=b_maintenance_t.break_type_id) left join breakdown_severity on breakdown_severity.breakdownseverity_id=b_maintenance_t.breakdown_sevearity LEFT JOIN w_machine_hdr_t ON b_maintenance_t.machine_id = w_machine_hdr_t.machine_hdr_id where 1=1 $wh ORDER BY $sidx $sord LIMIT $start , $limit";


 $download_SQL = "SELECT b_maintenance_t.*,m_department_lines_t.sub_department_name as department_name ,m_breakdowntype_t.breakdown_name,breakdown_severity.severity_name,w_machine_hdr_t.machine_name

FROM
    b_maintenance_t left join(m_department_lines_t) on (m_department_lines_t.department_line_id=b_maintenance_t.department_id) left join(m_breakdowntype_t) on (m_breakdowntype_t.breakdowntype_id=b_maintenance_t.break_type_id) left join breakdown_severity on breakdown_severity.breakdownseverity_id=b_maintenance_t.breakdown_sevearity LEFT JOIN w_machine_hdr_t ON b_maintenance_t.machine_id = w_machine_hdr_t.machine_hdr_id where 1=1 $wh ORDER BY $sidx $sord";
    $result1 = \DB::select( $download_SQL );
        $result1=collect($result1)->map(function($x){ return (array) $x; })->toArray();
    if(isset($_GET['download']))
    {
        return $result1;
    }


    $result = \DB::select( $SQL );
    $responce->rows[]='';
    $responce->rows=$result;
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    echo json_encode($responce);
    }

    function view($id=null)
    {
        //dd($id);
            $this->data['pageMethod']=$_GET['btnval'];

        $data = \DB::table('b_maintenance_t')
        ->leftjoin('m_department_lines_t','m_department_lines_t.department_line_id','=','b_maintenance_t.department_id')
        ->leftjoin('w_machine_hdr_t','w_machine_hdr_t.machine_hdr_id','=','b_maintenance_t.machine_id')
        ->leftjoin('m_breakdowntype_t','m_breakdowntype_t.breakdowntype_id','=','b_maintenance_t.break_type_id')
        ->leftjoin('breakdown_severity','breakdown_severity.breakdownseverity_id','=','b_maintenance_t.breakdown_sevearity')
        ->leftjoin('tb_users','tb_users.id','=','b_maintenance_t.engineer')
            ->select('b_maintenance_t.issue_date','b_maintenance_t.ticket_number','b_maintenance_t.maintenance_type','b_maintenance_t.causes','m_department_lines_t.sub_department_name as department_name','w_machine_hdr_t.machine_name','m_breakdowntype_t.breakdown_name','breakdown_severity.severity_name','b_maintenance_t.maintenance_type','b_maintenance_t.active','tb_users.username','tb_users.first_name')
            ->where('b_maintenance_t.id',$id)
            ->get();
         
      //  $this->data['data'] = $data;
      //  dd($this->data['data']);

            $this->data['department_name'] = $data[0]->department_name;
                        $this->data['first_name'] = $data[0]->first_name;

            //$this->data['department_name'] = $data[0]->first_name;

            //$this->data['issue_date'] = $data[0]->issue_date;
           $this->data['issue_date']=date(\Session::get('p_date_format'), strtotime($data[0]->issue_date));
            $this->data['maintenance_type'] = $data[0]->maintenance_type;
            $this->data['causes'] = $data[0]->causes;
            $this->data['ticket_number'] = $data[0]->ticket_number;
            $this->data['machine_name'] = $data[0]->machine_name;
            $this->data['breakdown_name'] = $data[0]->breakdown_name;
            $this->data['severity_name'] = $data[0]->severity_name;
            $this->data['active'] = $data[0]->active;
       // $this->data['data']=$data;    
//dd($this->data);
        return view('ticketsystem.view',$this->data);

    }

       function show($id=null)
    {
              //  $this->data['pageMethod']=$_GET['btnval'];

        $data = \DB::table('b_maintenance_t')
        ->leftjoin('m_department_lines_t','m_department_lines_t.department_line_id','=','b_maintenance_t.department_id')
        ->leftjoin('w_machine_hdr_t','w_machine_hdr_t.machine_hdr_id','=','b_maintenance_t.machine_id')
        ->leftjoin('m_breakdowntype_t','m_breakdowntype_t.breakdowntype_id','=','b_maintenance_t.break_type_id')
        ->leftjoin('breakdown_severity','breakdown_severity.breakdownseverity_id','=','b_maintenance_t.breakdown_sevearity')
        ->leftjoin('tb_users as engineer','engineer.id','=','b_maintenance_t.engineer')
        ->leftjoin('tb_users as technicians','technicians.id','=','b_maintenance_t.technician')
            ->select('b_maintenance_t.issue_date','b_maintenance_t.preventive_action','b_maintenance_t.shift','b_maintenance_t.ticket_number','b_maintenance_t.is_breakdown','b_maintenance_t.error_code','b_maintenance_t.end_date','b_maintenance_t.start_date','b_maintenance_t.causes','m_department_lines_t.sub_department_name as department_name','w_machine_hdr_t.machine_name','m_breakdowntype_t.breakdown_name','breakdown_severity.severity_name','b_maintenance_t.maintenance_type','b_maintenance_t.active','engineer.first_name as e_name','technicians.first_name as t_name')
            ->where('b_maintenance_t.id',$id)
            ->get();
       //  dd($data);
      //  $this->data['data'] = $data;
      //  dd($this->data['data']);

            $this->data['department_name'] = $data[0]->department_name;
             $this->data['e_name'] = $data[0]->e_name;
              $this->data['t_name'] = $data[0]->t_name;
           $this->data['shift'] = $data[0]->shift;
            $this->data['error_code'] = $data[0]->error_code; 
            $this->data['start_date'] = $data[0]->start_date;
            $this->data['end_date'] = $data[0]->end_date;
           $this->data['issue_date']=date(\Session::get('p_date_format'), strtotime($data[0]->issue_date));
            $this->data['maintenance_type'] = $data[0]->maintenance_type;
            $this->data['causes'] = $data[0]->causes;
            $this->data['ticket_number'] = $data[0]->ticket_number;
            $this->data['machine_name'] = $data[0]->machine_name;
            $this->data['breakdown_name'] = $data[0]->breakdown_name;
            $this->data['severity_name'] = $data[0]->severity_name;
       
            $this->data['preventive_action'] = $data[0]->preventive_action;
            $this->data['is_breakdown'] = $data[0]->is_breakdown;
 
//dd($this->data);
        return view('ticketsystem.sopview',$this->data);

    }


 public  function view1($id=null)
    {
        $data = \DB::table('b_maintenance_t')
        ->leftjoin('m_department_lines_t','m_department_lines_t.department_line_id','=','b_maintenance_t.department_id')
        ->leftjoin('w_machine_hdr_t','w_machine_hdr_t.machine_hdr_id','=','b_maintenance_t.machine_id')
        ->leftjoin('m_breakdowntype_t','m_breakdowntype_t.breakdowntype_id','=','b_maintenance_t.break_type_id')
        ->leftjoin('breakdown_severity','breakdown_severity.breakdownseverity_id','=','b_maintenance_t.breakdown_sevearity')
        ->leftjoin('tb_users','tb_users.id','=','b_maintenance_t.engineer')
            ->select('b_maintenance_t.issue_date','b_maintenance_t.ticket_number','b_maintenance_t.maintenance_type','b_maintenance_t.causes','m_department_lines_t.sub_department_name as department_name ','w_machine_hdr_t.machine_name','m_breakdowntype_t.breakdown_name','breakdown_severity.severity_name','b_maintenance_t.maintenance_type','b_maintenance_t.active','tb_users.username','tb_users.first_name')
            ->where('b_maintenance_t.id',$id)
            ->get();

                $html = '';
                        //$grnlineid=$_GET['line_id'];
               
                    $html.='<table class="serial serial"><thead>
                           <thead>
                          <tr>
                          <th>Line No</th>
                          <th>Ticket Number</th>
                          <th>Machine Name</th>
                          <th>Department</th>
                          <th>Issue Date</th>
                          <th>Maintenance Type</th>
                          <th>Allocate Engineer</th>
                          <th>Allocate Technician</th>
                          </tr>    
                            </thead><tbody>';
   
                 if(count($data)>0){
foreach($data as $key=>$val){
               
     
        $html.='<tr class="table'.$key.'">
               <td><input type="text" name="line_no[]" class="input-sm s_no" value="'.($key+1).'" readonly="readonly" style="width:68px !important; color:black;">
               </td>';
      
        $html.='<td><input type="text"  name="bulk_parameter'.$grnlineid.'['.$key.']" class="input-sm bulk_parameter'.$grnlineid.$key.'" value="'.$val->parameter.'" readonly="readonly" style="width:150px !important; color:black;">
             </td>
             <td><input type="text"  name="bulk_spec_criteria'.$grnlineid.'['.$key.']" class="input-sm bulk_spec_criteria'.$grnlineid.$key.'" value="'.$spec[0]->lookup_code.'" readonly="readonly" style="width:150px !important;color:black;"></td>
             <td><input type="text"  name="bulk_spec_value_from'.$grnlineid.'['.$key.']" class="input-sm bulk_spec_value_from'.$grnlineid.$key.'" value="'.$val->spec_value_from.'" readonly="readonly" style="width:100px !important;color:black;"></td>
             <td><input type="text"  name="bulk_spec_value_to'.$grnlineid.'['.$key.']" class="input-sm bulk_spec_value_to'.$grnlineid.$key.'" value="'.$val->spec_value_to.'" readonly="readonly" style="width:100px !important;color:black;"></td>';


        // dd($data);
      //  $this->data['data'] = $data;
      //  dd($this->data['data']);

            $this->data['department_name'] = $data[0]->department_name;
                        $this->data['first_name'] = $data[0]->first_name;

            //$this->data['department_name'] = $data[0]->first_name;

            //$this->data['issue_date'] = $data[0]->issue_date;
            $this->data['issue_date']=date(\Session::get('p_date_format'), strtotime($data[0]->issue_date));
            $this->data['maintenance_type'] = $data[0]->maintenance_type;
            $this->data['causes'] = $data[0]->causes;
            $this->data['ticket_number'] = $data[0]->ticket_number;
            $this->data['machine_name'] = $data[0]->machine_name;
            $this->data['breakdown_name'] = $data[0]->breakdown_name;
            $this->data['severity_name'] = $data[0]->severity_name;
            $this->data['active'] = $data[0]->active;
       // $this->data['data']=$data;    
// dd($this->data);
        return view('home',$this->data);

    }
}
}
     public function getspareqty($hdrid=null){

        $sql=\DB::select("SELECT sum(qoh_trx_qty)  as qty FROM `i_qoh_detail_t` where product_id= '$hdrid' ");
//dd($sql);
        return $sql;
    }



}
