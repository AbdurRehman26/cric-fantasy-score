<?php

namespace App\Policies;

use App\Models\Monitor;
use App\Models\Project;
use App\Models\User;

class MonitorPolicy
{
    public function viewAny(User $user, Project $project): bool
    {
        return $user->isMemberOfProject($project);
    }

    public function view(User $user, Monitor $monitor, Project $project): bool
    {
        return $user->isMemberOfProject($project) && $monitor->project_id === $project->id;
    }

    public function create(User $user, Project $project): bool
    {
        return $user->isMemberOfProject($project);
    }

    public function update(User $user, Monitor $monitor, Project $project): bool
    {
        return $user->isMemberOfProject($project) && $monitor->project_id === $project->id;
    }

    public function delete(User $user, Monitor $monitor, Project $project): bool
    {
        return $user->isMemberOfProject($project) && $monitor->project_id === $project->id;
    }
}
