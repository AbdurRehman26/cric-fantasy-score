<?php

use App\Models\Monitor;

test('can see the monitors', function () {
    $user = $this->createUserWithProject();

    $monitor = Monitor::factory()->create([
        'project_id' => $this->project->id,
    ]);

    $response = $this->actingAs($user)
        ->get(route('monitors.index'));

    $response
        ->assertOk()
        ->assertSee($monitor->address);
});

test('can create a monitor', function () {
    $user = $this->createUserWithProject();

    $response = $this->actingAs($user)
        ->post(route('monitors.store'), [
            'type' => 'http',
            'name' => 'Google',
            'address' => 'https://google.com',
        ]);

    $response
        ->assertOk()
        ->assertHeader('HX-Redirect', route('monitors.index'));

    $this->assertDatabaseHas('monitors', [
        'project_id' => $this->project->id,
        'type' => 'http',
    ]);
});
