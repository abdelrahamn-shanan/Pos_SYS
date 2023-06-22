<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    use HasFactory;
    protected $table='admin_panel_settings';
    protected $guarded=[];

    public function getPhotoAttribute($val) // accessors
    {
        return ($val !== null) ? asset('public/'.$val) : '';
    }
}
