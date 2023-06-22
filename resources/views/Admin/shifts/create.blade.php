@extends('layouts.admin')
@section('title')
شفتات الخزن
@endsection
@section('contentheader')
حركة الخزنية
@endsection
@section('contentheaderlink')
<a href="{{ route('shifts.index') }}"> شفتات الخزن </a>
@endsection
@section('contentheaderactive')
استلام شفت جديد
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center"> استلام خزنة لشفت جديد </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('shifts.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label> بيانات الخزن المضافة لصلاحياتي</label>
                        <select name="treasuries_id" id="treasuries_id" class="form-control ">
                            <option selected value=""> من فضلك اختر الخزنة لاستلامها وبدء الشفت </option>
                            @if (@isset($admins_treasuries) && !@empty($admins_treasuries))
                            @foreach ($admins_treasuries as $info )
                            <option value="{{ $info->treasury_id }}" @if ($info->available == false) disabled @endif>
                                {{ $info->treasuries_name }} @if ($info->available == false) (عفوا الخزنة قيد الاستخدام
                                من قبل شخص اخر ) @endif </option>
                            @endforeach
                            @endif
                        </select>
                        @error('treasuries_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary addnewshift btn-sm">
                            إضافة
                        </button>
                        <a href="{{ route('shifts.index') }}" class="btn btn-sm btn-danger">الغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
<script src="{{ asset('assets/admin/js/admin_shift.js') }}"></script>
@endsection