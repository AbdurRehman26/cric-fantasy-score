@if ($project->projectInvitations->isNotEmpty())
    <x-card>
        <div x-data="{ 'cancelAction': '' }">
            <header>
                <h2 class="text-lg font-medium">
                    {{ __("Pending Team Invitations") }}
                </h2>

                <p class="mt-1 text-sm">
                    {{ __("These people have been invited to your team and have been sent an invitation email. They may join the team by accepting the email invitation.") }}
                </p>
            </header>

            <!-- Team Member Invitations -->
            <div class="mt-10 sm:mt-5">
                <x-table>
                    <x-thead>
                        <tr>
                            <x-th>{{ __("Email") }}</x-th>
                            <x-th>{{ __("Invited On") }}</x-th>
                            <x-th></x-th>
                        </tr>
                    </x-thead>
                    @foreach ($project->projectInvitations as $invitation)
                        <x-tr>
                            <x-td>{{ $invitation->email }}</x-td>
                            <x-td>
                                <x-datetime :value="$invitation->created_at" />
                            </x-td>
                            <x-td>
                                <div class="flex items-center justify-end">
                                    @if (Gate::check("delete", $project))
                                        <button
                                            class="ms-6 cursor-pointer text-sm text-red-500 focus:outline-none"
                                            @click.prevent="cancelAction  = '{{ route("project-invitations.destroy", ["projectInvitation" => $invitation->id]) }}'; $dispatch('open-modal', 'confirm-invitation-cancel')"
                                        >
                                            {{ __("Cancel") }}
                                        </button>
                                    @endif
                                </div>
                            </x-td>
                        </x-tr>
                    @endforeach
                </x-table>
            </div>

            <x-modal name="confirm-invitation-cancel" focusable>
                <form id="cancel-invitation-form" method="post" x-bind:action="cancelAction" class="p-6">
                    @csrf
                    @method("delete")

                    <h2 class="text-lg font-medium">
                        {{ __("Are you sure you want to cancel this invitation?") }}
                    </h2>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button type="button" x-on:click="$dispatch('close')">
                            {{ __("No") }}
                        </x-secondary-button>

                        <x-danger-button class="ml-3">
                            {{ __("Yes") }}
                        </x-danger-button>
                    </div>
                </form>
            </x-modal>
        </div>
    </x-card>
@endif
