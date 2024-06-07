<?php

namespace App\Enums;

use App\Traits\Enums;

enum MonitorStatus: string
{
    use Enums;

    case RUNNING = 'running';

    case PAUSED = 'paused';
}
