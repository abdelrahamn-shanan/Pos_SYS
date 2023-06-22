<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
            'name' => 'required',
            'Supplier_Category_id' => 'required_without:id',
            'active' => 'required',
            'start_balance_status' => 'required_without:id',
            'start_balance' => 'required_without:id|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم الحساب مطلوب',
            'Supplier_Category_id.required' => 'فئة المورد مطلوب',
            'active.required' => '   حالة تفعيل الصنف مطلوب',
            'start_balance_status.required' => '   حالة الحساب اول المدة مطلوب',
            'start_balance.required' => '    رصيد اول المدة مطلوب',
        ];
    }
}