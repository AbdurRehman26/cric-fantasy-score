<?php

namespace App\Notifications;

use App\Models\Monitor;
use App\Models\MonitorEvent;
use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;

class MonitorIsNormalNotification extends AbstractNotification
{
    public function __construct(public Monitor $monitor, public string $location, public MonitorEvent $event)
    {
    }

    public function toMail(object $notifiable): MailMessage
    {
        $downtime = Carbon::parse(
            sprintf('-%d seconds', $this->event->data['downtime'])
        )->longAbsoluteDiffForHumans();

        return (new MailMessage)
            ->subject('✅ Monitor is firing')
            ->greeting('Hello!')
            ->line("Monitor {$this->monitor->name} is normal in {$this->location} location.")
            ->line("The monitor was firing for {$downtime}.")
            ->action('View Monitor', url('/monitors/'.$this->monitor->id));
    }
}
