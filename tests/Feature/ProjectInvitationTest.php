<?php

use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Faker\fake;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->project = Project::factory()->create();
});

test('can invite project member', function () {

    $email = fake()->email;

    $this->post(route('project-members.store', ['project' => $this->project->id]), [
        'email' => $email,
        'role' => Role::EDITOR,
    ])->assertOk();

    $this->assertDatabaseHas('project_invitations', [
        'email' => $email,
        'project_id' => $this->project->id,
        'role' => Role::EDITOR,
    ]);
});

it('can not invite user if user is already invited', function () {

    $email = fake()->email;
    $this->project->projectInvitations()->create([
        'email' => $email,
        'role' => Role::EDITOR,
    ]);

    $this->postJson(route('project-members.store', ['project' => $this->project->id]), [
        'email' => $email,
        'role' => Role::EDITOR,
    ])->assertUnprocessable()->assertJsonValidationErrors([
        'email',
    ]);
});

test('registered user can accept project invitation', function () {

    $projectMember = User::factory()->create();

    $projectInvitation = $this->project->projectInvitations()->create([
        'email' => $projectMember->email,
        'role' => Role::EDITOR,
    ]);

    $verificationUrl = URL::temporarySignedRoute(
        'project-invitations.accept',
        now()->addMinutes(60),
        ['projectInvitation' => $projectInvitation->id]
    );

    $this->getJson($verificationUrl)->assertStatus(Response::HTTP_FOUND);

    $this->assertDatabaseHas('project_members', [
        'user_id' => $projectMember->id,
        'project_id' => $this->project->id,
        'role' => Role::EDITOR,
    ]);
});

test('project invitation can be cancelled', function () {

    $projectInvitation = $this->project->projectInvitations()->create([
        'email' => fake()->email,
        'role' => Role::EDITOR,
    ]);

    $this->delete(route('project-invitations.destroy', [
        'projectInvitation' => $projectInvitation->id,
    ]))->assertStatus(Response::HTTP_FOUND);

    $this->assertDatabaseEmpty('project_members');
});
