<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'employee_number';
    }

	public function login(Request $request)
{
    $this->validate($request, [
        'employee_number' => 'required|string',
        'password' => 'required|string',
        'company_id' => 'required|integer',
    ]);

    $credentials = [
        'employee_number' => $request->employee_number,
        'password' => $request->password,
        'company_id' => $request->company_id,
    ];

  if (Auth::attempt($credentials, $request->filled('remember'))) {
	  
        $user = Auth::user();

        session([
			'id' => $user->id,
			'emp_id' => $user->employee_id,           
			'companyid' => $user->company_id,    
			'loc_id' => "1", //$user->loc_id,
			'location' => "1", //$user->loc_id,
			'decimal' => "2",
			'p_date_format' => "Y-m-d",
			'first_name' => $user->first_name,
			'username' => $user->username,
			'organization' => $user->org_id,    
			'groupid' => $user->group_id, 
			'show_announcement' => true
				]);

	  
        return redirect()->intended($this->redirectPath());
    }

    return back()->withErrors([
        'employee_number' => 'Invalid credentials.',
    ])->withInput($request->only('employee_number', 'remember'));
}


public function logout(Request $request)
{
    $sid = session('employeeid'); // 'id' wasn't stored, use 'employeeid'
    date_default_timezone_set("Asia/Kolkata");
    $date_time = date('Y-m-d H:i:s');

    DB::table('tb_users')->where('id', $sid)->update(['last_logout' => $date_time]);

    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('login');
}

}
