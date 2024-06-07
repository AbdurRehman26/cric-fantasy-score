<?php

namespace App\NotificationChannels;

use App\Notifications\NotificationInterface;

class Discord extends AbstractNotificationChannel
{
    public function createRules(array $input): array
    {
        return [];
    }

    public function createData(array $input): array
    {
        return [];
    }

    public function data(): array
    {
        return [];
    }

    public function connect(): bool
    {
        return true;
    }

    public function send(object $notifiable, NotificationInterface $notification): void
    {
        //
    }
}
