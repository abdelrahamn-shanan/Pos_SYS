@extends('layouts.admin')
@section('title')
المشتريات
@endsection
@section("css")
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

@endsection
@section('contentheader')
حركات مخزنية
@endsection
@section('contentheaderlink')
<a href="{{ route('Supplier_with_orders.index') }}"> فواتير المشتريات </a>
@endsection
@section('contentheaderactive')
اضافة
@endsection
@section('content')


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center"> اضافة فاتورة مشتريات من مورد </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <form action="{{ route('Supplier_with_orders.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label> تاريخ الفاتورة</label>
                        <input name="order_date" id="order_date" type="date" value="@php echo date(" yyyy-mm-dd");
                            @endphp" class="form-control" value="{{ old('order_date') }}">
                        @error('order_date')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label> رقم فاتورة المشتريات</label>
                        <input name="Doc_no" id="Doc_no" type="text" class="form-control" value="{{ old('Doc_no') }}">
                        @error('Doc_no')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label> فئة المورد</label>
                        <select name="SupplierCode" id="SupplierCode" class="form-control select2">
                            <option value="">اختر المورد</option>
                            @if (@isset($suppliers) && !@empty($suppliers))
                            @foreach ($suppliers as $info )
                            <option @if(old('Supplier_code')==$info->Supplier_code) selected="selected" @endif
                                value="{{ $info->Supplier_code }}"> {{ $info->name }} </option>
                            @endforeach
                            @endif
                        </select>
                        @error('SupplierCode')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label> نوع الفاتورة</label>
                        <select name="bill_type" id="bill_type" class="form-control">
                            <option @if(old('bill_type')==1) selected="selected" @endif value="1"> كاش</option>
                            <option @if(old('bill_type')==2 ) selected="selected" @endif value="2">آجل</option>
                        </select>
                        @error('bill_type')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label> بيانات المخازن</label>
                        <select name="store_id" id="store_id" class="form-control select2">
                            <option value=""> اختر المخزن المستلم للفاتورة</option>
                            @if (@isset($stores) && !@empty($stores))
                            @foreach ($stores as $info )
                            <option @if(old('store_id')==$info->id) selected="selected" @endif value="{{ $info->id }}">
                                {{ $info->name }} </option>
                            @endforeach
                            @endif
                        </select>
                        @error('store_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label> ملاحظات</label>
                        <input name="notes" id="notes" class="form-control" value="{{ old('notes') }}">
                        @error('notes')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-sm"> اضافة</button>
                        <a href="{{ route('Supplier_with_orders.index') }}" class="btn btn-sm btn-danger">الغاء</a>

                    </div>


                </form>



            </div>




        </div>
    </div>
</div>
</div>

@endsection

@section("script")

<script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"> </script>
<script>
//Initialize Select2 Elements
$('.select2').select2({
    theme: 'bootstrap4'
});
</script>
@endsection