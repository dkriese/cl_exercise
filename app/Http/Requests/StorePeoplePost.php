<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePeoplePost extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'data.*.first_name' => 'required|max:255',
            'data.*.last_name' => 'required|max:255',
            'data.*.email' => 'required|email',
            'data.*.age' => 'required|numeric|min:0|max:125',
            'data.*.secret' => 'required|max:255'
        ];
    }

    public function messages()
    {
        return [
            'data.*.first_name.required' => 'First Name is required',
            'data.*.last_name.required' => 'Last Name is required',
            'data.*.email.required' => 'Email is required',
            'data.*.age.required' => 'Age is required',
            'data.*.secret.required' => 'Secret is require'
        ];
    }
}