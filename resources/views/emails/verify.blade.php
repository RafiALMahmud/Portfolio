@component('mail::message')
# Hi {{ $name }},

Welcome to **L'essence** — please confirm your email by clicking the button below.

@component('mail::button', ['url' => $verifyUrl])
Verify my email
@endcomponent

If the button doesn't work, copy and paste this link into your browser:

{{ $verifyUrl }}

This link will expire in 60 minutes.

If you did not create an account with us, no further action is required.

Thanks,<br>
L'essence
@endcomponent