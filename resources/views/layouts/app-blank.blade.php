<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>
            @if (isset($pageTitle))  {{ $pageTitle }} -
            @endif {{ config("app.name", "Laravel") }}
        </title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(["resources/css/app.css", "resources/js/app.js"])
    </head>
    <body class="bg-white font-sans antialiased dark:bg-gray-900">
        <div class="flex min-h-screen">
            <div class="flex min-h-screen flex-grow flex-col">
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
