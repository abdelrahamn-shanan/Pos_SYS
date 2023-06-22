@if (@isset($item_card_data) && !@empty($item_card_data))
<div class="form-group">
    <label> الكميات بالمخزن المحدد</label>
    <select id="inv_itemcard_batches_autoserial" class="form-control " style="width: 100%;">
        @if (@isset($Items_batches_qty) && !@empty($Items_batches_qty) && count($Items_batches_qty)>0)
        <!-- اذا كان الوحده اب  -->
        @if($uom_data['is_master']==1)
        @foreach ( $Items_batches_qty as $info )
        @if($item_card_data['item_type']==2)
        <!-- لو كان بتواريخ استهلاكي -->
        <option data-qunatity="{{ $info->quantity }}" value="{{ $info->auto_serial }}"> عدد {{ $info->quantity*(1) }}
            {{ $uom_data['name'] }} انتاج {{ $info->production_date }} بتكلفة {{ $info->unit_cost_price*1 }} للوحدة
        </option>
        @else
        <option data-qunatity="{{ $info->quantity }}" value="{{ $info->auto_serial }}"> عدد {{ $info->quantity*(1) }}
            {{ $uom_data['name'] }} بتكلفة {{ $info->unit_cost_price*1 }} للوحدة </option>
        @endif
        @endforeach
        @else
        <!-- لوكان مختار التجزئة يبقي لازن نحول الكميات الاب بالتجزئة -->
        @foreach ( $Items_batches_qty as $info )
        @php
        $quantity= $info->quantity * $item_card_data['retail_uom_quantityToParent'];
        $unit_cost_price= round($info->unit_cost_price/$item_card_data['retail_uom_quantityToParent'],2);
        @endphp
        @if($item_card_data['item_type']==2)
        //لو كان بتواريخ استهلاكي
        <option data-qunatity="{{ $quantity }}" value="{{ $info->auto_serial }}"> عدد {{ $quantity*(1) }}
            {{ $uom_data['name'] }} انتاج {{ $info->production_date }} بتكلفة {{ $unit_cost_price*1 }} للوحدة </option>
        @else
        <option data-qunatity="{{ $quantity }}" value="{{ $info->auto_serial }}"> عدد {{ $quantity * (1) }}
            {{ $uom_data['name'] }} بتكلفة {{ $unit_cost_price*1 }} للوحددة </option>
        @endif
        @endforeach
        @endif
        @else
        <option value="">عفوا لاتوجد باتشات</option>
        @endif
    </select>
</div>
@endif