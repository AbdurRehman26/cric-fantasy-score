<?php

namespace App\Actions\Monitors;

use App\Enums\MonitorStatus;
use App\Models\Monitor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateMonitorStatus
{
    public function update(Monitor $monitor, array $input): Monitor
    {
        $this->validate($input);

        $monitor->status = $input['status'];
        $monitor->save();

        return $monitor;
    }

    private function validate(array $input): void
    {
        Validator::make($input, [
            'status' => [
                'required',
                Rule::in(MonitorStatus::values()),
            ],
        ])->validate();
    }
}
