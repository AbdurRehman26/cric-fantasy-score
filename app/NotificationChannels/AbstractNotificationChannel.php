<?php

namespace App\NotificationChannels;

use App\Models\NotificationChannel;

abstract class AbstractNotificationChannel implements NotificationChannelInterface
{
    public function __construct(protected NotificationChannel $notificationChannel)
    {
    }
}
