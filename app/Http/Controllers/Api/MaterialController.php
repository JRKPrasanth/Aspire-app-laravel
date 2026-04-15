<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * MaterialController — Mobile API for the WIP post-job-card flow:
 *   1. Material Issue  (production = SEMI FINISHED GOODS, packing = FINISHED GOODS)
 *   2. Material Receive / Acknowledge
 *   3. Store Move      (packing only — multi-process, PROCESS-1 → FINALPROCESS)
 *   4. Job Card Completion (close job card)
 */
class MaterialController extends Controller
{
    private const MANAGEMENT_IDS = [151, 152, 160, 676];

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // -----------------------------------------------------------------------
    // Helper: returns null (full access) | [] (no access) | int[] (mapped ids)
    // -----------------------------------------------------------------------
    private function getEmployeeProductIds(): ?array
    {
        $employeeId = Auth::user()->employee_id ?? null;
        if (!$employeeId) return [];

        if (in_array((int) $employeeId, self::MANAGEMENT_IDS)) return null;

        $ids = DB::table('productmapping_hdr_tbl as ph')
            ->join('productmapping_lines_tbl as pl', 'pl.productmapping_hdr_id', '=', 'ph.productmapping_hdr_id')
            ->where('ph.employee_id', $employeeId)
            ->distinct()
            ->pluck('pl.prd_id');

        return $ids->map(fn($v) => (int) $v)->filter(fn($v) => $v > 0)->values()->all();
    }

    // -----------------------------------------------------------------------
    // Helper: resolve org/company/location from the authenticated API user
    // -----------------------------------------------------------------------
    private function getUserContext(): array
    {
        $user = Auth::user();
        return [
            'userId'   => $user->id,
            'companyId'  => $user->company_id      ?? 1,
            'orgId'      => $user->organization_id  ?? 1,
            'locationId' => $user->location_id      ?? 1,
            'employeeId' => $user->employee_id      ?? null,
        ];
    }

    // =======================================================================
    // MATERIAL ISSUE
    // =======================================================================

    /**
     * GET /api/material/issue/list
     * Query: type=production|packing, page, search
     *
     * Production = SEMI FINISHED GOODS, Packing = FINISHED GOODS
     * Shows job cards where material can still be issued
     * (not yet MATERIAL ISSUED / RECEIVED / QA SUBMITTED / STORE MOVED / CLOSED)
     */
    public function getMaterialIssueList(Request $request)
    {
        try {
            $type    = $request->input('type', 'production');
            $page    = max(1, (int) $request->input('page', 1));
            $search  = trim($request->input('search', ''));
            $perPage = 20;

            $productIds = $this->getEmployeeProductIds();

            $query = DB::table('w_jobcard_hdr_t as j')
                ->join('m_products_t as p', 'p.product_id', '=', 'j.product_id')
                ->join('m_product_groups_t as pg', 'pg.product_group_id', '=', 'p.product_group_id')
                ->leftJoin('w_productionplan_hdr_t as plan', 'plan.productionplan_hdr_id', '=', 'j.reference_source_id')
                ->leftJoin('m_uom_codes_t as u', 'u.uom_code_id', '=', 'j.uom_code_id')
                ->select(
                    'j.w_jobs_hdr_id',
                    'j.job_no',
                    'j.job_date',
                    'j.job_completion_date',
                    'j.job_adjusted_qty',
                    'j.batch_no',
                    'j.job_status',
                    'j.job_process',
                    'j.bom_process',
                    'p.product_id',
                    'p.concatenated_product',
                    'p.product_code',
                    'plan.plan_no',
                    'pg.group_name as product_group',
                    'u.uom_code'
                )
                ->where('j.qasubmit_status', '0')
                ->whereNotIn('j.job_status', ['CLOSED', 'QA SUBMITTED', 'STORE MOVED', 'MATERIAL ISSUED', 'MATERIAL RECEIVED']);

            if ($type === 'packing') {
                $query->where('pg.group_name', 'FINISHED GOODS');
            } else {
                $query->where('pg.group_name', 'SEMI FINISHED GOODS');
            }

            if ($productIds !== null) {
                if (empty($productIds)) {
                    return response()->json(['success' => true, 'data' => [], 'total' => 0, 'per_page' => $perPage, 'current_page' => $page]);
                }
                $query->whereIn('j.product_id', $productIds);
            }

            if ($search !== '') {
                $like = "%{$search}%";
                $query->where(function ($q) use ($like) {
                    $q->where('j.job_no', 'like', $like)
                        ->orWhere('p.concatenated_product', 'like', $like)
                        ->orWhere('p.product_code', 'like', $like);
                });
            }

            $total = (clone $query)->count();
            $items = $query->orderBy('j.w_jobs_hdr_id', 'desc')
                ->offset(($page - 1) * $perPage)
                ->limit($perPage)
                ->get();

            return response()->json([
                'success'      => true,
                'data'         => $items,
                'total'        => $total,
                'per_page'     => $perPage,
                'current_page' => $page,
            ]);
        } catch (\Exception $e) {
            Log::error('MaterialController@getMaterialIssueList: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch list'], 500);
        }
    }

    /**
     * GET /api/material/issue/{jobId}/lines
     * Returns component lines for issuing materials for a job card.
     * If a material issue already exists (receive_status=0), returns those lines.
     * Otherwise returns production plan lines.
     */
    public function getMaterialIssueLines($jobId)
    {
        try {
            $job = DB::table('w_jobcard_hdr_t as j')
                ->join('m_products_t as p', 'p.product_id', '=', 'j.product_id')
                ->leftJoin('m_uom_codes_t as u', 'u.uom_code_id', '=', 'j.uom_code_id')
                ->leftJoin('hr_employee_t as ec', 'ec.employee_id', '=', 'j.job_created_by')
                ->leftJoin('hr_employee_t as ea', 'ea.employee_number', '=', 'j.job_assigned_to')
                ->leftJoin('w_machine_hdr_t as m', 'm.machine_hdr_id', '=', 'j.machine_hdr_id')
                ->leftJoin('m_products_t as pb', 'pb.product_id', '=', 'j.bom_product_id')
                ->where('j.w_jobs_hdr_id', $jobId)
                ->select(
                    'j.*',
                    'p.concatenated_product',
                    'p.product_code',
                    'u.uom_code',
                    DB::raw("CONCAT(COALESCE(ec.employee_number,''), ' - ', COALESCE(ec.first_name,'')) as created_by_name"),
                    DB::raw("CONCAT(COALESCE(ea.employee_number,''), ' - ', COALESCE(ea.first_name,'')) as assigned_to_name"),
                    'm.machine_name',
                    'm.capacity as machine_capacity',
                    'pb.concatenated_product as bom_product_name'
                )
                ->first();

            if (!$job) {
                return response()->json(['success' => false, 'message' => 'Job card not found'], 404);
            }

            // Check existing un-received material issue
            $mtlhdr = DB::table('w_materialissue_hdr_t')
                ->where('w_jobs_hdr_id', $jobId)
                ->where('receive_status', 0)
                ->first();

            if ($mtlhdr) {
                $lines = DB::table('w_materialissue_line_t as l')
                    ->join('m_products_t as p', 'p.product_id', '=', 'l.product_id')
                    ->leftJoin('m_uom_codes_t as u', 'u.uom_code_id', '=', 'l.uom_code_id')
                    ->leftJoin('m_sublocators_t as sl', function ($join) {
                        $join->on(DB::raw('FIND_IN_SET(sl.sublocator_id, l.locator_id)'), '>', DB::raw('0'));
                    })
                    ->where('l.w_materialissue_hdr_id', $mtlhdr->w_materialissue_hdr_id)
                    ->where('l.receive_status', 0)
                    ->select(
                        'l.w_materialissue_line_id',
                        'l.line_no',
                        'l.product_id',
                        'p.product_code',
                        'p.concatenated_product',
                        'l.qty',
                        'l.issue_qty',
                        'l.uom_code_id',
                        'u.uom_code',
                        'l.mtl_issue_qty',
                        'l.issued_qty',
                        'l.balance_qty',
                        'l.subinventory_id',
                        'l.locator_id',
                        'l.batchnumber',
                        'l.issueqty',
                        'l.comments',
                        DB::raw('GROUP_CONCAT(sl.locator_code ORDER BY sl.sublocator_id SEPARATOR ", ") as locator_codes')
                    )
                    ->groupBy('l.w_materialissue_line_id')
                    ->orderBy('l.line_no')
                    ->get();

                return response()->json([
                    'success'      => true,
                    'existing'     => true,
                    'issue_hdr_id' => $mtlhdr->w_materialissue_hdr_id,
                    'lines'        => $lines,
                    'job'          => $job,
                    'remarks'      => $mtlhdr->remarks ?? '',
                ]);
            }

            // Build from production plan lines (new issue)
            $process = ($job->bom_process !== '0' && $job->bom_process !== '' && $job->bom_process !== null)
                ? $job->bom_process : null;

            $planQuery = DB::table('w_productionplan_lines_t as pl')
                ->join('m_products_t as p', 'p.product_id', '=', 'pl.product_id')
                ->leftJoin('m_uom_codes_t as u', 'u.uom_code_id', '=', 'p.primary_uom_id')
                ->where('pl.productionplan_hdr_id', $job->reference_source_id)
                ->where('pl.parent_product', $job->product_id)
                ->select(
                    'pl.product_id',
                    'p.product_code',
                    'p.concatenated_product',
                    'pl.component_qty as qty',
                    'p.primary_uom_id as uom_code_id',
                    'u.uom_code',
                    'pl.process_level'
                );

            if ($process) {
                $planQuery->where('pl.process_level', $process);
            }

            $lines = $planQuery->get()
                ->values()
                ->map(function ($line, $idx) use ($job) {
                    $line->issue_qty   = round(floatval($line->qty) * floatval($job->job_adjusted_qty), 4);
                    $line->issued_qty  = 0;
                    $line->balance_qty = $line->issue_qty;
                    $line->mtl_issue_qty = $line->issue_qty;
                    $line->line_no     = $idx + 1;
                    return $line;
                });

            return response()->json([
                'success'  => true,
                'existing' => false,
                'lines'    => $lines,
                'job'      => $job,
            ]);
        } catch (\Exception $e) {
            Log::error('MaterialController@getMaterialIssueLines: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch lines'], 500);
        }
    }

    /**
     * POST /api/material/issue/{jobId}
     * Body: { lines: [{product_id, uom_code_id, qty, issue_qty, comments}] }
     * Creates material issue hdr+lines and updates job_status → MATERIAL ISSUED
     */
    public function saveMaterialIssue(Request $request, $jobId)
    {
        try {
            $job = DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $jobId)->first();
            if (!$job) {
                return response()->json(['success' => false, 'message' => 'Job card not found'], 404);
            }

            $allowedStatuses = ['OPEN', 'CREATED', ''];
            if (!empty($job->job_status) && !in_array(strtoupper($job->job_status), $allowedStatuses)) {
                return response()->json(['success' => false, 'message' => 'Job card is not in a state where material can be issued (current: ' . $job->job_status . ')']);
            }

            $ctx   = $this->getUserContext();
            $lines = $request->input('lines', []);

            if (empty($lines)) {
                return response()->json(['success' => false, 'message' => 'No material lines provided']);
            }

            DB::beginTransaction();

            $hdrId = DB::table('w_materialissue_hdr_t')->insertGetId([
                'w_jobs_hdr_id'    => $jobId,
                'job_qty'          => $job->job_adjusted_qty,
                'mtl_issue_date'   => date('Y-m-d H:i:s'),
                'remarks'          => $request->input('remarks', ''),
                'receive_status'   => 0,
                'created_by'       => $ctx['userId'],
                'created_at'       => date('Y-m-d H:i:s'),
                'organization_id'  => $ctx['orgId'],
                'location_id'      => $ctx['locationId'],
                'company_id'       => $ctx['companyId'],
                'last_updated_by'  => $ctx['userId'],
                'updated_at'       => date('Y-m-d H:i:s'),
            ]);

            foreach ($lines as $lineNo => $line) {
                $qty      = floatval($line['qty'] ?? 0);
                $issueQty = floatval($line['issue_qty'] ?? ($qty * floatval($job->job_adjusted_qty)));
                $mtlIssueQty = floatval($line['mtl_issue_qty'] ?? $issueQty);
                $issuedQty   = 0;
                $balanceQty  = max(0, $issueQty - ($mtlIssueQty));

                DB::table('w_materialissue_line_t')->insert([
                    'w_materialissue_hdr_id' => $hdrId,
                    'product_id'             => $line['product_id'],
                    'uom_code_id'            => $line['uom_code_id'] ?? null,
                    'qty'                    => $qty,
                    'issue_qty'              => $issueQty,
                    'mtl_issue_qty'          => $mtlIssueQty,
                    'issued_qty'             => $issuedQty,
                    'balance_qty'            => $balanceQty,
                    'subinventory_id'        => $line['subinventory_id'] ?? '',
                    'locator_id'             => $line['locator_id'] ?? '',
                    'batchnumber'            => $line['batch_number'] ?? '',
                    'issueqty'               => $line['bulk_issueqty'] ?? $mtlIssueQty,
                    'receive_status'         => 0,
                    'line_no'                => $lineNo + 1,
                    'job_qty'                => floatval($job->job_adjusted_qty),
                    'comments'               => $line['comments'] ?? '',
                    'created_by'             => $ctx['userId'],
                    'created_at'             => date('Y-m-d H:i:s'),
                    'organization_id'        => $ctx['orgId'],
                    'location_id'            => $ctx['locationId'],
                    'company_id'             => $ctx['companyId'],
                    'last_updated_by'        => $ctx['userId'],
                    'updated_at'             => date('Y-m-d H:i:s'),
                ]);
            }

            DB::table('w_jobcard_hdr_t')
                ->where('w_jobs_hdr_id', $jobId)
                ->update(['job_status' => 'MATERIAL ISSUED']);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Material issued successfully', 'hdr_id' => $hdrId]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MaterialController@saveMaterialIssue: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * GET /api/material/product/{productId}/qoh
     * Query: type=production|packing
     * Returns available batch-wise stock from i_qoh_detail_t.
     *   production → SEMI FINISHED GOODS, qualitystatus=1
     *   packing    → FINISHED GOODS, rack_no IN ('PRM','PP','WIP') for sub-inv 5
     */
    public function getProductQoh(Request $request, $productId)
    {
        try {
            $ctx  = $this->getUserContext();
            $type = $request->input('type', 'production');

            $query = DB::table('i_qoh_detail_t as i')
                ->leftJoin('m_subinventory_t as s', 's.subinventory_id', '=', 'i.subinventory_id')
                ->leftJoin('m_sublocators_t as l', 'l.sublocator_id', '=', 'i.locator_id')
                ->where('i.product_id', $productId)
                ->where('i.company_id', $ctx['companyId'])
                ->select(
                    'i.batch_number',
                    'i.subinventory_id',
                    's.subinventory_name',
                    'i.locator_id',
                    'l.locator_code',
                    DB::raw('ROUND(SUM(i.qoh_trx_qty), 3) as qoh_qty')
                )
                ->groupBy('i.batch_number', 'i.subinventory_id', 's.subinventory_name', 'i.locator_id', 'l.locator_code')
                ->havingRaw('qoh_qty > 0')
                ->orderByDesc('i.qoh_detail_id');

            if ($type === 'production') {
                // Do NOT filter by qualitystatus here.
                // i_qoh_detail_t stores both receipts (qualitystatus=1, positive qty) and
                // issue/deduction transactions (qualitystatus=0, negative qty).
                // Filtering to qualitystatus=1 only shows gross receipts and misses deductions,
                // causing inflated QOH (e.g. API returns 95501.27 but web shows 42416.24).
                // The web's prdstocksublocdata (MATERIALISSUE) omits the qualitystatus filter
                // and sums all transactions to get the net qty.
                // HAVING qoh_qty > 0 ensures only locations with positive net stock appear.
                //
                // Rack filter matches web's MATERIALISSUE prdstocksublocdata:
                //   subinventory 5 → rack_no IN ('PRM','PP')
                //   other subinventories → include as-is
                $query->where(function ($q) {
                    $q->where(function ($inner) {
                        $inner->where('i.subinventory_id', 5)
                            ->whereIn('l.rack_no', ['PRM', 'PP']);
                    })->orWhere('i.subinventory_id', '!=', 5);
                });
            } else {
                // packing: FINISHED GOODS — sub-inv 5 restricted to WIP racks
                $query->where(function ($q) {
                    $q->where('i.subinventory_id', '!=', 5)
                        ->orWhere(function ($inner) {
                            $inner->where('i.subinventory_id', 5)
                                ->whereIn('l.rack_no', ['PRM', 'PP', 'WIP']);
                        });
                });
            }

            $batches = $query->get();

            return response()->json(['success' => true, 'batches' => $batches]);
        } catch (\Exception $e) {
            Log::error('MaterialController@getProductQoh: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch QOH'], 500);
        }
    }

    // =======================================================================
    // MATERIAL RECEIVE / ACKNOWLEDGE
    // =======================================================================

    /**
     * GET /api/material/receive/list
     * Query: type=production|packing, page, search
     * Returns job cards with job_status = 'MATERIAL ISSUED'
     */
    public function getMaterialReceiveList(Request $request)
    {
        try {
            $type    = $request->input('type', 'production');
            $page    = max(1, (int) $request->input('page', 1));
            $search  = trim($request->input('search', ''));
            $perPage = 20;

            $productIds = $this->getEmployeeProductIds();

            $query = DB::table('w_jobcard_hdr_t as j')
                ->join('m_products_t as p', 'p.product_id', '=', 'j.product_id')
                ->join('m_product_groups_t as pg', 'pg.product_group_id', '=', 'p.product_group_id')
                ->leftJoin('w_productionplan_hdr_t as plan', 'plan.productionplan_hdr_id', '=', 'j.reference_source_id')
                ->leftJoin('w_materialissue_hdr_t as mi', 'mi.w_jobs_hdr_id', '=', 'j.w_jobs_hdr_id')
                ->select(
                    'j.w_jobs_hdr_id',
                    'j.job_no',
                    'j.job_date',
                    'j.job_adjusted_qty',
                    'j.batch_no',
                    'j.job_status',
                    'j.job_process',
                    'j.bom_process',
                    'p.product_id',
                    'p.concatenated_product',
                    'p.product_code',
                    'plan.plan_no',
                    'pg.group_name as product_group',
                    'mi.w_materialissue_hdr_id'
                )
                ->where('j.job_status', 'MATERIAL ISSUED');

            if ($type === 'packing') {
                $query->where('pg.group_name', 'FINISHED GOODS');
            } else {
                $query->where('pg.group_name', 'SEMI FINISHED GOODS');
            }

            if ($productIds !== null) {
                if (empty($productIds)) {
                    return response()->json(['success' => true, 'data' => [], 'total' => 0, 'per_page' => $perPage, 'current_page' => $page]);
                }
                $query->whereIn('j.product_id', $productIds);
            }

            if ($search !== '') {
                $like = "%{$search}%";
                $query->where(function ($q) use ($like) {
                    $q->where('j.job_no', 'like', $like)
                        ->orWhere('p.concatenated_product', 'like', $like)
                        ->orWhere('p.product_code', 'like', $like);
                });
            }

            $total = (clone $query)->count();
            $items = $query->orderBy('j.w_jobs_hdr_id', 'desc')
                ->offset(($page - 1) * $perPage)
                ->limit($perPage)
                ->get();

            return response()->json([
                'success'      => true,
                'data'         => $items,
                'total'        => $total,
                'per_page'     => $perPage,
                'current_page' => $page,
            ]);
        } catch (\Exception $e) {
            Log::error('MaterialController@getMaterialReceiveList: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch list'], 500);
        }
    }

    /**
     * POST /api/material/receive/{jobId}
     * Simple acknowledgement — creates receive header, marks issue lines received,
     * updates job_status → MATERIAL RECEIVED
     */
    public function saveMaterialReceive(Request $request, $jobId)
    {
        try {
            $job = DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $jobId)->first();
            if (!$job) {
                return response()->json(['success' => false, 'message' => 'Job card not found'], 404);
            }
            if ($job->job_status !== 'MATERIAL ISSUED') {
                return response()->json(['success' => false, 'message' => 'Job card is not in MATERIAL ISSUED status (current: ' . $job->job_status . ')']);
            }

            $ctx = $this->getUserContext();

            DB::beginTransaction();

            // Create receive header
            $hdrId = DB::table('w_materialreceive_hdr_t')->insertGetId([
                'w_jobs_hdr_id'   => $jobId,
                'product_id'      => $job->product_id,
                'uom_code_id'     => $job->uom_code_id,
                'mtl_receive_date' => date('Y-m-d H:i:s'),
                'created_by'      => $ctx['userId'],
                'created_at'      => date('Y-m-d'),
                'organization_id' => $ctx['orgId'],
                'location_id'     => $ctx['locationId'],
                'company_id'      => $ctx['companyId'],
                'last_updated_by' => $ctx['userId'],
                'updated_at'      => date('Y-m-d'),
            ]);

            // Mark all issue lines as received
            $mtlHdrs = DB::table('w_materialissue_hdr_t')
                ->where('w_jobs_hdr_id', $jobId)
                ->get();

            foreach ($mtlHdrs as $mh) {
                // Update each line: receive_qty = mtl_issue_qty, receive_status = 1
                DB::table('w_materialissue_line_t')
                    ->where('w_materialissue_hdr_id', $mh->w_materialissue_hdr_id)
                    ->update([
                        'receive_qty'    => DB::raw('mtl_issue_qty'),
                        'receive_status' => 1,
                    ]);

                DB::table('w_materialissue_hdr_t')
                    ->where('w_materialissue_hdr_id', $mh->w_materialissue_hdr_id)
                    ->update(['receive_status' => 1]);

                // Insert a basic line in receive table per issued line for traceability
                $issueLines = DB::table('w_materialissue_line_t')
                    ->where('w_materialissue_hdr_id', $mh->w_materialissue_hdr_id)
                    ->get();

                foreach ($issueLines as $li => $line) {
                    $rcvLine = DB::table('w_materialreceive_line_t')
                        ->where('w_materialreceive_hdr_id', $hdrId)
                        ->where('product_id', $line->product_id)
                        ->first();

                    if ($rcvLine) {
                        DB::table('w_materialreceive_line_t')
                            ->where('w_materialreceive_line_id', $rcvLine->w_materialreceive_line_id)
                            ->update([
                                'receiveqty'  => $rcvLine->receiveqty + $line->mtl_issue_qty,
                                'receive_qty' => $rcvLine->receive_qty + $line->mtl_issue_qty,
                            ]);
                    } else {
                        DB::table('w_materialreceive_line_t')->insert([
                            'w_materialreceive_hdr_id'    => $hdrId,
                            'product_id'                  => $line->product_id,
                            'uom_code_id'                 => $line->uom_code_id,
                            'issueqty'                    => $line->mtl_issue_qty,
                            'receive_qty'                 => $line->mtl_issue_qty,
                            'receiveqty'                  => $line->mtl_issue_qty,
                            'reference_source_hdr_id'     => $mh->w_materialissue_hdr_id,
                            'reference_source_line_id'    => $line->w_materialissue_line_id,
                            'reference_source'            => 'MATERIAL ISSUE',
                            'line_no'                     => $li + 1,
                            'created_by'                  => $ctx['userId'],
                            'created_at'                  => date('Y-m-d'),
                            'organization_id'             => $ctx['orgId'],
                            'location_id'                 => $ctx['locationId'],
                            'company_id'                  => $ctx['companyId'],
                            'last_updated_by'             => $ctx['userId'],
                            'updated_at'                  => date('Y-m-d'),
                        ]);
                    }
                }
            }

            // Update job card status
            DB::table('w_jobcard_hdr_t')
                ->where('w_jobs_hdr_id', $jobId)
                ->update(['job_status' => 'MATERIAL RECEIVED']);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Material acknowledged successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MaterialController@saveMaterialReceive: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // =======================================================================
    // PACKING JOB CARD STATUS (Store Move)
    // =======================================================================

    /**
     * GET /api/material/packing-status
     * Returns packing job cards (FINISHED GOODS) with status MATERIAL RECEIVED or later
     * (before CLOSED) — used in the Packing Job Card Status screen
     */
    public function getPackingJobCardStatus(Request $request)
    {
        try {
            $page    = max(1, (int) $request->input('page', 1));
            $search  = trim($request->input('search', ''));
            $perPage = 20;

            $productIds = $this->getEmployeeProductIds();

            $query = DB::table('w_jobcard_hdr_t as j')
                ->join('m_products_t as p', 'p.product_id', '=', 'j.product_id')
                ->join('m_product_groups_t as pg', 'pg.product_group_id', '=', 'p.product_group_id')
                ->leftJoin('w_productionplan_hdr_t as plan', 'plan.productionplan_hdr_id', '=', 'j.reference_source_id')
                ->select(
                    'j.w_jobs_hdr_id',
                    'j.job_no',
                    'j.job_date',
                    'j.job_adjusted_qty',
                    'j.batch_no',
                    'j.job_status',
                    'j.store_move_status',
                    'j.bom_process',
                    'p.product_id',
                    'p.concatenated_product',
                    'p.product_code',
                    'plan.plan_no'
                )
                ->where('pg.group_name', 'FINISHED GOODS')
                ->whereIn('j.job_status', ['MATERIAL RECEIVED', 'STORE MOVED', 'QA SUBMITTED']);

            if ($productIds !== null) {
                if (empty($productIds)) {
                    return response()->json(['success' => true, 'data' => [], 'total' => 0]);
                }
                $query->whereIn('j.product_id', $productIds);
            }

            if ($search !== '') {
                $like = "%{$search}%";
                $query->where(function ($q) use ($like) {
                    $q->where('j.job_no', 'like', $like)
                        ->orWhere('p.concatenated_product', 'like', $like);
                });
            }

            $total = (clone $query)->count();
            $items = $query->orderBy('j.w_jobs_hdr_id', 'desc')
                ->offset(($page - 1) * $perPage)
                ->limit($perPage)
                ->get();

            // Attach existing process entries per job card
            $jobIds = $items->pluck('w_jobs_hdr_id')->all();
            $processEntries = DB::table('w_jobcard_process_details_t')
                ->whereIn('job_id', $jobIds)
                ->select('job_id', 'process_level', 'process_name', 'move_qty', 'process_date')
                ->get()
                ->groupBy('job_id');

            $items = $items->map(function ($item) use ($processEntries) {
                $item->process_entries = $processEntries[$item->w_jobs_hdr_id] ?? collect();
                return $item;
            });

            return response()->json([
                'success'      => true,
                'data'         => $items,
                'total'        => $total,
                'per_page'     => $perPage,
                'current_page' => $page,
            ]);
        } catch (\Exception $e) {
            Log::error('MaterialController@getPackingJobCardStatus: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch status'], 500);
        }
    }

    /**
     * GET /api/material/storemove/{jobId}/details
     * Returns everything needed to fill the Store Move form:
     *   - job card info
     *   - BOM process levels
     *   - already-moved qty per process
     *   - machines (filtered by product_type_id)
     *   - subinventories (production_store = Yes)
     *   - employees list
     *   - latest completed process (so frontend knows prev_process_level)
     */
    public function getStoreMoveDetails($jobId)
    {
        try {
            $job = DB::table('w_jobcard_hdr_t as j')
                ->join('m_products_t as p', 'p.product_id', '=', 'j.product_id')
                ->leftJoin('m_uom_codes_t as u', 'u.uom_code_id', '=', 'j.uom_code_id')
                ->where('j.w_jobs_hdr_id', $jobId)
                ->select('j.*', 'p.concatenated_product', 'p.product_code', 'p.product_type_id', 'u.uom_code')
                ->first();

            if (!$job) {
                return response()->json(['success' => false, 'message' => 'Job card not found'], 404);
            }

            // BOM process levels for this product (exclude empty/null process_level rows)
            $bomProcesses = DB::table('m_material_bom_hdr_t as h')
                ->join('m_material_bom_lines_t as l', 'l.material_bom_hdr_id', '=', 'h.material_bom_hdr_id')
                ->where('h.active', 'Yes')
                ->where('h.assembly_product_id', $job->product_id)
                ->whereNotNull('l.process_level')
                ->where('l.process_level', '!=', '')
                ->where('l.process_level', '!=', '0')
                ->select('l.process_level', 'l.process_name')
                ->groupBy('l.process_level', 'l.process_name')
                ->orderBy('l.material_bom_line_id', 'asc')
                ->get();

            // Already-moved qty by process level — return as flat key=>qty map
            $movedQtyRaw = DB::table('i_qoh_detail_t')
                ->where('product_id', $job->product_id)
                ->where('job_id', $jobId)
                ->where('qoh_source', 'JOB STORE MOVE')
                ->select('job_process', DB::raw('SUM(qoh_trx_qty) as qty'))
                ->groupBy('job_process')
                ->get();
            $movedQty = [];
            foreach ($movedQtyRaw as $row) {
                $movedQty[$row->job_process] = (float) $row->qty;
            }

            // Latest completed process level (for prev_process_level)
            $latestProcess = DB::table('w_jobcard_process_details_t')
                ->where('job_id', $jobId)
                ->orderBy('job_process_id', 'desc')
                ->value('process_level');

            // Machines filtered by product type
            $machines = DB::table('w_machine_hdr_t as m')
                ->join('w_machine_lines_t as ml', 'ml.machine_hdr_id', '=', 'm.machine_hdr_id')
                ->where('ml.product_type_id', $job->product_type_id)
                ->select('m.machine_hdr_id as machine_id', 'm.machine_name')
                ->distinct()
                ->get();

            // Fallback: all machines if none found for this product type
            if ($machines->isEmpty()) {
                $machines = DB::table('w_machine_hdr_t')
                    ->select('machine_hdr_id as machine_id', 'machine_name')
                    ->orderBy('machine_name')
                    ->get();
            }

            // Production-store subinventories
            $subinventories = DB::table('m_subinventory_t')
                ->where('production_store', 'Yes')
                ->select('subinventory_id', 'subinventory_name')
                ->get();

            // Employees
            $employees = DB::table('hr_employee_t')
                ->select('employee_id', 'employee_number', 'first_name')
                ->whereNotNull('first_name')
                ->orderBy('first_name')
                ->limit(300)
                ->get();

            return response()->json([
                'success'         => true,
                'job'             => $job,
                'bom_processes'   => $bomProcesses,
                'moved_qty'       => $movedQty,
                'latest_process'  => $latestProcess,
                'machines'        => $machines,
                'subinventories'  => $subinventories,
                'employees'       => $employees,
            ]);
        } catch (\Exception $e) {
            Log::error('MaterialController@getStoreMoveDetails: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to load store move details'], 500);
        }
    }

    /**
     * GET /api/material/subinventories/{subinvId}/locators
     * Returns locators for the selected subinventory
     */
    public function getLocators($subinvId)
    {
        $items = DB::table('m_sublocators_t')
            ->where('subinventory_id', $subinvId)
            ->select('sublocator_id', 'locator_code')
            ->get();
        return response()->json(['success' => true, 'data' => $items]);
    }

    /**
     * POST /api/material/storemove/{jobId}
     * Saves a Store Move process entry.
     * Body: {
     *   process_level, process_name, subinventory_id, locator_id,
     *   machine_id, machine_time, qty, trx_date,
     *   process_start_date, process_end_date,
     *   prev_process_level, prev_process_name,
     *   calibration_checked_by (only for PROCESS-1),
     *   employees: [{employee_id, type(MAJOR|MINOR), activity_name, start_time, end_time, actual_hrs, working_hrs, emp_qty}]
     * }
     */
    public function saveStoreMoveEntry(Request $request, $jobId)
    {
        try {
            $job = DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $jobId)->first();
            if (!$job) {
                return response()->json(['success' => false, 'message' => 'Job card not found'], 404);
            }

            $ctx = $this->getUserContext();

            $processLevel    = $request->input('process_level');
            $processName     = $request->input('process_name', '');
            $subinvId        = $request->input('subinventory_id');
            $locatorId       = $request->input('locator_id');
            $machineId       = $request->input('machine_id');
            $machineTime     = $request->input('machine_time', 0);
            $qty             = floatval($request->input('qty', 0));
            $trxDate         = $request->input('trx_date', date('Y-m-d'));
            $processStart    = $request->input('process_start_date');
            $processEnd      = $request->input('process_end_date');
            $prevProcessLevel = $request->input('prev_process_level', '0');
            $prevProcessName = $request->input('prev_process_name', '');
            $calibrationBy   = $request->input('calibration_checked_by', '');
            $employees       = $request->input('employees', []);

            if (!$processLevel || !$subinvId || $qty <= 0) {
                return response()->json(['success' => false, 'message' => 'process_level, subinventory_id, and qty are required']);
            }

            $group = DB::table('m_products_t')->where('product_id', $job->product_id)->first();

            DB::beginTransaction();

            $base = [
                'created_by'      => $ctx['userId'],
                'created_at'      => date('Y-m-d'),
                'organization_id' => $ctx['orgId'],
                'location_id'     => $ctx['locationId'],
                'company_id'      => $ctx['companyId'],
                'last_updated_by' => $ctx['userId'],
                'updated_at'      => date('Y-m-d'),
            ];

            // ── Job Activity entries ─────────────────────────────────────────
            $majorEmployees = [];
            foreach ($employees as $key => $emp) {
                if (!empty($emp['activity_name']) && !empty($emp['employee_id'])) {
                    $hdrId = DB::table('w_jobactivity_hdr_t')->insertGetId(array_merge($base, [
                        'employee_id' => $emp['employee_id'],
                        'job_id'      => $jobId,
                    ]));
                    DB::table('w_jobactivity_lines_t')->insert(array_merge($base, [
                        'job_activity_hdr_id' => $hdrId,
                        'activity_name'       => $emp['activity_name'],
                        'start_datetime'      => $emp['start_time'] ?? null,
                        'end_datetime'        => $emp['end_time'] ?? null,
                        'duration'            => $emp['working_hrs'] ?? 0,
                        'type'                => $emp['type'] ?? 'MAJOR',
                        'line_no'             => $key + 1,
                    ]));
                    if (($emp['type'] ?? 'MAJOR') === 'MAJOR') {
                        $majorEmployees[] = $emp;
                    }
                }
            }

            // ── Machine Log ──────────────────────────────────────────────────
            DB::table('b_machine_log_t')->insert(array_merge($base, [
                'process_dept'  => 'OPERATION',
                'machine_id'    => $machineId,
                'date'          => $processStart ? date('Y-m-d', strtotime($processStart)) : date('Y-m-d'),
                'product_id'    => $job->product_id,
                'batch_number'  => $job->batch_no,
                'quantity'      => $qty,
                'running_hours' => $machineTime,
            ]));

            // ── Process Details entry (MAJOR employees only) ─────────────────
            if (!empty($majorEmployees)) {
                $pdata = array_merge($base, [
                    'job_id'           => $jobId,
                    'machine_id'       => $machineId,
                    'machine_time'     => $machineTime,
                    'jobassigned_to'   => implode(',', array_column($majorEmployees, 'employee_id')),
                    'actual_hrs'       => implode(',', array_column($majorEmployees, 'actual_hrs')),
                    'start_time'       => implode(',', array_column($majorEmployees, 'start_time')),
                    'end_time'         => implode(',', array_column($majorEmployees, 'end_time')),
                    'working_hrs'      => implode(',', array_column($majorEmployees, 'working_hrs')),
                    'emp_qty'          => implode(',', array_column($majorEmployees, 'emp_qty')),
                    'process_level'    => $processLevel,
                    'process_name'     => $processName,
                    'move_qty'         => $qty,
                    'process_date'     => date('Y-m-d', strtotime($trxDate)),
                    'subinventory_id'  => $subinvId,
                    'locator_id'       => $locatorId,
                    'process_start_date' => $processStart ? date('Y-m-d H:i:s', strtotime($processStart)) : null,
                    'process_end_date'   => $processEnd   ? date('Y-m-d H:i:s', strtotime($processEnd))   : null,
                ]);
                if ($processLevel === 'PROCESS-1') {
                    $pdata['calibration_checked_by'] = $calibrationBy;
                }
                DB::table('w_jobcard_process_details_t')->insert($pdata);

                // Check FINALPROCESS completion → mark store_move_status + job_status
                if ($processLevel === 'FINALPROCESS') {
                    $process1Qty = DB::table('w_jobcard_process_details_t')
                        ->where('job_id', $jobId)->where('process_level', 'PROCESS-1')->sum('move_qty');
                    $finalQty = DB::table('w_jobcard_process_details_t')
                        ->where('job_id', $jobId)->where('process_level', 'FINALPROCESS')->sum('move_qty');

                    if ($process1Qty == $finalQty) {
                        DB::table('w_jobcard_hdr_t')
                            ->where('w_jobs_hdr_id', $jobId)
                            ->update(['store_move_status' => 1, 'job_status' => 'STORE MOVED']);
                    }
                }
            }

            // ── QOH + Material Transactions ─────────────────────────────────
            $trsns = DB::table('m_transaction_types_t')
                ->where('transaction_type_name', 'STORE MOVE')
                ->first();

            if ($trsns) {
                $mtlData = array_merge($base, [
                    'trx_source_type_id' => $trsns->transaction_source_id,
                    'trx_action_id'      => $trsns->transaction_action_id,
                    'trx_type_id'        => $trsns->transaction_type_id,
                    'trx_source_hdr_id'  => $jobId,
                    'trx_source_line_id' => '',
                    'line_number'        => 1,
                    'product_id'         => $job->product_id,
                    'trx_uom'            => $group ? $group->primary_uom_id : null,
                    'trx_date'           => date('Y-m-d'),
                    'subinventory_id'    => $subinvId,
                    'locator_id'         => $locatorId,
                    'trx_qty'            => $qty,
                ]);
                $mtlId = DB::table('m_material_trx_t')->insertGetId($mtlData);

                $qohBase = array_merge($base, [
                    'job_id'          => $jobId,
                    'product_id'      => $job->product_id,
                    'qoh_uom_code_id' => $group ? $group->primary_uom_id : null,
                    'qoh_source'      => 'JOB STORE MOVE',
                    'qoh_trx_date'    => date('Y-m-d'),
                    'qoh_source_id'   => $jobId,
                    'job_process'     => $processLevel,
                    'job_process_name' => $processName,
                    'qualitystatus'   => ($processLevel === 'FINALPROCESS') ? 0 : 1,
                    'qoh_trx_qty'     => $qty,
                    'subinventory_id' => $subinvId,
                    'job_move_date'   => date('Y-m-d', strtotime($trxDate)),
                    'locator_id'      => $locatorId,
                    'batch_number'    => $job->batch_no,
                    'create_trx_id'   => $mtlId,
                ]);
                DB::table('i_qoh_detail_t')->insert($qohBase);

                // JOB ISSUE reversal for the previous process
                if ($prevProcessLevel !== '0' && $prevProcessLevel !== '' && $prevProcessLevel !== null) {
                    $issueTrsn = DB::table('m_transaction_types_t')
                        ->where('transaction_type_name', 'JOB ISSUE')
                        ->first();

                    if ($issueTrsn) {
                        $mtlData2               = $mtlData;
                        $mtlData2['trx_source_type_id'] = $issueTrsn->transaction_source_id;
                        $mtlData2['trx_action_id']      = $issueTrsn->transaction_action_id;
                        $mtlData2['trx_type_id']        = $issueTrsn->transaction_type_id;
                        $mtlData2['trx_qty']            = -$qty;
                        $mtlId2 = DB::table('m_material_trx_t')->insertGetId($mtlData2);

                        $qohBase2                    = $qohBase;
                        $qohBase2['create_trx_id']   = $mtlId2;
                        $qohBase2['qoh_source']       = 'JOB ISSUE';
                        $qohBase2['job_process']      = $prevProcessLevel;
                        $qohBase2['job_process_name'] = $prevProcessName;
                        $qohBase2['qualitystatus']    = 1;
                        $qohBase2['qoh_trx_qty']      = -$qty;
                        DB::table('i_qoh_detail_t')->insert($qohBase2);
                    }
                }
            }

            // ── Update production plan qty for FINALPROCESS ──────────────────
            if ($processLevel === 'FINALPROCESS') {
                $plan = DB::table('w_productionplan_hdr_t')
                    ->where('productionplan_hdr_id', $job->reference_source_id)
                    ->where('product_id', $job->product_id)
                    ->first();
                if ($plan) {
                    DB::table('w_productionplan_hdr_t')
                        ->where('productionplan_hdr_id', $job->reference_source_id)
                        ->where('product_id', $job->product_id)
                        ->update([
                            'plan_qty'    => $plan->plan_qty + $qty,
                            'pending_qty' => max(0, $plan->pending_qty - $qty),
                        ]);
                }
                $planLine = DB::table('w_productionplan_lines_t')
                    ->where('productionplan_hdr_id', $job->reference_source_id)
                    ->where('parent_product', $job->product_id)
                    ->where('process_level', $processLevel)
                    ->first();
                if ($planLine) {
                    DB::table('w_productionplan_lines_t')
                        ->where('productionplan_hdr_id', $job->reference_source_id)
                        ->where('parent_product', $job->product_id)
                        ->where('process_level', $processLevel)
                        ->update([
                            'production_qty' => $planLine->production_qty + $qty,
                            'pending_qty'    => max(0, $planLine->pending_qty - $qty),
                        ]);
                }
            }

            DB::commit();

            $storeMoveComplete = ($processLevel === 'FINALPROCESS');
            return response()->json([
                'success'              => true,
                'message'              => 'Store move saved successfully',
                'store_move_complete'  => $storeMoveComplete,
                'process_level'        => $processLevel,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MaterialController@saveStoreMoveEntry: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // =======================================================================
    // JOB CARD COMPLETION
    // =======================================================================

    /**
     * GET /api/material/completion/list
     * Query: type=production|packing, page, search
     * Production: MATERIAL RECEIVED
     * Packing: STORE MOVED with store_move_status=1
     */
    public function getCompletionList(Request $request)
    {
        try {
            $type    = $request->input('type', 'production');
            $page    = max(1, (int) $request->input('page', 1));
            $search  = trim($request->input('search', ''));
            $perPage = 20;

            $productIds = $this->getEmployeeProductIds();

            $query = DB::table('w_jobcard_hdr_t as j')
                ->join('m_products_t as p', 'p.product_id', '=', 'j.product_id')
                ->join('m_product_groups_t as pg', 'pg.product_group_id', '=', 'p.product_group_id')
                ->leftJoin('w_productionplan_hdr_t as plan', 'plan.productionplan_hdr_id', '=', 'j.reference_source_id')
                ->select(
                    'j.w_jobs_hdr_id',
                    'j.job_no',
                    'j.job_date',
                    'j.job_adjusted_qty',
                    'j.batch_no',
                    'j.job_status',
                    'j.store_move_status',
                    'p.product_id',
                    'p.concatenated_product',
                    'p.product_code',
                    'plan.plan_no',
                    'pg.group_name as product_group'
                );

            if ($type === 'packing') {
                $query->where('pg.group_name', 'FINISHED GOODS')
                    ->whereIn('j.job_status', ['STORE MOVED', 'QA SUBMITTED'])
                    ->where('j.store_move_status', 1);
            } else {
                $query->where('pg.group_name', 'SEMI FINISHED GOODS')
                    ->where('j.job_status', 'MATERIAL RECEIVED');
            }

            if ($productIds !== null) {
                if (empty($productIds)) {
                    return response()->json(['success' => true, 'data' => [], 'total' => 0]);
                }
                $query->whereIn('j.product_id', $productIds);
            }

            if ($search !== '') {
                $like = "%{$search}%";
                $query->where(function ($q) use ($like) {
                    $q->where('j.job_no', 'like', $like)
                        ->orWhere('p.concatenated_product', 'like', $like);
                });
            }

            $total = (clone $query)->count();
            $items = $query->orderBy('j.w_jobs_hdr_id', 'desc')
                ->offset(($page - 1) * $perPage)
                ->limit($perPage)
                ->get();

            return response()->json([
                'success'      => true,
                'data'         => $items,
                'total'        => $total,
                'per_page'     => $perPage,
                'current_page' => $page,
            ]);
        } catch (\Exception $e) {
            Log::error('MaterialController@getCompletionList: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch list'], 500);
        }
    }

    /**
     * POST /api/material/completion/{jobId}
     * Body: { remarks? }
     * Marks the job card as CLOSED
     */
    public function completeJobCard(Request $request, $jobId)
    {
        try {
            $job = DB::table('w_jobcard_hdr_t')->where('w_jobs_hdr_id', $jobId)->first();
            if (!$job) {
                return response()->json(['success' => false, 'message' => 'Job card not found'], 404);
            }

            $allowedStatuses = ['MATERIAL RECEIVED', 'STORE MOVED', 'QA SUBMITTED'];
            if (!in_array($job->job_status, $allowedStatuses)) {
                return response()->json(['success' => false, 'message' => 'Cannot complete job card in current status: ' . $job->job_status]);
            }

            $ctx     = $this->getUserContext();
            $remarks = $request->input('remarks', '');

            DB::table('w_jobcard_hdr_t')
                ->where('w_jobs_hdr_id', $jobId)
                ->update([
                    'job_status'     => 'CLOSED',
                    'remarks'        => $remarks,
                    'created_by'     => $ctx['employeeId'],
                    'last_updated_by' => $ctx['userId'],
                    'updated_at'     => date('Y-m-d'),
                ]);

            return response()->json(['success' => true, 'message' => 'Job card completed successfully']);
        } catch (\Exception $e) {
            Log::error('MaterialController@completeJobCard: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
