<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminShift;
use App\Models\Admin;
use App\Models\Treasure;
use App\Models\Admin_treasury;




class Admin_shiftsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin_id = auth()->user()->id;
        $com_code = auth()->user()->com_code;
         $data = get_cols_where_p( new AdminShift(), array('*'), array('com_code' => $com_code), "id", "DESC", PAGINATION_COUNT);
        if(!empty($data)){
            
            foreach($data as $info){
            $info->admin_name= get_field_value(new Admin(),'name',array('id'=>$info->added_by));
            $info->treasuries_name= get_field_value(new Treasure(),'name',array('id'=>$info->treasury_id));
            }
            $checkExistsOpenShift=get_cols_where_row(new AdminShift(),array("id"),array("com_code"=>$com_code,"admin_id"=>$admin_id,"is_finished"=>0));
            }
        return view('admin.shifts.index',['data'=>$data ,'checkExistsOpenShift'=>$checkExistsOpenShift]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $com_code = auth()->user()->com_code;
        $admins_treasuries = get_cols_where_p( new Admin_treasury(), array('Admin_id','treasury_id'), array('active'=>1,'com_code' => $com_code,'Admin_id'=>auth()->user()->id), "id", "DESC", PAGINATION_COUNT);
        if(!empty($admins_treasuries)){
            foreach($admins_treasuries as $info){
            $info->admin_name= get_field_value(new Admin(),'name',array('id'=>$info->Admin_id));
            $info->treasuries_name= get_field_value(new Treasure(),'name',array('active'=>1 ,'id'=>$info->treasury_id));
            
            $check_exists_admin_shift = get_cols_where_row(new AdminShift(),array('id'),array('treasury_id'=>$info->treasury_id,'com_code' => $com_code, 'is_finished'=>0));
            
            if(!empty($check_exists_admin_shift) && $check_exists_admin_shift!=null){
             $info->available = false;
            }else{
                $info->available = true;
            }
            } 
            }            
        return view('admin.shifts.create', ['admins_treasuries'=>$admins_treasuries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
        $com_code = auth()->user()->com_code;
        $admin_id = auth()->user()->id;

        // check if current has another shift or not
        $check_exists_open_shift = get_cols_where_row(new AdminShift(),array('id'),array('admin_id'=>$admin_id,'com_code' => $com_code, 'is_finished'=>0));
        if(!empty($check_exists_open_shift) and $check_exists_open_shift!=null){
            return redirect()->back()->with(['error'=>"  الرجاء إغلاق الشفت الحالي لديك"]);
        }
        $check_exists_open_treauries = get_cols_where_row(new AdminShift(),array('id'),array('treasury_id'=>$request->treasury_id ,'com_code' => $com_code, 'is_finished'=>0));
        if(!empty($check_exists_open_treauries) and $check_exists_open_treauries!=null){
            return redirect()->back()->with(['error'=>"عفوا الخزنة المختاره بالفعل مستخدمة حاليا لدي شفت اخر"]);
        }
          // set shift code
          $row = get_cols_where_row_orderby(new AdminShift(), array("shift_code"),array('com_code'=>$com_code),'id','DESC');
          if(! $row){
              $data_insert['shift_code']= 1;
          }else{
              $data_insert['shift_code']= $row['shift_code']+1;

          }
        $data_insert['admin_id']=$admin_id;
        $data_insert['treasury_id']=$request->treasuries_id;
        $data_insert['start_date']=date("Y-m-d H:i:s");
        $data_insert['created_at']=date("Y-m-d H:i:s");
        $data_insert['added_by']=auth()->user()->id;
        $data_insert['com_code']=$com_code;
        $data_insert['date']=date("Y-m-d");
        $flag=insert(new AdminShift(),$data_insert);
        if($flag){
        return redirect()->back()->with(['success'=>'لقد تم اضافة البيانات بنجاح']);
        }else{
        return redirect()->back()->with(['error'=>'عفوا لقد حدث خطأ ما من فضلك حاول مرة اخري']);
        }
    }catch(\Exception $ex){
        return redirect()->back()
        ->with(['error'=>'عفوا حدث خطأ ما'.$ex->getMessage()])
        ->withInput();           
        }


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