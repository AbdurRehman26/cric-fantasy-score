<div x-data="getCreateData()">
    <x-modal name="create-modal">
        <form
            id="create-form"
            hx-post="{{ route("notification-channels.store") }}"
            hx-swap="outerHTML"
            hx-select="#create-form"
            class="p-6"
        >
            @csrf

            <h2 class="text-lg font-medium">
                {{ __("Create Notification Channel") }}
            </h2>

            <div class="mt-6">
                <x-input-label for="type" :value="__('Type')" />
                <x-select-input id="type" name="type" class="mt-1 block w-full" x-model="type">
                    @foreach (array_keys(config("core.notification_channels")) as $value)
                        <option value="{{ $value }}">{{ str($value)->ucfirst() }}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('type')" />
            </div>

            <div class="mt-6">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input
                    id="name"
                    name="name"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('name')"
                    autocomplete="notification_channel_name"
                />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div x-show="type === 'email'">
                <div class="mt-6">
                    <x-input-label for="email" :value="__('Email Address')" />
                    <x-text-input
                        id="email"
                        name="email"
                        type="email"
                        class="mt-1 block w-full"
                        :value="old('email')"
                        autocomplete="email"
                        placeholder="email@example.com"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>
            </div>

            <div x-show="['slack', 'discord'].includes(type)">
                <div class="mt-6">
                    <x-input-label for="webhook_url" :value="__('Webhook URL')" />
                    <x-text-input
                        id="webhook_url"
                        name="webhook_url"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old('webhook_url')"
                        autocomplete="webhook_url"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('webhook_url')" />
                </div>
            </div>

            <div class="mt-6 flex items-center gap-4">
                <x-primary-button>{{ __("Create") }}</x-primary-button>
            </div>
        </form>
    </x-modal>
</div>
<script>
    function getCreateData() {
        return {
            type: '{{ old("type", "email") }}'
        };
    }
</script>
