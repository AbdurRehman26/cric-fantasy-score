<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $monitor_id
 * @property array $data
 * @property string $location
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Monitor $monitor
 */
class MonitorRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitor_id',
        'data',
        'location',
    ];

    protected $casts = [
        'monitor_id' => 'integer',
        'data' => 'json',
    ];

    public static function boot(): void
    {
        parent::boot();
    }

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }
}
