@component('mail::message')
    <p>Dear User,</p>
    <p>Thank you for starting the online application process for your food handlers permit.</p>
    <p>Please use the link below to verify your email and continue the application process.</p>
    <a href="{{ $signature_link }}">Verification Link</a>
    <br><br>
    <p>Thank you, <br>
        South East Regional Health Authority</p>
@endcomponent
