<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class PurchaseOrderController extends Controller
{
    /**
     * Get all purchase orders with pagination and filtering
     * GET /api/purchase/orders/all
     * Query params: search, po_status, po_type, from_date, to_date, month, year, sort_by, sort_order
     */
    public function getAllPurchaseOrders(Request $request)
    {
        try {
            $user = Auth::user();
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 20);
            $offset = ($page - 1) * $perPage;

            // Get filter parameters
            $search = $request->input('search');
            $poStatus = $request->input('po_status');
            $poType = $request->input('po_type');
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');
            $month = $request->input('month');
            $year = $request->input('year');
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');

            Log::info('[PurchaseOrderAPI] Fetching orders with filters', [
                'search' => $search,
                'po_status' => $poStatus,
                'po_type' => $poType,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'month' => $month,
                'year' => $year,
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ]);

            // Get date range in database for debugging
            $dateRange = DB::table('p_po_hdr_t')
                ->selectRaw('MIN(po_date) as min_date, MAX(po_date) as max_date, COUNT(*) as total_orders')
                ->where('company_id', $user->company_id)
                ->first();

            Log::info('[PurchaseOrderAPI] Orders Date Range in DB', [
                'min_date' => $dateRange->min_date,
                'max_date' => $dateRange->max_date,
                'total_orders' => $dateRange->total_orders,
            ]);

            // Build query using Query Builder
            $query = DB::table('p_po_hdr_t as ph')
                ->leftJoin('m_supplier_t as s', 's.supplier_id', '=', 'ph.supplier_id')
                ->leftJoin('hr_employee_t as e', 'e.employee_id', '=', 'ph.created_by')
                ->select(
                    'ph.po_hdr_id',
                    'ph.po_number',
                    'ph.po_date',
                    'ph.delivery_date',
                    'ph.supplier_id',
                    's.supplier_name',
                    'ph.po_status',
                    'ph.po_type',
                    'ph.po_for_verdura',
                    'ph.po_grand_total',
                    'ph.created_by',
                    'ph.created_at',
                    'e.first_name as created_by_name',
                )
                ->where('ph.company_id', $user->company_id);

            // Search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('ph.po_number', 'like', '%' . $search . '%')
                        ->orWhere('s.supplier_name', 'like', '%' . $search . '%')
                        ->orWhere('ph.supplier_reference_no', 'like', '%' . $search . '%');
                });
            }

            // Status filter
            if ($poStatus) {
                $query->where('ph.po_status', $poStatus);
            }

            // Type filter
            if ($poType) {
                $query->where('ph.po_type', $poType);
            }

            // Date filters
            $dateFiltersApplied = [];
            if ($fromDate) {
                $query->where('ph.po_date', '>=', $fromDate);
                $dateFiltersApplied[] = "from_date: $fromDate";
            }

            if ($toDate) {
                $query->where('ph.po_date', '<=', $toDate);
                $dateFiltersApplied[] = "to_date: $toDate";
            }

            if ($month) {
                $query->whereRaw('MONTH(ph.po_date) = ?', [$month]);
                $dateFiltersApplied[] = "month: $month";
            }

            if ($year) {
                $query->whereRaw('YEAR(ph.po_date) = ?', [$year]);
                $dateFiltersApplied[] = "year: $year";
            }

            if (!empty($dateFiltersApplied)) {
                Log::info('[PurchaseOrderAPI] Date filters applied: ' . implode(', ', $dateFiltersApplied));
            }

            // Validate sort column
            $allowedSortColumns = ['created_at', 'po_date', 'po_grand_total', 'po_status'];
            if (!in_array($sortBy, $allowedSortColumns)) {
                $sortBy = 'created_at';
            }

            // Validate sort order
            $sortOrder = strtolower($sortOrder) === 'asc' ? 'asc' : 'desc';

            // Apply sorting
            $query->orderBy('ph.' . $sortBy, $sortOrder);

            // Get SQL query for logging
            $sql = $query->toSql();
            $bindings = $query->getBindings();
            Log::info('[PurchaseOrderAPI] Final SQL Query', ['sql' => $sql, 'bindings' => $bindings]);

            // Get total count for pagination (before applying limit)
            $totalCount = $query->count();

            // Apply pagination
            $orders = $query->offset($offset)->limit($perPage)->get();

            Log::info('[PurchaseOrderAPI] Query results', [
                'total_count' => $totalCount,
                'returned_count' => count($orders),
                'page' => $page,
                'filters_active' => [
                    'search' => !empty($search),
                    'status' => !empty($poStatus),
                    'type' => !empty($poType),
                    'date_filters' => !empty($dateFiltersApplied),
                ],
            ]);

            $totalPages = ceil($totalCount / $perPage);
            $hasMore = $page < $totalPages;

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase orders retrieved successfully',
                'data' => $orders,
                'pagination' => [
                    'current_page' => (int) $page,
                    'per_page' => (int) $perPage,
                    'total' => (int) $totalCount,
                    'last_page' => (int) $totalPages,
                    'has_more' => $hasMore,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('[PurchaseOrderAPI] Error fetching orders: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve purchase orders',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get purchase order by ID with full details
     * GET /api/purchase/orders/{id}
     */
    public function getPurchaseOrderById($id)
    {
        try {
            $user = Auth::user();

            // Get header details
            $order = DB::table('p_po_hdr_t')
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_po_hdr_t.supplier_id')
                ->leftJoin('m_supplier_sites_t', 'm_supplier_sites_t.supplier_site_id', '=', 'p_po_hdr_t.suppliersite_id')
                ->leftJoin('m_payment_terms_t', 'm_payment_terms_t.payment_term_id', '=', 'p_po_hdr_t.payment_term_id')
                ->leftJoin('m_payment_methods_t', 'm_payment_methods_t.payment_method_id', '=', 'p_po_hdr_t.default_payment_method_id')
                ->leftJoin('m_delivery_terms_t', 'm_delivery_terms_t.delivery_terms_id', '=', 'p_po_hdr_t.delivery_terms_id')
                ->leftJoin('m_projects_t', 'm_projects_t.project_id', '=', 'p_po_hdr_t.project_id')
                ->leftJoin('m_organizations_t', 'm_organizations_t.organization_id', '=', 'p_po_hdr_t.organization_id')
                ->leftJoin('tb_users', 'tb_users.id', '=', 'p_po_hdr_t.created_by')
                ->select(
                    'p_po_hdr_t.*',
                    'm_supplier_t.supplier_name',
                    'm_supplier_sites_t.supplier_site_name',
                    'm_supplier_sites_t.address as supplier_site_address',
                    'm_supplier_sites_t.city as supplier_site_city',
                    'm_supplier_sites_t.state as supplier_site_state',
                    'm_supplier_sites_t.pincode as supplier_site_pincode',
                    'm_payment_terms_t.payment_term_name',
                    'm_payment_methods_t.payment_method_name',
                    'm_delivery_terms_t.delivery_term_name',
                    'm_projects_t.project_name',
                    'm_organizations_t.organization_name',
                    'tb_users.username as created_by_name'
                )
                ->where('p_po_hdr_t.po_hdr_id', $id)
                ->where('p_po_hdr_t.company_id', $user->company_id)
                ->first();

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Purchase order not found',
                ], 404);
            }

            // Get line items
            $lines = DB::table('p_po_lines_t')
                ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 'p_po_lines_t.product_id')
                ->leftJoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'p_po_lines_t.uom_code_id')
                ->leftJoin('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'p_po_lines_t.tax_group_id')
                ->leftJoin('f_gst_code_hdr_t', 'f_gst_code_hdr_t.gst_code_hdr_id', '=', 'p_po_lines_t.hsn_code')
                ->select(
                    'p_po_lines_t.*',
                    'm_products_t.product_id',
                    'm_products_t.product_code',
                    'm_products_t.concatenated_product',
                    'm_uom_codes_t.uom_code',
                    'm_tax_group_t.tax_group_name',
                    'f_gst_code_hdr_t.classification_code as hsn_code_name'
                )
                ->where('p_po_lines_t.po_hdr_id', $id)
                ->orderBy('p_po_lines_t.line_no')
                ->get();

            // Get bill to and ship to addresses
            $billToAddress = $this->PurchaseOrder_getLocationAddress($order->bill_to_location_id);
            $shipToAddress = $this->PurchaseOrder_getLocationAddress($order->ship_to_location_id);

            // ensure each line has qoh_qty if not stored already
            foreach ($lines as $line) {
                if (!isset($line->qoh_qty) || $line->qoh_qty === null) {
                    // compute QOH same way quotation API does
                    $qohQuery = "
                        SELECT SUM(f.qty - IFNULL(f.qtyy, 0)) as qoh_qty 
                        FROM (
                            SELECT SUM(qoh_trx_qty) as qty, 0 as qtyy, product_id 
                            FROM i_qoh_detail_t 
                            WHERE product_id = ? AND company_id = ? 
                            GROUP BY product_id
                            UNION ALL
                            SELECT 0 as qty, SUM(reserv_trx_qty) as qtyy, product_id 
                            FROM i_reservation_detail_t 
                            WHERE product_id = ? AND company_id = ? 
                            GROUP BY product_id
                        ) f
                    ";
                    $qoh = DB::select($qohQuery, [$line->product_id, $user->company_id, $line->product_id, $user->company_id]);
                    $line->qoh_qty = (!empty($qoh) && isset($qoh[0]->qoh_qty)) ? $qoh[0]->qoh_qty : 0;
                }
            }

            // Calculate summary — use header values if present, otherwise compute from lines
            $poSubTotal = isset($order->po_sub_total) ? (float) $order->po_sub_total : (float) $lines->sum('line_sub_total');
            $poTaxTotal = isset($order->po_tax_total) ? (float) $order->po_tax_total : (float) $lines->sum('tax_amount');

            // Ensure response includes these values for clients expecting them
            $order->po_sub_total = $poSubTotal;
            $order->po_tax_total = $poTaxTotal;

            $summary = [
                'subtotal' => $poSubTotal,
                'tax_total' => $poTaxTotal,
                'total_items' => count($lines),
                'total_quantity' => $lines->sum('qty'),
            ];

            // Build attachments list: API uploads (files_t) + legacy web uploads (p_po_hdr_t.attachment_file)
            $attachments = [];

            // Track unique filenames already found so backward-compat doesn't create duplicates
            // (API upload also writes filename to attachment_file column for web compatibility)
            $seenFileNames = [];

            $filesFromTable = DB::table('files_t')
                ->where('entity_type', 'purchase_order')
                ->where('entity_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($filesFromTable as $file) {
                $attachments[] = [
                    'id'          => $file->id,
                    'name'        => $file->file_name,
                    'url'         => $file->file_url,
                    'path'        => $file->file_path,
                    'size'        => $file->file_size,
                    'mime_type'   => $file->mime_type,
                    'source'      => 'api',
                    'uploaded_at' => $file->created_at,
                ];
                // Record the stored filename (basename) to avoid duplicates from backward-compat
                $seenFileNames[] = basename($file->file_path);
            }

            // Backward-compat: web-uploaded files stored in attachfile_name JSON column.
            // Matches web save() function: PO{id} folder (no underscore), attachfile_name column.
            // Skip any filename that already came from files_t above.
            if (!empty($order->attachfile_name)) {
                $webFiles = json_decode($order->attachfile_name, true) ?? [];
                if (is_array($webFiles)) {
                    foreach ($webFiles as $fileName) {
                        // Skip duplicates already present in files_t
                        if (in_array($fileName, $seenFileNames)) {
                            continue;
                        }

                        $filePath   = 'Uploads/purchaseorder/PO' . $id . '/' . $fileName;
                        $fullPath   = public_path($filePath);
                        $fileExists = file_exists($fullPath);

                        $attachments[] = [
                            'id'             => null,
                            'name'           => $fileName,
                            'url'            => $fileExists ? url($filePath) : null,
                            'path'           => $fileExists ? '/' . $filePath : null,
                            'size'           => $fileExists ? filesize($fullPath) : null,
                            'mime_type'      => $fileExists ? mime_content_type($fullPath) : null,
                            'source'         => 'web',
                            'uploaded_at'    => null,
                            'exists_on_disk' => $fileExists,
                        ];
                    }
                }
            }

            Log::info('[PurchaseOrderAPI] Attachments for order ' . $id . ': ' . count($attachments));

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase order retrieved successfully',
                'data' => [
                    'order'          => $order,
                    'lines'          => $lines,
                    'bill_to_address' => $billToAddress,
                    'ship_to_address' => $shipToAddress,
                    'summary'        => $summary,
                    'attachments'    => $attachments,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('[PurchaseOrderAPI] Error fetching order details: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve purchase order details',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create new purchase order
     * POST /api/purchase/orders
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();
            $payload = $request->all();

            Log::info('[PurchaseOrderAPI] Creating new purchase order', ['user_id' => $user->id]);

            // Validate required fields
            $validator = Validator::make($payload, [
                'supplier_id' => 'required|integer',
                'suppliersite_id' => 'required|integer',
                'po_date' => 'required|date',
                'pricelist_id' => 'required|integer',
                'delivery_terms_id' => 'required|integer',
                'payment_term_id' => 'required|integer',
                'freight_terms_id' => 'required|integer',
                'ship_to_location_id' => 'required|integer',
                'freight_carrier_id' => 'required|integer',
                'lines' => 'required|array|min:1',
                // optional quantity-on-hand value for informational purposes
                'lines.*.qoh_qty' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Generate PO number and count
            list($poNumber, $poCount) = $this->generatePONumber($user->company_id);

            // Calculate totals from line items
            $productSubTotal = 0;
            $productTaxTotal = 0;

            foreach ($payload['lines'] as $line) {
                // Calculate line_sub_total if not provided: qty * unit_price - discount_amount
                if (isset($line['line_sub_total'])) {
                    $lineSubTotal = (float) $line['line_sub_total'];
                } else {
                    $qty = (float) ($line['qty'] ?? 0);
                    $unitPrice = (float) ($line['unit_price'] ?? 0);
                    $discountAmount = (float) ($line['discount_amount'] ?? 0);
                    $lineSubTotal = ($qty * $unitPrice) - $discountAmount;
                }

                $lineTax = (float) ($line['tax_amount'] ?? 0);

                $productSubTotal += $lineSubTotal;
                $productTaxTotal += $lineTax;
            }

            // Process additional charges (same format as quotations)
            $packingCharge = (float) ($payload['packing_charges'] ?? 0);
            $packingTax = (float) ($payload['packing_tax_amount'] ?? 0);
            $packingTaxGroupId = (int) ($payload['packing_tax_group_id'] ?? 0);
            $packingChargeWithTax = $packingCharge + $packingTax;
            $packingChargesTaxString = ($packingTaxGroupId && $packingTax) ? "{$packingTaxGroupId},{$packingTax}" : '';

            $transportCharge = (float) ($payload['transport_charges'] ?? 0);
            $transportTax = (float) ($payload['transport_tax_amount'] ?? 0);
            $transportTaxGroupId = (int) ($payload['transport_tax_group_id'] ?? 0);
            $transportChargeWithTax = $transportCharge + $transportTax;
            $transportChargesTaxString = ($transportTaxGroupId && $transportTax) ? "{$transportTaxGroupId},{$transportTax}" : '';

            $insuranceCharge = (float) ($payload['insurance_charges'] ?? 0);
            $insuranceTax = (float) ($payload['insurance_charge_tax_amount'] ?? 0);
            $insuranceTaxGroupId = (int) ($payload['insurance_charge_tax_group_id'] ?? 0);
            $insuranceChargeWithTax = $insuranceCharge + $insuranceTax;
            $insuranceChargesTaxString = ($insuranceTaxGroupId && $insuranceTax) ? "{$insuranceTaxGroupId},{$insuranceTax}" : '';

            $unloadingCharge = (float) ($payload['unloading_charges'] ?? 0);
            $unloadingTax = (float) ($payload['unloading_tax_amount'] ?? 0);
            $unloadingTaxGroupId = (int) ($payload['unloading_tax_group_id'] ?? 0);
            $unloadingChargeWithTax = $unloadingCharge + $unloadingTax;
            $unloadingChargesTaxString = ($unloadingTaxGroupId && $unloadingTax) ? "{$unloadingTaxGroupId},{$unloadingTax}" : '';

            $otherFreightCharge = (float) ($payload['other_frieght_amount'] ?? 0);
            $otherFreightTax = (float) ($payload['other_freight_tax_amount'] ?? 0);
            $otherFreightTaxGroupId = (int) ($payload['other_freight_tax_group_id'] ?? 0);
            $otherFreightChargeWithTax = $otherFreightCharge + $otherFreightTax;
            $otherFreightChargesTaxString = ($otherFreightTaxGroupId && $otherFreightTax) ? "{$otherFreightTaxGroupId},{$otherFreightTax}" : '';

            $otherTaxOnly = (float) ($payload['other_tax_amount'] ?? 0);
            $otherTaxGroupId = (int) ($payload['other_tax_group_id'] ?? 0);
            $otherTaxAmountTaxString = ($otherTaxGroupId && $otherTaxOnly) ? "{$otherTaxGroupId},{$otherTaxOnly}" : '';

            // Calculate final totals
            $subTotal = $productSubTotal;
            $totalTaxAmount = $productTaxTotal; // ONLY product tax
            $chargesWithTaxTotal = $packingChargeWithTax + $transportChargeWithTax + $insuranceChargeWithTax + $unloadingChargeWithTax + $otherFreightChargeWithTax;
            $grandTotal = $subTotal + $chargesWithTaxTotal + $totalTaxAmount + $otherTaxOnly;

            // Get approver based on value
            $approverId = $this->getApproverForAmount('poorder', $grandTotal);

            // Determine PO status based on approver
            $poStatus = 'INITIATED';
            if ($approverId == '0') {
                // No approver needed - auto approve
                $poStatus = ($payload['po_status'] ?? 'INITIATED') === 'DRAFT' ? 'DRAFT' : 'APPROVED';
                $approverId = $user->id;
            }

            // Insert header
            $poHdrId = DB::table('p_po_hdr_t')->insertGetId([
                'po_number' => $poNumber,
                'po_count' => $poCount,
                'po_date' => $payload['po_date'],
                'delivery_date' => $payload['delivery_date'] ?? null,
                'supplier_id' => $payload['supplier_id'],
                'suppliersite_id' => $payload['suppliersite_id'],
                'po_type' => $payload['po_type'] ?? 'STANDARD',
                'po_for_verdura' => $payload['po_for_verdura'] ?? 'No',
                'po_status' => $poStatus,
                'payment_term_id' => $payload['payment_term_id'] ?? null,
                'default_payment_method_id' => $payload['payment_method_id'] ?? null,
                'delivery_terms_id' => $payload['delivery_terms_id'] ?? null,
                'freight_terms_id' => $payload['freight_terms_id'] ?? null,
                'freight_carrier_id' => $payload['freight_carrier_id'] ?? null,
                'insurance_term_id' => $payload['insurance_term_id'] ?? null,
                'po_pricelist_id' => $payload['pricelist_id'] ?? null,
                'project_id' => $payload['project_id'] ?? null,
                'organization_id' => $payload['organization_id'] ?? 1,
                'bill_to_location_id' => $payload['bill_to_location_id'] ?? null,
                'ship_to_location_id' => $payload['ship_to_location_id'] ?? null,
                'reference_number' => $payload['reference_number'] ?? null,
                'reference_id' => $payload['reference_id'] ?? null,
                'source' => $payload['source'] ?? 'STANDARD',
                'supplier_reference_no' => $payload['supplier_reference_no'] ?? null,
                'remarks' => $payload['remarks'] ?? null,
                'po_tax_total' => $totalTaxAmount,
                'po_grand_total' => $grandTotal,
                'transport_charges' => $transportCharge,
                'transport_charges_tax' => $transportChargesTaxString ?? null,
                'unloading_charges' => $unloadingCharge,
                'unloading_charges_tax' => $unloadingChargesTaxString ?? null,
                'insurance_charges' => $insuranceCharge,
                'insurance_charges_tax' => $insuranceChargesTaxString ?? null,
                'packing_charges' => $packingCharge,
                'packing_charges_tax' => $packingChargesTaxString ?? null,
                'other_frieght_amount' => $otherFreightCharge,
                'other_frieght_amount_tax' => $otherFreightChargesTaxString ?? null,
                'other_tax_amount' => $otherTaxOnly,
                'other_tax_amount_tax' => $otherTaxAmountTaxString ?? null,
                'amendment' => '0',
                'approver_id' => $approverId,
                'currency' => $payload['currency'] ?? 37,
                'company_id' => $user->company_id ?? 1,
                'location_id' => $user->location_id ?? 1,
                'created_by' => $user->id,
                'last_updated_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert line items
            foreach ($payload['lines'] as $index => $line) {
                // Calculate line_sub_total if not provided
                if (isset($line['line_sub_total'])) {
                    $lineSubTotal = (float) $line['line_sub_total'];
                } else {
                    $qty = (float) ($line['qty'] ?? 0);
                    $unitPrice = (float) ($line['unit_price'] ?? 0);
                    $discountAmount = (float) ($line['discount_amount'] ?? 0);
                    $lineSubTotal = ($qty * $unitPrice) - $discountAmount;
                }

                // Calculate line_total if not provided: line_sub_total + tax_amount
                $lineTax = (float) ($line['tax_amount'] ?? 0);
                $lineTotal = isset($line['line_total']) ? (float) $line['line_total'] : ($lineSubTotal + $lineTax);

                $insertData = [
                    'po_hdr_id' => $poHdrId,
                    'line_no' => $index + 1,
                    'product_id' => $line['product_id'],
                    'qty' => $line['qty'],
                    'pending_qty' => $line['qty'], // Initially all qty is pending
                    'uom_code_id' => $line['uom_code_id'] ?? null,
                    'unit_price' => $line['unit_price'],
                    'discount_percentage' => $line['discount_percentage'] ?? 0,
                    'discount_amount' => $line['discount_amount'] ?? 0,
                    'line_sub_total' => $lineSubTotal,
                    'tax_group_id' => $line['tax_group_id'] ?? null,
                    'tax_amount' => $line['tax_amount'] ?? 0,
                    'line_total' => $lineTotal,
                    // API clients may send either gst_code_hdr_id (HSN id) or hsn_code (numeric/code)
                    // fallback preserves compatibility with web version
                    'hsn_code' => $line['gst_code_hdr_id'] ?? $line['hsn_code'] ?? null,
                    'promised_date' => $line['promised_date'] ?? null,
                    'comments' => $line['remarks'] ?? $line['comments'] ?? null,
                    'company_id' => $user->company_id ?? 1,
                    'location_id' => $user->location_id ?? 1,
                    'organization_id' => $payload['organization_id'] ?? 1,
                    'created_by' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if (Schema::hasColumn('p_po_lines_t', 'qoh_qty')) {
                    $insertData['qoh_qty'] = $line['qoh_qty'] ?? null;
                }
                DB::table('p_po_lines_t')->insert($insertData);
            }

            DB::commit();

            Log::info('[PurchaseOrderAPI] Purchase order created successfully', ['po_hdr_id' => $poHdrId, 'po_number' => $poNumber]);

            return response()->json([
                'success' => true,
                'status' => 'success',
                'message' => 'Purchase order created successfully',
                'id' => $poHdrId,
                'po_hdr_id' => $poHdrId,
                'po_number' => $poNumber,
                'po_status' => $poStatus,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[PurchaseOrderAPI] Error creating purchase order: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create purchase order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Approve or reject purchase order
     * POST /api/purchase/orders/{id}/approve
     */
    public function approve(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();
            $decision = $request->input('decision'); // APPROVED or REJECTED
            $comments = $request->input('comments', '');

            if (!in_array($decision, ['APPROVED', 'REJECTED'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid decision. Must be APPROVED or REJECTED',
                ], 422);
            }

            $order = DB::table('p_po_hdr_t')
                ->where('po_hdr_id', $id)
                ->where('company_id', $user->company_id)
                ->first();

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Purchase order not found',
                ], 404);
            }

            if ($order->po_status !== 'INITIATED') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only INITIATED orders can be approved/rejected',
                ], 422);
            }

            DB::table('p_po_hdr_t')
                ->where('po_hdr_id', $id)
                ->update([
                    'po_status'       => $decision,
                    'last_updated_by' => $user->id,
                    'updated_at'      => now(),
                ]);

            DB::commit();

            Log::info('[PurchaseOrderAPI] Purchase order ' . $decision, ['po_hdr_id' => $id, 'user_id' => $user->id]);

            return response()->json([
                'status' => 'success',
                'message' => "Purchase order {$decision} successfully",
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[PurchaseOrderAPI] Error approving order: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process approval',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Prepare PO creation data from an APPROVED quotation
     * GET /api/purchase/quotes/{id}/prepare-po
     *
     * Validates:
     *  - Quotation exists and belongs to authenticated user's company
     *  - Status is APPROVED
     *  - No PO already created from this quotation in the current fiscal year
     *
     * Returns full quotation header + lines formatted for the PO create form.
     */
    public function prepareFromQuotation($id)
    {
        try {
            $user = Auth::user();

            // ── Fetch quotation header ──────────────────────────────────────
            $quotation = DB::table('p_quotation_hdr_t as qh')
                ->leftJoin('m_supplier_t as s',         'qh.supplier_id',        '=', 's.supplier_id')
                ->leftJoin('m_supplier_sites_t as ss',  'qh.supplier_site_id',   '=', 'ss.supplier_site_id')
                ->leftJoin('m_location_t as bill_loc',  'qh.bill_to_location_id', '=', 'bill_loc.location_id')
                ->leftJoin('m_location_t as ship_loc',  'qh.ship_to_location_id', '=', 'ship_loc.location_id')
                ->select(
                    'qh.quotation_hdr_id',
                    'qh.quotation_no',
                    'qh.quotation_date',
                    'qh.delivery_date',
                    'qh.supplier_id',
                    's.supplier_name',
                    'qh.supplier_site_id',
                    'ss.supplier_site_name',
                    'qh.quote_pricelist_id',
                    'qh.freight_carrier_id',
                    'qh.quote_status',
                    'qh.quotation_type',
                    'qh.default_payment_method_id',
                    'qh.payment_term_id',
                    'qh.delivery_terms_id',
                    'qh.insurance_term_id',
                    'qh.bill_to_location_id',
                    'bill_loc.location_name as bill_to_location',
                    'qh.ship_to_location_id',
                    'ship_loc.location_name as ship_to_location',
                    'qh.supplier_ref_no',
                    'qh.remarks',
                    'qh.packing_charges',
                    'qh.insurance_charges',
                    'qh.unloading_charges',
                    'qh.transport_charges',
                    'qh.other_frieght_amount',
                    'qh.other_tax_amount'
                )
                ->where('qh.quotation_hdr_id', $id)
                ->first();

            if (!$quotation) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Quotation not found',
                ], 404);
            }

            // ── Validate approval status ────────────────────────────────────
            if ($quotation->quote_status !== 'APPROVED') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Only APPROVED quotations can be converted to a Purchase Order. Current status: ' . $quotation->quote_status,
                ], 422);
            }

            // ── Check for duplicate PO in current fiscal year ───────────────
            $currentMonth  = (int) date('m');
            $fiscalYearStart = ($currentMonth > 3)
                ? date('Y') . '-04-01'
                : (date('Y') - 1) . '-04-01';

            $existingPO = DB::table('p_po_hdr_t')
                ->where('reference_id', $id)
                ->where('source', 'QUOTATION')
                ->where('po_date', '>=', $fiscalYearStart)
                ->first();

            if ($existingPO) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'A Purchase Order (' . $existingPO->po_number . ') already exists for this quotation in the current fiscal year.',
                ], 422);
            }

            // ── Fetch line items ────────────────────────────────────────────
            $lines = DB::table('p_quotation_lines_t as ql')
                ->leftJoin('m_products_t as p',      'ql.product_id',   '=', 'p.product_id')
                ->leftJoin('m_uom_codes_t as uom',   'ql.uom_code_id',  '=', 'uom.uom_code_id')
                ->leftJoin('f_gst_code_hdr_t as hsn', 'ql.hsn_code',    '=', 'hsn.gst_code_hdr_id')
                ->leftJoin('m_tax_group_t as tg',    'ql.tax_group_id', '=', 'tg.tax_group_id')
                ->select(
                    'ql.quotation_line_id',
                    'ql.line_no',
                    'ql.product_id',
                    'p.product_code',
                    'p.concatenated_product as product_name',
                    'ql.manufacturer_partno_id',
                    'ql.uom_code_id',
                    'uom.uom_code',
                    'ql.qty',
                    'ql.unit_price',
                    'ql.discount_percentage',
                    'ql.discount_amount',
                    'ql.hsn_code as gst_code_hdr_id',
                    'hsn.classification_code as hsn_display_code',
                    'ql.tax_group_id',
                    'tg.tax_group_name',
                    DB::raw('CASE WHEN (ql.qty * ql.unit_price - ql.discount_amount) > 0
                                  THEN ROUND(ql.tax_amount / (ql.qty * ql.unit_price - ql.discount_amount) * 100, 4)
                                  ELSE 0
                             END as tax_percentage'),
                    'ql.tax_amount',
                    'ql.line_total',
                    'ql.promised_date',
                    'ql.comments'
                )
                ->where('ql.quotation_hdr_id', $id)
                ->orderBy('ql.line_no')
                ->get();

            // ── Build formatted address strings ─────────────────────────────
            $bill_to_address = '';
            $ship_to_address = '';

            $buildAddress = function ($locationId) {
                $loc = DB::table('m_location_t as ml')
                    ->leftJoin('m_cities_t as mc',    'ml.city_id',    '=', 'mc.city_id')
                    ->leftJoin('m_states_t as ms',    'ml.state_id',   '=', 'ms.state_id')
                    ->leftJoin('m_countries_t as mco', 'ml.country_id', '=', 'mco.country_id')
                    ->select(
                        'ml.address',
                        'ml.street_name',
                        'ml.area',
                        'mc.city_name',
                        'ms.state_name',
                        'mco.country_name',
                        'ml.pincode'
                    )
                    ->where('ml.location_id', $locationId)
                    ->first();
                if (!$loc) return '';
                $parts = array_filter([
                    ($loc->address && $loc->address !== 'null') ? $loc->address : null,
                    $loc->street_name,
                    $loc->area,
                    $loc->city_name,
                    $loc->state_name,
                    $loc->country_name,
                    $loc->pincode,
                ]);
                return implode(', ', $parts);
            };

            if ($quotation->bill_to_location_id) {
                $bill_to_address = $buildAddress($quotation->bill_to_location_id);
            }
            if ($quotation->ship_to_location_id) {
                $ship_to_address = $buildAddress($quotation->ship_to_location_id);
            }

            Log::info('[PurchaseOrderAPI] Quotation prepared for PO creation', [
                'quotation_hdr_id' => $id,
                'quotation_no'     => $quotation->quotation_no,
                'user_id'          => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'status'  => 'success',
                'message' => 'Quotation data prepared for PO creation',
                'data'    => [
                    'quotation'       => $quotation,
                    'lines'           => $lines,
                    'bill_to_address' => $bill_to_address,
                    'ship_to_address' => $ship_to_address,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseOrderAPI] Error preparing from quotation: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to prepare quotation data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper: Generate PO number in format YYYY-YY/NNNN (e.g., 2025-26/3845)
     */
    private function generatePONumber($companyId)
    {
        // Determine fiscal year (April-March)
        $currentMonth = (int) date('m');
        if ($currentMonth > 3) {
            $startYear = date('Y');
            $endYear = date('y', strtotime('+1 year'));
        } else {
            $startYear = date('Y', strtotime('-1 year'));
            $endYear = date('y');
        }

        // Get next sequence number
        $lastPO = DB::table('p_po_hdr_t')
            ->where('company_id', $companyId)
            ->orderBy('po_count', 'desc')
            ->first();

        $poCount = $lastPO ? ((int) $lastPO->po_count + 1) : 1;
        $poNumber = $startYear . '-' . $endYear . '/' . $poCount;

        return [$poNumber, $poCount];
    }

    /**
     * Helper: Get approver based on amount (value-based approval)
     * Matches Approvaldatacheck() logic from Controller.php
     */
    private function getApproverForAmount($module, $amount)
    {
        try {
            $module_name = $this->getModuleApprovalName($module);

            $approval_settings = DB::select(
                "SELECT m_approvalsettings_hdr_t.*, m_approvalsettings_line_t.* 
                 FROM m_approvalsettings_hdr_t 
                 LEFT JOIN m_approvalsettings_line_t 
                    ON m_approvalsettings_hdr_t.approvalsettings_hdr_id = m_approvalsettings_line_t.approvalsettings_hdr_id
                 WHERE m_approvalsettings_hdr_t.module_name = ? 
                 AND ? BETWEEN m_approvalsettings_line_t.value_from AND m_approvalsettings_line_t.value_to
                 AND m_approvalsettings_line_t.approve_required = 'Yes'",
                [$module_name, $amount]
            );

            if (count($approval_settings) > 0) {
                $approvers = [];
                foreach ($approval_settings as $setting) {
                    if ($setting->approver_id) {
                        $approvers[] = (int)$setting->approver_id;
                    }
                }
                // Remove duplicates
                $approvers = array_unique($approvers);
                return json_encode(array_values($approvers));
            }

            return '0';
        } catch (\Exception $e) {
            Log::warning('[PurchaseOrderAPI] Error fetching approvers: ' . $e->getMessage());
            return '0';
        }
    }

    /**
     * Helper: Map module code to module_name in m_approvalsettings_hdr_t
     */
    private function getModuleApprovalName($module)
    {
        $module_map = [
            'poorder' => 'Purchaseorder Approval',
            'poquote' => 'Purchase Quotation Approval',
            'poinvoice' => 'Purchase Invoice Approval',
            'soorder' => 'Sales Order Approval',
            'soquote' => 'Sales Quote Approval',
            'soinvoice' => 'Sales Invoice Approval',
        ];

        return $module_map[$module] ?? 'Purchaseorder Approval';
    }

    /**
     * Get pending purchase orders for approval
     * GET /api/purchase/orders/pending
     */
    public function getPendingOrders(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                Log::warning('[PurchaseOrderAPI] getPendingOrders - unauthenticated request');
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated',
                ], 401);
            }

            $page = (int) $request->input('page', 1);
            $perPage = (int) $request->input('per_page', 20);
            $offset = max(0, ($page - 1) * $perPage);

            Log::info('[PurchaseOrderAPI] getPendingOrders - user_id: ' . $user->id . ', page: ' . $page . ', per_page: ' . $perPage);

            $query = "SELECT 
                p_po_hdr_t.po_hdr_id,
                p_po_hdr_t.po_number,
                p_po_hdr_t.po_date,
                p_po_hdr_t.po_type,
                p_po_hdr_t.po_for_verdura,
                p_po_hdr_t.po_status,
                p_po_hdr_t.po_grand_total,
                p_po_hdr_t.delivery_date,
                m_supplier_t.supplier_name,
                COALESCE(m_supplier_t.supplier_number, '') as supplier_number,
                COALESCE(hr_employee_t.first_name, '') as created_by_name
            FROM p_po_hdr_t
            LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = p_po_hdr_t.supplier_id
            LEFT JOIN hr_employee_t ON hr_employee_t.employee_id = p_po_hdr_t.created_by
            WHERE p_po_hdr_t.company_id = ? 
            AND p_po_hdr_t.po_status = 'INITIATED'
            ORDER BY p_po_hdr_t.po_hdr_id DESC
            LIMIT ? OFFSET ?";

            $orders = DB::select($query, [$user->company_id, $perPage, $offset]);

            $countQuery = "SELECT COUNT(*) as total FROM p_po_hdr_t 
                          WHERE company_id = ? AND po_status = 'INITIATED'";
            $totalCountRow = DB::selectOne($countQuery, [$user->company_id]);
            $totalCount = $totalCountRow ? (int) $totalCountRow->total : 0;

            $totalPages = $perPage > 0 ? (int) ceil($totalCount / $perPage) : 0;
            $hasMore = $page < $totalPages;

            return response()->json([
                'status' => 'success',
                'message' => 'Pending purchase orders retrieved successfully',
                'data' => $orders,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $totalCount,
                    'last_page' => $totalPages,
                    'has_more' => $hasMore,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('[PurchaseOrderAPI] Error fetching pending orders: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve pending orders',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get freight terms (FOB Points)
     * GET /api/purchase/freight-terms
     */
    public function getFreightTerms()
    {
        Log::info('[PurchaseOrder] getFreightTerms - Request received');

        try {
            $freightTerms = DB::table('m_frieghtterms_t')
                ->select('frieghtterm_id', 'fob_point_name', 'freight_terms_type')
                ->where('active', 'Yes')
                ->orderBy('fob_point_name', 'asc')
                ->get();

            Log::info('[PurchaseOrder] getFreightTerms - Found ' . count($freightTerms) . ' freight terms');

            return response()->json([
                'status' => 'success',
                'data' => $freightTerms
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseOrder] getFreightTerms - Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch freight terms',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update purchase order
     * PUT /api/purchase/orders/{id}
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();
            $payload = $request->all();

            $order = DB::table('p_po_hdr_t')
                ->where('po_hdr_id', $id)
                ->where('company_id', $user->company_id)
                ->first();

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Purchase order not found',
                ], 404);
            }

            if (!in_array($order->po_status, ['DRAFT', 'INITIATED'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only DRAFT or INITIATED orders can be edited',
                ], 422);
            }

            // Validate required fields
            $validator = Validator::make($payload, [
                'supplier_id' => 'required|integer',
                'suppliersite_id' => 'required|integer',
                'po_date' => 'required|date',
                'lines' => 'required|array|min:1',
                'lines.*.qoh_qty' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Calculate totals
            $productSubTotal = 0;
            $productTaxTotal = 0;

            foreach ($payload['lines'] as $line) {
                // Calculate line_sub_total if not provided
                if (isset($line['line_sub_total'])) {
                    $lineSubTotal = (float) $line['line_sub_total'];
                } else {
                    $qty = (float) ($line['qty'] ?? 0);
                    $unitPrice = (float) ($line['unit_price'] ?? 0);
                    $discountAmount = (float) ($line['discount_amount'] ?? 0);
                    $lineSubTotal = ($qty * $unitPrice) - $discountAmount;
                }

                $productSubTotal += $lineSubTotal;
                $productTaxTotal += (float) ($line['tax_amount'] ?? 0);
            }

            // Process charges
            $packingCharge = (float) ($payload['packing_charges'] ?? 0);
            $packingTax = (float) ($payload['packing_tax_amount'] ?? 0);
            $packingTaxGroupId = (int) ($payload['packing_tax_group_id'] ?? 0);
            $packingChargeWithTax = $packingCharge + $packingTax;
            $packingChargesTaxString = ($packingTaxGroupId && $packingTax) ? "{$packingTaxGroupId},{$packingTax}" : '';

            $transportCharge = (float) ($payload['transport_charges'] ?? 0);
            $transportTax = (float) ($payload['transport_tax_amount'] ?? 0);
            $transportTaxGroupId = (int) ($payload['transport_tax_group_id'] ?? 0);
            $transportChargeWithTax = $transportCharge + $transportTax;
            $transportChargesTaxString = ($transportTaxGroupId && $transportTax) ? "{$transportTaxGroupId},{$transportTax}" : '';

            $insuranceCharge = (float) ($payload['insurance_charges'] ?? 0);
            $insuranceTax = (float) ($payload['insurance_charge_tax_amount'] ?? 0);
            $insuranceTaxGroupId = (int) ($payload['insurance_charge_tax_group_id'] ?? 0);
            $insuranceChargeWithTax = $insuranceCharge + $insuranceTax;
            $insuranceChargesTaxString = ($insuranceTaxGroupId && $insuranceTax) ? "{$insuranceTaxGroupId},{$insuranceTax}" : '';

            $unloadingCharge = (float) ($payload['unloading_charges'] ?? 0);
            $unloadingTax = (float) ($payload['unloading_tax_amount'] ?? 0);
            $unloadingTaxGroupId = (int) ($payload['unloading_tax_group_id'] ?? 0);
            $unloadingChargeWithTax = $unloadingCharge + $unloadingTax;
            $unloadingChargesTaxString = ($unloadingTaxGroupId && $unloadingTax) ? "{$unloadingTaxGroupId},{$unloadingTax}" : '';

            $otherFreightCharge = (float) ($payload['other_frieght_amount'] ?? 0);
            $otherFreightTax = (float) ($payload['other_freight_tax_amount'] ?? 0);
            $otherFreightTaxGroupId = (int) ($payload['other_freight_tax_group_id'] ?? 0);
            $otherFreightChargeWithTax = $otherFreightCharge + $otherFreightTax;
            $otherFreightChargesTaxString = ($otherFreightTaxGroupId && $otherFreightTax) ? "{$otherFreightTaxGroupId},{$otherFreightTax}" : '';

            $otherTaxOnly = (float) ($payload['other_tax_amount'] ?? 0);
            $otherTaxGroupId = (int) ($payload['other_tax_group_id'] ?? 0);
            $otherTaxAmountTaxString = ($otherTaxGroupId && $otherTaxOnly) ? "{$otherTaxGroupId},{$otherTaxOnly}" : '';

            // Calculate final totals
            $subTotal = $productSubTotal;
            $totalTaxAmount = $productTaxTotal;
            $chargesWithTaxTotal = $packingChargeWithTax + $transportChargeWithTax + $insuranceChargeWithTax + $unloadingChargeWithTax + $otherFreightChargeWithTax;
            $grandTotal = $subTotal + $chargesWithTaxTotal + $totalTaxAmount + $otherTaxOnly;

            // Update header
            DB::table('p_po_hdr_t')
                ->where('po_hdr_id', $id)
                ->update([
                    'po_date' => $payload['po_date'],
                    'delivery_date' => $payload['delivery_date'] ?? null,
                    'supplier_id' => $payload['supplier_id'],
                    'suppliersite_id' => $payload['suppliersite_id'],
                    'po_type' => $payload['po_type'] ?? 'STANDARD',
                    'po_for_verdura' => $payload['po_for_verdura'] ?? 'No',
                    'payment_term_id' => $payload['payment_term_id'] ?? null,
                    'default_payment_method_id' => $payload['payment_method_id'] ?? null,
                    'delivery_terms_id' => $payload['delivery_terms_id'] ?? null,
                    'freight_terms_id' => $payload['freight_terms_id'] ?? null,
                    'freight_carrier_id' => $payload['freight_carrier_id'] ?? null,
                    'project_id' => $payload['project_id'] ?? null,
                    'organization_id' => $payload['organization_id'] ?? null,
                    'bill_to_location_id' => $payload['bill_to_location_id'] ?? null,
                    'ship_to_location_id' => $payload['ship_to_location_id'] ?? null,
                    'reference_number' => $payload['reference_number'] ?? null,
                    'supplier_reference_no' => $payload['supplier_reference_no'] ?? null,
                    'remarks' => $payload['remarks'] ?? null,
                    'po_sub_total' => $subTotal,
                    'po_tax_total' => $totalTaxAmount,
                    'po_grand_total' => $grandTotal,
                    'transport_charges' => $transportCharge,
                    'transport_charges_tax' => $transportChargesTaxString,
                    'unloading_charges' => $unloadingCharge,
                    'unloading_charges_tax' => $unloadingChargesTaxString,
                    'insurance_charges' => $insuranceCharge,
                    'insurance_charges_tax' => $insuranceChargesTaxString,
                    'packing_charges' => $packingCharge,
                    'packing_charges_tax' => $packingChargesTaxString,
                    'other_frieght_amount' => $otherFreightCharge,
                    'other_frieght_amount_tax' => $otherFreightChargesTaxString,
                    'other_tax_amount' => $otherTaxOnly,
                    'other_tax_amount_tax' => $otherTaxAmountTaxString,
                    'last_updated_by' => $user->id,
                    'updated_at' => now(),
                ]);

            // Delete existing lines and insert new ones
            DB::table('p_po_lines_t')->where('po_hdr_id', $id)->delete();

            foreach ($payload['lines'] as $index => $line) {
                // Calculate line_sub_total if not provided
                if (isset($line['line_sub_total'])) {
                    $lineSubTotal = (float) $line['line_sub_total'];
                } else {
                    $qty = (float) ($line['qty'] ?? 0);
                    $unitPrice = (float) ($line['unit_price'] ?? 0);
                    $discountAmount = (float) ($line['discount_amount'] ?? 0);
                    $lineSubTotal = ($qty * $unitPrice) - $discountAmount;
                }

                $lineTax = (float) ($line['tax_amount'] ?? 0);
                $lineTotal = isset($line['line_total']) ? (float) $line['line_total'] : ($lineSubTotal + $lineTax);

                $insertData = [
                    'po_hdr_id' => $id,
                    'line_no' => $index + 1,
                    'product_id' => $line['product_id'],
                    'qty' => $line['qty'],
                    'uom_code_id' => $line['uom_code_id'] ?? null,
                    'unit_price' => $line['unit_price'],
                    'discount_percentage' => $line['discount_percentage'] ?? 0,
                    'discount_amount' => $line['discount_amount'] ?? 0,
                    'line_sub_total' => $lineSubTotal,
                    'tax_group_id' => $line['tax_group_id'] ?? null,
                    'tax_amount' => $lineTax,
                    'line_total' => $lineTotal,
                    'hsn_code' => $line['gst_code_hdr_id'] ?? $line['hsn_code'] ?? null,
                    'remarks' => $line['remarks'] ?? null,
                    'promised_date' => $line['promised_date'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if (Schema::hasColumn('p_po_lines_t', 'qoh_qty')) {
                    $insertData['qoh_qty'] = $line['qoh_qty'] ?? null;
                }
                DB::table('p_po_lines_t')->insert($insertData);
            }

            DB::commit();

            Log::info('[PurchaseOrderAPI] Purchase order updated', ['po_hdr_id' => $id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase order updated successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[PurchaseOrderAPI] Error updating purchase order: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update purchase order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete purchase order
     * DELETE /api/purchase/orders/{id}
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();

            $order = DB::table('p_po_hdr_t')
                ->where('po_hdr_id', $id)
                ->where('company_id', $user->company_id)
                ->first();

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Purchase order not found',
                ], 404);
            }

            if (!in_array($order->po_status, ['DRAFT', 'INITIATED'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only DRAFT or INITIATED orders can be deleted',
                ], 422);
            }

            // Delete lines first
            DB::table('p_po_lines_t')->where('po_hdr_id', $id)->delete();

            // Delete header
            DB::table('p_po_hdr_t')->where('po_hdr_id', $id)->delete();

            DB::commit();

            Log::info('[PurchaseOrderAPI] Purchase order deleted', ['po_hdr_id' => $id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase order deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[PurchaseOrderAPI] Error deleting purchase order: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete purchase order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper: Get location address
     */
    /**
     * Get location address details (formatted with city, state, country names)
     * GET /api/purchase/location/{locationId}/address
     * Note: Renamed from getLocationAddress to avoid conflict with parent Controller method
     */
    public function getLocationAddressById($locationId)
    {
        Log::info('[PurchaseQuotation] getLocationAddressById - Request for location_id: ' . $locationId);

        try {
            $location = DB::table('m_location_t')
                ->select(
                    'm_location_t.location_id',
                    'm_location_t.location_name',
                    'm_location_t.address',
                    'm_location_t.street_name',
                    'm_location_t.area',
                    'm_location_t.pincode',
                    'm_location_t.city_id',
                    'm_location_t.state_id',
                    'm_location_t.country_id',
                    'm_countries_t.country_name',
                    'm_states_t.state_name',
                    'm_states_t.state_code',
                    'm_cities_t.city_name'
                )
                ->leftJoin('m_countries_t', 'm_countries_t.country_id', '=', 'm_location_t.country_id')
                ->leftJoin('m_states_t', 'm_states_t.state_id', '=', 'm_location_t.state_id')
                ->leftJoin('m_cities_t', 'm_cities_t.city_id', '=', 'm_location_t.city_id')
                ->where('m_location_t.location_id', $locationId)
                ->first();

            if (!$location) {
                Log::warning('[PurchaseQuotation] getLocationAddressById - Location not found: ' . $locationId);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Location not found'
                ], 404);
            }

            Log::info('[PurchaseQuotation] getLocationAddressById - Success for location_id: ' . $locationId);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'location_id' => $location->location_id,
                    'location_name' => $location->location_name,
                    'address' => $location->address,
                    'street_name' => $location->street_name,
                    'area' => $location->area,
                    'pincode' => $location->pincode,
                    'city' => [
                        'city_id' => $location->city_id,
                        'city_name' => $location->city_name,
                    ],
                    'state' => [
                        'state_id' => $location->state_id,
                        'state_name' => $location->state_name,
                        'state_code' => $location->state_code,
                    ],
                    'country' => [
                        'country_id' => $location->country_id,
                        'country_name' => $location->country_name,
                    ],
                    'formatted_address' => $this->getFormattedLocationAddressString($location->location_id),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getLocationAddressById - Error: ' . $e->getMessage(), [
                'location_id' => $locationId
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch location address',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get formatted location address string
     */
    private function PurchaseOrder_getLocationAddress($locationId)
    {
        $location = DB::table('m_location_t')
            ->select(
                'm_location_t.address',
                'm_location_t.street_name',
                'm_location_t.area',
                'm_location_t.pincode',
                'm_countries_t.country_name',
                'm_states_t.state_name',
                'm_cities_t.city_name'
            )
            ->leftJoin('m_countries_t', 'm_countries_t.country_id', '=', 'm_location_t.country_id')
            ->leftJoin('m_states_t', 'm_states_t.state_id', '=', 'm_location_t.state_id')
            ->leftJoin('m_cities_t', 'm_cities_t.city_id', '=', 'm_location_t.city_id')
            ->where('m_location_t.location_id', $locationId)
            ->first();

        if (!$location) {
            return null;
        }

        $parts = array_filter([
            $location->address != 'null' ? $location->address : null,
            $location->street_name,
            $location->area,
            $location->city_name,
            $location->state_name,
            $location->country_name,
            $location->pincode
        ]);

        return implode(' - ', $parts);
    }

    /**
     * Get PAGINATED supplier products for purchase order (with server-side search)
     * GET /api/purchase/order/supplier/{supplierId}/products
     * Query params: page, per_page, search, supplier_site_id, pricelist_id, quotation_type
     */
    public function getOrderSupplierProductsPaginated(Request $request, $supplierId)
    {
        $supplierSiteId  = $request->input('supplier_site_id');
        $quotationType   = $request->input('quotation_type', 'STANDARD');
        $search          = $request->input('search', '');
        $pricelistId     = $request->input('pricelist_id');
        $page            = max(1, (int) $request->input('page', 1));
        $perPage         = min(100, max(1, (int) $request->input('per_page', 50)));
        $offset          = ($page - 1) * $perPage;

        Log::info('[PurchaseOrder] getOrderSupplierProductsPaginated', [
            'supplier_id'      => $supplierId,
            'page'             => $page,
            'per_page'         => $perPage,
            'search'           => $search,
            'supplier_site_id' => $supplierSiteId,
        ]);

        try {
            // Resolve pricelist if not provided
            if (!$pricelistId) {
                $supplier = DB::table('m_supplier_t')
                    ->select('default_pricelist_id')
                    ->where('supplier_id', $supplierId)
                    ->first();
                if ($supplier) {
                    $pricelistId = $supplier->default_pricelist_id;
                }
            }

            // Base query - Use subquery to avoid duplicate products when multiple prices exist in pricelist
            $priceSubquery = DB::table('i_pricelist_lines_t')
                ->select('product_id', DB::raw('MAX(unit_price) as unit_price'))
                ->where('pricelist_hdr_id', $pricelistId ?? 0)
                ->groupBy('product_id');

            $baseQuery = DB::table('m_products_t as mp')
                ->leftJoinSub($priceSubquery, 'ipl', function ($join) {
                    $join->on('mp.product_id', '=', 'ipl.product_id');
                })
                ->select(
                    'mp.product_id',
                    'mp.product_code',
                    'mp.concatenated_product',
                    'mp.primary_uom_id',
                    'mp.defalut_hsn_code',
                    'mp.hsn_code',
                    'mp.min_order_qty',
                    'mp.max_order_qty',
                    'ipl.unit_price'
                )
                ->where('mp.active', 'Yes');

            if ($search) {
                $baseQuery->where(function ($q) use ($search) {
                    $q->where('mp.concatenated_product', 'like', '%' . $search . '%')
                        ->orWhere('mp.product_code', 'like', '%' . $search . '%');
                });
            }

            $total = (clone $baseQuery)->count();

            $products = (clone $baseQuery)
                ->orderBy('mp.concatenated_product', 'asc')
                ->offset($offset)
                ->limit($perPage)
                ->get();

            $enrichedProducts = [];
            foreach ($products as $product) {
                $partNo = DB::table('m_manufacturer_partno_t')
                    ->select('manufacturer_partno_id')
                    ->where('product_id', $product->product_id)
                    ->where('manufacturer_source_value_id', $supplierId)
                    ->where('manufacturer_source', 'SUPPLIER')
                    ->first();

                $taxData = [];
                if ($supplierSiteId && $product->defalut_hsn_code) {
                    $taxData = $this->getOrderTaxGroupForHsn($product->defalut_hsn_code, $supplierSiteId);
                }

                $enrichedProducts[] = [
                    'product_id'           => $product->product_id,
                    'product_code'         => $product->product_code,
                    'concatenated_product' => $product->concatenated_product,
                    'uom_code_id'          => $product->primary_uom_id,
                    'hsn_code'             => $product->defalut_hsn_code,
                    'unit_price'           => $product->unit_price ?? 0,
                    'manufacturer_part_no' => $partNo->manufacturer_partno_id ?? null,
                    'tax_group_id'         => $taxData['tax_group_id'] ?? null,
                    'tax_group_name'       => $taxData['tax_group_name'] ?? '',
                    'tax_percentage'       => $taxData['display_name'] ?? '',
                    'min_order_qty'        => $product->min_order_qty,
                    'max_order_qty'        => $product->max_order_qty,
                ];
            }

            $lastPage = $total > 0 ? (int) ceil($total / $perPage) : 1;

            Log::info('[PurchaseOrder] getOrderSupplierProductsPaginated - returning ' . count($enrichedProducts) . " of {$total}");

            return response()->json([
                'status' => 'success',
                'data'   => $enrichedProducts,
                'pagination' => [
                    'current_page' => $page,
                    'per_page'     => $perPage,
                    'total'        => $total,
                    'last_page'    => $lastPage,
                    'has_more'     => $page < $lastPage,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseOrder] getOrderSupplierProductsPaginated error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to fetch products: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Resolve tax group for a given HSN code and supplier site (intrastate vs interstate).
     * Mirrors the logic in PurchaseQuotationController::getTaxGroupForHsn.
     */
    private function getOrderTaxGroupForHsn($hsnCodeId, $supplierSiteId)
    {
        $result = [
            'tax_group_id'        => 0,
            'tax_group_name'      => '',
            'display_name'        => '',
            'tax_group_id_expiry' => null,
        ];

        $date = date('Y-m-d');

        $currentLocation = Auth::user()->location_id ?? 1;
        $locationQuery   = DB::table('m_location_t')
            ->select('state_id')
            ->where('location_id', $currentLocation)
            ->first();
        $curState = $locationQuery->state_id ?? '';

        $supplierSite = DB::table('m_supplier_sites_t')
            ->select('state')
            ->where('supplier_site_id', $supplierSiteId)
            ->first();

        if (!$supplierSite) {
            return $result;
        }

        $supplierState = $supplierSite->state ?? '';

        if ($curState !== '' && $supplierState !== '') {
            $locationType = ($curState == $supplierState) ? 'Intrastate(within-state)' : 'Interstate';

            $tax = DB::table('f_gst_code_lines_t')
                ->select('tax_group_id')
                ->where('gst_code_hdr_id', $hsnCodeId)
                ->where('active', 'Yes')
                ->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->where('tax_location_type', $locationType)
                ->first();

            if ($tax && $tax->tax_group_id) {
                $taxGroup = DB::table('m_tax_group_t')
                    ->select('tax_group_id', 'tax_group_name', 'display_name')
                    ->where('tax_group_id', $tax->tax_group_id)
                    ->first();

                if ($taxGroup) {
                    $result['tax_group_id']   = $taxGroup->tax_group_id;
                    $result['tax_group_name'] = $taxGroup->tax_group_name ?? '';
                    $result['display_name']   = $taxGroup->display_name ?? '';
                }
            }
        }

        return $result;
    }
}
