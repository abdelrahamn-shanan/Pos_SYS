<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Admin;
use App\Models\SupplierCategory;
use App\Http\Requests\SupplierRequest;
use App\Models\AdminSetting;
use App\Models\Account;
use DB;


class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p( new Supplier(), array('*'), array('com_code' => $com_code), "id", "DESC", PAGINATION_COUNT);
        if(!empty($data)){
            foreach($data as $info){
            $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');    
            if($info->updated_by>0 and $info->updated_by!=null){
            $info->updated_by_admin=Admin::where('id',$info->updated_by)->value('name');    
            }
            $info->suppliers_categories_name = get_field_value(new SupplierCategory(),'name',array('active'=>1 , 'com_code'=>$com_code ,'id'=>$info['Supplier_Category_id']));
            }
            }
        return view('admin.suppliers.index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $com_code = auth()->user()->com_code;
        $data =  get_cols_where(new SupplierCategory(), array('id','name'),array('active'=>1 ,'com_code' => $com_code),"id" ,"DESC");
        return view('admin.suppliers.create',['data'=>$data]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierRequest $request)
    {
        {
            try {
                DB::beginTransaction();
            $com_code = auth()->user()->com_code;
            //check if not exsits for name
            $checkExists_name = get_cols_where_row(new Supplier(), array("id"), array('name' => $request->name, 'com_code' => $com_code));
            if (!empty($checkExists_name)) {
            return redirect()->back()
            ->with(['error' => 'عفوا اسم المورد مسجل من قبل'])
            ->withInput();
            }
            //set customer code
            $row = get_cols_where_row_orderby(new Supplier(), array("Supplier_code"), array("com_code" => $com_code), 'id', 'DESC');
            if (!empty($row)) {
            $data_insert['Supplier_code'] = $row['Supplier_code'] + 1;
            } else {
            $data_insert['Supplier_code'] = 1;
            }
            //set account number
            $row = get_cols_where_row_orderby(new Account(), array("account_number"), array("com_code" => $com_code), 'id', 'DESC');
            if (!empty($row)) {
            $data_insert['account_number'] = $row['account_number'] + 1;
            } else {
            $data_insert['account_number'] = 1;
            }
            $data_insert['name'] = $request->name;
            $data_insert['phones'] = $request->phones;
            $data_insert['Supplier_Category_id'] = $request->Supplier_Category_id ;
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
            $data_insert['current_balance'] = $data_insert['start_balance'];
            $data_insert['notes'] = $request->notes;
            $data_insert['active'] = $request->active;
            $data_insert['added_by'] = auth()->user()->id;
            $data_insert['created_at'] = date("Y-m-d H:i:s");
            $data_insert['updated_by'] = null;
            $data_insert['updated_at'] = null;
            $data_insert['date'] = date("Y-m-d");
            $data_insert['com_code'] = $com_code;
            $flag = insert(new Supplier(), $data_insert);
            if ($flag) {
            //insert into accounts  بتفح سجل ليه بالشجرة المحاسبية
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
            $suppliers_parent_account_number = get_field_value(new AdminSetting(), "suppliers_parent_account_number", array('com_code' => $com_code));
            $data_insert_account['notes'] = $request->notes;
            $data_insert_account['parent_account_number'] = $suppliers_parent_account_number;
            $data_insert_account['is_parent'] = 0;
            $data_insert_account['account_number'] = $data_insert['account_number'];
            $data_insert_account['account_type'] = 2;
            $data_insert_account['active'] = $request->active;
            $data_insert_account['added_by'] = auth()->user()->id;
            $data_insert_account['updated_by'] = null;
            $data_insert_account['updated_at'] = null;
            $data_insert_account['created_at'] = date("Y-m-d H:i:s");
            $data_insert_account['com_code'] = $com_code;
            $data_insert_account['other_table_FK'] = $data_insert['Supplier_code'];
            $flag = insert(new Account(), $data_insert_account);
            };
            DB::commit();
            return redirect()->route('Suppliers.index')->with(['success' => 'لقد تم اضافة البيانات بنجاح']);
            } catch (\Exception $ex) {
                return $ex;
            return redirect()->back()
            ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
            ->withInput();
            }
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
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_row(new Supplier(), array("*"), array("id" => $id, "com_code" => $com_code));
        $suppliers_categories = get_cols_where(new SupplierCategory(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
        return view('admin.suppliers.edit', ['data' => $data, 'suppliers_categories' => $suppliers_categories]);
        }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Supplier(), array("id", "account_number", "Supplier_code"), array("id" => $id, "com_code" => $com_code));
            if (empty($data)) {
            return redirect()->route('Suppliers.index')->with(['error' => 'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
            }
            $checkExists = Supplier::where(['name' => $request->name, 'com_code' => $com_code])->where('id', '!=', $id)->first();
            if ($checkExists != null) {
            return redirect()->back()
            ->with(['error' => 'عفوا اسم الحساب مسجل من قبل'])
            ->withInput();
            }
            $data_to_update['name'] = $request->name;
            $data_to_update['phones'] = $request->phones;
            $data_to_update['address'] = $request->address;
            $data_to_update['notes'] = $request->notes;
            $data_to_update['active'] = $request->active;
            $data_to_update['updated_by'] = auth()->user()->id;
            $data_to_update['updated_at'] = date("Y-m-d H:i:s");
            $flag = update(new Supplier(), $data_to_update, array('id' => $id, 'com_code' => $com_code));
            
            $data_to_update_account['name'] = $request->name;
            $data_to_update_account['active'] = $request->active;
            $data_to_update_account['updated_by'] = auth()->user()->id;
            $data_to_update_account['updated_at'] = date("Y-m-d H:i:s");
            $flag = update(new Account(), $data_to_update_account, array('account_number' => $data['account_number'], 'other_table_FK' => $data['Supplier_code'], 'com_code' => $com_code, 'account_type' => 2));
            
            DB::COMMIT();
            return redirect()->route('Suppliers.index')->with(['success' => 'لقد تم تحديث البيانات بنجاح']);
            } catch (\Exception $ex) {
            return redirect()->back()
            ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
            ->withInput();
            }
            
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
    try{
        DB::beginTransaction();

        $com_code=auth()->user()->com_code;
        $supplier=Supplier::find($id);
        if(!empty($supplier)){
        $flag=$supplier->delete();
        if($flag){
        $data= get_cols_where_row(new Account(),array('*'),array('account_number'=>$supplier['account_number'],
        'com_code'=>$com_code,
        'other_table_fk'=>$supplier['Supplier_code'],
        'account_type'=>2));
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

        public function search (Request $request)
        {
            if($request->ajax()){
                $com_code=auth()->user()->com_code;
                $search_by_text=$request->search_by_text;
                $searchByBalanceStatus=$request->searchByBalanceStatus;
                $searchByactiveStatus=$request->searchByactiveStatus;
                $searchbyradio=$request->searchbyradio;
               

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
                    if ($searchbyradio == 'Supplier_code') {
                    $field3 = "Supplier_code";
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
                        $data = Supplier::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)->where($field3, $operator3, $value3)->where('com_code',$com_code)
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
                return view('Admin.suppliers.ajax_search',['data' => $data]);
                
            }
        }
}