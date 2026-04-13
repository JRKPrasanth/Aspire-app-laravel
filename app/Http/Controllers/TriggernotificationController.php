<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Console\Scheduling\Schedule;
class TriggernotificationController extends Controller
{
	

	


   Public function data(Schedule $schedule)
    {
		$module=\DB::select("SELECT * FROM `notification_module_t`");
		
		
		$data=\DB::select("select notification_genration_tbl.*,tb_users.*,notification_module_t.* from notification_genration_tbl left join tb_users on tb_users.id=notification_genration_tbl.user_id left join notification_module_t on notification_module_t.module_name=notification_genration_tbl.notification_source  where notification_genration_tbl.status='0' and notification_genration_tbl.type='Email'  ");
	
	foreach( $data as $k=>$v ){
		
	$datas=array();	

$datas=\DB::select("select ".$v->header_tbl.".*,".$v->lines_tbl.".* from ".$v->header_tbl." left join ".$v->lines_tbl." on ".$v->header_tbl.".".$v->header_id." = ".$v->lines_tbl.".".$v->header_id." ");	
		
		  \Mail::send($v->folder_name,$datas, function($message) use ($v)
        {
			
			
		
$to=$v->email;
               
		$message->to($to);
		$message->subject($v->module_name);

			//$message->from('Saipavan9010@gmail.com');
			if(!empty(\Session::get('user_email'))){
                    $message->from(\Session::get('user_email'));
                    }else{
                    $message->from(\Config::get('mail.username'));
                    }
                        
        });
		
		
		
	}
	//dd("pavan");
	
	
	
    }

	
    
}
