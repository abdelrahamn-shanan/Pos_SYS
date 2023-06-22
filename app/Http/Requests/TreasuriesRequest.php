<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreasuriesRequest extends FormRequest
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
         'is_master'=>'required',
         'last_recieve_exchange'=>'required|integer|min:0',
         'last_recieve_collect'=>'required|integer|min:0',
         'active'=>'required',
        ];
    }

    public function messages()
    {
        return [
        'name.required'=>'اسم الخزنة مطلوب',
        'is_master.required'=>'نوع الخزنة مطلوب',
        'active.required'=>'حالة تفعيل الخزنة مطلوب',
        'last_recieve_exchange.required'=>'اخر رقم ايصال صرف نقدية لهذة الخزنة',
        'last_recieve_exchange.integer'=>'قيمة رقم الايصال تكون قيمة صحيحه',
        'last_recieve_collect.required'=>'اخر رقم ايصال تحصيل نقدية لهذة الخزنة',
        'last_recieve_collect.integer'=>'قيمة رقم الايصال تكون قيمة صحيحه'


        ];
    }

}

