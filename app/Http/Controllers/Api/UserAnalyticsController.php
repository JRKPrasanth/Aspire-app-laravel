<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserAnalyticsController extends Controller
{
    /** auto deploy checking 3
     * Log analytics events in batch
     * 
     * POST /api/analytics/batch
     * 
     * Request Body:
     * {
     *   "events": [
     *     {
     *       "eventType": "app_opened",
     *       "userId": "user_123",
     *       "deviceId": "device_abc",
     *       "version": "1.0.0",
     *       "timestamp": "2026-03-11T10:00:00Z",
     *       "metadata": { "sessionNumber": 1 }
     *     }
     *   ]
     * }
     */
    public function logBatchEvents(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'events' => 'required|array',
                'events.*.eventType' => 'required|in:app_opened,app_closed,installation,upgrade,crash,feature_used',
                'events.*.userId' => 'required|string',
                'events.*.deviceId' => 'required|string',
                'events.*.version' => 'required|string',
                'events.*.timestamp' => 'required|date',
                'events.*.metadata' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed',
                    'details' => $validator->errors(),
                ], 422);
            }

            $events = $request->input('events');
            $insertedCount = 0;
            $duplicatesRemoved = 0;

            foreach ($events as $event) {
                // Check for duplicate events (same user, device, type, timestamp within 1 minute)
                $exists = DB::table('analytics_events')
                    ->where('user_id', $event['userId'])
                    ->where('device_id', $event['deviceId'])
                    ->where('event_type', $event['eventType'])
                    ->whereBetween('event_timestamp', [
                        date('Y-m-d H:i:s', strtotime($event['timestamp']) - 60),
                        date('Y-m-d H:i:s', strtotime($event['timestamp']) + 60)
                    ])
                    ->exists();

                if ($exists) {
                    $duplicatesRemoved++;
                    continue;
                }

                // Insert event
                DB::table('analytics_events')->insert([
                    'event_type' => $event['eventType'],
                    'user_id' => $event['userId'],
                    'device_id' => $event['deviceId'],
                    'version' => $event['version'],
                    'event_timestamp' => $event['timestamp'],
                    'metadata' => isset($event['metadata']) ? json_encode($event['metadata']) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $insertedCount++;

                // Update device last active date
                DB::table('device_registrations')
                    ->where('device_id', $event['deviceId'])
                    ->update([
                        'last_active_date' => now(),
                        'updated_at' => now(),
                    ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Events logged successfully',
                'eventsProcessed' => count($events),
                'eventsInserted' => $insertedCount,
                'duplicatesRemoved' => $duplicatesRemoved,
            ]);
        } catch (\Exception $e) {
            Log::error('Error logging batch events', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to log events',
                'message' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Start user session
     * 
     * POST /api/user/session/start
     * 
     * Request Body:
     * {
     *   "sessionId": "session_123",
     *   "userId": "user_123",
     *   "deviceId": "device_abc",
     *   "startTime": "2026-03-11T10:00:00Z"
     * }
     */
    public function startSession(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sessionId' => 'required|string',
                'userId' => 'required|string',
                'deviceId' => 'required|string',
                'startTime' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed',
                    'details' => $validator->errors(),
                ], 422);
            }

            $data = $validator->validated();

            // Check if session already exists
            $exists = DB::table('user_sessions')
                ->where('session_id', $data['sessionId'])
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'error' => 'Session already exists',
                ], 409);
            }

            DB::table('user_sessions')->insert([
                'session_id' => $data['sessionId'],
                'user_id' => $data['userId'],
                'device_id' => $data['deviceId'],
                'start_time' => $data['startTime'],
                'end_time' => null,
                'duration' => null,
                'features' => json_encode([]),
                'screens_visited' => json_encode([]),
                'actions_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Session started',
                'sessionId' => $data['sessionId'],
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error starting session', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to start session',
            ], 500);
        }
    }

    /**
     * End user session
     * 
     * PUT /api/user/session/{sessionId}/end
     * 
     * Request Body:
     * {
     *   "endTime": "2026-03-11T11:00:00Z",
     *   "duration": 3600000,
     *   "features": ["leave_request", "purchase_order"],
     *   "screensVisited": ["Home", "LeaveRequest", "Profile"],
     *   "actionsCount": 25
     * }
     */
    public function endSession(Request $request, $sessionId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'endTime' => 'required|date',
                'duration' => 'required|integer',
                'features' => 'nullable|array',
                'screensVisited' => 'nullable|array',
                'actionsCount' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed',
                    'details' => $validator->errors(),
                ], 422);
            }

            $data = $validator->validated();

            $updated = DB::table('user_sessions')
                ->where('session_id', $sessionId)
                ->update([
                    'end_time' => $data['endTime'],
                    'duration' => $data['duration'],
                    'features' => isset($data['features']) ? json_encode($data['features']) : json_encode([]),
                    'screens_visited' => isset($data['screensVisited']) ? json_encode($data['screensVisited']) : json_encode([]),
                    'actions_count' => $data['actionsCount'] ?? 0,
                    'updated_at' => now(),
                ]);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'error' => 'Session not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Session ended',
            ]);
        } catch (\Exception $e) {
            Log::error('Error ending session', [
                'sessionId' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to end session',
            ], 500);
        }
    }

    /**
     * Log user activity
     * 
     * POST /api/user/activity/log
     * 
     * Request Body:
     * {
     *   "logId": "log_123",
     *   "userId": "user_123",
     *   "activityType": "screen_view",
     *   "timestamp": "2026-03-11T10:00:00Z",
     *   "screenName": "Home",
     *   "featureName": null,
     *   "details": {}
     * }
     */
    public function logActivity(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'logId' => 'required|string',
                'userId' => 'required|string',
                'activityType' => 'required|in:screen_view,feature_use,action,error,logout',
                'timestamp' => 'required|date',
                'screenName' => 'nullable|string',
                'featureName' => 'nullable|string',
                'details' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Validation failed',
                    'details' => $validator->errors(),
                ], 422);
            }

            $data = $validator->validated();

            DB::table('user_activity_logs')->insert([
                'log_id' => $data['logId'],
                'user_id' => $data['userId'],
                'activity_timestamp' => $data['timestamp'],
                'activity_type' => $data['activityType'],
                'screen_name' => $data['screenName'] ?? null,
                'feature_name' => $data['featureName'] ?? null,
                'details' => isset($data['details']) ? json_encode($data['details']) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Activity logged',
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error logging activity', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to log activity',
            ], 500);
        }
    }

    /**
     * Get user engagement metrics
     * 
     * GET /api/user/{userId}/engagement
     */
    public function getUserEngagement($userId)
    {
        try {
            // Get sessions
            $sessions = DB::table('user_sessions')
                ->where('user_id', $userId)
                ->whereNotNull('end_time')
                ->orderBy('start_time', 'desc')
                ->get();

            $totalSessions = $sessions->count();
            $totalDuration = $sessions->sum('duration');
            $avgDuration = $totalSessions > 0 ? $totalDuration / $totalSessions : 0;

            // Get first and last session dates
            $firstSession = $sessions->last();
            $lastSession = $sessions->first();

            // Calculate days active
            $daysActive = DB::table('user_sessions')
                ->where('user_id', $userId)
                ->selectRaw('COUNT(DISTINCT DATE(start_time)) as days')
                ->first()
                ->days ?? 0;

            // Get most used features
            $featuresUsage = [];
            foreach ($sessions as $session) {
                $features = json_decode($session->features, true) ?? [];
                foreach ($features as $feature) {
                    if (!isset($featuresUsage[$feature])) {
                        $featuresUsage[$feature] = 0;
                    }
                    $featuresUsage[$feature]++;
                }
            }
            arsort($featuresUsage);
            $mostUsedFeatures = array_slice(array_map(function ($name, $count) {
                return ['name' => $name, 'count' => $count];
            }, array_keys($featuresUsage), $featuresUsage), 0, 10);

            // Get most visited screens
            $screensVisited = [];
            foreach ($sessions as $session) {
                $screens = json_decode($session->screens_visited, true) ?? [];
                foreach ($screens as $screen) {
                    if (!isset($screensVisited[$screen])) {
                        $screensVisited[$screen] = 0;
                    }
                    $screensVisited[$screen]++;
                }
            }
            arsort($screensVisited);
            $mostVisitedScreens = array_slice(array_map(function ($name, $count) {
                return ['name' => $name, 'count' => $count];
            }, array_keys($screensVisited), $screensVisited), 0, 10);

            // Get device count
            $deviceCount = DB::table('device_registrations')
                ->where('user_id', $userId)
                ->where('is_active', true)
                ->count();

            // Calculate session frequency
            $daysSinceLastSession = $lastSession
                ? now()->diffInDays($lastSession->start_time)
                : null;

            $frequency = 'inactive';
            if ($daysSinceLastSession !== null) {
                if ($daysSinceLastSession <= 1) {
                    $frequency = 'daily';
                } elseif ($daysSinceLastSession <= 7) {
                    $frequency = 'weekly';
                } elseif ($daysSinceLastSession <= 30) {
                    $frequency = 'monthly';
                }
            }

            return response()->json([
                'success' => true,
                'userId' => $userId,
                'metrics' => [
                    'totalSessions' => $totalSessions,
                    'totalSessionDuration' => $totalDuration,
                    'averageSessionDuration' => round($avgDuration),
                    'firstSessionDate' => $firstSession ? $firstSession->start_time : null,
                    'lastSessionDate' => $lastSession ? $lastSession->start_time : null,
                    'daysActive' => $daysActive,
                    'sessionFrequency' => $frequency,
                    'mostUsedFeatures' => $mostUsedFeatures,
                    'mostVisitedScreens' => $mostVisitedScreens,
                    'deviceCount' => $deviceCount,
                    'isReturningUser' => $totalSessions > 1,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching user engagement', [
                'userId' => $userId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch engagement metrics',
            ], 500);
        }
    }

    /**
     * Get user activity log
     * 
     * GET /api/user/{userId}/activity-log
     */
    public function getUserActivityLog(Request $request, $userId)
    {
        try {
            $limit = $request->input('limit', 100);
            $activityType = $request->input('activityType');

            $query = DB::table('user_activity_logs')
                ->where('user_id', $userId)
                ->orderBy('activity_timestamp', 'desc')
                ->limit($limit);

            if ($activityType) {
                $query->where('activity_type', $activityType);
            }

            $activities = $query->get();

            return response()->json([
                'success' => true,
                'userId' => $userId,
                'totalActivities' => $activities->count(),
                'activities' => $activities,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching activity log', [
                'userId' => $userId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch activity log',
            ], 500);
        }
    }

    /**
     * Get analytics dashboard summary
     * 
     * GET /api/analytics/dashboard
     */
    public function getDashboard(Request $request)
    {
        try {
            $userId = $request->input('userId');

            // Total users
            $totalUsers = DB::table('device_registrations')
                ->when($userId, function ($q) use ($userId) {
                    return $q->where('user_id', $userId);
                })
                ->distinct('user_id')
                ->count('user_id');

            // Total devices
            $totalDevices = DB::table('device_registrations')
                ->when($userId, function ($q) use ($userId) {
                    return $q->where('user_id', $userId);
                })
                ->count();

            // Active devices (last 7 days)
            $activeDevices = DB::table('device_registrations')
                ->where('last_active_date', '>=', now()->subDays(7))
                ->when($userId, function ($q) use ($userId) {
                    return $q->where('user_id', $userId);
                })
                ->count();

            // Total sessions
            $totalSessions = DB::table('user_sessions')
                ->when($userId, function ($q) use ($userId) {
                    return $q->where('user_id', $userId);
                })
                ->count();

            // Total events
            $totalEvents = DB::table('analytics_events')
                ->when($userId, function ($q) use ($userId) {
                    return $q->where('user_id', $userId);
                })
                ->count();

            // New installations (last 30 days)
            $newInstallations = DB::table('device_registrations')
                ->where('installation_date', '>=', now()->subDays(30))
                ->when($userId, function ($q) use ($userId) {
                    return $q->where('user_id', $userId);
                })
                ->count();

            // Most used features (from analytics_events)
            $topFeatures = DB::table('analytics_events')
                ->where('event_type', 'feature_used')
                ->when($userId, function ($q) use ($userId) {
                    return $q->where('user_id', $userId);
                })
                ->selectRaw('JSON_EXTRACT(metadata, "$.featureName") as feature, COUNT(*) as count')
                ->groupBy('feature')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'dashboard' => [
                    'totalUsers' => $totalUsers,
                    'totalDevices' => $totalDevices,
                    'activeDevices' => $activeDevices,
                    'totalSessions' => $totalSessions,
                    'totalEvents' => $totalEvents,
                    'newInstallations' => $newInstallations,
                    'topFeatures' => $topFeatures,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching dashboard', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch dashboard data',
            ], 500);
        }
    }
}
