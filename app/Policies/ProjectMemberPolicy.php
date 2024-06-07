<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectMemberPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }
}
