<?php

use App\Models\Project;
use App\Models\Role;
use App\Models\User;

test('can create project', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get(route('projects.create'))
        ->assertSee('Create Project');

    $this->post(route('projects.create'), [
        'name' => 'test project',
    ]);

    $this->assertDatabaseHas('projects', [
        'user_id' => $user->id,
        'name' => 'test project',
    ]);
});

test('validation fails creating project', function () {
    $this->actingAs(User::factory()->create());

    $this->post(route('projects.create'), [])->assertSessionHasErrors([
        'name',
    ]);
});

test('should have current project', function () {
    $user = $this->createUser();

    $project = Project::factory()->create([
        'user_id' => $user->id,
    ]);

    $project->users()->attach(
        $user, ['role' => Role::ADMIN]
    );

    $this->actingAs($user);

    expect($user->current_project_id)->toBeNull();

    $this->get(route('monitors.index'));

    $user->refresh();

    expect($user->current_project_id)->toEqual($project->id);
});

test('see projects list', function () {
    $user = User::factory()->withDefaultProject()->create();

    $this->actingAs($user);

    $this->get(route('projects'))->assertSee([
        $user->currentProject->name,
    ]);
});
