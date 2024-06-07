<?php

namespace App\Console\Commands;

use App\Enums\MonitorStatus;
use App\Models\Monitor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Throwable;

class MonitorResultListenerCommand extends Command
{
    protected $signature = 'monitor:result-listener';

    protected $description = 'Monitor Result Listener';

    public function handle(): void
    {
        $this->info('Started listening for monitor-result...');

        Redis::connection('subscription')->subscribe(['monitor-result'], function (string $message) {
            $startTime = microtime(true);
            $startMemory = memory_get_usage();

            $monitorCollection = collect(json_decode($message, true));
            Log::channel('analytics')->info('monitor-result-received', [
                'count' => $monitorCollection->count(),
            ]);
            $this->info('monitor-result-received');
            $ids = $monitorCollection->pluck('id')->toArray();
            $monitors = Monitor::query()
                ->whereIn('id', $ids)
                ->where(['status' => MonitorStatus::RUNNING->value])
                ->get();
            /** @var Monitor $monitor */
            foreach ($monitors as $monitor) {
                $start = microtime(true);
                try {
                    $monitor->type()->record(
                        $monitorCollection->firstWhere('id', $monitor->id)['data']
                    );
                    Log::channel('analytics')->info('monitor-record', [
                        'monitor_id' => $monitor->id,
                        'time' => round((microtime(true) - $start) * 1000, 2),
                    ]);
                } catch (Throwable $e) {
                    Log::channel('analytics')->error('monitor-record', [
                        'monitor_id' => $monitor->id,
                        'time' => round((microtime(true) - $start) * 1000, 2),
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    throw_if(! app()->environment('production'), $e);
                }
            }

            $this->info(sprintf('time spent: %s seconds', round((microtime(true) - $startTime), 2)));
            $this->info(sprintf('memory used: %s MB', (memory_get_usage() - $startMemory) / 1024));
        });
    }
}
