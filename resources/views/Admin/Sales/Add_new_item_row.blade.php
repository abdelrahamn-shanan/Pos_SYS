<tr>
    <td>{{ $received_data['store_name'] }}
        <input type="hidden" name="itemTotalArray[]" class="itemTotalArray"
            value="{{$received_data['item_total_price']}}">

        <input type="hidden" name="store_idArray[]" class="store_idArray" value="{{$received_data['store_id']}}">

        <input type="hidden" name="Sale_typeArray[]" class="Sale_typeArray" value="{{$received_data['Sale_type']}}">

        <input type="hidden" name="item_codeArray[]" class="item_codeArray" value="{{$received_data['item_code']}}">

        <input type="hidden" name="uom_idArray[]" class="uom_idArray" value="{{$received_data['uom_id']}}">

        <input type="hidden" name="inv_itemcard_batches_autoserialArray[]" class="inv_itemcard_batches_autoserialArray"
            value="{{$received_data['inv_itemcard_batches_autoserial']}}">

        <input type="hidden" name="item_qtyArray[]" class="item_qtyArray" value="{{$received_data['item_qty']}}">

        <input type="hidden" name="item_priceArray[]" class="item_priceArray" value="{{$received_data['item_price']}}">

        <input type="hidden" name="is_bonus_or_normalArray[]" class="is_bonus_or_normalArray"
            value="{{$received_data['is_bonus_or_normal']}}">

        <input type="hidden" name="isparentuomArray[]" class="isparentuomArray"
            value="{{$received_data['isparentuom']}}">
    </td>

    <td>{{ $received_data['sales_item_type_name'] }}</td>
    <td>{{ $received_data['item_code_name'] }}</td>
    <td>{{ $received_data['uom_id_name'] }}</td>
    <td>{{ $received_data['item_price']*1 }}</td>
    <td>{{ $received_data['item_qty']*1 }}</td>
    <td>{{ $received_data['item_total_price']*1 }}</td>
    <td>
        <button class="btn remove_current_row btn-sm btn-danger">حذف</button>
    </td>
</tr>