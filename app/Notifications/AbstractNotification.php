<?php

namespace App\Notifications;

use App\Models\NotificationChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

abstract class AbstractNotification extends Notification implements NotificationInterface, ShouldQueue
{
    use Queueable, SerializesModels;

    public function via(NotificationChannel $notifiable): array
    {
        return [get_class($notifiable->type())];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return new MailMessage();
    }

    public function toSlack(object $notifiable): string
    {
        return '';
    }

    public function toDiscord(object $notifiable): string
    {
        return '';
    }

    public function toTelegram(object $notifiable): string
    {
        return '';
    }
}
