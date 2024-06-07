<?php

namespace App\Console\Commands;

use App\Enums\MonitorStatus;
use App\Models\Monitor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class MonitorCommand extends Command
{
    protected $signature = 'monitor {--frequency=}';

    protected $description = 'Command description';

    public function handle(): void
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $this->info('start checking monitors');

        $locations = collect(config('core.monitor_nodes'))
            ->pluck('location')
            ->unique()
            ->toArray();

        foreach ($locations as $location) {
            $totalMonitors = Monitor::query()
                ->where('status', '=', MonitorStatus::RUNNING->value)
                ->where('check_frequency', '=', $this->option('frequency'))
                ->whereJsonContains('locations', $location)
                ->count();

            $nodes = collect(config('core.monitor_nodes'))
                ->where('location', '=', $location)
                ->toArray();

            $skip = 0;

            while ($totalMonitors > 0) {
                $monitors = Monitor::query()
                    ->where('status', '=', MonitorStatus::RUNNING->value)
                    ->where('check_frequency', '=', $this->option('frequency'))
                    ->whereJsonContains('locations', $location)
                    ->select(['id', 'type', 'type_data'])
                    ->limit(1000)
                    ->skip($skip)
                    ->get();

                $node = collect($nodes)
                    ->sortBy('used')
                    ->sortByDesc('capacity')
                    ->first();

                try {
                    Redis::client()->publish('monitor-check-'.$node['id'], json_encode($monitors->toArray()));
                    $this->info(sprintf('published %s monitors to %s', $monitors->count(), $node['id']));
                    Log::channel('analytics')->info('monitor-check-published', [
                        'node' => $node['id'],
                        'count' => $monitors->count(),
                    ]);
                } catch (\Throwable $e) {
                    $this->error(sprintf('failed to publish %s monitors to %s', $monitors->count(), $node['id']));
                    Log::channel('analytics')->error('monitor-check-published', [
                        'node' => $node['id'],
                        'count' => $monitors->count(),
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }

                $skip += count($monitors);
                $totalMonitors -= $monitors->count();
                $nodes = collect($nodes)
                    ->map(function ($n) use ($monitors, $node) {
                        if ($node['id'] === $n['id']) {
                            $n['used'] += $monitors->count();
                        }

                        return $n;
                    })
                    ->toArray();
            }
        }
        // print time spent and memory used
        $this->info(sprintf('time spent: %s seconds', round((microtime(true) - $startTime), 2)));
        $this->info(sprintf('memory used: %s MB', (memory_get_usage() - $startMemory) / 1024));
    }
}
