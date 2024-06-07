<?php

namespace App\Http\Controllers;

use App\Actions\ProjectMembers\AddTeamMember;
use App\Facades\Toast;
use App\Models\ProjectInvitation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProjectInvitationController extends Controller
{
    public function accept(Request $request, ProjectInvitation $projectInvitation)
    {
        app(AddTeamMember::class)->add(
            $projectInvitation->project,
            $projectInvitation->email,
            $projectInvitation->role
        );

        $projectInvitation->delete();

        Toast::success(__('You have been added to '.$projectInvitation->project->name));

        return redirect()->route('projects');
    }

    public function destroy(Request $request, ProjectInvitation $projectInvitation): RedirectResponse
    {
        $project = $projectInvitation->project;

        $projectInvitation->delete();

        Toast::success(__('Invitation Cancelled.'));

        return redirect()->route('projects.edit', $project);
    }
}
