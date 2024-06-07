<?php

namespace App\Mail;

use App\Models\ProjectInvitation as ProjectInvitationModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class ProjectInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public ProjectInvitationModel $invitation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ProjectInvitationModel $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.project-invitation', ['acceptUrl' => URL::signedRoute('project-invitations.accept', [
            'projectInvitation' => $this->invitation,
        ])])->subject(__('Project Invitation'));
    }
}
