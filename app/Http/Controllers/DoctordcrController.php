<?php

namespace App\Http\Controllers;

use App\Doctordcr;
use Illuminate\Http\Request;

class DoctordcrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->data=array();
        $this->model    = new Doctordcr();
        $this->data['urlmenu']=$this->indexs(); 
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['pageFormtype']='ajax';
        $this->data['pageModule']='Doctordcr';
        $this->table="app_doctor_dcr_t";
        $this->subtable="app_doctor_dcr_t";
        $this->pobtable="app_pob_detail_t";
        $this->gifttable="app_gift_sampledetail_t";
        $this->sampletable="app_product_sampledetail_t";
        $this->postertable="app_poster_detail_t";
        $this->data['urlname']=\Request::route()->getName();
        
    }

    public function index()
    {
        $this->data['pageMethod']=\Request::route()->getName();
        $this->data['urlname']=\Request::route()->getName();
        $this->data['f_area'] = $this->jqgridselect('m_area_t','area_id','area_name');
        $this->data['t_area'] = $this->jqgridselect('m_area_t','area_id','area_name');

        return view("doctordcr.table",$this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null)
    {
        $this->data['pageMethod']="doctordcr";
        $sql=\Session::get('id');
        // dd($sql);
        $this->data['created_by']=$this->jCombologin('tb_users','id','first_name',$sql);

        if($id == '0')
        {
            /******linedata ******/
            $this->data['linedata'] = array(); 
            $dcr_date = \DB::table('app_doctor_dcr_t')->where('created_by',\Session::get('id'))->select('tp_date')->orderBy('tp_date','desc')->first();
            
            $month = date('m');
            
            $date=array();
            if($dcr_date != ''){
                $tp_date = \DB::table('app_tourprogram_t')->where('created_by',\Session::get('id'))->where('status','=','APPROVED')->whereMonth('tour_date',$month)->select('tour_date')->get();

                foreach ($tp_date as $key => $value) {
                    if($dcr_date->tp_date == $value->tour_date){
                        $date = $value->tour_date;
                    }
                }

                if(count($date )>0 ){
                    $tpd = date('Y-m-d', strtotime($date . ' +1 day'));
                }else{
                    $tpd ='';
                }

            }else{
                $tp_date = \DB::table('app_tourprogram_t')->where('created_by',\Session::get('id'))->where('status','=','APPROVED')->select('tour_date')->orderBy('tour_date','asc')->first();
                if($tp_date != null ){
                    $tpd = date('Y-m-d',strtotime($tp_date->tour_date));    
                }else{
                    $tpd ='';
                }
                
            }
            
            $this->data['tp_date']    = $tpd;
            $this->data['from_area']=$this->jCombologin('m_area_t','area_id','area_name','');
            $this->data['doctor_id']=$this->jCombologin('app_doctors_t','doctor_id','doctor_name','');
            
            $this->data['activity_id']=$this->jCombologin('m_activity_t','activity_id','activity_name','');
            $this->data['outcome_id']=$this->jCombologin('m_outcome_t','outcome_id','outcome_name','');
            
            $this->data['focus_product']=$this->jcustomselect('m_products_t','product_id','concatenated_product','',"and product_group_id = '1'");
            $this->data['visit_with']=$this->jCombologin('hr_employee_t','employee_id','first_name','');
            $this->data['hide_newrow'] = 0;
        }else{
            $this->data['linedata'] = array();
            $this->data['hide_newrow'] = 1;
            $sql= \DB::select('select * from app_doctor_dcr_t where doctor_dcr_id in('.$id.')');
            $this->data['tp_date']= $sql[0]->tp_date;
            foreach ($sql as $key => $value) {
                  $this->data['linedata'][$key] = (object) array();
                $this->data['linedata'][$key]->doctor_dcr_id = $value->doctor_dcr_id;    
                $this->data['linedata'][$key]->tp_date =  date( 'd-m-Y',strtotime($value->tp_date));    
                $this->data['linedata'][$key]->divert_detail = $value->divert_detail; 
                $this->data['linedata'][$key]->divert_reason = $value->divert_reason; 
                $this->data['linedata'][$key]->from_area =$this->jCombologin('m_area_t','area_id','area_name',$value->from_area);   
                $this->data['linedata'][$key]->doctor_id=$this->jCombologin('app_doctors_t','doctor_id','doctor_name',$value->doctor_id);

                if($value->focus_product != "null" && $value->focus_product !=''){
                    $focus_product = implode(',', json_decode($value->focus_product));
                    $this->data['linedata'][$key]->focus_product=$this->jcustommultiselect('m_products_t','product_id','concatenated_product',$focus_product,"and product_group_id = '1'");
                }else{
                    $this->data['linedata'][$key]->focus_product=$this->jcustommultiselect('m_products_t','product_id','concatenated_product','',"and product_group_id = '1'");
                }
                $this->data['linedata'][$key]->timing = $value->timing;
                if($value->visit_with != "null" && $value->visit_with != ''){
                    $visit_with = implode(',', json_decode($sql[0]->visit_with));
                    $this->data['linedata'][$key]->visit_with=$this->jcustommultiselect('hr_employee_t','employee_id','first_name',$visit_with,'');
                }else{
                    $this->data['linedata'][$key]->visit_with=$this->jcustommultiselect('hr_employee_t','employee_id','first_name','','');
                }
                $this->data['linedata'][$key]->activity_id=$this->jCombologin('m_activity_t','activity_id','activity_name',$value->activity_id);
                $this->data['linedata'][$key]->outcome_id=$this->jCombologin('m_outcome_t','outcome_id','outcome_name',$value->outcome_id);
                $this->data['linedata'][$key]->dcr_reminder_date =  date( 'd-m-Y',strtotime($value->dcr_reminder_date));
                $this->data['linedata'][$key]->dcr_reminder_reason = $value->dcr_reminder_reason;
                $this->data['linedata'][$key]->remarks = $value->remarks;
                
                $pobdata= \DB::select('select * from app_pob_detail_t where doctor_dcr_id in('.$value->doctor_dcr_id.')');
                $pob_id= $pob_prd = $pob_qty ='';
                foreach ($pobdata as $k1 => $v1) {
                    $pob_id .=$v1->pob_detail_id.",";
                    $pob_prd .=$v1->product_order.",";
                    $pob_qty .=$v1->pob_qty.",";
                }
                
                $this->data['linedata'][$key]->pob_id = rtrim($pob_id,",");
                $this->data['linedata'][$key]->pob_product = rtrim($pob_prd,",");
                $this->data['linedata'][$key]->pob_qty = rtrim($pob_qty,",");

                $giftdata= \DB::select('select * from app_gift_sampledetail_t where doctor_dcr_id in('.$value->doctor_dcr_id.')');
                $gift_id= $gift_prd = $gift_qty ='';

                foreach ($giftdata as $k2 => $v2) {
                    $gift_id .=$v2->gift_sample_id.",";
                    $gift_prd .=$v2->gift_sample.",";
                    $gift_qty .=$v2->gift_qty.",";
                }
                $this->data['linedata'][$key]->gift_id = rtrim($gift_id,",");
                $this->data['linedata'][$key]->gift_product = rtrim($gift_prd,",");
                $this->data['linedata'][$key]->gift_qty = rtrim($gift_qty,",");

                $sampledata= \DB::select('select * from app_product_sampledetail_t where doctor_dcr_id in('.$value->doctor_dcr_id.')');
                $sam_id= $sam_prd = $sam_qty ='';
                
                foreach ($sampledata as $k3 => $v3) {
                    $sam_id .=$v3->product_sample_id.",";
                    $sam_prd .=$v3->product_sample.",";
                    $sam_qty .=$v3->prd_qty.",";
                }
                $this->data['linedata'][$key]->sample_id = rtrim($sam_id,",");
                $this->data['linedata'][$key]->sample_product = rtrim($sam_prd,",");
                $this->data['linedata'][$key]->sample_qty = rtrim($sam_qty,",");

                $posterdata= \DB::select('select * from app_poster_detail_t where doctor_dcr_id in('.$value->doctor_dcr_id.')');
                $poster_id= $poster_prd = $poster_qty ='';

                foreach ($posterdata as $k4 => $v4) {
                    $poster_id .=$v4->poster_detail_id.",";
                    $poster_prd .=$v4->poster_product.",";
                    $poster_qty .=$v4->poster_qty.",";
                }
                
                $this->data['linedata'][$key]->poster_id = rtrim($poster_id,",");
                $this->data['linedata'][$key]->poster_product = rtrim($poster_prd,",");
                $this->data['linedata'][$key]->poster_qty = rtrim($poster_qty,",");

            }   
        }

        $this->data['pob_product']=$this->jcustomselect('m_products_t','product_id','concatenated_product','',"and product_group_id = '1'");
        $this->data['gift_product']=$this->jcustomselect('m_products_t','product_id','concatenated_product','',"and product_group_id = '5'");
        $this->data['sample_product']=$this->jcustomselect('m_products_t','product_id','concatenated_product','',"and product_group_id = '6'");
        $this->data['poster_product']=$this->jcustomselect('m_products_t','product_id','concatenated_product','',"and product_group_id = '7'");        

        return view('doctordcr.form',$this->data);
    }

    public function save(Request $request){

        
        $id='';
        $lines_data = $this->validatePost($request->all(), $this->subtable, 'lines');
        $pob_lines_data = $this->validatePost($request->all(), $this->pobtable, 'lines');
        $gift_lines_data = $this->validatePost($request->all(), $this->gifttable, 'lines');
        $sample_lines_data = $this->validatePost($request->all(), $this->sampletable, 'lines');
        $poster_lines_data = $this->validatePost($request->all(), $this->postertable, 'lines');

        \DB::beginTransaction();
        try{
            // dd($_POST);
            if($_POST['tp_deviation'] == "Yes"){
                $datatp['tp_deviation_reason'] = trim($_POST['tp_deviation_reason']);
                $datatp['tp_deviation'] = $_POST['tp_deviation'];
                $datatp['tp_date'] = date('Y-m-d',strtotime($_POST['tp_date']));
                $datatp['employee_id'] = \Session::get('emp_id');
                $datatp['created_by'] = \Session::get('id');
                $datatp['last_updated_by'] = \Session::get('id');
                $datatp['created_at'] = date('Y-m-d H:s:i');
                $datatp['updated_at'] = date('Y-m-d H:s:i');
                $datatp['organization_id'] = \Session::get('organization');
                $datatp['location_id'] = \Session::get('location');
                $datatp['company_id'] = \Session::get('companyid');

                $dcr_id = \DB::table('app_doctor_dcr_t')->insertGetId($datatp);
            }else{

                $doctor_dcr_id=$_POST['bulk_doctor_dcr_id'];
                $remove_id = explode(",", $_POST['dcr_remove_id']);

                if(count($remove_id) >0){
                    foreach($remove_id as $val)
                    {
                        \DB::table('app_doctor_dcr_t')->where('doctor_dcr_id',$val)->delete();
                        \DB::table('app_pob_detail_t')->where('doctor_dcr_id',$val)->delete();
                        \DB::table('app_gift_sampledetail_t')->where('doctor_dcr_id',$val)->delete();
                        \DB::table('app_product_sampledetail_t')->where('doctor_dcr_id',$val)->delete();
                        \DB::table('app_poster_detail_t')->where('doctor_dcr_id',$val)->delete();
                    }
                }

                foreach ($doctor_dcr_id as $key => $value) {
                    if($value != ''){
                        $data['tp_date'] = date('Y-m-d',strtotime($_POST['tp_date']));
                        $data['divert_detail'] = $_POST['bulk_divert_detail'][$key];
                        $data['divert_reason'] = $_POST['bulk_divert_reason'][$key];
                        $data['from_area'] = $_POST['bulk_from_area'][$key];
                        $data['doctor_id'] = $_POST['bulk_doctor_id'][$key];
                        $data['focus_product'] = json_encode($_POST['bulk_focus_product'.$key]);
                        $data['timing'] = $_POST['bulk_timing'][$key];
                        $data['activity_id'] = $_POST['bulk_activity_id'][$key];
                        $data['visit_with'] = json_encode($_POST['bulk_visit_with'.$key]);
                        $data['outcome_id'] = $_POST['bulk_outcome_id'][$key];
                        $data['dcr_reminder_reason'] = trim($_POST['bulk_dcr_reminder_reason'][$key]);
                        $data['dcr_reminder_date'] = date('Y-m-d',strtotime($_POST['bulk_dcr_reminder_date'][$key]));
                        $data['remarks'] = $_POST['bulk_remarks'][$key];
                        $data['employee_id'] = \Session::get('emp_id');
                        $data['created_by'] = \Session::get('id');
                        $data['last_updated_by'] = \Session::get('id');
                        $data['created_at'] = date('Y-m-d H:s:i');
                        $data['updated_at'] = date('Y-m-d H:s:i');
                        $data['organization_id'] = \Session::get('organization');
                        $data['location_id'] = \Session::get('location');
                        $data['company_id'] = \Session::get('companyid');

                
                        if($value != ""){
                            $doctor_dcr_id=$_POST['bulk_doctor_dcr_id'][$key];
                            \DB::table('app_doctor_dcr_t')->where('doctor_dcr_id',$doctor_dcr_id)->update($data);
                        }else{
                           $dcr_id = \DB::table('app_doctor_dcr_t')->insertGetId($data);
                        }

                        /******************POB save start ***************************/
                        $pob_detail_id=$_POST['bulk_pob_id'][$key];

                        $pob_id = explode(",",$_POST['bulk_pob_id'][$key]);
                        $pob_prd = explode(",",$_POST['bulk_pob_product'][$key]);
                        $pob_qty = explode(",",$_POST['bulk_pob_qty'][$key]);
                        
                        if($pob_detail_id != '')
                            $check= \DB::select('select * from app_pob_detail_t where pob_detail_id in('.$pob_detail_id.')');
                        else
                            $check=array();
                        
                        if($value != '')
                            $oldid= \DB::select('select * from app_pob_detail_t where doctor_dcr_id in('.$value.')');
                        else
                            $oldid=array();
                        
                        if(count($oldid)>0){
                            $existingId =$oldIds = $newIds =array();                        
                            foreach ($oldid as $k1=> $v1){
                                $oldIds[]= $v1->pob_detail_id;
                            }                        
                            foreach ($pob_id as $val1){
                                $newIds[]= $val1;
                            }
                            $arraydiff=  array_diff($oldIds, $newIds);                        
                            foreach($arraydiff as $val1)
                            {
                                \DB::table('app_pob_detail_t')->where('pob_detail_id',$val1)->delete();
                            }

                            foreach ( $pob_id as $kk1 => $vv1) 
                            {
                                $pobdata1['doctor_dcr_id'] = $value;
                                $pobdata1['product_order'] = $pob_prd[$kk1];
                                $pobdata1['pob_qty'] = $pob_qty[$kk1];
                                $pobdata1['createdBy'] = \Session::get('id');
                                $pobdata1['updatedBy'] = \Session::get('id');
                                $pobdata1['createdOn'] = date('Y-m-d H:s:i');
                                $pobdata1['updatedOn'] = date('Y-m-d H:s:i');
                                $pobdata1['organization_id'] = \Session::get('organization');
                                $pobdata1['location_id'] = \Session::get('location');
                                $pobdata1['company_id'] = \Session::get('companyid');

                                if($vv1 != "" && $vv1 != 0){
                                    \DB::table('app_pob_detail_t')->where('pob_detail_id',$vv1)->update($pobdata1);
                                }else{
                                    \DB::table('app_pob_detail_t')->insert($pobdata1);
                                }
                            }           
                        }else{
                            foreach ( $pob_id as $key1 => $val1) {
                                $pobdata1['doctor_dcr_id'] = $value;
                                $pobdata1['product_order'] = $pob_prd[$key1];
                                $pobdata1['pob_qty'] = $pob_qty[$key1];
                                $pobdata1['createdBy'] = \Session::get('id');
                                $pobdata1['updatedBy'] = \Session::get('id');
                                $pobdata1['createdOn'] = date('Y-m-d H:s:i');
                                $pobdata1['updatedOn'] = date('Y-m-d H:s:i');
                                $pobdata1['organization_id'] = \Session::get('organization');
                                $pobdata1['location_id'] = \Session::get('location');
                                $pobdata1['company_id'] = \Session::get('companyid');

                                \DB::table('app_pob_detail_t')->insert($pobdata1);
                            }
                        }                
                        /***********************POB save end *********************/

                        /******************gift save start ***************************/
                        $gift_detail_id=$_POST['bulk_gift_id'][$key];
                        $gift_id = explode(",",$_POST['bulk_gift_id'][$key]);
                        $gift_prd = explode(",",$_POST['bulk_gift_product'][$key]);
                        $gift_qty = explode(",",$_POST['bulk_gift_qty'][$key]);
                        if($pob_detail_id != '')
                            $check= \DB::select('select * from app_gift_sampledetail_t where gift_sample_id in('.$gift_detail_id.')');
                        else
                            $check=array();
                        if($value != '')
                            $oldid= \DB::select('select * from app_gift_sampledetail_t where doctor_dcr_id in('.$value.')');
                        else
                            $oldid=array();

                        if(count($oldid)>0){
                            $existingId =$oldIds = $newIds =array();                        
                            foreach ($oldid as $k2=> $v2){
                                $oldIds[]= $v2->gift_sample_id;
                            }                        
                            foreach ($gift_id as $val2){
                                $newIds[]= $val2;
                            }
                            $arraydiff=  array_diff($oldIds, $newIds);                  
                            foreach($arraydiff as $val2)
                            {
                                \DB::table('app_gift_sampledetail_t')->where('gift_sample_id',$val2)->delete();
                            }

                            foreach ( $gift_id as $kk2 => $vv2) 
                            {
                                $giftdata1['doctor_dcr_id'] = $value;
                                $giftdata1['gift_sample'] = $gift_prd[$kk2];
                                $giftdata1['gift_qty'] = $gift_qty[$kk2];
                                $giftdata1['createdBy'] = \Session::get('id');
                                $giftdata1['updatedBy'] = \Session::get('id');
                                $giftdata1['createdOn'] = date('Y-m-d H:s:i');
                                $giftdata1['updatedOn'] = date('Y-m-d H:s:i');
                                $giftdata1['organization_id'] = \Session::get('organization');
                                $giftdata1['location_id'] = \Session::get('location');
                                $giftdata1['company_id'] = \Session::get('companyid');

                                if($vv2 != "" && $vv2 != 0){
                                    \DB::table('app_gift_sampledetail_t')->where('gift_sample_id',$vv2)->update($giftdata1);
                                }else{
                                    \DB::table('app_gift_sampledetail_t')->insert($giftdata1);
                                }
                            }           
                        }else{
                            foreach ( $gift_id as $key2 => $val2) {
                                $giftdata1['doctor_dcr_id'] = $value;
                                $giftdata1['gift_sample'] = $gift_prd[$key2];
                                $giftdata1['gift_qty'] = $gift_qty[$key2];
                                $giftdata1['createdBy'] = \Session::get('id');
                                $giftdata1['updatedBy'] = \Session::get('id');
                                $giftdata1['createdOn'] = date('Y-m-d H:s:i');
                                $giftdata1['updatedOn'] = date('Y-m-d H:s:i');
                                $giftdata1['organization_id'] = \Session::get('organization');
                                $giftdata1['location_id'] = \Session::get('location');
                                $giftdata1['company_id'] = \Session::get('companyid');

                                \DB::table('app_gift_sampledetail_t')->insert($giftdata1);
                            }
                        }                
                        /***********************gift save end *********************/
                         
                        /******************sample save start ***************************/
                        $sam_detail_id=$_POST['bulk_sample_id'][$key];
                        $sam_id = explode(",",$_POST['bulk_sample_id'][$key]);
                        $sam_prd = explode(",",$_POST['bulk_sample_product'][$key]);
                        $sam_qty = explode(",",$_POST['bulk_sample_qty'][$key]);
                        if($sam_detail_id != '')
                            $check= \DB::select('select * from app_product_sampledetail_t where product_sample_id in('.$sam_detail_id.')');
                        else
                            $check=array();
                        if($value != '')
                            $oldid= \DB::select('select * from app_product_sampledetail_t where doctor_dcr_id in('.$value.')');
                        else
                            $oldid=array();

                        if(count($oldid)>0){
                            $existingId =$oldIds = $newIds =array();                        
                            foreach ($oldid as $k3=> $v3){
                                $oldIds[]= $v3->product_sample_id;
                            }                        
                            foreach ($sam_id as $val3){
                                $newIds[]= $val3;
                            }
                            $arraydiff=  array_diff($oldIds, $newIds);                  
                            foreach($arraydiff as $val3)
                            {
                                \DB::table('app_product_sampledetail_t')->where('product_sample_id',$val3)->delete();
                            }

                            foreach ( $sam_id as $kk3 => $vv3) 
                            {
                                $samdata1['doctor_dcr_id'] = $value;
                                $samdata1['product_sample'] = $sam_prd[$kk3];
                                $samdata1['prd_qty'] = $sam_qty[$kk3];
                                $samdata1['createdBy'] = \Session::get('id');
                                $samdata1['updatedBy'] = \Session::get('id');
                                $samdata1['createdOn'] = date('Y-m-d H:s:i');
                                $samdata1['updatedOn'] = date('Y-m-d H:s:i');
                                $samdata1['organization_id'] = \Session::get('organization');
                                $samdata1['location_id'] = \Session::get('location');
                                $samdata1['company_id'] = \Session::get('companyid');

                                if($vv3 != "" && $vv3 != 0){
                                    \DB::table('app_product_sampledetail_t')->where('product_sample_id',$vv3)->update($samdata1);
                                }else{
                                    \DB::table('app_product_sampledetail_t')->insert($samdata1);
                                }
                            }           
                        }else{
                            foreach ( $sam_id as $key3 => $val3) {
                                $samdata1['doctor_dcr_id'] = $value;
                                $samdata1['product_sample'] = $sam_prd[$key3];
                                $samdata1['prd_qty'] = $sam_qty[$key3];
                                $samdata1['createdBy'] = \Session::get('id');
                                $samdata1['updatedBy'] = \Session::get('id');
                                $samdata1['createdOn'] = date('Y-m-d H:s:i');
                                $samdata1['updatedOn'] = date('Y-m-d H:s:i');
                                $samdata1['organization_id'] = \Session::get('organization');
                                $samdata1['location_id'] = \Session::get('location');
                                $samdata1['company_id'] = \Session::get('companyid');

                                \DB::table('app_product_sampledetail_t')->insert($samdata1);
                            }
                        }                
                        /***********************sample save end *********************/

                        /******************poster save start ***************************/
                        $pos_detail_id=$_POST['bulk_poster_id'][$key];
                        $pos_id = explode(",",$_POST['bulk_poster_id'][$key]);
                        $pos_prd = explode(",",$_POST['bulk_poster_product'][$key]);
                        $pos_qty = explode(",",$_POST['bulk_poster_qty'][$key]);
                        if($pos_detail_id != '')
                            $check= \DB::select('select * from app_poster_detail_t where poster_detail_id in('.$pos_detail_id.')');
                        else
                            $check=array();
                        if($value != '')
                            $oldid= \DB::select('select * from app_poster_detail_t where doctor_dcr_id in('.$value.')');
                        else
                            $oldid=array();

                        if(count($oldid)>0){
                            $existingId =$oldIds = $newIds =array();                        
                            foreach ($oldid as $k4=> $v4){
                                $oldIds[]= $v4->poster_detail_id;
                            }                        
                            foreach ($pos_id as $val4){
                                $newIds[]= $val4;
                            }
                            $arraydiff=  array_diff($oldIds, $newIds);                  
                            foreach($arraydiff as $val4)
                            {
                                \DB::table('app_poster_detail_t')->where('poster_detail_id',$val4)->delete();
                            }

                            foreach ( $pos_id as $kk4 => $vv4) 
                            {
                                $posdata1['doctor_dcr_id'] = $value;
                                $posdata1['poster_product'] = $pos_prd[$kk4];
                                $posdata1['poster_qty'] = $pos_qty[$kk4];
                                $posdata1['createdBy'] = \Session::get('id');
                                $posdata1['updatedBy'] = \Session::get('id');
                                $posdata1['createdOn'] = date('Y-m-d H:s:i');
                                $posdata1['updatedOn'] = date('Y-m-d H:s:i');
                                $posdata1['organization_id'] = \Session::get('organization');
                                $posdata1['location_id'] = \Session::get('location');
                                $posdata1['company_id'] = \Session::get('companyid');

                                if($vv2 != "" && $vv2 != 0){
                                    \DB::table('app_poster_detail_t')->where('poster_detail_id',$vv4)->update($posdata1);
                                }else{
                                    \DB::table('app_poster_detail_t')->insert($posdata1);
                                }
                            }           
                        }else{
                            foreach ( $pos_id as $key4 => $val4) {
                                $posdata1['doctor_dcr_id'] = $value;
                                $posdata1['poster_product'] = $pos_prd[$key4];
                                $posdata1['poster_qty'] = $pos_qty[$key4];
                                $posdata1['createdBy'] = \Session::get('id');
                                $posdata1['updatedBy'] = \Session::get('id');
                                $posdata1['createdOn'] = date('Y-m-d H:s:i');
                                $posdata1['updatedOn'] = date('Y-m-d H:s:i');
                                $posdata1['organization_id'] = \Session::get('organization');
                                $posdata1['location_id'] = \Session::get('location');
                                $posdata1['company_id'] = \Session::get('companyid');

                                \DB::table('app_poster_detail_t')->insert($posdata1);
                            }
                        }                
                        /***********************poster save end *********************/
                    }else{
                        $data['tp_date'] = date('Y-m-d',strtotime($_POST['tp_date']));
                        $data['divert_detail'] = $_POST['bulk_divert_detail'][$key];
                        $data['divert_reason'] = $_POST['bulk_divert_reason'][$key];
                        $data['from_area'] = $_POST['bulk_from_area'][$key];
                        $data['doctor_id'] = $_POST['bulk_doctor_id'][$key];
                        $data['focus_product'] = json_encode($_POST['bulk_focus_product'.$key]);
                        $data['timing'] = $_POST['bulk_timing'][$key];
                        $data['activity_id'] = $_POST['bulk_activity_id'][$key];
                        $data['visit_with'] = json_encode($_POST['bulk_visit_with'.$key]);
                        $data['outcome_id'] = $_POST['bulk_outcome_id'][$key];
                        $data['dcr_reminder_reason'] = trim($_POST['bulk_dcr_reminder_reason'][$key]);
                        $data['dcr_reminder_date'] = date('Y-m-d',strtotime($_POST['bulk_dcr_reminder_date'][$key]));
                        $data['remarks'] = $_POST['bulk_remarks'][$key];
                        $data['employee_id'] = \Session::get('emp_id');
                        $data['created_by'] = \Session::get('id');
                        $data['last_updated_by'] = \Session::get('id');
                        $data['created_at'] = date('Y-m-d H:s:i');
                        $data['updated_at'] = date('Y-m-d H:s:i');
                        $data['organization_id'] = \Session::get('organization');
                        $data['location_id'] = \Session::get('location');
                        $data['company_id'] = \Session::get('companyid');

                        $dcr_id = \DB::table('app_doctor_dcr_t')->insertGetId($data);
                        $pob_prd = explode(",",$_POST['bulk_pob_product'][$key]);
                        $pob_qty = explode(",",$_POST['bulk_pob_qty'][$key]);
                        $gift_prd = explode(",",$_POST['bulk_gift_product'][$key]);
                        $gift_qty = explode(",",$_POST['bulk_gift_qty'][$key]);
                        $sample_prd = explode(",",$_POST['bulk_sample_product'][$key]);
                        $sample_qty = explode(",",$_POST['bulk_sample_qty'][$key]);
                        $poster_prd = explode(",",$_POST['bulk_poster_product'][$key]);
                        $poster_qty = explode(",",$_POST['bulk_poster_qty'][$key]);

                        foreach ($pob_prd as $k1 => $v1) {

                            $pobdata['doctor_dcr_id'] = $dcr_id;
                            $pobdata['product_order'] = $v1;
                            $pobdata['pob_qty'] = $pob_qty[$k1];
                            $pobdata['createdBy'] = \Session::get('id');
                            $pobdata['updatedBy'] = \Session::get('id');
                            $pobdata['createdOn'] = date('Y-m-d H:s:i');
                            $pobdata['updatedOn'] = date('Y-m-d H:s:i');
                            $pobdata['organization_id'] = \Session::get('organization');
                            $pobdata['location_id'] = \Session::get('location');
                            $pobdata['company_id'] = \Session::get('companyid');
                            
                            $pob_id= \DB::table('app_pob_detail_t')->insertGetId($pobdata);
                        }
                        foreach ($gift_prd as $k2 => $v2) {

                            $giftdata['doctor_dcr_id'] = $dcr_id;
                            $giftdata['gift_sample'] = $v2;
                            $giftdata['gift_qty'] = $gift_qty[$k2];
                            $giftdata['createdBy'] = \Session::get('id');
                            $giftdata['updatedBy'] = \Session::get('id');
                            $giftdata['createdOn'] = date('Y-m-d H:s:i');
                            $giftdata['updatedOn'] = date('Y-m-d H:s:i');
                            $giftdata['organization_id'] = \Session::get('organization');
                            $giftdata['location_id'] = \Session::get('location');
                            $giftdata['company_id'] = \Session::get('companyid');
                            
                            $gift_id= \DB::table('app_gift_sampledetail_t')->insertGetId($giftdata);
                        }
                        foreach ($sample_prd as $k3 => $v3) {

                            $sampledata['doctor_dcr_id'] = $dcr_id;
                            $sampledata['product_sample'] = $v3;
                            $sampledata['prd_qty'] = $sample_qty[$k3];
                            $sampledata['createdBy'] = \Session::get('id');
                            $sampledata['updatedBy'] = \Session::get('id');
                            $sampledata['createdOn'] = date('Y-m-d H:s:i');
                            $sampledata['updatedOn'] = date('Y-m-d H:s:i');
                            $sampledata['organization_id'] = \Session::get('organization');
                            $sampledata['location_id'] = \Session::get('location');
                            $sampledata['company_id'] = \Session::get('companyid');
                            
                            $sample_id= \DB::table('app_product_sampledetail_t')->insertGetId($sampledata);
                        }
                        foreach ($poster_prd as $k4 => $v4) {

                            $posterdata['doctor_dcr_id'] = $dcr_id;
                            $posterdata['poster_product'] = $v4;
                            $posterdata['poster_qty'] = $poster_qty[$k4];
                            $posterdata['createdBy'] = \Session::get('id');
                            $posterdata['updatedBy'] = \Session::get('id');
                            $posterdata['createdOn'] = date('Y-m-d H:s:i');
                            $posterdata['updatedOn'] = date('Y-m-d H:s:i');
                            $posterdata['organization_id'] = \Session::get('organization');
                            $posterdata['location_id'] = \Session::get('location');
                            $posterdata['company_id'] = \Session::get('companyid');
                            
                            $poster_id= \DB::table('app_poster_detail_t')->insertGetId($posterdata);
                        }
                    }
                }
            }
            \DB::commit();
            
           return response()->json(array('status' => 'success', 'message' =>'Saved Successfully','id' => $id));
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

    public function tourapprove($date=null){
        $dt = date('Y-m-d', strtotime($date));
        $emp_id = \Session::get('id');
        
        $sql = \DB::table('app_tourprogram_t')->wheredate('tour_date',$dt)->where('created_by',$emp_id)->get();
        if(count($sql) > 0 ){
            $status = $sql[0]->status;
        }else{
            $status="empty";
        }
        return $status;
    }

    public function areas($date=null,$id=null){
        $emp_id = \Session::get('id');

        $details=[];
        $n_area_id=[];
        $htmldata=[];
        
        $n_date=date('Y-m-d', strtotime($date));
        $sql = \DB::table('app_tourprogram_t')->wheredate('tour_date',$n_date)->where('status','APPROVED')->where('created_by',$emp_id)->get();
        
        $dt = \DB::table('app_doctor_dcr_t')->where('doctor_dcr_id',$id)->get();

        if($dt->isNotEmpty()){
            if($sql->isNotEmpty()){
                foreach ($sql as $key => $value) {
                    $ctiy =json_decode($value->tour_area);
                    $details[]=\DB::table('m_area_t')->whereIn('area_id',$ctiy)->select('area_id')->get();
                }
                if( count($details) > 0 ){
                    foreach ($details as $k => $v) {
                        foreach ($v as $sk => $sv) {
                            $n_area_id[] = $sv->area_id;
                        }
                    }
                    $arr =array_unique($n_area_id);

                    $str = implode(',', $arr);
                    $htmldata['from_area'] = $this->jcustomselecttool('m_area_t','area_id','area_name',$dt[0]->from_area,"and area_id in ($str)");
                                         
                }
            }
        }else{
            if($sql->isNotEmpty()){
                foreach ($sql as $key => $value) {
                    $ctiy =explode(",", $value->tour_area);
                    // dd($ctiy);
                    $details[]=\DB::table('m_area_t')->whereIn('area_id',$ctiy)->select('area_id')->get();
                }
                if( count($details) > 0 ){
                    foreach ($details as $k => $v) {
                        foreach ($v as $sk => $sv) {
                            $n_area_id[] = $sv->area_id;
                        }
                    }
                    $arr =array_unique($n_area_id);

                    $str = implode(',', $arr);
                    $htmldata['from_area'] = $this->jcustomselecttool('m_area_t','area_id','area_name','',"and area_id in ($str)");                      
                }
            }
        }
        return $htmldata;
    }


    public function areawisedoctor($id=null,$date=null)
    {
        $ids = explode(",", $id);
        $dt =date('Y-m-d',strtotime($date));
        $html= '';
        $arr=[];
        $dcr_d_id=[];
        $d_id=[];
        $d_name=[];$doctor_id_name=[];
        $employee_id=\Session::get('emp_id');

        $area = \DB::table('app_doctors_adr_t')
                ->leftjoin('app_doctors_t','app_doctors_t.doctor_id','=','app_doctors_adr_t.doctor_id')
                ->whereIn('app_doctors_adr_t.area',$ids)
                ->select('app_doctors_adr_t.doctor_id','app_doctors_t.doctor_name','app_doctors_adr_t.area','app_doctors_adr_t.doctors_adr_id')
                ->get();
        $html.= "<option value='' >--Please select--</option>";
        $sql = \DB::select("SELECT doctor_id FROM `app_tourprogram_t` WHERE `tour_area` regexp '$id' and `tour_date` = '$dt' and `status` = 'APPROVED'");

        if(count($sql) > 0){
            foreach ($sql as $key => $value) {
                $data= json_decode($value->doctor_id);
                if($data){
                    foreach ($data as $k => $v) {
                        $arr[] = $k;
                    }
                }
            }
                
            $doctor = \DB::table('app_doctors_adr_t')
                ->leftjoin('app_doctors_t','app_doctors_t.doctor_id','=','app_doctors_adr_t.doctor_id')
                ->whereIn('app_doctors_adr_t.doctors_adr_id',$arr)
                ->whereIn('app_doctors_adr_t.area',$ids)
                ->select('app_doctors_adr_t.doctor_id','app_doctors_t.doctor_name','app_doctors_adr_t.area','app_doctors_adr_t.doctors_adr_id')
                ->get();
        
            if(count($doctor)>0){                           
                foreach ($doctor as $key => $val) {
                    $d_id[] = $val->doctor_id;
                    $doctor_id_name[$val->doctor_id] = $val->doctor_name;
                }  
            }
        
            $dcr_doc_id = \DB::table('app_doctor_dcr_t')->select('doctor_id')->where('tp_date',$dt)->whereIn('doctor_id',$d_id)->get();
            foreach ($dcr_doc_id as $k1 => $v1) {
                $dcr_d_id[] = $v1->doctor_id;
            }
            foreach ($d_id as $ks) {
                if (!in_array($ks, $dcr_d_id)){
                    $new_id[] = $ks;
                    $html.= "<option value='".$ks."'>".$doctor_id_name[$ks]."</option>";
                }
            }
        }else{
            foreach ($area as $y => $e) {
                $html.= "<option value='".$e->doctor_id."'>".$e->doctor_name."</option>";
            }
        }

        return $html;
    }

    public function focusproductdata(Request $request,$id = null)
    {

        $sql=\DB::table('app_doctors_t')->where('doctor_id',$id)->get();  
        $html=[];
        if($sql[0]->focus_product){
            $pd = json_decode($sql[0]->focus_product);

            foreach ($pd as $key => $value) {
                $html['select'][]=$this->jcustomfocusproduct('m_products_t','product_id','concatenated_product','',"and product_id='$value'");

                $html['id'][]=$value;
            }
            
        }else{
            $html = '';
        }

        return $html;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Doctordcr  $doctordcr
     * @return \Illuminate\Http\Response
     */
    public function show(Doctordcr $doctordcr,$id=null)
    {   
        $sql = \DB::table('app_doctor_dcr_t')->where('doctor_dcr_id',$id)->get();
// dd($sql);

        if($sql->isNotEmpty()){
             
            $this->data['doctordcr']=$sql[0];

            if($sql[0]->doctor_id)
                $this->data['doctor_name']=$this->idname('doctor_name','app_doctors_t','doctor_id',$sql[0]->doctor_id);
            else
                $this->data['doctor_name']='';

            if($sql[0]->visit_with){
                $sqpl =(json_decode($sql[0]->visit_with));
            //dd($sqpl);
                $visit_with ='';
                if($sqpl !=""  ){
                    //$sqpl = explode(',',$sqpl);
                    if( count($sqpl) > 0){

                        foreach ($sqpl as $k => $v) {

                           $visit_with.= $this->idname('first_name','hr_employee_t','employee_id',$v).',';
                        }
                    }else{
                        $visit_with= $this->idname('first_name','hr_employee_t','employee_id',$sqpl);
                    }
                }
                $this->data['visit_with']=rtrim($visit_with,',');
            }
            else{
                $this->data['visit_with']='';
            }

            if($sql[0]->from_area)
                $this->data['from_area']=$this->idname('area_name','m_area_t','area_id',$sql[0]->from_area);
            else
                $this->data['from_area']='';

            if($sql[0]->outcome_id)
                $this->data['outcome_id']=$this->idname('outcome_name','m_outcome_t','outcome_id',$sql[0]->outcome_id);
            else
                $this->data['outcome_id']='';

            if($sql[0]->activity_id)
                $this->data['activity']=$this->idname('activity_name','m_activity_t','activity_id',$sql[0]->activity_id);
            else
                $this->data['activity']='';
// dd($sql);
            if($sql[0]->focus_product){

                $sqpl =(json_decode($sql[0]->focus_product));

                $focus_product='';
                if($sqpl !=NULL ){
                     //$sqpl = explode(',',$sqpl);
                    foreach ($sqpl as $key => $value) {
                        $focus_product.=$this->idname('concatenated_product','m_products_t','product_id',$value).',';
                    }
                }
                $this->data['focus_product']=rtrim($focus_product,',');
            }
            else{
                $this->data['focus_product']='';
            }
        }
        $giftlinesdata = \DB::table('app_gift_sampledetail_t')
                ->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'app_gift_sampledetail_t.gift_sample')
                ->where('app_gift_sampledetail_t.doctor_dcr_id',$id)->get();
        
        $samplelinesdata = \DB::table('app_product_sampledetail_t')
                ->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'app_product_sampledetail_t.product_sample')
                ->where('app_product_sampledetail_t.doctor_dcr_id',$id)->get();
        
        $linesdata = \DB::table('app_pob_detail_t')
                ->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'app_pob_detail_t.product_order')
                ->where('app_pob_detail_t.doctor_dcr_id',$id)->get();
        
        $posterlinesdata = \DB::table('app_poster_detail_t')
                ->leftjoin('m_products_t', 'm_products_t.product_id', '=', 'app_poster_detail_t.poster_product')
                ->where('app_poster_detail_t.doctor_dcr_id',$id)->get();
        

        $this->data['giftlinesdata']=$giftlinesdata;
        $this->data['samplelinesdata']=$samplelinesdata;
        $this->data['posterlinesdata']=$posterlinesdata;
        $this->data['linesdata']=$linesdata;

        return view('doctordcr.view',$this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Doctordcr  $doctordcr
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctordcr $doctordcr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Doctordcr  $doctordcr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Doctordcr $doctordcr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Doctordcr  $doctordcr
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctordcr $doctordcr,$id=null)
    {
        $count=0;
        if($count <= 0)
        {
            $query = \DB::table('app_doctor_dcr_t')->where('doctor_dcr_id',$id)->delete();
            $query = \DB::table('app_product_sampledetail_t')->where('doctor_dcr_id',$id)->delete();
            $query = \DB::table('app_gift_sampledetail_t')->where('doctor_dcr_id',$id)->delete();

            if($query)
            {
                return 0;
            }
            else
            {
                return 1;
            }

        }
        else
        {
            return 2;
        }
    }
    public function getdoctordcrData()   
    {
        $wh='';
        $search_table=array("m_area_t","tb_users","app_doctors_t");
        if($_GET['_search']=='true')
        {
            $wh=$this->jqgridsearch('app_doctor_dcr_t',$_GET['filters'],$search_table);
        }
        

        $page = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx = $_GET['sidx'];
        $sord = $_GET['sord'];
        if(!$sidx) $sidx =1;

        $result = \DB::select("SELECT count(app_doctor_dcr_t.doctor_dcr_id) as count FROM `app_doctor_dcr_t` left join m_area_t on(m_area_t.area_id = app_doctor_dcr_t.from_area) left join `tb_users` on (tb_users.id=app_doctor_dcr_t.created_by) left join app_doctors_t on(app_doctors_t.doctor_id = app_doctor_dcr_t.doctor_id) where 1=1 $wh");

        $count = count($result);
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
        
        $emp = \Session::get('id');

        $sql="SELECT tb_users.username,app_doctor_dcr_t.tp_deviation,app_doctor_dcr_t.doctor_dcr_id,app_doctor_dcr_t.tp_date,app_doctor_dcr_t.divert_detail,app_doctor_dcr_t.remarks,m_area_t.area_name,app_doctors_t.doctor_name FROM `app_doctor_dcr_t` left join m_area_t on(m_area_t.area_id = app_doctor_dcr_t.from_area) left join `tb_users` on (tb_users.id=app_doctor_dcr_t.created_by) left join app_doctors_t on(app_doctors_t.doctor_id = app_doctor_dcr_t.doctor_id)  where 1=1 and app_doctor_dcr_t.created_by='".$emp."' $wh ORDER BY $sidx $sord LIMIT $start , $limit";
        
        $result = \DB::select($sql);
        // dd($result);
        $responce->rows[]='';
        $responce->rows=$result;
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;

        echo json_encode($responce);
    }

}
