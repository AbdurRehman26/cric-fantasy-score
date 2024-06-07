<?php

namespace App\Console\Commands;

use App\Enums\MonitorStatus;
use App\Models\Monitor;
use Illuminate\Console\Command;

class UpdateMonitorMetricsCommand extends Command
{
    protected $signature = 'monitor:update-metrics';

    protected $description = 'Command description';

    public function handle(): void
    {
        Monitor::query()->where('status', MonitorStatus::RUNNING->value)
            ->each(function (Monitor $monitor) {
                $monitor->updateMetrics(
                    now()->subDay()->setHour(0)->setMinute(0)->setSecond(0),
                    now()->setHour(23)->setMinute(59)->setSecond(59)
                );
            });
    }
}
