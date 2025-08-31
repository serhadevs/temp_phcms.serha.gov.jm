@component('mail::message')
# Welcome to PHCMS 2.0

Hello {{ $newUser->firstname }} {{ $newUser->lastname }},

Your account has been created successfully. Here are your login credentials:

**Email:** {{ $newUser->email }}  
**Password:** {{ $stringPassword }}

Please change your password after your first login for security purposes.

@component('mail::button', ['url' => config('app.url')])
Login Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent