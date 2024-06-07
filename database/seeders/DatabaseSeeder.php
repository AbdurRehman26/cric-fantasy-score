<?php

namespace Database\Seeders;

use App\Enums\MonitorEventType;
use App\Enums\MonitorType;
use App\Models\Monitor;
use App\Models\MonitorEvent;
use App\Models\MonitorMetric;
use App\Models\MonitorRecord;
use App\Models\NotificationChannel;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->benchMark();

        return;
        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
        ]);
        $project = $user->createDefaultProject();

        $this->generateMonitorMetrics(
            $this->generateMonitor($project)
        );
    }

    private function generateMonitor(Project $project): Monitor
    {
        $monitor = Monitor::factory()->create([
            'project_id' => $project->id,
            'name' => 'Fake Monitor',
            'type' => MonitorType::HTTP,
            'locations' => ['de', 'es'],
            'type_data' => [
                'address' => 'https://fakeaddress123.com',
                'threshold' => '0s',
            ],
        ]);
        MonitorRecord::factory(1000)->create([
            'monitor_id' => $monitor->id,
            'location' => 'de',
        ]);
        MonitorRecord::factory(1000)->create([
            'monitor_id' => $monitor->id,
            'location' => 'es',
        ]);
        MonitorEvent::factory(5)->create([
            'monitor_id' => $monitor->id,
            'location' => 'de',
            'type' => MonitorEventType::FIRING,
        ]);
        $monitor->save();
        $notificationChannel = NotificationChannel::factory()->create([
            'project_id' => $project->id,
            'name' => 'Email',
            'type' => 'email',
            'type_data' => [
                'email' => 'user@example.com',
            ],
            'is_connected' => true,
        ]);
        $monitor->notificationChannels()->attach($notificationChannel->id);

        return $monitor;
    }

    private function generateMonitorMetrics(Monitor $monitor): void
    {
        $range = CarbonPeriod::create(Carbon::now()->subDays(10), Carbon::now());
        foreach ($range as $date) {
            MonitorMetric::factory()->create([
                'monitor_id' => $monitor->id,
                'location' => 'de',
                'created_at' => $date,
                'updated_at' => $date,
            ]);
            MonitorMetric::factory()->create([
                'monitor_id' => $monitor->id,
                'location' => 'es',
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }

    private function benchMark(): void
    {
        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
        ]);
        $project = $user->createDefaultProject();
        Monitor::factory(1)->create([
            'project_id' => $project->id,
            'locations' => ['de'],
        ]);
        MonitorRecord::factory(1000)->create([
            'monitor_id' => 1,
            'location' => 'de',
        ]);
        $notificationChannel = NotificationChannel::factory()->create([
            'project_id' => $project->id,
            'name' => 'Email',
            'type' => 'email',
            'type_data' => [
                'email' => 'user@example.com',
            ],
            'is_connected' => true,
        ]);
        Monitor::query()->each(function (Monitor $monitor) use ($notificationChannel) {
            $monitor->notificationChannels()->attach($notificationChannel->id);
        });
    }
}
