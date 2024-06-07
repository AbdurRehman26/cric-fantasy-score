<x-app-layout>
    <x-slot name="pageTitle">{{ $pageTitle }}</x-slot>

    <div x-data="{ 'deleteAction': '' }">
        <x-slot name="header">
            <div class="flex w-full items-center justify-between">
                <x-header>{{ $pageTitle }}</x-header>
                <div x-data="">
                    <x-primary-button x-on:click="$dispatch('open-modal', 'create-project-modal')">
                        Create new Project
                    </x-primary-button>
                    @include("projects.partials.create-project-modal")
                </div>
            </div>
        </x-slot>

        <x-container class="py-8">
            <x-table>
                <x-thead>
                    <tr>
                        <x-th>{{ __("Name") }}</x-th>
                        <x-th>{{ __("Created At") }}</x-th>
                        <x-th></x-th>
                    </tr>
                </x-thead>
                @foreach ($projects as $project)
                    <x-tr>
                        <x-td>{{ $project->name }}</x-td>
                        <x-td>
                            <x-datetime :value="$project->created_at" />
                        </x-td>
                        <x-td>
                            <div class="flex items-center justify-end">
                                @if (Gate::check("update", $project))
                                    <x-icon-button :href="route('projects.edit', ['project' => $project])">
                                        <x-heroicon-o-pencil-square class="h-6 w-6" />
                                    </x-icon-button>
                                    <x-icon-button
                                        x-on:click="deleteAction = '{{ route('projects.destroy', ['project' => $project]) }}';$dispatch('open-modal', 'delete-project')"
                                    >
                                        <x-heroicon-o-trash class="h-6 w-6" />
                                    </x-icon-button>
                                @endif
                            </div>
                        </x-td>
                    </x-tr>
                @endforeach
            </x-table>
        </x-container>
        @include("projects.partials.delete-project-modal")
    </div>
</x-app-layout>
