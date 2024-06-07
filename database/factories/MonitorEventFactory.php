<?php

namespace Database\Factories;

use App\Models\MonitorEvent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MonitorEventFactory extends Factory
{
    protected $model = MonitorEvent::class;

    public function definition(): array
    {
        return [
            'monitor_id' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
