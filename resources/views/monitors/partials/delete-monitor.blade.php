<x-card>
    <header>
        <h2 class="text-lg font-medium">
            {{ __("Delete Monitor") }}
        </h2>

        <p class="mt-1 text-sm">
            {{ __("Once your monitor is deleted, all of its records will be permanently deleted and the monitor will be disappeared from the status pages!") }}
        </p>
    </header>

    <x-danger-button class="mt-6" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-monitor-deletion')">
        {{ __("Delete Account") }}
    </x-danger-button>

    <x-modal name="confirm-monitor-deletion" :show="$errors->monitorDeletion->isNotEmpty()" focusable>
        <form
            id="delete-monitor-form"
            hx-post="{{ route("monitors.destroy", ["monitor" => $monitor]) }}"
            hx-swap="outerHTML"
            hx-select="#delete-monitor-form"
            class="p-6"
        >
            @csrf
            @method("delete")

            <h2 class="text-lg font-medium">
                {{ __("Are you sure you want to delete this monitor?") }}
            </h2>

            <div class="mt-6 flex justify-end">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">
                    {{ __("Cancel") }}
                </x-secondary-button>

                <x-danger-button class="ml-3">
                    {{ __("Delete Monitor") }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</x-card>
