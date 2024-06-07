<?php

namespace App\Loggers;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

class Analytics
{
    public function __invoke(array $config): LoggerInterface
    {
        return Log::channel(config('logging.analytics'));
    }
}
