<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MrpController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // -------------------------------------------------------------------------
    // GET /api/mrp/workorders
    // Returns workorders that have at least one unplanned line (plan_status = 0)
    // -------------------------------------------------------------------------
    public function getWorkordersForMrp(Request $request)
    {
        try {
            $perPage = (int) $request->input('per_page', 50);
            $page    = max(1, (int) $request->input('page', 1));
            $offset  = ($page - 1) * $perPage;
            $search  = trim($request->input('search', ''));

            $query = DB::table('w_workorder_hdr_t as wh')
                ->join('w_workorder_lines_t as wl', 'wl.workorder_hdr_id', '=', 'wh.workorder_hdr_id')
                ->leftJoin('m_products_t as mp', 'mp.product_id', '=', 'wl.product_id')
                ->leftJoin('m_uom_codes_t as um', 'um.uom_code_id', '=', 'mp.primary_uom_id')
                ->leftJoin('tb_users as u', 'u.id', '=', 'wh.created_by')
                ->select(
                    'wh.workorder_hdr_id',
                    'wh.workorder_no',
                    'wh.workorder_date',
                    'wh.shift',
                    DB::raw("u.first_name as created_by_name"),
                    DB::raw("GROUP_CONCAT(DISTINCT mp.concatenated_product ORDER BY wl.line_no SEPARATOR ', ') as products"),
                    DB::raw("COUNT(DISTINCT wl.workorder_line_id) as unplanned_lines"),
                    DB::raw("MAX(wl.due_date) as due_date")
                )
                ->where('wl.plan_status', 0)
                ->groupBy(
                    'wh.workorder_hdr_id',
                    'wh.workorder_no',
                    'wh.workorder_date',
                    'wh.shift',
                    'u.first_name'
                );

            if ($search !== '') {
                $like = "%{$search}%";
                $query->where(function ($q) use ($like) {
                    $q->where('wh.workorder_no', 'like', $like)
                        ->orWhere('mp.concatenated_product', 'like', $like);
                });
            }

            // Employee product restriction — only workorders with a mapped product line
            $productIds = $this->getEmployeeProductIds();
            if ($productIds !== null) {
                if (!empty($productIds)) {
                    $query->whereIn('wl.product_id', $productIds);
                } else {
                    $query->whereRaw('0 = 1');
                }
            }

            $total = DB::table('w_workorder_hdr_t as wh')
                ->join('w_workorder_lines_t as wl', 'wl.workorder_hdr_id', '=', 'wh.workorder_hdr_id')
                ->where('wl.plan_status', 0)
                ->distinct('wh.workorder_hdr_id')
                ->count('wh.workorder_hdr_id');

            $workorders = $query
                ->orderBy('wh.workorder_hdr_id', 'desc')
                ->offset($offset)
                ->limit($perPage)
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $workorders,
                'total'   => $total,
                'page'    => $page,
                'per_page' => $perPage,
            ]);
        } catch (\Exception $e) {
            Log::error('MrpController@getWorkordersForMrp: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch workorders'], 500);
        }
    }

    // -------------------------------------------------------------------------
    // GET /api/mrp/workorders/{id}/lines
    // Returns unplanned lines + BOM preview for a specific workorder
    // -------------------------------------------------------------------------
    public function getWorkorderLines(int $id)
    {
        try {
            $workorder = DB::table('w_workorder_hdr_t')
                ->where('workorder_hdr_id', $id)
                ->first();

            if (!$workorder) {
                return response()->json(['success' => false, 'message' => 'Workorder not found'], 404);
            }

            $lines = DB::table('w_workorder_lines_t as wl')
                ->leftJoin('m_products_t as mp', 'mp.product_id', '=', 'wl.product_id')
                ->leftJoin('m_uom_codes_t as um', 'um.uom_code_id', '=', 'mp.primary_uom_id')
                ->leftJoin('m_product_groups_t as grp', 'grp.product_group_id', '=', 'mp.product_group_id')
                ->select(
                    'wl.workorder_line_id',
                    'wl.line_no',
                    'wl.product_id',
                    'wl.qty',
                    'wl.due_date',
                    'wl.plan_status',
                    'mp.product_code',
                    'mp.concatenated_product',
                    'mp.primary_uom_id as uom_code_id',
                    'um.uom_code',
                    'grp.group_name'
                )
                ->where('wl.workorder_hdr_id', $id)
                ->where('wl.plan_status', 0)
                ->orderBy('wl.line_no')
                ->get();

            // Attach BOM preview for each line
            foreach ($lines as $line) {
                $line->bom_components = $this->getBomComponents($line->product_id, (float)$line->qty);
            }

            return response()->json([
                'success'   => true,
                'workorder' => $workorder,
                'lines'     => $lines,
            ]);
        } catch (\Exception $e) {
            Log::error('MrpController@getWorkorderLines: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch workorder lines'], 500);
        }
    }

    // -------------------------------------------------------------------------
    // GET /api/mrp
    // List existing production plans (MRPs already created)
    // -------------------------------------------------------------------------
    public function index(Request $request)
    {
        try {
            $perPage = (int) $request->input('per_page', 20);
            $page    = max(1, (int) $request->input('page', 1));
            $offset  = ($page - 1) * $perPage;
            $search  = trim($request->input('search', ''));
            $status  = trim($request->input('status', ''));   // e.g. 'INITIATED', 'APPROVED', 'REJECTED'
            $type    = trim($request->input('type', ''));     // 'production' | 'packing' | ''

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
                );

            // Filter by plan status (e.g. INITIATED for approval queue)
            if ($status !== '') {
                $query->where('ph.plan_status', strtoupper($status));
            }

            // Filter by product type: FG = packing, SFG = production
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
                    $query->whereRaw('0 = 1'); // No mapped products → empty result
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

            $total = (clone $query)->count();
            $plans = $query
                ->orderBy('ph.productionplan_hdr_id', 'desc')
                ->offset($offset)
                ->limit($perPage)
                ->get();

            return response()->json([
                'success'  => true,
                'data'     => $plans,
                'total'    => $total,
                'page'     => $page,
                'per_page' => $perPage,
            ]);
        } catch (\Exception $e) {
            Log::error('MrpController@index: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch production plans'], 500);
        }
    }

    // -------------------------------------------------------------------------
    // GET /api/mrp/{id}
    // Get one production plan detail + its BOM lines
    // -------------------------------------------------------------------------
    public function show(int $id)
    {
        try {
            $plan = DB::table('w_productionplan_hdr_t as ph')
                ->leftJoin('m_products_t as mp', 'mp.product_id', '=', 'ph.product_id')
                ->leftJoin('m_uom_codes_t as um', 'um.uom_code_id', '=', 'ph.uom_code_id')
                ->leftJoin('m_product_groups_t as grp', 'grp.product_group_id', '=', 'mp.product_group_id')
                ->select(
                    'ph.*',
                    'mp.product_code',
                    'mp.concatenated_product',
                    'um.uom_code',
                    'grp.group_name'
                )
                ->where('ph.productionplan_hdr_id', $id)
                ->first();

            if (!$plan) {
                return response()->json(['success' => false, 'message' => 'Production plan not found'], 404);
            }

            $lines = DB::table('w_productionplan_lines_t as pl')
                ->leftJoin('m_products_t as mp', 'mp.product_id', '=', 'pl.product_id')
                ->leftJoin('m_products_t as pp', 'pp.product_id', '=', 'pl.parent_product')
                ->leftJoin('m_uom_codes_t as um', 'um.uom_code_id', '=', 'pl.uom_code_id')
                ->select(
                    'pl.productionplan_line_id',
                    'pl.product_id',
                    'pl.parent_product',
                    'pl.qty',
                    'pl.component_qty',
                    'pl.qoh',
                    'pl.pending_qty',
                    'pl.process_level',
                    'pl.process_name',
                    'pl.uom_code_id',
                    'mp.product_code',
                    'mp.concatenated_product',
                    'pp.concatenated_product as parent_product_name',
                    'um.uom_code'
                )
                ->where('pl.productionplan_hdr_id', $id)
                ->orderBy('pl.productionplan_line_id')
                ->get();

            return response()->json([
                'success' => true,
                'data'    => [
                    'plan'  => $plan,
                    'lines' => $lines,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('MrpController@show: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch production plan'], 500);
        }
    }

    // -------------------------------------------------------------------------
    // POST /api/mrp
    // Create a production plan for one workorder line
    // Body: {
    //   workorder_hdr_id, workorder_line_id,
    //   product_id, uom_code_id, production_qty,
    //   plan_date, start_date, end_date,
    //   remarks (optional)
    // }
    // -------------------------------------------------------------------------
    public function store(Request $request)
    {
        try {
            $request->validate([
                'workorder_hdr_id'  => 'required|integer|min:1',
                'workorder_line_id' => 'required|integer|min:1',
                'product_id'        => 'required|integer|min:1',
                'uom_code_id'       => 'required|integer|min:1',
                'production_qty'    => 'required|numeric|min:0.01',
                'plan_date'         => 'required|date_format:Y-m-d',
                'start_date'        => 'required|date_format:Y-m-d',
                'end_date'          => 'required|date_format:Y-m-d',
            ]);

            $user           = Auth::user();
            $companyId      = $user->company_id      ?? 1;
            $organizationId = $user->organization_id ?? 1;
            $locationId     = $user->location_id     ?? 1;
            $userId         = $user->id;

            // Verify the workorder line exists and is unplanned
            $woline = DB::table('w_workorder_lines_t')
                ->where('workorder_line_id', $request->input('workorder_line_id'))
                ->where('workorder_hdr_id', $request->input('workorder_hdr_id'))
                ->first();

            if (!$woline) {
                return response()->json(['success' => false, 'message' => 'Workorder line not found'], 404);
            }
            if ((int)$woline->plan_status !== 0) {
                return response()->json(['success' => false, 'message' => 'This workorder line already has an MRP plan'], 422);
            }

            // Fetch workorder header for reference_no
            $woHeader = DB::table('w_workorder_hdr_t')
                ->where('workorder_hdr_id', $request->input('workorder_hdr_id'))
                ->first();

            // Generate plan_no
            $planNo = $this->generatePlanNo();

            $productionQty = (float) $request->input('production_qty');

            DB::beginTransaction();

            // Insert production plan header
            $planHdrId = DB::table('w_productionplan_hdr_t')->insertGetId([
                'plan_no'           => $planNo,
                'plan_date'         => $request->input('plan_date'),
                'start_date'        => $request->input('start_date'),
                'end_date'          => $request->input('end_date'),
                'plan_status'       => 'INITIATED',
                'product_id'        => $request->input('product_id'),
                'uom_code_id'       => $request->input('uom_code_id'),
                'production_qty'    => $productionQty,
                'plan_qty'          => $productionQty,
                'pending_qty'       => $productionQty,
                'remarks'           => $request->input('remarks', ''),
                'reference_no'      => $woHeader->workorder_no ?? '',
                'reference_id'      => $request->input('workorder_hdr_id'),
                'reference_line_id' => $request->input('workorder_line_id'),
                'batch_no'          => '',
                'organization_id'   => $organizationId,
                'company_id'        => $companyId,
                'location_id'       => $locationId,
                'created_by'        => $userId,
                'last_updated_by'   => $userId,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
                'job_card_status'   => 0,
            ]);

            // Auto-populate BOM lines recursively
            $this->insertBomLines(
                $request->input('product_id'),
                $productionQty,
                $planHdrId,
                $companyId,
                $locationId,
                $organizationId,
                $userId
            );

            // Mark workorder line as planned
            DB::table('w_workorder_lines_t')
                ->where('workorder_line_id', $request->input('workorder_line_id'))
                ->update(['plan_status' => 1, 'updated_at' => Carbon::now()]);

            DB::commit();

            return response()->json([
                'success'               => true,
                'message'               => 'Production plan created successfully',
                'plan_no'               => $planNo,
                'productionplan_hdr_id' => $planHdrId,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MrpController@store: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create production plan: ' . $e->getMessage()], 500);
        }
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Get BOM components for preview (does not insert — read-only).
     * Recursively expands SFG components.
     */
    private function getBomComponents(int $productId, float $qty, int $depth = 0): array
    {
        if ($depth > 5) {
            return []; // safety guard against deep recursion
        }

        $rows = DB::table('m_material_bom_hdr_t as bomh')
            ->leftJoin('m_material_bom_lines_t as boml', 'bomh.material_bom_hdr_id', '=', 'boml.material_bom_hdr_id')
            ->leftJoin('m_products_t as mp', 'mp.product_id', '=', 'boml.component_product_id')
            ->leftJoin('m_product_groups_t as grp', 'grp.product_group_id', '=', 'mp.product_group_id')
            ->leftJoin('m_uom_codes_t as um', 'um.uom_code_id', '=', 'boml.component_uom_code_id')
            ->select(
                'boml.component_product_id as product_id',
                'boml.component_uom_code_id as uom_code_id',
                'boml.component_qty',
                'boml.process_level',
                'boml.process_name',
                'mp.product_code',
                'mp.concatenated_product',
                'um.uom_code',
                'grp.group_name'
            )
            ->where('bomh.assembly_product_id', $productId)
            ->get();

        $components = [];
        foreach ($rows as $row) {
            $totalQty = $qty * (float)$row->component_qty;
            $component = [
                'product_id'        => $row->product_id,
                'product_code'      => $row->product_code,
                'concatenated_product' => $row->concatenated_product,
                'uom_code_id'       => $row->uom_code_id,
                'uom_code'          => $row->uom_code,
                'component_qty'     => $row->component_qty,
                'total_qty'         => round($totalQty, 4),
                'process_level'     => $row->process_level,
                'process_name'      => $row->process_name,
                'group_name'        => $row->group_name,
                'sub_components'    => [],
            ];

            // Recurse for SFG products
            if ($row->group_name === 'SEMI FINISHED GOODS') {
                $component['sub_components'] = $this->getBomComponents(
                    (int)$row->product_id,
                    $totalQty,
                    $depth + 1
                );
            }

            $components[] = $component;
        }

        return $components;
    }

    /**
     * Recursively insert BOM lines into w_productionplan_lines_t.
     * Mirrors the web ProductionplanController::checkbom() logic.
     */
    private function insertBomLines(
        int $productId,
        float $productionQty,
        int $planHdrId,
        int $companyId,
        int $locationId,
        int $organizationId,
        int $userId,
        int $depth = 0
    ): void {
        if ($depth > 5) {
            return;
        }

        $rows = DB::table('m_material_bom_hdr_t as bomh')
            ->leftJoin('m_material_bom_lines_t as boml', 'bomh.material_bom_hdr_id', '=', 'boml.material_bom_hdr_id')
            ->leftJoin('m_products_t as prod', 'prod.product_id', '=', 'boml.component_product_id')
            ->leftJoin('m_product_groups_t as grp', 'grp.product_group_id', '=', 'prod.product_group_id')
            ->select(
                'boml.component_product_id',
                'boml.component_uom_code_id',
                'boml.component_qty',
                'boml.process_level',
                'boml.process_name',
                'boml.machine_name',
                'grp.group_name'
            )
            ->where('bomh.assembly_product_id', $productId)
            ->get();

        if ($rows->isEmpty()) {
            return;
        }

        foreach ($rows as $row) {
            $componentQty = (float)$row->component_qty;
            $totalQty     = $productionQty * $componentQty;

            // QOH from i_qoh_detail_t
            $isFgOrSfg = in_array($row->group_name, ['SEMI FINISHED GOODS', 'FINISHED GOODS'], true);
            $qohQuery  = DB::table('i_qoh_detail_t')
                ->selectRaw('COALESCE(SUM(qoh_trx_qty), 0) as qoh_qty')
                ->where('product_id', $row->component_product_id)
                ->where('company_id', $companyId);

            if ($isFgOrSfg) {
                $qohQuery->where('qualitystatus', 1);
            }

            $qoh = max(0, (float)($qohQuery->value('qoh_qty') ?? 0));

            DB::table('w_productionplan_lines_t')->insert([
                'productionplan_hdr_id' => $planHdrId,
                'parent_product'        => $productId,
                'product_id'            => $row->component_product_id,
                'uom_code_id'           => $row->component_uom_code_id,
                'qty'                   => $totalQty,
                'component_qty'         => $componentQty,
                'qoh'                   => $qoh,
                'pending_qty'           => $totalQty,
                'process_level'         => $row->process_level,
                'process_name'          => $row->process_name,
                'machine_name'          => $row->machine_name,
                'company_id'            => $companyId,
                'location_id'           => $locationId,
                'organization_id'       => $organizationId,
                'created_by'            => $userId,
                'last_updated_by'       => $userId,
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ]);

            // Recurse only for SFG (same as web logic)
            if ($row->group_name === 'SEMI FINISHED GOODS') {
                $this->insertBomLines(
                    (int)$row->component_product_id,
                    $totalQty,
                    $planHdrId,
                    $companyId,
                    $locationId,
                    $organizationId,
                    $userId,
                    $depth + 1
                );
            }
        }
    }

    /**
     * Generate next production plan number (WOPN000001 format).
     */
    private function generatePlanNo(): string
    {
        $latest = DB::table('w_productionplan_hdr_t')
            ->selectRaw("MAX(CAST(SUBSTRING(plan_no, 5) AS UNSIGNED)) as max_no")
            ->whereRaw("plan_no REGEXP '^WOPN[0-9]+'")
            ->first();

        $next = (int)($latest->max_no ?? 0) + 1;
        return 'WOPN' . str_pad($next, 6, '0', STR_PAD_LEFT);
    }

    // =========================================================================
    // EMPLOYEE PRODUCT RESTRICTION
    // =========================================================================

    /**
     * Returns the list of product IDs the current employee is allowed to see.
     * Management employees (IDs: 151, 152, 160, 676) get NULL (= no restriction).
     * All other employees get the product IDs from productmapping_lines_tbl.
     * An empty array means the employee has no mapped products.
     *
     * @return int[]|null  null = full access; array = restricted set (may be empty)
     */
    private function getEmployeeProductIds(): ?array
    {
        $user       = Auth::user();
        $employeeId = (int)($user->employee_id ?? 0);

        // Management employees – unrestricted
        $managementIds = [151, 152, 160, 676];
        if (in_array($employeeId, $managementIds)) {
            return null;
        }

        if ($employeeId <= 0) {
            return []; // Unknown employee → deny
        }

        // Fetch mapped product IDs (prd_id is varchar, cast to int)
        $productIds = DB::table('productmapping_hdr_tbl as ph')
            ->join('productmapping_lines_tbl as pl', 'pl.productmapping_id', '=', 'ph.productmapping_id')
            ->where('ph.employee_id', $employeeId)
            ->distinct()
            ->pluck('pl.prd_id')
            ->map(fn($v) => (int) $v)
            ->filter(fn($v) => $v > 0)
            ->values()
            ->toArray();

        return $productIds;
    }

    // -------------------------------------------------------------------------
    // GET /api/wip/stats
    // Returns WIP access flag and summary counts for the current employee
    // -------------------------------------------------------------------------
    public function wipStats(Request $request)
    {
        try {
            $user       = Auth::user();
            $employeeId = (int)($user->employee_id ?? 0);

            $managementIds = [151, 152, 160, 676];
            $isManagement  = in_array($employeeId, $managementIds);

            // Get mapped products (null = full access)
            $productIds = $this->getEmployeeProductIds();
            $hasAccess  = $isManagement || ($productIds !== null && !empty($productIds));

            if (!$hasAccess) {
                return response()->json([
                    'success'       => true,
                    'has_access'    => false,
                    'is_management' => false,
                    'stats'         => [
                        'pending_plans'   => 0,
                        'approved_plans'  => 0,
                        'active_job_cards' => 0,
                    ],
                ]);
            }

            // ── Pending plans (INITIATED) ────────────────────────────────────
            $pendingQuery = DB::table('w_productionplan_hdr_t')
                ->where('plan_status', 'INITIATED');
            if ($productIds !== null) {
                $pendingQuery->whereIn('product_id', $productIds);
            }
            $pendingPlans = $pendingQuery->count();

            // ── Approved plans awaiting job card ─────────────────────────────
            $approvedQuery = DB::table('w_productionplan_hdr_t')
                ->where('plan_status', 'APPROVED')
                ->where('job_card_status', 0);
            if ($productIds !== null) {
                $approvedQuery->whereIn('product_id', $productIds);
            }
            $approvedPlans = $approvedQuery->count();

            // ── Active job cards (OPEN) ──────────────────────────────────────
            $jobCardQuery = DB::table('w_jobcard_hdr_t')
                ->where('job_status', 'OPEN');
            if ($productIds !== null) {
                $jobCardQuery->whereIn('product_id', $productIds);
            }
            $activeJobCards = $jobCardQuery->count();

            return response()->json([
                'success'       => true,
                'has_access'    => true,
                'is_management' => $isManagement,
                'stats'         => [
                    'pending_plans'    => $pendingPlans,
                    'approved_plans'   => $approvedPlans,
                    'active_job_cards' => $activeJobCards,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('MrpController@wipStats: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch WIP stats'], 500);
        }
    }

    // -------------------------------------------------------------------------
    // PUT /api/mrp/{id}/approve
    // Approve or reject a production plan
    // Body: { action: 'APPROVED' | 'REJECTED' }
    // -------------------------------------------------------------------------
    public function approve(Request $request, int $id)
    {
        try {
            $request->validate([
                'action' => 'required|in:APPROVED,REJECTED',
            ]);

            $plan = DB::table('w_productionplan_hdr_t')
                ->where('productionplan_hdr_id', $id)
                ->first();

            if (!$plan) {
                return response()->json(['success' => false, 'message' => 'Production plan not found'], 404);
            }

            if ($plan->plan_status !== 'INITIATED') {
                return response()->json([
                    'success' => false,
                    'message' => 'Plan is already ' . $plan->plan_status . ' and cannot be changed',
                ], 422);
            }

            $action = $request->input('action');

            DB::table('w_productionplan_hdr_t')
                ->where('productionplan_hdr_id', $id)
                ->update([
                    'plan_status'     => $action,
                    'last_updated_by' => Auth::id(),
                    'updated_at'      => Carbon::now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Plan ' . strtolower($action) . ' successfully',
                'plan_status' => $action,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('MrpController@approve: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Operation failed'], 500);
        }
    }
}
