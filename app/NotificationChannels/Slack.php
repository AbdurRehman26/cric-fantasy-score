<?php

namespace App\NotificationChannels;

use App\Notifications\NotificationInterface;
use Illuminate\Support\Facades\Http;

class Slack extends AbstractNotificationChannel
{
    public function channel(): string
    {
        return 'slack';
    }

    public function createRules(array $input): array
    {
        return [
            'webhook_url' => 'required|url',
        ];
    }

    public function createData(array $input): array
    {
        return [
            'webhook_url' => $input['webhook_url'],
        ];
    }

    public function data(): array
    {
        return [
            'webhook_url' => $this->notificationChannel->type_data['webhook_url'] ?? '',
        ];
    }

    public function connect(): bool
    {
        $connect = $this->checkConnection(
            __('Congratulations! 🎉'),
            __("You've connected your Slack to :app", ['app' => config('app.name')])."\n".
            __('Manage your notification channels')."\n".
            route('notification-channels.index')
        );

        if (! $connect) {
            $this->notificationChannel->delete();

            return false;
        }

        $this->notificationChannel->is_connected = true;
        $this->notificationChannel->save();

        return true;
    }

    private function checkConnection(string $subject, string $text): bool
    {
        $connect = Http::post($this->data()['webhook_url'], [
            'text' => '*'.$subject.'*'."\n".$text,
        ]);

        return $connect->ok();
    }

    public function send(object $notifiable, NotificationInterface $notification): void
    {
        //
    }
}
