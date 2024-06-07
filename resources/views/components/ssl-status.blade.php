@props([
    "ssl",
])

@php
    $status = $ssl ? "success" : "danger";
@endphp

<div>
    <x-status {{ $attributes }} :status="$status">
        <div class="flex items-center">
            <x-heroicon-o-shield-check class="h-3 w-3" />
            <span class="capitalize">{{ $ssl ? "Valid" : "Invalid" }} SSL</span>
        </div>
    </x-status>
</div>
