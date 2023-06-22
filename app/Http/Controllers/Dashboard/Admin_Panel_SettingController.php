<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\AdminSetting;
use App\Models\Admin;
use App\Models\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests\AdminPanelRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class Admin_Panel_SettingController extends Controller
{
    public function index(){
        $data = AdminSetting::where('com_code', auth()->user()->com_code)->first();
        if (!empty($data)) {
        if ($data-> updated_by > 0 and $data->updated_by != null) {
         $data['updated_by_admin'] = Admin::where('id', $data['updated_by'])->value('name');
         $data['customer_parent_account_name'] = Account::where('account_number', $data['customer_parent_account_number'])->value('name');
          $data['suppliers_parent_account_name'] = Account::where('account_number', $data['suppliers_parent_account_number'])->value('name');


        }
    }
        return view('Admin.AdminPanelSettings.index', compact('data'));

    }

    public function edit($id){
        $data = AdminSetting::where('com_code',$id)->first();
        $parent_accounts = get_cols_where(new Account(), array('name','account_number') , array('is_parent'=>1,'com_code'=>auth()->user()->id) ,"id" ,"DESC");

        return view ('Admin.AdminPanelSettings.edit', ['data'=>$data,'parent_accounts'=>$parent_accounts]);
    }

    public function update(AdminPanelRequest $request){
        
            try{
                DB::beginTransaction();
                $admin_panel_setting = AdminSetting::where('com_code', auth()->user()->com_code)->first();
                $oldphotoPath= Str::after(getImage($admin_panel_setting->photo), 'public/');
                if ($request->has('photo')) {
                    unlink('public/'.$oldphotoPath);
                    $the_file_path = uploadImage('uploads', $request->photo);

                    }
                $admin_panel_setting->update([
                    'sys_name' => $request->system_name,
                    'address' => $request->address,
                    'phone' =>$request->phone,
                    'general_alert'=> $request->general_alert,
                    'customer_parent_account_number '=> $request->customer_parent_account_number,
                    'suppliers_parent_account_number '=> $request->suppliers_parent_account_number,
                     'updated_by' => auth()->user()->id,
                     'updated_at' => date("Y-m-d H:i:s"),
                     'photo'=> $the_file_path
             ]);
                
                $admin_panel_setting->save();
                DB::commit();
                return redirect()->route('settings')->with(['success' => 'تم تحديث البيانات بنجاح']);

            }catch(\Exception $ex){
               //return $ex;
                return redirect()->route('settings')->with(['errors'=>'حدث خطأ ما']);
            }
    }
}