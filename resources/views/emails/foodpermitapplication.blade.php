@component('mail::message')

<img src="{{ asset('/images/serha_logo.png') }}" alt="Your Logo" width="200">

<p>Dear {{ $user->firstname }}</p>



<p>Thank you,<br>
South East Regional Health Authority

    
@endcomponent