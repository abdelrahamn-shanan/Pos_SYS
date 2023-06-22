<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Admin;
use App\Models\Account_Type;
use App\Models\Customer;
use App\Http\Requests\AccountRequest;

class AccountController extends Controller
{
    public function index(){
        $com_code = auth()->user()->com_code;
        $data = get_cols_where_p(new Account(), array('*'), array('com_code' => $com_code), "id", "DESC", PAGINATION_COUNT);
        if(!empty($data)){
            foreach($data as $info){
            $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');    
            if($info->updated_by>0 and $info->updated_by!=null){
            $info->updated_by_admin=Admin::where('id',$info->updated_by)->value('name');    
            }
            $info->account_types_name = Account_Type::where('id',$info->account_type)->value('name'); 
            if ($info->is_parent == 0 ){
                $info->parent_account_name = Account::where('account_number',$info->parent_account_number)->value('name');
            }else{
                $info->parent_account_name = " لا يوجد";
            }
            }
            }
            $account_type = get_cols_where(new Account_type(), array('id','name'),array('active'=>1,'relatedinternalaccounts'=>0),"id" ,"DESC");
        return view('admin.accounts.index',['data'=>$data,'account_type'=>$account_type]);
    }


    public function create(){
        $com_code=auth()->user()->id;
        $account_type = get_cols_where(new Account_type(), array('id','name'),array('active'=>1,'relatedinternalaccounts'=>0),"id" ,"DESC");
        $parent_accounts = get_cols_where(new Account(), array('name','account_number') , array('is_parent'=>1,'com_code'=>$com_code) ,"id" ,"DESC");
        return view ('admin.accounts.create',['accounts_types'=>$account_type , 'parent_accounts'=>$parent_accounts]);
    }

    public function store(AccountRequest $request){
        try{
            $com_code=auth()->user()->com_code;
            //check if not exsits
            $checkExists_name = get_cols_where_row(new Account(),array("id"),array('name'=>$request->name,'com_code'=>$com_code));
            if(!empty($checkExists_name)){ 
            return redirect()->back()
            ->with(['error'=>'عفوا اسم الحساب المالي مسجل من قبل'])
            ->withInput(); 
            }

            // set account_number
            $row = get_cols_where_row_orderby(new Account(), array("account_number"),array('com_code'=>$com_code),'id','DESC');
            if(! $row){
                $data_insert['account_number']= 1;
            }else{
                $data_insert['account_number']= $row['account_number']+1;

            }

                $data_insert['name'] = $request->name;
                $data_insert['account_type'] = $request->account_type;
                $data_insert['is_parent'] = $request->is_parent;
                if($data_insert['is_parent']==0){
                    $data_insert['parent_account_number'] = $request->parent_account_number;
                    }
                $data_insert['start_balance_status'] = $request->start_balance_status;
                if ($data_insert['start_balance_status'] <= 1) {
                    $data_insert['start_balance'] = $request->start_balance*(-1);
                }
                elseif ($data_insert['start_balance_status'] == 2) {
                    $data_insert['start_balance'] = $request->start_balance;
                    }
                elseif ($data_insert['start_balance_status'] == 3) {
                    $data_insert['start_balance'] = 0 ;
                    }
                else{
                    $data_insert['start_balance_status'] = 3;
                    $data_insert['start_balance'] = 0 ;
                }
                $data_insert['current_balance'] = $data_insert['start_balance'];
                $data_insert['notes'] = $request->notes;
                $data_insert['active'] = $request->active;
                $data_insert['added_by'] = auth()->user()->id;
                $data_insert['created_at'] = date("Y-m-d H:i:s");
                $data_insert['com_code'] = $com_code;
                Account::create($data_insert);
                return redirect()->route('admin.accounts.index')->with(['success' => 'لقد تم اضافة البيانات بنجاح']);

            }catch(\Exception $ex){
            return redirect()->back()
            ->with(['error'=>'عفوا حدث خطأ ما'.$ex->getMessage()])
            ->withInput();           
            }
        }

             public function edit($id)
            {
                $com_code=auth()->user()->id;
                $data= get_cols_where_row(new Account(),array('*'),array('id'=>$id,'com_code'=>$com_code,'active'=>1));
                $account_type = get_cols_where(new Account_type(), array('id','name'),array('active'=>1,'relatedinternalaccounts'=>0),"id" ,"DESC");
                $parent_accounts = get_cols_where(new Account(), array('name','account_number') , array('is_parent'=>1,'com_code'=>$com_code) ,"id" ,"DESC");
                return view ('admin.accounts.edit',['data'=>$data,'accounts_types'=>$account_type , 'parent_accounts'=>$parent_accounts]);
            }

            public function update($id,AccountRequest $request)
            {
                try{
                    $com_code=auth()->user()->com_code;
                    $data= get_cols_where_row(new Account(),array('id','account_number','other_table_fk','account_type'),array('id'=>$id,'com_code'=>$com_code,'active'=>1));
                    if(empty($data)){
                    return redirect()->route('admin.accounts.index')->with(['error'=>'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
                       }
                    //check if not exsits
                        $data_update['name'] = $request->name;
                        $data_update['account_type'] = $request->account_type;
                        $data_update['is_parent'] = $request->is_parent;
                        if($data_update['is_parent'] == 0 ){
                            $data_update['parent_account_number'] = $request-> parent_account_number;
                            }else{
                                $data_update['parent_account_number']=null;
                            }

                    $data_update['active']=$request->active;
                    $data_update['created_at']=date("Y-m-d H:i:s");
                    $data_update['added_by']=auth()->user()->id;
                    $data_update['com_code']=$com_code;
                     update(new Account(),$data_update,array('id'=>$id,'com_code'=>$com_code));
                    
                        if($data['account_type']==3){
                            $data_update_customer['name'] = $request->name;
                            $data_update_customer['active'] = $request->active;
                            $data_update_customer['updated_by'] = auth()->user()->id;
                            $data_update_customer['updated_at'] = date("Y-m-d H:i:s");
                             update(new Customer(),$data_update_customer,array('account_number'=>$data['account_number'],'com_code'=>$com_code,
                             'customer_code'=>$data['other_table_fk']));
                        }
                    

                    return redirect()->route('admin.accounts.index')->with(['success'=>'لقد تم اضافة البيانات بنجاح']);
                    }catch(\Exception $ex){
                        return $ex;
                    return redirect()->back()
                    ->with(['error'=>'عفوا حدث خطأ ما'.$ex->getMessage()])
                    ->withInput();           
                    }
        
            }

            public function delete($id){
                try{
                $account=Account::find($id);
                if(!empty($account)){
                $flag=$account->delete();
                if($flag){
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

                public function show($id)
                {
                    $com_code=auth()->user()->id;
                    $data= get_cols_where_row(new Account(),array('*'),array('id'=>$id,'com_code'=>$com_code,'active'=>1));
                    $account_type = get_cols_where(new Account_type(), array('id','name'),array('active'=>1,'relatedinternalaccounts'=>0),"id" ,"DESC");
                    $parent_accounts = get_cols_where(new Account(), array('name','account_number') , array('is_parent'=>1,'com_code'=>$com_code) ,"id" ,"DESC");
                    return view ('admin.accounts.show',['data'=>$data,'accounts_types'=>$account_type , 'parent_accounts'=>$parent_accounts]);           
                }

                public function ajax_search (Request $request)
                {
                    if($request->ajax()){
                        $account_type=$request->account_type;
                        $is_parent=$request->is_parent;
                        $search_by_text=$request->search_by_text;
                        $searchbyradio=$request->searchbyradio;
                        $active_search=$request->active_search;

                        if ($is_parent == 'all') {
                            $field1 = "id";
                            $operator1 = ">";
                            $value1 = 0;
                            } else {
                            $field1 = "is_parent";
                            $operator1 = "=";
                            $value1 = $is_parent;
                            }
                            if ($account_type == 'all') {
                            $field2 = "id";
                            $operator2 = ">";
                            $value2 = 0;
                            } else {
                            $field2 = "account_type";
                            $operator2 = "=";
                            $value2 = $account_type;
                            }
                        if ($search_by_text != '') {
                            if ($searchbyradio == 'account_number') {
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
                            if ($active_search == 'all') {
                                $field4 = "id";
                                $operator4 = ">";
                                $value4 = 0;
                                } else {
                                $field4 = "active";
                                $operator4 = "=";
                                $value4 = $active_search;
                                }
                                $data = Account::where($field1, $operator1, $value1)
                                ->where($field2, $operator2, $value2)
                                ->where($field3, $operator3, $value3)
                                ->where($field4, $operator4, $value4)
                                ->orderBy('id', 'DESC')
                                ->paginate(PAGINATION_COUNT);
                                if (!empty($data)) {
                                    foreach($data as $info){
                                        $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');    
                                        if($info->updated_by>0 and $info->updated_by!=null){
                                        $info->updated_by_admin=Admin::where('id',$info->updated_by)->value('name');    
                                        }
                                        $info->account_types_name = Account_Type::where('id',$info->account_type)->value('name'); 
                                        if ($info->is_parent == 0 ){
                                            $info->parent_account_name = Account::where('account_number',$info->parent_account_number)->value('name');
                                        }else{
                                            $info->parent_account_name = " لا يوجد";
                                        }
                                        }
                        return view('Admin.accounts.ajax_search',['data' => $data]);
                        }
                        
                }
            }

}

        
