<?php

namespace App\NotificationChannels;

use App\Notifications\NotificationInterface;
use Illuminate\Support\Facades\Http;
use Ramsey\Uuid\Uuid;

class Telegram extends AbstractNotificationChannel
{
    public function channel(): string
    {
        return 'telegram';
    }

    public function createRules(array $input): array
    {
        return [
            'name' => 'required|string',
        ];
    }

    public function createData(array $input): array
    {
        return [
            'name' => $input['name'],
        ];
    }

    public function data(): array
    {
        return [
            'chat_id' => $this->notificationChannel->type_data['chat_id'] ?? '',
            'uuid' => $this->notificationChannel->type_data['uuid'] ?? '',
        ];
    }

    public function connect(): bool
    {
        $data = $this->data();
        $data['uuid'] = Uuid::uuid4()->toString();
        $this->notificationChannel->type_data = $data;
        $this->notificationChannel->save();

        return true;
    }

    public function send(object $notifiable, NotificationInterface $notification): void
    {
        Http::post(config('services.telegram_bot.api').'/sendMessage', [
            'chat_id' => $this->data()['chat_id'],
            'text' => $notification->toTelegram($notifiable),
            'parse_mode' => 'markdown',
            'disable_web_page_preview' => true,
        ]);
    }
}
