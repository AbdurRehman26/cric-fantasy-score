<?php

namespace App\MonitorTypes;

use App\Enums\MetricInterval;
use App\Models\MonitorEvent;
use App\Models\MonitorRecord;
use Carbon\Carbon;

interface MonitorTypeInterface
{
    public function createRules(array $input): array;

    public function createData(array $input): array;

    public function updateRules(array $input): array;

    public function updateData(array $input): array;

    public function data(): array;

    public function record(array $data): MonitorRecord;

    public function address(): string;

    public function isHealthy(MonitorRecord $record): bool;

    public function isThresholdPassed(MonitorRecord $record, MonitorEvent $lastFailedEvent): bool;

    public function metrics(
        Carbon $fromDate,
        Carbon $toDate,
        array $locations,
        ?MetricInterval $interval = null
    ): array;

    public function metricData(array $data): array;
}
