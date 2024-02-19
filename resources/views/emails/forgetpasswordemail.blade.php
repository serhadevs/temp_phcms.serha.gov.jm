@component('mail::message')

<img src="{{ asset('images/serha_logo.png') }}" alt="Your Logo" width="200">

<p>Dear {{ $user->firstname }}</p>

<p>It looks like you have forgotten your password. Please follow the link below to reset your password:</p>

@component('mail::button', ['url' => url('reset/'.$user->remember_token)])
    Reset Password
@endcomponent

<p>If you did not request a password reset, please ignore this message.</p>

<p>Thank you,<br>
{{ config('app.name') }}</p>

    
@endcomponent