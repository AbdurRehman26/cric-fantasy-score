@props(["disabled" => false])

<input
    {{ $disabled ? "disabled" : "" }}
    {!! $attributes->merge(["class" => "flex w-full h-10 px-3 py-2 text-sm bg-white dark:bg-gray-700 border rounded-md border-gray-300 dark:border-gray-600 placeholder:text-gray-500 focus:border-primary-300 dark:focus:border-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-primary-800 focus:ring-primary-700 dark:focus:ring-primary-600 disabled:cursor-not-allowed disabled:opacity-50"]) !!}
/>
