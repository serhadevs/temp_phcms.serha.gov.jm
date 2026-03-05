<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
    'price_id' => 'required',
    'application_id' => 'required',
    'amount_paid' => 'required',
    'total_cost' => 'required',
    'change_amt' => 'required|numeric|min:0',
    'manual_receipt_no' => 'required_if:is_backlog,1',
    'manual_receipt_date' => 'required_if:is_backlog,1',
    'payment_type_id' => [
        'required',
        function ($attribute, $value, $fail) {
            // If has_waiver exists, only option 5 is allowed
            if ($this->has_waiver && $value != 5) {
                $fail('Please select the "Waiver" payment option since this is a waiver.');
            }

            // If there is NO waiver at all, option 5 must NOT be selected
            if (empty($this->has_waiver) && $value == 5) {
                $fail('You cannot select "Waiver" since no waiver is attached to this application.');
            }
        },
    ],
    'wire_transfer_date' => 'required_if:payment_type_id,4',
    'waiver_id' => 'required_if:has_waiver,1',
    'has_waiver' => 'nullable',
];

    }
}
