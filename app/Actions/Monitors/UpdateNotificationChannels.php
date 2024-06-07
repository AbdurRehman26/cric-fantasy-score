<?php

namespace App\Actions\Monitors;

use App\Models\Monitor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateNotificationChannels
{
    public function update(Monitor $monitor, array $input): Monitor
    {
        $this->validate($monitor, $input);

        if (! isset($input['channels'])) {
            $input['channels'] = [];
        }

        $monitor->notificationChannels()->withTimestamps()->sync($input['channels']);

        return $monitor;
    }

    private function validate(Monitor $monitor, array $input): void
    {
        Validator::make($input, [
            'channels.*' => [
                'required',
                Rule::exists('notification_channels', 'id')->where('project_id', $monitor->project_id),
            ],
        ])->validate();
    }
}
