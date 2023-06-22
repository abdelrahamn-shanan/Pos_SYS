<div class="col-md-6">
    <div class="form-group">
        <label> خزنة الصرف </label>
        <select id="treasuries_id" name="treasuries_id" class="form-control">
            @if(!@empty($current_user_shift))
            <option selected value="{{ $current_user_shift['treasury_id']  }}">
                {{ $current_user_shift['tresuries_name'] }}
            </option>
            @else
            <option value=""> عفوا لاتوجد خزنة لديك الان</option>
            @endif
        </select>
    </div>
</div>

<div class="col-md-6">
    <div class=" form-group">
        <label> الرصيد المتاح بالخزنة </label>
        <input readonly name="treasuries_balance" id="treasuries_balance" class="form-control"
            @if(!@empty($current_user_shift)) value="{{$current_user_shift['treasuries_balance']*1 }}" @else value="0"
            @endif>
    </div>
</div>