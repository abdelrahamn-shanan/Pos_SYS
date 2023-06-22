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
تعديل
@endsection
@section('content')


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title card_title_center"> تعديل فاتورة مشتريات من مورد </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if(!@empty($data))

                <form action="{{ route('Supplier_with_orders.update',$data['id']) }}" method="post">
                    <input name="id" value="{{$data['id']}}" type="hidden">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label> تاريخ الفاتورة</label>
                        <input name="order_date" id="order_date" type="date"
                            value="{{ old('order_date',$data['order_date']) }}" class="form-control"
                            value="{{ old('order_date') }}">
                        @error('notes')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label> رقم الفاتورة المسجل بأصل فاتورة المشتريات</label>
                        <input name="Doc_no" id="Doc_no" type="text" class="form-control"
                            value="{{ old('Doc_no',$data['Doc_no']) }}">
                        @error('DOC_NO')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label> بيانات الموردين</label>
                        <select name="Supplier_code" id="Supplier_code" class="form-control select2">
                            <option value="">اختر المورد</option>
                            @if (@isset($suppliers) && !@empty($suppliers))
                            @foreach ($suppliers as $info )
                            <option @if(old('Supplier_code',$data['Supplier_code'])==$info->Supplier_code)
                                selected="selected" @endif value="{{ $info->Supplier_code }}"> {{ $info->name }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                        @error('Supplier_code')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label> نوع الفاتورة</label>
                        <select name="bill_type" id="bill_type" class="form-control">
                            <option @if(old('bill_type',$data['bill_type'])==1) selected="selected" @endif value="1">
                                كاش</option>
                            <option @if(old('bill_type',$data['bill_type'])==2 ) selected="selected" @endif value="2">
                                اجل</option>
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
                            <option @if(old('store_id',$data['store_id'])==$info->id) selected="selected" @endif
                                value="{{ $info->id }}"> {{ $info->name }} </option>
                            @endforeach
                            @endif
                        </select>
                        @error('store_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label> ملاحظات</label>
                        <input name="notes" id="notes" class="form-control" value="{{ old('notes',$data['notes']) }}">
                        @error('notes')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-sm"> تعديل</button>
                        <a href="{{ route('Supplier_with_orders.index') }}" class="btn btn-sm btn-danger">الغاء</a>

                    </div>


                </form>



                @else
                <div class="alert alert-danger">
                    عفوا لاتوجد بيانات لعرضها !!
                </div>
                @endif








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