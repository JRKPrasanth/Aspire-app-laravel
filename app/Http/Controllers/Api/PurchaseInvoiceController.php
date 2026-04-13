<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PurchaseInvoiceController extends Controller
{
    /**
     * Get all purchase invoices with pagination
     * GET /api/purchase/invoices/all
     */
    public function getAllPurchaseInvoices(Request $request)
    {
        try {
            $user = Auth::user();
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 20);
            $offset = ($page - 1) * $perPage;

            // Base query for purchase invoices
            $query = "SELECT 
                p_po_invoice_hdr_t.po_invoice_id,
                p_po_invoice_hdr_t.invoice_number,
                p_po_invoice_hdr_t.invoice_date,
                p_po_invoice_hdr_t.due_date,
                p_po_invoice_hdr_t.supplier_id,
                m_supplier_t.supplier_name,
                p_po_invoice_hdr_t.po_invoice_status,
                p_po_invoice_hdr_t.invoice_type,
                p_po_invoice_hdr_t.invoice_grand_total,
                p_po_invoice_hdr_t.balance_amount,
                p_po_invoice_hdr_t.created_by,
                p_po_invoice_hdr_t.created_at,
                tb_users.username as created_by_name
            FROM p_po_invoice_hdr_t
            LEFT JOIN m_supplier_t ON m_supplier_t.supplier_id = p_po_invoice_hdr_t.supplier_id
            LEFT JOIN tb_users ON tb_users.id = p_po_invoice_hdr_t.created_by
            WHERE p_po_invoice_hdr_t.company_id = ?
            ORDER BY p_po_invoice_hdr_t.po_invoice_id DESC
            LIMIT ? OFFSET ?";

            $invoices = DB::select($query, [$user->company_id, $perPage, $offset]);

            // Get total count
            $countQuery = "SELECT COUNT(*) as total FROM p_po_invoice_hdr_t WHERE company_id = ?";
            $totalCount = DB::selectOne($countQuery, [$user->company_id])->total;

            $totalPages = ceil($totalCount / $perPage);
            $hasMore = $page < $totalPages;

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase invoices retrieved successfully',
                'data' => $invoices,
                'pagination' => [
                    'current_page' => (int) $page,
                    'per_page' => (int) $perPage,
                    'total' => (int) $totalCount,
                    'last_page' => (int) $totalPages,
                    'has_more' => $hasMore,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('[PurchaseInvoiceAPI] Error fetching invoices: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve purchase invoices',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get purchase invoice by ID with full details
     * GET /api/purchase/invoices/{id}
     */
    public function getPurchaseInvoiceById($id)
    {
        try {
            $user = Auth::user();

            // Get header details
            $invoice = DB::table('p_po_invoice_hdr_t')
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_po_invoice_hdr_t.supplier_id')
                ->leftJoin('m_supplier_sites_t', 'm_supplier_sites_t.supplier_site_id', '=', 'p_po_invoice_hdr_t.suppliersite_id')
                ->leftJoin('m_payment_terms_t', 'm_payment_terms_t.payment_term_id', '=', 'p_po_invoice_hdr_t.payment_term_id')
                ->leftJoin('m_payment_methods_t', 'm_payment_methods_t.payment_method_id', '=', 'p_po_invoice_hdr_t.payment_method_id')
                ->leftJoin('m_delivery_terms_t', 'm_delivery_terms_t.delivery_terms_id', '=', 'p_po_invoice_hdr_t.delivery_terms_id')
                ->leftJoin('m_projects_t', 'm_projects_t.project_id', '=', 'p_po_invoice_hdr_t.project_id')
                ->leftJoin('tb_users', 'tb_users.id', '=', 'p_po_invoice_hdr_t.created_by')
                ->select(
                    'p_po_invoice_hdr_t.*',
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
                    'tb_users.username as created_by_name'
                )
                ->where('p_po_invoice_hdr_t.po_invoice_id', $id)
                ->where('p_po_invoice_hdr_t.company_id', $user->company_id)
                ->first();

            if (!$invoice) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Purchase invoice not found',
                ], 404);
            }

            // Get line items
            $lines = DB::table('p_po_invoice_lines_t')
                ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 'p_po_invoice_lines_t.product_id')
                ->leftJoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'p_po_invoice_lines_t.uom_code_id')
                ->leftJoin('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'p_po_invoice_lines_t.tax_group_id')
                ->leftJoin('f_gst_code_hdr_t', 'f_gst_code_hdr_t.gst_code_hdr_id', '=', 'p_po_invoice_lines_t.hsn_code')
                ->select(
                    'p_po_invoice_lines_t.*',
                    'm_products_t.product_name',
                    'm_products_t.product_code',
                    'm_products_t.concatenated_product',
                    'm_uom_codes_t.uom_code',
                    'm_tax_group_t.tax_group_name',
                    'f_gst_code_hdr_t.classification_code as hsn_code_name'
                )
                ->where('p_po_invoice_lines_t.po_invoice_id', $id)
                ->orderBy('p_po_invoice_lines_t.line_no')
                ->get();

            // Get bill to and ship to addresses
            $billToAddress = $this->getLocationAddressById($invoice->bill_to_location_id ?? null);
            $shipToAddress = $this->getLocationAddressById($invoice->ship_to_location_id ?? null);

            // Calculate summary
            $summary = [
                'subtotal' => (float) ($invoice->invoice_sub_total ?? 0),
                'tax_total' => (float) ($invoice->invoice_tax_total ?? 0),
                'total_items' => count($lines),
                'total_quantity' => $lines->sum('inv_qty'),
                'balance_amount' => (float) ($invoice->balance_amount ?? 0),
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase invoice retrieved successfully',
                'data' => [
                    'invoice' => $invoice,
                    'lines' => $lines,
                    'bill_to_address' => $billToAddress,
                    'ship_to_address' => $shipToAddress,
                    'summary' => $summary,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('[PurchaseInvoiceAPI] Error fetching invoice details: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve purchase invoice details',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create new purchase invoice
     * POST /api/purchase/invoices
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();
            $payload = $request->all();

            Log::info('[PurchaseInvoiceAPI] Creating new purchase invoice', ['user_id' => $user->id]);

            // Validate required fields
            $validator = Validator::make($payload, [
                'supplier_id' => 'required|integer',
                'suppliersite_id' => 'required|integer',
                'invoice_date' => 'required|date',
                'supplier_invoice_no' => 'required|string',
                'lines' => 'required|array|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Generate invoice number
            $invoiceNumber = $this->generateInvoiceNumber($user->company_id);

            // Calculate totals from line items
            $invoiceSubTotal = 0;
            $invoiceTaxTotal = 0;

            foreach ($payload['lines'] as $line) {
                $lineSubTotal = (float) ($line['line_sub_total'] ?? 0);
                $lineTax = (float) ($line['tax_amount'] ?? 0);

                $invoiceSubTotal += $lineSubTotal;
                $invoiceTaxTotal += $lineTax;
            }

            // Process additional charges (same format as quotations)
            $packingCharge = (float) ($payload['packing_charges'] ?? 0);
            $packingTax = (float) ($payload['packing_tax_amount'] ?? 0);
            $packingTaxGroupId = (int) ($payload['packing_tax_group_id'] ?? 0);
            $packingChargeWithTax = $packingCharge + $packingTax;
            $packingChargesTaxString = ($packingTaxGroupId && $packingCharge) ? "{$packingTaxGroupId},{$packingCharge}" : null;

            $transportCharge = (float) ($payload['transport_charges'] ?? 0);
            $transportTax = (float) ($payload['transport_tax_amount'] ?? 0);
            $transportTaxGroupId = (int) ($payload['transport_tax_group_id'] ?? 0);
            $transportChargeWithTax = $transportCharge + $transportTax;
            $transportChargesTaxString = ($transportTaxGroupId && $transportCharge) ? "{$transportTaxGroupId},{$transportCharge}" : null;

            $insuranceCharge = (float) ($payload['insurance_charges'] ?? 0);
            $insuranceTax = (float) ($payload['insurance_charge_tax_amount'] ?? 0);
            $insuranceTaxGroupId = (int) ($payload['insurance_charge_tax_group_id'] ?? 0);
            $insuranceChargeWithTax = $insuranceCharge + $insuranceTax;
            $insuranceChargesTaxString = ($insuranceTaxGroupId && $insuranceCharge) ? "{$insuranceTaxGroupId},{$insuranceCharge}" : null;

            $unloadingCharge = (float) ($payload['unloading_charges'] ?? 0);
            $unloadingTax = (float) ($payload['unloading_tax_amount'] ?? 0);
            $unloadingTaxGroupId = (int) ($payload['unloading_tax_group_id'] ?? 0);
            $unloadingChargeWithTax = $unloadingCharge + $unloadingTax;
            $unloadingChargesTaxString = ($unloadingTaxGroupId && $unloadingCharge) ? "{$unloadingTaxGroupId},{$unloadingCharge}" : null;

            $otherFreightCharge = (float) ($payload['other_freight_amount'] ?? 0);
            $otherFreightTax = (float) ($payload['other_freight_tax_amount'] ?? 0);
            $otherFreightTaxGroupId = (int) ($payload['other_freight_tax_group_id'] ?? 0);
            $otherFreightChargeWithTax = $otherFreightCharge + $otherFreightTax;
            $otherFreightChargesTaxString = ($otherFreightTaxGroupId && $otherFreightCharge) ? "{$otherFreightTaxGroupId},{$otherFreightCharge}" : null;

            $otherTaxOnly = (float) ($payload['other_tax_amount'] ?? 0);
            $otherTaxGroupId = (int) ($payload['other_tax_group_id'] ?? 0);
            $otherTaxAmountTaxString = ($otherTaxGroupId && $otherTaxOnly) ? "{$otherTaxGroupId},{$otherTaxOnly}" : null;

            // Calculate final totals
            $chargesWithTaxTotal = $packingChargeWithTax + $transportChargeWithTax + $insuranceChargeWithTax + $unloadingChargeWithTax + $otherFreightChargeWithTax;
            $grandTotal = $invoiceSubTotal + $chargesWithTaxTotal + $invoiceTaxTotal + $otherTaxOnly;

            // Insert header
            $poInvoiceId = DB::table('p_po_invoice_hdr_t')->insertGetId([
                'invoice_number' => $invoiceNumber,
                'invoice_date' => $payload['invoice_date'],
                'due_date' => $payload['due_date'] ?? null,
                'supplier_id' => $payload['supplier_id'],
                'suppliersite_id' => $payload['suppliersite_id'],
                'supplier_invoice_no' => $payload['supplier_invoice_no'],
                'supplier_invoice_date' => $payload['supplier_invoice_date'] ?? null,
                'dc_number' => $payload['dc_number'] ?? null,
                'dc_date' => $payload['dc_date'] ?? null,
                'invoice_type' => $payload['invoice_type'] ?? 'STANDARD',
                'po_invoice_status' => 'INITIATED',
                'payment_term_id' => $payload['payment_term_id'] ?? null,
                'payment_method_id' => $payload['payment_method_id'] ?? null,
                'delivery_terms_id' => $payload['delivery_terms_id'] ?? null,
                'freight_terms_id' => $payload['freight_terms_id'] ?? null,
                'freight_carrier_id' => $payload['freight_carrier_id'] ?? null,
                'project_id' => $payload['project_id'] ?? null,
                'bill_to_location_id' => $payload['bill_to_location_id'] ?? null,
                'ship_to_location_id' => $payload['ship_to_location_id'] ?? null,
                'remarks' => $payload['remarks'] ?? null,
                'invoice_sub_total' => $invoiceSubTotal,
                'invoice_tax_total' => $invoiceTaxTotal,
                'invoice_grand_total' => $grandTotal,
                'balance_amount' => $grandTotal,
                'transport_charges' => $transportChargeWithTax,
                'transport_charges_tax' => $transportChargesTaxString,
                'unloading_charges' => $unloadingChargeWithTax,
                'unloading_charges_tax' => $unloadingChargesTaxString,
                'insurance_charges' => $insuranceChargeWithTax,
                'insurance_charges_tax' => $insuranceChargesTaxString,
                'packing_charges' => $packingChargeWithTax,
                'packing_charges_tax' => $packingChargesTaxString,
                'other_freight_amount' => $otherFreightChargeWithTax,
                'other_frieght_amount_tax' => $otherFreightChargesTaxString,
                'other_tax_amount' => $otherTaxOnly,
                'other_tax_amount_tax' => $otherTaxAmountTaxString,
                'tds_applicable' => $payload['tds_applicable'] ?? 'No',
                'reverse_charge' => $payload['reverse_charge'] ?? 'No',
                'need_to_close' => $payload['need_to_close'] ?? 'No',
                'company_id' => $user->company_id,
                'location_id' => $user->location_id ?? 1,
                'created_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert line items
            foreach ($payload['lines'] as $index => $line) {
                DB::table('p_po_invoice_lines_t')->insert([
                    'po_invoice_id' => $poInvoiceId,
                    'line_no' => $index + 1,
                    'product_id' => $line['product_id'],
                    'inv_qty' => $line['inv_qty'],
                    'uom_code_id' => $line['uom_code_id'] ?? null,
                    'unit_price' => $line['unit_price'],
                    'discount_percentage' => $line['discount_percentage'] ?? 0,
                    'discount_amount' => $line['discount_amount'] ?? 0,
                    'line_sub_total' => $line['line_sub_total'],
                    'tax_group_id' => $line['tax_group_id'] ?? null,
                    'tax_amount' => $line['tax_amount'] ?? 0,
                    'line_total' => $line['line_total'],
                    'hsn_code' => $line['hsn_code'] ?? null,
                    'remarks' => $line['remarks'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            Log::info('[PurchaseInvoiceAPI] Purchase invoice created successfully', ['po_invoice_id' => $poInvoiceId, 'invoice_number' => $invoiceNumber]);

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase invoice created successfully',
                'data' => [
                    'po_invoice_id' => $poInvoiceId,
                    'invoice_number' => $invoiceNumber,
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[PurchaseInvoiceAPI] Error creating purchase invoice: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create purchase invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Approve or reject purchase invoice
     * POST /api/purchase/invoices/{id}/approve
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

            $invoice = DB::table('p_po_invoice_hdr_t')
                ->where('po_invoice_id', $id)
                ->where('company_id', $user->company_id)
                ->first();

            if (!$invoice) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Purchase invoice not found',
                ], 404);
            }

            if ($invoice->po_invoice_status !== 'INITIATED') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only INITIATED invoices can be approved/rejected',
                ], 422);
            }

            DB::table('p_po_invoice_hdr_t')
                ->where('po_invoice_id', $id)
                ->update([
                    'po_invoice_status' => $decision,
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                    'approval_comments' => $comments,
                    'updated_at' => now(),
                ]);

            DB::commit();

            Log::info('[PurchaseInvoiceAPI] Purchase invoice ' . $decision, ['po_invoice_id' => $id, 'user_id' => $user->id]);

            return response()->json([
                'status' => 'success',
                'message' => "Purchase invoice {$decision} successfully",
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[PurchaseInvoiceAPI] Error approving invoice: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process approval',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper: Generate invoice number
     */
    private function generateInvoiceNumber($companyId)
    {
        $prefix = 'INV-';
        $year = date('y');
        $month = date('m');

        $lastInvoice = DB::table('p_po_invoice_hdr_t')
            ->where('company_id', $companyId)
            ->where('invoice_number', 'like', $prefix . $year . $month . '%')
            ->orderBy('po_invoice_id', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $year . $month . $newNumber;
    }

    /**
     * Helper: Get location address by ID (returns formatted string)
     */
    public function getLocationAddressById($locationId)
    {
        if (!$locationId) return null;

        $location = DB::table('m_location_t')->where('location_id', $locationId)->first();

        if (!$location) return null;

        return trim(implode(', ', array_filter([
            $location->address ?? '',
            $location->street_name ?? '',
            $location->city ?? '',
            $location->state ?? '',
            $location->pincode ?? '',
        ])));
    }
}
