<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 * @property Collection<Monitor> $monitors
 * @property Collection<Page> $pages
 * @property Collection<NotificationChannel> $notificationChannels
 */
class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::deleting(function (Project $project) {
            $project->monitors()->each(function (Monitor $monitor) {
                $monitor->delete();
            });
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function monitors(): HasMany
    {
        return $this->hasMany(Monitor::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function notificationChannels(): HasMany
    {
        return $this->hasMany(NotificationChannel::class);
    }

    public function projectInvitations(): HasMany
    {
        return $this->hasMany(ProjectInvitation::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, ProjectMember::class)
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
    }

    public function hasUserWithEmail(string $email): bool
    {
        return $this->users()->get()->contains(function ($user) use ($email) {
            return $user->email === $email;
        });
    }
}
