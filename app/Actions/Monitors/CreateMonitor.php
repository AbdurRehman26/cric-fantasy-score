<?php

namespace App\Actions\Monitors;

use App\Models\Monitor;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CreateMonitor
{
    public function create(Project $project, array $input): Monitor
    {
        $this->validate($input);

        $monitor = new Monitor();
        $monitor->project_id = $project->id;
        $monitor->type = $input['type'];
        $monitor->name = $input['name'];
        $monitor->check_frequency = config('core.default_check_frequency');
        $monitor->locations = [
            config('core.monitor_nodes')[0]['location'],
        ];

        $this->validateType($monitor, $input);

        $monitor->type_data = $monitor->type()->createData($input);

        $monitor->save();

        return $monitor;
    }

    private function validate(array $input): void
    {
        Validator::make($input, [
            'type' => [
                'required',
                Rule::in(array_keys(config('core.monitor_types'))),
            ],
            'name' => 'required|string|max:255',
        ])->validate();
    }

    private function validateType(Monitor $monitor, array $input): void
    {
        Validator::make($input, $monitor->type()->createRules($input))->validate();
    }
}
