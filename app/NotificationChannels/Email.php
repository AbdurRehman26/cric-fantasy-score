<?php

namespace App\NotificationChannels;

use App\Mail\NotificationMail;
use App\Mail\VerifyEmailNotificationChannel;
use App\Models\NotificationChannel;
use App\Notifications\NotificationInterface;
use Illuminate\Support\Facades\Mail;

class Email extends AbstractNotificationChannel
{
    public function createRules(array $input): array
    {
        return [
            'email' => 'required|email',
        ];
    }

    public function createData(array $input): array
    {
        return [
            'email' => $input['email'],
        ];
    }

    public function data(): array
    {
        return [
            'email' => $this->notificationChannel->type_data['email'] ?? '',
        ];
    }

    public function connect(): bool
    {
        $notificationChannel = $this->notificationChannel;
        Mail::to($notificationChannel->type()->data()['email'])->queue(
            new VerifyEmailNotificationChannel(
                $notificationChannel->id,
                $notificationChannel->type()->data()['email']
            )
        );

        return false;
    }

    public function send(object $notifiable, NotificationInterface $notification): void
    {
        /** @var NotificationChannel $notifiable */
        $this->notificationChannel = $notifiable;
        $message = $notification->toMail($notifiable);

        Mail::to($this->data()['email'])->send(
            new NotificationMail($message->subject, $message->render())
        );
    }
}
