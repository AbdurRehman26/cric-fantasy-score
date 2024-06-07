<x-app-layout>
    <x-slot name="pageTitle">{{ __("Monitors") }}</x-slot>

    <x-slot name="header">
        <div class="flex w-full items-center justify-between">
            <x-header>{{ __("Monitors") }}</x-header>
            <div x-data="">
                <x-primary-button x-on:click="$dispatch('open-modal', 'create-monitor-modal')">
                    Create new Monitor
                </x-primary-button>
                @include("monitors.partials.create-monitor-modal")
            </div>
        </div>
    </x-slot>

    <div x-data="{ deleteAction: '' }">
        <x-container class="py-8">
            <div
                id="monitors-list"
                hx-get="{{ request()->url() }}"
                hx-trigger="every 10s"
                hx-select="#monitors-list"
                hx-swap="outerHTML"
            >
                @if (isset($monitors[0]))
                    <div class="space-y-6">
                        @foreach ($monitors as $monitor)
                            <x-simple-card
                                class="grid grid-cols-2 gap-5 lg:grid-cols-5 lg:gap-2"
                                id="monitor-row-{{ $monitor->id }}"
                            >
                                <div class="col-span-1 flex items-center">
                                    <a href="{{ $monitor->address }}" target="_blank" class="hover:underline">
                                        {{ $monitor->name }}
                                    </a>
                                </div>
                                <div class="col-span-1 flex items-center justify-end uppercase lg:justify-start">
                                    {{ $monitor->type }}
                                </div>
                                <div class="col-span-1 flex items-center">
                                    @if ($monitor->lastRecord())
                                        {{ __("Last checked") }}
                                        {{ $monitor->lastRecord()?->created_at->diffForHumans() ?? "-" }}
                                    @else
                                        {{ __("Never checked") }}
                                    @endif
                                </div>
                                <div class="col-span-1 flex items-center justify-end lg:justify-start">
                                    @include("monitors.partials.status", ["monitor" => $monitor])
                                </div>
                                <div class="col-span-2 flex justify-end lg:col-span-1">
                                    <x-icon-button :href="route('monitors.metrics', ['monitor' => $monitor])">
                                        <x-heroicon-o-chart-bar class="h-6 w-6" />
                                    </x-icon-button>
                                    @if ($monitor->isRunning())
                                        <x-icon-button
                                            hx-get="{{ route('monitors.status', ['monitor' => $monitor, 'status' => \App\Enums\MonitorStatus::PAUSED->value]) }}"
                                            hx-select="#monitor-row-{{ $monitor->id }}"
                                            hx-target="#monitor-row-{{ $monitor->id }}"
                                            hx-swap="outerHTML"
                                        >
                                            <x-heroicon-o-pause-circle class="h-6 w-6" />
                                        </x-icon-button>
                                    @elseif ($monitor->isPaused())
                                        <x-icon-button
                                            hx-get="{{ route('monitors.status', ['monitor' => $monitor, 'status' => \App\Enums\MonitorStatus::RUNNING->value]) }}"
                                            hx-select="#monitor-row-{{ $monitor->id }}"
                                            hx-target="#monitor-row-{{ $monitor->id }}"
                                            hx-swap="outerHTML"
                                        >
                                            <x-heroicon-o-play-circle class="h-6 w-6" />
                                        </x-icon-button>
                                    @endif
                                    <x-icon-button :href="route('monitors.show', ['monitor' => $monitor])">
                                        <x-heroicon-o-cog-6-tooth class="h-6 w-6" />
                                    </x-icon-button>
                                </div>
                            </x-simple-card>
                        @endforeach
                    </div>
                @else
                    <x-container class="max-w-3xl">
                        <x-simple-card>
                            <p class="text-center dark:text-white">
                                {{ __("You don't have a monitor yet! Create the first one now") }}
                            </p>
                        </x-simple-card>
                    </x-container>
                @endif
            </div>
        </x-container>
    </div>
</x-app-layout>
