<?php

namespace App\Models;

use App\Enums\MonitorStatus;
use App\Jobs\UpdateMonitorMetricsJob;
use App\MonitorTypes\MonitorTypeInterface;
use App\Notifications\MonitorIsFiringNotification;
use App\Notifications\MonitorIsNormalNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $project_id
 * @property string $type
 * @property array $type_data
 * @property string $name
 * @property string $check_frequency
 * @property array $locations
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Project $project
 * @property MonitorRecord[] $records
 * @property string $address
 * @property Collection<NotificationChannel> $notificationChannels
 */
class Monitor extends AbstractModel
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'type',
        'type_data',
        'check_frequency',
        'locations',
        'status',
    ];

    protected $casts = [
        'project_id' => 'integer',
        'type_data' => 'json',
        'locations' => 'array',
    ];

    protected $attributes = [
        'locations' => '[]',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::deleting(function (Monitor $monitor) {
            $monitorId = $monitor->id;
            dispatch(function () use ($monitorId) {
                $locations = collect(config('core.monitor_nodes'))->groupBy('location')->keys()->toArray();
                MonitorRecord::query()->where('monitor_id', $monitorId)->whereIn(
                    'location',
                    $locations
                )->delete();
                MonitorEvent::query()->where('monitor_id', $monitorId)->whereIn(
                    'location',
                    $locations
                )->delete();
                MonitorMetric::query()->where('monitor_id', $monitorId)->whereIn(
                    'location',
                    $locations
                )->delete();
            });
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class);
    }

    public function records(): HasMany
    {
        return $this->hasMany(MonitorRecord::class);
    }

    public function lastRecord(?string $location = null): ?MonitorRecord
    {
        if (! $location) {
            $location = $this->locations[0];
        }

        /** @var MonitorRecord $record */
        $record = MonitorRecord::query()
            ->where('monitor_id', $this->id)
            ->where('location', $location)
            ->latest()
            ->limit(1)
            ->first();

        return $record;
    }

    public function lastEvent(?string $location = null): ?MonitorEvent
    {
        if (! $location) {
            $location = $this->locations[0];
        }

        /** @var ?MonitorEvent $lastEvent */
        $lastEvent = $this->events()
            ->where('location', $location)
            ->latest()
            ->limit(1)
            ->first();

        return $lastEvent;
    }

    public function notificationChannels(): BelongsToMany
    {
        return $this->belongsToMany(
            NotificationChannel::class,
            'monitor_notification_channel',
            'monitor_id',
            'notification_channel_id'
        );
    }

    public function events(): HasMany
    {
        return $this->hasMany(MonitorEvent::class);
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(MonitorMetric::class);
    }

    public function type(): MonitorTypeInterface
    {
        $typeClass = config('core.monitor_types')[$this->type]['class'];

        return new $typeClass($this);
    }

    public function isRunning(): bool
    {
        return $this->status === MonitorStatus::RUNNING->value;
    }

    public function isPaused(): bool
    {
        return $this->status === MonitorStatus::PAUSED->value;
    }

    public function getAddressAttribute(): string
    {
        return $this->type()->address();
    }

    public function sendMonitorIsFiringNotification(string $location): void
    {
        $monitor = $this;
        $channels = $this->notificationChannels;
        /** @var NotificationChannel $channel */
        foreach ($channels as $channel) {
            $channel->notify(new MonitorIsFiringNotification($monitor, $location));
        }
    }

    public function sendMonitorIsNormalNotification(string $location, MonitorEvent $event): void
    {
        $monitor = $this;
        $channels = $this->notificationChannels;
        /** @var NotificationChannel $channel */
        foreach ($channels as $channel) {
            $channel->notify(new MonitorIsNormalNotification($monitor, $location, $event));
        }
    }

    public function updateMetrics(Carbon $fromDate, Carbon $toDate): void
    {
        dispatch(new UpdateMonitorMetricsJob($this, $fromDate, $toDate));
    }
}
