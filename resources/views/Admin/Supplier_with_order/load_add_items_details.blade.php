@if(!@empty($parent_bill_data))
@if($parent_bill_data['is_approved']==0)
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label> الأصناف</label>
            <select name="item_code_add" id="item_code_add" class="form-control select2">
                <option value="">اختر الصنف</option>
                @if (@isset($itemcards) && !@empty($itemcards))
                @foreach ($itemcards as $info )
                <option data-type="{{$info->item_type}}" value="{{ $info->item_code }}">
                    {{ $info->name }}
                </option>
                @endforeach
                @endif
            </select>
            @error('item_code_add')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-md-4 relateditemcard" id="UomDivId">
    </div>

    <div class="col-md-4 relateditemcard" style="display: none;">

        <div class="form-group">
            <label> الكمية المستلمة </label>
            <input oninput="this.value=this.value.replace(/[^0-9]/g,'');" name="quantity-add" id="quantity-add"
                class="form-control" value="" placeholder="ادخل اسم الكمية المستلمة"
                oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')" onchange="try{setCustomValidity('')}catch(e){}">
            @error('quantity-add')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-md-4 relateditemcard" style="display: none;">

        <div class="form-group">
            <label> سعر الوحده </label>
            <input oninput="this.value=this.value.replace(/[^0-9]/g,'');" name="price-add" id="price-add"
                class="form-control" value="" placeholder="ادخل  سعر الوحدة"
                oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')" onchange="try{setCustomValidity('')}catch(e){}">
            @error('price-add')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4 related-date" style="display: none;">

        <div class="form-group">
            <label> تاريخ الانتاج </label>
            <input type="date" name="production-date" id="production-date" class="form-control" value=""
                oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')" onchange="try{setCustomValidity('')}catch(e){}">
            @error('production-date')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4 related-date" style="display: none;">

        <div class="form-group">
            <label> تاريخ انتهاء الصلاحية </label>
            <input type="date" name="expire-date" id="expire-date" class="form-control" value=""
                oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')" onchange="try{setCustomValidity('')}catch(e){}">
            @error('expire-date')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-md-4 relateditemcard" style="display: none;">

        <div class="form-group">
            <label> الإجمالي </label>
            <input readOnly name="total-add" id="total-add" class="form-control" value=""
                oninvalid="setCustomValidity('من فضلك ادخل هذا الحقل')" onchange="try{setCustomValidity('')}catch(e){}">
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group text-center">
            <button type="button" class="btn btn-sm btn-danger" id="AddToBill">اضف للفاتورة </button>
        </div>

    </div>


</div>

@else
<div class="alert alert-danger">
    عفوا غير قادر الي الوصول للبيانات المطلوبة
</div>
@endif

@else
<div class="alert alert-danger">
    عفوا لايمكت تحديث فاتورة معتمدة ومؤرشفة
</div>
@endif
