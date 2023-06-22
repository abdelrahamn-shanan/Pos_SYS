<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class bill_approverequest extends FormRequest
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
            "total_cost_items"=>'required',
            "tax_percent"=> 'required|max:100',
            "tax_value"=>'required',
            "total_befor_discount"=>'required',
            "discount_value"=>'required',
            "total_cost"=>'required',
            "treasuries_id"=>'required',
            ""

        ];
    }


    public function messages()
    {
        return [
            'required'=> 'هذا الحقل مطلوب',
           

        ];
    }
}