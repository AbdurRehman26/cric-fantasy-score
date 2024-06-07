<x-app-layout>
    <x-slot name="header">
        <div class="flex w-full items-center justify-between">
            <x-header>{{ $monitor->name }} - {{ __("Metrics") }}</x-header>
            <x-secondary-button :href="route('monitors.index')">{{ __("Back to Monitors") }}</x-secondary-button>
        </div>
    </x-slot>

    <x-container class="w-full max-w-full space-y-6 py-8">
        @include("monitors.partials.metrics.filter")
        @include("monitors.partials.metrics." . $monitor->type)
    </x-container>
</x-app-layout>
