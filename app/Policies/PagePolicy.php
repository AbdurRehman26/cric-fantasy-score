<?php

namespace App\Policies;

use App\Models\Page;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Project $project): bool
    {
        return $user->isMemberOfProject($project);
    }

    public function view(User $user, Page $page, Project $project): bool
    {
        return $user->isMemberOfProject($project) && $page->project_id === $project->id;
    }

    public function create(User $user, Project $project): bool
    {
        return $user->isMemberOfProject($project);
    }

    public function update(User $user, Page $page, Project $project): bool
    {
        return $user->isMemberOfProject($project) && $page->project_id === $project->id;
    }

    public function delete(User $user, Page $page, Project $project): bool
    {
        return $user->isMemberOfProject($project) && $page->project_id === $project->id;
    }
}
