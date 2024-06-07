<x-app-layout>
    <x-slot name="pageTitle">{{ $pageTitle }}</x-slot>

    <x-slot name="header">
        <div class="flex w-full items-center justify-between">
            <x-header>
                {{ __("Notification Channels") }}
            </x-header>
            <div x-data="">
                <x-primary-button x-on:click="$dispatch('open-modal', 'create-modal')">New Channel</x-primary-button>
                @include("notification-channels.partials.create-notification-channel-modal")
            </div>
        </div>
    </x-slot>

    <div x-data="{ deleteAction: '', deleteMonitors: [] }">
        <x-container class="py-8">
            @if (isset($channels[0]))
                <x-table>
                    <x-thead>
                        <tr>
                            <x-th>{{ __("Name") }}</x-th>
                            <x-th>{{ __("Type") }}</x-th>
                            <x-th>{{ __("Status") }}</x-th>
                            <x-th></x-th>
                        </tr>
                    </x-thead>
                    @foreach ($channels as $channel)
                        <x-tr id="channel-row-{{ $channel->id }}">
                            <x-td>{{ $channel->name }}</x-td>
                            <x-td class="capitalize">{{ $channel->type }}</x-td>
                            <x-td>
                                <div class="flex items-center">
                                    @if ($channel->is_connected)
                                        <x-status status="success">
                                            {{ __("Ready") }}
                                        </x-status>
                                    @else
                                        @if ($channel->type == "email")
                                            {{ __("Verification email sent!") }}
                                            <a
                                                class="ml-2 text-primary-600"
                                                href="{{ route("notification-channels.resend-email", ["notificationChannel" => $channel]) }}"
                                            >
                                                {{ __("Resend") }}
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </x-td>
                            <x-td>
                                <div class="flex items-center justify-end">
                                    <x-icon-button
                                        x-on:click="deleteAction = '{{ route('notification-channels.destroy', ['notificationChannel' => $channel]) }}';deleteMonitors = {{ \Illuminate\Support\Js::from($channel->monitors()->select('id', 'name')->get()) }};$dispatch('open-modal', 'delete-notification-channel')"
                                    >
                                        <x-heroicon-o-trash class="h-5 w-5" />
                                    </x-icon-button>
                                </div>
                            </x-td>
                        </x-tr>
                    @endforeach
                </x-table>
                @include("notification-channels.partials.delete-notification-channel-modal")
            @else
                <x-container class="max-w-3xl">
                    <x-simple-card>
                        <p class="text-center dark:text-white">
                            {{ __("You don't have any notification channels yet!") }}
                        </p>
                    </x-simple-card>
                </x-container>
            @endif
        </x-container>
    </div>
</x-app-layout>
