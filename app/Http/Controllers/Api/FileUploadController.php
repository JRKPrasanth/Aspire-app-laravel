<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * File Upload Controller
 * Handles secure file upload, validation, download, and deletion
 * Production-ready with comprehensive security and error handling
 */
class FileUploadController extends Controller
{
    /**
     * ============================================================
     * CONFIGURATION
     * ============================================================
     */

    // Maximum file size: 1 MB
    private const MAX_FILE_SIZE = 1048576; // 1 MB in bytes

    // Allowed MIME types
    private const ALLOWED_MIME_TYPES = [
        'application/pdf',
        'image/jpeg',
        'image/jpg',
        'image/png',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    ];

    // Allowed file extensions
    private const ALLOWED_EXTENSIONS = [
        'pdf',
        'jpeg',
        'jpg',
        'png',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx'
    ];

    /**
     * ============================================================
     * FILE UPLOAD
     * ============================================================
     */

    /**
     * Upload file with validation and security checks
     * POST /api/files/upload
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $userId = Auth::id();
        $companyId = Auth::user()->company_id ?? null;

        Log::info('[FileUpload] Upload request received', [
            'user_id' => $userId,
            'entity_type' => $request->input('entity_type'),
            'entity_id' => $request->input('entity_id'),
        ]);

        // ============================================================
        // VALIDATION
        // ============================================================

        $validator = Validator::make($request->all(), [
            'file' => [
                'required',
                'file',
                'max:1024', // 1 MB in KB
                'mimes:pdf,jpeg,jpg,png,doc,docx,xls,xlsx,ppt,pptx',
            ],
            'entity_id' => 'required|string',
            'entity_type' => 'required|string',
        ], [
            'file.required' => 'Please select a file to upload',
            'file.max' => 'File size must not exceed 1 MB',
            'file.mimes' => 'Invalid file type. Allowed types: PDF, JPEG, PNG, DOC, DOCX, XLS, XLSX, PPT, PPTX',
        ]);

        if ($validator->fails()) {
            Log::warning('[FileUpload] Validation failed', [
                'errors' => $validator->errors()->toArray(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // ============================================================
        // SECURITY CHECKS
        // ============================================================

        if (!$request->hasFile('file')) {
            return response()->json([
                'success' => false,
                'message' => 'No file received',
            ], 400);
        }

        $file = $request->file('file');

        // Check if file is valid
        if (!$file->isValid()) {
            Log::error('[FileUpload] Invalid file upload', [
                'error' => $file->getErrorMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'File upload failed. Please try again.',
            ], 400);
        }

        // Additional security: Check file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            return response()->json([
                'success' => false,
                'message' => 'File size exceeds 1 MB limit',
            ], 400);
        }

        // Additional security: Check MIME type
        if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES)) {
            Log::warning('[FileUpload] Invalid MIME type', [
                'mime_type' => $file->getMimeType(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid file type',
            ], 400);
        }

        // Additional security: Check file extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            Log::warning('[FileUpload] Invalid file extension', [
                'extension' => $extension,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid file extension',
            ], 400);
        }

        // ============================================================
        // FILE UPLOAD
        // ============================================================

        DB::beginTransaction();

        try {
            $entityType = $request->input('entity_type');
            $entityId = $request->input('entity_id');

            // Generate unique file name
            $originalName = $file->getClientOriginalName();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $uniqueFileName = $fileName . '_' . time() . '_' . Str::random(10) . '.' . $extension;

            // Determine upload path based on entity type
            $uploadPath = $this->getUploadPath($entityType, $entityId);

            // Create directory if not exists
            $fullPath = public_path($uploadPath);
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            // Capture temp path and size BEFORE moving (to avoid SplFileInfo stat failures)
            $tempPath = $file->getPathname() ?: $file->getRealPath();
            $tempSize = null;
            try {
                $tempSize = $file->getSize();
            } catch (\Exception $e) {
                Log::warning('[FileUpload] Unable to get temp file size', ['path' => $tempPath, 'error' => $e->getMessage()]);
                $tempSize = null;
            }

            // Capture mime types early with fallbacks to avoid exceptions when tmp file is gone
            $clientMime = null;
            $detectedMime = null;
            try {
                $clientMime = $file->getClientMimeType();
            } catch (\Exception $e) {
                Log::warning('[FileUpload] getClientMimeType failed', ['error' => $e->getMessage()]);
                $clientMime = null;
            }

            try {
                $detectedMime = $file->getMimeType();
            } catch (\Exception $e) {
                // This often fails when temp file is missing; log and continue
                Log::warning('[FileUpload] getMimeType failed (tmp file missing or unreadable)', ['temp_path' => $tempPath, 'error' => $e->getMessage()]);
                $detectedMime = null;
            }

            // Compute final mime type (prefer detection, fall back to client mime or extension map)
            $finalMimeType = $detectedMime ?: $clientMime ?: $this->getMimeTypeFromExtension($extension);

            // Ensure upload directory is writable
            if (!is_dir($fullPath)) {
                try {
                    mkdir($fullPath, 0755, true);
                    Log::info('[FileUpload] Created upload directory', ['path' => $fullPath]);
                } catch (\Exception $e) {
                    Log::error('[FileUpload] Failed to create upload directory', ['error' => $e->getMessage(), 'path' => $fullPath]);
                    throw $e;
                }
            }

            if (!is_writable($fullPath)) {
                try {
                    chmod($fullPath, 0755);
                    Log::warning('[FileUpload] Upload directory was not writable; attempted chmod', ['path' => $fullPath]);
                } catch (\Exception $e) {
                    Log::error('[FileUpload] Failed to chmod upload directory', ['error' => $e->getMessage(), 'path' => $fullPath]);
                }
            }

            // Move file to public directory
            $moved = false;
            try {
                $file->move($fullPath, $uniqueFileName);
                $moved = true;
            } catch (\Exception $e) {
                Log::error('[FileUpload] Move failed', ['error' => $e->getMessage(), 'temp_path' => $tempPath, 'dest_path' => $fullPath]);
                throw $e; // will be caught by outer try/catch and rollback
            }

            // Build file URL and final path
            $fileUrl = url($uploadPath . '/' . $uniqueFileName);
            $finalPath = $fullPath . '/' . $uniqueFileName;

            // Determine final file size (prefer the moved file size)
            $finalSize = null;
            if (file_exists($finalPath)) {
                $finalSize = filesize($finalPath);
            } elseif ($tempSize !== null) {
                $finalSize = $tempSize;
            }

            // Save file metadata to database (for tracking)
            $fileId = DB::table('files_t')->insertGetId([
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'file_name' => $originalName,
                'file_path' => $uploadPath . '/' . $uniqueFileName,
                'file_url' => $fileUrl,
                'file_size' => $finalSize,
                'mime_type' => $finalMimeType,
                'extension' => $extension,
                'uploaded_by' => $userId,
                'company_id' => $companyId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ALSO update entity table for backward compatibility with web app
            $this->updateEntityAttachments($entityType, $entityId, $uniqueFileName);

            DB::commit();

            Log::info('[FileUpload] File uploaded successfully', [
                'file_id' => $fileId,
                'file_name' => $originalName,
                'temp_path' => $tempPath,
                'temp_size' => $tempSize,
                'final_path' => $finalPath,
                'final_size' => $finalSize,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'file_id' => (string) $fileId,
                'file_name' => $originalName,
                'file_url' => $fileUrl,
                'file_size' => $finalSize,
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('[FileUpload] Upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'File upload failed',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * ============================================================
     * FILE DOWNLOAD
     * ============================================================
     */

    /**
     * Download file
     * GET /api/files/{id}/download
     * 
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $userId = Auth::id();

        Log::info('[FileUpload] Download request', [
            'user_id' => $userId,
            'file_id' => $id,
        ]);

        try {
            // Get file metadata
            $file = DB::table('files_t')
                ->where('id', $id)
                ->first();

            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found',
                ], 404);
            }

            // Check file exists
            $filePath = public_path($file->file_path);
            if (!file_exists($filePath)) {
                Log::error('[FileUpload] File not found on disk', [
                    'file_id' => $id,
                    'file_path' => $filePath,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'File not found on server',
                ], 404);
            }

            // Return file download response
            return response()->download($filePath, $file->file_name);
        } catch (\Exception $e) {
            Log::error('[FileUpload] Download failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Download failed',
            ], 500);
        }
    }

    /**
     * ============================================================
     * FILE DELETION
     * ============================================================
     */

    /**
     * Delete file
     * DELETE /api/files/{id}
     * 
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $userId = Auth::id();

        Log::info('[FileUpload] Delete request', [
            'user_id' => $userId,
            'file_id' => $id,
        ]);

        DB::beginTransaction();

        try {
            // Get file metadata
            $file = DB::table('files_t')
                ->where('id', $id)
                ->first();

            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found',
                ], 404);
            }

            // Optional: Check if user has permission to delete
            // if ($file->uploaded_by != $userId && !Auth::user()->is_admin) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Unauthorized to delete this file',
            //     ], 403);
            // }

            // Delete file from disk
            $filePath = public_path($file->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Remove from entity table's attachfile_name (for web app compatibility)
            $this->removeFromEntityAttachments($file->entity_type, $file->entity_id, basename($file->file_path));

            // Delete from database
            DB::table('files_t')->where('id', $id)->delete();

            DB::commit();

            Log::info('[FileUpload] File deleted successfully', [
                'file_id' => $id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('[FileUpload] Delete failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Delete failed',
            ], 500);
        }
    }

    /**
     * ============================================================
     * GET FILES FOR ENTITY
     * ============================================================
     */

    /**
     * Get all files for an entity
     * GET /api/files?entity_type=purchase_quotation&entity_id=123
     * 
     * Supports BOTH:
     * 1. New files uploaded via API (stored in files_t table)
     * 2. Old files uploaded via web app (stored in entity table's attachfile_name field)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFiles(Request $request)
    {
        $entityType = $request->input('entity_type');
        $entityId = $request->input('entity_id');

        Log::info('[FileUpload] Get files request', [
            'entity_type' => $entityType,
            'entity_id' => $entityId,
        ]);

        try {
            $files = [];

            // Get files from files_t table (new API uploads)
            $query = DB::table('files_t')
                ->select(
                    'id as file_id',
                    'file_name',
                    'file_url',
                    'file_size',
                    'mime_type',
                    'extension',
                    'uploaded_by',
                    'created_at as upload_date'
                );

            if ($entityType) {
                $query->where('entity_type', $entityType);
            }

            if ($entityId) {
                $query->where('entity_id', $entityId);
            }

            $filesFromTable = $query->orderBy('created_at', 'desc')->get();

            foreach ($filesFromTable as $file) {
                $files[] = [
                    'file_id' => $file->file_id,
                    'file_name' => $file->file_name,
                    'file_url' => $file->file_url,
                    'file_size' => $file->file_size,
                    'mime_type' => $file->mime_type,
                    'extension' => $file->extension,
                    'uploaded_by' => $file->uploaded_by,
                    'upload_date' => $file->upload_date,
                    'source' => 'api' // Uploaded via API
                ];
            }

            // ALSO get files from entity table (web app uploads - backward compatibility)
            if ($entityType === 'purchase_quotation' && $entityId) {
                $quotation = DB::table('p_quotation_hdr_t')
                    ->where('quotation_hdr_id', $entityId)
                    ->first();

                if ($quotation && !empty($quotation->attachfile_name)) {
                    $webFiles = json_decode($quotation->attachfile_name, true);
                    if (is_array($webFiles)) {
                        $uploadPath = $this->getUploadPath($entityType, $entityId);

                        foreach ($webFiles as $webFile) {
                            // Check if this file is already in files_t (avoid duplicates)
                            $existsInTable = false;
                            foreach ($filesFromTable as $tableFile) {
                                if (basename($tableFile->file_url) === $webFile) {
                                    $existsInTable = true;
                                    break;
                                }
                            }

                            if (!$existsInTable) {
                                $fileUrl = url($uploadPath . '/' . $webFile);
                                $filePath = public_path($uploadPath . '/' . $webFile);
                                $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                $extension = pathinfo($webFile, PATHINFO_EXTENSION);

                                $files[] = [
                                    'file_id' => null, // No ID for web uploads
                                    'file_name' => $webFile,
                                    'file_url' => $fileUrl,
                                    'file_size' => $fileSize,
                                    'mime_type' => $this->getMimeTypeFromExtension($extension),
                                    'extension' => $extension,
                                    'uploaded_by' => null,
                                    'upload_date' => null,
                                    'source' => 'web' // Uploaded via web app
                                ];
                            }
                        }
                    }
                }
            }

            // Backward compat for purchase_order web uploads (attachfile_name column, matches web save())
            if ($entityType === 'purchase_order' && $entityId) {
                $purchaseOrder = DB::table('p_po_hdr_t')
                    ->where('po_hdr_id', $entityId)
                    ->first();

                if ($purchaseOrder && !empty($purchaseOrder->attachfile_name)) {
                    $webFiles = json_decode($purchaseOrder->attachfile_name, true);
                    if (is_array($webFiles)) {
                        $uploadPath = $this->getUploadPath($entityType, $entityId);

                        foreach ($webFiles as $webFile) {
                            // Avoid duplicates already in files_t
                            $existsInTable = false;
                            foreach ($filesFromTable as $tableFile) {
                                if (basename($tableFile->file_url) === $webFile) {
                                    $existsInTable = true;
                                    break;
                                }
                            }

                            if (!$existsInTable) {
                                $fileUrl  = url($uploadPath . '/' . $webFile);
                                $filePath = public_path($uploadPath . '/' . $webFile);
                                $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                                $extension = pathinfo($webFile, PATHINFO_EXTENSION);

                                $files[] = [
                                    'file_id'    => null,
                                    'file_name'  => $webFile,
                                    'file_url'   => $fileUrl,
                                    'file_size'  => $fileSize,
                                    'mime_type'  => $this->getMimeTypeFromExtension($extension),
                                    'extension'  => $extension,
                                    'uploaded_by' => null,
                                    'upload_date' => null,
                                    'source'     => 'web',
                                ];
                            }
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'files' => $files,
                'count' => count($files),
            ], 200);
        } catch (\Exception $e) {
            Log::error('[FileUpload] Get files failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve files',
            ], 500);
        }
    }

    /**
     * Get MIME type from file extension
     * 
     * @param string $extension
     * @return string
     */
    private function getMimeTypeFromExtension($extension)
    {
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ];

        return $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
    }

    /**
     * ============================================================
     * HELPER METHODS
     * ============================================================
     */

    /**
     * Get upload path based on entity type
     * Matches existing web application folder structure
     * 
     * @param string $entityType
     * @param string $entityId
     * @return string
     */
    private function getUploadPath($entityType, $entityId)
    {
        // Customize paths based on entity type to match existing web app structure
        switch ($entityType) {
            case 'purchase_quotation':
                // Matches existing: /Uploads/poquoteattachment/PO{id}/
                return '/Uploads/poquoteattachment/PO' . $entityId;

            case 'sales_invoice':
                return '/Uploads/salesinvoice/SI' . $entityId;

            case 'purchase_order':
                // Matches web save() function: /Uploads/purchaseorder/PO{id}/
                return '/Uploads/purchaseorder/PO' . $entityId;

            case 'sales_order':
                return '/Uploads/salesorder/SO' . $entityId;

            default:
                // Generic path for other entity types
                return '/Uploads/files/' . $entityType . '/' . $entityId;
        }
    }

    /**
     * Remove file from entity table's attachment field
     * 
     * @param string $entityType
     * @param string $entityId
     * @param string $fileName
     * @return void
     */
    private function removeFromEntityAttachments($entityType, $entityId, $fileName)
    {
        try {
            switch ($entityType) {
                case 'purchase_quotation':
                    $quotation = DB::table('p_quotation_hdr_t')
                        ->where('quotation_hdr_id', $entityId)
                        ->first();

                    if ($quotation && !empty($quotation->attachfile_name)) {
                        $existingFiles = json_decode($quotation->attachfile_name, true);
                        if (is_array($existingFiles)) {
                            // Remove the file from array
                            $existingFiles = array_values(array_filter($existingFiles, function ($file) use ($fileName) {
                                return $file !== $fileName;
                            }));

                            // Update
                            DB::table('p_quotation_hdr_t')
                                ->where('quotation_hdr_id', $entityId)
                                ->update([
                                    'attachfile_name' => json_encode($existingFiles)
                                ]);

                            Log::info('[FileUpload] Removed from p_quotation_hdr_t.attachfile_name', [
                                'quotation_hdr_id' => $entityId,
                                'file_name' => $fileName
                            ]);
                        }
                    }
                    break;

                case 'purchase_order':
                    // Matches web save() which uses attachfile_name column
                    $purchaseOrder = DB::table('p_po_hdr_t')
                        ->where('po_hdr_id', $entityId)
                        ->first();

                    if ($purchaseOrder && !empty($purchaseOrder->attachfile_name)) {
                        $existingFiles = json_decode($purchaseOrder->attachfile_name, true);
                        if (is_array($existingFiles)) {
                            $existingFiles = array_values(array_filter($existingFiles, function ($file) use ($fileName) {
                                return $file !== $fileName;
                            }));

                            DB::table('p_po_hdr_t')
                                ->where('po_hdr_id', $entityId)
                                ->update([
                                    'attachfile_name' => json_encode($existingFiles)
                                ]);

                            Log::info('[FileUpload] Removed from p_po_hdr_t.attachfile_name', [
                                'po_hdr_id' => $entityId,
                                'file_name' => $fileName
                            ]);
                        }
                    }
                    break;

                // Add similar cases for other entity types
                default:
                    break;
            }
        } catch (\Exception $e) {
            Log::error('[FileUpload] Failed to remove from entity attachments', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update entity table's attachment field (for web app compatibility)
     * Maintains existing JSON array format in attachfile_name column
     * 
     * @param string $entityType
     * @param string $entityId
     * @param string $fileName
     * @return void
     */
    private function updateEntityAttachments($entityType, $entityId, $fileName)
    {
        try {
            switch ($entityType) {
                case 'purchase_quotation':
                    // Get current attachments from p_quotation_hdr_t
                    $quotation = DB::table('p_quotation_hdr_t')
                        ->where('quotation_hdr_id', $entityId)
                        ->first();

                    if ($quotation) {
                        // Decode existing attachments
                        $existingFiles = [];
                        if (!empty($quotation->attachfile_name)) {
                            $existingFiles = json_decode($quotation->attachfile_name, true);
                            if (!is_array($existingFiles)) {
                                $existingFiles = [];
                            }
                        }

                        // Add new file
                        $existingFiles[] = $fileName;

                        // Update with JSON encoded array
                        DB::table('p_quotation_hdr_t')
                            ->where('quotation_hdr_id', $entityId)
                            ->update([
                                'attachfile_name' => json_encode($existingFiles)
                            ]);

                        Log::info('[FileUpload] Updated p_quotation_hdr_t.attachfile_name', [
                            'quotation_hdr_id' => $entityId,
                            'file_count' => count($existingFiles)
                        ]);
                    }
                    break;

                case 'sales_invoice':
                    // Add similar logic for sales invoice if needed
                    // DB::table('sales_invoice_hdr_t')->where(...)->update([...]);
                    break;

                case 'purchase_order':
                    // Matches web save() which uses attachfile_name column
                    $purchaseOrder = DB::table('p_po_hdr_t')
                        ->where('po_hdr_id', $entityId)
                        ->first();

                    if ($purchaseOrder) {
                        $existingFiles = [];
                        if (!empty($purchaseOrder->attachfile_name)) {
                            $existingFiles = json_decode($purchaseOrder->attachfile_name, true);
                            if (!is_array($existingFiles)) {
                                $existingFiles = [];
                            }
                        }

                        $existingFiles[] = $fileName;

                        DB::table('p_po_hdr_t')
                            ->where('po_hdr_id', $entityId)
                            ->update([
                                'attachfile_name' => json_encode($existingFiles)
                            ]);

                        Log::info('[FileUpload] Updated p_po_hdr_t.attachfile_name', [
                            'po_hdr_id' => $entityId,
                            'file_count' => count($existingFiles)
                        ]);
                    }
                    break;

                default:
                    // No entity table update for generic types
                    Log::info('[FileUpload] No entity table update for type: ' . $entityType);
                    break;
            }
        } catch (\Exception $e) {
            Log::error('[FileUpload] Failed to update entity attachments', [
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'error' => $e->getMessage()
            ]);
            // Don't throw - metadata is already saved
        }
    }

    /**
     * ============================================================
     * CLEANUP (OPTIONAL)
     * ============================================================
     */

    /**
     * Clean up old files (can be scheduled via cron)
     * DELETE /api/files/cleanup
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cleanup(Request $request)
    {
        // Only allow admin users
        if (!Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $days = $request->input('days', 30); // Default: 30 days

        Log::info('[FileUpload] Cleanup started', [
            'days' => $days,
        ]);

        try {
            $cutoffDate = now()->subDays($days);

            // Get old files
            $oldFiles = DB::table('files_t')
                ->where('created_at', '<', $cutoffDate)
                ->get();

            $deletedCount = 0;

            foreach ($oldFiles as $file) {
                // Delete from disk
                $filePath = public_path($file->file_path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                // Delete from database
                DB::table('files_t')->where('id', $file->id)->delete();
                $deletedCount++;
            }

            Log::info('[FileUpload] Cleanup completed', [
                'deleted_count' => $deletedCount,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cleanup completed',
                'deleted_count' => $deletedCount,
            ], 200);
        } catch (\Exception $e) {
            Log::error('[FileUpload] Cleanup failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Cleanup failed',
            ], 500);
        }
    }
}
