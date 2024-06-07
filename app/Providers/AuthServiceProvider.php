<?php

namespace App\Providers;

use App\Models\Monitor;
use App\Models\NotificationChannel;
use App\Models\Page;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Policies\MonitorPolicy;
use App\Policies\NotificationChannelPolicy;
use App\Policies\PagePolicy;
use App\Policies\ProjectMemberPolicy;
use App\Policies\ProjectPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Project::class => ProjectPolicy::class,
        Monitor::class => MonitorPolicy::class,
        Page::class => PagePolicy::class,
        NotificationChannel::class => NotificationChannelPolicy::class,
        ProjectMember::class => ProjectMemberPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
