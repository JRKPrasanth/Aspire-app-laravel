<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Create Sales Order
     * POST /api/sales/create
     */
    public function createSalesOrder(Request $request)
    {
        try {
            Log::info('=== CREATE SALES ORDER STARTED ===');
            $posted_data = $request->json()->all();
            Log::debug('Request Data Received', ['posted_data' => $posted_data]);

            // Validate required fields
            Log::info('Validating Sales Order Fields');
            $validated = $this->validateSalesOrder($posted_data);
            if (!$validated['success']) {
                Log::warning('Sales Order Validation Failed', ['errors' => $validated['message']]);
                return response()->json([
                    'message' => $validated['message'],
                    'success' => false
                ], 400);
            }

            // Get authenticated user
            // Log::info('Fetching Authenticated User Details');
            $user = Auth::user();
            // Log::debug('Authenticated User ID', ['user_id' => $user->id]);
            $user_details = DB::table('tb_users')
                ->where('id', $user->id)
                ->first();
            // Log::debug('User Details Retrieved', ['employee_id' => $user_details->employee_id ?? null, 'company_id' => $user_details->company_id ?? null]);

            if (!$user_details) {
                Log::error('User Details Not Found', ['user_id' => $user->id]);
                return response()->json([
                    'message' => 'User not found',
                    'success' => false
                ], 401);
            }

            // Start transaction
            Log::info('Starting Database Transaction');
            DB::beginTransaction();

            // Fetch customer site addresses (BILL_TO and SHIP_TO)
            Log::info('Fetching Customer Site Addresses', ['customer_id' => $posted_data['ship_to_customer_id']]);
            $customer_sites = DB::table('m_customer_sites_t')
                ->where('customer_id', $posted_data['ship_to_customer_id'])
                ->whereIn('site_type', ['BILL_TO', 'SHIP_TO'])
                ->where('active', 'Yes')
                ->select('customer_site_id', 'site_type')
                ->get();

            $bill_to_address_id = '';
            $ship_to_address_id = '';

            foreach ($customer_sites as $site) {
                if ($site->site_type === 'BILL_TO') {
                    $bill_to_address_id = $site->customer_site_id;
                } elseif ($site->site_type === 'SHIP_TO') {
                    $ship_to_address_id = $site->customer_site_id;
                }
            }
            Log::debug('Customer Site Addresses Retrieved', ['bill_to' => $bill_to_address_id, 'ship_to' => $ship_to_address_id]);

            // Fetch salesperson from customer record
            Log::info('Fetching Salesperson from Customer Record');
            $customer_record = DB::table('m_customers_t')
                ->where('customer_id', $posted_data['ship_to_customer_id'])
                ->select('sales_person')
                ->first();

            $salesperson_id = $customer_record->sales_person ?? $user_details->employee_id;
            Log::debug('Salesperson ID Retrieved', ['salesperson_id' => $salesperson_id]);

            // Fetch salesperson name for contact person
            Log::info('Fetching Salesperson Name for Contact Person', ['salesperson_id' => $salesperson_id]);
            $salesperson = DB::table('hr_employee_t')
                ->where('employee_id', $salesperson_id)
                ->select(DB::raw("CONCAT(first_name, ' ', COALESCE(last_name, '')) as full_name"))
                ->first();

            $contact_person_name = $salesperson ? $salesperson->full_name : '';
            Log::debug('Salesperson Name Retrieved', ['contact_person' => $contact_person_name]);

            // Prepare header data
            Log::info('Preparing Sales Order Header Data');
            $header_data = [
                'created_by' => $user_details->employee_id,
                'company_id' => $user_details->company_id,
                'location_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'ar_delivery_terms_id' => $posted_data['ar_delivery_terms_id'] ?? 0,
                'ar_payment_method_id' => $posted_data['ar_payment_method_id'] ?? 0,
                'bill_to_address_id' => $bill_to_address_id,
                'ship_to_address_id' => $ship_to_address_id,
                'salesperson_id' => $salesperson_id,
                'contact_person' => $contact_person_name,
                'order_status_id' => 'DRAFT',
                'order_status' => 'INITIATED',
                'order_type_id' => 'STANDARD',
                'cash_discount' => 2,
                'source' => 'STANDARD',
                'proforma_invoice' => 'NO',
                'contact_number' => $posted_data['contact_number'] ?? '',
                'sales_order_date' => date('Y-m-d', strtotime($posted_data['sales_order_date'])),
                'invoice_currency' => $posted_data['invoice_currency'] ?? 37,
                'freight_carrier_id' => $posted_data['freight_carrier_id'] ?? 0,
                'pricelist_id' => $posted_data['pricelist_id'],
                'discount_id' => $posted_data['discount_id'] ?? 0,
                'ship_to_customer_id' => $posted_data['ship_to_customer_id'],
                'organization_id' => 1,
                'last_updated_by' => $user_details->employee_id,
                'employee_id' => 0,
                'tcs_applicable' => $posted_data['tcs_applicable'] ?? 'NO',
                'delivery_date' => date('Y-m-d', strtotime($posted_data['delivery_date'])),
            ];

            // Generate sequence number
            Log::info('Generating Sales Order Sequence Number');
            $seqno = $this->generateSequenceNumber('SO', 's_salesorder_hdr_t', $header_data['order_type_id']);
            $header_data['sales_order_no'] = $seqno['number'];
            $header_data['salesorder_count'] = $seqno['count'];
            $header_data['reference_number'] = $seqno['number'];
            Log::debug('Sales Order Number Generated', ['sales_order_no' => $seqno['number'], 'count' => $seqno['count']]);

            // Insert header
            Log::info('Inserting Sales Order Header into Database');
            $sales_hdr_id = DB::table('s_salesorder_hdr_t')->insertGetId($header_data);
            Log::info('Sales Order Header Inserted Successfully', ['sales_hdr_id' => $sales_hdr_id, 'type' => gettype($sales_hdr_id)]);

            // Verify sales_hdr_id was generated
            if (!$sales_hdr_id || $sales_hdr_id <= 0) {
                Log::error('Failed to Generate Sales Order ID', ['sales_hdr_id' => $sales_hdr_id, 'header_data' => $header_data]);
                DB::rollBack();
                return response()->json([
                    'message' => 'Failed to generate sales order ID',
                    'success' => false,
                    'error' => 'Database insert returned invalid ID: ' . var_export($sales_hdr_id, true)
                ], 500);
            }

            // Process lines
            Log::info('Processing Sales Order Line Items', ['line_count' => count($posted_data['so_lines'])]);
            $total_amount = 0;
            $total_tax = 0;
            $total_qty = 0;

            // Get customer schemes for free goods calculation
            Log::info('Fetching Customer Schemes for Free Goods Calculation');
            $customer_schemes = DB::table('m_customers_t')
                ->where('customer_id', $posted_data['ship_to_customer_id'])
                ->select('schemes')
                ->first();
            $schemes = [];
            if ($customer_schemes && $customer_schemes->schemes) {
                $schemes = explode(',', $customer_schemes->schemes);
                Log::debug('Customer Schemes Found', ['scheme_count' => count($schemes)]);
            }

            foreach ($posted_data['so_lines'] as $key => $line_item) {
                Log::debug('Processing Line Item', ['line_no' => $key + 1, 'product_id' => $line_item['product_id'], 'qty' => $line_item['qty']]);
                // Support both 'amount' (old format) and 'line_subtotal' (new format)
                $line_amount = $line_item['amount'] ?? $line_item['line_subtotal'] ?? 0;
                $line_tax = $line_item['tax_amount'] ?? 0;
                $line_total = $line_amount + $line_tax;
                $total_amount += $line_amount;
                $total_tax += $line_tax;
                $total_qty += $line_item['qty'] ?? 0;
                Log::debug('Line Item Calculations', ['line_no' => $key + 1, 'amount' => $line_amount, 'tax' => $line_tax, 'line_total' => $line_total]);

                // Calculate free qty from schemes (Gift scheme evaluation)
                Log::info('Evaluating Schemes for Free Qty', ['product_id' => $line_item['product_id'], 'qty' => $line_item['qty']]);
                $free_qty = $this->calculateFreeQtyFromSchemes(
                    $line_item['product_id'],
                    $line_item['qty'],
                    $line_item['unit_price'],
                    $schemes
                );
                Log::debug('Free Qty Calculated', ['product_id' => $line_item['product_id'], 'free_qty' => $free_qty]);

                $line_data = [
                    'sales_hdr_id' => $sales_hdr_id,
                    'line_no' => $key + 1,
                    'product_id' => $line_item['product_id'],
                    'part_no' => $line_item['part_no'] ?? '',
                    'product_description' => $line_item['product_description'] ?? '',
                    'uom_code_id' => $line_item['uom_code_id'] ?? 1,
                    'qty' => $line_item['qty'],
                    'unit_price' => $line_item['unit_price'],
                    'delivery_date' => date('Y-m-d', strtotime($line_item['delivery_date'])),
                    'hsn_code' => $line_item['hsn_code'] ?? '',
                    'tax_group_id' => $line_item['tax_group_id'] ?? '',
                    'tax_amount' => $line_item['tax_amount'] ?? 0,
                    'tax_excemption' => $line_item['tax_excemption'] ?? 'No',
                    'discount_percentage' => $line_item['discount_percentage'] ?? 0,
                    'discount_amount' => $line_item['discount_amount'] ?? 0,
                    'pending_qty' => 0,
                    'line_total' => $line_total,
                    'free_qty' => $free_qty,
                    'comments' => $line_item['comments'] ?? '',
                    'location_id' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'last_updated_by' => $user_details->employee_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $user_details->employee_id,
                    'organization_id' => 1,
                    'company_id' => $user_details->company_id,
                ];

                DB::table('s_salesorder_lines_t')->insert($line_data);
                Log::info('Line Item Inserted Successfully', ['line_no' => $key + 1, 'sales_hdr_id' => $sales_hdr_id]);
            }
            Log::info('All Line Items Processed', ['total_amount' => $total_amount, 'total_tax' => $total_tax, 'total_qty' => $total_qty]);

            // Update order with calculated totals and assign approver from approval settings
            Log::info('Assigning Approver from Approval Settings and Setting Order Totals');
            $approver_json = $this->getApprovalDataFromSettings('soorder', $total_amount, $user_details->employee_id);
            Log::debug('Approver Array from Settings', ['approver_json' => $approver_json]);

            DB::table('s_salesorder_hdr_t')
                ->where('sales_hdr_id', $sales_hdr_id)
                ->update([
                    'order_total' => $total_amount,
                    'qty_total' => $total_qty,
                    'order_tax' => $total_tax,
                    'approver_id' => $approver_json
                ]);
            Log::info('Sales Order Updated with Approver', ['sales_hdr_id' => $sales_hdr_id, 'order_total' => $total_amount, 'qty_total' => $total_qty, 'order_tax' => $total_tax, 'approver_json' => $approver_json]);

            DB::commit();
            Log::info('Database Transaction Committed Successfully');
            Log::info('=== CREATE SALES ORDER COMPLETED SUCCESSFULLY ===', [
                'sales_hdr_id' => $sales_hdr_id,
                'sales_order_no' => $seqno['number'],
                'order_total' => $total_amount,
                'order_tax' => $total_tax,
                'qty_total' => $total_qty
            ]);

            return response()->json([
                'message' => 'Sales order created successfully',
                'success' => true,
                'sales_hdr_id' => (int)$sales_hdr_id,
                'sales_order_no' => $seqno['number'],
                'order_total' => (float)$total_amount,
                'order_tax' => (float)$total_tax,
                'qty_total' => (int)$total_qty,
                'order_status' => 'DRAFT',
                'approver_list' => json_decode($approver_json, true),  // Return array of approver IDs
                'requires_approval' => true,
                'created_at' => date('Y-m-d H:i:s')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== SALES ORDER CREATION FAILED ===');
            Log::error('Sales order creation error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Error creating sales order: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }

    /**
     * Get Product Data with Pricing and Tax
     * GET /api/sales/product/{productId}
     */
    public function getProductData(Request $request, $productId)
    {
        try {
            Log::info('=== GET PRODUCT DATA STARTED ===');
            $pricelist_id = $request->header('pricelistid');
            $customer_site_id = $request->header('customersiteid');
            $current_date = date('Y-m-d');
            Log::debug('Request Headers Received', ['product_id' => $productId, 'pricelist_id' => $pricelist_id, 'customer_site_id' => $customer_site_id]);

            if (!$pricelist_id) {
                Log::warning('Pricelist ID Missing from Request Headers');
                return response()->json([
                    'message' => 'Pricelist ID is required in header',
                    'success' => false
                ], 400);
            }

            // Debug: Log the query parameters
            Log::info('Get Product Data Request', [
                'product_id' => $productId,
                'pricelist_id' => $pricelist_id,
                'customer_site_id' => $customer_site_id,
                'current_date' => $current_date
            ]);

            // Get product pricing from pricelist (without date validation - all pricelists expired)
            Log::info('Fetching Product Pricing from Pricelist', ['product_id' => $productId, 'pricelist_id' => $pricelist_id]);
            $pricelist_tbl = DB::table('i_pricelist_lines_t')
                ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 'i_pricelist_lines_t.product_id')
                ->leftJoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'm_products_t.trx_uom_id')
                ->select(
                    'm_uom_codes_t.uom_code',
                    'm_products_t.*',
                    'i_pricelist_lines_t.unit_price',
                    'i_pricelist_lines_t.std_price'
                )
                ->where('i_pricelist_lines_t.pricelist_hdr_id', $pricelist_id)
                ->where('i_pricelist_lines_t.product_id', $productId)
                ->where('i_pricelist_lines_t.active', 'Yes')
                // NOTE: Removed end_date check - all pricelists in DB are expired but still in use
                ->orderBy('i_pricelist_lines_t.pricelist_line_id', 'desc')
                ->limit(1)
                ->get();

            if (count($pricelist_tbl) == 0) {
                Log::warning('Product Not Found in Pricelist', ['product_id' => $productId, 'pricelist_id' => $pricelist_id]);
                return response()->json([
                    'message' => 'Product not found in pricelist',
                    'success' => false
                ], 404);
            }

            $product = $pricelist_tbl[0];
            Log::debug('Product Found', ['product_id' => $product->product_id, 'unit_price' => $product->unit_price]);

            // Get tax details based on HSN code (primary: hsn_code, fallback: defalut_hsn_code)
            $hsn_code = $product->hsn_code ?? $product->defalut_hsn_code ?? '';
            Log::info('Fetching Tax Details', ['hsn_code' => $hsn_code, 'customer_site_id' => $customer_site_id]);

            $tax_details = $this->getTaxDetails(
                $hsn_code,
                $customer_site_id ?? 0,
                'Sales'
            );

            $response = [
                'success' => true,
                'pricelist_tbl' => [$product],  // Match old API response structure
                'message' => 'success',
                'tax_details' => $tax_details  // Return complete tax details array
            ];

            // Add tax group compatibility (for legacy clients)
            if ($tax_details && count($tax_details) > 0) {
                Log::debug('Tax Details Found', [
                    'tax_group_id' => $tax_details[0]['tax_group_id'],
                    'tax_percentage' => $tax_details[0]['tax_percentage'],
                    'tax_location_type' => $tax_details[0]['tax_location_type']
                ]);
                $taxgroup = DB::table('m_tax_group_t')
                    ->where('tax_group_id', $tax_details[0]['tax_group_id'])
                    ->select('tax_group_id', 'tax_group_name', 'display_name')
                    ->get();
                $response['taxgroup'] = $taxgroup;
            } else {
                Log::debug('No Tax Details Found for HSN Code', ['hsn_code' => $hsn_code]);
                $response['taxgroup'] = [];
            }

            Log::info('=== GET PRODUCT DATA COMPLETED SUCCESSFULLY ===', [
                'product_id' => $productId,
                'hsn_code' => $hsn_code,
                'tax_details_count' => count($tax_details)
            ]);
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('=== GET PRODUCT DATA FAILED ===');
            Log::error('Get product data error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Error retrieving product data',
                'success' => false
            ], 500);
        }
    }

    /**
     * Get Products List for Dropdown (by Pricelist)
     * GET /api/sales/pricelist/{pricelistId}/products
     * Perfect for dropdown selection
     */
    public function getProductsByPricelist($pricelistId)
    {
        try {
            Log::info('=== GET PRODUCTS BY PRICELIST STARTED ===', ['pricelist_id' => $pricelistId]);
            $current_date = date('Y-m-d');
            Log::debug('Query Parameters', ['pricelist_id' => $pricelistId, 'current_date' => $current_date]);

            // Get all products in this pricelist that are active and not expired
            // GROUP BY product_id to eliminate duplicate entries in pricelist (avoid React Native key duplication error)
            Log::info('Fetching Products from Pricelist');
            $products = DB::table('i_pricelist_lines_t')
                ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 'i_pricelist_lines_t.product_id')
                ->leftJoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'm_products_t.trx_uom_id')
                ->select(
                    'm_products_t.product_id',
                    'm_products_t.product_code',
                    'm_products_t.concatenated_product',
                    'm_uom_codes_t.uom_code',
                    'i_pricelist_lines_t.unit_price',
                    'i_pricelist_lines_t.std_price',
                    'm_products_t.defalut_hsn_code'
                )
                ->where('i_pricelist_lines_t.pricelist_hdr_id', $pricelistId)
                ->where('i_pricelist_lines_t.active', 'Yes')
                ->where('m_products_t.active', 'Yes')
                ->where('i_pricelist_lines_t.end_date', '>=', $current_date)
                ->groupBy('m_products_t.product_id')
                ->orderBy('m_products_t.concatenated_product', 'asc')
                ->get();

            if (!$products || count($products) === 0) {
                Log::warning('No Products Found in Pricelist', ['pricelist_id' => $pricelistId]);
                return response()->json([
                    'success' => true,
                    'message' => 'No products found in this pricelist',
                    'products' => []
                ]);
            }

            Log::info('=== GET PRODUCTS BY PRICELIST COMPLETED SUCCESSFULLY ===', ['product_count' => count($products)]);
            return response()->json([
                'success' => true,
                'message' => 'Products list retrieved successfully',
                'count' => count($products),
                'products' => $products
            ]);
        } catch (\Exception $e) {
            Log::error('=== GET PRODUCTS BY PRICELIST FAILED ===');
            Log::error('Get products by pricelist error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Error retrieving products',
                'success' => false
            ], 500);
        }
    }

    /**
     * Get Sample Products
     * GET /api/sales/sample-products
     * 
     * Fetches products from the sample pricelist (pricelist_hdr_id = 176)
     * for sample order creation. Uses the same logic as getProductsByPricelist
     * but with a fixed pricelist for sample distribution.
     */
    public function getSampleProducts()
    {
        try {
            Log::info('=== GET SAMPLE PRODUCTS STARTED ===');

            // Fixed pricelist PROMOTIONAL for sample order creation 
            $samplePricelistId = 176;
            $current_date = date('Y-m-d');
            Log::debug('Sample Products Query Parameters', ['pricelist_id' => $samplePricelistId, 'current_date' => $current_date]);

            // Get all products in the sample pricelist that are active and not expired
            // Using same logic as getProductsByPricelist
            // GROUP BY product_id to eliminate duplicate entries in pricelist (avoid React Native key duplication error)
            Log::info('Fetching Sample Products from Pricelist', ['pricelist_id' => $samplePricelistId]);
            $products = DB::table('i_pricelist_lines_t')
                ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 'i_pricelist_lines_t.product_id')
                ->leftJoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 'm_products_t.trx_uom_id')
                ->select(
                    'm_products_t.product_id',
                    'm_products_t.product_code',
                    'm_products_t.concatenated_product',
                    'm_uom_codes_t.uom_code',
                    'i_pricelist_lines_t.unit_price',
                    'i_pricelist_lines_t.std_price',
                    'm_products_t.defalut_hsn_code'
                )
                ->where('i_pricelist_lines_t.pricelist_hdr_id', $samplePricelistId)
                ->where('i_pricelist_lines_t.active', 'Yes')
                ->where('m_products_t.active', 'Yes')
                ->groupBy('m_products_t.product_id')
                ->orderBy('m_products_t.concatenated_product', 'asc')
                ->get();

            if (!$products || count($products) === 0) {
                Log::warning('No Sample Products Found in Pricelist', ['pricelist_id' => $samplePricelistId]);
                return response()->json([
                    'success' => true,
                    'message' => 'No sample products found in this pricelist',
                    'products' => []
                ]);
            }

            Log::info('=== GET SAMPLE PRODUCTS COMPLETED SUCCESSFULLY ===', ['product_count' => count($products), 'pricelist_id' => $samplePricelistId]);
            return response()->json([
                'success' => true,
                'message' => 'Sample products list retrieved successfully',
                'count' => count($products),
                'products' => $products
            ]);
        } catch (\Exception $e) {
            Log::error('=== GET SAMPLE PRODUCTS FAILED ===');
            Log::error('Get sample products error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Error retrieving sample products',
                'success' => false
            ], 500);
        }
    }

    /**
     * Get Customers List for Dropdown (Only customers mapped to employee)
     * GET /api/sales/customers
     * 
     * Returns only customers that are mapped/assigned to the logged-in employee
     * via distributor mapping tables (same logic as old app)
     * Selects only essential fields for performance
     */
    public function getCustomersList()
    {
        try {
            Log::info('=== GET CUSTOMERS LIST STARTED ===');
            // Get authenticated user and employee details
            // Log::info('Fetching Authenticated User Details');
            $user = Auth::user();
            // Log::debug('User ID', ['user_id' => $user->id]);
            $user_details = DB::table('tb_users')
                ->where('id', $user->id)
                ->first();
            // Log::debug('User Details Retrieved', ['employee_id' => $user_details->employee_id ?? null]);

            if (!$user_details) {
                Log::error('User Details Not Found', ['user_id' => $user->id]);
                return response()->json([
                    'message' => 'User not found',
                    'success' => false
                ], 401);
            }

            $emp_id = $user_details->employee_id;
            Log::info('🔍 STEP 1: Employee ID Retrieved', ['emp_id' => $emp_id, 'user_id' => $user->id]);

            // Check if distributor mapping tables exist
            Log::info('🔍 STEP 2: Checking for Distributor Mapping Tables');
            $mappingExists = DB::select("SHOW TABLES LIKE 'distributormapping_hdr_tbl'");
            Log::info('🔍 STEP 2 RESULT: Mapping Tables Check', [
                'tables_found' => count($mappingExists),
                'table_exists' => count($mappingExists) > 0 ? 'YES' : 'NO'
            ]);

            if (count($mappingExists) > 0) {
                // Use distributor mapping (only assigned customers)
                Log::info('🔍 STEP 3A: Using Distributor Mapping - Fetching Mapped Customers Only');

                // First check if mapping records exist for this employee
                $mapping_count = DB::table('distributormapping_hdr_tbl')
                    ->where('employee_id', $emp_id)
                    ->count();
                Log::info('🔍 STEP 3A.1: Mapping Records Check', [
                    'emp_id' => $emp_id,
                    'mapping_records_found' => $mapping_count
                ]);

                $customers = DB::table('m_customers_t')
                    ->join('distributormapping_lines_tbl', 'distributormapping_lines_tbl.disti_id', '=', 'm_customers_t.customer_id')
                    ->join('distributormapping_hdr_tbl', 'distributormapping_hdr_tbl.distributormapping_id', '=', 'distributormapping_lines_tbl.distributormapping_id')
                    ->leftJoin('i_pricelist_hdr_t', 'i_pricelist_hdr_t.pricelist_hdr_id', '=', 'm_customers_t.pricelist_id')
                    ->select(
                        'm_customers_t.customer_id',
                        'm_customers_t.customer_name',
                        'm_customers_t.customer_number',
                        'm_customers_t.pricelist_id',
                        'm_customers_t.schemes',
                        'm_customers_t.approver_id',
                        'm_customers_t.ar_discount_hdr_id',
                        'i_pricelist_hdr_t.pricelist_name'
                    )
                    ->where('distributormapping_hdr_tbl.employee_id', $emp_id)
                    ->groupBy('m_customers_t.customer_id')
                    ->orderBy('m_customers_t.customer_name', 'asc')
                    ->get();
                Log::info('🔍 STEP 3A.2: Mapped Customers Retrieved', [
                    'customer_count' => count($customers),
                    'customer_ids' => $customers->pluck('customer_id')->toArray()
                ]);
            } else {
                // Fallback: Get all active customers (no mapping tables)
                Log::info('🔍 STEP 3B: Distributor Mapping Not Found - Fetching All Active Customers (Fallback)');
                $customers = DB::table('m_customers_t')
                    ->leftJoin('i_pricelist_hdr_t', 'i_pricelist_hdr_t.pricelist_hdr_id', '=', 'm_customers_t.pricelist_id')
                    ->select(
                        'm_customers_t.customer_id',
                        'm_customers_t.customer_name',
                        'm_customers_t.customer_number',
                        'm_customers_t.pricelist_id',
                        'm_customers_t.schemes',
                        'm_customers_t.approver_id',
                        'm_customers_t.ar_discount_hdr_id',
                        'i_pricelist_hdr_t.pricelist_name'
                    )
                    ->where('m_customers_t.active', 'Yes')
                    ->orderBy('m_customers_t.customer_name', 'asc')
                    ->get();
                Log::info('🔍 STEP 3B.1: Active Customers Retrieved (Fallback)', [
                    'customer_count' => count($customers),
                    'customer_ids' => $customers->pluck('customer_id')->toArray()
                ]);
            }

            if (!$customers || count($customers) === 0) {
                Log::warning('⚠️ STEP 4: NO CUSTOMERS FOUND', [
                    'emp_id' => $emp_id,
                    'mapping_table_exists' => count($mappingExists) > 0,
                    'reason' => 'Employee has no customer mappings in distributor tables'
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'No customers assigned to you',
                    'customerlist' => []
                ]);
            }

            Log::info('=== GET CUSTOMERS LIST COMPLETED SUCCESSFULLY ===', ['customer_count' => count($customers)]);

            return response()->json([
                'success' => true,
                'message' => 'Customer List',
                'customerlist' => $customers
            ]);
        } catch (\Exception $e) {
            Log::error('=== GET CUSTOMERS LIST FAILED ===');
            Log::error('Get customers list error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Error retrieving customers list',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Customer Data for Sales Order Creation
     * GET /api/sales/customer/{customerId}
     */
    public function getCustomerData($customerId)
    {
        try {
            Log::info('=== GET CUSTOMER DATA STARTED ===', ['customer_id' => $customerId]);
            Log::debug('Fetching Customer Details', ['customer_id' => $customerId]);
            $customer = DB::table('m_customers_t')
                ->leftJoin('hr_employee_t', 'm_customers_t.sales_person', '=', 'hr_employee_t.employee_id')
                ->leftJoin('m_customer_sites_t', function ($join) {
                    $join->on('m_customer_sites_t.customer_id', '=', 'm_customers_t.customer_id')
                        ->where('m_customer_sites_t.site_type', '=', 'BILL_TO')
                        ->where('m_customer_sites_t.active', '=', 'Yes')
                        ->where('m_customer_sites_t.primary_address', '=', 'YES');
                })
                ->leftJoin('m_cities_t', 'm_customer_sites_t.city', '=', 'm_cities_t.city_id')
                ->leftJoin('m_discounts_hdr_t', 'm_customers_t.ar_discount_hdr_id', '=', 'm_discounts_hdr_t.ar_discount_hdr_id')
                ->leftJoin('m_states_t', 'm_customer_sites_t.state', '=', 'm_states_t.state_id')
                ->leftJoin('m_countries_t', 'm_customer_sites_t.country', '=', 'm_countries_t.country_id')
                ->leftJoin('i_pricelist_hdr_t', 'i_pricelist_hdr_t.pricelist_hdr_id', '=', 'm_customers_t.pricelist_id')
                ->leftJoin('m_payment_methods_t', 'm_customers_t.default_payment_method_id', '=', 'm_payment_methods_t.payment_method_id')
                ->leftJoin('m_payment_terms_t', 'm_customers_t.default_payment_terms_id', '=', 'm_payment_terms_t.payment_term_id')
                ->select(
                    'hr_employee_t.employee_number',
                    'hr_employee_t.first_name',
                    'm_payment_terms_t.payment_term_id',
                    'm_payment_terms_t.payment_term_name',
                    'm_payment_methods_t.payment_method_name',
                    'm_payment_methods_t.payment_method_id',
                    'm_customers_t.*',
                    'i_pricelist_hdr_t.pricelist_name',
                    'm_customer_sites_t.customer_site_id',
                    'm_customer_sites_t.city as city_id',
                    'm_customer_sites_t.state as state_id',
                    'm_customer_sites_t.country as country_id',
                    'm_customer_sites_t.address',
                    'm_customer_sites_t.customer_site_name',
                    'm_cities_t.city_name',
                    'm_states_t.state_name',
                    'm_countries_t.country_name',
                    'm_customer_sites_t.pincode'
                )
                ->selectRaw('COALESCE(m_discounts_hdr_t.default_discount_amount, 0) as discount_amount')
                ->where('m_customers_t.customer_id', $customerId)
                ->first();

            if (!$customer) {
                Log::warning('Customer Not Found', ['customer_id' => $customerId]);
                return response()->json([
                    'message' => 'Customer not found',
                    'success' => false
                ], 404);
            }
            Log::debug('Customer Found', ['customer_name' => $customer->customer_name, 'pricelist_id' => $customer->pricelist_id]);

            // Get product list for this customer's pricelist
            // Use pricelist_lines_t as source of truth - filter only by: pricelist, active status, and product group
            // Exclude SAMPLES/COMPRESSION but ALLOW products with NULL subcategory mapping
            Log::info('Fetching Customer Products from Pricelist', ['pricelist_id' => $customer->pricelist_id]);
            $products = DB::table('i_pricelist_lines_t')
                ->join('m_products_t', 'm_products_t.product_id', '=', 'i_pricelist_lines_t.product_id')
                ->leftJoin('m_product_category_t', 'm_product_category_t.product_category_id', '=', 'm_products_t.product_category_id')
                ->leftJoin('m_product_subcategory_t', 'm_product_subcategory_t.product_subcategory_id', '=', 'm_products_t.product_subcategory_id')
                ->leftJoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')
                ->select(
                    'i_pricelist_lines_t.product_id',
                    'm_products_t.product_code',
                    'm_products_t.concatenated_product',
                    'm_products_t.product_group_id',
                    'm_product_groups_t.group_name',
                    'm_products_t.product_category_id',
                    'm_product_category_t.category_name',
                    'm_products_t.product_subcategory_id',
                    'm_product_subcategory_t.subcategory_name'
                )
                ->where('i_pricelist_lines_t.pricelist_hdr_id', $customer->pricelist_id)
                ->where('i_pricelist_lines_t.active', 'Yes')
                ->where('m_products_t.active', 'Yes')
                ->where('m_products_t.product_group_id', 1)  // IMPORTANT: Filter by product group = 1 (FINISHED GOODS)
                // Use raw SQL for explicit NULL handling - simpler and more reliable
                ->whereRaw("(m_product_subcategory_t.subcategory_name IS NULL OR m_product_subcategory_t.subcategory_name NOT IN ('SAMPLES', 'COMPRESSION'))")
                ->groupBy(
                    'i_pricelist_lines_t.product_id',
                    'm_products_t.product_code',
                    'm_products_t.concatenated_product',
                    'm_products_t.product_group_id',
                    'm_product_groups_t.group_name',
                    'm_products_t.product_category_id',
                    'm_product_category_t.category_name',
                    'm_products_t.product_subcategory_id',
                    'm_product_subcategory_t.subcategory_name'
                )
                ->orderBy('m_products_t.concatenated_product', 'asc')
                ->get();

            // Debug logging for product 4356
            $product4356Check = DB::table('i_pricelist_lines_t')
                ->where('pricelist_hdr_id', $customer->pricelist_id)
                ->where('product_id', 4356)
                ->where('active', 'Yes')
                ->count();
            Log::info('Product 4356 Debug Check', [
                'in_pricelist' => $product4356Check,
                'in_result' => $products->contains('product_id', 4356),
                'total_products' => count($products)
            ]);

            // Get currencies
            Log::info('Fetching Currencies');
            $currencies = DB::table('f_account_currency_t')
                ->select('account_currency_id', 'currency_code')
                ->get();
            Log::debug('Data Retrieved', ['product_count' => count($products), 'currency_count' => count($currencies)]);

            Log::info('=== GET CUSTOMER DATA COMPLETED SUCCESSFULLY ===', ['customer_id' => $customerId]);
            return response()->json([
                'success' => true,
                'customer' => $customer,
                'products' => $products,
                'currencies' => $currencies
            ]);
        } catch (\Exception $e) {
            Log::error('=== GET CUSTOMER DATA FAILED ===');
            Log::error('Get customer data error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Error retrieving customer data',
                'success' => false
            ], 500);
        }
    }

    /**
     * Approve Sales Order
     * POST /api/sales/approve
     */
    public function approveSalesOrder(Request $request)
    {
        try {
            Log::info('=== APPROVE SALES ORDER STARTED ===');
            $posted_data = $request->json()->all();
            Log::debug('Request Data Received', ['sales_hdr_id' => $posted_data['sales_hdr_id'] ?? null, 'action' => $posted_data['action'] ?? 'approve']);

            $user = Auth::user();
            $user_details = DB::table('tb_users')
                ->where('id', $user->id)
                ->first();
            Log::debug('User Retrieved', ['employee_id' => $user_details->employee_id]);

            $sales_hdr_id = $posted_data['sales_hdr_id'] ?? null;
            $action = $posted_data['action'] ?? 'approve'; // approve or reject
            Log::debug('Action Parameters', ['sales_hdr_id' => $sales_hdr_id, 'action' => $action]);

            if (!$sales_hdr_id) {
                Log::warning('Sales Order ID Missing from Request');
                return response()->json([
                    'message' => 'Sales order ID is required',
                    'success' => false
                ], 400);
            }

            // Get current sales order
            Log::info('Fetching Current Sales Order', ['sales_hdr_id' => $sales_hdr_id]);
            $sales_order = DB::table('s_salesorder_hdr_t')
                ->where('sales_hdr_id', $sales_hdr_id)
                ->first();

            if (!$sales_order) {
                Log::warning('Sales Order Not Found', ['sales_hdr_id' => $sales_hdr_id]);
                return response()->json([
                    'message' => 'Sales order not found',
                    'success' => false
                ], 404);
            }

            // Verify order is in DRAFT status (can only approve DRAFT orders)
            if ($sales_order->order_status_id !== 'DRAFT') {
                Log::warning('Cannot Approve - Order Not in DRAFT Status', ['current_status' => $sales_order->order_status_id]);
                return response()->json([
                    'message' => 'Order must be in DRAFT status to submit for approval. Current status: ' . $sales_order->order_status_id,
                    'success' => false
                ], 400);
            }

            // Update status based on action
            Log::info('Preparing Update Data', ['current_status' => $sales_order->order_status_id, 'new_action' => $action]);
            $update_data = [
                'last_updated_by' => $user_details->employee_id,
                'updated_at' => date('Y-m-d H:i:s'),
                'remarks' => $posted_data['remarks'] ?? ''
            ];

            // Maintain / append approver history in app_approver_id (stored as JSON array in DB)
            try {
                $existing_app_approvers = $sales_order->app_approver_id ?? '';
                $app_approvers = [];

                if (!empty($existing_app_approvers)) {
                    $decoded = json_decode($existing_app_approvers, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $app_approvers = $decoded;
                    } else {
                        // handle comma separated or plain integer values
                        $trimmed = trim($existing_app_approvers, "[] \n\r\t");
                        if ($trimmed !== '') {
                            $parts = preg_split('/\s*,\s*/', $trimmed);
                            foreach ($parts as $p) {
                                $p = trim($p);
                                if ($p !== '') {
                                    $app_approvers[] = is_numeric($p) ? (int)$p : $p;
                                }
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Failed to parse existing app_approver_id, starting fresh', ['error' => $e->getMessage()]);
                $app_approvers = [];
            }

            if ($action === 'reject') {
                Log::info('Setting Order Status to REJECTED');
                $update_data['order_status_id'] = 'REJECTED';

                // Append current user to approver history for tracking
                if (!in_array($user_details->employee_id, $app_approvers, true)) {
                    $app_approvers[] = $user_details->employee_id;
                }
                $update_data['app_approver_id'] = json_encode(array_values($app_approvers));
            } else {
                // Approve action - determine final status based on order amount
                Log::info('Processing Order Approval - Checking Order Amount', ['order_total' => $sales_order->order_total]);
                $approver_id = $this->checkApprovalNeeded('soorder', $sales_order->order_total, $user_details->employee_id);
                Log::debug('Approval Decision', ['approver_id' => $approver_id, 'requires_approval' => $approver_id !== '0']);

                if ($approver_id === '0') {
                    // Auto-approve for small orders
                    Log::info('Order Auto-INITIATED - Amount Below Threshold (< 50,000)', ['order_total' => $sales_order->order_total]);
                    $update_data['order_status_id'] = 'INITIATED';
                    $update_data['approve_status'] = 'INITIATED';
                    $update_data['last_updated_by'] = $user_details->employee_id;

                    // Append current user to approver history for tracking
                    if (!in_array($user_details->employee_id, $app_approvers, true)) {
                        $app_approvers[] = $user_details->employee_id;
                    }
                    $update_data['app_approver_id'] = json_encode(array_values($app_approvers));
                } else {
                    // Large orders need manager approval
                    Log::info('Order Requires Manager Approval - Amount >= 50,000', ['order_total' => $sales_order->order_total, 'approver_id' => $approver_id]);
                    $update_data['order_status_id'] = 'INITIATED';
                    $update_data['order_status'] = 'INITIATED';
                    $update_data['last_updated_by'] = $user_details->employee_id;

                    // For manager approval flow also record who submitted for approval (current user)
                    if (!in_array($user_details->employee_id, $app_approvers, true)) {
                        $app_approvers[] = $user_details->employee_id;
                    }
                    $update_data['app_approver_id'] = json_encode(array_values($app_approvers));
                }
            }

            Log::info('Updating Sales Order in Database');
            DB::table('s_salesorder_hdr_t')
                ->where('sales_hdr_id', $sales_hdr_id)
                ->update($update_data);
            Log::info('Sales Order Updated Successfully', ['new_status' => $update_data['order_status_id']]);

            Log::info('=== APPROVE SALES ORDER COMPLETED SUCCESSFULLY ===', ['sales_hdr_id' => $sales_hdr_id, 'action' => $action]);

            return response()->json([
                'message' => 'Sales order ' . ($action === 'reject' ? 'rejected' : 'approved') . ' successfully',
                'success' => true,
                'sales_hdr_id' => $sales_hdr_id,
                'action' => $action,
                'status' => $update_data['order_status_id']
            ]);
        } catch (\Exception $e) {
            Log::error('=== APPROVE SALES ORDER FAILED ===');
            Log::error('Approve sales order error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Error processing sales order approval',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Sales Orders List
     * GET /api/sales/orders
     */
    public function getSalesOrdersList(Request $request)
    {
        try {
            Log::info('=== GET SALES ORDERS LIST STARTED ===');
            $user = Auth::user();
            $user_details = DB::table('tb_users')
                ->where('id', $user->id)
                ->first();
            Log::debug('User Retrieved', ['employee_id' => $user_details->employee_id]);

            $status = $request->input('status');
            $per_page = $request->input('per_page', 10);
            Log::debug('Query Parameters', ['status' => $status, 'per_page' => $per_page]);

            $query = DB::table('s_salesorder_hdr_t')
                ->leftJoin('m_customers_t', 'm_customers_t.customer_id', '=', 's_salesorder_hdr_t.ship_to_customer_id')
                ->leftJoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 's_salesorder_hdr_t.created_by')
                ->select(
                    's_salesorder_hdr_t.sales_hdr_id',
                    's_salesorder_hdr_t.sales_order_no',
                    's_salesorder_hdr_t.sales_order_date',
                    's_salesorder_hdr_t.order_status_id as order_status',
                    's_salesorder_hdr_t.order_type_id as order_type',
                    's_salesorder_hdr_t.order_total',
                    's_salesorder_hdr_t.order_tax',
                    's_salesorder_hdr_t.order_sub_total',
                    'm_customers_t.customer_name',
                    's_salesorder_hdr_t.created_by',
                    's_salesorder_hdr_t.created_at',
                    DB::raw("CONCAT(hr_employee_t.first_name, ' ', COALESCE(hr_employee_t.last_name, '')) as created_by_name")
                )
                ->where('s_salesorder_hdr_t.created_by', $user_details->employee_id);

            if ($status) {
                Log::info('Filtering by Status', ['status' => $status]);
                $query->where('s_salesorder_hdr_t.order_status_id', $status);
            }

            Log::info('Fetching Sales Orders');
            $orders = $query->orderBy('s_salesorder_hdr_t.created_at', 'desc')
                ->paginate($per_page);
            Log::info('=== GET SALES ORDERS LIST COMPLETED ===', ['count' => count($orders->items()), 'total' => $orders->total()]);

            return response()->json([
                'success' => true,
                'orders' => $orders->items(),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                    'last_page' => $orders->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('=== GET SALES ORDERS FAILED ===');
            Log::error('Get sales orders error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Error retrieving sales orders',
                'success' => false
            ], 500);
        }
    }

    /**
     * Get Pending Sales Orders (INITIATED, DRAFT, PENDING status) - For Approval
     * GET /api/sales/orders/pending
     * 
     * Returns pending orders with complete product line details including:
     * - Product details (code, name)
     * - Quantity and unit price
     * - Discount (percentage and amount)
     * - Tax amount
     * - Free quantity
     * - Line total
     */
    public function getPendingSalesOrders(Request $request)
    {
        try {
            $user = Auth::user();
            $user_details = DB::table('tb_users')
                ->where('id', $user->id)
                ->first();

            $per_page = $request->input('per_page', 100);
            $emp_id = $user_details->employee_id;

            // Get orders that are waiting for approval by this user
            // Note: approver_id can be stored as integer (new format) or JSON array (old format)
            $orders_query = DB::table('s_salesorder_hdr_t')
                ->leftJoin('m_customers_t', 'm_customers_t.customer_id', '=', 's_salesorder_hdr_t.ship_to_customer_id')
                ->leftJoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 's_salesorder_hdr_t.created_by')
                ->select(
                    's_salesorder_hdr_t.sales_hdr_id',
                    's_salesorder_hdr_t.sales_order_no',
                    's_salesorder_hdr_t.sales_order_date',
                    's_salesorder_hdr_t.delivery_date',
                    's_salesorder_hdr_t.order_status_id as order_status',
                    's_salesorder_hdr_t.order_type_id as order_type',
                    's_salesorder_hdr_t.order_total',
                    's_salesorder_hdr_t.order_tax',
                    's_salesorder_hdr_t.order_sub_total',
                    's_salesorder_hdr_t.qty_total',
                    's_salesorder_hdr_t.approver_id',
                    'm_customers_t.customer_id',
                    'm_customers_t.customer_name',
                    'm_customers_t.customer_number',
                    's_salesorder_hdr_t.created_by',
                    's_salesorder_hdr_t.created_at',
                    DB::raw("CONCAT(hr_employee_t.first_name, ' ', COALESCE(hr_employee_t.last_name, '')) as created_by_name")
                )
                ->whereIn('s_salesorder_hdr_t.order_status_id', ['INITIATED', 'DRAFT', 'PENDING'])
                ->where(function ($query) use ($emp_id) {
                    // Check if employee_id is in the approver_id JSON array
                    // Using CONCAT to add comma boundaries for exact match in array
                    $query->where(DB::raw("CONCAT(',', REPLACE(REPLACE(s_salesorder_hdr_t.approver_id, '[', ''), ']', ''), ',')"), 'LIKE', "%,$emp_id,%")
                        ->orWhere('s_salesorder_hdr_t.approver_id', '=', "[$emp_id]");
                })
                // Exclude orders where the order creator is also listed as an approver (created_by inside approver_id)
                // Make check NULL/empty-safe so orders with no approver_id are not excluded accidentally
                ->whereRaw("NOT ( (s_salesorder_hdr_t.approver_id IS NOT NULL AND s_salesorder_hdr_t.approver_id <> '' AND (
                    s_salesorder_hdr_t.approver_id = s_salesorder_hdr_t.created_by OR 
                    s_salesorder_hdr_t.approver_id = CONCAT('[', s_salesorder_hdr_t.created_by, ']') OR 
                    CONCAT(',', REPLACE(REPLACE(s_salesorder_hdr_t.approver_id, '[', ''), ']', ''), ',') LIKE CONCAT('%,', s_salesorder_hdr_t.created_by, ',%')
                ) ) )")
                ->orderBy('s_salesorder_hdr_t.created_at', 'desc');

            $orders = $orders_query->paginate($per_page);

            // Get product line items for each order
            $orders_with_lines = [];
            foreach ($orders->items() as $order) {
                $lines = DB::table('s_salesorder_lines_t')
                    ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 's_salesorder_lines_t.product_id')
                    ->leftJoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 's_salesorder_lines_t.uom_code_id')
                    ->select(
                        's_salesorder_lines_t.line_no as line_number',
                        'm_products_t.product_id',
                        'm_products_t.product_code',
                        'm_products_t.concatenated_product as product_name',
                        's_salesorder_lines_t.qty as quantity',
                        'm_uom_codes_t.uom_code',
                        's_salesorder_lines_t.unit_price',
                        's_salesorder_lines_t.discount_percentage',
                        's_salesorder_lines_t.discount_amount',
                        's_salesorder_lines_t.tax_amount',
                        's_salesorder_lines_t.free_qty',
                        's_salesorder_lines_t.line_total as total_amount'
                    )
                    ->where('s_salesorder_lines_t.sales_hdr_id', $order->sales_hdr_id)
                    ->orderBy('s_salesorder_lines_t.line_no', 'asc')
                    ->get();

                $order_array = (array) $order;
                $order_array['order_lines'] = $lines;
                $orders_with_lines[] = $order_array;
            }

            return response()->json([
                'success' => true,
                'message' => 'Pending sales orders for approval with product details',
                'orders' => $orders_with_lines,
                'count' => $orders->total(),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                    'last_page' => $orders->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get pending sales orders error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Error retrieving pending sales orders',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Approved Sales Orders
     * GET /api/sales/orders/approved
     */
    public function getApprovedSalesOrders(Request $request)
    {
        try {
            $user = Auth::user();
            $user_details = DB::table('tb_users')
                ->where('id', $user->id)
                ->first();

            $per_page = $request->input('per_page', 50);
            $from_date = $request->input('from_date');
            $to_date = $request->input('to_date');

            $query = DB::table('s_salesorder_hdr_t')
                ->leftJoin('m_customers_t', 'm_customers_t.customer_id', '=', 's_salesorder_hdr_t.ship_to_customer_id')
                ->leftJoin('hr_employee_t as creator', 'creator.employee_id', '=', 's_salesorder_hdr_t.created_by')
                ->select(
                    's_salesorder_hdr_t.sales_hdr_id',
                    's_salesorder_hdr_t.sales_order_no',
                    's_salesorder_hdr_t.sales_order_date',
                    's_salesorder_hdr_t.delivery_date',
                    's_salesorder_hdr_t.order_status_id as order_status',
                    's_salesorder_hdr_t.order_total',
                    's_salesorder_hdr_t.order_tax',
                    's_salesorder_hdr_t.order_sub_total',
                    's_salesorder_hdr_t.approve_status',
                    'm_customers_t.customer_name',
                    'm_customers_t.customer_number',
                    's_salesorder_hdr_t.created_by',
                    's_salesorder_hdr_t.updated_at',
                    's_salesorder_hdr_t.created_at',
                    DB::raw("CONCAT(creator.first_name, ' ', COALESCE(creator.last_name, '')) as created_by_name")
                )
                ->whereIn('s_salesorder_hdr_t.order_status_id', ['APPROVED', 'COMPLETED']);

            // Filter by employee's created orders
            $query->where('s_salesorder_hdr_t.created_by', $user_details->employee_id);

            // Date range filter
            if ($from_date) {
                $query->where('s_salesorder_hdr_t.sales_order_date', '>=', $from_date);
            }
            if ($to_date) {
                $query->where('s_salesorder_hdr_t.sales_order_date', '<=', $to_date);
            }

            $orders = $query->orderBy('s_salesorder_hdr_t.updated_at', 'desc')
                ->paginate($per_page);

            return response()->json([
                'success' => true,
                'message' => 'Approved/Completed sales orders',
                'orders' => $orders->items(),
                'count' => $orders->total(),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total(),
                    'last_page' => $orders->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get approved sales orders error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error retrieving approved sales orders',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllSalesOrders_backup(Request $request)
    {
        try {
            Log::info('=== GET ALL SALES ORDERS STARTED (NO CONDITIONS) ===');

            // Get authenticated user
            $user = Auth::user();
            $user_details = DB::table('tb_users')
                ->where('id', $user->id)
                ->first();

            if (!$user_details) {
                Log::error('User not found', ['user_id' => $user->id]);
                return response()->json([
                    'message' => 'User not found',
                    'success' => false
                ], 401);
            }

            Log::info('🔍 STEP 1: User Authenticated', ['user_id' => $user->id, 'emp_id' => $user_details->employee_id]);

            // Input parameters
            $per_page = min($request->input('per_page', 50), 500); // Max 500 per page for large datasets
            $page = $request->input('page', 1);

            Log::info('🔍 STEP 2: Pagination Parameters', ['per_page' => $per_page, 'page' => $page]);

            // STEP 3: Build query WITHOUT ANY WHERE CONDITIONS - GET ALL ORDERS
            Log::info('🔍 STEP 3: Building Query - NO CONDITIONS, GET ALL ORDERS');

            $query = DB::table('s_salesorder_hdr_t')
                ->leftJoin('m_customers_t', 'm_customers_t.customer_id', '=', 's_salesorder_hdr_t.ship_to_customer_id')
                ->leftJoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 's_salesorder_hdr_t.created_by');

            Log::info('🔍 STEP 4: Selecting Order Header Fields');

            // Select essential fields from order header
            $query->select(
                's_salesorder_hdr_t.sales_hdr_id',
                's_salesorder_hdr_t.sales_order_no as order_number',
                's_salesorder_hdr_t.sales_order_date as order_date',
                's_salesorder_hdr_t.delivery_date',
                's_salesorder_hdr_t.order_status_id as order_status',
                's_salesorder_hdr_t.order_total',
                's_salesorder_hdr_t.order_sub_total',
                's_salesorder_hdr_t.order_tax',
                's_salesorder_hdr_t.qty_total as order_quantity',
                's_salesorder_hdr_t.created_by',
                's_salesorder_hdr_t.created_at',
                'm_customers_t.customer_id',
                'm_customers_t.customer_name',
                DB::raw("CONCAT(COALESCE(hr_employee_t.first_name, ''), ' ', COALESCE(hr_employee_t.last_name, '')) as created_by_name")
            );

            // Group by sales_hdr_id to avoid duplicate rows from joins
            $query->groupBy('s_salesorder_hdr_t.sales_hdr_id');

            Log::info('🔍 STEP 5: Executing Query - Fetching Paginated Results', ['per_page' => $per_page, 'page' => $page]);

            // Execute paginated query
            $orders = $query->orderBy('s_salesorder_hdr_t.created_at', 'desc')
                ->paginate($per_page, ['*'], 'page', $page);

            $total_count = $orders->total();
            Log::info('🔍 STEP 6: Orders Retrieved', [
                'records_returned' => count($orders->items()),
                'total_available' => $total_count,
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage()
            ]);

            // STEP 7: Get Order Line Items for each order
            Log::info('🔍 STEP 7: Fetching Order Line Items');

            $orders_with_lines = [];
            foreach ($orders->items() as $order) {
                Log::debug('  Fetching lines for order', ['sales_hdr_id' => $order->sales_hdr_id, 'order_number' => $order->order_number]);

                $lines = DB::table('s_salesorder_lines_t')
                    ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 's_salesorder_lines_t.product_id')
                    ->leftJoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 's_salesorder_lines_t.uom_code_id')
                    ->select(
                        's_salesorder_lines_t.line_no',
                        'm_products_t.product_id',
                        'm_products_t.product_code',
                        'm_products_t.concatenated_product as product_name',
                        's_salesorder_lines_t.qty as quantity',
                        'm_uom_codes_t.uom_code',
                        's_salesorder_lines_t.unit_price',
                        's_salesorder_lines_t.discount_percentage',
                        's_salesorder_lines_t.discount_amount',
                        's_salesorder_lines_t.tax_amount',
                        's_salesorder_lines_t.free_qty',
                        's_salesorder_lines_t.line_total'
                    )
                    ->where('s_salesorder_lines_t.sales_hdr_id', $order->sales_hdr_id)
                    ->orderBy('s_salesorder_lines_t.line_no', 'asc')
                    ->get();

                $order_array = (array) $order;
                $order_array['order_lines'] = $lines;
                $order_array['line_count'] = count($lines);
                $orders_with_lines[] = $order_array;
            }

            Log::info('🔍 STEP 8: Order Line Items Fetched', ['total_orders_with_lines' => count($orders_with_lines)]);

            // STEP 9: Calculate Status Summary
            Log::info('🔍 STEP 9: Calculating Status Summary');

            $status_counts = DB::table('s_salesorder_hdr_t')
                ->select('order_status_id', DB::raw('COUNT(*) as count'))
                ->groupBy('order_status_id')
                ->get();

            $summary = [
                'total' => $total_count,
                'initiated' => 0,
                'approved' => 0,
                'rejected' => 0,
                'draft' => 0,
                'completed' => 0,
                'pending' => 0
            ];

            foreach ($status_counts as $status) {
                $status_key = strtolower($status->order_status_id);
                if (isset($summary[$status_key])) {
                    $summary[$status_key] = (int)$status->count;
                }
            }

            Log::info('🔍 STEP 10: Status Summary Calculated', $summary);

            Log::info('=== GET ALL SALES ORDERS COMPLETED SUCCESSFULLY ===', [
                'total_records' => $total_count,
                'current_page' => $orders->currentPage(),
                'records_on_page' => count($orders_with_lines),
                'has_more' => $orders->hasMorePages()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'All sales orders retrieved successfully (without conditions)',
                'orders' => $orders_with_lines,
                'summary' => $summary,
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $total_count,
                    'last_page' => $orders->lastPage(),
                    'has_more' => $orders->hasMorePages()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('=== GET ALL SALES ORDERS FAILED ===');
            Log::error('Get all sales orders error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Error retrieving sales orders',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllSalesOrders(Request $request)
    {
        try {
            Log::info('=== GET ALL SALES ORDERS STARTED (WITH DISTRIBUTOR MAPPING) ===');

            // Get authenticated user
            $user = Auth::user();
            $user_details = DB::table('tb_users')
                ->where('id', $user->id)
                ->first();

            if (!$user_details) {
                Log::error('User not found', ['user_id' => $user->id]);
                return response()->json([
                    'message' => 'User not found',
                    'success' => false
                ], 401);
            }

            Log::info('🔍 STEP 1: User Authenticated', ['user_id' => $user->id, 'emp_id' => $user_details->employee_id]);

            // Input parameters
            $per_page = min($request->input('per_page', 50), 500); // Max 500 per page for large datasets
            $page = $request->input('page', 1);

            Log::info('🔍 STEP 2: Pagination Parameters', ['per_page' => $per_page, 'page' => $page]);

            // STEP 3: Check if distributor mapping exists and filter orders accordingly
            Log::info('🔍 STEP 3: Checking for Distributor Mapping Tables');
            $mappingExists = DB::select("SHOW TABLES LIKE 'distributormapping_hdr_tbl'");
            Log::info('🔍 STEP 3 RESULT: Mapping Tables Check', [
                'tables_found' => count($mappingExists),
                'table_exists' => count($mappingExists) > 0 ? 'YES' : 'NO'
            ]);

            // STEP 4: Build query with distributor mapping filter
            Log::info('🔍 STEP 4: Building Query with Distributor Mapping Filter');

            $query = DB::table('s_salesorder_hdr_t')
                ->leftJoin('m_customers_t', 'm_customers_t.customer_id', '=', 's_salesorder_hdr_t.ship_to_customer_id')
                ->leftJoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 's_salesorder_hdr_t.created_by');

            // Apply distributor mapping filter if tables exist
            // if (count($mappingExists) > 0) {
            //     Log::info('🔍 STEP 4A: Applying Distributor Mapping Filter', ['emp_id' => $user_details->employee_id]);

            //     // Join with distributor mapping tables to filter only mapped customers
            //     $query->join('distributormapping_lines_tbl', 'distributormapping_lines_tbl.disti_id', '=', 's_salesorder_hdr_t.ship_to_customer_id')
            //         ->join('distributormapping_hdr_tbl', 'distributormapping_hdr_tbl.distributormapping_id', '=', 'distributormapping_lines_tbl.distributormapping_id')
            //         ->where('distributormapping_hdr_tbl.employee_id', $user_details->employee_id);

            //     Log::info('🔍 STEP 4A RESULT: Distributor Mapping Filter Applied - Only showing orders from mapped customers');
            // } else {
            //     Log::info('🔍 STEP 4B: No Distributor Mapping - Showing All Orders (Fallback)');
            // }
            // Apply distributor mapping filter if tables exist
            if (count($mappingExists) > 0) {
                Log::info('🔍 STEP 4A: Applying Distributor Mapping Filter', ['emp_id' => $user_details->employee_id]);

                // Join with distributor mapping tables to filter only mapped customers
                // Also include orders created by the current employee so they see their own orders even if not mapped
                $query->leftJoin('distributormapping_lines_tbl', 'distributormapping_lines_tbl.disti_id', '=', 's_salesorder_hdr_t.ship_to_customer_id')
                    ->leftJoin('distributormapping_hdr_tbl', 'distributormapping_hdr_tbl.distributormapping_id', '=', 'distributormapping_lines_tbl.distributormapping_id')
                    ->where(function ($q) use ($user_details) {
                        $q->where('distributormapping_hdr_tbl.employee_id', $user_details->employee_id)
                            ->orWhere('s_salesorder_hdr_t.created_by', $user_details->employee_id);
                    });

                Log::info('🔍 STEP 4A RESULT: Distributor Mapping Filter Applied - Showing mapped customers OR orders created by the employee');
            } else {
                Log::info('🔍 STEP 4B: No Distributor Mapping - Showing All Orders (Fallback)');
            }

            Log::info('🔍 STEP 5: Selecting Order Header Fields');

            // Select essential fields from order header
            $query->select(
                's_salesorder_hdr_t.sales_hdr_id',
                's_salesorder_hdr_t.sales_order_no as order_number',
                's_salesorder_hdr_t.sales_order_date as order_date',
                's_salesorder_hdr_t.delivery_date',
                's_salesorder_hdr_t.order_status_id as order_status',
                's_salesorder_hdr_t.order_total',
                's_salesorder_hdr_t.order_sub_total',
                's_salesorder_hdr_t.order_tax',
                's_salesorder_hdr_t.qty_total as order_quantity',
                's_salesorder_hdr_t.created_by',
                's_salesorder_hdr_t.created_at',
                'm_customers_t.customer_id',
                'm_customers_t.customer_name',
                DB::raw("CONCAT(COALESCE(hr_employee_t.first_name, ''), ' ', COALESCE(hr_employee_t.last_name, '')) as created_by_name")
            );

            // Group by sales_hdr_id to avoid duplicate rows from joins
            $query->groupBy('s_salesorder_hdr_t.sales_hdr_id');

            Log::info('🔍 STEP 6: Executing Query - Fetching Paginated Results', ['per_page' => $per_page, 'page' => $page]);

            // Execute paginated query
            $orders = $query->orderBy('s_salesorder_hdr_t.created_at', 'desc')
                ->paginate($per_page, ['*'], 'page', $page);

            $total_count = $orders->total();
            Log::info('🔍 STEP 7: Orders Retrieved', [
                'records_returned' => count($orders->items()),
                'total_available' => $total_count,
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage()
            ]);

            // STEP 8: Get Order Line Items for each order
            Log::info('🔍 STEP 8: Fetching Order Line Items');

            $orders_with_lines = [];
            foreach ($orders->items() as $order) {
                Log::debug('  Fetching lines for order', ['sales_hdr_id' => $order->sales_hdr_id, 'order_number' => $order->order_number]);

                $lines = DB::table('s_salesorder_lines_t')
                    ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 's_salesorder_lines_t.product_id')
                    ->leftJoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 's_salesorder_lines_t.uom_code_id')
                    ->select(
                        's_salesorder_lines_t.line_no',
                        'm_products_t.product_id',
                        'm_products_t.product_code',
                        'm_products_t.concatenated_product as product_name',
                        's_salesorder_lines_t.qty as quantity',
                        'm_uom_codes_t.uom_code',
                        's_salesorder_lines_t.unit_price',
                        's_salesorder_lines_t.discount_percentage',
                        's_salesorder_lines_t.discount_amount',
                        's_salesorder_lines_t.tax_amount',
                        's_salesorder_lines_t.free_qty',
                        's_salesorder_lines_t.line_total'
                    )
                    ->where('s_salesorder_lines_t.sales_hdr_id', $order->sales_hdr_id)
                    ->orderBy('s_salesorder_lines_t.line_no', 'asc')
                    ->get();

                $order_array = (array) $order;
                $order_array['order_lines'] = $lines;
                $order_array['line_count'] = count($lines);
                $orders_with_lines[] = $order_array;
            }

            Log::info('🔍 STEP 9: Order Line Items Fetched', ['total_orders_with_lines' => count($orders_with_lines)]);

            // STEP 10: Calculate Status Summary (filtered by distributor mapping)
            Log::info('🔍 STEP 10: Calculating Status Summary');

            // Build status count query with same distributor mapping filter
            $status_query = DB::table('s_salesorder_hdr_t');

            if (count($mappingExists) > 0) {
                // Apply same distributor mapping filter to status summary
                $status_query->join('distributormapping_lines_tbl', 'distributormapping_lines_tbl.disti_id', '=', 's_salesorder_hdr_t.ship_to_customer_id')
                    ->join('distributormapping_hdr_tbl', 'distributormapping_hdr_tbl.distributormapping_id', '=', 'distributormapping_lines_tbl.distributormapping_id')
                    ->where('distributormapping_hdr_tbl.employee_id', $user_details->employee_id);
            }

            $status_counts = $status_query
                ->select('s_salesorder_hdr_t.order_status_id', DB::raw('COUNT(DISTINCT s_salesorder_hdr_t.sales_hdr_id) as count'))
                ->groupBy('s_salesorder_hdr_t.order_status_id')
                ->get();

            $summary = [
                'total' => $total_count,
                'initiated' => 0,
                'approved' => 0,
                'rejected' => 0,
                'draft' => 0,
                'completed' => 0,
                'pending' => 0
            ];

            foreach ($status_counts as $status) {
                $status_key = strtolower($status->order_status_id);
                if (isset($summary[$status_key])) {
                    $summary[$status_key] = (int)$status->count;
                }
            }

            Log::info('🔍 STEP 11: Status Summary Calculated', $summary);

            Log::info('=== GET ALL SALES ORDERS COMPLETED SUCCESSFULLY ===', [
                'total_records' => $total_count,
                'current_page' => $orders->currentPage(),
                'records_on_page' => count($orders_with_lines),
                'has_more' => $orders->hasMorePages(),
                'distributor_mapping_applied' => count($mappingExists) > 0
            ]);

            return response()->json([
                'success' => true,
                'message' => count($mappingExists) > 0 ? 'Sales orders retrieved successfully (filtered by distributor mapping)' : 'All sales orders retrieved successfully',
                'orders' => $orders_with_lines,
                'summary' => $summary,
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $total_count,
                    'last_page' => $orders->lastPage(),
                    'has_more' => $orders->hasMorePages()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('=== GET ALL SALES ORDERS FAILED ===');
            Log::error('Get all sales orders error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Error retrieving sales orders',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Single Sales Order by ID (Complete Details with Line Items)
     * GET /api/sales/order/{sales_hdr_id}
     * 
     * This API is called when user clicks on a specific order from the list.
     * Returns complete order details including:
     * - Order header with all fields and totals
     * - All product line items with full details:
     *   - Product code and name
     *   - Quantity, unit price, and UOM
     *   - Discount (percentage and amount)
     *   - Tax amount
     *   - Free quantity (from schemes)
     *   - Line total
     * 
     * Use this endpoint to get full order details after selecting from getAllSalesOrders list
     */
    public function getSalesOrderById($sales_hdr_id)
    {
        try {
            // Get order header
            $order = DB::table('s_salesorder_hdr_t')
                ->leftJoin('m_customers_t', 'm_customers_t.customer_id', '=', 's_salesorder_hdr_t.ship_to_customer_id')
                ->leftJoin('i_pricelist_hdr_t', 'i_pricelist_hdr_t.pricelist_hdr_id', '=', 's_salesorder_hdr_t.pricelist_id')
                ->leftJoin('hr_employee_t', 'hr_employee_t.employee_id', '=', 's_salesorder_hdr_t.created_by')
                ->select(
                    's_salesorder_hdr_t.sales_hdr_id',
                    's_salesorder_hdr_t.sales_order_no',
                    's_salesorder_hdr_t.sales_order_date',
                    's_salesorder_hdr_t.delivery_date',
                    's_salesorder_hdr_t.order_status_id as order_status',
                    's_salesorder_hdr_t.order_type_id as order_type',
                    's_salesorder_hdr_t.order_total',
                    's_salesorder_hdr_t.order_tax',
                    's_salesorder_hdr_t.order_sub_total',
                    's_salesorder_hdr_t.qty_total',
                    'm_customers_t.customer_id',
                    'm_customers_t.customer_name',
                    'm_customers_t.customer_number',
                    'i_pricelist_hdr_t.pricelist_name',
                    's_salesorder_hdr_t.created_by',
                    's_salesorder_hdr_t.created_at',
                    DB::raw("CONCAT(hr_employee_t.first_name, ' ', COALESCE(hr_employee_t.last_name, '')) as created_by_name")
                )
                ->where('s_salesorder_hdr_t.sales_hdr_id', $sales_hdr_id)
                ->first();

            if (!$order) {
                return response()->json([
                    'message' => 'Sales order not found',
                    'success' => false
                ], 404);
            }

            // Get order lines with complete product details
            $lines = DB::table('s_salesorder_lines_t')
                ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 's_salesorder_lines_t.product_id')
                ->leftJoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 's_salesorder_lines_t.uom_code_id')
                ->select(
                    's_salesorder_lines_t.line_no as line_number',
                    'm_products_t.product_id',
                    'm_products_t.product_code',
                    'm_products_t.concatenated_product as product_name',
                    's_salesorder_lines_t.qty as quantity',
                    'm_uom_codes_t.uom_code',
                    's_salesorder_lines_t.unit_price',
                    's_salesorder_lines_t.discount_percentage',
                    's_salesorder_lines_t.discount_amount',
                    's_salesorder_lines_t.tax_amount',
                    's_salesorder_lines_t.free_qty',
                    's_salesorder_lines_t.line_total as total_amount'
                )
                ->where('s_salesorder_lines_t.sales_hdr_id', $sales_hdr_id)
                ->orderBy('s_salesorder_lines_t.line_no', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Sales order details retrieved successfully',
                'order' => $order,
                'order_lines' => $lines,
                'line_count' => count($lines)
            ]);
        } catch (\Exception $e) {
            Log::error('Get sales order by ID error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Error retrieving sales order details',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Get Customer's Products Grouped by Product Group and Subcategory (with filters)
     * GET /api/sales/customer/{customerId}/products-grouped
     * 
     * Returns products for a specific customer grouped by product group and subcategory
     * Filters: product_group_id = 1, excludes SAMPLES and COMPRESSION
     * Only shows products from customer's mapped pricelist
     */
    public function getCustomerProductsGrouped($customerId)
    {
        try {
            // Get customer and their pricelist
            $customer = DB::table('m_customers_t')
                ->select('customer_id', 'pricelist_id', 'customer_name')
                ->where('customer_id', $customerId)
                ->where('active', 'Yes')
                ->first();

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found or inactive',
                ], 404);
            }

            if (!$customer->pricelist_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No pricelist assigned to this customer',
                ], 400);
            }

            // Get products from customer's pricelist with filters
            $products = DB::table('i_pricelist_lines_t')
                ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 'i_pricelist_lines_t.product_id')
                ->leftJoin('m_product_category_t', 'm_product_category_t.product_category_id', '=', 'm_products_t.product_category_id')
                ->leftJoin('m_product_subcategory_t', function ($join) {
                    $join->on('m_product_subcategory_t.product_category_id', '=', 'm_products_t.product_category_id')
                        ->on('m_product_subcategory_t.product_subcategory_id', '=', 'm_products_t.product_subcategory_id');
                })
                ->leftJoin('m_product_groups_t', 'm_product_groups_t.product_group_id', '=', 'm_products_t.product_group_id')
                ->select(
                    'i_pricelist_lines_t.product_id',
                    'm_products_t.product_code',
                    'm_products_t.concatenated_product',
                    'm_products_t.product_category_id',
                    'm_product_category_t.category_name',
                    'm_products_t.product_subcategory_id',
                    'm_product_subcategory_t.subcategory_name',
                    'm_products_t.product_group_id',
                    'm_product_groups_t.group_name'
                )
                ->where('i_pricelist_lines_t.pricelist_hdr_id', $customer->pricelist_id)
                ->where('i_pricelist_lines_t.active', 'Yes')
                ->where('m_products_t.active', 'Yes')
                ->where('m_products_t.product_group_id', 1)  // Filter by product_group_id = 1
                ->whereNotIn('m_product_subcategory_t.subcategory_name', ['SAMPLES', 'COMPRESSION'])  // Exclude these subcategories
                ->groupBy('m_products_t.product_id')  // CRITICAL FIX: Deduplicate at DB level - prevents 27x data bloat
                ->orderBy('m_product_groups_t.group_name', 'asc')
                ->orderBy('m_product_subcategory_t.subcategory_name', 'asc')
                ->orderBy('m_products_t.concatenated_product', 'asc')
                ->get();

            if (count($products) == 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'No products found for this customer',
                    'customer_id' => $customerId,
                    'customer_name' => $customer->customer_name,
                    'pricelist_id' => $customer->pricelist_id,
                    'data' => []
                ]);
            }

            // Group products by group_name, then by subcategory_name
            $grouped_products = [];
            foreach ($products as $product) {
                $group_name = $product->group_name ?? 'UNCATEGORIZED';
                $subcategory_name = $product->subcategory_name ?? 'Uncategorized';

                if (!isset($grouped_products[$group_name])) {
                    $grouped_products[$group_name] = [];
                }

                if (!isset($grouped_products[$group_name][$subcategory_name])) {
                    $grouped_products[$group_name][$subcategory_name] = [];
                }

                $grouped_products[$group_name][$subcategory_name][] = [
                    'product_id' => $product->product_id,
                    'product_code' => $product->product_code,
                    'concatenated_product' => $product->concatenated_product,
                    'product_category_id' => $product->product_category_id,
                    'category_name' => $product->category_name,
                    'product_subcategory_id' => $product->product_subcategory_id,
                    'subcategory_name' => $product->subcategory_name,
                    'product_group_id' => $product->product_group_id,
                    'group_name' => $product->group_name
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Customer products grouped by product group and subcategory retrieved successfully',
                'customer_name' => $customer->customer_name,
                'pricelist_id' => $customer->pricelist_id,
                'count' => count($products),
                'data' => $grouped_products
            ]);
        } catch (\Exception $e) {
            Log::error('Get customer products grouped error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving customer products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper Methods
     */

    /**
     * Validate sales order input
     */
    private function validateSalesOrder($data)
    {
        Log::debug('=== VALIDATING SALES ORDER ===', ['data_keys' => array_keys($data)]);
        $required_fields = ['ship_to_customer_id', 'pricelist_id', 'sales_order_date', 'delivery_date', 'so_lines'];

        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                Log::warning('Validation Failed - Missing Required Field', ['field' => $field]);
                return [
                    'success' => false,
                    'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required'
                ];
            }
        }
        Log::debug('All Required Fields Present');

        if (!is_array($data['so_lines']) || count($data['so_lines']) === 0) {
            Log::warning('Validation Failed - No Line Items');
            return [
                'success' => false,
                'message' => 'At least one line item is required'
            ];
        }

        Log::info('Sales Order Validation Passed', ['line_count' => count($data['so_lines'])]);
        return ['success' => true];
    }

    /**
     * Generate sequence number for sales order
     */
    private function generateSequenceNumber($prefix, $table, $order_type)
    {
        Log::info('Generating Sequence Number', ['prefix' => $prefix, 'table' => $table, 'order_type' => $order_type]);

        // Query database for last record of this order type
        $last_record = DB::table($table)
            ->where('order_type_id', $order_type)
            ->orderBy('salesorder_count', 'desc')
            ->limit(1)
            ->first();

        $current_count = ($last_record->salesorder_count ?? 0);
        $new_count = $current_count + 1;

        // Append type suffix to prefix (matches old code logic)
        $seqname = $prefix;
        if ($order_type == "LABOUR") {
            $seqname = $seqname . 'L';
        } elseif ($order_type == "STANDARD") {
            $seqname = $seqname . 'S';
        } elseif ($order_type == "SAMPLE") {
            $seqname = $seqname . 'SM';
        }

        // Format sequence number with 4-digit padding (matches old code: sprintf('%04d'))
        $seqno_formatted = sprintf('%04d', $new_count);

        // For sales order table, use simple format: prefix + formatted number
        // Example: SOSM0001, SOSM3283, etc. (not financial year format)
        $number = $seqname . $seqno_formatted;

        Log::debug('Sequence Number Generated', [
            'number' => $number,
            'prefix' => $seqname,
            'sequence' => $seqno_formatted,
            'count' => $new_count
        ]);

        return [
            'number' => $number,
            'count' => $new_count
        ];
    }

    /**
     * Check if approval is needed based on amount and employee rules
     */
    private function checkApprovalNeeded($document_type, $total_amount, $employee_id)
    {
        Log::info('Checking Approval Requirements', ['document_type' => $document_type, 'total_amount' => $total_amount, 'employee_id' => $employee_id]);
        // TODO: Implement approval limit check from company settings
        // For now, return '0' for auto-approval if amount < 50000, else get reporting manager

        if ($total_amount < 50000) {
            Log::debug('Auto-Approve: Amount Below Threshold', ['threshold' => 50000]);
            return '0'; // Auto-approve
        }

        // Get reporting manager for approval
        Log::info('Fetching Reporting Manager for Approval');
        $reporting_manager = DB::table('hr_employee_t')
            ->where('employee_id', $employee_id)
            ->select('reporting_manager')
            ->first();

        $approver = $reporting_manager->reporting_manager ?? $employee_id;
        Log::debug('Approver Assigned', ['approver_id' => $approver]);
        return $approver;
    }

    /**
     * Get tax details based on HSN code and customer location
     * Complex logic:
     * 1. Find HSN code in f_gst_code_hdr_t (active only)
     * 2. Get tax lines from f_gst_code_lines_t (intrastate vs interstate)
     * 3. Determine customer state from customer_site_id
     * 4. If state_id = 31 (Tamil Nadu), use intrastate tax_group_id, else interstate
     * 5. Look up tax_group details from m_tax_group_t
     */
    private function getTaxDetails($hsn_code, $customer_site_id, $transaction_type)
    {
        try {
            Log::debug('Get Tax Details Called', ['hsn_code' => $hsn_code, 'customer_site_id' => $customer_site_id, 'transaction_type' => $transaction_type]);

            if (empty($hsn_code)) {
                Log::debug('HSN Code is Empty');
                return [];
            }

            // Step 1: Find GST Code Header by HSN Code
            Log::info('Step 1: Finding GST Code Header by HSN Code', ['hsn_code' => $hsn_code]);
            $gst_code_header = DB::table('f_gst_code_hdr_t')
                ->where('gst_code_hdr_id', $hsn_code)
                ->where('active', 'Yes')
                ->select('gst_code_hdr_id', 'classification_code', 'description')
                ->first();

            if (!$gst_code_header) {
                Log::debug('GST Code Header Not Found for HSN Code', ['hsn_code' => $hsn_code]);
                return [];
            }
            Log::debug('GST Code Header Found', ['gst_code_hdr_id' => $gst_code_header->gst_code_hdr_id, 'description' => $gst_code_header->description]);

            // Step 2: Get Customer State from customer_site_id
            Log::info('Step 2: Getting Customer State from Customer Site', ['customer_site_id' => $customer_site_id]);

            // Directly fetch from m_customer_sites_t using customer_site_id
            $customer_site = DB::table('m_customer_sites_t')
                ->where('customer_site_id', $customer_site_id)
                ->where('active', 'Yes')
                ->select('customer_site_id', 'customer_id', 'state', 'site_type')
                ->first();

            $state_id = null;
            if ($customer_site && $customer_site->state) {
                $state_id = $customer_site->state;
                Log::debug('Customer State Found', [
                    'customer_site_id' => $customer_site->customer_site_id,
                    'customer_id' => $customer_site->customer_id,
                    'state_id' => $state_id,
                    'site_type' => $customer_site->site_type
                ]);
            } else {
                Log::warning('Customer Site State Not Found', ['customer_site_id' => $customer_site_id]);
                Log::debug('Debug: Customer site query result', ['result' => $customer_site]);
                // Return empty if state not found
                return [];
            }

            // Step 3: Determine if Intrastate (31 = Tamil Nadu) or Interstate
            Log::info('Step 3: Determining Tax Location Type', ['state_id' => $state_id]);
            $tax_location_type = 'Interstate';

            // Check if state_id is 31 (Tamil Nadu - Intrastate)
            if ($state_id == 31) {
                $tax_location_type = 'Intrastate(within-state)';
                Log::debug('Intrastate Tax Applied (Tamil Nadu)');
            } else {
                Log::debug('Interstate Tax Applied');
            }

            // Step 4: Get Tax Group ID from GST Code Lines based on location type
            // Filter by current date to get active tax rate based on start_date and end_date
            $current_date = date('Y-m-d');
            Log::info('Step 4: Getting Tax Group ID from GST Code Lines', [
                'gst_code_hdr_id' => $gst_code_header->gst_code_hdr_id,
                'tax_location_type' => $tax_location_type,
                'current_date' => $current_date
            ]);
            $gst_code_line = DB::table('f_gst_code_lines_t')
                ->where('gst_code_hdr_id', $gst_code_header->gst_code_hdr_id)
                ->where('tax_location_type', $tax_location_type)
                ->where('active', 'Yes')
                ->whereDate('start_date', '<=', $current_date)
                ->whereDate('end_date', '>=', $current_date)
                ->select('tax_group_id', 'tax_location_type', 'start_date', 'end_date')
                ->first();

            if (!$gst_code_line) {
                Log::warning('GST Code Line Not Found', [
                    'gst_code_hdr_id' => $gst_code_header->gst_code_hdr_id,
                    'tax_location_type' => $tax_location_type,
                    'current_date' => $current_date
                ]);
                return [];
            }
            Log::debug('GST Code Line Found', [
                'tax_group_id' => $gst_code_line->tax_group_id,
                'start_date' => $gst_code_line->start_date,
                'end_date' => $gst_code_line->end_date
            ]);

            // Step 5: Get Tax Group Details from m_tax_group_t
            Log::info('Step 5: Getting Tax Group Details', ['tax_group_id' => $gst_code_line->tax_group_id]);
            $tax_group = DB::table('m_tax_group_t')
                ->where('tax_group_id', $gst_code_line->tax_group_id)
                ->select('tax_group_id', 'tax_group_name', 'display_name')
                ->first();

            if (!$tax_group) {
                Log::warning('Tax Group Not Found', ['tax_group_id' => $gst_code_line->tax_group_id]);
                return [];
            }
            Log::debug('Tax Group Found', ['tax_group_name' => $tax_group->tax_group_name, 'display_name' => $tax_group->display_name]);

            // Build complete tax details response
            $tax_details = [
                [
                    'hsn_code' => $hsn_code,
                    'gst_code_hdr_id' => $gst_code_header->gst_code_hdr_id,
                    'classification_code' => $gst_code_header->classification_code,
                    'description' => $gst_code_header->description,
                    'tax_group_id' => (int)$gst_code_line->tax_group_id,
                    'tax_location_type' => $gst_code_line->tax_location_type,
                    'state_id' => $state_id,
                    'tax_group_name' => $tax_group->tax_group_name,
                    'tax_percentage' => (int)$tax_group->display_name
                ]
            ];

            Log::info('Tax Details Resolved Successfully', [
                'tax_group_id' => $gst_code_line->tax_group_id,
                'tax_percentage' => $tax_group->display_name,
                'tax_location_type' => $tax_location_type
            ]);

            return $tax_details;
        } catch (\Exception $e) {
            Log::error('Error Getting Tax Details', ['error' => $e->getMessage(), 'hsn_code' => $hsn_code]);
            return [];
        }
    }

    /**
     * ============================================================
     * SAMPLE SALES ORDER APIS
     * ============================================================
     */

    /**
     * Create Sample Sales Order
     * POST /api/sales/sample-orders
     * 
     * Sample orders are employee-based product sample distribution orders
     * Based on old code logic: order_type_id='SAMPLE', ship_to_customer_id=0
     * Follows same structure as sales orders but for sample distribution
     */
    public function createSampleSalesOrder(Request $request)
    {
        try {
            Log::info('=== CREATE SAMPLE SALES ORDER STARTED ===');
            $posted_data = $request->json()->all();
            Log::debug('Request Data Received', ['posted_data' => $posted_data]);

            // Validate required fields for sample order
            Log::info('Validating Sample Order Fields');
            $validated = $this->validateSampleOrder($posted_data);
            if (!$validated['success']) {
                Log::warning('Sample Order Validation Failed', ['errors' => $validated['errors'] ?? null]);
                return response()->json([
                    'success' => false,
                    'message' => $validated['message'],
                    'errors' => $validated['errors'] ?? null
                ], 422);
            }

            // Get authenticated user
            Log::info('Fetching Authenticated User Details');
            $user = Auth::user();
            $user_details = DB::table('tb_users')
                ->where('id', $user->id)
                ->first();

            if (!$user_details) {
                Log::error('User Details Not Found', ['user_id' => $user->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 401);
            }

            $emp_id = $user_details->employee_id;

            // Fetch address IDs from hr_emp_contact table using employee_id
            Log::info('Fetching Employee Contact Address Details', ['emp_id' => $emp_id]);
            $emp_contact = DB::table('hr_emp_contact')
                ->where('employee_id', $emp_id)
                ->first();

            $bill_to_address_id = '';
            $ship_to_address_id = '';

            if ($emp_contact) {
                $bill_to_address_id = $emp_contact->id ?? '';
                $ship_to_address_id = $emp_contact->id ?? '';
                Log::debug('Employee Contact Found', ['emp_id' => $emp_id, 'bill_to_address_id' => $bill_to_address_id, 'ship_to_address_id' => $ship_to_address_id]);
            } else {
                Log::warning('Employee Contact Not Found', ['emp_id' => $emp_id]);
            }

            // Start transaction
            Log::info('Starting Database Transaction');
            DB::beginTransaction();

            // Prepare sample order header data (same structure as sales order)
            Log::info('Preparing Sample Order Header Data');
            $header_data = [
                'created_by' => $emp_id,
                'company_id' => $user_details->company_id,
                'location_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'last_updated_by' => $emp_id,

                // Core SO fields
                'order_type_id' => 'SAMPLE',  // Sample order type
                'source' => 'SAMPLE',
                'order_status_id' => 'DRAFT',
                'order_status' => 'INITIATED',

                // Address and customer fields (sample orders have no customer)
                'ship_to_customer_id' => 0,  // Sample orders: customer_id = 0
                'bill_to_address_id' => $bill_to_address_id,
                'ship_to_address_id' => $ship_to_address_id,
                'contact_number' => $posted_data['contact_number'] ?? '',
                'contact_person' => 0,
                'salesperson_id' => 0,
                // Dates
                'sales_order_date' => date('Y-m-d'),
                'delivery_date' => date('Y-m-d', strtotime($posted_data['delivery_date'])),
                'employee_id' => $emp_id,
                // Default payment and currency
                'ar_delivery_terms_id' => 0,
                'ar_payment_method_id' => 0,
                'invoice_currency' => $posted_data['invoice_currency'] ?? 37,
                'freight_carrier_id' => 0,
                'organization_id' => 1,
                // Pricing and discount (samples usually no pricelist)
                'pricelist_id' => 176,
                'discount_id' => 0,
                'proforma_invoice' => 'NO',
                'tcs_applicable' => 'NO',
                'tcs_account_id' => 0,
                'tcs_amount' => 0,
                'tcs_calc_amount' => 0,
                // Remarks
                'remarks' => $posted_data['remarks'] ?? ''
            ];

            // Generate sequence number for sample order (uses 'SO' prefix same as regular SO)
            Log::info('Generating Sample Order Sequence Number');
            $seqno = $this->generateSequenceNumber('SO', 's_salesorder_hdr_t', 'SAMPLE');
            $header_data['sales_order_no'] = $seqno['number'];
            $header_data['salesorder_count'] = $seqno['count'];
            $header_data['reference_number'] = $seqno['number'];
            Log::debug('Sample Order Number Generated', ['sales_order_no' => $seqno['number'], 'count' => $seqno['count']]);

            // Insert header
            Log::info('Inserting Sample Order Header into Database');
            $sales_hdr_id = DB::table('s_salesorder_hdr_t')->insertGetId($header_data);
            Log::info('Sample Order Header Inserted Successfully', ['sales_hdr_id' => $sales_hdr_id]);

            if (!$sales_hdr_id || $sales_hdr_id <= 0) {
                Log::error('Failed to Generate Sample Order ID', ['sales_hdr_id' => $sales_hdr_id]);
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate sample order ID'
                ], 500);
            }

            // Process line items
            Log::info('Processing Sample Order Line Items', ['line_count' => count($posted_data['so_lines'])]);
            $total_amount = 0;
            $total_tax = 0;
            $total_qty = 0;

            foreach ($posted_data['so_lines'] as $key => $line_item) {
                Log::debug('Processing Line Item', ['line_no' => $key + 1, 'product_id' => $line_item['product_id'], 'qty' => $line_item['qty']]);

                // Support both 'amount' (old format) and 'line_subtotal' (new format)
                $line_amount = $line_item['amount'] ?? $line_item['line_subtotal'] ?? 0;
                $line_tax = $line_item['tax_amount'] ?? 0;
                $line_total = $line_amount + $line_tax;
                $total_amount += $line_amount;
                $total_tax += $line_tax;
                $total_qty += $line_item['qty'] ?? 0;

                // Get product info for complete line data
                $product = DB::table('m_products_t')
                    ->where('product_id', $line_item['product_id'])
                    ->select('product_code', 'concatenated_product', 'defalut_hsn_code', 'trx_uom_id')
                    ->first();

                $line_data = [
                    'sales_hdr_id' => $sales_hdr_id,
                    'line_no' => $key + 1,
                    'product_id' => $line_item['product_id'],
                    'part_no' => $product->product_code ?? '',
                    'product_description' => $product->concatenated_product ?? '',
                    'uom_code_id' => $product->trx_uom_id ?? 1,
                    'qty' => $line_item['qty'],
                    'unit_price' => $line_item['unit_price'] ?? 0,
                    'delivery_date' => date('Y-m-d', strtotime($line_item['delivery_date'] ?? now())),
                    'hsn_code' => $product->defalut_hsn_code ?? '',
                    'tax_group_id' => $line_item['tax_group_id'] ?? 0,
                    'tax_amount' => $line_item['tax_amount'] ?? 0,
                    'discount_percentage' => $line_item['discount_percentage'] ?? 0,
                    'discount_amount' => $line_item['discount_amount'] ?? 0,
                    'pending_qty' => $line_item['qty'], // For sample orders, pending_qty = qty
                    'line_total' => $line_total,
                    'free_qty' => $line_item['free_qty'] ?? 0,
                    'comments' => $line_item['comments'] ?? '',
                    'location_id' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'last_updated_by' => $emp_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $emp_id,
                    'organization_id' => 1,
                    'tax_excemption' => $line_item['tax_excemption'] ?? 'No',
                    'company_id' => $user_details->company_id
                ];

                DB::table('s_salesorder_lines_t')->insert($line_data);
                Log::info('Line Item Inserted Successfully', ['line_no' => $key + 1, 'sales_hdr_id' => $sales_hdr_id]);
            }
            Log::info('All Line Items Processed', ['total_amount' => $total_amount, 'total_tax' => $total_tax, 'total_qty' => $total_qty]);

            // Check approval requirements based on amount
            Log::info('Checking Sample Order Approval Requirements', ['total_amount' => $total_amount]);
            $approver_json = $this->getApprovalDataFromSettings('soorder', $total_amount, $emp_id);
            Log::debug('Approver Array from Settings', ['approver_json' => $approver_json]);

            // Update header with calculated totals and approver
            DB::table('s_salesorder_hdr_t')
                ->where('sales_hdr_id', $sales_hdr_id)
                ->update([
                    'order_total' => $total_amount,
                    'qty_total' => $total_qty,
                    'order_tax' => $total_tax,
                    'order_sub_total' => $total_amount,
                    'approver_id' => $approver_json
                ]);
            Log::info('Sample Order Updated with Approver', ['sales_hdr_id' => $sales_hdr_id, 'order_total' => $total_amount]);

            DB::commit();
            Log::info('=== CREATE SAMPLE SALES ORDER COMPLETED SUCCESSFULLY ===', [
                'sales_hdr_id' => $sales_hdr_id,
                'sales_order_no' => $header_data['sales_order_no'],
                'order_total' => $total_amount,
                'order_tax' => $total_tax,
                'qty_total' => $total_qty
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sample sales order created successfully',
                'data' => [
                    'sales_hdr_id' => (int)$sales_hdr_id,
                    'sales_order_no' => $header_data['sales_order_no'],
                    'order_type_id' => 'SAMPLE',
                    'order_status_id' => 'DRAFT',
                    'order_status' => 'INITIATED',
                    'order_total' => (float)$total_amount,
                    'order_tax' => (float)$total_tax,
                    'qty_total' => (int)$total_qty,
                    'approver_list' => json_decode($approver_json, true),
                    'requires_approval' => true,
                    'created_at' => date('Y-m-d H:i:s')
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== CREATE SAMPLE SALES ORDER FAILED ===');
            Log::error('Create sample sales order error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Error creating sample sales order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Sample Sales Orders List
     * GET /api/sales/sample-orders
     */
    public function getSampleSalesOrdersList(Request $request)
    {
        try {
            Log::info('=== GET SAMPLE SALES ORDERS LIST STARTED ===');
            $user = Auth::user();
            $user_details = DB::table('tb_users')
                ->where('id', $user->id)
                ->first();
            Log::debug('User Retrieved', ['employee_id' => $user_details->employee_id]);

            $emp_id = $user_details->employee_id;
            $status = $request->input('status');
            $per_page = $request->input('per_page', 10);
            Log::debug('Query Parameters', ['status' => $status, 'per_page' => $per_page]);

            $query = DB::table('s_salesorder_hdr_t')
                ->leftJoin('hr_employee_t as creator', 'creator.employee_id', '=', 's_salesorder_hdr_t.created_by')
                ->leftJoin('hr_employee_t as approver', 'approver.employee_id', '=', 's_salesorder_hdr_t.approver_id')
                ->select(
                    's_salesorder_hdr_t.sales_hdr_id',
                    's_salesorder_hdr_t.sales_order_no',
                    's_salesorder_hdr_t.order_type_id',
                    's_salesorder_hdr_t.order_status_id',
                    's_salesorder_hdr_t.sales_order_date',
                    's_salesorder_hdr_t.delivery_date',
                    's_salesorder_hdr_t.order_total',
                    's_salesorder_hdr_t.order_tax',
                    's_salesorder_hdr_t.qty_total',
                    's_salesorder_hdr_t.remarks',
                    's_salesorder_hdr_t.created_at',
                    DB::raw("CONCAT(creator.first_name, ' ', COALESCE(creator.last_name, '')) as employee_name"),
                    DB::raw("CONCAT(approver.first_name, ' ', COALESCE(approver.last_name, '')) as approver_name")
                )
                ->where('s_salesorder_hdr_t.order_type_id', 'SAMPLE')
                ->where('s_salesorder_hdr_t.created_by', $emp_id);

            if ($status) {
                Log::info('Filtering by Status', ['status' => $status]);
                $query->where('s_salesorder_hdr_t.order_status_id', $status);
            }

            Log::info('Fetching Sample Sales Orders');
            $orders = $query->orderBy('s_salesorder_hdr_t.sales_hdr_id', 'desc')
                ->paginate($per_page);
            Log::info('=== GET SAMPLE SALES ORDERS LIST COMPLETED ===', ['count' => count($orders->items())]);

            return response()->json([
                'success' => true,
                'message' => 'Sample sales orders retrieved successfully',
                'data' => [
                    'orders' => $orders->items(),
                    'pagination' => [
                        'total' => $orders->total(),
                        'per_page' => $orders->perPage(),
                        'current_page' => $orders->currentPage(),
                        'last_page' => $orders->lastPage(),
                        'from' => $orders->firstItem(),
                        'to' => $orders->lastItem()
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('=== GET SAMPLE SALES ORDERS FAILED ===');
            Log::error('Get sample sales orders list error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve sample sales orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Single Sample Sales Order Details
     * GET /api/sales/sample-orders/{sales_hdr_id}
     */
    public function getSampleSalesOrderDetails($sales_hdr_id)
    {
        try {
            Log::info('=== GET SAMPLE SALES ORDER DETAILS STARTED ===', ['sales_hdr_id' => $sales_hdr_id]);
            // Get header
            Log::info('Fetching Sample Order Header');
            $header = DB::table('s_salesorder_hdr_t')
                ->leftJoin('hr_employee_t as creator', 'creator.employee_id', '=', 's_salesorder_hdr_t.created_by')
                ->leftJoin('hr_employee_t as approver', 'approver.employee_id', '=', 's_salesorder_hdr_t.approver_id')
                ->select(
                    's_salesorder_hdr_t.*',
                    DB::raw("CONCAT(creator.first_name, ' ', COALESCE(creator.last_name, '')) as employee_name"),
                    DB::raw("CONCAT(approver.first_name, ' ', COALESCE(approver.last_name, '')) as approver_name")
                )
                ->where('s_salesorder_hdr_t.sales_hdr_id', $sales_hdr_id)
                ->where('s_salesorder_hdr_t.order_type_id', 'SAMPLE')
                ->first();

            if (!$header) {
                Log::warning('Sample Sales Order Not Found', ['sales_hdr_id' => $sales_hdr_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Sample sales order not found'
                ], 404);
            }
            Log::debug('Sample Order Header Found');

            // Get lines
            Log::info('Fetching Sample Order Lines');
            $lines = DB::table('s_salesorder_lines_t')
                ->leftJoin('m_products_t', 'm_products_t.product_id', '=', 's_salesorder_lines_t.product_id')
                ->leftJoin('m_uom_codes_t', 'm_uom_codes_t.uom_code_id', '=', 's_salesorder_lines_t.uom_code_id')
                ->select(
                    's_salesorder_lines_t.sales_line_id',
                    's_salesorder_lines_t.line_no',
                    's_salesorder_lines_t.product_id',
                    'm_products_t.product_code',
                    'm_products_t.concatenated_product as product_name',
                    's_salesorder_lines_t.qty',
                    's_salesorder_lines_t.unit_price',
                    's_salesorder_lines_t.tax_group_id',
                    's_salesorder_lines_t.tax_amount',
                    's_salesorder_lines_t.discount_amount',
                    's_salesorder_lines_t.line_total',
                    's_salesorder_lines_t.delivery_date',
                    's_salesorder_lines_t.comments',
                    'm_uom_codes_t.uom_code'
                )
                ->where('s_salesorder_lines_t.sales_hdr_id', $sales_hdr_id)
                ->orderBy('s_salesorder_lines_t.line_no')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Sample sales order details retrieved',
                'data' => [
                    'header' => $header,
                    'lines' => $lines
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('=== GET SAMPLE SALES ORDER DETAILS FAILED ===');
            Log::error('Get sample sales order details error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve sample sales order details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update Sample Sales Order
     * PUT /api/sales/sample-orders/{sales_hdr_id}
     */
    public function updateSampleSalesOrder(Request $request, $sales_hdr_id)
    {
        try {
            Log::info('=== UPDATE SAMPLE SALES ORDER STARTED ===', ['sales_hdr_id' => $sales_hdr_id]);
            $posted_data = $request->json()->all();
            Log::debug('Request Data Received', ['posted_data' => $posted_data]);

            // Check if order exists and is editable
            Log::info('Checking if Sample Order Exists');
            $order = DB::table('s_salesorder_hdr_t')
                ->where('sales_hdr_id', $sales_hdr_id)
                ->where('order_type_id', 'SAMPLE')
                ->first();

            if (!$order) {
                Log::warning('Sample Sales Order Not Found', ['sales_hdr_id' => $sales_hdr_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Sample sales order not found'
                ], 404);
            }

            // Only DRAFT and INITIATED orders can be edited
            if (!in_array($order->order_status_id, ['DRAFT', 'INITIATED'])) {
                Log::warning('Cannot Edit Order - Invalid Status', ['current_status' => $order->order_status_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot edit order in ' . $order->order_status_id . ' status'
                ], 400);
            }

            $user = Auth::user();
            $user_details = DB::table('tb_users')->where('id', $user->id)->first();
            $emp_id = $user_details->employee_id;
            Log::debug('Employee ID', ['emp_id' => $emp_id]);

            Log::info('Starting Database Transaction');
            DB::beginTransaction();

            // Update header
            Log::info('Updating Sample Order Header');
            $update_data = [
                'delivery_date' => isset($posted_data['delivery_date']) ? date('Y-m-d', strtotime($posted_data['delivery_date'])) : $order->delivery_date,
                'remarks' => $posted_data['remarks'] ?? $order->remarks,
                'updated_at' => date('Y-m-d H:i:s'),
                'last_updated_by' => $emp_id
            ];

            DB::table('s_salesorder_hdr_t')
                ->where('sales_hdr_id', $sales_hdr_id)
                ->update($update_data);
            Log::info('Sample Order Header Updated Successfully');

            // Update lines if provided
            if (isset($posted_data['so_lines']) && is_array($posted_data['so_lines'])) {
                Log::info('Updating Sample Order Lines', ['line_count' => count($posted_data['so_lines'])]);
                // Delete existing lines
                DB::table('s_salesorder_lines_t')
                    ->where('sales_hdr_id', $sales_hdr_id)
                    ->delete();
                Log::info('Old Lines Deleted');

                // Insert updated lines
                $total_amount = 0;
                $total_tax = 0;
                $total_qty = 0;

                foreach ($posted_data['so_lines'] as $key => $line_item) {
                    Log::debug('Inserting Updated Line', ['line_no' => $key + 1]);
                    $line_total = ($line_item['amount'] ?? 0) + ($line_item['tax_amount'] ?? 0);
                    $total_amount += $line_item['amount'] ?? 0;
                    $total_tax += $line_item['tax_amount'] ?? 0;
                    $total_qty += $line_item['qty'] ?? 0;

                    $line_data = [
                        'sales_hdr_id' => $sales_hdr_id,
                        'line_no' => $key + 1,
                        'product_id' => $line_item['product_id'],
                        'qty' => $line_item['qty'],
                        'unit_price' => 0,
                        'delivery_date' => date('Y-m-d', strtotime($line_item['delivery_date'])),
                        'tax_group_id' => $line_item['tax_group_id'] ?? 0,
                        'tax_amount' => $line_item['tax_amount'] ?? 0,
                        'discount_amount' => $line_item['discount_amount'] ?? 0,
                        'line_total' => $line_total,
                        'uom_code_id' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'last_updated_by' => $emp_id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => $emp_id
                    ];

                    DB::table('s_salesorder_lines_t')->insert($line_data);
                }

                // Update totals
                Log::info('Updating Order Totals', ['total_amount' => $total_amount]);
                DB::table('s_salesorder_hdr_t')
                    ->where('sales_hdr_id', $sales_hdr_id)
                    ->update([
                        'order_total' => $total_amount + $total_tax,
                        'qty_total' => $total_qty,
                        'order_tax' => $total_tax
                    ]);
            }

            DB::commit();
            Log::info('Database Transaction Committed Successfully');
            Log::info('=== UPDATE SAMPLE SALES ORDER COMPLETED SUCCESSFULLY ===', ['sales_hdr_id' => $sales_hdr_id]);

            // Get updated order
            $updated_order = DB::table('s_salesorder_hdr_t')
                ->where('sales_hdr_id', $sales_hdr_id)
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Sample sales order updated successfully',
                'data' => [
                    'sales_hdr_id' => $sales_hdr_id,
                    'sales_order_no' => $updated_order->sales_order_no,
                    'order_total' => $updated_order->order_total,
                    'order_tax' => $updated_order->order_tax,
                    'qty_total' => $updated_order->qty_total,
                    'updated_at' => $updated_order->updated_at
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== UPDATE SAMPLE SALES ORDER FAILED ===');
            Log::error('Update sample sales order error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update sample sales order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve Sample Sales Order
     * POST /api/sales/sample-orders/{sales_hdr_id}/approve
     */
    public function approveSampleSalesOrder(Request $request, $sales_hdr_id)
    {
        try {
            Log::info('=== APPROVE SAMPLE SALES ORDER STARTED ===', ['sales_hdr_id' => $sales_hdr_id]);
            $posted_data = $request->json()->all();
            Log::debug('Request Data Received', ['remarks' => $posted_data['remarks'] ?? '']);

            Log::info('Fetching Sample Order');
            $order = DB::table('s_salesorder_hdr_t')
                ->where('sales_hdr_id', $sales_hdr_id)
                ->where('order_type_id', 'SAMPLE')
                ->first();

            if (!$order) {
                Log::warning('Sample Sales Order Not Found', ['sales_hdr_id' => $sales_hdr_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Sample sales order not found'
                ], 404);
            }

            if ($order->order_status_id !== 'INITIATED') {
                Log::warning('Cannot Approve Order - Invalid Status', ['current_status' => $order->order_status_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Only INITIATED orders can be approved'
                ], 400);
            }

            $user = Auth::user();
            $user_details = DB::table('tb_users')->where('id', $user->id)->first();
            $emp_id = $user_details->employee_id;
            Log::debug('Approver ID', ['emp_id' => $emp_id]);

            // Verify user is the approver
            if ($order->approver_id != $emp_id) {
                Log::warning('Unauthorized Approval Attempt', ['expected_approver' => $order->approver_id, 'attempted_by' => $emp_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to approve this order'
                ], 403);
            }

            Log::info('Updating Sample Order to APPROVED Status');

            // Maintain / append approver history in app_approver_id (stored as JSON array in DB)
            try {
                $existing_app_approvers = $order->app_approver_id ?? '';
                $app_approvers = [];

                if (!empty($existing_app_approvers)) {
                    $decoded = json_decode($existing_app_approvers, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $app_approvers = $decoded;
                    } else {
                        // handle comma separated or plain integer values
                        $trimmed = trim($existing_app_approvers, "[] \n\r\t");
                        if ($trimmed !== '') {
                            $parts = preg_split('/\s*,\s*/', $trimmed);
                            foreach ($parts as $p) {
                                $p = trim($p);
                                if ($p !== '') {
                                    $app_approvers[] = is_numeric($p) ? (int)$p : $p;
                                }
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Failed to parse existing app_approver_id for sample order, starting fresh', ['error' => $e->getMessage()]);
                $app_approvers = [];
            }

            // Append current approver if not present
            if (!in_array($emp_id, $app_approvers, true)) {
                $app_approvers[] = $emp_id;
            }

            $update_data = [
                'order_status_id' => 'INITIATED',
                'order_status' => 'INITIATED',
                'approved_by' => $emp_id,
                'approved_date' => date('Y-m-d'),
                'approval_remarks' => $posted_data['remarks'] ?? '',
                'app_approver_id' => json_encode(array_values($app_approvers)),
                'updated_at' => date('Y-m-d H:i:s'),
                'last_updated_by' => $emp_id
            ];

            DB::table('s_salesorder_hdr_t')
                ->where('sales_hdr_id', $sales_hdr_id)
                ->update($update_data);
            Log::info('Sample Order Approved Successfully', ['sales_hdr_id' => $sales_hdr_id, 'app_approver_count' => count($app_approvers)]);

            Log::info('Sample Sales Order Approved', [
                'sales_hdr_id' => $sales_hdr_id,
                'approved_by' => $emp_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sample sales order approved successfully',
                'data' => [
                    'sales_hdr_id' => $sales_hdr_id,
                    'sales_order_no' => $order->sales_order_no,
                    'order_status_id' => 'INITIATED',
                    'last_updated_by' => $emp_id,
                    'approved_date' => date('Y-m-d H:i:s'),
                    'approval_remarks' => $posted_data['remarks'] ?? ''
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('=== APPROVE SAMPLE SALES ORDER FAILED ===');
            Log::error('Approve sample sales order error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve sample sales order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject Sample Sales Order
     * POST /api/sales/sample-orders/{sales_hdr_id}/reject
     */
    public function rejectSampleSalesOrder(Request $request, $sales_hdr_id)
    {
        try {
            Log::info('=== REJECT SAMPLE SALES ORDER STARTED ===', ['sales_hdr_id' => $sales_hdr_id]);
            $posted_data = $request->json()->all();
            Log::debug('Request Data Received', ['remarks' => $posted_data['remarks'] ?? '']);

            Log::info('Fetching Sample Order');
            $order = DB::table('s_salesorder_hdr_t')
                ->where('sales_hdr_id', $sales_hdr_id)
                ->where('order_type_id', 'SAMPLE')
                ->first();

            if (!$order) {
                Log::warning('Sample Sales Order Not Found', ['sales_hdr_id' => $sales_hdr_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Sample sales order not found'
                ], 404);
            }

            if ($order->order_status_id !== 'INITIATED') {
                Log::warning('Cannot Reject Order - Invalid Status', ['current_status' => $order->order_status_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Only INITIATED orders can be rejected'
                ], 400);
            }

            $user = Auth::user();
            $user_details = DB::table('tb_users')->where('id', $user->id)->first();
            $emp_id = $user_details->employee_id;
            Log::debug('Rejector ID', ['emp_id' => $emp_id]);

            // Verify user is the approver
            if ($order->approver_id != $emp_id) {
                Log::warning('Unauthorized Rejection Attempt', ['expected_approver' => $order->approver_id, 'attempted_by' => $emp_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to reject this order'
                ], 403);
            }

            Log::info('Updating Sample Order to REJECTED Status');
            $update_data = [
                'order_status_id' => 'REJECTED',
                'rejected_by' => $emp_id,
                'rejected_date' => date('Y-m-d'),
                'rejection_remarks' => $posted_data['remarks'] ?? 'Rejected',
                'updated_at' => date('Y-m-d H:i:s'),
                'last_updated_by' => $emp_id
            ];

            DB::table('s_salesorder_hdr_t')
                ->where('sales_hdr_id', $sales_hdr_id)
                ->update($update_data);
            Log::info('Sample Order Rejected Successfully', ['sales_hdr_id' => $sales_hdr_id]);

            Log::info('Sample Sales Order Rejected', [
                'sales_hdr_id' => $sales_hdr_id,
                'rejected_by' => $emp_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sample sales order rejected',
                'data' => [
                    'sales_hdr_id' => $sales_hdr_id,
                    'sales_order_no' => $order->sales_order_no,
                    'order_status_id' => 'REJECTED',
                    'rejected_by' => $emp_id,
                    'rejected_date' => date('Y-m-d'),
                    'rejection_remarks' => $posted_data['remarks'] ?? 'Rejected'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('=== REJECT SAMPLE SALES ORDER FAILED ===');
            Log::error('Reject sample sales order error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject sample sales order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Pending Sample Sales Orders for Approval
     * GET /api/sales/sample-orders/pending-approvals
     */
    public function getPendingSampleApprovals(Request $request)
    {
        try {
            Log::info('=== GET PENDING SAMPLE APPROVALS STARTED ===');
            $user = Auth::user();
            $user_details = DB::table('tb_users')->where('id', $user->id)->first();
            $emp_id = $user_details->employee_id;
            Log::debug('Approver ID', ['emp_id' => $emp_id]);

            Log::info('Fetching Pending Sample Orders for Approval');
            $orders = DB::table('s_salesorder_hdr_t')
                ->leftJoin('hr_employee_t as creator', 'creator.employee_id', '=', 's_salesorder_hdr_t.created_by')
                ->select(
                    's_salesorder_hdr_t.sales_hdr_id',
                    's_salesorder_hdr_t.sales_order_no',
                    's_salesorder_hdr_t.sales_order_date',
                    's_salesorder_hdr_t.order_total',
                    's_salesorder_hdr_t.qty_total',
                    's_salesorder_hdr_t.created_at',
                    DB::raw("CONCAT(creator.first_name, ' ', COALESCE(creator.last_name, '')) as employee_name")
                )
                ->where('s_salesorder_hdr_t.order_type_id', 'SAMPLE')
                ->where('s_salesorder_hdr_t.order_status_id', 'INITIATED')
                ->where('s_salesorder_hdr_t.approver_id', $emp_id)
                ->orderBy('s_salesorder_hdr_t.sales_hdr_id', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Pending sample orders for approval',
                'data' => [
                    'orders' => $orders,
                    'count' => count($orders)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('=== GET PENDING SAMPLE APPROVALS FAILED ===');
            Log::error('Get pending sample approvals error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve pending approvals',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel/Delete Sample Sales Order
     * DELETE /api/sales/sample-orders/{sales_hdr_id}
     */
    public function cancelSampleSalesOrder($sales_hdr_id)
    {
        try {
            Log::info('=== CANCEL SAMPLE SALES ORDER STARTED ===', ['sales_hdr_id' => $sales_hdr_id]);
            Log::info('Fetching Sample Order');
            $order = DB::table('s_salesorder_hdr_t')
                ->where('sales_hdr_id', $sales_hdr_id)
                ->where('order_type_id', 'SAMPLE')
                ->first();

            if (!$order) {
                Log::warning('Sample Sales Order Not Found', ['sales_hdr_id' => $sales_hdr_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Sample sales order not found'
                ], 404);
            }

            // Only DRAFT and INITIATED orders can be cancelled
            if (!in_array($order->order_status_id, ['DRAFT', 'INITIATED'])) {
                Log::warning('Cannot Cancel Order - Invalid Status', ['current_status' => $order->order_status_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel order in ' . $order->order_status_id . ' status'
                ], 400);
            }

            $user = Auth::user();
            $user_details = DB::table('tb_users')->where('id', $user->id)->first();
            $emp_id = $user_details->employee_id;
            Log::debug('Employee ID', ['emp_id' => $emp_id]);

            Log::info('Updating Sample Order to CANCELLED Status');
            DB::table('s_salesorder_hdr_t')
                ->where('sales_hdr_id', $sales_hdr_id)
                ->update([
                    'order_status_id' => 'CANCELLED',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'last_updated_by' => $emp_id
                ]);
            Log::info('Sample Order Cancelled Successfully', ['sales_hdr_id' => $sales_hdr_id]);

            Log::info('Sample Sales Order Cancelled', [
                'sales_hdr_id' => $sales_hdr_id,
                'cancelled_by' => $emp_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sample sales order cancelled successfully',
                'data' => [
                    'sales_hdr_id' => $sales_hdr_id,
                    'order_status_id' => 'CANCELLED'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('=== CANCEL SAMPLE SALES ORDER FAILED ===');
            Log::error('Cancel sample sales order error: ' . $e->getMessage());
            Log::error('Stack Trace', ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel sample sales order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate sample order input
     */
    private function validateSampleOrder($data)
    {
        Log::debug('=== VALIDATING SAMPLE ORDER ===', ['data_keys' => array_keys($data)]);
        $errors = [];

        if (!isset($data['delivery_date']) || empty($data['delivery_date'])) {
            Log::warning('Validation Failed - Missing Delivery Date');
            $errors['delivery_date'] = ['The delivery date field is required'];
        }

        if (!isset($data['so_lines']) || !is_array($data['so_lines']) || count($data['so_lines']) === 0) {
            Log::warning('Validation Failed - No Line Items');
            $errors['so_lines'] = ['At least one line item is required'];
        }

        if (isset($data['so_lines']) && is_array($data['so_lines'])) {
            foreach ($data['so_lines'] as $index => $line) {
                if (!isset($line['product_id']) || empty($line['product_id'])) {
                    Log::warning('Validation Failed - Missing Product ID', ['line_index' => $index]);
                    $errors["so_lines.{$index}.product_id"] = ['Product ID is required'];
                }
                if (!isset($line['qty']) || $line['qty'] <= 0) {
                    Log::warning('Validation Failed - Invalid Quantity', ['line_index' => $index]);
                    $errors["so_lines.{$index}.qty"] = ['Quantity must be greater than 0'];
                }
            }
        }

        if (count($errors) > 0) {
            Log::warning('Sample Order Validation Failed', ['errors' => $errors]);
            return [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ];
        }

        Log::info('Sample Order Validation Passed');
        return ['success' => true];
    }

    /**
     * Check if sample order requires approval
     */
    private function checkSampleOrderApproval($total_amount, $employee_id)
    {
        Log::info('Checking Sample Order Approval Requirements', ['total_amount' => $total_amount, 'employee_id' => $employee_id]);
        // Simple logic: auto-approve if amount < 50000
        if ($total_amount < 50000) {
            Log::debug('Auto-Approve: Amount Below Threshold', ['threshold' => 50000]);
            return '0';
        }

        // Get reporting manager for approval
        Log::info('Fetching Reporting Manager for Sample Order Approval');
        $reporting_manager = DB::table('hr_employee_t')
            ->where('employee_id', $employee_id)
            ->value('reporting_manager');

        $approver = $reporting_manager ?? $employee_id;
        Log::debug('Approver Assigned for Sample Order', ['approver_id' => $approver]);
        return $approver;
    }

    /**
     * Get Approver from Reporting Manager
     * Get the reporting manager for the given employee to assign as approver
     */
    private function getApproverFromReportingManager($employee_id)
    {
        Log::info('Getting Approver from Reporting Manager', ['employee_id' => $employee_id]);
        $reporting_manager = DB::table('hr_employee_t')
            ->where('employee_id', $employee_id)
            ->select('reporting_manager')
            ->first();

        if ($reporting_manager && $reporting_manager->reporting_manager) {
            Log::debug('Reporting Manager Found', ['reporting_manager' => $reporting_manager->reporting_manager]);
            return $reporting_manager->reporting_manager;
        }

        // Fallback to current employee if no reporting manager
        Log::debug('No Reporting Manager Found - Using Current Employee as Approver', ['employee_id' => $employee_id]);
        return $employee_id;
    }

    /**
     * Calculate Free Qty from Schemes
     * Evaluates customer schemes to determine free quantity (Gift schemes)
     */
    private function calculateFreeQtyFromSchemes($product_id, $qty, $unit_price, $schemes = [])
    {
        Log::debug('Calculating Free Qty from Schemes', ['product_id' => $product_id, 'qty' => $qty, 'unit_price' => $unit_price, 'scheme_count' => count($schemes)]);

        if (empty($schemes)) {
            Log::debug('No Schemes Available - Returning 0');
            return 0;
        }

        $free_qty = 0;

        // Evaluate each scheme for the product
        foreach ($schemes as $scheme_id) {
            // Check for Quantity Based Gift schemes
            Log::debug('Checking Scheme', ['scheme_id' => $scheme_id, 'product_id' => $product_id]);

            $qty_based = DB::select(
                "SELECT * FROM s_schemes_lines_t 
                WHERE schemes_hdr_id = ? 
                AND product_id = ? 
                AND scheme_base = 'Quantity Based' 
                AND scheme_base_value_from <= ?",
                [$scheme_id, $product_id, $qty]
            );

            // Check for Price Based Gift schemes
            $price_based = DB::select(
                "SELECT * FROM s_schemes_lines_t 
                WHERE schemes_hdr_id = ? 
                AND product_id = ? 
                AND scheme_base = 'Price Based' 
                AND scheme_base_value_from <= ? 
                AND scheme_base_value_to >= ?",
                [$scheme_id, $product_id, $qty, $qty]
            );

            // If scheme found and is Gift type, calculate free qty
            if (count($qty_based) > 0) {
                $scheme_data = $qty_based[0];
                Log::debug('Qty Based Scheme Found', ['schemes_type' => $scheme_data->schemes_type]);

                if ($scheme_data->schemes_type === 'Gift') {
                    $from_qty = $scheme_data->scheme_base_value_from;
                    $free_qty = floor($qty / $from_qty);
                    Log::debug('Gift Scheme Applied - Qty Based', ['from_qty' => $from_qty, 'free_qty' => $free_qty]);
                    return $free_qty;
                }
            } elseif (count($price_based) > 0) {
                $scheme_data = $price_based[0];
                Log::debug('Price Based Scheme Found', ['schemes_type' => $scheme_data->schemes_type]);

                if ($scheme_data->schemes_type === 'Gift') {
                    $from_qty = $scheme_data->scheme_base_value_from;
                    $free_qty = floor($qty / $from_qty);
                    Log::debug('Gift Scheme Applied - Price Based', ['from_qty' => $from_qty, 'free_qty' => $free_qty]);
                    return $free_qty;
                }
            }
        }

        Log::debug('No Gift Scheme Applied - Returning 0');
        return 0;
    }

    /**
     * Get Approval Data from Approval Settings Tables
     * Replaces old APIApprovaldatacheck() function
     * 
     * Returns JSON-encoded array of approver IDs based on:
     * 1. Amount range in m_approvalsettings_line_t
     * 2. Reporting managers from hr_employee_t
     * 3. Approver IDs from m_approvalsettings_line_t
     */
    private function getApprovalDataFromSettings($module = 'soorder', $total_amount = 0, $employee_id = null)
    {
        Log::info('=== GET APPROVAL DATA FROM SETTINGS STARTED ===', [
            'module' => $module,
            'total_amount' => $total_amount,
            'employee_id' => $employee_id
        ]);

        $approvers = [];

        // Get reporting managers from hr_employee_t (for soorder: both reporting_manager and reporting_manager1)
        if ($module === 'soorder' && $employee_id) {
            Log::info('Fetching Reporting Managers for Sales Order');
            $employee = DB::table('hr_employee_t')
                ->where('employee_id', $employee_id)
                ->select('reporting_manager', 'reporting_manager1')
                ->first();

            if ($employee) {
                if ($employee->reporting_manager) {
                    $approvers[] = (int)$employee->reporting_manager;
                    Log::debug('Primary Reporting Manager Added', ['manager_id' => $employee->reporting_manager]);
                }
                if ($employee->reporting_manager1) {
                    $approvers[] = (int)$employee->reporting_manager1;
                    Log::debug('Secondary Reporting Manager Added', ['manager_id' => $employee->reporting_manager1]);
                }
            }
        } elseif ($employee_id) {
            Log::info('Fetching Reporting Manager for Other Module');
            $employee = DB::table('hr_employee_t')
                ->where('employee_id', $employee_id)
                ->select('reporting_manager')
                ->first();

            if ($employee && $employee->reporting_manager) {
                $approvers[] = (int)$employee->reporting_manager;
                Log::debug('Reporting Manager Added', ['manager_id' => $employee->reporting_manager]);
            }
        }

        // Get approval settings based on amount and module
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
}
