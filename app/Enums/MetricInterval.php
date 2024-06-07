<?php

namespace App\Enums;

use App\Traits\Enums;

enum MetricInterval: string
{
    use Enums;

    case MINUTE = 'MINUTE';

    case HOUR = 'HOUR';

    case DAY = 'DAY';
}
