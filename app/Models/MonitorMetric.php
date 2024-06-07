<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $monitor_id
 * @property string $location
 * @property array $data
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Monitor $monitor
 */
class MonitorMetric extends AbstractModel
{
    use HasFactory;

    protected $fillable = [
        'monitor_id',
        'location',
        'data',
    ];

    protected $casts = [
        'data' => 'json',
    ];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }
}
