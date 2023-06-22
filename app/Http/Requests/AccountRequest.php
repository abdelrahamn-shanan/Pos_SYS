<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
            'name'=>'required_without:id',
            'account_type'=>'required',
            'is_parent'=>'required',
            'parent_account_number'=>'required_if:is_parent,0', // use enum best practice
            'start_balance_status'=>'required_without:id',
            'start_balance'=>'required_without:id|min:0',
            'active'=>'required',
        ];
    }

    public function messages(){
        return[
        'name.required' => 'اسم الحساب مطلوب',
        'account_type.required' => 'نوع الحساب مطلوب',
        'is_parent.required' => ' هل الحساب اب مطلوب',
        'parent_account_number.required_if' => '  الحساب الاب مطلوب',
        'start_balance_status.required' => '   حالة الحساب اول المدة مطلوب',
        'start_balance.required' => '    رصيد اول المدة مطلوب',
        'active.required' => '   حالة تفعيل الصنف مطلوب',
        ];
    }
}