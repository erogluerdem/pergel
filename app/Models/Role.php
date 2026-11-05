<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 'display_name', 'description', 'permissions', 'is_active'
    ];
    
    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean'
    ];
    
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }
    
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }
    
    public function hasPermission(string $permission): bool
    {
        if (!$this->is_active) return false;
        
        return $this->permissions()->where('name', $permission)->exists()
            || (is_array($this->permissions) && in_array($permission, $this->permissions));
    }
    
    public function givePermissionTo(Permission|string $permission): self
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }
        
        $this->permissions()->syncWithoutDetaching([$permission->id]);
        return $this;
    }
    
    public function revokePermissionTo(Permission|string $permission): self
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }
        
        $this->permissions()->detach($permission->id);
        return $this;
    }
}
