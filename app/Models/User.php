<?php

namespace App\Models;

use App\Actions\ProjectMembers\AddTeamMember;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property ?int $current_project_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $timezone
 * @property Project[] $projects
 * @property Project $currentProject
 */
class User extends Authenticatable implements MustVerifyEmail, UserContract
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'current_project_id',
        'timezone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin(): bool
    {
        return true;
    }

    public function projects(): HasManyThrough
    {
        return $this->hasManyThrough(
            Project::class,
            ProjectMember::class,
            'user_id',
            'id',
            'id',
            'project_id'
        );
    }

    public function currentProject(): HasOne
    {
        return $this->HasOne(Project::class, 'id', 'current_project_id');
    }

    public function isMemberOfProject(Project $project): bool
    {
        return $project->user_id === $this->id || $project->hasUserWithEmail($this->email);
    }

    public function isAdminOfProject(Project $project): bool
    {
        return
            $project->user_id === $this->id ||
            $project->users()->get()->contains(function ($user) {
                return $user->id === $this->id && $user->membership->role === Role::ADMIN;
            });
    }

    public function createDefaultProject(): Project
    {
        $project = $this->projects()->first();

        if (! $project) {
            $project = new Project();
            $project->user_id = $this->id;
            $project->name = 'Personal';
            $project->save();
            app(AddTeamMember::class)->add($project, $this->email, Role::ADMIN);
        }

        $this->current_project_id = $project->id;
        $this->save();

        return $project;
    }

    public static function findByEmailOrFail(string $email)
    {
        return self::where('email', $email)->firstOrFail();
    }
}
