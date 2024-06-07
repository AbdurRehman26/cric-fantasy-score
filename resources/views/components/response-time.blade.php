@props([
    "responseTime",
    "isUp",
])

@php
    $status = "success";
    if ($responseTime > 700) {
        $status = "danger";
    } elseif ($responseTime > 400) {
        $status = "warning";
    }
    if (! $isUp) {
        $status = "danger";
    }
@endphp

<div>
    <x-status {{ $attributes }} :status="$status">
        @if ($isUp)
            {{ $responseTime }}
            <span class="lowercase">ms</span>
        @else
            {{ __("Down") }}
        @endif
    </x-status>
</div>
