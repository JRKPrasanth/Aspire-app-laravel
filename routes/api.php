<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\SalesController;
use App\Http\Controllers\Api\MobileMenuController;
use App\Http\Controllers\Api\PurchaseQuotationController;
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Controllers\Api\PurchaseInvoiceController;
use App\Http\Controllers\Api\FileUploadController;
use App\Http\Controllers\Api\AppController;
use App\Http\Controllers\Api\DeviceTrackingController;
use App\Http\Controllers\Api\UserAnalyticsController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\WorkorderController;
use App\Http\Controllers\Api\MrpController;
use App\Http\Controllers\Api\JobCardController;
use App\Http\Controllers\Api\MaterialController;

Route::post('test-log', function (Request $request) {
    Log::info('Test log route hit', ['request' => $request->all()]);
    return response()->json(['prasanth ok' => true]);
});

// App Version Check - Public route (no authentication required)
Route::get('app/version/check', [AppController::class, 'checkVersion']);

// App Version Management - Admin only (add authentication middleware as needed)
Route::get('app/versions', [AppController::class, 'getAllVersions']);
Route::post('app/versions', [AppController::class, 'createVersion']);
Route::put('app/versions/{id}', [AppController::class, 'updateVersion']);

/**
 * ============================================================
 * WORKORDER APIs
 * ============================================================
 */
Route::middleware('auth:api')->prefix('workorders')->group(function () {
    // products/my must be declared before /{id} to avoid route conflict
    Route::get('/products/my', [WorkorderController::class, 'getMyProducts']);
    Route::get('/', [WorkorderController::class, 'index']);
    Route::post('/', [WorkorderController::class, 'store']);
    Route::get('/{id}', [WorkorderController::class, 'show']);
});

/**
 * ============================================================
 * MRP (MATERIAL REQUIREMENT PLAN) APIs
 * ============================================================
 */
Route::middleware('auth:api')->prefix('mrp')->group(function () {
    // workorders/pending must be declared before workorders/{id} to avoid route conflict
    Route::get('/workorders', [MrpController::class, 'getWorkordersForMrp']);
    Route::get('/workorders/{id}/lines', [MrpController::class, 'getWorkorderLines']);
    Route::get('/', [MrpController::class, 'index']);
    Route::post('/', [MrpController::class, 'store']);
    Route::get('/{id}', [MrpController::class, 'show']);
    Route::put('/{id}/approve', [MrpController::class, 'approve']);
});

/**
 * ============================================================
 * JOB CARD APIs
 * ============================================================
 */
// WIP Stats (combined MRP + Job Card counts for the home dashboard)
Route::middleware('auth:api')->get('/wip/stats', [MrpController::class, 'wipStats']);

Route::middleware('auth:api')->prefix('jobcards')->group(function () {
    Route::get('/approved-plans', [JobCardController::class, 'getApprovedPlans']);
    Route::get('/', [JobCardController::class, 'index']);
    Route::post('/', [JobCardController::class, 'store']);
    Route::get('/{id}', [JobCardController::class, 'show']);
});

/**
 * ============================================================
 * MATERIAL ISSUE / RECEIVE / STORE MOVE / COMPLETION APIs
 * ============================================================
 */
Route::middleware('auth:api')->prefix('material')->group(function () {
    // Material Issue
    Route::get('/issue/list',             [MaterialController::class, 'getMaterialIssueList']);
    Route::get('/issue/{jobId}/lines',    [MaterialController::class, 'getMaterialIssueLines']);
    Route::post('/issue/{jobId}',         [MaterialController::class, 'saveMaterialIssue']);
    Route::get('/product/{productId}/qoh', [MaterialController::class, 'getProductQoh']);

    // Material Receive / Acknowledge
    Route::get('/receive/list',           [MaterialController::class, 'getMaterialReceiveList']);
    Route::post('/receive/{jobId}',       [MaterialController::class, 'saveMaterialReceive']);

    // Packing Job Card Status (Store Move)
    Route::get('/packing-status',         [MaterialController::class, 'getPackingJobCardStatus']);
    Route::get('/storemove/{jobId}/details', [MaterialController::class, 'getStoreMoveDetails']);
    Route::get('/subinventories/{subinvId}/locators', [MaterialController::class, 'getLocators']);
    Route::post('/storemove/{jobId}',     [MaterialController::class, 'saveStoreMoveEntry']);

    // Job Card Completion
    Route::get('/completion/list',        [MaterialController::class, 'getCompletionList']);
    Route::post('/completion/{jobId}',    [MaterialController::class, 'completeJobCard']);
});

/**
 * ============================================================
 * DEVICE TRACKING & ANALYTICS APIs
 * ============================================================
 */
// Device Registration & Tracking - Protected with API authentication
Route::middleware('auth:api')->group(function () {
    Route::post('devices/register', [DeviceTrackingController::class, 'registerDevice']);
    Route::post('devices/check-duplicate', [DeviceTrackingController::class, 'checkDuplicate']);
    Route::put('devices/{deviceId}/update-activity', [DeviceTrackingController::class, 'updateDeviceActivity']);
    Route::get('devices/statistics', [DeviceTrackingController::class, 'getStatistics']);
    Route::get('users/{userId}/devices', [DeviceTrackingController::class, 'getUserDevices']);

    // Analytics & User Engagement
    Route::post('analytics/batch', [UserAnalyticsController::class, 'logBatchEvents']);
    Route::post('user/session/start', [UserAnalyticsController::class, 'startSession']);
    Route::put('user/session/{sessionId}/end', [UserAnalyticsController::class, 'endSession']);
    Route::post('user/activity/log', [UserAnalyticsController::class, 'logActivity']);
    Route::get('user/{userId}/engagement', [UserAnalyticsController::class, 'getUserEngagement']);
    Route::get('user/{userId}/activity-log', [UserAnalyticsController::class, 'getUserActivityLog']);
    Route::get('analytics/dashboard', [UserAnalyticsController::class, 'getDashboard']);
});

Route::post('authlogin', [AuthController::class, 'login']);
Route::post('refresh-token', [AuthController::class, 'refreshToken']);
Route::get('me', [AuthController::class, 'me']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::post('leave-balances', [LeaveController::class, 'getLeaveBalances']);
Route::post('leave-types', [LeaveController::class, 'getLeaveTypes']);
Route::post('available-leave-types', [LeaveController::class, 'getAvailableLeaveTypes']);
Route::get('leave-modes', [LeaveController::class, 'getLeaveModes']);
Route::post('get_user_by_employee_number', [AuthController::class, 'getUserByEmployeeNumber']);
Route::post('leave_request', [LeaveController::class, 'save_leave_request']);
Route::get('approvals/pending', [LeaveController::class, 'pendingApprovals']);
Route::get('approvals/summary', [LeaveController::class, 'approvalsSummary']);
Route::get('approvals/{id}', [LeaveController::class, 'getApproval']);
Route::post('approvals/{id}/decision', [LeaveController::class, 'decideApproval']);
Route::get('employee/{id}', [AuthController::class, 'getEmployeeById']);

// Get employee address details (current and permanent address)
Route::get('employee/{id}/address', [AuthController::class, 'getEmployeeAddressDetails']);

// Get department employees with manager check (auto-filters based on manager status)
Route::get('department/{departmentId}/employees', [AuthController::class, 'getDepartmentEmployees'])
    ->middleware('auth:api');

// Employee leave history (API) - returns JSON history for an employee with status filtering
Route::get('employee/{id}/leave-history', [LeaveController::class, 'getEmployeeLeaveHistory']);

// Employee dashboard (API) - attendance events + leave summary + activity chart data
Route::get('employee/{id}/dashboard', [DashboardController::class, 'employeeDashboard'])
    ->middleware('auth:api');

// Get permission quota for a specific month (API) - check remaining requests and hours
Route::get('employee/{id}/permission-quota', [LeaveController::class, 'getPermissionQuota']);

// Get OD (Over Duty) details for an employee (API) - for OD tracking and comp-off eligibility
Route::get('employee/{id}/od-details', [LeaveController::class, 'getODDetails']);

// Check initiated leaves in a specific month (API) - for blocking multiple requests in same month
Route::get('employee/{id}/initiated-leaves-month', [LeaveController::class, 'getInitiatedLeavesInMonth']);

// Get Casual Leave (CL) accrual status - counts initiated/pending/approved against monthly eligible quota
Route::get('employee/{id}/cl-accrual', [LeaveController::class, 'getCLAccrualStatus']);

// Get leave approver (reporting manager) for an employee
Route::get('leave/approver', [LeaveController::class, 'getLeaveApprover']);

Route::get('leave/ineligible-dates', [LeaveController::class, 'getIneligibleLeaveDates']);

/**
 * ============================================================
 * MOBILE MENU & ACCESS CONTROL APIS
 * ============================================================
 */
Route::prefix('mobile')->middleware('auth:api')->group(function () {
    // Get user's module access and permissions for mobile app
    Route::get('menus', [MobileMenuController::class, 'getUserMenuAccess']);

    // Check if user can perform specific action
    Route::post('check-access', [MobileMenuController::class, 'checkAccess']);
});

/**
 * ============================================================
 * EMPLOYEE DETAILS API
 * Returns employee info with department, position, and reporting managers
 * ============================================================
 */
Route::get('employee/{employeeId}/details', [MobileMenuController::class, 'getEmployeeDetails'])
    ->middleware('auth:api');

/**
 * Sales APIs
 * All endpoints protected with auth:api middleware
 */
Route::prefix('sales')->middleware('auth:api')->group(function () {
    // Get all customers for dropdown
    Route::get('/customers', [SalesController::class, 'getCustomersList']);

    // Create new sales order
    Route::post('/create', [SalesController::class, 'createSalesOrder']);

    // Get products list for dropdown (by pricelist)
    Route::get('/pricelist/{pricelistId}/products', [SalesController::class, 'getProductsByPricelist']);

    // Get product data with pricing and tax (single product)
    Route::get('/product/{productId}', [SalesController::class, 'getProductData']);

    // Get customer data for SO creation
    Route::get('/customer/{customerId}', [SalesController::class, 'getCustomerData']);

    // Get customer's products grouped by product group (with filters)
    Route::get('/customer/{customerId}/products-grouped', [SalesController::class, 'getCustomerProductsGrouped']);

    // Get sales orders list (all statuses or filtered by status query param)
    Route::get('/orders', [SalesController::class, 'getSalesOrdersList']);

    // Get pending sales orders (INITIATED status - for approval)
    Route::get('/orders/pending', [SalesController::class, 'getPendingSalesOrders']);

    // Get approved sales orders
    Route::get('/orders/approved', [SalesController::class, 'getApprovedSalesOrders']);

    // Get all sales orders with status summary (List view - essential data only)
    Route::get('/orders/all', [SalesController::class, 'getAllSalesOrders']);

    // Get single sales order by ID (Full details with line items)
    Route::get('/orders/{id}', [SalesController::class, 'getSalesOrderById']);

    // Approve or reject sales order
    Route::post('/approve', [SalesController::class, 'approveSalesOrder']);

    /**
     * ============================================================
     * SAMPLE SALES ORDER APIS
     * ============================================================
     */

    // Get sample products for sample order creation
    Route::get('/sample-products', [SalesController::class, 'getSampleProducts']);

    // Get pending sample orders for approval (for approvers)
    Route::get('/sample-orders/pending-approvals', [SalesController::class, 'getPendingSampleApprovals']);

    // Create sample sales order
    Route::post('/sample-orders', [SalesController::class, 'createSampleSalesOrder']);

    // Get sample sales orders list (for employee)
    Route::get('/sample-orders', [SalesController::class, 'getSampleSalesOrdersList']);

    // Get single sample sales order details
    Route::get('/sample-orders/{sales_hdr_id}', [SalesController::class, 'getSampleSalesOrderDetails']);

    // Update sample sales order
    Route::put('/sample-orders/{sales_hdr_id}', [SalesController::class, 'updateSampleSalesOrder']);

    // Approve sample sales order
    Route::post('/sample-orders/{sales_hdr_id}/approve', [SalesController::class, 'approveSampleSalesOrder']);

    // Reject sample sales order
    Route::post('/sample-orders/{sales_hdr_id}/reject', [SalesController::class, 'rejectSampleSalesOrder']);

    // Cancel/Delete sample sales order
    Route::delete('/sample-orders/{sales_hdr_id}', [SalesController::class, 'cancelSampleSalesOrder']);
});

/**
 * ============================================================
 * PURCHASE QUOTATION APIs
 * All endpoints protected with auth:api middleware
 * ============================================================
 */
Route::prefix('purchase')->middleware('auth:api')->group(function () {

    /**
     * ============================================================
     * CONSOLIDATED SUPPLIER ENDPOINTS (OPTIMIZED)
     * These combine multiple API calls into single efficient requests
     * ============================================================
     */
    // Get comprehensive supplier details in ONE call
    // Returns: supplier info, sites, payment terms, delivery terms, insurance, freight carriers, addresses, pricelist
    // REPLACES: ~8-10 separate API calls to individual endpoint
    Route::get('/supplier/{supplierId}/comprehensive', [PurchaseQuotationController::class, 'getComprehensiveSupplierDetails']);

    // Get supplier products with supplier-specific pricing and tax
    // Returns: Products with pricelist pricing, manufacturer part numbers, tax group based on supplier site
    // REPLACES: /products + /product/{id}/details for each product
    Route::get('/supplier/{supplierId}/products', [PurchaseQuotationController::class, 'getSupplierProducts']);

    /**
     * ============================================================
     * MASTER DATA ENDPOINTS (For Dropdowns)
     * Note: Keep these for backward compatibility if needed
     * ============================================================
     */
    // Get all suppliers
    Route::get('/suppliers', [PurchaseQuotationController::class, 'getSuppliers']);

    // Get supplier sites by supplier_id
    Route::get('/supplier/{supplierId}/sites', [PurchaseQuotationController::class, 'getSupplierSites']);

    // Get supplier details with defaults (pricelist, payment terms, etc.)
    Route::get('/supplier/{supplierId}/details', [PurchaseQuotationController::class, 'getSupplierDetails']);

    // Get freight carriers (Purchase type)
    Route::get('/freight-carriers', [PurchaseQuotationController::class, 'getFreightCarriers']);

    // Get freight terms (FOB Points)
    Route::get('/freight-terms', [PurchaseQuotationController::class, 'getFreightTerms']);

    // Get payment terms
    Route::get('/payment-terms', [PurchaseQuotationController::class, 'getPaymentTerms']);

    // Get payment methods
    Route::get('/payment-methods', [PurchaseQuotationController::class, 'getPaymentMethods']);

    // Get insurance terms
    Route::get('/insurance-terms', [PurchaseQuotationController::class, 'getInsuranceTerms']);

    // Get delivery terms (Purchase type)
    Route::get('/delivery-terms', [PurchaseQuotationController::class, 'getDeliveryTerms']);

    /**
     * PRODUCT ENDPOINTS
     */
    // Get products for purchase quotation
    Route::get('/products', [PurchaseQuotationController::class, 'getProducts']);

    // Get product details (when product is selected)
    Route::get('/product/{productId}/details', [PurchaseQuotationController::class, 'getProductDetails']);

    // Get previous purchase cost for a product (recent invoices)
    Route::get('/product/{productId}/previous-cost', [PurchaseQuotationController::class, 'getProductPreviousCost']);

    // Get tax details for HSN code
    Route::get('/tax-details', [PurchaseQuotationController::class, 'getTaxDetails']);

    // Get HSN/SAC codes
    Route::get('/hsn-codes', [PurchaseQuotationController::class, 'getHsnCodes']);

    // Get projects
    Route::get('/projects', [PurchaseQuotationController::class, 'getProjects']);

    // Get tax groups
    Route::get('/tax-groups', [PurchaseQuotationController::class, 'getTaxGroups']);

    // Get UOM codes
    Route::get('/uom-codes', [PurchaseQuotationController::class, 'getUomCodes']);

    /**
     * PAGINATED PRODUCT ENDPOINTS (for mobile infinite-scroll picker)
     * Separate endpoints for Quotation vs Order – server-side search + pagination
     * Query params: page, per_page (default 50), search, supplier_site_id, pricelist_id
     */
    // Paginated products for Purchase Quotation
    Route::get('/quotation/supplier/{supplierId}/products', [PurchaseQuotationController::class, 'getQuotationSupplierProductsPaginated']);

    // Paginated products for Purchase Order
    Route::get('/order/supplier/{supplierId}/products', [PurchaseOrderController::class, 'getOrderSupplierProductsPaginated']);

    /**
     * PURCHASE QUOTATION CRUD
     */
    // List all quotations (with optional status filter)
    Route::get('/quotes', [PurchaseQuotationController::class, 'index']);

    //Get All Purchase Quotations
    Route::get('/quotes/all', [PurchaseQuotationController::class, 'getAllPurchaseQuotations']);

    // Get pending quotations for approval
    Route::get('/quotes/pending', [PurchaseQuotationController::class, 'getPendingQuotes']);

    // Get quotation by ID (for PurchaseQuoteList.tsx - using /quotes/{id})
    Route::get('/quotes/{id}', [PurchaseQuotationController::class, 'getPurchaseQuotationByID']);

    // Create purchase quotation (header + lines)
    Route::post('/quote', [PurchaseQuotationController::class, 'store']);

    // Update purchase quotation (header + lines)
    Route::put('/quote/{id}', [PurchaseQuotationController::class, 'update']);

    // Get quotation attachments
    Route::get('/quote/{id}/attachments', [PurchaseQuotationController::class, 'getQuotationAttachments']);

    // Approve / Reject purchase quotation
    Route::post('/quote/{id}/approve', [PurchaseQuotationController::class, 'approve']);

    // Delete purchase quotation
    Route::delete('/quote/{id}', [PurchaseQuotationController::class, 'destroy']);

    // Prepare PO creation from an APPROVED quotation (validates + returns data)
    Route::get('/quotes/{id}/prepare-po', [PurchaseOrderController::class, 'prepareFromQuotation']);

    /**
     * ============================================================
     * PURCHASE ORDER APIs
     * ============================================================
     */
    // Get all purchase orders with pagination
    Route::get('/orders/all', [PurchaseOrderController::class, 'getAllPurchaseOrders']);

    // Get pending purchase orders for approval
    Route::get('/orders/pending', [PurchaseOrderController::class, 'getPendingOrders']);

    // Get purchase order by ID with full details
    Route::get('/orders/{id}', [PurchaseOrderController::class, 'getPurchaseOrderById']);

    // Create new purchase order
    Route::post('/orders', [PurchaseOrderController::class, 'store']);

    // Update purchase order
    Route::put('/orders/{id}', [PurchaseOrderController::class, 'update']);

    // Delete purchase order
    Route::delete('/orders/{id}', [PurchaseOrderController::class, 'destroy']);

    // Approve or reject purchase order
    Route::post('/orders/{id}/approve', [PurchaseOrderController::class, 'approve']);

    /**
     * ============================================================
     * PURCHASE INVOICE APIs
     * ============================================================
     */
    // Get all purchase invoices with pagination
    Route::get('/invoices/all', [PurchaseInvoiceController::class, 'getAllPurchaseInvoices']);

    // Get purchase invoice by ID with full details
    Route::get('/invoices/{id}', [PurchaseInvoiceController::class, 'getPurchaseInvoiceById']);

    // Create new purchase invoice
    Route::post('/invoices', [PurchaseInvoiceController::class, 'store']);

    // Approve or reject purchase invoice
    Route::post('/invoices/{id}/approve', [PurchaseInvoiceController::class, 'approve']);
});

/**
 * ============================================================
 * FILE UPLOAD ROUTES (JWT Protected)
 * ============================================================
 */
Route::middleware('auth:api')->prefix('files')->group(function () {
    // Upload file
    Route::post('/upload', [FileUploadController::class, 'upload']);

    // Get files for entity
    Route::get('/', [FileUploadController::class, 'getFiles']);

    // Download file
    Route::get('/{id}/download', [FileUploadController::class, 'download']);

    // Delete file
    Route::delete('/{id}', [FileUploadController::class, 'delete']);

    // Cleanup old files (admin only)
    Route::delete('/cleanup', [FileUploadController::class, 'cleanup']);
});
