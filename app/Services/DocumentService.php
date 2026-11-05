<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * DocumentService - Handles file upload and management operations
 * 
 * Provides centralized file handling for documents, media, and attachments
 * across the application with validation and storage management.
 */
class DocumentService
{
    /**
     * Storage disk to use
     */
    private string $disk = 'public';

    /**
     * Base path for document storage
     */
    private string $basePath = 'documents';

    /**
     * Allowed MIME types for different categories
     */
    private array $allowedMimes = [
        'pdf' => ['application/pdf'],
        'image' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        'office' => ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                     'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        'all' => ['application/pdf', 'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                  'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                  'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
    ];

    /**
     * Upload a single file
     * 
     * @param UploadedFile $file
     * @param string $category Subdirectory for organization (e.g., 'projects', 'users')
     * @param string $subfolder Optional subfolder within category
     * @return string|null Path to stored file or null on failure
     */
    public function uploadFile(UploadedFile $file, string $category = 'general', string $subfolder = ''): ?string
    {
        try {
            if (!$file->isValid()) {
                return null;
            }

            // Generate unique filename
            $filename = $this->generateFilename($file);
            
            // Build path
            $path = "{$this->basePath}/{$category}";
            if (!empty($subfolder)) {
                $path .= "/{$subfolder}";
            }

            // Store file
            $storedPath = $file->storeAs($path, $filename, $this->disk);
            
            return $storedPath;
        } catch (\Exception $e) {
            \Log::error('File upload failed', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Upload multiple files
     * 
     * @param array $files Array of UploadedFile instances
     * @param string $category
     * @param string $subfolder
     * @return array Array of stored file paths
     */
    public function uploadMultiple(array $files, string $category = 'general', string $subfolder = ''): array
    {
        $uploadedPaths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $path = $this->uploadFile($file, $category, $subfolder);
                if ($path) {
                    $uploadedPaths[] = $path;
                }
            }
        }

        return $uploadedPaths;
    }

    /**
     * Delete a file
     * 
     * @param string $path Relative path to file
     * @return bool Success status
     */
    public function deleteFile(string $path): bool
    {
        try {
            if (Storage::disk($this->disk)->exists($path)) {
                Storage::disk($this->disk)->delete($path);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            \Log::error('File deletion failed', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Delete multiple files
     * 
     * @param array $paths Array of relative file paths
     * @return int Number of successfully deleted files
     */
    public function deleteMultiple(array $paths): int
    {
        $deleted = 0;

        foreach ($paths as $path) {
            if ($this->deleteFile($path)) {
                $deleted++;
            }
        }

        return $deleted;
    }

    /**
     * Get file URL
     * 
     * @param string $path Relative path to file
     * @return string|null Public URL or null if file doesn't exist
     */
    public function getUrl(string $path): ?string
    {
        try {
            if (Storage::disk($this->disk)->exists($path)) {
                return Storage::disk($this->disk)->url($path);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to get file URL', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Check if file exists
     * 
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        try {
            return Storage::disk($this->disk)->exists($path);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get file size in KB
     * 
     * @param string $path
     * @return int|null File size in KB or null if file doesn't exist
     */
    public function getFileSize(string $path): ?int
    {
        try {
            if ($this->exists($path)) {
                $bytes = Storage::disk($this->disk)->size($path);
                return ceil($bytes / 1024);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to get file size', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Get file MIME type
     * 
     * @param string $path
     * @return string|null MIME type or null if file doesn't exist
     */
    public function getMimeType(string $path): ?string
    {
        try {
            if ($this->exists($path)) {
                return Storage::disk($this->disk)->mimeType($path);
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Copy file to new location
     * 
     * @param string $sourcePath
     * @param string $destinationPath
     * @return bool
     */
    public function copyFile(string $sourcePath, string $destinationPath): bool
    {
        try {
            if ($this->exists($sourcePath)) {
                $content = Storage::disk($this->disk)->get($sourcePath);
                Storage::disk($this->disk)->put($destinationPath, $content);
                return true;
            }
        } catch (\Exception $e) {
            \Log::error('File copy failed', [
                'source' => $sourcePath,
                'destination' => $destinationPath,
                'error' => $e->getMessage(),
            ]);
        }

        return false;
    }

    /**
     * Move file to new location
     * 
     * @param string $sourcePath
     * @param string $destinationPath
     * @return bool
     */
    public function moveFile(string $sourcePath, string $destinationPath): bool
    {
        try {
            if ($this->copyFile($sourcePath, $destinationPath)) {
                return $this->deleteFile($sourcePath);
            }
        } catch (\Exception $e) {
            \Log::error('File move failed', [
                'source' => $sourcePath,
                'destination' => $destinationPath,
                'error' => $e->getMessage(),
            ]);
        }

        return false;
    }

    /**
     * Generate unique filename preserving extension
     * 
     * @param UploadedFile $file
     * @return string Unique filename
     */
    private function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        
        return "{$name}-" . uniqid() . ".{$extension}";
    }

    /**
     * Clean up orphaned files (older than specified days)
     * 
     * @param string $category
     * @param int $olderThanDays
     * @return int Number of deleted files
     */
    public function cleanupOldFiles(string $category = '', int $olderThanDays = 30): int
    {
        try {
            $path = empty($category) ? $this->basePath : "{$this->basePath}/{$category}";
            $files = Storage::disk($this->disk)->allFiles($path);
            
            $deleted = 0;
            $cutoffDate = now()->subDays($olderThanDays)->getTimestamp();

            foreach ($files as $file) {
                $fileTimestamp = Storage::disk($this->disk)->lastModified($file);
                
                if ($fileTimestamp < $cutoffDate) {
                    if ($this->deleteFile($file)) {
                        $deleted++;
                    }
                }
            }

            return $deleted;
        } catch (\Exception $e) {
            \Log::error('Cleanup old files failed', [
                'error' => $e->getMessage(),
            ]);
        }

        return 0;
    }
}
