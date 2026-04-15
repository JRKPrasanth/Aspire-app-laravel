<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class JobCardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // -------------------------------------------------------------------------
    // GET /api/jobcards/approved-plans
    // Returns approved production plans that have not yet had a job card created
    // Query param: type = 'production' | 'packing' | '' (all)
    // -------------------------------------------------------------------------
    public function getApprovedPlans(Request $request)
    {
        try {
            $type   = $request->input('type', '');   // 'production' | 'packing' | ''
            $search = trim($request->input('search', ''));

            $query = DB::table('w_productionplan_hdr_t as ph')
                ->leftJoin('m_products_t as mp', 'mp.product_id', '=', 'ph.product_id')
                ->leftJoin('m_uom_codes_t as um', 'um.uom_code_id', '=', 'ph.uom_code_id')
                ->leftJoin('m_product_groups_t as grp', 'grp.product_group_id', '=', 'mp.product_group_id')
                ->select(
                    'ph.productionplan_hdr_id',
                    'ph.plan_no',
                    'ph.plan_date',
                    'ph.start_date',
                    'ph.end_date',
                    'ph.plan_status',
                    'ph.production_qty',
                    'ph.pending_qty',
                    'ph.remarks',
                    'ph.reference_no',
                    'ph.batch_no',
                    'ph.job_card_status',
                    'mp.product_code',
                    'mp.concatenated_product',
                    'um.uom_code',
                    'grp.group_name'
                )
                ->where('ph.plan_status', 'APPROVED');

            // Filter by plan type: FINISHED GOODS = packing, SEMI FINISHED GOODS = production
            if ($type === 'packing') {
                $query->where('grp.group_name', 'FINISHED GOODS');
            } elseif ($type === 'production') {
                $query->where('grp.group_name', 'SEMI FINISHED GOODS');
            }

            // Employee product restriction
            $productIds = $this->getEmployeeProductIds();
            if ($productIds !== null) {
                if (!empty($productIds)) {
                    $query->whereIn('ph.product_id', $productIds);
                } else {
                    $query->whereRaw('0 = 1');
                }
            }

            if ($search !== '') {
                $like = "%{$search}%";
                $query->where(function ($q) use ($like) {
                    $q->where('ph.plan_no', 'like', $like)
                        ->orWhere('ph.reference_no', 'like', $like)
                        ->orWhere('mp.concatenated_product', 'like', $like);
                });
            }

            $plans = $query->orderBy('ph.productionplan_hdr_id', 'desc')->get();

            return response()->json(['success' => true, 'data' => $plans]);
        } catch (\Exception $e) {
            Log::error('JobCardController@getApprovedPlans: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch approved plans'], 500);
        }
    }

    // -------------------------------------------------------------------------
    // GET /api/jobcards
    // List job cards with optional type filter
    // Query params: page, per_page, search, type (production|packing|all)
    // -------------------------------------------------------------------------
    public function index(Request $request)
    {
        try {
            $perPage = (int) $request->input('per_page', 20);
            $page    = max(1, (int) $request->input('page', 1));
            $offset  = ($page - 1) * $perPage;
            $search  = trim($request->input('search', ''));
            $type    = $request->input('type', '');  // 'production' | 'packing' | ''

            $query = DB::table('w_jobcard_hdr_t as jc')
                ->leftJoin('m_products_t as mp', 'mp.product_id', '=', 'jc.product_id')
                ->leftJoin('m_product_groups_t as grp', 'grp.product_group_id', '=', 'mp.product_group_id')
                ->leftJoin('m_uom_codes_t as um', 'um.uom_code_id', '=', 'jc.uom_code_id')
                ->leftJoin('w_productionplan_hdr_t as ph', 'ph.productionplan_hdr_id', '=', 'jc.reference_source_id')
                ->select(
                    'jc.w_jobs_hdr_id',
                    'jc.job_no',
                    'jc.job_date',
                    'jc.job_completion_date',
                    'jc.job_status',
                    'jc.job_qty',
                    'jc.job_adjusted_qty',
                    'jc.job_process',
                    'jc.remarks',
                    'jc.batch_no',
                    'jc.reference_source_id',
                    'ph.plan_no',
                    'ph.reference_no as wo_reference_no',
                    'mp.product_code',
                    'mp.concatenated_product',
                    'um.uom_code',
                    'grp.group_name'
                );

            // Type filter: FINISHED GOODS = packing (job_no like JOBOP), SEMI FINISHED GOODS = production (JOBPR)
            if ($type === 'packing') {
                $query->where('grp.group_name', 'FINISHED GOODS');
            } elseif ($type === 'production') {
                $query->where('grp.group_name', 'SEMI FINISHED GOODS');
            }

            // Employee product restriction
            $productIds = $this->getEmployeeProductIds();
            if ($productIds !== null) {
                if (!empty($productIds)) {
                    $query->whereIn('jc.product_id', $productIds);
                } else {
                    $query->whereRaw('0 = 1');
                }
            }

            if ($search !== '') {
                $like = "%{$search}%";
                $query->where(function ($q) use ($like) {
                    $q->where('jc.job_no', 'like', $like)
                        ->orWhere('ph.plan_no', 'like', $like)
                        ->orWhere('mp.concatenated_product', 'like', $like);
                });
            }

            $total = (clone $query)->count();
            $jobs  = $query
                ->orderBy('jc.w_jobs_hdr_id', 'desc')
                ->offset($offset)
                ->limit($perPage)
                ->get();

            return response()->json([
                'success'  => true,
                'data'     => $jobs,
                'total'    => $total,
                'page'     => $page,
                'per_page' => $perPage,
            ]);
        } catch (\Exception $e) {
            Log::error('JobCardController@index: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch job cards'], 500);
        }
    }

    // -------------------------------------------------------------------------
    // GET /api/jobcards/{id}
    // Get one job card with its material lines
    // -------------------------------------------------------------------------
    public function show(int $id)
    {
        try {
            $job = DB::table('w_jobcard_hdr_t as jc')
                ->leftJoin('m_products_t as mp', 'mp.product_id', '=', 'jc.product_id')
                ->leftJoin('m_product_groups_t as grp', 'grp.product_group_id', '=', 'mp.product_group_id')
                ->leftJoin('m_uom_codes_t as um', 'um.uom_code_id', '=', 'jc.uom_code_id')
                ->leftJoin('w_productionplan_hdr_t as ph', 'ph.productionplan_hdr_id', '=', 'jc.reference_source_id')
                ->select(
                    'jc.*',
                    'ph.plan_no',
                    'ph.reference_no as wo_reference_no',
                    'mp.product_code',
                    'mp.concatenated_product',
                    'um.uom_code',
                    'grp.group_name'
                )
                ->where('jc.w_jobs_hdr_id', $id)
                ->first();

            if (!$job) {
                return response()->json(['success' => false, 'message' => 'Job card not found'], 404);
            }

            // Material lines from production plan BOM lines
            $lines = DB::table('w_productionplan_lines_t as pl')
                ->leftJoin('m_products_t as mp', 'mp.product_id', '=', 'pl.product_id')
                ->leftJoin('m_uom_codes_t as um', 'um.uom_code_id', '=', 'pl.uom_code_id')
                ->select(
                    'pl.productionplan_line_id',
                    'pl.product_id',
                    'pl.qty',
                    'pl.component_qty',
                    'pl.qoh',
                    'pl.pending_qty',
                    'pl.process_level',
                    'pl.process_name',
                    'mp.product_code',
                    'mp.concatenated_product',
                    'um.uom_code'
                )
                ->where('pl.productionplan_hdr_id', $job->reference_source_id)
                ->orderBy('pl.productionplan_line_id')
                ->get();

            return response()->json([
                'success' => true,
                'data'    => [
                    'job'   => $job,
                    'lines' => $lines,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('JobCardController@show: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch job card'], 500);
        }
    }

    // -------------------------------------------------------------------------
    // POST /api/jobcards
    // Create a job card from an approved production plan
    // Body: { productionplan_hdr_id, job_date, job_completion_date, remarks? }
    // -------------------------------------------------------------------------
    public function store(Request $request)
    {
        try {
            $request->validate([
                'productionplan_hdr_id' => 'required|integer|min:1',
                'job_date'              => 'required|date_format:Y-m-d',
                'job_completion_date'   => 'required|date_format:Y-m-d',
            ]);

            $user           = Auth::user();
            $companyId      = $user->company_id      ?? 1;
            $organizationId = $user->organization_id ?? 1;
            $locationId     = $user->location_id     ?? 1;
            $userId         = $user->id;

            // Load the plan
            $plan = DB::table('w_productionplan_hdr_t as ph')
                ->leftJoin('m_products_t as mp', 'mp.product_id', '=', 'ph.product_id')
                ->leftJoin('m_product_groups_t as grp', 'grp.product_group_id', '=', 'mp.product_group_id')
                ->select('ph.*', 'grp.group_name')
                ->where('ph.productionplan_hdr_id', $request->input('productionplan_hdr_id'))
                ->first();

            if (!$plan) {
                return response()->json(['success' => false, 'message' => 'Production plan not found'], 404);
            }

            if ($plan->plan_status !== 'APPROVED') {
                return response()->json([
                    'success' => false,
                    'message' => 'This plan is not approved. Current status: ' . $plan->plan_status,
                ], 422);
            }

            if ((int)($plan->job_card_status ?? 0) === 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'A job card has already been created for this plan',
                ], 422);
            }

            // Determine job type by product group
            $isFG    = $plan->group_name === 'FINISHED GOODS';
            $prefix  = $isFG ? 'JOBOP' : 'JOBPR';
            $process = $isFG ? 'PACKING' : 'PRODUCTION';

            // Generate job_no
            $jobNo = $this->generateJobNo($prefix);

            DB::beginTransaction();

            $jobId = DB::table('w_jobcard_hdr_t')->insertGetId([
                'job_no'             => $jobNo,
                'job_date'           => $request->input('job_date'),
                'job_completion_date' => $request->input('job_completion_date'),
                'product_id'         => $plan->product_id,
                'uom_code_id'        => $plan->uom_code_id,
                'reference_source'   => 'PLAN',
                'reference_source_id' => $plan->productionplan_hdr_id,
                'job_qty'            => $plan->production_qty,
                'job_adjusted_qty'   => $plan->production_qty,
                'job_status'         => 'OPEN',
                'job_process'        => $process,
                'batch_no'           => $plan->batch_no ?? '',
                'remarks'            => $request->input('remarks', ''),
                'job_created_by'     => $userId,
                'reworksrc'          => '',
                'kitpack_no'         => '',
                'bom_product_id'     => '',
                'bom_process'        => '',
                'machine_hdr_id'     => 0,
                'machine_capacity'   => 0,
                'hour'               => 0,
                'job_assigned_to'    => '',
                'organization_id'    => $organizationId,
                'company_id'         => $companyId,
                'location_id'        => $locationId,
                'created_at'         => Carbon::now(),
                'updated_at'         => Carbon::now(),
            ]);

            // Mark the production plan as having a job card
            DB::table('w_productionplan_hdr_t')
                ->where('productionplan_hdr_id', $plan->productionplan_hdr_id)
                ->update([
                    'job_card_status' => 1,
                    'updated_at'      => Carbon::now(),
                ]);

            DB::commit();

            return response()->json([
                'success'        => true,
                'message'        => 'Job card created successfully',
                'job_no'         => $jobNo,
                'w_jobs_hdr_id'  => $jobId,
                'job_process'    => $process,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('JobCardController@store: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create job card: ' . $e->getMessage()], 500);
        }
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Get the list of product IDs the authenticated employee is allowed to see.
     * Returns null  = no restriction (management).
     * Returns int[] = restrict to these product IDs (empty = no access).
     */
    private function getEmployeeProductIds(): ?array
    {
        $user       = Auth::user();
        $employeeId = (int)($user->employee_id ?? 0);

        // Management employees: full access
        $managementIds = [151, 152, 160, 676];
        if (in_array($employeeId, $managementIds)) {
            return null;
        }

        if ($employeeId <= 0) {
            return [];
        }

        $productIds = DB::table('productmapping_hdr_tbl as pm')
            ->join('productmapping_lines_tbl as pl', 'pl.productmapping_id', '=', 'pm.productmapping_id')
            ->where('pm.employee_id', $employeeId)
            ->distinct()
            ->pluck('pl.prd_id')
            ->map(fn($v) => (int) $v)
            ->filter(fn($v) => $v > 0)
            ->values()
            ->toArray();

        return $productIds;
    }

    /**
     * Generate the next job number (JOBPR000001 or JOBOP000001 format).
     */
    private function generateJobNo(string $prefix): string
    {
        $latest = DB::table('w_jobcard_hdr_t')
            ->selectRaw("MAX(CAST(SUBSTRING(job_no, " . (strlen($prefix) + 1) . ") AS UNSIGNED)) as max_no")
            ->whereRaw("job_no REGEXP '^{$prefix}[0-9]+'")
            ->first();

        $next = (int)($latest->max_no ?? 0) + 1;
        return $prefix . str_pad($next, 6, '0', STR_PAD_LEFT);
    }
}
