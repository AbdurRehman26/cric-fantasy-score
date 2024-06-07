@if ($monitor->isRunning())
    @if ($monitor->lastRecord())
        <x-status status="success">
            {{ __("Running") }}
        </x-status>
    @else
        <x-status status="warning">
            {{ __("Checking") }}
        </x-status>
    @endif
@else
    <x-status status="disabled">
        {{ __("Paused") }}
    </x-status>
@endif
