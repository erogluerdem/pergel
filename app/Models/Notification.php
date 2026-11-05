<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'type', 'title', 'message', 'data', 'user_id', 'created_by',
        'priority', 'is_read', 'is_actionable', 'action_url', 'read_at', 'expires_at'
    ];
    
    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'is_actionable' => 'boolean',
        'read_at' => 'datetime',
        'expires_at' => 'datetime'
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function markAsRead(): self
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
        return $this;
    }
    
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
    
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
    
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
    
    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }
    
    public static function createForUser(User $user, array $data): self
    {
        return static::create(array_merge($data, ['user_id' => $user->id]));
    }
    
    public static function broadcast(array $userIds, array $data): void
    {
        foreach ($userIds as $userId) {
            static::create(array_merge($data, ['user_id' => $userId]));
        }
    }
}
