<?php

namespace App\Http\Enumerations;

use Spatie\Enum\Enum;

 Final class transaction_type extends Enum
{   
const collect = 2; // تحصيل
const exchange = 1; // صرف
const dissmissal = 1; // صرف
const private_screen = 1;// شاشة داخلية
const general_screen = 0; // شاشة عامة
const approved = 1; //تم الاعتماد
const account = 1; //التحصيل من حساب مالي


}