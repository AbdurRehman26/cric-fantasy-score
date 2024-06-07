@props([
    "disabled" => false,
    "id",
    "name",
    "value",
])

<div class="flex items-center">
    <input
        id="{{ $id }}"
        name="{{ $name }}"
        type="checkbox"
        value="{{ $value }}"
        {{ $attributes->merge(["disabled" => $disabled, "class" => "h-4 w-4 rounded border-gray-300 dark:border-gray-700 bg-primary-100 text-primary-700 focus:ring-2 focus:ring-black dark:border-primary-600 dark:bg-primary-700 dark:ring-offset-primary-800 dark:focus:ring-primary-600 dark:focus:ring-offset-primary-800"]) }}
    />
    <label for="{{ $id }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
        {{ $slot }}
    </label>
</div>
