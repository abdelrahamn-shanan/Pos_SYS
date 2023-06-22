<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
         'name'=>'required',
         'phone'=>'required_without:id',
         'address'=>'required_without:id',
         'active'=>'required',
        ];
    }

    public function messages()
    {
        return [
        'name.required'=>'اسم المخزن مطلوب',
        'phone.required'=>'هاتف المخزن مطلوب',
        'address.required'=>'عنوان المخزن مطلوب',
        'active.required'=>'حالة تفعيل المخزن مطلوب',


        ];
    }

}