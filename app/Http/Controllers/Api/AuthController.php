<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct()
    {
        // DO NOT call parent::__construct() to avoid global auth middleware
        // Use JWT middleware with the api guard
        $this->middleware('auth:api')->except(['login', 'refreshToken']);
    }

    public function login(Request $request)
    {
        try {
            // Log request metadata
            Log::info('API Login Request Initiated', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'employee_number' => $request->employee_number ?? 'not_provided',
                'company_id' => $request->company_id ?? 'not_provided',
                'timestamp' => now()->toDateTimeString()
            ]);

            // Validate input
            $request->validate([
                'employee_number' => 'required|string',
                'password' => 'required|string',
                'company_id' => 'required|integer',
            ]);

            $credentials = $request->only('employee_number', 'password', 'company_id');

            // Check if user exists
            $user = \App\Models\MtbUser::where('employee_number', $request->employee_number)
                ->where('company_id', $request->company_id)
                ->first();

            // Validate active status in hr_employee_t (tb_employee table)
            $employeeRecord = DB::table('hr_employee_t')
                ->where('employee_number', $request->employee_number)
                ->where('company_id', $request->company_id)
                ->first();

            if ($employeeRecord && isset($employeeRecord->active) && strtolower(trim($employeeRecord->active)) !== 'yes') {
                Log::warning('Login Failed - Employee Inactive', [
                    'employee_number' => $request->employee_number,
                    'company_id' => $request->company_id,
                    'employee_id' => $employeeRecord->employee_id ?? null,
                    'active_status' => $employeeRecord->active ?? null,
                    'ip' => $request->ip(),
                    'timestamp' => now()->toDateTimeString()
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'User is inactive',
                ], 403);
            }
            if (!$user) {
                Log::warning('Login Failed - User Not Found', [
                    'employee_number' => $request->employee_number,
                    'company_id' => $request->company_id,
                    'ip' => $request->ip(),
                    'timestamp' => now()->toDateTimeString()
                ]);
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }

            // Verify password
            if (!Hash::check($request->password, $user->password)) {
                Log::warning('Login Failed - Invalid Password', [
                    'employee_number' => $request->employee_number,
                    'company_id' => $request->company_id,
                    'user_id' => $user->id,
                    'ip' => $request->ip(),
                    'timestamp' => now()->toDateTimeString()
                ]);
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }

            // JWT attempt
            if (!$token = auth('api')->attempt($credentials)) {
                Log::error('Login Failed - JWT Generation Failed', [
                    'employee_number' => $request->employee_number,
                    'company_id' => $request->company_id,
                    'user_id' => $user->id,
                    'ip' => $request->ip(),
                    'timestamp' => now()->toDateTimeString()
                ]);
                return response()->json([
                    'status' => false,
                    'message' => 'Could not create token',
                ], 500);
            }

            $authenticatedUser = auth('api')->user();
            $expiresIn = auth('api')->factory()->getTTL() * 60;

            Log::info('Login Successful - Token Generated', [
                'user_id' => $authenticatedUser->id,
                'employee_number' => $authenticatedUser->employee_number,
                'company_id' => $authenticatedUser->company_id,
                'token_expires_in_seconds' => $expiresIn,
                'token_expires_at' => now()->addSeconds($expiresIn)->toDateTimeString(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'access_token' => $token,
                'refresh_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $expiresIn,
                'expires_at' => now()->addSeconds($expiresIn)->toDateTimeString(),
                'user' => [
                    'id' => $authenticatedUser->id,
                    'employee_number' => $authenticatedUser->employee_number,
                    'company_id' => $authenticatedUser->company_id,
                    'first_name' => $authenticatedUser->first_name,
                    'group_id' => $authenticatedUser->group_id,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Login Failed - Validation Error', [
                'errors' => $e->errors(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Login Failed - Unexpected Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during login',
            ], 500);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }

    public function logout(Request $request)
    {
        try {
            $user = auth('api')->user();

            Log::info('Logout Request Initiated', [
                'user_id' => $user ? $user->id : null,
                'employee_number' => $user ? $user->employee_number : null,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            auth('api')->logout();

            Log::info('Logout Successful', [
                'user_id' => $user ? $user->id : null,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            Log::error('Logout Failed - Unexpected Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during logout'
            ], 500);
        }
    }

    public function refresh(Request $request)
    {
        try {
            $user = auth('api')->user();

            Log::info('Token Refresh Request Initiated', [
                'user_id' => $user ? $user->id : null,
                'employee_number' => $user ? $user->employee_number : null,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            $newToken = auth('api')->refresh();
            $expiresIn = auth('api')->factory()->getTTL() * 60;

            Log::info('Token Refresh Successful', [
                'user_id' => $user ? $user->id : null,
                'new_token_expires_in_seconds' => $expiresIn,
                'new_token_expires_at' => now()->addSeconds($expiresIn)->toDateTimeString(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Token refreshed successfully',
                'access_token' => $newToken,
                'token_type' => 'bearer',
                'expires_in' => $expiresIn,
                'expires_at' => now()->addSeconds($expiresIn)->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            Log::error('Token Refresh Failed - Unexpected Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while refreshing token'
            ], 500);
        }
    }

    public function refreshToken(Request $request)
    {
        try {
            Log::info('Refresh Token Request with Body', [
                'has_refresh_token' => $request->has('refresh_token'),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            $request->validate([
                'refresh_token' => 'required|string',
            ]);

            // Set the token from the request
            auth('api')->setToken($request->refresh_token);

            // Get user info before refresh
            $user = auth('api')->user();

            // Refresh the token
            $newToken = auth('api')->refresh();
            $expiresIn = auth('api')->factory()->getTTL() * 60;

            Log::info('Token Refreshed Successfully via refreshToken()', [
                'user_id' => $user ? $user->id : null,
                'employee_number' => $user ? $user->employee_number : null,
                'new_token_expires_in_seconds' => $expiresIn,
                'new_token_expires_at' => now()->addSeconds($expiresIn)->toDateTimeString(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Token refreshed successfully',
                'access_token' => $newToken,
                'refresh_token' => $newToken,
                'token_type' => 'bearer',
                'expires_in' => $expiresIn,
                'expires_at' => now()->addSeconds($expiresIn)->toDateTimeString(),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Refresh Token Failed - Validation Error', [
                'errors' => $e->errors(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Refresh Token Failed - Unexpected Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while refreshing token',
            ], 500);
        }
    }

    public function me(Request $request)
    {
        try {
            Log::info('Get User Info Request', [
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            $user = auth('api')->user();

            if (!$user) {
                Log::warning('Get User Info - No Authenticated User', [
                    'ip' => $request->ip(),
                    'timestamp' => now()->toDateTimeString()
                ]);
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            Log::info('Get User Info - Success', [
                'user_id' => $user->id,
                'employee_number' => $user->employee_number,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Get User Info - Unexpected Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving user information'
            ], 500);
        }
    }

    /**
     * Get user details by employee_number (from hr_employee_t)
     * POST: { employee_number }
     * Returns: all employee data from hr_employee_t
     */
    public function getUserByEmployeeNumber(Request $request)
    {
        try {
            Log::info('Get User By Employee Number Request', [
                'employee_number' => $request->employee_number ?? 'not_provided',
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            $request->validate([
                'employee_number' => 'required|string',
            ]);

            $employee = DB::table('hr_employee_t')
                ->where('employee_number', $request->employee_number)
                ->first();

            if (!$employee) {
                Log::info('Get User By Employee Number - Employee Not Found', [
                    'employee_number' => $request->employee_number,
                    'ip' => $request->ip(),
                    'timestamp' => now()->toDateTimeString()
                ]);
                return response()->json([
                    'status' => false,
                    'message' => 'Employee not found',
                ], 404);
            }

            Log::info('Get User By Employee Number - Success', [
                'employee_id' => $employee->employee_id,
                'employee_number' => $employee->employee_number,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => true,
                'employee' => $employee,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Get User By Employee Number - Validation Error', [
                'errors' => $e->errors(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Get User By Employee Number - Unexpected Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving employee information'
            ], 500);
        }
    }

    /**
     * Get employee details by employee_id (from hr_employee_t)
     * GET: /api/employee/{id}
     * Returns: all employee data from hr_employee_t with joined related data
     */
    public function getEmployeeById(Request $request, $id)
    {
        try {
            Log::info('Get Employee By ID Request', [
                'employee_id' => $id,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            // Get base employee data (only select e.* to avoid duplicate columns)
            $employee = DB::table('hr_employee_t as e')
                ->where('e.employee_id', $id)
                ->select('e.*')
                ->first();

            if (!$employee) {
                Log::info('Get Employee By ID - Employee Not Found', [
                    'employee_id' => $id,
                    'ip' => $request->ip(),
                    'timestamp' => now()->toDateTimeString()
                ]);
                return response()->json([
                    'status' => false,
                    'message' => 'Employee not found',
                ], 404);
            }

            // Parse department array (JSON format like "[\"42\"]" or "[\"42\",\"43\"]")
            $departmentIds = [];
            if (!empty($employee->department)) {
                $decoded = json_decode($employee->department);
                if (is_array($decoded)) {
                    $departmentIds = array_map('intval', $decoded);
                } elseif (is_numeric($employee->department)) {
                    $departmentIds = [(int)$employee->department];
                }
            }

            // Get department details for all departments
            $departments = [];
            if (!empty($departmentIds)) {
                $departments = DB::table('m_department_lines_t')
                    ->whereIn('department_line_id', $departmentIds)
                    ->select('department_line_id', 'sub_department_name', 'sub_department_code')
                    ->get()
                    ->map(function ($dept) {
                        return [
                            'department_id' => $dept->department_line_id,
                            'department_name' => $dept->sub_department_name,
                            'department_code' => $dept->sub_department_code,
                        ];
                    })
                    ->toArray();
            }

            // Parse med_rep_area array (JSON format like "[\"126\"]" or "[\"126\",\"127\"]")
            $areaIds = [];
            if (!empty($employee->med_rep_area)) {
                $decoded = json_decode($employee->med_rep_area);
                if (is_array($decoded)) {
                    $areaIds = array_map('intval', $decoded);
                } elseif (is_numeric($employee->med_rep_area)) {
                    $areaIds = [(int)$employee->med_rep_area];
                }
            }

            // Get area details with city, state, and country
            $areas = [];
            if (!empty($areaIds)) {
                $areas = DB::table('m_area_t as a')
                    ->leftJoin('m_cities_t as c', 'c.city_id', '=', 'a.city_id')
                    ->leftJoin('m_states_t as s', 's.state_id', '=', 'a.state_id')
                    ->leftJoin('m_countries_t as co', 'co.country_id', '=', 'a.country_id')
                    ->whereIn('a.area_id', $areaIds)
                    ->select(
                        'a.area_id',
                        'a.area_name',
                        'a.teritory_type',
                        'c.city_id',
                        'c.city_name',
                        's.state_id',
                        's.state_name',
                        's.state_code',
                        'co.country_id',
                        'co.country_name'
                    )
                    ->get()
                    ->map(function ($area) {
                        return [
                            'area_id' => $area->area_id,
                            'area_name' => $area->area_name,
                            'teritory_type' => $area->teritory_type,
                            'city' => [
                                'city_id' => $area->city_id,
                                'city_name' => $area->city_name,
                            ],
                            'state' => [
                                'state_id' => $area->state_id,
                                'state_name' => $area->state_name,
                                'state_code' => $area->state_code,
                            ],
                            'country' => [
                                'country_id' => $area->country_id,
                                'country_name' => $area->country_name,
                            ],
                        ];
                    })
                    ->toArray();
            }

            // Get job title details
            $jobTitleDetails = null;
            if (!empty($employee->job_title)) {
                $jobTitle = DB::table('m_job_title')
                    ->where('job_title_id', $employee->job_title)
                    ->select('job_title_id', 'job_title_name', 'job_description')
                    ->first();
                if ($jobTitle) {
                    $jobTitleDetails = [
                        'job_title_id' => $jobTitle->job_title_id,
                        'job_title_name' => $jobTitle->job_title_name,
                        'job_description' => $jobTitle->job_description,
                    ];
                }
            }

            // Get position details
            $positionDetails = null;
            if (!empty($employee->position)) {
                $position = DB::table('m_position')
                    ->where('position_id', $employee->position)
                    ->select('position_id', 'position', 'position_description')
                    ->first();
                if ($position) {
                    $positionDetails = [
                        'position_id' => $position->position_id,
                        'position_name' => $position->position,
                        'position_description' => $position->position_description,
                    ];
                }
            }

            // Get employee type details
            $employeeTypeDetails = null;
            if (!empty($employee->employee_type)) {
                $empType = DB::table('a_lookuplines_t')
                    ->where('lookuplines_id', $employee->employee_type)
                    ->where('lookup_type', 'EMPLOYEE_TYPE')
                    ->select('lookuplines_id', 'lookup_code', 'lookup_meaning')
                    ->first();
                if ($empType) {
                    $employeeTypeDetails = [
                        'lookuplines_id' => $empType->lookuplines_id,
                        'lookup_code' => $empType->lookup_code,
                        'lookup_meaning' => $empType->lookup_meaning,
                    ];
                }
            }

            // Get employment status details
            $employmentStatusDetails = null;
            if (!empty($employee->employment_status)) {
                $empStatus = DB::table('a_lookuplines_t')
                    ->where('lookuplines_id', $employee->employment_status)
                    ->select('lookuplines_id', 'lookup_code', 'lookup_meaning')
                    ->first();
                if ($empStatus) {
                    $employmentStatusDetails = [
                        'status_id' => $empStatus->lookuplines_id,
                        'status_code' => $empStatus->lookup_code,
                        'status_name' => $empStatus->lookup_meaning,
                    ];
                }
            }

            // Get role details
            $roleDetails = null;
            if (!empty($employee->role)) {
                $role = DB::table('a_lookuplines_t')
                    ->where('lookuplines_id', $employee->role)
                    ->select('lookuplines_id', 'lookup_code', 'lookup_meaning')
                    ->first();
                if ($role) {
                    $roleDetails = [
                        'role_id' => $role->lookuplines_id,
                        'role_code' => $role->lookup_code,
                        'role_name' => $role->lookup_meaning,
                    ];
                }
            }

            // Get group details
            $groupDetails = null;
            if (!empty($employee->group_type)) {
                $group = DB::table('a_m_group_t')
                    ->where('group_id', $employee->group_type)
                    ->select('group_id', 'group_name', 'description')
                    ->first();
                if ($group) {
                    $groupDetails = [
                        'group_id' => $group->group_id,
                        'group_name' => $group->group_name,
                        'description' => $group->description,
                    ];
                }
            }

            // Get reporting manager 1 details (reporting_manager field - varchar)
            $reportingManager1Details = null;
            if (!empty($employee->reporting_manager)) {
                $rm1 = DB::table('hr_employee_t')
                    ->where('employee_number', $employee->reporting_manager)
                    ->orWhere('employee_id', $employee->reporting_manager)
                    ->select('employee_id', 'employee_number', 'prefix', 'first_name', 'last_name', 'email', 'position', 'department')
                    ->first();

                if ($rm1) {
                    $reportingManager1Details = [
                        'employee_id' => $rm1->employee_id,
                        'employee_number' => $rm1->employee_number,
                        'name' => ($rm1->first_name ?? '') . ' ' . ($rm1->last_name ?? ''),
                        'email' => $rm1->email,
                        'position' => $rm1->position,
                        'department' => $rm1->department,
                    ];
                }
            }

            // Get reporting manager 2 details (reporting_manager1 field - int)
            $reportingManager2Details = null;
            if (!empty($employee->reporting_manager1)) {
                $rm2 = DB::table('hr_employee_t')
                    ->where('employee_id', $employee->reporting_manager1)
                    ->select('employee_id', 'employee_number', 'prefix', 'first_name', 'last_name', 'email', 'position', 'department')
                    ->first();

                if ($rm2) {
                    $reportingManager2Details = [
                        'employee_id' => $rm2->employee_id,
                        'employee_number' => $rm2->employee_number,
                        'name' => ($rm2->first_name ?? '') . ' ' . ($rm2->last_name ?? ''),
                        'email' => $rm2->email,
                        'position' => $rm2->position,
                        'department' => $rm2->department,
                    ];
                }
            }

            // Get personal details from hr_emp_personal table
            $personalDetails = null;
            $personalData = DB::table('hr_emp_personal')
                ->where('employee_id', $id)
                ->first();

            if ($personalData) {
                // Use date_of_birth from hr_emp_personal if employee's DOB is null
                $dateOfBirth = $employee->date_of_birth ?? $personalData->date_of_birth;

                $personalDetails = [
                    'id' => $personalData->id,
                    'employee_id' => $personalData->employee_id,
                    'gender' => $personalData->gender,
                    'marital_status' => $personalData->marital_status,
                    'nationality' => $personalData->nationality,
                    'mother_tongue' => $personalData->mother_tongue,
                    'religion' => $personalData->religion,
                    'language' => $personalData->language,
                    'date_of_birth' => $dateOfBirth,
                    'age' => $personalData->age,
                    'blood_group' => $personalData->blood_group,
                    'personal_mail' => $personalData->personal_mail,
                    'personal_mobile' => $personalData->personal_mobile,
                    'passport_number' => $personalData->passport_number,
                    'passport_expiry_date' => $personalData->passport_expiry_date,
                    'driving_licence_number' => $personalData->driving_licence_number,
                    'driving_licence_expiry_date' => $personalData->driving_licence_expiry_date,
                    'id_name' => $personalData->id_name,
                    'id_number' => $personalData->id_number,
                    'id_name1' => $personalData->id_name1,
                    'id_number1' => $personalData->id_number1,
                    'vehicle_type' => $personalData->vehicle_type,
                    'vehicle_number' => $personalData->vehicle_number,
                    'father_name' => $personalData->father_name,
                    'mother_name' => $personalData->mother_name,
                    'spouse_name' => $personalData->spouse_name,
                    'spouse_dob' => $personalData->spouse_dob,
                    'no_of_children' => $personalData->no_of_children,
                    'children_name1' => $personalData->children_name1,
                    'children_name2' => $personalData->children_name2,
                    'children_dob1' => $personalData->children_dob1,
                    'children_dob2' => $personalData->children_dob2,
                    'aadhar_number' => $personalData->aadhar_number,
                    'pan_number' => $personalData->pan_number,
                    'father_aadhar_number' => $personalData->father_aadhar_number,
                    'mother_aadhar_number' => $personalData->mother_aadhar_number,
                    'spouce_aadhar_number' => $personalData->spouce_aadhar_number,
                    'updated_at' => $personalData->updated_at,
                    'created_at' => $personalData->created_at,
                ];

                // Update employee's date_of_birth if it was null
                if (!$employee->date_of_birth && $personalData->date_of_birth) {
                    $employee->date_of_birth = $personalData->date_of_birth;
                }
            }

            // Get leave balance details from Leave_balance_tbl table
            $leaveBalanceDetails = null;
            $leaveBalanceData = DB::table('Leave_balance_tbl')
                ->where('employee_id', $id)
                ->first();

            if ($leaveBalanceData) {
                $leaveBalanceDetails = [
                    'leave_balance_id' => $leaveBalanceData->leave_balance_id ?? null,
                    'employee_id' => $leaveBalanceData->employee_id ?? null,
                    'causal_leave' => $leaveBalanceData->causal_leave ?? 0,
                    'earn_leave' => $leaveBalanceData->earn_leave ?? 0,
                    'sick_leave' => $leaveBalanceData->sick_leave ?? 0,
                    'comp_off_leave' => $leaveBalanceData->comp_off_leave ?? 0,
                    'lop' => $leaveBalanceData->lop ?? 0,
                    'ocl' => $leaveBalanceData->ocl ?? 0,
                    'osl' => $leaveBalanceData->osl ?? 0,
                    'oel' => $leaveBalanceData->oel ?? 0,
                    'ocol' => $leaveBalanceData->ocol ?? 0,
                    'remarks' => $leaveBalanceData->remarks ?? null,
                    'location_id' => $leaveBalanceData->location_id ?? null,
                    'company_id' => $leaveBalanceData->company_id ?? null,
                    'updated_at' => $leaveBalanceData->updated_at ?? null,
                    'created_at' => $leaveBalanceData->created_at ?? null,
                ];
            }

            // Structure the response with nested objects
            $response = [
                'status' => true,
                'employee' => $employee,
                'personal_details' => $personalDetails,
                'leave_balance_details' => $leaveBalanceDetails,
                'department_details' => $departments,
                'area_details' => $areas,
                'job_title_details' => $jobTitleDetails,
                'position_details' => $positionDetails,
                'employee_type_details' => $employeeTypeDetails,
                'employment_status_details' => $employmentStatusDetails,
                'role_details' => $roleDetails,
                'group_details' => $groupDetails,
                'reporting_manager1_details' => $reportingManager1Details,
                'reporting_manager2_details' => $reportingManager2Details,
            ];

            Log::info('Get Employee By ID - Success', [
                'employee_id' => $employee->employee_id,
                'employee_number' => $employee->employee_number,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Get Employee By ID - Unexpected Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving employee information'
            ], 500);
        }
    }

    /**
     * Get department employees by group (main department)
     * GET: /api/department/{departmentId}/employees
     * 
     * Note: departmentId is actually group_id from a_m_group_t (main department classification)
     * 
     * Logic:
     * 1. Filter employees by group_type (group_id from a_m_group_t)
     * 2. Superadmin → All groups
     * 3. Manager → Same group employees
     * 4. Team Member → Only self
     * 5. is_manager determined by reporting_manager1 subordinate count
     * 
     * Returns: list of employees based on user's role and group
     */
    public function getDepartmentEmployees(Request $request, $departmentId)
    {
        try {
            // Note: $departmentId is actually group_id from a_m_group_t
            $groupId = $departmentId;

            Log::info('Get Department Employees Request', [
                'group_id' => $groupId,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            // Get authenticated user
            $user = auth('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Get current employee details
            $currentEmployee = DB::table('hr_employee_t')
                ->where('employee_id', $user->employee_id)
                ->where('active', 'Yes')
                ->first();

            if (!$currentEmployee) {
                return response()->json([
                    'status' => false,
                    'message' => 'Employee record not found or inactive'
                ], 404);
            }

            // Get group/main department info from a_m_group_t
            $currentGroup = null;
            $isSuperadmin = false;
            if (!empty($currentEmployee->group_type)) {
                $currentGroup = DB::table('a_m_group_t')
                    ->where('group_id', $currentEmployee->group_type)
                    ->select('group_id', 'group_name', 'description')
                    ->first();

                if ($currentGroup) {
                    // Check if Superadmin (group_id=1 or name contains "superadmin")
                    $isSuperadmin = ($currentGroup->group_id == 1 || stripos($currentGroup->group_name, 'superadmin') !== false);
                }
            }

            // Check if current employee is a manager (has subordinates in reporting_manager1)
            $subordinateCount = DB::table('hr_employee_t')
                ->where('reporting_manager', $currentEmployee->employee_id)
                ->where('active', 'Yes')
                ->count();

            $isManager = $subordinateCount > 0;

            Log::info('Access Check', [
                'employee_id' => $currentEmployee->employee_id,
                'group_type' => $currentEmployee->group_type,
                'current_group' => $currentGroup ? $currentGroup->group_name : null,
                'requested_group_id' => $groupId,
                'is_superadmin' => $isSuperadmin,
                'is_manager' => $isManager,
                'subordinate_count' => $subordinateCount
            ]);

            $query = DB::table('hr_employee_t as e')
                ->leftJoin('a_m_group_t as g', 'e.group_type', '=', 'g.group_id')
                ->leftJoin('m_position as p', 'e.position', '=', 'p.position_id')
                ->leftJoin('hr_employee_t as rm_primary', 'e.reporting_manager', '=', 'rm_primary.employee_id')
                ->leftJoin('hr_employee_t as rm_fallback', 'e.reporting_manager1', '=', 'rm_fallback.employee_id')
                ->where('e.active', 'Yes')
                ->where('e.group_type', $groupId);  // ALWAYS filter by requested group_id

            // Additional filtering based on access level
            if (!$isSuperadmin && !$isManager) {
                // TEAM MEMBER: Only their own record
                $query->where('e.employee_id', $currentEmployee->employee_id);
                Log::info('Applying Team Member Filter - Self Only');
            } elseif ($isManager && !$isSuperadmin) {
                // MANAGER (non-superadmin): Their group only (already filtered above)
                Log::info('Applying Manager Filter', ['group_id' => $groupId]);
            } else {
                // SUPERADMIN: All employees in the requested group (already filtered above)
                Log::info('Applying Superadmin Filter - Requested Group Only', ['group_id' => $groupId]);
            }

            $employees = $query
                ->select(
                    'e.employee_id',
                    'e.employee_number',
                    'e.first_name',
                    'e.last_name',
                    'e.email',
                    'e.department',
                    'e.position',
                    'e.group_type',
                    'e.reporting_manager',
                    'e.reporting_manager1',
                    'e.active',
                    'g.group_id as main_group_id',
                    'g.group_name as main_group_name',
                    'g.description as main_group_description',
                    'p.position as position_name',
                    'rm_primary.employee_number as rm_primary_number',
                    'rm_primary.first_name as rm_primary_first_name',
                    'rm_primary.last_name as rm_primary_last_name',
                    'rm_fallback.employee_number as rm_fallback_number',
                    'rm_fallback.first_name as rm_fallback_first_name',
                    'rm_fallback.last_name as rm_fallback_last_name'
                )
                ->orderBy('e.employee_number')
                ->get();

            Log::info('Employees Retrieved', [
                'employee_count' => count($employees),
                'is_superadmin' => $isSuperadmin,
                'is_manager' => $isManager
            ]);

            // Format the response
            $formattedEmployees = $employees->map(function ($emp) {
                // Parse department array to get first department ID and name
                $departmentInfo = null;
                if (!empty($emp->department)) {
                    $deptArray = json_decode($emp->department);
                    if (is_array($deptArray) && count($deptArray) > 0) {
                        $firstDeptId = intval($deptArray[0]);
                        // Get department name from m_department_lines_t
                        $dept = DB::table('m_department_lines_t')
                            ->where('department_line_id', $firstDeptId)
                            ->select('department_line_id', 'sub_department_name')
                            ->first();

                        if ($dept) {
                            $departmentInfo = [
                                'id' => $dept->department_line_id,
                                'name' => $dept->sub_department_name,
                            ];
                        }
                    }
                }

                // Check if this employee is a manager (has subordinates)
                $empIsManager = DB::table('hr_employee_t')
                    ->where('reporting_manager1', $emp->employee_id)
                    ->where('active', 'Yes')
                    ->exists();

                return [
                    'employee_id' => $emp->employee_id,
                    'employee_number' => $emp->employee_number,
                    'name' => trim($emp->first_name . ' ' . $emp->last_name),
                    'first_name' => $emp->first_name,
                    'last_name' => $emp->last_name,
                    'email' => $emp->email,
                    'active' => $emp->active,
                    'is_manager' => $empIsManager,
                    'group' => [
                        'id' => $emp->main_group_id,
                        'name' => $emp->main_group_name,
                        'description' => $emp->main_group_description,
                    ],
                    'department' => $departmentInfo,
                    'position' => [
                        'name' => $emp->position_name,
                    ],
                    'reporting_managers' => [
                        'primary' => $emp->reporting_manager ? [
                            'employee_number' => $emp->rm_primary_number,
                            'name' => trim(($emp->rm_primary_first_name ?? '') . ' ' . ($emp->rm_primary_last_name ?? '')),
                        ] : null,
                        'fallback' => $emp->reporting_manager1 ? [
                            'employee_number' => $emp->rm_fallback_number,
                            'name' => trim(($emp->rm_fallback_first_name ?? '') . ' ' . ($emp->rm_fallback_last_name ?? '')),
                        ] : null,
                    ],
                ];
            })->toArray();

            return response()->json([
                'status' => true,
                'access_level' => [
                    'is_superadmin' => $isSuperadmin,
                    'is_manager' => $isManager,
                    'subordinate_count' => $subordinateCount,
                ],
                'current_employee' => [
                    'employee_id' => $currentEmployee->employee_id,
                    'employee_number' => $currentEmployee->employee_number,
                    'name' => trim($currentEmployee->first_name . ' ' . $currentEmployee->last_name),
                    'group' => $currentGroup ? [
                        'id' => $currentGroup->group_id,
                        'name' => $currentGroup->group_name,
                        'description' => $currentGroup->description,
                    ] : null,
                ],
                'requested_group_id' => $groupId,
                'employee_count' => count($formattedEmployees),
                'employees' => $formattedEmployees,
            ]);
        } catch (\Exception $e) {
            Log::error('Get Department Employees - Unexpected Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'group_id' => $departmentId ?? 'unknown',
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving department employees'
            ], 500);
        }
    }



    /**
     * Get employee address details (current and permanent address)
     * GET: /api/employee/{employeeId}/address
     * 
     * Returns: current and permanent address with city, state, country details
     */
    public function getEmployeeAddressDetails(Request $request, $employeeId)
    {
        try {
            Log::info('Get Employee Address Details Request', [
                'employee_id' => $employeeId,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            // Get address data from hr_emp_contact table
            $addressData = DB::table('hr_emp_contact as ec')
                ->where('ec.employee_id', $employeeId)
                ->first();

            if (!$addressData) {
                Log::info('Get Employee Address Details - No Address Record Found', [
                    'employee_id' => $employeeId,
                    'ip' => $request->ip(),
                    'timestamp' => now()->toDateTimeString()
                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'No address record found for employee',
                    'data' => null
                ]);
            }

            // Get current city details
            $currentCityData = null;
            if (!empty($addressData->current_city)) {
                $currentCityData = DB::table('m_cities_t as c')
                    ->leftJoin('m_states_t as s', 's.state_id', '=', 'c.state_id')
                    ->leftJoin('m_countries_t as co', 'co.country_id', '=', 'c.country_id')
                    ->where('c.city_id', $addressData->current_city)
                    ->select(
                        'c.city_id',
                        'c.city_name',
                        's.state_id',
                        's.state_name',
                        's.state_code',
                        'co.country_id',
                        'co.country_name'
                    )
                    ->first();
            }

            // Get permanent city details
            $permanentCityData = null;
            if (!empty($addressData->permanent_city)) {
                $permanentCityData = DB::table('m_cities_t as c')
                    ->leftJoin('m_states_t as s', 's.state_id', '=', 'c.state_id')
                    ->leftJoin('m_countries_t as co', 'co.country_id', '=', 'c.country_id')
                    ->where('c.city_id', $addressData->permanent_city)
                    ->select(
                        'c.city_id',
                        'c.city_name',
                        's.state_id',
                        's.state_name',
                        's.state_code',
                        'co.country_id',
                        'co.country_name'
                    )
                    ->first();
            }

            // Build response
            $response = [
                'status' => true,
                'employee_id' => $employeeId,
                'current_address' => [
                    'street_address' => $addressData->current_street_address,
                    'flat_no' => $addressData->current_flat_no,
                    'street' => $addressData->current_street,
                    'locality' => $addressData->current_locality,
                    'postal_code' => $addressData->current_postal_code,
                    'city' => $currentCityData ? [
                        'city_id' => $currentCityData->city_id,
                        'city_name' => $currentCityData->city_name,
                    ] : [
                        'city_id' => $addressData->current_city,
                        'city_name' => null,
                    ],
                    'state' => $currentCityData ? [
                        'state_id' => $currentCityData->state_id,
                        'state_name' => $currentCityData->state_name,
                        'state_code' => $currentCityData->state_code,
                    ] : [
                        'state_id' => $addressData->current_state,
                        'state_name' => null,
                        'state_code' => null,
                    ],
                    'country' => $currentCityData ? [
                        'country_id' => $currentCityData->country_id,
                        'country_name' => $currentCityData->country_name,
                    ] : [
                        'country_id' => $addressData->current_country,
                        'country_name' => null,
                    ],
                ],
                'permanent_address' => [
                    'street_address' => $addressData->permanent_street_address,
                    'flat_no' => $addressData->permanent_flat_no,
                    'street' => $addressData->permanent_street,
                    'locality' => $addressData->permanent_locality,
                    'postal_code' => $addressData->permanent_postal_code,
                    'city' => $permanentCityData ? [
                        'city_id' => $permanentCityData->city_id,
                        'city_name' => $permanentCityData->city_name,
                    ] : [
                        'city_id' => $addressData->permanent_city,
                        'city_name' => null,
                    ],
                    'state' => $permanentCityData ? [
                        'state_id' => $permanentCityData->state_id,
                        'state_name' => $permanentCityData->state_name,
                        'state_code' => $permanentCityData->state_code,
                    ] : [
                        'state_id' => $addressData->permanent_state,
                        'state_name' => null,
                        'state_code' => null,
                    ],
                    'country' => $permanentCityData ? [
                        'country_id' => $permanentCityData->country_id,
                        'country_name' => $permanentCityData->country_name,
                    ] : [
                        'country_id' => $addressData->permanent_country,
                        'country_name' => null,
                    ],
                ],
                'same_address' => $addressData->same_address,
                'emergency_contacts' => $addressData->emergency_contacts ? json_decode($addressData->emergency_contacts) : null,
                'created_at' => $addressData->created_at,
                'updated_at' => $addressData->updated_at,
            ];

            Log::info('Get Employee Address Details - Success', [
                'employee_id' => $employeeId,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Get Employee Address Details - Unexpected Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'employee_id' => $employeeId,
                'ip' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving employee address details'
            ], 500);
        }
    }

    /**
     * Parse department ID (handles both single ID and JSON array format)
     * Example: "42" or "[\"42\"]" or "[\"42\",\"43\"]"
     */
    private function parseDepartmentIds($departmentId)
    {
        // Try JSON decode first
        $decoded = json_decode($departmentId);
        if (is_array($decoded)) {
            return array_map('intval', $decoded);
        }

        // If numeric string, return as array
        if (is_numeric($departmentId)) {
            return [(int)$departmentId];
        }

        // If already a number, return as array
        return [$departmentId];
    }
}
