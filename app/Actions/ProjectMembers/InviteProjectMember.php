<?php

namespace App\Actions\ProjectMembers;

use App\Mail\ProjectInvitation as ProjectInvitationMail;
use App\Models\Project;
use App\Models\ProjectInvitation;
use App\Models\User;
use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InviteProjectMember
{
    /**
     * Invite a new team member to the given team.
     */
    public function invite(User $user, Project $project, string $email, ?string $role = null): void
    {
        $this->validate($project, $email, $role);

        /* @var ProjectInvitation $invitation */
        $invitation = $project->projectInvitations()->create([
            'email' => $email,
            'role' => $role,
        ]);

        Mail::to($email)->send(new ProjectInvitationMail($invitation));
    }

    /**
     * Validate the invite member operation.
     */
    protected function validate(Project $project, string $email, ?string $role): void
    {
        Validator::make([
            'email' => $email,
            'role' => $role,
        ], $this->rules($project), [
            'email.unique' => __('This user has already been invited to the team.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnTeam($project, $email)
        )->validate();
    }

    /**
     * Get the validation rules for inviting a team member.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    protected function rules(Project $project): array
    {
        return array_filter([
            'email' => [
                'required', 'email',
                Rule::unique('project_invitations')->where(function (Builder $query) use ($project) {
                    $query->where('project_id', $project->id);
                }),
            ],
            'role' => ['required', 'string'],
        ]);
    }

    /**
     * Ensure that the user is not already on the team.
     */
    protected function ensureUserIsNotAlreadyOnTeam(Project $project, string $email): Closure
    {
        return function ($validator) use ($project, $email) {
            $validator->errors()->addIf(
                $project->hasUserWithEmail($email),
                'email',
                __('This user already belongs to the team.')
            );
        };
    }
}
