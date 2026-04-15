<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * GET /api/employee/{id}/dashboard
     *
     * Returns:
     *  - leave_summary  : total / used / balance (CL + EL + comp-off only)
     *  - attendance_by_month : keyed by 'YYYY-MM', each entry has label + days[]
     *    - days[]: date, status, color, in_time, out_time, working_hours, leave_type,
     *              in_time_hours (0-24 float), out_time_hours (0-24 float)
     *
     * Query params:
     *  - from  (Y-m-d, default: start of current year)
     *  - to    (Y-m-d, default: today)
     */
    public function employeeDashboard(Request $request, $employee_id)
    {
        $user = auth()->user();
        if (!$user || !$user->employee_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Only allow employees to view their own dashboard, or admins
        if ((int)$user->employee_id !== (int)$employee_id) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $from  = $request->input('from', Carbon::now()->startOfYear()->toDateString());
        $to    = $request->input('to',   Carbon::now()->toDateString());

        // Clamp range to valid dates
        $from = Carbon::parse($from)->toDateString();
        $to   = Carbon::parse($to)->toDateString();

        try {
            // -------------------------------------------------------
            // 1. Leave balance raw row
            // -------------------------------------------------------
            $bal = DB::table('Leave_balance_tbl')
                ->where('employee_id', $employee_id)
                ->first();

            $totalCL      = (float)($bal->ocl            ?? 0);
            $totalEL      = (float)($bal->earn_leave      ?? 0);
            $totalCompOff = (float)($bal->comp_off_leave  ?? 0);

            // Total allocated (CL + EL + Comp-off only, per requirement)
            $leaveTotalAllocated = $totalCL + $totalEL + $totalCompOff;

            // Used = approved leaves for CL(130), EL(132), Comp-off(277) this year
            $startOfYear = Carbon::now()->startOfYear()->toDateString();
            $today       = Carbon::now()->toDateString();

            $usedRows = DB::table('hr_leaves_t')
                ->selectRaw('leave_type, SUM(alloted_days) as used_days')
                ->where('employee_id', $employee_id)
                ->whereIn('leave_type', [130, 132, 277])
                ->where('leave_status', 'APPROVE')
                ->whereDate('start_date', '>=', $startOfYear)
                ->whereDate('start_date', '<=', $today)
                ->groupBy('leave_type')
                ->get()
                ->keyBy('leave_type');

            $usedCL      = (float)($usedRows->get(130)->used_days ?? 0);
            $usedEL      = (float)($usedRows->get(132)->used_days ?? 0);
            $usedCompOff = (float)($usedRows->get(277)->used_days ?? 0);
            $totalUsed   = $usedCL + $usedEL + $usedCompOff;
            $balance     = max(0, $leaveTotalAllocated - $totalUsed);

            // -------------------------------------------------------
            // 2. Employee biometric number for attendance lookup
            // -------------------------------------------------------
            $emp = DB::table('hr_employee_t')
                ->where('employee_id', $employee_id)
                ->first(['employee_number', 'company_id']);

            $empNumber = $emp->employee_number ?? null;

            // -------------------------------------------------------
            // 3. Attendance records keyed by date
            // -------------------------------------------------------
            $attendance = DB::table('hr_emp_attendence')
                ->select('atten_date', 'check_in', 'check_out')
                ->where('emp_id', $empNumber)
                ->whereBetween('atten_date', [$from, $to])
                ->get()
                ->keyBy(fn($r) => Carbon::parse($r->atten_date)->toDateString());

            // -------------------------------------------------------
            // 4. Approved leave ranges in period
            // -------------------------------------------------------
            $leaves = DB::table('hr_leaves_t as l')
                ->leftJoin('a_lookuplines_t as a', 'a.lookuplines_id', '=', 'l.leave_type')
                ->select('l.start_date', 'l.end_date', 'l.leave_mode', 'a.lookup_code')
                ->where('l.employee_id', $employee_id)
                ->where('l.leave_status', 'APPROVE')
                ->where(function ($q) use ($from, $to) {
                    $q->whereBetween('l.start_date', [$from, $to])
                        ->orWhereBetween('l.end_date', [$from, $to])
                        ->orWhere(function ($qq) use ($from, $to) {
                            $qq->where('l.start_date', '<=', $from)
                                ->where('l.end_date', '>=', $to);
                        });
                })
                ->get();

            $leaveDates = [];
            foreach ($leaves as $lv) {
                $code = strtoupper($lv->lookup_code);
                $half = ($lv->leave_mode == 134);
                $lvPeriod = CarbonPeriod::create(
                    Carbon::parse($lv->start_date),
                    Carbon::parse($lv->end_date)
                );
                foreach ($lvPeriod as $d) {
                    $leaveDates[$d->toDateString()] = ['code' => $code, 'half' => $half];
                }
            }

            // -------------------------------------------------------
            // 5. Holidays
            // -------------------------------------------------------
            $holidays = DB::table('hr_holiday_t')
                ->whereBetween('date', [$from, $to])
                ->where('company_id', $emp->company_id ?? 1)
                ->where('active', 'Yes')
                ->get()
                ->keyBy('date');

            // -------------------------------------------------------
            // 6. Color map (mirrors web colour scheme)
            // -------------------------------------------------------
            $colorMap = [
                'P'     => '#198754',
                'P(H)'  => '#198754',
                'P(OD)' => '#198754',
                'A'     => '#dc3545',
                'WO'    => '#0d6efd',
                'H'     => '#6c757d',
                'CL'    => '#ffc107',
                'SL'    => '#ffc107',
                'EL'    => '#ffc107',
                'LOP'   => '#fd7e14',
                'C-OFF' => '#0dcaf0',
                ''      => '#adb5bd',
            ];

            // -------------------------------------------------------
            // 7. Iterate every day and build event list
            // -------------------------------------------------------
            $period = CarbonPeriod::create(Carbon::parse($from), Carbon::parse($to));
            $attendanceByMonth = [];

            foreach ($period as $date) {
                $d      = $date->toDateString();
                $att    = $attendance->get($d);
                $status = '';
                $leaveType = null;
                $inTime = $outTime = $workingHours = null;
                $inTimeHours  = null; // float 0-24 for bar chart
                $outTimeHours = null;

                if ($att) {
                    $checkIn  = $att->check_in;
                    $checkOut = $att->check_out;

                    if ($checkIn && $checkOut) {
                        $status = 'P';
                        $start  = Carbon::parse($checkIn);
                        $end    = Carbon::parse($checkOut);
                        $workingHours  = $start->diffInHours($end) . ' hrs ' . $start->diff($end)->format('%I mins');
                        $inTime        = $start->format('h:i A');
                        $outTime       = $end->format('h:i A');
                        $inTimeHours   = $start->hour + ($start->minute / 60);
                        $outTimeHours  = $end->hour   + ($end->minute   / 60);
                    } else {
                        $status = 'A';
                    }

                    if (isset($leaveDates[$d]) && $leaveDates[$d]['half']) {
                        $code = $leaveDates[$d]['code'];
                        $leaveType = $code;
                        $status = ($code === 'ON-DUTY') ? 'P(OD)' : 'P(H)';
                    }
                } else {
                    if (isset($leaveDates[$d])) {
                        $code      = $leaveDates[$d]['code'];
                        $leaveType = $code;

                        if ($code === 'ON-DUTY') {
                            $status = 'P(OD)';
                        } elseif ($code === 'CASUAL LEAVE') {
                            $status = 'CL';
                        } elseif ($code === 'SICK LEAVE') {
                            $status = 'SL';
                        } elseif ($code === 'EARN LEAVE') {
                            $status = 'EL';
                        } elseif ($code === 'COMP-OFF') {
                            $status = 'C-OFF';
                        } elseif (stripos($code, 'WITHOUT PAY') !== false || $code === 'LWP' || $code === 'LOP') {
                            $status = 'LOP';
                        } else {
                            $status = $code;
                        }

                        if ($leaveDates[$d]['half'] && $status === 'P') {
                            $status = 'P(H)';
                        }
                    } elseif ($holidays->has($d)) {
                        $status = 'H';
                    } elseif (strtoupper($date->format('D')) === 'SUN') {
                        $status = 'WO';
                    } else {
                        $status = 'A';
                    }
                }

                $monthKey   = $date->format('Y-m');
                $monthLabel = $date->format('M Y'); // e.g. "Jan 2026"

                if (!isset($attendanceByMonth[$monthKey])) {
                    $attendanceByMonth[$monthKey] = [
                        'label' => $monthLabel,
                        'days'  => [],
                    ];
                }

                $attendanceByMonth[$monthKey]['days'][] = [
                    'date'          => $d,
                    'day'           => $date->format('D'), // Mon, Tue …
                    'status'        => $status,
                    'color'         => $colorMap[$status] ?? '#adb5bd',
                    'in_time'       => $inTime,
                    'out_time'      => $outTime,
                    'working_hours' => $workingHours,
                    'leave_type'    => $leaveType,
                    'in_time_hours' => $inTimeHours,
                    'out_time_hours' => $outTimeHours,
                ];
            }

            return response()->json([
                'success' => true,
                'leave_summary' => [
                    'total'   => (int)$leaveTotalAllocated,
                    'used'    => (int)$totalUsed,
                    'balance' => (int)$balance,
                    'breakdown' => [
                        'cl'       => ['total' => (int)$totalCL,      'used' => (int)$usedCL],
                        'el'       => ['total' => (int)$totalEL,      'used' => (int)$usedEL],
                        'comp_off' => ['total' => (int)$totalCompOff, 'used' => (int)$usedCompOff],
                    ],
                ],
                'attendance_by_month' => $attendanceByMonth,
                'color_map'           => $colorMap,
            ]);
        } catch (\Exception $e) {
            Log::error('DashboardController@employeeDashboard error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard data.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
