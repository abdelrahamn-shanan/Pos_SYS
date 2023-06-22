<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminShift extends Model


{
    protected $table="admins_shifts";
    protected $guarded=[];
    use HasFactory;
}