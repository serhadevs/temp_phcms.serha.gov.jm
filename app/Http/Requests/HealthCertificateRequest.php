<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HealthCertificateRequest extends FormRequest
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
            'firstname' => 'required',
            'lastname' => 'required',
            'middlename' => 'nullable',
            'date_of_birth' => 'required|date',
            'sex' => 'required',
            'email' => 'nullable|email',
            'trn' => 'nullable|regex:/^[0-9]{3}\-[0-9]{3}\-[0-9]{3}+$/',
            'occupation' => 'nullable',
            'employer_address' => 'nullable',
            'employer' => 'nullable',
            'granted' => 'required_if:applied_before,1',
            'appointment_date' => 'required',
            'telephone' => 'required|regex:/^\+1+\(+[0-9]{3}+\)+[0-9]{3}+\-+[0-9]{4}+$/',
            'applied_before' => 'required',
            'reason' => 'required_if:granted,0|max:255',
            'exam_date_id' => 'required',
            'application_date' => 'required',
            'address' => 'required'
        ];
    }
}
