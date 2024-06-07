<?php

namespace Database\Factories;

use App\Enums\MonitorStatus;
use App\Models\Monitor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Monitor>
 */
class MonitorFactory extends Factory
{
    protected $model = Monitor::class;

    public function definition(): array
    {
        return [
            'type' => 'http',
            'type_data' => [
                'address' => $this->faker->url(),
                'threshold' => '10s',
            ],
            'locations' => ['de'],
            'name' => $this->faker->name(),
            'status' => MonitorStatus::RUNNING,
        ];
    }
}
