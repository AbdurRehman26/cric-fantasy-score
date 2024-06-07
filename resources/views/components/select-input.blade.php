@props(["disabled" => false])

<select
    {{ $disabled ? "disabled" : "" }}
    {!! $attributes->merge(["class" => "flex w-full h-10 px-3 py-2 text-sm bg-white dark:bg-gray-700 border rounded-md border-gray-300 dark:border-gray-600 placeholder:text-gray-500 focus:border-gray-300 dark:focus:border-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 focus:ring-black dark:focus:ring-gray-600 disabled:cursor-not-allowed disabled:opacity-50"]) !!}
>
    {{ $slot }}
</select>
