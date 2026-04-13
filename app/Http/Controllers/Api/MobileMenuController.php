<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MobileMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get User Menu Access for Mobile App
     * GET /api/mobile/menus
     * 
     * Returns: modules, permissions, and user hierarchy info
     * - Modules: Leave, Sales (based on user's group access)
     * - Permissions: Button-level access per module
     * - Hierarchy: Role, can_approve, subordinates_count
     */
    public function getUserMenuAccess(Request $request)
    {
        try {
            Log::info('=== MOBILE MENU ACCESS REQUEST ===');

            $user = auth('api')->user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            Log::info('Fetching Menu Access', ['user_id' => $user->id, 'employee_id' => $user->employee_id]);

            // Get user details including group and hierarchy info
            $userDetails = DB::table('tb_users')
                ->leftJoin('hr_employee_t', 'tb_users.employee_id', '=', 'hr_employee_t.employee_id')
                ->leftJoin('hr_department_t', 'hr_employee_t.department_id', '=', 'hr_department_t.department_id')
                ->leftJoin('m_position', 'hr_employee_t.position', '=', 'm_position.position_id')
                ->where('tb_users.id', $user->id)
                ->select(
                    'tb_users.id',
                    'tb_users.username',
                    'tb_users.employee_id',
                    'tb_users.group_id',
                    'hr_employee_t.first_name',
                    'hr_employee_t.last_name',
                    'hr_employee_t.employee_number',
                    'hr_employee_t.reporting_manager1',
                    'hr_employee_t.reporting_manager2',
                    'hr_employee_t.role',
                    'hr_employee_t.department_id',
                    'hr_department_t.department_name',
                    'hr_employee_t.position as position_id',
                    'm_position.position as position_name'
                )
                ->first();

            if (!$userDetails) {
                Log::warning('User Details Not Found', ['user_id' => $user->id]);
                return response()->json([
                    'status' => false,
                    'message' => 'User details not found'
                ], 404);
            }

            // Get user's group-level menu access
            $groupAccess = DB::table('a_group_menu_access_t')
                ->where('group_id', $userDetails->group_id)
                ->select('menus', 'permission')
                ->first();

            // Get user-level overrides (if any)
            $userAccess = DB::table('a_user_access_t')
                ->where('user_id', $userDetails->id)
                ->select('menus', 'permission')
                ->first();

            // Check if user is in Superadmin group
            $groupInfo = DB::table('a_m_group_t')
                ->where('group_id', $userDetails->group_id)
                ->first();

            $isSuperadmin = $groupInfo && (stripos($groupInfo->group_name, 'superadmin') !== false ||
                stripos($groupInfo->group_name, 'administrator') !== false ||
                $userDetails->group_id == 1);

            Log::info('Group Check', [
                'group_id' => $userDetails->group_id,
                'group_name' => $groupInfo->group_name ?? 'Unknown',
                'is_superadmin' => $isSuperadmin
            ]);

            // Decode menus and permissions
            $groupMenus = $groupAccess ? json_decode($groupAccess->menus, true) ?? [] : [];
            $groupPermissions = $groupAccess ? json_decode($groupAccess->permission, true) ?? [] : [];

            $userMenus = $userAccess ? json_decode($userAccess->menus, true) ?? [] : [];
            $userPermissions = $userAccess ? json_decode($userAccess->permission, true) ?? [] : [];

            // LOG: Debug what's actually in database
            Log::info('=== DATABASE ACCESS DATA ===', [
                'user_id' => $userDetails->id,
                'group_id' => $userDetails->group_id,
                'group_access_found' => $groupAccess ? 'YES' : 'NO',
                'user_access_found' => $userAccess ? 'YES' : 'NO',
                'group_menus' => $groupMenus,
                'group_permissions' => $groupPermissions,
                'user_menus' => $userMenus,
                'user_permissions' => $userPermissions,
            ]);

            // Merge: user overrides group, group is default
            $availableMenus = array_merge($groupMenus, $userMenus);
            $availablePermissions = array_merge($groupPermissions, $userPermissions);

            Log::info('Merged Access Data', [
                'menus' => $availableMenus,
                'permissions' => $availablePermissions
            ]);

            // Determine user role and hierarchy (MUST come before using it)
            $hierarchy = $this->getUserHierarchy($userDetails);
            $hierarchy['is_superadmin'] = $isSuperadmin;

            // Apply position-based access control for Sales team
            if (!$isSuperadmin) {
                $positionAccess = $this->getPositionBasedAccess($userDetails);

                Log::info('Position-Based Access', [
                    'department' => $userDetails->department_name,
                    'position' => $userDetails->position_name,
                    'access_rules' => $positionAccess
                ]);

                // Filter permissions based on position
                $availablePermissions = $this->filterPermissionsByPosition(
                    $availablePermissions,
                    $positionAccess,
                    $userDetails
                );
            }

            // Build button-level permissions (pass hierarchy for admin checks)
            $permissions = $this->getButtonPermissions($availablePermissions, $userDetails->employee_id, $hierarchy);

            // Build module access (pass button permissions to determine features and position rules)
            $modules = $this->getModuleAccess($availableMenus, $hierarchy, $userDetails->employee_id, $permissions, $userDetails, $isSuperadmin);

            Log::info('Menu Access Built', [
                'modules' => array_keys($modules),
                'hierarchy' => $hierarchy
            ]);

            return response()->json([
                'status' => true,
                'user' => [
                    'id' => $userDetails->id,
                    'username' => $userDetails->username,
                    'employee_id' => $userDetails->employee_id,
                    'name' => $userDetails->first_name,
                    'employee_number' => $userDetails->employee_number,
                    'group_id' => $userDetails->group_id,
                ],
                'hierarchy' => $hierarchy,
                'modules' => $modules,
                'permissions' => $permissions,
            ]);
        } catch (\Exception $e) {
            Log::error('Menu Access Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Error fetching menu access'
            ], 500);
        }
    }

    /**
     * Determine user's role in hierarchy based on reporting structure
     * - Uses actual subordinate count and reporting relationships
     * - No hardcoded group name checking
     */
    private function getUserHierarchy($userDetails)
    {
        Log::debug('Determining User Hierarchy', ['employee_id' => $userDetails->employee_id]);

        // Check if has subordinates (actual reporting chain)
        $subordinatesCount = DB::table('hr_employee_t')
            ->where('reporting_manager1', $userDetails->employee_id)
            ->count();

        // Check if has reporting manager
        $hasReportingManager = !empty($userDetails->reporting_manager1);

        // Get group info
        $group = DB::table('a_m_group_t')
            ->where('group_id', $userDetails->group_id)
            ->first();

        // Simple role detection based on actual hierarchy
        $isSupervisor = $subordinatesCount > 0;
        $isManager = $subordinatesCount > 0;  // Has team reporting to them
        $isAdmin = false; // Will be determined by actual permissions, not group name

        // Determine role level based on subordinate count
        $roleLevel = 1; // Default: Employee
        if ($subordinatesCount > 0 && $subordinatesCount <= 5) $roleLevel = 2; // Supervisor (small team)
        if ($subordinatesCount > 5) $roleLevel = 3; // Manager (larger team)

        return [
            'role_level' => $roleLevel,
            'role_name' => $subordinatesCount > 5 ? 'Manager' : ($subordinatesCount > 0 ? 'Supervisor' : 'Employee'),
            'is_admin' => false,  // Not used - permissions come from database
            'is_manager' => $isManager,
            'is_supervisor' => $isSupervisor,
            'has_reporting_manager' => $hasReportingManager,
            'subordinates_count' => $subordinatesCount,
            'can_approve_leaves' => $isSupervisor,
            'can_approve_sales' => $isManager,
            'group_name' => $group->group_name ?? 'Unknown',
        ];
    }

    /**
     * Get module-level access for mobile (Leave & Sales only)
     * Menu IDs:
     * - 65 = Leave
     * - 262 = Sales
     * 
     * IMPORTANT: Features are determined by actual button permissions (a_user_access_t),
     * not by role/group. This allows different managers to have different access.
     * 
     * POSITION-BASED ACCESS:
     * - Superadmin: Full access to all modules
     * - Sales L1: Only Sample orders
     * - Sales L2, L3: Sales order creation and approval
     * - Sales L4, L5: Full sales access
     */
    private function getModuleAccess($menuIds, $hierarchy, $employeeId, $buttonPermissions = [], $userDetails = null, $isSuperadmin = false)
    {
        Log::debug('Building Module Access', ['menu_ids' => array_keys($menuIds)]);

        $modules = [];

        // LEAVE MODULE (ID: 65)
        // Available to all employees
        // Database uses "leave" module name, not "LeaveController"
        $leaveButtons = $buttonPermissions['leave'] ?? [];

        Log::info('Leave Buttons from DB', ['buttons' => $leaveButtons]);

        // Get button IDs dynamically from button_names_tbl
        $leaveApproveIds = $this->getButtonIdsByAction('leave', 'approve');
        $leaveCreateIds = $this->getButtonIdsByAction('leave', 'create');
        $leaveViewIds = $this->getButtonIdsByAction('leave', 'view');

        // Check if user has any of the action buttons
        $hasApprove = !empty(array_intersect($leaveButtons, $leaveApproveIds));
        $hasCreate = !empty(array_intersect($leaveButtons, $leaveCreateIds));
        $hasView = !empty(array_intersect($leaveButtons, $leaveViewIds));

        $modules['leave'] = [
            'id' => 65,
            'name' => 'Leave',
            'icon' => 'calendar',
            'enabled' => !empty($leaveButtons),
            'features' => [
                'request_leave' => $hasCreate || !empty($leaveButtons),    // Can create or has any permission
                'view_my_leaves' => $hasView || !empty($leaveButtons),      // Can view or has any permission
                'approve_leaves' => $hasApprove,                             // Has approve button
                'view_team_leaves' => $hasApprove || $hasView,              // Can approve or view
                'bulk_approve' => $hasApprove,                               // Has approve button
            ],
            'button_ids' => $leaveButtons,                                  // Return raw button IDs for reference
            'available_actions' => $this->getButtonActions('leave', $leaveButtons), // Detailed actions
        ];

        // SALES MODULE (ID: 262)
        // Available based on menu access (not role)
        $salesEnabled = isset($menuIds[262]) || isset($menuIds[260]); // 260 = Sales, 262 = alternate ID

        if ($salesEnabled) {
            // Check actual button permissions for Sales features
            // Database uses "soorder" module name (Sales Order)
            $salesButtons = $buttonPermissions['soorder'] ?? [];

            Log::info('Sales Buttons from DB', ['buttons' => $salesButtons]);

            // Get button IDs dynamically from button_names_tbl
            $salesApproveIds = $this->getButtonIdsByAction('soorder', 'approve');
            $salesCreateIds = $this->getButtonIdsByAction('soorder', 'create');
            $salesViewIds = $this->getButtonIdsByAction('soorder', 'view');
            $salesEditIds = $this->getButtonIdsByAction('soorder', 'edit');

            // Check if user has any of the action buttons
            $hasApprove = !empty(array_intersect($salesButtons, $salesApproveIds));
            $hasCreate = !empty(array_intersect($salesButtons, $salesCreateIds));
            $hasView = !empty(array_intersect($salesButtons, $salesViewIds));
            $hasEdit = !empty(array_intersect($salesButtons, $salesEditIds));

            $modules['sales'] = [
                'id' => 262,
                'name' => 'Sales',
                'icon' => 'shopping-cart',
                'enabled' => !empty($salesButtons),
                'features' => [
                    'create_order' => $hasCreate || !empty($salesButtons),    // Can create or has any permission
                    'edit_order' => $hasEdit || !empty($salesButtons),         // Can edit or has any permission
                    'view_my_orders' => $hasView || !empty($salesButtons),     // Can view or has any permission
                    'view_all_orders' => $hasView,                              // Has view button
                    'approve_orders' => $hasApprove,                            // Has approve button
                    'manage_customers' => !empty($salesButtons),               // Has any sales permission
                    'view_reports' => $hasView || !empty($salesButtons),       // Can view or has any permission
                ],
                'button_ids' => $salesButtons,                                 // Return raw button IDs for reference
                'available_actions' => $this->getButtonActions('soorder', $salesButtons), // Detailed actions
            ];
        }

        Log::info('Modules Built', ['count' => count($modules), 'modules' => array_keys($modules)]);
        return $modules;
    }

    /**
     * Get button-level permissions (Create, Edit, Delete, Approve, etc.)
     * 
     * Returns EXACTLY what's in a_user_access_t or a_group_menu_access_t
     * Uses actual module names from database (not "Controller" names)
     */
    private function getButtonPermissions($permissionData, $employeeId, $hierarchy = null)
    {
        Log::debug('Building Button Permissions', ['employee_id' => $employeeId, 'permission_data' => $permissionData]);

        $permissions = [
            'leave' => [],           // Leave module
            'soorder' => [],         // Sales Order module
            'organization' => [],    // For future use
            'company' => [],         // For future use
        ];

        // Extract all button IDs from each module in permission data
        foreach ($permissionData as $moduleName => $buttons) {
            if (is_array($buttons) || is_object($buttons)) {
                // Convert object to array if needed
                $buttonArray = is_object($buttons) ? get_object_vars($buttons) : $buttons;

                // Extract just the button IDs (values) from the array
                $buttonIds = array_values($buttonArray);

                // Store with lowercase module name
                $permissions[strtolower($moduleName)] = array_map('intval', $buttonIds);
            }
        }

        Log::info('Button Permissions Extracted', $permissions);

        return $permissions;
    }

    /**
     * Get all available buttons for each controller
     * This is used to determine what permissions exist
     * 
     * Button IDs map to web menu items:
     * - Button 6 = "Sales Order Approval" (approve_orders feature)
     * - Button 7 = "View All Orders" (view_all_orders feature)
     * - Button 9 = "View Sales Reports" (view_reports feature)
     * - Button 10 = "Manage Customers" (manage_customers feature)
     * 
     * FUTURE: Add new modules here as they are created
     * E.g., for a new "Procurement" module, add 'ProcurementController' => [...]
     */
    private function getAllButtonsByController()
    {
        // This could be cached or fetched from DB
        // For now, we define standard buttons per module
        // NOTE: These button IDs come from button_names_tbl in your web system
        return [
            'LeaveController' => [
                1 => ['name' => 'Create Leave Request', 'admin_only' => false],
                2 => ['name' => 'Approve Leave', 'admin_only' => false],
                3 => ['name' => 'View Leave History', 'admin_only' => false],
                4 => ['name' => 'Bulk Approve Leaves', 'admin_only' => false],
                5 => ['name' => 'Cancel Leave', 'admin_only' => false],
            ],
            'SalesController' => [
                5 => ['name' => 'Create Sales Order', 'admin_only' => false],
                6 => ['name' => 'Sales Order Approval', 'admin_only' => false],  // approve_orders feature
                7 => ['name' => 'View All Orders', 'admin_only' => false],       // view_all_orders feature
                8 => ['name' => 'Delete Order', 'admin_only' => true],
                9 => ['name' => 'View Sales Reports', 'admin_only' => false],    // view_reports feature
                10 => ['name' => 'Manage Customers', 'admin_only' => false],     // manage_customers feature
            ],
            // FUTURE: Add more controllers here as new modules are created
            // 'ProcurementController' => [...],
            // 'InventoryController' => [...],
        ];
    }

    /**
     * Get button name by ID from button_names_tbl
     * This creates a mapping of button IDs to their actual names
     */
    private function getButtonNameMap()
    {
        // Cache this for performance
        static $buttonMap = null;

        if ($buttonMap === null) {
            $buttons = DB::table('button_names_tbl')
                ->select('button_id', 'button_name', 'module_name', 'click_name')
                ->get();

            $buttonMap = [];
            foreach ($buttons as $btn) {
                $buttonMap[$btn->button_id] = [
                    'name' => $btn->button_name,
                    'module' => $btn->module_name,
                    'click_name' => $btn->click_name
                ];
            }
        }

        return $buttonMap;
    }

    /**
     * Get button IDs for a specific module and action type
     * Maps button names to common actions (create, edit, view, approve, delete)
     */
    private function getButtonIdsByAction($moduleName, $action)
    {
        static $cache = [];
        $cacheKey = $moduleName . '_' . $action;

        if (isset($cache[$cacheKey])) {
            return $cache[$cacheKey];
        }

        $buttons = DB::table('button_names_tbl')
            ->where('module_name', $moduleName)
            ->select('button_id', 'button_name', 'click_name')
            ->get();

        $buttonIds = [];
        foreach ($buttons as $btn) {
            $name = strtolower($btn->button_name);
            $clickName = strtolower($btn->click_name ?? '');

            // Match action to button name patterns
            $matches = false;
            switch ($action) {
                case 'create':
                case 'add':
                    $matches = (stripos($name, 'create') !== false ||
                        stripos($name, 'add') !== false ||
                        stripos($clickName, 'create') !== false);
                    break;
                case 'edit':
                case 'update':
                    $matches = (stripos($name, 'edit') !== false ||
                        stripos($name, 'update') !== false ||
                        stripos($name, 'modify') !== false);
                    break;
                case 'view':
                case 'list':
                    $matches = (stripos($name, 'view') !== false ||
                        stripos($name, 'list') !== false ||
                        stripos($name, 'show') !== false);
                    break;
                case 'approve':
                    $matches = (stripos($name, 'approve') !== false ||
                        stripos($name, 'approval') !== false);
                    break;
                case 'delete':
                case 'remove':
                    $matches = (stripos($name, 'delete') !== false ||
                        stripos($name, 'remove') !== false);
                    break;
            }

            if ($matches) {
                $buttonIds[] = $btn->button_id;
            }
        }

        $cache[$cacheKey] = $buttonIds;
        return $buttonIds;
    }

    /**
     * Get module features based on button permissions
     * Maps button IDs from permission array to feature flags
     */
    private function getModuleFeatures($moduleKey, $buttonIds)
    {
        $buttonMap = $this->getButtonNameMap();
        $features = [];

        Log::info('Processing Module Features', [
            'module' => $moduleKey,
            'buttons' => $buttonIds,
            'count' => count($buttonIds)
        ]);

        // Common button names that map to features
        foreach ($buttonIds as $buttonId) {
            if (isset($buttonMap[$buttonId])) {
                $btnInfo = $buttonMap[$buttonId];
                $btnName = strtolower($btnInfo['name']);

                Log::debug('Button Info', [
                    'id' => $buttonId,
                    'name' => $btnName,
                    'module' => $btnInfo['module']
                ]);

                // Store button name for reference
                $features['buttons'][$buttonId] = $btnInfo['name'];
            }
        }

        return $features;
    }

    /**
     * Get detailed button actions for a module
     * Returns array of actions with button IDs and names
     */
    private function getButtonActions($moduleName, $userButtonIds)
    {
        if (empty($userButtonIds)) {
            return [];
        }

        $buttons = DB::table('button_names_tbl')
            ->whereIn('button_id', $userButtonIds)
            ->where('module_name', $moduleName)
            ->select('button_id', 'button_name', 'click_name')
            ->get();

        $actions = [];
        foreach ($buttons as $btn) {
            $actions[] = [
                'id' => $btn->button_id,
                'name' => $btn->button_name,
                'action' => $btn->click_name,
            ];
        }

        return $actions;
    }

    /**
     * Check if user can perform an action
     * POST /api/mobile/check-access
     * Body: { module: 'leave'|'sales', action: 'create'|'approve'|'delete', target_id?: int }
     */
    public function checkAccess(Request $request)
    {
        try {
            $validated = $request->validate([
                'module' => 'required|in:leave,sales',
                'action' => 'required|string',
                'target_id' => 'nullable|integer',
            ]);

            $user = auth('api')->user();
            $employee = DB::table('hr_employee_t')
                ->where('employee_id', $user->employee_id)
                ->first();

            // Get user's menu access
            $menuResponse = $this->getUserMenuAccess($request);
            $menuData = json_decode($menuResponse->getContent(), true);

            if (!$menuData['status']) {
                return response()->json(['status' => false, 'allowed' => false], 403);
            }

            $modules = $menuData['modules'];
            $hierarchy = $menuData['hierarchy'];

            // Check module access
            if (!isset($modules[$validated['module']])) {
                Log::warning('Module Not Accessible', ['module' => $validated['module'], 'user_id' => $user->id]);
                return response()->json(['status' => true, 'allowed' => false]);
            }

            $module = $modules[$validated['module']];
            $action = $validated['action'];
            $allowed = false;

            // Check action-level access
            $featureKey = $action; // e.g., 'approve_leaves', 'create_order'
            if (isset($module['features'][$featureKey])) {
                $allowed = $module['features'][$featureKey];
            }

            // Special case: checking if user can approve someone's request
            if ($allowed && in_array($action, ['approve_leave', 'approve_order']) && isset($validated['target_id'])) {
                $allowed = $this->canApproveTarget($validated['module'], $validated['target_id'], $employee->employee_id, $hierarchy);
            }

            Log::info('Access Check Result', [
                'module' => $validated['module'],
                'action' => $action,
                'allowed' => $allowed,
                'user_id' => $user->id
            ]);

            return response()->json([
                'status' => true,
                'allowed' => $allowed,
                'module' => $validated['module'],
                'action' => $action,
            ]);
        } catch (\Exception $e) {
            Log::error('Access Check Error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile()
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Error checking access'
            ], 500);
        }
    }

    /**
     * Check if user can approve a specific request
     * - Can't approve own request
     * - Can approve subordinate's request (basic hierarchy check)
     */
    private function canApproveTarget($module, $targetId, $approverId, $hierarchy)
    {
        Log::debug('Checking Approval Authority', [
            'module' => $module,
            'target_id' => $targetId,
            'approver_id' => $approverId,
            'role' => $hierarchy['role_name']
        ]);

        try {
            if ($module === 'leave') {
                $leave = DB::table('hr_leaves_t')
                    ->where('leave_id', $targetId)
                    ->select('leave_id', 'employee_id')
                    ->first();

                if (!$leave) {
                    return false;
                }

                // Can't approve own request
                if ($leave->employee_id == $approverId) {
                    return false;
                }

                // Can approve if is subordinate
                $isSubordinate = DB::table('hr_employee_t')
                    ->where('employee_id', $leave->employee_id)
                    ->where('reporting_manager1', $approverId)
                    ->exists();

                return $isSubordinate;
            }

            if ($module === 'sales') {
                $order = DB::table('s_salesorder_hdr_t')
                    ->where('sales_hdr_id', $targetId)
                    ->select('sales_hdr_id', 'employee_id')
                    ->first();

                if (!$order) {
                    return false;
                }

                // Can't approve own request
                if ($order->employee_id == $approverId) {
                    return false;
                }

                // Can approve if is subordinate
                $isSubordinate = DB::table('hr_employee_t')
                    ->where('employee_id', $order->employee_id)
                    ->where('reporting_manager1', $approverId)
                    ->exists();

                return $isSubordinate;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Approval Check Error', [
                'error' => $e->getMessage(),
                'module' => $module
            ]);
            return false;
        }
    }

    /**
     * Get Employee Details API
     * GET /api/employee/{employee_id}
     * 
     * Returns complete employee information including:
     * - Department details
     * - Position/Designation
     * - Reporting Manager 1 and 2
     * - Contact info
     */
    public function getEmployeeDetails(Request $request, $employeeId)
    {
        try {
            Log::info('Fetching Employee Details', ['employee_id' => $employeeId]);

            // Get employee with all related data
            $employee = DB::table('hr_employee_t')
                ->leftJoin('hr_department_t', 'hr_employee_t.department_id', '=', 'hr_department_t.department_id')
                ->leftJoin('m_position', 'hr_employee_t.position', '=', 'm_position.position_id')
                ->leftJoin('hr_employee_t as rm1', 'hr_employee_t.reporting_manager1', '=', 'rm1.employee_id')
                ->leftJoin('hr_employee_t as rm2', 'hr_employee_t.reporting_manager2', '=', 'rm2.employee_id')
                ->where('hr_employee_t.employee_id', $employeeId)
                ->select(
                    'hr_employee_t.employee_id',
                    'hr_employee_t.employee_number',
                    'hr_employee_t.first_name',
                    'hr_employee_t.last_name',
                    'hr_employee_t.email',
                    'hr_employee_t.mobile',
                    'hr_employee_t.role',
                    'hr_employee_t.active',
                    // Department info
                    'hr_employee_t.department_id',
                    'hr_department_t.department_name',
                    'hr_department_t.department_code',
                    // Position info
                    'hr_employee_t.position as position_id',
                    'm_position.position as position_name',
                    'm_position.position_code',
                    // Reporting Manager 1
                    'hr_employee_t.reporting_manager1 as manager1_id',
                    'rm1.employee_number as manager1_number',
                    'rm1.first_name as manager1_first_name',
                    'rm1.last_name as manager1_last_name',
                    'rm1.email as manager1_email',
                    // Reporting Manager 2
                    'hr_employee_t.reporting_manager2 as manager2_id',
                    'rm2.employee_number as manager2_number',
                    'rm2.first_name as manager2_first_name',
                    'rm2.last_name as manager2_last_name',
                    'rm2.email as manager2_email'
                )
                ->first();

            if (!$employee) {
                return response()->json([
                    'status' => false,
                    'message' => 'Employee not found'
                ], 404);
            }

            // Get user account info if exists
            $userAccount = DB::table('tb_users')
                ->where('employee_id', $employeeId)
                ->select('id', 'username', 'group_id')
                ->first();

            // Get group name if user account exists
            $groupName = null;
            if ($userAccount) {
                $group = DB::table('a_m_group_t')
                    ->where('group_id', $userAccount->group_id)
                    ->first();
                $groupName = $group->group_name ?? null;
            }

            // Determine position level for Sales team
            $positionLevel = $this->extractPositionLevel($employee->position_name);

            // Build response
            $response = [
                'status' => true,
                'employee' => [
                    'id' => $employee->employee_id,
                    'employee_number' => $employee->employee_number,
                    'name' => trim($employee->first_name . ' ' . $employee->last_name),
                    'first_name' => $employee->first_name,
                    'last_name' => $employee->last_name,
                    'email' => $employee->email,
                    'mobile' => $employee->mobile,
                    'role' => $employee->role,
                    'active' => $employee->active,
                ],
                'department' => [
                    'id' => $employee->department_id,
                    'name' => $employee->department_name,
                    'code' => $employee->department_code,
                ],
                'position' => [
                    'id' => $employee->position_id,
                    'name' => $employee->position_name,
                    'code' => $employee->position_code,
                    'level' => $positionLevel, // L1, L2, L3, L4, L5 or null
                ],
                'reporting_manager1' => $employee->manager1_id ? [
                    'id' => $employee->manager1_id,
                    'employee_number' => $employee->manager1_number,
                    'name' => trim($employee->manager1_first_name . ' ' . $employee->manager1_last_name),
                    'email' => $employee->manager1_email,
                ] : null,
                'reporting_manager2' => $employee->manager2_id ? [
                    'id' => $employee->manager2_id,
                    'employee_number' => $employee->manager2_number,
                    'name' => trim($employee->manager2_first_name . ' ' . $employee->manager2_last_name),
                    'email' => $employee->manager2_email,
                ] : null,
                'user_account' => $userAccount ? [
                    'user_id' => $userAccount->id,
                    'username' => $userAccount->username,
                    'group_id' => $userAccount->group_id,
                    'group_name' => $groupName,
                ] : null,
            ];

            Log::info('Employee Details Retrieved', [
                'employee_id' => $employeeId,
                'department' => $employee->department_name,
                'position' => $employee->position_name,
                'level' => $positionLevel
            ]);

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Employee Details Error', [
                'error' => $e->getMessage(),
                'employee_id' => $employeeId
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Error fetching employee details'
            ], 500);
        }
    }

    /**
     * Extract position level from position name
     * Looks for L1, L2, L3, L4, L5 in position name
     */
    private function extractPositionLevel($positionName)
    {
        if (empty($positionName)) {
            return null;
        }

        // Check for L1-L5 pattern
        if (preg_match('/\bL([1-5])\b/i', $positionName, $matches)) {
            return 'L' . $matches[1];
        }

        // Check for Level 1-5 pattern
        if (preg_match('/\bLevel\s*([1-5])\b/i', $positionName, $matches)) {
            return 'L' . $matches[1];
        }

        return null;
    }

    /**
     * Get position-based access rules for employee
     * Returns module and feature restrictions based on department and position level
     */
    private function getPositionBasedAccess($userDetails)
    {
        $department = strtolower($userDetails->department_name ?? '');
        $positionLevel = $this->extractPositionLevel($userDetails->position_name);

        $access = [
            'department' => $userDetails->department_name,
            'position' => $userDetails->position_name,
            'level' => $positionLevel,
            'allowed_modules' => [],
            'module_features' => [],
        ];

        // Check if Sales team/department
        $isSalesTeam = (stripos($department, 'sales') !== false ||
            stripos($department, 'marketing') !== false);

        if ($isSalesTeam && $positionLevel) {
            // Sales Team Position-Based Rules
            switch ($positionLevel) {
                case 'L1':
                    // L1: Only Sample Orders
                    $access['allowed_modules'] = ['sample_orders'];
                    $access['module_features'] = [
                        'sales' => [
                            'sample_orders_only' => true,
                            'sales_orders' => false,
                            'approval' => false,
                            'view_all' => false,
                        ]
                    ];
                    break;

                case 'L2':
                case 'L3':
                    // L2, L3: Sales Order Creation and Approval
                    $access['allowed_modules'] = ['sales', 'leave'];
                    $access['module_features'] = [
                        'sales' => [
                            'sample_orders_only' => false,
                            'create_order' => true,
                            'edit_order' => true,
                            'approval' => true,
                            'view_all' => true,
                            'view_reports' => false,
                        ]
                    ];
                    break;

                case 'L4':
                case 'L5':
                    // L4, L5: Full Sales Access
                    $access['allowed_modules'] = ['sales', 'leave'];
                    $access['module_features'] = [
                        'sales' => [
                            'sample_orders_only' => false,
                            'create_order' => true,
                            'edit_order' => true,
                            'approval' => true,
                            'view_all' => true,
                            'view_reports' => true,
                            'manage_customers' => true,
                            'full_access' => true,
                        ]
                    ];
                    break;

                default:
                    // No specific level - use default permissions
                    $access['allowed_modules'] = ['sales', 'leave'];
                    break;
            }
        } else {
            // Non-sales team: use standard permissions
            $access['allowed_modules'] = ['leave'];
        }

        return $access;
    }

    /**
     * Filter permissions based on position-based access rules
     * Restricts modules/features based on Sales team position (L1-L5)
     */
    private function filterPermissionsByPosition($permissions, $positionAccess, $userDetails)
    {
        // If no position restrictions, return original permissions
        if (empty($positionAccess['level']) || empty($positionAccess['module_features'])) {
            return $permissions;
        }

        $filtered = $permissions;

        // Apply Sales module restrictions for L1 (Sample orders only)
        if ($positionAccess['level'] === 'L1' && isset($positionAccess['module_features']['sales'])) {
            $salesRules = $positionAccess['module_features']['sales'];

            if ($salesRules['sample_orders_only']) {
                // For L1, restrict to only sample order buttons
                // You may need to identify specific button IDs for sample orders
                Log::info('L1 Sales Restriction Applied', [
                    'employee_id' => $userDetails->employee_id,
                    'original_sales_buttons' => $filtered['soorder'] ?? []
                ]);

                // Keep only sample order related buttons (adjust button IDs as needed)
                // This is a placeholder - update with actual sample order button IDs from your DB
                $sampleOrderButtonIds = $this->getButtonIdsByAction('sosampleorder', 'create');

                if (!empty($sampleOrderButtonIds)) {
                    $filtered['soorder'] = array_intersect($filtered['soorder'] ?? [], $sampleOrderButtonIds);
                }
            }
        }

        return $filtered;
    }
}
