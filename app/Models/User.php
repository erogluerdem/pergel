<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'page_locked',
        'is_admin',
        'avatar',
        'phone',
        'title',
        'department',
        'bio',
        'language',
        'timezone',
        'two_factor_enabled',
        'last_login_at',
        'is_active',
        'theme',
        'dark_mode',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'two_factor_enabled' => 'boolean',
            'last_login_at' => 'datetime',
            'is_active'          => 'boolean',
            'dark_mode'          => 'boolean',
        ];
    }

    // Role & Permission relationships
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class, 'created_by');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function mediaFiles()
    {
        return $this->hasMany(MediaFile::class, 'uploaded_by');
    }

    public function salesTargets()
    {
        return $this->hasMany(\App\Models\SalesTarget::class);
    }

    public function opportunities()
    {
        return $this->hasMany(\App\Models\Opportunity::class, 'owner_id');
    }

    // Authorization methods
    public function hasRole($role): bool
    {
        if (is_array($role)) {
            return $this->hasAnyRole($role);
        }
        return $this->roles()->where('name', (string)$role)->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->exists();
    }

    public function assignRole(string $role): self
    {
        $roleModel = Role::where('name', $role)->firstOrFail();
        $this->roles()->syncWithoutDetaching([$roleModel->id]);
        return $this;
    }

    public function removeRole(string $role): self
    {
        $roleModel = Role::where('name', $role)->first();
        if ($roleModel) {
            $this->roles()->detach($roleModel->id);
        }
        return $this;
    }

    public function getUnreadNotificationsCount(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }
}
