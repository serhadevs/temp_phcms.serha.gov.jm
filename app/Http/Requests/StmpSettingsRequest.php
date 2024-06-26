<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StmpSettingsRequest extends FormRequest
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
            'id' => 'required|integer',
            'host' => 'required|string|min:7',
            'port' => 'required|integer|min:2',
            'username' => 'required|string',
            'password' => 'required|string|min:2',
            'encryption' => 'required|string',
            'from_address' => 'required|string'
            
        ];
    }
}
