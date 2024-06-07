<?php

namespace Database\Factories;

use App\Models\MonitorMetric;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MonitorMetricFactory extends Factory
{
    protected $model = MonitorMetric::class;

    public function definition(): array
    {
        return [
            'monitor_id' => $this->faker->randomNumber(),
            'data' => [
                'response_time' => rand(100, 500),
            ],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
