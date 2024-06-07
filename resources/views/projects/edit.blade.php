<x-app-layout>
    <x-slot name="pageTitle">{{ $pageTitle }}</x-slot>

    <x-slot name="header">
        <div class="flex w-full items-center justify-between">
            <x-header>{{ $pageTitle }}</x-header>
            <x-secondary-button :href="route('projects')">{{ __("Back to Projects") }}</x-secondary-button>
        </div>
    </x-slot>

    <x-container class="max-w-3xl space-y-6">
        @include("projects.partials.edit-project-form", ["project" => $project])
        @include("projects.project-member-manager", ["project" => $project])
    </x-container>
</x-app-layout>
