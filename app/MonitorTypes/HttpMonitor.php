<?php

namespace App\MonitorTypes;

use App\Enums\MetricInterval;
use App\Enums\MonitorEventType;
use App\Models\MonitorEvent;
use App\Models\MonitorRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;
use Throwable;

class HttpMonitor extends AbstractMonitorType
{
    public function createRules(array $input): array
    {
        return [
            'address' => [
                'required',
                'active_url',
            ],
        ];
    }

    public function createData(array $input): array
    {
        return [
            'address' => $input['address'],
            'threshold' => config('core.monitor_types.http.default_threshold'),
        ];
    }

    public function updateRules(array $input): array
    {
        return [
            'address' => [
                'required',
                'active_url',
            ],
            'threshold' => [
                'required',
                Rule::in(config('core.monitor_types.http.thresholds')),
            ],
        ];
    }

    public function updateData(array $input): array
    {
        return [
            'address' => $input['address'],
            'threshold' => $input['threshold'],
        ];
    }

    public function data(): array
    {
        return [
            'address' => $this->monitor->type_data['address'] ?? '',
            'threshold' => $this->monitor->type_data['threshold'] ?? '',
        ];
    }

    /**
     * @throws Throwable
     */
    public function record(array $data): MonitorRecord
    {
        return $this->createRecord([
            'status_code' => $data['status_code'],
            'is_up' => $data['is_up'],
            'response_time' => $data['response_time'],
        ], $data['node']);
    }

    public function address(): string
    {
        return $this->monitor->type()->data()['address'];
    }

    public function isHealthy(MonitorRecord $record): bool
    {
        return $record && $record->data['is_up'];
    }

    public function isThresholdPassed(MonitorRecord $record, MonitorEvent $lastFailedEvent): bool
    {
        $thresholdSeconds = abs(
            Carbon::parse(
                convert_time_format($this->monitor->type()->data()['threshold'])
            )->diffInSeconds()
        );
        $lastFailedSeconds = strtotime($lastFailedEvent->created_at->format('Y-m-d H:i:s'));
        $recordSeconds = strtotime($record->created_at->format('Y-m-d H:i:s'));

        return $recordSeconds - $lastFailedSeconds > $thresholdSeconds;
    }

    public function metrics(
        Carbon $fromDate,
        Carbon $toDate,
        array $locations,
        ?MetricInterval $interval = null
    ): array {
        $data = [
            'metrics' => $this->getMetrics($fromDate, $toDate, $locations, $interval),
        ];

        $downtime = $this->monitor->events()
            ->whereBetween(
                'created_at',
                [$fromDate->format('Y-m-d H:i:s'), $toDate->format('Y-m-d H:i:s')]
            )
            ->whereIn('location', $locations)
            ->where('type', MonitorEventType::NORMAL->value)
            ->sum('data->downtime') ?? 0;
        $data['uptime'] = Number::forHumans(
            100 - (($downtime / $fromDate->diffInSeconds($toDate)) * 100),
            2
        );

        return $data;
    }

    public function metricData(array $data): array
    {
        return [
            'response_time' => $data['response_time'] ?? '',
        ];
    }

    protected function metricSelects(): array
    {
        return [
            DB::raw('AVG(data->>"$.response_time") as response_time'),
        ];
    }
}
