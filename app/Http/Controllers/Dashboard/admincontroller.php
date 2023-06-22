<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Treasure;
use App\Models\Admin_treasury;


class admincontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_p(new Admin(), array("*"), array("com_code" => $com_code), 'id', 'DESC', PAGINATION_COUNT);
            if (!empty($data)) {
            foreach ($data as $info) {
            $info->added_by_admin = get_field_value(new Admin(),'name',array('id'=>$info->added_by));
            if ($info->updated_by > 0 and $info->updated_by != null) {
            $info->updated_by_admin = get_field_value(new Admin(),'name',array('id'=>$info->updated_by));
            }
            }
            }
                return view('admin.Admins_accounts.index', ['data' => $data]);

    }

    public function details($id)
{
            try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Admin(), array("*"), array("id" => $id, 'com_code' => $com_code));
            if (empty($data)) {
            return redirect()->route('Admin_accounts.index')->with(['error' => 'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
            }
            $data['added_by_admin'] = get_field_value(new Admin(),'name',array('id'=>$data['added_by']));
            if ($data['updated_by'] > 0 and $data['updated_by'] != null) {
            $data['updated_by_admin'] = get_field_value(new Admin(),'name',array('id'=>$data['updated_by']));
            
            }
            $treasuries = get_cols_where(new Treasure(), array("id", "name"), array("active" => 1, "com_code" => $com_code), 'id', 'ASC');
            $admins_treasuries = get_cols_where(new Admin_treasury(), array("*"), array("admin_id" => $id, 'com_code' => $com_code), 'id', 'DESC');
            if (!empty($admins_treasuries)) {
            foreach ($admins_treasuries as $info) {
            $info->name = get_field_value(new Treasure(),'name',array('id'=> $info->treasury_id));
            $info->added_by_admin = get_field_value(new Admin(),'name',array('id'=>$info->added_by));
            if ($info->updated_by > 0 and $info->updated_by != null) {
            $info->updated_by_admin = get_field_value(new Admin(),'name',array('id'=>$info->updated_by));
                }
                }
                }
            return view("admin.Admins_accounts.details", ['data' => $data ,'admins_treasuries' => $admins_treasuries, 'treasuries' => $treasuries]);
            } catch (\Exception $ex) {
            return redirect()->back()
            ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()]);
            }

}

public function Add_treasury_To_Admin(Request $request , $id){
    try{
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Admin(), array("*"), array("id" => $id, 'com_code' => $com_code));
            if (empty($data)) {
            return redirect()->route('Admin_accounts.index')->with(['error' => 'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
            }
            //check if not exists
            $admins_treasuries_exsits = get_cols_where_row(new Admin_treasury(), array("id"), array("admin_id" => $id,"treasury_id"=>$request->treasuries_id, 'com_code' => $com_code));
            if (!empty($admins_treasuries_exsits)) {
            return redirect()->route('Admin_details',$id)->with(['error' => 'عفوا هذه الخزنة بالفعل مضافة من قبل لهذا المستخدم !!!']);
            }
            $data_insert['admin_id'] = $id;
            $data_insert['treasury_id'] = $request->treasuries_id;
            $data_insert['active'] = 1;
            $data_insert['created_at'] = date("Y-m-d H:i:s");
            $data_insert['added_by'] = auth()->user()->id;
            $data_insert['com_code'] = $com_code;
            $data_insert['date'] = date("Y-m-d");
            $flag=insert(new Admin_treasury(),$data_insert);
            if($flag){
            return redirect()->route('Admin_details',$id)->with(['success' => 'لقد تم اضافة البيانات بنجاح']);
            }else{
            return redirect()->route('Admin_details',$id)->with(['error' => 'عفوا حدث خطأ ما من فضلك حاول مرة اخري !!!']);
}
} catch (\Exception $ex) {
return redirect()->back()
->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()]);
}
}
    


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
