@props([
    "active",
])

@php
    $classes =
        $active ?? false
            ? "group flex items-center rounded-md bg-gray-100 p-2 text-black dark:bg-gray-900 dark:text-white"
            : "group flex items-center rounded-md p-2 text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700";
@endphp

<a {{ $attributes->merge(["class" => $classes]) }}>
    {{ $slot }}
</a>
