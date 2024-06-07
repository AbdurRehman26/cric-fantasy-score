<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ config("app.name", "Laravel") }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(["resources/css/app.css", "resources/js/app.js"])
    </head>

    <body class="font-sans antialiased">
        <div class="flex min-h-screen flex-col items-center bg-gray-50 pt-6 sm:justify-center sm:pt-0 dark:bg-gray-900">
            <div
                class="w-full max-w-sm rounded-md border border-gray-200 bg-white p-4 sm:p-6 md:p-8 dark:border-gray-700 dark:bg-gray-800"
            >
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
