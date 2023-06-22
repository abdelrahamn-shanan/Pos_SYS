<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreasureDelivery extends Model
{
    use HasFactory;
    protected $table='treasuries__delivery';
    protected $guarded=[];
    protected $fillable=[];
}
