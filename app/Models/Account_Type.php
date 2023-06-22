<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account_Type extends Model
{
    use HasFactory;

    protected $table = "accounts_types";
    protected $guarded=[];
}