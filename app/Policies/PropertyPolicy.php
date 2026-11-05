<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Property;

class PropertyPolicy
{
    /**
     * Determine whether the user can view any properties.
     */
    public function viewAny(User $user): bool
    {
        return true; // Herkes listeyi görebilir
    }

    /**
     * Determine whether the user can view the property.
     */
    public function view(User $user, Property $property): bool
    {
        return true; // Herkes detayı görebilir
    }

    /**
     * Determine whether the user can create properties.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can update the property.
     */
    public function update(User $user, Property $property): bool
    {
        // Admin/Manager veya yaratıcı (user_id) uygulaması yapabilir
        return $user->hasRole(['admin', 'manager']) || $user->id === $property->user_id;
    }

    /**
     * Determine whether the user can delete the property.
     */
    public function delete(User $user, Property $property): bool
    {
        // Yalnızca admin/manager silebilir
        return $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can restore the property.
     */
    public function restore(User $user, Property $property): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the property.
     */
    public function forceDelete(User $user, Property $property): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can manage documents for the property.
     */
    public function manageDocuments(User $user, Property $property): bool
    {
        return $user->hasRole(['admin', 'manager']) || $user->id === $property->assigned_to_id;
    }

    /**
     * Determine whether the user can add legal processes.
     */
    public function addLegalProcess(User $user, Property $property): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can add evaluations.
     */
    public function addEvaluation(User $user, Property $property): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can add reminders.
     */
    public function addReminder(User $user, Property $property): bool
    {
        return $user->hasRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can view reports.
     */
    public function viewReports(User $user, Property $property): bool
    {
        return $user->hasRole(['admin', 'manager']) || $user->id === $property->assigned_to_id;
    }
}
