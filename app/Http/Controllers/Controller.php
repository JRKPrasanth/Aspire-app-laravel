<?php
namespace App\Http\Controllers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect, Input;
use Illuminate\Support\Facades\Auth;
use App\productgroup;
use DB;
use session;
use DateTime;
use Config;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        date_default_timezone_set("Asia/Calcutta");
        ini_set('precision', 10);
        ini_set('serialize_precision', 10);
        $this->middleware('auth');

    }
    /** purpose jcombo join slect with company ondition  and active **/

    public function jcustommultiselect1forw($table, $option, $display, $selected, $condition)
    {

        $selected = explode(",", $selected);

        $display_name = explode("|", $display);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");

        $select_query = \DB::select("select  CONCAT(" . $dis_name . ") as  name ," . $option . "  from " . $table . " WHERE employee_id IN (" . $condition . ")");

        $html = "<option value=''>-- Please Select --</option>";

        foreach ($select_query as $value) {

            //foreach($selected as $key=>$value1){
            if (in_array($value->$option, $selected)) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->name . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->name . "</option>";//;
            }
            //}
        }//dd($html);
        return $html;
    }
    public function jcombojoinselect($table, $option, $display, $table1, $option1, $option2, $display1, $selected, $condition)
    {
        $compy = \Session::get('companyid');
        $display_name = explode("|", $display);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $table . "." . $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");
        $select_query = \DB::select("select  CONCAT(" . $dis_name . ", '-' ," . $table1 . "." . $display1 . ") as  name ," . $option . "  from " . $table . " left join " . $table1 . " on " . $table1 . "." . $option1 . " = " . $table . " . " . $option2 . " WHERE 1=1 and " . $table . ".company_id=" . $compy . " and " . $table . ".active='Yes' " . $condition);

        $html = "<option value=''>-- Please Select --</option>";
        foreach ($select_query as $value) {

            if ($value->$option == $selected) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->name . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->name . "</option>";
            }
        }
        return $html;
    }
    /** kaviya purpose for save common feilds in table  for hrms  start**/
    public function hrmssaveinsert($table, $column, $id, $insert_update)
    {
        $data['company_id'] = \Session::get('companyid');
        $data['location_id'] = \Session::get('location');
        if ($insert_update == 1)
            $data['created_by'] = \Session::get('id');
        $data['last_updated_by'] = \Session::get('id');

        $update = DB::table($table)->where($column, $id)->update($data);
    }
    /** kaviya purpose for save common feilds for hrms  end**/
    public function indexs()
    {

        //dd(\Request::route()->action['menu']);

        if (isset(\Request::route()->action['menu']) && \Request::route()->action['menu'] != "userprofile") {
            // dd(\Request::route()->action['check']);
            $url_data['check'] = $check = \Request::route()->action['check'];
            $url_data['menu'] = $menu = \Request::route()->action['menu'];
            $url_data['label'] = $label = \Request::route()->action['label'];


            $this->middleware(function ($request, $next, $a = null) {
                if (\Auth::user()) {

                    $check = \Request::route()->action['check'];
                    $menu = \Request::route()->action['menu'];
                    $data = \Session::get('data');
                    $u_name = \Session::get('username');


                    if (\Session::get('groupid') == 0) {


                        if (!empty($check)) {

                            if (!isset($data[$menu][$check])) {

                                return redirect('permissioindenied');

                            }


                        } else if (!empty($menu)) {

                            if (!isset($data[$menu]) && array_search(\Request::route()->action['menu'], \Session::get('menu_data')) == '') {

                                return redirect('permissioindenied');
                            }



                        }
                    } else {
                        return $next($request);
                    }

                } else {


                    return redirect('/login')->with('message', "Your Session Has Been Expired");
                }


                return $next($request);

            });

            return $url_data;
        } else {
            return 1;
        }

    }

    /* vimala purpose: Jq Grid Search Select option with out company condition */
    public function jqgridselectlogin($table, $val, $option)
    {

        $data = \DB::table($table)->get();

        $opt = ":--Please Select--";
        foreach ($data as $key => $value) {
            $opt .= ";" . $value->$val . ":" . trim($value->$option);
        }


        return $opt;
    }
    /*End*/


    public function jcustommultiselect1($table, $option, $display, $selected, $condition)
    {

        $selected = explode(",", $selected);

        $display_name = explode("|", $display);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");
        // echo "select  CONCAT(".$dis_name.") as  name ,".$option."  from ".$table." WHERE 1=1 ".$condition; exit;
        $select_query = \DB::select("select  CONCAT(" . $dis_name . ") as  name ," . $option . "  from " . $table . " WHERE 1=1 " . $condition);
        $html = "<option value=''>-- Please Select --</option>";

        //  print_r($value1);
        foreach ($select_query as $value) {

            //foreach($selected as $key=>$value1){
            if (in_array($value->$option, $selected)) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->name . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->name . "</option>";//;
            }
            //}
        }//dd($html);
        return $html;
    }
    /*end*/
    public function jCombowithoutactive($table, $option, $display, $selects)
    {
        $compy = \Session::get('companyid');

        $display_name = explode("|", $display);
        $dis = "";

        foreach ($display_name as $value) {
            $dis .= $value . "," . "' - ', ";

        }

        $dis_name = rtrim($dis, ",' - ',");

        $select = \DB::select("select DISTINCT CONCAT(" . $dis_name . ") as name ," . $option . " from " . $table . " where company_id=" . $compy);
        $html = "<option data-toggle='tooltip' data-placement='top' title='Tooltip' value=''>-- Please Select --</option>";

        foreach ($select as $val) {

            if ($val->$option == $selects) {
                $html .= "<option data-toggle='tooltip' data-placement='top' title='" . $val->name . "'' value='" . $val->$option . "' selected='selected'>" . $val->name . "</option>";
            } else {
                $html .= "<option title='" . $val->name . "' data-toggle='tooltip' class='toold' data-placement='top' value='" . $val->$option . "'>" . $val->name . "</option>";

            }
        }

        return $html;
    }
    /****** Only for notification *****/
    public function sendPopUpHomeNoty($poID, $module_name, $noti_message, $pageurl)
    {

        if ($module_name == 'TASK INITIATED' || $module_name == 'TASK REJECTED' || $module_name == 'TASK APPROVED') {
            $da = \DB::select("select assigned_to from a_taskmanager_t where taskmanager_id='" . $poID . "'");
            $uid = $da[0]->assigned_to;
        } elseif ($module_name == 'TASK Completed') {
            $da = \DB::select("select department_lead from a_taskmanager_t where taskmanager_id='" . $poID . "'");
            $uid = $da[0]->department_lead;
        } else {
            $uid = 1;
        }

        $popnoti['reference_source'] = $module_name;
        $popnoti['reference_source_id'] = $poID;
        $popnoti['reference_url'] = $pageurl;
        $popnoti['user_id'] = $uid;
        $popnoti['date'] = date('Y-m-d');
        $popnoti['read/unread'] = "unread";
        $popnoti['description'] = $noti_message;
        $popnoti['company_id'] = \Session::get('companyid');
        $popnoti['location_id'] = \Session::get('location');
        $popnoti['organization_id'] = \Session::get('organization');
        $popnoti['created_by'] = \Session::get('id');
        $popnoti['created_at'] = date('Y-m-d h:i:s');
        $popnoti['last_updated_by'] = \Session::get('id');
        $popnoti['updated_at'] = date('Y-m-d h:i:s');

        \DB::table('notifications_t')->insert($popnoti);

        return $popnoti;
    }


    /***Return Notification ***/
    public function returnnotification($module_name, $poID, $pageurl, $id, $noti_message)
    {
        $popnoti['reference_source'] = $module_name;
        $popnoti['reference_source_id'] = $poID;
        $popnoti['reference_url'] = $pageurl;
        $popnoti['user_id'] = $id;
        $popnoti['date'] = date('Y-m-d');
        $popnoti['read/unread'] = "unread";
        $popnoti['description'] = $noti_message;
        $popnoti['company_id'] = \Session::get('companyid');
        $popnoti['location_id'] = \Session::get('location');
        $popnoti['organization_id'] = \Session::get('organization');
        $popnoti['created_by'] = \Session::get('id');
        $popnoti['created_at'] = date('Y-m-d h:i:s');
        $popnoti['last_updated_by'] = \Session::get('id');
        $popnoti['updated_at'] = date('Y-m-d h:i:s');

        \DB::table('notifications_t')->insert($popnoti);
    }

    /****** for notification and email *****/
    public function sendPopUpNotification($poID, $module_name, $noti_message, $pageurl)
    {

        $notification_setting = DB::table('notification_module_t')
            ->select('*')
            ->where('module_name', $module_name)->get();
        //dd($notification_setting);
        $header_table = $notification_setting[0]->header_tbl;
        $line_table = $notification_setting[0]->lines_tbl;
        $ids = $notification_setting[0]->header_id;
        // dd($ids);
        $notification['body'] = "$noti_message";
        $notification['title'] = "";
        $notification['icon'] = "";
        $notification['sound'] = "default";
        $notification_messag = \DB::select("select " . $header_table . ".*," . $line_table . ".* from " . $header_table . " left join " . $line_table . " on " . $header_table . "." . $ids . "=" . $line_table . "." . $ids . " where " . $header_table . "." . $ids . "='$poID'");
        $notification_time = DB::table('nofication_user_tbl')
            ->select('*')
            ->where('module_type', $module_name)->get();
        //dd($notification_time);
        if (count($notification_time) > 0) {

            $email_user_id = implode(',', json_decode($notification_time[0]->email_user_id));
            $user_id = implode(',', json_decode($notification_time[0]->user_id));
            $email_users = \DB::select("select * from tb_users where id IN ('$email_user_id') ");
            $users = \DB::select("select * from tb_users where id IN ($user_id) ");

            if (count($email_users) > 0) {

                foreach ($email_users as $k => $v) {

                    if ($pageurl == "salesinvoiceapproval") {
                        $controller = new Salesinvoicecontroller();
                        $data = $controller->show($poID, "welcome");
                        $page = 'salesinvoice.welcome';
                    } else if ($pageurl == "salesquoteapproval") {
                        $controller = new Soquotecontroller();
                        $data = $controller->view($poID, "welcome");
                        $page = 'soquote.welcome';
                    } else if ($pageurl == "salesorderapproval") {
                        $controller = new Soordercontroller();
                        $data = $controller->show($poID, "welcome");
                        $page = 'soorder.welcome';
                    } else if ($pageurl == "poinvoiceapproval") {
                        $controller = new Purchaseinvoicecontroller();
                        $data = $controller->poinvapprovdatashow($poID, "", "welcome");
                        $page = 'purchaseinvoice.welcome';
                    } else if ($pageurl == "purchasequtoetionapprove") {
                        $controller = new Purchasequotationcontroller();
                        $data = $controller->show($poID, "welcome");
                        $page = 'purchasequotation.welcome';
                    } else {
                        $controller = new Purchaseordercontroller();
                        $data = $controller->show($poID, "welcome");
                        $page = 'purchaseorder.welcome';
                    }


                    if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
                        Config::set('mail.username', \Session::get('user_email'));
                        Config::set('mail.password', \Session::get('user_password'));
                    }


                    if ($v->email != '') {
                        \Mail::send($page, $data, function ($message) use ($v, $module_name, $noti_message) {
                            $to = $v->email;
                            $message->to($to);
                            $message->subject($noti_message);
                            if (!empty(\Session::get('user_email'))) {
                                $message->from(\Session::get('user_email'));
                            } else {
                                $message->from(\Config::get('mail.username'));
                            }
                            //$message->from(Config::get('mail.username'));
                            //$message->from('Saipavan9010@gmail.com');
                        });
                    }
                    $notifi['notification_source'] = $module_name;
                    $notifi['reference_id'] = $poID;
                    $notifi['user_id'] = $v->id;
                    $notifi['interval_time'] = $notification_time[0]->email_duration;
                    $notifi['type'] = "Email";
                    \DB::table('notification_genration_tbl')->insert($notifi);
                }
            }
            if (count($users) > 0) {
                foreach ($users as $k => $v) {
                    $notification_message['order'] = json_encode($notification_messag[0]);

                    $message = [];
                    if ($v->fcm_token != "") {
                        $message[] = $this->fcmMsgNotification($v->fcm_token, $notification_message, $notification);
                    }
                    $notifi['notification_source'] = $module_name;
                    $notifi['reference_id'] = $poID;
                    $notifi['user_id'] = $v->id;
                    $notifi['interval_time'] = $notification_time[0]->duration;
                    $notifi['type'] = "Notification";
                    \DB::table('notification_genration_tbl')->insert($notifi);

                    $popnoti['reference_source'] = $module_name;
                    $popnoti['reference_source_id'] = $poID;
                    $popnoti['reference_url'] = $pageurl;
                    $popnoti['user_id'] = $v->id;
                    $popnoti['date'] = date('Y-m-d');
                    $popnoti['read/unread'] = "unread";
                    $popnoti['description'] = $noti_message;
                    $popnoti['company_id'] = \Session::get('companyid');
                    $popnoti['location_id'] = \Session::get('location');
                    $popnoti['organization_id'] = \Session::get('organization');
                    $popnoti['created_by'] = \Session::get('id');
                    $popnoti['created_at'] = date('Y-m-d h:i:s');
                    $popnoti['last_updated_by'] = \Session::get('id');
                    $popnoti['updated_at'] = date('Y-m-d h:i:s');

                    \DB::table('notifications_t')->insert($popnoti);
                }
            }
        } else {
            $message = [];
        }


        return $message;
    }

    public function fcmMsgNotification($token, $res, $notification)
    {

    if (!defined('FIREBASE_API_KEY')) {
    define('FIREBASE_API_KEY', 'AAAA6yke-Eo:APA91bFPI_sIpv5rT8blSyA8rw2-So_F6nKvM-I9Ol7tX0KTqJuDi54C_AIIZyMGgUcM_ClTcHu2QgZwlyd8UK5ueA42d0bUndrQK91p7CqxDKH9g9eXP65cEpms8zfrmIAr2cGKmFCh');
}
        $fields = array(
            'to' => $token,
            'notification' => $notification,
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
        //dd($ch);
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

        }
        // Close connection
        curl_close($ch);
        //dd($result);

        return $result;
    }



    /*karthigaa:purpose for jcustomselect*/

    public function jcustomproductdataselect($table, $option, $display, $selected, $condition, $where)
    {

        $con = \DB::select("SELECT product_group_id,select_option FROM `m_product_setting_t` WHERE module_name='$condition'");

        if ($con) {
            $con_data = explode(',', $con[0]->product_group_id);
            $condition = ' and  ';
            foreach ($con_data as $index => $val) {
                if ($index != 0)
                    $condition .= " or ";
                $condition .= "product_group_id='$val'";
            }
        } else {
            $condition = '';
        }


        $select_query = \DB::select("select  CONCAT(" . $con[0]->select_option . ") as  name ," . $option . "  from " . $table . " WHERE 1=1 " . $condition . "" . $where);

        $html = "";

        foreach ($select_query as $value) {

            if ($value->$option == $selected) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->name . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->name . "</option>";
            }
        }
        return $html;

    }

    public function jcustomproductselect($table, $option, $display, $selected, $condition)
    {

        $emp_id = \Session::get('emp_id');

        $con = \DB::select("SELECT menus,menus1,menus2 FROM `a_product_menu_access_t` where user_id='$emp_id' and process_id='$condition'");

        $workord = $condition;
        if ($con) {
            $group = json_decode($con[0]->menus);

            $cat = json_decode($con[0]->menus1);
            $sub_cat = json_decode($con[0]->menus2);
            $condition = " and m_products_t.product_status='APPROVED' ";

        } else {
            $condition = " and m_products_t.product_status='APPROVED' and m_products_t.product_group_id='' ";
        }

        if ($con) {
            if ($workord == 'workorder') {

                $select_query = $users = DB::table('m_products_t')
                    ->leftjoin('m_material_bom_hdr_t', 'm_material_bom_hdr_t.assembly_product_id', '=', 'm_products_t.product_id')
                    ->whereIn('m_products_t.product_group_id', $group)
                    ->whereIn('m_products_t.product_category_id', $cat)->whereIn('m_products_t.product_subcategory_id', $sub_cat)->where('m_products_t.product_status', 'APPROVED')->where('m_products_t.active', 'Yes')->where('m_material_bom_hdr_t.savestatus', 'APPROVED')->get();
            } else {
                $select_query = $users = DB::table('m_products_t')
                    ->whereIn('product_group_id', $group)
                    ->whereIn('product_category_id', $cat)->whereIn('product_subcategory_id', $sub_cat)->where('product_status', 'APPROVED')->where('active', 'Yes')->get();
            }
        } else {
            $select_query = \DB::select("select  concatenated_product as  name ," . $option . "  from " . $table . " WHERE 1=1 " . $condition);
        }
        $html = "<option value=''>-- Please Select --</option>";
        foreach ($select_query as $value) {

            if ($value->$option == $selected) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->concatenated_product . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->concatenated_product . "</option>";
            }
        }

        return $html;

    }


    public function jcustomproductselect1($table, $option, $display, $selected, $condition)
    {

        // $con=\DB::select("SELECT product_group_id FROM `m_product_setting_t` WHERE module_name='$condition'");

        /*
        if($con){
            $con_data=  explode(',', $con[0]->product_group_id);
            $condition=' and  ';
            foreach($con_data as $index=>$val){
                if($index!=0)
                    $condition.=" or ";
                $condition.="product_group_id='$val'";
            }
        } else {
            $condition='';
        }
    */
        $display_name = explode("|", $display);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }

        $dis_name = rtrim($display_n, ",' - ',");
        $select_query = \DB::select("select  CONCAT(" . $dis_name . ") as  name ," . $option . "  from " . $table . " WHERE 1=1 " . $condition);
        $html = "<option value=''>-- Please Select --</option>";

        foreach ($select_query as $value) {

            if ($value->$option == $selected) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->name . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->name . "</option>";
            }
        }

        return $html;

    }
    //kaviya purpose for jcombo no company and no active
    public function jCombologin($table, $option, $display, $selects)
    {

        $display_name = explode("|", $display);
        $dis = "";

        foreach ($display_name as $value) {
            $dis .= $value . "," . "' - ', ";
        }

        $dis_name = rtrim($dis, ",' - ',");
        if ($table == "m_countries_t")
            $select = \DB::select("select DISTINCT CONCAT(" . $dis_name . ") as name ," . $option . " from " . $table . " where country_id != '6'");
        else if ($table == "m_states_t")
            $select = \DB::select("select DISTINCT CONCAT(" . $dis_name . ") as name ," . $option . " from " . $table . " where state_id != '41'");
        else if ($table == "m_cities_t")
            $select = \DB::select("select DISTINCT CONCAT(" . $dis_name . ") as name ," . $option . " from " . $table . " where city_id!='1'");
        else
            $select = \DB::select("select DISTINCT CONCAT(" . $dis_name . ") as name ," . $option . " from " . $table);

        $html = "<option value=''>-- Please Select --</option>";

        foreach ($select as $val) {
            if ($val->$option == $selects) {
                $html .= "<option value='" . $val->$option . "' selected='selected'>" . $val->name . "</option>";
            } else {
                $html .= "<option value='" . $val->$option . "'>" . $val->name . "</option>";
            }
        }

        return $html;
    }

    //kaviya purpose for jcombo company and active
    public function jCombo($table, $option, $display, $selects)
    {
        $compy = \Session::get('companyid');

        $display_name = explode("|", $display);
        $dis = "";

        foreach ($display_name as $value) {
            $dis .= $value . "," . "' - ', ";

        }

        $dis_name = rtrim($dis, ",' - ',");
        $wh = '';
        if ($table == 'm_supplier_t') {
            $wh .= " and savestatus='APPROVED' ";
        }
        if ($table == 's_schemes_hdr_t') {
            $wh .= " and active='Yes' and savestatus='APPROVED'  and approvestatus='APPROVED'";
        }

        $select = \DB::select("select DISTINCT CONCAT(" . $dis_name . ") as name ," . $option . " from " . $table . " where company_id=" . $compy . " $wh and active='Yes'");
        $html = "<option data-toggle='tooltip' data-placement='top' title='Tooltip' value=''>-- Please Select --</option>";

        foreach ($select as $val) {

            if ($val->$option == $selects) {
                $html .= "<option data-toggle='tooltip' data-placement='top' title='" . $val->name . "'' value='" . $val->$option . "' selected='selected'>" . $val->name . "</option>";
            } else {
                $html .= "<option title='" . $val->name . "' data-toggle='tooltip' class='toold' data-placement='top' value='" . $val->$option . "'>" . $val->name . "</option>";

            }
        }

        return $html;
    }


    public function jCombosalreturn($table, $option, $display, $selects)
    {
        $compy = \Session::get('companyid');

        $display_name = explode("|", $display);
        $dis = "";

        foreach ($display_name as $value) {
            $dis .= $value . "," . "' - ', ";

        }

        $dis_name = rtrim($dis, ",' - ',");
        $wh = '';
        if ($table == 'm_supplier_t' || $table == 's_schemes_hdr_t') {
            $wh .= " and savestatus='APPROVED' ";
        }


        $select = \DB::select("select DISTINCT CONCAT(" . $dis_name . ") as name ," . $option . " from " . $table . " where company_id=" . $compy . " $wh ");
        $html = "<option data-toggle='tooltip' data-placement='top' title='Tooltip' value=''>-- Please Select --</option>";

        foreach ($select as $val) {

            if ($val->$option == $selects) {
                $html .= "<option data-toggle='tooltip' data-placement='top' title='" . $val->name . "'' value='" . $val->$option . "' selected='selected'>" . $val->name . "</option>";
            } else {
                $html .= "<option title='" . $val->name . "' data-toggle='tooltip' class='toold' data-placement='top' value='" . $val->$option . "'>" . $val->name . "</option>";

            }
        }

        return $html;
    }



    //kaviya purpose for jcombo company and active
    public function jComboprodsfg($table, $option, $display, $selects)
    {
        $compy = \Session::get('companyid');

        $display_name = explode("|", $display);
        $dis = "";

        foreach ($display_name as $value) {
            $dis .= $value . "," . "' - ', ";

        }

        $dis_name = rtrim($dis, ",' - ',");
        $wh = '';
        if ($table == 'm_supplier_t') {
            $wh .= " and savestatus='APPROVED' ";
        }


        $select = \DB::select("select DISTINCT CONCAT(" . $dis_name . ") as name ," . $option . " from " . $table . " where company_id=" . $compy . " $wh and active='Yes' and (product_group_id = 1 or product_group_id = 4)");
        $html = "<option data-toggle='tooltip' data-placement='top' title='Tooltip' value=''>-- Please Select --</option>";

        foreach ($select as $val) {

            if ($val->$option == $selects) {
                $html .= "<option data-toggle='tooltip' data-placement='top' title='" . $val->name . "'' value='" . $val->$option . "' selected='selected'>" . $val->name . "</option>";
            } else {
                $html .= "<option title='" . $val->name . "' data-toggle='tooltip' class='toold' data-placement='top' value='" . $val->$option . "'>" . $val->name . "</option>";

            }
        }

        return $html;
    }

    public function jCombo_purchase($table, $option, $display, $selects)
    {
        $compy = \Session::get('companyid');

        $display_name = explode("|", $display);
        $dis = "";

        foreach ($display_name as $value) {
            $dis .= $value . "," . "' - ', ";

        }

        $dis_name = rtrim($dis, ",' - ',");
        $wh = '';
        if ($table == 'm_supplier_t') {
            $wh .= " and savestatus='APPROVED' ";
        }


        $select = \DB::select("select DISTINCT CONCAT(" . $dis_name . ") as name ," . $option . " from " . $table . " where company_id=" . $compy . " $wh and active='Yes' AND (group_name = 'RAW MATERIALS' OR group_name = 'PACKING MATERIALS' OR group_name='PROMOTIONAL ITEMS' OR group_name ='ACCESSORIES' OR group_name ='CONSUMABLES' OR group_name ='ASSET')");
        $html = "<option data-toggle='tooltip' data-placement='top' title='Tooltip' value=''>-- Please Select --</option>";

        foreach ($select as $val) {

            if ($val->$option == $selects) {
                $html .= "<option data-toggle='tooltip' data-placement='top' title='" . $val->name . "'' value='" . $val->$option . "' selected='selected'>" . $val->name . "</option>";
            } else {
                $html .= "<option title='" . $val->name . "' data-toggle='tooltip' class='toold' data-placement='top' value='" . $val->$option . "'>" . $val->name . "</option>";

            }
        }

        return $html;
    }

    public function jCombo_logistics($table, $option, $display, $selects)
    {
        $compy = \Session::get('companyid');

        $display_name = explode("|", $display);
        $dis = "";

        foreach ($display_name as $value) {
            $dis .= $value . "," . "' - ', ";

        }

        $dis_name = rtrim($dis, ",' - ',");
        $wh = '';
        if ($table == 'm_supplier_t') {
            $wh .= " and savestatus='APPROVED' ";
        }


        $select = \DB::select("select DISTINCT CONCAT(" . $dis_name . ") as name ," . $option . " from " . $table . " where company_id=" . $compy . " $wh and active='Yes' AND (group_name = 'FINISHED GOODS' OR group_name = 'SAMPLE PRODUCTS')");
        $html = "<option data-toggle='tooltip' data-placement='top' title='Tooltip' value=''>-- Please Select --</option>";

        foreach ($select as $val) {

            if ($val->$option == $selects) {
                $html .= "<option data-toggle='tooltip' data-placement='top' title='" . $val->name . "'' value='" . $val->$option . "' selected='selected'>" . $val->name . "</option>";
            } else {
                $html .= "<option title='" . $val->name . "' data-toggle='tooltip' class='toold' data-placement='top' value='" . $val->$option . "'>" . $val->name . "</option>";

            }
        }

        return $html;
    }

    public function jCombo_production($table, $option, $display, $selects)
    {
        $compy = \Session::get('companyid');

        $display_name = explode("|", $display);
        $dis = "";

        foreach ($display_name as $value) {
            $dis .= $value . "," . "' - ', ";

        }

        $dis_name = rtrim($dis, ",' - ',");
        $wh = '';
        if ($table == 'm_supplier_t') {
            $wh .= " and savestatus='APPROVED' ";
        }


        $select = \DB::select("select DISTINCT CONCAT(" . $dis_name . ") as name ," . $option . " from " . $table . " where company_id=" . $compy . " $wh and active='Yes' AND (group_name = 'SEMI FINISHED GOODS')");
        $html = "<option data-toggle='tooltip' data-placement='top' title='Tooltip' value=''>-- Please Select --</option>";

        foreach ($select as $val) {

            if ($val->$option == $selects) {
                $html .= "<option data-toggle='tooltip' data-placement='top' title='" . $val->name . "'' value='" . $val->$option . "' selected='selected'>" . $val->name . "</option>";
            } else {
                $html .= "<option title='" . $val->name . "' data-toggle='tooltip' class='toold' data-placement='top' value='" . $val->$option . "'>" . $val->name . "</option>";

            }
        }

        return $html;
    }



    //kaviya purpose for jcombo company and no active
    public function jCombocomp($table, $option, $display, $selects)
    {
        $wh1 = '';
        if ($table == 's_schemes_hdr_t') {
            $wh1 .= " and s_schemes_hdr_t.savestatus='APPROVED' and s_schemes_hdr_t.approvestatus='APPROVED' and active='Yes'";
        }

        $compy = \Session::get('companyid');

        $display_name = explode("|", $display);
        $dis = "";

        foreach ($display_name as $value) {
            $dis .= $value . "," . "' - ', ";
        }

        $dis_name = rtrim($dis, ",' - ',");

        $select = \DB::select("select DISTINCT CONCAT(" . $dis_name . ") as name ," . $option . " from " . $table . " where 1=1 $wh1 and company_id=" . $compy);

        $html = "<option value=''>-- Please Select --</option>";

        foreach ($select as $val) {
            if ($val->$option == $selects) {
                $html .= "<option value='" . $val->$option . "' selected='selected'>" . $val->name . "</option>";
            } else {
                $html .= "<option value='" . $val->$option . "'>" . $val->name . "</option>";
            }
        }

        return $html;
    }
    /*end*/


    //Vj purpose for jcombo company and no active
    public function jCombocompdist($table, $option, $display, $selects)
    {
        $compy = \Session::get('companyid');
        $depart = \Session::get('groupname');
        $wh = '';
        if ($depart == "14") {
            $emp_id = \Session::get('emp_id');
            $cus_id = \DB::select("SELECT GROUP_CONCAT(distributormapping_lines_tbl.disti_id)as dis FROM `distributormapping_hdr_tbl` join distributormapping_lines_tbl on distributormapping_lines_tbl.distributormapping_id=distributormapping_hdr_tbl.distributormapping_id where distributormapping_hdr_tbl.employee_id=$emp_id");

            $wh .= " and customer_id in (" . $cus_id[0]->dis . ")";
        }
        $display_name = explode("|", $display);
        $dis = "";

        foreach ($display_name as $value) {
            $dis .= $value . "," . "' - ', ";
        }

        $dis_name = rtrim($dis, ",' - ',");

        $select = \DB::select("select DISTINCT CONCAT(" . $dis_name . ") as name ," . $option . " from " . $table . " where 1=1 $wh and company_id=" . $compy);

        $html = "<option value=''>-- Please Select --</option>";

        foreach ($select as $val) {
            if ($val->$option == $selects) {
                $html .= "<option value='" . $val->$option . "' selected='selected'>" . $val->name . "</option>";
            } else {
                $html .= "<option value='" . $val->$option . "'>" . $val->name . "</option>";
            }
        }

        return $html;
    }
    /*end*/


    ///*pavan:purpose for displayname*/


    function idname($displayname, $table, $condition, $value)
    {
        $display_name = explode("|", $displayname);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");
        $name = \DB::select("select CONCAT(" . $dis_name . ") as disname from $table where $condition = '$value' ");
        if ($name)
            return $name[0]->disname;
        else
            return "";
    }

    //end

    ///*Vj:purpose for displaymultiname*/


    function idmultiname($displayname, $table, $condition, $value)
    {
        $display_name = explode("|", $displayname);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");
        $name = \DB::select("select CONCAT(" . $dis_name . ") as disname from $table where $condition in ($value) ");
        if ($name) {
            $po = "";
            foreach ($name as $pk => $pv) {

                $po .= $pv->disname . ",";
            }
            $po_number = rtrim($po, ",");
            return $po_number;
        } else {
            return "";
        }
    }

    //end

    /*kavya purpose jcustom no company and no active*/
    public function jcustomselecttool($table, $option, $display, $selected, $condition)
    {

        $display_name = explode("|", $display);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");

        $select_query = \DB::select("select  CONCAT(" . $dis_name . ") as  name ," . $option . "  from " . $table . " WHERE 1=1 " . $condition);

        $html = "<option value=''>-- Please Select --</option>";

        foreach ($select_query as $value) {
            if ($value->$option == $selected) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->name . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->name . "</option>";
            }
        }

        return $html;
    }
    /*kavya:purpose for jcustomselect with company and active*/
    // public function jcustomselect($table,$option,$display,$selected,$condition)
// {
//  $compy=\Session::get('companyid');
// $display_name=explode("|",$display);
// $display_n="";
// foreach($display_name as $val)
// {

    // $display_n.=$val.","."' - ',";
// }
// $dis_name=rtrim($display_n,",' - ',");
// $select_query=\DB::select("select  CONCAT(".$dis_name.") as  name ,".$option."  from ".$table." WHERE 1=1 and company_id=".$compy." and active='Yes' ".$condition);
// $html="<option value=''>-- Please Select --</option>";
// foreach ($select_query as $value)
// {

    // if($value->$option == $selected)
// {
// $html.= "<option value='".$value->$option."' selected='selected'>".$value->name."</option>";
// }
// else
// {
// $html.= "<option value='".$value->$option."' >".$value->name."</option>";
// }
// }
// return $html;
// }

    public function jcustomselect($table, $option, $display, $selected, $condition)
    {
        $wh1 = '';

        if ($table == 'm_products_t') {
            $wh1 .= " and m_products_t.product_status='APPROVED'";
        }

        if ($table == 'i_pricelist_hdr_t') {
            $wh1 .= " and i_pricelist_hdr_t.savestatus='APPROVED'";
        }


        $compy = \Session::get('companyid');
        $display_name = explode("|", $display);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");
        $select_query = \DB::select("select  CONCAT(" . $dis_name . ") as  name ," . $option . "  from " . $table . " WHERE 1=1 and company_id=" . $compy . "  $wh1  and active='Yes' " . $condition);

       
        $html = "<option value=''>-- Please Select --</option>";
        foreach ($select_query as $value) {

            if ($value->$option == $selected) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->name . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->name . "</option>";
            }
        }
        return $html;
    }
    /*end*/
    /*kavya:purpose for jcustomselect with no company and active*/
    public function jcustomselectactive($table, $option, $display, $selected, $condition)
    {

        $display_name = explode("|", $display);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");
        $select_query = \DB::select("select  CONCAT(" . $dis_name . ") as  name ," . $option . "  from " . $table . " WHERE 1=1  and active='Yes' " . $condition);
        $html = "<option value=''>-- Please Select --</option>";

        foreach ($select_query as $value) {

            if ($value->$option == $selected) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->name . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->name . "</option>";
            }
        }
        return $html;
    }
    /*end*/
    /*purpose for jcustomselect with company and no active*/
    public function jcustomselectcomp($table, $option, $display, $selected, $condition)
    {
        $compy = \Session::get('companyid');
        $display_name = explode("|", $display);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");

        $select_query = \DB::select("select  CONCAT(" . $dis_name . ") as  name ," . $option . "  from " . $table . " WHERE 1=1 " . $condition . " and company_id=" . $compy);

        $html = "<option value=''>-- Please Select --</option>";
        foreach ($select_query as $value) {

            if ($value->$option == $selected) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->name . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->name . "</option>";
            }
        }
        return $html;
    }
    /*end*/
    /*deepika:purpose for jcustomselect with company and no active and group by*/
    public function jcustomselectcomp1($table, $option, $display, $selected, $condition, $groupby)
    {
        $compy = \Session::get('companyid');
        $display_name = explode("|", $display);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");
        $select_query = \DB::select("select  CONCAT(" . $dis_name . ") as  name ," . $option . "  from " . $table . " WHERE 1=1 " . $condition . " and company_id=" . $compy . " group by $groupby");

        $html = "<option value=''>-- Please Select --</option>";
        foreach ($select_query as $value) {

            if ($value->$option == $selected) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->name . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->name . "</option>";
            }
        }
        return $html;
    }
    /*end*/
    /*deepika:purpose for jcustomselect with company and no active and group by having*/
    public function jcustomselectcomp2($table, $option, $display, $selected, $condition, $groupby, $having)
    {
        $compy = \Session::get('companyid');
        $display_name = explode("|", $display);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");
        $select_query = \DB::select("select  CONCAT(" . $dis_name . ") as  name ," . $option . "  from " . $table . " WHERE 1=1 " . $condition . " and company_id=" . $compy . " group by $groupby having sum(" . $having . ") > 0");

        $html = "<option value=''>-- Please Select --</option>";
        foreach ($select_query as $value) {

            if ($value->$option == $selected) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->name . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->name . "</option>";
            }
        }
        return $html;
    }
    /*end*/
    /*deepika:purpose for jcustomselect with company and no active and group by*/
    public function jcustomselectcomp3($table, $option, $display, $selected, $condition, $groupby)
    {
        $compy = \Session::get('companyid');
        $display_name = explode("|", $display);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");
        $select_query = \DB::select("select  CONCAT(" . $dis_name . ") as  name ," . $option . "  from " . $table . " WHERE 1=1 " . $condition . " and company_id=" . $compy . " group by $groupby ORDER BY " . $option . " DESC");

        $html = "<option value=''>-- Please Select --</option>";
        foreach ($select_query as $value) {

            if ($value->$option == $selected) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->name . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->name . "</option>";
            }
        }
        return $html;
    }
    /*end*/
    /*purpose for focus product*/
    public function jcustomfocusproduct($table, $option, $display, $selected, $condition)
    {

        $display_name = explode("|", $display);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");
        $select_query = \DB::select("select  CONCAT(" . $dis_name . ") as  name ," . $option . "  from " . $table . " WHERE 1=1 " . $condition);
        $html = '';

        foreach ($select_query as $value) {

            if ($value->$option == $selected) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->name . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->name . "</option>";
            }
        }
        return $html;
    }
    /*end*/


    /*deepika purpose:to get multiselect*/
    public function jcustommultiselect($table, $option, $display, $selected, $condition)
    {

        $selected = explode(",", $selected);

        $display_name = explode("|", $display);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");

        $select_query = \DB::select("select  CONCAT(" . $dis_name . ") as  name ," . $option . "  from " . $table . " WHERE 1=1 " . $condition);
        $html = "<option value=''>-- Please Select --</option>";
        //dd($select_query);
//  print_r($value1);
        foreach ($select_query as $value) {

            //foreach($selected as $key=>$value1){
            if (in_array($value->$option, $selected)) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->name . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->name . "</option>";//;
            }
            //}
        }//dd($html);
        return $html;
    }
    /*end*/
    /*deepika purpose:to get select data without please select*/
    public function jcustomdataselect($table, $option, $display, $selected, $condition)
    {
        $display_name = explode("|", $display);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");
        $select_query = \DB::select("select  CONCAT(" . $dis_name . ") as  name ," . $option . "  from " . $table . " WHERE 1=1 " . $condition);
        $html = "";

        foreach ($select_query as $value) {
            if ($value->$option == $selected) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->name . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->name . "</option>";
            }
        }
        //dd($html);
        return $html;
    }
    /*end*/

    /* Jq Grid Search Select option */
    public function jqgridselect($table, $val, $option)
    {
        $data = \DB::table($table)->get();

        $opt = ":--Please Select--";
        foreach ($data as $key => $value) {
            $opt .= ";" . $value->$val . ":" . trim($value->$option);
        }


        return $opt;
    }
    /*End*/

    /*Karthigaa Purpose for No table Jqgrid Search */
    public function jqgridsearchnotab($table1, $data)
    {
        $wh = '';
        $search = json_decode($_GET['filters']);
        foreach ($search->rules as $key => $value) {

            if ($value->data != '') {
                if ($value->op == 'cn') {
                    $wh .= " and $table1." . $value->field . " like '%" . $value->data . "%'";
                } else if ($value->op == 'bw') {
                    $wh .= " and $table1." . $value->field . " like '%" . $value->data . "%'";
                } else if ($value->op == 'eq') {
                    $wh .= " and $table1." . $value->field . " = '" . $value->data . "'";
                } else if ($value->op == 'gt') {
                    $wh .= " and $table1." . $value->field . " = '" . $value->data . "'";
                } else if ($value->op == 'ne') {
                    $wh .= " and $table1." . $value->field . " != '" . $value->data . "'";
                } else if ($value->op == 'bn') {
                    $wh .= " and $table1." . $value->field . " not like '" . $value->data . "%'";
                } else if ($value->op == 'ew') {
                    $wh .= " and $table1." . $value->field . " like '%" . $value->data . "'";
                } else if ($value->op == 'en') {
                    $wh .= " and $table1." . $value->field . " not like '%" . $value->data . "'";
                } else if ($value->op == 'cn') {
                    $wh .= " and $table1." . $value->field . " not like '%" . $value->data . "%'";
                } else if ($value->op == 'nu') {
                    $wh .= " and $table1." . $value->field . " = ''";
                } else if ($value->op == 'nn') {
                    $wh .= " and $table1." . $value->field . " != ''";
                } else if ($value->op == 'in') {
                    $wh .= " and $table1." . $value->field . " in (" . $value->data . ")";
                } else if ($value->op == 'ni') {
                    $wh .= " and $table1." . $value->field . " not in (" . $value->data . ")";
                }
            }
        }
        return $wh;
    }


    /* Jq Grid Search Select option */
    public function jqgridcustselect($table, $val, $option, $condition)
    {
        //$data=\DB::table($table)->whereIn('customer_id', $condition)->get();
        $data = \DB::select("select * from $table where 1=1 $condition");


        $opt = ":--Please Select--";
        foreach ($data as $key => $value) {
            $opt .= ";" . $value->$val . ":" . $value->$option;
        }

        return $opt;
    }
    /*End*/

    /*pqgrid search*/
    public function pqgridsearchsum($table1, $data)
    {



        $wh = '';

        foreach ($data as $key => $value) {

            if ($value->condition == 'begin') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " like '" . $value->value . "%'";
            } else if ($value->condition == 'between') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " between '" . $value->value . "' and '" . $value->value2 . "'";
            } else if ($value->condition == 'contain') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " LIKE '%" . $value->value . "%'";
            } else if ($value->condition == 'empty') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " = '" . $value->value . "'";
            } else if ($value->condition == 'end') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " like '%" . $value->value . "'";
            } else if ($value->condition == 'equal') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " = '" . $value->value . "'";
            } else if ($value->condition == 'great') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " > '" . $value->value . "%'";
            } else if ($value->condition == 'gte') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " >= '" . $value->value . "'";
            } else if ($value->condition == 'less') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " < '" . $value->value . "'";
            } else if ($value->condition == 'lte') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " <= '" . $value->value . "' and '" . $value->value2 . "'";
            } else if ($value->condition == 'notbegin') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " NOT LIKE '%" . $value->value . "%'";
            } else if ($value->condition == 'notcontain') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " NOT LIKE '%" . $value->value . "%'";
            } else if ($value->condition == 'notempty') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " != '" . $value->value . "'";
            } else if ($value->condition == 'notend') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " not like '%" . $value->value . "'";
            } else if ($value->condition == 'notequal') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " != '" . $value->value . "'";
            } else if ($value->condition == 'range') {
                $value1 = implode("','", $value->value);
                //$value1=  str_replace(',',"','",$value1);
                //dd($value1);
                $wh .= " and " . $value->dataIndx . " in ('" . $value1 . "')";
            } else {
                //regexp
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " REGEXP '" . $value->value . "'";
            }



        }
        return $wh;
    }
    /*end*/
    /*pqgrid search*/
    public function pqgridsearch($table, $data, $array = array())
    {



        $wh = '';

        foreach ($data as $key => $value) {
            //dd("SHOW COLUMNS FROM $table where Field='$value->field'");

            $table1 = '';
            foreach ($array as $t) {

                $check = \DB::select("SHOW COLUMNS FROM $t where Field='$value->dataIndx'");

                if ($check) {

                    $table1 = $t;
                    break;
                }
            }
            if ($table1 == '') {
                $table1 = $table;
            }





            if ($value->condition == 'begin') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " like '" . $value->value . "%'";
            } else if ($value->condition == 'between') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " between '" . $value->value . "' and '" . $value->value2 . "'";
            } else if ($value->condition == 'contain') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " LIKE '%" . $value->value . "%'";
            } else if ($value->condition == 'empty') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " = '" . $value->value . "'";
            } else if ($value->condition == 'end') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " like '%" . $value->value . "'";
            } else if ($value->condition == 'equal') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " = '" . $value->value . "'";
            } else if ($value->condition == 'great') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " > '" . $value->value . "%'";
            } else if ($value->condition == 'gte') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " >= '" . $value->value . "'";
            } else if ($value->condition == 'less') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " < '" . $value->value . "%'";
            } else if ($value->condition == 'lte') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " <= '" . $value->value . "' and '" . $value->value2 . "'";
            } else if ($value->condition == 'notbegin') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " NOT LIKE '%" . $value->value . "%'";
            } else if ($value->condition == 'notcontain') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " NOT LIKE '%" . $value->value . "%'";
            } else if ($value->condition == 'notempty') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " != '" . $value->value . "'";
            } else if ($value->condition == 'notend') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " not like '%" . $value->value . "'";
            } else if ($value->condition == 'notequal') {
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " != '" . $value->value . "'";
            } else if ($value->condition == 'range') {
                $value1 = implode("','", $value->value);
                //$value1=  str_replace(',',"','",$value1);
                //dd($value1);
                $wh .= " and " . $value->dataIndx . " in ('" . $value1 . "')";
            } else {
                //regexp
                $wh .= " and " . $table1 . '.' . $value->dataIndx . " REGEXP '" . $value->value . "'";
            }



        }
        return $wh;
    }
    /*end*/

    /*pqgrid sales search*/
    public function pqgridsearch_sales($table, $data, $array = array())
    {
        $wh = '';

        foreach ($data as $key => $value) {
            //dd("SHOW COLUMNS FROM $table where Field='$value->field'");
            $table1 = '';
            foreach ($array as $t) {
                $check = \DB::select("SHOW COLUMNS FROM $t where Field='$value->dataIndx'");
                if ($check) {
                    $table1 = $t;
                    break;
                }
            }
            if ($table1 == '') {
                $table1 = $table;
            }
            if ($value->condition == 'begin') {
                $wh .= " and REPLACE(" . $table1 . '.' . $value->dataIndx . ",'STANDARD', 'SALES') like '" . $value->value . "%'";
            } else if ($value->condition == 'between') {
                $wh .= " and REPLACE(" . $table1 . '.' . $value->dataIndx . ",'STANDARD', 'SALES') between '" . $value->value . "' and '" . $value->value2 . "'";
            } else if ($value->condition == 'contain') {
                $wh .= " and REPLACE(" . $table1 . '.' . $value->dataIndx . ",'STANDARD', 'SALES') LIKE '%" . $value->value . "%'";
            } else if ($value->condition == 'empty') {
                $wh .= " and REPLACE(" . $table1 . '.' . $value->dataIndx . ",'STANDARD', 'SALES') = '" . $value->value . "'";
            } else if ($value->condition == 'end') {
                $wh .= " and REPLACE(" . $table1 . '.' . $value->dataIndx . ",'STANDARD', 'SALES') like '%" . $value->value . "'";
            } else if ($value->condition == 'equal') {
                $wh .= " and REPLACE(" . $table1 . '.' . $value->dataIndx . ",'STANDARD', 'SALES') = '" . $value->value . "'";
            } else if ($value->condition == 'great') {
                $wh .= " and REPLACE(" . $table1 . '.' . $value->dataIndx . ",'STANDARD', 'SALES') > '" . $value->value . "%'";
            } else if ($value->condition == 'gte') {
                $wh .= " and REPLACE(" . $table1 . '.' . $value->dataIndx . ",'STANDARD', 'SALES') >= '" . $value->value . "'";
            } else if ($value->condition == 'less') {
                $wh .= " and REPLACE(" . $table1 . '.' . $value->dataIndx . ",'STANDARD', 'SALES') < '" . $value->value . "%'";
            } else if ($value->condition == 'lte') {
                $wh .= " and REPLACE(" . $table1 . '.' . $value->dataIndx . ",'STANDARD', 'SALES') <= '" . $value->value . "' and '" . $value->value2 . "'";
            } else if ($value->condition == 'notbegin') {
                $wh .= " and REPLACE(" . $table1 . '.' . $value->dataIndx . ",'STANDARD', 'SALES') NOT LIKE '%" . $value->value . "%'";
            } else if ($value->condition == 'notcontain') {
                $wh .= " and REPLACE(" . $table1 . '.' . $value->dataIndx . ",'STANDARD', 'SALES') NOT LIKE '%" . $value->value . "%'";
            } else if ($value->condition == 'notempty') {
                $wh .= " and REPLACE(" . $table1 . '.' . $value->dataIndx . ",'STANDARD', 'SALES') != '" . $value->value . "'";
            } else if ($value->condition == 'notend') {
                $wh .= " and REPLACE(" . $table1 . '.' . $value->dataIndx . ",'STANDARD', 'SALES') not like '%" . $value->value . "'";
            } else if ($value->condition == 'notequal') {
                $wh .= " and REPLACE(" . $table1 . '.' . $value->dataIndx . ",'STANDARD', 'SALES') != '" . $value->value . "'";
            } else if ($value->condition == 'range') {
                $value1 = implode("','", $value->value);
                //$value1=  str_replace(',',"','",$value1);
                //dd($value1);
                $wh .= " and REPLACE(" . $value->dataIndx . ",'STANDARD', 'SALES') in ('" . $value1 . "')";
            } else {
                //regexp
                $wh .= " and REPLACE(" . $table1 . '.' . $value->dataIndx . ",'STANDARD', 'SALES') REGEXP '" . $value->value . "'";
            }
        }
        return $wh;
    }
    /*end*/




    /* Jq Grid Search Query Build */
    public function jqgridsearch($table, $data, $array = array())
    {

        $search = json_decode($_GET['filters']);

        $wh = '';

        foreach ($search->rules as $key => $value) {
            //dd("SHOW COLUMNS FROM $table where Field='$value->field'");
            $check = \DB::select("SHOW COLUMNS FROM $table where Field='$value->field'");
            //dd($check);
            if ($check) {

                $table1 = $table;

            } else {

                foreach ($array as $t) {

                    $check = \DB::select("SHOW COLUMNS FROM $t where Field='$value->field'");

                    if ($check) {

                        $table1 = $t;
                        break;
                    }
                }
            }

            if ($value->data != '') {
                if ($value->op == 'cn') {
                    $wh .= " and $table1." . $value->field . " like '%" . $value->data . "%'";
                } else if ($value->op == 'bw') {
                    $wh .= " and $table1." . $value->field . " like '%" . $value->data . "%'";
                } else if ($value->op == 'eq') {
                    $wh .= " and $table1." . $value->field . " = '" . $value->data . "'";
                } else if ($value->op == 'gt') {
                    $wh .= " and $table1." . $value->field . " = '" . $value->data . "'";
                } else if ($value->op == 'ne') {
                    $wh .= " and $table1." . $value->field . " != '" . $value->data . "'";
                } else if ($value->op == 'bn') {
                    $wh .= " and $table1." . $value->field . " not like '" . $value->data . "%'";
                } else if ($value->op == 'ew') {
                    $wh .= " and $table1." . $value->field . " like '%" . $value->data . "'";
                } else if ($value->op == 'en') {
                    $wh .= " and $table1." . $value->field . " not like '%" . $value->data . "'";
                } else if ($value->op == 'cn') {
                    $wh .= " and $table1." . $value->field . " not like '%" . $value->data . "%'";
                } else if ($value->op == 'nu') {
                    $wh .= " and $table1." . $value->field . " = ''";
                } else if ($value->op == 'nn') {
                    $wh .= " and $table1." . $value->field . " != ''";
                } else if ($value->op == 'in') {
                    $wh .= " and $table1." . $value->field . " in (" . $value->data . ")";
                } else if ($value->op == 'ni') {
                    $wh .= " and $table1." . $value->field . " not in (" . $value->data . ")";
                }
            }
        }
        return $wh;
    }
    /*End*/
    public function jqgridsearch1($table, $data, $array = array())
    {

        $search = json_decode($_GET['filters']);

        $wh = '';
        foreach ($search->rules as $key => $value) {

            //dd("SHOW COLUMNS FROM $table where Field='$value->field'");
            $check = \DB::select("SHOW COLUMNS FROM $table where Field='$value->field'");
            //dd($check);
            if ($check) {

                $table1 = $table;

            } else {

                foreach ($array as $t) {

                    $check = \DB::select("SHOW COLUMNS FROM $t where Field='$value->field'");

                    if ($check) {

                        $table1 = $t;
                        break;
                    }
                }
            }

            if ($value->data != '') {
                if ($value->op == 'cn') {
                    $wh .= " and $table1." . $value->field . " like '%" . $value->data . "%'";
                } else if ($value->op == 'bw') {
                    $wh .= " and $table1." . $value->field . " like '%" . $value->data . "%'";
                } else if ($value->op == 'eq') {
                    $wh .= " and $table1." . $value->field . " = '" . $value->data . "'";
                } else if ($value->op == 'gt') {
                    $wh .= " and $table1." . $value->field . " = '" . $value->data . "'";
                } else if ($value->op == 'ne') {
                    $wh .= " and $table1." . $value->field . " != '" . $value->data . "'";
                } else if ($value->op == 'bn') {
                    $wh .= " and $table1." . $value->field . " not like '" . $value->data . "%'";
                } else if ($value->op == 'ew') {
                    $wh .= " and $table1." . $value->field . " like '%" . $value->data . "'";
                } else if ($value->op == 'en') {
                    $wh .= " and $table1." . $value->field . " not like '%" . $value->data . "'";
                } else if ($value->op == 'cn') {
                    $wh .= " and $table1." . $value->field . " not like '%" . $value->data . "%'";
                } else if ($value->op == 'nu') {
                    $wh .= " and $table1." . $value->field . " = ''";
                } else if ($value->op == 'nn') {
                    $wh .= " and $table1." . $value->field . " != ''";
                } else if ($value->op == 'in') {
                    $wh .= " and $table1." . $value->field . " in (" . $value->data . ")";
                } else if ($value->op == 'ni') {
                    $wh .= " and $table1." . $value->field . " not in (" . $value->data . ")";
                }
            }
        }
        return $wh;
    }

    /**********************Insert Data****************/
    function insertData($model_name, $primary, $data, $id, $module)
    {
        if ($id == '') {
            $data->save();
            $id = \DB::getPdo()->lastInsertId();
            $this->auditlog($id, $module, 'create', $data, '');
        } else {
            $model_name::find($id)->update($_POST);
            $this->auditlog($id, $module, 'edit', $data, '');
        }
        return $id;
    }
    /*deepika purpose:insert data using model */
    function insertData1($model_name, $primary, $data, $id, $module)
    {
        if ($id == '') {
            $data->save();
            $id = \DB::getPdo()->lastInsertId();
            $this->auditlog($id, $module, 'create', $data, '');
        } else {
            $model_name::find($id)->update($data);
            $this->auditlog($id, $module, 'edit', $data, '');

        }
        return $id;
    }
    /*end*/
    // New format function --- VIGNESH M
    public function jcomboformlogin(Request $request)
    {
        $tableInput = $request->input('table');
        $parentInput = $request->input('parent');
        $orderBy = $request->input('order_by');
        $groupBy = $request->input('group_by');

        // Validate table input
        if (!$tableInput) {
            return response()->json([], 400); // Bad request
        }

        // Split format: table:primary_key:label_field
        $parts = explode(':', $tableInput);
        if (count($parts) < 3) {
            return response()->json([], 400); // Incomplete input
        }

        [$table, $keyField, $labelField] = $parts;

        // Start query builder
        $query = DB::table($table)->select("$keyField as val", "$labelField as option_name");

        // Optional parent condition (e.g., lookup_type='LOCATION_TYPES')
        if ($parentInput && strpos($parentInput, '=') !== false) {
            [$field, $value] = explode('=', $parentInput, 2);
            $field = trim($field);
            $value = trim($value, "'\" "); // Remove quotes and whitespace
            $query->where($field, $value);
        }

        // Optional ordering
        if ($orderBy) {
            $query->orderBy($orderBy);
        }

        // Optional group by
        if ($groupBy) {
            $query->groupBy($groupBy);
        }

        // Get result
        $result = $query->get();

        // Handle JSONP callback if needed
        if ($request->has('callback')) {
            $callback = $request->input('callback');
            return response($callback . '(' . $result->toJson() . ')', 200)
                ->header('Content-Type', 'application/javascript');
        }

        return response()->json($result);
    }

    /*End*/

    /*kavya-m Purpose for Form Jcombo company and  active */
    // public function jcomboform()
// {
//  $compy=\Session::get('companyid');
// $table=$_GET['table'];
// if(isset($_GET['parent']) && isset($_GET['order_by']))
// {

    // $parent="where ".$_GET['parent'];
// $orderby="ORDER BY ".$_GET['order_by'];
// }
// else if(isset($_GET['parent']) )
// {

    // $parent="where ".$_GET['parent'];
// $orderby="";
// }
// else
// {
// $parent='where 1=1 ';
// $orderby='';
// }
// $table=explode(":",$table);
//     $display_name=explode("|",$table[2]);
//     $display_n="";
// foreach($display_name as $val)
// {

    // $display_n.=$val.","."' - ',";
// }
// $dis_name=rtrim($display_n,",' - ',");

    // $result = \DB::select("SELECT $table[1] as val, CONCAT(".$dis_name.") as option_name FROM $table[0] $parent and company_id=".$compy." and active='Yes'  $orderby");
// $data= json_encode($result);


    // // dd(count($result));
// if(count($result)==0){
//  $response="[{'val':0,'option_name':'--Please Select --'}]";
// }else{
//   $response = isset($_GET['callback'])?$_GET['callback']."(".$data.")":$data;
// }
// return $response;
// }

    public function jcomboform()
    {
        $compy = \Session::get('companyid');
        $table = $_GET['table'];
        $wh1 = "";
        //dd($table);


        if (isset($_GET['parent']) && isset($_GET['order_by'])) {

            $parent = "where " . $_GET['parent'];
            $orderby = "ORDER BY " . $_GET['order_by'];
        } else if (isset($_GET['parent'])) {

            $parent = "where " . $_GET['parent'];
            $orderby = "";
        } else {
            $parent = 'where 1=1 ';
            $orderby = '';
        }
        $table = explode(":", $table);
        $display_name = explode("|", $table[2]);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");

        if ($table[0] == 'm_supplier_t') {
            $wh1 .= " and m_supplier_t.savestatus='APPROVED'";
        }

        if ($table[0] == 'i_pricelist_hdr_t') {
            $wh1 .= " and i_pricelist_hdr_t.savestatus='APPROVED'";
        }

        //dd($wh1);
        $result = \DB::select("SELECT $table[1] as val, CONCAT(" . $dis_name . ") as option_name FROM $table[0] $parent and company_id=" . $compy . "  $wh1  and active='Yes'  $orderby");
        $data = json_encode($result);


        // dd(count($result));
        if (count($result) == 0) {
            $response = "[{'val':0,'option_name':'--Please Select --'}]";
        } else {
            $response = isset($_GET['callback']) ? $_GET['callback'] . "(" . $data . ")" : $data;
        }
        return $response;
    }

    /*Harish Purpose for choosing all category*/
    public function jcomboformallcheck()
    {
        $compy = \Session::get('companyid');
        $table = $_GET['table'];
        if (isset($_GET['parent']) && isset($_GET['order_by'])) {

            $parent = "where " . $_GET['parent'];
            $orderby = "ORDER BY " . $_GET['order_by'];
        } else if (isset($_GET['parent'])) {

            $parent = "where " . $_GET['parent'];
            $orderby = "";
        } else {
            $parent = 'where 1=1 ';
            $orderby = '';
        }
        $table = explode(":", $table);
        $display_name = explode("|", $table[2]);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");

        $result = \DB::select("SELECT $table[1] as val, CONCAT(" . $dis_name . ") as option_name FROM $table[0] $parent and company_id=" . $compy . " and active='Yes'  $orderby");
        if (count($result) > 0) {
            $count = count($result);
            $all = (object) array();
            $all->val = "9999";
            $all->option_name = "ALL";
            array_unshift($result, $all);

        }
        //dd($result);
        $data = json_encode($result);
        if (count($result) == 0) {
            $response = "[{'val':0,'option_name':'--Please Select --'}]";
        } else {
            $response = isset($_GET['callback']) ? $_GET['callback'] . "(" . $data . ")" : $data;
        }
        return $response;
    }


    public function jcomboformallchecknew()
    {
        $compy = \Session::get('companyid');
        $groupname = \Session::get('groupname');
        $table = $_GET['table'];
        $condition = $_GET['condition'];
        if (isset($_GET['parent']) && isset($_GET['order_by'])) {

            $parent = "where " . $_GET['parent'];
            $orderby = "ORDER BY " . $_GET['order_by'];
        } else if (isset($_GET['parent'])) {

            $parent = "where " . $_GET['parent'];
            $orderby = "";
        } else if (isset($_GET['condition'])) {
            if ($groupname == "Production") {
                $parent = " where product_group_id=2";
            } else {
                $parent = " where product_group_id in (2,3)";
            }
            $orderby = "";
        } else {
            $parent = 'where 1=1 ';
            $orderby = '';
        }
        $table = explode(":", $table);
        $display_name = explode("|", $table[2]);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");

        $result = \DB::select("SELECT $table[1] as val, CONCAT(" . $dis_name . ") as option_name FROM $table[0] $parent and company_id=" . $compy . " and active='Yes'  $orderby");
        if (count($result) > 0) {
            $count = count($result);
            /*$all = (object) array();*/
            /*$all->val = "9999";
            $all->option_name = "ALL";*/
            /*array_unshift($result, $all);*/

        }
        //dd($result);
        $data = json_encode($result);
        if (count($result) == 0) {
            $response = "[{'val':0,'option_name':'--Please Select --'}]";
        } else {
            $response = isset($_GET['callback']) ? $_GET['callback'] . "(" . $data . ")" : $data;
        }
        return $response;
    }



    public function jcomboformrerule()
    {

        $compy = \Session::get('companyid');
        $table = $_GET['table'];//dd($table[1]);

        if (isset($_GET['parent']) && isset($_GET['order_by'])) {

            $parent = "where " . $_GET['parent'];
            $orderby = "ORDER BY " . $_GET['order_by'];
        } else if (isset($_GET['parent'])) {

            $parent = "where " . $_GET['parent'];
            $orderby = "";
        } else {
            $parent = 'where 1=1 ';
            $orderby = '';
        }
        $table = explode(":", $table);
        $display_name = explode("|", $table[2]);
        $display_n = "";

        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");
        //echo $dis_name; exit;
//echo "SELECT $table[1] as val, CONCAT(".$dis_name.") as option_name FROM $table[0] $parent and company_id=".$compy." and active='Yes'  $orderby"; exit;
        $result = \DB::select("SELECT $table[1] as val, CONCAT(" . $dis_name . ") as option_name FROM $table[0] $parent and company_id=" . $compy . " and active='Yes'  $orderby");
        //$result = \DB::select("SELECT $table[1] as val, CONCAT(".$dis_name.") as option_name FROM $table[0] $parent and company_id=".$compy." and active='Yes'  $orderby");
//foreach($result as $key=>$value)
//{
//
//    $result[$key] = (object)array();
//    $result[$key]->val = $value->val;
//    $parent_class = $this->getparent(parent_class_id);
//    $result[$key]->option_name = $parent_class."-".$value->sub_department_name;
//
//}
//dd($result);
//date($display_n)
        $data = json_encode($result);

        $response = isset($_GET['callback']) ? $_GET['callback'] . "(" . $data . ")" : $data;
        return $response;
    }

    /* Purpose for Form Jcombo company and no active */
    public function jcomboformcomp()
    {
        $compy = \Session::get('companyid');
        $table = $_GET['table'];
        if (isset($_GET['parent']) && isset($_GET['order_by'])) {
            $parent = "where " . $_GET['parent'];
            $orderby = "ORDER BY " . $_GET['order_by'];
        } elseif (isset($_GET['parent'])) {
            $parent = "where " . $_GET['parent'];
            $orderby = "";
        } else {
            $parent = 'where 1=1 ';
            $orderby = '';
        }
        if (isset($_GET['group_by'])) {
            $groupby = "group by " . $_GET['group_by'];
        } else {
            $groupby = "";
        }
        $table = explode(":", $table);
        $display_name = explode("|", $table[2]);
        $display_n = "";
        foreach ($display_name as $val) {
            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");
        $result = \DB::select("SELECT $table[1] as val, CONCAT(" . $dis_name . ") as option_name FROM $table[0] $parent and company_id=" . $compy . " $groupby $orderby");
        $data = json_encode($result);
        $response = isset($_GET['callback']) ? $_GET['callback'] . "(" . $data . ")" : $data;

        return $response;
    }
    /*End*/


    /* Purpose for Form Jcombo company and no active */
    public function jcomboformcompwithref()
    {

        $compy = \Session::get('companyid');
        $table = $_GET['table'];

        if (isset($_GET['parent']) && isset($_GET['order_by'])) {
            $parent = "where " . $_GET['parent'];
            $orderby = "ORDER BY " . $_GET['order_by'];
        } else {
            $parent = 'where 1=1 ';
            $orderby = '';
        }
        $table = explode(":", $table);
        $display_name = explode("|", $table[2]);

        $display_n = "";
        foreach ($display_name as $val) {
            $display_n .= $val . "," . "' - ',";
        }

        $dis_name = rtrim($display_n, ",' - ',");
        $list = array();

        $result = \DB::select("SELECT product_id FROM $table[0] $parent and company_id=" . $compy . " $orderby");


        if (count($result) > 0) {
            foreach ($result as $key => $value) {
                $list[] = $value->product_id;
            }
            $list = implode(',', $list);

        } else {
            $list = '0';
        }
        echo "SELECT $table[1] as val,CONCAT(" . $dis_name . ") as option_name FROM m_products_t where product_id in($list) $orderby";
        exit;
        $result = \DB::select("SELECT $table[1] as val,CONCAT(" . $dis_name . ") as option_name FROM m_products_t where product_id in($list) $orderby");
        $data = json_encode($result);
        $response = isset($_GET['callback']) ? $_GET['callback'] . "(" . $data . ")" : $data;

        return $response;
    }
    /*End*/

    /*saravanan purpose: in condition in jcombo*/
    public function jcomboform1()
    {

        $table = $_GET['table'];

        if (!empty($_GET['parent']) && !empty($_GET['order_by'])) {
            $parent = "where 1=1 " . $_GET['parent'];
            $orderby = "ORDER BY " . $_GET['order_by'];
        } else if (!empty($_GET['parent']) && empty($_GET['order_by'])) {
            $parent = "where 1=1 " . $_GET['parent'];
            $orderby = '';
        } else {
            $parent = '';
            $orderby = '';
        }
        $table = explode(":", $table);
        //dd($table[2]);
        $display_name = explode("|", $table[2]);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");
        //dd("SELECT CONCAT(".$dis_name.") as option_name, $table[1] as val FROM $table[0] $parent $orderby");

        $result = \DB::select("SELECT CONCAT(" . $dis_name . ") as option_name, $table[1] as val FROM $table[0] $parent $orderby");
        if (!empty($result)) {
            $data = json_encode($result);
        } else {
            $data1 = (object) array();
            $data1->val = '';
            $data = json_encode($data1);
        }


        $response = isset($_GET['callback']) ? $_GET['callback'] . "(" . $data . ")" : $data;
        return $response;
    }
    public function jcomboforminv()
    {

        $table = $_GET['table'];
        if (isset($_GET['parent']) && isset($_GET['order_by']) && isset($_GET['group_by'])) {
            $parent = "where " . $_GET['parent'];
            $orderby = "ORDER BY " . $_GET['order_by'];
            $groupby = "GROUP BY " . $_GET['group_by'];
        } else if (isset($_GET['parent']) && isset($_GET['order_by'])) {
            $parent = "where " . $_GET['parent'];
            $orderby = "ORDER BY " . $_GET['order_by'];
            $groupby = "";
        } else {
            $parent = '';
            $orderby = '';
            $groupby = '';
        }

        if ($parent == "where department=53") {
            $parent = "where  department LIKE '%53%'";
        }
        //dd($_GET['parent']  );
        $table = explode(":", $table);
        if ($table[2] == "employee_number|first_name") {
            $table[2] = "CONCAT(employee_number,'-',first_name)";
        }
        $wh = '';
        if ($table[0] == "hr_employee_t") {
            $wh = " and active='Yes'";
            $result = \DB::select("SELECT $table[2] as val, $table[2] as option_name FROM $table[0] $parent $wh $groupby $orderby ");

        }
        if ($table[0] == "w_machine_lines_t") {
            $result = \DB::select("SELECT frequency_tbl.frequency_id as val, frequency_tbl.frequency_name as option_name from frequency_tbl left join w_machine_lines_t on w_machine_lines_t.frequency_id=frequency_tbl.frequency_name  $parent $groupby $orderby ");
        } else {
            $result = \DB::select("SELECT $table[1] as val, $table[2] as option_name FROM $table[0] $parent $wh $groupby $orderby ");

        }

        if (!empty($result)) {
            $data = json_encode($result);
        } else {
            $data1 = (object) array();
            $data1->val = '';
            $data = json_encode($data1);
        }

        $response = isset($_GET['callback']) ? $_GET['callback'] . "(" . $data . ")" : $data;
        return $response;
    }

    public function decvalue($id = null)
    {
        $decivalue = \DB::select("select decimal_points from settings_tbl");
        $decidtl = $decivalue[0]->decimal_points;
        return $decidtl;
    }


    public function dateform($id = null)
    {
        $datevalue = \DB::select("select df.javascript_format from settings_tbl st left JOIN date_formats_tbl df on df.date_formats_id = st.date_format");
        $datefrmt = $datevalue[0]->javascript_format;
        return $datefrmt;
    }

    /* purpose:get Supplier Details*/
    public function getSuppliergridData()
    {

        $wh = '';

        if (!empty($_GET['site_type'])) {
            $wh .= " and m_supplier_sites_t.site_type='" . $_GET['site_type'] . "' and m_supplier_sites_t.supplier_id=" . $_GET['cid'];
        }

        $SQL = "SELECT m_supplier_t.supplier_id as supplierid,m_supplier_t.location_id,m_supplier_t.supplier_number as supplier_number,m_supplier_t.supplier_name,m_suppliertypes_t.suppliertype_name,
m_supplier_sites_t.supplier_site_name as supplier_site_name,m_supplier_sites_t.site_type as site_type,m_supplier_sites_t.address as address,m_cities_t.city_name,m_states_t.state_name,
m_countries_t.country_name FROM `m_supplier_t` left join m_supplier_sites_t on(m_supplier_sites_t.supplier_site_id=m_supplier_t.`supplier_id`)left join m_suppliertypes_t 
on(m_suppliertypes_t.suppliertype_id=m_supplier_t.`supplier_type_id`) left join m_countries_t on(m_countries_t.country_id=m_supplier_sites_t.`country`)
left join m_cities_t on(m_cities_t.city_id=m_supplier_sites_t.`city`)left join m_states_t on(m_states_t.state_id=m_supplier_sites_t.`state`)where 1=1 and m_supplier_t.savestatus='APPROVED' and
m_supplier_t.active= 'Yes' and m_supplier_sites_t.active='Yes' $wh";

        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);
    }

    /*end*/

    /*deepika purpose:get Customer Details*/
    public function getCustomergridData()
    {
        $wh = '';

        if ((!empty($_GET['site_type']))) {
            //dd($_GET['site_type']);
            $wh .= " and m_customer_sites_t.site_type='" . $_GET['site_type'] . "' and m_customer_sites_t.customer_id=" . $_GET['cid'];
        }
        if (isset($_GET['statusship'])) {
            $wh .= " and  m_customer_sites_t.site_type='SHIP_TO'";
        }
        if (isset($_GET['status'])) {

            $wh .= " and (m_customers_t.status='QUICKCUSTOMER' OR m_customers_t.savestatus='SAVE')  and m_customer_sites_t.active='Yes' ";
        } else {
            $wh .= "  and m_customers_t.active='Yes'  and  m_customer_sites_t.active='Yes'";
        }
        $wh .= " and m_customers_t.savestatus='SAVE' ";

        $SQL = "SELECT m_customers_t.customer_id,m_customers_t.customer_number,m_customers_t.customer_name,m_customer_types_t.customer_type,m_customer_sites_t.customer_site_id,
    m_customer_sites_t.customer_site_name,m_customer_sites_t.site_type,m_customer_sites_t.address,m_cities_t.city_name,m_states_t.state_name,m_countries_t.country_name,
    m_customer_sites_t.pincode,m_customer_sites_t.contact_number,m_customer_sites_t.primary_address  from m_customers_t  left join  m_customer_sites_t on(m_customers_t.customer_id=m_customer_sites_t.customer_id) left join m_cities_t  on(m_customer_sites_t.city=m_cities_t.city_id) left join m_states_t  on(m_customer_sites_t.state=m_states_t.state_id) left join m_countries_t  on(m_countries_t.country_id=m_customer_sites_t.country) left join m_customer_types_t on (m_customer_types_t.customer_type_id=m_customers_t.customer_type_id) where 1=1 $wh";

        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);
    }


    /* purpose:get Product Details*/
    public function getProductgridData(Request $request)
    {

        $prdgp = "";
        $prdgpname = "";
        $comp = \Session::get('companyid');
        if (isset($_GET['prggrp'])) {
            $sql = \DB::select("select product_group_id,group_name from m_product_groups_t where group_name in(" . $_GET['prggrp'] . ")");
            $prdgp .= "and m_products_t.product_group_id='" . $sql[0]->product_group_id . "'";
            $prdgpname .= "and m_product_groups_t.group_name in(" . $_GET['prggrp'] . " )";
        }

        $wh = '';

        $wh .= " and m_products_t.product_status='APPROVED' and m_products_t.active='Yes' and m_products_t.company_id=" . $comp;


        if (isset($_GET['pricelist_id'])) {
            $SQL = "SELECT m_products_t.product_id,m_products_t.product_code,m_products_t.concatenated_product,m_product_groups_t.group_name,m_product_category_t.category_name from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id)  left join m_product_category_t on(m_product_category_t.product_category_id=m_products_t.product_category_id) left join i_pricelist_lines_t on i_pricelist_lines_t.product_id=m_products_t.product_id where 1=1  and i_pricelist_lines_t.pricelist_hdr_id='" . $_GET['pricelist_id'] . "'  and i_pricelist_lines_t.active='Yes' $prdgpname $wh";
        } else {
            $SQL = "SELECT m_products_t.product_id,m_products_t.product_code,m_products_t.concatenated_product,m_product_groups_t.group_name ,m_product_category_t.category_name from m_products_t left join m_product_groups_t on(m_product_groups_t.product_group_id=m_products_t.product_group_id)  left join m_product_category_t on(m_product_category_t.product_category_id=m_products_t.product_category_id) where 1=1 $prdgpname $wh";
        }

        $result = \DB::select($SQL);
        return DataTables::of($result)->make(true);
    }


    /*end*/

    public function getProductgridDatasubinventory()
    {

        $prdgp = "";
        $prdgpname = "";

        $wh = '';


        $compy = \Session::get('companyid');

        if (isset($_GET['sub_inve'])) {

            $SQL = "SELECT m_products_t.product_code, m_product_groups_t.group_name, m_product_category_t.category_name, m_products_t.concatenated_product,m_products_t.product_id FROM i_qoh_detail_t LEFT JOIN m_products_t ON( m_products_t.product_id = i_qoh_detail_t.product_id ) LEFT JOIN m_product_groups_t ON( m_product_groups_t.product_group_id = m_products_t.product_group_id ) LEFT JOIN m_product_category_t ON( m_product_category_t.product_category_id = m_products_t.product_category_id ) WHERE 1 = 1 AND i_qoh_detail_t.subinventory_id = '" . $_GET['sub_inve'] . "' and i_qoh_detail_t.company_id='$compy' $prdgpname $wh";
        } else {

            $SQL = "SELECT m_products_t.product_code, m_product_groups_t.group_name, m_product_category_t.category_name, m_products_t.concatenated_product,m_products_t.product_id FROM i_qoh_detail_t LEFT JOIN m_products_t ON( m_products_t.product_id = i_qoh_detail_t.product_id ) LEFT JOIN m_product_groups_t ON( m_product_groups_t.product_group_id = m_products_t.product_group_id ) LEFT JOIN m_product_category_t ON( m_product_category_t.product_category_id = m_products_t.product_category_id ) WHERE 1 = 1 AND i_qoh_detail_t.company_id='$compy' $prdgpname $wh";
        }


        $result = \DB::select($SQL);

        return DataTables::of($result)->make(true);

    }



    public function taxdetails($id = null, $suppsite = null, $mtype = null)
    {
        $prd_data1 = array();
        $date = date('Y-m-d');
        $tax_value = 0;
        $current_location = 1;

        $query = \DB::select("SELECT `state_id` FROM `m_location_t` where `location_id` ='$current_location'");
        //dd($query);
        $cur_state = '';
        if (!empty($query)) {
            $cur_state = $query[0]->state_id;
        }
        $query1 = '';
        $supplier_location = array();

        if ($mtype == "PURCHASE") {

            $query1 = \DB::select("SELECT `state` FROM `m_supplier_sites_t` WHERE `supplier_site_id`='$suppsite'");
        } else {
            $query1 = \DB::select("SELECT `state` FROM `m_customer_sites_t` WHERE `customer_site_id`='$suppsite'");
        }

        if (!empty($query1)) {
            $supplier_location['state'] = $query1[0]->state;
        } else {
            $supplier_location['state'] = '';
        }

        $supplier_state = $supplier_location['state'];

        if ($cur_state != '' && $supplier_state != '') {

            if ($cur_state == $supplier_state) {


                $tax = \DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='$id' and ('$date' between start_date and end_date) and active='Yes' and tax_location_type='Intrastate(within-state)'");
                // dd("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='$id' and ('$date' between start_date and end_date) and active='Yes' and tax_location_type='Intrastate(within-state)'");
                if (!empty($tax)) {
                    $prd_data1['tax_group_id'] = $tax_value = $tax[0]->tax_group_id;
                    $tax1 = \DB::table("m_tax_group_t")->select('display_name')->where('tax_group_id', $tax[0]->tax_group_id)->get();
                    $prd_data1['display_name'] = $tax1[0]->display_name;

                } else {
                    $prd_data1['tax_group_id'] = $tax_value = 0;
                    $type = 1;
                }
            } else {
                $tax = \DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='$id' and ('$date' between start_date and end_date) and active='Yes' and tax_location_type='Interstate'");
                //dd("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='$id' and ('$date' between start_date and end_date) and active='Yes' and tax_location_type='Interstate'");
                if (!empty($tax)) {
                    $prd_data1['tax_group_id'] = $tax_value = $tax[0]->tax_group_id;
                } else {
                    $prd_data1['tax_group_id'] = $tax_value = 0;
                    $type = 2;
                }
            }
        } else {
            $prd_data1['tax_group_id'] = $tax_value = 0;
        }
        //-----------------------------------

        if ($tax_value == 0) {
            if ($type = '1') {
                $tax_check = \DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='$id' and  active='Yes' and tax_location_type='Intrastate(within-state)'");

            } else {
                $tax_check = \DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='$id' and  active='Yes' and tax_location_type='Interstate'");

            }
            //     dd($tax_check);
            if (count($tax_check) > 0) {
                $tax_group = $tax_check[0]->tax_group_id;
                $tax_expiry = \DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='$id' and tax_group_id='$tax_group'");
                // dd("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='$id' and tax_group_id='$tax_group'");
                if (count($tax_expiry) > 0) {
                    $prd_data1['tax_group_id_expiry'] = "expiry";
                }
            } else {
                $prd_data1['tax_group_id_expiry'] = "location";
            }

        }
        //dd($prd_data1);
        return $prd_data1;
    }


    /*Karthigaa Purpose for loading product details*/
    public function productDetails($pid = null, $plid = null, $suppsite = null, $type = null)
    {

        $prd_data = array();
        $prd_data['uom_code_id'] = '';
        $prd_data['unit_price'] = '';
        $prd_data['tax_group_id'] = '';
        $prd_data['hsn_code'] = '';
        $prd_data['multihsn'] = '';
        $prd_data['qoh_qty'] = '';
        $supplier_id = $_GET['supplier_id'];
        $source = $_GET['source'];
        $compy = \Session::get('companyid');

        if ($pid != '') {
            if (isset($_GET['supplier_id'])) {
                $manufactpartno = \DB::select('select * from m_manufacturer_partno_t where product_id=' . $pid . ' and manufacturer_source_value_id=' . $_GET['supplier_id'] . ' and manufacturer_source="SUPPLIER"');
            }

            if (count($manufactpartno) > 0) {
                $prd_data['part_no'] = $manufactpartno[0]->manufacturer_partno_id;
            } else {
                $prd_data['part_no'] = 0;
            }

            if ($type == 'STANDARD') {
                $pro_details = \DB::table("m_products_t")->select('product_id', 'primary_uom_id', 'hsn_code', 'defalut_hsn_code', 'min_order_qty', 'max_order_qty')->where('product_id', $pid)->get();

                if (!empty($pro_details))
                    $prd_data['product_id'] = $pro_details[0]->product_id;
                $prd_data['uom_code_id'] = $pro_details[0]->primary_uom_id;
                $hsn_code = $pro_details[0]->defalut_hsn_code;
                $prd_data['hsn_code'] = $pro_details[0]->defalut_hsn_code;

                $prd_data['multihsn'] = $pro_details[0]->hsn_code;

                //$prd_data['min_order_qty']=$pro_details[0]->min_order_qty;
                //$prd_data['max_order_qty']=$pro_details[0]->max_order_qty;
                $date = date('Y-m-d');
            } else {
                $pro_details = \DB::table("m_products_t")->select('primary_uom_id', 'hsn_code', 'min_order_qty', 'max_order_qty')->where('product_id', $pid)->get();
                if (!empty($pro_details))
                    $prd_data['uom_code_id'] = $pro_details[0]->primary_uom_id;
                $hsn_code = $pro_details[0]->hsn_code;
                // $prd_data['min_order_qty']=$pro_details[0]->min_order_qty;
                //  $prd_data['max_order_qty']=$pro_details[0]->max_order_qty;
                $date = date('Y-m-d');
            }
            //-----------------------------------

            $price_cnd = array('pricelist_hdr_id' => $plid, 'product_id' => $pid);
            $pricelist_details = $this->pricelist_details($price_cnd);
            //        dd($pricelist_details);
            if (!empty($pricelist_details)) {
                $prd_data['unit_price'] = $pricelist_details[0]->unit_price;
            }
            $qoh_qty = DB::SELECT("select sum(f.qty-IFNULL(f.qtyy,0)) as qoh_qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id=" . $pid . " and  i_qoh_detail_t. company_id=" . $compy . "  GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id=" . $pid . " and  i_reservation_detail_t. company_id=" . $compy . "  GROUP by product_id)f");


            if (!empty($qoh_qty)) {
                $prd_data['qoh_qty'] = $qoh_qty[0]->qoh_qty;
            } else {
                $prd_data['qoh_qty'] = 0;
            }
            $mtype = 'PURCHASE';
            $taxdata = $this->taxdetails($hsn_code, $suppsite, $mtype);
            //        dd($taxdata);
            $prd_data['tax_group_id'] = $taxdata['tax_group_id'];
        }
        //    dd($prd_data);
        return $prd_data;
    }

    public function productdetails_so($pid = null, $plid = null, $cussite = null, $type = null)
    {

        $prd_data = array();

        $prd_data['uom_code_id'] = '';
        $prd_data['unit_price'] = '';
        $prd_data['tax_group_id'] = '';
        $prd_data['qoh_qty'] = '';
        $prd_data['manufactpartno'] = 0;
        $customer_id = '';

        if (isset($_GET['customer_id'])) {
            $cus = $_GET['customer_id'];
            $col = 'customer_id';
        } else {
            $cus = $cussite;
            $col = 'customer_site_id';
        }

        $customer = \DB::table('m_customer_sites_t')->where($col, $cus)->select('customer_id')->groupBy('customer_id')->get();
        if (count($customer) > 0) {
            $customer_id = $customer[0]->customer_id;
        } else {
            $customer_id = '';
        }

        $data = \DB::table('m_manufacturer_partno_t')->where('product_id', $pid)->where('manufacturer_source_value_id', $customer_id)->get();

        if (count($data) > 0) {
            $prd_data['manufactpartno'] = $data[0]->manufacturer_partno_id;
        }

        $pro_details = \DB::table("m_products_t")->select('trx_uom_id', 'hsn_code', 'defalut_hsn_code', 'min_order_qty', 'max_order_qty')->where('product_id', $pid)->get();

        if (!empty($pro_details))
            $prd_data['uom_code_id'] = $pro_details[0]->trx_uom_id;

        $hsn_code = $pro_details[0]->defalut_hsn_code;
        $prd_data['hsn_code'] = $pro_details[0]->defalut_hsn_code;
        $prd_data['multihsn'] = $pro_details[0]->hsn_code;

        $date = date('Y-m-d');

        $tax = \DB::select("select tax_group_id from f_gst_code_lines_t where gst_code_hdr_id='$hsn_code' and start_date<='$date' and end_date>='$date' and active='Yes'");
        if (!empty($tax)) {
            $prd_data['tax_group_id'] = $tax[0]->tax_group_id;
        }


        $price_cnd = array('pricelist_hdr_id' => $plid, 'product_id' => $pid);

        $pricelist_details = $this->pricelist_details($price_cnd);
        //dd($pricelist_details);
        if (!empty($pricelist_details)) {
            //  dd($pricelist_details[0]->std_price);
            $prd_data['unit_price'] = $pricelist_details[0]->unit_price;
            $prd_data['std_price'] = $pricelist_details[0]->std_price;
        }
        //dd($prd_data['unit_price']);
        $compy = \Session::get('companyid');
        $qoh_qty = DB::SELECT("select sum(f.qty-f.qtyy) as qoh_qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id=" . $pid . " and  i_qoh_detail_t.subinventory_id != '5' and  i_qoh_detail_t.company_id=" . $compy . " GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id=" . $pid . " and  i_reservation_detail_t.subinventory_id!= '5' and  i_reservation_detail_t.company_id=" . $compy . "  GROUP by product_id)f");

        if (!empty($qoh_qty)) {
            $prd_data['qoh_qty'] = $qoh_qty[0]->qoh_qty;
        } else {
            $prd_data['qoh_qty'] = 0;
        }
        $mtype = 'SALES';

        $taxdata = $this->taxdetails($hsn_code, $cussite, $mtype);

        $prd_data['tax_group_id'] = $taxdata['tax_group_id'];
        //dd($prd_data);
        return $prd_data;
    }

    public function productdetails_so_old($pid = null)
    {

        $prd_data = array();


        $prd_data['uom_code_id'] = '';
        $prd_data['unit_price'] = '';
        $prd_data['tax_group_id'] = '';
        $prd_data['qoh_qty'] = '';

        $pro_details = \DB::table("m_products_t")->select('trx_uom_id')->where('product_id', $pid)->get();
        // dd($pro_details);
        if (!empty($pro_details))
            $prd_data['uom_code_id'] = $pro_details[0]->trx_uom_id;


        $compy = \Session::get('companyid');
        $qoh_qty = DB::SELECT("select sum(f.qty-f.qtyy) as qoh_qty,f.product_id from (select sum(qoh_trx_qty)as qty,0 as qtyy,product_id FROM i_qoh_detail_t where product_id=" . $pid . " and  i_qoh_detail_t. company_id=" . $compy . "  GROUP by product_id   UNION ALL SELECT 0 as qty,sum(reserv_trx_qty) as qtyy,product_id as fdfd from i_reservation_detail_t where product_id=" . $pid . " and  i_reservation_detail_t. company_id=" . $compy . "  GROUP by product_id)f");



        if (!empty($qoh_qty)) {
            $prd_data['qoh_qty'] = $qoh_qty[0]->qoh_qty;
        } else {
            $prd_data['qoh_qty'] = 0;
        }
        $mtype = 'SALES';

        return $prd_data;
    }

    public function single_select_to_outer_array($array = null)
    {
        $new_array = array();

        if (!empty($array)) {
            foreach ($array as $k => $v) {
                foreach ($v as $k1 => $v1) {
                    array_push($new_array, $v1);
                }

            }

        }

        return $new_array;
    }


    public function pricelist_details($price_cnd = null)
    {
        $cond = '';

        if (!empty($price_cnd)) {
            $price_cnd_cnt = count($price_cnd);

            foreach ($price_cnd as $col_name => $col_val) {
                if ($price_cnd_cnt > 1) {
                    $cond .= " and $col_name='$col_val'";
                } else {
                    $cond = " and $col_name='$col_val'";
                }
            }
        }

        $q = "select * from (SELECT pll.`pricelist_line_id` pricelist_line_id, plh.`pricelist_hdr_id`pricelist_hdr_id, plh.`pricelist_name` pricelist_name, plh.`price_list_type` price_list_type, plh.`description` description, plh.`currency_code_id` currency_code_id, pll.`start_date` start_date, pll.`end_date` end_date, plh.`remarks` remarks, plh.`organization_id` organization_id, plh.`company_id` company_id, pll.`line_no` line_no, pll.`product_id` product_id, pll.`unit_price` unit_price, pll.`std_price` std_price, pll.`active` active, pll.`comments` comments FROM `i_pricelist_lines_t` pll JOIN i_pricelist_hdr_t plh ON ( pll.`pricelist_hdr_id` = plh.pricelist_hdr_id )) pldata where 1=1 and (CURDATE() between start_date and end_date) and active='Yes'" . $cond . " order by  pldata.`pricelist_line_id` desc";

        $res = \DB::select($q);

        if (empty($res)) {
            $res = [];
            $res[0] = (object) $res;
            $res[0]->unit_price = 0;
            $res[0]->std_price = 0;
        }
        //dd($res);
        return $res;
    }


    public function productDetails1($id = null)
    {

        $prd_data = array();

        if ($id != '') {
            $uom_code_id = '1';
            $unit_price = '100';
            $tax_group_id = '1';

            $prd_data['uom_code_id'] = $uom_code_id;
            $prd_data['unit_price'] = $unit_price;
            $prd_data['tax_group_id'] = $tax_group_id;
        }

        return $prd_data;
    }

    public function jcomboformtax()
    {
        $compy = \Session::get('companyid');
        $table = $_GET['table'];
        if (isset($_GET['parent']) && isset($_GET['order_by'])) {
            $parent = "where " . $_GET['parent'];
            $orderby = "ORDER BY " . $_GET['order_by'];
        } else if (isset($_GET['parent'])) {
            $parent = "where " . $_GET['parent'];
            $orderby = "";
        } else {
            $parent = 'where 1=1 ';
            $orderby = '';
        }
        $table = explode(":", $table);

        $display_name = explode("|", $table[2]);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");

        $result = \DB::select("SELECT $table[1] as val, CONCAT(" . $dis_name . ") as option_name,$table[3] as datadisplay FROM $table[0] $parent and company_id=" . $compy . " and active='Yes'  $orderby");
        $data = json_encode($result);

        if (count($result) == 0) {
            $response = "[{'val':0,'datadisplay':'0','option_name':'--Please Select --'}]";
        } else {
            $response = isset($_GET['callback']) ? $_GET['callback'] . "(" . $data . ")" : $data;
        }

        return $response;
    }

    public function jCombotax($table, $option, $display, $selects)
    {
        $display_name = explode("|", $display);
        $dis = "";
        foreach ($display_name as $value) {
            $dis .= $value . "," . "' - ', ";
        }

        $dis_name = rtrim($dis, ",' - ',");
        //dd("select DISTINCT CONCAT(".$dis_name.") as name,display_name ,".$option." from ".$table);
        $select = \DB::select("select DISTINCT CONCAT(" . $dis_name . ") as name,display_name ," . $option . " from " . $table);
        $html = "<option value=''>-- Please Select --</option>";
        foreach ($select as $val) {
            if ($val->$option == $selects) {
                $html .= "<option value='" . $val->$option . "' data-display='" . $val->display_name . "' selected='selected'>" . $val->name . "</option>";
            } else {
                $html .= "<option value='" . $val->$option . "'  data-display='" . $val->display_name . "' >" . $val->name . "</option>";
            }
        }
        return $html;
    }
    /*end*/



    protected function normalizeLineFormKeys(array $form): array
    {
        $out = $form;

        foreach ($form as $key => $val) {
            if (!is_array($val))
                continue;                 // only line arrays
            if (!Str::startsWith($key, 'bulk_'))
                continue; // only prefixed

            $plain = substr($key, 5); // remove "bulk_"

            // If plain key doesn't exist, just rename
            if (!array_key_exists($plain, $out)) {
                $out[$plain] = $val;
            } else {
                // Ensure it's an array before merging
                $merged = is_array($out[$plain]) ? $out[$plain] : [];

                // Both exist: merge index-wise, prefer plain if non-null/nonnull-string
                foreach ($val as $i => $v) {
                    $hasPlain = array_key_exists($i, $merged);
                    $plainV = $hasPlain ? $merged[$i] : null;

                    $useBulk = !$hasPlain
                        || $plainV === null
                        || $plainV === '';

                    if ($useBulk) {
                        $merged[$i] = $v;
                    }
                }
                $out[$plain] = $merged;
            }

            unset($out[$key]); // remove bulk_* key
        }

        return $out;
    }





    // new validate save function for ajax -- VIGNESH MS
    public function validatePost($form_data = null, $table = null, $module_type = null)
    {
        $data = [];
        $col_data = $this->Configtabledata($table);

        // Load form_config from either legacy form_data_json or direct array
        if (isset($form_data['form_data_json'])) {
            $form_config = json_decode(urldecode($form_data['form_data_json']), true) ?: [];
        } else {
            $form_config = $form_data['form_config'] ?? [];
        }

        // Common pass-through
        if (isset($form_data['removed_line_id'])) {
            $data['removed_line_id'] = $form_data['removed_line_id'];
        }

        // ---------------------------
        // HEADER
        // ---------------------------
        if ($module_type === 'header') {
            // Fallback if no header config: copy all non-array scalars
            if (empty($form_config['header'])) {
                foreach ($form_data as $key => $value) {
                    if (!is_array($value)) {
                        $data[$key] = $value;
                    }
                }
            } else {
                // Config-driven header
                $str = $form_config['header'];
                foreach ($str as $f) {
                    $field = $f['field'] ?? null;
                    if (!$field)
                        continue;

                    if (array_key_exists($field, $form_data)) {
                        $value = $form_data[$field];
                        $type = $f['type'] ?? '';

                        if ($type === 'checkbox' && is_array($value)) {
                            $data[$field] = implode(',', $value);
                        } elseif ($type === 'date') {
                            $data[$field] = ($value !== '') ? date('Y-m-d', strtotime($value)) : null;
                        } elseif ($type === 'select' && !empty($f['option']['select_multiple'])) {
                            $data[$field] = is_array($value) ? implode(',', $value) : $value;
                        } else {
                            $data[$field] = $value;
                        }
                    }
                }
            }

            // Audit for header
            $global = $this->access['is_global'] ?? 0;
            if ((int) $global === 0) {
                $data['created_by'] = \Session::get('id');
                $data['created_at'] = now();
                $data['last_updated_by'] = \Session::get('id');
                $data['updated_at'] = now();
                $data['location_id'] = \Session::get('location');
                $data['company_id'] = \Session::get('companyid');
                $data['organization_id'] = \Session::get('organization');
            }

            return $data;
        }

        // ---------------------------
        // LINES
        // ---------------------------

        // Indices (counters) from post, if present
        $counter_array = $form_data['counter'] ?? [];

        // Fallback when there is NO config for lines:
        if (empty($form_config['lines'])) {
            $ignore = [
                '_token',
                'form_config',
                'form_data_json',
                'choosefile',
                'existing_file',
                'removed_line_id',
                'quotation_hdr_id',
                'savestatus',
                'quote_status',
                'submit_type',
            ];

            $tmp = [];
            foreach ($form_data as $k => $v) {
                if (in_array($k, $ignore, true))
                    continue;
                if (is_array($v)) {
                    // Treat any array as a line field as-is (no bulk_ stripping because no config)
                    $tmp[$k] = $v;
                }
            }

            // Derive counters if not provided
            if (empty($counter_array)) {
                $firstArray = reset($tmp) ?: [];
                $counter_array = array_keys($firstArray);
            }
            $counter_array = array_values($counter_array);

            // Copy collected arrays
            foreach ($tmp as $k => $arr) {
                foreach ($counter_array as $i) {
                    if (array_key_exists($i, $arr)) {
                        $data[$k][$i] = $arr[$i];
                    }
                }
            }

            // Per-row audit
            $global = $this->access['is_global'] ?? 0;
            if ((int) $global === 0) {
                foreach ($counter_array as $i) {
                    $data['created_by'][$i] = \Session::get('id');
                    $data['created_at'][$i] = now();
                    $data['last_updated_by'][$i] = \Session::get('id');
                    $data['updated_at'][$i] = now();
                    $data['location_id'][$i] = \Session::get('location');
                    $data['company_id'][$i] = \Session::get('companyid');
                    $data['organization_id'][$i] = \Session::get('organization');
                }
            }

            // removed_line_id already added above
            return $data;
        }

        // Config-driven lines
        $str = $form_config['lines'];
        foreach ($str as $f) {
            $field = $f['field'] ?? '';
            if ($field === '')
                continue;

            // qty[] -> qty ; bulk_qty[] -> bulk_qty
            $field_br = preg_replace('/\[\]/', '', $field);
            // remove ONLY a leading 'bulk_' to map to your DB keys
            $field_key = preg_replace('/^bulk_/', '', $field_br);

            if ($field_key === 'counter')
                continue;

            // include only if view == 1 (default = true)
            $isView = array_key_exists('view', (array) $f) ? ((int) $f['view'] === 1) : true;
            if (!$isView)
                continue;

            // If counters missing, derive from posted field keys for this field
            $indices = !empty($counter_array)
                ? array_values($counter_array)
                : array_keys($form_data[$field_br] ?? []);

            foreach ($indices as $i) {
                $val = $form_data[$field_br][$i] ?? null;
                if ($val === null)
                    continue;

                $type = $f['type'] ?? '';
                if ($type === 'checkbox' && is_array($val)) {
                    $val = implode(',', $val);
                } elseif ($type === 'date') {
                    $val = ($val !== '') ? date('Y-m-d', strtotime($val)) : null;
                } elseif ($type === 'select' && !empty($f['option']['select_multiple'])) {
                    $val = is_array($val) ? implode(',', $val) : $val;
                }

                $data[$field_key][$i] = $val;

                // per-row audit
                $global = $this->access['is_global'] ?? 0;
                if ((int) $global === 0) {
                    $data['created_by'][$i] = \Session::get('id');
                    $data['created_at'][$i] = now();
                    $data['last_updated_by'][$i] = \Session::get('id');
                    $data['updated_at'][$i] = now();
                    $data['location_id'][$i] = \Session::get('location');
                    $data['company_id'][$i] = \Session::get('companyid');
                    $data['organization_id'][$i] = \Session::get('organization');
                }
            }
        }

        // removed_line_id already handled above
        return $data;
    }


    function Configtabledata($table = null)
    {

        $new_field_data = array();

        $get_table_columns = "SHOW COLUMNS FROM $table";
        $col_data = \DB::select($get_table_columns);

        if (!empty($col_data)) {
            // HEADER AND LABEL DETAILS
            $new_field_data['tablePimarykey'] = '';

            foreach ($col_data as $key => $field) {
                $table_field_key = $field->Key;
                $field = $field->Field;

                $new_field_data['field_data'][$field] = $field;


                if ($table_field_key == "PRI") {
                    $new_field_data['tablePimarykey'] = $field;
                }

                //dd($table_field_key,$new_field_data);


            }
        }

        return $new_field_data;

    }
    /*deepika purpose:seqno for batch in job card*/
    public function BatchSeqno($seqletter, $seqname1, $seqno, $table, $type)
    {
        $seqno = sprintf('%03d', $seqno + 1);
        return $seqletter . $seqno . $seqname1;
    }
    /*end*/
    public function Seqno($seqname, $table, $type)
    {


        $seqno = \DB::table($table)->count();


        if ($type == "LABOUR") {
            $seqname = $seqname . 'L';
        }
        if ($type == "STANDARD") {
            $seqname = $seqname . 'S';
        }
        $seqno = sprintf('%03d', $seqno + 1);

        return $seqname . $seqno;
    }

    public function Seqnoe($seqname, $table, $type, $column)
    {

        if ($table == "s_salesorder_hdr_t") {
            $seqno = \DB::table($table)->where('order_type_id', $type)->orderBy($column, 'desc')->get();
            /*if($type == "EXPORT" || $type=='EXPORT SAMPLE')
            {

            }
            else
            {
                 $seqno = \DB::table($table)->where('order_type_id','!=','EXPORT')->where('order_type_id','!=','EXPORT SAMPLE')->orderBy($column,'desc')->get();
             }*/
        } else {
            $seqno = \DB::table($table)->orderBy($column, 'desc')->get();
        }

        if (count($seqno) > 0) {
            $seqno = $count = $seqno[0]->{$column};
        } else {
            $seqno = $count = 0;
        }

        if ($type == "LABOUR") {
            $seqname = $seqname . 'L';
        }
        if ($type == "STANDARD") {
            $seqname = $seqname . 'S';
        }
        if ($type == "SAMPLE") {
            $seqname = $seqname . 'SM';
        }

        //$seqno = sprintf('%03d',$seqno+1);
        $seqno = sprintf('%04d', $seqno + 1);
        $count = $count + 1;
        // $y = date('y');
        // $nxt = date('y',strtotime('+1 year'));
        $seqmon = date('m');
        if ($seqmon > 3) {
            $y = date('y');
            $nxt = date('y', strtotime('+1 year'));
        } else {
            $y = date('y', strtotime('-1 year'));
            $nxt = date('y');
        }
        if ($table == "s_dispatch_hdr_t") {
            if ($type == "EXPORT") {
                //$seqno = sprintf('%04d',$seqno+1);
                $seqno = sprintf('%03d', $seqno + 1);
                $out_put[0] = $seqname . $seqno;
            } elseif ($type == 'EXPORT SAMPLE') {
                //$seqno = sprintf('%04d',$seqno+1);
                $seqno = sprintf('%03d', $seqno + 1);
                $out_put[0] = $seqname . $seqno;
            } else {
                $out_put[0] = $seqname . "-" . $y . $nxt . $seqno;
            }
        } else {
            $out_put[0] = $seqname . $seqno;
        }

        $out_put[1] = $count;


        return $out_put;

    }

    public function Seqnoapproved($seqname, $table, $type, $column, $status, $page_module, $count_column)
    {
        if ($page_module == "salesinvoice") {
            $seqno = \DB::table($table)->where($column, $status)->orderBy($count_column, 'desc')->get();
            if (count($seqno) > 0) {
                $seqno = $count = $seqno[0]->{$count_column};
            } else {
                $seqno = $count = 0;
            }
        } else {
            $seqno = \DB::table($table)->orderBy($count_column, 'desc')->get();
            if (count($seqno) > 0) {
                $seqno = $count = $seqno[0]->{$count_column};
            } else {
                $seqno = $count = 0;
            }
        }

        if ($type == "LABOUR") {
            $seqname = $seqname . 'L';
        }
        if ($type == "STANDARD") {
            $seqname = $seqname . 'S';
        }
        if ($type == "SAMPLE") {
            $seqname = $seqname . 'SM';
        }
        if ($type == "EXPORT INVOICE" || $type == "EXPORT SAMPLE") {
            $seqno = sprintf('%04d', $seqno + 1);
        } else {
            $seqno = sprintf('%03d', $seqno + 1);
        }


        $count = $count + 1;
        $y = date('Y');
        $nxt = date('y', strtotime('+1 year'));
        if ($page_module == "salesinvoice") {
            $out_put[0] = $seqname . "-" . $y . $nxt . $seqno;
        } else {
            $out_put[0] = $seqname . $seqno;
        }

        $out_put[1] = $count;


        return $out_put;

    }
    /*deepika purpose:to get qoh for product*/
    public function Qohdetails($prdid = null, $orgid = null)
    {
        if ($orgid == '') {
            $orgid = 2;
        }
        $sql = "SELECT sum(qohtbl.available_qoh) as totalqoh from (SELECT
    i_qoh_detail_t.qoh_trx_qty,
    i_reservation_detail_t.reserv_trx_qty,
    (
        i_qoh_detail_t.qoh_trx_qty - i_reservation_detail_t.reserv_trx_qty
    ) AS available_qoh
FROM
    i_qoh_detail_t
LEFT JOIN i_reservation_detail_t ON
    (
        i_reservation_detail_t.product_id = i_qoh_detail_t.product_id
    )
LEFT JOIN m_products_t ON
    (
        m_products_t.product_id = i_qoh_detail_t.product_id
    )
WHERE
    1 = 1 AND m_products_t.product_id = " . $prdid . " and i_qoh_detail_t.organization_id=" . $orgid . "
group by
 i_qoh_detail_t.product_id
ORDER BY
    i_qoh_detail_t.product_id) qohtbl";
        $qohresult = \DB::Select($qoh);
        return $qohresult[0]->totalqoh;
    }



    public function pricelistdetails($pid = null, $pricelistid)
    {
        $prd_data = array();

        if ($pid != '') {
            $plist = \DB::table('i_pricelist_lines_t')->where('pricelist_hdr_id', $pricelistid)->where('product_id', $pid)->get();
            if ($plist->isEmpty()) {
                $unitprice = '0.00';
            } else {
                $unitprice = $plist[0]->unit_price;
            }
            //return $unitprice;
        } else {
            $unitprice = '0.00';
        }

        return $unitprice;
    }
    public function customerpricelist($id)
    {

        if ($id != '') {
            $plist = \DB::table('m_customers_t')->where('customer_id', $id)->get();
            if ($plist->isNotEmpty()) {  //dd($plist);

                return $plist[0]->pricelist_id;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    /*Karthigaa Purpose For Supplier Based Pricelist load*/
    public function supplierpricelist($id)
    {
        if ($id != '') {
            $plist = \DB::table('m_supplier_t')->where('supplier_id', $id)->get();

            if ($plist->isNotEmpty()) {
                $suppdatas1['supplier_id'] = $plist[0]->supplier_id;
                $suppdatas1['supplier_type_id'] = $plist[0]->supplier_type_id;
                $suppdatas1['default_payment_terms_id'] = $plist[0]->default_payment_terms_id;
                $suppdatas1['default_payment_method_id'] = $plist[0]->default_payment_method_id;
                $suppdatas1['delivery_terms_id'] = $plist[0]->delivery_terms_id;
                $suppdatas1['insurance_term_id'] = $plist[0]->insurance_term_id;
                $suppdatas1['frieghtterm_id'] = $plist[0]->frieghtterm_id;
                $suppdatas1['frieghtcarriers_id'] = $plist[0]->frieghtcarriers_id;

                //$supsite=\DB::select("select supplier_site_id,supplier_site_name from m_supplier_sites_t where supplier_id='".$suppdatas[0]->supplier_id."' and primary_address='YES'");
                //$supsite=\DB::select("select supplier_site_id,supplier_site_name from m_supplier_sites_t where supplier_id='".$id."'");
                $supsite = \DB::select("select supplier_site_id,supplier_site_name from m_supplier_sites_t where supplier_id='" . $id . "' AND primary_address = 'Yes'");
                if (!empty($supsite))
                    $suppdatas1['supplier_site_id'] = $supsite[0]->supplier_site_id;
                else
                    $suppdatas1['supplier_site_id'] = 0;

                $suppdatas1['price_list'] = $plist[0]->default_pricelist_id;
                // dd($suppdatas1);

                return $suppdatas1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    function getLocationaddress()
    {
        $sql = array();
        $location = \Session::get('location');
        //dd($location);
        $sql = \DB::SELECT('select * from m_location_t where location_id=' . $location . '');
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }
    function getCompany()
    {
        $sql = array();
        $company = \Session::get('location');
        $sql = \DB::SELECT("SELECT company_id,company_name FROM `m_company_t` WHERE `company_id`=" . $company . "");
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }
    function getCity($id = null)
    {
        $sql = array();

        $sql = \DB::SELECT('select city_id,city_name from m_cities_t where city_id=' . $id . '');
        if (!empty($sql)) {
            return $sql[0]->city_name;
        } else {
            return 0;
        }

    }
    function getState($id = null)
    {
        $sql = array();

        $sql = \DB::SELECT('select state_id,state_name from m_states_t where state_id=' . $id . '');
        if (!empty($sql)) {
            return $sql[0]->state_name;
        } else {
            return 0;
        }

    }
    function getCountry($id = null)
    {
        $sql = array();
        $sql = \DB::SELECT('select country_id,country_name from m_countries_t where country_id =' . $id . '');
        if (!empty($sql)) {
            return $sql[0]->country_name;
        } else {
            return 0;
        }
    }

    function getSuppliersite($id = null)
    {
        $sql = array();
        $sql = \DB::SELECT('select * from m_supplier_sites_t where supplier_id=' . $id . '');

        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }
    }

    function getSupplier($id = null)
    {
        $sql = array();
        $sql = \DB::SELECT('select * from m_supplier_t where supplier_id=' . $id . '');
        if (!empty($sql)) {
            return $sql;
        } else {
            return 0;
        }

    }
    public function getpriceproduct($id = null, $pid = null)
    {
        // dd("dfg");
        $c = $_GET['condition'];

        $emp_id = \Session::get('emp_id');
        //dd("fddf");	 
        $con = \DB::select("SELECT menus,menus1,menus2 FROM `a_product_menu_access_t` where user_id='$emp_id' and process_id='$c'");

        if ($con) {
            $group = json_decode($con[0]->menus);

            $cat = json_decode($con[0]->menus1);
            $sub_cat = json_decode($con[0]->menus2);
            $condition = " and m_products_t.product_status='APPROVED' ";

        } else {
            $condition = " and m_products_t.product_status='APPROVED' and m_products_t.product_group_id=''";
        }

        if ($con) {


            //$d=\DB::select("select * from i_pricelist_lines_t where pricelist_hdr_id='$id' group by product_id");

            //dd($d);
            $productid = DB::table('i_pricelist_lines_t')->groupBy('i_pricelist_lines_t.product_id')->join('m_products_t', 'm_products_t.product_id', '=', 'i_pricelist_lines_t.product_id')->select('i_pricelist_lines_t.product_id', 'm_products_t.concatenated_product')
                ->where('i_pricelist_lines_t.pricelist_hdr_id', $id)->whereIn('m_products_t.product_group_id', $group)
                ->whereIn('m_products_t.product_category_id', $cat)->whereIn('m_products_t.product_subcategory_id', $sub_cat)->where('m_products_t.product_status', 'APPROVED')->get();
            //dd(($select_query));
        } else {
            $productid = \DB::select("SELECT  m_products_t.concatenated_product,m_products_t.product_id FROM i_pricelist_lines_t JOIN m_products_t ON i_pricelist_lines_t.product_id=m_products_t.product_id WHERE i_pricelist_lines_t.pricelist_hdr_id='$id'
	and i_pricelist_lines_t.active='Yes' $condition GROUP BY m_products_t.product_id");

        }
        //  dd($productid);
        $this->data['productid'] = '<option value="">-- Please Select --</option>';

        foreach ($productid as $key => $value) {
            if ($value->product_id == $pid)
                $select = "selected";
            else
                $select = '';

            $this->data['productid'] .= "<option value=" . $value->product_id . " " . $select . ">" . $value->concatenated_product . "</option>";
        }
        //dd("Ddd");
        return $this->data['productid'];    //dd($this->data['productid']);
    }

    public function productgroupid()
    {

        $module_name = $_GET['module_name'];

        $query = DB::table('m_product_setting_t')->where('module_name', $module_name)->get();

        $group_id = $query[0]->product_group_id;
        return $group_id;
    }
    public function jCombodiscount($table, $option, $display, $selects)
    {
        $compy = \Session::get('companyid');
        $display_name = explode("|", $display);
        $dis = "";
        foreach ($display_name as $value) {
            $dis .= $value . "," . "' - ', ";
        }

        $dis_name = rtrim($dis, ",' - ',");
        //dd("select DISTINCT CONCAT(".$dis_name.") as name,display_name ,".$option." from ".$table);
        $select = \DB::select("select DISTINCT CONCAT(" . $dis_name . ") as name,default_discount_amount ," . $option . " from " . $table . " where company_id=" . $compy . " and active='Yes'");
        $html = "<option value=''>-- Please Select --</option>";
        foreach ($select as $val) {
            if ($val->$option == $selects) {
                $html .= "<option value='" . $val->$option . "' data-display='" . $val->default_discount_amount . "' selected='selected'>" . $val->name . "</option>";
            } else {
                $html .= "<option value='" . $val->$option . "'  data-display='" . $val->default_discount_amount . "' >" . $val->name . "</option>";
            }
        }
        return $html;
    }

    public function Approvaldatacheck($module = null, $total = null)
    {
        $wh = "";

        

        if (isset($total)) {

            $total = " AND $total";
        } else {

            $total = "";
        }
           //dd($total); 

        if ($module == 'poquote') {
            $wh .= " and m_approvalsettings_hdr_t.module_name='Purchase Quotation Approval' $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
        } else if ($module == 'poinvoice') {
            $wh .= " and m_approvalsettings_hdr_t.module_name='Purchase Invoice Approval' $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";

        } else if ($module == 'poorder') {
            $wh .= " and m_approvalsettings_hdr_t.module_name='Purchaseorder Approval' $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
        } else if ($module == 'soinvoice') {
            $wh .= " and m_approvalsettings_hdr_t.module_name='Sales Invoice Approval' $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
        } else if ($module == 'soquote') {
            $wh .= " and m_approvalsettings_hdr_t.module_name='Sales Quote Approval' $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
        } else if ($module == 'soorder') {
            $wh .= " and m_approvalsettings_hdr_t.module_name='Sales Order Approval' $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
        } else if ($module == 'product') {
            $wh .= " and m_approvalsettings_hdr_t.module_name='Product Approval' $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
        } else if ($module == 'supplier') {
            $wh .= " and m_approvalsettings_hdr_t.module_name='Supplier Approval' $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
        } else if ($module == 'materialbom') {
            $wh .= " and m_approvalsettings_hdr_t.module_name='BOM Approval' $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
        } else if ($module == 'schemes') {
            $wh .= " and m_approvalsettings_hdr_t.module_name='Schemes Approval - Level 1' $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
        } else if ($module == 'schemeslevel2approval') {
            $wh .= " and m_approvalsettings_hdr_t.module_name='Schemes Approval - Level 2' $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
        } else if ($module == 'customers') {
            $wh .= " and m_approvalsettings_hdr_t.module_name='Customer Approval' $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
        } else if ($module == 'purchaseprice') {
            $wh .= " and m_approvalsettings_hdr_t.module_name='Purchase Pricelist Approval' $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
        } else if ($module == 'salesprice') {
            $wh .= " and m_approvalsettings_hdr_t.module_name='Sales Pricelist Approval' $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
        } else if ($module == 'batch_conversion') {
            $wh .= " and m_approvalsettings_hdr_t.module_name='Batch Conversion Approval' $total between m_approvalsettings_line_t.value_from and m_approvalsettings_line_t.value_to";
        }

        $sql_chk = \DB::SELECT("select m_approvalsettings_hdr_t.*,m_approvalsettings_line_t.* from m_approvalsettings_hdr_t left join m_approvalsettings_line_t on (m_approvalsettings_hdr_t.approvalsettings_hdr_id=m_approvalsettings_line_t.approvalsettings_hdr_id) Where 1=1 $wh and m_approvalsettings_line_t.approve_required='Yes'");


        if (count($sql_chk) > 0) {
            foreach ($sql_chk as $val) {
                $data[] = $val->approver_id;
            }

            return json_encode($data);
        } else {
            return 0;
        }


    }
    public function timeoutcheck()
    {
        if (isset($_GET['popup'])) {
            setcookie("popup", "ok");
        } else {


            if (isset($_COOKIE["start"]) and $_COOKIE["start"] < time()) {
                setcookie("popup", "yes");
                if (isset($_COOKIE["popup"]) and $_COOKIE["popup"] == "yes") {
                    $return = "2";
                } else if (isset($_COOKIE["popup"]) and $_COOKIE["popup"] == "ok") {
                    $return = "3";
                } else {
                    $return = \Session::get('location');
                }
                return $return;
            } else {
                setcookie("popup", "no");
                $return['time'] = \Session::get('session_time');
                $return['return'] = "4";
                return $return;
            }
        }
    }
    /*deepika purpose:to save audit log details*/
    public function auditlog($id = null, $module = null, $action = null, $note = null, $table_name = null)
    {

        $data['ipaddress'] = $_SERVER['REMOTE_ADDR'];
        $data['primary_id'] = $id;
        $data['module'] = $module;
        $data['action'] = $action;
        $data['note'] = json_encode($note);
        $data['table_name'] = $table_name;
        $data['company_id'] = \Session::get('companyid');
        $data['location_id'] = \Session::get('loc_id');
        $data['organization_id'] = \Session::get('organization');
        $data['created_by'] = \Session::get('id');
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['last_updated_by'] = \Session::get('id');
        $data['last_updated_at'] = date('Y-m-d H:i:s');
        \DB::table('tb_logs')->insert($data);
    }
    /*end*/
    public function leavemailsend($id = null)
    {

        $this->data['leave_data'] = $leave_data = DB::table('hr_leaves_t')
            ->leftjoin('hr_employee_t as emp', 'emp.employee_id', '=', 'hr_leaves_t.employee_id')
            ->leftjoin('hr_employee_t as report', 'report.employee_id', '=', 'hr_leaves_t.forwarded_id')
            ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'hr_leaves_t.leave_type')
            ->leftjoin('a_lookuplines_t as leavemode', 'leavemode.lookuplines_id', '=', 'hr_leaves_t.leave_mode')
            ->leftjoin('a_lookuplines_t as leavesession', 'leavesession.lookuplines_id', '=', 'hr_leaves_t.session')
            ->select('hr_leaves_t.forwarded_id', 'hr_leaves_t.leave_reason', 'leavesession.lookup_meaning as session', 'hr_leaves_t.no_of_days', 'report.email as report_email', 'hr_leaves_t.leave_status', 'hr_leaves_t.end_date', 'hr_leaves_t.start_date', 'hr_leaves_t.approval_reason', 'hr_leaves_t.alloted_days', 'hr_leaves_t.start_date_time', 'hr_leaves_t.end_date_time', 'hr_leaves_t.no_of_hrs', 'hr_leaves_t.alloted_hrs', 'hr_leaves_t.od_start_date', 'hr_leaves_t.od_end_date', 'hr_leaves_t.od_no_of_days', 'hr_leaves_t.od_alloted_days', 'emp.employee_number', 'emp.first_name', 'report.employee_number as report_number', 'report.first_name as report_name', 'a_lookuplines_t.lookup_meaning', 'leavemode.lookup_meaning as leave_mode', 'hr_leaves_t.leave_id')
            ->where('leave_id', $id)->get();
        $for_id = explode(",", $leave_data[0]->forwarded_id);
        $leave_type_mail = $leave_data[0]->lookup_meaning;
        Session::put('leave_type_mail', $leave_type_mail);
        //  dd($for_id);
        $emp_mail = DB::table('hr_employee_t')->WhereIn('employee_id', $for_id)->select('email', 'employee_id')->get();
        if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
            Config::set('mail.username', \Session::get('user_email'));
            Config::set('mail.password', \Session::get('user_password'));
        }
        foreach ($emp_mail as $key => $val) {
            if ($val->email != '') {
                $emp_mail_id = $val->email;
                //dd("dsds");
                $d = urlencode(1);
                // dd($d);
                $this->data['emp_id'] = $val->employee_id;
                $this->data['status'] = "request";
                \Mail::send('leaves.mail_details', $this->data, function ($message) use ($emp_mail_id) {
                    //   dd($this->data['leave_data']);
                    $message->to($emp_mail_id);
                    //   $message->cc('Vijay.Augustin@modine.com');
                    //$message->cc('aspire@jrkresearch.com');
                    if (!empty(\Session::get('user_email'))) {
                        $message->from(\Session::get('user_email'));
                    } else {
                        $message->from(\Config::get('mail.username'));
                    }
                    //$message->from(Config::get('mail.username'));
                    if (!empty(Session::get('leave_type_mail'))) {
                        $leave_type = Session::get('leave_type_mail');
                    } else {
                        $leave_type = "Leave";
                    }
                    // dd($leave_type);
                    $message->subject($leave_type . " Initiated");
                });
            }
        }//dd("jn");
        return 1;

        //return view('leaves.approve');
    }

    public function leaveapprovmailsend($id = null)
    {

        $this->data['leave_data'] = $leave_data = DB::table('hr_leaves_t')
            ->leftjoin('hr_employee_t as emp', 'emp.employee_id', '=', 'hr_leaves_t.employee_id')
            ->leftjoin('hr_employee_t as report', 'report.employee_id', '=', 'hr_leaves_t.forwarded_id')
            ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'hr_leaves_t.leave_type')
            ->leftjoin('a_lookuplines_t as leavemode', 'leavemode.lookuplines_id', '=', 'hr_leaves_t.leave_mode')
            ->leftjoin('a_lookuplines_t as leavesession', 'leavesession.lookuplines_id', '=', 'hr_leaves_t.session')
            ->select('hr_leaves_t.employee_id', 'hr_leaves_t.forwarded_id', 'hr_leaves_t.leave_reason', 'leavesession.lookup_meaning as session', 'hr_leaves_t.no_of_days', 'report.email as report_email', 'hr_leaves_t.leave_status', 'hr_leaves_t.end_date', 'hr_leaves_t.start_date', 'hr_leaves_t.approval_reason', 'hr_leaves_t.alloted_days', 'hr_leaves_t.start_date_time', 'hr_leaves_t.end_date_time', 'hr_leaves_t.no_of_hrs', 'hr_leaves_t.alloted_hrs', 'hr_leaves_t.od_start_date', 'hr_leaves_t.od_end_date', 'hr_leaves_t.od_no_of_days', 'hr_leaves_t.od_alloted_days', 'emp.employee_number', 'emp.first_name', 'report.employee_number as report_number', 'report.first_name as report_name', 'a_lookuplines_t.lookup_meaning', 'leavemode.lookup_meaning as leave_mode', 'hr_leaves_t.leave_id')
            ->where('leave_id', $id)->get();
        //  $for_id=explode(",",$leave_data[0]->forwarded_id);
        $emp_id = $leave_data[0]->employee_id;
        $for_rpt_id = $leave_data[0]->forwarded_id;
        $leave_type_mail = $leave_data[0]->lookup_meaning;
        Session::put('leave_type_mail', $leave_type_mail);
        //  dd($for_id);
        // dd($this->data['leave_data']);
        //  $emp_mail=DB::table('hr_employee_t')->WhereIn('employee_id',$for_id)->select('email','employee_id')->get();
        $emp_mail = DB::table('hr_employee_t')->Where('employee_id', $emp_id)->select('email', 'employee_id')->get();
        $emp_rpt_mail = DB::table('hr_employee_t')->Where('employee_id', $for_rpt_id)->select('email', 'employee_id')->get();
        Session::put('reporting_manager_email', $emp_rpt_mail[0]->email);
        if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
            Config::set('mail.username', \Session::get('user_email'));
            Config::set('mail.password', \Session::get('user_password'));
        }
        foreach ($emp_mail as $key => $val) {
            if ($val->email != '') {
                $emp_mail_id = $val->email;
                //dd("dsds");
                $d = urlencode(1);
                // dd($d);
                $this->data['emp_id'] = $val->employee_id;
                $this->data['status'] = "approve";
                //dd($emp_mail_id);
                \Mail::send('leaves.mail_details', $this->data, function ($message) use ($emp_mail_id) {
                    //   dd($this->data['leave_data']);
                    $message->to($emp_mail_id);
                    if (!empty(Session::get('reporting_manager_email'))) {
                        $emp_rpt_email = Session::get('reporting_manager_email');
                    } else {
                        $emp_rpt_email = 'aspire@jrkresearch.com';
                    }
                    // dd($emp_rpt_email);
                    // $message->cc($emp_rpt_email);  
                    //$message->cc('rajesh_r@jrkresearch.com');
                    if (!empty(\Session::get('user_email'))) {
                        $message->from(\Session::get('user_email'));
                    } else {
                        $message->from(\Config::get('mail.username'));
                    }
                    //$message->from(Config::get('mail.username'));
                    if (!empty(Session::get('leave_type_mail'))) {
                        $leave_type = Session::get('leave_type_mail');
                    } else {
                        $leave_type = "Leave";
                    }
                    //dd($leave_type);
                    $message->subject($leave_type . " Approved");
                });
            }
        }//dd("jn");
        return 1;

        //return view('leaves.approve');
    }


    public function compoffmailsend($id = null)
    {

        $this->data['leave_data'] = $leave_data = DB::table('hr_compoff_t')
            ->leftjoin('hr_employee_t as emp', 'emp.employee_id', '=', 'hr_compoff_t.employee_id')
            ->leftjoin('hr_employee_t as report', 'report.employee_id', '=', 'hr_compoff_t.forwarded_id')
            ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'hr_compoff_t.leave_type')
            ->leftjoin('a_lookuplines_t as leavemode', 'leavemode.lookuplines_id', '=', 'hr_compoff_t.leave_mode')
            ->select('hr_compoff_t.employee_id', 'emp.reporting_manager', 'hr_compoff_t.forwarded_id', 'hr_compoff_t.leave_reason', 'hr_compoff_t.no_of_days', 'report.email as report_email', 'hr_compoff_t.leave_status', 'hr_compoff_t.end_date', 'hr_compoff_t.start_date', 'hr_compoff_t.approval_reason', 'hr_compoff_t.alloted_days', 'emp.employee_number', 'emp.first_name', 'report.employee_number as report_number', 'report.first_name as report_name', 'a_lookuplines_t.lookup_meaning', 'leavemode.lookup_meaning as leave_mode', 'hr_compoff_t.compoff_id')
            ->where('compoff_id', $id)->get();
        $for_id = explode(",", $leave_data[0]->forwarded_id);
        $for_rpt_id = $leave_data[0]->reporting_manager;
        //  dd($for_id);
        $emp_mail = DB::table('hr_employee_t')->WhereIn('employee_id', $for_id)->select('email', 'employee_id')->get();
        $emp_rpt_mail = DB::table('hr_employee_t')->Where('employee_id', $for_rpt_id)->select('email', 'employee_id')->get();
        Session::put('reporting_manager_email', $emp_rpt_mail[0]->email);
        if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
            Config::set('mail.username', \Session::get('user_email'));
            Config::set('mail.password', \Session::get('user_password'));
        }
        foreach ($emp_mail as $key => $val) {
            if ($val->email != '') {
                $emp_mail_id = $val->email;
                //dd("dsds");
                $d = urlencode(1);
                // dd($d);
                $this->data['emp_id'] = $val->employee_id;
                $this->data['status'] = "request";
                \Mail::send('compoffrequest.mail_details', $this->data, function ($message) use ($emp_mail_id) {
                    //   dd($this->data['leave_data']);
                    $message->to($emp_mail_id);
                    if (!empty(Session::get('reporting_manager_email'))) {
                        $emp_rpt_email = Session::get('reporting_manager_email');
                    } else {
                        $emp_rpt_email = 'aspire@jrkresearch.com';
                    }
                    $message->cc($emp_rpt_email);
                    $message->cc('rajesh_r@jrkresearch.com');
                    $message->cc('munusamy_s@jrkresearch.com');
                    $message->cc('payroll@jrkresearch.com');
                    if (!empty(\Session::get('user_email'))) {
                        $message->from(\Session::get('user_email'));
                    } else {
                        $message->from(\Config::get('mail.username'));
                    }
                    //$message->from(Config::get('mail.username'));
                    $message->subject("Comp-Off Request Initiated");
                });
            }
        }//dd("jn");
        return 1;

        //return view('leaves.approve');
    }

    public function compoffapprovmailsend($id = null)
    {

        $this->data['leave_data'] = $leave_data = DB::table('hr_compoff_t')
            ->leftjoin('hr_employee_t as emp', 'emp.employee_id', '=', 'hr_compoff_t.employee_id')
            ->leftjoin('hr_employee_t as report', 'report.employee_id', '=', 'hr_compoff_t.forwarded_id')
            ->leftjoin('a_lookuplines_t', 'a_lookuplines_t.lookuplines_id', '=', 'hr_compoff_t.leave_type')
            ->leftjoin('a_lookuplines_t as leavemode', 'leavemode.lookuplines_id', '=', 'hr_compoff_t.leave_mode')
            ->select('hr_compoff_t.employee_id', 'emp.reporting_manager', 'hr_compoff_t.forwarded_id', 'hr_compoff_t.leave_reason', 'hr_compoff_t.no_of_days', 'report.email as report_email', 'hr_compoff_t.leave_status', 'hr_compoff_t.end_date', 'hr_compoff_t.start_date', 'hr_compoff_t.approval_reason', 'hr_compoff_t.alloted_days', 'emp.employee_number', 'emp.first_name', 'report.employee_number as report_number', 'report.first_name as report_name', 'a_lookuplines_t.lookup_meaning', 'leavemode.lookup_meaning as leave_mode', 'hr_compoff_t.compoff_id')
            ->where('compoff_id', $id)->get();
        //  $for_id=explode(",",$leave_data[0]->forwarded_id);
        $emp_id = $leave_data[0]->employee_id;
        $for_rpt_id = $leave_data[0]->reporting_manager;
        //  dd($for_id);
        //  $emp_mail=DB::table('hr_employee_t')->WhereIn('employee_id',$for_id)->select('email','employee_id')->get();
        $emp_mail = DB::table('hr_employee_t')->Where('employee_id', $emp_id)->select('email', 'employee_id')->get();
        $emp_rpt_mail = DB::table('hr_employee_t')->Where('employee_id', $for_rpt_id)->select('email', 'employee_id')->get();
        Session::put('reporting_manager_email', $emp_rpt_mail[0]->email);
        if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
            Config::set('mail.username', \Session::get('user_email'));
            Config::set('mail.password', \Session::get('user_password'));
        }
        foreach ($emp_mail as $key => $val) {
            if ($val->email != '') {
                $emp_mail_id = $val->email;
                //dd("dsds");
                $d = urlencode(1);
                // dd($d);
                $this->data['emp_id'] = $val->employee_id;
                $this->data['status'] = "approve";
                \Mail::send('compoffrequest.mail_details', $this->data, function ($message) use ($emp_mail_id) {
                    //   dd($this->data['leave_data']);
                    $message->to($emp_mail_id);
                    if (!empty(Session::get('reporting_manager_email'))) {
                        $emp_rpt_email = Session::get('reporting_manager_email');
                    } else {
                        $emp_rpt_email = 'aspire@jrkresearch.com';
                    }
                    $message->cc($emp_rpt_email);
                    $message->cc('rajesh_r@jrkresearch.com');
                    $message->cc('payroll@jrkresearch.com');
                    if (!empty(\Session::get('user_email'))) {
                        $message->from(\Session::get('user_email'));
                    } else {
                        $message->from(\Config::get('mail.username'));
                    }
                    //$message->from(Config::get('mail.username'));
                    $message->subject("Comp-Off Request Reg.");
                });
            }
        }//dd("jn");
        return 1;

        //return view('leaves.approve');
    }

    public function pmmailsend($id = null)
    {

        $this->data['pm_data'] = $pm_data = DB::table('machine_pm_detail_t')
            ->leftjoin('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'machine_pm_detail_t.machine_id')
            ->leftjoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'machine_pm_detail_t.department_id')
            ->select('w_machine_hdr_t.machine_code', 'w_machine_hdr_t.machine_name', 'machine_pm_detail_t.pm_no', 'machine_pm_detail_t.actual_pm_date', 'machine_pm_detail_t.initiate_date', 'machine_pm_detail_t.user_clearance_by', 'm_department_lines_t.sub_department_name')
            ->where('initiate_pm_id', $id)->get();
        $clearance = trim($pm_data[0]->user_clearance_by, '[');
        $clearance = rtrim($clearance, ']');
        $for_id = str_replace('"', "", $clearance);
        //  dd($for_id);
        $emp_mail = DB::Select("select email,employee_id from hr_employee_t where employee_id in($for_id) ");
        if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
            Config::set('mail.username', \Session::get('user_email'));
            Config::set('mail.password', \Session::get('user_password'));
        }
        foreach ($emp_mail as $key => $val) {
            if ($val->email != '') {
                $emp_mail_id = $val->email;
                // dd($emp_mail_id);
                //dd("dsds");
                $d = urlencode(1);
                // dd($d);
                $this->data['emp_id'] = $val->employee_id;
                $this->data['status'] = "request";
                \Mail::send('initiatepm.mail_details', $this->data, function ($message) use ($emp_mail_id) {
                    $message->to($emp_mail_id);
                    //   $message->cc('Vijay.Augustin@modine.com');
                    //$message->cc('aspire@jrkresearch.com');
                    $message->cc('gayathri_rajagopal@jrkresearch.com');
                    if (!empty(\Session::get('user_email'))) {
                        $message->from(\Session::get('user_email'));
                    } else {
                        $message->from(\Config::get('mail.username'));
                    }
                    $message->subject("PM Initiated");
                });
            }
        }//dd("jn");
        return 1;

    }

    public function pmclearancemailsend($id = null)
    {

        $this->data['pm_data'] = $pm_data = DB::table('machine_pm_detail_t')
            ->leftjoin('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'machine_pm_detail_t.machine_id')
            ->leftjoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'machine_pm_detail_t.department_id')
            ->select('w_machine_hdr_t.machine_code', 'w_machine_hdr_t.machine_name', 'machine_pm_detail_t.pm_no', 'machine_pm_detail_t.actual_pm_date', 'machine_pm_detail_t.initiate_date', 'machine_pm_detail_t.postponed_date', 'machine_pm_detail_t.shift_timing', 'machine_pm_detail_t.user_clearance_by', 'm_department_lines_t.sub_department_name')
            ->where('initiate_pm_id', $id)->get();
        if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
            Config::set('mail.username', \Session::get('user_email'));
            Config::set('mail.password', \Session::get('user_password'));
        }

        $emp_mail_id = "maintenance@jrkresearch.com";
        $d = urlencode(1);
        // dd($d);
        $this->data['status'] = "request";
        \Mail::send('initiatepm.pmclearance_mail', $this->data, function ($message) use ($emp_mail_id) {
            $message->to($emp_mail_id);
            //   $message->cc('Vijay.Augustin@modine.com');
            //$message->cc('aspire@jrkresearch.com');
            //$message->cc('gayathri_rajagopal@jrkresearch.com');
            if (!empty(\Session::get('user_email'))) {
                $message->from(\Session::get('user_email'));
            } else {
                $message->from(\Config::get('mail.username'));
            }
            $message->subject("PM User Clearanced");
        });

        return 1;

    }

    public function pmagencyallocationmailsend($id = null)
    {

        $this->data['agency_data'] = $pm_data = DB::table('machine_pm_detail_t')
            ->leftjoin('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'machine_pm_detail_t.machine_id')
            ->leftjoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'machine_pm_detail_t.department_id')
            ->leftjoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 'machine_pm_detail_t.allocated_agency')
            ->leftjoin('ma_agency_t', 'ma_agency_t.agency_id', '=', 'machine_pm_detail_t.allocated_agency')
            ->select('w_machine_hdr_t.machine_code', 'w_machine_hdr_t.machine_name', 'machine_pm_detail_t.pm_no', 'machine_pm_detail_t.actual_pm_date', 'machine_pm_detail_t.initiate_date', 'machine_pm_detail_t.user_clearance_by', 'machine_pm_detail_t.allocated_on', 'machine_pm_detail_t.allocation_type', 'hr_employee_t.first_name', 'ma_agency_t.agency_name', 'm_department_lines_t.sub_department_name')
            ->where('initiate_pm_id', $id)->get();
        $clearance = trim($pm_data[0]->user_clearance_by, '[');
        $clearance = rtrim($clearance, ']');
        $for_id = str_replace('"', "", $clearance);
        //  dd($for_id);
        $emp_mail = DB::Select("select email,employee_id from hr_employee_t where employee_id in($for_id) ");
        if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
            Config::set('mail.username', \Session::get('user_email'));
            Config::set('mail.password', \Session::get('user_password'));
        }
        foreach ($emp_mail as $key => $val) {
            if ($val->email != '') {
                $emp_mail_id = $val->email;
                // dd($emp_mail_id);
                //dd("dsds");
                $d = urlencode(1);
                // dd($d);
                $this->data['emp_id'] = $val->employee_id;
                $this->data['status'] = "request";
                \Mail::send('initiatepm.agency_details', $this->data, function ($message) use ($emp_mail_id) {
                    $message->to($emp_mail_id);
                    //   $message->cc('Vijay.Augustin@modine.com');
                    //$message->cc('aspire@jrkresearch.com');
                    //$message->cc('gayathri_rajagopal@jrkresearch.com');
                    if (!empty(\Session::get('user_email'))) {
                        $message->from(\Session::get('user_email'));
                    } else {
                        $message->from(\Config::get('mail.username'));
                    }
                    $message->subject("PM Agency/Engineer Allocated");
                });
            }
        }//dd("jn");
        return 1;

    }

    public function pmcheckmailsend($id = null)
    {

        $this->data['pm_data'] = $pm_data = DB::table('machine_pm_detail_t')
            ->leftjoin('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'machine_pm_detail_t.machine_id')
            ->leftjoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'machine_pm_detail_t.department_id')
            ->leftjoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 'machine_pm_detail_t.allocated_agency')
            ->leftjoin('ma_agency_t', 'ma_agency_t.agency_id', '=', 'machine_pm_detail_t.allocated_agency')
            ->select('w_machine_hdr_t.machine_code', 'w_machine_hdr_t.machine_name', 'machine_pm_detail_t.pm_no', 'machine_pm_detail_t.actual_pm_date', 'machine_pm_detail_t.initiate_date', 'machine_pm_detail_t.user_clearance_by', 'm_department_lines_t.sub_department_name', 'hr_employee_t.first_name', 'ma_agency_t.agency_name', 'machine_pm_detail_t.allocation_type')
            ->where('initiate_pm_id', $id)->get();
        $clearance = trim($pm_data[0]->user_clearance_by, '[');
        $clearance = rtrim($clearance, ']');
        $for_id = str_replace('"', "", $clearance);
        //  dd($for_id);
        $emp_mail = DB::Select("select email,employee_id from hr_employee_t where employee_id in($for_id) ");
        if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
            Config::set('mail.username', \Session::get('user_email'));
            Config::set('mail.password', \Session::get('user_password'));
        }
        foreach ($emp_mail as $key => $val) {
            if ($val->email != '') {
                $emp_mail_id = $val->email;
                // dd($emp_mail_id);
                //dd("dsds");
                $d = urlencode(1);
                // dd($d);
                $this->data['emp_id'] = $val->employee_id;
                $this->data['status'] = "request";
                \Mail::send('initiatepm.pmcheck_mail_details', $this->data, function ($message) use ($emp_mail_id) {
                    $message->to($emp_mail_id);
                    //   $message->cc('Vijay.Augustin@modine.com');
                    //$message->cc('aspire@jrkresearch.com');
                    //$message->cc('gayathri_rajagopal@jrkresearch.com');
                    if (!empty(\Session::get('user_email'))) {
                        $message->from(\Session::get('user_email'));
                    } else {
                        $message->from(\Config::get('mail.username'));
                    }
                    $message->subject("PM Checklist Checked");
                });
            }
        }//dd("jn");
        return 1;

    }

    public function pmcheckapprovemailsend($id = null)
    {

        $this->data['pm_data'] = $pm_data = DB::table('machine_pm_detail_t')
            ->leftjoin('w_machine_hdr_t', 'w_machine_hdr_t.machine_hdr_id', '=', 'machine_pm_detail_t.machine_id')
            ->leftjoin('m_department_lines_t', 'm_department_lines_t.department_line_id', '=', 'machine_pm_detail_t.department_id')
            ->leftjoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 'machine_pm_detail_t.allocated_agency')
            ->leftjoin('ma_agency_t', 'ma_agency_t.agency_id', '=', 'machine_pm_detail_t.allocated_agency')
            ->select('w_machine_hdr_t.machine_code', 'w_machine_hdr_t.machine_name', 'machine_pm_detail_t.pm_no', 'machine_pm_detail_t.actual_pm_date', 'machine_pm_detail_t.initiate_date', 'machine_pm_detail_t.postponed_date', 'machine_pm_detail_t.shift_timing', 'machine_pm_detail_t.user_clearance_by', 'm_department_lines_t.sub_department_name', 'hr_employee_t.first_name', 'ma_agency_t.agency_name', 'machine_pm_detail_t.allocation_type')
            ->where('initiate_pm_id', $id)->get();
        if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
            Config::set('mail.username', \Session::get('user_email'));
            Config::set('mail.password', \Session::get('user_password'));
        }

        $emp_mail_id = "maintenance@jrkresearch.com";
        $d = urlencode(1);
        // dd($d);
        $this->data['status'] = "request";
        \Mail::send('initiatepm.pmcheckapprove_mail', $this->data, function ($message) use ($emp_mail_id) {
            $message->to($emp_mail_id);
            //   $message->cc('Vijay.Augustin@modine.com');
            //$message->cc('aspire@jrkresearch.com');
            $message->cc('gayathri_rajagopal@jrkresearch.com');
            if (!empty(\Session::get('user_email'))) {
                $message->from(\Session::get('user_email'));
            } else {
                $message->from(\Config::get('mail.username'));
            }
            $message->subject("Checklist Approved - PM Completed");
        });

        return 1;

    }

    public function salesinquirymailsend($id = null)
    {

        $this->data['salesinquiry_data'] = $salesinquiry_data = DB::table('s_inquiry_hdr_t')
            ->leftjoin('s_inquiry_lines_t', 's_inquiry_hdr_t.so_inquiry_hdr_id', '=', 's_inquiry_lines_t.so_inquiry_hdr_id')
            ->leftjoin('m_customers_t', 'm_customers_t.customer_id', '=', 's_inquiry_hdr_t.customerid')
            ->leftjoin('m_products_t', 'm_products_t.product_id', '=', 's_inquiry_lines_t.product_id')
            ->leftjoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 's_inquiry_lines_t.uom_code_id')
            ->select('s_inquiry_hdr_t.so_inquiry_hdr_id', 's_inquiry_hdr_t.inquiry_no', 's_inquiry_hdr_t.inquiry_date', 's_inquiry_hdr_t.inquiry_type', 's_inquiry_hdr_t.reference_number', 's_inquiry_hdr_t.source', 's_inquiry_hdr_t.remarks', 's_inquiry_hdr_t.inquiry_status', 's_inquiry_hdr_t.tittle_of_work', 's_inquiry_hdr_t.order_status', 's_inquiry_lines_t.product_description', 's_inquiry_lines_t.required_qty', 's_inquiry_lines_t.need_by_date', 'm_customers_t.customer_name', 'm_products_t.concatenated_product', 'm_uom_codes_t.uom_code')
            ->where('s_inquiry_hdr_t.so_inquiry_hdr_id', $id)->get();
        //dd($this->data['salesinquiry_data']);
        //$emp_mail=DB::Select("select email,employee_id from hr_employee_t where employee_id in(151,38,32,265,12,21,169) ");
        if (!empty(\Session::get('user_email')) && !empty(\Session::get('user_password'))) {
            Config::set('mail.username', \Session::get('user_email'));
            Config::set('mail.password', \Session::get('user_password'));
        }
        $emp_mail_id = "drabbas@jrkresearch.com";
        $d = urlencode(1);
        // dd($d);
        $this->data['status'] = "request";
        \Mail::send('salesinquiry.mail_details', $this->data, function ($message) use ($emp_mail_id) {
            $message->to($emp_mail_id);
            $message->to('aruna_v@jrkresearch.com');
            $message->to('hemashree_k@jrkresearch.com');
            $message->to('cheran_senguttuvan@jrkresearch.com');
            $message->to('uma_p@jrkresearch.com');
            $message->to('purchase_pm@jrkresearch.com');
            $message->to('purchase_rm@jrkresearch.com');
            $message->to('operations@jrkresearch.com');
            $message->cc('gayathri_rajagopal@jrkresearch.com');
            $message->cc('logistics@jrkresearch.com');
            //$message->cc('aspire@jrkresearch.com');
            if (!empty(\Session::get('user_email'))) {
                $message->from(\Session::get('user_email'));
            } else {
                $message->from(\Config::get('mail.username'));
            }
            $message->subject("Sales Projection Qty");
        });
        return 1;

    }



    //Journal Reverse for isac naveen

    public function journalreverse($id = null, $name = null)
    {
        //Journal Hdr Insert 
        $reverse = "REVERSE-" . $name;
        $date = date('Y-m-d');
        $org = \Session::get('organization');
        $loc = \Session::get('location');
        $compy = \Session::get('companyid');
        $journalhdr = \DB::insert("insert into f_journal_entry_t(journal_name,journal_type,journal_date,journal_reference,journal_status,company_id,location_id,organization_id)values('$reverse','REVERSE','$date','$id','REVERSED','$compy','$loc','$org')");
        $jid = DB::getPdo()->lastInsertId();

        //Journal lINES Insert           
//             $accid=$_GET['account'];   
//             $credit=$_GET['credit']; 
//             $debit=$_GET['debit']; 

        //\DB::insert("insert into f_journal_entry_lines_t(journal_entry_id,account_id,debit_amount,credit_amount,company_id,location_id,organization_id)value('$jid','$accid','$credit','','$compy','$loc','$org')");         

        $jlinesid = DB::table('f_journal_entry_lines_t')->where('journal_entry_id', $id)->get();
        //                   dd($jlinesid);
        foreach ($jlinesid as $key => $value) {
            $inv_acc['journal_entry_id'] = $jid;
            $inv_acc['account_id'] = $value->account_id;
            $inv_acc['journal_date'] = $date;
            $inv_acc['debit_amount'] = $value->credit_amount;
            $inv_acc['credit_amount'] = $value->debit_amount;
            $inv_acc['organization_id'] = $org;
            $inv_acc['location_id'] = $loc;
            $inv_acc['company_id'] = $compy;
            \DB::table('f_journal_entry_lines_t')->insert($inv_acc);
        }
    }
    public function getjsoncondition($column, $array)
    {

        $condition = implode(" OR ", array_map(function ($dept) use ($column) {
            $val = '\"' . $dept . '\"';
            return "JSON_CONTAINS($column, '$val')";
        }, $array));

        return $condition;
    }
    public function accyearcondition($column)
    {
        $acc_from_date = \Session::get('griddate');
        $acc_to_date = \Session::get('gridenddate');
        $wha = " and date($column) between '$acc_from_date' and '$acc_to_date'";
        return $wha;
    }
    public function grid_datecheck($tbl_name, $date_col, $status_col)
    {
        $groupname = \Session::get('groupname');
        $grid_date = \Session::get('griddate');
        $gridenddate = \Session::get('gridenddate');
        $compy = \Session::get('companyid');
        $loc = \Session::get('location');
        $wh = '';
        if ($groupname == 'Superadmin' || $groupname == 'Admin') {
            $wh .= " and  $tbl_name.company_id=" . $compy;


        } else {
            $wh .= " and ( ( $tbl_name.$date_col < '$grid_date'  and $tbl_name.$status_col='APPROVED') or  ( $tbl_name.$date_col BETWEEN '$grid_date' and '$gridenddate' ) )  ";
            $wh .= "and  $tbl_name.company_id=" . $compy . " and $tbl_name.location_id=" . $loc;

            //$wh .=" and ( ( $tbl_name.$date_col < '$grid_date'  and $tbl_name.$status_col $operator '$status_value') or  ( $tbl_name.$date_col BETWEEN '$grid_date' and '$gridenddate' ) )  ";
        }
        return $wh;
    }



    public function grid_check($tbl_name, $date_col)
    {

        $groupname = \Session::get('groupname');
        $grid_date = \Session::get('griddate');
        $gridenddate = \Session::get('gridenddate');
        $compy = \Session::get('companyid');
        $loc = \Session::get('location');
        $wh = '';
        if ($groupname == 'Superadmin' || $groupname == 'Admin') {
            $wh .= " and  $tbl_name.company_id=" . $compy;
        } else if ($groupname == '3') {
            $wh .= " and ( ( $tbl_name.$date_col < '$grid_date') or  ( $tbl_name.$date_col BETWEEN '$grid_date' and '$gridenddate' ) )  ";
            //$wh.="and  $tbl_name.company_id=".$compy." and $tbl_name.location_id=".$loc;
            $wh .= "and  $tbl_name.company_id=" . $compy;
        } else {
            $grid_date = '2024-01-01';
            $wh .= " and ( ( $tbl_name.$date_col > '$grid_date') or  ( $tbl_name.$date_col BETWEEN '$grid_date' and '$gridenddate' ) )  ";
            //$wh.="and  $tbl_name.company_id=".$compy." and $tbl_name.location_id=".$loc;
            $wh .= "and  $tbl_name.company_id=" . $compy;
        }
        return $wh;
    }
    public function grid_reportdate_check($tbl_name, $date_col)
    {
        $groupname = \Session::get('groupname');
        $grid_date = \Session::get('griddate');
        $gridenddate = \Session::get('gridenddate');
        $compy = \Session::get('companyid');
        $loc = \Session::get('location');
        $wh = '';

        $wh .= " and ( ( $tbl_name.$date_col < '$grid_date') or  ( $tbl_name.$date_col BETWEEN '$grid_date' and '$gridenddate' ) )  ";


        return $wh;
    }
    public function grid_statuscheck($tbl_name, $date_col, $status_col, $operator, $status_value)
    {
        $groupname = \Session::get('groupname');
        $grid_date = \Session::get('griddate');
        $gridenddate = \Session::get('gridenddate');
        $compy = \Session::get('companyid');
        $loc = \Session::get('location');
        $wh = '';
        if ($groupname == 'Superadmin' || $groupname == 'Admin') {
            $wh .= " and  $tbl_name.company_id=" . $compy;


        } else {

            $wh .= " and ( ( $tbl_name.$date_col < '$grid_date'  and $tbl_name.$status_col $operator $status_value) or  ( $tbl_name.$date_col BETWEEN '$grid_date' and '$gridenddate' ) )  ";
            //$wh.="and  $tbl_name.company_id=".$compy." and $tbl_name.location_id=".$loc;
            $wh .= "and  $tbl_name.company_id=" . $compy;

        }
        return $wh;
    }

    public function brk_gridcheck($tbl_name, $date_col, $tbl_name1, $status_col, $operator, $status_value)
    {

        $grid_date = "2024-04-01";
        $gridenddate = date('Y-m-d');
        $wh = '';

        if ($operator === 'IN' && is_array($status_value)) {
            // Convert array to comma-separated quoted string
            $quotedValues = implode(",", array_map(function ($v) {
                return "'" . addslashes($v) . "'";
            }, $status_value));
            $statusCondition = "$tbl_name1.$status_col IN ($quotedValues)";
        } else {
            // Single value comparison
            $value = is_array($status_value) ? $status_value[0] : $status_value;
            $statusCondition = "$tbl_name1.$status_col $operator '" . addslashes($value) . "'";
        }

        $wh .= "(
        ($tbl_name.$date_col < '$grid_date' AND $statusCondition)
        OR
        ($tbl_name.$date_col BETWEEN '$grid_date' AND '$gridenddate')
    )";

        return $wh;
    }



    // product varient type function
    public function jcomboproduct()
    {
        //$compy=\Session::get('companyid');
        $table = $_GET['table'];
        $type_id = $_GET['type_id'];

        $result = \DB::select("select $table.product_variant_id as val, $table.product_variant_name as option_name FROM `m_products_t` LEFT JOIN m_product_type_t ON
    m_products_t.product_type_id = m_product_type_t.product_type_id left join $table on m_products_t.product_variant_id=$table.product_variant_id where m_product_type_t.product_type_id='$type_id' group by product_variant_name");
        $data = json_encode($result);

        // dd(count($result));
        if (count($result) == 0) {
            $response = "[{'val':0,'option_name':'--Please Select --'}]";
        } else {
            $response = isset($_GET['callback']) ? $_GET['callback'] . "(" . $data . ")" : $data;
        }
        return $response;
    }

    public function jcomboproductvar()
    {

        $table = $_GET['table'];
        $type_id = $_GET['type_id'];
        $var_id = $_GET['var_id'];

        $result = \DB::select("SELECT $table.packing_id as val,$table.pack_name as option_name
    FROM `m_products_t` LEFT JOIN m_product_type_t ON
    m_products_t.product_type_id = m_product_type_t.product_type_id left join m_product_variants_t on m_products_t.product_variant_id=m_product_variants_t.product_variant_id
    LEFT JOIN $table on m_products_t.product_pack_id=$table.packing_id
    where m_product_type_t.product_type_id ='$type_id' and m_product_variants_t.product_variant_id='$var_id' and $table.packing_id!=''
    GROUP BY $table.packing_id");
        $data = json_encode($result);

        // dd(count($result));
        if (count($result) == 0) {
            $response = "[{'val':0,'option_name':'--Please Select --'}]";
        } else {
            $response = isset($_GET['callback']) ? $_GET['callback'] . "(" . $data . ")" : $data;
        }
        return $response;
    }

    // product unit function
    public function jcomboproductunit()
    {

        $table = $_GET['table'];
        $type_id = $_GET['type_id'];
        $var_id = $_GET['var_id'];
        $pack_id = $_GET['pack_id'];

        $result = \DB::select("SELECT $table.product_id as val, $table.concatenated_product as option_name FROM `$table` LEFT JOIN m_product_type_t ON
        $table.product_type_id = m_product_type_t.product_type_id left join m_product_variants_t on $table.product_variant_id=m_product_variants_t.product_variant_id
        LEFT JOIN i_product_packs on $table.product_pack_id=i_product_packs.packing_id
        where m_product_type_t.product_type_id ='$type_id' and m_product_variants_t.product_variant_id='$var_id' and i_product_packs.packing_id='$pack_id'
        GROUP BY $table.product_id");
        $data = json_encode($result);

        // dd(count($result));
        if (count($result) == 0) {
            $response = "[{'val':0,'option_name':'--Please Select --'}]";
        } else {
            $response = isset($_GET['callback']) ? $_GET['callback'] . "(" . $data . ")" : $data;
        }
        return $response;
    }

    // product  function
    public function jcomboproductname()
    {
        //$compy=\Session::get('companyid');
        $table = $_GET['table'];
        $type_id = $_GET['type_id'];

        $result = \DB::select("select $table.product_id as val, $table.concatenated_product as option_name FROM `$table` LEFT JOIN    m_product_type_t ON
    $table.product_type_id = m_product_type_t.product_type_id  where m_product_type_t.product_type_id='$type_id' group by concatenated_product");
        $data = json_encode($result);

        // dd(count($result));
        if (count($result) == 0) {
            $response = "[{'val':0,'option_name':'--Please Select --'}]";
        } else {
            $response = isset($_GET['callback']) ? $_GET['callback'] . "(" . $data . ")" : $data;
        }
        return $response;
    }


    // product  function
    public function jcomboprodt()
    {

        $table = $_GET['table'];
        $type_id = $_GET['type_id'];
        $var_id = $_GET['var_id'];

        $result = \DB::select("SELECT $table.product_id as val,$table.concatenated_product as option_name
    FROM `$table` LEFT JOIN m_product_type_t ON
    $table.product_type_id = m_product_type_t.product_type_id left join m_product_variants_t on $table.product_variant_id=m_product_variants_t.product_variant_id
    LEFT JOIN i_product_packs on $table.product_pack_id=i_product_packs.packing_id
    where m_product_type_t.product_type_id ='$type_id' and m_product_variants_t.product_variant_id='$var_id' 
    GROUP BY $table.product_id");
        $data = json_encode($result);

        // dd(count($result));
        if (count($result) == 0) {
            $response = "[{'val':0,'option_name':'--Please Select --'}]";
        } else {
            $response = isset($_GET['callback']) ? $_GET['callback'] . "(" . $data . ")" : $data;
        }
        return $response;
    }

    // secondary sales reports zone ,region,state on change  function

    public function jcombosecondsales()
    {
        $compy = \Session::get('companyid');
        $table = $_GET['table'];
        $wh1 = "";
        //dd($table);


        if (isset($_GET['parent']) && isset($_GET['order_by'])) {

            $parent = "where " . $_GET['parent'];
            $orderby = "ORDER BY " . $_GET['order_by'];
        } else if (isset($_GET['parent'])) {

            $parent = "where " . $_GET['parent'];
            $orderby = "";
        } else {
            $parent = 'where 1=1 ';
            $orderby = '';
        }
        $table = explode(":", $table);
        $display_name = explode("|", $table[2]);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");

        if ($table[0] == 'm_supplier_t') {
            $wh1 .= " and m_supplier_t.savestatus='APPROVED'";
        }

        if ($table[0] == 'i_pricelist_hdr_t') {
            $wh1 .= " and i_pricelist_hdr_t.savestatus='APPROVED'";
        }

        //dd($wh1);
        $result = \DB::select("SELECT DISTINCT  $table[1] as val, CONCAT(" . $dis_name . ") as option_name FROM $table[0] $parent and company_id=" . $compy . " AND region NOT IN ('EXPORT','LOCAL', 'WB(DERMA)','GOVT SUPPLY') $wh1   $orderby");
        $data = json_encode($result);


        // dd(count($result));
        if (count($result) == 0) {
            $response = "[{'val':'','option_name':'--Please Select --'}]";
        } else {
            $response = isset($_GET['callback']) ? $_GET['callback'] . "(" . $data . ")" : $data;
        }
        return $response;
    }

    public function grnjournal()
    {
        echo "Completed";
    }

    // if employee didn't menu access restrict purpose

    public function Accessdined()
    {


        //  Retrieve menu access data for the user
        $userAccess = \DB::table('a_user_access_t')
            ->select('menus')
            ->where('user_id', \Session::get('id'))
            ->first();

        if ($userAccess) {

            $menusMapping = json_decode($userAccess->menus, true);

            $headers = [];
            foreach ($menusMapping as $menuId => $menuName) {
                $menu = DB::table("tb_menus")
                    ->select('menus_id', 'controller_name')
                    ->where('menus_id', '=', $menuId)
                    ->where('parent_id', '!=', 0)
                    ->first();

                if ($menu) {
                    $headers[] = [

                        $menu->controller_name,
                    ];
                }
            }

        } else {

            dd('User access data not found.');
        }

        $access = json_encode($headers);


        return $access;


    }

    // END
    // product type group based purpose

    public function jcomboprotypejoinselect($table, $option, $display, $table1, $option1, $option2, $display1, $selected, $condition)
    {
        $compy = \Session::get('companyid');
        $display_name = explode("|", $display);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $table . "." . $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");
        $select_query = \DB::select("select  DISTINCT(" . $dis_name . ") as  name ," . $table . '.' . $option . "  from " . $table . " left join " . $table1 . " on " . $table1 . "." . $option1 . " = " . $table . " . " . $option2 . " WHERE 1=1 and " . $table . ".company_id=" . $compy . " and " . $table . ".active='Yes' " . $condition);

        $html = "<option value=''>-- Please Select --</option>";
        foreach ($select_query as $value) {

            if ($value->$option == $selected) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->name . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->name . "</option>";
            }
        }
        return $html;
    }

    public function jcomboreportingjoinselect($table, $option, $display, $table1, $option1, $option2, $display1, $selected, $condition)
    {
        $compy = \Session::get('companyid');
        $display_name = explode("|", $display);
        $display_n = "";
        foreach ($display_name as $val) {

            $display_n .= $table . "." . $val . "," . "' - ',";
        }
        $dis_name = rtrim($display_n, ",' - ',");
        $select_query = \DB::select("SELECT DISTINCT(CONCAT(" . $dis_name . ")) AS name, m.employee_id FROM hr_employee_t e JOIN hr_employee_t m ON e.reporting_manager = m.employee_id WHERE e.active='Yes' and e.group_type!=14 and m.employee_id!=1");

        $html = "<option value=''>-- Please Select --</option>";
        foreach ($select_query as $value) {

            if ($value->$option == $selected) {
                $html .= "<option value='" . $value->$option . "' selected='selected'>" . $value->name . "</option>";
            } else {
                $html .= "<option value='" . $value->$option . "' >" . $value->name . "</option>";
            }
        }
        return $html;
    }


}
