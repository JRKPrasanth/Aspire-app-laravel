<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WorkorderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * List all workorders (paginated, with optional search)
     */
    public function index(Request $request)
    {
        try {
            $perPage = (int) $request->input('per_page', 20);
            $page    = max(1, (int) $request->input('page', 1));
            $offset  = ($page - 1) * $perPage;
            $search  = trim($request->input('search', ''));

            $query = DB::table('w_workorder_hdr_t as wh')
                ->leftJoin('tb_users as u', 'u.id', '=', 'wh.created_by')
                ->select(
                    'wh.workorder_hdr_id',
                    'wh.workorder_no',
                    'wh.workorder_date',
                    'wh.source',
                    'wh.shift',
                    'wh.so_reference_number',
                    'wh.remarks',
                    'wh.created_by',
                    DB::raw("u.first_name as created_by_name")
                );

            // Employee product restriction — only show workorders containing mapped products
            $productIds = $this->getEmployeeProductIds();
            if ($productIds !== null) {
                if (!empty($productIds)) {
                    $query->whereExists(function ($sub) use ($productIds) {
                        $sub->select(DB::raw(1))
                            ->from('w_workorder_lines_t as wl')
                            ->whereColumn('wl.workorder_hdr_id', 'wh.workorder_hdr_id')
                            ->whereIn('wl.product_id', $productIds);
                    });
                } else {
                    $query->whereRaw('0 = 1');
                }
            }

            if ($search !== '') {
                $like = "%{$search}%";
                $query->where(function ($q) use ($like) {
                    $q->where('wh.workorder_no', 'like', $like)
                        ->orWhere('wh.source', 'like', $like)
                        ->orWhere('wh.shift', 'like', $like);
                });
            }

            $total      = (clone $query)->count();
            $workorders = $query->orderBy('wh.workorder_hdr_id', 'desc')
                ->offset($offset)
                ->limit($perPage)
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $workorders,
                'pagination' => [
                    'total'        => $total,
                    'per_page'     => $perPage,
                    'current_page' => $page,
                    'last_page'    => max(1, (int) ceil($total / $perPage)),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('WorkorderController@index: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch workorders'], 500);
        }
    }

    /**
     * View a single workorder with its line items
     */
    public function show($id)
    {
        try {
            $workorder = DB::table('w_workorder_hdr_t as wh')
                ->leftJoin('tb_users as u', 'u.id', '=', 'wh.created_by')
                ->select(
                    'wh.workorder_hdr_id',
                    'wh.workorder_no',
                    'wh.workorder_date',
                    'wh.source',
                    'wh.shift',
                    'wh.so_reference_number',
                    'wh.remarks',
                    'wh.created_by',
                    DB::raw("u.first_name as created_by_name")
                )
                ->where('wh.workorder_hdr_id', (int) $id)
                ->first();

            if (!$workorder) {
                return response()->json(['success' => false, 'message' => 'Workorder not found'], 404);
            }

            $lines = DB::table('w_workorder_lines_t as wl')
                ->leftJoin('m_products_t as mp', 'mp.product_id', '=', 'wl.product_id')
                ->leftJoin('m_uom_codes_t as um', 'um.uom_code_id', '=', 'wl.uom_code_id')
                ->select(
                    'wl.workorder_line_id',
                    'wl.line_no',
                    'wl.product_id',
                    'wl.qty',
                    'wl.uom_code_id',
                    'wl.due_date',
                    'wl.comments',
                    'mp.product_code',
                    'mp.concatenated_product',
                    'um.uom_code'
                )
                ->where('wl.workorder_hdr_id', (int) $id)
                ->orderBy('wl.line_no')
                ->get();

            return response()->json([
                'success' => true,
                'data'    => [
                    'workorder' => $workorder,
                    'lines'     => $lines,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('WorkorderController@show: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch workorder'], 500);
        }
    }

    /**
     * Get products for the authenticated user.
     * Employees 152, 525, 676 have full product access.
     * All other employees get only their mapped products via productmapping tables.
     */
    public function getMyProducts()
    {
        // Employee IDs that have full (unrestricted) product access
        $fullAccessEmployeeIds = [151, 152, 160, 676];

        try {
            $user = Auth::user();
            $employeeId = (int) ($user->employee_id ?? 0);

            $baseQuery = DB::table('m_products_t as mp')
                ->leftJoin('m_uom_codes_t as um', 'um.uom_code_id', '=', 'mp.primary_uom_id')
                ->select(
                    'mp.product_id',
                    'mp.product_code',
                    'mp.concatenated_product',
                    'mp.primary_uom_id as uom_code_id',
                    'um.uom_code'
                )
                ->where('mp.active', 'Yes')
                ->orderBy('mp.concatenated_product');

            // Full-access employees: return all products
            if (in_array($employeeId, $fullAccessEmployeeIds)) {
                $products = $baseQuery->get();

                return response()->json([
                    'success'      => true,
                    'data'         => $products,
                    'full_access'  => true,
                ]);
            }

            // No employee_id on user record
            if (!$employeeId) {
                return response()->json([
                    'success' => true,
                    'data'    => [],
                    'message' => 'No employee mapping found for this user',
                ]);
            }

            // Standard employees: return only mapped products
            // prd_id is varchar so we cast both sides for a safe join
            $products = DB::table('productmapping_hdr_tbl as ph')
                ->join('productmapping_lines_tbl as pl', 'pl.productmapping_id', '=', 'ph.productmapping_id')
                ->join('m_products_t as mp', DB::raw('CAST(mp.product_id AS CHAR)'), '=', DB::raw('CAST(pl.prd_id AS CHAR)'))
                ->leftJoin('m_uom_codes_t as um', 'um.uom_code_id', '=', 'mp.primary_uom_id')
                ->select(
                    'mp.product_id',
                    'mp.product_code',
                    'mp.concatenated_product',
                    'mp.primary_uom_id as uom_code_id',
                    'um.uom_code'
                )
                ->where('ph.employee_id', $employeeId)
                ->distinct()
                ->orderBy('mp.concatenated_product')
                ->get();

            return response()->json([
                'success'     => true,
                'data'        => $products,
                'full_access' => false,
            ]);
        } catch (\Exception $e) {
            Log::error('WorkorderController@getMyProducts: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch products'], 500);
        }
    }

    /**
     * Create a new workorder
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'shift'               => 'required|string|max:50',
                'lines'               => 'required|array|min:1',
                'lines.*.product_id'  => 'required|integer|min:1',
                'lines.*.qty'         => 'required|numeric|min:0.01',
                'lines.*.uom_code_id' => 'required|integer|min:1',
                'lines.*.due_date'    => 'required|date_format:Y-m-d',
            ]);

            // Generate workorder number from the last one in the table
            $lastNo  = DB::table('w_workorder_hdr_t')
                ->orderBy('workorder_hdr_id', 'desc')
                ->value('workorder_no');

            $nextNum = 1;
            if ($lastNo && preg_match('/(\d+)$/', $lastNo, $matches)) {
                $nextNum = (int) $matches[1] + 1;
            }
            $workorderNo = 'WOR' . $nextNum;

            $organizationId = $user->organization_id ?? 1;
            $companyId      = $user->company_id      ?? 1;

            DB::beginTransaction();

            $headerId = DB::table('w_workorder_hdr_t')->insertGetId([
                'workorder_no'       => $workorderNo,
                'workorder_date'     => Carbon::today()->format('Y-m-d'),
                'source'             => 'STANDARD',
                'shift'              => $request->input('shift'),
                'remarks'            => $request->input('remarks', ''),
                'so_reference_number' => null,
                'reference_id'       => '0',
                'organization_id'    => $organizationId,
                'company_id'         => $companyId,
                'created_by'         => $user->id,
                'last_updated_by'    => $user->id,
                'created_at'         => Carbon::now(),
                'updated_at'         => Carbon::now(),
            ]);

            $lineNo = 1;
            foreach ($request->input('lines') as $line) {
                DB::table('w_workorder_lines_t')->insert([
                    'workorder_hdr_id' => $headerId,
                    'line_no'          => $lineNo++,
                    'product_id'       => $line['product_id'],
                    'qty'              => $line['qty'],
                    'uom_code_id'      => $line['uom_code_id'],
                    'due_date'         => $line['due_date'],
                    'comments'         => $line['comments'] ?? null,
                    'created_by'       => $user->id,
                    'last_updated_by'   => $user->id,
                    'location_id'      => $user->location_id ?? 1,
                    'organization_id'  => $organizationId,
                    'company_id'       => $companyId,
                    'created_at'       => Carbon::now(),
                    'updated_at'       => Carbon::now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success'          => true,
                'message'          => 'Workorder created successfully',
                'workorder_no'     => $workorderNo,
                'workorder_hdr_id' => $headerId,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('WorkorderController@store: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create workorder'], 500);
        }
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Get product IDs the current employee is allowed to see.
     * Returns null  = no restriction (management).
     * Returns int[] = restrict to these IDs (empty array = no access).
     */
    private function getEmployeeProductIds(): ?array
    {
        $user       = Auth::user();
        $employeeId = (int)($user->employee_id ?? 0);

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
}
