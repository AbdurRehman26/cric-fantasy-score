@props([
    "href",
])

@php
    $class =
    "inline-flex items-center justify-center px-2 py-1 font-semibold capitalize outline-0 transition hover:opacity-50 focus:ring-0 focus:ring-primary-200 disabled:opacity-25 dark:focus:ring-primary-700 dark:focus:ring-opacity-40";
@endphp

@if (isset($href))
    <a href="{{ $href }}" {{ $attributes->merge(["class" => $class]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(["class" => $class]) }}>
        {{ $slot }}
    </button>
@endif
