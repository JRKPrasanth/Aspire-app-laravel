<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class PurchaseQuotationController extends Controller
{
    /**
     * ============================================================
     * MASTER DATA ENDPOINTS (For Dropdowns in Mobile App)
     * ============================================================
     */

    /**
     * Get all suppliers for dropdown
     * GET /api/purchase/suppliers
     */
    public function getSuppliers(Request $request)
    {
        Log::info('[PurchaseQuotation] getSuppliers - Request received');

        try {
            $suppliers = DB::table('m_supplier_t')
                ->select(
                    'supplier_id',
                    'supplier_number',
                    'supplier_name'
                )
                ->where('active', 'Yes')
                ->orderBy('supplier_id', 'asc')
                ->get();

            Log::info('[PurchaseQuotation] getSuppliers - Found ' . count($suppliers) . ' suppliers');

            return response()->json([
                'status' => 'success',
                'data' => $suppliers
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getSuppliers - Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch suppliers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get supplier sites by supplier_id
     * GET /api/purchase/supplier/{supplierId}/sites
     */
    public function getSupplierSites($supplierId)
    {
        Log::info('[PurchaseQuotation] getSupplierSites - Request for supplier_id: ' . $supplierId);

        try {
            $sites = DB::table('m_supplier_sites_t')
                ->select(
                    'supplier_site_id',
                    'supplier_site_number',
                    'supplier_site_name',
                    'primary_address',
                    'address',
                    'city',
                    'state',
                    'country',
                    'pincode'
                )
                ->where('supplier_id', $supplierId)
                ->orderBy('supplier_site_name', 'asc')
                ->get();

            Log::info('[PurchaseQuotation] getSupplierSites - Found ' . count($sites) . ' sites for supplier ' . $supplierId);

            return response()->json([
                'status' => 'success',
                'data' => $sites
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getSupplierSites - Error: ' . $e->getMessage(), [
                'supplier_id' => $supplierId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch supplier sites',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get supplier details with defaults (when supplier is selected)
     * GET /api/purchase/supplier/{supplierId}/details
     * Returns: pricelist, payment terms, delivery terms, insurance, freight carrier, primary site
     */
    public function getSupplierDetails($supplierId)
    {
        Log::info('[PurchaseQuotation] getSupplierDetails - Request for supplier_id: ' . $supplierId);

        try {
            $supplier = DB::table('m_supplier_t')
                ->where('supplier_id', $supplierId)
                ->first();

            if (!$supplier) {
                Log::warning('[PurchaseQuotation] getSupplierDetails - Supplier not found: ' . $supplierId);
                return response()->json(['status' => 'error', 'message' => 'Supplier not found'], 404);
            }

            // Get primary supplier site
            $primarySite = DB::table('m_supplier_sites_t')
                ->where('supplier_id', $supplierId)
                ->where('primary_address', 'Yes')
                ->first();

            // Get all sites for this supplier
            $allSites = DB::table('m_supplier_sites_t')
                ->select('supplier_site_id', 'supplier_site_number', 'supplier_site_name', 'primary_address')
                ->where('supplier_id', $supplierId)
                ->orderBy('supplier_site_name', 'asc')
                ->get();

            Log::info('[PurchaseQuotation] getSupplierDetails - Success', [
                'supplier_id' => $supplierId,
                'sites_count' => count($allSites),
                'has_primary_site' => $primarySite !== null
            ]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'supplier_id' => $supplier->supplier_id,
                    'supplier_name' => $supplier->supplier_name,
                    'supplier_type_id' => $supplier->supplier_type_id,
                    'default_pricelist_id' => $supplier->default_pricelist_id,
                    'default_payment_terms_id' => $supplier->default_payment_terms_id,
                    'default_payment_method_id' => $supplier->default_payment_method_id,
                    'delivery_terms_id' => $supplier->delivery_terms_id,
                    'insurance_term_id' => $supplier->insurance_term_id,
                    'frieghtterm_id' => $supplier->frieghtterm_id,
                    'frieghtcarriers_id' => $supplier->frieghtcarriers_id,
                    'primary_supplier_site_id' => $primarySite->supplier_site_id ?? null,
                    'supplier_sites' => $allSites
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getSupplierDetails - Error: ' . $e->getMessage(), [
                'supplier_id' => $supplierId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch supplier details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get freight carriers (Purchase type)
     * GET /api/purchase/freight-carriers
     */
    public function getFreightCarriers()
    {
        Log::info('[PurchaseQuotation] getFreightCarriers - Request received');

        try {
            $carriers = DB::table('m_frieghtcarriers_hdr_t')
                ->select('ar_frieghtcarriers_hdr_id as freight_carrier_id', 'carrier_name')
                ->where('source_type_id', 'Purchase')
                ->orderBy('carrier_name', 'asc')
                ->get();

            Log::info('[PurchaseQuotation] getFreightCarriers - Found ' . count($carriers) . ' carriers');

            return response()->json([
                'status' => 'success',
                'data' => $carriers
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getFreightCarriers - Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch freight carriers',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get freight terms (FOB Points)
     * GET /api/purchase/freight-terms
     */
    public function getFreightTerms()
    {
        Log::info('[PurchaseQuotation] getFreightTerms - Request received');

        try {
            $freightTerms = DB::table('m_frieghtterms_t')
                ->select('frieghtterm_id', 'fob_point_name', 'freight_terms_type')
                ->where('active', 'Yes')
                ->orderBy('fob_point_name', 'asc')
                ->get();

            Log::info('[PurchaseQuotation] getFreightTerms - Found ' . count($freightTerms) . ' freight terms');

            return response()->json([
                'status' => 'success',
                'data' => $freightTerms
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getFreightTerms - Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch freight terms',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment terms
     * GET /api/purchase/payment-terms
     */
    public function getPaymentTerms()
    {
        Log::info('[PurchaseQuotation] getPaymentTerms - Request received');

        try {
            $terms = DB::table('m_payment_terms_t')
                ->select('payment_term_id', 'payment_term_name')
                ->orderBy('payment_term_name', 'asc')
                ->get();

            Log::info('[PurchaseQuotation] getPaymentTerms - Found ' . count($terms) . ' terms');

            return response()->json([
                'status' => 'success',
                'data' => $terms
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getPaymentTerms - Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch payment terms',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment methods
     * GET /api/purchase/payment-methods
     */
    public function getPaymentMethods()
    {
        Log::info('[PurchaseQuotation] getPaymentMethods - Request received');

        try {
            $methods = DB::table('m_payment_methods_t')
                ->select('payment_method_id', 'payment_method_name')
                ->orderBy('payment_method_name', 'asc')
                ->get();

            Log::info('[PurchaseQuotation] getPaymentMethods - Found ' . count($methods) . ' methods');

            return response()->json([
                'status' => 'success',
                'data' => $methods
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getPaymentMethods - Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch payment methods',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get insurance terms
     * GET /api/purchase/insurance-terms
     */
    public function getInsuranceTerms()
    {
        Log::info('[PurchaseQuotation] getInsuranceTerms - Request received');

        try {
            $terms = DB::table('m_insurance_terms_t')
                ->select('insurance_term_id', 'insurance_term_name')
                ->orderBy('insurance_term_name', 'asc')
                ->get();

            Log::info('[PurchaseQuotation] getInsuranceTerms - Found ' . count($terms) . ' terms');

            return response()->json([
                'status' => 'success',
                'data' => $terms
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getInsuranceTerms - Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch insurance terms',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get delivery terms (Purchase type)
     * GET /api/purchase/delivery-terms
     */
    public function getDeliveryTerms()
    {
        Log::info('[PurchaseQuotation] getDeliveryTerms - Request received');

        try {
            $terms = DB::table('m_delivery_terms_t')
                ->select('delivery_terms_id', 'delivery_term_name')
                ->where('source_type_id', 'Purchase')
                ->orderBy('delivery_term_name', 'asc')
                ->get();

            Log::info('[PurchaseQuotation] getDeliveryTerms - Found ' . count($terms) . ' terms');

            return response()->json([
                'status' => 'success',
                'data' => $terms
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getDeliveryTerms - Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch delivery terms',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * ============================================================
     * PRODUCT ENDPOINTS
     * ============================================================
     */

    /**
     * Get products for purchase quotation
     * GET /api/purchase/products
     * Optional query params: supplier_id, pricelist_id, search
     */
    public function getProducts(Request $request)
    {
        $supplierId = $request->input('supplier_id');
        $pricelistId = $request->input('pricelist_id');
        $search = $request->input('search');

        Log::info('[PurchaseQuotation] getProducts - Request received', [
            'supplier_id' => $supplierId,
            'pricelist_id' => $pricelistId,
            'search' => $search
        ]);

        try {
            $query = DB::table('m_products_t')
                ->select(
                    'm_products_t.product_id',
                    'm_products_t.product_code',
                    'm_products_t.concatenated_product',
                    'm_products_t.primary_uom_id',
                    'm_products_t.hsn_code',
                    'm_products_t.defalut_hsn_code',
                    'm_uom_codes_t.uom_code'
                )
                ->leftJoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'm_products_t.primary_uom_id')
                ->where('m_products_t.active', 'Yes');

            // If pricelist_id provided, filter by products in that pricelist
            if ($pricelistId) {
                $query->whereIn('m_products_t.product_id', function ($q) use ($pricelistId) {
                    $q->select('product_id')
                        ->from('i_pricelist_lines_t')
                        ->where('pricelist_hdr_id', $pricelistId);
                });
            }

            // Search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('m_products_t.concatenated_product', 'like', "%{$search}%")
                        ->orWhere('m_products_t.product_code', 'like', "%{$search}%");
                });
            }

            $products = $query->orderBy('m_products_t.concatenated_product', 'asc')
                ->get();

            Log::info('[PurchaseQuotation] getProducts - Found ' . count($products) . ' products');

            return response()->json([
                'status' => 'success',
                'data' => $products
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getProducts - Error: ' . $e->getMessage(), [
                'supplier_id' => $supplierId,
                'pricelist_id' => $pricelistId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product details (when product is selected in a line)
     * GET /api/purchase/product/{productId}/details
     * Query params: supplier_id, supplier_site_id, quotation_type (STANDARD/LABOUR)
     */
    public function getProductDetails(Request $request, $productId)
    {
        $supplierId = $request->input('supplier_id');
        $supplierSiteId = $request->input('supplier_site_id');
        $quotationType = $request->input('quotation_type', 'STANDARD');
        $pricelistId = $request->input('pricelist_id', 0);

        Log::info('[PurchaseQuotation] getProductDetails - Request received', [
            'product_id' => $productId,
            'supplier_id' => $supplierId,
            'supplier_site_id' => $supplierSiteId,
            'quotation_type' => $quotationType
        ]);

        try {
            $product = DB::table('m_products_t')
                ->select(
                    'product_id',
                    'product_code',
                    'product_group_id',
                    'product_category_id',
                    'product_subcategory_id',
                    'concatenated_product',
                    'product_type_id',
                    'defalut_hsn_code',
                    'primary_uom_id',
                    'min_order_qty',
                    'mpq_qty',
                    'max_order_qty',
                    'hsn_code',
                    'created_at',
                    'updated_at',
                    'created_by',
                    'product_variant_id',
                    'variant_group_id'
                )
                ->where('product_id', $productId)
                ->first();

            if (!$product) {
                Log::warning('[PurchaseQuotation] getProductDetails - Product not found: ' . $productId);
                return response()->json(['status' => 'error', 'message' => 'Product not found'], 404);
            }
            $defalut_hsn_code = $product->defalut_hsn_code;
            $result = [
                'product_id' => $product->product_id,
                'product_code' => $product->product_code,
                'concatenated_product' => $product->concatenated_product,
                'uom_code_id' => $product->primary_uom_id,
                'defalut_hsn_code' => $product->defalut_hsn_code,
                'hsn_code' => $product->hsn_code,
                'min_order_qty' => $product->min_order_qty,
                'max_order_qty' => $product->max_order_qty,
                'unit_price' => 0,
                'part_no' => null,
                'tax_group_id' => null,
                'qoh_qty' => 0
            ];

            // Get manufacturer part number for supplier
            if ($supplierId) {
                $partNo = DB::table('m_manufacturer_partno_t')
                    ->where('product_id', $productId)
                    ->where('manufacturer_source_value_id', $supplierId)
                    ->where('manufacturer_source', 'SUPPLIER')
                    ->first();

                if ($partNo) {
                    $result['part_no'] = $partNo->manufacturer_partno;
                }
            }

            $pricelistLine = DB::table('i_pricelist_lines_t')
                ->where('product_id', $productId)
                ->first();

            if ($pricelistLine) {
                $result['unit_price'] = $pricelistLine->unit_price;
            }

            // Get QOH (Quantity on Hand)
            $companyId = Auth::user()->company_id ?? null;
            if ($companyId) {
                $qohQty = DB::select("
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
                ", [$productId, $companyId, $productId, $companyId]);

                if (!empty($qohQty) && $qohQty[0]->qoh_qty) {
                    $result['qoh_qty'] = $qohQty[0]->qoh_qty;
                }
            }

            // Get tax group based on HSN and supplier site
            if ($defalut_hsn_code && $supplierSiteId) {
                $taxData = $this->getTaxGroupForHsn($defalut_hsn_code, $supplierSiteId);
                $result['tax_group_id'] = $taxData['tax_group_id'];
                $result['tax_group_name'] = $taxData['tax_group_name'] ?? '';
                $result['tax_percentage'] = $taxData['display_name'] ?? '';
            }

            // Get HSN codes for this product
            if ($product->hsn_code) {
                $classification = ($quotationType === 'STANDARD') ? 'HSN' : 'SAC';
                $hsnCodes = DB::table('f_gst_code_hdr_t')
                    ->select('gst_code_hdr_id', 'classification_code', 'classification_name')
                    ->whereIn('gst_code_hdr_id', explode(',', $product->hsn_code))
                    ->where('classification_name', $classification)
                    ->get();
                $result['hsn_codes'] = $hsnCodes;
            }

            Log::info('[PurchaseQuotation] getProductDetails - Success', [
                'product_id' => $productId,
                'unit_price' => $result['unit_price'],
                'tax_group_id' => $result['tax_group_id'] ?? null
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getProductDetails - Error: ' . $e->getMessage(), [
                'product_id' => $productId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch product details',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Get tax details for HSN code
     * GET /api/purchase/tax-details
     * Query params: hsn_code_id, supplier_site_id
     */
    public function getTaxDetails(Request $request)
    {
        $hsnCodeId = $request->input('hsn_code_id');
        $supplierSiteId = $request->input('supplier_site_id');

        Log::info('[PurchaseQuotation] getTaxDetails - Request received', [
            'hsn_code_id' => $hsnCodeId,
            'supplier_site_id' => $supplierSiteId
        ]);

        if (!$hsnCodeId || !$supplierSiteId) {
            Log::warning('[PurchaseQuotation] getTaxDetails - Missing required parameters');
            return response()->json([
                'status' => 'error',
                'message' => 'hsn_code_id and supplier_site_id are required'
            ], 422);
        }

        try {
            $taxData = $this->getTaxGroupForHsn($hsnCodeId, $supplierSiteId);

            Log::info('[PurchaseQuotation] getTaxDetails - Success', [
                'hsn_code_id' => $hsnCodeId,
                'tax_group_id' => $taxData['tax_group_id'] ?? null
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $taxData
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getTaxDetails - Error: ' . $e->getMessage(), [
                'hsn_code_id' => $hsnCodeId,
                'supplier_site_id' => $supplierSiteId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch tax details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get HSN/SAC codes
     * GET /api/purchase/hsn-codes
     * Query params: classification (HSN/SAC), product_id (optional - to filter by product's HSN codes)
     */
    public function getHsnCodes(Request $request)
    {
        $classification = $request->input('classification', 'HSN');
        $productId = $request->input('product_id');

        Log::info('[PurchaseQuotation] getHsnCodes - Request received', [
            'classification' => $classification,
            'product_id' => $productId
        ]);

        try {
            $query = DB::table('f_gst_code_hdr_t')
                ->select('gst_code_hdr_id', 'classification_code', 'classification_name', 'gst_description')
                ->where('classification_name', $classification);

            // Filter by product's HSN codes if product_id provided
            if ($productId) {
                $product = DB::table('m_products_t')
                    ->select('hsn_code')
                    ->where('product_id', $productId)
                    ->first();

                if ($product && $product->hsn_code) {
                    $query->whereIn('gst_code_hdr_id', explode(',', $product->hsn_code));
                }
            }

            $hsnCodes = $query->orderBy('classification_code', 'asc')->get();

            Log::info('[PurchaseQuotation] getHsnCodes - Found ' . count($hsnCodes) . ' HSN codes');

            return response()->json([
                'status' => 'success',
                'data' => $hsnCodes
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getHsnCodes - Error: ' . $e->getMessage(), [
                'classification' => $classification,
                'product_id' => $productId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch HSN codes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tax groups
     * GET /api/purchase/tax-groups
     */
    public function getTaxGroups()
    {
        Log::info('[PurchaseQuotation] getTaxGroups - Request received');

        try {
            $taxGroups = DB::table('m_tax_group_t')
                ->select('tax_group_id', 'tax_group_name', 'display_name')
                ->where('active', 'Yes')
                ->orderBy('tax_group_name', 'asc')
                ->get();

            Log::info('[PurchaseQuotation] getTaxGroups - Found ' . count($taxGroups) . ' tax groups');

            return response()->json([
                'status' => 'success',
                'data' => $taxGroups
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getTaxGroups - Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch tax groups',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Projects list     
     * GET /api/purchase/projects
     */
    public function getProjects()
    {
        Log::info('[PurchaseQuotation] getProjects - Request received');

        try {
            $projects = DB::table('m_projects_t')
                ->select('project_id', 'project_name', 'project_type_id', 'customer_id', 'organization_id')
                ->where('active', 'YES')
                ->orderBy('project_name', 'asc')
                ->get();

            Log::info('[PurchaseQuotation] getProjects - Found ' . count($projects) . ' projects');

            return response()->json([
                'status' => 'success',
                'data' => $projects
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getProjects - Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch projects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get UOM codes
     * GET /api/purchase/uom-codes
     */
    public function getUomCodes()
    {
        Log::info('[PurchaseQuotation] getUomCodes - Request received');

        try {
            $uomCodes = DB::table('m_uom_codes_t')
                ->select('uom_code_id', 'uom_code', 'uom_name')
                ->orderBy('uom_code', 'asc')
                ->get();

            Log::info('[PurchaseQuotation] getUomCodes - Found ' . count($uomCodes) . ' UOM codes');

            return response()->json([
                'status' => 'success',
                'data' => $uomCodes
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getUomCodes - Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch UOM codes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ============================================================
     * PURCHASE QUOTATION CRUD ENDPOINTS
     * ============================================================
     */

    /**
     * Get list of purchase quotations
     * GET /api/purchase/quotes
     * Query params: status, page, per_page
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $perPage = $request->input('per_page', 20);

        Log::info('[PurchaseQuotation] index - Request received', [
            'status' => $status,
            'per_page' => $perPage
        ]);

        try {
            $query = DB::table('p_quotation_hdr_t')
                ->select(
                    'p_quotation_hdr_t.quotation_hdr_id',
                    'p_quotation_hdr_t.quotation_no',
                    'p_quotation_hdr_t.quotation_date',
                    'p_quotation_hdr_t.quotation_type',
                    'p_quotation_hdr_t.quote_status',
                    'p_quotation_hdr_t.supplier_ref_no',
                    'p_quotation_hdr_t.quote_grand_total',
                    'p_quotation_hdr_t.quote_tax_total',
                    'p_quotation_hdr_t.remarks',
                    'm_supplier_t.supplier_name',
                    'tb_users.username as created_by_name'
                )
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_quotation_hdr_t.supplier_id')
                ->leftJoin('tb_users', 'tb_users.id', '=', 'p_quotation_hdr_t.created_by');

            if ($status) {
                $query->where('p_quotation_hdr_t.quote_status', $status);
            }

            $quotations = $query->orderBy('p_quotation_hdr_t.quotation_date', 'desc')
                ->paginate($perPage);

            Log::info('[PurchaseQuotation] index - Found ' . $quotations->total() . ' quotations');

            return response()->json([
                'status' => 'success',
                'data' => $quotations
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] index - Error: ' . $e->getMessage(), [
                'status' => $status,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch quotations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending quotations for approval
     * GET /api/purchase/quotes/pending
     */
    public function getPendingQuotes(Request $request)
    {
        $userId = Auth::id();

        Log::info('[PurchaseQuotation] getPendingQuotes - Request received', [
            'user_id' => $userId
        ]);

        try {
            $quotations = DB::table('p_quotation_hdr_t')
                ->select(
                    'p_quotation_hdr_t.quotation_hdr_id',
                    'p_quotation_hdr_t.quotation_no',
                    'p_quotation_hdr_t.quotation_date',
                    'p_quotation_hdr_t.delivery_date',
                    'p_quotation_hdr_t.quotation_type',
                    'p_quotation_hdr_t.quote_status',
                    'p_quotation_hdr_t.quote_grand_total',
                    'p_quotation_hdr_t.quote_tax_total',
                    'p_quotation_hdr_t.remarks',
                    'm_supplier_t.supplier_name',
                    'e.first_name as created_by_name'
                )
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_quotation_hdr_t.supplier_id')
                ->leftJoin('hr_employee_t as e', 'e.employee_id', '=', 'p_quotation_hdr_t.created_by')
                ->where('p_quotation_hdr_t.quote_status', 'INITIATED')
                ->whereNotNull('p_quotation_hdr_t.approver_id')
                ->where(function ($query) use ($userId) {
                    // Check for both integer and string values in JSON array
                    // JSON_CONTAINS([151,152,537], '152') - matches integer
                    // JSON_CONTAINS(["151","152","537"], '"152"') - matches string
                    $query->whereRaw("JSON_CONTAINS(p_quotation_hdr_t.approver_id, ?)", [(string)$userId])
                        ->orWhereRaw("JSON_CONTAINS(p_quotation_hdr_t.approver_id, ?)", ['"' . $userId . '"']);
                })
                ->orderBy('p_quotation_hdr_t.quotation_date', 'desc')
                ->get();

            Log::info('[PurchaseQuotation] getPendingQuotes - Found ' . count($quotations) . ' pending quotations for user ' . $userId);

            return response()->json([
                'status' => 'success',
                'data' => $quotations
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getPendingQuotes - Error: ' . $e->getMessage(), [
                'user_id' => $userId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch pending quotations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new Purchase Quotation (header + lines)
     * POST /api/purchase/quote
     */
    public function store(Request $request)
    {
        $payload = $request->all();
        $userId = Auth::id();

        Log::info('[PurchaseQuotation] store - CREATE QUOTATION REQUEST', [
            'user_id' => $userId,
            'payload' => $payload
        ]);

        $validator = Validator::make($payload, [
            'supplier_id' => 'required|integer',
            'quotation_date' => 'required|date',
            'quotation_type' => 'required|string|in:STANDARD,LABOUR',
            'freight_carrier_id' => 'required|integer',
            'supplier_ref_no' => 'required|string',
            'default_payment_method_id' => 'required|integer',
            'insurance_term_id' => 'required|integer',
            'lines' => 'required|array|min:1',
            'lines.*.product_id' => 'required_if:quotation_type,STANDARD|integer',
            'lines.*.qty' => 'required|numeric|min:0.01',
            'lines.*.unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            Log::warning('[PurchaseQuotation] store - Validation failed', [
                'user_id' => $userId,
                'errors' => $validator->errors()->toArray()
            ]);
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            Log::info('[PurchaseQuotation] store - Starting transaction');

            // Calculate grand total for approval routing
            // Structure: SubTotal (products) + ProductTax + AdditionalCharges (with their taxes) + OtherTaxAdjustments
            $productSubTotal = 0;
            $productTaxTotal = 0;
            $chargesTotal = 0;
            $chargesTaxTotal = 0;

            // Calculate product subtotal and tax
            foreach ($payload['lines'] as $line) {
                $qty = (float) ($line['qty'] ?? 0);
                $price = (float) ($line['unit_price'] ?? 0);
                $discountAmount = (float) ($line['discount_amount'] ?? 0);
                $taxAmount = (float) ($line['tax_amount'] ?? 0);
                $lineSubtotal = round(($qty * $price) - $discountAmount, 2);
                $productSubTotal += $lineSubtotal;
                $productTaxTotal += $taxAmount;
            }

            // Calculate charges with their taxes + prepare formatted charge tax strings
            // Format: packing_charges = base + tax, packing_charges_tax = "tax_group_id,base_amount"
            $packingCharge = (float) ($payload['packing_charges'] ?? 0);
            $packingTax = (float) ($payload['packing_tax_amount'] ?? 0);
            $packingTaxGroupId = (int) ($payload['packing_tax_group_id'] ?? 0);
            $packingChargeWithTax = $packingCharge + $packingTax;  // Store total (base + tax)
            $packingChargesTaxString = ($packingTaxGroupId && $packingCharge) ? "{$packingTaxGroupId},{$packingCharge}" : null; // Store "tax_group_id,base_amount"
            $chargesTaxTotal += $packingTax;

            $transportCharge = (float) ($payload['transport_charges'] ?? 0);
            $transportTax = (float) ($payload['transport_tax_amount'] ?? 0);
            $transportTaxGroupId = (int) ($payload['transport_tax_group_id'] ?? 0);
            $transportChargeWithTax = $transportCharge + $transportTax;
            $transportChargesTaxString = ($transportTaxGroupId && $transportCharge) ? "{$transportTaxGroupId},{$transportCharge}" : null;
            $chargesTaxTotal += $transportTax;

            $insuranceCharge = (float) ($payload['insurance_charges'] ?? 0);
            $insuranceTax = (float) ($payload['insurance_charge_tax_amount'] ?? 0);
            $insuranceTaxGroupId = (int) ($payload['insurance_charge_tax_group_id'] ?? 0);
            $insuranceChargeWithTax = $insuranceCharge + $insuranceTax;
            $insuranceChargesTaxString = ($insuranceTaxGroupId && $insuranceCharge) ? "{$insuranceTaxGroupId},{$insuranceCharge}" : null;
            $chargesTaxTotal += $insuranceTax;

            $unloadingCharge = (float) ($payload['unloading_charges'] ?? 0);
            $unloadingTax = (float) ($payload['unloading_tax_amount'] ?? 0);
            $unloadingTaxGroupId = (int) ($payload['unloading_tax_group_id'] ?? 0);
            $unloadingChargeWithTax = $unloadingCharge + $unloadingTax;
            $unloadingChargesTaxString = ($unloadingTaxGroupId && $unloadingCharge) ? "{$unloadingTaxGroupId},{$unloadingCharge}" : null;
            $chargesTaxTotal += $unloadingTax;

            $otherFreightCharge = (float) ($payload['other_frieght_amount'] ?? 0);
            $otherFreightTax = (float) ($payload['other_freight_tax_amount'] ?? 0);
            $otherFreightTaxGroupId = (int) ($payload['other_freight_tax_group_id'] ?? 0);
            $otherFreightChargeWithTax = $otherFreightCharge + $otherFreightTax;
            $otherFreightChargesTaxString = ($otherFreightTaxGroupId && $otherFreightCharge) ? "{$otherFreightTaxGroupId},{$otherFreightCharge}" : null;
            $chargesTaxTotal += $otherFreightTax;

            // Other tax amount (tax-only adjustment, no base amount)
            $otherTaxOnly = (float) ($payload['other_tax_amount'] ?? 0);
            $otherTaxGroupId = (int) ($payload['other_tax_group_id'] ?? 0);
            $otherTaxAmountTaxString = ($otherTaxGroupId && $otherTaxOnly) ? "{$otherTaxGroupId},{$otherTaxOnly}" : null;

            // Final totals: 
            // - quote_tax_total = ONLY product taxes (NOT charge taxes)
            // - quote_grand_total = SubTotal + ChargesWithTax + ProductTax + OtherTax
            $subTotal = $productSubTotal;
            $totalTaxAmount = $productTaxTotal; // ONLY product tax, no charge taxes
            $chargesWithTaxTotal = $packingChargeWithTax + $transportChargeWithTax + $insuranceChargeWithTax + $unloadingChargeWithTax + $otherFreightChargeWithTax;
            $grandTotal = $subTotal + $chargesWithTaxTotal + $totalTaxAmount + $otherTaxOnly;

            Log::info('[PurchaseQuotation] store - Calculated totals with charges', [
                'product_subtotal' => $productSubTotal,
                'product_tax' => $productTaxTotal,
                'packing_charge_with_tax' => $packingChargeWithTax,
                'transport_charge_with_tax' => $transportChargeWithTax,
                'insurance_charge_with_tax' => $insuranceChargeWithTax,
                'unloading_charge_with_tax' => $unloadingChargeWithTax,
                'other_freight_charge_with_tax' => $otherFreightChargeWithTax,
                'charges_total_with_tax' => $chargesWithTaxTotal,
                'charges_tax_only' => $chargesTaxTotal,
                'other_tax' => $otherTaxOnly,
                'sub_total' => $subTotal,
                'total_tax_amount' => $totalTaxAmount,
                'grand_total' => $grandTotal
            ]);

            // Get approver based on value
            $approverId = $this->getApproverForValue('poquote', $grandTotal);
            Log::info('[PurchaseQuotation] store - Approver determined', [
                'approver_id' => $approverId,
                'grand_total' => $grandTotal
            ]);

            // Generate quotation number
            $quotationNoData = $this->generateQuotationNumber();
            $quotationNo = $quotationNoData['quotation_no'];
            $poquoteCount = $quotationNoData['poquote_count'];
            Log::info('[PurchaseQuotation] store - Generated quotation number: ' . $quotationNo . ', count: ' . $poquoteCount);

            // Determine status
            $status = $payload['quote_status'] ?? 'DRAFT';

            // Get user's location and company
            $locationId = Auth::user()->location_id ?? $payload['location_id'] ?? 1;
            $companyId = Auth::user()->company_id ?? $payload['company_id'] ?? 1;

            $supplierId = $payload['supplier_id'];
            $plist = DB::table('m_supplier_t')->where('supplier_id', $supplierId)->get();

            // Insert header - columns matching actual p_quotation_hdr_t schema
            $hdr = [
                'quotation_no' => $quotationNo,
                'poquote_count' => $poquoteCount,
                'supplier_id' => $payload['supplier_id'],
                'supplier_site_id' => $payload['supplier_site_id'] ?? null,
                'quotation_date' => $payload['quotation_date'],
                'supplier_quotation_date' => $payload['quotation_date'] ?? null,
                'quotation_type' => $payload['quotation_type'],
                'supplier_ref_no' => $payload['supplier_ref_no'] ?? null,
                'delivery_date' => $payload['delivery_date'] ?? null,
                'delivery_terms_id' => $payload['delivery_terms_id'] ?? null,
                'payment_term_id' => $payload['payment_term_id'] ?? null,
                'default_payment_method_id' => $payload['default_payment_method_id'] ?? null,
                'insurance_term_id' => $payload['insurance_term_id'] ?? null,
                'freight_carrier_id' => $payload['freight_carrier_id'],
                'quote_pricelist_id' => $plist[0]->default_pricelist_id ?? null,
                'bill_to_location_id' => $payload['bill_to_location_id'] ?? null,
                'ship_to_location_id' => $payload['ship_to_location_id'] ?? null,
                'location_id' => $locationId,
                'company_id' => $companyId,
                'organization_id' => $payload['organization_id'] ?? 1,
                'project_id' => $payload['project_id'] ?? null,
                'remarks' => $payload['remarks'] ?? null,
                'quote_status' => $status,
                'quote_grand_total' => $grandTotal,
                'quote_tax_total' => $totalTaxAmount,
                // Charges (storing total with tax + formatted tax string)
                'transport_charges' => $transportChargeWithTax,
                'transport_charges_tax' => $transportChargesTaxString,
                'unloading_charges' => $unloadingChargeWithTax,
                'unloading_charges_tax' => $unloadingChargesTaxString,
                'insurance_charges' => $insuranceChargeWithTax,
                'insurance_charges_tax' => $insuranceChargesTaxString,
                'packing_charges' => $packingChargeWithTax,
                'packing_charges_tax' => $packingChargesTaxString,
                'other_frieght_amount' => $otherFreightChargeWithTax,
                'other_frieght_amount_tax' => $otherFreightChargesTaxString,
                'other_tax_amount' => $otherTaxOnly,
                'other_tax_amount_tax' => $otherTaxAmountTaxString,
                // Source/Reference
                'source' => $payload['source'] ?? 'STANDARD',
                'reference_id' => $payload['reference_id'] ?? null,
                'reference_number' => $payload['reference_number'] ?? null,
                'approver_id' => $approverId !== '0' ? $approverId : null,
                'active' => 'Yes',
                'created_by' => Auth::id() ?? $payload['created_by'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $headerId = DB::table('p_quotation_hdr_t')->insertGetId($hdr);
            Log::info('[PurchaseQuotation] store - Header inserted', [
                'quotation_hdr_id' => $headerId,
                'quotation_no' => $quotationNo
            ]);

            // Insert lines
            foreach ($payload['lines'] as $index => $line) {
                $qty = (float) ($line['qty'] ?? 0);
                $price = (float) ($line['unit_price'] ?? 0);
                $discountPercentage = (float) ($line['discount_percentage'] ?? 0);
                $discountAmount = (float) ($line['discount_amount'] ?? 0);
                $taxAmount = (float) ($line['tax_amount'] ?? 0);

                // Calculate if discount_amount not provided
                if ($discountAmount == 0 && $discountPercentage > 0) {
                    $discountAmount = round(($qty * $price) * $discountPercentage / 100, 2);
                }

                $lineSubTotal = round(($qty * $price) - $discountAmount, 2);
                $lineTotal = round($lineSubTotal + $taxAmount, 2);

                // Line insert - columns matching actual p_quotation_lines_t schema
                $lineInsert = [
                    'quotation_hdr_id' => $headerId,
                    'line_no' => $index + 1,
                    'product_id' => $line['product_id'] ?? 0,
                    'manufacturer_partno_id' => $line['manufacturer_partno_id'] ?? 0,
                    'product_description' => $line['product_description'] ?? null,
                    'uom_code_id' => $line['uom_code_id'] ?? 0,
                    'qty' => $qty,
                    'unit_price' => $price,
                    'discount_percentage' => $discountPercentage,
                    'discount_amount' => $discountAmount,
                    'line_sub_total' => $lineSubTotal,
                    'hsn_code' => $line['hsn_code'] ?? null,
                    'tax_group_id' => $line['tax_group_id'] ?? null,
                    'tax_amount' => $taxAmount,
                    'line_total' => $lineTotal,
                    'promised_date' => $line['promised_date'] ?? null,
                    'comments' => $line['comments'] ?? null,
                    'location_id' => $locationId ?? 1,
                    'company_id' => $companyId ?? 1,
                    'organization_id' => $payload['organization_id'] ?? 1,
                    'created_by' => Auth::id() ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                DB::table('p_quotation_lines_t')->insert($lineInsert);
            }

            Log::info('[PurchaseQuotation] store - All lines inserted', [
                'quotation_hdr_id' => $headerId,
                'lines_count' => count($payload['lines'])
            ]);

            DB::commit();

            Log::info('[PurchaseQuotation] store - QUOTATION CREATED SUCCESSFULLY', [
                'quotation_hdr_id' => $headerId,
                'quotation_no' => $quotationNo,
                'status' => $status,
                'grand_total' => $grandTotal,
                'user_id' => $userId
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase quotation created successfully',
                'id' => $headerId,
                'quotation_id' => $headerId, // legacy/client compatibility
                'quotation_no' => $quotationNo,
                'quote_status' => $status
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[PurchaseQuotation] store - QUOTATION CREATE FAILED', [
                'user_id' => $userId,
                'supplier_id' => $payload['supplier_id'] ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Create failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show a purchase quotation (header + lines)
     * GET /api/purchase/quote/{id}
     */
    public function show($id)
    {
        Log::info('[PurchaseQuotation] show - Request for quotation_id: ' . $id);

        try {
            $hdr = DB::table('p_quotation_hdr_t')
                ->select(
                    'p_quotation_hdr_t.*',
                    'm_supplier_t.supplier_name',
                    'm_supplier_t.supplier_number',
                    'm_supplier_sites_t.supplier_site_name',
                    'm_frieghtcarriers_hdr_t.carrier_name',
                    'm_payment_terms_t.payment_term_name',
                    'm_payment_methods_t.payment_method_name',
                    'm_insurance_terms_t.insurance_term_name',
                    'm_delivery_terms_t.delivery_term_name',
                    'i_pricelist_hdr_t.pricelist_name',
                    'm_organizations_t.organization_name',
                    'm_projects_t.project_name',
                    'tb_users.username as created_by_name'
                )
                ->leftJoin('m_supplier_t', 'm_supplier_t.supplier_id', '=', 'p_quotation_hdr_t.supplier_id')
                ->leftJoin('m_supplier_sites_t', 'm_supplier_sites_t.supplier_site_id', '=', 'p_quotation_hdr_t.supplier_site_id')
                ->leftJoin('m_frieghtcarriers_hdr_t', 'm_frieghtcarriers_hdr_t.ar_frieghtcarriers_hdr_id', '=', 'p_quotation_hdr_t.freight_carrier_id')
                ->leftJoin('m_payment_terms_t', 'm_payment_terms_t.payment_term_id', '=', 'p_quotation_hdr_t.payment_term_id')
                ->leftJoin('m_payment_methods_t', 'm_payment_methods_t.payment_method_id', '=', 'p_quotation_hdr_t.default_payment_method_id')
                ->leftJoin('m_insurance_terms_t', 'm_insurance_terms_t.insurance_term_id', '=', 'p_quotation_hdr_t.insurance_term_id')
                ->leftJoin('m_delivery_terms_t', 'm_delivery_terms_t.delivery_terms_id', '=', 'p_quotation_hdr_t.delivery_terms_id')
                ->leftJoin('i_pricelist_hdr_t', 'i_pricelist_hdr_t.pricelist_hdr_id', '=', 'p_quotation_hdr_t.quote_pricelist_id')
                ->leftJoin('m_organizations_t', 'm_organizations_t.organization_id', '=', 'p_quotation_hdr_t.organization_id')
                ->leftJoin('m_projects_t', 'm_projects_t.project_id', '=', 'p_quotation_hdr_t.project_id')
                ->leftJoin('tb_users', 'tb_users.id', '=', 'p_quotation_hdr_t.created_by')
                ->where('p_quotation_hdr_t.quotation_hdr_id', $id)
                ->first();

            if (!$hdr) {
                Log::warning('[PurchaseQuotation] show - Quotation not found: ' . $id);
                return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
            }

            // Get lines with product details
            $lines = DB::table('p_quotation_lines_t')
                ->select(
                    'p_quotation_lines_t.*',
                    'm_products_t.product_code',
                    'm_products_t.concatenated_product as product_name',
                    'm_uom_codes_t.uom_code',
                    'm_tax_group_t.tax_group_name',
                    'm_tax_group_t.tax_group_percentage',
                    'f_gst_code_hdr_t.classification_code as hsn_code_name',
                    'm_manufacturer_partno_t.part_no as part_no_name'
                )
                ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 'p_quotation_lines_t.product_id')
                ->leftJoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'p_quotation_lines_t.uom_code_id')
                ->leftJoin('m_tax_group_t', 'm_tax_group_t.tax_group_id', '=', 'p_quotation_lines_t.tax_group_id')
                ->leftJoin('f_gst_code_hdr_t', 'f_gst_code_hdr_t.gst_code_hdr_id', '=', 'p_quotation_lines_t.hsn_code')
                ->leftJoin('m_manufacturer_partno_t', 'm_manufacturer_partno_t.manufacturer_partno_id', '=', 'p_quotation_lines_t.manufacturer_partno_id')
                ->where('p_quotation_lines_t.quotation_hdr_id', $id)
                ->orderBy('p_quotation_lines_t.line_no', 'asc')
                ->get();

            // Get bill to and ship to addresses
            $billToAddress = null;
            $shipToAddress = null;

            if ($hdr->bill_to_location_id) {
                $billToAddress = $this->getFormattedLocationAddress($hdr->bill_to_location_id);
            }
            if ($hdr->ship_to_location_id) {
                $shipToAddress = $this->getFormattedLocationAddress($hdr->ship_to_location_id);
            }

            Log::info('[PurchaseQuotation] show - Success', [
                'quotation_id' => $id,
                'quotation_no' => $hdr->quotation_no,
                'lines_count' => count($lines)
            ]);

            return response()->json([
                'status' => 'success',
                'header' => $hdr,
                'lines' => $lines,
                'bill_to_address' => $billToAddress,
                'ship_to_address' => $shipToAddress
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] show - Error: ' . $e->getMessage(), [
                'quotation_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch quotation details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a purchase quotation
     * POST /api/purchase/quote/{id}/approve
     * body: {decision: 'APPROVED'|'REJECTED', comments: ''}
     */
    public function approve(Request $request, $id)
    {
        $userId = Auth::id();
        $decision = $request->input('decision', 'APPROVED');

        Log::info('[PurchaseQuotation] approve - APPROVAL REQUEST', [
            'quotation_id' => $id,
            'user_id' => $userId,
            'decision' => $decision
        ]);

        try {
            $hdr = DB::table('p_quotation_hdr_t')->where('quotation_hdr_id', $id)->first();
            if (!$hdr) {
                Log::warning('[PurchaseQuotation] approve - Quotation not found: ' . $id);
                return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
            }

            if ($hdr->quote_status === 'APPROVED') {
                Log::warning('[PurchaseQuotation] approve - Quotation already approved', ['quotation_id' => $id]);
                return response()->json(['status' => 'error', 'message' => 'Quotation already approved'], 400);
            }

            if ($hdr->quote_status === 'REJECTED') {
                Log::warning('[PurchaseQuotation] approve - Quotation already rejected', ['quotation_id' => $id]);
                return response()->json(['status' => 'error', 'message' => 'Quotation already rejected'], 400);
            }

            if ($hdr->quote_status !== 'INITIATED') {
                Log::warning('[PurchaseQuotation] approve - Invalid status for approval', [
                    'quotation_id' => $id,
                    'current_status' => $hdr->quote_status
                ]);
                return response()->json(['status' => 'error', 'message' => 'Quotation must be in INITIATED status to approve'], 400);
            }

            // Check if current user is an approver
            $approverIds = json_decode($hdr->approver_id ?? '[]', true);

            if (!empty($approverIds) && !in_array($userId, $approverIds) && !in_array((string)$userId, $approverIds)) {
                Log::warning('[PurchaseQuotation] approve - Unauthorized approver', [
                    'quotation_id' => $id,
                    'user_id' => $userId,
                    'approver_ids' => $approverIds
                ]);
                return response()->json(['status' => 'error', 'message' => 'You are not authorized to approve this quotation'], 403);
            }

            if (!in_array($decision, ['APPROVED', 'REJECTED'])) {
                return response()->json(['status' => 'error', 'message' => 'Invalid decision. Must be APPROVED or REJECTED'], 422);
            }

            $comments = $request->input('comments', null);

            // Only update minimal fields present in the current p_quotation_hdr_t schema
            $update = [
                'quote_status' => $decision,
                'last_updated_by' => $userId,
                'updated_at' => now(),
            ];

            DB::table('p_quotation_hdr_t')->where('quotation_hdr_id', $id)->update($update);

            Log::info('[PurchaseQuotation] approve - QUOTATION ' . $decision, [
                'quotation_id' => $id,
                'quotation_no' => $hdr->quotation_no,
                'user_id' => $userId,
                'decision' => $decision
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Quotation ' . strtolower($decision) . ' successfully',
                'id' => $id,
                'new_status' => $decision
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] approve - Error: ' . $e->getMessage(), [
                'quotation_id' => $id,
                'user_id' => $userId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process approval',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a purchase quotation (header + lines) - For editing before approval
     * PUT /api/purchase/quote/{id}
     */
    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $payload = $request->all();

        Log::info('[PurchaseQuotation] update - UPDATE REQUEST', [
            'quotation_id' => $id,
            'user_id' => $userId,
            'payload_keys' => array_keys($payload)
        ]);

        try {
            $hdr = DB::table('p_quotation_hdr_t')->where('quotation_hdr_id', $id)->first();

            if (!$hdr) {
                return response()->json(['status' => 'error', 'message' => 'Quotation not found'], 404);
            }

            // Only allow updates for INITIATED or DRAFT status
            if (!in_array($hdr->quote_status, ['INITIATED', 'DRAFT'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot update quotation in ' . $hdr->quote_status . ' status'
                ], 400);
            }

            DB::beginTransaction();

            // Update header fields
            $headerUpdate = [
                'last_updated_by' => $userId,
                'updated_at' => now(),
            ];

            // Optional header fields that can be updated
            $allowedHeaderFields = [
                'supplier_site_id',
                'quotation_date',
                'quotation_type',
                'delivery_date',
                'freight_carrier_id',
                'payment_term_id',
                'default_payment_method_id',
                'insurance_term_id',
                'delivery_terms_id',
                'bill_to_location_id',
                'ship_to_location_id',
                'remarks',
                'supplier_ref_no',
                'project_id',
                'packing_charges',
                'packing_tax_group_id',
                'packing_tax_amount',
                'transport_charges',
                'transport_tax_group_id',
                'transport_tax_amount',
                'insurance_charges',
                'insurance_charge_tax_group_id',
                'insurance_charge_tax_amount',
                'unloading_charges',
                'unloading_tax_group_id',
                'unloading_tax_amount',
                'other_frieght_amount',
                'other_freight_tax_group_id',
                'other_freight_tax_amount',
                'other_tax_amount',
                'other_tax_group_id'
            ];

            foreach ($allowedHeaderFields as $field) {
                if (isset($payload[$field])) {
                    $headerUpdate[$field] = $payload[$field];
                }
            }

            // Recalculate totals if lines are provided
            if (isset($payload['lines']) && is_array($payload['lines'])) {
                $productSubTotal = 0;
                $productTaxTotal = 0;
                $chargesTotal = 0;
                $chargesTaxTotal = 0;

                // Delete existing lines
                DB::table('p_quotation_lines_t')->where('quotation_hdr_id', $id)->delete();

                // Calculate product subtotal and tax
                foreach ($payload['lines'] as $index => $line) {
                    $qty = (float) ($line['qty'] ?? 0);
                    $price = (float) ($line['unit_price'] ?? 0);
                    $discountPercentage = (float) ($line['discount_percentage'] ?? 0);
                    $discountAmount = (float) ($line['discount_amount'] ?? 0);
                    $taxAmount = (float) ($line['tax_amount'] ?? 0);

                    if ($discountAmount == 0 && $discountPercentage > 0) {
                        $discountAmount = round(($qty * $price) * $discountPercentage / 100, 2);
                    }

                    $lineSubTotal = round(($qty * $price) - $discountAmount, 2);
                    $lineTotal = round($lineSubTotal + $taxAmount, 2);

                    $productSubTotal += $lineSubTotal;
                    $productTaxTotal += $taxAmount;

                    DB::table('p_quotation_lines_t')->insert([
                        'quotation_hdr_id' => $id,
                        'line_no' => $index + 1,
                        'product_id' => $line['product_id'] ?? 0,
                        'manufacturer_partno_id' => $line['manufacturer_partno_id'] ?? 0,
                        'product_description' => $line['product_description'] ?? null,
                        'uom_code_id' => $line['uom_code_id'] ?? 0,
                        'qty' => $qty,
                        'unit_price' => $price,
                        'discount_percentage' => $discountPercentage,
                        'discount_amount' => $discountAmount,
                        'line_sub_total' => $lineSubTotal,
                        'hsn_code' => $line['hsn_code'] ?? null,
                        'tax_group_id' => $line['tax_group_id'] ?? null,
                        'tax_amount' => $taxAmount,
                        'line_total' => $lineTotal,
                        'promised_date' => $line['promised_date'] ?? null,
                        'comments' => $line['comments'] ?? null,
                        'location_id' => $hdr->location_id ?? 1,
                        'company_id' => $hdr->company_id ?? 1,
                        'organization_id' => $hdr->organization_id ?? 1,
                        'created_by' => $userId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Calculate charges with their taxes + prepare formatted tax strings
                // Format: packing_charges = base + tax, packing_charges_tax = "tax_group_id,base_amount"
                $packingCharge = (float) ($payload['packing_charges'] ?? $hdr->packing_charges ?? 0);
                $packingTax = (float) ($payload['packing_tax_amount'] ?? 0);
                $packingTaxGroupId = (int) ($payload['packing_tax_group_id'] ?? 0);
                $packingChargeWithTax = $packingCharge + $packingTax;
                $packingChargesTaxString = ($packingTaxGroupId && $packingCharge) ? "{$packingTaxGroupId},{$packingCharge}" : null;
                $chargesTaxTotal += $packingTax;

                $transportCharge = (float) ($payload['transport_charges'] ?? $hdr->transport_charges ?? 0);
                $transportTax = (float) ($payload['transport_tax_amount'] ?? 0);
                $transportTaxGroupId = (int) ($payload['transport_tax_group_id'] ?? 0);
                $transportChargeWithTax = $transportCharge + $transportTax;
                $transportChargesTaxString = ($transportTaxGroupId && $transportCharge) ? "{$transportTaxGroupId},{$transportCharge}" : null;
                $chargesTaxTotal += $transportTax;

                $insuranceCharge = (float) ($payload['insurance_charges'] ?? $hdr->insurance_charges ?? 0);
                $insuranceTax = (float) ($payload['insurance_charge_tax_amount'] ?? 0);
                $insuranceTaxGroupId = (int) ($payload['insurance_charge_tax_group_id'] ?? 0);
                $insuranceChargeWithTax = $insuranceCharge + $insuranceTax;
                $insuranceChargesTaxString = ($insuranceTaxGroupId && $insuranceCharge) ? "{$insuranceTaxGroupId},{$insuranceCharge}" : null;
                $chargesTaxTotal += $insuranceTax;

                $unloadingCharge = (float) ($payload['unloading_charges'] ?? $hdr->unloading_charges ?? 0);
                $unloadingTax = (float) ($payload['unloading_tax_amount'] ?? 0);
                $unloadingTaxGroupId = (int) ($payload['unloading_tax_group_id'] ?? 0);
                $unloadingChargeWithTax = $unloadingCharge + $unloadingTax;
                $unloadingChargesTaxString = ($unloadingTaxGroupId && $unloadingCharge) ? "{$unloadingTaxGroupId},{$unloadingCharge}" : null;
                $chargesTaxTotal += $unloadingTax;

                $otherFreightCharge = (float) ($payload['other_frieght_amount'] ?? $hdr->other_frieght_amount ?? 0);
                $otherFreightTax = (float) ($payload['other_freight_tax_amount'] ?? 0);
                $otherFreightTaxGroupId = (int) ($payload['other_freight_tax_group_id'] ?? 0);
                $otherFreightChargeWithTax = $otherFreightCharge + $otherFreightTax;
                $otherFreightChargesTaxString = ($otherFreightTaxGroupId && $otherFreightCharge) ? "{$otherFreightTaxGroupId},{$otherFreightCharge}" : null;
                $chargesTaxTotal += $otherFreightTax;

                // Other tax amount (tax-only adjustment, no base amount)
                $otherTaxOnly = (float) ($payload['other_tax_amount'] ?? $hdr->other_tax_amount ?? 0);
                $otherTaxGroupId = (int) ($payload['other_tax_group_id'] ?? 0);
                $otherTaxAmountTaxString = ($otherTaxGroupId && $otherTaxOnly) ? "{$otherTaxGroupId},{$otherTaxOnly}" : null;

                // Final totals: 
                // - quote_tax_total = ONLY product taxes (NOT charge taxes)
                // - quote_grand_total = SubTotal + ChargesWithTax + ProductTax + OtherTax
                $subTotal = $productSubTotal;
                $totalTaxAmount = $productTaxTotal; // ONLY product tax, no charge taxes
                $chargesWithTaxTotal = $packingChargeWithTax + $transportChargeWithTax + $insuranceChargeWithTax + $unloadingChargeWithTax + $otherFreightChargeWithTax;
                $grandTotal = $subTotal + $chargesWithTaxTotal + $totalTaxAmount + $otherTaxOnly;

                $headerUpdate['quote_tax_total'] = $totalTaxAmount;
                $headerUpdate['quote_grand_total'] = $grandTotal;
                // Store charges with formatted tax strings
                $headerUpdate['transport_charges'] = $transportChargeWithTax;
                $headerUpdate['transport_charges_tax'] = $transportChargesTaxString;
                $headerUpdate['unloading_charges'] = $unloadingChargeWithTax;
                $headerUpdate['unloading_charges_tax'] = $unloadingChargesTaxString;
                $headerUpdate['insurance_charges'] = $insuranceChargeWithTax;
                $headerUpdate['insurance_charges_tax'] = $insuranceChargesTaxString;
                $headerUpdate['packing_charges'] = $packingChargeWithTax;
                $headerUpdate['packing_charges_tax'] = $packingChargesTaxString;
                $headerUpdate['other_frieght_amount'] = $otherFreightChargeWithTax;
                $headerUpdate['other_frieght_amount_tax'] = $otherFreightChargesTaxString;
                $headerUpdate['other_tax_amount'] = $otherTaxOnly;
                $headerUpdate['other_tax_amount_tax'] = $otherTaxAmountTaxString;
            }

            DB::table('p_quotation_hdr_t')->where('quotation_hdr_id', $id)->update($headerUpdate);

            DB::commit();

            Log::info('[PurchaseQuotation] update - QUOTATION UPDATED', [
                'quotation_id' => $id,
                'user_id' => $userId
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Quotation updated successfully',
                'id' => $id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[PurchaseQuotation] update - Error: ' . $e->getMessage(), [
                'quotation_id' => $id,
                'user_id' => $userId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get quotation attachments
     * GET /api/purchase/quote/{id}/attachments
     * Returns files from both files_t table (API uploads) and attachfile_name JSON field (web uploads)
     */
    public function getQuotationAttachments($id)
    {
        Log::info('[PurchaseQuotation] getQuotationAttachments - Request for quotation_id: ' . $id);

        try {
            $hdr = DB::table('p_quotation_hdr_t')
                ->select('quotation_hdr_id', 'attachfile_name')
                ->where('quotation_hdr_id', $id)
                ->first();

            if (!$hdr) {
                return response()->json(['status' => 'error', 'message' => 'Quotation not found'], 404);
            }

            $attachments = [];

            // Get files from files_t table (new API uploads)
            $filesFromTable = DB::table('files_t')
                ->where('entity_type', 'purchase_quotation')
                ->where('entity_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($filesFromTable as $file) {
                $attachments[] = [
                    'id' => $file->id,
                    'name' => $file->file_name,
                    'url' => $file->file_url,
                    'path' => $file->file_path,
                    'size' => $file->file_size,
                    'mime_type' => $file->mime_type,
                    'source' => 'api',
                    'uploaded_at' => $file->created_at
                ];
            }

            // Get files from attachfile_name JSON field (web uploads) - matches web controller logic
            if (!empty($hdr->attachfile_name)) {
                $webFiles = json_decode($hdr->attachfile_name, true);
                if (is_array($webFiles)) {
                    foreach ($webFiles as $fileName) {
                        // Match web controller path: Uploads/poquoteattachment/PO{id}/
                        $filePath = 'Uploads/poquoteattachment/PO' . $id . '/' . $fileName;
                        $fullPath = public_path($filePath);

                        $attachments[] = [
                            'id' => null,
                            'name' => $fileName,
                            'url' => url($filePath),
                            'path' => '/' . $filePath,
                            'size' => file_exists($fullPath) ? filesize($fullPath) : 0,
                            'mime_type' => file_exists($fullPath) ? mime_content_type($fullPath) : null,
                            'source' => 'web',
                            'uploaded_at' => null
                        ];
                    }
                }
            }

            Log::info('[PurchaseQuotation] getQuotationAttachments - Found ' . count($attachments) . ' attachments');

            return response()->json([
                'status' => 'success',
                'data' => $attachments
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getQuotationAttachments - Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch attachments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a purchase quotation
     * DELETE /api/purchase/quote/{id}
     */
    public function destroy($id)
    {
        $userId = Auth::id();

        Log::info('[PurchaseQuotation] destroy - DELETE REQUEST', [
            'quotation_id' => $id,
            'user_id' => $userId
        ]);

        try {
            $hdr = DB::table('p_quotation_hdr_t')->where('quotation_hdr_id', $id)->first();
            if (!$hdr) {
                Log::warning('[PurchaseQuotation] destroy - Quotation not found: ' . $id);
                return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
            }

            // Check if used in PO
            $usedInPo = DB::table('p_po_hdr_t')
                ->where('reference_id', $id)
                ->where('source', 'PO')
                ->exists();

            if ($usedInPo) {
                Log::warning('[PurchaseQuotation] destroy - Cannot delete, used in PO', [
                    'quotation_id' => $id,
                    'quotation_no' => $hdr->quotation_no
                ]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete. Quotation is used in Purchase Order.'
                ], 400);
            }

            DB::beginTransaction();

            DB::table('p_quotation_lines_t')->where('quotation_hdr_id', $id)->delete();
            DB::table('p_quotation_hdr_t')->where('quotation_hdr_id', $id)->delete();

            DB::commit();

            Log::info('[PurchaseQuotation] destroy - QUOTATION DELETED', [
                'quotation_id' => $id,
                'quotation_no' => $hdr->quotation_no,
                'user_id' => $userId
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Quotation deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[PurchaseQuotation] destroy - DELETE FAILED', [
                'quotation_id' => $id,
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Delete failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ============================================================
     * HELPER METHODS
     * ============================================================
     */

    /**
     * Get all locations (for Bill To / Ship To dropdowns)
     * GET /api/purchase/locations
     */
    public function getLocations(Request $request)
    {
        $companyId = Auth::user()->company_id ?? $request->input('company_id');
        $locationIdValue = $request->input('location_id'); // From session or request

        Log::info('[PurchaseQuotation] getLocations - Request received', [
            'company_id' => $companyId
        ]);

        try {
            $query = DB::table('m_location_t')
                ->select(
                    'location_id',
                    'location_name',
                    'address',
                    'street_name',
                    'area',
                    'pincode',
                    'city_id',
                    'state_id',
                    'country_id'
                )
                ->orderBy('location_name', 'asc');

            if ($companyId) {
                $query->where('company_id', $companyId);
            }

            $locations = $query->get();

            Log::info('[PurchaseQuotation] getLocations - Found ' . count($locations) . ' locations');

            return response()->json([
                'status' => 'success',
                'data' => $locations
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getLocations - Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch locations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

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
     * Get tax group for HSN code and supplier site
     */
    private function getTaxGroupForHsn($hsnCodeId, $supplierSiteId)
    {
        // This replicates the taxdetails() logic from Controller.php
        $result = [
            'tax_group_id' => 0,
            'tax_group_name' => '',
            'display_name' => '',
            'tax_group_id_expiry' => null
        ];

        $date = date('Y-m-d');

        // Get current location state (default location_id = 1)
        $currentLocation = Auth::user()->location_id ?? 1;
        $locationQuery = DB::table('m_location_t')
            ->select('state_id')
            ->where('location_id', $currentLocation)
            ->first();

        $curState = $locationQuery->state_id ?? '';

        // Get supplier site state
        $supplierSite = DB::table('m_supplier_sites_t')
            ->select('state')
            ->where('supplier_site_id', $supplierSiteId)
            ->first();

        if (!$supplierSite) {
            return $result;
        }

        $supplierState = $supplierSite->state ?? '';

        if ($curState != '' && $supplierState != '') {
            if ($curState == $supplierState) {
                // Intrastate (within state)
                $tax = DB::table('f_gst_code_lines_t')
                    ->select('tax_group_id')
                    ->where('gst_code_hdr_id', $hsnCodeId)
                    ->where('active', 'Yes')
                    ->whereDate('start_date', '<=', $date)
                    ->whereDate('end_date', '>=', $date)
                    ->where('tax_location_type', 'Intrastate(within-state)')
                    ->first();
            } else {
                // Interstate
                $tax = DB::table('f_gst_code_lines_t')
                    ->select('tax_group_id')
                    ->where('gst_code_hdr_id', $hsnCodeId)
                    ->where('active', 'Yes')
                    ->whereDate('start_date', '<=', $date)
                    ->whereDate('end_date', '>=', $date)
                    ->where('tax_location_type', 'Interstate')
                    ->first();
            }

            if ($tax && $tax->tax_group_id) {
                $taxGroup = DB::table('m_tax_group_t')
                    ->select('tax_group_id', 'tax_group_name', 'display_name')
                    ->where('tax_group_id', $tax->tax_group_id)
                    ->first();

                if ($taxGroup) {
                    $result['tax_group_id'] = $taxGroup->tax_group_id;
                    $result['tax_group_name'] = $taxGroup->tax_group_name ?? '';
                    $result['display_name'] = $taxGroup->display_name ?? '';
                }
            }
        }

        return $result;
    }

    /**
     * Get formatted location address string
     */
    private function getFormattedLocationAddressString($locationId)
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
     * Get formatted location address
     */
    private function getFormattedLocationAddress($locationId)
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
     * Get approver based on value (poquote approval settings)
     */
    private function getApproverForValue($module, $total_amount)
    {
        try {
            Log::info('Fetching Approval Settings from m_approvalsettings_*_t');

            $module_name = $this->getModuleApprovalName($module);

            $approval_settings = DB::select(
                "SELECT m_approvalsettings_hdr_t.*, m_approvalsettings_line_t.* 
                FROM m_approvalsettings_hdr_t 
                LEFT JOIN m_approvalsettings_line_t 
                    ON m_approvalsettings_hdr_t.approvalsettings_hdr_id = m_approvalsettings_line_t.approvalsettings_hdr_id
                WHERE m_approvalsettings_hdr_t.module_name = ? 
                AND ? BETWEEN m_approvalsettings_line_t.value_from AND m_approvalsettings_line_t.value_to
                AND m_approvalsettings_line_t.approve_required = 'Yes'",
                [$module_name, $total_amount]
            );

            Log::debug('Approval Settings Query Result', ['count' => count($approval_settings)]);

            if (count($approval_settings) > 0) {
                foreach ($approval_settings as $setting) {
                    if ($setting->approver_id) {
                        $approvers[] = (int)$setting->approver_id;
                        Log::debug('Approver from Settings Added', ['approver_id' => $setting->approver_id]);
                    }
                }
            }

            // Remove duplicates while maintaining order
            $approvers = array_unique($approvers);

            Log::info('Final Approver List', ['approvers' => $approvers, 'count' => count($approvers)]);

            // Return as JSON-encoded array (like old code)
            $json_approvers = json_encode(array_values($approvers));
            Log::debug('JSON Approvers String', ['json' => $json_approvers]);

            return $json_approvers;
        } catch (\Exception $e) {
            Log::warning('[PurchaseQuotation] Error checking approval settings: ' . $e->getMessage());
            return '0'; // Default to auto-approve on error
        }
    }

    /**
     * ============================================================
     * CONSOLIDATED API ENDPOINTS - OPTIMIZED FOR MOBILE
     * ============================================================
     * Single comprehensive API that returns all supplier-based details
     * Reduces multiple API calls to ONE efficient call
     */

    /**
     * Get all supplier-based details in a SINGLE API call
     * GET /api/purchase/supplier/{supplierId}/comprehensive-details
     * 
     * Returns:
     * - Supplier details (payment terms, delivery terms, insurance, freight)
     * - All supplier sites with addresses
     * - Default pricelist
     * - Freight carriers
     * - Payment terms
     * - Payment methods
     * - Insurance terms
     * - Delivery terms
     * - Bill To & Ship To location addresses (if user location exists)
     * 
     * Optimized: Combines ~8-10 API calls into ONE efficient request
     */
    public function getComprehensiveSupplierDetails(Request $request, $supplierId)
    {
        Log::info('[PurchaseQuotation] getComprehensiveSupplierDetails - Request for supplier_id: ' . $supplierId);

        try {
            // ---- SUPPLIER DETAILS ----
            $supplier = DB::table('m_supplier_t')
                ->select(
                    'supplier_id',
                    'supplier_number',
                    'supplier_name',
                    'default_pricelist_id',
                    'default_payment_method_id',
                    'default_payment_terms_id',
                    'delivery_terms_id',
                    'insurance_term_id',
                    'frieghtterm_id',
                    'frieghtcarriers_id',
                    'supplier_type_id'
                )
                ->where('supplier_id', $supplierId)
                ->where('active', 'Yes')
                ->first();

            if (!$supplier) {
                Log::warning('[PurchaseQuotation] getComprehensiveSupplierDetails - Supplier not found: ' . $supplierId);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Supplier not found'
                ], 404);
            }

            // ---- SUPPLIER SITES ----
            $sites = DB::table('m_supplier_sites_t')
                ->select(
                    'supplier_site_id',
                    'supplier_site_number',
                    'supplier_site_name',
                    'primary_address',
                    'address',
                    'city',
                    'state',
                    'country',
                    'pincode'
                )
                ->where('supplier_id', $supplierId)
                ->orderBy('primary_address', 'desc')
                ->orderBy('supplier_site_name', 'asc')
                ->get();

            // Get primary site details with full address info
            $primarySite = DB::table('m_supplier_sites_t')
                ->select(
                    'supplier_site_id',
                    'supplier_site_number',
                    'supplier_site_name',
                    'address',
                    'city',
                    'state',
                    'country',
                    'pincode'
                )
                ->where('supplier_id', $supplierId)
                ->where('primary_address', 'Yes')
                ->first();

            // ---- FREIGHT CARRIERS ----
            // Always get freight carriers list (not just when supplier has default)
            $freightCarriers = DB::table('m_frieghtcarriers_hdr_t')
                ->select('ar_frieghtcarriers_hdr_id as freight_carrier_id', 'carrier_name')
                ->where('source_type_id', 'Purchase')
                ->orderBy('carrier_name', 'asc')
                ->get();

            // ---- PAYMENT TERMS ----
            $paymentTerms = DB::table('m_payment_terms_t')
                ->select('payment_term_id', 'payment_term_name')
                ->orderBy('payment_term_name', 'asc')
                ->get();

            // ---- PAYMENT METHODS ----
            $paymentMethods = DB::table('m_payment_methods_t')
                ->select('payment_method_id', 'payment_method_name')
                ->orderBy('payment_method_name', 'asc')
                ->get();

            // ---- INSURANCE TERMS ----
            $insuranceTerms = DB::table('m_insurance_terms_t')
                ->select('insurance_term_id', 'insurance_term_name')
                ->orderBy('insurance_term_name', 'asc')
                ->get();

            // ---- DELIVERY TERMS ----
            $deliveryTerms = DB::table('m_delivery_terms_t')
                ->select('delivery_terms_id', 'delivery_term_name')
                ->where('source_type_id', 'Purchase')
                ->orderBy('delivery_term_name', 'asc')
                ->get();

            // ---- FREIGHT TERMS (FOB Points) ----
            $freightTerms = DB::table('m_frieghtterms_t')
                ->select('frieghtterm_id', 'fob_point_name', 'freight_terms_type')
                ->where('active', 'Yes')
                ->orderBy('fob_point_name', 'asc')
                ->get();

            // ---- BILL TO & SHIP TO LOCATIONS (Current user's company location) ----
            // Use user's location_id if available, otherwise fallback to location_id = 1
            $userLocationId = Auth::user()->location_id ?? 1;

            $billToLocation = null;
            $shipToLocation = null;

            // Always try to get location details
            $locationData = DB::table('m_location_t')
                ->select(
                    'm_location_t.location_id',
                    'm_location_t.location_name',
                    'm_location_t.address',
                    'm_location_t.street_name',
                    'm_location_t.area',
                    'm_location_t.pincode',
                    'm_cities_t.city_name',
                    'm_states_t.state_name',
                    'm_countries_t.country_name'
                )
                ->leftJoin('m_cities_t', 'm_cities_t.city_id', '=', 'm_location_t.city_id')
                ->leftJoin('m_states_t', 'm_states_t.state_id', '=', 'm_location_t.state_id')
                ->leftJoin('m_countries_t', 'm_countries_t.country_id', '=', 'm_location_t.country_id')
                ->where('m_location_t.location_id', $userLocationId)
                ->first();

            if ($locationData) {
                // Format address string
                $addressParts = array_filter([
                    $locationData->address != 'null' ? $locationData->address : null,
                    $locationData->street_name,
                    $locationData->area,
                    $locationData->city_name,
                    $locationData->state_name,
                    $locationData->country_name,
                    $locationData->pincode
                ]);

                $billToLocation = [
                    'location_id' => $locationData->location_id,
                    'location_name' => $locationData->location_name,
                    'address' => $locationData->address,
                    'city_name' => $locationData->city_name,
                    'state_name' => $locationData->state_name,
                    'country_name' => $locationData->country_name,
                    'pincode' => $locationData->pincode,
                    'formatted_address' => implode(' - ', $addressParts)
                ];

                // Ship to location same as bill to for purchase
                $shipToLocation = $billToLocation;
            }

            // ---- PRICELIST DETAILS ----
            $pricelist = null;
            if ($supplier->default_pricelist_id) {
                $pricelist = DB::table('i_pricelist_hdr_t')
                    ->select('pricelist_hdr_id', 'pricelist_name')
                    ->where('pricelist_hdr_id', $supplier->default_pricelist_id)
                    ->first();
            }

            Log::info('[PurchaseQuotation] getComprehensiveSupplierDetails - Success', [
                'supplier_id' => $supplierId,
                'sites_count' => count($sites),
                'has_bill_to' => $billToLocation !== null
            ]);

            // ---- FORMAT RESPONSE ----
            return response()->json([
                'status' => 'success',
                'data' => [
                    // Supplier Info
                    'supplier' => [
                        'supplier_id' => $supplier->supplier_id,
                        'supplier_number' => $supplier->supplier_number,
                        'supplier_name' => $supplier->supplier_name,
                        'supplier_type_id' => $supplier->supplier_type_id,
                        'default_pricelist_id' => $supplier->default_pricelist_id,
                        'default_payment_method_id' => $supplier->default_payment_method_id,
                        'default_payment_terms_id' => $supplier->default_payment_terms_id,
                        'delivery_terms_id' => $supplier->delivery_terms_id,
                        'insurance_term_id' => $supplier->insurance_term_id,
                        'frieghtterm_id' => $supplier->frieghtterm_id,
                        'frieghtcarriers_id' => $supplier->frieghtcarriers_id
                    ],

                    // Sites (for dropdown)
                    'sites' => $sites,
                    'primary_site' => $primarySite,

                    // Master Dropdowns
                    'freight_carriers' => $freightCarriers,
                    'freight_terms' => $freightTerms,
                    'payment_terms' => $paymentTerms,
                    'payment_methods' => $paymentMethods,
                    'insurance_terms' => $insuranceTerms,
                    'delivery_terms' => $deliveryTerms,

                    // Addresses
                    'bill_to_location' => $billToLocation,
                    'ship_to_location' => $shipToLocation,

                    // Pricelist
                    'pricelist' => $pricelist
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getComprehensiveSupplierDetails - Error: ' . $e->getMessage(), [
                'supplier_id' => $supplierId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching supplier details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get supplier-based products with pricing
     * GET /api/purchase/supplier/{supplierId}/products
     * 
     * Query params:
     * - supplier_site_id: Filter tax by supplier site
     * - quotation_type: STANDARD or LABOUR
     * - search: Product search term
     * 
     * Returns products with:
     * - Supplier-specific pricing from default pricelist
     * - Manufacturer part number for supplier
     * - Tax group based on HSN and supplier site
     * - Unit of measurement
     */
    public function getSupplierProducts(Request $request, $supplierId)
    {
        $supplierSiteId = $request->input('supplier_site_id');
        $quotationType = $request->input('quotation_type', 'STANDARD');
        $search = $request->input('search', '');
        $pricelistId = $request->input('pricelist_id');

        Log::info('[PurchaseQuotation] getSupplierProducts - Request received', [
            'supplier_id' => $supplierId,
            'supplier_site_id' => $supplierSiteId,
            'quotation_type' => $quotationType,
            'pricelist_id' => $pricelistId,
            'search' => $search
        ]);

        try {
            // Get supplier's default pricelist if not provided
            if (!$pricelistId) {
                $supplier = DB::table('m_supplier_t')
                    ->select('default_pricelist_id')
                    ->where('supplier_id', $supplierId)
                    ->first();

                if ($supplier) {
                    $pricelistId = $supplier->default_pricelist_id;
                }
            }

            // ---- PRODUCT QUERY WITH PRICELIST ----
            // Use subquery to avoid duplicate products when multiple prices exist in pricelist
            $priceSubquery = DB::table('i_pricelist_lines_t')
                ->select('product_id', DB::raw('MAX(unit_price) as unit_price'))
                ->where('pricelist_hdr_id', $pricelistId ?? 0)
                ->groupBy('product_id');

            $query = DB::table('m_products_t as mp')
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

            // Search filter
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('mp.concatenated_product', 'like', '%' . $search . '%')
                        ->orWhere('mp.product_code', 'like', '%' . $search . '%');
                });
            }

            $products = $query->orderBy('mp.concatenated_product', 'asc')
                ->limit(100)
                ->get();

            // ---- ENRICH PRODUCTS WITH SUPPLIER-SPECIFIC DATA ----
            $enrichedProducts = [];

            foreach ($products as $product) {
                // Manufacturer part number for this supplier
                $partNo = DB::table('m_manufacturer_partno_t')
                    ->select('manufacturer_partno_id')
                    ->where('product_id', $product->product_id)
                    ->where('manufacturer_source_value_id', $supplierId)
                    ->where('manufacturer_source', 'SUPPLIER')
                    ->first();

                // Tax group for this product in supplier site
                $taxData = [];
                if ($supplierSiteId && $product->defalut_hsn_code) {
                    $taxData = $this->getTaxGroupForHsn($product->defalut_hsn_code, $supplierSiteId);
                }

                $enrichedProducts[] = [
                    'product_id' => $product->product_id,
                    'product_code' => $product->product_code,
                    'concatenated_product' => $product->concatenated_product,
                    'uom_code_id' => $product->primary_uom_id,
                    'hsn_code' => $product->defalut_hsn_code,
                    'unit_price' => $product->unit_price ?? 0,
                    'manufacturer_part_no' => $partNo->manufacturer_partno_id ?? null,
                    'tax_group_id' => $taxData['tax_group_id'] ?? null,
                    'tax_group_name' => $taxData['tax_group_name'] ?? '',
                    'tax_percentage' => $taxData['display_name'] ?? '',
                    'min_order_qty' => $product->min_order_qty,
                    'max_order_qty' => $product->max_order_qty
                ];
            }

            Log::info('[PurchaseQuotation] getSupplierProducts - Found ' . count($enrichedProducts) . ' products', [
                'supplier_id' => $supplierId
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $enrichedProducts
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getSupplierProducts - Error: ' . $e->getMessage(), [
                'supplier_id' => $supplierId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching supplier products: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get PAGINATED supplier products for purchase quotation (with server-side search)
     * GET /api/purchase/quotation/supplier/{supplierId}/products
     * Query params: page, per_page, search, supplier_site_id, pricelist_id, quotation_type
     */
    public function getQuotationSupplierProductsPaginated(Request $request, $supplierId)
    {
        $supplierSiteId  = $request->input('supplier_site_id');
        $quotationType   = $request->input('quotation_type', 'STANDARD');
        $search          = $request->input('search', '');
        $pricelistId     = $request->input('pricelist_id');
        $page            = max(1, (int) $request->input('page', 1));
        $perPage         = min(100, max(1, (int) $request->input('per_page', 50)));
        $offset          = ($page - 1) * $perPage;

        Log::info('[PurchaseQuotation] getQuotationSupplierProductsPaginated', [
            'supplier_id'     => $supplierId,
            'page'            => $page,
            'per_page'        => $perPage,
            'search'          => $search,
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

            // Base query (shared for count and page)
            // Use subquery to avoid duplicate products when multiple prices exist in pricelist
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

            // Total count for pagination metadata
            $total = (clone $baseQuery)->count();

            // Fetch paged products
            $products = (clone $baseQuery)
                ->orderBy('mp.concatenated_product', 'asc')
                ->offset($offset)
                ->limit($perPage)
                ->get();

            // Enrich each product with supplier-specific data
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
                    $taxData = $this->getTaxGroupForHsn($product->defalut_hsn_code, $supplierSiteId);
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

            Log::info('[PurchaseQuotation] getQuotationSupplierProductsPaginated - returning ' . count($enrichedProducts) . " of {$total}");

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
            Log::error('[PurchaseQuotation] getQuotationSupplierProductsPaginated error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to fetch products: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate quotation number in format QTN-S{number}
     * Returns array with quotation_no and poquote_count
     */
    private function generateQuotationNumber()
    {
        // Format: QTN-S{number} - matches existing web app format
        // Example: QTN-S1153, QTN-S1154, etc.
        $prefix = 'QTN-S';

        // Get the last quotation to find the highest poquote_count
        $lastQuote = DB::table('p_quotation_hdr_t')
            ->whereNotNull('poquote_count')
            ->orderBy('poquote_count', 'desc')
            ->first();

        if ($lastQuote && $lastQuote->poquote_count) {
            $newCount = (int) $lastQuote->poquote_count + 1;
        } else {
            // Fallback: extract number from last quotation_no
            $lastQuoteByNo = DB::table('p_quotation_hdr_t')
                ->where('quotation_no', 'like', 'QTN-S%')
                ->orderBy('quotation_hdr_id', 'desc')
                ->first();

            if ($lastQuoteByNo && $lastQuoteByNo->quotation_no) {
                // Extract number from QTN-S1153 format
                $lastNum = (int) str_replace('QTN-S', '', $lastQuoteByNo->quotation_no);
                $newCount = $lastNum + 1;
            } else {
                $newCount = 1;
            }
        }

        return [
            'quotation_no' => $prefix . $newCount,
            'poquote_count' => $newCount
        ];
    }

    /**
     * Get previous purchase (PVS) cost for a product (JSON response)
     * GET /api/purchase/product/{productId}/previous-cost
     * Returns up to 3 most recent invoice records (current + backup)
     */
    public function getProductPreviousCost($id)
    {
        Log::info('[PurchaseQuotation] getProductPreviousCost - Request for product_id: ' . $id);

        try {
            // Current invoices (include bill_number to dedupe)
            $main = DB::table('p_po_invoice_lines_t as pl')
                ->leftJoin('p_po_invoice_hdr_t as ph', 'pl.po_invoice_id', '=', 'ph.po_invoice_id')
                ->leftJoin('p_quotation_lines_t as ql', 'pl.product_id', '=', 'ql.product_id')
                ->leftJoin('m_supplier_t as s', 'ph.supplier_id', '=', 's.supplier_id')
                ->leftJoin('m_products_t as p', 'ql.product_id', '=', 'p.product_id')
                ->select(
                    'ph.supplier_id',
                    's.supplier_name',
                    'ph.supplier_invoice_no',
                    'ph.supplier_invoice_date',
                    'ph.bill_number',
                    'pl.product_id',
                    DB::raw("p.concatenated_product as prd_name"),
                    DB::raw("pl.unit_price as pvs_cost"),
                    DB::raw("pl.qty as inv_qty"),
                    DB::raw("ql.unit_price as quoted_cost"),
                    'pl.po_invoice_lines_id'
                )
                ->where('ql.product_id', $id)
                ->orderBy('pl.po_invoice_lines_id', 'desc')
                ->get()
                ->toArray();

            // Backup invoices (include bill_number)
            $bk = DB::table('p_po_invoice_lines_t_bk as plb')
                ->leftJoin('p_po_invoice_hdr_t_bk as phb', 'plb.po_invoice_id', '=', 'phb.po_invoice_id')
                ->leftJoin('p_quotation_lines_t as ql2', 'plb.product_id', '=', 'ql2.product_id')
                ->leftJoin('m_supplier_t as s2', 'phb.supplier_id', '=', 's2.supplier_id')
                ->leftJoin('m_products_t as p2', 'ql2.product_id', '=', 'p2.product_id')
                ->select(
                    'phb.supplier_id',
                    's2.supplier_name',
                    'phb.supplier_invoice_no',
                    'phb.supplier_invoice_date',
                    'phb.bill_number',
                    'plb.product_id',
                    DB::raw("p2.concatenated_product as prd_name"),
                    DB::raw("plb.unit_price as pvs_cost"),
                    DB::raw("plb.qty as inv_qty"),
                    DB::raw("ql2.unit_price as quoted_cost"),
                    'plb.po_invoice_lines_id'
                )
                ->where('ql2.product_id', $id)
                ->orderBy('plb.po_invoice_lines_id', 'desc')
                ->get()
                ->toArray();

            // Merge keeping order (newest first)
            $combined = array_merge($main, $bk);

            // Deduplicate by bill_number (keep the first occurrence, which is the latest)
            $unique = [];
            $seenBills = [];
            foreach ($combined as $row) {
                $bill = $row->bill_number ?? null;
                // If bill_number missing, fallback to supplier_invoice_no (may contain text)
                if (empty($bill)) {
                    $bill = $row->supplier_invoice_no ?? null;
                }
                // Use a unique key even when both are empty
                $key = $bill !== null && $bill !== '' ? (string)$bill : 'bn_id_' . ($row->po_invoice_lines_id ?? uniqid());

                if (isset($seenBills[$key])) {
                    continue;
                }

                $seenBills[$key] = true;
                $unique[] = $row;

                if (count($unique) >= 3) {
                    break;
                }
            }

            // Map to clean array (optional: format dates)
            $result = array_map(function ($r) {
                return [
                    'supplier_id' => $r->supplier_id ?? null,
                    'supplier_name' => $r->supplier_name ?? null,
                    'supplier_invoice_no' => $r->supplier_invoice_no ?? null,
                    'supplier_invoice_date' => $r->supplier_invoice_date ?? null,
                    'product_id' => $r->product_id ?? null,
                    'prd_name' => $r->prd_name ?? null,
                    'pvs_cost' => (float) ($r->pvs_cost ?? 0),
                    'inv_qty' => (float) ($r->inv_qty ?? 0),
                    'quoted_cost' => (float) ($r->quoted_cost ?? 0),
                    'po_invoice_lines_id' => $r->po_invoice_lines_id ?? null,
                    'bill_number' => $r->bill_number ?? null
                ];
            }, $unique);

            Log::info('[PurchaseQuotation] getProductPreviousCost - Found ' . count($result) . ' previous purchases for product ' . $id);

            return response()->json([
                'status' => 'success',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('[PurchaseQuotation] getProductPreviousCost - Error: ' . $e->getMessage(), ['product_id' => $id, 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch previous costs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Module Approval Name for Approval Settings Query
     * Maps module code to module_name in m_approvalsettings_hdr_t
     */
    private function getModuleApprovalName($module)
    {
        $module_map = [
            'soorder' => 'Sales Order Approval',
            'soinvoice' => 'Sales Invoice Approval',
            'soquote' => 'Sales Quote Approval',
            'poorder' => 'Purchaseorder Approval',
            'poinvoice' => 'Purchase Invoice Approval',
            'poquote' => 'Purchase Quotation Approval',
            'product' => 'Product Approval',
            'supplier' => 'Supplier Approval',
            'customers' => 'Customer Approval',
            'schemes' => 'Schemes Approval',
            'materialbom' => 'BOM Approval',
            'purchaseprice' => 'Purchase Pricelist Approval',
            'salesprice' => 'Sales Pricelist Approval',
        ];

        $module_name = $module_map[$module] ?? 'Sales Order Approval';
        Log::debug('Module Name Mapped', ['module' => $module, 'module_name' => $module_name]);
        return $module_name;
    }

    public function getAllPurchaseQuotations(Request $request)
    {
        try {
            Log::info('=== GET ALL PURCHASE QUOTATIONS STARTED ===');

            // Input parameters with pagination
            $per_page = min($request->input('per_page', 50), 500); // Max 500 per page for large datasets
            $page = $request->input('page', 1);

            // Search and filter parameters
            $search = $request->input('search', '');
            $quote_status = $request->input('quote_status', '');
            $quotation_type = $request->input('quotation_type', '');
            $from_date = $request->input('from_date', '');
            $to_date = $request->input('to_date', '');
            $month = $request->input('month', '');
            $year = $request->input('year', '');
            $sort_by = $request->input('sort_by', 'created_at');
            $sort_order = $request->input('sort_order', 'desc');

            Log::info('Pagination and Filter Parameters', [
                'per_page' => $per_page,
                'page' => $page,
                'search' => $search,
                'quote_status' => $quote_status,
                'quotation_type' => $quotation_type,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'month' => $month,
                'year' => $year,
                'sort_by' => $sort_by,
                'sort_order' => $sort_order
            ]);

            // Debug: Get date range of quotations in database
            $dateRange = DB::table('p_quotation_hdr_t')
                ->selectRaw('MIN(quotation_date) as min_date, MAX(quotation_date) as max_date, COUNT(*) as total')
                ->first();
            Log::info('Quotations Date Range in DB', [
                'min_date' => $dateRange->min_date,
                'max_date' => $dateRange->max_date,
                'total_quotations' => $dateRange->total
            ]);

            // Build query - join to tb_users instead of hr_employee_t
            $query = DB::table('p_quotation_hdr_t as qh')
                ->leftJoin('m_supplier_t as s', 'qh.supplier_id', '=', 's.supplier_id')
                ->leftJoin('hr_employee_t as e', 'e.employee_id', '=', 'qh.created_by')
                ->select(
                    'qh.quotation_hdr_id',
                    'qh.quotation_no',
                    'qh.quotation_date',
                    'qh.delivery_date',
                    'qh.supplier_id',
                    's.supplier_name',
                    'qh.quote_status',
                    'qh.quotation_type',
                    'qh.quote_grand_total',
                    'qh.created_by',
                    'e.first_name as created_by_name',
                    'qh.created_at'
                );

            // Apply search filter
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('qh.quotation_no', 'like', '%' . $search . '%')
                        ->orWhere('s.supplier_name', 'like', '%' . $search . '%')
                        ->orWhere('qh.supplier_ref_no', 'like', '%' . $search . '%');
                });
            }

            // Apply status filter
            if (!empty($quote_status)) {
                $query->where('qh.quote_status', $quote_status);
            }

            // Apply quotation type filter
            if (!empty($quotation_type)) {
                $query->where('qh.quotation_type', $quotation_type);
            }

            // Apply date range filter (handle nulls properly)
            if (!empty($from_date) && !empty($to_date)) {
                $query->whereBetween('qh.quotation_date', [$from_date, $to_date]);
                Log::info('Applying date range filter', ['from' => $from_date, 'to' => $to_date]);
            } elseif (!empty($from_date)) {
                $query->where('qh.quotation_date', '>=', $from_date);
                Log::info('Applying from_date filter', ['from_date' => $from_date]);
            } elseif (!empty($to_date)) {
                $query->where('qh.quotation_date', '<=', $to_date);
                Log::info('Applying to_date filter', ['to_date' => $to_date]);
            }

            // Apply month/year filter (these take precedence over date range if both provided)
            if (!empty($month) && !empty($year)) {
                $query->whereYear('qh.quotation_date', $year)
                    ->whereMonth('qh.quotation_date', $month);
                Log::info('Applying month/year filter', ['month' => $month, 'year' => $year]);
            } elseif (!empty($month)) {
                $query->whereMonth('qh.quotation_date', $month);
                Log::info('Applying month filter', ['month' => $month]);
            } elseif (!empty($year)) {
                $query->whereYear('qh.quotation_date', $year);
                Log::info('Applying year filter', ['year' => $year]);
            }

            // Apply sorting
            $allowed_sort_fields = ['created_at', 'quotation_date', 'quote_grand_total', 'quote_status'];
            $sort_column = in_array($sort_by, $allowed_sort_fields) ? 'qh.' . $sort_by : 'qh.created_at';
            $sort_direction = in_array(strtolower($sort_order), ['asc', 'desc']) ? $sort_order : 'desc';

            $query->orderBy($sort_column, $sort_direction);

            // Log the SQL query for debugging
            $sql = str_replace(['?'], ['\'%s\''], $query->toSql());
            $sql = vsprintf($sql, $query->getBindings());
            Log::info('Final SQL Query', ['sql' => $sql]);

            // Apply pagination
            $quotations = $query->paginate($per_page, ['*'], 'page', $page);

            Log::info('Purchase Quotations Retrieved', [
                'total' => $quotations->total(),
                'current_page' => $quotations->currentPage(),
                'per_page' => $quotations->perPage(),
                'has_more' => $quotations->hasMorePages(),
                'filters_applied' => [
                    'has_search' => !empty($search),
                    'has_status' => !empty($quote_status),
                    'has_type' => !empty($quotation_type),
                    'has_date_filter' => !empty($from_date) || !empty($to_date) || !empty($month) || !empty($year)
                ]
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase quotations retrieved successfully',
                'data' => $quotations->items(),
                'pagination' => [
                    'current_page' => $quotations->currentPage(),
                    'per_page' => $quotations->perPage(),
                    'total' => $quotations->total(),
                    'last_page' => $quotations->lastPage(),
                    'has_more' => $quotations->hasMorePages()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('=== GET ALL PURCHASE QUOTATIONS FAILED ===');
            Log::error('Get all purchase quotations error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error retrieving purchase quotations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPurchaseQuotationByID(Request $request, $id)
    {
        try {
            Log::info('=== GET PURCHASE QUOTATION DETAILS BY ID STARTED ===', ['quotation_hdr_id' => $id]);

            // Get quotation header with all details including supplier site address details
            $quotation = DB::table('p_quotation_hdr_t as qh')
                ->leftJoin('m_supplier_t as s', 'qh.supplier_id', '=', 's.supplier_id')
                ->leftJoin('m_supplier_sites_t as ss', 'qh.supplier_site_id', '=', 'ss.supplier_site_id')
                ->leftJoin('m_states_t as supplier_state', 'ss.state', '=', 'supplier_state.state_id')
                ->leftJoin('m_cities_t as supplier_city', 'ss.city', '=', 'supplier_city.city_id')
                ->leftJoin('i_pricelist_hdr_t as ip', 'qh.quote_pricelist_id', '=', 'ip.pricelist_hdr_id')
                ->leftJoin('m_frieghtcarriers_hdr_t as fc', 'qh.freight_carrier_id', '=', 'fc.ar_frieghtcarriers_hdr_id')
                ->leftJoin('m_payment_methods_t as pm', 'qh.default_payment_method_id', '=', 'pm.payment_method_id')
                ->leftJoin('m_payment_terms_t as pt', 'qh.payment_term_id', '=', 'pt.payment_term_id')
                ->leftJoin('m_delivery_terms_t as dt', 'qh.delivery_terms_id', '=', 'dt.delivery_terms_id')
                ->leftJoin('m_insurance_terms_t as it', 'qh.insurance_term_id', '=', 'it.insurance_term_id')
                ->leftJoin('m_projects_t as pj', 'qh.project_id', '=', 'pj.project_id')
                ->leftJoin('m_organizations_t as org', 'qh.organization_id', '=', 'org.organization_id')
                ->leftJoin('tb_users as u', 'qh.created_by', '=', 'u.id')
                ->leftJoin('m_location_t as bill_loc', 'qh.bill_to_location_id', '=', 'bill_loc.location_id')
                ->leftJoin('m_location_t as ship_loc', 'qh.ship_to_location_id', '=', 'ship_loc.location_id')
                ->select(
                    'qh.quotation_hdr_id',
                    'qh.quotation_no',
                    'qh.quotation_date',
                    'qh.delivery_date',
                    'qh.supplier_id',
                    's.supplier_name',
                    'ss.supplier_site_name',
                    'qh.supplier_site_id',
                    'supplier_state.state_name as supplier_site_state',
                    'supplier_city.city_name as supplier_site_city',
                    'ss.address as supplier_site_address',
                    'ss.pincode as supplier_site_pincode',
                    'qh.quote_pricelist_id',
                    'ip.pricelist_name',
                    'qh.freight_carrier_id',
                    'fc.carrier_name',
                    'qh.quote_status',
                    'qh.quotation_type',
                    'qh.quote_grand_total',
                    'qh.quote_tax_total',
                    'qh.created_by',
                    'u.username as created_by_name',
                    'qh.created_at',
                    'qh.source',
                    'qh.reference_number',
                    'qh.reference_id',
                    'qh.attachfile_name as attachements',
                    'qh.default_payment_method_id',
                    'pm.payment_method_name',
                    'qh.payment_term_id',
                    'pt.payment_term_name',
                    'qh.supplier_quotation_date',
                    'qh.delivery_terms_id',
                    'dt.delivery_term_name',
                    'qh.insurance_term_id',
                    'it.insurance_term_name',
                    'qh.project_id',
                    'pj.project_name',
                    'qh.organization_id',
                    'org.organization_name',
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
                Log::warning('Purchase quotation not found', ['quotation_hdr_id' => $id]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Purchase quotation not found'
                ], 404);
            }

            // Get quotation line items with product details
            $lines = DB::table('p_quotation_lines_t as ql')
                ->leftJoin('m_products_t as p', 'ql.product_id', '=', 'p.product_id')
                ->leftJoin('m_uom_codes_t as uom', 'ql.uom_code_id', '=', 'uom.uom_code_id')
                ->leftJoin('f_gst_code_hdr_t as hsn', 'ql.hsn_code', '=', 'hsn.gst_code_hdr_id')
                ->leftJoin('m_tax_group_t as tg', 'ql.tax_group_id', '=', 'tg.tax_group_id')
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
                    'ql.hsn_code',
                    'hsn.classification_code as hsn_code_display',
                    'ql.tax_group_id',
                    'tg.tax_group_name',
                    'ql.tax_amount',
                    'ql.line_total',
                    'ql.promised_date',
                    'ql.comments'
                )
                ->where('ql.quotation_hdr_id', $id)
                ->orderBy('ql.line_no', 'asc')
                ->get();

            // Get bill-to and ship-to address details
            $bill_to_address = "";
            $ship_to_address = "";

            if ($quotation->bill_to_location_id) {
                $bill_location = DB::table('m_location_t as ml')
                    ->leftJoin('m_cities_t as mc', 'ml.city_id', '=', 'mc.city_id')
                    ->leftJoin('m_states_t as ms', 'ml.state_id', '=', 'ms.state_id')
                    ->leftJoin('m_countries_t as mco', 'ml.country_id', '=', 'mco.country_id')
                    ->select(
                        'ml.location_name',
                        'ml.address',
                        'ml.street_name',
                        'ml.area',
                        'mc.city_name',
                        'ms.state_name',
                        'mco.country_name',
                        'ml.pincode'
                    )
                    ->where('ml.location_id', $quotation->bill_to_location_id)
                    ->first();

                if ($bill_location) {
                    $parts = array_filter([
                        $bill_location->address != 'null' ? $bill_location->address : null,
                        $bill_location->street_name,
                        $bill_location->area,
                        $bill_location->city_name,
                        $bill_location->state_name,
                        $bill_location->country_name,
                        $bill_location->pincode
                    ]);
                    $bill_to_address = implode(', ', $parts);
                }
            }

            if ($quotation->ship_to_location_id) {
                $ship_location = DB::table('m_location_t as ml')
                    ->leftJoin('m_cities_t as mc', 'ml.city_id', '=', 'mc.city_id')
                    ->leftJoin('m_states_t as ms', 'ml.state_id', '=', 'ms.state_id')
                    ->leftJoin('m_countries_t as mco', 'ml.country_id', '=', 'mco.country_id')
                    ->select(
                        'ml.location_name',
                        'ml.address',
                        'ml.street_name',
                        'ml.area',
                        'mc.city_name',
                        'ms.state_name',
                        'mco.country_name',
                        'ml.pincode'
                    )
                    ->where('ml.location_id', $quotation->ship_to_location_id)
                    ->first();

                if ($ship_location) {
                    $parts = array_filter([
                        $ship_location->address != 'null' ? $ship_location->address : null,
                        $ship_location->street_name,
                        $ship_location->area,
                        $ship_location->city_name,
                        $ship_location->state_name,
                        $ship_location->country_name,
                        $ship_location->pincode
                    ]);
                    $ship_to_address = implode(', ', $parts);
                }
            }

            // Build attachments list from files_t (API uploads) + attachfile_name (web uploads)
            $attachments = [];

            // 1) files uploaded via API (files_t)
            $filesFromTable = DB::table('files_t')
                ->where('entity_type', 'purchase_quotation')
                ->where('entity_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($filesFromTable as $file) {
                $attachments[] = [
                    'id' => $file->id,
                    'name' => $file->file_name,
                    'url' => $file->file_url,
                    'path' => $file->file_path,
                    'size' => $file->file_size,
                    'mime_type' => $file->mime_type,
                    'source' => 'api',
                    'uploaded_at' => $file->created_at
                ];
            }

            // 2) old web uploads (attachfile_name JSON stored on header)
            // NOTE: use correct column name 'attachfile_name' and add debug info
            Log::info('[PurchaseQuotation] attachfile_name raw', ['quotation_hdr_id' => $id, 'attachfile_name' => $quotation->attachfile_name ?? null]);

            // Debug: files from files_t table
            Log::info('[PurchaseQuotation] files_from_table debug', [
                'quotation_hdr_id' => $id,
                'files_count' => isset($filesFromTable) ? count($filesFromTable) : 0,
                'files' => array_map(function ($f) {
                    return [
                        'id' => $f->id ?? null,
                        'file_name' => $f->file_name ?? null,
                        'file_path' => $f->file_path ?? null
                    ];
                }, ($filesFromTable ?? collect())->toArray())
            ]);

            if (!empty($quotation->attachfile_name)) {
                $webFiles = json_decode($quotation->attachfile_name, true) ?? [];
                if (is_array($webFiles)) {
                    foreach ($webFiles as $fileName) {
                        // Check both legacy folder names
                        $path1 = public_path('Uploads/poquoteattachment/PO' . $id . '/' . $fileName);
                        $path2 = public_path('Uploads/poquoteattachment/POQUOTE' . $id . '/' . $fileName);
                        $foundPath = null;
                        $exists1 = file_exists($path1);
                        $exists2 = file_exists($path2);

                        Log::debug('[PurchaseQuotation] web file check', [
                            'quotation_hdr_id' => $id,
                            'file_name' => $fileName,
                            'path1' => $path1,
                            'exists1' => $exists1,
                            'path2' => $path2,
                            'exists2' => $exists2
                        ]);

                        if ($exists1) {
                            $foundPath = $path1;
                            $url = url('Uploads/poquoteattachment/PO' . $id . '/' . rawurlencode($fileName));
                        } elseif ($exists2) {
                            $foundPath = $path2;
                            $url = url('Uploads/poquoteattachment/POQUOTE' . $id . '/' . rawurlencode($fileName));
                        } else {
                            // File not found on disk, still include with source 'web' but mark as missing
                            $url = null;
                        }

                        $attachments[] = [
                            'id' => null,
                            'name' => $fileName,
                            'url' => $url,
                            'path' => $foundPath,
                            'size' => $foundPath ? filesize($foundPath) : null,
                            'mime_type' => $foundPath ? mime_content_type($foundPath) : null,
                            'source' => 'web',
                            'uploaded_at' => null,
                            'exists_on_disk' => $foundPath !== null
                        ];
                    }
                }
            }

            // DEBUG: Log attachments info for troubleshooting
            Log::info('[PurchaseQuotation] attachments debug', [
                'quotation_hdr_id' => $id,
                'attachments_count' => count($attachments),
                'api_files_count' => isset($filesFromTable) ? count($filesFromTable) : 0,
                'attachment_names' => array_column($attachments, 'name')
            ]);

            Log::info('Purchase quotation details retrieved successfully', [
                'quotation_hdr_id' => $id,
                'lines_count' => count($lines)
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase quotation details retrieved successfully',
                'data' => [
                    'quotation' => $quotation,
                    'lines' => $lines,
                    'bill_to_address' => $bill_to_address,
                    'ship_to_address' => $ship_to_address,
                    'attachments' => $attachments,
                    'summary' => [
                        'total_items' => count($lines),
                        'total_quantity' => collect($lines)->sum('qty'),
                        'subtotal' => collect($lines)->sum(function ($line) {
                            return ($line->qty * $line->unit_price) - ($line->discount_amount ?? 0);
                        }),
                        'total_tax' => $quotation->quote_tax_total,
                        'grand_total' => $quotation->quote_grand_total
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('=== GET PURCHASE QUOTATION BY ID FAILED ===', ['quotation_hdr_id' => $id]);
            Log::error('Get purchase quotation by ID error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Error retrieving purchase quotation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
