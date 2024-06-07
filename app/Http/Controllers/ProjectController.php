<?php

namespace App\Http\Controllers;

use App\Actions\ProjectMembers\AddTeamMember;
use App\Actions\Projects\CreateProject;
use App\Actions\Projects\UpdateProject;
use App\Helpers\HtmxResponse;
use App\Models\Project;
use App\Models\Role;
use App\Models\RoleAndPermission;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Project::class);

        return view('projects.index', [
            'pageTitle' => __('Projects'),
            'projects' => $this->getUser()->projects()->orderByDesc('id')->get(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Project::class);

        return view('projects.create', [
            'pageTitle' => __('Create new Project'),
        ]);
    }

    public function store(Request $request): HtmxResponse
    {
        $this->authorize('create', Project::class);

        $project = app(CreateProject::class)->create($this->getUser(), $request->input());
        app(AddTeamMember::class)->add($project, $this->getUser()->email, Role::ADMIN);

        $redirect = route('projects');

        if ($request->input('first')) {
            $redirect = route('monitors.index');
        }

        return htmx()->redirect($redirect);
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        if ($this->getUser()->projects()->count() === 1) {
            return back()->with([
                'toast.type' => 'error',
                'toast.message' => __("You can't delete all projects."),
            ]);
        }

        $project->users()->delete();
        $project->delete();

        return redirect()->route('projects');
    }

    public function select(Project $project): RedirectResponse
    {
        $this->authorize('view', $project);

        $user = $this->getUser();
        $user->current_project_id = $project->id;
        $user->save();

        return redirect()->route('monitors.index');
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        return view('projects.edit', [
            'pageTitle' => __('Edit Project'),
            'project' => $project,
            'roles' => RoleAndPermission::roles(),
        ]);
    }

    public function update(Project $project, Request $request): RedirectResponse
    {
        $this->authorize('update', $project);

        app(UpdateProject::class)->update($project, $request->input());

        return back()->with([
            'status' => 'project-updated',
        ]);
    }
}
