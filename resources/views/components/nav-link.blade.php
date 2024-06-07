@props([
    "active",
])

@php
    $classes =
        $active ?? false
            ? "flex cursor-pointer items-center justify-center rounded-md bg-gray-800 text-white transition ease-in-out"
            : "flex cursor-pointer items-center justify-center rounded-md text-white transition ease-in-out hover:bg-gray-800";
@endphp

<a {{ $attributes->merge(["class" => $classes]) }}>
    {{ $slot }}
</a>
