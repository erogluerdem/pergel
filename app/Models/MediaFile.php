<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MediaFile extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 'original_name', 'path', 'disk', 'mime_type', 'extension',
        'size', 'metadata', 'folder_path', 'thumbnail_path', 'is_public',
        'uploaded_by', 'downloads', 'last_accessed'
    ];
    
    protected $casts = [
        'metadata' => 'array',
        'is_public' => 'boolean',
        'last_accessed' => 'datetime'
    ];
    
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
    
    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }
    
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path ? Storage::disk($this->disk)->url($this->thumbnail_path) : null;
    }
    
    public function getHumanReadableSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }
    
    public function isVideo(): bool
    {
        return str_starts_with($this->mime_type, 'video/');
    }
    
    public function incrementDownloads(): void
    {
        $this->increment('downloads');
        $this->update(['last_accessed' => now()]);
    }
}
