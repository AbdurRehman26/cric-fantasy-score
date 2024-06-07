@props([
    "href",
])

@php
    $class =
    "focus:shadow-outline inline-flex min-w-max items-center justify-center rounded-md border border-transparent bg-primary-700 px-4 py-2 text-sm font-medium tracking-wide text-white transition duration-150 duration-200 ease-in-out hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 disabled:opacity-50 dark:border-transparent dark:focus:ring-primary-500 dark:focus:ring-offset-primary-800";
@endphp

@if (isset($href))
    <button onclick="location.href = '{{ $href }}'" {{ $attributes->merge(["class" => $class]) }}>
        {{ $slot }}
    </button>
@else
    <button {{ $attributes->merge(["type" => "submit", "class" => $class]) }}>
        {{ $slot }}
    </button>
@endif
