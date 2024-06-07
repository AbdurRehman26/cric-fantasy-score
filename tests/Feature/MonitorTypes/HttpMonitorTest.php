<?php

use App\Enums\MonitorEventType;
use App\Models\Monitor;
use App\Models\MonitorEvent;
use App\Models\MonitorRecord;
use App\MonitorTypes\HttpMonitor;

test('it creates rules correctly', function () {
    $this->createUserWithProject();
    $monitor = Monitor::factory()->create([
        'project_id' => $this->project->id,
    ]);
    $httpMonitor = new HttpMonitor($monitor);
    $input = ['address' => 'http://example.com'];

    $rules = $httpMonitor->createRules($input);

    expect($rules)->toHaveKey('address');
    expect($rules['address'])->toEqual(['required', 'active_url']);
});

test('it creates data correctly', function () {
    $this->createUserWithProject();
    $monitor = Monitor::factory()->create([
        'project_id' => $this->project->id,
    ]);
    $httpMonitor = new HttpMonitor($monitor);

    $input = ['address' => 'http://example.com'];

    $data = $httpMonitor->createData($input);

    expect($data)->toHaveKey('address');
    expect($data)->toHaveKey('threshold');
});

test('it updates rules correctly', function () {
    $this->createUserWithProject();
    $monitor = Monitor::factory()->create([
        'project_id' => $this->project->id,
    ]);
    $httpMonitor = new HttpMonitor($monitor);

    $input = ['address' => 'http://example.com', 'threshold' => '5 minutes'];

    $rules = $httpMonitor->updateRules($input);

    expect($rules)->toHaveKey('address');
    expect($rules)->toHaveKey('threshold');
});

test('it updates data correctly', function () {
    $this->createUserWithProject();
    $monitor = Monitor::factory()->create([
        'project_id' => $this->project->id,
    ]);
    $httpMonitor = new HttpMonitor($monitor);

    $input = ['address' => 'http://example.com', 'threshold' => '5 minutes'];

    $data = $httpMonitor->updateData($input);

    expect($data)->toHaveKey('address');
    expect($data)->toHaveKey('threshold');
});

test('it checks if monitor is healthy', function () {
    $this->createUserWithProject();
    $monitor = Monitor::factory()->create([
        'project_id' => $this->project->id,
    ]);
    $httpMonitor = new HttpMonitor($monitor);

    $record = new MonitorRecord(['data' => ['is_up' => true]]);

    expect($httpMonitor->isHealthy($record))->toBeTrue();
});

test('it checks if monitor is not healthy', function () {
    $this->createUserWithProject();
    $monitor = Monitor::factory()->create([
        'project_id' => $this->project->id,
    ]);
    $httpMonitor = new HttpMonitor($monitor);

    $record = new MonitorRecord(['data' => ['is_up' => false]]);

    expect($httpMonitor->isHealthy($record))->toBeFalse();
});

test('it checks if threshold is passed', function () {
    $this->createUserWithProject();
    $monitor = Monitor::factory()->create([
        'project_id' => $this->project->id,
        'locations' => ['de'],
    ]);

    MonitorRecord::factory()->create([
        'monitor_id' => $monitor->id,
        'location' => 'de',
        'created_at' => now()->subMinutes(10),
    ]);

    $lastRecord = MonitorRecord::factory()->create([
        'monitor_id' => $monitor->id,
        'location' => 'de',
        'created_at' => now(),
    ]);

    $lastEvent = MonitorEvent::factory()->create([
        'monitor_id' => $monitor->id,
        'location' => 'de',
        'type' => MonitorEventType::FAILED->value,
        'created_at' => now()->subMinutes(5),
    ]);

    expect($monitor->type()->isThresholdPassed($lastRecord, $lastEvent))->toBeTrue();
});

test('threshold is not passed', function () {
    $this->createUserWithProject();
    $monitor = Monitor::factory()->create([
        'project_id' => $this->project->id,
        'locations' => ['de'],
    ]);
    $httpMonitor = new HttpMonitor($monitor);

    $now = now();
    $record = MonitorRecord::factory()->create([
        'monitor_id' => $monitor->id,
        'location' => 'de',
        'created_at' => $now,
    ]);

    $lastEvent = MonitorEvent::factory()->create([
        'monitor_id' => $monitor->id,
        'location' => 'de',
        'type' => MonitorEventType::FAILED->value,
        'created_at' => $now->subSeconds(5), // the threshold is 10 seconds
    ]);

    expect($httpMonitor->isThresholdPassed($record, $lastEvent))->toBeFalse();
});
