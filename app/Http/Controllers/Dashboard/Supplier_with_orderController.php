<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Supplier_with_order;
use App\Models\suppliers_orders_detatils;
use App\Models\ItemCard;
use App\Models\Uom;
use App\Models\Supplier;
use App\Models\inv_itemcard_batch;
use App\Models\inv_itemcard_movement;
use App\Models\store;
use App\Models\Treasure;
use App\Models\Account;
use App\Models\AdminShift;
use App\Models\Treasury_Transaction;
use App\Http\Requests\supplierordersRequest;
use App\Http\Requests\bill_approverequest;
use App\Http\Enumerations\transaction_type;
use App\Http\Enumerations\mov_type;
use App\Http\Enumerations\order_type;
use App\Http\Enumerations\uom_type;
use App\Http\Enumerations\item_type;
use App\Http\Enumerations\ItemsCard;   
use App\Http\Enumerations\item_archived;   
use App\Http\Enumerations\itemcard_movements;
use DB;

class Supplier_with_orderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            $com_code=auth()->user()->com_code;
            $data = get_cols_where_p(new Supplier_with_order(),array('*'),array('com_code'=>$com_code),'id','DESC',PAGINATION_COUNT);
            if (!empty($data)) {
                foreach ($data as $info) {
                    $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
                    $info->supplier_name = get_field_value (new Supplier() ,'name' , array('Supplier_code'=> $info->Supplier_code));
                    $info->store_name  = get_field_value (new store() ,'name' , array('id'=> $info->store_id));

                    if ($info->updated_by > 0 and $info->updated_by != null) {
                        $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
                    }

                }
            }
            $suppliers = get_cols_where(new Supplier(), array('Supplier_code', 'name'), array('com_code' => $com_code), 'id', 'DESC');
            $stores = get_cols_where(new Store(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            return view('admin.Supplier_with_order.index', ['data' => $data,'suppliers'=>$suppliers,'stores'=>$stores]);
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            $com_code = auth()->user()->com_code;
            $suppliers = get_cols_where(new Supplier(), array('Supplier_code', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            $stores = get_cols_where(new Store(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            return view('admin.Supplier_with_order.create', ['suppliers' => $suppliers, 'stores' => $stores]);
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function store(supplierordersRequest $request)
        {
            try {
                $com_code = auth()->user()->com_code;
                $supplierdata= get_cols_where_row(new Supplier(),array('account_number'),array('Supplier_code'=>$request->SupplierCode ,'com_code'=>$com_code));
                if(empty( $supplierdata))
                {
                    return redirect()->back()->with(['error' => 'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);

                }

                $row = get_cols_where_row_orderby(new Supplier_with_order(), array("auto_serial"), array("com_code" => $com_code), 'id', 'DESC');
                if (!empty($row)) {
                $data_insert['auto_serial'] = $row['auto_serial'] + 1;
                } else {
                $data_insert['auto_serial'] = 1;
                }
                $data_insert['order_date'] = $request->order_date;
                $data_insert['order_type'] = 1;
                $data_insert['Doc_no'] = $request->Doc_no;
                $data_insert['store_id'] = $request->store_id;
                $data_insert['Supplier_code'] = $request->SupplierCode;
                $data_insert['bill_type'] = $request->bill_type;
                $data_insert['account_number'] = $supplierdata['account_number'];
                $data_insert['added_by'] = auth()->user()->id;
                $data_insert['created_at'] = date("Y-m-d H:i:s");
                $data_insert['date'] = date("Y-m-d");
                $data_insert['com_code'] = $com_code;
                Supplier_with_order::create($data_insert);
                        return redirect()->route('Supplier_with_orders.index')->with(['success' => 'لقد تم اضافة البيانات بنجاح']);
                    }catch(\Exception $ex){
                        return redirect()->back()
                        ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
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
                try{
                $com_code=auth()->user()->com_code;
                $data= get_cols_where_row(new Supplier_with_order(),array('*'),array('id'=>$id ,'com_code'=>$com_code,'order_type'=>1));
                if(empty($data))
                {
                    return redirect()->back()->with(['error' => 'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);

                }
                    $data->added_by_admin=get_field_value(new Admin(),'name',array('id'=>$data['added_by'],'com_code'=>$com_code)); 
                    $data->Supplier_name=get_field_value(new Supplier(),'name',array('Supplier_code'=>$data['Supplier_code'],'com_code'=>$com_code));
                    $data->store_name  = get_field_value (new Store() ,'name' , array('id'=> $data['store_id']));


                    if($data->updated_by>0 and $data->updated_by!=null){
                    $data->updated_by_admin=Admin::where('id',$data->updated_by)->value('name');    
                    }
                    // bill details

                    $details= get_cols_where(new suppliers_orders_detatils(),array('*'),array('Supplier_order_auto_serial'=>$data['auto_serial'],'order_type'=>1,'com_code'=>$com_code),'id','DESC');
                    if(empty($details)){
                        return redirect()->route('Supplier_with_orders.index')->with(['error'=>'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
                        }
                        foreach($details as $info){
                            $info->ItemCard_name=get_field_value(new ItemCard(),'name',array('item_code'=>$info->item_code,'com_code'=>$com_code));
                            $info->uom_name=get_field_value(new Uom(),'name',array('id'=>$info->uom_id,'com_code'=>$com_code));
                            $info->added_by_admin=get_field_value(new Admin(),'name',array('id'=>$info->added_by,'com_code'=>$com_code));
                            if($info->updated_by>0 and $info->updated_by!=null){
                                $info->updated_by_admin=get_field_value(new Admin(),'name',array('id'=>$info->updated_by,'com_code'=>$com_code));
                            }   
                        }
                        return view('admin.Supplier_with_order.details',['data'=>$data ,'details'=>$details]);
            }catch(\Exception $ex){
                return redirect()->back()
                ->with(['error'=>'عفوا حدث خطأ ما'.$ex->getMessage()])
                ->withInput(); 
            }       
        }

        public function delete_bill($id)
    {
                try {
                $com_code = auth()->user()->com_code;
                $parent_bill_data = get_cols_where_row(new Supplier_with_order(), array("is_approved", "auto_serial"), array("id" => $id, "com_code" => $com_code, 'order_type' => 1));
                if (empty($parent_bill_data)) {
                return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما']);
                }
                if ($parent_bill_data['is_approved'] == 1) {
                if (empty($parent_bill_data)) {
                return redirect()->back()
                ->with(['error' => 'عفوا  لايمكن الحذف بتفاصيل فاتورة معتمده ومؤرشفة']);
                }
                }
                delete(new Supplier_with_order(), array("id" => $id, "com_code" => $com_code, 'order_type' => 1));
            
                        return redirect()->back()
                            ->with(['success' => 'تم حذف البيانات بنجاح']);
                
            
                } catch (\Exception $ex) {
                return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()]);
                }
                }

        public function get_item_uoms(Request $request){
            if($request->Ajax()){
            $com_code=auth()->user()->com_code; 
                $item_code=$request->item_code;
                $item_card_data=get_cols_where_row(new ItemCard() , array('does_has_retailunit','retail_uom_id','uom_id') , array('item_code'=>$item_code,'active'=>1));
                if(!empty( $item_card_data)){
                    if( $item_card_data['does_has_retailunit']==1){
                        $item_card_data['parent_uom_name']=get_field_value(new Uom(),'name',array('id'=>$item_card_data['uom_id']));
                        $item_card_data['retail_uom_name']=get_field_value(new Uom(),'name',array('id'=>$item_card_data['retail_uom_id']));
                    }else{
                        $item_card_data['parent_uom_name']=get_field_value(new Uom(),'name',array('id'=>$item_card_data['uom_id']));
                    }
            }
            return view ('admin.Supplier_with_order.get_item_uoms',['item_card_data'=>$item_card_data]);
        }
        }

        public function add_new_details(Request $request){
            if($request->Ajax()){
            $com_code=auth()->user()->com_code; 
                $item_code=$request->item_code;
                $suppliers_with_orders_data=get_cols_where_row(new Supplier_with_order() , array('is_approved','order_date','tax_value','discount_value') , array('auto_serial'=>$request->parentautoserial,'com_code'=>$com_code,'order_type'=>1));
                if(!empty($suppliers_with_orders_data)){
                    if($suppliers_with_orders_data['is_approved']==0){
                        $data_insert['Supplier_order_auto_serial'] = $request->parentautoserial;
                        $data_insert['order_type'] = 1;
                        $data_insert['item_code'] = $request->item_code_add;
                        $data_insert['delivered_quantity'] = $request->quantity_add;
                        $data_insert['unit_price'] = $request->price_add;
                        $data_insert['uom_id'] = $request->uom_id_Add;
                        $data_insert['uom_type'] = $request->isparentuom;
                        $data_insert['order_date'] =  $suppliers_with_orders_data['order_date'];
                        $data_insert['item_card_type'] = $request->type;
                        if($request->type==2){
                        $data_insert['production_date'] = $request->production_date;
                        $data_insert['expire_date'] = $request->expire_date;
                        }
                        
                        $data_insert['total_price'] = $request->total_add;
                        $data_insert['added_by'] = auth()->user()->id;
                        $data_insert['created_at'] = date("Y-m-d H:i:s");
                        $data_insert['com_code'] = $com_code;
                    $flag = insert(new suppliers_orders_detatils(),$data_insert);
                    if($flag){
                        $total_items_sum = get_sum_where(new suppliers_orders_detatils(),'total_price',array('Supplier_order_auto_serial'=> $request->parentautoserial
                        ,'order_type'=>1,'com_code'=>$com_code));
                        $totalUpdateParent['total_cost_items']= $total_items_sum;
                        $totalUpdateParent['total_before_discount'] =  $total_items_sum + $suppliers_with_orders_data['tax_value'];
                        $totalUpdateParent['total_cost']=  $totalUpdateParent['total_before_discount'] -$suppliers_with_orders_data['discount_value'];
                        $totalUpdateParent['updated_by'] = auth()->user()->id;
                        $totalUpdateParent['updated_at'] = date("Y-m-d H:i:s");
                        update(new Supplier_with_order(),$totalUpdateParent,
                        array('auto_serial'=>$request->parentautoserial,'com_code'=>$com_code,'order_type'=>1));
                        echo json_encode("doneee");
                    }

                    }
                    
            }
        }
        }

        public function reload_items (Request $request)
        {
        if ($request->ajax()) {
        $com_code = auth()->user()->com_code;
        $auto_serial = $request->autoserailparent;
        $data = get_cols_where_row(new Supplier_with_order(), array("is_approved","id"), array("auto_serial" => $auto_serial, "com_code" => $com_code, 'order_type' => 1));
        if (!empty($data)) {
        $details = get_cols_where(new suppliers_orders_detatils(), array("*"), array('Supplier_order_auto_serial' => $auto_serial, 'order_type' => 1, 'com_code' => $com_code), 'id', 'DESC');
        if (!empty($details)) {
        foreach ($details as $info) {
        $info->item_card_name = ItemCard::where('item_code', $info->item_code)->value('name');
        $info->uom_name = get_field_value(new Uom(), "name", array("id" => $info->uom_id));
        $data['added_by_admin'] = Admin::where('id', $data['added_by'])->value('name');
        if ($data['updated_by'] > 0 and $data['updated_by'] != null) {
        $data['updated_by_admin'] = Admin::where('id', $data['updated_by'])->value('name');
        }
        }
        }
        }
        return view("Admin.Supplier_with_order.reload", ['data' => $data, 'details' => $details]);
        }
        }
        public function reload_parent_bill(Request $request){
            if($request->Ajax()){
                $com_code=auth()->user()->com_code;
                $data= get_cols_where_row(new Supplier_with_order(),array('*'),array('auto_serial'=>$request->autoserailparent,'com_code'=>$com_code,'order_type'=>1));
                if(!empty($data))
                {
                    $data->added_by_admin=get_field_value(new Admin(),'name',array('id'=>$data['added_by'],'com_code'=>$com_code)); 
                    $data->Supplier_name=get_field_value(new Supplier(),'name',array('Supplier_code'=>$data['Supplier_code'],'com_code'=>$com_code));

                    if($data->updated_by>0 and $data->updated_by!=null){
                    $data->updated_by_admin=Admin::where('id',$data->updated_by)->value('name'); 
                }
                return view("Admin.Supplier_with_order.reload_parent_bill", ['data' => $data]);

        

        }
        }
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
            $data = get_cols_where_row(new Supplier_with_order(),array('*'), array("id"=>$id,"com_code" => $com_code,"order_type"=>1));
            if(empty($data))
            {
                return redirect()->back()->with(['error' => 'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);

            }
            if($data['is_approved']==1){
                return redirect()->route('Supplier_with_orders.index')->with(['error' => 'عفوا لايمكنك تحديث فاتورة معتمدة ومؤرشفة']);

            }
            $suppliers = get_cols_where(new Supplier(), array('Supplier_code', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            $stores = get_cols_where(new Store(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            return view('admin.Supplier_with_order.edit', ['data'=>$data,'suppliers' => $suppliers, 'stores' => $stores]);
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function update(supplierordersRequest $request,$id)
        {
            try {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_row(new Supplier_with_order(), array("is_approved"), array("id" => $id, "com_code" => $com_code, 'order_type' => 1));
            if (empty($data)) {
            return redirect()->route('Supplier_with_orders.index')->with(['error' => 'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
            }
            $supplierData = get_cols_where_row(new Supplier(), array("account_number"), array("Supplier_code" => $request->Supplier_code, "com_code" => $com_code));
            if (empty($supplierData)) {
            return redirect()->back()
            ->with(['error' => 'عفوا   غير قادر علي الوصول الي بيانات المورد المحدد'])
            ->withInput();
            }
            $data_to_update['order_date'] = $request->order_date;
            $data_to_update['order_type'] = 1;
            $data_to_update['Doc_no'] = $request->Doc_no;
            $data_to_update['Supplier_code'] = $request->Supplier_code;
            $data_to_update['bill_type'] = $request->bill_type;
            $data_to_update['store_id'] = $request->store_id;
            $data_to_update['account_number'] = $supplierData['account_number'];
            $data_to_update['updated_by'] = auth()->user()->id;
            $data_to_update['updated_at'] = date("Y-m-d H:i:s");
            update(new Supplier_with_order(), $data_to_update, array("id" => $id, "com_code" => $com_code, 'order_type' => 1));
            return redirect()->route('Supplier_with_orders.show', $id)->with(['success' => 'لقد تم تحديث البيانات بنجاح']);
            } catch (\Exception $ex) {
                return $ex;
            return redirect()->back()
            ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
            ->withInput();
            }
        }
        public  function load_modal_add_details(Request $request)
        {
            if ($request->Ajax()) {
                $com_code = auth()->user()->com_code;
                $parent_bill_data = get_cols_where_row(new Supplier_with_order(), array("is_approved")
                , array("auto_serial" => $request->autoserailparent, "com_code" => $com_code, 'order_type' => 1));
                if (!empty($parent_bill_data)) {
                if ($parent_bill_data['is_approved'] == 0) {
                $itemcards = get_cols_where(new ItemCard(), array("name", "item_code", "item_type"), array('active' => 1, 'com_code' => $com_code), 'id', 'DESC');
                return view("admin.Supplier_with_order.load_add_items_details", ['parent_bill_data' => $parent_bill_data,
                'itemcards' => $itemcards]);
                }
                }
                }  
            }

    public function update_bill_items(Request $request){
        if($request->Ajax()){
            $com_code = auth()->user()->com_code;
            $auto_serial = $request->autoserailparent;
            $parent_bill_data = get_cols_where_row(new Supplier_with_order(), array("is_approved"), 
            array("auto_serial" => $request->autoserailparent, "com_code" => $com_code, 'order_type' => 1));
            if (!empty($parent_bill_data)) {
                if ($parent_bill_data['is_approved'] == 0) { 
                $item_data_details = get_cols_where_row(new suppliers_orders_detatils(), array("*"), array("Supplier_order_auto_serial" => $request->autoserailparent,
                "com_code" => $com_code, 'order_type' => 1, 'id' => $request->id));

                $item_cards = get_cols_where(new ItemCard(), array("name", "item_code", "item_type"), array('active' => 1, 'com_code' => $com_code), 'id', 'DESC');

                $item_card_Data = get_cols_where_row(new ItemCard(), array("does_has_retailunit", "retail_uom_id", "uom_id"),
                array("item_code" => $item_data_details['item_code'], "com_code" => $com_code));
                if (!empty($item_card_Data)) {
                if ($item_card_Data['does_has_retailunit'] == 1) {
                $item_card_Data['parent_uom_name'] = get_field_value(new Uom(), "name", array("id" => $item_card_Data['uom_id']));
                $item_card_Data['retial_uom_name'] = get_field_value(new Uom(), "name", array("id" => $item_card_Data['retail_uom_id']));
                } else {
                $item_card_Data['parent_uom_name'] = get_field_value(new Uom(), "name", array("id" => $item_card_Data['uom_id']));
                }
                }
                return view("admin.Supplier_with_order.load_edit_item_details", ['parent_bill_data' => $parent_bill_data, 
                'item_data_details' => $item_data_details,
                'item_cards' => $item_cards, 'item_card_Data' => $item_card_Data]);
                }
                }
                }
                }

                public function edit_item_details(Request $request)
                {

                    if($request->Ajax()){
                        $com_code = auth()->user()->com_code;
                        $auto_serial = $request->parentautoserial;
                        $parent_bill_data = get_cols_where_row(new Supplier_with_order(), array("is_approved","order_date","tax_value","discount_value"), 
                        array("auto_serial" => $request->parentautoserial, "com_code" => $com_code, 'order_type' => 1));
                        if (!empty($parent_bill_data)) {

                            if ($parent_bill_data['is_approved'] == 0) { 
                                $data_to_update['Supplier_order_auto_serial'] =  $auto_serial;
                                $data_to_update['order_type'] = 1;
                                $data_to_update['item_code'] = $request->item_code_add;
                                $data_to_update['delivered_quantity'] = $request->quantity_add;
                                $data_to_update['unit_price'] = $request->price_add;
                                $data_to_update['uom_id'] = $request->uom_id_Add;
                                $data_to_update['uom_type'] = $request->isparentuom;
                                $data_to_update['order_date'] =  $parent_bill_data['order_date'];
                                $data_to_update['item_card_type'] = $request->type;
                                if($request->type==2){
                                $data_to_update['production_date'] = $request->production_date;
                                $data_to_update['expire_date'] = $request->expire_date;
                                }
                                
                                $data_to_update['total_price'] = $request->total_add;
                                $data_to_update['updated_by'] = auth()->user()->id;
                                $data_to_update['updated_at'] = date("Y-m-d H:i:s");
                                $data_to_update['com_code'] = $com_code;
                                update(new suppliers_orders_detatils(),$data_to_update,array("id" => $request->id, 'com_code' => $com_code, 'order_type' => 1, 'Supplier_order_auto_serial' => $request->parentautoserial));
                            
                                $total_items_sum = get_sum_where(new suppliers_orders_detatils(),'total_price',array('Supplier_order_auto_serial'=> $request->parentautoserial
                                ,'order_type'=>1,'com_code'=>$com_code));
                                $totalUpdateParent['total_cost_items']= $total_items_sum;
                                $totalUpdateParent['total_before_discount'] =  $total_items_sum + $parent_bill_data['tax_value'];
                                $totalUpdateParent['total_cost']=  $totalUpdateParent['total_before_discount'] -$parent_bill_data['discount_value'];
                                $totalUpdateParent['updated_by'] = auth()->user()->id;
                                $totalUpdateParent['updated_at'] = date("Y-m-d H:i:s");
                                update(new Supplier_with_order(),$totalUpdateParent,
                                array('auto_serial'=>$request->parentautoserial,'com_code'=>$com_code,'order_type'=>1));
                                echo json_encode("done");
                            
                            
            
                            }
                            
                    }
                }
                }

        public function delete_details($id,$id_parent)
        {
            try {
            $com_code = auth()->user()->com_code;
            $parent_bill_data = get_cols_where_row(new Supplier_with_order(), array("is_approved", "auto_serial"), array("id" => $id_parent, "com_code" => $com_code, 'order_type' => 1));
            if (empty($parent_bill_data)) {
            return redirect()->back()
            ->with(['error' => ' غير قادر على الوصول للبيانات المطلوبة']);
            }
            if ($parent_bill_data['is_approved'] == 1) {
            if (empty($parent_bill_data)) {
            return redirect()->back()
            ->with(['error' => 'عفوا  لايمكن الحذف بتفاصيل فاتورة معتمده ومؤرشفة']);
            }
            }
            $item_row = suppliers_orders_detatils::find($id);
            if (!empty($item_row)) {
            $flag = $item_row->delete();
            if ($flag) {
            /** update parent pill */
            $total_detials_sum = get_sum_where(new suppliers_orders_detatils(), 'total_price', array("Supplier_order_auto_serial" => $parent_bill_data['auto_serial'], 'order_type' => 1, 'com_code' => $com_code));
            $dataUpdateParent['total_cost_items'] = $total_detials_sum;
            $dataUpdateParent['total_before_discount'] = $total_detials_sum + $parent_bill_data['tax_value'];
            $dataUpdateParent['total_cost'] = $dataUpdateParent['total_before_discount'] - $parent_bill_data['discount_value'];
            $dataUpdateParent['updated_by'] = auth()->user()->id;
            $dataUpdateParent['updated_at'] = date("Y-m-d H:i:s");
            update(new Supplier_with_order(), $dataUpdateParent, array("id" => $id_parent, "com_code" => $com_code, 'order_type' => 1));
            return redirect()->back()
            ->with(['success' => '   تم حذف البيانات بنجاح']);
            } else {
            return redirect()->back()
            ->with(['error' => 'عفوا حدث خطأ ما']);
            }
            } else {
            return redirect()->back()
            ->with(['error' => 'عفوا غير قادر الي الوصول للبيانات المطلوبة']);
            }
            } catch (\Exception $ex) {
            return redirect()->back()
            ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()]);
    }
    }

    public function load_modal_approve_invoice(Request $request){
        if($request->Ajax()){
            $com_code=auth()->user()->com_code;
            $data= get_cols_where_row(new Supplier_with_order(),array('*'),array('auto_serial'=>$request->autoserailparent,'com_code'=>$com_code,'order_type'=>1));
            // get current user shift
        $current_user_shift =  get_user_shift(new AdminShift() ,new Treasury_Transaction() , new Treasure());
        
            return view("Admin.Supplier_with_order.load_modal_approve_invoice", ['data' => $data,'current_user_shift'=>$current_user_shift]);
    }
    }

    public function load_userShift(Request $request)
    {
        if($request->Ajax()){
            $com_code=auth()->user()->com_code;
            // get current user shift
        $current_user_shift =  get_user_shift(new AdminShift() ,new Treasury_Transaction() , new Treasure());
        
            return view("Admin.Supplier_with_order.load_modal_userShift", ['current_user_shift'=>$current_user_shift]); 
    }
    }

    public function do_approve($auto_serial,bill_approverequest $request)
    {
        try{
        $com_code=auth()->user()->com_code;

        $data=get_cols_where_row(new Supplier_with_order() , array('store_id','com_code','auto_serial','id','is_approved','total_cost_items','account_number','Supplier_code') , array('auto_serial'=>$auto_serial,'com_code'=>$com_code,'order_type'=>1));
        if(empty($data)){
            return redirect()->route('Supplier_with_orders.index')->with(['error'=>"عفوا غير قادر على الوصول الى البيانات المطلوبة !!"]);
        }
         $supplierName= get_field_value(new Supplier(),'name',array('active'=>1,'com_code'=>$com_code,'Supplier_code'=> $data['Supplier_code']));


        if($data['is_approved']==1){
            return redirect()->route('Supplier_with_orders.show',$data['id'])->with(['error'=>"عفوا لا يمكن اعتماد فاتورة معتمدة من قبل  !!"]);
        }

        $dataUpdatedParent['tax_percent']=$request['tax_percent'];
        $dataUpdatedParent['tax_value']=$request['tax_value'];
        $dataUpdatedParent['total_before_discount']=$request['total_befor_discount'];
        $dataUpdatedParent['discount_type']=$request['discount_type'];
        $dataUpdatedParent['discount_percent']=$request['discount_percent'];
        $dataUpdatedParent['discount_value']=$request['discount_value'];
        $dataUpdatedParent['total_cost']=$request['total_cost'];
        $dataUpdatedParent['bill_type']=$request['bill_type'];
        $dataUpdatedParent['money_for_account']=$request['total_cost']*(-1);
        $dataUpdatedParent['what_paid']=$request['what_paid'];
        $dataUpdatedParent['what_remain']=$request['what_remain'];
        $dataUpdatedParent['is_approved']=  transaction_type::approved;
        $dataUpdatedParent['updated_by'] = auth()->user()->id;
        $dataUpdatedParent['updated_at'] = date("Y-m-d H:i:s");
        $dataUpdatedParent['date'] = date("Y-m-d");
        $dataUpdatedParent['com_code'] = $com_code;
        $dataUpdatedParent['approved_by']= auth()->user()->com_code;



        $current_user_shift =  get_user_shift(new AdminShift() ,new Treasury_Transaction() , new Treasure());
        if (empty( $current_user_shift)){
            return redirect()->route('Supplier_with_orders.show',$data['id'])->with(['error'=>"  عفواً المستخدم الحالي لا يمتلك خزنة متاحة للصرف او التحصيل  !!"]);
        }
         update(new Supplier_with_order(),$dataUpdatedParent,array('auto_serial'=>$auto_serial,'com_code'=>$com_code,'order_type'=>1));
         
         get_current_balance($data['account_number'],new Account(),new Supplier_with_order(),new Treasury_Transaction(),new Supplier());
         // treasuries transaction تسجيل حركة المشتريات
         if($request['what_paid']>0){
            $treasury_data = get_cols_where_row(new Treasure(), array("last_recieve_exchange"), array("com_code" => $com_code, "id" => $request->treasuries_id));
            if (empty($treasury_data)) {
             return redirect()->route('Supplier_with_orders.show',$data['id'])->with(['error'=>"  عفوا بيانات الخزنة المختارة غير موجوده  !!"]);
            }

            $last_record_treasuries_transactions_record = get_cols_where_row_orderby(new Treasury_Transaction(), array("auto_serial"), array("com_code" => $com_code), "auto_serial", "DESC");
            if (!empty($last_record_treasuries_transactions_record)) {
            $dataInsert['auto_serial'] = $last_record_treasuries_transactions_record['auto_serial'] + 1;
            } else {
            $dataInsert['auto_serial'] = 1;
            }

            $dataInsert['Isal_number'] = $treasury_data['last_recieve_exchange'] + 1;
            $dataInsert['treasury_id'] = $request->treasuries_id;
            $dataInsert['admins_shifts_code'] = $current_user_shift['shift_code'];
            $dataInsert['money'] = $request['what_paid'] * (-1); //credit دائن
            $dataInsert['is_approved'] =transaction_type::approved;
            $dataInsert['fk'] =  $data['auto_serial'];
            $dataInsert['mov_type'] = mov_type::supplier_purchases_bill;
            $dataInsert['move_date'] = date("Y-m-d H:i:s");;
            $dataInsert['account_number'] = $data['account_number'];
            $dataInsert['is_account'] = transaction_type::account;
            $dataInsert['money_for_account'] =$request['what_paid']*(1); // debit مدين
            $dataInsert['byan'] =" صرف نظير مشتريات فاتورة مورد رقم". $data['auto_serial'];
            $dataInsert['created_at'] = date("Y-m-d H:i:s");
            $dataInsert['added_by'] = auth()->user()->id;
            $dataInsert['com_code'] = $com_code;
            $flag = insert(new Treasury_Transaction(), $dataInsert);
            if($flag){
                $dataUpdateTreasuries['last_recieve_exchange'] = $dataInsert['Isal_number'];
                update(new Treasure(), $dataUpdateTreasuries, array("com_code" => $com_code, "id" => $request->treasuries_id));
                return redirect()->route('Supplier_with_orders.show',$data['id'])->with(['success' => "لقد تم اعتماد الفاتورة  بنجاح "]);
            }else{
                return redirect()->back()->route('Supplier_with_orders.show',$data['id'])->with(['error' => " عفوا حدث خطأ م من فضلك حاول مرة اخري !"])->withInput();
            }
         }


         // register items in stores
        $bill_items =  get_cols_where (new suppliers_orders_detatils() ,array('*'),array('com_code'=>$data['com_code'],'Supplier_order_auto_serial'=>$data['auto_serial'],'order_type'=>order_type::purchases),'id','ASC');
        if(!empty($bill_items)){
            foreach($bill_items as $info){
              $bill_items_data= get_cols_where_row(new Itemcard(),array('does_has_retailunit','uom_id','retail_uom_id','retail_uom_quantityToParent'),array('com_code'=>$data['com_code'],'item_code'=>$info->item_code));
                if(!empty( $bill_items_data)){
                    $quantity_before_move = get_sum_where(new inv_itemcard_batch(),'quantity',array('com_code'=>$data['com_code'],'item_code'=>$info->item_code));
                    $quantity_before_move_current_store = get_sum_where(new inv_itemcard_batch(),'quantity',array('com_code'=>$data['com_code'],'item_code'=>$info->item_code,'store_id'=>$data['store_id']));

                    $MainUomName= get_field_value(new Uom(),'name',array('com_code'=>$com_code,'active'=>1,'id'=> $bill_items_data['uom_id']));

                    if($info->uom_type==uom_type::main_uom){ // اذا كان الصنف وحدة اب
                        $quantity = $info->delivered_quantity;
                        $unit_price = $info-> unit_price; 
                    }else{ // اذا كان الصنف وحدة تجزئة
                        $quantity =($info->delivered_quantity / $bill_items_data['retail_uom_quantityToParent']);
                        $unit_price =( $info->unit_price * $bill_items_data['retail_uom_quantityToParent']);

                    }
                

                    if($info->item_card_type==item_type::consumption){

                        $oldbatchexists = get_cols_where_row(new inv_itemcard_batch(),array('*'),
                        array('production_date'=>$info->production_date,'expire_date'=>$info->expire_date,
                        'store_id'=>$data['store_id'],'item_code'=>$info->item_code,
                        'unit_cost_price'=>$unit_price,'inv_uoms_id'=>$bill_items_data['uom_id']));
                    }else{
                        
                        $oldbatchexists = get_cols_where_row(new inv_itemcard_batch(),array('*'),array('store_id'=>$data['store_id'],
                        'item_code'=>$info->item_code,'unit_cost_price'=>$unit_price,'inv_uoms_id'=>$bill_items_data['uom_id']));

                    }

                
                    if(!empty($oldbatchexists)){
                        // update old batch
                        $dataupdatedoldbatch['quantity'] = $oldbatchexists['quantity'] + $quantity;
                        $dataupdatedoldbatch['total_cost_price'] = $oldbatchexists['unit_cost_price'] * $dataupdatedoldbatch['quantity'];
                        $dataupdatedoldbatch['created_at'] = date("Y-m-d H:i:s");
                        $dataupdatedoldbatch['added_by'] = auth()->user()->id;
                        $dataupdatedoldbatch['com_code']=$com_code;

                        update(new inv_itemcard_batch(),$dataupdatedoldbatch,array('id'=>$oldbatchexists['id']));
                    }else{
                        // insert new batch
                        $datainsertbatch['store_id']=$data['store_id'];
                        $datainsertbatch['item_code']=$info->item_code;
                        $datainsertbatch['unit_cost_price']=$unit_price;
                        $datainsertbatch['inv_uoms_id']=$bill_items_data['uom_id'];
                        $datainsertbatch['production_date']=$info->production_date;
                        $datainsertbatch['expire_date']=$info->expire_date;
                        $datainsertbatch['quantity']=$quantity;
                        $datainsertbatch['total_cost_price']=$info->total_price;
                        $datainsertbatch['com_code']=$com_code;
                        $datainsertbatch['created_at'] = date("Y-m-d H:i:s");
                        $datainsertbatch['added_by'] = auth()->user()->id;
                        $row = get_cols_where_row_orderby(new inv_itemcard_batch(), array("auto_serial"), array("com_code" => $com_code), 'id', 'DESC');
                        if (!empty($row)) {
                        $datainsertbatch['auto_serial'] = $row['auto_serial'] + 1;
                        } else {
                        $datainsertbatch['auto_serial'] = 1;
                        }
                        insert(new inv_itemcard_batch(),$datainsertbatch);
                    }

                     // تسجيل الحركة في كارت الصنف
                        $quantity_after_move = get_sum_where(new inv_itemcard_batch(),'quantity',array('com_code'=>$data['com_code'],'item_code'=>$info->item_code));
                        $quantity_after_move_current_store = get_sum_where(new inv_itemcard_batch(),'quantity',array('com_code'=>$data['com_code'],'item_code'=>$info->item_code,'store_id'=>$data['store_id']));

                        $datainsert_inv_itemcard_movements['inv_itemcard_movements_categories']= itemcard_movements::purchases_category;
                        $datainsert_inv_itemcard_movements['item_code']= $info->item_code ;
                        $datainsert_inv_itemcard_movements['store_id']=$data['store_id'] ;
                        $datainsert_inv_itemcard_movements['items_movements_types']=itemcard_movements::purchases_move_type ;
                        $datainsert_inv_itemcard_movements['FK_table']=$data['auto_serial'] ;
                        $datainsert_inv_itemcard_movements['FK_table_details']=$info->id;
                        $datainsert_inv_itemcard_movements['byan']="نظير مشتريات من"." " .$supplierName." " ."فاتورة رقم : "." ".$auto_serial;
                        $datainsert_inv_itemcard_movements['quantity_before_movement']= " عدد" . " " . $quantity_before_move ." " . $MainUomName;
                        $datainsert_inv_itemcard_movements['quantity_after_movement']=  " عدد" . " " . $quantity_after_move ." " . $MainUomName;
                        $datainsert_inv_itemcard_movements['quantity_before_movement_store']=  " عدد" . " " . $quantity_before_move_current_store ." " . $MainUomName;
                        $datainsert_inv_itemcard_movements['quantity_after_movement_store']=  " عدد" . " " . $quantity_after_move_current_store ." " . $MainUomName;
                        $datainsert_inv_itemcard_movements['added_by']=auth()->user()->id;
                        $datainsert_inv_itemcard_movements['date']=date("Y-m-d");
                        $datainsert_inv_itemcard_movements['created_at']=date("Y-m-d H:i:s");
                        $datainsert_inv_itemcard_movements['com_code']=$com_code;
                        
                        insert(new inv_itemcard_movement(),$datainsert_inv_itemcard_movements);
                
                }else{
                    return redirect()->route('Supplier_with_orders.show',$data['id'])->with(['error' => "  الصنف غير موجود!"])->withInput();
                }

                // update items mirror
                refresh_cost_price(new ItemCard(),$info->item_code,$info->uom_type, $info->unit_price , $bill_items_data['does_has_retailunit'],
                $bill_items_data['retail_uom_quantityToParent']);
                // update items quantity
                 refresh_item_qty(new inv_itemcard_batch(),"quantity",$bill_items_data['does_has_retailunit'], $bill_items_data['retail_uom_quantityToParent'],new ItemCard(),$info->item_code);
            }
        }else{
            return redirect()->route('Supplier_with_orders.show',$data['id'])->with(['error' => "   غير قادر على الوصول للبيانات المطلوبة!"])->withInput();
        }
        return redirect()->route('Supplier_with_orders.show',$data['id'])->with(['success' => " تم الاعتماد والترحيل بنجاح !"]);
        }catch(\Exception $ex){
            return $ex;
        }
    }

    public function search(Request $request){
        if($request->ajax()){
            $search_by_text=$request->search_by_text;
            $store_id=$request->store_id;
            $searchbyradio=$request->searchbyradio;
            $supplier_code=$request->supplier_code;
            $order_date_from = $request->order_date_from;
            $order_date_to = $request->order_date_to;


            if ($supplier_code == 'all') {
                $field1 = "id";
                $operator1 = ">";
                $value1 = 0;
                } else {
                $field1 = "Supplier_code";
                $operator1 = "=";
                $value1 = $supplier_code;
                }


                if ($store_id == 'all') {
                    $field2 = "id";
                    $operator2 = ">";
                    $value2 = 0;
                    } else {
                    $field2 = "store_id";
                    $operator2 = "=";
                    $value2 = $store_id;
                    }

            if ($search_by_text != '') {
                if($searchbyradio =='auto_serial'){
                 $field = "auto_serial";
                 $operator = "=";
                 $value = $search_by_text;
                 }else {
                 $field = "DOC_NO";
                 $operator = "=";
                 $value = $search_by_text;
                 }             
                }else{
                $field = "id";
                $operator = ">";
                $value = 0;
             }

             if ($order_date_from == '') {
                $field3 = "id";
                $operator3 = ">";
                $value3 = 0;
                } else {
                $field3 = "order_date";
                $operator3 = ">=";
                $value3 = $order_date_from;
                }
                if ($order_date_to == '') {
                $field4 = "id";
                $operator4 = ">";
                $value4 = 0;
                } else {
                $field4 = "order_date";
                $operator4 = "<=";
                $value4 = $order_date_to;
                }

             $data=Supplier_with_order::where($field,$operator,$value)
                   ->where($field1,$operator1,$value1)
                   ->where($field2,$operator2,$value2)
                   ->where($field3,$operator3,$value3)
                   ->where($field4,$operator4,$value4)
                   ->where('order_type',order_type::purchases)
                   ->orderBy('id','ASC')->paginate(PAGINATION_COUNT);
             if (!empty($data)) {
                foreach ($data as $info) {
                    $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
                    $info->supplier_name = get_field_value (new Supplier() ,'name' , array('Supplier_code'=> $info->Supplier_code));
                    $info->store_name  = get_field_value (new store() ,'name' , array('id'=> $info->store_id));

                    if ($info->updated_by > 0 and $info->updated_by != null) {
                        $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
                    }

                }
            }
             return view('Admin.Supplier_with_order.ajax_search',['data'=>$data]);
        }


    }
}