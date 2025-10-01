@component('mail::message')
# Hi {{ $name }},

You requested a password reset for your L'essence account. Click the button below to reset your password.

@component('mail::button', ['url' => $resetUrl])
Reset Password
@endcomponent

If the button doesn't work, copy and paste this link into your browser:

{{ $resetUrl }}

This link will expire in 60 minutes.

If you did not request a password reset, no further action is required.

Thanks,<br>
L'essence
@endcomponent