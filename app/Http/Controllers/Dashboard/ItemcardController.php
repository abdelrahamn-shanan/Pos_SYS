<?php

    namespace App\Http\Controllers\Dashboard;
    use App\Http\Controllers\Controller;
    use App\Models\Uom;
    use Illuminate\Http\Request;
    use App\Models\Admin;
    use App\Models\ItemCardCategories;
    use App\Models\Itemcard;
    use App\Http\Requests\ItemcardRequest;
    use Illuminate\Support\Str;

    class ItemcardController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            $com_code = auth()->user()->com_code;
            $data = get_cols_where_p(new Itemcard(), array('*'), array('com_code' => $com_code), "id", "DESC", PAGINATION_COUNT);
            if (!empty($data)) {
                foreach ($data as $info) {
                    $info->added_by_admin = get_field_value(new Admin(), 'name', array('id' => $info->added_by));
                    if ($info->updated_by > 0 and $info->updated_by != null) {
                        $info->updated_by_admin = get_field_value(new Admin(), 'name', array('id' => $info->updated_by));
                    }

                    $info->ItemCardCategories_name = get_field_value(new ItemCardCategories(), 'name', array('id' => $info->itemcard_category)); // اسم الفئة
                    $info->parent_itemcard_name = get_field_value(new Itemcard(), 'name', array('id' => $info->parent_itemcard_id)); // اسم الصنف الاب
                    $info->parent_uom_name = get_field_value(new Uom(), 'name', array('id' => $info->uom_id)); // اسم الوحدة الاب 
                    $info->retail_uom_name = get_field_value(new Uom(), 'name', array('id' => $info->retail_uom_id)); // اسم الوحدة التجزئة 
                    $inv_itemcard_categories = get_cols_where(new ItemCardCategories(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');

                }
            }
            return view('admin.inv_itemCard.index', ['data' => $data , 'inv_itemcard_categories'=>$inv_itemcard_categories]);

        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            $com_code = auth()->user()->com_code;
            $inv_itemcard_categories = get_cols_where(new ItemCardCategories(), array('id' ,'name'), array('com_code' => $com_code , 'active'=>1), "id", "DESC");
            $inv_itemcard_Uoms_parent = get_cols_where(new Uom(), array('id' ,'name'), array('com_code' => $com_code , 'active'=>1 ,'is_master'=>1), "id", "DESC");
            $inv_itemcard_Uoms_child = get_cols_where(new Uom(), array('id' ,'name'), array('com_code' => $com_code , 'active'=>1 ,'is_master'=>0), "id", "DESC");
            $item_card_data = get_cols_where(new ItemCard(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');

            return view('admin.inv_itemCard.create', ['inv_itemcard_categories' => $inv_itemcard_categories , 'inv_itemcard_Uoms_parent'=>$inv_itemcard_Uoms_parent ,
            'inv_itemcard_Uoms_child'=>$inv_itemcard_Uoms_child, 'item_card_data' => $item_card_data]);

        }

        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function store(ItemCardRequest $request)
        {
            try {
            $com_code = auth()->user()->com_code;
            //set item code for itemcard
             $row = get_cols_where_row_orderby(new ItemCard(), array("item_code"), array("com_code" => $com_code), 'id', 'DESC');
             if (!empty($row)) {
             $data_insert['item_code'] = $row['item_code'] + 1;
             } else {
             $data_insert['item_code'] = 1;
             }
            // check for barcode
            if($request->barcode !=''){
            $check_exists = Itemcard::select()->where(['barcode'=>$request->barcode ,'com_code'=>$com_code])->first();
                if(!empty($check_exists)){
                return redirect()->back()
                    ->with(['error' => 'عفوا باركود الصنف مسجل من قبل'])
                    ->withInput();
                }else{
                    $data_insert['barcode'] = $request->barcode;
                    }
            }else{
                $data_insert['barcode'] ="item".$data_insert['item_code'];
        }

            //check for name
           $com_code = auth()->user()->com_code;
            $check_exists = Itemcard::select()->where(['name'=>$request->name ,'com_code'=>$com_code])->first();
                if(!empty($check_exists)){
                return redirect()->back()
                    ->with(['error' => 'عفوا اسم الصنف مسجل من قبل'])
                    ->withInput();
                }
                $data_insert['name'] = $request->name;
                $data_insert['item_type'] = $request->item_type;
                $data_insert['itemcard_category'] = $request->itemcard_category;
                $data_insert['uom_id'] = $request->uom_id;
                $data_insert['price'] = $request->price;
                $data_insert['nos_gomla_price'] = $request->nos_gomla_price;
                $data_insert['gomla_price'] = $request->gomla_price;
                $data_insert['cost_price'] = $request->cost_price;
                $data_insert['does_has_retailunit'] = $request->does_has_retailunit;
                $data_insert['parent_itemcard_id'] = $request->parent_itemcard_id;
                if ($data_insert['parent_itemcard_id'] =="") {
                $data_insert['parent_itemcard_id'] = null ;
                }
                if ($data_insert['does_has_retailunit'] == 1) {
                $data_insert['retail_uom_quantityToParent'] = $request->retail_uom_quantityToParent;
                $data_insert['retail_uom_id'] = $request->retail_uom_id;
                $data_insert['retail_price'] = $request->price_retail;
                $data_insert['nos_gomla_retail_price'] = $request->nos_gomla_price_retail;
                $data_insert['gomla_retail_price'] = $request->gomla_price_retail;
                $data_insert['retail_cost_price'] = $request->cost_price_retail;
                }

                if ($request->has('photo')) {
                    $request->validate([
                    'photo' => 'required|mimes:png,jpg,jpeg|max:2000',
                    ]);
                    $the_file_path = uploadImage('uploads', $request->photo);
                    $data_insert['photo'] = $the_file_path;
                    }

                    $data_insert['has_fixed_price'] = $request->has_fixed_price;
                    $data_insert['active'] = $request->active;
                    $data_insert['added_by'] = auth()->user()->id;
                    $data_insert['created_at'] = date("Y-m-d H:i:s");
                    $data_insert['date'] = date("Y-m-d");
                    $data_insert['com_code'] = $com_code;
                    ItemCard::create($data_insert);
                    return redirect()->route('ItemCard.index')->with(['success' => 'لقد تم اضافة البيانات بنجاح']);
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
            $data = get_cols_where_row(new Itemcard(),array("*") , array("id"=>$id));
            $com_code = auth()->user()->com_code;
            $data['added_by_admin'] = get_field_value(new Admin(), 'name', array('id' => $data['added_by']));
            $data['inv_itemcard_categories_name'] = get_field_value(new ItemCardCategories(), 'name', array('id' => $data['itemcard_category']));
            $data['parent_item_name'] = get_field_value(new itemcard(), 'name', array('id' => $data['parent_itemcard_id']));
            $data['Uom_name'] = get_field_value(new Uom(), 'name', array('id' => $data['uom_id']));
            if ($data['does_has_retailunit'] == 1) {
                $data['retail_uom_name'] = get_field_value(new Uom(), 'name', array('id' => $data['retail_uom_id'])); // اسم الوحدة التجزئة 
            }
                if ($data['updated_by'] > 0 and $data['updated_by']  != null) {
                $data['updated_by_admin'] = get_field_value(new Admin(), 'name', array('id' => $data['updated_by']));
                }
            return view('admin.inv_itemCard.show', ['data'=>$data]);
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
            $inv_itemcard_categories = get_cols_where(new ItemCardCategories(), array('id' ,'name'), array('com_code' => $com_code , 'active'=>1), "id", "DESC");
            $inv_itemcard_Uoms_parent = get_cols_where(new Uom(), array('id' ,'name'), array('com_code' => $com_code , 'active'=>1 ,'is_master'=>1), "id", "DESC");
            $inv_itemcard_Uoms_child = get_cols_where(new Uom(), array('id' ,'name'), array('com_code' => $com_code , 'active'=>1 ,'is_master'=>0), "id", "DESC");
            $item_card_data = get_cols_where(new ItemCard(), array('id', 'name'), array('com_code' => $com_code, 'active' => 1), 'id', 'DESC');
            $data=get_cols_where_row( new ItemCard(),array("*"),array('id'=>$id));
            return view('admin.inv_itemCard.edit', ['inv_itemcard_categories' => $inv_itemcard_categories , 'inv_itemcard_Uoms_parent'=>$inv_itemcard_Uoms_parent ,
            'inv_itemcard_Uoms_child'=>$inv_itemcard_Uoms_child, 'item_card_data' => $item_card_data ,'data'=>$data]);
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
            try{
                $com_code=auth()->user()->com_code; 
                $data=get_cols_where_row( new ItemCard(),array("*"),array('id'=>$id));
                $oldphotoPath= Str::after(getImage($data->photo), 'public/');
                if(empty($data)){
                return redirect()->route('admin.inv_itemCard.index')->with(['error'=>'عفوا غير قادر علي الوصول الي البيانات المطلوبة !!']);
                   }
                   if($request->barcode !=''){
                    $check_exists=Itemcard::where(['barcode'=>$request->barcode,'com_code'=>$com_code])->where('id','!=',$id)->first();
                    if(!empty($check_exists)){
                        return redirect()->back()
                            ->with(['error' => 'عفوا باركود الصنف مسجل من قبل'])
                            ->withInput();
                        }else{
                            $data_to_update['barcode'] = $request->barcode;
                            }
                    }
                    $check_exists=Itemcard::where(['name'=>$request->name,'com_code'=>$com_code])->where('id','!=',$id)->first();
                    if(!empty($check_exists)){
                        return redirect()->back()
                            ->with(['error' => 'عفوا اسم الصنف مسجل من قبل'])
                            ->withInput();
                        }
                        $data_to_update['name'] = $request->name;
                        $data_to_update['item_type'] = $request->item_type;
                        $data_to_update['itemcard_category'] = $request->itemcard_category;
                        $data_to_update['uom_id'] = $request->uom_id;
                        $data_to_update['price'] = $request->price;
                        $data_to_update['nos_gomla_price'] = $request->nos_gomla_price;
                        $data_to_update['gomla_price'] = $request->gomla_price;
                        $data_to_update['cost_price'] = $request->cost_price;
                        $data_to_update['does_has_retailunit'] = $request->does_has_retailunit;
                        $data_to_update['parent_itemcard_id'] = $request->parent_itemcard_id;
                        
                        if ($data_to_update['does_has_retailunit'] == 1) {
                            $data_to_update['retail_uom_quantityToParent'] = $request->retail_uom_quantityToParent;
                            $data_to_update['retail_uom_id'] = $request->retail_uom_id;
                            } else {
                                $data_to_update['retail_uom_quantityToParent'] =0;
                                $data_to_update['retail_uom_id'] = null;
                                }
                            if ($data_to_update['does_has_retailunit'] == 1) {
                            $data_to_update['retail_price'] = $request->retail_price;
                            $data_to_update['nos_gomla_retail_price'] = $request->nos_gomla_retail_price;
                            $data_to_update['gomla_retail_price'] = $request->gomla_retail_price;
                            $data_to_update['retail_cost_price'] = $request->retail_cost_price;
                            }else{
                                $data_to_update['retail_price'] = 0;
                                $data_to_update['nos_gomla_retail_price'] = 0;
                                $data_to_update['gomla_retail_price'] = 0;
                                $data_to_update['retail_cost_price'] = 0; 
                            }
        
                        if ($request->has('photo')) {
                            $request->validate([
                            'photo' => 'required|mimes:png,jpg,jpeg|max:2000',
                            ]);
                            unlink('public/'.$oldphotoPath);
                            $the_file_path = uploadImage('uploads', $request->photo);
                            $data_to_update['photo'] = $the_file_path;

                            }
        
                            $data_to_update['has_fixed_price'] = $request->has_fixed_price;
                            $data_to_update['active'] = $request->active;
                            $data_to_update['updated_by'] = auth()->user()->id;
                            $data_to_update['updated_at'] = date("Y-m-d H:i:s");

                            update(new Itemcard(), $data_to_update, array('id' => $id, 'com_code' => $com_code));
                            return redirect()->route('ItemCard.index')->with(['success' => 'لقد تم تعديل البيانات بنجاح']);

           }catch(\Exception $ex){
                return redirect()->back()
                ->with(['error'=>'عفوا حدث خطأ ما'.$ex->getMessage()])
                ->withInput();           
                }
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function delete($id){
        try {
            $com_code = auth()->user()->com_code;
            $data=get_cols_where_row( new ItemCard(),array("id"),array('id'=>$id , 'com_code'=>$com_code));
            if (!empty($data)) {
                $flag=$data->delete();
                if ($flag) {
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


        public function search(Request $request){
            if($request->ajax()){
                $search_by_text=$request->search_by_text;
                $item_type=$request->item_type;
                $itemcard_category_search=$request->itemcard_category_search; 
                $searchbyradio=$request->searchbyradio;
               
                if ($item_type == 'all') {
                    $field1 = "id";
                    $operator1 = ">";
                    $value1 = 0;
                    } else {
                    $field1 = "item_type";
                    $operator1 = "=";
                    $value1 = $item_type;
                    }

                if ($itemcard_category_search == 'all') {
                    $field2 = "id";
                    $operator2 = ">";
                    $value2 = 0;
                     } else {
                    $field2 = "itemcard_category";
                    $operator2 = "=";
                    $value2 = $itemcard_category_search;
                        }
                if ($search_by_text != '') {

                    if($searchbyradio =='barcode'){
                     $field = "barcode";
                     $operator = "=";
                     $value = $search_by_text;
                     }elseif($searchbyradio =='item_code' ){
                     $field = "item_code";
                     $operator = "=";
                     $value = $search_by_text;
                     }else{
                     $field = "name";
                     $operator = "like";
                     $value = "%{$search_by_text}%";
                     }             
                     }else{
                    $field = "id";
                    $operator = ">";
                    $value = 0;
                 }
                $data=Itemcard::where($field1, $operator1, $value1)->where($field2, $operator2, $value2)
                ->where($field, $operator, $value) ->orderBy('id','ASC')->paginate(PAGINATION_COUNT);
                if (!empty($data)) {
                foreach ($data as $info) {
                $info->added_by_admin = Admin::where('id', $info->added_by)->value('name');
                if ($info->updated_by > 0 and $info->updated_by != null) {
                $info->updated_by_admin = Admin::where('id', $info->updated_by)->value('name');
                }
                $info->ItemCardCategories_name = get_field_value(new ItemCardCategories(), 'name', array('id' => $info->itemcard_category)); // اسم الفئة
                $info->parent_itemcard_name = get_field_value(new Itemcard(), 'name', array('id' => $info->parent_itemcard_id)); // اسم الصنف الاب
                $info->parent_uom_name = get_field_value(new Uom(), 'name', array('id' => $info->uom_id)); // اسم الوحدة الاب 
                $info->retail_uom_name = get_field_value(new Uom(), 'name', array('id' => $info->retail_uom_id)); // اسم الوحدة التجزئة
                }
                }
                return view('Admin.inv_itemCard.ajax_search',['data'=>$data]);
                }
                    
        }
    }