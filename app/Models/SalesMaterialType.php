<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesMaterialType extends Model
{
    use HasFactory;
    protected $table='sales__materials__types';
    protected $guarded=[];
    protected $fillable=[];
}
