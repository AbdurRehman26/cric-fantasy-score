@component("mail::message")
    {{ $text }}

    Regards,
    <br />
    {{ config("app.name") }}
@endcomponent
