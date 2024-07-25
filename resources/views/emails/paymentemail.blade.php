@component('mail::message')
# Payment Confirmation

{{-- Dear {{ $applicant['firstname'] }} {{ $applicant['lastname'] }}, --}}

Thank you for your payment. We have received your payment of **${{ number_format($register_new_payment->amount_paid, 2) }}** for your application.

## Payment Details
- **Receipt Number:** {{ $receipt_number }}
- **Amount Paid:** ${{ number_format($register_new_payment->amount_paid, 2) }}
- **Total Cost:** ${{ number_format($register_new_payment->total_cost, 2) }}
- **Payment Date:** {{ \Carbon\Carbon::now()->format('F d Y') }}
- **Cashier:** {{ $cashier_name }}

If you have any questions or need further assistance, please do not hesitate to contact us.

Thank you for choosing our services.

Best regards,  
{{ config('app.name') }}
@endcomponent