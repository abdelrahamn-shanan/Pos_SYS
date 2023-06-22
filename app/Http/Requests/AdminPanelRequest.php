<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminPanelRequest extends FormRequest
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
            "system_name" => 'required|string',
            'address'  => 'required|string',
            'phone'  => 'required|string',
            'photo' => 'required_without:id|mimes:jpg,jpeg,png',
            "customer_parent_account_number" => 'required',
            "suppliers_parent_account_number" => 'required',


            
        ];
    }

    public function messages()
    {
        return [
           'required'=>'هذا الحقل مطلوب ',
           'customer_parent_account_number.required'=>'رقم الحساب المالى للعملاء مطلوب',
           'suppliers_parent_account_number.required'=>'رقم الحساب المالى للموردين مطلوب',


        ];
    }
}