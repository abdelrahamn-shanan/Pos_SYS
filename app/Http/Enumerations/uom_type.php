<?php

namespace App\Http\Enumerations;

use Spatie\Enum\Enum;

 Final class uom_type extends Enum
{   
const main_uom = 1; // وحدة اب
const retail_uom = 0; // وحدة تجزئة
const Active = 1;
const has_retailunit = 1;


}