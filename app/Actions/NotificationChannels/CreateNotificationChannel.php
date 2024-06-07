<?php

namespace App\Actions\NotificationChannels;

use App\Models\NotificationChannel;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CreateNotificationChannel
{
    public function create(Project $project, array $input): NotificationChannel
    {
        $this->validate($input);

        $notificationChannel = new NotificationChannel();
        $notificationChannel->project_id = $project->id;
        $notificationChannel->type = $input['type'];
        $notificationChannel->name = $input['name'];

        $this->validateType($notificationChannel, $input);

        $notificationChannel->type_data = $notificationChannel->type()->createData($input);
        $notificationChannel->is_connected = false;
        $notificationChannel->save();

        $notificationChannel->is_connected = $notificationChannel->type()->connect();
        $notificationChannel->save();

        return $notificationChannel;
    }

    private function validate(array $input): void
    {
        Validator::make($input, [
            'type' => [
                'required',
                Rule::in(array_keys(config('core.notification_channels'))),
            ],
            'name' => 'required|string|max:255',
        ])->validate();
    }

    private function validateType(NotificationChannel $notificationChannel, array $input): void
    {
        Validator::make($input, $notificationChannel->type()->createRules($input))->validate();
    }
}
