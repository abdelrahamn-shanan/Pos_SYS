<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class supplierordersRequest extends FormRequest
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
         'SupplierCode'=>'required_without:id',
         'bill_type'=>'required_without:id',
         'order_date'=>'required_without:id',
         'store_id'=>'required_without:id',
         


        ];
    }

    public function messages()
    {
        return [
        'SupplierCode.required'=>'اسم  المورد',
        'bill_type.required'=>'نوع الفاتورة مطلوب',
        'order_date.required'=>'تاريخ الفاتورة مطلوب',
        'store_id.required'=>'اسم المخزن مطلوب',


        ];
    }
}