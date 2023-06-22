<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Treasure;
use App\Models\TreasureDelivery;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Http\Requests\TreasuriesRequest;
use  App\Http\Requests\Sub_TreasureRequest;

use DB;
use  App\Http\Enumerations\TreasuriesType;
class TreasureController extends Controller
{
    public function index (){
        $data = Treasure::select()->orderby('id','desc')->paginate(PAGINATION_COUNT);
        if(!empty($data)){
            foreach($data as $info){
            $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');    
            if($info->updated_by>0 and $info->updated_by!=null){
            $info->updated_by_admin=Admin::where('id',$info->updated_by)->value('name');    
            }
            }
            }
            return view('admin.treasuries.index',['data'=>$data]);    
            }

    public function create (){
           return view('admin.treasuries.create');

       }
    public function store(TreasuriesRequest $request){
      /*  try{
            if (!$request->has('is_master')) {
                $request->request->add(['is_master' => 0]);
            } else {
                $request->request->add(['is_master' => 1]);
            }

            if (!$request->has('active')) {
                $request->request->add(['active' => 0]);
            } else {
                $request->request->add(['active' => 1]);
            }
            DB::beginTransaction();
            $treasure = Treasure::create($request->except('_token'));
            DB::commit();
            return redirect()->route('Treasureies_index')->with(['success' => 'تم تحديث البيانات بنجاح']);

        }catch(\Exception $ex){
            return $ex;
            DB::rollback();
            return redirect()->route('Treasureies_index')->with(['errors'=>'حدث خطأ ما']);

        }*/

        try{
            $com_code=auth()->user()->com_code;
            //check if not exsits
            $checkExists=Treasure::where(['name'=>$request->name,'com_code'=>$com_code])->first();
            if($checkExists==null){
            if($request->is_master==1){
            $checkExists_isMaster=Treasure::where(['is_master'=>1,'com_code'=>$com_code])->first();
            if($checkExists_isMaster!=null){
            return redirect()->back()
            ->with(['error'=>'عفوا هناك خزنة رئيسية بالفعل مسجلة من قبل لايمكن ان يكون هناك اكثر من خزنة رئيسية'])
            ->withInput(); }
            }
            $data['name']=$request->name;
            $data['is_master']=$request->is_master;
            $data['last_recieve_exchange']=$request->last_recieve_exchange;
            $data['last_recieve_collect']=$request->last_recieve_collect;
            $data['active']=$request->active;
            $data['created_at']=date("Y-m-d H:i:s");
            $data['added_by']=auth()->user()->id;
            $data['com_code']=$com_code;
            $data['date']=date("Y-m-d");
            Treasure::create($data);
            return redirect()->route('Treasureies_index')->with(['success'=>'لقد تم اضافة البيانات بنجاح']);
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
        $data=Treasure::select()->find($id);
        return view('admin.treasuries.edit',['data'=>$data]);
        
    }

    public function update(TreasuriesRequest $request , $id){
        try{
            $com_code=auth()->user()->com_code;
            $data=Treasure::select()->find($id);
           if(empty($data)){
            return redirect()->route('Treasuries.index')->with(['error'=>'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
               }
            //check if not exsits
            $checkExists=Treasure::where(['name'=>$request->name,'com_code'=>$com_code])->where('id','!=' , $id)->first();
            if($checkExists==null){
            if($request->is_master==1){
            $checkExists_isMaster=Treasure::where(['is_master'=>1,'com_code'=>$com_code])->where('id','!=' , $id)->first();
            if($checkExists_isMaster!=null){
            return redirect()->back()
            ->with(['error'=>'عفوا هناك خزنة رئيسية بالفعل مسجلة من قبل لايمكن ان يكون هناك اكثر من خزنة رئيسية'])
            ->withInput(); }
            }
            $data['name']=$request->name;
            $data['is_master']=$request->is_master;
            $data['last_recieve_exchange']=$request->last_recieve_exchange;
            $data['last_recieve_collect']=$request->last_recieve_collect;
            $data['active']=$request->active;
            $data['created_at']=date("Y-m-d H:i:s");
            $data['added_by']=auth()->user()->id;
            $data['com_code']=$com_code;
            $data['date']=date("Y-m-d");
            $data->update();
            return redirect()->route('Treasureies_index')->with(['success'=>'لقد تم اضافة البيانات بنجاح']);
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

    public function Ajax_Search_ByName(Request $request){
        if($request->ajax()){ /// if requst type of ajax
            $search_by_text=$request->search_by_text;
            $data = Treasure::where('name','LIKE',"%{$search_by_text}%" )->orderby('id','DESC')->paginate(PAGINATION_COUNT);
            return view('admin.treasuries.ajax_search',['data'=>$data]);

        }

    }

    public function Show_Details($id){
        try{
        $com_code=auth()->user()->com_code;
        $data=Treasure::select()->find($id);
        if(empty($data)){
            return redirect()->route('Treasuries.index')->with(['error'=>'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
               }
               $data->added_by_admin=Admin::where('id',$data->added_by)->value('name');    
               if($data->updated_by>0 and $data->updated_by!=null){
               $data->updated_by_admin=Admin::where('id',$data->updated_by)->value('name');    
               }
               $treasuries_delivery = TreasureDelivery::select()->where('Traesuries_id',$id)->orderby('id','desc')->get();
               if(empty($treasuries_delivery)){
                return redirect()->route('Treasuries.index')->with(['error'=>'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
                   }
                   foreach($treasuries_delivery as $info){
                    $info->name=Treasure::select()->where('id',$info->treasuries_can_delivery_id )->value('name');
                    $info->added_by_admin=Admin::where('id',$info->added_by)->value('name');    
                   }
                return view('admin.treasuries.details',['data'=>$data , 'treasuries_delivery'=>$treasuries_delivery]);
       }catch(\Exception $ex){
        return redirect()->back()
        ->with(['error'=>'عفوا حدث خطأ ما'.$ex->getMessage()])
        ->withInput(); 
       }       

    }

    public function add_sub_treasure($id){
        try{
            $com_code=auth()->user()->com_code;
            $data=Treasure::select('id','name')->find($id);
            if(empty($data)){
            return redirect()->route('Treasureies_index')->with(['error'=>'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
            }
            $Treasuries=Treasure::select('id','name')->where(['com_code'=>$com_code,'active'=>1])->get();  
            return view("admin.treasuries.SubTreasuries.create",['data'=>$data,'Treasuries'=>$Treasuries]);
            }catch(\Exception $ex){
            return redirect()->back()
            ->with(['error'=>'عفوا حدث خطأ ما'.$ex->getMessage()]);
            }
            
    }

    public function Sub_Treasure_store($id,Sub_TreasureRequest $request){
        try{
            $com_code=auth()->user()->com_code;
            $data=Treasure::select('id','name')->find($id);
            if(empty($data)){
            return redirect()->route('admin.treasuries.index')->with(['error'=>'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
            }
            $checkExists=TreasureDelivery::where(['Traesuries_id'=>$id,'treasuries_can_delivery_id'=>$request->treasuries_can_delivery_id,'com_code'=>$com_code])->first();
            if($checkExists!=null){
            return redirect()->back()
            ->with(['error'=>'عفوا هذه الخزنة مسجلة من قبل !'])
            ->withInput(); 
            }
            $data_insert_details['Traesuries_id']=$id;
            $data_insert_details['treasuries_can_delivery_id']=$request->treasuries_can_delivery_id;
            $data_insert_details['created_at']=date("Y-m-d H:i:s");
            $data_insert_details['added_by']=auth()->user()->id;
            $data_insert_details['com_code']=$com_code;
            TreasureDelivery::create($data_insert_details);
            return redirect()->route('Treasureies_Details',$id)->with(['success'=>'لقد تم اضافة البيانات بنجاح']);
            }catch(\Exception $ex){
            return redirect()->back()
            ->with(['error'=>'عفوا حدث خطأ ما'.$ex->getMessage()]);
            }
            } 
    public function delete_treasuries_delivery($id){
        try{
        $treasuries_delivery=TreasureDelivery::find($id);
        if(!empty($treasuries_delivery)){
        $flag=$treasuries_delivery->delete();
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
