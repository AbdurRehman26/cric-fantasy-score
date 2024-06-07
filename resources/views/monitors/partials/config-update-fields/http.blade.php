<div>
    <x-input-label for="address" :value="__('Address')" />
    <x-text-input
        id="address"
        name="address"
        type="text"
        class="mt-1 block w-full"
        :value="old('address', $monitor->type()->data()['address'])"
        autocomplete="url"
    />
    <x-input-error class="mt-2" :messages="$errors->get('address')" />
</div>
<div>
    <x-input-label for="threshold" :value="__('Threshold')" />
    <x-select-input
        id="threshold"
        name="threshold"
        class="mt-1 block w-full"
        :value="old('threshold', $monitor->type()->data()['threshold'])"
    >
        @foreach (config("core.monitor_types.http.thresholds") as $value)
            <option value="{{ $value }}" @if($value == $monitor->type()->data()['threshold']) selected @endif>
                {{ $value }}
            </option>
        @endforeach
    </x-select-input>
    <x-input-error class="mt-2" :messages="$errors->get('threshold')" />
</div>
