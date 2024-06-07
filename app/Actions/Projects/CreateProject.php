<?php

namespace App\Actions\Projects;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CreateProject
{
    public function create(User $user, array $input): Project
    {
        $this->validate($user, $input);

        $project = new Project([
            'user_id' => $user->id,
            'name' => $input['name'],
        ]);
        $project->save();

        if (isset($input['first']) && $input['first']) {
            $user->current_project_id = $project->id;
            $user->save();
        }

        return $project;
    }

    private function validate(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => [
                'required',
                Rule::unique('projects')->where('user_id', $user->id),
            ],
        ])->validate();
    }
}
