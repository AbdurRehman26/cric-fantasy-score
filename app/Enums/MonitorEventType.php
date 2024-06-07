<?php

namespace App\Enums;

use App\Traits\Enums;

enum MonitorEventType: string
{
    use Enums;

    case FIRING = 'firing';

    case NORMAL = 'normal';

    case FAILED = 'failed';
}
