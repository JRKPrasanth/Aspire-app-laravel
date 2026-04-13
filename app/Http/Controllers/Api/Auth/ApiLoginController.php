<?

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiLoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('employee_number', 'password', 'company_id');

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::user();
            return response()->json([
                'status' => 'success',
                'user' => $user,
                'token' => $user->createToken('API Token')->accessToken, // If using Passport
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid credentials'], 401);
    }
}
