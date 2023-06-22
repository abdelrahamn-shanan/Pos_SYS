<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Treasury_Transaction;
use App\Models\Supplier_with_order;
use App\Models\Supplier;
use App\Models\Treasure;
use App\Models\AdminShift;
use App\Models\Account;
use App\Models\Account_Type;
use App\Models\mov_type;
use App\Http\Enumerations\transaction_type;
use App\Http\Requests\Collect_Transaction_Request;





class CollectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $admin_id = auth()->user()->id;

        $data = get_cols_where2_p( new Treasury_Transaction(), array('*'), array('com_code' => $com_code),'money','>',0, "id", "ASC", PAGINATION_COUNT);
       if(!empty($data)){
           
           foreach($data as $info){
           $info->admin_name= get_field_value(new Admin(),'name',array('id'=>$info->added_by));
           $info->treasuries_name= get_field_value(new Treasure(),'name',array('id'=>$info->treasury_id));
           $info->mov_type_name= get_field_value(new mov_type(),'name',array('id'=>$info->mov_type));


           }
           }
        $checkExistsOpenShift=get_cols_where_row(new AdminShift(),array("id",'treasury_id','shift_code'),array("com_code"=>$com_code,"admin_id"=>$admin_id,"is_finished"=>0));
        if(!@empty($checkExistsOpenShift)){
            $checkExistsOpenShift['treasury_name']= get_field_value(new Treasure(),'name',array('id'=>$checkExistsOpenShift['treasury_id']));
            // get shift balance now
            $checkExistsOpenShift['shift_balance_now']= get_sum_where(new Treasury_Transaction() ,('money'),array('com_code'=>$com_code,
            'admins_shifts_code'=>$checkExistsOpenShift['shift_code'],'treasury_id'=>$checkExistsOpenShift['treasury_id']));
        }
         $accounts = get_cols_where(new Account(),array('name','account_number','account_type'), array('com_code'=>$com_code,'is_archieved'=>0,'active'=>1,'is_parent'=>0),'id','desc');
         if (! empty($accounts)){
            foreach($accounts as $info){
                $info->Account_type_name= get_field_value(new Account_Type(),'name',array('id'=>$info->account_type,'active'=>1));
            }
         }
         $mov_types = get_cols_where(new mov_type(),array('*'), array('active'=>1,'in_screen'=>transaction_type::collect,'is_private_internal'=>transaction_type::general_screen),'id','desc');

       return view('admin.collect_Transactions.index',['data'=>$data ,'checkExistsOpenShift'=>$checkExistsOpenShift,'accounts'=>$accounts,'mov_types'=>$mov_types]);
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
    public function store(Collect_Transaction_Request $request)
    {
        try{
            $com_code = auth()->user()->com_code;
            $checkExistsOpenShift = get_cols_where_row(new AdminShift(), array("treasury_id", "shift_code"), array("com_code" => $com_code, "admin_id" => auth()->user()->id, "is_finished" => 0, "treasury_id" => $request->treasuries_id));
            if (empty($checkExistsOpenShift)) {
            return redirect()->back()->with(['error' => "عفوا لايوجد شفت خزنة مفتوح حاليا !!"])->withInput();
            }
            //first get isal number with treasuries 
            $treasury_date = get_cols_where_row(new Treasure(), array("last_recieve_collect"), array("com_code" => $com_code, "id" => $request->treasuries_id));
            if (empty($treasury_date)) {
            return redirect()->back()->with(['error' => "  عفوا بيانات الخزنة المختارة غير موجوده !!"])->withInput();
            }

            $last_record_treasuries_transactions_record = get_cols_where_row_orderby(new Treasury_Transaction(), array("auto_serial"), array("com_code" => $com_code), "auto_serial", "DESC");
            if (!empty($last_record_treasuries_transactions_record)) {
            $dataInsert['auto_serial'] = $last_record_treasuries_transactions_record['auto_serial'] + 1;
            } else {
            $dataInsert['auto_serial'] = 1;
            }

            $dataInsert['Isal_number'] = $treasury_date['last_recieve_collect'] + 1;
            $dataInsert['treasury_id'] = $request->treasuries_id;
            $dataInsert['admins_shifts_code'] = $checkExistsOpenShift['shift_code'];
            $dataInsert['money'] = $request->money; //debit مدين
            $dataInsert['is_approved'] = transaction_type::approved;
            $dataInsert['mov_type'] = $request->mov_type;
            $dataInsert['move_date'] = $request->move_date;
            $dataInsert['account_number'] = $request->account_number;
            $dataInsert['is_account'] = transaction_type::account;
            $dataInsert['money_for_account'] = $request->money*(-1); // credit دائن
            $dataInsert['byan'] = $request->byan;
            $dataInsert['created_at'] = date("Y-m-d H:i:s");
            $dataInsert['added_by'] = auth()->user()->id;
            $dataInsert['com_code'] = $com_code;
            $flag = insert(new Treasury_Transaction(), $dataInsert);
            get_current_balance($request->account_number,new Account(),new Supplier_with_order(),new Treasury_Transaction(),new Supplier());

            if($flag){
                $dataUpdateTreasuries['last_recieve_collect'] = $dataInsert['Isal_number'];
                update(new Treasure(), $dataUpdateTreasuries, array("com_code" => $com_code, "id" => $request->treasuries_id));
                return redirect()->route('collect_transaction.index')->with(['success' => "لقد تم اضافة البيانات بنجاح "]);
            }else{
                return redirect()->back()->with(['error' => " عفوا حدث خطأ م من فضلك حاول مرة اخري !"])->withInput();
            }

        }catch(\Exception $ex){
            return redirect()->back()->with(['error'=>"عفواً حدث خطأ ما"." " .$ex->getMessage()])
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