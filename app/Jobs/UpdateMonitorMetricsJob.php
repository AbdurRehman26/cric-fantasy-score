<?php

namespace App\Jobs;

use App\Models\Monitor;
use App\Models\MonitorMetric;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class UpdateMonitorMetricsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Monitor $monitor,
        protected Carbon $fromDate,
        protected Carbon $toDate
    ) {
    }

    public function handle(): void
    {
        /** @var array<Collection> $metrics */
        $metrics = $this->monitor->type()->metrics(
            $this->fromDate,
            $this->toDate,
            $this->monitor->locations
        );
        $metrics['metrics']->groupBy('location')->each(function (Collection $data) {
            /** @var MonitorMetric $metric */
            $metric = $this->monitor->metrics()
                ->where('location', $data->first()->location)
                ->whereBetween('created_at', [
                    $this->fromDate->format('Y-m-d H:i:s'),
                    $this->toDate->format('Y-m-d H:i:s'),
                ])
                ->firstOrNew();
            $metric->location = $data->first()->location;
            $metric->data = $this->monitor->type()->metricData((array) $data->first());
            $metric->save();
        });
    }
}
