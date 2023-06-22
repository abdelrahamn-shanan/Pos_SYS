<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treasury_Transaction extends Model
{
    protected $table='treasuries_transactions' ;
    protected $guarded=[];
    use HasFactory;
}