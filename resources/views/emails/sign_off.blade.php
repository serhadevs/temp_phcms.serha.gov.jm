@component('mail::message')
# Signed Off Confirmation

{{-- Dear {{ $applicant['firstname'] }} {{ $applicant['lastname'] }}, --}}

This email serves to inform you that your Food Handler's Application has been signed off and is being prepared for printing.

If you have any questions or need further assistance, please do not hesitate to contact us.

Thank you for choosing our services.

Best regards,  
{{ config('app.name') }}
@endcomponent