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
            'payment_type_id' => 'required'
        ];
    }
}
