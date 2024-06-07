<x-card>
    <header>
        <h2 class="text-lg font-medium">
            {{ __("Notification Channels") }}
        </h2>

        <p class="mt-1 text-sm">
            {{ __("Specify the channels you would like to get notified about the monitor.") }}
        </p>
    </header>

    <form
        id="update-notification-channels"
        hx-post="{{ route("monitors.update-notification-channels", ["monitor" => $monitor]) }}"
        hx-swap="outerHTML"
        hx-select="#update-notification-channels"
        class="space-y-6"
        hx-ext="disable-element"
        hx-disable-element="#btn-save-notifications"
    >
        @csrf
        @method("patch")

        @php
            $channels = $monitor->notificationChannels->pluck("id")->toArray();
            $availableChannels = $monitor->project
                ->notificationChannels()
                ->where("is_connected", true)
                ->get();
        @endphp

        @if (count($availableChannels) > 0)
            @foreach ($availableChannels as $channel)
                <x-checkbox
                    name="channels[]"
                    :id="$channel->id"
                    :value="$channel->id"
                    :checked="in_array($channel->id, $channels)"
                    class="mr-1"
                >
                    <span class="text-sm">{{ $channel->name }} ({{ $channel->type }})</span>
                </x-checkbox>
            @endforeach
        @else
            <p class="text-sm text-orange-300">{{ __("No notification channels available.") }}</p>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button id="btn-save-notifications">{{ __("Save") }}</x-primary-button>
        </div>
    </form>
</x-card>
