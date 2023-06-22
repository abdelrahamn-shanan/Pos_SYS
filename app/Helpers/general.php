<?php

use App\Http\Enumerations\account_type;  
use App\Http\Enumerations\transaction_type;
use App\Http\Enumerations\uom_type;
use App\Http\Enumerations\ItemsCard;



define('PAGINATION_COUNT', 2);
function getFolder()
{
    return app()->getlocale() === 'ar' ? 'css-rtl' : 'css';
}

function uploadImage($folder, $image)
{
    $image->store('/', $folder);
    $filename = $image->hashName();
    $path = 'public/admin/' . $folder . '/' . $filename;

    return $path;
}

function getImage($image)
{
    $img = Str::after($image, public_path());

    return $imagpath = base_path($img);
}


/*get some cols by pagination  table */
function get_cols_where_p($model = null, $columns_names = array(), $where = array(), $order_field = "id", $order_type = "DESC", $pagination_counter)
{
    $data = $model::select($columns_names)->where($where)->orderby($order_field, $order_type)->paginate($pagination_counter);
    return $data;
}


function get_cols_where2_p($model = null, $columns_names = array(), $where = array(),$where2field,$where2opertor,$where2value, $order_field = "id", $order_type = "DESC", $pagination_counter)
{
    $data = $model::select($columns_names)->where($where)->where($where2field,$where2opertor,$where2value)->
    orderby($order_field, $order_type)->paginate($pagination_counter);
    return $data;
}


/*get some cols  table */
function get_cols_where($model = null, $columns_names = array(), $where = array(), $order_field = "id", $order_type = "DESC")
{
    $data = $model::select($columns_names)->where($where)->orderby($order_field, $order_type)->get();
    return $data;
}
/*get some cols row table order by */
function get_cols_where_row_orderby($model, $columns_names = array(), $where = array(), $order_field="id",$order_type="DESC")
{
$data = $model::select($columns_names)->where($where)->orderby($order_field, $order_type)->first();
return $data;
}

function get_cols($model, $columns_names = array(), $order_field,$order_type)
{
$data = $model::select($columns_names)->orderby($order_field, $order_type)->get();
return $data;
}

function get_cols_where_row($model, $columns_names = array(),$where = array())
{
$data = $model::select($columns_names)->where($where)->first();
return $data;
}



function get_cols_where2_row($model, $columns_names = array(),$where = array(), $where2 = array())
{
$data = $model::select($columns_names)->where($where)->where($where2)->first();
return $data;
}

function get_field_value($model, $field_name, $where = array())
{
    $data = $model::where($where)->value($field_name);
    return $data;
}

function update ($model ,$data_to_update , $where=array())
{
     $model::where($where)->update($data_to_update);

}

function insert ($model ,$arrayToInsert=array())
{
   $flag=  $model::create($arrayToInsert);
   return $flag;

}
function delete ($model ,$where=array())
{
 $model::where($where)->delete();
}

function get_sum_where ($model ,$field_name , $where=array())
{
     $sum = $model::where($where)->sum($field_name);
     return $sum;

}

function get_user_shift($model1,$model2,$model3){
    $com_code=auth()->user()->com_code;
    $data = $model1::select("treasury_id","shift_code")->where(["com_code"=>$com_code,"admin_id"=>auth()->user()->id,"is_finished"=>0])->first();
     if(!empty($data)){
        $data['treasuries_balance'] = $model2::where(['admins_shifts_code'=>$data['shift_code'],'com_code'=>$com_code])->sum("money");
        $data['tresuries_name'] =  $model3::where(['com_code'=>$com_code,'active'=>1,'id'=>$data['treasury_id']])->value("name");
    }
    return $data;
}

function get_current_balance($account_number=null,$AccountModel=null , $Supplier_with_orderModel=null , $Treasury_TransactionModel=null,$SupllierModel=null ,$returnFlag=false){
    $com_code=auth()->user()->com_code;  
        // effect supplier balance رصيد المورد اول المدة 
    $SupplierAccountStartBalance= $AccountModel::select('start_balance','account_type')->where(['com_code'=>$com_code,
    'account_number'=>$account_number,'active'=>1])->first();
    if($SupplierAccountStartBalance['account_type'] == account_type::Supplier){
         // فواتير المشتريات والمرتجعات المورد
         $money_for_supplier_bill_account = $Supplier_with_orderModel::where(['com_code'=>$com_code, 'account_number'=>$account_number])
         ->sum("money_for_account");
          
         // حركة الخزن للمورد
         $money_for_supplier_treasuries_transactions =  $Treasury_TransactionModel::where(['com_code'=>$com_code, 'account_number'=>$account_number,'is_account'=>transaction_type::account])
         ->sum("money_for_account");

         $final_account_balance =  $SupplierAccountStartBalance['start_balance']+$money_for_supplier_bill_account+$money_for_supplier_treasuries_transactions;
         // تحديث الرصيد الحالى على حساب المورد فالشجرة المحاسبية
         $dataUpdateAccount['current_balance'] =  $final_account_balance;
         $AccountModel::where(['com_code'=>$com_code,
         'account_number'=>$account_number,'active'=>1])->update($dataUpdateAccount);

        // تحديث الرصيد الحالى للمورد في جدول الموردين
         $dataUpdatesupplietbalance['current_balance'] =  $final_account_balance;
         $SupllierModel::where(['com_code'=>$com_code,
         'account_number'=>$account_number,'active'=>1])->update($dataUpdatesupplietbalance);

         if($returnFlag){
            return  $final_account_balance;
         }
    }

}


function refresh_cost_price($ItemCardModel = null,$item_code=null,$uom_type=null , $unit_price=null,$does_has_retailunit=null,$retail_uom_quantityToParent=null,){
    $com_code=auth()->user()->com_code;  
    if($uom_type==uom_type::main_uom){ // اذا كان الصنف وحدة اب
        $dataupdateItemCosts['cost_price'] = $unit_price;
        if($does_has_retailunit==ItemsCard::has_retailunit){
            $dataupdateItemCosts['retail_cost_price']= $unit_price / $retail_uom_quantityToParent; 

        }
    }else{ // اذا كان الصنف وحدة تجزئة
        $dataupdateItemCosts['cost_price'] = $unit_price * $retail_uom_quantityToParent ;
        $dataupdateItemCosts['retail_cost_price'] = $unit_price; 

    }
    $ItemCardModel::where(['com_code'=>$com_code,'item_code'=>$item_code])->update($dataupdateItemCosts);



}


function refresh_item_qty($batchModel=null,$field_name=null,$does_has_retailunit = null,$retail_uom_quantityToParent=null,$ItemCardModel=null,$item_code=null)
{
    $com_code=auth()->user()->com_code;  
    $items_batches_quantity= $batchModel::where(['com_code'=>$com_code,'item_code'=>$item_code])->sum($field_name);
    if($does_has_retailunit== ItemsCard::has_retailunit){
        $all_retail_quantity = $items_batches_quantity *  $retail_uom_quantityToParent;
        $ParentUomQuantity=intdiv($all_retail_quantity,$retail_uom_quantityToParent);
        $UpdateItemCardQuantity['qty']=$ParentUomQuantity;
        $UpdateItemCardQuantity['qty_retail']=fmod($all_retail_quantity,$retail_uom_quantityToParent);
        $UpdateItemCardQuantity['qty_all_retails'] = $all_retail_quantity;

        }else{
        $UpdateItemCardQuantity['qty']=$items_batches_quantity;

        }
        $ItemCardModel::where(['com_code'=>$com_code,'item_code'=>$item_code])->update($UpdateItemCardQuantity);

}






//------------------------------------------------------------------------------
/*
 * Handle Endpoint Errors Function 
 */

function handleError($response)
{

    $json = json_decode($response);
    if (isset($json->IsSuccess) && $json->IsSuccess == true) {
        return null;
    }

    //Check for the errors
    if (isset($json->ValidationErrors) || isset($json->FieldsErrors)) {
        $errorsObj = isset($json->ValidationErrors) ? $json->ValidationErrors : $json->FieldsErrors;
        $blogDatas = array_column($errorsObj, 'Error', 'Name');

        $error = implode(', ', array_map(function ($k, $v) {
            return "$k: $v";
        }, array_keys($blogDatas), array_values($blogDatas)));
    } else if (isset($json->Data->ErrorMessage)) {
        $error = $json->Data->ErrorMessage;
    }

    if (empty($error)) {
        $error = (isset($json->Message)) ? $json->Message : (!empty($response) ? $response : 'API key or API URL is not correct');
    }

    return $error;
}