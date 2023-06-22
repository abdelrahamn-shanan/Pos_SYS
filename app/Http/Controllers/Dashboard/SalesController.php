<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Sale;
use App\Models\SalesMaterialType;
use App\Models\Customer;
use App\Http\Enumerations\SalesInvoice; 
use App\Http\Enumerations\ItemsCard; 
use App\Http\Enumerations\item_type;
use App\Http\Enumerations\Sale_type;
use App\Http\Enumerations\uom_type; 
use App\Http\Enumerations\Store_Status; 
use App\Models\Itemcard;
use App\Models\Uom;
use App\Models\Store;
use App\Models\inv_itemcard_batch;
use App\Models\AdminShift;
use App\Models\Treasury_Transaction;
use App\Models\Treasure;




  




class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $com_code=auth()->user()->com_code;
        $data = get_cols_where_p(new Sale(),array('*'),array('com_code'=>$com_code),'id','DESC',PAGINATION_COUNT);
        if (!empty($data)) {
            foreach ($data as $info) {
                $info->added_by_admin  = get_field_value (new Admin() ,'name' , array('id'=> $info->added_by,'com_code'=>$com_code));
                $info->Sales_material_type  = get_field_value (new SalesMaterialType() ,'name' , array('id'=> $info->sales_materials_types_id,'com_code'=>$com_code));
                $info->store_name  = get_field_value (new store() ,'name' , array('id'=> $info->store_id,'com_code'=>$com_code));

                if(  $info->has_customer == SalesInvoice::hasCustomer)
                {
                    $info->Customer_name  = get_field_value (new Customer() ,'name' , array('customer_code'=> $info->customer_code,'com_code'=>$com_code));

                }else{
                    $info->Customer_name = "بدون عميل";                }
            }
        }
        $current_user_shift =  get_user_shift(new AdminShift() ,new Treasury_Transaction() , new Treasure());
        $items = get_cols_where( new Itemcard(),array('name','item_code','item_type'),array('com_code'=>$com_code,'active'=> ItemsCard::active));
        $stores = get_cols_where( new Store(),array('id','name'),array('com_code'=>$com_code,'active'=>Store_Status::Active));

        return view('admin.Sales.index', ['data' => $data,'items'=>$items,'stores'=>$stores,'current_user_shift'=>$current_user_shift]);
    }

    public function get_item_uoms(Request $request){
        if($request->Ajax()){
            $com_code=auth()->user()->com_code; 
            $item_code=$request->item_code;
            $item_card_data=get_cols_where_row(new Itemcard() , array('does_has_retailunit','retail_uom_id','uom_id') , array('item_code'=>$item_code,'active'=> ItemsCard::active,'com_code'=>$com_code));
            if(!empty( $item_card_data)){
                if( $item_card_data['does_has_retailunit']==1){
                    $item_card_data['parent_uom_name']=get_field_value(new Uom(),'name',array('id'=>$item_card_data['uom_id']));
                    $item_card_data['retail_uom_name']=get_field_value(new Uom(),'name',array('id'=>$item_card_data['retail_uom_id']));
                }else{
                    $item_card_data['parent_uom_name']=get_field_value(new Uom(),'name',array('id'=>$item_card_data['uom_id']));
                }
        }
        return view ('admin.Sales.get_item_uoms',['item_card_data'=>$item_card_data]);
    }
    }

    public function get_item_batches(Request $request){
        if($request->Ajax()){
               $com_code=auth()->user()->com_code; 
                $item_card_data = get_cols_where_row(new Itemcard() , array('item_type','uom_id','retail_uom_quantityToParent') , array('item_code'=>$request->item_code,'active'=>ItemsCard::active,'com_code'=>$com_code));
                if(!empty($item_card_data)){
                    $requested['uom_id'] = $request->uom_id;
                    $requested['store_id']= $request->store_id;
                    $requested['item_code'] = $request->item_code;
                    $parent_uom = $item_card_data['uom_id'];

                    $uom_data=get_cols_where_row(new Uom() , array('name',"is_master") , array('id'=>$requested['uom_id'] ,'active'=> uom_type::Active,'com_code'=>$com_code));
                    if($uom_data !=""){
                        if($item_card_data['item_type'] == item_type::consumption){
                            $Items_batches_qty = get_cols_where(new inv_itemcard_batch(),array('unit_cost_price','quantity','auto_serial','production_date','expire_date')
                            ,array('com_code'=>$com_code,'store_id'=>$requested['store_id'],'item_code'=> $requested['item_code'],'inv_uoms_id'=>$parent_uom),'production_date','ASC');
                        }else{
                            $Items_batches_qty = get_cols_where(new inv_itemcard_batch(),array('unit_cost_price','quantity','auto_serial')
                            ,array('com_code'=>$com_code,'store_id'=>$requested['store_id'],'item_code'=> $requested['item_code'],'inv_uoms_id'=>$parent_uom),'id','ASC');

                        }
                       

                        return view('admin.Sales.load_modal_addInvoice',['item_card_data'=>$item_card_data,'requested'=>$requested,
                        'uom_data'=>$uom_data,'Items_batches_qty'=>$Items_batches_qty]);
                    }

                    
                }
                            
           
                         
            }
        }

        public  function get_item_unit_price(Request $request)
        {
            if($request->Ajax()){
                $com_code=auth()->user()->com_code; 
                $uom_id = $request->uom_id;
                $Sale_type= $request->Sale_type;
                 $item_card_data = get_cols_where_row(new Itemcard() , array('price','nos_gomla_price','gomla_price','retail_price','nos_gomla_retail_price','gomla_retail_price','does_has_retailunit','retail_uom_id','uom_id') , array('item_code'=>$request->item_code,'active'=>ItemsCard::active,'com_code'=>$com_code));
                 if(!empty($item_card_data)){
                     $uom_data=get_cols_where_row(new Uom() , array("is_master") , array('id'=>$uom_id,'active'=> uom_type::Active,'com_code'=>$com_code));
                     if(!empty($uom_data)){
                        if($uom_data['is_master']==uom_type::main_uom){
                            if($item_card_data['uom_id']==$uom_id){
                            if($Sale_type == Sale_type::قطاعي ){
                                echo json_encode($item_card_data['price']);
                            }elseif($Sale_type == Sale_type::نص_جمله ){
                                echo json_encode($item_card_data['nos_gomla_price']);
                            }else{
                                echo json_encode($item_card_data['gomla_price']);
                            }
                        }
                        }else{
                            if($item_card_data['retail_uom_id']==$uom_id and $item_card_data['does_has_retailunit'] == uom_type::has_retailunit ){
                            if($Sale_type == Sale_type::قطاعي ){
                                echo json_encode($item_card_data['retail_price']);
                            } elseif($Sale_type == Sale_type::نص_جمله ){
                                echo json_encode($item_card_data['nos_gomla_retail_price']);
                            }else{
                                echo json_encode($item_card_data['gomla_retail_price']);
                            }
                           }
                        }
                    }  
                 }
                             
            
                          
             }

        }
        
        public function add_new_item_row(Request $request)
        {
            $com_code = auth()->user()->com_code;
        if ($request->ajax()) {
            $received_data['store_id'] = $request->store_id;
            $received_data['Sale_type'] = $request->Sale_type;
            $received_data['item_code'] = $request->item_code;
            $received_data['uom_id'] = $request->uom_id;
            $received_data['inv_itemcard_batches_autoserial'] = $request->inv_itemcard_batches_autoserial;
            $received_data['item_qty'] = $request->item_qty;
            $received_data['item_price'] = $request->item_price;
            $received_data['is_bonus_or_normal'] = $request->is_bonus_or_normal;
            $received_data['item_total_price'] = $request->item_total_price;
            $received_data['store_name'] = $request->store_name;
            $received_data['uom_id_name'] = $request->uom_id_name;
            $received_data['item_code_name'] = $request->item_code_name;
            $received_data['sales_item_type_name'] = $request->sales_item_type_name;
            $received_data['is_normal_orOther_name'] = $request->is_normal_orOther_name;
            $received_data['isparentuom'] = $request->isparentuom;
            return view('admin.Sales.Add_new_item_row', ['received_data' => $received_data]);
}
           
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
    public function store(Request $request)
    {
        //
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