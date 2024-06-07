<button
    {{ $attributes->merge(["type" => "submit", "class" => "focus:shadow-outline inline-flex min-w-max items-center justify-center rounded-md border border-transparent bg-red-700 px-4 py-2 text-sm font-medium tracking-wide text-white transition duration-150 duration-200 ease-in-out hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 dark:border-transparent dark:focus:ring-offset-gray-800 disabled:opacity-50"]) }}
>
    {{ $slot }}
</button>
