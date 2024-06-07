<?php

namespace App\Enums;

use App\Traits\Enums;

enum NotificationChannelType: string
{
    use Enums;

    case EMAIL = 'email';

    case SLACK = 'slack';

    case DISCORD = 'discord';

    case TELEGRAM = 'telegram';
}
