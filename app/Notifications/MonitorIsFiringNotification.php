<?php

namespace App\Notifications;

use App\Models\Monitor;
use Illuminate\Notifications\Messages\MailMessage;

class MonitorIsFiringNotification extends AbstractNotification
{
    public function __construct(public Monitor $monitor, public string $location)
    {
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🔥 Monitor is firing')
            ->greeting('Hello!')
            ->line("Monitor {$this->monitor->name} is firing in {$this->location} location.")
            ->action('View Monitor', url('/monitors/'.$this->monitor->id));
    }
}
