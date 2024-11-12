@component('mail::message')
    <p>Dear {{ $user->firstname }} {{ $user->lastname }}</p>

    <p>Here are your login credentials for PHCMS 2.0:</p>

   <p>Username:{{ $user->email }}  </p>

    <p>Password: password123  </p>

    <p>Welcome to the team<br>
       PHCMS 2.0</p>
    @endcomponent
