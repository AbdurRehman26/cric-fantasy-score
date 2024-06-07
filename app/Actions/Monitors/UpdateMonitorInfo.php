<?php

namespace App\Actions\Monitors;

use App\Models\Monitor;
use Illuminate\Support\Facades\Validator;

class UpdateMonitorInfo
{
    public function update(Monitor $monitor, array $input): Monitor
    {
        $this->validate($monitor, $input);

        $monitor->name = $input['name'];
        $monitor->save();

        return $monitor;
    }

    private function validate(Monitor $monitor, array $input): void
    {
        Validator::make($input, [
            'name' => 'required|string|max:255',
        ])->validate();
    }
}
