<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Admin;
use App\Models\Account;
use App\Models\AdminSetting;
use DB;
use App\Http\Requests\CustomerRequest;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p( new Customer(), array('*'), array('com_code' => $com_code), "id", "DESC", PAGINATION_COUNT);
        if(!empty($data)){
            foreach($data as $info){
            $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');    
            if($info->updated_by>0 and $info->updated_by!=null){
            $info->updated_by_admin=Admin::where('id',$info->updated_by)->value('name');    
            }
           
            }
            }
        return view('admin.customers.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view ('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        try{
            DB::beginTransaction();
            $com_code=auth()->user()->com_code;
            //check if not exsits
            $checkExists_name = get_cols_where_row(new Customer(),array("id"),array('name'=>$request->name,'com_code'=>$com_code));
            if(!empty($checkExists_name)){ 
            return redirect()->back()
            ->with(['error'=>'عفوا اسم  العميل مسجل من قبل'])
            ->withInput(); 
            }
            // set customer code
            $row = get_cols_where_row_orderby(new Customer(), array("customer_code"),array('com_code'=>$com_code),'id','DESC');
            if(! $row){
                $data_insert['customer_code']= 1;
            }else{
                $data_insert['customer_code']= $row['customer_code']+1;

            }

            //set account number
            $row = get_cols_where_row_orderby(new Account(), array("account_number"), array("com_code" => $com_code), 'id', 'DESC');
            if (!empty($row)) {
            $data_insert['account_number'] = $row['account_number'] + 1;
            } else {
            $data_insert['account_number'] = 1;
            }

            $data_insert['name'] = $request->name;
            $data_insert['address'] = $request->address;
            $data_insert['start_balance_status'] = $request->start_balance_status;
            if ($data_insert['start_balance_status'] == 1) {
            //credit
            $data_insert['start_balance'] = $request->start_balance * (-1);
            } elseif ($data_insert['start_balance_status'] == 2) {
            //debit
            $data_insert['start_balance'] = $request->start_balance;
            if ($data_insert['start_balance'] < 0) {
            $data_insert['start_balance'] = $data_insert['start_balance'] * (-1);
            }
            } elseif ($data_insert['start_balance_status'] == 3) {
            //balanced
            $data_insert['start_balance'] = 0;
            } else {
            $data_insert['start_balance_status'] = 3;
            $data_insert['start_balance'] = 0;
            }
            $data_insert['phone'] = $request->phone;
            $data_insert['current_balance'] = $data_insert['start_balance'];
            $data_insert['notes'] = $request->notes;
            $data_insert['active'] = $request->active;
            $data_insert['added_by'] = auth()->user()->id;
            $data_insert['created_at'] = date("Y-m-d H:i:s");
            $data_insert['date'] = date("Y-m-d");
            $data_insert['updated_by'] = null;
            $data_insert['com_code'] = $com_code;
            $flag = insert(new Customer(), $data_insert);
            if($flag){
                // insert into accounts
                $data_insert_account['name'] = $request->name;
                $data_insert_account['start_balance_status'] = $request->start_balance_status;
                if ($data_insert_account['start_balance_status'] == 1) {
                //credit
                $data_insert_account['start_balance'] = $request->start_balance * (-1);
                } elseif ($data_insert_account['start_balance_status'] == 2) {
                //debit
                $data_insert_account['start_balance'] = $request->start_balance;
                if ($data_insert_account['start_balance'] < 0) {
                $data_insert_account['start_balance'] = $data_insert_account['start_balance'] * (-1);
                }
                } elseif ($data_insert_account['start_balance_status'] == 3) {
                //balanced
                $data_insert_account['start_balance'] = 0;
                } else {
                $data_insert_account['start_balance_status'] = 3;
                $data_insert_account['start_balance'] = 0;
                }
                $data_insert_account['current_balance'] = $data_insert_account['start_balance'];
                $customer_parent_account_number = get_field_value(new AdminSetting(), "customer_parent_account_number", array('com_code' => $com_code));
                $data_insert_account['notes'] = $request->notes;
                $data_insert_account['parent_account_number'] = $customer_parent_account_number;
                $data_insert_account['is_parent'] = 0;
                $data_insert_account['account_number'] = $data_insert['account_number'];
                $data_insert_account['account_type'] = 3;
                $data_insert_account['active'] = $request->active;
                $data_insert_account['added_by'] = auth()->user()->id;
                $data_insert_account['created_at'] = date("Y-m-d H:i:s");
                $data_insert_account['com_code'] = $com_code;
                $data_insert_account['other_table_FK'] = $data_insert['customer_code'];
                $flag = insert(new Account(), $data_insert_account);

            }
                DB::commit();
                return redirect()->route('Customer.index')->with(['success' => 'لقد تم اضافة البيانات بنجاح']);

            }catch(\Exception $ex){
                return $ex;
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
        $com_code=auth()->user()->id;
        $data= get_cols_where_row(new Customer(),array('*'),array('id'=>$id,'com_code'=>$com_code,'active'=>1));
        if(empty($data)){
            return redirect()->route('Customer.index')->with(['error'=>'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
        }
        return view ('admin.customers.edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, $id)
    {
        try{
            DB::beginTransaction();
            $com_code=auth()->user()->com_code;
            $data= get_cols_where_row(new Customer(),array('id','account_number','customer_code'),array('id'=>$id,'com_code'=>$com_code));
            if(empty($data)){
            return redirect()->route('Customer.index')->with(['error'=>'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
                       }
            //check if not exsits
           /* $checkExists_name = get_cols_where_row(new Customer(),array("id"),array('name'=>$request->name,'com_code'=>$com_code));
            if(!empty($checkExists_name)){ 
            return redirect()->back()
            ->with(['error'=>'عفوا اسم  العميل مسجل من قبل'])
            ->withInput(); 
            }*/
            $data_to_update['name'] = $request->name;
            $data_to_update['address'] = $request->address;
            $data_to_update['phone'] = $request->phone;
            $data_to_update['notes'] = $request->notes;
            $data_to_update['active'] = $request->active;
            $data_to_update['updated_at'] = date("Y-m-d");
            $data_to_update['updated_by'] = auth()->user()->id;
            $flag= update(new Customer(),$data_to_update,array('id'=>$id,'com_code'=>$com_code));
            // insert into accounts
            $data_update_account['name'] = $request->name;
            $data_update_account['active'] = $request->active;
            $data_update_account['updated_by'] = auth()->user()->id;
            $data_update_account['updated_at'] = date("Y-m-d H:i:s");
            update(new Account(),$data_update_account,array('account_number'=>$data['account_number'],
            'other_table_fk'=>$data['customer_code'],'com_code'=>$com_code ,'account_type'=>3));
               DB::commit(); 
                return redirect()->route('Customer.index')->with(['success' => 'لقد تم تحديث البيانات بنجاح']);

            }catch(\Exception $ex){
            return redirect()->back()
            ->with(['error'=>'عفوا حدث خطأ ما'.$ex->getMessage()])
            ->withInput();           
            }
    }

    public function delete($id){
        try{
        DB::beginTransaction();

        $com_code=auth()->user()->com_code;
        $customer=Customer::find($id);
        if(!empty($customer)){
        $flag=$customer->delete();
        if($flag){
        $data= get_cols_where_row(new Account(),array('*'),array('account_number'=>$customer['account_number'],
        'com_code'=>$com_code,
        'other_table_fk'=>$customer['customer_code'],
        'account_type'=>3));
        $data->delete();
        DB::commit();
        return redirect()->back()
        ->with(['success'=>'   تم حذف البيانات بنجاح']);
        }else{
        return redirect()->back()
        ->with(['error'=>'عفوا حدث خطأ ما']);
        }
        }else{
        return redirect()->back()
        ->with(['error'=>'عفوا غير قادر الي الوصول للبيانات المطلوبة']);
        }
        }catch(\Exception $ex){
        return redirect()->back()
        ->with(['error'=>'عفوا حدث خطأ ما'.$ex->getMessage()]);
        }
        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search (Request $request)
                {
                    if($request->ajax()){
                        $com_code=auth()->user()->com_code;
                        $search_by_text=$request->search_by_text;
                        $searchByBalanceStatus=$request->searchByBalanceStatus;
                        $searchByactiveStatus=$request->searchByactiveStatus;
                        $searchbyradio=$request->searchbyradio;
                        if ($searchByBalanceStatus == '') {
                        }

                        if ($searchByBalanceStatus == 'all') {
                            $field1 = "id";
                            $operator1 = ">";
                            $value1 = 0;
                            } else {
                            $field1 = "start_balance_status";
                            $operator1 = "=";
                            $value1 = $searchByBalanceStatus;
                            }

                        if ($searchByactiveStatus == 'all') {
                                $field2 = "id";
                                $operator2 = ">";
                                $value2 = 0;
                                } else {
                                $field2 = "active";
                                $operator2 = "=";
                                $value2 = $searchByactiveStatus;
                             }
                        if ($search_by_text != '') {
                            if ($searchbyradio == 'customer_code') {
                            $field3 = "customer_code";
                            $operator3 = "=";
                            $value3 = $search_by_text;
                             } elseif($searchbyradio == 'account_number') {
                            $field3 = "account_number";
                            $operator3 = "=";
                            $value3 = $search_by_text;
                             } else {
                            $field3 = "name";
                            $operator3 = "like";
                            $value3 = "%{$search_by_text}%";
                             }
                            } else {
                            //true 
                            $field3 = "id";
                            $operator3 = ">";
                            $value3 = 0;
                            }
                                $data = Customer::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where('com_code',$com_code)
                                ->orderBy('id', 'DESC')
                                ->paginate(PAGINATION_COUNT);
                                if(!empty($data)){
                                    foreach($data as $info){
                                    $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');    
                                    if($info->updated_by>0 and $info->updated_by!=null){
                                    $info->updated_by_admin=Admin::where('id',$info->updated_by)->value('name');    
                                    }
                                   
                                    }
                                    }
                        return view('Admin.customers.ajax_search',['data' => $data]);
                        
                    }
                }
}