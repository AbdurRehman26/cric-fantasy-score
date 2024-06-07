<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $monitor_id
 * @property string $location
 * @property string $type
 * @property array $data
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property ?Monitor $monitor
 */
class MonitorEvent extends AbstractModel
{
    use HasFactory;

    protected $fillable = [
        'monitor_id',
        'location',
        'type',
        'data',
    ];

    protected $casts = [
        'monitor_id' => 'integer',
        'data' => 'json',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }
}
