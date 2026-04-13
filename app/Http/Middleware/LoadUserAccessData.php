<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class LoadUserAccessData
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Always fetch fresh data
			$user_info = DB::table('hr_employee_t')->where('employee_id', $user->id)->first();
            $access = DB::table('a_user_access_t')->where('user_id', $user->id)->first();
            $user_mail = DB::table('tb_users')->where('id', $user->id)->first();
            $buttons = DB::table('button_names_tbl')->get();
            $company_info = DB::table('m_company_t')->where('company_id', $user_info->company_id)->first();
			
            if ($access) {
                Session::put('data', json_decode($access->permission, true));
                Session::put('button_name', $buttons);
				Session::put('dept_id', $user_info->department);
				Session::put('groupname', $user_info->group_type);
                Session::put('user_email', $user_mail->user_mail);
				Session::put('griddate', "2024-04-01");
				Session::put('gridenddate', date('Y-m-d'));
                Session::put('j_date_format', "yy-mm-dd");
                Session::put('companylogo',$company_info->company_logo_name);
                Session::put('img', $user->avatar);
            }
			
        }

        return $next($request);
    }
}

?>