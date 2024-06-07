<?php

use App\Models\Monitor;
use App\Models\MonitorRecord;

test('can see the metrics page', function () {
    $user = $this->createUserWithProject();

    $monitor = Monitor::factory()->create([
        'project_id' => $this->project->id,
    ]);

    $response = $this->actingAs($user)
        ->get(route('monitors.metrics', ['monitor' => $monitor]));

    $response
        ->assertOk()
        ->assertSee($monitor->name);
});

test('it can see the last checks', function () {
    $user = $this->createUserWithProject();

    $monitor = Monitor::factory()->create([
        'project_id' => $this->project->id,
    ]);

    MonitorRecord::factory(10)->create([
        'monitor_id' => $monitor->id,
        'location' => 'de',
    ]);

    $response = $this->actingAs($user)
        ->get(route('monitors.metrics', ['monitor' => $monitor]));

    $response
        ->assertOk()
        ->assertSee('Last Checks')
        ->assertSee('de');
});
