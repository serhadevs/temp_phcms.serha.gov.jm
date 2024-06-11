@component('mail::message')
    <p>Dear {{ $new_permit_application->firstname }} {{ $new_permit_application->lastname }}</p>

    <p>Thank you for submitting your application for your {{ $new_permit_application->permit_type }}. We are pleased to inform
    you that we have received it successfully.</p>

   <p>The confirmation of the appointment date and time will be sent to you once you have made payment.</p>

    <p>If you have any questions or need further assistance, please feel free to contact us at 876-555-5555</p>

    <p>Thank you again for choosing us. We look forward to assisting you further.</p>


    <p>Thank you, for applying<br>
        South East Regional Health Authority</p>
    @endcomponent
