<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" hx-swap-oob="true" />

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

    <body class="bg-gray-50 font-sans antialiased dark:bg-gray-900" hx-ext="source" x-data="" x-cloak>
        @include("layouts.navigation")

        @include("layouts.sidebar")

        <div class="mt-[64px] w-full"></div>

        @if (isset($header))
            <header class="border-b border-gray-200 bg-white sm:ml-64 dark:border-gray-700 dark:bg-gray-800">
                <x-container class="flex h-20 w-full max-w-full items-center">
                    {{ $header }}
                </x-container>
            </header>
        @endif

        <div class="p-4 sm:ml-64">
            <!-- Page Heading -->
            {{ $slot }}
        </div>

        <x-toast />
    </body>
</html>
