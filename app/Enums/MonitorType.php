<?php

namespace App\Enums;

use App\Traits\Enums;

enum MonitorType: string
{
    use Enums;

    case HTTP = 'http';
}
