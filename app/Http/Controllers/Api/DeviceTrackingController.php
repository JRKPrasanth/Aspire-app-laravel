<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DeviceTrackingController extends Controller
{
    /**
     * Register a new device installation
     * 
     * POST /api/devices/register
     * 
     * Request Body:
     * {
     *   "userId": "user_123",
     *   "deviceId": "device_abc",
     *   "osType": "android",
     *   "osVersion": "13",
     *   "appVersion": "1.0.0",
     *   "buildNumber": "1",
     *   "deviceModel": "Samsung Galaxy S21",
     *   "deviceBrand": "Samsung",
     *   "screenSize": "1080x2400",
     *   "uniqueDeviceFingerprint": "fp_123abc",
     *   "installationDate": "2026-03-11T10:00:00Z"
     * }
     */
    public function registerDevice(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'userId' => 'required|string',
                'deviceId' => 'required|string',
                'osType' => 'required|in:ios,android,web',
                'osVersion' => 'required|string',
                'appVersion' => 'required|string',
                'buildNumber' => 'nullable|string',
                'deviceModel' => 'nullable|string',
                'deviceBrand' => 'nullable|string',
                'screenSize' => 'nullable|string',
                'uniqueDeviceFingerprint' => 'nullable|string',
                'installationDate' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed',
                    'details' => $validator->errors(),
                ], 422);
            }

            $data = $validator->validated();

            // Check if device already exists
            $existingDevice = DB::table('device_registrations')
                ->where('device_id', $data['deviceId'])
                ->first();

            if ($existingDevice) {
                // Update existing device
                DB::table('device_registrations')
                    ->where('device_id', $data['deviceId'])
                    ->update([
                        'user_id' => $data['userId'],
                        'os_version' => $data['osVersion'],
                        'app_version' => $data['appVersion'],
                        'build_number' => $data['buildNumber'] ?? null,
                        'last_active_date' => now(),
                        'is_active' => true,
                        'updated_at' => now(),
                    ]);

                // Create installation record
                $this->createInstallationRecord(
                    $data['deviceId'],
                    $data['userId'],
                    $data['appVersion']
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Device updated successfully',
                    'device_id' => $data['deviceId'],
                    'is_new' => false,
                ]);
            }

            // Register new device
            DB::table('device_registrations')->insert([
                'device_id' => $data['deviceId'],
                'user_id' => $data['userId'],
                'os_type' => $data['osType'],
                'os_version' => $data['osVersion'],
                'app_version' => $data['appVersion'],
                'build_number' => $data['buildNumber'] ?? null,
                'device_model' => $data['deviceModel'] ?? null,
                'device_brand' => $data['deviceBrand'] ?? null,
                'screen_size' => $data['screenSize'] ?? null,
                'unique_device_fingerprint' => $data['uniqueDeviceFingerprint'] ?? null,
                'installation_date' => $data['installationDate'],
                'last_active_date' => now(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create installation record
            $this->createInstallationRecord(
                $data['deviceId'],
                $data['userId'],
                $data['appVersion']
            );

            return response()->json([
                'success' => true,
                'message' => 'Device registered successfully',
                'device_id' => $data['deviceId'],
                'is_new' => true,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error registering device', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to register device',
                'message' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get user's device history
     * 
     * GET /api/users/{userId}/devices
     */
    public function getUserDevices($userId)
    {
        try {
            $devices = DB::table('device_registrations')
                ->where('user_id', $userId)
                ->orderBy('installation_date', 'desc')
                ->get();

            $deviceHistory = [];
            foreach ($devices as $device) {
                $installationRecords = DB::table('installation_records')
                    ->where('device_id', $device->device_id)
                    ->where('user_id', $userId)
                    ->orderBy('installation_timestamp', 'desc')
                    ->get();

                $deviceHistory[] = [
                    'deviceId' => $device->device_id,
                    'osType' => $device->os_type,
                    'osVersion' => $device->os_version,
                    'appVersion' => $device->app_version,
                    'deviceModel' => $device->device_model,
                    'deviceBrand' => $device->device_brand,
                    'installationDate' => $device->installation_date,
                    'lastActiveDate' => $device->last_active_date,
                    'isActive' => $device->is_active,
                    'installationRecords' => $installationRecords,
                ];
            }

            return response()->json([
                'success' => true,
                'userId' => $userId,
                'totalDevices' => count($devices),
                'devices' => $deviceHistory,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching user devices', [
                'userId' => $userId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch user devices',
            ], 500);
        }
    }

    /**
     * Check for duplicate installation
     * 
     * POST /api/devices/check-duplicate
     * 
     * Request Body:
     * {
     *   "userId": "user_123",
     *   "deviceId": "device_abc",
     *   "version": "1.0.0"
     * }
     */
    public function checkDuplicate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'userId' => 'required|string',
                'deviceId' => 'required|string',
                'version' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed',
                    'details' => $validator->errors(),
                ], 422);
            }

            $data = $validator->validated();

            // Check if this exact combination exists
            $duplicate = DB::table('installation_records')
                ->where('user_id', $data['userId'])
                ->where('device_id', $data['deviceId'])
                ->where('version', $data['version'])
                ->where('status', 'active')
                ->exists();

            return response()->json([
                'success' => true,
                'isDuplicate' => $duplicate,
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking duplicate installation', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to check duplicate',
            ], 500);
        }
    }

    /**
     * Update device last active date
     * 
     * PUT /api/devices/{deviceId}/update-activity
     */
    public function updateDeviceActivity($deviceId)
    {
        try {
            $updated = DB::table('device_registrations')
                ->where('device_id', $deviceId)
                ->update([
                    'last_active_date' => now(),
                    'updated_at' => now(),
                ]);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'error' => 'Device not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Device activity updated',
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating device activity', [
                'deviceId' => $deviceId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to update device activity',
            ], 500);
        }
    }

    /**
     * Get installation statistics
     * 
     * GET /api/devices/statistics
     */
    public function getStatistics(Request $request)
    {
        try {
            $userId = $request->input('userId');

            $query = DB::table('device_registrations');

            if ($userId) {
                $query->where('user_id', $userId);
            }

            $totalDevices = $query->count();
            $activeDevices = (clone $query)->where('is_active', true)->count();

            // OS distribution
            $osDistribution = DB::table('device_registrations')
                ->select('os_type', DB::raw('count(*) as count'))
                ->when($userId, function ($q) use ($userId) {
                    return $q->where('user_id', $userId);
                })
                ->groupBy('os_type')
                ->get();

            // Version distribution
            $versionDistribution = DB::table('device_registrations')
                ->select('app_version', DB::raw('count(*) as count'))
                ->when($userId, function ($q) use ($userId) {
                    return $q->where('user_id', $userId);
                })
                ->groupBy('app_version')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();

            // Recent installations (last 30 days)
            $recentInstallations = DB::table('device_registrations')
                ->where('installation_date', '>=', now()->subDays(30))
                ->when($userId, function ($q) use ($userId) {
                    return $q->where('user_id', $userId);
                })
                ->count();

            return response()->json([
                'success' => true,
                'statistics' => [
                    'totalDevices' => $totalDevices,
                    'activeDevices' => $activeDevices,
                    'inactiveDevices' => $totalDevices - $activeDevices,
                    'recentInstallations' => $recentInstallations,
                    'osDistribution' => $osDistribution,
                    'versionDistribution' => $versionDistribution,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching statistics', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch statistics',
            ], 500);
        }
    }

    /**
     * Helper: Create installation record
     */
    private function createInstallationRecord($deviceId, $userId, $version)
    {
        $installationId = 'inst_' . time() . '_' . substr(md5($deviceId . $userId), 0, 8);

        // Check if exact record exists
        $exists = DB::table('installation_records')
            ->where('device_id', $deviceId)
            ->where('user_id', $userId)
            ->where('version', $version)
            ->where('status', 'active')
            ->exists();

        if ($exists) {
            // Mark as duplicate
            DB::table('installation_records')->insert([
                'installation_id' => $installationId . '_dup',
                'device_id' => $deviceId,
                'user_id' => $userId,
                'version' => $version,
                'installation_timestamp' => now(),
                'status' => 'duplicated',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // Create new installation record
            DB::table('installation_records')->insert([
                'installation_id' => $installationId,
                'device_id' => $deviceId,
                'user_id' => $userId,
                'version' => $version,
                'installation_timestamp' => now(),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
