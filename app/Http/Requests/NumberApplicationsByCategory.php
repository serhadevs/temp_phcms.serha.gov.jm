<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NumberApplicationsByCategory extends FormRequest
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
                'module' => 'required|integer',
                // 'permit_categories' => 'required_if:module,1|integer',
                // 'categories' => 'required_if:module,2|integer',
                'starting_date' => 'required|date',
                'ending_date' => 'required|date'
        ];
    }
}
