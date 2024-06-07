<?php

namespace App\Actions\Monitors;

use App\Models\Monitor;
use Illuminate\Support\Facades\Validator;

class UpdateMonitorConfigs
{
    public function update(Monitor $monitor, array $input): Monitor
    {
        $this->validate($monitor, $input);

        $monitor->type_data = $monitor->type()->updateData($input);
        $monitor->save();

        return $monitor;
    }

    private function validate(Monitor $monitor, array $input): void
    {
        Validator::make($input, $monitor->type()->updateRules($input))->validate();
    }
}
