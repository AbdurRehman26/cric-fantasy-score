<?php

namespace App\Actions\Monitors;

use App\Enums\MetricInterval;
use App\Models\Monitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class GetMetrics
{
    public function filter(Monitor $monitor, array $input): array
    {
        if (isset($input['from']) && isset($input['to']) && $input['from'] === $input['to']) {
            $input['from'] = Carbon::parse($input['from'])->format('Y-m-d').' 00:00:00';
            $input['to'] = Carbon::parse($input['to'])->format('Y-m-d').' 23:59:59';
        }

        $defaultInput = [
            'location' => 'all',
            'period' => '1h',
        ];

        $input = array_merge($defaultInput, $input);

        $this->validate($monitor, $input);

        return $monitor->type()->metrics(
            fromDate: $this->getFromDate($input),
            toDate: $this->getToDate($input),
            locations: $this->getLocation($monitor, $input),
            interval: $this->getInterval($input)
        );
    }

    private function getFromDate(array $input): Carbon
    {
        if ($input['period'] === 'custom') {
            return new Carbon($input['from']);
        }

        return Carbon::parse('-'.convert_time_format($input['period']));
    }

    private function getToDate(array $input): Carbon
    {
        if ($input['period'] === 'custom') {
            return new Carbon($input['to']);
        }

        return Carbon::now();
    }

    private function getLocation(Monitor $monitor, array $input): array
    {
        if ($input['location'] === 'all') {
            return $monitor->locations;
        }

        return $input['location'];
    }

    private function getInterval(array $input): MetricInterval
    {
        if ($input['period'] === 'custom') {
            $from = new Carbon($input['from']);
            $to = new Carbon($input['to']);
            $periodInHours = $from->diffInHours($to);
        }

        if (! isset($periodInHours)) {
            $periodInHours = Carbon::parse(
                convert_time_format($input['period'])
            )->diffInHours();
        }

        if ($periodInHours <= 1) {
            return MetricInterval::MINUTE;
        }

        if ($periodInHours <= 24) {
            return MetricInterval::HOUR;
        }

        if ($periodInHours > 24) {
            return MetricInterval::DAY;
        }

        return MetricInterval::MINUTE;
    }

    private function validate(Monitor $monitor, array $input): void
    {
        Validator::make($input, [
            'location' => [
                'required',
                Rule::in(array_merge($monitor->locations, ['all'])),
            ],
            'period' => [
                'required',
                Rule::in(config('core.metric_intervals')),
            ],
        ])->validate();

        if ($input['period'] === 'custom') {
            Validator::make($input, [
                'from' => [
                    'required',
                    'date',
                    'before:to',
                ],
                'to' => [
                    'required',
                    'date',
                    'after:from',
                ],
            ])->validate();

            $from = new Carbon($input['from']);
            $to = new Carbon($input['to']);
            $diff = $from->diff($to);
            if ($diff->days > 30) {
                throw ValidationException::withMessages([
                    'to' => [__('Maximum 30 days')],
                ]);
            }
        }
    }
}
