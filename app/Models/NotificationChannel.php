<?php

namespace App\Models;

use App\NotificationChannels\NotificationChannelInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int project_id
 * @property string type
 * @property array type_data
 * @property string name
 * @property bool is_connected
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Project $project
 * @property Collection<Monitor> $monitors
 */
class NotificationChannel extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'project_id',
        'type',
        'type_data',
        'name',
        'is_connected',
    ];

    protected $casts = [
        'project_id' => 'integer',
        'type_data' => 'array',
        'is_connected' => 'boolean',
    ];

    public function type(): NotificationChannelInterface
    {
        $class = config('core.notification_channels')[$this->type]['class'];

        return new $class($this);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function monitors(): BelongsToMany
    {
        return $this->belongsToMany(
            Monitor::class,
            'monitor_notification_channel',
            'notification_channel_id',
            'monitor_id'
        );
    }
}
