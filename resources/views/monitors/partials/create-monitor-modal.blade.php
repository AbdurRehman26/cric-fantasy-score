@php
    use App\Enums\MonitorType;
@endphp

<div x-data="getCreateMonitorData()">
    <x-modal name="create-monitor-modal">
        <form
            id="create-monitor-form"
            hx-post="{{ route("monitors.store") }}"
            hx-swap="outerHTML"
            hx-select="#create-monitor-form"
            class="p-6"
        >
            @csrf

            <h2 class="text-lg font-medium">
                {{ __("Create Monitor") }}
            </h2>

            <div class="mt-6">
                <x-input-label for="type" :value="__('Type')" />
                <x-select-input id="type" name="type" class="mt-1 block w-full" x-model="type">
                    @foreach (array_keys(config("core.monitor_types")) as $value)
                        <option value="{{ $value }}">{{ str($value)->upper() }}</option>
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
                    autocomplete="name"
                />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div x-show="type === '{{ MonitorType::HTTP->value }}'">
                <div class="mt-6">
                    <x-input-label for="address" :value="__('Address')" />
                    <x-text-input
                        id="address"
                        name="address"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old('address')"
                        autocomplete="url"
                        placeholder="http(s)://example.com"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('address')" />
                </div>
            </div>

            <div class="mt-6 flex items-center gap-4">
                <x-primary-button>{{ __("Create") }}</x-primary-button>
            </div>
        </form>
    </x-modal>
</div>
<script>
    function getCreateMonitorData() {
        return {
            type: '{{ old("type", "http") }}'
        };
    }
</script>
