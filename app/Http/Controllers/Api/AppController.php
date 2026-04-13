<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppController extends Controller
{
    /**
     * Check if a new version of the app is available
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkVersion(Request $request)
    {
        try {
            $currentVersion = $request->input('current_version', '1.0.0');
            $platform = $request->input('platform', 'android');

            Log::info('Version check requested', [
                'current_version' => $currentVersion,
                'platform' => $platform,
                'user_agent' => $request->header('User-Agent'),
            ]);

            // Get the latest active version for this platform
            $latestVersion = DB::table('app_versions')
                ->where('platform', $platform)
                ->where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->first();

            // If no version found in database, return current version
            if (!$latestVersion) {
                Log::warning('No active version found in database', [
                    'platform' => $platform
                ]);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'latest_version' => $currentVersion,
                        'current_version' => $currentVersion,
                        'force_update' => false,
                        'release_notes' => '',
                        'update_url' => $this->getStoreUrl($platform),
                    ]
                ]);
            }

            // Compare versions
            $updateAvailable = $this->compareVersions($currentVersion, $latestVersion->version) < 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'latest_version' => $latestVersion->version,
                    'current_version' => $currentVersion,
                    'force_update' => $latestVersion->force_update,
                    'release_notes' => $latestVersion->release_notes ?? '',
                    'update_url' => $latestVersion->update_url ?? $this->getStoreUrl($platform),
                    'update_available' => $updateAvailable,
                    'released_at' => $latestVersion->released_at,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking app version', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Unable to check for updates at this time',
                'message' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get all app versions (admin only)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllVersions(Request $request)
    {
        try {
            $platform = $request->input('platform');

            $query = DB::table('app_versions')
                ->orderBy('created_at', 'desc');

            if ($platform) {
                $query->where('platform', $platform);
            }

            $versions = $query->get();

            return response()->json([
                'success' => true,
                'data' => $versions,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching app versions', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Unable to fetch versions',
            ], 500);
        }
    }

    /**
     * Create a new app version (admin only)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createVersion(Request $request)
    {
        try {
            $validated = $request->validate([
                'platform' => 'required|in:android,ios',
                'version' => 'required|string|max:20',
                'build_number' => 'nullable|integer',
                'force_update' => 'boolean',
                'release_notes' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            // If setting as active, deactivate other versions for this platform
            if ($validated['is_active'] ?? false) {
                DB::table('app_versions')
                    ->where('platform', $validated['platform'])
                    ->update(['is_active' => false]);
            }

            $versionId = DB::table('app_versions')->insertGetId([
                'platform' => $validated['platform'],
                'version' => $validated['version'],
                'build_number' => $validated['build_number'] ?? null,
                'force_update' => $validated['force_update'] ?? false,
                'release_notes' => $validated['release_notes'] ?? null,
                'update_url' => $this->getStoreUrl($validated['platform']),
                'is_active' => $validated['is_active'] ?? true,
                'released_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $version = DB::table('app_versions')->find($versionId);

            return response()->json([
                'success' => true,
                'message' => 'App version created successfully',
                'data' => $version,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating app version', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Unable to create version',
            ], 500);
        }
    }

    /**
     * Update app version (admin only)
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateVersion(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'force_update' => 'boolean',
                'release_notes' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            $version = DB::table('app_versions')->find($id);

            if (!$version) {
                return response()->json([
                    'success' => false,
                    'error' => 'Version not found',
                ], 404);
            }

            // If setting as active, deactivate other versions for this platform
            if (($validated['is_active'] ?? false)) {
                DB::table('app_versions')
                    ->where('platform', $version->platform)
                    ->where('id', '!=', $id)
                    ->update(['is_active' => false]);
            }

            DB::table('app_versions')
                ->where('id', $id)
                ->update(array_merge($validated, [
                    'updated_at' => now(),
                ]));

            $updatedVersion = DB::table('app_versions')->find($id);

            return response()->json([
                'success' => true,
                'message' => 'App version updated successfully',
                'data' => $updatedVersion,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating app version', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Unable to update version',
            ], 500);
        }
    }

    /**
     * Compare two version strings (semantic versioning)
     * 
     * @param string $v1
     * @param string $v2
     * @return int Returns -1 if v1 < v2, 0 if equal, 1 if v1 > v2
     */
    private function compareVersions($v1, $v2)
    {
        $parts1 = array_map('intval', explode('.', $v1));
        $parts2 = array_map('intval', explode('.', $v2));

        $maxLength = max(count($parts1), count($parts2));

        for ($i = 0; $i < $maxLength; $i++) {
            $part1 = $parts1[$i] ?? 0;
            $part2 = $parts2[$i] ?? 0;

            if ($part1 < $part2) return -1;
            if ($part1 > $part2) return 1;
        }

        return 0;
    }

    /**
     * Get store URL for platform
     * 
     * @param string $platform
     * @return string
     */
    private function getStoreUrl($platform)
    {
        if ($platform === 'android') {
            // Internal testing URL (replace with market:// URL when published to production)
            return 'https://play.google.com/apps/internaltest/4701533552178048234';
        } else {
            return 'itms-apps://itunes.apple.com/app/id<YOUR_APP_STORE_ID>';
        }
    }
}
