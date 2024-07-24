<?php

namespace App\Http\Requests;

use App\Rules\ApplicationDateAfterExamDate;
use Illuminate\Foundation\Http\FormRequest;

class PermitApplicationRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge([
            'exam_date' => $this->exam_date,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
           'permit_category_id' => 'required',
            'firstname' => "required",
            'middlename' => "nullable",
            'lastname' => "required",
            'address' => 'required',
            'date_of_birth' => 'required|date',
            'gender' => 'required',
            'permit_type' => 'required',
            'no_of_years' => ['required_if:permit_type,student'],
            'cell_phone' => 'nullable',
            'home_phone' => 'nullable',
            'work_phone' => 'nullable',
            'occupation' => 'nullable',
            'employer' => 'nullable',
            'employer_address' => 'nullable',
            'email' => 'nullable|email',
            'trn' => 'nullable',
            'applied_before' => 'required',
            'granted' => 'required_if:applied_before,=,1',
            'reason' => 'nullable',
            'photo_upload' => 'nullable',
            'exam_date' => 'required',
            'exam_session' => 'required',
            'application_date' => 'required',
            // 'application_date' => ['required', 'date', new ApplicationDateAfterExamDate($this->exam_date)],
        ];
    }
}
