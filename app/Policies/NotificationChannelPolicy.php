<?php

namespace App\Policies;

use App\Models\NotificationChannel;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationChannelPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Project $project): bool
    {
        return $user->isMemberOfProject($project);
    }

    public function view(User $user, NotificationChannel $notificationChannel, Project $project): bool
    {
        return $user->isMemberOfProject($project) && $notificationChannel->project_id === $project->id;
    }

    public function create(User $user, Project $project): bool
    {
        return $user->isMemberOfProject($project);
    }

    public function update(User $user, NotificationChannel $notificationChannel, Project $project): bool
    {
        return $user->isMemberOfProject($project) && $notificationChannel->project_id === $project->id;
    }

    public function delete(User $user, NotificationChannel $notificationChannel, Project $project): bool
    {
        return $user->isMemberOfProject($project) && $notificationChannel->project_id === $project->id;
    }
}
