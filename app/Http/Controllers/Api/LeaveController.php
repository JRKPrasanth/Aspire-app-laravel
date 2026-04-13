<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class LeaveController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    // Get leave balances for an employee
    public function getLeaveBalances(Request $request)
    {
        $eid = $request->input('employee_id');

        // Get leave balance
        $leave = DB::table('Leave_balance_tbl')
            ->where('employee_id', $eid)
            ->select('causal_leave', 'earn_leave', 'comp_off_leave', 'sick_leave')
            ->first();

        // Get employee details to check role and subordinates
        $employee = DB::table('hr_employee_t')
            ->where('employee_id', $eid)
            ->first();

        // Get initiated leaves breakdown by type for this employee
        // Exclude leave_type 133 (OD) since it's handled separately
        $initiatedLeaves = DB::table('hr_leaves_t')
            ->select('leave_type', DB::raw('COUNT(*) as count'))
            ->where('employee_id', $eid)
            ->where('leave_status', 'INITIATED')
            ->where('leave_type', '!=', 133) // Exclude OD - handled separately
            ->groupBy('leave_type')
            ->get()
            ->keyBy('leave_type');

        // Breakdown by leave type (using standard leave type IDs)
        $cl_initiated = $initiatedLeaves->get(130)->count ?? 0; // Casual Leave
        $el_initiated = $initiatedLeaves->get(132)->count ?? 0; // Earn Leave
        $sl_initiated = $initiatedLeaves->get(131)->count ?? 0; // Sick Leave
        $compoff_initiated = $initiatedLeaves->get(277)->count ?? 0; // Comp-off
        $permission_initiated = $initiatedLeaves->get(265)->count ?? 0; // Permission
        $lop_initiated = $initiatedLeaves->get(276)->count ?? 0; // Leave without pay

        // Get OD (On-Duty) initiated details - leave_type = 133
        // OD can be half-day (leave_mode = 134) or full-day (leave_mode = 135)
        $odInitiatedDetails = DB::table('hr_leaves_t')
            ->select('leave_mode', DB::raw('COUNT(*) as count'))
            ->where('employee_id', $eid)
            ->where('leave_type', 133) // OD leave type
            ->where('leave_status', 'INITIATED')
            ->groupBy('leave_mode')
            ->get()
            ->keyBy('leave_mode');

        $od_halfday_initiated = $odInitiatedDetails->get(134)->count ?? 0; // Half-day OD (hours-based)
        $od_fullday_initiated = $odInitiatedDetails->get(135)->count ?? 0; // Full-day OD (day-based)
        $od_total_initiated = $od_halfday_initiated + $od_fullday_initiated;

        // Total initiated leaves across all types (now correctly avoids double-counting OD)
        $total_leave_initiated = $initiatedLeaves->sum('count') + $od_total_initiated;

        // Check if this employee has any subordinates (anyone reporting to them)
        // This determines if they should see approval pending count
        $hasSubordinates = DB::table('hr_employee_t')
            ->where('reporting_manager', $eid)
            ->exists();

        // Get pending approvals count (only if employee has subordinates)
        // Executive-level employees without subordinates will get 0
        // Managers with subordinates will get actual count
        $reportApprovePending = 0;
        if ($hasSubordinates) {
            $reportApprovePending = DB::table('hr_leaves_t')
                ->where('forwarded_id', $eid)
                ->where('leave_status', 'INITIATED')
                ->count();
        }

        return response()->json([
            // Leave balances available
            'cl_balance_count' => $leave->causal_leave ?? 0,
            'el_balance_count' => $leave->earn_leave ?? 0,
            'sl_balance_count' => $leave->sick_leave ?? 0,
            'compoff_balance_count' => $leave->comp_off_leave ?? 0,

            // Initiated leaves breakdown by type
            'cl_initiated' => $cl_initiated,
            'el_initiated' => $el_initiated,
            'sl_initiated' => $sl_initiated,
            'compoff_initiated' => $compoff_initiated,
            'permission_initiated' => $permission_initiated,
            'lop_initiated' => $lop_initiated,
            'leave_initiated' => $total_leave_initiated, // Total of all types

            // OD (On-Duty) initiated details - leave_type = 133
            'od_initiated' => [
                'total' => $od_total_initiated,
                'halfday' => [
                    'count' => $od_halfday_initiated,
                    'mode_id' => 134,
                    'mode_name' => 'Half-Day OD',
                    'duration_field' => 'od_no_of_days (hours)' // Data stored as hours in od_start_date, od_end_date, od_no_of_days
                ],
                'fullday' => [
                    'count' => $od_fullday_initiated,
                    'mode_id' => 135,
                    'mode_name' => 'Full-Day OD',
                    'duration_field' => 'od_no_of_days (days)' // Data stored as dates in od_start_date, od_end_date with od_no_of_days = 1
                ]
            ],

            // Approval pending (0 for executives without subordinates, actual count for managers)
            'report_approve_pending' => $reportApprovePending,

            // Additional context for frontend
            'has_subordinates' => $hasSubordinates,
            'employee_role' => $employee->role ?? null,
            'reporting_manager_id' => $employee->reporting_manager ?? null,
        ]);
    }

    public function getEmployeeTypes(Request $request)
    {
        $employee_types = DB::table('a_lookuplines_t')
            ->select('lookuplines_id as id', 'lookup_code as name')
            ->where('lookup_type', 'employee_type')
            ->orderBy('lookup_code', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $employee_types
        ]);
    }

    public function getLeaveTypes(Request $request)
    {
        $employee_id = $request->input('employee_id');

        // Get leave balances for employee
        $leaveBalance = DB::table('Leave_balance_tbl')->where('employee_id', $employee_id)->first();

        $leaveTypes = [];

        // Casual Leave (130)
        if (!empty($leaveBalance->causal_leave) && $leaveBalance->causal_leave > 0) {
            $leaveTypes[] = DB::table('a_lookuplines_t')
                ->where('lookup_type', 'leave_type')
                ->where('lookuplines_id', 130)
                ->select('lookuplines_id', 'lookup_meaning', 'lookup_type')
                ->first();
        }

        // Sick Leave (131)
        if (!empty($leaveBalance->sick_leave) && $leaveBalance->sick_leave > 0) {
            $leaveTypes[] = DB::table('a_lookuplines_t')
                ->where('lookup_type', 'leave_type')
                ->where('lookuplines_id', 131)
                ->select('lookuplines_id', 'lookup_meaning', 'lookup_type')
                ->first();
        }

        // Earn Leave (132)
        if (!empty($leaveBalance->earn_leave) && $leaveBalance->earn_leave > 0) {
            $leaveTypes[] = DB::table('a_lookuplines_t')
                ->where('lookup_type', 'leave_type')
                ->where('lookuplines_id', 132)
                ->select('lookuplines_id', 'lookup_meaning', 'lookup_type')
                ->first();
        }

        // comp_off_leave (277)
        if (!empty($leaveBalance->comp_off_leave) && $leaveBalance->comp_off_leave > 0) {
            $leaveTypes[] = DB::table('a_lookuplines_t')
                ->where('lookup_type', 'leave_type')
                ->where('lookuplines_id', 277)
                ->select('lookuplines_id', 'lookup_meaning', 'lookup_type')
                ->first();
        }

        // Leave with no pay (276) - always available
        $leaveTypes[] = DB::table('a_lookuplines_t')
            ->where('lookup_type', 'leave_type')
            ->where('lookuplines_id', 276)
            ->select('lookuplines_id', 'lookup_meaning', 'lookup_type')
            ->first();

        // Leave with no pay (276) - always available
        $leaveTypes[] = DB::table('a_lookuplines_t')
            ->where('lookup_type', 'leave_type')
            ->where('lookuplines_id', 265)
            ->select('lookuplines_id', 'lookup_meaning', 'lookup_type')
            ->first();

        return response()->json([
            'status' => true,
            'leave_types' => array_values(array_filter($leaveTypes))
        ]);
    }

    public function getAvailableLeaveTypes(Request $request)
    {
        $employee_id = $request->input('employee_id');
        $group_id = $request->input('group_id');

        // Get group info
        $group = DB::table('a_m_group_t')->where('group_id', $group_id)->first();
        $isMarketing = $group && strtolower($group->group_name) === 'marketing';

        // Get leave balances
        $leaveBalance = DB::table('Leave_balance_tbl')->where('employee_id', $employee_id)->first();

        // Get all leave types from lookup table
        $leaveTypesLookup = DB::table('a_lookuplines_t')
            ->where('lookup_type', 'leave_type')
            ->get();

        $leaveTypes = [];
        foreach ($leaveTypesLookup as $type) {
            $balance = null;
            switch ($type->lookuplines_id) {
                case 130:
                    $balance = $leaveBalance->causal_leave ?? 0;
                    break;
                case 131:
                    $balance = $leaveBalance->sick_leave ?? 0;
                    break;
                case 132:
                    $balance = $leaveBalance->earn_leave ?? 0;
                    break;
                case 277:
                    $balance = $leaveBalance->comp_off_leave ?? 0;
                    break;
            }

            if (
                $type->lookuplines_id == 276 || $type->lookuplines_id == 265 || $type->lookuplines_id == 133 // always available
                || ($type->lookuplines_id == 131 && $isMarketing && $balance > 0)
                || ($type->lookuplines_id != 131 && $balance > 0)
            ) {
                $leaveTypes[] = [
                    'id' => $type->lookuplines_id,
                    'name' => $type->lookup_meaning,
                    'balance' => $balance
                ];
            }
        }

        return response()->json([
            'status' => true,
            'leave_types' => $leaveTypes
        ]);
    }

    public function getLeaveModes()
    {
        $leaveModes = DB::table('a_lookuplines_t')
            ->where('lookup_type', 'leave_mode')
            ->whereIn('lookuplines_id', [134, 135, 285, 286, 287])
            ->select('lookuplines_id as id', 'lookup_meaning as name')
            ->orderBy('lookuplines_id', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'leave_modes' => $leaveModes
        ]);
    }

    /**
     * Save a leave request for an employee (API)
     * POST: employee_id, leave_type_id, leave_mode_id, start_date, end_date, no_of_days, reason, approver_id
     * Returns: status, message, leave_id
     */
    public function save_leave_request(Request $request)
    {
        // Accept all possible fields for hr_leaves_t
        $validated = $request->validate([
            'employee_id'   => 'required|integer|exists:hr_employee_t,employee_id',
            'leave_type_id' => 'required|integer|exists:a_lookuplines_t,lookuplines_id',
            'leave_mode_id' => 'required|integer|exists:a_lookuplines_t,lookuplines_id',
            'start_date'    => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $leaveType = $request->input('leave_type_id');
                    $leaveMode = $request->input('leave_mode_id');
                    // For non-OD leaves and OD full-day, start_date is required
                    if ($leaveType != 133 || $leaveMode == 135) {
                        if (empty($value)) {
                            $fail('The start date field is required.');
                        }
                    }
                }
            ],
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'od_start_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $leaveType = $request->input('leave_type_id');
                    $leaveMode = $request->input('leave_mode_id');
                    if ($leaveType == 133 && $leaveMode == 134) {
                        if (empty($value)) {
                            $fail('The od_start_date field is required for half-day OD.');
                        }
                    }
                }
            ],
            'od_end_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request) {
                    $leaveType = $request->input('leave_type_id');
                    $leaveMode = $request->input('leave_mode_id');
                    if ($leaveType == 133 && $leaveMode == 134) {
                        if (empty($value)) {
                            $fail('The od_end_date field is required for half-day OD.');
                        }
                    }
                }
            ],
            'od_no_of_days' => [
                'nullable',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    $leaveType = $request->input('leave_type_id');
                    $leaveMode = $request->input('leave_mode_id');
                    if ($leaveType == 133 && $leaveMode == 134) {
                        if ($value === null || floatval($value) <= 0) {
                            $fail('The od_no_of_days must be greater than 0 for half-day OD.');
                        }
                    }
                }
            ],
            // Allow no_of_days = 0 only for permission (leave_type_id = 265)
            'no_of_days'    => [
                'nullable',
                'numeric',
                function ($attribute, $value, $fail) use ($request) {
                    $leaveType = $request->input('leave_type_id');
                    $leaveMode = $request->input('leave_mode_id');
                    // For half-day OD, no_of_days is not required (od_no_of_days is used)
                    if ($leaveType == 133 && $leaveMode == 134) {
                        return;
                    }
                    if ($leaveType == 265) {
                        if ($value < 0) {
                            $fail('The no_of_days must be 0 or greater for permission leave.');
                        }
                    } else {
                        if ($value < 0.5) {
                            $fail('The no_of_days must be at least 0.5 for this leave type.');
                        }
                    }
                }
            ],
            'alloted_days'  => 'nullable|numeric',
            'reason'        => 'nullable|string|max:255',
            'approver_id'   => 'required|integer|exists:hr_employee_t,employee_id',
            'start_date_time' => 'nullable|date',
            'end_date_time'   => 'nullable|date',
            'no_of_hrs'       => 'nullable|string|max:255',
            'alloted_hrs'     => 'nullable|string|max:255',
            'session'         => 'nullable|string|max:55',
            'leave_status'    => 'nullable|string|max:100',
            'approval_reason' => 'nullable|string|max:100',
            'approvel_comments' => 'nullable|string|max:250',
            'organization_id' => 'nullable|integer',
            'od_alloted_days' => 'nullable|string|max:255',
            'project_id'      => 'nullable|integer',
            'created_by'      => 'nullable|integer',
            'last_updated_by' => 'nullable|integer',
            'company_id'      => 'nullable|integer',
            'location_id'     => 'nullable|integer',
            'leave_combo'     => 'nullable|date',
            'dates'           => 'nullable|string',
        ]);

        // If end_date is not provided or empty, set it to start_date
        if (empty($validated['end_date'])) {
            $validated['end_date'] = $validated['start_date'] ?? null;
        }

        // If od_end_date is not provided for half-day OD, default it to od_start_date
        if (!empty($validated['od_start_date']) && empty($validated['od_end_date'])) {
            $validated['od_end_date'] = $validated['od_start_date'];
        }

        DB::beginTransaction();
        try {
            // Check for multiple initiated leaves in same month (blocking logic)
            // Limits: CL(130) max 2, EL(132) max 2, Comp-off(277) max 1 initiated per month
            // Use od_start_date for OD (133) entries
            if ($validated['leave_type_id'] == 133) {
                $month = date('Y-m', strtotime($validated['od_start_date'] ?? $validated['start_date']));
                $dateField = "DATE_FORMAT(od_start_date, '%Y-%m')";
            } else {
                $month = date('Y-m', strtotime($validated['start_date']));
                $dateField = "DATE_FORMAT(start_date, '%Y-%m')";
            }

            $initiatedInMonth = DB::table('hr_leaves_t')
                ->where('employee_id', $validated['employee_id'])
                ->where('leave_type', $validated['leave_type_id'])
                ->where('leave_status', 'INITIATED')
                ->where(DB::raw($dateField), '=', $month)
                ->count();

            // Apply limits based on leave type
            $monthlyLimit = null;
            $leaveTypeId = $validated['leave_type_id'];

            if ($leaveTypeId == 130) { // Casual Leave
                $monthlyLimit = 2;
            } elseif ($leaveTypeId == 132) { // Earn Leave
                $monthlyLimit = 2;
            } elseif ($leaveTypeId == 265) { // Permission
                $monthlyLimit = 2;
            } elseif ($leaveTypeId == 277) { // Comp-off
                $monthlyLimit = 1;
            }

            if ($monthlyLimit !== null && $initiatedInMonth >= $monthlyLimit) {
                return response()->json([
                    'status' => false,
                    'code' => 'MONTHLY_LIMIT_EXCEEDED',
                    'message' => "Cannot submit more than {$monthlyLimit} initiated request(s) for this leave type in the same month. Please wait for approval or rejection of existing request(s).",
                    'limit' => $monthlyLimit,
                    'current_count' => $initiatedInMonth
                ], 422);
            }

            // ✅ SPECIAL VALIDATION FOR PERMISSION (leave_type_id = 265)
            if ($leaveTypeId == 265) {
                // Permission-specific limit: Max 3 hours (180 minutes) per month
                $permissionMonthDate = $validated['start_date_time']
                    ? date('Y-m', strtotime($validated['start_date_time']))
                    : date('Y-m');

                // Helper: Convert H.MM to minutes (1.30 = 90 min)
                $toMinutes = function ($timeStr) {
                    $timeStr = strval($timeStr);
                    if (strpos($timeStr, '.') !== false) {
                        list($h, $m) = explode('.', $timeStr);
                        return (intval($h) * 60) + intval($m);
                    }
                    return intval($timeStr) * 60;
                };

                // Helper: Convert minutes to H.MM (90 = 1.30)
                $toHourMin = function ($min) {
                    return intdiv($min, 60) . '.' . str_pad($min % 60, 2, '0', STR_PAD_LEFT);
                };

                // Get all permission entries for this month
                $entries = DB::table('hr_leaves_t')
                    ->where('employee_id', $validated['employee_id'])
                    ->where('leave_type', 265)
                    ->where('leave_status', '!=', 'REJECT')
                    ->where(DB::raw("DATE_FORMAT(start_date_time, '%Y-%m')"), '=', $permissionMonthDate)
                    ->pluck('no_of_hrs');

                $totalMinutes = 0;
                foreach ($entries as $hrs) {
                    $totalMinutes += $toMinutes($hrs);
                }

                $requestedMinutes = $toMinutes($validated['no_of_hrs'] ?? 0);

                // Validate: 30, 60, or 90 minutes only
                if (!in_array($requestedMinutes, [30, 60, 90])) {
                    return response()->json([
                        'status' => false,
                        'code' => 'INVALID_PERMISSION_HOURS',
                        'message' => 'Permission must be 0.30, 1.00, or 1.30 hours (30, 60, or 90 minutes)',
                        'valid_options' => ['0.30', '1.00', '1.30'],
                        'requested' => $validated['no_of_hrs']
                    ], 422);
                }

                // Check 180 minutes limit
                if (($totalMinutes + $requestedMinutes) > 180) {
                    $remaining = max(0, 180 - $totalMinutes);
                    return response()->json([
                        'status' => false,
                        'code' => 'PERMISSION_HOURS_LIMIT_EXCEEDED',
                        'message' => 'Cannot exceed 3.00 hours (180 minutes) per month',
                        'limit' => [
                            'max_hours' => '3.00',
                            'current_hours' => $toHourMin($totalMinutes),
                            'requested_hours' => $validated['no_of_hrs'],
                            'total_would_be' => $toHourMin($totalMinutes + $requestedMinutes),
                            'remaining_hours' => $toHourMin($remaining),
                            'max_minutes' => 180,
                            'current_minutes' => $totalMinutes,
                            'remaining_minutes' => $remaining
                        ]
                    ], 422);
                }

                Log::info('Permission validated', [
                    'employee_id' => $validated['employee_id'],
                    'month' => $permissionMonthDate,
                    'requested' => $validated['no_of_hrs'],
                    'requested_min' => $requestedMinutes,
                    'total_min' => $totalMinutes,
                    'new_total_min' => $totalMinutes + $requestedMinutes
                ]);
            }

            // Attendance check: choose the appropriate date to check
            $attendanceDate = null;
            if ($validated['leave_type_id'] == 265) { // permission: use date portion of start_date_time if provided else start_date
                if (!empty($validated['start_date_time'])) {
                    $attendanceDate = date('Y-m-d', strtotime($validated['start_date_time']));
                } else {
                    $attendanceDate = $validated['start_date'] ?? null;
                }
            } elseif ($validated['leave_type_id'] == 133 && $validated['leave_mode_id'] == 134) { // OD half-day
                $attendanceDate = $validated['od_start_date'] ?? null;
            } else {
                $attendanceDate = $validated['start_date'] ?? null;
            }

            if (!empty($attendanceDate)) {
                $attendance = DB::table('hr_emp_attendence')
                    ->where('emp_id', $validated['employee_id'])
                    ->where('atten_date', $attendanceDate)
                    ->first();
                if ($attendance) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Attendance already exists for this date. Leave cannot be applied.'
                    ], 400);
                }
            }

            // Normalize OD fields based on mode: ensure only relevant date/duration fields are set
            if ($validated['leave_type_id'] == 133) {
                if ($validated['leave_mode_id'] == 134) { // half-day OD uses od_* fields
                    $validated['start_date'] = null;
                    $validated['end_date'] = null;
                    $validated['no_of_days'] = null;
                } else { // full-day OD uses start_date/no_of_days
                    // If frontend sent od_* instead of start_date, map them into start_date/no_of_days
                    if (empty($validated['start_date']) && !empty($validated['od_start_date'])) {
                        $validated['start_date'] = $validated['od_start_date'];
                        $validated['end_date'] = $validated['od_end_date'] ?? $validated['od_start_date'];
                        // od_no_of_days may be string; set as numeric for no_of_days
                        $validated['no_of_days'] = is_numeric($validated['od_no_of_days']) ? floatval($validated['od_no_of_days']) : $validated['od_no_of_days'];
                    }

                    // Clear od fields for full-day entries
                    $validated['od_start_date'] = null;
                    $validated['od_end_date'] = null;
                    $validated['od_no_of_days'] = null;
                }
            }

            // Get employee details for organization_id and location_id
            $employee = DB::table('hr_employee_t')->where('employee_id', $validated['employee_id'])->first();
            if (!$employee) {
                return response()->json(['status' => false, 'message' => 'Employee not found'], 404);
            }

            // Get leave balance row
            $leaveBalance = DB::table('Leave_balance_tbl')->where('employee_id', $validated['employee_id'])->lockForUpdate()->first();
            if (!$leaveBalance) {
                return response()->json(['status' => false, 'message' => 'Leave balance not found'], 404);
            }

            // Map leave_type_id to balance field
            $balanceField = null;
            switch ($validated['leave_type_id']) {
                case 130:
                    $balanceField = 'causal_leave';
                    break;
                case 131:
                    $balanceField = 'sick_leave';
                    break;
                case 132:
                    $balanceField = 'earn_leave';
                    break;
                case 277:
                    $balanceField = 'comp_off_leave';
                    break;
            }

            // Check balance if required
            if ($balanceField && isset($leaveBalance->{$balanceField})) {
                $days = isset($validated['no_of_days']) ? $validated['no_of_days'] : 0;
                if ($leaveBalance->{$balanceField} < $days) {
                    return response()->json(['status' => false, 'message' => 'Insufficient leave balance'], 400);
                }
            }

            // Set leave status to APPROVE if not provided
            $leave_status = $validated['leave_status'] ?? 'APPROVE';

            // Prepare insert data for all columns
            $insertData = [
                'employee_id'       => $validated['employee_id'],
                'leave_type'        => $validated['leave_type_id'],
                'leave_mode'        => $validated['leave_mode_id'],
                'start_date'        => $validated['start_date'],
                'end_date'          => $validated['end_date'],
                'start_date_time'   => $validated['start_date_time'] ?? null,
                'end_date_time'     => $validated['end_date_time'] ?? null,
                'no_of_days'        => $validated['no_of_days'],
                'alloted_days'      => $validated['alloted_days'] ?? $validated['no_of_days'],
                'no_of_hrs'         => $validated['no_of_hrs'] ?? null,
                'alloted_hrs'       => $validated['alloted_hrs'] ?? null,
                'session'           => $validated['session'] ?? null,
                'leave_status'      => $leave_status,
                'leave_reason'      => $validated['reason'] ?? null,
                'forwarded_id'      => $validated['approver_id'],
                'approval_reason'   => $validated['approval_reason'] ?? null,
                'approvel_comments' => $validated['approvel_comments'] ?? null,
                'organization_id'   => $validated['organization_id'] ?? $employee->org_id ?? 1,
                'od_start_date'     => $validated['od_start_date'] ?? null,
                'od_end_date'       => $validated['od_end_date'] ?? null,
                'od_no_of_days'     => $validated['od_no_of_days'] ?? null,
                'od_alloted_days'   => $validated['od_alloted_days'] ?? null,
                'created_at'        => now(),
                'updated_at'        => now(),
                'project_id'        => $validated['project_id'] ?? 0,
                'created_by'        => $validated['created_by'] ?? $validated['employee_id'],
                'last_updated_by'   => $validated['last_updated_by'] ?? $validated['employee_id'],
                'company_id'        => $validated['company_id'] ?? $employee->company_id ?? 1,
                'location_id'       => $validated['location_id'] ?? $employee->loc_id ?? 1,
                'leave_combo'       => $validated['leave_combo'] ?? null,
                'dates'             => $validated['dates'] ?? '',
            ];

            $leave_id = DB::table('hr_leaves_t')->insertGetId($insertData);

            // Update leave balance if approve
            if ($leave_status == 'APPROVE' && $balanceField && isset($leaveBalance->{$balanceField})) {
                DB::table('Leave_balance_tbl')
                    ->where('leave_balance_id', $leaveBalance->leave_balance_id)
                    ->update([
                        $balanceField => DB::raw($balanceField . ' - ' . floatval($validated['no_of_days'])),
                        'updated_at' => now(),
                    ]);
            }

            // Call auditlog if available
            // if (function_exists('auditlog')) {
            //     auditlog($leave_id, 'leave', 'create', $request->all(), 'hr_leaves_t');
            // }

            DB::commit();

            // ✅ Prepare response with remaining quota if permission
            $responseData = [
                'status' => true,
                'message' => 'Leave request saved successfully',
                'leave_id' => $leave_id,
            ];

            // Add remaining quota for permission requests
            if ($leaveTypeId == 265) {
                $permissionMonthDate = $validated['start_date_time']
                    ? date('Y-m', strtotime($validated['start_date_time']))
                    : date('Y-m');

                $totalHoursInMonth = DB::table('hr_leaves_t')
                    ->where('employee_id', $validated['employee_id'])
                    ->where('leave_type', 265)
                    ->where('leave_status', '!=', 'REJECT')
                    ->where(DB::raw("DATE_FORMAT(start_date_time, '%Y-%m')"), '=', $permissionMonthDate)
                    ->sum(DB::raw('CAST(no_of_hrs AS DECIMAL(5,2))'));

                $requestCountInMonth = DB::table('hr_leaves_t')
                    ->where('employee_id', $validated['employee_id'])
                    ->where('leave_type', 265)
                    ->where('leave_status', '!=', 'REJECT')
                    ->where(DB::raw("DATE_FORMAT(start_date_time, '%Y-%m')"), '=', $permissionMonthDate)
                    ->count();

                $responseData['data'] = [
                    'remaining_permission_quota' => [
                        'month' => $permissionMonthDate,
                        'requests_used' => $requestCountInMonth,
                        'requests_remaining' => max(0, 2 - $requestCountInMonth),
                        'hours_used' => round($totalHoursInMonth, 2),
                        'hours_remaining' => round(max(0, 3.0 - $totalHoursInMonth), 2),
                        'total_hours_limit' => 3.0,
                        'max_requests' => 2
                    ]
                ];
            }

            return response()->json($responseData);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error saving leave request: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Save bulk leave requests for all employees of a given type (API)
     * POST: employee_type, plus all fields required for a single leave (except employee_id)
     * Returns: status, message, count, errors
     */
    public function save_bulk_leave_request(Request $request)
    {
        $validated = $request->validate([
            'employee_type'   => 'required|string',
            'leave_type_id'   => 'required|integer|exists:a_lookuplines_t,lookuplines_id',
            'leave_mode_id'   => 'required|integer|exists:a_lookuplines_t,lookuplines_id',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'no_of_days'      => 'required|numeric|min:0.5',
            'alloted_days'    => 'nullable|numeric',
            'reason'          => 'nullable|string|max:255',
            'approver_id'     => 'required|integer|exists:hr_employee_t,employee_id',
            'start_date_time' => 'nullable|date',
            'end_date_time'   => 'nullable|date',
            'no_of_hrs'       => 'nullable|string|max:255',
            'alloted_hrs'     => 'nullable|string|max:255',
            'session'         => 'nullable|string|max:55',
            'leave_status'    => 'nullable|string|max:100',
            'approval_reason' => 'nullable|string|max:100',
            'approvel_comments' => 'nullable|string|max:250',
            'organization_id' => 'nullable|integer',
            'od_start_date'   => 'nullable|date',
            'od_end_date'     => 'nullable|date',
            'od_no_of_days'   => 'nullable|string|max:255',
            'od_alloted_days' => 'nullable|string|max:255',
            'project_id'      => 'nullable|integer',
            'created_by'      => 'nullable|integer',
            'last_updated_by' => 'nullable|integer',
            'company_id'      => 'nullable|integer',
            'location_id'     => 'nullable|integer',
            'leave_combo'     => 'nullable|date',
            'dates'           => 'nullable|string',
        ]);

        // Map OD full-day od_* fields into start_date/no_of_days if frontend provided od_* for bulk requests
        if ($validated['leave_type_id'] == 133 && $validated['leave_mode_id'] == 135) {
            if (empty($validated['start_date']) && !empty($validated['od_start_date'])) {
                $validated['start_date'] = $validated['od_start_date'];
                $validated['end_date'] = $validated['od_end_date'] ?? $validated['od_start_date'];
                $validated['no_of_days'] = is_numeric($validated['od_no_of_days']) ? floatval($validated['od_no_of_days']) : $validated['od_no_of_days'];
            }

            // Clear od_* to keep DB consistency for full-day OD
            $validated['od_start_date'] = null;
            $validated['od_end_date'] = null;
            $validated['od_no_of_days'] = null;
        }

        // Get all employees of the given type, excluding filtered employee_numbers and employee_id=1
        $employees = DB::table('hr_employee_t')
            ->where('employee_type', $validated['employee_type'])
            ->where('employee_id', '!=', 1)
            ->where(function ($query) {
                $query->where('employee_number', 'not like', 'NT-%')
                    ->where('employee_number', 'not like', 'NT%')
                    ->where('employee_number', 'not like', 'TT-%')
                    ->where('employee_number', 'not like', 'T-%')
                    ->where('employee_number', 'not like', 'A-%');
            })
            ->get();

        $successCount = 0;
        $errors = [];

        foreach ($employees as $emp) {
            DB::beginTransaction();
            try {
                // Attendance check: do not allow leave if attendance exists for start_date
                $attendance = DB::table('hr_emp_attendence')
                    ->where('emp_id', $emp->employee_id)
                    ->where('atten_date', $validated['start_date'])
                    ->first();
                if ($attendance) {
                    DB::rollBack();
                    $errors[] = [
                        'employee_id' => $emp->employee_id,
                        'error' => 'Attendance already exists for this date. Leave cannot be applied.'
                    ];
                    continue;
                }

                // Get leave balance row
                $leaveBalance = DB::table('Leave_balance_tbl')->where('employee_id', $emp->employee_id)->lockForUpdate()->first();
                if (!$leaveBalance) {
                    DB::rollBack();
                    $errors[] = [
                        'employee_id' => $emp->employee_id,
                        'error' => 'Leave balance not found'
                    ];
                    continue;
                }

                // Map leave_type_id to balance field
                // Note: OD (133) should not be validated against comp-off balance when creating bulk leave
                $balanceField = null;
                switch ($validated['leave_type_id']) {
                    case 130:
                        $balanceField = 'causal_leave';
                        break;
                    case 131:
                        $balanceField = 'sick_leave';
                        break;
                    case 132:
                        $balanceField = 'earn_leave';
                        break;
                    case 277:
                        $balanceField = 'comp_off_leave';
                        break;
                }

                // Check balance if required
                if ($balanceField && isset($leaveBalance->{$balanceField})) {
                    $days = isset($validated['no_of_days']) ? $validated['no_of_days'] : 0;
                    if ($leaveBalance->{$balanceField} < $days) {
                        DB::rollBack();
                        $errors[] = [
                            'employee_id' => $emp->employee_id,
                            'error' => 'Insufficient leave balance'
                        ];
                        continue;
                    }
                }

                // Set leave status to APPROVE if not provided
                $leave_status = $validated['leave_status'] ?? 'APPROVE';

                // Prepare insert data for all columns
                $insertData = [
                    'employee_id'       => $emp->employee_id,
                    'leave_type'        => $validated['leave_type_id'],
                    'leave_mode'        => $validated['leave_mode_id'],
                    'start_date'        => $validated['start_date'],
                    'end_date'          => $validated['end_date'],
                    'start_date_time'   => $validated['start_date_time'] ?? null,
                    'end_date_time'     => $validated['end_date_time'] ?? null,
                    'no_of_days'        => $validated['no_of_days'],
                    'alloted_days'      => $validated['alloted_days'] ?? $validated['no_of_days'],
                    'no_of_hrs'         => $validated['no_of_hrs'] ?? null,
                    'alloted_hrs'       => $validated['alloted_hrs'] ?? null,
                    'session'           => $validated['session'] ?? null,
                    'leave_status'      => $leave_status,
                    'leave_reason'      => $validated['reason'] ?? null,
                    'forwarded_id'      => $validated['approver_id'],
                    'approval_reason'   => $validated['approval_reason'] ?? null,
                    'approvel_comments' => $validated['approvel_comments'] ?? null,
                    'organization_id'   => $validated['organization_id'] ?? null,
                    'od_start_date'     => $validated['od_start_date'] ?? null,
                    'od_end_date'       => $validated['od_end_date'] ?? null,
                    'od_no_of_days'     => $validated['od_no_of_days'] ?? null,
                    'od_alloted_days'   => $validated['od_alloted_days'] ?? null,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                    'project_id'        => $validated['project_id'] ?? null,
                    'created_by'        => $validated['created_by'] ?? null,
                    'last_updated_by'   => $validated['last_updated_by'] ?? null,
                    'company_id'        => $validated['company_id'] ?? null,
                    'location_id'       => $validated['location_id'] ?? null,
                    'leave_combo'       => $validated['leave_combo'] ?? null,
                    'dates'             => $validated['dates'] ?? null,
                ];

                $leave_id = DB::table('hr_leaves_t')->insertGetId($insertData);

                // Update leave balance if approve
                if ($leave_status == 'APPROVE' && $balanceField && isset($leaveBalance->{$balanceField})) {
                    DB::table('Leave_balance_tbl')
                        ->where('leave_balance_id', $leaveBalance->leave_balance_id)
                        ->update([
                            $balanceField => DB::raw($balanceField . ' - ' . floatval($validated['no_of_days'])),
                            'updated_at' => now(),
                        ]);
                }

                // Call auditlog if available
                // if (function_exists('auditlog')) {
                //     auditlog($leave_id, 'leave', 'create', $request->all(), 'hr_leaves_t');
                // }

                DB::commit();
                $successCount++;
            } catch (\Exception $e) {
                DB::rollBack();
                $errors[] = [
                    'employee_id' => $emp->employee_id,
                    'error' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Bulk leave request processed',
            'success_count' => $successCount,
            'error_count' => count($errors),
            'errors' => $errors,
        ]);
    }

    /**
     * Get pending leave approvals for the logged-in user
     * GET /api/approvals/pending
     * Query params: page, per_page, search, from_date, to_date, leave_type
     */
    public function pendingApprovals(Request $request)
    {
        $user = auth()->user();
        if (!$user || !$user->employee_id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $approver_id = $user->employee_id;
        $perPage = $request->input('per_page', 20);
        $search = $request->input('search');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $leaveType = $request->input('leave_type');

        $query = DB::table('hr_leaves_t')
            ->select(
                'hr_leaves_t.leave_id',
                'hr_leaves_t.employee_id',
                'hr_leaves_t.leave_type',
                'hr_leaves_t.leave_mode',
                'hr_leaves_t.start_date',
                'hr_leaves_t.end_date',
                'hr_leaves_t.start_date_time',
                'hr_leaves_t.end_date_time',
                'hr_leaves_t.no_of_days',
                'hr_leaves_t.no_of_hrs',
                // OD-specific fields
                'hr_leaves_t.od_start_date',
                'hr_leaves_t.od_end_date',
                'hr_leaves_t.od_no_of_days',
                'hr_leaves_t.leave_reason',
                'hr_leaves_t.leave_status',
                'hr_leaves_t.created_at',
                DB::raw("CONCAT(emp.employee_number, ' - ', emp.first_name) as employee_name"),
                'leave_type_lookup.lookup_meaning as leave_type_name',
                'leave_mode_lookup.lookup_meaning as leave_mode_name'
            )
            ->leftJoin('hr_employee_t as emp', 'emp.employee_id', '=', 'hr_leaves_t.employee_id')
            ->leftJoin('a_lookuplines_t as leave_type_lookup', 'leave_type_lookup.lookuplines_id', '=', 'hr_leaves_t.leave_type')
            ->leftJoin('a_lookuplines_t as leave_mode_lookup', 'leave_mode_lookup.lookuplines_id', '=', 'hr_leaves_t.leave_mode')
            ->where('hr_leaves_t.forwarded_id', $approver_id)
            ->where('hr_leaves_t.leave_status', 'INITIATED');

        // Apply filters
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('emp.employee_number', 'like', "%$search%")
                    ->orWhere('emp.first_name', 'like', "%$search%")
                    ->orWhere('hr_leaves_t.leave_reason', 'like', "%$search%");
            });
        }
        if ($fromDate) {
            $query->where('hr_leaves_t.start_date', '>=', $fromDate);
        }
        if ($toDate) {
            $query->where('hr_leaves_t.end_date', '<=', $toDate);
        }
        if ($leaveType) {
            $query->where('hr_leaves_t.leave_type', $leaveType);
        }

        $total = $query->count();
        $data = $query->orderBy('hr_leaves_t.created_at', 'desc')
            ->paginate($perPage);

        // Post-process paginated items to map OD fields for half-day/full-day correctly
        $items = $data->items();
        foreach ($items as $idx => $row) {
            // Ensure OD half-day has date/time and hours filled from od_* fields
            if (isset($row->leave_type) && $row->leave_type == 133) {
                if (isset($row->leave_mode) && $row->leave_mode == 134) { // half-day OD
                    if (!empty($row->od_start_date)) {
                        $row->start_date = $row->od_start_date;
                        $row->end_date = $row->od_end_date ?? $row->od_start_date;
                        $row->start_date_time = $row->od_start_date;
                        $row->end_date_time = $row->od_end_date ?? $row->od_start_date;
                    }
                    // Map hours from od_no_of_days if present
                    if (!empty($row->od_no_of_days)) {
                        $row->no_of_hrs = (string)$row->od_no_of_days;
                        $row->no_of_days = 0;
                    }
                } elseif (isset($row->leave_mode) && $row->leave_mode == 135) { // full-day OD
                    // If full-day OD stored in od_* fields, map to start_date/no_of_days
                    if (empty($row->start_date) && !empty($row->od_start_date)) {
                        $row->start_date = date('Y-m-d', strtotime($row->od_start_date));
                        $row->end_date = !empty($row->od_end_date) ? date('Y-m-d', strtotime($row->od_end_date)) : $row->start_date;
                        $row->no_of_days = is_numeric($row->od_no_of_days) ? floatval($row->od_no_of_days) : $row->no_of_days;
                    }
                }
            }

            // Replace the item in the array
            $items[$idx] = $row;
        }

        return response()->json([
            'success' => true,
            'data' => $items,
            'total' => $total,
            'page' => $data->currentPage(),
            'per_page' => $data->perPage(),
            'last_page' => $data->lastPage(),
        ]);
    }

    /**
     * Summary by employee for approvals (initiated + approved) with leave balances
     * GET /api/approvals/summary
     * Optional query params: page, per_page, from_date, to_date, leave_type
     */
    public function approvalsSummary(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->employee_id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $approver_id = $user->employee_id;
            $perPage = (int) $request->input('per_page', 20);
            $page = (int) $request->input('page', 1);
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');
            $leaveType = $request->input('leave_type');
            // Year filter: default to current year if not provided
            $year = $request->input('year', date('Y'));


            // Build base query to get distinct employees who have leaves forwarded to this approver
            $baseQuery = DB::table('hr_leaves_t')
                ->where('forwarded_id', $approver_id)
                ->whereIn('leave_status', ['INITIATED', 'APPROVE'])
                ->whereYear('created_at', $year);

            if ($leaveType) {
                $baseQuery->where('leave_type', $leaveType);
            }
            if ($fromDate) {
                $baseQuery->whereDate('created_at', '>=', $fromDate);
            }
            if ($toDate) {
                $baseQuery->whereDate('created_at', '<=', $toDate);
            }

            $employeeIds = $baseQuery->distinct()->pluck('employee_id')->toArray();
            $totalEmployees = count($employeeIds);

            // Pagination slice
            $start = max(0, ($page - 1) * $perPage);
            $slice = array_slice($employeeIds, $start, $perPage);

            $result = [];

            foreach ($slice as $empId) {
                // Employee basic info
                $emp = DB::table('hr_employee_t')->where('employee_id', $empId)->first();
                $name = $emp ? ($emp->employee_number . ' - ' . $emp->first_name) : null;

                // Counts
                $initiatedCountQ = DB::table('hr_leaves_t')
                    ->where('forwarded_id', $approver_id)
                    ->where('employee_id', $empId)
                    ->where('leave_status', 'INITIATED');
                $approvedCountQ = DB::table('hr_leaves_t')
                    ->where('forwarded_id', $approver_id)
                    ->where('employee_id', $empId)
                    ->whereIn('leave_status', ['APPROVE']);

                if ($leaveType) {
                    $initiatedCountQ->where('leave_type', $leaveType);
                    $approvedCountQ->where('leave_type', $leaveType);
                }
                if ($fromDate) {
                    $initiatedCountQ->whereDate('created_at', '>=', $fromDate);
                    $approvedCountQ->whereDate('created_at', '>=', $fromDate);
                }
                if ($toDate) {
                    $initiatedCountQ->whereDate('created_at', '<=', $toDate);
                    $approvedCountQ->whereDate('created_at', '<=', $toDate);
                }
                // Filter by year (defaults to current year)
                $initiatedCountQ->whereYear('created_at', $year);
                $approvedCountQ->whereYear('created_at', $year);

                $initiatedCount = $initiatedCountQ->count();
                $approvedCount = $approvedCountQ->count();

                // Recent leaves lists (limit 10 each)
                $initiatedLeaves = DB::table('hr_leaves_t as l')
                    ->leftJoin('a_lookuplines_t as lt', 'lt.lookuplines_id', '=', 'l.leave_type')
                    ->leftJoin('a_lookuplines_t as lm', 'lm.lookuplines_id', '=', 'l.leave_mode')
                    ->where('l.forwarded_id', $approver_id)
                    ->where('l.employee_id', $empId)
                    ->where('l.leave_status', 'INITIATED')
                    ->when($leaveType, function ($q) use ($leaveType) {
                        return $q->where('l.leave_type', $leaveType);
                    })
                    ->whereYear('l.created_at', $year)
                    ->select('l.leave_id', 'l.leave_type', 'lt.lookup_meaning as leave_type_name', 'l.leave_mode', 'lm.lookup_meaning as leave_mode_name', 'l.start_date', 'l.end_date', 'l.start_date_time', 'l.end_date_time', 'l.no_of_days', 'l.no_of_hrs', 'l.od_start_date', 'l.od_end_date', 'l.od_no_of_days', 'l.leave_reason', 'l.leave_status', 'l.created_at')
                    ->orderBy('l.created_at', 'desc')
                    ->limit(10)
                    ->get();

                $approvedLeaves = DB::table('hr_leaves_t as l')
                    ->leftJoin('a_lookuplines_t as lt', 'lt.lookuplines_id', '=', 'l.leave_type')
                    ->leftJoin('a_lookuplines_t as lm', 'lm.lookuplines_id', '=', 'l.leave_mode')
                    ->where('l.forwarded_id', $approver_id)
                    ->where('l.employee_id', $empId)
                    ->whereIn('l.leave_status', ['APPROVE'])
                    ->when($leaveType, function ($q) use ($leaveType) {
                        return $q->where('l.leave_type', $leaveType);
                    })
                    ->whereYear('l.created_at', $year)
                    ->select('l.leave_id', 'l.leave_type', 'lt.lookup_meaning as leave_type_name', 'l.leave_mode', 'lm.lookup_meaning as leave_mode_name', 'l.start_date', 'l.end_date', 'l.start_date_time', 'l.end_date_time', 'l.no_of_days', 'l.no_of_hrs', 'l.od_start_date', 'l.od_end_date', 'l.od_no_of_days', 'l.leave_reason', 'l.leave_status', 'l.created_at')
                    ->orderBy('l.created_at', 'desc')
                    ->limit(10)
                    ->get();

                // Map OD fields for display (half-day/full-day)
                $mapLeaveRow = function ($row) {
                    if ($row->leave_type == 133) {
                        if ($row->leave_mode == 134) { // half-day
                            $row->start_date = $row->od_start_date ?? null;
                            $row->end_date = $row->od_end_date ?? $row->od_start_date ?? null;
                            $row->start_date_time = $row->od_start_date ?? $row->start_date_time ?? null;
                            $row->end_date_time = $row->od_end_date ?? $row->end_date_time ?? null;
                            if (!empty($row->od_no_of_days)) {
                                $row->no_of_hrs = (string)$row->od_no_of_days;
                                $row->no_of_days = 0;
                            }
                        } else { // full-day
                            if (empty($row->start_date) && !empty($row->od_start_date)) {
                                $row->start_date = date('Y-m-d', strtotime($row->od_start_date));
                                $row->end_date = !empty($row->od_end_date) ? date('Y-m-d', strtotime($row->od_end_date)) : $row->start_date;
                                $row->no_of_days = is_numeric($row->od_no_of_days) ? floatval($row->od_no_of_days) : $row->no_of_days;
                            }
                        }
                    }
                    return $row;
                };

                $initiatedLeaves = $initiatedLeaves->map($mapLeaveRow);
                $approvedLeaves = $approvedLeaves->map($mapLeaveRow);

                // Leave balances
                $balance = DB::table('Leave_balance_tbl')
                    ->where('employee_id', $empId)
                    ->select('causal_leave', 'sick_leave', 'earn_leave', 'comp_off_leave')
                    ->first();

                $result[] = [
                    'employee_id' => $empId,
                    'employee_name' => $name,
                    'initiated_count' => $initiatedCount,
                    'approved_count' => $approvedCount,
                    'leave_balance' => $balance,
                    'initiated_leaves' => $initiatedLeaves,
                    'approved_leaves' => $approvedLeaves,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'total_employees' => $totalEmployees,
                    'page' => $page,
                    'per_page' => $perPage,
                    'employees' => $result,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('approvalsSummary error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get single leave details for approval
     * GET /api/approvals/{id}
     */
    public function getApproval(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user || !$user->employee_id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $leave = DB::table('hr_leaves_t')
            ->select(
                'hr_leaves_t.*',
                DB::raw("CONCAT(emp.employee_number, ' - ', emp.first_name) as employee_name"),
                'emp.employee_number',
                'emp.first_name',
                'emp.company_id',
                'leave_type_lookup.lookup_meaning as leave_type_name',
                'leave_mode_lookup.lookup_meaning as leave_mode_name'
            )
            ->leftJoin('hr_employee_t as emp', 'emp.employee_id', '=', 'hr_leaves_t.employee_id')
            ->leftJoin('a_lookuplines_t as leave_type_lookup', 'leave_type_lookup.lookuplines_id', '=', 'hr_leaves_t.leave_type')
            ->leftJoin('a_lookuplines_t as leave_mode_lookup', 'leave_mode_lookup.lookuplines_id', '=', 'hr_leaves_t.leave_mode')
            ->where('hr_leaves_t.leave_id', $id)
            ->first();

        if (!$leave) {
            return response()->json(['status' => false, 'message' => 'Leave not found'], 404);
        }

        // Check if user is the approver
        if ($leave->forwarded_id != $user->employee_id) {
            return response()->json(['status' => false, 'message' => 'You are not authorized to approve this leave'], 403);
        }

        // Normalize OD fields for display: half-day uses od_start_date/time and od_no_of_days (hours);
        // full-day OD may have od_* values stored but should present as start_date/no_of_days
        if ($leave->leave_type == 133) {
            if ($leave->leave_mode == 134) { // half-day
                if (!empty($leave->od_start_date)) {
                    $leave->start_date_time = $leave->od_start_date;
                    $leave->end_date_time = $leave->od_end_date ?? $leave->od_start_date;
                }
                if (!empty($leave->od_no_of_days)) {
                    $leave->no_of_hrs = $leave->od_no_of_days;
                    $leave->no_of_days = 0;
                }
            } else { // full-day
                if (empty($leave->start_date) && !empty($leave->od_start_date)) {
                    $leave->start_date = date('Y-m-d', strtotime($leave->od_start_date));
                    $leave->end_date = !empty($leave->od_end_date) ? date('Y-m-d', strtotime($leave->od_end_date)) : $leave->start_date;
                    $leave->no_of_days = is_numeric($leave->od_no_of_days) ? floatval($leave->od_no_of_days) : $leave->no_of_days;
                }
            }
        }

        // Get leave balance
        $leaveBalance = DB::table('Leave_balance_tbl')
            ->where('employee_id', $leave->employee_id)
            ->select('causal_leave', 'sick_leave', 'earn_leave', 'comp_off_leave')
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'leave' => $leave,
                'leave_balance' => $leaveBalance,
            ],
        ]);
    }

    /**
     * Get employee leave history (API)
     * GET /api/employee/{id}/leave-history
     * Optional query params: type (leave_type id), year, status (comma-separated)
     */
    public function employeeLeaveHistory(Request $request, $employee_id = null)
    {
        $user = auth()->user();
        if (!$user || !$user->employee_id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $employee_id = $employee_id ?? $request->input('employee_id');
        if (!$employee_id) {
            return response()->json(['status' => false, 'message' => 'employee_id is required'], 400);
        }

        $type = $request->input('type');
        $year = $request->input('year'); // Optional - if null, return all years
        $status = $request->input('status'); // comma-separated: APPROVE,REJECT,INITIATED etc

        // Build year filter SQL
        $yearFilter = '';
        if ($year) {
            if ($type == '265') {
                $yearFilter = " AND (YEAR(l.start_date_time) = '" . $year . "' OR YEAR(l.end_date_time) = '" . $year . "')";
            } else {
                $yearFilter = " AND (YEAR(l.start_date) = '" . $year . "' OR YEAR(l.end_date) = '" . $year . "')";
            }
        }

        // Build status filter SQL
        $statusFilter = '';
        $statusesRequested = '';
        if ($status) {
            $statusesRequested = $status;
            $statusArray = array_map('trim', explode(',', $status));
            $statusList = "'" . implode("','", $statusArray) . "'";
            $statusFilter = " AND l.leave_status IN ($statusList)";
        }

        // If permission type (265) use start_date_time and no_of_hrs
        if ($type == '265') {
            $leave_summary = DB::select("SELECT l.leave_id, l.start_date_time as start_date, l.end_date_time as end_date, CONCAT(YEAR(date(l.start_date_time)), '-', LPAD(MONTH(date(l.start_date_time)),2,'0')) as month_yr, l.no_of_hrs as no_of_days, l.no_of_hrs, l.leave_reason, l.leave_status, l.leave_type, lt.lookup_meaning as leave_type_name, l.leave_mode, lm.lookup_meaning as leave_mode_name FROM hr_leaves_t l LEFT JOIN a_lookuplines_t lt ON lt.lookuplines_id = l.leave_type LEFT JOIN a_lookuplines_t lm ON lm.lookuplines_id = l.leave_mode WHERE l.employee_id='" . $employee_id . "'" . $yearFilter . $statusFilter . " ORDER BY l.start_date_time DESC");
            $unit = 'hrs';
        } else {
            $leave_summary = DB::select("SELECT l.leave_id, l.start_date, l.end_date, CONCAT(YEAR(date(l.start_date)), '-', LPAD(MONTH(date(l.start_date)),2,'0')) as month_yr, l.no_of_days, l.no_of_hrs, l.leave_reason, l.leave_status, l.leave_type, lt.lookup_meaning as leave_type_name, l.leave_mode, lm.lookup_meaning as leave_mode_name FROM hr_leaves_t l LEFT JOIN a_lookuplines_t lt ON lt.lookuplines_id = l.leave_type LEFT JOIN a_lookuplines_t lm ON lm.lookuplines_id = l.leave_mode WHERE l.employee_id='" . $employee_id . "'" . $yearFilter . $statusFilter . " ORDER BY l.start_date DESC");
            $unit = 'days';
        }

        // Group by month_yr and calculate totals
        $monthly = [];
        $monthlyTotals = [];

        foreach ($leave_summary as $row) {
            $m = $row->month_yr;
            if (!isset($monthly[$m])) {
                $monthly[$m] = [];
                $monthlyTotals[$m] = 0;
            }
            $monthly[$m][] = $row;
            $monthlyTotals[$m] += floatval($row->no_of_days ?? 0);
        }

        $result = [];
        foreach ($monthly as $month => $rows) {
            $result[] = [
                'month_yr' => $month,
                'total' => $monthlyTotals[$month],
                'unit' => $unit,
                'rows' => $rows,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'year' => $year ?? 'all',
                'count' => count($leave_summary),
                'statuses_requested' => $statusesRequested,
                'statuses_applied' => $statusesRequested,
                'total_leaves_found' => count($leave_summary),
                'monthly' => $result,
            ],
        ]);
    }

    /**
     * Get count of initiated leaves in a specific month for blocking multiple requests
     * GET /api/employee/{id}/initiated-leaves-month?month=YYYY-MM&leave_types=130,132,277
     * Returns: {success, initiated_count, leaves[]}
     */
    public function getInitiatedLeavesInMonth(Request $request, $employee_id)
    {
        $user = auth()->user();
        if (!$user || !$user->employee_id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        // Only allow employees to check their own leaves, or managers to check their subordinates
        if ($user->employee_id != $employee_id) {
            // For now, restrict to own employee
            return response()->json(['status' => false, 'message' => 'You can only check your own leaves'], 403);
        }

        $month = $request->input('month'); // Format: YYYY-MM
        $leave_types = $request->input('leave_types'); // Comma-separated: 130,132,277

        if (!$month || !preg_match('/^\d{4}-\d{2}$/', $month)) {
            return response()->json(['status' => false, 'message' => 'Invalid month format. Use YYYY-MM'], 400);
        }

        $types = [];
        if ($leave_types) {
            $types = array_map('intval', explode(',', $leave_types));
        }

        try {
            $query = DB::table('hr_leaves_t')
                ->where('employee_id', $employee_id)
                ->where('leave_status', 'INITIATED')
                ->where(DB::raw("DATE_FORMAT(start_date, '%Y-%m')"), '=', $month);

            if (!empty($types)) {
                $query->whereIn('leave_type', $types);
            }

            $leaves = $query->select(
                'leave_id',
                'leave_type',
                'start_date',
                'end_date',
                'no_of_days',
                'leave_status',
                'created_at'
            )->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'employee_id' => $employee_id,
                'month' => $month,
                'initiated_count' => count($leaves),
                'leaves' => $leaves,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching initiated leaves: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Approve or reject a leave request
     * POST /api/approvals/{id}/decision
     * Body: { decision: "APPROVE"|"REJECT", comments?: string, approval_reason?: string }
     */
    public function decideApproval(Request $request, $id)
    {
        $user = auth()->user();
        if (!$user || !$user->employee_id) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'decision' => 'nullable|in:APPROVE,REJECT',
            'alloted_days' => 'nullable|numeric|min:0',
            'od_alloted_days' => 'nullable|string|max:255',
            'comments' => 'nullable|string|max:250',
            'approval_reason' => 'nullable|string|max:100',
        ]);

        // Normalize decision (may be omitted; we'll derive from allotted days)
        $decision = isset($validated['decision']) ? strtoupper($validated['decision']) : null;
        if ($decision === 'REJECT') {
            $decision = 'REJECT';
        } elseif ($decision === 'APPROVE') {
            $decision = 'APPROVE';
        } elseif ($decision === 'PARTIAL') {
            $decision = 'PARTIALLY_APPROVE';
        }

        DB::beginTransaction();
        try {
            // Lock and get leave record
            $leave = DB::table('hr_leaves_t')
                ->where('leave_id', $id)
                ->lockForUpdate()
                ->first();

            if (!$leave) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Leave not found'], 404);
            }

            // Check if user is the approver
            if ($leave->forwarded_id != $user->employee_id) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'You are not authorized to approve this leave'], 403);
            }

            // Check if already processed
            if ($leave->leave_status !== 'INITIATED') {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'This leave has already been ' . strtolower($leave->leave_status)
                ], 409);
            }

            // Compute allotted, lop and resulting status
            // For most leaves we use no_of_days; for OD half-day (mode 134) od_no_of_days stores hours
            $requestedDays = floatval($leave->no_of_days ?? 0);
            $requestedHours = floatval($leave->od_no_of_days ?? 0); // used for OD half-day (hours)

            // Default alloted values
            $allotedDays = isset($validated['alloted_days']) ? floatval($validated['alloted_days']) : null;
            $odAllotedHours = isset($validated['od_alloted_days']) ? floatval($validated['od_alloted_days']) : null;

            // Add sensible defaults when missing
            if ($leave->leave_type == 133 && $leave->leave_mode == 134) {
                // Half-day OD: work in hours internally, convert to days (8 hours = 1 day) for balance deduction
                if ($odAllotedHours === null) {
                    $odAllotedHours = $requestedHours;
                }
                // Convert hours to days for ledger operations
                $allotedDays = ($odAllotedHours / 8.0);
                $requestedValueForDecision = $requestedHours / 8.0; // in days
            } else {
                // Regular leaves and full-day OD
                if ($allotedDays === null) {
                    $allotedDays = ($requestedDays > 0) ? $requestedDays : ($requestedHours > 0 ? $requestedHours : 0);
                }
                $requestedValueForDecision = ($requestedDays > 0) ? $requestedDays : ($requestedHours > 0 ? $requestedHours : 0);
            }

            $lopDays = max(0, $requestedValueForDecision - $allotedDays);

            // Derive decision if not explicitly provided
            if (!$decision) {
                if ($allotedDays <= 0) {
                    $decision = 'REJECT';
                } elseif ($allotedDays >= $requestedValueForDecision) {
                    $decision = 'APPROVE';
                } else {
                    $decision = 'APPROVE';
                }
            }

            // Prepare update data
            $updateData = [
                'leave_status' => $decision,
                'approvel_comments' => $validated['comments'] ?? null,
                'approval_reason' => $validated['approval_reason'] ?? null,
                'last_updated_by' => $user->employee_id,
                'updated_at' => now(),
                'alloted_days' => $allotedDays,
            ];

            // Add lop_days if table supports it
            if (Schema::hasColumn('hr_leaves_t', 'lop_days')) {
                $updateData['lop_days'] = $lopDays;
            }

            // Map leave_type to balance field (only for approved portion)
            // Note: OD (133) should NOT deduct comp-off on approval; comp-off is earned from OD
            $balanceField = null;
            switch ($leave->leave_type) {
                case 130:
                    $balanceField = 'causal_leave';
                    break;
                case 131:
                    $balanceField = 'sick_leave';
                    break;
                case 132:
                    $balanceField = 'earn_leave';
                    break;
                case 277:
                    $balanceField = 'comp_off_leave';
                    break;
            }

            // If any approved days to deduct and a balance field exists, deduct
            // approvedToDeduct is in DAYS (for half-day OD we converted hours -> days above)
            $approvedToDeduct = max(0, min($allotedDays, $requestedValueForDecision));

            // For half-day OD, also record od_alloted_days (hours)
            if ($leave->leave_type == 133 && $leave->leave_mode == 134) {
                $updateData['od_alloted_days'] = isset($odAllotedHours) ? (string)$odAllotedHours : null;
                // Ensure alloted_days is in days (already set above)
                $updateData['alloted_days'] = $allotedDays;
            }

            if ($approvedToDeduct > 0 && $balanceField) {
                $leaveBalance = DB::table('Leave_balance_tbl')
                    ->where('employee_id', $leave->employee_id)
                    ->lockForUpdate()
                    ->first();

                if (!$leaveBalance) {
                    DB::rollBack();
                    return response()->json(['status' => false, 'message' => 'Leave balance not found'], 404);
                }

                $currentBalance = $leaveBalance->{$balanceField} ?? 0;
                if ($currentBalance < $approvedToDeduct) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Insufficient leave balance. Available: ' . $currentBalance . ', To deduct: ' . $approvedToDeduct
                    ], 400);
                }

                DB::table('Leave_balance_tbl')
                    ->where('employee_id', $leave->employee_id)
                    ->update([
                        $balanceField => DB::raw($balanceField . ' - ' . $approvedToDeduct),
                        'updated_at' => now(),
                    ]);
            }

            // Update leave record
            DB::table('hr_leaves_t')
                ->where('leave_id', $id)
                ->update($updateData);

            // Audit log (if function exists)
            // if (function_exists('auditlog')) {
            //     auditlog($id, 'leave', 'approve', $request->all(), 'hr_leaves_t');
            // }

            DB::commit();

            // Fetch updated leave
            $updatedLeave = DB::table('hr_leaves_t')
                ->where('leave_id', $id)
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Leave ' . strtolower($decision) . ' successfully',
                'leave' => $updatedLeave,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error processing approval: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get employee leave history with status filtering
     * 
     * Backend Filtering: Only returns requested statuses
     * Default: APPROVE leaves only
     * 
     * Query Parameters:
     * - status: comma-separated list (APPROVE,REJECT,INITIATED)
     * - year: specific year (optional, returns all years if omitted)
     * 
     * Usage:
     * GET /api/employee/{id}/leave-history
     * GET /api/employee/{id}/leave-history?status=APPROVE
     * GET /api/employee/{id}/leave-history?status=APPROVE,REJECT
     * GET /api/employee/{id}/leave-history?status=APPROVE&year=2025
     */
    public function getEmployeeLeaveHistory($employee_id)
    {
        try {
            Log::info('=== getEmployeeLeaveHistory START ===');
            Log::info('Employee ID: ' . $employee_id);

            // ✅ Get status filter from query parameter
            // Default: show ALL statuses (empty means no filter)
            $statusFilter = request()->query('status', '');

            Log::info('Status filter from request: ' . $statusFilter);

            // ✅ Convert comma-separated string to array and normalize
            $statuses = [];
            if (!empty($statusFilter)) {
                $statusesRaw = array_map('trim', explode(',', $statusFilter));

                Log::info('Statuses array (raw): ' . json_encode($statusesRaw));

                // ✅ Normalize: APPROVE 
                foreach ($statusesRaw as $status) {
                    if ($status === 'APPROVE') {
                        $statuses[] = 'APPROVE';
                    } else {
                        $statuses[] = $status;
                    }
                }

                // Remove duplicates
                $statuses = array_unique($statuses);

                // ✅ Validate status values against allowed statuses
                $validStatuses = ['INITIATED', 'APPROVE', 'REJECT', 'PARTIALLY_APPROVED', 'PENDING'];
                $statuses = array_intersect($statuses, $validStatuses);

                Log::info('Valid statuses after normalization: ' . json_encode($statuses));
            } else {
                // No filter provided - return all statuses
                Log::info('No status filter provided, returning all statuses');
            }

            // ✅ Year filter - if not provided, get ALL years (null means no filter)
            $year = request()->query('year'); // Returns null if not provided
            $yearDisplay = $year ?? 'all';

            Log::info('Year filter: ' . $yearDisplay);

            // ✅ Get counts for ALL statuses (always returned)
            $countQuery = DB::table('hr_leaves_t')
                ->select('leave_status', DB::raw('COUNT(*) as count'))
                ->where('employee_id', $employee_id);

            // Apply year filter to counts (MUST MATCH main query logic)
            // Only apply year filter if year is provided; otherwise get all years
            if ($year !== null) {
                $countQuery->where(function ($q) use ($year) {
                    $q->where(function ($q2) use ($year) {
                        // Regular leaves: check start_date and end_date
                        $q2->whereYear('start_date', $year)
                            ->orWhereYear('end_date', $year);
                    })
                        ->orWhere(function ($q2) use ($year) {
                            // Permission leaves (265): check start_date_time
                            $q2->whereYear('start_date_time', $year);
                        })
                        ->orWhere(function ($q2) use ($year) {
                            // OD leaves (133): check both od_start_date (half-day) and start_date (full-day)
                            $q2->where('leave_type', 133)
                                ->where(function ($q3) use ($year) {
                                    $q3->whereYear('od_start_date', $year)
                                        ->orWhereYear('start_date', $year);
                                });
                        });
                });
            }

            $statusCounts = $countQuery->groupBy('leave_status')->pluck('count', 'leave_status');

            // ✅ Normalize counts - combine APPROVE and APPROVED
            $approvedCount = ($statusCounts->get('APPROVE', 0));
            $rejectedCount = $statusCounts->get('REJECT', 0);
            $initiatedCount = $statusCounts->get('INITIATED', 0);
            $partiallyApprovedCount = $statusCounts->get('PARTIALLY_APPROVED', 0);
            $pendingCount = $statusCounts->get('PENDING', 0);

            Log::info('Status counts - Approved: ' . $approvedCount . ', Rejected: ' . $rejectedCount . ', Initiated: ' . $initiatedCount);

            // ✅ Query database for leaves - use start_date_time for permissions, start_date for others
            $query = DB::table('hr_leaves_t')
                ->select(
                    DB::raw("COALESCE(YEAR(hr_leaves_t.start_date), YEAR(hr_leaves_t.start_date_time), YEAR(hr_leaves_t.od_start_date)) as year"),
                    DB::raw("COALESCE(MONTH(hr_leaves_t.start_date), MONTH(hr_leaves_t.start_date_time), MONTH(hr_leaves_t.od_start_date)) as month"),
                    'hr_leaves_t.leave_id',
                    'hr_leaves_t.leave_type',
                    'leave_type_lookup.lookup_meaning as leave_type_name',
                    'hr_leaves_t.leave_mode',
                    'leave_mode_lookup.lookup_meaning as leave_mode_name',
                    'hr_leaves_t.no_of_days',
                    'hr_leaves_t.no_of_hrs',
                    'hr_leaves_t.start_date',
                    'hr_leaves_t.end_date',
                    'hr_leaves_t.start_date_time',
                    'hr_leaves_t.end_date_time',
                    'hr_leaves_t.od_start_date',
                    'hr_leaves_t.od_end_date',
                    'hr_leaves_t.od_no_of_days',
                    'hr_leaves_t.leave_reason',
                    'hr_leaves_t.leave_status'
                )
                ->leftJoin('a_lookuplines_t as leave_type_lookup', 'leave_type_lookup.lookuplines_id', '=', 'hr_leaves_t.leave_type')
                ->leftJoin('a_lookuplines_t as leave_mode_lookup', 'leave_mode_lookup.lookuplines_id', '=', 'hr_leaves_t.leave_mode')
                ->where('hr_leaves_t.employee_id', $employee_id);

            // ✅ Apply status filter only if provided
            if (!empty($statuses)) {
                $query->whereIn('hr_leaves_t.leave_status', $statuses);
            }

            // ✅ Apply year filter (now includes OD and Permission dates)
            // Only apply year filter if year is provided; otherwise get all years
            if ($year !== null) {
                $query->where(function ($q) use ($year) {
                    $q->where(function ($q2) use ($year) {
                        // Regular leaves: check start_date and end_date
                        $q2->whereYear('hr_leaves_t.start_date', $year)
                            ->orWhereYear('hr_leaves_t.end_date', $year);
                    })
                        ->orWhere(function ($q2) use ($year) {
                            // Permission leaves (265): check start_date_time
                            $q2->whereYear('hr_leaves_t.start_date_time', $year);
                        })
                        ->orWhere(function ($q2) use ($year) {
                            // OD leaves (133): check both od_start_date (half-day) and start_date (full-day)
                            $q2->where('hr_leaves_t.leave_type', 133)
                                ->where(function ($q3) use ($year) {
                                    $q3->whereYear('hr_leaves_t.od_start_date', $year)
                                        ->orWhereYear('hr_leaves_t.start_date', $year);
                                });
                        });
                });
            }

            $leaveData = $query->orderBy('hr_leaves_t.start_date', 'DESC')
                ->orderBy('hr_leaves_t.start_date_time', 'DESC')
                ->orderBy('hr_leaves_t.od_start_date', 'DESC')
                ->get();

            Log::info('Total leaves found: ' . $leaveData->count());

            // ✅ Group leaves by year -> month hierarchy
            $yearlyData = [];

            foreach ($leaveData as $leave) {
                // Now year and month should always have values (from start_date, start_date_time, or od_start_date)
                $leaveYear = $leave->year;
                $leaveMonth = $leave->month;

                // Skip if still null (shouldn't happen now with COALESCE)
                if (is_null($leaveYear) || is_null($leaveMonth)) {
                    Log::warning('Leave with null year/month found: ' . $leave->leave_id);
                    continue;
                }

                // ✅ Initialize year if not exists
                if (!isset($yearlyData[$leaveYear])) {
                    $yearlyData[$leaveYear] = [];
                }

                // ✅ Initialize month if not exists
                if (!isset($yearlyData[$leaveYear][$leaveMonth])) {
                    $yearlyData[$leaveYear][$leaveMonth] = [
                        'total' => 0,
                        'unit' => 'days',
                        'rows' => [],
                        'permission_quota' => null // Will be populated for permission leaves
                    ];
                }

                // ✅ Calculate duration (days, hours, or OD days)
                $duration = 0;
                $displayDuration = 0;

                // Determine duration based on leave type
                if ($leave->leave_type == 265) {
                    // Permission: uses no_of_hrs
                    $duration = floatval($leave->no_of_hrs ?? 0);
                    $displayDuration = $duration;
                } elseif ($leave->leave_type == 133) {
                    // OD: use no_of_days if present (full-day), otherwise od_no_of_days (half-day)
                    $duration = floatval($leave->no_of_days ?? $leave->od_no_of_days ?? 0);
                    $displayDuration = $duration;
                } else {
                    // Regular leaves: uses no_of_days
                    $duration = floatval($leave->no_of_days ?? 0);
                    $displayDuration = $duration;
                }

                $yearlyData[$leaveYear][$leaveMonth]['total'] += $displayDuration;

                // ✅ Add leave details to month with all relevant fields
                $rowData = [
                    'leave_id' => $leave->leave_id,
                    'leave_type' => $leave->leave_type,
                    'leave_type_name' => $leave->leave_type_name,
                    'leave_mode' => $leave->leave_mode,
                    'leave_mode_name' => $leave->leave_mode_name,
                    'leave_reason' => $leave->leave_reason,
                    'leave_status' => $leave->leave_status,
                ];

                // Add date fields based on leave type
                if ($leave->leave_type == 265) {
                    // Permission
                    $rowData['start_date'] = $leave->start_date_time;
                    $rowData['end_date'] = $leave->end_date_time;
                    $rowData['duration'] = $leave->no_of_hrs;
                    $rowData['unit'] = 'hours';
                } elseif ($leave->leave_type == 133) {
                    // OD - prefer full-day fields when present, otherwise use od_* fields
                    $rowData['start_date'] = $leave->start_date ?? $leave->od_start_date;
                    $rowData['end_date'] = $leave->end_date ?? $leave->od_end_date;
                    $rowData['duration'] = $leave->no_of_days ?? $leave->od_no_of_days;
                    $rowData['unit'] = $leave->leave_mode == 134 ? 'hours' : 'days';
                    $rowData['od_mode'] = $leave->leave_mode == 134 ? 'half-day' : 'full-day';
                } else {
                    // Regular leaves
                    $rowData['start_date'] = $leave->start_date;
                    $rowData['end_date'] = $leave->end_date;
                    $rowData['duration'] = $leave->no_of_days;
                    $rowData['unit'] = 'days';
                }

                $yearlyData[$leaveYear][$leaveMonth]['rows'][] = $rowData;
            }

            // ✅ Convert to proper nested structure and sort by year/month descending
            $formattedYears = [];

            // Sort years descending (most recent first)
            krsort($yearlyData);

            foreach ($yearlyData as $yearKey => $months) {
                $formattedMonths = [];

                // Sort months descending (most recent first)
                krsort($months);

                foreach ($months as $monthKey => $data) {
                    // ✅ Calculate permission quota for this month (if needed)
                    if (!empty($data['rows'])) {
                        // Check if any row is a permission (leave_type == 265)
                        $hasPermission = false;
                        $hasOD = false;
                        $odDetails = [
                            'total_halfday' => 0,
                            'total_fullday' => 0,
                            'halfday_count' => 0,
                            'fullday_count' => 0,
                            'entries' => []
                        ];

                        foreach ($data['rows'] as $row) {
                            if ($row['leave_type'] == 265) {
                                $hasPermission = true;
                            }
                            if ($row['leave_type'] == 133) {
                                $hasOD = true;
                                // Track OD details by mode
                                if ($row['leave_mode'] == 134) {
                                    // Half-day OD (hours)
                                    $odDetails['total_halfday'] += floatval($row['duration'] ?? 0);
                                    $odDetails['halfday_count']++;
                                } elseif ($row['leave_mode'] == 135) {
                                    // Full-day OD
                                    $odDetails['total_fullday'] += floatval($row['duration'] ?? 0);
                                    $odDetails['fullday_count']++;
                                }
                                $odDetails['entries'][] = [
                                    'leave_id' => $row['leave_id'],
                                    'mode' => $row['od_mode'],
                                    'duration' => $row['duration'],
                                    'unit' => $row['unit'],
                                    'status' => $row['leave_status']
                                ];
                            }
                        }

                        // If this month has permission leaves, add quota info
                        if ($hasPermission) {
                            $monthString = str_pad($monthKey, 2, '0', STR_PAD_LEFT);
                            $monthKey_str = $yearKey . '-' . $monthString;

                            // Get permission quota for this month
                            $permissionsInMonth = DB::table('hr_leaves_t')
                                ->where('employee_id', $employee_id)
                                ->where('leave_type', 265) // Permission
                                ->where('leave_status', '!=', 'REJECT')
                                ->where(DB::raw("DATE_FORMAT(start_date_time, '%Y-%m')"), '=', $monthKey_str)
                                ->count();

                            $hoursInMonth = DB::table('hr_leaves_t')
                                ->where('employee_id', $employee_id)
                                ->where('leave_type', 265)
                                ->where('leave_status', '!=', 'REJECT')
                                ->where(DB::raw("DATE_FORMAT(start_date_time, '%Y-%m')"), '=', $monthKey_str)
                                ->sum(DB::raw('CAST(no_of_hrs AS DECIMAL(5,2))'));

                            $hoursInMonth = floatval($hoursInMonth ?? 0);

                            $data['permission_quota'] = [
                                'requests_used' => $permissionsInMonth,
                                'requests_remaining' => max(0, 2 - $permissionsInMonth),
                                'hours_used' => round($hoursInMonth, 2),
                                'hours_remaining' => round(max(0, 3.0 - $hoursInMonth), 2),
                                'total_requests_limit' => 2,
                                'total_hours_limit' => 3.0
                            ];

                            Log::info('Permission quota calculated for month ' . $monthKey_str, [
                                'requests' => $permissionsInMonth,
                                'hours' => round($hoursInMonth, 2)
                            ]);
                        }

                        // Add OD details if this month has OD
                        if ($hasOD) {
                            $data['od_details'] = $odDetails;

                            Log::info('OD details calculated for month ' . $yearKey . '-' . str_pad($monthKey, 2, '0', STR_PAD_LEFT), [
                                'halfday_total_hours' => $odDetails['total_halfday'],
                                'fullday_total_days' => $odDetails['total_fullday'],
                                'halfday_count' => $odDetails['halfday_count'],
                                'fullday_count' => $odDetails['fullday_count']
                            ]);
                        }
                    }

                    $formattedMonths[(string)$monthKey] = $data;
                }
                $formattedYears[(string)$yearKey] = [
                    'months' => $formattedMonths
                ];
            }

            Log::info('Years found: ' . json_encode(array_keys($formattedYears)));

            // ✅ Return successful response with status counts always included
            $response = [
                'success' => true,
                'data' => [
                    'approved_count' => $approvedCount,
                    'rejected_count' => $rejectedCount,
                    'initiated_count' => $initiatedCount,
                    'partially_approved_count' => $partiallyApprovedCount,
                    'pending_count' => $pendingCount,
                    'statuses_requested' => $statusFilter,
                    'total_leaves_in_filter' => $leaveData->count(),
                    'years' => $formattedYears
                ]
            ];

            Log::info('=== getEmployeeLeaveHistory SUCCESS ===');

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('=== getEmployeeLeaveHistory ERROR ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to load leave history: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get leave approver (reporting manager) for an employee
     * GET /api/leave/approver?employee_id={id}
     * Returns: { status: true, approver: { id, name, employee_number } }
     */
    public function getLeaveApprover(Request $request)
    {
        try {
            $employee_id = $request->input('employee_id');

            if (!$employee_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'employee_id is required'
                ], 422);
            }

            // Get employee's reporting manager
            $employee = DB::table('hr_employee_t')
                ->where('employee_id', $employee_id)
                ->first();

            if (!$employee) {
                return response()->json([
                    'status' => false,
                    'message' => 'Employee not found'
                ], 404);
            }

            // Get reporting manager details
            $approver = null;
            if ($employee->reporting_id) {
                $approver = DB::table('hr_employee_t')
                    ->where('employee_id', $employee->reporting_id)
                    ->select('employee_id as id', 'first_name', 'employee_number')
                    ->first();
            }

            if (!$approver) {
                return response()->json([
                    'status' => false,
                    'message' => 'No reporting manager found for this employee'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'approver' => [
                    'id' => $approver->id,
                    'name' => $approver->first_name,
                    'employee_number' => $approver->employee_number
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get Leave Approver Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving approver: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Permission Quota for a specific month
     * GET /api/employee/{id}/permission-quota?month=YYYY-MM
     * Returns: Remaining permission requests, hours, and breakdown
     */
    public function getPermissionQuota(Request $request, $employee_id)
    {
        try {
            Log::info('=== getPermissionQuota START ===');
            Log::info('Employee ID: ' . $employee_id);

            $month = $request->query('month', date('Y-m'));
            if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
                return response()->json(['success' => false, 'message' => 'Invalid month format. Use YYYY-MM'], 400);
            }

            // Helper: H.MM to minutes (1.30 = 90)
            $toMinutes = function ($t) {
                $t = strval($t);
                if (strpos($t, '.') !== false) {
                    list($h, $m) = explode('.', $t);
                    return (intval($h) * 60) + intval($m);
                }
                return intval($t) * 60;
            };

            // Helper: minutes to H.MM (90 = 1.30)
            $toHourMin = function ($min) {
                return intdiv($min, 60) . '.' . str_pad($min % 60, 2, '0', STR_PAD_LEFT);
            };

            $leaves = DB::table('hr_leaves_t')
                ->select('leave_id', 'start_date_time', 'end_date_time', 'no_of_hrs', 'leave_status', 'leave_mode', 'leave_reason')
                ->leftJoin('a_lookuplines_t as leave_mode_lookup', 'leave_mode_lookup.lookuplines_id', '=', 'hr_leaves_t.leave_mode')
                ->where('employee_id', $employee_id)
                ->where('leave_type', 265)
                ->where('leave_status', '!=', 'REJECT')
                ->where(DB::raw("DATE_FORMAT(start_date_time, '%Y-%m')"), '=', $month)
                ->orderBy('start_date_time', 'DESC')
                ->get();

            $totalMin = $approvedMin = $initiatedMin = 0;
            $reqCount = $appCount = $initCount = 0;
            $breakdown = [];

            foreach ($leaves as $leave) {
                $min = $toMinutes($leave->no_of_hrs ?? 0);
                $totalMin += $min;
                $reqCount++;

                if ($leave->leave_status === 'APPROVE') {
                    $approvedMin += $min;
                    $appCount++;
                } elseif ($leave->leave_status === 'INITIATED') {
                    $initiatedMin += $min;
                    $initCount++;
                }

                $breakdown[] = [
                    'leave_id' => $leave->leave_id,
                    'start_date_time' => $leave->start_date_time,
                    'end_date_time' => $leave->end_date_time,
                    'no_of_hrs' => $leave->no_of_hrs,
                    'minutes' => $min,
                    'leave_status' => $leave->leave_status,
                    'leave_mode_name' => $leave->lookup_meaning ?? 'N/A',
                    'leave_reason' => $leave->leave_reason
                ];
            }

            $remainingReq = max(0, 2 - $reqCount);
            $remainingMin = max(0, 180 - $totalMin);

            Log::info('Permission quota', [
                'month' => $month,
                'total_min' => $totalMin,
                'total_hrs' => $toHourMin($totalMin),
                'remaining_min' => $remainingMin,
                'remaining_hrs' => $toHourMin($remainingMin)
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'month' => $month,
                    'permission_requests' => [
                        'total_submitted' => $reqCount,
                        'total_approved' => $appCount,
                        'total_initiated' => $initCount,
                        'total_rejected' => 0,
                        'quota_limit' => 2,
                        'remaining' => $remainingReq,
                        'can_submit' => $remainingReq > 0
                    ],
                    'permission_hours' => [
                        'total_submitted' => $toHourMin($totalMin),
                        'total_approved' => $toHourMin($approvedMin),
                        'total_initiated' => $toHourMin($initiatedMin),
                        'total_submitted_minutes' => $totalMin,
                        'total_approved_minutes' => $approvedMin,
                        'total_initiated_minutes' => $initiatedMin,
                        'quota_limit_hours' => '3.00',
                        'quota_limit_minutes' => 180,
                        'remaining_hours' => $toHourMin($remainingMin),
                        'remaining_minutes' => $remainingMin,
                        'can_submit' => $remainingMin > 0
                    ],
                    'valid_hour_options' => ['0.30', '1.00', '1.30'],
                    'permission_breakdown' => $breakdown
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('=== getPermissionQuota ERROR ===');
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get OD (Over Duty) Details for an employee
     * GET /api/employee/{id}/od-details?year=2026
     * Returns: Monthly OD tracking and comp-off eligibility
     */
    public function getODDetails(Request $request, $employee_id)
    {
        try {
            Log::info('=== getODDetails START ===');
            Log::info('Employee ID: ' . $employee_id);

            $year = $request->query('year', date('Y'));

            // Get OD entries for the specified year
            $odEntries = DB::table('hr_leaves_t')
                ->select(
                    'leave_id',
                    'od_start_date',
                    'od_end_date',
                    'od_no_of_days',
                    'od_alloted_days',
                    'leave_status',
                    'leave_reason',
                    'created_at'
                )
                ->where('employee_id', $employee_id)
                ->where('leave_type', 277) // Comp-off (related to OD)
                ->whereYear('od_start_date', $year)
                ->orderBy('od_start_date', 'DESC')
                ->get();

            // Calculate totals and group by month
            $totalODDays = 0;
            $monthlyOD = [];

            foreach ($odEntries as $entry) {
                $odDays = floatval($entry->od_no_of_days ?? 0);
                $totalODDays += $odDays;

                if ($entry->od_start_date) {
                    $monthKey = date('Y-m', strtotime($entry->od_start_date));

                    if (!isset($monthlyOD[$monthKey])) {
                        $monthlyOD[$monthKey] = [
                            'total_od_hours' => 0,
                            'od_entries' => []
                        ];
                    }

                    $monthlyOD[$monthKey]['total_od_hours'] += $odDays;
                    $monthlyOD[$monthKey]['od_entries'][] = [
                        'leave_id' => $entry->leave_id,
                        'od_start_date' => $entry->od_start_date,
                        'od_end_date' => $entry->od_end_date,
                        'od_no_of_days' => $entry->od_no_of_days,
                        'od_alloted_days' => $entry->od_alloted_days,
                        'leave_status' => $entry->leave_status,
                        'leave_reason' => $entry->leave_reason
                    ];
                }
            }

            // Sort months descending
            krsort($monthlyOD);

            // Calculate comp-off eligibility (1 comp-off per day of OD)
            $compOffEligible = floor($totalODDays);

            Log::info('OD details calculated', [
                'year' => $year,
                'total_od_days' => $totalODDays,
                'comp_off_eligible' => $compOffEligible
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'year' => $year,
                    'total_od_days' => round($totalODDays, 2),
                    'comp_off_eligible' => $compOffEligible,
                    'monthly_od' => $monthlyOD
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('=== getODDetails ERROR ===');
            Log::error('Error message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching OD details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dates not eligible for leave for a given year (holidays + Sundays + 2nd Saturdays)
     * GET /api/leave/ineligible-dates?year=YYYY&company_id=&location_id=
     * Returns list of dates (YYYY-MM-DD) and optional details for React Native datepicker
     */
    public function getIneligibleLeaveDates(Request $request)
    {
        try {
            $year = $request->query('year', date('Y'));
            $company_id = $request->query('company_id');

            Log::info('Fetching ineligible leave dates', ['year' => $year, 'company_id' => $company_id]);

            // Fetch holidays from hr_holiday_t for the given year (active holidays only)
            $holidaysQuery = DB::table('hr_holiday_t')
                ->select('date', 'holiday_name')
                ->whereYear('date', $year)
                ->where(function ($q) {
                    $q->where('active', 'Yes')
                        ->orWhere('active', 'Y')
                        ->orWhereNull('active');
                });

            if ($company_id) {
                $holidaysQuery->where('company_id', $company_id);
            }

            $holidays = $holidaysQuery->get();

            $ineligible = []; // keyed by date string => ['date' => ..., 'reasons' => [...], 'name' => ...]

            // Add holidays
            foreach ($holidays as $h) {
                $d = date('Y-m-d', strtotime($h->date));
                if (!isset($ineligible[$d])) {
                    $ineligible[$d] = ['date' => $d, 'reasons' => [], 'names' => []];
                }
                $ineligible[$d]['reasons'][] = 'HOLIDAY';
                $ineligible[$d]['names'][] = $h->holiday_name ?? 'Holiday';
            }

            // Generate every date of the year and mark Sundays
            $start = new \DateTime("$year-01-01");
            $end = new \DateTime("$year-12-31");
            $interval = new \DateInterval('P1D');
            $period = new \DatePeriod($start, $interval, $end->add(new \DateInterval('P1D')));

            foreach ($period as $dt) {
                $d = $dt->format('Y-m-d');
                // Sunday (w = 0)
                if ($dt->format('w') === '0') {
                    if (!isset($ineligible[$d])) {
                        $ineligible[$d] = ['date' => $d, 'reasons' => [], 'names' => []];
                    }
                    $ineligible[$d]['reasons'][] = 'SUNDAY';
                }
            }

            // Add 2nd Saturday of each month
            for ($m = 1; $m <= 12; $m++) {
                $monthStr = str_pad($m, 2, '0', STR_PAD_LEFT);
                $firstOfMonth = new \DateTime("$year-{$monthStr}-01");
                $dayOfWeek = (int)$firstOfMonth->format('w'); // 0 Sun .. 6 Sat

                // days to add to reach first Saturday
                $daysToFirstSaturday = ($dayOfWeek <= 6) ? (6 - $dayOfWeek) : 0; // usually 0..6
                $firstSaturday = (clone $firstOfMonth)->add(new \DateInterval('P' . $daysToFirstSaturday . 'D'));
                $secondSaturday = (clone $firstSaturday)->add(new \DateInterval('P7D'));

                $d = $secondSaturday->format('Y-m-d');
                if (!isset($ineligible[$d])) {
                    $ineligible[$d] = ['date' => $d, 'reasons' => [], 'names' => []];
                }
                $ineligible[$d]['reasons'][] = '2ND_SATURDAY';
            }

            // Prepare response arrays
            $dates = array_keys($ineligible);
            sort($dates);

            $details = array_values(array_map(function ($v) {
                // dedupe reasons/names
                $v['reasons'] = array_values(array_unique($v['reasons']));
                $v['names'] = array_values(array_unique($v['names']));
                return $v;
            }, $ineligible));

            Log::info('Ineligible dates computed', ['count' => count($dates)]);

            return response()->json([
                'success' => true,
                'year' => $year,
                'count' => count($dates),
                'dates' => $dates, // simple array for datepicker
                'details' => $details // optional richer detail
            ]);
        } catch (\Exception $e) {
            Log::error('=== getIneligibleLeaveDates ERROR ===');
            Log::error('Error message: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching ineligible dates: ' . $e->getMessage()
            ], 500);
        }
    }
}
