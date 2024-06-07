@props([
    "interval" => "10s",
    "id",
])

<div
    id="{{ $id }}"
    hx-get="{{ request()->url() }}"
    hx-trigger="every {{ $interval }}"
    hx-select="#{{ $id }}"
    hx-swap="outerHTML"
>
    {{ $slot }}
</div>
