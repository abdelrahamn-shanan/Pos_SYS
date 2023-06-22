<div class="form-group">
    <label> وحدة البيع</label>
    <select id="uom_id" class="form-control select2" style="width: 100%;">
        @if (@isset($item_card_data) && !@empty($item_card_data))
        @if($item_card_data['does_has_retailunit']==1)
        <option selected data-isparentuom="1" value="{{ $item_card_data['uom_id'] }}">
            {{ $item_card_data['parent_uom_name']  }}
            (وحده اب) </option>
        <option data-isparentuom="0" value="{{ $item_card_data['retail_uom_id'] }}">
            {{ $item_card_data['retail_uom_name']  }}
            (وحده تجزئة) </option>
        @else
        <option data-isparentuom="1" value="{{ $item_card_data['uom_id'] }}"> {{ $item_card_data['parent_uom_name']  }}
            (وحده اب) </option>
        @endif

        @endif
    </select>
</div>