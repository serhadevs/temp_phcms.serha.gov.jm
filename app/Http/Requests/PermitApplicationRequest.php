<?php

namespace App\Http\Requests;

use App\Rules\ApplicationDateAfterExamDate;
use App\Rules\FileExtensions;
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
        // dd();
        $this->merge([
            'exam_date' => $this->exam_date,
            // 'photo_upload'=>$this->photo_upload->extension()
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // determine current id from route-model binding or route/input parameter
        $id = null;
        $routeParam = $this->route('permit_application') ?? $this->route('id') ?? $this->input('id');
        if ($routeParam) {
            $id = is_object($routeParam) ? $routeParam->id : $routeParam;
        }

        // build TRN rule; include the ignore-id only when available
        $trnRule = 'required|unique:permit_applications,trn';
        if ($id) {
            $trnRule .= ',' . $id;
        }

        return [
           'permit_category_id' => 'required',
            'firstname' => "required",
            'middlename' => "nullable",
            'lastname' => "required",
            'address' => 'required',
            'date_of_birth' => 'required|date',
            'gender' => 'required',
            'permit_type' => 'required',
            'no_of_years' => [ 'nullable', 'numeric', 'required_if:permit_type,student'],
            'cell_phone' => 'nullable',
            'home_phone' => 'nullable',
            'work_phone' => 'nullable',
            'occupation' => 'nullable',
            'employer' => 'nullable',
            'employer_address' => 'nullable',
            'email' => 'nullable|email',
            'trn' => $trnRule,

            'applied_before' => 'required',
            'granted' => 'required_if:applied_before,=,1',
            'reason' => 'nullable',
            // 'photo_upload' => 'nullable|extensions:jpg,png,jpeg',
            'photo_upload' => ['nullable', new FileExtensions($this->photo_upload? pathinfo($this->photo_upload->getClientOriginalName(), PATHINFO_EXTENSION): '')],
            'exam_date' => 'required',
            'exam_session' => 'required',
            // 'application_date' => 'required',
            'application_date' => ['required', 'date', new ApplicationDateAfterExamDate($this->exam_date)],
        ];
    }
}
