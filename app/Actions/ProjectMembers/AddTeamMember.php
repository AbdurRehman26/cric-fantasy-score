<?php

namespace App\Actions\ProjectMembers;

use App\Models\Project;
use App\Models\RoleAndPermission;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Validator;

class AddTeamMember
{
    /**
     * Add a new team member to the given team.
     */
    public function add(Project $project, string $email, ?string $role = null)
    {
        $validator = $this->validate($project, $email, $role);

        if ($validator->fails()) {
            redirect()->route('login')->send();
        }

        $newTeamMember = User::findByEmailOrFail($email);

        $project->users()->attach(
            $newTeamMember, ['role' => $role]
        );
    }

    protected function validate(Project $project, string $email, ?string $role): \Illuminate\Validation\Validator
    {
        return Validator::make([
            'email' => $email,
            'role' => $role,
        ], $this->rules(), [
            'email.exists' => __('We were unable to find a registered user with this email address.'),
        ])->after(
            $this->checkIfUserIsAuthUser($email)
        )->after(
            $this->ensureUserIsNotAlreadyOnTeam($project, $email)
        );
    }

    protected function rules(): array
    {
        return array_filter([
            'email' => ['required', 'email', 'exists:users'],
            'role' => RoleAndPermission::hasRoles()
                ? ['required', 'string']
                : null,
        ]);
    }

    protected function ensureUserIsNotAlreadyOnTeam(Project $project, string $email): Closure
    {
        return static function ($validator) use ($project, $email) {
            $validator->errors()->addIf(
                $project->hasUserWithEmail($email),
                'email',
                __('This user already belongs to the team.')
            );
        };
    }

    protected function checkIfUserIsAuthUser(string $email): Closure
    {
        return static function ($validator) use ($email) {
            $validator->errors()->addIf(
                auth()->user()->email !== $email,
                'email',
                __('Please log in from correct user.')
            );
        };
    }
}
