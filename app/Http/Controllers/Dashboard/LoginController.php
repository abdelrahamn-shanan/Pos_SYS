<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Requests\AdminRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class LoginController extends Controller
{
    public function login(){
        return view ('admin.auth.login');
    }

    public function postlogin(AdminRequest $request){
        if(auth()->guard('admin')->attempt(['name'=> $request->input('username') , 'password'=> $request->input('password')]))
        {
           // notify()->success('تم الدخول بنجاح');
            return redirect()-> route('admin.dashboard')->with(['success' =>'تم الدخول بنجاح']);
        }
         // notify()->error('خطا في البيانات  برجاء المجاولة مجدا ');
        return redirect()->back()->with(['error' =>'هناك خطأ يرجى المحاولة لاحقاً']);
    }
}