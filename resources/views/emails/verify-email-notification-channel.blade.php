@component("mail::message")
    # Verify Email Address Please click the button below to verify your email address.

    @component("mail::button", ["url" => \Illuminate\Support\Facades\URL::signedRoute("notification-channels.verify-email", ["notificationChannel" => $id, "email" => $email])])
        Verify Email Address
    @endcomponent

    Thanks,
    <br />
    {{ config("app.name") }}
@endcomponent
