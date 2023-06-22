<?php
namespace App\Http\Controllers\Dashboard;
use App\Models\Store;
use App\Models\Admin;
use Illuminate\Http\Request;
use  App\Http\Requests\StoreRequest;
use App\Http\Controllers\Controller;


class StoreController extends Controller
{
    public function index (){
        $data = Store::select()->orderby('id','desc')->paginate(PAGINATION_COUNT);
        if(!empty($data)){
            foreach($data as $info){
            $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');    
            if($info->updated_by>0 and $info->updated_by!=null){
            $info->updated_by_admin=Admin::where('id',$info->updated_by)->value('name');    
            }
            }
            }
            return view('admin.Stores.index',['data'=>$data]);    
            }


    public function create (){
         return view('admin.stores.create');
     
            }
    public function store(StoreRequest $request){
        try{
            $com_code=auth()->user()->com_code;
            //check if not exsits
            $checkExists=Store::where(['name'=>$request->name,'com_code'=>$com_code])->first();
            if($checkExists==null){
            $data['name']=$request->name;
            $data['phones']=$request->phone;
            $data['address']=$request->address;
            $data['active']=$request->active;
            $data['created_at']=date("Y-m-d H:i:s");
            $data['added_by']=auth()->user()->id;
            $data['com_code']=$com_code;
            $data['date']=date("Y-m-d");
            Store::create($data);
            return redirect()->route('admin.stores.index')->with(['success'=>'لقد تم اضافة البيانات بنجاح']);
            }else{
            return redirect()->back()
            ->with(['error'=>'عفوا اسم الخزنة مسجل من قبل'])
            ->withInput(); 
            }
            }catch(\Exception $ex){
            return redirect()->back()
            ->with(['error'=>'عفوا حدث خطأ ما'.$ex->getMessage()])
            ->withInput();           
            }
            

    
    
    }  
    
    public function edit($id){
        $data=Store::select()->find($id);
        return view('admin.Stores.edit',['data'=>$data]);
        
    }

    public function update(StoreRequest $request,$id){
       // return $request;
        try{
            $com_code=auth()->user()->com_code;
            $data=Store::select()->find($id);
           if(empty($data)){
            return redirect()->route('admin.stores.index')->with(['error'=>'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
               }
            //check if not exsits
            $checkExists=Store::where(['name'=>$request->name,'com_code'=>$com_code])->where('id','!=' , $id)->first();
            if($checkExists==null){
            $data['name']=$request->name;
            $data['phones']=$request->phone;
            $data['address']=$request->address;
            $data['active']=$request->active;
            $data['created_at']=date("Y-m-d H:i:s");
            $data['added_by']=auth()->user()->id;
            $data['com_code']=$com_code;
            $data['date']=date("Y-m-d");
            $data->update();
            return redirect()->route('admin.stores.index')->with(['success'=>'لقد تم اضافة البيانات بنجاح']);
            }else{
            return redirect()->back()
            ->with(['error'=>'عفوا اسم المخزن مسجل من قبل'])
            ->withInput(); 
            }
            }catch(\Exception $ex){
            return redirect()->back()
            ->with(['error'=>'عفوا حدث خطأ ما'.$ex->getMessage()])
            ->withInput();           
            }

    }

    public function delete($id){
        try{
        $Store=Store::find($id);
        if(!empty($Store)){
        $flag=$Store->delete();
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

}