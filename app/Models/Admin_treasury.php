<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin_treasury extends Model
{
    use HasFactory;
    protected $table = 'admin_treasuries';
    protected $guarded=[];
}
