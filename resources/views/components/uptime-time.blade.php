@props([
    "value",
])

@php
    $class = "text-green-500";
    if ($value < 95) {
        $class = "text-red-500";
    }
@endphp

<div>
    <div {{ $attributes->merge(["class" => $class]) }}>{{ $value }}%</div>
</div>
