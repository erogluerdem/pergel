<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;

class ProjectPolicy
{
    public function before(User $user, string $ability)
    {
        if ($user->id === 1 || $user->hasRole(['admin', 'Super Admin', 'yönetici', 'Sistem Yöneticisi', 'Proje Yöneticisi'])) {
            return true;
        }
    }

    public function view(User $user, Project $project)
    {
        return true;
    }

    public function update(User $user, Project $project)
    {
        return true;
    }

    public function upload(User $user, Project $project)
    {
        return true;
    }
}

