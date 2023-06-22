<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Uom;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Http\Requests\UomsRequest;


class UomsController extends Controller
{
    public function index(){
        $data = Uom::select()->orderby('id','desc')->paginate(PAGINATION_COUNT);
        if(!empty($data)){
            foreach($data as $info){
            $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');    
            if($info->updated_by>0 and $info->updated_by!=null){
            $info->updated_by_admin=Admin::where('id',$info->updated_by)->value('name');    
            }
            }
            }
            return view('admin.uoms.index',['data'=>$data]);    
        }

        public function create (){
            return view('admin.uoms.create');
        
               }
       public function store(UomsRequest $request){
           try{
               $com_code=auth()->user()->com_code;
               //check if not exsits
               $checkExists=Uom::where(['name'=>$request->name,'com_code'=>$com_code])->first();
               if($checkExists==null){
               $data['name']=$request->name;
               $data['is_master']=$request->is_master;
               $data['active']=$request->active;
               $data['created_at']=date("Y-m-d H:i:s");
               $data['added_by']=auth()->user()->id;
               $data['com_code']=$com_code;
               $data['date']=date("Y-m-d");
               Uom::create($data);
               return redirect()->route('admin.uoms.index')->with(['success'=>'لقد تم اضافة البيانات بنجاح']);
               }else{
               return redirect()->back()
               ->with(['error'=>'عفوا اسم الوحدة مسجل من قبل'])
               ->withInput(); 
               }
               }catch(\Exception $ex){
               return redirect()->back()
               ->with(['error'=>'عفوا حدث خطأ ما'.$ex->getMessage()])
               ->withInput();           
               }
       }  

       public function edit($id){
        $data=Uom::select()->find($id);
        return view('admin.uoms.edit',['data'=>$data]);
        
    }

    public function update(UomsRequest $request,$id){
       // return $request;
        try{
            $com_code=auth()->user()->com_code;
            $data=Uom::select()->find($id);
           if(empty($data)){
            return redirect()->route('admin.uoms.index')->with(['error'=>'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
               }
            //check if not exsits
            $checkExists=Uom::where(['name'=>$request->name,'com_code'=>$com_code])->where('id','!=' , $id)->first();
            if($checkExists==null){
            $data['name']=$request->name;
            $data['is_master']=$request->is_master;
            $data['active']=$request->active;
            $data['created_at']=date("Y-m-d H:i:s");
            $data['added_by']=auth()->user()->id;
            $data['com_code']=$com_code;
            $data['date']=date("Y-m-d");
            $data->update();
            return redirect()->route('admin.uoms.index')->with(['success'=>'لقد تم تعديل البيانات بنجاح']);
            }else{
            return redirect()->back()
            ->with(['error'=>'عفوا اسم الوحدة مسجل من قبل'])
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
        $data=Uom::find($id);
        if(!empty($data)){
        $flag=$data->delete();
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

        public function ajax_search(Request $request){
            if($request->ajax()){
            $search_by_text=$request->search_by_text;
            $is_master_search=$request->is_master_search;
            if($search_by_text==''){
            $field1="id";
            $operator1=">";
            $value1=0;
            }else{
            $field1="name";
            $operator1="LIKE";
            $value1="%{$search_by_text}%";
            }
            if($is_master_search=="all"){
            $field2="id";
            $operator2=">";
            $value2=0;
            }else{
            $field2="is_master";
            $operator2="=";
            $value2=$is_master_search;
            }
            $data=Uom::where($field1,$operator1,$value1)->where($field2,$operator2,$value2)->orderBy('id','DESC')->paginate(2);
            if (!empty($data)) {
            foreach ($data as $info) {
            $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
            if ($info->updated_by > 0 and $info->updated_by != null) {
            $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
            }
            }
            }
            return view('admin.uoms.ajax_search',['data'=>$data]);
            }
            }
            
            
            }