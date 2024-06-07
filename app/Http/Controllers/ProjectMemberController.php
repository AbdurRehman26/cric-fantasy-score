<?php

namespace App\Http\Controllers;

use App\Actions\ProjectMembers\InviteProjectMember;
use App\Facades\Toast;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $this->authorize('create', ProjectMember::class);

        app(InviteProjectMember::class)->invite(
            $request->user(),
            $project,
            $request->email,
            Role::EDITOR
        );

        $redirect = route('projects.edit', $project);

        return htmx()->redirect($redirect);
    }

    public function destroy(Project $project, User $user): RedirectResponse
    {
        $this->authorize('delete', $project);

        if ($project->user->id === $user->id) {

            Toast::error(__('You cannot delete the owner of the project.'));

            return back();
        }

        Toast::error(__('User removed from project.'));

        $project->users()->detach([$user->id]);

        return redirect()->route('projects.edit', $project);
    }
}
