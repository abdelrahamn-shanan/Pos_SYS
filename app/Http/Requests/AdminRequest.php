<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
            "username" => 'required|min:2',
            'password'  => 'required|min:5'
        ];
    }
    public function messages()
    {
        return [
           'username.required'=>'هذا الحقل مطلوب ',
           'password.required'=>'هذا الحقل مطلوب',
        ];
    }
}
