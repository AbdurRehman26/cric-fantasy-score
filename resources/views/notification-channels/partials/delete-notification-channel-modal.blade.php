<x-modal name="delete-notification-channel" :show="$errors->deleteNotificationChannel->isNotEmpty()">
    <form id="delete-notification-channel-form" method="post" x-bind:action="deleteAction" class="p-6">
        @csrf
        @method("delete")

        <h2 class="text-lg font-medium">
            {{ __("Are you sure you want to delete this channel?") }}
        </h2>

        <div x-show="deleteMonitors.length > 0" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            {{ __("This channel is being used in the following monitors:") }}
            <ul class="mt-1 text-primary-600">
                <template x-for="monitor in deleteMonitors">
                    <li>
                        <a x-bind:href="'{{ url("/monitors") }}/' + monitor.id" x-text="monitor.name"></a>
                    </li>
                </template>
            </ul>
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button type="button" x-on:click="$dispatch('close')">
                {{ __("Cancel") }}
            </x-secondary-button>

            <x-danger-button class="ml-3">
                {{ __("Delete") }}
            </x-danger-button>
        </div>
    </form>
</x-modal>
