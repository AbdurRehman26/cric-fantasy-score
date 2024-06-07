<?php

namespace App\MonitorTypes;

use App\Enums\MetricInterval;
use App\Enums\MonitorEventType;
use App\Models\Monitor;
use App\Models\MonitorEvent;
use App\Models\MonitorRecord;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

abstract class AbstractMonitorType implements MonitorTypeInterface
{
    public function __construct(protected Monitor $monitor)
    {
    }

    protected function getMetrics(
        Carbon $fromDate,
        Carbon $toDate,
        array $locations,
        ?MetricInterval $interval = null
    ): Collection {
        $table = 'monitor_records';
        if ($interval == MetricInterval::DAY) {
            $table = 'monitor_metrics';
        }
        if (! $interval) {
            $interval = MetricInterval::DAY;
        }

        return DB::table($table)
            ->where('monitor_id', $this->monitor->id)
            ->whereBetween('created_at', [$fromDate->format('Y-m-d H:i:s'), $toDate->format('Y-m-d H:i:s')])
            ->whereIn('location', $locations)
            ->select(
                array_merge(
                    [
                        'location',
                        DB::raw('COUNT(*) as count'),
                        DB::raw('ANY_VALUE(created_at) as date'),
                        DB::raw($interval->value.'(created_at) as date_interval'),
                    ],
                    $this->metricSelects()
                )
            )
            ->groupByRaw('date_interval, location')
            ->orderBy('date_interval')
            ->get()
            ->map(function ($item) {
                $item->date = Carbon::parse($item->date)->format('Y-m-d H:i');

                return $item;
            });
    }

    abstract protected function metricSelects(): array;

    /**
     * @throws Throwable
     */
    protected function createRecord(array $data, string $nodeId): MonitorRecord
    {
        $node = collect(config('core.monitor_nodes'))->firstWhere('id', $nodeId);
        $location = $node['location'];

        /** @var MonitorRecord $record */
        $record = $this->monitor->records()->create([
            'data' => $data,
            'location' => $location,
        ]);

        $lastEvent = $this->monitor->lastEvent($location);

        if ($lastEvent) {
            // bring the monitor back to normal if it was failing
            if ($this->isHealthy($record) && $lastEvent->type === MonitorEventType::FAILED->value) {
                $this->monitorIsNormal($location, false);

                return $record;
            }

            // bring the monitor back to normal if it was firing and notify
            if ($this->isHealthy($record) && $lastEvent->type === MonitorEventType::FIRING->value) {
                $this->monitorIsNormal($location);

                return $record;
            }

            // fire monitor if it's not healthy and already failing and the threshold is passed
            if (
                ! $this->isHealthy($record) &&
                $lastEvent->type === MonitorEventType::FAILED->value &&
                $this->isThresholdPassed($record, $lastEvent)
            ) {
                $this->monitorIsFiring($record, $location);

                return $record;
            }
        }

        // monitor is failed if it's not healthy and there is no last event, or it's normal
        if (
            ! $this->isHealthy($record) &&
            (
                ! $lastEvent ||
                $lastEvent->type === MonitorEventType::NORMAL->value
            )
        ) {
            $this->monitorIsFailed($record, $location);
        }

        return $record;
    }

    /**
     * @throws Throwable
     */
    private function monitorIsNormal(string $location, bool $notify = true): void
    {
        DB::beginTransaction();
        try {
            /** @var ?MonitorEvent $failedEvent */
            $failedEvent = $this->monitor->events()
                ->whereIn('type', [
                    MonitorEventType::FIRING->value,
                    MonitorEventType::FAILED->value,
                ])
                ->where('location', $location)
                ->latest()
                ->limit(1)
                ->first();
            $event = $this->monitor->events()->create([
                'type' => MonitorEventType::NORMAL->value,
                'location' => $location,
                'data' => [
                    'downtime' => $failedEvent ? $failedEvent->created_at->diffInSeconds() : 0,
                ],
            ]);
            if ($notify) {
                $this->monitor->sendMonitorIsNormalNotification($location, $event);
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws Throwable
     */
    private function monitorIsFiring(MonitorRecord $monitorRecord, string $location): void
    {
        DB::beginTransaction();
        try {
            $this->monitor->events()->create([
                'type' => MonitorEventType::FIRING->value,
                'location' => $location,
                'data' => [
                    'record_id' => $monitorRecord->id,
                ],
            ]);
            $this->monitor->sendMonitorIsFiringNotification($location);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws Throwable
     */
    private function monitorIsFailed(MonitorRecord $monitorRecord, string $location): void
    {
        DB::beginTransaction();
        try {
            $this->monitor->events()->create([
                'type' => MonitorEventType::FAILED->value,
                'location' => $location,
                'data' => [
                    'record_id' => $monitorRecord->id,
                ],
            ]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
