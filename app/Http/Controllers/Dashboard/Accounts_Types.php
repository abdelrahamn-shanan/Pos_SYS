<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account_Type;

class Accounts_Types extends Controller
{
    public function index(){
        $data =get_cols(new Account_Type(),array('*'),'id','ASC');
        return view ('admin.accounts_types.index',['data'=>$data]);
    }
}