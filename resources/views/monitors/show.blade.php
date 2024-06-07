<x-app-layout>
    <x-slot name="pageTitle">{{ $monitor->name }} - {{ __("Configs") }}</x-slot>

    <x-slot name="header">
        <div class="flex w-full items-center justify-between">
            <x-header>{{ $monitor->name }} - {{ __("Configs") }}</x-header>
            <div class="flex items-center">
                <x-secondary-button :href="route('monitors.index')">
                    {{ __("Back to Monitors") }}
                </x-secondary-button>
                <x-primary-button :href="route('monitors.metrics', ['monitor' => $monitor])" class="ml-2">
                    <x-heroicon-o-chart-bar class="mr-1 h-5 w-5" />
                    {{ __("Metrics") }}
                </x-primary-button>
            </div>
        </div>
    </x-slot>

    <x-container class="max-w-3xl space-y-6">
        @include("monitors.partials.update-monitor-info")

        @include("monitors.partials.update-monitor-configs")

        @include("monitors.partials.update-monitor-notification-channels")

        @include("monitors.partials.delete-monitor")
    </x-container>
</x-app-layout>
