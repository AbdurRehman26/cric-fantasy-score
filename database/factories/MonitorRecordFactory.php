<?php

namespace Database\Factories;

use App\Models\MonitorRecord;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MonitorRecordFactory extends Factory
{
    protected $model = MonitorRecord::class;

    public function definition(): array
    {
        $timestamp = rand(
            now()->subDays(20)->timestamp,
            now()->timestamp,
        );
        $date = Carbon::createFromTimestamp($timestamp);

        return [
            'monitor_id' => $this->faker->randomNumber(),
            'data' => [
                'is_up' => true,
                'status_code' => 200,
                'response_time' => rand(100, 500),
            ],
            'created_at' => $date->format('Y-m-d H:i:s'),
            'updated_at' => $date->format('Y-m-d H:i:s'),
        ];
    }
}
